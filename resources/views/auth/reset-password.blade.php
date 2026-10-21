@extends('layouts.auth')

@section('title', 'Reset password')
@section('panel_eyebrow')
    Official University System
@endsection
@section('panel_title')
    Almost <span class="gold">there</span>.
@endsection
@section('panel_sub')
    Pick a fresh password you haven&rsquo;t used before &mdash; one that only you will know.
@endsection

@section('auth_title', 'Set a new password')
@section('auth_subtitle')
    Choose a strong password you haven't used before on this account.
@endsection

@section('content')
<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">New password</label>
        <div class="password-field">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="new-password" placeholder="Create a strong password">
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirm new password</label>
        <div class="password-field">
            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                   name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password">
        </div>
        @error('password_confirmation')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-sure">Reset password</button>
</form>
@endsection
