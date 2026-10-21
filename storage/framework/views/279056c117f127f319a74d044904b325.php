
<style>
    [x-cloak] { display: none !important; }

    :root {
        <?php echo brand_css_vars(); ?>

        --sidebar-width: 264px;
        --sidebar-bg: var(--brand-dark, #0f172a);
        --sidebar-line: rgba(255, 255, 255, 0.06);
        --accent: var(--brand-primary, #7c3aed);
        --accent-rgb: var(--brand-primary-rgb, 124, 58, 237);
        --surface: #f6f7fb;
        --ink: var(--brand-dark, #0f172a);
        --ink-2: #475569;
        --ink-3: #94a3b8;
        --line: #e7eaf0;
        /* Soften Bootstrap corner radii globally */
        --bs-border-radius: .65rem;
        --bs-border-radius-sm: .5rem;
        --bs-border-radius-lg: .85rem;
        --bs-border-radius-xl: 1rem;
        --bs-body-color: var(--ink-2);
    }

    body {
        font-family: '<?php echo e(branding('panel_font', 'Figtree')); ?>', sans-serif;
        background: var(--surface);
        color: var(--ink-2);
        min-height: 100vh;
        margin: 0;
        -webkit-font-smoothing: antialiased;
    }

    /* ── Sidebar ─────────────────────────────────────────── */
    .sidebar {
        width: var(--sidebar-width);
        background: var(--sidebar-bg, var(--brand-dark, #0f172a));
        height: 100vh;
        position: fixed;
        left: 0; top: 0;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        border-right: 1px solid rgba(255, 255, 255, 0.04);
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,.12) transparent;
    }
    .sidebar::-webkit-scrollbar { width: 6px; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 99px; }

    .sidebar-header {
        padding: 1.4rem 1.25rem 1.1rem;
        display: flex;
        flex-direction: column;
        gap: .65rem;
        border-bottom: 1px solid var(--sidebar-line);
    }
    .brand-wrapper { display: flex; align-items: center; gap: .75rem; min-height: 40px; }
    .brand-logo-img { max-height: 38px; max-width: 46px; width: auto; height: auto; object-fit: contain; border-radius: 8px; flex-shrink: 0; }
    /* Wordmark-only mode: with the name text hidden, let a wide wordmark
       breathe instead of squeezing it into the icon box. */
    .brand-logo-img.brand-wordmark-only { width: auto; height: 38px; max-height: 38px; border-radius: 6px; max-width: calc(100% - 1.5rem); }
    .brand-name { color: #fff; font-family: 'JetBrains Mono', monospace; font-weight: 700; font-size: 1.05rem; letter-spacing: .5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2; }

    .current-panel-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        color: rgba(255, 255, 255, .72);
        font-size: .62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }
    .current-panel-badge i { color: var(--accent); }

    /* ── Navigation ──────────────────────────────────────── */
    .sidebar nav { flex: 1; padding: 1rem .85rem; }
    .nav-section {
        font-size: .6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.4px;
        color: rgba(255, 255, 255, .58);
        padding: 1.1rem .65rem .45rem;
        user-select: none;
    }
    .sidebar nav .nav-section:first-child { padding-top: .15rem; }
    .sidebar .nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: .7rem;
        padding: .62rem .8rem;
        color: rgba(255, 255, 255, .84);
        text-decoration: none;
        font-size: .9rem;
        font-weight: 500;
        border-radius: 10px;
        transition: color .15s, background-color .15s;
        margin-bottom: 2px;
    }
    .sidebar .nav-link:hover { color: #e2e8f0; background: rgba(255, 255, 255, 0.08); }
    .sidebar .nav-link.active {
        color: #fff;
        background: var(--accent, var(--brand-primary, #7c3aed));
        font-weight: 600;
        box-shadow: none;
    }
    .sidebar .nav-link.active::before {
        content: '';
        position: absolute;
        left: -0.85rem;
        top: 22%;
        height: 56%;
        width: 3px;
        border-radius: 99px;
        background: var(--accent);
    }
    .sidebar .nav-link i { font-size: 1.02rem; width: 22px; text-align: center; opacity: .9; }

    /* ── Sidebar Footer ──────────────────────────────────── */
    /* Native checkbox is only the state source; the track is the visual switch. */
    .acct-switch input[type="checkbox"] {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }
    .sidebar-footer { padding: 1rem; border-top: 1px solid var(--sidebar-line); }
    .user-info { display: flex; align-items: center; gap: .7rem; margin-bottom: .9rem; }
    .avatar {
        width: 38px; height: 38px;
        background: linear-gradient(135deg, var(--accent), rgba(var(--accent-rgb), .65));
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: .85rem; flex-shrink: 0;
    }
    /* ── Unified user avatar (component: x-user-avatar) ──
       Uses --uav-hue set inline per-user for a deterministic tile color.
       The subtle person watermark keeps the "default avatar" feel when
       no photo is uploaded; uploaded photos cover it entirely. */
    .uavatar {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        background: hsl(var(--uav-hue, 220) 55% 52%);
        color: #fff;
        font-weight: 700;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
    }
    .uavatar-img { width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 2; }
    .uavatar-initials { position: relative; z-index: 2; font-size: inherit; line-height: 1; letter-spacing: .3px; }
    .uavatar-watermark {
        position: absolute; inset: 0; z-index: 1;
        display: flex; align-items: center; justify-content: center;
        font-size: .9em; color: rgba(255, 255, 255, .32);
    }
    .uavatar.xs { width: 26px; height: 26px; font-size: .6rem; border-radius: 7px; }
    .uavatar.sm { width: 38px; height: 38px; font-size: .85rem; }
    .uavatar.md { width: 46px; height: 46px; font-size: 1rem; }
    .uavatar.lg { width: 64px; height: 64px; font-size: 1.4rem; }

    .user-details { overflow: hidden; line-height: 1.25; }
    .user-name { color: #fff; font-size: .87rem; font-weight: 600; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; display: block; }
    .user-role { color: rgba(255, 255, 255, .56); font-size: .68rem; text-transform: uppercase; letter-spacing: .8px; }

    .panel-switcher {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
        gap: .45rem;
        margin-bottom: .55rem;
    }
    .switch-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .25rem;
        padding: .55rem .25rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.07);
        color: var(--ink-3);
        font-size: .62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        text-decoration: none;
        transition: all .15s;
    }
    .switch-btn i { font-size: .95rem; }
    .switch-btn:hover { background: rgba(var(--accent-rgb), .2); border-color: rgba(var(--accent-rgb), .35); color: #fff; }

    .btn-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        width: 100%;
        padding: .55rem;
        border-radius: 10px;
        font-size: .82rem;
        font-weight: 600;
        background: transparent;
        color: #f87171;
        border: 1px solid rgba(248, 113, 113, 0.25);
        transition: all .15s;
    }
    .btn-logout:hover { background: #ef4444; border-color: #ef4444; color: #fff; }

    /* ── Main Content & Top Bar ──────────────────────────── */
    .main-content { margin-left: var(--sidebar-width); min-height: 100vh; padding: 0 2rem 3rem; }
    .top-bar {
        position: sticky;
        top: 0;
        z-index: 900;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .75rem;
        margin: 0 -2rem 1.5rem;
        padding: .8rem 2rem;
        background: rgba(255, 255, 255, .82);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--line);
    }
    .header-title {
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        letter-spacing: -.02em;
        position: relative;
        padding-bottom: .6rem;
    }
    .header-title::after {
        content: '';
        position: absolute;
        left: 0; bottom: 0;
        width: 44px; height: 3px;
        border-radius: 99px;
        background: linear-gradient(90deg, var(--accent), rgba(var(--accent-rgb), .35));
    }

    /* PJAX page transition */
    #pjax-content { animation: pjaxFadeUp .28s ease; }
    @keyframes pjaxFadeUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: none; }
    }

    /* ── Cards ───────────────────────────────────────────── */
    .main-content .card {
        border: 1px solid var(--line);
        border-radius: .9rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        transition: box-shadow .2s, transform .2s;
    }
    .main-content .card:hover { box-shadow: 0 6px 18px rgba(15, 23, 42, .07); }
    .main-content .card .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; }
    .main-content .shadow-sm { box-shadow: 0 1px 3px rgba(15, 23, 42, .06), 0 1px 2px rgba(15, 23, 42, .04) !important; }

    /* ── Tables ──────────────────────────────────────────── */
    .main-content .table { --bs-table-hover-bg: #f8fafc; margin-bottom: 0; }
    .main-content .table thead th {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--ink-3);
        background: #fafbfd;
        border-bottom: 1px solid var(--line);
        padding: .7rem 1rem;
        white-space: nowrap;
    }
    .main-content .table tbody td { padding: .85rem 1rem; vertical-align: middle; border-color: #f1f5f9; }
    .main-content .table tbody tr:last-child td { border-bottom: 0; }
    .main-content .table tbody tr:hover { background: #f8fafc; }

    /* ── Buttons ─────────────────────────────────────────── */
    .main-content .btn { font-weight: 600; }
    .btn-sure {
        background: var(--accent);
        border: 1px solid var(--accent);
        color: #fff;
    }
    .btn-sure:hover, .btn-sure:focus { background: rgba(var(--accent-rgb), .88); border-color: rgba(var(--accent-rgb), .88); color: #fff; }
    .btn-sure:disabled { background: rgba(var(--accent-rgb), .45); border-color: transparent; color: #fff; }

    /* ── Badges ──────────────────────────────────────────── */
    .main-content .badge { font-weight: 600; letter-spacing: .02em; }

    /* ── Forms ───────────────────────────────────────────── */
    .main-content .form-control, .main-content .form-select {
        border-color: var(--line);
    }
    .main-content .form-control:focus, .main-content .form-select:focus {
        border-color: rgba(var(--accent-rgb), .5);
        box-shadow: 0 0 0 .2rem rgba(var(--accent-rgb), .1);
    }
    .main-content .input-group-text { background: #fafbfd; border-color: var(--line); color: var(--ink-3); }
    .main-content .form-switch .form-check-input {
        width: 2.75em;
        height: 1.5em;
        margin-top: 0;
        cursor: pointer;
        border: 0;
        border-radius: 2em;
        background-color: #cbd5e1;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: left 0.15rem center;
        background-size: 1.15rem 1.15rem;
        box-shadow: none;
        transition: background-position 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease;
    }
    .main-content .form-switch .form-check-input:checked {
        background-color: var(--accent);
        background-position: right 0.15rem center;
    }
    .main-content .form-switch .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(var(--accent-rgb), 0.12);
    }
    .main-content .form-check-input:checked { background-color: var(--accent); border-color: var(--accent); }
    .main-content .form-check-input:focus { border-color: rgba(var(--accent-rgb), .5); box-shadow: 0 0 0 .2rem rgba(var(--accent-rgb), .1); }
    .main-content label.form-label { color: var(--ink-2); }

    /* ── Pagination ──────────────────────────────────────── */
    .main-content .pagination { gap: .25rem; }
    .main-content .page-link {
        border: 0;
        border-radius: .55rem;
        color: var(--ink-2);
        font-weight: 600;
        font-size: .85rem;
        min-width: 2.1rem;
        text-align: center;
    }
    .main-content .page-link:hover { background: #eef0f6; color: var(--ink); }
    .main-content .page-item.active .page-link { background: var(--accent); color: #fff; }
    .main-content .page-item.disabled .page-link { background: transparent; color: #cbd5e1; }
    .main-content .page-link:focus { box-shadow: 0 0 0 .2rem rgba(var(--accent-rgb), .15); }

    /* ── Alerts ──────────────────────────────────────────── */
    .main-content .alert { border: 0; border-radius: .85rem; font-weight: 500; }
    .main-content .alert-success { background: #ecfdf5; color: #047857; }
    .main-content .alert-danger { background: #fef2f2; color: #b91c1c; }
    .main-content .alert-info { background: #eff6ff; color: #1d4ed8; }
    .main-content .alert-warning { background: #fffbeb; color: #b45309; }

    /* ── Modals / Offcanvas ──────────────────────────────── */
    .modal-content { border: 0; border-radius: 1rem; box-shadow: 0 24px 64px rgba(15, 23, 42, .18); }
    .modal-header { border-bottom: 1px solid #f1f5f9; }
    .modal-footer { border-top: 1px solid #f1f5f9; }
    .offcanvas { border: 0; }

    /* ══ Panel Component Library ══════════════════════════ */

    /* Page head: title + action buttons on one row */
    .page-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .page-head .header-title { margin-bottom: 0; }

    /* KPI stat cards */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: .9rem;
        padding: 1.15rem 1.3rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 8px 20px rgba(15, 23, 42, .08); transform: translateY(-2px); }
    .stat-card .stat-icon {
        width: 2.8rem; height: 2.8rem;
        border-radius: .75rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        background: rgba(var(--accent-rgb), .1);
        color: var(--accent);
    }
    .stat-card .stat-icon.success { background: #ecfdf5; color: #059669; }
    .stat-card .stat-icon.warning { background: #fffbeb; color: #d97706; }
    .stat-card .stat-icon.danger  { background: #fef2f2; color: #dc2626; }
    .stat-card .stat-icon.info    { background: #eff6ff; color: #2563eb; }
    .stat-card .stat-value { font-size: 1.45rem; font-weight: 800; color: var(--ink); line-height: 1.15; }
    .stat-card .stat-label { font-size: .72rem; font-weight: 700; color: var(--ink-3); text-transform: uppercase; letter-spacing: .06em; }

    /* Status chips (reservations, payments, users) */
    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .28rem .7rem;
        border-radius: 99px;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .status-chip::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .status-chip.approved, .status-chip.paid, .status-chip.active   { background: #ecfdf5; color: #047857; }
    .status-chip.pending, .status-chip.partial                       { background: #fffbeb; color: #b45309; }
    .status-chip.denied, .status-chip.cancelled, .status-chip.failed { background: #fef2f2; color: #b91c1c; }
    .status-chip.completed, .status-chip.neutral                     { background: #eef2ff; color: #4338ca; }

    /* Empty states */
    .panel-empty {
        text-align: center;
        padding: 3.5rem 1.5rem;
        color: var(--ink-3);
    }
    .panel-empty i { font-size: 2.6rem; display: block; margin-bottom: .75rem; color: #cbd5e1; }
    .panel-empty h4 { font-size: 1rem; font-weight: 700; color: var(--ink-2); margin-bottom: .3rem; }
    .panel-empty p { font-size: .875rem; margin: 0; }

    /* Toolbar row above tables: search + filters */
    .table-toolbar {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
        border-radius: .9rem .9rem 0 0;
    }
    .table-toolbar .form-control, .table-toolbar .form-select { max-width: 240px; }

    /* ── Panel drawer trigger ────────────────────────────── */
    /* Hidden on desktop, where the sidebar is permanently visible. */
    .panel-hamburger { display: none; }

    /* Groups the clock and bell so the hamburger can sit opposite them. */
    .top-bar-actions {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-left: auto;
    }

    .panel-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(2, 6, 23, .55);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: opacity .25s ease, visibility .25s ease;
    }
    .panel-backdrop.is-open { opacity: 1; visibility: visible; }

    /* ── Responsive ──────────────────────────────────────── */
    @media (max-width: 1024px) {
        /* The sidebar was translated off-screen here with nothing able
           to bring it back, so admin, staff, superadmin and cashier had
           no navigation at all on a phone. It is now a real drawer. */
        .sidebar {
            transform: translateX(-100%);
            transition: transform .3s cubic-bezier(.32, .72, 0, 1),
                        visibility 0s linear .3s,
                        box-shadow 0s linear .3s;
            /* Off-screen but still painting its right border and any
               shadow down the screen edge; hide it outright. */
            visibility: hidden;
            box-shadow: none;
            width: min(84vw, var(--sidebar-width));
            /* Clear the notch and the home indicator. */
            padding-top: env(safe-area-inset-top, 0px);
            padding-bottom: env(safe-area-inset-bottom, 0px);
            overscroll-behavior: contain;
        }
        .sidebar.is-open {
            transform: translateX(0);
            visibility: visible;
            box-shadow: 8px 0 32px rgba(0, 0, 0, .3);
            transition: transform .3s cubic-bezier(.32, .72, 0, 1),
                        visibility 0s linear 0s,
                        box-shadow 0s linear 0s;
        }

        .main-content { margin-left: 0; padding: 1.25rem 1rem 2.5rem; }

        /* The bar now carries the drawer trigger on the left, so it can
           no longer be a flex-end row. */
        .top-bar {
            justify-content: space-between;
            margin: 0 -1rem 1.25rem;
            padding: .6rem 1rem;
            padding-left: max(1rem, env(safe-area-inset-left, 0px));
            padding-right: max(1rem, env(safe-area-inset-right, 0px));
        }

        .panel-hamburger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--ink);
            font-size: 1.4rem;
            line-height: 1;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        .panel-hamburger:active { background: #f1f5f9; }

        /* Close control, only meaningful while the drawer is a drawer.
           The header is a column flex container, so this is pinned
           rather than added to the flow. */
        .sidebar-header { position: relative; padding-right: 3.6rem; }
        .sidebar-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 1rem;
            right: .9rem;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, .08);
            color: #fff;
            font-size: 1.1rem;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        .sidebar-close:active { background: rgba(255, 255, 255, .18); }

        /* Comfortable targets for the nav rows. */
        .sidebar .nav-link { padding-top: .8rem; padding-bottom: .8rem; }

        .header-title { font-size: 1.25rem; }
    }

    /* The close button has no purpose while the sidebar is pinned. */
    @media (min-width: 1025px) {
        .sidebar-close { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        * { transition: none !important; }
    }
</style>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/layouts/partials/panel-theme.blade.php ENDPATH**/ ?>