<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="sa-shell-wrap">
<head>
    <title><?php echo $__env->yieldContent('title', 'SuperAdmin'); ?> - <?php echo e(branding('system_name', 'Platform')); ?></title>
    <?php echo $__env->make('layouts.partials.panel-head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $superAdminCssPath = resource_path('css/panel2.css'); ?>
    <?php if(is_file($superAdminCssPath)): ?>
        
        <style id="superadmin-page-css"><?php echo file_get_contents($superAdminCssPath); ?></style>
    <?php endif; ?>
</head>
<body>
    <div class="sidebar" id="panelSidebar" data-drawer role="navigation" aria-label="Panel navigation">
        <div class="sidebar-header">
            
            <button type="button" class="sidebar-close" data-drawer-close aria-label="Close navigation menu">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
            <div class="brand-wrapper">
                <img src="<?php echo e(\App\Services\BrandingService::logoForBg('dark')); ?>" data-logo-light="<?php echo e(\App\Services\BrandingService::logoForBg('dark')); ?>" data-logo-dark="<?php echo e(\App\Services\BrandingService::logoForBg('light')); ?>" alt="<?php echo e(branding('system_name', 'Platform')); ?> Logo" class="brand-logo-img<?php echo e(\App\Services\BrandingService::logoAutoVariant() ? ' brand-logo-auto' : ''); ?><?php echo e(\App\Services\BrandingService::wordmarkOnly() ? ' brand-wordmark-only' : ''); ?>">
                <span class="brand-name<?php echo e(\App\Services\BrandingService::wordmarkOnly() ? ' d-none' : ''); ?>"><?php echo e(branding('system_name', 'Platform')); ?></span>
            </div>
            <div class="current-panel-badge">
                <i class="bi bi-shield-fill-check"></i>
                SuperAdmin Panel
            </div>
        </div>

        <nav id="pjax-sidenav" data-pjax-swap>
            <div class="nav-section">Overview</div>
            <a href="<?php echo e(route('superadmin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('superadmin.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-section">Administration</div>
            <a href="<?php echo e(route('superadmin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('superadmin.users.*') ? 'active' : ''); ?>">
                <i class="bi bi-people"></i> User Management
            </a>

            
            <div class="nav-section">Content &amp; Branding</div>
            <a href="<?php echo e(route('superadmin.settings.branding')); ?>" class="nav-link <?php echo e(request()->routeIs('superadmin.settings.branding', 'superadmin.settings.payments', 'superadmin.settings.announcements', 'superadmin.settings.pages', 'superadmin.settings.appearance', 'superadmin.settings.logo', 'superadmin.settings.reports') ? 'active' : ''); ?>">
                <i class="bi bi-sliders"></i> System Settings
            </a>
            <a href="<?php echo e(route('superadmin.settings.hero')); ?>" class="nav-link <?php echo e(request()->routeIs('superadmin.settings.hero', 'superadmin.settings.about', 'superadmin.settings.features', 'superadmin.settings.gallery', 'superadmin.settings.faq', 'superadmin.settings.contact') ? 'active' : ''); ?>">
                <i class="bi bi-house-fill"></i> Homepage Editor
            </a>
            <a href="<?php echo e(route('superadmin.settings.legal')); ?>" class="nav-link <?php echo e(request()->routeIs('superadmin.settings.legal*') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-text"></i> Legal Documents
            </a>

            <div class="nav-section">System</div>
            <a href="<?php echo e(route('superadmin.audit-logs')); ?>" class="nav-link <?php echo e(request()->routeIs('superadmin.audit-logs') ? 'active' : ''); ?>">
                <i class="bi bi-journal-text"></i> Audit Logs
            </a>
        </nav>

        <?php echo $__env->make('layouts.partials.panel-footer', ['current' => 'superadmin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <div class="main-content">
        <div class="top-bar">
            
            <button type="button" class="panel-hamburger" data-drawer-toggle="#panelSidebar"
                    aria-controls="panelSidebar" aria-expanded="false" aria-label="Open navigation menu">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
            <div class="top-bar-actions">
                <?php if (isset($component)) { $__componentOriginal318b79fe35291da50e94db40c1ab0d95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal318b79fe35291da50e94db40c1ab0d95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.realtime-clock','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('realtime-clock'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal318b79fe35291da50e94db40c1ab0d95)): ?>
<?php $attributes = $__attributesOriginal318b79fe35291da50e94db40c1ab0d95; ?>
<?php unset($__attributesOriginal318b79fe35291da50e94db40c1ab0d95); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal318b79fe35291da50e94db40c1ab0d95)): ?>
<?php $component = $__componentOriginal318b79fe35291da50e94db40c1ab0d95; ?>
<?php unset($__componentOriginal318b79fe35291da50e94db40c1ab0d95); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6541145ad4a57bfb6e6f221ba77eb386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-bell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-bell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386)): ?>
<?php $attributes = $__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386; ?>
<?php unset($__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6541145ad4a57bfb6e6f221ba77eb386)): ?>
<?php $component = $__componentOriginal6541145ad4a57bfb6e6f221ba77eb386; ?>
<?php unset($__componentOriginal6541145ad4a57bfb6e6f221ba77eb386); ?>
<?php endif; ?>
            </div>
        </div>

        
        <div class="panel-backdrop" data-drawer-backdrop="#panelSidebar" aria-hidden="true"></div>

        <div id="pjax-content" data-pjax-swap>
            
            <div class="sa">
                <?php echo $__env->yieldPushContent('styles'); ?>

                <?php echo $__env->make('layouts.partials.panel-alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <?php if (! empty(trim($__env->yieldContent('header')))): ?>
                    <h1 class="header-title"><?php echo $__env->yieldContent('header'); ?></h1>
                <?php endif; ?>

                
                <?php if(View::hasSection('cms-preview')): ?>
                    <div id="cmsLivePreview" class="pv-shared-mount">
                        <?php echo $__env->yieldContent('cms-preview'); ?>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
                <?php echo $__env->yieldPushContent('scripts'); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    <?php echo $__env->make('components.accessibility-toolbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo \App\Services\BrandingService::logoAutoSwitchScript(); ?>

</body>
</html>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/layouts/superadmin.blade.php ENDPATH**/ ?>