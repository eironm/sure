<?php $__env->startSection('preheader', 'Your reservation for ' . $reservation->facility->name . ' has been submitted. Ref: ' . $reservation->booking_reference); ?>

<?php $__env->startSection('status_banner'); ?>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: <?php echo e(\App\Services\BrandingService::emailBrandTint()); ?>; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15)); ?>; letter-spacing: 0.3px;">
                Reservation Submitted
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.4)); ?>;">
                <?php echo e($reservation->booking_reference); ?>

            </p>
        </td>
    </tr>
</table>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: <?php echo e(branding('brand_dark', '#0f172a')); ?>;">
        Hi <?php echo e($reservation->user->first_name); ?>,
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        Your reservation has been successfully submitted and is now awaiting review. Here are your booking details:
    </p>

    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
        <tr>
            <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px 26px;">
                <h4 style="margin: 0 0 14px 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b;">
                    Booking Details
                </h4>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; width: 130px; vertical-align: top;">Reference</td>
                        <td style="padding: 6px 0; font-size: 14px; color: <?php echo e(branding('brand_dark', '#0f172a')); ?>; font-weight: 700;"><?php echo e($reservation->booking_reference); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Facility</td>
                        <td style="padding: 6px 0; font-size: 14px; color: <?php echo e(branding('brand_dark', '#0f172a')); ?>; font-weight: 600;"><?php echo e($reservation->facility->name); ?></td>
                    </tr>
                    <?php if($reservation->event_title): ?>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Event</td>
                        <td style="padding: 6px 0; font-size: 14px; color: <?php echo e(branding('brand_dark', '#0f172a')); ?>;"><?php echo e($reservation->event_title); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Date</td>
                        <td style="padding: 6px 0; font-size: 14px; color: <?php echo e(branding('brand_dark', '#0f172a')); ?>;">
                            <?php echo e($reservation->start_date->format('M d, Y')); ?>

                            <?php if($reservation->end_date && $reservation->end_date->ne($reservation->start_date)): ?>
                                — <?php echo e($reservation->end_date->format('M d, Y')); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if(!$reservation->is_fee_waived): ?>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Total Amount</td>
                        <td style="padding: 6px 0; font-size: 16px; color: <?php echo e(branding('brand_dark', '#0f172a')); ?>; font-weight: 700;">₱<?php echo e(number_format($reservation->total_price, 2)); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Status</td>
                        <td style="padding: 6px 0; font-size: 14px;">
                            <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: <?php echo e(\App\Services\BrandingService::emailBrandTint()); ?>; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15)); ?>;">
                                Pending Review
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    
    <?php if($reservation->is_fee_waived): ?>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
            <tr>
                <td style="background-color: #f0fdf4; border-left: 3px solid #10b981; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: #166534; line-height: 22px;">
                        <strong>Fees Waived</strong><br>
                        As an internal client, facility fees have been waived for this reservation. No payment is required.
                    </p>
                </td>
            </tr>
        </table>
    <?php elseif($reservation->payment_method === 'cash'): ?>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
            <tr>
                <td style="background-color: #fffbeb; border-left: 3px solid #d97706; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: #92400e; line-height: 22px;">
                        <strong>Cash Payment Required</strong><br>
                        Please visit the cashier to pay <strong>₱<?php echo e(number_format($reservation->total_price, 2)); ?></strong> by <strong><?php echo e($reservation->deadlineForHumans()); ?></strong>. Failure to pay by the deadline will result in automatic cancellation.
                    </p>
                </td>
            </tr>
        </table>
    <?php else: ?>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
            <tr>
                <td style="background-color: <?php echo e(\App\Services\BrandingService::emailBrandTint()); ?>; border-left: 3px solid <?php echo e(branding('brand_primary', '#2563eb')); ?>; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.35)); ?>; line-height: 22px;">
                        <strong>Online Payment</strong><br>
                        Please complete your online payment by <strong><?php echo e($reservation->deadlineForHumans()); ?></strong> to confirm your booking.
                    </p>
                </td>
            </tr>
        </table>
    <?php endif; ?>

    <p style="margin: 0; font-size: 14px; color: #475569; line-height: 22px;">
        Our team will review your reservation shortly. You'll receive another email once it's approved or if any action is needed.
    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.email-base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/emails/reservations/reservation-submitted.blade.php ENDPATH**/ ?>