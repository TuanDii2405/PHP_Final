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
                            <th>Tình trạng điểm danh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($diemDanhs as $i => $dd)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $dd->TenLopHoc }}</td>
                            <td>{{ $dd->ten_giao_vien }}</td>
                            <td>{{ $dd->Ten_MonHoc }}</td>
                            <td>{{ \Carbon\Carbon::parse($dd->NgayHoc_DiemDanh)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $trangThai = match($dd->TrangThaiBuoiHoc_DiemDanh) {
                                        'scheduled'   => 'Đã lên lịch',
                                        'in_progress' => 'Đang diễn ra',
                                        'completed'   => 'Đã hoàn thành',
                                        'cancelled'   => 'Đã hủy',
                                        default       => $dd->TrangThaiBuoiHoc_DiemDanh,
                                    };
                                @endphp
                                {{ $trangThai }}
                            </td>
                            <td>
                                @if ($dd->tinh_trang_ca_nhan === 'present')
                                    <span style="color:#198754;font-weight:600">Có mặt</span>
                                @elseif ($dd->tinh_trang_ca_nhan === 'absent')
                                    <span style="color:#dc3545;font-weight:600">Vắng mặt</span>
                                @elseif ($dd->tinh_trang_ca_nhan === 'late')
                                    <span style="color:#fd7e14;font-weight:600">Đi trễ</span>
                                @elseif ($dd->tinh_trang_ca_nhan === 'excused')
                                    <span style="color:#6c757d;font-weight:600">Có phép</span>
                                @else
                                    <span style="color:#aaa">Chưa điểm danh</span>
                                @endif
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
<script>window.PAGE_USER_NAME = "{{ session('auth.name') }}";
      window.PAGE_ROLE = 'hocsinh'; window.PAGE_ACTIVE = 'hs-diemdanh';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
