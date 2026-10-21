@extends('errors.layout')

@section('title', '403 - Access Forbidden')

@section('content')
    <div class="error-header">
        <div class="sure-logo">
            <img src="{{ \App\Services\BrandingService::logoForBg('dark') }}" alt="{{ branding('system_name', 'Platform') }} Logo">
            <span class="sure-logo-text">{{ branding('system_name', 'Platform') }}</span>
        </div>
    </div>

    <div class="error-icon error-403">
        <i class="bi bi-lock-fill"></i>
    </div>

    <div class="error-code">403</div>

    <h1 class="error-title">Access Forbidden</h1>

    <p class="error-description">
        You don't have permission to access this resource. If you believe this is a mistake, please contact your administrator.
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
            <span>Error Code: 403 Forbidden</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-clock"></i>
            <span>{{ now()->format('M d, Y H:i:s') }}</span>
        </div>
        <div class="error-detail-item">
            <i class="bi bi-shield-exclamation"></i>
            <span>Insufficient Permissions</span>
        </div>
    </div>

    <div class="error-footer">
        <p>Need access? <a href="mailto:{{ branding('contact_email', 'admin@university.edu') }}">Request permissions</a> or contact your <a href="{{ url('/') }}">administrator</a>.</p>
    </div>
@endsection
