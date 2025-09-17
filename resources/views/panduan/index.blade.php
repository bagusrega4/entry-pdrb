@extends('layouts.app')
@section('content')
<div class="container">
    <div class="page-inner">
        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <!-- Heading -->
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h2 class="fw-bold mb-3">Panduan</h2>
                <h6 class="op-7 mb-2">Panduan Penggunaan Sistem Rencana Kerja dan Monitoring (SiReMon)</h6>
            </div>
            @if(Auth::user()->id_role == 3)
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('panduan.upload.form') }}" class="btn btn-primary btn-round">Perbarui Panduan</a>
            </div>
            @endif
        </div>
        <div class="embed-responsive">
            @if ($bukuPanduanTerakhir && Storage::disk('public')->exists($bukuPanduanTerakhir->file))
            <embed
                type="application/pdf"
                src="{{ Storage::url($bukuPanduanTerakhir->file) }}"
                class="embed-responsive-item">
            </embed>
            @else
            <p>Tidak ada buku panduan yang tersedia untuk ditampilkan.</p>
            @endif

        </div>
    </div>
</div>
@endsection

<style>
    .embed-responsive {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        /* 16:9 aspect ratio */
        overflow: hidden;
        background: #f8f9fa;
        /* Opsional, memberikan latar belakang abu-abu terang */
        border: 1px solid #ddd;
        /* Opsional, memberikan border */
        border-radius: 8px;
        /* Opsional, memberikan sudut melengkung */
    }

    .embed-responsive-item {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
</style>