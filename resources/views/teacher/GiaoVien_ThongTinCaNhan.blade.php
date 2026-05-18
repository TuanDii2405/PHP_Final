<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giáo viên – Thông tin cá nhân</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ GIÁO VIÊN</h2></div>
        <div class="content-box">
            <div class="section-title blue">Thông tin cá nhân</div>
            @if ($teacher)
            <table class="info-table" style="max-width:600px">
                <tr>
                    <td class="info-label">Họ và tên</td>
                    <td><span class="info-value">{{ $teacher->HoVaTen_User }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Ngày sinh</td>
                    <td><span class="info-value">{{ $teacher->NgayThangNamSinh_User ? \Carbon\Carbon::parse($teacher->NgayThangNamSinh_User)->format('d/m/Y') : '—' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Môn phụ trách</td>
                    <td><span class="info-value">{{ $teacher->Ten_MonHoc ?? '—' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Khối phụ trách</td>
                    <td><span class="info-value">{{ $teacher->Ten_KhoiLop ?? '—' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Email</td>
                    <td><span class="info-value">{{ $teacher->EmailCaNhan_User ?? '—' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Số điện thoại</td>
                    <td><span class="info-value">{{ $teacher->SoDienThoai_User ?? '—' }}</span></td>
                </tr>
                <tr>
                    <td class="info-label">Số lớp đang dạy</td>
                    <td><span class="info-value">{{ $teacher->so_lop }} lớp</span></td>
                </tr>
                <tr>
                    <td class="info-label">Ngày tham gia</td>
                    <td><span class="info-value">{{ \Carbon\Carbon::parse($teacher->NgayTaoTaiKhoan_User)->format('d/m/Y') }}</span></td>
                </tr>
            </table>
            <br>
            <button class="btn-primary" onclick="alert('Cập nhật thông tin!')">Cập nhật thông tin</button>
            @else
            <div class="empty-notice">Không tìm thấy thông tin tài khoản.</div>
            @endif
        </div>
    </main>
</div>
<script>window.PAGE_ROLE = 'giaovien'; window.PAGE_ACTIVE = 'gv-thongtin';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
