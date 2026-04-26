@extends('layouts.app')

@section('title', 'Simulasi Input Output')

@section('content')

<style>
    * { box-sizing: border-box; }
    body { background: #f7f6f3; }
    .page { padding: 24px 24px 48px; padding-top: 80px; font-family: 'Figtree', sans-serif; }

    /* Card */
    .card { background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; overflow:hidden; margin-bottom:16px; }
    .card-head {
        display:flex; align-items:center; justify-content:space-between;
        padding:14px 20px; border-bottom:0.5px solid #e5e7eb; background:#fafafa; gap:8px; flex-wrap:wrap;
    }
    .card-head-left { display:flex; align-items:center; gap:8px; }
    .card-head-icon { color:#9ca3af; }
    .card-head-title { font-size:14px; font-weight:500; color:#111827; }
    .card-body { padding:20px; }

    /* Layout grid */
    .sim-grid { display:grid; grid-template-columns:360px 1fr; gap:16px; align-items:start; }

    /* Form */
    .form-group { margin-bottom:14px; }
    .form-label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:5px; }
    .form-select, .form-input {
        width:100%; height:38px; padding:0 12px; font-size:13px;
        font-family:'Figtree',sans-serif; color:#374151;
        background:#fff; border:0.5px solid #d1d5db; border-radius:8px; outline:none;
        transition:border-color 0.15s, box-shadow 0.15s;
    }
    .form-select:focus,.form-input:focus { border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.08); }

    .info-box {
        display:flex; align-items:flex-start; gap:8px; padding:10px 14px;
        border-radius:8px; margin-bottom:12px; font-size:12px; line-height:1.55;
    }
    .info-box svg { flex-shrink:0; margin-top:1px; }
    .info-box.blue  { background:#eff6ff; border:0.5px solid #bfdbfe; color:#1d4ed8; }
    .info-box.purple { background:#f5f3ff; border:0.5px solid #c4b5fd; color:#5b21b6; }

    .sektor-block { border:0.5px solid #e5e7eb; border-radius:10px; padding:14px; margin-bottom:10px; background:#fafafa; }
    .sektor-block-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .sektor-block-label { font-size:11px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; }
    .btn-hapus-sektor {
        display:inline-flex; align-items:center; justify-content:center;
        width:24px; height:24px; border-radius:6px;
        border:0.5px solid #fca5a5; background:#fef2f2; color:#dc2626; cursor:pointer;
    }
    .btn-hapus-sektor:hover { background:#fee2e2; }

    .input-with-suffix { position:relative; }
    .input-with-suffix .form-input { padding-right:78px; }
    .input-suffix {
        position:absolute; right:10px; top:50%; transform:translateY(-50%);
        font-size:11px; font-weight:500; color:#9ca3af;
        background:#f3f4f6; border:0.5px solid #e5e7eb; border-radius:4px;
        padding:2px 6px; pointer-events:none; white-space:nowrap;
    }

    /* Buttons */
    .btn-tambah-sektor {
        display:inline-flex; align-items:center; gap:6px;
        height:34px; padding:0 14px; font-size:12px; font-weight:500;
        color:#374151; background:#f3f4f6; border:0.5px solid #e5e7eb; border-radius:8px;
        cursor:pointer; font-family:'Figtree',sans-serif; margin-bottom:16px; transition:background 0.12s;
    }
    .btn-tambah-sektor:hover { background:#e5e7eb; }
    .btn-simulasi {
        display:inline-flex; align-items:center; gap:6px;
        height:38px; padding:0 20px; font-size:13px; font-weight:500;
        color:#fff; background:#f97316; border:none; border-radius:8px;
        cursor:pointer; font-family:'Figtree',sans-serif; transition:background 0.15s;
    }
    .btn-simulasi:hover { background:#ea580c; }
    .btn-reset {
        display:inline-flex; align-items:center; gap:6px;
        height:38px; padding:0 16px; font-size:13px; font-weight:500;
        color:#991b1b; background:#fef2f2; border:0.5px solid #fca5a5; border-radius:8px;
        cursor:pointer; font-family:'Figtree',sans-serif; transition:background 0.12s;
    }
    .btn-reset:hover { background:#fee2e2; }
    .btn-outline {
        display:inline-flex; align-items:center; gap:5px;
        height:32px; padding:0 14px; font-size:12px; font-weight:500;
        border-radius:7px; cursor:pointer; font-family:'Figtree',sans-serif;
        transition:opacity 0.12s; text-decoration:none;
    }
    .btn-outline:hover { opacity:0.8; text-decoration:none; }
    .btn-excel  { color:#166534; background:#f0fdf4; border:0.5px solid #86efac; }
    .btn-pdf    { color:#991b1b; background:#fef2f2; border:0.5px solid #fca5a5; }
    .btn-save   { color:#1d4ed8; background:#eff6ff; border:0.5px solid #bfdbfe; }
    .btn-kompar { color:#5b21b6; background:#f5f3ff; border:0.5px solid #c4b5fd; }
    .form-actions { display:flex; gap:8px; justify-content:flex-end; margin-top:4px; }

    /* Multiplier Strip */
    .multiplier-strip {
        display:flex; align-items:center; gap:8px; padding:10px 16px;
        background:#f0fdf4; border:0.5px solid #86efac; border-radius:8px;
        margin-bottom:12px; font-size:12px; color:#166534; flex-wrap:wrap;
    }
    .multiplier-strip strong { font-weight:700; font-size:14px; }

    /* Metodologi Note */
    .metodologi-note {
        display:flex; align-items:flex-start; gap:8px; padding:9px 13px;
        background:#fefce8; border:0.5px solid #fde68a; border-radius:8px;
        margin-bottom:14px; font-size:11.5px; color:#854d0e; line-height:1.6;
    }
    .metodologi-note svg { flex-shrink:0; margin-top:2px; }

    /* Summary Cards 2×3 */
    .summary-grid-6 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .sc {
        position:relative; overflow:hidden;
        border-radius:10px; padding:14px 15px;
        display:flex; flex-direction:column; gap:5px;
        background:#fff; border:0.5px solid #e5e7eb;
        box-shadow:0 1px 3px rgba(0,0,0,0.04);
    }
    .sc::before {
        content:''; position:absolute; top:0; left:0; right:0; height:3px;
        border-radius:10px 10px 0 0;
    }
    /* accent colours */
    .sc-orange::before { background:#f97316; }
    .sc-blue::before   { background:#3b82f6; }
    .sc-green::before  { background:#22c55e; }
    .sc-teal::before   { background:#14b8a6; }
    .sc-purple::before { background:#8b5cf6; }
    .sc-emerald::before{ background:#10b981; }

    /* divider between ADHB row and ADHK row */
    .sc-divider {
        grid-column: 1 / -1;
        height:1px; background:#e5e7eb;
        margin: 2px 0;
    }
    .sc-row-label {
        grid-column: 1 / -1;
        font-size:10px; font-weight:600; text-transform:uppercase;
        letter-spacing:0.6px; color:#9ca3af;
        display:flex; align-items:center; gap:8px; margin-bottom:-4px;
    }
    .sc-row-label::after { content:''; flex:1; height:1px; background:#e5e7eb; }

    .sc-label { font-size:10px; font-weight:500; color:#9ca3af; text-transform:uppercase; letter-spacing:0.5px; }
    .sc-val   { font-size:14px; font-weight:700; line-height:1.2; letter-spacing:-0.3px; color:#111827; }
    .sc-val.orange { color:#ea580c; }
    .sc-val.blue   { color:#2563eb; }
    .sc-val.green  { color:#16a34a; }
    .sc-val.teal   { color:#0d9488; }
    .sc-val.purple { color:#7c3aed; }
    .sc-val.emerald{ color:#059669; }
    .sc-sub  { font-size:10px; color:#9ca3af; }

    /* Tabs */
    .tabs {
        display:flex; gap:4px; padding:14px 20px 0;
        border-bottom:0.5px solid #e5e7eb; background:#fafafa; overflow-x:auto;
    }
    .tab-btn {
        display:inline-flex; align-items:center; gap:5px; padding:8px 14px;
        font-size:12px; font-weight:500; color:#6b7280; background:none;
        border:none; border-bottom:2px solid transparent; cursor:pointer;
        font-family:'Figtree',sans-serif; white-space:nowrap; transition:color 0.15s;
    }
    .tab-btn:hover { color:#374151; }
    .tab-btn.active { color:#f97316; border-bottom-color:#f97316; font-weight:600; }
    .tab-panel { display:none; padding:20px; }
    .tab-panel.active { display:block; }

    /* Table */
    .sim-table { width:100%; border-collapse:collapse; font-size:13px; }
    .sim-table thead th {
        font-size:11px; font-weight:500; text-transform:uppercase; letter-spacing:0.5px;
        color:#6b7280; padding:10px 14px; border-bottom:0.5px solid #e5e7eb;
        background:#f9fafb; white-space:nowrap; text-align:center;
    }
    .sim-table thead th.th-left { text-align:left; }
    .sim-table thead th.th-ntb  { background:#f0fdfa; color:#0d9488; }
    .sim-table tbody tr:hover td { background:#fafafa; }
    .sim-table tbody tr:not(:last-child) td { border-bottom:0.5px solid #f0f0f0; }
    .sim-table tbody td { padding:10px 14px; color:#374151; font-size:13px; text-align:right; vertical-align:middle; }
    .sim-table tbody td.td-no   { color:#9ca3af; text-align:center; width:36px; }
    .sim-table tbody td.td-left { text-align:left; font-weight:500; color:#111827; }
    .sim-table tbody td.td-num  { font-family:monospace; font-size:12.5px; }
    .sim-table tbody td.td-ntb  { background:#f0fdfa; }

    .badge-output {
        display:inline-flex; align-items:center; font-size:11.5px; font-weight:600;
        color:#166534; background:#f0fdf4; border:0.5px solid #86efac;
        border-radius:100px; padding:2px 10px; white-space:nowrap; font-family:monospace;
    }
    .badge-ntb {
        display:inline-flex; align-items:center; font-size:11.5px; font-weight:600;
        color:#0d9488; background:#f0fdfa; border:0.5px solid #99f6e4;
        border-radius:100px; padding:2px 10px; white-space:nowrap; font-family:monospace;
    }
    .pct-badge { display:inline-block; font-size:12px; font-weight:600; padding:2px 8px; border-radius:6px; font-family:monospace; }
    .pct-low  { color:#374151; background:#f3f4f6; }
    .pct-mid  { color:#b45309; background:#fefce8; }
    .pct-high { color:#991b1b; background:#fef2f2; }

    .klas-badge { display:inline-block; font-size:11px; font-weight:600; padding:2px 9px; border-radius:100px; }
    .klas-kunci      { background:#fef9c3; color:#854d0e; border:0.5px solid #fde68a; }
    .klas-hilir      { background:#eff6ff; color:#1d4ed8; border:0.5px solid #bfdbfe; }
    .klas-hulu       { background:#f0fdf4; color:#166534; border:0.5px solid #86efac; }
    .klas-independen { background:#f3f4f6; color:#6b7280; border:0.5px solid #d1d5db; }

    /* Linkage bars */
    .link-bar-wrap { display:flex; align-items:center; gap:6px; }
    .link-bar-track { flex:1; height:6px; border-radius:3px; background:#f3f4f6; overflow:hidden; min-width:60px; }
    .link-bar-fill { height:100%; border-radius:3px; transition:width 0.5s ease; }
    .link-bar-fill.back { background:#f97316; }
    .link-bar-fill.fwd  { background:#3b82f6; }
    .link-val { font-size:11.5px; font-weight:600; color:#374151; min-width:36px; text-align:right; font-family:monospace; }

    /* Chart */
    .chart-box { position:relative; width:100%; }

    /* Scatter quadrant */
    .quadrant-legend { display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-top:12px; }
    .q-item { display:flex; align-items:center; gap:6px; padding:6px 10px; border-radius:6px; font-size:11px; }
    .q-kunci { background:#fef9c3; color:#854d0e; }
    .q-hilir { background:#eff6ff; color:#1d4ed8; }
    .q-hulu  { background:#f0fdf4; color:#166534; }
    .q-independen { background:#f3f4f6; color:#6b7280; }
    .q-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; }

    /* Empty state */
    .empty-state { padding:56px 20px; text-align:center; color:#9ca3af; }
    .empty-state svg { margin-bottom:14px; color:#e5e7eb; }
    .empty-state h6 { font-size:14px; font-weight:600; color:#374151; margin-bottom:4px; }
    .empty-state p  { font-size:13px; margin:0; }

    /* Modal */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.35); z-index:1000; align-items:center; justify-content:center; padding:16px; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:#fff; border-radius:12px; border:0.5px solid #e5e7eb; width:100%; max-width:420px; overflow:hidden; box-shadow:0 8px 32px rgba(0,0,0,0.12); }
    .modal-head { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:0.5px solid #e5e7eb; background:#fafafa; }
    .modal-title { font-size:14px; font-weight:600; color:#111827; }
    .modal-close { display:flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:6px; border:none; background:none; cursor:pointer; color:#9ca3af; }
    .modal-close:hover { background:#f3f4f6; color:#374151; }
    .modal-body { padding:20px; }
    .modal-foot { display:flex; align-items:center; justify-content:flex-end; gap:8px; padding:12px 20px; border-top:0.5px solid #e5e7eb; background:#fafafa; }
    .btn-cancel { height:36px; padding:0 16px; font-size:13px; font-weight:500; color:#374151; background:#fff; border:0.5px solid #d1d5db; border-radius:8px; cursor:pointer; font-family:'Figtree',sans-serif; }
    .btn-cancel:hover { background:#f9fafb; }
    .btn-submit { display:inline-flex; align-items:center; gap:6px; height:36px; padding:0 18px; font-size:13px; font-weight:500; color:#fff; background:#3b82f6; border:0.5px solid #2563eb; border-radius:8px; cursor:pointer; font-family:'Figtree',sans-serif; }
    .btn-submit:hover { background:#2563eb; }

    /* Responsive */
    html, body, .page { overflow-x:hidden; }

    @media (max-width:1100px) {
        .sim-grid { grid-template-columns:320px 1fr; }
    }
    @media (max-width:900px) {
        .sim-grid { grid-template-columns:1fr; }
        .summary-grid-6 { grid-template-columns:repeat(2,1fr); }
    }
    @media (max-width:640px) {
        .page { padding:12px !important; padding-top:72px !important; }
        .summary-grid-6 { grid-template-columns:repeat(2,1fr); gap:8px; }
        .sc-val { font-size:12px !important; }
        .sc-row-label { font-size:9px; }
        .form-actions { flex-direction:column; }
        .form-actions .btn-reset,
        .form-actions .btn-simulasi { width:100%; justify-content:center; min-height:44px; }
        .form-select, .form-input { font-size:16px !important; height:44px !important; }
        .btn-outline { flex:1 1 calc(50% - 4px); justify-content:center; }
        .tab-btn { padding:7px 8px; font-size:11px; }
        .tab-panel { padding:12px; }
        .multiplier-strip .hint-text { display:none; }
        .btn-tambah-sektor { width:100%; justify-content:center; }
        .tabs-chart-grid { grid-template-columns:1fr !important; }
        .tabs-linkage-grid { grid-template-columns:1fr !important; }
    }
    @media (max-width:420px) {
        .summary-grid-6 { grid-template-columns:1fr; }
    }

    /* table horizontal scroll wrapper */
    .table-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <!-- Page header -->
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
        <div>
            <h3 class="fw-bold mb-1" style="font-family:'Figtree',sans-serif;">Simulasi Input Output</h3>
            <p style="font-size:13px;color:#9ca3af;margin:0;">Simulasikan dampak stimulus ekonomi antar sektor (Model Leontief).</p>
        </div>
        <a href="{{ route('simulasi.skenario') }}" class="btn-outline btn-kompar">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
            Komparasi Skenario
        </a>
    </div>

    <div class="sim-grid">

        <!-- KOLOM KIRI: FORM -->
        <div>
            <div class="card">
                <div class="card-head">
                    <div class="card-head-left">
                        <svg class="card-head-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        <span class="card-head-title">Input Simulasi</span>
                    </div>
                </div>
                <div class="card-body">

                    @if($pdrbInfo)
                    <div class="info-box purple">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-top:1px;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                        <div>
                            <strong>PDRB Referensi:</strong> {{ $pdrbInfo['tahun'] }} — {{ $pdrbInfo['triwulan'] }}<br>
                            <span style="font-size:11px;">
                                ADHB: <strong>Rp {{ number_format($pdrbInfo['total_adhb'],3,',','.') }} M</strong>
                                &nbsp;·&nbsp;
                                ADHK: <strong>Rp {{ number_format($pdrbInfo['total_adhk'],3,',','.') }} M</strong>
                            </span>
                        </div>
                    </div>
                    @endif

                    <div class="info-box blue">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <div><strong>Satuan: Miliar Rupiah.</strong><br>Contoh: Rp 500 miliar → isi <strong>500</strong>.</div>
                    </div>

                    <form action="{{ route('simulasi.proses') }}" method="POST" id="formSimulasi">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Dataset IO</label>
                            <select name="dataset_id" id="dataset_id" class="form-select" onchange="loadSektor()" required>
                                <option value="">— Pilih Dataset —</option>
                                @foreach($datasets as $d)
                                    <option value="{{ $d->id }}" data-jumlah="{{ $d->jumlah_sektor }}"
                                        {{ session('simulasi_input.dataset_id') == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama_dataset }} ({{ $d->tahun }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="height:1px;background:#f3f4f6;margin:14px 0;"></div>

                        <div id="wrapper-sektor">
                            @php
                                $savedSektor = session('simulasi_input.sektor', [null]);
                                $savedNilai  = session('simulasi_input.nilai',  [null]);
                            @endphp
                            @foreach($savedSektor as $idx => $savedS)
                            <div class="sektor-block {{ $idx > 0 ? 'posisi' : '' }}">
                                <div class="sektor-block-head">
                                    <span class="sektor-block-label">Sektor {{ $idx + 1 }}</span>
                                    @if($idx > 0)
                                    <button type="button" class="btn-hapus-sektor" onclick="hapusBaris(this)">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                    @endif
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Sektor</label>
                                    <select name="sektor[]" class="form-select sektor-select" required>
                                        <option value="">— Pilih Sektor —</option>
                                        @foreach($sektors as $s)
                                            <option value="{{ $loop->iteration }}" {{ $savedS == $loop->iteration ? 'selected' : '' }}>{{ $s->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label class="form-label">Nilai Stimulus</label>
                                    <div class="input-with-suffix">
                                        <input type="number" min="0" step="0.001" name="nilai[]" class="form-input"
                                            placeholder="Contoh: 500" value="{{ $savedNilai[$idx] ?? '' }}" required>
                                        <span class="input-suffix">Miliar Rp</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn-tambah-sektor" onclick="tambahBaris()">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Sektor
                        </button>

                        <div class="form-actions">
                            <button type="button" onclick="resetSimulasi()" class="btn-reset">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                                Reset
                            </button>
                            <button type="submit" class="btn-simulasi">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                Simulasikan
                            </button>
                        </div>
                    </form>

                    <script>
                        const sektorData = @json($sektors->values()->map(fn($s,$i) => ['id'=>$i+1,'nama'=>$s->nama]));
                    </script>
                </div>
            </div>

            <!-- Riwayat singkat -->
            @if($riwayat_singkat->count() > 0)
            <div class="card" style="margin-bottom:0;">
                <div class="card-head">
                    <div class="card-head-left">
                        <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span class="card-head-title">Riwayat Terakhir</span>
                    </div>
                    <a href="{{ route('simulasi.riwayat') }}" style="font-size:11px;color:#f97316;text-decoration:none;font-weight:500;">Lihat Semua →</a>
                </div>
                <div class="card-body" style="padding:10px 12px;">
                    @foreach($riwayat_singkat as $r)
                    @php $rs = json_decode($r->summary, true); @endphp
                    <a href="{{ route('simulasi.lihat-riwayat', $r->id) }}"
                        style="display:flex;align-items:center;justify-content:space-between;padding:8px 10px;border-radius:8px;text-decoration:none;color:#374151;transition:background 0.1s;"
                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                        <div>
                            <div style="font-size:12px;font-weight:500;color:#111827;">{{ $r->nama_simulasi }}</div>
                            <div style="font-size:10.5px;color:#9ca3af;">{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d M Y H:i') : '-' }}</div>
                        </div>
                        <span style="font-size:11px;font-weight:600;color:#16a34a;white-space:nowrap;">
                            {{ isset($rs['multiplier_output']) ? number_format($rs['multiplier_output'],2).'×' : (isset($rs['multiplier']) ? number_format($rs['multiplier'],2).'×' : '-') }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- KOLOM KANAN: HASIL -->
        <div>
            @if(session('summary_simulasi'))
            @php
                $sum          = session('summary_simulasi');
                $hasil        = session('hasil_simulasi');
                $linkage      = session('linkage_data', []);

                $mOutput      = $sum['multiplier_output'] ?? $sum['multiplier'] ?? 0;
                $mNtb         = $sum['multiplier_ntb']    ?? 0;

                $adhbAwal     = $sum['pdrb_adhb_awal']   ?? $sum['pdrb_awal']  ?? 0;
                $adhbBaru     = $sum['pdrb_adhb_baru']   ?? $sum['pdrb_baru']  ?? 0;
                $adhkAwal     = $sum['pdrb_adhk_awal']   ?? 0;
                $adhkBaru     = $sum['pdrb_adhk_baru']   ?? 0;
                $persenAdhb   = $sum['persen_naik_adhb'] ?? $sum['persen_naik'] ?? 0;
                $persenAdhk   = $sum['persen_naik_adhk'] ?? 0;
                $tambahanNtb  = $sum['tambahan_ntb']     ?? 0;
                $tambahanOut  = $sum['tambahan_output']  ?? 0;
                $dampakPdrb   = $persenAdhb;
            @endphp

            <!-- Multiplier strip -->
            @if($mOutput > 0)
            <div class="multiplier-strip">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                <div>
                    Output Multiplier: <strong>{{ number_format($mOutput,4) }}×</strong>
                    @if($mNtb > 0)
                        &nbsp;<span style="opacity:.6;">|</span>&nbsp;
                        NTB Multiplier: <strong>{{ number_format($mNtb,4) }}×</strong>
                    @endif
                    <span class="hint-text" style="color:#4ade80;font-size:11px;margin-left:6px;">
                        setiap Rp 1 M stimulus → Rp{{ number_format($mOutput,2) }} M tambahan output
                    </span>
                </div>
            </div>
            @endif

            <!-- Metodologi note -->
            <div class="metodologi-note">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <strong>Catatan Metodologi:</strong>
                    Dampak PDRB dihitung dari <strong>ΔNTB</strong> (Nilai Tambah Bruto tambahan), bukan dari ΔOutput mentah.
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-grid-6">
                <div class="sc-row-label">ADHB — Atas Dasar Harga Berlaku</div>

                <!-- PDRB ADHB Lama -->
                <div class="sc sc-orange">
                    <div class="sc-label">PDRB ADHB Awal</div>
                    <div class="sc-val orange" style="font-size:13px;">Rp {{ number_format($adhbAwal,3,',','.') }} M</div>
                    <div class="sc-sub">{{ $sum['pdrb_tahun'] ?? '-' }} {{ $sum['pdrb_triwulan'] ?? '' }}</div>
                </div>

                <!-- Dampak thd PDRB -->
                <div class="sc sc-blue">
                    <div class="sc-label">Dampak thd PDRB</div>
                    <div class="sc-val blue">{{ number_format($dampakPdrb,4) }}%</div>
                    <div class="sc-sub">via ΔNTB</div>
                </div>

                <!-- PDRB ADHB Baru -->
                <div class="sc sc-green">
                    <div class="sc-label">PDRB ADHB Baru Estimasi</div>
                    <div class="sc-val green" style="font-size:13px;">Rp {{ number_format($adhbBaru,3,',','.') }} M</div>
                    <div class="sc-sub">Rp +{{ number_format($adhbBaru - $adhbAwal, 3,',','.') }} M</div>
                </div>

                <!-- Divider -->
                <div class="sc-divider"></div>

                <!-- Label baris ADHK -->
                <div class="sc-row-label">ADHK — Atas Dasar Harga Konstan (riil)</div>

                <!-- PDRB ADHK Awal -->
                <div class="sc sc-teal">
                    <div class="sc-label">PDRB ADHK Awal</div>
                    <div class="sc-val teal" style="font-size:13px;">Rp {{ number_format($adhkAwal,3,',','.') }} M</div>
                    <div class="sc-sub">Harga konstan</div>
                </div>

                <!-- ΔNTB -->
                <div class="sc sc-purple">
                    <div class="sc-label">ΔNTB Tambahan NTB</div>
                    <div class="sc-val purple" style="font-size:13px;">Rp +{{ number_format($tambahanNtb,3,',','.') }} M</div>
                    <div class="sc-sub">Dasar dampak PDRB</div>
                </div>

                <!-- PDRB ADHK Baru -->
                @php
                    $ntbRiil = ($adhbAwal > 0 && $adhkAwal > 0)
                        ? $tambahanNtb * ($adhkAwal / $adhbAwal)
                        : $tambahanNtb;
                @endphp
                <div class="sc sc-emerald">
                    <div class="sc-label">PDRB ADHK Baru Estimasi</div>
                    <div class="sc-val emerald" style="font-size:13px;">Rp {{ number_format($adhkBaru,3,',','.') }} M</div>
                    <div class="sc-sub">
                        ΔNTB riil: Rp +{{ number_format($ntbRiil, 3, ',', '.') }} M Rp
                    </div>
                </div>

            </div>

            <!-- Action bar -->
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;flex-wrap:wrap;">
                <a href="{{ route('simulasi.export.excel') }}" class="btn-outline btn-excel">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Export CSV
                </a>
                <a href="{{ route('simulasi.export.pdf') }}" target="_blank" class="btn-outline btn-pdf">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Export PDF
                </a>
                <button type="button" class="btn-outline btn-save" onclick="document.getElementById('modalSimpan').classList.add('open')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan
                </button>
                <a href="{{ route('simulasi.riwayat') }}" class="btn-outline" style="color:#374151;background:#f9fafb;border:0.5px solid #e5e7eb;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Riwayat
                </a>
            </div>

            <!-- Tabs -->
            <div class="card" style="margin-bottom:0;">
                <div class="tabs">
                    <button class="tab-btn active" onclick="switchTab(event,'tab-dampak')">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
                        Tabel Dampak
                    </button>
                    <button class="tab-btn" onclick="switchTab(event,'tab-chart')">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        Grafik
                    </button>
                    <button class="tab-btn" onclick="switchTab(event,'tab-linkage')">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        Analisis Linkage
                    </button>
                </div>

                <!-- Tab: Tabel Dampak -->
                <div id="tab-dampak" class="tab-panel active">
                    <div class="table-scroll">
                        <table class="sim-table" style="min-width:580px;">
                            <thead>
                                <tr>
                                    <th style="width:36px;">No</th>
                                    <th class="th-left">Sektor</th>
                                    <th>Output Awal<br><span style="font-weight:400;font-size:9px;text-transform:none;">(M Rp)</span></th>
                                    <th>ΔX Output<br><span style="font-weight:400;font-size:9px;text-transform:none;">(M Rp)</span></th>
                                    <th class="th-ntb">ΔNTB<br><span style="font-weight:400;font-size:9px;text-transform:none;">(M Rp)</span></th>
                                    <th>Output Baru<br><span style="font-weight:400;font-size:9px;text-transform:none;">(M Rp)</span></th>
                                    <th>% Dampak</th>
                                    <th>Klasifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasil as $i => $row)
                                @php
                                    $pct      = $row['persen_dampak'];
                                    $pctClass = $pct < 1 ? 'pct-low' : ($pct < 5 ? 'pct-mid' : 'pct-high');
                                    $klasMap  = ['Kunci'=>'klas-kunci','Hilir'=>'klas-hilir','Hulu'=>'klas-hulu','Independen'=>'klas-independen'];
                                    $kc       = $klasMap[$row['klasifikasi'] ?? 'Independen'] ?? 'klas-independen';
                                @endphp
                                <tr>
                                    <td class="td-no">{{ $i + 1 }}</td>
                                    <td class="td-left">{{ $row['nama'] }}</td>
                                    <td class="td-num">{{ number_format($row['output_awal'],3,',','.') }}</td>
                                    <td><span class="badge-output">+{{ number_format($row['tambahan_output'],3,',','.') }}</span></td>
                                    <td class="td-ntb"><span class="badge-ntb">+{{ number_format($row['tambahan_ntb'] ?? 0,3,',','.') }}</span></td>
                                    <td class="td-num">{{ number_format($row['output_baru'],3,',','.') }}</td>
                                    <td><span class="pct-badge {{ $pctClass }}">{{ number_format($pct,2) }}%</span></td>
                                    <td><span class="klas-badge {{ $kc }}">{{ $row['klasifikasi'] ?? '-' }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Legenda kolom -->
                    <div style="margin-top:10px;padding:9px 12px;background:#f9fafb;border:0.5px solid #e5e7eb;border-radius:8px;font-size:11px;color:#6b7280;line-height:1.7;">
                        <strong style="color:#374151;">Keterangan:</strong>
                        ΔX = tambahan output bruto ·
                        <span style="color:#0d9488;font-weight:600;">ΔNTB</span> = ΔX × (1 − rasio biaya antara) → dasar dampak PDRB ·
                        % Dampak = ΔX / Output Awal
                    </div>
                </div>

                <!-- Tab: Grafik -->
                <div id="tab-chart" class="tab-panel">
                    <div class="tabs-chart-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div>
                            <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:10px;">Tambahan Output ΔX (M Rp)</div>
                            <div class="chart-box" style="height:{{ max(200, count($hasil)*28+60) }}px;">
                                <canvas id="chartDampak"></canvas>
                            </div>
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:10px;">Tambahan NTB ΔNTB (M Rp)</div>
                            <div class="chart-box" style="height:{{ max(200, count($hasil)*28+60) }}px;">
                                <canvas id="chartNtb"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Linkage -->
                <div id="tab-linkage" class="tab-panel">
                    <div class="tabs-linkage-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div>
                            <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:4px;">Peta Keterkaitan Antar Sektor</div>
                            <div style="font-size:11px;color:#9ca3af;margin-bottom:10px;">Backward Linkage (X) vs Forward Linkage (Y), dinormalisasi</div>
                            <div class="chart-box" style="height:280px;"><canvas id="chartScatter"></canvas></div>
                            <div class="quadrant-legend">
                                <div class="q-item q-kunci"><span class="q-dot" style="background:#f59e0b;"></span><strong>Kunci</strong> — BL≥1 & FL≥1</div>
                                <div class="q-item q-hilir"><span class="q-dot" style="background:#3b82f6;"></span><strong>Hilir</strong> — BL≥1, FL&lt;1</div>
                                <div class="q-item q-hulu"><span class="q-dot" style="background:#22c55e;"></span><strong>Hulu</strong> — BL&lt;1, FL≥1</div>
                                <div class="q-item q-independen"><span class="q-dot" style="background:#9ca3af;"></span><strong>Independen</strong> — BL&lt;1 & FL&lt;1</div>
                            </div>
                        </div>
                        <!-- Linkage table -->
                        <div>
                            <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:10px;">Detail Backward & Forward Linkage</div>
                            <div class="table-scroll" style="max-height:360px;overflow-y:auto;">
                                <table class="sim-table" style="min-width:280px;">
                                    <thead>
                                        <tr>
                                            <th class="th-left">Sektor</th>
                                            <th>Backward</th>
                                            <th>Forward</th>
                                            <th>Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $maxBack = max(array_column($hasil,'norm_backward') ?: [1]);
                                            $maxFwd  = max(array_column($hasil,'norm_forward')  ?: [1]);
                                        @endphp
                                        @foreach($hasil as $row)
                                        @php
                                            $kc    = ($klasMap[$row['klasifikasi'] ?? 'Independen'] ?? 'klas-independen');
                                            $pBack = $maxBack > 0 ? min(100, ($row['norm_backward'] ?? 0) / $maxBack * 100) : 0;
                                            $pFwd  = $maxFwd  > 0 ? min(100, ($row['norm_forward']  ?? 0) / $maxFwd  * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td class="td-left" style="font-size:11.5px;">{{ $row['nama'] }}</td>
                                            <td style="min-width:110px;">
                                                <div class="link-bar-wrap">
                                                    <div class="link-bar-track"><div class="link-bar-fill back" style="width:{{ $pBack }}%"></div></div>
                                                    <span class="link-val">{{ number_format($row['norm_backward'] ?? 0,3) }}</span>
                                                </div>
                                            </td>
                                            <td style="min-width:110px;">
                                                <div class="link-bar-wrap">
                                                    <div class="link-bar-track"><div class="link-bar-fill fwd" style="width:{{ $pFwd }}%"></div></div>
                                                    <span class="link-val">{{ number_format($row['norm_forward'] ?? 0,3) }}</span>
                                                </div>
                                            </td>
                                            <td><span class="klas-badge {{ $kc }}">{{ $row['klasifikasi'] ?? '-' }}</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @else
            <div class="card" style="margin-bottom:0;">
                <div class="empty-state">
                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    <h6>Belum Ada Hasil</h6>
                    <p>Pilih dataset, masukkan stimulus (<strong>Miliar Rp</strong>), lalu klik <strong>Simulasikan</strong>.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Simpan -->
<div class="modal-overlay" id="modalSimpan">
    <div class="modal-box">
        <div class="modal-head">
            <span class="modal-title">Simpan ke Riwayat</span>
            <button class="modal-close" onclick="document.getElementById('modalSimpan').classList.remove('open')">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('simulasi.simpan') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Nama Simulasi</label>
                    <input type="text" name="nama_simulasi" class="form-input"
                        placeholder="Contoh: Skenario Stimulus Pertanian 2024"
                        value="Simulasi {{ now()->format('d/m/Y H:i') }}" required>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-cancel" onclick="document.getElementById('modalSimpan').classList.remove('open')">Batal</button>
                <button type="submit" class="btn-submit">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(session('success'))
Swal.fire({icon:'success',title:'Berhasil',text:'{{ session("success") }}',timer:2200,showConfirmButton:false});
@endif
@if(session('error'))
Swal.fire({icon:'error',title:'Gagal',text:'{{ session("error") }}',confirmButtonColor:'#f97316'});
@endif

let sektorCount = {{ count(session('simulasi_input.sektor', [null])) }};
Chart.defaults.font.family = "'Figtree', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#6b7280';

function switchTab(e, id) {
    document.querySelectorAll('.tab-btn').forEach(b  => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    e.currentTarget.classList.add('active');
    document.getElementById(id).classList.add('active');
    if (id === 'tab-chart'   && !window._chartsBuilt)  { window._chartsBuilt  = true; buildCharts(); }
    if (id === 'tab-linkage' && !window._scatterBuilt) { window._scatterBuilt = true; buildScatter(); }
}

@if(session('hasil_simulasi'))
const HASIL_DATA   = @json(session('hasil_simulasi'));
const LINKAGE_DATA = @json(session('linkage_data', []));

function mkBarChart(canvasId, data, label, color) {
    const labels = HASIL_DATA.map(r => r.nama.length > 22 ? r.nama.substr(0,20)+'…' : r.nama);
    new Chart(document.getElementById(canvasId), {
        type: 'bar',
        data: { labels, datasets:[{ label, data, backgroundColor: color, borderRadius:3 }] },
        options: {
            indexAxis:'y', responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{display:false}, tooltip:{callbacks:{label:ctx=>` ${ctx.raw.toFixed(3)} M Rp`}} },
            scales:{
                x:{ grid:{color:'#f3f4f6'}, ticks:{callback:v=>v.toFixed(1)} },
                y:{ grid:{display:false}, ticks:{font:{size:10}} }
            }
        }
    });
}

function buildCharts() {
    mkBarChart('chartDampak',
        HASIL_DATA.map(r => parseFloat((r.tambahan_output||0).toFixed(3))),
        'ΔX Output', 'rgba(249,115,22,0.75)');

    mkBarChart('chartNtb',
        HASIL_DATA.map(r => parseFloat((r.tambahan_ntb||0).toFixed(3))),
        'ΔNTB', 'rgba(20,184,166,0.75)');
}

function buildScatter() {
    const colorMap = {
        'Kunci'     :'rgba(245,158,11,0.85)',
        'Hilir'     :'rgba(59,130,246,0.85)',
        'Hulu'      :'rgba(34,197,94,0.85)',
        'Independen':'rgba(156,163,175,0.75)'
    };
    const datasets = {};
    LINKAGE_DATA.forEach(item => {
        const k = item.klasifikasi;
        if (!datasets[k]) datasets[k] = {
            label:k, data:[],
            backgroundColor:colorMap[k]||'rgba(156,163,175,0.75)',
            pointRadius:7, pointHoverRadius:9
        };
        datasets[k].data.push({x:item.backward, y:item.forward, nama:item.nama});
    });
    new Chart(document.getElementById('chartScatter'), {
        type:'scatter',
        data:{ datasets:Object.values(datasets) },
        options:{
            responsive:true, maintainAspectRatio:false,
            plugins:{
                legend:{ position:'top', labels:{boxWidth:10,padding:10,font:{size:11}} },
                tooltip:{ callbacks:{ label:ctx=>`${ctx.raw.nama} (BL:${ctx.raw.x.toFixed(3)}, FL:${ctx.raw.y.toFixed(3)})` } }
            },
            scales:{
                x:{ title:{display:true,text:'Backward Linkage (Normalized)',font:{size:10}}, grid:{color:ctx=>ctx.tick.value===1?'#f97316':'#f3f4f6'} },
                y:{ title:{display:true,text:'Forward Linkage (Normalized)', font:{size:10}}, grid:{color:ctx=>ctx.tick.value===1?'#f97316':'#f3f4f6'} }
            }
        }
    });
}
@endif

function resetSimulasi() {
    Swal.fire({
        title:'Reset simulasi?', text:'Hasil simulasi akan dihapus.', icon:'warning',
        showCancelButton:true, confirmButtonColor:'#dc2626', cancelButtonColor:'#6b7280',
        confirmButtonText:'Ya, reset', cancelButtonText:'Batal'
    }).then(r => { if(r.isConfirmed) window.location.href="{{ route('simulasi.reset') }}"; });
}

function loadSektor() {
    document.querySelectorAll('.sektor-select').forEach(el => {
        const cur = el.value;
        el.innerHTML = '<option value="">— Pilih Sektor —</option>';
        sektorData.forEach(s => { el.innerHTML += `<option value="${s.id}" ${cur==s.id?'selected':''}>${s.nama}</option>`; });
    });
}

function tambahBaris() {
    if (!document.getElementById('dataset_id').value) {
        Swal.fire({icon:'warning',title:'Perhatian',text:'Pilih dataset terlebih dahulu.',confirmButtonColor:'#f97316'});
        return;
    }
    let opsi = '<option value="">— Pilih Sektor —</option>';
    sektorData.forEach(s => { opsi += `<option value="${s.id}">${s.nama}</option>`; });
    sektorCount++;
    document.getElementById('wrapper-sektor').insertAdjacentHTML('beforeend', `
        <div class="sektor-block posisi">
            <div class="sektor-block-head">
                <span class="sektor-block-label">Sektor ${sektorCount}</span>
                <button type="button" class="btn-hapus-sektor" onclick="hapusBaris(this)">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="form-group" style="margin-bottom:10px;">
                <label class="form-label">Sektor</label>
                <select name="sektor[]" class="form-select sektor-select" required>${opsi}</select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Nilai Stimulus</label>
                <div class="input-with-suffix">
                    <input type="number" min="0" step="0.001" name="nilai[]" class="form-input" placeholder="Contoh: 500" required>
                    <span class="input-suffix">Miliar Rp</span>
                </div>
            </div>
        </div>`);
}

function hapusBaris(btn) {
    Swal.fire({
        title:'Hapus sektor ini?', icon:'question',
        showCancelButton:true, confirmButtonColor:'#dc2626', cancelButtonColor:'#6b7280',
        confirmButtonText:'Ya, hapus', cancelButtonText:'Batal'
    }).then(r => { if(r.isConfirmed) btn.closest('.posisi').remove(); });
}

document.getElementById('modalSimpan')?.addEventListener('click', function(e) {
    if(e.target===this) this.classList.remove('open');
});
</script>
@endsection