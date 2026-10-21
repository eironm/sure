@extends('layouts.admin')

@section('title', 'Blackout Dates')
@section('header', 'Facility Settings')

@section('content')
<div class="container-fluid p-0">
    <!-- Settings Navigation Tabs -->
    @include('admin.settings.nav-tabs')
    @include('components.delete-confirmation-modal')

    <div class="row g-4">
        <!-- Add Blackout Date Form -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 border-0">
                    <h5 class="mb-0 fw-bold">Add New Blackout Date</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <form action="{{ route('admin.settings.blackout-dates.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="facility_id" class="form-label small fw-bold text-muted text-uppercase">Facility (Optional)</label>
                            <select name="facility_id" id="facility_id" class="form-select rounded-3 border-light bg-light">
                                <option value="">Global (All Facilities)</option>
                                @foreach($facilities as $facility)
                                    <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text smaller">Leave empty to apply to all facilities.</div>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label small fw-bold text-muted text-uppercase">Start Date</label>
                            <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}" class="form-control rounded-3 border-light bg-light">
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label small fw-bold text-muted text-uppercase">End Date</label>
                            <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}" class="form-control rounded-3 border-light bg-light">
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label small fw-bold text-muted text-uppercase">Reason</label>
                            <input type="text" name="reason" id="reason" required placeholder="e.g., Maintenance, Holiday" class="form-control rounded-3 border-light bg-light">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-dark fw-bold rounded-3 py-2">
                                <i class="bi bi-plus-circle me-2"></i> Add Blackout Date
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Blackout Dates List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 border-0">
                    <h5 class="mb-0 fw-bold">Existing Blackout Dates</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Facility</th>
                                    <th class="py-3 text-uppercase small fw-bold text-muted">Dates</th>
                                    <th class="py-3 text-uppercase small fw-bold text-muted">Reason</th>
                                    <th class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blackoutDates as $blackout)
                                    <tr>
                                        <td class="ps-4">
                                            @if($blackout->facility)
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill small fw-bold">
                                                    {{ $blackout->facility->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-dark bg-opacity-10 text-dark px-2 py-1 rounded-pill small fw-bold">
                                                    Global
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small fw-bold">{{ $blackout->start_date->format('M d, Y') }}</div>
                                            <div class="text-muted smaller">to {{ $blackout->end_date->format('M d, Y') }}</div>
                                        </td>
                                        <td>
                                            <span class="small">{{ $blackout->reason }}</span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('admin.settings.blackout-dates.destroy', $blackout->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-3" onclick="showDeleteConfirmation(event, 'blackout date')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted small">
                                            <i class="bi bi-calendar-x d-block mb-2" style="font-size: 2rem;"></i>
                                            No blackout dates configured.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($blackoutDates->hasPages())
                        <div class="p-4 border-top">
                            {{ $blackoutDates->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
