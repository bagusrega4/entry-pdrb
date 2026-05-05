@extends('layouts.app')

@section('title', 'Komparasi Skenario IO')

@section('content')

<style>
    * { box-sizing: border-box; }
    body { background: #f7f6f3; }
    .page { padding: 24px 24px 48px; padding-top: 80px; font-family: 'Figtree', sans-serif; }

    .card { background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; overflow:hidden; margin-bottom:16px; }
    .card-head {
        display:flex; align-items:center; justify-content:space-between;
        padding:14px 20px; border-bottom:0.5px solid #e5e7eb; background:#fafafa; gap:8px; flex-wrap:wrap;
    }
    .card-head-left { display:flex; align-items:center; gap:8px; }
    .card-head-icon { color:#9ca3af; }
    .card-head-title { font-size:14px; font-weight:500; color:#111827; }
    .card-body { padding:20px; }

    .compare-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        align-items: start;
    }
    @media (max-width: 900px) { .compare-grid { grid-template-columns: 1fr; } }

    .sce-panel {
        border: 0.5px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .sce-panel-head {
        padding: 12px 16px;
        display: flex; align-items: center; justify-content: space-between;
        font-size: 13px; font-weight: 600;
    }
    .sce-a .sce-panel-head { background: #fff7ed; border-bottom: 0.5px solid #fed7aa; color: #c2410c; }
    .sce-b .sce-panel-head { background: #eff6ff; border-bottom: 0.5px solid #bfdbfe; color: #1d4ed8; }
    .sce-panel-body { padding: 14px 16px; }

    .form-group { margin-bottom: 12px; }
    .form-label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:5px; }
    .form-select, .form-input {
        width:100%; height:36px; padding:0 10px;
        font-size:13px; font-family:'Figtree',sans-serif; color:#374151;
        background:#fff; border:0.5px solid #d1d5db; border-radius:8px; outline:none;
        transition: border-color 0.15s;
    }
    .form-select:focus, .form-input:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.08); }

    .sektor-block {
        border:0.5px solid #e5e7eb; border-radius:8px; padding:12px;
        margin-bottom:8px; background:#fafafa;
    }
    .sektor-block-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; }
    .sektor-block-label { font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; }
    .btn-hapus-sektor {
        display:inline-flex; align-items:center; justify-content:center;
        width:22px; height:22px; border-radius:5px;
        border:0.5px solid #fca5a5; background:#fef2f2; color:#dc2626; cursor:pointer;
    }
    .btn-hapus-sektor:hover { background:#fee2e2; }

    .input-with-suffix { position:relative; }
    .input-with-suffix .form-input { padding-right:74px; }
    .input-suffix {
        position:absolute; right:8px; top:50%; transform:translateY(-50%);
        font-size:10px; font-weight:500; color:#9ca3af;
        background:#f3f4f6; border:0.5px solid #e5e7eb; border-radius:4px;
        padding:2px 5px; pointer-events:none; white-space:nowrap;
    }

    .btn-tambah {
        display:inline-flex; align-items:center; gap:5px;
        height:30px; padding:0 12px; font-size:11px; font-weight:500;
        color:#374151; background:#f3f4f6; border:0.5px solid #e5e7eb; border-radius:7px;
        cursor:pointer; font-family:'Figtree',sans-serif; margin-bottom:12px;
        transition:background 0.12s;
    }
    .btn-tambah:hover { background:#e5e7eb; }

    .btn-hitung {
        display:inline-flex; align-items:center; gap:6px;
        height:38px; padding:0 24px; font-size:13px; font-weight:500;
        color:#fff; background:#f97316; border:none; border-radius:8px;
        cursor:pointer; font-family:'Figtree',sans-serif; transition:background 0.15s;
    }
    .btn-hitung:hover { background:#ea580c; }

    .btn-back {
        display:inline-flex; align-items:center; gap:6px;
        height:38px; padding:0 18px; font-size:13px; font-weight:500;
        color:#374151; background:#f9fafb; border:0.5px solid #e5e7eb; border-radius:8px;
        cursor:pointer; font-family:'Figtree',sans-serif; text-decoration:none;
    }
    .btn-back:hover { background:#f3f4f6; text-decoration:none; color:#111827; }

    .metodologi-note {
        display:flex; align-items:flex-start; gap:8px; padding:9px 13px;
        background:#fefce8; border:0.5px solid #fde68a; border-radius:8px;
        margin-bottom:16px; font-size:11.5px; color:#854d0e; line-height:1.6;
    }
    .metodologi-note svg { flex-shrink:0; margin-top:2px; }

    .result-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px; }
    @media (max-width: 900px) { .result-grid { grid-template-columns:1fr; } }

    .result-card {
        border-radius:10px; overflow:hidden;
        border:0.5px solid #e5e7eb;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .result-card-head {
        padding:10px 14px; font-size:13px; font-weight:600;
        display:flex; align-items:center; justify-content:space-between;
    }
    .rc-a .result-card-head { background:#fff7ed; color:#c2410c; border-bottom:0.5px solid #fed7aa; }
    .rc-b .result-card-head { background:#eff6ff; color:#1d4ed8; border-bottom:0.5px solid #bfdbfe; }

    .metric-cards-row { display:grid; grid-template-columns:repeat(4,1fr); gap:8px; padding:12px 14px; }
    @media (max-width:640px) { .metric-cards-row { grid-template-columns:repeat(2,1fr); } }

    .metric-mini-card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        position: relative; overflow:hidden;
    }
    .metric-mini-card::before {
        content:''; position:absolute; top:0; left:0; right:0; height:2px; border-radius:8px 8px 0 0;
    }
    .metric-mini-card.mc-orange::before { background:#f97316; }
    .metric-mini-card.mc-green::before  { background:#22c55e; }
    .metric-mini-card.mc-teal::before   { background:#14b8a6; }
    .metric-mini-card.mc-blue::before   { background:#3b82f6; }

    .metric-mini-label { font-size:9.5px; font-weight:500; color:#9ca3af; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:4px; }
    .metric-mini-value { font-size:13px; font-weight:700; color:#111827; line-height:1.2; }
    .metric-mini-value.up     { color:#16a34a; }
    .metric-mini-value.blue   { color:#2563eb; }
    .metric-mini-value.orange { color:#ea580c; }
    .metric-mini-value.teal   { color:#0d9488; }
    .metric-mini-sub { font-size:9px; color:#9ca3af; margin-top:2px; }

    .mult-strip {
        display:flex; align-items:center; gap:8px;
        padding:7px 14px; font-size:11.5px; font-weight:500; flex-wrap:wrap;
        border-top:0.5px solid;
    }
    .mult-strip-a { background:#fff7ed; border-color:#fed7aa; color:#92400e; }
    .mult-strip-b { background:#eff6ff; border-color:#bfdbfe; color:#1e40af; }
    .mult-strip strong { font-weight:700; }

    .diff-card {
        border:0.5px solid #e5e7eb; border-radius:10px; padding:0;
        margin-bottom:16px; background:#fff;
        display:grid; grid-template-columns:repeat(4,1fr);
        overflow:hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    @media (max-width:640px) { .diff-card { grid-template-columns:repeat(2,1fr); } }
    .diff-item {
        text-align:center; padding:16px 10px;
        border-right:0.5px solid #f3f4f6;
    }
    .diff-item:last-child { border-right:none; }
    .diff-label { font-size:10px; color:#9ca3af; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px; font-weight:500; }
    .diff-val { font-size:17px; font-weight:700; }
    .diff-val.pos { color:#16a34a; }
    .diff-val.neg { color:#dc2626; }
    .diff-val.neutral { color:#374151; }
    .diff-sub { font-size:10px; color:#9ca3af; margin-top:3px; }

    .cmp-table { width:100%; border-collapse:collapse; font-size:12.5px; min-width:760px; }
    .cmp-table thead th {
        font-size:10.5px; font-weight:500; text-transform:uppercase; letter-spacing:0.5px;
        color:#6b7280; padding:9px 12px; border-bottom:0.5px solid #e5e7eb;
        background:#f9fafb; white-space:nowrap; text-align:right;
    }
    .cmp-table thead th:first-child { text-align:left; }
    .cmp-table thead th.th-a     { background:#fff7ed; color:#c2410c; }
    .cmp-table thead th.th-a-ntb { background:#fff3e8; color:#b45309; }
    .cmp-table thead th.th-b     { background:#eff6ff; color:#1d4ed8; }
    .cmp-table thead th.th-b-ntb { background:#e8f1ff; color:#1d4ed8; opacity:.85; }
    .cmp-table thead th.th-diff  { background:#f5f3ff; color:#5b21b6; }
    .cmp-table thead th.th-diff-ntb { background:#f0fdfa; color:#0d9488; }
    .cmp-table tbody tr:hover td { background:#fafafa; }
    .cmp-table tbody tr:not(:last-child) td { border-bottom:0.5px solid #f3f4f6; }
    .cmp-table tbody td { padding:8px 12px; text-align:right; vertical-align:middle; color:#374151; }
    .cmp-table tbody td:first-child { text-align:left; font-weight:500; color:#111827; font-size:12px; }
    .cmp-table tbody td.td-ntb-a { background:#fff7ed; }
    .cmp-table tbody td.td-ntb-b { background:#eff6ff; }
    .td-mono { font-family:monospace; font-size:12px; }
    .diff-plus  { color:#16a34a; font-weight:600; font-family:monospace; font-size:11.5px; }
    .diff-minus { color:#dc2626; font-weight:600; font-family:monospace; font-size:11.5px; }
    .diff-zero  { color:#9ca3af; font-size:11.5px; }

    .chart-box { position:relative; width:100%; }
    .two-col-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    @media (max-width:900px) { .two-col-grid { grid-template-columns:1fr; gap:16px; } }

    .empty-state { padding:48px 20px; text-align:center; color:#9ca3af; }
    .empty-state svg { margin-bottom:12px; color:#e5e7eb; }
    .empty-state h6 { font-size:14px; font-weight:600; color:#374151; margin-bottom:4px; }
    .empty-state p { font-size:13px; margin:0; }

    .result-section-title {
        font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.6px;
        color:#9ca3af; margin:20px 0 10px; display:flex; align-items:center; gap:8px;
    }
    .result-section-title::after { content:''; flex:1; height:1px; background:#e5e7eb; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
        <div>
            <h3 class="fw-bold mb-1" style="font-family:'Figtree',sans-serif;">Komparasi Skenario</h3>
            <p style="font-size:13px; color:#9ca3af; margin:0;">Bandingkan dua skenario stimulus secara side-by-side.</p>
        </div>
        <a href="{{ route('simulasi.index') }}" class="btn-back">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('simulasi.proses-skenario') }}" method="POST" id="formSkenario">
        @csrf

        <div class="compare-grid" style="margin-bottom:16px;">

            <div class="sce-panel sce-a">
                <div class="sce-panel-head">
                    <span>Skenario A</span>
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div class="sce-panel-body">
                    <div class="form-group">
                        <label class="form-label">Nama Skenario A</label>
                        <input type="text" name="label_a" class="form-input" placeholder="Contoh: Stimulus Pertanian" value="Skenario A">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dataset IO</label>
                        <select name="dataset_id_a" class="form-select" onchange="loadSektorA()" required>
                            <option value="">— Pilih Dataset —</option>
                            @foreach($datasets as $row)
                                <option value="{{ $row->id }}" data-jumlah="{{ $row->jumlah_sektor }}">
                                    {{ $row->nama_dataset }} ({{ $row->tahun }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="height:1px; background:#f3f4f6; margin:12px 0;"></div>

                    <div id="wrapper-a">
                        <div class="sektor-block">
                            <div class="sektor-block-head">
                                <span class="sektor-block-label">Sektor 1</span>
                            </div>
                            <div class="form-group" style="margin-bottom:8px;">
                                <label class="form-label">Sektor</label>
                                <select name="sektor_a[]" class="form-select sektor-select-a" required>
                                    <option value="">— Pilih Sektor —</option>
                                    @foreach($sektors as $s)
                                        <option value="{{ $loop->iteration }}">{{ $s->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Nilai Stimulus</label>
                                <div class="input-with-suffix">
                                    <input type="number" min="0" step="0.001" name="nilai_a[]" class="form-input" placeholder="500" required>
                                    <span class="input-suffix">Miliar Rp</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-tambah" onclick="tambahSektorA()">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Tambah Sektor
                    </button>
                </div>
            </div>

            <div class="sce-panel sce-b">
                <div class="sce-panel-head">
                    <span>Skenario B</span>
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div class="sce-panel-body">
                    <div class="form-group">
                        <label class="form-label">Nama Skenario B</label>
                        <input type="text" name="label_b" class="form-input" placeholder="Contoh: Stimulus Industri" value="Skenario B">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dataset IO</label>
                        <select name="dataset_id_b" class="form-select" onchange="loadSektorB()" required>
                            <option value="">— Pilih Dataset —</option>
                            @foreach($datasets as $row)
                                <option value="{{ $row->id }}" data-jumlah="{{ $row->jumlah_sektor }}">
                                    {{ $row->nama_dataset }} ({{ $row->tahun }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="height:1px; background:#f3f4f6; margin:12px 0;"></div>

                    <div id="wrapper-b">
                        <div class="sektor-block">
                            <div class="sektor-block-head">
                                <span class="sektor-block-label">Sektor 1</span>
                            </div>
                            <div class="form-group" style="margin-bottom:8px;">
                                <label class="form-label">Sektor</label>
                                <select name="sektor_b[]" class="form-select sektor-select-b" required>
                                    <option value="">— Pilih Sektor —</option>
                                    @foreach($sektors as $s)
                                        <option value="{{ $loop->iteration }}">{{ $s->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Nilai Stimulus</label>
                                <div class="input-with-suffix">
                                    <input type="number" min="0" step="0.001" name="nilai_b[]" class="form-input" placeholder="500" required>
                                    <span class="input-suffix">Miliar Rp</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-tambah" onclick="tambahSektorB()">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Tambah Sektor
                    </button>
                </div>
            </div>

        </div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:8px;">

            <button type="submit" class="btn-hitung">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/>
                    <polyline points="16 7 22 7 22 13"/>
                </svg>
                Bandingkan Skenario
            </button>

            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                {{-- Tombol Export PDF — hanya tampil jika ada hasil --}}
                @if($hasil_a && $hasil_b && $summary_a && $summary_b)
                <a href="{{ route('simulasi.skenario.export.pdf') }}" target="_blank"
                   class="btn-back"
                   style="color:#991b1b; background:#fef2f2; border-color:#fca5a5;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Export PDF
                </a>
                @endif

                {{-- Tombol Reset --}}
                @if(session('skenario_a') || session('skenario_b'))
                <button type="button" id="btnResetSkenario"
                   style="display:inline-flex; align-items:center; gap:6px; height:38px; padding:0 16px; font-size:13px; font-weight:500; color:#991b1b; background:#fef2f2; border:0.5px solid #fca5a5; border-radius:8px; cursor:pointer; font-family:'Figtree',sans-serif; transition:background 0.12s;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="1 4 1 10 7 10"/>
                        <path d="M3.51 15a9 9 0 1 0 .49-3.5"/>
                    </svg>
                    Reset Komparasi
                </button>
                @endif
            </div>

        </div>

    </form>

    @if($hasil_a && $hasil_b && $summary_a && $summary_b)
    @php
        function fmtRp($val) {
            if (floor((float)$val) == (float)$val) {
                return number_format((float)$val, 0, ',', '.');
            }
            return rtrim(rtrim(number_format((float)$val, 3, ',', '.'), '0'), ',');
        }

        function fmtDiff($val) {
            $abs = abs((float)$val);
            if (floor($abs) == $abs) {
                return number_format($abs, 0, ',', '.');
            }
            return rtrim(rtrim(number_format($abs, 3, ',', '.'), '0'), ',');
        }

        $ntbA = $summary_a['tambahan_ntb']    ?? 0;
        $ntbB = $summary_b['tambahan_ntb']    ?? 0;
        $mNtbA = $summary_a['multiplier_ntb'] ?? 0;
        $mNtbB = $summary_b['multiplier_ntb'] ?? 0;
        $mOutA = $summary_a['multiplier_output'] ?? $summary_a['multiplier'] ?? 0;
        $mOutB = $summary_b['multiplier_output'] ?? $summary_b['multiplier'] ?? 0;

        $diffStimulus   = $summary_b['total_stimulus']    - $summary_a['total_stimulus'];
        $diffTambahan   = $summary_b['tambahan_output']   - $summary_a['tambahan_output'];
        $diffNtb        = $ntbB - $ntbA;
        $diffMultiplier = $mOutB - $mOutA;
        $diffMNtb       = $mNtbB - $mNtbA;
    @endphp

    <div class="metodologi-note">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            <strong>Catatan Metodologi:</strong>
            Dampak PDRB dihitung dari <strong>ΔNTB</strong>, bukan dari ΔOutput mentah.
        </div>
    </div>

    <div class="result-section-title">Ringkasan per Skenario</div>

    <div class="result-grid">

        <div class="result-card rc-a">
            <div class="result-card-head">
                <span>{{ $summary_a['label'] }}</span>
                <span style="font-size:11px; font-weight:400; color:#92400e;">{{ $summary_a['dataset'] }} ({{ $summary_a['tahun'] }})</span>
            </div>
            <div class="metric-cards-row">
                <div class="metric-mini-card mc-orange">
                    <div class="metric-mini-label">Total Stimulus</div>
                    <div class="metric-mini-value orange">{{ fmtRp($summary_a['total_stimulus']) }}</div>
                    <div class="metric-mini-sub">Miliar Rp</div>
                </div>
                <div class="metric-mini-card mc-green">
                    <div class="metric-mini-label">ΔX Output</div>
                    <div class="metric-mini-value up">+{{ fmtRp($summary_a['tambahan_output']) }}</div>
                    <div class="metric-mini-sub">Miliar Rp</div>
                </div>
                <div class="metric-mini-card mc-teal">
                    <div class="metric-mini-label">ΔNTB</div>
                    <div class="metric-mini-value teal">+{{ fmtRp($ntbA) }}</div>
                    <div class="metric-mini-sub">Miliar Rp · dasar PDRB</div>
                </div>
                <div class="metric-mini-card mc-blue">
                    <div class="metric-mini-label">Multiplier</div>
                    <div class="metric-mini-value blue">{{ number_format($mOutA, 4) }}×</div>
                    <div class="metric-mini-sub">
                        @if($mNtbA > 0)NTB: {{ number_format($mNtbA, 4) }}×@else Output/Stimulus @endif
                    </div>
                </div>
            </div>
            <div class="mult-strip mult-strip-a">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                Output Multiplier: <strong>{{ number_format($mOutA, 4) }}×</strong>
                @if($mNtbA > 0)
                    &nbsp;·&nbsp; NTB Multiplier: <strong>{{ number_format($mNtbA, 4) }}×</strong>
                @endif
            </div>
        </div>

        <div class="result-card rc-b">
            <div class="result-card-head">
                <span>{{ $summary_b['label'] }}</span>
                <span style="font-size:11px; font-weight:400; color:#1e40af;">{{ $summary_b['dataset'] }} ({{ $summary_b['tahun'] }})</span>
            </div>
            <div class="metric-cards-row">
                <div class="metric-mini-card mc-orange">
                    <div class="metric-mini-label">Total Stimulus</div>
                    <div class="metric-mini-value" style="color:#1d4ed8;">{{ fmtRp($summary_b['total_stimulus']) }}</div>
                    <div class="metric-mini-sub">Miliar Rp</div>
                </div>
                <div class="metric-mini-card mc-green">
                    <div class="metric-mini-label">ΔX Output</div>
                    <div class="metric-mini-value up">+{{ fmtRp($summary_b['tambahan_output']) }}</div>
                    <div class="metric-mini-sub">Miliar Rp</div>
                </div>
                <div class="metric-mini-card mc-teal">
                    <div class="metric-mini-label">ΔNTB</div>
                    <div class="metric-mini-value teal">+{{ fmtRp($ntbB) }}</div>
                    <div class="metric-mini-sub">Miliar Rp · dasar PDRB</div>
                </div>
                <div class="metric-mini-card mc-blue">
                    <div class="metric-mini-label">Multiplier</div>
                    <div class="metric-mini-value blue">{{ number_format($mOutB, 4) }}×</div>
                    <div class="metric-mini-sub">
                        @if($mNtbB > 0)NTB: {{ number_format($mNtbB, 4) }}×@else Output/Stimulus @endif
                    </div>
                </div>
            </div>
            <div class="mult-strip mult-strip-b">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                Output Multiplier: <strong>{{ number_format($mOutB, 4) }}×</strong>
                @if($mNtbB > 0)
                    &nbsp;·&nbsp; NTB Multiplier: <strong>{{ number_format($mNtbB, 4) }}×</strong>
                @endif
            </div>
        </div>

    </div>

    <div class="result-section-title">Selisih (B − A)</div>
    <div class="diff-card">
        <div class="diff-item">
            <div class="diff-label">Δ Total Stimulus</div>
            <div class="diff-val {{ $diffStimulus >= 0 ? 'pos' : 'neg' }}">
                {{ $diffStimulus >= 0 ? '+' : '-' }}{{ fmtDiff($diffStimulus) }}
            </div>
            <div class="diff-sub">Miliar Rp</div>
        </div>
        <div class="diff-item">
            <div class="diff-label">Δ ΔX Output</div>
            <div class="diff-val {{ $diffTambahan >= 0 ? 'pos' : 'neg' }}">
                {{ $diffTambahan >= 0 ? '+' : '-' }}{{ fmtDiff($diffTambahan) }}
            </div>
            <div class="diff-sub">Miliar Rp</div>
        </div>
        <div class="diff-item" style="background:#f0fdfa;">
            <div class="diff-label" style="color:#0d9488;">Δ ΔNTB</div>
            <div class="diff-val {{ $diffNtb >= 0 ? 'pos' : 'neg' }}">
                {{ $diffNtb >= 0 ? '+' : '-' }}{{ fmtDiff($diffNtb) }}
            </div>
            <div class="diff-sub">Miliar Rp · dampak PDRB</div>
        </div>
        <div class="diff-item">
            <div class="diff-label">Δ Multiplier ΔX</div>
            <div class="diff-val {{ $diffMultiplier >= 0 ? 'pos' : 'neg' }}">
                {{ $diffMultiplier >= 0 ? '+' : '' }}{{ number_format($diffMultiplier, 4, ',', '.') }}×
            </div>
            <div class="diff-sub">B lebih {{ $diffMultiplier >= 0 ? 'efisien' : 'rendah' }}</div>
        </div>
    </div>

    <div class="result-section-title">Tabel Detail per Sektor</div>
    <div class="card" style="margin-bottom:16px;">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
                <span class="card-head-title">Perbandingan Dampak per Sektor</span>
            </div>
            <span style="font-size:11px; padding:2px 8px; background:#f5f3ff; border:0.5px solid #c4b5fd; border-radius:100px; color:#5b21b6; font-weight:500;">Nilai dalam Miliar Rp</span>
        </div>
        <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
            <table class="cmp-table">
                <thead>
                    <tr>
                        <th>Sektor</th>
                        <th class="th-a">{{ $summary_a['label'] }}<br><span style="font-weight:400;font-size:9px;text-transform:none;">ΔX Output</span></th>
                        <th class="th-a-ntb">{{ $summary_a['label'] }}<br><span style="font-weight:400;font-size:9px;text-transform:none;">ΔNTB</span></th>
                        <th class="th-b">{{ $summary_b['label'] }}<br><span style="font-weight:400;font-size:9px;text-transform:none;">ΔX Output</span></th>
                        <th class="th-b-ntb">{{ $summary_b['label'] }}<br><span style="font-weight:400;font-size:9px;text-transform:none;">ΔNTB</span></th>
                        <th class="th-diff">Selisih ΔX<br><span style="font-weight:400;font-size:9px;text-transform:none;">(B − A)</span></th>
                        <th class="th-diff-ntb">Selisih ΔNTB<br><span style="font-weight:400;font-size:9px;text-transform:none;">(B − A)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @php $hasilBBySektor = collect($hasil_b)->keyBy('sektor'); @endphp
                    @foreach($hasil_a as $row)
                    @php
                        $rb       = $hasilBBySektor[$row['sektor']] ?? null;
                        $dxA      = $row['tambahan_output'];
                        $ntbRowA  = $row['tambahan_ntb']  ?? 0;
                        $dxB      = $rb ? $rb['tambahan_output'] : 0;
                        $ntbRowB  = $rb ? ($rb['tambahan_ntb'] ?? 0) : 0;
                        $diffDx   = $dxB - $dxA;
                        $diffNtbR = $ntbRowB - $ntbRowA;
                    @endphp
                    <tr>
                        <td>{{ $row['nama'] }}</td>
                        <td class="td-mono">{{ fmtRp($dxA) }}</td>
                        <td class="td-mono td-ntb-a">{{ fmtRp($ntbRowA) }}</td>
                        <td class="td-mono">{{ $rb ? fmtRp($dxB) : '—' }}</td>
                        <td class="td-mono td-ntb-b">{{ $rb ? fmtRp($ntbRowB) : '—' }}</td>
                        <td>
                            @if(abs($diffDx) < 0.001)
                                <span class="diff-zero">≈ 0</span>
                            @elseif($diffDx > 0)
                                <span class="diff-plus">+{{ fmtDiff($diffDx) }}</span>
                            @else
                                <span class="diff-minus">-{{ fmtDiff($diffDx) }}</span>
                            @endif
                        </td>
                        <td>
                            @if(abs($diffNtbR) < 0.001)
                                <span class="diff-zero">≈ 0</span>
                            @elseif($diffNtbR > 0)
                                <span class="diff-plus" style="color:#0d9488;">+{{ fmtDiff($diffNtbR) }}</span>
                            @else
                                <span class="diff-minus">-{{ fmtDiff($diffNtbR) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap; padding:9px 14px; background:#f9fafb; border-top:0.5px solid #e5e7eb; font-size:11px; color:#6b7280;">
            <div style="display:flex; align-items:center; gap:5px;">
                <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;flex-shrink:0;"></span>
                <span><strong>ΔX</strong> — Tambahan output bruto</span>
            </div>
            <div style="display:flex; align-items:center; gap:5px;">
                <span style="width:8px;height:8px;border-radius:50%;background:#14b8a6;flex-shrink:0;"></span>
                <span><strong>ΔNTB</strong> — ΔX × (1 − rasio biaya antara) → dasar dampak PDRB</span>
            </div>
        </div>
    </div>

    <div class="result-section-title">Visualisasi Perbandingan</div>
    <div class="card" style="margin-bottom:0;">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <span class="card-head-title">Perbandingan per Sektor</span>
            </div>
            <span style="font-size:11px; color:#6b7280;">Miliar Rupiah</span>
        </div>
        <div class="card-body">
            <div class="two-col-grid">
                <div>
                    <div style="font-size:12px; font-weight:600; color:#374151; margin-bottom:10px;">Tambahan Output ΔX</div>
                    <div class="chart-box" style="height:{{ max(200, count($hasil_a) * 32 + 60) }}px;">
                        <canvas id="chartCompare"></canvas>
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; font-weight:600; color:#374151; margin-bottom:10px;">Tambahan NTB ΔNTB</div>
                    <div class="chart-box" style="height:{{ max(200, count($hasil_a) * 32 + 60) }}px;">
                        <canvas id="chartCompareNtb"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else

    <div class="card" style="margin-bottom:0;">
        <div class="empty-state">
            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                <path d="M18 20V10M12 20V4M6 20v-6"/>
            </svg>
            <h6>Belum Ada Hasil Komparasi</h6>
            <p>Isi kedua skenario di atas lalu klik <strong>Bandingkan Skenario</strong>.</p>
        </div>
    </div>

    @endif

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
@if(session('success'))
Swal.fire({ icon:'success', title:'Berhasil', text:'{{ session("success") }}', timer:2200, showConfirmButton:false });
@endif
@if(session('error'))
Swal.fire({ icon:'error', title:'Gagal', text:'{{ session("error") }}', confirmButtonColor:'#f97316' });
@endif

@if(session('error_pdrb'))
Swal.fire({
    icon: 'warning',
    title: 'PDRB Belum Difinalisasi',
    text: '{{ session("error_pdrb") }}',
    confirmButtonColor: '#f97316',
    confirmButtonText: 'Mengerti',
});
@endif

const sektorData = @json($sektors->values()->map(fn($s, $i) => ['id' => $i+1, 'nama' => $s->nama]));

Chart.defaults.font.family = "'Figtree', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#6b7280';

const btnReset = document.getElementById('btnResetSkenario');
if (btnReset) {
    btnReset.addEventListener('click', function () {
        Swal.fire({
            title: 'Reset komparasi?',
            text: 'Hasil kedua skenario akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, reset',
            cancelButtonText: 'Batal',
        }).then(result => {
            if (result.isConfirmed) window.location.href = "{{ route('simulasi.reset-skenario') }}";
        });
    });
}

function makeSektorOpts(selected = '') {
    let o = '<option value="">— Pilih Sektor —</option>';
    sektorData.forEach(s => { o += `<option value="${s.id}" ${selected == s.id ? 'selected' : ''}>${s.nama}</option>`; });
    return o;
}

function tambahSektorA() {
    const wrap = document.getElementById('wrapper-a');
    const idx  = wrap.querySelectorAll('.sektor-block').length + 1;
    wrap.insertAdjacentHTML('beforeend', `
        <div class="sektor-block posisi-a">
            <div class="sektor-block-head">
                <span class="sektor-block-label">Sektor ${idx}</span>
                <button type="button" class="btn-hapus-sektor" onclick="this.closest('.posisi-a').remove()">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="form-group" style="margin-bottom:8px;">
                <label class="form-label">Sektor</label>
                <select name="sektor_a[]" class="form-select sektor-select-a" required>${makeSektorOpts()}</select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Nilai Stimulus</label>
                <div class="input-with-suffix">
                    <input type="number" min="0" step="0.001" name="nilai_a[]" class="form-input" placeholder="500" required>
                    <span class="input-suffix">Miliar Rp</span>
                </div>
            </div>
        </div>`);
}

function tambahSektorB() {
    const wrap = document.getElementById('wrapper-b');
    const idx  = wrap.querySelectorAll('.sektor-block').length + 1;
    wrap.insertAdjacentHTML('beforeend', `
        <div class="sektor-block posisi-b">
            <div class="sektor-block-head">
                <span class="sektor-block-label">Sektor ${idx}</span>
                <button type="button" class="btn-hapus-sektor" onclick="this.closest('.posisi-b').remove()">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="form-group" style="margin-bottom:8px;">
                <label class="form-label">Sektor</label>
                <select name="sektor_b[]" class="form-select sektor-select-b" required>${makeSektorOpts()}</select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Nilai Stimulus</label>
                <div class="input-with-suffix">
                    <input type="number" min="0" step="0.001" name="nilai_b[]" class="form-input" placeholder="500" required>
                    <span class="input-suffix">Miliar Rp</span>
                </div>
            </div>
        </div>`);
}

function loadSektorA() {
    document.querySelectorAll('.sektor-select-a').forEach(el => { el.innerHTML = makeSektorOpts(el.value); });
}
function loadSektorB() {
    document.querySelectorAll('.sektor-select-b').forEach(el => { el.innerHTML = makeSektorOpts(el.value); });
}

@if($hasil_a && $hasil_b)
const HASIL_A = @json($hasil_a);
const HASIL_B = @json($hasil_b);
const LABEL_A = '{{ addslashes($summary_a["label"] ?? "Skenario A") }}';
const LABEL_B = '{{ addslashes($summary_b["label"] ?? "Skenario B") }}';

function mkGroupedBar(canvasId, dataA, dataB, labelA, labelB, colorA, colorB) {
    const labels = HASIL_A.map(r => r.nama.length > 22 ? r.nama.substr(0,20)+'…' : r.nama);
    new Chart(document.getElementById(canvasId), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: labelA, data: dataA, backgroundColor: colorA, borderRadius: 3 },
                { label: labelB, data: dataB, backgroundColor: colorB, borderRadius: 3 },
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position:'top', labels:{ boxWidth:10, padding:12, font:{size:11} } },
                tooltip: { callbacks: { label: ctx => ` ${ctx.raw.toFixed(3)} Miliar Rp` } }
            },
            scales: {
                x: { grid:{ color:'#f3f4f6' }, ticks:{ callback: v => v.toFixed(1) } },
                y: { grid:{ display:false }, ticks:{ font:{size:10} } }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    mkGroupedBar(
        'chartCompare',
        HASIL_A.map(r => parseFloat((r.tambahan_output || 0).toFixed(3))),
        HASIL_B.map(r => parseFloat((r.tambahan_output || 0).toFixed(3))),
        LABEL_A, LABEL_B,
        'rgba(249,115,22,0.75)',
        'rgba(59,130,246,0.75)'
    );

    mkGroupedBar(
        'chartCompareNtb',
        HASIL_A.map(r => parseFloat((r.tambahan_ntb || 0).toFixed(3))),
        HASIL_B.map(r => parseFloat((r.tambahan_ntb || 0).toFixed(3))),
        LABEL_A, LABEL_B,
        'rgba(245,158,11,0.75)',
        'rgba(20,184,166,0.75)'
    );
});
@endif
</script>
@endsection