@extends('layouts.app')

@section('title', 'Detail Riwayat Simulasi')

@section('content')

<style>
    * { box-sizing: border-box; }
    body { background: #f7f6f3; }

    .page {
        padding: 24px 24px 48px;
        padding-top: 80px;
        font-family: 'Figtree', sans-serif;
        overflow-x: hidden;
    }

    .card { background:#fff; border:0.5px solid #e5e7eb; border-radius:12px; overflow:hidden; margin-bottom:16px; }
    .card-head {
        display:flex; align-items:center; justify-content:space-between;
        padding:14px 20px; border-bottom:0.5px solid #e5e7eb; background:#fafafa; gap:8px; flex-wrap:wrap;
    }
    .card-head-left { display:flex; align-items:center; gap:8px; }
    .card-head-icon { color:#9ca3af; }
    .card-head-title { font-size:14px; font-weight:500; color:#111827; }

    .btn-back {
        display:inline-flex; align-items:center; gap:6px;
        height:36px; padding:0 16px; font-size:13px; font-weight:500;
        color:#374151; background:#f9fafb; border:0.5px solid #e5e7eb; border-radius:8px;
        text-decoration:none; transition:background 0.12s;
    }
    .btn-back:hover { background:#f3f4f6; text-decoration:none; color:#111827; }

    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

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

    /* Summary Cards 2×3 (identical to index) */
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
    .sc-orange::before  { background:#f97316; }
    .sc-blue::before    { background:#3b82f6; }
    .sc-green::before   { background:#22c55e; }
    .sc-teal::before    { background:#14b8a6; }
    .sc-purple::before  { background:#8b5cf6; }
    .sc-emerald::before { background:#10b981; }

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
    .sc-val.orange  { color:#ea580c; }
    .sc-val.blue    { color:#2563eb; }
    .sc-val.green   { color:#16a34a; }
    .sc-val.teal    { color:#0d9488; }
    .sc-val.purple  { color:#7c3aed; }
    .sc-val.emerald { color:#059669; }
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

    /* Grid 2 kolom */
    .two-col-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Table */
    .sim-table { width:100%; border-collapse:collapse; font-size:13px; min-width:560px; }
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
    .sim-table tbody td.td-no   { color:#9ca3af; text-align:center; }
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

    /* Linkage bar */
    .link-bar-wrap { display:flex; align-items:center; gap:6px; }
    .link-bar-track { flex:1; height:6px; border-radius:3px; background:#f3f4f6; overflow:hidden; }
    .link-bar-fill { height:100%; border-radius:3px; transition:width 0.5s ease; }
    .link-bar-fill.back { background:#f97316; }
    .link-bar-fill.fwd  { background:#3b82f6; }
    .link-val { font-size:11.5px; font-weight:600; color:#374151; min-width:36px; text-align:right; font-family:monospace; }

    /* Scatter */
    .quadrant-legend { display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-top:12px; }
    .q-item { display:flex; align-items:center; gap:6px; padding:6px 10px; border-radius:6px; font-size:11px; }
    .q-kunci      { background:#fef9c3; color:#854d0e; }
    .q-hilir      { background:#eff6ff; color:#1d4ed8; }
    .q-hulu       { background:#f0fdf4; color:#166534; }
    .q-independen { background:#f3f4f6; color:#6b7280; }
    .q-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; }

    .chart-box { position:relative; width:100%; }

    /* Responsive */
    @media (max-width: 900px) {
        .summary-grid-6 { grid-template-columns: repeat(2, 1fr); }
        .two-col-grid   { grid-template-columns: 1fr; gap:16px; }
    }
    @media (max-width: 640px) {
        .page { padding: 12px 12px 40px; padding-top: 72px; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .btn-back { width:100%; justify-content:center; min-height:44px; }
        .summary-grid-6 { grid-template-columns: repeat(2,1fr); gap:8px; }
        .sc-val { font-size:12px !important; }
        .sc-row-label { font-size:9px; }
        .tabs { padding:8px 10px 0; }
        .tab-btn { padding:7px 8px; font-size:11px; gap:4px; }
        .tab-panel { padding:12px; }
        .multiplier-strip .hint-text { display:none; }
        .tab-btn span.tab-label { display:none; }
    }
    @media (max-width: 480px) {
        .summary-grid-6 { grid-template-columns: 1fr; }
        .quadrant-legend { grid-template-columns: 1fr; }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <div class="page-header">
        <div>
            <h3 class="fw-bold mb-1" style="font-family:'Figtree',sans-serif;">{{ $row->nama_simulasi }}</h3>
            <p style="font-size:13px; color:#9ca3af; margin:0;">
                Disimpan {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') : '-' }}
            </p>
        </div>
        <a href="{{ route('simulasi.riwayat') }}" class="btn-back">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali ke Riwayat
        </a>
    </div>

    @php
        $mOutput     = $summary['multiplier_output'] ?? $summary['multiplier'] ?? 0;
        $mNtb        = $summary['multiplier_ntb']    ?? 0;

        $adhbAwal    = $summary['pdrb_adhb_awal']   ?? $summary['pdrb_awal']  ?? 0;
        $adhbBaru    = $summary['pdrb_adhb_baru']   ?? $summary['pdrb_baru']  ?? 0;
        $adhkAwal    = $summary['pdrb_adhk_awal']   ?? 0;
        $adhkBaru    = $summary['pdrb_adhk_baru']   ?? 0;
        $persenAdhb  = $summary['persen_naik_adhb'] ?? $summary['persen_naik'] ?? 0;
        $tambahanNtb = $summary['tambahan_ntb']     ?? 0;
        $tambahanOut = $summary['tambahan_output']  ?? 0;
        $totalStimulus = $summary['total_stimulus'] ?? 0;
        $stimulusFormatted = $totalStimulus > 0
            ? (fmod($totalStimulus, 1) == 0
                ? number_format($totalStimulus, 0, ',', '.')
                : number_format($totalStimulus, 3, ',', '.'))
            : null;
    @endphp

    <!-- Total Stimulus Strip -->
    @if($stimulusFormatted)
    <div class="multiplier-strip" style="background:#eef2ff; border-color:#a5b4fc; color:#3730a3; margin-bottom:10px;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <div>
            Total Stimulus: <strong>Rp {{ $stimulusFormatted }} M</strong>
            <span class="hint-text" style="color:#818cf8; font-size:11px; margin-left:6px;">nilai injeksi ke perekonomian</span>
        </div>
    </div>
    @endif

    <!-- Multiplier Strip -->
    @if($mOutput > 0)
    <div class="multiplier-strip">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
        <div>
            Output Multiplier: <strong>{{ number_format($mOutput, 4) }}×</strong>
            @if($mNtb > 0)
                &nbsp;<span style="opacity:.6;">|</span>&nbsp;
                NTB Multiplier: <strong>{{ number_format($mNtb, 4) }}×</strong>
            @endif
            <span class="hint-text" style="color:#4ade80; font-size:11px; margin-left:6px;">
                setiap Rp 1 M stimulus → Rp {{ number_format($mOutput, 2) }} M tambahan output
            </span>
        </div>
    </div>
    @endif

    <!-- Sektor yang Diinjeksi -->
    @if(!empty($summary['sektor_injeksi']))
    <div style="display:flex; align-items:flex-start; gap:8px; padding:10px 16px;
        background:#fff7ed; border:0.5px solid #fed7aa; border-radius:8px;
        margin-bottom:12px; font-size:12px; color:#92400e; flex-wrap:wrap;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
        </svg>
        <div style="flex:1;">
            <span style="font-weight:600;">Sektor yang Diinjeksi:</span>
            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:6px;">
                @foreach($summary['sektor_injeksi'] as $si)
                <span style="display:inline-flex; align-items:center; gap:6px;
                    background:#fff; border:0.5px solid #fed7aa; border-radius:7px;
                    padding:4px 10px; font-size:11.5px;">
                    <span style="font-weight:500; color:#111827;">{{ $si['nama'] }}</span>
                    <span style="color:#9a3412; font-weight:700; font-family:monospace;">
                        Rp {{ fmod($si['nilai'], 1) == 0
                            ? number_format($si['nilai'], 0, ',', '.')
                            : number_format($si['nilai'], 3, ',', '.') }} M
                    </span>
                </span>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Metodologi Note -->
    <div class="metodologi-note">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>
            <strong>Catatan Metodologi:</strong>
            Dampak PDRB dihitung dari <strong>ΔNTB</strong> (Nilai Tambah Bruto tambahan), bukan dari ΔOutput mentah.
        </div>
    </div>

    <!-- Action bar -->
    <div style="display:flex; align-items:center; justify-content:flex-end; gap:8px; margin-bottom:14px; flex-wrap:wrap;">
        <a href="{{ route('simulasi.riwayat.export.excel', $row->id) }}"
            style="display:inline-flex; align-items:center; gap:5px; height:32px; padding:0 14px;
            font-size:12px; font-weight:500; border-radius:7px; text-decoration:none;
            color:#166534; background:#f0fdf4; border:0.5px solid #86efac; font-family:'Figtree',sans-serif;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/>
            </svg>
            Export CSV
        </a>
        <a href="{{ route('simulasi.riwayat.export.pdf', $row->id) }}" target="_blank"
            style="display:inline-flex; align-items:center; gap:5px; height:32px; padding:0 14px;
            font-size:12px; font-weight:500; border-radius:7px; text-decoration:none;
            color:#991b1b; background:#fef2f2; border:0.5px solid #fca5a5; font-family:'Figtree',sans-serif;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/>
            </svg>
            Export PDF
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid-6">

        <!-- Label baris ADHB -->
        <div class="sc-row-label">ADHB — Atas Dasar Harga Berlaku</div>

        <!-- PDRB ADHB Awal -->
        <div class="sc sc-orange">
            <div class="sc-label">PDRB ADHB Awal</div>
            <div class="sc-val orange" style="font-size:13px;">Rp {{ number_format($adhbAwal, 3, ',', '.') }} M</div>
            <div class="sc-sub">{{ $summary['pdrb_tahun'] ?? '-' }} {{ $summary['pdrb_triwulan'] ?? '' }}</div>
        </div>

        <!-- Dampak PDRB via ΔNTB -->
        <div class="sc sc-blue">
            <div class="sc-label">Dampak thd PDRB</div>
            <div class="sc-val blue">{{ number_format($persenAdhb, 4) }}%</div>
            <div class="sc-sub">via ΔNTB</div>
        </div>

        <!-- PDRB ADHB Baru -->
        <div class="sc sc-green">
            <div class="sc-label">PDRB ADHB Baru Estimasi</div>
            <div class="sc-val green" style="font-size:13px;">Rp {{ number_format($adhbBaru, 3, ',', '.') }} M</div>
            <div class="sc-sub">Rp +{{ number_format($adhbBaru - $adhbAwal, 3, ',', '.') }} M</div>
        </div>

        <!-- Divider -->
        <div class="sc-divider"></div>

        <!-- Label baris ADHK -->
        <div class="sc-row-label">ADHK — Atas Dasar Harga Konstan (riil)</div>

        <!-- PDRB ADHK Awal -->
        <div class="sc sc-teal">
            <div class="sc-label">PDRB ADHK Awal</div>
            @if($adhkAwal > 0)
                <div class="sc-val teal" style="font-size:13px;">Rp {{ number_format($adhkAwal, 3, ',', '.') }} M</div>
                <div class="sc-sub">Harga konstan</div>
            @else
                <div class="sc-val teal" style="font-size:13px; opacity:.5;">—</div>
                <div class="sc-sub">Data tidak tersimpan di riwayat ini</div>
            @endif
        </div>

        <!-- ΔNTB -->
        <div class="sc sc-purple">
            <div class="sc-label">ΔNTB Tambahan NTB</div>
            @if($tambahanNtb > 0)
                <div class="sc-val purple" style="font-size:13px;">Rp +{{ number_format($tambahanNtb, 3, ',', '.') }} M</div>
                <div class="sc-sub">Dasar dampak PDRB</div>
            @else
                <div class="sc-val purple" style="font-size:13px; opacity:.5;">—</div>
                <div class="sc-sub">Data tidak tersimpan di riwayat ini</div>
            @endif
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

    <!-- Tabs -->
    <div class="card" style="margin-bottom:0;">
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab(event,'tab-dampak')">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
                <span class="tab-label">Tabel Dampak</span>
            </button>
            <button class="tab-btn" onclick="switchTab(event,'tab-chart')">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <span class="tab-label">Grafik</span>
            </button>
            <button class="tab-btn" onclick="switchTab(event,'tab-linkage')">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                <span class="tab-label">Analisis Linkage</span>
            </button>
        </div>

        <!-- Tab: Tabel Dampak -->
        <div id="tab-dampak" class="tab-panel active">
            <div style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
                <table class="sim-table">
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
                        @foreach($hasil as $i => $row_h)
                        @php
                            $pct      = $row_h['persen_dampak'];
                            $pctClass = $pct < 1 ? 'pct-low' : ($pct < 5 ? 'pct-mid' : 'pct-high');
                            $klasMap  = ['Kunci'=>'klas-kunci','Hilir'=>'klas-hilir','Hulu'=>'klas-hulu','Independen'=>'klas-independen'];
                            $kc       = $klasMap[$row_h['klasifikasi'] ?? 'Independen'] ?? 'klas-independen';
                        @endphp
                        <tr>
                            <td class="td-no">{{ $i + 1 }}</td>
                            <td class="td-left">{{ $row_h['nama'] }}</td>
                            <td class="td-num">{{ number_format($row_h['output_awal'], 3, ',', '.') }}</td>
                            <td><span class="badge-output">+{{ number_format($row_h['tambahan_output'], 3, ',', '.') }}</span></td>
                            <td class="td-ntb">
                                @if(isset($row_h['tambahan_ntb']))
                                    <span class="badge-ntb">+{{ number_format($row_h['tambahan_ntb'], 3, ',', '.') }}</span>
                                @else
                                    <span style="color:#9ca3af; font-size:11px;">—</span>
                                @endif
                            </td>
                            <td class="td-num">{{ number_format($row_h['output_baru'], 3, ',', '.') }}</td>
                            <td><span class="pct-badge {{ $pctClass }}">{{ number_format($pct, 2) }}%</span></td>
                            <td><span class="klas-badge {{ $kc }}">{{ $row_h['klasifikasi'] ?? '-' }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Legenda kolom -->
            <div style="margin-top:10px; padding:9px 12px; background:#f9fafb; border:0.5px solid #e5e7eb; border-radius:8px; font-size:11px; color:#6b7280; line-height:1.7;">
                <strong style="color:#374151;">Keterangan:</strong>
                ΔX = tambahan output bruto ·
                <span style="color:#0d9488; font-weight:600;">ΔNTB</span> = ΔX × (1 − rasio biaya antara) → dasar dampak PDRB ·
                % Dampak = ΔX / Output Awal
            </div>
        </div>

        <!-- Tab: Grafik -->
        <div id="tab-chart" class="tab-panel">
            <div class="two-col-grid">
                <div>
                    <div style="font-size:12px; font-weight:600; color:#374151; margin-bottom:10px;">Tambahan Output ΔX (M Rp)</div>
                    <div class="chart-box" style="height:{{ max(200, count($hasil)*28+60) }}px;">
                        <canvas id="chartDampak"></canvas>
                    </div>
                </div>
                <div>
                    <div style="font-size:12px; font-weight:600; color:#374151; margin-bottom:10px;">Tambahan NTB ΔNTB (M Rp)</div>
                    <div class="chart-box" style="height:{{ max(200, count($hasil)*28+60) }}px;">
                        <canvas id="chartNtb"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Linkage -->
        <div id="tab-linkage" class="tab-panel">
            <div class="two-col-grid">
                <!-- Scatter -->
                <div>
                    <div style="font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Peta Keterkaitan Antar Sektor</div>
                    <div style="font-size:11px; color:#9ca3af; margin-bottom:10px;">Backward Linkage (X) vs Forward Linkage (Y), dinormalisasi</div>
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
                    <div style="font-size:12px; font-weight:600; color:#374151; margin-bottom:10px;">Detail Backward & Forward Linkage</div>
                    <div style="overflow-x:auto; -webkit-overflow-scrolling:touch; max-height:360px; overflow-y:auto;">
                        <table class="sim-table" style="min-width:unset;">
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
                                    $maxBack = max(array_column($hasil, 'norm_backward') ?: [1]);
                                    $maxFwd  = max(array_column($hasil, 'norm_forward')  ?: [1]);
                                @endphp
                                @foreach($hasil as $row_h)
                                @php
                                    $klasMap = ['Kunci'=>'klas-kunci','Hilir'=>'klas-hilir','Hulu'=>'klas-hulu','Independen'=>'klas-independen'];
                                    $kc      = $klasMap[$row_h['klasifikasi'] ?? 'Independen'] ?? 'klas-independen';
                                    $pBack   = $maxBack > 0 ? min(100, ($row_h['norm_backward'] ?? 0) / $maxBack * 100) : 0;
                                    $pFwd    = $maxFwd  > 0 ? min(100, ($row_h['norm_forward']  ?? 0) / $maxFwd  * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="td-left" style="font-size:11.5px;">{{ $row_h['nama'] }}</td>
                                    <td style="min-width:110px;">
                                        <div class="link-bar-wrap">
                                            <div class="link-bar-track"><div class="link-bar-fill back" style="width:{{ $pBack }}%"></div></div>
                                            <span class="link-val">{{ number_format($row_h['norm_backward'] ?? 0, 3) }}</span>
                                        </div>
                                    </td>
                                    <td style="min-width:110px;">
                                        <div class="link-bar-wrap">
                                            <div class="link-bar-track"><div class="link-bar-fill fwd" style="width:{{ $pFwd }}%"></div></div>
                                            <span class="link-val">{{ number_format($row_h['norm_forward'] ?? 0, 3) }}</span>
                                        </div>
                                    </td>
                                    <td><span class="klas-badge {{ $kc }}">{{ $row_h['klasifikasi'] ?? '-' }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
Chart.defaults.font.family = "'Figtree', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#6b7280';

const HASIL_DATA   = @json($hasil);
const LINKAGE_DATA = @json($linkageData);

function switchTab(e, id) {
    document.querySelectorAll('.tab-btn').forEach(b  => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    e.currentTarget.classList.add('active');
    document.getElementById(id).classList.add('active');

    if (id === 'tab-chart'   && !window._chartsBuilt)  { window._chartsBuilt  = true; buildCharts(); }
    if (id === 'tab-linkage' && !window._scatterBuilt) { window._scatterBuilt = true; buildScatter(); }
}

function mkBarChart(canvasId, data, label, color) {
    const labels = HASIL_DATA.map(r => r.nama.length > 22 ? r.nama.substr(0,20)+'…' : r.nama);
    new Chart(document.getElementById(canvasId), {
        type: 'bar',
        data: { labels, datasets:[{ label, data, backgroundColor: color, borderRadius:3 }] },
        options: {
            indexAxis:'y', responsive:true, maintainAspectRatio:false,
            plugins:{
                legend:{display:false},
                tooltip:{callbacks:{label:ctx=>` ${ctx.raw.toFixed(3)} M Rp`}}
            },
            scales:{
                x:{ grid:{color:'#f3f4f6'}, ticks:{callback:v=>v.toFixed(1)} },
                y:{ grid:{display:false}, ticks:{font:{size:10}} }
            }
        }
    });
}

function buildCharts() {
    mkBarChart(
        'chartDampak',
        HASIL_DATA.map(r => parseFloat((r.tambahan_output || 0).toFixed(3))),
        'ΔX Output',
        'rgba(249,115,22,0.75)'
    );
    mkBarChart(
        'chartNtb',
        HASIL_DATA.map(r => parseFloat((r.tambahan_ntb || 0).toFixed(3))),
        'ΔNTB',
        'rgba(20,184,166,0.75)'
    );
}

function buildScatter() {
    const colorMap = {
        'Kunci'     : 'rgba(245,158,11,0.85)',
        'Hilir'     : 'rgba(59,130,246,0.85)',
        'Hulu'      : 'rgba(34,197,94,0.85)',
        'Independen': 'rgba(156,163,175,0.75)',
    };

    const datasets = {};
    LINKAGE_DATA.forEach(item => {
        const k = item.klasifikasi;
        if (!datasets[k]) {
            datasets[k] = {
                label: k, data: [],
                backgroundColor: colorMap[k] || 'rgba(156,163,175,0.75)',
                pointRadius:7, pointHoverRadius:9,
            };
        }
        datasets[k].data.push({ x: item.backward, y: item.forward, nama: item.nama });
    });

    new Chart(document.getElementById('chartScatter'), {
        type:'scatter',
        data:{ datasets: Object.values(datasets) },
        options:{
            responsive:true, maintainAspectRatio:false,
            plugins:{
                legend:{ position:'top', labels:{boxWidth:10, padding:10, font:{size:11}} },
                tooltip:{ callbacks:{ label:ctx=>`${ctx.raw.nama} (BL:${ctx.raw.x.toFixed(3)}, FL:${ctx.raw.y.toFixed(3)})` } }
            },
            scales:{
                x:{ title:{display:true, text:'Backward Linkage (Normalized)', font:{size:10}}, grid:{color:ctx=>ctx.tick.value===1?'#f97316':'#f3f4f6'} },
                y:{ title:{display:true, text:'Forward Linkage (Normalized)',  font:{size:10}}, grid:{color:ctx=>ctx.tick.value===1?'#f97316':'#f3f4f6'} },
            }
        }
    });
}
</script>
@endsection