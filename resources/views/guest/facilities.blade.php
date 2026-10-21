@extends('layouts.app')

@section('content')
@include('components.custom-alert')
<style>
/* ══════════════════════════════════════════════════════════════
   SURE — Campus Facilities (redesign)
   Matches the dashboard/document design language:
   Space Grotesk headings, Inter body, dark navy hero,
   white cards, blue primary accent.
   ══════════════════════════════════════════════════════════════ */
.fl-root { background: #f4f6fb; min-height: calc(100vh - 140px); }
.fl-wrap { max-width: 1240px; margin: 0 auto; padding: 1.75rem 1.5rem 3.5rem; }

/* ── Hero ─────────────────────────────────────────────── */
.fl-hero {
    background: linear-gradient(120deg, var(--brand-dark, #0f172a) 0%, #1e293b 62%, rgba(var(--brand-primary-rgb,37,99,235),0.55) 100%);
    border-radius: 18px;
    padding: 2.4rem 2.25rem;
    color: #fff;
    display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
    flex-wrap: wrap;
    box-shadow: 0 14px 34px rgba(15,23,42,.22);
    position: relative; overflow: hidden;
    margin-bottom: 1.4rem;
}
.fl-hero::after {
    content: ""; position: absolute; right: -60px; top: -60px;
    width: 260px; height: 260px; border-radius: 50%;
    background: rgba(37,99,235,.22); filter: blur(40px);
}
.fl-hero::before {
    content: ""; position: absolute; left: -40px; bottom: -80px;
    width: 220px; height: 220px; border-radius: 50%;
    background: rgba(124,58,237,.16); filter: blur(38px);
}
.fl-hero-left { position: relative; z-index: 2; max-width: 44rem; }
.fl-hero h1 {
    font-family: 'Space Grotesk','Inter',sans-serif;
    font-size: 2rem; font-weight: 700; margin: 0 0 .35rem;
    letter-spacing: -.02em; color: #fff;
}
.fl-hero p { margin: 0; color: rgba(255,255,255,.72); font-size: .95rem; }
.fl-hero-stats {
    position: relative; z-index: 2;
    display: flex; gap: 2.5rem;
}
.fl-hs-val {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.7rem; font-weight: 700; color: #fff; line-height: 1.1;
}
.fl-hs-lbl {
    font-size: .68rem; font-weight: 600; letter-spacing: .07em;
    text-transform: uppercase; color: rgba(255,255,255,.62); margin-top: .2rem;
}

/* ── Filter bar ───────────────────────────────────────── */
.fl-filterbar {
    display: flex; align-items: center; gap: .55rem; flex-wrap: wrap;
    margin-bottom: 1.4rem;
}
.fl-filterbar-label {
    font-size: .7rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: #64748b; margin-right: .4rem;
    display: flex; align-items: center; gap: .35rem;
}
.fl-chip {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .8rem; font-weight: 600;
    padding: .48rem 1rem; border-radius: 999px;
    border: 1.5px solid #dde3ee; background: #fff; color: #334155;
    cursor: pointer; user-select: none;
    transition: all .18s ease;
}
.fl-chip:hover { border-color: var(--brand-primary, #2563eb); color: var(--brand-primary, #2563eb); }
.fl-chip.active {
    background: var(--brand-dark, #0f172a); border-color: var(--brand-dark, #0f172a); color: #fff;
    box-shadow: 0 4px 12px rgba(15,23,42,.25);
}
.fl-chip .fl-chip-count {
    font-family: 'Space Grotesk', sans-serif;
    font-size: .68rem; font-weight: 700;
    background: rgba(15,23,42,.08); border-radius: 999px;
    padding: .05rem .42rem;
}
.fl-chip.active .fl-chip-count { background: rgba(255,255,255,.2); }
.fl-filterbar-right { margin-left: auto; display: flex; align-items: center; gap: .6rem; }
.fl-viewtoggle {
    display: inline-flex; background: #fff; border: 1.5px solid #dde3ee;
    border-radius: 10px; overflow: hidden;
}
.fl-viewtoggle button {
    border: none; background: transparent; padding: .45rem .7rem;
    font-size: .85rem; color: #64748b; cursor: pointer;
}
.fl-viewtoggle button.active { background: var(--brand-dark, #0f172a); color: #fff; }

/* ── Grid ─────────────────────────────────────────────── */
.fl-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.4rem; }

/* ── Facility card ────────────────────────────────────── */
.fl-card {
    background: #fff; border: 1px solid #e5e9f2; border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(15,23,42,.04);
    transition: transform .22s ease, box-shadow .22s ease;
    display: flex; flex-direction: column;
    opacity: 1; transform: translateY(0);
    animation: flfade .35s ease both;
}
@keyframes flfade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
.fl-card:hover { transform: translateY(-5px); box-shadow: 0 14px 32px rgba(15,23,42,.12); }

.fl-img { position: relative; height: 210px; overflow: hidden; background: var(--brand-dark, #0f172a); }
.fl-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .5s cubic-bezier(.4,0,.2,1);
}
.fl-card:hover .fl-img img { transform: scale(1.06); }
.fl-img::after {
    content: ""; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,.55) 0%, rgba(15,23,42,0) 45%);
}
.fl-cat {
    position: absolute; top: .9rem; left: .9rem; z-index: 3;
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .66rem; font-weight: 800; letter-spacing: .09em; text-transform: uppercase;
    background: rgba(15,23,42,.72); backdrop-filter: blur(8px);
    color: #fff; border: 1px solid rgba(255,255,255,.25);
    padding: .32rem .72rem; border-radius: 999px;
}
.fl-ratespan {
    position: absolute; top: .9rem; right: .9rem; z-index: 3;
    background: rgba(15,23,42,.8); backdrop-filter: blur(8px);
    color: #fff; border: 1px solid rgba(255,255,255,.25);
    font-family: 'Space Grotesk', sans-serif;
    font-size: .78rem; font-weight: 700;
    padding: .32rem .7rem; border-radius: 999px;
    white-space: nowrap;
}
.fl-ratecap {
    position: absolute; left: .9rem; bottom: .8rem; z-index: 3;
    display: flex; gap: .5rem;
    font-size: .68rem; font-weight: 700; color: rgba(255,255,255,.92);
}
.fl-ratecap span {
    display: inline-flex; align-items: center; gap: .28rem;
    background: rgba(15,23,42,.6); backdrop-filter: blur(6px);
    padding: .24rem .58rem; border-radius: 999px;
}

.fl-body { padding: 1.2rem 1.25rem 1rem; flex: 1; display: flex; flex-direction: column; }
.fl-name {
    font-family: 'Space Grotesk','Inter',sans-serif;
    font-size: 1.15rem; font-weight: 700; color: var(--brand-dark, #0f172a);
    letter-spacing: -.01em; margin: 0 0 .3rem; line-height: 1.25;
}
.fl-loc {
    display: flex; align-items: center; gap: .35rem;
    font-size: .78rem; color: #64748b; margin-bottom: .75rem;
}
.fl-loc i { color: var(--brand-primary, #2563eb); font-size: .8rem; }
.fl-desc {
    font-size: .85rem; color: #475569; line-height: 1.55;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;
    overflow: hidden; margin: 0 0 .9rem;
}
.fl-tags { display: flex; flex-wrap: wrap; gap: .35rem; margin-bottom: 1rem; }
.fl-tag {
    font-size: .64rem; font-weight: 700; letter-spacing: .03em; text-transform: uppercase;
    color: #3b82f6; background: #eff6ff;
    border: 1px solid #dbeafe;
    padding: .18rem .5rem; border-radius: 999px;
}
.fl-footer {
    display: flex; align-items: center; justify-content: space-between;
    gap: .7rem; padding: 1rem 1.25rem;
    border-top: 1px solid #eef1f7; background: #fbfcfe;
    margin-top: auto;
}
.fl-price { display: flex; flex-direction: column; }
.fl-price .fl-price-val {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.1rem; font-weight: 700; color: var(--brand-dark, #0f172a); line-height: 1.1;
}
.fl-price .fl-price-lbl {
    font-size: .62rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em;
    color: #94a3b8;
}
.fl-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .82rem; font-weight: 700;
    padding: .58rem 1.1rem; border-radius: 10px;
    background: var(--brand-primary, #2563eb); color: #fff; border: none; cursor: pointer;
    text-decoration: none; white-space: nowrap;
    transition: all .18s ease;
}
.fl-btn:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(37,99,235,.4); color: #fff; }
.fl-btn-ghost { background: #fff; color: var(--brand-dark, #0f172a); border: 1.5px solid #dde3ee; }
.fl-btn-ghost:hover { border-color: var(--brand-dark, #0f172a); box-shadow: none; transform: translateY(-1px); color: var(--brand-dark, #0f172a); }

/* Empty */
.fl-empty {
    grid-column: 1 / -1;
    background: #fff; border: 1.5px dashed #cbd5e1; border-radius: 16px;
    text-align: center; padding: 3rem 1.5rem; color: #94a3b8;
}
.fl-empty i { font-size: 2.4rem; display: block; margin-bottom: .7rem; color: #cbd5e1; }
.fl-empty .fl-empty-t { font-size: 1.05rem; font-weight: 700; color: #475569; margin-bottom: .3rem; }

/* ── Modal ────────────────────────────────────────────── */
.fl-modal-overlay {
    position: fixed; inset: 0; z-index: 1050;
    background: rgba(15,23,42,.62); backdrop-filter: blur(6px);
    display: none; align-items: flex-start; justify-content: center;
    padding: 2.5rem 1rem; overflow-y: auto;
    opacity: 0; transition: opacity .25s ease;
}
.fl-modal-overlay.active { display: flex; opacity: 1; }
.fl-modal {
    background: #fff; border-radius: 18px; width: 100%; max-width: 860px;
    overflow: hidden;
    box-shadow: 0 30px 80px rgba(15,23,42,.45);
    transform: scale(.94); opacity: 0;
    transition: transform .28s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
}
.fl-modal-overlay.active .fl-modal { transform: scale(1); opacity: 1; }

.fl-m-hero { position: relative; height: 340px; overflow: hidden; }
.fl-m-hero img { width: 100%; height: 100%; object-fit: cover; }
.fl-m-hero::after {
    content: ""; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,.85) 0%, rgba(15,23,42,.15) 55%, rgba(15,23,42,0) 100%);
}
.fl-m-hero-title {
    position: absolute; left: 1.8rem; right: 1.8rem; bottom: 1.2rem; z-index: 3; color: #fff;
}
.fl-m-hero-cat {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .68rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
    background: rgba(37,99,235,.85); padding: .3rem .7rem; border-radius: 999px; margin-bottom: .5rem;
}
.fl-m-hero-title h2 {
    font-family: 'Space Grotesk','Inter',sans-serif;
    font-size: 1.9rem; font-weight: 700; margin: 0; letter-spacing: -.02em;
    text-shadow: 0 2px 10px rgba(0,0,0,.4);
}
.fl-m-hero-loc { display: flex; align-items: center; gap: .4rem; font-size: .85rem; color: rgba(255,255,255,.85); margin-top: .35rem; }
.fl-m-close {
    position: absolute; top: 1rem; right: 1rem; z-index: 4;
    width: 38px; height: 38px; border-radius: 50%;
    background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.3); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; cursor: pointer; transition: all .2s ease;
}
.fl-m-close:hover { background: rgba(15,23,42,.9); transform: rotate(90deg); }

.fl-m-body { padding: 1.75rem 1.8rem 1.25rem; }
.fl-m-section { margin-bottom: 1.6rem; }
.fl-m-section-title {
    font-size: .72rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
    color: #64748b; margin-bottom: .8rem;
    display: flex; align-items: center; gap: .55rem;
}
.fl-m-section-title::after { content: ''; flex: 1; height: 1px; background: #eef1f7; }
.fl-m-desc { font-size: .95rem; color: #334155; line-height: 1.7; margin: 0; white-space: pre-line; }

.fl-m-kpis { display: grid; grid-template-columns: repeat(3, 1fr); gap: .9rem; }
@media (max-width: 640px) { .fl-m-kpis { grid-template-columns: 1fr; } }
.fl-m-kpi {
    background: #f8fafc; border: 1px solid #eef1f7; border-radius: 12px;
    padding: .9rem 1rem;
}
.fl-m-kpi .fl-m-kpi-lbl { font-size: .64rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #94a3b8; }
.fl-m-kpi .fl-m-kpi-val {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.25rem; font-weight: 700; color: var(--brand-dark, #0f172a); margin-top: .2rem;
}

.fl-m-tags { display: flex; flex-wrap: wrap; gap: .45rem; }
.fl-m-tag {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .74rem; font-weight: 600; color: #334155;
    background: #f1f5f9; border: 1px solid #e2e8f0;
    padding: .34rem .7rem; border-radius: 999px;
}
.fl-m-tag i { color: var(--brand-primary, #2563eb); }

.fl-m-amenities { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: .55rem; }
.fl-m-amen {
    display: flex; align-items: center; gap: .55rem;
    background: #f8fafc; border: 1px solid #eef1f7; border-radius: 10px;
    padding: .6rem .8rem; font-size: .82rem; font-weight: 600; color: #334155;
}
.fl-m-amen i { color: #10b981; flex-shrink: 0; }

.fl-m-terms { font-size: .85rem; color: #475569; line-height: 1.7; margin: 0; white-space: pre-line; }

.fl-m-footer {
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    padding: 1.1rem 1.8rem; border-top: 1px solid #eef1f7; background: #fbfcfe;
    flex-wrap: wrap;
}
.fl-m-total { display: flex; align-items: baseline; gap: .5rem; }
.fl-m-total-val {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.4rem; font-weight: 700; color: var(--brand-dark, #0f172a);
}
.fl-m-total-lbl { font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: #94a3b8; }

/* ── Lightbox ─────────────────────────────────────────── */
.fl-lb {
    position: fixed; inset: 0; z-index: 2050;
    background: rgba(5,10,20,.94);
    display: none; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s ease;
}
.fl-lb.active { display: flex; opacity: 1; }
.fl-lb img { max-width: 92%; max-height: 86vh; object-fit: contain; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,.6); }
.fl-lb-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 52px; height: 52px; border-radius: 50%;
    background: rgba(255,255,255,.12); backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.25); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; cursor: pointer; transition: all .2s ease;
}
.fl-lb-nav:hover { background: rgba(255,255,255,.25); }
.fl-lb-prev { left: 1.5rem; } .fl-lb-next { right: 1.5rem; }
.fl-lb-close {
    position: absolute; top: 1.5rem; right: 1.5rem;
    width: 44px; height: 44px; border-radius: 50%;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
    color: #fff; font-size: 1.1rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.fl-lb-counter {
    position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
    background: rgba(15,23,42,.7); color: #fff;
    font-family: 'Space Grotesk', sans-serif; font-size: .8rem; font-weight: 700;
    padding: .4rem 1rem; border-radius: 999px;
}

@media (max-width: 768px) {
    .fl-hero h1 { font-size: 1.55rem; }
    .fl-hero-stats { gap: 1.5rem; }
    .fl-grid { grid-template-columns: 1fr; }
    .fl-m-hero { height: 240px; }
    .fl-m-hero-title h2 { font-size: 1.4rem; }
    .fl-m-body { padding: 1.4rem; }
}
@media (max-width: 480px) {
    .fl-hero { padding: 1.7rem 1.25rem; }
    .fl-hero-stats { display: none; }
    .fl-img { height: 180px; }
}
</style>

<div class="fl-root">
    <div class="fl-wrap">

        <!-- fl-section-hero -->
        <div class="fl-hero">
            <div class="fl-hero-left">
                <h1>Campus Facilities</h1>
                <p>Explore our bookable venues &mdash; every space photographed, priced, and ready for your next event.</p>
            </div>
            <div class="fl-hero-stats">
                <div>
                    <div class="fl-hs-val">{{ count($facilities) }}</div>
                    <div class="fl-hs-lbl">{{ Str::plural('Venue', count($facilities)) }}</div>
                </div>
                <div>
                    <div class="fl-hs-val">{{ count($categories) }}</div>
                    <div class="fl-hs-lbl">Categories</div>
                </div>
                <div>
                    <div class="fl-hs-val">{{ number_format(collect($facilities)->min('price_per_hour'), 0) }}&ndash;{{ number_format(collect($facilities)->max('price_per_hour'), 0) }}</div>
                    <div class="fl-hs-lbl">Rate Range per Hour</div>
                </div>
            </div>
        </div>

        <!-- fl-section-filter -->
        <div class="fl-filterbar">
            <span class="fl-filterbar-label"><i class="bi bi-funnel-fill"></i> Filter</span>
            <button type="button" class="fl-chip active" data-filter="all">
                All <span class="fl-chip-count">{{ count($facilities) }}</span>
            </button>
            @foreach($categories as $cat)
                @php $n = count($facilities->where('category_id', $cat->id)); @endphp
                @if($n > 0)
                <button type="button" class="fl-chip" data-filter="{{ $cat->id }}">
                    <i class="bi {{ $cat->icon ?? 'bi-building' }}"></i> {{ $cat->name }}
                    <span class="fl-chip-count">{{ $n }}</span>
                </button>
                @endif
            @endforeach
            <div class="fl-filterbar-right">
                <div class="fl-viewtoggle" role="group" aria-label="Grid density">
                    <button type="button" class="active" data-density="normal" title="Standard"><i class="bi bi-grid-3x3-gap-fill"></i></button>
                    <button type="button" data-density="compact" title="Compact"><i class="bi bi-list-ul"></i></button>
                </div>
            </div>
        </div>

        <!-- fl-section-grid -->
        <div class="fl-grid" id="flGrid">
            @forelse($facilities as $facility)
                @php
                    $catIcon = match($facility->category->name ?? '') {
                        'Auditorium' => 'bi-music-note-beamed',
                        'Sports' => 'bi-basketball',
                        'Meeting Rooms' => 'bi-people-fill',
                        'Outdoor' => 'bi-tree',
                        'Laboratory' => 'bi-clipboard2-data',
                        default => 'bi-building',
                    };
                    $tags = $facility->tags->pluck('name')->take(5)->toArray();
                    $displayUrl = $facility->getMainImage();
                @endphp
                <div class="fl-card" data-category="{{ $facility->category_id }}" data-name="{{ strtolower($facility->name) }}" style="animation-delay: {{ $loop->index * .05 }}s">
                    <div class="fl-img">
                        @if($displayUrl)
                            <img src="{{ $displayUrl }}" alt="{{ $facility->name }}" loading="lazy">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="color:rgba(255,255,255,.4);">
                                <i class="bi bi-building fs-1"></i>
                            </div>
                        @endif
                        <span class="fl-cat"><i class="bi {{ $catIcon }}"></i> {{ $facility->category->name ?? 'Facility' }}</span>
                        <span class="fl-ratespan">&#8369;{{ number_format($facility->price_per_hour, 2) }} / hr</span>
                        <span class="fl-ratecap">
                            <span><i class="bi bi-people-fill"></i> Up to {{ number_format($facility->capacity) }}</span>
                        </span>
                    </div>
                    <div class="fl-body">
                        <h3 class="fl-name">{{ $facility->name }}</h3>
                        <div class="fl-loc"><i class="bi bi-geo-alt-fill"></i> {{ $facility->location }}</div>
                        <p class="fl-desc">{{ $facility->description }}</p>
                        <div class="fl-tags">
                            @foreach($tags as $tag)
                                <span class="fl-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="fl-footer">
                        <div class="fl-price">
                            <span class="fl-price-val">&#8369;{{ number_format($facility->price_per_hour, 2) }}</span>
                            <span class="fl-price-lbl">per hour &middot; capacity {{ number_format($facility->capacity) }}</span>
                        </div>
                        <div style="display:flex; gap:.55rem;">
                            <a href="{{ route('guest.reserve') }}?facility_id={{ $facility->id }}" class="fl-btn" data-no-pjax>
                                <i class="bi bi-calendar-check"></i> Reserve
                            </a>
                            <button type="button" class="fl-btn fl-btn-ghost" onclick="flOpenModal({{ $facility->id }})">
                                <i class="bi bi-eye"></i> Details
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="fl-empty">
                    <i class="bi bi-building-exclamation"></i>
                    <div class="fl-empty-t">No Facilities Found</div>
                    <p>We couldn't find any facilities at the moment. Please check back later.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

<!-- fl-section-modal -->
<div class="fl-modal-overlay" id="flModal" onclick="if(event.target===this)flCloseModal()">
    <div class="fl-modal">
        <div id="flModalContent"></div>
    </div>
</div>

<!-- fl-section-lightbox -->
<div class="fl-lb" id="flLightbox" onclick="if(event.target===this)flCloseLightbox()">
    <button type="button" class="fl-lb-close" onclick="flCloseLightbox()"><i class="bi bi-x-lg"></i></button>
    <button type="button" class="fl-lb-nav fl-lb-prev" onclick="flLbNav(-1);event.stopPropagation();"><i class="bi bi-chevron-left"></i></button>
    <img id="flLbImg" src="" alt="Facility photo">
    <button type="button" class="fl-lb-nav fl-lb-next" onclick="flLbNav(1);event.stopPropagation();"><i class="bi bi-chevron-right"></i></button>
    <div class="fl-lb-counter"><span id="flLbIdx">1</span> / <span id="flLbTotal">1</span></div>
</div>

<script>
(function () {
    var facilities = @json($facilities);

    /* ── Filter ───────────────────────────── */
    function flSetFilter(catId) {
        document.querySelectorAll('.fl-chip').forEach(function (c) {
            c.classList.toggle('active', String(c.dataset.filter) === String(catId));
        });
        document.querySelectorAll('.fl-card').forEach(function (card) {
            var show = catId === 'all' || String(card.dataset.category) === String(catId);
            card.style.display = show ? '' : 'none';
        });
        var noneVisible = Array.from(document.querySelectorAll('.fl-card')).every(function (c) { return c.style.display === 'none'; });
        if (noneVisible) {
            var empty = document.querySelector('.fl-empty');
            if (!empty) {
                empty = document.createElement('div');
                empty.className = 'fl-empty';
                empty.innerHTML = '<i class="bi bi-funnel"></i><div class="fl-empty-t">No Venues in This Category</div><p>Try selecting another filter or view all venues.</p>';
                document.getElementById('flGrid').appendChild(empty);
            }
            empty.style.display = '';
        } else {
            var empty = document.querySelector('.fl-empty');
            if (empty) empty.style.display = 'none';
        }
    }
    document.querySelectorAll('.fl-chip').forEach(function (chip) {
        chip.addEventListener('click', function () { flSetFilter(chip.dataset.filter); });
    });

    /* ── Density toggle ───────────────────── */
    var grid = document.getElementById('flGrid');
    document.querySelectorAll('.fl-viewtoggle button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.fl-viewtoggle button').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            grid.style.gridTemplateColumns = btn.dataset.density === 'compact'
                ? 'repeat(auto-fill, minmax(240px, 1fr))' : '';
        });
    });

    /* ── Modal ────────────────────────────── */
    function imgUrls(f) {
        var urls = [];
        if (f.featured_image && f.featured_image.url) urls.push(f.featured_image.url);
        (f.images || []).forEach(function (img) {
            if (img.url && urls.indexOf(img.url) === -1) urls.push(img.url);
        });
        return urls;
    }

    window.flOpenModal = function (id) {
        var f = facilities.find(function (x) { return x.id === id; });
        if (!f) return;
        var urls = imgUrls(f);
        var heroImg = urls.length
            ? '<img src="' + urls[0] + '" alt="' + (f.name || '').replace(/"/g, '&quot;') + '" onclick="flOpenLightbox(0, ' + id + ')">'
            : '<div class="w-100 h-100 bg-dark d-flex align-items-center justify-content-center" style="color:rgba(255,255,255,.35);"><i class="bi bi-building display-3"></i></div>';

        var amenities = (f.amenities || '').split(/\n/).map(function (a) { return a.trim(); }).filter(function (a) { return a; });
        var amenHtml = amenities.map(function (a) {
            return '<div class="fl-m-amen"><i class="bi bi-check-circle-fill"></i><span>' + a.replace(/</g,'&lt;') + '</span></div>';
        }).join('');

        var terms = (f.terms_conditions || '').replace(/</g,'&lt;');
        var contactName = (f.contact_person || '').replace(/</g,'&lt;');
        var contactPhone = (f.contact_phone || '').replace(/</g,'&lt;');
        var tagsHtml = (f.tags || []).map(function (t) {
            return '<span class="fl-m-tag"><i class="bi bi-patch-check-fill"></i>' + (t.name || t).replace(/</g,'&lt;') + '</span>';
        }).join('');

        var gallery = urls.length > 1
            ? '<div class="fl-m-section"><div class="fl-m-section-title">Photo Gallery</div>' +
              '<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(170px,1fr)); gap:.6rem;">' +
              urls.map(function (u, i) {
                  return '<div style="height:110px; border-radius:10px; overflow:hidden; border:1px solid #eef1f7; cursor:pointer;" onclick="flOpenLightbox(' + i + ',' + id + ')"><img src="' + u + '" alt="" style="width:100%;height:100%;object-fit:cover;"></div>';
              }).join('') +
              '</div></div>' : '';

        var html =
            '<div class="fl-m-hero">' + heroImg +
            '<button type="button" class="fl-m-close" onclick="flCloseModal()"><i class="bi bi-x-lg"></i></button>' +
            '<div class="fl-m-hero-title">' +
                '<span class="fl-m-hero-cat"><i class="bi bi-geo-alt-fill"></i> ' + ((f.category && f.category.name) || 'Facility') + '</span>' +
                '<h2>' + (f.name || '').replace(/</g,'&lt;') + '</h2>' +
                '<div class="fl-m-hero-loc"><i class="bi bi-geo-alt-fill"></i> ' + ((f.location || '').replace(/</g,'&lt;')) + '</div>' +
            '</div></div>' +
            '<div class="fl-m-body">' +
                '<div class="fl-m-section">' +
                    '<div class="fl-m-section-title">About This Venue</div>' +
                    '<p class="fl-m-desc">' + ((f.description || '').replace(/</g,'&lt;')) + '</p>' +
                '</div>' +
                '<div class="fl-m-kpis">' +
                    '<div class="fl-m-kpi"><div class="fl-m-kpi-lbl">Max Capacity</div><div class="fl-m-kpi-val">' + Number(f.capacity).toLocaleString() + ' people</div></div>' +
                    '<div class="fl-m-kpi"><div class="fl-m-kpi-lbl">Rental Rate</div><div class="fl-m-kpi-val">&#8369;' + parseFloat(f.price_per_hour).toLocaleString(undefined, {minimumFractionDigits:2}) + '<span style="font-size:.7rem;font-weight:600;color:#94a3b8;"> / hr</span></div></div>' +
                    '<div class="fl-m-kpi"><div class="fl-m-kpi-lbl">Contact</div><div class="fl-m-kpi-val" style="font-size:.95rem;">' + (contactName || '&mdash;') + '</div></div>' +
                '</div>' +
                (contactPhone ? '<div style="margin-top:.6rem; font-size:.82rem; color:#64748b;"><i class="bi bi-telephone-fill" style="color:var(--brand-primary,#2563eb);"></i> ' + contactPhone + '</div>' : '') +
                (tagsHtml ? '<div class="fl-m-section" style="margin-top:1rem;"><div class="fl-m-section-title">Highlights</div><div class="fl-m-tags">' + tagsHtml + '</div></div>' : '') +
                (amenities.length ? '<div class="fl-m-section"><div class="fl-m-section-title">Amenities &amp; Equipment</div><div class="fl-m-amenities">' + amenHtml + '</div></div>' : '') +
                gallery +
                (terms ? '<div class="fl-m-section"><div class="fl-m-section-title">House Rules</div><p class="fl-m-terms">' + terms + '</p></div>' : '') +
            '</div>' +
            '<div class="fl-m-footer">' +
                '<div class="fl-m-total">' +
                    '<span class="fl-m-total-val">&#8369;' + parseFloat(f.price_per_hour).toLocaleString(undefined, {minimumFractionDigits:2}) + '</span>' +
                    '<span class="fl-m-total-lbl">per hour</span>' +
                '</div>' +
                '<div style="display:flex; gap:.6rem;">' +
                    '<button type="button" class="fl-btn fl-btn-ghost" onclick="flCloseModal()">Close</button>' +
                    '<a href="{{ route('guest.reserve') }}?facility_id=' + f.id + '" class="fl-btn" data-no-pjax><i class="bi bi-calendar-check"></i> Book This Venue</a>' +
                '</div>' +
            '</div>';

        document.getElementById('flModalContent').innerHTML = html;
        var overlay = document.getElementById('flModal');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.flCloseModal = function () {
        var overlay = document.getElementById('flModal');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    };

    /* ── Lightbox ─────────────────────────── */
    var lbGallery = [], lbIdx = 0, lbFacId = null;

    window.flOpenLightbox = function (index, id) {
        var f = facilities.find(function (x) { return x.id === id; });
        if (!f) return;
        lbGallery = imgUrls(f);
        if (!lbGallery.length) return;
        lbIdx = index % lbGallery.length;
        lbFacId = id;
        flLbPaint();
        var lb = document.getElementById('flLightbox');
        lb.style.display = 'flex';
        requestAnimationFrame(function () { lb.classList.add('active'); });
        document.body.style.overflow = 'hidden';
    };
    window.flLbNav = function (d) {
        lbIdx = (lbIdx + d + lbGallery.length) % lbGallery.length;
        flLbPaint();
    };
    function flLbPaint() {
        document.getElementById('flLbImg').src = lbGallery[lbIdx];
        document.getElementById('flLbIdx').textContent = lbIdx + 1;
        document.getElementById('flLbTotal').textContent = lbGallery.length;
    }
    window.flCloseLightbox = function () {
        var lb = document.getElementById('flLightbox');
        lb.classList.remove('active');
        lb.style.display = 'none';
        if (!document.getElementById('flModal').classList.contains('active')) {
            document.body.style.overflow = '';
        }
    };

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { flCloseLightbox(); flCloseModal(); }
    });
})();
</script>
@endsection
