
<?php
    // Web app uses AUTO light/dark logo switching (logo_auto_variant):
    // dark surface -> uploaded white variant (fallback: light); light surface -> dark variant.
    // system_logo is email-only; the web app renders logo_light / logo_white / logo_dark.
    $pvLogoDark   = \App\Services\BrandingService::logoForBg('dark');   // white/light logo for the dark navbar
    $pvLogoLight  = \App\Services\BrandingService::logoForBg('light');  // dark logo for light surfaces
    $pvWordmarkOnly = \App\Services\BrandingService::wordmarkOnly();
    // Saved DB values — always seeded into the preview regardless of the page.
    $pvPrimary   = \App\Models\SystemSetting::getValue('brand_primary', '#2563eb');
    $pvSecondary = \App\Models\SystemSetting::getValue('brand_secondary', '#7c3aed');
    $pvAccent    = \App\Models\SystemSetting::getValue('brand_accent', '#06b6d4');
    $pvDark      = \App\Models\SystemSetting::getValue('brand_dark', '#0f172a');
    $pvAnnEnabled = \App\Models\SystemSetting::getValue('announcement_enabled', '0') === '1';
    $pvAnnText    = trim((string) \App\Models\SystemSetting::getValue('announcement_text', ''));
    $pvSysName    = \App\Models\SystemSetting::getValue('system_name', 'System Name');
