<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quên mật khẩu – Hệ thống Quản lý Trường học</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <style>
      body { display:flex; flex-direction:column; min-height:100vh; }
      .auth-wrap {
        flex:1; display:flex; align-items:center;
        justify-content:center; padding:34px 14px;
      }
      .auth-card {
        position:relative; background:rgba(255,255,255,0.95);
        border-radius:16px; border:1.5px solid #9fc0ea;
        padding:34px 34px 26px; width:100%; max-width:420px;
        box-shadow:0 18px 40px rgba(18,72,116,0.2); backdrop-filter:blur(2px);
      }
      .auth-card::before {
        content:""; position:absolute; left:0; top:0;
        width:100%; height:5px; border-radius:16px 16px 0 0;
        background:linear-gradient(90deg,#124874,#2f6897);
      }
      .auth-card h2 {
        font-size:22px; font-weight:700; color:#124874;
        margin-bottom:6px; text-align:center; letter-spacing:.2px;
      }
      .auth-subtitle { text-align:center; color:#4e6b88; font-size:13px; margin-bottom:20px; }
      .auth-error {
        margin-bottom:14px; border:1px solid #cf373d;
        background:#ffe9ea; color:#8f2025;
        border-radius:10px; padding:10px 12px; font-size:13px;
      }
      .auth-success {
        margin-bottom:14px; border:1px solid #2e7d32;
        background:#e8f5e9; color:#1b5e20;
        border-radius:10px; padding:10px 12px; font-size:13px;
      }

      /* ── Step bar ── */
      .step-bar {
        display:flex; justify-content:center; gap:0; margin-bottom:24px;
      }
      .step-item {
        display:flex; flex-direction:column; align-items:center;
        flex:1; position:relative;
      }
      .step-item:not(:last-child)::after {
        content:""; position:absolute;
        top:14px; left:calc(50% + 14px); right:calc(-50% + 14px);
        height:2px; background:#c8daf0; z-index:0;
      }
      .step-circle {
        width:28px; height:28px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        font-size:13px; font-weight:700; z-index:1;
        border:2px solid #c8daf0; background:#fff; color:#90a8c4;
      }
      .step-item.active .step-circle  { border-color:#124874; background:#124874; color:#fff; }
      .step-item.done  .step-circle   { border-color:#2e7d32; background:#2e7d32; color:#fff; }
      .step-item:not(:last-child).done::after,
      .step-item:not(:last-child).active::after { background:#124874; }
      .step-label { font-size:11px; color:#90a8c4; margin-top:4px; text-align:center; }
      .step-item.active .step-label  { color:#124874; font-weight:600; }
      .step-item.done  .step-label   { color:#2e7d32; }

      /* ── Badges ── */
      .user-confirm-badge {
        display:flex; align-items:center; gap:10px;
        background:#eef5ff; border:1px solid #b2cff0;
        border-radius:10px; padding:10px 14px; margin-bottom:16px;
      }
      .user-confirm-badge .icon { font-size:22px; color:#124874; }
      .user-confirm-badge .info small { color:#4e6b88; font-size:12px; display:block; }
      .user-confirm-badge .info strong { color:#124874; font-size:14px; }
      .email-badge {
        display:flex; align-items:center; gap:8px;
        background:#f0f8ff; border:1px solid #b2cff0;
        border-radius:10px; padding:9px 13px; margin-bottom:14px;
        font-size:13px; color:#124874;
      }
      .email-badge i { font-size:16px; color:#2f6897; }

      /* ── OTP input ── */
      .otp-input-wrap { position:relative; }
      .otp-input-wrap i {
        position:absolute; left:12px; top:50%;
        transform:translateY(-50%); color:#5d82b8; font-size:14px;
      }
      .otp-input {
        width:100%; padding:11px 12px 11px 36px;
        border:1.5px solid #7fa7de; border-radius:10px;
        font-size:20px; font-weight:700; letter-spacing:8px;
        text-align:center; outline:none; background:#f8fbff;
        transition:border-color .2s,box-shadow .2s;
        box-sizing:border-box;
      }
      .otp-input:focus { border-color:#124874; box-shadow:0 0 0 3px rgba(18,72,116,.14); }
      .otp-hint { font-size:12px; color:#4e6b88; margin-top:4px; text-align:center; }

      /* ── Regular form ── */
      .form-group { margin-bottom:14px; }
      .form-group label { display:block; font-size:13px; font-weight:600; color:#1e4d94; margin-bottom:5px; }
      .field-wrap { position:relative; }
      .field-wrap i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#5d82b8; font-size:14px; }
      .form-group input {
        width:100%; padding:10px 12px 10px 36px;
        border:1.5px solid #7fa7de; border-radius:10px;
        font-size:13px; outline:none; background:#f8fbff;
        transition:border-color .2s,box-shadow .2s;
      }
      .form-group input:focus { border-color:#124874; box-shadow:0 0 0 3px rgba(18,72,116,.14); }
      .hint-text { font-size:12px; color:#4e6b88; margin-top:3px; }

      /* ── Buttons ── */
      .btn-submit {
        width:100%; padding:11px; background:#124874; color:#fff;
        border:none; border-radius:10px; font-size:14px; font-weight:bold;
        cursor:pointer; margin-top:10px; transition:background .2s,transform .2s;
      }
      .btn-submit:hover { background:#0d3657; transform:translateY(-1px); }
      .btn-submit i { margin-right:6px; }
      .btn-back {
        display:block; text-align:center; text-decoration:none;
        width:100%; padding:10px; background:transparent; color:#124874;
        border:1.5px solid #7fa7de; border-radius:10px;
        font-size:13px; cursor:pointer; margin-top:10px;
        transition:background .2s,transform .2s; box-sizing:border-box;
      }
      .btn-back:hover { background:#d3e1f2; transform:translateY(-1px); }
      .btn-back i { margin-right:6px; }
      .resend-wrap { text-align:center; margin-top:10px; }
      .btn-resend {
        background:none; border:none; color:#2f6897; font-size:12.5px;
        cursor:pointer; text-decoration:underline; padding:0;
      }
      .btn-resend:hover { color:#124874; }
    </style>
  </head>
  <body>
    <header class="header">
      <div class="header-left">
        <a href="https://i.ibb.co/s9YdMrTJ/Logo-HCMUE-Gia-tri-cot-loi-1-co-vien.png"
           target="_blank" rel="noopener noreferrer" aria-label="Logo HCMUE">
          <img class="header-logo-img"
               src="https://i.ibb.co/s9YdMrTJ/Logo-HCMUE-Gia-tri-cot-loi-1-co-vien.png"
               alt="Logo Trường ĐHSP TP.HCM" />
        </a>
      </div>
    </header>

    <div class="auth-wrap">
      <div class="auth-card">

        {{-- ── Thanh bước (3 bước) ── --}}
        <div class="step-bar">
          <div class="step-item {{ $step === 1 ? 'active' : 'done' }}">
            <div class="step-circle">@if($step > 1) ✓ @else 1 @endif</div>
            <span class="step-label">Xác minh</span>
          </div>
          <div class="step-item {{ $step === 2 ? 'active' : ($step > 2 ? 'done' : '') }}">
            <div class="step-circle">@if($step > 2) ✓ @else 2 @endif</div>
            <span class="step-label">Nhập OTP</span>
          </div>
          <div class="step-item {{ $step === 3 ? 'active' : '' }}">
            <div class="step-circle">3</div>
            <span class="step-label">Mật khẩu mới</span>
          </div>
        </div>

        @if($step === 1)
          <h2>QUÊN MẬT KHẨU</h2>
          <p class="auth-subtitle">Nhập email đã đăng ký để nhận mã xác nhận</p>
        @elseif($step === 2)
          <h2>NHẬP MÃ OTP</h2>
          <p class="auth-subtitle">Mã 6 chữ số đã được gửi đến email của bạn</p>
        @else
          <h2>ĐẶT MẬT KHẨU MỚI</h2>
          <p class="auth-subtitle">Nhập mật khẩu mới cho tài khoản của bạn</p>
        @endif

        @if(session('error'))
          <div class="auth-error">{{ session('error') }}</div>
        @endif
        @if(session('success'))
          <div class="auth-success">{{ session('success') }}</div>
        @endif

        {{-- ── BƯỚC 1: Nhập email ── --}}
        @if($step === 1)
        <form method="POST" action="{{ route('doi-mat-khau') }}">
          @csrf
          <input type="hidden" name="step" value="1">

          <div class="form-group">
            <label for="username">Địa chỉ email</label>
            <div class="field-wrap">
              <i class="bi bi-envelope"></i>
              <input type="email" id="username" name="username"
                     value="{{ old('username') }}"
                     placeholder="Nhập email đã đăng ký..." required autofocus />
            </div>
          </div>

          <button type="submit" class="btn-submit">
            <i class="bi bi-send"></i>Gửi mã OTP
          </button>
          <a href="{{ route('login') }}" class="btn-back">
            <i class="bi bi-arrow-return-left"></i>Quay lại đăng nhập
          </a>
        </form>

        {{-- ── BƯỚC 2: Nhập OTP ── --}}
        @elseif($step === 2)
        <div class="email-badge">
          <i class="bi bi-envelope-check"></i>
          <span>Mã đã gửi đến <strong>{{ $qmk_email_masked }}</strong></span>
        </div>

        <form method="POST" action="{{ route('doi-mat-khau') }}">
          @csrf
          <input type="hidden" name="step" value="2">

          <div class="form-group">
            <label for="otp">Mã OTP</label>
            <div class="otp-input-wrap">
              <i class="bi bi-shield-lock"></i>
              <input class="otp-input" type="text" id="otp" name="otp"
                     maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                     placeholder="000000" required autofocus
                     oninput="this.value=this.value.replace(/[^0-9]/g,'')" />
            </div>
            <p class="otp-hint"><i class="bi bi-clock"></i> Mã có hiệu lực trong 5 phút</p>
          </div>

          <button type="submit" class="btn-submit">
            <i class="bi bi-check-circle"></i>Xác nhận OTP
          </button>
        </form>

        <div class="resend-wrap">
          <form method="POST" action="{{ route('doi-mat-khau') }}" style="display:inline">
            @csrf
            <input type="hidden" name="step" value="resend">
            <button type="submit" class="btn-resend">
              <i class="bi bi-arrow-clockwise"></i> Gửi lại mã OTP
            </button>
          </form>
        </div>

        <a href="{{ route('doi-mat-khau', ['cancel' => 1]) }}" class="btn-back" style="margin-top:10px">
          <i class="bi bi-arrow-return-left"></i>Quay lại bước trước
        </a>

        {{-- ── BƯỚC 3: Đặt mật khẩu mới ── --}}
        @else
        <div class="user-confirm-badge">
          <div class="icon"><i class="bi bi-person-check-fill"></i></div>
          <div class="info">
            <small>Đặt lại mật khẩu cho</small>
            <strong>{{ $qmk_user_name }}</strong>
          </div>
        </div>

        <form method="POST" action="{{ route('doi-mat-khau') }}">
          @csrf
          <input type="hidden" name="step" value="3">

          <div class="form-group">
            <label for="new_password">Mật khẩu mới</label>
            <div class="field-wrap">
              <i class="bi bi-key"></i>
              <input type="password" id="new_password" name="new_password"
                     placeholder="Nhập mật khẩu mới..." required autofocus />
            </div>
            <p class="hint-text">Tối thiểu 6 ký tự</p>
          </div>

          <div class="form-group">
            <label for="confirm_password">Xác nhận mật khẩu mới</label>
            <div class="field-wrap">
              <i class="bi bi-shield-check"></i>
              <input type="password" id="confirm_password" name="confirm_password"
                     placeholder="Nhập lại mật khẩu mới..." required />
            </div>
          </div>

          <button type="submit" class="btn-submit">
            <i class="bi bi-check-circle"></i>Xác nhận đặt lại mật khẩu
          </button>
          <a href="{{ route('doi-mat-khau', ['cancel' => 1]) }}" class="btn-back">
            <i class="bi bi-arrow-return-left"></i>Quay lại từ đầu
          </a>
        </form>
        @endif

      </div>
    </div>
  </body>
</html>
