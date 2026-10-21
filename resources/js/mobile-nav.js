/**
 * Mobile drawer engine
 * --------------------
 * Drives every off-canvas panel in the app:
 *
 *   - the guest navigation drawer (#mobileDrawer), and
 *   - the staff-panel sidebar (.sidebar) on admin, superadmin, staff
 *     and cashier, which at <=1024px is translated off-screen by
 *     panel-theme.blade.php and previously had no way to be opened at
 *     all, leaving those roles unable to navigate on a phone.
 *
 * Markup contract:
 *   <button data-drawer-toggle="#mobileDrawer">      opens / closes
 *   <button data-drawer-close>                       closes (inside panel)
 *   <div id="mobileDrawer" data-drawer>              the panel
 *   <div data-drawer-backdrop="#mobileDrawer">       optional scrim
 *
 * Everything is bound through delegation on `document`, because both the
 * PJAX engine and the sidebar hot-swap replace nav regions wholesale on
 * navigation. Listeners bound directly to a trigger would be discarded.
 */

(function () {
    if (window.__mobileNavBooted) return;
    window.__mobileNavBooted = true;

    const FOCUSABLE = [
        'a[href]',
        'button:not([disabled])',
        'input:not([disabled])',
        'select:not([disabled])',
        'textarea:not([disabled])',
        '[tabindex]:not([tabindex="-1"])',
    ].join(',');

    // Currently open panel, or null. Only one may be open at a time.
    let openPanel = null;
    let scrollY = 0;
    let lastTrigger = null;

    /* ── Scroll lock ──────────────────────────────────────────── */
    function lockScroll() {
        scrollY = window.scrollY || window.pageYOffset || 0;
        // Pinning the body is the only approach that reliably stops the
        // page behind the drawer from scrolling in mobile Safari.
        document.body.style.top = `-${scrollY}px`;
        document.body.classList.add('mobile-scroll-locked', 'mobile-drawer-open');
    }

    // restore=false is used when the drawer is closing because the user
    // picked an in-page anchor: putting them back where they started would
    // immediately undo the jump they just asked for.
    function unlockScroll(restore = true) {
        document.body.classList.remove('mobile-scroll-locked', 'mobile-drawer-open');
        document.body.style.top = '';
        if (restore) window.scrollTo(0, scrollY);
    }

    /* ── Resolve pieces ───────────────────────────────────────── */
    function backdropFor(panel) {
        if (!panel) return null;
        // Prefer a scrim explicitly bound to this panel, else any generic one.
        return document.querySelector(`[data-drawer-backdrop="#${panel.id}"]`)
            || document.querySelector('[data-drawer-backdrop]:not([data-drawer-backdrop*="#"])');
    }

    function togglesFor(panel) {
        if (!panel) return [];
        return Array.from(document.querySelectorAll(`[data-drawer-toggle="#${panel.id}"]`));
    }

    /* ── Open / close ─────────────────────────────────────────── */
    function open(panel, trigger) {
        if (!panel || openPanel === panel) return;
        if (openPanel) close();

        openPanel = panel;
        lastTrigger = trigger || null;

        panel.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');

        const bd = backdropFor(panel);
        if (bd) {
            bd.classList.add('is-open');
            bd.setAttribute('aria-hidden', 'false');
        }

        togglesFor(panel).forEach((t) => t.setAttribute('aria-expanded', 'true'));

        lockScroll();

        // Land focus inside the dialog rather than behind it.
        const first = panel.querySelector('[data-drawer-close]') || panel.querySelector(FOCUSABLE);
        if (first) requestAnimationFrame(() => first.focus({ preventScroll: true }));
    }

    function close({ restoreScroll = true } = {}) {
        const panel = openPanel;
        if (!panel) return;

        openPanel = null;

        panel.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');

        const bd = backdropFor(panel);
        if (bd) {
            bd.classList.remove('is-open');
            bd.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('[data-drawer-toggle]').forEach((t) => {
            t.setAttribute('aria-expanded', 'false');
        });

        unlockScroll(restoreScroll);

        if (lastTrigger && document.contains(lastTrigger)) {
            lastTrigger.focus({ preventScroll: true });
        }
        lastTrigger = null;
    }

    /* ── Focus trap ───────────────────────────────────────────── */
    function trapFocus(e) {
        if (!openPanel || e.key !== 'Tab') return;

        const items = Array.from(openPanel.querySelectorAll(FOCUSABLE))
            .filter((n) => n.offsetParent !== null);
        if (!items.length) return;

        const first = items[0];
        const last = items[items.length - 1];

        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    }

    /* ── Wiring (delegated, survives PJAX swaps) ──────────────── */
    document.addEventListener('click', (e) => {
        const toggle = e.target.closest('[data-drawer-toggle]');
        if (toggle) {
            e.preventDefault();
            const panel = document.querySelector(toggle.getAttribute('data-drawer-toggle'));
            if (!panel) return;
            openPanel === panel ? close() : open(panel, toggle);
            return;
        }

        if (e.target.closest('[data-drawer-close]')) {
            e.preventDefault();
            close();
            return;
        }

        if (openPanel && e.target.closest('[data-drawer-backdrop]')) {
            close();
            return;
        }

        // A link inside the panel.
        if (openPanel && openPanel.contains(e.target)) {
            const link = e.target.closest('a[href]');
            if (!link) return;

            const href = link.getAttribute('href') || '';
            // Same-page section anchor (the landing page puts its section
            // bookmarks in the drawer). The scroll lock has to come off
            // WITHOUT restoring the old offset, and the jump has to happen
            // after the body is unpinned, or it lands in the wrong place.
            const hashOnly = href.startsWith('#') && href.length > 1;

            if (hashOnly) {
                const target = document.getElementById(href.slice(1));
                if (target) {
                    e.preventDefault();
                    close({ restoreScroll: false });

                    // Next frame: the body is unpinned and the document has
                    // its real height back, so offsets are measurable.
                    requestAnimationFrame(() => {
                        target.scrollIntoView({
                            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches
                                ? 'auto' : 'smooth',
                            block: 'start',
                        });
                        // Keep the URL in step without triggering a re-jump.
                        history.replaceState(null, '', href);
                    });
                    return;
                }
            }

            // Ordinary navigation: close so the panel is not left open
            // behind the incoming page after a PJAX swap.
            close();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && openPanel) {
            e.preventDefault();
            close();
            return;
        }
        trapFocus(e);
    });

    // A PJAX visit can begin from something other than a drawer link
    // (popstate, programmatic). Always reset to a known state.
    window.addEventListener('pjax:start', close);

    // Crossing back to a desktop width while open would strand the scroll
    // lock on a layout that no longer has a drawer. The three drawers hide
    // their triggers at different widths (700px on the landing page, 992px
    // for the guest nav, 1025px for the staff panels), so rather than
    // hard-coding a breakpoint, close as soon as the open panel's own
    // trigger stops being rendered.
    let resizeTimer = null;
    window.addEventListener('resize', () => {
        if (!openPanel) return;
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (!openPanel) return;
            const stillVisible = togglesFor(openPanel).some((t) => t.offsetParent !== null);
            if (!stillVisible) close();
        }, 150);
    });

    window.mobileNav = {
        open: (sel, trigger) => open(document.querySelector(sel), trigger),
        close,
        isOpen: () => openPanel !== null,
    };
})();
