<?php $__env->startSection('title', 'Report Letterhead & Footer CMS'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* ── Report Letterhead CMS Studio ── */
    .cms-studio {
        color: #1e293b;
    }
    .cms-studio-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }
    .cms-studio-title {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        color: #0f172a;
        margin: 0;
    }
    .cms-studio-sub {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    /* Studio Card */
    .studio-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .studio-panel-title {
        font-size: 0.825rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Word-style Segmented Buttons */
    .word-segmented {
        display: inline-flex;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .word-segmented-btn {
        border: none;
        background: transparent;
        padding: 0.35rem 0.85rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #475569;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .word-segmented-btn.active {
        background: #ffffff;
        color: #0f172a;
        font-weight: 700;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.08);
    }

    /* Word-style Toolbar Pill Group */
    .word-toolbar-group {
        display: inline-flex;
        align-items: center;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 2px;
        gap: 2px;
    }
    .word-tool-btn {
        border: none;
        background: transparent;
        padding: 0.2rem 0.45rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 4px;
        color: #475569;
        cursor: pointer;
        transition: all 0.12s ease;
        line-height: 1;
    }
    .word-tool-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .word-tool-btn.active {
        background: #0f2b48;
        color: #ffffff;
    }

    /* Line item card */
    .line-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem;
        margin-bottom: 0.65rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .line-card:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        background: #ffffff;
    }
    .line-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.4rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
    }

    /* Color Swatch Button */
    .color-swatch-picker {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .swatch-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 1px #cbd5e1;
        cursor: pointer;
        transition: transform 0.12s;
    }
    .swatch-dot:hover {
        transform: scale(1.18);
    }

    /* Seal Dropzone */
    .seal-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1rem 0.75rem;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.15s ease;
        position: relative;
    }
    .seal-dropzone:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }
    .seal-thumb-img {
        max-width: 64px;
        max-height: 64px;
        object-fit: contain;
        display: block;
        margin: 0 auto 0.4rem;
    }

    /* Sticky Canvas Preview (A4 Paper Container) */
    .a4-desk {
        background: #475569;
        background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 16px 16px;
        border-radius: 16px;
        padding: 24px 16px;
        position: sticky;
        top: 20px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    .a4-sheet-preview {
        background: #ffffff;
        border-radius: 3px;
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.35);
        padding: 0; /* Zero padding on paper so banners can touch top and bottom edges directly */
        margin: 0 auto;
        min-height: 297mm;
        width: 100%;
        max-width: 210mm;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #0f172a;
        transform-origin: top center;
        position: relative;
        overflow: hidden;
    }
    #a4HeaderSlot {
        width: 100%;
        transition: margin 0.1s ease;
        flex-shrink: 0;
    }
    .a4-builder-header-wrap {
        padding: 14mm 14mm 0 14mm;
    }
    #a4BodyContent {
        padding: 6px 14mm 14px 14mm;
        flex-grow: 1;
        min-height: 120mm;
    }
    #a4FooterSlot {
        width: 100%;
        transition: margin 0.1s ease;
        flex-shrink: 0;
    }
    .a4-builder-footer-wrap {
        padding: 0 14mm 12mm 14mm;
    }
    .banner-canvas-viewport.is-flush {
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    /* Live Header Preview Components */
    .preview-doc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
    }
    .preview-seal-box {
        text-align: center;
        flex-shrink: 0;
    }
    .preview-seal-circle {
        position: relative;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        box-sizing: border-box;
    }
    .preview-seal-circle.has-ring {
        border-radius: 50% !important;
        overflow: hidden !important; /* STRICT CLIPPING: nothing outside the circle */
        clip-path: circle(50% at 50% 50%) !important;
        -webkit-clip-path: circle(50% at 50% 50%) !important;
        -webkit-mask-image: -webkit-radial-gradient(white, black);
        mask-image: radial-gradient(white, black);
        isolation: isolate;
        border: none !important;
    }
    /* Ring overlay: strictly positioned IN FRONT of the photo (z-index: 10) */
    .preview-seal-circle.has-ring::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid #0f2b48;
        box-shadow: inset 0 0 0 1.5px #ffffff, inset 0 0 0 2.5px #0f2b48;
        pointer-events: none;
        z-index: 10; /* Ensures ring is strictly in front of the photo */
        box-sizing: border-box;
    }
    .preview-seal-circle.has-ring .preview-seal-img {
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        border-radius: 50% !important;
        clip-path: circle(50% at 50% 50%) !important;
        -webkit-clip-path: circle(50% at 50% 50%) !important;
        position: relative;
        z-index: 1; /* Photo is strictly underneath the ring overlay (never at the front) */
        display: block;
    }
    .preview-seal-circle.no-ring {
        border-radius: 0 !important;
        overflow: visible !important;
        background: transparent !important;
        border: none !important;
    }
    .preview-seal-circle.no-ring::after {
        display: none !important;
    }
    .preview-seal-circle.no-ring .preview-seal-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 0;
        position: static;
        z-index: auto;
    }
    .preview-center-text {
        text-align: center;
        flex-grow: 1;
    }

    /* Divider Lines */
    .rule-double {
        border-top: 2px solid #0f2b48;
        border-bottom: 1px solid #0f2b48;
        height: 4px;
        margin: 5px 0 6px;
        box-sizing: border-box;
    }
    .rule-single {
        border-top: 1.5px solid #0f2b48;
        height: 0;
        margin: 5px 0 6px;
        box-sizing: border-box;
    }
    .rule-thick {
        border-top: 3px solid #0f2b48;
        height: 0;
        margin: 5px 0 6px;
        box-sizing: border-box;
    }
    .rule-none {
        display: none;
    }

    /* Live Footer Preview */
    .preview-doc-footer {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        justify-content: space-between;
        align-items: flex-start;
        font-size: 8px;
        color: #475569;
        line-height: 1.4;
        padding-top: 6px;
        border-top: 1px solid #cbd5e1;
        margin-top: 20px;
        width: 100%;
        box-sizing: border-box;
    }
    .preview-doc-footer > div {
        flex: 1 1 0%;
        box-sizing: border-box;
    }

    /* Preset template chip */
    .preset-chip {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.12s ease;
    }
    .preset-chip:hover {
        background: #ffffff;
        border-color: #2563eb;
        color: #1d4ed8;
    }

    /* ── Advanced Drag & Move Canvas Studio ── */
    .banner-canvas-viewport {
        position: relative;
        width: 100%;
        overflow: hidden;
        border-radius: 6px;
        background: #f8fafc;
        box-shadow: inset 0 0 0 1px #e2e8f0;
        cursor: grab;
        user-select: none;
        transition: box-shadow 0.15s ease;
    }
    .banner-canvas-viewport:hover {
        box-shadow: inset 0 0 0 1.5px #0284c7;
    }
    .banner-canvas-viewport.is-dragging-move {
        cursor: grabbing !important;
        box-shadow: inset 0 0 0 2px #0284c7 !important;
    }
    .banner-canvas-stage {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        transform-origin: center center;
        will-change: transform;
    }
    .banner-canvas-img {
        width: 100%;
        height: 100%;
        max-width: none;
        max-height: none;
        object-fit: cover;
        user-select: none;
        -webkit-user-drag: none;
        pointer-events: none;
        display: block;
    }
    .banner-canvas-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        border: 1.5px dashed transparent;
        transition: border-color 0.15s ease;
    }
    .banner-canvas-viewport:hover .banner-canvas-overlay,
    .banner-canvas-viewport.is-active-focus .banner-canvas-overlay {
        border-color: #0284c7;
    }
    .banner-move-hint {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(15, 43, 72, 0.8);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 9999px;
        backdrop-filter: blur(4px);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s ease;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }
    .banner-canvas-viewport:hover .banner-move-hint {
        opacity: 0.9;
    }
    .banner-canvas-viewport.is-dragging-move .banner-move-hint {
        opacity: 1 !important;
        background: rgba(2, 132, 199, 0.95) !important;
    }
    /* 4 Corner scale handles */
    .canvas-handle {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #ffffff;
        border: 2px solid #0284c7;
        border-radius: 2px;
        pointer-events: auto;
        opacity: 0;
        transition: opacity 0.15s ease, transform 0.1s ease;
        z-index: 20;
    }
    .canvas-handle:hover {
        transform: scale(1.35);
        background: #0284c7;
    }
    .banner-canvas-viewport:hover .canvas-handle {
        opacity: 1;
    }
    .handle-nw { top: -2px; left: -2px; cursor: nwse-resize; }
    .handle-ne { top: -2px; right: -2px; cursor: nesw-resize; }
    .handle-se { bottom: -2px; right: -2px; cursor: nwse-resize; }
    .handle-sw { bottom: -2px; left: -2px; cursor: nesw-resize; }

    /* Edge height resize bar */
    .canvas-edge-bar {
        position: absolute;
        left: 0;
        right: 0;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: ns-resize;
        pointer-events: auto;
        z-index: 22;
    }
    .bar-bottom { bottom: 0; }
    .bar-top { top: 0; }
    .edge-bar-pill {
        background: #0f2b48;
        color: #ffffff;
        font-size: 9.5px;
        font-weight: 700;
        padding: 1px 8px;
        border-radius: 9999px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        opacity: 0.7;
        transition: opacity 0.15s ease, background-color 0.15s ease;
    }
    .canvas-edge-bar:hover .edge-bar-pill {
        opacity: 1;
        background: #0284c7;
    }

    /* Floating HUD Toolbar */
    .banner-canvas-hud {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(15, 43, 72, 0.92);
        color: #ffffff;
        border-radius: 8px;
        padding: 3px 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        backdrop-filter: blur(6px);
        z-index: 25;
        opacity: 0;
        transform: translateY(-4px);
        transition: opacity 0.2s ease, transform 0.2s ease;
        pointer-events: auto;
    }
    .banner-canvas-viewport:hover .banner-canvas-hud,
    .banner-canvas-viewport.is-active-focus .banner-canvas-hud {
        opacity: 1;
        transform: translateY(0);
    }
    .hud-pill {
        font-size: 9px;
        font-weight: 700;
        color: #38bdf8;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .hud-divider {
        width: 1px;
        height: 12px;
        background: rgba(255,255,255,0.2);
    }
    .hud-btn {
        background: transparent;
        border: none;
        color: #f1f5f9;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        transition: background-color 0.12s ease;
    }
    .hud-btn:hover {
        background: rgba(255,255,255,0.15);
        color: #ffffff;
    }
    .hud-btn-action {
        background: #0284c7;
        color: #ffffff;
    }
    .hud-btn-action:hover {
        background: #0369a1;
    }
    .hud-btn-snap {
        background: #10b981;
        color: #ffffff;
    }
    .hud-btn-snap:hover {
        background: #059669;
    }
    .hud-zoom-val {
        font-size: 9.5px;
        font-weight: 700;
        color: #e2e8f0;
        min-width: 32px;
        text-align: center;
    }
    .btn-xs {
        padding: 0.18rem 0.5rem;
        font-size: 0.72rem;
        line-height: 1.3;
    }
</style>

