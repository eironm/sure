<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ branding('system_name', 'Platform') }} - {{ branding('system_tagline', 'Simplify University Reservations') }}</title>
    @php $hpMetaDesc = trim(branding('meta_description', '')) ?: branding('system_tagline', 'Simplify University Reservations'); @endphp
    <meta name="description" content="{{ $hpMetaDesc }}">
    @if (trim(branding('site_keywords', '')))
        <meta name="keywords" content="{{ branding('site_keywords') }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ branding('system_name', 'Platform') }}">
    <meta property="og:description" content="{{ $hpMetaDesc }}">
    @if (trim(branding('og_image', '')))
        <meta property="og:image" content="{{ asset(branding('og_image')) }}">
    @endif
    <meta property="og:site_name" content="{{ branding('system_name', 'Platform') }}">
    <link rel="icon" type="{{ brand_favicon_mimetype() }}" href="{{ brand_favicon() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/@panzoom/panzoom/dist/panzoom.min.js"></script>
    @vite(['resources/css/app.css', 'resources/css/mobile.css', 'resources/css/home-new.css', 'resources/js/app.js', 'resources/js/home-new.js'])
    <style>
        :root {
            {!! brand_css_vars() !!}
            @if (trim(branding('public_font', '')))
                --hn-public-font: '{{ branding('public_font') }}';
            @endif
            /* The homepage CSS palette is built statically by Vite, so remap
               every brand-meaningful token to the live CMS colors here. */
            --hn-primary: var(--brand-primary);
            --hn-dark: var(--brand-dark);
            --hn-dark-2: var(--brand-dark);
            --hn-primary-rgb: var(--brand-primary-rgb);
        }
        /* CTA buttons: the static stylesheet uses literal indigo/purple hexes,
           so re-skin them with the live CMS primary + secondary. */
        .hn-btn-primary {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)) !important;
            box-shadow: 0 8px 24px rgba(var(--brand-primary-rgb), .35);
        }
        @if (trim(branding('public_font', '')))
        body { font-family: var(--hn-public-font), 'Inter', sans-serif; }
        @endif
        @php
            $heroStart = trim(branding('hero_gradient_start', ''));
            $heroEnd   = trim(branding('hero_gradient_end', ''));
            $heroEnabled = branding('hero_gradient_enabled', '1') !== '0';
            // When both fields are empty or when toggle is disabled, gradient is completely GONE/DISABLED.
            // Color gradient ONLY renders when enabled AND at least one color field is explicitly configured.
            $hasHeroGradient = $heroEnabled && ($heroStart !== '' || $heroEnd !== '');
        @endphp
        @if ($hasHeroGradient)
        .hn-hero-scrim {
            background: linear-gradient(120deg,
                rgba(15, 23, 42, .88) 0%,
                rgba(15, 23, 42, .62) 45%,
                {{ $heroStart ?: '#2563eb' }} 82%,
                {{ $heroEnd ?: '#0f172a' }} 100%) !important;
        }
        @else
        .hn-hero-scrim {
            background: linear-gradient(120deg,
                rgba(15, 23, 42, .88) 0%,
                rgba(15, 23, 42, .62) 50%,
                rgba(15, 23, 42, .45) 100%) !important;
        }
        @endif
    </style>
    @include('layouts.partials.clarity')
</head>
<body>

{{-- CMS announcement banner (public site) --}}
@if (branding('announcement_enabled') === '1' && trim(branding('announcement_text', '')) !== '')
<div class="hn-announcement" id="hnAnnouncement" role="banner">
    <div class="container py-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
            <span class="hn-announcement-text flex-grow-1">{!! branding('announcement_text') !!}</span>
            <button type="button" class="hn-announcement-close" aria-label="Dismiss announcement" onclick="dismissAnnouncement()">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</div>
