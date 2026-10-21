<?php $__env->startSection('title', 'System Settings — Branding & Identity'); ?>

<?php $__env->startSection('cms-preview'); ?>
    <?php echo $__env->make('partials.sa-live-preview', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="branding-layout">
<?php echo $__env->make('partials.superadmin-branding-nav', ['page' => 'system'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Branding &amp; Identity</h1>
        <p class="ph-sub">System name, brand colors, contact information, social links, SEO and reservation policies.</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('superadmin.dashboard')); ?>" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Dashboard</a>
    </div>
</div>

<?php
    $allSettings = $settings->flatten()->keyBy('key');

    $renderField = function($key, $defaultLabel = null, $defaultType = 'text', $defaultHint = '') use ($allSettings) {
        $setting = $allSettings->get($key);
        if (!$setting) {
            $setting = (object)[
                'key'   => $key,
                'value' => \App\Models\SystemSetting::getValue($key, ''),
                'type'  => $defaultType,
                'label' => $defaultLabel ?? ucwords(str_replace('_', ' ', $key)),
                'hint'  => $defaultHint,
            ];
        }
        return view('partials._cms_field', ['setting' => $setting, 'showHint' => true])->render();
    };
?>

<form action="<?php echo e(route('superadmin.settings.system.update')); ?>" method="POST" enctype="multipart/form-data" id="brandingForm">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    
    <div class="setting-group-card" id="sec-identity">
        <div class="setting-group-header">
            <i class="bi bi-fonts"></i>
            Identity &amp; System Names
            <span class="ms-auto text-muted small">System Name, Tagline &amp; Title</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?php echo $renderField('system_name', 'System Name', 'text', 'Main application and portal brand name.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('system_tagline', 'System Tagline', 'text', 'Short tagline shown on login and public pages.'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('admin_title', 'Admin Panel Title', 'text', 'Header title displayed inside administrative portals.'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="setting-group-card" id="sec-colors">
        <div class="setting-group-header">
            <i class="bi bi-palette-fill"></i>
            Brand Colors &amp; Palette
            <span class="ms-auto text-muted small">Primary, Secondary &amp; Dark Accents</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?php echo $renderField('brand_primary', 'Primary Brand Color', 'color', 'Primary buttons, active indicators, and highlights.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('brand_secondary', 'Secondary Brand Color', 'color', 'Secondary gradients, badges, and accents.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('brand_accent', 'Accent Color', 'color', 'Subtle tints, focus rings, and secondary icons.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('brand_dark', 'Dark Background Color', 'color', 'Sidebar and dark navigation background color.'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="setting-group-card" id="sec-hero">
        <div class="setting-group-header">
            <i class="bi bi-card-heading"></i>
            Hero &amp; Landing Page Copy
            <span class="ms-auto text-muted small">Main Headline &amp; Subtitle</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-12">
                    <?php echo $renderField('hero_heading', 'Hero Heading', 'text', 'Primary headline on the public homepage hero banner.'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('hero_subheading', 'Hero Subheading', 'textarea', 'Supporting descriptive copy on the public hero banner.'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="setting-group-card" id="sec-contact">
        <div class="setting-group-header">
            <i class="bi bi-envelope-at-fill"></i>
            Contact Information &amp; Footer
            <span class="ms-auto text-muted small">Email, Phone, Address &amp; Copyright</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?php echo $renderField('contact_email', 'Contact Email Address', 'email', 'Public contact and inquiry email.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('contact_phone', 'Contact Telephone / Mobile', 'text', 'Office or helpline telephone number.'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('contact_address', 'Physical Office Address', 'textarea', 'Official postal or facility address.'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('footer_text', 'Footer Copyright Text', 'text', 'Copyright line displayed at the bottom of public pages.'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="setting-group-card" id="sec-social">
        <div class="setting-group-header">
            <i class="bi bi-share-fill"></i>
            Social Media Profiles
            <span class="ms-auto text-muted small">Official Organization Channels</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?php echo $renderField('social_facebook', 'Facebook URL', 'url', 'e.g. https://facebook.com/your-org'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('social_instagram', 'Instagram URL', 'url', 'e.g. https://instagram.com/your-org'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('social_tiktok', 'TikTok URL', 'url', 'e.g. https://tiktok.com/@your-org'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('social_x', 'X (Twitter) URL', 'url', 'e.g. https://x.com/your-org'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('social_linkedin', 'LinkedIn URL', 'url', 'e.g. https://linkedin.com/company/your-org'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="setting-group-card" id="sec-seo">
        <div class="setting-group-header">
            <i class="bi bi-search"></i>
            Search Engine Optimization (SEO)
            <span class="ms-auto text-muted small">Metadata &amp; Social Share Image</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-12">
                    <?php echo $renderField('meta_description', 'Meta Description', 'textarea', 'Page description indexed by search engines and displayed in snippets.'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('site_keywords', 'Search Keywords', 'text', 'Comma-separated search keywords and metadata tags.'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('og_image', 'OpenGraph Social Share Image', 'image', 'Image preview shown when sharing links on Facebook, Discord, etc.'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="setting-group-card" id="sec-email">
        <div class="setting-group-header">
            <i class="bi bi-send-fill"></i>
            Outgoing Email Identity
            <span class="ms-auto text-muted small">Sender Name &amp; Signature</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?php echo $renderField('mail_from_name', 'Email Sender Name', 'text', 'The sender name displayed on automated notification emails.'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('email_signature', 'Email Signature', 'textarea', 'Official sign-off signature placed at the bottom of notification emails.'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="setting-group-card" id="sec-policies">
        <div class="setting-group-header">
            <i class="bi bi-shield-check"></i>
            Reservation &amp; Booking Policies
            <span class="ms-auto text-muted small">Deposits, Deadlines, Waivers &amp; Rules</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?php echo $renderField('security_deposit_amount', 'Security Deposit Amount (₱)', 'text', 'Default security deposit amount applied to bookings.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('payment_deadline_days', 'Payment Deadline (Days)', 'number', 'Number of days permitted before an unpaid reservation expires.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('internal_fee_waived', 'Waive Fees for Internal Users', 'boolean', 'Automatically waive facility rental charges for internal staff/departments.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('require_endorsement_internal', 'Require Endorsement for Internal', 'boolean', 'Require dean/director endorsement prior to internal booking approval.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('allow_multiple_advance_reservations', 'Allow Multiple Advance Bookings', 'boolean', 'Permit clients to hold multiple future reservations at the same time.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('approval_chain_enabled', 'Enable Multi-Tier Approval Chain', 'boolean', 'Route booking requests through sequential approval stages.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('approval_settle_grace_days', 'Approval Settle Grace Period (Days)', 'number', 'Grace days granted to finalize payment after reservation approval.'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('approval_allow_pay_during_review', 'Allow Payment During Review', 'boolean', 'Permit users to submit payment while reservation is pending approval.'); ?>

                </div>
                <div class="col-12">
                    <?php echo $renderField('cancellation_policy_text', 'Cancellation & Refund Policy', 'textarea', 'Detailed cancellation rules presented to clients during checkout.'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="setting-group-card" id="sec-reserve-copy">
        <div class="setting-group-header">
            <i class="bi bi-calendar-plus"></i>
            Guest Reservation Page Copy
            <span class="ms-auto text-muted small">Reserve Form Headline &amp; Subtitle</span>
        </div>
        <div class="setting-group-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <?php echo $renderField('reserve_hero_title', 'Reservation Page Headline', 'text', 'Main title displayed at the top of the guest reserve form (default: Reserve a Venue).'); ?>

                </div>
                <div class="col-md-6">
                    <?php echo $renderField('reserve_hero_subheading', 'Reservation Page Subtitle', 'textarea', 'Guidance copy displayed beneath the reservation page title.'); ?>

                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials._cms_save_bar', ['label' => 'Save All Settings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>

<?php echo $__env->make('partials._cms_cropper', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* ── Scroll-spy: highlight sidebar subsection matching the card in view ── */
    var subLinks = document.querySelectorAll('.branding-subnav a.bsn-scroll');
    var secCards = document.querySelectorAll('.branding-main .setting-group-card[id^="sec-"]');

    if (subLinks.length && secCards.length) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var targetLink = document.querySelector('.branding-subnav a.bsn-scroll[data-section="' + entry.target.id + '"]');
                    if (targetLink) {
                        subLinks.forEach(function (item) { item.classList.remove('bsn-scroll-active'); });
                        targetLink.classList.add('bsn-scroll-active');
                    }
                }
            });
        }, { root: null, rootMargin: '-12% 0px -65% 0px', threshold: 0 });

        subLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                var targetId = link.getAttribute('href');
                var targetEl = document.querySelector(targetId);
                if (targetEl) {
                    e.preventDefault();
                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    subLinks.forEach(function (item) { item.classList.remove('bsn-scroll-active'); });
                    link.classList.add('bsn-scroll-active');
                    if (history.pushState) {
                        history.pushState(null, '', targetId);
                    }
                }
            });
        });

        if (subLinks[0]) {
            subLinks[0].classList.add('bsn-scroll-active');
        }
        secCards.forEach(function (card) {
            observer.observe(card);
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/superadmin/settings/system/category/branding.blade.php ENDPATH**/ ?>