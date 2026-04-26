@extends('layouts/app')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --font:      'DM Sans', sans-serif;
    --font-mono: 'DM Mono', monospace;
    --orange:    #f97316;
    --orange-lt: #fff7ed;
    --orange-bd: #fed7aa;
    --green:     #16a34a;
    --green-lt:  #f0fdf4;
    --green-bd:  #86efac;
    --blue:      #2563eb;
    --blue-lt:   #eff6ff;
    --blue-bd:   #bfdbfe;
    --red:       #dc2626;
    --red-lt:    #fef2f2;
    --red-bd:    #fca5a5;
    --purple:    #7c3aed;
    --purple-lt: #f5f3ff;
    --purple-bd: #c4b5fd;
    --amber:     #d97706;
    --amber-lt:  #fef9c3;
    --amber-bd:  #fde68a;
    --gray-50:   #f9fafb;
    --gray-100:  #f3f4f6;
    --gray-200:  #e5e7eb;
    --gray-300:  #d1d5db;
    --gray-400:  #9ca3af;
    --gray-500:  #6b7280;
    --gray-700:  #374151;
    --gray-900:  #111827;
    --radius:    10px;
    --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow:    0 4px 12px rgba(0,0,0,.07), 0 1px 3px rgba(0,0,0,.05);
}

body { background: #f7f6f3; }

.dash-page {
    font-family: var(--font);
    padding: 80px 24px 72px;
    color: var(--gray-900);
    max-width: 1440px;
}

/* SECTION LABEL */
.section-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--gray-400);
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 28px 0 10px;
}
.section-label::after {
    content: '';
    flex: 1;
    height: 0.5px;
    background: var(--gray-200);
}

/* CARD */
.card {
    background: #fff;
    border: 0.5px solid var(--gray-200);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    border-bottom: 0.5px solid var(--gray-200);
    background: var(--gray-50);
    gap: 8px;
    flex-wrap: wrap;
}
.card-head-left  { display: flex; align-items: center; gap: 8px; }
.card-head-icon  { color: var(--gray-400); flex-shrink: 0; }
.card-title      { font-size: 13px; font-weight: 600; color: var(--gray-900); }
.card-body       { padding: 18px; }

