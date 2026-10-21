<?php $__env->startSection('title', 'Reports & Analytics'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* ==========================================================================
       REPORTS & ANALYTICS — MODERN SAAS DESIGN SUITE (Orzika & AeroOps Inspired)
       ========================================================================== */

    :root {
        --rep-bg-canvas: #f8fafc;
        --rep-card-bg: #ffffff;
        --rep-border-subtle: #f1f5f9;
        --rep-border-light: #e2e8f0;
        --rep-border-focus: #3b82f6;
        --rep-text-heading: #0f172a;
        --rep-text-body: #334155;
        --rep-text-muted: #64748b;
        --rep-primary: #2563eb;
        --rep-primary-hover: #1d4ed8;
        --rep-success: #10b981;
        --rep-warning: #f59e0b;
        --rep-danger: #ef4444;
    }

    .rep-suite {
        font-family: inherit;
        color: var(--rep-text-body);
        padding-bottom: 3rem;
    }

    /* ── 1. Top Breadcrumb & Page Header ── */
    .rep-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--rep-text-muted);
        margin-bottom: 0.4rem;
    }
    .rep-breadcrumb a {
        color: var(--rep-text-muted);
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .rep-breadcrumb a:hover {
        color: var(--rep-primary);
    }
    .rep-breadcrumb .sep {
        opacity: 0.5;
        font-size: 0.75rem;
    }
    .rep-breadcrumb .current {
        color: var(--rep-text-heading);
        font-weight: 600;
    }

    .rep-header-bar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.25rem;
        flex-wrap: wrap;
        margin-bottom: 0.85rem;
    }
    .rep-page-title {
        font-size: 1.45rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        color: var(--rep-text-heading);
        margin: 0;
        line-height: 1.2;
    }
    .rep-page-subtitle {
        color: var(--rep-text-muted);
        font-size: 0.82rem;
        margin: 0.2rem 0 0 0;
    }
    .rep-header-actions {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        flex-wrap: wrap;
    }

    /* ── 2. Segmented Navigation Tabs ── */
    .rep-nav-tabs {
        display: flex;
        gap: 0.25rem;
        background: #eef2f6;
        padding: 0.25rem;
        border-radius: 10px;
        overflow-x: auto;
        margin-bottom: 0.75rem;
        border: 1px solid rgba(226, 232, 240, 0.8);
        scrollbar-width: thin;
    }
    .rep-nav-tab {
        padding: 0.35rem 0.8rem;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 8px;
        text-decoration: none;
        color: #475569;
        white-space: nowrap;
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .rep-nav-tab:hover {
        color: var(--rep-text-heading);
        background: rgba(255, 255, 255, 0.65);
    }
    .rep-nav-tab.active {
        background: #ffffff;
        color: var(--rep-text-heading);
        font-weight: 700;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.06);
    }

    /* ── 3. Streamlined Filter Toolbar (Eliminates the 11-box clutter) ── */
    .rep-filter-box {
        background: #ffffff;
        border: 1px solid var(--rep-border-light);
        border-radius: 12px;
        padding: 0.65rem 0.95rem;
        box-shadow: 0 2px 10px -2px rgba(15, 23, 42, 0.03);
        margin-bottom: 0.85rem;
    }

    .rep-filter-primary-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .rep-control-group {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid var(--rep-border-light);
        border-radius: 8px;
        height: 34px;
        padding: 0 0.65rem;
        transition: all 0.15s ease;
    }
    .rep-control-group:focus-within {
        border-color: var(--rep-primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .rep-control-icon {
        color: var(--rep-text-muted);
        font-size: 0.8rem;
        margin-right: 0.4rem;
    }
    .rep-control-select, .rep-control-input {
        border: none;
        background: transparent;
        font-size: 0.8rem;
        color: var(--rep-text-heading);
        font-weight: 500;
        outline: none;
        width: 100%;
    }
    .rep-control-select option {
        color: var(--rep-text-heading);
        background: #ffffff;
    }

    /* Advanced Collapsible Drawer */
    .rep-advanced-filters-panel {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }
    .rep-advanced-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.85rem;
        align-items: end;
    }
    .rep-field-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--rep-text-muted);
        margin-bottom: 0.35rem;
    }

    /* Quick Preset Chips */
    .rep-shortcuts-strip {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
        padding-top: 0.45rem;
        border-top: 1px dashed #f1f5f9;
    }
    .rep-shortcuts-tag {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--rep-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-right: 0.2rem;
    }
    .rep-chip {
        padding: 0.2rem 0.6rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.15s ease;
    }
    .rep-chip:hover {
        background: #ffffff;
        color: var(--rep-primary);
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
    .rep-chip.active {
        background: #0f2b48;
        color: #ffffff;
        border-color: #0f2b48;
        box-shadow: 0 2px 6px rgba(15, 43, 72, 0.2);
    }

    /* ── 4. Main Report Canvas Card (Orzika CRM Layout) ── */
    .rep-canvas-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.03);
        margin-bottom: 1.25rem;
        position: relative;
    }

    .rep-canvas-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.85rem;
    }
    .rep-canvas-title {
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--rep-text-heading);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .rep-canvas-count-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.15rem 0.55rem;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #dbeafe;
    }

    /* ── 5. Official Report Metadata Summary Banner ── */
    .rep-meta-banner {
        background: #f8fafc;
        border: 1px solid #eef2f6;
        border-radius: 10px;
        padding: 0.6rem 0.95rem;
        margin-bottom: 0.85rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        font-size: 0.78rem;
    }
    @media (max-width: 768px) {
        .rep-meta-banner {
            grid-template-columns: 1fr;
            gap: 0.6rem;
        }
    }
    .rep-meta-col-title {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #475569;
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .rep-meta-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    .rep-meta-item {
        display: flex;
        align-items: baseline;
        gap: 0.4rem;
    }
    .rep-meta-label {
        color: #64748b;
        min-width: 100px;
        font-weight: 500;
    }
    .rep-meta-val {
        color: #0f172a;
        font-weight: 600;
    }

    /* ── 6. Minimalist Executive KPI Strip ── */
    .rep-kpi-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.85rem;
        margin-bottom: 0.85rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .rep-kpi-item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding-right: 0.75rem;
        position: relative;
    }
    .rep-kpi-item:not(:last-child)::after {
        content: "";
        position: absolute;
        right: 0;
        top: 15%;
        bottom: 15%;
        width: 1px;
        background: #f1f5f9;
    }
    @media (max-width: 768px) {
        .rep-kpi-item:not(:last-child)::after {
            display: none;
        }
    }
    .rep-kpi-num-row {
        display: flex;
        align-items: baseline;
        gap: 0.45rem;
    }
    .rep-kpi-number {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--rep-text-heading);
        letter-spacing: -0.03em;
        line-height: 1.1;
        margin: 0;
    }
    .rep-kpi-trend {
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        padding: 0.12rem 0.4rem;
        border-radius: 999px;
    }
    .rep-kpi-trend.positive {
        background: #ecfdf5;
        color: #059669;
    }
    .rep-kpi-trend.neutral {
        background: #eff6ff;
        color: #2563eb;
    }
    .rep-kpi-trend.warning {
        background: #fffbeb;
        color: #d97706;
    }
    .rep-kpi-trend.negative {
        background: #fef2f2;
        color: #dc2626;
    }
    .rep-kpi-desc {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--rep-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-top: 0.2rem;
    }
    .rep-kpi-note {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 0.15rem;
    }

    /* ── 7. Operational Insight Banner ── */
    .rep-insight-banner {
        background: linear-gradient(135deg, rgba(240, 253, 244, 0.85) 0%, rgba(248, 250, 252, 0.95) 100%);
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .rep-insight-icon-wrap {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #dcfce7;
        color: #15803d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
        box-shadow: 0 1px 3px rgba(22, 101, 52, 0.1);
    }
    .rep-insight-heading {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #15803d;
        margin-bottom: 0.1rem;
    }
    .rep-insight-body {
        font-size: 0.78rem;
        line-height: 1.4;
        color: #14532d;
        margin: 0;
    }

    /* ── 8. Modern Data Table (Image 1 Orzika CRM Table) ── */
    .rep-table-shell {
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        overflow-x: auto;
        background: #ffffff;
    }
    .rep-table-pro {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.79rem;
        text-align: left;
    }
    .rep-table-pro thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.69rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.55rem 0.75rem;
        border-top: none;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .rep-table-pro tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.14s ease;
    }
    .rep-table-pro tbody tr:hover {
        background: #f8fafc;
    }
    .rep-table-pro tbody tr:last-child {
        border-bottom: none;
    }
    .rep-table-pro td {
        padding: 0.52rem 0.75rem;
        color: #334155;
        vertical-align: middle;
    }

    /* Soft Pastel Status Pills */
    .rep-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .rep-pill-paid {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .rep-pill-cash-pending {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .rep-pill-pending {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }
    .rep-pill-cancelled {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .rep-pill-partial {
        background: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
    }

    /* Logistics equipment chips */
    .rep-eq-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.15rem 0.45rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: #334155;
        margin: 0.1rem 0.2rem 0.1rem 0;
    }
    .rep-eq-chip .qty {
        background: #2563eb;
        color: #ffffff;
        border-radius: 4px;
        padding: 0.05rem 0.3rem;
        font-size: 0.65rem;
        font-weight: 700;
    }

    /* Grouped view headers */
    .rep-group-divider {
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 700;
        font-size: 0.76rem;
        padding: 0.5rem 0.85rem;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
    }
    .rep-group-sum {
        background: #ffffff;
        font-weight: 700;
        color: #0f172a;
        padding: 0.5rem 0.85rem;
        font-size: 0.76rem;
        text-align: right;
        border-bottom: 2px solid #e2e8f0;
    }

    /* Buttons */
    .btn-pill-sm {
        padding: 0.32rem 0.75rem !important;
        font-size: 0.75rem !important;
        height: auto !important;
    }

    .btn-pill-primary {
        background: #2563eb;
        color: #ffffff;
        border: 1px solid #2563eb;
        border-radius: 999px;
        padding: 0.42rem 1.1rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
    }
    .btn-pill-primary:hover {
        background: #1d4ed8;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
    }

    .btn-pill-teal {
        background: #0d9488;
        color: #ffffff;
        border: 1px solid #0d9488;
        border-radius: 999px;
        padding: 0.42rem 1.1rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(13, 148, 136, 0.2);
    }
    .btn-pill-teal:hover {
        background: #0f766e;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(13, 148, 136, 0.28);
    }

    .btn-pill-dark {
        background: #0f172a;
        color: #ffffff;
        border: 1px solid #0f172a;
        border-radius: 999px;
        padding: 0.42rem 1.1rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
    }
    .btn-pill-dark:hover {
        background: #1e293b;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .btn-pill-outline {
        background: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 999px;
        padding: 0.4rem 0.95rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.15s ease;
    }
    .btn-pill-outline:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    /* ── Floating Action Bar ── */
    .rep-floating-bar {
        position: fixed;
        bottom: 1.25rem;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: rgba(15, 23, 42, 0.94);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 999px;
        padding: 0.35rem 0.5rem 0.35rem 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.28), 0 2px 6px rgba(0, 0, 0, 0.15);
        z-index: 1040;
        opacity: 0;
        pointer-events: none;
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.22s ease;
    }
    .rep-floating-bar.visible {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }
    .rep-floating-info {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.76rem;
        font-weight: 700;
        color: #f8fafc;
        white-space: nowrap;
    }
    .rep-floating-actions {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .rep-floating-actions .btn-pill-outline {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.2);
    }
    .rep-floating-actions .btn-pill-outline:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }
    @media (max-width: 640px) {
        .rep-floating-bar {
            left: 0.75rem;
            right: 0.75rem;
            bottom: 0.75rem;
            transform: translateY(20px);
            justify-content: space-between;
        }
        .rep-floating-bar.visible {
            transform: translateY(0);
        }
    }

    /* Modal styling */
    .rep-modal-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.15);
    }
