<?php $__env->startSection('title', 'Verify your email'); ?>
<?php $__env->startSection('panel_eyebrow'); ?>
    Official University System
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_title'); ?>
    One <span class="gold">last step</span> &mdash; verify your email.
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_sub'); ?>
    We sent a 6-digit verification code to your email address. Enter it below to activate your account and log in.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_action'); ?>
    <a href="<?php echo e(route('login')); ?>" class="auth-header-action">Sign in</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('auth_title', 'Enter your verification code'); ?>
<?php $__env->startSection('auth_subtitle'); ?>
    A 6-digit code was sent to <strong><?php echo e($email); ?></strong>.
    <?php if(isset($user) && $user && !$user->codeVerified()): ?>
        It expires in 10 minutes &mdash; request a new one anytime.
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('info')): ?>
    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
            <span class="small fw-semibold"><?php echo e(session('info')); ?></span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
<?php endif; ?>

<?php if(session('verify_error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <span class="small fw-semibold"><?php echo e(session('verify_error')); ?></span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
<?php endif; ?>

<?php if(session('resend_success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <span class="small fw-semibold"><?php echo e(session('resend_success')); ?></span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('verify-code.store')); ?>" id="verify-form">
    <?php echo csrf_field(); ?>

    <input type="hidden" name="email" value="<?php echo e($email); ?>">

    <div class="mb-3">
        <label for="code" class="form-label">Verification code</label>
        <input id="code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
               class="form-control form-control-lg text-center <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               name="code" value="<?php echo e(old('code')); ?>" required autofocus autocomplete="one-time-code"
               autocapitalize="none" autocorrect="off" spellcheck="false"
               placeholder="0 0 0 0 0 0"
               style="letter-spacing: .8rem; font-variant-numeric: tabular-nums;">
        <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="form-text">Enter the 6-digit code we emailed to you.</div>
    </div>

    <button type="submit" class="btn btn-sure w-100">Verify &amp; continue</button>
</form>

<div class="auth-divider mt-4">didn't get the code?</div>

<form method="POST" action="<?php echo e(route('verify-code.resend')); ?>" class="d-inline w-100">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="email" value="<?php echo e($email); ?>">
    <button type="submit" class="btn btn-sure-ghost w-100">Resend code</button>
</form>

<div class="mt-4 text-center">
    <a href="<?php echo e(route('login')); ?>" class="auth-link"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
</div>

<script>
    (function () {
        var code = document.getElementById('code');
        code.addEventListener('input', function () {
            var v = code.value.replace(/[^0-9]/g, '');
            if (v.length > 6) { v = v.slice(0, 6); }
            code.value = v;
            if (v.length === 6) {
                var form = document.getElementById('verify-form');
                if (form) { form.submit(); }
            }
        });
    })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/auth/verify-code.blade.php ENDPATH**/ ?>