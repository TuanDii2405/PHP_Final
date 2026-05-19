<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học sinh – Danh sách lớp học</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .lich-row td { padding: 0; border-top: none; }
        .lich-inner { padding: 12px 20px 16px; background: #f7faff; border-top: 1.5px solid var(--cerulean-200, #d0e8f8); }
        .lich-inner .section-title { font-size: 13px; margin-bottom: 8px; }
        .lich-inner .tbl th, .lich-inner .tbl td { font-size: 13px; }
        .status-badge { display:inline-block; padding:2px 10px; border-radius:12px; font-size:12px; font-weight:600; }
        .status-scheduled   { background:#EAF0FB; color:#2563A8; }
        .status-in_progress { background:#FFF7E0; color:#C9943A; }
        .status-completed   { background:#EAF6EF; color:#1A6E3F; }
        .status-cancelled   { background:#FDF0F0; color:#A02020; }
    </style>
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
                        @php
                            $sessions = $lichHoc[$lh->ID_LopHoc] ?? [];
                            $rowId    = 'lich-' . $lh->ID_LopHoc;
                            $btnId    = 'btn-lich-' . $lh->ID_LopHoc;
                        @endphp

                        {{-- Hàng lớp học --}}
                        <tr>
                            <td>{{ $lh->TenLopHoc }}</td>
                            <td>{{ $lh->Ten_KhoiLop }}</td>
                            <td>{{ $lh->Ten_MonHoc }}</td>
                            <td>{{ $lh->ten_giao_vien }}</td>
                            <td>{{ $lh->NamHoc }}</td>
                            <td>
                                <a class="tbl-link" id="{{ $btnId }}"
                                   onclick="toggleLich('{{ $rowId }}', '{{ $btnId }}')">
                                   Xem lịch học ▼
                                </a>
                            </td>
                        </tr>

                        {{-- Hàng lịch học xổ ra --}}
                        <tr class="lich-row" id="{{ $rowId }}" style="display:none">
                            <td colspan="6">
                                <div class="lich-inner">
                                    <div class="section-title blue">Lịch học – {{ $lh->TenLopHoc }}</div>
                                    <div class="table-wrap">
                                        <table class="tbl">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Ngày học</th>
                                                    <th>Thứ</th>
                                                    <th>Giờ học</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($sessions as $idx => $s)
                                                @php
                                                    $ngay = \Carbon\Carbon::parse($s->NgayHoc_DiemDanh);
                                                    $thu  = ['CN','T2','T3','T4','T5','T6','T7'][$ngay->dayOfWeek];
                                                    $bat  = $s->ThoiGianBatDau_DiemDanh
                                                              ? \Carbon\Carbon::parse($s->ThoiGianBatDau_DiemDanh)->format('H:i')
                                                              : '—';
                                                    $ket  = $s->ThoiGianKetThuc_DiemDanh
                                                              ? \Carbon\Carbon::parse($s->ThoiGianKetThuc_DiemDanh)->format('H:i')
                                                              : '—';
                                                    $trangThai = match($s->TrangThaiBuoiHoc_DiemDanh) {
                                                        'scheduled'   => ['label' => 'Đã lên lịch',  'cls' => 'status-scheduled'],
                                                        'in_progress' => ['label' => 'Đang diễn ra', 'cls' => 'status-in_progress'],
                                                        'completed'   => ['label' => 'Đã hoàn thành','cls' => 'status-completed'],
                                                        'cancelled'   => ['label' => 'Đã hủy',       'cls' => 'status-cancelled'],
                                                        default       => ['label' => $s->TrangThaiBuoiHoc_DiemDanh, 'cls' => ''],
                                                    };
                                                @endphp
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $ngay->format('d/m/Y') }}</td>
                                                    <td>{{ $thu }}</td>
                                                    <td>{{ $bat }} – {{ $ket }}</td>
                                                    <td><span class="status-badge {{ $trangThai['cls'] }}">{{ $trangThai['label'] }}</span></td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="empty-notice">Chưa có buổi học nào được lên lịch.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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

<script>
    window.PAGE_USER_NAME = "{{ session('auth.name') }}";
    window.PAGE_ROLE   = 'hocsinh';
    window.PAGE_ACTIVE = 'hs-ds-lophoc';

    function toggleLich(rowId, btnId) {
        const row = document.getElementById(rowId);
        const btn = document.getElementById(btnId);
        const isOpen = row.style.display !== 'none';
        row.style.display = isOpen ? 'none' : 'table-row';
        btn.textContent   = isOpen ? 'Xem lịch học ▼' : 'Ẩn lịch học ▲';
    }
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
