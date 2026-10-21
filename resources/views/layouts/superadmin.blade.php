<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="sa-shell-wrap">
<head>
    <title>@yield('title', 'SuperAdmin') - {{ branding('system_name', 'Platform') }}</title>
    @include('layouts.partials.panel-head')
    @php $superAdminCssPath = resource_path('css/panel2.css'); @endphp
    @if (is_file($superAdminCssPath))
        {{-- Keep the SuperAdmin content styled before Vite finishes loading.
             The shell itself is still owned by the shared inline panel theme. --}}
        <style id="superadmin-page-css">{!! file_get_contents($superAdminCssPath) !!}</style>
    @endif
</head>
<body>
    <div class="sidebar" id="panelSidebar" data-drawer role="navigation" aria-label="Panel navigation">
        <div class="sidebar-header">
            {{-- Rendered as a control only below 1025px; see panel-theme. --}}
            <button type="button" class="sidebar-close" data-drawer-close aria-label="Close navigation menu">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
            <div class="brand-wrapper">
                <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}" data-logo-light="{{ \App\Services\BrandingService::logoForBg('dark') }}" data-logo-dark="{{ \App\Services\BrandingService::logoForBg('light') }}" alt="{{ branding('system_name', 'Platform') }} Logo" class="brand-logo-img{{ \App\Services\BrandingService::logoAutoVariant() ? ' brand-logo-auto' : '' }}{{ \App\Services\BrandingService::wordmarkOnly() ? ' brand-wordmark-only' : '' }}">
                <span class="brand-name{{ \App\Services\BrandingService::wordmarkOnly() ? ' d-none' : '' }}">{{ branding('system_name', 'Platform') }}</span>
            </div>
            <div class="current-panel-badge">
                <i class="bi bi-shield-fill-check"></i>
                SuperAdmin Panel
            </div>
        </div>

        <nav id="pjax-sidenav" data-pjax-swap>
            <div class="nav-section">Overview</div>
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-section">Administration</div>
            <a href="{{ route('superadmin.users.index') }}" class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> User Management
            </a>

            {{-- CMS navigation lives in the same primary sidebar as the other
                 panel sections; the CMS category pages remain available inside. --}}
            <div class="nav-section">Content &amp; Branding</div>
            <a href="{{ route('superadmin.settings.branding') }}" class="nav-link {{ request()->routeIs('superadmin.settings.branding', 'superadmin.settings.payments', 'superadmin.settings.announcements', 'superadmin.settings.pages', 'superadmin.settings.appearance', 'superadmin.settings.logo', 'superadmin.settings.reports') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i> System Settings
            </a>
            <a href="{{ route('superadmin.settings.hero') }}" class="nav-link {{ request()->routeIs('superadmin.settings.hero', 'superadmin.settings.about', 'superadmin.settings.features', 'superadmin.settings.gallery', 'superadmin.settings.faq', 'superadmin.settings.contact') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i> Homepage Editor
            </a>
            <a href="{{ route('superadmin.settings.legal') }}" class="nav-link {{ request()->routeIs('superadmin.settings.legal*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Legal Documents
            </a>

            <div class="nav-section">System</div>
            <a href="{{ route('superadmin.audit-logs') }}" class="nav-link {{ request()->routeIs('superadmin.audit-logs') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Audit Logs
            </a>
        </nav>

        @include('layouts.partials.panel-footer', ['current' => 'superadmin'])
    </div>

    <div class="main-content">
        <div class="top-bar">
            {{-- Restores navigation on mobile, where the sidebar is off-canvas. --}}
            <button type="button" class="panel-hamburger" data-drawer-toggle="#panelSidebar"
                    aria-controls="panelSidebar" aria-expanded="false" aria-label="Open navigation menu">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
            <div class="top-bar-actions">
                <x-realtime-clock />
                <x-notification-bell />
            </div>
        </div>

        {{-- Scrim behind the off-canvas sidebar. Tapping it closes the drawer. --}}
        <div class="panel-backdrop" data-drawer-backdrop="#panelSidebar" aria-hidden="true"></div>

        <div id="pjax-content" data-pjax-swap>
            {{-- Keep SuperAdmin page components scoped to their own visual
                 vocabulary while leaving the shared panel chrome untouched. --}}
            <div class="sa">
                @stack('styles')

                @include('layouts.partials.panel-alerts')

                @hasSection('header')
                    <h1 class="header-title">@yield('header')</h1>
                @endif

                {{-- Shared CMS Live Preview — rendered only on CMS pages that
                     push a `cms-preview` section. --}}
                @if (View::hasSection('cms-preview'))
                    <div id="cmsLivePreview" class="pv-shared-mount">
                        @yield('cms-preview')
                    </div>
                @endif

                @yield('content')
                @stack('scripts')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    @include('components.accessibility-toolbar')
    {!! \App\Services\BrandingService::logoAutoSwitchScript() !!}
</body>
</html>
