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
    .card-head-badge {
        font-size: 11px;
        padding: 2px 8px;
        background: #f3f4f6;
        border: 0.5px solid #e5e7eb;
        border-radius: 100px;
        color: #6b7280;
    }
    .card-head-badge-period {
        font-size: 11px;
        padding: 2px 10px;
        background: #fff7ed;
        border: 0.5px solid #fed7aa;
        border-radius: 100px;
        color: #c2410c;
        font-weight: 500;
    }

    .card-body { padding: 20px; }

    /* Info satuan */
    .info-satuan {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        padding: 10px 14px;
        background: #eff6ff;
        border: 0.5px solid #bfdbfe;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: 12px;
        color: #1d4ed8;
        line-height: 1.5;
    }
    .info-satuan svg { flex-shrink: 0; margin-top: 1px; }

    /* Filter */
    .filter-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .filter-label {
        font-size: 12px;
        font-weight: 500;
        color: #374151;
    }
    .filter-select {
        height: 36px;
        padding: 0 10px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #374151;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        min-width: 160px;
        cursor: pointer;
        transition: border-color 0.15s;
    }
    .filter-select:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.08); }

    /* Status grid */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    @media (max-width: 640px) { .status-grid { grid-template-columns: 1fr 1fr; } }

    .status-item {
        padding: 14px 16px;
        border: 0.5px solid #e5e7eb;
        border-radius: 10px;
        background: #fafafa;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .status-label { font-size: 12px; font-weight: 500; color: #374151; }
    .status-sublabel { font-size: 11px; color: #9ca3af; font-weight: 400; }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 500;
        padding: 3px 10px;
        border-radius: 100px;
        width: fit-content;
    }
    .badge-ok       { background: #f0fdf4; color: #166534; border: 0.5px solid #86efac; }
    .badge-fail     { background: #fef2f2; color: #991b1b; border: 0.5px solid #fca5a5; }
    .badge-optional { background: #f3f4f6; color: #6b7280; border: 0.5px solid #e5e7eb; }

    /* Action bar */
    .action-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 20px;
    }

    .btn-hitung {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 38px;
        padding: 0 20px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        background: #f97316;
        border: 0.5px solid #ea580c;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        transition: background 0.12s;
    }
    .btn-hitung:hover { background: #ea580c; }
    .btn-hitung svg { color: rgba(255,255,255,0.8); }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 38px;
        padding: 0 20px;
        font-size: 13px;
        font-weight: 500;
        color: #991b1b;
        background: #fef2f2;
        border: 0.5px solid #fca5a5;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        transition: background 0.12s;
    }
    .btn-reset:hover { background: #fee2e2; }

    /* Table */
    .table-wrap { overflow-x: auto; }

    .pdrb-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 800px;
    }

    .pdrb-table thead tr.th-unit td {
        font-size: 10px;
        font-weight: 400;
        color: #9ca3af;
        text-align: center;
        padding: 2px 14px 6px;
        background: #f9fafb;
        font-style: italic;
        border-bottom: 0.5px solid #e5e7eb;
    }
    .pdrb-table thead tr.th-unit td:first-child,
    .pdrb-table thead tr.th-unit td:nth-child(2) { text-align: left; }

    .pdrb-table thead th {
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        padding: 10px 14px 4px;
        text-align: center;
        background: #f9fafb;
        white-space: nowrap;
    }
    .pdrb-table thead th:first-child { width: 40px; }
    .pdrb-table thead th.th-left { text-align: left; }

    .pdrb-table tbody tr:hover td { background: #fffbf7; }
    .pdrb-table tbody tr:not(:last-child) td { border-bottom: 0.5px solid #f0f0f0; }

    .pdrb-table tbody td {
        padding: 10px 14px;
        text-align: center;
        vertical-align: middle;
        color: #374151;
        font-size: 13px;
    }
    .pdrb-table tbody td.td-no { color: #9ca3af; width: 40px; }
    .pdrb-table tbody td.td-left { text-align: left; font-weight: 500; color: #111827; }
    .pdrb-table tbody td.td-num { font-family: monospace; font-size: 12.5px; }

    .growth-up   { color: #166534; font-weight: 700; font-size: 12.5px; white-space: nowrap; }
    .growth-down { color: #991b1b; font-weight: 700; font-size: 12.5px; white-space: nowrap; }

    .pdrb-table tfoot td {
        padding: 10px 14px;
        text-align: center;
        font-weight: 600;
        font-size: 13px;
        color: #111827;
        background: #f3f4f6;
        border-top: 1.5px solid #e5e7eb;
        white-space: nowrap;
    }
    .pdrb-table tfoot td.td-left { text-align: left; }
    .pdrb-table tfoot td.td-num { font-family: monospace; }

    .total-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 100px;
    }
    .total-badge-pct  { background: #fef9c3; color: #854d0e; border: 0.5px solid #fde68a; }
    .total-badge-up   { background: #f0fdf4; color: #166534; border: 0.5px solid #86efac; }
    .total-badge-down { background: #fef2f2; color: #991b1b; border: 0.5px solid #fca5a5; }

    /* Empty state */
    .empty-state {
        padding: 56px 20px;
        text-align: center;
        color: #9ca3af;
    }
    .empty-state svg { margin-bottom: 14px; color: #d1d5db; }
    .empty-state h6 { font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 4px; }
    .empty-state p { font-size: 13px; margin: 0; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <h3 class="fw-bold mb-3">Pembentukan PDRB</h3>

    <div class="mb-3">
        <h5 style="font-size:14px; color:#6b7280; font-weight:400; margin:0;">
            Proses pembentukan PDRB berdasarkan data pendukung (Harga &amp; Produksi, Rasio, IHP, WIP/CBR).
        </h5>
    </div>

    {{-- Info satuan --}}
    <div class="info-satuan">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div>
            <strong>Satuan Hasil: Miliar Rupiah.</strong><br>
            Satuan Input yang dimasukkan adalah dalam Rupiah. Sistem mengkonversi hasil (Output, NTB, ADHB, ADHK)
            ke <strong>Miliar Rupiah</strong> saat menyimpan
        </div>
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
            <form method="GET" action="{{ route('pdrb.index') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Tahun</label>
                        <select name="tahun" class="filter-select" onchange="this.form.submit()">
                            <option value="">— Pilih Tahun —</option>
                            @foreach($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Triwulan</label>
                        <select name="triwulan_id" class="filter-select" onchange="this.form.submit()">
                            <option value="">— Pilih Triwulan —</option>
                            @foreach($triwulans as $tw)
                                <option value="{{ $tw->id }}" {{ request('triwulan_id') == $tw->id ? 'selected' : '' }}>
                                    {{ $tw->triwulan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Status Data Pendukung -->
    <div class="card">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <span class="card-head-title">Status Data Pendukung</span>
            </div>
        </div>
        <div class="card-body">
            <div class="status-grid">

                <div class="status-item">
                    <span class="status-label">Harga &amp; Produksi</span>
                    <span class="status-badge {{ $status['hp'] ? 'badge-ok' : 'badge-fail' }}">
                        @if($status['hp'])
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Lengkap
                        @else
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Tidak Lengkap
                        @endif
                    </span>
                </div>

                <div class="status-item">
                    <span class="status-label">Rasio</span>
                    <span class="status-badge {{ $status['rasio'] ? 'badge-ok' : 'badge-fail' }}">
                        @if($status['rasio'])
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Lengkap
                        @else
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Tidak Lengkap
                        @endif
                    </span>
                </div>

                <div class="status-item">
                    <span class="status-label">IHP</span>
                    <span class="status-badge {{ $status['ihp'] ? 'badge-ok' : 'badge-fail' }}">
                        @if($status['ihp'])
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Lengkap
                        @else
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Tidak Lengkap
                        @endif
                    </span>
                </div>

                <div class="status-item">
                    <div>
                        <span class="status-label">WIP / CBR </span>
                        <span class="status-sublabel">(Opsional)</span>
                    </div>
                    <span class="status-badge {{ $status['wip'] ? 'badge-ok' : 'badge-optional' }}">
                        @if($status['wip'])
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Tersedia
                        @else
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                            Opsional
                        @endif
                    </span>
                </div>

            </div>
        </div>
    </div>

    <!-- Tombol Hitung & Reset -->
    <div class="card">
        <div class="action-bar">
            <form action="{{ route('pdrb.hitung') }}" method="POST" id="formHitung">
                @csrf
                <input type="hidden" name="tahun"       value="{{ request('tahun') }}">
                <input type="hidden" name="triwulan_id" value="{{ request('triwulan_id') }}">
                <button type="submit" class="btn-hitung">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="4" y="2" width="16" height="20" rx="2"/>
                        <line x1="8" y1="6" x2="16" y2="6"/>
                        <line x1="8" y1="10" x2="16" y2="10"/>
                        <line x1="8" y1="14" x2="12" y2="14"/>
                    </svg>
                    Hitung PDRB
                </button>
            </form>

            @if(isset($results) && count($results) > 0)
            <form action="{{ route('pdrb.reset') }}" method="POST" id="formReset">
                @csrf
                @method('DELETE')
                <input type="hidden" name="tahun"       value="{{ request('tahun') }}">
                <input type="hidden" name="triwulan_id" value="{{ request('triwulan_id') }}">
                <button type="button" class="btn-reset" id="btnReset">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                    </svg>
                    Reset Hasil
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Tabel Hasil PDRB -->
    <div class="card" style="margin-bottom:0;">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                <span class="card-head-title">Hasil Pembentukan PDRB</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:11px; color:#6b7280; font-family:'Figtree',sans-serif;">Nilai dalam Miliar Rp</span>
                @if(request('tahun') && request('triwulan_id'))
                    <span class="card-head-badge-period">
                        {{ request('tahun') }} — {{ $triwulans->firstWhere('id', request('triwulan_id'))?->triwulan ?? '' }}
                    </span>
                @endif
            </div>
        </div>

        @if(!request('tahun') || !request('triwulan_id'))
            <div class="empty-state">
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                <h6>Filter Belum Dipilih</h6>
                <p>Silakan pilih <strong>Tahun</strong> dan <strong>Triwulan</strong> untuk melihat hasil pembentukan PDRB.</p>
            </div>

        @elseif(isset($results) && count($results) > 0)
            <div class="table-wrap">
                <table class="pdrb-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="th-left">Sektor</th>
                            <th>Output</th>
                            <th>Biaya Antara</th>
                            <th>ADHB</th>
                            <th>ADHK</th>
                            <th>Kontribusi</th>
                            <th>QoQ</th>
                            <th>YoY</th>
                        </tr>
                        {{-- Baris satuan di bawah header --}}
                        <tr class="th-unit">
                            <td></td>
                            <td></td>
                            <td>Miliar Rp</td>
                            <td>Miliar Rp</td>
                            <td>Miliar Rp</td>
                            <td>Miliar Rp</td>
                            <td>%</td>
                            <td>%</td>
                            <td>%</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $i => $row)
                        @php
                            $biayaAntara = $row->output - $row->ntb;
                            $kontribusi  = $totalAdhb > 0 ? ($row->adhb / $totalAdhb) * 100 : 0;
                            $tumbuh      = $pertumbuhan[$row->sektor_id] ?? null;
                            $tumbuhQoq   = $pertumbuhanQoq[$row->sektor_id] ?? null;
                        @endphp
                        <tr>
                            <td class="td-no">{{ $i + 1 }}</td>
                            <td class="td-left">{{ $row->sektor->nama ?? '-' }}</td>
                            <td class="td-num">{{ number_format($row->output, 2, ',', '.') }}</td>
                            <td class="td-num">{{ number_format($biayaAntara, 2, ',', '.') }}</td>
                            <td class="td-num">{{ number_format($row->adhb, 2, ',', '.') }}</td>
                            <td class="td-num">{{ number_format($row->adhk, 2, ',', '.') }}</td>
                            <td>{{ number_format($kontribusi, 2) }}%</td>
                            <td>
                                @if($tumbuhQoq !== null)
                                    <span class="{{ $tumbuhQoq >= 0 ? 'growth-up' : 'growth-down' }}">
                                        {{ $tumbuhQoq >= 0 ? '▲' : '▼' }} {{ $tumbuhQoq >= 0 ? '+' : '' }}{{ number_format($tumbuhQoq, 2) }}%
                                    </span>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($tumbuh !== null)
                                    <span class="{{ $tumbuh >= 0 ? 'growth-up' : 'growth-down' }}">
                                        {{ $tumbuh >= 0 ? '▲' : '▼' }} {{ $tumbuh >= 0 ? '+' : '' }}{{ number_format($tumbuh, 2) }}%
                                    </span>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="td-left">Total PDRB</td>
                            <td class="td-num">{{ number_format($totalOutput, 2, ',', '.') }}</td>
                            <td class="td-num">{{ number_format($totalOutput - $totalNtb, 2, ',', '.') }}</td>
                            <td class="td-num">{{ number_format($totalAdhb, 2, ',', '.') }}</td>
                            <td class="td-num">{{ number_format($totalAdhk, 2, ',', '.') }}</td>
                            <td><span class="total-badge total-badge-pct">100%</span></td>
                            <td>
                                @if($totalQoq !== null)
                                    <span class="total-badge {{ $totalQoq >= 0 ? 'total-badge-up' : 'total-badge-down' }}">
                                        {{ $totalQoq >= 0 ? '▲ +' : '▼ ' }}{{ number_format($totalQoq, 2) }}%
                                    </span>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($totalYoy !== null)
                                    <span class="total-badge {{ $totalYoy >= 0 ? 'total-badge-up' : 'total-badge-down' }}">
                                        {{ $totalYoy >= 0 ? '▲ +' : '▼ ' }}{{ number_format($totalYoy, 2) }}%
                                    </span>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        @else
            <div class="empty-state">
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                    <ellipse cx="12" cy="5" rx="9" ry="3"/>
                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                </svg>
                <h6>Belum Ada Data</h6>
                <p>Belum ada hasil pembentukan PDRB untuk periode ini. Klik <strong>Hitung PDRB</strong> untuk memulai.</p>
            </div>
        @endif

    </div>

</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        timer: 2200,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(session('error')),
        confirmButtonColor: '#f97316',
        confirmButtonText: 'OK'
    });
    @endif

    document.getElementById('formHitung').addEventListener('submit', function (e) {
        const tahun    = "{{ request('tahun') }}";
        const triwulan = "{{ request('triwulan_id') }}";
        if (!tahun || !triwulan) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Periode Belum Dipilih',
                text: 'Silakan pilih Tahun dan Triwulan terlebih dahulu sebelum menghitung PDRB.',
                confirmButtonColor: '#f97316',
                confirmButtonText: 'OK'
            });
        }
    });

    @if(isset($results) && count($results) > 0)
    document.getElementById('btnReset')?.addEventListener('click', function () {
        const tahun    = "{{ request('tahun') }}";
        const triwulan = "{{ request('triwulan_id') }}";
        if (!tahun || !triwulan) {
            Swal.fire({
                icon: 'warning',
                title: 'Periode Belum Dipilih',
                text: 'Silakan pilih Tahun dan Triwulan terlebih dahulu.',
                confirmButtonColor: '#f97316',
                confirmButtonText: 'OK'
            });
            return;
        }
        Swal.fire({
            icon: 'warning',
            title: 'Reset Data PDRB?',
            html: 'Data PDRB periode <strong>{{ request('tahun') }} — {{ $triwulans->firstWhere('id', request('triwulan_id'))?->triwulan ?? '' }}</strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan.',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('formReset').submit();
        });
    });
    @endif

});
</script>
@endsection