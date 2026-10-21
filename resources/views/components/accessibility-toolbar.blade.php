@if(branding('enable_accessibility', '1'))
<!-- Accessibility Suite Toggle Button -->
<div id="a11y-suite-trigger-wrapper">
    <button class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
            id="a11y-suite-toggle" 
            aria-label="Open Accessibility Suite" 
            aria-expanded="false" 
            title="Accessibility Tools">
        <i class="bi bi-universal-access-circle fs-2 text-white"></i>
    </button>
</div>

<!-- Draggable Accessibility Suite Popup -->
<div id="a11y-suite-popup" class="a11y-suite-popup shadow-lg" role="dialog" aria-labelledby="a11y-suite-title" aria-hidden="true" tabindex="-1">
    <!-- Header / Drag Handle -->
    <div class="a11y-suite-header" id="a11y-suite-header">
        <div class="d-flex align-items-center">
            <i class="bi bi-universal-access-circle me-2 fs-4"></i>
            <h5 id="a11y-suite-title" class="mb-0 fw-bold small text-uppercase letter-spacing-1">Accessibility Suite</h5>
        </div>
        <button class="a11y-suite-close" id="a11y-suite-close" aria-label="Close Accessibility Suite">&times;</button>
    </div>
    
    <div class="a11y-suite-body">
        <!-- Section 1: Experience Profiles -->
        <div class="a11y-suite-section">
            <h6 class="a11y-suite-section-title">
                <i class="bi bi-person-gear me-2"></i> Browsing Experience Profiles
            </h6>
            
            <div class="a11y-suite-grid-list">
                <!-- Seizure Safety -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Seizure Safety</span>
                        <small class="a11y-suite-card-desc">Reduces motion & visual triggers</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="profile-seizure-safety" data-a11y-profile="seizure-safety">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- Low Vision Support -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Low Vision Support</span>
                        <small class="a11y-suite-card-desc">Improves font legibility & clarity</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="profile-low-vision" data-a11y-profile="low-vision">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- ADHD Friendly -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">ADHD Friendly</span>
                        <small class="a11y-suite-card-desc">Ruler focus & lower distractions</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="profile-adhd" data-a11y-profile="adhd">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- Reading & Cognitive -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Reading & Cognitive</span>
                        <small class="a11y-suite-card-desc">Simplifies content structure</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="profile-cognitive" data-a11y-profile="cognitive">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- Keyboard Navigation -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Keyboard Navigation</span>
                        <small class="a11y-suite-card-desc">Keyboard helper guide & triggers</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="profile-keyboard-nav" data-a11y-profile="keyboard-nav">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- Screen Reader -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Screen Reader</span>
                        <small class="a11y-suite-card-desc">TTS voice narrator on hover/focus</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="profile-screen-reader" data-a11y-profile="screen-reader">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- Older Adults -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Older Adults</span>
                        <small class="a11y-suite-card-desc">Large layout targets & contrast</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="profile-older-adults" data-a11y-profile="older-adults">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Section 2: Color Adjustments -->
        <div class="a11y-suite-section">
            <h6 class="a11y-suite-section-title">
                <i class="bi bi-palette me-2"></i> Color Adjustments
            </h6>
            
            <div class="a11y-suite-modes-grid">
                <button class="a11y-suite-mode-btn" data-a11y-mode="dark" id="btn-mode-dark">
                    <i class="bi bi-moon-stars d-block fs-5 mb-1"></i>
                    Dark Contrast
                </button>
                <button class="a11y-suite-mode-btn" data-a11y-mode="light" id="btn-mode-light">
                    <i class="bi bi-brightness-high d-block fs-5 mb-1"></i>
                    Light Contrast
                </button>
                <button class="a11y-suite-mode-btn" data-a11y-mode="high" id="btn-mode-high">
                    <i class="bi bi-circle-half d-block fs-5 mb-1"></i>
                    High Contrast
                </button>
                <button class="a11y-suite-mode-btn" data-a11y-mode="monochrome" id="btn-mode-monochrome">
                    <i class="bi bi-droplet-slash d-block fs-5 mb-1"></i>
                    Monochrome
                </button>
                <button class="a11y-suite-mode-btn" data-a11y-mode="low-sat" id="btn-mode-low-sat">
                    <i class="bi bi-droplet d-block fs-5 mb-1 opacity-50"></i>
                    Low Saturation
                </button>
                <button class="a11y-suite-mode-btn" data-a11y-mode="high-sat" id="btn-mode-high-sat">
                    <i class="bi bi-droplet-fill d-block fs-5 mb-1 text-primary"></i>
                    High Saturation
                </button>
            </div>

            <!-- Color Pickers -->
            <div class="a11y-suite-pickers">
                <!-- Text Colors -->
                <div class="a11y-suite-picker-group mb-2">
                    <span class="a11y-suite-picker-label"><i class="bi bi-type me-1"></i> Text Colors</span>
                    <div class="a11y-suite-circles" id="picker-text-colors">
                        <button class="a11y-suite-circle color-blue" data-a11y-color-type="text" data-a11y-color="#1e40af" title="Blue"></button>
                        <button class="a11y-suite-circle color-purple" data-a11y-color-type="text" data-a11y-color="#6b21a8" title="Purple"></button>
                        <button class="a11y-suite-circle color-red" data-a11y-color-type="text" data-a11y-color="#b91c1c" title="Red"></button>
                        <button class="a11y-suite-circle color-orange" data-a11y-color-type="text" data-a11y-color="#c2410c" title="Orange"></button>
                        <button class="a11y-suite-circle color-teal" data-a11y-color-type="text" data-a11y-color="#0f766e" title="Teal"></button>
                        <button class="a11y-suite-circle color-green" data-a11y-color-type="text" data-a11y-color="#15803d" title="Green"></button>
                        <button class="a11y-suite-circle color-white border" data-a11y-color-type="text" data-a11y-color="#ffffff" title="White"></button>
                        <button class="a11y-suite-circle color-black" data-a11y-color-type="text" data-a11y-color="#000000" title="Black"></button>
                    </div>
                </div>

                <!-- Title Colors -->
                <div class="a11y-suite-picker-group mb-2">
                    <span class="a11y-suite-picker-label"><i class="bi bi-hash me-1"></i> Title Colors</span>
                    <div class="a11y-suite-circles" id="picker-title-colors">
                        <button class="a11y-suite-circle color-blue" data-a11y-color-type="title" data-a11y-color="#1e40af" title="Blue"></button>
                        <button class="a11y-suite-circle color-purple" data-a11y-color-type="title" data-a11y-color="#6b21a8" title="Purple"></button>
                        <button class="a11y-suite-circle color-red" data-a11y-color-type="title" data-a11y-color="#b91c1c" title="Red"></button>
                        <button class="a11y-suite-circle color-orange" data-a11y-color-type="title" data-a11y-color="#c2410c" title="Orange"></button>
                        <button class="a11y-suite-circle color-teal" data-a11y-color-type="title" data-a11y-color="#0f766e" title="Teal"></button>
                        <button class="a11y-suite-circle color-green" data-a11y-color-type="title" data-a11y-color="#15803d" title="Green"></button>
                        <button class="a11y-suite-circle color-white border" data-a11y-color-type="title" data-a11y-color="#ffffff" title="White"></button>
                        <button class="a11y-suite-circle color-black" data-a11y-color-type="title" data-a11y-color="#000000" title="Black"></button>
                    </div>
                </div>

                <!-- Background Colors -->
                <div class="a11y-suite-picker-group">
                    <span class="a11y-suite-picker-label"><i class="bi bi-window-sidebar me-1"></i> Background Colors</span>
                    <div class="a11y-suite-circles" id="picker-bg-colors">
                        <button class="a11y-suite-circle color-blue" data-a11y-color-type="bg" data-a11y-color="#1e40af" title="Blue"></button>
                        <button class="a11y-suite-circle color-purple" data-a11y-color-type="bg" data-a11y-color="#6b21a8" title="Purple"></button>
                        <button class="a11y-suite-circle color-red" data-a11y-color-type="bg" data-a11y-color="#b91c1c" title="Red"></button>
                        <button class="a11y-suite-circle color-orange" data-a11y-color-type="bg" data-a11y-color="#c2410c" title="Orange"></button>
                        <button class="a11y-suite-circle color-teal" data-a11y-color-type="bg" data-a11y-color="#0f766e" title="Teal"></button>
                        <button class="a11y-suite-circle color-green" data-a11y-color-type="bg" data-a11y-color="#15803d" title="Green"></button>
                        <button class="a11y-suite-circle color-white border" data-a11y-color-type="bg" data-a11y-color="#ffffff" title="White"></button>
                        <button class="a11y-suite-circle color-black" data-a11y-color-type="bg" data-a11y-color="#000000" title="Black"></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Orientation Adjustments -->
        <div class="a11y-suite-section">
            <h6 class="a11y-suite-section-title">
                <i class="bi bi-sliders me-2"></i> Orientation Adjustments
            </h6>
            
            <div class="a11y-suite-grid-list">
                <!-- Text Scaling -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Text Scaling</span>
                        <small class="a11y-suite-card-desc">Adjust page font size</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-light border px-2 py-0" id="scale-down" aria-label="Decrease Scale">-</button>
                        <span class="fw-bold small" id="scale-val">100%</span>
                        <button class="btn btn-sm btn-light border px-2 py-0" id="scale-up" aria-label="Increase Scale">+</button>
                    </div>
                </div>

                <!-- Mute Sounds -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Mute Sounds</span>
                        <small class="a11y-suite-card-desc">Blocks audio playback</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="control-mute" data-a11y-control="mute">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- Hide Images -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Hide Images</span>
                        <small class="a11y-suite-card-desc">Removes all images & svgs</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="control-hide-images" data-a11y-control="hide-images">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- Stop Animations -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Stop Animations</span>
                        <small class="a11y-suite-card-desc">Freezes all transitions</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="control-stop-animations" data-a11y-control="stop-animations">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>

                <!-- Highlight Focus -->
                <div class="a11y-suite-card">
                    <div class="a11y-suite-card-content">
                        <span class="a11y-suite-card-label">Highlight Focus</span>
                        <small class="a11y-suite-card-desc">Prominent focus borders</small>
                    </div>
                    <label class="a11y-suite-switch">
                        <input type="checkbox" id="control-highlight-focus" data-a11y-control="highlight-focus">
                        <span class="a11y-suite-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    
    <div class="a11y-suite-footer">
        <button class="btn btn-danger w-100 fw-bold text-white rounded-3 py-2 shadow-sm" id="a11y-suite-reset">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset All Settings
        </button>
    </div>
