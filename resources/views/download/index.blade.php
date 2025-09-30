@extends('layouts/app')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Download</h3>
                <h6 class="op-7 mb-2">Halaman Download Data PDRB</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Download Data PDRB</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('download.generate-pdf') }}" method="POST" id="downloadForm">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label for="kategori_id">Pilih Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" id="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kode }} - {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="tahun">Pilih Tahun <span class="text-danger">*</span></label>
                                <select name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach($tahuns as $t)
                                        <option value="{{ $t }}" {{ old('tahun') == $t ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="triwulan_id">Pilih Triwulan <span class="text-danger">*</span></label>
                                <select name="triwulan_id" id="triwulan_id" class="form-control @error('triwulan_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Triwulan --</option>
                                    @foreach($triwulans as $triwulan)
                                        <option value="{{ $triwulan->id }}" {{ old('triwulan_id') == $triwulan->id ? 'selected' : '' }}>
                                            Triwulan {{ $triwulan->triwulan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('triwulan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="downloadBtn">
                                    <i class="fa fa-download"></i> Download PDF
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                                    <i class="fa fa-refresh"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fa fa-info-circle"></i> Informasi
                        </h5>
                        <hr class="bg-white">
                        <p class="card-text">
                            Pilih <strong>Kategori</strong>, <strong>Tahun</strong>, dan <strong>Triwulan</strong> untuk mendownload data PDRB dalam format PDF.
                        </p>
                        <hr class="bg-white">
                        <small>
                            <strong>Format Output:</strong><br>
                            • Header: Badan Pusat Statistik<br>
                            • Judul: Data PDRB Triwulan<br>
                            • Tabel data sesuai kategori yang dipilih<br>
                            • Orientasi: Potrait (A4)
                        </small>
                        <hr class="bg-white">
                        <small>
                            <strong>Data yang ditampilkan:</strong><br>
                            • Harga & Produksi<br>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.getElementById('downloadForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('downloadBtn');
        const kategori = document.getElementById('kategori_id').value;
        const tahun = document.getElementById('tahun').value;
        const triwulan = document.getElementById('triwulan_id').value;
        
        if (!kategori || !tahun || !triwulan) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang required!');
            return false;
        }
        
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Generating PDF...';
        btn.disabled = true;
        
        // Re-enable button after 10 seconds (in case of error)
        setTimeout(function() {
            btn.innerHTML = '<i class="fa fa-download"></i> Download PDF';
            btn.disabled = false;
        }, 10000);
    });
</script>
@endsection