<aside class="app-sidebar" id="appSidebar">

    <!-- Logo + Toggle -->
    <div class="sidebar-logo">
        <a href="{{ route('simulasi.index') }}" class="brand">
            <img src="{{ asset('assets/img/logo_bps.png') }}" alt="Logo" class="brand-logo">
            <span class="brand-text">SI-PRABU</span>
        </a>
        <button class="sidebar-toggle" id="sidebarToggle" title="Sembunyikan / Tampilkan Sidebar">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="3" y1="6"  x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>

    <!-- Scrollable nav area -->
    <div class="sidebar-scroll">
        <nav class="sidebar-nav">

            <p class="nav-section-label">Menu Utama</p>

            <a href="{{ route('simulasi.index') }}"
               class="nav-item {{ request()->routeIs('simulasi.*') ? 'active' : '' }}">
                <i class="fas fa-random nav-icon"></i>
                <span class="nav-label">Simulasi Input Output</span>
            </a>

            <a href="{{ route('dashboard.index') }}"
               class="nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line nav-icon"></i>
                <span class="nav-label">Dashboard</span>
            </a>

            <p class="nav-section-label" style="margin-top:18px">Menu Penunjang</p>

            <!-- Collapsible: Data Pendukung PDRB -->
            @php
                $isInputMenu = request()->routeIs('prices_productions.*')
                    || request()->routeIs('rasio.*')
                    || request()->routeIs('ihp.*')
                    || request()->routeIs('wip-cbr.*');
            @endphp

            <div class="nav-group {{ $isInputMenu ? 'open' : '' }}">
                <button class="nav-item nav-group-toggle {{ $isInputMenu ? 'active' : '' }}"
                        aria-expanded="{{ $isInputMenu ? 'true' : 'false' }}">
                    <i class="fas fa-edit nav-icon"></i>
                    <span class="nav-label">Data Pendukung PDRB</span>
                    <svg class="nav-caret" width="12" height="12" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="nav-submenu">
                    <a href="{{ route('prices_productions.index') }}"
                       class="nav-sub-item {{ request()->routeIs('prices_productions.*') ? 'active' : '' }}">
                        Input Harga &amp; Produksi
                    </a>
                    <a href="{{ route('rasio.index') }}"
                       class="nav-sub-item {{ request()->routeIs('rasio.*') ? 'active' : '' }}">
                        Input Rasio
                    </a>
                    <a href="{{ route('ihp.index') }}"
                       class="nav-sub-item {{ request()->routeIs('ihp.*') ? 'active' : '' }}">
                        Input IHP
                    </a>
                    <a href="{{ route('wip-cbr.index') }}"
                       class="nav-sub-item {{ request()->routeIs('wip-cbr.*') ? 'active' : '' }}">
                        Input WIP/CBR
                    </a>
                </div>
            </div>

            <a href="{{ route('documents.index') }}"
               class="nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                <i class="fas fa-folder-open nav-icon"></i>
                <span class="nav-label">Dokumen Pendukung</span>
            </a>

            <a href="{{ route('pdrb.index') }}"
               class="nav-item {{ request()->routeIs('pdrb.*') ? 'active' : '' }}">
                <i class="fas fa-calculator nav-icon"></i>
                <span class="nav-label">Pembentukan PDRB</span>
            </a>

            <a href="{{ route('finalisasi.index') }}"
               class="nav-item {{ request()->routeIs('finalisasi.*') ? 'active' : '' }}">
                <i class="fas fa-flag-checkered nav-icon"></i>
                <span class="nav-label">Finalisasi PDRB</span>
            </a>

            <a href="{{ route('io.index') }}"
               class="nav-item {{ request()->routeIs('io.*') ? 'active' : '' }}">
                <i class="fas fa-table nav-icon"></i>
                <span class="nav-label">Kelola Tabel IO</span>
            </a>

            <a href="{{ route('download.index') }}"
               class="nav-item {{ request()->routeIs('download.*') ? 'active' : '' }}">
                <i class="fas fa-download nav-icon"></i>
                <span class="nav-label">Download Laporan</span>
            </a>

            @if(Auth::user()->id_role == 2)
            <a href="{{ route('manage.user.index') }}"
               class="nav-item {{ request()->routeIs('manage.user.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i>
                <span class="nav-label">Kelola Pengguna</span>
            </a>
            @endif

        </nav>
    </div>
</aside>

<!-- Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>


