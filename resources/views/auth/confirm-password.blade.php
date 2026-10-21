@extends('layouts.auth')

@section('title', 'Confirm password')
@section('panel_eyebrow')
    Official University System
@endsection
@section('panel_title')
    Just one quick <span class="gold">check</span>.
@endsection
@section('panel_sub')
    Please confirm your password before continuing &mdash; it keeps your account safe, and only takes a second.
@endsection

@section('auth_title', 'Confirm your password')
@section('auth_subtitle')
    This is a secure area of the application. Please confirm your password before continuing.
@endsection

@section('content')
<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <div class="password-field">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password" placeholder="Enter your password">
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-sure">Confirm</button>
</form>
@endsection
