{{-- Shared sidebar footer for the staff-side panels.
     Usage: @include('layouts.partials.panel-footer', ['current' => 'admin'])
     Panel jumpers: max three buttons are shown at once — the panel
     the user is already viewing is hidden from the list, keeping the
     switcher compact. Role reachability still gates each button. --}}
@php
    $user = Auth::user();
    $canSuper  = $user->isSuperAdmin();
    $canAdmin  = $user->isAdminOrAbove() || $user->isStaff();
    $canCashier = $user->canAccessCashier();
    $canGuest  = ! $user->isStaff();
@endphp

<div class="sidebar-footer">
    <div class="user-info">
        <x-user-avatar :user="$user" size="sm" />
        <div class="user-details">
            <span class="user-name">{{ $user->name }}</span>
            <span class="user-role">{{ $user->roleLabel() }}</span>
        </div>
    </div>

    @if(! $user->isRestrictedCashier())
        <div class="panel-switcher">
            @if($canSuper)
                <a href="{{ route('superadmin.dashboard') }}"
                   class="switch-btn {{ $current === 'superadmin' ? 'd-none' : '' }}"
                   title="SuperAdmin Panel">
                    <i class="bi bi-shield-lock"></i> Super
                </a>
            @endif
            @if($canAdmin)
                <a href="{{ route('admin.dashboard') }}"
                   class="switch-btn {{ $current === 'admin' ? 'd-none' : '' }}"
                   title="Admin Panel">
                    <i class="bi bi-grid-3x3-gap"></i> Admin
                </a>
            @endif
            @if($canCashier)
                <a href="{{ route('cashier.dashboard') }}"
                   class="switch-btn {{ $current === 'cashier' ? 'd-none' : '' }}"
                   title="Cashier Panel">
                    <i class="bi bi-cash-coin"></i> Cashier
                </a>
            @endif
            @if($canGuest)
                <a href="{{ route('guest.dashboard') }}"
                   class="switch-btn {{ $current === 'guest' ? 'd-none' : '' }}"
                   title="Guest Panel">
                    <i class="bi bi-person-badge"></i> Guest
                </a>
            @endif
        </div>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>