</div>

<!-- Helper Elements -->
<div id="a11y-reading-ruler" class="a11y-reading-ruler" aria-hidden="true"></div>
<div id="a11y-keyboard-guide" class="a11y-keyboard-guide shadow-lg" aria-hidden="true">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-keyboard me-2 fs-5 text-primary"></i>
        <h6 class="mb-0 fw-bold">Keyboard Navigation Mode</h6>
    </div>
    <ul class="mb-0 small ps-3">
        <li><strong>Tab</strong> to move forward</li>
        <li><strong>Shift + Tab</strong> to move backward</li>
        <li><strong>Enter</strong> to activate</li>
        <li><strong>Esc</strong> to close suite</li>
    </ul>
</div>

<style>
    /* ── Floating Trigger ── */
    #a11y-suite-trigger-wrapper {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
        touch-action: none;
        user-select: none;
        -webkit-user-select: none;
    }
    #a11y-suite-toggle {
        width: 56px;
        height: 56px;
        cursor: grab;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease;
        border: 2px solid rgba(255,255,255,0.85);
        user-select: none;
        -webkit-user-select: none;
    }
    #a11y-suite-toggle:hover {
        transform: scale(1.08);
        box-shadow: 0 10px 25px rgba(var(--brand-primary-rgb, 37, 99, 235), 0.4);
    }
    #a11y-suite-trigger-wrapper.is-dragging #a11y-suite-toggle {
        cursor: grabbing !important;
        transform: scale(1.12) !important;
        box-shadow: 0 14px 30px rgba(var(--brand-primary-rgb, 37, 99, 235), 0.55) !important;
        transition: none !important;
    }
    
    /* ── Draggable Popup ── */
    .a11y-suite-popup {
        position: fixed;
        top: 100px;
        right: 2rem;
        width: 380px;
        max-height: 85vh;
        background: #ffffff;
        border-radius: 16px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(0,0,0,0.08);
        overflow: hidden;
        user-select: none;
    }
    .a11y-suite-popup.is-open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }
    @media (max-width: 480px) {
        .a11y-suite-popup {
            width: calc(100% - 2rem);
            right: 1rem;
            left: 1rem;
            top: 1rem !important;
            bottom: 1rem !important;
            max-height: calc(100vh - 2rem);
        }
    }
    
    .a11y-suite-header {
        padding: 1rem 1.25rem;
        background: #0f172a;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: move;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .a11y-suite-close {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 1.5rem;
        line-height: 1;
        padding: 0;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    .a11y-suite-close:hover { opacity: 1; }
    
    .a11y-suite-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.25rem;
        background: #f8fafc;
    }
    
    .a11y-suite-footer {
        padding: 1rem 1.25rem;
        background: #ffffff;
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    /* ── Sections ── */
    .a11y-suite-section { margin-bottom: 1.5rem; }
    .a11y-suite-section-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }
    
    /* ── Experience Cards ── */
    .a11y-suite-grid-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .a11y-suite-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s;
    }
    .a11y-suite-card:hover { border-color: rgba(var(--brand-primary-rgb, 37, 99, 235), 0.2); background: #fff; }
    .a11y-suite-card-content { display: flex; flex-direction: column; }
    .a11y-suite-card-label { font-size: 0.85rem; font-weight: 700; color: #1e293b; }
    .a11y-suite-card-desc { font-size: 0.7rem; color: #64748b; margin-top: 1px; }
    
    /* ── Switches ── */
    .a11y-suite-switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
        flex-shrink: 0;
    }
    .a11y-suite-switch input { opacity: 0; width: 0; height: 0; }
    .a11y-suite-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #e2e8f0;
        transition: .3s;
        border-radius: 20px;
    }
    .a11y-suite-slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .a11y-suite-switch input:checked + .a11y-suite-slider { background-color: var(--brand-primary, #2563eb); }
    .a11y-suite-switch input:checked + .a11y-suite-slider:before { transform: translateX(20px); }
    
    /* ── Mode Buttons Grid ── */
    .a11y-suite-modes-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .a11y-suite-mode-btn {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 10px;
        padding: 0.75rem 0.5rem;
        font-size: 0.7rem;
        font-weight: 700;
        color: #475569;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .a11y-suite-mode-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .a11y-suite-mode-btn.active {
        background: var(--brand-primary, #2563eb);
        color: #ffffff !important;
        border-color: var(--brand-primary, #2563eb);
        box-shadow: 0 4px 12px rgba(var(--brand-primary-rgb, 37, 99, 235), 0.3);
    }
    .a11y-suite-mode-btn.active i { color: #ffffff !important; opacity: 1 !important; }
    
    /* ── Color Pickers ── */
    .a11y-suite-pickers { margin-top: 0.75rem; }
    .a11y-suite-picker-group { background: #ffffff; padding: 0.75rem; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05); }
    .a11y-suite-picker-label { display: block; font-size: 0.7rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 0.75rem; }
    .a11y-suite-circles { display: flex; flex-wrap: wrap; gap: 0.65rem; }
    .a11y-suite-circle {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
    }
    .a11y-suite-circle:hover { transform: scale(1.15); }
    .a11y-suite-circle.active {
        border-color: var(--brand-primary, #2563eb) !important;
        box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px var(--brand-primary, #2563eb);
    }
    
    /* Circle Colors */
    .color-blue { background: #1e40af; }
    .color-purple { background: #6b21a8; }
    .color-red { background: #b91c1c; }
    .color-orange { background: #c2410c; }
    .color-teal { background: #0f766e; }
    .color-green { background: #15803d; }
    .color-white { background: #ffffff; }
    .color-black { background: #000000; }
    
    /* ── Ruler & Guide ── */
    .a11y-reading-ruler {
        position: fixed; left: 0; width: 100%; height: 32px;
        background: rgba(254, 240, 138, 0.35);
        border-top: 2px solid #eab308; border-bottom: 2px solid #eab308;
        pointer-events: none; z-index: 100010; display: none;
    }
    html.a11y-profile-adhd .a11y-reading-ruler { display: block; }
    
    .a11y-keyboard-guide {
        position: fixed; bottom: 2rem; left: 2rem; z-index: 100002;
        background: #ffffff; color: #1e293b; padding: 1rem 1.25rem;
        border-radius: 12px; border: 2px solid var(--brand-primary, #2563eb);
        max-width: 280px; opacity: 0; visibility: hidden;
        transition: all 0.3s ease;
    }
    .a11y-keyboard-guide.show { opacity: 1; visibility: visible; }
    
    /* ── Global A11y Classes ── */
    html.a11y-profile-seizure-safety * { animation: none !important; transition: none !important; scroll-behavior: auto !important; }
    html.a11y-profile-low-vision { font-size: 1.2rem !important; }
    html.a11y-profile-low-vision p, html.a11y-profile-low-vision span, html.a11y-profile-low-vision li { font-weight: 500 !important; color: #000 !important; }
    html.a11y-profile-older-adults { font-size: 1.25rem !important; }
    html.a11y-profile-older-adults button, html.a11y-profile-older-adults a.btn { min-height: 48px !important; }
    
    /* ── DARK MODE ── */
    html.a11y-mode-dark { color-scheme: dark; }
    html.a11y-mode-dark body { background-color: #0f172a !important; color: #f1f5f9 !important; }
    html.a11y-mode-dark .card, 
    html.a11y-mode-dark .main-content,
    html.a11y-mode-dark .a11y-suite-popup,
    html.a11y-mode-dark .a11y-suite-footer { background-color: #1e293b !important; color: #f1f5f9 !important; border-color: rgba(255,255,255,0.1) !important; }
    html.a11y-mode-dark .a11y-suite-body,
    html.a11y-mode-dark .a11y-suite-card,
    html.a11y-mode-dark .a11y-suite-mode-btn,
    html.a11y-mode-dark .a11y-suite-picker-group { background-color: #0f172a !important; color: #f1f5f9 !important; border-color: rgba(255,255,255,0.08) !important; }
    html.a11y-mode-dark .a11y-suite-card-label { color: #f1f5f9 !important; }
    html.a11y-mode-dark .a11y-suite-card-desc { color: #94a3b8 !important; }
    html.a11y-mode-dark .a11y-suite-mode-btn:hover { background: #1e293b; }
    
    /* Orientation Control Classes */
    html.a11y-control-hide-images img,
    html.a11y-control-hide-images svg:not(.bi),
    html.a11y-control-hide-images [style*="background-image"] { display: none !important; }
    
    html.a11y-control-stop-animations * { animation: none !important; transition: none !important; }
    
    html.a11y-control-highlight-focus *:focus { outline: 4px solid #facc15 !important; outline-offset: 2px !important; }

    /* Other modes */
    html.a11y-mode-monochrome { filter: grayscale(100%) !important; }
    html.a11y-mode-low-sat { filter: saturate(30%) !important; }
    html.a11y-mode-high-sat { filter: saturate(200%) !important; }
    html.a11y-mode-high { filter: contrast(150%) !important; }
</style>

<script>
(function() {
    const STORAGE_KEY = 'a11y_suite_v3';
    const STATE = {
        isOpen: false,
        profiles: {},
        mode: null,
        colors: { text: null, title: null, bg: null },
        controls: {},
        scale: 1.0,
        pos: { x: null, y: null },
        triggerPos: { x: null, y: null }
    };

    const DOM = {
        triggerWrapper: document.getElementById('a11y-suite-trigger-wrapper'),
        trigger: document.getElementById('a11y-suite-toggle'),
        popup: document.getElementById('a11y-suite-popup'),
        header: document.getElementById('a11y-suite-header'),
        close: document.getElementById('a11y-suite-close'),
        reset: document.getElementById('a11y-suite-reset'),
        ruler: document.getElementById('a11y-reading-ruler'),
        guide: document.getElementById('a11y-keyboard-guide'),
        scaleVal: document.getElementById('scale-val')
    };

    function init() {
        loadState();
        bindEvents();
        applyState();
    }

    function loadState() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) Object.assign(STATE, JSON.parse(saved));
    }

    function saveState() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(STATE));
    }

    function bindEvents() {
        DOM.close.onclick = closePopup;
        DOM.reset.onclick = resetAll;

        // Draggable Trigger Icon (Mouse & Touch)
        let isDraggingTrigger = false;
        let triggerStartX = 0, triggerStartY = 0;
        let triggerOffsetX = 0, triggerOffsetY = 0;
        let hasMovedTrigger = false;

        function startTriggerDrag(e) {
            if (e.type === 'mousedown' && e.button !== 0) return;

            const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;

            if (!DOM.triggerWrapper) return;
            const rect = DOM.triggerWrapper.getBoundingClientRect();

            triggerStartX = clientX;
            triggerStartY = clientY;
            triggerOffsetX = clientX - rect.left;
            triggerOffsetY = clientY - rect.top;
            hasMovedTrigger = false;
            isDraggingTrigger = true;

            document.addEventListener('mousemove', onTriggerDrag);
            document.addEventListener('mouseup', stopTriggerDrag);
            document.addEventListener('touchmove', onTriggerDrag, { passive: false });
            document.addEventListener('touchend', stopTriggerDrag);
        }

        function onTriggerDrag(e) {
            if (!isDraggingTrigger) return;
            const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;

            const dx = clientX - triggerStartX;
            const dy = clientY - triggerStartY;

            if (!hasMovedTrigger && Math.hypot(dx, dy) > 4) {
                hasMovedTrigger = true;
                DOM.triggerWrapper.classList.add('is-dragging');
            }

            if (hasMovedTrigger) {
                if (e.cancelable) e.preventDefault();

                const elW = DOM.triggerWrapper.offsetWidth || 56;
                const elH = DOM.triggerWrapper.offsetHeight || 56;
                const minX = 8;
                const maxX = Math.max(minX, window.innerWidth - elW - 8);
                const minY = 8;
                const maxY = Math.max(minY, window.innerHeight - elH - 8);

                const newX = Math.max(minX, Math.min(maxX, clientX - triggerOffsetX));
                const newY = Math.max(minY, Math.min(maxY, clientY - triggerOffsetY));

                DOM.triggerWrapper.style.left = newX + 'px';
                DOM.triggerWrapper.style.top = newY + 'px';
                DOM.triggerWrapper.style.right = 'auto';
                DOM.triggerWrapper.style.bottom = 'auto';

                STATE.triggerPos = { x: newX, y: newY };
            }
        }

        function stopTriggerDrag() {
            if (!isDraggingTrigger) return;
            isDraggingTrigger = false;
            if (DOM.triggerWrapper) DOM.triggerWrapper.classList.remove('is-dragging');

            document.removeEventListener('mousemove', onTriggerDrag);
            document.removeEventListener('mouseup', stopTriggerDrag);
            document.removeEventListener('touchmove', onTriggerDrag);
            document.removeEventListener('touchend', stopTriggerDrag);

            if (hasMovedTrigger) {
                saveState();
            }
        }

        if (DOM.trigger) {
            DOM.trigger.addEventListener('mousedown', startTriggerDrag);
            DOM.trigger.addEventListener('touchstart', startTriggerDrag, { passive: true });

            DOM.trigger.addEventListener('click', function(e) {
                if (hasMovedTrigger) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    hasMovedTrigger = false;
                    return;
                }
                togglePopup();
            });
        }

        // Draggable Popup
        let isDragging = false;
        let startX, startY;
        DOM.header.onmousedown = (e) => {
            if (window.innerWidth <= 480) return;
            isDragging = true;
            startX = e.clientX - DOM.popup.offsetLeft;
            startY = e.clientY - DOM.popup.offsetTop;
            document.addEventListener('mousemove', onDrag);
            document.addEventListener('mouseup', stopDrag);
        };
        function onDrag(e) {
            if (!isDragging) return;
            DOM.popup.style.left = (e.clientX - startX) + 'px';
            DOM.popup.style.top = (e.clientY - startY) + 'px';
            DOM.popup.style.right = 'auto';
            STATE.pos = { x: DOM.popup.offsetLeft, y: DOM.popup.offsetTop };
        }
        function stopDrag() {
            isDragging = false;
            document.removeEventListener('mousemove', onDrag);
            document.removeEventListener('mouseup', stopDrag);
            saveState();
        }

        // Toggles
        document.querySelectorAll('[data-a11y-profile]').forEach(el => {
            el.onchange = () => {
                STATE.profiles[el.dataset.a11yProfile] = el.checked;
                applyState(); saveState();
            };
        });

        document.querySelectorAll('[data-a11y-control]').forEach(el => {
            el.onchange = () => {
                STATE.controls[el.dataset.a11yControl] = el.checked;
                applyState(); saveState();
            };
        });

        document.querySelectorAll('[data-a11y-mode]').forEach(el => {
            el.onclick = () => {
                const m = el.dataset.a11yMode;
                STATE.mode = (STATE.mode === m) ? null : m;
                applyState(); saveState();
            };
        });

        document.querySelectorAll('[data-a11y-color]').forEach(el => {
            el.onclick = () => {
                const { a11yColorType, a11yColor } = el.dataset;
                STATE.colors[a11yColorType] = (STATE.colors[a11yColorType] === a11yColor) ? null : a11yColor;
                applyState(); saveState();
            };
        });

        // Scale
        document.getElementById('scale-up').onclick = () => { STATE.scale = Math.min(1.8, STATE.scale + 0.1); applyState(); saveState(); };
        document.getElementById('scale-down').onclick = () => { STATE.scale = Math.max(0.7, STATE.scale - 0.1); applyState(); saveState(); };

        document.addEventListener('keydown', e => { if (e.key === 'Escape' && STATE.isOpen) closePopup(); });
        document.addEventListener('mousemove', e => { if (STATE.profiles.adhd) DOM.ruler.style.top = (e.clientY - 16) + 'px'; });
    }

    function togglePopup() { STATE.isOpen = !STATE.isOpen; applyPopupState(); saveState(); }
    function closePopup() { STATE.isOpen = false; applyPopupState(); saveState(); }
    function applyPopupState() {
        DOM.popup.classList.toggle('is-open', STATE.isOpen);
        DOM.trigger.setAttribute('aria-expanded', STATE.isOpen);
        if (STATE.isOpen) DOM.popup.focus();
    }

    function applyState() {
        const html = document.documentElement;
        
        // Ensure clean slate for profiles/controls classes
        [...html.classList].forEach(cls => {
            if (cls.startsWith('a11y-profile-') || cls.startsWith('a11y-control-')) {
                html.classList.remove(cls);
            }
        });

        Object.keys(STATE.profiles).forEach(p => {
            if (STATE.profiles[p]) html.classList.add(`a11y-profile-${p}`);
            const input = document.getElementById(`profile-${p}`);
            if (input) input.checked = !!STATE.profiles[p];
        });

        Object.keys(STATE.controls).forEach(c => {
            if (STATE.controls[c]) html.classList.add(`a11y-control-${c}`);
            const input = document.getElementById(`control-${c}`);
            if (input) input.checked = !!STATE.controls[c];
        });

        ['dark', 'light', 'high', 'monochrome', 'low-sat', 'high-sat'].forEach(m => {
            const active = STATE.mode === m;
            html.classList.toggle(`a11y-mode-${m}`, active);
            const btn = document.getElementById(`btn-mode-${m}`);
            if (btn) btn.classList.toggle('active', active);
        });

        // Dynamic CSS
        let style = document.getElementById('a11y-dynamic-css');
        if (!style) { style = document.createElement('style'); style.id = 'a11y-dynamic-css'; document.head.appendChild(style); }
        let css = '';
        if (STATE.colors.text) css += `html body, html p, html span, html li, html a:not(.btn) { color: ${STATE.colors.text} !important; }`;
        if (STATE.colors.title) css += `html h1, html h2, html h3, html h4, html h5, html h6 { color: ${STATE.colors.title} !important; }`;
        if (STATE.colors.bg) css += `html body, html main, html .card, html .main-content { background-color: ${STATE.colors.bg} !important; }`;
        style.innerHTML = css;

        document.querySelectorAll('[data-a11y-color]').forEach(el => {
            el.classList.toggle('active', STATE.colors[el.dataset.a11yColorType] === el.dataset.a11yColor);
        });

        html.style.fontSize = (STATE.scale * 100) + '%';
        DOM.scaleVal.innerText = Math.round(STATE.scale * 100) + '%';
        DOM.guide.classList.toggle('show', !!STATE.profiles['keyboard-nav']);
        DOM.ruler.style.display = STATE.profiles['adhd'] ? 'block' : 'none';

        if (STATE.pos.x !== null && window.innerWidth > 480) {
            DOM.popup.style.left = STATE.pos.x + 'px';
            DOM.popup.style.top = STATE.pos.y + 'px';
            DOM.popup.style.right = 'auto';
        }

        if (STATE.triggerPos && STATE.triggerPos.x !== null && DOM.triggerWrapper) {
            const elW = DOM.triggerWrapper.offsetWidth || 56;
            const elH = DOM.triggerWrapper.offsetHeight || 56;
            const minX = 8;
            const maxX = Math.max(minX, window.innerWidth - elW - 8);
            const minY = 8;
            const maxY = Math.max(minY, window.innerHeight - elH - 8);

            const clampedX = Math.max(minX, Math.min(maxX, STATE.triggerPos.x));
            const clampedY = Math.max(minY, Math.min(maxY, STATE.triggerPos.y));

            DOM.triggerWrapper.style.left = clampedX + 'px';
            DOM.triggerWrapper.style.top = clampedY + 'px';
            DOM.triggerWrapper.style.right = 'auto';
            DOM.triggerWrapper.style.bottom = 'auto';
        }

        window.dispatchEvent(new CustomEvent('branding-changed'));
        if (STATE.mode === 'dark') {
            document.querySelectorAll('img.brand-logo-auto').forEach(img => {
                if (window.__brandWhite) img.src = window.__brandWhite;
            });
        }
    }

    function resetAll() {
        STATE.profiles = {}; STATE.mode = null; STATE.colors = { text: null, title: null, bg: null };
        STATE.controls = {}; STATE.scale = 1.0;
        STATE.triggerPos = { x: null, y: null };
        if (DOM.triggerWrapper) {
            DOM.triggerWrapper.style.left = '';
            DOM.triggerWrapper.style.top = '';
            DOM.triggerWrapper.style.right = '';
            DOM.triggerWrapper.style.bottom = '';
        }
        
        // Explicitly uncheck all inputs
        document.querySelectorAll('[data-a11y-profile], [data-a11y-control]').forEach(el => el.checked = false);
        
        applyState(); saveState();
    }

    init();
})();
</script>
@endif
