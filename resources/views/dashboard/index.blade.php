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
    --shadow-lg: 0 20px 40px rgba(0,0,0,.12), 0 4px 12px rgba(0,0,0,.08);
}

body { background: #f7f6f3; }

.dash-page {
    font-family: var(--font);
    padding: 80px 24px 80px;
    color: var(--gray-900);
    max-width: 1440px;
}

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
.section-label::after { content:''; flex:1; height:.5px; background:var(--gray-200); }

.card { background:#fff; border:.5px solid var(--gray-200); border-radius:12px; overflow:hidden; box-shadow:var(--shadow-sm); }
.card-head { display:flex; align-items:center; justify-content:space-between; padding:12px 18px; border-bottom:.5px solid var(--gray-200); background:var(--gray-50); gap:8px; flex-wrap:wrap; }
.card-head-left  { display:flex; align-items:center; gap:8px; }
.card-head-icon  { color:var(--gray-400); flex-shrink:0; }
.card-title      { font-size:13px; font-weight:600; color:var(--gray-900); }
.card-body       { padding:18px; }

.badge { display:inline-flex; align-items:center; gap:4px; font-size:10.5px; font-weight:600; padding:2px 9px; border-radius:100px; white-space:nowrap; }
.badge-orange { background:var(--orange-lt); color:#c2410c;        border:.5px solid var(--orange-bd); }
.badge-green  { background:var(--green-lt);  color:var(--green);   border:.5px solid var(--green-bd); }
.badge-blue   { background:var(--blue-lt);   color:var(--blue);    border:.5px solid var(--blue-bd); }
.badge-red    { background:var(--red-lt);    color:var(--red);     border:.5px solid var(--red-bd); }
.badge-purple { background:var(--purple-lt); color:var(--purple);  border:.5px solid var(--purple-bd); }
.badge-amber  { background:var(--amber-lt);  color:var(--amber);   border:.5px solid var(--amber-bd); }

.filter-row   { display:flex; gap:14px; flex-wrap:wrap; align-items:flex-end; }
.filter-group { display:flex; flex-direction:column; gap:5px; }
.filter-label { font-size:10.5px; font-weight:700; color:var(--gray-500); text-transform:uppercase; letter-spacing:.6px; }
.filter-select { height:36px; padding:0 12px; font-size:13px; font-family:var(--font); color:var(--gray-700); background:#fff; border:.5px solid var(--gray-300); border-radius:8px; outline:none; min-width:160px; cursor:pointer; transition:border-color .15s,box-shadow .15s; }
.filter-select:focus { border-color:var(--orange); box-shadow:0 0 0 3px rgba(249,115,22,.1); }

.metric-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
@media(max-width:900px){ .metric-grid{grid-template-columns:1fr 1fr;} }
@media(max-width:480px){ .metric-grid{grid-template-columns:1fr;} }

.metric-card { background:#fff; border:.5px solid var(--gray-200); border-radius:12px; padding:18px 20px; position:relative; overflow:hidden; box-shadow:var(--shadow-sm); transition:box-shadow .2s,transform .2s; }
.metric-card:hover { box-shadow:var(--shadow); transform:translateY(-1px); }
.metric-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:12px 12px 0 0; }
.metric-card.orange::before { background:linear-gradient(90deg,#f97316,#fb923c); }
.metric-card.green::before  { background:linear-gradient(90deg,#16a34a,#4ade80); }
.metric-card.blue::before   { background:linear-gradient(90deg,#2563eb,#60a5fa); }
.metric-card.red::before    { background:linear-gradient(90deg,#dc2626,#f87171); }
.metric-card.purple::before { background:linear-gradient(90deg,#7c3aed,#a78bfa); }
.metric-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.7px; color:var(--gray-400); margin-bottom:10px; }
.metric-value { font-size:24px; font-weight:700; color:var(--gray-900); letter-spacing:-.5px; line-height:1; margin-bottom:6px; font-family:var(--font-mono); }
.metric-value.up   { color:var(--green); }
.metric-value.down { color:var(--red); }
.metric-sub { font-size:11px; color:var(--gray-400); }

.grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
@media(max-width:768px){ .grid-2,.grid-3{grid-template-columns:1fr;} }

.str-item { margin-bottom:14px; }
.str-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:5px; }
.str-badge { display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:var(--gray-900); }
.str-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; }
.str-pct-val { font-size:13px; font-weight:700; font-family:var(--font-mono); }
.progress-track { height:6px; border-radius:4px; background:var(--gray-100); overflow:hidden; margin-bottom:8px; }
.progress-fill  { height:100%; border-radius:4px; transition:width 1.2s cubic-bezier(.4,0,.2,1); }
.str-sub-list { display:flex; flex-direction:column; gap:2px; }
.str-sub-item { display:flex; justify-content:space-between; align-items:center; padding:3px 6px; border-radius:5px; font-size:11px; transition:background .1s; }
.str-sub-item:hover { background:var(--gray-50); }
.str-sub-name { color:var(--gray-500); flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.str-sub-pct  { color:var(--gray-700); font-weight:600; font-family:var(--font-mono); margin-left:8px; }

.heatmap-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:4px; }
@media(max-width:600px){ .heatmap-grid{grid-template-columns:repeat(3,1fr);} }
.hm-cell { aspect-ratio:1.1; border-radius:7px; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:5px 3px; text-align:center; cursor:pointer; transition:transform .15s,filter .15s,outline .1s; }
.hm-cell:hover { transform:scale(1.07); filter:brightness(1.07); z-index:1; }
.hm-cell.hm-dimmed { opacity:.25; transform:scale(.97); }
.hm-cell.hm-focused { outline:2px solid var(--orange); outline-offset:2px; }
.hm-cell-name { font-size:8px; font-weight:600; line-height:1.2; margin-bottom:2px; }
.hm-cell-val  { font-size:10.5px; font-weight:700; font-family:var(--font-mono); }
.hm-legend { display:flex; align-items:center; gap:8px; margin-top:10px; font-size:10px; color:var(--gray-400); }
.hm-legend-bar { flex:1; height:5px; border-radius:3px; background:linear-gradient(to right,#fff7ed,#f97316); }
.grp-mini-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-top:14px; }
.grp-mini-card { border-radius:8px; padding:11px 13px; border:.5px solid var(--gray-200); }
.grp-mini-label { font-size:9.5px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:3px; }
.grp-mini-val   { font-size:18px; font-weight:700; color:var(--gray-900); font-family:var(--font-mono); line-height:1; }
.grp-mini-sub   { font-size:10.5px; color:var(--gray-400); margin-top:3px; }

.rank-list { display:flex; flex-direction:column; gap:5px; }
.rank-item { display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:8px; border:.5px solid var(--gray-100); background:var(--gray-50); transition:border-color .15s; }
.rank-item:hover { border-color:var(--gray-300); }
.rank-num  { font-size:10.5px; font-weight:700; color:var(--gray-400); min-width:18px; text-align:center; font-family:var(--font-mono); }
.rank-name { font-size:12px; color:var(--gray-700); flex:1; line-height:1.3; }
.rank-bar-wrap { width:60px; height:4px; background:var(--gray-100); border-radius:2px; overflow:hidden; flex-shrink:0; }
.rank-bar { height:100%; border-radius:2px; }
.rank-badge { font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:100px; white-space:nowrap; font-family:var(--font-mono); }
.rank-badge-up   { background:var(--green-lt); color:var(--green); border:.5px solid var(--green-bd); }
.rank-badge-down { background:var(--red-lt);   color:var(--red);   border:.5px solid var(--red-bd); }

.chart-box { position:relative; width:100%; }

.legend-row { display:flex; flex-wrap:wrap; gap:12px; font-size:11px; color:var(--gray-500); margin-bottom:10px; }
.legend-item { display:flex; align-items:center; gap:5px; }
.legend-sq { width:10px; height:10px; border-radius:2px; flex-shrink:0; }

.dash-table { width:100%; border-collapse:collapse; font-size:12.5px; }
.dash-table thead th { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--gray-400); padding:10px 14px; border-bottom:.5px solid var(--gray-200); background:var(--gray-50); white-space:nowrap; text-align:right; }
.dash-table thead th:first-child  { text-align:left; }
.dash-table thead th:nth-child(2) { text-align:center; }
.dash-table tbody td { padding:9px 14px; border-bottom:.5px solid var(--gray-100); color:var(--gray-700); text-align:right; font-size:12.5px; }
.dash-table tbody td:first-child  { text-align:left; font-weight:600; color:var(--gray-900); }
.dash-table tbody td:nth-child(2) { text-align:center; }
.dash-table tbody tr:last-child td { border-bottom:none; }
.dash-table tbody tr:hover td { background:var(--gray-50); }
.dash-table tbody tr.row-dimmed td { opacity:.3; }
.dash-table tbody tr.row-focused { background:var(--orange-lt) !important; }
.dash-table tbody tr.row-focused td { background:transparent !important; }
.td-mono  { font-family:var(--font-mono); font-size:12px; }
.gr-up    { color:var(--green); font-weight:700; font-size:11.5px; }
.gr-down  { color:var(--red);   font-weight:700; font-size:11.5px; }
.gr-zero  { color:var(--gray-400); font-size:11.5px; }
.k-dot    { width:8px; height:8px; border-radius:50%; display:inline-block; margin-right:4px; vertical-align:middle; }
.k-label  { font-size:11px; font-weight:500; }

.btn-export { display:inline-flex; align-items:center; gap:6px; height:34px; padding:0 16px; font-size:12.5px; font-weight:600; color:#fff; background:var(--orange); border:none; border-radius:8px; cursor:pointer; font-family:var(--font); text-decoration:none; transition:background .15s,transform .15s; box-shadow:0 2px 6px rgba(249,115,22,.3); }
.btn-export:hover { background:#ea580c; transform:translateY(-1px); }

.insight-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
@media(max-width:700px){ .insight-grid{grid-template-columns:1fr;} }
.insight-item { padding:12px 14px; border-radius:9px; border:.5px solid var(--gray-200); display:flex; gap:10px; align-items:flex-start; }
.insight-icon { width:28px; height:28px; border-radius:7px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:14px; }
.insight-text h5 { font-size:11.5px; font-weight:700; color:var(--gray-900); margin-bottom:2px; }
.insight-text p  { font-size:10.5px; color:var(--gray-500); line-height:1.5; }

.v-divider { background:var(--gray-200); width:1px; }

.empty-state { padding:60px 20px; text-align:center; color:var(--gray-400); }
.empty-state h6 { font-size:14px; font-weight:700; color:var(--gray-500); margin:12px 0 4px; }
.empty-state p  { font-size:13px; }

.section-collapsible { overflow:hidden; transition:max-height .4s cubic-bezier(.4,0,.2,1), opacity .3s ease; }
.section-collapsible.collapsed { max-height:0 !important; opacity:0; margin-bottom:0 !important; }

.persona-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border: .5px solid var(--gray-200);
    border-radius: 12px;
    padding: 10px 16px;
    margin-bottom: 12px;
    box-shadow: var(--shadow-sm);
    flex-wrap: wrap;
    gap: 10px;
}
.persona-label {
    font-size: 11px;
    font-weight: 700;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: .7px;
}
.persona-switch {
    display: flex;
    background: var(--gray-100);
    border-radius: 8px;
    padding: 3px;
    gap: 2px;
}
.persona-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 6px;
    border: none;
    background: transparent;
    font-family: var(--font);
    font-size: 12px;
    font-weight: 600;
    color: var(--gray-400);
    cursor: pointer;
    transition: all .2s;
}
.persona-btn.active {
    background: #fff;
    color: var(--gray-900);
    box-shadow: var(--shadow-sm);
}
.persona-btn.active.analyst { color: var(--blue); }
.persona-btn.active.pejabat { color: var(--purple); }
.persona-hint {
    font-size: 11px;
    color: var(--gray-400);
    font-style: italic;
}

.section-ctrl-panel {
    background: #fff;
    border: .5px solid var(--gray-200);
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    margin-bottom: 12px;
}
.section-ctrl-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    cursor: pointer;
    user-select: none;
    background: var(--gray-50);
    border-bottom: .5px solid var(--gray-200);
}
.section-ctrl-head:hover { background: var(--gray-100); }
.section-ctrl-title { font-size:12px; font-weight:600; color:var(--gray-700); display:flex; align-items:center; gap:7px; }
.section-ctrl-body {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 12px 16px;
}
.ctrl-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 100px;
    border: .5px solid var(--gray-200);
    background: var(--gray-50);
    font-size: 11.5px;
    font-weight: 600;
    color: var(--gray-500);
    cursor: pointer;
    transition: all .15s;
    user-select: none;
}
.ctrl-chip:hover { border-color: var(--gray-300); background: var(--gray-100); }
.ctrl-chip.active {
    background: var(--orange-lt);
    border-color: var(--orange-bd);
    color: #c2410c;
}
.ctrl-chip .chip-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--gray-300);
    transition: background .15s;
}
.ctrl-chip.active .chip-dot { background: var(--orange); }
.ctrl-caret { transition: transform .25s; color: var(--gray-400); }
.ctrl-caret.open { transform: rotate(180deg); }
.ctrl-reset-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 6px;
    border: .5px solid var(--gray-200);
    background: #fff;
    font-family: var(--font);
    font-size: 11px;
    color: var(--gray-500);
    cursor: pointer;
    transition: all .15s;
}
.ctrl-reset-btn:hover { border-color: var(--gray-400); color: var(--gray-700); }

.sektor-filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: var(--gray-50);
    border-bottom: .5px solid var(--gray-200);
    flex-wrap: wrap;
}
.sektor-filter-label { font-size:11px; font-weight:700; color:var(--gray-500); text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; }
.sektor-search {
    height:30px;
    padding: 0 10px;
    font-size: 12px;
    font-family: var(--font);
    border: .5px solid var(--gray-300);
    border-radius: 7px;
    outline: none;
    background: #fff;
    color: var(--gray-700);
    width: 180px;
    transition: border-color .15s, box-shadow .15s;
}
.sektor-search:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(249,115,22,.1); }
.sektor-kelompok-btns { display:flex; gap:4px; flex-wrap:wrap; }
.kelompok-btn {
    height: 28px;
    padding: 0 12px;
    border-radius: 100px;
    border: .5px solid var(--gray-200);
    background: #fff;
    font-size: 11px;
    font-weight: 600;
    color: var(--gray-500);
    cursor: pointer;
    font-family: var(--font);
    transition: all .15s;
}
.kelompok-btn:hover { border-color: var(--gray-400); }
.kelompok-btn.k-active { border-color: transparent; color: #fff; }
.sektor-actions { display:flex; gap:6px; margin-left:auto; }
.sektor-action-btn {
    height: 28px;
    padding: 0 10px;
    border-radius: 7px;
    border: .5px solid var(--gray-200);
    background: #fff;
    font-size: 11px;
    font-weight: 600;
    color: var(--gray-500);
    cursor: pointer;
    font-family: var(--font);
    transition: all .15s;
    display: flex;
    align-items: center;
    gap: 4px;
}
.sektor-action-btn:hover { border-color: var(--gray-400); color: var(--gray-700); }
.sektor-action-btn.btn-clear { color: var(--red); border-color: var(--red-bd); background: var(--red-lt); }
.sektor-action-btn.btn-clear:hover { background: #fee2e2; }
.selected-counter {
    font-size: 11px;
    font-weight: 700;
    color: var(--orange);
    background: var(--orange-lt);
    border: .5px solid var(--orange-bd);
    border-radius: 100px;
    padding: 2px 9px;
    white-space: nowrap;
}
.selected-counter.hidden { display: none; }

.row-checkbox-cell { width: 36px; text-align: center !important; }
.sektor-row-check {
    width: 14px; height: 14px;
    accent-color: var(--orange);
    cursor: pointer;
}

.drill-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.35);
    z-index: 1000;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s;
    backdrop-filter: blur(2px);
}
.drill-overlay.open { opacity: 1; pointer-events: all; }

.drill-panel {
    position: fixed;
    top: 0; right: 0; bottom: 0;
    width: min(520px, 95vw);
    background: #fff;
    box-shadow: var(--shadow-lg);
    z-index: 1001;
    transform: translateX(100%);
    transition: transform .35s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.drill-panel.open { transform: translateX(0); }

.drill-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: .5px solid var(--gray-200);
    background: var(--gray-50);
    flex-shrink: 0;
}
.drill-head-info h4 { font-size: 15px; font-weight: 700; color: var(--gray-900); margin-bottom: 3px; }
.drill-head-info p  { font-size: 11px; color: var(--gray-400); }
.drill-close {
    width: 32px; height: 32px;
    border: none; background: var(--gray-100);
    border-radius: 8px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--gray-500); font-size: 18px;
    transition: background .15s, color .15s;
}
.drill-close:hover { background: var(--gray-200); color: var(--gray-900); }

.drill-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 22px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.drill-metric-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
}
.drill-metric {
    background: var(--gray-50);
    border: .5px solid var(--gray-200);
    border-radius: 10px;
    padding: 12px 14px;
}
.drill-metric-label { font-size:9.5px; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:var(--gray-400); margin-bottom:6px; }
.drill-metric-value { font-size:17px; font-weight:700; color:var(--gray-900); font-family:var(--font-mono); line-height:1; }
.drill-metric-sub   { font-size:10.5px; color:var(--gray-400); margin-top:4px; }

.drill-section-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: var(--gray-400);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.drill-section-title::after { content:''; flex:1; height:.5px; background:var(--gray-200); }

.drill-stat-list { display:flex; flex-direction:column; gap:5px; }
.drill-stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 10px;
    border-radius: 7px;
    background: var(--gray-50);
    font-size: 12px;
}
.drill-stat-name { color: var(--gray-500); }
.drill-stat-val  { font-weight: 700; font-family: var(--font-mono); color: var(--gray-900); font-size: 12px; }

.position-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
}

