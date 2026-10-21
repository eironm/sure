<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.custom-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    

    <div class="rw-shell">
        <!-- ═══ Hero strip (CMS-driven) ═══ -->
        <div class="rw-hero">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h1 class="rw-hero-title mb-1"><?php echo e($reservationSettings['reserve_hero_title'] ?? 'Reserve a Venue'); ?></h1>
                        <p class="rw-hero-sub mb-0"><?php echo e($reservationSettings['reserve_hero_subheading'] ?? 'Find the perfect campus space for your event — availability checked in real time.'); ?></p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <?php if($clientType === 'internal'): ?>
                            <span class="rw-hero-badge rw-badge-success"><i class="bi bi-mortarboard-fill"></i> Internal — Fees Waived</span>
                        <?php else: ?>
                            <span class="rw-hero-badge rw-badge-info"><i class="bi bi-building"></i> External User</span>
                        <?php endif; ?>
                        <span class="rw-hero-badge rw-badge-gold"><i class="bi bi-shield-check"></i> Secure Booking</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error banner (server-side validation failures come back with old input) -->
        <?php if($errors->any()): ?>
        <div class="container">
            <div class="alert rw-alert-error mt-4 d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                <div>
                    <strong>Something needs fixing before we can continue.</strong>
                    <ul class="mb-0 mt-1 small">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Draft restore prompt -->
        <div class="container">
            <div class="rw-draft-banner mt-3 d-none" id="draft-banner">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <i class="bi bi-file-earmark-diff text-warning fs-5"></i>
                    <span class="small fw-bold flex-grow-1">You have an unfinished reservation draft saved on this device. Restore it or start fresh?</span>
                    <button type="button" class="btn btn-sm btn-warning fw-bold" onclick="RW.restoreDraft()">Restore Draft</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="RW.clearDraft(); this.parentElement.parentElement.parentElement.classList.add('d-none')">Start Fresh</button>
                </div>
            </div>
        </div>

        <div class="container pb-5">
            <div class="rw-grid mt-4">

                <!-- ═══ LEFT: sticky booking summary (quote panel) ═══ -->
                <div class="rw-quote-col">
                    <div class="rw-quote" id="rw-quote-box">
                        <div class="rw-quote-head">
                            <i class="bi bi-ticket-perforated"></i>
                            <span>Your Booking</span>
                            <small class="rw-quote-step" id="quote-step">Section 1 of 5</small>
                        </div>

                        <div class="rw-quote-row">
                            <div class="rw-quote-icon"><i class="bi bi-stars"></i></div>
                            <div class="rw-quote-row-body">
                                <div class="rw-quote-label">Event</div>
                                <div class="rw-quote-value rw-editable" onclick="RW.openSection(1)" title="Click to view or edit event details">
                                    <span id="q-event">Not set</span>
                                    <i class="bi bi-pencil d-none d-md-inline ms-1"></i>
                                </div>
                            </div>
                        </div>
                        <div class="rw-quote-row">
                            <div class="rw-quote-icon"><i class="bi bi-calendar-event"></i></div>
                            <div class="rw-quote-row-body">
                                <div class="rw-quote-label">Schedule</div>
                                <div class="rw-quote-value rw-editable" onclick="RW.openSection(1)" title="Click to change schedule">
                                    <span id="q-schedule">Pick a date & time</span>
                                    <i class="bi bi-pencil d-none d-md-inline ms-1"></i>
                                </div>
                                <div class="rw-quote-sub" id="q-schedule-sub">Duration will appear here</div>
                            </div>
                        </div>
                        <div class="rw-quote-row">
                            <div class="rw-quote-icon"><i class="bi bi-people-fill"></i></div>
                            <div class="rw-quote-row-body">
                                <div class="rw-quote-label">Attendees</div>
                                <div class="rw-quote-value rw-editable" onclick="RW.openSection(1)" title="Click to change attendees">
                                    <span id="q-people">— guests</span>
                                    <i class="bi bi-pencil d-none d-md-inline ms-1"></i>
                                </div>
                            </div>
                        </div>

                        <div class="rw-quote-venue d-none" id="q-venue-card" onclick="RW.openSection(2)" title="Click to change venue" style="cursor: pointer;">
                            <div class="rw-quote-venue-img" style="background-image:url('');"></div>
                            <div class="rw-quote-venue-body">
                                <div class="rw-quote-venue-name" id="q-venue-name">—</div>
                                <div class="rw-quote-venue-cap" id="q-venue-cap">—</div>
                            </div>
                            <div class="rw-quote-venue-price" id="q-venue-price">—</div>
                        </div>

                        <!-- Logistics & Arrangements selected summary -->
                        <div class="rw-quote-row" id="q-logi-row">
                            <div class="rw-quote-icon"><i class="bi bi-box-seam-fill"></i></div>
                            <div class="rw-quote-row-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="rw-quote-label mb-0">Arrangements</div>
                                    <span class="badge" id="q-logi-badge" style="font-size: 0.66rem; font-weight: 700; background: #e2e8f0; color: #475569;">0 items</span>
                                </div>
                                <div class="rw-quote-value rw-editable" onclick="RW.openSection(3)" title="Click to view or edit arrangements">
                                    <span id="q-logi-summary-text" class="small">None selected</span>
                                    <i class="bi bi-pencil d-none d-md-inline ms-1"></i>
                                </div>
                                <div id="q-logi-items-list" class="rw-quote-logi-list mt-1.5 d-none"></div>
                            </div>
                        </div>

                        <!-- Itemized pricing -->
                        <div class="rw-quote-lines d-none" id="q-lines-wrap">
                            <div class="rw-quote-line-label">Estimated breakdown</div>
                            <div id="q-lines"></div>
                        </div>

                        <div class="rw-quote-total">
                            <div class="rw-quote-total-label">
                                <span id="q-total-label">ESTIMATED TOTAL</span>
                                <span class="rw-quote-total-sub" id="q-total-sub">+ Security deposit collected separately</span>
                            </div>
                            <div class="rw-quote-total-value" id="q-total">₱0.00</div>
                        </div>
                    </div>
                </div>

                <!-- ═══ RIGHT: single scrollable accordion deck ═══ -->
                <div class="rw-main-col">

                    <!-- Hidden stepper container for backward-compatibility if referenced -->
                    <div id="stepper" class="d-none"></div>

                    <!-- ═══════════════════════════════════════════════════════
                         SECTION 1: Event & Contact Details (Merged)
                         ═══════════════════════════════════════════════════════ -->
                    <div class="rw-accordion-card is-open is-active" id="section-1" data-section="1">
                        <div class="rw-accordion-header" onclick="RW.toggleSection(1)" role="button" tabindex="0">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <span class="rw-sec-badge" id="badge-1">1</span>
                                <div class="rw-sec-title-wrap text-truncate">
                                    <h3 class="rw-sec-title mb-0">Event &amp; Contact Details</h3>
                                    <p class="rw-sec-sub mb-0 text-truncate">Tell us what you're planning, your schedule, and contact information.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="rw-sec-summary-pill d-none" id="sec-1-summary"></span>
                                <i class="bi bi-chevron-down rw-sec-toggle-icon"></i>
                            </div>
                        </div>

                        <div class="rw-accordion-collapse" id="collapse-1">
                            <div class="rw-accordion-body">

                                <!-- Part A: Event Details -->
                                <div class="rw-subcard-group mb-4">
                                    <div class="rw-subcard-head mb-3">
                                        <i class="bi bi-stars text-primary me-2"></i>
                                        <strong>Event Information</strong>
                                    </div>

                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="rw-field-label mb-0" for="event_title">Event Title <span class="text-danger">*</span></label>
                                            <div class="rw-char-count text-muted small"><span id="event_title_count">0</span>/50</div>
                                        </div>
                                        <input type="text" name="event_title" id="event_title" class="rw-input" maxlength="50" placeholder="e.g. Annual Leadership Summit 2026" autocomplete="off" required>
                                        <div class="rw-field-hint">Give your event a name you'll recognize in your bookings (max 50 chars, letters and numbers only).</div>
                                    </div>

                                    <!-- Purpose of Reservation -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="rw-field-label mb-0" for="reason">Purpose of Reservation <span class="text-danger">*</span></label>
                                            <div class="rw-char-count text-muted small"><span id="reason_count">0</span>/400</div>
                                        </div>
                                        <textarea name="reason" id="reason" class="rw-input rw-textarea" rows="3" maxlength="400" placeholder="Enter the purpose of your reservation (e.g. Annual General Assembly, Seminar, Conference, Workshop, Rehearsal, Sports Tournament…)" required></textarea>
                                        <div class="rw-field-hint">Specify the purpose and planned activity for this reservation (max 400 chars, no emojis or special symbols).</div>
                                    </div>

                                    <!-- Schedule row -->
                                    <div class="mb-4">
                                        <label class="rw-field-label">Schedule <span class="text-danger">*</span></label>

                                        <!-- Custom schedule picker trigger -->
                                        <button type="button" class="rw-sched-picker" id="rw-sched-trigger" onclick="RW.openSchedPicker()" aria-label="Open date &amp; time picker">
                                            <div class="rw-sched-picker-main">
                                                <i class="bi bi-calendar-week"></i>
                                                <span id="rw-sched-display">Pick your date &amp; time</span>
                                            </div>
                                            <span class="rw-sched-picker-dur" id="rw-sched-display-dur"></span>
                                        </button>
                                        <div class="rw-sched-summary" id="rw-sched-summary"></div>

                                        <!-- Hidden native inputs keep the form contract -->
                                        <input type="date" name="start_date" id="start_date" class="d-none" min="<?php echo e($reservationSettings['payment_earliest_date'] ?? date('Y-m-d')); ?>" max="<?php echo e(date('Y-m-d', strtotime('+1 year'))); ?>" required>
                                        <input type="date" name="end_date" id="end_date" class="d-none" min="<?php echo e($reservationSettings['payment_earliest_date'] ?? date('Y-m-d')); ?>" max="<?php echo e(date('Y-m-d', strtotime('+1 year'))); ?>" required>
                                        <input type="time" name="start_time" id="start_time" class="d-none" step="900" required>
                                        <input type="time" name="end_time" id="end_time" class="d-none" step="900" required>

                                        <!-- Duration chips -->
                                        <div class="rw-dur-row">
                                            <span class="rw-dur-label">Quick duration</span>
                                            <button type="button" class="rw-dur-chip" data-hours="2">2 hrs</button>
                                            <button type="button" class="rw-dur-chip" data-hours="4">4 hrs</button>
                                            <button type="button" class="rw-dur-chip" data-hours="8">8 hrs</button>
                                            <button type="button" class="rw-dur-chip" data-hours="12">Full Day</button>
                                        </div>

                                        <div class="rw-field-hint" id="schedule-hint">Pick the window your event needs — venue availability updates live.</div>
                                        <div class="rw-field-note rw-field-note-error d-none mt-2" id="schedule-note"></div>
                                    </div>

                                    <!-- Attendees -->
                                    <div class="mb-4">
                                        <label class="rw-field-label">Expected Attendees <span class="text-danger">*</span></label>
                                        <div class="rw-stepper-row">
                                            <button type="button" class="rw-stepper-btn" onclick="RW.adjustPeople(-10)" aria-label="Decrease attendees">
                                                <i class="bi bi-dash-lg"></i>
                                            </button>
                                            <input type="number" name="number_of_people" id="number_of_people" class="rw-stepper-value" min="1" max="10000" value="" placeholder="0" required>
                                            <button type="button" class="rw-stepper-btn" onclick="RW.adjustPeople(10)" aria-label="Increase attendees">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                            <div class="rw-field-hint ms-3 align-self-center" id="people-hint">Enter your expected headcount — we'll rank venues that fit.</div>
                                        </div>
                                    </div>

                                    <!-- Supporting Documents -->
                                    <div class="mt-4 pt-3 border-top">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="rw-field-label mb-0" for="rw_documents">
                                                <i class="bi bi-file-earmark-arrow-up me-1 text-primary"></i> Supporting Documents <span class="badge bg-light text-secondary border rounded-pill ms-2 fw-semibold">Optional</span>
                                            </label>
                                            <span class="small text-muted" id="rw-doc-count-badge">0 / 5 files attached</span>
                                        </div>
                                        <div class="rw-field-hint mb-3">
                                            Attach formal request letters, activity proposals, or official endorsement papers (PDF, DOC, DOCX, PNG, JPG up to 10MB each, max 5 files).
                                        </div>

                                        <div class="rw-dropzone" id="rw-dropzone" tabindex="0" role="button" aria-label="Upload supporting documents">
                                            <input type="file" id="rw_documents" name="documents[]" multiple class="d-none" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.webp">
                                            <div class="rw-dropzone-inner">
                                                <div class="rw-dropzone-icon">
                                                    <i class="bi bi-cloud-arrow-up"></i>
                                                </div>
                                                <div class="rw-dropzone-text">
                                                    <span class="fw-bold text-primary">Click to browse</span> or drag &amp; drop files here
                                                </div>
                                                <div class="rw-dropzone-sub">
                                                    Supports PDF, Word (.doc, .docx), and images up to 10MB per file (max 5 files)
                                                </div>
                                            </div>
                                        </div>

                                        <div class="rw-file-list d-none mt-3" id="rw-file-list"></div>
                                    </div>
                                </div>

                                <!-- Part B: Contact Information (Moved to Step 1 per Production Office requirement) -->
                                <div class="rw-subcard-group mt-4 pt-3 border-top">
                                    <div class="rw-subcard-head mb-3">
                                        <i class="bi bi-person-lines-fill text-primary me-2"></i>
                                        <strong>Contact &amp; Reserving Party</strong>
                                    </div>

                                    <!-- Reserved By -->
                                    <div class="rw-section-card mb-4">
                                        <div class="rw-section-card-head"><i class="bi bi-person-badge"></i> Reserved By (Contact Person)</div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="rw-field-label">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="reserved_by_name" id="reserved_by_name" class="rw-input" value="<?php echo e(Auth::user()->first_name ? Auth::user()->first_name . ' ' . Auth::user()->last_name : Auth::user()->name); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="rw-field-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="reserved_by_email" id="reserved_by_email" class="rw-input" value="<?php echo e(Auth::user()->email); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="rw-field-label">Contact Number <span class="text-danger">*</span></label>
                                                <?php if (isset($component)) { $__componentOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.international-phone-input','data' => ['name' => 'reserved_by_phone','id' => 'reserved_by_phone','value' => Auth::user()->contact_number ?? '','required' => true,'theme' => 'reserve','helper' => 'Select country code and enter digits for booking notifications']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('international-phone-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'reserved_by_phone','id' => 'reserved_by_phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(Auth::user()->contact_number ?? ''),'required' => true,'theme' => 'reserve','helper' => 'Select country code and enter digits for booking notifications']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f)): ?>
<?php $attributes = $__attributesOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f; ?>
<?php unset($__attributesOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f)): ?>
<?php $component = $__componentOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f; ?>
<?php unset($__componentOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f); ?>
<?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                        $defaultEndUserType = old('end_user_type');
                                        $defaultEndUserName = old('end_user_name');

                                        if ($defaultEndUserType === null && $defaultEndUserName === null && Auth::check()) {
                                            $authUser = Auth::user();
                                            $isInternal = ($clientType === 'internal' || ($authUser->client_type ?? null) === 'internal');
                                            $isExternal = ($clientType === 'external' || ($authUser->client_type ?? null) === 'external');

                                            if ($isInternal && !empty(trim($authUser->college_department ?? ''))) {
                                                $defaultEndUserType = 'college';
                                                $defaultEndUserName = trim($authUser->college_department);
                                            } elseif ($isExternal && !empty(trim($authUser->organization_name ?? ''))) {
                                                $defaultEndUserType = 'organization';
                                                $defaultEndUserName = trim($authUser->organization_name);
                                            }
                                        }
                                    ?>

                                    <!-- End User -->
                                    <div class="rw-section-card mb-4">
                                        <div class="rw-section-card-head"><i class="bi bi-people"></i> End User (Who benefits from this reservation?)</div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="rw-field-label">Type <span class="text-danger">*</span></label>
                                                <div class="rw-type-grid">
                                                    <label class="rw-type-card">
                                                        <input type="radio" name="end_user_type" value="college" required <?php echo e($defaultEndUserType === 'college' ? 'checked' : ''); ?>>
                                                        <span class="rw-type-card-body">
                                                            <i class="bi bi-building"></i>
                                                            <span>College</span>
                                                        </span>
                                                    </label>
                                                    <label class="rw-type-card">
                                                        <input type="radio" name="end_user_type" value="department" required <?php echo e($defaultEndUserType === 'department' ? 'checked' : ''); ?>>
                                                        <span class="rw-type-card-body">
                                                            <i class="bi bi-diagram-3"></i>
                                                            <span>Department</span>
                                                        </span>
                                                    </label>
                                                    <label class="rw-type-card">
                                                        <input type="radio" name="end_user_type" value="organization" required <?php echo e($defaultEndUserType === 'organization' ? 'checked' : ''); ?>>
                                                        <span class="rw-type-card-body">
                                                            <i class="bi bi-people-fill"></i>
                                                            <span>Organization</span>
                                                        </span>
                                                    </label>
                                                    <label class="rw-type-card">
                                                        <input type="radio" name="end_user_type" value="personal" required <?php echo e($defaultEndUserType === 'personal' ? 'checked' : ''); ?>>
                                                        <span class="rw-type-card-body">
                                                            <i class="bi bi-person"></i>
                                                            <span>Personal</span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="rw-field-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="end_user_name" id="end_user_name" class="rw-input" placeholder="e.g. College of Engineering" value="<?php echo e($defaultEndUserName ?? ''); ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if($clientType === 'internal' && $reservationSettings['require_endorsement_internal']): ?>
                                    <div class="rw-section-card mb-4 rw-endorse-card">
                                        <div class="rw-section-card-head"><i class="bi bi-award"></i> Endorsement</div>
                                        <p class="text-muted small mb-3">As an internal user, your reservation needs endorsement from a department head or dean.</p>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="rw-field-label">Endorsed By (Name)</label>
                                                <input type="text" name="endorsed_by_name" id="endorsed_by_name" class="rw-input" placeholder="e.g. Dr. Maria Santos">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="rw-field-label">Position / Title</label>
                                                <input type="text" name="endorsed_by_position" id="endorsed_by_position" class="rw-input" placeholder="e.g. Department Head, Dean">
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Action button to advance to Section 2 -->
                                <div class="rw-sec-footer mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="button" class="btn rw-btn-primary px-4 py-2.5 fw-bold" onclick="RW.completeSection(1)">
                                        Check Available Venues <i class="bi bi-arrow-down-circle ms-1"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════════
                         SECTION 2: Choose a Venue
                         ═══════════════════════════════════════════════════════ -->
                    <div class="rw-accordion-card is-locked" id="section-2" data-section="2">
                        <div class="rw-accordion-header" onclick="RW.toggleSection(2)" role="button" tabindex="0">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <span class="rw-sec-badge" id="badge-2"><i class="bi bi-lock-fill"></i></span>
                                <div class="rw-sec-title-wrap text-truncate">
                                    <h3 class="rw-sec-title mb-0">Choose a Venue</h3>
                                    <p class="rw-sec-sub mb-0 text-truncate">Venues ranked by fit for your event and checked against your exact schedule.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="rw-sec-lock-badge" id="sec-2-lock"><i class="bi bi-lock-fill"></i> Locked</span>
                                <span class="rw-sec-summary-pill d-none" id="sec-2-summary"></span>
                                <i class="bi bi-chevron-down rw-sec-toggle-icon"></i>
                            </div>
                        </div>

                        <div class="rw-accordion-collapse" id="collapse-2">
                            <div class="rw-accordion-body">
                                <div class="rw-match-summary mb-3" id="match-summary">
                                    <i class="bi bi-hourglass-split"></i>
                                    <span id="match-summary-text">Searching venues…</span>
                                </div>

                                <!-- Global blackout / no-schedule states -->
                                <div class="rw-empty d-none" id="venue-empty">
                                    <i class="bi bi-calendar2-x"></i>
                                    <h6 class="fw-bold">No schedule configured yet</h6>
                                    <p class="text-muted small mb-0">Set your dates and time in Section 1, and we'll show you exactly which spaces are free.</p>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill fw-bold mt-2" onclick="RW.openSection(1)">Edit Event &amp; Schedule</button>
                                </div>

                                <div class="rw-venue-list" id="venue-list">
                                    <!-- venue cards rendered by JS -->
                                </div>

                                <div class="rw-venue-select-hint d-none mt-3" id="venue-select-hint">
                                    <i class="bi bi-hand-index-thumb"></i> Click any <strong>available</strong> venue card to select it and continue to logistics.
                                </div>

                                <div class="rw-sec-footer mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" id="sec-2-footer">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill fw-bold" onclick="RW.openSection(1)">
                                        <i class="bi bi-arrow-left me-1"></i> Back to Event Details
                                    </button>
                                    <button type="button" class="btn rw-btn-primary px-4 py-2.5 fw-bold" onclick="RW.completeSection(2)" id="sec-2-continue-btn">
                                        Continue to Logistics <i class="bi bi-arrow-down-circle ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════════
                         SECTION 3: Logistics & Arrangements
                         ═══════════════════════════════════════════════════════ -->
                    <div class="rw-accordion-card is-locked" id="section-3" data-section="3">
                        <div class="rw-accordion-header" onclick="RW.toggleSection(3)" role="button" tabindex="0">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <span class="rw-sec-badge" id="badge-3"><i class="bi bi-lock-fill"></i></span>
                                <div class="rw-sec-title-wrap text-truncate">
                                    <h3 class="rw-sec-title mb-0">Logistics &amp; Arrangements</h3>
                                    <p class="rw-sec-sub mb-0 text-truncate">Customize furniture, audio/visual gear, staging, and utilities.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="rw-sec-lock-badge" id="sec-3-lock"><i class="bi bi-lock-fill"></i> Locked</span>
                                <span class="rw-sec-summary-pill d-none" id="sec-3-summary"></span>
                                <i class="bi bi-chevron-down rw-sec-toggle-icon"></i>
                            </div>
                        </div>

                        <div class="rw-accordion-collapse" id="collapse-3">
                            <div class="rw-accordion-body">
                                <!-- Live selection summary bar -->
                                <div class="rw-logi-summary-bar mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rw-logi-summary-icon"><i class="bi bi-box-seam-fill"></i></div>
                                        <div>
                                            <div class="rw-logi-summary-title" id="logi-selection-summary">No arrangements selected</div>
                                            <div class="rw-logi-summary-sub">Optional add-ons for your event · <span id="logi-selection-cost">₱0.00 extra</span></div>
                                        </div>
                                    </div>
                                    <button type="button" class="rw-logi-reset-btn d-none" id="logi-clear-btn" onclick="RW.clearAllLogi()">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset all
                                    </button>
                                </div>

                                <!-- Modern Split Category Sidebar + Independent Scroll Viewport -->
                                <div class="rw-logi-layout">
                                    <!-- Category Sidebar (Left) -->
                                    <aside class="rw-logi-sidebar">
                                        <div class="rw-logi-sidebar-head">
                                            <i class="bi bi-funnel-fill"></i>
                                            <span>Categories</span>
                                        </div>
                                        <div class="rw-logi-nav">
                                            <button type="button" class="rw-logi-nav-item active" data-filter="all" onclick="RW.filterLogi('all')">
                                                <span class="rw-logi-nav-icon"><i class="bi bi-grid-3x3-gap-fill"></i></span>
                                                <span class="rw-logi-nav-name">All Items</span>
                                                <span class="rw-logi-nav-badge d-none" id="tab-count-all">0</span>
                                            </button>
                                            <?php $__currentLoopData = $logisticalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $catSlug = \Illuminate\Support\Str::slug($category);
                                                $catIcon = match(strtolower($category)) {
                                                    'furniture' => 'bi-chair',
                                                    'audio/visual', 'audio / visual' => 'bi-speaker-fill',
                                                    'staging' => 'bi-easel-fill',
                                                    'utilities' => 'bi-lightning-charge-fill',
                                                    default => 'bi-box-seam-fill'
                                                };
                                            ?>
                                            <button type="button" class="rw-logi-nav-item" data-filter="<?php echo e($catSlug); ?>" onclick="RW.filterLogi('<?php echo e($catSlug); ?>')">
                                                <span class="rw-logi-nav-icon"><i class="bi <?php echo e($catIcon); ?>"></i></span>
                                                <span class="rw-logi-nav-name"><?php echo e($category); ?></span>
                                                <span class="rw-logi-nav-count"><?php echo e(count($items)); ?></span>
                                                <span class="rw-logi-nav-badge d-none" id="tab-count-<?php echo e($catSlug); ?>">0</span>
                                            </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </aside>

                                    <!-- Scrollable Items Viewport (Right) -->
                                    <div class="rw-logi-viewport" id="rwLogiViewport">
                                        <?php $__currentLoopData = $logisticalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $catSlug = \Illuminate\Support\Str::slug($category); ?>
                                        <div class="rw-logi-group mb-4" data-category-slug="<?php echo e($catSlug); ?>" id="logi-group-<?php echo e($catSlug); ?>">
                                            <div class="rw-logi-group-head">
                                                <i class="bi bi-tag-fill"></i>
                                                <span><?php echo e($category ?? 'Other'); ?></span>
                                                <span class="rw-logi-group-count"><?php echo e(count($items)); ?> <?php echo e(count($items) === 1 ? 'item' : 'items'); ?></span>
                                            </div>
                                            <div class="rw-logi-grid">
                                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $itemIcon = match(true) {
                                                        stripos($item->name, 'chair') !== false => 'bi-person-workspace',
                                                        stripos($item->name, 'table') !== false => 'bi-table',
                                                        stripos($item->name, 'sound') !== false => 'bi-speaker-fill',
                                                        stripos($item->name, 'led') !== false => 'bi-tv-fill',
                                                        stripos($item->name, 'projector') !== false => 'bi-projector-fill',
                                                        stripos($item->name, 'microphone') !== false || stripos($item->name, 'mic') !== false => 'bi-mic-fill',
                                                        stripos($item->name, 'podium') !== false || stripos($item->name, 'lectern') !== false => 'bi-easel-fill',
                                                        stripos($item->name, 'stage') !== false || stripos($item->name, 'platform') !== false => 'bi-layers-fill',
                                                        stripos($item->name, 'generator') !== false => 'bi-lightning-charge-fill',
                                                        stripos($item->name, 'cord') !== false || stripos($item->name, 'outlet') !== false => 'bi-plug-fill',
                                                        default => 'bi-box-seam'
                                                    };
                                                ?>
                                                <div class="rw-logi-item" data-item-id="<?php echo e($item->id); ?>" data-price="<?php echo e($item->price); ?>" data-max="<?php echo e($item->max_quantity); ?>" data-name="<?php echo e(e($item->name)); ?>" data-unit="<?php echo e($item->unit_label); ?>" data-category="<?php echo e($catSlug); ?>">
                                                    <div class="rw-logi-item-header">
                                                        <div class="rw-logi-icon-badge"><i class="bi <?php echo e($itemIcon); ?>"></i></div>
                                                        <div class="rw-logi-item-info">
                                                            <div class="rw-logi-name"><?php echo e($item->name); ?></div>
                                                            <div class="rw-logi-unit-hint">Limit: max <?php echo e(number_format($item->max_quantity)); ?> <?php echo e($item->unit_label ?? 'pc'); ?></div>
                                                        </div>
                                                        <div class="rw-logi-price-tag <?php echo e((float)$item->price > 0 ? 'rw-price-paid' : 'rw-price-free'); ?>">
                                                            <?php echo e((float)$item->price > 0 ? '₱' . number_format($item->price, 2) . '/' . $item->unit_label : 'Included'); ?>

                                                        </div>
                                                    </div>

                                                    <div class="rw-logi-controls">
                                                        <button type="button" class="rw-qty-btn" onclick="RW.logiQty('<?php echo e($item->id); ?>', -1)" aria-label="Decrease <?php echo e(e($item->name)); ?>">
                                                            <i class="bi bi-dash-lg"></i>
                                                        </button>
                                                        <input type="number" class="rw-qty-input" id="rw-qty-<?php echo e($item->id); ?>" value="0" min="0" max="<?php echo e($item->max_quantity); ?>" data-item-id="<?php echo e($item->id); ?>" data-max="<?php echo e($item->max_quantity); ?>" data-price="<?php echo e((float)$item->price); ?>" data-name="<?php echo e(e($item->name)); ?>" oninput="RW.qtyInputChanged('<?php echo e($item->id); ?>', this.value)" aria-label="Quantity for <?php echo e(e($item->name)); ?>">
                                                        <button type="button" class="rw-qty-btn" onclick="RW.logiQty('<?php echo e($item->id); ?>', 1)" aria-label="Increase <?php echo e(e($item->name)); ?>">
                                                            <i class="bi bi-plus-lg"></i>
                                                        </button>

                                                        <?php if($item->max_quantity >= 50): ?>
                                                        <div class="rw-logi-quick-adds">
                                                            <button type="button" class="rw-quick-add-btn" onclick="RW.logiQty('<?php echo e($item->id); ?>', 10)">+10</button>
                                                            <button type="button" class="rw-quick-add-btn" onclick="RW.logiQty('<?php echo e($item->id); ?>', 50)">+50</button>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <div class="rw-sec-footer mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill fw-bold" onclick="RW.openSection(2)">
                                        <i class="bi bi-arrow-left me-1"></i> Back to Choose Venue
                                    </button>
                                    <button type="button" class="btn rw-btn-primary px-4 py-2.5 fw-bold" onclick="RW.completeSection(3)">
                                        Confirm Arrangements &amp; Review Booking <i class="bi bi-arrow-down-circle ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════════
                         SECTION 4: Review & Policy Agreement
                         ═══════════════════════════════════════════════════════ -->
                    <div class="rw-accordion-card is-locked" id="section-4" data-section="4">
                        <div class="rw-accordion-header" onclick="RW.toggleSection(4)" role="button" tabindex="0">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <span class="rw-sec-badge" id="badge-4"><i class="bi bi-lock-fill"></i></span>
                                <div class="rw-sec-title-wrap text-truncate">
                                    <h3 class="rw-sec-title mb-0">Review &amp; Policy Agreement</h3>
                                    <p class="rw-sec-sub mb-0 text-truncate">Verify all booking details, itemized quotation, and accept reservation policies.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="rw-sec-lock-badge" id="sec-4-lock"><i class="bi bi-lock-fill"></i> Locked</span>
                                <span class="rw-sec-summary-pill d-none" id="sec-4-summary"></span>
                                <i class="bi bi-chevron-down rw-sec-toggle-icon"></i>
                            </div>
                        </div>

                        <div class="rw-accordion-collapse" id="collapse-4">
                            <div class="rw-accordion-body">

                                <div class="rw-rev-grid mb-4">
                                    <div class="rw-rev-box" onclick="RW.openSection(1)" role="button">
                                        <div class="rw-rev-box-head">
                                            <i class="bi bi-stars"></i> Event
                                            <span class="rw-rev-edit"><i class="bi bi-pencil"></i> Edit</span>
                                        </div>
                                        <div class="rw-rev-title" id="rev-event">—</div>
                                        <div class="rw-rev-sub" id="rev-purpose">—</div>
                                    </div>
                                    <div class="rw-rev-box" onclick="RW.openSection(1)" role="button">
                                        <div class="rw-rev-box-head">
                                            <i class="bi bi-calendar-event"></i> Schedule
                                            <span class="rw-rev-edit"><i class="bi bi-pencil"></i> Edit</span>
                                        </div>
                                        <div class="rw-rev-title" id="rev-schedule">—</div>
                                        <div class="rw-rev-sub" id="rev-duration">—</div>
                                    </div>
                                    <div class="rw-rev-box" onclick="RW.openSection(1)" role="button">
                                        <div class="rw-rev-box-head">
                                            <i class="bi bi-people-fill"></i> Attendees
                                            <span class="rw-rev-edit"><i class="bi bi-pencil"></i> Edit</span>
                                        </div>
                                        <div class="rw-rev-title" id="rev-people">—</div>
                                        <div class="rw-rev-sub" id="rev-fit">—</div>
                                    </div>
                                    <div class="rw-rev-box" onclick="RW.openSection(2)" role="button">
                                        <div class="rw-rev-box-head">
                                            <i class="bi bi-geo-alt"></i> Venue
                                            <span class="rw-rev-edit"><i class="bi bi-pencil"></i> Edit</span>
                                        </div>
                                        <div class="rw-rev-title" id="rev-venue">—</div>
                                        <div class="rw-rev-sub" id="rev-venue-sub">—</div>
                                    </div>
                                    <div class="rw-rev-box" onclick="RW.openSection(1)" role="button">
                                        <div class="rw-rev-box-head">
                                            <i class="bi bi-person-lines-fill"></i> Contact &amp; Beneficiary
                                            <span class="rw-rev-edit"><i class="bi bi-pencil"></i> Edit</span>
                                        </div>
                                        <div class="rw-rev-title" id="rev-contact">—</div>
                                        <div class="rw-rev-sub" id="rev-enduser">—</div>
                                    </div>
                                    <div class="rw-rev-box" onclick="RW.openSection(3)" role="button">
                                        <div class="rw-rev-box-head">
                                            <i class="bi bi-tools"></i> Logistics
                                            <span class="rw-rev-edit"><i class="bi bi-pencil"></i> Edit</span>
                                        </div>
                                        <div class="rw-rev-title" id="rev-logi">—</div>
                                        <div class="rw-rev-sub" id="rev-logi-sub">—</div>
                                    </div>
                                    <div class="rw-rev-box" onclick="RW.openSection(1)" role="button">
                                        <div class="rw-rev-box-head">
                                            <i class="bi bi-file-earmark-arrow-up"></i> Documents
                                            <span class="rw-rev-edit"><i class="bi bi-pencil"></i> Edit</span>
                                        </div>
                                        <div class="rw-rev-title" id="rev-docs-title">None attached</div>
                                        <div class="rw-rev-sub" id="rev-docs-sub">Optional formal request letters or proposals</div>
                                    </div>
                                </div>

                                <!-- Quotation -->
                                <div class="rw-quot-card mb-4">
                                    <div class="rw-quot-card-head">
                                        <i class="bi bi-receipt-cutoff"></i> Quotation Breakdown
                                        <span class="rw-quot-status" id="rev-avail-status"></span>
                                    </div>
                                    <div class="rw-quot-body">
                                        <table class="rw-quot-table" id="quot-table">
                                            <tbody id="quot-body">
                                                <tr><td colspan="2" class="rw-quot-empty">Calculating…</td></tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="rw-quot-sub">
                                                    <td>Subtotal</td>
                                                    <td id="quot-subtotal">₱0.00</td>
                                                </tr>
                                                <tr class="rw-quot-deposit d-none" id="quot-deposit-row">
                                                    <td>Security deposit <span class="fw-normal text-muted">(refundable)</span></td>
                                                    <td id="quot-deposit">₱0.00</td>
                                                </tr>
                                                <tr class="rw-quot-total-row">
                                                    <td>Total Amount Due</td>
                                                    <td id="quot-total">₱0.00</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <?php if($clientType === 'internal' && $reservationSettings['internal_fee_waived']): ?>
                                    <div class="rw-quot-waived">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>As an internal university user, venue rental and arrangement fees are waived for your reservation.</span>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="rw-rev-reason mb-3">
                                    <div class="rw-field-label">Purpose</div>
                                    <p class="mb-0 fw-medium" id="rev-reason">—</p>
                                </div>

                                <?php if(!empty($reservationSettings['cancellation_policy_text'])): ?>
                                <div class="rw-policy-alert mb-3">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div><strong>Cancellation Policy:</strong> <?php echo e($reservationSettings['cancellation_policy_text']); ?> <a href="javascript:void(0)" onclick="RW.openCancelModal(event)" class="text-decoration-underline ms-1 fw-semibold text-dark">Details</a></div>
                                </div>
                                <?php endif; ?>

                                <div class="rw-policy-alert rw-policy-warn d-none mb-3" id="rev-capacity-warning">
                                    <i class="bi bi-exclamation-diamond-fill text-warning fs-5"></i>
                                    <div><strong>Capacity Responsibility Notice:</strong> Your expected attendees (<span id="rev-cap-people">0</span>) exceed <span id="rev-cap-venue">this venue</span>'s standard capacity (<span id="rev-cap-limit">0</span>). By submitting, you acknowledge that you are responsible for crowd coordination, space constraints, and safety accommodations.</div>
                                </div>

                                <div class="rw-terms-card p-3 rounded-3 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                        <span class="small fw-bold text-dark d-flex align-items-center gap-1.5">
                                            <i class="bi bi-shield-check text-primary fs-6"></i> Reservation Agreement &amp; Policy Articles
                                        </span>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" onclick="RW.openTermsModal(event)" style="font-size:0.78rem;font-weight:600;">
                                            <i class="bi bi-journal-text me-1"></i> Read All Articles
                                        </button>
                                    </div>
                                    <div class="small text-muted mb-2.5" style="font-size:0.78rem;line-height:1.45;">
                                        By reserving, you agree to adhere strictly to all university facility rules, safety protocols, noise guidelines, damage liabilities, and schedule allowances (including overnight event requirements).
                                    </div>
                                    <label class="rw-agree mb-0" for="agreement_accepted_check">
                                        <input type="checkbox" id="agreement_accepted_check" required>
                                        <span>I have read, understood, and agree to the <a href="javascript:void(0)" onclick="RW.openTermsModal(event)" class="text-primary text-decoration-underline fw-bold">Facility Reservation Terms, Conditions, and Articles</a>, and the <a href="javascript:void(0)" onclick="RW.openCancelModal(event)" class="text-primary text-decoration-underline fw-bold">Cancellation Policy</a>. <span class="text-danger">*</span></span>
                                    </label>
                                </div>

                                <div class="rw-sec-footer mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill fw-bold" onclick="RW.openSection(3)">
                                        <i class="bi bi-arrow-left me-1"></i> Back to Arrangements
                                    </button>
                                    <button type="button" class="btn rw-btn-primary px-4 py-2.5 fw-bold" onclick="RW.completeSection(4)">
                                        Proceed to Payment Method <i class="bi bi-arrow-down-circle ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════════
                         SECTION 5: Payment Method & Final Submission
                         ═══════════════════════════════════════════════════════ -->
                    <div class="rw-accordion-card is-locked" id="section-5" data-section="5">
                        <div class="rw-accordion-header" onclick="RW.toggleSection(5)" role="button" tabindex="0">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <span class="rw-sec-badge" id="badge-5"><i class="bi bi-lock-fill"></i></span>
                                <div class="rw-sec-title-wrap text-truncate">
                                    <h3 class="rw-sec-title mb-0">Payment &amp; Final Submission</h3>
                                    <p class="rw-sec-sub mb-0 text-truncate">Choose how you'll settle the reservation, then submit it for admin review.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <span class="rw-sec-lock-badge" id="sec-5-lock"><i class="bi bi-lock-fill"></i> Locked</span>
                                <span class="rw-sec-summary-pill d-none" id="sec-5-summary"></span>
                                <i class="bi bi-chevron-down rw-sec-toggle-icon"></i>
                            </div>
                        </div>

                        <div class="rw-accordion-collapse" id="collapse-5">
                            <div class="rw-accordion-body">
                                <?php if($clientType === 'internal' && $reservationSettings['internal_fee_waived']): ?>
                                    <div class="rw-waived-panel mb-4">
                                        <div class="rw-waived-icon"><i class="bi bi-check-lg"></i></div>
                                        <h5 class="fw-bold mb-2">No Payment Required</h5>
                                        <p class="text-muted mb-4">As an internal university user, all fees have been waived.<br>Your reservation will be submitted directly for administration review.</p>
                                        <div class="rw-info-chip"><i class="bi bi-info-circle-fill"></i> You'll be notified via email once approved.</div>
                                    </div>
                                <?php else: ?>
                                    <?php
                                        $cashOn     = $reservationSettings['payment_method_cash_enabled']     ?? true;
                                        $paymongoOn = $reservationSettings['payment_method_paymongo_enabled'] ?? true;
                                        $enabledCount = ($cashOn ? 1 : 0) + ($paymongoOn ? 1 : 0);
                                    ?>

                                    <?php if($enabledCount === 0): ?>
                                        <div class="rw-policy-alert rw-policy-warn mb-4">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <div>
                                                <strong>Payment Temporarily Unavailable</strong><br>
                                                <span class="small text-muted">All payment options are currently disabled by the administration. Please contact the administration to proceed.</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="rw-pay-grid mb-4">
                                            <?php if($cashOn): ?>
                                            <label class="rw-pay-card <?php echo e($enabledCount === 1 ? 'rw-pay-selected' : ''); ?>" onclick="RW.selectPayment('cash')">
                                                <input type="radio" name="payment_method" value="cash" <?php echo e($enabledCount === 1 ? 'checked' : ''); ?>>
                                                <div class="rw-pay-card-body">
                                                    <div class="rw-pay-icon rw-pay-icon-cash"><i class="bi bi-cash-coin"></i></div>
                                                    <h6 class="fw-bold mb-1">Pay at Cashier</h6>
                                                    <p class="rw-pay-sub mb-0">Pay cash on-site. The payment deadline follows the university policy window.</p>
                                                </div>
                                            </label>
                                            <?php else: ?>
                                            <div class="rw-pay-card rw-pay-card-disabled" title="Cashier payment is currently suspended by management">
                                                <div class="rw-pay-card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="rw-pay-icon rw-pay-icon-cash opacity-50"><i class="bi bi-cash-coin"></i></div>
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1"><i class="bi bi-slash-circle me-1"></i> Unavailable</span>
                                                    </div>
                                                    <h6 class="fw-bold mb-1 text-muted">Pay at Cashier</h6>
                                                    <p class="rw-pay-sub mb-0 text-muted">On-site cashier payments are temporarily unavailable.</p>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <?php if($paymongoOn): ?>
                                            <label class="rw-pay-card <?php echo e($enabledCount === 1 ? 'rw-pay-selected' : ''); ?>" onclick="RW.selectPayment('paymongo')">
                                                <input type="radio" name="payment_method" value="paymongo" <?php echo e($enabledCount === 1 ? 'checked' : ''); ?>>
                                                <div class="rw-pay-card-body">
                                                    <div class="rw-pay-icon rw-pay-icon-online"><i class="bi bi-credit-card-2-front"></i></div>
                                                    <h6 class="fw-bold mb-1">Pay Online</h6>
                                                    <p class="rw-pay-sub mb-0">GCash, Maya, Cards via PayMongo. <strong>24-hour payment window.</strong></p>
                                                </div>
                                            </label>
                                            <?php else: ?>
                                            <div class="rw-pay-card rw-pay-card-disabled" title="Online payment is currently suspended by management">
                                                <div class="rw-pay-card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="rw-pay-icon rw-pay-icon-online opacity-50"><i class="bi bi-credit-card-2-front"></i></div>
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1"><i class="bi bi-slash-circle me-1"></i> Unavailable</span>
                                                    </div>
                                                    <h6 class="fw-bold mb-1 text-muted">Pay Online</h6>
                                                    <p class="rw-pay-sub mb-0 text-muted">Online payment is temporarily unavailable.</p>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($reservationSettings['security_deposit_amount'] > 0 && $enabledCount > 0): ?>
                                    <div class="rw-policy-alert rw-policy-warn mb-4">
                                        <i class="bi bi-shield-exclamation"></i>
                                        <div><strong>Security Deposit:</strong> A refundable security deposit of <strong>₱<?php echo e(number_format($reservationSettings['security_deposit_amount'], 2)); ?></strong> is required and will be collected separately before turnover.</div>
                                    </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <div class="rw-info-chip mb-4">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <span>Your reservation will be reviewed by the administration. You will receive a notification once it is approved or declined.</span>
                                </div>

                                <!-- Deadline note from quotation -->
                                <div class="rw-deadline-note d-none mb-4" id="payment-deadline-note">
                                    <i class="bi bi-clock-history"></i>
                                    <span id="payment-deadline-text"></span>
                                </div>

                                <!-- Final Submit CTA -->
                                <div class="rw-final-submit-box p-4 rounded-3 text-center border" style="background:#f8fafc;">
                                    <h5 class="fw-bold mb-1">Ready to complete your reservation?</h5>
                                    <p class="text-muted small mb-3">All sections have been validated. Click below to submit your official reservation.</p>
                                    <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill fw-bold px-4 py-2.5" onclick="RW.openSection(4)">
                                            <i class="bi bi-arrow-left me-1"></i> Back to Review
                                        </button>
                                        <button type="button" class="btn rw-btn-submit px-5 py-3 fw-bold rounded-pill shadow-sm" onclick="RW.submit()" id="final-submit-btn">
                                            <i class="bi bi-send-fill me-2"></i> Submit Reservation
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    
    <form id="rw-post-form" action="<?php echo e(route('guest.reserve.store')); ?>" method="POST" class="d-none" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="agreement_accepted" id="hf_agree" value="">
        <input type="hidden" name="arrangements" id="hf_arrangements" value="">
        <input type="hidden" name="facility_id" id="hf_facility" value="">
        <input type="hidden" name="payment_method" id="hf_payment" value="">
        <input type="hidden" name="event_title" id="hf_event_title">
        <input type="hidden" name="start_date" id="hf_start_date">
        <input type="hidden" name="end_date" id="hf_end_date">
        <input type="hidden" name="start_time" id="hf_start_time">
        <input type="hidden" name="end_time" id="hf_end_time">
        <input type="hidden" name="number_of_people" id="hf_people">
        <input type="hidden" name="reason" id="hf_reason">
        <input type="hidden" name="reserved_by_name" id="hf_rbn">
        <input type="hidden" name="reserved_by_email" id="hf_rbe">
        <input type="hidden" name="reserved_by_phone" id="hf_rbp">
        <input type="hidden" name="end_user_type" id="hf_eut">
        <input type="hidden" name="end_user_name" id="hf_eun">
        <input type="hidden" name="endorsed_by_name" id="hf_ebn">
        <input type="hidden" name="endorsed_by_position" id="hf_ebp">
    </form>

    <!-- Venue detail modal -->
    <div class="modal fade" id="venueModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rw-modal">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title fw-bold" id="vm-title">—</h5>
                        <span class="rw-vm-cat" id="vm-cat">—</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="rw-vm-hero" id="vm-hero"></div>
                    <div class="row g-3 mt-1">
                        <div class="col-6">
                            <div class="rw-vm-stat">
                                <i class="bi bi-people-fill"></i>
                                <div>
                                    <div class="rw-vm-stat-label">Capacity</div>
                                    <div class="fw-bold" id="vm-capacity">—</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rw-vm-stat">
                                <i class="bi bi-cash-stack"></i>
                                <div>
                                    <div class="rw-vm-stat-label">Rate</div>
                                    <div class="fw-bold" id="vm-rate">—</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="rw-vm-desc mt-3" id="vm-desc">—</p>
                    <h6 class="rw-vm-sect-head rw-vm-amenities-head"><i class="bi bi-check2-circle"></i> Amenities and included features</h6>
                    <div class="rw-vm-amenities" id="vm-amenities"></div>
                    <div class="rw-vm-loc mt-2"><i class="bi bi-geo-alt"></i> <span id="vm-loc">—</span></div>

                    <h6 class="rw-vm-sect-head"><i class="bi bi-clock-history"></i> Availability on your dates</h6>
                    <div class="rw-vm-timeline" id="vm-timeline"></div>
                    <div class="rw-vm-legend" id="vm-legend"></div>

                    <div class="rw-vm-hours">
                        <h6 class="rw-vm-sect-head mb-2"><i class="bi bi-sun"></i> Operating hours this weekday</h6>
                        <div id="vm-hours"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="rw-vm-price" id="vm-price">—</span>
                    <div>
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn rw-btn-gold rounded-pill fw-bold" id="vm-select-btn" onclick="RW.selectVenueFromModal()">
                            <i class="bi bi-check-lg me-1"></i> Select this venue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zero Logistics Warning Modal -->
    <div class="modal fade" id="logiZeroModal" tabindex="-1" aria-labelledby="logiZeroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:1.1rem;overflow:hidden;border:none;box-shadow:0 20px 40px rgba(0,0,0,.2);">
                <div class="modal-header" style="background:var(--rw-navy);color:#fff;border:none;padding:1.25rem 1.5rem;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:2.2rem;height:2.2rem;border-radius:50%;background:rgba(234,179,8,.15);display:flex;align-items:center;justify-content:center;color:var(--rw-gold);font-size:1.1rem;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <h5 class="modal-title fw-bold mb-0" id="logiZeroModalLabel" style="font-size:1.05rem;">No Arrangements Selected</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.5rem;">
                    <p class="text-secondary mb-3" style="font-size:0.9rem;line-height:1.5;">
                        You haven't requested any furniture, audio/visual gear, or event utilities for this booking.
                    </p>
                    <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;border:1.5px solid #e2e8f0;font-size:0.84rem;color:#334155;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill text-primary mt-0.5"></i>
                            <div>
                                <strong>Did you know?</strong> Essential items like <strong>monoblock chairs</strong>, <strong>tables</strong>, and <strong>podiums</strong> are <em>Included for Free</em> with your reservation!
                            </div>
                        </div>
                    </div>
                    <p class="mb-0 text-muted" style="font-size:0.84rem;">
                        If you continue, your venue will be booked without any setup or additional equipment. Are you sure you want to proceed?
                    </p>
                </div>
                <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:1rem 1.5rem;display:flex;justify-content:space-between;gap:0.75rem;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold" onclick="RW.confirmProceedWithoutLogi()">
                        Yes, continue without items
                    </button>
                    <button type="button" class="btn rw-btn-gold rounded-pill px-3 py-1.5 fw-bold" data-bs-dismiss="modal">
                        <i class="bi bi-plus-circle me-1"></i> Add arrangements
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date and Time Picker Modal -->
    <div class="modal fade rw-picker-modal" id="rwSchedModal" tabindex="-1" aria-labelledby="rwSchedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:1.1rem;overflow:hidden;">
                <div class="modal-header" style="background:var(--rw-navy);color:#fff;border:none;">
                    <h5 class="modal-title fw-bold" id="rwSchedModalLabel"><i class="bi bi-calendar-week me-2" style="color:var(--rw-gold)"></i>Choose your dates &amp; time</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.1rem;">
                    
                    <div class="rw-pick-banner">
                        <div class="rw-pick-banner-main">
                            <i class="bi bi-calendar-check"></i>
                            <span id="rw-pick-range">No date chosen</span>
                        </div>
                        <div class="rw-pick-banner-dur" id="rw-pick-dur">—</div>
                    </div>

                    <div class="row g-3">
                        
                        <div class="col-lg-7">
                            <div class="rw-cal-card">
                                <div class="rw-cal-policy-info mb-2 py-1.5 px-2.5 rounded-3 d-flex align-items-center gap-2" style="background:#f8fafc; border: 1.5px solid #e2e8f0; font-size:.76rem; color:#475569;">
                                    <i class="bi bi-shield-lock-fill text-warning fs-6"></i>
                                    <span><strong>Advance Policy:</strong> Earliest bookable date is <strong><?php echo e(\Carbon\Carbon::parse($reservationSettings['payment_earliest_date'] ?? date('Y-m-d'))->format('M d, Y')); ?></strong> (requires <?php echo e($reservationSettings['payment_deadline_value'] ?? 3); ?> <?php echo e($reservationSettings['payment_deadline_unit'] ?? 'days'); ?> advance notice). Earlier dates are disabled.</span>
                                </div>
                                <div class="rw-cal-head">
                                    <button type="button" class="rw-cal-nav" id="rw-cal-prev" aria-label="Previous month"><i class="bi bi-chevron-left"></i></button>
                                    <div class="rw-cal-title" id="rw-cal-title">August 2026</div>
                                    <button type="button" class="rw-cal-nav" id="rw-cal-next" aria-label="Next month"><i class="bi bi-chevron-right"></i></button>
                                </div>
                                <div class="rw-cal-dow">
                                    <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                </div>
                                <div class="rw-cal-grid" id="rw-cal-grid"></div>
                                <div class="rw-pick-hint mt-2">Tap a day for the <strong>start</strong>, then tap another for the <strong>end</strong>. Only eligible dates are selectable.</div>
                            </div>
                        </div>

                        
                        <div class="col-lg-5">
                            <div class="rw-time-card">
                                <div class="rw-pick-sect-head mb-2"><i class="bi bi-clock"></i> Event time</div>
                                <div class="rw-time-field">
                                    <label class="form-label small fw-bold mb-1" for="rw-pick-start-input">Start time</label>
                                    <input type="time" class="form-control rw-time-input" id="rw-pick-start-input" step="900" title="Type or pick start time">
                                    <div class="form-text small text-muted" style="font-size:.7rem;">Pick or type any start time (e.g. 7:30 AM).</div>
                                </div>
                                <div class="rw-time-field mt-3">
                                    <label class="form-label small fw-bold mb-1" for="rw-pick-end-input">End time</label>
                                    <input type="time" class="form-control rw-time-input" id="rw-pick-end-input" step="900" title="Type or pick end time">
                                    <div class="form-text small text-muted" style="font-size:.7rem;">Pick or type any end time (times past midnight support overnight events).</div>
                                </div>
                                <div class="rw-pick-hint mt-2" id="rw-time-hint">Freeflow time selection supported. Overnight galas and off-hours scheduling permitted.</div>
                                <div class="rw-time-venue-note mt-2 small text-muted" id="rw-time-venue-note"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--rw-border);background:#fbfcfe;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" onclick="RW.clearSched()"><i class="bi bi-x-lg me-1"></i> Clear</button>
                    <button type="button" class="btn rw-btn-gold rounded-pill fw-bold px-4" onclick="RW.applySched()"><i class="bi bi-check-lg me-1"></i> Apply schedule</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade rw-picker-modal" id="rwTermsModal" tabindex="-1" aria-labelledby="rwTermsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:1.1rem;overflow:hidden;">
                <div class="modal-header" style="background:var(--rw-navy);color:#fff;border:none;">
                    <h5 class="modal-title fw-bold" id="rwTermsModalLabel">
                        <i class="bi bi-journal-text me-2" style="color:var(--rw-gold)"></i>Facility Reservation Terms, Conditions &amp; Articles
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.4rem;font-size:0.88rem;color:#334155;line-height:1.6;">
                    <div class="p-3 mb-3 rounded-3" style="background:#f1f5f9;border:1px solid #e2e8f0;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-shield-check text-primary fs-5"></i>
                            <strong class="text-dark">Official Institutional Reservation Articles</strong>
                        </div>
                        <p class="mb-0 text-muted" style="font-size:0.8rem;">
                            All individuals, internal organizations, and external reserving parties must strictly comply with the terms and articles set forth below. Failure to observe these rules may result in immediate event cessation, security deposit forfeiture, and suspension of facility booking privileges.
                        </p>
                    </div>

                    <?php if(!empty($reservationSettings['legal_terms'])): ?>
                    <div class="p-3 rounded-3 border bg-white shadow-sm mb-3" style="white-space: pre-wrap;">
                        <?php echo nl2br(e($reservationSettings['legal_terms'])); ?>

                    </div>
                    <?php else: ?>
                    <div class="rw-articles-list d-flex flex-column gap-3">
                        <div class="p-3 rounded-3 border bg-white shadow-sm">
                            <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size:0.72rem;">Article I</span>
                                Permitted Use &amp; Reservation Scope
                            </h6>
                            <p class="mb-0 text-secondary" style="font-size:0.82rem;">
                                Facilities shall be used exclusively for the stated purpose and event program described in this application. Sub-leasing, transferring, or relinquishing reserved slots to third parties is strictly prohibited. The Reserving Party must ensure actual attendance does not exceed declared capacity and complies with university facility regulations.
                            </p>
                        </div>

                        <div class="p-3 rounded-3 border bg-white shadow-sm">
                            <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size:0.72rem;">Article II</span>
                                Schedule Adherence, Operating Hours &amp; Overnight Events
                            </h6>
                            <p class="mb-2 text-secondary" style="font-size:0.82rem;">
                                The Reserving Party must strictly observe the approved start date, end date, start time, and end time. Ingress (setup) and egress (pack-up) must be completed strictly within the reserved window.
                            </p>
                            <div class="p-2 rounded-2" style="background:#fdf2f8;border-left:3px solid #db2777;font-size:0.8rem;color:#831843;">
                                <strong>Overnight &amp; Night Events Protocol:</strong> For galas, balls, conferences, vigils, or ceremonies extending past midnight into the subsequent calendar day, the Reserving Party must appoint an on-site safety coordinator. Campus security and facility supervisors must be briefed prior to the event. Unauthorized overruns will incur pro-rated hourly penalty surcharges.
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border bg-white shadow-sm">
                            <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size:0.72rem;">Article III</span>
                                Safety, Security, Fire &amp; Capacity Compliance
                            </h6>
                            <p class="mb-0 text-secondary" style="font-size:0.82rem;">
                                Fire corridors, emergency exits, electrical breaker panels, and fire hydrants must remain entirely unobstructed. Open flames, pyrotechnics, fireworks, smoking, vaping, and unauthorized hazardous materials are strictly forbidden. If expected attendance exceeds normal facility capacity, the Reserving Party explicitly accepts full responsibility for crowd control, ushering, and safety measures.
                            </p>
                        </div>

                        <div class="p-3 rounded-3 border bg-white shadow-sm">
                            <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size:0.72rem;">Article IV</span>
                                Facility Care, Equipment &amp; Damage Liability
                            </h6>
                            <p class="mb-0 text-secondary" style="font-size:0.82rem;">
                                The Reserving Party is financially and legally liable for any damage, loss, or deterioration caused to the venue fixtures, acoustic wall treatments, flooring, audio-visual systems, lighting, and rented logistical items. Heavy duct tape, nails, screws, and damaging adhesives must not be applied to surfaces. Repair or replacement costs will be deducted from the security deposit, with any surplus billed directly.
                            </p>
                        </div>

                        <div class="p-3 rounded-3 border bg-white shadow-sm">
                            <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size:0.72rem;">Article V</span>
                                Financial Obligations, Security Deposits &amp; Payment Deadlines
                            </h6>
                            <p class="mb-0 text-secondary" style="font-size:0.82rem;">
                                Rental charges and refundable security deposits must be settled before the prescribed payment deadline. Unsettled reservations expire automatically without further notice. Following event conclusion and facility turnover inspection, the security deposit will be processed for refund within 7 to 10 business days, less any documented damage or cleaning fees.
                            </p>
                        </div>

                        <div class="p-3 rounded-3 border bg-white shadow-sm">
                            <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size:0.72rem;">Article VI</span>
                                Noise Regulations, Curfew &amp; Cleanliness Turnover
                            </h6>
                            <p class="mb-0 text-secondary" style="font-size:0.82rem;">
                                Sound levels and amplified music must conform to institutional acoustic regulations and must not interfere with adjacent campus activities. Clean-up is the immediate responsibility of the Reserving Party. Proper waste segregation (biodegradable, recyclable, residual) must be observed, and the venue returned to its pristine condition.
                            </p>
                        </div>

                        <div class="p-3 rounded-3 border bg-white shadow-sm">
                            <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary text-white rounded-pill px-2 py-1" style="font-size:0.72rem;">Article VII</span>
                                Cancellation, Rescheduling &amp; Force Majeure
                            </h6>
                            <p class="mb-0 text-secondary" style="font-size:0.82rem;">
                                Cancellations requested less than 3 days prior to the event date will result in forfeiture of the security deposit. Cancellations requested earlier receive a refund less a 10% administrative processing charge. The University reserves the authority to cancel, postpone, or relocate bookings in the event of force majeure, severe weather advisories, national declarations, or paramount institutional requirements.
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer d-flex justify-content-between" style="border-top:1px solid var(--rw-border);background:#fbfcfe;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary rounded-pill fw-bold px-4" onclick="RW.acceptTermsFromModal()">
                        <i class="bi bi-check2-circle me-1"></i> I Read &amp; Agree to All Articles
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade rw-picker-modal" id="rwCancelModal" tabindex="-1" aria-labelledby="rwCancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="border-radius:1.1rem;overflow:hidden;">
                <div class="modal-header" style="background:var(--rw-navy);color:#fff;border:none;">
                    <h5 class="modal-title fw-bold" id="rwCancelModalLabel">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Cancellation &amp; Refund Policy
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:1.3rem;font-size:0.88rem;color:#334155;line-height:1.6;">
                    <div class="p-3 mb-3 rounded-3" style="background:#fffbeb;border:1px solid #fde68a;">
                        <h6 class="fw-bold text-amber-800 mb-1" style="color:#92400e;">Cancellation Timeline Rules</h6>
                        <p class="mb-0 text-secondary" style="font-size:0.82rem;">
                            <?php echo e($reservationSettings['cancellation_policy_text'] ?? 'Cancellations made less than 3 days before the event date will result in forfeiture of the security deposit. Cancellations made more than 3 days before the event will receive a refund minus a 10% administrative fee.'); ?>

                        </p>
                    </div>
                    <ul class="mb-0 text-secondary ps-3" style="font-size:0.82rem;">
                        <li class="mb-1.5"><strong>More than 3 days notice:</strong> Security deposit is refunded via original payment channel less 10% processing fee.</li>
                        <li class="mb-1.5"><strong>Less than 3 days notice:</strong> Security deposit is forfeited to compensate for lost booking window and reserved operational scheduling.</li>
                        <li><strong>Force Majeure / University Cancellation:</strong> Full 100% refund of all fees and deposits is issued.</li>
                    </ul>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--rw-border);background:#fbfcfe;">
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Understood</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ═══════════════════════════════════════════════════════════
   SURE Single-Page Progressive Reservation Form — Midnight Navy + Gold
   ─────────────────────────────────────────────────────────── */
