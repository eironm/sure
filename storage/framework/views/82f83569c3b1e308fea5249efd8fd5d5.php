<?php $__env->startSection('title', 'Confirm password'); ?>
<?php $__env->startSection('panel_eyebrow'); ?>
    Official University System
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_title'); ?>
    Just one quick <span class="gold">check</span>.
<?php $__env->stopSection(); ?>
<?php $__env->startSection('panel_sub'); ?>
    Please confirm your password before continuing &mdash; it keeps your account safe, and only takes a second.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('auth_title', 'Confirm your password'); ?>
<?php $__env->startSection('auth_subtitle'); ?>
    This is a secure area of the application. Please confirm your password before continuing.
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('password.confirm')); ?>">
    <?php echo csrf_field(); ?>

    <div class="mb-4">
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
                   name="password" required autocomplete="current-password" placeholder="Enter your password">
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

    <button type="submit" class="btn btn-sure">Confirm</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/auth/confirm-password.blade.php ENDPATH**/ ?>