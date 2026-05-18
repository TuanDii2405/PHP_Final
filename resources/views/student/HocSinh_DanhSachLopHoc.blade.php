<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học sinh – Danh sách lớp học</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
            <div class="section-title blue">Danh sách lớp học</div>
            <div class="action-bar">
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Tên lớp học</th>
                            <th>Khối lớp</th>
                            <th>Môn học</th>
                            <th>Giáo viên</th>
                            <th>Năm học</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lopHocs as $lh)
                        <tr>
                            <td>{{ $lh->TenLopHoc }}</td>
                            <td>{{ $lh->Ten_KhoiLop }}</td>
                            <td>{{ $lh->Ten_MonHoc }}</td>
                            <td>{{ $lh->ten_giao_vien }}</td>
                            <td>{{ $lh->NamHoc }}</td>
                            <td>
                                <a class="tbl-link" onclick="alert('Xem lịch học {{ $lh->TenLopHoc }}!')">Xem lịch học</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-notice">Bạn chưa tham gia lớp học nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>window.PAGE_ROLE = 'hocsinh'; window.PAGE_ACTIVE = 'hs-ds-lophoc';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
