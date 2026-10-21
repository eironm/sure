
<?php
    $languages = legal_doc($doc);
?>

<?php if(empty($languages)): ?>
    <div class="legal-scroll-body h-100 w-100 lang-scroller" data-parent="<?php echo e($slug); ?>">
        <p class="text-muted">This document has not been configured yet.</p>
    </div>
<?php else: ?>
    
    <?php if(count($languages) > 1): ?>
        <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom bg-light legal-lang-bar">
            <span class="small fw-bold text-muted"><i class="bi bi-translate me-1"></i>Language</span>
            <ul class="nav nav-pills nav-pills-sm flex-wrap gap-1" role="tablist">
                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 px-3 <?php echo e($i === 0 ? 'active' : ''); ?>"
                                data-bs-toggle="pill"
                                data-bs-target="#scroll-<?php echo e($slug); ?>-<?php echo e($lang['code']); ?>"
                                type="button" role="tab"><?php echo e($lang['label']); ?></button>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="tab-content flex-grow-1 overflow-hidden" style="background:#fff;">
        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="tab-pane fade <?php echo e($i === 0 ? 'show active' : ''); ?> h-100"
                 id="scroll-<?php echo e($slug); ?>-<?php echo e($lang['code']); ?>" role="tabpanel">
                <div class="legal-scroll-body legal-markdown h-100 w-100 lang-scroller" data-parent="<?php echo e($slug); ?>">
                    <p class="text-muted small mb-3">
                        <strong>Effective Date:</strong> <?php echo e(date('F d, Y')); ?> &nbsp;|&nbsp;
                        <strong><?php echo e(branding('system_name', 'Platform')); ?></strong>
                    </p>
                    <?php echo $lang['html']; ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/legal/_document.blade.php ENDPATH**/ ?>