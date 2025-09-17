@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card card-round">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Tambah Kegiatan</h4>
                <a href="{{ route('manage.kegiatan.template') }}" class="btn btn-success">
                    Download Template Excel
                </a>
            </div>
            <div class="card-body">
                <form id="kegiatanForm" action="{{ route('manage.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Pilih kegiatan dari database --}}
                    <div class="mb-3">
                        <label for="kegiatan_id" class="form-label">Pilih Kegiatan</label>
                        <select name="kegiatan_id" id="kegiatan_id" class="form-control" required>
                            <option value="" disabled selected hidden>Pilih kegiatan</option>
                            @foreach($kegiatan as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_kegiatan }}</option>
                            @endforeach
                            <option value="other">+ Tambah Baru</option>
                        </select>
                    </div>

                    {{-- Input manual (hidden default) --}}
                    <div class="mb-3" id="new_kegiatan_wrapper" style="display: none;">
                        <label for="nama_kegiatan" class="form-label">Nama Kegiatan Baru</label>
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Kegiatan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" placeholder="Tuliskan deskripsi kegiatan..."></textarea>
                    </div>

                    {{-- Periode Mulai --}}
                    <div class="mb-3">
                        <label for="periode_mulai" class="form-label">Periode Mulai</label>
                        <input type="date"
                            name="periode_mulai"
                            id="periode_mulai"
                            class="form-control"
                            required
                            min="{{ date('Y-m-d') }}">
                    </div>

                    {{-- Periode Selesai --}}
                    <div class="mb-3">
                        <label for="periode_selesai" class="form-label">Periode Selesai</label>
                        <input type="date"
                            name="periode_selesai"
                            id="periode_selesai"
                            class="form-control"
                            required
                            min="{{ date('Y-m-d') }}">
                    </div>

                    {{-- Import Excel --}}
                    <div class="mb-3">
                        <label for="file_excel" class="form-label">Import dari Excel</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls">
                        <small class="text-muted">Format file: .xlsx / .xls. Pastikan kolom minimal berisi <b>nama_kegiatan</b>, <b>deskripsi</b>, <b>periode_mulai</b>, dan <b>periode_selesai</b>.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('manage.kegiatan.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('kegiatan_id').addEventListener('change', function() {
        let wrapper = document.getElementById('new_kegiatan_wrapper');
        let input = document.getElementById('nama_kegiatan');
        if (this.value === 'other') {
            wrapper.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            wrapper.style.display = 'none';
            input.removeAttribute('required');
        }
    });
</script>
@endpush

@push('scripts')
<script>
    const form = document.getElementById('kegiatanForm'); // tambahkan id="kegiatanForm" di <form>
    const kegiatanSelect = document.getElementById('kegiatan_id');
    const newKegiatanWrapper = document.getElementById('new_kegiatan_wrapper');
    const newKegiatanInput = document.getElementById('nama_kegiatan');
    const deskripsi = document.getElementById('deskripsi');
    const mulai = document.getElementById('periode_mulai');
    const selesai = document.getElementById('periode_selesai');
    const fileExcel = document.getElementById('file_excel');

    // Toggle input manual / tambah baru
    kegiatanSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            newKegiatanWrapper.style.display = 'block';
            newKegiatanInput.setAttribute('required', 'required');
        } else {
            newKegiatanWrapper.style.display = 'none';
            newKegiatanInput.removeAttribute('required');
        }
    });

    // Toggle ketika pilih file Excel
    fileExcel.addEventListener('change', function() {
        if (this.files.length > 0) {
            // Nonaktifkan input manual
            kegiatanSelect.disabled = true;
            newKegiatanInput.disabled = true;
            deskripsi.disabled = true;
            mulai.disabled = true;
            selesai.disabled = true;

            // Hilangkan required
            kegiatanSelect.removeAttribute('required');
            newKegiatanInput.removeAttribute('required');
            mulai.removeAttribute('required');
            selesai.removeAttribute('required');

            // Ganti action ke import
            form.setAttribute('action', "{{ route('manage.kegiatan.import') }}");
        } else {
            // Aktifkan kembali input manual
            kegiatanSelect.disabled = false;
            newKegiatanInput.disabled = false;
            deskripsi.disabled = false;
            mulai.disabled = false;
            selesai.disabled = false;

            // Kembalikan required default
            kegiatanSelect.setAttribute('required', 'required');
            mulai.setAttribute('required', 'required');
            selesai.setAttribute('required', 'required');

            // Balik lagi ke store
            form.setAttribute('action', "{{ route('manage.kegiatan.store') }}");
        }
    });
</script>
@endpush