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

    /* ── Card ── */
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
        flex-wrap: wrap;
    }
    .card-head-left  { display: flex; align-items: center; gap: 8px; }
    .card-head-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .card-head-icon  { color: #9ca3af; flex-shrink: 0; }
    .card-head-title { font-size: 14px; font-weight: 500; color: #111827; }
    .card-body { padding: 20px; }

    /* ── Filter row ── */
    .filter-row { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 200px; }
    .filter-label { font-size: 12px; font-weight: 500; color: #374151; }
    .filter-select {
        height: 38px;
        padding: 0 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #374151;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s;
        width: 100%;
    }
    .filter-select:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.08);
    }

    /* ── Buttons ── */
    .btn-base {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 14px;
        font-size: 12.5px;
        font-weight: 500;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        border: 0.5px solid transparent;
        white-space: nowrap;
        transition: background 0.12s, border-color 0.12s;
        text-decoration: none;
    }
    .btn-primary-o   { background: #f97316; color: #fff; border-color: #ea580c; }
    .btn-primary-o:hover { background: #ea580c; }
    .btn-secondary-o { background: #f3f4f6; color: #374151; border-color: #e5e7eb; }
    .btn-secondary-o:hover { background: #e5e7eb; }
    .btn-ghost-green { background: #f0fdf4; color: #166534; border-color: #86efac; }
    .btn-ghost-green:hover { background: #dcfce7; }
    .btn-ghost-amber { background: #fffbeb; color: #92400e; border-color: #fde68a; }
    .btn-ghost-amber:hover { background: #fef3c7; }
    .btn-ghost-blue  { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
    .btn-ghost-blue:hover  { background: #dbeafe; }
    .btn-save { background: #f97316; color: #fff; border-color: #ea580c; font-size: 13px; height: 38px; padding: 0 18px; }
    .btn-save:hover { background: #ea580c; }

    /* ── Year filter ── */
    .year-filter-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .year-filter-label {
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
        white-space: nowrap;
    }

    /* ── Notice bar ── */
    .notice-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: #fefce8;
        border: 0.5px solid #fde047;
        border-radius: 8px;
        font-size: 12.5px;
        color: #854d0e;
        margin-bottom: 16px;
    }
    .notice-bar svg { flex-shrink: 0; color: #ca8a04; }

    /* ── Table ── */
    .table-wrap { overflow-x: auto; }

    #ihpTable {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
        min-width: 600px;
        font-family: 'Figtree', sans-serif;
    }

    #ihpTable thead th {
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #6b7280;
        padding: 9px 12px;
        border-bottom: 0.5px solid #e5e7eb;
        border-right: 0.5px solid #f3f4f6;
        background: #f9fafb;
        white-space: nowrap;
        text-align: center;
    }
    #ihpTable thead th:first-child { text-align: left; }

    #ihpTable tbody tr:hover td { background: #fffbf7; }
    #ihpTable tbody tr:not(:last-child) td { border-bottom: 0.5px solid #f3f4f6; }
    #ihpTable tbody td {
        padding: 8px 12px;
        color: #374151;
        vertical-align: middle;
        border-right: 0.5px solid #f3f4f6;
    }
    #ihpTable tbody td.td-name { font-weight: 500; color: #111827; min-width: 220px; }
    #ihpTable tbody td.td-name.fw-bold { color: #111827; background: #f9fafb; }
    #ihpTable tbody td.bg-light { background: #f9fafb !important; }

    /* ── Table inputs ── */
    #ihpTable input.form-control {
        height: 32px;
        padding: 0 8px;
        font-size: 12px;
        font-family: 'Figtree', sans-serif;
        border: 0.5px solid #e5e7eb;
        border-radius: 6px;
        background: #fff;
        color: #111827;
        width: 100%;
        min-width: 100px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        text-align: right;
    }
    #ihpTable input.form-control:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 2px rgba(249,115,22,0.1);
    }
    #ihpTable input.form-control::placeholder { color: #d1d5db; }

    /* ── Year column header ── */
    .year-col-head {
        background: #fff7ed !important;
        color: #c2410c !important;
        border-bottom: 2px solid #fed7aa !important;
    }

    /* ── Empty state ── */
    .empty-state {
        padding: 56px 20px;
        text-align: center;
        color: #9ca3af;
    }
    .empty-state svg { margin-bottom: 14px; color: #e5e7eb; }
    .empty-state h6 { font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 4px; }
    .empty-state p  { font-size: 13px; margin: 0; }

    /* ── Modal ── */
    .modal-content {
        border-radius: 12px !important;
        border: 0.5px solid #e5e7eb !important;
        font-family: 'Figtree', sans-serif !important;
    }
    .modal-header {
        padding: 16px 20px !important;
        border-bottom: 0.5px solid #e5e7eb !important;
        background: #fafafa !important;
    }
    .modal-title { font-size: 14px !important; font-weight: 600 !important; color: #111827 !important; }
    .modal-body  { padding: 20px !important; }
    .modal-footer {
        padding: 12px 20px !important;
        border-top: 0.5px solid #e5e7eb !important;
        background: #fafafa !important;
        gap: 8px !important;
    }
    .modal-label {
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 5px;
        display: block;
    }
    .modal-input, .modal-select {
        width: 100%;
        height: 36px;
        padding: 0 10px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #374151;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.15s;
        background: #fff;
    }
    .modal-input:focus, .modal-select:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.08);
    }
    .modal-textarea {
        width: 100%;
        padding: 8px 10px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #374151;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        resize: vertical;
        min-height: 72px;
        transition: border-color 0.15s;
    }
    .modal-textarea:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.08);
    }
    .modal-group { margin-bottom: 14px; }
    .modal-grid  { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .modal-divider { height: 1px; background: #f3f4f6; margin: 16px 0; }

    /* Select2 overrides */
    .select2-container--default .select2-selection--multiple {
        border: 0.5px solid #d1d5db !important;
        border-radius: 8px !important;
        min-height: 38px !important;
        font-family: 'Figtree', sans-serif !important;
        padding: 2px 6px !important;
    }
    .select2-container--default .select2-selection__choice {
        background: #fff7ed !important;
        border: 0.5px solid #fed7aa !important;
        color: #c2410c !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
        padding: 2px 8px !important;
        font-family: 'Figtree', sans-serif !important;
    }
    .select2-container--default .select2-selection__choice__remove { color: #f97316 !important; }
    .select2-dropdown {
        border: 0.5px solid #e5e7eb !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
        font-family: 'Figtree', sans-serif !important;
        font-size: 13px !important;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page">

    <h3 style="font-family:'Figtree',sans-serif; font-size:20px; font-weight:600; color:#111827; margin-bottom:4px;">Input Indeks Harga Produsen (IHP)</h3>
    <p style="font-size:13px; color:#9ca3af; margin-bottom:16px; font-family:'Figtree',sans-serif;">
        Pilih komoditas untuk menampilkan seluruh tabel turunannya.
    </p>

    <div class="notice-bar">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
        </svg>
        IHP menggunakan tahun dasar <strong>2010 = 100</strong>
    </div>

    <!-- Filter Komoditas -->
    <div class="card">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                <span class="card-head-title">Filter Komoditas</span>
            </div>
            <div class="card-head-right">
                <button id="downloadDoc" class="btn-base btn-secondary-o">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Template
                </button>
                <button id="importData" class="btn-base btn-ghost-blue">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Import
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Komoditas</label>
                    <select id="level1" class="filter-select">
                        <option value="">— Pilih Komoditas —</option>
                        @foreach($commodities as $c)
                        <option value="{{ $c->id }}">{{ $c->kode }} - {{ $c->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card" style="margin-bottom:0;">

        <div class="card-head" id="table-actions" style="display:none;">
            <div class="card-head-left">
                <svg class="card-head-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/>
                </svg>
                <span class="card-head-title">IHP per Komoditas</span>
            </div>
            <div class="card-head-right">
                <div class="year-filter-wrap">
                    <span class="year-filter-label">Periode:</span>
                    <div style="min-width:220px;">
                        <select id="yearFilter" multiple="multiple" style="width:100%;"></select>
                    </div>
                </div>
                <button id="addYear" class="btn-base btn-ghost-green">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tahun
                </button>
                <button id="addCommodity" class="btn-base btn-ghost-amber">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Komoditas
                </button>
                <button id="uploadDoc" class="btn-base btn-ghost-blue">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Dokumen
                </button>
                <button id="saveAll" class="btn-base btn-save">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </div>

        <div id="table-container" style="display:none;">
            <div class="table-wrap">
                <table id="ihpTable">
                    <thead>
                        <tr>
                            <th style="min-width:260px; text-align:left;">Komoditas</th>
                            <th style="width:160px;">Indikator</th>
                            <th style="width:120px;">Sat. Harga</th>
                            <th style="width:140px;">Sat. Produksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div id="empty-table-state">
            <div class="empty-state">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/>
                </svg>
                <h6>Belum Ada Komoditas Dipilih</h6>
                <p>Pilih <strong>komoditas</strong> di atas untuk menampilkan tabel IHP.</p>
            </div>
        </div>

    </div>
</div>

<!-- Modal Tambah Komoditas -->
<div class="modal fade" id="commodityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Komoditas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-group">
                    <label class="modal-label">Parent Komoditas</label>
                    <select id="parentSelect" class="modal-select">
                        <option value="">— Root (tanpa parent) —</option>
                    </select>
                </div>
                <div class="modal-grid">
                    <div class="modal-group">
                        <label class="modal-label">Kode (otomatis)</label>
                        <input type="text" id="newKode" class="modal-input" readonly style="background:#f9fafb; color:#6b7280;">
                    </div>
                    <div class="modal-group">
                        <label class="modal-label">Nama</label>
                        <input type="text" id="newNama" class="modal-input" placeholder="Nama komoditas">
                    </div>
                </div>
                <div class="modal-divider"></div>
                <div class="modal-grid">
                    <div class="modal-group">
                        <label class="modal-label">Indikator</label>
                        <select id="newIndikator" class="modal-select"></select>
                    </div>
                    <div class="modal-group">
                        <label class="modal-label">Satuan Harga</label>
                        <select id="newSatuanHarga" class="modal-select"></select>
                    </div>
                    <div class="modal-group">
                        <label class="modal-label">Satuan Produksi</label>
                        <select id="newSatuanProduksi" class="modal-select"></select>
                    </div>
                    <div class="modal-group">
                        <label class="modal-label">Satuan Luas Tanam</label>
                        <select id="newSatuanLuasTanam" class="modal-select"></select>
                    </div>
                    <div class="modal-group" style="grid-column:1/-1;">
                        <label class="modal-label">Satuan Biaya Perawatan</label>
                        <select id="newSatuanBiayaPerawatan" class="modal-select"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-base btn-secondary-o" data-bs-dismiss="modal">Batal</button>
                <button id="saveCommodity" class="btn-base btn-primary-o">Tambah Komoditas</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Dokumen -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Dokumen Pendukung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-group">
                    <label class="modal-label">Nama Dokumen</label>
                    <input type="text" id="docName" class="modal-input" placeholder="Nama dokumen">
                </div>
                <div class="modal-grid">
                    <div class="modal-group">
                        <label class="modal-label">Tahun</label>
                        <input type="number" id="docYear" class="modal-input" placeholder="2024">
                    </div>
                    <div class="modal-group">
                        <label class="modal-label">Triwulan</label>
                        <select id="docTriwulan" class="modal-select"></select>
                    </div>
                </div>
                <div class="modal-group">
                    <label class="modal-label">Jenis Dokumen</label>
                    <select id="docType" class="modal-select">
                        <option value="laporan">Laporan</option>
                        <option value="survey">Hasil Survei</option>
                        <option value="sensus">Hasil Sensus</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="modal-group">
                    <label class="modal-label">File</label>
                    <input type="file" id="docFile" class="modal-input" style="height:auto; padding:6px 10px; cursor:pointer;">
                </div>
                <div class="modal-group" style="margin-bottom:0;">
                    <label class="modal-label">Keterangan</label>
                    <textarea id="docDesc" class="modal-textarea" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-base btn-secondary-o" data-bs-dismiss="modal">Batal</button>
                <button id="saveDoc" class="btn-base btn-primary-o">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data IHP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-group" style="margin-bottom:6px;">
                    <label class="modal-label">File Excel / CSV</label>
                    <input type="file" id="importFile" class="modal-input" style="height:auto; padding:6px 10px; cursor:pointer;">
                </div>
                <p style="font-size:11.5px; color:#9ca3af; margin:0;">Format yang didukung: .xlsx, .xls, .csv</p>
            </div>
            <div class="modal-footer">
                <button class="btn-base btn-secondary-o" data-bs-dismiss="modal">Batal</button>
                <button id="submitImport" class="btn-base btn-primary-o">Import</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let currentRootId = null;

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(function () {
        let itemsData = [], allYears = [], selectedYears = [];

        function parseNumberID(formatted) {
            if (formatted === null || formatted === undefined || formatted === '') return null;
            let s = String(formatted).trim().replace(',', '.');
            const num = parseFloat(s);
            return isNaN(num) ? null : num;
        }

        function formatNumberID(value) {
            if (value === null || value === undefined || value === '') return '';
            const num = typeof value === 'number' ? value : parseNumberID(value);
            if (num === null) return '';
            return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 10 }).format(num);
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function setupDropdown(selector, endpoint, label, callback) {
            $.get(endpoint, function (res) {
                const $el = $(selector).empty().append(`<option value="">— Pilih ${label} —</option>`);
                res.forEach(callback);
                $el.append(`<option value="__new__">+ Tambah Baru...</option>`);
            });
        }

        $('#level1').on('change', function () {
            currentRootId = $(this).val();
            if (currentRootId) {
                loadSubtree(currentRootId);
            } else {
                $('#table-container').hide();
                $('#table-actions').hide();
                $('#empty-table-state').show();
            }
        });

        function loadSubtree(rootId) {
            const previouslySelectedYears = [...selectedYears];
            $.get('/ihp/commodities/' + rootId + '/subtree', function (res) {
                itemsData = res.commodities || [];
                allYears = (res.years || []).map(y => ({
                    year: parseInt(y.year || y),
                    triwulan_id: parseInt(y.triwulan_id ?? 0),
                    triwulan_name: y.triwulan_name || y.triwulan_nama || (y.triwulan_obj?.triwulan)
                }));

                const $yearFilter = $('#yearFilter').empty();
                allYears.forEach(y => {
                    const val = `${y.year}-${y.triwulan_id ?? 0}`;
                    const label = y.triwulan_name ? `${y.year} - ${y.triwulan_name}` : y.year;
                    $yearFilter.append(`<option value="${val}">${label}</option>`);
                });

                $yearFilter.select2({ placeholder: "Pilih Periode", allowClear: true, width: '100%', multiple: true });

                if (previouslySelectedYears.length > 0) {
                    selectedYears = allYears.filter(y =>
                        previouslySelectedYears.some(prev => prev.year === y.year && prev.triwulan_id === y.triwulan_id)
                    );
                    $yearFilter.val(selectedYears.map(y => `${y.year}-${y.triwulan_id ?? 0}`)).trigger('change');
                } else {
                    selectedYears = [];
                    $yearFilter.val(null).trigger('change');
                }

                buildTable();
                $('#empty-table-state').hide();
                $('#table-actions').show();
                $('#table-container').show();
            }).fail(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil data subtree.' }));
        }

        function buildTable() {
            // Clear thead dulu sebelum rebuild (fix bug header menumpuk)
            $('#ihpTable thead').empty();

            const $theadRow = $('<tr></tr>').html(`
                <th style="min-width:260px; text-align:left;">Komoditas</th>
                <th style="width:160px;">Indikator</th>
                <th style="width:120px;">Sat. Harga</th>
                <th style="width:140px;">Sat. Produksi</th>
            `);

            selectedYears.forEach(y => {
                const label = y.triwulan_name ? `${y.year} - ${y.triwulan_name}` : y.year;
                $theadRow.append(`<th class="text-center year-col-head">${label}</th>`);
            });

            $('#ihpTable thead').append($theadRow);

            const $tbody = $('#ihpTable tbody').empty();

            itemsData.forEach(it => {
                const isParent = it.is_parent;
                const tdClass = isParent ? 'fw-bold bg-light' : '';
                const displayName = isParent || !it.is_leaf
                    ? escapeHtml(it.kode + ' - ' + it.nama)
                    : escapeHtml(it.nama);
                const itemLevel = (it.kode.match(/\./g) || []).length;
                const isLeafNode = (it.is_leaf === true || it.is_leaf === 1) && itemLevel > 1;
                const indent = isParent ? '' : `style="padding-left:${itemLevel * 16}px;"`;

                let row = `<tr data-id="${it.id}">
                    <td class="td-name ${tdClass}" ${indent}>${displayName}</td>
                    <td class="${tdClass}" style="font-size:12px; color:#6b7280;">${it.indicator_name ?? ''}</td>
                    <td class="${tdClass}" style="font-size:12px; color:#6b7280;">${it.satuan_harga_name ?? ''}</td>
                    <td class="${tdClass}" style="font-size:12px; color:#6b7280;">${it.satuan_produksi_name ?? ''}</td>`;

                selectedYears.forEach(y => {
                    const triId = y.triwulan_id ?? 0;
                    const key = `${y.year}-${triId}`;
                    const ihpData = it.ihp?.[key] || {};
                    const ihpVal = ihpData.ihp ?? '';

                    if (isLeafNode) {
                        row += `
                            <td class="${tdClass}" style="padding:6px 8px;">
                                <input type="text" class="form-control ihp"
                                    data-year="${y.year}" data-triwulan="${triId}" data-id="${it.id}"
                                    data-raw="${ihpVal}" value="${formatNumberID(ihpVal)}" placeholder="IHP">
                            </td>`;
                    } else {
                        row += `<td class="${tdClass}" style="text-align:right; font-family:monospace; font-size:12px;">${formatNumberID(ihpVal)}</td>`;
                    }
                });

                row += '</tr>';
                $tbody.append(row);
            });

            attachFormatHandlers();
        }

        function attachFormatHandlers() {
            $('#ihpTable').off('focus blur input', 'input');
            $('#ihpTable').on('focus', 'input', function () {
                $(this).val($(this).data('raw') ?? '');
                $(this).select();
            }).on('blur', 'input', function () {
                const raw = parseNumberID($(this).val());
                $(this).data('raw', raw);
                $(this).val(formatNumberID(raw));
            }).on('input', 'input', function () {
                $(this).data('raw', parseNumberID($(this).val()));
            });
        }

        $('#yearFilter').on('change', function () {
            const selectedKeys = $(this).val() || [];
            selectedYears = allYears.filter(y => selectedKeys.includes(`${y.year}-${y.triwulan_id ?? 0}`));
            buildTable();
        });

        $('#addYear').on('click', function () {
            $.get('/ihp/triwulans').done(triwulans => {
                Swal.fire({
                    title: 'Tambah Periode',
                    html: `
                        <input id="swalYearInput" type="number" min="1900" max="2100" class="swal2-input" placeholder="Tahun">
                        <select id="swalTriwulanSelect" class="swal2-select">
                            <option value="">— Pilih Triwulan —</option>
                            ${triwulans.map(t => `<option value="${t.id}">${t.triwulan}</option>`).join('')}
                        </select>`,
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#e5e7eb',
                    confirmButtonText: 'Tambah',
                    cancelButtonText: 'Batal',
                    preConfirm: () => {
                        const newYear = parseInt($('#swalYearInput').val());
                        const triwulanId = parseInt($('#swalTriwulanSelect').val());
                        const triwulanName = $('#swalTriwulanSelect option:selected').text();
                        if (!newYear || !triwulanId) { Swal.showValidationMessage('Tahun dan Triwulan wajib diisi!'); return false; }
                        return { newYear, triwulanId, triwulanName };
                    }
                }).then(result => {
                    if (!result.isConfirmed) return;
                    const { newYear, triwulanId, triwulanName } = result.value;
                    if (allYears.some(y => y.year === newYear && y.triwulan_id === triwulanId)) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Periode sudah ada!' }); return;
                    }
                    const newYearObj = { year: newYear, triwulan_id: triwulanId, triwulan_name: triwulanName };
                    allYears.push(newYearObj);
                    selectedYears.push(newYearObj);
                    const optionValue = `${newYear}-${triwulanId}`;
                    $('#yearFilter').append(`<option value="${optionValue}">${newYear} - ${triwulanName}</option>`);
                    const current = $('#yearFilter').val() || [];
                    current.push(optionValue);
                    $('#yearFilter').val(current).trigger('change');
                    buildTable();
                });
            }).fail(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil data triwulan.' }));
        });

        $('#saveAll').on('click', function () {
            const payload = [];
            $('#ihpTable tbody tr').each(function () {
                const commodityId = $(this).data('id');
                if (!commodityId) return;
                $(this).find('input.ihp').each(function () {
                    const year = $(this).data('year');
                    const triwulanId = $(this).data('triwulan');
                    const ihp = $(this).data('raw');
                    if (ihp !== null && ihp !== undefined && ihp !== '') {
                        payload.push({ commodity_id: commodityId, tahun: year, triwulan_id: triwulanId, ihp: ihp || null });
                    }
                });
            });

            if (!payload.length) { Swal.fire({ icon: 'info', title: 'Info', text: 'Tidak ada data untuk disimpan.' }); return; }

            $.post('/ihp/bulk-store', { data: JSON.stringify(payload) })
                .done(() => {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Data berhasil disimpan.', timer: 2200, showConfirmButton: false });
                    if (currentRootId) loadSubtree(currentRootId);
                })
                .fail(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan data.' }));
        });

        $('#addCommodity').on('click', function () {
            $('#newNama, #newKode').val('');
            $('#parentSelect').empty().append('<option value="">— Root (tanpa parent) —</option>');
            $.get('/ihp/commodities/all', res => {
                res.forEach(c => $('#parentSelect').append(`<option value="${c.id}">${c.full_name}</option>`));
            });
            setupDropdown('#newIndikator', '/ihp/indicators', 'Indikator', i => $('#newIndikator').append(`<option value="${i.id}">${i.indikator}</option>`));
            setupDropdown('#newSatuanHarga', '/ihp/unit-harga', 'Satuan Harga', u => $('#newSatuanHarga').append(`<option value="${u.id}">${u.satuan_harga}</option>`));
            setupDropdown('#newSatuanProduksi', '/ihp/unit-produksi', 'Satuan Produksi', u => $('#newSatuanProduksi').append(`<option value="${u.id}">${u.satuan_produksi}</option>`));
            setupDropdown('#newSatuanLuasTanam', '/ihp/unit-luas', 'Satuan Luas Tanam', u => $('#newSatuanLuasTanam').append(`<option value="${u.id}">${u.satuan_luas_tanam}</option>`));
            setupDropdown('#newSatuanBiayaPerawatan', '/ihp/unit-perawatan', 'Satuan Biaya Perawatan', u => $('#newSatuanBiayaPerawatan').append(`<option value="${u.id}">${u.satuan_biaya_perawatan}</option>`));
            $('#commodityModal').modal('show');
        });

        ['#newIndikator','#newSatuanHarga','#newSatuanProduksi','#newSatuanLuasTanam','#newSatuanBiayaPerawatan'].forEach(selector => {
            $(selector).on('change', function () {
                if ($(this).val() !== '__new__') return;
                const typeMap = {
                    '#newIndikator': { title: 'Indikator', endpoint: '/ihp/indicators', field: 'indikator' },
                    '#newSatuanHarga': { title: 'Satuan Harga', endpoint: '/ihp/unit-harga', field: 'satuan_harga' },
                    '#newSatuanProduksi': { title: 'Satuan Produksi', endpoint: '/ihp/unit-produksi', field: 'satuan_produksi' },
                    '#newSatuanLuasTanam': { title: 'Satuan Luas Tanam', endpoint: '/ihp/unit-luas', field: 'satuan_luas_tanam' },
                    '#newSatuanBiayaPerawatan': { title: 'Satuan Biaya Perawatan', endpoint: '/ihp/unit-perawatan', field: 'satuan_biaya_perawatan' }
                };
                const config = typeMap[selector];
                $('#commodityModal').modal('hide');
                Swal.fire({ title: `Tambah ${config.title} Baru`, input: 'text', inputLabel: `Nama ${config.title}`, showCancelButton: true, confirmButtonColor: '#f97316' })
                    .then(res => {
                        $('#commodityModal').modal('show');
                        if (!res.isConfirmed || !res.value) { $(this).val(''); return; }
                        $.post(config.endpoint, { [config.field]: res.value }).done(data => {
                            $(selector).append(new Option(data[config.field], data.id, true, true)).trigger('change');
                        }).fail(() => Swal.fire({ icon: 'error', title: 'Error', text: `Gagal menambah ${config.title.toLowerCase()}.` }));
                    });
            });
        });

        $('#parentSelect').on('change', function () {
            const parentId = $(this).val();
            const endpoint = parentId ? `/ihp/commodities/${parentId}/next-code` : `/ihp/commodities/next-code`;
            $.get(endpoint, res => $('#newKode').val(res.new_code));
        });

        $('#saveCommodity').on('click', function () {
            const data = {
                parent_id: $('#parentSelect').val() || null,
                kode: $('#newKode').val(),
                nama: $('#newNama').val(),
                indikator_id: $('#newIndikator').val() || null,
                satuan_harga_id: $('#newSatuanHarga').val() || null,
                satuan_produksi_id: $('#newSatuanProduksi').val() || null,
                satuan_luas_id: $('#newSatuanLuasTanam').val() || null,
                satuan_perawatan_id: $('#newSatuanBiayaPerawatan').val() || null
            };
            if (!data.kode || !data.nama) { Swal.fire({ icon: 'error', title: 'Error', text: 'Kode dan nama wajib diisi.' }); return; }
            $.post('/ihp/commodities', data).done(() => {
                $('#commodityModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Komoditas berhasil ditambahkan.', timer: 2200, showConfirmButton: false });
                if (currentRootId) loadSubtree(currentRootId);
            }).fail(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menambah komoditas.' }));
        });

        $('#uploadDoc').on('click', function () {
            if (!currentRootId) { Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih komoditas terlebih dahulu!' }); return; }
            $('#docName, #docYear, #docDesc').val('');
            $('#docFile').val('');
            $.get('/prices-productions/triwulans', function (res) {
                let opt = '<option value="">— Pilih Triwulan —</option>';
                res.forEach(t => opt += `<option value="${t.id}">${t.triwulan}</option>`);
                $('#docTriwulan').html(opt);
            });
            $('#uploadModal').modal('show');
        });

        $('#saveDoc').on('click', function () {
            if (!$('#docName').val()) { Swal.fire({ icon: 'error', title: 'Error', text: 'Nama dokumen wajib diisi.' }); return; }
            if (!$('#docYear').val()) { Swal.fire({ icon: 'error', title: 'Error', text: 'Tahun wajib diisi.' }); return; }
            if (!$('#docFile')[0].files.length) { Swal.fire({ icon: 'error', title: 'Error', text: 'File wajib diupload.' }); return; }
            const file = $('#docFile')[0].files[0];
            if (file.size > 5 * 1024 * 1024) { Swal.fire({ icon: 'error', title: 'Error', text: 'Ukuran file maksimal 5MB.' }); return; }
            const formData = new FormData();
            formData.append('nama', $('#docName').val());
            formData.append('tahun', $('#docYear').val());
            formData.append('triwulan_id', $('#docTriwulan').val());
            formData.append('jenis', $('#docType').val());
            formData.append('keterangan', $('#docDesc').val());
            formData.append('file', file);
            formData.append('modul', 'ihp');
            formData.append('commodity_id', currentRootId);
            $.ajax({
                url: '/documents/upload', method: 'POST', data: formData, processData: false, contentType: false,
                success: () => { $('#uploadModal').modal('hide'); Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Dokumen berhasil diupload.', timer: 2200, showConfirmButton: false }); },
                error: () => Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal upload dokumen.' })
            });
        });
    });

    $('#downloadDoc').on('click', function () {
        fetch('/ihp/template')
            .then(r => r.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url; a.download = 'template_ihp.xlsx';
                document.body.appendChild(a); a.click(); a.remove();
                window.URL.revokeObjectURL(url);
            }).catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal download template.' }));
    });

    $('#importData').on('click', function () { $('#importModal').modal('show'); });

    $('#submitImport').on('click', function () {
        const file = $('#importFile')[0].files[0];
        if (!file) { Swal.fire({ icon: 'error', title: 'Error', text: 'File wajib dipilih.' }); return; }
        const formData = new FormData();
        formData.append('file', file);
        Swal.fire({ title: 'Mengimport...', text: 'Mohon tunggu sebentar.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        $.ajax({
            url: '/ihp/import', method: 'POST', data: formData, processData: false, contentType: false,
            success: function (res) {
                $('#importModal').modal('hide'); $('#importFile').val('');
                let html = `<b>${res.imported}</b> baris berhasil diimport.<br>`;
                if (res.skipped > 0) html += `<small style="color:#6b7280;">${res.skipped} baris dilewati.</small>`;
                if (res.warnings?.length) {
                    html += `<br><br><details><summary style="cursor:pointer;color:#e67e22;">⚠ ${res.warnings.length} peringatan</summary>
                        <ul style="text-align:left;font-size:12px;max-height:200px;overflow-y:auto;">${res.warnings.map(w => `<li>${w}</li>`).join('')}</ul></details>`;
                }
                Swal.fire({ icon: res.warnings?.length ? 'warning' : 'success', title: 'Import Selesai', html });
                if (currentRootId) loadSubtree(currentRootId);
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Gagal import data.';
                let errHtml = `<p>${msg}</p>`;
                if (xhr.responseJSON?.errors?.length) {
                    errHtml += `<ul style="text-align:left;font-size:13px;max-height:200px;overflow-y:auto;">${xhr.responseJSON.errors.map(e => `<li>${e}</li>`).join('')}</ul>`;
                }
                Swal.fire({ icon: 'error', title: 'Error', html: errHtml });
            }
        });
    });
</script>
@endsection