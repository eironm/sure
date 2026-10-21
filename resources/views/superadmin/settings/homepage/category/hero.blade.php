@extends('layouts.superadmin')

@push('styles')
<style>
    .hero-media-preview { display:flex; flex-direction:column; gap:.45rem; }
    .hero-media-preview video, .hero-media-preview img { width:100%; max-height:220px; object-fit:cover; border-radius:.8rem; background:#0f172a; border:1px solid var(--line,#e7eaf0); }

    /* Selected File Preview & Details Card */
    .file-selected-card {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        padding: 0.85rem;
        margin-top: 0.65rem;
        transition: all 0.2s ease;
    }
    .file-selected-card:hover {
        border-color: #94a3b8;
    }
    .file-selected-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    .file-selected-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 0.88rem;
        color: #0f172a;
        word-break: break-all;
    }
    .file-selected-preview-wrap {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        background: #0f172a;
        margin-bottom: 0.5rem;
        border: 1px solid #cbd5e1;
    }
    .file-selected-preview-wrap video,
    .file-selected-preview-wrap img {
        width: 100%;
        max-height: 220px;
        object-fit: contain;
        display: block;
    }

    /* Upload Progress HUD Overlay Modal */
    .upload-hud-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 10500;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }
    .upload-hud-backdrop.active {
        opacity: 1;
        visibility: visible;
    }
    .upload-hud-card {
        background: #ffffff;
        border-radius: 1.25rem;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.2);
        width: 100%;
        max-width: 520px;
        overflow: hidden;
        transform: scale(0.94);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .upload-hud-backdrop.active .upload-hud-card {
        transform: scale(1);
    }
    .upload-hud-header {
        padding: 1.35rem 1.5rem 1rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .upload-hud-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    .upload-hud-icon-wrap.success {
        background: #f0fdf4;
        color: #16a34a;
    }
    .upload-hud-icon-wrap.error {
        background: #fef2f2;
        color: #dc2626;
    }
    .upload-hud-body {
        padding: 1.25rem 1.5rem;
    }
    .hud-progress-wrap {
        margin: 1rem 0;
    }
    .hud-progress-track {
        height: 14px;
        background: #f1f5f9;
        border-radius: 9999px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    .hud-progress-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #3b82f6 0%, #6366f1 50%, #8b5cf6 100%);
        border-radius: 9999px;
        transition: width 0.15s ease-out;
        box-shadow: 0 0 12px rgba(99, 102, 241, 0.5);
    }
    .hud-progress-bar.pulse-indeterminate {
        width: 100% !important;
        background: linear-gradient(90deg, #3b82f6 20%, #8b5cf6 50%, #3b82f6 80%);
        background-size: 200% 100%;
        animation: hudBarShimmer 1.5s infinite linear;
    }
    @keyframes hudBarShimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    .hud-metrics-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.65rem;
        margin-top: 1rem;
    }
    .hud-metric-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        padding: 0.6rem 0.75rem;
    }
    .hud-metric-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 0.15rem;
    }
    .hud-metric-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        font-variant-numeric: tabular-nums;
    }
    .hud-stages-list {
        margin-top: 1.25rem;
        padding: 0.75rem 0.85rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
    }
    .hud-stage-item {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 0.82rem;
        color: #64748b;
        padding: 0.3rem 0;
    }
    .hud-stage-item.active {
        color: #2563eb;
        font-weight: 600;
    }
    .hud-stage-item.done {
        color: #16a34a;
        font-weight: 500;
    }
    .hud-stage-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        background: #e2e8f0;
        color: #64748b;
        flex-shrink: 0;
    }
    .hud-stage-item.active .hud-stage-dot {
        background: #2563eb;
        color: #fff;
    }
    .hud-stage-item.done .hud-stage-dot {
        background: #16a34a;
        color: #fff;
    }
    .upload-hud-footer {
        padding: 0.85rem 1.5rem 1.25rem;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.75rem;
        border-top: 1px solid #f1f5f9;
    }
</style>
@endpush


@section('title', 'Homepage Editor — Hero Section')

@section('content')

