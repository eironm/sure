@extends('layouts.app')
@section('title', 'Cookie Notice')
@section('content')

{{-- Page header band (same style as legal/page) --}}
<div class="legal-page-header">
    <div class="container" style="max-width: 1080px;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-4">
            <div class="d-flex align-items-center gap-3">
                <span class="legal-header-icon"><i class="bi bi-cookie"></i></span>
                <div>
                    <div class="legal-header-eyebrow">{{ branding('system_name', 'Platform') }} &mdash; Legal</div>
                    <h1 class="legal-header-title mb-0">Cookie Notice</h1>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <span class="legal-updated"><i class="bi bi-clock"></i> Last updated: {{ \App\Services\LegalService::lastUpdated()->format('F j, Y') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="container legal-page-body" style="max-width: 1080px;">
    <div class="row g-4">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="legal-doc-card">
                <div class="legal-document">
                    <h2>What cookies we use</h2>
                    <p>
                        This website uses only the cookies that are strictly necessary for it to work.
                        We do not use advertising, tracking, or analytics cookies.
                    </p>

                    <h3>Cookies we use</h3>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0 legal-cookies-table">
                            <thead>
                                <tr>
                                    <th style="width: 26%;">Name</th>
                                    <th style="width: 36%;">Purpose</th>
                                    <th style="width: 18%;">Lifetime</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>sure_session</code></td>
                                    <td>Keeps you signed in and maintains your session</td>
                                    <td>Session / 2 hours</td>
                                    <td><span class="legal-badge legal-badge-safe">Strictly necessary</span></td>
                                </tr>
                                <tr>
                                    <td><code>XSRF-TOKEN</code></td>
                                    <td>Protects forms from cross-site request forgery</td>
                                    <td>Session</td>
                                    <td><span class="legal-badge legal-badge-safe">Strictly necessary</span></td>
                                </tr>
                                <tr>
                                    <td><code>sure_accessibility_*</code></td>
                                    <td>Remembers your accessibility preferences (contrast, font size, etc.)</td>
                                    <td>1 year</td>
                                    <td><span class="legal-badge legal-badge-functional">Functional</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3>How to control cookies</h3>
                    <p>
                        You can block or delete these cookies at any time through your browser's privacy
                        settings. Blocking necessary cookies may prevent you from signing in or using the
                        reservation system.
                    </p>
                </div>
            </div>

            <div class="legal-page-contact">
                <i class="bi bi-envelope-paper"></i>
                <span>
                    Questions? Use the contact form on the
                    <a href="{{ route('home') }}#contact">home page</a>.
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ---- Page header band (mirrors legal/page) ---- */
.legal-page-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-bottom: 3px solid var(--brand-primary, #2563eb);
    color: #fff;
}
.legal-header-icon {
    width: 46px; height: 46px; border-radius: 12px;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.legal-header-eyebrow { font-size: .8rem; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.55); }
.legal-header-title { font-size: 1.75rem; font-weight: 800; letter-spacing: -.01em; }
.legal-back-link { color: rgba(255,255,255,.75); text-decoration: none; font-size: .9rem; font-weight: 500; }
.legal-updated { color: rgba(255,255,255,.45); font-size: .8rem; }
.legal-page-body { padding-top: 2.5rem; padding-bottom: 4rem; }

/* ---- Document card ---- */
.legal-doc-card {
    background: #fff; border-radius: 14px; border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 2px 8px rgba(15,23,42,.06); overflow: hidden;
}
.legal-document { font-size: 1rem; line-height: 1.8; color: #1e293b; padding: 2.25rem; }
@media (max-width: 576px) { .legal-document { padding: 1.25rem; } }
.legal-document h2 {
    font-size: 1.45rem; font-weight: 800; color: #0f172a;
    padding-bottom: .85rem; margin-bottom: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
}
.legal-document h3 {
    font-size: 1.15rem; font-weight: 700; color: #0f172a;
    margin-top: 2.25rem; margin-bottom: .75rem;
    padding-left: .9rem; border-left: 3px solid var(--brand-primary, #2563eb);
    line-height: 1.4;
}
.legal-document p { margin-bottom: 1.1rem; }
.legal-document code { color: #7c3aed; background: #f1f5f9; padding: .15rem .4rem; border-radius: 5px; font-size: .9em; }

/* ---- Cookies table ---- */
.legal-cookies-table th {
    font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
    color: #64748b; border-bottom: 2px solid #e2e8f0; padding: .85rem 1rem;
}
.legal-cookies-table td { border-top: 1px solid #f1f5f9; padding: 1rem; color: #334155; }
.legal-cookies-table tr:first-child td { border-top: none; }

/* ---- Badges ---- */
.legal-badge {
    display: inline-block; font-size: .78rem; font-weight: 600;
    padding: .28rem .7rem; border-radius: 999px; white-space: nowrap;
}
.legal-badge-safe { background: #dcfce7; color: #166534; }
.legal-badge-functional { background: #dbeafe; color: #1e40af; }

/* ---- Contact bar ---- */
.legal-page-contact {
    margin-top: 1.75rem; display: flex; align-items: center; gap: .6rem;
    color: #64748b; font-size: .9rem;
}
.legal-page-contact i { color: var(--brand-primary, #2563eb); font-size: 1.05rem; }
.legal-page-contact a { color: var(--brand-primary, #2563eb); text-decoration: none; font-weight: 600; }
.legal-page-contact a:hover { text-decoration: underline; }
</style>
@endpush
