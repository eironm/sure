@extends('layouts.app')

@section('title', 'Campus Board')

@section('content')
<!-- ══════════════════════════════════════════════════════════════
     Campus Board — Transparency Feed
     What's happening across campus, and which spaces are free.
     ══════════════════════════════════════════════════════════════ -->
<style>
    .cb {
        --fx-accent: var(--brand-primary, #2563eb);
        --fx-accent-rgb: var(--brand-primary-rgb, 37, 99, 235);
        --fx-ink: var(--brand-dark, #0f172a);
        --fx-ink-2: #475569;
        --fx-muted: #94a3b8;
        --fx-line: #e8edf3;
        --fx-card: #ffffff;
        --fx-radius: 18px;
        max-width: 1240px;
        margin: 0 auto;
        padding: 2rem 1.25rem 4rem;
        color: var(--fx-ink);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* ── Hero ──────────────────────────────────────────────────── */
    .cb-hero {
        position: relative;
        border-radius: 22px;
        padding: 2rem 2.25rem;
        margin-bottom: 1.5rem;
        overflow: hidden;
        background: linear-gradient(135deg, var(--brand-dark, #0f172a), #1e293b 55%, color-mix(in srgb, var(--fx-accent) 30%, #1e293b));
        color: #fff;
    }
    .cb-hero::after {
        content: '';
        position: absolute; top: -60%; right: -8%;
        width: 420px; height: 420px; border-radius: 50%;
        background: radial-gradient(circle, rgba(var(--fx-accent-rgb), .25), transparent 70%);
    }
    .cb-hero-row { position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: flex-start; gap: 1.5rem; flex-wrap: wrap; }
    .cb-hero .cb-eyebrow { display: inline-flex; align-items: center; gap: .5rem; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; color: #93c5fd; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); padding: .35rem .8rem; border-radius: 999px; margin-bottom: .75rem; }
    .cb-hero .cb-eyebrow .cb-live-dot { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; animation: cbpulse 1.8s infinite; }
    .cb-hero h1 { font-size: 2rem; font-weight: 800; letter-spacing: -0.03em; margin: 0 0 .35rem; }
    .cb-hero p { margin: 0; color: rgba(255,255,255,.72); font-size: .95rem; max-width: 40rem; }
    .cb-clock { text-align: right; }
    .cb-clock-time { font-family: 'JetBrains Mono', monospace; font-size: 1.9rem; font-weight: 800; line-height: 1; }
    .cb-clock-date { font-size: .8rem; color: rgba(255,255,255,.7); margin-top: .3rem; }

    /* ── Stats ──────────────────────────────────────────────────── */
    .cb-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
    .cb-stat {
        background: var(--fx-card); border: 1px solid var(--fx-line); border-radius: var(--fx-radius);
        padding: 1.15rem 1.35rem; display: flex; align-items: center; gap: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.04); transition: transform .2s, box-shadow .2s;
    }
    .cb-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.06); }
    .cb-stat-ic { width: 46px; height: 46px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    .cb-stat-ic.a { background: rgba(var(--fx-accent-rgb), .1); color: var(--fx-accent); }
    .cb-stat-ic.g { background: #ecfdf5; color: #10b981; }
    .cb-stat-ic.o { background: #fffbeb; color: #f59e0b; }
    .cb-stat-ic.s { background: #eff6ff; color: #3b82f6; }
    .cb-stat-val { font-family: 'JetBrains Mono', monospace; font-size: 1.65rem; font-weight: 800; line-height: 1; }
    .cb-stat-lbl { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--fx-muted); margin-top: .25rem; }

    /* ── Cards / sections ───────────────────────────────────────── */
    .cb-card { background: var(--fx-card); border: 1px solid var(--fx-line); border-radius: var(--fx-radius); box-shadow: 0 1px 3px rgba(0,0,0,.04); margin-bottom: 1.75rem; overflow: hidden; }
    .cb-card-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.15rem 1.5rem; border-bottom: 1px solid var(--fx-line); flex-wrap: wrap; }
    .cb-card-title { font-size: 1.05rem; font-weight: 800; display: flex; align-items: center; gap: .55rem; margin: 0; letter-spacing: -.01em; }
    .cb-card-title i { color: var(--fx-accent); }
    .cb-pill { font-size: .74rem; font-weight: 600; color: var(--fx-ink-2); background: #f1f5f9; border: 1px solid var(--fx-line); padding: .28rem .8rem; border-radius: 999px; }
    .cb-card-body { padding: 1.5rem; }

    /* ── Feed day groups ────────────────────────────────────────── */
    .cb-dayhead { display: flex; align-items: center; gap: .85rem; margin: 1.6rem 0 .9rem; }
    .cb-dayhead:first-child { margin-top: 0; }
    .cb-daychip { display: inline-flex; align-items: center; gap: .5rem; font-size: .76rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; padding: .4rem .95rem; border-radius: 999px; white-space: nowrap; }
    .cb-daychip.today { background: var(--fx-ink); color: #fff; }
    .cb-daychip.live { background: linear-gradient(90deg, var(--brand-dark, #0f172a), var(--brand-primary, #1e3a8a)); color: #fff; border: 1px solid rgba(255,255,255,.15); }
    .cb-daychip.soon { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .cb-daychip.later { background: #f1f5f9; color: var(--fx-ink-2); border: 1px solid var(--fx-line); }
    .cb-dayhead .cb-dayrule { flex: 1; height: 1px; background: var(--fx-line); }
    .cb-dayhead .cb-daycount { font-size: .72rem; font-weight: 700; color: var(--fx-muted); }

    /* ── Event cards ────────────────────────────────────────────── */
    .cb-feed { display: grid; gap: .8rem; }
    .cb-event {
        display: grid; grid-template-columns: auto 1fr auto; gap: 1rem; align-items: start;
        border: 1px solid var(--fx-line); border-radius: 14px; padding: 1rem 1.15rem;
        background: #fff; transition: transform .15s, box-shadow .15s, border-color .15s;
    }
    .cb-event:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,.06); border-color: rgba(var(--fx-accent-rgb), .3); }
    .cb-event.is-live { border-color: rgba(16,185,129,.45); background: linear-gradient(135deg, rgba(16,185,129,.05), rgba(59,130,246,.03)); box-shadow: inset 3px 0 0 #10b981; }
    .cb-event-time { text-align: center; min-width: 58px; }
    .cb-event-t1 { font-family: 'JetBrains Mono', monospace; font-weight: 800; font-size: .92rem; }
    .cb-event-t2 { font-size: .68rem; color: var(--fx-muted); font-weight: 600; margin-top: .15rem; }
    .cb-event-body { min-width: 0; }
    .cb-event-title { font-weight: 800; font-size: .98rem; line-height: 1.25; }
    .cb-event-meta { display: flex; flex-wrap: wrap; gap: .55rem; margin-top: .45rem; font-size: .76rem; color: var(--fx-ink-2); align-items: center; }
    .cb-event-meta span { display: inline-flex; align-items: center; gap: .3rem; white-space: nowrap; }
    .cb-event-meta i { color: var(--fx-muted); font-size: .74rem; }
    .cb-event-meta .cb-org { font-weight: 700; color: var(--fx-ink); }
    .cb-fac-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .cb-event-actions { display: flex; gap: .45rem; align-items: center; align-self: center; flex-wrap: wrap; justify-content: flex-end; }
    .cb-badge { display: inline-flex; align-items: center; gap: .3rem; padding: .3rem .7rem; border-radius: 999px; font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; white-space: nowrap; }
    .cb-b-live { background: #10b981; color: #fff; }
    .cb-b-live .cb-dot-live { width: 6px; height: 6px; border-radius: 50%; background: #fff; animation: cbpulse 1.4s infinite; }
    .cb-b-up { background: #fffbeb; color: #92400e; }
    .cb-b-next { background: #eff6ff; color: #1e40af; }
    .cb-b-reserved { background: #f1f5f9; color: var(--fx-muted); }
    .cb-resid { display: block; font-family: 'JetBrains Mono', monospace; font-size: .64rem; color: var(--fx-muted); margin-top: .35rem; }
    .cb-link-sm { display: inline-flex; align-items: center; gap: .3rem; font-size: .74rem; font-weight: 700; color: var(--fx-accent); background: rgba(var(--fx-accent-rgb), .07); border: 1px solid rgba(var(--fx-accent-rgb), .18); padding: .32rem .7rem; border-radius: 8px; text-decoration: none; white-space: nowrap; }
    .cb-link-sm:hover { background: rgba(var(--fx-accent-rgb), .14); color: var(--fx-accent); }

    /* ── Activity ledger ────────────────────────────────────────── */
    .cb-ledger { display: grid; gap: 0; }
    .cb-ledger-item { display: grid; grid-template-columns: auto 1fr auto; gap: .85rem; align-items: center; padding: .8rem 1.5rem; border-bottom: 1px solid #f1f5f9; transition: background .15s; }
    .cb-ledger-item:last-child { border-bottom: none; }
    .cb-ledger-item:hover { background: #f8fafc; }
    .cb-ledger-when { font-family: 'JetBrains Mono', monospace; font-size: .7rem; font-weight: 700; color: var(--fx-muted); }
    .cb-ledger-txt { font-size: .84rem; }
    .cb-ledger-txt strong { font-weight: 700; }
    .cb-ledger-date { font-size: .7rem; font-weight: 700; color: var(--fx-ink-2); background: #f1f5f9; border: 1px solid var(--fx-line); padding: .22rem .6rem; border-radius: 8px; white-space: nowrap; }

    /* ── Facility openness ──────────────────────────────────────── */
    .cb-facgrid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 1rem; }
    .cb-facchip { border: 1px solid var(--fx-line); border-radius: 14px; padding: 1.1rem 1.15rem; display: flex; align-items: center; gap: .85rem; transition: transform .2s, box-shadow .2s; }
    .cb-facchip:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0,0,0,.06); }
    .cb-facchip.occ { border-color: rgba(245,158,11,.3); background: rgba(245,158,11,.03); }
    .cb-facchip.open { border-color: rgba(16,185,129,.3); background: rgba(16,185,129,.03); }
    .cb-facchip-dot { width: 13px; height: 13px; border-radius: 50%; flex-shrink: 0; }
    .cb-facchip-name { font-weight: 700; font-size: .9rem; }
    .cb-facchip-st { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
    .cb-facchip-st.open { color: #059669; }
    .cb-facchip-st.occ { color: #b45309; }
    .cb-facchip-ev { font-size: .76rem; color: var(--fx-ink-2); font-style: italic; margin-top: .2rem; }

    /* ── Availability planner (kept, reservation-relevant) ──────── */
    .cb-legend { display: flex; flex-wrap: wrap; gap: .9rem; margin-bottom: 1.1rem; font-size: .76rem; font-weight: 600; color: var(--fx-ink-2); }
    .cb-legend span { display: inline-flex; align-items: center; gap: .4rem; }
    .cb-legend i { width: 13px; height: 13px; border-radius: 4px; display: inline-block; }
    .cb-scroll { overflow-x: auto; }
    .cb-grid { border-collapse: separate; border-spacing: 4px; width: 100%; min-width: 780px; }
    .cb-grid th { font-size: .66rem; font-weight: 700; color: var(--fx-muted); text-transform: uppercase; text-align: center; padding: .2rem .1rem; white-space: nowrap; }
    .cb-grid th.today { color: var(--fx-accent); }
    .cb-grid th.today::after { content: ''; display: block; width: 5px; height: 5px; border-radius: 50%; background: var(--fx-accent); margin: 3px auto 0; }
    .cb-grid td.fac { font-size: .82rem; font-weight: 700; white-space: nowrap; padding-right: .75rem; min-width: 155px; }
    .cb-cell { width: 40px; height: 36px; border-radius: 9px; border: none; display: block; margin: 0 auto; position: relative; transition: transform .12s, box-shadow .12s; }
    .cb-cell-free { background: #dcfce7; }
    .cb-cell-free.clickable { cursor: pointer; }
    .cb-cell-free.clickable:hover { transform: scale(1.14); box-shadow: 0 6px 14px rgba(16,185,129,.4); background: #86efac; }
    .cb-cell-pending { background: #fde68a; }
    .cb-cell-booked { background: #fca5a5; }
    .cb-cell-blackout { background: #cbd5e1; background-image: repeating-linear-gradient(45deg, transparent, transparent 4px, rgba(0,0,0,.12) 4px, rgba(0,0,0,.12) 8px); }
    .cb-cell-penciled { background: #ddd6fe; }
    .cb-cell-penciled.mine { outline: 2px solid var(--brand-secondary, #7c3aed); outline-offset: 1px; }
    .cb-cell-count { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: .68rem; font-weight: 800; color: #5b21b6; }

    /* ── My pencils ─────────────────────────────────────────────── */
    .cb-pencils { margin-top: 1.4rem; border-top: 1px dashed var(--fx-line); padding-top: 1.2rem; }
    .cb-pencil { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 12px; padding: .7rem 1rem; margin-bottom: .6rem; font-size: .85rem; }
    .cb-btn-sm { display: inline-flex; align-items: center; gap: .35rem; font-size: .78rem; font-weight: 700; padding: .42rem .9rem; border-radius: 9px; border: none; cursor: pointer; text-decoration: none; }
    .cb-btn-reserve { background: var(--fx-accent); color: #fff; }
    .cb-btn-reserve:hover { filter: brightness(.92); color: #fff; }
    .cb-btn-remove { background: #fff; border: 1px solid #fca5a5; color: #dc2626; }

    .cb-empty { text-align: center; padding: 2.75rem 1.5rem; color: var(--fx-muted); }
    .cb-empty i { font-size: 2.4rem; display: block; margin-bottom: .6rem; color: var(--fx-line); }
    .cb-empty-t { font-size: 1rem; font-weight: 700; color: var(--fx-ink); margin-bottom: .2rem; }

    .cb-note { text-align: center; font-size: .78rem; color: var(--fx-muted); margin-top: 1.5rem; }
    .cb-note i { color: var(--fx-accent); }

    @keyframes cbpulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

    @media (max-width: 768px) {
        .cb-stats { grid-template-columns: 1fr 1fr; }
        .cb-hero h1 { font-size: 1.6rem; }
        .cb-clock { text-align: left; }
        .cb-event { grid-template-columns: auto 1fr; }
        .cb-event-actions { grid-column: 1 / -1; justify-content: flex-start; }
        .cb-ledger-item { grid-template-columns: auto 1fr; }
        .cb-ledger-date { justify-self: end; }
        .cb-card-body { padding: 1rem; }
        .cb-scroll {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            scroll-snap-type: x proximity;
            scroll-padding-left: 124px;
            -webkit-mask-image: linear-gradient(to right, #000 calc(100% - 24px), transparent 100%);
                    mask-image: linear-gradient(to right, #000 calc(100% - 24px), transparent 100%);
        }
        .cb-scroll.at-end { -webkit-mask-image: none; mask-image: none; }
        .cb-grid { min-width: 640px; }
        .cb-grid td.fac { position: sticky; left: 0; z-index: 2; background: #fff; min-width: 120px; max-width: 120px; font-size: .74rem; padding-right: .5rem; overflow: hidden; text-overflow: ellipsis; box-shadow: 2px 0 4px rgba(15, 23, 42, .06); }
        .cb-grid thead th:first-child { position: sticky; left: 0; z-index: 3; background: #fff; }
        .cb-grid td:not(.fac) { scroll-snap-align: start; }
        .cb-cell { width: 38px; height: 40px; }
        .cb-legend { font-size: .7rem; gap: .6rem; }
    }
    @media (max-width: 480px) { .cb-stats { grid-template-columns: 1fr; } .cb-facgrid { grid-template-columns: 1fr; } }

    @media (hover: none) and (pointer: coarse) {
        .cb-cell-free.clickable:hover { transform: none; box-shadow: none; background: #dcfce7; }
        .cb-cell-free.clickable:active { transform: scale(1.1); background: #86efac; }
        .cb-event:hover, .cb-ledger-item:hover { transform: none; box-shadow: none; }
    }
</style>

<div class="cb">

    {{-- Hero ──────────────────────────────────────────────────── --}}
    <div class="cb-hero">
        <div class="cb-hero-row">
            <div>
                <span class="cb-eyebrow"><span class="cb-live-dot"></span> Campus Board — Live</span>
                <h1>What's Happening on Campus</h1>
                <p>The open feed of approved events, activities, and bookings across every SURE venue &mdash; see what's on, what's coming, and which spaces are free right now.</p>
            </div>
            <div class="cb-clock">
                <div class="cb-clock-time" id="cbClock">--:--:--</div>
                <div class="cb-clock-date" id="cbDate">&mdash;</div>
            </div>
        </div>
    </div>

    {{-- Stats ──────────────────────────────────────────────────── --}}
    <div class="cb-stats">
        <div class="cb-stat">
            <div class="cb-stat-ic a"><i class="bi bi-calendar2-event"></i></div>
            <div><div class="cb-stat-val">{{ $stats['happening_today'] }}</div><div class="cb-stat-lbl">Happening Today</div></div>
        </div>
        <div class="cb-stat">
            <div class="cb-stat-ic g"><i class="bi bi-broadcast"></i></div>
            <div><div class="cb-stat-val">{{ $stats['live_now'] }}</div><div class="cb-stat-lbl">Live Right Now</div></div>
        </div>
        <div class="cb-stat">
            <div class="cb-stat-ic o"><i class="bi bi-calendar-plus"></i></div>
            <div><div class="cb-stat-val">{{ $stats['upcoming_7d'] }}</div><div class="cb-stat-lbl">Next 7 Days</div></div>
        </div>
        <div class="cb-stat">
            <div class="cb-stat-ic s"><i class="bi bi-door-open"></i></div>
            <div><div class="cb-stat-val">{{ $stats['available'] }}</div><div class="cb-stat-lbl">Spaces Open</div></div>
        </div>
    </div>

    {{-- What's Happening — the transparency feed ──────────────── --}}
    <div class="cb-card">
        <div class="cb-card-head">
            <h2 class="cb-card-title"><i class="bi bi-megaphone"></i> What's Happening</h2>
            <span class="cb-pill">Approved campus activity &mdash; public &amp; open</span>
        </div>
        <div class="cb-card-body">
            @php
                $todayStr = $today->format('Y-m-d');
                $tomorrowStr = $today->copy()->addDay()->format('Y-m-d');
                $weekEndStr = $today->copy()->addDays(6)->format('Y-m-d');
                $totalEvents = collect($feedSections)->sum(fn($items) => $items->count());
            @endphp

            @forelse($feedSections as $dateStr => $events)
                @php
                    $dateCarbon = \Carbon\Carbon::parse($dateStr);
                    if ($dateStr === $todayStr) { $group = 'today'; $label = 'Today'; $chip = 'today'; $dayLbl = $dateCarbon->format('l, M j'); }
                    elseif ($dateStr === $tomorrowStr) { $group = 'tomorrow'; $label = 'Tomorrow'; $chip = 'soon'; $dayLbl = $dateCarbon->format('l, M j'); }
                    elseif ($dateStr <= $weekEndStr) { $group = 'soon'; $label = 'Next Few Days'; $chip = 'soon'; $dayLbl = $dateCarbon->format('D, M j'); }
                    else { $group = 'later'; $label = 'Beyond This Week'; $chip = 'later'; $dayLbl = $dateCarbon->format('D, M j'); }
                @endphp

                <div class="cb-dayhead">
                    <span class="cb-daychip {{ $chip }}">{{ $label }}</span>
                    <span class="cb-dayrule"></span>
                    <span class="cb-daycount">{{ $dayLbl }} &middot; {{ $events->count() }} {{ Str::plural('event', $events->count()) }}</span>
                </div>

                <div class="cb-feed">
                    @foreach($events as $ev)
                        @php $isLive = $ev['start_date'] === $todayStr && ($now->format('H:i') >= \Carbon\Carbon::parse($ev['start_time'])->format('H:i')) && ($now->format('H:i') <= \Carbon\Carbon::parse($ev['end_time'])->format('H:i')); @endphp
                        <div class="cb-event {{ $isLive ? 'is-live' : '' }}">
                            <div class="cb-event-time">
                                <div class="cb-event-t1">{{ $ev['start_time'] }}</div>
                                <div class="cb-event-t2">{{ $ev['end_time'] }}</div>
                            </div>
                            <div class="cb-event-body">
                                <div class="cb-event-title">{{ $ev['title'] }}@if($ev['multi_day']) <span class="cb-badge cb-b-next" style="font-size:.6rem;">Multi-Day</span>@endif</div>
                                <div class="cb-event-meta">
                                    <span><i class="bi bi-person-fill"></i><span class="cb-org">{{ $ev['requester'] }}</span></span>
                                    <span><span class="cb-fac-dot" style="background: {{ $ev['dot_color'] }};"></span>{{ $ev['facility'] }}</span>
                                    @if($ev['participants']) <span><i class="bi bi-people-fill"></i>{{ number_format($ev['participants']) }} people</span> @endif
                                </div>
                                @if($isLive) <span class="cb-badge cb-b-live" style="margin-top:.5rem;"><span class="cb-dot-live"></span> Happening Now</span> @endif
                            </div>
                            <div class="cb-event-actions">
                                @if($ev['start_date'] > $todayStr)
                                    <a href="{{ route('guest.reserve') }}" class="cb-link-sm"><i class="bi bi-calendar-plus"></i> Want in? Book next</a>
                                @endif
                                <span class="cb-badge {{ $isLive ? 'cb-b-live' : ($ev['start_date'] === $todayStr ? 'cb-b-up' : 'cb-b-reserved') }}">{{ $isLive ? 'LIVE' : ($ev['start_date'] === $todayStr ? 'TODAY' : 'APPROVED') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="cb-empty">
                    <i class="bi bi-megaphone"></i>
                    <div class="cb-empty-t">Nothing Scheduled Yet</div>
                    <p>No approved events on the board. When something gets approved, it appears here.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Activity Ledger ──────────────────────────────────── --}}
    @if(count($recentActivity))
        <div class="cb-card">
            <div class="cb-card-head">
                <h2 class="cb-card-title"><i class="bi bi-journal-check"></i> Recent Activity</h2>
                <span class="cb-pill">Bookings &amp; approvals &mdash; the transparency log</span>
            </div>
            <div class="cb-ledger">
                @foreach($recentActivity as $act)
                    <div class="cb-ledger-item">
                        <span class="cb-fac-dot" style="background: {{ $act['dot_color'] }};"></span>
                        <span class="cb-ledger-txt"><strong>{{ $act['title'] }}</strong> was approved at <strong>{{ $act['facility'] }}</strong></span>
                        <span class="cb-ledger-date">{{ $act['date_label'] }}</span>
                    </div>
                @endforeach
            </div>
            <div style="padding:.9rem 1.5rem; border-top:1px solid #f1f5f9; font-size:.74rem; color:var(--fx-muted); text-align:center;">
                Latest approval was {{ $recentActivity[0]['when'] ?? '' }} &middot; approvals appear here as soon as they land
            </div>
        </div>
    @endif

    {{-- Facility Openness ──────────────────────────────────────── --}}
    <div class="cb-card">
        <div class="cb-card-head">
            <h2 class="cb-card-title"><i class="bi bi-signpost-split"></i> Spaces Right Now</h2>
            <span class="cb-pill">{{ count($facilities) }} {{ Str::plural('space', count($facilities)) }}</span>
        </div>
        <div class="cb-card-body">
            @if(count($facilities))
                <div class="cb-facgrid">
                    @foreach($facilities as $fac)
                        <div class="cb-facchip {{ $fac['occupied'] ? 'occ' : 'open' }}">
                            <span class="cb-facchip-dot" style="background: {{ $fac['occupied'] ? '#f59e0b' : '#10b981' }};"></span>
                            <div style="flex:1; min-width:0;">
                                <div class="cb-facchip-name">{{ $fac['name'] }}</div>
                                <div class="cb-facchip-st {{ $fac['occupied'] ? 'occ' : 'open' }}">{{ $fac['occupied'] ? 'In Use' : 'Open & Free' }}</div>
                                @if($fac['occupied'] && !empty($fac['current_event']))
                                    <div class="cb-facchip-ev">Inside: {{ $fac['current_event'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="cb-empty"><i class="bi bi-signpost-split"></i><div class="cb-empty-t">No Spaces Listed</div><p>There are no active facilities registered in the system.</p></div>
            @endif
        </div>
    </div>

    {{-- Availability Planner ─────────────────────────────────────── --}}
    <div class="cb-card">
        <div class="cb-card-head">
            <h2 class="cb-card-title"><i class="bi bi-calendar-range"></i> Availability Planner</h2>
            <span class="cb-pill">Next 14 days &mdash; tap a green day to pencil it in</span>
        </div>
        <div class="cb-card-body">
            <div class="cb-legend">
                <span><i style="background:#dcfce7;"></i> Free</span>
                <span><i style="background:#fde68a;"></i> Pending request</span>
                <span><i style="background:#fca5a5;"></i> Booked</span>
                <span><i style="background:#cbd5e1;"></i> Unavailable</span>
                <span><i style="background:#ddd6fe;"></i> Penciled (tentative)</span>
            </div>

            <div class="cb-scroll">
                <table class="cb-grid" aria-label="Facility availability for the next 14 days">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Facility</th>
                            @foreach($days as $day)
                                <th class="{{ $day->isToday() ? 'today' : '' }}">{{ $day->format('D') }}<br>{{ $day->format('j') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availability as $row)
                            <tr>
                                <td class="fac">
                                    <span class="cb-fac-dot" style="background: {{ $row['dot_color'] }};"></span>{{ $row['name'] }}
                                </td>
                                @foreach($row['cells'] as $cell)
                                    <td>
                                        @if($cell['status'] === 'free')
                                            <button type="button" class="cb-cell cb-cell-free clickable"
                                                data-facility-id="{{ $row['id'] }}"
                                                data-facility-name="{{ $row['name'] }}"
                                                data-date="{{ $cell['date'] }}"
                                                data-date-label="{{ \Carbon\Carbon::parse($cell['date'])->format('D, M j, Y') }}"
                                                title="Free — click to pencil {{ $row['name'] }} on {{ $cell['date_label'] }}"
                                                aria-label="Pencil {{ $row['name'] }} on {{ $cell['date_label'] }}"></button>
                                        @elseif($cell['status'] === 'penciled')
                                            <span class="cb-cell cb-cell-penciled {{ $cell['my_pencil_id'] ? 'mine' : '' }}"
                                                title="{{ $cell['pencil_count'] }} tentative {{ Str::plural('hold', $cell['pencil_count']) }}{{ $cell['my_pencil_id'] ? ' (including yours)' : '' }}">
                                                <span class="cb-cell-count">{{ $cell['pencil_count'] }}</span>
                                            </span>
                                        @else
                                            <span class="cb-cell cb-cell-{{ $cell['status'] }}" title="{{ ucfirst($cell['status']) }} — {{ $cell['date'] }}"></span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($myPencils->count())
                <div class="cb-pencils">
                    <h3 style="font-size:.95rem; font-weight:800; margin-bottom:.75rem;">
                        <i class="bi bi-pin-angle-fill" style="color:var(--brand-secondary, #7c3aed);"></i> My Pencil Holds
                    </h3>
                    @foreach($myPencils as $pencil)
                        <div class="cb-pencil">
                            <div>
                                <strong>{{ $pencil->facility?->name ?? 'Facility' }}</strong>
                                &mdash; {{ $pencil->date->format('D, M j, Y') }}
                                <span style="color:var(--brand-secondary, #7c3aed); font-weight:600;">&middot; expires {{ $pencil->expires_at->diffForHumans() }}</span>
                                @if($pencil->note)
                                    <div style="color: var(--fx-ink-2); font-size:.8rem;">"{{ $pencil->note }}"</div>
                                @endif
                            </div>
                            <div style="display:flex; gap:.5rem;">
                                <a href="{{ route('guest.reserve') }}" class="cb-btn-sm cb-btn-reserve" data-no-pjax>
                                    <i class="bi bi-calendar-check"></i> Reserve for real
                                </a>
                                <form method="POST" action="{{ route('guest.facility-board.pencil-cancel', $pencil) }}"
                                    onsubmit="return confirm('Remove this pencil hold?')" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cb-btn-sm cb-btn-remove"><i class="bi bi-x-lg"></i> Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="cb-note">
        <i class="bi bi-info-circle"></i>
        This board shows approved campus activity and live space availability. Data refreshes each time you load the page.
    </div>
</div>

{{-- Pencil booking modal ────────────────────────────────────────── --}}
<div class="modal fade" id="pencilModal" tabindex="-1" aria-labelledby="pencilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <form method="POST" action="{{ route('guest.facility-board.pencil') }}">
                @csrf
                <input type="hidden" name="facility_id" id="pencilFacilityId">
                <input type="hidden" name="date" id="pencilDate">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="pencilModalLabel"><i class="bi bi-pencil-square" style="color:var(--brand-secondary, #7c3aed);"></i> Pencil this date in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3" style="font-size:.9rem; color:#475569;">
                        You're placing a <strong>tentative, non-binding hold</strong> on
                        <strong id="pencilFacilityLabel"></strong> for <strong id="pencilDateLabel"></strong>.
                        It expires automatically in <strong>{{ \App\Models\PencilBooking::HOLD_DAYS }} days</strong>
                        and does not block others from booking &mdash; convert it to a real reservation to lock it in.
                    </p>
                    <label for="pencilNote" class="form-label fw-semibold" style="font-size:.85rem;">Note (optional)</label>
                    <input type="text" class="form-control" id="pencilNote" name="note" maxlength="255"
                        placeholder="e.g., Org fair — waiting for budget approval">
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background:var(--brand-secondary, #7c3aed); color:#fff; font-weight:700;"><i class="bi bi-pin-angle"></i> Place Pencil Hold</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        // Pencil modal
        document.querySelectorAll('.cb-cell-free.clickable').forEach(function (cell) {
            cell.addEventListener('click', function () {
                document.getElementById('pencilFacilityId').value = cell.dataset.facilityId;
                document.getElementById('pencilDate').value = cell.dataset.date;
                document.getElementById('pencilFacilityLabel').textContent = cell.dataset.facilityName;
                document.getElementById('pencilDateLabel').textContent = cell.dataset.dateLabel;
                document.getElementById('pencilNote').value = '';
                bootstrap.Modal.getOrCreateInstance(document.getElementById('pencilModal')).show();
            });
        });

        // Live clock
        const clockEl = document.getElementById('cbClock');
        const dateEl  = document.getElementById('cbDate');
        const pad = n => n.toString().padStart(2, '0');
        function tick() {
            const now = new Date();
            const h = now.getHours(), m = pad(now.getMinutes()), s = pad(now.getSeconds());
            const ampm = h >= 12 ? 'PM' : 'AM', h12 = h % 12 || 12;
            clockEl.textContent = pad(h12) + ':' + m + ':' + s + ' ' + ampm;
            dateEl.textContent = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        tick(); setInterval(tick, 1000);

        // Availability grid: drop the right-edge fade once the user has
        // scrolled to the end, so it does not imply there is more to see.
        const scroller = document.querySelector('.cb-scroll');
        if (scroller) {
            const updateEdge = () => {
                const atEnd = scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth - 2;
                scroller.classList.toggle('at-end', atEnd);
            };
            scroller.addEventListener('scroll', updateEdge, { passive: true });
            window.addEventListener('resize', updateEdge);
            updateEdge();
        }
    })();
</script>
@endsection
