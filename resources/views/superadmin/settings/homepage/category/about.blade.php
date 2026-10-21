@extends('layouts.superadmin')

@section('title', 'Homepage Editor — About Section')

@section('content')

<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'homepage'])
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">About Section</h1>
        <p class="ph-sub">The story behind the platform.</p>
    </div>
    <div class="page-actions">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-ghost"><i class="bi bi-box-arrow-up-right"></i>View live homepage</a>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.settings.homepage.update') }}" id="homepageForm">
    @csrf
    @method('PUT')

    <input type="hidden" name="editing_section" value="about">

    <div class="cms-grid cms-grid-single">
        <div class="setting-group-card">
            <div class="setting-group-header">
                <i class="bi bi-info-circle-fill"></i>
                About Section
                <span class="sg-sub">The story behind the platform.</span>
            </div>
            <div class="setting-group-body">
                <div class="setting-field">
                    <label class="setting-label" for="setting-about_title">
                        Section title
                        <span class="setting-key">about.title</span>
                    </label>
                    <input
                        type="text"
                        class="setting-input"
                        id="setting-about_title"
                        name="about_title"
                        value="{{ $data['about']['title'] ?? 'Built for every campus community' }}"
                    >
                </div>

                <div class="setting-field">
                    <label class="setting-label" for="setting-about_text">
                        Intro paragraph
                        <span class="setting-key">about.text</span>
                    </label>
                    <textarea
                        class="setting-input"
                        id="setting-about_text"
                        name="about_text"
                        rows="3"
                    >{{ $data['about']['text'] ?? 'is a centralized reservation and approval system designed to Simplify University Reservations. Faster scheduling, transparent tracking, and a friendlier experience — built for students, faculty, and administrators alike.' }}</textarea>
                    <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>The system name and tagline are inserted automatically before this text.</p>
                </div>

                <div class="setting-field">
                    <label class="setting-label" for="setting-about_sub_text">
                        Secondary paragraph
                        <span class="setting-key">about.subtitle</span>
                    </label>
                    <textarea
                        class="setting-input"
                        id="setting-about_sub_text"
                        name="about_sub_text"
                        rows="2"
                    >{{ $data['about']['sub_text'] ?? 'Connecting students, faculty, and administration through a unified platform that makes booking university spaces quick and hassle-free.' }}</textarea>
                </div>

                <div class="setting-field">
                    <label class="setting-label" for="setting-about_quote">
                        Quote line
                        <span class="setting-key">about.quote</span>
                    </label>
                    <textarea
                        class="setting-input"
                        id="setting-about_quote"
                        name="about_quote"
                        rows="2"
                    >{{ $data['about']['quote'] ?? 'Your campus, your space — reserved with confidence, approved with clarity.' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    @include('partials._cms_save_bar', ['label' => 'Save About Section'])
</form>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

@endsection
