<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tham số filter từ URL (ví dụ: ?filter=tonghop), mặc định là 'tonghop'
        $filter = $request->query('filter', 'tonghop');

        // Query tính tổng điểm của từng học sinh và sắp xếp giảm dần
        $query = DB::table('Diem_so')
            ->join('User', 'Diem_so.ID_User', '=', 'User.ID_User')
            ->select(
                'User.HoVaTen_User',
                DB::raw('SUM(Diem_so.TongDiem_DiemSo) as TongDiem'),
                DB::raw('COUNT(Diem_so.ID_DiemSo) as SoBaiThi')
            )
            ->where('User.PhanQuyen_User', 'student')
            ->groupBy('User.ID_User', 'User.HoVaTen_User')
            ->orderByDesc('TongDiem'); // Sắp xếp từ điểm cao nhất xuống thấp nhất

        // Tạm thời lấy Top 10 học sinh xuất sắc nhất
        $rankings = $query->take(10)->get();

        return view('student.HocSinh_XepHang', [
            'rankings' => $rankings,
            'currentFilter' => $filter
        ]);
    }
}