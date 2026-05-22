<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\StudentExamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentExamController extends Controller
{
    public function __construct(private readonly StudentExamService $examService) {}

    public function loadExam(Request $request): View|RedirectResponse
    {
        $idKyThi = (int) $request->query('id_kythi', '0');

        if (!$idKyThi) {
            return redirect()->route('student.ky-thi')->with('error', 'Kỳ thi không hợp lệ.');
        }

        $kythi = $this->examService->getThongTinKyThi($idKyThi);
        if (!$kythi) {
            return redirect()->route('student.ky-thi')->with('error', 'Kỳ thi không tồn tại.');
        }

        // Kiểm tra kỳ thi đang trong thời gian mở
        $now = now();
        if ($kythi['ThoiGianBatDau_KyThi'] && $now->lt($kythi['ThoiGianBatDau_KyThi'])) {
            return redirect()->route('student.ky-thi')->with('error', 'Kỳ thi chưa bắt đầu.');
        }
        if ($kythi['ThoiGianKetThuc_KyThi'] && $now->gt($kythi['ThoiGianKetThuc_KyThi'])) {
            return redirect()->route('student.ky-thi')->with('error', 'Kỳ thi đã kết thúc.');
        }

        // Kiểm tra học sinh thuộc lớp của kỳ thi
        $studentId = $request->session()->get('auth.id');
        $enrolled = \Illuminate\Support\Facades\DB::selectOne(
            "SELECT 1 FROM Lop_hoc_ThanhVien WHERE ID_LopHoc = ? AND ID_Student = ? LIMIT 1",
            [$kythi['ID_LopHoc'], $studentId]
        );
        if (!$enrolled) {
            return redirect()->route('student.ky-thi')->with('error', 'Bạn không có quyền tham gia kỳ thi này.');
        }

        $cauHoi = $this->examService->getCauHoiDeThi(
            (int) $kythi['ID_MaDeThi'],
            (int) ($kythi['SoCauHoiTracNghiem4PhuongAn_KyThi']  ?? 0),
            (int) ($kythi['SoCauHoiTracNghiemDungSai_KyThi']    ?? 0),
            (int) ($kythi['SoCauHoiTracNghiemTraLoiNgan_KyThi'] ?? 0)
        );

        return view('student.HocSinh_ThamGiaThi', [
            'thong_tin'  => $kythi,
            'cau_hoi'    => $cauHoi,
            'time_start' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function submitExam(Request $request): RedirectResponse
    {
        $studentId = $request->session()->get('auth.id');
        $idKyThi   = (int) $request->input('id_kythi', 0);

        if (!$idKyThi) {
            return redirect()->route('student.ky-thi')->with('error', 'Dữ liệu nộp bài không hợp lệ.');
        }

        $kythi = $this->examService->getThongTinKyThi($idKyThi);
        if (!$kythi) {
            return redirect()->route('student.ky-thi')->with('error', 'Kỳ thi không tồn tại.');
        }

        // Kiểm tra thời gian thi còn hiệu lực
        $now = now();
        if ($kythi['ThoiGianBatDau_KyThi'] && $now->lt($kythi['ThoiGianBatDau_KyThi'])) {
            return redirect()->route('student.ky-thi')->with('error', 'Kỳ thi chưa bắt đầu.');
        }
        if ($kythi['ThoiGianKetThuc_KyThi'] && $now->gt($kythi['ThoiGianKetThuc_KyThi'])) {
            return redirect()->route('student.ky-thi')->with('error', 'Kỳ thi đã kết thúc.');
        }

        // Kiểm tra học sinh thuộc lớp của kỳ thi
        $enrolled = \Illuminate\Support\Facades\DB::selectOne(
            "SELECT 1 FROM Lop_hoc_ThanhVien WHERE ID_LopHoc = ? AND ID_Student = ? LIMIT 1",
            [$kythi['ID_LopHoc'], $studentId]
        );
        if (!$enrolled) {
            return redirect()->route('student.ky-thi')->with('error', 'Bạn không có quyền tham gia kỳ thi này.');
        }

        try {
            $this->examService->chamDiemVaLuu(
                $studentId,
                $kythi,
                $request->input('answers', []),
                $request->input('time_start', now()->format('Y-m-d H:i:s')),
                (int) $request->input('time_spent', 0)
            );
            return redirect()->route('student.lich-su-bai')->with('success', 'Nộp bài thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function reviewExam(Request $request, int $id): View|RedirectResponse
    {
        $studentId = $request->session()->get('auth.id');

        $diemSo = \Illuminate\Support\Facades\DB::selectOne(
            "SELECT ds.*, kt.Ten_KyThi, m.Ten_MonHoc,
                    kt.SoCauHoiTracNghiem4PhuongAn_KyThi as so_4pa,
                    kt.SoCauHoiTracNghiemDungSai_KyThi as so_ds,
                    kt.SoCauHoiTracNghiemTraLoiNgan_KyThi as so_ngan,
                    kt.PhanBoDiemTracNghiem4PhuongAn_KyThi as diem_4pa_max,
                    kt.PhanBoDiemTracNghiemDungSai_KyThi as diem_ds_max,
                    kt.PhanBoDiemTracNghiemTraLoiNgan_KyThi as diem_ngan_max,
                    kt.CheDo_XemKetQua_KyThi as che_do_xem
             FROM Diem_so ds
             JOIN Ky_thi kt ON kt.ID_KyThi = ds.ID_MaKyThi
             JOIN Mon_Hoc m ON m.ID_MonHoc = kt.ID_MonHoc
             WHERE ds.ID_DiemSo = ? AND ds.ID_User = ?",
            [$id, $studentId]
        );

        if (!$diemSo) {
            return redirect()->route('student.lich-su-bai')->with('error', 'Không tìm thấy bài thi.');
        }

        $mode = (int) ($diemSo->che_do_xem ?? 1);
        if ($mode === 3) {
            return redirect()->route('student.lich-su-bai')
                ->with('error', 'Giáo viên không cho phép xem lại bài làm của kỳ thi này.');
        }

        $lichSu = json_decode($diemSo->LichSuLamBai ?? '{}', true) ?? [];

        $ids1 = array_map('intval', array_keys($lichSu['phan1'] ?? []));
        $ids2 = array_map('intval', array_keys($lichSu['phan2'] ?? []));
        $ids3 = array_map('intval', array_keys($lichSu['phan3'] ?? []));

        $cau4PA  = $this->examService->getCauHoiForReview4PA($ids1);
        $cauDS   = $this->examService->getCauHoiForReviewDS($ids2);
        $cauNgan = $this->examService->getCauHoiForReviewNgan($ids3);

        return view('student.HocSinh_XemLaiBai', [
            'diem_so'  => $diemSo,
            'lich_su'  => $lichSu,
            'cau_4pa'  => $cau4PA,
            'cau_ds'   => $cauDS,
            'cau_ngan' => $cauNgan,
            'ids1'     => $ids1,
            'ids2'     => $ids2,
            'ids3'     => $ids3,
            'mode'     => $mode,  // 1=full, 2=no answers, 3=blocked (already redirected)
        ]);
    }
}
