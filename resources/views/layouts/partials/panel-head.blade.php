{{-- Shared <head> assets for the staff-side panels. --}}
@include('layouts.partials.clarity')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="{{ branding('brand_dark', '#0f172a') }}">
<link rel="icon" type="{{ brand_favicon_mimetype() }}" href="{{ brand_favicon() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@vite(['resources/css/panel2.css', 'resources/css/app.css', 'resources/css/mobile.css'])
<script>
// Cache-busting guarantee: re-link every local panel stylesheet with a fresh
// fingerprint query string so a stale browser cache can never serve the old
// CSS (which broke the flex column layout and hid the live preview).
(function () {
  try {
    var ts = Date.now();
    var styles = document.querySelectorAll('link[rel="stylesheet"][href]');
    styles.forEach(function (link) {
      var href = link.getAttribute('href') || '';
      if (link.dataset.__cbc === '1') return; // already repointed this lifetime
      if (href.indexOf('/build/assets/') === -1) return; // skip CDNs/fonts
      var clean = href.split('?')[0];
      link.setAttribute('href', clean + '?v=' + ts);
      link.dataset.__cbc = '1';
    });
  } catch (e) {}
})();
</script>
<script>
    // Run panel page initializers on full loads and PJAX-compatible swaps.
    window.onPageLoad = window.onPageLoad || function (cb) {
        if (typeof cb !== 'function') return;
        const run = () => {
            try { cb(); } catch (error) { console.error('Panel initializer failed', error); }
        };
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', run, { once: true });
        } else {
            setTimeout(run, 0);
        }
    };
</script>
@include('layouts.partials.panel-theme')
