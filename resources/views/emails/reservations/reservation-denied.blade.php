@extends('emails.layouts.email-base')

@section('preheader', 'Your reservation for ' . $reservation->facility->name . ' has been denied. Ref: ' . $reservation->booking_reference)

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: #fef2f2; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #b91c1c; letter-spacing: 0.3px;">
                Reservation Denied
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #dc2626;">
                {{ $reservation->booking_reference }}
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
        Hi {{ $reservation->user->first_name }},
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        We regret to inform you that your reservation for <strong>{{ $reservation->facility->name }}</strong> has been <strong style="color: #b91c1c;">denied</strong> by the administration team.
    </p>

    {{-- Booking Details Card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
        <tr>
            <td style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px 26px;">
                <h4 style="margin: 0 0 14px 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b;">
                    Booking Details
                </h4>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; width: 130px; vertical-align: top;">Reference</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: 700;">{{ $reservation->booking_reference }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Facility</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ $reservation->facility->name }}</td>
                    </tr>
                    @if($reservation->event_title)
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Event</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">{{ $reservation->event_title }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Date</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">
                            {{ $reservation->start_date->format('M d, Y') }}
                            @if($reservation->end_date && $reservation->end_date->ne($reservation->start_date))
                                — {{ $reservation->end_date->format('M d, Y') }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($reservation->admin_remarks)
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="background-color: #fef2f2; border-left: 3px solid #b91c1c; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #991b1b; line-height: 22px;">
                    <strong>Reason for Denial:</strong><br>
                    {{ $reservation->admin_remarks }}
                </p>
            </td>
        </tr>
    </table>
    @endif

    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        You may submit a new reservation with the required adjustments. If you have questions, please contact our administration team.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
        <tr>
            <td style="border-radius: 8px; background-color: {{ branding('brand_primary', '#2563eb') }};">
                <a href="{{ route('guest.reserve') }}" target="_blank" style="display: inline-block; padding: 14px 36px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                    Make a New Reservation
                </a>
            </td>
        </tr>
    </table>
@endsection
