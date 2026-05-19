<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Học sinh – Xếp hạng</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <style>
        .rank-section  { margin-bottom: 28px; }
        .rank-title    { font-size: 14px; font-weight: 700; color: var(--cerulean);
                         margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .rank-badge    { font-size: 11px; background: var(--cerulean-100, #e0f0ff);
                         color: var(--cerulean); padding: 2px 10px; border-radius: 12px; font-weight: 500; }
        .tbl td.rank-me { font-weight: 700; color: var(--cerulean); }
        .rank-pos      { font-size: 16px; font-weight: 800; }
        .rank-gold   { color: #C9943A; }
        .rank-silver { color: #8A9BB0; }
        .rank-bronze { color: #A05A2C; }
        .rank-me-row { background: #EFF8FF !important; }
    </style>
  </head>
  <body>
    <div id="app-header"></div>
    <div class="layout">
      <div id="app-sidebar"></div>
      <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
          <div class="section-title blue">Xếp hạng</div>
          <div class="action-bar">
            <button class="action-btn" onclick="location.reload()">Làm mới</button>
          </div>

          @if(count($rankings) === 0)
            <div class="empty-notice">Bạn chưa tham gia lớp học nào để xếp hạng.</div>
          @else
            @foreach($rankings as $group)
              @php
                $lop  = $group['lop'];
                $bang = $group['bang'];
                $hang = 1;
              @endphp
              <div class="rank-section">
                <div class="rank-title">
                  <i class="bi bi-bar-chart-line"></i>
                  {{ $lop->TenLopHoc }}
                  <span class="rank-badge">{{ $lop->Ten_KhoiLop }} – {{ $lop->Ten_MonHoc }}</span>
                </div>
                <div class="table-wrap">
                  <table class="tbl">
                    <thead>
                      <tr>
                        <th style="width:60px">Hạng</th>
                        <th>Học sinh</th>
                        <th>Điểm TB</th>
                        <th>Số bài đã làm</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($bang as $row)
                        @php
                          $isMe     = $row->ID_User == $studentId;
                          $rankClass = match($hang) { 1 => 'rank-gold', 2 => 'rank-silver', 3 => 'rank-bronze', default => '' };
                        @endphp
                        <tr class="{{ $isMe ? 'rank-me-row' : '' }}">
                          <td class="rank-pos {{ $rankClass }}" style="text-align:center">
                            @if($hang === 1) 🥇
                            @elseif($hang === 2) 🥈
                            @elseif($hang === 3) 🥉
                            @else {{ $hang }}
                            @endif
                          </td>
                          <td class="{{ $isMe ? 'rank-me' : '' }}">
                            {{ $row->HoVaTen_User }}
                            @if($isMe) <span style="font-size:11px;color:var(--cerulean)">(Bạn)</span> @endif
                          </td>
                          <td>{{ number_format((float)$row->diem_tb, 2) }}</td>
                          <td>{{ $row->so_bai }}</td>
                        </tr>
                        @php $hang++ @endphp
                      @empty
                        <tr><td colspan="4" class="empty-notice">Chưa có dữ liệu xếp hạng</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </main>
    </div>
    <script>
      window.PAGE_USER_NAME = "{{ session('auth.name') }}";
      window.PAGE_ROLE   = 'hocsinh';
      window.PAGE_ACTIVE = 'hs-xephang';
    </script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
  </body>
</html>
