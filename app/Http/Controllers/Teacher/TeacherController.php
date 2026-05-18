<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function dashboard(): View
    {
        return view('teacher.GiaoVien_TrangChu');
    }

    public function lopHoc(Request $request): View
    {
        $teacherId = $request->session()->get('auth.id');
        $lopHocs = DB::select(
            "SELECT l.ID_LopHoc, l.TenLopHoc, l.NamHoc,
                    l.ID_KhoiLop, l.ID_MonHoc, l.ID_Teacher, l.ID_Student,
                    k.Ten_KhoiLop, m.Ten_MonHoc,
                    COUNT(lv.ID_Student) as so_hoc_sinh
             FROM Lop_hoc l
             JOIN Khoi_lop k ON l.ID_KhoiLop = k.ID_KhoiLop
             JOIN Mon_Hoc m ON l.ID_MonHoc = m.ID_MonHoc
             LEFT JOIN Lop_hoc_ThanhVien lv ON l.ID_LopHoc = lv.ID_LopHoc
             WHERE l.ID_Teacher = ?
             GROUP BY l.ID_LopHoc, l.TenLopHoc, l.NamHoc,
                      l.ID_KhoiLop, l.ID_MonHoc, l.ID_Teacher, l.ID_Student,
                      k.Ten_KhoiLop, m.Ten_MonHoc
             ORDER BY l.ID_LopHoc",
            [$teacherId]
        );
        return view('teacher.GiaoVien_DanhSachLopHoc', compact('lopHocs'));
    }

    public function chuDe(Request $request): View
    {
        $teacherId = $request->session()->get('auth.id');
        $chuDes = DB::select(
            "SELECT cd.*, m.Ten_MonHoc, k.Ten_KhoiLop,
                    (SELECT COUNT(*) FROM Cau_hoi_trac_nghiem_4_phuong_an WHERE ID_ChuDe = cd.ID_ChuDe)
                  + (SELECT COUNT(*) FROM Cau_hoi_trac_nghiem_dung_sai WHERE ID_ChuDe = cd.ID_ChuDe)
                  + (SELECT COUNT(*) FROM Cau_hoi_tra_loi_ngan WHERE ID_ChuDe = cd.ID_ChuDe) as tong_cau_hoi
             FROM Chu_De cd
             JOIN Mon_Hoc m ON cd.ID_MonHoc = m.ID_MonHoc
             JOIN Khoi_lop k ON cd.ID_KhoiLop = k.ID_KhoiLop
             WHERE cd.ID_NguoiTao = ?
             ORDER BY cd.ID_ChuDe",
            [$teacherId]
        );
        return view('teacher.GiaoVien_DanhSachChuDe', compact('chuDes'));
    }

    public function deThi(Request $request): View
    {
        $teacherId = $request->session()->get('auth.id');
        $deThis = DB::select(
            "SELECT dt.*, m.Ten_MonHoc, k.Ten_KhoiLop,
                    (SELECT COUNT(*) FROM De_Thi_Chi_Tiet WHERE ID_MaDeThi = dt.ID_MaDeThi) as tong_cau_hoi
             FROM De_Thi dt
             JOIN Mon_Hoc m ON dt.ID_MaMon = m.ID_MonHoc
             JOIN Khoi_lop k ON dt.ID_MaKhoi = k.ID_KhoiLop
             WHERE dt.ID_NguoiTao = ?
             ORDER BY dt.ID_MaDeThi",
            [$teacherId]
        );
        return view('teacher.GiaoVien_DanhSachDeThi', compact('deThis'));
    }

    public function tracNghiem4PA(Request $request): View
    {
        $teacherId = $request->session()->get('auth.id');
        $cauHois = DB::select(
            "SELECT q.*, cd.NoiDung_ChuDe, m.Ten_MonHoc
             FROM Cau_hoi_trac_nghiem_4_phuong_an q
             JOIN Chu_De cd ON q.ID_ChuDe = cd.ID_ChuDe
             JOIN Mon_Hoc m ON q.ID_MonHoc = m.ID_MonHoc
             WHERE cd.ID_NguoiTao = ?
             ORDER BY q.ID_TracNghiem4PhuongAn",
            [$teacherId]
        );
        return view('teacher.GiaoVien_TracNghiem4PA', compact('cauHois'));
    }

    public function tracNghiemDungSai(Request $request): View
    {
        $teacherId = $request->session()->get('auth.id');
        $cauHois = DB::select(
            "SELECT q.*, cd.NoiDung_ChuDe, m.Ten_MonHoc
             FROM Cau_hoi_trac_nghiem_dung_sai q
             JOIN Chu_De cd ON q.ID_ChuDe = cd.ID_ChuDe
             JOIN Mon_Hoc m ON q.ID_MonHoc = m.ID_MonHoc
             WHERE cd.ID_NguoiTao = ?
             ORDER BY q.ID_TracNghiemDungSai",
            [$teacherId]
        );
        return view('teacher.GiaoVien_TracNghiemDungSai', compact('cauHois'));
    }

    public function tracNghiemTraLoiNgan(Request $request): View
    {
        $teacherId = $request->session()->get('auth.id');
        $cauHois = DB::select(
            "SELECT q.*, cd.NoiDung_ChuDe, m.Ten_MonHoc
             FROM Cau_hoi_tra_loi_ngan q
             JOIN Chu_De cd ON q.ID_ChuDe = cd.ID_ChuDe
             JOIN Mon_Hoc m ON q.ID_MonHoc = m.ID_MonHoc
             WHERE cd.ID_NguoiTao = ?
             ORDER BY q.ID_TracNghiemTraLoiNgan",
            [$teacherId]
        );
        return view('teacher.GiaoVien_TracNghiemTraLoiNgan', compact('cauHois'));
    }

    public function diemDanh(Request $request): View
    {
        $teacherId = $request->session()->get('auth.id');
        $diemDanhs = DB::select(
            "SELECT dd.*, l.TenLopHoc, m.Ten_MonHoc,
                    u.HoVaTen_User as ten_hoc_sinh,
                    JSON_UNQUOTE(JSON_EXTRACT(dd.ChiTietDiemDanh_DiemDanh, CONCAT('\$.', CAST(u.ID_User AS CHAR)))) as trang_thai_ca_nhan
             FROM Diem_danh dd
             JOIN Lop_hoc l ON dd.ID_LopHoc = l.ID_LopHoc
             JOIN Mon_Hoc m ON l.ID_MonHoc = m.ID_MonHoc
             JOIN Lop_hoc_ThanhVien lv ON l.ID_LopHoc = lv.ID_LopHoc
             JOIN `User` u ON lv.ID_Student = u.ID_User
             WHERE l.ID_Teacher = ?
             ORDER BY dd.NgayHoc_DiemDanh DESC, u.HoVaTen_User",
            [$teacherId]
        );
        return view('teacher.GiaoVien_QuanLyDiemDanh', compact('diemDanhs'));
    }

    public function thongTin(Request $request): View
    {
        $teacherId = $request->session()->get('auth.id');
        $teacher = DB::selectOne(
            "SELECT u.ID_User, u.HoVaTen_User, u.EmailCaNhan_User, u.SoDienThoai_User,
                    u.NgayThangNamSinh_User, u.PhanQuyen_User, u.TrangThaiHoatDong_User,
                    u.PhuTrachKhoi_User, u.PhuTrachMon_User, u.NgayTaoTaiKhoan_User,
                    m.Ten_MonHoc, k.Ten_KhoiLop,
                    COUNT(DISTINCT l.ID_LopHoc) as so_lop
             FROM `User` u
             LEFT JOIN Mon_Hoc m ON u.PhuTrachMon_User = m.ID_MonHoc
             LEFT JOIN Khoi_lop k ON u.PhuTrachKhoi_User = k.ID_KhoiLop
             LEFT JOIN Lop_hoc l ON l.ID_Teacher = u.ID_User
             WHERE u.ID_User = ?
             GROUP BY u.ID_User, u.HoVaTen_User, u.EmailCaNhan_User, u.SoDienThoai_User,
                      u.NgayThangNamSinh_User, u.PhanQuyen_User, u.TrangThaiHoatDong_User,
                      u.PhuTrachKhoi_User, u.PhuTrachMon_User, u.NgayTaoTaiKhoan_User,
                      m.Ten_MonHoc, k.Ten_KhoiLop",
            [$teacherId]
        );
        return view('teacher.GiaoVien_ThongTinCaNhan', compact('teacher'));
    }

    public function doiMatKhau(): View
    {
        return view('teacher.GiaoVien_DoiMatKhau');
    }
}
