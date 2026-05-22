<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Học sinh – Xếp hạng</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <style>
      /* ── Podium ── */
      .podium-wrap {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 12px;
        margin: 10px 0 28px;
      }
      .podium-card {
        flex: 1;
        max-width: 190px;
        border-radius: 14px;
        padding: 18px 12px 16px;
        text-align: center;
        border: 2px solid transparent;
        background: var(--surface-1);
        position: relative;
        transition: transform 0.18s;
      }
      .podium-card:hover { transform: translateY(-4px); }

      /* vị trí 1 – cao nhất */
      .podium-1 {
        min-height: 200px;
        background: linear-gradient(160deg, #fdf6e3 0%, #fff8ea 100%);
        border-color: #C9943A;
        box-shadow: 0 4px 18px rgba(201,148,58,.22);
      }
      /* vị trí 2 */
      .podium-2 {
        min-height: 168px;
        background: linear-gradient(160deg, #f4f7fb 0%, #eef3f9 100%);
        border-color: #8A9BB0;
        box-shadow: 0 4px 14px rgba(138,155,176,.18);
      }
      /* vị trí 3 */
      .podium-3 {
        min-height: 148px;
        background: linear-gradient(160deg, #fdf0e8 0%, #faeee4 100%);
        border-color: #A05A2C;
        box-shadow: 0 4px 14px rgba(160,90,44,.16);
      }

      .podium-trophy {
        font-size: 28px;
        margin-bottom: 6px;
        line-height: 1;
      }
      .podium-1 .podium-trophy { color: #C9943A; }
      .podium-2 .podium-trophy { color: #8A9BB0; }
      .podium-3 .podium-trophy { color: #A05A2C; }

      .podium-pos {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 28px;
        border-radius: 50%;
        font-size: 13px; font-weight: 800;
        margin-bottom: 8px;
      }
      .podium-1 .podium-pos { background: #C9943A; color: #fff; }
      .podium-2 .podium-pos { background: #8A9BB0; color: #fff; }
      .podium-3 .podium-pos { background: #A05A2C; color: #fff; }

      .podium-name {
        font-size: 13px; font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
        word-break: break-word;
        line-height: 1.3;
      }
      .podium-score {
        font-size: 22px; font-weight: 800;
        color: var(--cerulean);
        line-height: 1.1;
      }
      .podium-1 .podium-score { color: #C9943A; }
      .podium-2 .podium-score { color: #8A9BB0; }
      .podium-3 .podium-score { color: #A05A2C; }
      .podium-score-label {
        font-size: 10px; font-weight: 500;
        color: var(--text-soft); margin-top: 2px;
      }
      .podium-me-badge {
        position: absolute; top: 8px; right: 8px;
        background: var(--cerulean); color: #fff;
        font-size: 9px; font-weight: 700;
        padding: 2px 7px; border-radius: 10px;
        text-transform: uppercase; letter-spacing: .4px;
      }

      /* ── My-stat bar ── */
      .my-stat-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
      }
      .my-stat-card {
        flex: 1;
        min-width: 120px;
        background: var(--cerulean-50);
        border: 1.5px solid var(--cerulean-200);
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .my-stat-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: var(--cerulean-100);
        border: 1px solid var(--cerulean-200);
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; color: var(--cerulean);
        flex-shrink: 0;
      }
      .my-stat-label { font-size: 11px; color: var(--text-soft); margin-bottom: 2px; }
      .my-stat-value { font-size: 18px; font-weight: 800; color: var(--cerulean); }

      /* ── Full ranking table ── */
      .rank-section { margin-bottom: 36px; }
      .rank-header {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 14px; flex-wrap: wrap;
      }
      .rank-class-badge {
        display: inline-flex; align-items: center; gap: 7px;
        background: var(--cerulean); color: #fff;
        padding: 5px 14px; border-radius: 20px;
        font-size: 13px; font-weight: 700;
      }
      .rank-subject-badge {
        background: var(--cerulean-100); color: var(--cerulean);
        padding: 4px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 500;
        border: 1px solid var(--cerulean-200);
      }
      .rank-divider {
        border: none; border-top: 2px solid var(--cerulean-100);
        margin: 20px 0;
      }

      .tbl td.rank-num {
        font-weight: 800; font-size: 15px; text-align: center;
      }
      .rank-gold   { color: #C9943A; }
      .rank-silver { color: #8A9BB0; }
      .rank-bronze { color: #A05A2C; }
      .rank-me-row { background: #EFF8FF !important; }
      .rank-me-name { font-weight: 700; color: var(--cerulean); }

      .rank-icon-cell {
        display: flex; align-items: center; justify-content: center;
      }
      .rank-medal {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px;
      }
      .medal-gold   { background: #fef3d4; color: #C9943A; border: 1.5px solid #C9943A; }
      .medal-silver { background: #f0f3f7; color: #8A9BB0; border: 1.5px solid #8A9BB0; }
      .medal-bronze { background: #fdeee5; color: #A05A2C; border: 1.5px solid #A05A2C; }

      .score-pill {
        display: inline-block;
        padding: 2px 10px; border-radius: 12px;
        font-size: 13px; font-weight: 700;
        background: var(--cerulean-100); color: var(--cerulean);
      }
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
                $lop   = $group['lop'];
                $bang  = $group['bang'];
                $myRow = collect($bang)->firstWhere('ID_User', $studentId);
                $myPos = 0;
                foreach ($bang as $ri => $r) { if ($r->ID_User == $studentId) { $myPos = $ri + 1; break; } }
                $top3 = array_slice($bang, 0, 3);
              @endphp

              <div class="rank-section">
                {{-- Header lớp --}}
                <div class="rank-header">
                  <span class="rank-class-badge">
                    <i class="bi bi-people-fill"></i>
                    {{ $lop->TenLopHoc }}
                  </span>
                  <span class="rank-subject-badge">
                    <i class="bi bi-journal-bookmark"></i>
                    {{ $lop->Ten_KhoiLop }} – {{ $lop->Ten_MonHoc }}
                  </span>
                </div>

                {{-- Stat bar cho bản thân --}}
                @if($myRow)
                <div class="my-stat-bar">
                  <div class="my-stat-card">
                    <div class="my-stat-icon"><i class="bi bi-person-fill"></i></div>
                    <div>
                      <div class="my-stat-label">Hạng của bạn</div>
                      <div class="my-stat-value">#{{ $myPos }} / {{ count($bang) }}</div>
                    </div>
                  </div>
                  <div class="my-stat-card">
                    <div class="my-stat-icon"><i class="bi bi-star-fill"></i></div>
                    <div>
                      <div class="my-stat-label">Tổng điểm</div>
                      <div class="my-stat-value">{{ number_format((float)$myRow->tong_diem, 2) }}</div>
                    </div>
                  </div>
                  <div class="my-stat-card">
                    <div class="my-stat-icon"><i class="bi bi-file-earmark-check-fill"></i></div>
                    <div>
                      <div class="my-stat-label">Số bài đã làm</div>
                      <div class="my-stat-value">{{ $myRow->so_bai }}</div>
                    </div>
                  </div>
                </div>
                @endif

                {{-- Podium top 3 --}}
                @if(count($bang) >= 1)
                <div class="podium-wrap">
                  {{-- Vị trí 2 (trái) --}}
                  @if(isset($top3[1]))
                  @php $s2 = $top3[1]; @endphp
                  <div class="podium-card podium-2">
                    @if($s2->ID_User == $studentId)<span class="podium-me-badge">Bạn</span>@endif
                    <div class="podium-trophy"><i class="bi bi-trophy"></i></div>
                    <div class="podium-pos">2</div>
                    <div class="podium-name">{{ $s2->HoVaTen_User }}</div>
                    <div class="podium-score">{{ number_format((float)$s2->tong_diem, 2) }}</div>
                    <div class="podium-score-label">tổng điểm</div>
                  </div>
                  @else<div class="podium-card podium-2" style="opacity:.3;min-height:168px"></div>
                  @endif

                  {{-- Vị trí 1 (giữa, cao nhất) --}}
                  @php $s1 = $top3[0]; @endphp
                  <div class="podium-card podium-1">
                    @if($s1->ID_User == $studentId)<span class="podium-me-badge">Bạn</span>@endif
                    <div class="podium-trophy"><i class="bi bi-trophy-fill"></i></div>
                    <div class="podium-pos">1</div>
                    <div class="podium-name">{{ $s1->HoVaTen_User }}</div>
                    <div class="podium-score">{{ number_format((float)$s1->tong_diem, 2) }}</div>
                    <div class="podium-score-label">tổng điểm</div>
                  </div>

                  {{-- Vị trí 3 (phải) --}}
                  @if(isset($top3[2]))
                  @php $s3 = $top3[2]; @endphp
                  <div class="podium-card podium-3">
                    @if($s3->ID_User == $studentId)<span class="podium-me-badge">Bạn</span>@endif
                    <div class="podium-trophy"><i class="bi bi-award-fill"></i></div>
                    <div class="podium-pos">3</div>
                    <div class="podium-name">{{ $s3->HoVaTen_User }}</div>
                    <div class="podium-score">{{ number_format((float)$s3->tong_diem, 2) }}</div>
                    <div class="podium-score-label">tổng điểm</div>
                  </div>
                  @else<div class="podium-card podium-3" style="opacity:.3;min-height:148px"></div>
                  @endif
                </div>
                @endif

                {{-- Full ranking table --}}
                @if(count($bang) > 3)
                <hr class="rank-divider">
                <div style="font-size:12px;font-weight:600;color:var(--text-soft);margin-bottom:8px;display:flex;align-items:center;gap:6px">
                  <i class="bi bi-list-ol"></i> Bảng xếp hạng đầy đủ
                </div>
                @endif
                <div class="table-wrap">
                  <table class="tbl">
                    <thead>
                      <tr>
                        <th style="width:54px;text-align:center">Hạng</th>
                        <th>Học sinh</th>
                        <th style="width:110px;text-align:center">Tổng điểm</th>
                        <th style="width:120px;text-align:center">Số bài đã làm</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($bang as $ri => $row)
                        @php
                          $pos   = $ri + 1;
                          $isMe  = $row->ID_User == $studentId;
                        @endphp
                        <tr class="{{ $isMe ? 'rank-me-row' : '' }}">
                          <td style="text-align:center">
                            @if($pos === 1)
                              <span class="rank-medal medal-gold"><i class="bi bi-trophy-fill"></i></span>
                            @elseif($pos === 2)
                              <span class="rank-medal medal-silver"><i class="bi bi-trophy"></i></span>
                            @elseif($pos === 3)
                              <span class="rank-medal medal-bronze"><i class="bi bi-award-fill"></i></span>
                            @else
                              <span style="font-size:13px;font-weight:700;color:var(--text-soft)">{{ $pos }}</span>
                            @endif
                          </td>
                          <td class="{{ $isMe ? 'rank-me-name' : '' }}">
                            @if($isMe)
                              <i class="bi bi-person-fill" style="color:var(--cerulean);margin-right:4px"></i>
                            @endif
                            {{ $row->HoVaTen_User }}
                            @if($isMe)
                              <span style="font-size:10px;background:var(--cerulean);color:#fff;padding:1px 7px;border-radius:10px;margin-left:4px;font-weight:600">Bạn</span>
                            @endif
                          </td>
                          <td style="text-align:center">
                            <span class="score-pill">{{ number_format((float)$row->tong_diem, 2) }}</span>
                          </td>
                          <td style="text-align:center;color:var(--text-soft);font-weight:500">
                            {{ $row->so_bai }}
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="4" class="empty-notice">Chưa có dữ liệu xếp hạng</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

              @if(!$loop->last)
                <hr style="border:none;border-top:2px solid var(--cerulean-100);margin:8px 0 28px">
              @endif
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
