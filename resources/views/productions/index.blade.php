@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Input Produksi Komoditas</h3>
                <h6 class="op-7 mb-2">Silakan pilih komoditas lalu isi produksi</h6>
            </div>
        </div>

        <!-- Container untuk level dropdown -->
        <div id="level-container" class="mb-3">
            <label>Komoditas Level 1</label>
            <select id="level1" data-level="1" class="form-control">
                <option value="">-- Pilih --</option>
                @foreach($commodities as $c)
                <option value="{{ $c->id }}">{{ $c->kode }} - {{ $c->nama }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted d-block mt-3"></small>
        </div>

        <!-- Form produksi -->
        <div id="form-produksi" style="display:none;">
            <h5>Input Produksi</h5>
            <div class="mb-3">
                <label>Indikator</label>
                <select id="indikator" class="form-control"></select>
                <small class="form-text text-muted">Pilih indikator sesuai pengukuran (misalnya: Produksi, Konsumsi, Impor, Ekspor, dll).</small>
            </div>
            <div class="mb-3">
                <label>Satuan</label>
                <select id="unit" class="form-control"></select>
                <small class="form-text text-muted">Pilih satuan yang sesuai untuk indikator (contoh: Kg, Ton, Liter, dll).</small>
            </div>
            <div class="mb-3">
                <label>Tahun</label>
                <input type="number" id="tahun" class="form-control" value="{{ date('Y') }}">
                <small class="form-text text-muted">Isi tahun produksi komoditas, default tahun berjalan.</small>
            </div>
            <div class="mb-3">
                <label>Produksi Menurut Tahun</label>
                <input type="number" id="produksi" class="form-control">
                <small class="form-text text-muted">Isi produksi komoditas berdasarkan indikator & satuan yang dipilih.</small>
            </div>
            <button id="save" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function() {
        function removeLowerLevels(level) {
            $('#level-container select').each(function() {
                let l = parseInt($(this).data('level')) || 0;
                if (l > level) {
                    $(this).closest('div.mb-3').remove();
                }
            });
        }

        function loadChildren(parentId, level) {
            $.get('/productions/commodities/' + parentId + '/children', function(data) {
                removeLowerLevels(level);

                if (data && data.length > 0) {
                    let nextLevel = level + 1;
                    let $wrap = $(`
                    <div class="mb-3">
                        <label>Level ${nextLevel}</label>
                        <select id="level${nextLevel}" data-level="${nextLevel}" class="form-control">
                            <option value="">-- Pilih --</option>
                        </select>
                    </div>
                `);
                    $('#level-container').append($wrap);

                    data.forEach(function(c) {
                        $wrap.find('select').append(
                            $('<option>').val(c.id).text(`${c.kode} - ${c.nama}`)
                        );
                    });

                    $wrap.find('select').on('change', function() {
                        let id = $(this).val();
                        if (!id) {
                            $('#form-produksi').hide();
                            removeLowerLevels(parseInt($(this).data('level')));
                            return;
                        }
                        loadChildren(id, nextLevel);
                    });

                    $('#form-produksi').hide();
                } else {
                    // leaf node: load indikator & units
                    loadIndicatorsAndUnits();
                }
            }).fail(function() {
                Swal.fire('Error', 'Gagal mengambil children untuk commodity ' + parentId, 'error');
            });
        }

        function loadIndicatorsAndUnits() {
            // indikator
            $.get('/productions/indicators', function(data) {
                let $indikator = $('#indikator').empty().append($('<option>').val('').text('-- Pilih --'));
                (data || []).forEach(function(i) {
                    $indikator.append($('<option>').val(i.id).text(i.indikator_produksi));
                });
            }).fail(function() {
                Swal.fire('Error', 'Gagal mengambil data indikator', 'error');
            });

            // units
            $.get('/productions/units', function(data) {
                let $unit = $('#unit').empty().append($('<option>').val('').text('-- Pilih --'));
                (data || []).forEach(function(u) {
                    $unit.append($('<option>').val(u.id).text(u.satuan_produksi));
                });
            }).fail(function() {
                Swal.fire('Error', 'Gagal mengambil data unit', 'error');
            });

            $('#form-produksi').show();
        }

        // level1 change
        $('#level1').on('change', function() {
            let id = $(this).val();
            removeLowerLevels(1);
            if (!id) {
                $('#form-produksi').hide();
                return;
            }
            loadChildren(id, 1);
        });

        // simpan produksi
        $('#save').on('click', function() {
            let commodityId = null;
            $('#level-container select').each(function() {
                if ($(this).val()) commodityId = $(this).val();
            });

            let indicatorId = $('#indikator').val();
            let unitId = $('#unit').val();
            let tahun = $('#tahun').val();
            let produksi = $('#produksi').val();

            if (!commodityId) return Swal.fire('Peringatan', 'Pilih komoditas sampai level paling bawah.', 'warning');
            if (!tahun) return Swal.fire('Peringatan', 'Isi tahun.', 'warning');
            if (!produksi) return Swal.fire('Peringatan', 'Isi produksi.', 'warning');

            // cek indikator & unit kosong
            if (!indicatorId || !unitId) {
                let msg = '';
                if (!indicatorId && !unitId) {
                    msg = 'Indikator dan Satuan kosong. Apakah tetap ingin menyimpan?';
                } else if (!indicatorId) {
                    msg = 'Indikator kosong. Apakah tetap ingin menyimpan?';
                } else if (!unitId) {
                    msg = 'Satuan kosong. Apakah tetap ingin menyimpan?';
                }

                Swal.fire({
                    title: 'Konfirmasi',
                    text: msg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        saveProduksi(commodityId, indicatorId, unitId, tahun, produksi);
                    }
                });
            } else {
                // langsung simpan
                saveProduksi(commodityId, indicatorId, unitId, tahun, produksi);
            }
        });

        function saveProduksi(commodityId, indicatorId, unitId, tahun, produksi) {
            $.post('/productions', {
                _token: '{{ csrf_token() }}',
                commodity_id: commodityId,
                indicator_production_id: indicatorId || null,
                unit_production_id: unitId || null,
                tahun: tahun,
                produksi: produksi
            }).done(function(res) {
                Swal.fire('Berhasil', 'Produksi berhasil disimpan', 'success');
                $('#produksi').val('');
                $('#indikator').val('');
                $('#unit').val('');
            }).fail(function(xhr) {
                let msg = xhr.responseJSON?.message || 'Gagal menyimpan';
                Swal.fire('Error', msg, 'error');
            });
        }
    });
</script>
@endsection