@php
    $brandName = branding('system_name', 'Sure') ?: 'Sure';
    $tagline = branding('system_tagline', 'Smart University Reservation Engine');
    $logo = \App\Services\BrandingService::logoForBg('dark');
    $wordmarkOnly = filter_var(branding('use_wordmark_only', '0'), FILTER_VALIDATE_BOOLEAN);
    $primary = branding('brand_primary', '#ff7a18') ?: '#ff7a18';
    $secondary = branding('brand_secondary', '#7c3aed') ?: '#7c3aed';
    $supportEmail = branding('contact_email', '');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d1728">
    <title>@yield('title', 'Something went wrong') · {{ $brandName }}</title>
    <style>
        :root { --error-primary: {{ $primary }}; --error-secondary: {{ $secondary }}; --error-bg: #0d1728; --error-ink: #f8fbff; --error-muted: #b8c5d8; --error-subtle: #8e9db4; --error-line: rgba(255,255,255,.13); }
        *, *::before, *::after { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body { margin: 0; min-height: 100vh; color: var(--error-ink); background: var(--error-bg); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; -webkit-font-smoothing: antialiased; }
        a { color: inherit; }
        .error-page { position: relative; isolation: isolate; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 42px 22px; overflow: hidden; background: var(--error-bg); }
        .error-page::before, .error-page::after { content: ""; position: absolute; z-index: -1; width: 420px; aspect-ratio: 1; border: 1px solid rgba(255,255,255,.06); border-radius: 50%; pointer-events: none; }
        .error-page::before { top: -290px; right: -160px; box-shadow: 0 0 0 24px rgba(255,255,255,.018), 0 0 0 48px rgba(255,255,255,.014); }
        .error-page::after { bottom: -310px; left: -170px; }
        .error-shell { width: min(1080px, 100%); }
        .error-brand { display:flex; align-items:center; justify-content:space-between; gap:24px; margin:0 0 18px; padding:0 4px; } .brand-lockup { display:inline-flex; align-items:center; text-decoration:none; } .brand-lockup img { display:block; width:auto; height:auto; max-width:190px; max-height:54px; object-fit:contain; } .brand-wordmark { display:none; color:#fff; font-size:.82rem; font-weight:850; letter-spacing:.18em; text-transform:uppercase; } .wordmark-only .brand-lockup img { display:none; } .wordmark-only .brand-wordmark { display:inline; } .brand-tagline { max-width:42%; color:var(--error-subtle); font-size:.82rem; text-align:right; }
        .error-content { position:relative; overflow:hidden; min-height:540px; padding:clamp(34px,6vw,70px); border:1px solid var(--error-line); border-radius:30px; background:linear-gradient(135deg,rgba(25,39,61,.98),rgba(15,25,42,.98)); box-shadow:0 30px 90px rgba(0,0,0,.4); } .error-content::before { content:""; position:absolute; width:520px; height:520px; top:50%; right:-240px; transform:translateY(-50%); border:1px solid rgba(255,255,255,.075); border-radius:50%; box-shadow:0 0 0 26px rgba(255,255,255,.018),0 0 0 52px rgba(255,255,255,.012); pointer-events:none; } .error-content > * { position:relative; z-index:1; } .error-content .error-header { display:none; }
        .error-icon { width:62px; height:62px; margin:0 0 24px; display:grid; place-items:center; border:1px solid color-mix(in srgb,var(--error-primary) 68%,transparent); border-radius:18px; color:var(--error-primary); background:color-mix(in srgb,var(--error-primary) 14%,transparent); font-size:1.55rem; } .error-code { margin:0; color:#fff; font-size:clamp(5.2rem,13vw,10rem); font-weight:950; line-height:.78; letter-spacing:-.1em; text-shadow:0 14px 42px rgba(0,0,0,.3); } .error-title { max-width:650px; margin:24px 0 0; color:#fff; font-size:clamp(2rem,4.2vw,4.1rem); font-weight:850; line-height:1.04; letter-spacing:-.055em; } .error-description { max-width:560px; margin:18px 0 0; color:var(--error-muted); font-size:1rem; line-height:1.75; }
        .error-actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:32px; } .error-actions a { display:inline-flex; align-items:center; justify-content:center; gap:9px; min-height:48px; padding:0 20px; border-radius:12px; font-weight:800; text-decoration:none; transition:transform .2s ease,filter .2s ease; } .error-actions .btn-primary { color:#fff; background:linear-gradient(115deg,var(--error-primary),var(--error-secondary)); } .error-actions .btn-secondary { color:#eef4ff; border:1px solid var(--error-line); background:rgba(255,255,255,.055); } .error-actions a:hover { color:#fff; filter:brightness(1.08); transform:translateY(-2px); }
        .error-details { display:flex; flex-wrap:wrap; gap:12px 24px; margin-top:38px; padding-top:20px; border-top:1px solid var(--error-line); color:var(--error-subtle); font-size:.78rem; } .error-detail-item { display:inline-flex; align-items:center; gap:8px; } .error-detail-item i { color:var(--error-primary); } .error-content > .error-footer { margin-top:30px; color:var(--error-subtle); font-size:.8rem; line-height:1.6; } .error-content > .error-footer p { margin:0; } .error-content > .error-footer a,.site-footer a { color:#ffd2ad; font-weight:750; text-decoration:none; } .site-footer { display:flex; justify-content:space-between; gap:20px; padding:18px 4px 0; color:var(--error-subtle); font-size:.78rem; } .site-footer p { margin:0; }
        @media (max-width:700px) { .error-page { padding:24px 14px; align-items:flex-start; } .error-brand { align-items:flex-start; flex-direction:column; gap:10px; } .brand-tagline { max-width:100%; text-align:left; } .error-content { min-height:0; padding:32px 24px; border-radius:22px; } .error-title { font-size:clamp(2rem,11vw,3.2rem); } .error-actions a { flex:1 1 160px; } .site-footer { flex-direction:column; gap:8px; } }
    </style>
</head>
<body class="{{ $wordmarkOnly ? 'wordmark-only' : '' }}">
    <div class="error-page"><div class="error-shell">
        <header class="error-brand"><a class="brand-lockup" href="{{ url('/') }}" aria-label="{{ $brandName }} home"><img src="{{ $logo }}" alt="{{ $brandName }} logo"><span class="brand-wordmark">{{ $brandName }}</span></a><span class="brand-tagline">{{ $tagline }}</span></header>
        <main class="error-content">@yield('content')</main>
        <footer class="site-footer"><p>{{ $brandName }} · {{ $tagline ?: 'A smarter way to reserve campus spaces.' }}</p><p>@if($supportEmail)<a href="mailto:{{ $supportEmail }}">Contact support</a>@else<a href="{{ url('/') }}">Return home</a>@endif</p></footer>
    </div></div>
</body>
</html>