<div class="cms-studio">

    
    <div id="cmsNoticeBanner" class="alert alert-danger alert-dismissible fade d-none rounded-4 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-start">
            <i class="bi bi-exclamation-octagon-fill text-danger fs-4 me-2 mt-1 flex-shrink-0"></i>
            <div>
                <strong id="cmsNoticeTitle" class="d-block mb-1">Invalid File</strong>
                <span id="cmsNoticeText" class="small"></span>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="dismissCmsNotice()" aria-label="Close"></button>
        </div>
    </div>

    
    <div class="cms-studio-header">
        <div>
            <h1 class="cms-studio-title">
                <i class="bi bi-file-earmark-richtext-fill text-primary me-2"></i> Report Letterhead &amp; Footer CMS
            </h1>
            <p class="cms-studio-sub">Word-style WYSIWYG letterhead designer. Real-time preview with zero HTML required.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('admin.reports.index')); ?>" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
                <i class="bi bi-arrow-left"></i> Reports Dashboard
            </a>
            <a href="<?php echo e(route('admin.reports.print')); ?>" target="_blank" class="btn btn-dark rounded-pill btn-sm px-3">
                <i class="bi bi-printer me-1"></i> Preview Print Document
            </a>
            <button type="button" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold shadow-sm" onclick="document.getElementById('letterheadForm').submit()">
                <i class="bi bi-save me-1"></i> Save Changes
            </button>
        </div>
    </div>

    
    <div class="studio-panel py-2 mb-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="small fw-bold text-muted text-uppercase" style="font-size: 0.72rem;">
                <i class="bi bi-lightning-charge-fill text-warning"></i> Load Preset Template:
            </span>
            <button type="button" class="preset-chip" onclick="loadPreset('suc')">
                <i class="bi bi-bank"></i> Philippine State University (SUC / CHED)
            </button>
            <button type="button" class="preset-chip" onclick="loadPreset('city')">
                <i class="bi bi-building"></i> Local Gov / City College
            </button>
            <button type="button" class="preset-chip" onclick="loadPreset('private')">
                <i class="bi bi-stars"></i> Private University / Academy
            </button>
            <button type="button" class="preset-chip" onclick="loadPreset('minimal')">
                <i class="bi bi-dash-lg"></i> Clean Minimalist
            </button>
        </div>
    </div>

    
    <form action="<?php echo e(auth()->user() && auth()->user()->isSuperAdmin() ? route('superadmin.settings.system.update') : route('admin.reports.letterhead-cms.update')); ?>"
          method="POST"
          enctype="multipart/form-data"
          id="letterheadForm">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <input type="hidden" name="remove_report_header_left_logo" id="remove_left_logo" value="0">
        <input type="hidden" name="remove_report_header_right_logo" id="remove_right_logo" value="0">
        <input type="hidden" name="remove_report_header_image" id="remove_header_image" value="0">
        <input type="hidden" name="remove_report_footer_image" id="remove_footer_image" value="0">

        <div class="row g-4">

            
            <div class="col-xl-6 col-lg-6">

                
                <div class="studio-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="studio-panel-title mb-0 border-0 p-0">
                            <i class="bi bi-card-heading text-primary"></i> 1. Letterhead Header Mode
                        </div>
                        <div class="word-segmented">
                            <button type="button" class="word-segmented-btn" id="btnModeBuilder" onclick="setHeaderMode('builder')">
                                <i class="bi bi-grid-1x2"></i> Word-Style Builder
                            </button>
                            <button type="button" class="word-segmented-btn" id="btnModeImage" onclick="setHeaderMode('image')">
                                <i class="bi bi-image"></i> Pre-Made Banner Image
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="settings[report_header_mode]" id="input_header_mode" value="<?php echo e(branding('report_header_mode', 'builder')); ?>">

                    
                    <div id="bannerImageSection" class="d-none">
                        <p class="small text-muted mb-2">
                            Upload your official institutional stationery header image (PNG with transparency recommended).
                        </p>
                        <div class="seal-dropzone" onclick="document.getElementById('file_header_image').click()">
                            <?php $hdrImg = branding('report_header_image'); ?>
                            <?php if($hdrImg): ?>
                                <img src="<?php echo e(asset($hdrImg)); ?>" class="seal-thumb-img w-100" style="max-height: 80px;" id="thumb_header_image">
                                <span class="badge bg-success mb-1">Current Banner Active</span>
                                <span class="text-muted d-block" style="font-size: 0.68rem;">Click to replace image</span>
                            <?php else: ?>
                                <i class="bi bi-cloud-arrow-up fs-2 text-muted d-block mb-1"></i>
                                <span class="small fw-bold text-dark d-block">Click or Drag &amp; Drop Header Banner Image</span>
                            <?php endif; ?>
                            <span class="badge bg-white text-secondary border mt-1" style="font-size: 0.65rem;">PNG, JPG, WebP, SVG &bull; Max 4MB</span>
                        </div>
                        <input type="file" name="settings[report_header_image]" id="file_header_image" class="d-none" accept=".png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewBannerFile(this)">

                        
                        <input type="hidden" name="settings[report_header_image_pos_x]" id="input_header_image_pos_x" value="<?php echo e(branding('report_header_image_pos_x', '0')); ?>">
                        <input type="hidden" name="settings[report_header_image_pos_y]" id="input_header_image_pos_y" value="<?php echo e(branding('report_header_image_pos_y', '0')); ?>">
                        <input type="hidden" name="settings[report_header_image_scale]" id="input_header_image_scale" value="<?php echo e(branding('report_header_image_scale', '1.0')); ?>">

                        
                        <div class="bg-light border rounded-3 p-3 mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="small fw-bold text-dark mb-0">
                                    <i class="bi bi-arrows-move text-primary me-1"></i> Header Canvas &amp; Position Controls
                                </label>
                                <div class="d-flex align-items-center gap-1">
                                    <button type="button" class="btn btn-xs btn-primary rounded-pill px-2 shadow-sm" onclick="autoFitBanner('header')" title="Fit banner to full printable width using its natural aspect ratio">
                                        <i class="bi bi-aspect-ratio me-1"></i> ⚡ Auto-Fit Width
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" onclick="resetBannerPosition('header')" title="Reset position to center">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                    <?php if($hdrImg): ?>
                                        <button type="button" class="btn btn-outline-danger btn-xs rounded-pill px-2" onclick="removeImage('header_image')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted fw-bold" style="font-size: 0.75rem;">Frame Height:</span>
                                    <div class="input-group input-group-sm" style="width: 85px;">
                                        <input type="number" name="settings[report_header_image_height]" id="input_header_image_height" value="<?php echo e(branding('report_header_image_height', '90')); ?>" min="25" max="400" class="form-control text-center py-0" oninput="setHeightFromInput('header', this.value)">
                                        <span class="input-group-text py-0 px-1 text-muted" style="font-size: 0.68rem;">px</span>
                                    </div>
                                </div>
                                <input type="range" id="range_header_height" min="25" max="350" value="<?php echo e(branding('report_header_image_height', '90')); ?>" class="form-range" oninput="setHeightFromRange('header', this.value)">
                            </div>

                            
                            <div class="mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted fw-bold" style="font-size: 0.75rem;">Scale / Zoom:</span>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1" onclick="adjustScale('header', -0.05)">-</button>
                                        <span class="badge bg-white text-dark border px-2 py-1" id="badge_header_zoom" style="font-size: 0.72rem;"><?php echo e(round((float)branding('report_header_image_scale', '1') * 100)); ?>%</span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1" onclick="adjustScale('header', 0.05)">+</button>
                                    </div>
                                </div>
                                <input type="range" id="range_header_scale" min="30" max="300" value="<?php echo e(round((float)branding('report_header_image_scale', '1') * 100)); ?>" class="form-range" oninput="setScaleFromRange('header', this.value)">
                            </div>

                            
                            <div class="p-2 border rounded-2 bg-white mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted fw-bold" style="font-size: 0.72rem;">Position Coordinates:</span>
                                    <span class="badge bg-light text-primary border" id="badge_header_pos" style="font-size: 0.68rem;">
                                        X: <?php echo e((int)branding('report_header_image_pos_x', '0')); ?>px, Y: <?php echo e((int)branding('report_header_image_pos_y', '0')); ?>px
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="small text-muted" style="font-size: 0.68rem;"><i class="bi bi-arrows-move text-primary"></i> Drag image on A4 to move or nudge:</span>
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2" onclick="nudgePos('header', -5, 0)" title="Nudge Left">◀</button>
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2" onclick="nudgePos('header', 0, -5)" title="Nudge Up">▲</button>
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2" onclick="nudgePos('header', 0, 5)" title="Nudge Down">▼</button>
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2" onclick="nudgePos('header', 5, 0)" title="Nudge Right">▶</button>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="p-2 border rounded-2 bg-white mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-dark fw-bold" style="font-size: 0.72rem;">
                                        <i class="bi bi-pin-map-fill text-primary me-1"></i> Document Page Placement
                                    </span>
                                    <span class="badge bg-primary text-white" id="badge_header_edge_status" style="font-size: 0.68rem;">
                                        <?php echo e(branding('report_header_edge', 'flush') === 'flush' ? (branding('report_header_offset_y', '0') == '0' ? '📌 Flush Top Edge (0px)' : 'Flush Top (' . branding('report_header_offset_y', '0') . 'px)') : 'Inside Margins'); ?>

                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-1 mb-2">
                                    <button type="button" class="btn btn-xs btn-primary rounded-pill px-2" id="btn_header_snap_flush" onclick="snapToEdge('header', 'flush')">
                                        <i class="bi bi-pin-angle-fill me-1"></i> 📌 Flush to Very Top (0px)
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" id="btn_header_snap_margin" onclick="snapToEdge('header', 'margin')">
                                        ↕ Inside Margins
                                    </button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted" style="font-size: 0.72rem;">Distance from Top Edge:</span>
                                    <span class="badge bg-light text-dark border" id="badge_header_offset_val" style="font-size: 0.68rem;"><?php echo e(branding('report_header_offset_y', '0')); ?>px</span>
                                </div>
                                <input type="range" id="range_header_offset" min="0" max="80" value="<?php echo e(branding('report_header_offset_y', '0')); ?>" class="form-range" oninput="setOffsetYFromRange('header', this.value)">
                                <input type="hidden" name="settings[report_header_edge]" id="input_header_edge" value="<?php echo e(branding('report_header_edge', 'flush')); ?>">
                                <input type="hidden" name="settings[report_header_offset_y]" id="input_header_offset_y" value="<?php echo e(branding('report_header_offset_y', '0')); ?>">
                            </div>

                            
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="small text-muted" style="font-size: 0.72rem;">Fit:</span>
                                    <select name="settings[report_header_image_fit]" id="sel_header_fit" class="form-select form-select-sm py-0 px-2" style="width: 140px; font-size: 0.72rem;" onchange="setFitMode('header', this.value)">
                                        <option value="contain" <?php echo e(branding('report_header_image_fit', 'contain') === 'contain' ? 'selected' : ''); ?>>Center Contain</option>
                                        <option value="width" <?php echo e(branding('report_header_image_fit', 'contain') === 'width' ? 'selected' : ''); ?>>Full Page Width (100%)</option>
                                    </select>
                                </div>

                                <div class="d-flex align-items-center gap-1">
                                    <span class="small text-muted" style="font-size: 0.72rem;">Presets:</span>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill" onclick="setHeightPreset('header', 60)">60px</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill" onclick="setHeightPreset('header', 95)">95px</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill" onclick="setHeightPreset('header', 140)">140px</button>
                                </div>
                            </div>

                            <div class="small text-primary mt-2 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                <i class="bi bi-hand-index-thumb"></i>
                                <span><strong>Direct Canvas Drag:</strong> Click &amp; drag the image inside the A4 sheet to move it freely, or drag corner handles to zoom!</span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="studio-panel" id="sealsPanel">
                    <div class="studio-panel-title">
                        <i class="bi bi-shield-check text-primary"></i> 2. Institutional Seals &amp; Logos
                    </div>

                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="small fw-bold text-dark mb-0">Left Seal (Primary)</label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="settings[report_header_left_logo_show]" id="chk_left_logo_show" value="1" <?php echo e(branding('report_header_left_logo_show', '1') == '1' ? 'checked' : ''); ?> onchange="updateLivePreview()">
                                    </div>
                                </div>

                                <div class="seal-dropzone" onclick="document.getElementById('file_left_logo').click()">
                                    <?php $leftLogo = branding('report_header_left_logo'); ?>
                                    <img src="<?php echo e($leftLogo ? asset($leftLogo) : asset('assets/logo-dark.png')); ?>" class="seal-thumb-img" id="thumb_left_logo">
                                    <span class="small fw-bold text-dark d-block">Change Left Seal</span>
                                    <span class="badge bg-white text-secondary border mt-1" style="font-size: 0.65rem;">PNG, JPG, WebP, SVG &bull; Max 4MB</span>
                                </div>
                                <input type="file" name="settings[report_header_left_logo]" id="file_left_logo" class="d-none" accept=".png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewSealFile(this, 'left')">

                                <div class="mt-2 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="small text-muted" style="font-size: 0.72rem;">Size:</span>
                                        <input type="range" name="settings[report_header_left_logo_size]" id="range_left_size" min="40" max="100" value="<?php echo e(branding('report_header_left_logo_size', '60')); ?>" class="form-range" style="width: 80px;" oninput="updateLivePreview()">
                                    </div>
                                    <select name="settings[report_header_left_logo_shape]" id="sel_left_shape" class="form-select form-select-sm" style="width: 90px; font-size: 0.72rem;" onchange="updateLivePreview()">
                                        <option value="circle" <?php echo e(branding('report_header_left_logo_shape', 'circle') === 'circle' ? 'selected' : ''); ?>>Gov Ring</option>
                                        <option value="none" <?php echo e(branding('report_header_left_logo_shape') === 'none' ? 'selected' : ''); ?>>No Ring</option>
                                    </select>
                                </div>
                                <?php if($leftLogo): ?>
                                    <button type="button" class="btn btn-link text-danger p-0 mt-1" style="font-size: 0.72rem;" onclick="removeImage('left_logo')">
                                        <i class="bi bi-trash"></i> Reset to Default
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="small fw-bold text-dark mb-0">Right Seal (Auxiliary)</label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="settings[report_header_right_logo_show]" id="chk_right_logo_show" value="1" <?php echo e(branding('report_header_right_logo_show', '1') == '1' ? 'checked' : ''); ?> onchange="updateLivePreview()">
                                    </div>
                                </div>

                                <div class="seal-dropzone" onclick="document.getElementById('file_right_logo').click()">
                                    <?php $rightLogo = branding('report_header_right_logo'); ?>
                                    <img src="<?php echo e($rightLogo ? asset($rightLogo) : asset('assets/logo-dark.png')); ?>" class="seal-thumb-img" id="thumb_right_logo">
                                    <span class="small fw-bold text-dark d-block">Change Right Seal</span>
                                    <span class="badge bg-white text-secondary border mt-1" style="font-size: 0.65rem;">PNG, JPG, WebP, SVG &bull; Max 4MB</span>
                                </div>
                                <input type="file" name="settings[report_header_right_logo]" id="file_right_logo" class="d-none" accept=".png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewSealFile(this, 'right')">

                                <div class="mt-2 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="small text-muted" style="font-size: 0.72rem;">Size:</span>
                                        <input type="range" name="settings[report_header_right_logo_size]" id="range_right_size" min="40" max="100" value="<?php echo e(branding('report_header_right_logo_size', '60')); ?>" class="form-range" style="width: 80px;" oninput="updateLivePreview()">
                                    </div>
                                    <select name="settings[report_header_right_logo_shape]" id="sel_right_shape" class="form-select form-select-sm" style="width: 90px; font-size: 0.72rem;" onchange="updateLivePreview()">
                                        <option value="circle" <?php echo e(branding('report_header_right_logo_shape', 'circle') === 'circle' ? 'selected' : ''); ?>>Gov Ring</option>
                                        <option value="none" <?php echo e(branding('report_header_right_logo_shape') === 'none' ? 'selected' : ''); ?>>No Ring</option>
                                    </select>
                                </div>
                                <?php if($rightLogo): ?>
                                    <button type="button" class="btn btn-link text-danger p-0 mt-1" style="font-size: 0.72rem;" onclick="removeImage('right_logo')">
                                        <i class="bi bi-trash"></i> Reset to Default
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="studio-panel" id="textLinesPanel">
                    <div class="studio-panel-title">
                        <i class="bi bi-fonts text-primary"></i> 3. Letterhead Text Lines (Word-Style Formatter)
                    </div>

                    
                    <div class="line-card">
                        <div class="line-card-header">
                            <span>Line 1: Country / Pre-header</span>
                            <div class="d-flex align-items-center gap-2">
                                <select name="settings[report_header_line1_font_size]" id="fsize_line1" class="form-select form-select-sm py-0" style="width: 76px; font-size: 0.72rem;" onchange="updateLivePreview()">
                                    <option value="8.5px" <?php echo e(branding('report_header_line1_font_size') == '8.5px' ? 'selected' : ''); ?>>8.5px</option>
                                    <option value="9.5px" <?php echo e(branding('report_header_line1_font_size', '9.5px') == '9.5px' ? 'selected' : ''); ?>>9.5px</option>
                                    <option value="11px" <?php echo e(branding('report_header_line1_font_size') == '11px' ? 'selected' : ''); ?>>11px</option>
                                    <option value="13px" <?php echo e(branding('report_header_line1_font_size') == '13px' ? 'selected' : ''); ?>>13px</option>
                                </select>
                                <input type="color" name="settings[report_header_line1_color]" id="fcolor_line1" value="<?php echo e(branding('report_header_line1_color', '#334155')); ?>" class="form-control form-control-color p-0" style="width: 22px; height: 22px;" onchange="updateLivePreview()">
                            </div>
                        </div>
                        <input type="text" name="settings[report_header_line1]" id="text_line1" value="<?php echo e(branding('report_header_line1', 'Republic of the Philippines')); ?>" class="form-control form-control-sm" placeholder="Republic of the Philippines" oninput="updateLivePreview()">
                        <input type="hidden" name="settings[report_header_line1_font_weight]" id="fweight_line1" value="<?php echo e(branding('report_header_line1_font_weight', '700')); ?>">
                        <input type="hidden" name="settings[report_header_line1_transform]" id="ftrans_line1" value="<?php echo e(branding('report_header_line1_transform', 'uppercase')); ?>">
                    </div>

                    
                    <div class="line-card">
                        <div class="line-card-header">
                            <span class="text-primary fw-bold">Line 2: Institution / University Name</span>
                            <div class="d-flex align-items-center gap-2">
                                <select name="settings[report_header_line2_font_size]" id="fsize_line2" class="form-select form-select-sm py-0" style="width: 76px; font-size: 0.72rem;" onchange="updateLivePreview()">
                                    <option value="14px" <?php echo e(branding('report_header_line2_font_size') == '14px' ? 'selected' : ''); ?>>14px</option>
                                    <option value="16.5px" <?php echo e(branding('report_header_line2_font_size', '16.5px') == '16.5px' ? 'selected' : ''); ?>>16.5px</option>
                                    <option value="18px" <?php echo e(branding('report_header_line2_font_size') == '18px' ? 'selected' : ''); ?>>18px</option>
                                    <option value="20px" <?php echo e(branding('report_header_line2_font_size') == '20px' ? 'selected' : ''); ?>>20px</option>
                                </select>
                                <input type="color" name="settings[report_header_line2_color]" id="fcolor_line2" value="<?php echo e(branding('report_header_line2_color', '#0f2b48')); ?>" class="form-control form-control-color p-0" style="width: 22px; height: 22px;" onchange="updateLivePreview()">
                            </div>
                        </div>
                        <input type="text" name="settings[report_header_line2]" id="text_line2" value="<?php echo e(branding('report_header_line2', 'STATE UNIVERSITY SYSTEM')); ?>" class="form-control form-control-sm fw-bold" placeholder="STATE UNIVERSITY SYSTEM" oninput="updateLivePreview()">
                        <input type="hidden" name="settings[report_header_line2_font_weight]" id="fweight_line2" value="<?php echo e(branding('report_header_line2_font_weight', '900')); ?>">
                        <input type="hidden" name="settings[report_header_line2_transform]" id="ftrans_line2" value="<?php echo e(branding('report_header_line2_transform', 'none')); ?>">
                    </div>

                    
                    <div class="line-card">
                        <div class="line-card-header">
                            <span>Line 3: Office / Department</span>
                            <div class="d-flex align-items-center gap-2">
                                <select name="settings[report_header_line3_font_size]" id="fsize_line3" class="form-select form-select-sm py-0" style="width: 76px; font-size: 0.72rem;" onchange="updateLivePreview()">
                                    <option value="9px" <?php echo e(branding('report_header_line3_font_size') == '9px' ? 'selected' : ''); ?>>9px</option>
                                    <option value="10.5px" <?php echo e(branding('report_header_line3_font_size', '10.5px') == '10.5px' ? 'selected' : ''); ?>>10.5px</option>
                                    <option value="12px" <?php echo e(branding('report_header_line3_font_size') == '12px' ? 'selected' : ''); ?>>12px</option>
                                    <option value="14px" <?php echo e(branding('report_header_line3_font_size') == '14px' ? 'selected' : ''); ?>>14px</option>
                                </select>
                                <input type="color" name="settings[report_header_line3_color]" id="fcolor_line3" value="<?php echo e(branding('report_header_line3_color', '#475569')); ?>" class="form-control form-control-color p-0" style="width: 22px; height: 22px;" onchange="updateLivePreview()">
                            </div>
                        </div>
                        <input type="text" name="settings[report_header_line3]" id="text_line3" value="<?php echo e(branding('report_header_line3', 'OFFICE OF PHYSICAL PLANT & AUXILIARY SERVICES')); ?>" class="form-control form-control-sm" placeholder="OFFICE OF PHYSICAL PLANT & AUXILIARY SERVICES" oninput="updateLivePreview()">
                        <input type="hidden" name="settings[report_header_line3_font_weight]" id="fweight_line3" value="<?php echo e(branding('report_header_line3_font_weight', '600')); ?>">
                        <input type="hidden" name="settings[report_header_line3_transform]" id="ftrans_line3" value="<?php echo e(branding('report_header_line3_transform', 'none')); ?>">
                    </div>

                    
                    <div class="line-card">
                        <div class="line-card-header">
                            <span>Line 4: Campus Address</span>
                            <div class="d-flex align-items-center gap-2">
                                <select name="settings[report_header_line4_font_size]" id="fsize_line4" class="form-select form-select-sm py-0" style="width: 76px; font-size: 0.72rem;" onchange="updateLivePreview()">
                                    <option value="8px" <?php echo e(branding('report_header_line4_font_size') == '8px' ? 'selected' : ''); ?>>8px</option>
                                    <option value="8.5px" <?php echo e(branding('report_header_line4_font_size', '8.5px') == '8.5px' ? 'selected' : ''); ?>>8.5px</option>
                                    <option value="9.5px" <?php echo e(branding('report_header_line4_font_size') == '9.5px' ? 'selected' : ''); ?>>9.5px</option>
                                </select>
                                <input type="color" name="settings[report_header_line4_color]" id="fcolor_line4" value="<?php echo e(branding('report_header_line4_color', '#475569')); ?>" class="form-control form-control-color p-0" style="width: 22px; height: 22px;" onchange="updateLivePreview()">
                            </div>
                        </div>
                        <input type="text" name="settings[report_header_line4]" id="text_line4" value="<?php echo e(branding('report_header_line4', 'University Boulevard, Main Campus, Metro Manila, Philippines')); ?>" class="form-control form-control-sm" placeholder="Campus Address" oninput="updateLivePreview()">
                        <input type="hidden" name="settings[report_header_line4_font_weight]" id="fweight_line4" value="<?php echo e(branding('report_header_line4_font_weight', '400')); ?>">
                    </div>

                    
                    <div class="line-card">
                        <div class="line-card-header">
                            <span>Line 5: Tel, Email, Website</span>
                            <div class="d-flex align-items-center gap-2">
                                <select name="settings[report_header_line5_font_size]" id="fsize_line5" class="form-select form-select-sm py-0" style="width: 76px; font-size: 0.72rem;" onchange="updateLivePreview()">
                                    <option value="8px" <?php echo e(branding('report_header_line5_font_size') == '8px' ? 'selected' : ''); ?>>8px</option>
                                    <option value="8.5px" <?php echo e(branding('report_header_line5_font_size', '8.5px') == '8.5px' ? 'selected' : ''); ?>>8.5px</option>
                                    <option value="9.5px" <?php echo e(branding('report_header_line5_font_size') == '9.5px' ? 'selected' : ''); ?>>9.5px</option>
                                </select>
                                <input type="color" name="settings[report_header_line5_color]" id="fcolor_line5" value="<?php echo e(branding('report_header_line5_color', '#475569')); ?>" class="form-control form-control-color p-0" style="width: 22px; height: 22px;" onchange="updateLivePreview()">
                            </div>
                        </div>
                        <input type="text" name="settings[report_header_line5]" id="text_line5" value="<?php echo e(branding('report_header_line5', 'Tel: (02) 8911-2000 • Web: sure.university.edu.ph')); ?>" class="form-control form-control-sm" placeholder="Contact, Web, Email" oninput="updateLivePreview()">
                        <input type="hidden" name="settings[report_header_line5_font_weight]" id="fweight_line5" value="<?php echo e(branding('report_header_line5_font_weight', '400')); ?>">
                    </div>
                </div>

                
                <div class="studio-panel" id="dividerPanel">
                    <div class="studio-panel-title">
                        <i class="bi bi-dash-lg text-primary"></i> 4. Header Divider Rule
                    </div>
                    <div class="row g-2 align-items-center">
                        <div class="col-md-7">
                            <select name="settings[report_header_divider_style]" id="sel_divider_style" class="form-select form-select-sm" onchange="updateLivePreview()">
                                <option value="double" <?php echo e(branding('report_header_divider_style', 'double') === 'double' ? 'selected' : ''); ?>>Double Line (Gov Letterhead Standard)</option>
                                <option value="single" <?php echo e(branding('report_header_divider_style') === 'single' ? 'selected' : ''); ?>>Single Solid Line</option>
                                <option value="thick" <?php echo e(branding('report_header_divider_style') === 'thick' ? 'selected' : ''); ?>>Thick Solid Bar</option>
                                <option value="none" <?php echo e(branding('report_header_divider_style') === 'none' ? 'selected' : ''); ?>>No Divider Line</option>
                            </select>
                        </div>
                        <div class="col-md-5 d-flex align-items-center gap-2">
                            <span class="small text-muted">Color:</span>
                            <input type="color" name="settings[report_header_divider_color]" id="fcolor_divider" value="<?php echo e(branding('report_header_divider_color', '#0f2b48')); ?>" class="form-control form-control-color p-0" style="width: 28px; height: 28px;" onchange="updateLivePreview()">
                        </div>
                    </div>
                </div>

                
                <div class="studio-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="studio-panel-title mb-0 border-0 p-0">
                            <i class="bi bi-layout-text-window-reverse text-primary"></i> 5. Official Document Footer CMS
                        </div>
                        <div class="word-segmented">
                            <button type="button" class="word-segmented-btn" id="btnFootModeBuilder" onclick="setFooterMode('builder')">
                                <i class="bi bi-fonts"></i> Word-Style Footer
                            </button>
                            <button type="button" class="word-segmented-btn" id="btnFootModeImage" onclick="setFooterMode('image')">
                                <i class="bi bi-image"></i> Pre-Made Footer Banner
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="settings[report_footer_mode]" id="input_footer_mode" value="<?php echo e(branding('report_footer_mode', 'builder')); ?>">

                    
                    <div id="footerBannerImageSection" class="d-none mb-3">
                        <p class="small text-muted mb-2">
                            Upload your official institutional stationery footer graphic banner (PNG with transparency recommended).
                        </p>
                        <div class="seal-dropzone" onclick="document.getElementById('file_footer_image').click()">
                            <?php $ftrImg = branding('report_footer_image'); ?>
                            <?php if($ftrImg): ?>
                                <img src="<?php echo e(asset($ftrImg)); ?>" class="seal-thumb-img w-100" style="max-height: 50px; object-fit: contain;" id="thumb_footer_image">
                                <span class="badge bg-success mb-1">Current Footer Active</span>
                                <span class="text-muted d-block" style="font-size: 0.68rem;">Click to replace image</span>
                            <?php else: ?>
                                <i class="bi bi-cloud-arrow-up fs-2 text-muted d-block mb-1"></i>
                                <span class="small fw-bold text-dark d-block">Click or Drag &amp; Drop Footer Graphic Banner</span>
                            <?php endif; ?>
                            <span class="badge bg-white text-secondary border mt-1" style="font-size: 0.65rem;">PNG, JPG, WebP, SVG &bull; Max 4MB</span>
                        </div>
                        <input type="file" name="settings[report_footer_image]" id="file_footer_image" class="d-none" accept=".png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml" onchange="previewFooterFile(this)">

                        
                        <input type="hidden" name="settings[report_footer_image_pos_x]" id="input_footer_image_pos_x" value="<?php echo e(branding('report_footer_image_pos_x', '0')); ?>">
                        <input type="hidden" name="settings[report_footer_image_pos_y]" id="input_footer_image_pos_y" value="<?php echo e(branding('report_footer_image_pos_y', '0')); ?>">
                        <input type="hidden" name="settings[report_footer_image_scale]" id="input_footer_image_scale" value="<?php echo e(branding('report_footer_image_scale', '1.0')); ?>">

                        
                        <div class="bg-light border rounded-3 p-3 mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="small fw-bold text-dark mb-0">
                                    <i class="bi bi-arrows-move text-primary me-1"></i> Footer Canvas &amp; Position Controls
                                </label>
                                <div class="d-flex align-items-center gap-1">
                                    <button type="button" class="btn btn-xs btn-primary rounded-pill px-2 shadow-sm" onclick="autoFitBanner('footer')" title="Fit banner to full printable width using its natural aspect ratio">
                                        <i class="bi bi-aspect-ratio me-1"></i> ⚡ Auto-Fit Width
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" onclick="resetBannerPosition('footer')" title="Reset position to center">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                    <?php if($ftrImg): ?>
                                        <button type="button" class="btn btn-outline-danger btn-xs rounded-pill px-2" onclick="removeImage('footer_image')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted fw-bold" style="font-size: 0.75rem;">Frame Height:</span>
                                    <div class="input-group input-group-sm" style="width: 85px;">
                                        <input type="number" name="settings[report_footer_image_height]" id="input_footer_image_height" value="<?php echo e(branding('report_footer_image_height', '50')); ?>" min="20" max="400" class="form-control text-center py-0" oninput="setHeightFromInput('footer', this.value)">
                                        <span class="input-group-text py-0 px-1 text-muted" style="font-size: 0.68rem;">px</span>
                                    </div>
                                </div>
                                <input type="range" id="range_footer_height" min="20" max="350" value="<?php echo e(branding('report_footer_image_height', '50')); ?>" class="form-range" oninput="setHeightFromRange('footer', this.value)">
                            </div>

                            
                            <div class="mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted fw-bold" style="font-size: 0.75rem;">Scale / Zoom:</span>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1" onclick="adjustScale('footer', -0.05)">-</button>
                                        <span class="badge bg-white text-dark border px-2 py-1" id="badge_footer_zoom" style="font-size: 0.72rem;"><?php echo e(round((float)branding('report_footer_image_scale', '1') * 100)); ?>%</span>
                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-1" onclick="adjustScale('footer', 0.05)">+</button>
                                    </div>
                                </div>
                                <input type="range" id="range_footer_scale" min="30" max="300" value="<?php echo e(round((float)branding('report_footer_image_scale', '1') * 100)); ?>" class="form-range" oninput="setScaleFromRange('footer', this.value)">
                            </div>

                            
                            <div class="p-2 border rounded-2 bg-white mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted fw-bold" style="font-size: 0.72rem;">Position Coordinates:</span>
                                    <span class="badge bg-light text-primary border" id="badge_footer_pos" style="font-size: 0.68rem;">
                                        X: <?php echo e((int)branding('report_footer_image_pos_x', '0')); ?>px, Y: <?php echo e((int)branding('report_footer_image_pos_y', '0')); ?>px
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="small text-muted" style="font-size: 0.68rem;"><i class="bi bi-arrows-move text-primary"></i> Drag image on A4 to move or nudge:</span>
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2" onclick="nudgePos('footer', -5, 0)" title="Nudge Left">◀</button>
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2" onclick="nudgePos('footer', 0, -5)" title="Nudge Up">▲</button>
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2" onclick="nudgePos('footer', 0, 5)" title="Nudge Down">▼</button>
                                        <button type="button" class="btn btn-xs btn-light border py-0 px-2" onclick="nudgePos('footer', 5, 0)" title="Nudge Right">▶</button>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="p-2 border rounded-2 bg-white mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-dark fw-bold" style="font-size: 0.72rem;">
                                        <i class="bi bi-pin-map-fill text-primary me-1"></i> Document Page Placement
                                    </span>
                                    <span class="badge bg-primary text-white" id="badge_footer_edge_status" style="font-size: 0.68rem;">
                                        <?php echo e(branding('report_footer_edge', 'flush') === 'flush' ? (branding('report_footer_offset_y', '0') == '0' ? '📌 Flush Bottom Edge (0px)' : 'Flush Bottom (' . branding('report_footer_offset_y', '0') . 'px)') : 'Inside Margins'); ?>

                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-1 mb-2">
                                    <button type="button" class="btn btn-xs btn-primary rounded-pill px-2" id="btn_footer_snap_flush" onclick="snapToEdge('footer', 'flush')">
                                        <i class="bi bi-pin-angle-fill me-1"></i> 📌 Flush to Very Bottom (0px)
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2" id="btn_footer_snap_margin" onclick="snapToEdge('footer', 'margin')">
                                        ↕ Inside Margins
                                    </button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small text-muted" style="font-size: 0.72rem;">Distance from Bottom Edge:</span>
                                    <span class="badge bg-light text-dark border" id="badge_footer_offset_val" style="font-size: 0.68rem;"><?php echo e(branding('report_footer_offset_y', '0')); ?>px</span>
                                </div>
                                <input type="range" id="range_footer_offset" min="0" max="80" value="<?php echo e(branding('report_footer_offset_y', '0')); ?>" class="form-range" oninput="setOffsetYFromRange('footer', this.value)">
                                <input type="hidden" name="settings[report_footer_edge]" id="input_footer_edge" value="<?php echo e(branding('report_footer_edge', 'flush')); ?>">
                                <input type="hidden" name="settings[report_footer_offset_y]" id="input_footer_offset_y" value="<?php echo e(branding('report_footer_offset_y', '0')); ?>">
                            </div>

                            
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="small text-muted" style="font-size: 0.72rem;">Fit:</span>
                                    <select name="settings[report_footer_image_fit]" id="sel_footer_fit" class="form-select form-select-sm py-0 px-2" style="width: 140px; font-size: 0.72rem;" onchange="setFitMode('footer', this.value)">
                                        <option value="contain" <?php echo e(branding('report_footer_image_fit', 'contain') === 'contain' ? 'selected' : ''); ?>>Center Contain</option>
                                        <option value="width" <?php echo e(branding('report_footer_image_fit', 'contain') === 'width' ? 'selected' : ''); ?>>Full Page Width (100%)</option>
                                    </select>
                                </div>

                                <div class="d-flex align-items-center gap-1">
                                    <span class="small text-muted" style="font-size: 0.72rem;">Presets:</span>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill" onclick="setHeightPreset('footer', 40)">40px</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill" onclick="setHeightPreset('footer', 60)">60px</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 rounded-pill" onclick="setHeightPreset('footer', 100)">100px</button>
                                </div>
                            </div>

                            <div class="small text-primary mt-2 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                <i class="bi bi-hand-index-thumb"></i>
                                <span><strong>Direct Canvas Drag:</strong> Click &amp; drag the image inside the A4 sheet to move it freely, or drag corner handles to zoom!</span>
                            </div>
                        </div>
                    </div>

                    
                    <div id="footerBuilderSection">
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="small fw-bold text-dark mb-1">Footer Left Title (Institution / System)</label>
                                <input type="text" name="settings[report_footer_left_title]" id="input_footer_left_title" value="<?php echo e(branding('report_footer_left_title', 'State University Reservation Engine')); ?>" class="form-control form-control-sm" oninput="updateLivePreview()">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Footer Line 1 (Address)</label>
                                <input type="text" name="settings[report_footer_left_line1]" id="input_footer_left_line1" value="<?php echo e(branding('report_footer_left_line1', 'University Boulevard, Main Campus, Metro Manila')); ?>" class="form-control form-control-sm" oninput="updateLivePreview()">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Footer Line 2 (Contact / Email)</label>
                                <input type="text" name="settings[report_footer_left_line2]" id="input_footer_left_line2" value="<?php echo e(branding('report_footer_left_line2', 'Contact: (02) 8911-2000 | Email: facilities-office@university.edu.ph')); ?>" class="form-control form-control-sm" oninput="updateLivePreview()">
                            </div>
                            <div class="col-12">
                                <label class="small text-muted mb-1">Footer Center Tag (ISO Certification or Special Notice)</label>
                                <input type="text" name="settings[report_footer_center_text]" id="input_footer_center_text" value="<?php echo e(branding('report_footer_center_text', 'ISO 9001:2015 Certified Institution')); ?>" class="form-control form-control-sm" placeholder="e.g. ISO 9001:2015 Certified" oninput="updateLivePreview()">
                            </div>
                            <div class="col-12">
                                <label class="small text-muted mb-1">Footer Right Official Notice</label>
                                <input type="text" name="settings[report_footer_system_notice]" id="input_footer_system_notice" value="<?php echo e(branding('report_footer_system_notice', 'Generated by: SURE Facility Management System (Official Report)')); ?>" class="form-control form-control-sm" oninput="updateLivePreview()">
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-4 pt-2 border-top">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="settings[report_footer_show_timestamp]" id="chk_footer_time" value="1" <?php echo e(branding('report_footer_show_timestamp', '1') == '1' ? 'checked' : ''); ?> onchange="updateLivePreview()">
                                <label class="form-check-label small text-muted">Print Timestamp</label>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="settings[report_footer_show_pagenum]" id="chk_footer_page" value="1" <?php echo e(branding('report_footer_show_pagenum', '1') == '1' ? 'checked' : ''); ?> onchange="updateLivePreview()">
                                <label class="form-check-label small text-muted">Page Number</label>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="settings[report_footer_divider]" id="chk_footer_divider" value="1" <?php echo e(branding('report_footer_divider', '1') == '1' ? 'checked' : ''); ?> onchange="updateLivePreview()">
                                <label class="form-check-label small text-muted">Top Divider Line</label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="col-xl-6 col-lg-6">
                <div class="a4-desk">
                    <div class="d-flex justify-content-between align-items-center text-white-50 small mb-2 px-1">
                        <span><i class="bi bi-eye-fill me-1"></i> Live A4 Printable Preview (WYSIWYG)</span>
                        <span>Scale: 100% Fit</span>
                    </div>

                    
                    <div class="a4-sheet-preview" id="a4Canvas">

                        
                        <div id="a4HeaderSlot">
                            
                            <div id="previewHeaderImageWrap" class="text-center <?php echo e(branding('report_header_mode', 'builder') === 'image' ? '' : 'd-none'); ?>">
                                <div class="banner-canvas-viewport banner-viewport-header <?php echo e(branding('report_header_edge', 'flush') === 'flush' ? 'is-flush' : ''); ?>" id="viewport_header" style="height: <?php echo e(branding('report_header_image_height', '90')); ?>px;">
                                    <div class="banner-canvas-stage" id="stage_header">
                                        <img src="<?php echo e(branding('report_header_image') ? asset(branding('report_header_image')) : ''); ?>"
                                             id="previewHeaderImg"
                                             class="banner-canvas-img">
                                    </div>

                                    <div class="banner-canvas-overlay" id="overlay_header">
                                        <div class="banner-move-hint" id="moveHint_header"><i class="bi bi-arrows-move"></i> Drag to Move</div>
                                        <div class="canvas-handle handle-nw" data-handle="nw"></div>
                                        <div class="canvas-handle handle-ne" data-handle="ne"></div>
                                        <div class="canvas-handle handle-se" data-handle="se"></div>
                                        <div class="canvas-handle handle-sw" data-handle="sw"></div>
                                        <div class="canvas-edge-bar bar-bottom" id="edgeBar_header" title="Drag to resize height">
                                            <span class="edge-bar-pill">
                                                <i class="bi bi-arrows-expand-vertical me-1"></i> <span id="badgeHeight_header"><?php echo e(branding('report_header_image_height', '90')); ?>px</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="banner-canvas-hud" id="hud_header">
                                        <span class="hud-pill"><i class="bi bi-arrows-move"></i> Move</span>
                                        <button type="button" class="hud-btn hud-btn-snap" onclick="snapToEdge('header', 'flush', event)" title="Snap flush to very top edge (0px gap)"><i class="bi bi-pin-angle-fill me-1"></i> Flush Top</button>
                                        <button type="button" class="hud-btn" onclick="snapToEdge('header', 'margin', event)" title="Inset inside standard page margins"><i class="bi bi-arrows-collapse me-1"></i> Inset</button>
                                        <div class="hud-divider"></div>
                                        <button type="button" class="hud-btn" onclick="adjustScale('header', -0.05, event)"><i class="bi bi-dash"></i></button>
                                        <span class="hud-zoom-val" id="hudZoom_header"><?php echo e(round((float)branding('report_header_image_scale', '1') * 100)); ?>%</span>
                                        <button type="button" class="hud-btn" onclick="adjustScale('header', 0.05, event)"><i class="bi bi-plus"></i></button>
                                        <div class="hud-divider"></div>
                                        <button type="button" class="hud-btn hud-btn-action" onclick="autoFitBanner('header', event)"><i class="bi bi-aspect-ratio me-1"></i> Fit Width</button>
                                        <button type="button" class="hud-btn" onclick="resetBannerPosition('header', event)"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
                                    </div>
                                </div>
                            </div>

                            
                            <div id="previewBuilderWrap" class="a4-builder-header-wrap <?php echo e(branding('report_header_mode', 'builder') === 'image' ? 'd-none' : ''); ?>">
                                <div class="preview-doc-header">
                                    
                                    <div class="preview-seal-box" id="previewLeftSealBox">
                                        <div class="preview-seal-circle <?php echo e(branding('report_header_left_logo_shape', 'circle') === 'none' ? 'no-ring' : 'has-ring'); ?>" id="previewLeftCircle" style="width: <?php echo e(branding('report_header_left_logo_size', '60')); ?>px; height: <?php echo e(branding('report_header_left_logo_size', '60')); ?>px;">
                                            <img src="<?php echo e(branding('report_header_left_logo') ? asset(branding('report_header_left_logo')) : asset('assets/logo-dark.png')); ?>"
                                                 id="previewLeftSealImg"
                                                 class="preview-seal-img">
                                        </div>
                                    </div>

                                    
                                    <div class="preview-center-text">
                                        <div id="prev_line1" style="font-size: <?php echo e(branding('report_header_line1_font_size', '9.5px')); ?>; font-weight: <?php echo e(branding('report_header_line1_font_weight', '700')); ?>; color: <?php echo e(branding('report_header_line1_color', '#334155')); ?>; text-transform: <?php echo e(branding('report_header_line1_transform', 'uppercase')); ?>; letter-spacing: 0.12em;">
                                            <?php echo e(branding('report_header_line1', 'Republic of the Philippines')); ?>

                                        </div>
                                        <div id="prev_line2" style="font-size: <?php echo e(branding('report_header_line2_font_size', '16.5px')); ?>; font-weight: <?php echo e(branding('report_header_line2_font_weight', '900')); ?>; color: <?php echo e(branding('report_header_line2_color', '#0f2b48')); ?>; line-height: 1.2; margin: 2px 0;">
                                            <?php echo e(branding('report_header_line2', 'STATE UNIVERSITY SYSTEM')); ?>

                                        </div>
                                        <div id="prev_line3" style="font-size: <?php echo e(branding('report_header_line3_font_size', '10.5px')); ?>; font-weight: <?php echo e(branding('report_header_line3_font_weight', '600')); ?>; color: <?php echo e(branding('report_header_line3_color', '#475569')); ?>; margin-bottom: 3px;">
                                            <?php echo e(branding('report_header_line3', 'OFFICE OF PHYSICAL PLANT & AUXILIARY SERVICES')); ?>

                                        </div>
                                        <div id="previewRule" class="rule-<?php echo e(branding('report_header_divider_style', 'double')); ?>" style="border-color: <?php echo e(branding('report_header_divider_color', '#0f2b48')); ?>; background-color: <?php echo e(branding('report_header_divider_color', '#0f2b48')); ?>;"></div>
                                        <div id="prev_line4" style="font-size: <?php echo e(branding('report_header_line4_font_size', '8.5px')); ?>; font-weight: <?php echo e(branding('report_header_line4_font_weight', '400')); ?>; color: <?php echo e(branding('report_header_line4_color', '#475569')); ?>;">
                                            <?php echo e(branding('report_header_line4', 'University Boulevard, Main Campus, Metro Manila, Philippines')); ?>

                                        </div>
                                        <div id="prev_line5" style="font-size: <?php echo e(branding('report_header_line5_font_size', '8.5px')); ?>; font-weight: <?php echo e(branding('report_header_line5_font_weight', '400')); ?>; color: <?php echo e(branding('report_header_line5_color', '#475569')); ?>;">
                                            <?php echo e(branding('report_header_line5', 'Tel: (02) 8911-2000 • Web: sure.university.edu.ph')); ?>

                                        </div>
                                    </div>

                                    
                                    <div class="preview-seal-box" id="previewRightSealBox">
                                        <div class="preview-seal-circle <?php echo e(branding('report_header_right_logo_shape', 'circle') === 'none' ? 'no-ring' : 'has-ring'); ?>" id="previewRightCircle" style="width: <?php echo e(branding('report_header_right_logo_size', '60')); ?>px; height: <?php echo e(branding('report_header_right_logo_size', '60')); ?>px;">
                                            <img src="<?php echo e(branding('report_header_right_logo') ? asset(branding('report_header_right_logo')) : asset('assets/logo-dark.png')); ?>"
                                                 id="previewRightSealImg"
                                                 class="preview-seal-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div id="a4BodyContent" class="a4-body-content">
                            <div class="pt-2" style="opacity: 0.85;">
                                <div style="text-align: right; font-size: 8px; color: #64748b; line-height: 1.4;">
                                    Document Ref: <strong>SURE-RPT-<?php echo e(date('Ymd')); ?>-SAMPLE</strong><br>
                                    Date Generated: <strong><?php echo e(date('F d, Y')); ?></strong><br>
                                    Classification: <strong>OFFICIAL ADMINISTRATIVE RECORD</strong>
                                </div>

                                <div style="text-align: center; margin: 12px 0 8px;">
                                    <h5 style="font-size: 13px; font-weight: 800; text-transform: uppercase; margin: 0; color: #0f172a;">
                                        CERTIFIED LIST OF FACILITY RESERVATIONS
                                    </h5>
                                    <div style="font-size: 8.5px; color: #64748b; text-transform: uppercase; margin-top: 2px;">
                                        Coverage Period: All Records &bull; University Venues
                                    </div>
                                </div>

                                
                                <table style="width: 100%; border-collapse: collapse; font-size: 8.5px; margin-top: 8px;">
                                    <thead>
                                        <tr style="background: #0f2b48; color: #ffffff;">
                                            <th style="padding: 4px 6px;">#</th>
                                            <th style="padding: 4px 6px;">Ref #</th>
                                            <th style="padding: 4px 6px;">Client / Event</th>
                                            <th style="padding: 4px 6px;">Facility</th>
                                            <th style="padding: 4px 6px; text-align: right;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody style="border-bottom: 1px solid #cbd5e1;">
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 4px 6px;">1</td>
                                            <td style="padding: 4px 6px; font-family: monospace; font-weight: bold;">RES-2026-001</td>
                                            <td style="padding: 4px 6px;">University Student Council (General Assembly)</td>
                                            <td style="padding: 4px 6px;">University Gymnasium</td>
                                            <td style="padding: 4px 6px; text-align: right; font-weight: bold;">₱12,500.00</td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 4px 6px;">2</td>
                                            <td style="padding: 4px 6px; font-family: monospace; font-weight: bold;">RES-2026-002</td>
                                            <td style="padding: 4px 6px;">College of Engineering (Tech Summit 2026)</td>
                                            <td style="padding: 4px 6px;">Main Auditorium</td>
                                            <td style="padding: 4px 6px; text-align: right; font-weight: bold;">₱25,000.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        
                        <div id="a4FooterSlot">
                            
                            <div id="previewFooterImageWrap" class="text-center <?php echo e(branding('report_footer_mode', 'builder') === 'image' ? '' : 'd-none'); ?>">
                                <div class="banner-canvas-viewport banner-viewport-footer <?php echo e(branding('report_footer_edge', 'flush') === 'flush' ? 'is-flush' : ''); ?>" id="viewport_footer" style="height: <?php echo e(branding('report_footer_image_height', '50')); ?>px;">
                                    <div class="banner-canvas-stage" id="stage_footer">
                                        <img src="<?php echo e(branding('report_footer_image') ? asset(branding('report_footer_image')) : ''); ?>"
                                             id="previewFooterImg"
                                             class="banner-canvas-img">
                                    </div>

                                    <div class="banner-canvas-overlay" id="overlay_footer">
                                        <div class="banner-move-hint" id="moveHint_footer"><i class="bi bi-arrows-move"></i> Drag to Move</div>
                                        <div class="canvas-handle handle-nw" data-handle="nw"></div>
                                        <div class="canvas-handle handle-ne" data-handle="ne"></div>
                                        <div class="canvas-handle handle-se" data-handle="se"></div>
                                        <div class="canvas-handle handle-sw" data-handle="sw"></div>
                                        <div class="canvas-edge-bar bar-top" id="edgeBar_footer" title="Drag to resize height">
                                            <span class="edge-bar-pill">
                                                <i class="bi bi-arrows-expand-vertical me-1"></i> <span id="badgeHeight_footer"><?php echo e(branding('report_footer_image_height', '50')); ?>px</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="banner-canvas-hud" id="hud_footer">
                                        <span class="hud-pill"><i class="bi bi-arrows-move"></i> Move</span>
                                        <button type="button" class="hud-btn hud-btn-snap" onclick="snapToEdge('footer', 'flush', event)" title="Snap flush to very bottom edge (0px gap)"><i class="bi bi-pin-angle-fill me-1"></i> Flush Bottom</button>
                                        <button type="button" class="hud-btn" onclick="snapToEdge('footer', 'margin', event)" title="Inset inside standard page margins"><i class="bi bi-arrows-collapse me-1"></i> Inset</button>
                                        <div class="hud-divider"></div>
                                        <button type="button" class="hud-btn" onclick="adjustScale('footer', -0.05, event)"><i class="bi bi-dash"></i></button>
                                        <span class="hud-zoom-val" id="hudZoom_footer"><?php echo e(round((float)branding('report_footer_image_scale', '1') * 100)); ?>%</span>
                                        <button type="button" class="hud-btn" onclick="adjustScale('footer', 0.05, event)"><i class="bi bi-plus"></i></button>
                                        <div class="hud-divider"></div>
                                        <button type="button" class="hud-btn hud-btn-action" onclick="autoFitBanner('footer', event)"><i class="bi bi-aspect-ratio me-1"></i> Fit Width</button>
                                        <button type="button" class="hud-btn" onclick="resetBannerPosition('footer', event)"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="preview-doc-footer a4-builder-footer-wrap <?php echo e(branding('report_footer_mode', 'builder') === 'image' ? 'd-none' : ''); ?>" id="previewFooterWrap">
                                <div style="flex: 1 1 0%; text-align: left;">
                                    <strong id="prev_foot_title"><?php echo e(branding('report_footer_left_title', 'State University Reservation Engine')); ?></strong><br>
                                    <span id="prev_foot_line1"><?php echo e(branding('report_footer_left_line1', 'University Boulevard, Main Campus, Metro Manila')); ?></span><br>
                                    <span id="prev_foot_line2"><?php echo e(branding('report_footer_left_line2', 'Contact: (02) 8911-2000 | Email: facilities-office@university.edu.ph')); ?></span>
                                </div>
                                <div style="flex: 1 1 0%; text-align: center;" id="prev_foot_center">
                                    <em><?php echo e(branding('report_footer_center_text', 'ISO 9001:2015 Certified Institution')); ?></em>
                                </div>
                                <div style="flex: 1 1 0%; text-align: right;">
                                    <span id="prev_foot_time">Date Printed: <?php echo e(date('F d, Y')); ?> &bull; <?php echo e(date('g:i A')); ?></span><br>
                                    <span id="prev_foot_page">Page 1 of 1</span><br>
                                    <span id="prev_foot_notice" style="font-size: 7.5px;"><?php echo e(branding('report_footer_system_notice', 'Generated by: SURE Facility Management System (Official Report)')); ?></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </form>