.drill-chart-wrap { height: 160px; position:relative; }
</style>

<div class="dash-page">

<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
    <div>
        <h3 style="font-size:22px;font-weight:700;color:var(--gray-900);margin-bottom:4px;letter-spacing:-.3px;">Dashboard PDRB</h3>
        <p style="font-size:13px;color:var(--gray-400);">Analisis ekonomi regional berbasis data PDRB triwulanan.</p>
    </div>
    @if($tahun && $triwulan)
    <a href="{{ route('dashboard.export-pdf', ['tahun' => $tahun, 'triwulan' => $triwulan]) }}"
       target="_blank" class="btn-export">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Export PDF
    </a>
    @endif
</div>

@if(session('error'))   <input type="hidden" id="sa-error"   value="{{ session('error') }}"> @endif
@if(session('success')) <input type="hidden" id="sa-success" value="{{ session('success') }}"> @endif

<div class="section-label">Filter Periode</div>
<div class="card" style="margin-bottom:12px;">
    <div class="card-head">
        <div class="card-head-left">
            <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            <span class="card-title">Filter Periode</span>
        </div>
        @if($tahun && $triwulan)
        <span class="badge badge-orange">{{ $tahun }} &mdash; {{ $list_triwulan->firstWhere('id', $triwulan)?->triwulan }}</span>
        @endif
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard.index') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select name="tahun" class="filter-select" onchange="this.form.submit()">
                        <option value="">Pilih Tahun</option>
                        @foreach($list_tahun as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Triwulan</label>
                    <select name="triwulan" class="filter-select" onchange="this.form.submit()">
                        <option value="">Pilih Triwulan</option>
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

<div class="persona-bar">
    <div>
        <div class="persona-label">Mode Tampilan</div>
        <div class="persona-hint" id="persona-hint">Menampilkan semua section untuk eksplorasi mendalam</div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        <div class="persona-switch">
            <button class="persona-btn analyst active" id="btn-analyst" onclick="setPersona('analyst')">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Analyst
            </button>
            <button class="persona-btn pejabat" id="btn-pejabat" onclick="setPersona('pejabat')">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                Ringkas
            </button>
        </div>
    </div>
</div>

<div class="section-ctrl-panel" id="sectionCtrlPanel">
    <div class="section-ctrl-head" onclick="toggleCtrlPanel()">
        <div class="section-ctrl-title">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            Kustomisasi Tampilan
            <span style="font-size:10px;font-weight:400;color:var(--gray-400);">Centang section yang ingin ditampilkan</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
            <button class="ctrl-reset-btn" onclick="event.stopPropagation();resetSections()">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.36"/></svg>
                Reset
            </button>
            <svg class="ctrl-caret open" id="ctrlCaret" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
        </div>
    </div>
    <div class="section-ctrl-body" id="ctrlBody">
        <div class="ctrl-chip active" data-section="makro"    onclick="toggleSection('makro')">   <span class="chip-dot"></span> Ringkasan Makro</div>
        <div class="ctrl-chip active" data-section="insight"  onclick="toggleSection('insight')"> <span class="chip-dot"></span> Insight Otomatis</div>
        <div class="ctrl-chip active" data-section="struktur" onclick="toggleSection('struktur')"><span class="chip-dot"></span> Struktur &amp; Heatmap</div>
        <div class="ctrl-chip active" data-section="trend"    onclick="toggleSection('trend')">   <span class="chip-dot"></span> Trend &amp; Pertumbuhan</div>
        <div class="ctrl-chip active" data-section="group"    onclick="toggleSection('group')">   <span class="chip-dot"></span> Kelompok Ekonomi</div>
        <div class="ctrl-chip active" data-section="ranking"  onclick="toggleSection('ranking')"> <span class="chip-dot"></span> Peringkat YoY</div>
        <div class="ctrl-chip active" data-section="tabel"    onclick="toggleSection('tabel')">   <span class="chip-dot"></span> Tabel Detail</div>
    </div>
</div>

<div id="sec-makro" class="section-collapsible" style="max-height:2000px;">
<div class="section-label">Ringkasan Makro</div>
<div class="metric-grid" style="margin-bottom:12px;">
    <div class="metric-card orange">
        <div class="metric-label">PDRB ADHB</div>
        <div class="metric-value">Rp {{ number_format($total_adhb, 3, ',', '.') }} M</div>
        <div class="metric-sub">Harga Berlaku</div>
    </div>
    <div class="metric-card green">
        <div class="metric-label">PDRB ADHK</div>
        <div class="metric-value">Rp {{ number_format($total_adhk, 3, ',', '.') }} M</div>
        <div class="metric-sub">Harga Konstan</div>
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
</div>

@php
    $topSektor = $top5->first();
    $botSektor = $bottom5->first();
    $topStr    = collect($struktur_data)->sortByDesc('pct')->first();
@endphp
<div id="sec-insight" class="section-collapsible" style="max-height:2000px;">
<div class="insight-grid" style="margin-bottom:12px;">
    <div class="insight-item">
        <div class="insight-icon" style="background:var(--green-lt);">🚀</div>
        <div class="insight-text">
            <h5>Sektor Tumbuh Tercepat</h5>
            <p><strong>{{ $topSektor?->sektor?->nama ?? 'N/A' }}</strong> tumbuh <strong style="color:var(--green);">+{{ number_format($topSektor?->growth_yoy ?? 0, 2) }}%</strong> YoY, tertinggi periode ini.</p>
        </div>
    </div>
    <div class="insight-item">
        <div class="insight-icon" style="background:var(--orange-lt);">🏆</div>
        <div class="insight-text">
            <h5>Kelompok Dominan</h5>
            <p>Kelompok <strong>{{ $topStr['label'] ?? 'N/A' }}</strong> mendominasi ADHB dengan kontribusi <strong style="color:var(--orange);">{{ $topStr['pct'] ?? 0 }}%</strong>.</p>
        </div>
    </div>
    <div class="insight-item">
        <div class="insight-icon" style="background:var(--red-lt);">📉</div>
        <div class="insight-text">
            <h5>Sektor Kontraksi</h5>
            <p><strong>{{ $botSektor?->sektor?->nama ?? 'N/A' }}</strong> mencatat pertumbuhan terendah <strong style="color:var(--red);">{{ number_format($botSektor?->growth_yoy ?? 0, 2) }}%</strong> YoY.</p>
        </div>
    </div>
</div>
</div>

<div id="sec-struktur" class="section-collapsible" style="max-height:5000px;">
<div class="section-label">Struktur Ekonomi &amp; Dominansi Sektor</div>
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
            <div class="chart-box" style="height:130px;margin-bottom:16px;">
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
                        <div class="progress-fill" style="width:{{ $grp['pct'] }}%;background:{{ $grp['warna'] }};"></div>
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
            <p style="font-size:11px;color:var(--gray-400);margin-bottom:10px;">Klik sel untuk fokus pada sektor. Warna lebih gelap menunjukkan kontribusi lebih besar.</p>
            <div class="heatmap-grid" id="heatmapGrid">
                @foreach($heatmap_data as $idx => $cell)
                @php
                    $i  = $cell['intensitas'];
                    $r  = 249 - round($i * 80);
                    $g  = round(242 - $i * 127);
                    $b  = round(232 - $i * 210);
                    $bg = "rgba({$r},{$g},{$b},1)";
                    $tc = $i > 0.45 ? 'rgba(255,255,255,0.95)' : '#92400e';
                    $ns = mb_strlen($cell['nama']) > 16 ? mb_substr($cell['nama'], 0, 14) . '…' : $cell['nama'];
                @endphp
                <div class="hm-cell"
                     style="background:{{ $bg }};"
                     title="{{ $cell['nama'] }}: {{ $cell['kontribusi'] }}%"
                     data-sektor="{{ $cell['nama'] }}"
                     data-idx="{{ $idx }}"
                     onclick="heatmapFocus(this)">
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
</div>

<div id="sec-trend" class="section-collapsible" style="max-height:5000px;">
<div class="section-label">Analisis Pertumbuhan &amp; Trend</div>
<div class="grid-2" style="margin-bottom:12px;align-items:stretch;">
    <div style="display:flex;flex-direction:column;gap:12px;">
        <div class="card" style="flex:1;display:flex;flex-direction:column;">
            <div class="card-head">
                <div class="card-head-left">
                    <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <span class="card-title">Trend PDRB ADHK</span>
                </div>
                <span class="badge badge-orange">Harga Konstan</span>
            </div>
            <div class="card-body" style="flex:1;display:flex;flex-direction:column;">
                <div class="chart-box" style="flex:1;min-height:130px;">
                    <canvas id="chartTrend"></canvas>
                </div>
            </div>
        </div>
        <div class="card" style="flex:1;display:flex;flex-direction:column;">
            <div class="card-head">
                <div class="card-head-left">
                    <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <span class="card-title">Trend PDRB ADHB</span>
                </div>
                <span class="badge badge-purple">Harga Berlaku</span>
            </div>
            <div class="card-body" style="flex:1;display:flex;flex-direction:column;">
                <div class="chart-box" style="flex:1;min-height:130px;">
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
                <div class="legend-item"><div class="legend-sq" style="background:rgba(34,197,94,.75)"></div>Positif</div>
                <div class="legend-item"><div class="legend-sq" style="background:rgba(239,68,68,.75)"></div>Negatif</div>
            </div>
            <div class="chart-box" style="height:{{ count($growth_data) * 28 + 60 }}px;">
                <canvas id="chartGrowth"></canvas>
            </div>
        </div>
    </div>
</div>
</div>

<div id="sec-group" class="section-collapsible" style="max-height:2000px;">
<div class="card" style="margin-bottom:12px;">
    <div class="card-head">
        <div class="card-head-left">
            <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            <span class="card-title">Kontribusi &amp; Pertumbuhan per Kelompok Ekonomi</span>
        </div>
        <span class="badge badge-purple">Analisis Kelompok</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1px 1fr;gap:0;align-items:start;">
            <div style="padding-right:18px;">
                <div style="font-size:10.5px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                    <span style="width:10px;height:10px;border-radius:2px;background:var(--orange);flex-shrink:0;display:inline-block;"></span>
                    Kontribusi ADHB
                </div>
                <div class="chart-box" style="height:180px;"><canvas id="chartGroupKontrib"></canvas></div>
            </div>
            <div class="v-divider" style="height:200px;align-self:center;"></div>
            <div style="padding-left:18px;">
                <div style="font-size:10.5px;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                    <span style="width:10px;height:10px;border-radius:2px;background:var(--blue);flex-shrink:0;display:inline-block;"></span>
                    Pertumbuhan YoY ADHK
                </div>
                <div class="chart-box" style="height:180px;"><canvas id="chartGroupYoy"></canvas></div>
            </div>
        </div>
    </div>
</div>
</div>

<div id="sec-ranking" class="section-collapsible" style="max-height:2000px;">
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
                <div class="rank-item" style="cursor:pointer;" onclick="openDrill({{ $item->sektor_id }})">
                    <span class="rank-num">{{ $i + 1 }}</span>
                    <span class="rank-name">{{ $item->sektor->nama ?? 'N/A' }}</span>
                    <div class="rank-bar-wrap">
                        <div class="rank-bar" style="width:{{ min(100, $pct/$maxPct*100) }}%;background:var(--green);"></div>
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
                <div class="rank-item" style="cursor:pointer;" onclick="openDrill({{ $item->sektor_id }})">
                    <span class="rank-num">{{ $i + 1 }}</span>
                    <span class="rank-name">{{ $item->sektor->nama ?? 'N/A' }}</span>
                    <div class="rank-bar-wrap">
                        <div class="rank-bar" style="width:{{ min(100, $pct/$maxPct*100) }}%;background:var(--red);"></div>
                    </div>
                    <span class="rank-badge rank-badge-down">▼ {{ number_format($item->growth_yoy, 2, ',', '.') }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>

<div id="sec-tabel" class="section-collapsible" style="max-height:99999px;">
<div class="section-label">Data Detail per Sektor</div>
<div class="card">
    <div class="card-head">
        <div class="card-head-left">
            <svg class="card-head-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
            <span class="card-title">Tabel PDRB Semua Sektor</span>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <span class="badge badge-orange" id="sektorCountBadge">{{ $data->count() }} sektor</span>
            <span class="selected-counter hidden" id="selectedCounter">0 dipilih</span>
        </div>
    </div>

    <div class="sektor-filter-bar">
        <span class="sektor-filter-label">Filter</span>
        <input type="text" class="sektor-search" id="sektorSearch" placeholder="Cari sektor…" oninput="filterTable()">
        <div class="sektor-kelompok-btns" id="kelompokBtns">
            <button class="kelompok-btn k-active" data-kelompok="semua" style="background:#374151;border-color:#374151;" onclick="filterKelompok('semua',this)">Semua</button>
            @foreach($struktur_data as $grp)
            <button class="kelompok-btn" data-kelompok="{{ $grp['key'] }}"
                    style="--k-color:{{ $grp['warna'] }};"
                    onclick="filterKelompok('{{ $grp['key'] }}',this)">{{ $grp['label'] }}</button>
            @endforeach
        </div>
        <div class="sektor-actions">
            <button class="sektor-action-btn" onclick="selectAllVisible()">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Pilih Semua
            </button>
            <button class="sektor-action-btn btn-clear" onclick="clearSelection()">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Bersihkan
            </button>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="dash-table" id="sektorTable">
            <thead>
                <tr>
                    <th class="row-checkbox-cell">
                        <input type="checkbox" class="sektor-row-check" id="checkAll" onchange="toggleCheckAll(this)">
                    </th>
                    <th>Sektor</th>
                    <th>Kelompok</th>
                    <th>ADHB <span style="font-weight:400;text-transform:none;font-size:9px;color:var(--gray-300);">(Miliar Rp)</span></th>
                    <th>ADHK <span style="font-weight:400;text-transform:none;font-size:9px;color:var(--gray-300);">(Miliar Rp)</span></th>
                    <th>Kontribusi</th>
                    <th>QoQ</th>
                    <th>YoY</th>
                    <th style="text-align:center;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                @php
                    $kel      = $row->kelompok ?? 'tersier';
                    $kelLabel = ['primer'=>'Primer','sekunder'=>'Sekunder','tersier'=>'Tersier'][$kel] ?? 'N/A';
                    $kelWarna = match($kel) { 'primer'=>'#22c55e','sekunder'=>'#3b82f6',default=>'#f97316' };
                @endphp
                <tr data-sektor-id="{{ $row->sektor_id }}"
                    data-nama="{{ strtolower($row->sektor->nama ?? '') }}"
                    data-kelompok="{{ $kel }}">
                    <td class="row-checkbox-cell">
                        <input type="checkbox" class="sektor-row-check row-check"
                               data-sektor-id="{{ $row->sektor_id }}"
                               onchange="onRowCheck(this)">
                    </td>
                    <td>{{ $row->sektor->nama ?? 'N/A' }}</td>
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
                    <td style="text-align:center;">
                        <button onclick="openDrill({{ $row->sektor_id }})"
                                style="border:none;background:var(--orange-lt);color:var(--orange);border-radius:6px;padding:3px 10px;font-size:11px;font-weight:600;cursor:pointer;font-family:var(--font);">
                            Detail →
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;color:var(--gray-400);padding:40px;">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
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

<div class="drill-overlay" id="drillOverlay" onclick="closeDrill()"></div>
<div class="drill-panel" id="drillPanel">
    <div class="drill-head">
        <div class="drill-head-info">
            <h4 id="drillTitle">Detail Sektor</h4>
            <p id="drillSubtitle">Data PDRB periode ini</p>
        </div>
        <button class="drill-close" onclick="closeDrill()">&#x2715;</button>
    </div>
    <div class="drill-body" id="drillBody">
        <div style="text-align:center;padding:40px;color:var(--gray-400);">Memuat data…</div>
    </div>
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
    if (suc) Swal.fire({ icon:'success', title:'Berhasil!', text: suc.value, timer:2200, showConfirmButton:false });
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

@php
$_sektorJs = $data->map(function($r) {
    return [
        'sektor_id'  => $r->sektor_id,
        'nama'       => $r->sektor->nama ?? 'N/A',
        'kelompok'   => $r->kelompok ?? 'tersier',
        'adhb'       => round($r->adhb ?? 0, 2),
        'adhk'       => round($r->adhk ?? 0, 2),
        'kontribusi' => round($r->kontribusi ?? 0, 2),
        'growth_qoq' => round($r->growth_qoq ?? 0, 2),
        'growth_yoy' => round($r->growth_yoy ?? 0, 2),
    ];
})->values();
@endphp
const ALL_SEKTOR_DATA = @json($_sektorJs);

const TOTAL_ADHB       = {{ $total_adhb }};
const GROWTH_QOQ_TOTAL = {{ $growth_qoq_total }};
const GROWTH_YOY_TOTAL = {{ $growth_yoy_total }};

const KELOMPOK_LABELS = @json(collect($struktur_data)->pluck('label','key'));
const KELOMPOK_COLORS = @json(collect($struktur_data)->pluck('warna','key'));

Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#6b7280';

document.addEventListener('DOMContentLoaded', function () {
    buildDonut();
    buildTrend();
    buildTrendAdhb();
    buildGrowth();
    buildGroupBar();
    initSectionStates();
    initPersona();
});

function buildDonut() {
    new Chart(document.getElementById('chartDonut'), {
        type: 'doughnut',
        data: {
            labels: STRUKTUR_DATA.map(s => s.label),
            datasets: [{ data: STRUKTUR_DATA.map(s => s.pct), backgroundColor: STRUKTUR_DATA.map(s => s.warna+'cc'), borderWidth:0, hoverOffset:6 }]
        },
        options: {
            responsive:true, maintainAspectRatio:false, cutout:'65%',
            plugins: { legend:{display:false}, tooltip:{callbacks:{label:ctx=>` ${ctx.label}: ${ctx.raw.toFixed(1)}%`}} }
        }
    });
}

function buildTrend() {
    new Chart(document.getElementById('chartTrend'), {
        type: 'line',
        data: {
            labels: TREND_LABELS,
            datasets: [{ label:'ADHK', data:TREND_ADHK, borderColor:'#f97316', backgroundColor:'rgba(249,115,22,.07)', borderWidth:2, pointBackgroundColor:'#f97316', pointRadius:3.5, pointHoverRadius:5, tension:.35, fill:true }]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            interaction:{mode:'index',intersect:false},
            plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>` ${ctx.raw.toFixed(3)} Miliar`}}},
            scales:{
                x:{grid:{color:'#f3f4f6'},border:{color:'#e5e7eb'},ticks:{maxRotation:30}},
                y:{grid:{color:'#f3f4f6'},border:{color:'#e5e7eb'}}
            }
        }
    });
}

