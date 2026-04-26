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

    .page-header {
        margin-bottom: 20px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
    }
    .page-header-left {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }
    .page-title {
        font-size: 23px;
        font-weight: 600;
        color: #111827;
        letter-spacing: -0.2px;
    }
    .page-sub {
        font-size: 17px;
        color: #6b7280;
        margin-top: 4px;
    }

    .btn-add {
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
        white-space: nowrap;
        transition: background 0.12s, border-color 0.12s;
        font-family: 'Figtree', sans-serif;
        flex-shrink: 0;
    }
    .btn-add:hover { background: #ea580c; border-color: #c2410c; color: #fff; }
    .btn-add:active { transform: scale(0.98); }
    .btn-add svg { color: rgba(255,255,255,0.8); }

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

    .table-wrap { overflow-x: auto; }

    .io-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .io-table thead th {
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        padding: 10px 16px;
        text-align: left;
        border-bottom: 0.5px solid #e5e7eb;
        background: #f9fafb;
        white-space: nowrap;
    }
    .io-table thead th.th-center { text-align: center; }
    .io-table thead th.th-aksi { text-align: center; width: 300px; }

    .io-table tbody tr:hover td { background: #f9fafb; }
    .io-table tbody tr:not(:last-child) td { border-bottom: 0.5px solid #f0f0f0; }

    .io-table tbody td {
        padding: 10px 16px;
        color: #1f2937;
        font-size: 13px;
        vertical-align: middle;
    }
    .io-table tbody td.td-center { text-align: center; }
    .io-table tbody td.td-no { color: #9ca3af; text-align: center; width: 48px; }
    .io-table tbody td.td-name { font-weight: 500; color: #111827; }
    .td-aksi { text-align: center; }

    .aksi-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-aksi {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0 10px;
        height: 30px;
        font-size: 12px;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        border: 0.5px solid transparent;
        transition: opacity 0.12s, transform 0.1s;
        font-family: 'Figtree', sans-serif;
        white-space: nowrap;
        background: none;
    }
    .btn-aksi:hover { opacity: 0.8; text-decoration: none; }
    .btn-aksi:active { transform: scale(0.97); }
    .btn-aksi svg { flex-shrink: 0; }

    .btn-input  { background: #f0fdf4; border-color: #86efac; color: #166534; }
    .btn-edit   { background: #fffbeb; border-color: #fcd34d; color: #92400e; }
    .btn-hapus  { background: #fef2f2; border-color: #fca5a5; color: #991b1b; }
    .btn-matrix { background: #eff6ff; border-color: #93c5fd; color: #1e40af; }
    .btn-multi  { background: #f5f3ff; border-color: #c4b5fd; color: #5b21b6; }

    .empty-state {
        padding: 48px 20px;
        text-align: center;
        color: #9ca3af;
    }
    .empty-state svg { margin-bottom: 12px; color: #d1d5db; }
    .empty-state p { font-size: 13px; margin: 0; }

    /* ── MODAL ── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.35);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-overlay.open { display: flex; }

    .modal-box {
        background: #fff;
        border-radius: 12px;
        border: 0.5px solid #e5e7eb;
        width: 100%;
        max-width: 480px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        animation: modalIn 0.18s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: translateY(12px) scale(0.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 0.5px solid #e5e7eb;
        background: #fafafa;
    }
    .modal-head-left { display: flex; align-items: center; gap: 8px; }
    .modal-title { font-size: 14px; font-weight: 600; color: #111827; }
    .modal-close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: none;
        background: none;
        cursor: pointer;
        color: #9ca3af;
        transition: background 0.12s, color 0.12s;
    }
    .modal-close:hover { background: #f3f4f6; color: #374151; }

    .modal-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-label { font-size: 13px; font-weight: 500; color: #374151; }
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

    .modal-foot {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        padding: 12px 20px;
        border-top: 0.5px solid #e5e7eb;
        background: #fafafa;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        height: 36px;
        padding: 0 16px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        transition: background 0.12s;
    }
    .btn-cancel:hover { background: #f9fafb; border-color: #9ca3af; }

    .btn-update {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 18px;
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
    .btn-update:hover { background: #ea580c; }
    .btn-update svg { color: rgba(255,255,255,0.8); }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <div class="page-header">
        <div class="page-header-left">
            <div class="page-title">Kelola Tabel IO</div>
            <div class="page-sub">Daftar seluruh dataset Input-Output yang tersedia.</div>
        </div>
        <a href="{{ route('io.create') }}" class="btn-add">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Dataset
        </a>
    </div>

    <div class="card">

        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                </svg>
                <span class="card-head-title">Dataset IO</span>
            </div>
            <span class="card-head-badge">{{ $datasets->count() }} dataset</span>
        </div>

        <div class="table-wrap">
            <table class="io-table">
                <thead>
                    <tr>
                        <th class="th-center">No</th>
                        <th>Nama Dataset</th>
                        <th class="th-center">Tahun</th>
                        <th class="th-center">Jumlah Sektor</th>
                        <th class="th-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datasets as $index => $data)
                    <tr>
                        <td class="td-no">{{ $index + 1 }}</td>
                        <td class="td-name">{{ $data->nama_dataset }}</td>
                        <td class="td-center">{{ $data->tahun }}</td>
                        <td class="td-center">{{ $data->jumlah_sektor }}</td>
                        <td class="td-aksi">
                            <div class="aksi-group">

                                <a href="{{ route('io.input', $data->id) }}" class="btn-aksi btn-input">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                                    </svg>
                                    Input
                                </a>

                                {{-- Tombol Edit → buka modal --}}
                                <button
                                    type="button"
                                    class="btn-aksi btn-edit btn-open-edit"
                                    data-id="{{ $data->id }}"
                                    data-nama="{{ $data->nama_dataset }}"
                                    data-tahun="{{ $data->tahun }}"
                                >
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit
                                </button>

                                <form action="{{ route('io.destroy', $data->id) }}" method="POST" class="form-delete" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-hapus">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>

                                <a href="{{ route('io.matrixA', $data->id) }}" class="btn-aksi btn-matrix">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 7h16M4 12h16M4 17h16"/>
                                    </svg>
                                    Matrix A
                                </a>

                                <a href="{{ route('io.leontief', $data->id) }}" class="btn-aksi btn-multi">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                                    </svg>
                                    Leontief
                                </a>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                                </svg>
                                <p>Belum ada dataset IO. Klik <strong>Tambah Dataset</strong> untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- ── MODAL EDIT ── --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">

        <div class="modal-head">
            <div class="modal-head-left">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="color:#9ca3af;">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                <span class="modal-title">Edit Dataset</span>
            </div>
            <button class="modal-close" id="modalCloseBtn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Dataset</label>
                    <input
                        type="text"
                        name="nama_dataset"
                        id="editNama"
                        class="form-input"
                        placeholder="Contoh: IO Jawa Barat 2024"
                        required
                    >
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun</label>
                    <input
                        type="number"
                        name="tahun"
                        id="editTahun"
                        class="form-input"
                        placeholder="Contoh: 2024"
                        required
                    >
                </div>
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-cancel" id="modalCancelBtn">Batal</button>
                <button type="submit" class="btn-update">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Perubahan
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

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session("success") }}',
        timer: 2200,
        showConfirmButton: false
    });
    @endif

    // Delete confirmation
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Dataset?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#e5e7eb',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // Modal edit
    const overlay    = document.getElementById('modalEdit');
    const formEdit   = document.getElementById('formEdit');
    const editNama   = document.getElementById('editNama');
    const editTahun  = document.getElementById('editTahun');

    function openModal(id, nama, tahun) {
        const action = `/io/${id}`;   // sesuaikan prefix route jika perlu
        formEdit.action = action;
        editNama.value  = nama;
        editTahun.value = tahun;
        overlay.classList.add('open');
    }

    function closeModal() {
        overlay.classList.remove('open');
    }

    document.querySelectorAll('.btn-open-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            openModal(
                this.dataset.id,
                this.dataset.nama,
                this.dataset.tahun
            );
        });
    });

    document.getElementById('modalCloseBtn').addEventListener('click', closeModal);
    document.getElementById('modalCancelBtn').addEventListener('click', closeModal);

    // Klik overlay di luar modal-box → tutup
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closeModal();
    });

    // Esc → tutup
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // Konfirmasi sebelum update
    formEdit.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Data dataset akan diperbarui.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) formEdit.submit();
        });
    });

});
</script>
@endsection