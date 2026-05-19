<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học sinh – Lịch sử điểm danh</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
            <div class="section-title blue">Lịch sử điểm danh</div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Lớp học</th>
                            <th>Giáo viên</th>
                            <th>Môn học</th>
                            <th>Ngày học</th>
                            <th>Trạng thái buổi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Sử dụng biến $attendances và các tên cột khớp với Controller --}}
                        @forelse ($attendances as $i => $dd)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $dd->TenLopHoc }}</td>
                            <td>{{ $dd->TenGiaoVien }}</td>
                            <td>{{ $dd->Ten_MonHoc }}</td>
                            <td>{{ \Carbon\Carbon::parse($dd->NgayHoc_DiemDanh)->format('d/m/Y') }}</td>
                            <td>
                                {{-- Làm đẹp trạng thái buổi học --}}
                                @if($dd->TrangThaiBuoiHoc_DiemDanh == 'completed')
                                    Đã hoàn thành
                                @else
                                    {{ $dd->TrangThaiBuoiHoc_DiemDanh }}
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-notice">Chưa có dữ liệu điểm danh</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>window.PAGE_ROLE = 'hocsinh'; window.PAGE_ACTIVE = 'hs-diemdanh';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>