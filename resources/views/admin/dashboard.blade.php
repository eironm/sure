@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
{{-- ── 1. HEADER & ACTION STRIP ── --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
    <div>
        <h1 class="dash-page-title mb-1">
            <span id="dashboard-greeting">{{ $greeting ?? 'Good day' }}</span>, {{ Auth::user()->first_name ?? Auth::user()->name }} 👋
        </h1>
        <p class="dash-page-subtitle mb-0">Here's the operational overview of campus facilities and reservations today.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.reservations.index') }}" class="dash-head-btn">
            <i class="bi bi-calendar3 text-primary"></i>
            <span>Calendar</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="dash-head-btn">
            <i class="bi bi-file-earmark-bar-graph text-success"></i>
            <span>Reports</span>
        </a>
        <a href="{{ route('admin.facilities.create') }}" class="dash-head-btn dash-head-btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>New Venue</span>
        </a>
    </div>
</div>

{{-- ── 2. HIGH-PRIORITY ACTION STRIP (When pending reviews exist) ── --}}
@if($pendingReservations > 0)
<div class="dash-alert-strip mb-3">
    <div class="d-flex align-items-center gap-3">
        <div class="dash-alert-icon">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="dash-alert-title">Action Required: Pending Approvals</span>
                <span class="dash-pill dash-pill-amber fw-bold">{{ $pendingReservations }} {{ Str::plural('request', $pendingReservations) }}</span>
            </div>
            <p class="dash-alert-desc mb-0">
                {{ $pendingReservations }} facility reservation {{ $pendingReservations == 1 ? 'request is' : 'requests are' }} awaiting your administrative review and approval.
            </p>
        </div>
    </div>
    <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" class="dash-alert-btn">
        <span>Review Requests</span>
        <i class="bi bi-arrow-right"></i>
    </a>
</div>
@endif

