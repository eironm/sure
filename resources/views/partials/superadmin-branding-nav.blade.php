{{-- CMS & Branding sub-navigation.
     Renders only the categories that belong to the current page as real
     route links (the three parent pages already live in the main sidebar).
     Legal Documents keeps anchor links with scroll-spy (single page).
--}}
@php $page = $page ?? 'system'; @endphp

<div class="branding-subnav" role="navigation" aria-label="Content &amp; Branding">
    <div class="bsn-title">Content &amp; Branding</div>

    {{-- System Settings categories ─────────────────────────────── --}}
    @if ($page === 'system')
        <a href="{{ route('superadmin.settings.branding') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.branding') ? 'active' : '' }}">
            <i class="bi bi-palette-fill" aria-hidden="true"></i> Branding &amp; Identity
        </a>
        @if (request()->routeIs('superadmin.settings.branding'))
            <div class="bsn-subsections" id="brandingSubsections">
                <a href="#sec-identity" class="bsn-sub-cat bsn-scroll" data-section="sec-identity">
                    <i class="bi bi-fonts"></i> Identity &amp; Names
                </a>
                <a href="#sec-colors" class="bsn-sub-cat bsn-scroll" data-section="sec-colors">
                    <i class="bi bi-paint-bucket"></i> Brand Colors
                </a>
                <a href="#sec-hero" class="bsn-sub-cat bsn-scroll" data-section="sec-hero">
                    <i class="bi bi-card-heading"></i> Hero &amp; Landing
                </a>
                <a href="#sec-contact" class="bsn-sub-cat bsn-scroll" data-section="sec-contact">
                    <i class="bi bi-envelope-at-fill"></i> Contact &amp; Footer
                </a>
                <a href="#sec-social" class="bsn-sub-cat bsn-scroll" data-section="sec-social">
                    <i class="bi bi-share-fill"></i> Social Media
                </a>
                <a href="#sec-seo" class="bsn-sub-cat bsn-scroll" data-section="sec-seo">
                    <i class="bi bi-search"></i> SEO &amp; Meta
                </a>
                <a href="#sec-email" class="bsn-sub-cat bsn-scroll" data-section="sec-email">
                    <i class="bi bi-send-fill"></i> Email Identity
                </a>
                <a href="#sec-policies" class="bsn-sub-cat bsn-scroll" data-section="sec-policies">
                    <i class="bi bi-shield-check"></i> Reservation Policies
                </a>
                <a href="#sec-reserve-copy" class="bsn-sub-cat bsn-scroll" data-section="sec-reserve-copy">
                    <i class="bi bi-calendar-plus"></i> Reserve Page Copy
                </a>
            </div>
        @endif
        <a href="{{ route('superadmin.settings.payments') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.payments') ? 'active' : '' }}">
            <i class="bi bi-credit-card-fill" aria-hidden="true"></i> Payment Methods
        </a>
        <a href="{{ route('superadmin.settings.announcements') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.announcements') ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill" aria-hidden="true"></i> Announcements
        </a>
        <a href="{{ route('superadmin.settings.pages') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.pages') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text" aria-hidden="true"></i> Login, Error &amp; Reserve Pages
        </a>
        <a href="{{ route('superadmin.settings.appearance') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.appearance') ? 'active' : '' }}">
            <i class="bi bi-brush-fill" aria-hidden="true"></i> Site Appearance
        </a>
        <a href="{{ route('superadmin.settings.logo') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.logo') ? 'active' : '' }}">
            <i class="bi bi-images" aria-hidden="true"></i> Logo Variants
        </a>
        <a href="{{ route('superadmin.settings.reports') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.reports', 'reports.letterhead-cms') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-richtext-fill" aria-hidden="true"></i> Report Letterhead &amp; Footer
        </a>
    @endif

    {{-- Homepage Editor sections ────────────────────────────────── --}}
    @if ($page === 'homepage')
        <a href="{{ route('superadmin.settings.hero') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.hero') ? 'active' : '' }}">
            <i class="bi bi-stars" aria-hidden="true"></i> Hero Section
        </a>
        <a href="{{ route('superadmin.settings.about') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.about') ? 'active' : '' }}">
            <i class="bi bi-info-circle-fill" aria-hidden="true"></i> About Section
        </a>
        <a href="{{ route('superadmin.settings.features') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.features') ? 'active' : '' }}">
            <i class="bi bi-grid-fill" aria-hidden="true"></i> Features Section
        </a>
        <a href="{{ route('superadmin.settings.gallery') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.gallery') ? 'active' : '' }}">
            <i class="bi bi-images" aria-hidden="true"></i> Gallery Section
        </a>
        <a href="{{ route('superadmin.settings.faq') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.faq') ? 'active' : '' }}">
            <i class="bi bi-question-circle-fill" aria-hidden="true"></i> FAQ Section
        </a>
        <a href="{{ route('superadmin.settings.contact') }}"
           class="bsn-cat {{ request()->routeIs('superadmin.settings.contact') ? 'active' : '' }}">
            <i class="bi bi-telephone-fill" aria-hidden="true"></i> Contact Section
        </a>
    @endif

    {{-- Legal Documents — single page, anchor links with scroll-spy --}}
    @if ($page === 'legal')
        <a href="#doc-terms" class="bsn-cat {{ request()->routeIs('superadmin.settings.legal*') ? 'bsn-scroll' : '' }}"
           data-doc="doc-terms">
            <i class="bi bi-file-earmark-check-fill" aria-hidden="true"></i> Terms of Service
        </a>
        <a href="#doc-privacy" class="bsn-cat bsn-scroll" data-doc="doc-privacy">
            <i class="bi bi-shield-lock-fill" aria-hidden="true"></i> Privacy Policy
        </a>
        <a href="#doc-data_collection" class="bsn-cat bsn-scroll" data-doc="doc-data_collection">
            <i class="bi bi-database-fill" aria-hidden="true"></i> Data Collection Notice
        </a>
    @endif
</div>
