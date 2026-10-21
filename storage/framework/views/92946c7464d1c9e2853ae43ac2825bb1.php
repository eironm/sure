
<?php ($canReserve = $canReserve ?? true); ?>

<nav class="mobile-tabbar" aria-label="Primary">
    <div class="mobile-tabbar-inner">

        <a href="<?php echo e(route('guest.dashboard')); ?>"
           class="mobile-tab <?php echo e(request()->routeIs('guest.dashboard') ? 'active' : ''); ?>"
           <?php if(request()->routeIs('guest.dashboard')): ?> aria-current="page" <?php endif; ?>>
            <i class="bi <?php echo e(request()->routeIs('guest.dashboard') ? 'bi-grid-1x2-fill' : 'bi-grid-1x2'); ?>" aria-hidden="true"></i>
            <span>Home</span>
        </a>

        <a href="<?php echo e(route('guest.facilities')); ?>"
           class="mobile-tab <?php echo e(request()->routeIs('guest.facilities') ? 'active' : ''); ?>"
           <?php if(request()->routeIs('guest.facilities')): ?> aria-current="page" <?php endif; ?>>
            <i class="bi <?php echo e(request()->routeIs('guest.facilities') ? 'bi-map-fill' : 'bi-map'); ?>" aria-hidden="true"></i>
            <span>Explore</span>
        </a>

        <?php if($canReserve): ?>
            <a href="<?php echo e(route('guest.reserve')); ?>"
               class="mobile-tab tab-primary <?php echo e(request()->routeIs('guest.reserve') ? 'active' : ''); ?>"
               <?php if(request()->routeIs('guest.reserve')): ?> aria-current="page" <?php endif; ?>>
                <i class="bi bi-plus-lg" aria-hidden="true"></i>
                <span>Reserve</span>
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('guest.facility-board')); ?>"
               class="mobile-tab <?php echo e(request()->routeIs('guest.facility-board') ? 'active' : ''); ?>"
               <?php if(request()->routeIs('guest.facility-board')): ?> aria-current="page" <?php endif; ?>>
                <i class="bi <?php echo e(request()->routeIs('guest.facility-board') ? 'bi-calendar3-week-fill' : 'bi-calendar3-week'); ?>" aria-hidden="true"></i>
                <span>Board</span>
            </a>
        <?php endif; ?>

        <a href="<?php echo e(route('guest.history')); ?>"
           class="mobile-tab <?php echo e(request()->routeIs('guest.history') ? 'active' : ''); ?>"
           <?php if(request()->routeIs('guest.history')): ?> aria-current="page" <?php endif; ?>>
            <i class="bi <?php echo e(request()->routeIs('guest.history') ? 'bi-clock-history' : 'bi-clock'); ?>" aria-hidden="true"></i>
            <span>Bookings</span>
        </a>

        <a href="<?php echo e(route('profile.edit')); ?>"
           class="mobile-tab <?php echo e(request()->routeIs('profile.edit') ? 'active' : ''); ?>"
           <?php if(request()->routeIs('profile.edit')): ?> aria-current="page" <?php endif; ?>>
            <i class="bi <?php echo e(request()->routeIs('profile.edit') ? 'bi-person-fill' : 'bi-person'); ?>" aria-hidden="true"></i>
            <span>Profile</span>
        </a>

    </div>
</nav>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/layouts/partials/mobile-tabbar.blade.php ENDPATH**/ ?>