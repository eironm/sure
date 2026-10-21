<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', 'Authentication') - {{ branding('system_name', 'Platform') }}</title>
    <meta name="description" content="{{ branding('system_tagline', 'Smart University Reservation Engine') }}">
    <meta name="theme-color" content="{{ branding('brand_dark', '#0f172a') }}">
    <link rel="icon" type="image/x-icon" href="{{ brand_favicon() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Shared mobile layer. Loaded after Bootstrap so its touch-target
         and 16px input rules win over the CDN defaults. --}}
    @vite(['resources/css/mobile.css'])
    <style>
        :root {
            {!! brand_css_vars() !!}
            --auth-canvas: #f5f6f8;
            --auth-border: #e2e8f0;
            --auth-text: #1a1a1a;
            --auth-muted: #595959;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--auth-canvas);
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--auth-text);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Focus Ring (WCAG) ─────────────────────────────── */
        *:focus-visible {
            outline: 3px solid var(--brand-primary);
            outline-offset: 2px;
        }

        /* ══ Header bar (Booking.com style) ══════════════════ */
        .auth-header {
            background: var(--brand-dark, #0f172a);
            padding: 0.75rem 0;
        }
        .auth-header-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .auth-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
        }
        .auth-brand img { width: 36px; height: 36px; }
