<x-guest-layout>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Reset & Base */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --orange:     #f97316;
    --orange-dk:  #ea580c;
    --orange-lt:  rgba(249,115,22,.08);
    --orange-glow:rgba(249,115,22,.18);
    --dark:       #0f172a;
    --dark-2:     #1e293b;
    --text:       #111827;
    --text-2:     #6b7280;
    --text-3:     #9ca3af;
    --border:     #e5e7eb;
    --bg:         #f8fafc;
    --white:      #ffffff;
    --red:        #ef4444;
    --green:      #22c55e;
    --radius:     10px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,.08);
    --shadow-lg:  0 10px 40px rgba(0,0,0,.12);
}

body {
    font-family: 'Figtree', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    color: var(--text);
}

/* Layout */
.page {
    display: grid;
    grid-template-columns: 1fr 380px;
    min-height: 100vh;
}

/* Right branding panel */
.panel-right {
    background: var(--dark);
    position: sticky;
    top: 0;
    height: 100vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 48px 40px;
}

.panel-right::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 50% at 110% 20%, var(--orange-glow), transparent),
        radial-gradient(ellipse 40% 40% at -10% 80%, rgba(249,115,22,.1), transparent);
    pointer-events: none;
}

/* Subtle grid texture */
.panel-right::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
}

.panel-right-content { position: relative; z-index: 1; }

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 48px;
}
.brand-logo { width: 36px; height: 36px; object-fit: contain; }
.brand-name {
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: var(--white);
}

.panel-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--orange);
    background: rgba(249,115,22,.12);
    border: 1px solid rgba(249,115,22,.2);
    border-radius: 100px;
    padding: 5px 12px;
    margin-bottom: 20px;
}
.panel-badge::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--orange);
    animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(.7); }
}

.panel-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--white);
    line-height: 1.3;
    letter-spacing: -.5px;
    margin-bottom: 14px;
}
.panel-title em {
    font-style: normal;
    color: var(--orange);
}

.panel-desc {
    font-size: 12.5px;
    color: rgba(255,255,255,.38);
    line-height: 1.75;
    margin-bottom: 36px;
}

.panel-stats { display: flex; flex-direction: column; gap: 12px; }
.stat {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 10px;
}
.stat-icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: rgba(249,115,22,.12);
    border: 1px solid rgba(249,115,22,.18);
    display: flex; align-items: center; justify-content: center;
    color: var(--orange);
    flex-shrink: 0;
}
.stat-lbl { font-size: 10.5px; color: rgba(255,255,255,.28); font-weight: 500; margin-bottom: 2px; }
.stat-val { font-size: 12.5px; color: rgba(255,255,255,.8); font-weight: 600; }

/* Left form panel  */
.panel-left {
    background: var(--white);
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 48px;
    overflow-y: auto;
}

.form-wrap {
    width: 100%;
    max-width: 560px;
}

/* Top brand (mobile only / left panel) */
.form-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
}
.form-brand-logo { width: 34px; height: 34px; object-fit: contain; }
.form-brand-name {
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text);
}

.form-heading { margin-bottom: 24px; }
.form-heading h1 {
    font-size: 24px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -.4px;
    margin-bottom: 4px;
}
.form-heading p { font-size: 13px; color: var(--text-3); line-height: 1.5; }

/* BPS Type selector  */
.type-selector {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 24px;
}

.type-card { position: relative; }
.type-card input[type="radio"] {
    position: absolute; opacity: 0; width: 0; height: 0;
}
.type-card label {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    padding: 16px 18px;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    cursor: pointer;
    transition: all .2s;
    background: var(--white);
    position: relative;
    overflow: hidden;
}
.type-card label::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--orange-lt);
    opacity: 0;
    transition: opacity .2s;
}
.type-card input:checked + label {
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(249,115,22,.1);
}
.type-card input:checked + label::before { opacity: 1; }

.type-card-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: #f3f4f6;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;
    color: var(--text-2);
    transition: all .2s;
    position: relative; z-index: 1;
}
.type-card input:checked + label .type-card-icon {
    background: rgba(249,115,22,.12);
    color: var(--orange);
}

.type-card-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
    position: relative; z-index: 1;
    transition: color .2s;
    line-height: 1.2;
}
.type-card input:checked + label .type-card-title { color: var(--orange); }

.type-card-sub {
    font-size: 11px;
    color: var(--text-3);
    font-weight: 400;
    position: relative; z-index: 1;
    line-height: 1.4;
}

