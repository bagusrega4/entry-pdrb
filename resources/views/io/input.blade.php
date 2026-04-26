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

    .legend-bar {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #6b7280;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 3px;
        flex-shrink: 0;
    }
    .dot-z   { background: #f3f4f6; border: 0.5px solid #d1d5db; }
    .dot-fd  { background: #FFF9EC; border: 0.5px solid #E8C96A; }
    .dot-out { background: #F0FAF4; border: 0.5px solid #7ECFA3; }

    .card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 0.5px solid #e5e7eb;
        background: #fafafa;
    }
    .card-head-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-head-icon { color: #9ca3af; }
    .card-head-title {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
    }
    .card-head-badge {
        font-size: 11px;
        padding: 2px 8px;
        background: #f3f4f6;
        border: 0.5px solid #e5e7eb;
        border-radius: 100px;
        color: #6b7280;
    }

    .table-wrap { overflow-x: auto; }

    .io-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 600px;
    }

    .io-table thead th {
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        padding: 10px 12px;
        text-align: center;
        border-bottom: 0.5px solid #e5e7eb;
        background: #f9fafb;
        white-space: nowrap;
    }
    .io-table thead th:first-child { text-align: left; }
    .io-table thead th.th-fd {
        border-left: 0.5px solid #E8C96A;
        background: #FFF9EC;
        color: #A07830;
    }
    .io-table thead th.th-out {
        border-left: 0.5px solid #7ECFA3;
        background: #F0FAF4;
        color: #2D7A52;
    }

    .io-table tbody tr:hover td,
    .io-table tbody tr:hover th { background: #f9fafb; }

    .io-table tbody tr:not(:last-child) td,
    .io-table tbody tr:not(:last-child) th {
        border-bottom: 0.5px solid #f0f0f0;
    }

    .io-table tbody th.row-label {
        font-weight: 500;
        font-size: 13px;
        color: #374151;
        padding: 8px 16px;
        text-align: left;
        white-space: nowrap;
        background: #f9fafb;
        border-right: 0.5px solid #e5e7eb;
        position: sticky;
        left: 0;
        z-index: 1;
        min-width: 180px;
    }

    .io-table tbody td {
        padding: 6px 8px;
        text-align: center;
        vertical-align: middle;
        min-width: 120px;
    }
    .io-table tbody td.td-fd { border-left: 0.5px solid #E8C96A; }
    .io-table tbody td.td-out { border-left: 0.5px solid #7ECFA3; }

    /* Input base */
    .io-table input[type="number"] {
        display: block;
        width: 100%;
        height: 36px;
        padding: 0 10px;
        font-size: 13px;
        font-family: 'Figtree', monospace;
        color: #1f2937;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        text-align: right;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .io-table input[type="number"]::-webkit-inner-spin-button,
    .io-table input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
    }
    .io-table input[type="number"]:hover { border-color: #9ca3af; }
    .io-table input[type="number"]:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }

    /* Final demand input */
    .fd-input {
        background: #FFF9EC !important;
        border-color: #E8C96A !important;
        color: #7A5C1E !important;
    }
    .fd-input:focus {
        box-shadow: 0 0 0 3px rgba(232,201,106,0.2) !important;
    }

    /* Output input */
    .out-input {
        background: #F0FAF4 !important;
        border-color: #7ECFA3 !important;
        color: #1E6E42 !important;
        font-weight: 600;
        cursor: default;
        pointer-events: none;
    }

    /* Footer */
    .card-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-top: 0.5px solid #e5e7eb;
        background: #fafafa;
        gap: 12px;
    }
    .foot-note {
        font-size: 12px;
        color: #9ca3af;
    }
    .foot-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .saved-toast {
        display: none;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #2D7A52;
        background: #F0FAF4;
        border: 0.5px solid #7ECFA3;
        border-radius: 8px;
        padding: 6px 12px;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 18px;
        height: 36px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        background: #f97316;
        border: 0.5px solid #ea580c;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.12s, border-color 0.12s;
        font-family: 'Figtree', sans-serif;
    }
    .btn-save:hover { background: #ea580c; border-color: #c2410c; }
    .btn-save:active { transform: scale(0.98); }
    .btn-save svg { color: rgba(255,255,255,0.8); }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <h3 class="fw-bold mb-3">Input Tabel IO</h3>

    <div class="mb-3">
        <h5>Isi nilai matriks Z dan final demand — output akan terhitung otomatis. <strong style="color:#c2410c;">Semua nilai dalam satuan Miliar Rupiah.</strong></h5>
    </div>

    <div class="legend-bar">
        <div class="legend-item">
            <div class="legend-dot dot-z"></div>
            Matriks Z — transaksi antar sektor (Miliar Rp)
        </div>
        <div class="legend-item">
            <div class="legend-dot dot-fd"></div>
            Final Demand
        </div>
        <div class="legend-item">
            <div class="legend-dot dot-out"></div>
            Output — otomatis
        </div>
    </div>

    <div class="card">

        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                </svg>
                <span class="card-head-title">Tabel Input-Output</span>
            </div>
            <span class="card-head-badge">{{ $dataset->jumlah_sektor }} Sektor</span>
        </div>

        <form action="{{ route('io.storeMatrix', $dataset->id) }}" method="POST">
            @csrf

            <div class="table-wrap">
                <table class="io-table" id="ioTable">
                    <thead>
                        <tr>
                            <th>Sektor</th>
                            @foreach($sektors as $sektor)
                                <th>{{ $sektor->nama }}</th>
                            @endforeach
                            <th class="th-fd">Final Demand <span style="font-weight:400;text-transform:none;font-size:10px;opacity:0.7;"></span></th>
                            <th class="th-out">Output <span style="font-weight:400;text-transform:none;font-size:10px;opacity:0.7;">(Miliar Rp)</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i <= $dataset->jumlah_sektor; $i++)
                        <tr>
                            <th class="row-label">{{ $sektors[$i-1]->nama ?? 'Sektor '.$i }}</th>

                            @for($j = 1; $j <= $dataset->jumlah_sektor; $j++)
                            <td>
                                <input
                                    type="number"
                                    step="any"
                                    name="z[{{ $i }}][{{ $j }}]"
                                    class="z-input"
                                    data-row="{{ $i }}"
                                    value="{{ $z[$i][$j] ?? 0 }}"
                                >
                            </td>
                            @endfor

                            <td class="td-fd">
                                <input
                                    type="number"
                                    step="any"
                                    name="fd[{{ $i }}]"
                                    class="fd-input"
                                    data-row="{{ $i }}"
                                    value="{{ $fd[$i] ?? 0 }}"
                                >
                            </td>

                            <td class="td-out">
                                <input
                                    type="number"
                                    step="any"
                                    name="output[{{ $i }}]"
                                    class="out-input"
                                    data-row="{{ $i }}"
                                    value="{{ $out[$i] ?? 0 }}"
                                    readonly
                                >
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <div class="card-foot">
                <span class="foot-note">Output = Σ Z (baris) + Final Demand &nbsp;—&nbsp; semua nilai dalam <strong>Miliar Rupiah</strong></span>
                <div class="foot-right">
                    <div id="savedToast" class="saved-toast">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Tersimpan
                    </div>
                    <button type="submit" class="btn-save">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Simpan Tabel IO
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session("success") }}',
    timer: 2200,
    showConfirmButton: false,
    toast: true,
    position: 'top-end'
});
</script>
@endif

