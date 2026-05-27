<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
                     WHERE LOWER(EmailCaNhan_User) = ?
                     ORDER BY ID_User ASC
                     LIMIT 1',
                    [$loginInput]
                );
            }
        } catch (\Throwable) {
            return redirect()->route('login')->withInput(['username' => $loginInput])->with('error', 'Hệ thống tạm thời gián đoạn kết nối CSDL.');
        }

        if (!$user) {
            return redirect()->route('login')->withInput(['username' => $loginInput])->with('error', 'Sai tài khoản hoặc mật khẩu.');
        }

        if (($user->TrangThaiHoatDong_User ?? '') === 'pending') {
            return redirect()->route('login')->withInput(['username' => $loginInput])->with('error', 'Tài khoản đang chờ admin duyệt. Vui lòng thử lại sau.');
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
                [password_hash($password, PASSWORD_BCRYPT), $roleMap[$role], $fullname, $dob, $email, 'pending']
            );
        } catch (\Throwable) {
            return $back('Hệ thống tạm thời gián đoạn kết nối CSDL.');
        }

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Tài khoản đang chờ admin duyệt.');
    }

    public function showDoiMatKhau(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth')) {
            return $this->redirectHome($request);
        }

        if ($request->has('cancel')) {
            $request->session()->forget(['qmk_user_id', 'qmk_user_name', 'qmk_email_masked', 'qmk_otp', 'qmk_otp_expiry']);
            return redirect()->route('doi-mat-khau');
        }

        // Step 3: OTP verified — show new password form
        if ($request->session()->has('qmk_user_id') && !$request->session()->has('qmk_otp')) {
            return view('auth.doimatkhau', [
                'step'          => 3,
                'qmk_user_name' => $request->session()->get('qmk_user_name', ''),
            ]);
        }

        // Step 2: OTP sent — show OTP input form
        if ($request->session()->has('qmk_otp')) {
            return view('auth.doimatkhau', [
                'step'             => 2,
                'qmk_user_name'    => $request->session()->get('qmk_user_name', ''),
                'qmk_email_masked' => $request->session()->get('qmk_email_masked', ''),
            ]);
        }

        return view('auth.doimatkhau', ['step' => 1]);
    }

    public function doiMatKhau(Request $request): RedirectResponse
    {
        if ($request->session()->has('auth')) {
            return $this->redirectHome($request);
        }

        $step = (string) $request->input('step', '1');

        // ── Bước 1: gửi OTP ──────────────────────────────────
        if ($step === '1') {
            $email = strtolower(trim((string) $request->input('username', '')));

            if (!$email) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Vui lòng nhập địa chỉ email.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return redirect()->route('doi-mat-khau')
                    ->withInput()->with('error', 'Địa chỉ email không hợp lệ.');
            }

            try {
                $user = DB::selectOne(
                    'SELECT ID_User, HoVaTen_User, EmailCaNhan_User, TrangThaiHoatDong_User
                     FROM `User`
                     WHERE LOWER(EmailCaNhan_User) = ?
                     ORDER BY ID_User ASC LIMIT 1',
                    [$email]
                );
            } catch (\Throwable) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Hệ thống tạm thời gián đoạn kết nối CSDL.');
            }

            if (!$user) {
                return redirect()->route('doi-mat-khau')
                    ->withInput()->with('error', 'Không tìm thấy tài khoản với email đã nhập.');
            }

            if (($user->TrangThaiHoatDong_User ?? '') !== 'active') {
                return redirect()->route('doi-mat-khau')
                    ->withInput()->with('error', 'Tài khoản hiện không hoạt động, vui lòng liên hệ quản trị viên.');
            }

            return $this->sendOtp($request, $user);
        }

        // ── Gửi lại OTP ───────────────────────────────────────
        if ($step === 'resend') {
            if (!$request->session()->has('qmk_user_id')) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Phiên làm việc hết hạn, vui lòng thử lại từ đầu.');
            }

            $userId = (int) $request->session()->get('qmk_user_id');

            try {
                $user = DB::selectOne(
                    'SELECT ID_User, HoVaTen_User, EmailCaNhan_User FROM `User` WHERE ID_User = ? LIMIT 1',
                    [$userId]
                );
            } catch (\Throwable) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Hệ thống tạm thời gián đoạn kết nối CSDL.');
            }

            if (!$user) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Không tìm thấy tài khoản.');
            }

            return $this->sendOtp($request, $user, success: true);
        }

        // ── Bước 2: xác minh OTP ─────────────────────────────
        if ($step === '2') {
            if (!$request->session()->has('qmk_otp')) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Phiên làm việc hết hạn, vui lòng thử lại từ đầu.');
            }

            $otp       = (string) $request->input('otp', '');
            $storedOtp = (string) $request->session()->get('qmk_otp');
            $expiry    = (string) $request->session()->get('qmk_otp_expiry', '');

            if ($expiry && now()->gt($expiry)) {
                $request->session()->forget(['qmk_otp', 'qmk_otp_expiry']);
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Mã OTP đã hết hạn. Vui lòng yêu cầu gửi lại mã.');
            }

            if (!hash_equals($storedOtp, $otp)) {
                return redirect()->route('doi-mat-khau')
                    ->with('error', 'Mã OTP không chính xác. Vui lòng kiểm tra lại.');
            }

            $request->session()->forget(['qmk_otp', 'qmk_otp_expiry']);
            return redirect()->route('doi-mat-khau');
        }

        // ── Bước 3: đặt mật khẩu mới ─────────────────────────
        if (!$request->session()->has('qmk_user_id') || $request->session()->has('qmk_otp')) {
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
                [password_hash($newPass, PASSWORD_BCRYPT), $userId]
            );
        } catch (\Throwable) {
            return redirect()->route('doi-mat-khau')
                ->with('error', 'Không thể cập nhật mật khẩu. Vui lòng thử lại.');
        }

        $request->session()->forget(['qmk_user_id', 'qmk_user_name', 'qmk_email_masked', 'qmk_otp', 'qmk_otp_expiry']);

        return redirect()->route('login')
            ->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $th) {
            return redirect()->route('login')->with('error', 'Đăng nhập bằng Google thất bại. Vui lòng thử lại.');
        }

        $email = strtolower((string)$googleUser->getEmail());

        $user = DB::selectOne(
            'SELECT ID_User, Pass_User, PhanQuyen_User, HoVaTen_User, TrangThaiHoatDong_User
             FROM `User`
             WHERE LOWER(EmailCaNhan_User) = ?
             ORDER BY ID_User ASC
             LIMIT 1',
            [$email]
        );

        if ($user) {
            if (($user->TrangThaiHoatDong_User ?? '') === 'pending') {
                return redirect()->route('login')->with('error', 'Tài khoản đang chờ admin duyệt. Vui lòng thử lại sau.');
            }

            if (($user->TrangThaiHoatDong_User ?? '') !== 'active') {
                return redirect()->route('login')->with('error', 'Tài khoản hiện không hoạt động.');
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

        $request->session()->put('google_new_user', [
            'email' => $email,
            'name'  => $googleUser->getName(),
        ]);

        return redirect()->route('auth.google.complete');
    }

    public function showGoogleComplete(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth')) {
            return $this->redirectHome($request);
        }

        if (!$request->session()->has('google_new_user')) {
            return redirect()->route('login');
        }

        return view('auth.google-complete-register', [
            'google_user' => $request->session()->get('google_new_user')
        ]);
    }

    public function googleComplete(Request $request): RedirectResponse
    {
        if (!$request->session()->has('google_new_user')) {
            return redirect()->route('login');
        }

        $googleSession = $request->session()->get('google_new_user');
        $email = $googleSession['email'];

        $name = trim((string) $request->input('name', ''));
        $dob = trim((string) $request->input('dob', ''));
        $role = trim((string) $request->input('role', ''));

        if (!$name || !$dob || !$role) {
            return redirect()->route('auth.google.complete')
                ->with('error', 'Vui lòng điền đầy đủ thông tin.');
        }

        if (!in_array($role, ['hocsinh', 'giaovien'], true)) {
            return redirect()->route('auth.google.complete')
                ->with('error', 'Vai trò không hợp lệ.');
        }

        $roleMap = ['hocsinh' => 'student', 'giaovien' => 'teacher'];
        $randomPassword = password_hash(Str::random(16), PASSWORD_BCRYPT);

        try {
            DB::insert(
                'INSERT INTO `User` (Pass_User, PhanQuyen_User, HoVaTen_User, NgayThangNamSinh_User, EmailCaNhan_User, TrangThaiHoatDong_User)
                 VALUES (?, ?, ?, ?, ?, ?)',
                [$randomPassword, $roleMap[$role], $name, $dob, $email, 'pending']
            );
        } catch (\Throwable $th) {
            return redirect()->route('auth.google.complete')
                ->with('error', 'Hệ thống tạm thời gián đoạn kết nối CSDL.');
        }

        $request->session()->forget('google_new_user');

        return redirect()->route('login')->with('success', 'Hoàn tất đăng ký thành công! Tài khoản đang chờ admin duyệt.');
    }

    private function sendOtp(Request $request, object $user, bool $success = false): RedirectResponse
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            Mail::raw(
                "Xin chào {$user->HoVaTen_User},\n\nMã OTP khôi phục mật khẩu của bạn là:\n\n    {$otp}\n\nMã có hiệu lực trong 5 phút. Không chia sẻ mã này với bất kỳ ai.\n\n– Hệ thống Quản lý Trường học",
                function ($m) use ($user) {
                    $m->to((string) $user->EmailCaNhan_User)
                      ->subject('Mã OTP khôi phục mật khẩu – Hệ thống Quản lý Trường học');
                }
            );
        } catch (\Throwable) {
            return redirect()->route('doi-mat-khau')
                ->with('error', 'Không thể gửi email. Vui lòng thử lại sau.');
        }

        $email = strtolower((string) $user->EmailCaNhan_User);
        $request->session()->put('qmk_user_id',     (int)    $user->ID_User);
        $request->session()->put('qmk_user_name',   (string) $user->HoVaTen_User);
        $request->session()->put('qmk_email_masked', $this->maskEmail($email));
        $request->session()->put('qmk_otp',          $otp);
        $request->session()->put('qmk_otp_expiry',   now()->addMinutes(5)->toIso8601String());

        $msg = $success
            ? 'Đã gửi lại mã OTP mới. Vui lòng kiểm tra hộp thư của bạn.'
            : null;

        return $msg
            ? redirect()->route('doi-mat-khau')->with('success', $msg)
            : redirect()->route('doi-mat-khau');
    }

    private function maskEmail(string $email): string
    {
        $parts  = explode('@', $email, 2);
        $local  = $parts[0];
        $domain = $parts[1] ?? '';
        $len    = mb_strlen($local);
        $show   = min(2, $len);
        $masked = mb_substr($local, 0, $show) . str_repeat('*', max(0, $len - $show));
        return $masked . '@' . $domain;
    }
}