/* Check badge */
.type-check {
    position: absolute;
    top: 10px; right: 10px;
    width: 18px; height: 18px;
    border-radius: 50%;
    border: 1.5px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    background: var(--white);
    transition: all .2s;
    z-index: 1;
}
.type-card input:checked + label .type-check {
    background: var(--orange);
    border-color: var(--orange);
    color: var(--white);
}
.type-check i { font-size: 9px; display: none; }
.type-card input:checked + label .type-check i { display: block; }

/* Section header  */
.section-head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 20px 0 16px;
}
.section-head-line { flex: 1; height: 1px; background: #f1f5f9; }
.section-head-label {
    font-size: 10.5px;
    font-weight: 700;
    color: var(--text-3);
    text-transform: uppercase;
    letter-spacing: 1px;
    white-space: nowrap;
}

/* Form elements */
.field-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 14px; }
.field-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0 14px; }

.field { margin-bottom: 14px; }

.field-label {
    display: block;
    font-size: 11.5px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 6px;
    letter-spacing: .1px;
}
.field-label span { font-weight: 400; color: var(--text-3); }

.field-wrap { position: relative; }
.field-ico {
    position: absolute;
    left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--text-3); font-size: 14px;
    pointer-events: none; line-height: 1;
}

.field-input, .field-select {
    width: 100%; height: 42px;
    padding: 0 14px 0 38px;
    font-size: 13px;
    font-family: 'Figtree', sans-serif;
    color: var(--text);
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    outline: none;
    transition: border-color .15s, box-shadow .15s, background .15s;
}
.field-input::placeholder { color: #d1d5db; }
.field-input:focus, .field-select:focus {
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(249,115,22,.1);
    background: #fffaf7;
}
.field-input.err { border-color: #fca5a5; background: #fff5f5; }
.field-select { appearance: none; cursor: pointer; }
.field-select-arrow {
    position: absolute;
    right: 12px; top: 50%; transform: translateY(-50%);
    color: var(--text-3); font-size: 12px; pointer-events: none;
}

.pw-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; padding: 2px;
    color: var(--text-3); font-size: 14px; cursor: pointer;
    transition: color .15s; line-height: 1;
}
.pw-toggle:hover { color: var(--orange); }

.field-error {
    font-size: 11px;
    color: var(--red);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Photo upload */
.photo-box {
    border: 1.5px dashed var(--border);
    border-radius: var(--radius);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    transition: border-color .15s, background .15s;
}
.photo-box:hover { border-color: var(--orange); background: #fffaf7; }

.photo-avatar {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: #f3f4f6;
    border: 2px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0;
    transition: border-color .15s;
}
.photo-box:hover .photo-avatar { border-color: rgba(249,115,22,.3); }
.photo-avatar img { width: 100%; height: 100%; object-fit: cover; display: none; }
.photo-avatar i   { font-size: 20px; color: #d1d5db; }

.photo-text .t1 { font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 2px; }
.photo-text .t2 { font-size: 11px; color: var(--text-3); }

.photo-cta {
    margin-left: auto;
    font-size: 11.5px; font-weight: 600;
    color: var(--orange);
    background: rgba(249,115,22,.08);
    border: 1px solid rgba(249,115,22,.15);
    border-radius: 7px;
    padding: 6px 12px;
    cursor: pointer; flex-shrink: 0;
    transition: background .15s;
}
.photo-cta:hover { background: rgba(249,115,22,.15); }

/* Collapsible */
.collapsible {
    overflow: hidden;
    transition: max-height .35s cubic-bezier(.4,0,.2,1), opacity .25s;
}
.collapsible.hidden { max-height: 0 !important; opacity: 0; pointer-events: none; }

/* Submit */
.btn-submit {
    width: 100%; height: 46px;
    background: var(--orange);
    border: none; border-radius: var(--radius);
    font-size: 14px; font-weight: 700;
    font-family: 'Figtree', sans-serif;
    color: var(--white);
    cursor: pointer; letter-spacing: .2px;
    margin-top: 8px;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: background .15s, box-shadow .15s, transform .1s;
    box-shadow: 0 2px 8px rgba(249,115,22,.3);
}
.btn-submit:hover {
    background: var(--orange-dk);
    box-shadow: 0 6px 20px rgba(249,115,22,.4);
    transform: translateY(-1px);
}
.btn-submit:active { transform: translateY(0); }

/* Login link */
.to-login {
    margin-top: 18px;
    text-align: center;
    font-size: 12.5px;
    color: var(--text-3);
}
.to-login a { color: var(--orange); font-weight: 700; text-decoration: none; }
.to-login a:hover { text-decoration: underline; }

.footer-note {
    margin-top: 16px;
    text-align: center;
    font-size: 11px;
    color: #d1d5db;
}

/* Responsive */
@media (max-width: 960px) {
    .page { grid-template-columns: 1fr; }
    .panel-right { display: none; }
    .panel-left { padding: 32px 24px; }
}
@media (max-width: 520px) {
    .field-grid-2, .field-grid-3 { grid-template-columns: 1fr; }
    .type-selector { grid-template-columns: 1fr; }
}
</style>

<div class="page">

    <!-- Left: Form  -->
    <div class="panel-left">
        <div class="form-wrap">

            <div class="form-brand">
                <img src="{{ asset('assets/img/logo_bps.png') }}" alt="Logo" class="form-brand-logo">
                <span class="form-brand-name">SI-PRABU</span>
            </div>

            <div class="form-heading">
                <h1>Buat Akun Baru</h1>
                <p>Pilih tipe akun Anda, lalu lengkapi data untuk mendaftar.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="regForm">
                @csrf

                {{-- TYPE SELECTOR --}}
                <div class="type-selector">
                    {{-- BPS --}}
                    <div class="type-card">
                        <input type="radio" id="t_bps" name="is_bps" value="1"
                            {{ old('is_bps', '1') == '1' ? 'checked' : '' }}
                            onchange="onTypeChange(1)">
                        <label for="t_bps">
                            <div class="type-check"><i class="bi bi-check"></i></div>
                            <div class="type-card-icon"><i class="bi bi-building-fill"></i></div>
                            <div class="type-card-title">Pegawai BPS</div>
                            <div class="type-card-sub">Kabupaten Kediri</div>
                        </label>
                    </div>
                    {{-- Non-BPS --}}
                    <div class="type-card">
                        <input type="radio" id="t_non" name="is_bps" value="0"
                            {{ old('is_bps') == '0' ? 'checked' : '' }}
                            onchange="onTypeChange(0)">
                        <label for="t_non">
                            <div class="type-check"><i class="bi bi-check"></i></div>
                            <div class="type-card-icon"><i class="bi bi-person-badge-fill"></i></div>
                            <div class="type-card-title">Non-BPS</div>
                            <div class="type-card-sub">Instansi lain</div>
                        </label>
                    </div>
                </div>

                {{-- SEKSI PEGAWAI BPS --}}
                <div class="collapsible" id="sec-bps" style="max-height:9999px">

                    <div class="section-head">
                        <div class="section-head-line"></div>
                        <span class="section-head-label"><i class="bi bi-person-vcard"></i> &nbsp;Data Kepegawaian</span>
                        <div class="section-head-line"></div>
                    </div>

                    {{-- NIP Lama & NIP Baru --}}
                    <div class="field-grid-2">
                        <div class="field">
                            <label class="field-label">NIP Lama</label>
                            <div class="field-wrap">
                                <i class="bi bi-fingerprint field-ico"></i>
                                <input type="text" name="nip_lama"
                                    class="field-input {{ $errors->get('nip_lama') ? 'err' : '' }}"
                                    value="{{ old('nip_lama') }}" placeholder="NIP Lama" autocomplete="off">
                            </div>
                            <x-input-error :messages="$errors->get('nip_lama')" class="field-error" />
                        </div>
                        <div class="field">
                            <label class="field-label">NIP Baru</label>
                            <div class="field-wrap">
                                <i class="bi bi-fingerprint field-ico"></i>
                                <input type="text" name="nip_baru"
                                    class="field-input {{ $errors->get('nip_baru') ? 'err' : '' }}"
                                    value="{{ old('nip_baru') }}" placeholder="NIP Baru">
                            </div>
                            <x-input-error :messages="$errors->get('nip_baru')" class="field-error" />
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div class="field">
                        <label class="field-label">Nama Lengkap</label>
                        <div class="field-wrap">
                            <i class="bi bi-person field-ico"></i>
                            <input type="text" name="nama"
                                class="field-input {{ $errors->get('nama') ? 'err' : '' }}"
                                value="{{ old('nama') }}" placeholder="Sesuai dokumen kepegawaian">
                        </div>
                        <x-input-error :messages="$errors->get('nama')" class="field-error" />
                    </div>

                    {{-- Jabatan & Golongan --}}
                    <div class="field-grid-2">
                        <div class="field">
                            <label class="field-label">Jabatan</label>
                            <div class="field-wrap">
                                <i class="bi bi-briefcase field-ico"></i>
                                <input type="text" name="jabatan"
                                    class="field-input {{ $errors->get('jabatan') ? 'err' : '' }}"
                                    value="{{ old('jabatan') }}" placeholder="Jabatan saat ini">
                            </div>
                            <x-input-error :messages="$errors->get('jabatan')" class="field-error" />
                        </div>
                        <div class="field">
                            <label class="field-label">Golongan Akhir</label>
                            <div class="field-wrap">
                                <i class="bi bi-award field-ico"></i>
                                <input type="text" name="golongan_akhir"
                                    class="field-input {{ $errors->get('golongan_akhir') ? 'err' : '' }}"
                                    value="{{ old('golongan_akhir') }}" placeholder="Cth: III/c">
                            </div>
                            <x-input-error :messages="$errors->get('golongan_akhir')" class="field-error" />
                        </div>
                    </div>

                    {{-- Tamat Gol & Pendidikan --}}
                    <div class="field-grid-2">
                        <div class="field">
                            <label class="field-label">Tamat Golongan</label>
                            <div class="field-wrap">
                                <i class="bi bi-calendar-check field-ico"></i>
                                <input type="date" name="tamat_gol"
                                    class="field-input {{ $errors->get('tamat_gol') ? 'err' : '' }}"
                                    value="{{ old('tamat_gol') }}">
                            </div>
                            <x-input-error :messages="$errors->get('tamat_gol')" class="field-error" />
                        </div>
                        <div class="field">
                            <label class="field-label">Pendidikan</label>
                            <div class="field-wrap">
                                <i class="bi bi-mortarboard field-ico"></i>
                                <select name="pendidikan" class="field-select {{ $errors->get('pendidikan') ? 'err' : '' }}">
                                    <option value="">-- Pilih --</option>
                                    @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $p)
                                        <option value="{{ $p }}" {{ old('pendidikan') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down field-select-arrow"></i>
                            </div>
                            <x-input-error :messages="$errors->get('pendidikan')" class="field-error" />
                        </div>
                    </div>

                    {{-- Tanggal Lulus & Jenis Kelamin --}}
                    <div class="field-grid-2">
                        <div class="field">
                            <label class="field-label">Tanggal Lulus</label>
                            <div class="field-wrap">
                                <i class="bi bi-calendar-event field-ico"></i>
                                <input type="date" name="tanggal_lulus"
                                    class="field-input {{ $errors->get('tanggal_lulus') ? 'err' : '' }}"
                                    value="{{ old('tanggal_lulus') }}">
                            </div>
                            <x-input-error :messages="$errors->get('tanggal_lulus')" class="field-error" />
                        </div>
                        <div class="field">
                            <label class="field-label">Jenis Kelamin</label>
                            <div class="field-wrap">
                                <i class="bi bi-gender-ambiguous field-ico"></i>
                                <select name="jenis_kelamin" class="field-select {{ $errors->get('jenis_kelamin') ? 'err' : '' }}">
                                    <option value="">-- Pilih --</option>
                                    <option value="LK" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="PR" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <i class="bi bi-chevron-down field-select-arrow"></i>
                            </div>
                            <x-input-error :messages="$errors->get('jenis_kelamin')" class="field-error" />
                        </div>
                    </div>

                    {{-- Tim --}}
                    <div class="field">
                        <label class="field-label">Tim / Seksi</label>
                        <div class="field-wrap">
                            <i class="bi bi-people field-ico"></i>
                            <select name="tim_id" id="tim_bps" class="field-select {{ $errors->get('tim_id') ? 'err' : '' }}">
                                <option value="">-- Pilih Tim --</option>
                                @foreach($tims as $tim)
                                    @if($tim->nama_tim !== 'Non-BPS')
                                        <option value="{{ $tim->id }}" {{ old('tim_id') == $tim->id ? 'selected' : '' }}>
                                            {{ $tim->nama_tim }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down field-select-arrow"></i>
                        </div>
                        <x-input-error :messages="$errors->get('tim_id')" class="field-error" />
                    </div>

                    {{-- Email BPS --}}
                    <div class="field">
                        <label class="field-label">Email</label>
                        <div class="field-wrap">
                            <i class="bi bi-envelope field-ico"></i>
                            <input type="email" name="email" id="email_bps"
                                class="field-input {{ $errors->get('email') ? 'err' : '' }}"
                                value="{{ old('email') }}" placeholder="email@bps.go.id">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="field-error" />
                    </div>

                </div>
                {{-- END BPS --}}


                {{-- SEKSI NON-BPS --}}
                <div class="collapsible hidden" id="sec-non" style="max-height:0">

                    <div class="section-head">
                        <div class="section-head-line"></div>
                        <span class="section-head-label"><i class="bi bi-person"></i> &nbsp;Data Diri</span>
                        <div class="section-head-line"></div>
                    </div>

                    <div class="field">
                        <label class="field-label">Nama Lengkap</label>
                        <div class="field-wrap">
                            <i class="bi bi-person field-ico"></i>
                            <input type="text" name="nama_nonbps" id="nama_nonbps"
                                class="field-input {{ $errors->get('nama_nonbps') ? 'err' : '' }}"
                                value="{{ old('nama_nonbps') }}" placeholder="Nama lengkap Anda">
                        </div>
                        <x-input-error :messages="$errors->get('nama_nonbps')" class="field-error" />
                    </div>

                    <div class="field-grid-2">
                        <div class="field">
                            <label class="field-label">Jenis Kelamin</label>
                            <div class="field-wrap">
                                <i class="bi bi-gender-ambiguous field-ico"></i>
                                <select name="jenis_kelamin_nonbps" id="jk_non"
                                    class="field-select {{ $errors->get('jenis_kelamin_nonbps') ? 'err' : '' }}">
                                    <option value="">-- Pilih --</option>
                                    <option value="LK" {{ old('jenis_kelamin_nonbps') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="PR" {{ old('jenis_kelamin_nonbps') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <i class="bi bi-chevron-down field-select-arrow"></i>
                            </div>
                            <x-input-error :messages="$errors->get('jenis_kelamin_nonbps')" class="field-error" />
                        </div>
                        <div class="field">
                            <label class="field-label">Email</label>
                            <div class="field-wrap">
                                <i class="bi bi-envelope field-ico"></i>
                                <input type="email" name="email_nonbps" id="email_non"
                                    class="field-input {{ $errors->get('email_nonbps') ? 'err' : '' }}"
                                    value="{{ old('email_nonbps') }}" placeholder="email@example.com">
                            </div>
                            <x-input-error :messages="$errors->get('email_nonbps')" class="field-error" />
                        </div>
                    </div>

                </div>
                {{-- END NON-BPS --}}


                {{-- Data Akun (shared) --}}
                <div class="section-head">
                    <div class="section-head-line"></div>
                    <span class="section-head-label"><i class="bi bi-shield-lock"></i> &nbsp;Data Akun</span>
                    <div class="section-head-line"></div>
                </div>

                <div class="field">
                    <label class="field-label">Username</label>
                    <div class="field-wrap">
                        <i class="bi bi-at field-ico"></i>
                        <input type="text" name="username"
                            class="field-input {{ $errors->get('username') ? 'err' : '' }}"
                            value="{{ old('username') }}" placeholder="username" required>
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="field-error" />
                </div>

                <div class="field-grid-2">
                    <div class="field">
                        <label class="field-label">Password</label>
                        <div class="field-wrap">
                            <i class="bi bi-lock field-ico"></i>
                            <input type="password" id="pw1" name="password"
                                class="field-input {{ $errors->get('password') ? 'err' : '' }}"
                                placeholder="Min. 8 karakter" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('pw1','ic1')">
                                <i id="ic1" class="bi bi-eye"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="field-error" />
                    </div>
                    <div class="field">
                        <label class="field-label">Konfirmasi Password</label>
                        <div class="field-wrap">
                            <i class="bi bi-lock-fill field-ico"></i>
                            <input type="password" id="pw2" name="password_confirmation"
                                class="field-input" placeholder="Ulangi password" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('pw2','ic2')">
                                <i id="ic2" class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Foto Profil --}}
                <div class="field">
                    <label class="field-label">Foto Profil <span>(wajib · JPG/PNG maks. 2MB)</span></label>
                    <div class="photo-box" onclick="document.getElementById('photoFile').click()">
                        <div class="photo-avatar">
                            <img id="photoPreview" src="" alt="">
                            <i class="bi bi-person" id="photoIco"></i>
                        </div>
                        <div class="photo-text">
                            <div class="t1" id="photoName">Pilih foto profil</div>
                            <div class="t2">Klik untuk upload file</div>
                        </div>
                        <button type="button" class="photo-cta">Browse</button>
                        <input type="file" id="photoFile" name="photo"
                            accept="image/*" style="display:none"
                            onchange="onPhoto(this)" required>
                    </div>
                    <x-input-error :messages="$errors->get('photo')" class="field-error" />
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-person-check-fill"></i>
                    Daftar Sekarang
                </button>
            </form>

            <div class="to-login">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
            <div class="footer-note">© {{ date('Y') }} SI-PRABU · BPS Kabupaten Kediri</div>

        </div>
    </div>

    <!-- Right: Branding -->
    <div class="panel-right">
        <div class="panel-right-content">

            <div class="brand">
                <img src="{{ asset('assets/img/logo_bps.png') }}" alt="Logo" class="brand-logo">
                <span class="brand-name">SI-PRABU</span>
            </div>

            <div class="panel-badge">Sistem Informasi</div>

            <div class="panel-title">
                Sistem Informasi<br>
                <em>Perhitungan & Simulasi</em><br>
                PDRB Kab. Kediri
            </div>

            <p class="panel-desc">
                Platform pengambilan keputusan untuk analisis dan simulasi pertumbuhan ekonomi Kabupaten Kediri berbasis data PDRB.
            </p>

            <div class="panel-stats">
                <div class="stat">
                    <div class="stat-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-lbl">Fitur Utama</div>
                        <div class="stat-val">Pembentukan & Finalisasi PDRB</div>
                    </div>
                </div>
                <div class="stat">
                    <div class="stat-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-lbl">Data Pendukung</div>
                        <div class="stat-val">Harga, Produksi, Rasio, IHP, WIP</div>
                    </div>
                </div>
                <div class="stat">
                    <div class="stat-icon">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 15l6 6M4 4l5 5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-lbl">Unggulan</div>
                        <div class="stat-val">Simulasi Input Output Sektoral</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- SweetAlert: NIP sudah punya akun --}}
@if(session('nip_exists'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Akun Sudah Terdaftar',
        html: `NIP <strong>{{ session('nip_exists') }}</strong> sudah memiliki akun di sistem.<br><small style="color:#6b7280">Silakan gunakan akun yang sudah ada untuk masuk.</small>`,
        confirmButtonText: 'Masuk Sekarang',
        confirmButtonColor: '#f97316',
        showCancelButton: true,
        cancelButtonText: 'Kembali',
        cancelButtonColor: '#9ca3af',
        reverseButtons: true,
    }).then(r => { if (r.isConfirmed) window.location.href = '{{ route('login') }}'; });
</script>
@endif

<script>
    // Type selector 
    function onTypeChange(isBps) {
        const secBps = document.getElementById('sec-bps');
        const secNon = document.getElementById('sec-non');
        const timBps = document.getElementById('tim_bps');
        const emBps  = document.getElementById('email_bps');
        const nmNon  = document.getElementById('nama_nonbps');
        const jkNon  = document.getElementById('jk_non');
        const emNon  = document.getElementById('email_non');

        if (isBps) {
            secBps.style.maxHeight = '9999px'; secBps.classList.remove('hidden');
            secNon.style.maxHeight = '0';      secNon.classList.add('hidden');
            req([timBps, emBps], true);
            req([nmNon, jkNon, emNon], false);
        } else {
            secBps.style.maxHeight = '0';      secBps.classList.add('hidden');
            secNon.style.maxHeight = '9999px'; secNon.classList.remove('hidden');
            req([nmNon, jkNon, emNon], true);
            req([timBps, emBps], false);
        }
    }

    function req(els, on) {
        els.forEach(el => { if (!el) return; on ? el.setAttribute('required','') : el.removeAttribute('required'); });
    }

    // Password toggle 
    function togglePw(id, ico) {
        const el = document.getElementById(id);
        el.type  = el.type === 'password' ? 'text' : 'password';
        const i = document.getElementById(ico);
        i.classList.toggle('bi-eye');
        i.classList.toggle('bi-eye-slash');
    }

    // Photo preview 
    function onPhoto(input) {
        if (!input.files[0]) return;
        const r = new FileReader();
        r.onload = e => {
            const img = document.getElementById('photoPreview');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('photoIco').style.display = 'none';
            document.getElementById('photoName').textContent  = input.files[0].name;
        };
        r.readAsDataURL(input.files[0]);
    }

    // Init
    document.addEventListener('DOMContentLoaded', () => {
        const checked = document.querySelector('input[name="is_bps"]:checked');
        if (checked) onTypeChange(parseInt(checked.value));
    });
</script>

</x-guest-layout>