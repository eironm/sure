{{-- Aurora CMS Live Preview.
     A faithful mini-mockup of the public homepage, driven live by the current
     form values on EVERY category page (nothing is saved until "Save All
     Settings" is pressed). Saved DB values are always seeded, so the preview
     never shows hardcoded placeholders on pages without form inputs.
     Structure mirrors resources/views/home-page.blade.php:
       announcement banner -> dark navbar (auto light/dark logo) -> hero
       (scrim + white text + badges + scroll hint). --}}
@php
    // Web app uses AUTO light/dark logo switching (logo_auto_variant):
    // dark surface -> uploaded white variant (fallback: light); light surface -> dark variant.
    // system_logo is email-only; the web app renders logo_light / logo_white / logo_dark.
    $pvLogoDark   = \App\Services\BrandingService::logoForBg('dark');   // white/light logo for the dark navbar
    $pvLogoLight  = \App\Services\BrandingService::logoForBg('light');  // dark logo for light surfaces
    $pvWordmarkOnly = \App\Services\BrandingService::wordmarkOnly();
    // Saved DB values — always seeded into the preview regardless of the page.
    $pvPrimary   = \App\Models\SystemSetting::getValue('brand_primary', '#2563eb');
    $pvSecondary = \App\Models\SystemSetting::getValue('brand_secondary', '#7c3aed');
    $pvAccent    = \App\Models\SystemSetting::getValue('brand_accent', '#06b6d4');
    $pvDark      = \App\Models\SystemSetting::getValue('brand_dark', '#0f172a');
    $pvAnnEnabled = \App\Models\SystemSetting::getValue('announcement_enabled', '0') === '1';
    $pvAnnText    = trim((string) \App\Models\SystemSetting::getValue('announcement_text', ''));
    $pvSysName    = \App\Models\SystemSetting::getValue('system_name', 'System Name');
@endphp
<div class="cms-preview-card" id="cmsPreviewCard"
     data-pv-primary="{{ preg_match('/^#[0-9a-fA-F]{6}$/', $pvPrimary)   ? $pvPrimary   : '#2563eb' }}"
     data-pv-secondary="{{ preg_match('/^#[0-9a-fA-F]{6}$/', $pvSecondary) ? $pvSecondary : '#7c3aed' }}"
     data-pv-accent="{{ preg_match('/^#[0-9a-fA-F]{6}$/', $pvAccent)    ? $pvAccent    : '#06b6d4' }}"
          data-pv-dark="{{ preg_match('/^#[0-9a-fA-F]{6}$/', $pvDark)        ? $pvDark      : '#0f172a' }}"
     data-pv-logo-dark="{{ $pvLogoDark }}"
     data-pv-logo-light="{{ $pvLogoLight }}"
     data-pv-logo-fallback="{{ \App\Services\BrandingService::logoVariant('white') }}"
     data-pv-wordmark-only="{{ $pvWordmarkOnly ? '1' : '0' }}"
     data-pv-sys-name="{{ e($pvSysName) }}"
     data-pv-hero-heading="{{ e(\App\Models\SystemSetting::getValue('hero.heading', '')) }}"
     data-pv-hero-subheading="{{ e(\App\Models\SystemSetting::getValue('hero.sub', '')) }}"
     data-pv-hero-eyebrow="{{ e(\App\Models\SystemSetting::getValue('hero.eyebrow', '')) }}"
     data-pv-ann-enabled="{{ $pvAnnEnabled ? '1' : '0' }}"
     data-pv-ann-text="{{ e($pvAnnText) }}">

    <div class="cms-preview-head">
        <h6><i class="bi bi-eye-fill"></i> Live Preview</h6>
        <span class="cms-live-status">
            <span class="cms-preview-live-dot"></span> Unsaved &mdash; reflects current form values
        </span>
    </div>
    <div class="cms-preview-body pv-phone">

        {{-- ── Announcement banner (hidden until announcement_enabled is on) ── --}}
        <div class="pv-annbar{{ $pvAnnEnabled ? '' : ' pv-annbar-off' }}" id="pvAnnBar">
            <i class="bi bi-megaphone-fill"></i>
            <span class="pv-annbar-text" id="pvAnnText">{{ $pvAnnText ?: 'University facilities reservation system is now live.' }}</span>
            <span class="pv-annbar-x"><i class="bi bi-x-lg"></i></span>
        </div>

        {{-- ── Navbar: dark surface, auto light/dark logo (matches web app behavior) ── --}}
        <div class="pv-appbar" id="pvNavbar"
             data-pv-logo-dark="{{ $pvLogoDark }}"
             data-pv-logo-light="{{ $pvLogoLight }}"
             data-pv-logo-fallback="{{ \App\Services\BrandingService::logoVariant('white') }}">
            <span class="pv-brand">
                <img id="pvLogoImg" src="{{ $pvLogoDark }}" alt="Logo"
                     onerror="this.style.display='none';document.getElementById('pvLogoFallback').style.display='flex';">
                <span id="pvLogoFallback" class="pv-logo-fallback" style="display:none;">LOGO</span>
                <span class="pv-name{{ $pvWordmarkOnly ? ' pv-name-wordmark' : '' }}" id="pvName">{{ e($pvSysName) }}</span>
            </span>
            <span class="pv-navlinks">
                <span>About</span><span>Features</span><span>Gallery</span><span>FAQ</span><span>Contact</span>
            </span>
            <span class="pv-navactions">
                <span class="pv-nav-btn pv-nav-btn-outline">Log in</span>
                <span class="pv-nav-btn pv-nav-btn-solid">Sign up</span>
            </span>
        </div>

        {{-- ── Hero: full background, dark scrim gradient, WHITE text (matches hn-hero) ── --}}
        <div class="pv-hero" id="pvHero">
            <div class="pv-hero-scrim"></div>
            <div class="pv-hero-content">
                <span class="pv-eyebrow" id="pvHeroEyebrow">
                    <i class="bi bi-stars"></i> {{ \App\Models\SystemSetting::getValue('hero.eyebrow', null) ?: 'Simplify University Reservations' }}
                </span>
                <h3 id="pvHeroTitle">{{ \App\Models\SystemSetting::getValue('hero.heading', 'Reserve University Facilities') }}</h3>
                <p id="pvHeroSubtitle">{{ \App\Models\SystemSetting::getValue('hero.sub', 'Book campus spaces effortlessly with our streamlined reservation platform.') }}</p>
                <div class="pv-hero-cta">
                    <span class="pv-btn pv-btn-primary pv-btn-lg" id="pvHeroCta">{{ \App\Models\SystemSetting::getValue('hero.cta_text', 'Reserve a facility') }} <i class="bi bi-arrow-right"></i></span>
                    <span class="pv-btn pv-btn-ghost">Learn more <i class="bi bi-chevron-down"></i></span>
                </div>
                <div class="pv-hero-badges" id="pvHeroBadges">
                    @php
                        $pvBadges = \App\Models\SystemSetting::getValue('homepage_content', '');
                        $pvBadgeList = [];
                        try { $decoded = json_decode($pvBadges, true); } catch (\Throwable $e) { $decoded = null; }
                        if (is_array($decoded) && !empty($decoded['hero']['badges'])) {
                            $pvBadgeList = collect($decoded['hero']['badges'])->filter(fn ($b) => !empty($b['label']))->take(4)->all();
                        }
                        if (!$pvBadgeList) {
                            $pvBadgeList = [
                                ['label' => '100% online process'],
                                ['label' => 'Real-time status updates'],
                                ['label' => 'No double bookings'],
                            ];
                        }
                    @endphp
                    @foreach ($pvBadgeList as $pvB)
                        <span class="pv-badge-chip"><i class="bi bi-shield-check-fill"></i> {{ $pvB['label'] }}</span>
                    @endforeach
                </div>
            </div>
            <div class="pv-scroll-hint"><span class="pv-scroll-line"></span><span>Scroll</span></div>
        </div>

        {{-- ── Brand color strip ── --}}
        <div class="pv-strip">
            <span class="pv-chip pv-chip-primary">Primary</span>
            <span class="pv-chip pv-chip-secondary">Secondary</span>
            <span class="pv-chip pv-chip-accent">Accent</span>
            <a href="{{ url('/') }}" target="_blank" class="pv-link">View live homepage &rarr;</a>
            <span class="pv-swatches">
                <span class="pv-swatch" id="pvSwatchPrimary"></span>
                <span class="pv-swatch" id="pvSwatchSecondary"></span>
                <span class="pv-swatch" id="pvSwatchAccent"></span>
                <span class="pv-swatch" id="pvSwatchDark"></span>
            </span>
        </div>
    </div>
</div>


