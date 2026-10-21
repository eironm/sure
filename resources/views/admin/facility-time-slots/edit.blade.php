@extends('layouts.admin')

@section('title', 'Edit Time Slot')
@section('header', 'Edit Time Slot')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Time Slot for {{ $facility->name }}</h5>
                    <a href="{{ route('admin.facilities.time-slots.index', $facility) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.facilities.time-slots.update', [$facility, $timeSlot]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Day of Week -->
                            <div class="col-md-12">
                                <label for="day_of_week" class="form-label fw-bold text-muted small text-uppercase">Day of the Week</label>
                                <select name="day_of_week" id="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror" required>
                                    <option value="" disabled>Select a day</option>
                                    @foreach($days as $index => $day)
                                        <option value="{{ $index }}" {{ old('day_of_week', $timeSlot->day_of_week) == $index ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                                @error('day_of_week')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Time Range -->
                            <div class="col-md-6">
                                <label for="start_time" class="form-label fw-bold text-muted small text-uppercase">Start Time</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-clock"></i></span>
                                    <input type="time" name="start_time" id="start_time" class="form-control border-start-0 @error('start_time') is-invalid @enderror" value="{{ old('start_time', \Illuminate\Support\Str::of($timeSlot->start_time)->substr(0, 5)) }}" required>
                                </div>
                                @error('start_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_time" class="form-label fw-bold text-muted small text-uppercase">End Time</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-clock-fill"></i></span>
                                    <input type="time" name="end_time" id="end_time" class="form-control border-start-0 @error('end_time') is-invalid @enderror" value="{{ old('end_time', \Illuminate\Support\Str::of($timeSlot->end_time)->substr(0, 5)) }}" required>
                                </div>
                                @error('end_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Capacity & Price -->
                            <div class="col-md-6">
                                <label for="capacity" class="form-label fw-bold text-muted small text-uppercase">Simultaneous Bookings</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-layers"></i></span>
                                    <input type="number" name="capacity" id="capacity" class="form-control border-start-0 @error('capacity') is-invalid @enderror" value="{{ old('capacity', $timeSlot->capacity) }}" min="1" required>
                                </div>
                                <small class="text-muted">How many reservations may share this slot at the same time — <strong>not</strong> the venue's headcount. Keep this at 1 for exclusive venues (halls, gyms, rooms booked as a whole).</small>
                                @error('capacity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="price_per_hour" class="form-label fw-bold text-muted small text-uppercase">Price per Hour (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">₱</span>
                                    <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" class="form-control border-start-0 @error('price_per_hour') is-invalid @enderror" value="{{ old('price_per_hour', $timeSlot->price_per_hour) }}" placeholder="Leave empty to use base price">
                                </div>
                                <small class="text-muted">If empty, the facility's base price (₱{{ number_format($facility->price_per_hour, 2) }}) will be used.</small>
                                @error('price_per_hour')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Duration hint -->
                            <div class="col-12">
                                <div class="alert alert-light border d-flex align-items-center gap-2 mb-0" id="durationHint">
                                    <i class="bi bi-hourglass-split text-muted"></i>
                                    <span class="small" id="durationText">—</span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <div class="form-check form-switch p-3 bg-light rounded border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $timeSlot->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">Active and Available for Booking</label>
                                    <p class="text-muted small mb-0 mt-1">Inactive time slots will not be visible to guests during the reservation process.</p>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 mt-4">
                                <hr class="mb-4 opacity-10">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sure px-4 py-2">
                                        <i class="bi bi-check-lg me-1"></i> Update Time Slot
                                    </button>
                                    <a href="{{ route('admin.facilities.time-slots.index', $facility) }}" class="btn btn-light px-4 py-2 border">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const start = document.getElementById('start_time');
    const end   = document.getElementById('end_time');
    const text  = document.getElementById('durationText');
    const hint  = document.getElementById('durationHint');
    if (!start || !end || !text) return;

    function toMinutes(v) {
        if (!v) return null;
        const [h, m] = v.split(':').map(Number);
        return h * 60 + m;
    }
    function update() {
        const s = toMinutes(start.value);
        const e = toMinutes(end.value);
        hint.classList.remove('alert-danger', 'alert-light');
        if (s === null || e === null) {
            hint.classList.add('alert-light');
            text.textContent = 'Set a start and end time to see the slot duration.';
            return;
        }
        if (e <= s) {
            hint.classList.add('alert-danger');
            text.innerHTML = '<strong>End time must be after start time.</strong>';
            return;
        }
        const mins = e - s;
        const h = Math.floor(mins / 60), m = mins % 60;
        hint.classList.add('alert-light');
        text.innerHTML = `Slot duration: <strong>${h ? h + 'h ' : ''}${m ? m + 'm' : (h ? '' : '0m')}</strong>.`;
    }
    start.addEventListener('input', update);
    end.addEventListener('input', update);
    update();
})();
</script>
@endsection