/* BADGES */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10.5px;
    font-weight: 600;
    padding: 2px 9px;
    border-radius: 100px;
    white-space: nowrap;
}
.badge-orange { background: var(--orange-lt); color: #c2410c; border: 0.5px solid var(--orange-bd); }
.badge-green  { background: var(--green-lt);  color: var(--green);  border: 0.5px solid var(--green-bd); }
.badge-blue   { background: var(--blue-lt);   color: var(--blue);   border: 0.5px solid var(--blue-bd); }
.badge-red    { background: var(--red-lt);    color: var(--red);    border: 0.5px solid var(--red-bd); }
.badge-purple { background: var(--purple-lt); color: var(--purple); border: 0.5px solid var(--purple-bd); }
.badge-amber  { background: var(--amber-lt);  color: var(--amber);  border: 0.5px solid var(--amber-bd); }

/* FILTER */
.filter-row   { display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end; }
.filter-group { display: flex; flex-direction: column; gap: 5px; }
.filter-label {
    font-size: 10.5px;
    font-weight: 700;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.6px;
}
.filter-select {
    height: 36px;
    padding: 0 12px;
    font-size: 13px;
    font-family: var(--font);
    color: var(--gray-700);
    background: #fff;
    border: 0.5px solid var(--gray-300);
    border-radius: 8px;
    outline: none;
    min-width: 160px;
    cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.filter-select:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(249,115,22,.1); }

/* METRIC CARDS */
.metric-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
@media (max-width: 900px) { .metric-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 480px) { .metric-grid { grid-template-columns: 1fr; } }

.metric-card {
    background: #fff;
    border: 0.5px solid var(--gray-200);
    border-radius: 12px;
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: box-shadow 0.2s, transform 0.2s;
}
.metric-card:hover { box-shadow: var(--shadow); transform: translateY(-1px); }
.metric-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 12px 12px 0 0;
}
.metric-card.orange::before { background: linear-gradient(90deg,#f97316,#fb923c); }
.metric-card.green::before  { background: linear-gradient(90deg,#16a34a,#4ade80); }
.metric-card.blue::before   { background: linear-gradient(90deg,#2563eb,#60a5fa); }
.metric-card.red::before    { background: linear-gradient(90deg,#dc2626,#f87171); }
.metric-card.purple::before { background: linear-gradient(90deg,#7c3aed,#a78bfa); }

.metric-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: var(--gray-400);
    margin-bottom: 10px;
}
.metric-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--gray-900);
    letter-spacing: -0.5px;
    line-height: 1;
    margin-bottom: 6px;
    font-family: var(--font-mono);
}
.metric-value.up   { color: var(--green); }
.metric-value.down { color: var(--red); }
.metric-sub { font-size: 11px; color: var(--gray-400); }

/* GRID LAYOUTS */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
@media (max-width: 768px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }

/* STRUKTUR EKONOMI */
.str-item { margin-bottom: 14px; }
.str-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; }
.str-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-900);
}
.str-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.str-pct-val { font-size: 13px; font-weight: 700; font-family: var(--font-mono); }
.progress-track {
    height: 6px;
    border-radius: 4px;
    background: var(--gray-100);
    overflow: hidden;
    margin-bottom: 8px;
}
.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 1.2s cubic-bezier(.4,0,.2,1);
}
.str-sub-list { display: flex; flex-direction: column; gap: 2px; }
.str-sub-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 3px 6px;
    border-radius: 5px;
    font-size: 11px;
    transition: background 0.1s;
}
.str-sub-item:hover { background: var(--gray-50); }
.str-sub-name { color: var(--gray-500); flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.str-sub-pct  { color: var(--gray-700); font-weight: 600; font-family: var(--font-mono); margin-left: 8px; }

/* HEATMAP */
.heatmap-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 4px;
}
@media (max-width: 600px) { .heatmap-grid { grid-template-columns: repeat(3,1fr); } }

.hm-cell {
    aspect-ratio: 1.1;
    border-radius: 7px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 5px 3px;
    text-align: center;
    cursor: default;
    transition: transform 0.15s, filter 0.15s;
}
.hm-cell:hover { transform: scale(1.07); filter: brightness(1.07); z-index: 1; }
.hm-cell-name { font-size: 8px; font-weight: 600; line-height: 1.2; margin-bottom: 2px; }
.hm-cell-val  { font-size: 10.5px; font-weight: 700; font-family: var(--font-mono); }
.hm-legend { display: flex; align-items: center; gap: 8px; margin-top: 10px; font-size: 10px; color: var(--gray-400); }
.hm-legend-bar { flex: 1; height: 5px; border-radius: 3px; background: linear-gradient(to right,#fff7ed,#f97316); }

.grp-mini-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: 14px; }
.grp-mini-card { border-radius: 8px; padding: 11px 13px; border: 0.5px solid var(--gray-200); }
.grp-mini-label { font-size: 9.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
.grp-mini-val   { font-size: 18px; font-weight: 700; color: var(--gray-900); font-family: var(--font-mono); line-height: 1; }
.grp-mini-sub   { font-size: 10.5px; color: var(--gray-400); margin-top: 3px; }

/* RANK LIST */
.rank-list { display: flex; flex-direction: column; gap: 5px; }
.rank-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: 8px;
    border: 0.5px solid var(--gray-100);
    background: var(--gray-50);
    transition: border-color 0.15s;
}
.rank-item:hover { border-color: var(--gray-300); }
.rank-num  { font-size: 10.5px; font-weight: 700; color: var(--gray-400); min-width: 18px; text-align: center; font-family: var(--font-mono); }
.rank-name { font-size: 12px; color: var(--gray-700); flex: 1; line-height: 1.3; }
.rank-bar-wrap { width: 60px; height: 4px; background: var(--gray-100); border-radius: 2px; overflow: hidden; flex-shrink: 0; }
.rank-bar { height: 100%; border-radius: 2px; }
.rank-badge {
    font-size: 10.5px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 100px;
    white-space: nowrap;
    font-family: var(--font-mono);
}
.rank-badge-up   { background: var(--green-lt); color: var(--green); border: 0.5px solid var(--green-bd); }
.rank-badge-down { background: var(--red-lt);   color: var(--red);   border: 0.5px solid var(--red-bd); }

/* CHART BOX */
.chart-box { position: relative; width: 100%; }

/* LEGEND ROW */
.legend-row { display: flex; flex-wrap: wrap; gap: 12px; font-size: 11px; color: var(--gray-500); margin-bottom: 10px; }
.legend-item { display: flex; align-items: center; gap: 5px; }
.legend-sq { width: 10px; height: 10px; border-radius: 2px; flex-shrink: 0; }

/* TABLE */
.dash-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.dash-table thead th {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--gray-400);
    padding: 10px 14px;
    border-bottom: 0.5px solid var(--gray-200);
    background: var(--gray-50);
    white-space: nowrap;
    text-align: right;
}
.dash-table thead th:first-child  { text-align: left; }
.dash-table thead th:nth-child(2) { text-align: center; }
.dash-table tbody td {
    padding: 9px 14px;
    border-bottom: 0.5px solid var(--gray-100);
    color: var(--gray-700);
    text-align: right;
    font-size: 12.5px;
}
.dash-table tbody td:first-child  { text-align: left; font-weight: 600; color: var(--gray-900); }
.dash-table tbody td:nth-child(2) { text-align: center; }
.dash-table tbody tr:last-child td { border-bottom: none; }
.dash-table tbody tr:hover td { background: var(--gray-50); }
.td-mono  { font-family: var(--font-mono); font-size: 12px; }
.gr-up    { color: var(--green); font-weight: 700; font-size: 11.5px; }
.gr-down  { color: var(--red);   font-weight: 700; font-size: 11.5px; }
.gr-zero  { color: var(--gray-400); font-size: 11.5px; }
.k-dot    { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 4px; vertical-align: middle; }
.k-label  { font-size: 11px; font-weight: 500; }

/* EXPORT BUTTON */
.btn-export {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 34px;
    padding: 0 16px;
    font-size: 12.5px;
    font-weight: 600;
    color: #fff;
    background: var(--orange);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: var(--font);
    text-decoration: none;
    transition: background 0.15s, transform 0.15s;
    box-shadow: 0 2px 6px rgba(249,115,22,0.3);
}
.btn-export:hover { background: #ea580c; transform: translateY(-1px); }

/* INSIGHT CARD */
.insight-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; }
@media (max-width: 700px) { .insight-grid { grid-template-columns: 1fr; } }

.insight-item {
    padding: 12px 14px;
    border-radius: 9px;
    border: 0.5px solid var(--gray-200);
    display: flex;
    gap: 10px;
    align-items: flex-start;
}
.insight-icon {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 14px;
}
.insight-text h5 { font-size: 11.5px; font-weight: 700; color: var(--gray-900); margin-bottom: 2px; }
.insight-text p  { font-size: 10.5px; color: var(--gray-500); line-height: 1.5; }

/* DIVIDER */
.v-divider { background: var(--gray-200); width: 1px; }

/* EMPTY STATE */
.empty-state { padding: 60px 20px; text-align: center; color: var(--gray-400); }
.empty-state h6 { font-size: 14px; font-weight: 700; color: var(--gray-500); margin: 12px 0 4px; }
.empty-state p  { font-size: 13px; }
</style>

<div class="dash-page">

    <!-- PAGE HEADER -->
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:4px;">
        <div>
            <h3 style="font-size:22px;font-weight:700;color:var(--gray-900);margin-bottom:4px;letter-spacing:-0.3px;">
                Dashboard PDRB
            </h3>
            <p style="font-size:13px;color:var(--gray-400);">Analisis ekonomi regional berbasis data PDRB triwulanan.</p>
        </div>
        @if($tahun && $triwulan)
        <a href="{{ route('dashboard.export-pdf', ['tahun' => $tahun, 'triwulan' => $triwulan]) }}"
           target="_blank" class="btn-export">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export PDF
        </a>
        @endif
    </div>

    @if(session('error'))   <input type="hidden" id="sa-error"   value="{{ session('error') }}"> @endif
    @if(session('success')) <input type="hidden" id="sa-success" value="{{ session('success') }}"> @endif

    <!-- FILTER -->
    <div class="section-label">Filter Periode</div>
    <div class="card" style="margin-bottom:12px;">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                <span class="card-title">Filter Periode</span>
            </div>
            @if($tahun && $triwulan)
            <span class="badge badge-orange">
                {{ $tahun }} — {{ $list_triwulan->firstWhere('id', $triwulan)?->triwulan }}
            </span>
            @endif
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.index') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Tahun</label>
                        <select name="tahun" class="filter-select" onchange="this.form.submit()">
                            <option value="">— Pilih Tahun —</option>
                            @foreach($list_tahun as $t)
                                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Triwulan</label>
                        <select name="triwulan" class="filter-select" onchange="this.form.submit()">
                            <option value="">— Pilih Triwulan —</option>
                            @foreach($list_triwulan as $t)
                                <option value="{{ $t->id }}" {{ $triwulan == $t->id ? 'selected' : '' }}>{{ $t->triwulan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($tahun && $triwulan)

    <!-- RINGKASAN MAKRO -->
    <div class="section-label">Ringkasan Makro</div>
    <div class="metric-grid" style="margin-bottom:12px;">
        <div class="metric-card orange">
            <div class="metric-label">PDRB ADHB</div>
            <div class="metric-value">{{ number_format($total_adhb, 3, ',', '.') }}</div>
            <div class="metric-sub">Miliar Rupiah · Harga Berlaku</div>
        </div>
        <div class="metric-card green">
            <div class="metric-label">PDRB ADHK</div>
            <div class="metric-value">{{ number_format($total_adhk, 3, ',', '.') }}</div>
            <div class="metric-sub">Miliar Rupiah · Harga Konstan</div>
        </div>
        <div class="metric-card {{ $growth_qoq_total >= 0 ? 'blue' : 'red' }}">
            <div class="metric-label">Pertumbuhan QoQ</div>
            <div class="metric-value {{ $growth_qoq_total >= 0 ? 'up' : 'down' }}">
                {{ $growth_qoq_total >= 0 ? '▲' : '▼' }} {{ number_format(abs($growth_qoq_total), 2, ',', '.') }}%
            </div>
            <div class="metric-sub">vs triwulan sebelumnya</div>
        </div>
        <div class="metric-card {{ $growth_yoy_total >= 0 ? 'green' : 'red' }}">
            <div class="metric-label">Pertumbuhan YoY</div>
            <div class="metric-value {{ $growth_yoy_total >= 0 ? 'up' : 'down' }}">
                {{ $growth_yoy_total >= 0 ? '▲' : '▼' }} {{ number_format(abs($growth_yoy_total), 2, ',', '.') }}%
            </div>
            <div class="metric-sub">vs periode sama tahun lalu</div>
        </div>
    </div>

    <!-- INSIGHT OTOMATIS -->
    @php
        $topSektor = $top5->first();
        $botSektor = $bottom5->first();
        $topStr    = collect($struktur_data)->sortByDesc('pct')->first();
        $topGrpYoy = collect($struktur_data)->sortByDesc('growth_yoy')->first();
    @endphp
    <div class="insight-grid" style="margin-bottom:12px;">
        <div class="insight-item">
            <div class="insight-icon" style="background:var(--green-lt);">🚀</div>
            <div class="insight-text">
                <h5>Sektor Tumbuh Tercepat</h5>
                <p>
                    <strong>{{ $topSektor?->sektor?->nama ?? '-' }}</strong>
                    tumbuh <strong style="color:var(--green);">+{{ number_format($topSektor?->growth_yoy ?? 0, 2) }}%</strong> YoY,
                    tertinggi periode ini.
                </p>
            </div>
        </div>
        <div class="insight-item">
            <div class="insight-icon" style="background:var(--orange-lt);">🏆</div>
            <div class="insight-text">
                <h5>Kelompok Dominan</h5>
                <p>
                    Kelompok <strong>{{ $topStr['label'] ?? '-' }}</strong> mendominasi ADHB dengan kontribusi
                    <strong style="color:var(--orange);">{{ $topStr['pct'] ?? 0 }}%</strong>.
                </p>
            </div>
        </div>
        <div class="insight-item">
            <div class="insight-icon" style="background:var(--red-lt);">📉</div>
            <div class="insight-text">
                <h5>Sektor Kontraksi</h5>
                <p>
                    <strong>{{ $botSektor?->sektor?->nama ?? '-' }}</strong>
                    mencatat pertumbuhan terendah
                    <strong style="color:var(--red);">{{ number_format($botSektor?->growth_yoy ?? 0, 2) }}%</strong> YoY.
                </p>
            </div>
        </div>
    </div>

    <!-- STRUKTUR EKONOMI & HEATMAP -->
    <div class="section-label">Struktur Ekonomi & Dominansi Sektor</div>
    <div class="grid-2" style="margin-bottom:12px;">

        <div class="card">
            <div class="card-head">
                <div class="card-head-left">
                    <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    <span class="card-title">Struktur Ekonomi</span>
                </div>
                <span class="badge badge-orange">ADHB</span>
            </div>
            <div class="card-body">
                <div class="legend-row">
                    @foreach($struktur_data as $grp)
                        <div class="legend-item">
                            <div class="legend-sq" style="background:{{ $grp['warna'] }}"></div>
                            {{ $grp['label'] }} {{ $grp['pct'] }}%
                        </div>
                    @endforeach
                </div>
                <div class="chart-box" style="height:130px; margin-bottom:16px;">
                    <canvas id="chartDonut"></canvas>
                </div>
                <div>
                    @foreach($struktur_data as $grp)
                    <div class="str-item">
                        <div class="str-header">
                            <span class="str-badge">
                                <span class="str-dot" style="background:{{ $grp['warna'] }}"></span>
                                {{ $grp['label'] }}
                                <span style="font-size:10.5px;color:var(--gray-400);font-weight:400;">({{ $grp['jml_sektor'] }} sektor)</span>
                            </span>
                            <span class="str-pct-val" style="color:{{ $grp['warna'] }}">{{ $grp['pct'] }}%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="width:{{ $grp['pct'] }}%; background:{{ $grp['warna'] }};"></div>
                        </div>
                        <div class="str-sub-list">
                            @foreach($grp['sub_sektors'] as $sub)
                            <div class="str-sub-item">
                                <span class="str-sub-name">{{ $sub['nama'] }}</span>
                                <span class="str-sub-pct">{{ $sub['kontribusi'] }}%</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div class="card-head-left">
                    <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span class="card-title">Heatmap Dominansi Sektor</span>
                </div>
                <span class="badge badge-orange">% ADHB</span>
            </div>
            <div class="card-body">
                <p style="font-size:11px;color:var(--gray-400);margin-bottom:10px;">Warna lebih gelap = kontribusi lebih besar terhadap PDRB.</p>
                <div class="heatmap-grid">
                    @foreach($heatmap_data as $cell)
                    @php
                        $i  = $cell['intensitas'];
                        $r  = 249 - round($i * 80);
                        $g  = round(242 - $i * 127);
                        $b  = round(232 - $i * 210);
                        $bg = "rgba({$r},{$g},{$b},1)";
                        $tc = $i > 0.45 ? 'rgba(255,255,255,0.95)' : '#92400e';
                        $ns = mb_strlen($cell['nama']) > 16 ? mb_substr($cell['nama'], 0, 14) . '…' : $cell['nama'];
                    @endphp
                    <div class="hm-cell" style="background:{{ $bg }};" title="{{ $cell['nama'] }}: {{ $cell['kontribusi'] }}%">
                        <span class="hm-cell-name" style="color:{{ $tc }}">{{ $ns }}</span>
                        <span class="hm-cell-val"  style="color:{{ $tc }}">{{ $cell['kontribusi'] }}%</span>
                    </div>
                    @endforeach
                </div>
                <div class="hm-legend">
                    <span>Rendah</span><div class="hm-legend-bar"></div><span>Tinggi</span>
                </div>
                <div class="grp-mini-grid">
                    @foreach($struktur_data as $grp)
                    <div class="grp-mini-card" style="border-top:3px solid {{ $grp['warna'] }};">
                        <div class="grp-mini-label" style="color:{{ $grp['warna'] }}">{{ $grp['label'] }}</div>
                        <div class="grp-mini-val">{{ $grp['pct'] }}%</div>
                        <div class="grp-mini-sub">YoY
                            @if($grp['growth_yoy'] >= 0)
                                <span style="color:var(--green);font-weight:700;">▲ +{{ $grp['growth_yoy'] }}%</span>
                            @else
                                <span style="color:var(--red);font-weight:700;">▼ {{ $grp['growth_yoy'] }}%</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- TREND & GROWTH CHARTS -->
    <div class="section-label">Analisis Pertumbuhan & Trend</div>
    <div class="grid-2" style="margin-bottom:12px; align-items:stretch;">

        <div style="display:flex; flex-direction:column; gap:12px;">
            <div class="card" style="flex:1; display:flex; flex-direction:column;">
                <div class="card-head">
                    <div class="card-head-left">
                        <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        <span class="card-title">Trend PDRB ADHK</span>
                    </div>
                    <span class="badge badge-orange">Harga Konstan</span>
                </div>
                <div class="card-body" style="flex:1; display:flex; flex-direction:column;">
                    <div class="chart-box" style="flex:1; min-height:130px;">
                        <canvas id="chartTrend"></canvas>
                    </div>
                </div>
            </div>
            <div class="card" style="flex:1; display:flex; flex-direction:column;">
                <div class="card-head">
                    <div class="card-head-left">
                        <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        <span class="card-title">Trend PDRB ADHB</span>
                    </div>
                    <span class="badge badge-purple">Harga Berlaku</span>
                </div>
                <div class="card-body" style="flex:1; display:flex; flex-direction:column;">
                    <div class="chart-box" style="flex:1; min-height:130px;">
                        <canvas id="chartTrendAdhb"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div class="card-head-left">
                    <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    <span class="card-title">Pertumbuhan YoY per Sektor</span>
                </div>
                <span class="badge badge-blue">ADHK</span>
            </div>
            <div class="card-body">
                <div class="legend-row">
                    <div class="legend-item"><div class="legend-sq" style="background:rgba(34,197,94,0.75)"></div>Positif</div>
                    <div class="legend-item"><div class="legend-sq" style="background:rgba(239,68,68,0.75)"></div>Negatif</div>
                </div>
                <div class="chart-box" style="height:{{ count($growth_data) * 28 + 60 }}px;">
                    <canvas id="chartGrowth"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- GROUP BAR -->
    <div class="card" style="margin-bottom:12px;">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                <span class="card-title">Kontribusi & Pertumbuhan per Kelompok Ekonomi</span>
            </div>
            <span class="badge badge-purple">Analisis Kelompok</span>
        </div>
        <div class="card-body">
            <div style="display:grid; grid-template-columns:1fr 1px 1fr; gap:0; align-items:start;">
                <div style="padding-right:18px;">
                    <div style="font-size:10.5px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                        <span style="width:10px;height:10px;border-radius:2px;background:var(--orange);flex-shrink:0;display:inline-block;"></span>
                        Kontribusi ADHB
                    </div>
                    <div class="chart-box" style="height:180px;">
                        <canvas id="chartGroupKontrib"></canvas>
                    </div>
                </div>
                <div class="v-divider" style="height:200px; align-self:center;"></div>
                <div style="padding-left:18px;">
                    <div style="font-size:10.5px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                        <span style="width:10px;height:10px;border-radius:2px;background:var(--blue);flex-shrink:0;display:inline-block;"></span>
                        Pertumbuhan YoY ADHK
                    </div>
                    <div class="chart-box" style="height:180px;">
                        <canvas id="chartGroupYoy"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TOP 5 / BOTTOM 5 -->
    <div class="section-label">Peringkat Pertumbuhan YoY</div>
    <div class="grid-2" style="margin-bottom:12px;">
        <div class="card">
            <div class="card-head">
                <div class="card-head-left">
                    <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
                    <span class="card-title">Top 5 Pertumbuhan YoY</span>
                </div>
                <span class="badge badge-green">ADHK</span>
            </div>
            <div class="card-body">
                <div class="rank-list">
                    @foreach($top5 as $i => $item)
                    @php $pct = abs($item->growth_yoy); $maxPct = abs($top5->first()->growth_yoy ?: 1); @endphp
                    <div class="rank-item">
                        <span class="rank-num">{{ $i + 1 }}</span>
                        <span class="rank-name">{{ $item->sektor->nama ?? '-' }}</span>
                        <div class="rank-bar-wrap">
                            <div class="rank-bar" style="width:{{ min(100, $pct/$maxPct*100) }}%; background:var(--green);"></div>
                        </div>
                        <span class="rank-badge rank-badge-up">▲ +{{ number_format($item->growth_yoy, 2, ',', '.') }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-head">
                <div class="card-head-left">
                    <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/></svg>
                    <span class="card-title">Bottom 5 Pertumbuhan YoY</span>
                </div>
                <span class="badge badge-red">ADHK</span>
            </div>
            <div class="card-body">
                <div class="rank-list">
                    @foreach($bottom5 as $i => $item)
                    @php $pct = abs($item->growth_yoy); $maxPct = abs($bottom5->first()->growth_yoy ?: 1); @endphp
                    <div class="rank-item">
                        <span class="rank-num">{{ $i + 1 }}</span>
                        <span class="rank-name">{{ $item->sektor->nama ?? '-' }}</span>
                        <div class="rank-bar-wrap">
                            <div class="rank-bar" style="width:{{ min(100, $pct/$maxPct*100) }}%; background:var(--red);"></div>
                        </div>
                        <span class="rank-badge rank-badge-down">▼ {{ number_format($item->growth_yoy, 2, ',', '.') }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL DETAIL -->
    <div class="section-label">Data Detail per Sektor</div>
    <div class="card">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
                <span class="card-title">Tabel PDRB Semua Sektor</span>
            </div>
            <span class="badge badge-orange">{{ $data->count() }} sektor</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Sektor</th>
                        <th>Kelompok</th>
                        <th>ADHB <span style="font-weight:400;text-transform:none;font-size:9px;color:var(--gray-300);">(Miliar Rp)</span></th>
                        <th>ADHK <span style="font-weight:400;text-transform:none;font-size:9px;color:var(--gray-300);">(Miliar Rp)</span></th>
                        <th>Kontribusi</th>
                        <th>QoQ</th>
                        <th>YoY</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    @php
                        $kel = $row->kelompok ?? 'tersier';
                        $kelLabel = ['primer'=>'Primer','sekunder'=>'Sekunder','tersier'=>'Tersier'][$kel] ?? '-';
                        $kelWarna = match($kel) { 'primer'=>'#22c55e','sekunder'=>'#3b82f6',default=>'#f97316' };
                    @endphp
                    <tr>
                        <td>{{ $row->sektor->nama ?? '-' }}</td>
                        <td>
                            <span class="k-dot" style="background:{{ $kelWarna }}"></span>
                            <span class="k-label">{{ $kelLabel }}</span>
                        </td>
                        <td class="td-mono">{{ number_format($row->adhb ?? 0, 2, ',', '.') }}</td>
                        <td class="td-mono">{{ number_format($row->adhk ?? 0, 2, ',', '.') }}</td>
                        <td>{{ number_format($row->kontribusi ?? 0, 2, ',', '.') }}%</td>
                        <td>
                            @if(($row->growth_qoq ?? 0) > 0) <span class="gr-up">▲ +{{ number_format($row->growth_qoq, 2, ',', '.') }}%</span>
                            @elseif(($row->growth_qoq ?? 0) < 0) <span class="gr-down">▼ {{ number_format($row->growth_qoq, 2, ',', '.') }}%</span>
                            @else <span class="gr-zero">0%</span> @endif
                        </td>
                        <td>
                            @if(($row->growth_yoy ?? 0) > 0) <span class="gr-up">▲ +{{ number_format($row->growth_yoy, 2, ',', '.') }}%</span>
                            @elseif(($row->growth_yoy ?? 0) < 0) <span class="gr-down">▼ {{ number_format($row->growth_yoy, 2, ',', '.') }}%</span>
                            @else <span class="gr-zero">0%</span> @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--gray-400);padding:40px;">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @else

    <div class="card">
        <div class="empty-state">
            <svg width="40" height="40" fill="none" stroke="var(--gray-200)" stroke-width="1.2" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            <h6>Filter Belum Dipilih</h6>
            <p>Pilih <strong>Tahun</strong> dan <strong>Triwulan</strong> untuk menampilkan dashboard.</p>
        </div>
    </div>

    @endif

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const err = document.getElementById('sa-error');
    const suc = document.getElementById('sa-success');
    if (err) Swal.fire({ icon:'error',   title:'Oops…',     text: err.value });
    if (suc) Swal.fire({ icon:'success', title:'Berhasil!', text: suc.value, timer: 2200, showConfirmButton: false });
});
</script>

@if($tahun && $triwulan)
<script>
const TREND_LABELS   = @json($trend_labels);
const TREND_ADHK     = @json($trend_adhk);
const TREND_ADHB     = @json($trend_adhb);
const GROWTH_LABELS  = @json($growth_labels);
const GROWTH_DATA    = @json($growth_data);
const STRUKTUR_DATA  = @json($struktur_data);
const GROUP_LABELS   = @json($group_labels);
const GROUP_KONTRIB  = @json($group_kontribusi);
const GROUP_YOY_AVG  = @json($group_yoy_avg);
const GROUP_COLORS   = @json($group_colors);

Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#6b7280';

document.addEventListener('DOMContentLoaded', function () {
    buildDonut();
    buildTrend();
    buildTrendAdhb();
    buildGrowth();
    buildGroupBar();
});

function buildDonut() {
    new Chart(document.getElementById('chartDonut'), {
        type: 'doughnut',
        data: {
            labels: STRUKTUR_DATA.map(s => s.label),
            datasets: [{ data: STRUKTUR_DATA.map(s => s.pct), backgroundColor: STRUKTUR_DATA.map(s => s.warna + 'cc'), borderWidth: 0, hoverOffset: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw.toFixed(1)}%` } }
            }
        }
    });
}

function buildTrend() {
    new Chart(document.getElementById('chartTrend'), {
        type: 'line',
        data: {
            labels: TREND_LABELS,
            datasets: [{ label: 'ADHK', data: TREND_ADHK, borderColor: '#f97316', backgroundColor: 'rgba(249,115,22,0.07)', borderWidth: 2, pointBackgroundColor: '#f97316', pointRadius: 3.5, pointHoverRadius: 5, tension: 0.35, fill: true }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw.toFixed(3)} Miliar` } } },
            scales: {
                x: { grid: { color: '#f3f4f6' }, border: { color: '#e5e7eb' }, ticks: { maxRotation: 30 } },
                y: { grid: { color: '#f3f4f6' }, border: { color: '#e5e7eb' } }
            }
        }
    });
}

function buildTrendAdhb() {
    new Chart(document.getElementById('chartTrendAdhb'), {
        type: 'line',
        data: {
            labels: TREND_LABELS,
            datasets: [{ label: 'ADHB', data: TREND_ADHB, borderColor: '#7c3aed', backgroundColor: 'rgba(124,58,237,0.07)', borderWidth: 2, pointBackgroundColor: '#7c3aed', pointRadius: 3.5, pointHoverRadius: 5, tension: 0.35, fill: true }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw.toFixed(3)} Miliar` } } },
            scales: {
                x: { grid: { color: '#f3f4f6' }, border: { color: '#e5e7eb' }, ticks: { maxRotation: 30 } },
                y: { grid: { color: '#f3f4f6' }, border: { color: '#e5e7eb' } }
            }
        }
    });
}

function buildGrowth() {
    new Chart(document.getElementById('chartGrowth'), {
        type: 'bar',
        data: {
            labels: GROWTH_LABELS,
            datasets: [{
                data: GROWTH_DATA,
                backgroundColor: GROWTH_DATA.map(v => v >= 0 ? 'rgba(34,197,94,0.75)' : 'rgba(239,68,68,0.75)'),
                borderRadius: 3, borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y', responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw.toFixed(2)}%` } } },
            scales: {
                x: { grid: { color: '#f3f4f6' }, border: { color: '#e5e7eb' }, ticks: { callback: v => v + '%' } },
                y: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
}

function buildGroupBar() {
    new Chart(document.getElementById('chartGroupKontrib'), {
        type: 'bar',
        data: {
            labels: GROUP_LABELS,
            datasets: [{ data: GROUP_KONTRIB, backgroundColor: GROUP_COLORS.map(c => c + 'bf'), borderRadius: 6, borderSkipped: false }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw.toFixed(1)}%` } } },
            scales: {
                x: { grid: { display: false }, border: { color: '#e5e7eb' }, ticks: { font: { size: 11, weight: '600' } } },
                y: { grid: { color: '#f3f4f6' }, border: { color: '#e5e7eb' }, beginAtZero: true, ticks: { callback: v => v + '%', font: { size: 10 } } }
            }
        }
    });

    new Chart(document.getElementById('chartGroupYoy'), {
        type: 'bar',
        data: {
            labels: GROUP_LABELS,
            datasets: [{ data: GROUP_YOY_AVG, backgroundColor: GROUP_YOY_AVG.map(v => v >= 0 ? 'rgba(34,197,94,0.8)' : 'rgba(239,68,68,0.8)'), borderRadius: 6, borderSkipped: false }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw.toFixed(2)}%` } } },
            scales: {
                x: { grid: { display: false }, border: { color: '#e5e7eb' }, ticks: { font: { size: 11, weight: '600' } } },
                y: {
                    grid: { color: ctx => ctx.tick.value === 0 ? '#374151' : '#f3f4f6', lineWidth: ctx => ctx.tick.value === 0 ? 1.5 : 1 },
                    border: { color: '#e5e7eb' }, ticks: { callback: v => v + '%', font: { size: 10 } }
                }
            }
        }
    });
}
</script>
@endif
@endsection