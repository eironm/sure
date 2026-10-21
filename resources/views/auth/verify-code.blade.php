@extends('layouts.auth')

@section('title', 'Verify your email')
@section('panel_eyebrow')
    Official University System
@endsection
@section('panel_title')
    One <span class="gold">last step</span> &mdash; verify your email.
@endsection
@section('panel_sub')
    We sent a 6-digit verification code to your email address. Enter it below to activate your account and log in.
@endsection

@section('header_action')
    <a href="{{ route('login') }}" class="auth-header-action">Sign in</a>
@endsection

@section('auth_title', 'Enter your verification code')
@section('auth_subtitle')
    A 6-digit code was sent to <strong>{{ $email }}</strong>.
    @if (isset($user) && $user && !$user->codeVerified())
        It expires in 10 minutes &mdash; request a new one anytime.
    @endif
@endsection

@section('content')
@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
            <span class="small fw-semibold">{{ session('info') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
@endif

@if (session('verify_error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <span class="small fw-semibold">{{ session('verify_error') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
@endif

@if (session('resend_success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <span class="small fw-semibold">{{ session('resend_success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
@endif

<form method="POST" action="{{ route('verify-code.store') }}" id="verify-form">
    @csrf

    <input type="hidden" name="email" value="{{ $email }}">

    <div class="mb-3">
        <label for="code" class="form-label">Verification code</label>
        <input id="code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
               class="form-control form-control-lg text-center @error('code') is-invalid @enderror"
               name="code" value="{{ old('code') }}" required autofocus autocomplete="one-time-code"
               autocapitalize="none" autocorrect="off" spellcheck="false"
               placeholder="0 0 0 0 0 0"
               style="letter-spacing: .8rem; font-variant-numeric: tabular-nums;">
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Enter the 6-digit code we emailed to you.</div>
    </div>

    <button type="submit" class="btn btn-sure w-100">Verify &amp; continue</button>
</form>

<div class="auth-divider mt-4">didn't get the code?</div>

<form method="POST" action="{{ route('verify-code.resend') }}" class="d-inline w-100">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">
    <button type="submit" class="btn btn-sure-ghost w-100">Resend code</button>
</form>

<div class="mt-4 text-center">
    <a href="{{ route('login') }}" class="auth-link"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
</div>

<script>
    (function () {
        var code = document.getElementById('code');
        code.addEventListener('input', function () {
            var v = code.value.replace(/[^0-9]/g, '');
            if (v.length > 6) { v = v.slice(0, 6); }
            code.value = v;
            if (v.length === 6) {
                var form = document.getElementById('verify-form');
                if (form) { form.submit(); }
            }
        });
    })();
</script>
@endsection
