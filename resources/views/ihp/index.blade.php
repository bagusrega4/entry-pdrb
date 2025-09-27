@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Add CSRF token meta tag -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Input Indeks Harga Produsen</h3>
                <h6 class="op-7 mb-2">Pilih komoditas -> seluruh tabel turunannya akan muncul.</h6>
                <h6 class="op-7 mb-2">*Perhatian: IHP 2010 = 100</h6>
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
                <h5>Daftar IHP per Komoditas</h5>
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
                <table class="table table-bordered" id="ihpTable">
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
    <div class="modal-dialog">
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
                <div class="mb-2">
                    <label>Indikator</label>
                    <select id="newIndikator" class="form-control"></select>
                </div>
                <div class="mb-2">
                    <label>Satuan Harga</label>
                    <select id="newSatuanHarga" class="form-control"></select>
                </div>
                <div class="mb-2">
                    <label>Satuan Produksi</label>
                    <select id="newSatuanProduksi" class="form-control"></select>
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
            let s = String(formatted).trim().replace(',', '.');
            const num = parseFloat(s);
            return isNaN(num) ? null : num;
        }

        function formatNumberID(value) {
            if (value === null || value === undefined || value === '') return '';
            let num = (typeof value === 'number') ? value : parseNumberID(value);
            if (num === null) return '';
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 10
            }).format(num);
        }

        // ambil indikator, unit harga, dan unit produksi
        function fetchIndicatorsAndUnits() {
            return $.when(
                $.get('/ihp/indicators').done(data => indikatorOptions = data || []),
                $.get('/ihp/unit-harga').done(data => unitHargaOptions = data || []),
                $.get('/ihp/unit-produksi').done(data => unitProduksiOptions = data || [])
            ).fail(() => Swal.fire('Error', 'Gagal mengambil indikator / satuan', 'error'));
        }

        $('#level1').on('change', function() {
            currentRootId = $(this).val();
            if (currentRootId) {
                $.when(
                    $.get('/ihp/indicators'),
                    $.get('/ihp/unit-harga'),
                    $.get('/ihp/unit-produksi')
                ).then(() => loadSubtree(currentRootId));
            } else {
                $('#table-container').hide();
            }
        });

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function loadSubtree(rootId) {
            const previouslySelectedYears = [...selectedYears];

            $.get('/ihp/commodities/' + rootId + '/subtree', function(res) {
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

            let $theadRow = $('#ihpTable thead tr').empty();

            $theadRow.html(`
                <th style="min-width:260px;">Komoditas</th>
                <th style="width:160px">Indikator</th>
                <th style="width:120px">Satuan Harga</th>
                <th style="width:140px">Satuan Produksi</th>
            `);

            selectedYears.forEach(y => {
                const label = y.triwulan_name ? `${y.year} - ${y.triwulan_name}` : y.year;
                $theadRow.append(`<th class="text-center">${label}</th>`);
            });

            const $tbody = $('#ihpTable tbody').empty();

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
                    const ihpData = it.ihp?.[key] || {};
                    const ihpVal = ihpData.ihp ?? '';

                    row += `
                        <td class="${tdClass}">
                            <input type="text" class="form-control ihp text-end"
                                data-year="${y.year}" data-triwulan="${triId}" data-id="${it.id}"
                                data-raw="${ihpVal}"
                                value="${formatNumberID(ihpVal)}" placeholder="Input IHP">
                        </td>`;
                });

                row += '</tr>';
                $tbody.append(row);
            });

            attachFormatHandlers();
        }

        function attachFormatHandlers() {
            $('#ihpTable').off('focus blur input', 'input');
            $('#ihpTable').on('focus', 'input', function() {
                const raw = $(this).data('raw');
                $(this).val(raw ?? '');
                $(this).select();
            }).on('blur', 'input', function() {
                const raw = parseNumberID($(this).val());
                $(this).data('raw', raw);
                $(this).val(formatNumberID(raw));
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
            $.get('/ihp/triwulans').done(triwulans => {
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

            $('#ihpTable tbody tr').each(function() {
                const commodityId = $(this).data('id');
                if (!commodityId) return;

                $(this).find('input.ihp').each(function() {
                    const year = $(this).data('year');
                    const triwulanId = $(this).data('triwulan');
                    const ihp = $(this).data('raw');

                    if (ihp !== null && ihp !== undefined && ihp !== '') {
                        payload.push({
                            commodity_id: commodityId,
                            tahun: year,
                            triwulan_id: triwulanId,
                            ihp: ihp || null
                        });
                    }
                });
            });

            if (!payload.length) {
                Swal.fire('Info', 'Tidak ada data untuk disimpan', 'info');
                return;
            }

            console.log('Payload yang akan dikirim:', payload);

            $.post('/ihp/bulk-store', {
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

            $.get('/ihp/commodities/all', res => {
                res.forEach(c => $('#parentSelect').append(`<option value="${c.id}">${c.full_name}</option>`));
            });

            setupDropdown('#newIndikator', '/ihp/indicators', 'Indikator',
                i => $('#newIndikator').append(`<option value="${i.id}">${i.indikator}</option>`));
            setupDropdown('#newSatuanHarga', '/ihp/unit-harga', 'Satuan Harga',
                u => $('#newSatuanHarga').append(`<option value="${u.id}">${u.satuan_harga}</option>`));
            setupDropdown('#newSatuanProduksi', '/ihp/unit-produksi', 'Satuan Produksi',
                u => $('#newSatuanProduksi').append(`<option value="${u.id}">${u.satuan_produksi}</option>`));

            $('#commodityModal').modal('show');
        });

        ['#newIndikator', '#newSatuanHarga', '#newSatuanProduksi'].forEach(selector => {
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
                `/ihp/commodities/${parentId}/next-code` :
                `/ihp/commodities/next-code`;
            $.get(endpoint, res => $('#newKode').val(res.new_code));
        });

        $('#saveCommodity').on('click', function() {
            const data = {
                parent_id: $('#parentSelect').val() || null,
                kode: $('#newKode').val(),
                nama: $('#newNama').val(),
                indikator_id: $('#newIndikator').val() || null,
                satuan_harga_id: $('#newSatuanHarga').val() || null,
                satuan_produksi_id: $('#newSatuanProduksi').val() || null
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