<style>
:root {
    --sidebar-width: 240px;
    --sidebar-collapsed-width: 60px;
    --sidebar-bg: #ffffff;
    --sidebar-border: #f0f0f0;
    --sidebar-logo-height: 57px;

    --accent:       #f97316;
    --accent-light: #fff7ed;
    --accent-dark:  #ea580c;

    --text-primary:   #111827;
    --text-secondary: #6b7280;
    --text-muted:     #c4c4c4;
    --text-icon:      #9ca3af;

    --radius-item: 8px;
    --transition: 0.22s cubic-bezier(.4,0,.2,1);
}

.app-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    border-right: 1px solid var(--sidebar-border);
    display: flex;
    flex-direction: column;
    font-family: 'Figtree', sans-serif;
    z-index: 1000;
    transition: width var(--transition), transform var(--transition);
    overflow: hidden;
}

/* Collapsed state */
.app-sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

.sidebar-logo {
    height: var(--sidebar-logo-height);
    min-height: var(--sidebar-logo-height);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 12px 0 16px;
    border-bottom: 1px solid var(--sidebar-border);
    gap: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.brand {
    text-decoration: none;
    display: flex;
    align-items: center;
    min-width: 0;
    flex: 1;
    overflow: hidden;
}

.brand-text {
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 1.8px;
    color: var(--text-primary);
    white-space: nowrap;
    transition: opacity var(--transition), width var(--transition);
}

.brand-logo {
    width: 28px;
    height: 28px;
    object-fit: contain;
    flex-shrink: 0;
    margin-right: 8px;
}

/* Saat collapsed: sembunyikan logo image juga bersama brand */
.app-sidebar.collapsed .brand-text {
    opacity: 0;
    width: 0;
    pointer-events: none;
}

/* Toggle button */
.sidebar-toggle {
    flex-shrink: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 7px;
    background: transparent;
    color: var(--text-icon);
    cursor: pointer;
    transition: background var(--transition), color var(--transition);
    margin-left: auto;
}

.sidebar-toggle:hover {
    background: var(--accent-light);
    color: var(--accent);
}

/* When collapsed, center the entire logo row & toggle */
.app-sidebar.collapsed .sidebar-logo {
    justify-content: center;
    padding: 0;
}

.app-sidebar.collapsed .brand {
    display: none;
}

.app-sidebar.collapsed .sidebar-toggle {
    margin-left: 0;
    width: 36px;
    height: 36px;
}

.sidebar-scroll {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 8px 0 16px;
    scrollbar-width: thin;
    scrollbar-color: var(--sidebar-border) transparent;
}

.sidebar-scroll::-webkit-scrollbar { width: 3px; }
.sidebar-scroll::-webkit-scrollbar-thumb {
    background: var(--sidebar-border);
    border-radius: 4px;
}

.nav-section-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--text-muted);
    padding: 0 16px;
    margin: 10px 0 2px;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity var(--transition);
}

.app-sidebar.collapsed .nav-section-label {
    display: none;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 16px;
    margin: 1px 8px;
    border-radius: var(--radius-item);
    text-decoration: none;
    color: inherit;
    background: transparent;
    border: none;
    width: calc(100% - 16px);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: background var(--transition);
    white-space: nowrap;
}

.nav-icon {
    width: 16px;
    min-width: 16px;
    font-size: 13px;
    color: var(--text-icon);
    text-align: center;
    transition: color var(--transition);
    flex-shrink: 0;
}