@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Terjadi Kesalahan',
    html: `<ul style="text-align:left;margin:0;padding-left:1.2rem;">
        @foreach($errors->all() as $error)
            <li style="font-size:14px;margin-bottom:4px;">{{ $error }}</li>
        @endforeach
    </ul>`
});
</script>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {

    function hitungOutput(row) {
        let total = 0;

        document.querySelectorAll('.z-input[data-row="' + row + '"]').forEach(el => {
            total += parseFloat(el.value) || 0;
        });

        const fd = document.querySelector('.fd-input[data-row="' + row + '"]');
        if (fd) total += parseFloat(fd.value) || 0;

        const out = document.querySelector('.out-input[data-row="' + row + '"]');
        if (out) out.value = parseFloat(total.toFixed(4));
    }

    function hitungSemua() {
        document.querySelectorAll('.out-input').forEach(el => {
            hitungOutput(el.dataset.row);
        });
    }

    document.querySelectorAll('.z-input, .fd-input').forEach(input => {
        input.addEventListener('input', function () {
            hitungOutput(this.dataset.row);
        });
    });

    hitungSemua();

    // Toast on submit
    const form = document.querySelector('form');
    const toast = document.getElementById('savedToast');
    if (form && toast) {
        form.addEventListener('submit', function () {
            toast.style.display = 'flex';
        });
    }
});
</script>
@endsection