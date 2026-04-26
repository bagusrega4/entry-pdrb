<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export PDF — Simulasi Input Output</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #111827; background: #f7f6f3; padding: 32px; }

        .print-bar { display:flex; gap:10px; margin-bottom:24px; }
        .btn-print {
            display:inline-flex; align-items:center; gap:6px; height:36px; padding:0 18px;
            font-size:13px; font-weight:500; color:#fff; background:#f97316; border:none;
            border-radius:8px; cursor:pointer; font-family:'Segoe UI',sans-serif;
        }
        .btn-close {
            display:inline-flex; align-items:center; gap:6px; height:36px; padding:0 18px;
            font-size:13px; font-weight:500; color:#374151; background:#f3f4f6;
            border:1px solid #e5e7eb; border-radius:8px; cursor:pointer;
            text-decoration:none; font-family:'Segoe UI',sans-serif;
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

        /* Multiplier box */
        .multiplier-box {
            display:flex; align-items:center; gap:8px; padding:10px 14px;
            background:#f0fdf4; border:1px solid #86efac; border-radius:8px;
            margin-bottom:14px; font-size:12px; color:#166534; flex-wrap:wrap;
        }
        .multiplier-box strong { font-size:14px; }

        .multiplier-stimulus {
            display:inline-flex; align-items:center; gap:6px; padding:10px 14px;
            background:#eef2ff; border:1px solid #a5b4fc; border-radius:8px;
            margin-bottom:14px; font-size:12px; color:#3730a3; flex-wrap:wrap;
        }
        .multiplier-stimulus strong { font-size:14px; }

        /* Summary grid: 2 rows × 3 cols */
        .summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:16px; }

        .sc {
            position:relative; overflow:hidden;
            border:1px solid #e5e7eb; border-radius:8px; padding:10px 12px;
            display:flex; flex-direction:column; gap:4px;
        }
        .sc::before {
            content:''; position:absolute; top:0; left:0; right:0; height:3px;
            border-radius:8px 8px 0 0;
        }
        .sc-orange::before  { background:#f97316; }
        .sc-blue::before    { background:#3b82f6; }
        .sc-green::before   { background:#22c55e; }
        .sc-teal::before    { background:#14b8a6; }
        .sc-purple::before  { background:#8b5cf6; }
        .sc-emerald::before { background:#10b981; }

        .sc-row-label {
            grid-column: 1 / -1;
            font-size:9px; font-weight:600; text-transform:uppercase;
            letter-spacing:0.6px; color:#9ca3af;
            display:flex; align-items:center; gap:8px; margin-bottom:-4px;
        }
        .sc-row-label::after { content:''; flex:1; height:1px; background:#e5e7eb; }
        .sc-divider { grid-column:1/-1; height:1px; background:#e5e7eb; margin:2px 0; }

        .s-label { font-size:9px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#9ca3af; margin-bottom:3px; }
        .s-val   { font-size:13px; font-weight:700; color:#111827; line-height:1.2; }
        .s-val.orange  { color:#ea580c; }
        .s-val.blue    { color:#2563eb; }
        .s-val.green   { color:#16a34a; }
        .s-val.teal    { color:#0d9488; }
        .s-val.purple  { color:#7c3aed; }
        .s-val.emerald { color:#059669; }
        .s-sub   { font-size:9px; color:#9ca3af; margin-top:1px; }

        /* Charts 2-col */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
            align-items: start;
        }
        @media (max-width: 600px) { .charts-grid { grid-template-columns: 1fr; } }

        .chart-wrap { }
        .chart-label { font-size:11px; font-weight:600; color:#374151; margin-bottom:6px; }
        .chart-container {
            position: relative;
            width: 100%;
            height: 240px;
        }
        .chart-container canvas {
            position: absolute;
            top: 0; left: 0;
            width: 100% !important;
            height: 100% !important;
        }

        /* Tables */
        table { width:100%; border-collapse:collapse; font-size:11px; margin-bottom:16px; }
        thead th {
            font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;
            color:#6b7280; padding:8px 10px; border-bottom:1.5px solid #e5e7eb;
            background:#f9fafb; text-align:center;
        }
        thead th:first-child  { text-align:left; }
        thead th:nth-child(2) { text-align:left; }
        thead th.th-ntb { background:#f0fdfa; color:#0d9488; }
        tbody tr:nth-child(even) td { background:#fafafa; }
        tbody tr:not(:last-child) td { border-bottom:0.5px solid #f0f0f0; }
        tbody td { padding:7px 10px; text-align:right; color:#374151; vertical-align:middle; }
        tbody td:first-child  { text-align:center; color:#9ca3af; }
        tbody td:nth-child(2) { text-align:left; font-weight:500; color:#111827; }
        tbody td.td-ntb { background:#f0fdfa; }
        .td-mono { font-family:'Courier New',monospace; font-size:11px; }
        .gr-pos  { color:#16a34a; font-weight:600; }
        .gr-neg  { color:#991b1b; font-weight:600; }
        .gr-teal { color:#0d9488; font-weight:600; }

        /* Linkage table */
        .link-bar { height:6px; border-radius:3px; display:inline-block; vertical-align:middle; margin-right:4px; }
        .link-back { background:#f97316; }
        .link-fwd  { background:#3b82f6; }

        .klas { display:inline-block; font-size:9px; font-weight:600; padding:1px 6px; border-radius:20px; }
        .klas-kunci      { background:#fef9c3; color:#854d0e; }
        .klas-hilir      { background:#eff6ff; color:#1d4ed8; }
        .klas-hulu       { background:#f0fdf4; color:#166534; }
        .klas-independen { background:#f3f4f6; color:#6b7280; }

        /* Footer */
        .doc-footer {
            margin-top:24px; padding-top:12px; border-top:1px solid #e5e7eb;
            display:flex; justify-content:space-between; font-size:10px; color:#9ca3af;
        }

        .page-section { margin-bottom: 48px; }

        /* Print overrides */
        @media print {
            @page { size: A4; margin: 14mm 14mm 14mm 14mm; }

            html, body { margin:0 !important; padding:0 !important; background:#fff; }
            .print-bar { display:none !important; }

            .page-section {
                page-break-before: always; page-break-after: always;
                page-break-inside: avoid; break-before: page; break-after: page;
                break-inside: avoid; margin:0; padding:0;
                min-height:100vh; display:flex; flex-direction:column;
            }
            .page-section:first-child { page-break-before:avoid; break-before:avoid; }

            .charts-grid { grid-template-columns:1fr 1fr !important; flex:1; }
            .chart-container { height:200px !important; }

            .multiplier-box, .summary-grid { page-break-inside:avoid; break-inside:avoid; }

            .linkage-section table, .dampak-table { page-break-inside:auto; }
            .linkage-section tr, .dampak-table tr { page-break-inside:avoid; }
        }
    </style>
</head>
<body>

@php
    $mOutput     = $summary['multiplier_output'] ?? $summary['multiplier'] ?? 0;
    $mNtb        = $summary['multiplier_ntb']    ?? 0;

    $adhbAwal    = $summary['pdrb_adhb_awal']   ?? $summary['pdrb_awal']  ?? 0;
    $adhbBaru    = $summary['pdrb_adhb_baru']   ?? $summary['pdrb_baru']  ?? 0;
    $adhkAwal    = $summary['pdrb_adhk_awal']   ?? 0;
    $adhkBaru    = $summary['pdrb_adhk_baru']   ?? 0;
    $persenAdhb  = $summary['persen_naik_adhb'] ?? $summary['persen_naik'] ?? 0;
    $persenAdhk  = $summary['persen_naik_adhk'] ?? 0;
    $tambahanNtb = $summary['tambahan_ntb']      ?? 0;
    $tambahanOut = $summary['tambahan_output']   ?? 0;
@endphp

<div class="print-bar">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    <a href="{{ route('simulasi.index') }}" class="btn-close">✕ Tutup</a>
</div>

<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Simulasi Input-Output</div>
            <div class="doc-sub">Model Leontief — Analisis Dampak Stimulus Ekonomi</div>
        </div>
        <div class="doc-meta">
            Dataset: <strong>{{ $summary['dataset'] }}</strong><br>
            Tahun IO: <strong>{{ $summary['tahun'] }}</strong><br>
            Ref PDRB: <strong>{{ $summary['pdrb_tahun'] ?? '-' }} {{ $summary['pdrb_triwulan'] ?? '' }}</strong><br>
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <!-- Metodologi note -->
    <div class="metodologi-note">
        ⚠ <strong>Catatan Metodologi:</strong>
        Dampak PDRB dihitung dari <strong>ΔNTB,</strong> bukan dari ΔOutput mentah.
    </div>

    <!-- Total Stimulus strip -->
    @php
        $stimulusFormatted = fmod($summary['total_stimulus'], 1) == 0
            ? number_format($summary['total_stimulus'], 0, ',', '.')
            : number_format($summary['total_stimulus'], 3, ',', '.');
    @endphp

    @if(($summary['total_stimulus'] ?? 0) > 0)
    <div class="multiplier-box" style="background:#eef2ff; border-color:#a5b4fc; color:#3730a3; margin-bottom:10px;">
        <span>💰</span>
        Total Stimulus: <strong>{{ $stimulusFormatted }} M Rp</strong>
        &nbsp;—&nbsp; nilai injeksi ke perekonomian
    </div>
    @endif

    <!-- Multiplier strip -->
    @if($mOutput > 0)
    <div class="multiplier-box">
        <span>⬆</span>
        Output Multiplier: <strong>{{ number_format($mOutput, 4) }}×</strong>
        @if($mNtb > 0)
            &nbsp;·&nbsp; NTB Multiplier: <strong>{{ number_format($mNtb, 4) }}×</strong>
        @endif
        &nbsp;—&nbsp; setiap 1 M Rp stimulus
    </div>
    @endif

    <!-- Sektor yang Diinjeksi -->
    @if(!empty($summary['sektor_injeksi']))
    <div class="section-title">Sektor yang Diinjeksi</div>
    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px;">
        @foreach($summary['sektor_injeksi'] as $si)
        <div style="display:inline-flex; align-items:center; gap:10px;
            background:#fff7ed; border:1px solid #fed7aa; border-radius:7px;
            padding:7px 12px; font-size:11px;">
            <div style="display:flex; flex-direction:column; gap:2px;">
                <span style="font-weight:600; color:#111827;">{{ $si['nama'] }}</span>
                <span style="font-weight:700; color:#9a3412; font-family:'Courier New',monospace; font-size:11px;">
                    Rp {{ fmod($si['nilai'], 1) == 0
                        ? number_format($si['nilai'], 0, ',', '.')
                        : number_format($si['nilai'], 3, ',', '.') }} M
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Summary Cards (ADHB + ADHK) -->
    <div class="section-title">Ringkasan Makro</div>
    <div class="summary-grid">

        <div class="sc-row-label">ADHB — Atas Dasar Harga Berlaku</div>

        <div class="sc sc-orange">
            <div class="s-label">PDRB ADHB Awal</div>
            <div class="s-val orange" style="font-size:12px;">Rp {{ number_format($adhbAwal, 3, ',', '.') }} M</div>
            <div class="s-sub">{{ $summary['pdrb_tahun'] ?? '-' }} {{ $summary['pdrb_triwulan'] ?? '' }}</div>
        </div>
        <div class="sc sc-blue">
            <div class="s-label">Dampak thd PDRB ADHB</div>
            <div class="s-val blue">{{ number_format($persenAdhb, 4) }}%</div>
            <div class="s-sub">via ΔNTB</div>
        </div>
        <div class="sc sc-green">
            <div class="s-label">PDRB ADHB Baru Estimasi</div>
            <div class="s-val green" style="font-size:12px;">Rp {{ number_format($adhbBaru, 3, ',', '.') }} M</div>
            <div class="s-sub">Rp +{{ number_format($adhbBaru - $adhbAwal, 3, ',', '.') }} M</div>
        </div>

        <div class="sc-divider"></div>
        <div class="sc-row-label">ADHK — Atas Dasar Harga Konstan (riil)</div>

        <div class="sc sc-teal">
            <div class="s-label">PDRB ADHK Awal</div>
            <div class="s-val teal" style="font-size:12px;">Rp {{ $adhkAwal > 0 ? number_format($adhkAwal, 3, ',', '.') : '—' }} M</div>
            <div class="s-sub">Harga konstan</div>
        </div>
        <div class="sc sc-purple">
            <div class="s-label">ΔNTB Tambahan NTB</div>
            <div class="s-val purple" style="font-size:12px;">Rp +{{ number_format($tambahanNtb, 3, ',', '.') }} M</div>
            <div class="s-sub">Dasar dampak PDRB</div>
        </div>
        <div class="sc sc-emerald">
            <div class="s-label">PDRB ADHK Baru Estimasi</div>
            <div class="s-val emerald" style="font-size:12px;">Rp {{ $adhkBaru > 0 ? number_format($adhkBaru, 3, ',', '.') : '—' }} M</div>
            <div class="s-sub">ΔNTB riil: Rp +{{ $adhkBaru > 0 ? number_format($adhkBaru - $adhkAwal, 3, ',', '.') : '—' }} M</div>
        </div>

    </div>

    <!-- Grafik ΔX Output + ΔNTB -->
    <div class="section-title">Visualisasi Hasil</div>
    <div class="charts-grid">
        <div class="chart-wrap">
            <div class="chart-label">Tambahan Output ΔX per Sektor (Miliar Rp)</div>
            <div class="chart-container"><canvas id="chartDampak"></canvas></div>
        </div>
        <div class="chart-wrap">
            <div class="chart-label">Tambahan NTB ΔNTB per Sektor (Miliar Rp)</div>
            <div class="chart-container"><canvas id="chartNtb"></canvas></div>
        </div>
    </div>

    <div class="doc-footer">
        <span>Simulasi Input-Output — Model Leontief</span>
        <span>Halaman 1 dari 3 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>

</div>

<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Simulasi Input-Output</div>
            <div class="doc-sub">Model Leontief — Peta Keterkaitan Antar Sektor</div>
        </div>
        <div class="doc-meta">
            Dataset: <strong>{{ $summary['dataset'] }}</strong><br>
            Tahun IO: <strong>{{ $summary['tahun'] }}</strong><br>
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <div class="section-title">Peta Keterkaitan (Linkage Analysis)</div>
    <div class="linkage-section">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th style="text-align:left;">Sektor</th>
                    <th>BL (Norm)</th>
                    <th>FL (Norm)</th>
                    <th>Klasifikasi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $maxB = max(array_column($hasil, 'norm_backward') ?: [1]);
                    $maxF = max(array_column($hasil, 'norm_forward')  ?: [1]);
                @endphp
                @foreach($hasil as $i => $row)
                @php
                    $klasMap = ['Kunci'=>'klas-kunci','Hilir'=>'klas-hilir','Hulu'=>'klas-hulu','Independen'=>'klas-independen'];
                    $kc = $klasMap[$row['klasifikasi'] ?? 'Independen'] ?? 'klas-independen';
                    $pB = $maxB > 0 ? min(60, ($row['norm_backward'] ?? 0) / $maxB * 60) : 0;
                    $pF = $maxF > 0 ? min(60, ($row['norm_forward']  ?? 0) / $maxF * 60) : 0;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="text-align:left; font-size:10px;">{{ $row['nama'] }}</td>
                    <td>
                        <span class="link-bar link-back" style="width:{{ $pB }}px;"></span>
                        <span class="td-mono">{{ number_format($row['norm_backward'] ?? 0, 3) }}</span>
                    </td>
                    <td>
                        <span class="link-bar link-fwd" style="width:{{ $pF }}px;"></span>
                        <span class="td-mono">{{ number_format($row['norm_forward'] ?? 0, 3) }}</span>
                    </td>
                    <td><span class="klas {{ $kc }}">{{ $row['klasifikasi'] ?? '-' }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
            <span class="klas klas-kunci">🔑 Kunci (BL≥1, FL≥1)</span>
            <span class="klas klas-hilir">⬇ Hilir (BL≥1, FL&lt;1)</span>
            <span class="klas klas-hulu">⬆ Hulu (BL&lt;1, FL≥1)</span>
            <span class="klas klas-independen">◎ Independen (BL&lt;1, FL&lt;1)</span>
        </div>
    </div>

    <div class="doc-footer">
        <span>Simulasi Input-Output — Model Leontief</span>
        <span>Halaman 2 dari 3 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>

</div>

<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Simulasi Input-Output</div>
            <div class="doc-sub">Model Leontief — Dampak per Sektor</div>
        </div>
        <div class="doc-meta">
            Dataset: <strong>{{ $summary['dataset'] }}</strong><br>
            Tahun IO: <strong>{{ $summary['tahun'] }}</strong><br>
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <div class="section-title">Dampak per Sektor</div>
    <table class="dampak-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Sektor</th>
                <th>Output Awal<br><span style="font-weight:400;font-size:8.5px;text-transform:none;">(M Rp)</span></th>
                <th>ΔX Output<br><span style="font-weight:400;font-size:8.5px;text-transform:none;">(M Rp)</span></th>
                <th class="th-ntb">ΔNTB<br><span style="font-weight:400;font-size:8.5px;text-transform:none;">(M Rp)</span></th>
                <th>Output Baru<br><span style="font-weight:400;font-size:8.5px;text-transform:none;">(M Rp)</span></th>
                <th>% Dampak</th>
                <th>Klasifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasil as $i => $row)
            @php
                $pct     = $row['persen_dampak'];
                $ntbRow  = $row['tambahan_ntb']      ?? 0;
                $klasMap = ['Kunci'=>'klas-kunci','Hilir'=>'klas-hilir','Hulu'=>'klas-hulu','Independen'=>'klas-independen'];
                $kc      = $klasMap[$row['klasifikasi'] ?? 'Independen'] ?? 'klas-independen';
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="td-mono">{{ number_format($row['output_awal'], 3, ',', '.') }}</td>
                <td class="td-mono gr-pos">+{{ number_format($row['tambahan_output'], 3, ',', '.') }}</td>
                <td class="td-mono td-ntb gr-teal">+{{ number_format($ntbRow, 3, ',', '.') }}</td>
                <td class="td-mono">{{ number_format($row['output_baru'], 3, ',', '.') }}</td>
                <td class="{{ $pct >= 0 ? 'gr-pos' : 'gr-neg' }}">{{ number_format($pct, 2) }}%</td>
                <td><span class="klas {{ $kc }}">{{ $row['klasifikasi'] ?? '-' }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Legenda kolom -->
    <div style="padding:8px 10px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; font-size:9.5px; color:#6b7280; line-height:1.8; margin-bottom:16px;">
        <strong style="color:#374151;">Keterangan:</strong>
        ΔX = tambahan output bruto ·
        <span style="color:#0d9488; font-weight:600;">ΔNTB</span> = ΔX × (1 − Rasio Biaya Antara) → dasar dampak PDRB ·
        % Dampak = ΔX / Output Awal
    </div>

    <div class="doc-footer">
        <span>Simulasi Input-Output — Model Leontief</span>
        <span>Halaman 3 dari 3 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>

</div>

<script>
Chart.defaults.font.family = 'Segoe UI, Arial, sans-serif';
Chart.defaults.font.size   = 10;
Chart.defaults.color       = '#6b7280';

const HASIL_DATA = @json($hasil);

window.addEventListener('load', function () {

    const labels    = HASIL_DATA.map(r => r.nama.length > 20 ? r.nama.substr(0,18)+'…' : r.nama);
    const tambahan  = HASIL_DATA.map(r => parseFloat((r.tambahan_output || 0).toFixed(3)));
    const ntbData   = HASIL_DATA.map(r => parseFloat((r.tambahan_ntb   || 0).toFixed(3)));
    const tickSize  = Math.max(7, Math.min(9, Math.floor(220 / labels.length)));

    const commonOpts = {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 0 },
        plugins: { legend:{ display:false }, tooltip:{ enabled:false } },
        scales: {
            x: { grid:{ color:'#f3f4f6' } },
            y: { grid:{ display:false }, ticks:{ font:{ size: tickSize } } }
        }
    };

    // Chart 1: ΔX Output
    new Chart(document.getElementById('chartDampak'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: tambahan,
                backgroundColor: tambahan.map(v => v >= 0 ? 'rgba(249,115,22,0.75)' : 'rgba(239,68,68,0.75)'),
                borderRadius: 2,
            }]
        },
        options: commonOpts
    });

    // Chart 2: ΔNTB
    new Chart(document.getElementById('chartNtb'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: ntbData,
                backgroundColor: ntbData.map(v => v >= 0 ? 'rgba(20,184,166,0.75)' : 'rgba(239,68,68,0.75)'),
                borderRadius: 2,
            }]
        },
        options: commonOpts
    });

});
</script>

</body>
</html>