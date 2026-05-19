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
        $idKyThi = (int) $request->query('id_kythi', 0);

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

        // Kiểm tra học sinh đã làm chưa
        $daDone = \Illuminate\Support\Facades\DB::selectOne(
            "SELECT ID_DiemSo FROM Diem_so WHERE ID_MaKyThi = ? AND ID_User = ? LIMIT 1",
            [$idKyThi, $studentId]
        );
        if ($daDone) {
            return redirect()->route('student.ky-thi')->with('error', 'Bạn đã nộp bài cho kỳ thi này.');
        }

        $cauHoi = $this->examService->getCauHoiDeThi((int) $kythi['ID_MaDeThi']);

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

        // Ngăn nộp bài 2 lần
        $daDone = \Illuminate\Support\Facades\DB::selectOne(
            "SELECT ID_DiemSo FROM Diem_so WHERE ID_MaKyThi = ? AND ID_User = ? LIMIT 1",
            [$idKyThi, $studentId]
        );
        if ($daDone) {
            return redirect()->route('student.ky-thi')->with('error', 'Bạn đã nộp bài cho kỳ thi này.');
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
}
