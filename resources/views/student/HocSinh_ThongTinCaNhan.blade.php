<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học sinh – Thông tin cá nhân</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
            <div class="section-title blue">Thông tin cá nhân</div>
            @if ($student)
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
            @else
            <div class="empty-notice">Không tìm thấy thông tin tài khoản.</div>
            @endif
        </div>
    </main>
</div>
<script>window.PAGE_ROLE = 'hocsinh'; window.PAGE_ACTIVE = 'hs-thongtin';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
