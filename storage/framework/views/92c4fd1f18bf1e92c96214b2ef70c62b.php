<?php $__env->startSection('title', '403 - Access Forbidden'); ?>

<?php $__env->startSection('content'); ?>
    <div class="error-header">
        <div class="sure-logo">
            <img src="<?php echo e(\App\Services\BrandingService::logoForBg('dark')); ?>" alt="<?php echo e(branding('system_name', 'Platform')); ?> Logo">
            <span class="sure-logo-text"><?php echo e(branding('system_name', 'Platform')); ?></span>
        </div>
    </div>

    <div class="error-icon error-403">
        <i class="bi bi-lock-fill"></i>
    </div>

    <div class="error-code">403</div>

    <h1 class="error-title">Access Forbidden</h1>

    <p class="error-description">
        You don't have permission to access this resource. If you believe this is a mistake, please contact your administrator.
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
            <span>Error Code: 403 Forbidden</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-clock"></i>
            <span><?php echo e(now()->format('M d, Y H:i:s')); ?></span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-shield-exclamation"></i>
            <span>Insufficient Permissions</span>
        </div>
    </div>

    <div class="error-footer">
        <p>Need access? <a href="mailto:<?php echo e(branding('contact_email', 'admin@university.edu')); ?>">Request permissions</a> or contact your <a href="<?php echo e(url('/')); ?>">administrator</a>.</p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/errors/403.blade.php ENDPATH**/ ?>