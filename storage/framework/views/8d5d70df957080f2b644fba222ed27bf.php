<?php $__env->startSection('title', 'System Settings — Site Appearance'); ?>

<?php $__env->startSection('content'); ?>

<div class="branding-layout">
<?php echo $__env->make('partials.superadmin-branding-nav', ['page' => 'system'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Site Appearance</h1>
        <p class="ph-sub">Typography, hero accent and identity modes.</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('superadmin.dashboard')); ?>" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Dashboard</a>
    </div>
</div>

<?php
    $apFonts       = ['Figtree', 'Inter', 'Roboto', 'Open Sans', 'Source Sans 3', 'Lato'];
    $panelFont     = old('settings.panel_font', \App\Models\SystemSetting::getValue('panel_font', 'Figtree'));
    $publicFont    = old('settings.public_font', \App\Models\SystemSetting::getValue('public_font', ''));
    $heroStart     = old('settings.hero_gradient_start', \App\Models\SystemSetting::getValue('hero_gradient_start', ''));
    $heroEnd       = old('settings.hero_gradient_end', \App\Models\SystemSetting::getValue('hero_gradient_end', ''));
    $heroStartPreview = preg_match('/^#[0-9a-fA-F]{6}$/', trim((string) $heroStart)) ? trim((string) $heroStart) : '#2563eb';
    $heroEndPreview   = preg_match('/^#[0-9a-fA-F]{6}$/', trim((string) $heroEnd))   ? trim((string) $heroEnd)   : '#0f172a';
    $hasHeroPreset    = trim((string) $heroStart) !== '' || trim((string) $heroEnd) !== '';
    $heroGradEnabled  = old('settings.hero_gradient_enabled', \App\Models\SystemSetting::getValue('hero_gradient_enabled', '1')) === '1';
    $wordmarkOnly = old('settings.use_wordmark_only', \App\Models\SystemSetting::getValue('use_wordmark_only', '0')) === '1';
    $autoVariant  = old('settings.logo_auto_variant', \App\Models\SystemSetting::getValue('logo_auto_variant', '0')) === '1';
?>

<form action="<?php echo e(route('superadmin.settings.system.update')); ?>" method="POST" id="appearanceForm">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="cms-grid">
        <div class="setting-group-card" id="group-appearance">
            <div class="setting-group-header">
                <i class="bi bi-paint-bucket"></i>
                Site Appearance
                <span class="sg-sub">Typography, hero accent and identity modes.</span>
            </div>
            <div class="setting-group-body">
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-panel_font">
                                Admin Panel Font Family
                                <span class="setting-key">panel_font</span>
                            </label>
                            <select class="setting-input" id="setting-panel_font" name="settings[panel_font]">
                                <?php $__currentLoopData = $apFonts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($f); ?>" <?php echo e($panelFont === $f ? 'selected' : ''); ?>><?php echo e($f); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Applied across the guest dashboard, admin panels, and cashier views.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-public_font">
                                Public Site Font Family
                                <span class="setting-key">public_font</span>
                            </label>
                            <select class="setting-input" id="setting-public_font" name="settings[public_font]">
                                <option value="" <?php echo e($publicFont === '' ? 'selected' : ''); ?>>Default (Inter)</option>
                                <?php $__currentLoopData = $apFonts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($f); ?>" <?php echo e($publicFont === $f ? 'selected' : ''); ?>><?php echo e($f); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Homepage, FAQ, and public pages. Blank keeps the default.</p>
                        </div>
                    </div>
                </div>

                <hr class="cms-divider">

                
                <div class="setting-field is-boolean mb-3">
                    <label class="setting-label mb-0" for="setting-hero_gradient_enabled">
                        Hero Gradient Overlay
                        <span class="setting-key">hero_gradient_enabled</span>
                    </label>
                    <div class="form-check form-switch premium-switch mb-0">
                        <input type="hidden" name="settings[hero_gradient_enabled]" value="0">
                        <input class="form-check-input" type="checkbox" role="switch"
                               id="setting-hero_gradient_enabled" name="settings[hero_gradient_enabled]" value="1"
                               <?php echo e($heroGradEnabled ? 'checked' : ''); ?>>
                    </div>
                </div>
                <p class="setting-hint mb-3">
                    <i class="bi bi-info-circle me-1"></i>Apply a colorful gradient accent across the homepage hero media. Turn off to use a clean, neutral dark overlay with no color tint.
                </p>

                <div id="hero-gradient-config" class="<?php echo e($heroGradEnabled ? '' : 'opacity-50'); ?>" style="<?php echo e($heroGradEnabled ? '' : 'pointer-events: none;'); ?>">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="setting-field">
                                <label class="setting-label" for="setting-hero_gradient_start">
                                    Hero Gradient Start Color
                                    <span class="setting-key">hero_gradient_start</span>
                                </label>
                                <div class="color-preview">
                                    <input
                                        type="color"
                                        class="color-swatch"
                                        id="color-swatch-hero_gradient_start"
                                        value="<?php echo e($heroStartPreview); ?>"
                                        <?php echo e($heroGradEnabled ? '' : 'disabled'); ?>

                                    >
                                    <input
                                        type="text"
                                        class="setting-input color-hex-input"
                                        id="setting-hero_gradient_start"
                                        name="settings[hero_gradient_start]"
                                        value="<?php echo e($heroStart); ?>"
                                        placeholder="#2563eb"
                                        <?php echo e($heroGradEnabled ? '' : 'disabled'); ?>

                                    >
                                </div>
                                <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Accent color at the right edge of the hero overlay. Blank uses default.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="setting-field">
                                <label class="setting-label" for="setting-hero_gradient_end">
                                    Hero Gradient End Color
                                    <span class="setting-key">hero_gradient_end</span>
                                </label>
                                <div class="color-preview">
                                    <input
                                        type="color"
                                        class="color-swatch"
                                        id="color-swatch-hero_gradient_end"
                                        value="<?php echo e($heroEndPreview); ?>"
                                        <?php echo e($heroGradEnabled ? '' : 'disabled'); ?>

                                    >
                                    <input
                                        type="text"
                                        class="setting-input color-hex-input"
                                        id="setting-hero_gradient_end"
                                        name="settings[hero_gradient_end]"
                                        value="<?php echo e($heroEnd); ?>"
                                        placeholder="#0f172a"
                                        <?php echo e($heroGradEnabled ? '' : 'disabled'); ?>

                                    >
                                </div>
                                <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Final color the hero overlay fades into. Blank uses default.</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <button type="button" class="btn btn-sm btn-ghost text-muted" id="btn-reset-hero-gradient" title="Reset gradient to default colors">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset to Defaults
                        </button>
                        <button type="button" class="btn btn-sm btn-ghost text-muted" id="btn-clear-hero-gradient" title="Clear inputs">
                            <i class="bi bi-x-circle me-1"></i>Clear
                        </button>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="small fw-semibold text-muted mb-0">Hero Preview</label>
                        <?php
                            $hasActiveGrad = $heroGradEnabled && ($heroStart !== '' || $heroEnd !== '');
                        ?>
                        <span id="hero-preview-status" class="badge <?php echo e($hasActiveGrad ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary'); ?>">
                            <?php echo e($hasActiveGrad ? 'Gradient Overlay Active' : ($heroGradEnabled ? 'Neutral Overlay (Fields Empty)' : 'Neutral Overlay (Gradient Off)')); ?>

                        </span>
                    </div>
                    <div class="hero-gradient-preview" id="hero-gradient-preview"
                         style="transition: background 0.3s ease; background: <?php echo e($hasActiveGrad ? 'linear-gradient(120deg, rgba(15,23,42,.88) 0%, rgba(15,23,42,.62) 45%, ' . ($heroStartPreview ?: '#2563eb') . ' 82%, ' . ($heroEndPreview ?: '#0f172a') . ' 100%)' : 'linear-gradient(120deg, rgba(15,23,42,.88) 0%, rgba(15,23,42,.62) 50%, rgba(15,23,42,.45) 100%)'); ?>;">
                        <span id="hero-preview-label"><?php echo e($hasActiveGrad ? 'Home hero overlay' : 'Neutral hero overlay (gradient disabled)'); ?></span>
                    </div>
                </div>

                <hr class="cms-divider">

                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="setting-field is-boolean">
                            <label class="setting-label mb-0" for="setting-use_wordmark_only">
                                Wordmark-Only Mode
                                <span class="setting-key">use_wordmark_only</span>
                            </label>
                            <div class="form-check form-switch premium-switch mb-0">
                                <input type="hidden" name="settings[use_wordmark_only]" value="0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="setting-use_wordmark_only" name="settings[use_wordmark_only]" value="1"
                                       <?php echo e($wordmarkOnly ? 'checked' : ''); ?>>
                            </div>
                        </div>
                        <p class="setting-hint mb-0">
                            <i class="bi bi-info-circle me-1"></i>Hide the system-name text next to every logo — the logo/wordmark alone carries the identity. Works sitewide (nav, hero, footer, panels, emails).
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-field is-boolean">
                            <label class="setting-label mb-0" for="setting-logo_auto_variant">
                                Automatic Logo Variant
                                <span class="setting-key">logo_auto_variant</span>
                            </label>
                            <div class="form-check form-switch premium-switch mb-0">
                                <input type="hidden" name="settings[logo_auto_variant]" value="0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="setting-logo_auto_variant" name="settings[logo_auto_variant]" value="1"
                                       <?php echo e($autoVariant ? 'checked' : ''); ?>>
                            </div>
                        </div>
                        <p class="setting-hint mb-0">
                            <i class="bi bi-info-circle me-1"></i>Each logo measures the brightness of the surface behind it and swaps to the white/light variant on dark areas and the dark variant on light areas — on the fly, no reload.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials._cms_save_bar', ['label' => 'Save Appearance'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* ── Sync each color swatch with its hex input ── */
    function bindColorPair(key) {
        const swatch = document.getElementById('color-swatch-' + key);
        const hex = document.getElementById('setting-' + key);
        if (!swatch || !hex) return;
        swatch.addEventListener('input', function () {
            hex.value = swatch.value;
            updateGradientPreview();
        });
        hex.addEventListener('input', function () {
            if (/^#[0-9a-fA-F]{6}$/.test(hex.value)) {
                swatch.value = hex.value;
            }
            updateGradientPreview();
        });
    }
    bindColorPair('hero_gradient_start');
    bindColorPair('hero_gradient_end');

    /* ── Mirror the gradient colors into the small hero overlay preview card ── */
    const preview = document.getElementById('hero-gradient-preview');
    const toggleSwitch = document.getElementById('setting-hero_gradient_enabled');
    const configBox = document.getElementById('hero-gradient-config');
    const previewStatus = document.getElementById('hero-preview-status');
    const previewLabel = document.getElementById('hero-preview-label');

    window.updateGradientPreview = function () {
        if (!preview) return;
        const isEnabled = toggleSwitch ? toggleSwitch.checked : true;

        if (configBox) {
            configBox.classList.toggle('opacity-50', !isEnabled);
            configBox.style.pointerEvents = isEnabled ? '' : 'none';
            const inputs = configBox.querySelectorAll('input');
            inputs.forEach(input => { input.disabled = !isEnabled; });
        }

        if (previewStatus) {
            previewStatus.textContent = isEnabled ? 'Gradient Overlay Active' : 'Neutral Overlay (Gradient Off)';
            previewStatus.className = 'badge ' + (isEnabled ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary');
        }

        if (previewLabel) {
            previewLabel.textContent = isEnabled ? 'Home hero overlay' : 'Neutral hero overlay (no color tint)';
        }

        const start = (document.getElementById('setting-hero_gradient_start').value || '').trim();
        const end   = (document.getElementById('setting-hero_gradient_end').value || '').trim();
        const hasColors = start !== '' || end !== '';

        if (!isEnabled || !hasColors) {
            preview.style.background = 'linear-gradient(120deg, rgba(15,23,42,.88) 0%, rgba(15,23,42,.62) 50%, rgba(15,23,42,.45) 100%)';
            if (previewStatus) {
                previewStatus.textContent = !isEnabled ? 'Neutral Overlay (Gradient Off)' : 'Neutral Overlay (Fields Empty)';
                previewStatus.className = 'badge bg-secondary-subtle text-secondary';
            }
            if (previewLabel) {
                previewLabel.textContent = 'Neutral hero overlay (gradient disabled)';
            }
            return;
        }

        if (previewStatus) {
            previewStatus.textContent = 'Gradient Overlay Active';
            previewStatus.className = 'badge bg-primary-subtle text-primary';
        }
        if (previewLabel) {
            previewLabel.textContent = 'Home hero overlay';
        }

        const startHex = /^#[0-9a-fA-F]{6}$/.test(start) ? start : '#2563eb';
        const endHex   = /^#[0-9a-fA-F]{6}$/.test(end)   ? end   : '#0f172a';
        preview.style.background = 'linear-gradient(120deg, rgba(15,23,42,.88) 0%, rgba(15,23,42,.62) 45%, ' + startHex + ' 82%, ' + endHex + ' 100%)';
    };

    if (toggleSwitch) {
        toggleSwitch.addEventListener('change', updateGradientPreview);
    }

    ['setting-hero_gradient_start', 'setting-hero_gradient_end'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', updateGradientPreview);
    });

    const btnReset = document.getElementById('btn-reset-hero-gradient');
    if (btnReset) {
        btnReset.addEventListener('click', function () {
            const startInput = document.getElementById('setting-hero_gradient_start');
            const endInput = document.getElementById('setting-hero_gradient_end');
            const startSwatch = document.getElementById('color-swatch-hero_gradient_start');
            const endSwatch = document.getElementById('color-swatch-hero_gradient_end');
            if (startInput) startInput.value = '#2563eb';
            if (endInput) endInput.value = '#0f172a';
            if (startSwatch) startSwatch.value = '#2563eb';
            if (endSwatch) endSwatch.value = '#0f172a';
            updateGradientPreview();
        });
    }

    const btnClear = document.getElementById('btn-clear-hero-gradient');
    if (btnClear) {
        btnClear.addEventListener('click', function () {
            const startInput = document.getElementById('setting-hero_gradient_start');
            const endInput = document.getElementById('setting-hero_gradient_end');
            const startSwatch = document.getElementById('color-swatch-hero_gradient_start');
            const endSwatch = document.getElementById('color-swatch-hero_gradient_end');
            if (startInput) startInput.value = '';
            if (endInput) endInput.value = '';
            if (startSwatch) startSwatch.value = '#2563eb';
            if (endSwatch) endSwatch.value = '#0f172a';
            updateGradientPreview();
        });
    }

    updateGradientPreview();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/superadmin/settings/system/category/appearance.blade.php ENDPATH**/ ?>