function buildTrendAdhb() {
    new Chart(document.getElementById('chartTrendAdhb'), {
        type: 'line',
        data: {
            labels: TREND_LABELS,
            datasets: [{ label:'ADHB', data:TREND_ADHB, borderColor:'#7c3aed', backgroundColor:'rgba(124,58,237,.07)', borderWidth:2, pointBackgroundColor:'#7c3aed', pointRadius:3.5, pointHoverRadius:5, tension:.35, fill:true }]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            interaction:{mode:'index',intersect:false},
            plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>` ${ctx.raw.toFixed(3)} Miliar`}}},
            scales:{
                x:{grid:{color:'#f3f4f6'},border:{color:'#e5e7eb'},ticks:{maxRotation:30}},
                y:{grid:{color:'#f3f4f6'},border:{color:'#e5e7eb'}}
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
                backgroundColor: GROWTH_DATA.map(v => v>=0?'rgba(34,197,94,.75)':'rgba(239,68,68,.75)'),
                borderRadius:3, borderSkipped:false
            }]
        },
        options: {
            indexAxis:'y', responsive:true, maintainAspectRatio:false,
            plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>` ${ctx.raw.toFixed(2)}%`}}},
            scales:{
                x:{grid:{color:'#f3f4f6'},border:{color:'#e5e7eb'},ticks:{callback:v=>v+'%'}},
                y:{grid:{display:false},border:{display:false},ticks:{font:{size:10}}}
            }
        }
    });
}

