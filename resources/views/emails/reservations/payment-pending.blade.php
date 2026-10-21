@extends('emails.layouts.email-base')

@section('preheader', 'Action required: Complete your payment for ' . $reservation->facility->name . ' by ' . $reservation->deadlineForHumans() . '. Ref: ' . $reservation->booking_reference)

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: #fffbeb; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #b45309; letter-spacing: 0.3px;">
                Payment Pending
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: #d97706;">
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
        Your reservation for <strong>{{ $reservation->facility->name }}</strong> is awaiting payment. Please complete your payment to confirm your booking.
    </p>

    {{-- Deadline Warning --}}
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
        <tr>
            <td style="background-color: #fffbeb; border: 1px solid #f5d07d; border-radius: 10px; padding: 18px 24px; text-align: center;">
                <p style="margin: 0 0 4px 0; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #b45309;">
                    Payment Deadline
                </p>
                <p style="margin: 0; font-size: 18px; font-weight: 800; color: #92400e;">
                    {{ $reservation->deadlineForHumans() }}
                </p>
            </td>
        </tr>
    </table>

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
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Amount Due</td>
                        <td style="padding: 6px 0; font-size: 16px; color: #b45309; font-weight: 700;">₱{{ number_format($reservation->remaining_balance, 2) }}</td>
                    </tr>
                    @if((float) $reservation->amount_paid > 0)
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Already Paid</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #059669; font-weight: 600;">₱{{ number_format($reservation->amount_paid, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Payment Method</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a;">{{ $reservation->payment_method === 'cash' ? 'Cash (at Cashier)' : 'Online Payment' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Payment Instructions --}}
    @if($reservation->payment_method === 'cash')
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 26px 0;">
            <tr>
                <td style="background-color: #f8fafc; border-left: 3px solid #94a3b8; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: #475569; line-height: 22px;">
                        <strong>Cash Payment Instructions</strong><br>
                        Please visit the cashier window and present your booking reference <strong>{{ $reservation->booking_reference }}</strong> to complete your payment. Failure to pay by the deadline will result in automatic cancellation.
                    </p>
                </td>
            </tr>
        </table>
    @else
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 26px auto;">
            <tr>
                <td style="border-radius: 8px; background-color: {{ branding('brand_primary', '#2563eb') }};">
                    <a href="{{ route('guest.history') }}" target="_blank" style="display: inline-block; padding: 14px 40px; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; border-radius: 8px;">
                        Complete Payment Now
                    </a>
                </td>
            </tr>
        </table>
    @endif

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0;">
        <tr>
            <td style="background-color: #fef2f2; border-left: 3px solid #dc2626; padding: 16px 20px;">
                <p style="margin: 0; font-size: 14px; color: #991b1b; line-height: 22px;">
                    <strong>Important:</strong> If payment is not received by <strong>{{ $reservation->deadlineForHumans() }}</strong>, your reservation will be <strong>automatically cancelled</strong>.
                </p>
            </td>
        </tr>
    </table>
@endsection
