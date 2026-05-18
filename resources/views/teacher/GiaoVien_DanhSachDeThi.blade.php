<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giáo viên – Danh sách đề thi</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ GIÁO VIÊN</h2></div>
        <div class="content-box">
            <div class="section-title blue">Danh sách đề thi</div>
            <div class="action-bar">
                <button class="action-btn" onclick="alert('Tạo đề thi mới!')">+ Tạo đề thi</button>
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên đề thi</th>
                            <th>Môn học</th>
                            <th>Khối lớp</th>
                            <th>Tổng câu</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deThis as $i => $dt)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $dt->TenDeThi }}</td>
                            <td>{{ $dt->Ten_MonHoc }}</td>
                            <td>{{ $dt->Ten_KhoiLop }}</td>
                            <td>{{ $dt->tong_cau_hoi }}</td>
                            <td>
                                <button class="btn-edit" onclick="alert('Sửa đề thi!')">Sửa</button>
                                <button class="btn-danger" onclick="confirmDelete('đề thi này')">Xóa</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-notice">Bạn chưa tạo đề thi nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>
    window.PAGE_ROLE = 'giaovien'; window.PAGE_ACTIVE = 'ds-dethi';
    function confirmDelete(name) {
        if (confirm('Bạn có chắc muốn xóa ' + name + '?')) alert('Đã xóa!');
    }
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
