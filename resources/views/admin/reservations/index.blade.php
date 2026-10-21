@extends('layouts.admin')

@section('title', 'Reservation Management')

@section('content')
{{-- ============================================================
 PAGE HEADER
 ============================================================ --}}
<div class="d-flex justify-content-between align-items-center mb-4">
 <div>
 <h1 class="mb-0 fw-black text-dark tracking-tight" style="font-size: 2.2rem;">Reservations</h1>
 <p class="text-muted mb-0">Monitor facility usage, review requests, and manage bookings.</p>
 </div>
 <div class="d-flex align-items-center gap-2">
 {{-- View Toggle --}}
 <div class="view-toggle-group" id="viewToggle">
 <button class="view-toggle-btn active" id="btnCalView" onclick="switchView('calendar')" title="Calendar View">
 <i class="bi bi-calendar3"></i>
 </button>
 <button class="view-toggle-btn" id="btnListView" onclick="switchView('list')" title="List View">
 <i class="bi bi-list-ul"></i>
 </button>
 </div>
 </div>
</div>

{{-- ============================================================
 STAT CARDS
 ============================================================ --}}
<div class="row g-3 mb-4">
 <div class="col-6 col-lg-3">
 <div class="stat-card">
 <div class="stat-card-icon" style="background: rgba(99,102,241,0.12); color: #6366f1;">
 <i class="bi bi-collection"></i>
 </div>
 <div>
 <p class="stat-card-label">Total</p>
 <h3 class="stat-card-value">{{ $stats['total'] }}</h3>
 </div>
 </div>
 </div>
 <div class="col-6 col-lg-3">
 <div class="stat-card">
 <div class="stat-card-icon" style="background: rgba(245,158,11,0.12); color: #f59e0b;">
 <i class="bi bi-hourglass-split"></i>
 </div>
 <div>
 <p class="stat-card-label">Pending</p>
 <h3 class="stat-card-value">{{ $stats['pending'] }}</h3>
 </div>
 </div>
 </div>
 <div class="col-6 col-lg-3">
 <div class="stat-card">
 <div class="stat-card-icon" style="background: rgba(16,185,129,0.12); color: #10b981;">
 <i class="bi bi-check-circle"></i>
 </div>
 <div>
 <p class="stat-card-label">Approved</p>
 <h3 class="stat-card-value">{{ $stats['approved'] }}</h3>
 </div>
 </div>
 </div>
 <div class="col-6 col-lg-3">
 <div class="stat-card">
 <div class="stat-card-icon" style="background: rgba(239,68,68,0.12); color: #ef4444;">
 <i class="bi bi-x-circle"></i>
 </div>
 <div>
 <p class="stat-card-label">Denied</p>
 <h3 class="stat-card-value">{{ $stats['denied'] }}</h3>
 </div>
 </div>
 </div>
</div>

{{-- ============================================================
 CALENDAR VIEW (primary)
 ============================================================ --}}
<div id="calendarView">
 <div class="cal-wrap card border-0 shadow-sm rounded-4 overflow-hidden">
 {{-- Calendar Toolbar --}}
 <div class="cal-toolbar">
 <div class="d-flex align-items-center gap-2">
 <button class="cal-nav-btn" id="calPrev"><i class="bi bi-chevron-left"></i></button>
 <button class="cal-nav-btn" id="calNext"><i class="bi bi-chevron-right"></i></button>
 <button class="cal-today-btn" id="calToday">Today</button>
 <h5 class="cal-title mb-0" id="calTitle"></h5>
 </div>
 <div class="cal-view-btns">
 <button class="cal-view-btn active" data-view="dayGridMonth">Month</button>
 <button class="cal-view-btn" data-view="timeGridWeek">Week</button>
 <button class="cal-view-btn" data-view="timeGridDay">Day</button>
 <button class="cal-view-btn" data-view="listYear">Agenda</button>
 </div>
 </div>
 {{-- Legend --}}
 <div class="cal-legend">
 <span class="legend-dot" style="background:#f59e0b;"></span><span class="legend-label">Pending</span>
 <span class="legend-dot" style="background:#10b981;"></span><span class="legend-label">Approved</span>
 <span class="legend-dot" style="background:#0284c7; opacity:.72;"></span><span class="legend-label">Completed</span>
 <span class="legend-dot" style="background:#94a3b8;"></span><span class="legend-label">Denied / Cancelled</span>
 <span class="legend-dot legend-dot--stripe"></span><span class="legend-label">Blackout</span>
 </div>
 {{-- FullCalendar mount point --}}
 <div id="adminCalendar" class="p-3 p-md-4"></div>
 </div>
</div>

{{-- ============================================================
 LIST VIEW (search, filters, sort, pagination)
 ============================================================ --}}
