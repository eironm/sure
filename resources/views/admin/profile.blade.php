@extends('layouts.admin')

@section('title', 'My Profile')
@section('header', 'My Profile')

@section('content')
    @include('components.custom-alert')
    <style>
        /* ═══════════════════════════════════════════════════════════
           Admin Profile Page — Complete Redesign
           ═══════════════════════════════════════════════════════════ */
        .adm-profile {
            --adm-dark: var(--brand-dark, #0f172a);
            --adm-gray: #64748b;
            --adm-border: #e2e8f0;
            --adm-surface: #f8fafc;
            --adm-white: #ffffff;
            --adm-success: #10b981;
            --adm-danger: #ef4444;
            --adm-radius: 20px;
            --adm-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.04);
            --adm-shadow-lg: 0 4px 6px rgba(0,0,0,0.04), 0 20px 50px rgba(0,0,0,0.06);
            padding: 0 0 3rem;
        }

        /* Hero */
        .adm-hero {
            height: 160px;
            background: linear-gradient(135deg, var(--adm-dark) 0%, #1e293b 40%, color-mix(in srgb, var(--brand-primary, #2563eb) 25%, #1e293b) 100%);
            border-radius: var(--adm-radius);
            position: relative;
            overflow: hidden;
        }

        .adm-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.06;
        /* ═══════════════════════════════════════════════════════════
           Admin Profile Page — Dashboard Redesign
           ═══════════════════════════════════════════════════════════ */
        .adm-profile {
            --adm-ink: #0f172a;
            --adm-muted: #64748b;
            --adm-line: #dbe4ee;
            --adm-surface: #f8fbff;
            --adm-card: #ffffff;
            --adm-primary: var(--brand-primary, #2563eb);
            --adm-secondary: var(--brand-secondary, #7c3aed);
            --adm-success: #10b981;
            --adm-danger: #ef4444;
            padding: 0 0 3rem;
        }

        .adm-shell {
            display: grid;
            gap: 1.25rem;
        }

        .adm-hero {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            padding: 2rem 2rem 1.75rem;
            background:
                radial-gradient(circle at 18% 22%, rgba(59, 130, 246, 0.32), transparent 28%),
                radial-gradient(circle at 82% 0%, rgba(124, 58, 237, 0.22), transparent 22%),
                linear-gradient(135deg, #081226 0%, #13294b 45%, #1a365d 100%);
            color: #fff;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.16);
        }

        .adm-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.14) 1px, transparent 0);
            background-size: 18px 18px;
            opacity: 0.45;
        }

        .adm-hero::after {
            content: '';
            position: absolute;
            inset: auto -6% -35% auto;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.16) 0%, transparent 70%);
        }

        .adm-hero-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .adm-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.38rem 0.8rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            font-size: 0.64rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.78);
        }

        .adm-hero-title {
            margin: 0.8rem 0 0.35rem;
            font-size: clamp(2rem, 2.8vw, 3rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1.02;
        }

        .adm-hero-copy {
            margin: 0;
            max-width: 54rem;
            font-size: 0.94rem;
            line-height: 1.6;
            color: rgba(255,255,255,0.74);
        }

        .adm-hero-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .adm-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.72rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.07);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .adm-pill strong {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.76rem;
        }

        .adm-grid {
            display: grid;
            grid-template-columns: minmax(300px, 365px) minmax(0, 1fr);
            gap: 1.25rem;
            align-items: start;
        }

        .adm-rail,
        .adm-stage,
        .adm-panel,
        .adm-mini-card {
            background: var(--adm-card);
            border: 1px solid var(--adm-line);
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 14px 34px rgba(15, 23, 42, 0.05);
        }

        .adm-rail {
            border-radius: 24px;
            overflow: hidden;
            position: sticky;
            top: 1rem;
        }

        .adm-portrait {
            padding: 1.5rem 1.5rem 1.25rem;
            background:
                linear-gradient(180deg, rgba(37, 99, 235, 0.04), rgba(124, 58, 237, 0.02)),
                #fff;
            border-bottom: 1px solid #edf2f7;
            text-align: center;
        }

        .adm-avatar-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
            position: relative;
            width: 112px;
            margin-left: auto;
            margin-right: auto;
            cursor: pointer;
        }

        .adm-avatar {
            width: 112px;
            height: 112px;
            border-radius: 32px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #dbeafe 0%, #e9d5ff 100%);
            color: var(--adm-ink);
            font-size: 2.2rem;
            font-weight: 900;
            letter-spacing: -0.06em;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.55), 0 18px 32px rgba(15, 23, 42, 0.12);
            position: relative;
            overflow: hidden;
        }
        .adm-avatar img { width: 100%; height: 100%; object-fit: cover; }

        /* Upload overlay */
        .adm-avatar-overlay {
            position: absolute; inset: 0; border-radius: 32px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(15, 23, 42, 0.5); color: #fff; font-size: 1.4rem;
            opacity: 0; transition: opacity .2s ease; pointer-events: none;
        }
        .adm-avatar-wrap:hover .adm-avatar-overlay,
        .adm-avatar-wrap.is-dragging .adm-avatar-overlay { opacity: 1; }
        .adm-avatar-wrap.is-dragging .adm-avatar {
            box-shadow: 0 0 0 4px rgba(99,102,241,.3), 0 18px 32px rgba(15,23,42,.12);
        }
        .adm-avatar-spin {
            position: absolute; inset: 0; border-radius: 32px;
            display: none; align-items: center; justify-content: center;
            background: rgba(15, 23, 42, 0.55);
        }
        .adm-avatar-spin.show { display: flex; }

        .adm-avatar-actions {
            display: flex; justify-content: center; gap: .4rem; margin-bottom: 1rem;
        }
        .adm-avatar-btn {
            border: 1px solid var(--adm-border, #e2e8f0); background: #fff;
            color: var(--adm-ink, #0f172a); border-radius: 999px;
            padding: .3rem .8rem; font-size: .78rem; font-weight: 700; cursor: pointer;
            transition: background .15s, border-color .15s, color .15s;
        }
        .adm-avatar-btn:hover { background: #f1f5f9; }
        .adm-avatar-btn.danger { color: #dc2626; border-color: #fecaca; }
        .adm-avatar-btn.danger:hover { background: #fef2f2; }

        .adm-avatar::after {
            content: '';
            position: absolute;
            right: 7px;
            bottom: 7px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid #fff;
            background: var(--adm-success);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.14);
        }

        .adm-name {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--adm-ink);
            letter-spacing: -0.04em;
        }

        .adm-email {
            margin: 0.3rem 0 0.9rem;
            font-size: 0.86rem;
            color: var(--adm-muted);
        }

        .adm-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fbbf24;
            font-size: 0.68rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.05px;
        }

        .adm-rail-actions {
            display: flex;
            gap: 0.7rem;
            justify-content: center;
            padding: 1rem 1.25rem 1.35rem;
            border-top: 1px solid #edf2f7;
        }

        .adm-action {
            padding: 0.68rem 1rem;
            border-radius: 12px;
            border: 1px solid var(--adm-line);
            background: #fff;
            color: var(--adm-ink);
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .adm-action.danger {
            border-color: rgba(239, 68, 68, 0.2);
            color: #dc2626;
        }

        .adm-quick-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1px;
            background: #edf2f7;
        }

        .adm-quick-stat {
            padding: 1rem 1.1rem;
            background: #fff;
        }

        .adm-quick-stat-label {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.62rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
        }

        .adm-quick-stat-value {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--adm-ink);
        }

        .adm-stage {
            border-radius: 26px;
            overflow: hidden;
        }

        .adm-stage-head {
            padding: 1.25rem 1.35rem;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .adm-tabs {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.6rem;
            width: 100%;
        }

        .adm-tabs .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            padding: 0.88rem 1rem;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            color: var(--adm-muted) !important;
            font-size: 0.76rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            background: #fff !important;
            transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .adm-tabs .nav-link:hover {
            transform: translateY(-1px);
            border-color: rgba(37, 99, 235, 0.22);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }

        .adm-tabs .nav-link.active {
            color: #fff !important;
            border-color: transparent;
            background: linear-gradient(135deg, var(--adm-primary), var(--adm-secondary)) !important;
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.24);
        }

        .adm-tabs .nav-link.tab-danger {
            color: #dc2626 !important;
        }

        .adm-tabs .nav-link.tab-danger:hover {
            border-color: rgba(239, 68, 68, 0.2);
            background: #fff7f7 !important;
        }

        .adm-tabs .nav-link.tab-danger.active {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            box-shadow: 0 14px 28px rgba(239, 68, 68, 0.22);
        }

        .adm-stage-body {
            padding: 1.35rem;
        }

        .adm-section-title {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 1.15rem;
        }

        .adm-section-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(37, 99, 235, 0.04));
            color: var(--adm-primary);
            flex-shrink: 0;
        }

        .adm-section-icon i {
            font-size: 1.1rem;
        }

        .adm-section-copy h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 900;
            color: var(--adm-ink);
        }

        .adm-section-copy p {
            margin: 0.15rem 0 0;
            font-size: 0.8rem;
            color: var(--adm-muted);
        }

        .adm-panel {
            border-radius: 22px;
            overflow: hidden;
            margin-bottom: 1.15rem;
        }

        .adm-panel-head {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid #edf2f7;
            background: linear-gradient(180deg, #fafcff, #ffffff);
        }

        .adm-panel-head h6 {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 900;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: var(--adm-ink);
        }

        .adm-panel-head p {
            margin: 0.2rem 0 0;
            font-size: 0.76rem;
            color: var(--adm-muted);
        }

        .adm-panel-body {
            padding: 1.2rem;
        }

        .adm-security-policy,
        .adm-danger-notice {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 1.1rem;
            border-radius: 18px;
            margin-bottom: 1.2rem;
        }

        .adm-security-policy {
            background: linear-gradient(135deg, #fffdf2, #fffbeb);
            border: 1px solid #fde68a;
        }

        .adm-security-policy-icon,
        .adm-danger-notice-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .adm-security-policy-icon {
            background: rgba(245, 158, 11, 0.14);
            color: #d97706;
        }

        .adm-security-policy strong,
        .adm-danger-notice h6 {
            display: block;
            margin: 0 0 0.22rem;
            font-size: 0.88rem;
            font-weight: 900;
        }

        .adm-security-policy strong { color: #92400e; }
        .adm-security-policy span,
        .adm-danger-notice p {
            font-size: 0.82rem;
            line-height: 1.55;
            margin: 0;
        }

        .adm-security-policy span { color: #a16207; }

        .adm-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .adm-form-group {
            display: grid;
            gap: 0.45rem;
        }

        .adm-form-label {
            font-size: 0.72rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #334155;
        }

        .adm-form-input {
            width: 100%;
            min-height: 50px;
            border-radius: 14px;
            border: 1.5px solid #dbe4ee;
            background: #f8fbff;
            color: #0f172a;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: inherit;
        }

        .adm-form-input.readonly {
            background: #f8fbff;
            color: #475569;
        }

        .adm-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .adm-badge.success {
            background: #ecfdf5;
            color: #047857;
        }

        .adm-badge.danger {
            background: #fef2f2;
            color: #b91c1c;
        }

        .adm-badge.neutral {
            background: #f1f5f9;
            color: #475569;
        }

        .adm-danger-card {
            border-color: rgba(239, 68, 68, 0.12);
            background: linear-gradient(180deg, #ffffff, #fff7f7);
        }

        .adm-danger-card .adm-panel-head {
            border-bottom-color: rgba(239, 68, 68, 0.08);
        }

        .adm-danger-card .adm-panel-head h6,
        .adm-danger-card .adm-section-icon {
            color: var(--adm-danger);
        }

        .adm-danger-notice {
            background: rgba(239, 68, 68, 0.04);
            border: 1px solid rgba(239, 68, 68, 0.1);
        }

        .adm-danger-notice-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--adm-danger);
        }

        .btn-adm-delete {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.82rem 1.25rem;
            border-radius: 14px;
            border: none;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-size: 0.82rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            box-shadow: 0 10px 22px rgba(239, 68, 68, 0.18);
        }

        .adm-modal-error {
            display: none;
            align-items: center;
            gap: 0.45rem;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #b91c1c;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .adm-modal-error.show { display: flex; }

        .adm-delete-modal {
            padding: 1.5rem;
        }

        .adm-delete-head {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1.35rem;
        }

        .adm-delete-badge {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: rgba(239, 68, 68, 0.1);
            color: var(--adm-danger);
            flex-shrink: 0;
            font-size: 1.35rem;
        }

        .adm-delete-title {
            margin: 0 0 0.25rem;
            font-size: 1.16rem;
            font-weight: 900;
            color: var(--adm-ink);
        }

        .adm-delete-copy {
            margin: 0;
            font-size: 0.86rem;
            line-height: 1.55;
            color: var(--adm-muted);
        }

        .adm-delete-field {
            max-width: 380px;
            margin: 0 auto 1.25rem;
        }

        .adm-delete-label {
            display: block;
            margin-bottom: 0.45rem;
            font-size: 0.72rem;
            font-weight: 900;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .adm-delete-input {
            width: 100%;
            border-radius: 14px;
            border: 1.5px solid #dbe4ee;
            padding: 0.8rem 1rem;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .adm-delete-actions {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .adm-delete-cancel {
            padding: 0.8rem 1.15rem;
            border-radius: 12px;
            border: 1.5px solid #dbe4ee;
            background: #fff;
            color: #475569;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .adm-delete-submit {
            padding: 0.8rem 1.15rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-size: 0.84rem;
            font-weight: 900;
        }

        @media (max-width: 992px) {
            .adm-grid { grid-template-columns: 1fr; }
            .adm-rail { position: static; }
        }

        @media (max-width: 768px) {
            .adm-hero { padding: 1.4rem; border-radius: 22px; }
            .adm-stage-body { padding: 1rem; }
            .adm-form-grid { grid-template-columns: 1fr; }
            .adm-tabs { grid-template-columns: 1fr; }
        }

        @keyframes admFadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .adm-hero,
        .adm-rail,
        .adm-stage {
            animation: admFadeUp 0.45s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        .adm-rail { animation-delay: 0.08s; }
        .adm-stage { animation-delay: 0.14s; }
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        <div class="adm-shell">
            <div class="adm-hero">
                <div class="adm-hero-inner">
                    <div>
                        <div class="adm-kicker"><i class="bi bi-activity"></i> Account Control Center</div>
                        <h1 class="adm-hero-title">My Profile</h1>
                        <p class="adm-hero-copy">Profile, security, and deletion are separated into a dashboard-style workspace so the page feels like a control panel instead of a plain form.</p>
                    </div>
                    <div class="adm-hero-actions">
                        <div class="adm-pill"><i class="bi bi-person-badge"></i> <span>{{ Auth::user()->role }}</span></div>
                        <div class="adm-pill"><i class="bi bi-hash"></i> <strong>#{{ str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) }}</strong></div>
                    </div>
                </div>
            </div>

            <div class="adm-grid">
                <aside class="adm-rail">
                    <div class="adm-portrait">
                        <div class="adm-avatar-wrap" id="admAvatarWrap" title="Click or drop an image to change photo">
                            <div class="adm-avatar" id="admAvatar">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatarUrl() }}" alt="Avatar" id="admAvatarImg">
                                @else
                                    <span id="admAvatarInitial">{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="adm-avatar-overlay" id="admAvatarOverlay"><i class="bi bi-camera-fill"></i></div>
                            <div class="adm-avatar-spin" id="admAvatarSpin"><span class="spinner-border spinner-border-sm text-light"></span></div>
                        </div>
                        <input type="file" id="admAvatarInput" accept="image/jpeg,image/png,image/webp" class="d-none">
                        <div class="adm-avatar-actions" id="admAvatarActions">
                            <button type="button" class="adm-avatar-btn" id="admBtnChange">
                                <i class="bi bi-{{ Auth::user()->avatar ? 'pencil' : 'upload' }} me-1"></i> {{ Auth::user()->avatar ? 'Change' : 'Upload Photo' }}
                            </button>
                            @if(Auth::user()->avatar)
                                <button type="button" class="adm-avatar-btn danger" id="admBtnRemove"><i class="bi bi-trash3 me-1"></i> Remove</button>
                            @endif
                        </div>
                        <h2 class="adm-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                        <p class="adm-email">{{ Auth::user()->email }}</p>
                        <div class="adm-role-badge"><i class="bi bi-shield-check"></i> {{ Auth::user()->role }}</div>
                    </div>

                    <div class="adm-quick-stats">
                        <div class="adm-quick-stat">
                            <span class="adm-quick-stat-label">Member Since</span>
                            <span class="adm-quick-stat-value">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="adm-quick-stat">
                            <span class="adm-quick-stat-label">Updated</span>
                            <span class="adm-quick-stat-value">{{ Auth::user()->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="adm-quick-stat">
                            <span class="adm-quick-stat-label">Access</span>
                            <span class="adm-quick-stat-value">{{ strtoupper(Auth::user()->role) }}</span>
                        </div>
                        <div class="adm-quick-stat">
                            <span class="adm-quick-stat-label">Status</span>
                            <span class="adm-quick-stat-value">Active</span>
                        </div>
                    </div>

                    <div class="adm-rail-actions">
                        <button type="button" class="adm-action"><i class="bi bi-person-gear me-1"></i> Profile</button>
                        <button type="button" class="adm-action danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"><i class="bi bi-trash3 me-1"></i> Delete</button>
                    </div>
                </aside>

                <main class="adm-stage">
                    <div class="adm-stage-head">
                        <div class="adm-tabs nav" id="v-pills-tab" role="tablist">
                            <button class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab">
                                <i class="bi bi-person-circle"></i> Personal
                            </button>
                            <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab">
                                <i class="bi bi-shield-lock"></i> Security
                            </button>
                            <button class="nav-link tab-danger" id="v-pills-danger-tab" data-bs-toggle="pill" data-bs-target="#v-pills-danger" type="button" role="tab">
                                <i class="bi bi-exclamation-triangle"></i> Danger
                            </button>
                        </div>
                    </div>

                    <div class="adm-stage-body tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel">
                            <div class="adm-section-title">
                                <div class="adm-section-icon"><i class="bi bi-person-vcard"></i></div>
                                <div class="adm-section-copy">
                                    <h5>Personal Information</h5>
                                    <p>Read-only identity data with a stronger dashboard presentation.</p>
                                </div>
                            </div>

                            <div class="adm-panel">
                                <div class="adm-panel-head">
                                    <h6>Account Snapshot</h6>
                                    <p>These values are locked for administrators.</p>
                                </div>
                                <div class="adm-panel-body">
                                    <div class="adm-security-policy">
                                        <div class="adm-security-policy-icon"><i class="bi bi-shield-lock-fill"></i></div>
                                        <div>
                                            <strong>Protected identity fields</strong>
                                            <span>Updates to name and email must go through an administrator workflow.</span>
                                        </div>
                                    </div>

                                    <div class="adm-form-grid">
                                        <div class="adm-form-group">
                                            <label class="adm-form-label">First Name</label>
                                            <input type="text" class="adm-form-input readonly" value="{{ Auth::user()->first_name }}" readonly>
                                        </div>
                                        <div class="adm-form-group">
                                            <label class="adm-form-label">Last Name</label>
                                            <input type="text" class="adm-form-input readonly" value="{{ Auth::user()->last_name }}" readonly>
                                        </div>
                                        <div class="adm-form-group">
                                            <label class="adm-form-label">Middle Name</label>
                                            <input type="text" class="adm-form-input readonly" value="{{ Auth::user()->middle_name ?? 'N/A' }}" readonly>
                                        </div>
                                        <div class="adm-form-group">
                                            <label class="adm-form-label">Email Address</label>
                                            <input type="email" class="adm-form-input readonly" value="{{ Auth::user()->email }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                            <div class="adm-section-title">
                                <div class="adm-section-icon"><i class="bi bi-shield-lock"></i></div>
                                <div class="adm-section-copy">
                                    <h5>Security Settings</h5>
                                    <p>Update your password from a focused, high-contrast control panel.</p>
                                </div>
                            </div>

                            <div class="adm-panel">
                                <div class="adm-panel-head">
                                    <h6>Password Control</h6>
                                    <p>Use a strong password and keep it unique.</p>
                                </div>
                                <div class="adm-panel-body">
                                    @if (session('status') === 'password-updated')
                                        <div class="adm-security-policy" style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border-color: #6ee7b7; margin-bottom: 1.2rem;">
                                            <div class="adm-security-policy-icon" style="background: rgba(16, 185, 129, 0.14); color: #047857;"><i class="bi bi-check-circle-fill"></i></div>
                                            <div>
                                                <strong style="color: #065f46;">Password updated</strong>
                                                <span style="color: #047857;">Your current password has been replaced successfully.</span>
                                            </div>
                                        </div>
                                    @endif

                                    <form method="post" action="{{ route('password.update') }}">
                                        @csrf
                                        @method('put')

                                        <div class="adm-form-grid" style="grid-template-columns: 1fr;">
                                            <div class="adm-form-group">
                                                <label for="current_password" class="adm-form-label">Current Password</label>
                                                <input type="password" name="current_password" id="current_password" class="adm-form-input @error('current_password', 'updatePassword') is-invalid @enderror" required>
                                                @error('current_password', 'updatePassword') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="adm-form-grid">
                                                <div class="adm-form-group">
                                                    <label for="password" class="adm-form-label">New Password</label>
                                                    <input type="password" name="password" id="password" class="adm-form-input @error('password', 'updatePassword') is-invalid @enderror" required>
                                                    @error('password', 'updatePassword') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="adm-form-group">
                                                    <label for="password_confirmation" class="adm-form-label">Confirm Password</label>
                                                    <input type="password" name="password_confirmation" id="password_confirmation" class="adm-form-input" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="margin-top: 1.25rem;">
                                            <button type="submit" class="btn-adm-delete" style="background: linear-gradient(135deg, var(--adm-primary), var(--adm-secondary)); box-shadow: 0 10px 22px rgba(37, 99, 235, 0.18);">
                                                <i class="bi bi-shield-lock"></i> Update Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-danger" role="tabpanel">
                            <div class="adm-section-title">
                                <div class="adm-section-icon" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.12), rgba(239, 68, 68, 0.05));"><i class="bi bi-exclamation-triangle-fill"></i></div>
                                <div class="adm-section-copy">
                                    <h5 style="color: var(--adm-danger);">Danger Zone</h5>
                                    <p>Account deletion lives in a dedicated destructive-action block.</p>
                                </div>
                            </div>

                            <div class="adm-panel adm-danger-card">
                                <div class="adm-panel-head">
                                    <h6>Deletion Controls</h6>
                                    <p>This is permanent and cannot be reversed.</p>
                                </div>
                                <div class="adm-panel-body">
                                    <div class="adm-danger-notice">
                                        <div class="adm-danger-notice-icon"><i class="bi bi-trash3-fill"></i></div>
                                        <div>
                                            <h6>Delete Account</h6>
                                            <p>Deleting your account removes all administrative data and access immediately. Proceed only if this is intentional.</p>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn-adm-delete"
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                    >
                                        <i class="bi bi-trash3-fill"></i> Delete Account
                                    </button>

                                    <x-modal name="confirm-user-deletion" :show="false" focusable>
                                        <form id="deleteAccountForm" class="p-0">
                                            @csrf
                                            @method('delete')

                                            <div class="adm-delete-modal">
                                                <div class="adm-delete-head">
                                                    <div class="adm-delete-badge"><i class="bi bi-exclamation-triangle-fill"></i></div>
                                                    <div>
                                                        <h3 class="adm-delete-title">Confirm permanent deletion</h3>
                                                        <p class="adm-delete-copy">Enter your password to permanently remove this account and all related data.</p>
                                                    </div>
                                                </div>

                                                <div id="deleteErrorMessage" class="adm-modal-error mb-3">
                                                    <i class="bi bi-exclamation-circle"></i>
                                                    <span id="deleteErrorText"></span>
                                                </div>

                                                <div class="adm-delete-field">
                                                    <label for="deletePassword" class="adm-delete-label">Confirm Password</label>
                                                    <input
                                                        id="deletePassword"
                                                        name="password"
                                                        type="password"
                                                        class="adm-delete-input"
                                                        placeholder="••••••••"
                                                        required
                                                    />
                                                </div>

                                                <div class="adm-delete-actions">
                                                    <button type="button" class="adm-delete-cancel" x-on:click="$dispatch('close')">
                                                        Cancel
                                                    </button>
                                                    <button type="button" id="deleteSubmitBtn" class="adm-delete-submit">
                                                        <i class="bi bi-trash3-fill me-1"></i> Delete Permanently
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </x-modal>

                                    <script>
                                        document.getElementById('deleteSubmitBtn').addEventListener('click', function() {
                                            document.getElementById('deleteAccountForm').dispatchEvent(new Event('submit'));
                                        });

                                        document.getElementById('deleteAccountForm').addEventListener('submit', async function(e) {
                                            e.preventDefault();
                                            const password = document.getElementById('deletePassword').value;
                                            const errorDiv = document.getElementById('deleteErrorMessage');
                                            const errorText = document.getElementById('deleteErrorText');
                                            const btn = document.getElementById('deleteSubmitBtn');

                                            errorDiv.classList.remove('show');

                                            if (!password) {
                                                errorText.textContent = 'Please enter your password';
                                                errorDiv.classList.add('show');
                                                return;
                                            }

                                            btn.disabled = true;
                                            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Deleting...';

                                            try {
                                                const response = await fetch('{{ route("profile.destroy") }}', {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                                        'X-Requested-With': 'XMLHttpRequest',
                                                        'Accept': 'application/json'
                                                    },
                                                    body: JSON.stringify({ password: password })
                                                });

                                                const data = await response.json();

                                                if (response.ok) {
                                                    window.location.href = data.redirect || '/';
                                                } else if (response.status === 422) {
                                                    errorText.textContent = data.errors && data.errors.password ? data.errors.password[0] : 'Invalid password. Please try again.';
                                                    errorDiv.classList.add('show');
                                                    document.getElementById('deletePassword').value = '';
                                                    document.getElementById('deletePassword').focus();
                                                } else {
                                                    errorText.textContent = data.message || 'An error occurred. Please try again.';
                                                    errorDiv.classList.add('show');
                                                }
                                            } catch (error) {
                                                console.error('Error:', error);
                                                errorText.textContent = 'An error occurred. Please try again.';
                                                errorDiv.classList.add('show');
                                            } finally {
                                                btn.disabled = false;
                                                btn.innerHTML = '<i class="bi bi-trash3-fill me-1"></i> Delete Permanently';
                                            }
                                        });

                                        document.getElementById('deletePassword').addEventListener('input', function() {
                                            document.getElementById('deleteErrorMessage').classList.remove('show');
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                                        } finally {
                                            btn.disabled = false;
                                            btn.innerHTML = '<i class="bi bi-trash3-fill me-1"></i> Delete Permanently';
                                        }
                                    });

                                    document.getElementById('deletePassword').addEventListener('input', function() {
                                        document.getElementById('deleteErrorMessage').classList.remove('show');
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div class="profile-toast" id="admAvatarToast"
         style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:2000;background:#0f172a;color:#fff;padding:.7rem 1rem;border-radius:10px;font-size:.85rem;font-weight:600;box-shadow:0 12px 32px rgba(0,0,0,.2);opacity:0;transform:translateY(10px);transition:opacity .25s,transform .25s;pointer-events:none;"></div>

    {{-- ─── Admin Avatar Upload (PJAX-safe) ─── --}}
    <script>
    onPageLoad(function () {
        const wrap    = document.getElementById('admAvatarWrap');
        if (!wrap) return;
        const input   = document.getElementById('admAvatarInput');
        const box      = document.getElementById('admAvatar');
        const spin     = document.getElementById('admAvatarSpin');
        const actions  = document.getElementById('admAvatarActions');
        const toast    = document.getElementById('admAvatarToast');
        const csrf     = document.querySelector('meta[name="csrf-token"]')?.content;

        function showToast(msg, ok = true) {
            toast.style.background = ok ? '#0f172a' : '#b91c1c';
            toast.textContent = msg;
            toast.style.opacity = '1'; toast.style.transform = 'translateY(0)';
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateY(10px)'; }, 3200);
        }

        wrap.addEventListener('click', () => input.click());

        ['dragenter', 'dragover'].forEach(ev => wrap.addEventListener(ev, e => {
            e.preventDefault(); e.stopPropagation(); wrap.classList.add('is-dragging');
        }));
        ['dragleave', 'drop'].forEach(ev => wrap.addEventListener(ev, e => {
            e.preventDefault(); e.stopPropagation(); wrap.classList.remove('is-dragging');
        }));
        wrap.addEventListener('drop', e => {
            const f = e.dataTransfer?.files?.[0];
            if (f) upload(f);
        });

        input.addEventListener('change', function () {
            const f = this.files[0];
            if (f) upload(f);
            this.value = '';
        });

        actions.addEventListener('click', function (e) {
            const btn = e.target.closest('button');
            if (!btn) return;
            if (btn.id === 'admBtnChange') { input.click(); }
            else if (btn.id === 'admBtnRemove') { remove(); }
        });

        async function upload(file) {
            const valid = ['image/jpeg', 'image/png', 'image/webp'];
            if (!valid.includes(file.type)) return showToast('Only JPEG, PNG, and WebP images are accepted.', false);
            if (file.size > 2 * 1024 * 1024) return showToast('Image must be smaller than 2 MB.', false);

            spin.classList.add('show');
            const fd = new FormData();
            fd.append('avatar', file);
            try {
                const res = await fetch('{{ route("profile.avatar.upload") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: fd,
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    box.innerHTML = '<img src="' + data.avatar_url + '?v=' + Date.now() + '" alt="Avatar" id="admAvatarImg">';
                    actions.innerHTML =
                        '<button type="button" class="adm-avatar-btn" id="admBtnChange"><i class="bi bi-pencil me-1"></i> Change</button>' +
                        '<button type="button" class="adm-avatar-btn danger" id="admBtnRemove"><i class="bi bi-trash3 me-1"></i> Remove</button>';
                    showToast(data.message || 'Profile photo updated.');
                } else {
                    showToast(data.errors?.avatar?.[0] || data.message || 'Upload failed.', false);
                }
            } catch (err) {
                showToast('An error occurred during upload.', false);
            } finally {
                spin.classList.remove('show');
            }
        }

        async function remove() {
            if (!confirm('Remove your profile photo?')) return;
            spin.classList.add('show');
            try {
                const res = await fetch('{{ route("profile.avatar.remove") }}', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    box.innerHTML = '<span id="admAvatarInitial">' + (data.initial || '') + '</span>';
                    actions.innerHTML =
                        '<button type="button" class="adm-avatar-btn" id="admBtnChange"><i class="bi bi-upload me-1"></i> Upload Photo</button>';
                    showToast(data.message || 'Profile photo removed.');
                } else {
                    showToast(data.message || 'Failed to remove photo.', false);
                }
            } catch (err) {
                showToast('An error occurred.', false);
            } finally {
                spin.classList.remove('show');
            }
        }
    });
    </script>
@endsection
