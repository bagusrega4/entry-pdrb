@extends('layouts/app')

@section('content')

<style>
    * { box-sizing: border-box; }

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

    /* Filter bar */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px 20px;
        border-bottom: 0.5px solid #e5e7eb;
        background: #fafafa;
        align-items: center;
        justify-content: space-between;
    }
    .filter-inputs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .filter-actions {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
    }

    .filter-input, .filter-select {
        height: 34px;
        padding: 0 10px;
        font-size: 12px;
        font-family: 'Figtree', sans-serif;
        color: #374151;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 7px;
        outline: none;
        transition: border-color 0.15s;
        min-width: 140px;
    }
    .filter-input:focus, .filter-select:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.08);
    }
    .filter-input::placeholder { color: #9ca3af; }

    .btn-filter {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 34px;
        padding: 0 14px;
        font-size: 12px;
        font-weight: 500;
        color: #fff;
        background: #f97316;
        border: 0.5px solid #ea580c;
        border-radius: 7px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        text-decoration: none;
        transition: background 0.12s;
    }
    .btn-filter:hover { background: #ea580c; }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 34px;
        padding: 0 14px;
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 7px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        text-decoration: none;
        transition: background 0.12s;
    }
    .btn-reset:hover { background: #f9fafb; border-color: #9ca3af; color: #374151; text-decoration: none; }

    /* Table */
    .table-wrap { overflow-x: auto; }

    .doc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .doc-table thead th {
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
    .doc-table thead th.th-center { text-align: center; }

    .doc-table tbody tr:hover td { background: #fffbf7; }
    .doc-table tbody tr:not(:last-child) td { border-bottom: 0.5px solid #f0f0f0; }

    .doc-table tbody td {
        padding: 10px 16px;
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }
    .doc-table tbody td.td-no { color: #9ca3af; text-align: center; width: 40px; }
    .doc-table tbody td.td-center { text-align: center; }
    .doc-table tbody td.td-name { font-weight: 500; color: #111827; }

    /* Badges */
    .badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 500;
        padding: 2px 8px;
        border-radius: 100px;
        white-space: nowrap;
    }
    .badge-modul { background: #fff7ed; color: #c2410c; border: 0.5px solid #fed7aa; }
    .badge-jenis { background: #f3f4f6; color: #374151; border: 0.5px solid #e5e7eb; }

    /* Action buttons */
    .aksi-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
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

    .btn-view  { background: #fff7ed; border-color: #fed7aa; color: #c2410c; }
    .btn-edit  { background: #fffbeb; border-color: #fcd34d; color: #92400e; }
    .btn-hapus { background: #fef2f2; border-color: #fca5a5; color: #991b1b; }

    /* Empty state */
    .empty-state {
        padding: 48px 20px;
        text-align: center;
        color: #9ca3af;
    }
    .empty-state svg { margin-bottom: 12px; color: #d1d5db; }
    .empty-state p { font-size: 13px; margin: 0; }

    /* Pagination wrapper */
    .card-foot-paging {
        padding: 12px 20px;
        border-top: 0.5px solid #e5e7eb;
        background: #fafafa;
    }

    /* MODAL EDIT */
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
        max-width: 500px;
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
        width: 28px; height: 28px;
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
        gap: 14px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }

    .form-label { font-size: 13px; font-weight: 500; color: #374151; }

    .form-input, .form-select, .form-textarea {
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
    }
    .form-input, .form-select { height: 38px; }
    .form-textarea { padding: 10px 12px; resize: vertical; min-height: 80px; }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.08);
    }
    .form-input::-webkit-inner-spin-button { -webkit-appearance: none; }

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

    <h3 class="fw-bold mb-3">Kelola Dokumen Pendukung</h3>

    <div class="mb-3">
        <h5 style="font-size:14px; color:#6b7280; font-weight:400; margin:0;">Daftar seluruh dokumen pendukung yang telah diupload.</h5>
    </div>

    <div class="card">

        <!-- Card Header -->
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                <span class="card-head-title">Daftar Dokumen</span>
            </div>
            <span class="card-head-badge">{{ $documents->total() }} dokumen</span>
        </div>

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('documents.index') }}">
            <div class="filter-bar">

                <div class="filter-inputs">
                    <input
                        type="text"
                        name="search"
                        class="filter-input"
                        placeholder="Cari nama dokumen..."
                        value="{{ request('search') }}"
                    >

                    <select name="modul" class="filter-select">
                        <option value="">Semua Data</option>
                        <option value="harga_produksi" {{ request('modul') == 'harga_produksi' ? 'selected' : '' }}>Harga & Produksi</option>
                        <option value="rasio"          {{ request('modul') == 'rasio'          ? 'selected' : '' }}>Rasio</option>
                        <option value="ihp"            {{ request('modul') == 'ihp'            ? 'selected' : '' }}>IHP</option>
                        <option value="wip_cbr"        {{ request('modul') == 'wip_cbr'        ? 'selected' : '' }}>WIP/CBR</option>
                    </select>

                    <select name="tahun" class="filter-select">
                        <option value="">Semua Tahun</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>

                    <select name="triwulan_id" class="filter-select">
                        <option value="">Semua Triwulan</option>
                        @foreach($triwulans as $tw)
                            <option value="{{ $tw->id }}" {{ request('triwulan_id') == $tw->id ? 'selected' : '' }}>
                                {{ $tw->triwulan }}
                            </option>
                        @endforeach
                    </select>

                    <select name="jenis" class="filter-select">
                        <option value="">Semua Jenis</option>
                        <option value="laporan" {{ request('jenis') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                        <option value="survey"  {{ request('jenis') == 'survey'  ? 'selected' : '' }}>Survey</option>
                        <option value="sensus"  {{ request('jenis') == 'sensus'  ? 'selected' : '' }}>Sensus</option>
                        <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('documents.index') }}" class="btn-reset">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 1 0 .49-4.5"/>
                        </svg>
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <!-- Table -->
        <div class="table-wrap">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th class="th-center">No</th>
                        <th>Nama Dokumen</th>
                        <th>Data Pendukung</th>
                        <th class="th-center">Tahun</th>
                        <th class="th-center">Triwulan</th>
                        <th class="th-center">Jenis</th>
                        <th>Keterangan</th>
                        <th class="th-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td class="td-no">{{ $loop->iteration }}</td>
                        <td class="td-name">{{ $doc->nama }}</td>
                        <td><span class="badge badge-modul">{{ $doc->modul_label }}</span></td>
                        <td class="td-center">{{ $doc->tahun ?? '-' }}</td>
                        <td class="td-center">{{ $doc->triwulan->triwulan ?? '-' }}</td>
                        <td class="td-center"><span class="badge badge-jenis">{{ ucfirst($doc->jenis ?? '-') }}</span></td>
                        <td style="color:#6b7280;font-size:12px;">{{ Str::limit($doc->keterangan, 50) ?? '-' }}</td>
                        <td class="td-center">
                            <div class="aksi-group">

                                <a href="{{ route('documents.view', [$doc->id, Str::slug($doc->nama)]) }}"
                                   target="_blank"
                                   class="btn-aksi btn-view">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Lihat
                                </a>

                                <button
                                    type="button"
                                    class="btn-aksi btn-edit btn-open-edit"
                                    data-id="{{ $doc->id }}"
                                    data-nama="{{ $doc->nama }}"
                                    data-tahun="{{ $doc->tahun }}"
                                    data-triwulan="{{ $doc->triwulan_id }}"
                                    data-jenis="{{ $doc->jenis }}"
                                    data-keterangan="{{ $doc->keterangan }}"
                                >
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit
                                </button>

                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="form-delete" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-hapus">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                                <p>Belum ada dokumen pendukung.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-foot-paging">
            {{ $documents->links() }}
        </div>

    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">

        <div class="modal-head">
            <div class="modal-head-left">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="color:#9ca3af;">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                <span class="modal-title">Edit Dokumen</span>
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
                    <label class="form-label">Nama Dokumen</label>
                    <input type="text" name="nama" id="editNama" class="form-input" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="tahun" id="editTahun" class="form-input" placeholder="Contoh: 2024">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Triwulan</label>
                        <select name="triwulan_id" id="editTriwulan" class="form-select">
                            @foreach($triwulans ?? [] as $tw)
                                <option value="{{ $tw->id }}">{{ $tw->triwulan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Dokumen</label>
                    <select name="jenis" id="editJenis" class="form-select">
                        <option value="laporan">Laporan</option>
                        <option value="survey">Hasil Survei</option>
                        <option value="sensus">Hasil Sensus</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="editKeterangan" class="form-textarea"></textarea>
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
        text: @json(session('success')),
        timer: 2200,
        showConfirmButton: false
    });
    @endif

    @if($errors->any())
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

    // Delete confirmation
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Dokumen?',
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
    const overlay        = document.getElementById('modalEdit');
    const formEdit       = document.getElementById('formEdit');
    const editNama       = document.getElementById('editNama');
    const editTahun      = document.getElementById('editTahun');
    const editTriwulan   = document.getElementById('editTriwulan');
    const editJenis      = document.getElementById('editJenis');
    const editKeterangan = document.getElementById('editKeterangan');

    function openModal(btn) {
        const id = btn.dataset.id;
        formEdit.action      = `/documents/${id}`;
        editNama.value       = btn.dataset.nama;
        editTahun.value      = btn.dataset.tahun;
        editKeterangan.value = btn.dataset.keterangan;
        if (editTriwulan) editTriwulan.value = btn.dataset.triwulan;
        if (editJenis)    editJenis.value    = btn.dataset.jenis;
        overlay.classList.add('open');
    }

    function closeModal() { overlay.classList.remove('open'); }

    document.querySelectorAll('.btn-open-edit').forEach(btn => {
        btn.addEventListener('click', function() { openModal(this); });
    });

    document.getElementById('modalCloseBtn').addEventListener('click', closeModal);
    document.getElementById('modalCancelBtn').addEventListener('click', closeModal);
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closeModal(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

    // Konfirmasi simpan
    formEdit.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Data dokumen akan diperbarui.',
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