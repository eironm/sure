@extends('errors.layout')

@section('title', '500 - Internal Server Error')

@section('content')
    <div class="error-header">
        <div class="sure-logo">
            <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}" alt="{{ branding('system_name', 'Platform') }} Logo">
            <span class="sure-logo-text">{{ branding('system_name', 'Platform') }}</span>
        </div>
    </div>

    <div class="error-icon error-500">
        <i class="bi bi-exclamation-octagon-fill"></i>
    </div>

    <div class="error-code">500</div>

    <h1 class="error-title">Internal Server Error</h1>

    <p class="error-description">
        Something went wrong on our end. Our team has been notified and is working to fix the issue. Please try again later.
    </p>

    <div class="error-actions">
        <a href="{{ url('/') }}" class="btn-primary">
            <i class="bi bi-house-fill"></i>
            Go to Home
        </a>
        <a href="javascript:location.reload()" class="btn-secondary">
            <i class="bi bi-arrow-clockwise"></i>
            Refresh Page
        </a>
    </div>

    <div class="error-details">
        <div class="error-detail-item">
            <i class="bi bi-info-circle"></i>
            <span>Error Code: 500 Internal Server Error</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-clock"></i>
            <span>{{ now()->format('M d, Y H:i:s') }}</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-shield-check"></i>
            <span>Reference ID: {{ \Illuminate\Support\Str::uuid() }}</span>
        </div>
    </div>

    <div class="error-footer">
        <p>If the problem persists, please <a href="mailto:{{ branding('contact_email', 'admin@university.edu') }}">contact our support team</a> with the reference ID above.</p>
    </div>
@endsection
