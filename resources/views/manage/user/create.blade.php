@extends('layouts/app')

@section('content')

<style>
    * { box-sizing: border-box; }

    body { background: #f7f6f3; }

    .page {
        padding: 24px 24px 48px;
        padding-top: 80px;
        font-family: 'Figtree', sans-serif;
    }

    .card {
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 16px;
    }

    .card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 0.5px solid #e5e7eb;
        background: #fafafa;
        gap: 8px;
    }
    .card-head-left { display: flex; align-items: center; gap: 8px; }
    .card-head-icon { color: #9ca3af; }
    .card-head-title { font-size: 14px; font-weight: 500; color: #111827; }

    .card-body { padding: 24px; }

    /* Form */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 24px;
    }
    @media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-group.full { grid-column: 1 / -1; }

    .form-label {
        font-size: 12px;
        font-weight: 500;
        color: #374151;
    }
    .form-label span { color: #dc2626; margin-left: 2px; }

    .form-input {
        height: 38px;
        padding: 0 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #111827;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.15s;
        width: 100%;
    }
    .form-input:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.08); }
    .form-input.is-invalid { border-color: #dc2626; }
    .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,0.08); }

    .form-select {
        height: 38px;
        padding: 0 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #111827;
        background: #fff;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        cursor: pointer;
        transition: border-color 0.15s;
        width: 100%;
    }
    .form-select:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.08); }
    .form-select.is-invalid { border-color: #dc2626; }

    .invalid-msg {
        font-size: 11.5px;
        color: #dc2626;
        margin-top: 2px;
    }

    /* Radio */
    .radio-group {
        display: flex;
        gap: 20px;
        align-items: center;
        padding-top: 4px;
    }
    .radio-label {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13px;
        color: #374151;
        cursor: pointer;
    }
    .radio-label input[type="radio"] {
        width: 15px;
        height: 15px;
        accent-color: #f97316;
        cursor: pointer;
    }

    /* Divider */
    .form-divider {
        grid-column: 1 / -1;
        border: none;
        border-top: 0.5px solid #f0f0f0;
        margin: 4px 0;
    }

    /* Action bar */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 24px;
        border-top: 0.5px solid #e5e7eb;
        background: #fafafa;
    }

    .btn-kembali {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 38px;
        padding: 0 18px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        background: #f3f4f6;
        border: 0.5px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        text-decoration: none;
        transition: background 0.12s;
    }
    .btn-kembali:hover { background: #e5e7eb; color: #374151; }

    .btn-simpan {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 38px;
        padding: 0 20px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        background: #f97316; 
        border: 0.5px solid #ea580c;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Figtree', sans-serif;
        transition: background 0.12s;
    }
    .btn-simpan:hover { background: #ea580c; }

    /* Error alert */
    .alert-error {
        padding: 14px 16px;
        background: #fef2f2;
        border: 0.5px solid #fca5a5;
        border-radius: 10px;
        margin-bottom: 16px;
    }
    .alert-error ul {
        margin: 0;
        padding-left: 18px;
        font-size: 13px;
        color: #991b1b;
    }
    .alert-error-title {
        font-size: 13px;
        font-weight: 600;
        color: #991b1b;
        margin-bottom: 6px;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="page">

    <h3 class="fw-bold mb-2">Tambah Pengguna</h3>
    <div class="mb-3">
        <h5 style="font-size:14px; color:#6b7280; font-weight:400; margin:0;">Menambahkan pengguna baru ke sistem SI-PRABU.</h5>
    </div>

    @if($errors->any())
    <div class="alert-error">
        <div class="alert-error-title">Periksa kembali form Anda:</div>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card" style="margin-bottom:0;">
        <div class="card-head">
            <div class="card-head-left">
                <svg class="card-head-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="card-head-title">Informasi Pengguna</span>
            </div>
        </div>

        <form action="{{ route('manage.user.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-grid">

                    <!-- Nama -->
                    <div class="form-group">
                        <label class="form-label">Nama <span>*</span></label>
                        <input type="text" name="nama" class="form-input {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                            value="{{ old('nama') }}" placeholder="Masukkan nama lengkap">
                        @error('nama')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label class="form-label">Username <span>*</span></label>
                        <input type="text" name="username" class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                            value="{{ old('username') }}" placeholder="Masukkan username" autocomplete="off">
                        @error('username')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- NIP Lama -->
                    <div class="form-group">
                        <label class="form-label">NIP Lama <span>*</span></label>
                        <input type="text" name="nip_lama" class="form-input {{ $errors->has('nip_lama') ? 'is-invalid' : '' }}"
                            value="{{ old('nip_lama') }}" placeholder="Masukkan NIP lama">
                        @error('nip_lama')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- NIP Baru -->
                    <div class="form-group">
                        <label class="form-label">NIP Baru <span>*</span></label>
                        <input type="text" name="nip_baru" class="form-input {{ $errors->has('nip_baru') ? 'is-invalid' : '' }}"
                            value="{{ old('nip_baru') }}" placeholder="Masukkan NIP baru">
                        @error('nip_baru')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <hr class="form-divider">

                    <!-- Jabatan -->
                    <div class="form-group">
                        <label class="form-label">Jabatan <span>*</span></label>
                        <select name="jabatan" class="form-select {{ $errors->has('jabatan') ? 'is-invalid' : '' }}">
                            <option value="">— Pilih Jabatan —</option>
                            @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan }}" {{ old('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                            @endforeach
                        </select>
                        @error('jabatan')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- Golongan Akhir -->
                    <div class="form-group">
                        <label class="form-label">Golongan Akhir <span>*</span></label>
                        <select name="golongan_akhir" class="form-select {{ $errors->has('golongan_akhir') ? 'is-invalid' : '' }}">
                            <option value="">— Pilih Golongan Akhir —</option>
                            @foreach($golongans as $golongan_akhir)
                            <option value="{{ $golongan_akhir }}" {{ old('golongan_akhir') == $golongan_akhir ? 'selected' : '' }}>{{ $golongan_akhir }}</option>
                            @endforeach
                        </select>
                        @error('golongan_akhir')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- Tamat Golongan -->
                    <div class="form-group">
                        <label class="form-label">Tamat Golongan <span>*</span></label>
                        <input type="date" name="tamat_gol" class="form-input {{ $errors->has('tamat_gol') ? 'is-invalid' : '' }}"
                            value="{{ old('tamat_gol') }}">
                        @error('tamat_gol')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- Pendidikan -->
                    <div class="form-group">
                        <label class="form-label">Pendidikan <span>*</span></label>
                        <input type="text" name="pendidikan" class="form-input {{ $errors->has('pendidikan') ? 'is-invalid' : '' }}"
                            value="{{ old('pendidikan') }}" placeholder="Masukkan pendidikan terakhir">
                        @error('pendidikan')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- Tanggal Lulus -->
                    <div class="form-group">
                        <label class="form-label">Tanggal Lulus <span>*</span></label>
                        <input type="date" name="tanggal_lulus" class="form-input {{ $errors->has('tanggal_lulus') ? 'is-invalid' : '' }}"
                            value="{{ old('tanggal_lulus') }}">
                        @error('tanggal_lulus')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span>*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="LK" {{ old('jenis_kelamin') == 'LK' ? 'checked' : '' }}>
                                Laki-laki
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="PR" {{ old('jenis_kelamin') == 'PR' ? 'checked' : '' }}>
                                Perempuan
                            </label>
                        </div>
                        @error('jenis_kelamin')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <hr class="form-divider">

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email <span>*</span></label>
                        <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}" placeholder="Masukkan email">
                        @error('email')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password <span>*</span></label>
                        <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan password">
                        @error('password')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label class="form-label">Role <span>*</span></label>
                        <select name="id_role" class="form-select {{ $errors->has('id_role') ? 'is-invalid' : '' }}">
                            <option value="">— Pilih Role —</option>
                            <option value="1" {{ old('id_role') == 1 ? 'selected' : '' }}>Operator</option>
                            <option value="2" {{ old('id_role') == 2 ? 'selected' : '' }}>Ketua Tim</option>
                            <option value="3" {{ old('id_role') == 3 ? 'selected' : '' }}>Admin/Pimpinan</option>
                        </select>
                        @error('id_role')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('manage.user.index') }}" class="btn-kembali">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Kembali
                </a>
                <button type="submit" class="btn-simpan">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>

</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        timer: 2200,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(session('error')),
        confirmButtonColor: '#f97316',
        confirmButtonText: 'OK'
    });
    @endif

});
</script>
@endsection