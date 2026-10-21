@extends('emails.layouts.email-base')

@section('preheader', 'Reset your ' . branding('system_name', 'Platform') . ' password — this link expires in 60 minutes.')

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: {{ \App\Services\BrandingService::emailBrandTint() }}; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15) }}; letter-spacing: 0.3px;">
                Password Reset
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.4) }};">
                Someone requested a password reset for your account
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
        Hello,
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        We received a request to reset the password for your <strong>{{ branding('system_name', 'Platform') }}</strong> account. Click the button below to set a new password.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 26px auto;">
        <tr>
            <td style="border-radius: 8px; background-color: {{ branding('brand_primary', '#2563eb') }};">
                <a href="{{ $resetUrl }}" target="_blank" style="display: inline-block; padding: 14px 40px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                    Reset My Password
                </a>
            </td>
        </tr>
    </table>

    {{-- Expiry Warning --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="background-color: #fffbeb; border-left: 3px solid #d97706; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #92400e; line-height: 22px;">
                    <strong>This link expires in 60 minutes.</strong><br>
                    If the link has expired, you can request a new one from the login page.
                </p>
            </td>
        </tr>
    </table>

    {{-- Security Info --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 20px 0;">
        <tr>
            <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px 24px;">
                <h4 style="margin: 0 0 10px 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b;">
                    Security Tips
                </h4>
                <ul style="margin: 0; padding-left: 18px; font-size: 14px; color: #475569; line-height: 24px;">
                    <li>Use at least 8 characters with uppercase, lowercase, numbers, and symbols</li>
                    <li>Don't reuse passwords from other websites</li>
                    <li>Never share your password with anyone</li>
                </ul>
            </td>
        </tr>
    </table>

    <p style="margin: 0; font-size: 13px; color: #94a3b8; line-height: 20px;">
        If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.
    </p>

    {{-- Fallback URL --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 22px 0 0 0;">
        <tr>
            <td style="border-top: 1px solid #e2e8f0; padding-top: 16px;">
                <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 18px; word-break: break-all;">
                    If the button doesn't work, copy and paste this URL into your browser:<br>
                    <a href="{{ $resetUrl }}" style="color: {{ branding('brand_primary', '#2563eb') }}; text-decoration: none;">{{ $resetUrl }}</a>
                </p>
            </td>
        </tr>
    </table>
@endsection
