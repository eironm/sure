// resources/js/hash-nav.js
// ---------------------------------------------------------------------------
// Shared hash navigation: makes footer "Browse" links (Home / About Us /
// FAQ) smooth-scroll to homepage sections instead of reloading the page,
// whether the visitor is already on the homepage or arrives from elsewhere.
// ---------------------------------------------------------------------------
function scrollSection(id) {
    const target = document.getElementById(id);
    if (!target) return false;
    requestAnimationFrame(function () {
        target.scrollIntoView({
            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches
                ? 'auto' : 'smooth',
            block: 'start',
        });
    });
    return true;
}

document.addEventListener('click', function (e) {
    const link = e.target.closest('a[href]');
    if (!link) return;
    const href = link.getAttribute('href') || '';
    const hashIdx = href.indexOf('#');
    if (hashIdx < 0 || href.length - hashIdx < 2) return; // no hash anchor
    const hash = href.slice(hashIdx + 1);
    // Only handle homepage section anchors.
    if (!['top', 'about', 'features', 'gallery', 'faq', 'contact'].includes(hash)) return;

    // Already on the homepage: scroll in place, no reload.
    if (document.getElementById('top')) {
        if (scrollSection(hash)) e.preventDefault();
        return;
    }
});

// If the page loads with a homepage section hash in the URL, scroll to it.
(function () {
    if (!document.getElementById('top')) return;
    if (location.hash && location.hash.length > 1) {
        const hash = location.hash.slice(1);
        if (['top', 'about', 'features', 'gallery', 'faq', 'contact'].includes(hash)) {
            scrollSection(hash);
        }
    }
})();
