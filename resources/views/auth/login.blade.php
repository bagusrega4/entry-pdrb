<x-guest-layout>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
    *, *::before, *::after { box-sizing: border-box; }

    body {
        background: #fafafa;
        min-height: 100vh;
        font-family: 'Figtree', sans-serif;
        margin: 0;
    }

    .login-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* Left panel */
    .login-left {
        flex: 1;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 48px 56px;
        position: relative;
    }

    .login-left-inner {
        width: 100%;
        max-width: 360px;
    }

    /* Brand */
    .login-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 40px;
    }
    .login-brand-icon {
        width: 34px;
        height: 34px;
        background: #f97316;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
    }
    .login-brand-logo {
        width: 38px;
        height: 38px;
        object-fit: contain;
        flex-shrink: 0;
    }
    .login-brand-name {
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 1.8px;
        color: #111827;
    }

    /* Heading */
    .login-heading {
        margin-bottom: 28px;
    }
    .login-heading h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 6px;
        letter-spacing: -0.3px;
    }
    .login-heading p {
        font-size: 13px;
        color: #9ca3af;
        margin: 0;
        font-weight: 400;
        line-height: 1.5;
    }

    /* Form */
    .form-group {
        margin-bottom: 16px;
    }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        letter-spacing: 0.2px;
    }
    .input-wrap {
        position: relative;
    }
    .input-wrap .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 15px;
        pointer-events: none;
        line-height: 1;
    }
    .form-input {
        width: 100%;
        height: 42px;
        padding: 0 40px 0 38px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: #111827;
        background: #fff;
        border: 0.5px solid #e5e7eb;
        border-radius: 9px;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .form-input::placeholder { color: #c4c4c4; }
    .form-input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }
    .form-input.is-invalid {
        border-color: #fca5a5;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 15px;
        cursor: pointer;
        line-height: 1;
        transition: color 0.15s;
        background: none;
        border: none;
        padding: 0;
    }
    .password-toggle:hover { color: #f97316; }

    /* Error */
    .input-error {
        font-size: 11.5px;
        color: #dc2626;
        margin-top: 5px;
    }

    /* Tombol Login */
    .btn-login {
        width: 100%;
        height: 42px;
        background: #f97316;
        border: none;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Figtree', sans-serif;
        color: #fff;
        cursor: pointer;
        margin-top: 8px;
        transition: background 0.15s, box-shadow 0.15s;
        letter-spacing: 0.2px;
    }
    .btn-login:hover {
        background: #ea580c;
        box-shadow: 0 4px 14px rgba(249,115,22,0.3);
    }

    /* TOMBOL REGISTER */
    .btn-register {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        height: 42px;
        background: transparent;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        font-family: 'Figtree', sans-serif;
        color: #374151;
        cursor: pointer;
        margin-top: 10px;
        text-decoration: none;
        transition: border-color 0.15s, color 0.15s, background 0.15s;
        letter-spacing: 0.2px;
    }
    .btn-register:hover {
        border-color: #f97316;
        color: #f97316;
        background: rgba(249,115,22,0.04);
    }
    .btn-register i { font-size: 14px; }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 18px 0 0;
    }
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f3f4f6;
    }
    .divider span {
        font-size: 11px;
        color: #d1d5db;
        font-weight: 500;
        white-space: nowrap;
    }

    /* Footer note */
    .login-footer-note {
        margin-top: 28px;
        font-size: 11.5px;
        color: #d1d5db;
        text-align: center;
    }

    /* Right panel */
    .login-right {
        width: 440px;
        flex-shrink: 0;
        background: #111827;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 56px 48px;
        position: relative;
        overflow: hidden;
    }

    .login-right::before {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(249,115,22,0.18) 0%, transparent 70%);
        top: -80px;
        right: -80px;
    }
    .login-right::after {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(249,115,22,0.12) 0%, transparent 70%);
        bottom: -60px;
        left: -40px;
    }

    .right-content { position: relative; z-index: 1; }

    .right-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #f97316;
        background: rgba(249,115,22,0.1);
        border: 0.5px solid rgba(249,115,22,0.25);
        border-radius: 100px;
        padding: 4px 12px;
        margin-bottom: 24px;
    }
    .right-tag::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #f97316;
    }

    .right-title {
        font-size: 26px;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
        margin-bottom: 16px;
        letter-spacing: -0.5px;
    }
    .right-title span { color: #f97316; }

    .right-desc {
        font-size: 13px;
        color: rgba(255,255,255,0.4);
        line-height: 1.7;
        margin-bottom: 40px;
        max-width: 300px;
    }

    .right-stats {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .stat-item {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: rgba(249,115,22,0.1);
        border: 0.5px solid rgba(249,115,22,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f97316;
        flex-shrink: 0;
    }
    .stat-text-label {
        font-size: 11px;
        color: rgba(255,255,255,0.3);
        font-weight: 400;
        margin-bottom: 1px;
    }
    .stat-text-value {
        font-size: 13px;
        color: rgba(255,255,255,0.85);
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .login-right { display: none; }
        .login-left { padding: 40px 24px; }
    }
</style>

<div class="login-wrapper">

    <!-- Left: Form -->
    <div class="login-left">
        <div class="login-left-inner">

            <div class="login-brand">
                <img src="{{ asset('assets/img/logo_bps.png') }}" alt="Logo" class="login-brand-logo">
                <span class="login-brand-name">SI-PRABU</span>
            </div>

            <div class="login-heading">
                <h1>Selamat Datang</h1>
                <p>Masukkan email dan password Anda<br>untuk mengakses sistem.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope input-icon"></i>
                        <input
                            type="email"
                            class="form-input {{ $errors->get('email') ? 'is-invalid' : '' }}"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="email@bps.go.id"
                            required autofocus autocomplete="username">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="input-error" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            class="form-input {{ $errors->get('password') ? 'is-invalid' : '' }}"
                            name="password"
                            placeholder="••••••••"
                            required autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i id="toggleIcon" class="bi bi-eye"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="input-error" />
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <!-- Divider -->
            <div class="divider"><span>atau</span></div>

            <!-- Tombol Daftar Akun -->
            <a href="{{ route('register') }}" class="btn-register">
                <i class="bi bi-person-plus"></i>
                Daftar Akun Baru
            </a>

            <div class="login-footer-note">
                © {{ date('Y') }} SI-PRABU · BPS Kabupaten Kediri
            </div>

        </div>
    </div>

    <!-- Right: Branding panel -->
    <div class="login-right">
        <div class="right-content">
            <div class="right-tag">Sistem Informasi</div>
            <div class="right-title">
                Sistem Informasi<br>
                <span>Perhitungan & Simulasi</span><br>
                PDRB Kab. Kediri
            </div>
            <p class="right-desc">
                Sistem Informasi Pengambilan Keputusan untuk Analisis Pertumbuhan Ekonomi Kabupaten Kediri (SI-PRABU)
            </p>

            <div class="right-stats">
                <div class="stat-item">
                    <div class="stat-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-text-label">Fitur Utama</div>
                        <div class="stat-text-value">Pembentukan & Finalisasi PDRB</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-text-label">Data Pendukung</div>
                        <div class="stat-text-value">Harga, Produksi, Rasio, IHP, WIP</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 15l6 6M4 4l5 5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-text-label">Unggulan</div>
                        <div class="stat-text-value">Simulasi Input Output Sektoral</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>

</x-guest-layout>