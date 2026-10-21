<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($reservation->payment_method === 'cash')
            Cash Payment Slip — {{ $reservation->booking_reference }}
        @else
            Digital Payment Receipt — {{ $reservation->booking_reference }}
        @endif
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:        #0b1220;
            --ink-2:      #334155;
            --muted:      #64748b;
            --line:       #e4e8ef;
            --line-soft:  #eef1f6;
            --paper:      #ffffff;
            --bg:         #e9edf4;
            --green:      #047857;
            --green-bg:   #ecfdf5;
            --green-line: #a7f3d0;
            --blue:       #1d4ed8;
            --blue-bg:    #eff6ff;
            --blue-line:  #bfdbfe;
            --amber:      #b45309;
            --amber-bg:   #fffbeb;
            --amber-line: #fde68a;
            --red:        #be123c;
            --gold:       #a16207;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--ink);
            padding: 2.5rem 1.5rem 4rem;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Screen Controls ─────────────── */
        .screen-controls {
            max-width: 820px;
            margin: 0 auto 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .sc-back {
            display: inline-flex; align-items: center; gap: .5rem;
            color: var(--muted); font-size: .875rem; font-weight: 600;
            text-decoration: none; transition: color .15s;
        }
        .sc-back:hover { color: var(--ink); }
        .ctrl-btn {
            display: inline-flex; align-items: center; gap: .5rem;
            background: var(--ink); color: #fff; border: none;
            padding: .6rem 1.25rem; border-radius: 999px;
            font-size: .85rem; font-weight: 700; cursor: pointer;
            text-decoration: none; transition: transform .15s, box-shadow .15s;
            box-shadow: 0 2px 8px rgba(11,18,32,.25);
        }
        .ctrl-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(11,18,32,.3); color: #fff; }

        /* ══════ DOCUMENT ══════ */
        .document {
            max-width: 820px;
            margin: 0 auto 3rem;
            background: var(--paper);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(11,18,32,.06), 0 12px 40px rgba(11,18,32,.10);
            position: relative;
        }

        /* ── Letterhead ─────────────────── */
        .letterhead {
            position: relative;
            background: linear-gradient(130deg, #0b1220 0%, #131d33 55%, #1a2744 100%);
            color: #fff;
            padding: 2rem 2.75rem 2.4rem;
            overflow: hidden;
        }
        .letterhead::before {
            content: '';
            position: absolute; top: -110%; right: -12%;
            width: 460px; height: 460px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.07), transparent 68%);
        }
        .letterhead::after {
            content: '';
            position: absolute; top: -40%; left: 30%;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(96,165,250,.14), transparent 70%);
        }
        .lh-row { position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: flex-start; gap: 2rem; }

        .lh-brand { display: flex; align-items: center; gap: .9rem; }
        .lh-logo {
            width: 52px; height: 52px; border-radius: 13px; flex-shrink: 0;
            background: #fff; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 14px rgba(0,0,0,.35);
        }
        .lh-logo img { width: 40px; height: 40px; object-fit: contain; }
        .lh-name { font-size: 1.3rem; font-weight: 900; letter-spacing: -0.02em; }
        .lh-org { font-size: .78rem; color: rgba(255,255,255,.6); margin-top: 2px; font-weight: 500; }

        .lh-title-block { text-align: right; }
        .lh-doc-type {
            display: inline-flex; align-items: center; gap: .45rem;
            font-size: .7rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase;
            color: rgba(255,255,255,.55); margin-bottom: .5rem;
        }
        .lh-doc-type i { font-size: .85rem; }
        .lh-title { font-size: 1.55rem; font-weight: 900; letter-spacing: -0.02em; line-height: 1.15; }
        .lh-sub { font-size: .8rem; color: rgba(255,255,255,.65); margin-top: .35rem; font-weight: 500; }

        /* Perforated edge below letterhead */
        .perforation {
            height: 10px;
            background:
                radial-gradient(circle at 8px 0, var(--bg) 7px, transparent 8px) top / 16px 100%,
                linear-gradient(var(--paper), var(--paper));
            border-top: 1px dashed #c8d0dc;
        }

        /* ── Ticket reference strip ─────── */
        .ticket-strip {
            display: flex; align-items: stretch;
            border-bottom: 1px solid var(--line);
        }
        .ticket-ref {
            flex: 1;
            padding: 1.1rem 2.75rem;
            display: flex; align-items: center; gap: 1.1rem;
        }
        .ticket-ref-lbl {
            font-size: .64rem; font-weight: 800; text-transform: uppercase; letter-spacing: .14em;
            color: var(--muted); white-space: nowrap; line-height: 1.4;
        }
        .ticket-ref-no {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.35rem; font-weight: 700; color: var(--ink); letter-spacing: .04em;
        }
        .ticket-meta {
            border-left: 1px solid var(--line);
            padding: .9rem 2rem;
            display: flex; flex-direction: column; justify-content: center; gap: .3rem;
            background: var(--line-soft);
        }
        .tm-item { font-size: .74rem; color: var(--ink-2); }
        .tm-item strong { font-weight: 700; color: var(--ink); }

        /* ── Status chip ─────────────────── */
        .status-row {
            padding: 0 2.75rem;
            display: flex; align-items: center; gap: .75rem;
            padding-top: 1.4rem; padding-bottom: .4rem;
        }
        .chip {
            display: inline-flex; align-items: center; gap: .4rem;
            font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em;
            padding: .32rem .8rem; border-radius: 999px;
        }
        .chip-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .chip.paid    { background: var(--green-bg);  color: var(--green);  border: 1px solid var(--green-line); }
        .chip.paid .chip-dot { background: var(--green); }
        .chip.partial { background: var(--blue-bg);   color: var(--blue);   border: 1px solid var(--blue-line); }
        .chip.partial .chip-dot { background: var(--blue); }
        .chip.due     { background: var(--amber-bg);  color: var(--amber);  border: 1px solid var(--amber-line); }
        .chip.due .chip-dot { background: var(--amber); animation: pulse 1.8s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.35;} }

        /* ── Sections ────────────────────── */
        .section { padding: 1.5rem 2.75rem 1.75rem; border-bottom: 1px solid var(--line-soft); }
        .section:last-child { border-bottom: none; }

        .sec-head {
            display: flex; align-items: center; gap: .6rem;
            margin-bottom: 1.2rem;
        }
        .sec-head i { font-size: .95rem; color: var(--ink); }
        .sec-title {
            font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .12em;
            color: var(--muted);
        }
        .sec-kicker {
            font-size: .68rem; font-weight: 700; color: var(--muted);
            border-bottom: 2px solid var(--line); flex: 1; height: 100%; min-height: 9px;
            margin-left: .5rem; transform: translateY(-4px);
        }

        .grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 1.15rem 2.5rem;
        }
        .field { }
        .field-lbl {
            font-size: .64rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
            color: var(--muted); margin-bottom: .3rem; display: block;
        }
        .field-val {
            font-size: .95rem; font-weight: 650; color: var(--ink); line-height: 1.45;
        }
        .field-val.mono {
            font-family: 'JetBrains Mono', monospace; font-size: .8rem; word-break: break-all;
        }

        /* ── Itemized requirements table ─ */
        .items-table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: .25rem; }
        .items-table th {
            font-size: .64rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em;
            color: var(--muted); text-align: left; padding: .65rem .8rem;
            background: var(--line-soft); border-bottom: 1px solid var(--line);
        }
        .items-table th:first-child { border-radius: 8px 0 0 0; }
        .items-table th:last-child  { border-radius: 0 8px 0 0; text-align: right; }
        .items-table th.r, .items-table td.r { text-align: right; }
        .items-table td {
            padding: .85rem .8rem; border-bottom: 1px dashed var(--line);
            font-size: .9rem; color: var(--ink-2); vertical-align: middle;
        }
        .items-table td.item-name { font-weight: 650; color: var(--ink); }
        .items-table td .qty-badge {
            display: inline-flex; align-items: center; justify-content: center;
            font-family: 'JetBrains Mono', monospace; font-size: .75rem; font-weight: 700;
            background: var(--line-soft); border: 1px solid var(--line);
            border-radius: 6px; padding: .15rem .5rem; margin-left: .5rem;
        }
        .items-table tr:last-child td { border-bottom: none; }
        .items-table td .free-tag { font-size: .72rem; font-weight: 700; color: var(--green); }

        /* ── Totals panel ────────────────── */
        .totals-panel {
            margin: 1.5rem 2.75rem 0;
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(11,18,32,.05);
        }
        .totals-body { display: flex; gap: 2rem; }
        .totals-left {
            flex: 1; padding: 1.4rem 1.6rem;
            display: flex; flex-direction: column; gap: .55rem;
        }
        .tot-line { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .tot-lbl { font-size: .78rem; font-weight: 600; color: var(--ink-2); }
        .tot-val { font-size: .85rem; font-weight: 700; color: var(--ink); }
        .tot-dash { border-top: 1px dashed var(--line); }
        .totals-right {
            width: 300px; flex-shrink: 0;
            display: flex; flex-direction: column; align-items: flex-end; justify-content: center;
            padding: 1.4rem 1.6rem;
            border-left: 1px solid var(--line);
            position: relative;
        }
        .totals-right .tot-lbl { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); }
        .totals-right .grand {
            font-size: 1.85rem; font-weight: 900; letter-spacing: -0.02em; color: var(--ink); line-height: 1.1;
            margin-top: .15rem;
        }
        .totals-right .grand small { font-size: 1rem; font-weight: 700; }

        /* Watermark-style big label */
        .watermark {
            position: absolute; right: 1rem; bottom: .4rem;
            font-size: 2.6rem; font-weight: 900; letter-spacing: .02em;
            color: rgba(11,18,32,.05); line-height: 1; z-index: 0;
            user-select: none; pointer-events: none;
        }

        /* ── Notice banner ───────────────── */
        .notice {
            margin: 1.5rem 2.75rem 0;
            border-radius: 12px;
            border: 1px solid;
            padding: 1.1rem 1.4rem;
            display: flex; gap: .85rem;
            font-size: .86rem; line-height: 1.6;
        }
        .notice-icon {
            width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; font-weight: 800;
        }
        .notice.paid    { background: var(--green-bg);  border-color: var(--green-line); color: #065f46; }
        .notice.paid .notice-icon    { background: #fff; color: var(--green);  border: 1px solid var(--green-line); }
        .notice.partial { background: var(--blue-bg);   border-color: var(--blue-line);  color: #1e40af; }
        .notice.partial .notice-icon { background: #fff; color: var(--blue);    border: 1px solid var(--blue-line); }
        .notice.due     { background: var(--amber-bg);  border-color: var(--amber-line); color: #92400e; }
        .notice.due .notice-icon     { background: #fff; color: var(--amber);   border: 1px solid var(--amber-line); }
        .notice strong { font-weight: 800; }

        /* ── Verified-by block ───────────── */
        .verified {
            margin: 1.5rem 2.75rem 0;
            border: 1px solid var(--green-line);
            background: linear-gradient(180deg, #f0fdf9, #ffffff);
            border-radius: 12px; padding: 1.1rem 1.4rem;
        }
        .verified-head {
            display: flex; align-items: center; gap: .5rem;
            font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
            color: var(--green); margin-bottom: .85rem;
        }
        .verified-grid { display: grid; grid-template-columns: 1.3fr 1fr 1fr; gap: 1.25rem; }
        .v-lbl { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .09em; color: var(--muted); margin-bottom: .2rem; }
        .v-val { font-size: .875rem; font-weight: 700; color: var(--ink); }
        .v-val .role { font-size: .72rem; font-weight: 600; color: var(--muted); }
        .v-val.mono { font-family: 'JetBrains Mono', monospace; font-size: .8rem; color: var(--green); }
        .v-notes {
            margin-top: .7rem; padding-top: .6rem; border-top: 1px dashed var(--green-line);
            font-size: .8rem; color: var(--ink-2); font-style: italic;
        }

        /* ── Manual signature block ──────── */
        .signoff {
            display: grid; grid-template-columns: 1fr 1fr; gap: 1.75rem;
            margin: 1.5rem 2.75rem 0;
        }
        .sig-block { }
        .sig-line { height: 2.25rem; border-bottom: 1.5px solid #94a3b8; }
        .sig-cap { font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); margin-top: .35rem; }

        /* ── Transaction details (digital) ── */
        .tx-box {
            border: 1px solid var(--line); border-radius: 12px; overflow: hidden;
            margin-top: 1rem;
        }
        .tx-row {
            display: flex; justify-content: space-between; align-items: baseline; gap: 1rem;
            padding: .7rem 1.1rem; font-size: .82rem;
            border-bottom: 1px solid var(--line-soft);
        }
        .tx-row:last-child { border-bottom: none; }
        .tx-row:nth-child(odd) { background: var(--line-soft); }
        .tx-k { font-weight: 700; color: var(--muted); font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; }
        .tx-v { font-weight: 650; color: var(--ink); }
        .tx-v.mono { font-family: 'JetBrains Mono', monospace; font-size: .75rem; word-break: break-all; text-align: right; }

        /* ── Tear-off stub ────────────────── */
        .stub-zone { margin-top: 1.75rem; }
        .tear-line {
            display: flex; align-items: center; gap: .75rem;
            padding: 0 2.75rem; margin-bottom: 0;
            color: var(--muted); font-size: .66rem; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase;
        }
        .tear-line::before, .tear-line::after {
            content: ''; flex: 1; height: 0;
            border-top: 2px dashed #c3ccd8;
        }
        .tear-scissors { font-size: .9rem; color: #94a3b8; }
        .stub {
            margin: .5rem 2.75rem 0;
            border: 1px solid var(--line); border-radius: 10px;
            background: linear-gradient(180deg, #fafbfc, #f6f8fb);
            padding: .9rem 1.3rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        }
        .stub-ref-lbl { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); margin-bottom: .2rem; }
        .stub-ref-no { font-family: 'JetBrains Mono', monospace; font-weight: 700; font-size: .85rem; letter-spacing: .04em; color: var(--ink); }
        .stub-col { text-align: right; }
        .stub-col-lbl { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); }
        .stub-col-val { font-size: .82rem; font-weight: 700; color: var(--ink); margin-top: .1rem; }

        /* ── Footer ──────────────────────── */
        .doc-footer {
            padding: 1.1rem 2.75rem;
            background: var(--line-soft);
            border-top: 1px solid var(--line);
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            font-size: .7rem; color: var(--muted);
        }
        .doc-footer .mono { font-family: 'JetBrains Mono', monospace; }

        /* ── Print ───────────────────────── */
        @media print {
            body            { background: #fff; padding: 0; }
            .screen-controls { display: none !important; }
            .document        { margin-bottom: 0; box-shadow: none; border: none; border-radius: 0; page-break-after: always; }
            .document:last-of-type { page-break-after: auto; }
            .letterhead { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

@php
    $isPaid    = $reservation->payment_status === 'paid';
    $isCash    = $reservation->payment_method === 'cash';
    $isDigital = $reservation->payment_method === 'paymongo';
    $siteName  = function_exists('branding') ? (branding('system_name') ?? config('app.name')) : config('app.name');
    $brandDark = function_exists('branding') ? (branding('brand_dark', '#0b1220') ?? '#0b1220') : '#0b1220';
    $brandPrimary = function_exists('branding') ? (branding('brand_primary', '#0b1220') ?? '#0b1220') : '#0b1220';
    $deadline  = $reservation->deadlineForHumans();

    // Render junk sentinel dates (e.g. year 9999 sample data) as "Open-ended"
    $fmtDate = function ($value, $format) {
        $c = \Carbon\Carbon::parse($value);
        return $c->year > 2099 ? 'Open-ended' : $c->format($format);
    };

    $guestName = trim(($reservation->user->first_name ?? '') . ' ' . ($reservation->user->last_name ?? ''));
    if (!$guestName) $guestName = $reservation->user->name ?? 'N/A';

    // Eager-load cashier info if not already loaded
    if ($isCash && ($isPaid || $reservation->payment_status === 'partially_paid') && !$reservation->relationLoaded('cashierSignedBy')) {
        $reservation->load('cashierSignedBy');
    }

    $slipArrangements = is_string($reservation->arrangements ?? null)
        ? (json_decode($reservation->arrangements, true) ?: [])
        : ($reservation->arrangements ?? []);
@endphp

{{-- ── Screen Controls ──────────────────────────────────────── --}}
<div class="screen-controls">
    <a href="{{ route('guest.history') }}" class="sc-back">
        <i class="bi bi-chevron-left"></i> Back to History
    </a>
    <button onclick="window.print()" class="ctrl-btn">
        <i class="bi bi-printer"></i> Print {{ $isCash ? 'Payment Slip' : 'Receipt' }}
    </button>
</div>


@if($isCash)
{{-- ══════════════════════════════════════════════════════════════
     CASH PAYMENT SLIP — redesigned
══════════════════════════════════════════════════════════════ --}}
<div class="document">

    {{-- Letterhead ──────────────────────────────────────── --}}
    <div class="letterhead">
        <div class="lh-row">
            <div>
                <div class="lh-brand">
                    <div class="lh-logo">
                        @php
                            $__logoUrl = function_exists('brand_logo') ? brand_logo() : '';
                            $__logoOk = $__logoUrl && str_starts_with($__logoUrl, 'http') && !str_contains($__logoUrl, 'http://:/') && !str_contains($__logoUrl, 'https://:/');
                        @endphp
                        @if($__logoOk)
                            <img src="{{ $__logoUrl }}" alt="{{ $siteName }} logo">
                        @else
                            <span style="font-size:1.4rem;font-weight:900;color:var(--brand-dark,#0b1220);">{{ mb_strtoupper(mb_substr($siteName, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div>
                        <div class="lh-name">{{ $siteName }}</div>
                        <div class="lh-org">Facility Reservation Office &nbsp;·&nbsp; {{ $siteName }} — University</div>
                    </div>
                </div>
            </div>
            <div class="lh-title-block">
                <div class="lh-doc-type"><i class="bi bi-receipt"></i> Official Document</div>
                <div class="lh-title">Cash Payment Slip</div>
                <div class="lh-sub">Present this slip at the Cashier's Office</div>
            </div>
        </div>
    </div>
    <div class="perforation"></div>

    {{-- Ticket reference strip ────────────────────────────── --}}
    <div class="ticket-strip">
        <div class="ticket-ref">
            <div>
                <div class="ticket-ref-lbl">Booking<br>Reference</div>
            </div>
            <div class="ticket-ref-no">#{{ $reservation->booking_reference }}</div>
        </div>
        <div class="ticket-meta">
            <div class="tm-item"><strong>Issued:</strong> {{ now()->format('F j, Y') }}</div>
            <div class="tm-item"><strong>Time:</strong> {{ now()->format('g:i A') }}</div>
        </div>
    </div>

    {{-- Status ────────────────────────────────────────────── --}}
    <div class="status-row">
        @if($isPaid)
            <span class="chip paid"><span class="chip-dot"></span> Payment Received</span>
        @elseif($reservation->payment_status === 'partially_paid')
            <span class="chip partial"><span class="chip-dot"></span> Partial Payment — Balance Due</span>
        @else
            <span class="chip due"><span class="chip-dot"></span> Payment Due</span>
        @endif
        <span style="font-size:.72rem;font-weight:600;color:var(--muted);margin-left:auto;">
            Ref. {{ $reservation->facility->name ?? 'Facility' }}
        </span>
    </div>

    {{-- Guest & Booking ───────────────────────────────────── --}}
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-person-lines-fill"></i>
            <span class="sec-title">Guest &amp; Booking Details</span>
            <span class="sec-kicker"></span>
        </div>
        <div class="grid">
            <div class="field">
                <span class="field-lbl">Contact Person</span>
                <span class="field-val">{{ $reservation->reserved_by_name ?? $guestName }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Client Type</span>
                <span class="field-val">{{ $reservation->client_type_label }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Event Title</span>
                <span class="field-val">{{ $reservation->event_title ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Facility Reserved</span>
                <span class="field-val">{{ $reservation->facility->name }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Event Date(s)</span>
                <span class="field-val">
                    {{ $fmtDate($reservation->start_date, 'F d, Y') }}
                    @if($reservation->start_date != $reservation->end_date)
                        &ndash; {{ $fmtDate($reservation->end_date, 'F d, Y') }}
                    @endif
                </span>
            </div>
            <div class="field">
                <span class="field-lbl">Event Time</span>
                <span class="field-val">
                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} &ndash;
                    {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}
                </span>
            </div>
            <div class="field">
                <span class="field-lbl">Expected Attendance</span>
                <span class="field-val">{{ $reservation->number_of_people }} persons</span>
            </div>
            <div class="field">
                <span class="field-lbl">End User / Beneficiary</span>
                <span class="field-val">{{ $reservation->end_user_name ?? 'N/A' }} ({{ ucfirst($reservation->end_user_type ?? 'N/A') }})</span>
            </div>
        </div>
    </div>

    {{-- Logistical Requirements (itemized) ────────────────── --}}
    @if(!empty($slipArrangements) && count($slipArrangements) > 0)
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-truck-flatbed"></i>
            <span class="sec-title">Logistical Requirements</span>
            <span class="sec-kicker"></span>
        </div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="r" style="width:90px;">Qty</th>
                    <th class="r" style="width:130px;">Unit Price</th>
                    <th class="r" style="width:130px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($slipArrangements as $arr)
                <tr>
                    <td class="item-name">{{ $arr['name'] ?? 'Item' }}@if(!empty($arr['unit_label'])) <span style="color:var(--muted);font-weight:500;">&nbsp;&nbsp;·&nbsp;{{ $arr['unit_label'] }}</span>@endif</td>
                    <td class="r"><span class="qty-badge">{{ $arr['quantity'] ?? 1 }}</span></td>
                    <td class="r">@if(($arr['unit_price'] ?? 0) > 0)₱{{ number_format($arr['unit_price'], 2) }}@else—@endif</td>
                    <td class="r">@if(($arr['subtotal'] ?? 0) > 0)₱{{ number_format($arr['subtotal'], 2) }}@else<span class="free-tag">Included</span>@endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Totals panel ──────────────────────────────────────── --}}
    <div class="totals-panel">
        <div class="totals-body">
            <div class="totals-left">
                @if($reservation->payment_status === 'partially_paid')
                    <div class="tot-line">
                        <span class="tot-lbl">Total Price</span>
                        <span class="tot-val">₱{{ number_format($reservation->total_price, 2) }}</span>
                    </div>
                    <div class="tot-line tot-dash">
                        <span class="tot-lbl">Deposit Paid</span>
                        <span class="tot-val" style="color:var(--green);">−₱{{ number_format($reservation->amount_paid, 2) }}</span>
                    </div>
                    <div class="tot-line" style="margin-top:.35rem;">
                        <span class="tot-lbl" style="font-weight:700;color:var(--ink);">Remaining Balance Due</span>
                        <span class="tot-val" style="font-size:1.05rem;">&nbsp;</span>
                    </div>
                @else
                    <div class="tot-line" style="padding:1.1rem 0;">
                        <span class="tot-lbl" style="font-size:.85rem;font-weight:700;color:var(--ink);">Total Amount to Pay at the Cashier</span>
                        <span class="tot-val" style="font-size:1.05rem;">&nbsp;</span>
                    </div>
                @endif
            </div>
            <div class="totals-right">
                <span class="tot-lbl">{{ $reservation->payment_status === 'partially_paid' ? 'Balance Due' : 'Total Due' }}</span>
                <div class="grand">{{ $reservation->payment_status === 'partially_paid' ? '₱' . number_format($reservation->remaining_balance, 2) : '₱' . number_format($reservation->total_price, 2) }}</div>
                @if($isPaid)
                    <span class="chip paid" style="margin-top:.6rem;"><span class="chip-dot"></span> Paid</span>
                @endif
                <span class="watermark">{{ branding('system_name', 'Platform') }}</span>
            </div>
        </div>
    </div>

    {{-- Notice ────────────────────────────────────────────── --}}
    @if($isPaid)
        <div class="notice paid">
            <span class="notice-icon"><i class="bi bi-patch-check-fill"></i></span>
            <div>
                <strong>Payment received — thank you.</strong>
                This slip serves as your official cash receipt. Please retain a copy and present it together with a valid ID on the day of the event.
            </div>
        </div>
    @elseif($reservation->payment_status === 'partially_paid')
        <div class="notice partial">
            <span class="notice-icon"><i class="bi bi-info-circle-fill"></i></span>
            <div>
                <strong>Deposit received — balance due.</strong>
                Please pay the remaining balance at the Cashier's Office <strong>no later than {{ $deadline }}</strong> (7 days before the event date).
                Failure to settle the balance before the deadline will result in <strong>automatic cancellation without refund</strong>.
            </div>
        </div>
    @else
        <div class="notice due">
            <span class="notice-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
            <div>
                <strong>Action required — payment due.</strong>
                Present this slip at the Cashier's Office and settle the amount above <strong>no later than {{ $deadline }}</strong>.
                Failure to pay before this deadline will result in <strong>automatic cancellation</strong> of your reservation and release of the time slot.
            </div>
        </div>
    @endif

    {{-- Verified by / Signatures ──────────────────────────── --}}
    @if(($isPaid || $reservation->payment_status === 'partially_paid') && $reservation->cashierSignedBy)
        @php $cashierUser = $reservation->cashierSignedBy; @endphp
        <div class="verified">
            <div class="verified-head"><i class="bi bi-shield-check"></i> {{ $isPaid ? 'Officially Confirmed by Cashier' : 'Partial Payment Confirmed by Cashier' }}</div>
            <div class="verified-grid">
                <div>
                    <div class="v-lbl">Confirmed By</div>
                    <div class="v-val">{{ $cashierUser->name }} <span class="role">· {{ $cashierUser->roleLabel() }}</span></div>
                </div>
                <div>
                    <div class="v-lbl">Date &amp; Time</div>
                    <div class="v-val">{{ $reservation->cashier_signed_at?->format('F j, Y') }}<br><span style="color:var(--muted);font-weight:600;font-size:.75rem;">{{ $reservation->cashier_signed_at?->format('g:i A') }}</span></div>
                </div>
                <div>
                    <div class="v-lbl">Official Receipt No.</div>
                    <div class="v-val mono">{{ $reservation->cash_receipt_number ?? '—' }}</div>
                </div>
            </div>
            @if($reservation->cash_payment_notes)
                <div class="v-notes">Note: {{ $reservation->cash_payment_notes }}</div>
            @endif
        </div>
    @else
        <div class="signoff">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-cap">Cashier's Signature over Printed Name</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-cap">Official Receipt No. / Date Received</div>
            </div>
        </div>
    @endif

    {{-- Tear-off stub ─────────────────────────────────────── --}}
    <div class="stub-zone">
        <div class="tear-line"><span class="tear-scissors">✂</span> Deposit Stub — Tear Here</div>
        <div class="stub">
            <div>
                <div class="stub-ref-lbl">Booking Reference</div>
                <div class="stub-ref-no">#{{ $reservation->booking_reference }}</div>
            </div>
            <div class="stub-col">
                <div class="stub-col-lbl">Guest</div>
                <div class="stub-col-val">{{ $guestName }}</div>
            </div>
            <div class="stub-col">
                <div class="stub-col-lbl">{{ $reservation->payment_status === 'partially_paid' ? 'Balance' : 'Amount' }}</div>
                <div class="stub-col-val">₱{{ number_format($reservation->payment_status === 'partially_paid' ? $reservation->remaining_balance : $reservation->total_price, 2) }}</div>
            </div>
            <span class="chip {{ $isPaid ? 'paid' : ($reservation->payment_status === 'partially_paid' ? 'partial' : 'due') }}">
                <span class="chip-dot"></span>
                @if($isPaid) Paid @elseif($reservation->payment_status === 'partially_paid') Partial @else Due @endif
            </span>
        </div>
    </div>

    {{-- Footer ────────────────────────────────────────────── --}}
    <div class="doc-footer">
        <span>{{ $siteName }} &nbsp;·&nbsp; Facility Reservation Office</span>
        <span class="mono">GEN: {{ now()->format('Ymd-His') }}</span>
    </div>
</div>

@else
{{-- ══════════════════════════════════════════════════════════════
     DIGITAL PAYMENT CONFIRMATION RECEIPT — redesigned
══════════════════════════════════════════════════════════════ --}}
<div class="document">

    {{-- Letterhead ──────────────────────────────────────── --}}
    <div class="letterhead">
        <div class="lh-row">
            <div>
                <div class="lh-brand">
                    <div class="lh-logo">
                        @php
                            $__logoUrl = function_exists('brand_logo') ? brand_logo() : '';
                            $__logoOk = $__logoUrl && str_starts_with($__logoUrl, 'http') && !str_contains($__logoUrl, 'http://:/') && !str_contains($__logoUrl, 'https://:/');
                        @endphp
                        @if($__logoOk)
                            <img src="{{ $__logoUrl }}" alt="{{ $siteName }} logo">
                        @else
                            <span style="font-size:1.4rem;font-weight:900;color:var(--brand-dark,#0b1220);">{{ mb_strtoupper(mb_substr($siteName, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div>
                        <div class="lh-name">{{ $siteName }}</div>
                        <div class="lh-org">Facility Reservation Office &nbsp;·&nbsp; Electronic Confirmation</div>
                    </div>
                </div>
            </div>
            <div class="lh-title-block">
                <div class="lh-doc-type"><i class="bi bi-credit-card-2-front"></i> Online Payment</div>
                <div class="lh-title">{{ $isPaid ? 'Payment Receipt' : 'Payment Pending' }}</div>
                <div class="lh-sub">Processed via PayMongo</div>
            </div>
        </div>
    </div>
    <div class="perforation"></div>

    {{-- Ticket reference strip ────────────────────────────── --}}
    <div class="ticket-strip">
        <div class="ticket-ref">
            <div>
                <div class="ticket-ref-lbl">Booking<br>Reference</div>
            </div>
            <div class="ticket-ref-no">#{{ $reservation->booking_reference }}</div>
        </div>
        <div class="ticket-meta">
            <div class="tm-item">
                <strong style="color:{{ $isPaid ? 'var(--green)' : 'var(--amber)' }};">
                    {{ $isPaid ? '● CONFIRMED PAID' : '● PENDING PAYMENT' }}
                </strong>
            </div>
            <div class="tm-item">{{ $isPaid ? now()->format('F j, Y \a\t g:i A') : 'Deadline: ' . $deadline }}</div>
        </div>
    </div>

    {{-- Status ────────────────────────────────────────────── --}}
    <div class="status-row">
        @if($isPaid)
            <span class="chip paid"><span class="chip-dot"></span> Payment Confirmed</span>
        @else
            <span class="chip due"><span class="chip-dot"></span> Awaiting Payment</span>
        @endif
        <span style="font-size:.72rem;font-weight:600;color:var(--muted);margin-left:auto;">
            Reservation status: {{ ucfirst($reservation->status) }}
        </span>
    </div>

    {{-- Guest & Booking ───────────────────────────────────── --}}
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-person-lines-fill"></i>
            <span class="sec-title">Guest &amp; Booking Details</span>
            <span class="sec-kicker"></span>
        </div>
        <div class="grid">
            <div class="field">
                <span class="field-lbl">Contact Person</span>
                <span class="field-val">{{ $reservation->reserved_by_name ?? $guestName }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Email Address</span>
                <span class="field-val">{{ $reservation->reserved_by_email ?? $reservation->user->email }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Client Type</span>
                <span class="field-val">{{ $reservation->client_type_label }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Event Title</span>
                <span class="field-val">{{ $reservation->event_title ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Facility Reserved</span>
                <span class="field-val">{{ $reservation->facility->name }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Event Date(s)</span>
                <span class="field-val">
                    {{ $fmtDate($reservation->start_date, 'F d, Y') }}
                    @if($reservation->start_date != $reservation->end_date)
                        &ndash; {{ $fmtDate($reservation->end_date, 'F d, Y') }}
                    @endif
                </span>
            </div>
            <div class="field">
                <span class="field-lbl">Event Time</span>
                <span class="field-val">
                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} &ndash;
                    {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}
                </span>
            </div>
            <div class="field">
                <span class="field-lbl">Expected Attendance</span>
                <span class="field-val">{{ $reservation->number_of_people }} persons</span>
            </div>
            <div class="field">
                <span class="field-lbl">End User / Beneficiary</span>
                <span class="field-val">{{ $reservation->end_user_name ?? 'N/A' }} ({{ ucfirst($reservation->end_user_type ?? 'N/A') }})</span>
            </div>
        </div>
    </div>

    {{-- Logistical Requirements (itemized) ────────────────── --}}
    @if(!empty($slipArrangements) && count($slipArrangements) > 0)
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-truck-flatbed"></i>
            <span class="sec-title">Logistical Requirements</span>
            <span class="sec-kicker"></span>
        </div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="r" style="width:90px;">Qty</th>
                    <th class="r" style="width:130px;">Unit Price</th>
                    <th class="r" style="width:130px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($slipArrangements as $arr)
                <tr>
                    <td class="item-name">{{ $arr['name'] ?? 'Item' }}@if(!empty($arr['unit_label'])) <span style="color:var(--muted);font-weight:500;">&nbsp;&nbsp;·&nbsp;{{ $arr['unit_label'] }}</span>@endif</td>
                    <td class="r"><span class="qty-badge">{{ $arr['quantity'] ?? 1 }}</span></td>
                    <td class="r">@if(($arr['unit_price'] ?? 0) > 0)₱{{ number_format($arr['unit_price'], 2) }}@else—@endif</td>
                    <td class="r">@if(($arr['subtotal'] ?? 0) > 0)₱{{ number_format($arr['subtotal'], 2) }}@else<span class="free-tag">Included</span>@endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Totals panel ──────────────────────────────────────── --}}
    <div class="totals-panel">
        <div class="totals-body">
            <div class="totals-left">
                <div class="tot-line" style="padding:1.1rem 0;">
                    <span class="tot-lbl" style="font-size:.85rem;font-weight:700;color:var(--ink);">{{ $isPaid ? 'Amount Paid' : 'Amount Due' }}</span>
                    <span class="tot-val" style="font-size:1.05rem;">&nbsp;</span>
                </div>
            </div>
            <div class="totals-right">
                <span class="tot-lbl">{{ $isPaid ? 'Amount Paid' : 'Total Due' }}</span>
                <div class="grand">₱{{ number_format($reservation->total_price, 2) }}</div>
                @if($isPaid)
                    <span class="chip paid" style="margin-top:.6rem;"><span class="chip-dot"></span> Captured</span>
                @endif
                <span class="watermark">{{ branding('system_name', 'Platform') }}</span>
            </div>
        </div>
    </div>

    {{-- Transaction details ───────────────────────────────── --}}
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-clock-history"></i>
            <span class="sec-title">Transaction &amp; Payment Details</span>
            <span class="sec-kicker"></span>
        </div>
        <div class="tx-box">
            <div class="tx-row">
                <span class="tx-k">Payment Channel</span>
                <span class="tx-v">PayMongo (Online)</span>
            </div>
            <div class="tx-row">
                <span class="tx-k">Reservation Status</span>
                <span class="tx-v">{{ ucfirst($reservation->status) }}</span>
            </div>
            <div class="tx-row">
                <span class="tx-k">Reservation Submitted</span>
                <span class="tx-v">{{ $reservation->created_at->format('F j, Y \a\t g:i A') }}</span>
            </div>
            <div class="tx-row">
                <span class="tx-k">Payment Deadline</span>
                <span class="tx-v">{{ $reservation->payment_deadline ? ($reservation->payment_deadline->year > 2099 ? 'N/A' : $reservation->payment_deadline->format('F j, Y \a\t g:i A')) : 'N/A' }}</span>
            </div>
            @if($reservation->payment_id)
            <div class="tx-row">
                <span class="tx-k">PayMongo Session ID</span>
                <span class="tx-v mono">{{ $reservation->payment_id }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Notice ────────────────────────────────────────────── --}}
    @if($isPaid)
        <div class="notice paid">
            <span class="notice-icon"><i class="bi bi-patch-check-fill"></i></span>
            <div>
                <strong>Payment successfully captured — thank you!</strong>
                Your payment of ₱{{ number_format($reservation->total_price, 2) }} has been verified by {{ $siteName }}.
                This digital receipt is your official proof of payment. Your reservation is now subject to review and approval by the facility office.
                Please present this document (or the transaction reference) together with a valid ID on the day of the event.
            </div>
        </div>
    @else
        <div class="notice due">
            <span class="notice-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
            <div>
                <strong>Payment not yet confirmed.</strong>
                Your online payment session was initiated through PayMongo but has not been completed.
                To secure your booking, please complete the payment <strong>before {{ $deadline }}</strong> by returning to your reservation history and selecting
                "Pay Now". This document will serve as your receipt once payment is confirmed.
            </div>
        </div>
    @endif

    {{-- Tear-off stub ─────────────────────────────────────── --}}
    <div class="stub-zone">
        <div class="tear-line"><span class="tear-scissors">✂</span> Record Stub — Tear Here</div>
        <div class="stub">
            <div>
                <div class="stub-ref-lbl">Booking Reference</div>
                <div class="stub-ref-no">#{{ $reservation->booking_reference }}</div>
            </div>
            <div class="stub-col">
                <div class="stub-col-lbl">Guest</div>
                <div class="stub-col-val">{{ $guestName }}</div>
            </div>
            <div class="stub-col">
                <div class="stub-col-lbl">Channel</div>
                <div class="stub-col-val">PayMongo</div>
            </div>
            <span class="chip {{ $isPaid ? 'paid' : 'due' }}">
                <span class="chip-dot"></span>
                {{ $isPaid ? 'Paid Online' : 'Pending' }}
            </span>
        </div>
    </div>

    {{-- Footer ────────────────────────────────────────────── --}}
    <div class="doc-footer">
        <span>{{ $siteName }} &nbsp;·&nbsp; Facility Reservation Office</span>
        <span class="mono">GEN: {{ now()->format('Ymd-His') }}</span>
    </div>
</div>

@endif

</body>
</html>
