@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-inner">

        {{-- Notifikasi Error --}}
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded px-3 py-2"
            role="alert"
            style="border-left: 5px solid #dc3545;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Notifikasi Validasi --}}
        @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded px-3 py-2"
            role="alert"
            style="border-left: 5px solid #dc3545;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded w-100 mb-3 d-flex justify-content-between align-items-center"
            role="alert"
            style="border-left: 5px solid #28a745; padding: 0.75rem 1rem;">
            <span class="me-3">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Card --}}
        <div class="card card-round">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <h4 class="card-title mb-0">Manage Kegiatan</h4>
                <div class="d-flex flex-column align-items-md-end gap-2">
                    @if(auth()->user()->id_role != 1)
                    <a href="{{ route('manage.kegiatan.create') }}" class="btn btn-primary btn-sm">+ Tambah Kegiatan</a>
                    @endif
                </div>
            </div>

            <div class="card-body">

                {{-- FILTER --}}
                <div class="mb-3">
                    <form method="GET" action="{{ route('manage.kegiatan.index') }}">
                        <div class="row g-3">

                            @if(auth()->user()->id_role == 3)
                            <div class="col-12 col-md-3">
                                <label for="tim_id" class="form-label">Nama Tim</label>
                                <select name="tim_id" id="tim_id" class="form-select">
                                    <option value="" disabled {{ request('tim_id') ? '' : 'selected' }}>Pilih Tim</option>
                                    @foreach($timList as $tim)
                                    <option value="{{ $tim->id }}" {{ request('tim_id') == $tim->id ? 'selected' : '' }}>
                                        {{ $tim->nama_tim }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="col-12 col-md-3">
                                <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                                    value="{{ request('nama_kegiatan') }}"
                                    class="form-control"
                                    placeholder="Cari kegiatan...">
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="periode_mulai" class="form-label">Periode Mulai</label>
                                <input type="date" name="periode_mulai" id="periode_mulai"
                                    value="{{ request('periode_mulai') }}"
                                    class="form-control">
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="periode_selesai" class="form-label">Periode Selesai</label>
                                <input type="date" name="periode_selesai" id="periode_selesai"
                                    value="{{ request('periode_selesai') }}"
                                    class="form-control">
                            </div>

                            <div class="col-12 col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-auto d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Filter</button>
                                <a href="{{ route('manage.kegiatan.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- END FILTER --}}

                @if($kegiatanList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Kegiatan</th>
                                <th>Periode</th>
                                <th>Deskripsi</th>
                                @if(auth()->user()->id_role == 3)
                                <th>Nama Tim</th>
                                @endif
                                <th>Status</th>
                                <th style="min-width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kegiatanList as $item)
                            <tr>
                                <td class="text-center">
                                    {{ ($kegiatanList->currentPage() - 1) * $kegiatanList->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $item->nama_kegiatan }}</td>
                                <td>
                                    @if($item->periode_mulai && $item->periode_selesai)
                                    {{ \Carbon\Carbon::parse($item->periode_mulai)->translatedFormat('d F Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($item->periode_selesai)->translatedFormat('d F Y') }}
                                    @else
                                    <em>Belum diatur</em>
                                    @endif
                                </td>
                                <td>{{ $item->deskripsi ?? '-' }}</td>

                                @if(auth()->user()->id_role == 3)
                                <td class="text-center">{{ $item->tim->nama_tim ?? '-' }}</td>
                                @endif

                                <td class="text-center">
                                    @if($item->status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                    @else
                                    <span class="badge bg-primary">Aktif</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex flex-row gap-1 justify-content-center">
                                        @if($item->status != 'selesai')
                                        {{-- Tombol Selesaikan --}}
                                        <form action="{{ route('manage.kegiatan.selesai', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirmSelesai(this, '{{ $item->nama_kegiatan }}')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm w-100">Selesai</button>
                                        </form>
                                        @else
                                        {{-- Tombol Aktifkan Kembali --}}
                                        <form action="{{ route('manage.kegiatan.aktif', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirmAktif(this, '{{ $item->nama_kegiatan }}')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning btn-sm w-100">Aktifkan</button>
                                        </form>
                                        @endif

                                        {{-- Tombol Hapus (khusus id_role == 3) --}}
                                        @if(auth()->user()->id_role == 3)
                                        <form action="{{ route('manage.kegiatan.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirmDelete(this, '{{ $item->nama_kegiatan }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Per Page & Pagination --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">

                        {{-- Per Page Selector --}}
                        <form method="GET" action="{{ route('manage.kegiatan.index') }}" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="tim_id" value="{{ request('tim_id') }}">
                            <input type="hidden" name="nama_kegiatan" value="{{ request('nama_kegiatan') }}">
                            <input type="hidden" name="periode_mulai" value="{{ request('periode_mulai') }}">
                            <input type="hidden" name="periode_selesai" value="{{ request('periode_selesai') }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">

                            <label for="per_page" class="form-label mb-0">Tampilkan</label>
                            <select name="per_page" id="per_page" class="form-select form-select-sm w-auto"
                                onchange="this.form.submit()">
                                <option value="5" {{ request('per_page') == 5  ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span class="ms-1">data per halaman</span>
                        </form>

                        {{-- Pagination + Download --}}
                        <div class="d-flex flex-column align-items-end gap-2">
                            {{-- Pagination --}}
                            <div>
                                {{ $kegiatanList->withQueryString()->links() }}
                            </div>

                            {{-- Tombol Download --}}
                            @if(auth()->user()->id_role == 3 || 2)
                            <div class="d-flex gap-2">
                                <a href="{{ route('manage.kegiatan.export.excel', request()->all()) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </a>
                                <a href="{{ route('manage.kegiatan.export.pdf', request()->all()) }}" class="btn btn-danger btn-sm" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <h6 class="text-muted mb-3">Belum ada kegiatan yang ditambahkan.</h6>
                    @if(auth()->user()->id_role != 3)
                    <a href="{{ route('manage.kegiatan.create') }}" class="btn btn-primary">
                        + Tambah Kegiatan Pertama
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmSelesai(form, namaKegiatan) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin?',
            text: "Kegiatan \"" + namaKegiatan + "\" akan ditandai sebagai selesai.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Selesaikan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmAktif(form, namaKegiatan) {
        event.preventDefault();
        Swal.fire({
            title: 'Aktifkan kembali?',
            text: "Kegiatan \"" + namaKegiatan + "\" akan diaktifkan kembali.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Aktifkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }

    function confirmDelete(form, namaKegiatan) {
        event.preventDefault();
        Swal.fire({
            title: 'Hapus kegiatan?',
            text: "Kegiatan \"" + namaKegiatan + "\" akan dihapus permanen.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>
@endpush