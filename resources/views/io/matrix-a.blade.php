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

    .page-header { margin-bottom: 20px; display: flex; flex-direction: column; align-items: flex-start; text-align: left; }
    .page-title {
        font-size: 20px;
        font-weight: 600;
        color: #111827;
        letter-spacing: -0.2px;
        text-align: left;
    }
    .page-sub {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
        text-align: left;
    }

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

    .mat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 600px;
    }

    .mat-table thead th {
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
    .mat-table thead th:first-child { text-align: left; }

    .mat-table tbody tr:hover td,
    .mat-table tbody tr:hover th { background: #f9fafb; }

    .mat-table tbody tr:not(:last-child) td,
    .mat-table tbody tr:not(:last-child) th {
        border-bottom: 0.5px solid #f0f0f0;
    }

    .mat-table tbody th.row-label {
        font-weight: 500;
        font-size: 13px;
        color: #374151;
        padding: 10px 16px;
        text-align: left;
        white-space: nowrap;
        background: #f9fafb;
        border-right: 0.5px solid #e5e7eb;
        position: sticky;
        left: 0;
        z-index: 1;
        min-width: 180px;
    }

    .mat-table tbody td {
        padding: 10px 12px;
        text-align: center;
        vertical-align: middle;
        font-family: 'Figtree', monospace;
        font-size: 13px;
        color: #1f2937;
        min-width: 110px;
    }

    /* Subtle color scale hint — nilai mendekati 0 lebih pudar */
    .mat-table tbody td .cell-val {
        display: inline-block;
        width: 100%;
        text-align: right;
    }

    .card-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-top: 0.5px solid #e5e7eb;
        background: #fafafa;
    }
    .foot-note {
        font-size: 12px;
        color: #9ca3af;
    }

    .btn-back {
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
        text-decoration: none;
        transition: background 0.12s, border-color 0.12s;
        font-family: 'Figtree', sans-serif;
    }
    .btn-back:hover { background: #ea580c; border-color: #c2410c; color: #fff; text-decoration: none; }
    .btn-back:active { transform: scale(0.98); }
    .btn-back svg { color: rgba(255,255,255,0.8); }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <h3 class="fw-bold mb-3">Matrix A</h3>

    <div class="mb-3">
        <h5>{{ $dataset->nama_dataset }} - {{ $dataset->tahun }}</h5>
    </div>

    <div class="card">

        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                </svg>
                <span class="card-head-title">Matriks Koefisien Teknologi (A)</span>
            </div>
            <span class="card-head-badge">{{ $dataset->jumlah_sektor }} Sektor</span>
        </div>

        <div class="table-wrap">
            <table class="mat-table">
                <thead>
                    <tr>
                        <th>Sektor</th>
                        @foreach($sektors as $sektor)
                            <th>{{ $sektor->nama }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= $dataset->jumlah_sektor; $i++)
                    <tr>
                        <th class="row-label">{{ $sektors[$i-1]->nama ?? 'Sektor '.$i }}</th>
                        @for($j = 1; $j <= $dataset->jumlah_sektor; $j++)
                        <td>{{ number_format($A[$i][$j] ?? 0, 4) }}</td>
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="card-foot">
            <span class="foot-note">A<sub>ij</sub> = Z<sub>ij</sub> / X<sub>j</sub> &nbsp;&mdash;&nbsp; nilai menunjukkan proporsi input dari sektor i terhadap output sektor j</span>
            <a href="{{ route('io.index') }}" class="btn-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Kembali
            </a>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session("success") }}',
        timer: 2200,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
    @endif

    @if ($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan',
        html: `<ul style="text-align:left;margin:0;padding-left:1.2rem;">
            @foreach($errors->all() as $error)
                <li style="font-size:14px;margin-bottom:4px;">{{ $error }}</li>
            @endforeach
        </ul>`
    });
    @endif

});
</script>
@endsection