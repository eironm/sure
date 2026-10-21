<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Agreement — {{ $reservation->booking_reference }}</title>
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
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--ink);
            padding: 2.5rem 1.5rem 4rem;
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }

        /* ── Screen Controls ─────────────────── */
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
        .chip.bind  { background: #eff6ff; color: var(--blue);  border: 1px solid var(--blue-line); }
        .chip.bind .chip-dot { background: var(--blue); }
        .chip.waived { background: var(--green-bg); color: var(--green); border: 1px solid var(--green-line); }
        .chip.waived .chip-dot { background: var(--green); }

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

        /* ── Itemized tables ─────────────── */
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

        /* ── Quotation: totals panel ─────── */
        .quot-panel {
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(11,18,32,.05);
            margin-top: .25rem;
        }
        .quot-body { display: flex; gap: 2rem; }
        .quot-left {
            flex: 1;
            display: flex; flex-direction: column;
        }
        .quot-row {
            display: flex; justify-content: space-between; align-items: baseline; gap: 1rem;
            padding: .8rem 1.5rem; border-bottom: 1px solid var(--line-soft);
            font-size: .875rem;
        }
        .quot-row:last-child { border-bottom: none; }
        .quot-row:nth-child(odd) { background: var(--line-soft); }
        .quot-row .k { font-weight: 600; color: var(--ink-2); }
        .quot-row .v { font-weight: 700; color: var(--ink); }
        .quot-row.waive { background: var(--green-bg); }
        .quot-row.waive .k, .quot-row.waive .v { color: var(--green); font-weight: 700; }
        .quot-right {
            width: 300px; flex-shrink: 0;
            display: flex; flex-direction: column; align-items: flex-end; justify-content: center;
            padding: 1.4rem 1.6rem;
            border-left: 1px solid var(--line);
            background: linear-gradient(180deg, #f8fafc, #ffffff);
            position: relative;
        }
        .quot-right .q-lbl { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); }
        .quot-right .grand {
            font-size: 1.85rem; font-weight: 900; letter-spacing: -0.02em; color: var(--ink); line-height: 1.1;
            margin-top: .15rem;
        }
        .quot-right .q-sub { font-size: .72rem; color: var(--muted); font-weight: 600; margin-top: .3rem; }
        .watermark {
            position: absolute; right: 1rem; bottom: .4rem;
            font-size: 2.6rem; font-weight: 900; letter-spacing: .02em;
            color: rgba(11,18,32,.05); line-height: 1; z-index: 0;
            user-select: none; pointer-events: none;
        }

        /* ── Terms ───────────────────────── */
        .terms-box {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--line-soft);
            padding: .4rem 1.4rem .6rem;
        }
        .terms-list { list-style: none; padding: .4rem 0 0; }
        .terms-list li {
            counter-increment: term-counter;
            padding: .65rem 0;
            font-size: .84rem; line-height: 1.7;
            display: flex; gap: .75rem;
            border-bottom: 1px dashed var(--line);
        }
        .terms-box { counter-reset: term-counter; }
        .terms-list li:last-child { border-bottom: none; }
        .t-num {
            flex-shrink: 0;
            width: 22px; height: 22px; border-radius: 7px;
            background: #fff; border: 1px solid var(--line);
            display: flex; align-items: center; justify-content: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: .7rem; font-weight: 700; color: var(--ink);
            margin-top: 3px;
        }
        .terms-list strong { font-weight: 800; }

        /* ── Signatures ──────────────────── */
        .signoff {
            display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;
            padding: 0 2.75rem;
        }
        .sig-block { }
        .sig-line { height: 2.25rem; border-bottom: 1.5px solid #94a3b8; }
        .sig-cap { font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); margin-top: .4rem; }
        .sig-sub { font-size: .76rem; color: var(--ink-2); font-style: italic; margin-top: .15rem; }
        .sig-date-row {
            display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;
            margin-top: 2.25rem;
        }

        /* ── Footer ──────────────────────── */
        .doc-footer {
            padding: 1.1rem 2.75rem;
            background: var(--line-soft);
            border-top: 1px solid var(--line);
            font-size: .7rem; color: var(--muted);
            text-align: center; line-height: 1.7;
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
    $siteName  = function_exists('branding') ? (branding('system_name') ?? config('app.name')) : config('app.name');
    $brandDark = function_exists('branding') ? (branding('brand_dark', '#0b1220') ?? '#0b1220') : '#0b1220';
    $guestName = trim(($reservation->user->first_name ?? '') . ' ' . ($reservation->user->last_name ?? ''));
    if (!$guestName) $guestName = $reservation->user->name ?? 'N/A';
    $isWaived  = $reservation->is_fee_waived;

    $fmtDate = function ($value, $format) {
        $c = \Carbon\Carbon::parse($value);
        return $c->year > 2099 ? 'Open-ended' : $c->format($format);
    };

    $raArrangements = is_string($reservation->arrangements ?? null)
        ? (json_decode($reservation->arrangements, true) ?: [])
        : ($reservation->arrangements ?? []);

    $qb = is_string($reservation->quotation_breakdown ?? null)
        ? (json_decode($reservation->quotation_breakdown, true) ?: [])
        : ($reservation->quotation_breakdown ?? []);

    $qbHasDeposit = collect($qb)->contains(fn ($item) => stripos((string)($item['label'] ?? ''), 'deposit') !== false);
@endphp

{{-- ── Screen Controls ──────────────────────────────────────── --}}
<div class="screen-controls">
    <a href="{{ route('guest.history') }}" class="sc-back">
        <i class="bi bi-chevron-left"></i> Back to History
    </a>
    <button onclick="window.print()" class="ctrl-btn">
        <i class="bi bi-printer"></i> Print Agreement
    </button>
</div>

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
                        <div class="lh-org">Facility Reservation Office &nbsp;·&nbsp; Legal Document</div>
                    </div>
                </div>
            </div>
            <div class="lh-title-block">
                <div class="lh-doc-type"><i class="bi bi-file-earmark-text"></i> Binding Document</div>
                <div class="lh-title">Facility Rental<br>Agreement</div>
                <div class="lh-sub">Terms governing the venue reservation below</div>
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
            <div class="tm-item"><strong>Date of Agreement:</strong> {{ now()->format('F j, Y') }}</div>
            <div class="tm-item"><strong>Status:</strong> {{ ucfirst($reservation->status) }}</div>
        </div>
    </div>

    {{-- Status ────────────────────────────────────────────── --}}
    <div class="status-row">
        <span class="chip bind"><span class="chip-dot"></span> Binding Upon Signature</span>
        @if($isWaived)
            <span class="chip waived"><span class="chip-dot"></span> Fee Waiver Applied</span>
        @endif
    </div>

    {{-- Party Details ─────────────────────────────────────── --}}
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-people-fill"></i>
            <span class="sec-title">Party Details</span>
            <span class="sec-kicker"></span>
        </div>
        <div class="grid">
            <div class="field">
                <span class="field-lbl">Reserving Party (Contact Person)</span>
                <span class="field-val">{{ $reservation->reserved_by_name ?? $guestName }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Client Type</span>
                <span class="field-val">{{ $reservation->client_type_label }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Contact Email</span>
                <span class="field-val">{{ $reservation->reserved_by_email ?? $reservation->user->email }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Contact Phone</span>
                <span class="field-val">{{ $reservation->reserved_by_phone ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">End User / Beneficiary</span>
                <span class="field-val">{{ $reservation->end_user_name ?? 'N/A' }} ({{ ucfirst($reservation->end_user_type ?? 'N/A') }})</span>
            </div>
            @if($reservation->endorsed_by_name)
                <div class="field">
                    <span class="field-lbl">Endorsed By</span>
                    <span class="field-val">{{ $reservation->endorsed_by_name }} — {{ $reservation->endorsed_by_position }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Event & Venue ─────────────────────────────────────── --}}
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-calendar-event"></i>
            <span class="sec-title">Event &amp; Venue Details</span>
            <span class="sec-kicker"></span>
        </div>
        <div class="grid">
            <div class="field">
                <span class="field-lbl">Event Title</span>
                <span class="field-val">{{ $reservation->event_title ?? 'N/A' }}</span>
            </div>
            <div class="field">
                <span class="field-lbl">Facility / Venue</span>
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
                <span class="field-lbl">Purpose</span>
                <span class="field-val">{{ $reservation->reason }}</span>
            </div>
        </div>
    </div>

    {{-- Logistical Requirements (itemized) ────────────────── --}}
    @if(!empty($raArrangements) && count($raArrangements) > 0)
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-truck-flatbed"></i>
            <span class="sec-title">Logistical Requirements / Arrangements</span>
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
                @foreach($raArrangements as $arr)
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

    {{-- Quotation breakdown ───────────────────────────────── --}}
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-calculator"></i>
            <span class="sec-title">Quotation Breakdown</span>
            <span class="sec-kicker"></span>
        </div>
        <div class="quot-panel">
            <div class="quot-body">
                <div class="quot-left">
                    @if(!empty($qb))
                        @foreach($qb as $item)
                            <div class="quot-row">
                                <span class="k">{{ $item['label'] }}</span>
                                <span class="v">₱{{ number_format($item['amount'], 2) }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="quot-row">
                            <span class="k">Facility Rental Fee</span>
                            <span class="v">₱{{ number_format($reservation->total_price, 2) }}</span>
                        </div>
                    @endif
                    @if($isWaived)
                        <div class="quot-row waive">
                            <span class="k"><i class="bi bi-check-circle-fill"></i>&nbsp; Fee Waiver Applied (Internal User)</span>
                            <span class="v">−₱{{ number_format($reservation->quotation_total, 2) }}</span>
                        </div>
                    @endif
                    @if($reservation->security_deposit > 0 && !$isWaived && !$qbHasDeposit)
                        <div class="quot-row">
                            <span class="k">Security Deposit <span style="font-weight:500;color:var(--muted);">(Refundable)</span></span>
                            <span class="v">₱{{ number_format($reservation->security_deposit, 2) }}</span>
                        </div>
                    @endif
                </div>
                <div class="quot-right">
                    <span class="q-lbl">Total Amount Due</span>
                    <div class="grand">₱{{ number_format($reservation->total_price, 2) }}</div>
                    <span class="q-sub">{{ $isWaived ? 'After fee waiver' : 'Before payment' }}</span>
                    <span class="watermark">{{ branding('system_name', 'Platform') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Terms & Conditions ────────────────────────────────── --}}
    <div class="section">
        <div class="sec-head">
            <i class="bi bi-journal-check"></i>
            <span class="sec-title">Terms &amp; Conditions</span>
            <span class="sec-kicker"></span>
        </div>
        <div class="terms-box">
            <ol class="terms-list">
                <li><span class="t-num">1</span><span>The Reserving Party agrees to use the facility only for the stated purpose and duration.</span></li>
                <li><span class="t-num">2</span><span>The Reserving Party shall be responsible for any damage to the facility, its fixtures, or equipment during the rental period.</span></li>
                @if(!$isWaived && $reservation->security_deposit > 0)
                    <li><span class="t-num">3</span><span>A refundable security deposit of <strong>₱{{ number_format($reservation->security_deposit, 2) }}</strong> is required prior to the event. The deposit will be returned within 7 business days after the event, less any deductions for damages or violations.</span></li>
                @endif
                <li><span class="t-num">4</span><span>Payment must be completed within the specified deadline. Failure to do so will result in automatic cancellation of the reservation.</span></li>
                @if(!empty($cancellationPolicy))
                    <li><span class="t-num">5</span><span><strong>Cancellation Policy:</strong> {{ $cancellationPolicy }}</span></li>
                @endif
                <li><span class="t-num">6</span><span>{{ $siteName }} reserves the right to cancel or reschedule any reservation due to force majeure, institutional priorities, or unforeseen circumstances.</span></li>
                <li><span class="t-num">7</span><span>The Reserving Party shall comply with all institutional rules, safety protocols, and regulations during the event.</span></li>
                <li><span class="t-num">8</span><span>Noise levels and activities must not disturb neighboring facilities or ongoing institutional operations.</span></li>
                <li><span class="t-num">9</span><span>The venue must be returned to its original condition after use. Clean-up is the responsibility of the Reserving Party.</span></li>
            </ol>
        </div>
    </div>

    {{-- Signatures ────────────────────────────────────────── --}}
    <div class="section" style="border-bottom:none;">
        <div class="sec-head">
            <i class="bi bi-pen"></i>
            <span class="sec-title">Signatures</span>
            <span class="sec-kicker"></span>
        </div>
        <div class="signoff">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-cap">Reserving Party</div>
                <div class="sig-sub">{{ $reservation->reserved_by_name ?? $guestName }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-cap">Authorized Representative</div>
                <div class="sig-sub">{{ $siteName }} — Facility Reservation Office</div>
            </div>
        </div>
        <div class="sig-date-row">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-cap">Date</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-cap">Date</div>
            </div>
        </div>
    </div>

    {{-- Footer ────────────────────────────────────────────── --}}
    <div class="doc-footer">
        This agreement becomes binding upon signature by both parties.<br>
        Generated by <strong>{{ $siteName }}</strong> · Booking Ref: <span class="mono">#{{ $reservation->booking_reference }}</span> · {{ now()->format('F j, Y \a\t g:i A') }}
    </div>
</div>

</body>
</html>
