@extends('layouts/app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --c-bg:       #f7f6f3;
    --c-surface:  #ffffff;
    --c-border:   #e4e2dc;
    --c-border-s: #ccc9c0;
    --c-ink:      #1a1916;
    --c-ink-2:    #4a4843;
    --c-ink-3:    #8a8880;
    --c-orange:   #e05c1a;
    --c-orange-l: #fff3ec;
    --c-orange-b: #f87c3a;
    --c-green:    #1a7a45;
    --c-green-l:  #edf7f1;
    --c-blue:     #1a4f8a;
    --c-blue-l:   #eaf1f9;
    --c-red:      #c22b2b;
    --c-red-l:    #fdf0f0;
    --c-amber:    #b05f0a;
    --c-amber-l:  #fef7ec;
    --r-sm:       6px;
    --r-md:       10px;
    --r-lg:       14px;
    --font:       'DM Sans', sans-serif;
    --font-mono:  'DM Mono', monospace;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
}

body { font-family: var(--font); background: var(--c-bg); }

/* Layout */
.dl-page {
    padding: 84px 28px 60px;
    max-width: 1280px;
    margin: 0 auto;
}

.dl-header {
    margin-bottom: 28px;
}
.dl-header h1 {
    font-size: 22px;
    font-weight: 600;
    color: var(--c-ink);
    letter-spacing: -0.3px;
}
.dl-header p {
    font-size: 13.5px;
    color: var(--c-ink-3);
    margin-top: 4px;
}

