<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giáo viên – Danh sách lớp học</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .detail-row td { padding: 0; border-top: none; }
        .detail-inner  { padding: 12px 20px 16px; background: #f7faff;
                         border-top: 1.5px solid var(--cerulean-200, #d0e8f8); }
        .detail-inner .section-title { font-size: 13px; margin-bottom: 8px; }
        .detail-inner .tbl th,
        .detail-inner .tbl td { font-size: 13px; }
        .detail-meta  { display:flex; gap:20px; flex-wrap:wrap; margin-bottom:12px; }
        .detail-meta span { font-size:13px; color:var(--text-soft); }
        .detail-meta strong { color:var(--text-main); }
    </style>
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
                <input class="search-input" type="text" id="searchInput"
                       placeholder="Tìm tên lớp, môn, khối..." oninput="filterTable()">
                <button class="action-btn" onclick="location.reload()">Làm mới</button>
            </div>
            <div class="table-wrap">
                <table class="tbl" id="tbl">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên lớp học</th>
                            <th>Khối lớp</th>
                            <th>Môn học</th>
                            <th>Năm học</th>
                            <th>Số học sinh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lopHocs as $i => $lh)
                        @php
                            $students = $studentsPerClass[$lh->ID_LopHoc] ?? [];
                            $rowId    = 'detail-' . $lh->ID_LopHoc;
                            $btnId    = 'btn-detail-' . $lh->ID_LopHoc;
                        @endphp

                        {{-- Hàng lớp học --}}
                        <tr class="main-row">
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $lh->TenLopHoc }}</td>
                            <td>{{ $lh->Ten_KhoiLop }}</td>
                            <td>{{ $lh->Ten_MonHoc }}</td>
                            <td>{{ $lh->NamHoc }}</td>
                            <td>{{ $lh->so_hoc_sinh }}</td>
                            <td>
                                <a class="tbl-link" id="{{ $btnId }}"
                                   onclick="toggleDetail('{{ $rowId }}', '{{ $btnId }}')">
                                   Xem chi tiết ▼
                                </a>
                            </td>
                        </tr>

                        {{-- Hàng chi tiết xổ ra --}}
                        <tr class="detail-row" id="{{ $rowId }}" style="display:none">
                            <td colspan="7">
                                <div class="detail-inner">
                                    <div class="detail-meta">
                                        <span><strong>Môn:</strong> {{ $lh->Ten_MonHoc }}</span>
                                        <span><strong>Khối:</strong> {{ $lh->Ten_KhoiLop }}</span>
                                        <span><strong>Năm học:</strong> {{ $lh->NamHoc }}</span>
                                        <span><strong>Sĩ số:</strong> {{ $lh->so_hoc_sinh }} học sinh</span>
                                    </div>
                                    <div class="section-title blue">Danh sách học sinh</div>
                                    <div class="table-wrap">
                                        <table class="tbl">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Họ và tên</th>
                                                    <th>Email</th>
                                                    <th>Ngày sinh</th>
                                                    <th>Ngày tham gia</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($students as $j => $s)
                                                <tr>
                                                    <td>{{ $j + 1 }}</td>
                                                    <td>{{ $s->HoVaTen_User }}</td>
                                                    <td>{{ $s->EmailCaNhan_User ?? '—' }}</td>
                                                    <td>{{ $s->NgayThangNamSinh_User ? \Carbon\Carbon::parse($s->NgayThangNamSinh_User)->format('d/m/Y') : '—' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($s->NgayThamGia)->format('d/m/Y') }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="empty-notice">Lớp chưa có học sinh nào.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr><td colspan="7" class="empty-notice">Bạn chưa phụ trách lớp học nào</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    window.PAGE_USER_NAME = "{{ session('auth.name') }}";
    window.PAGE_ROLE   = 'giaovien';
    window.PAGE_ACTIVE = 'ds-lophoc';

    function toggleDetail(rowId, btnId) {
        const row  = document.getElementById(rowId);
        const btn  = document.getElementById(btnId);
        const open = row.style.display !== 'none';
        row.style.display = open ? 'none' : 'table-row';
        btn.textContent   = open ? 'Xem chi tiết ▼' : 'Ẩn chi tiết ▲';
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#tbl tbody tr.main-row').forEach(row => {
            const match = row.textContent.toLowerCase().includes(q);
            row.style.display = match ? '' : 'none';
            // Đóng detail row tương ứng khi lọc
            const next = row.nextElementSibling;
            if (next && next.classList.contains('detail-row')) {
                if (!match) {
                    next.style.display = 'none';
                    const btn = row.querySelector('.tbl-link');
                    if (btn) btn.textContent = 'Xem chi tiết ▼';
                }
                next.style.display = match ? next.style.display : 'none';
            }
        });
    }
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
