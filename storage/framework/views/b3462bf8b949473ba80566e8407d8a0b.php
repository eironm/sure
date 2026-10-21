<?php $__env->startSection('title', 'System Settings — Logos'); ?>

<?php $__env->startSection('content'); ?>

<div class="branding-layout">
<?php echo $__env->make('partials.superadmin-branding-nav', ['page' => 'system'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Logos</h1>
        <p class="ph-sub">Upload different logos for light and dark areas. When an area doesn't have its own variant, the system logo above is used.</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('superadmin.settings.branding')); ?>" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Back to Branding</a>
    </div>
</div>

<?php
    $logoVariants = [
        [
            'key'   => 'logo_light',
            'title' => 'Light Logo',
            'desc'  => 'Used on light backgrounds (public site header, guest dashboard).',
            'icon'  => 'bi-sun-fill',
            'css'   => 'background:#fef3c7;color:#b45309;',
            'bg'    => '#ffffff',
        ],
        [
            'key'   => 'logo_white',
            'title' => 'White / Mono Logo',
            'desc'  => 'Used on dark backgrounds (admin sidebar, dark panels, email headers).',
            'icon'  => 'bi-moon-stars-fill',
            'css'   => 'background:#1e293b;color:#fff;',
            'bg'    => 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)',
        ],
    ];
?>

<form action="<?php echo e(route('superadmin.settings.system.update')); ?>" method="POST" enctype="multipart/form-data" id="logoForm">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="cms-grid">
        <div class="setting-group-card" id="group-logo">
            <div class="setting-group-header">
                <i class="bi bi-images"></i>
                Logo Variants
                <span class="sg-sub">Free-form crop — no ratio lock. Each variant is previewed live as you pick a file.</span>
            </div>
            <div class="setting-group-body">
                <div class="row g-4">
                    <?php $__currentLoopData = $logoVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $lvSetting = collect($settings['branding'] ?? collect())->firstWhere('key', $lv['key'])
                                ?? collect($settings)->flatten()->firstWhere('key', $lv['key']);
                            $lvValue   = $lvSetting ? $lvSetting->value : \App\Models\SystemSetting::getValue($lv['key']);
                            $hasCustom = $lvValue && ! \App\Services\BrandingService::isBundledLogoPath($lvValue);
                            $lvPreview = $hasCustom
                                ? asset($lvValue)
                                : (($lv['key'] === 'logo_white')
                                    ? \App\Services\BrandingService::logoVariant('white')
                                    : \App\Services\BrandingService::logoVariant('light'));
                        ?>
                        <div class="col-md-6">
                            <div class="logo-variant-card">
                                <div class="lv-head">
                                    <div class="lv-icon" style="<?php echo e($lv['css']); ?>"><i class="bi <?php echo e($lv['icon']); ?>"></i></div>
                                    <div>
                                        <h6 class="fw-bold mb-0"><?php echo e($lv['title']); ?> <span class="setting-key"><?php echo e($lv['key']); ?></span></h6>
                                        <p class="text-muted small mb-0"><?php echo e($lv['desc']); ?></p>
                                    </div>
                                </div>
                                <div class="lv-preview" style="background: <?php echo e($lv['bg']); ?>;">
                                    <img src="<?php echo e($lvPreview ?: brand_logo()); ?>"
                                         id="lv-preview-<?php echo e($lv['key']); ?>"
                                         alt="<?php echo e($lv['title']); ?> preview"
                                         style="max-height:64px;max-width:80%;object-fit:contain;">
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap mt-3">
                                    <?php $lvRawId = 'raw_input_' . $lv['key']; ?>
                                    <input type="file" class="d-none" id="<?php echo e($lvRawId); ?>"
                                           name="settings[<?php echo e($lv['key']); ?>]"
                                           accept=".png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml"
                                           data-preview="lv-preview-<?php echo e($lv['key']); ?>"
                                           data-target-hidden="cropped_input_<?php echo e($lv['key']); ?>"
                                           data-crop-ratio=""
                                           data-label="<?php echo e($lv['title']); ?>">
                                    <input type="file" class="d-none" id="cropped_input_<?php echo e($lv['key']); ?>"
                                           name="settings[cropped_input_<?php echo e($lv['key']); ?>]">
                                    <label for="<?php echo e($lvRawId); ?>" class="file-choose">
                                        <i class="bi bi-upload"></i> Choose File
                                    </label>
                                    <?php if($lvSetting): ?>
                                        <label for="<?php echo e($lvRawId); ?>" class="file-choose">
                                            <i class="bi bi-crop"></i> Crop &amp; Upload
                                        </label>
                                    <?php endif; ?>
                                    <span class="file-label" id="cms-file-label-<?php echo e($lv['key']); ?>">
                                        <?php echo e($lvSetting ? 'Current: ' . $lvValue : 'No file chosen'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials._cms_save_bar', ['label' => 'Save Logos'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>


<?php echo $__env->make('partials._cms_cropper', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* ── Live preview & label update on raw file pick + open cropper ── */
    document.querySelectorAll('input[type=file][data-preview]').forEach(function (el) {
        el.addEventListener('change', function () {
            if (!el.files || !el.files[0]) return;
            const file = el.files[0];
            const key = el.id.replace('raw_input_', '');
            const labelEl = document.getElementById('cms-file-label-' + key);
            if (labelEl) {
                labelEl.textContent = '\u2714 ' + file.name;
            }
            const previewId = el.dataset.preview;
            const preview   = document.getElementById(previewId);
            if (preview) {
                const url = URL.createObjectURL(file);
                preview.onload = function () { URL.revokeObjectURL(url); };
                preview.src = url;
                preview.style.display = '';
            }
            openCmsCropper(el);
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/superadmin/settings/system/category/logo.blade.php ENDPATH**/ ?>