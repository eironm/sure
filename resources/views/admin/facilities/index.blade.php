@extends('layouts.admin')

@section('title', 'Facilities Management')
@section('header', 'Facilities Management')

@section('content')
@include('components.delete-confirmation-modal')

{{-- Toolbar --}}
<div class="card rounded-4 mb-4">
    <div class="card-body py-3 px-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <form method="GET" action="{{ route('admin.facilities.index') }}" class="d-flex align-items-center gap-2 flex-grow-1 m-0">
            <div class="input-group" style="max-width: 320px;">
                <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0"
                       placeholder="Search name or location…" value="{{ request('search') }}">
            </div>
            <select name="availability" class="form-select" style="max-width: 170px;" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('availability') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
            @if(request()->hasAny(['search', 'availability']))
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold text-nowrap">
                    <i class="bi bi-x-lg me-1"></i>Clear
                </a>
            @endif
        </form>

        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small fw-semibold text-nowrap">{{ $facilities->total() }} facilit{{ $facilities->total() !== 1 ? 'ies' : 'y' }}</span>
            <a href="{{ route('admin.facilities.create') }}" class="btn btn-sure rounded-pill px-4 text-nowrap">
                <i class="bi bi-plus-lg me-1"></i> Add Facility
            </a>
        </div>
    </div>
</div>

{{-- Facility grid --}}
<div class="row g-4">
    @forelse($facilities as $facility)
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 rounded-4 overflow-hidden facility-card">
                {{-- Image --}}
                <div class="position-relative" style="aspect-ratio: 16/9; background: #f1f5f9;">
                    @if($facility->getMainImage())
                        <img src="{{ $facility->getMainImage() }}" alt="{{ $facility->name }}"
                             class="w-100 h-100" style="object-fit: cover;">
                    @else
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                            <i class="bi bi-image fs-2 opacity-50"></i>
                            <span class="small mt-1 opacity-75">No image</span>
                        </div>
                    @endif

                    {{-- Overlays --}}
                    <span class="position-absolute top-0 end-0 m-2 badge rounded-pill px-3 py-2 shadow-sm"
                          style="background: {{ $facility->is_available ? '#ecfdf5' : '#fef2f2' }}; color: {{ $facility->is_available ? '#047857' : '#b91c1c' }};">
                        {{ $facility->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                    @if($facility->category)
                        <span class="position-absolute top-0 start-0 m-2 badge rounded-pill px-3 py-2 shadow-sm text-white"
                              style="background-color: {{ $facility->category->color ?? '#64748b' }};">
                            {{ $facility->category->name }}
                        </span>
                    @endif
                </div>

                {{-- Body --}}
                <div class="card-body d-flex flex-column p-4">
                    <h6 class="fw-bold text-dark mb-1">{{ $facility->name }}</h6>
                    <div class="small text-muted mb-3 text-truncate">
                        <i class="bi bi-geo-alt me-1"></i>{{ $facility->location ?? 'Location not set' }}
                    </div>

                    <div class="d-flex justify-content-between text-center border rounded-3 py-2 px-1 mb-3 mt-auto" style="border-color: var(--line) !important;">
                        <div class="flex-fill border-end" style="border-color: var(--line) !important;">
                            <div class="fw-bold text-dark small">{{ $facility->capacity ?? '—' }}</div>
                            <div class="text-muted" style="font-size: .68rem;">People</div>
                        </div>
                        <div class="flex-fill border-end" style="border-color: var(--line) !important;">
                            <div class="fw-bold text-dark small">₱{{ number_format($facility->price_per_hour ?? 0, 0) }}</div>
                            <div class="text-muted" style="font-size: .68rem;">Per Hour</div>
                        </div>
                        <div class="flex-fill">
                            <div class="fw-bold text-dark small">{{ $facility->time_slots_count }}</div>
                            <div class="text-muted" style="font-size: .68rem;">Time Slots</div>
                        </div>
                    </div>

                    @if($facility->tags->isNotEmpty())
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach($facility->tags->take(4) as $tag)
                                <span class="badge rounded-pill" style="background-color: {{ $tag->color ?? '#64748b' }}; font-size: .65rem;">{{ $tag->name }}</span>
                            @endforeach
                            @if($facility->tags->count() > 4)
                                <span class="badge rounded-pill bg-light text-muted border" style="font-size: .65rem;">+{{ $facility->tags->count() - 4 }}</span>
                            @endif
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.facilities.time-slots.index', $facility->id) }}"
                           class="btn btn-sm btn-light border fw-semibold rounded-pill flex-grow-1">
                            <i class="bi bi-clock me-1"></i>Time Slots
                        </a>
                        <a href="{{ route('admin.facilities.edit', $facility->id) }}"
                           class="btn btn-sm btn-light border fw-semibold rounded-pill px-3" title="Edit facility">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('admin.facilities.destroy', $facility->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 text-danger" title="Delete facility"
                                    onclick="showDeleteConfirmation(event, 'Facility', 'delete')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card rounded-4 text-center py-5">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" style="width: 72px; height: 72px;">
                        <i class="bi bi-building text-muted opacity-50 fs-2"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">No facilities found</h6>
                    @if(request()->hasAny(['search', 'availability']))
                        <p class="text-muted small mb-3">No records match your current filters.</p>
                        <a href="{{ route('admin.facilities.index') }}" class="btn btn-sm btn-light border rounded-pill px-4 fw-semibold">Clear all filters</a>
                    @else
                        <p class="text-muted small mb-3">Register your first facility to start accepting reservations.</p>
                        <a href="{{ route('admin.facilities.create') }}" class="btn btn-sure rounded-pill px-4">
                            <i class="bi bi-plus-lg me-1"></i> Add Facility
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($facilities->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $facilities->links() }}
    </div>
@endif

<style>
    .facility-card { transition: transform .15s, box-shadow .15s; }
    .facility-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(15, 23, 42, .08) !important; }
</style>
@endsection