{{-- ── 3. FOUR BALANCED METRIC CARDS (Roomsy / TaxiAdmin Inspired) ── --}}
<div class="row g-3 mb-3">
    {{-- Card 1: Pending Approvals --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="dash-stat-card">
            <div class="dash-stat-top">
                <div class="dash-stat-icon icon-amber">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                @if($pendingReservations > 0)
                    <span class="dash-pill dash-pill-amber">Action Needed</span>
                @else
                    <span class="dash-pill dash-pill-emerald">All Cleared</span>
                @endif
            </div>
            <div class="dash-stat-value">{{ number_format($pendingReservations) }}</div>
            <div class="dash-stat-label">Pending Approvals</div>
            <div class="dash-stat-sub">
                @if($pendingReservations > 0)
                    {{ $pendingReservations }} awaiting review
                @else
                    No pending submissions
                @endif
            </div>
        </div>
    </div>

    {{-- Card 2: Scheduled Bookings --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="dash-stat-card">
            <div class="dash-stat-top">
                <div class="dash-stat-icon icon-blue">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <span class="dash-pill dash-pill-blue">Next 7 Days</span>
            </div>
            <div class="dash-stat-value">{{ number_format($upcomingReservations->count()) }}</div>
            <div class="dash-stat-label">Scheduled Bookings</div>
            <div class="dash-stat-sub">Upcoming active bookings</div>
        </div>
    </div>

    {{-- Card 3: Active Facilities --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="dash-stat-card">
            <div class="dash-stat-top">
                <div class="dash-stat-icon icon-emerald">
                    <i class="bi bi-building-check"></i>
                </div>
                <span class="dash-pill dash-pill-emerald">Operational</span>
            </div>
            <div class="dash-stat-value">{{ number_format($totalFacilities) }}</div>
            <div class="dash-stat-label">Active Facilities</div>
            <div class="dash-stat-sub">Across {{ $totalCategories }} {{ Str::plural('category', $totalCategories) }}</div>
        </div>
    </div>

    {{-- Card 4: Revenue & Collections --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="dash-stat-card">
            <div class="dash-stat-top">
                <div class="dash-stat-icon icon-purple">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="dash-pill dash-pill-purple" title="All-time collected">₱{{ number_format($revenueTotal, 0) }} Total</span>
            </div>
            <div class="dash-stat-value">₱{{ number_format($revenueThisMonth, 2) }}</div>
            <div class="dash-stat-label">Collected This Month</div>
            <div class="dash-stat-sub">
                @if($outstandingValue > 0)
                    <span class="text-amber-emphasis fw-semibold">₱{{ number_format($outstandingValue, 0) }} pending payment</span>
                @else
                    All payments up to date
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── 4. ANALYTICS ROW: DEMAND SPLINE + STATUS BREAKDOWN ── --}}
<div class="row g-3 mb-3">
    {{-- Left Chart: Booking Demand Spline (Col 8) --}}
    <div class="col-12 col-lg-8">
        <div class="dash-card h-100">
            <div class="dash-card-header">
                <div>
                    <h6 class="dash-card-title"><i class="bi bi-graph-up text-primary me-2"></i>Booking Activity & Demand</h6>
                    <p class="dash-card-subtitle">Daily facility reservation submissions over the last 14 days</p>
                </div>
                <span class="dash-pill dash-pill-blue fw-bold">
                    {{ $bookingTrend->sum('count') }} Total Requests
                </span>
            </div>
            <div class="dash-card-body pt-2">
                <div style="position: relative; height: 215px; width: 100%;">
                    <canvas id="bookingDemandChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Chart: Booking Status Donut (Col 4) --}}
    <div class="col-12 col-lg-4">
        <div class="dash-card h-100 d-flex flex-column justify-content-between">
            <div class="dash-card-header">
                <div>
                    <h6 class="dash-card-title"><i class="bi bi-pie-chart text-primary me-2"></i>Booking Status</h6>
                    <p class="dash-card-subtitle">Overall request distribution</p>
                </div>
                <span class="dash-pill dash-pill-slate fw-bold">
                    {{ number_format($totalReservations) }} Total
                </span>
            </div>
            <div class="dash-card-body pt-1 d-flex flex-column justify-content-between flex-grow-1">
                <div class="d-flex justify-content-center align-items-center position-relative my-2" style="height: 145px;">
                    <canvas id="bookingStatusChart"></canvas>
                    <div class="dash-donut-center">
                        <span class="donut-rate">{{ $approvalRate !== null ? $approvalRate . '%' : '—' }}</span>
                        <span class="donut-lbl">Approval</span>
                    </div>
                </div>
                
                {{-- 4-Item Status Legend --}}
                <div class="dash-status-legend">
                    <div class="legend-item">
                        <span class="legend-dot" style="background-color: #10b981;"></span>
                        <span class="legend-name">Approved</span>
                        <span class="legend-count">{{ number_format($statusDistribution['approved'] ?? 0) }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background-color: #f59e0b;"></span>
                        <span class="legend-name">Pending</span>
                        <span class="legend-count">{{ number_format($statusDistribution['pending'] ?? 0) }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background-color: #ef4444;"></span>
                        <span class="legend-name">Denied</span>
                        <span class="legend-count">{{ number_format($statusDistribution['denied'] ?? 0) }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot" style="background-color: #64748b;"></span>
                        <span class="legend-name">Cancelled</span>
                        <span class="legend-count">{{ number_format($statusDistribution['cancelled'] ?? 0) }}</span>
                    </div>
                </div>

                {{-- Outstanding Strip --}}
                @if($outstandingValue > 0)
                <div class="dash-outstanding-strip mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted fw-semibold"><i class="bi bi-clock-history me-1 text-warning"></i>Outstanding Payments:</span>
                        <span class="fw-bold small text-dark">₱{{ number_format($outstandingValue, 2) }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── 5. OPERATIONS WORKSPACE: RECENT RESERVATIONS + OPERATIONAL RAIL ── --}}
<div class="row g-3">
    {{-- Left: Recent Reservations Table (Col 8) --}}
    <div class="col-12 col-lg-8">
        <div class="dash-card h-100 d-flex flex-column justify-content-between">
            <div class="dash-card-header pb-2">
                <div>
                    <h6 class="dash-card-title"><i class="bi bi-clock-history text-primary me-2"></i>Recent Reservations</h6>
                    <p class="dash-card-subtitle">Latest booking submissions awaiting or completed by campus users</p>
                </div>
                <a href="{{ route('admin.reservations.index') }}" class="dash-link-btn">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="p-0 flex-grow-1">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 dash-table">
                        <thead>
                            <tr>
                                <th class="ps-3 py-2 text-uppercase">ID</th>
                                <th class="py-2 text-uppercase">Requester</th>
                                <th class="py-2 text-uppercase">Facility & Event</th>
                                <th class="py-2 text-uppercase">Schedule</th>
                                <th class="py-2 text-uppercase">Status</th>
                                <th class="pe-3 py-2 text-uppercase text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReservations as $res)
                            <tr>
                                <td class="ps-3 fw-bold text-nowrap font-mono text-muted" style="font-size: .8rem;">
                                    #{{ str_pad($res->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td style="max-width: 170px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <x-user-avatar :user="$res->user" size="sm" class="flex-shrink-0" />
                                        <div class="text-truncate" style="min-width: 0;">
                                            <p class="mb-0 fw-bold small text-truncate text-dark" style="font-size: .82rem;">{{ $res->user->name }}</p>
                                            <span class="text-muted text-truncate d-block" style="font-size: 11px;">{{ $res->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="max-width: 170px;">
                                    <p class="mb-0 fw-semibold small text-truncate text-dark" style="font-size: .82rem;">{{ $res->facility->name }}</p>
                                    <span class="text-muted text-truncate d-block" style="font-size: 11px;">{{ $res->event_title ?: 'Facility reservation' }}</span>
                                </td>
                                <td class="small text-nowrap">
                                    <p class="mb-0 fw-semibold text-dark" style="font-size: .82rem;">{{ \Carbon\Carbon::parse($res->start_date)->format('M d, Y') }}</p>
                                    <span class="text-muted" style="font-size: 11px;">{{ $res->start_time ? \Carbon\Carbon::parse($res->start_time)->format('g:i A') : 'All day' }}</span>
                                </td>
                                <td class="text-nowrap">
                                    @php
                                        $pillClass = match($res->status) {
                                            'pending'   => 'dash-pill-amber',
                                            'approved'  => 'dash-pill-emerald',
                                            'denied'    => 'dash-pill-rose',
                                            default     => 'dash-pill-slate',
                                        };
                                    @endphp
                                    <span class="dash-pill {{ $pillClass }} fw-bold">
                                        {{ $res->status_label ?? ucfirst($res->status) }}
                                    </span>
                                </td>
                                <td class="pe-3 text-end text-nowrap">
                                    <a href="{{ route('admin.reservations.index', ['open' => $res->id]) }}" class="dash-table-btn">
                                        Review
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">No recent reservations found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Operational Rail (Mini Calendar + Venues/Actions) (Col 4) --}}
    <div class="col-12 col-lg-4">
        <div class="d-flex flex-column gap-2.5 h-100 justify-content-between">
            {{-- Mini Calendar Card (Roomsy / Ventixe Style) --}}
            <div class="dash-card">
                <div class="dash-card-header py-2 px-3">
                    <h6 class="dash-card-title mb-0" style="font-size: .84rem;">
                        <i class="bi bi-calendar3 text-primary me-1.5"></i>
                        {{ now()->format('F Y') }}
                    </h6>
                    <a href="{{ route('admin.reservations.index') }}" class="dash-link-btn" style="font-size: .72rem;">
                        Full Calendar →
                    </a>
                </div>
                <div class="dash-card-body pt-1 pb-2 px-3">
                    <div class="dash-cal-wrap dash-mini-cal">
                        <div class="dash-cal-header-row">
                            <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                        </div>
                        <div class="dash-cal-days-grid">
                            @for($i = 0; $i < $firstDayOfWeek; $i++)
                                <span class="cal-day-cell empty"></span>
                            @endfor
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $isToday = (now()->day == $d);
                                    $hasBooking = in_array($d, $calendarBookingDays ?? []);
                                @endphp
                                <span class="cal-day-cell {{ $isToday ? 'today' : '' }} {{ $hasBooking ? 'has-event' : '' }}" title="{{ $hasBooking ? 'Active bookings on this day' : '' }}">
                                    {{ $d }}
                                    @if($hasBooking)<span class="cal-dot"></span>@endif
                                </span>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            {{-- Operational Feed / Top Venues Card --}}
            <div class="dash-card flex-grow-1 d-flex flex-column justify-content-between">
                <div>
                    <div class="dash-card-header py-2 px-3">
                        <h6 class="dash-card-title mb-0" style="font-size: .84rem;">
                            @if($upcomingReservations->count() > 0)
                                <i class="bi bi-lightning-charge text-warning me-1.5"></i>Upcoming ({{ $upcomingReservations->count() }})
                            @else
                                <i class="bi bi-trophy text-amber me-1.5"></i>Top Venues (30d)
                            @endif
                        </h6>
                        <span class="small fw-bold text-muted" style="font-size: 11px;">Top Venues</span>
                    </div>

                    <div class="dash-card-body pt-1 pb-2 px-3">
                        @if($upcomingReservations->count() > 0)
                            {{-- Upcoming Schedule Items --}}
                            @foreach($upcomingReservations->take(2) as $up)
                            @php
                                $isToday = \Carbon\Carbon::parse($up->start_date)->isToday();
                                $isTomorrow = \Carbon\Carbon::parse($up->start_date)->isTomorrow();
                            @endphp
                            <div class="d-flex align-items-center gap-2 py-1.5 border-bottom border-light">
                                <div class="dash-sched-pill flex-shrink-0">
                                    <span class="sched-date">{{ \Carbon\Carbon::parse($up->start_date)->format('M d') }}</span>
                                    <span class="sched-time">{{ $up->start_time ? \Carbon\Carbon::parse($up->start_time)->format('g:i A') : 'All Day' }}</span>
                                </div>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold small text-truncate text-dark" style="font-size: .8rem;">{{ $up->facility->name }}</h6>
                                        @if($isToday)
                                            <span class="dash-pill dash-pill-rose fw-bold" style="font-size: 9.5px; padding: 1px 5px;">TODAY</span>
                                        @elseif($isTomorrow)
                                            <span class="dash-pill dash-pill-blue fw-bold" style="font-size: 9.5px; padding: 1px 5px;">TOMORROW</span>
                                        @endif
                                    </div>
                                    <span class="text-muted text-truncate d-block" style="font-size: 10.5px;">
                                        <i class="bi bi-person me-1"></i> {{ $up->user->name ?? 'User' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        @else
                            {{-- Top Venues fallback if no upcoming bookings --}}
                            @forelse($topFacilities->take(2) as $tf)
                            <div class="py-1 border-bottom border-light">
                                <div class="d-flex justify-content-between small fw-bold mb-0.5">
                                    <span class="text-truncate pe-2 text-dark" style="font-size: .8rem;">{{ $tf->facility->name ?? 'Facility' }}</span>
                                    <span class="text-muted flex-shrink-0" style="font-size: .72rem;">{{ $tf->total }} {{ Str::plural('booking', $tf->total) }}</span>
                                </div>
                                <div class="dash-track">
                                    <div class="dash-track-bar" style="width: {{ ($tf->total / $topFacilityMax) * 100 }}%;"></div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-2 text-muted small">No recent bookings.</div>
                            @endforelse
                        @endif
                    </div>
                </div>

                {{-- Quick Shortcuts Bar --}}
                <div class="dash-card-footer p-2 px-3 bg-light bg-opacity-50 border-top d-flex align-items-center justify-content-between gap-1.5">
                    <span class="d-none">Quick Shortcuts</span>
                    <a href="{{ route('admin.facilities.create') }}" class="dash-footer-btn flex-fill text-center" style="font-size: .72rem;">
                        <i class="bi bi-building-add text-primary me-1"></i> Add Venue
                    </a>
                    <a href="{{ route('admin.reservations.index') }}" class="dash-footer-btn flex-fill text-center" style="font-size: .72rem;">
                        <i class="bi bi-calendar3 text-info me-1"></i> Calendar
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="dash-footer-btn flex-fill text-center" style="font-size: .72rem;">
                        <i class="bi bi-file-earmark-bar-graph text-success me-1"></i> Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Base Dashboard Layout & Typography ── */
.dash-page-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    line-height: 1.2;
}
.dash-page-subtitle {
    color: #64748b;
    font-size: .86rem;
    font-weight: 500;
}

/* ── Header Buttons ── */
.dash-head-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 99px;
    padding: .45rem .95rem;
    font-size: .8rem;
    font-weight: 700;
    color: #334155;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: all 0.15s ease;
}
.dash-head-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
    transform: translateY(-1px);
}
.dash-head-btn-primary {
    background: #7c3aed;
    border-color: #7c3aed;
    color: #ffffff;
}
.dash-head-btn-primary:hover {
    background: #6d28d9;
    border-color: #6d28d9;
    color: #ffffff;
}

/* ── High-Priority Notification Strip ── */
.dash-alert-strip {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 14px;
    padding: .85rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(245, 158, 11, 0.08);
}
.dash-alert-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fde68a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.dash-alert-title {
    font-size: .88rem;
    font-weight: 800;
    color: #92400e;
}
.dash-alert-desc {
    font-size: .78rem;
    color: #b45309;
    font-weight: 500;
}
.dash-alert-btn {
    background: #f59e0b;
    color: #ffffff;
    font-weight: 700;
    font-size: .78rem;
    padding: .45rem 1.1rem;
    border-radius: 99px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
    transition: all 0.15s ease;
}
.dash-alert-btn:hover {
    background: #d97706;
    color: #ffffff;
    transform: translateY(-1px);
}

/* ── Explicit High-Contrast Pills (Never Dependent on Broken Opacity Classes) ── */
.dash-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.3;
    letter-spacing: .01em;
    font-family: 'Figtree', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.dash-pill-amber   { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.dash-pill-emerald { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.dash-pill-blue    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.dash-pill-purple  { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
.dash-pill-rose    { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.dash-pill-slate   { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }

/* ── Four Spacious Stat Cards (No Truncation) ── */
.dash-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1rem 1.2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    min-height: 124px;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.dash-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
}
.dash-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: .6rem;
}
.dash-stat-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.icon-amber   { background: #fef3c7; color: #d97706; }
.icon-blue    { background: #eff6ff; color: #2563eb; }
.icon-emerald { background: #dcfce7; color: #059669; }
.icon-purple  { background: #f5f3ff; color: #7c3aed; }

.dash-stat-value {
    font-size: 1.65rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    letter-spacing: -0.02em;
    margin-bottom: 2px;
}
.dash-stat-label {
    font-size: .82rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 2px;
    white-space: normal; /* Guarantee NO truncation! */
}
.dash-stat-sub {
    font-size: .74rem;
    font-weight: 500;
    color: #64748b;
    white-space: normal;
}
.text-amber-emphasis {
    color: #b45309 !important;
}

/* ── Standard Dash Cards ── */
.dash-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}
.dash-card-header {
    padding: .9rem 1.15rem .6rem 1.15rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
}
.dash-card-title {
    font-size: .92rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 2px;
}
.dash-card-subtitle {
    font-size: .74rem;
    color: #64748b;
    margin-bottom: 0;
}
.dash-card-body {
    padding: .6rem 1.15rem .9rem 1.15rem;
}
.dash-link-btn {
    font-size: .78rem;
    font-weight: 700;
    color: #4f46e5;
    text-decoration: none;
}
.dash-link-btn:hover {
    color: #4338ca;
    text-decoration: underline;
}

/* ── Donut Chart Styling ── */
.dash-donut-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}
.donut-rate {
    display: block;
    font-size: 1.4rem;
    font-weight: 900;
    color: #0f172a;
    line-height: 1;
}
.donut-lbl {
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #94a3b8;
}

.dash-status-legend {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 6px 12px;
    padding-top: 8px;
    border-top: 1px solid #f1f5f9;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .78rem;
    font-weight: 600;
    color: #475569;
}
.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}
.legend-name {
    flex-grow: 1;
}
.legend-count {
    font-weight: 800;
    color: #0f172a;
}
.dash-outstanding-strip {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px 10px;
}

/* ── Recent Reservations Data Table ── */
.dash-table thead th {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .05em;
    color: #64748b;
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: .65rem 1rem;
}
.dash-table tbody td {
    padding: .7rem 1rem;
    border-bottom: 1px solid #f1f5f9;
}
.dash-table tbody tr:hover {
    background-color: #f8fafc;
}
.dash-table-btn {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 99px;
    padding: 3px 10px;
    font-size: .75rem;
    font-weight: 700;
    color: #334155;
    text-decoration: none;
    transition: all 0.15s ease;
}
.dash-table-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #94a3b8;
}

/* ── Mini Calendar Widget ── */
.dash-cal-header-row {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-size: .7rem;
    font-weight: 700;
    color: #94a3b8;
    margin-bottom: 6px;
}
.dash-cal-days-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 3px;
    text-align: center;
}
.cal-day-cell {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    font-weight: 600;
    color: #334155;
    border-radius: 6px;
    position: relative;
    cursor: default;
}
.cal-day-cell.empty {
    visibility: hidden;
}
.cal-day-cell.today {
    background: #7c3aed !important;
    color: #ffffff !important;
    font-weight: 800;
}
.cal-day-cell.has-event:not(.today) {
    background: #f5f3ff;
    color: #6d28d9;
    font-weight: 700;
}
.cal-day-cell .cal-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #7c3aed;
    position: absolute;
    bottom: 2px;
}
.cal-day-cell.today .cal-dot {
    background: #ffffff;
}

/* ── Schedule Feed & Utilization Tracks ── */
.dash-sched-pill {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 4px 6px;
    min-width: 62px;
    text-align: center;
}
.sched-date {
    display: block;
    font-size: .68rem;
    font-weight: 800;
    color: #4f46e5;
    text-transform: uppercase;
}
.sched-time {
    display: block;
    font-size: .62rem;
    font-weight: 600;
    color: #64748b;
}

.dash-track {
    height: 6px;
    border-radius: 99px;
    background: #f1f5f9;
    overflow: hidden;
}
.dash-track-bar {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #7c3aed, #a78bfa);
}

.dash-footer-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 99px;
    padding: 3px 8px;
    font-size: .74rem;
    font-weight: 700;
    color: #334155;
    text-decoration: none;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    transition: all 0.15s ease;
}
.dash-footer-btn:hover {
    background: #f8fafc;
    color: #0f172a;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── 1. BOOKING DEMAND SPLINE CHART ──
    const demandEl = document.getElementById('bookingDemandChart');
    if (demandEl && typeof Chart !== 'undefined') {
        const labels = @json($chartLabels ?? []);
        const dataValues = @json($chartData ?? []);

        const ctx2d = demandEl.getContext('2d');
        const grad = ctx2d.createLinearGradient(0, 0, 0, 215);
        grad.addColorStop(0, 'rgba(124, 58, 237, 0.25)');
        grad.addColorStop(1, 'rgba(124, 58, 237, 0.00)');

        new Chart(demandEl, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Reservations',
                    data: dataValues,
                    borderColor: '#7c3aed',
                    borderWidth: 2.2,
                    backgroundColor: grad,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#7c3aed',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 3.5,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Figtree, sans-serif', size: 11, weight: 'bold' },
                        bodyFont: { family: 'Figtree, sans-serif', size: 11 },
                        padding: 8,
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                return context.parsed.y + ' booking' + (context.parsed.y === 1 ? '' : 's');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Figtree, sans-serif', size: 10, weight: '600' },
                            color: '#94a3b8',
                            maxRotation: 0,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { family: 'Figtree, sans-serif', size: 10, weight: '600' },
                            color: '#94a3b8'
                        },
                        grid: {
                            color: '#f8fafc'
                        }
                    }
                }
            }
        });
    }

    // ── 2. BOOKING STATUS DONUT CHART ──
    const statusEl = document.getElementById('bookingStatusChart');
    if (statusEl && typeof Chart !== 'undefined') {
        const appCount = {{ (int) ($statusDistribution['approved'] ?? 0) }};
        const pendCount = {{ (int) ($statusDistribution['pending'] ?? 0) }};
        const denCount = {{ (int) ($statusDistribution['denied'] ?? 0) }};
        const canCount = {{ (int) ($statusDistribution['cancelled'] ?? 0) }};
        const totalCount = appCount + pendCount + denCount + canCount;

        new Chart(statusEl, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Denied', 'Cancelled'],
                datasets: [{
                    data: totalCount > 0 ? [appCount, pendCount, denCount, canCount] : [1],
                    backgroundColor: totalCount > 0 ? ['#10b981', '#f59e0b', '#ef4444', '#64748b'] : ['#e2e8f0'],
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: totalCount > 0,
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Figtree, sans-serif', size: 11, weight: 'bold' },
                        bodyFont: { family: 'Figtree, sans-serif', size: 11 },
                        padding: 8,
                        cornerRadius: 6,
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