</div>

<script>
    // ── Mode Switcher ──
    function setHeaderMode(mode) {
        document.getElementById('input_header_mode').value = mode;
        var btnBuilder = document.getElementById('btnModeBuilder');
        var btnImage = document.getElementById('btnModeImage');
        var bannerSec = document.getElementById('bannerImageSection');
        var sealsPanel = document.getElementById('sealsPanel');
        var textLinesPanel = document.getElementById('textLinesPanel');
        var dividerPanel = document.getElementById('dividerPanel');

        var prevHeaderWrap = document.getElementById('previewHeaderImageWrap');
        var prevBuilderWrap = document.getElementById('previewBuilderWrap');

        if (mode === 'image') {
            btnBuilder.classList.remove('active');
            btnImage.classList.add('active');
            bannerSec.classList.remove('d-none');
            sealsPanel.classList.add('d-none');
            textLinesPanel.classList.add('d-none');
            dividerPanel.classList.add('d-none');

            prevHeaderWrap.classList.remove('d-none');
            prevBuilderWrap.classList.add('d-none');
        } else {
            btnBuilder.classList.add('active');
            btnImage.classList.remove('active');
            bannerSec.classList.add('d-none');
            sealsPanel.classList.remove('d-none');
            textLinesPanel.classList.remove('d-none');
            dividerPanel.classList.remove('d-none');

            prevHeaderWrap.classList.add('d-none');
            prevBuilderWrap.classList.remove('d-none');
        }
    }

    // Initialize mode
    setHeaderMode("<?php echo e(branding('report_header_mode', 'builder')); ?>");

    // ── Footer Mode Switcher ──
    function setFooterMode(mode) {
        document.getElementById('input_footer_mode').value = mode;
        var btnBuilder = document.getElementById('btnFootModeBuilder');
        var btnImage = document.getElementById('btnFootModeImage');
        var bannerSec = document.getElementById('footerBannerImageSection');
        var builderSec = document.getElementById('footerBuilderSection');

        var prevFootImgWrap = document.getElementById('previewFooterImageWrap');
        var prevFootWrap = document.getElementById('previewFooterWrap');

        if (mode === 'image') {
            btnBuilder.classList.remove('active');
            btnImage.classList.add('active');
            bannerSec.classList.remove('d-none');
            builderSec.classList.add('d-none');

            prevFootImgWrap.classList.remove('d-none');
            prevFootWrap.classList.add('d-none');
        } else {
            btnBuilder.classList.add('active');
            btnImage.classList.remove('active');
            bannerSec.classList.add('d-none');
            builderSec.classList.remove('d-none');

            prevFootImgWrap.classList.add('d-none');
            prevFootWrap.classList.remove('d-none');
        }
    }

    // Initialize footer mode
    setFooterMode("<?php echo e(branding('report_footer_mode', 'builder')); ?>");

    // ── Instant Client-Side Image Pre-Validation ──
    function showCmsNotice(title, message) {
        var banner = document.getElementById('cmsNoticeBanner');
        if (!banner) return;
        document.getElementById('cmsNoticeTitle').textContent = title;
        document.getElementById('cmsNoticeText').textContent = message;
        banner.classList.remove('d-none');
        banner.classList.add('show');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function dismissCmsNotice() {
        var banner = document.getElementById('cmsNoticeBanner');
        if (banner) {
            banner.classList.add('d-none');
            banner.classList.remove('show');
        }
    }

    function validateSelectedImage(input, fieldLabel) {
        if (!input.files || !input.files[0]) return true;
        var file = input.files[0];
        var allowedExts = ['png', 'jpg', 'jpeg', 'webp', 'svg'];
        var allowedMimes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];
        var ext = (file.name.split('.').pop() || '').toLowerCase();

        var isExtValid = allowedExts.indexOf(ext) !== -1;
        var isMimeValid = !file.type || allowedMimes.indexOf(file.type.toLowerCase()) !== -1 || file.type.startsWith('image/');

        if (!isExtValid) {
            input.value = ''; // Instantly clear invalid selection so it cannot be saved
            showCmsNotice('Unsupported File Format (' + fieldLabel + ')',
                'The file "' + file.name + '" is not accepted. Please select a valid image file in PNG, JPG, WebP, or SVG format.');
            return false;
        }

        // 4MB limit (4 * 1024 * 1024 = 4194304 bytes)
        if (file.size > 4194304) {
            var sizeMb = (file.size / (1024 * 1024)).toFixed(1);
            input.value = ''; // Instantly clear oversized file
            showCmsNotice('File Too Large (' + fieldLabel + ')',
                'The selected image is ' + sizeMb + 'MB. Images must not exceed 4MB.');
            return false;
        }

        dismissCmsNotice();
        return true;
    }

    // ── Live File Previewers with Instant Pre-Validation ──
    function previewSealFile(input, side) {
        var label = side === 'left' ? 'Left Seal' : 'Right Seal';
        if (!validateSelectedImage(input, label)) {
            return;
        }
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var thumb = document.getElementById('thumb_' + side + '_logo');
                var prevImg = document.getElementById('preview' + (side === 'left' ? 'Left' : 'Right') + 'SealImg');
                if (thumb) thumb.src = e.target.result;
                if (prevImg) prevImg.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewBannerFile(input) {
        if (!validateSelectedImage(input, 'Header Banner')) {
            return;
        }
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var prevImg = document.getElementById('previewHeaderImg');
                if (prevImg) {
                    prevImg.src = e.target.result;
                    prevImg.onload = function() {
                        bannerStudio.header.edge = 'flush';
                        bannerStudio.header.offsetY = 0;
                        autoFitBanner('header');
                    };
                }
                var thumb = document.getElementById('thumb_header_image');
                if (thumb) thumb.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewFooterFile(input) {
        if (!validateSelectedImage(input, 'Footer Banner')) {
            return;
        }
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var prevImg = document.getElementById('previewFooterImg');
                if (prevImg) {
                    prevImg.src = e.target.result;
                    prevImg.onload = function() {
                        bannerStudio.footer.edge = 'flush';
                        bannerStudio.footer.offsetY = 0;
                        autoFitBanner('footer');
                    };
                }
                var thumb = document.getElementById('thumb_footer_image');
                if (thumb) thumb.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(type) {
        if (confirm('Are you sure you want to remove this image?')) {
            document.getElementById('remove_' + type).value = '1';
            document.getElementById('letterheadForm').submit();
        }
    }

    // ── Advanced Drag & Move Canvas Studio ──
    var bannerStudio = {
        header: {
            height: parseInt("<?php echo e(branding('report_header_image_height', '90')); ?>") || 90,
            posX: parseFloat("<?php echo e(branding('report_header_image_pos_x', '0')); ?>") || 0,
            posY: parseFloat("<?php echo e(branding('report_header_image_pos_y', '0')); ?>") || 0,
            scale: parseFloat("<?php echo e(branding('report_header_image_scale', '1.0')); ?>") || 1.0,
            fit: "<?php echo e(branding('report_header_image_fit', 'contain')); ?>",
            edge: "<?php echo e(branding('report_header_edge', 'flush')); ?>",
            offsetY: parseInt("<?php echo e(branding('report_header_offset_y', '0')); ?>") || 0
        },
        footer: {
            height: parseInt("<?php echo e(branding('report_footer_image_height', '50')); ?>") || 50,
            posX: parseFloat("<?php echo e(branding('report_footer_image_pos_x', '0')); ?>") || 0,
            posY: parseFloat("<?php echo e(branding('report_footer_image_pos_y', '0')); ?>") || 0,
            scale: parseFloat("<?php echo e(branding('report_footer_image_scale', '1.0')); ?>") || 1.0,
            fit: "<?php echo e(branding('report_footer_image_fit', 'contain')); ?>",
            edge: "<?php echo e(branding('report_footer_edge', 'flush')); ?>",
            offsetY: parseInt("<?php echo e(branding('report_footer_offset_y', '0')); ?>") || 0
        }
    };

    function applyBannerTransform(type) {
        var s = bannerStudio[type];
        if (!s) return;
        var isHeader = type === 'header';
        var stage = document.getElementById('stage_' + type);
        var viewport = document.getElementById('viewport_' + type);
        var img = document.getElementById(isHeader ? 'previewHeaderImg' : 'previewFooterImg');
        var slot = document.getElementById(isHeader ? 'a4HeaderSlot' : 'a4FooterSlot');

        // Viewport Height
        if (viewport) {
            viewport.style.height = s.height + 'px';
            if (s.edge === 'flush') {
                viewport.classList.add('is-flush');
            } else {
                viewport.classList.remove('is-flush');
            }
        }

        // Slot Positioning on A4 Document Page (Flush 0px to paper edge vs margin inset)
        if (slot) {
            if (isHeader) {
                if (s.edge === 'flush' && s.offsetY === 0) {
                    slot.style.margin = '0';
                    slot.style.width = '100%';
                } else if (s.edge === 'flush') {
                    slot.style.margin = s.offsetY + 'px 0 0 0';
                    slot.style.width = '100%';
                } else {
                    slot.style.margin = (s.offsetY + 28) + 'px 14mm 0 14mm';
                    slot.style.width = 'auto';
                }
            } else { // footer
                if (s.edge === 'flush' && s.offsetY === 0) {
                    slot.style.margin = '0';
                    slot.style.width = '100%';
                } else if (s.edge === 'flush') {
                    slot.style.margin = '0 0 ' + s.offsetY + 'px 0';
                    slot.style.width = '100%';
                } else {
                    slot.style.margin = '0 14mm ' + (s.offsetY + 20) + 'px 14mm';
                    slot.style.width = 'auto';
                }
            }
        }

        // Stage Transform (pan + scale)
        if (stage) {
            stage.style.transform = 'translate(' + Math.round(s.posX) + 'px, ' + Math.round(s.posY) + 'px) scale(' + s.scale.toFixed(2) + ')';
            stage.style.transformOrigin = 'center center';
        }

        // Fit mode
        if (img) {
            if (s.fit === 'width') {
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.maxWidth = 'none';
                img.style.maxHeight = 'none';
                img.style.objectFit = 'cover';
                img.style.display = 'block';
            } else {
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                img.style.objectFit = 'contain';
                img.style.display = 'block';
            }
        }

        // Edge bar height badge
        var badgeHeight = document.getElementById('badgeHeight_' + type);
        if (badgeHeight) badgeHeight.textContent = s.height + 'px';

        // Hidden inputs for form persistence
        var inH = document.getElementById('input_' + type + '_image_height');
        if (inH && document.activeElement !== inH) inH.value = s.height;

        var rangeH = document.getElementById('range_' + type + '_height');
        if (rangeH && document.activeElement !== rangeH) rangeH.value = s.height;

        var inPosX = document.getElementById('input_' + type + '_image_pos_x');
        if (inPosX) inPosX.value = Math.round(s.posX);

        var inPosY = document.getElementById('input_' + type + '_image_pos_y');
        if (inPosY) inPosY.value = Math.round(s.posY);

        var inScale = document.getElementById('input_' + type + '_image_scale');
        if (inScale) inScale.value = s.scale.toFixed(2);

        var inEdge = document.getElementById('input_' + type + '_edge');
        if (inEdge) inEdge.value = s.edge;

        var inOffsetY = document.getElementById('input_' + type + '_offset_y');
        if (inOffsetY) inOffsetY.value = s.offsetY;

        var rangeOffset = document.getElementById('range_' + type + '_offset');
        if (rangeOffset && document.activeElement !== rangeOffset) rangeOffset.value = s.offsetY;

        // Sidebar Readouts & Badges
        var badgePos = document.getElementById('badge_' + type + '_pos');
        if (badgePos) badgePos.textContent = 'X: ' + Math.round(s.posX) + 'px, Y: ' + Math.round(s.posY) + 'px';

        var zoomPct = Math.round(s.scale * 100);
        var badgeZoom = document.getElementById('badge_' + type + '_zoom');
        if (badgeZoom) badgeZoom.textContent = zoomPct + '%';

        var hudZoom = document.getElementById('hudZoom_' + type);
        if (hudZoom) hudZoom.textContent = zoomPct + '%';

        var rangeScale = document.getElementById('range_' + type + '_scale');
        if (rangeScale && document.activeElement !== rangeScale) rangeScale.value = zoomPct;

        var badgeOffsetVal = document.getElementById('badge_' + type + '_offset_val');
        if (badgeOffsetVal) badgeOffsetVal.textContent = s.offsetY + 'px';

        var badgeEdge = document.getElementById('badge_' + type + '_edge_status');
        if (badgeEdge) {
            if (s.edge === 'flush') {
                badgeEdge.className = 'badge bg-primary text-white';
                badgeEdge.textContent = s.offsetY === 0
                    ? '📌 Flush ' + (isHeader ? 'Top' : 'Bottom') + ' Edge (0px)'
                    : 'Flush ' + (isHeader ? 'Top' : 'Bottom') + ' (' + s.offsetY + 'px)';
            } else {
                badgeEdge.className = 'badge bg-secondary text-white';
                badgeEdge.textContent = 'Inside Margins';
            }
        }

        var btnFlush = document.getElementById('btn_' + type + '_snap_flush');
        var btnMargin = document.getElementById('btn_' + type + '_snap_margin');
        if (btnFlush && btnMargin) {
            if (s.edge === 'flush') {
                btnFlush.className = 'btn btn-xs btn-primary rounded-pill px-2';
                btnMargin.className = 'btn btn-xs btn-outline-secondary rounded-pill px-2';
            } else {
                btnFlush.className = 'btn btn-xs btn-outline-primary rounded-pill px-2';
                btnMargin.className = 'btn btn-xs btn-secondary rounded-pill px-2';
            }
        }
    }

    function snapToEdge(type, mode, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        var s = bannerStudio[type];
        s.edge = mode;
        if (mode === 'flush') {
            s.offsetY = 0;
            s.posX = 0;
            s.fit = 'width';
            var selFit = document.getElementById('sel_' + type + '_fit');
            if (selFit) selFit.value = 'width';
        } else {
            s.offsetY = 0;
        }
        applyBannerTransform(type);
    }

    function setOffsetYFromRange(type, val) {
        var offset = Math.max(0, Math.min(100, parseInt(val) || 0));
        bannerStudio[type].offsetY = offset;
        if (offset === 0) {
            bannerStudio[type].edge = 'flush';
        }
        applyBannerTransform(type);
    }

    function setHeightPreset(type, h) {
        bannerStudio[type].height = Math.round(Math.max(20, Math.min(400, parseInt(h) || 50)));
        applyBannerTransform(type);
    }

    function setHeightFromInput(type, val) {
        bannerStudio[type].height = Math.round(Math.max(20, Math.min(400, parseInt(val) || 50)));
        applyBannerTransform(type);
    }

    function setHeightFromRange(type, val) {
        bannerStudio[type].height = Math.round(Math.max(20, Math.min(400, parseInt(val) || 50)));
        applyBannerTransform(type);
    }

    function syncHeight(type, val) {
        setHeightFromInput(type, val);
    }

    function adjustScale(type, delta, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        var cur = bannerStudio[type].scale;
        bannerStudio[type].scale = Math.min(3.5, Math.max(0.3, parseFloat((cur + delta).toFixed(2))));
        applyBannerTransform(type);
    }

    function setScaleFromRange(type, val) {
        var pct = parseInt(val) || 100;
        bannerStudio[type].scale = Math.min(3.5, Math.max(0.3, parseFloat((pct / 100).toFixed(2))));
        applyBannerTransform(type);
    }

    function nudgePos(type, dx, dy) {
        bannerStudio[type].posX += dx;
        bannerStudio[type].posY += dy;
        applyBannerTransform(type);
    }

    function setFitMode(type, fit) {
        bannerStudio[type].fit = fit;
        applyBannerTransform(type);
    }

    function resetBannerPosition(type, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        bannerStudio[type].posX = 0;
        bannerStudio[type].posY = 0;
        bannerStudio[type].scale = 1.0;
        bannerStudio[type].offsetY = 0;
        bannerStudio[type].edge = 'flush';
        applyBannerTransform(type);
    }

    function autoFitBanner(type, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        var imgId = type === 'header' ? 'previewHeaderImg' : 'previewFooterImg';
        var img = document.getElementById(imgId);
        if (!img) return;

        function applyFit() {
            var nw = img.naturalWidth;
            var nh = img.naturalHeight;
            if (!nw || !nh) {
                bannerStudio[type].posX = 0;
                bannerStudio[type].posY = 0;
                bannerStudio[type].scale = 1.0;
                applyBannerTransform(type);
                return;
            }

            var sheet = document.getElementById('a4Canvas');
            var viewport = document.getElementById('viewport_' + type);
            var availableWidth = (viewport && viewport.clientWidth) ? viewport.clientWidth : (sheet ? sheet.clientWidth : 530);

            var targetHeight = Math.round(availableWidth * (nh / nw));
            targetHeight = Math.max(25, Math.min(380, targetHeight));

            bannerStudio[type].height = targetHeight;
            bannerStudio[type].posX = 0;
            bannerStudio[type].posY = 0;
            bannerStudio[type].scale = 1.0;
            bannerStudio[type].fit = 'width';

            var fitSelect = document.getElementById('sel_' + type + '_fit');
            if (fitSelect) {
                fitSelect.value = 'width';
            }

            applyBannerTransform(type);

            var badge = document.getElementById('badgeHeight_' + type);
            if (badge) {
                badge.textContent = '✓ Fitted ' + targetHeight + 'px';
                setTimeout(function() {
                    badge.textContent = targetHeight + 'px';
                }, 1500);
            }
        }

        if (img.complete && img.naturalWidth) {
            applyFit();
        } else {
            img.onload = applyFit;
        }
    }

    function setupCanvasStudio(type) {
        var viewport = document.getElementById('viewport_' + type);
        if (!viewport) return;

        var isHeader = type === 'header';
        var activeMode = null; // 'pan' | 'scale' | 'edge-resize'
        var startPointerX = 0;
        var startPointerY = 0;
        var initialPosX = 0;
        var initialPosY = 0;
        var initialOffsetY = 0;
        var initialScale = 1.0;
        var initialHeight = 90;
        var initialDist = 0;
        var activeHandle = null;

        // Visual focus tracking
        viewport.addEventListener('pointerdown', function(e) {
            // Ignore click inside HUD buttons
            if (e.target.closest('.banner-canvas-hud')) return;

            viewport.classList.add('is-active-focus');

            // 1. Check if user clicked on corner scale handle
            var handleEl = e.target.closest('.canvas-handle');
            if (handleEl) {
                e.preventDefault();
                e.stopPropagation();
                activeMode = 'scale';
                activeHandle = handleEl.getAttribute('data-handle');
                var rect = viewport.getBoundingClientRect();
                var centerX = rect.left + rect.width / 2;
                var centerY = rect.top + rect.height / 2;
                initialDist = Math.hypot(e.clientX - centerX, e.clientY - centerY) || 1;
                initialScale = bannerStudio[type].scale;
                document.body.style.cursor = handleEl.style.cursor || 'nwse-resize';
                document.body.style.userSelect = 'none';
                window.addEventListener('pointermove', onPointerMove, { passive: false });
                window.addEventListener('pointerup', onPointerUp);
                window.addEventListener('pointercancel', onPointerUp);
                return;
            }

            // 2. Check if user clicked on edge height resize bar
            var edgeBarEl = e.target.closest('.canvas-edge-bar');
            if (edgeBarEl) {
                e.preventDefault();
                e.stopPropagation();
                activeMode = 'edge-resize';
                startPointerY = e.clientY;
                initialHeight = bannerStudio[type].height;
                edgeBarEl.classList.add('is-dragging');
                document.body.style.cursor = 'ns-resize';
                document.body.style.userSelect = 'none';
                window.addEventListener('pointermove', onPointerMove, { passive: false });
                window.addEventListener('pointerup', onPointerUp);
                window.addEventListener('pointercancel', onPointerUp);
                return;
            }

            // 3. User clicked anywhere else on viewport -> DRAG & MOVE ON DOCUMENT!
            e.preventDefault();
            activeMode = 'pan';
            startPointerX = e.clientX;
            startPointerY = e.clientY;
            initialPosX = bannerStudio[type].posX;
            initialPosY = bannerStudio[type].posY;
            initialOffsetY = bannerStudio[type].offsetY;

            viewport.classList.add('is-dragging-move');
            document.body.style.cursor = 'grabbing';
            document.body.style.userSelect = 'none';

            window.addEventListener('pointermove', onPointerMove, { passive: false });
            window.addEventListener('pointerup', onPointerUp);
            window.addEventListener('pointercancel', onPointerUp);
        });

        function onPointerMove(e) {
            if (!activeMode) return;
            e.preventDefault();

            if (activeMode === 'pan') {
                var dx = e.clientX - startPointerX;
                var dy = e.clientY - startPointerY;

                // Horizontal movement shifts banner/image horizontally
                bannerStudio[type].posX = Math.round(initialPosX + dx);

                var hint = document.getElementById('moveHint_' + type);

                if (e.shiftKey) {
                    // Shift held: fine tune vertical crop inside the frame
                    bannerStudio[type].posY = Math.round(initialPosY + dy);
                    if (hint) {
                        hint.innerHTML = '<i class="bi bi-crop"></i> Pan Crop: Y ' + Math.round(bannerStudio[type].posY) + 'px';
                    }
                } else {
                    // Standard drag: physically position banner vertically on document
                    var newOffset;
                    if (isHeader) {
                        // Header: dragging down increases gap from top edge; dragging up closes gap
                        newOffset = Math.round(initialOffsetY + dy);
                    } else {
                        // Footer: dragging down closes gap to bottom edge; dragging up increases gap
                        newOffset = Math.round(initialOffsetY - dy);
                    }

                    // Snap threshold: within 6px of the paper edge snaps directly to 0px flush!
                    if (newOffset <= 6) {
                        newOffset = 0;
                        bannerStudio[type].edge = 'flush';
                        if (hint) {
                            hint.innerHTML = '📌 Snapped Flush to ' + (isHeader ? 'Top' : 'Bottom') + ' (0px)';
                        }
                    } else {
                        if (hint) {
                            hint.innerHTML = (isHeader ? 'Top Gap: ' : 'Bottom Gap: ') + newOffset + 'px';
                        }
                    }

                    bannerStudio[type].offsetY = Math.max(0, Math.min(120, newOffset));
                }

                applyBannerTransform(type);
            } else if (activeMode === 'scale') {
                var rect = viewport.getBoundingClientRect();
                var centerX = rect.left + rect.width / 2;
                var centerY = rect.top + rect.height / 2;
                var curDist = Math.hypot(e.clientX - centerX, e.clientY - centerY);
                var ratio = curDist / (initialDist || 1);
                var newScale = Math.min(3.5, Math.max(0.3, parseFloat((initialScale * ratio).toFixed(2))));
                bannerStudio[type].scale = newScale;
                applyBannerTransform(type);
            } else if (activeMode === 'edge-resize') {
                var delta = isHeader ? (e.clientY - startPointerY) : (startPointerY - e.clientY);
                var newH = Math.round(Math.max(20, Math.min(400, initialHeight + delta)));
                bannerStudio[type].height = newH;
                applyBannerTransform(type);
            }
        }

        function onPointerUp(e) {
            if (!activeMode) return;
            activeMode = null;
            viewport.classList.remove('is-dragging-move');
            var edgeBarEl = viewport.querySelector('.canvas-edge-bar');
            if (edgeBarEl) edgeBarEl.classList.remove('is-dragging');

            var hint = document.getElementById('moveHint_' + type);
            if (hint) {
                hint.innerHTML = '<i class="bi bi-arrows-move"></i> Drag to Move';
            }

            document.body.style.cursor = '';
            document.body.style.userSelect = '';

            window.removeEventListener('pointermove', onPointerMove);
            window.removeEventListener('pointerup', onPointerUp);
            window.removeEventListener('pointercancel', onPointerUp);
        }

        // Mouse Wheel Zoom
        viewport.addEventListener('wheel', function(e) {
            e.preventDefault();
            var step = e.deltaY < 0 ? 0.05 : -0.05;
            var cur = bannerStudio[type].scale;
            bannerStudio[type].scale = Math.min(3.5, Math.max(0.3, parseFloat((cur + step).toFixed(2))));
            applyBannerTransform(type);
        }, { passive: false });

        // Blur active focus on outside click
        document.addEventListener('pointerdown', function(e) {
            if (!viewport.contains(e.target)) {
                viewport.classList.remove('is-active-focus');
            }
        });
    }

    // ── Real-time WYSIWYG Sync ──
    function updateLivePreview() {
        // Line 1
        var p1 = document.getElementById('prev_line1');
        p1.textContent = document.getElementById('text_line1').value;
        p1.style.fontSize = document.getElementById('fsize_line1').value;
        p1.style.color = document.getElementById('fcolor_line1').value;

        // Line 2
        var p2 = document.getElementById('prev_line2');
        p2.textContent = document.getElementById('text_line2').value;
        p2.style.fontSize = document.getElementById('fsize_line2').value;
        p2.style.color = document.getElementById('fcolor_line2').value;

        // Line 3
        var p3 = document.getElementById('prev_line3');
        p3.textContent = document.getElementById('text_line3').value;
        p3.style.fontSize = document.getElementById('fsize_line3').value;
        p3.style.color = document.getElementById('fcolor_line3').value;

        // Line 4
        var p4 = document.getElementById('prev_line4');
        p4.textContent = document.getElementById('text_line4').value;
        p4.style.fontSize = document.getElementById('fsize_line4').value;
        p4.style.color = document.getElementById('fcolor_line4').value;

        // Line 5
        var p5 = document.getElementById('prev_line5');
        p5.textContent = document.getElementById('text_line5').value;
        p5.style.fontSize = document.getElementById('fsize_line5').value;
        p5.style.color = document.getElementById('fcolor_line5').value;

        // Left Seal
        var showLeft = document.getElementById('chk_left_logo_show').checked;
        var boxLeft = document.getElementById('previewLeftSealBox');
        var circLeft = document.getElementById('previewLeftCircle');
        var sizeLeft = document.getElementById('range_left_size').value;
        var shapeLeft = document.getElementById('sel_left_shape').value;
        boxLeft.style.display = showLeft ? '' : 'none';
        circLeft.style.width = sizeLeft + 'px';
        circLeft.style.height = sizeLeft + 'px';
        circLeft.style.border = 'none';
        if (shapeLeft === 'none') {
            circLeft.classList.remove('has-ring');
            circLeft.classList.add('no-ring');
        } else {
            circLeft.classList.add('has-ring');
            circLeft.classList.remove('no-ring');
        }

        // Right Seal
        var showRight = document.getElementById('chk_right_logo_show').checked;
        var boxRight = document.getElementById('previewRightSealBox');
        var circRight = document.getElementById('previewRightCircle');
        var sizeRight = document.getElementById('range_right_size').value;
        var shapeRight = document.getElementById('sel_right_shape').value;
        boxRight.style.display = showRight ? '' : 'none';
        circRight.style.width = sizeRight + 'px';
        circRight.style.height = sizeRight + 'px';
        circRight.style.border = 'none';
        if (shapeRight === 'none') {
            circRight.classList.remove('has-ring');
            circRight.classList.add('no-ring');
        } else {
            circRight.classList.add('has-ring');
            circRight.classList.remove('no-ring');
        }

        // Divider
        var ruleStyle = document.getElementById('sel_divider_style').value;
        var ruleColor = document.getElementById('fcolor_divider').value;
        var ruleEl = document.getElementById('previewRule');
        ruleEl.className = 'rule-' + ruleStyle;
        ruleEl.style.borderColor = ruleColor;
        ruleEl.style.borderTopColor = ruleColor;
        ruleEl.style.borderBottomColor = ruleColor;
        ruleEl.style.backgroundColor = ruleColor;

        // Banner Canvas Studio Transforms
        applyBannerTransform('header');
        applyBannerTransform('footer');

        // Footer structured text
        document.getElementById('prev_foot_title').textContent = document.getElementById('input_footer_left_title').value;
        document.getElementById('prev_foot_line1').textContent = document.getElementById('input_footer_left_line1').value;
        document.getElementById('prev_foot_line2').textContent = document.getElementById('input_footer_left_line2').value;
        document.getElementById('prev_foot_center').textContent = document.getElementById('input_footer_center_text').value;
        var noticeEl = document.getElementById('prev_foot_notice');
        if (noticeEl) noticeEl.textContent = document.getElementById('input_footer_system_notice').value;

        var showTime = document.getElementById('chk_footer_time').checked;
        document.getElementById('prev_foot_time').style.display = showTime ? '' : 'none';

        var showPage = document.getElementById('chk_footer_page').checked;
        document.getElementById('prev_foot_page').style.display = showPage ? '' : 'none';

        var showDivider = document.getElementById('chk_footer_divider').checked;
        document.getElementById('previewFooterWrap').style.borderTop = showDivider ? '1px solid #cbd5e1' : 'none';
    }

    // Initialize Canvas Studio & drag listeners immediately
    setupCanvasStudio('header');
    setupCanvasStudio('footer');
    applyBannerTransform('header');
    applyBannerTransform('footer');

    // ── Template Presets ──
    function loadPreset(key) {
        setHeaderMode('builder');

        if (key === 'suc') {
            document.getElementById('text_line1').value = 'Republic of the Philippines';
            document.getElementById('fsize_line1').value = '9.5px';
            document.getElementById('fcolor_line1').value = '#334155';

            document.getElementById('text_line2').value = 'STATE UNIVERSITY SYSTEM';
            document.getElementById('fsize_line2').value = '16.5px';
            document.getElementById('fcolor_line2').value = '#0f2b48';

            document.getElementById('text_line3').value = 'OFFICE OF PHYSICAL PLANT & AUXILIARY SERVICES';
            document.getElementById('fsize_line3').value = '10.5px';
            document.getElementById('fcolor_line3').value = '#475569';

            document.getElementById('text_line4').value = 'University Boulevard, Main Campus, Metro Manila, Philippines';
            document.getElementById('fsize_line4').value = '8.5px';
            document.getElementById('fcolor_line4').value = '#475569';

            document.getElementById('text_line5').value = 'Tel: (02) 8911-2000 • Web: sure.university.edu.ph';
            document.getElementById('fsize_line5').value = '8.5px';
            document.getElementById('fcolor_line5').value = '#475569';

            document.getElementById('sel_divider_style').value = 'double';
            document.getElementById('fcolor_divider').value = '#0f2b48';
            document.getElementById('sel_left_shape').value = 'circle';
            document.getElementById('sel_right_shape').value = 'circle';
            document.getElementById('chk_left_logo_show').checked = true;
            document.getElementById('chk_right_logo_show').checked = true;
        } else if (key === 'city') {
            document.getElementById('text_line1').value = 'Republic of the Philippines • City Government of Manila';
            document.getElementById('fsize_line1').value = '9.5px';
            document.getElementById('fcolor_line1').value = '#1e293b';

            document.getElementById('text_line2').value = 'CITY COLLEGE OF MANILA';
            document.getElementById('fsize_line2').value = '16.5px';
            document.getElementById('fcolor_line2').value = '#1e3a8a';

            document.getElementById('text_line3').value = 'CAMPUS GENERAL SERVICES & FACILITIES DIVISION';
            document.getElementById('fsize_line3').value = '10.5px';
            document.getElementById('fcolor_line3').value = '#3b82f6';

            document.getElementById('text_line4').value = 'Padre Burgos Ave, Ermita, Manila, 1000 Metro Manila';
            document.getElementById('fsize_line4').value = '8.5px';
            document.getElementById('fcolor_line4').value = '#475569';

            document.getElementById('text_line5').value = 'Email: facilities@ccm.edu.ph • (02) 8527-1111';
            document.getElementById('fsize_line5').value = '8.5px';
            document.getElementById('fcolor_line5').value = '#475569';

            document.getElementById('sel_divider_style').value = 'single';
            document.getElementById('fcolor_divider').value = '#1e3a8a';
            document.getElementById('sel_left_shape').value = 'circle';
            document.getElementById('sel_right_shape').value = 'none';
            document.getElementById('chk_left_logo_show').checked = true;
            document.getElementById('chk_right_logo_show').checked = true;
        } else if (key === 'private') {
            document.getElementById('text_line1').value = 'OFFICE OF THE REGISTRAR & CAMPUS VENUES';
            document.getElementById('fsize_line1').value = '8.5px';
            document.getElementById('fcolor_line1').value = '#64748b';

            document.getElementById('text_line2').value = 'METROPOLITAN UNIVERSITY OF THE PHILIPPINES';
            document.getElementById('fsize_line2').value = '16px';
            document.getElementById('fcolor_line2').value = '#0f172a';

            document.getElementById('text_line3').value = 'DIVISION OF AUXILIARY & COMMERCIAL SERVICES';
            document.getElementById('fsize_line3').value = '10px';
            document.getElementById('fcolor_line3').value = '#0d9488';

            document.getElementById('text_line4').value = 'Campus Hub, BGC, Taguig City, Philippines';
            document.getElementById('fsize_line4').value = '8.5px';
            document.getElementById('fcolor_line4').value = '#64748b';

            document.getElementById('text_line5').value = 'contact@metrouniv.edu.ph • www.metrouniv.edu.ph';
            document.getElementById('fsize_line5').value = '8.5px';
            document.getElementById('fcolor_line5').value = '#64748b';

            document.getElementById('sel_divider_style').value = 'thick';
            document.getElementById('fcolor_divider').value = '#0d9488';
            document.getElementById('sel_left_shape').value = 'none';
            document.getElementById('sel_right_shape').value = 'none';
            document.getElementById('chk_left_logo_show').checked = true;
            document.getElementById('chk_right_logo_show').checked = false;
        } else if (key === 'minimal') {
            document.getElementById('text_line1').value = '';
            document.getElementById('text_line2').value = 'CAMPUS VENUE RESERVATION SYSTEM';
            document.getElementById('fsize_line2').value = '14px';
            document.getElementById('fcolor_line2').value = '#0f172a';

            document.getElementById('text_line3').value = 'Certified Administrative Reports & Venue Scheduling';
            document.getElementById('fsize_line3').value = '9.5px';
            document.getElementById('fcolor_line3').value = '#64748b';

            document.getElementById('text_line4').value = 'Main Campus Administration Wing';
            document.getElementById('fsize_line4').value = '8.5px';
            document.getElementById('fcolor_line4').value = '#94a3b8';

            document.getElementById('text_line5').value = 'facilities@university.edu';
            document.getElementById('fsize_line5').value = '8.5px';
            document.getElementById('fcolor_line5').value = '#94a3b8';

            document.getElementById('sel_divider_style').value = 'none';
            document.getElementById('chk_left_logo_show').checked = true;
            document.getElementById('chk_right_logo_show').checked = false;
        }

        updateLivePreview();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user() && auth()->user()->isSuperAdmin() ? 'layouts.superadmin' : 'layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/superadmin/settings/system/category/reports.blade.php ENDPATH**/ ?>