.auth-brand .brand-wordmark-only { width: auto; height: 44px; }
        .auth-brand span {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.02em;
            color: #fff;
        }
        .auth-header-action {
            color: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.55);
            border-radius: 6px;
            padding: 0.45rem 1rem;
            transition: background 0.15s;
            white-space: nowrap;
        }
        .auth-header-action:hover { background: rgba(255,255,255,0.12); color: #fff; }

        /* ══ Main area ════════════════════════════════════════ */
        .auth-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2.5rem 1.25rem 3rem;
        }
        .auth-card {
            background: #fff;
            border: 1px solid var(--auth-border);
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.06);
            width: 100%;
            max-width: 440px;
            padding: 2rem;
        }
        .auth-card-lg { max-width: 680px; }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin: 0 0 0.4rem;
            color: var(--auth-text);
        }
        .auth-subtitle {
            font-size: 0.9rem;
            color: var(--auth-muted);
            margin-bottom: 1.75rem;
            line-height: 1.55;
        }
        .auth-subtitle a {
            color: var(--brand-primary, #2563eb);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-subtitle a:hover { text-decoration: underline; }

        /* ══ Forms ════════════════════════════════════════════ */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--auth-text);
            margin-bottom: 0.4rem;
        }
        .form-control, .form-select {
            background-color: #fff;
            border: 1px solid #949494;
            border-radius: 8px;
            padding: 0.7rem 0.9rem;
            font-size: 0.9375rem;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:hover, .form-select:hover { border-color: var(--auth-text); }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-primary, #2563eb);
            box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.18);
        }
        .form-control.is-invalid { border-color: #d4111e; }
        .invalid-feedback { color: #d4111e; font-weight: 500; }

        /* Password field with visibility toggle */
        .password-field { position: relative; }
        .password-field .form-control { padding-right: 3rem; }
        .password-toggle-btn {
            position: absolute;
            top: 50%;
            right: 0.4rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--auth-muted);
            font-size: 1.05rem;
            padding: 0.35rem 0.55rem;
            border-radius: 6px;
            cursor: pointer;
            line-height: 1;
        }
        .password-toggle-btn:hover { color: var(--auth-text); background: #f1f5f9; }

        /* ══ Primary CTA (solid, Booking-style) ═══════════════ */
        .btn-sure {
            background: var(--brand-primary, #2563eb);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.8rem;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            transition: filter 0.15s;
        }
        .btn-sure:hover, .btn-sure:focus { filter: brightness(0.9); color: #fff; }
        .btn-sure:disabled { opacity: 0.55; }

        .auth-link {
            color: var(--brand-primary, #2563eb);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .auth-link:hover { text-decoration: underline; }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0;
            color: var(--auth-muted);
            font-size: 0.8rem;
        }
        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--auth-border);
        }

        /* Trust strip below card */
        .auth-trust {
            width: 100%;
            max-width: 440px;
            margin-top: 1.25rem;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            color: var(--auth-muted);
            font-size: 0.78rem;
        }
        .auth-card-lg + .auth-trust { max-width: 680px; }
        .auth-trust span { display: inline-flex; align-items: center; gap: 0.35rem; }
        .auth-trust i { color: #16a34a; }

        /* ══ Footer ═══════════════════════════════════════════ */
        .auth-footer {
            border-top: 1px solid var(--auth-border);
            background: #fff;
            padding: 1.25rem;
            text-align: center;
            font-size: 0.78rem;
            color: var(--auth-muted);
        }
        .auth-footer p { margin: 0 0 0.25rem; }
        .auth-footer a { color: var(--brand-primary, #2563eb); text-decoration: none; font-weight: 600; }
        .auth-footer a:hover { text-decoration: underline; }

        @media (max-width: 767.98px) {
            .auth-main { padding: 1.25rem 1rem 2rem; }
            .auth-card {
                padding: 1.5rem 1.25rem;
                /* A hairline card on a near-white page adds no
                   information at this width and costs 2px of content. */
                border: none;
                box-shadow: none;
                background: transparent;
            }
            .auth-title { font-size: 1.35rem; }
            .auth-subtitle { margin-bottom: 1.25rem; }

            /* The header wordmark competes with the action button on
               narrow screens; trim it rather than let them collide. */
            .auth-header-inner { padding: 0 1rem; }
            .auth-brand span { font-size: 1.15rem; }
            .auth-brand img { width: 30px; height: 30px; }
.auth-brand .brand-wordmark-only { height: 34px; }
            .auth-header-action { padding: 0.45rem 0.75rem; font-size: 0.8rem; }

            /* Full-width, 48px-tall controls are far easier to hit and
               match the platform convention for a primary form action. */
            .btn-sure { padding: 0.85rem; min-height: 48px; }

            /* Stack the trust strip so it never wraps mid-phrase. */
            .auth-trust {
                flex-direction: column;
                align-items: center;
                gap: 0.4rem;
            }

            /* Clear the iOS home indicator at the foot of the page. */
            .auth-footer {
                padding: 1.25rem 1rem calc(1.25rem + env(safe-area-inset-bottom, 0px));
            }
        }

        /* The visibility toggle is injected as a bare button; give it a
           real target on touch so it is not a 20px tap. */
        @media (hover: none) and (pointer: coarse) {
            .password-toggle-btn {
                min-width: 44px;
                min-height: 44px;
                right: 0.15rem;
            }
            .password-field .form-control { padding-right: 3.25rem; }
        }
    </style>
    @include('layouts.partials.clarity')
</head>
<body>
    <header class="auth-header">
        <div class="auth-header-inner">
            <a href="{{ url('/') }}" class="auth-brand" aria-label="{{ branding('system_name', 'Platform') }} home">
                {{-- The header bar sits on the dark navy brand surface, so the
                     default is the white/light variant. When CMS auto light/dark
                     switching is enabled, the luminance script owns the src. --}}
                <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}" alt="" class="{{ \App\Services\BrandingService::logoAutoVariant() ? 'brand-logo-auto' : '' }}{{ \App\Services\BrandingService::wordmarkOnly() ? ' brand-wordmark-only' : '' }}">
                @if (\App\Services\BrandingService::logoAutoVariant())
                    {!! \App\Services\BrandingService::logoAutoVariantScript() !!}
                @endif
                <span class="{{ \App\Services\BrandingService::wordmarkOnly() ? 'd-none' : '' }}">{{ branding('system_name', 'Platform') }}</span>
            </a>
            @hasSection('header_action')
                @yield('header_action')
            @endif
        </div>
    </header>

    <main class="auth-main">
        <div class="auth-card @yield('card_size')">
            <h1 class="auth-title">@yield('auth_title')</h1>
            <p class="auth-subtitle">@yield('auth_subtitle')</p>

            @yield('content')
        </div>

        <div class="auth-trust" aria-hidden="true">
            <span><i class="bi bi-shield-lock-fill"></i> Secure &amp; encrypted</span>
            <span><i class="bi bi-patch-check-fill"></i> Official university system</span>
        </div>
    </main>

    <footer class="auth-footer">
        <p>By signing in or creating an account, you agree to our Terms of Service and Privacy Policy.</p>
        <p>&copy; {{ date('Y') }} {{ branding('system_name', 'Platform') }} &mdash; {{ branding('system_tagline', 'Smart University Reservation Engine') }}</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggles
        document.querySelectorAll('.password-field').forEach(function (field) {
            var input = field.querySelector('input');
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'password-toggle-btn';
            btn.setAttribute('aria-label', 'Show password');
            btn.innerHTML = '<i class="bi bi-eye"></i>';
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
            field.appendChild(btn);
        });
    </script>
    @yield('modals')
    @include('components.accessibility-toolbar')
</body>
</html>
