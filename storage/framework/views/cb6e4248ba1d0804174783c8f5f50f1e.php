<?php $__env->startSection('preheader', 'Verify your email address to activate your ' . branding('system_name', 'Platform') . ' account.'); ?>

<?php $__env->startSection('status_banner'); ?>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: <?php echo e(\App\Services\BrandingService::emailBrandTint()); ?>; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15)); ?>; letter-spacing: 0.3px;">
                Verify Your Email
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.4)); ?>;">
                One quick step to secure your account
            </p>
        </td>
    </tr>
</table>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
        Almost there,
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        Please verify your email address by clicking the button below to complete your <strong><?php echo e(branding('system_name', 'Platform')); ?></strong> account setup. This helps us ensure the security of your account.
    </p>

    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 26px auto;">
        <tr>
            <td style="border-radius: 8px; background-color: <?php echo e(branding('brand_primary', '#2563eb')); ?>;">
                <a href="<?php echo e($verificationUrl); ?>" target="_blank" style="display: inline-block; padding: 14px 40px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                    Verify Email Address
                </a>
            </td>
        </tr>
    </table>

    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 20px 0;">
        <tr>
            <td style="background-color: <?php echo e(\App\Services\BrandingService::emailBrandTint()); ?>; border-left: 3px solid <?php echo e(branding('brand_primary', '#2563eb')); ?>; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: <?php echo e(\App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.35)); ?>; line-height: 22px;">
                    <strong>Why verify?</strong><br>
                    Email verification protects your account and ensures you receive important notifications about your reservations, approvals, and payments.
                </p>
            </td>
        </tr>
    </table>

    <p style="margin: 0; font-size: 13px; color: #94a3b8; line-height: 20px;">
        If you did not create a <?php echo e(branding('system_name', 'Platform')); ?> account, no further action is required.
    </p>

    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 22px 0 0 0;">
        <tr>
            <td style="border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 18px; word-break: break-all;">
                    If the button doesn't work, copy and paste this URL into your browser:<br>
                    <a href="<?php echo e($verificationUrl); ?>" style="color: <?php echo e(branding('brand_primary', '#2563eb')); ?>; text-decoration: none;"><?php echo e($verificationUrl); ?></a>
                </p>
            </td>
        </tr>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.email-base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/emails/auth/verify-email.blade.php ENDPATH**/ ?>