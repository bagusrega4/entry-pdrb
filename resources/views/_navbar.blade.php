<header class="app-navbar" id="appNavbar">
    <div class="navbar-inner">

        <!-- Kiri: toggle (mobile only) -->
        <button class="navbar-mob-toggle" id="navbarMobToggle" title="Menu">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="3" y1="6"  x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        <!-- Kanan: user dropdown -->
        <div class="navbar-right">
            <div class="nav-user-dropdown" id="navUserDropdown">

                <!-- Trigger -->
                <button class="nav-user-toggle" id="navUserToggle" aria-expanded="false">
                    <div class="nav-avatar">
                        @if(Auth::user())
                        <img src="{{ !Auth::user()->photo
                            ? 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'
                            : asset('/storage/' . Auth::user()->photo) }}"
                            alt="avatar" />
                        @endif
                    </div>
                    <div class="nav-user-info">
                        <span class="nav-user-greeting">Hi,</span>
                        <span class="nav-user-name">{{ Auth::user()->username ?? '' }}</span>
                    </div>
                    <svg class="nav-user-caret" width="12" height="12" fill="none" stroke="currentColor"
                         stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <!-- Dropdown panel -->
                <div class="nav-dropdown-panel" id="navDropdownPanel">

                    <!-- User info box -->
                    <div class="nav-user-box">
                        <div class="nav-user-box-avatar">
                            @if(Auth::user())
                            <img src="{{ !Auth::user()->photo
                                ? 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'
                                : asset('/storage/' . Auth::user()->photo) }}"
                                alt="avatar" />
                            @endif
                        </div>
                        <div class="nav-user-box-info">
                            @if(Auth::user())
                            <div class="nav-user-box-name">{{ Auth::user()->username }}</div>
                            <div class="nav-user-box-email">{{ Auth::user()->email }}</div>
                            <span class="nav-user-box-role">{{ ucfirst(Auth::user()->role->role) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="nav-dropdown-divider"></div>

                    <a href="{{ route('profile.edit') }}" class="nav-dropdown-item">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Lihat Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="nav-dropdown-item nav-dropdown-item-danger"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Keluar
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</header>


<style>
.app-navbar {
    position: fixed;
    top: 0;
    left: var(--sidebar-width, 240px);
    right: 0;
    height: 57px;
    background: #fff;
    border-bottom: 1px solid #f0f0f0;
    z-index: 999;
    font-family: 'Figtree', sans-serif;
    transition: left 0.22s cubic-bezier(.4,0,.2,1);
}

body.sidebar-collapsed .app-navbar {
    left: var(--sidebar-collapsed-width, 60px);
}

.navbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    padding: 0 20px;
}

.navbar-mob-toggle {
    display: none;
    width: 32px;
    height: 32px;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 7px;
    background: transparent;
    color: #9ca3af;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
}
.navbar-mob-toggle:hover {
    background: #fff7ed;
    color: #f97316;
}

@media (max-width: 768px) {
    .app-navbar {
        left: 0 !important;
    }
    .navbar-mob-toggle {
        display: flex;
    }
}

.navbar-right {
    margin-left: auto;
}

.nav-user-dropdown {
    position: relative;
}

.nav-user-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 5px 10px;
    border-radius: 10px;
    background: transparent;
    border: 0.5px solid transparent;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    font-family: 'Figtree', sans-serif;
}
.nav-user-toggle:hover,
.nav-user-toggle[aria-expanded="true"] {
    background: #fff7ed;
    border-color: #fed7aa;
}

.nav-avatar {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    overflow: hidden;
    border: 1.5px solid #fed7aa;
    flex-shrink: 0;
}
.nav-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.nav-user-info {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
    text-align: left;
}
.nav-user-greeting {
    font-size: 10px;
    color: #9ca3af;
    font-weight: 400;
}
.nav-user-name {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
}

.nav-user-caret {
    color: #9ca3af;
    transition: transform 0.2s;
    flex-shrink: 0;
}
.nav-user-toggle[aria-expanded="true"] .nav-user-caret {
    transform: rotate(180deg);
}

.nav-dropdown-panel {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    min-width: 230px;
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    padding: 8px 0;
    z-index: 1050;
    animation: dropFadeIn 0.15s ease;
}
.nav-dropdown-panel.open {
    display: block;
}

@keyframes dropFadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

.nav-user-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
}
.nav-user-box-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    overflow: hidden;
    border: 1.5px solid #fed7aa;
    flex-shrink: 0;
}
.nav-user-box-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.nav-user-box-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}
.nav-user-box-name {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.nav-user-box-email {
    font-size: 11px;
    color: #9ca3af;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.nav-user-box-role {
    display: inline-block;
    margin-top: 4px;
    font-size: 10px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 100px;
    background: #fff7ed;
    color: #c2410c;
    border: 0.5px solid #fed7aa;
    text-transform: capitalize;
}

.nav-dropdown-divider {
    height: 1px;
    background: #f3f4f6;
    margin: 4px 0;
}

.nav-dropdown-item {
    display: flex;
    align-items: center;
    gap: 9px;
    width: 100%;
    padding: 9px 16px;
    font-size: 13px;
    font-weight: 400;
    color: #374151;
    background: none;
    border: none;
    text-decoration: none;
    cursor: pointer;
    font-family: 'Figtree', sans-serif;
    transition: background 0.12s, color 0.12s;
    text-align: left;
}
.nav-dropdown-item:hover {
    background: #f9fafb;
    color: #111827;
}
.nav-dropdown-item svg {
    color: #9ca3af;
    flex-shrink: 0;
}
.nav-dropdown-item-danger {
    color: #991b1b;
}
.nav-dropdown-item-danger svg {
    color: #fca5a5;
}
.nav-dropdown-item-danger:hover {
    background: #fef2f2;
    color: #7f1d1d;
}
</style>


<script>
(function () {
    'use strict';

    const toggle  = document.getElementById('navUserToggle');
    const panel   = document.getElementById('navDropdownPanel');

    toggle.addEventListener('click', function () {
        const isOpen = panel.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('navUserDropdown').contains(e.target)) {
            panel.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    const mobToggle = document.getElementById('navbarMobToggle');
    if (mobToggle) {
        mobToggle.addEventListener('click', function () {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.toggle('mobile-open');
            if (overlay) overlay.classList.toggle('active');
        });
    }
})();
</script>