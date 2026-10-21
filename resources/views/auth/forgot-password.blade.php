@extends('layouts.auth')

@section('title', 'Forgot password')
@section('panel_eyebrow')
    Official University System
@endsection
@section('panel_title')
    Happens to <span class="gold">everyone</span>.
@endsection
@section('panel_sub')
    We&rsquo;ll send a secure link to your registered email so you can choose a fresh password in a couple of taps &mdash; no fuss.
@endsection

@section('header_action')
    <a href="{{ route('login') }}" class="auth-header-action">Sign in</a>
@endsection

@section('auth_title', 'Forgot your password?')
@section('auth_subtitle')
    No worries. Enter the email you registered with and we'll send you a secure link to reset your password.
@endsection

@section('content')
@if (session('status'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-envelope-check-fill fs-5 text-success"></i>
            <span class="small fw-semibold">{{ session('status') }}</span>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-4">
        <label for="email" class="form-label">Email address</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
               placeholder="name@example.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-sure">Send reset link</button>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="auth-link"><i class="bi bi-arrow-left me-1"></i>Back to sign in</a>
    </div>
</form>
@endsection
