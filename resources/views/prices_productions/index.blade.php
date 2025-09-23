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
                <div class="d-flex gap-2">
                    <select id="yearFilter" multiple="multiple" style="width: 250px;"></select>
                    <button id="addYear" class="btn btn-sm btn-success">Tambah Tahun</button>
                    <button id="addCommodity" class="btn btn-sm btn-warning">Tambah Komoditas</button>
                    <button id="saveAll" class="btn btn-primary">Simpan Semua</button>
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
                    <select id="newIndikator" class="form-control">
                        <option value="">-- Pilih Indikator --</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Satuan Harga</label>
                    <select id="newSatuanHarga" class="form-control">
                        <option value="">-- Pilih Satuan Harga --</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Satuan Produksi</label>
                    <select id="newSatuanProduksi" class="form-control">
                        <option value="">-- Pilih Satuan Produksi --</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button id="saveCommodity" class="btn btn-primary">Tambah</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function() {
        let indikatorOptions = [];
        let unitHargaOptions = [];
        let unitProduksiOptions = [];
        let years = [];
        let itemsData = [];
        const requiredYear = 2010;
        let currentRootId = null;

        // --- parsing yang robust: menerima format ID/EN ---
        function parseNumberID(formatted) {
            if (formatted === null || formatted === undefined || formatted === '') return null;
            let s = String(formatted).trim();

            // hapus "Rp" dan spasi
            s = s.replace(/Rp\s*/i, '').replace(/\s+/g, '');

            // jika mengandung kedua separator '.' dan ','
            const hasDot = s.indexOf('.') !== -1;
            const hasComma = s.indexOf(',') !== -1;

            if (hasDot && hasComma) {
                // anggap separator terakhir adalah decimal
                const lastDot = s.lastIndexOf('.');
                const lastComma = s.lastIndexOf(',');
                if (lastComma > lastDot) {
                    // comma = decimal, dot = thousands
                    s = s.replace(/\./g, '').replace(',', '.');
                } else {
                    // dot = decimal, comma = thousands
                    s = s.replace(/,/g, '');
                }
            } else if (hasComma) {
                // hanya comma ada
                // jika lebih dari 1 comma, kemungkinan comma sebagai thousands -> hapus semua
                if ((s.match(/,/g) || []).length > 1) {
                    s = s.replace(/,/g, '');
                } else {
                    // 1 comma -> anggap decimal
                    s = s.replace(',', '.');
                }
            } else if (hasDot) {
                // hanya dot ada
                // jika lebih dari 1 dot, anggap dot sebagai thousands -> hapus semua
                if ((s.match(/\./g) || []).length > 1) {
                    s = s.replace(/\./g, '');
                }
                // jika 1 dot, bisa jadi decimal (EN style) -> biarkan (`parseFloat` menangani)
            }

            // hapus semua karakter selain 0-9, minus dan dot (decimal)
            s = s.replace(/[^0-9.\-]/g, '');

            const num = parseFloat(s);
            return isNaN(num) ? null : num;
        }

        function formatNumberID(value, isCurrency = false) {
            if (value === null || value === undefined || value === '') return '';
            // pastikan value numeric
            let num = (typeof value === 'number') ? value : parseNumberID(value);
            if (num === null) return '';
            // format dengan locale id-ID, 0-2 decimal places
            const formatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).format(num);
            return isCurrency ? 'Rp ' + formatted : formatted;
        }

        // ambil indikator, unit harga, dan unit produksi
        function fetchIndicatorsAndUnits() {
            return $.when(
                $.get('/prices-productions/indicators').done(data => indikatorOptions = data || []),
                $.get('/prices-productions/unit-harga').done(data => unitHargaOptions = data || []),
                $.get('/prices-productions/unit-produksi').done(data => unitProduksiOptions = data || [])
            ).fail(() => Swal.fire('Error', 'Gagal mengambil indikator / satuan', 'error'));
        }

        $('#level1').on('change', function() {
            currentRootId = $(this).val();
            if (!currentRootId) return $('#table-container').hide();
            fetchIndicatorsAndUnits().then(() => loadSubtree(currentRootId));
        });

        function loadSubtree(rootId) {
            $.get('/prices-productions/commodities/' + rootId + '/subtree', function(res) {
                itemsData = res.commodities || [];
                let serverYears = res.years || [];

                let set = new Set(serverYears.map(y => parseInt(y)));
                set.add(requiredYear);
                years = Array.from(set).sort((a, b) => a - b);

                // isi dropdown tahun
                let $yearFilter = $('#yearFilter').empty();
                years.forEach(y => {
                    $yearFilter.append(`<option value="${y}" selected>${y}</option>`);
                });

                // aktifkan select2
                $yearFilter.select2({
                    placeholder: "Pilih Tahun",
                    allowClear: true,
                    width: '250px'
                });

                buildTable(itemsData);
                $('#table-container').show();
            }).fail(() => Swal.fire('Error', 'Gagal mengambil data subtree', 'error'));
        }

        function buildTable(items, displayYears = years) {
            let $theadRow = $('#hargaTable thead tr').empty();
            $theadRow.append('<th style="min-width:260px;">Komoditas</th>');
            $theadRow.append('<th style="width:160px">Indikator</th>');
            $theadRow.append('<th style="width:120px">Satuan Harga</th>');
            $theadRow.append('<th style="width:140px">Satuan Produksi</th>');

            displayYears.forEach(y => {
                $theadRow.append(`<th colspan="2" style="width:260px;" class="text-center">${y}</th>`);
            });

            let $tbody = $('#hargaTable tbody').empty();

            items.forEach(it => {
                let row = $(`<tr data-id="${it.id}"></tr>`);
                if (it.is_parent) {
                    row.append(
                        `<td colspan="${4 + displayYears.length*2}" class="fw-bold bg-light">
                        ${escapeHtml(it.kode + ' - ' + it.nama)}
                    </td>`
                    );
                } else {
                    let displayName = it.is_leaf ? escapeHtml(it.nama) : escapeHtml(it.kode + ' - ' + it.nama);
                    row.append(`<td>${displayName}</td>`);
                    row.append(`<td>${it.indicator_name ? escapeHtml(it.indicator_name) : ''}</td>`);
                    row.append(`<td>${it.satuan_harga_name ? escapeHtml(it.satuan_harga_name) : ''}</td>`);
                    row.append(`<td>${it.satuan_produksi_name ? escapeHtml(it.satuan_produksi_name) : ''}</td>`);

                    displayYears.forEach(y => {
                        // ambil nilai asli (bisa number atau string)
                        let hargaVal = (it.prices && it.prices[y] !== undefined) ? it.prices[y] : '';
                        let prodVal = (it.productions && it.productions[y] !== undefined) ? it.productions[y] : '';

                        // simpan raw di data-raw (numeric) dan tampilkan formatted
                        const hargaRaw = parseNumberID(hargaVal);
                        const prodRaw = parseNumberID(prodVal);

                        row.append(`
                        <td>
                            <input type="text"
                                class="form-control harga text-end"
                                data-year="${y}"
                                data-raw="${hargaRaw !== null ? hargaRaw : ''}"
                                value="${formatNumberID(hargaRaw, true)}"
                                placeholder="Harga">
                        </td>
                    `);

                        row.append(`
                        <td>
                            <input type="text"
                                class="form-control produksi text-end"
                                data-year="${y}"
                                data-raw="${prodRaw !== null ? prodRaw : ''}"
                                value="${formatNumberID(prodRaw)}"
                                placeholder="Produksi">
                        </td>
                    `);
                    });
                }
                $tbody.append(row);
            });

            attachFormatHandlers();
        }

        // fokus → tampilkan raw untuk editing; blur → format kembali
        function attachFormatHandlers() {
            // HARGA (currency)
            $('#hargaTable').off('focus', 'input.harga blur', 'input.harga input', 'input.harga');
            $('#hargaTable')
                .on('focus', 'input.harga', function() {
                    // tampilkan angka raw (tanpa Rp/format)
                    const raw = $(this).data('raw');
                    $(this).val(raw !== undefined && raw !== '' && raw !== null ? raw : '');
                    $(this).select();
                })
                .on('blur', 'input.harga', function() {
                    const raw = parseNumberID($(this).val());
                    $(this).data('raw', raw);
                    $(this).val(formatNumberID(raw, true));
                })
                .on('input', 'input.harga', function() {
                    // update data-raw live (optional)
                    const raw = parseNumberID($(this).val());
                    $(this).data('raw', raw);
                });

            // PRODUKSI (non-currency)
            $('#hargaTable')
                .off('focus', 'input.produksi blur', 'input.produksi input', 'input.produksi')
                .on('focus', 'input.produksi', function() {
                    const raw = $(this).data('raw');
                    $(this).val(raw !== undefined && raw !== '' && raw !== null ? raw : '');
                    $(this).select();
                })
                .on('blur', 'input.produksi', function() {
                    const raw = parseNumberID($(this).val());
                    $(this).data('raw', raw);
                    $(this).val(formatNumberID(raw));
                })
                .on('input', 'input.produksi', function() {
                    const raw = parseNumberID($(this).val());
                    $(this).data('raw', raw);
                });
        }

        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // === Tambah Tahun ===
        $('#addYear').on('click', function() {
            Swal.fire({
                title: 'Tambah Tahun Baru',
                input: 'number',
                inputLabel: 'Masukkan Tahun',
                inputAttributes: {
                    min: 1900,
                    max: 2100
                },
                showCancelButton: true,
                confirmButtonText: 'Tambah'
            }).then(res => {
                if (!res.isConfirmed) return;
                let newYear = parseInt(res.value);
                if (!newYear) return;

                if (years.includes(newYear)) {
                    Swal.fire('Error', 'Tahun sudah ada', 'error');
                    return;
                }

                years.push(newYear);
                years.sort((a, b) => a - b);

                // tambahkan ke dropdown Select2
                let newOption = new Option(newYear, newYear, true, true);
                $('#yearFilter').append(newOption).trigger('change');

                buildTable(itemsData, $('#yearFilter').val().map(y => parseInt(y)));

                Swal.fire('Sukses', `Tahun ${newYear} berhasil ditambahkan`, 'success');
            });
        });

        // === Event Filter Tahun (Select2) ===
        $('#yearFilter').on('change', function() {
            let selected = $(this).val()?.map(y => parseInt(y)) || [];
            if (selected.length === 0) {
                buildTable(itemsData);
            } else {
                buildTable(itemsData, selected);
            }
        });

        $('#saveAll').on('click', function() {
            let payload = [];
            $('#hargaTable tbody tr').each(function() {
                let commodityId = $(this).data('id');
                if (!commodityId) return;

                $(this).find('input.harga').each(function() {
                    let year = $(this).data('year');
                    let harga = $(this).data('raw');
                    let produksi = $(this).closest('tr').find(`input.produksi[data-year="${year}"]`).data('raw');

                    if (harga || produksi) {
                        payload.push({
                            commodity_id: commodityId,
                            tahun: year,
                            harga: harga || null,
                            produksi: produksi || null
                        });
                    }
                });
            });

            if (payload.length === 0) {
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

        // === Tambah Komoditas (modal) ===
        $('#addCommodity').on('click', function() {
            $('#newNama').val('');
            $('#newKode').val('');
            $('#parentSelect').empty().append('<option value="">-- Root (tanpa parent) --</option>');
            $('#newIndikator').empty().append('<option value="">-- Pilih Indikator --</option>');
            $('#newSatuanHarga').empty().append('<option value="">-- Pilih Satuan Harga --</option>');
            $('#newSatuanProduksi').empty().append('<option value="">-- Pilih Satuan Produksi --</option>');

            $.get('/prices-productions/commodities/all', function(res) {
                res.forEach(c => {
                    $('#parentSelect').append(`<option value="${c.id}">${c.full_name}</option>`);
                });
            });

            // isi indikator
            $.get('/prices-productions/indicators', function(res) {
                res.forEach(i => $('#newIndikator').append(`<option value="${i.id}">${i.indikator}</option>`));
                $('#newIndikator').append('<option value="__new__">+ Tambah Baru...</option>');
            });

            // isi satuan harga
            $.get('/prices-productions/unit-harga', function(res) {
                res.forEach(u => $('#newSatuanHarga').append(`<option value="${u.id}">${u.satuan_harga}</option>`));
                $('#newSatuanHarga').append('<option value="__new__">+ Tambah Baru...</option>');
            });

            // isi satuan produksi
            $.get('/prices-productions/unit-produksi', function(res) {
                res.forEach(u => $('#newSatuanProduksi').append(`<option value="${u.id}">${u.satuan_produksi}</option>`));
                $('#newSatuanProduksi').append('<option value="__new__">+ Tambah Baru...</option>');
            });

            $('#commodityModal').modal('show');
        });

        // Tambah baru Indikator
        $('#newIndikator').on('change', function() {
            if ($(this).val() === '__new__') {
                // modal parent cuma disembunyikan
                $('#commodityModal').modal('hide');

                Swal.fire({
                    title: 'Tambah Indikator Baru',
                    input: 'text',
                    inputLabel: 'Nama Indikator',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan'
                }).then(res => {
                    // tampilkan kembali modal parent dengan isi yang sama
                    $('#commodityModal').modal('show');

                    if (!res.isConfirmed || !res.value) {
                        $(this).val('');
                        return;
                    }

                    $.post('/prices-productions/indicators', {
                        indikator: res.value,
                        _token: '{{ csrf_token() }}'
                    }).done(data => {
                        let newOpt = new Option(data.indikator, data.id, true, true);
                        $('#newIndikator').append(newOpt).trigger('change');
                    }).fail(() => Swal.fire('Error', 'Gagal menambah indikator', 'error'));
                });
            }
        });

        // Tambah baru Satuan Harga
        $('#newSatuanHarga').on('change', function() {
            if ($(this).val() === '__new__') {
                $('#commodityModal').modal('hide');

                Swal.fire({
                    title: 'Tambah Satuan Harga Baru',
                    input: 'text',
                    inputLabel: 'Nama Satuan Harga',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan'
                }).then(res => {
                    $('#commodityModal').modal('show');

                    if (!res.isConfirmed || !res.value) {
                        $(this).val('');
                        return;
                    }
                    $.post('/prices-productions/unit-harga', {
                        satuan_harga: res.value,
                        _token: '{{ csrf_token() }}'
                    }).done(data => {
                        let newOpt = new Option(data.satuan_harga, data.id, true, true);
                        $('#newSatuanHarga').append(newOpt).trigger('change');
                    }).fail(() => Swal.fire('Error', 'Gagal menambah satuan harga', 'error'));
                });
            }
        });

        // Tambah baru Satuan Produksi
        $('#newSatuanProduksi').on('change', function() {
            if ($(this).val() === '__new__') {
                // tutup modal parent dulu
                $('#commodityModal').modal('hide');

                Swal.fire({
                    title: 'Tambah Satuan Produksi Baru',
                    input: 'text',
                    inputLabel: 'Nama Satuan Produksi',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan'
                }).then(res => {
                    // buka lagi modal parent
                    $('#commodityModal').modal('show');

                    if (!res.isConfirmed || !res.value) {
                        $(this).val('');
                        return;
                    }
                    $.post('/prices-productions/unit-produksi', {
                        satuan_produksi: res.value,
                        _token: '{{ csrf_token() }}'
                    }).done(data => {
                        let newOpt = new Option(data.satuan_produksi, data.id, true, true);
                        $('#newSatuanProduksi').append(newOpt).trigger('change');
                    }).fail(() => Swal.fire('Error', 'Gagal menambah satuan produksi', 'error'));
                });
            }
        });

        $('#parentSelect').on('change', function() {
            let parentId = $(this).val();
            if (!parentId) {
                $.get(`/prices-productions/commodities/next-code`, function(res) {
                    $('#newKode').val(res.new_code);
                });
                return;
            }
            $.get(`/prices-productions/commodities/${parentId}/next-code`, function(res) {
                $('#newKode').val(res.new_code);
            });
        });

        $('#saveCommodity').on('click', function() {
            let parentId = $('#parentSelect').val();
            let kode = $('#newKode').val();
            let nama = $('#newNama').val();
            let indikator = $('#newIndikator').val();
            let satuanHarga = $('#newSatuanHarga').val();
            let satuanProduksi = $('#newSatuanProduksi').val();

            // === VALIDASI BARU ===
            if (!kode || !nama) {
                Swal.fire('Error', 'Parent, kode, dan nama wajib diisi', 'error');
                return;
            }

            $.post('/prices-productions/commodities', {
                parent_id: parentId || null,
                kode: kode,
                nama: nama,
                indikator_id: indikator || null,
                satuan_harga_id: satuanHarga || null,
                satuan_produksi_id: satuanProduksi || null,
                _token: '{{ csrf_token() }}'
            }).done(function() {
                $('#commodityModal').modal('hide');
                Swal.fire('Sukses', 'Komoditas berhasil ditambahkan', 'success');
                if (currentRootId) {
                    loadSubtree(currentRootId);
                }
            }).fail(() => Swal.fire('Error', 'Gagal menambah komoditas', 'error'));
        });
    });
</script>
@endsection