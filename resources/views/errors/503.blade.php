@extends('errors.layout')

@section('title', '503 - Service Unavailable')

@section('content')
    <div class="error-header">
        <div class="sure-logo">
            <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}" alt="{{ branding('system_name', 'Platform') }} Logo">
            <span class="sure-logo-text">{{ branding('system_name', 'Platform') }}</span>
        </div>
    </div>

    <div class="error-icon error-503">
        <i class="bi bi-cloud-slash"></i>
    </div>

    <div class="error-code">503</div>

    <h1 class="error-title">Service Unavailable</h1>

    <p class="error-description">
        The {{ branding('system_name', 'Platform') }} Portal is temporarily under maintenance. We'll be back online shortly. Thank you for your patience.
    </p>

    <div class="error-actions">
        <a href="javascript:location.reload()" class="btn-primary">
            <i class="bi bi-arrow-clockwise"></i>
            Try Again
        </a>
        <a href="mailto:{{ branding('contact_email', 'admin@university.edu') }}" class="btn-secondary">
            <i class="bi bi-envelope"></i>
            Contact Support
        </a>
    </div>

    <div class="error-details">
        <div class="error-detail-item">
            <i class="bi bi-info-circle"></i>
            <span>Error Code: 503 Service Unavailable</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-clock"></i>
            <span>{{ now()->format('M d, Y H:i:s') }}</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-tools"></i>
            <span>Maintenance in Progress</span>
        </div>
    </div>

    <div class="error-footer">
        <p>Expected to be back online soon. For updates, visit <a href="{{ url('/') }}">{{ branding('system_name', 'Platform') }}</a> or <a href="mailto:{{ branding('contact_email', 'admin@university.edu') }}">contact support</a>.</p>
    </div>
@endsection
