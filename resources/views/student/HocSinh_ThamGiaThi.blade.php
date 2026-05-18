<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tham Gia Kỳ Thi</title>
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
<style>
/* ── Reset cục bộ ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --navy:    #0D2B55; --navy-light: #1A3F73; --navy-mid: #2563A8;
  --gold:    #C9943A; --gold-light: #E8B458;
  --bg:      #F4F7FC; --white: #FFFFFF;
  --text:    #1A2332; --text-muted: #5A6A80;
  --border:  #D0DAEA;
  --success: #1A6E3F; --success-bg: #EAF6EF;
  --danger:  #A02020; --danger-bg:  #FDF0F0;
  --option-hover: #EFF4FF; --option-selected: #DBE8FF; --option-border-sel: #2563A8;
}

/* Các style cũ giữ nguyên */
#screen-ready { display: flex; align-items: center; justify-content: center; padding: 40px 20px; min-height: calc(100vh - 60px); background: var(--bg); }
.ready-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 16px; padding: 40px 44px 36px; max-width: 560px; width: 100%; box-shadow: 0 4px 24px rgba(13,43,85,.08); text-align: center; }
.ready-badge { display: inline-block; background: var(--option-selected); color: var(--navy-mid); font-size: 12px; font-weight: 600; padding: 4px 14px; border-radius: 20px; letter-spacing: .4px; margin-bottom: 14px; }
.ready-title { font-size: 22px; font-weight: 700; color: var(--navy); line-height: 1.35; margin-bottom: 28px; }
.ready-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 32px; }
.ready-stat { background: var(--bg); border: 1.5px solid var(--border); border-radius: 12px; padding: 18px 10px 14px; }
.ready-stat-icon { font-size: 22px; margin-bottom: 6px; }
.ready-stat-num { font-size: 26px; font-weight: 700; color: var(--navy); line-height: 1; margin-bottom: 4px; }
.ready-stat-label { font-size: 11.5px; color: var(--text-muted); font-weight: 500; }
.ready-note { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 28px; padding: 12px 16px; background: #FEF9EF; border: 1px solid #F0D9A0; border-radius: 10px; text-align: left; }
.ready-note strong { color: var(--gold); }
.btn-start { background: var(--navy); color: #fff; border: none; padding: 13px 36px; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; font-family: inherit; transition: background .2s, transform .15s; width: 100%; letter-spacing: .3px; }
.btn-start:hover { background: var(--navy-mid); transform: translateY(-1px); }
.btn-back { display: inline-block; margin-top: 14px; font-size: 13px; color: var(--text-muted); cursor: pointer; text-decoration: none; transition: color .2s; }
.btn-back:hover { color: var(--navy-mid); }

#screen-exam { display: none; flex-direction: column; height: 100vh; overflow: hidden; background: var(--bg); }
.exam-layout { display: flex; flex: 1; overflow: hidden; }
.sidebar { width: 240px; min-width: 240px; background: var(--white); border-right: 1px solid var(--border); display: flex; flex-direction: column; overflow-y: auto; }
.sidebar-head { padding: 14px 16px; border-bottom: 1px solid var(--border); background: var(--bg); }
.sidebar-head h3 { font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; }
.sidebar-head p  { font-size: 12px; color: var(--text-muted); margin-top: 3px; }
.q-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 5px; padding: 12px; }
.qbtn { width: 100%; aspect-ratio: 1; border: 1.5px solid var(--border); background: var(--white); border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; transition: .15s; font-family: inherit; color: var(--text-muted); }
.qbtn:hover    { border-color: var(--navy-mid); color: var(--navy-mid); }
.qbtn.answered { background: var(--navy); border-color: var(--navy); color: #fff; }
.qbtn.current  { border-color: var(--gold); color: var(--gold); font-weight: 700; }
.qbtn.flagged  { background: var(--gold-light); border-color: var(--gold); color: #fff; }
.sidebar-legend { padding: 10px 12px; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 5px; }
.leg { display: flex; align-items: center; gap: 7px; font-size: 11.5px; color: var(--text-muted); }
.leg-dot { width: 14px; height: 14px; border-radius: 3px; flex-shrink: 0; }
.sidebar-actions { padding: 12px; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 8px; margin-top: auto; }
.btn-submit { background: var(--navy); color: #fff; border: none; padding: 11px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; transition: .2s; }
.btn-submit:hover { background: var(--navy-mid); }
.btn-flag { background: transparent; color: var(--gold); border: 1.5px solid var(--gold); padding: 9px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; font-family: inherit; transition: .2s; }
.btn-flag:hover { background: #FEF9EF; }

.main { flex: 1; overflow-y: auto; padding: 24px 32px; }
.q-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
.q-num { font-size: 13px; color: var(--text-muted); }
.q-num strong { font-size: 20px; color: var(--navy); font-weight: 700; }
.q-topic { background: var(--option-selected); color: var(--navy-mid); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.q-text { font-size: 16px; font-weight: 500; line-height: 1.65; color: var(--text); margin-bottom: 20px; padding: 18px 20px; background: var(--white); border-radius: 10px; border: 1px solid var(--border); }
.options { display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px; }
.opt { display: flex; align-items: center; gap: 14px; padding: 14px 18px; background: var(--white); border: 1.5px solid var(--border); border-radius: 10px; cursor: pointer; transition: .2s; }
.opt:hover    { border-color: var(--navy-mid); background: var(--option-hover); }
.opt.selected { border-color: var(--option-border-sel); background: var(--option-selected); }
.opt-label { width: 30px; height: 30px; border-radius: 50%; border: 1.5px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0; color: var(--text-muted); transition: .2s; }
.opt.selected .opt-label { background: var(--navy-mid); border-color: var(--navy-mid); color: #fff; }
.opt-text { font-size: 14.5px; line-height: 1.55; color: var(--text); }

.tf-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; background: var(--white); border: 1.5px solid var(--border); border-radius: 10px; margin-bottom: 10px; }
.tf-text { font-size: 14.5px; color: var(--text); padding-right: 20px; }
.tf-actions { display: flex; gap: 8px; flex-shrink: 0; }
.tf-btn { padding: 6px 14px; border-radius: 6px; border: 1.5px solid var(--border); background: var(--bg); color: var(--text-muted); font-size: 13px; font-weight: 600; cursor: pointer; }
.tf-btn.active-t { background: var(--success); border-color: var(--success); color: #fff; }
.tf-btn.active-f { background: var(--danger); border-color: var(--danger); color: #fff; }

.short-ans-wrap { margin-bottom: 24px; }
.short-ans-input { width: 100%; max-width: 300px; padding: 14px 18px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 16px; font-family: inherit; color: var(--navy); font-weight: 600; outline: none; transition: .2s; }
.short-ans-input:focus { border-color: var(--navy-mid); box-shadow: 0 0 0 3px var(--option-selected); }

.q-nav { display: flex; gap: 10px; justify-content: space-between; align-items: center; }
.btn-nav { background: var(--white); border: 1.5px solid var(--border); color: var(--text); padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; font-family: inherit; transition: .2s; display: flex; align-items: center; gap: 6px; }
.btn-nav:hover    { border-color: var(--navy-mid); color: var(--navy-mid); }
.btn-nav:disabled { opacity: .4; cursor: not-allowed; }
.q-progress { font-size: 13px; color: var(--text-muted); }

.exam-bar { background: var(--navy-light); color: #fff; padding: 10px 24px; display: flex; align-items: center; gap: 20px; font-size: 13px; flex-shrink: 0; border-top: 1px solid rgba(255,255,255,.1); }
.exam-bar span { opacity: .85; }
.exam-bar strong { opacity: 1; font-weight: 600; }
.exam-bar .sep { opacity: .3; margin: 0 4px; }
.timer-wrap { margin-left: auto; display: flex; align-items: center; gap: 8px; }
.timer-box { background: var(--gold); color: #fff; padding: 6px 14px; border-radius: 6px; font-size: 16px; font-weight: 700; font-variant-numeric: tabular-nums; letter-spacing: 1px; min-width: 90px; text-align: center; }
.timer-box.warning { background: #C0392B; animation: pulse 1s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.75} }

.modal-bg { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 100; display: flex; align-items: center; justify-content: center; }
.modal { background: var(--white); border-radius: 14px; padding: 28px 32px; width: 500px; max-width: 90vw; box-shadow: 0 8px 40px rgba(0,0,0,.2); }
.modal h2 { font-size: 20px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
.modal p  { font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 20px; }
.modal-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 24px; }
.stat { background: var(--bg); border-radius: 8px; padding: 14px; text-align: center; }
.stat-num { font-size: 24px; font-weight: 700; color: var(--navy); }
.stat-lab { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
.modal-btns { display: flex; gap: 10px; }
.btn-cancel  { flex: 1; background: var(--white); border: 1.5px solid var(--border); color: var(--text); padding: 11px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; font-family: inherit; }
.btn-confirm { flex: 1; background: var(--navy); color: #fff; border: none; padding: 11px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; font-family: inherit; }
</style>
</head>
<body>

<div id="app-header"></div>

<div id="screen-ready">
  <div class="ready-card">
    <div class="ready-badge">📋 Thông tin kỳ thi</div>
    <div class="ready-title" id="ready-title">{{ $thong_tin['Ten_KyThi'] }}</div>

    <div class="ready-stats">
      <div class="ready-stat">
        <div class="ready-stat-icon">⏱</div>
        <div class="ready-stat-num" id="ready-thoigian">{{ $thong_tin['ThoiGianLamBai_KyThi'] }}</div>
        <div class="ready-stat-label">Phút làm bài</div>
      </div>
      <div class="ready-stat">
        <div class="ready-stat-icon">📝</div>
        <div class="ready-stat-num" id="ready-socau">--</div>
        <div class="ready-stat-label">Câu hỏi</div>
      </div>
      <div class="ready-stat">
        <div class="ready-stat-icon">📅</div>
        <div class="ready-stat-num" id="ready-ngaythi" style="font-size:16px;padding-top:4px">
            {{ \Carbon\Carbon::parse($thong_tin['ThoiGianBatDau_KyThi'])->format('d/m/Y') }}
        </div>
        <div class="ready-stat-label">Ngày thi</div>
      </div>
    </div>

    <div class="ready-note">
      <strong>⚠ Lưu ý trước khi thi:</strong><br>
      Kỳ thi gồm 3 phần: Trắc nghiệm 4 lựa chọn, Đúng/Sai, và Trả lời ngắn.<br>
      Sau khi bấm <em>Bắt đầu làm bài</em>, đồng hồ sẽ chạy và không thể tạm dừng. 
      Nộp bài đúng giờ hoặc hệ thống sẽ tự nộp khi hết thời gian.
    </div>

    @if(session('error'))
        <div style="color:var(--danger); background:var(--danger-bg); padding:10px; border-radius:8px; margin-bottom:15px; font-size:14px;">
            {{ session('error') }}
        </div>
    @endif

    <button class="btn-start" id="btn-start">🚀 Bắt đầu làm bài</button>
    <a class="btn-back" href="{{ route('student.ky-thi') }}">← Quay lại danh sách kỳ thi</a>
  </div>
</div>

<div id="screen-exam">
  <div class="exam-layout">
    <div class="sidebar">
      <div class="sidebar-head">
        <h3>Bảng câu hỏi</h3>
        <p id="answered-count">Đã trả lời: 0 / 0</p>
      </div>
      <div class="q-grid" id="q-grid"></div>
      <div class="sidebar-legend">
        <div class="leg"><div class="leg-dot" style="background:var(--navy)"></div>Đã trả lời</div>
        <div class="leg"><div class="leg-dot" style="background:var(--gold-light)"></div>Đánh dấu</div>
        <div class="leg"><div class="leg-dot" style="background:var(--white);border:1.5px solid var(--border)"></div>Chưa làm</div>
      </div>
      <div class="sidebar-actions">
        <button class="btn-flag" id="btn-flag">⚑ Đánh dấu câu này</button>
        <button class="btn-submit" id="btn-submit">📤 Nộp bài</button>
      </div>
    </div>

    <div class="main" id="main-area"></div>
  </div>

  <div class="exam-bar">
    <span>📋 <strong>{{ $thong_tin['Ten_KyThi'] }}</strong></span>
    <span class="sep">|</span>
    <span>📅 <strong>{{ \Carbon\Carbon::parse($thong_tin['ThoiGianBatDau_KyThi'])->format('d/m/Y') }}</strong></span>
    <span class="sep">|</span>
    <span>🔢 <strong id="total-q">0</strong> câu hỏi</span>
    <div class="timer-wrap">
      ⏱ Thời gian còn lại:
      <div class="timer-box" id="timer">--:--</div>
    </div>
  </div>
</div>

<div class="modal-bg" id="submit-modal" style="display:none">
  <div class="modal">
    <h2>Xác nhận nộp bài</h2>
    <p>Bạn có chắc chắn muốn nộp bài không? Hành động này sẽ được lưu trực tiếp lên hệ thống.</p>
    <div class="modal-stats">
      <div class="stat"><div class="stat-num" id="m-answered">0</div><div class="stat-lab">Đã trả lời</div></div>
      <div class="stat"><div class="stat-num" id="m-unanswered">0</div><div class="stat-lab">Chưa trả lời</div></div>
      <div class="stat"><div class="stat-num" id="m-flagged">0</div><div class="stat-lab">Đánh dấu</div></div>
      <div class="stat"><div class="stat-num" id="m-total">0</div><div class="stat-lab">Tổng số câu</div></div>
    </div>
    <div class="modal-btns">
      <button class="btn-cancel"  id="btn-cancel-modal">Quay lại</button>
      <button class="btn-confirm" id="btn-confirm-submit">Xác nhận nộp</button>
    </div>
  </div>
</div>

<script>
// ═══════════════════════════════════
//  1. NHẬN DỮ LIỆU TỪ PHP BẮN SANG JS (Chuẩn Blade)
// ═══════════════════════════════════
const rawKyThi  = @json($thong_tin);
const rawCauHoi = @json($cau_hoi);
const timeStart = "{{ $time_start }}";

let questions = []; // Mảng gộp cả 3 loại câu hỏi
let answers = [];   // Lưu đáp án của HS
let flagged = [];   // Cờ đánh dấu

// Gộp Phần 1: 4 Phương án
rawCauHoi.phan1_4pa.forEach(q => {
    questions.push({
        type: '4pa', id: q.id, text: q.cau_hoi,
        opts: [q.a, q.b, q.c, q.d],
        partName: 'Phần I: Câu trắc nghiệm nhiều phương án'
    });
    answers.push(null);
});
// Gộp Phần 2: Đúng / Sai
rawCauHoi.phan2_ds.forEach(q => {
    questions.push({
        type: 'ds', id: q.id, text: q.cau_hoi,
        stmts: [q.md1, q.md2, q.md3, q.md4],
        partName: 'Phần II: Câu trắc nghiệm Đúng/Sai'
    });
    answers.push([null, null, null, null]); // 4 ý chưa trả lời
});
// Gộp Phần 3: Trả lời ngắn
rawCauHoi.phan3_ngan.forEach(q => {
    questions.push({
        type: 'ngan', id: q.id, text: q.cau_hoi,
        partName: 'Phần III: Câu trắc nghiệm trả lời ngắn'
    });
    answers.push('');
});

flagged = new Array(questions.length).fill(false);

// Cập nhật số liệu hiển thị ban đầu
document.getElementById('ready-socau').textContent = questions.length;
document.getElementById('total-q').textContent = questions.length;

let current = 0, submitted = false;
let timerInterval = null;
let thoiGianPhut = parseInt(rawKyThi.ThoiGianLamBai_KyThi) || 45;
let totalSeconds = thoiGianPhut * 60;

// ═══════════════════════════════════
//  2. BẮT ĐẦU LÀM BÀI & TIMER
// ═══════════════════════════════════
document.getElementById('btn-start').onclick = () => {
  document.getElementById('screen-ready').style.display = 'none';
  document.getElementById('screen-exam').style.display  = 'flex';
  document.getElementById('timer').textContent = String(thoiGianPhut).padStart(2,'0') + ':00';
  render();
  startTimer();
};

function startTimer() {
  timerInterval = setInterval(() => {
    totalSeconds--;
    if (totalSeconds <= 0) { 
        clearInterval(timerInterval); 
        alert("Đã hết thời gian làm bài. Hệ thống sẽ tự động nộp bài.");
        executeSubmit(); 
        return; 
    }
    const m = Math.floor(totalSeconds / 60);
    const s = totalSeconds % 60;
    document.getElementById('timer').textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    if (totalSeconds <= 300) document.getElementById('timer').classList.add('warning');
  }, 1000);
}

// ═══════════════════════════════════
//  3. RENDER CÂU HỎI
// ═══════════════════════════════════
function checkAnswered(idx) {
    const q = questions[idx];
    const a = answers[idx];
    if (q.type === '4pa') return a !== null;
    if (q.type === 'ds') return a.every(val => val !== null);
    if (q.type === 'ngan') return a.trim() !== '';
    return false;
}

function buildGrid() {
  const grid = document.getElementById('q-grid');
  grid.innerHTML = '';
  let doneCount = 0;

  questions.forEach((_,i) => {
    const isAns = checkAnswered(i);
    if (isAns) doneCount++;

    const b = document.createElement('button');
    b.className = 'qbtn' 
      + (i === current ? ' current'  : '')
      + (isAns         ? ' answered' : '')
      + (flagged[i]    ? ' flagged'  : '');
    b.textContent = i + 1;
    b.onclick = () => { current = i; render(); };
    grid.appendChild(b);
  });
  
  document.getElementById('answered-count').textContent = `Đã trả lời: ${doneCount} / ${questions.length}`;
}

function render() {
  buildGrid();
  const q = questions[current];
  const main = document.getElementById('main-area');
  
  const prevDis = current === 0 ? 'disabled' : '';
  const nextDis = current === questions.length - 1 ? 'disabled' : '';
  let answeredNum = 0;
  questions.forEach((_,i) => { if(checkAnswered(i)) answeredNum++; });

  let qContent = '';

  if (q.type === '4pa') {
    const opts = q.opts.map((o,i) => {
      const cls = 'opt' + (answers[current] === i ? ' selected' : '');
      return `<div class="${cls}" onclick="select4PA(${i})">
        <div class="opt-label">${['A','B','C','D'][i]}</div>
        <div class="opt-text">${o}</div>
      </div>`;
    }).join('');
    qContent = `<div class="options">${opts}</div>`;
  }
  else if (q.type === 'ds') {
    const stmts = q.stmts.map((stmt, idx) => {
        const val = answers[current][idx]; 
        const btnT = `<button class="tf-btn ${val === 'T' ? 'active-t' : ''}" onclick="selectDS(${idx}, 'T')">ĐÚNG</button>`;
        const btnF = `<button class="tf-btn ${val === 'F' ? 'active-f' : ''}" onclick="selectDS(${idx}, 'F')">SAI</button>`;
        return `
        <div class="tf-row">
            <div class="tf-text"><strong>Ý ${['a','b','c','d'][idx]})</strong> ${stmt}</div>
            <div class="tf-actions">${btnT} ${btnF}</div>
        </div>`;
    }).join('');
    qContent = `<div class="options">${stmts}</div>`;
  }
  else if (q.type === 'ngan') {
    const currentVal = answers[current] || '';
    qContent = `
    <div class="short-ans-wrap">
        <p style="margin-bottom:8px; font-size:14px; color:var(--text-muted)">Nhập đáp án của bạn (Tối đa 4 ký tự):</p>
        <input type="text" class="short-ans-input" maxlength="4" value="${currentVal}" oninput="inputNgan(this.value)" placeholder="Ví dụ: 12, 5.5, -3">
    </div>`;
  }

  main.innerHTML = `
    <div class="q-header">
      <div class="q-num">Câu <strong>${current+1}</strong> / ${questions.length}</div>
      <div class="q-topic">${q.partName}</div>
    </div>
    <div class="q-text">${q.text}</div>
    ${qContent}
    <div class="q-nav">
      <button class="btn-nav" onclick="goPrev()" ${prevDis}>◀ Câu trước</button>
      <span class="q-progress">${answeredNum} / ${questions.length} đã trả lời</span>
      <button class="btn-nav" onclick="goNext()" ${nextDis}>Câu tiếp ▶</button>
    </div>`;

  document.getElementById('btn-flag').textContent = flagged[current] ? '✕ Bỏ đánh dấu' : '⚑ Đánh dấu câu này';
}

// ═══════════════════════════════════
//  4. TƯƠNG TÁC CHỌN ĐÁP ÁN
// ═══════════════════════════════════
function select4PA(i) { answers[current] = i; render(); }
function selectDS(stmtIdx, val) { answers[current][stmtIdx] = val; render(); }
function inputNgan(val) { answers[current] = val; buildGrid(); }

function goPrev() { if (current > 0) { current--; render(); } }
function goNext() { if (current < questions.length - 1) { current++; render(); } }

document.getElementById('btn-flag').onclick = () => { flagged[current] = !flagged[current]; render(); };

// ═══════════════════════════════════
//  5. XỬ LÝ SUBMIT VỀ SERVER
// ═══════════════════════════════════
document.getElementById('btn-submit').onclick = () => {
  let doneCount = 0;
  questions.forEach((_,i) => { if(checkAnswered(i)) doneCount++; });
  
  document.getElementById('m-answered').textContent   = doneCount;
  document.getElementById('m-unanswered').textContent = questions.length - doneCount;
  document.getElementById('m-flagged').textContent    = flagged.filter(Boolean).length;
  document.getElementById('m-total').textContent      = questions.length;
  document.getElementById('submit-modal').style.display = 'flex';
};

document.getElementById('btn-cancel-modal').onclick = () => {
  document.getElementById('submit-modal').style.display = 'none';
};

document.getElementById('btn-confirm-submit').onclick = () => {
  document.getElementById('submit-modal').style.display = 'none';
  executeSubmit();
};

function executeSubmit() {
    submitted = true;
    clearInterval(timerInterval);
    
    const timeSpent = (thoiGianPhut * 60) - totalSeconds;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.location.href; 
    
    // BẮT BUỘC: Thêm Token bảo mật của Laravel
    form.appendChild(createHiddenInput('_token', '{{ csrf_token() }}'));
    
    form.appendChild(createHiddenInput('id_kythi', rawKyThi.ID_KyThi));
    form.appendChild(createHiddenInput('time_start', timeStart));
    form.appendChild(createHiddenInput('time_spent', timeSpent));

    questions.forEach((q, i) => {
        const val = answers[i];
        if (q.type === '4pa' && val !== null) {
            form.appendChild(createHiddenInput(`answers[phan1][${q.id}]`, ['A','B','C','D'][val]));
        } 
        else if (q.type === 'ds') {
            if (val.some(v => v !== null)) {
                const strDS = val.map((v, idx) => `${idx+1}:${v || 'X'}`).join(',');
                form.appendChild(createHiddenInput(`answers[phan2][${q.id}]`, strDS));
            }
        } 
        else if (q.type === 'ngan' && val.trim() !== '') {
            form.appendChild(createHiddenInput(`answers[phan3][${q.id}]`, val.trim()));
        }
    });

    document.body.appendChild(form);
    form.submit();
}

function createHiddenInput(name, value) {
    const inp = document.createElement('input');
    inp.type = 'hidden';
    inp.name = name;
    inp.value = value;
    return inp;
}

</script>

<script>
  window.PAGE_ROLE   = 'hocsinh';
  window.PAGE_ACTIVE = 'hs-ds-kythi';
</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>