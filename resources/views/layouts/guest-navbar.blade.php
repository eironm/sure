@once
    <style>
        /* Guest navbar avatar tiles — mirrors panel-theme .uavatar.
           Guest pages only load app.css, so this block ships the exact
           rules the user-avatar tile component relies on. */
        .uavatar {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 10px;
            background: hsl(var(--uav-hue, 220) 55% 52%);
            color: #fff;
            font-weight: 700;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
        }
        .uavatar-img { width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 2; }
        .uavatar-initials { position: relative; z-index: 2; font-size: inherit; line-height: 1; letter-spacing: .3px; }
        .uavatar-watermark {
            position: absolute; inset: 0; z-index: 1;
            display: flex; align-items: center; justify-content: center;
            font-size: .9em; color: rgba(255, 255, 255, .32);
        }
        .uavatar.xs { width: 26px; height: 26px; font-size: .6rem; border-radius: 7px; }
        .uavatar.sm { width: 38px; height: 38px; font-size: .85rem; }
        .uavatar.md { width: 46px; height: 46px; font-size: 1rem; }
        .uavatar.lg { width: 64px; height: 64px; font-size: 1.4rem; }
    </style>
@endonce

<nav class="navbar-sure" role="navigation" aria-label="Main navigation">
    <div class="navbar-inner">

        {{-- Brand --}}
        <a class="navbar-brand" href="{{ route('guest.dashboard') }}" aria-label="{{ branding('system_name', 'Platform') }} Home">
            <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}"
                 data-logo-light="{{ \App\Services\BrandingService::logoForBg('dark') }}"
                 data-logo-dark="{{ \App\Services\BrandingService::logoForBg('light') }}"
                 alt="{{ branding('system_name', 'Platform') }} Logo"
                 class="navbar-logo{{ \App\Services\BrandingService::logoAutoVariant() ? ' brand-logo-auto' : '' }}{{ \App\Services\BrandingService::wordmarkOnly() ? ' brand-wordmark-only' : '' }}">
            <span class="{{ \App\Services\BrandingService::wordmarkOnly() ? 'd-none' : '' }}">{{ branding('system_name', 'Platform') }}</span>
        </a>

        @if (\App\Services\BrandingService::logoAutoVariant())
            {!! \App\Services\BrandingService::logoAutoVariantScript() !!}
        @endif

        {{-- Desktop Nav Links --}}
        <ul class="navbar-links" id="navbarLinks" role="menubar">
            @auth
                <li role="none"><a role="menuitem" class="nav-link {{ request()->routeIs('guest.dashboard') ? 'active' : '' }}" href="{{ route('guest.dashboard') }}">Dashboard</a></li>

                {{-- $canReserve is resolved once in layouts.app and passed in.
                     The fallback keeps this partial usable standalone. --}}
                @php($canReserve = $canReserve ?? true)

                @if ($canReserve)
                    <li role="none"><a role="menuitem" class="nav-link {{ request()->routeIs('guest.reserve') ? 'active' : '' }}" href="{{ route('guest.reserve') }}">Reserve</a></li>
                @endif

                <li role="none"><a role="menuitem" class="nav-link {{ request()->routeIs('guest.history') ? 'active' : '' }}" href="{{ route('guest.history') }}">History</a></li>
                <li role="none"><a role="menuitem" class="nav-link {{ request()->routeIs('guest.facilities') ? 'active' : '' }}" href="{{ route('guest.facilities') }}">Campus Map</a></li>
                <li role="none"><a role="menuitem" class="nav-link {{ request()->routeIs('guest.facility-board') ? 'active' : '' }}" href="{{ route('guest.facility-board') }}">Facility Board</a></li>

                {{-- Role-based Panel Switchers --}}
                @if (Auth::user()->isSuperAdmin())
                    <li role="none"><a role="menuitem" class="nav-link" href="{{ route('superadmin.dashboard') }}">SuperAdmin</a></li>
                @endif
                
                @if (Auth::user()->isAdminOrAbove())
                    <li role="none"><a role="menuitem" class="nav-link" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                @endif

                @if (Auth::user()->canAccessCashier())
                    <li role="none"><a role="menuitem" class="nav-link" href="{{ route('cashier.dashboard') }}">Cashier</a></li>
                @endif
            @else
                <li role="none"><a role="menuitem" class="nav-link" href="{{ route('login') }}">Home</a></li>
            @endauth
        </ul>

        {{-- RIGHT: Bell + User --}}
        <div class="navbar-actions">
            @auth
                <x-notification-bell />

                <div class="user-dropdown-container" x-data="{ open: false }" @click.away="open = false">
                    <button class="user-dropdown-btn {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                            @click="open = !open" type="button"
                            aria-expanded="false" aria-haspopup="true"
                            :aria-expanded="open.toString()">
                        <div class="user-icon" style="display:inline-flex;align-items:center;justify-content:center;padding:0;width:34px;height:34px;">
                            <x-user-avatar :user="Auth::user()" size="sm" />
                        </div>
                        <span>{{ Auth::user()->first_name ?? Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down ms-1" :style="open ? 'transform:rotate(180deg)' : ''" style="transition:transform 0.2s;font-size:0.75rem;" aria-hidden="true"></i>
                    </button>

                    <div class="dropdown-menu-refined" x-show="open" x-cloak x-transition role="menu">
                        {{-- My Profile is permanently here --}}
                        <a href="{{ route('profile.edit') }}" class="dropdown-item-refined {{ request()->routeIs('profile.edit') ? 'active' : '' }}" role="menuitem">
                            <i class="bi bi-person-circle" aria-hidden="true"></i><span>My Profile</span>
                        </a>
                        <div class="dropdown-divider-refined" role="separator"></div>
                        <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="width:100%;">
                            @csrf
                            <button type="button" class="dropdown-item-refined logout" style="width:100%;" onclick="confirmLogout()" role="menuitem">
                                <i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="user-dropdown-btn">
                    <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i><span>Login</span>
                </a>
            @endauth

            <button class="navbar-hamburger" type="button"
                    data-drawer-toggle="#mobileDrawer"
                    aria-label="Open navigation menu"
                    aria-controls="mobileDrawer"
                    aria-expanded="false">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    {{-- Off-canvas drawer. Replaces the old push-down menu, which shifted
         page content and clipped its own contents once the role-based
         links pushed it past the viewport height. --}}
    <div class="mobile-drawer-backdrop" id="mobileDrawerBackdrop"
         data-drawer-backdrop="#mobileDrawer" aria-hidden="true"></div>

    <div class="mobile-drawer" id="mobileDrawer" data-drawer
         role="dialog" aria-modal="true" aria-label="Navigation menu" aria-hidden="true">

        <div class="mobile-drawer-header">
            @auth
                <div class="mobile-drawer-user">
                    <div class="mobile-drawer-avatar" aria-hidden="true">
                        <x-user-avatar :user="Auth::user()" size="sm" />
                    </div>
                    <div style="min-width:0;">
                        <div class="mobile-drawer-name">{{ Auth::user()->first_name ?? Auth::user()->name }}</div>
                        <div class="mobile-drawer-role">{{ Auth::user()->role ?? 'Guest' }}</div>
                    </div>
                </div>
            @else
                <div class="mobile-drawer-name">{{ branding('system_name', 'Platform') }}</div>
            @endauth

            <button type="button" class="mobile-drawer-close"
                    data-drawer-close aria-label="Close navigation menu">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <nav class="mobile-drawer-nav" aria-label="Mobile navigation">
            @auth
                <a class="mobile-drawer-link {{ request()->routeIs('guest.dashboard') ? 'active' : '' }}" href="{{ route('guest.dashboard') }}">
                    <i class="bi bi-grid-1x2-fill" aria-hidden="true"></i><span>Dashboard</span>
                </a>
                @if ($canReserve ?? true)
                    <a class="mobile-drawer-link {{ request()->routeIs('guest.reserve') ? 'active' : '' }}" href="{{ route('guest.reserve') }}">
                        <i class="bi bi-plus-square-fill" aria-hidden="true"></i><span>Reserve</span>
                    </a>
                @endif
                <a class="mobile-drawer-link {{ request()->routeIs('guest.history') ? 'active' : '' }}" href="{{ route('guest.history') }}">
                    <i class="bi bi-clock-history" aria-hidden="true"></i><span>History</span>
                </a>
                <a class="mobile-drawer-link {{ request()->routeIs('guest.facilities') ? 'active' : '' }}" href="{{ route('guest.facilities') }}">
                    <i class="bi bi-map-fill" aria-hidden="true"></i><span>Campus Map</span>
                </a>
                <a class="mobile-drawer-link {{ request()->routeIs('guest.facility-board') ? 'active' : '' }}" href="{{ route('guest.facility-board') }}">
                    <i class="bi bi-calendar3-week-fill" aria-hidden="true"></i><span>Facility Board</span>
                </a>

                @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdminOrAbove() || Auth::user()->canAccessCashier())
                    <div class="mobile-drawer-section">Panels</div>
                    @if (Auth::user()->isSuperAdmin())
                        <a class="mobile-drawer-link" href="{{ route('superadmin.dashboard') }}">
                            <i class="bi bi-shield-lock-fill" aria-hidden="true"></i><span>SuperAdmin</span>
                        </a>
                    @endif
                    @if (Auth::user()->isAdminOrAbove())
                        <a class="mobile-drawer-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-sliders" aria-hidden="true"></i><span>Admin Panel</span>
                        </a>
                    @endif
                    @if (Auth::user()->canAccessCashier())
                        <a class="mobile-drawer-link" href="{{ route('cashier.dashboard') }}">
                            <i class="bi bi-cash-coin" aria-hidden="true"></i><span>Cashier</span>
                        </a>
                    @endif
                @endif

                <div class="mobile-drawer-divider" role="separator"></div>

                <a class="mobile-drawer-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person-circle" aria-hidden="true"></i><span>My Profile</span>
                </a>
                <a class="mobile-drawer-link" href="{{ route('notifications.index') }}">
                    <i class="bi bi-bell-fill" aria-hidden="true"></i><span>Notifications</span>
                </a>

                <button type="button" class="mobile-drawer-link logout"
                        style="background:none;border:none;width:100%;text-align:left;"
                        onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Logout</span>
                </button>
            @else
                <a class="mobile-drawer-link" href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i><span>Log in</span>
                </a>
            @endauth
        </nav>
    </div>
</nav>

<style>
/* ── Navbar Shell ─────────────────────────────────────────── */
.navbar-sure {
    background: linear-gradient(135deg, var(--brand-dark, #0f172a) 0%, #1e293b 100%);
    border-bottom: 3px solid var(--brand-primary, #2563eb);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    position: sticky;
    top: 0;
    z-index: 1030;
}

.navbar-inner {
    display: flex;
    align-items: center;
    padding: 0.65rem 2rem;
    gap: 1rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* ── Brand ────────────────────────────────────────────────── */
.navbar-brand {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 900;
    font-size: 1.25rem;
    color: #fff !important;
    letter-spacing: 1px;
    text-decoration: none;
    flex-shrink: 0;
}
.navbar-brand:hover {
    color: var(--brand-primary, #2563eb) !important;
}
/* The brand image is a horizontal transparent wordmark, NOT a square badge —
   render it at auto width with a fixed readable height. */
.navbar-logo {
    width: auto;
    height: 2.9rem;
    max-width: min(240px, 30vw);
    object-fit: contain;
}
/* Wordmark-only mode: drop the text name, so the logo carries the identity —
   allow it a touch more room. */
.navbar-brand .brand-wordmark-only { height: 3.1rem; max-width: min(280px, 34vw); }

/* ── Desktop Nav Links ────────────────────────────────────── */
.navbar-links {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    list-style: none;
    padding: 0;
    margin: 0 auto;
    /* Allow the link list to shrink so .navbar-actions is never pushed off */
    min-width: 0;
    flex-shrink: 1;
    overflow: hidden;
}
.navbar-links li { list-style: none; }

.nav-link {
    color: rgba(255,255,255,0.85) !important;
    font-weight: 700;
    font-size: 0.82rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-decoration: none !important;
    white-space: nowrap;
    transition: all 0.25s ease;
    display: block;
}
.nav-link:hover {
    color: #fff !important;
    background: rgba(var(--brand-primary-rgb, 37,99,235), 0.2);
}
.nav-link.active {
    color: #fff !important;
    background: var(--brand-primary, #2563eb);
    font-weight: 800;
    box-shadow: 0 2px 8px rgba(var(--brand-primary-rgb, 37,99,235), 0.3);
}

/* ── Right Actions ────────────────────────────────────────── */
.navbar-actions {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-shrink: 0;
    margin-left: auto;
}

/* ── Hamburger (mobile only) ──────────────────────────────── */
.navbar-hamburger {
    display: none;
    border: none;
    background: transparent;
    color: var(--brand-primary, #2563eb);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.25rem 0.4rem;
    line-height: 1;
}

/* ── Intermediate: tighten padding before hamburger kicks in ─ */
@media (max-width: 1200px) {
    .navbar-inner { padding: 0.65rem 1.25rem; gap: 0.5rem; }
    .nav-link     { padding: 0.5rem 0.65rem; font-size: 0.78rem; letter-spacing: 0; }
}

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 991.98px) {
    .navbar-inner  { padding: 0.5rem 1rem; }
    .navbar-links  { display: none; }
    .navbar-brand  { font-size: 1.05rem; }
    .navbar-logo   { height: 2.3rem; max-width: min(200px, 32vw); }
    .navbar-brand .brand-wordmark-only { height: 2.5rem; max-width: min(220px, 34vw); }

    /* Hamburger needs a full tap target, not a 1.5rem glyph. */
    .navbar-hamburger {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 10px;
        font-size: 1.6rem;
    }
    .navbar-hamburger:active { background: rgba(255,255,255,0.1); }

    /* The account menu is duplicated inside the drawer, so the
       desktop dropdown is redundant here and only costs width. */
    .user-dropdown-container { display: none; }
}

@media (max-width: 575px) {
    .navbar-logo { max-width: 38vw; height: 1.8rem; }
    .navbar-brand .brand-wordmark-only { max-width: 42vw; height: 2rem; }
    .navbar-inner { padding: 0.5rem 0.75rem; gap: 0.5rem; }
    .navbar-actions { gap: 0.25rem; }

    /* Keep the wordmark from squeezing the bell and hamburger off
       the row on 320-360px devices. */
    .navbar-brand span {
        max-width: 42vw;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>

<script>
    if (typeof confirmLogout === 'undefined') {
        function confirmLogout() {
            if (typeof showAlert === 'function') {
                showAlert({
                    title: 'Logout Confirmation',
                    message: 'Are you sure you want to log out of your account?',
                    type: 'warning',
                    showCancel: true,
                    confirmText: 'Log Out',
                    onConfirm: () => { document.getElementById('logoutForm').submit(); }
                });
            } else {
                if (confirm('Are you sure you want to log out?')) {
                    document.getElementById('logoutForm').submit();
                }
            }
        }
    }
</script>
