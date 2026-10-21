/**
 * SURE — Homepage (new design) JS
 * Handles: hero video autoplay retry, nav scroll state + logo swap,
 * gallery zoom modal (panzoom), scroll-in fallbacks.
 * FAQ accordion lives in the blade's inline script.
 */
(function () {
    // ------------------------------------------------------------------
    // Hero video: iOS Low Power Mode recovery
    // autoplay is blocked by iOS when Low Power Mode is on. On the first
    // user touch/click, attempt to resume. The inline script in
    // home-page.blade.php handles the playing/error state display.
    // ------------------------------------------------------------------
    function initHeroVideo() {
        var video = document.querySelector('[data-hero-video]');
        if (!video) return;
        video.muted = true;
        video.defaultMuted = true;
        video.playsInline = true;
        if (!video.paused) return;
        var attempt = video.play();
        if (!attempt || typeof attempt.catch !== 'function') return;
        attempt.catch(function () {
            var retry = function () {
                video.muted = true;
                video.play().catch(function () {});
            };
            ['touchstart', 'click', 'pointerdown', 'scroll'].forEach(function (evt) {
                document.addEventListener(evt, retry, { once: true, passive: true });
            });
        });
    }
    try { initHeroVideo(); } catch (e) { /* autoplay unavailable in this context */ }

    // ------------------------------------------------------------------
    // Nav: scroll state + logo swap
    // ------------------------------------------------------------------
    var nav = document.getElementById('hnNav');
    var logo = nav ? nav.querySelector('.hn-logo') : null;

    function updateNavState() {
        if (!nav) return;
        var scrolled = window.scrollY > 50;
        nav.classList.toggle('scrolled', scrolled);
        // When the CMS auto light/dark logo mode is enabled, the
        // background-luminance script owns the src — never override it.
        if (logo && logo.dataset.logoDark && logo.dataset.logoLight && !logo.classList.contains('brand-logo-auto')) {
            logo.src = scrolled ? logo.dataset.logoDark : logo.dataset.logoLight;
        }
    }

    // Burger icon sync (drawer is owned by mobile-nav.js via data-drawer-toggle)
    var burger = document.getElementById('hnBurger');
    if (burger) {
        var applyBurger = function () {
            burger.classList.toggle('is-open', burger.getAttribute('aria-expanded') === 'true');
            updateNavState();
        };
        new MutationObserver(applyBurger).observe(burger, { attributes: true, attributeFilter: ['aria-expanded'] });
    }

    // ------------------------------------------------------------------
    // Gallery zoom modal (panzoom, loaded via CDN defer)
    // ------------------------------------------------------------------
    var modal = document.getElementById('hnZoomModal');
    var modalImg = document.getElementById('hnZoomImg');
    var closeBtn = document.getElementById('hnZoomClose');
    var viewport = document.querySelector('.hn-zoom-viewport');
    var panzoom = null;

    function initPanzoom() {
        if (panzoom || !modalImg || typeof Panzoom === 'undefined') return;
        panzoom = Panzoom(modalImg, { maxScale: 5, minScale: 1, contain: 'none', startScale: 1 });
        if (viewport) viewport.addEventListener('wheel', panzoom.zoomWithWheel, { passive: false });
    }

    function openZoomModal(imgUrl) {
        if (!modal || !modalImg) return;
        initPanzoom();
        modalImg.src = imgUrl;
        if (panzoom) panzoom.reset({ animate: false });
        modal.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeZoomModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeZoomModal);
    if (modal) {
        modal.addEventListener('click', function (e) { if (e.target === modal) closeZoomModal(); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) closeZoomModal();
        });
    }

    document.querySelectorAll('.hn-gallery-item').forEach(function (item) {
        item.addEventListener('click', function () {
            var bg = getComputedStyle(item).backgroundImage;
            var url = bg.replace(/^url\(['"]?/, '').replace(/['"]?\)$/, '');
            if (url && url !== 'none') openZoomModal(url);
        });
    });

    // ------------------------------------------------------------------
    // Init + RAF-throttled scroll
    // ------------------------------------------------------------------
    var rafPending = false;
    function onScroll() {
        if (rafPending) return;
        rafPending = true;
        requestAnimationFrame(function () {
            updateNavState();
            rafPending = false;
        });
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('load', function () { updateNavState(); });
    updateNavState();
    // ------------------------------------------------------------------
    // Nav links: smooth-scroll to sections (no page reload)
    // ------------------------------------------------------------------
    function scrollToSection(target, { replaceHash = true } = {}) {
        if (!target) return;
        requestAnimationFrame(function () {
            target.scrollIntoView({
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches
                    ? 'auto' : 'smooth',
                block: 'start',
            });
        });
        if (replaceHash && target.id) {
            history.replaceState(null, '', '#' + target.id);
        }
    }
    function handleHashLink(link) {
        const href = link.getAttribute('href') || '';
        if (!href.startsWith('#') || href.length < 2) return false;
        const target = document.getElementById(href.slice(1));
        if (!target) return false;
        scrollToSection(target);
        return true;
    }
    // Top navigation links (delegated so it always fires).
    document.addEventListener('click', function (e) {
        var link = e.target.closest('.hn-links a[href^="#"]');
        if (link && handleHashLink(link)) e.preventDefault();
    });
    // Mobile drawer links (delegated; mobile-nav.js handles the open-drawer
    // case, this covers clicks when the drawer isn't open).
    document.addEventListener('click', function (e) {
        var link = e.target.closest('.mobile-drawer-link[href^="#"]');
        if (link && handleHashLink(link)) e.preventDefault();
    });
    // If the page loads with a hash in the URL, scroll to the target
    // instead of sitting at the top of the page.
    (function () {
        if (location.hash && location.hash.length > 1) {
            const target = document.getElementById(location.hash.slice(1));
            if (target) scrollToSection(target, { replaceHash: false });
        }
    })();


    // Brand logo: click scrolls back to the top (no page reload).
    var brand = document.querySelector('.hn-brand');
    if (brand && brand.getAttribute('href') === '#top') {
        brand.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches
                    ? 'auto' : 'smooth',
            });
            history.replaceState(null, '', location.pathname);
        });
    }
})();


