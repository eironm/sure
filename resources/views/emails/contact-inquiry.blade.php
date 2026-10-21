@extends('emails.layouts.email-base')

@section('preheader', 'New contact inquiry from ' . $visitorName)

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: {{ \App\Services\BrandingService::emailBrandTint() }}; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15) }}; letter-spacing: 0.3px;">
                New Contact Form Inquiry
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.4) }};">
                Received via {{ branding('system_name', 'Platform') }} Landing Page
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: {{ branding('brand_dark', '#0f172a') }};">
        Hello Facilities Team,
    </h3>
    <p style="margin: 0 0 24px 0; font-size: 15px; color: #475569; line-height: 24px;">
        A new visitor inquiry has been submitted through the public contact form:
    </p>

    {{-- Sender Details Card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px 24px;">
                <h4 style="margin: 0 0 14px 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b;">
                    Sender Information
                </h4>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; width: 120px; vertical-align: top;">Name</td>
                        <td style="padding: 6px 0; font-size: 14px; color: {{ branding('brand_dark', '#0f172a') }}; font-weight: 700;">{{ $visitorName }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Email</td>
                        <td style="padding: 6px 0; font-size: 14px; color: {{ branding('brand_primary', '#2563eb') }}; font-weight: 600;">
                            <a href="mailto:{{ $visitorEmail }}" style="color: {{ branding('brand_primary', '#2563eb') }}; text-decoration: none;">{{ $visitorEmail }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Date &amp; Time</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #64748b;">{{ $submittedAt }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Message Body --}}
    <div style="margin: 0 0 28px 0;">
        <h4 style="margin: 0 0 10px 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b;">
            Message
        </h4>
        <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-left: 4px solid {{ branding('brand_primary', '#2563eb') }}; border-radius: 6px; padding: 18px 20px; font-size: 14px; color: #334155; line-height: 22px; white-space: pre-wrap;">{{ $body }}</div>
    </div>

    {{-- Quick Reply Action Button --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 10px auto;">
        <tr>
            <td style="border-radius: 8px; background-color: {{ branding('brand_primary', '#2563eb') }}; text-align: center;">
                <a href="mailto:{{ $visitorEmail }}?subject={{ rawurlencode('Re: ' . branding('system_name', 'Platform') . ' Contact Inquiry') }}" style="background-color: {{ branding('brand_primary', '#2563eb') }}; border: 12px 24px; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; display: inline-block; border-radius: 8px; padding: 12px 28px;">
                    Reply to {{ $visitorName }}
                </a>
            </td>
        </tr>
    </table>
@endsection
