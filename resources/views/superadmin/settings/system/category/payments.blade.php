@extends('layouts.superadmin')

@section('title', 'System Settings — Payment Methods')

@section('content')

<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'system'])
<div class="branding-main">

@php
    // Current on/off state for each payment method toggle card.
    $cashEnabled = old('settings.payment_method_cash_enabled',
        \App\Models\SystemSetting::getValue('payment_method_cash_enabled', '1')) === '1';
    $paymongoEnabled = old('settings.payment_method_paymongo_enabled',
        \App\Models\SystemSetting::getValue('payment_method_paymongo_enabled', '1')) === '1';
@endphp

<div class="page-header">
    <div>
        <h1 class="ph-title">Payment Methods</h1>
        <p class="ph-sub">Toggle which payment options guests see in the reservation wizard.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Dashboard</a>
    </div>
</div>

<form action="{{ route('superadmin.settings.system.update') }}" method="POST">
    @csrf
    @method('PUT')

    @include('superadmin.settings.system.category._payment_deadline_policy')

    <div class="cms-grid">
        <div class="setting-group-card" id="group-payments">
            <div class="setting-group-header">
                <i class="bi bi-credit-card-2-front"></i>
                Payment Methods
                <span class="sg-sub">Toggle which payment options guests see in the reservation wizard.</span>
            </div>
            <div class="setting-group-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="payment-method-toggle-card {{ $cashEnabled ? 'enabled' : 'disabled' }}" id="pm-card-cash">
                            <div class="pm-card-icon pm-icon-cash"><i class="bi bi-cash-coin"></i></div>
                            <div class="pm-card-body">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div>
                                        <h6 class="fw-bold mb-1">Cash / Pay at Cashier</h6>
                                        <p class="text-muted small mb-0">Guests pay on-site at the cashier's office within the payment deadline window.</p>
                                    </div>
                                    <div class="pm-toggle-wrap form-check form-switch premium-switch mb-0 flex-shrink-0">
                                        <input type="hidden" name="settings[payment_method_cash_enabled]" value="0">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            role="switch"
                                            id="setting-payment_method_cash_enabled"
                                            name="settings[payment_method_cash_enabled]"
                                            value="1"
                                            {{ $cashEnabled ? 'checked' : '' }}
                                            onchange="updatePmCard(this, 'pm-card-cash')"
                                        >
                                    </div>
                                </div>
                                <div class="pm-status-chips mt-3">
                                    <span class="pm-chip-enabled {{ $cashEnabled ? '' : 'd-none' }}"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                                    <span class="pm-chip-disabled {{ $cashEnabled ? 'd-none' : '' }}"><i class="bi bi-x-circle-fill me-1"></i> Disabled — displayed as unavailable to guests</span>
                                </div>
                                <div class="pm-method-tags mt-2">
                                    <span class="pm-tag"><i class="bi bi-building me-1"></i>On-site only</span>
                                    <span class="pm-tag"><i class="bi bi-clock me-1"></i>Deadline-based</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="payment-method-toggle-card {{ $paymongoEnabled ? 'enabled' : 'disabled' }}" id="pm-card-paymongo">
                            <div class="pm-card-icon pm-icon-paymongo"><i class="bi bi-credit-card-2-front"></i></div>
                            <div class="pm-card-body">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div>
                                        <h6 class="fw-bold mb-1">Online Payment <span class="badge bg-primary bg-opacity-10 text-primary align-middle" style="font-size:.65rem;">PayMongo</span></h6>
                                        <p class="text-muted small mb-0">Guests pay digitally via GCash, Maya, credit/debit cards or internet banking.</p>
                                    </div>
                                    <div class="pm-toggle-wrap form-check form-switch premium-switch mb-0 flex-shrink-0">
                                        <input type="hidden" name="settings[payment_method_paymongo_enabled]" value="0">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            role="switch"
                                            id="setting-payment_method_paymongo_enabled"
                                            name="settings[payment_method_paymongo_enabled]"
                                            value="1"
                                            {{ $paymongoEnabled ? 'checked' : '' }}
                                            onchange="updatePmCard(this, 'pm-card-paymongo')"
                                        >
                                    </div>
                                </div>
                                <div class="pm-status-chips mt-3">
                                    <span class="pm-chip-enabled {{ $paymongoEnabled ? '' : 'd-none' }}"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                                    <span class="pm-chip-disabled {{ $paymongoEnabled ? 'd-none' : '' }}"><i class="bi bi-x-circle-fill me-1"></i> Disabled — displayed as unavailable to guests</span>
                                </div>
                                <div class="pm-method-tags mt-2">
                                    <span class="pm-tag"><i class="bi bi-phone me-1"></i>GCash</span>
                                    <span class="pm-tag"><i class="bi bi-phone me-1"></i>Maya</span>
                                    <span class="pm-tag"><i class="bi bi-credit-card me-1"></i>Cards</span>
                                    <span class="pm-tag"><i class="bi bi-bank me-1"></i>Banks</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning border-0 rounded-3 mt-4 d-flex align-items-center gap-3 {{ ($cashEnabled || $paymongoEnabled) ? 'd-none' : '' }}" id="pm-both-disabled-alert" {{ ($cashEnabled || $paymongoEnabled) ? 'style="display:none;"' : '' }}>
                    <i class="bi bi-exclamation-triangle-fill fs-4 text-warning flex-shrink-0"></i>
                    <div><strong>Warning:</strong> Both payment methods are disabled. Guests will be unable to submit reservations that require payment — keep at least one method enabled.</div>
                </div>
            </div>
        </div>
    </div>

    @include('partials._cms_save_bar', ['label' => 'Save Payment Settings'])
</form>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ── Payment method cards ─────────────────────────────────── */
    window.updatePmCard = function (checkbox, cardId) {
        const card    = document.getElementById(cardId);
        const chipOn  = card.querySelector('.pm-chip-enabled');
        const chipOff = card.querySelector('.pm-chip-disabled');

        if (checkbox.checked) {
            card.classList.remove('disabled');
            card.classList.add('enabled');
            chipOn.classList.remove('d-none');
            chipOff.classList.add('d-none');
        } else {
            card.classList.remove('enabled');
            card.classList.add('disabled');
            chipOn.classList.add('d-none');
            chipOff.classList.remove('d-none');
        }

        const cashEnabled     = document.getElementById('setting-payment_method_cash_enabled')?.checked;
        const paymongoEnabled = document.getElementById('setting-payment_method_paymongo_enabled')?.checked;
        const warningEl = document.getElementById('pm-both-disabled-alert');
        if (warningEl) warningEl.style.display = (!cashEnabled && !paymongoEnabled) ? '' : 'none';
    };
});
</script>
@endpush


