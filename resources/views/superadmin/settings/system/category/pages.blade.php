@extends('layouts.superadmin')

@section('title', 'System Settings — Login & Error Pages')

@section('content')

<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'system'])
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Login, Error &amp; Reserve Pages</h1>
        <p class="ph-sub">Friendly copy for sign-in, error pages (404, 403, 500&hellip;), and the guest reservation page.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Dashboard</a>
    </div>
</div>

@php
    $loginWelcomeText = old('settings.login_welcome_text',
        \App\Models\SystemSetting::getValue('login_welcome_text', ''));
    $errorMessage = old('settings.error_page_message',
        \App\Models\SystemSetting::getValue('error_page_message', ''));
    $reserveHeroTitle = old('settings.reserve_hero_title',
        \App\Models\SystemSetting::getValue('reserve_hero_title', 'Reserve a Venue'));
    $reserveHeroSubheading = old('settings.reserve_hero_subheading',
        \App\Models\SystemSetting::getValue('reserve_hero_subheading', 'Find the perfect campus space for your event — availability checked in real time.'));
@endphp

<form action="{{ route('superadmin.settings.system.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="cms-grid">
        <div class="setting-group-card" id="group-pages">
            <div class="setting-group-header">
                <i class="bi bi-door-open"></i>
                Login &amp; Error Pages
                <span class="sg-sub">Friendly copy for the sign-in page and every error page (404, 403, 500&hellip;).</span>
            </div>
            <div class="setting-group-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-login_welcome_text">
                                Login Page Welcome Message
                                <span class="setting-key">login_welcome_text</span>
                            </label>
                            <textarea
                                class="setting-input"
                                id="setting-login_welcome_text"
                                name="settings[login_welcome_text]"
                                rows="3"
                                placeholder="A warm one-line welcome shown under the sign-in heading..."
                            >{{ $loginWelcomeText }}</textarea>
                            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>HTML supported. When set, it replaces the default subtitle on the login page — the "Create an account" hint is always appended.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-error_page_message">
                                Custom Error Page Message
                                <span class="setting-key">error_page_message</span>
                            </label>
                            <textarea
                                class="setting-input"
                                id="setting-error_page_message"
                                name="settings[error_page_message]"
                                rows="3"
                                placeholder="e.g. Lost? Contact the admin office at admin@university.edu and we'll get you on track."
                            >{{ $errorMessage }}</textarea>
                            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Shown beneath every error page so users always know where to get help.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="setting-group-card mt-4" id="group-reserve-page">
            <div class="setting-group-header">
                <i class="bi bi-calendar-plus"></i>
                Guest Reservation Page Copy
                <span class="sg-sub">Hero headline and subtitle displayed at the top of the guest reservation page.</span>
            </div>
            <div class="setting-group-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-reserve_hero_title">
                                Reservation Page Headline
                                <span class="setting-key">reserve_hero_title</span>
                            </label>
                            <input
                                type="text"
                                class="setting-input"
                                id="setting-reserve_hero_title"
                                name="settings[reserve_hero_title]"
                                value="{{ $reserveHeroTitle }}"
                                placeholder="e.g. Reserve a Venue"
                            >
                            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Main title displayed at the top of the reserve form.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="setting-field">
                            <label class="setting-label" for="setting-reserve_hero_subheading">
                                Reservation Page Subtitle
                                <span class="setting-key">reserve_hero_subheading</span>
                            </label>
                            <textarea
                                class="setting-input"
                                id="setting-reserve_hero_subheading"
                                name="settings[reserve_hero_subheading]"
                                rows="3"
                                placeholder="e.g. Find the perfect campus space for your event — availability checked in real time."
                            >{{ $reserveHeroSubheading }}</textarea>
                            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Guidance text shown beneath the reservation page title.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials._cms_save_bar', ['label' => 'Save Page Copy'])
</form>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

@endsection
