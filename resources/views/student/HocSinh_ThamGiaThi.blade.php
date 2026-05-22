<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tham Gia Kỳ Thi – {{ $thong_tin['Ten_KyThi'] }}</title>
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --navy: #0D2B55; --navy-light: #1A3F73; --navy-mid: #2563A8;
  --gold: #C9943A; --gold-light: #E8B458;
  --bg: #F4F7FC; --white: #FFFFFF;
  --text: #1A2332; --text-muted: #5A6A80;
  --border: #D0DAEA;
  --success: #1A6E3F;
  --danger: #A02020;  --danger-bg: #FDF0F0;
  --opt-hover: #EFF4FF; --opt-sel: #DBE8FF; --opt-sel-border: #2563A8;
}

/* ─── Màn hình chờ ─── */
#screen-ready {
  display: flex; align-items: center; justify-content: center;
  padding: 40px 20px;
  min-height: calc(100vh - var(--header-height));
  background: var(--bg);
}
.ready-card {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 18px; padding: 40px 44px 36px;
  max-width: 560px; width: 100%;
  box-shadow: 0 4px 28px rgba(13,43,85,.10); text-align: center;
}
.ready-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--opt-sel); color: var(--navy-mid);
  font-size: 12px; font-weight: 600; padding: 5px 16px;
  border-radius: 20px; letter-spacing: .4px; margin-bottom: 14px;
}
.ready-title { font-size: 22px; font-weight: 700; color: var(--navy); line-height: 1.35; margin-bottom: 28px; }
.ready-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 28px; }
.ready-stat { background: var(--bg); border: 1.5px solid var(--border); border-radius: 12px; padding: 18px 10px 14px; }
.ready-stat-icon { font-size: 22px; color: var(--navy-mid); margin-bottom: 6px; }
.ready-stat-num  { font-size: 26px; font-weight: 700; color: var(--navy); line-height: 1; margin-bottom: 4px; }
.ready-stat-label { font-size: 11.5px; color: var(--text-muted); font-weight: 500; }
.ready-note {
  font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 28px;
  padding: 12px 16px; background: #FEF9EF; border: 1px solid #F0D9A0; border-radius: 10px; text-align: left;
}
.ready-note strong { color: var(--gold); }
.btn-start {
  background: var(--navy); color: #fff; border: none; padding: 13px 36px;
  border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; font-family: inherit;
  transition: background .2s, transform .15s; width: 100%; letter-spacing: .3px;
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-start:hover { background: var(--navy-mid); transform: translateY(-1px); }
.btn-back {
  display: inline-block; margin-top: 14px; font-size: 13px;
  color: var(--text-muted); cursor: pointer; text-decoration: none; transition: color .2s;
}
.btn-back:hover { color: var(--navy-mid); }

/* ─── Màn hình làm bài – toàn màn hình ─── */
#screen-exam {
  display: none; position: fixed; inset: 0;
  z-index: 2000; flex-direction: column; background: var(--bg);
}