function buildGroupBar() {
    new Chart(document.getElementById('chartGroupKontrib'), {
        type:'bar',
        data:{
            labels:GROUP_LABELS,
            datasets:[{data:GROUP_KONTRIB,backgroundColor:GROUP_COLORS.map(c=>c+'bf'),borderRadius:6,borderSkipped:false}]
        },
        options:{
            responsive:true, maintainAspectRatio:false,
            plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>` ${ctx.raw.toFixed(1)}%`}}},
            scales:{
                x:{grid:{display:false},border:{color:'#e5e7eb'},ticks:{font:{size:11,weight:'600'}}},
                y:{grid:{color:'#f3f4f6'},border:{color:'#e5e7eb'},beginAtZero:true,ticks:{callback:v=>v+'%',font:{size:10}}}
            }
        }
    });
    new Chart(document.getElementById('chartGroupYoy'), {
        type:'bar',
        data:{
            labels:GROUP_LABELS,
            datasets:[{data:GROUP_YOY_AVG,backgroundColor:GROUP_YOY_AVG.map(v=>v>=0?'rgba(34,197,94,.8)':'rgba(239,68,68,.8)'),borderRadius:6,borderSkipped:false}]
        },
        options:{
            responsive:true, maintainAspectRatio:false,
            plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>` ${ctx.raw.toFixed(2)}%`}}},
            scales:{
                x:{grid:{display:false},border:{color:'#e5e7eb'},ticks:{font:{size:11,weight:'600'}}},
                y:{
                    grid:{color:ctx=>ctx.tick.value===0?'#374151':'#f3f4f6',lineWidth:ctx=>ctx.tick.value===0?1.5:1},
                    border:{color:'#e5e7eb'},ticks:{callback:v=>v+'%',font:{size:10}}
                }
            }
        }
    });
}

