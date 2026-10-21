@php
$deadlineBasis = old("settings.payment_deadline_basis", \App\Models\SystemSetting::getValue("payment_deadline_basis", "after_creation"));
$deadlineValue = old("settings.payment_deadline_value", \App\Models\SystemSetting::getValue("payment_deadline_value", "3"));
$deadlineUnit = old("settings.payment_deadline_unit", \App\Models\SystemSetting::getValue("payment_deadline_unit", "days"));
@endphp

<div class="setting-group-card mb-4" id="group-payment-deadline">
    <div class="setting-group-header"><i class="bi bi-hourglass-split"></i> Payment Deadline Policy <span class="sg-sub">Choose when the payment window begins.</span></div>
    <div class="setting-group-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6"><label class="form-label fw-semibold" for="payment_deadline_basis">Deadline starts from</label>
                <select class="form-select" id="payment_deadline_basis" name="settings[payment_deadline_basis]">
                    <option value="after_creation" {{ $deadlineBasis === "after_creation" ? "selected" : "" }}>After reservation creation</option>
                    <option value="before_event" {{ $deadlineBasis === "before_event" ? "selected" : "" }}>Before the event starts</option>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label fw-semibold" for="payment_deadline_value">Time window</label>
                <input class="form-control" type="number" min="1" max="3650" id="payment_deadline_value" name="settings[payment_deadline_value]" value="{{ $deadlineValue }}" required>
            </div>
            <div class="col-md-3"><label class="form-label fw-semibold" for="payment_deadline_unit">Unit</label>
                <select class="form-select" id="payment_deadline_unit" name="settings[payment_deadline_unit]">
                    <option value="hours" {{ $deadlineUnit === "hours" ? "selected" : "" }}>Hours</option>
                    <option value="days" {{ $deadlineUnit === "days" ? "selected" : "" }}>Days</option>
                </select>
            </div>
        </div>
        <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Before-event mode means payment is due X hours/days before the reservation starts.</p>
    </div>
</div>
