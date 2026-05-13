@extends('layouts/app')
@section('stylecss')
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; }
    body, .page-inner { font-family: 'Figtree', sans-serif; }

    /* Layout */
    .profile-wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 24px 16px 60px;
    }

    /* Hero Card */
    .hero-card {
        position: relative;
        background: linear-gradient(135deg, #7c2d12 0%, #ea580c 55%, #fb923c 100%);
        border-radius: 16px;
        padding: 36px 36px 28px;
        margin-bottom: 24px;
        color: #fff;
    }
    .hero-deco {
        position: absolute;
        inset: 0;
        border-radius: 16px;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    }
    .hero-deco::before {
        content: '';
        position: absolute;
        top: -70px; right: -50px;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .hero-deco::after {
        content: '';
        position: absolute;
        bottom: -50px; left: 100px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 28px;
    }

    /* Avatar */
    .avatar-wrap {
        flex-shrink: 0;
        position: relative;
        width: 96px; height: 96px;
    }
    .avatar-wrap img {
        width: 96px; height: 96px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,.85);
        box-shadow: 0 4px 16px rgba(0,0,0,.25);
        display: block;
    }
    .avatar-edit-btn {
        position: absolute;
        bottom: 2px; right: 2px;
        width: 28px; height: 28px;
        background: rgba(0,0,0,.45);
        border: 2px solid #fff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .2s;
    }
    .avatar-edit-btn:hover { background: rgba(0,0,0,.65); }
    .avatar-edit-btn svg { width: 11px; height: 11px; fill: #fff; }

    /* Hero text */
    .hero-info {
        padding-bottom: 0;
        flex: 1;
    }
    .hero-info h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 4px;
        letter-spacing: -.2px;
    }
    .hero-info .nip-badge {
        display: inline-block;
        background: rgba(255,255,255,.2);
        backdrop-filter: blur(4px);
        border-radius: 20px;
        padding: 2px 12px;
        font-size: .78rem;
        font-weight: 500;
        letter-spacing: .3px;
        margin-bottom: 10px;
    }
    .meta-chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .meta-chip {
        background: rgba(255,255,255,.15);
        border-radius: 6px;
        padding: 3px 10px;
        font-size: .76rem;
        font-weight: 500;
    }

    /* Tab Bar */
    .tab-bar {
        display: flex;
        gap: 2px;
        border-bottom: 0.5px solid #e5e7eb;
        margin-bottom: 24px;
    }
    .tab-btn {
        background: none;
        border: none;
        padding: 10px 18px;
        font-family: inherit;
        font-size: .85rem;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -0.5px;
        transition: color .15s, border-color .15s;
        display: flex; align-items: center; gap: 6px;
        white-space: nowrap;
    }
    .tab-btn:hover { color: #f97316; }
    .tab-btn.active { color: #f97316; border-bottom-color: #f97316; }
    .tab-btn svg { width: 14px; height: 14px; }

    /* Tab panels */
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Section Card */
    .section-card {
        background: #fff;
        border-radius: 12px;
        border: 0.5px solid #e5e7eb;
        overflow: hidden;
    }
    .section-card + .section-card { margin-top: 16px; }

    .section-head {
        padding: 16px 24px;
        border-bottom: 0.5px solid #e5e7eb;
        display: flex; align-items: center; justify-content: space-between;
        background: #fafafa;
    }
    .section-head h3 {
        font-size: .9rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
        display: flex; align-items: center; gap: 7px;
    }
    .section-head h3 svg { color: #f97316; }
    .section-head p { margin: 0; font-size: .77rem; color: #9ca3af; margin-top: 2px; }

    .section-body { padding: 22px 24px; }

    /* Detail View (read mode) */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    .detail-item {
        padding: 12px 16px;
        border-bottom: 0.5px solid #f3f4f6;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .detail-item:nth-child(odd) { border-right: 0.5px solid #f3f4f6; }
    .detail-item.full { grid-column: 1 / -1; border-right: none; }
    .detail-label {
        font-size: .72rem;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .detail-value {
        font-size: .88rem;
        font-weight: 500;
        color: #111827;
    }
    .detail-value.empty { color: #d1d5db; font-style: italic; }
    .detail-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #fff7ed;
        border: 0.5px solid #fed7aa;
        color: #c2410c;
        border-radius: 100px;
        padding: 2px 10px;
        font-size: .76rem;
        font-weight: 600;
    }

    /* Form grid (edit mode) */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .form-grid .full { grid-column: 1 / -1; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group label {
        font-size: .72rem;
        font-weight: 600;
        color: #374151;
        letter-spacing: .3px;
        text-transform: uppercase;
    }
    .form-group input,
    .form-group select {
        padding: 9px 12px;
        border: 0.5px solid #d1d5db;
        border-radius: 8px;
        font-family: inherit;
        font-size: .875rem;
        color: #111827;
        background: #fff;
        transition: border-color .15s, box-shadow .15s;
        outline: none;
        height: 38px;
    }
    .form-group input:focus,
    .form-group select:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,.08);
    }
    .form-group input.is-invalid,
    .form-group select.is-invalid { border-color: #ef4444; }
    .form-group input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
    .invalid-feedback { font-size: .75rem; color: #ef4444; margin-top: 2px; }
    .field-readonly {
        padding: 9px 12px;
        background: #f3f4f6;
        border: 0.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: .875rem;
        color: #6b7280;
        cursor: not-allowed;
        height: 38px;
        display: flex;
        align-items: center;
    }

    /* Buttons */
    .btn-primary {
        background: #f97316;
        color: #fff;
        border: none;
        padding: 0 22px;
        height: 36px;
        border-radius: 8px;
        font-family: inherit;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background .15s, box-shadow .15s, transform .1s;
        box-shadow: 0 2px 8px rgba(249,115,22,.3);
    }
    .btn-primary:hover { background: #ea580c; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(249,115,22,.35); }
    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 0.5px solid #e5e7eb;
        padding: 0 18px;
        height: 36px;
        border-radius: 8px;
        font-family: inherit;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background .12s;
    }
    .btn-secondary:hover { background: #e5e7eb; }
    .btn-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 0.5px solid #fca5a5;
        padding: 0 16px;
        height: 36px;
        border-radius: 8px;
        font-family: inherit;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background .12s;
    }
    .btn-danger:hover { background: #fee2e2; }
    .btn-edit-inline {
        background: #fff7ed;
        color: #c2410c;
        border: 0.5px solid #fed7aa;
        padding: 0 14px;
        height: 32px;
        border-radius: 7px;
        font-family: inherit;
        font-size: .78rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 5px;
        transition: background .12s;
    }
    .btn-edit-inline:hover { background: #ffedd5; border-color: #f97316; }
    .btn-edit-inline svg { width: 12px; height: 12px; }

    /* Edit mode toggle */
    .view-mode  { display: block; }
    .edit-mode  { display: none; }
    body.editing .view-mode { display: none; }
    body.editing .edit-mode  { display: block; }

    /* Photo upload */
    .photo-upload-area {
        display: flex;
        align-items: center;
        gap: 24px;
        padding: 20px 24px;
        background: #fafafa;
        border-radius: 10px;
        border: 0.5px dashed #d1d5db;
        transition: border-color .2s;
    }
    .photo-upload-area:hover { border-color: #f97316; }
    .photo-current {
        width: 72px; height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }
    .photo-file-input { display: none; }
    .photo-label {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff;
        border: 0.5px solid #d1d5db;
        padding: 0 16px;
        height: 34px;
        border-radius: 8px;
        font-size: .82rem;
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        transition: border-color .2s, color .2s;
    }
    .photo-label:hover { border-color: #f97316; color: #f97316; }
    .photo-label svg { width: 13px; height: 13px; }

    /* Password strength */
    .strength-bar { display: flex; gap: 4px; margin-top: 5px; }
    .strength-seg { flex: 1; height: 3px; background: #e5e7eb; border-radius: 2px; transition: background .3s; }
    .strength-seg.filled-weak   { background: #ef4444; }
    .strength-seg.filled-medium { background: #f59e0b; }
    .strength-seg.filled-strong { background: #10b981; }
    .strength-label { font-size: .72rem; color: #9ca3af; margin-top: 3px; }

    /* Alert */
    .alert-success-custom {
        background: #f0fdf4;
        border: 0.5px solid #bbf7d0;
        border-radius: 8px;
        padding: 10px 16px;
        color: #166534;
        font-size: .82rem;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 16px;
    }

    /* Info box */
    .info-box {
        display:flex; align-items:flex-start; gap:8px; padding:10px 14px;
        border-radius:8px; margin-bottom:12px; font-size:12px; line-height:1.55;
    }
    .info-box svg { flex-shrink:0; margin-top:1px; }
    .info-box.orange { background:#fff7ed; border:0.5px solid #fed7aa; color:#c2410c; }

    /* Responsive */
    @media (max-width: 700px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-grid .full { grid-column: auto; }
        .detail-grid { grid-template-columns: 1fr; }
        .detail-item:nth-child(odd) { border-right: none; }
        .detail-item.full { grid-column: auto; }
        .hero-inner { flex-direction: column; align-items: flex-start; gap: 12px; }
        .hero-card { padding: 24px 20px 0; }
        .hero-info { padding-bottom: 0; }
        .section-body, .section-head { padding-left: 16px; padding-right: 16px; }
    }
</style>
@endsection

@section('content')
<div class="profile-wrapper">

    {{-- Hero Card --}}
    <div class="hero-card">
        <div class="hero-deco"></div>
        <div class="hero-inner">
            <div class="avatar-wrap" id="hero-avatar-wrap">
                <img src="{{ !Auth::user()->photo
                    ? 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'
                    : asset('/storage/'.Auth::user()->photo) }}"
                    alt="Foto Profil" id="hero-avatar-img" />
                <label class="avatar-edit-btn" for="photo_input" title="Ganti foto">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.21a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                    </svg>
                </label>
            </div>
            <div class="hero-info">
                <h2>{{ $pegawai->nama }}</h2>
                @php $isNonBps = Auth::user()->tim_id == 10; @endphp
                @if($isNonBps)
                    {{-- Non-BPS: hanya nama, badge Non-BPS, role --}}
                    <span class="nip-badge" style="background:rgba(255,255,255,.25);">Non-BPS</span>
                    <div class="meta-chips">
                        <span class="meta-chip text-capitalize">{{ Auth::user()->role->role ?? '—' }}</span>
                    </div>
                @else
                    <span class="nip-badge">NIP {{ Auth::user()->nip_lama }}</span>
                    <div class="meta-chips">
                        <span class="meta-chip">{{ $pegawai->jabatan ?? '—' }}</span>
                        <span class="meta-chip">Gol. {{ $pegawai->golongan_akhir ?? '—' }}</span>
                        <span class="meta-chip text-capitalize">{{ Auth::user()->role->role ?? '—' }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tab Bar --}}
    <div class="tab-bar">
        <button class="tab-btn active" data-tab="profile">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Data Profil
        </button>
        <button class="tab-btn" data-tab="photo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            Foto Profil
        </button>
        <button class="tab-btn" data-tab="password">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Ganti Password
        </button>
    </div>

    {{-- TAB: PROFILE                               --}}
    <div class="tab-panel active" id="tab-profile">
        <div class="section-card">
            <div class="section-head">
                <div>
                    <h3>
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        Data Profil
                    </h3>
                    <p>Informasi akun dan data kepegawaian Anda</p>
                </div>
                {{-- Edit button (view mode) --}}
                <button class="btn-edit-inline view-mode" id="btn-start-edit" onclick="startEdit()">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit Profil
                </button>
            </div>

            {{-- VIEW MODE --}}
            <div class="view-mode" id="view-profile">
                @if(session('success') && session('activeTab') === 'profile')
                <div style="padding: 16px 24px 0;">
                    <div class="alert-success-custom">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Nama Lengkap</span>
                        <span class="detail-value">{{ $pegawai->nama ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Username</span>
                        <span class="detail-value">{{ Auth::user()->username ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">{{ Auth::user()->email ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Jenis Kelamin</span>
                        <span class="detail-value">
                            @if($pegawai->jenis_kelamin === 'LK') Laki-laki
                            @elseif($pegawai->jenis_kelamin === 'PR') Perempuan
                            @else <span class="empty">Belum diisi</span>
                            @endif
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">NIP Lama</span>
                        <span class="detail-value">{{ Auth::user()->nip_lama ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">NIP Baru</span>
                        <span class="detail-value">{{ $pegawai->nip_baru ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Jabatan</span>
                        <span class="detail-value">{{ $pegawai->jabatan ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Golongan Akhir</span>
                        <span class="detail-value">{{ $pegawai->golongan_akhir ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tamat Golongan</span>
                        <span class="detail-value">
                            {{ $pegawai->tamat_gol ? \Carbon\Carbon::parse($pegawai->tamat_gol)->format('d M Y') : '—' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Pendidikan Terakhir</span>
                        <span class="detail-value">{{ $pegawai->pendidikan ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Lulus</span>
                        <span class="detail-value">
                            {{ $pegawai->tanggal_lulus ? \Carbon\Carbon::parse($pegawai->tanggal_lulus)->format('d M Y') : '—' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Role</span>
                        <span class="detail-value">
                            <span class="detail-badge text-capitalize">{{ Auth::user()->role->role ?? '—' }}</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- EDIT MODE --}}
            <div class="edit-mode" id="edit-profile">
                <div class="section-body">
                    @if(session('success') && session('activeTab') === 'profile')
                    <div class="alert-success-custom">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" id="form-profile">
                        @csrf
                        @method('PATCH')

                        @if(Auth::user()->tim_id == 10)
                        <div class="info-box orange" style="margin-bottom:20px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div>
                                <strong>Akun Non-BPS.</strong> Anda hanya dapat mengubah <strong>Nama Lengkap, Username, Email,</strong> dan <strong>Jenis Kelamin</strong>. Data kepegawaian (NIP, Jabatan, Golongan, dll.) tidak tersedia untuk akun Non-BPS.
                            </div>
                        </div>
                        @endif

                        <div class="form-grid">
                            {{-- Nama Lengkap — selalu bisa diedit --}}
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" value="{{ old('nama', $pegawai->nama) }}"
                                    class="@error('nama') is-invalid @enderror" placeholder="Nama lengkap" />
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Username — selalu bisa diedit --}}
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}"
                                    class="@error('username') is-invalid @enderror" placeholder="Username" />
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Email — selalu bisa diedit --}}
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                    class="@error('email') is-invalid @enderror" placeholder="email@example.com" />
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Jenis Kelamin — selalu bisa diedit --}}
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="@error('jenis_kelamin') is-invalid @enderror">
                                    <option value="">— Pilih —</option>
                                    <option value="LK" {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') === 'LK' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="PR" {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') === 'PR' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Field kepegawaian — readonly untuk Non-BPS --}}
                            <div class="form-group">
                                <label>NIP Lama</label>
                                <div class="field-readonly" title="{{ Auth::user()->tim_id == 10 ? 'Tidak tersedia untuk akun Non-BPS' : 'NIP Lama tidak dapat diubah' }}">
                                    {{ Auth::user()->nip_lama ?: '—' }}
                                </div>
                                <span style="font-size:.7rem;color:#9ca3af;margin-top:3px;">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ Auth::user()->tim_id == 10 ? 'Tidak tersedia untuk Non-BPS' : 'NIP Lama tidak dapat diubah' }}
                                </span>
                            </div>

                            <div class="form-group">
                                <label>NIP Baru</label>
                                @if(Auth::user()->tim_id == 10)
                                    <div class="field-readonly">—</div>
                                @else
                                    <input type="text" name="nip_baru" value="{{ old('nip_baru', $pegawai->nip_baru) }}"
                                        class="@error('nip_baru') is-invalid @enderror" placeholder="NIP Baru (18 digit)" />
                                    @error('nip_baru')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Jabatan</label>
                                @if(Auth::user()->tim_id == 10)
                                    <div class="field-readonly">—</div>
                                @else
                                    <input type="text" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}"
                                        class="@error('jabatan') is-invalid @enderror" placeholder="Jabatan" />
                                    @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Golongan Akhir</label>
                                @if(Auth::user()->tim_id == 10)
                                    <div class="field-readonly">—</div>
                                @else
                                    <input type="text" name="golongan_akhir" value="{{ old('golongan_akhir', $pegawai->golongan_akhir) }}"
                                        class="@error('golongan_akhir') is-invalid @enderror" placeholder="Contoh: III/d" />
                                    @error('golongan_akhir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Tamat Golongan</label>
                                @if(Auth::user()->tim_id == 10)
                                    <div class="field-readonly">—</div>
                                @else
                                    <input type="date" name="tamat_gol" value="{{ old('tamat_gol', $pegawai->tamat_gol) }}"
                                        class="@error('tamat_gol') is-invalid @enderror" />
                                    @error('tamat_gol')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Pendidikan Terakhir</label>
                                @if(Auth::user()->tim_id == 10)
                                    <div class="field-readonly">—</div>
                                @else
                                    <select name="pendidikan" class="@error('pendidikan') is-invalid @enderror">
                                        <option value="">— Pilih —</option>
                                        @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','D4','S1','S2','S3'] as $p)
                                        <option value="{{ $p }}" {{ old('pendidikan', $pegawai->pendidikan ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                    @error('pendidikan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Tanggal Lulus</label>
                                @if(Auth::user()->tim_id == 10)
                                    <div class="field-readonly">—</div>
                                @else
                                    <input type="date" name="tanggal_lulus" value="{{ old('tanggal_lulus', $pegawai->tanggal_lulus) }}"
                                        class="@error('tanggal_lulus') is-invalid @enderror" />
                                    @error('tanggal_lulus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                <div class="field-readonly text-capitalize">{{ Auth::user()->role->role ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn-danger" onclick="cancelEdit()">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Batal
                            </button>
                            <button type="reset" class="btn-secondary">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                                Reset
                            </button>
                            <button type="submit" class="btn-primary">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB: FOTO                                  --}}
    <div class="tab-panel" id="tab-photo">
        <div class="section-card">
            <div class="section-head">
                <div>
                    <h3>
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        Foto Profil
                    </h3>
                    <p>Format JPG, PNG, atau GIF · Maks. 2 MB</p>
                </div>
            </div>
            <div class="section-body">
                @if(session('success') && session('activeTab') === 'photo')
                <div class="alert-success-custom">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('edit.profile') }}" enctype="multipart/form-data" id="photo-form">
                    @csrf
                    <div class="photo-upload-area">
                        <img src="{{ !Auth::user()->photo
                            ? 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'
                            : asset('/storage/'.Auth::user()->photo) }}"
                            class="photo-current" id="photo-preview" alt="Foto saat ini" />
                        <div class="photo-actions" style="flex:1;">
                            <p id="photo-filename" style="margin:0 0 8px;font-size:.82rem;color:#6b7280;">Belum ada file yang dipilih</p>
                            <label class="photo-label" for="photo_input">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Pilih Foto
                            </label>
                            <input type="file" name="photo" id="photo_input" class="photo-file-input"
                                accept="image/jpeg,image/png,image/gif" />
                            @error('photo')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn-primary" id="upload-btn" disabled
                            style="opacity:.5; cursor:not-allowed;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Upload Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TAB: PASSWORD                              --}}
    <div class="tab-panel" id="tab-password">
        <div class="section-card">
            <div class="section-head">
                <div>
                    <h3>
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        Ganti Password
                    </h3>
                    <p>Gunakan password yang kuat dan unik</p>
                </div>
            </div>
            <div class="section-body">
                @if(session('success') && session('activeTab') === 'password')
                <div class="alert-success-custom">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.change') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid" style="max-width: 560px;">
                        <div class="form-group full">
                            <label>Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password"
                                class="@error('current_password', 'updatePassword') is-invalid @enderror"
                                autocomplete="current-password" placeholder="••••••••" />
                            @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full">
                            <label>Password Baru</label>
                            <input type="password" name="password" id="new_password"
                                class="@error('password', 'updatePassword') is-invalid @enderror"
                                autocomplete="new-password" placeholder="Min. 8 karakter" />
                            @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="strength-bar">
                                <div class="strength-seg" id="seg1"></div>
                                <div class="strength-seg" id="seg2"></div>
                                <div class="strength-seg" id="seg3"></div>
                                <div class="strength-seg" id="seg4"></div>
                            </div>
                            <div class="strength-label" id="strength-label">Masukkan password baru</div>
                        </div>

                        <div class="form-group full">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="confirm_password"
                                class="@error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                autocomplete="new-password" placeholder="Ulangi password baru" />
                            @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="reset" class="btn-secondary">Reset</button>
                        <button type="submit" class="btn-primary">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* Tabs */
    const tabBtns   = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    function activateTab(tabId) {
        tabBtns.forEach(b   => b.classList.toggle('active', b.dataset.tab === tabId));
        tabPanels.forEach(p => p.classList.toggle('active', p.id === 'tab-' + tabId));
    }

    const activeTab = "{{ session('activeTab', 'profile') }}";
    activateTab(activeTab);
    tabBtns.forEach(btn => btn.addEventListener('click', () => activateTab(btn.dataset.tab)));

    /* Edit / View toggle */
    // Auto-open edit mode if there were validation errors
    @if($errors->any() && session('activeTab') === 'profile')
    startEdit();
    @endif

    /* Photo preview */
    function handlePhotoChange(file) {
        if (!file) return;
        const url = URL.createObjectURL(file);
        document.getElementById('photo-preview').src = url;
        document.getElementById('hero-avatar-img').src = url;
        document.getElementById('photo-filename').textContent = file.name;
        const uploadBtn = document.getElementById('upload-btn');
        uploadBtn.disabled = false;
        uploadBtn.style.opacity = '1';
        uploadBtn.style.cursor  = 'pointer';
        activateTab('photo');
    }

    document.getElementById('photo_input').addEventListener('change', function () {
        handlePhotoChange(this.files[0]);
    });

    /* Password strength */
    const newPw  = document.getElementById('new_password');
    const segs   = ['seg1','seg2','seg3','seg4'].map(id => document.getElementById(id));
    const slabel = document.getElementById('strength-label');

    newPw?.addEventListener('input', function () {
        const val = this.value;
        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        segs.forEach((s, i) => {
            s.className = 'strength-seg';
            if (i < score) {
                if      (score <= 1) s.classList.add('filled-weak');
                else if (score <= 2) s.classList.add('filled-medium');
                else                 s.classList.add('filled-strong');
            }
        });

        const labels = ['', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
        slabel.textContent = val.length ? (labels[score] || 'Lemah') : 'Masukkan password baru';
    });

    /* SweetAlert */
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#f97316',
        confirmButtonText: 'OK',
    });
    @endif
});

/* Edit/Cancel exposed globally */
function startEdit() {
    document.getElementById('view-profile').style.display  = 'none';
    document.getElementById('edit-profile').style.display  = 'block';
    document.getElementById('btn-start-edit').style.display = 'none';
}

function cancelEdit() {
    document.getElementById('view-profile').style.display  = 'block';
    document.getElementById('edit-profile').style.display  = 'none';
    document.getElementById('btn-start-edit').style.display = 'inline-flex';
}
</script>
@endsection