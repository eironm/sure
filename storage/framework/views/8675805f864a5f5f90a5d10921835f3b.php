<?php $__env->startSection('content'); ?>
<?php echo $__env->make('components.custom-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
/* ── SURE Guest Dashboard — matches homepage design language ── */
.gd-root {
    background: #f4f6fb;
    min-height: calc(100vh - 140px);
}
.gd-wrap { max-width: 1240px; margin: 0 auto; padding: 1.75rem 1.5rem 3rem; }

/* Hero */
.gd-hero {
    background: linear-gradient(120deg, var(--brand-dark, #0f172a) 0%, #1e293b 62%, rgba(var(--brand-primary-rgb,37,99,235),0.55) 100%);
    border-radius: 18px;
    padding: 2.1rem 2.25rem;
    color: #fff;
    display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
    flex-wrap: wrap;
    box-shadow: 0 14px 34px rgba(15,23,42,.22);
    position: relative; overflow: hidden;
}
.gd-hero::after {
    content: "";
    position: absolute; right: -60px; top: -60px;
    width: 240px; height: 240px; border-radius: 50%;
    background: rgba(37,99,235,.22);
    filter: blur(40px);
}
.gd-hero h1 {
    font-family: 'Space Grotesk','Inter',sans-serif;
    font-size: 1.75rem; font-weight: 700; margin: 0 0 .35rem;
    letter-spacing: -.02em; color:#fff;
}
.gd-hero p { margin:0; color: rgba(255,255,255,.72); font-size:.92rem; }
.gd-hero-actions { display:flex; gap:.75rem; align-items:center; flex-wrap:wrap; position:relative; z-index:1; }
.gd-btn {
    display:inline-flex; align-items:center; gap:.45rem;
    font-weight:600; font-size:.88rem;
    padding:.62rem 1.2rem; border-radius:10px;
    text-decoration:none; white-space:nowrap; cursor:pointer;
    border:1.5px solid transparent; transition: all .18s ease;
}
.gd-btn-primary { background:var(--brand-primary, #2563eb); color:#fff; border-color:var(--brand-primary, #2563eb); }
.gd-btn-primary:hover { background:var(--brand-primary-dark, #1d4ed8); color:#fff; transform:translateY(-1px); box-shadow:0 8px 20px rgba(var(--brand-primary-rgb,37,99,235),.4); }
.gd-btn-ghost { background:rgba(255,255,255,.14); color:#fff; border-color:rgba(255,255,255,.35); }
.gd-btn-ghost:hover { background:rgba(255,255,255,.24); color:#fff; }

/* KPI band */
.gd-kpis { display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem; margin-top:1.4rem; }
@media (max-width: 900px){ .gd-kpis { grid-template-columns:repeat(2,1fr);} }
.gd-kpi {
    background:#fff; border:1px solid #e5e9f2; border-radius:14px;
    padding:1.05rem 1.2rem; display:flex; align-items:center; gap:.9rem;
    box-shadow: 0 2px 8px rgba(15,23,42,.04);
    transition: transform .18s ease, box-shadow .18s ease;
}
.gd-kpi:hover { transform:translateY(-2px); box-shadow: 0 6px 18px rgba(15,23,42,.09); }
.gd-kpi-icon {
    width:2.6rem; height:2.6rem; border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0;
}
.gd-kpi-value { font-family:'Space Grotesk',sans-serif; font-size:1.45rem; font-weight:700; color:#0f172a; line-height:1.1; }
.gd-kpi-label { font-size:.72rem; font-weight:600; letter-spacing:.06em; text-transform:uppercase; color:#64748b; margin-top:.2rem; }

/* Layout */
.gd-grid { display:grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap:1.4rem; margin-top:1.4rem; align-items:start; }
@media (max-width: 980px){ .gd-grid { grid-template-columns: 1fr; } }

.gd-card {
    background:#fff; border:1px solid #e5e9f2; border-radius:16px;
    box-shadow: 0 2px 8px rgba(15,23,42,.04);
    overflow:hidden;
}
.gd-card-head {
    display:flex; align-items:center; justify-content:space-between; gap:.75rem;
    padding:1.1rem 1.35rem; border-bottom:1px solid #eef1f7;
}
.gd-card-head h2 {
    font-family:'Space Grotesk',sans-serif; font-size:1.05rem; font-weight:700;
    margin:0; color:#0f172a; display:flex; align-items:center; gap:.5rem;
}
.gd-card-body { padding:1.2rem 1.35rem; }

/* Payment banner */
.gd-payment-banner { border-radius:16px; padding:1.25rem 1.4rem; display:flex; align-items:flex-start; gap:1.1rem; flex-wrap:wrap; }
.gd-payment-banner .icon-circle {
    width:2.9rem; height:2.9rem; border-radius:50%;
    display:flex; align-items:center; justify-content:center; font-size:1.35rem; flex-shrink:0;
}
.gd-payment-banner .pb-title { font-weight:700; font-size:.98rem; margin-bottom:.3rem; }
.gd-payment-banner .pb-text { font-size:.88rem; line-height:1.55; }
.gd-payment-actions { display:flex; gap:.65rem; flex-wrap:wrap; align-items:center; flex-shrink:0; }
.gd-btn-outline { background:#fff; color:#374151; border-color:#d1d5db; }
.gd-btn-outline:hover { background:#f3f4f6; color:#111827; }
.gd-btn-danger-soft { background:#fff; color:#dc2626; border-color:#fca5a5; }
.gd-btn-danger-soft:hover { background:#fef2f2; }
.gd-btn-sm { padding:.5rem 1rem; font-size:.83rem; }
form.gd-inline { margin:0; }

/* Status tracker */
.gd-tracker-head { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; }
.gd-tracker-facility { font-weight:700; font-size:1.05rem; color:#0f172a; }
.gd-tracker-time { font-size:.86rem; color:#64748b; margin-top:.25rem; }
.gd-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    font-size:.72rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase;
    padding:.3rem .7rem; border-radius:999px; white-space:nowrap;
}
.gd-badge-pending { background:#fef3c7; color:#92400e; }
.gd-badge-approved { background:#dcfce7; color:#166534; }
.gd-badge-default { background:#e5e7eb; color:#374151; }
.gd-timeline { position:relative; margin-top:1.6rem; }
.gd-timeline-progress {
    position:absolute; top:1.1rem; left:0; height:3px; border-radius:3px;
    background:#2563eb; z-index:0;
}
.gd-timeline-steps { display:flex; justify-content:space-between; position:relative; z-index:1; }
.gd-step { display:flex; flex-direction:column; align-items:center; gap:.5rem; width:33%; }
.gd-step-icon {
    width:2.4rem; height:2.4rem; border-radius:50%;
    background:#eef1f7; color:#94a3b8; display:flex; align-items:center; justify-content:center;
    font-size:1rem; border:2px solid #fff; box-shadow:0 0 0 2px #e5e9f2;
}
.gd-step.completed .gd-step-icon { background:#2563eb; color:#fff; box-shadow:0 0 0 2px #2563eb33; }
.gd-step.active .gd-step-icon { background:#fff; color:#2563eb; box-shadow:0 0 0 2px #2563eb; animation: gd-pulse 2.4s infinite; }
.gd-step-label { font-size:.74rem; font-weight:600; color:#64748b; text-align:center; }
.gd-step.completed .gd-step-label { color:#0f172a; }
.gd-step.active .gd-step-label { color:#2563eb; font-weight:700; }
@keyframes gd-pulse {
    0%,100% { box-shadow:0 0 0 2px #2563eb; }
    50% { box-shadow:0 0 0 6px rgba(37,99,235,.14); }
}

/* Reservation list */
.gd-res-row {
    display:flex; align-items:center; gap:1rem;
    padding:.95rem 0; border-bottom:1px solid #eef1f7;
}
.gd-res-row:last-child { border-bottom:none; }
.gd-res-icon {
    width:2.6rem; height:2.6rem; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.05rem;
    background:rgba(37,99,235,.08); color:#2563eb;
}
.gd-res-meta { flex:1; min-width:0; }
.gd-res-facility { font-weight:700; font-size:.92rem; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.gd-res-date { font-size:.8rem; color:#64748b; margin-top:.15rem; }
.gd-res-when {
    text-align:right; flex-shrink:0;
    font-size:.8rem; font-weight:600; color:#334155;
}
.gd-empty {
    text-align:center; padding:2rem 1rem; color:#64748b;
}
.gd-empty i { font-size:1.8rem; color:#c7cfdc; display:block; margin-bottom:.6rem; }

/* Pencil holds */
.gd-pencil-item {
    display:flex; align-items:center; gap:.9rem; padding:.85rem 0;
    border-bottom:1px solid #eef1f7;
}
.gd-pencil-item:last-child { border-bottom:none; }
.gd-pencil-info { flex:1; min-width:0; }
.gd-pencil-facility { font-weight:700; font-size:.9rem; color:#0f172a; }
.gd-pencil-date { font-size:.78rem; color:#d97706; margin-top:.15rem; font-weight:600; }
.gd-pencil-cancel { flex-shrink:0; }
.gd-btn-soft-danger { background:#fef2f2; color:#dc2626; border:1.5px solid #fecaca; }
.gd-btn-soft-danger:hover { background:#fee2e2; }
.gd-expire-note { font-size:.78rem; color:#94a3b8; margin-top:.9rem; }

/* Quick actions */
.gd-actions { display:grid; grid-template-columns:repeat(4, 1fr); gap:1rem; }
@media (max-width: 760px){ .gd-actions { grid-template-columns:repeat(2,1fr); } }
.gd-action {
    display:flex; flex-direction:column; gap:.7rem;
    background:#fff; border:1px solid #e5e9f2; border-radius:16px;
    padding:1.2rem; text-decoration:none; color:#0f172a;
    box-shadow:0 2px 8px rgba(15,23,42,.04);
    transition: all .18s ease;
}
.gd-action:hover { transform:translateY(-3px); box-shadow:0 10px 24px rgba(15,23,42,.1); border-color:#c9d4e8; }
.gd-action-icon {
    width:2.8rem; height:2.8rem; border-radius:12px;
    display:flex; align-items:center; justify-content:center; font-size:1.15rem;
}
.gd-action h3 { font-size:.95rem; font-weight:700; margin:0; }
.gd-action p { font-size:.78rem; color:#64748b; margin:0; line-height:1.45; }
.gd-action.disabled { opacity:.55; cursor:not-allowed; }

/* Notifications */
.gd-notif-item {
    display:flex; gap:.8rem; padding:.85rem 0;
    border-bottom:1px solid #eef1f7;
}
.gd-notif-item:last-child { border-bottom:none; }
.gd-notif-icon {
    width:2rem; height:2rem; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:.85rem;
}
.gd-notif-content { flex:1; min-width:0; }
.gd-notif-title { font-size:.84rem; font-weight:700; color:#0f172a; }
.gd-notif-desc { font-size:.8rem; color:#64748b; margin-top:.1rem; line-height:1.4; }
.gd-notif-time { font-size:.7rem; color:#94a3b8; margin-top:.25rem; }
.gd-notif-dot { width:.5rem; height:.5rem; border-radius:50%; background:#2563eb; flex-shrink:0; margin-top:.7rem; }

/* Profile card */
.gd-profile-card .pc-row {
    display:flex; gap:.7rem; padding:.62rem 0; font-size:.85rem; color:#334155;
    border-bottom:1px solid #eef1f7;
}
.gd-profile-card .pc-row:last-child { border-bottom:none; }
.gd-profile-card .pc-label { width:7rem; flex-shrink:0; color:#64748b; font-weight:500; }
.gd-profile-card .pc-value { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-weight:600; color:#0f172a; }
.gd-link { color:#2563eb; font-weight:600; font-size:.82rem; text-decoration:none; }
.gd-link:hover { text-decoration:underline; }

/* Sidebar stacking */
.gd-sidebar-stack { display:flex; flex-direction:column; gap:1.4rem; }
</style>

<div class="gd-root">
<div class="gd-wrap">

    
    <div class="gd-hero">
        <div>
            <h1><span id="gd-greeting">Good morning</span>, <?php echo e($user->first_name ?? explode(' ', $user->name)[0]); ?>!</h1>
            <p><?php echo e(now()->format('l, F j, Y')); ?> &mdash; here's where your reservations stand.</p>
        </div>
        <div class="gd-hero-actions">
            <a href="<?php echo e(route('guest.reserve')); ?>" class="gd-btn gd-btn-primary">
                <i class="bi bi-calendar-plus"></i> New Reservation
            </a>
        </div>
    </div>

    
    <div class="gd-kpis">
        <div class="gd-kpi">
            <div class="gd-kpi-icon" style="background:rgba(37,99,235,.1); color:#2563eb;"><i class="bi bi-collection"></i></div>
            <div>
                <div class="gd-kpi-value"><?php echo e($statTotal); ?></div>
                <div class="gd-kpi-label">Total Bookings</div>
            </div>
        </div>
        <div class="gd-kpi">
            <div class="gd-kpi-icon" style="background:rgba(217,119,6,.1); color:#d97706;"><i class="bi bi-calendar-week"></i></div>
            <div>
                <div class="gd-kpi-value"><?php echo e($statUpcoming); ?></div>
                <div class="gd-kpi-label">Upcoming</div>
            </div>
        </div>
        <div class="gd-kpi">
            <div class="gd-kpi-icon" style="background:rgba(22,163,74,.1); color:#16a34a;"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="gd-kpi-value"><?php echo e($statCompleted); ?></div>
                <div class="gd-kpi-label">Completed</div>
            </div>
        </div>
        <div class="gd-kpi">
            <div class="gd-kpi-icon" style="background:rgba(124,58,237,.1); color:#7c3aed;"><i class="bi bi-cash-stack"></i></div>
            <div>
                <div class="gd-kpi-value">&#8369;<?php echo e(number_format((float)$statSpent, 0)); ?></div>
                <div class="gd-kpi-label">Total Paid</div>
            </div>
        </div>
    </div>

    <div class="gd-grid">
    
    <div style="display:flex; flex-direction:column; gap:1.4rem;">

        
        <?php if($pendingPayment): ?>
        <?php
            $isCash    = ($pendingPayment->payment_method ?? 'paymongo') === 'cash';
            $isPartial = ($pendingPayment->payment_status ?? '') === 'partially_paid';
            $deadline  = $pendingPayment->deadlineForHumans();
            if ($isPartial) {
                $bannerBg='#eff6ff'; $bannerBdr='#3b82f6'; $iconBg='#dbeafe'; $iconColor='#1d4ed8'; $icon='bi-hourglass-split';
                $titleColor='#1e40af'; $textColor='#1e3a8a';
            } elseif ($isCash) {
                $bannerBg='#fffbeb'; $bannerBdr='#f59e0b'; $iconBg='#fef3c7'; $iconColor='#d97706'; $icon='bi-cash-coin';
                $titleColor='#92400e'; $textColor='#78350f';
            } else {
                $bannerBg='#eff6ff'; $bannerBdr='#3b82f6'; $iconBg='#dbeafe'; $iconColor='#1d4ed8'; $icon='bi-credit-card-2-front';
                $titleColor='#1e40af'; $textColor='#1e3a8a';
            }
        ?>
        <div class="gd-payment-banner" style="background:<?php echo e($bannerBg); ?>; border:1.5px solid <?php echo e($bannerBdr); ?>;">
            <div class="icon-circle" style="background:<?php echo e($iconBg); ?>; color:<?php echo e($iconColor); ?>;">
                <i class="bi <?php echo e($icon); ?>"></i>
            </div>
            <div style="flex:1; min-width:220px;">
                <?php if($isPartial): ?>
                    <div class="pb-title" style="color:<?php echo e($titleColor); ?>;"><i class="bi bi-info-circle-fill me-1"></i>Deposit Paid, Balance Due — Ref: #<?php echo e($pendingPayment->booking_reference); ?></div>
                    <div class="pb-text" style="color:<?php echo e($textColor); ?>">
                        You have paid a deposit of <strong>&#8369;<?php echo e(number_format((float)$pendingPayment->amount_paid, 2)); ?></strong>.
                        Pay the remaining <strong>&#8369;<?php echo e(number_format((float)$pendingPayment->remaining_balance, 2)); ?></strong>
                        by <strong><?php echo e($deadline); ?></strong> (7 days before the event) or your booking will be cancelled <strong>without refund</strong>.
                    </div>
                <?php elseif($isCash): ?>
                    <div class="pb-title" style="color:<?php echo e($titleColor); ?>;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Cash Payment Pending — Ref: #<?php echo e($pendingPayment->booking_reference); ?></div>
                    <div class="pb-text" style="color:<?php echo e($textColor); ?>">
                        Bring your payment slip to the cashier and pay <strong>&#8369;<?php echo e(number_format((float)$pendingPayment->total_price, 2)); ?></strong>
                        by <strong><?php echo e($deadline); ?></strong> or your booking will be automatically cancelled.
                    </div>
                <?php else: ?>
                    <div class="pb-title" style="color:<?php echo e($titleColor); ?>;"><i class="bi bi-exclamation-circle-fill me-1"></i>Payment Incomplete — Ref: #<?php echo e($pendingPayment->booking_reference); ?></div>
                    <div class="pb-text" style="color:<?php echo e($textColor); ?>">
                        You didn't complete your digital payment. Finish it by <strong><?php echo e($deadline); ?></strong>
                        or your booking will be automatically cancelled.
                    </div>
                <?php endif; ?>
            </div>
            <div class="gd-payment-actions">
                <a href="<?php echo e(route('guest.reservation.slip', $pendingPayment)); ?>" class="gd-btn gd-btn-outline gd-btn-sm"><i class="bi bi-printer"></i> Print Slip</a>
                <?php if(!$isCash && !$isPartial): ?>
                <form class="gd-inline" method="POST" action="<?php echo e(route('guest.reservation.resume-payment', $pendingPayment)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="gd-btn gd-btn-primary gd-btn-sm"><i class="bi bi-credit-card"></i> Complete Payment</button>
                </form>
                <form class="gd-inline" method="POST" action="<?php echo e(route('guest.reservation.switch-cash', $pendingPayment)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="gd-btn gd-btn-outline gd-btn-sm"><i class="bi bi-cash"></i> Switch to Cash</button>
                </form>
                <?php endif; ?>
                <form class="gd-inline" method="POST" action="<?php echo e(route('guest.reservation.cancel-payment', $pendingPayment)); ?>"
                      data-ref="<?php echo e($pendingPayment->booking_reference); ?>"
                      data-facility="<?php echo e($pendingPayment->facility->name ?? 'Facility Reservation'); ?>"
                      data-partial="<?php echo e($isPartial ? '1' : '0'); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="button" class="gd-btn gd-btn-danger-soft gd-btn-sm" data-cancel-booking="true"
                            data-ref="<?php echo e($pendingPayment->booking_reference); ?>"
                            data-facility="<?php echo e($pendingPayment->facility->name ?? 'Facility Reservation'); ?>"
                            data-partial="<?php echo e($isPartial ? '1' : '0'); ?>">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="gd-card">
            <div class="gd-card-body">
                <?php if($activeReservation): ?>
                <div class="gd-tracker-head">
                    <div>
                        <div class="gd-tracker-facility"><i class="bi bi-geo-alt-fill" style="color:#2563eb;"></i> <?php echo e($activeReservation->facility->name ?? 'Facility Reservation'); ?></div>
                        <div class="gd-tracker-time">
                            <i class="bi bi-calendar3"></i>
                            <?php echo e(\Carbon\Carbon::parse($activeReservation->start_date)->format('M d, Y')); ?> &bull;
                            <?php echo e(\Carbon\Carbon::parse($activeReservation->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($activeReservation->end_time)->format('g:i A')); ?>

                        </div>
                    </div>
                    <span class="gd-badge gd-badge-<?php echo e($activeReservation->status); ?>"><?php echo e(ucfirst($activeReservation->status)); ?></span>
                </div>
                <?php
                    $isPending = $activeReservation->status === 'pending';
                    $isApproved = $activeReservation->status === 'approved';
                    $progressWidth = '15%';
                    if ($isPending) $progressWidth = '50%';
                    if ($isApproved) $progressWidth = '100%';
                ?>
                <div class="gd-timeline">
                    <div class="gd-timeline-progress" style="width: <?php echo e($progressWidth); ?>;"></div>
                    <div class="gd-timeline-steps">
                        <div class="gd-step completed">
                            <div class="gd-step-icon"><i class="bi bi-check-lg"></i></div>
                            <div class="gd-step-label">Submitted</div>
                        </div>
                        <div class="gd-step <?php echo e($isPending ? 'active' : ($isApproved ? 'completed' : '')); ?>">
                            <div class="gd-step-icon">
                                <?php if($isApproved): ?><i class="bi bi-check-lg"></i>
                                <?php else: ?><i class="bi bi-hourglass-split"></i>
                                <?php endif; ?>
                            </div>
                            <div class="gd-step-label">Under Review</div>
                        </div>
                        <div class="gd-step <?php echo e($isApproved ? 'active' : ''); ?>">
                            <div class="gd-step-icon">
                                <?php if($isApproved): ?><i class="bi bi-check-lg"></i>
                                <?php else: ?><i class="bi bi-calendar2-check"></i>
                                <?php endif; ?>
                            </div>
                            <div class="gd-step-label">Approved</div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="gd-empty">
                    <i class="bi bi-check2-circle"></i>
                    <h4 style="font-size:1rem; font-weight:700; color:#0f172a; margin-bottom:.25rem;">You're all caught up!</h4>
                    <p style="font-size:.85rem; margin:0;">No active reservations right now — plan your next event below.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="gd-card">
            <div class="gd-card-head">
                <h2><i class="bi bi-calendar-event" style="color:#2563eb;"></i> Upcoming Schedule</h2>
                <a href="<?php echo e(route('guest.history')); ?>" class="gd-link">View all</a>
            </div>
            <div class="gd-card-body" style="padding:0 1.35rem .4rem;">
                <?php if($upcomingReservations->count()): ?>
                    <?php $__currentLoopData = $upcomingReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="gd-res-row">
                        <div class="gd-res-icon"><i class="bi bi-house-door"></i></div>
                        <div class="gd-res-meta">
                            <div class="gd-res-facility"><?php echo e($r->facility->name ?? 'Facility'); ?></div>
                            <div class="gd-res-date"><?php echo e(\Carbon\Carbon::parse($r->start_date)->format('M d, Y')); ?> &bull; <?php echo e(\Carbon\Carbon::parse($r->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($r->end_time)->format('g:i A')); ?></div>
                        </div>
                        <span class="gd-badge gd-badge-<?php echo e($r->status); ?>" style="margin-right:.7rem;"><?php echo e(ucfirst($r->status)); ?></span>
                        <a href="<?php echo e(route('guest.reservation.slip', $r)); ?>" class="gd-btn gd-btn-outline gd-btn-sm" title="View slip"><i class="bi bi-receipt"></i></a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="gd-empty">
                        <i class="bi bi-calendar-x"></i>
                        <h4 style="font-size:.95rem; font-weight:700; color:#0f172a; margin-bottom:.25rem;">Nothing on the calendar</h4>
                        <p style="font-size:.82rem; margin:0;">Your upcoming reservations will appear here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($myPencils->count()): ?>
        <div class="gd-card">
            <div class="gd-card-head">
                <h2><i class="bi bi-pencil-square" style="color:#d97706;"></i> Pencil Holds</h2>
                <a href="<?php echo e(route('guest.facility-board')); ?>" class="gd-link">Facility board</a>
            </div>
            <div class="gd-card-body" style="padding:0 1.35rem .4rem;">
                <?php $__currentLoopData = $myPencils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="gd-pencil-item">
                    <div class="gd-res-icon" style="background:rgba(217,119,6,.1); color:#d97706;"><i class="bi bi-pencil"></i></div>
                    <div class="gd-pencil-info">
                        <div class="gd-pencil-facility"><?php echo e($p->facility?->name ?? 'Facility'); ?></div>
                        <div class="gd-pencil-date"><i class="bi bi-clock"></i> <?php echo e($p->date->format('M d, Y')); ?> &mdash; hold expires <?php echo e($p->expires_at->diffForHumans(now())); ?></div>
                    </div>
                    <a href="<?php echo e(route('guest.reserve')); ?>?facility=<?php echo e($p->facility_id); ?>&date=<?php echo e($p->date->format('Y-m-d')); ?>" class="gd-btn gd-btn-soft" style="background:rgba(37,99,235,.08); color:#2563eb; margin-right:.4rem;"><i class="bi bi-arrow-up-circle"></i> Reserve</a>
                    <form class="gd-inline gd-pencil-cancel" method="POST" action="<?php echo e(route('guest.facility-board.pencil-cancel', $p)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="gd-btn gd-btn-soft-danger gd-btn-sm" onclick="return confirm('Remove this pencil hold?')">Remove</button>
                    </form>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="gd-expire-note">Pencil holds are tentative and expire automatically after <?php echo e(\App\Models\PencilBooking::HOLD_DAYS); ?> days. Convert them into a real reservation before they lapse.</div>
            </div>
        </div>
        <?php endif; ?>

        
        <div>
            <div class="gd-actions">
                <?php if($activeReservation && !$allowMultipleAdvanceReservations): ?>
                <div class="gd-action disabled" title="You have an active reservation.">
                    <div class="gd-action-icon" style="background:rgba(100,116,139,.1); color:#64748b;"><i class="bi bi-plus-lg"></i></div>
                    <h3>Book Facility</h3>
                    <p>Complete your active reservation before booking again.</p>
                </div>
                <?php else: ?>
                <a href="<?php echo e(route('guest.reserve')); ?>" class="gd-action">
                    <div class="gd-action-icon" style="background:rgba(37,99,235,.1); color:#2563eb;"><i class="bi bi-calendar-plus"></i></div>
                    <h3>Book Facility</h3>
                    <p>Schedule your next event in our premium spaces.</p>
                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('guest.history')); ?>" class="gd-action">
                    <div class="gd-action-icon" style="background:rgba(124,58,237,.1); color:#7c3aed;"><i class="bi bi-clock-history"></i></div>
                    <h3>My Requests</h3>
                    <p>Track and view your past and present bookings.</p>
                </a>
                <a href="<?php echo e(route('guest.facility-board')); ?>" class="gd-action">
                    <div class="gd-action-icon" style="background:rgba(6,182,212,.1); color:#06b6d4;"><i class="bi bi-grid-3x3-gap"></i></div>
                    <h3>Facility Board</h3>
                    <p>See availability at a glance and place pencil holds.</p>
                </a>
                <a href="<?php echo e(route('guest.facilities')); ?>" class="gd-action">
                    <div class="gd-action-icon" style="background:rgba(22,163,74,.1); color:#16a34a;"><i class="bi bi-map"></i></div>
                    <h3>Campus Map</h3>
                    <p>Explore available facilities and their details.</p>
                </a>
            </div>
            <div class="gd-actions" style="margin-top:1rem;">
                <a href="<?php echo e(route('guest.history')); ?>?tab=past" class="gd-action" style="grid-column: span 2;">
                    <div class="gd-action-icon" style="background:rgba(217,119,6,.1); color:#d97706;"><i class="bi bi-archive"></i></div>
                    <h3>Booking History</h3>
                    <p>Review completed and cancelled reservations.</p>
                </a>
                <a href="<?php echo e(route('profile.edit')); ?>" class="gd-action" style="grid-column: span 2;">
                    <div class="gd-action-icon" style="background:rgba(15,23,42,.08); color:#0f172a;"><i class="bi bi-sliders"></i></div>
                    <h3>Account Settings</h3>
                    <p>Update your personal preferences securely.</p>
                </a>
            </div>
        </div>
    </div>

    
    <div class="gd-sidebar-stack">

        
        <div class="gd-card">
            <div class="gd-card-head">
                <h2><i class="bi bi-bell" style="color:#d97706;"></i> What You Missed</h2>
                <?php if($notifications->count()): ?>
                <form class="gd-inline" method="POST" action="<?php echo e(route('notifications.markAllAsRead')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="gd-link" style="background:none; border:none; padding:0; cursor:pointer;">Mark all read</button>
                </form>
                <?php endif; ?>
            </div>
            <div class="gd-card-body" style="padding:0 1.35rem .4rem;">
                <?php if($notifications->count()): ?>
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $type = 'info'; $icon = 'bi-info-circle';
                        $notifData = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                        $notifData = $notifData ?: [];
                        if (isset($notifData['status'])) {
                            if ($notifData['status'] === 'approved')       { $type = 'success'; $icon = 'bi-check-circle'; }
                            elseif (in_array($notifData['status'], ['denied','rejected','cancelled'])) { $type = 'danger'; $icon = 'bi-x-circle'; }
                            elseif ($notifData['status'] === 'pending')    { $type = 'warning'; $icon = 'bi-clock-history'; }
                        } elseif (str_contains(strtolower($notification->type), 'approved'))  { $type = 'success'; $icon = 'bi-check-circle'; }
                          elseif (str_contains(strtolower($notification->type), 'denied') || str_contains(strtolower($notification->type), 'cancelled')) { $type = 'danger'; $icon = 'bi-x-circle'; }
                        $iconBg = match($type) {
                            'success' => 'rgba(22,163,74,.1)',
                            'danger'  => 'rgba(220,38,38,.1)',
                            'warning' => 'rgba(217,119,6,.1)',
                            default   => 'rgba(37,99,235,.1)',
                        };
                        $iconColor = match($type) {
                            'success' => '#16a34a',
                            'danger'  => '#dc2626',
                            'warning' => '#d97706',
                            default   => '#2563eb',
                        };
                        $title = $notifData['title'] ?? 'Notification Update';
                        $message = $notifData['message'] ?? 'You have a new update regarding your reservation.';
                    ?>
                    <?php ($notificationDestination = $notifData['action_url'] ?? route('guest.dashboard')); ?>
                    <a href="<?php echo e($notificationDestination); ?>"
                       data-gd-notification
                       data-mark-read-url="<?php echo e(route('notifications.markAsRead', $notification->id)); ?>"
                       style="text-decoration:none;">
                    <div class="gd-notif-item <?php echo e($notification->read_at ? '' : ''); ?>">
                        <div class="gd-notif-icon" style="background:<?php echo e($iconBg); ?>; color:<?php echo e($iconColor); ?>;"><i class="bi <?php echo e($icon); ?>"></i></div>
                        <div class="gd-notif-content">
                            <div class="gd-notif-title"><?php echo e($title); ?></div>
                            <div class="gd-notif-desc"><?php echo e(Str::limit($message, 70)); ?></div>
                            <div class="gd-notif-time"><?php echo e($notification->created_at->diffForHumans()); ?></div>
                        </div>
                        <?php if(!$notification->read_at): ?><div class="gd-notif-dot"></div><?php endif; ?>
                    </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="gd-empty">
                        <i class="bi bi-bell-slash"></i>
                        <h4 style="font-size:.95rem; font-weight:700; color:#0f172a; margin-bottom:.25rem;">No updates yet</h4>
                        <p style="font-size:.82rem; margin:0;">Your recent notifications will appear here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="gd-card gd-profile-card">
            <div class="gd-card-head">
                <h2><i class="bi bi-person-vcard" style="color:#7c3aed;"></i> Your Profile</h2>
                <a href="<?php echo e(route('profile.edit')); ?>" class="gd-link">Edit</a>
            </div>
            <div class="gd-card-body">
                <div class="pc-row"><div class="pc-label">Name</div><div class="pc-value"><?php echo e($user->first_name); ?> <?php echo e($user->last_name ?? ''); ?></div></div>
                <div class="pc-row"><div class="pc-label">Email</div><div class="pc-value"><?php echo e($user->email); ?></div></div>
                <div class="pc-row"><div class="pc-label">Role</div><div class="pc-value"><?php echo e(ucfirst($user->role ?? 'Guest')); ?></div></div>
                <?php if($user->client_type ?? null): ?>
                <div class="pc-row"><div class="pc-label">Client Type</div><div class="pc-value"><?php echo e(ucfirst($user->client_type)); ?></div></div>
                <?php endif; ?>
                <?php if(($user->affiliation_type ?? null) && ($user->college_department ?? null)): ?>
                <div class="pc-row"><div class="pc-label">Affiliation</div><div class="pc-value"><?php echo e(ucfirst($user->affiliation_type)); ?> &middot; <?php echo e($user->college_department); ?></div></div>
                <?php endif; ?>
                <?php if($user->contact_number ?? null): ?>
                <div class="pc-row"><div class="pc-label">Contact</div><div class="pc-value"><?php echo e($user->contact_number); ?></div></div>
                <?php endif; ?>
                <?php if($user->organization_name ?? null): ?>
                <div class="pc-row"><div class="pc-label">Organization</div><div class="pc-value"><?php echo e($user->organization_name); ?></div></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>

</div>
</div>

<script>
(function syncGdGreeting() {
    function update() {
        const el = document.getElementById('gd-greeting');
        if (!el) return;
        const hour = new Date().getHours();
        el.textContent = hour < 12 ? 'Good morning' : (hour < 17 ? 'Good afternoon' : 'Good evening');
    }
    document.addEventListener('DOMContentLoaded', function () {
        update();
        setInterval(update, 60000);
    });
})();
</script>

<?php if(session('success') && str_contains(session('success'), 'Reservation submitted')): ?>
<?php $__env->startPush('scripts'); ?>
<script>
    // A fresh booking just completed on this dashboard — purge any wizard
    // draft so a stale form never reappears on the next wizard visit.
    try {
        const uid = <?php echo e(auth()->id() ?? 0); ?>;
        localStorage.removeItem('wizard_draft_' + uid);
        localStorage.removeItem('wizard_draft_anon');
    } catch (e) { /* storage unavailable — ignore */ }
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-gd-notification]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            if (link.dataset.read === '1') return;

            event.preventDefault();
            const destination = link.href;
            const csrf = document.querySelector('meta[name="csrf-token"]');

            fetch(link.dataset.markReadUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf.content } : {}),
                },
            }).catch(function () {
                // Navigation should still work if the read-state request fails.
            }).finally(function () {
                link.dataset.read = '1';
                window.location.assign(destination);
            });
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/guest/dashboard.blade.php ENDPATH**/ ?>