/* stat strip */
.stat-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 24px;
}
.stat-card {
    background: var(--c-surface);
    border: 0.5px solid var(--c-border);
    border-radius: var(--r-lg);
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.stat-icon {
    width: 38px; height: 38px;
    border-radius: var(--r-md);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stat-icon svg { width: 18px; height: 18px; }
.stat-icon.orange { background: var(--c-orange-l); color: var(--c-orange); }
.stat-icon.green  { background: var(--c-green-l);  color: var(--c-green); }
.stat-icon.blue   { background: var(--c-blue-l);   color: var(--c-blue); }
.stat-icon.amber  { background: var(--c-amber-l);  color: var(--c-amber); }
.stat-label { font-size: 11.5px; color: var(--c-ink-3); margin-bottom: 2px; }
.stat-value { font-size: 20px; font-weight: 600; color: var(--c-ink); letter-spacing: -0.5px; }

/* main grid */
.main-grid {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 16px;
    align-items: start;
}
@media (max-width: 1000px) { .main-grid { grid-template-columns: 1fr; } }

/* Panel / Card */
.panel {
    background: var(--c-surface);
    border: 0.5px solid var(--c-border);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 0.5px solid var(--c-border);
    background: #fafaf8;
}
.panel-head-left { display: flex; align-items: center; gap: 9px; }
.panel-title { font-size: 13.5px; font-weight: 600; color: var(--c-ink); }
.panel-body { padding: 20px; }

/* Form */
.form-stack { display: flex; flex-direction: column; gap: 16px; }
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

.field { display: flex; flex-direction: column; gap: 5px; }
.field-label {
    font-size: 11.5px;
    font-weight: 600;
    color: var(--c-ink-2);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.field-label em { color: var(--c-red); font-style: normal; margin-left: 2px; }

.form-select, .form-input {
    height: 40px;
    padding: 0 12px;
    font-size: 13.5px;
    font-family: var(--font);
    color: var(--c-ink);
    background: var(--c-surface);
    border: 0.5px solid var(--c-border-s);
    border-radius: var(--r-md);
    outline: none;
    cursor: pointer;
    width: 100%;
    transition: border-color 0.15s, box-shadow 0.15s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238a8880' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
}
.form-select:focus, .form-input:focus {
    border-color: var(--c-orange);
    box-shadow: 0 0 0 3px rgba(224,92,26,0.1);
}
.form-select.invalid, .form-input.invalid {
    border-color: var(--c-red);
    box-shadow: 0 0 0 3px rgba(194,43,43,0.08);
}
.field-err { font-size: 11.5px; color: var(--c-red); }

/* format toggle */
.format-toggle {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.format-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px;
    border: 0.5px solid var(--c-border-s);
    border-radius: var(--r-md);
    cursor: pointer;
    background: var(--c-surface);
    transition: all 0.15s;
    font-family: var(--font);
}
.format-btn:hover { border-color: var(--c-orange); background: var(--c-orange-l); }
.format-btn.active {
    border-color: var(--c-orange);
    background: var(--c-orange-l);
    box-shadow: 0 0 0 1px var(--c-orange);
}
.format-icon {
    width: 28px; height: 28px; border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; font-family: var(--font-mono);
    flex-shrink: 0;
}
.format-icon.pdf { background: #fee2e2; color: #991b1b; }
.format-icon.csv { background: #d1fae5; color: #065f46; }
.format-label { font-size: 12.5px; font-weight: 500; color: var(--c-ink); }
.format-sub   { font-size: 11px; color: var(--c-ink-3); }

/* divider */
.divider { border: none; border-top: 0.5px solid var(--c-border); margin: 4px 0; }

/* all-kategori notice */
.all-notice {
    display: none; padding: 10px 14px;
    background: var(--c-amber-l);
    border: 0.5px solid #f0c07a;
    border-radius: var(--r-md);
    font-size: 12.5px; color: var(--c-amber); line-height: 1.6;
}
.all-notice.show { display: block; }

/* Column configurator */
.col-config-toggle {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    border: 0.5px solid var(--c-border-s);
    border-radius: var(--r-md);
    background: var(--c-surface);
    font-size: 12.5px; font-weight: 500; color: var(--c-ink-2);
    cursor: pointer;
    font-family: var(--font);
    transition: all 0.15s;
}
.col-config-toggle:hover { border-color: var(--c-orange); color: var(--c-orange); background: var(--c-orange-l); }

.col-config-panel {
    display: none; margin-top: 10px;
    border: 0.5px solid var(--c-border);
    border-radius: var(--r-md); overflow: hidden;
}
.col-config-panel.open { display: block; }

.col-config-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px;
    background: #fafaf8;
    border-bottom: 0.5px solid var(--c-border);
    font-size: 12px; color: var(--c-ink-3);
}
.drag-hint {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; color: var(--c-ink-3);
}

.col-list { padding: 4px 0; }
.col-item {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 14px;
    border-bottom: 0.5px solid var(--c-border);
    cursor: grab; user-select: none;
    transition: background 0.1s;
}
.col-item:last-child { border-bottom: none; }
.col-item:hover { background: #fafaf8; }
.col-item.sortable-ghost { opacity: 0.4; background: var(--c-orange-l); }
.col-item.sortable-drag   { cursor: grabbing; }

.drag-handle { color: var(--c-ink-3); flex-shrink: 0; }
.drag-handle svg { display: block; }

.col-item-name { flex: 1; font-size: 12.5px; color: var(--c-ink); }
.col-order {
    font-size: 10.5px; font-family: var(--font-mono);
    color: var(--c-ink-3); min-width: 20px; text-align: right;
}

.badge-mandatory { font-size: 10px; padding: 2px 7px; border-radius: 100px; background: #fee2e2; color: #991b1b; font-weight: 500; }
.badge-optional  { font-size: 10px; padding: 2px 7px; border-radius: 100px; background: #f3f4f6; color: #6b7280; font-weight: 500; }

/* toggle switch */
.tog { position: relative; display: inline-block; width: 34px; height: 18px; flex-shrink: 0; }
.tog input { opacity:0; width:0; height:0; }
.tog-track {
    position: absolute; inset: 0;
    background: #d1d5db; border-radius: 18px; cursor: pointer;
    transition: background 0.2s;
}
.tog-track:before {
    content:''; position: absolute;
    width: 12px; height: 12px;
    left: 3px; top: 3px;
    background: #fff; border-radius: 50%;
    transition: transform 0.2s;
}
.tog input:checked + .tog-track { background: var(--c-orange); }
.tog input:checked + .tog-track:before { transform: translateX(16px); }
.tog input:disabled + .tog-track { opacity: 0.5; cursor: not-allowed; }

.col-config-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px;
    border-top: 0.5px solid var(--c-border);
    background: #fafaf8;
}
.col-config-footer span { font-size: 11.5px; color: var(--c-ink-3); }
.btn-sm {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px;
    font-size: 12px; font-weight: 500;
    border-radius: var(--r-sm);
    cursor: pointer; font-family: var(--font);
    transition: all 0.12s;
}
.btn-sm-primary { background: var(--c-orange); color: #fff; border: none; }
.btn-sm-primary:hover { background: #c94d13; }

/* Action bar */
.action-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px;
    border-top: 0.5px solid var(--c-border);
    background: #fafaf8;
    gap: 10px;
}
.btn-ghost {
    display: inline-flex; align-items: center; gap: 6px;
    height: 38px; padding: 0 16px;
    font-size: 13px; font-weight: 500;
    color: var(--c-ink-2);
    background: transparent;
    border: 0.5px solid var(--c-border-s);
    border-radius: var(--r-md);
    cursor: pointer; font-family: var(--font);
    transition: all 0.12s;
}
.btn-ghost:hover { background: var(--c-bg); border-color: var(--c-ink-3); }

.btn-action-group { display: flex; gap: 8px; }

.btn-preview {
    display: inline-flex; align-items: center; gap: 6px;
    height: 38px; padding: 0 18px;
    font-size: 13px; font-weight: 500;
    color: var(--c-orange);
    background: var(--c-orange-l);
    border: 0.5px solid #f0b088;
    border-radius: var(--r-md);
    cursor: pointer; font-family: var(--font);
    transition: all 0.12s;
}
.btn-preview:hover { background: #ffe8d8; }

.btn-download {
    display: inline-flex; align-items: center; gap: 6px;
    height: 38px; padding: 0 20px;
    font-size: 13px; font-weight: 500;
    color: #fff;
    background: var(--c-orange);
    border: 0.5px solid #c94d13;
    border-radius: var(--r-md);
    cursor: pointer; font-family: var(--font);
    transition: all 0.12s;
}
.btn-download:hover { background: #c94d13; }
.btn-download:disabled { opacity: 0.55; cursor: not-allowed; }

/* Right panel tabs */
.tab-bar {
    display: flex;
    border-bottom: 0.5px solid var(--c-border);
    background: #fafaf8;
}
.tab-btn {
    padding: 12px 18px;
    font-size: 13px; font-weight: 500;
    color: var(--c-ink-3);
    border: none; background: none;
    cursor: pointer; font-family: var(--font);
    border-bottom: 2px solid transparent;
    margin-bottom: -0.5px;
    transition: color 0.15s;
    display: flex; align-items: center; gap: 6px;
}
.tab-btn:hover { color: var(--c-ink); }
.tab-btn.active { color: var(--c-orange); border-bottom-color: var(--c-orange); }
.tab-pill {
    font-size: 10px; font-weight: 600;
    padding: 1px 6px; border-radius: 100px;
    background: var(--c-orange-l); color: var(--c-orange);
}

.tab-content { display: none; }
.tab-content.active { display: block; }

/* Preview panel */
.preview-empty {
    padding: 60px 20px;
    text-align: center;
    color: var(--c-ink-3);
}
.preview-empty svg { width: 48px; height: 48px; opacity: 0.25; margin: 0 auto 12px; display: block; }
.preview-empty p { font-size: 13px; }

.preview-meta {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
    padding: 14px 20px;
    border-bottom: 0.5px solid var(--c-border);
    background: #fafaf8;
}
.preview-meta-badges { display: flex; gap: 6px; flex-wrap: wrap; }
.meta-badge {
    font-size: 11px; font-weight: 500;
    padding: 3px 10px; border-radius: 100px;
    display: inline-flex; align-items: center; gap: 4px;
}
.meta-badge.orange { background: var(--c-orange-l); color: var(--c-orange); border: 0.5px solid #f0b088; }
.meta-badge.blue   { background: var(--c-blue-l);   color: var(--c-blue);   border: 0.5px solid #a8c8e8; }
.meta-badge.green  { background: var(--c-green-l);  color: var(--c-green);  border: 0.5px solid #8dcfad; }
.preview-count { font-size: 12px; color: var(--c-ink-3); }

.preview-table-wrap {
    overflow-x: auto;
    max-height: 460px;
    overflow-y: auto;
}
.preview-table {
    width: 100%; border-collapse: collapse; font-size: 12px;
    min-width: 600px;
}
.preview-table thead th {
    position: sticky; top: 0; z-index: 2;
    background: #f5f4f1;
    border-bottom: 0.5px solid var(--c-border-s);
    padding: 9px 12px;
    text-align: left; font-size: 11px;
    font-weight: 600;
    color: var(--c-ink-2);
    text-transform: uppercase; letter-spacing: 0.4px;
    white-space: nowrap;
}
.preview-table tbody td {
    padding: 8px 12px;
    border-bottom: 0.5px solid var(--c-border);
    color: var(--c-ink-2);
    vertical-align: top;
    font-size: 12.5px;
}
.preview-table tbody tr:hover td { background: #fafaf8; }
.preview-table tbody tr:last-child td { border-bottom: none; }

.preview-loading {
    padding: 40px 20px; text-align: center;
}
.spinner {
    display: inline-block;
    width: 28px; height: 28px;
    border: 2.5px solid var(--c-border-s);
    border-top-color: var(--c-orange);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 10px;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* History panel */
.history-empty {
    padding: 60px 20px; text-align: center; color: var(--c-ink-3);
}
.history-empty svg { width: 40px; height: 40px; opacity: 0.2; margin: 0 auto 10px; display: block; }
.history-empty p { font-size: 13px; }

.history-list { list-style: none; }
.history-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 20px;
    border-bottom: 0.5px solid var(--c-border);
    transition: background 0.1s;
}
.history-item:last-child { border-bottom: none; }
.history-item:hover { background: #fafaf8; }
.history-fmt-badge {
    width: 36px; height: 36px; border-radius: var(--r-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 10px; font-weight: 700; font-family: var(--font-mono);
    flex-shrink: 0;
}
.history-fmt-badge.pdf  { background: #fee2e2; color: #991b1b; }
.history-fmt-badge.csv  { background: #d1fae5; color: #065f46; }
.history-info { flex: 1; min-width: 0; }
.history-name {
    font-size: 13px; font-weight: 500; color: var(--c-ink);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.history-meta { font-size: 11.5px; color: var(--c-ink-3); margin-top: 2px; }
.history-status {
    font-size: 11px; font-weight: 500; padding: 3px 9px; border-radius: 100px;
}
.history-status.done    { background: var(--c-green-l); color: var(--c-green); }
.history-status.fail    { background: var(--c-red-l);   color: var(--c-red); }
.history-status.pending { background: var(--c-amber-l); color: var(--c-amber); }

.history-footer {
    padding: 12px 20px;
    border-top: 0.5px solid var(--c-border);
    background: #fafaf8;
    display: flex; justify-content: space-between; align-items: center;
}
.history-footer span { font-size: 12px; color: var(--c-ink-3); }
.btn-clear-history {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; color: var(--c-red);
    background: none; border: none; cursor: pointer; font-family: var(--font);
    padding: 4px 8px; border-radius: var(--r-sm);
    transition: background 0.12s;
}
.btn-clear-history:hover { background: var(--c-red-l); }

/* Guide panel */
.guide-body { padding: 20px; display: flex; flex-direction: column; gap: 16px; }
.guide-section {}
.guide-section-title {
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.6px;
    color: var(--c-ink-3); margin-bottom: 8px;
}
.guide-steps { display: flex; flex-direction: column; gap: 8px; }
.guide-step {
    display: flex; gap: 10px; align-items: flex-start;
}
.step-num {
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--c-orange); color: #fff;
    font-size: 10px; font-weight: 600;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
}
.guide-step p { font-size: 12.5px; color: var(--c-ink-2); line-height: 1.5; }
.guide-step p strong { color: var(--c-ink); }

.guide-chips { display: flex; flex-wrap: wrap; gap: 6px; }
.guide-chip {
    font-size: 11px; font-weight: 500;
    padding: 4px 10px; border-radius: 100px;
    border: 0.5px solid var(--c-border-s);
    color: var(--c-ink-2); background: #f5f4f1;
}

/* Responsive */
@media (max-width: 640px) {
    .stat-strip { grid-template-columns: 1fr 1fr; }
    .form-row-2 { grid-template-columns: 1fr; }
    .dl-page { padding: 84px 16px 60px; }
}

/* sweetalert2 customization */
.swal2-popup { font-family: var(--font) !important; border-radius: 14px !important; }
.swal2-confirm { border-radius: 8px !important; }
.swal2-cancel { border-radius: 8px !important; }
</style>

<div class="dl-page">

    <!-- Header -->
    <div class="dl-header">
        <h1>Download Laporan</h1>
        <p>Generate dan unduh laporan data PDRB dalam format PDF atau CSV.</p>
    </div>

    <!-- Stat strip -->
    <div class="stat-strip">
        <div class="stat-card">
            <div class="stat-icon orange">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Total Laporan</div>
                <div class="stat-value" id="stat-total">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Berhasil</div>
                <div class="stat-value" id="stat-ok">—</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Kategori</div>
                <div class="stat-value">{{ count($kategoris) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Tahun Terbaru</div>
                <div class="stat-value">{{ $tahuns->first() ?? '—' }}</div>
            </div>
        </div>
    </div>

    <!-- Main grid -->
    <div class="main-grid">

        <!-- Left: form -->
        <div>
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-head-left">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--c-ink-3);">
                            <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                        </svg>
                        <span class="panel-title">Konfigurasi Laporan</span>
                    </div>
                </div>

                <form action="{{ route('download.generate-pdf') }}" method="POST" id="downloadForm">
                    @csrf
                    <input type="hidden" name="format" id="formatInput" value="pdf">
                    <input type="hidden" name="column_order" id="columnOrderInput" value="">

                    <div class="panel-body">
                        <div class="form-stack">

                            <!-- Format -->
                            <div class="field">
                                <label class="field-label">Format Output <em>*</em></label>
                                <div class="format-toggle">
                                    <button type="button" class="format-btn active" data-format="pdf">
                                        <div class="format-icon pdf">PDF</div>
                                        <div>
                                            <div class="format-label">PDF</div>
                                            <div class="format-sub">Landscape A4</div>
                                        </div>
                                    </button>
                                    <button type="button" class="format-btn" data-format="csv">
                                        <div class="format-icon csv">CSV</div>
                                        <div>
                                            <div class="format-label">CSV</div>
                                            <div class="format-sub">Buka di Excel / Sheets</div>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <hr class="divider">

                            <!-- Kategori -->
                            <div class="field">
                                <label class="field-label" for="kategori_id">Kategori <em>*</em></label>
                                <select name="kategori_id" id="kategori_id" class="form-select {{ $errors->has('kategori_id') ? 'invalid' : '' }}" required>
                                    <option value="">— Pilih Kategori —</option>
                                    <option value="all" {{ old('kategori_id') == 'all' ? 'selected' : '' }}
                                        style="font-weight:600; color:#9a3412; background:#fff7ed;">
                                        ▤  Semua Kategori (Export Lengkap)
                                    </option>
                                    <optgroup label="── Kategori Individual">
                                        @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kode }} — {{ $kategori->nama }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('kategori_id')<div class="field-err">{{ $message }}</div>@enderror
                                <div class="all-notice" id="allNotice">
                                    <strong>Export Lengkap dipilih.</strong> Semua kategori digabung dalam satu file, setiap kategori di halaman terpisah (PDF) atau satu sheet besar (CSV). Proses lebih lama dari biasanya.
                                </div>
                            </div>

                            <!-- Tahun & Triwulan -->
                            <div class="form-row-2">
                                <div class="field">
                                    <label class="field-label" for="tahun">Tahun <em>*</em></label>
                                    <select name="tahun" id="tahun" class="form-select {{ $errors->has('tahun') ? 'invalid' : '' }}" required>
                                        <option value="">— Pilih —</option>
                                        @foreach($tahuns as $t)
                                        <option value="{{ $t }}" {{ old('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                    @error('tahun')<div class="field-err">{{ $message }}</div>@enderror
                                </div>
                                <div class="field">
                                    <label class="field-label" for="triwulan_id">Triwulan <em>*</em></label>
                                    <select name="triwulan_id" id="triwulan_id" class="form-select {{ $errors->has('triwulan_id') ? 'invalid' : '' }}" required>
                                        <option value="">— Pilih —</option>
                                        @foreach($triwulans as $triwulan)
                                        <option value="{{ $triwulan->id }}" {{ old('triwulan_id') == $triwulan->id ? 'selected' : '' }}>
                                            {{ $triwulan->triwulan }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('triwulan_id')<div class="field-err">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <hr class="divider">

                            <!-- Kolom config -->
                            <div class="field">
                                <label class="field-label">Konfigurasi Kolom</label>
                                <div>
                                    <button type="button" class="col-config-toggle" id="toggleColConfig">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="3"/>
                                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06-.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                        </svg>
                                        <span id="colConfigLabel">Atur & Urutkan Kolom</span>
                                        <svg id="colConfigChevron" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="transition:transform 0.2s;">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="col-config-panel" id="colConfigPanel">
                                    <div class="col-config-bar">
                                        <span>Centang untuk tampilkan · <strong>Seret untuk urutkan</strong></span>
                                        <span class="drag-hint">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                                            Drag to reorder
                                        </span>
                                    </div>
                                    <div class="col-list" id="sortableColList">
                                        @foreach($columnConfigs as $config)
                                        <div class="col-item" data-config-id="{{ $config->id }}">
                                            <div class="drag-handle">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <circle cx="9" cy="7" r="1" fill="currentColor"/><circle cx="15" cy="7" r="1" fill="currentColor"/>
                                                    <circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="15" cy="12" r="1" fill="currentColor"/>
                                                    <circle cx="9" cy="17" r="1" fill="currentColor"/><circle cx="15" cy="17" r="1" fill="currentColor"/>
                                                </svg>
                                            </div>
                                            <label class="tog">
                                                <input type="checkbox" class="col-checkbox"
                                                    data-config-id="{{ $config->id }}"
                                                    {{ $config->is_visible ? 'checked' : '' }}
                                                    {{ $config->is_mandatory ? 'disabled' : '' }}>
                                                <span class="tog-track"></span>
                                            </label>
                                            <span class="col-item-name">{{ $config->column_label }}</span>
                                            @if($config->is_mandatory)
                                                <span class="badge-mandatory">Wajib</span>
                                            @else
                                                <span class="badge-optional">Opsional</span>
                                            @endif
                                            <span class="col-order" data-order>—</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="col-config-footer">
                                        <span id="colConfigStat">— kolom aktif</span>
                                        <button type="button" class="btn-sm btn-sm-primary" id="saveColConfig">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                                <polyline points="17 21 17 13 7 13 7 21"/>
                                                <polyline points="7 3 7 8 15 8"/>
                                            </svg>
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="action-bar">
                        <button type="button" class="btn-ghost" id="btnReset">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="1 4 1 10 7 10"/>
                                <path d="M3.51 15a9 9 0 1 0 .49-4.5"/>
                            </svg>
                            Reset
                        </button>
                        <div class="btn-action-group">
                            <button type="button" class="btn-preview" id="btnPreview">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Preview Data
                            </button>
                            <button type="submit" class="btn-download" id="btnDownload">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right panel -->
        <div>
            <div class="panel">
                <div class="tab-bar">
                    <button class="tab-btn active" data-tab="preview">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        Preview
                    </button>
                    <button class="tab-btn" data-tab="history">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Riwayat
                        <span class="tab-pill" id="historyCount">0</span>
                    </button>
                    <button class="tab-btn" data-tab="guide">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Panduan
                    </button>
                </div>

                <!-- Preview tab -->
                <div class="tab-content active" id="tab-preview">
                    <div id="previewEmpty" class="preview-empty">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <p>Isi form & klik <strong>Preview Data</strong><br>untuk melihat sampel data sebelum download.</p>
                    </div>
                    <div id="previewLoading" class="preview-loading" style="display:none;">
                        <div class="spinner"></div>
                        <p style="font-size:13px; color:var(--c-ink-3);">Memuat preview...</p>
                    </div>
                    <div id="previewContent" style="display:none;">
                        <div class="preview-meta">
                            <div class="preview-meta-badges" id="previewBadges"></div>
                            <span class="preview-count" id="previewRowCount"></span>
                        </div>
                        <div class="preview-table-wrap">
                            <table class="preview-table" id="previewTable">
                                <thead id="previewThead"></thead>
                                <tbody id="previewTbody"></tbody>
                            </table>
                        </div>
                        <div style="padding:10px 16px; border-top:0.5px solid var(--c-border); background:#fafaf8;">
                            <p style="font-size:11.5px; color:var(--c-ink-3);">Menampilkan maksimal 50 baris pertama. Download untuk data lengkap.</p>
                        </div>
                    </div>
                </div>

                <!-- History tab -->
                <div class="tab-content" id="tab-history">
                    <div id="historyEmpty" class="history-empty">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <p>Belum ada riwayat download<br>di sesi ini.</p>
                    </div>
                    <ul class="history-list" id="historyList"></ul>
                    <div class="history-footer" id="historyFooter" style="display:none;">
                        <span id="historyFooterText"></span>
                        <button type="button" class="btn-clear-history" id="btnClearHistory">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                            Hapus semua
                        </button>
                    </div>
                </div>

                <!-- Guide tab -->
                <div class="tab-content" id="tab-guide">
                    <div class="guide-body">
                        <div class="guide-section">
                            <div class="guide-section-title">Langkah-langkah</div>
                            <div class="guide-steps">
                                <div class="guide-step"><div class="step-num">1</div><p>Pilih <strong>Format Output</strong> — PDF atau CSV</p></div>
                                <div class="guide-step"><div class="step-num">2</div><p>Pilih <strong>Kategori</strong>, <strong>Tahun</strong>, dan <strong>Triwulan</strong></p></div>
                                <div class="guide-step"><div class="step-num">3</div><p>Atur kolom: centang/hapus centang, lalu <strong>seret untuk mengubah urutan</strong></p></div>
                                <div class="guide-step"><div class="step-num">4</div><p>Klik <strong>Preview Data</strong> untuk melihat sampel sebelum download</p></div>
                                <div class="guide-step"><div class="step-num">5</div><p>Klik <strong>Download</strong> untuk mengunduh file</p></div>
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="guide-section">
                            <div class="guide-section-title">Format Output</div>
                            <div class="guide-chips">
                                <span class="guide-chip">PDF · Landscape A4</span>
                                <span class="guide-chip">CSV · Excel / Sheets</span>
                                <span class="guide-chip">Header BPS</span>
                                <span class="guide-chip">Kolom fleksibel</span>
                                <span class="guide-chip">Semua kategori</span>
                            </div>
                        </div>
                        <hr class="divider">
                        <div class="guide-section">
                            <div class="guide-section-title">Catatan</div>
                            <div class="guide-steps">
                                <div class="guide-step">
                                    <div style="width:20px;height:20px;border-radius:50%;background:var(--c-amber-l);color:var(--c-amber);font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">!</div>
                                    <p>Export semua kategori membutuhkan waktu lebih lama (±1–3 menit)</p>
                                </div>
                                <div class="guide-step">
                                    <div style="width:20px;height:20px;border-radius:50%;background:var(--c-amber-l);color:var(--c-amber);font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">!</div>
                                    <p>Kolom <strong>Wajib</strong> tidak dapat dinonaktifkan dan selalu muncul</p>
                                </div>
                                <div class="guide-step">
                                    <div style="width:20px;height:20px;border-radius:50%;background:var(--c-green-l);color:var(--c-green);font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">✓</div>
                                    <p>File CSV menggunakan BOM UTF-8 — karakter Indonesia terbaca dengan benar di Excel</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let history = JSON.parse(localStorage.getItem('dl_history') || '[]');
    const HISTORY_KEY = 'dl_history';

    @if(session('success'))
    Swal.fire({ icon:'success', title:'Berhasil', text: @json(session('success')), timer:2200, showConfirmButton:false });
    @endif
    @if(session('error'))
    Swal.fire({ icon:'error', title:'Gagal', text: @json(session('error')), confirmButtonColor:'#e05c1a', confirmButtonText:'OK' });
    @endif
    @if($errors->any())
    Swal.fire({ icon:'error', title:'Validasi Gagal', html:'<ul style="text-align:left;margin:0;padding-left:1.2rem;">@foreach($errors->all() as $error)<li style="font-size:13px;margin-bottom:4px;">{{ $error }}</li>@endforeach</ul>', confirmButtonColor:'#e05c1a' });
    @endif

    const FIELDS = ['kategori_id', 'tahun', 'triwulan_id'];
    FIELDS.forEach(f => {
        const v = sessionStorage.getItem('dl_' + f);
        if (v) { const el = document.getElementById(f); if (el) el.value = v; }
    });
    const savedFmt = sessionStorage.getItem('dl_format');
    if (savedFmt) setFormat(savedFmt);

    updateAllNotice();
    updateStats();

    document.querySelectorAll('.format-btn').forEach(btn => {
        btn.addEventListener('click', () => setFormat(btn.dataset.format));
    });

    function setFormat(fmt) {
        document.querySelectorAll('.format-btn').forEach(b => b.classList.toggle('active', b.dataset.format === fmt));
        document.getElementById('formatInput').value = fmt;
        sessionStorage.setItem('dl_format', fmt);
    }

    FIELDS.forEach(f => {
        document.getElementById(f)?.addEventListener('change', function () {
            sessionStorage.setItem('dl_' + f, this.value);
            if (f === 'kategori_id') updateAllNotice();
            resetPreview();
        });
    });

    function updateAllNotice() {
        const v = document.getElementById('kategori_id').value;
        document.getElementById('allNotice').classList.toggle('show', v === 'all');
    }

    const sortableList = document.getElementById('sortableColList');
    Sortable.create(sortableList, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: () => { updateColOrders(); syncColumnOrderInput(); }
    });

    function updateColOrders() {
        const items = sortableList.querySelectorAll('.col-item');
        let n = 1;
        items.forEach(item => {
            const orderEl = item.querySelector('[data-order]');
            const cb = item.querySelector('.col-checkbox');
            if (cb.checked || cb.disabled) {
                orderEl.textContent = n++;
            } else {
                orderEl.textContent = '—';
            }
        });
        updateColConfigStat();
    }

    function syncColumnOrderInput() {
        const items = sortableList.querySelectorAll('.col-item');
        const ids = Array.from(items).map(i => i.dataset.configId);
        document.getElementById('columnOrderInput').value = ids.join(',');
    }

    function updateColConfigStat() {
        const checked = sortableList.querySelectorAll('.col-checkbox:checked').length;
        document.getElementById('colConfigStat').textContent = checked + ' kolom aktif';
    }

    sortableList.querySelectorAll('.col-checkbox').forEach(cb => {
        cb.addEventListener('change', () => { updateColOrders(); syncColumnOrderInput(); });
    });

    updateColOrders();
    syncColumnOrderInput();

    document.getElementById('toggleColConfig').addEventListener('click', () => {
        const open = document.getElementById('colConfigPanel').classList.toggle('open');
        document.getElementById('colConfigChevron').style.transform = open ? 'rotate(180deg)' : '';
        document.getElementById('colConfigLabel').textContent = open ? 'Tutup Pengaturan' : 'Atur & Urutkan Kolom';
    });

    document.getElementById('saveColConfig').addEventListener('click', () => {
        const configs = [];
        sortableList.querySelectorAll('.col-item').forEach((item, idx) => {
            const cb = item.querySelector('.col-checkbox');
            configs.push({
                id: parseInt(item.dataset.configId),
                is_visible: cb.checked || cb.disabled,
                sort_order: idx + 1
            });
        });

        fetch('{{ route("download.update-column-config") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ configs })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon:'success', title:'Berhasil', text: data.message || 'Konfigurasi kolom disimpan.', timer:1800, showConfirmButton:false });
            } else {
                Swal.fire({ icon:'error', title:'Gagal', text: data.message, confirmButtonColor:'#e05c1a' });
            }
        })
        .catch(err => Swal.fire({ icon:'error', title:'Error', text: err.message, confirmButtonColor:'#e05c1a' }));
    });

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });

    document.getElementById('btnPreview').addEventListener('click', loadPreview);

    function resetPreview() {
        document.getElementById('previewEmpty').style.display = '';
        document.getElementById('previewContent').style.display = 'none';
        document.getElementById('previewLoading').style.display = 'none';
    }

    function loadPreview() {
        const kategoriId = document.getElementById('kategori_id').value;
        const tahun      = document.getElementById('tahun').value;
        const triwulanId = document.getElementById('triwulan_id').value;

        if (!kategoriId || !tahun || !triwulanId) {
            Swal.fire({ icon:'warning', title:'Data Tidak Lengkap', text:'Mohon isi Kategori, Tahun, dan Triwulan terlebih dahulu.', confirmButtonColor:'#e05c1a' });
            return;
        }

        document.querySelectorAll('.tab-btn').forEach(b => b.classList.toggle('active', b.dataset.tab === 'preview'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.toggle('active', c.id === 'tab-preview'));

        document.getElementById('previewEmpty').style.display = 'none';
        document.getElementById('previewContent').style.display = 'none';
        document.getElementById('previewLoading').style.display = '';

        const params = new URLSearchParams({ kategori_id: kategoriId, tahun, triwulan_id: triwulanId });
        fetch('{{ route("download.preview") }}?' + params.toString(), {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('previewLoading').style.display = 'none';

            if (!data.success) {
                Swal.fire({ icon:'error', title:'Gagal memuat preview', text: data.message, confirmButtonColor:'#e05c1a' });
                document.getElementById('previewEmpty').style.display = '';
                return;
            }

            const badges = document.getElementById('previewBadges');
            badges.innerHTML = `
                <span class="meta-badge orange">${data.kategori_name}</span>
                <span class="meta-badge blue">Tahun ${data.tahun}</span>
                <span class="meta-badge green">${data.triwulan_name}</span>
            `;
            document.getElementById('previewRowCount').textContent = `${data.total_rows} baris (preview: ${data.rows.length})`;

            const thead = document.getElementById('previewThead');
            thead.innerHTML = '<tr>' + data.columns.map(c => `<th>${c}</th>`).join('') + '</tr>';

            const tbody = document.getElementById('previewTbody');
            tbody.innerHTML = data.rows.map(row => {
                const cells = data.columns.map(col => `<td>${row[col] ?? '—'}</td>`).join('');
                return `<tr>${cells}</tr>`;
            }).join('');

            document.getElementById('previewContent').style.display = '';
        })
        .catch(err => {
            document.getElementById('previewLoading').style.display = 'none';
            document.getElementById('previewEmpty').style.display = '';
            Swal.fire({ icon:'error', title:'Error', text: err.message, confirmButtonColor:'#e05c1a' });
        });
    }

    function renderHistory() {
        const list   = document.getElementById('historyList');
        const empty  = document.getElementById('historyEmpty');
        const footer = document.getElementById('historyFooter');
        const count  = document.getElementById('historyCount');

        count.textContent = history.length;
        list.innerHTML = '';

        if (history.length === 0) {
            empty.style.display = '';
            footer.style.display = 'none';
            return;
        }

        empty.style.display = 'none';
        footer.style.display = '';
        document.getElementById('historyFooterText').textContent = history.length + ' download tercatat';

        history.slice().reverse().forEach(item => {
            const statusClass = item.status === 'success' ? 'done' : item.status === 'error' ? 'fail' : 'pending';
            const statusLabel = item.status === 'success' ? 'Berhasil' : item.status === 'error' ? 'Gagal' : 'Pending';
            const li = document.createElement('li');
            li.className = 'history-item';
            li.innerHTML = `
                <div class="history-fmt-badge ${item.format}">${item.format.toUpperCase()}</div>
                <div class="history-info">
                    <div class="history-name">${item.name}</div>
                    <div class="history-meta">${item.time}</div>
                </div>
                <span class="history-status ${statusClass}">${statusLabel}</span>
            `;
            list.appendChild(li);
        });
    }

    function addHistory(name, format, status) {
        const now = new Date();
        history.push({
            name, format, status,
            time: now.toLocaleDateString('id-ID') + ' · ' + now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' })
        });
        localStorage.setItem(HISTORY_KEY, JSON.stringify(history));
        renderHistory();
        updateStats();
    }

    document.getElementById('btnClearHistory').addEventListener('click', () => {
        Swal.fire({ title:'Hapus Riwayat?', text:'Semua riwayat download akan dihapus.', icon:'warning', showCancelButton:true, confirmButtonColor:'#c22b2b', cancelButtonColor:'#e5e7eb', confirmButtonText:'Ya, Hapus', cancelButtonText:'Batal' })
        .then(r => {
            if (r.isConfirmed) {
                history = [];
                localStorage.removeItem(HISTORY_KEY);
                renderHistory();
                updateStats();
            }
        });
    });

    function updateStats() {
        const total = history.length;
        const ok    = history.filter(h => h.status === 'success').length;
        document.getElementById('stat-total').textContent = total || '0';
        document.getElementById('stat-ok').textContent    = ok    || '0';
    }

    renderHistory();

    document.getElementById('btnReset').addEventListener('click', () => {
        Swal.fire({ title:'Reset Form?', text:'Semua pilihan akan dikosongkan.', icon:'question', showCancelButton:true, confirmButtonColor:'#e05c1a', cancelButtonColor:'#e5e7eb', confirmButtonText:'Ya, Reset', cancelButtonText:'Batal' })
        .then(r => {
            if (r.isConfirmed) {
                FIELDS.forEach(f => { document.getElementById(f).value = ''; sessionStorage.removeItem('dl_' + f); });
                setFormat('pdf');
                updateAllNotice();
                resetPreview();
            }
        });
    });

    document.getElementById('downloadForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const kategoriEl = document.getElementById('kategori_id');
        const tahunEl    = document.getElementById('tahun');
        const triwulanEl = document.getElementById('triwulan_id');
        const format     = document.getElementById('formatInput').value;

        if (!kategoriEl.value || !tahunEl.value || !triwulanEl.value) {
            Swal.fire({ icon:'warning', title:'Data Tidak Lengkap', text:'Mohon lengkapi semua field yang wajib diisi.', confirmButtonColor:'#e05c1a' });
            return;
        }

        const isAll        = kategoriEl.value === 'all';
        const kategoriText = isAll ? 'Semua Kategori' : kategoriEl.options[kategoriEl.selectedIndex].text;
        const triwulanText = triwulanEl.options[triwulanEl.selectedIndex].text;
        const formatLabel  = format === 'csv' ? 'CSV' : 'PDF';

        const confirmHtml = `
            <div style="text-align:left;font-size:13px;line-height:1.8;">
                ${isAll ? `<div style="background:#fef7ec;border:0.5px solid #f0c07a;border-radius:8px;padding:10px 14px;margin-bottom:14px;color:#b05f0a;font-size:12.5px;">
                    <strong>Export Lengkap dipilih.</strong> Semua kategori akan digabung dalam satu file. Proses mungkin lebih lama.
                </div>` : ''}
                <table style="width:100%;border-collapse:collapse;">
                    <tr><td style="color:#8a8880;padding:4px 0;width:90px;">Format</td><td style="font-weight:500;">${formatLabel}</td></tr>
                    <tr><td style="color:#8a8880;padding:4px 0;">Kategori</td><td style="font-weight:500;">${kategoriText}</td></tr>
                    <tr><td style="color:#8a8880;padding:4px 0;">Tahun</td><td style="font-weight:500;">${tahunEl.value}</td></tr>
                    <tr><td style="color:#8a8880;padding:4px 0;">Triwulan</td><td style="font-weight:500;">${triwulanText}</td></tr>
                </table>
            </div>`;

        Swal.fire({ title:'Konfirmasi Download', html:confirmHtml, icon:'question', showCancelButton:true, confirmButtonColor:'#e05c1a', cancelButtonColor:'#e5e7eb', confirmButtonText:'Ya, Download', cancelButtonText:'Batal' })
        .then(result => {
            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Generating ' + formatLabel + '...',
                html: isAll
                    ? 'Sedang memproses semua kategori.<br><small style="color:#8a8880;">Proses ini bisa memakan waktu beberapa menit.</small>'
                    : 'Sedang membuat file ' + formatLabel + '.<br><small style="color:#8a8880;">Mohon tunggu sebentar.</small>',
                allowOutsideClick: false, allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const btn = document.getElementById('btnDownload');
            btn.disabled = true;

            const histName = `${kategoriText} · ${triwulanText} ${tahunEl.value}`;

            this.submit();

            const timeout = isAll ? 90000 : 20000;
            setTimeout(() => {
                Swal.close();
                btn.disabled = false;
                addHistory(histName, format, 'success');
            }, Math.min(timeout, 3000));

            setTimeout(() => {
                Swal.close();
                btn.disabled = false;
            }, timeout);
        });
    });

});
</script>
<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
</style>
@endsection