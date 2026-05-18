<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giáo viên – Danh sách lớp học</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ GIÁO VIÊN</h2></div>
        <div class="content-box">
            <div class="section-title blue">Danh sách lớp học</div>
            <div class="action-bar">
                <button class="action-btn" onclick="alert('Tạo lớp học mới!')">+ Tạo lớp</button>
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên lớp học</th>
                            <th>Khối lớp</th>
                            <th>Môn học</th>
                            <th>Số học sinh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lopHocs as $i => $lh)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $lh->TenLopHoc }}</td>
                            <td>{{ $lh->Ten_KhoiLop }}</td>
                            <td>{{ $lh->Ten_MonHoc }}</td>
                            <td>{{ $lh->so_hoc_sinh }}</td>
                            <td>
                                <a class="tbl-link" onclick="alert('Xem chi tiết {{ $lh->TenLopHoc }}!')">Xem chi tiết</a>
                                &nbsp;|&nbsp;
                                <a class="tbl-link" href="{{ route('teacher.diem-danh') }}">Điểm danh</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-notice">Bạn chưa phụ trách lớp học nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>window.PAGE_ROLE = 'giaovien'; window.PAGE_ACTIVE = 'ds-lophoc';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