const ANALYST_SECTIONS = ['makro','insight','struktur','trend','group','ranking','tabel'];
const PEJABAT_SECTIONS = ['makro','insight','ranking'];

const PERSONA_HINT = {
    analyst: 'Menampilkan semua section untuk eksplorasi mendalam',
    pejabat: 'Mode ringkas: hanya metrik utama, insight, dan peringkat'
};

let currentPersona = localStorage.getItem('dash_persona') || 'analyst';

function initPersona() {
    applyPersona(currentPersona, false);
}

function setPersona(p) {
    currentPersona = p;
    localStorage.setItem('dash_persona', p);
    applyPersona(p, true);
}

function applyPersona(p, animate) {
    document.getElementById('btn-analyst').classList.toggle('active', p === 'analyst');
    document.getElementById('btn-pejabat').classList.toggle('active', p === 'pejabat');
    document.getElementById('persona-hint').textContent = PERSONA_HINT[p];

    if (p === 'pejabat') {
        ANALYST_SECTIONS.forEach(s => {
            const shouldShow = PEJABAT_SECTIONS.includes(s);
            setSectionVisible(s, shouldShow, animate);
            const chip = document.querySelector(`.ctrl-chip[data-section="${s}"]`);
            if (chip) chip.classList.toggle('active', shouldShow);
        });
    } else {
        ANALYST_SECTIONS.forEach(s => {
            const saved = localStorage.getItem('dash_sec_' + s);
            const show  = saved === null ? true : saved === '1';
            setSectionVisible(s, show, animate);
            const chip = document.querySelector(`.ctrl-chip[data-section="${s}"]`);
            if (chip) chip.classList.toggle('active', show);
        });
    }
}

