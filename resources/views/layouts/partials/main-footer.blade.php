{{-- Shared rich footer used by the public homepage and the Guest Panel.
     Keeps both footers in sync with a single source of truth.
     Optional variables (all have sensible defaults):
       $hnFooterCtaLink  - URL for the CTA button (default: dashboard when authed, register otherwise)
       $hnFooterCtaText  - Button label (default: 'Go to dashboard' / 'Create an account')
       $hnFooterBrand    - brand column heading (default: logo + system name) --}}
@php
    $hnFooterCtaLink = $hnFooterCtaLink ?? (Auth::check() ? route('dashboard') : route('login'));
    $hnFooterCtaText = $hnFooterCtaText ?? (Auth::check() ? 'Go to dashboard' : 'Log in');
@endphp
<footer class="hn-footer" role="contentinfo">
    <div class="container">
        <div class="row g-4 py-4">
            <div class="col-lg-4">
                <div class="hn-footer-brand">
                    {{-- The footer sits on the dark brand surface, so the default
                         is the white/light variant. Auto light/dark switching is
                         enabled when the CMS toggle is on. --}}
                    <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}" alt="{{ branding('system_name', 'Platform') }} Logo" class="hn-logo{{ \App\Services\BrandingService::logoAutoVariant() ? ' brand-logo-auto' : '' }}{{ \App\Services\BrandingService::wordmarkOnly() ? ' brand-wordmark-only' : '' }}"/>
                    @if (\App\Services\BrandingService::logoAutoVariant())
                        {!! \App\Services\BrandingService::logoAutoVariantScript() !!}
                    @endif
                    <span class="hn-footer-brand-name{{ \App\Services\BrandingService::wordmarkOnly() ? ' d-none' : '' }}">{{ branding('system_name', 'Platform') }}</span>
                </div>
                <p class="hn-footer-tagline">{{ branding('system_tagline', 'Smart University Reservation Engine') }}</p>
                <ul class="hn-contact-list">
                    <li><i class="bi bi-geo-alt-fill" aria-hidden="true"></i><span>{!! branding('contact_address', 'Gen. Tinio Street, Cabanatuan City,<br>3100 Nueva Ecija, Philippines') !!}</span></li>
                    @if(branding('contact_email'))<li><i class="bi bi-envelope-fill" aria-hidden="true"></i><a href="mailto:{{ branding('contact_email') }}">{{ branding('contact_email') }}</a></li>@endif
                    @if(branding('contact_phone'))<li><i class="bi bi-telephone-fill" aria-hidden="true"></i><span>{{ branding('contact_phone') }}</span></li>@endif
                </ul>
            </div>
            <div class="col-6 col-lg-2 offset-lg-1">
                <h4 class="hn-footer-heading">Browse</h4>
                <ul class="hn-footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('home') }}#about">About Us</a></li>
                    <li><a href="{{ route('home') }}#faq">FAQ</a></li>
                    <li><a href="{{ Auth::check() ? route('guest.facilities') : route('login') }}">Reserve</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h4 class="hn-footer-heading">Policies</h4>
                <ul class="hn-footer-links">
                    <li><a href="{{ route('legal.show', 'terms') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('legal.show', 'privacy') }}">{{ branding('system_name', 'Platform') }} Policies</a></li>
                    <li><a href="{{ route('legal.show', 'data_collection') }}">Privacy</a></li>
                    <li><a href="{{ route('legal.cookies') }}">Cookies</a></li>
                </ul>
            </div>
@if(empty($hnFooterHideCta))
            <div class="col-lg-3">
                <h4 class="hn-footer-heading">Ready to book?</h4>
                <p class="hn-footer-cta-text small">Skip the paperwork. Reserve your campus facility online in minutes.</p>
                <a href="{{ $hnFooterCtaLink }}" class="hn-btn hn-btn-primary">{{ $hnFooterCtaText }} <i class="bi bi-arrow-right"></i></a>
            </div>
            @endif
        </div>
        <div class="hn-footer-bottom">
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
                <p class="mb-0 text-center">{!! branding('footer_text', '&copy; ' . date('Y') . ' ' . branding('system_name', 'Platform') . '. All rights reserved.') !!}</p>
                @php
                    $hnSocials = collect([
                        'facebook'  => ['url' => branding('social_facebook'),  'icon' => 'bi-facebook'],
                        'instagram' => ['url' => branding('social_instagram'), 'icon' => 'bi-instagram'],
                        'tiktok'    => ['url' => branding('social_tiktok'),    'icon' => 'bi-tiktok'],
                        'x'         => ['url' => branding('social_x'),         'icon' => 'bi-twitter-x'],
                        'linkedin'  => ['url' => branding('social_linkedin'),  'icon' => 'bi-linkedin'],
                    ])->filter(fn ($s) => !empty($s['url']));
                @endphp
                @if ($hnSocials->isNotEmpty())
                    <span class="hn-social-links">
                        @foreach ($hnSocials as $platform => $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($platform) }}">
                                <i class="bi {{ $social['icon'] }}" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </span>
                @endif
            </div>
        </div>
    </div>
</footer>
<style>
.hn-footer { background: var(--brand-dark, #0f172a); color: rgba(255, 255, 255, .7); border-top: 1px solid rgba(255, 255, 255, .08); }
.hn-footer-brand { display: flex; align-items: center; gap: .6rem; margin-bottom: .9rem; }
.hn-footer-brand .hn-logo { height: 38px; width: auto; max-width: min(220px, 100%); object-fit: contain; }
/* Wordmark-only mode: a wide wordmark replaces the name text — give it room. */
.hn-footer-brand .brand-wordmark-only { height: 46px; width: auto; max-width: 100%; }
.hn-footer-brand-name { display: none; }
.hn-footer-tagline { font-size: .88rem; color: rgba(255, 255, 255, .55); margin-bottom: 1.1rem; }
.hn-contact-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: .65rem; font-size: .88rem; }
.hn-contact-list i { color: #60a5fa; width: 1.2rem; text-align: center; }
.hn-contact-list a { color: inherit; text-decoration: none; }
.hn-contact-list a:hover { color: #93c5fd; }
.hn-footer-heading { color: #fff; font-size: .95rem; font-weight: 700; margin-bottom: 1rem; }
.hn-footer-links { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: .6rem; font-size: .9rem; }
.hn-footer-links a { color: rgba(255, 255, 255, .65); text-decoration: none; transition: color .18s ease; }
.hn-footer-links a:hover { color: #fff; }
.hn-footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, .08); padding: 1.25rem 0;
    font-size: .82rem; color: rgba(255, 255, 255, .45);
}
.hn-footer-cta-text { color: rgba(255, 255, 255, .55); margin-bottom: 1rem; }
.hn-social-links { display: inline-flex; gap: .55rem; }
.hn-social-links a {
    display: inline-flex; align-items: center; justify-content: center;
    width: 2rem; height: 2rem; border-radius: 50%;
    background: rgba(255, 255, 255, .08); color: rgba(255, 255, 255, .7);
    font-size: .95rem; text-decoration: none;
    transition: background .18s ease, color .18s ease, transform .18s ease;
}
.hn-social-links a:hover { background: rgba(255, 255, 255, .18); color: #fff; transform: translateY(-2px); }
.hn-btn { display: inline-flex; align-items: center; gap: .5rem; padding: .7rem 1.4rem; border-radius: .75rem; font-weight: 600; text-decoration: none; transition: transform .18s ease, box-shadow .18s ease; }
.hn-btn-primary { background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed)); color: #fff; }
.hn-btn-primary:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(var(--brand-primary-rgb, 37, 99, 235), .35); }
</style>
