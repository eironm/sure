<?php $__env->startSection('title', 'Forgot password'); ?>
<?php $__env->startSection('panel_eyebrow'); ?>
    Official University System
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_title'); ?>
    Happens to <span class="gold">everyone</span>.
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_sub'); ?>
    We&rsquo;ll send a secure link to your registered email so you can choose a fresh password in a couple of taps &mdash; no fuss.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_action'); ?>
    <a href="<?php echo e(route('login')); ?>" class="auth-header-action">Sign in</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('auth_title', 'Forgot your password?'); ?>
<?php $__env->startSection('auth_subtitle'); ?>
    No worries. Enter the email you registered with and we'll send you a secure link to reset your password.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('status')): ?>
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-envelope-check-fill fs-5 text-success"></i>
            <span class="small fw-semibold"><?php echo e(session('status')); ?></span>
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('password.email')); ?>">
    <?php echo csrf_field(); ?>

    <div class="mb-4">
        <label for="email" class="form-label">Email address</label>
        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username"
               placeholder="name@example.com">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <button type="submit" class="btn btn-sure">Send reset link</button>

    <div class="mt-4 text-center">
        <a href="<?php echo e(route('login')); ?>" class="auth-link"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>