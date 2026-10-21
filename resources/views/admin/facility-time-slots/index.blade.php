@extends('layouts.admin')

@section('title', 'Manage Time Slots')
@section('header', 'Manage Time Slots')

@section('content')
@include('components.delete-confirmation-modal')

{{-- Facility summary --}}
<div class="card rounded-4 mb-4">
    <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width: 52px; height: 52px; background: rgba(var(--accent-rgb), .1); color: var(--accent); font-size: 1.4rem;">
                <i class="bi bi-building"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-1">{{ $facility->name }}</h5>
                <div class="d-flex flex-wrap gap-3 small text-muted">
                    <span><i class="bi bi-geo-alt me-1"></i>{{ $facility->location ?? 'No location set' }}</span>
                    <span><i class="bi bi-people me-1"></i>{{ $facility->capacity ?? 'N/A' }} people</span>
                    <span><i class="bi bi-cash me-1"></i>₱{{ number_format($facility->price_per_hour ?? 0, 2) }}/hr base</span>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.facilities.index') }}" class="btn btn-light border fw-semibold rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Facilities
            </a>
            <a href="{{ route('admin.facilities.time-slots.bulk-create', $facility) }}" class="btn btn-light border fw-semibold rounded-pill px-3">
                <i class="bi bi-stack me-1"></i> Bulk Create
            </a>
            <a href="{{ route('admin.facilities.time-slots.create', $facility) }}" class="btn btn-sure rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Add Time Slot
            </a>
        </div>
    </div>
</div>

{{-- Slots table --}}
<div class="card rounded-4 overflow-hidden">
    <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2" style="color: var(--accent);"></i>Configured Time Slots</h6>
        <span class="text-muted small fw-semibold">{{ $timeSlots->total() }} slot{{ $timeSlots->total() !== 1 ? 's' : '' }}</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Day</th>
                    <th>Hours</th>
                    <th>Simultaneous Bookings</th>
                    <th>Price / Hour</th>
                    <th>Status</th>
                    <th class="text-end pe-4" style="width: 130px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($timeSlots as $slot)
                <tr>
                    <td class="ps-4">
                        <span class="badge rounded-pill fw-bold px-3 py-2"
                              style="background: rgba(var(--accent-rgb), .1); color: var(--accent); font-size: .72rem; letter-spacing: .04em;">
                            {{ strtoupper(substr($days[$slot->day_of_week], 0, 3)) }}
                        </span>
                        <span class="fw-semibold text-dark ms-1 d-none d-md-inline">{{ $days[$slot->day_of_week] }}</span>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}</span>
                        <span class="text-muted mx-1">–</span>
                        <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}</span>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark">{{ $slot->capacity }}</span>
                        <span class="text-muted small">{{ $slot->capacity == 1 ? '(exclusive)' : 'concurrent' }}</span>
                    </td>
                    <td>
                        @if($slot->price_per_hour)
                            <span class="fw-semibold text-dark">₱{{ number_format($slot->price_per_hour, 2) }}</span>
                        @else
                            <span class="text-muted small fst-italic">Base price</span>
                        @endif
                    </td>
                    <td>
                        @if($slot->is_active)
                            <span class="badge rounded-pill px-3" style="background: #ecfdf5; color: #047857;">
                                <i class="bi bi-check-circle-fill me-1"></i>Active
                            </span>
                        @else
                            <span class="badge rounded-pill px-3" style="background: #fef2f2; color: #b91c1c;">
                                <i class="bi bi-x-circle-fill me-1"></i>Inactive
                            </span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-inline-flex gap-2">
                            <a href="{{ route('admin.facilities.time-slots.edit', [$facility, $slot]) }}"
                               class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold" title="Edit slot">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.facilities.time-slots.destroy', [$facility, $slot]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 text-danger" title="Delete slot"
                                        onclick="showDeleteConfirmation(event, 'Time Slot', 'delete')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" style="width: 72px; height: 72px;">
                            <i class="bi bi-calendar-x text-muted opacity-50 fs-2"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">No time slots configured</h6>
                        <p class="text-muted small mb-3">Guests cannot book this facility until operating hours are defined.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.facilities.time-slots.create', $facility) }}" class="btn btn-sure rounded-pill px-4">
                                <i class="bi bi-plus-lg me-1"></i> Add a Time Slot
                            </a>
                            <a href="{{ route('admin.facilities.time-slots.bulk-create', $facility) }}" class="btn btn-light border rounded-pill px-4 fw-semibold">
                                <i class="bi bi-stack me-1"></i> Bulk Create
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($timeSlots->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-center">
            {{ $timeSlots->links() }}
        </div>
    @endif
</div>
@endsection
