<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học sinh – Lịch sử làm bài</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
            <div class="section-title blue">Lịch sử làm bài</div>
            <div class="action-bar">
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Kỳ thi</th>
                            <th>Môn học</th>
                            <th>Thời gian nộp</th>
                            <th>Điểm số</th>
                            <th>Thời gian làm (phút)</th>
                            <th>Điểm 4PA</th>
                            <th>Điểm Đúng Sai</th>
                            <th>Điểm Ngắn</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lichSus as $ls)
                        <tr>
                            <td>{{ $ls->Ten_KyThi }}</td>
                            <td>{{ $ls->Ten_MonHoc }}</td>
                            <td>{{ \Carbon\Carbon::parse($ls->ThoiGianKetThuc_DiemSo)->format('H:i d/m/Y') }}</td>
                            <td>{{ number_format($ls->TongDiem_DiemSo, 2) }}</td>
                            <td>{{ $ls->ThoiGianLamBai_DiemSo }}</td>
                            <td>{{ number_format($ls->DiemPhanTracNghiem4PhuongAn_DiemSo, 2) }}</td>
                            <td>{{ number_format($ls->DiemPhanTracNghiemDungSai_DiemSo, 2) }}</td>
                            <td>{{ number_format($ls->DiemPhanTracNghiemTraLoiNgan_DiemSo, 2) }}</td>
                            <td>
                                <a class="tbl-link" onclick="alert('Xem chi tiết bài thi!')">Xem chi tiết</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="empty-notice">Bạn chưa tham gia kỳ thi nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>window.PAGE_ROLE = 'hocsinh'; window.PAGE_ACTIVE = 'hs-lichsu-lamdai';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
