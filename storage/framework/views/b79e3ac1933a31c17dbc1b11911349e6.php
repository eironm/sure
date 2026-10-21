
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>

<?php
    $greeting = (int) now()->format('H');
    $greet = $greeting < 12 ? 'Good morning' : ($greeting < 18 ? 'Good afternoon' : 'Good evening');
?>


<div class="page-header">
    <div>
        <h1 class="ph-title"><?php echo e($greet); ?>, <?php echo e(explode(' ', Auth::user()->name)[0]); ?></h1>
        <p class="ph-sub">
            <?php echo e(now()->format('l, F j, Y')); ?> &nbsp;·&nbsp; Here's what's happening across the platform right now.
        </p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('superadmin.users.create')); ?>" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Create Account
        </a>
    </div>
</div>


<div class="stat-grid" style="margin-bottom: 1.25rem;">
    <a class="stat-card" href="<?php echo e(route('superadmin.users.index')); ?>">
        <div class="stat-top">
            <span class="stat-tile neutral"><i class="bi bi-people-fill"></i></span>
        </div>
        <div class="stat-value"><?php echo e(number_format($stats['total_users'])); ?></div>
        <div class="stat-label">Total Accounts</div>
    </a>

    <a class="stat-card" href="<?php echo e(route('superadmin.users.index')); ?>?status=active">
        <div class="stat-top">
            <span class="stat-tile ok"><i class="bi bi-person-check-fill"></i></span>
        </div>
        <div class="stat-value"><?php echo e(number_format($stats['total_users'] - $stats['inactive_accounts'])); ?></div>
        <div class="stat-label">Active Accounts</div>
    </a>

    <a class="stat-card" href="<?php echo e(route('superadmin.users.index')); ?>?status=inactive">
        <div class="stat-top">
            <span class="stat-tile bad"><i class="bi bi-person-x-fill"></i></span>
        </div>
        <div class="stat-value"><?php echo e(number_format($stats['inactive_accounts'])); ?></div>
        <div class="stat-label">Inactive Accounts</div>
    </a>

    <a class="stat-card" href="<?php echo e(route('superadmin.users.index')); ?>?role=cashier">
        <div class="stat-top">
            <span class="stat-tile info"><i class="bi bi-cash-coin"></i></span>
        </div>
        <div class="stat-value">₱<?php echo e(number_format($stats['total_collected'], 0)); ?></div>
        <div class="stat-label">Total Collected</div>
    </a>
</div>


<div class="stat-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 1.5rem;">
    <a class="stat-card" href="<?php echo e(route('admin.reservations.index')); ?>?status=pending">
        <div class="stat-top">
            <span class="stat-tile warn"><i class="bi bi-hourglass-split"></i></span>
        </div>
        <div class="stat-value"><?php echo e(number_format($stats['pending_reservations'])); ?></div>
        <div class="stat-label">Pending Reservations</div>
    </a>

    <a class="stat-card" href="<?php echo e(route('admin.reservations.index')); ?>?payment_status=cash_pending">
        <div class="stat-top">
            <span class="stat-tile warn"><i class="bi bi-cash-stack"></i></span>
        </div>
        <div class="stat-value"><?php echo e(number_format($stats['cash_pending'])); ?></div>
        <div class="stat-label">Cash Pending Payment</div>
    </a>

    <a class="stat-card" href="<?php echo e(route('admin.reservations.index')); ?>?status=approved">
        <div class="stat-top">
            <span class="stat-tile ok"><i class="bi bi-calendar-check-fill"></i></span>
        </div>
        <div class="stat-value"><?php echo e(number_format($stats['approved_reservations'])); ?></div>
        <div class="stat-label">Approved Reservations</div>
    </a>
</div>


<div class="two-col">
    <div class="card">
        <div class="card-head">
            <span class="ch-title"><i class="bi bi-person-lines-fill"></i> Recent Accounts</span>
            <a href="<?php echo e(route('superadmin.users.index')); ?>" class="sa-link">View all →</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="plain">
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="who">
                                <?php if (isset($component)) { $__componentOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaa6ddd3b8ee0acee5a2d1d7ac5c7e40e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.user-avatar','data' => ['user' => $u,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('user-avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($u),'size' => 'sm']); ?>
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
                                <div style="min-width:0;">
                                    <div class="who-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($u->name); ?></div>
                                    <div class="who-sub" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($u->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-soft"><?php echo e($u->roleLabel()); ?></span></td>
                        <td>
                            <span class="badge <?php echo e($u->is_active ? 'badge-ok' : 'badge-bad'); ?>">
                                <?php echo e($u->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3">
                        <div class="empty">
                            <i class="bi bi-people"></i>
                            <strong>No accounts yet</strong>
                            <span>Created accounts will appear here.</span>
                        </div>
                    </td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <span class="ch-title"><i class="bi bi-calendar3"></i> Recent Reservations</span>
            <a href="<?php echo e(route('admin.reservations.index')); ?>" class="sa-link">View all →</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="plain">
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="who-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($r->user->name ?? 'N/A'); ?></div>
                            <div class="who-sub mono"><?php echo e($r->booking_reference); ?></div>
                        </td>
                        <td><?php echo e($r->facility->name ?? 'N/A'); ?></td>
                        <td>
                            <?php
                                $pstatus = match ($r->payment_status) {
                                    'paid' => ['Paid', 'badge-ok'],
                                    'cash_pending' => ['Cash Pending', 'badge-warn'],
                                    default => ['Unpaid', 'badge-bad'],
                                };
                            ?>
                            <span class="badge <?php echo e($pstatus[1]); ?>"><?php echo e($pstatus[0]); ?></span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3">
                        <div class="empty">
                            <i class="bi bi-calendar-x"></i>
                            <strong>No reservations yet</strong>
                            <span>Booking activity will appear here.</span>
                        </div>
                    </td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/superadmin/dashboard.blade.php ENDPATH**/ ?>