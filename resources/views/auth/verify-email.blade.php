@extends('layouts.auth')

@section('title', 'Verify email')
@section('panel_eyebrow')
    Official University System
@endsection
@section('panel_title')
    One last <span class="gold">step</span>.
@endsection
@section('panel_sub')
    Your account is almost ready &mdash; just confirm your email address and you&rsquo;ll be booking campus spaces in no time.
@endsection

@section('auth_title', 'Check your inbox')
@section('auth_subtitle')
    Thanks for signing up! We've sent a verification link to your email address. Click the link to activate your account and start reserving facilities.
@endsection

@section('content')
@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-envelope-check-fill fs-5 text-success"></i>
            <span class="small fw-semibold">A new verification link has been sent to the email address you provided during registration.</span>
        </div>
    </div>
@endif

<div class="d-flex flex-column gap-3">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-sure">Resend verification email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="text-center">
        @csrf
        <button type="submit" class="btn btn-link auth-link p-0">Sign out</button>
    </form>
</div>
@endsection
