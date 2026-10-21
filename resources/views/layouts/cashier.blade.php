<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title', 'Cashier') - {{ branding('system_name', 'Platform') }}</title>
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
                <i class="bi bi-cash-coin"></i>
                Cashier Panel
            </div>
        </div>

        <nav id="pjax-sidenav" data-pjax-swap>
            <div class="nav-section">Overview</div>
            <a href="{{ route('cashier.dashboard') }}" class="nav-link {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-section">Payments</div>
            <a href="{{ route('cashier.payments.index') }}" class="nav-link {{ request()->routeIs('cashier.payments*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> Payment Queue
            </a>
            <a href="{{ route('cashier.report') }}" class="nav-link {{ request()->routeIs('cashier.report*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Payment Report
            </a>
        </nav>

        @include('layouts.partials.panel-footer', ['current' => 'cashier'])
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

            <h1 class="header-title">@yield('heading', 'Cashier Panel')</h1>

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

