
<?php echo $__env->make('components.delete-confirmation-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
    $isSuper = $panel === 'superadmin';
    $tabs = [
        ['key' => null,         'label' => 'All',         'icon' => 'bi-people',        'count' => $roleCounts['all']],
        ['key' => 'superadmin', 'label' => 'Super Admin', 'icon' => 'bi-shield-lock',   'count' => $roleCounts['superadmin']],
        ['key' => 'admin',      'label' => 'Admin',       'icon' => 'bi-shield-check',  'count' => $roleCounts['admin']],
        ['key' => 'staff',      'label' => 'Staff',       'icon' => 'bi-briefcase',     'count' => $roleCounts['staff']],
        ['key' => 'cashier',    'label' => 'Cashier',     'icon' => 'bi-cash-coin',     'count' => $roleCounts['cashier']],
        ['key' => 'guest',      'label' => 'Guest',       'icon' => 'bi-person',        'count' => $roleCounts['guest']],
    ];
?>

<?php if($panel !== 'superadmin'): ?>

<style>
    .role-tabs { display: flex; gap: .45rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
    .role-tab {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .42rem .85rem; border-radius: 99px;
        font-size: .78rem; font-weight: 700; text-decoration: none;
        border: 1px solid var(--line, #e7eaf0); background: #fff;
        color: var(--ink-2, #475569); transition: all .15s;
    }
    .role-tab .count { font-weight: 600; opacity: .6; }
    .role-tab:hover { border-color: rgba(var(--accent-rgb), .5); color: var(--accent); }
    .role-tab.active { background: var(--accent); border-color: var(--accent); color: #fff; }
    .role-tab.active .count { opacity: .85; }
    .role-tab.tab-inactive:not(.active) { color: #b91c1c; border-color: #fecaca; }
    .role-tab.tab-inactive:not(.active):hover { background: #fef2f2; }
    .role-tab.tab-inactive.active { background: #dc2626; border-color: #dc2626; }
    .role-chip {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 99px;
        font-size: .68rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .05em;
    }
    .role-superadmin { background: #ede9fe; color: #6d28d9; }
    .role-admin      { background: #dbeafe; color: #1d4ed8; }
    .role-cashier    { background: #d1fae5; color: #047857; }
    .role-staff      { background: #fef3c7; color: #b45309; }
    .role-guest      { background: #f1f5f9; color: #475569; }
    .chip-active     { background: #ecfdf5; color: #047857; }
    .chip-inactive   { background: #fef2f2; color: #b91c1c; }
    .chip-muted      { background: #f8fafc; color: #94a3b8; border: 1px dashed var(--line, #e7eaf0); text-transform: none; letter-spacing: 0; font-weight: 600; }
</style>
<?php else: ?>
<?php $__env->startPush('styles'); ?>
<style>
    .sa .role-chip {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 99px;
        font-size: .66rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: .06em;
    }
    .sa .role-superadmin { background: #ede9fe; color: #6d28d9; }
    .sa .role-admin      { background: #dbeafe; color: #1d4ed8; }
    .sa .role-cashier    { background: #d1fae5; color: #047857; }
    .sa .role-staff      { background: #fef3c7; color: #b45309; }
    .sa .role-guest      { background: #f1f5f9; color: #475569; }
    .sa .role-chip.chip-active   { background: var(--ok-bg); color: var(--ok); text-transform: none; letter-spacing: 0; }
    .sa .role-chip.chip-inactive { background: var(--bad-bg); color: var(--bad); text-transform: none; letter-spacing: 0; }
    .sa .role-chip.chip-muted { background: #f8fafc; color: var(--ink-3); border: 1px dashed var(--line-strong); text-transform: none; letter-spacing: 0; font-weight: 600; }
</style>
<?php $__env->stopPush(); ?>
<?php endif; ?>


<div class="<?php echo e($panel === 'superadmin' ? 'tabs' : 'role-tabs'); ?>">
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route($panel.'.users.index', $tab['key'] ? ['role' => $tab['key']] : [])); ?>"
           class="<?php echo e($panel === 'superadmin' ? 'tab' : 'role-tab'); ?> <?php echo e((request('role') === $tab['key'] || (!$tab['key'] && !request('role') && request('status') !== 'inactive')) && request('status') !== 'inactive' ? 'active' : ''); ?>">
            <i class="bi <?php echo e($tab['icon']); ?>"></i> <?php echo e($tab['label']); ?> <span class="count"><?php echo e($tab['count']); ?></span>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route($panel.'.users.index', ['status' => 'inactive'])); ?>"
       class="<?php echo e($panel === 'superadmin' ? 'tab' : 'role-tab tab-inactive'); ?> <?php echo e(request('status') === 'inactive' ? 'active' : ''); ?>">
        <i class="bi bi-slash-circle"></i> Inactive <span class="count"><?php echo e($roleCounts['inactive']); ?></span>
    </a>
</div>

<div class="card <?php echo e($panel === 'superadmin' ? '' : 'rounded-4'); ?> overflow-hidden">
    
    <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 py-3 px-4">
        <form method="GET" action="<?php echo e(route($panel.'.users.index')); ?>" class="d-flex align-items-center gap-2 flex-grow-1 m-0">
            <?php if(request('role')): ?><input type="hidden" name="role" value="<?php echo e(request('role')); ?>"><?php endif; ?>
            <?php if(request('status')): ?><input type="hidden" name="status" value="<?php echo e(request('status')); ?>"><?php endif; ?>
            <?php if($panel === 'superadmin'): ?>
                <div class="search-box" style="flex:1;">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" name="search" placeholder="Search name or email…" value="<?php echo e(request('search')); ?>">
                </div>
            <?php else: ?>
                <div class="input-group" style="max-width: 340px;">
                    <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Search name or email…" value="<?php echo e(request('search')); ?>">
                </div>
            <?php endif; ?>
            <?php if(request()->hasAny(['search', 'role', 'status'])): ?>
                <a href="<?php echo e(route($panel.'.users.index')); ?>" class="btn <?php echo e($panel === 'superadmin' ? 'btn-ghost' : 'btn-sm btn-light border rounded-pill px-3 fw-semibold text-nowrap'); ?>">
                    <i class="bi bi-x-lg me-1"></i>Clear
                </a>
            <?php endif; ?>
        </form>

        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small fw-semibold text-nowrap"><?php echo e($users->total()); ?> account<?php echo e($users->total() !== 1 ? 's' : ''); ?></span>
            <a href="<?php echo e(route($panel.'.users.create')); ?>" class="<?php echo e($panel === 'superadmin' ? 'btn btn-primary' : 'btn btn-sure rounded-pill px-4 text-nowrap'); ?>">
                <i class="bi bi-plus-lg me-1"></i> Add Account
            </a>
        </div>
    </div>

    
    <?php if($panel === 'superadmin'): ?>
        <div class="table-responsive">
            <table class="plain">
                <thead>
                    <tr>
                        <th style="width: 42%;">Account</th>
                        <th style="width: 11%;">Role</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 13%;">Joined</th>
                        <th class="actions-col" style="width: 24%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isSelf = auth()->id() === $user->id;
                        $canManage = !$isSelf && ($isSuper
                            ? $user->role !== 'superadmin'
                            : in_array($user->role, ['guest', 'cashier', 'staff']));
                    ?>
                    <tr>
                        <td>
                            <div class="who">
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
                                <div style="min-width:0;">
                                    <div class="who-name">
                                        <?php echo e($user->name); ?>

                                        <?php if($isSelf): ?> <span class="badge badge-soft" style="font-size:.58rem; padding:1px 6px;">YOU</span> <?php endif; ?>
                                    </div>
                                    <div class="who-sub" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($user->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="role-chip role-<?php echo e($user->role); ?>"><?php echo e($user->roleLabel()); ?></span></td>
                        <td>
                            <span class="role-chip <?php echo e($user->is_active ? 'chip-active' : 'chip-inactive'); ?>">
                                <i class="bi bi-<?php echo e($user->is_active ? 'check-circle-fill' : 'x-circle-fill'); ?>"></i>
                                <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td>
                            <div class="who-name"><?php echo e($user->created_at->format('M d, Y')); ?></div>
                            <div class="who-sub"><?php echo e($user->created_at->format('h:i A')); ?></div>
                        </td>
                        <td style="text-align: right;">
                            <?php if($isSelf): ?>
                                <span class="role-chip chip-muted">Use Profile page</span>
                            <?php elseif(!$canManage): ?>
                                <span class="role-chip chip-muted"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php else: ?>
                                <div class="d-inline-flex gap-2">
                                    <a href="<?php echo e(route($panel.'.users.edit', $user)); ?>" class="btn btn-ghost btn-sm">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                    <form method="POST" action="<?php echo e(route($panel.'.users.toggleActive', $user)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                                class="btn btn-ghost btn-sm <?php echo e($user->is_active ? 'btn-outline-danger' : 'btn-outline-success'); ?>"
                                                onclick="showDeleteConfirmation(event, 'User Account', 'toggle', '<?php echo e($user->is_active ? 'deactivate' : 'activate'); ?>')">
                                            <i class="bi bi-<?php echo e($user->is_active ? 'person-slash' : 'person-check'); ?> me-1"></i><?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?>

                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty">
                                <i class="bi bi-people"></i>
                                <strong>No accounts found</strong>
                                <span>No records match your current filters.</span>
                                <?php if(request()->hasAny(['search', 'role', 'status'])): ?>
                                    <a href="<?php echo e(route($panel.'.users.index')); ?>" class="btn btn-ghost btn-sm mt-2">Clear all filters</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Account</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-end pe-4" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isSelf = auth()->id() === $user->id;
                        $canManage = !$isSelf && ($isSuper
                            ? $user->role !== 'superadmin'
                            : in_array($user->role, ['guest', 'cashier', 'staff']));
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
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
                                <div class="lh-sm">
                                    <div class="fw-bold text-dark">
                                        <?php echo e($user->name); ?>

                                        <?php if($isSelf): ?><span class="badge rounded-pill ms-1" style="background: var(--accent); font-size: .55rem;">YOU</span><?php endif; ?>
                                    </div>
                                    <div class="small text-muted"><?php echo e($user->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="role-chip role-<?php echo e($user->role); ?>"><?php echo e($user->roleLabel()); ?></span></td>
                        <td>
                            <span class="role-chip <?php echo e($user->is_active ? 'chip-active' : 'chip-inactive'); ?>">
                                <i class="bi bi-<?php echo e($user->is_active ? 'check-circle-fill' : 'x-circle-fill'); ?>"></i>
                                <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark"><?php echo e($user->created_at->format('M d, Y')); ?></div>
                            <div class="text-muted" style="font-size: .72rem;"><?php echo e($user->created_at->format('h:i A')); ?></div>
                        </td>
                        <td class="text-end pe-4">
                            <?php if($isSelf): ?>
                                <span class="role-chip chip-muted">Use Profile page</span>
                            <?php elseif(!$canManage): ?>
                                <span class="role-chip chip-muted"><i class="bi bi-lock-fill"></i> Locked</span>
                            <?php else: ?>
                                <div class="d-inline-flex gap-2">
                                    <a href="<?php echo e(route($panel.'.users.edit', $user)); ?>" class="btn btn-sm btn-light border fw-semibold rounded-pill px-3">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                    <form method="POST" action="<?php echo e(route($panel.'.users.toggleActive', $user)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                                class="btn btn-sm fw-semibold rounded-pill px-3 <?php echo e($user->is_active ? 'btn-outline-danger' : 'btn-outline-success'); ?>"
                                                onclick="showDeleteConfirmation(event, 'User Account', 'toggle', '<?php echo e($user->is_active ? 'deactivate' : 'activate'); ?>')">
                                            <i class="bi bi-<?php echo e($user->is_active ? 'person-slash' : 'person-check'); ?> me-1"></i><?php echo e($user->is_active ? 'Deactivate' : 'Activate'); ?>

                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" style="width: 72px; height: 72px;">
                                <i class="bi bi-people text-muted opacity-50 fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">No accounts found</h6>
                            <p class="text-muted small mb-3">No records match your current filters.</p>
                            <?php if(request()->hasAny(['search', 'role', 'status'])): ?>
                                <a href="<?php echo e(route($panel.'.users.index')); ?>" class="btn btn-sm btn-light border rounded-pill px-4 fw-semibold">Clear all filters</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if($users->hasPages()): ?>
        <div class="card-footer bg-white py-3 px-4 d-flex justify-content-center">
            <?php echo e($users->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/shared/user-management.blade.php ENDPATH**/ ?>