<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class StudentExamService {

    // Lấy danh sách kỳ thi đang mở (Đã lọc những bài đã thi)
    public function getDanhSachKyThiDangMo(int $id_student): array {
        return DB::table('Ky_thi as kt')
            ->join('Lop_hoc_ThanhVien as lhtv', 'lhtv.ID_LopHoc', '=', 'kt.ID_LopHoc')
            ->leftJoin('Diem_so as ds', function($join) use ($id_student) {
                $join->on('ds.ID_MaKyThi', '=', 'kt.ID_KyThi')
                     ->where('ds.ID_User', '=', $id_student);
            })
            ->where('lhtv.ID_Student', $id_student)
            ->where('kt.ThoiGianBatDau_KyThi', '<=', now())
            ->where('kt.ThoiGianKetThuc_KyThi', '>=', now())
            ->whereNull('ds.ID_DiemSo') // Chưa thi mới hiện
            ->select('kt.*')
            ->orderBy('kt.ThoiGianBatDau_KyThi', 'desc')
            ->get()->map(function($item) {
                return (array) $item; // Ép kiểu về array để view cũ không bị lỗi
            })->toArray();
    }

    // Lấy thông tin 1 kỳ thi cụ thể
    public function getThongTinKyThi($id_kythi) {
        $kythi = DB::table('Ky_thi')->where('ID_KyThi', $id_kythi)->first();
        return $kythi ? (array) $kythi : null;
    }

    // Lấy câu hỏi chia làm 3 phần
    public function getCauHoiDeThi($id_dethi): array {
        $result = ['phan1_4pa' => [], 'phan2_ds' => [], 'phan3_ngan' => []];

        $result['phan1_4pa'] = DB::table('De_Thi_Chi_Tiet as dt')
            ->join('Cau_hoi_trac_nghiem_4_phuong_an as c', 'dt.ID_TracNghiem4PhuongAn', '=', 'c.ID_TracNghiem4PhuongAn')
            ->where('dt.ID_MaDeThi', $id_dethi)
            ->select('c.ID_TracNghiem4PhuongAn as id', 'c.NoiDungCauHoi_TracNghiem4PhuongAn as cau_hoi', 'c.NoiDungCauTraLoi1_TracNghiem4PhuongAn as a', 'c.NoiDungCauTraLoi2_TracNghiem4PhuongAn as b', 'c.NoiDungCauTraLoi3_TracNghiem4PhuongAn as c', 'c.NoiDungCauTraLoi4_TracNghiem4PhuongAn as d')
            ->get()->toArray();

        $result['phan2_ds'] = DB::table('De_Thi_Chi_Tiet as dt')
            ->join('Cau_hoi_trac_nghiem_dung_sai as c', 'dt.ID_TracNghiemDungSai', '=', 'c.ID_TracNghiemDungSai')
            ->where('dt.ID_MaDeThi', $id_dethi)
            ->select('c.ID_TracNghiemDungSai as id', 'c.NoiDungCauHoi_TracNghiemDungSai as cau_hoi', 'c.NoiDungMenhDe1_TracNghiemDungSai as md1', 'c.NoiDungMenhDe2_TracNghiemDungSai as md2', 'c.NoiDungMenhDe3_TracNghiemDungSai as md3', 'c.NoiDungMenhDe4_TracNghiemDungSai as md4')
            ->get()->toArray();

        $result['phan3_ngan'] = DB::table('De_Thi_Chi_Tiet as dt')
            ->join('Cau_hoi_tra_loi_ngan as c', 'dt.ID_TracNghiemTraLoiNgan', '=', 'c.ID_TracNghiemTraLoiNgan')
            ->where('dt.ID_MaDeThi', $id_dethi)
            ->select('c.ID_TracNghiemTraLoiNgan as id', 'c.NoiDungCauHoi_TracNghiemTraLoiNgan as cau_hoi')
            ->get()->toArray();

        return $result;
    }

    // Chấm điểm và lưu
    public function chamDiemVaLuu(int $id_student, array $kythi, array $student_answers, string $time_start, int $time_spent): array {
        $id_dethi = $kythi['ID_MaDeThi'];
        $diem_4pa = 0.0; $diem_ds = 0.0; $diem_ngan = 0.0;

        // Chấm Phần 1
        if (!empty($student_answers['phan1'])) {
            $correct_4pa = DB::table('De_Thi_Chi_Tiet')->where('ID_MaDeThi', $id_dethi)->whereNotNull('ID_TracNghiem4PhuongAn')
                ->join('Cau_hoi_trac_nghiem_4_phuong_an', 'De_Thi_Chi_Tiet.ID_TracNghiem4PhuongAn', '=', 'Cau_hoi_trac_nghiem_4_phuong_an.ID_TracNghiem4PhuongAn')
                ->pluck('DapAn_TracNghiem4PhuongAn', 'De_Thi_Chi_Tiet.ID_TracNghiem4PhuongAn')->toArray();

            foreach ($student_answers['phan1'] as $id_cau => $dapan_hs) {
                if (isset($correct_4pa[$id_cau]) && $correct_4pa[$id_cau] === strtoupper($dapan_hs)) {
                    $diem_4pa += (float)$kythi['PhanBoDiemTracNghiem4PhuongAn_KyThi'];
                }
            }
        }

        // Chấm Phần 2
        if (!empty($student_answers['phan2'])) {
            $correct_ds = DB::table('De_Thi_Chi_Tiet')->where('ID_MaDeThi', $id_dethi)->whereNotNull('ID_TracNghiemDungSai')
                ->join('Cau_hoi_trac_nghiem_dung_sai', 'De_Thi_Chi_Tiet.ID_TracNghiemDungSai', '=', 'Cau_hoi_trac_nghiem_dung_sai.ID_TracNghiemDungSai')
                ->pluck('DapAn_TracNghiem4PhuongAn', 'De_Thi_Chi_Tiet.ID_TracNghiemDungSai')->toArray();

            foreach ($student_answers['phan2'] as $id_cau => $dapan_hs) {
                if (isset($correct_ds[$id_cau]) && $correct_ds[$id_cau] === $dapan_hs) {
                    $diem_ds += (float)$kythi['PhanBoDiemTracNghiemDungSai_KyThi'];
                }
            }
        }

        // Chấm Phần 3
        if (!empty($student_answers['phan3'])) {
            $correct_ngan = DB::table('De_Thi_Chi_Tiet')->where('ID_MaDeThi', $id_dethi)->whereNotNull('ID_TracNghiemTraLoiNgan')
                ->join('Cau_hoi_tra_loi_ngan', 'De_Thi_Chi_Tiet.ID_TracNghiemTraLoiNgan', '=', 'Cau_hoi_tra_loi_ngan.ID_TracNghiemTraLoiNgan')
                ->selectRaw('De_Thi_Chi_Tiet.ID_TracNghiemTraLoiNgan as id, CONCAT(KiTuThu1CuaDapAn_TracNghiemTraLoiNgan, KiTuThu2CuaDapAn_TracNghiemTraLoiNgan, KiTuThu3CuaDapAn_TracNghiemTraLoiNgan, KiTuThu4CuaDapAn_TracNghiemTraLoiNgan) as dapan')
                ->pluck('dapan', 'id')->toArray();

            foreach ($student_answers['phan3'] as $id_cau => $dapan_hs) {
                if (strcasecmp(trim($dapan_hs), trim($correct_ngan[$id_cau] ?? '')) == 0) {
                    $diem_ngan += (float)$kythi['PhanBoDiemTracNghiemTraLoiNgan_KyThi'];
                }
            }
        }

        $tong_diem = $diem_4pa + $diem_ds + $diem_ngan;

        // Lưu vào DB
        DB::table('Diem_so')->insert([
            'ID_User' => $id_student,
            'ID_MaKyThi' => $kythi['ID_KyThi'],
            'ID_MaDeThi' => $id_dethi,
            'DiemPhanTracNghiem4PhuongAn_DiemSo' => $diem_4pa,
            'DiemPhanTracNghiemDungSai_DiemSo' => $diem_ds,
            'DiemPhanTracNghiemTraLoiNgan_DiemSo' => $diem_ngan,
            'TongDiem_DiemSo' => $tong_diem,
            'ThoiGianBatDau_DiemSo' => $time_start,
            'ThoiGianKetThuc_DiemSo' => now(),
            'ThoiGianLamBai_DiemSo' => $time_spent,
            'LichSuLamBai' => json_encode($student_answers)
        ]);

        return ['success' => true, 'tong_diem' => $tong_diem];
    }
}