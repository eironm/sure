<?php $__env->startSection('title', 'Verify email'); ?>
<?php $__env->startSection('panel_eyebrow'); ?>
    Official University System
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_title'); ?>
    One last <span class="gold">step</span>.
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_sub'); ?>
    Your account is almost ready &mdash; just confirm your email address and you&rsquo;ll be booking campus spaces in no time.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('auth_title', 'Check your inbox'); ?>
<?php $__env->startSection('auth_subtitle'); ?>
    Thanks for signing up! We've sent a verification link to your email address. Click the link to activate your account and start reserving facilities.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('status') == 'verification-link-sent'): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-envelope-check-fill fs-5 text-success"></i>
            <span class="small fw-semibold">A new verification link has been sent to the email address you provided during registration.</span>
        </div>
    </div>
<?php endif; ?>

<div class="d-flex flex-column gap-3">
    <form method="POST" action="<?php echo e(route('verification.send')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-sure">Resend verification email</button>
    </form>

    <form method="POST" action="<?php echo e(route('logout')); ?>" class="text-center">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-link auth-link p-0">Sign out</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>