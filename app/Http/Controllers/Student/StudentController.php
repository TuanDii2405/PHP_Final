<?php

declare(strict_types=1);

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function dashboard(): View
    {
        $studentId = session('auth.id');

        $thongBaos = DB::select(
            "SELECT DISTINCT tb.*, u.HoVaTen_User as ten_nguoi_gui,
                    k.Ten_KhoiLop, m.Ten_MonHoc
             FROM Thong_bao tb
             JOIN `User` u ON tb.ID_User = u.ID_User
             LEFT JOIN Khoi_lop k ON tb.ID_KhoiLop = k.ID_KhoiLop
             LEFT JOIN Mon_Hoc m ON tb.ID_MonHoc = m.ID_MonHoc
             WHERE
               -- Toàn hệ thống
               (tb.ID_KhoiLop IS NULL AND tb.ID_MonHoc IS NULL)
               -- Chỉ có khối, không có môn
               OR (tb.ID_KhoiLop IS NOT NULL AND tb.ID_MonHoc IS NULL
                   AND tb.ID_KhoiLop IN (
                       SELECT DISTINCT l.ID_KhoiLop FROM Lop_hoc_ThanhVien lv
                       JOIN Lop_hoc l ON lv.ID_LopHoc = l.ID_LopHoc
                       WHERE lv.ID_Student = ?
                   ))
               -- Chỉ có môn, không có khối
               OR (tb.ID_KhoiLop IS NULL AND tb.ID_MonHoc IS NOT NULL
                   AND tb.ID_MonHoc IN (
                       SELECT DISTINCT l.ID_MonHoc FROM Lop_hoc_ThanhVien lv
                       JOIN Lop_hoc l ON lv.ID_LopHoc = l.ID_LopHoc
                       WHERE lv.ID_Student = ?
                   ))
               -- Có cả khối lẫn môn: phải khớp đúng lớp học học sinh đang tham gia
               OR (tb.ID_KhoiLop IS NOT NULL AND tb.ID_MonHoc IS NOT NULL
                   AND EXISTS (
                       SELECT 1 FROM Lop_hoc_ThanhVien lv
                       JOIN Lop_hoc l ON lv.ID_LopHoc = l.ID_LopHoc
                       WHERE lv.ID_Student = ?
                         AND l.ID_KhoiLop = tb.ID_KhoiLop
                         AND l.ID_MonHoc  = tb.ID_MonHoc
                   ))
             ORDER BY tb.NgayTao_ThongBao DESC",
            [$studentId, $studentId, $studentId]
        );

        return view('student.HocSinh_TrangChu', compact('thongBaos'));
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

        // Lịch học (buổi điểm danh) của từng lớp kèm trạng thái đơn xin vắng
        $lichHocRaw = DB::select(
            "SELECT dd.ID_DiemDanh, dd.ID_LopHoc, dd.NgayHoc_DiemDanh,
                    dd.ThoiGianBatDau_DiemDanh, dd.ThoiGianKetThuc_DiemDanh,
                    dd.TrangThaiBuoiHoc_DiemDanh,
                    dxn.ID_DonXinNghi, dxn.TrangThai_DonXinNghi
             FROM Diem_danh dd
             JOIN Lop_hoc_ThanhVien lv ON dd.ID_LopHoc = lv.ID_LopHoc
             LEFT JOIN Don_xin_nghi dxn
                    ON dxn.ID_DiemDanh = dd.ID_DiemDanh AND dxn.ID_User = ?
             WHERE lv.ID_Student = ?
             ORDER BY dd.NgayHoc_DiemDanh ASC, dd.ThoiGianBatDau_DiemDanh ASC",
            [$studentId, $studentId]
        );

        $lichHoc = [];
        foreach ($lichHocRaw as $row) {
            $lichHoc[$row->ID_LopHoc][] = $row;
        }

        return view('student.HocSinh_DanhSachLopHoc', compact('lopHocs', 'lichHoc'));
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
            "SELECT ds.*, kt.Ten_KyThi, dt.TenDeThi, m.Ten_MonHoc,
                    kt.CheDo_XemKetQua_KyThi as che_do_xem
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

        foreach ($diemDanhs as $dd) {
            $chiTiet = $dd->ChiTietDiemDanh_DiemDanh
                ? json_decode($dd->ChiTietDiemDanh_DiemDanh, true)
                : [];
            $dd->tinh_trang_ca_nhan = $chiTiet[$studentId] ?? null;
        }

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
        $studentId = session('auth.id');

        $lopHocs = DB::select(
            "SELECT l.ID_LopHoc, l.TenLopHoc, m.Ten_MonHoc, k.Ten_KhoiLop
             FROM Lop_hoc_ThanhVien lv
             JOIN Lop_hoc l  ON lv.ID_LopHoc  = l.ID_LopHoc
             JOIN Mon_Hoc m  ON l.ID_MonHoc   = m.ID_MonHoc
             JOIN Khoi_lop k ON l.ID_KhoiLop  = k.ID_KhoiLop
             WHERE lv.ID_Student = ?
             ORDER BY l.ID_LopHoc",
            [$studentId]
        );

        $rankings = [];
        foreach ($lopHocs as $lop) {
            $rows = DB::select(
                "SELECT u.ID_User, u.HoVaTen_User,
                        COALESCE(SUM(ds.TongDiem_DiemSo), 0) as tong_diem,
                        COUNT(DISTINCT ds.ID_DiemSo) as so_bai
                 FROM Lop_hoc_ThanhVien lv2
                 JOIN `User` u ON lv2.ID_Student = u.ID_User
                 LEFT JOIN Ky_thi kt ON kt.ID_LopHoc = lv2.ID_LopHoc
                 LEFT JOIN Diem_so ds ON ds.ID_MaKyThi = kt.ID_KyThi
                                    AND ds.ID_User = lv2.ID_Student
                 WHERE lv2.ID_LopHoc = ?
                 GROUP BY u.ID_User, u.HoVaTen_User
                 ORDER BY tong_diem DESC",
                [$lop->ID_LopHoc]
            );

            $rankings[] = [
                'lop'  => $lop,
                'bang' => $rows,
            ];
        }

        return view('student.HocSinh_XepHang', compact('rankings', 'studentId'));
    }

    public function thongTinUpdate(Request $request): RedirectResponse
    {
        $studentId = session('auth.id');
        $data = $request->validate([
            'HoVaTen_User'          => 'required|string|max:150',
            'EmailCaNhan_User'      => 'nullable|email|max:150',
            'SoDienThoai_User'      => 'nullable|string|max:20',
            'NgayThangNamSinh_User' => 'nullable|date',
        ]);

        if (!empty($data['EmailCaNhan_User'])) {
            $exists = DB::selectOne(
                'SELECT ID_User FROM `User` WHERE LOWER(EmailCaNhan_User) = ? AND ID_User != ? LIMIT 1',
                [strtolower($data['EmailCaNhan_User']), $studentId]
            );
            if ($exists) {
                return redirect()->route('student.thong-tin')
                    ->withErrors(['EmailCaNhan_User' => 'Email này đã được sử dụng bởi tài khoản khác.'])
                    ->withInput();
            }
        }

        DB::table('User')->where('ID_User', $studentId)->update($data);
        return redirect()->route('student.thong-tin')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function doiMatKhauUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'mat_khau_cu'  => 'required|string',
            'mat_khau_moi' => 'required|string|min:6',
            'xac_nhan'     => 'required|string|same:mat_khau_moi',
        ]);

        $studentId = session('auth.id');
        $user = DB::table('User')->where('ID_User', $studentId)->first();

        $matKhauCu  = $request->input('mat_khau_cu');
        $matKhauMoi = $request->input('mat_khau_moi');

        $stored  = (string) ($user->Pass_User ?? '');
        $isValid = password_get_info($stored)['algo'] !== null
            ? password_verify($matKhauCu, $stored)
            : hash_equals($stored, md5($matKhauCu));

        if (!$isValid) {
            return back()->withErrors(['mat_khau_cu' => 'Mật khẩu hiện tại không đúng.'])->withInput();
        }

        if ($matKhauCu === $matKhauMoi) {
            return back()->withErrors(['mat_khau_moi' => 'Mật khẩu mới không được trùng mật khẩu cũ.'])->withInput();
        }

        DB::table('User')
            ->where('ID_User', $studentId)
            ->update(['Pass_User' => password_hash($matKhauMoi, PASSWORD_BCRYPT)]);

        return redirect()->route('student.thong-tin')->with('success', 'Đổi mật khẩu thành công!');
    }

    public function xinVangStore(Request $request): RedirectResponse
    {
        $request->validate([
            'ID_DiemDanh'      => 'required|integer',
            'NoiDung_DonXinNghi' => 'required|string|max:1000',
        ]);

        $studentId   = session('auth.id');
        $idDiemDanh  = (int) $request->input('ID_DiemDanh');

        // Xác nhận buổi học thuộc lớp mà học sinh đang học
        $session = DB::selectOne(
            "SELECT dd.ID_DiemDanh, dd.ID_LopHoc, dd.TrangThaiBuoiHoc_DiemDanh,
                    dd.NgayHoc_DiemDanh, dd.ThoiGianBatDau_DiemDanh
             FROM Diem_danh dd
             JOIN Lop_hoc_ThanhVien lv ON dd.ID_LopHoc = lv.ID_LopHoc
             WHERE dd.ID_DiemDanh = ? AND lv.ID_Student = ?",
            [$idDiemDanh, $studentId]
        );

        if (!$session) {
            return back()->with('error', 'Buổi học không hợp lệ.');
        }

        if (!in_array($session->TrangThaiBuoiHoc_DiemDanh, ['scheduled', 'in_progress'])) {
            return back()->with('error', 'Chỉ có thể xin vắng khi buổi học chưa kết thúc.');
        }

        $batDauDt = $session->NgayHoc_DiemDanh && $session->ThoiGianBatDau_DiemDanh
            ? \Carbon\Carbon::parse(
                \Carbon\Carbon::parse($session->NgayHoc_DiemDanh)->format('Y-m-d') . ' ' .
                \Carbon\Carbon::parse($session->ThoiGianBatDau_DiemDanh)->format('H:i:s')
              )
            : null;
        if (!$batDauDt || now()->gte($batDauDt->subHour())) {
            return back()->with('error', 'Hết hạn báo vắng. Chỉ có thể xin vắng trước giờ học ít nhất 1 tiếng.');
        }

        // Kiểm tra đã có đơn chưa
        $existing = DB::selectOne(
            "SELECT ID_DonXinNghi FROM Don_xin_nghi WHERE ID_DiemDanh = ? AND ID_User = ?",
            [$idDiemDanh, $studentId]
        );

        if ($existing) {
            return back()->with('error', 'Bạn đã gửi đơn xin vắng cho buổi học này rồi.');
        }

        DB::table('Don_xin_nghi')->insert([
            'ID_LopHoc'            => $session->ID_LopHoc,
            'ID_User'              => $studentId,
            'ID_DiemDanh'          => $idDiemDanh,
            'ThoiGianGui_DonXinNghi' => now(),
            'NoiDung_DonXinNghi'   => $request->input('NoiDung_DonXinNghi'),
            'TrangThai_DonXinNghi' => 'pending',
        ]);

        return back()->with('success', 'Đã gửi đơn xin vắng thành công! Vui lòng chờ giáo viên duyệt.');
    }
}
