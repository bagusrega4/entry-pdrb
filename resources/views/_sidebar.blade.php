<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="/" class="logo d-flex align-items-center">
                <span
                    style="font-family: 'Poppins', sans-serif; font-size:20px; letter-spacing:1px;"
                    class="fw-bold text-uppercase text-white">
                    ENTRY PDRB
                </span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar text-white">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler text-white">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more text-white">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Dashboard -->
                <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                    @if(Auth::user()->id_role == 2)
                    <a href="{{ route('dashboard.admin') }}">
                        <i class="fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                    @else
                    <a href="{{ route('dashboard.operator') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                    @endif
                </li>

                <!-- Section -->
                <li class="nav-section mt-2">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h text-white"></i>
                    </span>
                    <h4 class="text-section">Menu Utama</h4>
                </li>

                <!-- Input Harga -->
                <li class="nav-item {{ request()->routeIs('prices.*') ? 'active' : '' }}">
                    <a href="{{ route('prices.index') }}">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Input Harga</p>
                    </a>
                </li>

                <!-- Input Produksi -->
                <li class="nav-item {{ request()->routeIs('productions.*') ? 'active' : '' }}">
                    <a href="{{ route('productions.index') }}">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Input Produksi</p>
                    </a>
                </li>
                @if(in_array(Auth::user()->id_role, [2]))
                <li class="nav-item {{ request()->routeIs('manage.user.*') || request()->routeIs('manage.user.create') || request()->routeIs('manage.user.edit') ? 'active' : '' }}">
                    <a href="{{ route('manage.user.index') }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Manage User</p>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    /* Pastikan semua teks & ikon sidebar berwarna putih */
    .sidebar .nav-item a p,
    .sidebar .nav-item a i,
    .sidebar .text-section,
    .sidebar .nav-item a {
        color: #ffffff !important;
    }

    /* Tambahkan sedikit efek agar tulisan lebih jelas */
    .sidebar .nav-item a p,
    .sidebar .nav-item a i {
        text-shadow: 0 0 4px rgba(0, 0, 0, 0.4);
    }

    /* Sidebar background */
    .sidebar[data-background-color="dark"] {
        background: linear-gradient(135deg, #8e2de2, #ff6a00, #ff9a9e) !important;
        color: #fff;
    }

    /* Logo/header background */
    .logo-header[data-background-color="dark"] {
        background: rgba(0, 0, 0, 0.25) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Nav item default */
    .sidebar .nav-item a {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #ffffff !important;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    /* Hover */
    .sidebar .nav-item a:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff !important;
        transform: translateX(4px);
    }

    /* Active */
    .sidebar .nav-item.active a {
        background: linear-gradient(90deg, #ff6a00, #ff9a9e);
        color: #ffffff !important;
        font-weight: bold;
    }

    /* Icons */
    .sidebar .nav-item i {
        margin-right: 10px;
        font-size: 16px;
        color: #ffffff !important;
    }

    /* Section title */
    .sidebar .text-section {
        font-size: 12px;
        letter-spacing: 1px;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 10px;
        color: #ffffff !important;
    }

    /* Badge */
    .sidebar .badge {
        font-size: 10px;
        padding: 3px 6px;
        background: #ffffff;
        color: #000;
    }
</style>