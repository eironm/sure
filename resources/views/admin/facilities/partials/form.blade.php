{{--
    Shared Create/Edit facility form body.
    Expects: $facility (nullable), $categories, $tags, $submitLabel, $submitIcon
    The <form> wrapper and @csrf/@method live in the parent (create/edit) view.
--}}
@php $isEdit = isset($facility) && $facility; @endphp

<div class="facility-editor">

    {{-- ── Command bar ─────────────────────────────────────────── --}}
    <div class="fe-commandbar">
        <div class="fe-commandbar__intro">
            <span class="fe-eyebrow">{{ $isEdit ? 'Editing facility' : 'New facility' }}</span>
            <h2 class="fe-title mb-0">{{ $isEdit ? $facility->name : 'Untitled facility' }}</h2>
        </div>
        <div class="fe-commandbar__actions">
            <a href="{{ route('admin.facilities.index') }}" class="btn btn-light border rounded-pill px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="bi {{ $submitIcon }} me-1"></i> {{ $submitLabel }}
            </button>
        </div>
    </div>

    <div class="fe-grid">

        {{-- ── Left: section navigator ─────────────────────────── --}}
        <aside class="fe-nav">
            <nav class="fe-nav__list" id="feSectionNav">
                <a href="#sec-basics"  class="fe-nav__item active"><i class="bi bi-info-circle"></i><span>Basics</span></a>
                <a href="#sec-specs"   class="fe-nav__item"><i class="bi bi-sliders"></i><span>Specifications</span></a>
                <a href="#sec-details" class="fe-nav__item"><i class="bi bi-card-checklist"></i><span>Details</span></a>
                <a href="#sec-org"     class="fe-nav__item"><i class="bi bi-diagram-3"></i><span>Organization</span></a>
                <a href="#sec-media"   class="fe-nav__item"><i class="bi bi-images"></i><span>Gallery</span></a>
            </nav>

            {{-- Live preview card --}}
            <div class="fe-preview">
                <div class="fe-preview__media" id="fePreviewMedia">
                    @php $main = $isEdit ? $facility->getMainImage() : null; @endphp
                    @if($main)
                        <img src="{{ $main }}" alt="Featured">
                    @else
                        <div class="fe-preview__placeholder"><i class="bi bi-building"></i></div>
                    @endif
                </div>
                <div class="fe-preview__body">
                    <div class="fe-preview__name" id="fePreviewName">{{ $isEdit ? $facility->name : 'Facility name' }}</div>
                    <div class="fe-preview__meta">
                        <span id="fePreviewLoc"><i class="bi bi-geo-alt"></i> {{ $isEdit ? ($facility->location ?: 'No location') : 'Location' }}</span>
                    </div>
                    <div class="fe-preview__meta">
                        <span id="fePreviewCap"><i class="bi bi-people"></i> {{ $isEdit ? ($facility->capacity ? $facility->capacity.' pax' : '—') : '— pax' }}</span>
                        <span id="fePreviewPrice"><i class="bi bi-cash"></i> ₱{{ $isEdit ? number_format((float)($facility->price_per_hour ?? 0), 2) : '0.00' }}/hr</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ── Right: form sections ────────────────────────────── --}}
        <div class="fe-main">

            {{-- Basics --}}
            <section class="fe-card" id="sec-basics">
                <header class="fe-card__head">
                    <div class="fe-card__icon bg-primary-subtle text-primary"><i class="bi bi-info-circle"></i></div>
                    <div>
                        <h3 class="fe-card__title">Basic Information</h3>
                        <p class="fe-card__sub">Name and describe the facility.</p>
                    </div>
                </header>
                <div class="fe-card__body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" placeholder="Facility Name" required autofocus
                               value="{{ old('name', $isEdit ? $facility->name : '') }}">
                        <label for="name">Facility Name <span class="text-danger">*</span></label>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" placeholder="Description"
                                  style="height:120px;">{{ old('description', $isEdit ? $facility->description : '') }}</textarea>
                        <label for="description">Description</label>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </section>

            {{-- Specifications --}}
            <section class="fe-card" id="sec-specs">
                <header class="fe-card__head">
                    <div class="fe-card__icon bg-success-subtle text-success"><i class="bi bi-sliders"></i></div>
                    <div>
                        <h3 class="fe-card__title">Specifications</h3>
                        <p class="fe-card__sub">Location, capacity, pricing and availability.</p>
                    </div>
                </header>
                <div class="fe-card__body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                       id="location" name="location" placeholder="Location"
                                       value="{{ old('location', $isEdit ? $facility->location : '') }}">
                                <label for="location">Location</label>
                                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                       id="capacity" name="capacity" placeholder="50" min="1"
                                       value="{{ old('capacity', $isEdit ? $facility->capacity : '') }}">
                                <label for="capacity">Capacity (People)</label>
                                @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control @error('price_per_hour') is-invalid @enderror"
                                       id="price_per_hour" name="price_per_hour" placeholder="0.00" step="0.01" min="0"
                                       value="{{ old('price_per_hour', $isEdit ? $facility->price_per_hour : '') }}">
                                <label for="price_per_hour">Price Per Hour (₱)</label>
                                @error('price_per_hour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fe-switch">
                                <div class="form-check form-switch premium-switch fs-5 m-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="is_available" name="is_available" value="1"
                                           {{ old('is_available', $isEdit ? $facility->is_available : true) ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="fw-semibold small d-block mb-0" for="is_available">Available for Reservation</label>
                                    <span class="text-muted" style="font-size:.75rem;">Users can book this facility when on.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Details --}}
            <section class="fe-card" id="sec-details">
                <header class="fe-card__head">
                    <div class="fe-card__icon bg-info-subtle text-info"><i class="bi bi-card-checklist"></i></div>
                    <div>
                        <h3 class="fe-card__title">Additional Details</h3>
                        <p class="fe-card__sub">Amenities, hours, contacts and terms.</p>
                    </div>
                </header>
                <div class="fe-card__body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control @error('amenities') is-invalid @enderror"
                                          id="amenities" name="amenities" placeholder="Amenities"
                                          style="height:90px;">{{ old('amenities', $isEdit ? $facility->amenities : '') }}</textarea>
                                <label for="amenities">Amenities</label>
                                @error('amenities')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-text">e.g. projector, sound system, air-conditioning.</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('operating_hours') is-invalid @enderror"
                                       id="operating_hours" name="operating_hours" placeholder="Operating Hours"
                                       value="{{ old('operating_hours', $isEdit ? $facility->operating_hours : '') }}">
                                <label for="operating_hours">Operating Hours</label>
                                @error('operating_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                       id="contact_person" name="contact_person" placeholder="Contact Person"
                                       value="{{ old('contact_person', $isEdit ? $facility->contact_person : '') }}">
                                <label for="contact_person">Contact Person</label>
                                @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                       id="contact_phone" name="contact_phone" placeholder="Contact Phone"
                                       value="{{ old('contact_phone', $isEdit ? $facility->contact_phone : '') }}">
                                <label for="contact_phone">Contact Phone</label>
                                @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control @error('terms_conditions') is-invalid @enderror"
                                          id="terms_conditions" name="terms_conditions" placeholder="Terms & Conditions"
                                          style="height:110px;">{{ old('terms_conditions', $isEdit ? $facility->terms_conditions : '') }}</textarea>
                                <label for="terms_conditions">Terms &amp; Conditions</label>
                                @error('terms_conditions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Organization --}}
            <section class="fe-card" id="sec-org">
                <header class="fe-card__head">
                    <div class="fe-card__icon bg-primary-subtle text-primary"><i class="bi bi-diagram-3"></i></div>
                    <div>
                        <h3 class="fe-card__title">Organization</h3>
                        <p class="fe-card__sub">Categorize and tag for discoverability.</p>
                    </div>
                </header>
                <div class="fe-card__body">
                    <div class="mb-4">
                        <label for="category_id" class="fe-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">— Select a Category —</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $isEdit ? $facility->category_id : '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="fe-label">Tags</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                @php
                                    $checked = $isEdit
                                        ? $facility->tags->contains($tag->id)
                                        : (is_array(old('tags')) && in_array($tag->id, old('tags')));
                                @endphp
                                <div>
                                    <input type="checkbox" class="btn-check" id="tag_{{ $tag->id }}"
                                           name="tags[]" value="{{ $tag->id }}" {{ $checked ? 'checked' : '' }}>
                                    <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="tag_{{ $tag->id }}">
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- Gallery --}}
            <section class="fe-card" id="sec-media">
                <header class="fe-card__head">
                    <div class="fe-card__icon bg-warning-subtle text-warning-emphasis"><i class="bi bi-images"></i></div>
                    <div>
                        <h3 class="fe-card__title">Gallery</h3>
                        <p class="fe-card__sub">
                            Photos save <strong>instantly</strong> as you add them — no need to wait for “{{ $submitLabel }}”.
                            Cropped to 16:9. Drag to reorder. First image is featured.
                        </p>
                    </div>
                </header>
                <div class="fe-card__body">
                    <div class="upload-zone rounded-4 p-4 p-md-5 text-center" id="dropZone">
                        <input type="file" class="d-none" id="rawImageInput" multiple accept="image/png,image/jpeg,image/jpg,image/webp">
                        <i class="bi bi-cloud-arrow-up display-4 text-primary mb-2 d-block"></i>
                        <p class="fw-semibold text-dark mb-1">Drag &amp; drop images here</p>
                        <p class="text-muted small mb-3">PNG, JPG, WEBP · cropped to 16:9 · min 1280×720 recommended</p>
                        <label for="rawImageInput" class="btn btn-primary rounded-pill px-4 mb-0">
                            <i class="bi bi-folder2-open me-1"></i> Browse Files
                        </label>
                    </div>

                    {{-- Instant-upload gallery grid --}}
                    <div id="galleryGrid" class="fe-gallery mt-3" data-facility-id="{{ $isEdit ? $facility->id : '' }}">
                        @if($isEdit)
                            @foreach($facility->images as $image)
                                <div class="fe-thumb {{ $image->is_featured ? 'is-featured' : '' }}" data-id="{{ $image->id }}" draggable="true">
                                    <div class="fe-thumb__img"><img src="{{ $image->url }}" alt="Facility image"></div>
                                    <span class="fe-thumb__badge">{{ $image->is_featured ? 'Featured' : '' }}</span>
                                    <div class="fe-thumb__bar">
                                        <button type="button" class="fe-thumb__btn js-feature" title="Set as featured"><i class="bi bi-star{{ $image->is_featured ? '-fill' : '' }}"></i></button>
                                        <span class="fe-thumb__drag" title="Drag to reorder"><i class="bi bi-grip-vertical"></i></span>
                                        <button type="button" class="fe-thumb__btn js-delete text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <p class="text-muted small mt-2 mb-0" id="galleryEmptyHint" style="{{ $isEdit && $facility->images->isNotEmpty() ? 'display:none;' : '' }}">
                        <i class="bi bi-info-circle me-1"></i> No images yet.
                    </p>
                </div>
            </section>

            {{-- Sticky footer actions --}}
            <div class="fe-footer">
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-secondary fw-bold px-4">Cancel</a>
                <button type="submit" class="btn btn-primary fw-bold px-5">
                    <i class="bi {{ $submitIcon }} me-1"></i> {{ $submitLabel }}
                </button>
            </div>
        </div>
    </div>
</div>
