@extends('emails.layouts.email-base')

@section('preheader', 'Your reservation for ' . $reservation->facility->name . ' has been submitted. Ref: ' . $reservation->booking_reference)

@section('status_banner')
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="background-color: {{ \App\Services\BrandingService::emailBrandTint() }}; padding: 22px 44px; text-align: center;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15) }}; letter-spacing: 0.3px;">
                Reservation Submitted
            </h2>
            <p style="margin: 4px 0 0 0; font-size: 13px; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.4) }};">
                {{ $reservation->booking_reference }}
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')
    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700; color: {{ branding('brand_dark', '#0f172a') }};">
        Hi {{ $reservation->user->first_name }},
    </h3>
    <p style="margin: 0 0 26px 0; font-size: 15px; color: #475569; line-height: 24px;">
        Your reservation has been successfully submitted and is now awaiting review. Here are your booking details:
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
                        <td style="padding: 6px 0; font-size: 14px; color: {{ branding('brand_dark', '#0f172a') }}; font-weight: 700;">{{ $reservation->booking_reference }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Facility</td>
                        <td style="padding: 6px 0; font-size: 14px; color: {{ branding('brand_dark', '#0f172a') }}; font-weight: 600;">{{ $reservation->facility->name }}</td>
                    </tr>
                    @if($reservation->event_title)
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Event</td>
                        <td style="padding: 6px 0; font-size: 14px; color: {{ branding('brand_dark', '#0f172a') }};">{{ $reservation->event_title }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Date</td>
                        <td style="padding: 6px 0; font-size: 14px; color: {{ branding('brand_dark', '#0f172a') }};">
                            {{ $reservation->start_date->format('M d, Y') }}
                            @if($reservation->end_date && $reservation->end_date->ne($reservation->start_date))
                                — {{ $reservation->end_date->format('M d, Y') }}
                            @endif
                        </td>
                    </tr>
                    @if(!$reservation->is_fee_waived)
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Total Amount</td>
                        <td style="padding: 6px 0; font-size: 16px; color: {{ branding('brand_dark', '#0f172a') }}; font-weight: 700;">₱{{ number_format($reservation->total_price, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 6px 0; font-size: 14px; color: #94a3b8; vertical-align: top;">Status</td>
                        <td style="padding: 6px 0; font-size: 14px;">
                            <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: {{ \App\Services\BrandingService::emailBrandTint() }}; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.15) }};">
                                Pending Review
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Payment or Waiver Info --}}
    @if($reservation->is_fee_waived)
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
            <tr>
                <td style="background-color: #f0fdf4; border-left: 3px solid #10b981; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: #166534; line-height: 22px;">
                        <strong>Fees Waived</strong><br>
                        As an internal client, facility fees have been waived for this reservation. No payment is required.
                    </p>
                </td>
            </tr>
        </table>
    @elseif($reservation->payment_method === 'cash')
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
            <tr>
                <td style="background-color: #fffbeb; border-left: 3px solid #d97706; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: #92400e; line-height: 22px;">
                        <strong>Cash Payment Required</strong><br>
                        Please visit the cashier to pay <strong>₱{{ number_format($reservation->total_price, 2) }}</strong> by <strong>{{ $reservation->deadlineForHumans() }}</strong>. Failure to pay by the deadline will result in automatic cancellation.
                    </p>
                </td>
            </tr>
        </table>
    @else
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 24px 0;">
            <tr>
                <td style="background-color: {{ \App\Services\BrandingService::emailBrandTint() }}; border-left: 3px solid {{ branding('brand_primary', '#2563eb') }}; padding: 16px 20px;">
                    <p style="margin: 0; font-size: 14px; color: {{ \App\Services\BrandingService::darkenHex(branding('brand_primary', '#2563eb'), 0.35) }}; line-height: 22px;">
                        <strong>Online Payment</strong><br>
                        Please complete your online payment by <strong>{{ $reservation->deadlineForHumans() }}</strong> to confirm your booking.
                    </p>
                </td>
            </tr>
        </table>
    @endif

    <p style="margin: 0; font-size: 14px; color: #475569; line-height: 22px;">
        Our team will review your reservation shortly. You'll receive another email once it's approved or if any action is needed.
    </p>
@endsection