/* Topbar */
.exam-topbar {
  height: 50px; flex-shrink: 0; background: var(--navy);
  display: flex; align-items: center; padding: 0 18px; gap: 0;
  box-shadow: 0 2px 8px rgba(0,0,0,.22);
}
.exam-topbar-name {
  font-size: 13px; font-weight: 600; color: rgba(255,255,255,.92);
  display: flex; align-items: center; gap: 7px;
  flex: 1; min-width: 0; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
}
.exam-topbar-sep { width: 1px; height: 22px; background: rgba(255,255,255,.2); margin: 0 12px; flex-shrink: 0; }
.exam-topbar-info { font-size: 12px; color: rgba(255,255,255,.7); display: flex; align-items: center; gap: 5px; flex-shrink: 0; }
.exam-topbar-timer {
  background: var(--gold); color: #fff; padding: 5px 13px; border-radius: 6px;
  font-size: 15px; font-weight: 700; font-variant-numeric: tabular-nums;
  letter-spacing: 1px; min-width: 80px; text-align: center; margin-left: 12px; flex-shrink: 0;
}
.exam-topbar-timer.warning { background: #C0392B; animation: pulse 1s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.7} }

/* Body */
.exam-body { display: flex; flex: 1; overflow: hidden; }

/* ─── Sidebar ─── */
.exam-sidebar {
  width: 228px; min-width: 228px; flex-shrink: 0;
  background: var(--white); border-right: 1px solid var(--border);
  display: flex; flex-direction: column; overflow: hidden;
}
.sidebar-head {
  padding: 10px 14px 8px; border-bottom: 1px solid var(--border); background: var(--bg); flex-shrink: 0;
}
.sidebar-head h3 { font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 2px; }
.sidebar-progress { font-size: 12px; color: var(--navy-mid); font-weight: 600; }

/* Phần accordion của sidebar */
.sidebar-parts { flex: 1; overflow-y: auto; overflow-x: hidden; }

.part-sec { border-bottom: 1px solid var(--border); }

.part-hd {
  width: 100%; display: flex; align-items: center; gap: 8px;
  padding: 9px 12px; background: var(--bg);
  border: none; cursor: pointer; text-align: left; transition: background .15s;
}
.part-hd:hover { background: var(--opt-hover); }
.part-hd.active { background: #e8f0fb; }

.part-hd-num {
  width: 22px; height: 22px; border-radius: 50%; flex-shrink: 0;
  background: var(--navy); color: #fff;
  font-size: 11px; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
}
.part-hd-1 .part-hd-num { background: var(--navy); }
.part-hd-2 .part-hd-num { background: #5A3E9E; }
.part-hd-3 .part-hd-num { background: #1A6E3F; }

.part-hd-info { flex: 1; min-width: 0; }
.part-hd-title { font-size: 11px; font-weight: 700; color: var(--text); line-height: 1.2; }
.part-hd-sub   { font-size: 10px; color: var(--text-muted); }

.part-hd-badge {
  font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 10px; flex-shrink: 0;
  background: var(--opt-sel); color: var(--navy-mid);
}
.part-arrow { font-size: 11px; color: var(--text-muted); flex-shrink: 0; transition: transform .2s; }
.part-arrow.open { transform: rotate(180deg); }

.part-body { overflow: hidden; }
.part-body.collapsed { display: none; }

.part-grid {
  display: grid;
  grid-template-columns: repeat(5, 34px);
  gap: 5px; padding: 8px 12px 10px;
}
.qbtn {
  width: 34px; height: 34px;
  border: 1.5px solid var(--border);
  background: var(--white); border-radius: 7px;
  font-size: 11px; font-weight: 600; cursor: pointer;
  transition: .15s; font-family: inherit; color: var(--text-muted);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.qbtn:hover    { border-color: var(--navy-mid); color: var(--navy-mid); }
.qbtn.answered { background: var(--navy); border-color: var(--navy); color: #fff; }
.qbtn.current  { outline: 2.5px solid var(--gold); outline-offset: 1px; font-weight: 700; }
.qbtn.flagged  { background: var(--gold-light); border-color: var(--gold); color: #fff; }

/* Legend + Actions */
.sidebar-legend {
  padding: 8px 14px 8px; border-top: 1px solid var(--border);
  display: flex; flex-direction: column; gap: 4px; flex-shrink: 0;
}
.leg { display: flex; align-items: center; gap: 7px; font-size: 11px; color: var(--text-muted); }
.leg-dot { width: 13px; height: 13px; border-radius: 3px; flex-shrink: 0; }
.sidebar-actions {
  padding: 8px 12px 12px; border-top: 1px solid var(--border);
  display: flex; flex-direction: column; gap: 7px; flex-shrink: 0;
}
.btn-submit {
  background: var(--navy); color: #fff; border: none; padding: 10px;
  border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: .2s;
  display: flex; align-items: center; justify-content: center; gap: 6px;
}
.btn-submit:hover { background: var(--navy-mid); }
.btn-flag {
  background: transparent; color: var(--gold); border: 1.5px solid var(--gold); padding: 8px;
  border-radius: 8px; font-size: 12.5px; font-weight: 500; cursor: pointer; font-family: inherit; transition: .2s;
  display: flex; align-items: center; justify-content: center; gap: 6px;
}
.btn-flag:hover { background: #FEF9EF; }

/* ─── Khu vực câu hỏi ─── */
.exam-main {
  flex: 1; overflow-y: auto; padding: 22px 30px 24px;
  display: flex; flex-direction: column;
}
.q-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.q-num { font-size: 13px; color: var(--text-muted); }
.q-num strong { font-size: 20px; color: var(--navy); font-weight: 700; }
.q-part-badge {
  background: var(--opt-sel); color: var(--navy-mid);
  padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;
}
.q-card {
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 12px; padding: 20px 22px; margin-bottom: 14px;
  box-shadow: 0 1px 6px rgba(13,43,85,.05);
}
.q-text { font-size: 16px; font-weight: 500; line-height: 1.65; color: var(--text); }

.options { display: flex; flex-direction: column; gap: 9px; margin-bottom: 20px; }
.opt {
  display: flex; align-items: center; gap: 13px; padding: 13px 18px;
  background: var(--white); border: 1.5px solid var(--border); border-radius: 10px; cursor: pointer; transition: .18s;
  box-shadow: 0 1px 4px rgba(0,0,0,.03);
}
.opt:hover    { border-color: var(--navy-mid); background: var(--opt-hover); transform: translateX(2px); }
.opt.selected { border-color: var(--opt-sel-border); background: var(--opt-sel); }
.opt-label {
  width: 32px; height: 32px; border-radius: 50%; border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 700; flex-shrink: 0; color: var(--text-muted); transition: .18s;
}
.opt.selected .opt-label { background: var(--navy-mid); border-color: var(--navy-mid); color: #fff; }
.opt-text { font-size: 14.5px; line-height: 1.55; color: var(--text); }

.tf-rows { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
.tf-row {
  display: flex; align-items: center; padding: 13px 18px;
  background: var(--white); border: 1.5px solid var(--border); border-radius: 10px; gap: 12px;
}
.tf-row-idx {
  width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
  background: var(--bg); border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 700; color: var(--text-muted);
}
.tf-text { font-size: 14px; color: var(--text); flex: 1; line-height: 1.5; }
.tf-actions { display: flex; gap: 7px; flex-shrink: 0; }
.tf-btn {
  padding: 6px 13px; border-radius: 7px; border: 1.5px solid var(--border); background: var(--bg);
  color: var(--text-muted); font-size: 12px; font-weight: 700; cursor: pointer; font-family: inherit; transition: .15s;
}
.tf-btn:hover    { border-color: var(--navy-mid); color: var(--navy-mid); }
.tf-btn.active-t { background: var(--success); border-color: var(--success); color: #fff; }
.tf-btn.active-f { background: var(--danger);  border-color: var(--danger);  color: #fff; }

.short-ans-wrap { margin-bottom: 20px; }
.short-ans-hint { font-size: 13px; color: var(--text-muted); margin-bottom: 10px; display: flex; align-items: center; gap: 5px; }
.short-ans-input {
  width: 100%; max-width: 260px; padding: 13px 18px;
  border: 2px solid var(--border); border-radius: 10px;
  font-size: 20px; font-family: inherit; color: var(--navy); font-weight: 700;
  outline: none; transition: .2s; text-align: center; letter-spacing: 3px; background: var(--white);
}
.short-ans-input:focus { border-color: var(--navy-mid); box-shadow: 0 0 0 3px var(--opt-sel); }

.q-nav {
  display: flex; gap: 10px; justify-content: space-between; align-items: center;
  margin-top: auto; padding-top: 16px; border-top: 1px solid var(--border);
}
.btn-nav {
  background: var(--white); border: 1.5px solid var(--border); color: var(--text);
  padding: 9px 20px; border-radius: 8px; font-size: 13.5px; font-weight: 500;
  cursor: pointer; font-family: inherit; transition: .18s; display: flex; align-items: center; gap: 6px;
}
.btn-nav:hover    { border-color: var(--navy-mid); color: var(--navy-mid); background: var(--opt-hover); }
.btn-nav:disabled { opacity: .35; cursor: not-allowed; }
.q-progress-txt  { font-size: 13px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 6px; }
.q-progress-dot  { width: 8px; height: 8px; border-radius: 50%; background: var(--navy-mid); }

/* Modal */
.modal-bg { position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 9000; display: flex; align-items: center; justify-content: center; }
.modal-exam { background: var(--white); border-radius: 16px; padding: 32px 36px; width: 480px; max-width: 92vw; box-shadow: 0 12px 48px rgba(0,0,0,.22); }
.modal-exam-title { font-size: 20px; font-weight: 700; color: var(--navy); display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.modal-exam p { font-size: 13.5px; color: var(--text-muted); line-height: 1.6; margin-bottom: 22px; }
.modal-stats-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 10px; margin-bottom: 24px; }
.mstat { background: var(--bg); border: 1px solid var(--border); border-radius: 10px; padding: 14px 12px; text-align: center; }
.mstat-num { font-size: 26px; font-weight: 800; color: var(--navy); line-height: 1; }
.mstat-lab { font-size: 11.5px; color: var(--text-muted); margin-top: 3px; }
.modal-btns { display: flex; gap: 10px; }
.btn-modal-cancel {
  flex: 1; background: var(--white); border: 1.5px solid var(--border); color: var(--text);
  padding: 11px; border-radius: 9px; font-size: 14px; font-weight: 500; cursor: pointer; font-family: inherit; transition: .15s;
}
.btn-modal-cancel:hover { background: var(--bg); }
.btn-modal-confirm {
  flex: 1; background: var(--navy); color: #fff; border: none; padding: 11px;
  border-radius: 9px; font-size: 14px; font-weight: 700; cursor: pointer; font-family: inherit; transition: .15s;
  display: flex; align-items: center; justify-content: center; gap: 7px;
}
.btn-modal-confirm:hover { background: var(--navy-mid); }
</style>
</head>
<body>

<div id="app-header"></div>

{{-- ══ Màn hình chuẩn bị ══ --}}
<div id="screen-ready">
  <div class="ready-card">
    <div class="ready-badge"><i class="bi bi-calendar-check"></i> Thông tin kỳ thi</div>
    <div class="ready-title">{{ $thong_tin['Ten_KyThi'] }}</div>
    <div class="ready-stats">
      <div class="ready-stat">
        <div class="ready-stat-icon"><i class="bi bi-clock"></i></div>
        <div class="ready-stat-num">{{ $thong_tin['ThoiGianLamBai_KyThi'] }}</div>
        <div class="ready-stat-label">Phút làm bài</div>
      </div>
      <div class="ready-stat">
        <div class="ready-stat-icon"><i class="bi bi-patch-question"></i></div>
        <div class="ready-stat-num" id="ready-socau">--</div>
        <div class="ready-stat-label">Câu hỏi</div>
      </div>
      <div class="ready-stat">
        <div class="ready-stat-icon"><i class="bi bi-calendar3"></i></div>
        <div class="ready-stat-num" style="font-size:15px;padding-top:4px">
          {{ $thong_tin['ThoiGianBatDau_KyThi'] ? \Carbon\Carbon::parse($thong_tin['ThoiGianBatDau_KyThi'])->format('d/m/Y') : '—' }}
        </div>
        <div class="ready-stat-label">Ngày thi</div>
      </div>
    </div>
    <div class="ready-note">
      <strong><i class="bi bi-exclamation-triangle-fill"></i> Lưu ý trước khi thi:</strong><br>
      Kỳ thi gồm 3 phần: Trắc nghiệm 4 lựa chọn, Đúng/Sai và Trả lời ngắn.<br>
      Sau khi bấm <em>Bắt đầu làm bài</em>, đồng hồ sẽ chạy và không thể tạm dừng.
    </div>
    @if(session('error'))
      <div style="color:var(--danger);background:var(--danger-bg);padding:10px;border-radius:8px;margin-bottom:15px;font-size:14px;">
        {{ session('error') }}
      </div>
    @endif
    <button class="btn-start" id="btn-start">
      <i class="bi bi-play-circle-fill"></i> Bắt đầu làm bài
    </button>
    <a class="btn-back" href="{{ route('student.ky-thi') }}">
      <i class="bi bi-arrow-left"></i> Quay lại danh sách kỳ thi
    </a>
  </div>
</div>

{{-- ══ Màn hình làm bài ══ --}}
<div id="screen-exam">
  {{-- Topbar --}}
  <div class="exam-topbar">
    <div class="exam-topbar-name">
      <i class="bi bi-journal-text"></i>
      {{ $thong_tin['Ten_KyThi'] }}
    </div>
    <div class="exam-topbar-sep"></div>
    <div class="exam-topbar-info">
      <i class="bi bi-calendar3"></i>
      {{ $thong_tin['ThoiGianBatDau_KyThi'] ? \Carbon\Carbon::parse($thong_tin['ThoiGianBatDau_KyThi'])->format('d/m/Y') : '—' }}
    </div>
    <div class="exam-topbar-sep"></div>
    <div class="exam-topbar-info">
      <i class="bi bi-hash"></i>
      <strong id="total-q" style="color:#fff">0</strong>&nbsp;câu hỏi
    </div>
    <div class="exam-topbar-timer" id="timer">--:--</div>
  </div>

  {{-- Body --}}
  <div class="exam-body">

    {{-- Sidebar accordion --}}
    <div class="exam-sidebar">
      <div class="sidebar-head">
        <h3>Bảng câu hỏi</h3>
        <div class="sidebar-progress" id="answered-count">0 / 0 đã trả lời</div>
      </div>

      <div class="sidebar-parts">
        {{-- Phần I --}}
        <div class="part-sec">
          <button class="part-hd part-hd-1" onclick="togglePart(1)">
            <span class="part-hd-num">I</span>
            <div class="part-hd-info">
              <div class="part-hd-title">Phần I</div>
              <div class="part-hd-sub">Trắc nghiệm 4 phương án</div>
            </div>
            <span class="part-hd-badge" id="part-badge-1">0/0</span>
            <i class="bi bi-chevron-down part-arrow open" id="part-arrow-1"></i>
          </button>
          <div class="part-body" id="part-body-1">
            <div class="part-grid" id="part-grid-1"></div>
          </div>
        </div>

        {{-- Phần II --}}
        <div class="part-sec">
          <button class="part-hd part-hd-2" onclick="togglePart(2)">
            <span class="part-hd-num" style="background:#5A3E9E">II</span>
            <div class="part-hd-info">
              <div class="part-hd-title">Phần II</div>
              <div class="part-hd-sub">Trắc nghiệm Đúng/Sai</div>
            </div>
            <span class="part-hd-badge" id="part-badge-2">0/0</span>
            <i class="bi bi-chevron-down part-arrow open" id="part-arrow-2"></i>
          </button>
          <div class="part-body" id="part-body-2">
            <div class="part-grid" id="part-grid-2"></div>
          </div>
        </div>

        {{-- Phần III --}}
        <div class="part-sec">
          <button class="part-hd part-hd-3" onclick="togglePart(3)">
            <span class="part-hd-num" style="background:#1A6E3F">III</span>
            <div class="part-hd-info">
              <div class="part-hd-title">Phần III</div>
              <div class="part-hd-sub">Trả lời ngắn</div>
            </div>
            <span class="part-hd-badge" id="part-badge-3">0/0</span>
            <i class="bi bi-chevron-down part-arrow open" id="part-arrow-3"></i>
          </button>
          <div class="part-body" id="part-body-3">
            <div class="part-grid" id="part-grid-3"></div>
          </div>
        </div>
      </div>

      <div class="sidebar-legend">
        <div class="leg"><div class="leg-dot" style="background:var(--navy)"></div> Đã trả lời</div>
        <div class="leg"><div class="leg-dot" style="background:var(--gold-light);border:1.5px solid var(--gold)"></div> Đánh dấu</div>
        <div class="leg"><div class="leg-dot" style="background:var(--white);border:1.5px solid var(--border)"></div> Chưa làm</div>
      </div>
      <div class="sidebar-actions">
        <button class="btn-flag" id="btn-flag">
          <i class="bi bi-flag"></i> Đánh dấu câu này
        </button>
        <button class="btn-submit" id="btn-submit">
          <i class="bi bi-send-fill"></i> Nộp bài
        </button>
      </div>
    </div>

    {{-- Main câu hỏi --}}
    <div class="exam-main" id="main-area"></div>
  </div>
</div>

{{-- Modal nộp bài --}}
<div class="modal-bg" id="submit-modal" style="display:none">
  <div class="modal-exam">
    <div class="modal-exam-title">
      <i class="bi bi-send-fill" style="color:var(--navy-mid)"></i>
      Xác nhận nộp bài
    </div>
    <p>Bạn có chắc chắn muốn nộp bài không? Hành động này không thể hoàn tác.</p>
    <div class="modal-stats-grid">
      <div class="mstat"><div class="mstat-num" id="m-answered">0</div><div class="mstat-lab">Đã trả lời</div></div>
      <div class="mstat"><div class="mstat-num" id="m-unanswered" style="color:var(--danger)">0</div><div class="mstat-lab">Chưa trả lời</div></div>
      <div class="mstat"><div class="mstat-num" id="m-flagged" style="color:var(--gold)">0</div><div class="mstat-lab">Đánh dấu</div></div>
      <div class="mstat"><div class="mstat-num" id="m-total">0</div><div class="mstat-lab">Tổng số câu</div></div>
    </div>
    <div class="modal-btns">
      <button class="btn-modal-cancel" id="btn-cancel-modal">
        <i class="bi bi-arrow-left"></i> Quay lại
      </button>
      <button class="btn-modal-confirm" id="btn-confirm-submit">
        <i class="bi bi-check2-circle"></i> Xác nhận nộp
      </button>
    </div>
  </div>
</div>

<script>
const rawKyThi  = @json($thong_tin);
const rawCauHoi = @json($cau_hoi);
const timeStart = "{{ $time_start }}";
const submitUrl = "{{ route('student.nop-bai-thi') }}";

let questions = [], answers = [], flagged = [];

rawCauHoi.phan1_4pa.forEach(q => {
    questions.push({ type:'4pa', id:q.id, text:q.cau_hoi, opts:[q.a,q.b,q.c,q.d], partName:'Phần I – Trắc nghiệm 4 phương án', part:1 });
    answers.push(null);
});
rawCauHoi.phan2_ds.forEach(q => {
    questions.push({ type:'ds', id:q.id, text:q.cau_hoi, stmts:[q.md1,q.md2,q.md3,q.md4], partName:'Phần II – Trắc nghiệm Đúng/Sai', part:2 });
    answers.push([null,null,null,null]);
});
rawCauHoi.phan3_ngan.forEach(q => {
    questions.push({ type:'ngan', id:q.id, text:q.cau_hoi, partName:'Phần III – Trả lời ngắn', part:3 });
    answers.push('');
});
flagged = new Array(questions.length).fill(false);

const n4pa  = rawCauHoi.phan1_4pa.length;
const nds   = rawCauHoi.phan2_ds.length;
const nngan = rawCauHoi.phan3_ngan.length;

document.getElementById('ready-socau').textContent = questions.length;
document.getElementById('total-q').textContent     = questions.length;

let current = 0, submitted = false, timerInterval = null;
let thoiGianPhut = parseInt(rawKyThi.ThoiGianLamBai_KyThi) || 45;
let totalSeconds = thoiGianPhut * 60;

/* ─ Accordion ─ */
const partOpen = { 1: true, 2: true, 3: true };

function togglePart(p) {
    partOpen[p] = !partOpen[p];
    document.getElementById('part-body-' + p).classList.toggle('collapsed', !partOpen[p]);
    const arrow = document.getElementById('part-arrow-' + p);
    arrow.classList.toggle('open', partOpen[p]);
}

function ensurePartOpen(p) {
    if (!partOpen[p]) togglePart(p);
}

function getPartOf(idx) {
    if (idx < n4pa) return 1;
    if (idx < n4pa + nds) return 2;
    return 3;
}

/* ─ Bắt đầu ─ */
document.getElementById('btn-start').onclick = () => {
    document.getElementById('screen-ready').style.display = 'none';
    document.getElementById('screen-exam').style.display  = 'flex';
    render();
    startTimer();
};

function startTimer() {
    timerInterval = setInterval(() => {
        totalSeconds--;
        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            alert('Hết giờ! Hệ thống tự động nộp bài.');
            executeSubmit();
            return;
        }
        const m = Math.floor(totalSeconds / 60);
        const s = totalSeconds % 60;
        const t = document.getElementById('timer');
        t.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        if (totalSeconds <= 300) t.classList.add('warning');
    }, 1000);
}

function checkAnswered(idx) {
    const q = questions[idx], a = answers[idx];
    if (q.type === '4pa')  return a !== null;
    if (q.type === 'ds')   return a.every(v => v !== null);
    if (q.type === 'ngan') return a.trim() !== '';
    return false;
}

/* ─ Xây grid từng phần ─ */
function buildPartGrid(partNum, startIdx, count) {
    const grid = document.getElementById('part-grid-' + partNum);
    grid.innerHTML = '';
    let done = 0;
    for (let i = startIdx; i < startIdx + count; i++) {
        const isAns = checkAnswered(i);
        if (isAns) done++;
        const b = document.createElement('button');
        b.className = 'qbtn'
            + (i === current ? ' current' : '')
            + (isAns ? ' answered' : '')
            + (flagged[i] ? ' flagged' : '');
        b.textContent = i + 1;
        b.title = 'Câu ' + (i + 1);
        b.onclick = () => navigateTo(i);
        grid.appendChild(b);
    }
    document.getElementById('part-badge-' + partNum).textContent = done + '/' + count;
    return done;
}

function buildGrid() {
    const d1 = buildPartGrid(1, 0,          n4pa);
    const d2 = buildPartGrid(2, n4pa,        nds);
    const d3 = buildPartGrid(3, n4pa + nds,  nngan);
    const total = d1 + d2 + d3;
    document.getElementById('answered-count').textContent = total + ' / ' + questions.length + ' đã trả lời';
}

function navigateTo(idx) {
    current = idx;
    ensurePartOpen(getPartOf(idx));
    render();
}

function render() {
    buildGrid();
    const q    = questions[current];
    const main = document.getElementById('main-area');
    let answeredNum = 0;
    questions.forEach((_,i) => { if (checkAnswered(i)) answeredNum++; });

    let qContent = '';
    if (q.type === '4pa') {
        qContent = '<div class="options">' + q.opts.map((o,i) =>
            `<div class="opt${answers[current]===i?' selected':''}" onclick="select4PA(${i})">
               <div class="opt-label">${['A','B','C','D'][i]}</div>
               <div class="opt-text">${esc(o)}</div>
             </div>`
        ).join('') + '</div>';
    } else if (q.type === 'ds') {
        qContent = '<div class="tf-rows">' + q.stmts.map((stmt,idx) => {
            const val = answers[current][idx];
            return `<div class="tf-row">
              <div class="tf-row-idx">${['a','b','c','d'][idx]}</div>
              <div class="tf-text">${esc(stmt)}</div>
              <div class="tf-actions">
                <button class="tf-btn${val==='T'?' active-t':''}" onclick="selectDS(${idx},'T')">ĐÚNG</button>
                <button class="tf-btn${val==='F'?' active-f':''}" onclick="selectDS(${idx},'F')">SAI</button>
              </div>
            </div>`;
        }).join('') + '</div>';
    } else if (q.type === 'ngan') {
        qContent = `<div class="short-ans-wrap">
          <p class="short-ans-hint"><i class="bi bi-pencil-fill"></i> Nhập đáp án (tối đa 4 ký tự):</p>
          <input type="text" class="short-ans-input" maxlength="4" value="${esc(answers[current]||'')}"
                 oninput="inputNgan(this.value)" placeholder="_ _ _ _">
        </div>`;
    }

    main.innerHTML = `
      <div class="q-header">
        <div class="q-num">Câu <strong>${current+1}</strong> / ${questions.length}</div>
        <span class="q-part-badge">${esc(q.partName)}</span>
      </div>
      <div class="q-card"><div class="q-text">${esc(q.text)}</div></div>
      ${qContent}
      <div class="q-nav">
        <button class="btn-nav" onclick="goPrev()" ${current===0?'disabled':''}>
          <i class="bi bi-chevron-left"></i> Câu trước
        </button>
        <span class="q-progress-txt">
          <span class="q-progress-dot"></span>
          ${answeredNum} / ${questions.length} đã trả lời
        </span>
        <button class="btn-nav" onclick="goNext()" ${current===questions.length-1?'disabled':''}>
          Câu tiếp <i class="bi bi-chevron-right"></i>
        </button>
      </div>`;

    document.getElementById('btn-flag').innerHTML = flagged[current]
        ? '<i class="bi bi-x-circle"></i> Bỏ đánh dấu'
        : '<i class="bi bi-flag"></i> Đánh dấu câu này';
}

function select4PA(i) { answers[current] = i; render(); }
function selectDS(idx, val) { answers[current][idx] = val; render(); }
function inputNgan(val) { answers[current] = val; buildGrid(); }
function goPrev() { if (current > 0) navigateTo(current - 1); }
function goNext() { if (current < questions.length - 1) navigateTo(current + 1); }

document.getElementById('btn-flag').onclick = () => { flagged[current] = !flagged[current]; render(); };

document.getElementById('btn-submit').onclick = () => {
    let done = 0;
    questions.forEach((_,i) => { if (checkAnswered(i)) done++; });
    document.getElementById('m-answered').textContent   = done;
    document.getElementById('m-unanswered').textContent = questions.length - done;
    document.getElementById('m-flagged').textContent    = flagged.filter(Boolean).length;
    document.getElementById('m-total').textContent      = questions.length;
    document.getElementById('submit-modal').style.display = 'flex';
};
document.getElementById('btn-cancel-modal').onclick   = () => { document.getElementById('submit-modal').style.display = 'none'; };
document.getElementById('btn-confirm-submit').onclick = () => { document.getElementById('submit-modal').style.display = 'none'; executeSubmit(); };

function executeSubmit() {
    if (submitted) return;
    submitted = true;
    clearInterval(timerInterval);
    const timeSpent = (thoiGianPhut * 60) - totalSeconds;
    const form = document.createElement('form');
    form.method = 'POST'; form.action = submitUrl;
    form.appendChild(hi('_token',    '{{ csrf_token() }}'));
    form.appendChild(hi('id_kythi',  rawKyThi.ID_KyThi));
    form.appendChild(hi('time_start', timeStart));
    form.appendChild(hi('time_spent', timeSpent));
    questions.forEach((q,i) => {
        const val = answers[i];
        if (q.type === '4pa' && val !== null) {
            form.appendChild(hi(`answers[phan1][${q.id}]`, ['A','B','C','D'][val]));
        } else if (q.type === 'ds') {
            const str = val.map((v,idx) => `${idx+1}:${v||'X'}`).join(',');
            form.appendChild(hi(`answers[phan2][${q.id}]`, str));
        } else if (q.type === 'ngan' && val.trim() !== '') {
            form.appendChild(hi(`answers[phan3][${q.id}]`, val.trim()));
        }
    });
    document.body.appendChild(form);
    form.submit();
}

function hi(name, value) {
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = name; inp.value = value;
    return inp;
}
function esc(str) {
    const d = document.createElement('div');
    d.textContent = str ?? '';
    return d.innerHTML;
}
</script>

<script>
  window.PAGE_USER_NAME = "{{ session('auth.name') }}";
  window.PAGE_ROLE   = 'hocsinh';
  window.PAGE_ACTIVE = 'hs-ds-kythi';
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
