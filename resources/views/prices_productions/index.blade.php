@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
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
                        <button id="addYear" class="btn btn-success">Tambah Tahun</button>
                        <button id="addCommodity" class="btn btn-warning">Tambah Komoditas</button>
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

    #table-container .btn {
        white-space: nowrap;
        font-size: 14px;
        padding: 6px 12px;
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
$(function() {
    let itemsData = [], currentRootId = null, allYears = [], selectedYears = [];

    // Utility functions
    function parseNumberID(formatted) {
        if (!formatted) return null;
        let s = String(formatted).trim()
            .replace(/Rp\s*/i, '').replace(/\s+/g, '');
        
        const hasDot = s.includes('.');
        const hasComma = s.includes(',');
        
        if (hasDot && hasComma) {
            s = s.lastIndexOf(',') > s.lastIndexOf('.') 
                ? s.replace(/\./g, '').replace(',', '.')
                : s.replace(/,/g, '');
        } else if (hasComma) {
            s = (s.match(/,/g) || []).length > 1 
                ? s.replace(/,/g, '') 
                : s.replace(',', '.');
        } else if (hasDot) {
            const parts = s.split('.');
            if (parts.length === 2 && parts[1].length === 3 && parts[0].length > 0) {
                s = s.replace(/\./g, '');
            }
        }
        
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

    // Main functions
    function loadSubtree(rootId) {
        $.get('/prices-productions/commodities/' + rootId + '/subtree', function(res) {
            itemsData = res.commodities || [];
            
            allYears = (res.years || []).map(y => ({
                year: parseInt(y.year || y),
                triwulan_id: y.triwulan_id ?? 0,
                triwulan_name: y.triwulan_name || y.triwulan_nama || (y.triwulan_obj?.triwulan)
            }));
            
            // Setup year filter
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
            }).val(null).trigger('change');
            
            selectedYears = [];
            buildTable();
            $('#table-container').show();
        }).fail(() => Swal.fire('Error', 'Gagal mengambil data subtree', 'error'));
    }

    function buildTable() {
        const $theadRow = $('#hargaTable thead tr').empty();
        
        // Headers
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
        
        const $tbody = $('#hargaTable tbody').empty();
        
        itemsData.forEach(it => {
            const tdClass = it.is_parent ? 'fw-bold bg-light' : '';
            const displayName = it.is_parent || !it.is_leaf 
                ? escapeHtml(it.kode + ' - ' + it.nama) 
                : escapeHtml(it.nama);
            
            let row = `<tr data-id="${it.id}">
                <td class="${tdClass}">${displayName}</td>
                <td class="${tdClass}">${it.indicator_name ?? ''}</td>
                <td class="${tdClass}">${it.satuan_harga_name ?? ''}</td>
                <td class="${tdClass}">${it.satuan_produksi_name ?? ''}</td>`;
            
            selectedYears.forEach(y => {
                const triId = y.triwulan_id ?? 0;
                const key = `${y.year}-${triId}`;
                
                const priceEntry = it.prices?.[key];
                const prodEntry = it.productions?.[key];
                
                const hargaVal = priceEntry?.harga ?? priceEntry ?? '';
                const produksiVal = prodEntry?.produksi ?? prodEntry ?? '';
                
                row += `
                    <td class="${tdClass}">
                        <input type="text" class="form-control harga text-end"
                            data-year="${y.year}" data-triwulan="${triId}" data-id="${it.id}"
                            value="${formatNumberID(hargaVal, true)}" placeholder="Harga">
                    </td>
                    <td class="${tdClass}">
                        <input type="text" class="form-control produksi text-end"
                            data-year="${y.year}" data-triwulan="${triId}" data-id="${it.id}"
                            value="${formatNumberID(produksiVal)}" placeholder="Produksi">
                    </td>`;
            });
            
            row += '</tr>';
            $tbody.append(row);
        });
        
        attachFormatHandlers();
    }

    function attachFormatHandlers() {
        const handleFocus = function(isHarga) {
            return function() {
                const raw = $(this).data('raw');
                $(this).val(raw ?? '').select();
            };
        };
        
        const handleBlur = function(isHarga) {
            return function() {
                const raw = parseNumberID($(this).val());
                $(this).data('raw', raw);
                $(this).val(formatNumberID(raw, isHarga));
            };
        };
        
        $('#hargaTable')
            .off('focus blur', 'input.harga, input.produksi')
            .on('focus', 'input.harga', handleFocus(true))
            .on('blur', 'input.harga', handleBlur(true))
            .on('focus', 'input.produksi', handleFocus(false))
            .on('blur', 'input.produksi', handleBlur(false));
    }

    function setupDropdown(selector, endpoint, label, callback) {
        $.get(endpoint, function(res) {
            const $el = $(selector).empty().append(`<option value="">-- Pilih ${label} --</option>`);
            res.forEach(callback);
            $el.append(`<option value="__new__">+ Tambah Baru...</option>`);
        });
    }

    // Event handlers
    $('#level1').on('change', function() {
        currentRootId = $(this).val();
        if (currentRootId) {
            $.when(
                $.get('/prices-productions/indicators'),
                $.get('/prices-productions/unit-harga'),
                $.get('/prices-productions/unit-produksi')
            ).then(() => loadSubtree(currentRootId));
        } else {
            $('#table-container').hide();
        }
    });

    $('#yearFilter').on('change', function() {
        const selectedKeys = $(this).val() || [];
        selectedYears = allYears.filter(y => 
            selectedKeys.includes(`${y.year}-${y.triwulan_id ?? 0}`)
        );
        buildTable();
    });

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
                    const triwulanId = $('#swalTriwulanSelect').val();
                    const triwulanName = $('#swalTriwulanSelect option:selected').text();
                    
                    if (!newYear || !triwulanId) {
                        Swal.showValidationMessage('Tahun dan Triwulan wajib diisi!');
                        return false;
                    }
                    return { newYear, triwulanId, triwulanName };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    const { newYear, triwulanId, triwulanName } = result.value;
                    
                    if (allYears.some(y => y.year === newYear && y.triwulan_id == triwulanId)) {
                        Swal.fire('Gagal', 'Tahun & triwulan sudah ada!', 'error');
                        return;
                    }
                    
                    const newYearObj = { year: newYear, triwulan_id: triwulanId, triwulan_name: triwulanName };
                    allYears.push(newYearObj);
                    selectedYears.push(newYearObj);
                    
                    $('#yearFilter').append(
                        `<option value="${newYear}-${triwulanId}" selected>${newYear} - ${triwulanName}</option>`
                    ).trigger('change');
                    
                    buildTable();
                    Swal.fire('Berhasil', 'Tahun berhasil ditambahkan!', 'success');
                }
            });
        }).fail(() => Swal.fire('Error', 'Gagal mengambil data triwulan', 'error'));
    });

    $('#saveAll').on('click', function() {
        const payload = [];
        
        $('#hargaTable tbody tr').each(function() {
            const commodityId = $(this).data('id');
            if (!commodityId) return;
            
            $(this).find('input.harga').each(function() {
                const year = $(this).data('year');
                const triwulanId = $(this).data('triwulan');
                const harga = $(this).data('raw');
                const produksi = $(this).closest('tr')
                    .find(`input.produksi[data-year="${year}"][data-triwulan="${triwulanId}"]`)
                    .data('raw');
                
                if (harga || produksi) {
                    payload.push({
                        commodity_id: commodityId,
                        tahun: year,
                        triwulan_id: triwulanId,
                        harga: harga || null,
                        produksi: produksi || null
                    });
                }
            });
        });
        
        if (!payload.length) {
            Swal.fire('Info', 'Tidak ada data untuk disimpan', 'info');
            return;
        }
        
        $.post('/prices-productions/bulk-store', {
            data: JSON.stringify(payload),
            _token: '{{ csrf_token() }}'
        }).done(() => {
            Swal.fire('Sukses', 'Data berhasil disimpan', 'success');
            if (currentRootId) loadSubtree(currentRootId);
        }).fail(() => Swal.fire('Error', 'Gagal menyimpan data', 'error'));
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
        
        $('#commodityModal').modal('show');
    });

    // Handle dynamic option additions
    ['#newIndikator', '#newSatuanHarga', '#newSatuanProduksi'].forEach(selector => {
        $(selector).on('change', function() {
            if ($(this).val() !== '__new__') return;
            
            const typeMap = {
                '#newIndikator': { title: 'Indikator', endpoint: '/prices-productions/indicators', field: 'indikator' },
                '#newSatuanHarga': { title: 'Satuan Harga', endpoint: '/prices-productions/unit-harga', field: 'satuan_harga' },
                '#newSatuanProduksi': { title: 'Satuan Produksi', endpoint: '/prices-productions/unit-produksi', field: 'satuan_produksi' }
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
                
                const postData = { [config.field]: res.value, _token: '{{ csrf_token() }}' };
                $.post(config.endpoint, postData).done(data => {
                    const newOpt = new Option(data[config.field], data.id, true, true);
                    $(selector).append(newOpt).trigger('change');
                }).fail(() => Swal.fire('Error', `Gagal menambah ${config.title.toLowerCase()}`, 'error'));
            });
        });
    });

    $('#parentSelect').on('change', function() {
        const parentId = $(this).val();
        const endpoint = parentId 
            ? `/prices-productions/commodities/${parentId}/next-code`
            : `/prices-productions/commodities/next-code`;
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
            _token: '{{ csrf_token() }}'
        };
        
        if (!data.kode || !data.nama) {
            Swal.fire('Error', 'Kode dan nama wajib diisi', 'error');
            return;
        }
        
        $.post('/prices-productions/commodities', data).done(() => {
            $('#commodityModal').modal('hide');
            Swal.fire('Sukses', 'Komoditas berhasil ditambahkan', 'success');
            if (currentRootId) loadSubtree(currentRootId);
        }).fail(() => Swal.fire('Error', 'Gagal menambah komoditas', 'error'));
    });
});
</script>
@endsection