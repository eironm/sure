

<?php $__env->startSection('title', 'Sign in'); ?>
<?php $__env->startSection('panel_eyebrow'); ?>
    Official University System
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_title'); ?>
    Welcome <span class="gold">back</span>, we&rsquo;ve missed you.
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_sub'); ?>
    Your gateway to every campus space &mdash; reserve what you need, when you need it, in just a few taps.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_action'); ?>
    <a href="<?php echo e(route('register')); ?>" class="auth-header-action">Create account</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('auth_title', 'Sign in to your account'); ?>
<?php if(!empty(trim(branding('login_welcome_text', '')))): ?>
    <?php $__env->startSection('auth_subtitle'); ?>
        <?php echo branding('login_welcome_text'); ?>

        <span class="text-muted">· New here? <a href="<?php echo e(route('register')); ?>" class="auth-link">Create an account</a></span>
    <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php $__env->startSection('auth_subtitle'); ?>
        Book campus facilities in minutes. New here? <a href="<?php echo e(route('register')); ?>">Create an account</a>
    <?php $__env->stopSection(); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<?php if(session('status')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <span class="small fw-semibold"><?php echo e(session('status')); ?></span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <span class="small fw-semibold"><?php echo e(session('error')); ?></span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo csrf_field(); ?>

    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        
        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               name="email" value="<?php echo e(old('email')); ?>" required autocomplete="username"
               inputmode="email" autocapitalize="none" autocorrect="off" spellcheck="false"
               enterkeyhint="next"
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

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="password-field">
            <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   name="password" required autocomplete="current-password"
                   autocapitalize="none" autocorrect="off" spellcheck="false"
                   enterkeyhint="go"
                   placeholder="Enter your password">
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check mb-0">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" style="border-radius:5px;">
            <label class="form-check-label small" for="remember">Keep me signed in</label>
        </div>
        <?php if(Route::has('password.request')): ?>
            <a class="auth-link" href="<?php echo e(route('password.request')); ?>">Forgot password?</a>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-sure">Sign in</button>

    <div class="auth-divider">or</div>

    <a href="<?php echo e(route('register')); ?>" class="btn btn-sure-ghost">Create a new account</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/auth/login.blade.php ENDPATH**/ ?>