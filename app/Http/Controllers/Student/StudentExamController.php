<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StudentExamService;

class StudentExamController extends Controller {
    protected $examService;

    public function __construct(StudentExamService $examService) {
        $this->examService = $examService;
    }

    // Hiển thị danh sách kỳ thi
    public function index(Request $request) {
        $studentId = $request->session()->get('auth.id');
        $kyThiList = $this->examService->getDanhSachKyThiDangMo($studentId);

        // Format lại dữ liệu cho view giống hệt lúc trước
        $formattedList = array_map(function($kt) {
            return [
                'id'       => $kt['ID_KyThi'],
                'ten'      => $kt['Ten_KyThi'],
                'ngaythi'  => date('d/m/Y', strtotime($kt['ThoiGianBatDau_KyThi'])),
                'thoigian' => $kt['ThoiGianLamBai_KyThi'],
                'phan_bo_cau' => $kt['SoCauHoiTracNghiem4PhuongAn_KyThi'] . '|' . $kt['SoCauHoiTracNghiemDungSai_KyThi'] . '|' . $kt['SoCauHoiTracNghiemTraLoiNgan_KyThi'],
                'phan_bo_diem' => $kt['PhanBoDiemTracNghiem4PhuongAn_KyThi'] . '|' . $kt['PhanBoDiemTracNghiemDungSai_KyThi'] . '|' . $kt['PhanBoDiemTracNghiemTraLoiNgan_KyThi'],
            ];
        }, $kyThiList);

        return view('student.HocSinh_DanhSachKyThi', ['kyThiList' => $formattedList]);
    }

    // Hiển thị giao diện làm bài
    public function loadExam(Request $request) {
        $id_kythi = $request->query('id_kythi');
        if (!$id_kythi) return redirect('student.ky-thi')->with('error', 'Không tìm thấy mã kỳ thi.');

        $kythi = $this->examService->getThongTinKyThi($id_kythi);
        if (!$kythi) return redirect('student.ky-thi')->with('error', 'Kỳ thi không tồn tại.');

        $cau_hoi = $this->examService->getCauHoiDeThi($kythi['ID_MaDeThi']);

        return view('student.HocSinh_ThamGiaThi', [
            'thong_tin' => $kythi,
            'cau_hoi' => $cau_hoi,
            'time_start' => now()->format('Y-m-d H:i:s')
        ]);
    }

    // Nộp bài thi
    public function submitExam(Request $request) {
        $studentId = $request->session()->get('auth.id');
        $kythi = $this->examService->getThongTinKyThi($request->id_kythi);

        try {
            $this->examService->chamDiemVaLuu(
                $studentId, 
                $kythi, 
                $request->answers ?? [], 
                $request->time_start, 
                $request->time_spent
            );
            return redirect('/student/danh-sach-ky-thi')->with('success', 'Nộp bài thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
}