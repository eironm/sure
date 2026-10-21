{{--
    Mobile bottom tab bar
    ---------------------
    Surfaces the four destinations that make up the guest journey so they
    are reachable with a thumb, instead of being buried behind the
    hamburger at the top of the screen.

    Rendered only for authenticated users and only shown under 992px by
    the .mobile-tabbar rule in resources/css/mobile.css.

    $canReserve is resolved once in layouts.app and passed in. When the
    user already holds an active reservation the slot falls back to the
    facility board, so the bar keeps a stable four-item layout instead of
    reflowing to three.
--}}
@php($canReserve = $canReserve ?? true)

<nav class="mobile-tabbar" aria-label="Primary">
    <div class="mobile-tabbar-inner">

        <a href="{{ route('guest.dashboard') }}"
           class="mobile-tab {{ request()->routeIs('guest.dashboard') ? 'active' : '' }}"
           @if(request()->routeIs('guest.dashboard')) aria-current="page" @endif>
            <i class="bi {{ request()->routeIs('guest.dashboard') ? 'bi-grid-1x2-fill' : 'bi-grid-1x2' }}" aria-hidden="true"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('guest.facilities') }}"
           class="mobile-tab {{ request()->routeIs('guest.facilities') ? 'active' : '' }}"
           @if(request()->routeIs('guest.facilities')) aria-current="page" @endif>
            <i class="bi {{ request()->routeIs('guest.facilities') ? 'bi-map-fill' : 'bi-map' }}" aria-hidden="true"></i>
            <span>Explore</span>
        </a>

        @if ($canReserve)
            <a href="{{ route('guest.reserve') }}"
               class="mobile-tab tab-primary {{ request()->routeIs('guest.reserve') ? 'active' : '' }}"
               @if(request()->routeIs('guest.reserve')) aria-current="page" @endif>
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Reserve</span>
            </a>
        @else
            <a href="{{ route('guest.facility-board') }}"
               class="mobile-tab {{ request()->routeIs('guest.facility-board') ? 'active' : '' }}"
               @if(request()->routeIs('guest.facility-board')) aria-current="page" @endif>
                <i class="bi {{ request()->routeIs('guest.facility-board') ? 'bi-calendar3-week-fill' : 'bi-calendar3-week' }}" aria-hidden="true"></i>
                <span>Board</span>
            </a>
        @endif

        <a href="{{ route('guest.history') }}"
           class="mobile-tab {{ request()->routeIs('guest.history') ? 'active' : '' }}"
           @if(request()->routeIs('guest.history')) aria-current="page" @endif>
            <i class="bi {{ request()->routeIs('guest.history') ? 'bi-clock-history' : 'bi-clock' }}" aria-hidden="true"></i>
            <span>Bookings</span>
        </a>

        <a href="{{ route('profile.edit') }}"
           class="mobile-tab {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
           @if(request()->routeIs('profile.edit')) aria-current="page" @endif>
            <i class="bi {{ request()->routeIs('profile.edit') ? 'bi-person-fill' : 'bi-person' }}" aria-hidden="true"></i>
            <span>Profile</span>
        </a>

    </div>
</nav>
