<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo $__env->yieldContent('title', 'Authentication'); ?> - <?php echo e(branding('system_name', 'Platform')); ?></title>
    <meta name="description" content="<?php echo e(branding('system_tagline', 'Smart University Reservation Engine')); ?>">
    <meta name="theme-color" content="<?php echo e(branding('brand_dark', '#0f172a')); ?>">
    <link rel="icon" type="<?php echo e(brand_favicon_mimetype()); ?>" href="<?php echo e(brand_favicon()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/mobile.css']); ?>
    <style>
        :root {
            <?php echo brand_css_vars(); ?>

            --auth-canvas: #f6f7fa;
            --auth-border: #e4e8f1;
            --auth-text: #17203a;
            --auth-muted: #5a6478;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--auth-canvas);
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--auth-text);
            margin: 0;
            min-height: 100vh;
        }

        /* ── Focus Ring (WCAG) ─────────────────────────────── */
        *:focus-visible {
            outline: 3px solid var(--brand-primary);
            outline-offset: 2px;
        }

        /* ══════════════════════════════════════════════════════
           Split shell: left brand panel + right form panel
           ══════════════════════════════════════════════════════ */
        .auth-shell {
            min-height: 100vh;
            display: flex;
        }

        /* ── Left brand panel ───────────────────────────────── */
        .auth-panel {
            width: 46%;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            color: #fff;
            display: flex;
            flex-direction: column;
            background-color: var(--brand-dark, #0f172a);
            padding: 3rem 3.25rem;
        }

        .auth-panel-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.85;
            filter: saturate(1.15) contrast(1.15) brightness(1.08);
            transform: scale(1.02);
            pointer-events: none;
            z-index: 0;
            transition: opacity 0.3s ease;
        }

        .auth-panel-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(11, 18, 36, 0.55) 0%, rgba(15, 23, 42, 0.30) 35%, rgba(11, 18, 36, 0.78) 100%),
                radial-gradient(900px 450px at -10% 105%, rgba(212,175,55,0.14), transparent 60%);
            pointer-events: none;
            z-index: 1;
        }

        /* Subtle academic ornament: thin gold hairlines framing the panel */
        .auth-panel::before, .auth-panel::after {
            content: '';
            position: absolute;
            background: rgba(212,175,55,0.35);
            z-index: 2;
        }
        .auth-panel::before {
            top: 1.75rem; left: 1.75rem; right: 1.75rem; bottom: auto;
            height: 1px;
        }
        .auth-panel::after {
            bottom: 1.75rem; left: 1.75rem; right: 1.75rem; top: auto;
            height: 1px;
        }

        /* Drifting soft shapes — calm, not busy */
        .auth-panel-shapes {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }
        .auth-panel-shapes svg {
            position: absolute;
            opacity: 0.5;
        }
        .aps-ring {
            top: -70px; right: -70px;
            width: 320px; height: 320px;
            border-radius: 50%;
            border: 28px solid rgba(255,255,255,0.035);
        }
        .aps-dot {
            bottom: 22%; left: 9%;
            width: 14px; height: 14px;
            border-radius: 50%;
            background: rgba(212,175,55,0.45);
            box-shadow: 0 0 30px 12px rgba(212,175,55,0.15);
        }
        .aps-arch {
            bottom: -90px; right: 14%;
            width: 260px; height: 180px;
            border-radius: 140px 140px 0 0;
            border: 24px solid rgba(255,255,255,0.04);
        }
        .aps-tile {
            top: 34%; left: -30px;
            width: 120px; height: 120px;
            border: 20px solid rgba(212,175,55,0.10);
            border-radius: 22px;
            transform: rotate(22deg);
        }

        .auth-panel-inner {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .auth-panel-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #fff;
            text-decoration: none;
        }
        .auth-panel-brand img {
            width: auto;
            height: 3.4rem;
            max-width: min(260px, 26vw);
            object-fit: contain;
        }
        .auth-panel-brand .brand-wordmark-only { height: 3.6rem; max-width: min(300px, 30vw); }
        .auth-panel-brand span {
            font-weight: 900;
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        .auth-panel-hero {
            margin-top: auto;
            margin-bottom: 2.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .auth-panel-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #d4af37;
            margin-bottom: 1.1rem;
        }
        .auth-panel-eyebrow::before {
            content: '';
            width: 26px; height: 1px;
            background: #d4af37;
        }
        .auth-panel-title {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.18;
            margin: 0 0 1rem;
        }
        .auth-panel-title .gold { color: #e9c968; }
        .auth-panel-sub {
            font-size: 1rem;
            line-height: 1.65;
            color: rgba(255,255,255,0.72);
            max-width: 34ch;
            margin: 0 0 2.25rem;
        }

        .auth-panel-features {
            list-style: none;
            padding: 0;
            margin: 0 0 2.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .auth-panel-features li {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            font-size: 0.95rem;
            color: rgba(255,255,255,0.85);
            line-height: 1.5;
        }
        .auth-panel-features li i {
            margin-top: 2px;
            width: 26px; height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(255,255,255,0.08);
            color: #e9c968;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .auth-panel-quote {
            font-size: 0.82rem;
            font-style: italic;
            color: rgba(255,255,255,0.45);
            line-height: 1.6;
            margin: 0;
        }

        /* ── Right form panel ───────────────────────────────── */
        .auth-form-panel {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .auth-form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 2.75rem;
        }
        .auth-form-brand {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            text-decoration: none;
            color: var(--auth-text);
        }
        .auth-form-brand img {
            width: auto;
            height: 2.1rem;
            max-width: 170px;
            object-fit: contain;
        }
        .auth-form-brand .brand-wordmark-only { height: 2.35rem; max-width: 190px; }
        .auth-form-brand span {
            font-weight: 800;
            font-size: 1rem;
            color: var(--brand-dark, #0f172a);
        }
        .auth-header-action {
            color: var(--auth-text);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            border: 1.5px solid var(--auth-border);
            border-radius: 999px;
            padding: 0.5rem 1.25rem;
            transition: all 0.15s;
            white-space: nowrap;
        }
        .auth-header-action:hover { border-color: var(--brand-dark, #0f172a); background: var(--auth-canvas); color: var(--auth-text); }

        .auth-form-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem 2.75rem 2.5rem;
        }
        .auth-form-body-inner {
            max-width: 480px;
            margin: 0 auto;
        }

        .auth-title {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin: 0 0 0.5rem;
            color: var(--auth-text);
        }
        .auth-subtitle {
            font-size: 0.95rem;
            color: var(--auth-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
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
            font-size: 0.84rem;
            color: var(--auth-text);
            margin-bottom: 0.4rem;
        }
        .form-control, .form-select {
            background-color: #fff;
            border: 1.5px solid #d7dce8;
            border-radius: 10px;
            padding: 0.78rem 1rem;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .form-control:hover, .form-select:hover { border-color: #b9c2d6; }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-primary, #2563eb);
            box-shadow: 0 0 0 4px rgba(var(--brand-primary-rgb, 37,99,235), 0.12);
        }
        .form-control.is-invalid { border-color: #d4111e; }
        .invalid-feedback { color: #d4111e; font-weight: 500; }

        /* Password field with visibility toggle */
        .password-field { position: relative; }
        .password-field .form-control { padding-right: 3.2rem; }
        .password-toggle-btn {
            position: absolute;
            top: 50%;
            right: 0.5rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--auth-muted);
            font-size: 1.05rem;
            padding: 0.4rem 0.55rem;
            border-radius: 8px;
            cursor: pointer;
            line-height: 1;
        }
        .password-toggle-btn:hover { color: var(--auth-text); background: #f2f4f9; }

        /* ══ Primary CTA ══════════════════════════════════════ */
        .btn-sure {
            background: var(--brand-dark, #0f172a);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.85rem;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 2px 10px rgba(15,23,42,0.18);
        }
        .btn-sure:hover, .btn-sure:focus {
            background: var(--brand-primary, #2563eb);
            color: #fff;
            box-shadow: 0 4px 16px rgba(37,99,235,0.30);
        }
        .btn-sure:active { transform: translateY(1px); }
        .btn-sure:disabled { opacity: 0.55; }

        .btn-sure-ghost {
            background: #fff;
            color: var(--brand-dark, #0f172a);
            border: 1.5px solid var(--auth-border);
            border-radius: 10px;
            padding: 0.82rem;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            transition: all 0.15s;
        }
        .btn-sure-ghost:hover { border-color: var(--brand-dark, #0f172a); background: var(--auth-canvas); color: var(--auth-text); }

        .auth-link {
            color: var(--brand-primary, #2563eb);
            text-decoration: none;
            font-size: 0.86rem;
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

        /* Trust strip below form */
        .auth-trust {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            padding-top: 1.5rem;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            color: var(--auth-muted);
            font-size: 0.78rem;
        }
        .auth-trust span { display: inline-flex; align-items: center; gap: 0.35rem; }
        .auth-trust i { color: #16a34a; }

        /* Mobile footer legal line */
        .auth-form-footer {
            padding: 1rem 2.75rem calc(1rem + env(safe-area-inset-bottom, 0px));
            border-top: 1px solid var(--auth-border);
            text-align: center;
            font-size: 0.75rem;
            color: var(--auth-muted);
        }
        .auth-form-footer a { color: var(--auth-muted); text-decoration: underline; }

        /* ══ Large registration card ══════════════════════════ */
        .auth-card-lg { max-width: 680px; }
        .auth-form-body-inner-lg { max-width: 560px; }

        /* ══ Responsive ═══════════════════════════════════════ */
        @media (max-width: 1199.98px) {
            .auth-panel { width: 42%; padding: 2.5rem 2.5rem; }
            .auth-panel-title { font-size: 2.1rem; }
            .auth-form-header, .auth-form-body { padding-left: 2rem; padding-right: 2rem; }
        }

        @media (max-width: 991.98px) {
            /* Collapse the desktop split shell into a real vertical mobile
               flow. Without this direction change, the 100%-wide brand panel
               keeps the form panel off-screen to the right. */
            .auth-shell {
                flex-direction: column;
                min-height: 100vh;
                min-height: 100dvh;
            }
            .auth-panel {
                width: 100%;
                flex: none;
                min-height: 0;
                padding: 1rem 1.25rem;
                background-color: var(--brand-dark, #0f172a);
            }
            .auth-panel-overlay {
                background:
                    radial-gradient(700px 320px at 90% -20%, rgba(255,255,255,0.06), transparent 60%),
                    linear-gradient(160deg, rgba(11, 18, 36, 0.90) 0%, rgba(15, 23, 42, 0.88) 45%, rgba(23, 35, 68, 0.95) 100%);
            }
            .auth-panel-inner { flex: none; }
            .auth-panel::before, .auth-panel::after { display: none; }
            .auth-panel-shapes { display: none; }
            .auth-panel-brand {
                min-width: 0;
                gap: 0.55rem;
            }
            .auth-panel-brand img {
                width: auto;
                height: 2.25rem;
                max-width: min(46vw, 190px);
                flex-shrink: 0;
            }
            .auth-panel-brand .brand-wordmark-only { height: 2.35rem; max-width: min(54vw, 220px); }
            .auth-panel-brand span {
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-size: 1rem;
            }
            .auth-panel-hero { display: none; }
            .auth-form-header { display: none; }
            .auth-form-panel {
                width: 100%;
                flex: 1 0 auto;
                min-height: 0;
            }
            .auth-form-body {
                flex: none;
                overflow: visible;
                padding: 1.5rem 1rem 2.25rem;
            }
            .auth-form-body-inner,
            .auth-form-body-inner-lg {
                width: 100%;
                max-width: 560px;
            }
            /* The dark panel already supplies the mobile brand header. The
               duplicate light-on-white row was invisible and consumed space. */
            .auth-mobile-brand-row { display: none !important; }
            .auth-trust { padding-left: 1rem; padding-right: 1rem; }
        }

        @media (min-width: 992px) {
            .auth-mobile-brand-row { display: none !important; }
            /* The left brand panel pins to the viewport. The SHELL keeps
               scrolling height for long forms, but the panel itself locks
               in place and never rides the scrollbar. */
            .auth-panel {
                position: fixed !important;
                top: 0;
                left: 0;
                width: 46% !important;
                height: 100vh;
                height: 100dvh;
                flex: none !important;
                align-self: flex-start;
            }
            .auth-panel-inner {
                overflow-y: auto;
                padding-right: 0.75rem; /* keep scrollbar off the hairline */
                scrollbar-width: thin;
                scrollbar-color: rgba(255,255,255,0.25) transparent;
            }
            .auth-form-panel {
                flex: none !important;
                width: 54% !important;
                margin-left: 46% !important;
            }
            body { overflow-x: hidden; }
        }

        @media (max-width: 767.98px) {
            /* Keep every auth screen inside the viewport width while giving
               the form enough room to breathe on phones. */
            .auth-panel { padding: 0.85rem 1rem; }
            .auth-panel-brand img {
                height: 2.1rem;
                max-width: 44vw;
            }
            .auth-panel-brand .brand-wordmark-only {
                height: 2.2rem;
                max-width: 58vw;
            }
            .auth-panel-brand span { font-size: 0.95rem; }
            .auth-form-body {
                padding: 1.35rem 0.85rem 2rem;
            }
            .auth-title {
                font-size: clamp(1.35rem, 6vw, 1.55rem);
                line-height: 1.2;
            }
            .auth-subtitle {
                font-size: 0.92rem;
                margin-bottom: 1.35rem;
            }
            .auth-form-body-inner,
            .auth-form-body-inner-lg {
                max-width: none;
            }
            /* Full-width, 48px-tall controls are far easier to hit and
               match the platform convention for a primary form action. */
            .btn-sure, .btn-sure-ghost {
                padding: 0.85rem;
                min-height: 48px;
            }
            .form-label { font-size: 0.85rem; }
            .form-control, .form-select { min-height: 48px; }
            textarea.form-control { min-height: 76px; }
            .auth-form-body form > .d-flex.justify-content-between {
                flex-wrap: wrap;
                align-items: flex-start !important;
                row-gap: 0.35rem;
            }
            .auth-form-body form > .d-flex.justify-content-between .auth-link {
                margin-left: auto;
                text-align: right;
            }
            /* Stack the trust strip so it never wraps mid-phrase. */
            .auth-trust {
                flex-direction: column;
                align-items: center;
                gap: 0.4rem;
                padding-top: 0.75rem;
                font-size: 0.72rem;
            }
            .auth-form-footer {
                padding: 0.9rem 1rem calc(0.9rem + env(safe-area-inset-bottom, 0px));
                line-height: 1.45;
            }
        }

        @media (max-width: 379.98px) {
            .auth-panel-brand span { display: none; }
            .auth-panel-brand img { max-width: 62vw; }
            .auth-form-body { padding-left: 0.7rem; padding-right: 0.7rem; }
            .auth-title { font-size: 1.3rem; }
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

        /* Gentle entrance — softens the first paint, nothing distracting. */
        @media (prefers-reduced-motion: no-preference) {
            .auth-form-body-inner {
                animation: auth-rise 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
            }
            @keyframes auth-rise {
                from { opacity: 0; transform: translateY(14px); }
                to { opacity: 1; transform: translateY(0); }
            }
        }
    </style>
    <?php echo $__env->make('layouts.partials.clarity', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body>
<div class="auth-shell">

    
    <aside class="auth-panel" aria-hidden="true">
        <div class="auth-panel-bg" style="background-image: url('<?php echo e(brand_login_bg()); ?>');"></div>
        <div class="auth-panel-overlay"></div>
        <div class="auth-panel-shapes">
            <div class="aps-ring"></div>
            <div class="aps-tile"></div>
            <div class="aps-dot"></div>
            <div class="aps-arch"></div>
        </div>

        <div class="auth-panel-inner">
            <a href="<?php echo e(url('/')); ?>" class="auth-panel-brand" aria-label="<?php echo e(branding('system_name', 'Platform')); ?> home">
                
                <img src="<?php echo e(\App\Services\BrandingService::logoVariant('light')); ?>" alt="<?php echo e(branding('system_name', 'Platform')); ?> Logo" class="<?php echo e(\App\Services\BrandingService::wordmarkOnly() ? 'brand-wordmark-only' : ''); ?>">
                <?php if(\App\Services\BrandingService::logoAutoVariant()): ?>
                    <?php echo \App\Services\BrandingService::logoAutoVariantScript(); ?>

                <?php endif; ?>
                <span class="<?php echo e(\App\Services\BrandingService::wordmarkOnly() ? 'd-none' : ''); ?>"><?php echo e(branding('system_name', 'Platform')); ?></span>
            </a>

            <div class="auth-panel-hero">
                <div class="auth-panel-eyebrow"><?php echo $__env->yieldContent('panel_eyebrow', 'Official University System'); ?></div>
                <?php $pt = $__env->hasSection('panel_title') ? $__env->yieldContent('panel_title') : 'Welcome back, we&rsquo;ve missed you.'; ?><h2 class="auth-panel-title"><?php echo $pt; ?></h2>
                <?php $ps = $__env->hasSection('panel_sub') ? $__env->yieldContent('panel_sub') : 'Your gateway to every campus space — reserve what you need, when you need it, in just a few taps.'; ?><p class="auth-panel-sub"><?php echo $ps; ?></p>

                <ul class="auth-panel-features">
                    <li><i class="bi bi-calendar2-check"></i> Reserve facilities in minutes, 24/7</li>
                    <li><i class="bi bi-graph-up"></i> Live availability &amp; transparent pricing</li>
                    <li><i class="bi bi-shield-lock-fill"></i> Secure payments &amp; official records</li>
                </ul>

                <p class="auth-panel-quote">&ldquo;A university campus is a living room for the mind &mdash; every space here deserves to be used well.&rdquo;</p>
            </div>
        </div>
    </aside>

    
    <section class="auth-form-panel">

        <header class="auth-form-header">
            
            <a href="<?php echo e(url('/')); ?>" class="visually-hidden-focusable"><?php echo e(branding('system_name', 'Platform')); ?> home</a>
            <?php if (! empty(trim($__env->yieldContent('header_action')))): ?>
                <?php echo $__env->yieldContent('header_action'); ?>
            <?php endif; ?>
        </header>

        <div class="auth-form-body">
            <div class="auth-form-body-inner <?php echo $__env->yieldContent('card_size'); ?> <?php echo $__env->yieldContent('body_size'); ?>">
                
                <div class="auth-mobile-brand-row mb-4 d-flex align-items-center justify-content-center gap-2">
                    <img src="<?php echo e(\App\Services\BrandingService::logoVariant('light')); ?>" alt="<?php echo e(branding('system_name', 'Platform')); ?> Logo" style="height:2.4rem;max-width:min(200px,28vw);object-fit:contain;" class="<?php echo e(\App\Services\BrandingService::wordmarkOnly() ? 'brand-wordmark-only' : ''); ?>">
                    <?php if(\App\Services\BrandingService::logoAutoVariant()): ?>
                        <?php echo \App\Services\BrandingService::logoAutoVariantScript(); ?>

                    <?php endif; ?>
                    <span class="fw-bold fs-6 text-white-50" style="color:rgba(255,255,255,0.8);"><?php echo e(branding('system_name', 'Platform')); ?></span>
                </div>

                <h1 class="auth-title"><?php echo $__env->yieldContent('auth_title'); ?></h1>
                <p class="auth-subtitle"><?php echo $__env->yieldContent('auth_subtitle'); ?></p>

                <?php echo $__env->yieldContent('content'); ?>
            </div>

            <div class="auth-trust" aria-hidden="true">
                <span><i class="bi bi-shield-lock-fill"></i> Secure &amp; encrypted</span>
                <span><i class="bi bi-patch-check-fill"></i> Official university system</span>
            </div>
        </div>

        <footer class="auth-form-footer">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(branding('system_name', 'Platform')); ?> &mdash; <?php echo e(branding('system_tagline', 'Smart University Reservation Engine')); ?>

        </footer>
    </section>

</div>

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
<?php echo $__env->yieldContent('modals'); ?>
<?php echo $__env->make('components.accessibility-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/layouts/auth.blade.php ENDPATH**/ ?>