<script>(function(){var announcement=document.getElementById('hnAnnouncement'),root=document.documentElement;function sync(){root.style.setProperty('--hn-announcement-h',(announcement?announcement.offsetHeight:0)+'px')}try{if(localStorage.getItem('hn-announcement-dismissed')){announcement?.remove();announcement=null}}catch(e){}window.dismissAnnouncement=function(){announcement?.remove();announcement=null;root.style.setProperty('--hn-announcement-h','0px');try{localStorage.setItem('hn-announcement-dismissed','1')}catch(e){}};sync();requestAnimationFrame(sync);window.addEventListener('resize',sync,{passive:true})})();</script>
<style>
    .hn-announcement { position: fixed; top: 0; left: 0; right: 0; z-index: 1060; background: linear-gradient(90deg, {{ branding('brand_primary', '#2563eb') }}, {{ branding('brand_secondary', '#7c3aed') }}); color: #fff; }
    .hn-announcement-text { font-size: .88rem; font-weight: 500; }
    .hn-announcement-close {
        background: rgba(255,255,255,.15); border: 0; color: #fff;
        width: 1.6rem; height: 1.6rem; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .8rem; cursor: pointer; flex-shrink: 0;
    }
    .hn-announcement-close:hover { background: rgba(255,255,255,.3); }
</style>
@endif

{{-- ================================================================
     NAVIGATION
     ================================================================ --}}
<header class="hn-nav" id="hnNav" aria-label="Primary site navigation">
    <div class="container">
        <div class="hn-nav-inner">
            <a class="hn-brand" href="#top" aria-label="{{ branding('system_name', 'Platform') }} Home">
                {{-- Dual-variant navbar logo: the transparent hero needs the
                     light variant, the white scrolled bar needs the dark one.
                     Both come straight from the CMS; CSS toggles visibility --}}
                <img class="hn-logo{{ \App\Services\BrandingService::logoAutoVariant() ? ' brand-logo-auto' : '' }}{{ \App\Services\BrandingService::wordmarkOnly() ? ' brand-wordmark-only' : '' }}"
                     src="{{ \App\Services\BrandingService::logoForBg('dark') }}"
                     data-logo-light="{{ \App\Services\BrandingService::logoForBg('dark') }}"
                     data-logo-dark="{{ \App\Services\BrandingService::logoForBg('light') }}"
                     alt="{{ branding('system_name', 'Platform') }} Logo"/>
            <span class="hn-brand-name{{ \App\Services\BrandingService::wordmarkOnly() ? ' d-none' : '' }}">{{ branding('system_name', 'Platform') }}</span>
            </a>
            <nav class="hn-links" aria-label="Primary">
                <a href="#about">About</a>
                <a href="#features">Features</a>
                <a href="#gallery">Gallery</a>
                <a href="#faq">FAQ</a>
                <a href="#contact">Contact</a>
            </nav>
            <div class="hn-actions">
                @auth
                    <a href="{{ route('notifications.index') }}" class="hn-icon-btn" aria-label="Notifications"><i class="bi bi-bell"></i></a>
                    <a href="{{ route('dashboard') }}" class="hn-btn hn-btn-soft">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline m-0">
                        @csrf
                        <button type="submit" class="hn-btn hn-btn-outline">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hn-btn hn-btn-outline">Log in</a>
                    <a href="{{ route('register') }}" class="hn-btn hn-btn-primary">Sign up</a>
                @endauth
            </div>
            <button class="hn-burger" id="hnBurger" type="button" data-drawer-toggle="#homeDrawer" aria-label="Open menu" aria-expanded="false" aria-controls="homeDrawer">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>

{{-- Mobile drawer (engine: mobile-nav.js) - keep markup compatible --}}
<div class="mobile-drawer-backdrop" data-drawer-backdrop="#homeDrawer" aria-hidden="true"></div>
<div class="mobile-drawer" id="homeDrawer" data-drawer role="dialog" aria-modal="true" aria-label="Menu" aria-hidden="true">
    <div class="mobile-drawer-header">
        <div class="mobile-drawer-name">{{ branding('system_name', 'Platform') }}</div>
        <button type="button" class="mobile-drawer-close" data-drawer-close aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="mobile-drawer-nav" aria-label="Mobile navigation">
        <div class="mobile-drawer-section">Browse</div>
        <a class="mobile-drawer-link" href="#top"><i class="bi bi-house-door-fill"></i><span>Home</span></a>
        <a class="mobile-drawer-link" href="#about"><i class="bi bi-info-circle-fill"></i><span>About Us</span></a>
        <a class="mobile-drawer-link" href="#features"><i class="bi bi-grid-fill"></i><span>Features</span></a>
        <a class="mobile-drawer-link" href="#gallery"><i class="bi bi-images"></i><span>Gallery</span></a>
        <a class="mobile-drawer-link" href="#faq"><i class="bi bi-question-circle-fill"></i><span>FAQ</span></a>
        <a class="mobile-drawer-link" href="#contact"><i class="bi bi-envelope-fill"></i><span>Contact</span></a>
        <div class="mobile-drawer-section">Account</div>
        @auth
            <a class="mobile-drawer-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
            <form method="POST" action="{{ route('logout') }}" class="mobile-drawer-form">
                @csrf
                <button type="submit" class="mobile-drawer-link"><i class="bi bi-box-arrow-right"></i><span>Log out</span></button>
            </form>
        @else
            <a class="mobile-drawer-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i><span>Log in</span></a>
            <a class="mobile-drawer-link" href="{{ route('register') }}"><i class="bi bi-person-plus-fill"></i><span>Sign up</span></a>
        @endauth
    </nav>
</div>

<main id="top">

@php
    $hnHeroMedia = homepage_content('hero.media', []);
    if (!is_array($hnHeroMedia)) {
        $hnHeroMedia = [];
    }
    $hnHeroMediaEnabled = filter_var($hnHeroMedia['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true;
    $hnHeroMediaType = in_array($hnHeroMedia['type'] ?? 'video', ['image', 'video', 'gif'], true) ? ($hnHeroMedia['type'] ?? 'video') : 'video';
    $hnHeroMediaUrl = asset($hnHeroMedia['url'] ?? 'assets/figma.mp4');
    $hnHeroMediaPoster = asset($hnHeroMedia['poster'] ?? 'assets/figma-not.jpg');
    $hnHeroMediaAlt = trim((string) ($hnHeroMedia['alt'] ?? 'Homepage hero media'));
    $hnHeroVideoMime = str_ends_with(strtolower((string) ($hnHeroMedia['url'] ?? 'assets/figma.mp4')), '.webm') ? 'video/webm' : (str_ends_with(strtolower((string) ($hnHeroMedia['url'] ?? 'assets/figma.mp4')), '.ogg') ? 'video/ogg' : 'video/mp4');
    /* CMS homepage content - CMS values override these defaults. */
    $hnBadges = homepage_content('hero.badges');
    if (!is_array($hnBadges) || !$hnBadges) {
        $hnBadges = [
            ['label' => '100% online process'],
            ['label' => 'Real-time status updates'],
            ['label' => 'No double bookings'],
        ];
    }
    $hnFeatures = homepage_content('features.cards');
    if (!is_array($hnFeatures) || !$hnFeatures) {
        $hnFeatures = [
            ['icon' => 'bi-calendar2-plus-fill', 'title' => 'Organized & reliable', 'body' => 'A reservation experience that is organized, reliable, and accessible for the entire university community.'],
            ['icon' => 'bi-eye-fill', 'title' => 'Transparent by default', 'body' => 'Fostering transparency and efficient facility management - the standard platform for campus reservations.'],
            ['icon' => 'bi-bell-fill', 'title' => 'Track every step', 'body' => 'Follow each request step-by-step with real-time status updates and automated reminders for seamless coordination.'],
            ['icon' => 'bi-graph-up-arrow', 'title' => 'Insights for decision-makers', 'body' => 'Clear logs and resource insights to better support university events, academic functions, and student activities.'],
        ];
    }
    $hnGallery = homepage_content('gallery.items');
    if (!is_array($hnGallery) || !$hnGallery) {
        $hnGallery = [
            ['caption' => 'Campus grounds & stands'],
            ['caption' => 'Event spaces'],
            ['caption' => 'Platform preview'],
            ['caption' => 'Dashboard & analytics'],
        ];
    }
    $hnFaq = homepage_content('faq.groups');
    if (!is_array($hnFaq) || !$hnFaq) {
        $hnFaq = [
            ['title' => 'General information', 'questions' => [
                ['q' => 'What is SURE?', 'a' => 'SURE (Simplify University Reservations) is an automated web platform designed to streamline, track, and manage university facility and resource bookings across campus, ensuring a transparent process for all users.'],
                ['q' => 'Who can use the system?', 'a' => 'Students, faculty, administrative staff, recognized student organizations, and authorized external clients across university campuses.'],
                ['q' => 'What facilities and resources can be reserved?', 'a' => 'Users can reserve authorized campus venues (such as auditoriums, gymnasiums, and grounds) as well as shared event equipment.'],
            ]],
            ['title' => 'Reservations &amp; approval process', 'questions' => [
                ['q' => 'How do I submit a campus facility reservation request?', 'a' => 'Log into the portal using your institutional credentials, navigate to the reservation section, select your desired location or equipment, and fill in the event details before submitting for approval.'],
                ['q' => 'How long does the approval process take?', 'a' => 'Processing times depend on the specific venue and administrative review requirements, but every approval step is updated dynamically on your tracking dashboard.'],
            ]],
            ['title' => 'Policies &amp; security', 'questions' => [
                ['q' => 'What is the policy for modifying or cancelling a reservation?', 'a' => 'Cancellations or modifications must be submitted at least 7 days (1 week) prior to your scheduled event date. Late cancellations may result in a temporary suspension of booking privileges.'],
                ['q' => 'How does SURE handle double-booking and system security?', 'a' => 'SURE employs automated soft-lock mechanisms to prevent overlapping submission attempts and enforces role-based access secured by institutional single sign-on authentication.'],
            ]],
            ['title' => 'Support &amp; assistance', 'questions' => [
                ['q' => 'Who do I contact if I need help planning an event?', 'a' => 'You can reach out directly to the facilities administration team via the contact form below or email support for specialized event requirements.'],
            ]],
        ];
    }
@endphp

{{-- ================================================================
     HERO
     ================================================================ --}}
<section class="hn-hero" style="--hn-hero-fallback: url('{{ $hnHeroMediaPoster }}');">
    @if($hnHeroMediaEnabled && $hnHeroMediaType === 'video')
        <img class="hn-hero-poster" src="{{ $hnHeroMediaPoster }}" alt="" aria-hidden="true" data-hero-poster>
        {{--
            VIDEO HERO STRATEGY:
            - preload="none"  → browser downloads ZERO bytes on page load
            - poster           → fallback image always visible while video loads
            - autoplay muted   → browser starts fetching + playing when ready
            - <source> is inline so the browser manages loading natively
              (no JS injection tricks that break canplay/codec negotiation)
        --}}
        <video class="hn-hero-video"
               autoplay muted loop playsinline
               preload="auto"
               poster="{{ $hnHeroMediaPoster }}"
               aria-hidden="true" tabindex="-1"
               data-hero-video>
            <source src="{{ $hnHeroMediaUrl }}" type="{{ $hnHeroVideoMime }}">
        </video>
    @elseif($hnHeroMediaEnabled && in_array($hnHeroMediaType, ['image', 'gif'], true))
        <img class="hn-hero-media-image" src="{{ $hnHeroMediaUrl }}" alt="{{ $hnHeroMediaAlt }}">
    @else
        <img class="hn-hero-media-image" src="{{ $hnHeroMediaPoster }}" alt="{{ $hnHeroMediaAlt }}">
    @endif
    <div class="hn-hero-scrim"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-7 py-5">
                <span class="hn-eyebrow fade-in-up"><i class="bi bi-stars"></i> {{ homepage_content('hero.eyebrow', 'Simplify University Reservations') }}</span>
                <h1 class="hn-hero-title fade-in-up">{{ homepage_content('hero.heading', 'Reserve university facilities') }}</h1>
                <p class="hn-hero-sub fade-in-up">{{ homepage_content('hero.sub', 'Book campus spaces effortlessly with our streamlined reservation platform. Transparent approval, real-time updates, zero double bookings.') }}</p>
                <div class="hn-hero-cta fade-in-up">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hn-btn hn-btn-primary hn-btn-lg">{{ homepage_content('hero.cta_text', 'Reserve a facility') }} <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="hn-btn hn-btn-primary hn-btn-lg">{{ homepage_content('hero.cta_text', 'Get started') }} <i class="bi bi-arrow-right"></i></a>
                    @endauth
                    <a href="#about" class="hn-btn hn-btn-ghost">Learn more <i class="bi bi-chevron-down"></i></a>
                </div>
                <div class="hn-hero-badges fade-in-up">
                    @foreach ($hnBadges as $hnB)
                        @if (!empty($hnB['label']))
                            <span><i class="bi bi-shield-check-fill"></i> {{ $hnB['label'] }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="hn-scroll-hint" aria-hidden="true"><div class="hn-scroll-line"></div><span>Scroll</span></div>
</section>

{{-- ================================================================
     ABOUT / STATS BAND
     ================================================================ --}}
<section id="about" class="hn-section hn-about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 fade-in-up">
                <span class="hn-eyebrow hn-eyebrow-dark">About Us</span>
                <h2 class="hn-section-title">{{ homepage_content('about.title', 'Built for every campus community') }}</h2>
                <p class="hn-lead">
                    <strong>{{ branding('system_name', 'Platform') }}</strong> is a centralized reservation and approval system designed to
                    <em>Simplify University Reservations.</em> Faster scheduling, transparent tracking, and a friendlier experience - built for students, faculty, and administrators alike.
                </p>
                <p class="hn-text-muted">{{ homepage_content('about.sub_text', 'Connecting students, faculty, and administration through a unified platform that makes booking university spaces quick and hassle-free.') }}</p>
                <div class="hn-stat-grid mt-4">
                    <div class="hn-stat-card">
                        <div class="hn-stat-icon hn-stat-icon-primary"><i class="bi bi-globe2"></i></div>
                        <div class="hn-stat-number">100%</div>
                        <div class="hn-stat-label">Online process</div>
                    </div>
                    <div class="hn-stat-card">
                        <div class="hn-stat-icon hn-stat-icon-success"><i class="bi bi-lightning-charge-fill"></i></div>
                        <div class="hn-stat-number">Real-time</div>
                        <div class="hn-stat-label">Status updates</div>
                    </div>
                    <div class="hn-stat-card">
                        <div class="hn-stat-icon hn-stat-icon-info"><i class="bi bi-people-fill"></i></div>
                        <div class="hn-stat-number">Multi-role</div>
                        <div class="hn-stat-label">Access control</div>
                    </div>
                    <div class="hn-stat-card">
                        <div class="hn-stat-icon hn-stat-icon-warning"><i class="bi bi-calendar2-x-fill"></i></div>
                        <div class="hn-stat-number">Zero</div>
                        <div class="hn-stat-label">Double bookings</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 fade-in-up">
                <div class="hn-about-visual position-relative">
                    <img src="{{ asset('assets/stand.jpg') }}" alt="Campus facility - {{ branding('system_name', 'Platform') }}" class="hn-img-raised"/>
                    <div class="hn-img-chip">
                        <span class="hn-img-chip-num">24/7</span>
                        <span class="hn-img-chip-text">Available online</span>
                    </div>
                </div>
                <blockquote class="hn-quote">
                    <i class="bi bi-quote"></i> <span>{{ homepage_content('about.quote', 'Your campus, your space - reserved with confidence, approved with clarity.') }}</span>
                </blockquote>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     FEATURES
     ================================================================ --}}
<section id="features" class="hn-section hn-section-alt">
    <div class="container">
        <div class="hn-section-head text-center fade-in-up">
            <span class="hn-eyebrow hn-eyebrow-dark">Our commitment</span>
            <h2 class="hn-section-title">{{ homepage_content('features.title', 'Why SURE?') }}</h2>
            <p class="hn-section-sub">{{ homepage_content('features.sub', 'Everything our community needs to plan, request, and manage campus facilities - in one place.') }}</p>
        </div>
        <div class="row g-4">
            @foreach ($hnFeatures as $hnF)
                @if (!empty($hnF['title']) || !empty($hnF['body']))
                    <div class="col-md-6 col-lg-3 fade-in-up">
                        <div class="hn-feature-card">
                            <div class="hn-feature-icon hn-feature-icon-primary"><i class="bi {{ $hnF['icon'] ?? 'bi-star' }}"></i></div>
                            <h3>{{ $hnF['title'] }}</h3>
                            <p>{{ $hnF['body'] }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

{{-- ================================================================
     GALLERY
     ================================================================ --}}
<section id="gallery" class="hn-section">
    <div class="container">
        <div class="hn-section-head text-center fade-in-up">
            <span class="hn-eyebrow hn-eyebrow-dark">Facilities</span>
            <h2 class="hn-section-title">{{ homepage_content('gallery.title', 'Your campus. Your space.') }}</h2>
            <p class="hn-section-sub">{{ homepage_content('gallery.sub', 'Explore the venues and spaces our community reserves every day.') }}</p>
        </div>
        @php
            $hnGalleryAssets = ['assets/stand.jpg', 'assets/pilot.jpg', 'assets/figma-not.jpg', 'assets/dashboard-hero.png'];
            $hnGalleryClasses = ['hn-gallery-item-lg', '', '', 'hn-gallery-item-wide'];
        @endphp
        <div class="hn-gallery">
            @foreach ($hnGallery as $hnG)
                @php $hnGi = $loop->index; @endphp
                @php $hnGImg = isset($hnG['image']) && trim((string) $hnG['image']) !== '' ? $hnG['image'] : ($hnGalleryAssets[$hnGi] ?? 'assets/stand.jpg'); @endphp
                <figure class="hn-gallery-item {{ $hnGalleryClasses[$hnGi] ?? '' }} fade-in-up" style="background-image: url('{{ asset($hnGImg) }}');">
                    <div class="hn-gallery-caption">
                        <i class="bi bi-fullscreen"></i>
                        <span>{{ !empty($hnG['caption']) ? $hnG['caption'] : 'Campus facilities' }}</span>
                    </div>
                </figure>
            @endforeach
        </div>
        <p class="text-center hn-text-muted mt-3 fade-in-up">
            <small>{{ homepage_content('gallery.note', 'Click any image to enlarge. Photos captured on campus facilities available for reservation.') }}</small>
        </p>
    </div>
</section>

{{-- Zoom modal (panzoom) --}}
<div class="hn-zoom-modal" id="hnZoomModal" role="dialog" aria-modal="true" aria-label="Image zoom view">
    <div class="hn-zoom-viewport">
        <button class="hn-zoom-close" id="hnZoomClose" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        <img id="hnZoomImg" src="" alt="Expanded facility view"/>
    </div>
</div>

{{-- ================================================================
     FAQ
     ================================================================ --}}
<section id="faq" class="hn-section hn-section-alt">
    <div class="container">
        <div class="hn-section-head text-center fade-in-up">
            <span class="hn-eyebrow hn-eyebrow-dark">Support</span>
            <h2 class="hn-section-title">{{ homepage_content('faq.title', 'Frequently asked questions') }}</h2>
            <p class="hn-section-sub">{{ homepage_content('faq.sub', 'Everything you need to know about booking campus facilities through ' . branding('system_name', 'Platform') . '.') }}</p>
        </div>
        @php
            $validFaqGroups = [];
            foreach ($hnFaq as $gIdx => $gData) {
                $hasQ = !empty($gData['questions']) && is_array($gData['questions']) && count($gData['questions']) > 0;
                if (!empty($gData['title']) || $hasQ) {
                    $validFaqGroups[] = array_merge($gData, ['_index' => $gIdx]);
                }
            }
        @endphp

        @if (count($validFaqGroups) > 0)
            <div class="hn-faq-tabs-container fade-in-up">
                <div class="hn-faq-tabs" role="tablist" aria-label="FAQ Topics">
                    @foreach ($validFaqGroups as $hnGp)
                        @php
                            $qCount = !empty($hnGp['questions']) && is_array($hnGp['questions']) ? count($hnGp['questions']) : 0;
                            $tabLabel = html_entity_decode($hnGp['title'] ?? 'General', ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        @endphp
                        <button type="button"
                                class="hn-faq-tab-btn {{ $loop->first ? 'is-active' : '' }}"
                                role="tab"
                                id="faq-tab-{{ $hnGp['_index'] }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                aria-controls="faq-pane-{{ $hnGp['_index'] }}"
                                data-faq-tab="{{ $hnGp['_index'] }}">
                            <span>{{ $tabLabel }}</span>
                            @if ($qCount > 0)
                                <span class="hn-faq-tab-count">{{ $qCount }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-lg-8">
                    @foreach ($validFaqGroups as $hnGp)
                        <div class="hn-faq-tab-pane {{ $loop->first ? 'is-active' : '' }}"
                             id="faq-pane-{{ $hnGp['_index'] }}"
                             role="tabpanel"
                             aria-labelledby="faq-tab-{{ $hnGp['_index'] }}"
                             data-faq-pane="{{ $hnGp['_index'] }}">
                            <div class="hn-faq fade-in-up">
                                @if (!empty($hnGp['questions']) && is_array($hnGp['questions']) && count($hnGp['questions']) > 0)
                                    @foreach ($hnGp['questions'] as $qIdx => $hnQa)
                                        @if (!empty($hnQa['q']) || !empty($hnQa['a']))
                                            <div class="hn-faq-item">
                                                <button class="hn-faq-btn" type="button" aria-expanded="false" aria-controls="faq-ans-{{ $hnGp['_index'] }}-{{ $qIdx }}">
                                                    <span>{{ $hnQa['q'] }}</span>
                                                    <i class="bi bi-chevron-down hn-faq-icon" aria-hidden="true"></i>
                                                </button>
                                                <div class="hn-faq-panel" id="faq-ans-{{ $hnGp['_index'] }}-{{ $qIdx }}">
                                                    <div class="hn-faq-body">
                                                        {{ $hnQa['a'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center py-4 hn-text-muted">
                                        <p class="mb-0">No questions in this section yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ================================================================
     CONTACT
     ================================================================ --}}
<section id="contact" class="hn-section hn-section-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center fade-in-up mb-4">
                    <span class="hn-eyebrow hn-eyebrow-light">Get in touch</span>
                    <h2 class="hn-section-title text-white">{{ homepage_content('contact.title', 'Contact the facilities team') }}</h2>
                    <p class="hn-section-sub text-white-50">{{ homepage_content('contact.note', 'Send us a message and we will get back to you as soon as possible. Fields marked with * are required.') }}</p>
                </div>
                <div class="hn-contact-card fade-in-up">
                    <div id="hnContactStatus" class="mb-3 d-none" role="alert"></div>
                    @if (session('success'))
                        <div class="alert alert-success mb-0" role="alert"><i class="bi bi-check-circle-fill me-1" aria-hidden="true"></i>{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('contact.store') }}" id="hnContactForm" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="contact_name" class="form-label">Name *</label>
                                <input id="contact_name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="255" autocomplete="name" placeholder="Your full name"/>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact_email" class="form-label">Email *</label>
                                <input id="contact_email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required maxlength="255" autocomplete="email" placeholder="you@example.com"/>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="contact_message" class="form-label">Message *</label>
                                <textarea id="contact_message" name="message" class="form-control @error('message') is-invalid @enderror" rows="5" required minlength="10" maxlength="5000" placeholder="Tell us how we can help - your event date, expected headcount, and any special requirements.">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="hn-btn hn-btn-primary hn-btn-lg w-100"><i class="bi bi-send-fill me-1" aria-hidden="true"></i> Send message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     FOOTER
     ================================================================ --}}
@include('layouts.partials.main-footer')

</main>

<script>
/**
 * Hero video controller — minimal, single-responsibility.
 *
 * The <video> has preload="none" so the browser downloads ZERO bytes on
 * page load. The poster image shows immediately (fast LCP).
 *
 * When the browser's own autoplay kicks in (after the page is painted),
 * it fires 'playing'. We use that single event to cross-fade from the
 * poster to the video. If autoplay is blocked or the file errors, the
 * poster stays visible forever — clean, silent fallback.
 *
 * We do NOT call video.load() or video.play() manually. The native
 * autoplay attribute handles it; manual calls cause race conditions.
 */
(function () {
    var video = document.querySelector('[data-hero-video]');
    if (!video) return;

    video.muted = true;
    video.defaultMuted = true;
    video.playsInline = true;
    video.setAttribute('muted', '');
    video.setAttribute('playsinline', '');

    var poster = document.querySelector('[data-hero-poster]');
    video.addEventListener('playing', function () {
        if (poster) poster.setAttribute('hidden', '');
    }, { once: true });

    function safePlay() {
        if (!video.paused) return;
        var p = video.play();
        if (p && typeof p.catch === 'function') {
            p.catch(function () {
                var onInteract = function () {
                    video.muted = true;
                    video.play().catch(function () {});
                };
                ['touchstart', 'click', 'pointerdown', 'scroll', 'keydown'].forEach(function (evt) {
                    document.addEventListener(evt, onInteract, { once: true, passive: true });
                });
            });
        }
    }

    if (video.readyState >= 2) {
        safePlay();
    } else {
        video.addEventListener('canplay', safePlay, { once: true });
        video.addEventListener('loadeddata', safePlay, { once: true });
    }
    safePlay();
})();

// Intersection observer - fade-in-up
if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.12 });
    document.querySelectorAll('.fade-in-up').forEach(el => io.observe(el));
} else {
    document.querySelectorAll('.fade-in-up').forEach(el => el.classList.add('visible'));
}
// Contact form - AJAX submit (no page reload)
(function () {
    const form = document.getElementById('hnContactForm');
    if (!form) return;
    const status = document.getElementById('hnContactStatus');
    const button = form.querySelector('button[type="submit"]');

    function setStatus(type, message) {
        status.className = 'alert mb-3 ' + (type === 'success' ? 'alert-success' : 'alert-danger');
        status.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill') + ' me-1" aria-hidden="true"></i>' + message;
        status.classList.remove('d-none');
        status.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (form.classList.contains('hn-contact-busy')) return;
        clearErrors();
        status.classList.add('d-none');

        form.classList.add('hn-contact-busy');
        button.disabled = true;
        const originalLabel = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...';

        const data = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: data,
        })
        .then(response => {
            const contentType = response.headers.get('content-type') || '';
            if (response.status === 422 && contentType.includes('application/json')) {
                return response.json().then(payload => { throw Object.assign(new Error('validation'), { errors: payload.errors }); });
            }
            if (!response.ok) throw new Error('network');
            if (contentType.includes('application/json')) return response.json();
            throw new Error('network');
        })
        .then(payload => {
            setStatus('success', payload.message || 'Thank you! Your message has been sent.');
            form.reset();
        })
        .catch(err => {
            if (err.errors) {
                Object.entries(err.errors).forEach(([field, messages]) => {
                    const input = form.elements[field];
                    if (!input) return;
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = (Array.isArray(messages) ? messages[0] : messages) || 'This field is invalid.';
                    input.insertAdjacentElement('afterend', feedback);
                });
                setStatus('danger', 'Please fix the errors below and try again.');
            } else {
                setStatus('danger', 'Something went wrong while sending your message. Please try again, or email us directly.');
            }
        })
        .finally(() => {
            form.classList.remove('hn-contact-busy');
            button.disabled = false;
            button.innerHTML = originalLabel;
        });
    });
})();

