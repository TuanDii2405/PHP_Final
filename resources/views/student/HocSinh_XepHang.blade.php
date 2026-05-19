<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Học sinh – Xếp hạng</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
  </head>
  <body>
    <div id="app-header"></div>
    <div class="layout">
      <div id="app-sidebar"></div>
      <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
          <div class="section-title blue">Xếp hạng</div>
          
          <div style="margin-top: 6px; margin-bottom: 20px;">
            <a href="{{ route('student.ranking', ['filter' => 'lop']) }}" 
               class="sub-btn block" 
               style="text-decoration: none; display: inline-block; {{ $currentFilter == 'lop' ? 'background-color: #0d6efd; color: #fff;' : '' }}">
              Xếp hạng theo lớp
            </a>
            <a href="{{ route('student.ranking', ['filter' => 'khoi']) }}" 
               class="sub-btn block" 
               style="text-decoration: none; display: inline-block; {{ $currentFilter == 'khoi' ? 'background-color: #0d6efd; color: #fff;' : '' }}">
              Xếp hạng theo khối
            </a>
            <a href="{{ route('student.ranking', ['filter' => 'mon']) }}" 
               class="sub-btn block" 
               style="text-decoration: none; display: inline-block; {{ $currentFilter == 'mon' ? 'background-color: #0d6efd; color: #fff;' : '' }}">
              Xếp hạng theo môn
            </a>
            <a href="{{ route('student.ranking', ['filter' => 'tonghop']) }}" 
               class="sub-btn block" 
               style="text-decoration: none; display: inline-block; {{ $currentFilter == 'tonghop' ? 'background-color: #0d6efd; color: #fff;' : '' }}">
              Xếp hạng tổng hợp
            </a>
          </div>

          <div class="table-wrap">
              <table class="tbl">
                  <thead>
                      <tr>
                          <th style="width: 10%; text-align: center;">Hạng</th>
                          <th>Họ và tên học sinh</th>
                          <th style="text-align: center;">Số bài đã thi</th>
                          <th style="text-align: right;">Tổng điểm tích lũy</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse ($rankings as $index => $rank)
                          <tr style="{{ $index < 3 ? 'font-weight: bold; background-color: #fff9e6;' : '' }}">
                              <td style="text-align: center;">
                                  @if($index == 0) 🏆 1 
                                  @elseif($index == 1) 🥈 2 
                                  @elseif($index == 2) 🥉 3 
                                  @else {{ $index + 1 }} 
                                  @endif
                              </td>
                              <td>{{ $rank->HoVaTen_User }}</td>
                              <td style="text-align: center;">{{ $rank->SoBaiThi }}</td>
                              <td style="text-align: right; color: #d9534f; font-size: 16px;">{{ number_format($rank->TongDiem, 2) }}</td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="4" style="text-align: center; padding: 20px;">Chưa có dữ liệu xếp hạng</td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>

        </div>
      </main>
    </div>
    <script>
      window.PAGE_ROLE = "hocsinh";
      window.PAGE_ACTIVE = "hs-xephang";
    </script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
  </body>
</html>