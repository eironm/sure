@extends('layouts.superadmin')
@section('title', 'Dashboard')
@section('content')

@php
    $greeting = (int) now()->format('H');
    $greet = $greeting < 12 ? 'Good morning' : ($greeting < 18 ? 'Good afternoon' : 'Good evening');
@endphp

{{-- ── Page header ──────────────────────────────────────────────── --}}
<div class="page-header">
    <div>
        <h1 class="ph-title">{{ $greet }}, {{ explode(' ', Auth::user()->name)[0] }}</h1>
        <p class="ph-sub">
            {{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Here's what's happening across the platform right now.
        </p>
    </div>
    <div class="page-actions">
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Create Account
        </a>
    </div>
</div>

{{-- ── Primary KPIs ─────────────────────────────────────────────── */ --}}
<div class="stat-grid" style="margin-bottom: 1.25rem;">
    <a class="stat-card" href="{{ route('superadmin.users.index') }}">
        <div class="stat-top">
            <span class="stat-tile neutral"><i class="bi bi-people-fill"></i></span>
        </div>
        <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
        <div class="stat-label">Total Accounts</div>
    </a>

    <a class="stat-card" href="{{ route('superadmin.users.index') }}?status=active">
        <div class="stat-top">
            <span class="stat-tile ok"><i class="bi bi-person-check-fill"></i></span>
        </div>
        <div class="stat-value">{{ number_format($stats['total_users'] - $stats['inactive_accounts']) }}</div>
        <div class="stat-label">Active Accounts</div>
    </a>

    <a class="stat-card" href="{{ route('superadmin.users.index') }}?status=inactive">
        <div class="stat-top">
            <span class="stat-tile bad"><i class="bi bi-person-x-fill"></i></span>
        </div>
        <div class="stat-value">{{ number_format($stats['inactive_accounts']) }}</div>
        <div class="stat-label">Inactive Accounts</div>
    </a>

    <a class="stat-card" href="{{ route('superadmin.users.index') }}?role=cashier">
        <div class="stat-top">
            <span class="stat-tile info"><i class="bi bi-cash-coin"></i></span>
        </div>
        <div class="stat-value">₱{{ number_format($stats['total_collected'], 0) }}</div>
        <div class="stat-label">Total Collected</div>
    </a>
</div>

{{-- ── Operations strip: reservations + attention queue ─────────── */ --}}
<div class="stat-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 1.5rem;">
    <a class="stat-card" href="{{ route('admin.reservations.index') }}?status=pending">
        <div class="stat-top">
            <span class="stat-tile warn"><i class="bi bi-hourglass-split"></i></span>
        </div>
        <div class="stat-value">{{ number_format($stats['pending_reservations']) }}</div>
        <div class="stat-label">Pending Reservations</div>
    </a>

    <a class="stat-card" href="{{ route('admin.reservations.index') }}?payment_status=cash_pending">
        <div class="stat-top">
            <span class="stat-tile warn"><i class="bi bi-cash-stack"></i></span>
        </div>
        <div class="stat-value">{{ number_format($stats['cash_pending']) }}</div>
        <div class="stat-label">Cash Pending Payment</div>
    </a>

    <a class="stat-card" href="{{ route('admin.reservations.index') }}?status=approved">
        <div class="stat-top">
            <span class="stat-tile ok"><i class="bi bi-calendar-check-fill"></i></span>
        </div>
        <div class="stat-value">{{ number_format($stats['approved_reservations']) }}</div>
        <div class="stat-label">Approved Reservations</div>
    </a>
</div>

{{-- ── Recent activity ──────────────────────────────────────────── */ --}}
<div class="two-col">
    <div class="card">
        <div class="card-head">
            <span class="ch-title"><i class="bi bi-person-lines-fill"></i> Recent Accounts</span>
            <a href="{{ route('superadmin.users.index') }}" class="sa-link">View all →</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="plain">
                <tbody>
                @forelse($recentUsers as $u)
                    <tr>
                        <td>
                            <div class="who">
                                <x-user-avatar :user="$u" size="sm" />
                                <div style="min-width:0;">
                                    <div class="who-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $u->name }}</div>
                                    <div class="who-sub" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-soft">{{ $u->roleLabel() }}</span></td>
                        <td>
                            <span class="badge {{ $u->is_active ? 'badge-ok' : 'badge-bad' }}">
                                {{ $u->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">
                        <div class="empty">
                            <i class="bi bi-people"></i>
                            <strong>No accounts yet</strong>
                            <span>Created accounts will appear here.</span>
                        </div>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <span class="ch-title"><i class="bi bi-calendar3"></i> Recent Reservations</span>
            <a href="{{ route('admin.reservations.index') }}" class="sa-link">View all →</a>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="plain">
                <tbody>
                @forelse($recentReservations as $r)
                    <tr>
                        <td>
                            <div class="who-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $r->user->name ?? 'N/A' }}</div>
                            <div class="who-sub mono">{{ $r->booking_reference }}</div>
                        </td>
                        <td>{{ $r->facility->name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $pstatus = match ($r->payment_status) {
                                    'paid' => ['Paid', 'badge-ok'],
                                    'cash_pending' => ['Cash Pending', 'badge-warn'],
                                    default => ['Unpaid', 'badge-bad'],
                                };
                            @endphp
                            <span class="badge {{ $pstatus[1] }}">{{ $pstatus[0] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">
                        <div class="empty">
                            <i class="bi bi-calendar-x"></i>
                            <strong>No reservations yet</strong>
                            <span>Booking activity will appear here.</span>
                        </div>
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
