<?php $__env->startSection('title', 'Homepage Editor — Gallery'); ?>

<?php $__env->startSection('content'); ?>

<div class="branding-layout">
<?php echo $__env->make('partials.superadmin-branding-nav', ['page' => 'homepage'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Gallery</h1>
        <p class="ph-sub">Photos and labels shown on the public gallery.</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(url('/')); ?>" target="_blank" class="btn btn-ghost"><i class="bi bi-box-arrow-up-right"></i>View live homepage</a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('superadmin.settings.homepage.update')); ?>" id="homepageForm">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <input type="hidden" name="editing_section" value="gallery">
    <input type="hidden" name="gallery_items" id="galleryItemsJson">

    <div class="cms-grid cms-grid-single">
        <div class="setting-group-card">
            <div class="setting-group-header">
                <i class="bi bi-images"></i>
                Gallery
                <span class="sg-sub">Photos and labels shown on the public gallery.</span>
            </div>
            <div class="setting-group-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-gallery_title">
                                Section title
                                <span class="setting-key">gallery.title</span>
                            </label>
                            <input
                                type="text"
                                class="setting-input"
                                id="setting-gallery_title"
                                name="gallery_title"
                                value="<?php echo e($data['gallery']['title'] ?? 'Your campus. Your space.'); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-gallery_sub">
                                Subtitle
                                <span class="setting-key">gallery.subtitle</span>
                            </label>
                            <input
                                type="text"
                                class="setting-input"
                                id="setting-gallery_sub"
                                name="gallery_sub"
                                value="<?php echo e($data['gallery']['sub'] ?? 'Explore the venues and spaces our community reserves every day.'); ?>"
                            >
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <?php $__currentLoopData = range(0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hnSlot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $hnSlotItem = $data['gallery']['items'][$hnSlot] ?? []; ?>
                        <div class="col-md-6">
                            <div class="setting-field">
                                <label class="setting-label">
                                    Slot <?php echo e($hnSlot + 1); ?> — image
                                    <span class="setting-key">gallery.items[<?php echo e($hnSlot); ?>].image</span>
                                </label>
                                <div class="input-group gallery-input-group">
                                    <input
                                        type="text"
                                        class="setting-input gallery-img-input"
                                        name="gallery_image_<?php echo e($hnSlot + 1); ?>"
                                        data-slot="<?php echo e($hnSlot); ?>"
                                        value="<?php echo e(old('gallery_image_' . ($hnSlot + 1), ($hnSlotItem['image'] ?? ''))); ?>"
                                        placeholder="<?php echo e($availableImages ? ($availableImages[0]['url'] ?? '') : 'uploads/facilities/photo.jpg'); ?>"
                                    >
                                    <button type="button" class="btn btn-ghost gallery-picker-btn" data-slot="<?php echo e($hnSlot); ?>" title="Pick from facility photos">
                                        <i class="bi bi-images"></i>
                                    </button>
                                </div>
                                <div class="gallery-img-preview mt-1" data-slot="<?php echo e($hnSlot); ?>"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="row g-3 mt-1">
                    <?php $__currentLoopData = range(0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $capSlot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="setting-field">
                                <label class="setting-label" for="setting-gallery_caption_<?php echo e($capSlot + 1); ?>">
                                    Caption — Image <?php echo e($capSlot + 1); ?>

                                    <span class="setting-key">gallery.items[<?php echo e($capSlot); ?>].caption</span>
                                </label>
                                <input
                                    type="text"
                                    class="setting-input"
                                    id="setting-gallery_caption_<?php echo e($capSlot + 1); ?>"
                                    name="gallery_caption_<?php echo e($capSlot + 1); ?>"
                                    value="<?php echo e($data['gallery']['items'][$capSlot]['caption'] ?? ($capSlot === 0 ? 'Campus grounds & stands' : ($capSlot === 1 ? 'Event spaces' : ($capSlot === 2 ? 'Platform preview' : 'Dashboard & analytics')))); ?>"
                                >
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="setting-field mt-3">
                    <label class="setting-label" for="setting-gallery_note">
                        Gallery note
                        <span class="setting-key">gallery.note</span>
                    </label>
                    <input
                        type="text"
                        class="setting-input"
                        id="setting-gallery_note"
                        name="gallery_note"
                        value="<?php echo e($data['gallery']['note'] ?? 'Click any image to enlarge. Photos captured on campus facilities available for reservation.'); ?>"
                    >
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials._cms_save_bar', ['label' => 'Save Gallery'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</form>


<div class="modal fade" id="galleryPickerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-images me-2"></i>Pick a facility photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="galleryPickerGrid" class="row g-3"></div>
                <p id="galleryPickerEmpty" class="text-muted mb-0 d-none">No facility photos found in public/uploads/facilities.</p>
            </div>
        </div>
    </div>
</div>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    var INITIAL = <?php echo json_encode($data, 15, 512) ?>;
    var items = (INITIAL && INITIAL.gallery && INITIAL.gallery.items) || [
        { caption: 'Campus grounds & stands', image: null },
        { caption: 'Event spaces', image: null },
        { caption: 'Platform preview', image: null },
        { caption: 'Dashboard & analytics', image: null }
    ];
    while (items.length < 4) items.push({ caption: '', image: null });

    var AVAILABLE_IMAGES = <?php echo json_encode($availableImages ?? [], 15, 512) ?>;
    var pickerSlot = null;

    function syncGallery() {
        for (var i = 0; i < 4; i++) {
            var cap = document.querySelector('input[name=gallery_caption_' + (i + 1) + ']');
            var img = document.querySelector('input[name=gallery_image_' + (i + 1) + ']');
            var v = img && img.value ? img.value.trim() : null;
            items[i] = { caption: cap ? cap.value : '', image: v };
            var preview = document.querySelector('.gallery-img-preview[data-slot="' + i + '"]');
            if (preview) {
                preview.innerHTML = v
                    ? '<img src="' + v + '" alt="" style="max-height:72px;max-width:100%;border-radius:6px;">'
                    : '<small class="text-muted">Default image</small>';
            }
        }
        document.getElementById('galleryItemsJson').value = JSON.stringify(items);
    }

    document.querySelectorAll('input[name^=gallery_caption_], .gallery-img-input').forEach(function (el) {
        el.addEventListener('input', syncGallery);
    });

    document.querySelectorAll('.gallery-picker-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            pickerSlot = parseInt(btn.getAttribute('data-slot'), 10);
            var grid = document.getElementById('galleryPickerGrid');
            var empty = document.getElementById('galleryPickerEmpty');
            grid.innerHTML = '';
            if (!AVAILABLE_IMAGES.length) { empty.classList.remove('d-none'); }
            else {
                empty.classList.add('d-none');
                AVAILABLE_IMAGES.forEach(function (img) {
                    var col = document.createElement('div');
                    col.className = 'col-6 col-md-4 col-lg-3';
                    col.innerHTML =
                        '<div class="border rounded p-1" style="cursor:pointer;">' +
                        '<img src="' + img.url + '" alt="" style="width:100%;height:90px;object-fit:cover;border-radius:4px;">' +
                        '<div class="form-check mt-1 px-1"><input class="form-check-input picker-radio" type="radio" name="galleryPickerRadio" value="' + img.url + '"></div>' +
                        '</div>';
                    col.querySelector('div').addEventListener('click', function (e) {
                        if (e.target.tagName !== 'INPUT') {
                            col.querySelector('.picker-radio').checked = true;
                        }
                    });
                    grid.appendChild(col);
                });
            }
            new bootstrap.Modal(document.getElementById('galleryPickerModal')).show();
        });
    });

    document.getElementById('galleryPickerModal').addEventListener('hidden.bs.modal', function () {
        var chosen = document.querySelector('input[name=galleryPickerRadio]:checked');
        if (chosen && pickerSlot !== null) {
            var input = document.querySelector('input[name=gallery_image_' + (pickerSlot + 1) + ']');
            if (input) { input.value = chosen.value; syncGallery(); }
        }
        pickerSlot = null;
    });

    document.querySelectorAll('.gallery-img-preview').forEach(function (p) {
        p.addEventListener('dblclick', function () {
            var slot = p.getAttribute('data-slot');
            var input = document.querySelector('input[name=gallery_image_' + (parseInt(slot, 10) + 1) + ']');
            if (input) { input.value = ''; syncGallery(); }
        });
    });

    syncGallery();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/superadmin/settings/homepage/category/gallery.blade.php ENDPATH**/ ?>