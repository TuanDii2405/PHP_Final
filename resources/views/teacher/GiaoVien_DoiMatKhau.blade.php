<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Giáo viên – Đổi mật khẩu</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>
<body>
    <div id="app-header"></div>
    <div class="layout">
        <div id="app-sidebar"></div>
        <main class="main-content">
            <div class="role-title-box"><h2>VAI TRÒ GIÁO VIÊN</h2></div>
            <div class="content-box">
                <div class="section-title blue">Đổi mật khẩu</div>

                @if(session('success'))
                    <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div style="color: red; margin-bottom: 10px;">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('teacher.doi-mat-khau.xu-ly') }}" method="POST" style="max-width: 440px">
                    @csrf
                    <div class="form-group-inline">
    <label>Mật khẩu hiện tại</label>
    <input type="password" name="old_password" placeholder="Nhập mật khẩu hiện tại..." required />
</div>

<div class="form-group-inline">
    <label>Mật khẩu mới</label>
    <input type="password" name="new_password" placeholder="Nhập mật khẩu mới..." required />
</div>

<div class="form-group-inline">
    <label>Xác nhận mật khẩu mới</label>
    <input type="password" name="new_password_confirmation" placeholder="Nhập lại mật khẩu mới..." required />
</div>
                    <button type="submit" class="btn-primary">
                        Xác nhận đổi mật khẩu
                    </button>
                </form>
            </div>
        </main>
    </div>
    
    <script>
        window.PAGE_ROLE = "giaovien";
        window.PAGE_ACTIVE = "gv-doimatkhau";
    </script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>