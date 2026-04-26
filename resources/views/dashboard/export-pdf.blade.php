<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export PDF — Dashboard PDRB {{ $tahun }} {{ $triwulan_label }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #111827; background: #fff; padding: 32px; }

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

        /* Section headers */
        .section-title {
            font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.6px;
            color:#9ca3af; margin:20px 0 10px; display:flex; align-items:center; gap:8px;
        }
        .section-title::after { content:''; flex:1; height:1px; background:#e5e7eb; }

        /* Page sections */
        .page-section { margin-bottom: 48px; }

        /* Doc header */
        .doc-header {
            display:flex; align-items:flex-start; justify-content:space-between;
            padding-bottom:14px; border-bottom:2px solid #f97316; margin-bottom:18px;
        }
        .doc-title { font-size:18px; font-weight:700; }
        .doc-sub   { font-size:12px; color:#6b7280; margin-top:3px; }
        .doc-meta  { font-size:11px; color:#9ca3af; text-align:right; line-height:1.7; }

        /* Metric cards */
        .metric-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:18px; }
        .m-card { border:1px solid #e5e7eb; border-radius:8px; padding:10px 12px; border-top:3px solid #f97316; }
        .m-card.green  { border-top-color:#22c55e; }
        .m-card.blue   { border-top-color:#3b82f6; }
        .m-card.red    { border-top-color:#ef4444; }
        .m-label { font-size:9px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; color:#9ca3af; margin-bottom:4px; }
        .m-val   { font-size:13px; font-weight:700; color:#111827; font-family:'Courier New',monospace; }
        .m-val.up   { color:#16a34a; }
        .m-val.down { color:#dc2626; }
        .m-sub   { font-size:9px; color:#9ca3af; margin-top:2px; }

        /* Insight row */
        .insight-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:18px; }
        .insight-item { padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; display:flex; gap:8px; align-items:flex-start; }
        .insight-icon { font-size:16px; }
        .insight-text h5 { font-size:11px; font-weight:700; color:#111827; margin-bottom:2px; }
        .insight-text p  { font-size:10px; color:#6b7280; line-height:1.5; }

        /* Charts */
        .charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:18px; align-items:start; }
        .chart-label { font-size:11px; font-weight:600; color:#374151; margin-bottom:8px; }
        .chart-container { position:relative; width:100%; height:220px; }
        .chart-container canvas { position:absolute; top:0; left:0; width:100%!important; height:100%!important; }

        /* Struktur cards */
        .str-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:18px; }
        .str-card { border:1px solid #e5e7eb; border-radius:8px; padding:10px 12px; }
        .str-card-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:4px; }
        .str-card-val   { font-size:15px; font-weight:700; color:#111827; font-family:'Courier New',monospace; }
        .str-card-sub   { font-size:9.5px; color:#6b7280; margin-top:3px; }
        .progress-track { height:5px; border-radius:3px; background:#f3f4f6; overflow:hidden; margin:6px 0; }
        .progress-fill  { height:100%; border-radius:3px; }

        /* Tables */
        table { width:100%; border-collapse:collapse; font-size:11px; margin-bottom:18px; }
        thead th {
            font-size:9.5px; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;
            color:#6b7280; padding:8px 10px; border-bottom:1.5px solid #e5e7eb;
            background:#f9fafb; text-align:center;
        }
        thead th:first-child  { text-align:center; }
        thead th:nth-child(2) { text-align:left; }
        tbody tr:nth-child(even) td { background:#fafafa; }
        tbody tr:not(:last-child) td { border-bottom:0.5px solid #f0f0f0; }
        tbody td { padding:7px 10px; text-align:right; color:#374151; vertical-align:middle; }
        tbody td:first-child  { text-align:center; color:#9ca3af; }
        tbody td:nth-child(2) { text-align:left; font-weight:500; color:#111827; }
        .td-mono { font-family:'Courier New',monospace; font-size:10.5px; }
        .gr-pos  { color:#16a34a; font-weight:600; }
        .gr-neg  { color:#991b1b; font-weight:600; }

        /* Footer */
        .doc-footer {
            margin-top:auto; padding-top:10px; border-top:1px solid #e5e7eb;
            display:flex; justify-content:space-between; font-size:10px; color:#9ca3af;
        }

        /* Rank list */
        .rank-list { display:flex; flex-direction:column; gap:4px; }
        .rank-item { display:flex; align-items:center; gap:8px; padding:6px 8px; border-radius:6px; border:0.5px solid #f0f0f0; background:#fafafa; }
        .rank-num  { font-size:10px; font-weight:700; color:#9ca3af; min-width:16px; }
        .rank-name { font-size:11px; color:#374151; flex:1; }
        .rank-badge { font-size:9.5px; font-weight:700; padding:2px 7px; border-radius:100px; }
        .rank-up   { background:#f0fdf4; color:#166534; }
        .rank-down { background:#fef2f2; color:#991b1b; }

        /* Print */
        @media print {
            .print-bar { display:none !important; }
            body { padding: 0; }

            @page { size: A4; margin: 14mm 14mm 14mm 14mm; }

            /* Every page-section starts on a new page */
            .page-section {
                page-break-before: always;
                break-before: page;
                page-break-after: always;
                break-after: page;
                page-break-inside: avoid;
                break-inside: avoid;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
            }
            /* First section: no forced break before */
            .page-section:first-child {
                page-break-before: avoid;
                break-before: avoid;
            }

            /* Page 1 — no charts, full width strukt */
            .str-grid { grid-template-columns: repeat(3,1fr) !important; }
            .metric-grid { grid-template-columns: repeat(4,1fr) !important; }

            /* Pages 2 & 3 — single-column charts */
            .charts-grid { grid-template-columns: 1fr !important; }
            .chart-container { height: 200px !important; }
        }
    </style>
</head>
<body>

<div class="print-bar">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    <a href="{{ route('dashboard.index', ['tahun' => $tahun, 'triwulan' => $triwulan]) }}" class="btn-close">✕ Tutup</a>
</div>

<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Dashboard PDRB</div>
            <div class="doc-sub">Analisis Ekonomi Regional Triwulanan — Model PDRB</div>
        </div>
        <div class="doc-meta">
            Periode: <strong>{{ $tahun }} — {{ $triwulan_label }}</strong><br>
            Dicetak: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    <div class="section-title">Ringkasan Makro</div>
    <div class="metric-grid">
        <div class="m-card">
            <div class="m-label">PDRB ADHB</div>
            <div class="m-val">{{ number_format($total_adhb, 3, ',', '.') }}</div>
            <div class="m-sub">Miliar Rupiah</div>
        </div>
        <div class="m-card green">
            <div class="m-label">PDRB ADHK</div>
            <div class="m-val">{{ number_format($total_adhk, 3, ',', '.') }}</div>
            <div class="m-sub">Miliar Rupiah</div>
        </div>
        <div class="m-card {{ $growth_qoq_total >= 0 ? 'blue' : 'red' }}">
            <div class="m-label">Pertumbuhan QoQ</div>
            <div class="m-val {{ $growth_qoq_total >= 0 ? 'up' : 'down' }}">
                {{ $growth_qoq_total >= 0 ? '▲' : '▼' }} {{ number_format(abs($growth_qoq_total), 2, ',', '.') }}%
            </div>
            <div class="m-sub">vs triwulan sebelumnya</div>
        </div>
        <div class="m-card {{ $growth_yoy_total >= 0 ? 'green' : 'red' }}">
            <div class="m-label">Pertumbuhan YoY</div>
            <div class="m-val {{ $growth_yoy_total >= 0 ? 'up' : 'down' }}">
                {{ $growth_yoy_total >= 0 ? '▲' : '▼' }} {{ number_format(abs($growth_yoy_total), 2, ',', '.') }}%
            </div>
            <div class="m-sub">vs periode sama tahun lalu</div>
        </div>
    </div>

    @php
        $topSektor = $top5->first();
        $botSektor = $bottom5->first();
        $topStr    = collect($struktur_data)->sortByDesc('pct')->first();
    @endphp

    <div class="section-title">Insight Otomatis</div>
    <div class="insight-grid">
        <div class="insight-item">
            <div class="insight-icon">🚀</div>
            <div class="insight-text">
                <h5>Sektor Tumbuh Tercepat</h5>
                <p><strong>{{ $topSektor?->sektor?->nama ?? '-' }}</strong> tumbuh <strong>+{{ number_format($topSektor?->growth_yoy ?? 0, 2) }}%</strong> YoY periode ini.</p>
            </div>
        </div>
        <div class="insight-item">
            <div class="insight-icon">🏆</div>
            <div class="insight-text">
                <h5>Kelompok Dominan</h5>
                <p>Kelompok <strong>{{ $topStr['label'] ?? '-' }}</strong> mendominasi ADHB dengan kontribusi <strong>{{ $topStr['pct'] ?? 0 }}%</strong>.</p>
            </div>
        </div>
        <div class="insight-item">
            <div class="insight-icon">📉</div>
            <div class="insight-text">
                <h5>Sektor Kontraksi</h5>
                <p><strong>{{ $botSektor?->sektor?->nama ?? '-' }}</strong> mencatat pertumbuhan terendah <strong>{{ number_format($botSektor?->growth_yoy ?? 0, 2) }}%</strong> YoY.</p>
            </div>
        </div>
    </div>

    <div class="section-title">Struktur Ekonomi per Kelompok</div>
    <div class="str-grid">
        @foreach($struktur_data as $grp)
        <div class="str-card" style="border-top:3px solid {{ $grp['warna'] }};">
            <div class="str-card-label" style="color:{{ $grp['warna'] }}">{{ $grp['label'] }}</div>
            <div class="str-card-val">{{ $grp['pct'] }}%</div>
            <div class="progress-track">
                <div class="progress-fill" style="width:{{ $grp['pct'] }}%; background:{{ $grp['warna'] }};"></div>
            </div>
            <div class="str-card-sub">
                ADHB: Rp {{ number_format($grp['adhb'], 2) }} M ·
                YoY: <span style="color:{{ $grp['growth_yoy'] >= 0 ? '#16a34a' : '#dc2626' }}; font-weight:700;">
                    {{ $grp['growth_yoy'] >= 0 ? '▲ +' : '▼ ' }}{{ $grp['growth_yoy'] }}%
                </span><br>
                {{ $grp['jml_sektor'] }} sektor · Top: {{ $grp['sub_sektors'][0]['nama'] ?? '-' }}
            </div>
        </div>
        @endforeach
    </div>

    <div class="doc-footer">
        <span>Dashboard PDRB — Analisis Ekonomi Regional</span>
        <span>Halaman 1 dari 4 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>
</div>{{-- /page 1 --}}

<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Dashboard PDRB</div>
            <div class="doc-sub">Visualisasi Trend & Pertumbuhan YoY per Sektor</div>
        </div>
        <div class="doc-meta">Periode: <strong>{{ $tahun }} — {{ $triwulan_label }}</strong><br>Dicetak: {{ now()->format('d M Y H:i') }}</div>
    </div>

    <div class="section-title">Trend PDRB</div>
    <div class="charts-grid">
        <div>
            <div class="chart-label">Trend PDRB ADHK (Harga Konstan, Miliar Rp)</div>
            <div class="chart-container"><canvas id="chartTrend"></canvas></div>
        </div>
        <div>
            <div class="chart-label">Trend PDRB ADHB (Harga Berlaku, Miliar Rp)</div>
            <div class="chart-container"><canvas id="chartTrendAdhb"></canvas></div>
        </div>
    </div>

    <div class="section-title">Pertumbuhan YoY per Sektor (%)</div>
    <div class="chart-label">Pertumbuhan YoY per Sektor (%)</div>
    <div class="chart-container" style="height:320px; position:relative;">
        <canvas id="chartGrowth"></canvas>
    </div>

    <div class="doc-footer">
        <span>Dashboard PDRB — Analisis Ekonomi Regional</span>
        <span>Halaman 2 dari 4 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>
</div>

<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Dashboard PDRB</div>
            <div class="doc-sub">Peringkat Sektor — Pertumbuhan YoY</div>
        </div>
        <div class="doc-meta">Periode: <strong>{{ $tahun }} — {{ $triwulan_label }}</strong><br>Dicetak: {{ now()->format('d M Y H:i') }}</div>
    </div>

    <div class="section-title">Top 5 Pertumbuhan YoY</div>
    <div class="rank-list" style="margin-bottom:24px;">
        @foreach($top5 as $i => $item)
        <div class="rank-item" style="padding:10px 12px;">
            <span class="rank-num" style="font-size:13px; min-width:22px;">{{ $i+1 }}</span>
            <span class="rank-name" style="font-size:12px;">{{ $item->sektor->nama ?? '-' }}</span>
            <span class="rank-badge rank-up" style="font-size:11px; padding:4px 12px;">▲ +{{ number_format($item->growth_yoy, 2, ',', '.') }}%</span>
        </div>
        @endforeach
    </div>

    <div class="section-title">Bottom 5 Pertumbuhan YoY</div>
    <div class="rank-list">
        @foreach($bottom5 as $i => $item)
        <div class="rank-item" style="padding:10px 12px;">
            <span class="rank-num" style="font-size:13px; min-width:22px;">{{ $i+1 }}</span>
            <span class="rank-name" style="font-size:12px;">{{ $item->sektor->nama ?? '-' }}</span>
            <span class="rank-badge rank-down" style="font-size:11px; padding:4px 12px;">▼ {{ number_format($item->growth_yoy, 2, ',', '.') }}%</span>
        </div>
        @endforeach
    </div>

    <div class="doc-footer">
        <span>Dashboard PDRB — Analisis Ekonomi Regional</span>
        <span>Halaman 3 dari 4 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>
</div>

<div class="page-section">

    <div class="doc-header">
        <div>
            <div class="doc-title">Laporan Dashboard PDRB</div>
            <div class="doc-sub">Data Detail PDRB per Sektor</div>
        </div>
        <div class="doc-meta">Periode: <strong>{{ $tahun }} — {{ $triwulan_label }}</strong><br>Dicetak: {{ now()->format('d M Y H:i') }}</div>
    </div>

    <div class="section-title">Data Detail PDRB per Sektor</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th style="text-align:left;">Sektor</th>
                <th>ADHB (M Rp)</th>
                <th>ADHK (M Rp)</th>
                <th>Kontribusi</th>
                <th>QoQ</th>
                <th>YoY</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="text-align:left;">{{ $row->sektor->nama ?? '-' }}</td>
                <td class="td-mono">{{ number_format($row->adhb ?? 0, 2, ',', '.') }}</td>
                <td class="td-mono">{{ number_format($row->adhk ?? 0, 2, ',', '.') }}</td>
                <td>{{ number_format($row->kontribusi ?? 0, 2, ',', '.') }}%</td>
                <td class="{{ ($row->growth_qoq ?? 0) >= 0 ? 'gr-pos' : 'gr-neg' }}">
                    {{ ($row->growth_qoq ?? 0) >= 0 ? '▲ +' : '▼ ' }}{{ number_format($row->growth_qoq ?? 0, 2) }}%
                </td>
                <td class="{{ ($row->growth_yoy ?? 0) >= 0 ? 'gr-pos' : 'gr-neg' }}">
                    {{ ($row->growth_yoy ?? 0) >= 0 ? '▲ +' : '▼ ' }}{{ number_format($row->growth_yoy ?? 0, 2) }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="doc-footer">
        <span>Dashboard PDRB — Analisis Ekonomi Regional</span>
        <span>Halaman 4 dari 4 &nbsp;·&nbsp; {{ now()->format('d/m/Y') }}</span>
    </div>
</div>{{-- /page 4 --}}


<script>
Chart.defaults.font.family = 'Segoe UI, Arial, sans-serif';
Chart.defaults.font.size   = 10;
Chart.defaults.color       = '#6b7280';

const TREND_LABELS  = @json($trend_labels);
const TREND_ADHK    = @json($trend_adhk);
const TREND_ADHB    = @json($trend_adhb);
const GROWTH_LABELS = @json($growth_labels);
const GROWTH_DATA   = @json($growth_data);

window.addEventListener('load', function () {
    // Trend ADHK
    new Chart(document.getElementById('chartTrend'), {
        type: 'line',
        data: {
            labels: TREND_LABELS,
            datasets: [{
                data: TREND_ADHK,
                borderColor: '#f97316', backgroundColor: 'rgba(249,115,22,0.07)',
                borderWidth: 2, pointRadius: 3, tension: 0.35, fill: true
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, animation: { duration: 0 },
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: {
                x: { grid: { color: '#f3f4f6' }, ticks: { maxRotation: 30, font: { size: 9 } } },
                y: { grid: { color: '#f3f4f6' } }
            }
        }
    });

    // Trend ADHB
    new Chart(document.getElementById('chartTrendAdhb'), {
        type: 'line',
        data: {
            labels: TREND_LABELS,
            datasets: [{
                data: TREND_ADHB,
                borderColor: '#7c3aed', backgroundColor: 'rgba(124,58,237,0.07)',
                borderWidth: 2, pointRadius: 3, tension: 0.35, fill: true
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, animation: { duration: 0 },
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: {
                x: { grid: { color: '#f3f4f6' }, ticks: { maxRotation: 30, font: { size: 9 } } },
                y: { grid: { color: '#f3f4f6' } }
            }
        }
    });

    // Growth YoY
    const growthFontSize = Math.max(7, Math.min(9, Math.floor(200 / Math.max(GROWTH_LABELS.length, 1))));
    new Chart(document.getElementById('chartGrowth'), {
        type: 'bar',
        data: {
            labels: GROWTH_LABELS,
            datasets: [{
                data: GROWTH_DATA,
                backgroundColor: GROWTH_DATA.map(v => v >= 0 ? 'rgba(34,197,94,0.75)' : 'rgba(239,68,68,0.75)'),
                borderRadius: 2, borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y', responsive: true, maintainAspectRatio: false, animation: { duration: 0 },
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: {
                x: { grid: { color: '#f3f4f6' }, ticks: { callback: v => v + '%', font: { size: 9 } } },
                y: { grid: { display: false }, ticks: { font: { size: growthFontSize } } }
            }
        }
    });
});
</script>
</body>
</html>