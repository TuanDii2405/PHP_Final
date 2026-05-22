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

        /* Badge đơn xin vắng */
        .xv-pending  { display:inline-block;padding:2px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#FFF3CD;color:#856404; }
        .xv-approved { display:inline-block;padding:2px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#D1E7DD;color:#0F5132; }
        .xv-rejected { display:inline-block;padding:2px 10px;border-radius:12px;font-size:11px;font-weight:600;background:#F8D7DA;color:#842029; }

        /* Modal */
        .modal-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;align-items:center;justify-content:center; }
        .modal-overlay.open { display:flex; }
        .modal-box { background:#fff;border-radius:10px;padding:28px 28px 22px;width:100%;max-width:460px;box-shadow:0 8px 32px rgba(0,0,0,.18); }
        .modal-box h3 { margin:0 0 16px;font-size:16px;color:#1a3a5c; }
        .modal-box textarea { width:100%;box-sizing:border-box;border:1px solid #c5d7ec;border-radius:6px;padding:10px;font-size:14px;resize:vertical;min-height:100px;font-family:inherit; }
        .modal-actions { display:flex;gap:10px;justify-content:flex-end;margin-top:16px; }
        .btn-xv { padding:7px 20px;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer; }
        .btn-xv-send { background:#2563A8;color:#fff; }
        .btn-xv-cancel { background:#e9ecef;color:#495057; }
        .btn-xv-send:hover { background:#1a4c8a; }
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

            @if (session('success'))
                <div class="alert alert-success" style="margin-bottom:12px">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" style="margin-bottom:12px">{{ session('error') }}</div>
            @endif

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
                                                    <th>Xin vắng</th>
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
                                                    $batDauDt = $s->ThoiGianBatDau_DiemDanh
                                                        ? \Carbon\Carbon::parse($ngay->format('Y-m-d') . ' ' . \Carbon\Carbon::parse($s->ThoiGianBatDau_DiemDanh)->format('H:i:s'))
                                                        : null;
                                                    $coTheXinVang = in_array($s->TrangThaiBuoiHoc_DiemDanh, ['scheduled', 'in_progress'])
                                                        && $batDauDt
                                                        && now()->lt($batDauDt->copy()->subHour());
                                                    $daCoXinVang  = !empty($s->ID_DonXinNghi);
                                                @endphp
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $ngay->format('d/m/Y') }}</td>
                                                    <td>{{ $thu }}</td>
                                                    <td>{{ $bat }} – {{ $ket }}</td>
                                                    <td><span class="status-badge {{ $trangThai['cls'] }}">{{ $trangThai['label'] }}</span></td>
                                                    <td>
                                                        @if ($daCoXinVang)
                                                            @php
                                                                $xvCls = match($s->TrangThai_DonXinNghi) {
                                                                    'pending'  => ['cls'=>'xv-pending',  'label'=>'Chờ duyệt'],
                                                                    'approved' => ['cls'=>'xv-approved', 'label'=>'Đã duyệt'],
                                                                    'rejected' => ['cls'=>'xv-rejected', 'label'=>'Từ chối'],
                                                                    default    => ['cls'=>'',             'label'=>$s->TrangThai_DonXinNghi],
                                                                };
                                                            @endphp
                                                            <span class="{{ $xvCls['cls'] }}">{{ $xvCls['label'] }}</span>
                                                        @elseif ($coTheXinVang)
                                                            <button type="button" class="btn-xv btn-xv-send"
                                                                onclick="moModalXinVang({{ $s->ID_DiemDanh }}, '{{ $ngay->format('d/m/Y') }}', '{{ $bat }} – {{ $ket }}')">
                                                                Xin vắng
                                                            </button>
                                                        @else
                                                            <span style="color:var(--text-soft);font-size:12px">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="empty-notice">Chưa có buổi học nào được lên lịch.</td>
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

{{-- Modal xin vắng --}}
<div class="modal-overlay" id="modalXinVang">
    <div class="modal-box">
        <h3>Gửi đơn xin vắng</h3>
        <p style="font-size:13px;color:#555;margin:0 0 12px" id="modalInfo"></p>
        <form method="POST" action="{{ route('student.xin-vang.store') }}">
            @csrf
            <input type="hidden" name="ID_DiemDanh" id="inputDiemDanh">
            <label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px">Lý do xin vắng <span style="color:red">*</span></label>
            <textarea name="NoiDung_DonXinNghi" placeholder="Nhập lý do xin vắng..." required maxlength="1000"></textarea>
            <div class="modal-actions">
                <button type="button" class="btn-xv btn-xv-cancel" onclick="dongModal()">Hủy</button>
                <button type="submit" class="btn-xv btn-xv-send">Gửi đơn</button>
            </div>
        </form>
    </div>
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

    function moModalXinVang(idDiemDanh, ngay, gio) {
        document.getElementById('inputDiemDanh').value = idDiemDanh;
        document.getElementById('modalInfo').textContent = 'Buổi học ngày ' + ngay + '  ·  ' + gio;
        document.getElementById('modalXinVang').classList.add('open');
    }

    function dongModal() {
        document.getElementById('modalXinVang').classList.remove('open');
    }

    document.getElementById('modalXinVang').addEventListener('click', function(e) {
        if (e.target === this) dongModal();
    });
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