// FAQ tabs switching
(function () {
    const tabBtns = document.querySelectorAll('.hn-faq-tab-btn');
    const tabPanes = document.querySelectorAll('.hn-faq-tab-pane');
    if (!tabBtns.length || !tabPanes.length) return;

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-faq-tab');
            tabBtns.forEach(b => {
                const isActive = (b === btn);
                b.classList.toggle('is-active', isActive);
                b.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
            tabPanes.forEach(pane => {
                const isMatch = (pane.getAttribute('data-faq-pane') === targetId);
                pane.classList.toggle('is-active', isMatch);
            });
        });

        btn.addEventListener('keydown', (e) => {
            const btns = Array.from(tabBtns);
            const idx = btns.indexOf(btn);
            let nextBtn = null;
            if (e.key === 'ArrowRight') nextBtn = btns[(idx + 1) % btns.length];
            else if (e.key === 'ArrowLeft') nextBtn = btns[(idx - 1 + btns.length) % btns.length];
            if (nextBtn) {
                e.preventDefault();
                nextBtn.focus();
                nextBtn.click();
            }
        });
    });
})();

// FAQ accordion (vanilla)
document.querySelectorAll('.hn-faq-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const panel = btn.nextElementSibling;
        const isOpen = panel.classList.contains('is-open');
        panel.classList.toggle('is-open', !isOpen);
        btn.setAttribute('aria-expanded', !isOpen);
    });
});
</script>
@include('components.accessibility-toolbar')
{!! \App\Services\BrandingService::logoAutoSwitchScript() !!}
</body>
</html>
