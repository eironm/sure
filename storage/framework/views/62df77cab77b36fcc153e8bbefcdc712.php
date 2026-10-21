<?php $__env->startSection('title', 'User Management'); ?>
<?php $__env->startSection('heading', 'User Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="ph-title">User Management</h1>
        <p class="ph-sub">Every account on the platform, with roles, statuses and access controls.</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('superadmin.dashboard')); ?>" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Dashboard</a>
    </div>
</div>

    <?php echo $__env->make('shared.user-management', ['panel' => 'superadmin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/superadmin/users/index.blade.php ENDPATH**/ ?>