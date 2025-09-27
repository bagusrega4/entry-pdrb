@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Add CSRF token meta tag -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Input Harga dan Produksi Komoditas</h3>
                <h6 class="op-7 mb-2">Pilih komoditas -> seluruh tabel turunannya akan muncul.</h6>
            </div>
        </div>

        <!-- pilih level 1 -->
        <div class="mb-3">
            <label>Komoditas</label>
            <select id="level1" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach($commodities as $c)
                <option value="{{ $c->id }}">{{ $c->kode }} - {{ $c->nama }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Pilih root komoditas — akan menampilkan semua anaknya sebagai baris tabel.</small>
        </div>

        <!-- tabel spreadsheet -->
        <div id="table-container" style="display:none;" class="mt-3">
            <div class="d-flex justify-content-between mb-2">
                <h5>Daftar Harga dan Produksi per Komoditas</h5>
                <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                    <div style="flex:1; min-width:300px;">
                        <select id="yearFilter" multiple="multiple" style="width:100%;"></select>
                    </div>
                    <div class="d-flex gap-2">
                        <button id="addYear" class="btn btn-sm btn-success">Tambah Tahun</button>
                        <button id="addCommodity" class="btn btn-sm btn-warning">Tambah Komoditas</button>
                        <button id="saveAll" class="btn btn-primary">Simpan Semua</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="hargaTable">
                    <thead>
                        <tr>
                            <th style="min-width:260px;">Komoditas</th>
                            <th style="width:160px">Indikator</th>
                            <th style="width:120px">Satuan Harga</th>
                            <th style="width:140px">Satuan Produksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
                <div class="mb-2">
                    <label>Pilih Parent (boleh kosong untuk root)</label>
                    <select id="parentSelect" class="form-control">
                        <option value="">-- Root (tanpa parent) --</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Kode (otomatis)</label>
                    <input type="text" id="newKode" class="form-control" readonly>
                </div>
                <div class="mb-2">
                    <label>Nama</label>
                    <input type="text" id="newNama" class="form-control">
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label>Indikator</label>
                        <select id="newIndikator" class="form-control"></select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Satuan Harga</label>
                        <select id="newSatuanHarga" class="form-control"></select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Satuan Produksi</label>
                        <select id="newSatuanProduksi" class="form-control"></select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>Satuan Luas Tanam</label>
                        <select id="newSatuanLuasTanam" class="form-control"></select>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label>Satuan Biaya Perawatan</label>
                        <select id="newSatuanBiayaPerawatan" class="form-control"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="saveCommodity" class="btn btn-primary">Tambah</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<style>
    .select2-selection__choice {
        font-size: 14px !important;
        padding: 4px 8px !important;
    }

    #table-container .btn {
        white-space: nowrap;
        padding: 6px 12px;
        font-size: 14px;
    }

    #table-container .select2-container {
        width: 100% !important;
    }

    #table-container .select2-selection--multiple {
        min-height: 50px !important;
    }

    #yearFilter+.select2-container .select2-selection--multiple {
        min-height: 38px !important;
        height: 38px !important;
        display: flex;
        align-items: center;
    }

    #yearFilter+.select2-container .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 4px;
        margin: 0;
        padding: 0;
    }
