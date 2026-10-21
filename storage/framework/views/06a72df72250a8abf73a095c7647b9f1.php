
<?php
    $hdrMode = branding('report_header_mode', 'builder');
    $hdrBanner = branding('report_header_image');
    $hdrBannerH = branding('report_header_image_height', '90');

    $showLeftLogo = branding('report_header_left_logo_show', '1') == '1';
    $leftLogoPath = branding('report_header_left_logo');
    $leftLogoSize = (int) branding('report_header_left_logo_size', '60');
    $leftLogoShape = branding('report_header_left_logo_shape', 'circle');

    $showRightLogo = branding('report_header_right_logo_show', '1') == '1';
    $rightLogoPath = branding('report_header_right_logo');
    $rightLogoSize = (int) branding('report_header_right_logo_size', '60');
    $rightLogoShape = branding('report_header_right_logo_shape', 'circle');

    $ruleStyle = branding('report_header_divider_style', 'double');
    $ruleColor = branding('report_header_divider_color', '#0f2b48');

    $hLine1 = branding('report_header_line1', 'Republic of the Philippines');
    $hLine2 = branding('report_header_line2', branding('institution_name', 'STATE UNIVERSITY SYSTEM'));
    $hLine3 = branding('report_header_line3', branding('office_name', 'OFFICE OF PHYSICAL PLANT & AUXILIARY SERVICES'));
    $hLine4 = branding('report_header_line4', branding('campus_address', 'University Boulevard, Main Campus, Metro Manila, Philippines'));
    $hLine5 = branding('report_header_line5', 'Tel: (02) 8911-2000 • Web: ' . url('/'));
?>

<?php if($hdrMode === 'image' && $hdrBanner): ?>
    <?php
        $hdrFit = branding('report_header_image_fit', 'contain');
        $hdrEdge = branding('report_header_edge', 'flush');
        $hdrOffsetY = (int) branding('report_header_offset_y', '0');
        $hdrPosX = (float) branding('report_header_image_pos_x', '0');
        $hdrPosY = (float) branding('report_header_image_pos_y', '0');
        $hdrScale = (float) branding('report_header_image_scale', '1');
        $isHdrFlush = $hdrEdge === 'flush';
    ?>
    <div class="doc-header-banner-wrap <?php echo e($isHdrFlush ? 'is-flush' : ''); ?>" style="height: <?php echo e($hdrBannerH); ?>px; overflow: hidden; position: relative; --hdr-offset-y: <?php echo e($hdrOffsetY); ?>px; <?php echo e($isHdrFlush ? 'width: calc(100% + 32mm); margin-left: -16mm; margin-right: -16mm; margin-top: calc(-14mm + ' . $hdrOffsetY . 'px); margin-bottom: 0.65rem;' : 'width: 100%; margin-top: ' . $hdrOffsetY . 'px; margin-bottom: 0.65rem;'); ?>">
        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; transform: translate(<?php echo e($hdrPosX); ?>px, <?php echo e($hdrPosY); ?>px) scale(<?php echo e($hdrScale); ?>); transform-origin: center center;">
            <img src="<?php echo e(asset($hdrBanner)); ?>" alt="Letterhead" class="doc-header-banner-img" style="width: 100%; height: 100%; <?php echo e($hdrFit === 'width' ? 'object-fit: cover;' : 'object-fit: contain;'); ?> display: block; max-width: none; max-height: none;">
        </div>
    </div>
