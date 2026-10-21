@extends('layouts.app')
@section('title', $title)
@section('content')

{{-- Page header band --}}
<div class="legal-page-header">
    <div class="legal-inner">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-4">
            <div class="d-flex align-items-center gap-3">
                <span class="legal-header-icon"><i class="bi bi-file-earmark-text-fill"></i></span>
                <div>
                    <div class="legal-header-eyebrow">{{ branding('system_name', 'Platform') }} &mdash; Legal</div>
                    <h1 class="legal-header-title mb-0">{{ $title }}</h1>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <span class="legal-updated"><i class="bi bi-clock"></i> Last updated: {{ \App\Services\LegalService::lastUpdated()->format('F j, Y') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="legal-body">
    <div class="legal-inner">
        <div class="row g-4">

            {{-- Table of contents (desktop) --}}
            @php
                $tocLangs = [];
                foreach ($entries as $i => $entry) {
                    $t = collect();
                    preg_match_all('/<h3[^>]*>\s*(?:([\d\.]+)\s*\.\s*)?(.*?)<\/h3>/s', $entry['html'], $matches, PREG_SET_ORDER);
                    foreach ($matches as $m) {
                        $prefix = isset($m[1]) && $m[1] !== '' ? trim($m[1]) . '-' : '';
                        $label = trim(strip_tags($m[2]));
                        $t->push([
                            'slug' => $prefix . \Str::slug(\Str::limit($label, 60, '')),
                            'label' => $label,
                        ]);
                    }
                    $tocLangs[$i] = $t->values();
                }
                $toc = $tocLangs[0] ?? collect();
            @endphp
            @if ($toc->isNotEmpty())
            <nav class="legal-toc-wrap col-12 col-lg-4 d-none d-lg-block" aria-label="Table of contents"
        @foreach ($tocLangs as $i => $t) data-toc-{{ $i }}='{!! json_encode($t->toArray()) !!}' @endforeach>
                <div class="legal-toc">
                    <div class="legal-toc-heading">On this page</div>
                    <ul class="legal-toc-list">
                        @foreach ($toc as $h)
                            <li><a href="#legal-{{ $h['slug'] }}">{{ $h['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </nav>
            @endif

            <div class="col-12 {{ $toc->isNotEmpty() ? 'col-lg-8' : '' }}">
                @if ($entries->isEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <div class="alert alert-info d-flex gap-3 border-0 mb-0" role="alert">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Coming soon</h5>
                                    <p class="mb-0">
                                        This document is being finalised by the administration and will be published
                                        here shortly. In the meantime, the short summary of our policies is shown in
                                        the registration agreement and on the landing page.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="legal-doc-card">
                        {{-- Language toggle (only when more than one language exists) --}}
                        @if ($entries->count() > 1)
                            <div class="legal-lang-bar">
                                <span class="legal-lang-label"><i class="bi bi-translate"></i> Language</span>
                                <div class="legal-lang-pills">
                                    @foreach ($entries as $i => $entry)
                                        <button class="legal-lang-pill {{ $i === 0 ? 'active' : '' }}"
                                                data-legal-lang="{{ $i }}"
                                                type="button">
                                            {{ $entry['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="legal-doc-tabs">
                            @foreach ($entries as $i => $entry)
                                @php
                                    $legalSlugHeadings = function ($tag, $m) {
                                        $label = trim(strip_tags($m[2]));
                                        $slug = \Str::slug(\Str::limit($label, 60, ''));
                                        return '<' . $tag . $m[1] . ' id="legal-' . $slug . '" class="legal-section">' . $m[2] . '</' . $tag . '>';
                                    };
                                    $langHtml = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/s', function ($m) use ($legalSlugHeadings) {
                                        return $legalSlugHeadings('h2', $m);
                                    }, $entry['html']);
                                    $langHtml = preg_replace_callback('/<h3([^>]*)>(.*?)<\/h3>/s', function ($m) use ($legalSlugHeadings) {
                                        return $legalSlugHeadings('h3', $m);
                                    }, $langHtml);
                                @endphp
                                <article class="legal-document {{ $i === 0 ? 'legal-document-active' : '' }}"
                                         data-legal-lang="{{ $i }}"
                                         role="tabpanel"
                                         @if ($i !== 0) style="display: none;" @endif>
                                    {!! $langHtml !!}
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="legal-page-contact">
                    <i class="bi bi-envelope-paper"></i>
                    <span>
                        Have questions about this policy? Reach out through the contact form on the
                        <a href="{{ route('home') }}#contact">home page</a>.
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* =====================================================================
   LEGAL PAGE STYLES
   =====================================================================
   Layout facts (from app.blade.php):
     main  { padding: 2rem 0 }          — top/bottom only, NO horizontal
     @768px: main { padding: 1.5rem 0 } — shrinks on mobile
     .container { padding: 1.5rem }     — provides horizontal gutters
     @768px: .container { padding: 1rem }

   The header sits directly inside <main>, which has ZERO horizontal
   padding. It is therefore naturally full-width — no horizontal negative
   margins are needed or wanted. Only the top-padding needs cancelling.
   ===================================================================== */

/* ── Full-bleed header: cancel main's top padding only ───────────── */
.legal-page-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-bottom: 3px solid var(--brand-primary, #2563eb);
    color: #fff;
    margin-top: -2rem;   /* exact inverse of main { padding-top: 2rem } */
}
@media (max-width: 768px) {
    /* Matches app.blade.php: main { padding: 1.5rem 0 } at ≤768px */
    .legal-page-header { margin-top: -1.5rem; }
}

/* ── Centered content wrapper ─────────────────────────────────────── */
.legal-inner {
    width: 100%;
    max-width: 1080px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    box-sizing: border-box;
}
@media (max-width: 768px) {
    .legal-inner { padding-left: 1rem; padding-right: 1rem; }
}
@media (max-width: 480px) {
    .legal-inner { padding-left: .75rem; padding-right: .75rem; }
}

/* ── Body area ────────────────────────────────────────────────────── */
.legal-body { padding-top: 2.5rem; padding-bottom: 4rem; }
@media (max-width: 992px) { .legal-body { padding-top: 2rem;   padding-bottom: 3rem; } }
@media (max-width: 768px) { .legal-body { padding-top: 1.5rem; padding-bottom: 2.5rem; } }
@media (max-width: 480px) { .legal-body { padding-top: 1.25rem; padding-bottom: 2rem; } }

/* ── Header elements ──────────────────────────────────────────────── */
.legal-header-icon {
    width: 46px; height: 46px; border-radius: 12px;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
@media (max-width: 480px) {
    .legal-header-icon { width: 36px; height: 36px; font-size: 1rem; }
}
.legal-header-eyebrow {
    font-size: .8rem; letter-spacing: .08em; text-transform: uppercase;
    color: rgba(255,255,255,.55);
}
.legal-header-title {
    font-size: clamp(1.15rem, 3vw, 1.75rem);
    font-weight: 800; letter-spacing: -.01em;
    word-break: break-word;
}
.legal-updated { color: rgba(255,255,255,.45); font-size: .8rem; }
@media (max-width: 480px) {
    .legal-header-eyebrow { display: none; }
    .legal-updated { font-size: .72rem; }
}

/* ── Table of contents ────────────────────────────────────────────── */
.legal-toc {
    position: sticky; top: 1.5rem;
    background: #fff; border: 1px solid rgba(15,23,42,.08); border-radius: 12px;
    padding: 1.25rem; box-shadow: 0 1px 3px rgba(15,23,42,.05);
    max-height: calc(100vh - 3rem); overflow-y: auto;
}
.legal-toc-heading {
    font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: var(--brand-primary, #2563eb); margin-bottom: .75rem;
}
.legal-toc-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: .25rem; }
.legal-toc-list a {
    color: #475569 !important; text-decoration: none !important;
    font-size: .875rem; line-height: 1.45; font-weight: 500;
    display: block; padding: .4rem .75rem; border-radius: 6px;
    border-left: 3px solid transparent; transition: all .15s ease;
}
.legal-toc-list a:hover, .legal-toc-list a:active {
    background: #f1f5f9; color: #2563eb !important; border-left-color: #2563eb;
}

/* ── Document card ────────────────────────────────────────────────── */
.legal-doc-card {
    background: #fff; border-radius: 14px; border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 2px 8px rgba(15,23,42,.06); overflow: hidden;
}

/* ── Language bar ─────────────────────────────────────────────────── */
.legal-lang-bar {
    display: flex; align-items: center; justify-content: space-between; gap: .75rem;
    background: #f8fafc; border-bottom: 1px solid rgba(15,23,42,.08);
    padding: .7rem 1.75rem; flex-wrap: wrap;
}
@media (max-width: 480px) {
    .legal-lang-bar { flex-direction: column; align-items: flex-start; padding: .75rem 1rem; gap: .5rem; }
}
.legal-lang-label  { font-size: .8rem; font-weight: 600; color: #64748b; white-space: nowrap; }
.legal-lang-pills  { display: flex; flex-wrap: wrap; gap: .4rem; }
.legal-lang-pill {
    border: 1px solid #cbd5e1 !important; background: #fff !important; color: #334155 !important;
    font-size: .82rem; font-weight: 600; padding: .3rem .95rem; border-radius: 999px;
    cursor: pointer; transition: all .15s ease; box-shadow: none !important;
}
button.legal-lang-pill:hover  { border-color: #2563eb !important; color: #2563eb !important; }
button.legal-lang-pill.active {
    background: #2563eb !important; border-color: #2563eb !important; color: #fff !important;
}

/* ── Document typography ──────────────────────────────────────────── */
.legal-document {
    padding: clamp(.9rem, 3vw, 2.25rem);
    font-size: clamp(.875rem, 1.8vw, 1rem);
    line-height: 1.8; color: #1e293b;
    word-break: break-word; overflow-wrap: break-word;
}
.legal-document h2 {
    font-size: clamp(1.1rem, 2.5vw, 1.45rem); font-weight: 800; color: #0f172a;
    padding-bottom: .85rem; margin-bottom: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
}
.legal-document h3 {
    font-size: clamp(.95rem, 2vw, 1.15rem); font-weight: 700; color: #0f172a;
    margin-top: clamp(1.25rem, 3vw, 2.25rem); margin-bottom: .75rem;
    padding-left: .9rem; border-left: 3px solid var(--brand-primary, #2563eb);
    line-height: 1.4;
}
.legal-section  { scroll-margin-top: 190px; }
.legal-document p  { margin-bottom: 1.1rem; }
.legal-document ul, .legal-document ol { margin-bottom: 1.1rem; padding-left: 1.6rem; }
.legal-document li { margin-bottom: .45rem; }
.legal-document li::marker { color: var(--brand-primary, #2563eb); }
.legal-document strong { color: #0f172a; }
.legal-document a  { color: var(--brand-primary, #2563eb); text-decoration: underline; }

/* ── Contact bar ──────────────────────────────────────────────────── */
.legal-page-contact {
    margin-top: 1.75rem; display: flex; align-items: flex-start; gap: .6rem;
    color: #64748b; font-size: .9rem; flex-wrap: wrap;
}
.legal-page-contact i { color: var(--brand-primary, #2563eb); font-size: 1.05rem; flex-shrink: 0; margin-top: .1rem; }
.legal-page-contact a { color: var(--brand-primary, #2563eb); text-decoration: none; font-weight: 600; }
.legal-page-contact a:hover { text-decoration: underline; }
</style>
@endpush

@push('scripts')
<script>
// Language tab switching for legal documents (syncs TOC labels, persists choice)
(function () {
    var bar = document.querySelector('.legal-lang-bar');
    var card = document.querySelector('.legal-doc-card');
    var nav = document.querySelector('.legal-toc-wrap');
    if (!bar || !card) return;
    function legalBuildToc(idx) {
        if (!nav) return;
        var raw = nav.getAttribute('data-toc-' + idx);
        var list = document.querySelector('.legal-toc-list');
        if (raw && list) {
            var items = JSON.parse(raw);
            list.innerHTML = items.map(function (h) {
                return '<li><a href="#legal-' + h.slug + '">' + h.label + '</a></li>';
            }).join('');
        }
    }
    function legalSwitchLang(idx, persist) {
        if (typeof persist === 'undefined') persist = true;
        // pills
        bar.querySelectorAll('.legal-lang-pill').forEach(function (b) {
            b.classList.toggle('active', b.getAttribute('data-legal-lang') === idx);
        });
        // articles
        card.querySelectorAll('.legal-document').forEach(function (art) {
            art.style.display = art.getAttribute('data-legal-lang') === idx ? '' : 'none';
        });
        legalBuildToc(idx);
        if (persist && window.sessionStorage) {
            try { window.sessionStorage.removeItem('legal-lang');
            window.sessionStorage.setItem('legal-lang-{{ $doc }}', String(idx)); } catch (e) {}
        }
    }
    // find a section target by id (works for either language article)
    function legalFindTarget(id) {
        if (!id) return null;
        var el = document.getElementById(id);
        if (el && el.classList && el.classList.contains('legal-section')) return el;
        // fallback: legacy/legacy-hash ids without the numeric prefix
        var suff = document.querySelector('[id$="-' + id + '"].legal-section');
        if (suff) return suff;
        return null;
    }
    // switch language so that the target section becomes visible, then scroll to it
    function legalGoToTarget(id, smooth) {
        var target = legalFindTarget(id);
        if (!target) return false;
        var art = target.closest('.legal-document');
        if (art) {
            legalSwitchLang(art.getAttribute('data-legal-lang'));
        }
        function legalApplyScroll(targetOffset, smooth) {
            window.scrollTo({ top: targetOffset, behavior: smooth ? 'smooth' : 'instant' });
            var moved = false;
            setTimeout(function () {
                if (Math.abs(window.pageYOffset - targetOffset) > 4) {
                    var root = document.scrollingElement || document.documentElement;
                    root.scrollTop = targetOffset;
                    window.scrollTo(0, targetOffset);
                    moved = true;
                }
            }, smooth ? 700 : 120);
            return moved;
        }
        function legalScrollToTarget() {
            var rect = target.getBoundingClientRect();
            var offset = rect.top + window.pageYOffset - 196;
            legalApplyScroll(offset, !!smooth);
        }
        setTimeout(legalScrollToTarget, 100);
        setTimeout(legalScrollToTarget, 600);
        return true;
    }
    function legalRestoreFromHash() {
        var hash = decodeURIComponent(window.location.hash || '');
        if (hash && hash.length > 1) {
            if (legalGoToTarget(hash.substring(1), false)) return true;
        }
        return false;
    }
    if (!legalRestoreFromHash()) {
        var retries = 0;
        var waitRestore = setInterval(function () {
            if (legalRestoreFromHash() || ++retries > 8) { clearInterval(waitRestore); }
        }, 150);
        if (window.sessionStorage) {
            try {
                var saved = window.sessionStorage.getItem('legal-lang-{{ $doc }}');
                if (saved !== null && saved !== '0') { legalSwitchLang(saved, false); }
            } catch (e) {}
        }
    }
    window.addEventListener('hashchange', function () { legalRestoreFromHash(); });
    document.addEventListener('click', function (e) {
        var link = e.target.closest('.legal-toc-list a[href^="#legal-"]');
        if (!link) return;
        e.preventDefault();
        var id = decodeURIComponent(link.getAttribute('href').substring(1));
        legalGoToTarget(id, true);
        try { history.replaceState(null, '', link.getAttribute('href')); } catch (err) {}
    });
    document.querySelectorAll('.legal-lang-pill').forEach(function (btn) {
        btn.addEventListener('click', function () {
            legalSwitchLang(btn.getAttribute('data-legal-lang'));
        });
    });
    // expose for accessibility toolbar / future extensions
    window.legalSwitchLang = legalSwitchLang;
})();
</script>
@endpush