</style>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        let itemsData = [],
            currentRootId = null,
            allYears = [],
            selectedYears = [];

        // --- parsing yang robust: menerima format ID/EN ---
        function parseNumberID(formatted) {
            if (formatted === null || formatted === undefined || formatted === '') return null;
            
            // Remove currency symbols and extra spaces
            let s = String(formatted).trim()
                .replace(/Rp\s*/i, '')
                .replace(/\s+/g, '');
            
            // Handle Indonesian number format (dot as thousands separator, comma as decimal)
            // But also handle international format
            const hasDot = s.includes('.');
            const hasComma = s.includes(',');
            
            if (hasDot && hasComma) {
                // Both dot and comma present
                const lastDotIndex = s.lastIndexOf('.');
                const lastCommaIndex = s.lastIndexOf(',');
                
                if (lastCommaIndex > lastDotIndex) {
                    // Format: 1.234.567,89 (Indonesian)
                    s = s.replace(/\./g, '').replace(',', '.');
                } else {
                    // Format: 1,234,567.89 (International)
                    s = s.replace(/,/g, '');
                }
            } else if (hasComma) {
                // Only comma present
                const commaCount = (s.match(/,/g) || []).length;
                if (commaCount === 1) {
                    // Check if it's decimal (less than 3 digits after comma)
                    const afterComma = s.split(',')[1];
                    if (afterComma && afterComma.length <= 3) {
                        s = s.replace(',', '.'); // Decimal separator
                    } else {
                        s = s.replace(/,/g, ''); // Thousands separator
                    }
                } else {
                    // Multiple commas = thousands separators
                    s = s.replace(/,/g, '');
                }
            } else if (hasDot) {
                // Only dot present
                const dotCount = (s.match(/\./g) || []).length;
                if (dotCount === 1) {
                    // Check if it's likely a thousands separator (3 digits after dot)
                    const afterDot = s.split('.')[1];
                    if (afterDot && afterDot.length === 3 && !afterDot.includes('0')) {
                        // Likely thousands separator like 1.000
                        s = s.replace(/\./g, '');
                    }
                    // Otherwise keep as decimal
                } else {
                    // Multiple dots = thousands separators
                    s = s.replace(/\./g, '');
                }
            }
            
            // Remove any remaining non-numeric characters except decimal point and minus
            s = s.replace(/[^0-9.\-]/g, '');
            
            const num = parseFloat(s);
            return isNaN(num) ? null : num;
        }

        function formatNumberID(value, isCurrency = false) {
            if (value == null || value === '') return '';
            const num = typeof value === 'number' ? value : parseNumberID(value);
            if (num === null) return '';
            const formatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 10
            }).format(num);
            return isCurrency ? 'Rp ' + formatted : formatted;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ambil semua indikator dan unit yang diperlukan
        function fetchIndicatorsAndUnits() {
            return $.when(
                $.get('/prices-productions/indicators').done(data => indikatorOptions = data || []),
                $.get('/prices-productions/unit-harga').done(data => unitHargaOptions = data || []),
                $.get('/prices-productions/unit-produksi').done(data => unitProduksiOptions = data || []),
                $.get('/prices-productions/unit-luas').done(data => unitLuasOptions = data || []),
                $.get('/prices-productions/unit-perawatan').done(data => unitPerawatanOptions = data || [])
            ).fail(() => Swal.fire('Error', 'Gagal mengambil indikator / satuan', 'error'));
        }

        $('#level1').on('change', function() {
            currentRootId = $(this).val();
            if (currentRootId) {
                $.when(
                    $.get('/prices-productions/indicators'),
                    $.get('/prices-productions/unit-harga'),
                    $.get('/prices-productions/unit-produksi'),
                    $.get('/prices-productions/unit-luas'),
                    $.get('/prices-productions/unit-perawatan')
                ).then(() => loadSubtree(currentRootId));
            } else {
                $('#table-container').hide();
            }
        });

        function loadSubtree(rootId) {
            const previouslySelectedYears = [...selectedYears];

            $.get('/prices-productions/commodities/' + rootId + '/subtree', function(res) {
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

                $yearFilter.select2({
                    placeholder: "Pilih Tahun",
                    allowClear: true,
                    width: '250px',
                    multiple: true
                });

                if (previouslySelectedYears.length > 0) {
                    selectedYears = allYears.filter(y =>
                        previouslySelectedYears.some(prev =>
                            prev.year === y.year && prev.triwulan_id === y.triwulan_id
                        )
                    );

                    const selectedValues = selectedYears.map(y => `${y.year}-${y.triwulan_id ?? 0}`);
                    $yearFilter.val(selectedValues).trigger('change');
                } else {
                    selectedYears = [];
                    $yearFilter.val(null).trigger('change');
                }

                buildTable();
                $('#table-container').show();
            }).fail(() => Swal.fire('Error', 'Gagal mengambil data subtree', 'error'));
        }

        function buildTable() {
            const items = itemsData;

            let $theadRow = $('#hargaTable thead tr').empty();

            $theadRow.html(`
                <th style="min-width:260px;">Komoditas</th>
                <th style="width:160px">Indikator</th>
                <th style="width:120px">Satuan Harga</th>
                <th style="width:140px">Satuan Produksi</th>
            `);

            selectedYears.forEach(y => {
                const label = y.triwulan_name ? `${y.year} - ${y.triwulan_name}` : y.year;
                $theadRow.append(`<th colspan="2" class="text-center">${label}</th>`);
            });

            // Tambahkan sub-header untuk Harga dan Produksi
            const $subheadRow = $('<tr></tr>');
            $subheadRow.html(`
                <th rowspan="2"></th>
                <th rowspan="2"></th>
                <th rowspan="2"></th>
                <th rowspan="2"></th>
            `);
            
            selectedYears.forEach(y => {
                $subheadRow.append(`
                    <th class="text-center bg-light">Harga</th>
                    <th class="text-center bg-light">Produksi</th>
                `);
            });

            // Hapus header lama dan tambahkan yang baru dengan sub-header
            $('#hargaTable thead').empty().append($theadRow);

            const $tbody = $('#hargaTable tbody').empty();

            items.forEach(it => {
                const tdClass = it.is_parent ? 'fw-bold bg-light' : '';
                const displayName = it.is_parent || !it.is_leaf ?
                    escapeHtml(it.kode + ' - ' + it.nama) :
                    escapeHtml(it.nama);

                let row = `<tr data-id="${it.id}">
                    <td class="${tdClass}">${displayName}</td>
                    <td class="${tdClass}">${it.indicator_name ?? ''}</td>
                    <td class="${tdClass}">${it.satuan_harga_name ?? ''}</td>
                    <td class="${tdClass}">${it.satuan_produksi_name ?? ''}</td>`;

                selectedYears.forEach(y => {
                    const triId = y.triwulan_id ?? 0;
                    const key = `${y.year}-${triId}`;

                    // Ambil data dari response API
                    const priceEntry = it.prices?.[key];
                    const prodEntry = it.productions?.[key];

                    // Extract raw values
                    const hargaVal = priceEntry?.harga ?? priceEntry ?? '';
                    const produksiVal = prodEntry?.produksi ?? prodEntry ?? '';

                    row += `
                        <td class="${tdClass}">
                            <input type="text" class="form-control harga text-end"
                                data-year="${y.year}" data-triwulan="${triId}" data-id="${it.id}"
                                data-raw="${hargaVal}"
                                value="${formatNumberID(hargaVal, true)}" placeholder="Harga">
                        </td>
                        <td class="${tdClass}">
                            <input type="text" class="form-control produksi text-end"
                                data-year="${y.year}" data-triwulan="${triId}" data-id="${it.id}"
                                data-raw="${produksiVal}"
                                value="${formatNumberID(produksiVal)}" placeholder="Produksi">
                        </td>`;
                });

                row += '</tr>';
                $tbody.append(row);
            });

            attachFormatHandlers();
        }

        function attachFormatHandlers() {
            $('#hargaTable').off('focus blur input', 'input');
            $('#hargaTable').on('focus', 'input', function() {
                const raw = $(this).data('raw');
                $(this).val(raw ?? '');
                $(this).select();
            }).on('blur', 'input', function() {
                const raw = parseNumberID($(this).val());
                $(this).data('raw', raw);
                const isHarga = $(this).hasClass('harga');
                $(this).val(formatNumberID(raw, isHarga));
            }).on('input', 'input', function() {
                const raw = parseNumberID($(this).val());
                $(this).data('raw', raw);
            });
        }

        function setupDropdown(selector, endpoint, label, callback) {
            $.get(endpoint, function(res) {
                const $el = $(selector).empty().append(`<option value="">-- Pilih ${label} --</option>`);
                res.forEach(callback);
                $el.append(`<option value="__new__">+ Tambah Baru...</option>`);
            });
        }

        $('#addYear').on('click', function() {
            $.get('/prices-productions/triwulans').done(triwulans => {
                Swal.fire({
                    title: 'Tambah Tahun',
                    html: `
                        <input id="swalYearInput" type="number" min="1900" max="2100" 
                            class="swal2-input" placeholder="Masukkan Tahun">
                        <select id="swalTriwulanSelect" class="swal2-select">
                            <option value="">-- Pilih Triwulan --</option>
                            ${triwulans.map(t => `<option value="${t.id}">${t.triwulan}</option>`).join('')}
                        </select>`,
                    showCancelButton: true,
                    preConfirm: () => {
                        const newYear = parseInt($('#swalYearInput').val());
                        const triwulanId = parseInt($('#swalTriwulanSelect').val());
                        const triwulanName = $('#swalTriwulanSelect option:selected').text();

                        if (!newYear || !triwulanId) {
                            Swal.showValidationMessage('Tahun dan Triwulan wajib diisi!');
                            return false;
                        }
                        return {
                            newYear,
                            triwulanId,
                            triwulanName
                        };
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        const {
                            newYear,
                            triwulanId,
                            triwulanName
                        } = result.value;

                        // Cek apakah kombinasi tahun+triwulan sudah ada
                        if (allYears.some(y => y.year === newYear && y.triwulan_id === triwulanId)) {
                            Swal.fire('Gagal', 'Tahun & triwulan sudah ada!', 'error');
                            return;
                        }

                        const newYearObj = {
                            year: newYear,
                            triwulan_id: triwulanId,
                            triwulan_name: triwulanName
                        };

                        // Tambahkan ke allYears
                        allYears.push(newYearObj);

                        // Tambahkan ke selectedYears untuk langsung dipilih
                        selectedYears.push(newYearObj);

                        // Update dropdown dengan option yang baru
                        const optionValue = `${newYear}-${triwulanId}`;
                        const optionLabel = `${newYear} - ${triwulanName}`;
                        $('#yearFilter').append(`<option value="${optionValue}">${optionLabel}</option>`);

                        // Set nilai yang dipilih (termasuk yang sudah ada sebelumnya + yang baru)
                        const currentSelected = $('#yearFilter').val() || [];
                        currentSelected.push(optionValue);
                        $('#yearFilter').val(currentSelected).trigger('change');

                        // Rebuild tabel dengan kolom baru
                        buildTable();
                        Swal.fire('Berhasil', 'Tahun berhasil ditambahkan!', 'success');
                    }
                });
            }).fail(() => Swal.fire('Error', 'Gagal mengambil data triwulan', 'error'));
        });

        $('#yearFilter').on('change', function() {
            const selectedKeys = $(this).val() || [];
            selectedYears = allYears.filter(y =>
                selectedKeys.includes(`${y.year}-${y.triwulan_id ?? 0}`)
            );
            buildTable();
        });

        $('#saveAll').on('click', function() {
            const payload = [];

            $('#hargaTable tbody tr').each(function() {
                const commodityId = $(this).data('id');
                if (!commodityId) return;

                const inputData = {};
                
                // Kumpulkan data per tahun-triwulan
                $(this).find('input.harga, input.produksi').each(function() {
                    const year = $(this).data('year');
                    const triwulanId = $(this).data('triwulan');
                    const key = `${year}-${triwulanId}`;
                    
                    if (!inputData[key]) {
                        inputData[key] = {
                            commodity_id: commodityId,
                            tahun: year,
                            triwulan_id: triwulanId
                        };
                    }
                    
                    const rawValue = $(this).data('raw');
                    if ($(this).hasClass('harga')) {
                        inputData[key].harga = rawValue;
                    } else if ($(this).hasClass('produksi')) {
                        inputData[key].produksi = rawValue;
                    }
                });

                // Tambahkan ke payload jika ada data yang tidak kosong
                Object.values(inputData).forEach(data => {
                    if (data.harga !== null && data.harga !== undefined && data.harga !== '' ||
                        data.produksi !== null && data.produksi !== undefined && data.produksi !== '') {
                        payload.push(data);
                    }
                });
            });

            if (!payload.length) {
                Swal.fire('Info', 'Tidak ada data untuk disimpan', 'info');
                return;
            }

            console.log('Payload yang akan dikirim:', payload);

            $.post('/prices-productions/bulk-store', {
                data: JSON.stringify(payload)
            }).done(() => {
                Swal.fire('Sukses', 'Data berhasil disimpan', 'success');
                // Reload data dengan mempertahankan selectedYears
                if (currentRootId) {
                    loadSubtree(currentRootId);
                }
            }).fail(xhr => {
                console.error('Error response:', xhr.responseText);
                Swal.fire('Error', 'Gagal menyimpan data', 'error');
            });
        });

        $('#addCommodity').on('click', function() {
            $('#newNama, #newKode').val('');
            $('#parentSelect').empty().append('<option value="">-- Root (tanpa parent) --</option>');

            $.get('/prices-productions/commodities/all', res => {
                res.forEach(c => $('#parentSelect').append(`<option value="${c.id}">${c.full_name}</option>`));
            });

            setupDropdown('#newIndikator', '/prices-productions/indicators', 'Indikator',
                i => $('#newIndikator').append(`<option value="${i.id}">${i.indikator}</option>`));
            setupDropdown('#newSatuanHarga', '/prices-productions/unit-harga', 'Satuan Harga',
                u => $('#newSatuanHarga').append(`<option value="${u.id}">${u.satuan_harga}</option>`));
            setupDropdown('#newSatuanProduksi', '/prices-productions/unit-produksi', 'Satuan Produksi',
                u => $('#newSatuanProduksi').append(`<option value="${u.id}">${u.satuan_produksi}</option>`));
            setupDropdown('#newSatuanLuasTanam', '/prices-productions/unit-luas', 'Satuan Luas Tanam',
                u => $('#newSatuanLuasTanam').append(`<option value="${u.id}">${u.satuan_luas_tanam}</option>`));
            setupDropdown('#newSatuanBiayaPerawatan', '/prices-productions/unit-perawatan', 'Satuan Biaya Perawatan',
                u => $('#newSatuanBiayaPerawatan').append(`<option value="${u.id}">${u.satuan_biaya_perawatan}</option>`));

            $('#commodityModal').modal('show');
        });

        ['#newIndikator', '#newSatuanHarga', '#newSatuanProduksi', '#newSatuanLuasTanam', '#newSatuanBiayaPerawatan'].forEach(selector => {
            $(selector).on('change', function() {
                if ($(this).val() !== '__new__') return;

                const typeMap = {
                    '#newIndikator': {
                        title: 'Indikator',
                        endpoint: '/prices-productions/indicators',
                        field: 'indikator'
                    },
                    '#newSatuanHarga': {
                        title: 'Satuan Harga',
                        endpoint: '/prices-productions/unit-harga',
                        field: 'satuan_harga'
                    },
                    '#newSatuanProduksi': {
                        title: 'Satuan Produksi',
                        endpoint: '/prices-productions/unit-produksi',
                        field: 'satuan_produksi'
                    },
                    '#newSatuanLuasTanam': {
                        title: 'Satuan Luas Tanam',
                        endpoint: '/prices-productions/unit-luas',
                        field: 'satuan_luas_tanam'
                    },
                    '#newSatuanBiayaPerawatan': {
                        title: 'Satuan Biaya Perawatan',
                        endpoint: '/prices-productions/unit-perawatan',
                        field: 'satuan_biaya_perawatan'
                    }
                };

                const config = typeMap[selector];
                $('#commodityModal').modal('hide');

                Swal.fire({
                    title: `Tambah ${config.title} Baru`,
                    input: 'text',
                    inputLabel: `Nama ${config.title}`,
                    showCancelButton: true
                }).then(res => {
                    $('#commodityModal').modal('show');
                    if (!res.isConfirmed || !res.value) {
                        $(this).val('');
                        return;
                    }

                    const postData = {
                        [config.field]: res.value
                    };
                    $.post(config.endpoint, postData).done(data => {
                        const newOpt = new Option(data[config.field], data.id, true, true);
                        $(selector).append(newOpt).trigger('change');
                    }).fail(() => Swal.fire('Error', `Gagal menambah ${config.title.toLowerCase()}`, 'error'));
                });
            });
        });

        $('#parentSelect').on('change', function() {
            const parentId = $(this).val();
            const endpoint = parentId ?
                `/prices-productions/commodities/${parentId}/next-code` :
                `/prices-productions/commodities/next-code`;
            $.get(endpoint, res => $('#newKode').val(res.new_code));
        });

        $('#saveCommodity').on('click', function() {
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

            if (!data.kode || !data.nama) {
                Swal.fire('Error', 'Kode dan nama wajib diisi', 'error');
                return;
            }

            $.post('/prices-productions/commodities', data).done(() => {
                $('#commodityModal').modal('hide');
                Swal.fire('Sukses', 'Komoditas berhasil ditambahkan', 'success');
                // Reload data dengan mempertahankan selectedYears
                if (currentRootId) {
                    loadSubtree(currentRootId);
                }
            }).fail(() => Swal.fire('Error', 'Gagal menambah komoditas', 'error'));
        });
    });
</script>
@endsection