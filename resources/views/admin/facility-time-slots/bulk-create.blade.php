@extends('layouts.admin')

@section('title', 'Bulk Create Time Slots')
@section('header', 'Bulk Create Time Slots')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-stack me-2 text-warning"></i>Bulk Create Time Slots for {{ $facility->name }}</h5>
                    <a href="{{ route('admin.facilities.time-slots.index', $facility) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">
                        <i class="bi bi-info-circle me-1"></i>
                        Quickly generate hourly time slots across multiple days with consistent capacity and pricing. Existing duplicates are skipped automatically.
                    </p>

                    <form action="{{ route('admin.facilities.time-slots.bulk-store', $facility) }}" method="POST" id="bulkForm">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Days Selection -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <label class="form-label fw-bold text-muted small text-uppercase mb-0">Select Days</label>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-day-preset="weekdays">Weekdays</button>
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-day-preset="weekends">Weekends</button>
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-day-preset="all">All</button>
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-day-preset="clear">Clear</button>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($days as $index => $day)
                                        <div>
                                            <input type="checkbox" class="btn-check bulk-day" name="days[]" value="{{ $index }}" id="day_{{ $index }}" {{ in_array($index, old('days', [])) ? 'checked' : '' }}>
                                            <label class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold day-pill" for="day_{{ $index }}">{{ $day }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted">Hourly slots will be created for every selected day.</small>
                                @error('days')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Time Range -->
                            <div class="col-md-6">
                                <label for="start_hour" class="form-label fw-bold text-muted small text-uppercase">Start Hour</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-clock"></i></span>
                                    <select name="start_hour" id="start_hour" class="form-select border-start-0 @error('start_hour') is-invalid @enderror" required>
                                        <option value="" disabled selected>Select start hour</option>
                                        @for($i = 0; $i < 24; $i++)
                                            <option value="{{ $i }}" {{ old('start_hour') == $i ? 'selected' : '' }}>{{ sprintf('%02d:00', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                @error('start_hour')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_hour" class="form-label fw-bold text-muted small text-uppercase">End Hour</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-clock-fill"></i></span>
                                    <select name="end_hour" id="end_hour" class="form-select border-start-0 @error('end_hour') is-invalid @enderror" required>
                                        <option value="" disabled selected>Select end hour</option>
                                        @for($i = 1; $i <= 24; $i++)
                                            <option value="{{ $i }}" {{ old('end_hour') == $i ? 'selected' : '' }}>{{ sprintf('%02d:00', $i) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <small class="text-muted">Creates hourly slots from start to end hour</small>
                                @error('end_hour')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Capacity & Price -->
                            <div class="col-md-6">
                                <label for="capacity" class="form-label fw-bold text-muted small text-uppercase">Simultaneous Bookings</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-layers"></i></span>
                                    <input type="number" name="capacity" id="capacity" class="form-control border-start-0 @error('capacity') is-invalid @enderror" value="{{ old('capacity', 1) }}" min="1" required>
                                </div>
                                <small class="text-muted">How many reservations may share each slot at the same time — <strong>not</strong> the venue's headcount. Keep this at 1 for exclusive venues (halls, gyms, rooms booked as a whole).</small>
                                @error('capacity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="price_per_hour" class="form-label fw-bold text-muted small text-uppercase">Price per Hour (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">₱</span>
                                    <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" class="form-control border-start-0 @error('price_per_hour') is-invalid @enderror" value="{{ old('price_per_hour', $facility->price_per_hour) }}" placeholder="Leave empty to use base price">
                                </div>
                                <small class="text-muted">If empty, the facility's base price (₱{{ number_format($facility->price_per_hour, 2) }}) will be used.</small>
                                @error('price_per_hour')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Live Preview -->
                            <div class="col-12">
                                <div class="bulk-preview border rounded-3 p-3" id="bulkPreview">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="fw-bold text-muted small text-uppercase"><i class="bi bi-eye me-1"></i> Preview</span>
                                        <span class="badge bg-primary rounded-pill px-3" id="bulkCount">0 slots</span>
                                    </div>
                                    <p class="text-muted small mb-2" id="bulkSummary">Select days and an hour range to preview the slots that will be generated.</p>
                                    <div class="d-flex flex-wrap gap-1" id="bulkChips"></div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <div class="form-check form-switch p-3 bg-light rounded border">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">Active and Available for Booking</label>
                                    <p class="text-muted small mb-0 mt-1">All created time slots will have this status.</p>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 mt-4">
                                <hr class="mb-4 opacity-10">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sure px-4 py-2">
                                        <i class="bi bi-check-lg me-1"></i> Create Time Slots
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

<style>
    .btn-check:checked + .day-pill {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }
    .bulk-preview { background: #f8fafc; }
    .bulk-chip {
        font-size: 0.72rem; font-weight: 600;
        padding: 0.25rem 0.6rem; border-radius: 6px;
        background: #fff; border: 1px solid #e2e8f0; color: #475569;
    }
    .bulk-chip .bulk-chip-day { color: var(--accent); font-weight: 700; }
</style>

<script>
(function () {
    const form       = document.getElementById('bulkForm');
    if (!form) return;
    const dayBoxes   = Array.from(document.querySelectorAll('.bulk-day'));
    const startSel   = document.getElementById('start_hour');
    const endSel     = document.getElementById('end_hour');
    const countEl    = document.getElementById('bulkCount');
    const summaryEl  = document.getElementById('bulkSummary');
    const chipsEl    = document.getElementById('bulkChips');
    const dayNames   = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

    // Quick day presets
    document.querySelectorAll('[data-day-preset]').forEach(btn => {
        btn.addEventListener('click', () => {
            const preset = btn.dataset.dayPreset;
            dayBoxes.forEach(cb => {
                const v = parseInt(cb.value, 10);
                if (preset === 'all')       cb.checked = true;
                else if (preset === 'clear') cb.checked = false;
                else if (preset === 'weekdays') cb.checked = (v >= 1 && v <= 5);
                else if (preset === 'weekends') cb.checked = (v === 0 || v === 6);
            });
            updatePreview();
        });
    });

    function selectedDays() {
        return dayBoxes.filter(cb => cb.checked).map(cb => parseInt(cb.value, 10)).sort((a, b) => a - b);
    }

    function updatePreview() {
        const days  = selectedDays();
        const start = parseInt(startSel.value, 10);
        const end   = parseInt(endSel.value, 10);
        const validRange = !isNaN(start) && !isNaN(end) && end > start;
        const hours = validRange ? (end - start) : 0;
        const total = days.length * hours;

        countEl.textContent = total + (total === 1 ? ' slot' : ' slots');

        if (days.length === 0 || !validRange) {
            summaryEl.textContent = 'Select days and a valid hour range (end after start) to preview.';
            chipsEl.innerHTML = '';
            return;
        }

        summaryEl.innerHTML =
            `<strong>${total}</strong> hourly slot(s) — ${hours} hour(s) × ${days.length} day(s), ` +
            `from <strong>${String(start).padStart(2,'0')}:00</strong> to <strong>${String(end).padStart(2,'0')}:00</strong>.`;

        // Show up to first 24 generated ranges to keep it light
        const chips = [];
        outer:
        for (const d of days) {
            for (let h = start; h < end; h++) {
                chips.push(
                    `<span class="bulk-chip"><span class="bulk-chip-day">${dayNames[d]}</span> ` +
                    `${String(h).padStart(2,'0')}:00–${String(h+1).padStart(2,'0')}:00</span>`
                );
                if (chips.length >= 24) break outer;
            }
        }
        if (total > chips.length) chips.push(`<span class="bulk-chip">+${total - chips.length} more…</span>`);
        chipsEl.innerHTML = chips.join('');
    }

    dayBoxes.forEach(cb => cb.addEventListener('change', updatePreview));
    startSel.addEventListener('change', updatePreview);
    endSel.addEventListener('change', updatePreview);
    updatePreview(); // initial (restores old() state on validation error)
})();
</script>
@endsection
