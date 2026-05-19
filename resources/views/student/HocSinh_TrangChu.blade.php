<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Học sinh – Trang chủ</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <style>
        .thongbao-list   { display:flex;flex-direction:column;gap:10px;margin-top:12px; }
        .thongbao-item   { border:1px solid var(--cerulean-200);border-radius:10px;padding:12px 16px;background:#fff; }
        .thongbao-header { display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:6px;margin-bottom:8px; }
        .thongbao-scope  { font-size:12px;font-weight:700;color:var(--cerulean);display:flex;align-items:center;gap:5px; }
        .scope-all       { color:var(--jasper); }
        .thongbao-date   { font-size:11px;color:var(--text-soft);display:flex;align-items:center;gap:4px; }
        .thongbao-content{ font-size:14px;color:var(--text-main);line-height:1.6;white-space:pre-wrap;word-break:break-word;margin-bottom:6px; }
        .thongbao-footer { font-size:12px;color:var(--text-soft);display:flex;align-items:center;gap:4px; }
    </style>
  </head>
  <body>
    <div id="app-header"></div>
    <div class="layout">
      <div id="app-sidebar"></div>
      <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
          <div class="section-title">Thông báo hệ thống</div>
          <div class="action-bar">
            <button class="action-btn" onclick="location.reload()">Làm mới</button>
          </div>

          @if(count($thongBaos) > 0)
            <div class="thongbao-list">
              @foreach($thongBaos as $tb)
                <div class="thongbao-item">
                  <div class="thongbao-header">
                    <span class="thongbao-scope {{ !$tb->Ten_KhoiLop && !$tb->Ten_MonHoc ? 'scope-all' : '' }}">
                      @if(!$tb->Ten_KhoiLop && !$tb->Ten_MonHoc)
                        <i class="bi bi-globe2"></i> Toàn hệ thống
                      @elseif($tb->Ten_KhoiLop && $tb->Ten_MonHoc)
                        <i class="bi bi-bookmark"></i> {{ $tb->Ten_KhoiLop }} – {{ $tb->Ten_MonHoc }}
                      @elseif($tb->Ten_KhoiLop)
                        <i class="bi bi-layers"></i> {{ $tb->Ten_KhoiLop }}
                      @else
                        <i class="bi bi-book"></i> {{ $tb->Ten_MonHoc }}
                      @endif
                    </span>
                    <span class="thongbao-date">
                      <i class="bi bi-clock"></i>
                      {{ \Carbon\Carbon::parse($tb->NgayTao_ThongBao)->format('d/m/Y H:i') }}
                    </span>
                  </div>
                  <div class="thongbao-content">{{ $tb->NoiDung_ThongBao }}</div>
                  <div class="thongbao-footer">
                    <i class="bi bi-person"></i> {{ $tb->ten_nguoi_gui }}
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="empty-notice">
              Hiện tại không có thông báo nào dành cho bạn
            </div>
          @endif
        </div>
      </main>
    </div>
    <script>
      window.PAGE_USER_NAME = "{{ session('auth.name') }}";
      window.PAGE_ROLE = "hocsinh";
      window.PAGE_ACTIVE = "";
    </script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
  </body>
</html>
