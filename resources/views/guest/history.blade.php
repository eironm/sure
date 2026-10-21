@extends('layouts.app')

@section('content')
    @include('components.custom-alert')
    <style>
        /* Local history page variables — does NOT override global brand CSS vars */
        .history-container {
            --hist-dark: var(--brand-dark, #0f172a);
            --hist-accent: var(--brand-primary, #2563eb);
            --surface-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: var(--brand-dark, #0f172a);
            --text-muted: #64748b;
            --border-soft: #e2e8f0;
            --border-strong: #cbd5e1;
            --success: #10b981;
            --success-bg: #ecfdf5;
            --warning: #f59e0b;
            --warning-bg: #fffbeb;
            --danger: #ef4444;
            --danger-bg: #fef2f2;
            --info: #3b82f6;
            --info-bg: #eff6ff;
        }

        body {
            background-color: var(--surface-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .history-container {
            max-width: 980px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        /* ── Page Header ── */
        .page-header {
            margin-bottom: 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.04em;
            margin-bottom: 0.4rem;
        }

        .page-subtitle {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 400;
            line-height: 1.5;
            max-width: 560px;
        }

        /* ── Filter Pills ── */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.75rem;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.95rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            background: var(--card-bg);
            border: 1px solid var(--border-soft);
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .filter-pill:hover {
            border-color: var(--hist-accent);
            color: var(--hist-accent);
        }

        .filter-pill.active {
            background: var(--hist-accent);
            color: #ffffff;
            border-color: var(--hist-accent);
        }

        .filter-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.25rem;
            height: 1.25rem;
            padding: 0 0.3rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .filter-pill .filter-count {
            background: rgba(15, 23, 42, 0.08);
            color: var(--text-muted);
        }

        .filter-pill.active .filter-count {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* ── Reservation Card ── */
        .res-card {
            background: var(--card-bg);
            border-radius: 14px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03), 0 4px 8px -4px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            margin-bottom: 1.25rem;
        }

        .res-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -6px rgba(0, 0, 0, 0.08);
            border-color: var(--border-strong);
        }

        .res-card-inner {
            display: flex;
            flex-direction: column;
        }

        /* ── Header Row ── */
        .res-header {
            padding: 1.25rem 1.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .res-title-block {
            min-width: 0;
        }

        .res-event-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.3rem;
            line-height: 1.3;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .res-facility-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .res-meta {
            margin-top: 0.5rem;
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, monospace;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--hist-accent);
            letter-spacing: 0.05em;
        }

        .badge-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            justify-content: flex-end;
            flex-shrink: 0;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        /* Booking Status */
        .status-pending { background: var(--warning-bg); color: #92400e; border-color: #fde68a; }
        .status-approved { background: var(--success-bg); color: #065f46; border-color: #a7f3d0; }
        .status-denied { background: var(--danger-bg); color: #991b1b; border-color: #fecaca; }
        .status-cancelled { background: var(--surface-bg); color: var(--text-muted); border-color: var(--border-strong); }
        .status-expired_unpaid_cancelled { background: #1c0a0a; color: #fca5a5; border-color: #7f1d1d; }

        /* Payment Status Supplements */
        .paym-paid { background: var(--success-bg); color: var(--success); border-color: #a7f3d0; }
        .paym-pending { background: var(--warning-bg); color: var(--warning); border-color: #fde68a; }
        .paym-cash_pending { background: var(--warning-bg); color: var(--warning); border-color: #fde68a; }
        .paym-partially_paid { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
        .paym-failed { background: var(--danger-bg); color: var(--danger); border-color: #fecaca; }
        .paym-expired { background: var(--danger-bg); color: var(--danger); border-color: #fecaca; }

        /* ── Info Grid ── */
        .res-body {
            padding: 0 1.5rem 1.25rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1.25rem 1.5rem;
        }

        .data-point {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .data-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .data-value {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-main);
            line-height: 1.45;
            word-break: break-word;
        }

        .data-value.price {
            font-weight: 700;
            color: var(--hist-accent);
            font-size: 1rem;
        }

        .purpose-block {
            margin: 0 1.5rem 1.25rem;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            background: var(--surface-bg);
            border: 1px dashed var(--border-soft);
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.5;
            word-break: break-word;
        }

        .purpose-block strong {
            color: var(--text-main);
        }

        /* ── Footer: alerts + actions ── */
        .res-footer {
            border-top: 1px solid var(--border-soft);
            background: var(--surface-bg);
        }

        .payment-alert {
            padding: 0.9rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border-soft);
        }

        .payment-alert.cash { background: var(--warning-bg); color: #92400e; }
        .payment-alert.digital { background: var(--info-bg); color: #1e40af; }
        .payment-alert.paid { background: var(--success-bg); color: #065f46; }
        .payment-alert.expired { background: var(--danger-bg); color: #991b1b; }

        .action-bar {
            padding: 0.9rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .action-bar form {
            display: inline-flex;
            margin: 0;
        }

        /* ── Buttons ── */
        .btn-sleek {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.5rem 0.95rem;
            border-radius: 8px;
            font-size: 0.825rem;
            font-weight: 600;
            transition: all 0.15s ease;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .btn-sleek-primary {
            background: var(--hist-accent);
            color: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .btn-sleek-primary:hover {
            background: #1d4ed8;
            color: #ffffff;
            opacity: 0.95;
        }

        .btn-sleek-secondary {
            background: #ffffff;
            color: var(--text-main);
            border-color: var(--border-strong);
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .btn-sleek-secondary:hover {
            background: var(--surface-bg);
            border-color: #94a3b8;
        }

        .btn-sleek-danger {
            background: #ffffff;
            color: var(--danger);
            border-color: #fca5a5;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .btn-sleek-danger:hover {
            background: var(--danger-bg);
            border-color: #ef4444;
        }

        .action-sep {
            color: var(--border-strong);
            margin: 0 0.15rem;
        }

        /* ── Extras ── */
        .details-panel {
            padding: 0 1.5rem 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .detail-item {
            background: var(--surface-bg);
            padding: 0.85rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--border-soft);
        }

        .detail-item.admin {
            background: #faf5ff;
            border-color: #e9d5ff;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px dashed var(--border-strong);
        }

        @media (max-width: 640px) {
            .history-container { padding: 1.5rem 1rem; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .res-header { flex-direction: column; gap: 0.75rem; }
            .badge-group { justify-content: flex-start; }
            .action-bar { flex-direction: column; align-items: stretch; }
            .btn-sleek { width: 100%; }
        }
    </style>

    <div class="history-container">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Reservation History</h1>
                <p class="page-subtitle">Every booking you have made, with quick access to slips, rental agreements, and payments.</p>
            </div>
            <a href="{{ route('guest.reserve') }}" class="btn-sleek btn-sleek-primary" style="padding:0.6rem 1.2rem;">
                <i class="bi bi-plus-lg"></i> New Reservation
            </a>
        </div>

        {{-- Filter Pills --}}
        <div class="filter-bar">
            <a href="{{ route('guest.history') }}" class="filter-pill {{ empty($filter) ? 'active' : '' }}">
                All <span class="filter-count">{{ $totalCount ?? $reservations->total() }}</span>
            </a>
            <a href="{{ route('guest.history', ['status' => 'pending']) }}" class="filter-pill {{ ($filter ?? '') === 'pending' ? 'active' : '' }}">
                <i class="bi bi-hourglass"></i> Pending Review
            </a>
            <a href="{{ route('guest.history', ['status' => 'approved']) }}" class="filter-pill {{ ($filter ?? '') === 'approved' ? 'active' : '' }}">
                <i class="bi bi-check2-circle"></i> Approved
            </a>
            <a href="{{ route('guest.history', ['status' => 'paid']) }}" class="filter-pill {{ ($filter ?? '') === 'paid' ? 'active' : '' }}">
                <i class="bi bi-check-circle-fill"></i> Paid
            </a>
            <a href="{{ route('guest.history', ['status' => 'partially_paid']) }}" class="filter-pill {{ ($filter ?? '') === 'partially_paid' ? 'active' : '' }}">
                <i class="bi bi-hourglass-split"></i> Partially Paid
            </a>
            <a href="{{ route('guest.history', ['status' => 'cash_pending']) }}" class="filter-pill {{ ($filter ?? '') === 'cash_pending' ? 'active' : '' }}">
                <i class="bi bi-cash"></i> Cash Pending
            </a>
            <a href="{{ route('guest.history', ['status' => 'denied']) }}" class="filter-pill {{ ($filter ?? '') === 'denied' ? 'active' : '' }}">
                <i class="bi bi-x-circle"></i> Denied
            </a>
            <a href="{{ route('guest.history', ['status' => 'cancelled']) }}" class="filter-pill {{ ($filter ?? '') === 'cancelled' ? 'active' : '' }}">
                <i class="bi bi-dash-circle"></i> Cancelled
            </a>
            <a href="{{ route('guest.history', ['status' => 'expired_unpaid_cancelled']) }}" class="filter-pill {{ ($filter ?? '') === 'expired_unpaid_cancelled' ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Expired
            </a>
        </div>

        {{-- History List --}}
        <div class="history-list">
            @forelse($reservations as $reservation)
                @php
                    $isPaid        = $reservation->payment_status === 'paid';
                    $isCashPending = $reservation->payment_status === 'cash_pending';
                    $isDigitalPend = $reservation->payment_status === 'pending';
                    $isPartialPaid = $reservation->payment_status === 'partially_paid';
                    $isCash        = $reservation->payment_method === 'cash';
                    $hasDeadline   = $reservation->payment_deadline && $reservation->payment_deadline->isFuture();
                    $needsAction   = ($isCashPending || $isDigitalPend || $isPartialPaid) && $hasDeadline && $reservation->status !== 'cancelled';
                    $isExpired     = $reservation->payment_deadline && $reservation->payment_deadline->isPast() && !$isPaid && $reservation->status !== 'cancelled' && $reservation->status !== 'denied';
                    $isApproved    = $reservation->status === 'approved';

                    // Clean junk dates (e.g. placeholder "Oct 12, 9999") from sample data
                    $endDateYear = \Carbon\Carbon::parse($reservation->end_date)->year;
                    $endDateDisplay = $endDateYear > 2099
                        ? 'Open-ended'
                        : \Carbon\Carbon::parse($reservation->end_date)->format('M d, Y');
                    $startDateDisplay = \Carbon\Carbon::parse($reservation->start_date)->year > 2099
                        ? 'Open-ended'
                        : \Carbon\Carbon::parse($reservation->start_date)->format('M d, Y');
                @endphp

                <div class="res-card">
                    <div class="res-card-inner">
                        {{-- ── Header ── --}}
                        <div class="res-header">
                            <div class="res-title-block">
                                <h2 class="res-event-title">{{ $reservation->event_title ?: $reservation->facility->name }}</h2>
                                <div class="res-facility-name"><i class="bi bi-geo-alt-fill"></i> {{ $reservation->facility->name }}</div>
                                <div class="res-meta">REF: #{{ $reservation->booking_reference ?? strtoupper(substr(md5($reservation->id), 0, 8)) }}</div>
                            </div>
                            @php
                                $isExpiredCancelled = $reservation->status === 'expired_unpaid_cancelled';
                                $isPaymentExpired   = $reservation->payment_status === 'expired';
                            @endphp
                            <div class="badge-group">
                                <span class="status-pill status-{{ $reservation->status }}">
                                    @if($reservation->status === 'approved') <i class="bi bi-check2"></i> Approved
                                    @elseif($reservation->status === 'denied') <i class="bi bi-x"></i> Denied
                                    @elseif($reservation->status === 'cancelled') <i class="bi bi-dash"></i> Cancelled
                                    @elseif($isExpiredCancelled) <i class="bi bi-clock-history"></i> Expired &amp; Cancelled
                                    @else <i class="bi bi-hourglass"></i> {{ ucfirst($reservation->status) }}
                                    @endif
                                </span>
                                {{-- Only show a separate payment badge when it adds new info --}}
                                @if($reservation->payment_status && !$isExpiredCancelled)
                                    <span class="status-pill paym-{{ $reservation->payment_status }}">
                                        @if($isPaid) <i class="bi bi-check-circle-fill"></i> Paid
                                        @elseif($isCashPending) <i class="bi bi-cash"></i> Cash Pending
                                        @elseif($isDigitalPend) <i class="bi bi-credit-card"></i> Pay Pending
                                        @elseif($isPartialPaid) <i class="bi bi-hourglass-split"></i> Partially Paid
                                        @elseif($isPaymentExpired) <i class="bi bi-x-circle-fill"></i> Unpaid &amp; Expired
                                        @elseif($reservation->payment_status === 'failed') <i class="bi bi-exclamation-triangle"></i> Failed
                                        @else {{ ucfirst($reservation->payment_status) }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- ── Info Grid ── --}}
                        <div class="res-body">
                            <div class="data-point">
                                <div class="data-label"><i class="bi bi-calendar-event"></i> Schedule</div>
                                <div class="data-value">
                                    {{ $startDateDisplay }}{{ $endDateDisplay !== $startDateDisplay ? " — $endDateDisplay" : '' }}
                                    <br>
                                    <span style="font-size:0.8em;color:var(--text-muted)">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}
                                    </span>
                                </div>
                            </div>
                            <div class="data-point">
                                <div class="data-label"><i class="bi bi-people"></i> Guests</div>
                                <div class="data-value">{{ $reservation->number_of_people ?? '—' }} Attendees</div>
                            </div>
                            <div class="data-point">
                                <div class="data-label"><i class="bi bi-wallet2"></i> Total Due</div>
                                <div class="data-value price">₱{{ number_format($reservation->total_price, 2) }}</div>
                            </div>
                            <div class="data-point">
                                <div class="data-label"><i class="bi bi-tags"></i> Payment Route</div>
                                <div class="data-value">
                                    @if($reservation->payment_method === 'cash') Cash / OTC
                                    @elseif($reservation->payment_method === 'paymongo') Digital (PayMongo)
                                    @elseif($reservation->payment_method === 'waived') Fees Waived
                                    @else —
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Purpose / Remarks --}}
                        @if(!empty($reservation->reason) || !empty($reservation->admin_remarks))
                            <div class="purpose-block" style="overflow-wrap: anywhere; word-break: break-word;">
                                @if(!empty($reservation->reason))
                                    <strong>Purpose:</strong> {{ $reservation->reason }}
                                    @if(!empty($reservation->admin_remarks)) &nbsp;·&nbsp; @endif
                                @endif
                                @if(!empty($reservation->admin_remarks))
                                    <strong>Admin Remarks:</strong> {{ $reservation->admin_remarks }}
                                @endif
                            </div>
                        @endif

                        {{-- Attached Documents --}}
                        @if($reservation->documents && $reservation->documents->isNotEmpty())
                            <div style="margin: 0 1.5rem 1.25rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                                <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);"><i class="bi bi-file-earmark-arrow-up"></i> Documents:</span>
                                @foreach($reservation->documents as $doc)
                                    <a href="{{ route('guest.reservation.documents.view', [$reservation, $doc]) }}" target="_blank" class="btn-sleek btn-sleek-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.6rem; text-decoration: none;" title="{{ $doc->original_name }} ({{ $doc->formatted_size }})">
                                        <i class="bi {{ $doc->icon_class }} me-1"></i> {{ \Illuminate\Support\Str::limit($doc->original_name, 24) }} <span style="opacity: 0.7; font-size: 0.7rem;">({{ $doc->formatted_size }})</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Footer: alerts + actions --}}
                        <div class="res-footer">
                            {{-- Alert Strip --}}
                            @if($hasDeadline && $reservation->status !== 'cancelled')
                                <div class="payment-alert {{ $isPaid ? 'paid' : ($isPartialPaid ? 'digital' : ($isCash ? 'cash' : 'digital')) }}">
                                    @if($isPaid)
                                        <i class="bi bi-check-circle-fill"></i>
                                        <strong>Payment cleared.</strong> Your booking is secured.
                                    @elseif($isPartialPaid)
                                        <i class="bi bi-info-circle-fill"></i>
                                        <span>Deposit of <strong>₱{{ number_format($reservation->amount_paid, 2) }}</strong> received. Remaining balance of <strong>₱{{ number_format($reservation->remaining_balance, 2) }}</strong> must be paid at the cashier by <strong style="font-weight:700;">{{ $reservation->deadlineForHumans() }}</strong>. Otherwise, your booking will be cancelled <strong>without refund</strong>.</span>
                                    @elseif($isCashPending)
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        <span>Over-the-counter payment required by <strong style="font-weight:700;">{{ $reservation->deadlineForHumans() }}</strong>.</span>
                                    @elseif($isDigitalPend)
                                        <i class="bi bi-clock-fill"></i>
                                        <span>Online checkout pending. Required by <strong style="font-weight:700;">{{ $reservation->deadlineForHumans() }}</strong>.</span>
                                    @endif
                                </div>
                            @elseif($isExpired)
                                <div class="payment-alert expired">
                                    <i class="bi bi-x-octagon-fill"></i>
                                    @if($reservation->amount_paid > 0)
                                        <span>Remaining balance deadline of <strong style="font-weight:700;">{{ $reservation->payment_deadline->format('M d, Y \a\t g:i A') }}</strong> was missed. Booking cancelled <strong>without refund</strong>.</span>
                                    @else
                                        <span>Payment window expired on <strong style="font-weight:700;">{{ $reservation->payment_deadline->format('M d, Y \a\t g:i A') }}</strong>.</span>
                                    @endif
                                </div>
                            @endif

                            {{-- Action Buttons (always show slip access; contextual actions beside it) --}}
                            <div class="action-bar">
                                <a href="{{ route('guest.reservation.slip', $reservation) }}" target="_blank" class="btn-sleek btn-sleek-primary">
                                    <i class="bi bi-receipt"></i> View Slip
                                </a>
                                <a href="{{ route('guest.reservation.rental-agreement', $reservation) }}" target="_blank" class="btn-sleek btn-sleek-secondary">
                                    <i class="bi bi-file-earmark-text"></i> Rental Agreement
                                </a>

                                @if($needsAction || $reservation->status === 'pending')
                                    <span class="action-sep">|</span>

                                    @if($isDigitalPend)
                                        <form method="POST" action="{{ route('guest.reservation.resume-payment', $reservation) }}">
                                            @csrf
                                            <button type="submit" class="btn-sleek btn-sleek-primary">
                                                <i class="bi bi-credit-card"></i> Pay Now
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('guest.reservation.switch-cash', $reservation) }}">
                                            @csrf
                                            <button type="button" class="btn-sleek btn-sleek-secondary" onclick="confirmSwitchCash(event, this)">
                                                <i class="bi bi-cash"></i> Switch to Cash
                                            </button>
                                        </form>
                                    @endif

                                    @if($reservation->status !== 'cancelled' && $reservation->status !== 'denied' && $reservation->status !== 'expired_unpaid_cancelled')
                                        <form method="POST" action="{{ route('guest.reservation.cancel-payment', $reservation) }}"
                                              data-ref="{{ $reservation->booking_reference }}"
                                              data-facility="{{ $reservation->facility->name ?? 'Facility Reservation' }}"
                                              data-partial="{{ $isPartialPaid ? '1' : '0' }}">
                                            @csrf
                                            <button type="button" class="btn-sleek btn-sleek-danger" data-cancel-booking="true"
                                                    data-ref="{{ $reservation->booking_reference }}"
                                                    data-facility="{{ $reservation->facility->name ?? 'Facility Reservation' }}"
                                                    data-partial="{{ $isPartialPaid ? '1' : '0' }}">
                                                <i class="bi bi-x-lg"></i> Cancel Booking
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="empty-state">
                    <i class="bi bi-calendar2-range" style="font-size:3rem;color:var(--border-strong);display:block;margin-bottom:1rem;"></i>
                    <h3 style="font-size:1.25rem;font-weight:700;color:var(--text-main);margin-bottom:0.5rem;">No History Available</h3>
                    <p style="color:var(--text-muted);font-size:0.95rem;max-width:300px;margin:0 auto 1.5rem;">
                        @if(!empty($filter))
                            You have no reservations matching this filter. Try a different category or view everything.
                        @else
                            You haven't made any facility reservations yet. Start by exploring our available locations.
                        @endif
                    </p>
                    @if(!empty($filter))
                        <a href="{{ route('guest.history') }}" class="btn-sleek btn-sleek-secondary" style="padding:0.75rem 1.5rem;">
                            View All Reservations
                        </a>
                    @else
                        <a href="{{ route('guest.reserve') }}" class="btn-sleek btn-sleek-primary" style="padding:0.75rem 1.5rem;">
                            Create First Reservation
                        </a>
                    @endif
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($reservations->hasPages())
                <div style="display:flex;justify-content:center;margin-top:1rem;">
                    {{ $reservations->appends(['status' => $filter ?? null])->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function confirmSwitchCash(e, btn) {
            e.preventDefault();
            var form = btn.closest('form');
            if (!form) return;

            if (typeof showAlert === 'function') {
                showAlert({
                    title: 'Switch to Cash Payment?',
                    message: 'Your booking will be updated for over-the-counter cash payment at the campus cashier. You must settle payment before the deadline.',
                    type: 'warning',
                    showCancel: true,
                    confirmText: 'Yes, Switch to Cash',
                    cancelText: 'Keep Current Payment',
                    onConfirm: function () {
                        form.submit();
                    }
                });
            } else {
                form.submit();
            }
        }
    </script>
@endsection
