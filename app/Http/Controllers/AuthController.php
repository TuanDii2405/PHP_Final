<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function redirectHome(Request $request): RedirectResponse
    {
        $role = $request->session()->get('auth.role');

        return match ($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default   => redirect()->route('login'),
        };
    }

    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth')) {
            return $this->redirectHome($request);
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $loginInput   = strtolower(trim((string) $request->input('username', '')));
        $passwordInput = trim((string) $request->input('password', ''));

        if ($loginInput === '' || $passwordInput === '') {
            return redirect()->route('login')->withInput(['username' => $loginInput])->with('error', 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.');
        }

        $roleAlias = ['admin' => 'admin', 'giaovien' => 'teacher', 'hocsinh' => 'student'];

        try {
            if (isset($roleAlias[$loginInput])) {
                $user = DB::selectOne(
                    'SELECT ID_User, Pass_User, PhanQuyen_User, HoVaTen_User, TrangThaiHoatDong_User
                     FROM `User`
                     WHERE PhanQuyen_User = ?
                     ORDER BY ID_User ASC
                     LIMIT 1',
                    [$roleAlias[$loginInput]]
                );
            } else {
                $user = DB::selectOne(
                    'SELECT ID_User, Pass_User, PhanQuyen_User, HoVaTen_User, TrangThaiHoatDong_User
                     FROM `User`
                     WHERE LOWER(EmailCaNhan_User) = ? OR SoDienThoai_User = ?
                     ORDER BY ID_User ASC
                     LIMIT 1',
                    [$loginInput, $loginInput]
                );
            }
        } catch (\Throwable) {
            return redirect()->route('login')->withInput(['username' => $loginInput])->with('error', 'Hệ thống tạm thời gián đoạn kết nối CSDL.');
        }

        if (!$user) {
            return redirect()->route('login')->withInput(['username' => $loginInput])->with('error', 'Sai tài khoản hoặc mật khẩu.');
        }

        if (($user->TrangThaiHoatDong_User ?? '') !== 'active') {
            return redirect()->route('login')->withInput(['username' => $loginInput])->with('error', 'Tài khoản hiện không hoạt động.');
        }

        $storedPassword = (string) $user->Pass_User;
        $isValid = password_get_info($storedPassword)['algo'] !== null
            ? password_verify($passwordInput, $storedPassword)
            : hash_equals($storedPassword, md5($passwordInput));

        if (!$isValid) {
            return redirect()->route('login')->withInput(['username' => $loginInput])->with('error', 'Sai tài khoản hoặc mật khẩu.');
        }

        $request->session()->put('auth', [
            'id'        => (int) $user->ID_User,
            'name'      => (string) $user->HoVaTen_User,
            'role'      => (string) $user->PhanQuyen_User,
            'logged_at' => now()->toIso8601String(),
        ]);

        return match ($user->PhanQuyen_User) {
            'admin'   => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            default   => redirect()->route('student.dashboard'),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('auth');

        return redirect()->route('login');
    }

    public function showRegister(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth')) {
            return $this->redirectHome($request);
        }

        return view('auth.dangki');
    }

    public function register(Request $request): RedirectResponse
    {
        $fullname   = trim((string) $request->input('fullname', ''));
        $dob        = trim((string) $request->input('dob', ''));
        $role       = trim((string) $request->input('role', ''));
        $email      = strtolower(trim((string) $request->input('email', '')));
        $password   = (string) $request->input('password', '');
        $repassword = (string) $request->input('repassword', '');

        $back = fn(string $msg) => redirect()->route('register')
            ->withInput($request->except(['password', 'repassword']))
            ->with('error', $msg);

        if (!$fullname || !$dob || !$role || !$email || !$password || !$repassword) {
            return $back('Vui lòng nhập đầy đủ thông tin.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $back('Địa chỉ email không hợp lệ.');
        }

        if (!in_array($role, ['hocsinh', 'giaovien'], true)) {
            return $back('Vai trò không hợp lệ.');
        }

        if (strlen($password) < 6) {
            return $back('Mật khẩu phải có ít nhất 6 ký tự.');
        }

        if ($password !== $repassword) {
            return $back('Mật khẩu xác nhận không khớp.');
        }

        $roleMap = ['hocsinh' => 'student', 'giaovien' => 'teacher'];

        try {
            $exists = DB::selectOne(
                'SELECT ID_User FROM `User` WHERE LOWER(EmailCaNhan_User) = ? LIMIT 1',
                [$email]
            );

            if ($exists) {
                return $back('Email này đã được sử dụng.');
            }

            DB::insert(
                'INSERT INTO `User` (Pass_User, PhanQuyen_User, HoVaTen_User, NgayThangNamSinh_User, EmailCaNhan_User, TrangThaiHoatDong_User)
                 VALUES (?, ?, ?, ?, ?, ?)',
                [password_hash($password, PASSWORD_BCRYPT), $roleMap[$role], $fullname, $dob, $email, 'active']
            );
        } catch (\Throwable) {
            return $back('Hệ thống tạm thời gián đoạn kết nối CSDL.');
        }

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }

    public function showDoiMatKhau(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth')) {
            return $this->redirectHome($request);
        }

        // Cho phép huỷ về bước 1
        if ($request->has('cancel')) {
            $request->session()->forget(['qmk_user_id', 'qmk_user_name']);
            return redirect()->route('doi-mat-khau');
        }

        if ($request->session()->has('qmk_user_id')) {
            return view('auth.doimatkhau', [
                'step'          => 2,
                'qmk_user_name' => $request->session()->get('qmk_user_name', ''),
            ]);
        }

        return view('auth.doimatkhau', ['step' => 1]);
    }

    public function doiMatKhau(Request $request): RedirectResponse
    {
        if ($request->session()->has('auth')) {
            return $this->redirectHome($request);
        }

        $step = $request->input('step', '1');

        // ── Bước 1: xác minh email / SĐT ─────────────────────
        if ($step === '1') {
            $loginInput = strtolower(trim((string) $request->input('username', '')));

            if (!$loginInput) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Vui lòng nhập email hoặc số điện thoại.');
            }

            try {
                $user = DB::selectOne(
                    'SELECT ID_User, HoVaTen_User, TrangThaiHoatDong_User
                     FROM `User`
                     WHERE LOWER(EmailCaNhan_User) = ? OR SoDienThoai_User = ?
                     ORDER BY ID_User ASC LIMIT 1',
                    [$loginInput, $loginInput]
                );
            } catch (\Throwable) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Hệ thống tạm thời gián đoạn kết nối CSDL.');
            }

            if (!$user) {
                return redirect()->route('doi-mat-khau')
                    ->withInput()
                    ->with('error', 'Không tìm thấy tài khoản với thông tin đã nhập.');
            }

            if (($user->TrangThaiHoatDong_User ?? '') !== 'active') {
                return redirect()->route('doi-mat-khau')
                    ->withInput()
                    ->with('error', 'Tài khoản hiện không hoạt động, vui lòng liên hệ quản trị viên.');
            }

            $request->session()->put('qmk_user_id',   $user->ID_User);
            $request->session()->put('qmk_user_name', $user->HoVaTen_User);

            return redirect()->route('doi-mat-khau');
        }

        // ── Bước 2: đặt mật khẩu mới ─────────────────────────
        if (!$request->session()->has('qmk_user_id')) {
            return redirect()->route('doi-mat-khau')
                ->with('error', 'Phiên làm việc hết hạn, vui lòng thử lại.');
        }

        $userId      = (int) $request->session()->get('qmk_user_id');
        $newPass     = (string) $request->input('new_password', '');
        $confirmPass = (string) $request->input('confirm_password', '');

        if (!$newPass || !$confirmPass) {
            return redirect()->route('doi-mat-khau')
                ->with('error', 'Vui lòng nhập đầy đủ mật khẩu mới.');
        }

        if (strlen($newPass) < 6) {
            return redirect()->route('doi-mat-khau')
                ->with('error', 'Mật khẩu mới phải có ít nhất 6 ký tự.');
        }

        if ($newPass !== $confirmPass) {
            return redirect()->route('doi-mat-khau')
                ->with('error', 'Mật khẩu xác nhận không khớp.');
        }

        try {
            DB::update(
                'UPDATE `User` SET Pass_User = ? WHERE ID_User = ?',
                [md5($newPass), $userId]
            );
        } catch (\Throwable) {
            return redirect()->route('doi-mat-khau')
                ->with('error', 'Không thể cập nhật mật khẩu. Vui lòng thử lại.');
        }

        $request->session()->forget(['qmk_user_id', 'qmk_user_name']);

        return redirect()->route('login')
            ->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }
}
