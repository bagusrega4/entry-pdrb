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

    .card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .card-head {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        border-bottom: 0.5px solid #e5e7eb;
        background: #fafafa;
        gap: 8px;
    }
    .card-head-icon { color: #9ca3af; }
    .card-head-title {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
    }

    .card-body {
        padding: 24px 20px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 540px) {
        .form-row { grid-template-columns: 1fr; }
    }

    .form-label {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
    }

    .form-input {
        height: 38px;
        padding: 0 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #111827;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%;
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .form-input::-webkit-inner-spin-button,
    .form-input::-webkit-outer-spin-button { -webkit-appearance: none; }
    .form-input:hover { border-color: #9ca3af; }
    .form-input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }
    .form-input::placeholder { color: #9ca3af; }

    .form-hint {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .info-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        background: #fff7ed;
        border: 0.5px solid #fed7aa;
        border-radius: 8px;
        font-size: 13px;
        color: #c2410c;
        line-height: 1.5;
    }
    .info-box svg { flex-shrink: 0; margin-top: 1px; }

    .card-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-top: 0.5px solid #e5e7eb;
        background: #fafafa;
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
    .btn-back:hover {
        background: #ea580c;
        border-color: #c2410c;
        color: #fff;
        text-decoration: none;
    }
    .btn-back:active { transform: scale(0.98); }
    .btn-back svg { color: rgba(255,255,255,0.8); }

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
    .btn-save:hover {
        background: #ea580c;
        border-color: #c2410c;
        color: #fff;
    }
    .btn-save:active { transform: scale(0.98); }
    .btn-save svg { color: rgba(255,255,255,0.8); }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <h3 class="fw-bold mb-3">Tambah Dataset IO</h3>

    <div class="mb-3">
        <h5>Buat dataset baru untuk mengisi tabel Input-Output.</h5>
    </div>

    <div class="card">

        <div class="card-head">
            <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            <span class="card-head-title">Informasi Dataset</span>
        </div>

        <form action="{{ route('io.store') }}" method="POST" id="formDataset">
            @csrf

            <div class="card-body">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Dataset</label>
                        <input
                            type="text"
                            name="nama_dataset"
                            class="form-input"
                            placeholder="Contoh: IO Jawa Barat 2024"
                            value="{{ old('nama_dataset') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tahun</label>
                        <input
                            type="number"
                            name="tahun"
                            class="form-input"
                            placeholder="Contoh: 2024"
                            value="{{ old('tahun') }}"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Sektor</label>
                    <input
                        type="number"
                        name="jumlah_sektor"
                        class="form-input"
                        value="{{ old('jumlah_sektor', 17) }}"
                        min="1"
                        max="17"
                        required
                        style="max-width: 200px;"
                    >
                    <span class="form-hint">Default 17 sektor — bisa diubah sesuai kebutuhan (maks. 17)</span>
                </div>

                <div class="info-box">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                    </svg>
                    Setelah dataset dibuat, Anda dapat mengisi tabel Input-Output pada menu <strong>Input</strong>.
                </div>

            </div>

            <div class="card-foot">
                <a href="{{ route('io.index') }}" class="btn-back">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 5l-7 7 7 7"/>
                    </svg>
                    Kembali
                </a>

                <button type="submit" class="btn-save" id="btnSubmit">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Dataset
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    @if ($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal',
        html: `<ul style="text-align:left;margin:0;padding-left:1.2rem;">
            @foreach ($errors->all() as $error)
                <li style="font-size:14px;margin-bottom:4px;">{{ $error }}</li>
            @endforeach
        </ul>`,
        confirmButtonText: 'OK',
        confirmButtonColor: '#f97316'
    });
    @endif

    const form = document.getElementById('formDataset');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan Dataset?',
            text: 'Data akan ditambahkan ke sistem.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

});
</script>

@endsection