<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function examHistory()
    {
        // Vẫn tạm fix cứng ID = 9 (Nguyễn Văn An) để test
        $userId = 9;

        // Query kết nối 3 bảng: Diem_so -> Ky_thi -> Mon_Hoc
        $histories = DB::table('Diem_so')
            ->join('Ky_thi', 'Diem_so.ID_MaKyThi', '=', 'Ky_thi.ID_KyThi')
            ->join('Mon_Hoc', 'Ky_thi.ID_MonHoc', '=', 'Mon_Hoc.ID_MonHoc')
            ->where('Diem_so.ID_User', $userId)
            ->select(
                'Ky_thi.Ten_KyThi',
                'Mon_Hoc.Ten_MonHoc',
                'Diem_so.ThoiGianKetThuc_DiemSo',
                'Diem_so.TongDiem_DiemSo',
                'Diem_so.ThoiGianLamBai_DiemSo',
                'Diem_so.DiemPhanTracNghiem4PhuongAn_DiemSo',
                'Diem_so.DiemPhanTracNghiemDungSai_DiemSo',
                'Diem_so.DiemPhanTracNghiemTraLoiNgan_DiemSo'
            )
            ->orderBy('Diem_so.ThoiGianKetThuc_DiemSo', 'desc') // Sắp xếp bài mới làm lên đầu
            ->get();

        return view('student.HocSinh_LichSuLamBai', [
            'histories' => $histories
        ]);
    }
    public function attendanceHistory()
{
    // Tạm dùng ID = 9 (Nguyễn Văn An) để test
    $userId = 9;

    $attendances = DB::table('Diem_danh')
        ->join('Lop_hoc', 'Diem_danh.ID_LopHoc', '=', 'Lop_hoc.ID_LopHoc')
        ->join('Mon_Hoc', 'Lop_hoc.ID_MonHoc', '=', 'Mon_Hoc.ID_MonHoc')
        ->join('User as Teacher', 'Lop_hoc.ID_Teacher', '=', 'Teacher.ID_User')
        ->whereExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                  ->from('Lop_hoc_ThanhVien')
                  ->whereColumn('Lop_hoc_ThanhVien.ID_LopHoc', 'Lop_hoc.ID_LopHoc')
                  ->where('Lop_hoc_ThanhVien.ID_Student', $userId);
        })
        ->select(
            'Lop_hoc.TenLopHoc',
            'Teacher.HoVaTen_User as TenGiaoVien',
            'Mon_Hoc.Ten_MonHoc',
            'Diem_danh.NgayHoc_DiemDanh',
            'Diem_danh.ThoiGianBatDau_DiemDanh',
            'Diem_danh.TrangThaiBuoiHoc_DiemDanh',
            'Diem_danh.ChiTietDiemDanh_DiemDanh'
        )
        ->orderByDesc('Diem_danh.NgayHoc_DiemDanh')
        ->get();

    return view('student.HocSinh_LichSuDiemDanh', [
        'attendances' => $attendances,
        'userId' => $userId
    ]);
}
}