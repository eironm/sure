<?php $__env->startSection('preheader', 'Enter your verification code to activate your ' . branding('system_name', 'Platform') . ' account.'); ?>

<?php $__env->startSection('status_banner'); ?>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: <?php echo e(\App\Services\BrandingService::emailBrandTint()); ?>; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15)); ?>; letter-spacing: 0.3px;">
                Your Verification Code
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.4)); ?>;">
                One quick step to activate your account
            </p>
        </td>
    </tr>
</table>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
        Hi <?php echo e($user->first_name ?? 'there'); ?>,
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        To keep your account secure, enter the <strong>6-digit code</strong> below on the verification screen before you can log in to <strong><?php echo e(branding('system_name', 'Platform')); ?></strong>.
    </p>

    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 26px auto;">
        <tr>
            <td style="background-color: <?php echo e(\App\Services\BrandingService::emailBrandTint()); ?>; border: 1px solid <?php echo e(\App\Services\BrandingService::lightenHex(branding('brand_primary', '#2563eb'), 0.6)); ?>; border-radius: 10px; padding: 20px 36px; text-align: center;">
                <p style="margin: 0 0 8px 0; font-size: 11px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.4)); ?>;">
                    Verification Code
                </p>
                <p style="margin: 0; font-family: 'Consolas', 'Courier New', monospace; font-size: 32px; font-weight: 800; letter-spacing: 10px; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15)); ?>;">
                    <?php echo e($code); ?>

                </p>
            </td>
        </tr>
    </table>

    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 20px 0;">
        <tr>
            <td style="background-color: #fffbeb; border-left: 3px solid #d97706; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #92400e; line-height: 22px;">
                    <strong>Expires in <?php echo e($minutes); ?> minutes.</strong><br>
                    The code is single-use and time-limited. If it expires, simply request a new one from the verification screen or the login page.
                </p>
            </td>
        </tr>
    </table>

    
    <?php if(($user->client_type ?? '') === 'internal'): ?>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 20px 0;">
            <tr>
                <td style="background-color: #f0fdf4; border-left: 3px solid #10b981; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: #166534; line-height: 22px;">
                        <strong>One more step for university accounts:</strong><br>
                        As an internal (university) account holder, your account also requires <strong>administrator approval</strong>. Once you enter your code, you will be shown an "awaiting approval" screen — our facilities team will review your credentials shortly. You will receive an email as soon as your account is approved.
                    </p>
                </td>
            </tr>
        </table>
    <?php endif; ?>

    <p style="margin: 0; font-size: 13px; color: #94a3b8; line-height: 20px;">
        If you did not create a <?php echo e(branding('system_name', 'Platform')); ?> account, no further action is required — your code will simply expire.
    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.email-base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/emails/auth/verification-code.blade.php ENDPATH**/ ?>