<?php $__env->startSection('preheader', 'Your reservation for ' . $reservation->facility->name . ' has been cancelled. Ref: ' . $reservation->booking_reference); ?>

<?php $__env->startSection('status_banner'); ?>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: #fef2f2; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #b91c1c; letter-spacing: 0.3px;">
                Reservation Cancelled
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #dc2626;">
                <?php echo e($reservation->booking_reference); ?>

            </p>
        </td>
    </tr>
</table>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
        Hi <?php echo e($reservation->user->first_name); ?>,
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        Your reservation for <strong><?php echo e($reservation->facility->name); ?></strong> has been <strong style="color: #b91c1c;">cancelled</strong>.
    </p>

    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
        <tr>
            <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px 26px;">
                <h4 style="margin: 0 0 14px 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b;">
                    Cancelled Booking
                </h4>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; width: 130px; vertical-align: top;">Reference</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: 700;"><?php echo e($reservation->booking_reference); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Facility</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: 600;"><?php echo e($reservation->facility->name); ?></td>
                    </tr>
                    <?php if($reservation->event_title): ?>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Event</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;"><?php echo e($reservation->event_title); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Date</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">
                            <?php echo e($reservation->start_date->format('M d, Y')); ?>

                            <?php if($reservation->end_date && $reservation->end_date->ne($reservation->start_date)): ?>
                                — <?php echo e($reservation->end_date->format('M d, Y')); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php if($reservation->admin_remarks): ?>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="background-color: #f8fafc; border-left: 3px solid #94a3b8; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #475569; line-height: 22px;">
                    <strong>Cancellation Reason:</strong><br>
                    <?php echo e($reservation->admin_remarks); ?>

                </p>
            </td>
        </tr>
    </table>
    <?php endif; ?>

    <?php if($reservation->cancellation_reason): ?>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="background-color: #f8fafc; border-left: 3px solid #94a3b8; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #475569; line-height: 22px;">
                    <strong>Your Reason:</strong><br>
                    <?php echo e($reservation->cancellation_reason); ?>

                </p>
            </td>
        </tr>
    </table>
    <?php endif; ?>

    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        If you'd like to book another facility, you can submit a new reservation anytime.
    </p>

    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
        <tr>
            <td style="border-radius: 8px; background-color: <?php echo e(branding('brand_primary', '#2563eb')); ?>;">
                <a href="<?php echo e(route('guest.reserve')); ?>" target="_blank" style="display: inline-block; padding: 14px 36px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                    Make a New Reservation
                </a>
            </td>
        </tr>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.email-base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/emails/reservations/reservation-cancelled.blade.php ENDPATH**/ ?>