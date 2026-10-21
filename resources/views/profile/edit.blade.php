@extends('layouts.app')

@section('title', 'My Profile - ' . branding('system_name', 'Platform'))

@section('content')
    @include('components.custom-alert')
    <style>
        /* ═══════════════════════════════════════════════════════════════
           My Profile — {{ branding('system_name', 'Platform') }} Redesign
           ═══════════════════════════════════════════════════════════════ */
        :root {
            --pf-dark: var(--brand-dark, #0f172a);
            --pf-gray: #64748b;
            --pf-border: #e2e8f0;
            --pf-surface: #f8fafc;
            --pf-white: #ffffff;
            --pf-success: #10b981;
            --pf-amber: #f59e0b;
            --pf-danger: #ef4444;
            --pf-radius: 20px;
            --pf-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.04);
            --pf-shadow-lg: 0 4px 6px rgba(0,0,0,0.04), 0 20px 50px rgba(0,0,0,0.06);
        }

        .pf-page {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 50%, #f8fafc 100%);
            min-height: 100vh;
            padding: 1.75rem 0 4rem;
        }

        /* ── Identity Banner ─────────────────────────────── */
        .pf-banner {
            border-radius: var(--pf-radius);
            background:
                radial-gradient(circle at 85% 20%, rgba(var(--brand-primary-rgb, 37,99,235), 0.35) 0%, transparent 45%),
                radial-gradient(circle at 10% 90%, rgba(var(--brand-secondary-rgb, 124,58,237), 0.25) 0%, transparent 40%),
                linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #1d2f50 100%);
            padding: 2.5rem 2.5rem 2.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--pf-shadow-lg);
        }
        .pf-banner::before {
            content: '';
            position: absolute; inset: 0;
            opacity: 0.05;
            background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0);
            background-size: 20px 20px;
        }

        .pf-banner-name {
            font-size: 1.85rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.03em;
            margin: 0;
            line-height: 1.15;
        }
        .pf-banner-sub {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0.35rem 0 0;
        }
        .pf-banner-email {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #cbd5e1;
            font-size: 0.85rem;
            font-weight: 600;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            padding: 0.35rem 0.9rem;
            border-radius: 50px;
            margin-top: 0.75rem;
        }
        .pf-banner-email i { font-size: 0.9rem; }

        .pf-banner-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.85rem;
        }
        .pf-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.8rem;
            border-radius: 50px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .pf-badge i { font-size: 0.8rem; }
        .pf-badge.role {
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .pf-badge.member-since {
            background: rgba(255,255,255,0.08);
            color: #cbd5e1;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .pf-badge.uid {
            background: rgba(255,255,255,0.08);
            color: #cbd5e1;
            border: 1px solid rgba(255,255,255,0.15);
            font-family: 'JetBrains Mono', monospace;
        }

        /* ── Summary KPI band ────────────────────────────── */
        .pf-kpi-row { margin-top: 1.5rem; display: grid; gap: 0.75rem; grid-template-columns: repeat(4, 1fr); }
        .pf-kpi {
            background: var(--pf-white);
            border: 1px solid var(--pf-border);
            border-radius: 16px;
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--pf-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .pf-kpi:hover { transform: translateY(-2px); box-shadow: var(--pf-shadow-lg); }
        .pf-kpi-icon {
            width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
        }
        .pf-kpi-icon.blue { background: linear-gradient(135deg, rgba(37,99,235,0.14), rgba(37,99,235,0.06)); color: #2563eb; border: 1px solid rgba(37,99,235,0.15); }
        .pf-kpi-icon.green { background: linear-gradient(135deg, rgba(16,185,129,0.14), rgba(16,185,129,0.06)); color: #059669; border: 1px solid rgba(16,185,129,0.15); }
        .pf-kpi-icon.amber { background: linear-gradient(135deg, rgba(245,158,11,0.14), rgba(245,158,11,0.06)); color: #d97706; border: 1px solid rgba(245,158,11,0.15); }
        .pf-kpi-icon.purple { background: linear-gradient(135deg, rgba(124,58,237,0.14), rgba(124,58,237,0.06)); color: #7c3aed; border: 1px solid rgba(124,58,237,0.15); }
        .pf-kpi-num { font-size: 1.5rem; font-weight: 800; color: var(--pf-dark); line-height: 1; letter-spacing: -0.02em; }
        .pf-kpi-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #94a3b8; margin-top: 0.3rem; }
        .pf-kpi-note { font-size: 0.72rem; color: var(--pf-gray); font-weight: 600; margin-top: 0.15rem; }

        /* ── Card ────────────────────────────────────────── */
        .pf-card {
            background: var(--pf-white);
            border: 1px solid var(--pf-border);
            border-radius: var(--pf-radius);
            box-shadow: var(--pf-shadow);
            overflow: hidden;
        }
        .pf-card-head {
            padding: 1.4rem 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; gap: 0.9rem;
        }
        .pf-card-head-icon {
            width: 40px; height: 40px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.05rem; flex-shrink: 0;
            background: linear-gradient(135deg, rgba(var(--brand-primary-rgb, 37,99,235), 0.12), rgba(var(--brand-primary-rgb, 37,99,235), 0.05));
            color: var(--brand-primary, #2563eb);
        }
        .pf-card-head-icon.amber { background: linear-gradient(135deg, rgba(245,158,11,0.14), rgba(245,158,11,0.05)); color: #d97706; }
        .pf-card-head-icon.green { background: linear-gradient(135deg, rgba(16,185,129,0.14), rgba(16,185,129,0.05)); color: #059669; }
        .pf-card-head-icon.red { background: linear-gradient(135deg, rgba(239,68,68,0.14), rgba(239,68,68,0.05)); color: #ef4444; }
        .pf-card-title { font-size: 1.05rem; font-weight: 800; color: var(--pf-dark); margin: 0; letter-spacing: -0.02em; }
        .pf-card-sub { font-size: 0.75rem; color: #94a3b8; font-weight: 600; margin: 0.1rem 0 0; }
        .pf-card-body { padding: 1.75rem; }

        /* ── Tab navigation ──────────────────────────────── */
        .pf-tabs {
            display: flex; gap: 0.5rem; padding: 0.35rem;
            background: var(--pf-white);
            border: 1px solid var(--pf-border);
            border-radius: 14px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .pf-tabs .nav-link {
            color: var(--pf-gray) !important;
            font-weight: 700 !important;
            padding: 0.7rem 1.3rem;
            border-radius: 11px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.82rem;
            border: 1.5px solid transparent;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            flex: 1; justify-content: center;
            background: transparent !important;
        }
        .pf-tabs .nav-link:hover { color: var(--pf-dark) !important; background: var(--pf-surface) !important; }
        .pf-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed)) !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .pf-tabs .nav-link i { font-size: 1rem; }
        .pf-tabs .nav-link .pf-tab-count {
            background: rgba(255,255,255,0.22);
            padding: 0.1rem 0.5rem;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 800;
        }
        .pf-tabs .nav-link:not(.active) .pf-tab-count {
            background: #eef2f7;
            color: #64748b;
        }

        /* ── Sidebar identity card ───────────────────────── */
        .pf-side { margin-bottom: 1.5rem; }
        .pf-side-inner { text-align: center; padding: 2rem 1.75rem 1.5rem; }

        .pf-avatar-wrap { position: relative; display: inline-block; margin-bottom: 1rem; }
        .pf-avatar {
            width: 104px; height: 104px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border: 4px solid var(--pf-white);
            border-radius: 50%;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem; font-weight: 800;
            color: var(--pf-dark);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .pf-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .pf-avatar-wrap:hover .pf-avatar { transform: scale(1.04); box-shadow: 0 12px 32px rgba(0,0,0,0.15); }

        .pf-avatar-overlay {
            position: absolute; inset: 4px; border-radius: 50%;
            background: rgba(0,0,0,0.55);
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.1rem;
            opacity: 0; transition: opacity 0.25s ease; cursor: pointer;
        }
        .pf-avatar-wrap:hover .pf-avatar-overlay { opacity: 1; }
        .pf-avatar-overlay i { font-size: 1.3rem; color: #fff; }
        .pf-avatar-overlay span { font-size: 0.6rem; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 0.5px; }

        .pf-avatar-progress { display: none; margin: 0.5rem 0 1rem; }
        .pf-avatar-progress .spinner-border { width: 1.1rem; height: 1.1rem; border-width: 0.18em; }
        .pf-avatar-progress span { font-size: 0.7rem; color: var(--pf-gray); font-weight: 600; display: block; margin-top: 0.35rem; }

        .pf-side-actions { display: flex; justify-content: center; gap: 0.4rem; margin-bottom: 1.25rem; }
        .pf-act {
            padding: 0.32rem 0.75rem; border-radius: 8px; font-size: 0.68rem; font-weight: 700;
            border: 1.5px solid var(--pf-border); background: var(--pf-white);
            color: var(--pf-gray); cursor: pointer; transition: all 0.2s ease;
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .pf-act:hover { background: var(--pf-surface); color: #334155; border-color: #cbd5e1; }
        .pf-act.danger { color: var(--pf-danger); border-color: rgba(239,68,68,0.2); }
        .pf-act.danger:hover { background: #fef2f2; border-color: var(--pf-danger); }

        .pf-side-name { font-size: 1.25rem; font-weight: 800; color: var(--pf-dark); margin: 0 0 0.25rem; letter-spacing: -0.02em; }
        .pf-side-meta { font-size: 0.78rem; color: var(--pf-gray); font-weight: 500; margin: 0; }

        .pf-side-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: #f1f5f9; margin-top: 1.25rem; border-top: 1px solid #f1f5f9; }
        .pf-side-stat { padding: 0.9rem 1.15rem; background: var(--pf-white); display: flex; flex-direction: column; gap: 0.25rem; text-align: left; }
        .pf-side-stat-label { font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #94a3b8; }
        .pf-side-stat-value { font-size: 0.82rem; font-weight: 700; color: var(--pf-dark); }

        /* ── Section dividers (forms) ────────────────────── */
        .pf-divider {
            display: flex; align-items: center; gap: 1rem;
            margin: 2.25rem 0 1.5rem;
        }
        .pf-divider::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, #e2e8f0, transparent); }
        .pf-divider-icon {
            width: 40px; height: 40px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center; font-size: 1rem;
            background: linear-gradient(135deg, rgba(var(--brand-primary-rgb, 37,99,235), 0.12), rgba(var(--brand-primary-rgb, 37,99,235), 0.05));
            color: var(--brand-primary, #2563eb);
        }
        .pf-divider-label { font-weight: 800; font-size: 0.92rem; color: #0f172a; letter-spacing: -0.01em; }
        .pf-divider-desc { font-size: 0.76rem; color: #94a3b8; font-weight: 500; }

        /* ── Forms ───────────────────────────────────────── */
        .pf-label { font-size: 0.72rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 0.4rem; }
        .pf-ctrl {
            width: 100%;
            padding: 0.72rem 1rem;
            font-size: 0.88rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 11px;
            background: var(--pf-white);
            color: var(--pf-dark);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-family: inherit;
        }
        .pf-ctrl:focus { outline: none; border-color: var(--brand-primary, #2563eb); box-shadow: 0 0 0 3px rgba(37,99,235,0.12); }
        .pf-ctrl[readonly] { background: #f8fafc; color: #64748b; cursor: default; }
        .pf-field-note { font-size: 0.7rem; color: #94a3b8; font-weight: 500; margin-top: 0.35rem; }

        .pf-btn {
            padding: 0.75rem 1.75rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex; align-items: center; gap: 0.5rem;
            color: #fff;
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            box-shadow: 0 4px 12px rgba(37,99,235,0.22);
        }
        .pf-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(37,99,235,0.3); }
        .pf-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        .pf-btn-outline {
            background: transparent; color: var(--pf-dark);
            border: 1.5px solid #cbd5e1; box-shadow: none;
        }
        .pf-btn-outline:hover { background: var(--pf-surface); box-shadow: none; }
        .pf-btn-red { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 12px rgba(239,68,68,0.22); }
        .pf-btn-red:hover { box-shadow: 0 8px 20px rgba(239,68,68,0.3); }

        /* ── Status chips ────────────────────────────────── */
        .pf-chip {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.22rem 0.65rem; border-radius: 50px;
            font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
        }
        .pf-chip.approved { background: #ecfdf5; color: #059669; border: 1px solid #6ee7b7; }
        .pf-chip.pending { background: #fffbeb; color: #b45309; border: 1px solid #fcd34d; }
        .pf-chip.completed { background: #eff6ff; color: #2563eb; border: 1px solid #93c5fd; }
        .pf-chip.cancelled, .pf-chip.denied { background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }
        .pf-chip.unknown { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

        /* ── Event rows ──────────────────────────────────── */
        .pf-event {
            display: flex; align-items: center; gap: 1rem;
            padding: 1rem;
            border: 1px solid #f1f5f9;
            border-radius: 13px;
            margin-bottom: 0.75rem;
            background: var(--pf-white);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .pf-event:last-child { margin-bottom: 0; }
        .pf-event:hover { border-color: #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .pf-event-date {
            flex-shrink: 0;
            width: 46px; text-align: center;
            padding: 0.4rem 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #f8fafc, #eef2f7);
            border: 1px solid #e2e8f0;
        }
        .pf-event-date .m { font-size: 0.58rem; font-weight: 800; text-transform: uppercase; color: #ef4444; letter-spacing: 0.5px; }
        .pf-event-date .d { font-size: 1.1rem; font-weight: 800; color: var(--pf-dark); line-height: 1.1; }
        .pf-event-body { flex: 1; min-width: 0; }
        .pf-event-title { font-size: 0.88rem; font-weight: 700; color: var(--pf-dark); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pf-event-meta { font-size: 0.72rem; color: var(--pf-gray); font-weight: 500; margin-top: 0.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pf-event-right { flex-shrink: 0; text-align: right; }

        /* ── Toast ───────────────────────────────────────── */
        .pf-toast {
            position: fixed; bottom: 2rem; right: 2rem;
            padding: 0.8rem 1.2rem; border-radius: 12px;
            font-size: 0.82rem; font-weight: 700; z-index: 9999;
            display: flex; align-items: center; gap: 0.5rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            transform: translateY(100px); opacity: 0;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .pf-toast.show { transform: translateY(0); opacity: 1; }
        .pf-toast.success { background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7; }
        .pf-toast.error { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── Danger zone ─────────────────────────────────── */
        .pf-danger-card { border-color: rgba(239,68,68,0.15); background: linear-gradient(135deg, #fff 0%, #fef2f2 100%); }
        .pf-danger-card .pf-card-head { border-bottom-color: rgba(239,68,68,0.1); }
        .pf-danger-card .pf-card-title { color: var(--pf-danger); }
        .pf-danger-notice {
            display: flex; align-items: flex-start; gap: 1rem;
            padding: 1.1rem; background: rgba(239,68,68,0.04);
            border: 1px solid rgba(239,68,68,0.1); border-radius: 13px;
            margin-bottom: 1.5rem;
        }
        .pf-danger-notice-icon {
            width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
            background: rgba(239,68,68,0.1);
            display: flex; align-items: center; justify-content: center;
            color: var(--pf-danger); font-size: 1.05rem;
        }
        .pf-danger-notice h6 { font-weight: 700; color: #991b1b; font-size: 0.88rem; margin: 0 0 0.25rem; }
        .pf-danger-notice p { font-size: 0.8rem; color: #b91c1c; margin: 0; opacity: 0.85; line-height: 1.5; }

        /* ── Empty state ─────────────────────────────────── */
        .pf-empty {
            text-align: center; padding: 2.5rem 1.5rem; color: var(--pf-gray);
        }
        .pf-empty i { font-size: 1.8rem; color: #cbd5e1; margin-bottom: 0.6rem; display: block; }
        .pf-empty h6 { font-weight: 700; color: #475569; margin: 0 0 0.3rem; font-size: 0.9rem; }
        .pf-empty p { font-size: 0.8rem; margin: 0; }

        /* ── Password meter ──────────────────────────────── */
        .pf-meter { display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; margin-top: 0.5rem; }
        .pf-meter span { height: 4px; border-radius: 2px; background: #eef2f7; }
        .pf-checks { display: grid; grid-template-columns: 1fr 1fr; gap: 0.35rem 1rem; margin-top: 0.75rem; }
        .pf-check { font-size: 0.72rem; color: #94a3b8; font-weight: 600; display: flex; align-items: center; gap: 0.35rem; }
        .pf-check.pass { color: #059669; }
        .pf-check i { font-size: 0.75rem; }

        /* ── Animations ──────────────────────────────────── */
        @keyframes pfFadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .pf-banner { animation: pfFadeUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) both; }
        .pf-kpi-row { animation: pfFadeUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.1s both; }
        .pf-card { animation: pfFadeUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.2s both; }

        /* ── Responsive ──────────────────────────────────── */
        @media (max-width: 992px) {
            .pf-kpi-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .pf-banner { padding: 1.75rem 1.25rem; }
            .pf-banner-name { font-size: 1.4rem; }
            .pf-kpi-row { grid-template-columns: 1fr 1fr; }
            .pf-tabs { flex-direction: column; }
            .pf-checks { grid-template-columns: 1fr; }
        }
    </style>

    <div class="pf-page">
        <div class="container-fluid" style="max-width: 1200px;">

            {{-- ── Identity Banner ──────────────────────────── --}}
            <div class="pf-banner">
                <h1 class="pf-banner-name">{{ $user->first_name ?? $user->name }} {{ $user->last_name ?? '' }}</h1>
                <p class="pf-banner-sub">Your {{ branding('system_name', 'Platform') }} account — one profile for every campus reservation.</p>
                <span class="pf-banner-email"><i class="bi bi-envelope-fill"></i> {{ $user->email }}</span>
                <div class="pf-banner-badges">
                    <span class="pf-badge role"><i class="bi bi-shield-check"></i> {{ ucfirst($user->role) }}</span>
                    <span class="pf-badge member-since"><i class="bi bi-calendar-event"></i> Member since {{ $user->created_at->format('M d, Y') }}</span>
                    <span class="pf-badge uid"><i class="bi bi-hash"></i> #{{ $user->id }}</span>
                </div>
            </div>

            {{-- ── Summary KPI band ──────────────────────────── --}}
            <div class="pf-kpi-row">
                <div class="pf-kpi">
                    <div class="pf-kpi-icon blue"><i class="bi bi-bookmark-fill"></i></div>
                    <div>
                        <div class="pf-kpi-num">{{ $summary['total'] }}</div>
                        <div class="pf-kpi-label">Total Reservations</div>
                        <div class="pf-kpi-note">all-time, excl. denied</div>
                    </div>
                </div>
                <div class="pf-kpi">
                    <div class="pf-kpi-icon green"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="pf-kpi-num">{{ $summary['approved'] }}</div>
                        <div class="pf-kpi-label">Approved</div>
                        <div class="pf-kpi-note">{{ $summary['completed'] }} completed</div>
                    </div>
                </div>
                <div class="pf-kpi">
                    <div class="pf-kpi-icon amber"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="pf-kpi-num">{{ $summary['pending'] }}</div>
                        <div class="pf-kpi-label">Pending Review</div>
                        <div class="pf-kpi-note">awaiting admin approval</div>
                    </div>
                </div>
                <div class="pf-kpi">
                    <div class="pf-kpi-icon purple"><i class="bi bi-building-fill"></i></div>
                    <div>
                        <div class="pf-kpi-num">{{ $summary['favorite_facility'] ? 1 : 0 }}</div>
                        <div class="pf-kpi-label">{{ $summary['favorite_facility'] ? mb_strimwidth($summary['favorite_facility'], 0, 22, '…') : 'Favorite Venue' }}</div>
                        <div class="pf-kpi-note">{{ $summary['favorite_count'] ? $summary['favorite_count'] . ' booking' . ($summary['favorite_count'] > 1 ? 's' : '') . ' here' : 'no bookings yet' }}</div>
                    </div>
                </div>
            </div>

            {{-- ── Two-column layout ──────────────────────────── --}}
            <div class="row g-4 mt-2">

                <!-- Sidebar: identity + upcoming -->
                <div class="col-lg-5">
                    <!-- Identity card -->
                    <div class="pf-card pf-side">
                        <div class="pf-side-inner">
                            <div class="pf-avatar-wrap" id="avatarWrapper" title="Change profile photo">
                                <div class="pf-avatar" id="avatarCircle">
                                    @if($user->avatar)
                                        <img src="{{ $user->avatarUrl() }}" alt="Avatar" id="avatarImage">
                                    @else
                                        <span id="avatarInitial">{{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="pf-avatar-overlay" id="avatarOverlay">
                                    <i class="bi bi-camera-fill"></i>
                                    <span>Change</span>
                                </div>
                            </div>
                            <input type="file" id="avatarInput" accept="image/jpeg,image/png,image/webp" style="display:none;">

                            <div class="pf-avatar-progress" id="avatarProgress">
                                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Uploading...</span></div>
                                <span>Uploading photo…</span>
                            </div>

                            <div class="pf-side-actions" id="avatarActions">
                                <button type="button" class="pf-act" id="btnChangeAvatar"><i class="bi bi-pencil me-1"></i> Change</button>
                                @if($user->avatar)
                                    <button type="button" class="pf-act danger" id="btnRemoveAvatar"><i class="bi bi-trash3 me-1"></i> Remove</button>
                                @endif
                            </div>

                            <h2 class="pf-side-name">{{ $user->first_name ?? $user->name }} {{ $user->last_name ?? '' }}</h2>
                            <p class="pf-side-meta">{{ $user->email }}</p>

                            <div class="pf-side-stats">
                                <div class="pf-side-stat">
                                    <span class="pf-side-stat-label">Client Type</span>
                                    <span class="pf-side-stat-value">{{ ucfirst($user->client_type ?? '—') }}</span>
                                </div>
                                <div class="pf-side-stat">
                                    <span class="pf-side-stat-label">Contact</span>
                                    <span class="pf-side-stat-value">{{ $user->contact_number ?? '—' }}</span>
                                </div>
                                <div class="pf-side-stat">
                                    <span class="pf-side-stat-label">Address</span>
                                    <span class="pf-side-stat-value" style="font-size:0.72rem;">{{ $user->personal_address ?? '—' }}</span>
                                </div>
                                <div class="pf-side-stat">
                                    <span class="pf-side-stat-label">People Hosted</span>
                                    <span class="pf-side-stat-value">{{ number_format($summary['total_people']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming next ─────────────────────── -->
                    <div class="pf-card">
                        <div class="pf-card-head">
                            <div class="pf-card-head-icon green"><i class="bi bi-calendar2-event-fill"></i></div>
                            <div>
                                <h5 class="pf-card-title">Your Next Bookings</h5>
                                <p class="pf-card-sub">Approvals ahead — tap to review on the board</p>
                            </div>
                        </div>
                        <div class="pf-card-body" style="padding-top:1rem;">
                            @if(count($summary['upcoming_next']) > 0)
                                @foreach($summary['upcoming_next'] as $ev)
                                    <div class="pf-event">
                                        <div class="pf-event-date">
                                            <div class="m">{{ \Carbon\Carbon::parse($ev['start_date'])->format('M') }}</div>
                                            <div class="d">{{ \Carbon\Carbon::parse($ev['start_date'])->format('d') }}</div>
                                        </div>
                                        <div class="pf-event-body">
                                            <p class="pf-event-title">{{ $ev['event_title'] ?: 'Untitled Event' }}</p>
                                            <p class="pf-event-meta"><i class="bi bi-geo-alt-fill me-1"></i>{{ $ev['facility'] ?? 'Unknown venue' }} · {{ $ev['date_range'] }} · {{ $ev['people'] }} people</p>
                                        </div>
                                        <div class="pf-event-right">
                                            <span class="pf-chip {{ $ev['status'] }}">{{ $ev['status_label'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="pf-empty">
                                    <i class="bi bi-calendar-plus"></i>
                                    <h6>Nothing on the calendar</h6>
                                    <p>Your upcoming approvals will appear here.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Main: tabs ─────────────────────────────── -->
                <div class="col-lg-7">
                    <div class="nav pf-tabs" role="tablist">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pfTabInfo" type="button" role="tab"><i class="bi bi-person-fill"></i> Personal Info</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pfTabActivity" type="button" role="tab"><i class="bi bi-clock-history"></i> Activity<span class="pf-tab-count">{{ $summary['total'] }}</span></button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pfTabSecurity" type="button" role="tab"><i class="bi bi-shield-lock-fill"></i> Security</button>
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pfTabDanger" type="button" role="tab"><i class="bi bi-exclamation-triangle-fill"></i> Danger</button>
                    </div>

                    <div class="tab-content">
                        <!-- Personal Info ──────────────────── -->
                        <div class="tab-pane fade show active" id="pfTabInfo" role="tabpanel">
                            <div class="pf-card">
                                <div class="pf-card-head">
                                    <div class="pf-card-head-icon"><i class="bi bi-person-badge-fill"></i></div>
                                    <div>
                                        <h5 class="pf-card-title">Personal Information</h5>
                                        <p class="pf-card-sub">How the university identifies and reaches you</p>
                                    </div>
                                </div>
                                <div class="pf-card-body">
                                    <div class="pf-divider" style="margin-top:0;">
                                        <div class="pf-divider-icon"><i class="bi bi-person-lines-fill"></i></div>
                                        <div>
                                            <div class="pf-divider-label">Legal Identity</div>
                                            <div class="pf-divider-desc">Managed by the university for official records</div>
                                        </div>
                                    </div>

                                    <form id="pfFormIdentity" novalidate>
                                        <div class="row g-3">
                                            <div class="col-sm-4">
                                                <label class="pf-label">First Name</label>
                                                <input class="pf-ctrl" type="text" value="{{ $user->first_name ?? '' }}" readonly tabindex="-1">
                                                <p class="pf-field-note">Identity records are managed by administrators — <a href="{{ url('/#contact') }}">contact support</a> to request a change.</p>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="pf-label">Middle Name</label>
                                                <input class="pf-ctrl" type="text" value="{{ $user->middle_name ?? '' }}" readonly tabindex="-1">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="pf-label">Last Name</label>
                                                <input class="pf-ctrl" type="text" value="{{ $user->last_name ?? '' }}" readonly tabindex="-1">
                                            </div>
                                        </div>
                                        <div class="row g-3 mt-1">
                                            <div class="col-12">
                                                <label class="pf-label">Email Address</label>
                                                <input class="pf-ctrl" type="email" value="{{ $user->email }}" readonly tabindex="-1">
                                            </div>
                                        </div>
                                    </form>

                                    <div class="pf-divider">
                                        <div class="pf-divider-icon"><i class="bi bi-telephone-fill"></i></div>
                                        <div>
                                            <div class="pf-divider-label">How We Reach You</div>
                                            <div class="pf-divider-desc">You control these — keep them current for reservation confirmations</div>
                                        </div>
                                    </div>

                                    <form id="pfFormContact" novalidate>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="pf-label">Contact Number</label>
                                                <x-international-phone-input
                                                    name="contact_number"
                                                    id="pfContactNumber"
                                                    :value="$user->contact_number ?? ''"
                                                    theme="profile"
                                                    helper="Select country code and enter phone digits for reservation confirmations"
                                                />
                                            </div>
                                            <div class="col-12">
                                                <label class="pf-label" for="pfAddress">Personal Address</label>
                                                <textarea class="pf-ctrl" id="pfAddress" name="personal_address" rows="2" placeholder="Street, barangay, city, province">{{ $user->personal_address ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="pf-btn" id="pfContactSave"><i class="bi bi-check2-all"></i> Save Contact Details</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Activity ───────────────────────── -->
                        <div class="tab-pane fade" id="pfTabActivity" role="tabpanel">
                            <div class="pf-card">
                                <div class="pf-card-head">
                                    <div class="pf-card-head-icon amber"><i class="bi bi-clock-history"></i></div>
                                    <div>
                                        <h5 class="pf-card-title">Reservation Activity</h5>
                                        <p class="pf-card-sub">Your latest reservations, newest first</p>
                                    </div>
                                </div>
                                <div class="pf-card-body" style="padding-top:1rem;">
                                    @if(count($summary['recent']) > 0)
                                        @foreach($summary['recent'] as $ev)
                                            <div class="pf-event">
                                                <div class="pf-event-date">
                                                    <div class="m">{{ \Carbon\Carbon::parse($ev['start_date'])->format('M') }}</div>
                                                    <div class="d">{{ \Carbon\Carbon::parse($ev['start_date'])->format('d') }}</div>
                                                </div>
                                                <div class="pf-event-body">
                                                    <p class="pf-event-title">{{ $ev['event_title'] ?: 'Untitled Event' }}</p>
                                                    <p class="pf-event-meta"><i class="bi bi-geo-alt-fill me-1"></i>{{ $ev['facility'] ?? 'Unknown venue' }} · {{ $ev['date_range'] }} · {{ $ev['people'] }} people · <span style="font-family:'JetBrains Mono',monospace;">{{ $ev['ref'] }}</span></p>
                                                </div>
                                                <div class="pf-event-right">
                                                    <span class="pf-chip {{ $ev['status'] }}">{{ $ev['status_label'] }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="pf-empty">
                                            <i class="bi bi-inbox"></i>
                                            <h6>No reservation activity yet</h6>
                                            <p>Once you book a facility, your history will appear here.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Security ───────────────────────── -->
                        <div class="tab-pane fade" id="pfTabSecurity" role="tabpanel">
                            <div class="pf-card">
                                <div class="pf-card-head">
                                    <div class="pf-card-head-icon"><i class="bi bi-shield-lock-fill"></i></div>
                                    <div>
                                        <h5 class="pf-card-title">Security Settings</h5>
                                        <p class="pf-card-sub">Keep your account protected</p>
                                    </div>
                                </div>
                                <div class="pf-card-body">
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>
                        </div>

                        <!-- Danger Zone ────────────────────── -->
                        <div class="tab-pane fade" id="pfTabDanger" role="tabpanel">
                            <div class="pf-card pf-danger-card">
                                <div class="pf-card-head">
                                    <div class="pf-card-head-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                                    <div>
                                        <h5 class="pf-card-title">Danger Zone</h5>
                                        <p class="pf-card-sub" style="color:#b91c1c;">Irreversible actions — handle with care</p>
                                    </div>
                                </div>
                                <div class="pf-card-body">
                                    <div class="pf-danger-notice">
                                        <div class="pf-danger-notice-icon"><i class="bi bi-exclamation-octagon-fill"></i></div>
                                        <div>
                                            <h6>Deleting your account is permanent</h6>
                                            <p>All your reservations, approvals, and history will be erased and cannot be recovered. You'll need to request a fresh account from the administrators afterward.</p>
                                        </div>
                                    </div>
                                    @include('profile.partials.delete-user-form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-center mt-4" style="font-size:0.75rem; color:#94a3b8;">
                <i class="bi bi-info-circle me-1"></i>Your identity records (name, email) are managed by the university — use the contact form on the homepage for changes.
            </p>
        </div>
    </div>

    <div class="pf-toast" id="pfToast"></div>

    <!-- ── Avatar + contact scripts ─────────────────────── -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        function showToast(message, type) {
            const t = document.getElementById('pfToast');
            t.className = 'pf-toast ' + (type === 'success' ? 'success' : 'error');
            t.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill') + '"></i> ' + message;
            setTimeout(function () { t.classList.add('show'); }, 50);
            setTimeout(function () { t.classList.remove('show'); }, 3500);
        }

        /* ── Avatar upload ─────────────────────────────── */
        const avatarInput = document.getElementById('avatarInput');
        const avatarOverlay = document.getElementById('avatarOverlay');
        const avatarProgress = document.getElementById('avatarProgress');
        const avatarCircle = document.getElementById('avatarCircle');
        const avatarActions = document.getElementById('avatarActions');

        if (avatarOverlay) {
            avatarOverlay.addEventListener('click', function (e) { e.stopPropagation(); avatarInput.click(); });
        }
        if (avatarActions) {
            avatarActions.addEventListener('click', function (e) {
                const btn = e.target.closest('button');
                if (!btn) return;
                if (btn.id === 'btnChangeAvatar') { avatarInput.click(); }
                if (btn.id === 'btnRemoveAvatar') { removeAvatarFile(); }
            });
        }
        if (avatarInput) {
            avatarInput.addEventListener('change', function () {
                if (!this.files || !this.files[0]) return;
                const file = this.files[0];
                if (file.size > 2 * 1024 * 1024) { showToast('The profile image must be smaller than 2 MB.', 'error'); this.value = ''; return; }
                const form = new FormData();
                form.append('avatar', file);
                avatarProgress.style.display = 'block';
                fetch("{{ route('profile.avatar.upload') }}", {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                    body: form
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    avatarProgress.style.display = 'none';
                    avatarInput.value = '';
                    if (data.success) {
                        if (data.avatar_url) {
                            avatarCircle.innerHTML = '<img src="' + data.avatar_url + '" alt="Avatar" id="avatarImage">';
                            if (!document.getElementById('btnRemoveAvatar')) {
                                const rm = document.createElement('button');
                                rm.type = 'button'; rm.className = 'pf-act danger'; rm.id = 'btnRemoveAvatar';
                                rm.innerHTML = '<i class="bi bi-trash3 me-1"></i> Remove';
                                avatarActions.appendChild(rm);
                            }
                        }
                        showToast(data.message || 'Profile photo updated.', 'success');
                    } else {
                        showToast((data.message && data.message.errors) ? Object.values(data.message.errors)[0][0] : (data.message || 'Upload failed.'), 'error');
                    }
                })
                .catch(function () {
                    avatarProgress.style.display = 'none';
                    showToast('Upload failed. Please try again.', 'error');
                });
            });
        }

        function removeAvatarFile() {
            if (!confirm('Remove your profile photo?')) return;
            fetch("{{ route('profile.avatar.remove') }}", {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    avatarCircle.innerHTML = '<span id="avatarInitial">' + (data.initial || '?') + '</span>';
                    const rm = document.getElementById('btnRemoveAvatar');
                    if (rm) rm.remove();
                    showToast(data.message || 'Profile photo removed.', 'success');
                } else {
                    showToast(data.message || 'Removal failed.', 'error');
                }
            });
        }

        /* ── Contact details form ──────────────────────── */
        const contactForm = document.getElementById('pfFormContact');
        if (contactForm) {
            contactForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const btn = document.getElementById('pfContactSave');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving…';
                fetch("{{ route('profile.contact.update') }}", {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        contact_number: document.getElementById('pfContactNumber').value.trim(),
                        personal_address: document.getElementById('pfAddress').value.trim()
                    })
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check2-all"></i> Save Contact Details';
                    if (data.success) {
                        showToast(data.message || 'Contact details updated.', 'success');
                    } else {
                        const msg = data.message || 'Update failed.';
                        const errs = data.errors;
                        showToast((errs && Object.values(errs)[0] && Object.values(errs)[0][0]) ? Object.values(errs)[0][0] : msg, 'error');
                    }
                })
                .catch(function () {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check2-all"></i> Save Contact Details';
                    showToast('Update failed. Please try again.', 'error');
                });
            });
        }
    });
    </script>
@endsection