<div id="listView" class="d-none">
 <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
 <div class="card-header bg-white py-3 border-0 px-4">
 <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
 <h5 class="mb-0 fw-bold">All Reservations</h5>
 <div class="d-flex gap-2 flex-wrap">
 @foreach(['all','pending','approved','denied','cancelled'] as $s)
 <a href="{{ route('admin.reservations.index', array_merge(['status' => $s], request()->only(['search','facility_id','date_from','date_to','sort_by','sort_dir','per_page']))) }}"
 class="btn btn-sm px-3 fw-bold {{ $status == $s ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill">
 {{ ucfirst($s) }}
 </a>
 @endforeach
 </div>
 </div>
 {{-- Search + filter bar --}}
 <form method="GET" action="{{ route('admin.reservations.index') }}" class="row g-2 mt-3 align-items-end">
 <input type="hidden" name="status" value="{{ $status }}">
 <div class="col-12 col-md-4">
 <label class="form-label small fw-bold text-muted mb-1">
 <i class="bi bi-search"></i> Search
 </label>
 <input type="search" name="search" value="{{ $search }}"
 class="form-control form-control-sm rounded-3"
 placeholder="Requester name, email, reason, reference...">
 </div>
 <div class="col-6 col-md-2">
 <label class="form-label small fw-bold text-muted mb-1">
 <i class="bi bi-building"></i> Facility
 </label>
 <select name="facility_id" class="form-select form-select-sm rounded-3">
 <option value="">All facilities</option>
 @foreach($facilities as $f)
 <option value="{{ $f->id }}" {{ $facility_id == (string)$f->id ? 'selected' : '' }}>{{ $f->name }}</option>
 @endforeach
 </select>
 </div>
 <div class="col-6 col-md-2">
 <label class="form-label small fw-bold text-muted mb-1">From date</label>
 <input type="date" name="date_from" value="{{ $date_from }}" class="form-control form-control-sm rounded-3">
 </div>
 <div class="col-6 col-md-2">
 <label class="form-label small fw-bold text-muted mb-1">To date</label>
 <input type="date" name="date_to" value="{{ $date_to }}" class="form-control form-control-sm rounded-3">
 </div>
 <div class="col-6 col-md-2 d-flex gap-2">
 <button type="submit" class="btn btn-sm btn-primary rounded-3 fw-bold flex-grow-1">
 <i class="bi bi-funnel-fill"></i> Filter
 </button>
 @if($search || $facility_id || $date_from || $date_to)
 <a href="{{ route('admin.reservations.index', ['status' => $status]) }}" class="btn btn-sm btn-outline-secondary rounded-3">
 <i class="bi bi-x-lg"></i>
 </a>
 @endif
 </div>
 </form>
 </div>

 <div class="card-body p-0">
 <div class="table-responsive">
 <table class="table table-hover align-middle mb-0">
 <thead class="bg-light">
 <tr>
 <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">ID</th>
 <th class="py-3 text-uppercase small fw-bold text-muted">Requester</th>
 <th class="py-3 text-uppercase small fw-bold text-muted">Facility</th>
 <th class="py-3 text-uppercase small fw-bold text-muted">
 <a href="{{ route('admin.reservations.index', array_merge(request()->all(), ['sort_by' => $sort_by == 'start_date' && $sort_dir == 'asc' ? 'start_date' : 'start_date', 'sort_dir' => ($sort_by == 'start_date' && $sort_dir == 'asc') ? 'desc' : 'asc', 'page' => 1])) }}"
 class="text-decoration-none text-muted d-inline-flex align-items-center gap-1">
 Schedule
 @if($sort_by == 'start_date')
 <i class="bi {{ $sort_dir == 'asc' ? 'bi-sort-down' : 'bi-sort-up' }} small"></i>
 @endif
 </a>
 </th>
 <th class="py-3 text-uppercase small fw-bold text-muted">Attendees</th>
 <th class="py-3 text-uppercase small fw-bold text-muted">
 <a href="{{ route('admin.reservations.index', array_merge(request()->all(), ['sort_by' => 'status', 'sort_dir' => ($sort_by == 'status' && $sort_dir == 'asc') ? 'desc' : 'asc', 'page' => 1])) }}"
 class="text-decoration-none text-muted d-inline-flex align-items-center gap-1">
 Status
 @if($sort_by == 'status')
 <i class="bi {{ $sort_dir == 'asc' ? 'bi-sort-down' : 'bi-sort-up' }} small"></i>
 @endif
 </a>
 </th>
 <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Action</th>
 </tr>
 </thead>
 <tbody>
 @forelse($reservations as $reservation)
 <tr>
 <td class="ps-4 fw-bold text-nowrap" style="font-family: 'JetBrains Mono', monospace;">#{{ str_pad($reservation->id, 4, '0', STR_PAD_LEFT) }}</td>
 <td style="max-width: 220px; min-width: 140px;">
 <div class="d-flex align-items-center gap-2" style="min-width: 0;">
 <x-user-avatar :user="$reservation->user" size="sm" class="flex-shrink-0" />
 <div style="min-width: 0; overflow: hidden;">
 <p class="mb-0 fw-semibold small text-truncate" title="{{ $reservation->user->name }}">{{ $reservation->user->name }}</p>
 <p class="mb-0 text-muted text-truncate" style="font-size:11px;" title="{{ $reservation->user->email }}">{{ $reservation->user->email }}</p>
 </div>
 </div>
 </td>
 <td class="fw-semibold small" style="max-width: 180px; min-width: 120px;">
 <span class="d-inline-block text-truncate w-100" title="{{ $reservation->facility->name }}">{{ $reservation->facility->name }}</span>
 </td>
 <td class="small text-nowrap">
 <p class="mb-0"><span class="text-muted">From:</span> {{ $reservation->start_date->format('M d, Y') }}</p>
 <p class="mb-0"><span class="text-muted">To:</span> {{ $reservation->end_date->format('M d, Y') }}</p>
 </td>
 <td class="text-nowrap">
 <span class="badge bg-light text-dark border rounded-pill fw-bold">{{ $reservation->number_of_people }} pax</span>
 </td>
 <td class="text-nowrap">
 @php
 $badgeMap = [
 'pending' => 'bg-warning bg-opacity-10 text-warning',
 'approved' => 'bg-success bg-opacity-10 text-success',
 'denied' => 'bg-danger bg-opacity-10 text-danger',
 'cancelled' => 'bg-secondary bg-opacity-10 text-secondary',
 'expired_unpaid_cancelled' => 'bg-secondary bg-opacity-10 text-secondary',
 ];
 $cls = $badgeMap[$reservation->status] ?? 'bg-secondary bg-opacity-10 text-secondary';
 @endphp
 <span class="badge {{ $cls }} px-3 py-2 rounded-pill small fw-bold">
 {{ $reservation->status_label }}
 </span>
 </td>
 <td class="pe-4 text-end text-nowrap">
 <button class="btn btn-sm btn-light border rounded-3 px-3 fw-bold"
 onclick="openReservationModal({{ json_encode([
 'reservationId' => $reservation->id,
 'status' => $reservation->status,
 'statusLabel' => $reservation->status_label,
 'userName' => $reservation->user->name,
 'userEmail' => $reservation->user->email,
 'reservedByName' => $reservation->reserved_by_name,
 'reservedByEmail' => $reservation->reserved_by_email,
 'reservedByPhone' => $reservation->reserved_by_phone,
 'eventTitle' => $reservation->event_title,
 'clientTypeLabel' => $reservation->client_type_label,
 'endUserName' => $reservation->end_user_name,
 'endUserType' => $reservation->end_user_type,
 'paymentMethod' => $reservation->payment_method,
 'arrangements' => $reservation->arrangements,
 'facilityName' => $reservation->facility->name,
 'facilityLocation' => $reservation->facility->location,
 'startDate' => $reservation->start_date->format('M d, Y'),
 'endDate' => $reservation->end_date->format('M d, Y'),
 'startTime' => $reservation->start_time?->format('g:i A'),
 'endTime' => $reservation->end_time?->format('g:i A'),
 'numberOfPeople' => $reservation->number_of_people,
 'reason' => $reservation->reason,
 'adminRemarks' => $reservation->admin_remarks,
 'totalPrice' => $reservation->total_price ? '₱'.number_format($reservation->total_price,2) : null,
 'approveUrl' => route('admin.reservations.approve', $reservation->id),
 'denyUrl' => route('admin.reservations.deny', $reservation->id),
 'cancelUrl' => route('admin.reservations.cancel', $reservation->id),
                                                        'documents' => $reservation->documents->map(fn($doc) => [
                                                            'id' => $doc->id,
                                                            'original_name' => $doc->original_name,
                                                            'file_type' => $doc->file_type,
                                                            'file_size' => $doc->file_size,
                                                            'formatted_size' => $doc->formatted_size,
                                                            'file_category' => $doc->file_category,
                                                            'icon_class' => $doc->icon_class,
                                                            'view_url' => route('admin.reservations.documents.view', [$reservation->id, $doc->id]),
                                                            'download_url' => route('admin.reservations.documents.download', [$reservation->id, $doc->id]),
                                                        ]),
 ]) }})">
 Review
 </button>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="7" class="text-center py-5 text-muted">
 <i class="bi bi-inbox" style="font-size:3rem;"></i>
 <p class="mt-3">No reservations match your filters.</p>
 <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 mt-2">Clear all filters</a>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>
 <div class="card-footer bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
 <p class="mb-0 text-muted small">Showing {{ $reservations->firstItem() ?? 0 }} to {{ $reservations->lastItem() ?? 0 }} of {{ $reservations->total() }} results</p>
 <div class="d-flex align-items-center gap-3">
 <form method="GET" action="{{ route('admin.reservations.index') }}" class="d-flex align-items-center gap-2 mb-0">
 @foreach(request()->except(['per_page','page']) as $k => $v)
 <input type="hidden" name="{{ $k }}" value="{{ $v }}">
 @endforeach
 <select name="per_page" class="form-select form-select-sm rounded-3 w-auto" onchange="this.form.submit()">
 @foreach([10,15,25,50] as $pp)
 <option value="{{ $pp }}" {{ (int)$per_page == $pp ? 'selected' : '' }}>{{ $pp }} / page</option>
 @endforeach
 </select>
 </form>
 {{ $reservations->links() }}
 </div>
 </div>
 </div>
</div>

{{-- ============================================================
 RESERVATION DETAIL OFFCANVAS============================================================ --}}