</style>

<div class="rep-suite">

    <?php
        $tabTitles = [
            'reservations'  => 'Master Reservation Register',
            'income'        => 'Financial Statement & Revenue Ledger',
            'facilities'    => 'Facility Utilization & Venue Audit',
            'clients'       => 'Client Activity & Booking Breakdown',
            'dispatch'      => 'Operations & Setup Dispatch Checklist',
            'cancellations' => 'Cancellations & Deposit Forfeiture Audit',
            'analytics'     => 'Executive Statistics & Analytics',
        ];
        $currentTabTitle = $tabTitles[$reportType] ?? 'Report';

        // Count active advanced filters
        $advancedFilterCount = 0;
        if ($categoryId && $categoryId !== 'all') $advancedFilterCount++;
        if ($payStatus && $payStatus !== 'all') $advancedFilterCount++;
        if ($urgency && $urgency !== 'all') $advancedFilterCount++;
        if ($timeShift && $timeShift !== 'all') $advancedFilterCount++;
        if ($hasLogistics && $hasLogistics !== 'all') $advancedFilterCount++;
        if ($clientType && $clientType !== 'all') $advancedFilterCount++;
        if ($groupBy && $groupBy !== 'none') $advancedFilterCount++;
    ?>

    
    <div class="rep-breadcrumb">
        <a href="<?php echo e(route('admin.dashboard')); ?>">Home</a>
        <span class="sep">/</span>
        <a href="<?php echo e(route('admin.reports.index')); ?>">Reports &amp; Analytics</a>
        <span class="sep">/</span>
        <span class="current"><?php echo e($currentTabTitle); ?></span>
    </div>

    <div class="rep-header-bar">
        <div>
            <h1 class="rep-page-title">Reports &amp; Analytics</h1>
            <p class="rep-page-subtitle">Official verified university booking registers, audited financial ledgers, and logistics sheets.</p>
        </div>
        <div class="rep-header-actions">
            <?php if(!auth()->user()?->isStaff()): ?>
            <a href="<?php echo e(route('admin.reports.letterhead-cms')); ?>" class="btn-pill-outline" title="Word-Style Visual Editor for Report Header &amp; Footer">
                <i class="bi bi-file-earmark-richtext-fill text-primary"></i> Letterhead CMS
            </a>
            <?php endif; ?>
            <button type="button" class="btn-pill-outline" data-bs-toggle="modal" data-bs-target="#signatoryModal" title="Configure Certified Signatories">
                <i class="bi bi-pen text-primary"></i> Certified Signatories
            </button>
            <a href="<?php echo e(route('admin.reports.export-csv', array_merge(['report_type' => $reportType], request()->query()))); ?>"
               onclick="event.preventDefault(); triggerReportExport('<?php echo e(route('admin.reports.export-csv')); ?>', '_self')"
               class="btn-pill-outline" title="Export Current Report to CSV" download>
                <i class="bi bi-file-earmark-spreadsheet text-success"></i> Export CSV
            </a>
            <a href="<?php echo e(route('admin.reports.print', array_merge(['report_type' => $reportType], request()->query()))); ?>"
               onclick="event.preventDefault(); triggerReportExport('<?php echo e(route('admin.reports.print')); ?>', '_blank')"
               target="_blank" class="btn-pill-dark" title="Print Official Document Preview">
                <i class="bi bi-printer-fill"></i> Print / PDF
            </a>
        </div>
    </div>

    
    <div class="rep-nav-tabs">
        <a href="<?php echo e(route('admin.reports.index', array_merge(request()->except('page'), ['report_type' => 'reservations']))); ?>"
           class="rep-nav-tab <?php echo e($reportType === 'reservations' ? 'active' : ''); ?>">
            <i class="bi bi-table"></i> Master List
        </a>
        <a href="<?php echo e(route('admin.reports.index', array_merge(request()->except('page'), ['report_type' => 'income']))); ?>"
           class="rep-nav-tab <?php echo e($reportType === 'income' ? 'active' : ''); ?>">
            <i class="bi bi-cash-stack"></i> Revenue &amp; Income
        </a>
        <a href="<?php echo e(route('admin.reports.index', array_merge(request()->except('page'), ['report_type' => 'facilities']))); ?>"
           class="rep-nav-tab <?php echo e($reportType === 'facilities' ? 'active' : ''); ?>">
            <i class="bi bi-building"></i> Facility Utilization
        </a>
        <a href="<?php echo e(route('admin.reports.index', array_merge(request()->except('page'), ['report_type' => 'clients']))); ?>"
           class="rep-nav-tab <?php echo e($reportType === 'clients' ? 'active' : ''); ?>">
            <i class="bi bi-people"></i> Client Breakdown
        </a>
        <a href="<?php echo e(route('admin.reports.index', array_merge(request()->except('page'), ['report_type' => 'dispatch']))); ?>"
           class="rep-nav-tab <?php echo e($reportType === 'dispatch' ? 'active' : ''); ?>">
            <i class="bi bi-truck"></i> Logistics Dispatch
        </a>
        <a href="<?php echo e(route('admin.reports.index', array_merge(request()->except('page'), ['report_type' => 'cancellations']))); ?>"
           class="rep-nav-tab <?php echo e($reportType === 'cancellations' ? 'active' : ''); ?>">
            <i class="bi bi-x-octagon"></i> Cancellations Audit
        </a>
        <a href="<?php echo e(route('admin.reports.index', array_merge(request()->except('page'), ['report_type' => 'analytics']))); ?>"
           class="rep-nav-tab <?php echo e($reportType === 'analytics' ? 'active' : ''); ?>">
            <i class="bi bi-pie-chart"></i> Executive Analytics
        </a>
    </div>

    
    <div class="rep-filter-box">
        <form method="GET" action="<?php echo e(route('admin.reports.index')); ?>" id="reportFilterForm">
            <input type="hidden" name="report_type" value="<?php echo e($reportType); ?>">

            
            <input type="hidden" name="prepared_by_name" id="hidden_prepared_by_name" value="<?php echo e($preparedByName); ?>">
            <input type="hidden" name="prepared_by_title" id="hidden_prepared_by_title" value="<?php echo e($preparedByTitle); ?>">
            <input type="hidden" name="approved_by_name" id="hidden_approved_by_name" value="<?php echo e($approvedByName); ?>">
            <input type="hidden" name="approved_by_title" id="hidden_approved_by_title" value="<?php echo e($approvedByTitle); ?>">

            
            <div class="rep-filter-primary-row">

                
                <div class="rep-control-group" style="min-width: 170px;">
                    <i class="bi bi-calendar3 rep-control-icon"></i>
                    <select name="date_preset" class="rep-control-select" onchange="handlePresetChange(this.value)">
                        <option value="all" <?php echo e($datePreset === 'all' ? 'selected' : ''); ?>>All Records</option>
                        <option value="today" <?php echo e($datePreset === 'today' ? 'selected' : ''); ?>>Today</option>
                        <option value="this_week" <?php echo e($datePreset === 'this_week' ? 'selected' : ''); ?>>This Week</option>
                        <option value="this_month" <?php echo e($datePreset === 'this_month' ? 'selected' : ''); ?>>This Month</option>
                        <option value="last_month" <?php echo e($datePreset === 'last_month' ? 'selected' : ''); ?>>Last Month</option>
                        <option value="this_quarter" <?php echo e($datePreset === 'this_quarter' ? 'selected' : ''); ?>>This Quarter</option>
                        <option value="this_year" <?php echo e($datePreset === 'this_year' ? 'selected' : ''); ?>>Annual (This Year)</option>
                        <option value="custom" <?php echo e($datePreset === 'custom' ? 'selected' : ''); ?>>Custom Date Range...</option>
                    </select>
                </div>

                
                <div class="rep-control-group" style="min-width: 180px; flex: 1;">
                    <i class="bi bi-building rep-control-icon"></i>
                    <select name="facility_id" class="rep-control-select">
                        <option value="all">All Facilities</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($f->id); ?>" <?php echo e((string)$facilityId === (string)$f->id ? 'selected' : ''); ?>>
                                <?php echo e($f->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="rep-control-group" style="min-width: 170px;">
                    <i class="bi bi-flag rep-control-icon"></i>
                    <select name="status" class="rep-control-select">
                        <option value="all">All Booking Statuses</option>
                        <option value="approved" <?php echo e($status === 'approved' ? 'selected' : ''); ?>>Approved</option>
                        <option value="pending" <?php echo e($status === 'pending' ? 'selected' : ''); ?>>Pending Review</option>
                        <option value="cancelled" <?php echo e($status === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                        <option value="cancelled_all" <?php echo e($status === 'cancelled_all' ? 'selected' : ''); ?>>Cancelled (All Types)</option>
                        <option value="denied" <?php echo e($status === 'denied' ? 'selected' : ''); ?>>Denied</option>
                    </select>
                </div>

                
                <div class="rep-control-group" style="min-width: 200px; flex: 1.2;">
                    <i class="bi bi-search rep-control-icon"></i>
                    <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search reference, client, title..." class="rep-control-input">
                </div>

                
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn-pill-primary" style="height: 34px;">
                        <i class="bi bi-funnel-fill"></i> Apply Filter
                    </button>

                    <button type="button" class="btn-pill-outline" style="height: 34px;" onclick="toggleAdvancedFilters()">
                        <i class="bi bi-sliders"></i>
                        <span>Filters</span>
                        <?php if($advancedFilterCount > 0): ?>
                            <span class="badge bg-primary rounded-pill ms-1" style="font-size: 0.7rem;"><?php echo e($advancedFilterCount); ?></span>
                        <?php endif; ?>
                        <i class="bi bi-chevron-down ms-1" id="advancedChevron" style="font-size: 0.75rem;"></i>
                    </button>

                    <a href="<?php echo e(route('admin.reports.index', ['report_type' => $reportType])); ?>" class="btn-pill-outline" style="height: 34px;" title="Reset All Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>

            </div>

            
            <div class="row g-2 mt-2 <?php echo e($datePreset !== 'custom' ? 'd-none' : ''); ?>" id="customDateRow">
                <div class="col-sm-6 col-md-3">
                    <label class="rep-field-label">From Date</label>
                    <input type="date" name="date_from" value="<?php echo e($dateFrom); ?>" class="form-control form-control-sm rounded-3">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="rep-field-label">To Date</label>
                    <input type="date" name="date_to" value="<?php echo e($dateTo); ?>" class="form-control form-control-sm rounded-3">
                </div>
            </div>

            
            <div class="rep-advanced-filters-panel <?php echo e($advancedFilterCount === 0 ? 'd-none' : ''); ?>" id="advancedFiltersDrawer">
                <div class="rep-advanced-grid">

                    <div>
                        <label class="rep-field-label"><i class="bi bi-tags"></i> Facility Category</label>
                        <select name="category_id" class="form-select form-select-sm rounded-3">
                            <option value="all">All Categories</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>" <?php echo e((string)$categoryId === (string)$c->id ? 'selected' : ''); ?>><?php echo e($c->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="rep-field-label"><i class="bi bi-credit-card"></i> Payment Status</label>
                        <select name="payment_status" class="form-select form-select-sm rounded-3">
                            <option value="all">All Payment States</option>
                            <option value="paid" <?php echo e($payStatus === 'paid' ? 'selected' : ''); ?>>Paid</option>
                            <option value="cash_pending" <?php echo e($payStatus === 'cash_pending' ? 'selected' : ''); ?>>Cash Pending (OTC)</option>
                            <option value="partially_paid" <?php echo e($payStatus === 'partially_paid' ? 'selected' : ''); ?>>Partially Paid</option>
                            <option value="unpaid" <?php echo e($payStatus === 'unpaid' ? 'selected' : ''); ?>>Unpaid / Pending</option>
                        </select>
                    </div>

                    <div>
                        <label class="rep-field-label"><i class="bi bi-hourglass-split"></i> Payment Urgency</label>
                        <select name="urgency" class="form-select form-select-sm rounded-3">
                            <option value="all">All Timelines</option>
                            <option value="expiring_soon" <?php echo e($urgency === 'expiring_soon' ? 'selected' : ''); ?>>⚠️ Expiring Soon (Next 48h)</option>
                            <option value="overdue" <?php echo e($urgency === 'overdue' ? 'selected' : ''); ?>>🚨 Past Deadline (Overdue)</option>
                            <option value="deposit_paid" <?php echo e($urgency === 'deposit_paid' ? 'selected' : ''); ?>>🛡️ Security Deposit Paid</option>
                        </select>
                    </div>

                    <div>
                        <label class="rep-field-label"><i class="bi bi-clock-history"></i> Shift &amp; Schedule</label>
                        <select name="time_shift" class="form-select form-select-sm rounded-3">
                            <option value="all">All Hours &amp; Days</option>
                            <option value="day" <?php echo e($timeShift === 'day' ? 'selected' : ''); ?>>☀️ Daytime Events</option>
                            <option value="night_overnight" <?php echo e($timeShift === 'night_overnight' ? 'selected' : ''); ?>>🌙 Night / Overnight</option>
                            <option value="weekday" <?php echo e($timeShift === 'weekday' ? 'selected' : ''); ?>>📅 Weekdays</option>
                            <option value="weekend" <?php echo e($timeShift === 'weekend' ? 'selected' : ''); ?>>🎉 Weekends</option>
                        </select>
                    </div>

                    <div>
                        <label class="rep-field-label"><i class="bi bi-box-seam"></i> Logistics / Equipment</label>
                        <select name="has_logistics" class="form-select form-select-sm rounded-3">
                            <option value="all" <?php echo e($hasLogistics === 'all' ? 'selected' : ''); ?>>All Bookings</option>
                            <option value="1" <?php echo e($hasLogistics === '1' ? 'selected' : ''); ?>>Has Equipment Requested</option>
                            <option value="0" <?php echo e($hasLogistics === '0' ? 'selected' : ''); ?>>No Equipment Requested</option>
                        </select>
                    </div>

                    <div>
                        <label class="rep-field-label"><i class="bi bi-person-badge"></i> Client Affiliation</label>
                        <select name="client_type" class="form-select form-select-sm rounded-3">
                            <option value="all">All Clients</option>
                            <option value="internal" <?php echo e($clientType === 'internal' ? 'selected' : ''); ?>>Internal (University)</option>
                            <option value="external" <?php echo e($clientType === 'external' ? 'selected' : ''); ?>>External Clients</option>
                        </select>
                    </div>

                    <div>
                        <label class="rep-field-label"><i class="bi bi-layers"></i> Group By Strategy</label>
                        <select name="group_by" class="form-select form-select-sm rounded-3">
                            <option value="none" <?php echo e($groupBy === 'none' ? 'selected' : ''); ?>>None (Flat Chronological)</option>
                            <option value="facility" <?php echo e($groupBy === 'facility' ? 'selected' : ''); ?>>Facility (Venue)</option>
                            <option value="payment_status" <?php echo e($groupBy === 'payment_status' ? 'selected' : ''); ?>>Payment Status</option>
                            <option value="month" <?php echo e($groupBy === 'month' ? 'selected' : ''); ?>>Month (Jan, Feb...)</option>
                            <option value="client" <?php echo e($groupBy === 'client' ? 'selected' : ''); ?>>Client</option>
                        </select>
                    </div>

                </div>
            </div>

            
            <div class="rep-shortcuts-strip">
                <span class="rep-shortcuts-tag"><i class="bi bi-lightning-charge-fill text-warning"></i> Quick Presets:</span>
                <a href="<?php echo e(route('admin.reports.index', ['report_type' => 'reservations', 'date_preset' => 'today', 'status' => 'approved'])); ?>"
                   class="rep-chip <?php echo e($reportType === 'reservations' && $datePreset === 'today' && $status === 'approved' ? 'active' : ''); ?>">
                    <i class="bi bi-calendar-check text-success"></i> Today's Schedule &amp; Setup
                </a>
                <a href="<?php echo e(route('admin.reports.index', ['report_type' => 'reservations', 'payment_status' => 'cash_pending', 'urgency' => 'expiring_soon'])); ?>"
                   class="rep-chip <?php echo e($payStatus === 'cash_pending' && $urgency === 'expiring_soon' ? 'active' : ''); ?>">
                    <i class="bi bi-exclamation-triangle text-danger"></i> Unsettled Cash Due Soon
                </a>
                <a href="<?php echo e(route('admin.reports.index', ['report_type' => 'income', 'date_preset' => 'this_month', 'group_by' => 'facility'])); ?>"
                   class="rep-chip <?php echo e($reportType === 'income' && $datePreset === 'this_month' && $groupBy === 'facility' ? 'active' : ''); ?>">
                    <i class="bi bi-cash-stack text-success"></i> Monthly Remittance Ledger
                </a>
                <a href="<?php echo e(route('admin.reports.index', ['report_type' => 'facilities', 'date_preset' => 'this_month'])); ?>"
                   class="rep-chip <?php echo e($reportType === 'facilities' && $datePreset === 'this_month' ? 'active' : ''); ?>">
                    <i class="bi bi-trophy text-warning"></i> Facility Leaderboard
                </a>
                <a href="<?php echo e(route('admin.reports.index', ['report_type' => 'dispatch', 'date_preset' => 'this_month', 'has_logistics' => '1'])); ?>"
                   class="rep-chip <?php echo e($reportType === 'dispatch' ? 'active' : ''); ?>">
                    <i class="bi bi-truck text-primary"></i> Logistics Dispatch Sheet
                </a>
                <a href="<?php echo e(route('admin.reports.index', ['report_type' => 'cancellations', 'date_preset' => 'this_year'])); ?>"
                   class="rep-chip <?php echo e($reportType === 'cancellations' ? 'active' : ''); ?>">
                    <i class="bi bi-shield-x text-danger"></i> Cancellations &amp; Forfeitures
                </a>
            </div>

        </form>
    </div>

    
    <div class="rep-canvas-card">

        
        <div class="rep-canvas-header">
            <div>
                <h2 class="rep-canvas-title">
                    <?php echo e($currentTabTitle); ?>

                    <span class="rep-canvas-count-badge"><?php echo e(number_format($totalCount)); ?> Bookings</span>
                </h2>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn-pill-outline btn-pill-sm" data-bs-toggle="modal" data-bs-target="#signatoryModal" title="Configure Certified Signatories">
                    <i class="bi bi-pen text-primary"></i> Signatories
                </button>
                <a href="<?php echo e(route('admin.reports.export-csv', array_merge(['report_type' => $reportType], request()->query()))); ?>"
                   onclick="event.preventDefault(); triggerReportExport('<?php echo e(route('admin.reports.export-csv')); ?>', '_self')"
                   class="btn-pill-outline btn-pill-sm" title="Export Current Report to CSV" download>
                    <i class="bi bi-file-earmark-spreadsheet text-success"></i> Export CSV
                </a>
                <a href="<?php echo e(route('admin.reports.print', array_merge(['report_type' => $reportType], request()->query(), ['autoprint' => 1]))); ?>"
                   onclick="event.preventDefault(); triggerReportExport('<?php echo e(route('admin.reports.print')); ?>', '_blank', { autoprint: '1' })"
                   target="_blank" class="btn-pill-teal btn-pill-sm" title="Export Certified PDF">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
                <a href="<?php echo e(route('admin.reports.print', array_merge(['report_type' => $reportType], request()->query()))); ?>"
                   onclick="event.preventDefault(); triggerReportExport('<?php echo e(route('admin.reports.print')); ?>', '_blank')"
                   target="_blank" class="btn-pill-dark btn-pill-sm" title="Print Official Document Preview">
                    <i class="bi bi-printer-fill"></i> Print
                </a>
            </div>
        </div>

        
        <div class="rep-meta-banner">
            <div>
                <div class="rep-meta-col-title">
                    <i class="bi bi-file-earmark-text text-primary"></i> Report Info
                </div>
                <ul class="rep-meta-list">
                    <li class="rep-meta-item">
                        <span class="rep-meta-label">Timeline:</span>
                        <span class="rep-meta-val"><?php echo e($dateRangeLabel); ?></span>
                    </li>
                    <li class="rep-meta-item">
                        <span class="rep-meta-label">Export Date:</span>
                        <span class="rep-meta-val"><?php echo e(now()->format('m/d/Y')); ?></span>
                    </li>
                    <li class="rep-meta-item">
                        <span class="rep-meta-label">Prepared By:</span>
                        <span class="rep-meta-val"><?php echo e($preparedByName); ?> (<?php echo e($preparedByTitle); ?>)</span>
                    </li>
                </ul>
            </div>
            <div>
                <div class="rep-meta-col-title">
                    <i class="bi bi-funnel text-primary"></i> Filter Criteria
                </div>
                <ul class="rep-meta-list">
                    <li class="rep-meta-item">
                        <span class="rep-meta-label">Period Preset:</span>
                        <span class="rep-meta-val"><?php echo e(ucwords(str_replace('_', ' ', $datePreset))); ?></span>
                    </li>
                    <li class="rep-meta-item">
                        <span class="rep-meta-label">Facility:</span>
                        <span class="rep-meta-val">
                            <?php echo e($facilityId && $facilityId !== 'all' ? ($facilities->firstWhere('id', $facilityId)->name ?? 'Selected') : 'All Facilities'); ?>

                        </span>
                    </li>
                    <li class="rep-meta-item">
                        <span class="rep-meta-label">Status Filter:</span>
                        <span class="rep-meta-val">
                            <?php echo e($status && $status !== 'all' ? ucwords(str_replace('_', ' ', $status)) : 'All Statuses'); ?>

                        </span>
                    </li>
                </ul>
            </div>
        </div>

        
        <div class="rep-kpi-strip">
            <?php if($reportType === 'dispatch'): ?>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number"><?php echo e(number_format($dispatchBookingsCount)); ?></span>
                        <span class="rep-kpi-trend positive"><i class="bi bi-truck"></i> Dispatched</span>
                    </div>
                    <div class="rep-kpi-desc">Setup Dispatches</div>
                    <div class="rep-kpi-note">Events requiring mobilization</div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number"><?php echo e(number_format($totalLogisticsUnits)); ?></span>
                        <span class="rep-kpi-trend neutral"><i class="bi bi-boxes"></i> Units</span>
                    </div>
                    <div class="rep-kpi-desc">Equipment Pieces</div>
                    <div class="rep-kpi-note">Chairs, tables, sound systems</div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number"><?php echo e($facilityBreakdown->count()); ?></span>
                        <span class="rep-kpi-trend positive"><i class="bi bi-building"></i> Venues</span>
                    </div>
                    <div class="rep-kpi-desc">Active Venues</div>
                    <div class="rep-kpi-note"><?php echo e($dateRangeLabel); ?></div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number"><?php echo e($approvedCount); ?></span>
                        <span class="rep-kpi-trend warning"><i class="bi bi-check-circle"></i> Ready</span>
                    </div>
                    <div class="rep-kpi-desc">Approved Bookings</div>
                    <div class="rep-kpi-note">Cleared for venue setup</div>
                </div>
            <?php elseif($reportType === 'cancellations'): ?>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number"><?php echo e(number_format($cancelledCount)); ?></span>
                        <span class="rep-kpi-trend negative"><i class="bi bi-arrow-down-right"></i> Lapsed</span>
                    </div>
                    <div class="rep-kpi-desc">Total Cancelled</div>
                    <div class="rep-kpi-note">Slots returned to inventory</div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number">₱<?php echo e(number_format($rawReservations->sum('total_price'), 2)); ?></span>
                        <span class="rep-kpi-trend warning">Released</span>
                    </div>
                    <div class="rep-kpi-desc">Original Booking Value</div>
                    <div class="rep-kpi-note">Released capacity value</div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number">₱<?php echo e(number_format($totalForfeitedDeposits, 2)); ?></span>
                        <span class="rep-kpi-trend positive"><i class="bi bi-shield-lock"></i> Retained</span>
                    </div>
                    <div class="rep-kpi-desc">Forfeited Deposits</div>
                    <div class="rep-kpi-note">Retained institutional funds</div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number">₱<?php echo e(number_format($totalRefundedAmount, 2)); ?></span>
                        <span class="rep-kpi-trend neutral"><i class="bi bi-arrow-return-left"></i> Returned</span>
                    </div>
                    <div class="rep-kpi-desc">Refunds Processed</div>
                    <div class="rep-kpi-note">Security deposits returned</div>
                </div>
            <?php else: ?>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number"><?php echo e(number_format($totalCount)); ?></span>
                        <span class="rep-kpi-trend positive"><i class="bi bi-arrow-up-right"></i> 100%</span>
                    </div>
                    <div class="rep-kpi-desc">Total Bookings</div>
                    <div class="rep-kpi-note"><?php echo e($dateRangeLabel); ?></div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number">₱<?php echo e(number_format($totalCollected, 2)); ?></span>
                        <span class="rep-kpi-trend positive"><i class="bi bi-check2"></i> Confirmed</span>
                    </div>
                    <div class="rep-kpi-desc">Revenue Collected</div>
                    <div class="rep-kpi-note"><?php echo e($approvedCount); ?> confirmed approved</div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number">₱<?php echo e(number_format($pendingReceivables, 2)); ?></span>
                        <span class="rep-kpi-trend warning"><i class="bi bi-hourglass-split"></i> Awaiting</span>
                    </div>
                    <div class="rep-kpi-desc">Pending Receivables</div>
                    <div class="rep-kpi-note"><?php echo e($pendingCount); ?> awaiting review/cashier</div>
                </div>
                <div class="rep-kpi-item">
                    <div class="rep-kpi-num-row">
                        <span class="rep-kpi-number"><?php echo e(number_format($cancelledCount)); ?></span>
                        <span class="rep-kpi-trend negative"><i class="bi bi-dash"></i> Released</span>
                    </div>
                    <div class="rep-kpi-desc">Cancelled / Expired</div>
                    <div class="rep-kpi-note">Slots returned to calendar</div>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="rep-insight-banner">
            <div class="rep-insight-icon-wrap">
                <i class="bi bi-lightbulb-fill"></i>
            </div>
            <div>
                <div class="rep-insight-heading">Operational Summary &amp; Key Insight</div>
                <div class="rep-insight-body"><?php echo $insightText; ?></div>
            </div>
        </div>

        

        
        <?php if($reportType === 'reservations'): ?>
            <?php if($groupBy === 'none'): ?>
                
                <div class="rep-table-shell">
                    <table class="rep-table-pro">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Reference</th>
                                <th>Client / Event</th>
                                <th>Facility</th>
                                <th>Schedule / Date</th>
                                <th>Contact Email</th>
                                <th>Payment</th>
                                <th style="text-align: right;">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $rawReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $isOvernight = ($res->end_date && $res->end_date->gt($res->start_date)) || ($res->end_time < $res->start_time);
                                    $ps = $res->payment_status ?? 'unpaid';
                                    $pillClass = match($ps) {
                                        'paid' => 'rep-pill-paid',
                                        'cash_pending' => 'rep-pill-cash-pending',
                                        'partially_paid' => 'rep-pill-partial',
                                        default => 'rep-pill-pending',
                                    };
                                ?>
                                <tr>
                                    <td class="text-muted fw-bold"><?php echo e($idx + 1); ?></td>
                                    <td>
                                        <span class="font-monospace fw-bold text-dark"><?php echo e($res->booking_reference); ?></span>
                                        <?php if($isOvernight): ?>
                                            <span class="badge bg-dark ms-1" style="font-size: 0.65rem;" title="Night/Overnight Schedule">
                                                <i class="bi bi-moon-stars-fill"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo e($res->user->name ?? 'Guest User'); ?></div>
                                        <div class="text-muted small">
                                            <?php echo e($res->event_title ?: ($res->purpose ? \Illuminate\Support\Str::limit($res->purpose, 35) : 'Facility Booking')); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-primary"><?php echo e($res->facility->name ?? 'Facility'); ?></div>
                                        <div class="text-muted small"><?php echo e($res->facility->category->name ?? 'General'); ?></div>
                                    </td>
                                    <td>
                                        <div><?php echo e(\Carbon\Carbon::parse($res->start_date)->format('M d, Y')); ?></div>
                                        <div class="text-muted small">
                                            <?php echo e(\Carbon\Carbon::parse($res->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($res->end_time)->format('g:i A')); ?>

                                            <?php if($isOvernight): ?>
                                                <span class="text-primary fw-bold">(Next Day)</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div><?php echo e($res->user->email ?? 'N/A'); ?></div>
                                        <div class="text-muted small"><?php echo e($res->user->phone_number ?? 'No phone'); ?></div>
                                    </td>
                                    <td>
                                        <span class="rep-pill <?php echo e($pillClass); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $ps))); ?>

                                        </span>
                                    </td>
                                    <td style="text-align: right;" class="fw-bold font-monospace text-dark">
                                        ₱<?php echo e(number_format($res->total_price, 2)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        No reservations found matching the current filters.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 px-1">
                    <span class="text-muted small">Showing <?php echo e($totalCount); ?> records for <?php echo e($dateRangeLabel); ?></span>
                    <div class="fw-bold text-dark font-monospace">
                        Grand Total: ₱<?php echo e(number_format($totalPriceSum, 2)); ?>

                    </div>
                </div>

            <?php else: ?>
                
                <?php $__empty_1 = true; $__currentLoopData = $groupedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $groupItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mb-4">
                        <div class="rep-group-divider d-flex justify-content-between align-items-center">
                            <div><i class="bi bi-folder2-open me-2 text-primary"></i> <?php echo e(strtoupper($groupName)); ?></div>
                            <span class="badge bg-primary rounded-pill px-2.5 py-1"><?php echo e($groupItems->count()); ?> items</span>
                        </div>
                        <div class="rep-table-shell" style="border-top: none; border-radius: 0;">
                            <table class="rep-table-pro">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Reference</th>
                                        <th>Client / Event</th>
                                        <th>Facility</th>
                                        <th>Schedule</th>
                                        <th>Payment Status</th>
                                        <th style="text-align: right;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $groupItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gIdx => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $ps = $res->payment_status ?? 'unpaid';
                                            $pillClass = match($ps) {
                                                'paid' => 'rep-pill-paid',
                                                'cash_pending' => 'rep-pill-cash-pending',
                                                'partially_paid' => 'rep-pill-partial',
                                                default => 'rep-pill-pending',
                                            };
                                        ?>
                                        <tr>
                                            <td class="text-muted fw-bold"><?php echo e($gIdx + 1); ?></td>
                                            <td class="font-monospace fw-bold text-dark"><?php echo e($res->booking_reference); ?></td>
                                            <td>
                                                <div class="fw-bold text-dark"><?php echo e($res->user->name ?? 'Guest User'); ?></div>
                                                <div class="text-muted small"><?php echo e($res->event_title ?: ($res->user->email ?? 'N/A')); ?></div>
                                            </td>
                                            <td><?php echo e($res->facility->name ?? 'N/A'); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($res->start_date)->format('M d, Y')); ?> (<?php echo e(\Carbon\Carbon::parse($res->start_time)->format('g:i A')); ?>)</td>
                                            <td>
                                                <span class="rep-pill <?php echo e($pillClass); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $ps))); ?>

                                                </span>
                                            </td>
                                            <td style="text-align: right;" class="fw-bold font-monospace text-dark">₱<?php echo e(number_format($res->total_price, 2)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="rep-group-sum font-monospace">
                            Subtotal (<?php echo e($groupName); ?>): ₱<?php echo e(number_format($groupItems->sum('total_price'), 2)); ?> &bull; <?php echo e($groupItems->count()); ?> bookings
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5 text-muted">No reservations found.</div>
                <?php endif; ?>
                <div class="text-end fw-bold fs-6 text-dark font-monospace mt-3">
                    Overall Total: ₱<?php echo e(number_format($totalPriceSum, 2)); ?> (<?php echo e($totalCount); ?> bookings)
                </div>
            <?php endif; ?>

        
        <?php elseif($reportType === 'income'): ?>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="text-muted small text-uppercase fw-bold">Cash / Over-The-Counter</div>
                        <div class="fs-4 fw-bold text-dark font-monospace mt-1">₱<?php echo e(number_format($cashCollected, 2)); ?></div>
                        <div class="small text-muted">Direct campus cashier receipts</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="text-muted small text-uppercase fw-bold">Digital / Online Gateway</div>
                        <div class="fs-4 fw-bold text-primary font-monospace mt-1">₱<?php echo e(number_format($digitalCollected, 2)); ?></div>
                        <div class="small text-muted">E-wallets, cards, PayMongo gateway</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="text-muted small text-uppercase fw-bold">Pending Settlement</div>
                        <div class="fs-4 fw-bold text-warning font-monospace mt-1">₱<?php echo e(number_format($pendingReceivables, 2)); ?></div>
                        <div class="small text-muted">Awaiting cashier settlement before deadline</div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mt-4 mb-2 text-dark">Revenue Per Facility</h5>
            <div class="rep-table-shell mb-4">
                <table class="rep-table-pro">
                    <thead>
                        <tr>
                            <th>Facility Name</th>
                            <th>Bookings Count</th>
                            <th>Paid Revenue</th>
                            <th>Pending Receivable</th>
                            <th style="text-align: right;">Gross Value</th>
                            <th style="text-align: right;">Share of Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $facilityBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $share = $totalCollected > 0 ? round(($fb['paid_total'] / $totalCollected) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo e($fb['name']); ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo e($fb['count']); ?> bookings</span></td>
                                <td class="text-success fw-bold font-monospace">₱<?php echo e(number_format($fb['paid_total'], 2)); ?></td>
                                <td class="text-warning fw-bold font-monospace">₱<?php echo e(number_format($fb['pending_total'], 2)); ?></td>
                                <td style="text-align: right;" class="fw-bold font-monospace text-dark">₱<?php echo e(number_format($fb['total_revenue'], 2)); ?></td>
                                <td style="text-align: right;" class="fw-bold text-primary"><?php echo e($share); ?>%</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <h5 class="fw-bold mt-4 mb-2 text-dark">Revenue Per Facility Category</h5>
            <div class="rep-table-shell">
                <table class="rep-table-pro">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Bookings</th>
                            <th>Paid Revenue</th>
                            <th style="text-align: right;">Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $categoryBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo e($cb['category']); ?></td>
                                <td><?php echo e($cb['count']); ?></td>
                                <td class="text-success fw-bold font-monospace">₱<?php echo e(number_format($cb['revenue'], 2)); ?></td>
                                <td style="text-align: right;" class="fw-bold font-monospace text-dark">₱<?php echo e(number_format($cb['total'], 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

        
        <?php elseif($reportType === 'facilities'): ?>
            <?php $__currentLoopData = $facilityBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card border mb-4 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark"><?php echo e($fac['name']); ?></h5>
                            <div class="small text-muted"><?php echo e($fac['count']); ?> total bookings by <?php echo e($fac['unique_clients']); ?> distinct clients</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-dark font-monospace fs-5">₱<?php echo e(number_format($fac['total_revenue'], 2)); ?></div>
                            <div class="small text-success fw-bold">₱<?php echo e(number_format($fac['paid_total'], 2)); ?> paid</div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="rep-table-shell" style="border: none; border-radius: 0;">
                            <table class="rep-table-pro mb-0">
                                <thead>
                                    <tr>
                                        <th>Ref #</th>
                                        <th>Reserved By (Client)</th>
                                        <th>Contact Information</th>
                                        <th>Event Schedule</th>
                                        <th>Payment Status</th>
                                        <th style="text-align: right;">Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $fac['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $ips = $item->payment_status ?? 'unpaid';
                                            $pillClass = match($ips) {
                                                'paid' => 'rep-pill-paid',
                                                'cash_pending' => 'rep-pill-cash-pending',
                                                'partially_paid' => 'rep-pill-partial',
                                                default => 'rep-pill-pending',
                                            };
                                        ?>
                                        <tr>
                                            <td class="font-monospace fw-bold"><?php echo e($item->booking_reference); ?></td>
                                            <td class="fw-bold text-dark"><?php echo e($item->user->name ?? 'Guest User'); ?></td>
                                            <td class="small text-muted"><?php echo e($item->user->email ?? 'N/A'); ?> | <?php echo e($item->user->phone_number ?? 'N/A'); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($item->start_date)->format('M d, Y')); ?> (<?php echo e(\Carbon\Carbon::parse($item->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($item->end_time)->format('g:i A')); ?>)</td>
                                            <td>
                                                <span class="rep-pill <?php echo e($pillClass); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $ips))); ?>

                                                </span>
                                            </td>
                                            <td style="text-align: right;" class="font-monospace fw-bold">₱<?php echo e(number_format($item->total_price, 2)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-3 text-muted">No reservations for this venue.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php elseif($reportType === 'clients'): ?>
            <div class="rep-table-shell">
                <table class="rep-table-pro">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Client Name</th>
                            <th>Email &amp; Contact</th>
                            <th style="text-align: center;">Total Bookings</th>
                            <th style="text-align: center;">Paid Bookings</th>
                            <th style="text-align: right;">Total Spent</th>
                            <th>Latest Reservation</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $cNum = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $clientBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="fw-bold text-muted"><?php echo e($cNum++); ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($cb['name']); ?></div>
                                    <div class="small text-muted">Account ID: #<?php echo e($cb['user']->id ?? 'N/A'); ?></div>
                                </td>
                                <td>
                                    <div><?php echo e($cb['email']); ?></div>
                                    <div class="small text-muted"><?php echo e($cb['phone']); ?></div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge bg-primary rounded-pill px-2.5 py-1"><?php echo e($cb['count']); ?></span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge bg-success rounded-pill px-2.5 py-1"><?php echo e($cb['paid_count']); ?></span>
                                </td>
                                <td style="text-align: right;" class="font-monospace fw-bold text-dark">
                                    ₱<?php echo e(number_format($cb['total_spent'], 2)); ?>

                                </td>
                                <td>
                                    <?php echo e($cb['latest_booking'] ? \Carbon\Carbon::parse($cb['latest_booking'])->format('M d, Y') : 'N/A'); ?>

                                </td>
                                <td style="text-align: center;">
                                    <a href="<?php echo e(route('admin.reports.index', ['report_type' => 'reservations', 'client_id' => $cb['user']->id ?? null])); ?>"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        View Bookings
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">No client reservation activity recorded.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        
        <?php elseif($reportType === 'dispatch'): ?>
            <div class="rep-table-shell">
                <table class="rep-table-pro">
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Ref # &amp; Event</th>
                            <th>Facility</th>
                            <th>Schedule &amp; Timings</th>
                            <th>Client &amp; Contact</th>
                            <th style="min-width: 260px;">Requested Equipment &amp; Logistics</th>
                            <th style="text-align: right;">Arrangement Fee</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $rawReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dIdx => $dRes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $arrs = is_string($dRes->arrangements) ? json_decode($dRes->arrangements, true) : $dRes->arrangements;
                                $isOvernight = ($dRes->end_date && $dRes->end_date->gt($dRes->start_date)) || ($dRes->end_time < $dRes->start_time);
                            ?>
                            <tr>
                                <td class="fw-bold text-muted"><?php echo e($dIdx + 1); ?></td>
                                <td>
                                    <div class="font-monospace fw-bold text-dark"><?php echo e($dRes->booking_reference); ?></div>
                                    <div class="small fw-semibold text-primary"><?php echo e($dRes->event_title ?: ($dRes->purpose ?: 'Venue Reservation')); ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($dRes->facility->name ?? 'Venue'); ?></div>
                                    <div class="small text-muted"><?php echo e($dRes->facility->category->name ?? 'General'); ?></div>
                                </td>
                                <td>
                                    <div><?php echo e(\Carbon\Carbon::parse($dRes->start_date)->format('M d, Y')); ?></div>
                                    <div class="small text-muted">
                                        <?php echo e(\Carbon\Carbon::parse($dRes->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($dRes->end_time)->format('g:i A')); ?>

                                        <?php if($isOvernight): ?>
                                            <span class="badge bg-dark ms-1">Overnight</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($dRes->user->name ?? ($dRes->reserved_by_name ?? 'Guest User')); ?></div>
                                    <div class="small text-muted"><?php echo e($dRes->user->phone_number ?? ($dRes->reserved_by_phone ?? 'No phone')); ?></div>
                                </td>
                                <td>
                                    <?php if(is_array($arrs) && !empty($arrs)): ?>
                                        <?php $__currentLoopData = $arrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="rep-eq-chip">
                                                <span class="qty"><?php echo e($arrItem['quantity'] ?? 1); ?>x</span>
                                                <span><?php echo e($arrItem['name'] ?? 'Equipment'); ?></span>
                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">No additional equipment requested</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;" class="font-monospace fw-bold text-dark">
                                    ₱<?php echo e(number_format($dRes->arrangements_total ?? 0, 2)); ?>

                                </td>
                                <td>
                                    <?php
                                        $dStatus = $dRes->status;
                                        $dClass = match($dStatus) {
                                            'approved' => 'rep-pill-paid',
                                            'pending' => 'rep-pill-pending',
                                            default => 'rep-pill-cancelled',
                                        };
                                    ?>
                                    <span class="rep-pill <?php echo e($dClass); ?>">
                                        <?php echo e(ucfirst($dStatus)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-truck fs-2 d-block mb-2 text-muted"></i>
                                    No setup dispatches found matching the active filters.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        
        <?php elseif($reportType === 'cancellations'): ?>
            <div class="rep-table-shell">
                <table class="rep-table-pro">
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Ref # &amp; Event</th>
                            <th>Facility</th>
                            <th>Client Details</th>
                            <th>Scheduled Date</th>
                            <th>Cancellation Details</th>
                            <th style="text-align: right;">Gross Price</th>
                            <th style="text-align: right;">Deposit Paid</th>
                            <th>Deposit Status</th>
                            <th style="text-align: right;">Forfeited Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $rawReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cIdx => $cRes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $depStatus = $cRes->security_deposit_status ?? 'N/A';
                                $forfeited = ($depStatus === 'forfeited') ? (float)($cRes->security_deposit_paid ?? 0) : 0.00;
                                $cancelledDate = $cRes->cancelled_at 
                                    ? \Carbon\Carbon::parse($cRes->cancelled_at)->format('M d, Y h:i A') 
                                    : ($cRes->updated_at ? $cRes->updated_at->format('M d, Y h:i A') : 'N/A');
                            ?>
                            <tr>
                                <td class="fw-bold text-muted"><?php echo e($cIdx + 1); ?></td>
                                <td>
                                    <div class="font-monospace fw-bold text-dark"><?php echo e($cRes->booking_reference); ?></div>
                                    <div class="small text-muted"><?php echo e($cRes->event_title ?: 'Facility Booking'); ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($cRes->facility->name ?? 'Venue'); ?></div>
                                    <div class="small text-muted"><?php echo e($cRes->facility->category->name ?? 'General'); ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo e($cRes->user->name ?? 'Guest User'); ?></div>
                                    <div class="small text-muted"><?php echo e($cRes->user->phone_number ?? ($cRes->user->email ?? 'N/A')); ?></div>
                                </td>
                                <td>
                                    <div><?php echo e(\Carbon\Carbon::parse($cRes->start_date)->format('M d, Y')); ?></div>
                                    <div class="small text-muted"><?php echo e(\Carbon\Carbon::parse($cRes->start_time)->format('g:i A')); ?></div>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-danger">
                                        <?php echo e($cRes->cancellation_reason ?: ($cRes->status === 'expired_unpaid_cancelled' ? 'Expired unpaid payment window' : 'Cancelled by user/admin')); ?>

                                    </div>
                                    <div class="small text-muted"><?php echo e($cancelledDate); ?></div>
                                </td>
                                <td style="text-align: right;" class="font-monospace">
                                    ₱<?php echo e(number_format($cRes->total_price ?? 0, 2)); ?>

                                </td>
                                <td style="text-align: right;" class="font-monospace text-primary fw-bold">
                                    ₱<?php echo e(number_format($cRes->security_deposit_paid ?? 0, 2)); ?>

                                </td>
                                <td>
                                    <?php if($depStatus === 'forfeited'): ?>
                                        <span class="rep-pill rep-pill-cancelled">Forfeited</span>
                                    <?php elseif($depStatus === 'refunded'): ?>
                                        <span class="rep-pill rep-pill-paid">Refunded</span>
                                    <?php elseif($depStatus === 'waived' || $cRes->is_fee_waived): ?>
                                        <span class="badge bg-secondary rounded-pill px-2 py-1">Waived</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark border"><?php echo e(ucfirst($depStatus)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;" class="font-monospace fw-bold text-danger">
                                    ₱<?php echo e(number_format($forfeited, 2)); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="bi bi-shield-check fs-2 d-block mb-2 text-success"></i>
                                    No cancellations or forfeitures recorded for the selected criteria.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 gap-4">
                <div class="small text-muted">
                    Total Deposits Forfeited: <strong class="text-danger font-monospace">₱<?php echo e(number_format($totalForfeitedDeposits, 2)); ?></strong>
                </div>
                <div class="small text-muted">
                    Total Refunds Issued: <strong class="text-success font-monospace">₱<?php echo e(number_format($totalRefundedAmount, 2)); ?></strong>
                </div>
            </div>

        
        <?php elseif($reportType === 'analytics'): ?>
            <div class="row g-4 mb-4">
                
                <div class="col-lg-7">
                    <div class="p-3 border rounded-3 bg-white">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-range me-2 text-primary"></i> Monthly Bookings Trend (<?php echo e(now()->year); ?>)</h6>
                        <div style="height: 220px; display: flex; align-items: flex-end; gap: 0.6rem; padding-top: 1rem; border-bottom: 2px solid #e2e8f0;">
                            <?php
                                $maxCount = max(collect($monthlyTrend)->pluck('count')->max() ?: 1, 5);
                            ?>
                            <?php $__currentLoopData = $monthlyTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mKey => $mData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $heightPct = round(($mData['count'] / $maxCount) * 100);
                                ?>
                                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;">
                                    <span style="font-size: 0.72rem; font-weight: 700; color: #1e40af; margin-bottom: 4px;"><?php echo e($mData['count'] > 0 ? $mData['count'] : ''); ?></span>
                                    <div style="width: 100%; max-width: 32px; height: <?php echo e(max($heightPct, 4)); ?>%; background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 4px 4px 0 0; transition: height 0.3s;" title="<?php echo e($mData['full']); ?>: <?php echo e($mData['count']); ?> bookings"></div>
                                    <span style="font-size: 0.72rem; font-weight: 600; color: #64748b; margin-top: 6px;"><?php echo e($mKey); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="col-lg-5">
                    <div class="p-3 border rounded-3 bg-white">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart me-2 text-primary"></i> Payment Status Distribution</h6>
                        <div class="d-flex flex-column gap-3 mt-3">
                            <?php $__currentLoopData = $paymentDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pName => $pCount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $pShare = $totalCount > 0 ? round(($pCount / $totalCount) * 100) : 0;
                                    $barColor = match($pName) {
                                        'Paid' => '#10b981',
                                        'Cash Pending' => '#f59e0b',
                                        'Partial Paid' => '#f97316',
                                        default => '#64748b'
                                    };
                                ?>
                                <div>
                                    <div class="d-flex justify-content-between small fw-bold mb-1">
                                        <span><?php echo e($pName); ?></span>
                                        <span><?php echo e($pCount); ?> (<?php echo e($pShare); ?>%)</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 99px; background-color: #f1f5f9;">
                                        <div class="progress-bar" role="progressbar" style="width: <?php echo e($pShare); ?>%; background-color: <?php echo e($barColor); ?>; border-radius: 99px;"></div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    
    <div class="rep-floating-bar" id="repFloatingBar">
        <div class="rep-floating-info">
            <i class="bi bi-file-earmark-bar-graph text-primary"></i>
            <span><?php echo e(number_format($totalCount)); ?> Bookings</span>
        </div>
        <div class="rep-floating-actions">
            <a href="<?php echo e(route('admin.reports.export-csv', array_merge(['report_type' => $reportType], request()->query()))); ?>"
               onclick="event.preventDefault(); triggerReportExport('<?php echo e(route('admin.reports.export-csv')); ?>', '_self')"
               class="btn-pill-outline btn-pill-sm" title="Export Current Report to CSV" download>
                <i class="bi bi-file-earmark-spreadsheet text-success"></i> CSV
            </a>
            <a href="<?php echo e(route('admin.reports.print', array_merge(['report_type' => $reportType], request()->query(), ['autoprint' => 1]))); ?>"
               onclick="event.preventDefault(); triggerReportExport('<?php echo e(route('admin.reports.print')); ?>', '_blank', { autoprint: '1' })"
               target="_blank" class="btn-pill-teal btn-pill-sm" title="Export Certified PDF">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
            <a href="<?php echo e(route('admin.reports.print', array_merge(['report_type' => $reportType], request()->query()))); ?>"
               onclick="event.preventDefault(); triggerReportExport('<?php echo e(route('admin.reports.print')); ?>', '_blank')"
               target="_blank" class="btn-pill-dark btn-pill-sm" title="Print Document">
                <i class="bi bi-printer-fill"></i> Print
            </a>
            <button type="button" class="btn-pill-outline btn-pill-sm" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Back to top">
                <i class="bi bi-arrow-up"></i> Top
            </button>
        </div>
    </div>

</div>


<div class="modal fade" id="signatoryModal" tabindex="-1" aria-labelledby="signatoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rep-modal-card">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="signatoryModalLabel">
                    <i class="bi bi-pen me-2 text-primary"></i> Certified Signatory Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="text-muted small mb-3">
                    Specify official signatories that will be printed on certified PDF documents and administrative printouts.
                </p>

                <div class="mb-3">
                    <label class="rep-field-label">Prepared By (Name)</label>
                    <input type="text" id="modal_prep_name" value="<?php echo e($preparedByName); ?>" class="form-control form-control-sm rounded-3">
                </div>
                <div class="mb-3">
                    <label class="rep-field-label">Prepared By (Title / Designation)</label>
                    <input type="text" id="modal_prep_title" value="<?php echo e($preparedByTitle); ?>" class="form-control form-control-sm rounded-3">
                </div>
                <div class="mb-3">
                    <label class="rep-field-label">Approved By (Name)</label>
                    <input type="text" id="modal_appr_name" value="<?php echo e($approvedByName); ?>" class="form-control form-control-sm rounded-3">
                </div>
                <div class="mb-3">
                    <label class="rep-field-label">Approved By (Title / Designation)</label>
                    <input type="text" id="modal_appr_title" value="<?php echo e($approvedByTitle); ?>" class="form-control form-control-sm rounded-3">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-4" onclick="applySignatorySettings()">Save &amp; Apply</button>
            </div>
        </div>
    </div>
</div>

<script>
    function handlePresetChange(val) {
        var customRow = document.getElementById('customDateRow');
        if (val === 'custom') {
            customRow.classList.remove('d-none');
        } else {
            customRow.classList.add('d-none');
        }
    }

    function toggleAdvancedFilters() {
        var drawer = document.getElementById('advancedFiltersDrawer');
        var chevron = document.getElementById('advancedChevron');
        if (drawer.classList.contains('d-none')) {
            drawer.classList.remove('d-none');
            chevron.classList.replace('bi-chevron-down', 'bi-chevron-up');
        } else {
            drawer.classList.add('d-none');
            chevron.classList.replace('bi-chevron-up', 'bi-chevron-down');
        }
    }

    function applySignatorySettings() {
        var prepName = document.getElementById('modal_prep_name').value;
        var prepTitle = document.getElementById('modal_prep_title').value;
        var apprName = document.getElementById('modal_appr_name').value;
        var apprTitle = document.getElementById('modal_appr_title').value;

        document.getElementById('hidden_prepared_by_name').value = prepName;
        document.getElementById('hidden_prepared_by_title').value = prepTitle;
        document.getElementById('hidden_approved_by_name').value = apprName;
        document.getElementById('hidden_approved_by_title').value = apprTitle;

        // Close modal
        var modalEl = document.getElementById('signatoryModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        // Submit filter form to reload with new signatories
        document.getElementById('reportFilterForm').submit();
    }

    function triggerReportExport(baseUrl, target, extraParams) {
        extraParams = extraParams || {};
        var form = document.getElementById('reportFilterForm');
        var params = new URLSearchParams();

        if (form) {
            var fd = new FormData(form);
            for (var pair of fd.entries()) {
                var val = pair[1];
                if (val !== '' && val !== null && val !== 'all') {
                    params.set(pair[0], val);
                } else if (pair[0] === 'report_type' || pair[0] === 'date_preset') {
                    params.set(pair[0], val);
                }
            }
        }

        // Always ensure report_type is preserved from current active tab
        if (!params.has('report_type')) {
            params.set('report_type', '<?php echo e($reportType); ?>');
        }

        for (var key in extraParams) {
            params.set(key, extraParams[key]);
        }

        var fullUrl = baseUrl + (baseUrl.indexOf('?') === -1 ? '?' : '&') + params.toString();
        if (target === '_blank') {
            window.open(fullUrl, '_blank');
        } else {
            window.location.href = fullUrl;
        }
    }

    (function repFloatingBarInit() {
        var bar = document.getElementById('repFloatingBar');
        if (!bar) return;
        function checkScroll() {
            if (window.scrollY > 160) {
                bar.classList.add('visible');
            } else {
                bar.classList.remove('visible');
            }
        }
        window.addEventListener('scroll', checkScroll, { passive: true });
        checkScroll();
    })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>