<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'homepage'])
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Hero Section</h1>
        <p class="ph-sub">The first thing visitors see on the public homepage.</p>
    </div>
    <div class="page-actions">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-ghost"><i class="bi bi-box-arrow-up-right"></i>View live homepage</a>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.settings.homepage.update') }}" id="homepageForm" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="editing_section" value="hero">
    <input type="hidden" name="hero_badges" id="heroBadgesJson">

    <div class="cms-grid">
        <div class="setting-group-card">
            <div class="setting-group-header">
                <i class="bi bi-stars"></i>
                Hero Section
                <span class="sg-sub">The first thing visitors see.</span>
            </div>
            <div class="setting-group-body">
                <div class="setting-field">
                    <label class="setting-label" for="setting-hero_eyebrow">
                        Eyebrow label
                        <span class="setting-key">hero.eyebrow</span>
                    </label>
                    <input
                        type="text"
                        class="setting-input"
                        id="setting-hero_eyebrow"
                        name="hero_eyebrow"
                        value="{{ $data['hero']['eyebrow'] ?? 'SIMPLIFY UNIVERSITY RESERVATIONS' }}"
                    >
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-hero_heading">
                                Main heading
                                <span class="setting-key">hero.heading</span>
                            </label>
                            <input
                                type="text"
                                class="setting-input"
                                id="setting-hero_heading"
                                name="hero_heading"
                                value="{{ $data['hero']['heading'] ?? 'Reserve University Facilities' }}"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-hero_cta_text">
                                Primary CTA text
                                <span class="setting-key">hero.cta_text</span>
                            </label>
                            <input
                                type="text"
                                class="setting-input"
                                id="setting-hero_cta_text"
                                name="hero_cta_text"
                                value="{{ $data['hero']['cta_text'] ?? 'Reserve a facility' }}"
                                placeholder="Reserve a facility"
                            >
                        </div>
                    </div>
                </div>

                <div class="setting-field mt-3">
                    <label class="setting-label" for="setting-hero_sub">
                        Subheading
                        <span class="setting-key">hero.sub</span>
                    </label>
                    <textarea
                        class="setting-input"
                        id="setting-hero_sub"
                        name="hero_sub"
                        rows="2"
                    >{{ $data['hero']['sub'] ?? 'Book campus spaces effortlessly with our streamlined reservation platform.' }}</textarea>
                </div>

                <hr class="cms-divider">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="setting-label mb-0">
                        Trust badges
                        <span class="setting-key">hero.badges</span>
                    </label>
                    <button type="button" class="btn btn-ghost btn-sm" id="addBadgeBtn" style="border-style:dashed;">
                        <i class="bi bi-plus-lg"></i> Add badge
                    </button>
                </div>
                <div id="badgesList" class="d-flex flex-column gap-2"></div>
            </div>
        </div>
    </div>


        <div class="setting-group-card hero-media-card">
            <div class="setting-group-header">
                <i class="bi bi-camera-reels"></i>
                Homepage Hero Media
                <span class="sg-sub">Use your own image, video, or GIF behind the hero copy.</span>
            </div>
            <div class="setting-group-body">
                <div class="d-flex align-items-center justify-content-between gap-3 mb-3 p-3 rounded-3" style="background:rgba(var(--accent-rgb),.06); border:1px solid rgba(var(--accent-rgb),.14);">
                    <div>
                        <div class="fw-semibold text-dark">Show hero media</div>
                        <div class="small text-muted">Disable this to use the poster/fallback image only.</div>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input type="hidden" name="hero_media_enabled" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="hero_media_enabled" name="hero_media_enabled" value="1" @checked(data_get($data, 'hero.media.enabled', true))>
                        <label class="visually-hidden" for="hero_media_enabled">Show hero media</label>
                    </div>
                </div>

                <div class="setting-field">
                    <label class="setting-label" for="hero_media_type">Media type <span class="setting-key">hero.media.type</span></label>
                    <select class="setting-input" id="hero_media_type" name="hero_media_type">
                        <option value="video" @selected(data_get($data, 'hero.media.type', 'video') === 'video')>Video (MP4, WebM, or OGG)</option>
                        <option value="image" @selected(data_get($data, 'hero.media.type', 'video') === 'image')>Image (JPG, PNG, or WebP)</option>
                        <option value="gif" @selected(data_get($data, 'hero.media.type', 'video') === 'gif')>Animated GIF</option>
                    </select>
                    <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>The type is detected automatically when you upload a new file.</p>
                </div>                <div class="setting-field mt-3">
                    <label class="setting-label" for="hero_media">Hero media upload</label>
                    <input class="form-control" type="file" id="hero_media" name="hero_media" accept="video/mp4,video/webm,video/ogg,image/gif,image/jpeg,image/png,image/webp">
                    <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Maximum 50 MB. Video (MP4) is preferred over GIF for best streaming performance.</p>

                    <!-- Selected Media Details & Live Preview Card -->
                    <div id="hero_media_selected_card" class="file-selected-card d-none">
                        <div class="file-selected-header">
                            <div class="file-selected-title">
                                <i class="bi bi-file-earmark-play-fill text-primary" id="hero_media_file_icon"></i>
                                <span id="hero_media_file_name">selected-file.mp4</span>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" id="hero_media_remove_btn" title="Remove selected file">
                                <i class="bi bi-x-lg me-1"></i>Remove
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" id="hero_media_file_size">0 MB</span>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" id="hero_media_file_type">Video</span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle d-none" id="hero_media_file_dim">1920×1080</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check2-circle me-1"></i>Ready to upload</span>
                        </div>
                        <div class="file-selected-preview-wrap" id="hero_media_preview_wrap">
                            <!-- Live video or image preview dynamically mounted here -->
                        </div>
                        <div class="small text-muted d-flex align-items-center gap-1">
                            <i class="bi bi-info-circle text-primary"></i>
                            <span id="hero_media_est_hint">Selected file will replace current media when you press Save.</span>
                        </div>
                    </div>
                </div>

                @php($heroMediaType = data_get($data, 'hero.media.type', 'video'))
                @php($heroMediaUrl = data_get($data, 'hero.media.url'))
                @if($heroMediaUrl)
                    <div class="hero-media-preview mt-3" id="hero_media_current_preview">
                        @if($heroMediaType === 'video')
                            <video src="{{ asset($heroMediaUrl) }}" poster="{{ asset(data_get($data, 'hero.media.poster', 'assets/figma-not.jpg')) }}" controls muted preload="metadata"></video>
                        @else
                            <img src="{{ asset($heroMediaUrl) }}" alt="Current hero media preview">
                        @endif
                        <span class="small text-muted"><i class="bi bi-broadcast me-1"></i>Current active media on live homepage</span>
                    </div>
                @endif

                <div class="setting-field mt-3">
                    <label class="setting-label" for="hero_media_poster">Poster / fallback image</label>
                    <input class="form-control" type="file" id="hero_media_poster" name="hero_media_poster" accept="image/gif,image/jpeg,image/png,image/webp">
                    <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Maximum 20 MB. Shown while video loads, when media is disabled, and for reduced-motion visitors.</p>

                    <!-- Selected Poster Details & Thumbnail Card -->
                    <div id="hero_poster_selected_card" class="file-selected-card d-none">
                        <div class="file-selected-header">
                            <div class="file-selected-title">
                                <i class="bi bi-file-earmark-image-fill text-success"></i>
                                <span id="hero_poster_file_name">poster.jpg</span>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm py-0 px-2" id="hero_poster_remove_btn" title="Remove selected file">
                                <i class="bi bi-x-lg me-1"></i>Remove
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" id="hero_poster_file_size">0 MB</span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle d-none" id="hero_poster_file_dim">1920×1080</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check2-circle me-1"></i>Ready to upload</span>
                        </div>
                        <div class="file-selected-preview-wrap" style="max-height: 160px;">
                            <img id="hero_poster_preview_img" src="" alt="Selected fallback poster preview" style="max-height: 160px; object-fit: contain;">
                        </div>
                    </div>

                    @php($heroMediaPoster = data_get($data, 'hero.media.poster'))
                    @if($heroMediaPoster)
                        <div id="hero_poster_current_wrap" class="d-flex align-items-center gap-3 mt-2 p-2 rounded-3" style="background: rgba(15, 23, 42, 0.03); border: 1px solid var(--line, #e7eaf0);">
                            <img src="{{ asset($heroMediaPoster) }}" alt="Current poster / fallback image" style="width: 80px; height: 50px; object-fit: cover; border-radius: .4rem; border: 1px solid var(--line, #cbd5e1);">
                            <div class="small">
                                <span class="fw-semibold text-dark d-block">Current fallback image</span>
                                <span class="text-muted font-monospace" style="font-size: .8rem;">{{ basename($heroMediaPoster) }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="setting-field mt-3">
                    <label class="setting-label" for="hero_media_alt">Accessible description <span class="setting-key">hero.media.alt</span></label>
                    <input class="setting-input" type="text" id="hero_media_alt" name="hero_media_alt" value="{{ data_get($data, 'hero.media.alt', '') }}" maxlength="255" placeholder="Describe the visual for visitors using assistive technology">
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="1" id="hero_media_reset" name="hero_media_reset">
                    <label class="form-check-label small text-muted" for="hero_media_reset">Reset hero media to the platform default</label>
                </div>
            </div>
        </div>

    <!-- Upload Progress HUD Modal -->
    <div id="uploadHudModal" class="upload-hud-backdrop" role="dialog" aria-modal="true" aria-labelledby="uploadHudTitle">
        <div class="upload-hud-card">
            <div class="upload-hud-header">
                <div class="upload-hud-icon-wrap" id="uploadHudIconWrap">
                    <i class="bi bi-cloud-arrow-up-fill" id="uploadHudIcon"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-1 fw-bold text-dark" id="uploadHudTitle">Uploading Hero Media</h5>
                    <p class="small text-muted mb-0" id="uploadHudSubtitle">Transferring media payload to server. Please keep this window open.</p>
                </div>
            </div>
            <div class="upload-hud-body">
                <!-- File summary line -->
                <div class="p-2 mb-2 rounded-2 bg-light border d-flex align-items-center justify-content-between small">
                    <div class="text-truncate me-2" style="max-width: 320px;">
                        <i class="bi bi-paperclip me-1 text-primary"></i>
                        <span class="fw-semibold text-dark" id="uploadHudFileName">video.mp4</span>
                    </div>
                    <span class="badge bg-secondary font-monospace" id="uploadHudTotalSize">0 MB</span>
                </div>

                <!-- Progress bar and percentage -->
                <div class="hud-progress-wrap">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-dark" id="uploadHudStatusText">Uploading to server...</span>
                        <span class="small fw-bold text-primary font-monospace" id="uploadHudPercentText">0%</span>
                    </div>
                    <div class="hud-progress-track">
                        <div class="hud-progress-bar" id="uploadHudProgressBar" style="width: 0%;"></div>
                    </div>
                </div>

                <!-- Metrics Grid -->
                <div class="hud-metrics-grid" id="uploadHudMetricsGrid">
                    <div class="hud-metric-box">
                        <div class="hud-metric-label">Transferred</div>
                        <div class="hud-metric-value" id="uploadHudTransferred">0 MB / 0 MB</div>
                    </div>
                    <div class="hud-metric-box">
                        <div class="hud-metric-label">Upload Speed</div>
                        <div class="hud-metric-value text-primary" id="uploadHudSpeed">0 KB/s</div>
                    </div>
                    <div class="hud-metric-box">
                        <div class="hud-metric-label">Time Remaining</div>
                        <div class="hud-metric-value" id="uploadHudEta">Calculating...</div>
                    </div>
                    <div class="hud-metric-box">
                        <div class="hud-metric-label">Elapsed Time</div>
                        <div class="hud-metric-value" id="uploadHudElapsed">00:00</div>
                    </div>
                </div>

                <!-- Stages List -->
                <div class="hud-stages-list">
                    <div class="hud-stage-item active" id="hudStage1">
                        <div class="hud-stage-dot"><i class="bi bi-cloud-arrow-up"></i></div>
                        <div>Uploading file stream to server</div>
                    </div>
                    <div class="hud-stage-item" id="hudStage2">
                        <div class="hud-stage-dot"><i class="bi bi-cpu"></i></div>
                        <div>Optimizing MP4 FastStart & processing assets</div>
                    </div>
                    <div class="hud-stage-item" id="hudStage3">
                        <div class="hud-stage-dot"><i class="bi bi-check-lg"></i></div>
                        <div>Saving settings & refreshing view</div>
                    </div>
                </div>

                <!-- Error Container -->
                <div id="uploadHudErrorBox" class="alert alert-danger d-none mt-3 mb-0 small" role="alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger flex-shrink-0"></i>
                        <div class="flex-grow-1" id="uploadHudErrorMsg">An error occurred during upload.</div>
                    </div>
                </div>
            </div>
            <div class="upload-hud-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="uploadHudCancelBtn">
                    <i class="bi bi-x-lg me-1"></i>Cancel Upload
                </button>
                <button type="button" class="btn btn-primary btn-sm px-3 d-none" id="uploadHudDismissBtn">
                    Close
                </button>
            </div>
        </div>
    </div>

    @include('partials._cms_save_bar', ['label' => 'Save Hero Section'])
</form>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    function esc(v) {
        return String(v == null ? '' : v).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    var INITIAL = @json($data);
    var badges = (INITIAL && INITIAL.hero && INITIAL.hero.badges) || [
        { label: '100% online process' },
        { label: 'Real-time status updates' },
        { label: 'No double bookings' }
    ];
    var wrap = document.getElementById('badgesList');
    var jsonField = document.getElementById('heroBadgesJson');

    function renderBadges() {
        wrap.innerHTML = '';
        badges.forEach(function (b, i) {
            var div = document.createElement('div');
            div.className = 'repeater-item';
            div.innerHTML =
                '<span class="badge badge-neutral">Badge ' + (i + 1) + '</span>' +
                '<input type="text" class="form-control" value="' + esc(b.label) + '" data-badge="' + i + '" placeholder="Badge text">' +
                '<button type="button" class="btn btn-outline-danger btn-sm" data-badge-del="' + i + '" title="Remove"><i class="bi bi-trash"></i></button>';
            wrap.appendChild(div);
        });
        syncBadges();
    }

    function syncBadges() {
        wrap.querySelectorAll('input[type=text]').forEach(function (el) {
            var i = parseInt(el.getAttribute('data-badge'), 10);
            badges[i] = { label: el.value };
        });
        jsonField.value = JSON.stringify(badges);
    }

    wrap.addEventListener('input', syncBadges);
    wrap.addEventListener('click', function (e) {
        var del = e.target.closest('[data-badge-del]');
        if (!del) return;
        badges.splice(parseInt(del.getAttribute('data-badge-del'), 10), 1);
        renderBadges();
    });
    document.getElementById('addBadgeBtn').addEventListener('click', function () {
        badges.push({ label: '' });
        renderBadges();
    });

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function formatDuration(sec) {
        sec = Math.round(sec || 0);
        var m = Math.floor(sec / 60);
        var s = sec % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    function formatClock(seconds) {
        var m = Math.floor(seconds / 60);
        var s = seconds % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    // --- Interactive Hero Media Selection ---
    var heroMediaInput = document.getElementById('hero_media');
    var heroMediaCard  = document.getElementById('hero_media_selected_card');
    var heroMediaName  = document.getElementById('hero_media_file_name');
    var heroMediaSize  = document.getElementById('hero_media_file_size');
    var heroMediaType  = document.getElementById('hero_media_file_type');
    var heroMediaDim   = document.getElementById('hero_media_file_dim');
    var heroMediaIcon  = document.getElementById('hero_media_file_icon');
    var heroMediaWrap  = document.getElementById('hero_media_preview_wrap');
    var heroMediaRem   = document.getElementById('hero_media_remove_btn');
    var heroTypeSelect = document.getElementById('hero_media_type');
    var heroMediaUrlObj = null;

    function cleanupHeroMediaUrl() {
        if (heroMediaUrlObj) {
            URL.revokeObjectURL(heroMediaUrlObj);
            heroMediaUrlObj = null;
        }
    }

    if (heroMediaInput) {
        heroMediaInput.addEventListener('change', function () {
            cleanupHeroMediaUrl();
            if (!this.files || !this.files[0]) {
                heroMediaCard.classList.add('d-none');
                return;
            }

            var file = this.files[0];
            var maxBytes = 50 * 1024 * 1024;

            if (file.size > maxBytes) {
                alert('Hero media exceeds the maximum limit of 50 MB (selected file is ' + formatBytes(file.size) + '). Please compress or choose a smaller file.');
                this.value = '';
                heroMediaCard.classList.add('d-none');
                return;
            }

            heroMediaName.textContent = file.name;
            heroMediaSize.textContent = formatBytes(file.size);
            heroMediaDim.classList.add('d-none');
            heroMediaWrap.innerHTML = '';

            var ext = (file.name.split('.').pop() || '').toLowerCase();
            var isVideo = ['mp4', 'webm', 'ogg'].indexOf(ext) !== -1 || file.type.indexOf('video') === 0;
            var isGif = ext === 'gif' || file.type === 'image/gif';

            heroMediaUrlObj = URL.createObjectURL(file);

            if (isVideo) {
                heroMediaType.textContent = (ext ? ext.toUpperCase() + ' ' : '') + 'Video';
                heroMediaIcon.className = 'bi bi-file-earmark-play-fill text-primary';
                if (heroTypeSelect) heroTypeSelect.value = 'video';

                var vid = document.createElement('video');
                vid.controls = true;
                vid.muted = true;
                vid.playsInline = true;
                vid.src = heroMediaUrlObj;
                vid.style.maxHeight = '220px';
                vid.style.width = '100%';
                vid.style.background = '#0f172a';
                vid.addEventListener('loadedmetadata', function () {
                    heroMediaDim.textContent = this.videoWidth + '×' + this.videoHeight + ' · ' + formatDuration(this.duration);
                    heroMediaDim.classList.remove('d-none');
                });
                heroMediaWrap.appendChild(vid);
            } else if (isGif) {
                heroMediaType.textContent = 'Animated GIF';
                heroMediaIcon.className = 'bi bi-file-earmark-play-fill text-warning';
                if (heroTypeSelect) heroTypeSelect.value = 'gif';

                var imgGif = document.createElement('img');
                imgGif.src = heroMediaUrlObj;
                imgGif.alt = 'Selected GIF preview';
                imgGif.style.maxHeight = '220px';
                imgGif.style.objectFit = 'contain';
                imgGif.style.width = '100%';
                imgGif.addEventListener('load', function () {
                    heroMediaDim.textContent = this.naturalWidth + '×' + this.naturalHeight;
                    heroMediaDim.classList.remove('d-none');
                });
                heroMediaWrap.appendChild(imgGif);
            } else {
                heroMediaType.textContent = (ext ? ext.toUpperCase() + ' ' : '') + 'Image';
                heroMediaIcon.className = 'bi bi-file-earmark-image-fill text-success';
                if (heroTypeSelect) heroTypeSelect.value = 'image';

                var img = document.createElement('img');
                img.src = heroMediaUrlObj;
                img.alt = 'Selected image preview';
                img.style.maxHeight = '220px';
                img.style.objectFit = 'contain';
                img.style.width = '100%';
                img.addEventListener('load', function () {
                    heroMediaDim.textContent = this.naturalWidth + '×' + this.naturalHeight;
                    heroMediaDim.classList.remove('d-none');
                });
                heroMediaWrap.appendChild(img);
            }

            heroMediaCard.classList.remove('d-none');
        });
    }

    if (heroMediaRem) {
        heroMediaRem.addEventListener('click', function () {
            cleanupHeroMediaUrl();
            if (heroMediaInput) heroMediaInput.value = '';
            heroMediaCard.classList.add('d-none');
        });
    }

    // --- Interactive Poster Image Selection ---
    var posterInput = document.getElementById('hero_media_poster');
    var posterCard  = document.getElementById('hero_poster_selected_card');
    var posterName  = document.getElementById('hero_poster_file_name');
    var posterSize  = document.getElementById('hero_poster_file_size');
    var posterDim   = document.getElementById('hero_poster_file_dim');
    var posterImg   = document.getElementById('hero_poster_preview_img');
    var posterRem   = document.getElementById('hero_poster_remove_btn');
    var posterUrlObj = null;

    function cleanupPosterUrl() {
        if (posterUrlObj) {
            URL.revokeObjectURL(posterUrlObj);
            posterUrlObj = null;
        }
    }

    if (posterInput) {
        posterInput.addEventListener('change', function () {
            cleanupPosterUrl();
            if (!this.files || !this.files[0]) {
                posterCard.classList.add('d-none');
                return;
            }

            var file = this.files[0];
            var maxBytes = 20 * 1024 * 1024;

            if (file.size > maxBytes) {
                alert('Poster image exceeds the maximum limit of 20 MB (selected file is ' + formatBytes(file.size) + '). Please choose a smaller image.');
                this.value = '';
                posterCard.classList.add('d-none');
                return;
            }

            posterName.textContent = file.name;
            posterSize.textContent = formatBytes(file.size);
            posterDim.classList.add('d-none');

            posterUrlObj = URL.createObjectURL(file);
            posterImg.src = posterUrlObj;
            posterImg.onload = function () {
                posterDim.textContent = this.naturalWidth + '×' + this.naturalHeight;
                posterDim.classList.remove('d-none');
            };

            posterCard.classList.remove('d-none');
        });
    }

    if (posterRem) {
        posterRem.addEventListener('click', function () {
            cleanupPosterUrl();
            if (posterInput) posterInput.value = '';
            posterCard.classList.add('d-none');
        });
    }

    // --- Form Submission & Live Upload Telemetry HUD ---
    var form = document.getElementById('homepageForm');
    var hudModal = document.getElementById('uploadHudModal');
    var hudIconWrap = document.getElementById('uploadHudIconWrap');
    var hudIcon = document.getElementById('uploadHudIcon');
    var hudTitle = document.getElementById('uploadHudTitle');
    var hudSubtitle = document.getElementById('uploadHudSubtitle');
    var hudFileName = document.getElementById('uploadHudFileName');
    var hudTotalSize = document.getElementById('uploadHudTotalSize');
    var hudProgressBar = document.getElementById('uploadHudProgressBar');
    var hudStatusText = document.getElementById('uploadHudStatusText');
    var hudPercentText = document.getElementById('uploadHudPercentText');
    var hudTransferred = document.getElementById('uploadHudTransferred');
    var hudSpeed = document.getElementById('uploadHudSpeed');
    var hudEta = document.getElementById('uploadHudEta');
    var hudElapsed = document.getElementById('uploadHudElapsed');
    var hudStage1 = document.getElementById('hudStage1');
    var hudStage2 = document.getElementById('hudStage2');
    var hudStage3 = document.getElementById('hudStage3');
    var hudErrorBox = document.getElementById('uploadHudErrorBox');
    var hudErrorMsg = document.getElementById('uploadHudErrorMsg');
    var hudCancelBtn = document.getElementById('uploadHudCancelBtn');
    var hudDismissBtn = document.getElementById('uploadHudDismissBtn');

    var currentXhr = null;
    var timerInterval = null;

    function openHudModal(summaryName, totalBytes) {
        hudModal.classList.add('active');
        hudIconWrap.className = 'upload-hud-icon-wrap';
        hudIcon.className = 'bi bi-cloud-arrow-up-fill';
        hudTitle.textContent = 'Uploading Hero Media';
        hudSubtitle.textContent = 'Transferring media payload to server. Please keep this window open.';

        hudFileName.textContent = summaryName;
        hudTotalSize.textContent = formatBytes(totalBytes);

        hudProgressBar.style.width = '0%';
        hudProgressBar.classList.remove('pulse-indeterminate');
        hudProgressBar.style.background = '';

        hudStatusText.textContent = 'Uploading to server...';
        hudPercentText.textContent = '0%';
        hudTransferred.textContent = '0 MB / ' + formatBytes(totalBytes);
        hudSpeed.textContent = 'Calculating...';
        hudEta.textContent = 'Calculating...';
        hudElapsed.textContent = '00:00';

        hudStage1.className = 'hud-stage-item active';
        hudStage2.className = 'hud-stage-item';
        hudStage3.className = 'hud-stage-item';

        hudErrorBox.classList.add('d-none');
        hudCancelBtn.classList.remove('d-none');
        hudDismissBtn.classList.add('d-none');
    }

    function closeHudModal() {
        hudModal.classList.remove('active');
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
        if (currentXhr) {
            currentXhr.abort();
            currentXhr = null;
        }
    }

    if (hudCancelBtn) {
        hudCancelBtn.addEventListener('click', function () {
            if (confirm('Cancel current media upload? Any unsaved changes will remain on the page.')) {
                closeHudModal();
            }
        });
    }

    if (hudDismissBtn) {
        hudDismissBtn.addEventListener('click', function () {
            closeHudModal();
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            syncBadges();

            var hasMediaFile = heroMediaInput && heroMediaInput.files && heroMediaInput.files.length > 0;
            var hasPosterFile = posterInput && posterInput.files && posterInput.files.length > 0;

            // If no files attached, show standard button spinner and let browser submit
            if (!hasMediaFile && !hasPosterFile) {
                var submitBtn = form.querySelector('button[type=submit]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving Hero Section...';
                }
                return;
            }

            // Media file upload with live progress telemetry
            e.preventDefault();

            var filesNames = [];
            var totalUploadBytes = 0;
            if (hasMediaFile) {
                filesNames.push(heroMediaInput.files[0].name);
                totalUploadBytes += heroMediaInput.files[0].size;
            }
            if (hasPosterFile) {
                filesNames.push(posterInput.files[0].name);
                totalUploadBytes += posterInput.files[0].size;
            }

            openHudModal(filesNames.join(', '), totalUploadBytes);

            var formData = new FormData(form);
            var startTime = Date.now();
            var lastTime = startTime;
            var lastLoaded = 0;

            timerInterval = setInterval(function () {
                var elapsedSec = Math.floor((Date.now() - startTime) / 1000);
                hudElapsed.textContent = formatClock(elapsedSec);
            }, 500);

            var xhr = new XMLHttpRequest();
            currentXhr = xhr;

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = function (ev) {
                if (ev.lengthComputable) {
                    var percent = Math.min(99, Math.round((ev.loaded / ev.total) * 100));
                    hudProgressBar.style.width = percent + '%';
                    hudPercentText.textContent = percent + '%';
                    hudTransferred.textContent = formatBytes(ev.loaded) + ' / ' + formatBytes(ev.total);

                    var now = Date.now();
                    var elapsedSec = (now - startTime) / 1000;
                    if (elapsedSec > 0.3) {
                        var speed = ev.loaded / elapsedSec; // bytes per sec
                        hudSpeed.textContent = formatBytes(speed) + '/s';

                        var remainingBytes = ev.total - ev.loaded;
                        if (speed > 0) {
                            var remSec = Math.max(1, Math.round(remainingBytes / speed));
                            hudEta.textContent = remSec + 's remaining';
                        }
                    }
                }
            };

            xhr.upload.onload = function () {
                // Upload stream complete; now awaiting server processing
                hudStage1.className = 'hud-stage-item done';
                hudStage2.className = 'hud-stage-item active';

                hudStatusText.textContent = 'Processing & optimizing on server...';
                hudSubtitle.textContent = 'Relocating MP4 FastStart atoms and saving system settings. Almost done...';
                hudProgressBar.classList.add('pulse-indeterminate');
                hudProgressBar.style.width = '100%';
                hudPercentText.textContent = '99%';
                hudEta.textContent = 'Processing...';
            };

            xhr.onload = function () {
                clearInterval(timerInterval);
                currentXhr = null;

                if (xhr.status >= 200 && xhr.status < 300) {
                    hudStage2.className = 'hud-stage-item done';
                    hudStage3.className = 'hud-stage-item active done';

                    hudIconWrap.className = 'upload-hud-icon-wrap success';
                    hudIcon.className = 'bi bi-check2-circle';
                    hudTitle.textContent = 'Saved Successfully!';
                    hudSubtitle.textContent = 'Your hero media has been uploaded, optimized, and saved.';

                    hudProgressBar.classList.remove('pulse-indeterminate');
                    hudProgressBar.style.width = '100%';
                    hudProgressBar.style.background = '#16a34a';
                    hudPercentText.textContent = '100%';
                    hudStatusText.textContent = 'Reloading page...';
                    hudEta.textContent = 'Complete';
                    hudCancelBtn.classList.add('d-none');

                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else if (xhr.status === 413) {
                    hudProgressBar.classList.remove('pulse-indeterminate');
                    hudIconWrap.className = 'upload-hud-icon-wrap error';
                    hudIcon.className = 'bi bi-exclamation-octagon-fill';
                    hudTitle.textContent = 'Upload Failed (File Too Large)';
                    hudSubtitle.textContent = 'The uploaded file exceeds the maximum server payload limit.';
                    hudErrorMsg.innerHTML = '<strong>Server Limit Exceeded (413):</strong> The server rejected the file because it exceeds the deployment post/upload size limit. Please compress your video or use a file under 50 MB.';
                    hudErrorBox.classList.remove('d-none');
                    hudCancelBtn.classList.add('d-none');
                    hudDismissBtn.classList.remove('d-none');
                } else if (xhr.status === 422) {
                    hudProgressBar.classList.remove('pulse-indeterminate');
                    hudIconWrap.className = 'upload-hud-icon-wrap error';
                    hudIcon.className = 'bi bi-exclamation-triangle-fill';
                    hudTitle.textContent = 'Validation Error';
                    hudSubtitle.textContent = 'One or more fields did not pass validation.';
                    try {
                        var res = JSON.parse(xhr.responseText);
                        var errs = res.errors ? Object.values(res.errors).flat().join('<br>') : (res.message || 'Validation failed.');
                        hudErrorMsg.innerHTML = '<strong>Validation Error:</strong><br>' + errs;
                    } catch (ex) {
                        hudErrorMsg.textContent = 'Validation error occurred on the server.';
                    }
                    hudErrorBox.classList.remove('d-none');
                    hudCancelBtn.classList.add('d-none');
                    hudDismissBtn.classList.remove('d-none');
                } else {
                    hudProgressBar.classList.remove('pulse-indeterminate');
                    hudIconWrap.className = 'upload-hud-icon-wrap error';
                    hudIcon.className = 'bi bi-x-circle-fill';
                    hudTitle.textContent = 'Upload Failed (' + xhr.status + ')';
                    hudSubtitle.textContent = 'The server encountered an error while processing the upload.';
                    hudErrorMsg.textContent = 'Server response error (' + xhr.status + '). Please try again or check server logs.';
                    hudErrorBox.classList.remove('d-none');
                    hudCancelBtn.classList.add('d-none');
                    hudDismissBtn.classList.remove('d-none');
                }
            };

            xhr.onerror = function () {
                clearInterval(timerInterval);
                currentXhr = null;
                hudProgressBar.classList.remove('pulse-indeterminate');
                hudIconWrap.className = 'upload-hud-icon-wrap error';
                hudIcon.className = 'bi bi-wifi-off';
                hudTitle.textContent = 'Network Connection Error';
                hudSubtitle.textContent = 'Connection to the server was lost during upload.';
                hudErrorMsg.textContent = 'A network error interrupted the file transfer. Please check your internet connection and try again.';
                hudErrorBox.classList.remove('d-none');
                hudCancelBtn.classList.add('d-none');
                hudDismissBtn.classList.remove('d-none');
            };

            xhr.send(formData);
        });
    }

    renderBadges();
});
</script>
@endpush