<div class="offcanvas offcanvas-end reservation-offcanvas" tabindex="-1" id="reservationOffcanvas" aria-labelledby="reservationOffcanvasLabel">

 {{-- Header --}}
 <div class="offcanvas-header border-bottom px-4 py-3">
 <div>
 <h5 class="offcanvas-title fw-black mb-0" id="reservationOffcanvasLabel">
 Reservation <span id="oc-id" class="text-muted fw-normal"></span>
 </h5>
 <span id="oc-status-badge" class="badge rounded-pill mt-1 px-3 py-1"></span>
 </div>
 <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
 </div>

 <div class="offcanvas-body p-0">
	{{-- Detail Panels --}}
	<div class="p-4 d-flex flex-column gap-3">

		{{-- Event Info --}}
		<div class="oc-section">
			<div class="d-flex justify-content-between align-items-start mb-2 gap-2">
				<p class="oc-section-label mb-0"><i class="bi bi-card-heading me-1"></i> Event Details</p>
				<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 flex-shrink-0" id="oc-client-type"></span>
			</div>
			<p class="oc-section-value mb-1" id="oc-event-title"></p>
			<div class="d-flex align-items-center gap-2 mt-2">
				<span class="badge bg-light text-dark border rounded-pill fw-bold flex-shrink-0" id="oc-people"></span>
				<span class="text-muted small">Attendees</span>
			</div>
		</div>

		{{-- Requester --}}
		<div class="oc-section">
			<p class="oc-section-label"><i class="bi bi-person me-1"></i> Contact Person</p>
			<p class="oc-section-value mb-0" id="oc-reserved-name"></p>
			<p class="oc-section-sub mb-0" id="oc-reserved-email"></p>
			<p class="oc-section-sub" id="oc-reserved-phone"></p>

			<div class="mt-2 pt-2 border-top">
				<p class="oc-section-label mb-0" style="font-size: 0.65rem;">Submitted By (Account)</p>
				<p class="oc-section-sub mb-0" id="oc-user-name"></p>
			</div>
		</div>

		{{-- End User --}}
		<div class="oc-section" id="oc-end-user-wrap">
			<p class="oc-section-label"><i class="bi bi-building-check me-1"></i> End User</p>
			<p class="oc-section-value mb-0" id="oc-end-user-name"></p>
			<p class="oc-section-sub" id="oc-end-user-type"></p>
		</div>

		{{-- Facility & Schedule --}}
		<div class="oc-section">
			<p class="oc-section-label"><i class="bi bi-building me-1"></i> Facility &amp; Schedule</p>
			<p class="oc-section-value mb-0" id="oc-facility-name"></p>
			<p class="oc-section-sub mb-3" id="oc-facility-location"></p>

			<div class="d-flex align-items-center gap-2 p-2 bg-white rounded border border-light">
				<div class="flex-fill" style="min-width: 0;">
					<p class="oc-section-sub mb-0" style="font-size: 0.7rem;">From</p>
					<p class="oc-section-value mb-0 text-truncate" style="font-size: 0.85rem;" id="oc-start-date"></p>
					<p class="oc-section-sub mb-0 text-truncate" style="font-size: 0.75rem;" id="oc-start-time"></p>
				</div>
				<i class="bi bi-arrow-right text-muted flex-shrink-0"></i>
				<div class="flex-fill" style="min-width: 0;">
					<p class="oc-section-sub mb-0" style="font-size: 0.7rem;">To</p>
					<p class="oc-section-value mb-0 text-truncate" style="font-size: 0.85rem;" id="oc-end-date"></p>
					<p class="oc-section-sub mb-0 text-truncate" style="font-size: 0.75rem;" id="oc-end-time"></p>
				</div>
			</div>
		</div>

		{{-- Arrangements --}}
		<div class="oc-section d-none" id="oc-arrangements-wrap">
			<p class="oc-section-label"><i class="bi bi-tools me-1"></i> Logistical Requirements</p>
			<ul class="list-unstyled mb-0" id="oc-arrangements-list" style="font-size: 0.85rem; color: #334155;"></ul>
		</div>

		{{-- Price & Payment --}}
		<div class="d-flex gap-2">
			<div class="oc-section flex-fill" style="min-width: 0;">
				<p class="oc-section-label"><i class="bi bi-credit-card me-1"></i> Payment Method</p>
				<p class="oc-section-value mb-0" id="oc-payment-method" style="text-transform: capitalize;"></p>
			</div>
			<div class="oc-section flex-fill" style="min-width: 0;" id="oc-price-wrap">
				<p class="oc-section-label"><i class="bi bi-currency-exchange me-1"></i> Total Price</p>
				<p class="oc-section-value mb-0" id="oc-price"></p>
			</div>
		</div>

		{{-- Purpose --}}
		<div class="oc-section">
			<p class="oc-section-label"><i class="bi bi-chat-left-text me-1"></i> Purpose / Reason</p>
			<p class="oc-section-value mb-0 fw-normal" id="oc-reason" style="font-style: italic;"></p>
		</div>

		{{-- Admin Remarks (shown for non-pending) --}}
		<div class="oc-section d-none" id="oc-remarks-section">
			<p class="oc-section-label"><i class="bi bi-shield-check me-1"></i> Admin Remarks</p>
			<p class="oc-section-value mb-0 fw-normal" id="oc-remarks" style="font-style: italic;"></p>
		</div>

		{{-- Attached Documents (Request letters / Proposals / Endorsements) --}}
		<div class="oc-section" id="oc-documents-section">
			<div class="d-flex justify-content-between align-items-center mb-2 gap-2">
				<p class="oc-section-label mb-0"><i class="bi bi-file-earmark-arrow-up me-1"></i> Supporting Documents</p>
				<span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-2 flex-shrink-0" id="oc-doc-count">0</span>
			</div>
			<div id="oc-documents-list" class="d-flex flex-column gap-2">
				<!-- Dynamically populated document items -->
			</div>
		</div>

	</div>

	<hr class="m-0">

 {{-- ── ACTION ZONE ── --}}
 <div class="p-4" id="oc-action-zone">

 {{-- PENDING: Approve / Deny --}}
 <div id="oc-pending-actions" class="d-none">
 <p class="fw-bold small text-muted text-uppercase mb-3" style="letter-spacing:.05em;">Review Action</p>

 {{-- Step 1: Choose --}}
 <div id="oc-step-choose" class="d-grid gap-2">
 <button class="btn btn-success fw-bold py-2" onclick="ocShowStep('approve')">
 <i class="bi bi-check-circle me-2"></i> Approve Reservation
 </button>
 <button class="btn btn-outline-danger fw-bold py-2" onclick="ocShowStep('deny')">
 <i class="bi bi-x-circle me-2"></i> Deny Reservation
 </button>
 </div>

 {{-- Step 2a: Confirm Approve --}}
 <div id="oc-step-approve" class="d-none">
 <form id="oc-approve-form" method="POST" data-spa-skip>
 @csrf
 <div class="alert alert-success border-0 rounded-3 small mb-3">
 <i class="bi bi-info-circle me-1"></i> Confirm approval. An optional note will be visible to the requester.
 </div>
 <div class="form-floating mb-3">
 <textarea class="form-control" id="oc-approve-remarks" name="admin_remarks"
 placeholder="Optional note" style="height:90px;"></textarea>
 <label for="oc-approve-remarks">Optional note to requester</label>
 </div>
 <div class="d-grid gap-2">
 <button type="submit" class="btn btn-success fw-bold py-2">
 <i class="bi bi-check-circle-fill me-2"></i> Confirm Approval
 </button>
 <button type="button" class="btn btn-light border fw-bold" onclick="ocShowStep('choose')">
 Back
 </button>
 </div>
 </form>
 </div>

 {{-- Step 2b: Confirm Deny --}}
 <div id="oc-step-deny" class="d-none">
 <form id="oc-deny-form" method="POST" data-spa-skip>
 @csrf
 <div class="form-floating mb-3">
 <textarea class="form-control border-danger border-opacity-25"
 id="oc-deny-remarks" name="admin_remarks"
 placeholder="Reason for denial" required
 style="height:90px; background: rgba(239,68,68,.04);"></textarea>
 <label for="oc-deny-remarks" class="text-danger">Reason for Denial <span class="text-danger">*</span></label>
 </div>
 <div class="d-grid gap-2">
 <button type="submit" class="btn btn-danger fw-bold py-2">
 <i class="bi bi-exclamation-triangle me-2"></i> Confirm Denial
 </button>
 <button type="button" class="btn btn-light border fw-bold" onclick="ocShowStep('choose')">
 Back
 </button>
 </div>
 </form>
 </div>
 </div>

 {{-- APPROVED: Cancel --}}
 <div id="oc-approved-actions" class="d-none">
 <p class="fw-bold small text-muted text-uppercase mb-3" style="letter-spacing:.05em;">Administrative Action</p>

 <div id="oc-cancel-choose">
 <div class="alert alert-warning border-0 rounded-3 small mb-3">
 <i class="bi bi-check-circle-fill text-success me-1"></i>
 This reservation is currently <strong>approved</strong>. You may cancel it if needed.
 </div>
 <button class="btn btn-outline-secondary fw-bold w-100 py-2" onclick="ocShowCancel()">
 <i class="bi bi-slash-circle me-2"></i> Cancel This Reservation
 </button>
 </div>

 <div id="oc-cancel-confirm" class="d-none">
 <form id="oc-cancel-form" method="POST" data-spa-skip>
 @csrf
 <div class="alert alert-danger border-0 rounded-3 small mb-3">
 <i class="bi bi-exclamation-triangle-fill me-1"></i>
 Cancelling an approved reservation will notify the requester. This action cannot be undone easily.
 </div>
 <div class="form-floating mb-3">
 <textarea class="form-control border-danger border-opacity-25"
 id="oc-cancel-remarks" name="admin_remarks"
 placeholder="Cancellation reason" required
 style="height:90px; background: rgba(239,68,68,.04);"></textarea>
 <label for="oc-cancel-remarks" class="text-danger">Cancellation Reason <span class="text-danger">*</span></label>
 </div>
 <div class="d-grid gap-2">
 <button type="submit" class="btn btn-danger fw-bold py-2">
 <i class="bi bi-slash-circle me-2"></i> Confirm Cancellation
 </button>
 <button type="button" class="btn btn-light border fw-bold" onclick="ocHideCancel()">
 Back
 </button>
 </div>
 </form>
 </div>
 </div>

 {{-- READ-ONLY (denied / cancelled / blackout) --}}
 <div id="oc-readonly-actions" class="d-none">
 <div class="alert alert-light border rounded-3 small mb-0 text-muted text-center py-4">
 <i class="bi bi-lock display-6 d-block mb-2 opacity-50"></i>
 No active actions available for this reservation.
 </div>
 </div>

 </div>{{-- /action zone --}}
 </div>{{-- /offcanvas-body --}}
 </div>{{-- /offcanvas --}}

 {{-- Error toast: surfaces silent JS failures in the detail offcanvas --}}
 <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:1090">
 <div id="oc-error-toast" class="toast align-items-center text-bg-danger border-0 d-none" role="alert" aria-live="assertive" aria-atomic="true">
 <div class="d-flex">
 <div class="toast-body"></div>
 <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
  </div>
 </div>
 </div>

	{{-- In-Browser Document Viewer Modal for Staff/Admin --}}
	<div class="modal fade" id="docViewerModal" tabindex="-1" aria-labelledby="docViewerModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 90vw; height: 90vh;">
			<div class="modal-content border-0 shadow-lg h-100 rounded-4 overflow-hidden">
				<div class="modal-header bg-dark text-white px-4 py-3 border-0">
					<div class="d-flex align-items-center gap-2 overflow-hidden me-auto">
						<span id="doc-modal-icon" class="fs-5"></span>
						<h6 class="modal-title fw-bold text-truncate mb-0" id="docViewerModalLabel">Document Viewer</h6>
						<span class="badge bg-secondary rounded-pill px-2 py-1 ms-2" id="doc-modal-meta"></span>
					</div>
					<div class="d-flex align-items-center gap-2">
						<a href="#" id="doc-modal-download-btn" class="btn btn-sm btn-outline-light rounded-pill px-3" download>
							<i class="bi bi-download me-1"></i> Download
						</a>
						<a href="#" id="doc-modal-newtab-btn" class="btn btn-sm btn-outline-light rounded-pill px-3" target="_blank" rel="noopener">
							<i class="bi bi-box-arrow-up-right me-1"></i> Open Tab
						</a>
						<button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
				</div>
				<div class="modal-body p-0 d-flex flex-column align-items-center justify-content-center bg-light position-relative h-100" id="doc-modal-body">
					<div id="doc-viewer-loading" class="text-center py-5">
						<div class="spinner-border text-primary" role="status"></div>
						<p class="text-muted mt-2 mb-0 small">Loading document preview…</p>
					</div>
					<iframe id="doc-iframe" class="w-100 h-100 border-0 d-none" title="Document Preview"></iframe>
					<div id="doc-image-container" class="w-100 h-100 d-none overflow-auto d-flex align-items-center justify-content-center p-3">
						<img id="doc-img" src="" alt="Document Preview" class="img-fluid rounded shadow-sm" style="max-height: 80vh; object-fit: contain;">
					</div>
					<div id="doc-fallback-container" class="text-center p-5 d-none">
						<i id="doc-fallback-icon" class="bi bi-file-earmark-word text-primary display-3 mb-3 d-block"></i>
						<h5 class="fw-bold mb-2" id="doc-fallback-filename">Document</h5>
						<p class="text-muted small mb-4" id="doc-fallback-message">
							In-browser preview is best supported for PDF and Image files.<br>
							Word documents (.doc, .docx) can be downloaded or opened with your desktop software.
						</p>
						<div class="d-flex justify-content-center gap-2">
							<a href="#" id="doc-fallback-download-btn" class="btn btn-primary rounded-pill px-4">
								<i class="bi bi-download me-1"></i> Download Document
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

