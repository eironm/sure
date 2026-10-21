<?php $__env->startSection('title', '419 - Page Expired'); ?>

<?php $__env->startSection('content'); ?>
    <div class="error-header">
        <div class="sure-logo">
            <img src="<?php echo e(\App\Services\BrandingService::logoForBg('dark')); ?>" alt="<?php echo e(branding('system_name', 'Platform')); ?> Logo">
            <span class="sure-logo-text"><?php echo e(branding('system_name', 'Platform')); ?></span>
        </div>
    </div>

    <div class="error-icon error-419">
        <i class="bi bi-hourglass-end"></i>
    </div>

    <div class="error-code">419</div>

    <h1 class="error-title">Page Expired</h1>

    <p class="error-description">
        Your session has expired. This usually happens when you've been inactive for a while. Please refresh and try again.
    </p>

    <div class="error-actions">
        <a href="javascript:location.reload()" class="btn-primary">
            <i class="bi bi-arrow-clockwise"></i>
            Refresh Page
        </a>
        <a href="<?php echo e(url('/')); ?>" class="btn-secondary">
            <i class="bi bi-house-fill"></i>
            Go to Home
        </a>
    </div>

    <div class="error-details">
        <div class="error-detail-item">
            <i class="bi bi-info-circle"></i>
            <span>Error Code: 419 Page Expired</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-clock"></i>
            <span><?php echo e(now()->format('M d, Y H:i:s')); ?></span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Session Timeout</span>
        </div>
    </div>

    <div class="error-footer">
        <p>If you continue to experience issues, please <a href="mailto:<?php echo e(branding('contact_email', 'admin@university.edu')); ?>">contact support</a> or return to the <a href="<?php echo e(url('/')); ?>"><?php echo e(branding('system_name', 'Platform')); ?> Portal</a>.</p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/errors/419.blade.php ENDPATH**/ ?>