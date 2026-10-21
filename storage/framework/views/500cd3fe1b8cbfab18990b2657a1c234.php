<?php $__env->startSection('content'); ?>
<div class="dashboard-wrapper">
    <div class="dashboard-container">
        <div class="welcome-header">
            <h1 class="welcome-title">Reservation Confirmation</h1>
            <p class="welcome-subtitle">Reference Number: <strong><?php echo e($reservation->booking_reference); ?></strong></p>
        </div>

        <div class="dashboard-layout">
            <!-- Main Content -->
            <div class="main-content">
                <div class="ent-card status-tracker-card mb-4">
                    <div class="status-header">
                        <div class="status-title-wrap">
                            <h2 class="status-facility"><?php echo e($reservation->event_title ?? $reservation->facility->name); ?></h2>
                            <div class="status-time" style="font-weight:600;color:var(--brand-primary, #1e3a8a);">
                                <i class="bi bi-geo-alt-fill"></i> <?php echo e($reservation->facility->name); ?>

                            </div>
                            <div class="status-time mt-1">
                                <i class="bi bi-calendar3"></i> 
                                <?php echo e($reservation->start_date->format('M d, Y')); ?> <?php if($reservation->start_date != $reservation->end_date): ?> - <?php echo e($reservation->end_date->format('M d, Y')); ?> <?php endif; ?>
                            </div>
                            <div class="status-time mt-1">
                                <i class="bi bi-clock"></i> 
                                <?php echo e(\Carbon\Carbon::parse($reservation->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($reservation->end_time)->format('g:i A')); ?>

                            </div>
                        </div>
                        <div class="text-right status-badge-wrap">
                            <?php if($reservation->is_fee_waived): ?>
                                <span class="status-badge badge-approved">
                                    <i class="bi bi-check-circle-fill me-1"></i> FEES WAIVED
                                </span>
                            <?php elseif($reservation->payment_status === 'paid'): ?>
                                <span class="status-badge badge-approved">
                                    <i class="bi bi-check-circle-fill me-1"></i> PAID
                                </span>
                            <?php else: ?>
                                <span class="status-badge badge-pending">
                                    <i class="bi bi-hourglass-split me-1"></i> PAYMENT PENDING
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="py-4 border-t border-b border-gray-100 my-4">
                        <?php if($reservation->is_fee_waived): ?>
                            <div class="flex items-center text-green-600 mb-2">
                                <i class="bi bi-check-circle-fill fs-2 me-3"></i>
                                <div>
                                    <h3 class="font-bold text-lg">Reservation Submitted — No Payment Required</h3>
                                    <p class="text-sm text-gray-600">As an internal university user, all fees have been waived. Your reservation is pending admin review and approval.</p>
                                </div>
                            </div>
                        <?php elseif($reservation->payment_status === 'paid'): ?>
                            <div class="flex items-center text-green-600 mb-2">
                                <i class="bi bi-check-circle-fill fs-2 me-3"></i>
                                <div>
                                    <h3 class="font-bold text-lg">Payment Successful</h3>
                                    <p class="text-sm text-gray-600">Your payment has been confirmed. Your reservation is now being processed.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center text-amber-600 mb-2">
                                <i class="bi bi-exclamation-triangle-fill fs-2 me-3"></i>
                                <div>
                                    <h3 class="font-bold text-lg">Action Required: Payment Pending</h3>
                                    <p class="text-sm text-gray-600">
                                        <?php if($reservation->payment_method === 'cash'): ?>
                                            Please settle your payment at the cashier to secure your reservation.
                                        <?php else: ?>
                                            Your digital payment is currently being processed or was not completed.
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="bg-amber-50 p-4 rounded-lg mt-4 border border-amber-100">
                                <p class="text-amber-800 font-medium">
                                    <i class="bi bi-hourglass-bottom me-2"></i>
                                    Payment Deadline: <strong><?php echo e($reservation->deadlineForHumans()); ?></strong>
                                </p>
                                <p class="text-xs text-amber-700 mt-1">
                                    Failure to pay before this deadline will result in automatic cancellation of your reservation.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-6">
                        <a href="<?php echo e(route('guest.reservation.slip', $reservation)); ?>" target="_blank" class="btn-primary-ent">
                            <i class="bi bi-printer me-2"></i> Print Reservation Slip
                        </a>
                        <a href="<?php echo e(route('guest.reservation.rental-agreement', $reservation)); ?>" target="_blank" class="btn-secondary-ent">
                            <i class="bi bi-file-earmark-text me-2"></i> View Rental Agreement
                        </a>
                        <a href="<?php echo e(route('guest.dashboard')); ?>" class="btn-secondary-ent">
                            <i class="bi bi-house me-2"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

                <!-- Reservation Details Card -->
                <div class="ent-card mb-4">
                    <h3 class="ent-card-title mb-4">
                        <i class="bi bi-info-circle-fill text-primary"></i> Event & Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="min-width: 0;">
                        <div class="min-w-0" style="min-width: 0; overflow-wrap: anywhere; word-break: break-word;">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Event Title</label>
                            <p class="text-gray-700 mt-1 font-bold break-words" style="word-break: break-word; overflow-wrap: anywhere;"><?php echo e($reservation->event_title ?? 'N/A'); ?></p>
                        </div>
                        <div class="min-w-0" style="min-width: 0; overflow-wrap: anywhere; word-break: break-word;">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Purpose of Use</label>
                            <p class="text-gray-700 mt-1 break-words" style="word-break: break-word; overflow-wrap: anywhere; white-space: pre-wrap;"><?php echo e($reservation->reason); ?></p>
                        </div>
                        <div class="min-w-0" style="min-width: 0; overflow-wrap: anywhere; word-break: break-word;">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Reserved By</label>
                            <p class="text-gray-700 mt-1 break-words" style="word-break: break-word; overflow-wrap: anywhere;"><?php echo e($reservation->reserved_by_name ?? 'N/A'); ?></p>
                        </div>
                        <div class="min-w-0" style="min-width: 0; overflow-wrap: anywhere; word-break: break-word;">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contact</label>
                            <p class="text-gray-700 mt-1 break-words" style="word-break: break-word; overflow-wrap: anywhere;"><?php echo e($reservation->reserved_by_email ?? $reservation->user->email); ?> · <?php echo e($reservation->reserved_by_phone ?? 'N/A'); ?></p>
                        </div>
                        <div class="min-w-0" style="min-width: 0; overflow-wrap: anywhere; word-break: break-word;">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">End User</label>
                            <p class="text-gray-700 mt-1 break-words" style="word-break: break-word; overflow-wrap: anywhere;"><?php echo e($reservation->end_user_name ?? 'N/A'); ?> (<?php echo e(ucfirst($reservation->end_user_type ?? 'N/A')); ?>)</p>
                        </div>
                        <div class="min-w-0" style="min-width: 0; overflow-wrap: anywhere; word-break: break-word;">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Number of People</label>
                            <p class="text-gray-700 mt-1 break-words" style="word-break: break-word; overflow-wrap: anywhere;"><?php echo e($reservation->number_of_people); ?> persons</p>
                        </div>
                    </div>
                </div>

                <!-- Arrangements Card -->
                <?php
                    $confArrangements = is_string($reservation->arrangements ?? null)
                        ? (json_decode($reservation->arrangements, true) ?: [])
                        : ($reservation->arrangements ?? []);
                ?>
                <?php if(!empty($confArrangements) && count($confArrangements) > 0): ?>
                    <div class="ent-card mb-4">
                        <h3 class="ent-card-title mb-4">
                            <i class="bi bi-tools text-warning"></i> Logistical Requirements
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $confArrangements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <div>
                                        <span class="font-medium"><?php echo e($arr['name'] ?? 'Item'); ?></span>
                                        <span class="text-gray-500 text-sm ml-2">× <?php echo e($arr['quantity'] ?? 1); ?></span>
                                        <?php if(($arr['subtotal'] ?? 0) > 0): ?>
                                            <span class="text-primary text-sm ml-2 font-bold">₱<?php echo e(number_format($arr['subtotal'], 2)); ?></span>
                                        <?php else: ?>
                                            <span class="text-success text-sm ml-2">Included</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Quotation Breakdown Card -->
                <?php if(!empty($reservation->quotation_breakdown)): ?>
                    <div class="ent-card">
                        <h3 class="ent-card-title mb-4">
                            <i class="bi bi-receipt-cutoff text-primary"></i> Quotation Breakdown
                        </h3>
                        <table style="width:100%;border-collapse:collapse;">
                            <?php $__currentLoopData = $reservation->quotation_breakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr style="border-bottom:1px solid #f3f4f6;">
                                    <td style="padding:0.75rem 0;color:#374151;"><?php echo e($item['label']); ?></td>
                                    <td style="padding:0.75rem 0;text-align:right;font-weight:600;">₱<?php echo e(number_format($item['amount'], 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($reservation->is_fee_waived): ?>
                                <tr style="background:#ecfdf5;border-bottom:1px solid #d1fae5;">
                                    <td style="padding:0.75rem;color:#065f46;font-weight:600;"><i class="bi bi-check-circle me-1"></i> Fee Waiver Applied</td>
                                    <td style="padding:0.75rem;text-align:right;color:#065f46;font-weight:600;">-₱<?php echo e(number_format($reservation->quotation_total, 2)); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr style="border-top:2px solid var(--brand-primary, #2563eb);">
                                <td style="padding:0.75rem 0;font-weight:800;font-size:1.1rem;">Total</td>
                                <td style="padding:0.75rem 0;text-align:right;font-weight:800;font-size:1.1rem;color:var(--brand-primary, #2563eb);">₱<?php echo e(number_format($reservation->total_price, 2)); ?></td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Supporting Documents Card -->
                <?php if($reservation->documents && $reservation->documents->isNotEmpty()): ?>
                    <div class="ent-card mt-4">
                        <h3 class="ent-card-title mb-4">
                            <i class="bi bi-file-earmark-arrow-up text-primary"></i> Supporting Documents
                        </h3>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $reservation->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <i class="bi <?php echo e($doc->icon_class); ?> text-xl"></i>
                                        <div>
                                            <p class="font-bold text-sm text-gray-800 mb-0"><?php echo e($doc->original_name); ?></p>
                                            <p class="text-xs text-gray-500 mb-0"><?php echo e($doc->formatted_size); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="<?php echo e(route('guest.reservation.documents.view', [$reservation, $doc])); ?>" target="_blank" class="px-3 py-1 bg-white border border-gray-200 rounded text-xs font-semibold text-gray-700 hover:bg-gray-100 text-decoration-none">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                        <a href="<?php echo e(route('guest.reservation.documents.download', [$reservation, $doc])); ?>" class="px-3 py-1 bg-white border border-gray-200 rounded text-xs font-semibold text-gray-700 hover:bg-gray-100 text-decoration-none">
                                            <i class="bi bi-download me-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="ent-card mb-4">
                    <h3 class="ent-card-title mb-4">Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-500">Status</span>
                            <span class="font-semibold text-blue-600 uppercase text-sm"><?php echo e($reservation->status); ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-500">Client Type</span>
                            <span class="font-semibold text-gray-700 capitalize"><?php echo e($reservation->client_type_label); ?></span>
                        </div>
                        <?php if($reservation->endorsement_status !== 'not_required'): ?>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-500">Endorsement</span>
                                <span class="font-semibold text-gray-700"><?php echo e($reservation->endorsement_label); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-500">Payment Method</span>
                            <span class="font-semibold text-gray-700 capitalize"><?php echo e($reservation->payment_method === 'waived' ? 'Waived' : ucfirst($reservation->payment_method)); ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <span class="text-gray-500">Payment Status</span>
                            <?php if($reservation->is_fee_waived): ?>
                                <span class="font-bold text-green-600">WAIVED</span>
                            <?php elseif($reservation->payment_status === 'paid'): ?>
                                <span class="font-bold text-green-600">PAID</span>
                            <?php else: ?>
                                <span class="font-bold text-amber-600">PENDING</span>
                            <?php endif; ?>
                        </div>
                        <?php if($reservation->security_deposit > 0 && !$reservation->is_fee_waived): ?>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                <span class="text-gray-500">Security Deposit</span>
                                <span class="font-semibold text-gray-700">₱<?php echo e(number_format($reservation->security_deposit, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="pt-2">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-gray-500">Total Amount</span>
                                <span class="text-2xl font-bold text-gray-900">₱<?php echo e(number_format($reservation->total_price, 2)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ent-card bg-blue-50 border-blue-100">
                    <h4 class="font-bold text-blue-800 mb-2">Need Help?</h4>
                    <p class="text-sm text-blue-700 mb-3">If you have any questions regarding your reservation or payment, please contact our support team.</p>
                    <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-800">
                        View FAQs <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Reuse dashboard styles and add specific ones */
    :root {
        --ent-bg: #F9FAFB;
        --ent-surface: #FFFFFF;
        --ent-border: #E5E7EB;
        --ent-text-primary: #111827;
        --ent-text-secondary: #4B5563;
        --ent-primary: #2563EB;
        --ent-primary-dark: #1D4ED8;
        --ent-success: #10B981;
        --ent-success-light: #ECFDF5;
        --ent-warning: #F59E0B;
        --ent-warning-light: #FFFBEB;
        --ent-radius: 16px;
        --ent-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --ent-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .dashboard-wrapper {
        background-color: var(--ent-bg);
        min-height: calc(100vh - 64px);
        padding: 2.5rem 1.5rem;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    @media (min-width: 1024px) {
        .dashboard-layout {
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
        }
    }

    .ent-card {
        background: var(--ent-surface);
        border-radius: var(--ent-radius);
        border: 1px solid var(--ent-border);
        box-shadow: var(--ent-shadow-sm);
        padding: 1.75rem;
    }

    .status-tracker-card {
        border-top: 5px solid var(--ent-primary);
    }

    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        gap: 1.25rem;
        min-width: 0;
    }

    .status-title-wrap {
        min-width: 0;
        flex: 1;
        overflow: hidden;
    }

    .status-badge-wrap {
        flex-shrink: 0;
    }

    .status-facility {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ent-text-primary);
        margin-bottom: 0.5rem;
        word-break: break-word;
        overflow-wrap: anywhere;
        min-width: 0;
        line-height: 1.25;
    }

    .status-time {
        font-size: 0.95rem;
        color: var(--ent-text-secondary);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.05em;
    }

    .badge-pending { background: var(--ent-warning-light); color: var(--ent-warning); }
    .badge-approved { background: var(--ent-success-light); color: var(--ent-success); }

    .btn-primary-ent {
        background-color: var(--ent-primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
    }

    .btn-primary-ent:hover {
        background-color: var(--ent-primary-dark);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-secondary-ent {
        background-color: white;
        color: var(--ent-text-primary);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: 1px solid var(--ent-border);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
    }

<?php $__env->startPush('scripts'); ?>
<script>
    // The wizard keeps its draft in localStorage until the booking is
    // actually confirmed. Now that this user just completed a reservation,
    // purge the draft so a stale form never reappears on the next visit.
    try {
        // Covers both the logged-in wizard key and the anonymous variant
        // used on the reserve page; removing both is cheap and safe.
        const uid = <?php echo e(auth()->id() ?? 0); ?>;
        localStorage.removeItem('wizard_draft_' + uid);
        localStorage.removeItem('wizard_draft_anon');
    } catch (e) { /* storage unavailable — ignore */ }
</script>
<?php $__env->stopPush(); ?>

    .btn-secondary-ent:hover {
        background-color: #F3F4F6;
        color: var(--ent-text-primary);
        border-color: #D1D5DB;
    }

    .welcome-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--ent-text-primary);
        letter-spacing: -0.025em;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 1.1rem;
        color: var(--ent-text-secondary);
    }

    .ent-card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--ent-text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/guest/reservations/confirmation.blade.php ENDPATH**/ ?>