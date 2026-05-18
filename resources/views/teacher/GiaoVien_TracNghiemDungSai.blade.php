<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giáo viên – Trắc nghiệm đúng sai</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ GIÁO VIÊN</h2></div>
        <div class="content-box">
            <div class="section-title blue">Ngân hàng câu hỏi – Trắc nghiệm Đúng / Sai</div>
            <div class="action-bar">
                <button class="action-btn" onclick="alert('Thêm câu hỏi mới!')">+ Thêm câu hỏi</button>
                <button class="action-btn" onclick="alert('Lọc theo chủ đề!')">Lọc</button>
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Nội dung câu hỏi</th>
                            <th>Mệnh đề 1</th>
                            <th>Mệnh đề 2</th>
                            <th>Mệnh đề 3</th>
                            <th>Mệnh đề 4</th>
                            <th>Chủ đề</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cauHois as $i => $ch)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td style="text-align:left">{{ \Illuminate\Support\Str::limit($ch->NoiDungCauHoi_TracNghiemDungSai, 60) }}</td>
                            <td>{{ $ch->NoiDungMenhDe1_TracNghiemDungSai }}</td>
                            <td>{{ $ch->NoiDungMenhDe2_TracNghiemDungSai }}</td>
                            <td>{{ $ch->NoiDungMenhDe3_TracNghiemDungSai }}</td>
                            <td>{{ $ch->NoiDungMenhDe4_TracNghiemDungSai }}</td>
                            <td>{{ $ch->NoiDung_ChuDe }}</td>
                            <td>
                                <button class="btn-edit" onclick="alert('Sửa!')">Sửa</button>
                                <button class="btn-danger" onclick="confirmDelete('câu hỏi này')">Xóa</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="empty-notice">Chưa có câu hỏi nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>
    window.PAGE_ROLE = 'giaovien'; window.PAGE_ACTIVE = 'tn-dungsai';
    function confirmDelete(name) {
        if (confirm('Bạn có chắc muốn xóa ' + name + '?')) alert('Đã xóa!');
    }
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
