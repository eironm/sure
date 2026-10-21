{{-- Styles, crop modal, and instant-upload JS shared by create & edit. --}}
<style>
/* ── Layout ─────────────────────────────────────────────────────── */
.facility-editor { --fe-radius: 16px; }

.fe-commandbar {
    position: sticky; top: 0; z-index: 20;
    display: flex; justify-content: space-between; align-items: center; gap: 1rem;
    background: rgba(255,255,255,.85); backdrop-filter: blur(10px);
    padding: .85rem 1.1rem; margin-bottom: 1.25rem;
    border: 1px solid #e5e9f0; border-radius: 14px;
    box-shadow: 0 4px 18px rgba(15,23,42,.05);
}
.fe-eyebrow { font-size: .68rem; letter-spacing: .12em; text-transform: uppercase; color: #94a3b8; font-weight: 700; }
.fe-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; letter-spacing: -.02em; }
.fe-commandbar__actions { display: flex; gap: .5rem; flex-shrink: 0; }

.fe-grid { display: grid; grid-template-columns: 260px 1fr; gap: 1.5rem; align-items: start; }
@media (max-width: 991px) { .fe-grid { grid-template-columns: 1fr; } }

/* ── Section navigator + preview ────────────────────────────────── */
.fe-nav { position: sticky; top: 5.2rem; display: flex; flex-direction: column; gap: 1rem; }
@media (max-width: 991px) { .fe-nav { position: static; } .fe-nav__list { flex-direction: row !important; overflow-x: auto; } }
.fe-nav__list { display: flex; flex-direction: column; gap: .25rem; background: #fff; border: 1px solid #e5e9f0; border-radius: var(--fe-radius); padding: .5rem; }
.fe-nav__item {
    display: flex; align-items: center; gap: .7rem;
    padding: .6rem .8rem; border-radius: 10px; text-decoration: none;
    color: #64748b; font-weight: 600; font-size: .9rem; white-space: nowrap;
    transition: background .15s, color .15s;
}
.fe-nav__item i { font-size: 1rem; }
.fe-nav__item:hover { background: #f1f5f9; color: #0f172a; }
.fe-nav__item.active { background: var(--brand-primary, #0d6efd); color: #fff; box-shadow: 0 4px 12px rgba(var(--brand-primary-rgb, 13,110,253), .25); }

.fe-preview { background: #fff; border: 1px solid #e5e9f0; border-radius: var(--fe-radius); overflow: hidden; }
.fe-preview__media { aspect-ratio: 16/9; background: #0f172a; }
.fe-preview__media img { width: 100%; height: 100%; object-fit: cover; display: block; }
.fe-preview__placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #475569; font-size: 2.5rem; background: linear-gradient(135deg,#1e293b,#0f172a); }
.fe-preview__body { padding: .9rem 1rem; }
.fe-preview__name { font-weight: 700; color: #0f172a; margin-bottom: .3rem; }
.fe-preview__meta { display: flex; flex-wrap: wrap; gap: .75rem; font-size: .78rem; color: #64748b; margin-top: .15rem; }
.fe-preview__meta i { margin-right: .2rem; }

/* ── Cards ──────────────────────────────────────────────────────── */
.fe-main { display: flex; flex-direction: column; gap: 1.25rem; }
.fe-card { background: #fff; border: 1px solid #e5e9f0; border-radius: var(--fe-radius); overflow: hidden; scroll-margin-top: 5.5rem; }
.fe-card__head { display: flex; gap: .9rem; align-items: center; padding: 1.1rem 1.4rem; border-bottom: 1px solid #eef1f6; }
.fe-card__icon { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; }
.fe-card__title { font-size: 1.02rem; font-weight: 700; color: #0f172a; margin: 0; }
.fe-card__sub { font-size: .8rem; color: #94a3b8; margin: .1rem 0 0; }
.fe-card__body { padding: 1.4rem; }
.fe-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #94a3b8; display: block; margin-bottom: .5rem; }

.fe-switch { display: flex; align-items: center; gap: .8rem; height: 100%; padding: .5rem .25rem; }

.form-control, .form-select { border-color: #cbd5e1; border-radius: 9px; }
.form-control:focus, .form-select:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 .22rem rgba(var(--brand-primary-rgb),.15); }
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select ~ label { color: var(--brand-primary); font-weight: 600; opacity: .85; }

/* ── Sticky footer ──────────────────────────────────────────────── */
.fe-footer {
    position: sticky; bottom: 0; z-index: 10;
    display: flex; justify-content: flex-end; gap: .6rem;
    background: rgba(255,255,255,.9); backdrop-filter: blur(8px);
    border: 1px solid #e5e9f0; border-radius: 14px; padding: .85rem 1.1rem;
    box-shadow: 0 -4px 18px rgba(15,23,42,.05);
}

/* ── Upload zone ────────────────────────────────────────────────── */
/* CMS theme: all facility actions follow the active primary brand color. */
#facilityForm .btn-primary,
#facilityForm .btn-primary:hover,
#facilityForm .btn-primary:focus,
#cropperModal .btn-primary,
#cropperModal .btn-primary:hover,
#cropperModal .btn-primary:focus {
    --bs-btn-bg: var(--brand-primary, #0d6efd);
    --bs-btn-border-color: var(--brand-primary, #0d6efd);
    --bs-btn-hover-bg: var(--brand-secondary, var(--brand-primary, #0d6efd));
    --bs-btn-hover-border-color: var(--brand-secondary, var(--brand-primary, #0d6efd));
    background-color: var(--brand-primary, #0d6efd) !important;
    border-color: var(--brand-primary, #0d6efd) !important;
    color: #fff !important;
}
#facilityForm .btn-primary:hover,
#facilityForm .btn-primary:focus,
#cropperModal .btn-primary:hover,
#cropperModal .btn-primary:focus {
    background-color: var(--brand-secondary, var(--brand-primary, #0d6efd)) !important;
    border-color: var(--brand-secondary, var(--brand-primary, #0d6efd)) !important;
}
.upload-zone:hover, .upload-zone.dragging { border-color: var(--brand-primary, #0d6efd); background: rgba(var(--brand-primary-rgb, 13,110,253), .06); }


/* ── Instant-upload gallery ─────────────────────────────────────── */
.fe-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: .8rem; }
.fe-thumb { position: relative; border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fff; transition: border-color .15s, transform .15s, box-shadow .15s; }
.fe-thumb.is-featured { border-color: var(--brand-primary, #f59e0b); box-shadow: 0 0 0 2px rgba(var(--brand-primary-rgb, 245,158,11), .14); }
.fe-thumb.dragging { opacity: .5; }
.fe-thumb.drop-target { border-color: var(--brand-primary,#0d6efd); transform: scale(1.02); }
.fe-thumb__img { aspect-ratio: 16/9; background: #f1f5f9; }
.fe-thumb__img img { width: 100%; height: 100%; object-fit: cover; display: block; }
.fe-thumb__badge { position: absolute; top: 6px; left: 6px; background: var(--brand-primary, #f59e0b); color: #1c1917; font-size: .62rem; font-weight: 800; letter-spacing: .04em; padding: 2px 8px; border-radius: 999px; opacity: 0; transition: opacity .15s; }
.fe-thumb.is-featured .fe-thumb__badge { opacity: 1; }
.fe-thumb__bar { display: flex; align-items: center; justify-content: space-between; padding: .35rem .5rem; background: #f8fafc; border-top: 1px solid #eef1f6; }
.fe-thumb__btn { border: none; background: transparent; color: #475569; width: 28px; height: 28px; border-radius: 7px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: background .12s, color .12s; }
.fe-thumb__btn:hover { background: #e2e8f0; }
.fe-thumb.is-featured .js-feature { color: var(--brand-primary, #f59e0b); }
.fe-thumb__drag { color: #cbd5e1; cursor: grab; padding: 0 .2rem; }
.fe-thumb__uploading { position: absolute; inset: 0; background: rgba(255,255,255,.75); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .4rem; }
.fe-thumb__uploading .progress { width: 70%; height: 5px; }

/* ── Premium switch (kept from original) ────────────────────────── */
.premium-switch .form-check-input {
    width: 3.2rem !important; height: 1.75rem !important; margin-top: 0; cursor: pointer;
    appearance: none; -webkit-appearance: none; box-shadow: none; border: none;
    background-color: #cbd5e1; border-radius: 99px;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat; background-position: left .2rem center !important;
    background-size: 1.35rem 1.35rem !important;
    transition: background-position .2s cubic-bezier(.4,0,.2,1), background-color .2s;
}
.premium-switch .form-check-input:checked {
    background-color: var(--brand-primary, #38bdf8);
    background-position: right .2rem center !important;
    border-color: var(--brand-primary, #38bdf8);
}
.premium-switch .form-check-input:focus { box-shadow: 0 0 0 4px rgba(56,189,248,.25); }

.btn-check:checked + .btn-outline-secondary { background: #1e293b; color: #fff; border-color: #1e293b; }

/* ── Cropper modal ──────────────────────────────────────────────── */
#cropperModal .modal-content { border-radius: 16px; }
#crop-preview-area { max-height: 440px; overflow: hidden; background: #111; border-radius: 8px; }
#crop-preview-area img { display: block; max-width: 100%; }
</style>

{{-- ── 16:9 Crop Modal ────────────────────────────────────────────── --}}
<div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:800px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h5 class="fw-bold mb-0"><i class="bi bi-crop text-primary me-2"></i> Crop Image — 16:9</h5>
                    <p class="text-muted small mb-0 mt-1">Locked to <strong>16:9</strong>. Applied crops upload immediately.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-2">
                <div id="crop-preview-area">
                    <img id="crop-img-el" src="" alt="Crop target" style="display:block;max-width:100%;">
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-3 d-flex justify-content-between align-items-center">
                <span class="text-muted small" id="crop-size-hint"></span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light px-4 fw-bold" id="crop-cancel-btn">Skip File</button>
                    <button type="button" class="btn btn-primary px-5 fw-bold" id="crop-confirm-btn">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Apply &amp; Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>

<script>
onPageLoad(function () {
    const form = document.getElementById('facilityForm');
    if (!form) return;

    const cfg = {
        uploadUrl:  form.dataset.uploadUrl,
        reorderUrl: form.dataset.reorderUrl,
        draftToken: form.dataset.draftToken || null,
        csrf: document.querySelector('meta[name="csrf-token"]')?.content
              || form.querySelector('input[name="_token"]')?.value,
    };

    const grid       = document.getElementById('galleryGrid');
    const facilityId = grid?.dataset.facilityId || null;
    const emptyHint  = document.getElementById('galleryEmptyHint');

    // ── Live preview bindings ──────────────────────────────────────
    const bind = (id, el, fmt) => {
        const src = document.getElementById(id);
        if (!src || !el) return;
        const update = () => { el.innerHTML = fmt(src.value); };
        src.addEventListener('input', update);
    };
    const pName  = document.getElementById('fePreviewName');
    const pLoc   = document.getElementById('fePreviewLoc');
    const pCap   = document.getElementById('fePreviewCap');
    const pPrice = document.getElementById('fePreviewPrice');
    document.getElementById('name')?.addEventListener('input', e => { if (pName) pName.textContent = e.target.value || 'Facility name'; });
    document.getElementById('location')?.addEventListener('input', e => { if (pLoc) pLoc.innerHTML = `<i class="bi bi-geo-alt"></i> ${e.target.value || 'Location'}`; });
    document.getElementById('capacity')?.addEventListener('input', e => { if (pCap) pCap.innerHTML = `<i class="bi bi-people"></i> ${e.target.value ? e.target.value + ' pax' : '— pax'}`; });
    document.getElementById('price_per_hour')?.addEventListener('input', e => {
        const v = parseFloat(e.target.value || 0).toFixed(2);
        if (pPrice) pPrice.innerHTML = `<i class="bi bi-cash"></i> ₱${v}/hr`;
    });

    // ── Scrollspy for section nav ──────────────────────────────────
    const navItems = Array.from(document.querySelectorAll('#feSectionNav .fe-nav__item'));
    const sections = navItems.map(a => document.querySelector(a.getAttribute('href'))).filter(Boolean);
    navItems.forEach(a => a.addEventListener('click', e => {
        e.preventDefault();
        document.querySelector(a.getAttribute('href'))?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }));
    if ('IntersectionObserver' in window && sections.length) {
        const spy = new IntersectionObserver(entries => {
            entries.forEach(en => {
                if (en.isIntersecting) {
                    navItems.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + en.target.id));
                }
            });
        }, { rootMargin: '-45% 0px -50% 0px' });
        sections.forEach(s => spy.observe(s));
    }

    // ── Crop + instant upload ──────────────────────────────────────
    const rawInput   = document.getElementById('rawImageInput');
    const dropZone   = document.getElementById('dropZone');
    const cropImgEl  = document.getElementById('crop-img-el');
    const cropHint   = document.getElementById('crop-size-hint');
    const modalEl    = document.getElementById('cropperModal');
    const confirmBtn = document.getElementById('crop-confirm-btn');
    const cancelBtn  = document.getElementById('crop-cancel-btn');

    let cropQueue = [], cropper = null, cropModal = null;

    const getModal = () => cropModal ??= new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });

    if (dropZone) {
        dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragging'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragging'));
        dropZone.addEventListener('drop', e => { e.preventDefault(); dropZone.classList.remove('dragging'); enqueue(e.dataTransfer.files); });
    }
    rawInput?.addEventListener('change', e => { enqueue(e.target.files); setTimeout(() => rawInput.value = '', 400); });

    function enqueue(files) {
        Array.from(files).forEach(f => { if (f.type.startsWith('image/')) cropQueue.push(f); });
        processNext();
    }

    function processNext() {
        if (!cropQueue.length) return;
        const file = cropQueue.shift();
        if (cropper) { cropper.destroy(); cropper = null; }
        cropImgEl.src = ''; cropImgEl.onload = null;

        const onShown = () => {
            modalEl.removeEventListener('shown.bs.modal', onShown);
            cropImgEl.onload = () => {
                if (cropper) { cropper.destroy(); cropper = null; }
                cropper = new Cropper(cropImgEl, {
                    aspectRatio: 16/9, viewMode: 1, dragMode: 'move', autoCropArea: 1,
                    restore: false, guides: true, center: true, highlight: false,
                    cropBoxMovable: true, cropBoxResizable: true, toggleDragModeOnDblclick: false,
                    crop(ev) {
                        const w = Math.round(ev.detail.width), h = Math.round(ev.detail.height);
                        cropHint.textContent = `Crop: ${w} × ${h}px` + (w < 1280 ? '  ⚠️ min 1280px recommended' : '');
                    }
                });
            };
            cropImgEl.src = URL.createObjectURL(file);
        };
        modalEl.addEventListener('shown.bs.modal', onShown);
        getModal().show();
    }

    confirmBtn?.addEventListener('click', () => {
        if (!cropper) return;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
        cropper.getCroppedCanvas({ width: 1280, height: 720, imageSmoothingQuality: 'high' })
            .toBlob(blob => {
                const file = new File([blob], `facility_${Date.now()}.jpg`, { type: 'image/jpeg' });
                getModal().hide();
                modalEl.addEventListener('hidden.bs.modal', () => {
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="bi bi-cloud-arrow-up me-1"></i> Apply &amp; Upload';
                    uploadFile(file);
                    setTimeout(processNext, 120);
                }, { once: true });
            }, 'image/jpeg', 0.92);
    });

    cancelBtn?.addEventListener('click', () => {
        getModal().hide();
        modalEl.addEventListener('hidden.bs.modal', () => {
            if (cropper) { cropper.destroy(); cropper = null; }
            setTimeout(processNext, 120);
        }, { once: true });
    });
    modalEl?.addEventListener('hidden.bs.modal', () => { if (cropper) { cropper.destroy(); cropper = null; } });

    // ── Upload a cropped file immediately ──────────────────────────
    function uploadFile(file) {
        if (emptyHint) emptyHint.style.display = 'none';
        const localUrl = URL.createObjectURL(file);
        const card = document.createElement('div');
        card.className = 'fe-thumb';
        card.innerHTML = `
            <div class="fe-thumb__img"><img src="${localUrl}" alt="Uploading"></div>
            <div class="fe-thumb__uploading">
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <div class="progress"><div class="progress-bar" role="progressbar" style="width:0%"></div></div>
            </div>`;
        grid.appendChild(card);
        const bar = card.querySelector('.progress-bar');

        const fd = new FormData();
        fd.append('image', file);
        fd.append('ajax', '1');
        if (facilityId) fd.append('facility_id', facilityId);
        else if (cfg.draftToken) fd.append('draft_token', cfg.draftToken);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', cfg.uploadUrl);
        xhr.setRequestHeader('X-CSRF-TOKEN', cfg.csrf);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.upload.onprogress = e => { if (e.lengthComputable && bar) bar.style.width = Math.round(e.loaded / e.total * 100) + '%'; };
        xhr.onload = () => {
            let res = {};
            try { res = JSON.parse(xhr.responseText); } catch (_) {}
            if (xhr.status >= 200 && xhr.status < 300 && res.ok) {
                hydrateCard(card, res.image);
            } else {
                card.querySelector('.fe-thumb__uploading').innerHTML =
                    '<div class="text-danger small text-center px-2"><i class="bi bi-exclamation-triangle"></i> Upload failed</div>';
                setTimeout(() => card.remove(), 2500);
            }
        };
        xhr.onerror = () => { card.remove(); };
        xhr.send(fd);
    }

    // Replace the uploading overlay with real controls once saved server-side.
    function hydrateCard(card, image) {
        card.dataset.id = image.id;
        card.setAttribute('draggable', 'true');
        if (image.is_featured) card.classList.add('is-featured');
        card.innerHTML = `
            <div class="fe-thumb__img"><img src="${image.url}" alt="Facility image"></div>
            <span class="fe-thumb__badge">Featured</span>
            <div class="fe-thumb__bar">
                <button type="button" class="fe-thumb__btn js-feature" title="Set as featured"><i class="bi bi-star${image.is_featured ? '-fill' : ''}"></i></button>
                <span class="fe-thumb__drag" title="Drag to reorder"><i class="bi bi-grip-vertical"></i></span>
                <button type="button" class="fe-thumb__btn js-delete text-danger" title="Delete"><i class="bi bi-trash"></i></button>
            </div>`;
        wireCard(card);
        syncPreviewFromFeatured();
    }

    // ── Card actions (feature / delete) ────────────────────────────
    function wireCard(card) {
        card.querySelector('.js-feature')?.addEventListener('click', async () => {
            const id = card.dataset.id;
            const r = await fetch(`/admin/facilities/images/${id}/set-featured`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': cfg.csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!r.ok) return;
            grid.querySelectorAll('.fe-thumb').forEach(c => {
                c.classList.remove('is-featured');
                c.querySelector('.js-feature i')?.classList.replace('bi-star-fill', 'bi-star');
            });
            card.classList.add('is-featured');
            card.querySelector('.js-feature i')?.classList.replace('bi-star', 'bi-star-fill');
            syncPreviewFromFeatured();
        });

        card.querySelector('.js-delete')?.addEventListener('click', async () => {
            const id = card.dataset.id;
            const r = await fetch(`/admin/facilities/images/${id}/delete-image`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': cfg.csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!r.ok) return;
            const wasFeatured = card.classList.contains('is-featured');
            card.remove();
            if (wasFeatured) {
                const first = grid.querySelector('.fe-thumb');
                if (first) {
                    first.classList.add('is-featured');
                    first.querySelector('.js-feature i')?.classList.replace('bi-star', 'bi-star-fill');
                }
            }
            if (!grid.querySelector('.fe-thumb') && emptyHint) emptyHint.style.display = '';
            syncPreviewFromFeatured();
        });

        enableDrag(card);
    }

    function syncPreviewFromFeatured() {
        const media = document.getElementById('fePreviewMedia');
        if (!media) return;
        const featured = grid.querySelector('.fe-thumb.is-featured img') || grid.querySelector('.fe-thumb img');
        media.innerHTML = featured
            ? `<img src="${featured.src}" alt="Featured">`
            : '<div class="fe-preview__placeholder"><i class="bi bi-building"></i></div>';
    }

    // ── Drag to reorder ────────────────────────────────────────────
    let dragEl = null;
    function enableDrag(card) {
        card.addEventListener('dragstart', () => { dragEl = card; card.classList.add('dragging'); });
        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
            grid.querySelectorAll('.fe-thumb').forEach(c => c.classList.remove('drop-target'));
            persistOrder();
        });
        card.addEventListener('dragover', e => {
            e.preventDefault();
            if (!dragEl || dragEl === card) return;
            const after = e.clientY > card.getBoundingClientRect().top + card.offsetHeight / 2;
            grid.insertBefore(dragEl, after ? card.nextSibling : card);
        });
    }

    let orderTimer = null;
    function persistOrder() {
        clearTimeout(orderTimer);
        orderTimer = setTimeout(() => {
            const order = Array.from(grid.querySelectorAll('.fe-thumb')).map(c => c.dataset.id).filter(Boolean);
            if (!order.length) return;
            fetch(cfg.reorderUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': cfg.csrf, 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ order }),
            });
        }, 400);
    }

    // Wire any server-rendered cards (edit page).
    grid?.querySelectorAll('.fe-thumb').forEach(wireCard);
});
</script>