<?php else: ?>
    <div class="doc-header">
        
        <?php if($showLeftLogo): ?>
            <div class="seal-wrap" style="width: <?php echo e(max($leftLogoSize, 64)); ?>px;">
                <?php if($leftLogoShape === 'none'): ?>
                    <img src="<?php echo e($leftLogoPath ? asset($leftLogoPath) : asset('assets/logo-dark.png')); ?>" class="seal-img-raw" style="max-width: <?php echo e($leftLogoSize); ?>px; max-height: <?php echo e($leftLogoSize); ?>px;">
                <?php else: ?>
                    <div class="seal-circle has-ring" style="width: <?php echo e($leftLogoSize); ?>px; height: <?php echo e($leftLogoSize); ?>px;">
                        <?php if($leftLogoPath): ?>
                            <img src="<?php echo e(asset($leftLogoPath)); ?>" class="seal-img" alt="Left Seal">
                        <?php else: ?>
                            <div class="seal-inner-circle">
                                <i class="bi bi-bank"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="seal-label">OFFICIAL</div>
            </div>
        <?php endif; ?>

        
        <div class="doc-header-center">
            <?php if($hLine1): ?>
                <div class="doc-rep-ph" style="font-size: <?php echo e(branding('report_header_line1_font_size', '9.5px')); ?>; font-weight: <?php echo e(branding('report_header_line1_font_weight', '700')); ?>; color: <?php echo e(branding('report_header_line1_color', '#334155')); ?>; text-transform: <?php echo e(branding('report_header_line1_transform', 'uppercase')); ?>; letter-spacing: 0.12em;">
                    <?php echo e($hLine1); ?>

                </div>
            <?php endif; ?>

            <?php if($hLine2): ?>
                <div class="doc-inst-name" style="font-size: <?php echo e(branding('report_header_line2_font_size', '16.5px')); ?>; font-weight: <?php echo e(branding('report_header_line2_font_weight', '900')); ?>; color: <?php echo e(branding('report_header_line2_color', '#0f2b48')); ?>; text-transform: <?php echo e(branding('report_header_line2_transform', 'none')); ?>; line-height: 1.2; margin: 2px 0;">
                    <?php echo e($hLine2); ?>

                </div>
            <?php endif; ?>

            <?php if($hLine3): ?>
                <div class="doc-dept-name" style="font-size: <?php echo e(branding('report_header_line3_font_size', '10.5px')); ?>; font-weight: <?php echo e(branding('report_header_line3_font_weight', '600')); ?>; color: <?php echo e(branding('report_header_line3_color', '#475569')); ?>; text-transform: <?php echo e(branding('report_header_line3_transform', 'none')); ?>; margin-bottom: 3px;">
                    <?php echo e($hLine3); ?>

                </div>
            <?php endif; ?>

            <?php if($ruleStyle !== 'none'): ?>
                <div class="rule-<?php echo e($ruleStyle); ?>" style="border-color: <?php echo e($ruleColor); ?> !important; border-top-color: <?php echo e($ruleColor); ?> !important; border-bottom-color: <?php echo e($ruleColor); ?> !important; background-color: <?php echo e($ruleColor); ?> !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;"></div>
            <?php endif; ?>

            <?php if($hLine4): ?>
                <div class="doc-address-line" style="font-size: <?php echo e(branding('report_header_line4_font_size', '8.5px')); ?>; font-weight: <?php echo e(branding('report_header_line4_font_weight', '400')); ?>; color: <?php echo e(branding('report_header_line4_color', '#475569')); ?>;">
                    <?php echo e($hLine4); ?>

                </div>
            <?php endif; ?>

            <?php if($hLine5): ?>
                <div class="doc-address-line" style="font-size: <?php echo e(branding('report_header_line5_font_size', '8.5px')); ?>; font-weight: <?php echo e(branding('report_header_line5_font_weight', '400')); ?>; color: <?php echo e(branding('report_header_line5_color', '#475569')); ?>;">
                    <?php echo e($hLine5); ?>

                </div>
            <?php endif; ?>
        </div>

        
        <?php if($showRightLogo): ?>
            <div class="seal-wrap" style="width: <?php echo e(max($rightLogoSize, 64)); ?>px;">
                <?php if($rightLogoShape === 'none'): ?>
                    <img src="<?php echo e($rightLogoPath ? asset($rightLogoPath) : asset('assets/logo-dark.png')); ?>" class="seal-img-raw" style="max-width: <?php echo e($rightLogoSize); ?>px; max-height: <?php echo e($rightLogoSize); ?>px;">
                <?php else: ?>
                    <div class="seal-circle has-ring" style="width: <?php echo e($rightLogoSize); ?>px; height: <?php echo e($rightLogoSize); ?>px;">
                        <?php if($rightLogoPath): ?>
                            <img src="<?php echo e(asset($rightLogoPath)); ?>" class="seal-img" alt="Right Seal">
                        <?php else: ?>
                            <div class="seal-inner-circle">
                                <i class="bi bi-shield-check"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="seal-label">SEAL</div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/components/report-letterhead.blade.php ENDPATH**/ ?>