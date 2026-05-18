<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function dashboard(): View
    {
        return view('student.HocSinh_TrangChu');
    }

    public function lopHoc(Request $request): View
    {
        $studentId = $request->session()->get('auth.id');
        $lopHocs = DB::select(
            "SELECT l.*, k.Ten_KhoiLop, m.Ten_MonHoc, u.HoVaTen_User as ten_giao_vien
             FROM Lop_hoc l
             JOIN Lop_hoc_ThanhVien lv ON l.ID_LopHoc = lv.ID_LopHoc
             JOIN Khoi_lop k ON l.ID_KhoiLop = k.ID_KhoiLop
             JOIN Mon_Hoc m ON l.ID_MonHoc = m.ID_MonHoc
             JOIN `User` u ON l.ID_Teacher = u.ID_User
             WHERE lv.ID_Student = ?
             ORDER BY l.ID_LopHoc",
            [$studentId]
        );
        return view('student.HocSinh_DanhSachLopHoc', compact('lopHocs'));
    }

    public function kyThi(Request $request): View
    {
        $studentId = $request->session()->get('auth.id');
        $kyThis = DB::select(
            "SELECT DISTINCT kt.*, m.Ten_MonHoc
             FROM Ky_thi kt
             JOIN Mon_Hoc m ON kt.ID_MonHoc = m.ID_MonHoc
             JOIN Lop_hoc_ThanhVien lv ON kt.ID_LopHoc = lv.ID_LopHoc
             WHERE lv.ID_Student = ?
             ORDER BY kt.ThoiGianBatDau_KyThi",
            [$studentId]
        );
        return view('student.HocSinh_DanhSachKyThi', compact('kyThis'));
    }

    public function lichSuLamBai(Request $request): View
    {
        $studentId = $request->session()->get('auth.id');
        $lichSus = DB::select(
            "SELECT ds.*, kt.Ten_KyThi, dt.TenDeThi, m.Ten_MonHoc
             FROM Diem_so ds
             JOIN Ky_thi kt ON ds.ID_MaKyThi = kt.ID_KyThi
             JOIN De_Thi dt ON ds.ID_MaDeThi = dt.ID_MaDeThi
             JOIN Mon_Hoc m ON kt.ID_MonHoc = m.ID_MonHoc
             WHERE ds.ID_User = ?
             ORDER BY ds.ThoiGianBatDau_DiemSo DESC",
            [$studentId]
        );
        return view('student.HocSinh_LichSuLamBai', compact('lichSus'));
    }

    public function diemDanh(Request $request): View
    {
        $studentId = $request->session()->get('auth.id');
        $diemDanhs = DB::select(
            "SELECT dd.*, l.TenLopHoc, m.Ten_MonHoc, u.HoVaTen_User as ten_giao_vien
             FROM Diem_danh dd
             JOIN Lop_hoc l ON dd.ID_LopHoc = l.ID_LopHoc
             JOIN Lop_hoc_ThanhVien lv ON l.ID_LopHoc = lv.ID_LopHoc
             JOIN Mon_Hoc m ON l.ID_MonHoc = m.ID_MonHoc
             JOIN `User` u ON l.ID_Teacher = u.ID_User
             WHERE lv.ID_Student = ?
             ORDER BY dd.NgayHoc_DiemDanh DESC",
            [$studentId]
        );
        return view('student.HocSinh_LichSuDiemDanh', compact('diemDanhs'));
    }

    public function thongTin(Request $request): View
    {
        $studentId = $request->session()->get('auth.id');
        $student = DB::selectOne(
            "SELECT u.ID_User, u.HoVaTen_User, u.EmailCaNhan_User, u.SoDienThoai_User,
                    u.NgayThangNamSinh_User, u.PhanQuyen_User, u.TrangThaiHoatDong_User,
                    u.PhuTrachKhoi_User, u.PhuTrachMon_User, u.NgayTaoTaiKhoan_User,
                    COUNT(DISTINCT ds.ID_DiemSo) as so_ky_thi
             FROM `User` u
             LEFT JOIN Diem_so ds ON u.ID_User = ds.ID_User
             WHERE u.ID_User = ?
             GROUP BY u.ID_User, u.HoVaTen_User, u.EmailCaNhan_User, u.SoDienThoai_User,
                      u.NgayThangNamSinh_User, u.PhanQuyen_User, u.TrangThaiHoatDong_User,
                      u.PhuTrachKhoi_User, u.PhuTrachMon_User, u.NgayTaoTaiKhoan_User",
            [$studentId]
        );
        return view('student.HocSinh_ThongTinCaNhan', compact('student'));
    }

    public function xepHang(): View
    {
        return view('student.HocSinh_XepHang');
    }
}
