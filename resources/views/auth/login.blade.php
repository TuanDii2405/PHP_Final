<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập - Hệ thống Quản lý Trường học</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .auth-wrap {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 34px 14px;
    }

    .auth-card {
        position: relative;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        border: 1.5px solid #9fc0ea;
        padding: 34px 34px 26px;
        width: 100%;
        max-width: 430px;
        box-shadow: 0 18px 40px rgba(31, 90, 179, 0.2);
        backdrop-filter: blur(2px);
    }

    .auth-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 5px;
        border-radius: 16px 16px 0 0;
        background: linear-gradient(90deg, #1f5ab3, #4f86d7);
    }

    .auth-card h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1e4d94;
        margin-bottom: 8px;
        text-align: center;
        letter-spacing: 0.2px;
    }

    .auth-subtitle {
        text-align: center;
        color: #5377a8;
        font-size: 13px;
        margin-bottom: 22px;
    }

    .auth-error {
        margin-bottom: 14px;
        border: 1px solid #cf373d;
        background: #ffe9ea;
        color: #8f2025;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
    }

    .auth-success {
        margin-bottom: 14px;
        border: 1px solid #1e8a3c;
        background: #e6f9ed;
        color: #155d2e;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1e4d94;
        margin-bottom: 6px;
    }

    .field-wrap {
        position: relative;
    }

    .field-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #5d82b8;
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 10px 12px 10px 36px;
        border: 1.5px solid #7fa7de;
        border-radius: 10px;
        font-size: 13px;
        outline: none;
        background: #f8fbff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
        border-color: #1f5ab3;
        box-shadow: 0 0 0 3px rgba(31, 90, 179, 0.14);
    }

    .btn-submit {
        width: 100%;
        padding: 11px;
        background: #1f5ab3;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
        transition: background 0.2s, transform 0.2s;
    }

    .btn-submit:hover {
        background: #174890;
        transform: translateY(-1px);
    }

    .btn-submit i {
        margin-right: 6px;
    }

    .btn-google {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 10px;
        background: #ffffff;
        color: #3c4043;
        border: 1.5px solid #dbe9ff;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.2s, box-shadow 0.2s, border-color 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .btn-google:hover {
        background: #f8fbff;
        box-shadow: 0 4px 12px rgba(31,90,179,0.15);
        border-color: #9fc0ea;
    }

    .btn-google img {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    .divider-text {
        text-align: center;
        margin: 15px 0;
        position: relative;
    }

    .divider-text::before {
        content: "";
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: #dbe9ff;
        z-index: 1;
    }

    .divider-text span {
        background: #fff;
        padding: 0 10px;
        color: #5377a8;
        font-size: 13px;
        position: relative;
        z-index: 2;
    }

    .auth-links {
        margin-top: 18px;
        text-align: center;
        font-size: 13px;
    }

    .auth-links a {
        display: inline-block;
        padding: 6px 18px;
        margin: 0 4px;
        background: #dbe9ff;
        border: 1.5px solid #7fa7de;
        border-radius: 20px;
        color: #1e4d94;
        text-decoration: none;
        cursor: pointer;
        font-size: 12.5px;
        transition: background 0.2s, transform 0.2s;
    }

    .auth-links a:hover {
        background: #c5dcff;
        transform: translateY(-1px);
    }

    .auth-links i {
        margin-right: 5px;
    }

    .divider {
        display: none;
    }
    </style>
</head>

<body>
    <header class="header">
        <div class="header-left">
            <a href="https://i.ibb.co/s9YdMrTJ/Logo-HCMUE-Gia-tri-cot-loi-1-co-vien.png" target="_blank"
                rel="noopener noreferrer" aria-label="Logo HCMUE">
                <img class="header-logo-img" src="https://i.ibb.co/s9YdMrTJ/Logo-HCMUE-Gia-tri-cot-loi-1-co-vien.png"
                    alt="Logo Trường ĐHSP TP.HCM" />
            </a>
        </div>
    </header>

    <div class="auth-wrap">
        <div class="auth-card">
            <h2>ĐĂNG NHẬP HỆ THỐNG</h2>
            <p class="auth-subtitle">Truy cập cổng học tập và quản lý của bạn</p>

            @if(session('success'))
            <div class="auth-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="auth-error">{{ session('error') }}</div>
            @endif

            <form method="post" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Email</label>
                    <div class="field-wrap">
                        <i class="bi bi-envelope"></i>
                        <input type="email" id="username" name="username" value="{{ old('username') }}" placeholder="Nhập địa chỉ email..." required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="field-wrap">
                        <i class="bi bi-key"></i>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu..." required />
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-box-arrow-in-right"></i>Đăng nhập
                </button>
            </form>

            <div class="divider-text">
                <span>Hoặc</span>
            </div>

            <a href="{{ route('auth.google') }}" class="btn-google">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Logo">
                Đăng nhập bằng Google
            </a>

            <div class="auth-links">
                <a href="{{ route('register') }}"><i class="bi bi-person-plus"></i>Đăng ký tài khoản</a>
                <span class="divider">|</span>
                <a href="{{ route('doi-mat-khau') }}"><i class="bi bi-key"></i>Quên mật khẩu?</a>
            </div>
        </div>
    </div>
</body>

</html>
