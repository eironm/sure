<?php $__env->startSection('title', 'Cashier Station'); ?>
<?php $__env->startSection('heading', 'Cashier Station'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* ── SURE Cashier Design Language: Register & Ledger ── */
    .csh-amount { font-family: 'JetBrains Mono', monospace; font-variant-numeric: tabular-nums; font-weight: 800; color: #0f172a; }
    .csh-ref { font-family: 'JetBrains Mono', monospace; font-size: 0.84rem; letter-spacing: 0.02em; color: #334155; font-weight: 700; }
    .csh-ref .csh-ref-hash { color: #94a3b8; }

    /* Register tape — slate totals band */
    .csh-tape {
        background: #0f172a;
        border-radius: 12px;
        padding: 1.25rem 1.75rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
        color: #fff;
    }
    .csh-tape-brand { line-height: 1.3; }
    .csh-tape-brand .greet { font-size: 1.15rem; font-weight: 800; color: #fff; margin: 0; }
    .csh-tape-brand .greet .wave { display: inline-block; }
    .csh-tape-brand .csh-date { font-size: 0.8rem; color: #94a3b8; margin: 0; font-weight: 500; }
    .csh-tape-cells { display: flex; align-items: stretch; flex-wrap: wrap; }
    .csh-tape-cell { padding: 0.2rem 1.5rem; border-left: 1px solid rgba(255,255,255,0.14); display: flex; flex-direction: column; justify-content: center; }
    .csh-tape-cell:first-child { border-left: 0; padding-left: 0; }
    .csh-tape-cell .v { font-family: 'JetBrains Mono', monospace; font-weight: 800; font-size: 1.5rem; line-height: 1.15; color: #fff; font-variant-numeric: tabular-nums; }
    .csh-tape-cell .v.hero { font-size: 2.15rem; letter-spacing: -0.02em; color: #34d399; }
    .csh-tape-cell .l { font-size: 0.66rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #94a3b8; margin-top: 0.15rem; }

    /* Priority tiles */
    .csh-tiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
    .csh-tile { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem; transition: transform .15s, box-shadow .15s; }
    .csh-tile:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(15,23,42,0.06); }
    .csh-tile .ic { width: 2.6rem; height: 2.6rem; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; }
    .csh-tile .v { font-family: 'JetBrains Mono', monospace; font-size: 1.35rem; font-weight: 800; color: #0f172a; line-height: 1.1; font-variant-numeric: tabular-nums; }
    .csh-tile .l { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; margin-top: 0.1rem; }
    .csh-tile.red .ic { background: #fef2f2; color: #b91c1c; } .csh-tile.red .v { color: #b91c1c; }
    .csh-tile.amber .ic { background: #fffbeb; color: #b45309; } .csh-tile.amber .v { color: #b45309; }
    .csh-tile.green .ic { background: #ecfdf5; color: #047857; } .csh-tile.green .v { color: #047857; }

    /* Ticket rail */
    .csh-tickets { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .csh-tickets-head { padding: 0.95rem 1.4rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .csh-tickets-head h3 { margin: 0; font-size: 0.92rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.55rem; }
    .csh-tickets-head h3 i { color: #0d9488; }
    .csh-ticket { display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; padding: 0.95rem 1.4rem; border-left: 3px solid transparent; transition: background .15s; }
    .csh-ticket + .csh-ticket { border-top: 1px solid #f1f5f9; }
    .csh-ticket:hover { background: #f8fafc; }
    .csh-ticket.ok { border-left-color: #14b8a6; }
    .csh-ticket.urg { border-left-color: #f59e0b; background: #fffdf8; }
    .csh-ticket.over { border-left-color: #dc2626; background: #fffbfb; }
    .csh-ticket-main { min-width: 0; }
    .csh-ticket-name { font-weight: 700; color: #0f172a; font-size: 0.9rem; }
    .csh-ticket-sub { font-size: 0.78rem; color: #64748b; margin-top: 0.15rem; }
    .csh-ticket-side { text-align: right; flex-shrink: 0; display: flex; flex-direction: column; align-items: flex-end; gap: 0.3rem; }
    .csh-amount-lg { font-size: 1.25rem; }

    /* Urgency chips */
    .csh-chip { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.22rem 0.6rem; border-radius: 99px; font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; }
    .csh-chip.amber { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .csh-chip.red { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* Buttons */
    .csh-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.42rem 0.95rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: opacity .15s, background .15s; height: 34px; }
    .csh-btn:hover { opacity: 0.88; color: #fff; }
    .csh-btn.emerald { background: #059669; color: #fff; }
</style>

<?php
    $urgentCount = $pendingCash->filter(fn ($r) => $r->payment_deadline->isFuture() && $r->payment_deadline->diffInHours(now()) < 6)->count();
    $weekCount   = $pendingCash->filter(fn ($r) => $r->payment_deadline->isFuture() && $r->payment_deadline->diffInHours(now()) >= 6)->count();
    $queueTotal  = $pendingCash->sum(fn ($r) => $r->payment_status === 'partially_paid' ? $r->remaining_balance : $r->total_price);
?>


<div class="csh-tape">
    <div class="csh-tape-brand">
        <p class="greet">Good <?php echo e(now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening')); ?>, <?php echo e(auth()->user()->first_name ?? auth()->user()->name); ?> <span class="wave">👋</span></p>
        <p class="csh-date"><i class="bi bi-calendar3 me-1"></i><?php echo e(now()->format('l, F j, Y')); ?> · Cashier station</p>
    </div>
    <div class="csh-tape-cells">
        <div class="csh-tape-cell">
            <div class="v hero">&#8369;<?php echo e(number_format($todayCollected, 2)); ?></div>
            <div class="l">Collected Today</div>
        </div>
        <div class="csh-tape-cell">
            <div class="v"><?php echo e($todayCount); ?></div>
            <div class="l">Confirmations</div>
        </div>
        <div class="csh-tape-cell">
            <div class="v"><?php echo e($totalPendingCount); ?></div>
            <div class="l">In Queue</div>
        </div>
        <div class="csh-tape-cell">
            <div class="v">&#8369;<?php echo e(number_format($queueTotal, 2)); ?></div>
            <div class="l">Awaiting Collection</div>
        </div>
    </div>
</div>


<div class="csh-tiles">
    <div class="csh-tile red">
        <div class="ic"><i class="bi bi-alarm-fill"></i></div>
        <div>
            <div class="v"><?php echo e($urgentCount); ?></div>
            <div class="l">Urgent &lt; 6 Hours</div>
        </div>
    </div>
    <div class="csh-tile amber">
        <div class="ic"><i class="bi bi-clock-history"></i></div>
        <div>
            <div class="v"><?php echo e($weekCount); ?></div>
            <div class="l">Due This Week</div>
        </div>
    </div>
    <div class="csh-tile green">
        <div class="ic"><i class="bi bi-piggy-bank-fill"></i></div>
        <div>
            <div class="v">&#8369;<?php echo e(number_format($todayCollected, 2)); ?></div>
            <div class="l">Today's Take</div>
        </div>
    </div>
</div>


<div class="csh-tickets">
    <div class="csh-tickets-head">
        <h3><i class="bi bi-ticket-perforated-fill"></i> Collection Tickets</h3>
        <div class="d-flex gap-2 align-items-center">
            <?php if($pendingCash->count() > 5): ?>
                <span style="font-size:0.78rem;color:#64748b;">Showing 5 of <?php echo e($totalPendingCount); ?></span>
            <?php endif; ?>
            <a href="<?php echo e(route('cashier.payments.index')); ?>" class="csh-btn emerald"><i class="bi bi-list-ul"></i> Full Queue</a>
        </div>
    </div>
    <?php $__empty_1 = true; $__currentLoopData = $pendingCash; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $isOverdue = $r->payment_deadline->isPast();
            $isUrgent  = !$isOverdue && $r->payment_deadline->diffInHours(now()) < 6;
            $due       = $r->payment_status === 'partially_paid' ? $r->remaining_balance : $r->total_price;
        ?>
        <div class="csh-ticket <?php echo e($isOverdue ? 'over' : ($isUrgent ? 'urg' : 'ok')); ?>">
            <div class="csh-ticket-main">
                <div class="csh-ref"><span class="csh-ref-hash">#</span><?php echo e($r->booking_reference); ?></div>
                <div class="csh-ticket-name"><?php echo e($r->user->name ?? 'N/A'); ?></div>
                <div class="csh-ticket-sub"><?php echo e($r->facility->name ?? 'N/A'); ?> · <?php echo e(\Carbon\Carbon::parse($r->start_date)->format('M d, Y')); ?> · <?php echo e(\Carbon\Carbon::parse($r->start_time)->format('g:i A')); ?></div>
            </div>
            <div class="csh-ticket-side">
                <div class="csh-amount csh-amount-lg">&#8369;<?php echo e(number_format($due, 2)); ?></div>
                <?php if($isOverdue): ?>
                    <span class="csh-chip red"><i class="bi bi-exclamation-triangle-fill"></i> Overdue</span>
                <?php elseif($isUrgent): ?>
                    <span class="csh-chip amber"><i class="bi bi-alarm"></i> <?php echo e($r->payment_deadline->diffForHumans()); ?></span>
                <?php else: ?>
                    <span class="csh-chip amber">Due <?php echo e($r->payment_deadline->diffForHumans()); ?></span>
                <?php endif; ?>
                <a href="<?php echo e(route('cashier.payments.show', $r)); ?>" class="csh-btn emerald" style="margin-top:0.15rem;">Process <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="panel-empty">
            <i class="bi bi-ticket-perforated"></i>
            <h4>Drawer is empty</h4>
            <p>No pending cash payments right now. New tickets will appear here as guests queue for on-site payment.</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cashier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/cashier/dashboard.blade.php ENDPATH**/ ?>