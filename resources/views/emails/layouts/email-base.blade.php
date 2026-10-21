{{--
    Master Email Layout — fully white-label via CMS branding settings.
    All system emails extend this layout for consistent branding.
    Uses 100% inline CSS for maximum email client compatibility.
--}}
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ branding('system_name', 'Platform') }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f1f5f9; }
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .content-padding { padding-left: 24px !important; padding-right: 24px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">

    {{-- Hidden preheader text --}}
    <div style="display: none; font-size: 1px; color: #f1f5f9; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        @yield('preheader', branding('system_name', 'Platform') . ' — System Notification')
        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f1f5f9;">
        <tr>
            <td style="padding: 32px 12px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" class="email-container" style="margin: 0 auto; max-width: 600px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.07);">

                    {{-- ── HEADER ─────────────────────────────────────────── --}}
                    <tr>
                        <td style="padding: 0;">
                            {!! \App\Services\BrandingService::emailHeaderHtml() !!}
                        </td>
                    </tr>

                    {{-- ── STATUS BANNER (optional) ────────────────────────── --}}
                    @hasSection('status_banner')
                        <tr>
                            <td style="padding: 0;">
                                @yield('status_banner')
                            </td>
                        </tr>
                    @endif

                    {{-- ── MAIN CONTENT ────────────────────────────────────── --}}
                    <tr>
                        <td style="background-color: #ffffff; padding: 40px 44px 36px 44px;" class="content-padding">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- ── FOOTER ──────────────────────────────────────────── --}}
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 28px 44px 24px 44px; text-align: center;">
                            @php
                                $footerName    = branding('system_name', 'Platform');
                                $contactEmail  = trim(branding('contact_email', '') ?? '');
                                $contactPhone  = trim(branding('contact_phone', '') ?? '');
                                $emailSig      = trim(branding('email_signature', '') ?? '');
                                $brandPrimary  = branding('brand_primary', '#2563eb');
                            @endphp

                            {{-- Brand name in footer --}}
                            <p style="margin: 0 0 6px 0; font-size: 13px; font-weight: 700; color: #334155; letter-spacing: 0.3px;">
                                {{ $footerName }}
                            </p>

                            {{-- Contact info --}}
                            @if ($contactEmail || $contactPhone)
                                <p style="margin: 0 0 14px 0; font-size: 12px; color: #64748b; line-height: 20px;">
                                    @if ($contactEmail)
                                        <a href="mailto:{{ $contactEmail }}" style="color: {{ $brandPrimary }}; text-decoration: none;">{{ $contactEmail }}</a>
                                    @endif
                                    @if ($contactEmail && $contactPhone)
                                        &nbsp;&middot;&nbsp;
                                    @endif
                                    @if ($contactPhone)
                                        <span>{{ $contactPhone }}</span>
                                    @endif
                                </p>
                            @endif

                            {{-- CMS email signature (HTML block, optional) --}}
                            @if ($emailSig)
                                <div style="margin: 0 0 16px 0; font-size: 12px; color: #64748b; line-height: 20px;">
                                    {!! $emailSig !!}
                                </div>
                            @endif

                            {{-- Divider --}}
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 0 14px 0;">
                                <tr><td style="height: 1px; background-color: #e2e8f0;"></td></tr>
                            </table>

                            <p style="margin: 0 0 4px 0; font-size: 11.5px; color: #94a3b8; line-height: 18px;">
                                This is an automated message from {{ $footerName }}. Please do not reply directly to this email.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #cbd5e1; line-height: 17px;">
                                &copy; {{ date('Y') }} {{ $footerName }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                    {{-- ── BRAND ACCENT BAR (bottom) ───────────────────────── --}}
                    <tr>
                        <td style="height: 4px; background-color: {{ branding('brand_primary', '#2563eb') }}; border-radius: 0 0 12px 12px;"></td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
