@extends('layouts.superadmin')

@section('title', 'System Settings — Announcements')

@section('content')

<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'system'])
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Announcements</h1>
        <p class="ph-sub">A dismissible banner pinned to the top of the public website.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Dashboard</a>
    </div>
</div>

@php
    $annEnabled = old('settings.announcement_enabled',
        \App\Models\SystemSetting::getValue('announcement_enabled', '0')) === '1';
    $annText    = old('settings.announcement_text',
        \App\Models\SystemSetting::getValue('announcement_text', ''));
@endphp

<form action="{{ route('superadmin.settings.system.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="cms-grid">
        <div class="setting-group-card" id="group-announcements">
            <div class="setting-group-header">
                <i class="bi bi-megaphone-fill"></i>
                Announcement Banner
                <span class="sg-sub">A dismissible banner pinned to the top of the public website.</span>
            </div>
            <div class="setting-group-body">
                <div class="setting-field is-boolean">
                    <label class="setting-label mb-0" for="setting-announcement_enabled">
                        Show Announcement Banner
                        <span class="setting-key">announcement_enabled</span>
                    </label>
                    <div class="form-check form-switch premium-switch mb-0">
                        <input type="hidden" name="settings[announcement_enabled]" value="0">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            role="switch"
                            id="setting-announcement_enabled"
                            name="settings[announcement_enabled]"
                            value="1"
                            {{ $annEnabled ? 'checked' : '' }}
                            onchange="window.announcementToggle(this)"
                        >
                    </div>
                </div>

                <div id="announcement-text-wrap" style="{{ $annEnabled ? '' : 'display:none;' }}">
                    <div class="setting-field mt-3">
                        <label class="setting-label" for="setting-announcement_text">
                            Announcement Message
                            <span class="setting-key">announcement_text</span>
                        </label>
                        <textarea
                            class="setting-input"
                            id="setting-announcement_text"
                            name="settings[announcement_text]"
                            rows="3"
                            placeholder="e.g. Campus facilities close at 6PM on Dec 24–26 for the holiday break."
                        >{{ $annText }}</textarea>
                        <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Basic HTML is supported. Leave empty and the banner stays hidden even when enabled. Dismissals are remembered per-browser.</p>
                        <div class="mt-3">
                            <label class="small fw-semibold text-muted mb-2 d-block">Preview</label>
                            <div id="announcement-preview" class="announcement-preview-box">No announcement configured.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials._cms_save_bar', ['label' => 'Save Announcement'])
</form>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ── Announcement toggle: show/hide + live preview ────────── */
    const annTextEl = document.getElementById('setting-announcement_text');

    window.announcementToggle = function (checkbox) {
        const wrap = document.getElementById('announcement-text-wrap');
        if (wrap) wrap.style.display = checkbox.checked ? '' : 'none';
        updateAnnouncementPreview();
    };

    if (annTextEl) annTextEl.addEventListener('input', updateAnnouncementPreview);

    function updateAnnouncementPreview() {
        const box = document.getElementById('announcement-preview');
        const enabled = document.getElementById('setting-announcement_enabled')?.checked;
        if (!box || !enabled) return;
        const text = (annTextEl ? annTextEl.value : '').trim();
        box.innerHTML = text
            ? '<i class="bi bi-megaphone-fill flex-shrink-0"></i><span style="word-break:break-word;">' + text + '</span>'
            : 'Enable and type a message above to see the banner preview.';
        box.classList.toggle('is-empty', !text);
    }

    if (document.getElementById('setting-announcement_text')) updateAnnouncementPreview();
});
</script>
@endpush
