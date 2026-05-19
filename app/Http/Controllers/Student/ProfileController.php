<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Dùng Query Builder cho nhanh gọn

class ProfileController extends Controller
{
    public function index()
    {
        // 1. Lấy ID của học sinh đang đăng nhập. 
        // (Tạm thời gán cứng ID = 9 là bạn Nguyễn Văn An để test giao diện trước. 
        // Sau này ráp luồng Login xong, bạn thay bằng Auth::id() hoặc session('ID_User'))
        $userId = 9; 

        // 2. Truy vấn thông tin cá nhân từ bảng User
        $user = DB::table('User')->where('ID_User', $userId)->first();

        // 3. Đếm số kỳ thi đã làm trong bảng Diem_so
        $examCount = DB::table('Diem_so')->where('ID_User', $userId)->count();

        // 4. Trả dữ liệu về View 
        // (Lưu ý: đường dẫn view 'pages.student.thong_tin' cần sửa lại cho khớp với cấu trúc thư mục resources/views của bạn)
        // ... code lấy $user và $examCount ở trên ...

        // Nhớ truyền mảng dữ liệu này sang view nhé
        return view('student.HocSinh_ThongTinCaNhan', [
            'user' => $user,
            'examCount' => $examCount
        ]);
    }
}