let ctrlOpen = true;

function initSectionStates() {
    document.querySelectorAll('.ctrl-chip').forEach(chip => {
        const key   = chip.dataset.section;
        const saved = localStorage.getItem('dash_sec_' + key);
        if (saved === '0') {
            chip.classList.remove('active');
            setSectionVisible(key, false, false);
        }
    });
}

function toggleCtrlPanel() {
    ctrlOpen = !ctrlOpen;
    const body  = document.getElementById('ctrlBody');
    const caret = document.getElementById('ctrlCaret');
    body.style.display  = ctrlOpen ? '' : 'none';
    caret.classList.toggle('open', ctrlOpen);
}

function toggleSection(key) {
    if (currentPersona === 'pejabat') return;
    const chip = document.querySelector(`.ctrl-chip[data-section="${key}"]`);
    const isOn = chip.classList.toggle('active');
    setSectionVisible(key, isOn, true);
    localStorage.setItem('dash_sec_' + key, isOn ? '1' : '0');
}

function setSectionVisible(key, show, animate) {
    const el = document.getElementById('sec-' + key);
    if (!el) return;
    if (show) {
        el.style.maxHeight = '99999px';
        el.style.opacity   = '1';
        el.classList.remove('collapsed');
    } else {
        el.style.maxHeight = '0';
        el.style.opacity   = '0';
        el.classList.add('collapsed');
    }
}

