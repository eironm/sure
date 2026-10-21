@extends('emails.layouts.email-base')

@section('preheader', 'Your ' . branding('system_name', 'Platform') . ' account has been deactivated.')

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: #fef2f2; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #b91c1c; letter-spacing: 0.3px;">
                Account Deactivated
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #dc2626;">
                Your access to {{ branding('system_name', 'Platform') }} has been suspended
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
        We're writing to inform you that your <strong>{{ branding('system_name', 'Platform') }}</strong> account has been <strong style="color: #b91c1c;">deactivated</strong> by an administrator. You will no longer be able to log in or make reservations until your account is reactivated.
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
                            <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #fee2e2; color: #991b1b;">
                                Deactivated
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- What This Means --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="background-color: #fffbeb; border-left: 3px solid #d97706; padding: 16px 20px;">
                <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 700; color: #92400e;">What This Means</p>
                <ul style="margin: 0; padding-left: 18px; font-size: 14px; color: #92400e; line-height: 22px;">
                    <li>You cannot log into your {{ branding('system_name', 'Platform') }} account</li>
                    <li>Any pending reservations remain on file</li>
                    <li>Your account data is preserved and not deleted</li>
                </ul>
            </td>
        </tr>
    </table>

    <p style="margin: 0; font-size: 15px; color: #475569; line-height: 24px;">
        If you believe this was done in error or have questions, please contact our administration team for assistance.
    </p>
@endsection
