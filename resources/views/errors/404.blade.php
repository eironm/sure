@extends('errors.layout')

@section('title', '404 - Page Not Found')

@section('content')
    <div class="error-header">
        <div class="sure-logo">
            <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}" alt="{{ branding('system_name', 'Platform') }} Logo">
            <span class="sure-logo-text">{{ branding('system_name', 'Platform') }}</span>
        </div>
    </div>

    <div class="error-icon error-404">
        <i class="bi bi-exclamation-triangle-fill"></i>
    </div>

    <div class="error-code">404</div>

    <h1 class="error-title">Page Not Found</h1>

    <p class="error-description">
        The page you're looking for doesn't exist or has been moved. Let's get you back on track.
    </p>

    <div class="error-actions">
        <a href="{{ url('/') }}" class="btn-primary">
            <i class="bi bi-house-fill"></i>
            Go to Home
        </a>
        <a href="javascript:history.back()" class="btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Go Back
        </a>
    </div>

    <div class="error-details">
        <div class="error-detail-item">
            <i class="bi bi-info-circle"></i>
            <span>Error Code: 404 Not Found</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-clock"></i>
            <span>{{ now()->format('M d, Y H:i:s') }}</span>
        </div>
    </div>

    <div class="error-footer">
        <p>Need help? <a href="mailto:{{ branding('contact_email', 'admin@university.edu') }}">Contact Support</a> or visit the <a href="{{ url('/') }}">{{ branding('system_name', 'Platform') }} Portal</a></p>
    </div>
@endsection