function resetSections() {
    if (currentPersona === 'pejabat') { setPersona('analyst'); return; }
    document.querySelectorAll('.ctrl-chip').forEach(chip => {
        chip.classList.add('active');
        setSectionVisible(chip.dataset.section, true, true);
        localStorage.removeItem('dash_sec_' + chip.dataset.section);
    });
}

let activeKelompok    = 'semua';
let selectedSektorIds = new Set();

function filterTable() {
    const q    = document.getElementById('sektorSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#sektorTable tbody tr[data-nama]');

    rows.forEach(row => {
        const nama   = row.dataset.nama || '';
        const kel    = row.dataset.kelompok || '';
        const matchQ = q === '' || nama.includes(q);
        const matchK = activeKelompok === 'semua' || kel === activeKelompok;
        row.style.display = (matchQ && matchK) ? '' : 'none';
    });

    updateCounter();
}

function filterKelompok(kel, btn) {
    activeKelompok = kel;

    document.querySelectorAll('.kelompok-btn').forEach(b => {
        b.classList.remove('k-active');
        b.style.background  = '';
        b.style.borderColor = '';
        b.style.color       = '';
    });

    btn.classList.add('k-active');
    if (kel === 'semua') {
        btn.style.background  = '#374151';
        btn.style.borderColor = '#374151';
        btn.style.color       = '#fff';
    } else {
        const color = btn.style.getPropertyValue('--k-color') || (KELOMPOK_COLORS[kel] || '#f97316');
        btn.style.background  = color;
        btn.style.borderColor = color;
        btn.style.color       = '#fff';
    }

    filterTable();
}

function onRowCheck(cb) {
    const id  = parseInt(cb.dataset.sektorId);
    const row = cb.closest('tr');
    if (cb.checked) {
        selectedSektorIds.add(id);
        row.classList.add('row-focused');
    } else {
        selectedSektorIds.delete(id);
        row.classList.remove('row-focused');
    }
    applyFocus();
    updateCounter();
}

function applyFocus() {
    const rows = document.querySelectorAll('#sektorTable tbody tr[data-sektor-id]');
    if (selectedSektorIds.size === 0) {
        rows.forEach(r => r.classList.remove('row-dimmed', 'row-focused'));
        return;
    }
    rows.forEach(r => {
        const id = parseInt(r.dataset.sektorId);
        if (selectedSektorIds.has(id)) {
            r.classList.remove('row-dimmed');
            r.classList.add('row-focused');
        } else {
            r.classList.add('row-dimmed');
            r.classList.remove('row-focused');
        }
    });
}

function selectAllVisible() {
    const rows = document.querySelectorAll('#sektorTable tbody tr[data-sektor-id]');
    rows.forEach(row => {
        if (row.style.display === 'none') return;
        const cb = row.querySelector('.row-check');
        const id = parseInt(row.dataset.sektorId);
        if (cb) cb.checked = true;
        selectedSektorIds.add(id);
        row.classList.add('row-focused');
    });
    applyFocus();
    updateCounter();
}

function clearSelection() {
    selectedSektorIds.clear();
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
    document.getElementById('checkAll').checked = false;
    applyFocus();
    updateCounter();
}

function toggleCheckAll(masterCb) {
    const rows = document.querySelectorAll('#sektorTable tbody tr[data-sektor-id]');
    rows.forEach(row => {
        if (row.style.display === 'none') return;
        const cb = row.querySelector('.row-check');
        const id = parseInt(row.dataset.sektorId);
        if (masterCb.checked) {
            if (cb) cb.checked = true;
            selectedSektorIds.add(id);
            row.classList.add('row-focused');
        } else {
            if (cb) cb.checked = false;
            selectedSektorIds.delete(id);
            row.classList.remove('row-focused', 'row-dimmed');
        }
    });
    applyFocus();
    updateCounter();
}

function updateCounter() {
    const counter = document.getElementById('selectedCounter');
    if (selectedSektorIds.size > 0) {
        counter.textContent = selectedSektorIds.size + ' dipilih';
        counter.classList.remove('hidden');
    } else {
        counter.classList.add('hidden');
    }
}

let drillChart = null;

