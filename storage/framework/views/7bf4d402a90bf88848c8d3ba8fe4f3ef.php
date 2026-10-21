
<?php
    $isFavicon = ($setting->key ?? '') === 'favicon';
    $needsCrop = in_array($setting->key ?? '', ['system_logo', 'login_background'], true);
    $cropRatio = ($setting->key ?? '') === 'login_background' ? '16:9' : null;
    $accept    = $isFavicon
        ? '.ico,.png,.jpg,.jpeg,.webp,.svg'
        : '.png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml';
    $showHint = $showHint ?? false;
?>

<div class="setting-field <?php if($setting->type === 'boolean'): ?> is-boolean <?php endif; ?>">
    <label class="setting-label" for="setting-<?php echo e($setting->key); ?>">
        <?php echo e($setting->label ?? ucwords(str_replace('_', ' ', $setting->key))); ?>

        <span class="setting-key"><?php echo e($setting->key); ?></span>
    </label>

    <?php if($setting->type === 'textarea'): ?>
        <textarea
            class="setting-input"
            id="setting-<?php echo e($setting->key); ?>"
            name="settings[<?php echo e($setting->key); ?>]"
            rows="3"
        ><?php echo e(old("settings.{$setting->key}", $setting->value)); ?></textarea>

    <?php elseif($setting->type === 'color'): ?>
        <div class="color-preview">
            <input
                type="color"
                class="color-swatch"
                id="color-swatch-<?php echo e($setting->key); ?>"
                value="<?php echo e(($setting->value && preg_match('/^#[0-9a-fA-F]{6}$/', $setting->value)) ? $setting->value : '#000000'); ?>"
                oninput="document.getElementById('setting-<?php echo e($setting->key); ?>').value = this.value; window.heroSwatchSync ? window.heroSwatchSync('<?php echo e($setting->key); ?>', this.value) : null;"
            >
            <input
                type="text"
                class="setting-input color-hex-input"
                id="setting-<?php echo e($setting->key); ?>"
                name="settings[<?php echo e($setting->key); ?>]"
                value="<?php echo e(old("settings.{$setting->key}", $setting->value)); ?>"
                placeholder="#000000"
                oninput="document.getElementById('color-swatch-<?php echo e($setting->key); ?>').value = this.value"
            >
        </div>

    <?php elseif($setting->type === 'image'): ?>
        <?php if($isFavicon): ?>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input
                    type="file"
                    class="d-none"
                    id="setting-<?php echo e($setting->key); ?>"
                    name="settings[<?php echo e($setting->key); ?>]"
                    accept="<?php echo e($accept); ?>"
                    onchange="document.getElementById('cms-file-label-<?php echo e($setting->key); ?>').innerText = this.files.length ? '\u2714 ' + this.files[0].name : 'No file chosen'"
                >
                <label for="setting-<?php echo e($setting->key); ?>" class="file-choose">
                    <i class="bi bi-upload"></i> Choose File
                </label>
                <span class="file-label" id="cms-file-label-<?php echo e($setting->key); ?>">No file chosen</span>
            </div>
            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Supported formats: <code>.ico</code>, <code>.png</code>, <code>.svg</code>, <code>.webp</code>, <code>.jpg</code>.</p>

        <?php elseif($needsCrop): ?>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input
                    type="file"
                    class="d-none"
                    id="raw_input_<?php echo e($setting->key); ?>"
                    name="settings[<?php echo e($setting->key); ?>]"
                    accept="<?php echo e($accept); ?>"
                    data-preview="lv-preview-<?php echo e($setting->key); ?>"
                    data-target-hidden="cropped_input_<?php echo e($setting->key); ?>"
                    data-crop-ratio="<?php echo e($cropRatio); ?>"
                    data-label="<?php echo e($setting->key === 'system_logo' ? 'System Logo' : 'Login Background'); ?>"
                    onchange="openCmsCropper(this)"
                >
                <input
                    type="file"
                    class="d-none"
                    id="cropped_input_<?php echo e($setting->key); ?>"
                    name="settings[cropped_input_<?php echo e($setting->key); ?>]"
                >
                <label for="raw_input_<?php echo e($setting->key); ?>" class="file-choose">
                    <i class="bi bi-upload"></i> Choose File
                </label>
                <?php if($setting->value): ?>
                    <label for="raw_input_<?php echo e($setting->key); ?>" class="file-choose">
                        <i class="bi bi-crop"></i> Crop &amp; Upload
                    </label>
                <?php endif; ?>
                <span class="file-label" id="cms-file-label-<?php echo e($setting->key); ?>">
                    <?php echo e($setting->value ? 'Current: ' . $setting->value : 'No file chosen'); ?>

                </span>
            </div>
            <?php if($setting->value): ?>
                <div class="mt-2">
                    <img src="<?php echo e(asset($setting->value)); ?>" alt="Current <?php echo e($setting->key); ?>"
                         id="lv-preview-<?php echo e($setting->key); ?>"
                         style="max-height:90px;border-radius:8px;border:1px solid var(--line);">
                </div>
            <?php endif; ?>
            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Recommended image types: PNG, JPG, WebP.</p>

        <?php else: ?>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input
                    type="file"
                    class="d-none"
                    id="setting-<?php echo e($setting->key); ?>"
                    name="settings[<?php echo e($setting->key); ?>]"
                    accept="<?php echo e($accept); ?>"
                    onchange="document.getElementById('cms-file-label-<?php echo e($setting->key); ?>').innerText = this.files.length ? '\u2714 ' + this.files[0].name : 'No file chosen'"
                >
                <label for="setting-<?php echo e($setting->key); ?>" class="file-choose">
                    <i class="bi bi-upload"></i> Choose File
                </label>
                <span class="file-label" id="cms-file-label-<?php echo e($setting->key); ?>">No file chosen</span>
            </div>
            <?php if($setting->value): ?>
                <div class="mt-2">
                    <img src="<?php echo e(asset($setting->value)); ?>" alt="Current <?php echo e($setting->key); ?>"
                         style="max-height:72px;border-radius:6px;border:1px solid var(--line);">
                </div>
            <?php endif; ?>
        <?php endif; ?>

    <?php elseif($setting->type === 'boolean'): ?>
        <div class="form-check form-switch premium-switch mb-0">
            <input type="hidden" name="settings[<?php echo e($setting->key); ?>]" value="0">
            <input
                class="form-check-input"
                type="checkbox"
                role="switch"
                id="setting-<?php echo e($setting->key); ?>"
                name="settings[<?php echo e($setting->key); ?>]"
                value="1"
                <?php echo e(old("settings.{$setting->key}", $setting->value) === '1' ? 'checked' : ''); ?>

            >
        </div>

    <?php else: ?>
        <input
            type="<?php echo e(in_array($setting->type, ['text','email','number','url','tel','password'], true) ? $setting->type : 'text'); ?>"
            class="setting-input"
            id="setting-<?php echo e($setting->key); ?>"
            name="settings[<?php echo e($setting->key); ?>]"
            value="<?php echo e(old("settings.{$setting->key}", $setting->value)); ?>"
            placeholder="<?php echo e($setting->label ?? ucwords(str_replace('_', ' ', $setting->key))); ?>"
        >
    <?php endif; ?>

    <?php if($showHint && $setting->type !== 'image' && ! empty($setting->hint)): ?>
        <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i><?php echo e($setting->hint); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/partials/_cms_field.blade.php ENDPATH**/ ?>