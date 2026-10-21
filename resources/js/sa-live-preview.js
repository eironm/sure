/**
 * Aurora CMS Live Preview — rebuilt.
 *
 * Simple and robust: on every CMS page the Blade template renders one
 * `.cms-preview-card` inside the page's `#cmsLivePreview` mount (placed at
 * the top of the content column by CSS). This module seeds the server-saved
 * brand values onto the card, then binds the page's form inputs so the
 * preview updates live as the admin types. Nothing is persisted until the
 * admin presses Save.
 *
 * No SPA swap machinery — every navigation is a normal full-page load.
 */
(function () {
    'use strict';

    function byId(id) {
        return document.getElementById(id);
    }

    function byKey(key) {
        return document.getElementById('setting-' + key);
    }

    /* ── Surface luminance for the auto light/dark logo ── */
    function luminanceOf(el) {
        var node = el;
        while (node && node !== document.body) {
            var bg = getComputedStyle(node).backgroundColor;
            var m = bg && bg.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
            if (m) return 0.299 * +m[1] + 0.587 * +m[2] + 0.114 * +m[3];
            node = node.parentElement;
        }
        return 255;
    }

    /* ── Seed the server-saved brand colors onto the card + swatches ── */
    function seedColors(card) {
        var colors = {
            '--pv-primary':   card.dataset.pvPrimary,
            '--pv-secondary': card.dataset.pvSecondary,
            '--pv-accent':    card.dataset.pvAccent,
            '--pv-dark':      card.dataset.pvDark
        };
        for (var prop in colors) {
            if (colors[prop]) card.style.setProperty(prop, colors[prop]);
        }
        var swMap = {
            '--pv-primary': 'pvSwatchPrimary',
            '--pv-secondary': 'pvSwatchSecondary',
            '--pv-accent': 'pvSwatchAccent',
            '--pv-dark': 'pvSwatchDark'
        };
        for (var prop in swMap) {
            var el = byId(swMap[prop]);
            if (el && colors[prop]) el.style.background = colors[prop];
        }
    }

    function initCard(card) {
        if (card.dataset.saPvInit === '1') return; // bound once per page load
        card.dataset.saPvInit = '1';

        seedColors(card);

        /* ── Auto light/dark logo ── */
        var logoImg = byId('pvLogoImg');
        var logoBar = byId('pvNavbar');

        function logoVariantAttrs() {
            var host = logoBar && logoBar.dataset.pvLogoDark ? logoBar : card;
            return {
                dark:     (host.dataset.pvLogoDark     || '').trim(),
                light:    (host.dataset.pvLogoLight    || '').trim(),
                fallback: (host.dataset.pvLogoFallback || '').trim()
            };
        }

        function applyLogoVariant() {
            if (!logoImg) return;
            var v = logoVariantAttrs();
            if (!/^blob:/.test(logoImg.src)) {
                var lum = luminanceOf(logoImg);
                // Dark surface (pv-appbar is dark by design, lum < 110) gets the
                // white/light variant; light surfaces get the dark variant.
                var pick = (lum < 110 ? v.dark : v.light) || v.fallback;
                if (pick) {
                    var fb = byId('pvLogoFallback');
                    if (fb) fb.style.display = 'none';
                    logoImg.style.display = '';
                    logoImg.src = pick;
                }
            }
            logoImg.style.filter = 'none';
        }
        applyLogoVariant();

        function showLogo(file) {
            if (!file || !logoImg) return;
            var url = URL.createObjectURL(file);
            logoImg.onload = function () { URL.revokeObjectURL(url); applyLogoVariant(); };
            logoImg.onerror = function () { applyLogoVariant(); };
            logoImg.src = url;
            logoImg.style.display = '';
            var fb = byId('pvLogoFallback');
            if (fb) fb.style.display = 'none';
        }

        /* ── CMS auto light/dark toggle -> preview logo ── */
        var autoToggle = byKey('logo_auto_variant');
        if (autoToggle && logoImg) {
            function syncAutoToggle() {
                var on = autoToggle.type === 'checkbox' ? autoToggle.checked : autoToggle.value === '1';
                if (on) {
                    applyLogoVariant();
                } else {
                    var v = logoVariantAttrs();
                    var src = v.dark || v.fallback;
                    if (src) {
                        var fb = byId('pvLogoFallback');
                        if (fb) fb.style.display = 'none';
                        logoImg.style.display = '';
                        logoImg.src = src;
                    }
                }
            }
            syncAutoToggle();
            autoToggle.addEventListener('change', syncAutoToggle);
            autoToggle.addEventListener('input', syncAutoToggle);
        }

        /* ── Logo uploads -> preview ── */
        [
            ['setting-system_logo', 'cropped_input_system_logo'],
            ['setting-logo_light',  'cropped_input_logo_light'],
            ['setting-logo_white',  'cropped_input_logo_white']
        ].forEach(function (ids) {
            ids.forEach(function (id) {
                var el = byId(id);
                if (el) el.addEventListener('change', function () {
                    if (el.files && el.files[0]) showLogo(el.files[0]);
                });
            });
        });

        /* ── Brand colors -> CSS vars + swatches ── */
        var colorMap = {
            brand_primary:   '--pv-primary',
            brand_secondary: '--pv-secondary',
            brand_accent:    '--pv-accent',
            brand_dark:      '--pv-dark'
        };
        var swatchMap = {
            brand_primary:   'pvSwatchPrimary',
            brand_secondary: 'pvSwatchSecondary',
            brand_accent:    'pvSwatchAccent',
            brand_dark:      'pvSwatchDark'
        };
        function applyColor(key) {
            var input = byKey(key);
            if (!input || !card) return;
            var val = (input.value || '').trim();
            if (!/^#[0-9a-fA-F]{6}$/.test(val)) return;
            card.style.setProperty(colorMap[key], val);
            var sw = byId(swatchMap[key]);
            if (sw) sw.style.background = val;
            if (key === 'brand_dark') applyLogoVariant();
        }
        Object.keys(colorMap).forEach(function (key) {
            var input = byKey(key);
            if (input) {
                applyColor(key);
                input.addEventListener('input', function () { applyColor(key); });
            }
            var swatchPicker = byId('color-swatch-' + key);
            if (swatchPicker) swatchPicker.addEventListener('input', function () { applyColor(key); });
        });

        /* ── Text bindings (saved DB value = fallback) ── */
        var textBind = [
            ['system_name',     'pvName',         card.dataset.pvSysName || 'System Name'],
            ['hero_heading',    'pvHeroTitle',    card.dataset.pvHeroHeading || ''],
            ['hero_subheading', 'pvHeroSubtitle', card.dataset.pvHeroSubheading || ''],
            ['hero_eyebrow',    'pvHeroEyebrow',  card.dataset.pvHeroEyebrow || '']
        ];
        textBind.forEach(function (pair) {
            var input = byKey(pair[0]);
            var target = byId(pair[1]);
            if (!input || !target) return;
            input.addEventListener('input', function () {
                target.textContent = input.value.trim() || pair[2];
            });
        });

        /* ── Announcement banner ── */
        var annBar   = byId('pvAnnBar');
        var annText  = byId('pvAnnText');
        var annEnabled = byKey('announcement_enabled');
        var annTextInp = byKey('announcement_text');
        var annFallbackText = card.dataset.pvAnnText || '';
        function syncAnn() {
            if (!annBar) return;
            var on = annEnabled
                ? (annEnabled.type === 'checkbox' ? annEnabled.checked : annEnabled.value === '1')
                : false;
            var txt = (annTextInp && annTextInp.value !== '') ? annTextInp.value : annFallbackText;
            annBar.classList.toggle('pv-annbar-off', !on);
            if (annText) annText.textContent = txt || ' ';
        }
        syncAnn();
        if (annEnabled) {
            annEnabled.addEventListener('change', syncAnn);
            annEnabled.addEventListener('input', syncAnn);
        }
        if (annTextInp) annTextInp.addEventListener('input', syncAnn);

        /* ── Wordmark-only mode ── */
        var wordmark = byKey('use_wordmark_only');
        var nameEl = byId('pvName');
        function syncWordmark() {
            if (!nameEl) return;
            var on = wordmark
                ? (wordmark.type === 'checkbox' ? wordmark.checked : wordmark.value === '1')
                : (card.dataset.pvWordmarkOnly === '1');
            nameEl.classList.toggle('pv-name-wordmark', on);
        }
        syncWordmark();
        if (wordmark) {
            wordmark.addEventListener('change', syncWordmark);
            wordmark.addEventListener('input', syncWordmark);
        }

        /* ── Hero gradient colors (appearance page) ── */
        var heroEl  = byId('pvHero');
        var gradStart = byKey('hero_gradient_start');
        var gradEnd   = byKey('hero_gradient_end');
        var gradEnabled = byKey('hero_gradient_enabled');
        function applyGradient() {
            if (!heroEl) return;
            if (/url\(/.test(heroEl.style.backgroundImage || '')) return;
            var isOff = gradEnabled && ((gradEnabled.type === 'checkbox' && !gradEnabled.checked) || gradEnabled.value === '0');
            var s = gradStart && /^#[0-9a-fA-F]{6}$/.test((gradStart.value || '').trim()) ? (gradStart.value || '').trim() : null;
            var e = gradEnd   && /^#[0-9a-fA-F]{6}$/.test((gradEnd.value   || '').trim()) ? (gradEnd.value   || '').trim() : null;
            if (isOff || (!s && !e)) {
                heroEl.style.background = 'linear-gradient(135deg, rgba(15,23,42,.92), rgba(15,23,42,.6))';
                return;
            }
            heroEl.style.background = 'linear-gradient(135deg, rgba(0,0,0,.55), rgba(0,0,0,.25)), '
                + 'linear-gradient(135deg, ' + (s || '#2563eb') + ', ' + (e || '#0f172a') + ')';
        }
        if (gradStart) { applyGradient(); gradStart.addEventListener('input', applyGradient); }
        if (gradEnd)   { applyGradient(); gradEnd.addEventListener('input', applyGradient); }
        if (gradEnabled) { gradEnabled.addEventListener('change', applyGradient); }

        /* ── Login background upload -> hero overlay image ── */
        ['setting-login_background', 'cropped_input_login_background'].forEach(function (id) {
            var el = byId(id);
            if (!el || !heroEl) return;
            el.addEventListener('change', function () {
                if (el.files && el.files[0]) {
                    var url = URL.createObjectURL(el.files[0]);
                    heroEl.style.backgroundImage =
                        'linear-gradient(135deg, rgba(0,0,0,.55), rgba(0,0,0,.25)), url(' + url + ')';
                    applyLogoVariant();
                }
            });
        });

        document.documentElement.dataset.saPvReady = '1';
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.cms-preview-card').forEach(initCard);
    });
})();
