@extends('layouts/app')

@section('content')

<style>
    * { box-sizing: border-box; }

    body { background: #f7f6f3; }

    .page {
        padding: 24px 24px 48px;
        padding-top: 80px;
        font-family: 'Figtree', sans-serif;
    }

    /* Card */
    .card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 0.5px solid #e5e7eb;
        background: #fafafa;
        gap: 8px;
    }
    .card-head-left { display: flex; align-items: center; gap: 8px; }
    .card-head-icon { color: #9ca3af; }
    .card-head-title { font-size: 14px; font-weight: 500; color: #111827; }

    .card-head-badge-period {
        font-size: 11px;
        padding: 2px 10px;
        background: #eff6ff;
        border: 0.5px solid #bfdbfe;
        border-radius: 100px;
        color: #1e40af;
        font-weight: 500;
    }
    .card-head-badge-final {
        font-size: 11px;
        padding: 2px 10px;
        background: #f0fdf4;
        border: 0.5px solid #86efac;
        border-radius: 100px;
        color: #166534;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .card-head-badge-pending {
        font-size: 11px;
        padding: 2px 10px;
        background: #fefce8;
        border: 0.5px solid #fde68a;
        border-radius: 100px;
        color: #854d0e;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .card-body { padding: 20px; }

    /* Filter */
    .filter-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-label { font-size: 12px; font-weight: 500; color: #374151; }
    .filter-select {
        height: 36px; padding: 0 10px; font-size: 13px;
        font-family: 'Figtree', sans-serif; color: #374151;
        background: #fff; border: 0.5px solid #d1d5db;
        border-radius: 8px; outline: none; min-width: 160px;
        cursor: pointer; transition: border-color 0.15s;
    }
    .filter-select:focus { border-color: #6b7280; box-shadow: 0 0 0 3px rgba(107,114,128,0.08); }

    /* Metric grid */
    .metric-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 640px) { .metric-grid { grid-template-columns: 1fr; } }

    .metric-item {
        padding: 16px 18px; border: 0.5px solid #e5e7eb; border-radius: 10px;
        background: #fafafa; display: flex; flex-direction: column; gap: 6px;
    }
    .metric-accent-adhb { border-left: 3px solid #f97316; border-radius: 0 10px 10px 0; }
    .metric-accent-adhk { border-left: 3px solid #8b5cf6; border-radius: 0 10px 10px 0; }
    .metric-label { font-size: 12px; font-weight: 500; color: #6b7280; }
    .metric-value { font-size: 20px; font-weight: 600; color: #111827; font-family: monospace; }

    /* Action bar */
    .action-bar { display: flex; align-items: center; gap: 10px; padding: 16px 20px; }

    .btn-finalisasi {
        display: inline-flex; align-items: center; gap: 6px; height: 38px;
        padding: 0 20px; font-size: 13px; font-weight: 500; color: #fff;
        background: #f97316; border: 0.5px solid #ea580c; border-radius: 8px;
        cursor: pointer; font-family: 'Figtree', sans-serif; transition: background 0.12s;
    }
    .btn-finalisasi:hover { background: #ea580c; }

    .btn-revisi {
        display: inline-flex; align-items: center; gap: 6px; height: 38px;
        padding: 0 20px; font-size: 13px; font-weight: 500; color: #fff;
        background: #f97316; border: 0.5px solid #ea580c; border-radius: 8px;
        cursor: pointer; font-family: 'Figtree', sans-serif; transition: background 0.12s; opacity: 0.85;
    }
    .btn-revisi:hover { background: #ea580c; opacity: 1; }

    /* Table base */
    .table-wrap { overflow-x: auto; }

    .fin-table {
        width: 100%; border-collapse: collapse; font-size: 13px; min-width: 700px;
    }
    .fin-table thead th {
        font-size: 11px; font-weight: 500; text-transform: uppercase;
        letter-spacing: 0.5px; color: #6b7280; padding: 10px 14px;
        text-align: center; border-bottom: 0.5px solid #e5e7eb;
        background: #f9fafb; white-space: nowrap;
    }
    .fin-table thead th:first-child { width: 40px; }
    .fin-table thead th.th-left { text-align: left; }
    .fin-table tbody tr:hover td { background: #f9fafb; }
    .fin-table tbody tr:not(:last-child) td { border-bottom: 0.5px solid #f0f0f0; }
    .fin-table tbody td {
        padding: 10px 14px; text-align: center;
        vertical-align: middle; color: #374151; font-size: 13px;
    }
    .fin-table tbody td.td-no { color: #9ca3af; width: 40px; }
    .fin-table tbody td.td-left { text-align: left; font-weight: 500; color: #111827; }
    .fin-table tbody td.td-num { font-family: monospace; font-size: 12.5px; text-align: right; }

    .growth-up   { color: #166534; font-weight: 700; font-size: 12.5px; white-space: nowrap; }
    .growth-down { color: #991b1b; font-weight: 700; font-size: 12.5px; white-space: nowrap; }

    .fin-table tfoot td {
        padding: 10px 14px; text-align: center; font-weight: 600; font-size: 13px;
        color: #111827; background: #f3f4f6; border-top: 1.5px solid #e5e7eb; white-space: nowrap;
    }
    .fin-table tfoot td.td-left { text-align: left; }
    .fin-table tfoot td.td-num  { font-family: monospace; text-align: right; }

    .total-badge {
        display: inline-block; font-size: 11px; font-weight: 600;
        padding: 2px 10px; border-radius: 100px;
    }
    .total-badge-pct  { background: #fef9c3; color: #854d0e; border: 0.5px solid #fde68a; }
    .total-badge-up   { background: #f0fdf4; color: #166534; border: 0.5px solid #86efac; }
    .total-badge-down { background: #fef2f2; color: #991b1b; border: 0.5px solid #fca5a5; }

    /* Empty state */
    .empty-state { padding: 56px 20px; text-align: center; color: #9ca3af; }
    .empty-state svg { margin-bottom: 14px; color: #d1d5db; }
    .empty-state h6 { font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 4px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    /*─ Riwayat Timeline─ */
    .riwayat-list { padding: 20px; display: flex; flex-direction: column; gap: 0; }

    .riwayat-item {
        display: flex;
        gap: 16px;
        padding-bottom: 24px;
        position: relative;
    }
    /* vertical line connecting items */
    .riwayat-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 32px;
        bottom: 0;
        width: 1.5px;
        background: #e5e7eb;
    }

    .riwayat-dot-wrap {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        z-index: 1;
    }
    .riwayat-dot-v1  { background: #f0fdf4; border: 1.5px solid #86efac; color: #166534; }
    .riwayat-dot-rev { background: #fef9c3; border: 1.5px solid #fde68a; color: #854d0e; }

    .riwayat-body { flex: 1; min-width: 0; }

    .riwayat-head {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 6px;
    }
    .riwayat-title {
        font-size: 13px;
        font-weight: 600;
        color: #111827;
    }
    .riwayat-period {
        font-size: 11px;
        padding: 1px 8px;
        background: #eff6ff;
        border: 0.5px solid #bfdbfe;
        border-radius: 100px;
        color: #1e40af;
        font-weight: 500;
    }
    .riwayat-badge-v1  { font-size:11px; padding:1px 8px; background:#f0fdf4; border:0.5px solid #86efac; border-radius:100px; color:#166534; font-weight:500; }
    .riwayat-badge-rev { font-size:11px; padding:1px 8px; background:#fef9c3; border:0.5px solid #fde68a; border-radius:100px; color:#854d0e; font-weight:500; }

    .riwayat-meta {
        font-size: 12px;
        color: #6b7280;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 8px;
    }
    .riwayat-meta span { display: inline-flex; align-items: center; gap: 4px; }

    .riwayat-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .riwayat-stat {
        padding: 5px 10px;
        border: 0.5px solid #e5e7eb;
        border-radius: 8px;
        background: #fafafa;
        font-size: 11px;
        color: #374151;
        display: flex;
        flex-direction: column;
        gap: 1px;
    }
    .riwayat-stat-label { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.4px; }
    .riwayat-stat-value { font-family: monospace; font-weight: 600; color: #111827; }

    .btn-detail-versi {
        display: inline-flex; align-items: center; gap: 5px;
        height: 28px; padding: 0 12px; font-size: 11px; font-weight: 500;
        color: #374151; background: #fff; border: 0.5px solid #d1d5db;
        border-radius: 6px; cursor: pointer; font-family: 'Figtree', sans-serif;
        transition: border-color 0.12s, background 0.12s;
    }
    .btn-detail-versi:hover { background: #f3f4f6; border-color: #9ca3af; }

    /*─ Modal Detail Versi─ */
    .modal-backdrop {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.35);
        z-index: 999;
        align-items: center;
        justify-content: center;
    }
    .modal-backdrop.open { display: flex; }

    .modal-box {
        background: #fff;
        border-radius: 14px;
        width: 92%;
        max-width: 760px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    }

    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 0.5px solid #e5e7eb; background: #fafafa;
    }
    .modal-header-left { display: flex; flex-direction: column; gap: 3px; }
    .modal-header-title { font-size: 14px; font-weight: 600; color: #111827; }
    .modal-header-sub   { font-size: 12px; color: #6b7280; }

    .modal-close {
        width: 30px; height: 30px; border-radius: 8px; border: 0.5px solid #e5e7eb;
        background: #fff; cursor: pointer; display: flex; align-items: center;
        justify-content: center; color: #6b7280; font-size: 16px; font-family: monospace;
        transition: background 0.12s;
    }
    .modal-close:hover { background: #f3f4f6; }

    .modal-body { overflow-y: auto; padding: 0; flex: 1; }

    .modal-loading {
        padding: 48px 20px; text-align: center; color: #9ca3af; font-size: 13px;
    }

    /* modal inner table */
    .modal-table {
        width: 100%; border-collapse: collapse; font-size: 12px;
    }
    .modal-table thead th {
        font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
        color: #6b7280; padding: 9px 14px; text-align: center;
        border-bottom: 0.5px solid #e5e7eb; background: #f9fafb; white-space: nowrap;
    }
    .modal-table thead th:nth-child(2) { text-align: left; }
    .modal-table tbody tr:not(:last-child) td { border-bottom: 0.5px solid #f0f0f0; }
    .modal-table tbody tr:hover td { background: #f9fafb; }
    .modal-table tbody td {
        padding: 8px 14px; text-align: right; color: #374151; font-size: 12px; vertical-align: middle;
    }
    .modal-table tbody td:first-child { text-align: center; color: #9ca3af; width: 36px; }
    .modal-table tbody td:nth-child(2) { text-align: left; font-weight: 500; color: #111827; }
    .modal-table tbody td.td-mono { font-family: monospace; }
    .modal-table tfoot td {
        padding: 9px 14px; font-weight: 600; font-size: 12px; text-align: right;
        background: #f3f4f6; border-top: 1.5px solid #e5e7eb; color: #111827;
    }
    .modal-table tfoot td:first-child { text-align: center; }
    .modal-table tfoot td:nth-child(2) { text-align: left; }
    .modal-table tfoot td.td-mono { font-family: monospace; }

    .modal-footer {
        padding: 12px 20px; border-top: 0.5px solid #e5e7eb; background: #fafafa;
        display: flex; justify-content: flex-end;
    }
    .btn-modal-close {
        height: 34px; padding: 0 18px; font-size: 13px; font-weight: 500;
        color: #374151; background: #fff; border: 0.5px solid #d1d5db;
        border-radius: 8px; cursor: pointer; font-family: 'Figtree', sans-serif;
        transition: background 0.12s;
    }
    .btn-modal-close:hover { background: #f3f4f6; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <h3 class="fw-bold mb-2">Finalisasi PDRB</h3>
    <div class="mb-3">
        <h5 style="font-size:14px; color:#6b7280; font-weight:400; margin:0;">
            Finalisasi dan penguncian data PDRB per periode triwulan.
        </h5>
    </div>

    <!-- Filter -->
    <div class="card">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                <span class="card-head-title">Filter Periode</span>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finalisasi.index') }}" id="formFilter">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Tahun</label>
                        <select name="tahun" class="filter-select" onchange="document.getElementById('formFilter').submit()">
                            <option value="">— Pilih Tahun —</option>
                            @foreach($tahunList as $thn)
                                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Triwulan</label>
                        <select name="triwulan_id" class="filter-select" onchange="document.getElementById('formFilter').submit()">
                            <option value="">— Pilih Triwulan —</option>
                            @foreach($triwulans as $tri)
                                <option value="{{ $tri->id }}" {{ ($triwulan_id ?? '') == $tri->id ? 'selected' : '' }}>{{ $tri->triwulan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($tahun && $triwulan_id)

    <!-- Ringkasan PDRB -->
    <div class="card">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
                <span class="card-head-title">Ringkasan PDRB</span>
            </div>
            <span class="card-head-badge-period">
                {{ $tahun }} — {{ $triwulans->firstWhere('id', $triwulan_id)?->triwulan }}
            </span>
        </div>
        <div class="card-body">
            <div class="metric-grid">
                <div class="metric-item metric-accent-adhb">
                    <span class="metric-label">PDRB ADHB (Miliar Rupiah)</span>
                    <span class="metric-value">Rp {{ number_format($total_adhb ?? 0, 2) }} Miliar</span>
                </div>
                <div class="metric-item metric-accent-adhk">
                    <span class="metric-label">PDRB ADHK (Miliar Rupiah)</span>
                    <span class="metric-value">Rp {{ number_format($total_adhk ?? 0, 2) }} Miliar</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data PDRB -->
    <div class="card">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                <span class="card-head-title">Data PDRB per Sektor</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                @if($isFinal)
                    <span class="card-head-badge-final">
                        <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        Sudah Final
                    </span>
                @else
                    <span class="card-head-badge-pending">
                        <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                        Belum Final
                    </span>
                @endif
                <span class="card-head-badge-period">
                    {{ $tahun }} — {{ $triwulans->firstWhere('id', $triwulan_id)?->triwulan }}
                </span>
            </div>
        </div>

        @if($data->count() > 0)
        <div class="table-wrap">
            <table class="fin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="th-left">Sektor</th>
                        <th>ADHB <span style="font-weight:400;text-transform:none;font-size:10px;color:#9ca3af;">(Miliar Rp)</span></th>
                        <th>ADHK <span style="font-weight:400;text-transform:none;font-size:10px;color:#9ca3af;">(Miliar Rp)</span></th>
                        <th>Kontribusi</th>
                        <th>QoQ</th>
                        <th>YoY</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $i => $row)
                    <tr>
                        <td class="td-no">{{ $i + 1 }}</td>
                        <td class="td-left">{{ $row->sektor->nama ?? '-' }}</td>
                        <td class="td-num">{{ number_format($row->adhb ?? 0, 2) }}</td>
                        <td class="td-num">{{ number_format($row->adhk ?? 0, 2) }}</td>
                        <td>{{ number_format($row->kontribusi ?? 0, 2) }}%</td>
                        <td>
                            @if($row->qoq > 0) <span class="growth-up">▲ +{{ number_format($row->qoq, 2) }}%</span>
                            @elseif($row->qoq < 0) <span class="growth-down">▼ {{ number_format($row->qoq, 2) }}%</span>
                            @else <span style="color:#9ca3af;">0,00%</span> @endif
                        </td>
                        <td>
                            @if($row->pertumbuhan > 0) <span class="growth-up">▲ +{{ number_format($row->pertumbuhan, 2) }}%</span>
                            @elseif($row->pertumbuhan < 0) <span class="growth-down">▼ {{ number_format($row->pertumbuhan, 2) }}%</span>
                            @else <span style="color:#9ca3af;">0,00%</span> @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="td-left">Total PDRB</td>
                        <td class="td-num">{{ number_format($total_adhb ?? 0, 2) }}</td>
                        <td class="td-num">{{ number_format($total_adhk ?? 0, 2) }}</td>
                        <td><span class="total-badge total-badge-pct">100%</span></td>
                        <td>
                            @php $totalQoq = $totalQoq ?? null; @endphp
                            @if($totalQoq !== null)
                                <span class="total-badge {{ $totalQoq >= 0 ? 'total-badge-up' : 'total-badge-down' }}">
                                    {{ $totalQoq >= 0 ? '▲ +' : '▼ ' }}{{ number_format($totalQoq, 2) }}%
                                </span>
                            @else <span style="color:#9ca3af;">—</span> @endif
                        </td>
                        <td>
                            @php $totalYoy = $totalYoy ?? null; @endphp
                            @if($totalYoy !== null)
                                <span class="total-badge {{ $totalYoy >= 0 ? 'total-badge-up' : 'total-badge-down' }}">
                                    {{ $totalYoy >= 0 ? '▲ +' : '▼ ' }}{{ number_format($totalYoy, 2) }}%
                                </span>
                            @else <span style="color:#9ca3af;">—</span> @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="empty-state">
            <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                <ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
            </svg>
            <h6>Belum Ada Data</h6>
            <p>Belum ada data PDRB yang tersedia untuk periode ini.</p>
        </div>
        @endif
    </div>

    <!-- Tombol Finalisasi -->
    @if($data->count() > 0)
    <div class="card" style="margin-bottom:0;">
        <div class="action-bar" style="justify-content: flex-end;">
            <form id="form-finalisasi" action="{{ route('finalisasi.proses') }}" method="POST">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <input type="hidden" name="triwulan_id" value="{{ $triwulan_id }}">
                @if($isFinal)
                    <button type="button" id="btnFinalisasi" class="btn-revisi">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/>
                        </svg>
                        Finalisasi Ulang (Versi {{ $versiTerakhir + 1 }})
                    </button>
                @else
                    <button type="button" id="btnFinalisasi" class="btn-finalisasi">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/>
                        </svg>
                        Finalisasi PDRB
                    </button>
                @endif
            </form>
        </div>
    </div>
    @endif

    @else

    <!-- Empty state filter -->
    <div class="card">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                <span class="card-head-title">Data PDRB per Sektor</span>
            </div>
        </div>
        <div class="empty-state">
            <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <h6>Filter Belum Dipilih</h6>
            <p>Silakan pilih <strong>Tahun</strong> dan <strong>Triwulan</strong> untuk melihat data finalisasi PDRB.</p>
        </div>
    </div>

    @endif

    <!-- RIWAYAT FINALISASI -->
    <div class="card" style="margin-top:24px; margin-bottom:0;">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span class="card-head-title">Riwayat Finalisasi</span>
            </div>
            @if($riwayat->count() > 0)
            <span style="font-size:11px; color:#9ca3af;">{{ $riwayat->count() }} entri</span>
            @endif
        </div>

        @if($riwayat->count() > 0)
        <div class="riwayat-list">
            @foreach($riwayat as $rw)
            @php
                $isFirst = $rw->versi === 1;
                $triwulanLabel = $triwulans->firstWhere('id', $rw->triwulan_id)?->triwulan ?? 'Triwulan '.$rw->triwulan_id;
                $finalizerName = $rw->finalizer?->name ?? 'Sistem';
            @endphp
            <div class="riwayat-item">
                <!-- Dot -->
                <div class="riwayat-dot-wrap {{ $isFirst ? 'riwayat-dot-v1' : 'riwayat-dot-rev' }}">
                    v{{ $rw->versi }}
                </div>

                <!-- Content -->
                <div class="riwayat-body">
                    <div class="riwayat-head">
                        <span class="riwayat-title">
                            {{ $isFirst ? 'Finalisasi Pertama' : 'Revisi Finalisasi' }}
                        </span>
                        <span class="riwayat-period">{{ $rw->tahun }} — {{ $triwulanLabel }}</span>
                        <span class="{{ $isFirst ? 'riwayat-badge-v1' : 'riwayat-badge-rev' }}">
                            Versi {{ $rw->versi }}
                        </span>
                    </div>

                    <div class="riwayat-meta">
                        <span>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/>
                            </svg>
                            {{ $finalizerName }}
                        </span>
                        <span>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($rw->created_at)->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                        <span>
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            {{ $rw->jumlah_sektor }} sektor dikunci
                        </span>
                    </div>

                    <div class="riwayat-stats">
                        <div class="riwayat-stat">
                            <span class="riwayat-stat-label">PDRB ADHB</span>
                            <span class="riwayat-stat-value">Rp {{ number_format($rw->total_adhb, 2) }} M</span>
                        </div>
                        <div class="riwayat-stat">
                            <span class="riwayat-stat-label">PDRB ADHK</span>
                            <span class="riwayat-stat-value">Rp {{ number_format($rw->total_adhk, 2) }} M</span>
                        </div>
                        <button
                            class="btn-detail-versi"
                            onclick="bukaDetailVersi({{ $rw->tahun }}, {{ $rw->triwulan_id }}, {{ $rw->versi }}, '{{ $rw->tahun }} — {{ $triwulanLabel }} Versi {{ $rw->versi }}')"
                        >
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            Lihat Detail Snapshot
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <h6>Belum Ada Riwayat</h6>
            <p>Belum ada data PDRB yang pernah difinalisasi.</p>
        </div>
        @endif
    </div>

</div>

<!-- Modal Detail Snapshot Versi -->
<div class="modal-backdrop" id="modalDetailVersi">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-left">
                <span class="modal-header-title">Snapshot Data Finalisasi</span>
                <span class="modal-header-sub" id="modalSubTitle">—</span>
            </div>
            <button class="modal-close" onclick="tutupModal()">✕</button>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="modal-loading">Memuat data…</div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-close" onclick="tutupModal()">Tutup</button>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    @if(session('success'))
    Swal.fire({ icon:'success', title:'Berhasil', text: @json(session('success')), timer:2200, showConfirmButton:false });
    @endif

    @if(session('error'))
    Swal.fire({ icon:'error', title:'Gagal', text: @json(session('error')), confirmButtonColor:'#2563eb', confirmButtonText:'OK' });
    @endif

    @if(isset($data) && $data->count() > 0)
    document.getElementById('btnFinalisasi')?.addEventListener('click', function () {
        let isFinal = {{ isset($isFinal) && $isFinal ? 'true' : 'false' }};
        Swal.fire({
            icon: 'warning',
            title: isFinal ? 'Finalisasi Ulang?' : 'Finalisasi Data?',
            html: isFinal
                ? 'Data PDRB periode <strong>{{ $tahun }} — {{ $triwulans->firstWhere('id', $triwulan_id)?->triwulan ?? '' }}</strong> akan dibuat versi baru.<br>Tindakan ini tidak dapat dibatalkan.'
                : 'Data PDRB periode <strong>{{ $tahun }} — {{ $triwulans->firstWhere('id', $triwulan_id)?->triwulan ?? '' }}</strong> akan dikunci sebagai versi pertama.<br>Tindakan ini tidak dapat dibatalkan.',
            showCancelButton: true,
            confirmButtonColor: isFinal ? '#ca8a04' : '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: isFinal ? 'Ya, Revisi!' : 'Ya, Finalisasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-finalisasi').submit();
        });
    });
    @endif

});

// Modal Detail Versi
function bukaDetailVersi(tahun, triwulanId, versi, label) {
    document.getElementById('modalSubTitle').textContent = label;
    document.getElementById('modalBody').innerHTML = '<div class="modal-loading">Memuat data…</div>';
    document.getElementById('modalDetailVersi').classList.add('open');

    fetch(`{{ route('finalisasi.detail-versi') }}?tahun=${tahun}&triwulan_id=${triwulanId}&versi=${versi}`)
        .then(r => r.json())
        .then(rows => {
            if (!rows || rows.length === 0) {
                document.getElementById('modalBody').innerHTML =
                    '<div class="modal-loading">Tidak ada data untuk versi ini.</div>';
                return;
            }

            let totalAdhb = rows.reduce((s, r) => s + (r.adhb || 0), 0);
            let totalAdhk = rows.reduce((s, r) => s + (r.adhk || 0), 0);

            let html = `<table class="modal-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="text-align:left;">Sektor</th>
                        <th>ADHB (M Rp)</th>
                        <th>ADHK (M Rp)</th>
                        <th>Kontribusi</th>
                        <th>Pertumbuhan</th>
                    </tr>
                </thead>
                <tbody>`;

            rows.forEach((r, i) => {
                const pertLabel = r.pertumbuhan !== null
                    ? (r.pertumbuhan >= 0
                        ? `<span style="color:#166534;font-weight:700;">▲ +${parseFloat(r.pertumbuhan).toFixed(2)}%</span>`
                        : `<span style="color:#991b1b;font-weight:700;">▼ ${parseFloat(r.pertumbuhan).toFixed(2)}%</span>`)
                    : `<span style="color:#9ca3af;">—</span>`;

                html += `<tr>
                    <td>${i + 1}</td>
                    <td>${r.sektor}</td>
                    <td class="td-mono">${parseFloat(r.adhb).toLocaleString('id-ID', {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                    <td class="td-mono">${parseFloat(r.adhk).toLocaleString('id-ID', {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                    <td>${r.kontribusi !== null ? parseFloat(r.kontribusi).toFixed(2) + '%' : '—'}</td>
                    <td>${pertLabel}</td>
                </tr>`;
            });

            html += `</tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="td-mono">${totalAdhb.toLocaleString('id-ID', {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                        <td class="td-mono">${totalAdhk.toLocaleString('id-ID', {minimumFractionDigits:2, maximumFractionDigits:2})}</td>
                        <td>100%</td>
                        <td>—</td>
                    </tr>
                </tfoot>
            </table>`;

            document.getElementById('modalBody').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('modalBody').innerHTML =
                '<div class="modal-loading" style="color:#991b1b;">Gagal memuat data. Silakan coba lagi.</div>';
        });
}

function tutupModal() {
    document.getElementById('modalDetailVersi').classList.remove('open');
}

// Tutup modal jika klik backdrop
document.getElementById('modalDetailVersi').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});
</script>
@endsection