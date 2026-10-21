@extends('layouts.cashier')
@section('title', 'Payment Statement Report')
@section('heading', 'Statement Report')

@section('content')
<style>
    /* ==========================================================================
       CASHIER STATEMENT REPORT — MODERN SAAS DESIGN SUITE
       ========================================================================== */
    :root {
        --csh-primary: #059669;
        --csh-primary-hover: #047857;
        --csh-ink: #0f172a;
        --csh-card-bg: #ffffff;
        --csh-border: #e2e8f0;
        --csh-text-main: #0f172a;
        --csh-text-muted: #64748b;
    }

    .csh-container {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }

    /* ── 1. Top Header & Actions ── */
    .csh-header-bar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.25rem;
        flex-wrap: wrap;
    }
    .csh-title-group h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--csh-text-main);
        letter-spacing: -0.02em;
        margin: 0;
    }
    .csh-title-group p {
        font-size: 0.82rem;
        color: var(--csh-text-muted);
        margin: 0.2rem 0 0.5rem 0;
    }
    .csh-chips-row {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .csh-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.22rem 0.65rem;
        border-radius: 99px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    .chip-range { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .chip-fac   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .chip-cash  { background: #faf5ff; color: #6d28d9; border: 1px solid #ddd6fe; }
    .chip-meth  { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }

    .csh-actions-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .csh-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 1rem;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.15s ease;
        height: 38px;
        box-sizing: border-box;
    }
    .csh-btn-primary {
        background: #059669;
        color: #ffffff;
    }
    .csh-btn-primary:hover {
        background: #047857;
        color: #ffffff;
    }
    .csh-btn-dark {
        background: #0f172a;
        color: #ffffff;
    }
    .csh-btn-dark:hover {
        background: #1e293b;
        color: #ffffff;
    }
    .csh-btn-outline {
        background: #ffffff;
        color: #334155;
        border-color: #cbd5e1;
    }
    .csh-btn-outline:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }
    .csh-btn-ghost {
        background: #f1f5f9;
        color: #475569;
    }
    .csh-btn-ghost:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* ── 2. Filter Toolbar & Date Presets ── */
    .csh-filter-card {
        background: #ffffff;
        border: 1px solid var(--csh-border);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    .csh-preset-pills {
        display: flex;
        gap: 0.35rem;
        overflow-x: auto;
        padding-bottom: 0.6rem;
        margin-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .csh-pill {
        padding: 0.28rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .csh-pill:hover {
        color: #0f172a;
        border-color: #cbd5e1;
        background: #f1f5f9;
    }
    .csh-pill.active {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
    }

    .csh-filter-form {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .csh-form-field {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        flex: 1 1 140px;
    }
    .csh-form-field label {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
    }
    .csh-form-input {
        height: 38px;
        padding: 0.4rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.82rem;
        color: #1e293b;
        background: #ffffff;
        width: 100%;
        box-sizing: border-box;
    }
    .csh-form-input:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
    }

    /* ── 3. KPI Summary Grid ── */
    .csh-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .csh-kpi-card {
        background: #ffffff;
        border: 1px solid var(--csh-border);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }
    .csh-kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
        background: #cbd5e1;
    }
    .csh-kpi-card.emerald::before { background: #059669; }
    .csh-kpi-card.blue::before { background: #2563eb; }
    .csh-kpi-card.purple::before { background: #7c3aed; }
    .csh-kpi-card.amber::before { background: #d97706; }

    .csh-kpi-label {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
    }
    .csh-kpi-val {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
    }
    .csh-kpi-sub {
        font-size: 0.74rem;
        color: #64748b;
    }

    /* ── 4. Document Sheet: Screen WYSIWYG Preview ── */
    .csh-sheet-container {
        display: flex;
        justify-content: center;
        margin-top: 0.5rem;
    }
    .csh-sheet {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 2.5rem 3rem;
        width: 100%;
        max-width: 1040px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        color: #0f172a;
        box-sizing: border-box;
    }
    .csh-sheet .doc-footer-banner-wrap.is-flush {
        width: calc(100% + 6rem) !important;
        margin-left: -3rem !important;
        margin-right: -3rem !important;
        margin-bottom: calc(-2.5rem + var(--foot-offset-y, 0px)) !important;
        margin-top: 1.5rem !important;
        border-bottom-left-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }
    .csh-sheet .doc-header-banner-wrap.is-flush {
        width: calc(100% + 6rem) !important;
        margin-left: -3rem !important;
        margin-right: -3rem !important;
        margin-top: calc(-2.5rem + var(--hdr-offset-y, 0px)) !important;
        margin-bottom: 1.25rem !important;
        border-top-left-radius: 8px !important;
        border-top-right-radius: 8px !important;
    }
    .csh-sheet .doc-footer-banner-wrap.is-flush img,
    .csh-sheet .doc-header-banner-wrap.is-flush img {
        width: 100% !important;
        height: 100% !important;
        display: block !important;
    }


    /* Seals & Header Rules */
    .doc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.85rem;
    }
    .seal-wrap {
        width: 68px;
        text-align: center;
        flex-shrink: 0;
    }
    .seal-circle {
        position: relative;
        border-radius: 50% !important;
        overflow: hidden !important;
        clip-path: circle(50% at 50% 50%) !important;
        -webkit-clip-path: circle(50% at 50% 50%) !important;
        -webkit-mask-image: -webkit-radial-gradient(white, black);
        mask-image: radial-gradient(white, black);
        isolation: isolate;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        box-sizing: border-box;
        border: none !important;
    }
    .seal-circle.has-ring::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid #0f2b48;
        box-shadow: inset 0 0 0 1.5px #ffffff, inset 0 0 0 2.5px #0f2b48;
        pointer-events: none;
        z-index: 10;
        box-sizing: border-box;
    }
    .seal-img {
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        border-radius: 50% !important;
        clip-path: circle(50% at 50% 50%) !important;
        -webkit-clip-path: circle(50% at 50% 50%) !important;
        position: relative;
        z-index: 1;
        display: block;
    }
    .seal-img-raw {
        display: block;
        margin: 0 auto;
        object-fit: contain;
    }
    .seal-inner-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 1px dashed #0f2b48;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #0f2b48;
        font-size: 1.5rem;
        position: relative;
        z-index: 1;
    }
    .seal-label {
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #64748b;
        margin-top: 3px;
    }

    .doc-header-center {
        text-align: center;
        flex-grow: 1;
    }
    .doc-rep-ph {
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #334155;
        margin-bottom: 2px;
    }
    .doc-inst-name {
        font-size: 16.5px;
        font-weight: 900;
        color: #0f2b48;
        letter-spacing: -0.01em;
        line-height: 1.2;
        margin-bottom: 2px;
    }
    .doc-dept-name {
        font-size: 10.5px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 4px;
    }

    .rule-double {
        border-top: 2px solid #0f2b48;
        border-bottom: 1px solid #0f2b48;
        height: 4px;
        margin: 4px 0 6px;
        box-sizing: border-box;
    }
    .rule-single {
        border-top: 1.5px solid #0f2b48;
        height: 0;
        margin: 4px 0 6px;
        box-sizing: border-box;
    }
    .rule-thick {
        border-top: 3px solid #0f2b48;
        height: 0;
        margin: 4px 0 6px;
        box-sizing: border-box;
    }
    .rule-none { display: none; }
    .doc-address-line { font-size: 8.5px; color: #475569; line-height: 1.4; }

    /* Document Metadata */
    .csh-doc-meta {
        text-align: right;
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.5;
        margin: 0.5rem 0 0.85rem;
    }
    .csh-doc-meta strong {
        color: #0f172a;
    }

    .csh-doc-title {
        text-align: center;
        font-size: 1.25rem;
        font-weight: 900;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #0f172a;
        margin: 0.25rem 0 0.25rem;
    }
    .csh-doc-subtitle {
        text-align: center;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 1.25rem;
    }

    .csh-scope-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.5rem 0.85rem;
        margin-bottom: 1.25rem;
        font-size: 0.78rem;
        color: #334155;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .csh-scope-bar span strong {
        color: #0f172a;
    }

    /* Ledger Table */
    .csh-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
        table-layout: fixed;
        margin-bottom: 1.5rem;
    }
    .csh-table thead th {
        background-color: #0f2b48;
        color: #ffffff;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 0.05em;
        padding: 0.65rem 0.65rem;
        border: 1px solid #0f2b48;
        text-align: left;
    }
    .csh-table thead th.text-end { text-align: right; }
    .csh-table tbody td {
        padding: 0.55rem 0.65rem;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        vertical-align: middle;
    }
    .csh-table tbody td.text-end {
        text-align: right;
        font-family: 'JetBrains Mono', monospace;
        font-variant-numeric: tabular-nums;
    }

    /* Column Sizing & No-Wrap */
    .csh-table .col-idx { width: 3.5%; text-align: center; color: #64748b; }
    .csh-table .col-ref { width: 15%; white-space: nowrap !important; font-family: 'JetBrains Mono', monospace; font-weight: 700; letter-spacing: -0.015em; font-size: 0.78rem; }
    .csh-table .col-guest { width: 17%; overflow-wrap: break-word; }
    .csh-table .col-fac { width: 16%; overflow-wrap: break-word; }
    .csh-table .col-event { width: 9.5%; white-space: nowrap !important; }
    .csh-table .col-settled { width: 10%; white-space: nowrap !important; }
    .csh-table .col-method { width: 8%; text-align: center; white-space: nowrap !important; }
    .csh-table .col-or { width: 11%; white-space: nowrap !important; font-family: 'JetBrains Mono', monospace; letter-spacing: -0.015em; font-size: 0.78rem; }
    .csh-table .col-amt { width: 10%; text-align: right; white-space: nowrap !important; font-family: 'JetBrains Mono', monospace; font-weight: 800; }
    .csh-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }
    .csh-table tbody tr:hover {
        background-color: #f1f5f9;
    }
    .csh-table tfoot td {
        padding: 0.75rem;
        border-top: 2px solid #0f2b48;
        border-bottom: 2px solid #0f2b48;
        background-color: #f8fafc;
        font-weight: 800;
    }

    .badge-method {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .badge-method-cash { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-method-digital { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

    /* Dual Signatories Block */
    .csh-sig-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-top: 2rem;
        margin-bottom: 1.5rem;
        padding-top: 1rem;
    }
    .csh-sig-block {
        display: flex;
        flex-direction: column;
    }
    .csh-sig-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #475569;
        margin-bottom: 35px;
    }
    .csh-sig-line {
        border-bottom: 1px solid #0f172a;
        max-width: 250px;
        margin-bottom: 4px;
    }
    .csh-sig-name {
        font-size: 0.88rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #0f172a;
    }
    .csh-sig-title {
        font-size: 0.76rem;
        color: #475569;
    }
    .csh-sig-subtext {
        font-size: 0.7rem;
        color: #94a3b8;
        font-style: italic;
    }

    /* ── Official Footer (screen preview) ── */
    .doc-footer {
        border-top: 1px solid #cbd5e1;
        padding-top: 8px;
        margin-top: 1.5rem;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: flex-start;
        justify-content: space-between;
        font-size: 8px;
        color: #64748b;
        width: 100%;
        box-sizing: border-box;
        gap: 1rem;
    }
    .footer-left {
        flex: 1 1 0%;
        text-align: left;
        line-height: 1.5;
    }
    .footer-left strong {
        color: #1e293b;
        font-size: 8.5px;
    }
    .footer-center {
        flex: 1 1 0%;
        text-align: center;
        line-height: 1.5;
        font-style: italic;
        font-size: 8px;
        color: #64748b;
    }
    .footer-right {
        flex: 1 1 0%;
        text-align: right;
        line-height: 1.5;
    }
    .page-num {
        font-weight: 600;
        color: #475569;
    }

    /* Empty State */
    .csh-empty {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748b;
    }
    .csh-empty i {
        font-size: 2.75rem;
        display: block;
        margin-bottom: 0.75rem;
        color: #94a3b8;
    }

    @media (max-width: 960px) {
        .csh-kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .csh-sheet { padding: 1.5rem; }
        .csh-sig-section { grid-template-columns: 1fr; gap: 1.5rem; }
    }
    @media (max-width: 640px) {
        .csh-kpi-grid { grid-template-columns: 1fr; }
        .csh-form-field { flex: 1 1 100%; }
    }
</style>

<div class="csh-container">

    {{-- ── 1. Page Header & Action Controls ── --}}
    <div class="csh-header-bar">
        <div class="csh-title-group">
            <h2>Payment Statement Report</h2>
            <p>Official collections register, accounting ledger, and verifiable audit statement</p>
            <div class="csh-chips-row">
                <span class="csh-chip chip-range">
                    <i class="bi bi-calendar3"></i> {{ $dateRangeLabel }}
                </span>
                <span class="csh-chip chip-fac">
                    <i class="bi bi-building"></i> {{ $selectedFacility ? $selectedFacility->name : 'All Facilities' }}
                </span>
                <span class="csh-chip chip-cash">
                    <i class="bi bi-person-check"></i> {{ $selectedCashier ? $selectedCashier->name : 'All Cashiers' }}
                </span>
                <span class="csh-chip chip-meth">
                    <i class="bi bi-wallet2"></i> {{ ucfirst($paymentMethod) }}
                </span>
            </div>
        </div>

        <div class="csh-actions-group">
            @if(request()->hasAny(['date_from', 'date_to', 'facility_id', 'cashier_id', 'payment_method', 'date_preset']))
                <a href="{{ route('cashier.report') }}" class="csh-btn csh-btn-ghost">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                </a>
            @endif

            @if(auth()->user()?->isAdmin() || auth()->user()?->isSuperAdmin())
                <a href="{{ route('admin.reports.letterhead-cms') }}" class="csh-btn csh-btn-outline" title="Edit Letterhead & Footer CMS Settings">
                    <i class="bi bi-sliders"></i> Letterhead CMS
                </a>
            @endif

            <a href="{{ route('cashier.report.export-csv', request()->query()) }}" class="csh-btn csh-btn-outline" title="Download Excel-compatible CSV">
                <i class="bi bi-file-earmark-spreadsheet text-success"></i> Export CSV
            </a>

            <a href="{{ route('cashier.report.print', request()->query()) }}" target="_blank" class="csh-btn csh-btn-primary">
                <i class="bi bi-printer-fill"></i> Print Statement
            </a>
        </div>
    </div>

    {{-- ── 2. Filter Toolbar with Date Presets ── --}}
    <div class="csh-filter-card">
        <div class="csh-preset-pills">
            @php
                $presets = [
                    'all'        => 'All Time',
                    'today'      => 'Today',
                    'this_week'  => 'This Week',
                    'this_month' => 'This Month',
                    'last_month' => 'Last Month',
                    'this_year'  => 'This Year',
                ];
            @endphp
            @foreach($presets as $key => $lbl)
                <a href="{{ route('cashier.report', array_merge(request()->except(['date_from', 'date_to', 'date_preset']), ['date_preset' => $key])) }}" 
                   class="csh-pill {{ $datePreset === $key ? 'active' : '' }}">
                    {{ $lbl }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('cashier.report') }}" class="csh-filter-form">
            <input type="hidden" name="date_preset" value="custom">

            <div class="csh-form-field">
                <label><i class="bi bi-calendar-event me-1"></i>Start Date</label>
                <input type="date" name="date_from" class="csh-form-input" value="{{ $dateFrom }}">
            </div>

            <div class="csh-form-field">
                <label><i class="bi bi-calendar-check me-1"></i>End Date</label>
                <input type="date" name="date_to" class="csh-form-input" value="{{ $dateTo }}">
            </div>

            <div class="csh-form-field">
                <label><i class="bi bi-building me-1"></i>Facility</label>
                <select name="facility_id" class="csh-form-input">
                    <option value="">All Facilities</option>
                    @foreach($facilities as $f)
                        <option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="csh-form-field">
                <label><i class="bi bi-person-badge me-1"></i>Cashier</label>
                <select name="cashier_id" class="csh-form-input">
                    <option value="">All Cashiers</option>
                    @foreach($cashiers as $c)
                        <option value="{{ $c->id }}" {{ request('cashier_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ ucfirst($c->role) }})</option>
                    @endforeach
                </select>
            </div>

            <div class="csh-form-field">
                <label><i class="bi bi-credit-card-2-front me-1"></i>Payment Method</label>
                <select name="payment_method" class="csh-form-input">
                    <option value="all" {{ $paymentMethod === 'all' ? 'selected' : '' }}>All Methods</option>
                    <option value="cash" {{ $paymentMethod === 'cash' ? 'selected' : '' }}>Cash (Over-The-Counter)</option>
                    <option value="paymongo" {{ $paymentMethod === 'paymongo' ? 'selected' : '' }}>PayMongo (Digital Gateway)</option>
                </select>
            </div>

            <button type="submit" class="csh-btn csh-btn-dark">
                <i class="bi bi-funnel-fill"></i> Apply Filters
            </button>
        </form>
    </div>

    {{-- ── 3. KPI Executive Summary ── --}}
    <div class="csh-kpi-grid">
        <div class="csh-kpi-card emerald">
            <div class="csh-kpi-label">Settled Bookings</div>
            <div class="csh-kpi-val">{{ number_format($totalCount) }}</div>
            <div class="csh-kpi-sub">Total verified transactions</div>
        </div>

        <div class="csh-kpi-card blue">
            <div class="csh-kpi-label">Total Revenue Collected</div>
            <div class="csh-kpi-val">&#8369;{{ number_format($totalAmount, 2) }}</div>
            <div class="csh-kpi-sub">Gross cash &amp; digital collection</div>
        </div>

        <div class="csh-kpi-card purple">
            <div class="csh-kpi-label">Cash (OTC)</div>
            <div class="csh-kpi-val">&#8369;{{ number_format($cashCollected, 2) }}</div>
            <div class="csh-kpi-sub">{{ number_format($cashCount) }} in-person settlements</div>
        </div>

        <div class="csh-kpi-card amber">
            <div class="csh-kpi-label">PayMongo Digital</div>
            <div class="csh-kpi-val">&#8369;{{ number_format($digitalCollected, 2) }}</div>
            <div class="csh-kpi-sub">{{ number_format($digitalCount) }} gateway payments</div>
        </div>
    </div>

    {{-- ── 4. Interactive Document Sheet Preview ── --}}
    @if($reservations->count() > 0)
        <div class="csh-sheet-container">
            <div class="csh-sheet" id="statement-sheet">

                {{-- Official Letterhead from CMS --}}
                <x-report-letterhead />

                {{-- Metadata Reference --}}
                <div class="csh-doc-meta">
                    <div>Document Ref: <strong>{{ $docReference }}</strong></div>
                    <div>Date Generated: <strong>{{ now()->format('F d, Y h:i A') }}</strong></div>
                    <div>Prepared by: <strong>{{ auth()->user()->name ?? 'System User' }} ({{ auth()->user()?->roleLabel() ?? 'Cashier' }})</strong></div>
                </div>

                {{-- Report Title & Subtitle --}}
                <div class="csh-doc-title">{{ $reportTitle }}</div>
                <div class="csh-doc-subtitle">
                    Coverage Period: {{ strtoupper($dateRangeLabel) }} &bull; Scope: {{ $selectedCashier ? strtoupper($selectedCashier->name) : 'ALL CASHIERS' }}
                </div>

                {{-- Scope Bar --}}
                <div class="csh-scope-bar">
                    <span><strong>Period:</strong> {{ $dateRangeLabel }}</span>
                    <span><strong>Facility:</strong> {{ $selectedFacility ? $selectedFacility->name : 'All Facilities' }}</span>
                    <span><strong>Method Filter:</strong> {{ ucfirst($paymentMethod) }}</span>
                    <span><strong>Cashier Scope:</strong> {{ $selectedCashier ? $selectedCashier->name : 'All Cashiers' }}</span>
                </div>

                {{-- Statement Ledger Table --}}
                <table class="csh-table">
                    <thead>
                        <tr>
                            <th class="col-idx">#</th>
                            <th class="col-ref">Reference #</th>
                            <th class="col-guest">Client / Guest</th>
                            <th class="col-fac">Facility &amp; Category</th>
                            <th class="col-event">Event Date</th>
                            <th class="col-settled">Settled Date</th>
                            <th class="col-method">Method</th>
                            <th class="col-or">OR / Gateway Ref</th>
                            <th class="col-amt">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $i => $r)
                            <tr>
                                <td class="col-idx">{{ $i + 1 }}</td>
                                <td class="col-ref">#{{ $r->booking_reference ?? $r->id }}</td>
                                <td class="col-guest">
                                    <div style="font-weight: 700; color: #0f172a;">{{ $r->user->name ?? 'Guest User' }}</div>
                                    <div style="font-size: 0.72rem; color: #64748b;">{{ $r->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="col-fac">
                                    <div style="font-weight: 600; color: #0f172a;">{{ $r->facility->name ?? 'Facility Removed' }}</div>
                                    <div style="font-size: 0.72rem; color: #64748b;">{{ $r->facility->category->name ?? 'Campus System' }}</div>
                                </td>
                                <td class="col-event">{{ $r->start_date ? \Carbon\Carbon::parse($r->start_date)->format('M d, Y') : 'N/A' }}</td>
                                <td class="col-settled">
                                    @if($r->cashier_signed_at)
                                        <div>{{ \Carbon\Carbon::parse($r->cashier_signed_at)->format('M d, Y') }}</div>
                                        <div style="font-size: 0.7rem; color: #64748b;">{{ \Carbon\Carbon::parse($r->cashier_signed_at)->format('h:i A') }}</div>
                                    @elseif($r->updated_at)
                                        <div>{{ $r->updated_at->format('M d, Y') }}</div>
                                        <div style="font-size: 0.7rem; color: #64748b;">{{ $r->updated_at->format('h:i A') }}</div>
                                    @else
                                        <span style="color: #94a3b8;">N/A</span>
                                    @endif
                                </td>
                                <td class="col-method">
                                    @if($r->payment_method === 'cash')
                                        <span class="badge-method badge-method-cash"><i class="bi bi-cash me-1"></i>Cash OTC</span>
                                    @elseif($r->payment_method === 'paymongo')
                                        <span class="badge-method badge-method-digital"><i class="bi bi-wallet2 me-1"></i>PayMongo</span>
                                    @else
                                        <span class="badge-method">{{ ucfirst($r->payment_method ?? 'Unknown') }}</span>
                                    @endif
                                </td>
                                <td class="col-or">
                                    @if($r->cash_receipt_number)
                                        <span style="font-weight: 700; color: #0f172a;">{{ $r->cash_receipt_number }}</span>
                                    @elseif($r->payment_method === 'paymongo' && $r->payment_id)
                                        <span style="font-size: 0.72rem; color: #0284c7;">{{ \Illuminate\Support\Str::limit(str_replace('cs_test_', '', $r->payment_id), 12, '') }}</span>
                                    @else
                                        <span style="color: #94a3b8; font-style: italic;">Pending</span>
                                    @endif
                                </td>
                                <td class="col-amt">
                                    &#8369;{{ number_format($r->total_price ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-transform: uppercase; font-size: 0.78rem; letter-spacing: 0.05em;">
                                Total Collections ({{ number_format($totalCount) }} Settled Transactions)
                            </td>
                            <td></td>
                            <td style="text-align: right; font-size: 0.78rem; text-transform: uppercase;">Sum:</td>
                            <td class="text-end" style="font-size: 1.05rem; color: #059669;">
                                &#8369;{{ number_format($totalAmount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                {{-- Dual Signatory Preview --}}
                <div class="csh-sig-section">
                    <div class="csh-sig-block">
                        <div class="csh-sig-label">Prepared &amp; Processed By:</div>
                        <div class="csh-sig-line"></div>
                        <div class="csh-sig-name">{{ $preparedByName }}</div>
                        <div class="csh-sig-title">{{ $preparedByTitle }}</div>
                        <div class="csh-sig-subtext">Cashiering &amp; Disbursements Unit</div>
                    </div>
                    <div class="csh-sig-block">
                        <div class="csh-sig-label">Certified &amp; Verified By:</div>
                        <div class="csh-sig-line"></div>
                        <div class="csh-sig-name">{{ $verifiedByName }}</div>
                        <div class="csh-sig-title">{{ $verifiedByTitle }}</div>
                        <div class="csh-sig-subtext">Office of Financial Management &amp; Accounting</div>
                    </div>
                </div>

                {{-- Official Footer from CMS --}}
                <x-report-footer />

            </div>
        </div>
    @else
        <div class="csh-empty">
            <i class="bi bi-receipt"></i>
            <h4 style="color: #0f172a; font-weight: 800; margin-bottom: 0.35rem;">No Records Match Current Filters</h4>
            <p style="margin: 0 0 1.25rem 0; font-size: 0.85rem;">Adjust the reporting period, facility, payment method, or cashier to generate statement records.</p>
            @if(request()->hasAny(['date_from', 'date_to', 'facility_id', 'cashier_id', 'payment_method', 'date_preset']))
                <a href="{{ route('cashier.report') }}" class="csh-btn csh-btn-outline">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                </a>
            @endif
        </div>
    @endif

</div>
@endsection
