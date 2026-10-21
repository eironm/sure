@extends('layouts.cashier')
@section('title', 'Payment Queue')
@section('heading', 'Payment Queue')

@section('content')
<style>
    /* ── Register tape ── */
    .csh-tape {
        background: #0f172a;
        border-radius: 12px;
        padding: 1.05rem 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        color: #fff;
    }
    .csh-tape-cell { padding: 0.2rem 1.5rem; border-left: 1px solid rgba(255,255,255,0.14); display: flex; flex-direction: column; justify-content: center; }
    .csh-tape-cell:first-child { border-left: 0; padding-left: 0; }
    .csh-tape-cell .v { font-family: 'JetBrains Mono', monospace; font-weight: 800; font-size: 1.5rem; line-height: 1.15; color: #fff; font-variant-numeric: tabular-nums; }
    .csh-tape-cell .v.hero { font-size: 1.85rem; color: #34d399; letter-spacing: -0.02em; }
    .csh-tape-cell .v.urg { color: #f87171; }
    .csh-tape-cell .l { font-size: 0.64rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #94a3b8; margin-top: 0.12rem; }

    /* Filter bar */
    .csh-filter { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.9rem 1.25rem; margin-bottom: 1.25rem; display: flex; gap: 0.7rem; align-items: flex-end; flex-wrap: wrap; }
    .csh-filter .fg { display: flex; flex-direction: column; gap: 0.28rem; }
    .csh-filter .fg label { font-size: 0.66rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; }
    .csh-filter .fi { padding: 0.35rem 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.84rem; color: #334155; background: #fff; height: 36px; }
    .csh-filter .fi:focus { outline: none; border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,0.1); }
    .csh-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.42rem 0.95rem; border-radius: 8px; font-size: 0.8rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: opacity .15s, background .15s; height: 36px; }
    .csh-btn:hover { opacity: 0.88; color: #fff; }
    .csh-btn.emerald { background: #059669; color: #fff; }
    .csh-btn.ghost { background: #f1f5f9; color: #475569; }
    .csh-btn.ghost:hover { color: #334155; background: #e2e8f0; }
    .csh-include { display: flex; align-items: center; gap: 0.4rem; font-size: 0.78rem; font-weight: 600; color: #64748b; cursor: pointer; height: 36px; }

    /* Ledger */
    .csh-ledger { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .csh-ledger-head { padding: 0.9rem 1.4rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .csh-ledger-head h3 { margin: 0; font-size: 0.9rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.55rem; }
    .csh-ledger-head h3 i { color: #0d9488; }
    .csh-ledger-head .badge { font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; font-weight: 700; color: #64748b; background: #f1f5f9; border-radius: 99px; padding: 0.2rem 0.7rem; }
    .csh-table { width: 100%; border-collapse: collapse; }
    .csh-table thead th { text-align: left; font-size: 0.66rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.09em; color: #64748b; padding: 0.65rem 1.1rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .csh-table thead th.num, .csh-table tbody td.num { text-align: right; }
    .csh-table tbody td { padding: 0.8rem 1.1rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.86rem; }
    .csh-table tbody tr { border-left: 3px solid transparent; transition: background .15s; }
    .csh-table tbody tr:hover { background: #f8fafc; }
    .csh-table tbody tr.row-urg { border-left-color: #f59e0b; background: #fffdf8; }
    .csh-table tbody tr.row-urg:hover { background: #fff9ee; }
    .csh-table tbody tr.row-over { border-left-color: #dc2626; background: #fffbfb; }
    .csh-table tbody tr.row-over:hover { background: #fff5f5; }

    /* Data cells */
    .csh-ref { font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; letter-spacing: 0.02em; color: #334155; font-weight: 700; white-space: nowrap; }
    .csh-ref .h { color: #94a3b8; }
    .csh-guest-name { font-weight: 700; color: #0f172a; }
    .csh-sub { font-size: 0.74rem; color: #94a3b8; margin-top: 0.12rem; }
    .csh-amount { font-family: 'JetBrains Mono', monospace; font-weight: 800; font-variant-numeric: tabular-nums; color: #0f172a; font-size: 0.95rem; }
    .csh-paid { font-size: 0.7rem; color: #047857; font-weight: 600; margin-top: 0.1rem; }
    .csh-chip { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.22rem 0.6rem; border-radius: 99px; font-size: 0.66rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; }
    .csh-chip.amber { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .csh-chip.red { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .csh-deadline-abs { font-size: 0.7rem; color: #94a3b8; margin-top: 0.25rem; font-family: 'JetBrains Mono', monospace; white-space: nowrap; }
    .csh-btn-process { background: #059669; color: #fff; }
</style>

@php
    $pageDue     = $reservations->sum(fn ($r) => $r->payment_status === 'partially_paid' ? $r->remaining_balance : $r->total_price);
    $pageUrgent  = $reservations->filter(fn ($r) => $r->payment_deadline->isFuture() && $r->payment_deadline->diffInHours(now()) < 6)->count();
    $pageExpired = $reservations->filter(fn ($r) => $r->payment_deadline->isPast())->count();
@endphp

{{-- Register tape --}}
<div class="csh-tape">
    <div class="csh-tape-cell">
        <div class="v hero">{{ $reservations->total() }}</div>
        <div class="l">In Queue</div>
    </div>
    <div class="csh-tape-cell">
        <div class="v hero">&#8369;{{ number_format($pageDue, 2) }}</div>
        <div class="l">Due (This Page)</div>
    </div>
    <div class="csh-tape-cell">
        <div class="v urg">{{ $pageUrgent }}</div>
        <div class="l">Urgent &lt; 6h</div>
    </div>
    <div class="csh-tape-cell">
        <div class="v">{{ $pageExpired }}</div>
        <div class="l">Expired</div>
    </div>
</div>

{{-- Filter bar --}}
<div class="csh-filter">
    <form method="GET" action="{{ route('cashier.payments.index') }}" style="display:flex;gap:0.7rem;flex-wrap:wrap;align-items:flex-end;flex:1;">
        <div class="fg" style="flex:1;min-width:190px;">
            <label><i class="bi bi-search me-1"></i>Search</label>
            <input type="text" name="search" class="fi" placeholder="Ref # or name…" value="{{ request('search') }}">
        </div>
        <div class="fg">
            <label><i class="bi bi-building me-1"></i>Facility</label>
            <select name="facility_id" class="fi" style="min-width:150px;">
                <option value="">All Facilities</option>
                @foreach($facilities as $f)
                    <option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="fg">
            <label><i class="bi bi-calendar3 me-1"></i>Date</label>
            <input type="date" name="date" class="fi" value="{{ request('date') }}">
        </div>
        <div class="fg" style="justify-content:flex-end;">
            <label style="opacity:0;">&nbsp;</label>
            <label class="csh-include">
                <input type="checkbox" name="include_expired" value="1" {{ request('include_expired') ? 'checked' : '' }}> Show Expired
            </label>
        </div>
        <button type="submit" class="csh-btn emerald"><i class="bi bi-funnel-fill"></i> Filter</button>
        @if(request()->hasAny(['search','facility_id','date','include_expired']))
            <a href="{{ route('cashier.payments.index') }}" class="csh-btn ghost">Clear</a>
        @endif
    </form>
</div>

{{-- Ledger --}}
<div class="csh-ledger">
    <div class="csh-ledger-head">
        <h3><i class="bi bi-cash-stack"></i> Reservations Pending Cash</h3>
        <span class="badge">{{ $reservations->total() }} record{{ $reservations->total() !== 1 ? 's' : '' }}</span>
    </div>
    <table class="csh-table">
        <thead><tr>
            <th style="width:130px;">Reference</th>
            <th style="width:185px;">Guest</th>
            <th>Facility</th>
            <th style="width:85px;">Event Date</th>
            <th class="num" style="width:115px;">Amount Due</th>
            <th style="width:130px;">Deadline</th>
            <th style="width:95px;">Action</th>
        </tr></thead>
        <tbody>
        @forelse($reservations as $r)
            @php
                $isOverdue = $r->payment_deadline->isPast();
                $isUrgent  = !$isOverdue && $r->payment_deadline->diffInHours(now()) < 6;
                $due       = $r->payment_status === 'partially_paid' ? $r->remaining_balance : $r->total_price;
            @endphp
            <tr class="{{ $isOverdue ? 'row-over' : ($isUrgent ? 'row-urg' : '') }}">
                <td><span class="csh-ref"><span class="h">#</span>{{ $r->booking_reference }}</span></td>
                <td>
                    <div class="csh-guest-name">{{ $r->user->name ?? 'N/A' }}</div>
                    <div class="csh-sub">{{ $r->user->email ?? '' }}</div>
                </td>
                <td>{{ $r->facility->name ?? 'N/A' }}</td>
                <td style="font-size:0.82rem;color:#475569;">
                    {{ \Carbon\Carbon::parse($r->start_date)->format('M d, Y') }}<br>
                    <span style="color:#94a3b8;">{{ \Carbon\Carbon::parse($r->start_time)->format('g:i A') }}</span>
                </td>
                <td class="num">
                    <span class="csh-amount">&#8369;{{ number_format($due, 2) }}</span>
                    @if($r->payment_status === 'partially_paid')
                        <div class="csh-paid">Balance · &#8369;{{ number_format($r->amount_paid, 2) }} paid</div>
                    @endif
                </td>
                <td>
                    @if($isOverdue)
                        <span class="csh-chip red"><i class="bi bi-exclamation-triangle-fill"></i> Overdue</span>
                    @elseif($isUrgent)
                        <span class="csh-chip amber"><i class="bi bi-alarm-fill"></i> {{ $r->payment_deadline->diffForHumans() }}</span>
                    @else
                        <span class="csh-chip amber">Due {{ $r->payment_deadline->diffForHumans() }}</span>
                    @endif
                    <div class="csh-deadline-abs">{{ $r->payment_deadline->format('M d g:i A') }}</div>
                </td>
                <td>
                    <a href="{{ route('cashier.payments.show', $r) }}" class="csh-btn csh-btn-process"><i class="bi bi-cash-coin"></i> Process</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">
                    <div class="panel-empty">
                        <i class="bi bi-inbox"></i>
                        <h4>Nothing in the queue</h4>
                        <p>No pending cash payments match the current filters.</p>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    @if($reservations->hasPages())
        <div style="padding:0.9rem 1.4rem;border-top:1px solid #e2e8f0;">{{ $reservations->links() }}</div>
    @endif
</div>
@endsection
