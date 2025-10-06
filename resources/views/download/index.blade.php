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
                        <form action="{{ route('download.generate-pdf') }}" method="POST" id="downloadForm">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label for="kategori_id">Pilih Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" id="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="all" {{ old('kategori_id') == 'all' ? 'selected' : '' }} style="font-weight: bold; background-color: #f0f0f0;">
                                        📋 SEMUA KATEGORI
                                    </option>
                                    <optgroup label="Kategori Individual">
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->kode }} - {{ $kategori->nama }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted mt-1">
                                    <i class="fa fa-info-circle"></i> Pilih "SEMUA KATEGORI" untuk mendownload seluruh kategori dalam satu PDF (setiap kategori di halaman terpisah)
                                </small>
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
                                            {{ $triwulan->triwulan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('triwulan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#columnConfigModal">
                                    <i class="fa fa-cog"></i> Konfigurasi Kolom
                                </button>
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
                            <strong>Fitur Baru:</strong><br>
                            • Download semua kategori sekaligus<br>
                            • Setiap kategori di halaman terpisah<br>
                            • Konfigurasi kolom yang muncul di PDF<br>
                            • Klik "Konfigurasi Kolom" untuk mengatur<br>
                            • Kolom kategori & komoditas wajib muncul<br>
                        </small>
                        <hr class="bg-white">
                        <small>
                            <strong>Format Output:</strong><br>
                            • Header: Badan Pusat Statistik<br>
                            • Judul: Data PDRB Triwulan<br>
                            • Tabel data sesuai kategori yang dipilih<br>
                            • Orientasi: Landscape (A4)<br>
                            • PDF multi-halaman untuk semua kategori
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfigurasi Kolom -->
<div class="modal fade" id="columnConfigModal" tabindex="-1" aria-labelledby="columnConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="columnConfigModalLabel">Konfigurasi Kolom PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> 
                    Centang kolom yang ingin ditampilkan dalam PDF. Kolom bertanda <span class="badge bg-danger">Wajib</span> tidak dapat diubah.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center">Tampilkan</th>
                                <th>Nama Kolom</th>
                                <th width="15%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($columnConfigs as $config)
                            <tr>
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input 
                                            class="form-check-input column-checkbox" 
                                            type="checkbox" 
                                            data-config-id="{{ $config->id }}"
                                            {{ $config->is_visible ? 'checked' : '' }}
                                            {{ $config->is_mandatory ? 'disabled' : '' }}
                                        >
                                    </div>
                                </td>
                                <td>
                                    {{ $config->column_label }}
                                </td>
                                <td class="text-center">
                                    @if($config->is_mandatory)
                                        <span class="badge bg-danger">Wajib</span>
                                    @else
                                        <span class="badge bg-secondary">Opsional</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveColumnConfig">
                    <i class="fa fa-save"></i> Simpan Konfigurasi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Variables untuk menyimpan form values
    let formValues = {
        kategori_id: '',
        tahun: '',
        triwulan_id: ''
    };

    // Restore form values saat halaman load
    document.addEventListener('DOMContentLoaded', function() {
        const savedKategori = sessionStorage.getItem('download_kategori_id');
        const savedTahun = sessionStorage.getItem('download_tahun');
        const savedTriwulan = sessionStorage.getItem('download_triwulan_id');

        if (savedKategori) {
            document.getElementById('kategori_id').value = savedKategori;
            formValues.kategori_id = savedKategori;
        }
        if (savedTahun) {
            document.getElementById('tahun').value = savedTahun;
            formValues.tahun = savedTahun;
        }
        if (savedTriwulan) {
            document.getElementById('triwulan_id').value = savedTriwulan;
            formValues.triwulan_id = savedTriwulan;
        }
    });

    // Auto-save form values ke sessionStorage saat berubah
    document.getElementById('kategori_id').addEventListener('change', function() {
        sessionStorage.setItem('download_kategori_id', this.value);
        formValues.kategori_id = this.value;
    });
    document.getElementById('tahun').addEventListener('change', function() {
        sessionStorage.setItem('download_tahun', this.value);
        formValues.tahun = this.value;
    });
    document.getElementById('triwulan_id').addEventListener('change', function() {
        sessionStorage.setItem('download_triwulan_id', this.value);
        formValues.triwulan_id = this.value;
    });

    // Show SweetAlert for session messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            confirmButtonColor: '#d33'
        });
    @endif

    document.getElementById('downloadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('downloadBtn');
        const kategori = document.getElementById('kategori_id');
        const tahun = document.getElementById('tahun').value;
        const triwulan = document.getElementById('triwulan_id');
        
        if (!kategori.value || !tahun || !triwulan.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Lengkap',
                text: 'Mohon lengkapi semua field yang required!',
                confirmButtonColor: '#3085d6'
            });
            return false;
        }
        
        // Get selected option text
        const kategoriText = kategori.value === 'all' 
            ? 'SEMUA KATEGORI' 
            : kategori.options[kategori.selectedIndex].text;
        const triwulanText = triwulan.options[triwulan.selectedIndex].text;
        
        // Special handling untuk "Semua Kategori"
        let confirmationMessage = '';
        if (kategori.value === 'all') {
            confirmationMessage = `
                <div style="text-align: left;">
                    <div class="alert alert-warning" style="background-color: #fff3cd; border: 1px solid #ffc107; padding: 10px; border-radius: 5px;">
                        <strong>⚠️ Perhatian:</strong><br>
                        Anda akan mendownload <strong>SEMUA KATEGORI</strong> sekaligus.
                        Setiap kategori akan berada di halaman terpisah dalam satu file PDF.
                    </div>
                    <hr>
                    <p><strong>Tahun:</strong> ${tahun}</p>
                    <p><strong>Triwulan:</strong> ${triwulanText}</p>
                </div>
                <hr>
                <p>Proses ini mungkin memakan waktu lebih lama. Lanjutkan?</p>
            `;
        } else {
            confirmationMessage = `
                <div style="text-align: left;">
                    <p><strong>Kategori:</strong> ${kategoriText}</p>
                    <p><strong>Tahun:</strong> ${tahun}</p>
                    <p><strong>Triwulan:</strong> ${triwulanText}</p>
                </div>
                <hr>
                <p>Apakah Anda yakin ingin mendownload PDF dengan data di atas?</p>
            `;
        }
        
        // Confirmation before download
        Swal.fire({
            title: 'Konfirmasi Download',
            html: confirmationMessage,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fa fa-download"></i> Ya, Download!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Adjust loading message based on selection
                const loadingTitle = kategori.value === 'all' 
                    ? 'Generating PDF Semua Kategori...'
                    : 'Generating PDF...';
                const loadingHtml = kategori.value === 'all'
                    ? 'Mohon tunggu, PDF sedang dibuat<br><small>Proses ini mungkin memakan waktu beberapa menit untuk semua kategori</small>'
                    : 'Mohon tunggu, PDF sedang dibuat<br><small>Proses ini mungkin memakan waktu beberapa detik</small>';
                
                // Show loading
                Swal.fire({
                    title: loadingTitle,
                    html: loadingHtml,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Generating PDF...';
                btn.disabled = true;
                
                // Submit form
                e.target.submit();
                
                // Auto close loading after longer time for all categories
                const timeout = kategori.value === 'all' ? 30000 : 10000;
                setTimeout(function() {
                    Swal.close();
                    btn.innerHTML = '<i class="fa fa-download"></i> Download PDF';
                    btn.disabled = false;
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Download Selesai!',
                        text: 'PDF berhasil di-generate',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }, timeout);
            }
        });
    });

    // Simpan konfigurasi kolom
    document.getElementById('saveColumnConfig').addEventListener('click', function(e) {
        e.preventDefault();
        
        const checkboxes = document.querySelectorAll('.column-checkbox:not([disabled])');
        const configs = [];

        checkboxes.forEach(checkbox => {
            configs.push({
                id: parseInt(checkbox.getAttribute('data-config-id')),
                is_visible: checkbox.checked
            });
        });

        console.log('Configs to save:', configs); // Debug

        // Tutup modal terlebih dahulu
        const modal = bootstrap.Modal.getInstance(document.getElementById('columnConfigModal'));
        if (modal) {
            modal.hide();
        }

        // Tunggu modal benar-benar tertutup sebelum menampilkan SweetAlert
        setTimeout(() => {
            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                html: 'Mohon tunggu',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("download.update-column-config") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ configs: configs })
            })
            .then(response => {
                console.log('Response status:', response.status); // Debug
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data); // Debug
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Konfigurasi kolom berhasil disimpan!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    // TIDAK RELOAD - biarkan user tetap di form
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Gagal menyimpan konfigurasi kolom!',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error); // Debug
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan: ' + error.message,
                    confirmButtonColor: '#d33'
                });
            });
        }, 300);
    });

    // Reset form confirmation
    document.querySelector('button[onclick="window.location.reload()"]').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Reset Form?',
            text: "Semua inputan akan dikosongkan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Clear sessionStorage
                sessionStorage.removeItem('download_kategori_id');
                sessionStorage.removeItem('download_tahun');
                sessionStorage.removeItem('download_triwulan_id');
                window.location.reload();
            }
        });
    });
</script>
@endsection