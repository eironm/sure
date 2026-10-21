@extends('emails.layouts.email-base')

@section('preheader', 'Your reservation for ' . $reservation->facility->name . ' has been approved! Ref: ' . $reservation->booking_reference)

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: #ecfdf5; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #047857; letter-spacing: 0.3px;">
                Reservation Approved
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #059669;">
                {{ $reservation->booking_reference }}
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
        Great news, {{ $reservation->user->first_name }},
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        Your reservation for <strong>{{ $reservation->facility->name }}</strong> has been <strong style="color: #047857;">approved</strong> by the administration team.
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
                    @if(!$reservation->is_fee_waived)
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Total</td>
                        <td style="padding: 6px 0; font-size: 16px; color: #0f172a; font-weight: 700;">₱{{ number_format($reservation->total_price, 2) }}</td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    @if($reservation->admin_remarks)
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
        <tr>
            <td style="background-color: #f8fafc; border-left: 3px solid #94a3b8; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #475569; line-height: 22px;">
                    <strong>Admin Remarks:</strong><br>
                    {{ $reservation->admin_remarks }}
                </p>
            </td>
        </tr>
    </table>
    @endif

    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        You can now proceed with your booking. Visit your dashboard to view your reservation details.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
        <tr>
            <td style="border-radius: 8px; background-color: {{ branding('brand_primary', '#2563eb') }};">
                <a href="{{ route('guest.history') }}" target="_blank" style="display: inline-block; padding: 14px 36px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                    View My Reservations
                </a>
            </td>
        </tr>
    </table>
@endsection
