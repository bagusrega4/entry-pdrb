@extends('layouts/app')

@section('stylecss')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
@endsection

@section('content')
<div class="container">
    <div class="page-inner">

        <h2 class="fw-bold mb-3">Form Tambah Rencana Kerja</h2>

        {{-- Tampilkan error validasi --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('form.store') }}" method="POST">
            @csrf

            <!-- Pilih Tim -->
            @if(auth()->user()->id_role == 1 || auth()->user()->id_role == 2)
            <div class="mb-3">
                <label for="tim_id" class="form-label">Pilih Tim <span class="text-danger">*</span></label>
                <select class="form-select" id="tim_id" name="tim_id" required>
                    <option value="" disabled selected hidden>Pilih Tim Kerja Kantor</option>
                    @foreach ($timList as $tim)
                    <option value="{{ $tim->id }}"
                        {{ old('tim_id', auth()->user()->tim_id) == $tim->id ? 'selected' : '' }}>
                        {{ $tim->nama_tim }}
                    </option>
                    @endforeach
                </select>
            </div>

            @elseif(auth()->user()->id_role == 3)
            @if(auth()->user()->tim_id != 9)
            <input type="hidden" name="tim_id" value="{{ auth()->user()->tim_id }}">
            @endif
            @endif

            <!-- Pilih Kegiatan -->
            <div class="mb-3">
                <label for="kegiatan_id" class="form-label">Pilih Kegiatan <span class="text-danger">*</span></label>
                <select class="form-select" id="kegiatan_id" name="kegiatan_id" required>
                    <option value="" disabled selected hidden>Pilih Kegiatan</option>
                    @foreach($kegiatanList as $kegiatan)
                    <option value="{{ $kegiatan->kegiatan_id }}">
                        {{ $kegiatan->kegiatan->nama_kegiatan }}
                    </option>
                    @endforeach
                </select>

                <!-- Catatan Kegiatan -->
                <div id="catatan_kegiatan" class="mt-2 text-muted small"></div>
            </div>

            <!-- Tanggal -->
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                <input
                    type="date"
                    class="form-control"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal') }}"
                    min="{{ date('Y-m-d') }}"
                    required>
            </div>

            <!-- Jam Kegiatan -->
            <div class="mb-3">
                <label class="form-label">Pukul <span class="text-danger">*</span></label>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label>Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai"
                            value="{{ old('jam_mulai') }}"
                            min="08:00" max="16:00" required>
                    </div>
                    <div class="col-md-6">
                        <label>Jam Akhir</label>
                        <input type="time" class="form-control" id="jam_akhir" name="jam_akhir"
                            value="{{ old('jam_akhir') }}"
                            min="08:00" max="16:00" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggal');
        const jamMulaiInput = document.getElementById('jam_mulai');
        const timSelect = document.getElementById('tim_id');
        const kegiatanSelect = document.getElementById('kegiatan_id');

        // Fungsi untuk atur min jam mulai
        function updateMinTime() {
            const today = new Date();
            const selectedDate = new Date(tanggalInput.value);
            const isToday = selectedDate.toDateString() === today.toDateString();

            if (isToday) {
                const nowHours = String(today.getHours()).padStart(2, '0');
                const nowMinutes = String(today.getMinutes()).padStart(2, '0');
                const nowTime = `${nowHours}:${nowMinutes}`;
                const minTime = (nowTime < "08:00") ? "08:00" : nowTime;
                jamMulaiInput.min = minTime;
            } else {
                jamMulaiInput.min = "08:00";
            }
        }

        tanggalInput.addEventListener('change', updateMinTime);
        if (tanggalInput.value) updateMinTime();

        // SweetAlert jika ada session sukses
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            timer: 3000,
            showConfirmButton: false
        });
        @endif

        function formatTanggalIndo(tanggal) {
            if (!tanggal) return '';
            const bulanIndo = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            const d = new Date(tanggal);
            const tgl = d.getDate();
            const bln = bulanIndo[d.getMonth()];
            const thn = d.getFullYear();
            return `${tgl} ${bln} ${thn}`;
        }

        function loadKegiatan(timId) {
            kegiatanSelect.innerHTML = '<option value="" disabled selected hidden>Pilih Kegiatan</option>';
            const catatanDiv = document.getElementById('catatan_kegiatan');
            catatanDiv.innerHTML = ''; // reset catatan

            if (timId) {
                fetch(`/form/get-kegiatan/${timId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(kegiatan => {
                                const option = document.createElement('option');
                                option.value = kegiatan.kegiatan_id;
                                option.textContent = kegiatan.nama_kegiatan;

                                // Format periode ke Indonesia
                                const periodeMulai = kegiatan.periode_mulai ? formatTanggalIndo(kegiatan.periode_mulai) : null;
                                const periodeSelesai = kegiatan.periode_selesai ? formatTanggalIndo(kegiatan.periode_selesai) : null;

                                option.setAttribute('data-deskripsi', kegiatan.deskripsi ?? '-');
                                option.setAttribute('data-periode',
                                    (periodeMulai && periodeSelesai) ?
                                    `${periodeMulai} s/d ${periodeSelesai}` :
                                    'Belum diatur'
                                );

                                // Tambahkan atribut raw date agar bisa dipakai langsung
                                if (kegiatan.periode_mulai) option.setAttribute('data-periode-mulai', kegiatan.periode_mulai);
                                if (kegiatan.periode_selesai) option.setAttribute('data-periode-selesai', kegiatan.periode_selesai);

                                kegiatanSelect.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.disabled = true;
                            option.textContent = 'Tidak ada kegiatan untuk tim ini';
                            kegiatanSelect.appendChild(option);
                        }
                    });
            }
        }

        // Cek role user
        const userRole = "{{ auth()->user()->id_role }}";
        const userTimId = "{{ auth()->user()->tim_id }}";

        if (userRole == 1 || userRole == 2) {
            // Admin & Ketua tim bisa pilih tim lalu kegiatan akan menyesuaikan
            kegiatanSelect.addEventListener('focus', function() {
                if (!timSelect.value) {
                    kegiatanSelect.innerHTML = '';
                    const option = document.createElement('option');
                    option.disabled = true;
                    option.selected = true;
                    option.textContent = 'Anda belum memilih tim';
                    kegiatanSelect.appendChild(option);
                }
            });

            timSelect.addEventListener('change', function() {
                loadKegiatan(this.value);
            });

            // Jika ada default tim terpilih (misalnya old value), langsung load
            if (timSelect.value) {
                loadKegiatan(timSelect.value);
            }

        } else if (userRole == 3) {
            // Anggota → auto load kegiatan timnya sendiri
            if (userTimId) {
                loadKegiatan(userTimId);
            }
        }

        kegiatanSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const deskripsi = selectedOption.getAttribute('data-deskripsi');
            const periode = selectedOption.getAttribute('data-periode');

            // Ambil periode mulai & selesai (format YYYY-MM-DD)
            const periodeMulai = selectedOption.getAttribute('data-periode-mulai');
            const periodeSelesai = selectedOption.getAttribute('data-periode-selesai');

            const tanggalInput = document.getElementById('tanggal');
            if (periodeMulai && periodeSelesai) {
                tanggalInput.min = periodeMulai;
                tanggalInput.max = periodeSelesai;

                // reset jika tanggal yang dipilih di luar range
                if (tanggalInput.value < periodeMulai || tanggalInput.value > periodeSelesai) {
                    tanggalInput.value = '';
                }
            } else {
                // fallback default (misal mulai dari hari ini tanpa batas)
                tanggalInput.min = "{{ date('Y-m-d') }}";
                tanggalInput.removeAttribute('max');
            }

            // tampilkan catatan kegiatan
            const catatanDiv = document.getElementById('catatan_kegiatan');
            if (deskripsi || periode) {
                catatanDiv.innerHTML = `
                <div class="card border-light shadow-sm mt-2">
                    <div class="card-body p-2">
                        <p class="mb-1"><strong>Periode:</strong> ${periode}</p>
                        <p class="mb-0"><strong>Deskripsi:</strong> ${deskripsi}</p>
                    </div>
                </div>
                `;
            } else {
                catatanDiv.innerHTML = '';
            }
        });
    });
</script>
@endpush