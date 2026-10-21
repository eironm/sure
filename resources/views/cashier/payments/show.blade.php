@extends('layouts.cashier')
@section('title', 'Confirm Payment — ' . $reservation->booking_reference)
@section('heading', 'Confirm Cash Payment')

@section('content')
@include('components.delete-confirmation-modal')
@php
    $isPaid    = $reservation->payment_status === 'paid';
    $cashier   = $reservation->cashierSignedBy;
    $guestName = trim(($reservation->user->first_name ?? '') . ' ' . ($reservation->user->last_name ?? ''));
    if (!$guestName) $guestName = $reservation->user->name ?? 'N/A';
@endphp
<style>
    /* ── Register & Ledger: Confirm Payment ── */
    .detail-grid { display: grid; grid-template-columns: 1.05fr 1fr; gap: 1.5rem; }

    /* Booking slip card */
    .slip-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .slip-head { background: #0f172a; color: #fff; padding: 0.85rem 1.4rem; display: flex; align-items: center; gap: 0.7rem; }
    .slip-head .ref { font-family: 'JetBrains Mono', monospace; font-weight: 800; font-size: 0.88rem; letter-spacing: 0.04em; }
    .slip-head .chip { margin-left: auto; }
    .csh-chip { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.22rem 0.6rem; border-radius: 99px; font-size: 0.66rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; }
    .chip-pending { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .chip-partial { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .chip-paid    { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }

    .det-body { padding: 1.25rem 1.4rem; }
    .det-row { display: flex; justify-content: space-between; align-items: baseline; padding: 0.52rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
    .det-row:last-child { border-bottom: 0; }
    .det-row .l { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; flex-shrink: 0; }
    .det-row .v { font-size: 0.88rem; font-weight: 600; color: #0f172a; text-align: right; word-break: break-word; }
    .det-row .v.mono { font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; font-weight: 700; }

    /* Register tape amount block */
    .slip-tape { background: #0f172a; color: #fff; padding: 0.95rem 1.4rem; }
    .slip-tape-row { display: flex; justify-content: space-between; align-items: baseline; padding: 0.28rem 0; }
    .slip-tape-row .l { font-size: 0.66rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; }
    .slip-tape-row .v { font-family: 'JetBrains Mono', monospace; font-weight: 700; font-size: 0.92rem; font-variant-numeric: tabular-nums; }
    .slip-tape-row .v.collected { color: #34d399; }
    .slip-tape-total { display: flex; justify-content: space-between; align-items: baseline; margin-top: 0.45rem; padding-top: 0.55rem; border-top: 1px dashed rgba(255,255,255,0.25); }
    .slip-tape-total .l { font-size: 0.74rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #e2e8f0; }
    .slip-tape-total .v { font-family: 'JetBrains Mono', monospace; font-weight: 800; font-size: 1.55rem; font-variant-numeric: tabular-nums; color: #34d399; letter-spacing: -0.02em; }

    /* Cashier sign-off */
    .signoff { margin-top: 1rem; background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 12px; padding: 0.95rem 1.25rem; }
    .signoff .so-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #0f766e; margin-bottom: 0.45rem; }
    .signoff .so-name { font-weight: 700; color: #0f172a; }
    .signoff .so-sub { font-size: 0.8rem; color: #475569; }
    .signoff .so-or { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; font-weight: 600; color: #0f7669; margin-top: 0.35rem; }

    /* Confirm card */
    .confirm-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .confirm-head { padding: 0.9rem 1.4rem; border-bottom: 1px solid #e2e8f0; font-weight: 800; font-size: 0.9rem; color: #0f172a; display: flex; align-items: center; gap: 0.55rem; }
    .confirm-head i { color: #0d9488; }
    .confirm-body { padding: 1.25rem 1.4rem; }
    .confirm-form { display: flex; flex-direction: column; gap: 0.9rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; }
    .form-group label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; }
    .form-input { padding: 0.55rem 0.85rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.88rem; color: #334155; background: #fff; transition: border-color 0.15s, box-shadow 0.15s; width: 100%; }
    .form-input:focus { outline: none; border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,0.08); }
    .radio-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 600; color: #334155; cursor: pointer; }

    /* Payment type cards */
    .pt-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; }
    .pt-card { border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 0.7rem 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 0.6rem; transition: border-color .15s, background .15s; }
    .pt-card:has(input:checked) { border-color: #0d9488; background: #f0fdfa; }
    .pt-card input { margin: 0; }
    .pt-card .pt-t { font-size: 0.82rem; font-weight: 700; color: #0f172a; }
    .pt-card .pt-s { font-size: 0.7rem; color: #64748b; }

    .btn-confirm { padding: 0.7rem 1.4rem; border-radius: 10px; font-size: 0.92rem; font-weight: 700; border: none; cursor: pointer; background: #059669; color: #fff; display: flex; align-items: center; gap: 0.5rem; justify-content: center; transition: opacity 0.15s; width: 100%; }
    .btn-confirm:hover { opacity: 0.9; color: #fff; }
    .btn-slip { padding: 0.7rem 1.4rem; border-radius: 10px; font-size: 0.92rem; font-weight: 700; border: none; cursor: pointer; text-decoration: none; background: #0f172a; color: #fff; display: inline-flex; align-items: center; gap: 0.5rem; transition: opacity 0.15s; }
    .btn-slip:hover { opacity: 0.9; color: #fff; }

    .paid-banner { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; padding: 0.95rem 1rem; color: #065f46; font-weight: 600; display: flex; align-items: flex-start; gap: 0.75rem; }
    .paid-banner i { font-size: 1.5rem; flex-shrink: 0; color: #059669; }
    .partial-note { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 0.85rem 1rem; font-size: 0.85rem; color: #1e40af; }
    .caution { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 0.85rem 1rem; font-size: 0.8rem; color: #92400e; }
    .dead-ok { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; padding: 0.8rem 1rem; font-size: 0.85rem; color: #065f46; display: flex; align-items: center; gap: 0.6rem; }
    .back-btn { font-size: 0.85rem; color: #0d9488; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 1.15rem; }
    .back-btn:hover { color: #0f766e; }
    @media(max-width:900px){.detail-grid{grid-template-columns:1fr;}}
</style>

<a href="{{ route('cashier.payments.index') }}" class="back-btn"><i class="bi bi-arrow-left"></i> Back to Queue</a>

<div class="detail-grid">
    {{-- Left: Booking slip --}}
    <div>
        <div class="slip-card">
            <div class="slip-head">
                <i class="bi bi-ticket-perforated-fill"></i>
                <span class="ref">#{{ $reservation->booking_reference }}</span>
                <span class="csh-chip chip-{{ $isPaid ? 'paid' : ($reservation->payment_status === 'partially_paid' ? 'partial' : 'pending') }} chip">
                    @if($isPaid) ✓ PAID
                    @elseif($reservation->payment_status === 'partially_paid') ⏳ PARTIALLY PAID
                    @else CASH PENDING
                    @endif
                </span>
            </div>
            <div class="det-body">
                <div class="det-row"><span class="l">Guest</span><span class="v">{{ $guestName }}</span></div>
                <div class="det-row"><span class="l">Email</span><span class="v" style="font-size:0.82rem;">{{ $reservation->user->email ?? 'N/A' }}</span></div>
                <div class="det-row"><span class="l">Facility</span><span class="v">{{ $reservation->facility->name ?? 'N/A' }}</span></div>
                <div class="det-row"><span class="l">Event Date</span><span class="v">
                    {{ \Carbon\Carbon::parse($reservation->start_date)->format('F d, Y') }}
                    @if($reservation->start_date != $reservation->end_date) – {{ \Carbon\Carbon::parse($reservation->end_date)->format('F d, Y') }} @endif
                </span></div>
                <div class="det-row"><span class="l">Time</span><span class="v">{{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}</span></div>
                <div class="det-row"><span class="l">Attendees</span><span class="v">{{ $reservation->number_of_people }} people</span></div>
                <div class="det-row"><span class="l">Reservation</span><span class="v">{{ ucfirst($reservation->status) }}</span></div>
                <div class="det-row"><span class="l">Payment Deadline</span><span class="v mono" style="{{ $reservation->payment_deadline?->isPast() && !$isPaid ? 'color:#dc2626;' : '' }}">
                    {{ $reservation->deadlineForHumans() }}
                </span></div>
            </div>
            <div class="slip-tape">
                <div class="slip-tape-row"><span class="l">Total Price</span><span class="v">&#8369;{{ number_format($reservation->total_price, 2) }}</span></div>
                @if($reservation->amount_paid > 0)
                    <div class="slip-tape-row"><span class="l">Amount Paid</span><span class="v collected">&#8369;{{ number_format($reservation->amount_paid, 2) }}</span></div>
                @endif
                <div class="slip-tape-total">
                    <span class="l">Remaining Balance</span>
                    <span class="v">&#8369;{{ number_format($reservation->remaining_balance, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Cashier sign-off block (if already paid) --}}
        @if($isPaid && $cashier)
            <div class="signoff">
                <div class="so-label"><i class="bi bi-person-check-fill"></i> Confirmed by Cashier</div>
                <div class="so-name">{{ $cashier->name }}</div>
                <div class="so-sub">{{ $cashier->email }}</div>
                <div class="so-sub">{{ $reservation->cashier_signed_at?->format('F d, Y \a\t g:i A') }}</div>
                @if($reservation->cash_receipt_number)
                    <div class="so-or">OR #{{ $reservation->cash_receipt_number }}</div>
                @endif
                @if($reservation->cash_payment_notes)
                    <div class="so-sub" style="margin-top:0.3rem;">Note: {{ $reservation->cash_payment_notes }}</div>
                @endif
            </div>
        @endif
    </div>

    {{-- Right: Confirmation --}}
    <div>
        @if($isPaid)
            <div class="confirm-card">
                <div class="confirm-head"><i class="bi bi-patch-check-fill" style="color:#059669;"></i> Payment Confirmed</div>
                <div class="confirm-body">
                    <div class="paid-banner">
                        <i class="bi bi-patch-check-fill"></i>
                        <div>
                            <strong>This payment has already been confirmed.</strong><br>
                            <span style="font-weight:400;font-size:0.85rem;">Confirmed by {{ $cashier?->name ?? 'a cashier' }} on {{ $reservation->cashier_signed_at?->format('F d, Y') }}.</span>
                        </div>
                    </div>
                    <div class="dead-ok" style="margin-top:1rem;">
                        <i class="bi bi-printer-fill"></i> Issue the confirmation slip so the guest leaves with a signed record.
                    </div>
                    <div style="margin-top:1.1rem;">
                        <a href="{{ route('cashier.reservation.slip', $reservation) }}" class="btn-slip"><i class="bi bi-printer-fill"></i> Print Confirmation Slip</a>
                    </div>
                </div>
            </div>
        @else
            <div class="confirm-card">
                <div class="confirm-head"><i class="bi bi-cash-stack"></i> Confirm Cash Payment</div>
                <div class="confirm-body">
                    <p style="font-size:0.85rem;color:#64748b;margin:0 0 1.1rem;">
                        @if($reservation->payment_status === 'partially_paid')
                            Verify that you have physically received the remaining balance of <strong>&#8369;{{ number_format($reservation->remaining_balance, 2) }}</strong> from the guest.
                        @else
                            Verify that you have physically received the payment from the guest.
                        @endif
                    </p>
                    <form method="POST" action="{{ route('cashier.payments.confirm', $reservation) }}" class="confirm-form" id="confirm-payment-form">
                        @csrf

                        @if($reservation->payment_status !== 'partially_paid')
                            {{-- Payment type selector --}}
                            <div class="form-group">
                                <label>Payment Type</label>
                                <div class="pt-cards">
                                    <label class="pt-card">
                                        <input type="radio" name="payment_type" value="full" checked onchange="togglePaymentAmountInput(false)">
                                        <div>
                                            <div class="pt-t">Full Payment</div>
                                            <div class="pt-s">&#8369;{{ number_format($reservation->total_price, 2) }}</div>
                                        </div>
                                    </label>
                                    <label class="pt-card">
                                        <input type="radio" name="payment_type" value="partial" {{ old('payment_type') === 'partial' ? 'checked' : '' }} onchange="togglePaymentAmountInput(true)">
                                        <div>
                                            <div class="pt-t">Partial / Deposit</div>
                                            <div class="pt-s">Collect a deposit</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" id="amount-collected-group" style="display: {{ old('payment_type') === 'partial' ? 'block' : 'none' }};">
                                <label>Amount Collected (₱)</label>
                                <input type="number" step="0.01" min="0.01" max="{{ $reservation->total_price }}" name="amount_collected" id="amount_collected" class="form-input"
                                    placeholder="e.g. 500.00" value="{{ old('amount_collected') }}">
                                <small class="text-danger mt-1 d-block" style="font-size:0.72rem;">
                                    <i class="bi bi-info-circle"></i> Once a partial payment is confirmed, the guest must pay the remaining balance <strong>at least 7 days before the event date</strong>, otherwise the booking is cancelled without refund.
                                </small>
                            </div>
                        @else
                            <input type="hidden" name="payment_type" value="full">
                            <div class="partial-note mb-1">
                                <i class="bi bi-info-circle-fill"></i> Guest has already paid a deposit of <strong>&#8369;{{ number_format($reservation->amount_paid, 2) }}</strong>. Collecting remaining balance of <strong>&#8369;{{ number_format($reservation->remaining_balance, 2) }}</strong> to fully pay this reservation.
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Official Receipt Number <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;">(Optional)</span></label>
                            <input type="text" name="cash_receipt_number" class="form-input"
                                placeholder="e.g. OR-2024-00123" value="{{ old('cash_receipt_number') }}">
                        </div>

                        <div class="form-group">
                            <label>Notes <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;">(Optional)</span></label>
                            <textarea name="cash_payment_notes" class="form-input" rows="3"
                                placeholder="e.g. Paid in full, deposit paid, remaining balance paid…" style="height:auto;resize:none;">{{ old('cash_payment_notes') }}</textarea>
                        </div>

                        <div class="caution">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <strong>Important:</strong> Verify receipt of cash before confirmation. If a partial payment is processed, the remaining balance is subject to a strict <strong>7-day pre-event cancellation policy</strong>.
                        </div>

                        <button type="submit" class="btn-confirm" id="confirm-payment-btn">
                            <i class="bi bi-check2-circle"></i> Confirm Payment Received
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function togglePaymentAmountInput(show) {
        const group = document.getElementById('amount-collected-group');
        const input = document.getElementById('amount_collected');
        if (group) {
            group.style.display = show ? 'block' : 'none';
            if (input && show) {
                input.focus();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('confirm-payment-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                const checkedRadio = document.querySelector('input[name="payment_type"]:checked');
                const paymentType = checkedRadio ? checkedRadio.value : 'full';
                let confirmMsg = 'Confirm receipt of full payment?';

                if (paymentType === 'partial') {
                    const amount = document.getElementById('amount_collected').value || '0.00';
                    confirmMsg = `Confirm receipt of deposit amount of ₱${parseFloat(amount).toFixed(2)}? The remaining balance will be due 7 days before the event.`;
                } else {
                    const remaining = "{{ number_format($reservation->remaining_balance, 2) }}";
                    confirmMsg = `Confirm receipt of ₱${remaining} to fully settle the balance?`;
                }

                if (!confirm(confirmMsg)) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endpush
@endsection
