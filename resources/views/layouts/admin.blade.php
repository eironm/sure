<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title', 'Admin') - {{ branding('system_name', 'Platform') }}</title>
    @include('layouts.partials.panel-head')
</head>
<body>
    <div class="sidebar" id="panelSidebar" data-drawer role="navigation" aria-label="Panel navigation">
        <div class="sidebar-header">
            {{-- Rendered as a control only below 1025px; see panel-theme. --}}
            <button type="button" class="sidebar-close" data-drawer-close aria-label="Close navigation menu">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
            <div class="brand-wrapper">
                <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}" data-logo-light="{{ \App\Services\BrandingService::logoForBg('dark') }}" data-logo-dark="{{ \App\Services\BrandingService::logoForBg('light') }}" alt="Logo" class="brand-logo-img{{ \App\Services\BrandingService::logoAutoVariant() ? ' brand-logo-auto' : '' }}{{ \App\Services\BrandingService::wordmarkOnly() ? ' brand-wordmark-only' : '' }}">
                <span class="brand-name{{ \App\Services\BrandingService::wordmarkOnly() ? ' d-none' : '' }}">{{ branding('system_name', 'Platform') }}</span>
            </div>
            <div class="current-panel-badge">
                <i class="bi bi-circle-fill" style="font-size: 0.35rem;"></i>
                {{ Auth::user()->isStaff() ? 'Staff Panel' : 'Admin Panel' }}
            </div>
        </div>

        <nav id="pjax-sidenav" data-pjax-swap>
            <div class="nav-section">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.reservations.index') }}" class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> Reservations
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Reports
            </a>

            <div class="nav-section">Management</div>
            @if(!Auth::user()->isStaff())
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> User Management
            </a>
            @endif
            <a href="{{ route('admin.facilities.index') }}" class="nav-link {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Facilities
            </a>

            <div class="nav-section">System</div>
            <a href="{{ route('admin.settings.categories.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Settings
            </a>
            @if(!Auth::user()->isStaff())
            <a href="{{ route('admin.audit-logs') }}" class="nav-link {{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Audit Logs
            </a>
            @endif
        </nav>

        @include('layouts.partials.panel-footer', ['current' => 'admin'])
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
            @stack('styles')

            @include('layouts.partials.panel-alerts')

            @hasSection('header')
                <h1 class="header-title">@yield('header')</h1>
            @endif

            @yield('content')

            @stack('scripts')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    @include('components.accessibility-toolbar')
    {!! \App\Services\BrandingService::logoAutoSwitchScript() !!}
</body>
</html>

