
<?php
    $user = Auth::user();
    $canSuper  = $user->isSuperAdmin();
    $canAdmin  = $user->isAdminOrAbove() || $user->isStaff();
    $canCashier = $user->canAccessCashier();
    $canGuest  = ! $user->isStaff();
?>

<div class="sidebar-footer">
    <div class="user-info">
        <?php if (isset($component)) { $__componentOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.user-avatar','data' => ['user' => $user,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('user-avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e)): ?>
<?php $attributes = $__attributesOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e; ?>
<?php unset($__attributesOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e)): ?>
<?php $component = $__componentOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e; ?>
<?php unset($__componentOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e); ?>
<?php endif; ?>
        <div class="user-details">
            <span class="user-name"><?php echo e($user->name); ?></span>
            <span class="user-role"><?php echo e($user->roleLabel()); ?></span>
        </div>
    </div>

    <?php if(! $user->isRestrictedCashier()): ?>
        <div class="panel-switcher">
            <?php if($canSuper): ?>
                <a href="<?php echo e(route('superadmin.dashboard')); ?>"
                   class="switch-btn <?php echo e($current === 'superadmin' ? 'd-none' : ''); ?>"
                   title="SuperAdmin Panel">
                    <i class="bi bi-shield-lock"></i> Super
                </a>
            <?php endif; ?>
            <?php if($canAdmin): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   class="switch-btn <?php echo e($current === 'admin' ? 'd-none' : ''); ?>"
                   title="Admin Panel">
                    <i class="bi bi-grid-3x3-gap"></i> Admin
                </a>
            <?php endif; ?>
            <?php if($canCashier): ?>
                <a href="<?php echo e(route('cashier.dashboard')); ?>"
                   class="switch-btn <?php echo e($current === 'cashier' ? 'd-none' : ''); ?>"
                   title="Cashier Panel">
                    <i class="bi bi-cash-coin"></i> Cashier
                </a>
            <?php endif; ?>
            <?php if($canGuest): ?>
                <a href="<?php echo e(route('guest.dashboard')); ?>"
                   class="switch-btn <?php echo e($current === 'guest' ? 'd-none' : ''); ?>"
                   title="Guest Panel">
                    <i class="bi bi-person-badge"></i> Guest
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('logout')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn-logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/layouts/partials/panel-footer.blade.php ENDPATH**/ ?>