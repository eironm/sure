@extends('emails.layouts.email-base')

@section('preheader', 'Welcome to ' . branding('system_name', 'Platform') . ' — Your account has been created successfully!')

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: {{ \App\Services\BrandingService::emailBrandTint() }}; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15) }}; letter-spacing: 0.3px;">
                Welcome to {{ branding('system_name', 'Platform') }}
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.4) }};">
                Your account has been created successfully
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
        Hello, {{ $user->first_name }},
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        Thank you for registering with <strong>{{ branding('system_name', 'Platform') }}</strong>@if(branding('system_tagline')) — {{ branding('system_tagline') }}@endif. We're glad to have you.
    </p>

    {{-- Registration Details Card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
        <tr>
            <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px 26px;">
                <h4 style="margin: 0 0 14px 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b;">
                    Registration Details
                </h4>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; width: 120px; vertical-align: top;">Name</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Email</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Client Type</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">
                            <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; {{ $user->client_type === 'internal' ? 'background-color: #eff6ff; color: #1d4ed8;' : 'background-color: #fae8ff; color: #9333ea;' }}">
                                {{ ucfirst($user->client_type) }}
                            </span>
                        </td>
                    </tr>
                    @if($user->client_type === 'internal')
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Affiliation</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">{{ $user->affiliation_type ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Department</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">{{ $user->college_department ?? 'N/A' }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    {{-- Status Message (differs by client type) --}}
    @if($user->client_type === 'internal')
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
            <tr>
                <td style="background-color: #fffbeb; border-left: 3px solid #d97706; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: #92400e; line-height: 22px;">
                        <strong>Pending Approval</strong><br>
                        Since you registered as an <strong>internal</strong> client, your account requires administrator approval before you can log in. You'll receive an email once your account is activated.
                    </p>
                </td>
            </tr>
        </table>
    @else
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
            <tr>
                <td style="background-color: #f0fdf4; border-left: 3px solid #10b981; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: #166534; line-height: 22px;">
                        <strong>Account Active</strong><br>
                        Your account is now active! You can log in immediately and start browsing our facilities to make a reservation.
                    </p>
                </td>
            </tr>
        </table>

        {{-- CTA Button --}}
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
            <tr>
                <td style="border-radius: 8px; background-color: {{ branding('brand_primary', '#2563eb') }};">
                    <a href="{{ route('login') }}" target="_blank" style="display: inline-block; padding: 14px 36px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                        Log In to {{ branding('system_name', 'Platform') }}
                    </a>
                </td>
            </tr>
        </table>
    @endif

    {{-- What's Next --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0 0 0;">
        <tr>
            <td style="border-top: 1px solid #e2e8f0; padding-top: 22px;">
                <h4 style="margin: 0 0 12px 0; font-size: 15px; font-weight: 700; color: #0f172a;">What You Can Do with {{ branding('system_name', 'Platform') }}</h4>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 5px 0; font-size: 14px; color: #475569; line-height: 22px;">Browse available facilities and venues</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; font-size: 14px; color: #475569; line-height: 22px;">Reserve facilities for your events</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; font-size: 14px; color: #475569; line-height: 22px;">Pay online or at the cashier</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; font-size: 14px; color: #475569; line-height: 22px;">Get real-time updates on your bookings</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