?>
<div class="cms-preview-card" id="cmsPreviewCard"
     data-pv-primary="<?php echo e(preg_match('/^#[0-9a-fA-F]{6}$/', $pvPrimary)   ? $pvPrimary   : '#2563eb'); ?>"
     data-pv-secondary="<?php echo e(preg_match('/^#[0-9a-fA-F]{6}$/', $pvSecondary) ? $pvSecondary : '#7c3aed'); ?>"
     data-pv-accent="<?php echo e(preg_match('/^#[0-9a-fA-F]{6}$/', $pvAccent)    ? $pvAccent    : '#06b6d4'); ?>"
          data-pv-dark="<?php echo e(preg_match('/^#[0-9a-fA-F]{6}$/', $pvDark)        ? $pvDark      : '#0f172a'); ?>"
     data-pv-logo-dark="<?php echo e($pvLogoDark); ?>"
     data-pv-logo-light="<?php echo e($pvLogoLight); ?>"
     data-pv-logo-fallback="<?php echo e(\App\Services\BrandingService::logoVariant('white')); ?>"
     data-pv-wordmark-only="<?php echo e($pvWordmarkOnly ? '1' : '0'); ?>"
     data-pv-sys-name="<?php echo e(e($pvSysName)); ?>"
     data-pv-hero-heading="<?php echo e(e(\App\Models\SystemSetting::getValue('hero.heading', ''))); ?>"
     data-pv-hero-subheading="<?php echo e(e(\App\Models\SystemSetting::getValue('hero.sub', ''))); ?>"
     data-pv-hero-eyebrow="<?php echo e(e(\App\Models\SystemSetting::getValue('hero.eyebrow', ''))); ?>"
     data-pv-ann-enabled="<?php echo e($pvAnnEnabled ? '1' : '0'); ?>"
     data-pv-ann-text="<?php echo e(e($pvAnnText)); ?>">

    <div class="cms-preview-head">
        <h6><i class="bi bi-eye-fill"></i> Live Preview</h6>
        <span class="cms-live-status">
            <span class="cms-preview-live-dot"></span> Unsaved &mdash; reflects current form values
        </span>
    </div>
    <div class="cms-preview-body pv-phone">

        
        <div class="pv-annbar<?php echo e($pvAnnEnabled ? '' : ' pv-annbar-off'); ?>" id="pvAnnBar">
            <i class="bi bi-megaphone-fill"></i>
            <span class="pv-annbar-text" id="pvAnnText"><?php echo e($pvAnnText ?: 'University facilities reservation system is now live.'); ?></span>
            <span class="pv-annbar-x"><i class="bi bi-x-lg"></i></span>
        </div>

        
        <div class="pv-appbar" id="pvNavbar"
             data-pv-logo-dark="<?php echo e($pvLogoDark); ?>"
             data-pv-logo-light="<?php echo e($pvLogoLight); ?>"
             data-pv-logo-fallback="<?php echo e(\App\Services\BrandingService::logoVariant('white')); ?>">
            <span class="pv-brand">
                <img id="pvLogoImg" src="<?php echo e($pvLogoDark); ?>" alt="Logo"
                     onerror="this.style.display='none';document.getElementById('pvLogoFallback').style.display='flex';">
                <span id="pvLogoFallback" class="pv-logo-fallback" style="display:none;">LOGO</span>
                <span class="pv-name<?php echo e($pvWordmarkOnly ? ' pv-name-wordmark' : ''); ?>" id="pvName"><?php echo e(e($pvSysName)); ?></span>
            </span>
            <span class="pv-navlinks">
                <span>About</span><span>Features</span><span>Gallery</span><span>FAQ</span><span>Contact</span>
            </span>
            <span class="pv-navactions">
                <span class="pv-nav-btn pv-nav-btn-outline">Log in</span>
                <span class="pv-nav-btn pv-nav-btn-solid">Sign up</span>
            </span>
        </div>

        
        <div class="pv-hero" id="pvHero">
            <div class="pv-hero-scrim"></div>
            <div class="pv-hero-content">
                <span class="pv-eyebrow" id="pvHeroEyebrow">
                    <i class="bi bi-stars"></i> <?php echo e(\App\Models\SystemSetting::getValue('hero.eyebrow', null) ?: 'Simplify University Reservations'); ?>

                </span>
                <h3 id="pvHeroTitle"><?php echo e(\App\Models\SystemSetting::getValue('hero.heading', 'Reserve University Facilities')); ?></h3>
                <p id="pvHeroSubtitle"><?php echo e(\App\Models\SystemSetting::getValue('hero.sub', 'Book campus spaces effortlessly with our streamlined reservation platform.')); ?></p>
                <div class="pv-hero-cta">
                    <span class="pv-btn pv-btn-primary pv-btn-lg" id="pvHeroCta"><?php echo e(\App\Models\SystemSetting::getValue('hero.cta_text', 'Reserve a facility')); ?> <i class="bi bi-arrow-right"></i></span>
                    <span class="pv-btn pv-btn-ghost">Learn more <i class="bi bi-chevron-down"></i></span>
                </div>
                <div class="pv-hero-badges" id="pvHeroBadges">
                    <?php
                        $pvBadges = \App\Models\SystemSetting::getValue('homepage_content', '');
                        $pvBadgeList = [];
                        try { $decoded = json_decode($pvBadges, true); } catch (\Throwable $e) { $decoded = null; }
                        if (is_array($decoded) && !empty($decoded['hero']['badges'])) {
                            $pvBadgeList = collect($decoded['hero']['badges'])->filter(fn ($b) => !empty($b['label']))->take(4)->all();
                        }
                        if (!$pvBadgeList) {
                            $pvBadgeList = [
                                ['label' => '100% online process'],
                                ['label' => 'Real-time status updates'],
                                ['label' => 'No double bookings'],
                            ];
                        }
                    ?>
                    <?php $__currentLoopData = $pvBadgeList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pvB): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="pv-badge-chip"><i class="bi bi-shield-check-fill"></i> <?php echo e($pvB['label']); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="pv-scroll-hint"><span class="pv-scroll-line"></span><span>Scroll</span></div>
        </div>

        
        <div class="pv-strip">
            <span class="pv-chip pv-chip-primary">Primary</span>
            <span class="pv-chip pv-chip-secondary">Secondary</span>
            <span class="pv-chip pv-chip-accent">Accent</span>
            <a href="<?php echo e(url('/')); ?>" target="_blank" class="pv-link">View live homepage &rarr;</a>
            <span class="pv-swatches">
                <span class="pv-swatch" id="pvSwatchPrimary"></span>
                <span class="pv-swatch" id="pvSwatchSecondary"></span>
                <span class="pv-swatch" id="pvSwatchAccent"></span>
                <span class="pv-swatch" id="pvSwatchDark"></span>
            </span>
        </div>
    </div>
</div>


<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/partials/sa-live-preview.blade.php ENDPATH**/ ?>