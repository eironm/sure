{{-- CMS Crop Modal + Cropper.js. Used by any category page with crop-able images
     (branding: system_logo / login_background; logo: logo_light / logo_white).
     File inputs opt in via: data-preview="lv-preview-KEY" data-target-hidden="cropped_input_KEY"
     data-crop-ratio="16:9" (optional) data-label="Human Label" onchange="openCmsCropper(this)". --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>

<div class="modal fade" id="cmsCropperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:760px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h5 class="fw-bold mb-0" id="cms-crop-title"><i class="bi bi-crop text-primary me-2"></i> Crop Image</h5>
                    <p class="text-muted small mb-0 mt-1" id="cms-crop-subtitle">Adjust the crop area to fit the required dimensions.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-2">
                <div id="cms-crop-area" style="max-height:420px;overflow:hidden;background:#111;border-radius:8px;">
                    <img id="cms-crop-img" src="" alt="Crop" style="display:block;max-width:100%;">
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-3 d-flex justify-content-between align-items-center">
                <span class="text-muted small" id="cms-crop-size-hint"></span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light px-4 fw-bold" id="cms-crop-cancel">Cancel</button>
                    <button type="button" class="btn btn-primary px-5 fw-bold" id="cms-crop-confirm">
                        <i class="bi bi-check-lg me-1"></i> Apply Crop
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let cmsCropper      = null;
    let cmsTargetHidden = null;
    let cmsCropRatio    = null;
    let cmsCropModal    = null;
    const cmsCropImg     = document.getElementById('cms-crop-img');
    const cmsCropModalEl = document.getElementById('cmsCropperModal');
    if (!cmsCropModalEl) return; // page has no crop inputs

    function getCmsCropModal() {
        if (!cmsCropModal) cmsCropModal = new bootstrap.Modal(cmsCropModalEl, { backdrop: 'static', keyboard: false });
        return cmsCropModal;
    }

    // File inputs call this from onchange="openCmsCropper(this)".
    window.openCmsCropper = function (input) {
        const file = input.files[0];
        if (!file) return;

        const allowedExts = ['png', 'jpg', 'jpeg', 'webp', 'svg'];
        const ext = (file.name.split('.').pop() || '').toLowerCase();
        if (!allowedExts.includes(ext)) {
            alert('Unsupported File Format: The file "' + file.name + '" is not accepted. Please select a PNG, JPG, WebP, or SVG image.');
            input.value = '';
            return;
        }
        if (file.size > 4 * 1024 * 1024) {
            const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
            alert('File Too Large: The selected image is ' + sizeMb + 'MB. Images must not exceed 4MB.');
            input.value = '';
            return;
        }

        cmsTargetHidden = document.getElementById(input.dataset.targetHidden);
        cmsCropRatio    = input.dataset.cropRatio; // '16:9' or undefined
        const cropLabel = input.dataset.label;
        const ratio     = cmsCropRatio === '16:9' ? (16 / 9) : NaN;

        document.getElementById('cms-crop-title').textContent    = 'Crop ' + cropLabel;
        document.getElementById('cms-crop-subtitle').textContent = cmsCropRatio
            ? 'Crop ratio locked to ' + cmsCropRatio + ' — recommended min 1280\u00d7720.'
            : 'Freely crop the image to the desired composition.';

        if (cmsCropper) { cmsCropper.destroy(); cmsCropper = null; }
        cmsCropImg.src = '';
        cmsCropImg.onload = null;

        const onShown = function () {
            cmsCropModalEl.removeEventListener('shown.bs.modal', onShown);
            cmsCropImg.onload = function () {
                if (cmsCropper) { cmsCropper.destroy(); cmsCropper = null; }
                cmsCropper = new Cropper(cmsCropImg, {
                    aspectRatio:      isNaN(ratio) ? NaN : ratio,
                    viewMode:         1,
                    dragMode:         'move',
                    autoCropArea:     1,
                    guides:           true,
                    cropBoxResizable: false,
                    background:       false,
                    ready: function () {
                        const sizeHint = document.getElementById('cms-crop-size-hint');
                        if (sizeHint) sizeHint.textContent = cmsCropRatio
                            ? 'Locked to ' + cmsCropRatio + ' (output 1280\u00d7720 for backgrounds).'
                            : 'Drag to reposition the crop box.';
                    },
                });
            };
            cmsCropImg.src = URL.createObjectURL(file);
        };
        cmsCropModalEl.addEventListener('shown.bs.modal', onShown);
        getCmsCropModal().show();
    };

    document.getElementById('cms-crop-confirm').addEventListener('click', function () {
        if (!cmsCropper || !cmsTargetHidden) return;
        const btn = document.getElementById('cms-crop-confirm');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing\u2026';

        const w        = cmsCropRatio === '16:9' ? 1280 : undefined;
        const h        = cmsCropRatio === '16:9' ? 720  : undefined;
        const isLogo   = cmsTargetHidden.id.indexOf('logo') !== -1;
        const blobType = isLogo ? 'image/png' : 'image/jpeg';

        cmsCropper.getCroppedCanvas({ width: w, height: h, imageSmoothingQuality: 'high' })
            .toBlob(function (blob) {
                const ext    = isLogo ? 'png' : 'jpg';
                const name   = 'cms_crop_' + Date.now() + '.' + ext;
                const crFile = new File([blob], name, { type: blobType });
                const dt     = new DataTransfer();
                dt.items.add(crFile);
                cmsTargetHidden.files = dt.files;
                cmsTargetHidden.dispatchEvent(new Event('change', { bubbles: true }));

                const labelKey = cmsTargetHidden.id.replace('cropped_input_', '');
                const labelEl  = document.getElementById('cms-file-label-' + labelKey);
                if (labelEl) labelEl.textContent = '\u2714 ' + name;

                const previewEl = document.getElementById('lv-preview-' + labelKey);
                if (previewEl) {
                    const previewUrl = URL.createObjectURL(crFile);
                    previewEl.onload = function () { URL.revokeObjectURL(previewUrl); };
                    previewEl.src = previewUrl;
                    previewEl.style.display = '';
                }

                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Apply Crop';
                getCmsCropModal().hide();
            }, blobType, isLogo ? undefined : 0.92);
    });

    document.getElementById('cms-crop-cancel').addEventListener('click', function () {
        getCmsCropModal().hide();
    });

    cmsCropModalEl.addEventListener('hidden.bs.modal', function () {
        if (cmsCropper) { cmsCropper.destroy(); cmsCropper = null; }
    });
});
</script>
