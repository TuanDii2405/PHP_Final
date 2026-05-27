<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hoàn tất đăng ký – Hệ thống Quản lý Trường học</title>
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
        max-width: 460px;
        box-shadow: 0 18px 40px rgba(18, 72, 116, 0.2);
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
        background: linear-gradient(90deg, #124874, #2f6897);
      }
      .auth-card h2 {
        font-size: 24px;
        font-weight: 700;
        color: #124874;
        margin-bottom: 8px;
        text-align: center;
        letter-spacing: 0.2px;
      }
      .auth-subtitle {
        text-align: center;
        color: #4e6b88;
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
      .form-group {
        margin-bottom: 14px;
      }
      .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1e4d94;
        margin-bottom: 5px;
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
      .form-group input,
      .form-group select {
        width: 100%;
        padding: 10px 12px 10px 36px;
        border: 1.5px solid #7fa7de;
        border-radius: 10px;
        font-size: 13px;
        outline: none;
        background: #f8fbff;
        transition: border-color 0.2s, box-shadow 0.2s;
      }
      .form-group input:disabled {
        background: #e6eef6;
        color: #4e6b88;
        cursor: not-allowed;
      }
      .form-group input:focus,
      .form-group select:focus {
        border-color: #124874;
        box-shadow: 0 0 0 3px rgba(18, 72, 116, 0.14);
      }
      .btn-submit {
        width: 100%;
        padding: 11px;
        background: #124874;
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
        background: #0d3657;
        transform: translateY(-1px);
      }
      .btn-submit i {
        margin-right: 6px;
      }
      .auth-links {
        margin-top: 18px;
        text-align: center;
        font-size: 13px;
        color: #4e6b88;
      }
      .auth-links a {
        display: inline-block;
        padding: 6px 18px;
        background: #e6eef6;
        border: 1.5px solid #7fa7de;
        border-radius: 20px;
        color: #124874;
        text-decoration: none;
        cursor: pointer;
        font-size: 12.5px;
        transition: background 0.2s, transform 0.2s;
      }
      .auth-links a:hover {
        background: #d3e1f2;
        transform: translateY(-1px);
      }
      .auth-links i {
        margin-right: 5px;
      }
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
        <h2>HOÀN TẤT ĐĂNG KÝ</h2>
        <p class="auth-subtitle">Vui lòng cung cấp thêm thông tin để hoàn tất tài khoản</p>

        @if(session('error'))
        <div class="auth-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('auth.google.complete') }}">
          @csrf

          <div class="form-group">
            <label for="name">Họ và tên</label>
            <div class="field-wrap">
              <i class="bi bi-person"></i>
              <input type="text" id="name" name="name" value="{{ $google_user['name'] }}" required />
            </div>
          </div>

          <div class="form-group">
            <label>Email (Từ Google)</label>
            <div class="field-wrap">
              <i class="bi bi-envelope"></i>
              <input type="email" value="{{ $google_user['email'] }}" disabled />
            </div>
          </div>

          <div class="form-group">
            <label for="dob">Ngày sinh</label>
            <div class="field-wrap">
              <i class="bi bi-calendar3"></i>
              <input type="date" id="dob" name="dob" required />
            </div>
          </div>
          
          <div class="form-group">
            <label for="role">Vai trò</label>
            <div class="field-wrap">
              <i class="bi bi-person-badge"></i>
              <select id="role" name="role" required>
                <option value="">-- Chọn vai trò --</option>
                <option value="hocsinh">Học sinh</option>
                <option value="giaovien">Giáo viên</option>
              </select>
            </div>
          </div>

          <button type="submit" class="btn-submit">
            <i class="bi bi-check-circle"></i>Hoàn tất đăng ký
          </button>
        </form>

        <div class="auth-links">
          <a href="{{ route('login') }}"><i class="bi bi-arrow-left"></i> Quay lại đăng nhập</a>
        </div>
      </div>
    </div>
  </body>
</html>
