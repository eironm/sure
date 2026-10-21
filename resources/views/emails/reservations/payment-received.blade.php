@extends('emails.layouts.email-base')

@section('preheader', 'Payment received — Your booking for ' . $reservation->facility->name . ' is now confirmed. Ref: ' . $reservation->booking_reference)

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: #ecfdf5; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #047857; letter-spacing: 0.3px;">
                Payment Received
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
        Thank you, {{ $reservation->user->first_name }},
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        Your payment has been successfully received and your booking for <strong>{{ $reservation->facility->name }}</strong> is now <strong style="color: #047857;">confirmed</strong>.
    </p>

    {{-- Payment Summary Card --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
        <tr>
            <td style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 22px 26px;">
                <h4 style="margin: 0 0 14px 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #166534;">
                    Payment Summary
                </h4>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #64748b; width: 130px; vertical-align: top;">Reference</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: 700;">{{ $reservation->booking_reference }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #64748b; vertical-align: top;">Facility</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: 600;">{{ $reservation->facility->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #64748b; vertical-align: top;">Date</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">
                            {{ $reservation->start_date->format('M d, Y') }}
                            @if($reservation->end_date && $reservation->end_date->ne($reservation->start_date))
                                — {{ $reservation->end_date->format('M d, Y') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #64748b; vertical-align: top;">Total Amount</td>
                        <td style="padding: 6px 0; font-size: 16px; color: #0f172a; font-weight: 700;">₱{{ number_format($reservation->total_price, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #64748b; vertical-align: top;">Amount Paid</td>
                        <td style="padding: 6px 0; font-size: 16px; color: #059669; font-weight: 700;">₱{{ number_format($reservation->amount_paid, 2) }}</td>
                    </tr>
                    @if($reservation->payment_method)
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #64748b; vertical-align: top;">Payment Method</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">{{ ucfirst($reservation->payment_method) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #64748b; vertical-align: top;">Status</td>
                        <td style="padding: 6px 0; font-size: 14px;">
                            <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #dcfce7; color: #166534;">
                                Paid
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        Your booking is now fully confirmed. You can view your reservation details and download your booking slip from your dashboard.
    </p>

    {{-- CTA Button --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
        <tr>
            <td style="border-radius: 8px; background-color: {{ branding('brand_primary', '#2563eb') }};">
                <a href="{{ route('guest.history') }}" target="_blank" style="display: inline-block; padding: 14px 36px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                    View My Booking
                </a>
            </td>
        </tr>
    </table>
@endsection