@push('styles')
<style>
/* ── Base ── */
.tracking-tight { letter-spacing: -0.02em; }
.fw-black { font-weight: 900; }
/* ── Stat Cards ── */
.stat-card {
 background: #fff;
 border: 0;
 border-radius: 16px;
 box-shadow: 0 2px 12px rgba(0,0,0,.06);
 padding: 1.1rem 1.25rem;
 display: flex;
 align-items: center;
 gap: 1rem;
 transition: transform .2s, box-shadow .2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.1); }
.stat-card-icon { width: 44px; height: 44px; border-radius: 12px; display: flex;
 align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
.stat-card-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; margin-bottom: 2px; }
.stat-card-value { font-size: 1.8rem; font-weight: 900; color: #1e293b; line-height: 1; margin: 0; }
/* ── View Toggle ── */
.view-toggle-group { display: flex; background: #f1f5f9; border-radius: 10px; padding: 3px; gap: 2px; }
.view-toggle-btn { background: transparent; border: none; width: 36px; height: 36px; border-radius: 8px; color: #64748b; cursor: pointer; transition: all .2s; display: flex; align-items: center; justify-content: center; font-size: .95rem; }
.view-toggle-btn.active { background: #fff; color: #1e293b; box-shadow: 0 1px 4px rgba(0,0,0,.12); }
/* ── Calendar Wrap ── */
.cal-wrap { border-radius: 20px !important; }
/* ── Calendar Toolbar ── */
.cal-toolbar {
 display: flex;
 justify-content: space-between;
 align-items: center;
 flex-wrap: wrap;
 gap: .75rem;
 padding: 1rem 1.5rem;
 border-bottom: 1px solid #e2e8f0;
 background: #fff;
}
.cal-nav-btn { background: #f1f5f9; border: none; width: 34px; height: 34px; border-radius: 8px; color: #334155; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .15s; }
.cal-nav-btn:hover { background: #e2e8f0; }
.cal-today-btn { background: #f1f5f9; border: none; height: 34px; padding: 0 14px; border-radius: 8px; font-weight: 600; font-size: .85rem; color: #334155; cursor: pointer; transition: all .15s; }
.cal-today-btn:hover { background: #e2e8f0; }
.cal-title { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-left: .5rem; }
.cal-view-btns { display: flex; background: #f1f5f9; border-radius: 10px; padding: 3px; gap: 2px; }
.cal-view-btn { background: transparent; border: none; padding: 6px 12px; border-radius: 8px; font-size: .78rem; font-weight: 600; color: #64748b; cursor: pointer; transition: all .2s; }
.cal-view-btn.active { background: #fff; color: #1e293b; box-shadow: 0 1px 4px rgba(0,0,0,.12); }
/* ── Legend ── */
.cal-legend { display: flex; align-items: center; gap: .6rem 1.2rem; flex-wrap: wrap; padding: .65rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.legend-dot--stripe { background: repeating-linear-gradient(45deg, #fca5a5 0, #fca5a5 3px, transparent 3px, transparent 7px); border-radius: 2px; width: 18px; height: 10px; }
.legend-label { font-size: .75rem; font-weight: 600; color: #475569; }
/* ── FullCalendar Overrides ── */
#adminCalendar { font-family: 'Figtree', sans-serif !important; }
.fc .fc-toolbar { display: none !important; } /* we use our own */
.fc .fc-scrollgrid { border-radius: 0; border: none; }
.fc .fc-scrollgrid td, .fc .fc-scrollgrid th { border-color: #e2e8f0 !important; }
.fc .fc-col-header-cell { background: #f8fafc; }
.fc .fc-col-header-cell-cushion { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #64748b; padding: 10px 0; text-decoration: none; }
.fc .fc-daygrid-day-number { font-size: .85rem; font-weight: 600; color: #475569; text-decoration: none; padding: 4px 8px; }
.fc .fc-daygrid-day.fc-day-today { background: rgba(99,102,241,.04) !important; }
.fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number { color: #6366f1; background: rgba(99,102,241,.12); border-radius: 6px; }
.fc .fc-event {
	border-radius: 6px !important;
	border: none !important;
	font-size: .78rem !important;
	font-weight: 600 !important;
	padding: 2px 6px !important;
	cursor: pointer;
	box-shadow: 0 1px 4px rgba(0,0,0,.12);
	margin-bottom: 1px !important;
}
.fc .fc-event:hover { filter: brightness(.92); }
.fc .fc-event-main {
	overflow: hidden !important;
	text-overflow: ellipsis !important;
	white-space: nowrap !important;
	min-width: 0 !important;
}
.fc .fc-event-title {
	overflow: hidden !important;
	text-overflow: ellipsis !important;
	white-space: nowrap !important;
}
.fc .fc-daygrid-block-event:not(.fc-event-end) {
	border-top-right-radius: 0 !important;
	border-bottom-right-radius: 0 !important;
}
.fc .fc-daygrid-block-event:not(.fc-event-start) {
	border-top-left-radius: 0 !important;
	border-bottom-left-radius: 0 !important;
}
.fc .fc-list-event:hover td { background: #f8fafc; }
.fc .fc-list-event-title { cursor: pointer; font-weight: 600; overflow-wrap: anywhere; word-break: break-word; }
.fc .fc-daygrid-more-link { font-size: .75rem; font-weight: 700; }
.fc .fc-timegrid-slot { height: 2.5rem; }
.fc .fc-timegrid-slot-label { font-size: .72rem; color: #94a3b8; }
.fc-bg-event { border-radius: 0 !important; opacity: .25 !important; }
/* ── Past Day Darkening ── */
.fc .fc-daygrid-day.fc-day-past { background-color: rgba(15, 23, 42, 0.038) !important; }
.fc .fc-daygrid-day.fc-day-past .fc-daygrid-day-number { opacity: 0.45; }
.fc .fc-timegrid-col.fc-day-past { background: rgba(15, 23, 42, 0.025) !important; }
.fc .fc-list-day.fc-day-past .fc-list-day-cushion { background: rgba(15, 23, 42, 0.06); color: #94a3b8; }
/* ── Completed Event Style ── */
.fc-event-completed {
    background-color: #0284c7 !important;
    border-color: #0284c7 !important;
    opacity: 0.72 !important;
}
.fc-event-completed .fc-event-title,
.fc-event-completed .fc-list-event-title {
    text-decoration: line-through;
    text-decoration-color: rgba(255,255,255,0.5);
}
/* ── Completed offcanvas badge ── */
.status-completed { background: rgba(2,132,199,.12); color: #0369a1; }
/* ── List Avatar ── */
.list-avatar { width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, var(--brand-primary,#6366f1), var(--brand-secondary,#8b5cf6)); color: #fff; font-weight: 700; font-size: .8rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-family: 'JetBrains Mono', monospace; }
/* ── Offcanvas Layout & Text Refitting ── */
.reservation-offcanvas {
	width: 450px !important;
	max-width: 100vw !important;
	overflow-x: hidden !important;
	box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15) !important;
}
.reservation-offcanvas .offcanvas-header {
	min-width: 0 !important;
	overflow: hidden !important;
	flex-shrink: 0 !important;
}
.reservation-offcanvas .offcanvas-title {
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
	font-size: 1.15rem !important;
}
.reservation-offcanvas .offcanvas-body {
	overflow-x: hidden !important;
	overflow-y: auto !important;
	max-width: 100% !important;
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
	scrollbar-width: thin;
	scrollbar-color: #cbd5e1 transparent;
}
.reservation-offcanvas .offcanvas-body::-webkit-scrollbar {
	width: 6px;
}
.reservation-offcanvas .offcanvas-body::-webkit-scrollbar-thumb {
	background-color: #cbd5e1;
	border-radius: 9999px;
}
.reservation-offcanvas .offcanvas-body::-webkit-scrollbar-track {
	background: transparent;
}
.reservation-offcanvas .offcanvas-body > * {
	min-width: 0 !important;
	max-width: 100% !important;
}
.oc-section {
	background: #f8fafc;
	border: 1px solid #e2e8f0;
	border-radius: 12px;
	padding: .875rem 1rem;
	min-width: 0 !important;
	max-width: 100% !important;
	overflow: hidden !important;
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
}
.oc-section-label {
	font-size: .72rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: .06em;
	color: #94a3b8;
	margin-bottom: .4rem;
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
}
.oc-section-value {
	font-weight: 700;
	color: #1e293b;
	font-size: .95rem;
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
	line-height: 1.45;
}
.oc-section-sub {
	font-size: .8rem;
	color: #64748b;
	margin-bottom: 0;
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
	line-height: 1.4;
}
#oc-event-title {
	font-size: 1.05rem !important;
	font-weight: 800 !important;
	color: #0f172a !important;
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
	line-height: 1.4 !important;
}
#oc-reason, #oc-remarks {
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
	white-space: pre-wrap !important;
	font-size: .88rem !important;
	line-height: 1.5 !important;
	color: #334155 !important;
}
#oc-arrangements-list li {
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
	line-height: 1.4 !important;
}
#oc-reserved-name, #oc-reserved-email, #oc-reserved-phone, #oc-user-name,
#oc-end-user-name, #oc-end-user-type, #oc-facility-name, #oc-facility-location,
#oc-payment-method, #oc-price {
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
}
#oc-action-zone {
	flex-shrink: 0 !important;
	min-width: 0 !important;
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
}
#reservationOffcanvas .alert {
	overflow-wrap: anywhere !important;
	word-break: break-word !important;
}
/* Status badge colours */
.status-pending { background: rgba(245,158,11,.12); color: #d97706; }
.status-approved { background: rgba(16,185,129,.12); color: #059669; }
.status-denied { background: rgba(148,163,184,.15); color: #475569; }
.status-cancelled { background: rgba(100,116,139,.15); color: #475569; }
@media (max-width: 576px) {
    .reservation-offcanvas { width: 100vw !important; }
}
</style>
@endpush

@push('scripts')
<meta name="calendar-events-url" content="{{ route('admin.reservations.calendarEvents') }}">
{{-- FullCalendar v6 --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
(function() {
 'use strict';

 /* ── Globals ── */
 let calendarInst = null;
 const metaEl = document.querySelector('meta[name="calendar-events-url"]');
 const EVENTS_URL = metaEl ? metaEl.getAttribute('content') : '';

 /* ── Status palette ── */
 function statusBadgeCls(status) {
 const map = {
 pending: 'status-pending',
 approved: 'status-approved',
 denied: 'status-denied',
 cancelled: 'status-cancelled',
 expired_unpaid_cancelled: 'status-cancelled',
 completed: 'status-completed',
 };
 return map[status] || 'status-denied';
 }
 function statusTextColor(status) {
 const map = {
 pending: '#d97706',
 approved: '#059669',
 denied: '#475569',
 cancelled: '#475569',
 completed: '#0369a1',
 expired_unpaid_cancelled: '#475569',
 };
 return map[status] || '#475569';
 }

 /* ── View switcher ── */
 window.switchView = function(view) {
 const calendarView = document.getElementById('calendarView');
 const listView = document.getElementById('listView');
 const btnCal = document.getElementById('btnCalView');
 const btnList = document.getElementById('btnListView');
 if (!calendarView || !listView) return;
 if (view === 'list') {
 calendarView.classList.add('d-none');
 listView.classList.remove('d-none');
 if (btnCal) btnCal.classList.remove('active');
 if (btnList) btnList.classList.add('active');
 } else {
 listView.classList.add('d-none');
 calendarView.classList.remove('d-none');
 if (btnList) btnList.classList.remove('active');
 if (btnCal) btnCal.classList.add('active');
 if (!calendarInst) { initCalendar(); }
 else { calendarInst.updateSize(); }
 }
 try { localStorage.setItem('reservationViewMode', view); } catch (e) {}
 };

 /* ── Custom calendar toolbar wiring ── */
 function updateCalTitle() {
 const el = document.getElementById('calTitle');
 if (!el || !calendarInst) return;
 const date = calendarInst.getDate();
 el.textContent = calendarInst.view.title;
 }
 function wireToolbar() {
 const prev = document.getElementById('calPrev');
 const next = document.getElementById('calNext');
 const today = document.getElementById('calToday');
 if (prev) prev.addEventListener('click', function() { if (calendarInst) { calendarInst.prev(); updateCalTitle(); } });
 if (next) next.addEventListener('click', function() { if (calendarInst) { calendarInst.next(); updateCalTitle(); } });
 if (today) today.addEventListener('click', function() { if (calendarInst) { calendarInst.today(); updateCalTitle(); } });
 document.querySelectorAll('.cal-view-btn').forEach(function(btn) {
 btn.addEventListener('click', function() {
 if (!calendarInst) return;
 const v = btn.getAttribute('data-view');
 calendarInst.changeView(v);
 updateCalTitle();
 document.querySelectorAll('.cal-view-btn').forEach(function(b) { b.classList.remove('active'); });
 btn.classList.add('active');
 });
 });
 }

 /* ── Event chip builders ── */
 function isBlackout(ev) { return ev.extendedProps && ev.extendedProps.type === 'blackout'; }

 function eventDidMount(info) {
 const ev = info.event;
 const el = info.el;
 el.style.cursor = 'pointer';
 if (isBlackout(ev)) {
 const isList = info.view.type.indexOf('list') === 0;
 if (isList) {
 el.classList.remove('fc-bg-event');
 el.style.backgroundColor = '';
 el.style.borderColor = '';
 el.style.opacity = '1';
 return;
 }
 el.classList.add('fc-bg-event');
 el.style.backgroundColor = '#fca5a5';
 return;
 }
 const p = ev.extendedProps || {};
 if (p.status && p.status !== 'approved' && p.status !== 'pending') return;
 const today = new Date(); today.setHours(0,0,0,0);
 const evtEnd = ev.end
 ? new Date(ev.end.getTime() - 86400000)
 : ev.start
 ? new Date(ev.start.getTime())
 : null;
 if (evtEnd && evtEnd < today) {
 el.classList.add('fc-event-completed');
 const titleEl = el.querySelector('.fc-event-title');
 if (titleEl) {
 titleEl.textContent = '\u2714 ' + titleEl.textContent.replace(/^[\u2713\u2715\u23F3\u2298\u2714]\s*/, '');
 }
 const dotEl = el.querySelector('.fc-list-event-dot');
 if (dotEl) dotEl.style.borderColor = '#0284c7';
 }
 }

 function eventClick(info) {
 const ev = info.event;
 if (isBlackout(ev)) {
 openBlackoutModal(ev);
 return;
 }
 const p = ev.extendedProps || {};
 if (p.type === 'reservation' && p.reservationId) {
 openReservationModal({
 reservationId: p.reservationId,
 status: p.status,
 statusLabel: p.statusLabel,
 userName: p.userName,
 userEmail: p.userEmail,
 reservedByName: p.reservedByName,
 reservedByEmail: p.reservedByEmail,
 reservedByPhone: p.reservedByPhone,
 eventTitle: p.eventTitle || ev.title,
 clientTypeLabel: p.clientTypeLabel,
 endUserName: p.endUserName,
 endUserType: p.endUserType,
 paymentMethod: p.paymentMethod,
 arrangements: p.arrangements,
 facilityName: p.facilityName,
 facilityLocation: p.facilityLocation,
 startDate: p.startDate,
 endDate: p.endDate,
 startTime: p.startTime,
 endTime: p.endTime,
 numberOfPeople: p.numberOfPeople,
 reason: p.reason,
 adminRemarks: p.adminRemarks,
 totalPrice: p.totalPrice,
 approveUrl: p.approveUrl,
 denyUrl: p.denyUrl,
 cancelUrl: p.cancelUrl,
 documents: p.documents || [],
 });
 }
 }

 /* ── Blackout modal (simple) ── */
 function openBlackoutModal(ev) {
 const titleEl = document.getElementById('reservationOffcanvasLabel');
 const idEl = document.getElementById('oc-id');
 const badge = document.getElementById('oc-status-badge');
 const actionZone = document.getElementById('oc-action-zone');
 if (titleEl) titleEl.innerHTML = '<i class="bi bi-calendar-x me-1"></i> Blackout Date';
 if (idEl) idEl.textContent = '';
 if (badge) {
 badge.textContent = 'Blackout';
 badge.className = 'badge rounded-pill mt-1 px-3 py-1 status-denied';
 }
 const hide = function(id) { const x = document.getElementById(id); if (x) x.classList.add('d-none'); };
 const showValue = function(id, text) { const x = document.getElementById(id); if (x) { x.classList.remove('d-none'); x.textContent = text || '\u2014'; } };
 ['oc-client-type','oc-event-title','oc-people','oc-reserved-name','oc-reserved-email','oc-reserved-phone','oc-user-name','oc-end-user-name','oc-end-user-type','oc-facility-name','oc-facility-location','oc-start-date','oc-start-time','oc-end-date','oc-end-time','oc-payment-method','oc-price','oc-reason','oc-remarks'].forEach(hide);
 const p = ev.extendedProps || {};
 showValue('oc-facility-name', p.facilityName || ev.title || 'Facility Blackout');
 showValue('oc-facility-location', p.facilityLocation || '');
 showValue('oc-start-date', ev.start ? ev.start.toLocaleDateString() : '\u2014');
 showValue('oc-end-date', ev.end ? ev.end.toLocaleDateString() : '\u2014');
 showValue('oc-reason', p.reason);
 const endUserWrap = document.getElementById('oc-end-user-wrap');
 const arrWrap = document.getElementById('oc-arrangements-wrap');
 const remarksSection = document.getElementById('oc-remarks-section');
 const priceWrap = document.getElementById('oc-price-wrap');
 if (endUserWrap) endUserWrap.classList.add('d-none');
 if (arrWrap) arrWrap.classList.add('d-none');
 if (remarksSection) remarksSection.classList.add('d-none');
		const docSec = document.getElementById('oc-documents-section');
		if (docSec) docSec.classList.add('d-none');
 if (priceWrap) priceWrap.classList.add('d-none');
 	if (actionZone) actionZone.classList.add('d-none');
	const oc = document.getElementById('reservationOffcanvas');
	if (oc) {
		const ocBody = oc.querySelector('.offcanvas-body');
		if (ocBody) { ocBody.scrollTop = 0; ocBody.scrollLeft = 0; }
		new bootstrap.Offcanvas(oc).show();
	}
 }

 /* ── Open reservation detail offcanvas ── */
 window.openReservationModal = function(data) {
 if (!data) return;
 const set = function(id, text) { const el = document.getElementById(id); if (el) el.textContent = (text != null && text !== '') ? text : '\u2014'; };
 const hide = function(id) { const el = document.getElementById(id); if (el) el.classList.add('d-none'); };
 const show = function(id) { const el = document.getElementById(id); if (el) el.classList.remove('d-none'); };

 /* Reset header (may have been changed by a previous blackout open) */
 const labelEl = document.getElementById('reservationOffcanvasLabel');
 if (labelEl) labelEl.innerHTML = 'Reservation <span id="oc-id" class="text-muted fw-normal"></span>';
 set('oc-id', '#' + String(data.reservationId).padStart(4, '0'));
 const badge = document.getElementById('oc-status-badge');
 if (badge) {
 badge.textContent = data.statusLabel || (data.status ? String(data.status).charAt(0).toUpperCase() + String(data.status).slice(1) : '\u2014');
 badge.className = 'badge rounded-pill mt-1 px-3 py-1 ' + statusBadgeCls(data.status);
 }
 set('oc-client-type', data.clientTypeLabel);
 set('oc-event-title', data.eventTitle);
 set('oc-people', (data.numberOfPeople || 0) + ' pax');
 set('oc-reserved-name', data.reservedByName);
 set('oc-reserved-email', data.reservedByEmail);
 set('oc-reserved-phone', data.reservedByPhone);
 set('oc-user-name', data.userName);
 hide('oc-end-user-wrap');
 if (data.endUserName) { set('oc-end-user-name', data.endUserName); set('oc-end-user-type', data.endUserType); show('oc-end-user-wrap'); }
 set('oc-facility-name', data.facilityName);
 set('oc-facility-location', data.facilityLocation);
 set('oc-start-date', data.startDate);
 set('oc-start-time', data.startTime || '\u2014');
 set('oc-end-date', data.endDate);
 set('oc-end-time', data.endTime || '\u2014');
 set('oc-payment-method', data.paymentMethod || '\u2014');
 set('oc-price', data.totalPrice || '\u2014');
 set('oc-reason', data.reason);
 set('oc-remarks', data.adminRemarks);

 const arrWrap = document.getElementById('oc-arrangements-wrap');
 const arrList = document.getElementById('oc-arrangements-list');
 if (arrWrap && arrList) {
                var arrItems = [];
                var rawArrangements = data.arrangements;
                var parsedArrangements = rawArrangements;
                if (typeof rawArrangements === 'string') {
                    try {
                        parsedArrangements = rawArrangements.trim() ? JSON.parse(rawArrangements) : [];
                    } catch (e) {
                        parsedArrangements = rawArrangements.split('\n').map(function (s) { return s.trim(); }).filter(Boolean);
                    }
                }
                var arrangementList = Array.isArray(parsedArrangements)
                    ? parsedArrangements
                    : (parsedArrangements && typeof parsedArrangements === 'object'
                        ? Object.keys(parsedArrangements).map(function (key) {
                            return { name: key, value: parsedArrangements[key] };
                        })
                        : []);
                function arrangementText(item) {
                    if (item == null) return '';
                    if (typeof item === 'string' || typeof item === 'number') return String(item);
                    if (typeof item !== 'object') return '';
                    var nested = item.item && typeof item.item === 'object' ? item.item : null;
                    var name = item.name || item.item_name || item.label || item.title
                        || (nested && (nested.name || nested.item_name || nested.label))
                        || (item.item_id != null ? 'Item #' + item.item_id : '');
                    var quantity = item.quantity != null && item.quantity !== '' ? String(item.quantity) + 'x ' : '';
                    var unitPrice = item.unit_price != null ? item.unit_price : item.price;
                    var price = unitPrice != null && Number(unitPrice) > 0
                        ? ' - \u20b1' + Number(unitPrice).toLocaleString()
                        : '';
                    var value = item.value != null && typeof item.value !== 'object' ? ': ' + String(item.value) : '';
                    return quantity + String(name || '') + value + price;
                }
                arrItems = arrangementList.map(arrangementText).filter(Boolean);
 if (arrItems.length) {
 arrList.innerHTML = arrItems.map(function(it){ return '<li class="mb-1"><i class="bi bi-check2 me-1 text-success"></i>' + it + '</li>'; }).join('');
 arrWrap.classList.remove('d-none');
 } else { arrWrap.classList.add('d-none'); }
 }

 if (data.adminRemarks && data.status !== 'pending') { show('oc-remarks-section'); } else { hide('oc-remarks-section'); }

		/* Attached documents */
		const docSection = document.getElementById('oc-documents-section');
		const docList = document.getElementById('oc-documents-list');
		const docCount = document.getElementById('oc-doc-count');
		let docs = Array.isArray(data.documents) ? data.documents : [];

		function renderDocList(items) {
			if (docCount) docCount.textContent = items.length;
			if (!items || items.length === 0) {
				docList.innerHTML = '<p class="text-muted small mb-0 fst-italic">No supporting documents attached.</p>';
			} else {
				docList.innerHTML = items.map(function(doc) {
					const safeDoc = JSON.stringify(doc).replace(/"/g, '&quot;');
					const iconHtml = '<i class="bi ' + (doc.icon_class || 'bi-file-earmark-text') + ' fs-5 flex-shrink-0"></i>';
					return '<div class="d-flex align-items-center justify-content-between p-2 rounded border bg-white shadow-sm gap-2" style="min-width: 0;">' +
						'<div class="d-flex align-items-center gap-2" style="min-width: 0; flex: 1; overflow: hidden;">' +
							iconHtml +
							'<div style="min-width: 0; flex: 1; overflow: hidden;">' +
								'<p class="mb-0 fw-bold small text-truncate" title="' + (doc.original_name || '') + '">' + (doc.original_name || 'Document') + '</p>' +
								'<span class="text-muted" style="font-size: 0.7rem;">' + (doc.formatted_size || '') + '</span>' +
							'</div>' +
						'</div>' +
						'<div class="d-flex align-items-center gap-1 flex-shrink-0 ms-1">' +
							'<button type="button" class="btn btn-sm btn-primary rounded-pill px-2 py-1" onclick=\'openDocViewer(' + safeDoc + ')\' title="View in browser" style="font-size: 0.75rem; white-space: nowrap;">' +
								'<i class="bi bi-eye me-1"></i> View' +
							'</button>' +
							'<a href="' + doc.download_url + '" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" download title="Download file" style="font-size: 0.75rem;">' +
								'<i class="bi bi-download"></i>' +
							'</a>' +
						'</div>' +
					'</div>';
				}).join('');
			}
		}

		if (docSection && docList) {
			renderDocList(docs);
			docSection.classList.remove('d-none');

			// If docs array is empty but we have a reservationId, attempt to fetch live details as safety-net
			if (docs.length === 0 && data.reservationId) {
				fetch('/admin/reservations/' + data.reservationId, {
					headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
				})
				.then(function(res) { return res.ok ? res.json() : null; })
				.then(function(resData) {
					if (resData && Array.isArray(resData.documents) && resData.documents.length > 0) {
						renderDocList(resData.documents);
					}
				})
				.catch(function() {});
			}
		}
 if (data.totalPrice) { show('oc-price-wrap'); } else { hide('oc-price-wrap'); }

 /* Action zone by status */
 const actionZone = document.getElementById('oc-action-zone');
 const pendingActions = document.getElementById('oc-pending-actions');
 const approvedActions = document.getElementById('oc-approved-actions');
 const readonlyActions = document.getElementById('oc-readonly-actions');
 if (actionZone) actionZone.classList.remove('d-none');
 const st = (data.status || '').toLowerCase();
 [pendingActions, approvedActions, readonlyActions].forEach(function(el) { if (el) el.classList.add('d-none'); });
 if (st === 'pending') {
 if (pendingActions) pendingActions.classList.remove('d-none');
 ocShowStep('choose');
 } else if (st === 'approved') {
 if (approvedActions) approvedActions.classList.remove('d-none');
 ocHideCancel();
 } else {
 if (readonlyActions) readonlyActions.classList.remove('d-none');
 }

 /* Wire forms */
 const forms = ['oc-approve-form','oc-deny-form','oc-cancel-form'];
 forms.forEach(function(fid) {
 const form = document.getElementById(fid);
 if (!form) return;
 form.onsubmit = function() { return submitOffcanvasForm(form, data); };
 });

 	const oc = document.getElementById('reservationOffcanvas');
	if (oc) {
		const ocBody = oc.querySelector('.offcanvas-body');
		if (ocBody) { ocBody.scrollTop = 0; ocBody.scrollLeft = 0; }
		new bootstrap.Offcanvas(oc).show();
	}
};

 /* ── Offcanvas step helpers ── */
 window.ocShowStep = function(step) {
 ['oc-step-choose','oc-step-approve','oc-step-deny'].forEach(function(id) { const el = document.getElementById(id); if (el) el.classList.add('d-none'); });
 const map = { choose: 'oc-step-choose', approve: 'oc-step-approve', deny: 'oc-step-deny' };
 if (map[step]) { const el = document.getElementById(map[step]); if (el) el.classList.remove('d-none'); }
 };
 window.ocShowCancel = function() {
 const choose = document.getElementById('oc-cancel-choose');
 const confirm = document.getElementById('oc-cancel-confirm');
 if (choose) choose.classList.add('d-none');
 if (confirm) confirm.classList.remove('d-none');
 };
 window.ocHideCancel = function() {
 const choose = document.getElementById('oc-cancel-choose');
 const confirm = document.getElementById('oc-cancel-confirm');
 if (confirm) confirm.classList.add('d-none');
 if (choose) choose.classList.remove('d-none');
 };

 /* ── Form submission (SPA-skip aware) ── */
 function submitOffcanvasForm(form, data) {
 ocShowErr(null);
 const urlMap = { 'oc-approve-form': data.approveUrl, 'oc-deny-form': data.denyUrl, 'oc-cancel-form': data.cancelUrl };
 const url = urlMap[form.id];
 if (!url) { ocShowErr('No action URL available.'); return false; }
 const body = new URLSearchParams(new FormData(form));
 fetch(url, {
 method: 'POST',
 headers: {
 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') || {}).content || '',
 'Accept': 'application/json',
 },
 body: body,
 })
 .then(function(r) { return r.json().catch(function() { return { ok: false }; }); })
 .then(function(j) {
 if (j.success || j.ok) {
 const oc = bootstrap.Offcanvas.getInstance(document.getElementById('reservationOffcanvas'));
 if (oc) oc.hide();
 setTimeout(function() { window.location.reload(); }, 400);
 } else {
 ocShowErr(j.message || 'Action failed. Please try again.');
 }
 })
 .catch(function() { ocShowErr('Network error. Please try again.'); });
 return false;
 }
 function ocShowErr(msg) {
 const toast = document.getElementById('oc-error-toast');
 if (!toast) return;
 if (!msg) { toast.classList.add('d-none'); return; }
 const body = toast.querySelector('.toast-body');
 if (body) body.textContent = msg;
 toast.classList.remove('d-none');
 const t = new bootstrap.Toast(toast);
 t.show();
 }

 /* ── Init FullCalendar ── */
 function initCalendar() {
 if (typeof FullCalendar === 'undefined' || !EVENTS_URL) return;
 const el = document.getElementById('adminCalendar');
 if (!el) return;
 calendarInst = new FullCalendar.Calendar(el, {
 initialView: 'dayGridMonth',
 headerToolbar: false,
 events: {
 url: EVENTS_URL,
 method: 'GET',
 },
 eventDidMount: eventDidMount,
 eventClick: eventClick,
 datesSet: updateCalTitle,
 dayMaxEventRows: 3,
 height: 'auto',
 });
 calendarInst.render();
 updateCalTitle();
 wireToolbar();
 }

 /* ── Boot ── */
 function onPageLoad(fn) {
 if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', fn); }
 else { fn(); }
 }

 onPageLoad(function() {
 initCalendar();
 setTimeout(function() {
 const savedView = (function() { try { return localStorage.getItem('reservationViewMode') || 'calendar'; } catch (e) { return 'calendar'; } })();
 switchView(savedView);
 }, 50);
 });

	/* ── In-browser document preview modal ── */
	window.openDocViewer = function(doc) {
		if (!doc) return;
		const modalEl = document.getElementById('docViewerModal');
		if (!modalEl) return;

		const modalTitle = document.getElementById('docViewerModalLabel');
		const modalMeta = document.getElementById('doc-modal-meta');
		const modalIcon = document.getElementById('doc-modal-icon');
		const downloadBtn = document.getElementById('doc-modal-download-btn');
		const newTabBtn = document.getElementById('doc-modal-newtab-btn');
		const loadingEl = document.getElementById('doc-viewer-loading');
		const iframeEl = document.getElementById('doc-iframe');
		const imageContainer = document.getElementById('doc-image-container');
		const imgEl = document.getElementById('doc-img');
		const fallbackContainer = document.getElementById('doc-fallback-container');
		const fallbackFilename = document.getElementById('doc-fallback-filename');
		const fallbackDownloadBtn = document.getElementById('doc-fallback-download-btn');

		if (modalTitle) modalTitle.textContent = doc.original_name || 'Document';
		if (modalMeta) modalMeta.textContent = doc.formatted_size || '';
		if (modalIcon) modalIcon.innerHTML = '<i class="bi ' + (doc.icon_class || 'bi-file-earmark-text') + '"></i>';
		if (downloadBtn) {
			downloadBtn.href = doc.download_url;
			downloadBtn.setAttribute('download', doc.original_name || 'document');
		}
		if (newTabBtn) newTabBtn.href = doc.view_url;
		if (fallbackFilename) fallbackFilename.textContent = doc.original_name || 'Document';
		if (fallbackDownloadBtn) {
			fallbackDownloadBtn.href = doc.download_url;
			fallbackDownloadBtn.setAttribute('download', doc.original_name || 'document');
		}

		// Reset all viewing containers
		[loadingEl, iframeEl, imageContainer, fallbackContainer].forEach(function(el) {
			if (el) el.classList.add('d-none');
		});
		if (iframeEl) iframeEl.src = '';
		if (imgEl) imgEl.src = '';

		const category = doc.file_category || 'other';

		if (category === 'image') {
			if (imgEl && imageContainer) {
				imgEl.onload = function() {
					if (loadingEl) loadingEl.classList.add('d-none');
					imageContainer.classList.remove('d-none');
				};
				imgEl.onerror = function() {
					if (loadingEl) loadingEl.classList.add('d-none');
					if (fallbackContainer) fallbackContainer.classList.remove('d-none');
				};
				if (loadingEl) loadingEl.classList.remove('d-none');
				imgEl.src = doc.view_url;
			}
		} else if (category === 'pdf') {
			if (iframeEl) {
				if (loadingEl) loadingEl.classList.remove('d-none');
				iframeEl.onload = function() {
					if (loadingEl) loadingEl.classList.add('d-none');
					iframeEl.classList.remove('d-none');
				};
				iframeEl.src = doc.view_url;
			}
		} else {
			// Word and other file formats
			if (fallbackContainer) fallbackContainer.classList.remove('d-none');
		}

		const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
		bsModal.show();
	};

	document.addEventListener('DOMContentLoaded', function() {
		const modalEl = document.getElementById('docViewerModal');
		if (modalEl) {
			modalEl.addEventListener('hidden.bs.modal', function() {
				const iframeEl = document.getElementById('doc-iframe');
				const imgEl = document.getElementById('doc-img');
				if (iframeEl) iframeEl.src = '';
				if (imgEl) imgEl.src = '';
			});
		}

		// Deep link: auto-open reservation offcanvas if ?open={id} query parameter is present
		const urlParams = new URLSearchParams(window.location.search);
		const openResId = urlParams.get('open');
		if (openResId) {
			fetch('/admin/reservations/' + openResId, {
				headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
			})
			.then(function(res) { return res.ok ? res.json() : null; })
			.then(function(resData) {
				if (resData && resData.reservationId) {
					openReservationModal(resData);
				}
			})
			.catch(function() {});
		}
	});

})();
</script>
@endpush
