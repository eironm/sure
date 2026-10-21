@extends('layouts.auth')

@section('title', 'Sign in')
@section('panel_eyebrow')
    Official University System
@endsection
@section('panel_title')
    Welcome <span class="gold">back</span>, we&rsquo;ve missed you.
@endsection
@section('panel_sub')
    Your gateway to every campus space &mdash; reserve what you need, when you need it, in just a few taps.
@endsection

@section('header_action')
    <a href="{{ route('register') }}" class="auth-header-action">Create account</a>
@endsection

@section('auth_title', 'Sign in to your account')
@if (!empty(trim(branding('login_welcome_text', ''))))
    @section('auth_subtitle')
        {!! branding('login_welcome_text') !!}
        <span class="text-muted">· New here? <a href="{{ route('register') }}" class="auth-link">Create an account</a></span>
    @endsection
@else
    @section('auth_subtitle')
        Book campus facilities in minutes. New here? <a href="{{ route('register') }}">Create an account</a>
    @endsection
@endif

@section('content')
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <span class="small fw-semibold">{{ session('status') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <span class="small fw-semibold">{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        {{-- inputmode surfaces the e-mail keyboard; autocapitalize and
             autocorrect are both on by default in iOS Safari and will
             otherwise mangle an address as it is typed. autofocus is
             dropped because it forces the keyboard open on load and
             hides most of the form. --}}
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required autocomplete="username"
               inputmode="email" autocapitalize="none" autocorrect="off" spellcheck="false"
               enterkeyhint="next"
               placeholder="name@example.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="password-field">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password"
                   autocapitalize="none" autocorrect="off" spellcheck="false"
                   enterkeyhint="go"
                   placeholder="Enter your password">
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check mb-0">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" style="border-radius:5px;">
            <label class="form-check-label small" for="remember">Keep me signed in</label>
        </div>
        @if (Route::has('password.request'))
            <a class="auth-link" href="{{ route('password.request') }}">Forgot password?</a>
        @endif
    </div>

    <button type="submit" class="btn btn-sure">Sign in</button>

    <div class="auth-divider">or</div>

    <a href="{{ route('register') }}" class="btn btn-sure-ghost">Create a new account</a>
</form>
@endsection
