<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <style>
            /* Initial cloak to prevent FOUC */
            [x-cloak] { display: none !important; }
            body { visibility: hidden; }
            body.ready { visibility: visible; }
        </style>
        <script>
            // Reveal the page as soon as possible. Using multiple triggers so the
            // body can never get stuck hidden (blank page) if DOMContentLoaded was
            // already missed — e.g. after a PJAX swap replays lifecycle events, or
            // when this layout is loaded via a full navigation from another panel.
            (function () {
                function reveal() {
                    if (document.body) document.body.classList.add('ready');
                }
                if (document.readyState !== 'loading') {
                    reveal();
                } else {
                    document.addEventListener('DOMContentLoaded', reveal, { once: true });
                }
                // Absolute safety net.
                window.addEventListener('load', reveal, { once: true });
                document.addEventListener('pjax:load', reveal);
                setTimeout(reveal, 1200);
            })();
        </script>
        <meta charset="utf-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="description" content="<?php echo e(trim(branding('meta_description', '')) !== '' ? branding('meta_description') : branding('system_tagline', 'Smart University Reservation Engine')); ?>">
        <?php if(!empty(trim(branding('site_keywords', '')))): ?>
            <meta name="keywords" content="<?php echo e(branding('site_keywords')); ?>">
        <?php endif; ?>
        <?php $appOgImage = branding('og_image') ? asset(branding('og_image')) : asset(\App\Services\BrandingService::logoForBg('dark')); ?>
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php echo e(branding('system_name', 'Platform')); ?>">
        <meta property="og:description" content="<?php echo e(trim(branding('meta_description', '')) !== '' ? branding('meta_description') : branding('system_tagline', '')); ?>">
        <meta property="og:image" content="<?php echo e($appOgImage); ?>">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:site_name" content="<?php echo e(branding('system_name', 'Platform')); ?>">
        
        <meta name="theme-color" content="<?php echo e(branding('brand_dark', '#0f172a')); ?>">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="icon" type="<?php echo e(brand_favicon_mimetype()); ?>" href="<?php echo e(brand_favicon()); ?>">

        <title><?php echo $__env->yieldContent('title', 'Guest'); ?> - <?php echo e(branding('system_name', 'Platform')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700;800&family=Inter:wght@400;500;600;700;800;900&family=Roboto:wght@400;500;700&family=Open+Sans:wght@400;500;600;700&family=Source+Sans+3:wght@400;500;600;700&family=Lato:wght@400;700;900&display=swap" rel="stylesheet" />
        
        <?php if(!empty(trim(branding('public_font', '')))): ?>
            <link href="https://fonts.googleapis.com/css2?family=<?php echo e(urlencode(branding('public_font'))); ?>:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <?php endif; ?>

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <!-- Vite CSS -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/mobile.css']); ?>
        <script>
            // Run page-level initializers on both full loads and PJAX-compatible swaps.
            window.onPageLoad = window.onPageLoad || function (cb) {
                if (typeof cb !== 'function') return;
                const run = () => {
                    try { cb(); } catch (error) { console.error('Page initializer failed', error); }
                };
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', run, { once: true });
                } else {
                    setTimeout(run, 0);
                }
            };
        </script>

        <style>
            :root {
                <?php echo brand_css_vars(); ?>

                --surface-light: #f0f4f8;
                --surface-card: #ffffff;
                --text-primary: var(--brand-dark, #0f172a);
                --text-secondary: #475569;
                --text-muted: #94a3b8;
                --border-light: #e2e8f0;
            }

            * { margin: 0; padding: 0; box-sizing: border-box; }
            html, body { height: 100%; }

            body {
                font-family: <?php if(!empty(trim(branding('public_font', '')))): ?> '<?php echo e(branding('public_font')); ?>', <?php endif; ?> 'Inter', sans-serif;
                background-color: var(--surface-light);
                color: var(--text-primary);
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            /* ── Skip-to-Content (Accessibility) ──────────────────── */
            .skip-to-content {
                position: absolute;
                top: -100%;
                left: 1rem;
                background: var(--brand-primary);
                color: #fff;
                padding: 0.75rem 1.5rem;
                border-radius: 0 0 8px 8px;
                font-weight: 700;
                font-size: 0.85rem;
                z-index: 9999;
                text-decoration: none;
                transition: top 0.2s;
            }
            .skip-to-content:focus {
                top: 0;
            }

            /* ── Shared Dropdown Styles ─────────────────────────────── */
            .user-dropdown-container { position: relative; }

            .user-dropdown-btn {
                background: linear-gradient(135deg, rgba(var(--brand-primary-rgb),0.1), rgba(var(--brand-primary-rgb),0.05));
                border: 1.5px solid var(--brand-primary);
                color: var(--brand-primary) !important;
                border-radius: 0.5rem;
                padding: 0.5rem 1rem;
                font-weight: 700;
                font-size: 0.85rem;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 0.6rem;
                transition: all 0.3s ease;
                white-space: nowrap;
                text-decoration: none;
            }
            .user-dropdown-btn:hover {
                background: linear-gradient(135deg, rgba(var(--brand-primary-rgb),0.2), rgba(var(--brand-primary-rgb),0.1));
                box-shadow: 0 4px 12px rgba(var(--brand-primary-rgb),0.2);
                transform: translateY(-1px);
            }
            .user-dropdown-btn.active {
                background-color: var(--brand-primary);
                color: #fff !important;
            }
            .user-icon {
                width: 1.5rem; height: 1.5rem;
                display: flex; align-items: center; justify-content: center;
                background: rgba(var(--brand-primary-rgb),0.2);
                border-radius: 0.375rem;
                font-size: 1rem;
            }
            .user-dropdown-btn.active .user-icon { background: rgba(255,255,255,0.2); }

            .dropdown-menu-refined {
                position: absolute;
                top: calc(100% + 0.5rem);
                right: 0;
                z-index: 1060;
                min-width: 220px;
                background: var(--surface-card);
                border-radius: 0.75rem;
                box-shadow: 0 12px 32px rgba(0,0,0,0.12);
                overflow: hidden;
                border: 1px solid var(--border-light);
            }
            .dropdown-item-refined {
                display: flex;
                align-items: center;
                width: 100%;
                padding: 0.75rem 1rem;
                color: var(--text-primary);
                text-decoration: none;
                background: transparent;
                border: 0;
                font-size: 0.88rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                gap: 0.625rem;
            }
            .dropdown-item-refined:hover { background: var(--surface-light); padding-left: 1.25rem; }
            .dropdown-item-refined.active { background: #f1f5f9; color: var(--brand-primary); }
            .dropdown-item-refined.logout { color: #dc3545; }
            .dropdown-divider-refined { height: 1px; margin: 0.25rem 0; background: var(--border-light); }

            /* ── Focus Ring (WCAG Keyboard Navigation) ──────────────── */
            *:focus-visible {
                outline: 3px solid var(--brand-primary);
                outline-offset: 2px;
                border-radius: 4px;
            }

            /* ── Main Content ───────────────────────────────────────── */
            main {
                padding: 2rem 0;
                min-height: calc(100vh - 200px);
            }

            .container, .container-fluid {
                max-width: 1200px;
                margin-left: auto;
                margin-right: auto;
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            @media (max-width: 768px) {
                .container, .container-fluid {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
                main { padding: 1.5rem 0; }
            }
        </style>
        <?php echo $__env->yieldPushContent('styles'); ?>
        <?php echo $__env->make('layouts.partials.clarity', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </head>
    <?php
        // Resolved once here and handed to both the navbar and the mobile
        // tab bar. Computing it inside each partial would run the same
        // reservation lookup twice on every request.
        $canReserve = true;

        if (Auth::check()) {
            $nowTs       = now();
            $todayStr    = $nowTs->toDateString();
            $currentTime = $nowTs->format('H:i:s');

            $allowMultiple = (bool) \App\Models\SystemSetting::getValue('allow_multiple_advance_reservations', '0');

            $hasActive = \App\Models\Reservation::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($q) use ($todayStr, $currentTime) {
                    $q->where('end_date', '>', $todayStr)
                      ->orWhere(fn ($q2) => $q2->where('end_date', $todayStr)->where('end_time', '>', $currentTime));
                })->exists();

            $canReserve = $allowMultiple || ! $hasActive;
        }

        // The tab bar is suppressed on the wizard (see below). This flag
        // drives both the include and the reserved bottom padding, so the
        // two can never disagree.
        $showMobileTabbar = Auth::check() && ! request()->routeIs('guest.reserve');
    ?>
    
    <body class="<?php echo \Illuminate\Support\Arr::toCssClasses(['has-mobile-tabbar' => $showMobileTabbar]); ?>">

        
        <?php if(branding('announcement_enabled') === '1' && trim(branding('announcement_text', '')) !== ''): ?>
            <div class="hn-announcement" id="hnAnnouncement" role="banner">
                <div class="container py-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                        <span class="hn-announcement-text flex-grow-1"><?php echo branding('announcement_text'); ?></span>
                        <button type="button" class="hn-announcement-close" aria-label="Dismiss announcement" onclick="dismissAnnouncement()">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <script>
                (function () {
                    if (localStorage.getItem('hn-announcement-dismissed')) {
                        document.getElementById('hnAnnouncement')?.remove();
                    }
                    window.dismissAnnouncement = function () {
                        document.getElementById('hnAnnouncement')?.remove();
                        try { localStorage.setItem('hn-announcement-dismissed', '1'); } catch (e) {}
                    };
                })();
            </script>
            <style>
                .hn-announcement { background: linear-gradient(90deg, <?php echo e(branding('brand_primary', '#2563eb')); ?>, <?php echo e(branding('brand_secondary', '#7c3aed')); ?>); color: #fff; }
                .hn-announcement-text { font-size: .88rem; font-weight: 500; }
                .hn-announcement-close {
                    background: rgba(255,255,255,.15); border: 0; color: #fff;
                    width: 1.6rem; height: 1.6rem; border-radius: 50%;
                    display: inline-flex; align-items: center; justify-content: center;
                    font-size: .8rem; cursor: pointer; flex-shrink: 0;
                }
                .hn-announcement-close:hover { background: rgba(255,255,255,.3); }
            </style>
        <?php endif; ?>

        <!-- Skip to Content (WCAG) -->
        <a href="#main-content" class="skip-to-content">Skip to main content</a>

        <!-- Header Navigation -->
        <div id="pjax-navbar" data-pjax-swap>
            <?php echo $__env->make('layouts.guest-navbar', ['canReserve' => $canReserve], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php if($showMobileTabbar): ?>
                <?php echo $__env->make('layouts.partials.mobile-tabbar', ['canReserve' => $canReserve], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </div>

        <!-- Main Content -->
        <main id="main-content" role="main" data-pjax-swap>
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- Footer -->
<?php echo $__env->make('layouts.partials.main-footer', ['hnFooterHideCta' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Accessibility Toolbar -->
        <?php echo $__env->make('components.accessibility-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Cancel Booking Customized Modal -->
        <?php echo $__env->make('components.cancel-booking-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Vite: Alpine.js + Laravel Echo — loaded after Bootstrap -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/layouts/app.blade.php ENDPATH**/ ?>