:root {
    --rw-navy: var(--brand-dark, #0d1b2a);
    --rw-navy-2: var(--brand-dark, #111827);
    --rw-navy-3: var(--brand-dark, #1b2a41);
    --rw-gold: var(--brand-accent, #b61111);
    --rw-gold-dark: var(--brand-accent, #b61111);
    --rw-gold-soft: rgba(var(--brand-accent-rgb, 182, 17, 17), .14);
    --rw-bg: #f4f6fa;
    --rw-card: #ffffff;
    --rw-border: #e2e8f0;
    --rw-text: var(--brand-dark, #0f172a);
    --rw-text-muted: #64748b;
    --rw-ok: #16a34a;
    --rw-partial: #f59e0b;
    --rw-bad: #dc2626;
    --rw-pending: #8b5cf6;
}
.rw-shell { background: var(--rw-bg); min-height: 100vh; }

/* Hero */
.rw-hero { background: linear-gradient(135deg, var(--rw-navy) 0%, rgba(0,0,0,0.85) 60%, var(--rw-navy-3) 100%); padding: 2.5rem 0 2.25rem; position: relative; overflow: hidden; }
.rw-hero::after { content: ""; position: absolute; right: -6rem; top: -6rem; width: 26rem; height: 26rem; background: radial-gradient(circle, rgba(var(--brand-accent-rgb, 182,17,17),.18), transparent 65%); border-radius: 50%; }
.rw-hero-title { color: #fff; font-weight: 800; font-size: 2.1rem; letter-spacing: -.02em; }
.rw-hero-sub { color: rgba(255,255,255,.7); font-size: 1rem; }
.rw-hero-badge { display: inline-flex; align-items: center; gap: .4rem; font-size: .78rem; font-weight: 700; padding: .4rem .85rem; border-radius: 999px; letter-spacing: .02em; }
.rw-badge-gold { background: var(--rw-gold); color: #fff; }
.rw-badge-success { background: rgba(22,163,74,.2); color: #16a34a; border: 1px solid rgba(22,163,74,.25); }
.rw-badge-info { background: rgba(96,165,250,.18); color: #2563eb; border: 1px solid rgba(37,99,235,.25); }

/* Alerts */
.rw-alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 0.85rem; }
.rw-draft-banner { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 0.85rem; padding: .85rem 1.1rem; }
.rw-policy-alert { display: flex; gap: .75rem; background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412; border-radius: 0.85rem; padding: 1rem 1.15rem; font-size: .9rem; }
.rw-policy-alert.rw-policy-warn { background: #fef3c7; border-color: #fcd34d; color: #92400e; }
.rw-info-chip { display: inline-flex; gap: .5rem; align-items: center; font-size: .85rem; color: var(--rw-text-muted); background: #fff; border: 1px dashed var(--rw-border); border-radius: 0.85rem; padding: .7rem 1rem; }

/* Grid Layout */
.rw-grid { display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.5rem; align-items: start; }
@media (min-width: 992px) { .rw-grid { grid-template-columns: 320px minmax(0, 1fr); align-items: start; } }
.rw-quote-col { order: 2; min-width: 0; }
.rw-main-col { order: 1; min-width: 0; }
@media (min-width: 992px) {
    .rw-quote-col {
        order: 1;
        min-width: 0;
        position: sticky;
        top: 85px;
        align-self: start;
        z-index: 1020;
    }
    .rw-main-col {
        order: 2;
        min-width: 0;
    }
}

/* Quote Panel */
.rw-quote {
    background: linear-gradient(160deg, var(--rw-navy) 0%, rgba(0,0,0,0.92) 70%, var(--rw-navy-3) 100%);
    color: #fff;
    border-radius: 1.15rem;
    padding: 1.35rem;
    box-shadow: 0 18px 45px rgba(0,0,0,.3);
    max-height: calc(100vh - 105px);
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.25) transparent;
}
.rw-quote-head { display: flex; align-items: center; gap: .6rem; font-weight: 800; font-size: 1.05rem; padding-bottom: .9rem; margin-bottom: .9rem; border-bottom: 1px solid rgba(255,255,255,.12); }
.rw-quote-head i { color: var(--rw-gold); font-size: 1.15rem; flex-shrink: 0; }
.rw-quote-step { margin-left: auto; font-size: .66rem; font-weight: 700; letter-spacing: .08em; color: var(--rw-gold); background: var(--rw-gold-soft); padding: .25rem .6rem; border-radius: 999px; flex-shrink: 0; }
.rw-quote-row { display: flex; gap: .75rem; align-items: flex-start; padding: .55rem 0; min-width: 0; }
.rw-quote-icon { color: var(--rw-gold); font-size: 1rem; margin-top: .15rem; width: 1.3rem; text-align: center; flex-shrink: 0; }
.rw-quote-row-body { min-width: 0; flex: 1; overflow: hidden; }
.rw-quote-label { font-size: .66rem; font-weight: 700; letter-spacing: .1em; color: rgba(255,255,255,.45); text-transform: uppercase; }
.rw-quote-value { font-size: .92rem; font-weight: 600; min-width: 0; word-break: break-word; overflow-wrap: anywhere; }
.rw-quote-value.rw-editable { cursor: pointer; display: flex; gap: .4rem; align-items: flex-start; }
.rw-quote-value.rw-editable:hover { color: #fed7aa; }
.rw-quote-sub { font-size: .75rem; color: rgba(255,255,255,.6); }
.rw-quote-venue { display: flex; gap: .75rem; align-items: center; background: rgba(255,255,255,.08); border-radius: .85rem; padding: .7rem; margin: .75rem 0; }
.rw-quote-venue-img { width: 3.5rem; height: 3.5rem; border-radius: .55rem; background-size: cover; background-position: center; flex-shrink: 0; background-color: rgba(255,255,255,.1); }
.rw-quote-venue-body { flex: 1; min-width: 0; }
.rw-quote-venue-name { font-weight: 700; font-size: .92rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.rw-quote-venue-cap { font-size: .75rem; color: rgba(255,255,255,.65); }
.rw-quote-venue-price { font-weight: 700; color: var(--rw-gold); font-size: .88rem; flex-shrink: 0; }
.rw-quote-lines { border-top: 1px dashed rgba(255,255,255,.15); padding-top: .75rem; margin-top: .75rem; font-size: .82rem; }
.rw-quote-line-label { font-size: .66rem; font-weight: 700; letter-spacing: .08em; color: rgba(255,255,255,.45); text-transform: uppercase; margin-bottom: .4rem; }
.rw-quote-line { display: flex; justify-content: space-between; padding: .25rem 0; color: rgba(255,255,255,.8); }
.rw-quote-total { border-top: 1px solid rgba(255,255,255,.18); padding-top: .9rem; margin-top: .9rem; display: flex; justify-content: space-between; align-items: flex-end; }
.rw-quote-total-label { font-size: .78rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
.rw-quote-total-sub { display: block; font-size: .66rem; font-weight: 400; color: rgba(255,255,255,.5); }
.rw-quote-total-value { font-size: 1.45rem; font-weight: 800; color: var(--rw-gold); letter-spacing: -.02em; }
.rw-quote-logi-list { display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.72rem; color: rgba(255,255,255,0.7); max-height: 120px; overflow-y: auto; }

/* ═══ Progress Tracker Bar ═══ */
.rw-tracker-card { background: #fff; border: 1px solid var(--rw-border); border-radius: 1rem; padding: 0.85rem 1.25rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
.rw-tracker-steps { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; overflow-x: auto; scrollbar-width: none; }
.rw-tracker-steps::-webkit-scrollbar { display: none; }
.rw-tracker-btn { display: inline-flex; align-items: center; gap: 0.55rem; background: transparent; border: none; padding: 0.4rem 0.65rem; border-radius: 999px; cursor: pointer; transition: all 0.2s ease; white-space: nowrap; font-size: 0.82rem; font-weight: 600; color: #64748b; }
.rw-tracker-btn:hover { background: #f1f5f9; color: var(--rw-navy); }
.rw-tracker-num { width: 1.6rem; height: 1.6rem; border-radius: 50%; background: #e2e8f0; color: #475569; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; }
.rw-tracker-btn.active { color: var(--rw-navy); background: rgba(37,99,235,0.08); font-weight: 700; }
.rw-tracker-btn.active .rw-tracker-num { background: var(--rw-navy); color: var(--rw-gold); }
.rw-tracker-btn.completed { color: #16a34a; }
.rw-tracker-btn.completed .rw-tracker-num { background: #16a34a; color: #fff; }
.rw-tracker-div { width: 1.5rem; height: 2px; background: #e2e8f0; flex-shrink: 0; }

/* ═══ Accordion Sections ═══ */
.rw-accordion-card {
    background: #ffffff;
    border: 1.5px solid var(--rw-border);
    border-radius: 1.15rem;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 1.35rem;
    overflow: hidden;
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
}
.rw-accordion-card.is-active {
    border-color: rgba(37,99,235,0.45);
    box-shadow: 0 10px 32px -4px rgba(13,27,42,0.08), 0 0 0 1px rgba(37,99,235,0.12);
}
.rw-accordion-card.is-completed {
    border-color: #cbd5e1;
}

/* Locked Section State */
.rw-accordion-card.is-locked {
    background: #f8fafc;
    border: 1.5px dashed #cbd5e1;
    box-shadow: none;
    opacity: 0.65;
}
.rw-accordion-card.is-locked .rw-accordion-header {
    cursor: not-allowed;
    background: #f8fafc;
}
.rw-accordion-card.is-locked .rw-accordion-header:hover {
    background: #f1f5f9;
}
.rw-accordion-card.is-locked .rw-sec-badge {
    background: #e2e8f0;
    color: #94a3b8;
    box-shadow: none;
}
.rw-accordion-card.is-locked .rw-sec-title {
    color: #64748b;
}
.rw-accordion-card.is-locked .rw-sec-sub {
    color: #94a3b8;
}
.rw-accordion-card.is-locked .rw-sec-toggle-icon {
    opacity: 0.35;
}
.rw-accordion-card.is-locked .rw-accordion-collapse {
    display: none !important;
}
.rw-sec-lock-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.72rem;
    font-weight: 700;
    background: #e2e8f0;
    color: #475569;
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
    letter-spacing: 0.02em;
}

/* Live Quote Card Pulse Effect */
@keyframes rwValuePulse {
    0% { transform: scale(1); color: var(--rw-gold); }
    50% { transform: scale(1.04); color: #fde047; text-shadow: 0 0 8px rgba(253, 224, 71, 0.4); }
    100% { transform: scale(1); }
}
.rw-value-pulse {
    display: inline-block;
    animation: rwValuePulse 0.4s ease-out;
}

/* Accordion Header */
.rw-accordion-header {
    padding: 1.2rem 1.45rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    cursor: pointer;
    user-select: none;
    background: #ffffff;
    transition: background-color 0.2s ease;
}
.rw-accordion-header:hover {
    background: #f8fafc;
}
.rw-accordion-card.is-open .rw-accordion-header {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
.rw-sec-badge {
    width: 2.3rem;
    height: 2.3rem;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.95rem;
    background: #f1f5f9;
    color: #475569;
    flex-shrink: 0;
    transition: all 0.25s ease;
}
.rw-accordion-card.is-active .rw-sec-badge {
    background: var(--rw-navy);
    color: var(--rw-gold);
    box-shadow: 0 4px 12px rgba(13,27,42,0.2);
}
.rw-accordion-card.is-completed .rw-sec-badge {
    background: #16a34a;
    color: #ffffff;
}
.rw-sec-title-wrap { min-width: 0; flex: 1; }
.rw-sec-title { font-size: 1.15rem; font-weight: 800; color: var(--rw-text); letter-spacing: -0.01em; }
.rw-sec-sub { font-size: 0.82rem; color: var(--rw-text-muted); }
.rw-sec-summary-pill {
    font-size: 0.74rem;
    font-weight: 600;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
    max-width: 280px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rw-sec-toggle-icon {
    font-size: 1.15rem;
    color: #94a3b8;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.2s ease;
}
.rw-accordion-card.is-open .rw-sec-toggle-icon {
    transform: rotate(180deg);
    color: var(--rw-navy);
}

/* Accordion Smooth Grid Transition */
.rw-accordion-collapse {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.rw-accordion-card.is-open .rw-accordion-collapse {
    grid-template-rows: 1fr;
}
.rw-accordion-body {
    overflow: hidden;
    padding: 0 1.5rem;
    transition: padding 0.35s ease;
}
.rw-accordion-card.is-open .rw-accordion-body {
    padding: 1.5rem 1.5rem 1.85rem;
}

/* Inputs & Form Elements */
.rw-field-label { display: block; font-weight: 700; font-size: .88rem; color: var(--rw-text); margin-bottom: .45rem; }
.rw-input { width: 100%; border: 1.5px solid var(--rw-border); border-radius: .7rem; padding: .65rem .95rem; font-size: .92rem; transition: border-color .15s, box-shadow .15s; background: #fff; color: var(--rw-text); }
.rw-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
.rw-input.invalid { border-color: #dc2626; background: #fef2f2; }
.rw-textarea { resize: vertical; min-height: 80px; }
.rw-field-hint { font-size: .78rem; color: var(--rw-text-muted); margin-top: .35rem; }
.rw-field-note { font-size: .82rem; padding: .6rem .85rem; border-radius: .55rem; }
.rw-field-note-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

/* Stepper row for attendees */
.rw-stepper-row { display: flex; align-items: center; }
.rw-stepper-btn { width: 2.5rem; height: 2.5rem; border-radius: .65rem; border: 1.5px solid var(--rw-border); background: #fff; color: var(--rw-text); font-weight: 700; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all .15s; }
.rw-stepper-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }
.rw-stepper-value { width: 6.5rem; text-align: center; border: 1.5px solid var(--rw-border); border-radius: .65rem; padding: .6rem .5rem; margin: 0 .4rem; font-weight: 700; font-size: 1rem; }
.rw-stepper-value.invalid { border-color: #dc2626; background: #fef2f2; }

/* Custom schedule picker trigger */
.rw-sched-picker { width: 100%; display: flex; align-items: center; justify-content: space-between; border: 1.5px solid var(--rw-border); border-radius: .85rem; padding: .85rem 1.15rem; background: #fff; cursor: pointer; transition: all .15s; text-align: left; }
.rw-sched-picker:hover { border-color: #cbd5e1; background: #fbfcfe; }
.rw-sched-picker-main { display: flex; align-items: center; gap: .75rem; font-weight: 700; font-size: .95rem; color: var(--rw-text); }
.rw-sched-picker-main i { color: #2563eb; font-size: 1.25rem; }
.rw-sched-picker-dur { font-size: .78rem; font-weight: 700; color: #2563eb; background: rgba(37,99,235,.1); padding: .25rem .65rem; border-radius: 999px; }
.rw-sched-summary { font-size: .8rem; color: var(--rw-text-muted); margin-top: .4rem; }

/* Duration quick chips */
.rw-dur-row { display: flex; gap: .4rem; align-items: center; flex-wrap: wrap; margin-top: .6rem; }
.rw-dur-label { font-size: .75rem; font-weight: 700; color: var(--rw-text-muted); text-transform: uppercase; letter-spacing: .05em; margin-right: .3rem; }
.rw-dur-chip { background: #f1f5f9; border: 1px solid var(--rw-border); border-radius: 999px; padding: .25rem .75rem; font-size: .78rem; font-weight: 600; color: #475569; cursor: pointer; transition: all .15s; }
.rw-dur-chip:hover, .rw-dur-chip.active { background: var(--rw-navy); color: #fff; border-color: var(--rw-navy); }

/* Supporting Documents Dropzone */
.rw-dropzone { border: 2px dashed #cbd5e1; border-radius: 1rem; padding: 2rem 1.5rem; text-align: center; background: #f8fafc; cursor: pointer; transition: all .2s; }
.rw-dropzone:hover, .rw-dropzone.dragover { border-color: #2563eb; background: rgba(37,99,235,0.03); }
.rw-dropzone-icon i { font-size: 2.2rem; color: #2563eb; }
.rw-dropzone-text { font-size: 0.92rem; color: #334155; margin-top: 0.5rem; }
.rw-dropzone-sub { font-size: 0.76rem; color: #64748b; margin-top: 0.25rem; }
.rw-file-item { display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.65rem; background: #fff; margin-bottom: 0.4rem; font-size: 0.82rem; }
.rw-file-item i { color: #2563eb; font-size: 1rem; }
.rw-file-remove { background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.2rem 0.5rem; border-radius: 0.35rem; font-size: 0.8rem; }
.rw-file-remove:hover { background: #fee2e2; }

/* Sub-card groups */
.rw-subcard-head { font-size: 0.98rem; color: var(--rw-navy); display: flex; align-items: center; }
.rw-section-card { background: #f8fafc; border: 1px solid var(--rw-border); border-radius: 0.95rem; padding: 1.15rem; }
.rw-section-card-head { font-weight: 700; font-size: 0.9rem; color: var(--rw-navy); margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.4rem; }

/* End User Type cards */
.rw-type-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: .5rem; }
@media (min-width: 600px) { .rw-type-grid { grid-template-columns: repeat(4, 1fr); } }
.rw-type-card { position: relative; display: block; cursor: pointer; margin: 0; }
.rw-type-card input { position: absolute; opacity: 0; pointer-events: none; }
.rw-type-card-body { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .35rem; padding: .75rem .5rem; border: 1.5px solid var(--rw-border); border-radius: .7rem; background: #fff; transition: all .15s; font-size: .8rem; font-weight: 600; color: #475569; text-align: center; }
.rw-type-card input:checked + .rw-type-card-body { border-color: #2563eb; background: rgba(37,99,235,.07); color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,.2); }
.rw-type-card-body i { font-size: 1.15rem; }

/* ═══ Venue Selection Deck ═══ */
.rw-match-summary { background: #f1f5f9; border-radius: .75rem; padding: .65rem 1rem; font-size: .85rem; font-weight: 600; color: #334155; display: flex; align-items: center; gap: .55rem; }
.rw-match-summary i { color: #2563eb; }
.rw-venue-list { display: flex; flex-direction: column; gap: 1rem; }
.rw-venue-card { display: grid; grid-template-columns: 140px 1fr auto; border: 1.5px solid var(--rw-border); border-radius: 1rem; overflow: hidden; background: #fff; cursor: pointer; transition: all .2s; }
@media (max-width: 768px) { .rw-venue-card { grid-template-columns: 1fr; } }
.rw-venue-card:hover { border-color: #94a3b8; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.06); }
.rw-venue-card.selected { border-color: var(--rw-gold); box-shadow: 0 0 0 2px var(--rw-gold), 0 10px 30px rgba(0,0,0,.08); background: #fffdfa; }
.rw-venue-card.blocked { opacity: .7; cursor: not-allowed; background: #fafafa; }
.rw-venue-img { height: 100%; min-height: 140px; background-size: cover; background-position: center; position: relative; background-color: #e2e8f0; }
.rw-venue-body { padding: 1.1rem; display: flex; flex-direction: column; justify-content: space-between; }
.rw-venue-head { display: flex; justify-content: space-between; align-items: flex-start; gap: .5rem; }
.rw-venue-name { font-weight: 800; font-size: 1.05rem; color: var(--rw-text); margin-bottom: .2rem; }
.rw-venue-cat { font-size: .75rem; font-weight: 700; color: var(--rw-text-muted); text-transform: uppercase; letter-spacing: .05em; }
.rw-venue-desc { font-size: .82rem; color: var(--rw-text-muted); margin: .4rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.rw-venue-tags { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .4rem; }
.rw-tag { font-size: .7rem; font-weight: 600; padding: .2rem .55rem; border-radius: 999px; background: #f1f5f9; color: #475569; display: inline-flex; align-items: center; gap: .3rem; }
.rw-venue-side { padding: 1.1rem; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-end; border-left: 1px solid #f1f5f9; min-width: 170px; }
@media (max-width: 768px) { .rw-venue-side { border-left: none; border-top: 1px solid #f1f5f9; align-items: flex-start; } }
.rw-rate { font-size: 1.1rem; font-weight: 800; color: var(--rw-text); }
.rw-rate-sub { font-size: .72rem; color: var(--rw-text-muted); }
.rw-avail-pill { font-size: .72rem; font-weight: 700; padding: .3rem .65rem; border-radius: 999px; display: inline-flex; align-items: center; gap: .3rem; }
.rw-avail-free { background: rgba(22,163,74,.15); color: #16a34a; }
.rw-avail-partial { background: rgba(245,158,11,.15); color: #d97706; }
.rw-avail-blocked { background: rgba(220,38,38,.15); color: #dc2626; }

/* ═══ Logistics & Arrangements ═══ */
.rw-logi-summary-bar { background: #f8fafc; border: 1px solid var(--rw-border); border-radius: .85rem; padding: .85rem 1.15rem; display: flex; align-items: center; justify-content: space-between; }
.rw-logi-summary-icon { width: 2.2rem; height: 2.2rem; border-radius: .55rem; background: rgba(37,99,235,.1); color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.rw-logi-summary-title { font-weight: 700; font-size: .92rem; color: var(--rw-text); }
.rw-logi-summary-sub { font-size: .75rem; color: var(--rw-text-muted); }
.rw-logi-reset-btn { background: none; border: 1px solid #cbd5e1; border-radius: 999px; padding: .3rem .75rem; font-size: .76rem; font-weight: 600; color: #64748b; cursor: pointer; transition: all .15s; }
.rw-logi-reset-btn:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

.rw-logi-layout { display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: start; }
@media (max-width: 820px) { .rw-logi-layout { grid-template-columns: 1fr; } }
.rw-logi-sidebar { background: #f8fafc; border: 1px solid var(--rw-border); border-radius: .85rem; padding: .65rem; position: sticky; top: 1.25rem; }
.rw-logi-sidebar-head { font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: #64748b; padding: .45rem .65rem; display: flex; align-items: center; gap: .4rem; }
.rw-logi-nav { display: flex; flex-direction: column; gap: .25rem; }
.rw-logi-nav-item { display: flex; align-items: center; width: 100%; border: none; background: transparent; padding: .55rem .75rem; border-radius: .55rem; font-size: .82rem; font-weight: 600; color: #475569; cursor: pointer; transition: all .15s; text-align: left; }
.rw-logi-nav-item:hover { background: #e2e8f0; color: var(--rw-text); }
.rw-logi-nav-item.active { background: var(--rw-navy); color: #fff; }
.rw-logi-nav-icon { margin-right: .55rem; font-size: .95rem; }
.rw-logi-nav-name { flex: 1; }
.rw-logi-nav-count { font-size: .7rem; opacity: .7; }
.rw-logi-nav-badge { font-size: .66rem; font-weight: 800; background: var(--rw-gold); color: #fff; padding: .15rem .45rem; border-radius: 999px; margin-left: .4rem; }
.rw-logi-viewport { max-height: 520px; overflow-y: auto; padding-right: .3rem; }
.rw-logi-group-head { font-size: .75rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: #475569; padding: .4rem 0; margin-bottom: .6rem; border-bottom: 1.5px solid #e2e8f0; display: flex; align-items: center; gap: .4rem; }
.rw-logi-group-count { margin-left: auto; font-size: .7rem; font-weight: 600; color: #94a3b8; }
.rw-logi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: .75rem; }
.rw-logi-item { border: 1.5px solid var(--rw-border); border-radius: .85rem; padding: .85rem; background: #fff; display: flex; flex-direction: column; justify-content: space-between; transition: all .15s; }
.rw-logi-item:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,.04); }
.rw-logi-item-header { display: flex; align-items: flex-start; gap: .6rem; margin-bottom: .75rem; }
.rw-logi-icon-badge { width: 2.2rem; height: 2.2rem; border-radius: .55rem; background: #f1f5f9; color: #334155; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.rw-logi-item-info { flex: 1; min-width: 0; }
.rw-logi-name { font-weight: 700; font-size: .88rem; color: var(--rw-text); line-height: 1.25; }
.rw-logi-unit-hint { font-size: .7rem; color: var(--rw-text-muted); }
.rw-logi-price-tag { font-size: .72rem; font-weight: 700; padding: .2rem .55rem; border-radius: 999px; flex-shrink: 0; }
.rw-price-free { background: rgba(22,163,74,.15); color: #16a34a; }
.rw-price-paid { background: #f1f5f9; color: #475569; }
.rw-logi-controls { display: flex; align-items: center; gap: .35rem; }
.rw-qty-btn { width: 2rem; height: 2rem; border-radius: .5rem; border: 1px solid var(--rw-border); background: #f8fafc; color: var(--rw-text); font-weight: 700; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all .15s; }
.rw-qty-btn:hover { background: #e2e8f0; }
.rw-qty-input { width: 3.5rem; text-align: center; border: 1px solid var(--rw-border); border-radius: .5rem; padding: .3rem .25rem; font-weight: 700; font-size: .88rem; }
.rw-logi-quick-adds { display: flex; gap: .25rem; margin-left: auto; }
.rw-quick-add-btn { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: .4rem; padding: .2rem .4rem; font-size: .68rem; font-weight: 700; color: #475569; cursor: pointer; }
.rw-quick-add-btn:hover { background: var(--rw-navy); color: #fff; }

/* ═══ Review Deck ═══ */
.rw-rev-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: .75rem; }
.rw-rev-box { border: 1.5px solid var(--rw-border); border-radius: .85rem; padding: .85rem; background: #fff; cursor: pointer; transition: all .15s; }
.rw-rev-box:hover { border-color: #2563eb; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,.05); }
.rw-rev-box-head { display: flex; justify-content: space-between; align-items: center; font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #64748b; margin-bottom: .4rem; }
.rw-rev-edit { font-size: .68rem; color: #2563eb; text-transform: none; font-weight: 600; }
.rw-rev-title { font-weight: 700; font-size: .92rem; color: var(--rw-text); word-break: break-word; }
.rw-rev-sub { font-size: .76rem; color: var(--rw-text-muted); margin-top: .15rem; word-break: break-word; }
.rw-quot-card { border: 1.5px solid var(--rw-border); border-radius: .95rem; overflow: hidden; background: #fff; }
.rw-quot-card-head { background: var(--rw-navy); color: #fff; padding: .75rem 1.15rem; font-weight: 800; font-size: .92rem; display: flex; justify-content: space-between; align-items: center; }
.rw-quot-body { padding: 1.15rem; }
.rw-quot-table { width: 100%; font-size: .88rem; }
.rw-quot-table td { padding: .45rem 0; }
.rw-quot-table td:last-child { text-align: right; font-weight: 600; }
.rw-quot-sub { border-top: 1px dashed var(--rw-border); color: #64748b; }
.rw-quot-total-row { border-top: 1.5px solid var(--rw-navy); font-weight: 800; font-size: 1.05rem; color: var(--rw-navy); }
.rw-quot-waived { background: rgba(22,163,74,.1); border-top: 1px solid rgba(22,163,74,.2); padding: .75rem 1.15rem; font-size: .82rem; color: #16a34a; font-weight: 600; display: flex; align-items: center; gap: .5rem; }
.rw-rev-reason { background: #f8fafc; border: 1px solid var(--rw-border); border-radius: .85rem; padding: .85rem 1.15rem; }

/* ═══ Payment Cards ═══ */
.rw-pay-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; }
.rw-pay-card { position: relative; border: 1.5px solid var(--rw-border); border-radius: 1rem; padding: 1.25rem; background: #fff; cursor: pointer; transition: all .2s; }
.rw-pay-card:hover:not(.rw-pay-card-disabled) { border-color: #2563eb; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.06); }
.rw-pay-card.rw-pay-selected { border-color: var(--rw-navy); background: rgba(13,27,42,0.02); box-shadow: 0 0 0 2px var(--rw-navy); }
.rw-pay-card input { position: absolute; opacity: 0; }
.rw-pay-icon { width: 2.8rem; height: 2.8rem; border-radius: .7rem; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; margin-bottom: .85rem; }
.rw-pay-icon-cash { background: rgba(22,163,74,.15); color: #16a34a; }
.rw-pay-icon-online { background: rgba(37,99,235,.15); color: #2563eb; }
.rw-pay-sub { font-size: .78rem; color: var(--rw-text-muted); }
.rw-waived-panel { text-align: center; padding: 2rem 1.5rem; background: #f0fdf4; border: 1.5px dashed #86efac; border-radius: 1rem; }
.rw-waived-icon { width: 3.5rem; height: 3.5rem; border-radius: 50%; background: #16a34a; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 1rem; }

/* ═══ Buttons & Controls ═══ */
.rw-btn-primary { background: var(--rw-navy); color: #fff; border: none; border-radius: 999px; transition: all .2s; }
.rw-btn-primary:hover { background: #1b2a41; color: var(--rw-gold); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(13,27,42,.25); }
.rw-btn-gold { background: var(--rw-gold); color: #fff; border: none; transition: all .2s; }
.rw-btn-gold:hover { background: #990f0f; color: #fff; }
.rw-btn-submit { background: linear-gradient(135deg, var(--rw-navy) 0%, #1b2a41 100%); color: var(--rw-gold); font-size: 1.1rem; border: none; transition: all .2s; }
.rw-btn-submit:hover { background: linear-gradient(135deg, #0a1420 0%, var(--rw-navy) 100%); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(13,27,42,.35); }

/* Shakes & animations */
@keyframes rwShake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}
.rw-shake { animation: rwShake 0.4s ease-in-out; }

/* Submitting overlay */
.rw-submitting { position: fixed; inset: 0; background: rgba(13,27,42,.85); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; backdrop-filter: blur(4px); }
.rw-submitting .spinner-border { color: var(--rw-gold); width: 2.8rem; height: 2.8rem; }
.rw-submitting span { margin-top: 1rem; font-weight: 700; font-size: 1.05rem; }

/* Modals */
.rw-modal { border-radius: 1.15rem; overflow: hidden; border: none; }
.rw-modal .modal-header { background: var(--rw-navy); color: #fff; border: none; }
.rw-vm-hero { height: 220px; background-size: cover; background-position: center; border-radius: .85rem; margin-bottom: 1rem; background-color: #e2e8f0; }
.rw-vm-cat { font-size: .75rem; color: rgba(255,255,255,.65); text-transform: uppercase; font-weight: 700; }
.rw-vm-stat { background: #f8fafc; border: 1px solid var(--rw-border); border-radius: .7rem; padding: .65rem .85rem; display: flex; align-items: center; gap: .65rem; }
.rw-vm-stat i { font-size: 1.25rem; color: #2563eb; }
.rw-vm-stat-label { font-size: .68rem; text-transform: uppercase; font-weight: 700; color: #64748b; }
.rw-vm-sect-head { font-size: .82rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: #475569; margin: 1.15rem 0 .45rem; display: flex; align-items: center; gap: .35rem; }
.rw-vm-amenities { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: .4rem; }
.rw-vm-amenity { font-size: .8rem; display: flex; align-items: center; gap: .4rem; color: #334155; }
.rw-vm-amenity i { color: #16a34a; }
.rw-vm-price { font-weight: 800; font-size: 1rem; color: #16a34a; }

/* Custom calendar modal */
.rw-pick-banner { background: #f1f5f9; border-radius: .75rem; padding: .75rem 1rem; display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.rw-pick-banner-main { display: flex; align-items: center; gap: .55rem; font-weight: 700; font-size: .95rem; color: var(--rw-navy); }
.rw-pick-banner-dur { font-size: .75rem; font-weight: 700; color: #2563eb; background: rgba(37,99,235,.1); padding: .2rem .55rem; border-radius: 999px; }
.rw-cal-card { border: 1px solid var(--rw-border); border-radius: .85rem; padding: .95rem; background: #fff; }
.rw-cal-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: .65rem; }
.rw-cal-title { font-weight: 800; font-size: .95rem; color: var(--rw-navy); }
.rw-cal-nav { background: none; border: 1px solid var(--rw-border); border-radius: .5rem; width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #475569; }
.rw-cal-dow { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: .68rem; font-weight: 700; color: #94a3b8; margin-bottom: .35rem; }
.rw-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
.rw-cal-cell { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 600; border-radius: .4rem; color: #334155; cursor: default; }
.rw-cal-cell.clickable { cursor: pointer; }
.rw-cal-cell.clickable:hover { background: #e2e8f0; }
.rw-cal-cell.past, .rw-cal-cell.deadline-locked, .rw-cal-cell.future-locked { color: #cbd5e1; cursor: not-allowed; text-decoration: line-through; }
.rw-cal-cell.active { background: var(--rw-navy) !important; color: #fff !important; font-weight: 800; }
.rw-cal-cell.in-range { background: rgba(37,99,235,.12); color: #2563eb; border-radius: 0; }
.rw-cal-cell.today { border: 1.5px solid #2563eb; }
.rw-time-card { border: 1px solid var(--rw-border); border-radius: .85rem; padding: .95rem; background: #fff; }
.rw-pick-hint { font-size: .72rem; color: var(--rw-text-muted); }
.rw-timeline { height: 18px; border-radius: 9px; overflow: hidden; display: flex; background: #e2e8f0; }
.rw-tl-axis { display: flex; justify-content: space-between; font-size: .66rem; color: #94a3b8; font-weight: 700; margin-bottom: 2px; }
.rw-tl-track { height: 16px; border-radius: 8px; overflow: hidden; display: flex; background: #e2e8f0; }
.rw-tl-seg { height: 100%; transition: opacity .15s; }
.rw-tl-free { background: #22c55e; }
.rw-tl-pending { background: #a855f7; }
.rw-tl-blocked { background: #ef4444; }
.rw-vm-legend { display: flex; gap: .75rem; font-size: .72rem; font-weight: 600; color: #64748b; margin-top: .4rem; }
.rw-vm-legend .dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 3px; }
.rw-dot-free { background: #22c55e; }
.rw-dot-pending { background: #a855f7; }
.rw-dot-blocked { background: #ef4444; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    'use strict';

    /* ═══════════════════════════════════════════════════════
       SURE Single-Page Progressive Controller
       ═══════════════════════════════════════════════════════ */
    const SETTINGS = {
        clientType:      <?php echo json_encode($clientType, 15, 512) ?>,
        internalWaived:  <?php echo json_encode((bool)($reservationSettings['internal_fee_waived'] ?? false), 15, 512) ?>,
        cashEnabled:     <?php echo json_encode((bool)($reservationSettings['payment_method_cash_enabled'] ?? true), 15, 512) ?>,
        paymongoEnabled: <?php echo json_encode((bool)($reservationSettings['payment_method_paymongo_enabled'] ?? true), 15, 512) ?>,
        depositAmount:   <?php echo json_encode((float)($reservationSettings['security_deposit_amount'] ?? 0), 15, 512) ?>,
        deadlineDays:    <?php echo json_encode((int)($reservationSettings['payment_deadline_days'] ?? 3), 15, 512) ?>,
        requireEndorse:  <?php echo json_encode((bool)($reservationSettings['require_endorsement_internal'] ?? false), 15, 512) ?>,
        allowMultiple:   <?php echo json_encode((bool)($reservationSettings['allow_multiple_advance_reservations'] ?? false), 15, 512) ?>,
        matchUrl:        <?php echo json_encode('/api/wizard/match', 15, 512) ?>,
        quotationUrl:    <?php echo json_encode('/api/wizard/quotation', 15, 512) ?>,
        facilityIds:     <?php echo json_encode(\App\Models\Facility::where('is_available', true)->pluck('id')->implode(', ')) ?>,
        draftKey:        <?php echo json_encode('wizard_draft_' . (auth()->id() ?? 'anon'), 15, 512) ?>,
        today:           <?php echo json_encode(date('Y-m-d'), 15, 512) ?>,
        earliestDate:   <?php echo json_encode($reservationSettings['payment_earliest_date'] ?? date('Y-m-d'), 15, 512) ?>,
        maxDate:         <?php echo json_encode(date('Y-m-d', strtotime('+1 year')), 512) ?>,
        maxMonth:        <?php echo json_encode(date('Y-m', strtotime('+1 year')), 512) ?>,
        minMonth:        <?php echo json_encode(date('Y-m'), 15, 512) ?>,
        serverError:     <?php echo json_encode(session()->has('error') || $errors->any() || old() !== [], 15, 512) ?>,
        csrf:            <?php echo json_encode(csrf_token(), 15, 512) ?>,
    };

    const TOTAL_SECTIONS = 5;
    const DAY_LABELS  = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const MON_LABELS  = ['Jan.', 'Feb.', 'Mar.', 'Apr.', 'May', 'Jun.', 'Jul.', 'Aug.', 'Sep.', 'Oct.', 'Nov.', 'Dec.'];

    const state = {
        activeSection: 1,
        openSections: new Set([1]),
        unlockedSections: new Set([1]),
        completedSections: new Set(),
        selectedVenueId: null,
        venues: [],
        matchFetchedFor: null,
        quotations: {},
        purpose: null,
        submitLocked: false,
        matchTimer: null,
        logiZeroConfirmed: false,
    };

    /* ---------- Helpers ---------- */
    const $  = (s) => document.querySelector(s);
    const $$ = (s) => Array.from(document.querySelectorAll(s));
    const fmtMoney = (v) => '₱' + Number(v || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    function highlightQuoteValue(el) {
        if (!el) return;
        el.classList.remove('rw-value-pulse');
        void el.offsetWidth;
        el.classList.add('rw-value-pulse');
    }

    function humanDate(d) {
        if (!d) return '—';
        const dt = new Date((d || '').trim().length === 10 ? (d + 'T00:00:00') : d);
        if (isNaN(dt)) return d;
        return MON_LABELS[dt.getMonth()] + ' ' + dt.getDate() + ', ' + dt.getFullYear();
    }
    function humanDateShort(d) {
        if (!d) return '—';
        const dt = new Date((d || '').trim().length === 10 ? (d + 'T00:00:00') : d);
        if (isNaN(dt)) return d;
        return MON_LABELS[dt.getMonth()].replace('.', '') + ' ' + dt.getDate();
    }
    function humanTime(hhmm) {
        if (!hhmm) return '—';
        let [h, m] = hhmm.split(':').map(Number);
        const ap = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        return h + ':' + String(m).padStart(2, '0') + ' ' + ap;
    }
    function m2m(hhmm) {
        if (!hhmm) return 0;
        const [h, m] = hhmm.split(':').map(Number);
        return h * 60 + m;
    }
    function esc(s) { return String(s ?? '').replace(/[&<>"']/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

    let attachedDataTransfer = new DataTransfer();

    /* ---------- IndexedDB draft persistence for files ---------- */
    const DraftFilesDB = {
        dbName: 'sure_reservation_draft_files',
        storeName: 'files',
        open() {
            return new Promise((resolve) => {
                if (!window.indexedDB) return resolve(null);
                try {
                    const req = window.indexedDB.open(this.dbName, 1);
                    req.onupgradeneeded = (e) => {
                        const db = e.target.result;
                        if (!db.objectStoreNames.contains(this.storeName)) {
                            db.createObjectStore(this.storeName, { keyPath: 'id', autoIncrement: true });
                        }
                    };
                    req.onsuccess = (e) => resolve(e.target.result);
                    req.onerror = () => resolve(null);
                } catch (err) { resolve(null); }
            });
        },
        async saveFiles(fileList) {
            const db = await this.open();
            if (!db) return;
            return new Promise((resolve) => {
                try {
                    const tx = db.transaction(this.storeName, 'readwrite');
                    const store = tx.objectStore(this.storeName);
                    store.clear();
                    Array.from(fileList).forEach((file) => {
                        store.add({ name: file.name, type: file.type, size: file.size, lastModified: file.lastModified, blob: file });
                    });
                    tx.oncomplete = () => resolve(true);
                    tx.onerror = () => resolve(false);
                } catch (e) { resolve(false); }
            });
        },
        async getFiles() {
            const db = await this.open();
            if (!db) return [];
            return new Promise((resolve) => {
                try {
                    const tx = db.transaction(this.storeName, 'readonly');
                    const store = tx.objectStore(this.storeName);
                    const req = store.getAll();
                    req.onsuccess = () => {
                        const records = req.result || [];
                        const files = records.map((r) => new File([r.blob], r.name, { type: r.type, lastModified: r.lastModified }));
                        resolve(files);
                    };
                    req.onerror = () => resolve([]);
                } catch (e) { resolve([]); }
            });
        },
        async clear() {
            const db = await this.open();
            if (!db) return;
            return new Promise((resolve) => {
                try {
                    const tx = db.transaction(this.storeName, 'readwrite');
                    tx.objectStore(this.storeName).clear();
                    tx.oncomplete = () => resolve(true);
                    tx.onerror = () => resolve(false);
                } catch (e) { resolve(false); }
            });
        }
    };

    /* ---------- Draft Persistence ---------- */
    const Draft = {
        save() {
            try {
                localStorage.setItem(SETTINGS.draftKey, JSON.stringify({
                    ts: Date.now(),
                    activeSection: state.activeSection,
                    unlockedSections: Array.from(state.unlockedSections),
                    completedSections: Array.from(state.completedSections),
                    selected_venue_id: state.selectedVenueId,
                    event_title: $('#event_title').value,
                    purpose: state.purpose,
                    reason: $('#reason').value,
                    start_date: $('#start_date').value,
                    end_date: $('#end_date').value,
                    start_time: $('#start_time').value,
                    end_time: $('#end_time').value,
                    number_of_people: $('#number_of_people').value,
                    reserved_by_name: $('#reserved_by_name').value,
                    reserved_by_email: $('#reserved_by_email').value,
                    reserved_by_phone: $('#reserved_by_phone').value,
                    end_user_type: ($$('input[name="end_user_type"]').find((r) => r.checked) || {}).value || '',
                    end_user_name: $('#end_user_name').value,
                    endorsed_by_name: $('#endorsed_by_name') ? $('#endorsed_by_name').value : '',
                    endorsed_by_position: $('#endorsed_by_position') ? $('#endorsed_by_position').value : '',
                    payment_method: ($$('input[name="payment_method"]').find((r) => r.checked) || {}).value || '',
                    logi: Object.fromEntries($$('.rw-qty-input').map((i) => [i.dataset.itemId, Number(i.value)])),
                }));
            } catch (e) {}
        },
        load() {
            try {
                const raw = localStorage.getItem(SETTINGS.draftKey);
                if (!raw) return null;
                const d = JSON.parse(raw);
                if (!d || !d.ts) return null;
                if (Date.now() - d.ts > 7 * 24 * 3600 * 1000) { localStorage.removeItem(SETTINGS.draftKey); return null; }
                return d;
            } catch (e) { return null; }
        },
        clear() {
            try {
                localStorage.removeItem(SETTINGS.draftKey);
                DraftFilesDB.clear();
            } catch (e) {}
        }
    };

    window.RW = window.RW || {};
    window.RW.clearDraft = function () { Draft.clear(); };

    window.RW.restoreDraft = async function () {
        const d = Draft.load();
        if (!d) return;

        if (d.event_title) {
            $('#event_title').value = d.event_title;
            $('#event_title').dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (d.reason) {
            $('#reason').value = d.reason;
            state.purpose = d.reason;
            $('#reason').dispatchEvent(new Event('input', { bubbles: true }));
        }

        if (d.start_date && d.start_date >= SETTINGS.earliestDate) {
            $('#start_date').value = d.start_date;
            let effEd = d.end_date || d.start_date;
            if (d.start_time && d.end_time && m2m(d.end_time) < m2m(d.start_time) && effEd === d.start_date) {
                effEd = addDaysStr(d.start_date, 1);
            }
            $('#end_date').value = effEd;
            if (d.start_time) $('#start_time').value = d.start_time;
            if (d.end_time) $('#end_time').value = d.end_time;
            syncPickFromInputs();
            refreshTrigger();
        }

        if (d.number_of_people) {
            $('#number_of_people').value = d.number_of_people;
            $('#number_of_people').dispatchEvent(new Event('input', { bubbles: true }));
        }

        if (d.reserved_by_name) $('#reserved_by_name').value = d.reserved_by_name;
        if (d.reserved_by_email) $('#reserved_by_email').value = d.reserved_by_email;
        if (d.reserved_by_phone) {
            $('#reserved_by_phone').value = d.reserved_by_phone;
            $('#reserved_by_phone').dispatchEvent(new CustomEvent('external:sync'));
        }
        if (d.end_user_type) {
            const r = $$('input[name="end_user_type"]').find((x) => x.value === d.end_user_type);
            if (r) r.checked = true;
        }
        if (d.end_user_name) $('#end_user_name').value = d.end_user_name;
        if ($('#endorsed_by_name') && d.endorsed_by_name) $('#endorsed_by_name').value = d.endorsed_by_name;
        if ($('#endorsed_by_position') && d.endorsed_by_position) $('#endorsed_by_position').value = d.endorsed_by_position;
        if (d.payment_method) RW.selectPayment(d.payment_method);

        // Restore logistics
        if (d.logi) {
            Object.entries(d.logi).forEach(([id, q]) => {
                const i = document.getElementById('rw-qty-' + id);
                if (i && q > 0) RW.setQty(id, Math.min(q, Number(i.max)));
            });
        }
        updateLogiSummary();

        // Restore documents from IndexedDB
        const restoredFiles = await DraftFilesDB.getFiles();
        if (restoredFiles && restoredFiles.length) {
            attachedDataTransfer = new DataTransfer();
            Array.from(restoredFiles).slice(0, 5).forEach((file) => attachedDataTransfer.items.add(file));
            const docInput = $('#rw_documents');
            if (docInput) docInput.files = attachedDataTransfer.files;
            renderFileList();
        }

        // Restore venue & schedule matching
        if (d.start_date && d.start_time && d.end_time && d.start_date >= SETTINGS.earliestDate) {
            await runMatch();
            if (d.selected_venue_id) {
                const v = state.venues.find((x) => x.id === d.selected_venue_id);
                if (v && v.available) {
                    RW.selectVenue(d.selected_venue_id);
                }
            }
        }

        if (d.unlockedSections && Array.isArray(d.unlockedSections)) {
            d.unlockedSections.forEach(s => state.unlockedSections.add(Number(s)));
        } else if (d.completedSections && Array.isArray(d.completedSections)) {
            d.completedSections.forEach(s => {
                state.unlockedSections.add(Number(s));
                if (Number(s) + 1 <= TOTAL_SECTIONS) state.unlockedSections.add(Number(s) + 1);
            });
        }

        if (d.completedSections && Array.isArray(d.completedSections)) {
            d.completedSections.forEach(s => {
                state.completedSections.add(Number(s));
                updateSummaryPill(Number(s));
            });
        }

        updateQuotePanel();
        refreshReview();
        $('#draft-banner').classList.add('d-none');

        if (d.activeSection && d.activeSection >= 1 && d.activeSection <= TOTAL_SECTIONS) {
            RW.openSection(d.activeSection);
        } else {
            updateSectionUI();
        }
    };

    /* ═══════════════════════════════════════════════════════
       ACCORDION MANAGEMENT & PROGRESSION
       ═══════════════════════════════════════════════════════ */
    function updateSectionUI() {
        for (let i = 1; i <= TOTAL_SECTIONS; i++) {
            const card = document.getElementById('section-' + i);
            const badge = document.getElementById('badge-' + i);
            const lockBadge = document.getElementById('sec-' + i + '-lock');

            if (!card) continue;

            const isUnlocked = state.unlockedSections.has(i);
            const isOpen = state.openSections.has(i) && isUnlocked;
            const isActive = state.activeSection === i;
            const isCompleted = state.completedSections.has(i);

            card.classList.toggle('is-locked', !isUnlocked);
            card.classList.toggle('is-unlocked', isUnlocked);
            card.classList.toggle('is-open', isOpen);
            card.classList.toggle('is-active', isActive);
            card.classList.toggle('is-completed', isCompleted);

            if (lockBadge) {
                lockBadge.style.display = isUnlocked ? 'none' : 'inline-flex';
            }

            if (badge) {
                if (!isUnlocked) {
                    badge.innerHTML = '<i class="bi bi-lock-fill"></i>';
                } else if (isCompleted) {
                    badge.innerHTML = '<i class="bi bi-check-lg"></i>';
                } else {
                    badge.innerHTML = i;
                }
            }
        }

        const stepLabel = $('#quote-step');
        if (stepLabel) {
            stepLabel.textContent = 'Section ' + state.activeSection + ' of ' + TOTAL_SECTIONS;
        }
    }

    function updateSummaryPill(secNum) {
        const pill = document.getElementById('sec-' + secNum + '-summary');
        if (!pill) return;

        let content = '';
        if (secNum === 1) {
            const title = $('#event_title').value.trim() || 'Event';
            const sd = $('#start_date').value;
            const people = $('#number_of_people').value;
            const name = $('#reserved_by_name').value.trim();
            if (sd) {
                content = `<i class="bi bi-calendar-check text-primary me-1"></i> ${humanDateShort(sd)} · ${esc(title)} · ${people ? people + ' guests' : ''}`;
            } else {
                content = `${esc(title)} · ${esc(name)}`;
            }
        } else if (secNum === 2) {
            const v = state.selectedVenueId && state.venues.find((x) => x.id === state.selectedVenueId);
            content = v ? `<i class="bi bi-geo-alt-fill text-primary me-1"></i> ${esc(v.name)} (${fmtMoney(v.price_per_hour)}/hr)` : 'No venue selected';
        } else if (secNum === 3) {
            const arr = arrangementsOf();
            const totalQty = arr.reduce((s, a) => s + a.quantity, 0);
            content = totalQty > 0 ? `<i class="bi bi-box-seam-fill text-primary me-1"></i> ${totalQty} items selected` : 'Venue only (no items)';
        } else if (secNum === 4) {
            content = '<i class="bi bi-shield-check text-success me-1"></i> Agreement accepted';
        } else if (secNum === 5) {
            const pay = ($$('input[name="payment_method"]:checked')[0] || {}).value;
            content = pay === 'paymongo' ? 'Online PayMongo' : 'Pay at Cashier';
        }

        if (content) {
            pill.innerHTML = content;
            pill.classList.remove('d-none');
        } else {
            pill.classList.add('d-none');
        }
    }

    window.RW.toggleSection = function (secNum) {
        if (!state.unlockedSections.has(secNum)) {
            const card = document.getElementById('section-' + secNum);
            if (card) {
                card.classList.add('rw-shake');
                setTimeout(() => card.classList.remove('rw-shake'), 450);
            }
            showErrors(['Section ' + secNum + ' is locked. Please complete the required fields in previous sections first.']);
            return;
        }

        if (state.openSections.has(secNum)) {
            state.openSections.delete(secNum);
        } else {
            state.activeSection = secNum;
            state.openSections.add(secNum);
            if (secNum === 2) { runMatch(); renderVenues(); }
            if (secNum === 4) { fetchQuotation(); refreshReview(); }
        }
        updateSectionUI();
    };

    window.RW.openSection = function (secNum) {
        if (secNum < 1 || secNum > TOTAL_SECTIONS) return;
        if (!state.unlockedSections.has(secNum)) {
            const card = document.getElementById('section-' + secNum);
            if (card) {
                card.classList.add('rw-shake');
                setTimeout(() => card.classList.remove('rw-shake'), 450);
            }
            showErrors(['Section ' + secNum + ' is locked. Please complete the required fields in previous sections first.']);
            return;
        }

        state.activeSection = secNum;
        state.openSections.add(secNum);
        updateSectionUI();

        if (secNum === 2) { runMatch(); renderVenues(); }
        if (secNum === 4) { fetchQuotation(); refreshReview(); }

        const card = document.getElementById('section-' + secNum);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    window.RW.closeSection = function (secNum) {
        state.openSections.delete(secNum);
        updateSectionUI();
    };

    window.RW.completeSection = function (secNum) {
        if (!validateSection(secNum)) return;

        state.completedSections.add(secNum);
        updateSummaryPill(secNum);

        // Close current section
        state.openSections.delete(secNum);

        // Unlock & advance to next section
        const next = secNum + 1;
        if (next <= TOTAL_SECTIONS) {
            state.unlockedSections.add(next);
            state.activeSection = next;
            state.openSections.add(next);

            if (next === 2) { runMatch(); renderVenues(); }
            if (next === 4) { fetchQuotation(); refreshReview(); }
        }

        updateSectionUI();
        Draft.save();

        if (next <= TOTAL_SECTIONS) {
            setTimeout(() => {
                const nextCard = document.getElementById('section-' + next);
                if (nextCard) {
                    nextCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 120);
        }
    };

    // Backward compatibility for existing goTo calls
    window.RW.goTo = function (n) {
        let target = n;
        if (n === 4) target = 1; // Contact is in section 1
        else if (n === 5) target = 4; // Review is section 4
        else if (n === 6) target = 5; // Payment is section 5
        RW.openSection(target);
    };

    window.RW.confirmProceedWithoutLogi = function () {
        state.logiZeroConfirmed = true;
        const modalEl = document.getElementById('logiZeroModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
        RW.completeSection(3);
    };

    /* ---------- Validation ---------- */
    function markInvalid(el, msg) {
        if (!el) return msg;
        el.classList.add('invalid');
        el.addEventListener('input', () => el.classList.remove('invalid'), { once: true });
        return msg;
    }
    function clearInvalids() { $$('.rw-input, .rw-stepper-value, .intl-phone-input, .intl-phone-select').forEach((e) => e.classList.remove('invalid')); }

    function validateSection(sec) {
        clearInvalids();
        const card = document.getElementById('section-' + sec);

        if (sec === 1) {
            const errs = [];
            if (!$('#event_title').value.trim()) errs.push(markInvalid($('#event_title'), 'Event title is required.'));
            let sd = $('#start_date').value, ed = $('#end_date').value, st = $('#start_time').value, et = $('#end_time').value;
            if (!sd) errs.push(markInvalid($('#start_date'), 'Start date is required.'));
            if (!ed && sd) { ed = sd; $('#end_date').value = ed; }
            if (!ed) errs.push(markInvalid($('#end_date'), 'End date is required.'));
            if (!st) errs.push(markInvalid($('#start_time'), 'Start time is required.'));
            if (!et) errs.push(markInvalid($('#end_time'), 'End time is required.'));
            else if (sd && ed && st && et) {
                if (sd === ed && m2m(et) < m2m(st)) {
                    ed = addDaysStr(sd, 1);
                    $('#end_date').value = ed;
                    if (pick.sd) pick.ed = ed;
                    refreshTrigger();
                    updateQuotePanel();
                } else if (sd === ed && m2m(et) === m2m(st)) {
                    markInvalid($('#end_time'), 'End time cannot be the same as start time.');
                    errs.push('End time cannot be the same as start time.');
                } else {
                    const startDt = new Date(sd + 'T' + st);
                    const endDt   = new Date(ed + 'T' + et);
                    if (endDt <= startDt) {
                        markInvalid($('#end_time'), 'End date & time must be after start.');
                        errs.push('The event end date and time must be after start.');
                    }
                }
            }
            if (!$('#number_of_people').value || Number($('#number_of_people').value) < 1) errs.push(markInvalid($('#number_of_people'), 'Expected attendees is required.'));
            const reasonEl = $('#reason');
            if (!reasonEl || !reasonEl.value.trim()) {
                if (reasonEl) markInvalid(reasonEl, 'Purpose of reservation is required.');
                errs.push('Purpose of reservation is required.');
            }

            // Contact validation (moved to section 1)
            if (!$('#reserved_by_name').value.trim()) errs.push(markInvalid($('#reserved_by_name'), 'Contact name is required.'));
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($('#reserved_by_email').value)) errs.push(markInvalid($('#reserved_by_email'), 'A valid email is required.'));
            if (!$('#reserved_by_phone').value.trim()) {
                const phoneInputEl = $('#reserved_by_phone').closest('.intl-phone-wrapper')?.querySelector('.intl-phone-input') || $('#reserved_by_phone');
                errs.push(markInvalid(phoneInputEl, 'Contact number is required.'));
            }
            if (!$$('input[name="end_user_type"]').some((r) => r.checked)) {
                $$('.rw-type-card').forEach((c) => c.classList.add('rw-shake'));
                setTimeout(() => $$('.rw-type-card').forEach((c) => c.classList.remove('rw-shake')), 450);
                errs.push('Select the end-user type.');
            }
            if (!$('#end_user_name').value.trim()) errs.push(markInvalid($('#end_user_name'), 'End user name is required.'));

            if (errs.length) return showErrors(errs);
            runMatch();
            return true;
        }

        if (sec === 2) {
            if (!state.selectedVenueId) {
                if (card) {
                    card.classList.add('rw-shake');
                    setTimeout(() => card.classList.remove('rw-shake'), 450);
                }
                return showErrors(['Please select an available venue to continue.']);
            }
            return true;
        }

        if (sec === 3) {
            const arr = arrangementsOf();
            if (arr.length === 0 && !state.logiZeroConfirmed) {
                const modalEl = document.getElementById('logiZeroModal');
                if (modalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                    return false;
                }
            }
            return true;
        }

        if (sec === 4) {
            if (!$('#agreement_accepted_check').checked) {
                $('#agreement_accepted_check').closest('.rw-agree').classList.add('rw-shake');
                setTimeout(() => $('#agreement_accepted_check').closest('.rw-agree').classList.remove('rw-shake'), 450);
                return showErrors(['Please accept the terms and conditions to continue.']);
            }
            return true;
        }

        if (sec === 5) {
            if (SETTINGS.clientType !== 'internal' || !SETTINGS.internalWaived) {
                const enabled = (SETTINGS.cashEnabled ? 1 : 0) + (SETTINGS.paymongoEnabled ? 1 : 0);
                if (enabled === 0) {
                    return showErrors(['All payment methods are currently disabled by the administration.']);
                }
                if (!$$('input[name="payment_method"]:checked').length) {
                    $$('.rw-pay-card:not(.rw-pay-card-disabled)').forEach((c) => c.classList.add('rw-shake'));
                    setTimeout(() => $$('.rw-pay-card').forEach((c) => c.classList.remove('rw-shake')), 450);
                    return showErrors(['Please select an available payment method.']);
                }
            }
            return true;
        }

        return true;
    }

    function showErrors(errs) {
        const note = $('#schedule-note');
        if (note) {
            note.textContent = errs.join(' ');
            note.classList.remove('d-none');
            note.style.display = 'block';
            setTimeout(() => { note.style.display = ''; note.classList.add('d-none'); }, 7000);
        }
        const existing = $('#rw-inline-err');
        if (existing) existing.remove();
        const div = document.createElement('div');
        div.id = 'rw-inline-err';
        div.style.cssText = 'position:fixed;top:1.25rem;left:50%;transform:translateX(-50%);z-index:9998;max-width:92vw;background:#7f1d1d;color:#fff;font-size:.82rem;font-weight:600;padding:.7rem 1.1rem;border-radius:.8rem;box-shadow:0 14px 40px rgba(0,0,0,.35);display:flex;gap:.6rem;align-items:flex-start;';
        div.innerHTML = '<i class="bi bi-exclamation-triangle-fill" style="margin-top:2px"></i><div>' + errs.map(esc).join('<br>') + '</div>';
        document.body.appendChild(div);
        setTimeout(() => { div.style.transition = 'opacity .3s'; div.style.opacity = '0'; setTimeout(() => div.remove(), 350); }, 5000);
        return false;
    }

    /* ---------- Quote Panel ---------- */
    function updateQuotePanel() {
        const title = $('#event_title').value.trim();
        $('#q-event').textContent = title || 'Not set';
        const sd = $('#start_date').value, ed = $('#end_date').value, st = $('#start_time').value, et = $('#end_time').value;
        let sched = 'Pick a date & time', schedSub = 'Duration will appear here';
        if (sd && st) {
            const range = ed && ed !== sd ? humanDateShort(sd) + ' – ' + humanDate(ed) : humanDate(sd);
            sched = range + ' · ' + humanTime(st) + (et ? ' – ' + humanTime(et) : '');
            if (st && et) {
                const [sh, sm] = st.split(':').map(Number), [eh, em] = et.split(':').map(Number);
                let mins = (eh * 60 + em) - (sh * 60 + sm);
                if (mins <= 0) mins += 1440;
                const h = Math.floor(mins / 60), m = mins % 60;
                schedSub = ((h ? h + 'h ' : '') + (m ? m + 'm' : '')).trim() || '—';
                if (sd !== ed) schedSub = 'Multi-day · ' + schedSub;
            }
        }
        $('#q-schedule').textContent = sched;
        $('#q-schedule-sub').textContent = schedSub;
        const people = Number($('#number_of_people').value) || 0;
        $('#q-people').textContent = people ? people.toLocaleString() + (people === 1 ? ' guest' : ' guests') : '— guests';

        const v = state.selectedVenueId && state.venues.find((x) => x.id === state.selectedVenueId);
        if (v) {
            $('#q-venue-card').style.display = 'flex';
            $('#q-venue-name').textContent = v.name;
            $('#q-venue-cap').textContent = 'Fits ' + v.capacity.toLocaleString() + ' · ' + (v.location || 'Campus');
            $('#q-venue-price').textContent = fmtMoney(v.price_per_hour) + '/hr';
            const img = $('#q-venue-card').querySelector('.rw-quote-venue-img');
            img.style.backgroundImage = v.image_url ? "url('" + v.image_url + "')" : 'none';
        } else {
            $('#q-venue-card').style.display = 'none';
        }

        if (typeof updateLogiSummary === 'function') updateLogiSummary();
        renderQuoteLines();
    }

    function renderQuoteLines() {
        const q = state.selectedVenueId ? state.quotations[state.selectedVenueId] : null;
        const wrap = $('#q-lines-wrap');
        if (!q || !q.quotation) { wrap.classList.add('d-none'); $('#q-total').textContent = '₱0.00'; return; }
        const quot = q.quotation;
        const lines = [{ label: 'Venue rental (' + quot.days + (quot.days === 1 ? ' day' : ' days') + ', ' + quot.hours_per_day + 'h/day)', amount: quot.venue_cost }];
        (quot.arrangements || []).forEach((a) => { lines.push({ label: esc(a.name) + ' ×' + a.quantity, amount: a.subtotal }); });
        $('#q-lines').innerHTML = lines.map((l) => '<div class="rw-quote-line"><span>' + l.label + '</span><span>' + fmtMoney(l.amount) + '</span></div>').join('');
        wrap.classList.remove('d-none');
        if (quot.is_waived) {
            $('#q-total-label').textContent = 'TOTAL';
            $('#q-total-sub').textContent = 'Internal fees waived';
            $('#q-total').textContent = '₱0.00';
            $('#q-total-sub').style.display = 'none';
        } else {
            $('#q-total-label').textContent = 'ESTIMATED TOTAL';
            $('#q-total-sub').textContent = quot.security_deposit > 0 ? '+ Security deposit collected separately' : '';
            $('#q-total-sub').style.display = '';
            $('#q-total').textContent = fmtMoney(quot.grand_total);
            highlightQuoteValue($('#q-total'));
        }
    }

    /* ---------- Input Character Trapping & Filtering ---------- */
    const TITLE_BANNED_REGEX = /[^\p{L}\p{N}\s.,'"\/()\-&]/gu;
    const REASON_BANNED_REGEX = /[^\p{L}\p{N}\s.,'"\/()\-&?!:;\r\n]/gu;

    function bindFieldTrapping(el, countEl, bannedRegex, maxLen) {
        if (!el) return;
        const sanitize = () => {
            const orig = el.value;
            const clean = orig.replace(bannedRegex, '').slice(0, maxLen);
            if (orig !== clean) el.value = clean;
            if (countEl) countEl.textContent = el.value.length;
        };

        el.addEventListener('beforeinput', (e) => {
            if (e.data) {
                const cleanData = e.data.replace(bannedRegex, '');
                if (cleanData !== e.data) {
                    e.preventDefault();
                    if (cleanData && (el.value.length - (el.selectionEnd - el.selectionStart) + cleanData.length) <= maxLen) {
                        const start = el.selectionStart, end = el.selectionEnd;
                        el.value = el.value.slice(0, start) + cleanData + el.value.slice(end);
                        el.setSelectionRange(start + cleanData.length, start + cleanData.length);
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            }
        });

        el.addEventListener('input', sanitize);
        el.addEventListener('paste', () => setTimeout(sanitize, 0));
        sanitize();
    }

    const titleInput = $('#event_title');
    bindFieldTrapping(titleInput, $('#event_title_count'), TITLE_BANNED_REGEX, 50);
    if (titleInput) {
        titleInput.addEventListener('input', () => {
            const val = titleInput.value.trim();
            $('#q-event').textContent = val || 'Not set';
            highlightQuoteValue($('#q-event'));
            Draft.save();
        });
    }

    const reasonInput = $('#reason');
    bindFieldTrapping(reasonInput, $('#reason_count'), REASON_BANNED_REGEX, 400);
    if (reasonInput) {
        reasonInput.addEventListener('input', () => {
            state.purpose = reasonInput.value.trim();
            Draft.save();
            refreshReview();
        });
    }

    /* ---------- Schedule Picker Modal ---------- */
    const pick = { sd: null, ed: null, st: null, et: null };
    let calMonth = null;

    function addDaysStr(dateStr, days) {
        if (!dateStr) return dateStr;
        const [y, m, d] = dateStr.split('-').map(Number);
        const dt = new Date(y, m - 1, d);
        dt.setDate(dt.getDate() + days);
        return dt.getFullYear() + '-' + String(dt.getMonth() + 1).padStart(2, '0') + '-' + String(dt.getDate()).padStart(2, '0');
    }

    function fmtDur(st, et) {
        if (!st || !et) return '—';
        let [sh, sm] = st.split(':').map(Number), [eh, em] = et.split(':').map(Number);
        let mins = (eh * 60 + em) - (sh * 60 + sm);
        let isOvernight = false;
        if (mins < 0) {
            mins += 1440;
            isOvernight = true;
        }
        if (mins === 0) return '0m';
        const h = Math.floor(mins / 60), m = mins % 60;
        const durStr = h > 0 ? (h + 'h' + (m ? ' ' + m + 'm' : '')) : (m + 'm');
        return isOvernight ? `${durStr} (Overnight)` : durStr;
    }

    function pickRangeText() {
        if (!pick.sd) return 'No date chosen';
        const isOvernight = Boolean(pick.st && pick.et && m2m(pick.et) < m2m(pick.st));
        const ed = (isOvernight && (!pick.ed || pick.ed === pick.sd)) ? addDaysStr(pick.sd, 1) : (pick.ed || pick.sd);
        if (pick.sd === ed) return humanDate(pick.sd);
        const sameMonth = ed.slice(0, 7) === pick.sd.slice(0, 7);
        let range = sameMonth ? (humanDateShort(pick.sd) + ' – ' + humanDate(ed)) : (humanDate(pick.sd) + ' – ' + humanDate(ed));
        if (isOvernight) range += ' • Overnight';
        return range;
    }

    function clampMonth(ym) {
        if (ym < SETTINGS.minMonth) return SETTINGS.minMonth;
        if (ym > SETTINGS.maxMonth) return SETTINGS.maxMonth;
        return ym;
    }
    function setCalMonth(ym) {
        calMonth = clampMonth(ym);
        const [y, m] = calMonth.split('-').map(Number);
        $('#rw-cal-title').textContent = MON_LABELS[m - 1] + ' ' + y;
        const prevBtn = $('#rw-cal-prev');
        const nextBtn = $('#rw-cal-next');
        if (prevBtn) {
            const isMin = calMonth <= SETTINGS.minMonth;
            prevBtn.disabled = isMin;
            prevBtn.style.opacity = isMin ? '0.3' : '1';
        }
        if (nextBtn) {
            const isMax = calMonth >= SETTINGS.maxMonth;
            nextBtn.disabled = isMax;
            nextBtn.style.opacity = isMax ? '0.3' : '1';
        }
        renderCalGrid();
    }
    function renderCalGrid() {
        const grid = $('#rw-cal-grid');
        grid.innerHTML = '';
        const [y, m] = calMonth.split('-').map(Number);
        const first = new Date(y, m - 1, 1);
        const cells = 42;
        const startOff = first.getDay();
        const today = SETTINGS.today;
        const maxDate = SETTINGS.maxDate;

        for (let i = 0; i < cells; i++) {
            const dt = new Date(first);
            dt.setDate(dt.getDate() + (i - startOff));
            const d = dt.getFullYear() + '-' + String(dt.getMonth() + 1).padStart(2, '0') + '-' + String(dt.getDate()).padStart(2, '0');
            const cell = document.createElement('div');
            const inMonth = dt.getMonth() === m - 1;
            const past = d < today;
            const futureLocked = d > maxDate;
            const deadlineLocked = !past && (d < SETTINGS.earliestDate);
            const eligible = !past && !deadlineLocked && !futureLocked;

            cell.className = 'rw-cal-cell'
                + (inMonth ? '' : ' outside')
                + (past ? ' past' : '')
                + (deadlineLocked ? ' deadline-locked' : '')
                + (futureLocked ? ' future-locked' : '')
                + (d === today ? ' today' : '')
                + (eligible ? ' clickable' : '')
                + (d === pick.sd || d === pick.ed ? ' active' : '')
                + (pick.sd && pick.ed && d > pick.sd && d < pick.ed ? ' in-range' : '');
            cell.textContent = dt.getDate();

            if (eligible) {
                cell.addEventListener('click', () => onCalCellClick(d));
            }
            grid.appendChild(cell);
        }
    }

    function onCalCellClick(d) {
        if (!pick.sd || (pick.sd && pick.ed)) {
            pick.sd = d;
            pick.ed = null;
        } else if (pick.sd && !pick.ed) {
            if (d < pick.sd) {
                pick.ed = pick.sd;
                pick.sd = d;
            } else {
                pick.ed = d;
            }
        }
        refreshPickBanner();
        renderCalGrid();
    }

    function refreshPickBanner() {
        $('#rw-pick-range').textContent = pickRangeText();
        $('#rw-pick-dur').textContent = fmtDur(pick.st, pick.et);
    }

    function syncPickFromInputs() {
        pick.sd = $('#start_date').value || null;
        pick.ed = $('#end_date').value || null;
        pick.st = $('#start_time').value || null;
        pick.et = $('#end_time').value || null;
        if ($('#rw-pick-start-input')) $('#rw-pick-start-input').value = pick.st || '';
        if ($('#rw-pick-end-input')) $('#rw-pick-end-input').value = pick.et || '';
        refreshPickBanner();
    }

    function refreshTrigger() {
        const sd = $('#start_date').value, ed = $('#end_date').value, st = $('#start_time').value, et = $('#end_time').value;
        const disp = $('#rw-sched-display');
        const durDisp = $('#rw-sched-display-dur');
        if (sd && st) {
            const range = ed && ed !== sd ? humanDateShort(sd) + ' – ' + humanDate(ed) : humanDate(sd);
            disp.textContent = range + ' · ' + humanTime(st) + (et ? ' – ' + humanTime(et) : '');
            durDisp.textContent = fmtDur(st, et);
        } else {
            disp.textContent = 'Pick your date & time';
            durDisp.textContent = '';
        }
    }

    window.RW.openSchedPicker = function () {
        syncPickFromInputs();
        const base = pick.sd || SETTINGS.earliestDate || SETTINGS.today;
        setCalMonth(base.slice(0, 7));
        new bootstrap.Modal($('#rwSchedModal')).show();
    };

    window.RW.clearSched = function () {
        pick.sd = null; pick.ed = null; pick.st = null; pick.et = null;
        if ($('#rw-pick-start-input')) $('#rw-pick-start-input').value = '';
        if ($('#rw-pick-end-input')) $('#rw-pick-end-input').value = '';
        refreshPickBanner();
        renderCalGrid();
    };

    window.RW.applySched = function () {
        pick.st = $('#rw-pick-start-input').value || null;
        pick.et = $('#rw-pick-end-input').value || null;

        if (!pick.sd) { return showErrors(['Please select at least a start date.']); }
        if (!pick.st || !pick.et) { return showErrors(['Please set both a start time and an end time.']); }

        $('#start_date').value = pick.sd;
        let effEd = pick.ed || pick.sd;
        if (m2m(pick.et) < m2m(pick.st) && effEd === pick.sd) {
            effEd = addDaysStr(pick.sd, 1);
        }
        $('#end_date').value = effEd;
        $('#start_time').value = pick.st;
        $('#end_time').value = pick.et;

        refreshTrigger();
        updateQuotePanel();
        highlightQuoteValue($('#q-schedule'));
        highlightQuoteValue($('#q-schedule-sub'));
        Draft.save();
        bootstrap.Modal.getInstance($('#rwSchedModal')).hide();
        runMatch();
    };

    /* Duration chips */
    $$('.rw-dur-chip').forEach((btn) => {
        btn.addEventListener('click', () => {
            $$('.rw-dur-chip').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            const hrs = Number(btn.dataset.hours);
            if (!$('#start_time').value) $('#start_time').value = '08:00';
            const [sh, sm] = $('#start_time').value.split(':').map(Number);
            let totalM = sh * 60 + sm + hrs * 60;
            const endH = Math.floor(totalM / 60) % 24;
            const endM = totalM % 60;
            $('#end_time').value = String(endH).padStart(2, '0') + ':' + String(endM).padStart(2, '0');
            if (totalM >= 1440 && $('#start_date').value) {
                $('#end_date').value = addDaysStr($('#start_date').value, Math.floor(totalM / 1440));
            }
            refreshTrigger();
            updateQuotePanel();
            highlightQuoteValue($('#q-schedule'));
            highlightQuoteValue($('#q-schedule-sub'));
            Draft.save();
            runMatch();
        });
    });

    /* People Stepper */
    window.RW.adjustPeople = function (delta) {
        const inp = $('#number_of_people');
        let cur = Number(inp.value) || 0;
        cur = Math.max(1, Math.min(10000, cur + delta));
        inp.value = cur;
        inp.dispatchEvent(new Event('input', { bubbles: true }));
        updateQuotePanel();
        highlightQuoteValue($('#q-people'));
        Draft.save();
        runMatch();
    };
    $('#number_of_people').addEventListener('input', () => {
        updateQuotePanel();
        highlightQuoteValue($('#q-people'));
        Draft.save();
        clearTimeout(state.matchTimer);
        state.matchTimer = setTimeout(runMatch, 350);
    });

    /* ---------- Supporting Documents Dropzone ---------- */
    function initDropzone() {
        const dropzone = $('#rw-dropzone');
        const docInput = $('#rw_documents');
        if (!dropzone || !docInput) return;

        dropzone.addEventListener('click', () => docInput.click());
        docInput.addEventListener('change', () => {
            Array.from(docInput.files).forEach((file) => {
                if (attachedDataTransfer.items.length < 5) {
                    attachedDataTransfer.items.add(file);
                }
            });
            docInput.files = attachedDataTransfer.files;
            DraftFilesDB.saveFiles(attachedDataTransfer.files);
            renderFileList();
        });

        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            if (e.dataTransfer.files) {
                Array.from(e.dataTransfer.files).forEach((file) => {
                    if (attachedDataTransfer.items.length < 5) {
                        attachedDataTransfer.items.add(file);
                    }
                });
                docInput.files = attachedDataTransfer.files;
                DraftFilesDB.saveFiles(attachedDataTransfer.files);
                renderFileList();
            }
        });
    }

    function renderFileList() {
        const list = $('#rw-file-list');
        const badge = $('#rw-doc-count-badge');
        if (!list) return;
        const files = Array.from(attachedDataTransfer.files);
        if (badge) badge.textContent = `${files.length} / 5 files attached`;
        if (files.length === 0) {
            list.classList.add('d-none');
            list.innerHTML = '';
            return;
        }
        list.classList.remove('d-none');
        list.innerHTML = files.map((file, idx) => `
            <div class="rw-file-item">
                <div class="d-flex align-items-center gap-2 text-truncate">
                    <i class="bi bi-file-earmark-check"></i>
                    <span class="text-truncate fw-semibold">${esc(file.name)}</span>
                    <span class="text-muted small">(${Math.round(file.size / 1024)} KB)</span>
                </div>
                <button type="button" class="rw-file-remove" onclick="RW.removeFile(${idx})"><i class="bi bi-x-lg"></i></button>
            </div>
        `).join('');
    }

    window.RW.removeFile = function (idx) {
        const dt = new DataTransfer();
        Array.from(attachedDataTransfer.files).forEach((f, i) => { if (i !== idx) dt.items.add(f); });
        attachedDataTransfer = dt;
        const docInput = $('#rw_documents');
        if (docInput) docInput.files = attachedDataTransfer.files;
        DraftFilesDB.saveFiles(attachedDataTransfer.files);
        renderFileList();
    };

    /* ---------- Live Venue Matching & Quotations ---------- */
    async function runMatch() {
        const sd = $('#start_date').value, ed = $('#end_date').value, st = $('#start_time').value, et = $('#end_time').value;
        if (!sd || !st || !et) {
            $('#venue-empty').classList.remove('d-none');
            $('#match-summary').classList.add('d-none');
            return;
        }
        $('#venue-empty').classList.add('d-none');
        $('#match-summary').classList.remove('d-none');
        $('#match-summary-text').textContent = 'Checking real-time venue availability…';

        const params = new URLSearchParams({ start_date: sd, end_date: ed || sd, start_time: st, end_time: et });
        const people = Number($('#number_of_people').value);
        if (people > 0) params.set('expected_people', people);

        try {
            const res = await fetch(SETTINGS.matchUrl + '?' + params.toString());
            const data = await res.json();
            state.venues = data.facilities || [];
            renderVenues();
            const availCount = state.venues.filter((v) => v.available).length;
            $('#match-summary-text').textContent = `${availCount} venue${availCount === 1 ? '' : 's'} available for ${humanDateShort(sd)} (${humanTime(st)} – ${humanTime(et)})`;
            if (state.selectedVenueId) fetchQuotation();
        } catch (e) {
            $('#match-summary-text').textContent = 'Could not load venue availability.';
        }
    }

    function renderVenues() {
        const list = $('#venue-list');
        if (!list) return;
        if (state.venues.length === 0) {
            list.innerHTML = '<div class="p-4 text-center text-muted">No venues found matching this schedule.</div>';
            return;
        }
        const people = Number($('#number_of_people').value) || 0;
        list.innerHTML = state.venues.map((v) => {
            const isSel = v.id === state.selectedVenueId;
            const isAvail = v.available;
            const availCls = isAvail ? 'rw-avail-free' : 'rw-avail-blocked';
            const availText = isAvail ? 'Available' : 'Unavailable';
            const fitLabel = people > v.capacity ? '<span class="rw-tag text-warning"><i class="bi bi-exclamation-triangle"></i> Exceeds capacity (' + v.capacity + ')</span>' : '<span class="rw-tag"><i class="bi bi-people"></i> Fits ' + v.capacity + '</span>';

            return `
            <div class="rw-venue-card ${isSel ? 'selected' : ''} ${isAvail ? '' : 'blocked'}" data-venue-id="${v.id}" onclick="RW.cardClick(${v.id})">
                <div class="rw-venue-img" style="background-image:${v.image_url ? `url('${v.image_url}')` : 'none'}"></div>
                <div class="rw-venue-body">
                    <div>
                        <div class="rw-venue-head">
                            <div>
                                <span class="rw-venue-cat">${esc(v.category?.name || 'Venue')}</span>
                                <h4 class="rw-venue-name">${esc(v.name)}</h4>
                            </div>
                            <span class="rw-avail-pill ${availCls}"><i class="bi ${isAvail ? 'bi-check-circle-fill' : 'bi-slash-circle'}"></i> ${availText}</span>
                        </div>
                        <p class="rw-venue-desc">${esc(v.description || 'No description available.')}</p>
                    </div>
                    <div class="rw-venue-tags">
                        ${fitLabel}
                        <span class="rw-tag"><i class="bi bi-geo-alt"></i> ${esc(v.location || 'Campus')}</span>
                    </div>
                </div>
                <div class="rw-venue-side">
                    <div class="text-end">
                        <div class="rw-rate">${fmtMoney(v.price_per_hour)}/hr</div>
                        <div class="rw-rate-sub">Rate per hour</div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="event.stopPropagation(); RW.openVenueModal(${v.id})">
                            <i class="bi bi-info-circle"></i> Details
                        </button>
                        ${isAvail ? `
                            <button type="button" class="btn btn-sm rw-btn-primary rounded-pill px-3 fw-bold" onclick="event.stopPropagation(); RW.selectVenue(${v.id})">
                                ${isSel ? '<i class="bi bi-check-lg"></i> Selected' : 'Select'}
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    window.RW.cardClick = function (id) {
        const v = state.venues.find((x) => x.id === id);
        if (!v) return;
        if (!v.available) {
            showErrors([v.availability && v.availability.reason ? v.availability.reason : 'This venue is not available for your schedule.']);
            return;
        }
        RW.selectVenue(id);
    };

    window.RW.selectVenue = function (id) {
        state.selectedVenueId = id;
        $$('.rw-venue-card').forEach((c) => c.classList.toggle('selected', Number(c.dataset.venueId) === id));
        $('#hf_facility').value = id;
        updateQuotePanel();
        highlightQuoteValue($('#q-venue-card'));
        fetchQuotation();
        Draft.save();

        // Mark Section 2 complete, unlock Section 3 & progress smoothly to Section 3
        state.completedSections.add(2);
        state.unlockedSections.add(3);
        updateSummaryPill(2);

        setTimeout(() => {
            state.openSections.delete(2);
            state.activeSection = 3;
            state.openSections.add(3);
            updateSectionUI();

            const sec3 = document.getElementById('section-3');
            if (sec3) {
                sec3.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 250);
    };

    window.RW.openVenueModal = function (id) {
        const v = state.venues.find((x) => x.id === id);
        if (!v) return;
        $('#vm-title').textContent = v.name;
        $('#vm-cat').textContent = v.category ? v.category.name : 'Venue';
        $('#vm-hero').style.backgroundImage = v.image_url ? "url('" + v.image_url + "')" : 'none';
        $('#vm-capacity').textContent = v.capacity.toLocaleString() + ' guests';
        $('#vm-rate').textContent = fmtMoney(v.price_per_hour) + '/hr';
        $('#vm-desc').textContent = v.description || 'No description available.';
        $('#vm-loc').textContent = v.location || '—';
        const modalAmenities = (v.amenities || []).flatMap((a) => String(a ?? '').split(/\r?\n/)).map((a) => a.trim()).filter(Boolean);
        $('#vm-amenities').innerHTML = modalAmenities.length
            ? modalAmenities.map((a) => '<div class="rw-vm-amenity"><i class="bi bi-check2"></i><span>' + esc(a) + '</span></div>').join('')
            : '<div class="rw-vm-empty">No amenities listed for this venue.</div>';

        const blocks = (v.operating_blocks || []);
        $('#vm-hours').innerHTML = blocks.length ? blocks.map((b) => humanTime(b.start) + ' – ' + humanTime(b.end)).join(' · ') : 'Operating hours not configured for this date.';
        $('#vm-price').innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + fmtMoney(v.estimated_price) + ' for your schedule';

        const btn = $('#vm-select-btn');
        if (v.available) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Select this venue';
            btn.className = 'btn rw-btn-gold rounded-pill fw-bold';
        } else {
            btn.disabled = true;
            btn.className = 'btn btn-secondary rounded-pill fw-bold disabled';
            btn.innerHTML = '<i class="bi bi-x-lg me-1"></i> Not available';
        }
        $('#venueModal').dataset.venueId = id;
        new bootstrap.Modal($('#venueModal')).show();
    };

    window.RW.selectVenueFromModal = function () {
        const id = Number($('#venueModal').dataset.venueId || 0);
        if (id) { RW.selectVenue(id); }
        bootstrap.Modal.getInstance($('#venueModal')).hide();
    };

    /* ---------- Logistics Items Handling ---------- */
    window.RW.logiQty = function (id, delta) {
        const el = document.getElementById('rw-qty-' + id);
        if (!el) return;
        RW.setQty(id, Math.max(0, Math.min(Number(el.max), Number(el.value) + delta)));
    };

    window.RW.qtyInputChanged = function (id, val) {
        const el = document.getElementById('rw-qty-' + id);
        if (!el) return;
        let num = parseInt(val, 10);
        if (isNaN(num) || num < 0) num = 0;
        const max = Number(el.max) || 9999;
        if (num > max) num = max;
        RW.setQty(id, num);
    };

    window.RW.setQty = function (id, qty) {
        const el = document.getElementById('rw-qty-' + id);
        if (!el) return;
        el.value = qty;
        updateLogiSummary();
        Draft.save();
        if (state.selectedVenueId) fetchQuotation();
    };

    window.RW.clearAllLogi = function () {
        state.logiZeroConfirmed = false;
        $$('.rw-qty-input').forEach((el) => { el.value = 0; });
        updateLogiSummary();
        Draft.save();
        if (state.selectedVenueId) fetchQuotation();
    };

    window.RW.filterLogi = function (cat) {
        $$('.rw-logi-nav-item').forEach((b) => b.classList.toggle('active', b.dataset.filter === cat));
        $$('.rw-logi-group').forEach((grp) => {
            if (cat === 'all') grp.style.display = '';
            else grp.style.display = grp.dataset.categorySlug === cat ? '' : 'none';
        });
    };

    function arrangementsOf() {
        const out = [];
        $$('.rw-qty-input').forEach((inp) => {
            const q = Number(inp.value) || 0;
            if (q > 0) {
                out.push({
                    item_id: Number(inp.dataset.itemId),
                    name: inp.dataset.name,
                    quantity: q,
                    unit_price: Number(inp.dataset.price) || 0,
                    subtotal: q * (Number(inp.dataset.price) || 0)
                });
            }
        });
        return out;
    }

    function arrangementsJSON() {
        return JSON.stringify(arrangementsOf().map((a) => ({ item_id: a.item_id, quantity: a.quantity })));
    }

    function updateLogiSummary() {
        const arr = arrangementsOf();
        const totalItems = arr.reduce((s, a) => s + a.quantity, 0);
        const totalCost = arr.reduce((s, a) => s + a.subtotal, 0);

        const sumTitle = $('#logi-selection-summary');
        const sumCost = $('#logi-selection-cost');
        const clearBtn = $('#logi-clear-btn');
        if (sumTitle) {
            sumTitle.textContent = totalItems > 0 ? `${totalItems} arrangement${totalItems === 1 ? '' : 's'} selected` : 'No arrangements selected';
        }
        if (sumCost) {
            sumCost.textContent = `${fmtMoney(totalCost)} extra`;
        }
        if (clearBtn) {
            clearBtn.classList.toggle('d-none', totalItems === 0);
        }

        // Summary row in quote sidebar
        const qBadge = $('#q-logi-badge');
        const qText = $('#q-logi-summary-text');
        const qList = $('#q-logi-items-list');
        if (qBadge) qBadge.textContent = `${totalItems} item${totalItems === 1 ? '' : 's'}`;
        if (qText) qText.textContent = totalItems > 0 ? `${fmtMoney(totalCost)} add-ons` : 'None selected';
        if (qList) {
            if (arr.length > 0) {
                qList.classList.remove('d-none');
                qList.innerHTML = arr.map(a => `<div>• ${esc(a.name)} × ${a.quantity}</div>`).join('');
            } else {
                qList.classList.add('d-none');
                qList.innerHTML = '';
            }
        }
    }

    /* ---------- Quotation API ---------- */
    async function fetchQuotation() {
        if (!state.selectedVenueId) return;
        const sd = $('#start_date').value, ed = $('#end_date').value, st = $('#start_time').value, et = $('#end_time').value;
        if (!sd || !st || !et) return;

        const payload = {
            facility_id: state.selectedVenueId,
            start_date: sd,
            end_date: ed || sd,
            start_time: st,
            end_time: et,
            arrangements: arrangementsJSON()
        };

        try {
            const res = await fetch(SETTINGS.quotationUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': SETTINGS.csrf
                },
                body: JSON.stringify(payload)
            });
            if (!res.ok) {
                console.error('Quotation API error status:', res.status);
                return;
            }
            const data = await res.json();
            state.quotations[state.selectedVenueId] = data;
            renderQuoteLines();
            refreshReview();
        } catch (e) {
            console.error('Quotation fetch exception:', e);
        }
    }

    /* ---------- Review Section Refresh ---------- */
    function refreshReview() {
        $('#rev-event').textContent = $('#event_title').value.trim() || '—';
        $('#rev-purpose').textContent = state.purpose || $('#reason').value.trim() || '—';
        $('#rev-reason').textContent = state.purpose || $('#reason').value.trim() || '—';

        const sd = $('#start_date').value, ed = $('#end_date').value, st = $('#start_time').value, et = $('#end_time').value;
        if (sd && st) {
            $('#rev-schedule').textContent = (ed && ed !== sd ? humanDateShort(sd) + ' – ' + humanDate(ed) : humanDate(sd)) + ' · ' + humanTime(st) + (et ? ' – ' + humanTime(et) : '');
            $('#rev-duration').textContent = fmtDur(st, et);
        }

        const people = Number($('#number_of_people').value) || 0;
        $('#rev-people').textContent = people ? people.toLocaleString() + ' attendees' : '—';

        const v = state.selectedVenueId && state.venues.find((x) => x.id === state.selectedVenueId);
        if (v) {
            $('#rev-venue').textContent = v.name;
            $('#rev-venue-sub').textContent = v.location + ' · Fits ' + v.capacity.toLocaleString();
            if (people > v.capacity) {
                $('#rev-capacity-warning').classList.remove('d-none');
                $('#rev-cap-people').textContent = people.toLocaleString();
                $('#rev-cap-venue').textContent = v.name;
                $('#rev-cap-limit').textContent = v.capacity.toLocaleString();
            } else {
                $('#rev-capacity-warning').classList.add('d-none');
            }
        }

        const name = $('#reserved_by_name').value.trim();
        const endUserName = $('#end_user_name').value.trim();
        $('#rev-contact').textContent = name || '—';
        $('#rev-enduser').textContent = endUserName ? 'For: ' + endUserName : '—';

        const arr = arrangementsOf();
        $('#rev-logi').textContent = arr.length ? arr.length + ' item types (' + arr.reduce((s, a) => s + a.quantity, 0) + ' pcs)' : 'None selected';

        const docs = attachedDataTransfer.files;
        $('#rev-docs-title').textContent = docs.length ? `${docs.length} file(s) attached` : 'None attached';

        // Update quotation table in Review section
        const qData = state.selectedVenueId ? state.quotations[state.selectedVenueId] : null;
        if (qData && qData.quotation) {
            const quot = qData.quotation;
            let rowsHtml = `<tr><td>Venue Rental (${quot.days} day${quot.days === 1 ? '' : 's'}, ${quot.hours_per_day}h/day)</td><td>${fmtMoney(quot.venue_cost)}</td></tr>`;
            (quot.arrangements || []).forEach(a => {
                rowsHtml += `<tr><td>${esc(a.name)} × ${a.quantity}</td><td>${fmtMoney(a.subtotal)}</td></tr>`;
            });
            $('#quot-body').innerHTML = rowsHtml;
            $('#quot-subtotal').textContent = fmtMoney(quot.subtotal);
            if (quot.security_deposit > 0) {
                $('#quot-deposit-row').classList.remove('d-none');
                $('#quot-deposit').textContent = fmtMoney(quot.security_deposit);
            } else {
                $('#quot-deposit-row').classList.add('d-none');
            }
            $('#quot-total').textContent = quot.is_waived ? '₱0.00 (Fees Waived)' : fmtMoney(quot.grand_total);
        } else if (state.selectedVenueId) {
            $('#quot-body').innerHTML = '<tr><td colspan="2" class="rw-quot-empty text-center py-3 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Calculating quotation…</td></tr>';
            fetchQuotation();
        }
    }

    /* ---------- Payment Selection ---------- */
    window.RW.selectPayment = function (method) {
        const radio = $$('input[name="payment_method"]').find((r) => r.value === method);
        if (!radio) return;
        radio.checked = true;
        $$('.rw-pay-card').forEach((c) => c.classList.remove('rw-pay-selected'));
        const card = radio.closest('.rw-pay-card');
        if (card) card.classList.add('rw-pay-selected');
        Draft.save();
    };

    /* ---------- Modals: Terms & Cancellation ---------- */
    window.RW.openTermsModal = function (e) {
        if (e) e.preventDefault();
        new bootstrap.Modal($('#rwTermsModal')).show();
    };
    window.RW.acceptTermsFromModal = function () {
        const chk = $('#agreement_accepted_check');
        if (chk) chk.checked = true;
        bootstrap.Modal.getInstance($('#rwTermsModal')).hide();
    };
    window.RW.openCancelModal = function (e) {
        if (e) e.preventDefault();
        new bootstrap.Modal($('#rwCancelModal')).show();
    };

    /* ---------- Final Submission ---------- */
    window.RW.submit = async function () {
        if (state.submitLocked) return;

        // Ensure all steps are valid before submission
        for (let s = 1; s <= TOTAL_SECTIONS; s++) {
            if (!validateSection(s)) {
                RW.openSection(s);
                return;
            }
        }

        state.submitLocked = true;
        syncHiddenFields();

        const overlay = document.createElement('div');
        overlay.className = 'rw-submitting';
        overlay.innerHTML = '<div class="spinner-border" role="status"></div><span>Submitting your reservation…</span>';
        document.body.appendChild(overlay);

        await new Promise((r) => setTimeout(r, 80));
        $('#rw-post-form').submit();
    };

    function syncHiddenFields() {
        if ($('#hf_agree')) $('#hf_agree').value = $('#agreement_accepted_check') && $('#agreement_accepted_check').checked ? '1' : '';
        if ($('#hf_arrangements')) $('#hf_arrangements').value = arrangementsJSON();
        $('#hf_facility').value = state.selectedVenueId || '';
        $('#hf_payment').value = ($$('input[name="payment_method"]:checked')[0] || {}).value || '';
        $('#hf_event_title').value = $('#event_title').value;
        $('#hf_start_date').value = $('#start_date').value;
        $('#hf_end_date').value = $('#end_date').value || $('#start_date').value;
        $('#hf_start_time').value = $('#start_time').value;
        $('#hf_end_time').value = $('#end_time').value;
        $('#hf_people').value = $('#number_of_people').value;
        $('#hf_reason').value = $('#reason').value;
        $('#hf_rbn').value = $('#reserved_by_name').value;
        $('#hf_rbe').value = $('#reserved_by_email').value;
        $('#hf_rbp').value = $('#reserved_by_phone').value;
        $('#hf_eut').value = ($$('input[name="end_user_type"]').find((r) => r.checked) || {}).value || '';
        $('#hf_eun').value = $('#end_user_name').value;
        if ($('#hf_ebn')) $('#hf_ebn').value = ($('#endorsed_by_name') ? $('#endorsed_by_name').value : '') || '';
        if ($('#hf_ebp')) $('#hf_ebp').value = ($('#endorsed_by_position') ? $('#endorsed_by_position').value : '') || '';

        const docInput = $('#rw_documents');
        if (docInput && docInput.files.length > 0 && docInput.form !== $('#rw-post-form')) {
            $('#rw-post-form').appendChild(docInput);
        }
    }

    /* ---------- Initialization ---------- */
    function init() {
        initDropzone();
        ['start_date', 'end_date'].forEach((id) => { const el = document.getElementById(id); if (el) el.min = SETTINGS.earliestDate; });
        updateQuotePanel();
        updateLogiSummary();
        updateSectionUI();

        const draft = Draft.load();
        const hasDraft = draft && (
            draft.event_title ||
            draft.reason ||
            draft.start_date ||
            draft.number_of_people ||
            draft.selected_venue_id
        );
        if (SETTINGS.serverError && hasDraft) {
            RW.restoreDraft();
        } else if (hasDraft) {
            $('#draft-banner').classList.remove('d-none');
        }

        const enabled = (SETTINGS.cashEnabled ? 1 : 0) + (SETTINGS.paymongoEnabled ? 1 : 0);
        if (enabled === 1 && $$('input[name="payment_method"]').length && !$$('input[name="payment_method"]:checked').length) {
            $$('input[name="payment_method"]')[0].checked = true;
        }

        if (!$('#start_date').value) $('#venue-empty').classList.remove('d-none');
        $$('.rw-input.invalid').forEach((e) => e.classList.remove('invalid'));

        // Dynamic end-user type listeners and placeholder updater
        const updateEndUserPlaceholder = () => {
            const checked = $$('input[name="end_user_type"]').find(r => r.checked);
            const val = checked ? checked.value : '';
            const eun = $('#end_user_name');
            const placeholders = {
                college: 'e.g. College of Engineering',
                department: 'e.g. Department of Computer Studies',
                organization: 'e.g. Tech Summit Guild / Organization Name',
                personal: 'e.g. Personal Use / Individual Name'
            };
            if (eun && placeholders[val]) {
                eun.placeholder = placeholders[val];
            }
        };
        $$('input[name="end_user_type"]').forEach(r => {
            r.addEventListener('change', () => {
                updateEndUserPlaceholder();
                Draft.save();
                refreshReview();
            });
        });
        const eunEl = $('#end_user_name');
        if (eunEl) {
            eunEl.addEventListener('input', () => {
                Draft.save();
                refreshReview();
            });
        }
        updateEndUserPlaceholder();

        // Check URL query parameters for facility preselection
        const urlParams = new URLSearchParams(window.location.search);
        const facilityParam = urlParams.get('facility_id') || urlParams.get('facility');
        if (facilityParam) {
            state.selectedVenueId = Number(facilityParam);
        }
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/guest/reserve.blade.php ENDPATH**/ ?>