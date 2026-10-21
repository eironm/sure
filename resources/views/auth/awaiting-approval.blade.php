@extends('layouts.auth')

@section('title', 'Awaiting approval')
@section('panel_eyebrow')
    Official University System
@endsection
@section('panel_title')
    Email <span class="gold">verified</span> &mdash; awaiting staff approval.
@endsection
@section('panel_sub')
    Your email has been verified successfully. Because your account is registered as an internal (university) user, our staff must approve it before you can sign in.
@endsection

@section('header_action')
    <a href="{{ route('login') }}" class="auth-header-action">Sign in</a>
@endsection

@section('auth_title', 'Your account is pending administrator approval')
@section('auth_subtitle')
    A university administrator has been notified and will review your account shortly. You'll receive an email once it has been activated.
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <span class="small fw-semibold">{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
            <span class="small fw-semibold">{{ session('info') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
@endif

<div class="d-flex flex-column align-items-center gap-3 mb-4">
    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
         style="width: 84px; height: 84px;">
        <i class="bi bi-person-check-fill text-primary" style="font-size: 2.4rem;"></i>
    </div>
    <p class="text-center text-muted small mb-0">
        Registered as <strong>{{ $user->email }}</strong><br>
        <span class="text-success"><i class="bi bi-envelope-check-fill me-1"></i>Email verified</span> &middot;
        Account status: <span class="badge bg-warning text-dark">Pending staff approval</span>
    </p>
</div>

<div class="alert alert-light border rounded-3 small mb-4" role="status">
    <i class="bi bi-hourglass-split text-primary me-2"></i>
    What happens next: an administrator will review your university details. Once approved, you will be able to sign in immediately, and we will notify you by email. You can also try signing in anytime &mdash; if your account has been approved in the meantime, you'll get straight in.
</div>

@if (session('verify_error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <span class="small fw-semibold">{{ session('verify_error') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
@endif

<a href="{{ route('login') }}" class="btn btn-sure w-100">Back to sign in</a>

<div class="mt-3 text-center">
    <a href="{{ route('verify-code') }}" class="auth-link">Re-enter your verification code</a>
</div>
@endsection
