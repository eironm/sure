{{-- Modern segmented settings navigation --}}
@php
    $settingsMeta = [
        'admin.settings.categories.*' => [
            'icon'  => 'bi-grid-fill',
            'title' => 'Facility Categories',
            'desc'  => 'Group facilities into browsable categories with icons and brand colors.',
        ],
        'admin.settings.tags.*' => [
            'icon'  => 'bi-tag-fill',
            'title' => 'Facility Tags',
            'desc'  => 'Searchable amenity and feature labels attached to facilities.',
        ],
        'admin.settings.blackout-dates.*' => [
            'icon'  => 'bi-calendar-x-fill',
            'title' => 'Blackout Dates',
            'desc'  => 'Block reservations on holidays, maintenance windows, and institutional events.',
        ],
        'admin.settings.logistical-items.*' => [
            'icon'  => 'bi-tools',
            'title' => 'Logistical Items',
            'desc'  => 'Rentable equipment and arrangements offered alongside facility bookings.',
        ],
    ];
    $currentMeta = collect($settingsMeta)->first(fn ($m, $pattern) => request()->routeIs($pattern));

    $settingsTabs = [
        ['route' => 'admin.settings.categories.index',      'match' => 'admin.settings.categories.*',       'icon' => 'bi-grid-fill',        'label' => 'Categories'],
        ['route' => 'admin.settings.tags.index',            'match' => 'admin.settings.tags.*',             'icon' => 'bi-tag-fill',         'label' => 'Tags'],
        ['route' => 'admin.settings.blackout-dates.index',  'match' => 'admin.settings.blackout-dates.*',   'icon' => 'bi-calendar-x-fill',  'label' => 'Blackout Dates'],
        ['route' => 'admin.settings.logistical-items.index','match' => 'admin.settings.logistical-items.*', 'icon' => 'bi-tools',            'label' => 'Logistical Items'],
    ];
@endphp

<style>
    .settings-tabs {
        display: flex;
        gap: .4rem;
        background: #fff;
        border: 1px solid var(--line, #e7eaf0);
        border-radius: 14px;
        padding: .45rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        overflow-x: auto;
    }
    .settings-tab {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        padding: .7rem 1rem;
        border-radius: 10px;
        font-size: .875rem;
        font-weight: 700;
        color: var(--ink-3, #94a3b8);
        text-decoration: none;
        white-space: nowrap;
        transition: all .18s ease;
    }
    .settings-tab i { font-size: 1rem; }
    .settings-tab:hover { color: var(--ink, #0f172a); background: #f4f6fa; }
    .settings-tab.active {
        color: #fff;
        background: linear-gradient(135deg, var(--accent, #7c3aed), rgba(var(--accent-rgb, 124, 58, 237), .8));
        box-shadow: 0 4px 12px rgba(var(--accent-rgb, 124, 58, 237), .35);
    }
</style>

@if($currentMeta)
    <div class="settings-hero">
        <div class="settings-hero-icon"><i class="bi {{ $currentMeta['icon'] }}"></i></div>
        <div>
            <h2>{{ $currentMeta['title'] }}</h2>
            <p>{{ $currentMeta['desc'] }}</p>
        </div>
    </div>
    <style>
        .settings-hero {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: linear-gradient(135deg, var(--sidebar-bg, #0f172a), #1e293b 70%, rgba(var(--accent-rgb, 124, 58, 237), .45) 150%);
            border-radius: 16px;
            padding: 1.4rem 1.75rem;
            margin-bottom: 1.25rem;
            color: #fff;
        }
        .settings-hero-icon {
            width: 3rem; height: 3rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, .12);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .settings-hero h2 { font-size: 1.15rem; font-weight: 800; margin: 0 0 .2rem; letter-spacing: -.01em; }
        .settings-hero p { font-size: .85rem; margin: 0; color: rgba(255, 255, 255, .65); }
    </style>
@endif

<nav class="settings-tabs" aria-label="Settings sections">
    @foreach($settingsTabs as $tab)
        <a href="{{ route($tab['route']) }}" class="settings-tab {{ request()->routeIs($tab['match']) ? 'active' : '' }}">
            <i class="bi {{ $tab['icon'] }}"></i> {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