.nav-label {
    font-size: 13px;
    font-weight: 400;
    color: var(--text-secondary);
    transition: color var(--transition), opacity var(--transition), width var(--transition);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Hover */
.nav-item:hover {
    background: var(--accent-light);
}
.nav-item:hover .nav-icon,
.nav-item:hover .nav-label {
    color: var(--accent);
}

/* Active */
.nav-item.active {
    background: var(--accent-light);
}
.nav-item.active::after {
    content: '';
    position: absolute;
    left: -8px;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: var(--accent);
    border-radius: 0 3px 3px 0;
}
.nav-item.active .nav-icon {
    color: var(--accent);
}
.nav-item.active .nav-label {
    color: var(--accent-dark);
    font-weight: 600;
}

/* Hide labels when collapsed */
.app-sidebar.collapsed .nav-label,
.app-sidebar.collapsed .nav-caret {
    display: none;
}

/* Center icons when collapsed */
.app-sidebar.collapsed .sidebar-scroll {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.app-sidebar.collapsed .sidebar-nav {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.app-sidebar.collapsed .nav-item {
    justify-content: center;
    padding: 9px 0;
    margin: 1px 0;
    width: var(--sidebar-collapsed-width);
    gap: 0;
}

.app-sidebar.collapsed .nav-item::after {
    left: 0;
}

.nav-group {
    display: flex;
    flex-direction: column;
}

.nav-group-toggle {
    text-align: left;
}

.nav-caret {
    margin-left: auto;
    flex-shrink: 0;
    color: var(--text-muted);
    transition: transform var(--transition), color var(--transition), opacity var(--transition);
}

.nav-group.open .nav-caret,
.nav-group-toggle[aria-expanded="true"] .nav-caret {
    transform: rotate(180deg);
}

.nav-group-toggle:hover .nav-caret {
    color: var(--accent);
}

/* Submenu */
.nav-submenu {
    display: none;
    flex-direction: column;
    padding: 2px 0 4px;
}

.nav-group.open .nav-submenu {
    display: flex;
}

.nav-sub-item {
    display: flex;
    align-items: center;
    padding: 7px 16px 7px 42px;
    margin: 1px 8px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 400;
    color: var(--text-secondary);
    text-decoration: none;
    position: relative;
    transition: background var(--transition), color var(--transition);
    white-space: nowrap;
    overflow: hidden;
}

.nav-sub-item::before {
    content: '';
    position: absolute;
    left: 27px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #d1d5db;
    transition: background var(--transition);
    flex-shrink: 0;
}

.nav-sub-item:hover {
    background: var(--accent-light);
    color: var(--accent);
}
.nav-sub-item:hover::before {
    background: var(--accent);
}

.nav-sub-item.active {
    color: var(--accent-dark);
    font-weight: 600;
    background: var(--accent-light);
}
.nav-sub-item.active::before {
    background: var(--accent);
}

/* Hide submenu when collapsed */
.app-sidebar.collapsed .nav-submenu {
    display: none !important;
}
.app-sidebar.collapsed .nav-group-toggle .nav-caret {
    display: none;
}

/* Fix nav-group width & centering when collapsed */
.app-sidebar.collapsed .nav-group {
    position: relative;
    width: var(--sidebar-collapsed-width);
    align-items: center;
}

/* Submenu popup ke kanan saat hover collapsed */
.app-sidebar.collapsed .nav-group:hover .nav-submenu {
    display: flex !important;
    position: fixed;
    left: var(--sidebar-collapsed-width);
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    padding: 6px 0;
    min-width: 200px;
    z-index: 1100;
    flex-direction: column;
    animation: dropFadeRight 0.15s ease;
}

.app-sidebar.collapsed .nav-group:hover .nav-submenu .nav-sub-item {
    padding: 9px 16px;
    margin: 1px 6px;
    white-space: nowrap;
}

.app-sidebar.collapsed .nav-group:hover .nav-submenu .nav-sub-item::before {
    display: none;
}

@keyframes dropFadeRight {
    from { opacity: 0; transform: translateX(-6px); }
    to   { opacity: 1; transform: translateX(0); }
}

.sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.35);
    z-index: 999;
}

@media (max-width: 768px) {
    .app-sidebar {
        transform: translateX(-100%);
        width: var(--sidebar-width) !important;
    }
    .app-sidebar.mobile-open {
        transform: translateX(0);
    }
    .sidebar-overlay.active {
        display: block;
    }
}

.main-panel {
    margin-left: var(--sidebar-width);
    transition: margin-left var(--transition);
}
.app-sidebar.collapsed ~ .main-panel,
body.sidebar-collapsed .main-panel {
    margin-left: var(--sidebar-collapsed-width);
}
</style>


<script>
(function () {
    'use strict';

    const sidebar  = document.getElementById('appSidebar');
    const toggle   = document.getElementById('sidebarToggle');
    const overlay  = document.getElementById('sidebarOverlay');
    const STORAGE_KEY = 'si_prabu_sidebar_collapsed';

    /* Restore state */
    if (localStorage.getItem(STORAGE_KEY) === '1') {
        sidebar.classList.add('collapsed');
        document.body.classList.add('sidebar-collapsed');
    }

    /* Toggle collapsed */
    toggle.addEventListener('click', function () {
        const isMobile = window.innerWidth <= 768;

        if (isMobile) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        } else {
            const collapsed = sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed', collapsed);
            localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
        }
    });

    /* Close on overlay click (mobile) */
    overlay.addEventListener('click', function () {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
    });

    /* Collapsible submenu */
    document.querySelectorAll('.nav-group-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const group    = btn.closest('.nav-group');
            const isOpen   = group.classList.toggle('open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });

    /* Posisi popup submenu saat collapsed */
    document.querySelectorAll('.nav-group').forEach(function (group) {
        group.addEventListener('mouseenter', function () {
            if (!sidebar.classList.contains('collapsed')) return;
            var submenu = group.querySelector('.nav-submenu');
            var toggleBtn = group.querySelector('.nav-group-toggle');
            if (!submenu || !toggleBtn) return;
            var rect = toggleBtn.getBoundingClientRect();
            submenu.style.top = rect.top + 'px';
        });
    });
})();
</script>