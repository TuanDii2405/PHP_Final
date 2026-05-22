<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem lại bài thi – {{ $diem_so->Ten_KyThi }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        /* ── Review wrapper ── */
        .review-wrap { max-width: 860px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }

        /* ── Score summary card ── */
        .score-card {
            background: linear-gradient(135deg, var(--cerulean, #1a73e8) 0%, #0d5bbf 100%);
            color: #fff;
            border-radius: 12px;
            padding: 24px 28px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: center;
        }
        .score-card h2 { margin: 0 0 4px; font-size: 1.25rem; font-weight: 700; }
        .score-card .meta { font-size: 0.85rem; opacity: .85; margin-bottom: 14px; }
        .score-card .part-chips { display: flex; gap: 10px; flex-wrap: wrap; }
        .part-chip {
            background: rgba(255,255,255,.18);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: .82rem;
            display: flex; align-items: center; gap: 6px;
        }
        .part-chip.correct { background: rgba(0,200,100,.30); }
        .part-chip.wrong   { background: rgba(255,80,80,.30); }
        .big-score {
            text-align: center;
            background: rgba(255,255,255,.15);
            border-radius: 12px;
            padding: 16px 28px;
            min-width: 110px;
        }
        .big-score .num { font-size: 2.6rem; font-weight: 800; line-height: 1; }
        .big-score .lbl { font-size: .75rem; opacity: .8; margin-top: 4px; }

        /* ── Part header ── */
        .part-header {
            display: flex; align-items: center; gap: 10px;
            font-size: 1rem; font-weight: 700;
            color: var(--cerulean, #1a73e8);
            border-bottom: 2px solid var(--cerulean, #1a73e8);
            padding-bottom: 6px;
            margin-bottom: 4px;
        }
        .part-header i { font-size: 1.1rem; }

        /* ── Question card ── */
        .q-card {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e5e9f0;
            overflow: hidden;
            transition: box-shadow .15s;
        }
        .q-card:hover { box-shadow: 0 3px 14px rgba(0,0,0,.08); }
        .q-top {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 14px 16px 10px;
        }
        .q-num {
            flex-shrink: 0;
            width: 30px; height: 30px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; font-weight: 700;
            background: #e8f0fb; color: var(--cerulean, #1a73e8);
        }
        .q-num.ok  { background: #d4f5e5; color: #1a8a50; }
        .q-num.err { background: #fde8e8; color: #c0392b; }
        .q-text { flex: 1; font-size: .92rem; line-height: 1.5; color: #1e2a3a; padding-top: 4px; }

        /* ── Options (4PA) ── */
        .opts { padding: 0 16px 14px 58px; display: flex; flex-direction: column; gap: 6px; }
        .opt {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 10px; border-radius: 7px;
            font-size: .88rem; border: 1px solid #e8ecf3;
        }
        .opt .opt-key {
            width: 24px; height: 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 700; flex-shrink: 0;
            background: #edf2fb; color: #5670a0;
        }
        .opt.student-pick { border-color: #e07b2c; background: #fff8f2; }
        .opt.student-pick .opt-key { background: #e07b2c; color: #fff; }
        .opt.correct-ans  { border-color: #1a8a50; background: #f0fdf6; }
        .opt.correct-ans .opt-key  { background: #1a8a50; color: #fff; }
        .opt.both-match   { border-color: #1a8a50; background: #f0fdf6; }
        .opt.both-match .opt-key   { background: #1a8a50; color: #fff; }

        /* ── DS statements ── */
        .ds-stmts { padding: 0 16px 14px 58px; display: flex; flex-direction: column; gap: 6px; }
        .ds-row {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 10px; border-radius: 7px;
            font-size: .88rem; border: 1px solid #e8ecf3;
        }
        .ds-row .md-idx {
            flex-shrink: 0; font-weight: 700; color: #7a8cb0; min-width: 14px;
        }
        .ds-row .md-text { flex: 1; }
        .ds-badges { display: flex; gap: 6px; flex-shrink: 0; align-items: center; }
        .badge-tf {
            padding: 2px 8px; border-radius: 10px; font-size: .75rem; font-weight: 700;
        }
        .badge-tf.t  { background: #d4f5e5; color: #1a6640; }
        .badge-tf.f  { background: #fde8e8; color: #9b2020; }
        .badge-tf.neutral { background: #edf2fb; color: #5670a0; }
        .badge-match { font-size: .85rem; }
        .badge-match.ok  { color: #1a8a50; }
        .badge-match.err { color: #c0392b; }

        /* ── Short answer ── */
        .short-ans { padding: 0 16px 14px 58px; display: flex; flex-direction: column; gap: 8px; }
        .ans-row { display: flex; gap: 10px; align-items: baseline; font-size: .88rem; }
        .ans-row .lbl { font-weight: 600; color: #7a8cb0; min-width: 120px; flex-shrink: 0; }
        .ans-row .val { padding: 3px 10px; border-radius: 6px; font-family: monospace; }
        .ans-row .val.student { background: #fff3e0; color: #8a4a00; border: 1px solid #f0c070; }
        .ans-row .val.correct  { background: #f0fdf6; color: #1a6640; border: 1px solid #7dd8a8; }

        /* ── Empty notice ── */
        .no-part { color: #aaa; font-style: italic; font-size: .88rem; text-align: center; padding: 16px 0; }

        /* ── Back button ── */
        .back-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--cerulean, #1a73e8); color: #fff;
            border: none; border-radius: 8px; padding: 9px 20px;
            font-size: .88rem; font-weight: 600; cursor: pointer;
            text-decoration: none;
        }
        .back-btn:hover { opacity: .88; }
    </style>
</head>
<body>
<div id="app-header"></div>
<div class="layout">
    <div id="app-sidebar"></div>
    <main class="main-content">
        <div class="role-title-box"><h2>VAI TRÒ HỌC SINH</h2></div>
        <div class="content-box">
            <div class="section-title blue">Xem lại bài thi</div>

            <div class="review-wrap">

                {{-- ── Score summary ── --}}
                <div class="score-card">
                    <div>
                        <h2>{{ $diem_so->Ten_KyThi }}</h2>
                        <div class="meta">
                            {{ $diem_so->Ten_MonHoc }}
                            &nbsp;·&nbsp;
                            Nộp lúc {{ \Carbon\Carbon::parse($diem_so->ThoiGianKetThuc_DiemSo)->format('H:i d/m/Y') }}
                            &nbsp;·&nbsp;
                            Thời gian làm: {{ $diem_so->ThoiGianLamBai_DiemSo }} phút
                        </div>
                        <div class="part-chips">
                            @php
                                $max4pa  = (float)($diem_so->diem_4pa_max  ?? 0);
                                $maxDs   = (float)($diem_so->diem_ds_max   ?? 0);
                                $maxNgan = (float)($diem_so->diem_ngan_max ?? 0);
                                $got4pa  = (float)($diem_so->DiemPhanTracNghiem4PhuongAn_DiemSo  ?? 0);
                                $gotDs   = (float)($diem_so->DiemPhanTracNghiemDungSai_DiemSo    ?? 0);
                                $gotNgan = (float)($diem_so->DiemPhanTracNghiemTraLoiNgan_DiemSo ?? 0);
                            @endphp
                            @if ($max4pa > 0)
                            <div class="part-chip {{ $got4pa >= $max4pa ? 'correct' : '' }}">
                                <i class="bi bi-list-check"></i>
                                Phần I: {{ number_format($got4pa, 2) }}/{{ number_format($max4pa, 2) }}đ
                            </div>
                            @endif
                            @if ($maxDs > 0)
                            <div class="part-chip {{ $gotDs >= $maxDs ? 'correct' : '' }}">
                                <i class="bi bi-toggle-on"></i>
                                Phần II: {{ number_format($gotDs, 2) }}/{{ number_format($maxDs, 2) }}đ
                            </div>
                            @endif
                            @if ($maxNgan > 0)
                            <div class="part-chip {{ $gotNgan >= $maxNgan ? 'correct' : '' }}">
                                <i class="bi bi-pencil-square"></i>
                                Phần III: {{ number_format($gotNgan, 2) }}/{{ number_format($maxNgan, 2) }}đ
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="big-score">
                        <div class="num">{{ number_format($diem_so->TongDiem_DiemSo, 2) }}</div>
                        <div class="lbl">tổng điểm</div>
                    </div>
                </div>

                {{-- Mode 2 notice --}}
                @if ($mode === 2)
                <div style="background:#fff8e1;border:1px solid #f0c040;border-radius:8px;padding:10px 16px;font-size:.85rem;color:#7a5c00;display:flex;align-items:center;gap:8px">
                    <i class="bi bi-eye-slash-fill"></i>
                    Giáo viên chỉ cho phép xem bài làm, không hiển thị đáp án đúng.
                </div>
                @endif

                {{-- ── Part I: 4PA ── --}}
                @if (count($ids1) > 0)
                <div>
                    <div class="part-header">
                        <i class="bi bi-list-check"></i>
                        Phần I – Trắc nghiệm 4 phương án
                    </div>
                    @foreach ($ids1 as $qIdx => $qId)
                        @php
                            $q = $cau_4pa[$qId] ?? null;
                            $studentAns = strtoupper(trim($lich_su['phan1'][$qId] ?? ''));
                            $correctAns = $mode === 1 ? strtoupper(trim($q['dap_an'] ?? '')) : '';
                            $isCorrect  = $mode === 1 && $q && $studentAns !== '' && $studentAns === $correctAns;
                            $opts = ['A' => $q['a'] ?? '', 'B' => $q['b'] ?? '', 'C' => $q['c'] ?? '', 'D' => $q['d'] ?? ''];
                        @endphp
                        <div class="q-card" style="margin-bottom:10px">
                            <div class="q-top">
                                <div class="q-num {{ $mode === 1 ? ($isCorrect ? 'ok' : 'err') : '' }}">{{ $qIdx + 1 }}</div>
                                <div class="q-text">{{ $q['cau_hoi'] ?? '(Câu hỏi không còn tồn tại)' }}</div>
                                @if ($mode === 1)
                                <div style="flex-shrink:0;font-size:1.1rem;padding-top:4px">
                                    <i class="bi bi-{{ $isCorrect ? 'check-circle-fill' : 'x-circle-fill' }}"
                                       style="color:{{ $isCorrect ? '#1a8a50' : '#c0392b' }}"></i>
                                </div>
                                @endif
                            </div>
                            @if ($q)
                            <div class="opts">
                                @foreach ($opts as $key => $text)
                                    @php
                                        $isStudent    = ($key === $studentAns);
                                        $isCorrectOpt = ($mode === 1 && $key === $correctAns);
                                        $cls = '';
                                        if ($isStudent && $isCorrectOpt) $cls = 'both-match';
                                        elseif ($isCorrectOpt)           $cls = 'correct-ans';
                                        elseif ($isStudent)              $cls = 'student-pick';
                                    @endphp
                                    <div class="opt {{ $cls }}">
                                        <div class="opt-key">{{ $key }}</div>
                                        <span>{{ $text }}</span>
                                        @if ($mode === 1)
                                            @if ($isStudent && !$isCorrectOpt)
                                                <span style="margin-left:auto;font-size:.78rem;color:#c0392b;font-weight:600">Bạn chọn</span>
                                            @elseif ($isCorrectOpt && !$isStudent)
                                                <span style="margin-left:auto;font-size:.78rem;color:#1a8a50;font-weight:600">Đáp án</span>
                                            @elseif ($isStudent && $isCorrectOpt)
                                                <span style="margin-left:auto;font-size:.78rem;color:#1a8a50;font-weight:600">Bạn chọn ✓</span>
                                            @endif
                                        @else
                                            @if ($isStudent)
                                                <span style="margin-left:auto;font-size:.78rem;color:#e07b2c;font-weight:600">Bạn chọn</span>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ── Part II: Đúng/Sai ── --}}
                @if (count($ids2) > 0)
                <div>
                    <div class="part-header">
                        <i class="bi bi-toggle-on"></i>
                        Phần II – Trắc nghiệm Đúng/Sai
                    </div>
                    @foreach ($ids2 as $qIdx => $qId)
                        @php
                            $q = $cau_ds[$qId] ?? null;
                            $ansStr = (string) ($lich_su['phan2'][$qId] ?? '');

                            // Parse correct answers — guard against null/short dap_an
                            $dapAnStr = $q ? strtoupper(trim($q['dap_an'] ?? '')) : '';
                            $dapAn    = (strlen($dapAnStr) === 4) ? str_split($dapAnStr) : [];
                            $hasValidDapAn = (count($dapAn) === 4) && $mode === 1;

                            // Parse student answer "1:T,2:F,3:T,4:F" — 'X' means unanswered
                            $stuArr = [];
                            foreach (explode(',', $ansStr) as $part) {
                                [$pIdx, $pVal] = array_pad(explode(':', $part, 2), 2, '');
                                if ($pIdx !== '') $stuArr[(int)$pIdx] = strtoupper(trim($pVal));
                            }

                            // Count only actual T/F matches against valid correct answers
                            $correctCount = 0;
                            for ($i = 1; $i <= 4; $i++) {
                                $stu = $stuArr[$i] ?? '';
                                $cor = $hasValidDapAn ? ($dapAn[$i-1] ?? '') : '';
                                if (in_array($stu, ['T','F']) && $cor !== '' && $stu === $cor) {
                                    $correctCount++;
                                }
                            }
                            $isFullCorrect = ($hasValidDapAn && $correctCount === 4);
                        @endphp
                        <div class="q-card" style="margin-bottom:10px">
                            <div class="q-top">
                                <div class="q-num {{ $hasValidDapAn ? ($isFullCorrect ? 'ok' : ($correctCount > 0 ? '' : 'err')) : '' }}">{{ $qIdx + 1 }}</div>
                                <div class="q-text">{{ $q['cau_hoi'] ?? '(Câu hỏi không còn tồn tại)' }}</div>
                                @if ($mode === 1)
                                <div style="flex-shrink:0;font-size:.85rem;padding-top:4px;color:#5670a0;font-weight:600">
                                    {{ $hasValidDapAn ? $correctCount . '/4 ý đúng' : '—' }}
                                </div>
                                @endif
                            </div>
                            @if ($q)
                            <div class="ds-stmts">
                                @foreach (['md1','md2','md3','md4'] as $mdi => $mdKey)
                                    @php
                                        $mdNum  = $mdi + 1;
                                        $mdText = $q[$mdKey] ?? '';
                                        $stuVal = $stuArr[$mdNum] ?? 'X';
                                        $corVal = $hasValidDapAn ? ($dapAn[$mdi] ?? '') : '';
                                        // Match only when student actually answered (T or F) and dap_an is valid
                                        $hasAnswered = in_array($stuVal, ['T','F']);
                                        $mdMatch = $hasAnswered && $corVal !== '' && $stuVal === $corVal;
                                    @endphp
                                    <div class="ds-row">
                                        <span class="md-idx">{{ $mdNum }}.</span>
                                        <span class="md-text">{{ $mdText }}</span>
                                        <div class="ds-badges">
                                            @if ($mode === 1)
                                                @if ($corVal !== '')
                                                <span class="badge-tf neutral">Đáp án:
                                                    <strong class="{{ $corVal === 'T' ? 't' : 'f' }}">{{ $corVal === 'T' ? 'Đúng' : 'Sai' }}</strong>
                                                </span>
                                                @else
                                                <span class="badge-tf neutral" style="opacity:.6">Không xác định</span>
                                                @endif
                                            @endif

                                            @if ($hasAnswered)
                                            <span class="badge-tf {{ $stuVal === 'T' ? 't' : 'f' }}">
                                                Bạn chọn: {{ $stuVal === 'T' ? 'Đúng' : 'Sai' }}
                                            </span>
                                            @else
                                            <span class="badge-tf neutral">Chưa chọn</span>
                                            @endif

                                            @if ($mode === 1 && $corVal !== '')
                                            <span class="badge-match {{ $mdMatch ? 'ok' : 'err' }}">
                                                <i class="bi bi-{{ $mdMatch ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ── Part III: Trả lời ngắn ── --}}
                @if (count($ids3) > 0)
                <div>
                    <div class="part-header">
                        <i class="bi bi-pencil-square"></i>
                        Phần III – Trắc nghiệm trả lời ngắn
                    </div>
                    @foreach ($ids3 as $qIdx => $qId)
                        @php
                            $q = $cau_ngan[$qId] ?? null;
                            $studentAns = trim((string) ($lich_su['phan3'][$qId] ?? ''));
                            $correctAns = $mode === 1 ? trim($q['dap_an'] ?? '') : '';
                            $isCorrect  = $mode === 1 && $q && $studentAns !== '' && strcasecmp($studentAns, $correctAns) === 0;
                        @endphp
                        <div class="q-card" style="margin-bottom:10px">
                            <div class="q-top">
                                <div class="q-num {{ $mode === 1 ? ($isCorrect ? 'ok' : 'err') : '' }}">{{ $qIdx + 1 }}</div>
                                <div class="q-text">{{ $q['cau_hoi'] ?? '(Câu hỏi không còn tồn tại)' }}</div>
                                @if ($mode === 1)
                                <div style="flex-shrink:0;font-size:1.1rem;padding-top:4px">
                                    <i class="bi bi-{{ $isCorrect ? 'check-circle-fill' : 'x-circle-fill' }}"
                                       style="color:{{ $isCorrect ? '#1a8a50' : '#c0392b' }}"></i>
                                </div>
                                @endif
                            </div>
                            @if ($q)
                            <div class="short-ans">
                                <div class="ans-row">
                                    <span class="lbl"><i class="bi bi-person-fill"></i> Câu trả lời của bạn:</span>
                                    <span class="val student">{{ $studentAns !== '' ? $studentAns : '(Chưa trả lời)' }}</span>
                                </div>
                                @if ($mode === 1)
                                <div class="ans-row">
                                    <span class="lbl"><i class="bi bi-check2-all"></i> Đáp án đúng:</span>
                                    <span class="val correct">{{ $correctAns }}</span>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                @if (count($ids1) === 0 && count($ids2) === 0 && count($ids3) === 0)
                <div class="no-part">Không có dữ liệu bài làm để hiển thị.</div>
                @endif

                <div style="padding-bottom:24px">
                    <a class="back-btn" href="{{ route('student.lich-su-bai') }}">
                        <i class="bi bi-arrow-left"></i> Quay lại lịch sử làm bài
                    </a>
                </div>

            </div>{{-- .review-wrap --}}
        </div>
    </main>
</div>
<script>window.PAGE_USER_NAME = "{{ session('auth.name') }}";
      window.PAGE_ROLE = 'hocsinh'; window.PAGE_ACTIVE = 'hs-lichsu-lamdai';</script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
</body>
</html>
