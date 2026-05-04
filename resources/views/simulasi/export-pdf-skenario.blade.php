<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export PDF — Komparasi Skenario IO</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #111827; background: #f7f6f3; padding: 32px; }

        .print-bar { display:flex; gap:10px; margin-bottom:24px; }
        .btn-print {
            display:inline-flex; align-items:center; gap:6px; height:36px; padding:0 18px;
            font-size:13px; font-weight:500; color:#fff; background:#f97316; border:none;
            border-radius:8px; cursor:pointer;
        }
        .btn-close {
            display:inline-flex; align-items:center; gap:6px; height:36px; padding:0 18px;
            font-size:13px; font-weight:500; color:#374151; background:#f3f4f6;
            border:1px solid #e5e7eb; border-radius:8px; cursor:pointer; text-decoration:none;
        }

        .doc-header {
            display:flex; align-items:flex-start; justify-content:space-between;
            padding-bottom:16px; border-bottom:2px solid #f97316; margin-bottom:20px;
        }
        .doc-title  { font-size:18px; font-weight:700; }
        .doc-sub    { font-size:12px; color:#6b7280; margin-top:3px; }
        .doc-meta   { font-size:11px; color:#9ca3af; text-align:right; line-height:1.7; }

        .section-title {
            font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.6px;
            color:#9ca3af; margin:20px 0 10px; display:flex; align-items:center; gap:8px;
        }
        .section-title::after { content:''; flex:1; height:1px; background:#e5e7eb; }

        /* Metodologi note */
        .metodologi-note {
            display:flex; align-items:flex-start; gap:8px; padding:8px 12px;
            background:#fefce8; border:1px solid #fde68a; border-radius:6px;
            margin-bottom:14px; font-size:10.5px; color:#854d0e; line-height:1.6;
        }

        /* ── SEKTOR INJEKSI ── */
        .injeksi-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }
        .injeksi-panel {
            border-radius: 8px;
            overflow: hidden;
            border: 0.5px solid #e5e7eb;
        }
        .injeksi-panel-head {
            padding: 7px 12px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .injeksi-panel-head .stimulus-total {
            font-size: 10px;
            font-weight: 500;
            opacity: .85;
        }
        .injeksi-a .injeksi-panel-head { background:#fff7ed; color:#c2410c; border-bottom:0.5px solid #fed7aa; }
        .injeksi-b .injeksi-panel-head { background:#eff6ff; color:#1d4ed8; border-bottom:0.5px solid #bfdbfe; }
        .injeksi-panel-body {
            padding: 10px 12px;
            background: #fff;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .injeksi-chip {
            display: inline-flex;
            flex-direction: column;
            gap: 1px;
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 10px;
            min-width: 0;
        }
        .injeksi-a .injeksi-chip { background:#fff7ed; border:0.5px solid #fed7aa; }
        .injeksi-b .injeksi-chip { background:#eff6ff; border:0.5px solid #bfdbfe; }
        .injeksi-chip-name { font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }
        .injeksi-chip-val-a { font-weight: 700; color: #9a3412; font-family:'Courier New',monospace; font-size:10px; }
        .injeksi-chip-val-b { font-weight: 700; color: #1e40af; font-family:'Courier New',monospace; font-size:10px; }
        .injeksi-empty { font-size:10px; color:#9ca3af; font-style:italic; }

        /* Summary grid per skenario: 2 col */
        .sce-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:14px; }
        .sce-card { border-radius:10px; overflow:hidden; border:0.5px solid #e5e7eb; }
        .sce-card-head { padding:9px 14px; font-size:12px; font-weight:600; }
        .sce-a .sce-card-head { background:#fff7ed; color:#c2410c; border-bottom:0.5px solid #fed7aa; }
        .sce-b .sce-card-head { background:#eff6ff; color:#1d4ed8; border-bottom:0.5px solid #bfdbfe; }
        .sce-card-body { padding:10px 14px; background:#fff; }

        .metric-row { display:grid; grid-template-columns:repeat(4,1fr); gap:6px; }
        .metric-item { text-align:center; padding:8px 4px; border-radius:6px; border:0.5px solid #e5e7eb; background:#fafafa; }
        .metric-label { font-size:8.5px; font-weight:500; color:#9ca3af; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:3px; }
        .metric-val { font-size:12px; font-weight:700; }
        .metric-val.orange  { color:#ea580c; }
        .metric-val.green   { color:#16a34a; }
        .metric-val.teal    { color:#0d9488; }
        .metric-val.blue    { color:#2563eb; }

        .mult-strip {
            display:flex; align-items:center; gap:8px;
            padding:6px 12px; font-size:10.5px; font-weight:500; flex-wrap:wrap;
            border-top:0.5px solid;
        }
        .mult-strip-a { background:#fff7ed; border-color:#fed7aa; color:#92400e; }
        .mult-strip-b { background:#eff6ff; border-color:#bfdbfe; color:#1e40af; }
        .mult-strip strong { font-weight:700; }

        /* Diff box */
        .diff-card {
            display:grid; grid-template-columns:repeat(4,1fr);
            border:0.5px solid #e5e7eb; border-radius:8px; overflow:hidden;
            margin-bottom:14px;
        }
        .diff-item {
            text-align:center; padding:12px 8px;
            border-right:0.5px solid #f3f4f6;
        }
        .diff-item:last-child { border-right:none; }
        .diff-label { font-size:9px; color:#9ca3af; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:4px; font-weight:500; }
        .diff-val { font-size:15px; font-weight:700; }
        .diff-val.pos { color:#16a34a; }
        .diff-val.neg { color:#dc2626; }
        .diff-sub { font-size:9px; color:#9ca3af; margin-top:2px; }

        /* Charts */
        .charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px; }
        .chart-label { font-size:11px; font-weight:600; color:#374151; margin-bottom:6px; }
        .chart-container { position:relative; width:100%; height:240px; }
        .chart-container canvas { position:absolute; top:0; left:0; width:100% !important; height:100% !important; }

        /* Table */
        table { width:100%; border-collapse:collapse; font-size:11px; margin-bottom:12px; }
        thead th {
            font-size:9.5px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;
            color:#6b7280; padding:7px 9px; border-bottom:1.5px solid #e5e7eb;
            background:#f9fafb; text-align:right; white-space:nowrap;
        }
        thead th:first-child { text-align:left; }
        thead th.th-a     { background:#fff7ed; color:#c2410c; }
        thead th.th-a-ntb { background:#fff3e8; color:#b45309; }
        thead th.th-b     { background:#eff6ff; color:#1d4ed8; }
        thead th.th-b-ntb { background:#e8f1ff; color:#1d4ed8; opacity:.85; }
        thead th.th-diff  { background:#f5f3ff; color:#5b21b6; }
        thead th.th-diff-ntb { background:#f0fdfa; color:#0d9488; }
        tbody tr:nth-child(even) td { background:#fafafa; }
        tbody tr:not(:last-child) td { border-bottom:0.5px solid #f0f0f0; }
        tbody td { padding:6px 9px; text-align:right; color:#374151; vertical-align:middle; }
        tbody td:first-child { text-align:left; font-weight:500; color:#111827; }
        tbody td.td-ntb-a { background:#fff7ed; }
        tbody td.td-ntb-b { background:#eff6ff; }
        .td-mono { font-family:'Courier New',monospace; font-size:10.5px; }
        .diff-plus  { color:#16a34a; font-weight:600; }
        .diff-minus { color:#dc2626; font-weight:600; }
        .diff-zero  { color:#9ca3af; }
        .klas { display:inline-block; font-size:8.5px; font-weight:600; padding:1px 5px; border-radius:20px; }
        .klas-kunci      { background:#fef9c3; color:#854d0e; }
        .klas-hilir      { background:#eff6ff; color:#1d4ed8; }
        .klas-hulu       { background:#f0fdf4; color:#166534; }
        .klas-independen { background:#f3f4f6; color:#6b7280; }

        /* Footer */
        .doc-footer {
            margin-top:24px; padding-top:10px; border-top:1px solid #e5e7eb;
            display:flex; justify-content:space-between; font-size:10px; color:#9ca3af;
        }

        .page-section { margin-bottom:48px; }

        @media print {
            @page { size: A4; margin: 14mm 14mm 14mm 14mm; }
            html, body { margin:0 !important; padding:0 !important; background:#fff; }
            .print-bar { display:none !important; }
            .page-section {
                page-break-before:always; page-break-after:always;
                page-break-inside:avoid; break-before:page; break-after:page;
                break-inside:avoid; margin:0; padding:0;
                min-height:100vh; display:flex; flex-direction:column;
            }
            .page-section:first-child { page-break-before:avoid; break-before:avoid; }
            .charts-grid { grid-template-columns:1fr 1fr !important; flex:1; }
            .chart-container { height:200px !important; }
            .sce-grid { grid-template-columns:1fr 1fr !important; }
            .diff-card { grid-template-columns:repeat(4,1fr) !important; }
            .injeksi-grid { grid-template-columns:1fr 1fr !important; page-break-inside:avoid; }
            table { page-break-inside:auto; }
            tr { page-break-inside:avoid; }
        }
    </style>
</head>
<body>

@php
    $ntbA   = $summary_a['tambahan_ntb']    ?? 0;
    $ntbB   = $summary_b['tambahan_ntb']    ?? 0;
    $mNtbA  = $summary_a['multiplier_ntb']  ?? 0;
    $mNtbB  = $summary_b['multiplier_ntb']  ?? 0;
    $mOutA  = $summary_a['multiplier_output'] ?? $summary_a['multiplier'] ?? 0;
    $mOutB  = $summary_b['multiplier_output'] ?? $summary_b['multiplier'] ?? 0;

    $diffStimulus   = $summary_b['total_stimulus']  - $summary_a['total_stimulus'];
    $diffTambahan   = $summary_b['tambahan_output'] - $summary_a['tambahan_output'];
    $diffNtb        = $ntbB - $ntbA;
    $diffMultiplier = $mOutB - $mOutA;

    function fmtRpS($val) {
        $v = (float)$val;
        return floor($v) == $v
            ? number_format($v, 0, ',', '.')
            : rtrim(rtrim(number_format($v, 3, ',', '.'), '0'), ',');
    }
    function fmtDiffS($val) {
        $v = abs((float)$val);
        return floor($v) == $v
            ? number_format($v, 0, ',', '.')
            : rtrim(rtrim(number_format($v, 3, ',', '.'), '0'), ',');
    }
@endphp

<div class="print-bar">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    <a href="{{ route('simulasi.skenario') }}" class="btn-close">✕ Tutup</a>
</div>

{{-- ═══════════════════════════════════════
     HALAMAN 1: Ringkasan & Grafik
═══════════════════════════════════════ --}}
<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Komparasi Skenario IO</div>
            <div class="doc-sub">Model Leontief — Perbandingan Dua Skenario Stimulus</div>
        </div>
        <div class="doc-meta">
            Skenario A: <strong>{{ $summary_a['label'] }}</strong> ({{ $summary_a['dataset'] }}, {{ $summary_a['tahun'] }})<br>
            Skenario B: <strong>{{ $summary_b['label'] }}</strong> ({{ $summary_b['dataset'] }}, {{ $summary_b['tahun'] }})<br>
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <div class="metodologi-note">
        ⚠ <strong>Catatan Metodologi:</strong>
        Dampak PDRB dihitung dari <strong>ΔNTB</strong> (Nilai Tambah Bruto tambahan), bukan dari ΔOutput mentah.
    </div>

    {{-- ── SEKTOR YANG DIINJEKSI ── --}}
    <div class="section-title">Sektor yang Diinjeksi</div>
    <div class="injeksi-grid">

        {{-- Skenario A --}}
        <div class="injeksi-panel injeksi-a">
            <div class="injeksi-panel-head">
                🔶 {{ $summary_a['label'] }}
                <span class="stimulus-total">
                    Total: {{ fmtRpS($summary_a['total_stimulus']) }} M Rp
                </span>
            </div>
            <div class="injeksi-panel-body">
                @if(!empty($summary_a['sektor_injeksi']))
                    @foreach($summary_a['sektor_injeksi'] as $si)
                    <div class="injeksi-chip">
                        <span class="injeksi-chip-name">{{ $si['nama'] }}</span>
                        <span class="injeksi-chip-val-a">
                            Rp {{ fmod($si['nilai'], 1) == 0
                                ? number_format($si['nilai'], 0, ',', '.')
                                : number_format($si['nilai'], 3, ',', '.') }} M
                        </span>
                    </div>
                    @endforeach
                @else
                    <span class="injeksi-empty">Data sektor injeksi tidak tersedia</span>
                @endif
            </div>
        </div>

        {{-- Skenario B --}}
        <div class="injeksi-panel injeksi-b">
            <div class="injeksi-panel-head">
                🔷 {{ $summary_b['label'] }}
                <span class="stimulus-total">
                    Total: {{ fmtRpS($summary_b['total_stimulus']) }} M Rp
                </span>
            </div>
            <div class="injeksi-panel-body">
                @if(!empty($summary_b['sektor_injeksi']))
                    @foreach($summary_b['sektor_injeksi'] as $si)
                    <div class="injeksi-chip">
                        <span class="injeksi-chip-name">{{ $si['nama'] }}</span>
                        <span class="injeksi-chip-val-b">
                            Rp {{ fmod($si['nilai'], 1) == 0
                                ? number_format($si['nilai'], 0, ',', '.')
                                : number_format($si['nilai'], 3, ',', '.') }} M
                        </span>
                    </div>
                    @endforeach
                @else
                    <span class="injeksi-empty">Data sektor injeksi tidak tersedia</span>
                @endif
            </div>
        </div>

    </div>

    <div class="section-title">Ringkasan per Skenario</div>
    <div class="sce-grid">

        {{-- Skenario A --}}
        <div class="sce-card sce-a">
            <div class="sce-card-head">
                🔶 {{ $summary_a['label'] }}
                <span style="font-size:10px; font-weight:400; color:#92400e; margin-left:6px;">{{ $summary_a['dataset'] }} ({{ $summary_a['tahun'] }})</span>
            </div>
            <div class="sce-card-body">
                <div class="metric-row">
                    <div class="metric-item">
                        <div class="metric-label">Total Stimulus</div>
                        <div class="metric-val orange">{{ fmtRpS($summary_a['total_stimulus']) }}</div>
                        <div style="font-size:8px;color:#9ca3af;">M Rp</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">ΔX Output</div>
                        <div class="metric-val green">+{{ fmtRpS($summary_a['tambahan_output']) }}</div>
                        <div style="font-size:8px;color:#9ca3af;">M Rp</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">ΔNTB</div>
                        <div class="metric-val teal">+{{ fmtRpS($ntbA) }}</div>
                        <div style="font-size:8px;color:#9ca3af;">M Rp</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Multiplier</div>
                        <div class="metric-val blue">{{ number_format($mOutA, 4) }}×</div>
                        <div style="font-size:8px;color:#9ca3af;">Output</div>
                    </div>
                </div>
            </div>
            <div class="mult-strip mult-strip-a">
                ⬆ Output Multiplier: <strong>{{ number_format($mOutA, 4) }}×</strong>
                @if($mNtbA > 0) &nbsp;·&nbsp; NTB Multiplier: <strong>{{ number_format($mNtbA, 4) }}×</strong> @endif
            </div>
        </div>

        {{-- Skenario B --}}
        <div class="sce-card sce-b">
            <div class="sce-card-head">
                🔷 {{ $summary_b['label'] }}
                <span style="font-size:10px; font-weight:400; color:#1e40af; margin-left:6px;">{{ $summary_b['dataset'] }} ({{ $summary_b['tahun'] }})</span>
            </div>
            <div class="sce-card-body">
                <div class="metric-row">
                    <div class="metric-item">
                        <div class="metric-label">Total Stimulus</div>
                        <div class="metric-val blue">{{ fmtRpS($summary_b['total_stimulus']) }}</div>
                        <div style="font-size:8px;color:#9ca3af;">M Rp</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">ΔX Output</div>
                        <div class="metric-val green">+{{ fmtRpS($summary_b['tambahan_output']) }}</div>
                        <div style="font-size:8px;color:#9ca3af;">M Rp</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">ΔNTB</div>
                        <div class="metric-val teal">+{{ fmtRpS($ntbB) }}</div>
                        <div style="font-size:8px;color:#9ca3af;">M Rp</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Multiplier</div>
                        <div class="metric-val blue">{{ number_format($mOutB, 4) }}×</div>
                        <div style="font-size:8px;color:#9ca3af;">Output</div>
                    </div>
                </div>
            </div>
            <div class="mult-strip mult-strip-b">
                ⬆ Output Multiplier: <strong>{{ number_format($mOutB, 4) }}×</strong>
                @if($mNtbB > 0) &nbsp;·&nbsp; NTB Multiplier: <strong>{{ number_format($mNtbB, 4) }}×</strong> @endif
            </div>
        </div>
    </div>

    <div class="section-title">Selisih (B − A)</div>
    <div class="diff-card">
        <div class="diff-item">
            <div class="diff-label">Δ Total Stimulus</div>
            <div class="diff-val {{ $diffStimulus >= 0 ? 'pos' : 'neg' }}">
                {{ $diffStimulus >= 0 ? '+' : '-' }}{{ fmtDiffS($diffStimulus) }}
            </div>
            <div class="diff-sub">Miliar Rp</div>
        </div>
        <div class="diff-item">
            <div class="diff-label">Δ ΔX Output</div>
            <div class="diff-val {{ $diffTambahan >= 0 ? 'pos' : 'neg' }}">
                {{ $diffTambahan >= 0 ? '+' : '-' }}{{ fmtDiffS($diffTambahan) }}
            </div>
            <div class="diff-sub">Miliar Rp</div>
        </div>
        <div class="diff-item" style="background:#f0fdfa;">
            <div class="diff-label" style="color:#0d9488;">Δ ΔNTB</div>
            <div class="diff-val {{ $diffNtb >= 0 ? 'pos' : 'neg' }}">
                {{ $diffNtb >= 0 ? '+' : '-' }}{{ fmtDiffS($diffNtb) }}
            </div>
            <div class="diff-sub">Miliar Rp</div>
        </div>
        <div class="diff-item">
            <div class="diff-label">Δ Multiplier</div>
            <div class="diff-val {{ $diffMultiplier >= 0 ? 'pos' : 'neg' }}">
                {{ $diffMultiplier >= 0 ? '+' : '' }}{{ number_format($diffMultiplier, 4, ',', '.') }}×
            </div>
            <div class="diff-sub">B lebih {{ $diffMultiplier >= 0 ? 'efisien' : 'rendah' }}</div>
        </div>
    </div>

    <div class="section-title">Visualisasi Perbandingan</div>
    <div class="charts-grid">
        <div>
            <div class="chart-label">Tambahan Output ΔX per Sektor (M Rp)</div>
            <div class="chart-container"><canvas id="chartDx"></canvas></div>
        </div>
        <div>
            <div class="chart-label">Tambahan NTB ΔNTB per Sektor (M Rp)</div>
            <div class="chart-container"><canvas id="chartNtb"></canvas></div>
        </div>
    </div>

    <div class="doc-footer">
        <span>Komparasi Skenario — Simulasi Input-Output — Model Leontief</span>
        <span>Halaman 1 dari 2 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>

</div>

{{-- ═══════════════════════════════════════
     HALAMAN 2: Tabel Detail per Sektor
═══════════════════════════════════════ --}}
<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Komparasi Skenario IO</div>
            <div class="doc-sub">Model Leontief — Detail Dampak per Sektor</div>
        </div>
        <div class="doc-meta">
            Skenario A: <strong>{{ $summary_a['label'] }}</strong><br>
            Skenario B: <strong>{{ $summary_b['label'] }}</strong><br>
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <div class="section-title">Perbandingan Dampak per Sektor (Miliar Rp)</div>
    @php $hasilBMap = collect($hasil_b)->keyBy('sektor'); @endphp
    <table>
        <thead>
            <tr>
                <th style="text-align:left;">Sektor</th>
                <th class="th-a">{{ $summary_a['label'] }}<br><span style="font-weight:400;font-size:8px;text-transform:none;">ΔX Output</span></th>
                <th class="th-a-ntb">{{ $summary_a['label'] }}<br><span style="font-weight:400;font-size:8px;text-transform:none;">ΔNTB</span></th>
                <th class="th-b">{{ $summary_b['label'] }}<br><span style="font-weight:400;font-size:8px;text-transform:none;">ΔX Output</span></th>
                <th class="th-b-ntb">{{ $summary_b['label'] }}<br><span style="font-weight:400;font-size:8px;text-transform:none;">ΔNTB</span></th>
                <th class="th-diff">Selisih ΔX<br><span style="font-weight:400;font-size:8px;text-transform:none;">(B − A)</span></th>
                <th class="th-diff-ntb">Selisih ΔNTB<br><span style="font-weight:400;font-size:8px;text-transform:none;">(B − A)</span></th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasil_a as $row)
            @php
                $rb       = $hasilBMap[$row['sektor']] ?? null;
                $dxA      = $row['tambahan_output'];
                $ntbRowA  = $row['tambahan_ntb']  ?? 0;
                $dxB      = $rb ? $rb['tambahan_output'] : 0;
                $ntbRowB  = $rb ? ($rb['tambahan_ntb'] ?? 0) : 0;
                $diffDx   = $dxB - $dxA;
                $diffNtbR = $ntbRowB - $ntbRowA;
                $klasMap  = ['Kunci'=>'klas-kunci','Hilir'=>'klas-hilir','Hulu'=>'klas-hulu','Independen'=>'klas-independen'];
                $kc       = $klasMap[$row['klasifikasi'] ?? 'Independen'] ?? 'klas-independen';
            @endphp
            <tr>
                <td style="font-size:10px;">{{ $row['nama'] }}</td>
                <td class="td-mono">{{ fmtRpS($dxA) }}</td>
                <td class="td-mono td-ntb-a">{{ fmtRpS($ntbRowA) }}</td>
                <td class="td-mono">{{ $rb ? fmtRpS($dxB) : '—' }}</td>
                <td class="td-mono td-ntb-b">{{ $rb ? fmtRpS($ntbRowB) : '—' }}</td>
                <td>
                    @if(abs($diffDx) < 0.001)
                        <span class="diff-zero td-mono">≈ 0</span>
                    @elseif($diffDx > 0)
                        <span class="diff-plus td-mono">+{{ fmtDiffS($diffDx) }}</span>
                    @else
                        <span class="diff-minus td-mono">-{{ fmtDiffS($diffDx) }}</span>
                    @endif
                </td>
                <td>
                    @if(abs($diffNtbR) < 0.001)
                        <span class="diff-zero td-mono">≈ 0</span>
                    @elseif($diffNtbR > 0)
                        <span class="diff-plus td-mono" style="color:#0d9488;">+{{ fmtDiffS($diffNtbR) }}</span>
                    @else
                        <span class="diff-minus td-mono">-{{ fmtDiffS($diffNtbR) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="padding:7px 10px; background:#f9fafb; border:0.5px solid #e5e7eb; border-radius:6px; font-size:9.5px; color:#6b7280; line-height:1.8; margin-bottom:16px;">
        <strong style="color:#374151;">Keterangan:</strong>
        ΔX = tambahan output bruto ·
        <span style="color:#0d9488; font-weight:600;">ΔNTB</span> = ΔX × (1 − Rasio Biaya Antara) → dasar dampak PDRB ·
        Selisih = B minus A (positif berarti B lebih besar)
    </div>

    <div class="doc-footer">
        <span>Komparasi Skenario — Simulasi Input-Output — Model Leontief</span>
        <span>Halaman 2 dari 2 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>

</div>

<script>
Chart.defaults.font.family = 'Segoe UI, Arial, sans-serif';
Chart.defaults.font.size   = 10;
Chart.defaults.color       = '#6b7280';

const HASIL_A = @json($hasil_a);
const HASIL_B = @json($hasil_b);
const LABEL_A = '{{ addslashes($summary_a["label"] ?? "Skenario A") }}';
const LABEL_B = '{{ addslashes($summary_b["label"] ?? "Skenario B") }}';

window.addEventListener('load', function () {
    const labels = HASIL_A.map(r => r.nama.length > 20 ? r.nama.substr(0,18)+'…' : r.nama);
    const tickSz = Math.max(7, Math.min(9, Math.floor(200 / labels.length)));

    const commonOpts = () => ({
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 0 },
        plugins: {
            legend: { position:'top', labels:{ boxWidth:10, padding:10, font:{size:10} } },
            tooltip: { enabled: false }
        },
        scales: {
            x: { grid:{ color:'#f3f4f6' } },
            y: { grid:{ display:false }, ticks:{ font:{ size: tickSz } } }
        }
    });

    new Chart(document.getElementById('chartDx'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: LABEL_A, data: HASIL_A.map(r => parseFloat((r.tambahan_output||0).toFixed(3))), backgroundColor:'rgba(249,115,22,0.75)', borderRadius:2 },
                { label: LABEL_B, data: HASIL_B.map(r => parseFloat((r.tambahan_output||0).toFixed(3))), backgroundColor:'rgba(59,130,246,0.75)',  borderRadius:2 },
            ]
        },
        options: commonOpts()
    });

    new Chart(document.getElementById('chartNtb'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: LABEL_A, data: HASIL_A.map(r => parseFloat((r.tambahan_ntb||0).toFixed(3))), backgroundColor:'rgba(245,158,11,0.75)', borderRadius:2 },
                { label: LABEL_B, data: HASIL_B.map(r => parseFloat((r.tambahan_ntb||0).toFixed(3))), backgroundColor:'rgba(20,184,166,0.75)',  borderRadius:2 },
            ]
        },
        options: commonOpts()
    });
});
</script>

</body>
</html>