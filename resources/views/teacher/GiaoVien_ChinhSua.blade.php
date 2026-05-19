<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thông tin</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div> <div class="layout">
    <div id="app-sidebar"></div> <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ GIÁO VIÊN</h2></div>
        <div class="content-box">
            <div class="section-title blue">Chỉnh sửa thông tin cá nhân</div>
            
            <form action="{{ route('teacher.profile-update') }}" method="POST">
    @csrf
    <table class="info-table" style="max-width:600px">
        <tr>
            <td class="info-label">Họ và tên</td>
            <td><input type="text" name="HoVaTen_User" value="{{ $teacher->HoVaTen_User }}" class="form-control" style="width: 100%;"></td>
        </tr>
        <tr>
            <td class="info-label">Ngày sinh</td>
            <td><input type="date" name="NgayThangNamSinh_User" value="{{ $teacher->NgayThangNamSinh_User }}" class="form-control" style="width: 100%;"></td>
        </tr>
        <tr>
            <td class="info-label">Môn phụ trách</td>
            <td>
                <select name="PhuTrachMon_User" class="form-control" style="width: 100%;">
                    @foreach($monHocs as $mh)
                        <option value="{{ $mh->ID_MonHoc }}" {{ $teacher->PhuTrachMon_User == $mh->ID_MonHoc ? 'selected' : '' }}>
                            {{ $mh->Ten_MonHoc }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td class="info-label">Khối phụ trách</td>
            <td>
                <select name="PhuTrachKhoi_User" class="form-control" style="width: 100%;">
                    @foreach($khoiLops as $kl)
                        <option value="{{ $kl->ID_KhoiLop }}" {{ $teacher->PhuTrachKhoi_User == $kl->ID_KhoiLop ? 'selected' : '' }}>
                            {{ $kl->Ten_KhoiLop }}
                        </option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td class="info-label">Email</td>
            <td><input type="email" name="EmailCaNhan_User" value="{{ $teacher->EmailCaNhan_User }}" class="form-control" style="width: 100%;"></td>
        </tr>
        <tr>
            <td class="info-label">Số điện thoại</td>
            <td><input type="text" name="SoDienThoai_User" value="{{ $teacher->SoDienThoai_User }}" class="form-control" style="width: 100%;"></td>
        </tr>
    </table>
    <br>
    <button type="submit" class="btn-primary">Lưu thay đổi</button>
</form>
        </div>
    </main>
</div>

<script>
    window.PAGE_ROLE = 'giaovien'; 
    window.PAGE_ACTIVE = 'gv-thongtin'; // Đánh dấu menu này đang active
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>