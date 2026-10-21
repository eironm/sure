<?php $__env->startSection('title', '404 - Page Not Found'); ?>

<?php $__env->startSection('content'); ?>
    <div class="error-header">
        <div class="sure-logo">
            <img src="<?php echo e(\App\Services\BrandingService::logoForBg('dark')); ?>" alt="<?php echo e(branding('system_name', 'Platform')); ?> Logo">
            <span class="sure-logo-text"><?php echo e(branding('system_name', 'Platform')); ?></span>
        </div>
    </div>

    <div class="error-icon error-404">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>

    <div class="error-code">404</div>

    <h1 class="error-title">Page Not Found</h1>

    <p class="error-description">
        The page you're looking for doesn't exist or has been moved. Let's get you back on track.
    </p>

    <div class="error-actions">
        <a href="<?php echo e(url('/')); ?>" class="btn-primary">
            <i class="bi bi-house-fill"></i>
            Go to Home
        </a>
        <a href="javascript:history.back()" class="btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Go Back
        </a>
    </div>

    <div class="error-details">
        <div class="error-detail-item">
            <i class="bi bi-info-circle"></i>
            <span>Error Code: 404 Not Found</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-clock"></i>
            <span><?php echo e(now()->format('M d, Y H:i:s')); ?></span>
        </div>
    </div>

    <div class="error-footer">
        <p>Need help? <a href="mailto:<?php echo e(branding('contact_email', 'admin@university.edu')); ?>">Contact Support</a> or visit the <a href="<?php echo e(url('/')); ?>"><?php echo e(branding('system_name', 'Platform')); ?> Portal</a></p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/errors/404.blade.php ENDPATH**/ ?>