@extends('emails.layouts.email-base')

@section('preheader', 'Great news — Your ' . branding('system_name', 'Platform') . ' account has been approved and is now active!')

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: #ecfdf5; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #047857; letter-spacing: 0.3px;">
                Account Approved
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #059669;">
                You can now log in and start reserving
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
        We're pleased to inform you that your <strong>{{ branding('system_name', 'Platform') }}</strong> account has been reviewed and <strong style="color: #047857;">approved</strong> by an administrator. Your account is now fully active.
    </p>

    {{-- Account Info Card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
        <tr>
            <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px 26px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 4px 0; font-size: 14px; color: #64748b; width: 100px;">Name</td>
                        <td style="padding: 4px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; font-size: 14px; color: #64748b;">Email</td>
                        <td style="padding: 4px 0; font-size: 14px; color: #0f172a;">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; font-size: 14px; color: #64748b;">Status</td>
                        <td style="padding: 4px 0; font-size: 14px;">
                            <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #dcfce7; color: #166534;">
                                Active
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        You can now log in, browse available facilities, and make reservations for your events.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
        <tr>
            <td style="border-radius: 8px; background-color: {{ branding('brand_primary', '#2563eb') }};">
                <a href="{{ route('login') }}" target="_blank" style="display: inline-block; padding: 14px 36px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                    Log In Now
                </a>
            </td>
        </tr>
    </table>
@endsection
