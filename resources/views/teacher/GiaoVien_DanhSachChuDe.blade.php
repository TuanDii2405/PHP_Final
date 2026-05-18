<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giáo viên – Danh sách chủ đề</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ GIÁO VIÊN</h2></div>
        <div class="content-box">
            <div class="section-title blue">Danh sách chủ đề</div>
            <div class="action-bar">
                <button class="action-btn" onclick="alert('Tạo chủ đề mới!')">+ Tạo chủ đề</button>
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên chủ đề</th>
                            <th>Môn học</th>
                            <th>Khối lớp</th>
                            <th>Số câu hỏi</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chuDes as $i => $cd)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $cd->NoiDung_ChuDe }}</td>
                            <td>{{ $cd->Ten_MonHoc }}</td>
                            <td>{{ $cd->Ten_KhoiLop }}</td>
                            <td>{{ $cd->tong_cau_hoi }}</td>
                            <td>
                                <button class="btn-edit" onclick="alert('Sửa chủ đề!')">Sửa</button>
                                <button class="btn-danger" onclick="confirmDelete('chủ đề này')">Xóa</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-notice">Bạn chưa tạo chủ đề nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>
    window.PAGE_ROLE = 'giaovien'; window.PAGE_ACTIVE = 'ds-chude';
    function confirmDelete(name) {
        if (confirm('Bạn có chắc muốn xóa ' + name + '?')) alert('Đã xóa!');
    }
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
