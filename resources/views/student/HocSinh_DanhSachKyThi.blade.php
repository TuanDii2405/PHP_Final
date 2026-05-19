<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học sinh – Danh sách kỳ thi</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
            <div class="section-title blue">Danh sách kỳ thi</div>
            <div class="action-bar">
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Tên kỳ thi</th>
                            <th>Môn học</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Thời gian (phút)</th>
                            <th>Số câu (4PA|DS|Ngắn)</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kyThis as $kt)
                        @php
                            $batDau = $kt->ThoiGianBatDau_KyThi ? \Carbon\Carbon::parse($kt->ThoiGianBatDau_KyThi) : null;
                            $ketThuc = $kt->ThoiGianKetThuc_KyThi ? \Carbon\Carbon::parse($kt->ThoiGianKetThuc_KyThi) : null;
                            $now = now();
                            $dangMo = $batDau && $ketThuc && $now->between($batDau, $ketThuc);
                        @endphp
                        <tr>
                            <td>{{ $kt->Ten_KyThi }}</td>
                            <td>{{ $kt->Ten_MonHoc }}</td>
                            <td>{{ $batDau ? $batDau->format('d/m/Y H:i') : '—' }}</td>
                            <td>{{ $kt->ThoiGianLamBai_KyThi }}</td>
                            <td>{{ $kt->SoCauHoiTracNghiem4PhuongAn_KyThi }}|{{ $kt->SoCauHoiTracNghiemDungSai_KyThi }}|{{ $kt->SoCauHoiTracNghiemTraLoiNgan_KyThi }}</td>
                            <td>
                                @if ($kt->da_nop)
                                    <span style="color:#27ae60;font-size:12px;font-weight:600">Đã hoàn thành</span>
                                @elseif ($dangMo)
                                    <a class="tbl-link" href="{{ route('student.tham-gia-thi', ['id_kythi' => $kt->ID_KyThi]) }}">Tham gia thi</a>
                                @else
                                    <span style="color:#aaa;font-size:12px">{{ $batDau && $now->lt($batDau) ? 'Chưa mở' : 'Đã kết thúc' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-notice">Chưa có kỳ thi nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>window.PAGE_USER_NAME = "{{ session('auth.name') }}";
      window.PAGE_ROLE = 'hocsinh'; window.PAGE_ACTIVE = 'hs-ds-kythi';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
