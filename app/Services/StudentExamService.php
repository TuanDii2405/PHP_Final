<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StudentExamService
{
    public function getDanhSachKyThiDangMo(int $studentId): array
    {
        return DB::select(
            "SELECT DISTINCT kt.*
             FROM Ky_thi kt
             JOIN Lop_hoc_ThanhVien lv ON kt.ID_LopHoc = lv.ID_LopHoc
             LEFT JOIN Diem_so ds ON ds.ID_MaKyThi = kt.ID_KyThi AND ds.ID_User = ?
             WHERE lv.ID_Student = ?
               AND kt.ThoiGianBatDau_KyThi <= NOW()
               AND kt.ThoiGianKetThuc_KyThi >= NOW()
               AND ds.ID_DiemSo IS NULL
             ORDER BY kt.ThoiGianBatDau_KyThi",
            [$studentId, $studentId]
        );
    }

    public function getThongTinKyThi(int $kyThiId): ?array
    {
        $kt = DB::selectOne("SELECT * FROM Ky_thi WHERE ID_KyThi = ?", [$kyThiId]);
        return $kt ? (array) $kt : null;
    }

    public function getCauHoiDeThi(int $deThiId, int $so4PA = 0, int $soDS = 0, int $soNgan = 0): array
    {
        $phan1 = $so4PA > 0 ? DB::select(
            "SELECT q.ID_TracNghiem4PhuongAn as id,
                    q.NoiDungCauHoi_TracNghiem4PhuongAn as cau_hoi,
                    q.NoiDungCauTraLoi1_TracNghiem4PhuongAn as a,
                    q.NoiDungCauTraLoi2_TracNghiem4PhuongAn as b,
                    q.NoiDungCauTraLoi3_TracNghiem4PhuongAn as c,
                    q.NoiDungCauTraLoi4_TracNghiem4PhuongAn as d
             FROM De_Thi_Chi_Tiet dtct
             JOIN Cau_hoi_trac_nghiem_4_phuong_an q
               ON dtct.ID_TracNghiem4PhuongAn = q.ID_TracNghiem4PhuongAn
             WHERE dtct.ID_MaDeThi = ?
               AND dtct.ID_TracNghiem4PhuongAn IS NOT NULL
             ORDER BY RAND()
             LIMIT ?",
            [$deThiId, $so4PA]
        ) : [];

        $phan2 = $soDS > 0 ? DB::select(
            "SELECT q.ID_TracNghiemDungSai as id,
                    q.NoiDungCauHoi_TracNghiemDungSai as cau_hoi,
                    q.NoiDungMenhDe1_TracNghiemDungSai as md1,
                    q.NoiDungMenhDe2_TracNghiemDungSai as md2,
                    q.NoiDungMenhDe3_TracNghiemDungSai as md3,
                    q.NoiDungMenhDe4_TracNghiemDungSai as md4,
                    q.DapAn_TracNghiem4PhuongAn as dap_an
             FROM De_Thi_Chi_Tiet dtct
             JOIN Cau_hoi_trac_nghiem_dung_sai q
               ON dtct.ID_TracNghiemDungSai = q.ID_TracNghiemDungSai
             WHERE dtct.ID_MaDeThi = ?
               AND dtct.ID_TracNghiemDungSai IS NOT NULL
             ORDER BY RAND()
             LIMIT ?",
            [$deThiId, $soDS]
        ) : [];

        $phan3 = $soNgan > 0 ? DB::select(
            "SELECT q.ID_TracNghiemTraLoiNgan as id,
                    q.NoiDungCauHoi_TracNghiemTraLoiNgan as cau_hoi
             FROM De_Thi_Chi_Tiet dtct
             JOIN Cau_hoi_tra_loi_ngan q
               ON dtct.ID_TracNghiemTraLoiNgan = q.ID_TracNghiemTraLoiNgan
             WHERE dtct.ID_MaDeThi = ?
               AND dtct.ID_TracNghiemTraLoiNgan IS NOT NULL
             ORDER BY RAND()
             LIMIT ?",
            [$deThiId, $soNgan]
        ) : [];

        return [
            'phan1_4pa'  => array_map(fn($r) => (array) $r, $phan1),
            'phan2_ds'   => array_map(fn($r) => (array) $r, $phan2),
            'phan3_ngan' => array_map(fn($r) => (array) $r, $phan3),
        ];
    }

    public function getCauHoiForReview4PA(array $ids): array
    {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $rows = DB::select(
            "SELECT ID_TracNghiem4PhuongAn as id,
                    NoiDungCauHoi_TracNghiem4PhuongAn as cau_hoi,
                    NoiDungCauTraLoi1_TracNghiem4PhuongAn as a,
                    NoiDungCauTraLoi2_TracNghiem4PhuongAn as b,
                    NoiDungCauTraLoi3_TracNghiem4PhuongAn as c,
                    NoiDungCauTraLoi4_TracNghiem4PhuongAn as d,
                    DapAn_TracNghiem4PhuongAn as dap_an
             FROM Cau_hoi_trac_nghiem_4_phuong_an
             WHERE ID_TracNghiem4PhuongAn IN ($placeholders)",
            $ids
        );
        return array_column(array_map(fn($r) => (array) $r, $rows), null, 'id');
    }

    public function getCauHoiForReviewDS(array $ids): array
    {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $rows = DB::select(
            "SELECT ID_TracNghiemDungSai as id,
                    NoiDungCauHoi_TracNghiemDungSai as cau_hoi,
                    NoiDungMenhDe1_TracNghiemDungSai as md1,
                    NoiDungMenhDe2_TracNghiemDungSai as md2,
                    NoiDungMenhDe3_TracNghiemDungSai as md3,
                    NoiDungMenhDe4_TracNghiemDungSai as md4,
                    DapAn_TracNghiem4PhuongAn as dap_an
             FROM Cau_hoi_trac_nghiem_dung_sai
             WHERE ID_TracNghiemDungSai IN ($placeholders)",
            $ids
        );
        return array_column(array_map(fn($r) => (array) $r, $rows), null, 'id');
    }

    public function getCauHoiForReviewNgan(array $ids): array
    {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $rows = DB::select(
            "SELECT ID_TracNghiemTraLoiNgan as id,
                    NoiDungCauHoi_TracNghiemTraLoiNgan as cau_hoi,
                    CONCAT(
                        COALESCE(KiTuThu1CuaDapAn_TracNghiemTraLoiNgan,''),
                        COALESCE(KiTuThu2CuaDapAn_TracNghiemTraLoiNgan,''),
                        COALESCE(KiTuThu3CuaDapAn_TracNghiemTraLoiNgan,''),
                        COALESCE(KiTuThu4CuaDapAn_TracNghiemTraLoiNgan,'')
                    ) as dap_an
             FROM Cau_hoi_tra_loi_ngan
             WHERE ID_TracNghiemTraLoiNgan IN ($placeholders)",
            $ids
        );
        return array_column(array_map(fn($r) => (array) $r, $rows), null, 'id');
    }

    public function chamDiemVaLuu(
        int    $studentId,
        array  $kythi,
        array  $answers,
        string $timeStart,
        int    $timeSpent
    ): void {
        // ── Phần 1: Trắc nghiệm 4 phương án ──
        $so4PA   = (int) ($kythi['SoCauHoiTracNghiem4PhuongAn_KyThi'] ?? 0);
        $diem4PA = 0.0;
        $diemMoi4PA = $so4PA > 0 ? (float) $kythi['PhanBoDiemTracNghiem4PhuongAn_KyThi'] / $so4PA : 0;

        foreach ((array) ($answers['phan1'] ?? []) as $id => $ans) {
            $q = DB::selectOne(
                "SELECT DapAn_TracNghiem4PhuongAn FROM Cau_hoi_trac_nghiem_4_phuong_an
                 WHERE ID_TracNghiem4PhuongAn = ?",
                [(int) $id]
            );
            if ($q && strtoupper(trim((string) $ans)) === strtoupper(trim($q->DapAn_TracNghiem4PhuongAn))) {
                $diem4PA += $diemMoi4PA;
            }
        }

        // ── Phần 2: Trắc nghiệm Đúng/Sai ──
        $soDS    = (int) ($kythi['SoCauHoiTracNghiemDungSai_KyThi'] ?? 0);
        $diemDS  = 0.0;
        $diemMoiDS = $soDS > 0 ? (float) $kythi['PhanBoDiemTracNghiemDungSai_KyThi'] / $soDS : 0;

        foreach ((array) ($answers['phan2'] ?? []) as $id => $ansStr) {
            $q = DB::selectOne(
                "SELECT DapAn_TracNghiem4PhuongAn as dap_an
                 FROM Cau_hoi_trac_nghiem_dung_sai
                 WHERE ID_TracNghiemDungSai = ?",
                [(int) $id]
            );
            if (!$q || !$q->dap_an) continue;

            // dap_an: chuỗi 4 ký tự "TTFF", ansStr: "1:T,2:F,3:T,4:F"
            $dapAnArr = str_split(strtoupper($q->dap_an)); // ['T','T','F','F']
            $parts    = explode(',', (string) $ansStr);
            $soYDung  = 0;
            foreach ($parts as $part) {
                [$idx, $val] = array_pad(explode(':', $part, 2), 2, '');
                $val = strtoupper(trim($val));
                $i   = (int) $idx - 1;
                if ($val !== 'X' && isset($dapAnArr[$i]) && $val === $dapAnArr[$i]) {
                    $soYDung++;
                }
            }
            // Thang điểm bậc thang: 1/4=10%, 2/4=25%, 3/4=50%, 4/4=100%
            $multipliers = [0, 0.10, 0.25, 0.50, 1.00];
            $diemDS += $diemMoiDS * ($multipliers[$soYDung] ?? 1.00);
        }

        // ── Phần 3: Trả lời ngắn ──
        $soNgan    = (int) ($kythi['SoCauHoiTracNghiemTraLoiNgan_KyThi'] ?? 0);
        $diemNgan  = 0.0;
        $diemMoiNgan = $soNgan > 0 ? (float) $kythi['PhanBoDiemTracNghiemTraLoiNgan_KyThi'] / $soNgan : 0;

        foreach ((array) ($answers['phan3'] ?? []) as $id => $ans) {
            $q = DB::selectOne(
                "SELECT CONCAT(
                    COALESCE(KiTuThu1CuaDapAn_TracNghiemTraLoiNgan,''),
                    COALESCE(KiTuThu2CuaDapAn_TracNghiemTraLoiNgan,''),
                    COALESCE(KiTuThu3CuaDapAn_TracNghiemTraLoiNgan,''),
                    COALESCE(KiTuThu4CuaDapAn_TracNghiemTraLoiNgan,'')
                 ) as dap_an
                 FROM Cau_hoi_tra_loi_ngan WHERE ID_TracNghiemTraLoiNgan = ?",
                [(int) $id]
            );
            if ($q && strcasecmp(trim((string) $ans), trim($q->dap_an)) === 0) {
                $diemNgan += $diemMoiNgan;
            }
        }

        // Đảm bảo điểm từng phần không vượt quá phân bổ của kỳ thi
        $diem4PA  = min($diem4PA,  (float) ($kythi['PhanBoDiemTracNghiem4PhuongAn_KyThi']  ?? 0));
        $diemDS   = min($diemDS,   (float) ($kythi['PhanBoDiemTracNghiemDungSai_KyThi']    ?? 0));
        $diemNgan = min($diemNgan, (float) ($kythi['PhanBoDiemTracNghiemTraLoiNgan_KyThi'] ?? 0));

        DB::table('Diem_so')->insert([
            'ID_User'                                  => $studentId,
            'ID_MaKyThi'                               => $kythi['ID_KyThi'],
            'ID_MaDeThi'                               => $kythi['ID_MaDeThi'],
            'TongDiem_DiemSo'                          => round($diem4PA + $diemDS + $diemNgan, 2),
            'DiemPhanTracNghiem4PhuongAn_DiemSo'       => round($diem4PA, 2),
            'DiemPhanTracNghiemDungSai_DiemSo'         => round($diemDS, 2),
            'DiemPhanTracNghiemTraLoiNgan_DiemSo'      => round($diemNgan, 2),
            'ThoiGianBatDau_DiemSo'                    => $timeStart,
            'ThoiGianKetThuc_DiemSo'                   => now()->toDateTimeString(),
            'ThoiGianLamBai_DiemSo'                    => (int) round($timeSpent / 60),
            'LichSuLamBai'                             => json_encode($answers),
        ]);
    }
}
