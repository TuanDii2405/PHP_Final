<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học sinh – Thông tin cá nhân</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .section-divider { border:none; border-top:1.5px solid var(--cerulean-200,#d0e8f8);
                           margin:28px 0 20px; }
    </style>
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
            <div class="section-title blue">Thông tin cá nhân</div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $e) {{ $e }}<br> @endforeach
                </div>
            @endif

            @if ($student)
            {{-- ── Bảng thông tin ── --}}
            <table class="info-table" style="max-width:620px">
                <tr>
                    <td class="info-label">Họ và tên</td>
                    <td><span class="info-value">{{ $student->HoVaTen_User }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Ngày sinh</td>
                    <td><span class="info-value">{{ $student->NgayThangNamSinh_User ? \Carbon\Carbon::parse($student->NgayThangNamSinh_User)->format('d/m/Y') : '—' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Email</td>
                    <td><span class="info-value">{{ $student->EmailCaNhan_User ?? '—' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Số điện thoại</td>
                    <td><span class="info-value">{{ $student->SoDienThoai_User ?? '—' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Số kỳ thi đã làm</td>
                    <td><span class="info-value">{{ $student->so_ky_thi }} bài thi</span></td>
                </tr>
                <tr>
                    <td class="info-label">Ngày đăng ký</td>
                    <td><span class="info-value">{{ \Carbon\Carbon::parse($student->NgayTaoTaiKhoan_User)->format('d/m/Y') }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Trạng thái</td>
                    <td><span class="info-value">{{ $student->TrangThaiHoatDong_User === 'active' ? 'Hoạt động' : 'Không hoạt động' }}</span></td>
                </tr>
            </table>
            <br>
            <div style="display:flex;gap:12px;flex-wrap:wrap">
                <button class="btn-primary" onclick="openEdit()">Cập nhật thông tin</button>
                <button class="btn-primary" onclick="openPassword()">Đổi mật khẩu</button>
            </div>


            @else
            <div class="empty-notice">Không tìm thấy thông tin tài khoản.</div>
            @endif
        </div>
    </main>
</div>

{{-- MODAL đổi mật khẩu --}}
<div id="modalPassword" class="modal-overlay" style="display:none" onclick="if(event.target===this)closePassword()">
    <div class="modal-box" style="width:440px">
        <div class="modal-header">
            <span class="modal-header-title">Đổi mật khẩu</span>
            <button class="modal-close" onclick="closePassword()">×</button>
        </div>
        <form method="POST" action="{{ route('student.doi-mat-khau.update') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Mật khẩu hiện tại <span class="required">*</span></label>
                    <input class="form-input" type="password" name="mat_khau_cu"
                           placeholder="Nhập mật khẩu hiện tại..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mật khẩu mới <span class="required">*</span></label>
                    <input class="form-input" type="password" name="mat_khau_moi"
                           placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Xác nhận mật khẩu mới <span class="required">*</span></label>
                    <input class="form-input" type="password" name="xac_nhan"
                           placeholder="Nhập lại mật khẩu mới..." required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closePassword()">Hủy</button>
                <button type="submit" class="action-btn">Xác nhận</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL cập nhật thông tin --}}
<div id="modalEdit" class="modal-overlay" style="display:none" onclick="if(event.target===this)closeEdit()">
    <div class="modal-box" style="width:480px">
        <div class="modal-header">
            <span class="modal-header-title">Cập nhật thông tin cá nhân</span>
            <button class="modal-close" onclick="closeEdit()">×</button>
        </div>
        <form method="POST" action="{{ route('student.thong-tin.update') }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Họ và tên <span class="required">*</span></label>
                    <input class="form-input" type="text" name="HoVaTen_User" id="f_hoten"
                           required maxlength="150">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input class="form-input" type="email" name="EmailCaNhan_User" id="f_email" maxlength="150">
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input class="form-input" type="text" name="SoDienThoai_User" id="f_sdt" maxlength="20">
                </div>
                <div class="form-group">
                    <label class="form-label">Ngày sinh</label>
                    <input class="form-input" type="date" name="NgayThangNamSinh_User" id="f_ngaysinh">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEdit()">Hủy</button>
                <button type="submit" class="action-btn">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.PAGE_USER_NAME = "{{ session('auth.name') }}";
    window.PAGE_ROLE   = 'hocsinh';
    window.PAGE_ACTIVE = 'hs-thongtin';

    @if ($student)
    const studentData = @json($student);
    @endif

    function openPassword() {
        document.getElementById('modalPassword').style.display = 'flex';
    }

    function closePassword() {
        document.getElementById('modalPassword').style.display = 'none';
    }

    @if ($errors->hasAny(['mat_khau_cu','mat_khau_moi','xac_nhan']))
    document.addEventListener('DOMContentLoaded', function () { openPassword(); });
    @endif

    function openEdit() {
        document.getElementById('f_hoten').value    = studentData.HoVaTen_User    || '';
        document.getElementById('f_email').value    = studentData.EmailCaNhan_User || '';
        document.getElementById('f_sdt').value      = studentData.SoDienThoai_User || '';
        const dob = studentData.NgayThangNamSinh_User
            ? studentData.NgayThangNamSinh_User.substring(0, 10) : '';
        document.getElementById('f_ngaysinh').value = dob;
        document.getElementById('modalEdit').style.display = 'flex';
        document.getElementById('f_hoten').focus();
    }

    function closeEdit() {
        document.getElementById('modalEdit').style.display = 'none';
    }
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
