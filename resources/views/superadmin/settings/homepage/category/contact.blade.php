@extends('layouts.superadmin')

@section('title', 'Homepage Editor — Contact')

@section('content')

<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'homepage'])
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Contact</h1>
        <p class="ph-sub">The message block visitors see before the contact form.</p>
    </div>
    <div class="page-actions">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-ghost"><i class="bi bi-box-arrow-up-right"></i>View live homepage</a>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.settings.homepage.update') }}" id="homepageForm">
    @csrf
    @method('PUT')

    <input type="hidden" name="editing_section" value="contact">

    <div class="cms-grid cms-grid-single">
        <div class="setting-group-card">
            <div class="setting-group-header">
                <i class="bi bi-envelope-fill"></i>
                Contact Section
                <span class="sg-sub">The message block visitors see before the contact form.</span>
            </div>
            <div class="setting-group-body">
                <div class="setting-field">
                    <label class="setting-label" for="setting-contact_title">
                        Section title
                        <span class="setting-key">contact.title</span>
                    </label>
                    <input
                        type="text"
                        class="setting-input"
                        id="setting-contact_title"
                        name="contact_title"
                        value="{{ $data['contact']['title'] ?? 'Contact the facilities team' }}"
                    >
                </div>

                <div class="setting-field">
                    <label class="setting-label" for="setting-contact_note">
                        Intro note
                        <span class="setting-key">contact.note</span>
                    </label>
                    <textarea
                        class="setting-input"
                        id="setting-contact_note"
                        name="contact_note"
                        rows="2"
                    >{{ $data['contact']['note'] ?? 'Send us a message and we will get back to you as soon as possible. Fields marked with * are required.' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    @include('partials._cms_save_bar', ['label' => 'Save Contact Section'])
</form>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

@endsection