function openDrill(sektorId) {
    const d = ALL_SEKTOR_DATA.find(s => s.sektor_id === sektorId);
    if (!d) return;

    document.getElementById('drillTitle').textContent    = d.nama;
    document.getElementById('drillSubtitle').textContent = KELOMPOK_LABELS[d.kelompok] + ' \u00B7 PDRB {{ $tahun }} TW{{ $triwulan }}';

    const sorted      = [...ALL_SEKTOR_DATA].sort((a,b) => b.kontribusi - a.kontribusi);
    const rankKontrib = sorted.findIndex(s => s.sektor_id === sektorId) + 1;
    const sortedYoy   = [...ALL_SEKTOR_DATA].sort((a,b) => b.growth_yoy - a.growth_yoy);
    const rankYoy     = sortedYoy.findIndex(s => s.sektor_id === sektorId) + 1;
    const totalSektor = ALL_SEKTOR_DATA.length;

    const kelGroup  = ALL_SEKTOR_DATA.filter(s => s.kelompok === d.kelompok);
    const avgYoyGrp = kelGroup.length ? (kelGroup.reduce((a,c) => a+c.growth_yoy, 0) / kelGroup.length).toFixed(2) : 0;
    const vsAvg     = (d.growth_yoy - avgYoyGrp).toFixed(2);

    const kelColor = KELOMPOK_COLORS[d.kelompok] || '#f97316';

    const html = `
        <div>
            <div class="drill-section-title">Metrik Utama</div>
            <div class="drill-metric-row">
                <div class="drill-metric">
                    <div class="drill-metric-label">ADHB</div>
                    <div class="drill-metric-value" style="font-size:15px;">${formatRp(d.adhb)}</div>
                    <div class="drill-metric-sub">Miliar Rp</div>
                </div>
                <div class="drill-metric">
                    <div class="drill-metric-label">ADHK</div>
                    <div class="drill-metric-value" style="font-size:15px;">${formatRp(d.adhk)}</div>
                    <div class="drill-metric-sub">Miliar Rp</div>
                </div>
                <div class="drill-metric">
                    <div class="drill-metric-label">Kontribusi</div>
                    <div class="drill-metric-value" style="color:${kelColor};">${d.kontribusi}%</div>
                    <div class="drill-metric-sub">dari total ADHB</div>
                </div>
            </div>
        </div>

        <div>
            <div class="drill-section-title">Pertumbuhan</div>
            <div class="drill-metric-row">
                <div class="drill-metric">
                    <div class="drill-metric-label">QoQ</div>
                    <div class="drill-metric-value" style="font-size:20px;color:${d.growth_qoq >= 0 ? 'var(--green)' : 'var(--red)'};">
                        ${d.growth_qoq >= 0 ? '▲' : '▼'} ${Math.abs(d.growth_qoq).toFixed(2)}%
                    </div>
                    <div class="drill-metric-sub">vs triwulan lalu</div>
                </div>
                <div class="drill-metric">
                    <div class="drill-metric-label">YoY</div>
                    <div class="drill-metric-value" style="font-size:20px;color:${d.growth_yoy >= 0 ? 'var(--green)' : 'var(--red)'};">
                        ${d.growth_yoy >= 0 ? '▲' : '▼'} ${Math.abs(d.growth_yoy).toFixed(2)}%
                    </div>
                    <div class="drill-metric-sub">vs tahun lalu</div>
                </div>
                <div class="drill-metric">
                    <div class="drill-metric-label">vs Rata-rata Kelompok</div>
                    <div class="drill-metric-value" style="font-size:18px;color:${vsAvg >= 0 ? 'var(--green)' : 'var(--red)'};">
                        ${vsAvg >= 0 ? '+' : ''}${vsAvg}%
                    </div>
                    <div class="drill-metric-sub">rata-rata ${avgYoyGrp}% YoY</div>
                </div>
            </div>
        </div>

        <div>
            <div class="drill-section-title">Posisi Ranking</div>
            <div class="drill-stat-list">
                <div class="drill-stat-item">
                    <span class="drill-stat-name">Peringkat Kontribusi ADHB</span>
                    <span class="drill-stat-val">
                        <span style="background:${kelColor}18;color:${kelColor};padding:2px 10px;border-radius:6px;font-size:12px;">
                            #${rankKontrib} dari ${totalSektor}
                        </span>
                    </span>
                </div>
                <div class="drill-stat-item">
                    <span class="drill-stat-name">Peringkat Pertumbuhan YoY</span>
                    <span class="drill-stat-val">
                        <span style="background:${d.growth_yoy>=0?'var(--green-lt)':'var(--red-lt)'};color:${d.growth_yoy>=0?'var(--green)':'var(--red)'};padding:2px 10px;border-radius:6px;font-size:12px;">
                            #${rankYoy} dari ${totalSektor}
                        </span>
                    </span>
                </div>
                <div class="drill-stat-item">
                    <span class="drill-stat-name">Kelompok Ekonomi</span>
                    <span class="drill-stat-val">
                        <span style="background:${kelColor}18;color:${kelColor};padding:2px 10px;border-radius:6px;font-size:12px;">
                            ${KELOMPOK_LABELS[d.kelompok] || 'N/A'}
                        </span>
                    </span>
                </div>
                <div class="drill-stat-item">
                    <span class="drill-stat-name">Jumlah sektor sekelompok</span>
                    <span class="drill-stat-val">${kelGroup.length} sektor</span>
                </div>
            </div>
        </div>

        <div>
            <div class="drill-section-title">Perbandingan dalam Kelompok</div>
            <div class="drill-chart-wrap">
                <canvas id="drillBarChart"></canvas>
            </div>
        </div>
    `;

    document.getElementById('drillBody').innerHTML = html;

    const grpSorted = [...kelGroup].sort((a,b) => b.kontribusi - a.kontribusi);
    const grpLabels = grpSorted.map(s => s.nama.length > 20 ? s.nama.substring(0,18)+'…' : s.nama);
    const grpData   = grpSorted.map(s => s.kontribusi);
    const grpColors = grpSorted.map(s => s.sektor_id === sektorId ? kelColor : kelColor + '55');

    if (drillChart) drillChart.destroy();
    drillChart = new Chart(document.getElementById('drillBarChart'), {
        type: 'bar',
        data: {
            labels: grpLabels,
            datasets: [{ data: grpData, backgroundColor: grpColors, borderRadius: 4, borderSkipped: false }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.raw.toFixed(2)}% kontribusi` } } },
            scales: {
                x: { grid: { color: '#f3f4f6' }, ticks: { callback: v => v + '%' } },
                y: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    document.getElementById('drillOverlay').classList.add('open');
    document.getElementById('drillPanel').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeDrill() {
    document.getElementById('drillOverlay').classList.remove('open');
    document.getElementById('drillPanel').classList.remove('open');
    document.body.style.overflow = '';
    if (drillChart) { drillChart.destroy(); drillChart = null; }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrill(); });

function heatmapFocus(cell) {
    const allCells = document.querySelectorAll('.hm-cell');
    const isActive = cell.classList.contains('hm-focused');

    allCells.forEach(c => c.classList.remove('hm-focused', 'hm-dimmed'));

    if (!isActive) {
        cell.classList.add('hm-focused');
        allCells.forEach(c => {
            if (c !== cell) c.classList.add('hm-dimmed');
        });

        const nama = cell.dataset.sektor.toLowerCase();
        document.getElementById('sektorSearch').value = nama;
        filterTable();
    } else {
        document.getElementById('sektorSearch').value = '';
        filterTable();
    }
}

function formatRp(num) {
    return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
}
</script>
@endif
@endsection