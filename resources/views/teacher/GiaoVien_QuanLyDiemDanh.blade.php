<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giáo viên – Quản lý điểm danh</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ GIÁO VIÊN</h2></div>
        <div class="content-box">
            <div class="section-title blue">Quản lý điểm danh</div>
            <div class="action-bar">
                <button class="action-btn" onclick="alert('Tạo buổi điểm danh mới!')">+ Tạo buổi điểm danh</button>
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Học sinh</th>
                            <th>Lớp học</th>
                            <th>Môn học</th>
                            <th>Ngày học</th>
                            <th>Trạng thái buổi</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($diemDanhs as $i => $dd)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $dd->ten_hoc_sinh }}</td>
                            <td>{{ $dd->TenLopHoc }}</td>
                            <td>{{ $dd->Ten_MonHoc }}</td>
                            <td>{{ \Carbon\Carbon::parse($dd->NgayHoc_DiemDanh)->format('d/m/Y') }}</td>
                            <td>{{ $dd->TrangThaiBuoiHoc_DiemDanh }}</td>
                            <td>
                                <button class="btn-edit" onclick="alert('Cập nhật điểm danh!')">Cập nhật</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="empty-notice">Chưa có dữ liệu điểm danh</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>window.PAGE_ROLE = 'giaovien'; window.PAGE_ACTIVE = 'diemdanh';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
