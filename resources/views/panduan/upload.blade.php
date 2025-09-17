@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Notifikasi Error Custom -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Notifikasi Validasi Error -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h2 class="fw-bold mb-3">Upload File Panduan</h2>
                <h6 class="op-7 mb-2">Mengunggah File Buku Panduan</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('panduan.index') }}" class="btn btn-danger btn-round">Kembali</a>
            </div>
        </div>

        <div class="card card-round">
            <div class="card-body">
                   
                <form action="{{ route('panduan.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">File Buku Panduan (PDF)</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Unggah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection