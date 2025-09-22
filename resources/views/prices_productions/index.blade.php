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
                <h5>Daftar Komoditas & Harga</h5>
                <div>
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
<script>
    $(function() {
        let indikatorOptions = [];
        let unitHargaOptions = [];
        let unitProduksiOptions = [];
        let years = [];
        const requiredYear = 2010;
        let currentRootId = null;

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
                let items = res.commodities || [];
                let serverYears = res.years || [];

                let set = new Set(serverYears.map(y => parseInt(y)));
                set.add(requiredYear);
                years = Array.from(set).sort((a, b) => a - b);

                buildTable(items);
                $('#table-container').show();
            }).fail(() => Swal.fire('Error', 'Gagal mengambil data subtree', 'error'));
        }

        function buildTable(items) {
            let $theadRow = $('#hargaTable thead tr').empty();
            $theadRow.append('<th style="min-width:260px;">Komoditas</th>');
            $theadRow.append('<th style="width:160px">Indikator</th>');
            $theadRow.append('<th style="width:120px">Satuan Harga</th>');
            $theadRow.append('<th style="width:140px">Satuan Produksi</th>');

            years.forEach(y => {
                $theadRow.append(`<th colspan="2" style="width:260px;" class="text-center">${y}</th>`);
            });

            let $tbody = $('#hargaTable tbody').empty();

            items.forEach(it => {
                let row = $(`<tr data-id="${it.id}"></tr>`);
                if (it.is_parent) {
                    row.append(
                        `<td colspan="${4 + years.length*2}" class="fw-bold bg-light">
                            ${escapeHtml(it.kode + ' - ' + it.nama)}
                        </td>`
                    );
                } else {
                    let displayName = it.is_leaf ? escapeHtml(it.nama) : escapeHtml(it.kode + ' - ' + it.nama);
                    row.append(`<td>${displayName}</td>`);
                    row.append(`<td>${it.indicator_name ? escapeHtml(it.indicator_name) : ''}</td>`);
                    row.append(`<td>${it.satuan_harga_name ? escapeHtml(it.satuan_harga_name) : ''}</td>`);
                    row.append(`<td>${it.satuan_produksi_name ? escapeHtml(it.satuan_produksi_name) : ''}</td>`);
                    years.forEach(y => {
                        let hargaVal = (it.prices && it.prices[y] !== undefined) ? it.prices[y] : '';
                        let prodVal = (it.productions && it.productions[y] !== undefined) ? it.productions[y] : '';
                        row.append(`<td><input type="number" class="form-control harga" data-year="${y}" value="${hargaVal}" placeholder="Harga"></td>`);
                        row.append(`<td><input type="number" class="form-control produksi" data-year="${y}" value="${prodVal}" placeholder="Produksi"></td>`);
                    });
                }
                $tbody.append(row);
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

        // === Tambah Komoditas ===
        $('#addCommodity').on('click', function() {
            $('#newNama').val('');
            $('#newKode').val('');
            $('#parentSelect').empty().append('<option value="">-- Root (tanpa parent) --</option>');
            $('#newIndikator').empty().append('<option value="">-- Pilih Indikator --</option>');
            $('#newSatuanHarga').empty().append('<option value="">-- Pilih Satuan Harga --</option>');
            $('#newSatuanProduksi').empty().append('<option value="">-- Pilih Satuan Produksi --</option>');

            // ambil semua komoditas untuk dropdown parent
            $.get('/prices-productions/commodities/all', function(res) {
                res.forEach(c => {
                    $('#parentSelect').append(`<option value="${c.id}">${c.full_name}</option>`);
                });
            });

            // ambil indikator, satuan harga, satuan produksi
            $.get('/prices-productions/indicators', function(res) {
                res.forEach(i => $('#newIndikator').append(`<option value="${i.id}">${i.indikator}</option>`));
            });
            $.get('/prices-productions/unit-harga', function(res) {
                res.forEach(u => $('#newSatuanHarga').append(`<option value="${u.id}">${u.satuan_harga}</option>`));
            });
            $.get('/prices-productions/unit-produksi', function(res) {
                res.forEach(u => $('#newSatuanProduksi').append(`<option value="${u.id}">${u.satuan_produksi}</option>`));
            });

            $('#commodityModal').modal('show');
        })

        // ketika pilih parent → ambil kode berikutnya
        $('#parentSelect').on('change', function() {
            let parentId = $(this).val();
            if (!parentId) {
                // kalau root, ambil kode root baru
                $.get(`/prices-productions/commodities/next-code`, function(res) {
                    $('#newKode').val(res.new_code);
                });
                return;
            }

            $.get(`/prices-productions/commodities/${parentId}/next-code`, function(res) {
                $('#newKode').val(res.new_code);
            });
        });

        // simpan komoditas baru
        $('#saveCommodity').on('click', function() {
            let parentId = $('#parentSelect').val();
            let kode = $('#newKode').val();
            let nama = $('#newNama').val();
            let indikator = $('#newIndikator').val();
            let satuanHarga = $('#newSatuanHarga').val();
            let satuanProduksi = $('#newSatuanProduksi').val();

            if (!kode || !nama || !indikator || !satuanHarga || !satuanProduksi) {
                Swal.fire('Error', 'Isi semua field', 'error');
                return;
            }

            $.post('/prices-productions/commodities', {
                parent_id: parentId || null,
                kode: kode,
                nama: nama,
                indikator_id: indikator,
                satuan_harga_id: satuanHarga,
                satuan_produksi_id: satuanProduksi,
                _token: '{{ csrf_token() }}'
            }).done(function(res) {
                $('#commodityModal').modal('hide');
                Swal.fire('Sukses', 'Komoditas berhasil ditambahkan', 'success');
                if (currentRootId) {
                    loadSubtree(currentRootId); // refresh tabel
                }
            }).fail(() => Swal.fire('Error', 'Gagal menambah komoditas', 'error'));
        });
    });
</script>
@endsection