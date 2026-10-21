@extends('layouts.auth')

@section('title', 'Create account')
@section('card_size', 'auth-card-lg')
@section('panel_eyebrow')
    Official University System
@endsection
@section('panel_title')
    Join us &mdash; your campus, <span class="gold">one account away</span>.
@endsection
@section('panel_sub')
    A single account unlocks every space on campus &mdash; gyms, courts, auditoriums and more. Booking takes only minutes.
@endsection

@section('header_action')
    <a href="{{ route('login') }}" class="auth-header-action">Sign in</a>
@endsection

@section('auth_title', 'Create your account')
@section('auth_subtitle')
    It only takes a few minutes. Already have an account? <a href="{{ route('login') }}">Sign in</a>
@endsection

@section('content')
@if(session('registration_error'))
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-3" role="alert" style="border-radius: 10px; border: 1px solid #fca5a5; background: #fef2f2; padding: 1rem 1.15rem;">
        <i class="bi bi-exclamation-triangle-fill text-danger mt-1" style="font-size: 1.1rem; flex-shrink: 0;"></i>
        <div>
            <strong class="d-block mb-1" style="color: #991b1b;">Registration Failed</strong>
            <span style="color: #b91c1c; font-size: 0.9rem;">{{ session('registration_error') }}</span>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-3" role="alert" style="border-radius: 10px; border: 1px solid #fca5a5; background: #fef2f2; padding: 1rem 1.15rem;">
        <i class="bi bi-x-circle-fill text-danger mt-1" style="font-size: 1.1rem; flex-shrink: 0;"></i>
        <div>
            <strong class="d-block mb-1" style="color: #991b1b;">Please fix the following errors:</strong>
            <ul class="mb-0 ps-3" style="color: #b91c1c; font-size: 0.85rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
    @csrf

    {{-- Inline validation: errors are rendered per-field, directly under
         the offending input, instead of a single banner. See the
         validateRegistrationForm() script at the bottom of this file. --}}

    <div class="row g-3 mb-3">
        <!-- First Name -->
        <div class="col-md-6">
            <label for="first_name" class="form-label">First Name</label>
            {{-- autofocus removed: on mobile it opens the keyboard on load,
                 which hides most of an already long form. --}}
            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                   name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name"
                   autocapitalize="words" enterkeyhint="next"
                   placeholder="First Name">
            @error('first_name')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Middle Name (Optional) -->
        <div class="col-md-6">
            <label for="middle_name" class="form-label">Middle Name <span class="text-muted small">(Optional)</span></label>
            <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror"
                   name="middle_name" value="{{ old('middle_name') }}" autocomplete="off"
                   placeholder="Middle Name">
            @error('middle_name')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row g-3 mb-3">
        <!-- Last Name -->
        <div class="col-md-12">
            <label for="last_name" class="form-label">Last Name</label>
            <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                   name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name"
                   placeholder="Last Name">
            @error('last_name')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Client Type Selection -->
    <div class="mb-4">
        <label class="form-label d-block mb-3 fw-bold text-dark">Client Classification</label>
        <div class="row g-3">
            <div class="col-md-6">
                <input type="radio" class="btn-check" name="client_type" id="client_internal" value="internal" {{ old('client_type', 'internal') === 'internal' ? 'checked' : '' }} autocomplete="off">
                <label class="btn btn-outline-primary w-100 p-3 text-start d-flex align-items-center gap-3 rounded-3" for="client_internal" style="border-width: 2px;">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark mb-0">Internal Client</div>
                        <div class="small text-muted" style="font-size: 0.78rem;">University Student or Employee</div>
                    </div>
                </label>
            </div>
            <div class="col-md-6">
                <input type="radio" class="btn-check" name="client_type" id="client_external" value="external" {{ old('client_type') === 'external' ? 'checked' : '' }} autocomplete="off">
                <label class="btn btn-outline-primary w-100 p-3 text-start d-flex align-items-center gap-3 rounded-3" for="client_external" style="border-width: 2px;">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 text-success">
                        <i class="bi bi-globe fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark mb-0">External Client</div>
                        <div class="small text-muted" style="font-size: 0.78rem;">General Public / Organization</div>
                    </div>
                </label>
            </div>
        </div>
        @error('client_type')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Contact Number -->
    <div class="mb-3">
        <label class="form-label">Mobile / Contact Number <span class="text-danger">*</span></label>
        <x-international-phone-input
            name="contact_number"
            id="contact_number"
            :value="old('contact_number')"
            required
            theme="bootstrap"
            helper="Select country code and enter digits for reservation and security alerts"
        />
    </div>

    <!-- Email Address -->
    <div class="mb-3">
        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required autocomplete="username"
               inputmode="email" autocapitalize="none" autocorrect="off" spellcheck="false"
               enterkeyhint="next"
               placeholder="name@example.com">
        <div class="form-text small text-muted">Used for login and booking confirmations.</div>
        @error('email')
            <div class="invalid-feedback small">{{ $message }}</div>
        @enderror
    </div>

    <!-- ─── Personal Details (Universal) ─── -->
    <div class="card p-3 border rounded-3 mb-4" style="background-color: #fafbfc;">
        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-person-vcard text-primary me-2"></i>Personal Details</h6>

        <!-- Date of Birth -->
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                   name="date_of_birth" value="{{ old('date_of_birth') }}" required>
            <div class="form-text small text-muted">Must be 18 years old or older.</div>
            @error('date_of_birth')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Sex & Civil Status -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="sex" class="form-label">Sex <span class="text-danger">*</span></label>
                <select id="sex" name="sex" class="form-select @error('sex') is-invalid @enderror" required>
                    <option value="" disabled selected>Select Sex</option>
                    <option value="Male"   {{ old('sex') === 'Male'   ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('sex')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                <select id="civil_status" name="civil_status" class="form-select @error('civil_status') is-invalid @enderror" required>
                    <option value="" disabled selected>Select Civil Status</option>
                    <option value="Single"    {{ old('civil_status') === 'Single'    ? 'selected' : '' }}>Single</option>
                    <option value="Married"   {{ old('civil_status') === 'Married'   ? 'selected' : '' }}>Married</option>
                    <option value="Widowed"   {{ old('civil_status') === 'Widowed'   ? 'selected' : '' }}>Widowed</option>
                    <option value="Separated" {{ old('civil_status') === 'Separated' ? 'selected' : '' }}>Separated</option>
                </select>
                @error('civil_status')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Nationality -->
        <div class="mb-3">
            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
            <input id="nationality" type="text" class="form-control @error('nationality') is-invalid @enderror"
                   name="nationality" value="{{ old('nationality', 'Filipino') }}" required placeholder="e.g., Filipino">
            @error('nationality')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Personal / Home Address -->
        <div class="mb-0">
            <label for="personal_address" class="form-label">Personal / Home Address <span class="text-danger">*</span></label>
            <textarea id="personal_address" class="form-control @error('personal_address') is-invalid @enderror"
                      name="personal_address" rows="2" placeholder="Street Address, Barangay, City, Province, ZIP Code" required>{{ old('personal_address') }}</textarea>
            <div class="form-text small text-muted">Your permanent or current residential address.</div>
            @error('personal_address')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- ─── Internal Fields ─── -->
    <div id="internalFields" class="d-none">
        <div class="card p-3 border rounded-3 mb-4" style="background-color: #fafbfc;">
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-shield-lock text-primary me-2"></i>Institutional Vetting Details</h6>
            
            <div class="mb-3">
                <label for="university_email" class="form-label">Official University Email <span class="text-danger">*</span></label>
                <input id="university_email" type="email" class="form-control @error('university_email') is-invalid @enderror"
                       name="university_email" value="{{ old('university_email') }}"
                       inputmode="email" autocapitalize="none" autocorrect="off" spellcheck="false"
                       enterkeyhint="next"
                       placeholder="your.university@email.com" required>
                <div class="form-text small text-muted">Use your official university or institutional email. It is your responsibility to ensure this is a valid university-related account.</div>
                @error('university_email')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="university_id" class="form-label">University ID Number <span class="text-danger">*</span></label>
                <input id="university_id" type="text" class="form-control @error('university_id') is-invalid @enderror"
                       name="university_id" value="{{ old('university_id') }}" placeholder="e.g., 2023-01234" required>
                <div class="form-text small text-muted">Cross-referenced by admin to verify your identity.</div>
                @error('university_id')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="affiliation_type" class="form-label">Affiliation Type <span class="text-danger">*</span></label>
                    <select id="affiliation_type" name="affiliation_type" class="form-select @error('affiliation_type') is-invalid @enderror" required>
                        <option value="" disabled selected>Select Affiliation</option>
                        <option value="Student" {{ old('affiliation_type') === 'Student' ? 'selected' : '' }}>Student</option>
                        <option value="Faculty" {{ old('affiliation_type') === 'Faculty' ? 'selected' : '' }}>Faculty</option>
                        <option value="Admin/Staff" {{ old('affiliation_type') === 'Admin/Staff' ? 'selected' : '' }}>Admin/Staff</option>
                    </select>
                    @error('affiliation_type')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="college_department" class="form-label">College / Department <span class="text-danger">*</span></label>
                    <select id="college_department" name="college_department" class="form-select @error('college_department') is-invalid @enderror" required>
                        <option value="" disabled selected>Select College/Dept</option>
                        <option value="College of Engineering" {{ old('college_department') === 'College of Engineering' ? 'selected' : '' }}>College of Engineering</option>
                        <option value="College of Education" {{ old('college_department') === 'College of Education' ? 'selected' : '' }}>College of Education</option>
                        <option value="College of Arts and Sciences" {{ old('college_department') === 'College of Arts and Sciences' ? 'selected' : '' }}>College of Arts and Sciences</option>
                        <option value="College of Business and Accountancy" {{ old('college_department') === 'College of Business and Accountancy' ? 'selected' : '' }}>College of Business and Accountancy</option>
                        <option value="College of Information and Communications Technology" {{ old('college_department') === 'College of Information and Communications Technology' ? 'selected' : '' }}>College of Information and Communications Technology</option>
                        <option value="College of Architecture and Fine Arts" {{ old('college_department') === 'College of Architecture and Fine Arts' ? 'selected' : '' }}>College of Architecture and Fine Arts</option>
                        <option value="College of Nursing" {{ old('college_department') === 'College of Nursing' ? 'selected' : '' }}>College of Nursing</option>
                        <option value="School of Graduate Studies" {{ old('college_department') === 'School of Graduate Studies' ? 'selected' : '' }}>School of Graduate Studies</option>
                        <option value="Administration / General Staff" {{ old('college_department') === 'Administration / General Staff' ? 'selected' : '' }}>Administration / General Staff</option>
                    </select>
                    @error('college_department')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <!-- ─── External Fields ─── -->
    <div id="externalFields" class="d-none">
        <div class="card p-3 border rounded-3 mb-4" style="background-color: #fafbfc;">
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-globe text-success me-2"></i>External Client Billing &amp; Identity</h6>

            <div class="mb-3">
                <label for="organization_name" class="form-label">Organization / Company <span class="text-muted small">(Optional)</span></label>
                <input id="organization_name" type="text" class="form-control @error('organization_name') is-invalid @enderror"
                       name="organization_name" value="{{ old('organization_name') }}" placeholder="Defaults to Individual">
                @error('organization_name')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="billing_address" class="form-label">Complete Billing Address <span class="text-danger">*</span></label>
                <textarea id="billing_address" class="form-control @error('billing_address') is-invalid @enderror"
                          name="billing_address" rows="2" placeholder="Street Address, City, Province, ZIP Code" required>{{ old('billing_address') }}</textarea>
                <div class="form-text small text-muted">Required for generating invoices and tax receipts.</div>
                @error('billing_address')
                    <div class="invalid-feedback small">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="government_id_type" class="form-label">Government ID Type <span class="text-danger">*</span></label>
                    <select id="government_id_type" name="government_id_type" class="form-select @error('government_id_type') is-invalid @enderror" required>
                        <option value="" disabled selected>Select ID Type</option>
                        <option value="UMID" {{ old('government_id_type') === 'UMID' ? 'selected' : '' }}>UMID</option>
                        <option value="SSS" {{ old('government_id_type') === 'SSS' ? 'selected' : '' }}>SSS</option>
                        <option value="GSIS" {{ old('government_id_type') === 'GSIS' ? 'selected' : '' }}>GSIS</option>
                        <option value="Driver License" {{ old('government_id_type') === 'Driver License' ? 'selected' : '' }}>Driver's License</option>
                        <option value="Passport" {{ old('government_id_type') === 'Passport' ? 'selected' : '' }}>Passport</option>
                        <option value="PRC ID" {{ old('government_id_type') === 'PRC ID' ? 'selected' : '' }}>PRC ID</option>
                        <option value="Postal ID" {{ old('government_id_type') === 'Postal ID' ? 'selected' : '' }}>Postal ID</option>
                        <option value="National ID (PhilID)" {{ old('government_id_type') === 'National ID (PhilID)' ? 'selected' : '' }}>National ID (PhilID)</option>
                        <option value="Voter ID" {{ old('government_id_type') === 'Voter ID' ? 'selected' : '' }}>Voter's ID</option>
                    </select>
                    @error('government_id_type')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="government_id_number" class="form-label">Government ID Number <span class="text-danger">*</span></label>
                    <input id="government_id_number" type="text" class="form-control @error('government_id_number') is-invalid @enderror"
                           name="government_id_number" value="{{ old('government_id_number') }}" placeholder="ID Number" required>
                    @error('government_id_number')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
        <div class="password-field">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="new-password" placeholder="Create a strong password">
        </div>
        @error('password')
            <div class="invalid-feedback small d-block">{{ $message }}</div>
        @enderror

        {{-- Password Strength Meter --}}
        @include('components.password-strength-meter', [
            'passwordId' => 'password',
            'confirmId'  => 'password_confirmation',
        ])
    </div>

    <!-- Confirm Password -->
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
        <div class="password-field">
            <input id="password_confirmation" type="password" class="form-control"
                   name="password_confirmation" required autocomplete="new-password"
                   placeholder="Repeat your password">
        </div>
    </div>

    {{-- ─── Legal Agreement ─────────────────────────────────────────── --}}
    <div class="legal-agreement-box mb-1" id="legalAgreementBox">
        <div class="d-flex align-items-start gap-2">
            {{-- Hidden submit enforcer --}}
            <input type="hidden" name="terms_agreed" value="{{ session('registration_legal_agreed') ? '1' : '0' }}" id="termsAgreedHidden">

            <div class="form-check mt-1" style="flex-shrink:0;">
                <input class="form-check-input" type="checkbox" id="termsCheckbox" {{ session('registration_legal_agreed') ? 'checked' : '' }} {{ session('registration_legal_agreed') ? 'disabled' : '' }}>
            </div>
            <label for="termsCheckbox" class="form-check-label small" style="cursor:pointer;">
                I have read and agree to the
                <button type="button" class="legal-link-btn" id="openLegalBtn">
                    Terms of Service, Privacy Policy, and Data Collection Notice
                </button>
                <span class="text-danger">*</span>
            </label>
        </div>
        <div id="legalStatusRow" class="d-flex gap-2 mt-2 flex-wrap" style="padding-left:1.6rem;">
            <span class="legal-pill" id="pill-tos"><i class="bi bi-x-circle text-danger"></i> Terms of Service</span>
            <span class="legal-pill" id="pill-privacy"><i class="bi bi-x-circle text-danger"></i> Privacy Policy</span>
            <span class="legal-pill" id="pill-data"><i class="bi bi-x-circle text-danger"></i> Data Collection</span>
        </div>
        <div id="legalAlreadyAgreed" class="text-muted small mt-1 d-none" style="padding-left:1.6rem;">
            <i class="bi bi-info-circle-fill text-primary me-1"></i>
            You already read and agreed to all legal documents on your previous attempt &mdash; no need to read them again. Just fix the errors above and submit.
        </div>
        <div id="legalError" class="text-danger small mt-1" style="display:none;padding-left:1.6rem;">
            You must read and agree to all legal documents before continuing.
        </div>
    </div>

    <button type="submit" class="btn btn-sure" id="submitBtn" disabled>
        Create Account
    </button>
</form>
@endsection

@section('modals')
{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- Legal Documents Modal                                              --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="legalModal" tabindex="-1" aria-labelledby="legalModalLabel" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width:860px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;height:82vh;">

            {{-- Header --}}
            <div class="modal-header border-0 pb-0 px-4 pt-4" style="background:#f8fafc;">
                <div class="w-100">
                    <h5 class="fw-bold mb-1" style="font-size:1.1rem;">
                        <i class="bi bi-shield-check text-primary me-2"></i>
                        Legal Documents — Please read before continuing
                    </h5>
                    <p class="text-muted small mb-0">
                        Scroll to the bottom of each tab to unlock it.
                        All three must be read before you can agree and create your account.
                    </p>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div class="px-4 pt-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <ul class="nav nav-tabs border-0 gap-1" id="legalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link legal-tab active" id="tab-tos-btn"
                                data-bs-toggle="tab" data-bs-target="#tab-tos"
                                type="button" role="tab" data-doc="tos">
                            <span class="tab-status-icon me-1 text-danger" id="icon-tos"><i class="bi bi-x-circle-fill"></i></span>
                            Terms of Service
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link legal-tab" id="tab-privacy-btn"
                                data-bs-toggle="tab" data-bs-target="#tab-privacy"
                                type="button" role="tab" data-doc="privacy">
                            <span class="tab-status-icon me-1 text-danger" id="icon-privacy"><i class="bi bi-x-circle-fill"></i></span>
                            Privacy Policy
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link legal-tab" id="tab-data-btn"
                                data-bs-toggle="tab" data-bs-target="#tab-data"
                                type="button" role="tab" data-doc="data">
                            <span class="tab-status-icon me-1 text-danger" id="icon-data"><i class="bi bi-x-circle-fill"></i></span>
                            Data Collection Notice
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Tab Content --}}
            <div class="tab-content d-flex flex-column flex-grow-1 overflow-hidden" id="legalTabContent">
                {{-- ToS --}}
                <div class="tab-pane fade show active h-100" id="tab-tos" role="tabpanel">
                    <div class="d-flex flex-column h-100">
                        @include('legal._document', ['doc' => 'terms', 'slug' => 'tos'])
                        <div class="legal-scroll-notice" id="notice-tos">
                            <i class="bi bi-arrow-down-circle me-1"></i>
                            Scroll to the bottom to mark as read
                        </div>
                        <div class="legal-read-badge" id="read-tos" style="display:none;">
                            <i class="bi bi-check-circle-fill me-1 text-success"></i>
                            Terms of Service — Read &amp; Acknowledged
                        </div>
                    </div>
                </div>

                {{-- Privacy --}}
                <div class="tab-pane fade h-100" id="tab-privacy" role="tabpanel">
                    <div class="d-flex flex-column h-100">
                        @include('legal._document', ['doc' => 'privacy', 'slug' => 'privacy'])
                        <div class="legal-scroll-notice" id="notice-privacy">
                            <i class="bi bi-arrow-down-circle me-1"></i>
                            Scroll to the bottom to mark as read
                        </div>
                        <div class="legal-read-badge" id="read-privacy" style="display:none;">
                            <i class="bi bi-check-circle-fill me-1 text-success"></i>
                            Privacy Policy — Read &amp; Acknowledged
                        </div>
                    </div>
                </div>

                {{-- Data Collection --}}
                <div class="tab-pane fade h-100" id="tab-data" role="tabpanel">
                    <div class="d-flex flex-column h-100">
                        @include('legal._document', ['doc' => 'data_collection', 'slug' => 'data'])
                        <div class="legal-scroll-notice" id="notice-data">
                            <i class="bi bi-arrow-down-circle me-1"></i>
                            Scroll to the bottom to mark as read
                        </div>
                        <div class="legal-read-badge" id="read-data" style="display:none;">
                            <i class="bi bi-check-circle-fill me-1 text-success"></i>
                            Data Collection Notice — Read &amp; Acknowledged
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-top px-4 py-3 d-flex justify-content-between align-items-center"
                 style="background:#f8fafc;">
                <div class="d-flex gap-2" id="modalPillsFooter">
                    <span class="legal-pill-sm pending" id="fpill-tos"><i class="bi bi-x-circle text-danger me-1"></i>ToS</span>
                    <span class="legal-pill-sm pending" id="fpill-privacy"><i class="bi bi-x-circle text-danger me-1"></i>Privacy</span>
                    <span class="legal-pill-sm pending" id="fpill-data"><i class="bi bi-x-circle text-danger me-1"></i>Data</span>
                </div>
                <button type="button" class="btn btn-primary fw-bold px-5" id="legalAgreeBtn" disabled
                        style="border-radius:8px;">
                    I Agree &amp; Continue
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ─── Styles ──────────────────────────────────────────────────────── --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Agreement box */
    .legal-agreement-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.875rem 1rem;
    }
    .legal-link-btn {
        background: none;
        border: none;
        padding: 0;
        color: var(--brand-primary, #2563eb);
        font-weight: 600;
        font-size: inherit;
        text-decoration: underline;
        cursor: pointer;
        transition: opacity 0.15s;
    }
    .legal-link-btn:hover { opacity: 0.75; }

    /* Status pills on the form */
    .legal-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 999px;
        background: #e2e8f0;
        color: #64748b;
        transition: all 0.25s;
    }
    .legal-pill.done {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Small footer pills in modal */
    .legal-pill-sm {
        display: inline-flex;
        align-items: center;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        background: #e2e8f0;
        color: #64748b;
        transition: all 0.25s;
    }
    .legal-pill-sm.pending {
        background: #fee2e2;
        color: #b91c1c;
    }
    .legal-pill-sm.done {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Tab styles */
    .nav-pills-sm .nav-link {
        font-size: 0.72rem;
        font-weight: 600;
        border-radius: 999px;
        color: #64748b;
        transition: all 0.2s;
    }
    .nav-pills-sm .nav-link.active {
        background-color: var(--brand-primary, #2563eb);
        color: #fff;
    }

    .legal-tab {
        font-size: 0.82rem;
        font-weight: 600;
        border-radius: 8px 8px 0 0 !important;
        border: none !important;
        color: #64748b;
        background: transparent;
        padding: 0.5rem 1rem;
        transition: all 0.15s;
    }
    .legal-tab.active {
        color: var(--brand-primary, #2563eb) !important;
        background: #fff !important;
        border-bottom: 2px solid var(--brand-primary, #2563eb) !important;
    }
    .legal-tab .tab-status-icon {
        font-size: 0.75rem;
        transition: color 0.2s;
    }
    .legal-tab.read .tab-status-icon {
        color: #16a34a !important;
    }

    /* Scrollable doc body */
    .legal-scroll-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.75rem 2rem;
        font-size: 0.875rem;
        line-height: 1.7;
        color: #334155;
        background: #fff;
    }
    .legal-scroll-body h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .legal-scroll-body h3 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 1.25rem;
        margin-bottom: 0.35rem;
    }
    .legal-scroll-body p { margin-bottom: 0.75rem; }
    .legal-scroll-body ul { padding-left: 1.25rem; margin-bottom: 0.75rem; }

    /* Scroll CTA / Read badge */
    .legal-scroll-notice {
        padding: 0.6rem 1.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        background: #fffbeb;
        border-top: 1px solid #fde68a;
    }
    .legal-read-badge {
        padding: 0.6rem 1.75rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #15803d;
        background: #f0fdf4;
        border-top: 1px solid #bbf7d0;
    }

    /* Checkbox enables once all read */
    #termsCheckbox:not(:disabled) { cursor: pointer; }
    #submitBtn:not(:disabled) { cursor: pointer; }

    /* ════════════════════════════════════════════════════════════
       MOBILE — registration
       ------------------------------------------------------------
       The form itself relies on Bootstrap's col-md-* pairs, which
       already stack under 768px. What did not work on mobile was
       the legal modal: a fixed 82vh box with three long tab labels.
       ════════════════════════════════════════════════════════════ */
    @media (max-width: 767.98px) {

        /* Go edge to edge. A centred, inset dialog wastes width that
           the scroll-to-unlock document text badly needs. */
        #legalModal .modal-dialog {
            max-width: 100% !important;
            margin: 0;
            height: 100%;
        }
        #legalModal .modal-content {
            /* dvh tracks the collapsing URL bar in mobile browsers;
               82vh measured against the large viewport and overflowed
               whenever the bar was expanded. Fixed height is the
               fallback order for engines without dvh support. */
            height: 100vh !important;
            height: 100dvh !important;
            border-radius: 0 !important;
        }
        #legalModal .modal-header {
            padding: 1rem 1rem 0 !important;
        }
        #legalModal .modal-header h5 { font-size: 0.98rem; }

        /* Three tabs will not fit on one row at 360px. Let the strip
           scroll horizontally instead of wrapping into a tall stack
           that eats the reading area. */
        #legalTabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 2px;
        }
        #legalTabs::-webkit-scrollbar { display: none; }
        #legalTabs .nav-item { flex: 0 0 auto; }
        #legalTabs .legal-tab {
            white-space: nowrap;
            font-size: 0.8rem;
            padding: 0.5rem 0.7rem;
        }

        /* Larger text and padding make the long documents readable
           and give the scroll-to-bottom gesture room to work. */
        .legal-scroll-body {
            font-size: 0.9rem;
            line-height: 1.6;
            padding: 1rem !important;
        }

        .legal-agreement-box { padding: 0.85rem; }

        /* Client-type selector cards are tap targets, not buttons. */
        label.btn-outline-primary { padding: 0.9rem !important; }
    }

    /* Custom radio check styling for client classification cards */
    .btn-check:checked + label.btn-outline-primary {
        background-color: rgba(var(--brand-primary-rgb, 37, 99, 235), 0.04);
        border-color: var(--brand-primary, #2563eb) !important;
        box-shadow: 0 0 0 4px rgba(var(--brand-primary-rgb, 37, 99, 235), 0.1);
    }
    .btn-check:checked + label.btn-outline-primary .text-dark {
        color: var(--brand-primary, #2563eb) !important;
    }
    label.btn-outline-primary {
        border: 2px solid #e2e8f0 !important;
        background-color: #fff;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    label.btn-outline-primary:hover {
        border-color: #cbd5e1 !important;
        background-color: #f8fafc;
        color: inherit !important;
    }
</style>

{{-- ─── Script ──────────────────────────────────────────────────────── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Client Type fields toggle logic ──────────────────────────────
    const internalRadio = document.getElementById('client_internal');
    const externalRadio = document.getElementById('client_external');
    const internalFields = document.getElementById('internalFields');
    const externalFields = document.getElementById('externalFields');

    function toggleFields() {
        if (internalRadio.checked) {
            internalFields.classList.remove('d-none');
            externalFields.classList.add('d-none');
            toggleInputs(internalFields, true);
            toggleInputs(externalFields, false);
        } else if (externalRadio.checked) {
            internalFields.classList.add('d-none');
            externalFields.classList.remove('d-none');
            toggleInputs(internalFields, false);
            toggleInputs(externalFields, true);
        }
    }

    function toggleInputs(container, enabled) {
        container.querySelectorAll('input, select, textarea').forEach(input => {
            if (enabled) {
                input.removeAttribute('disabled');
            } else {
                input.setAttribute('disabled', 'disabled');
            }
        });
        // A section that just became irrelevant must lose any leftover red
        // highlights from a previous submit attempt.
        if (!enabled) {
            container.querySelectorAll('input, select, textarea').forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
                try { touchedFields.delete(input.name); } catch (_) { /* called before the Set is declared */ }
            });
            container.querySelectorAll('div[data-inline-error]').forEach(n => n.remove());
        }
    }

    if (internalRadio && externalRadio) {
        internalRadio.addEventListener('change', toggleFields);
        externalRadio.addEventListener('change', toggleFields);
        toggleFields(); // Run on initial load
    }

    const DOCS = ['tos', 'privacy', 'data'];
    const readState = { tos: false, privacy: false, data: false };

    // ── Restore previously-given legal agreement ─────────────────────
    // If the server bounced the form back (validation error, duplicate
    // email, etc.), the applicant already read and agreed to the legal
    // documents. We keep the checkbox checked (server remembered via
    // session) and lock it in the "agreed" state so they can't be made
    // to re-read anything.
    const legalAlreadyAgreed = @json(session('registration_legal_agreed', false));
    const legalAlreadyAgreedEl = document.getElementById('legalAlreadyAgreed');

    const docLabels = { tos: 'Terms of Service', privacy: 'Privacy Policy', data: 'Data Collection' };

    function restoreLegalAgreement() {
        readState.tos = readState.privacy = readState.data = true;

        DOCS.forEach(doc => {
            const pill = document.getElementById('pill-' + doc);
            if (pill) { pill.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + docLabels[doc]; pill.classList.add('done'); }

            const fpill = document.getElementById('fpill-' + doc);
            if (fpill) { fpill.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>' + docLabels[doc]; fpill.classList.remove('pending'); fpill.classList.add('done'); }

            document.getElementById('notice-' + doc).style.display = 'none';
            document.getElementById('read-' + doc).style.display = 'block';

            const tabBtn = document.querySelector(`[data-doc="${doc}"]`);
            if (tabBtn) {
                tabBtn.classList.add('read');
                tabBtn.querySelector('.tab-status-icon').innerHTML = '<i class="bi bi-check-circle-fill"></i>';
                tabBtn.querySelector('.tab-status-icon').classList.remove('text-danger');
            }
        });

        agreeBtn.disabled = false;

        if (legalAlreadyAgreedEl) legalAlreadyAgreedEl.classList.remove('d-none');
    }

    const legalModal   = new bootstrap.Modal(document.getElementById('legalModal'), { backdrop: 'static', keyboard: false });
    const agreeBtn     = document.getElementById('legalAgreeBtn');
    const checkbox     = document.getElementById('termsCheckbox');
    const hiddenInput  = document.getElementById('termsAgreedHidden');
    const submitBtn    = document.getElementById('submitBtn');
    const openLegalBtn = document.getElementById('openLegalBtn');
    const legalError   = document.getElementById('legalError');

    if (legalAlreadyAgreed) {
        restoreLegalAgreement();
        checkbox.disabled = false;          // keep it visible and checked
        checkbox.checked  = true;
        hiddenInput.value = '1';
        submitBtn.disabled = false;
    }

    // ── Open modal when link-button clicked ──────────────────────────
    openLegalBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (legalAlreadyAgreed) {
            // Agreement is already locked in from a previous attempt —
            // there is nothing to re-read. Just keep the state intact.
            checkbox.checked       = true;
            hiddenInput.value      = '1';
            submitBtn.disabled     = false;
            return;
        }

        legalModal.show();
        // Set focus to active tab scroll body
        setTimeout(() => {
            const active = document.querySelector('.tab-pane.show.active .legal-scroll-body');
            if (active) active.focus({ preventScroll: true });
        }, 350);
    });

    // ── Scroll detection — unlock tab when scrolled to bottom ────────
    // (The authoritative `window.checkScroll` is defined below, after
    // `markRead`, so its short-doc branch can reference `markRead`. It is
    // referenced here before definition only because JS hoists the variable
    // declaration phase via `window.checkScroll = ...`; the handlers above
    // fire after the full script has run.)
    const scrollers = document.querySelectorAll('.lang-scroller');

    scrollers.forEach(scrollEl => {
        const doc = scrollEl.dataset.parent;
        scrollEl.setAttribute('tabindex', '0');

        const check = () => window.checkScroll(scrollEl, doc);
        scrollEl.addEventListener('scroll', check);
        // Also check immediately in case the content is short enough to not need scrolling
        setTimeout(check, 600);
    });

    function markRead(doc) {
        readState[doc] = true;

        // Tab button
        const tabBtn = document.querySelector(`[data-doc="${doc}"]`);
        if (tabBtn) {
            tabBtn.classList.add('read');
            tabBtn.querySelector('.tab-status-icon').innerHTML = '<i class="bi bi-check-circle-fill"></i>';
            tabBtn.querySelector('.tab-status-icon').classList.remove('text-danger');
        }

        // Form pills
        const pill = document.getElementById('pill-' + doc);
        if (pill) { pill.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + pill.innerText.trim(); pill.classList.add('done'); }

        // Footer pills
        const fpill = document.getElementById('fpill-' + doc);
        if (fpill) { 
            fpill.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>' + fpill.innerText.trim(); 
            fpill.classList.remove('pending');
            fpill.classList.add('done'); 
        }

        // Show read badge, hide scroll notice
        document.getElementById('notice-' + doc).style.display = 'none';
        document.getElementById('read-' + doc).style.display   = 'block';

        // Check if all done
        if (DOCS.every(d => readState[d])) {
            agreeBtn.disabled = false;
        }

        // Auto-advance to next unread tab
        const nextUnread = DOCS.find(d => !readState[d]);
        if (nextUnread) {
            setTimeout(() => {
                const nextBtn = document.querySelector(`[data-doc="${nextUnread}"]`);
                if (nextBtn) {
                    const tab = new bootstrap.Tab(nextBtn);
                    tab.show();
                }
            }, 400);
        }
    }

    // ── Handle Main Tab activation: re-check scroll state ─────────────
    // Languages are now fully dynamic per document (defined via the CMS), so we
    // simply re-check whichever language sub-tab is currently active.
    //
    // FIX: Bootstrap hides inactive tab panes with `display:none`, so a
    // hidden pane's scroller reports clientHeight/scrollHeight of 0 and
    // checkScroll() bails out — meaning scrolling a different tab could never
    // mark a doc read, and short docs that need no scrolling never unlocked
    // unless the lucky 600ms timeout caught them while visible. We now:
    //   1. Auto-mark a doc read the moment its tab (or any of its language
    //      panes) becomes visible AND the content fits without scrolling
    //      (scrollHeight <= clientHeight + tolerance) — short docs need no
    //      scroll gesture to "read" them.
    //   2. Force a re-check on every tab/pane activation, visible or not —
    //      for the edge case where the pane is still painted when the
    //      listener fires, we temporarily measure an off-screen clone.
    function measureScroller(scrollEl) {
        // Normal case: pane is painted.
        if (scrollEl.clientHeight > 0 && scrollEl.scrollHeight > 0) {
            return { clientHeight: scrollEl.clientHeight, scrollHeight: scrollEl.scrollHeight };
        }
        // Pane is hidden (display:none) — render a clone in the DOM with
        // display:block so we get real geometry without affecting layout.
        const clone = scrollEl.cloneNode(true);
        clone.style.cssText = 'display:block;position:absolute;visibility:hidden;overflow:auto;height:0;' +
                              scrollEl.getAttribute('style');
        scrollEl.parentNode.appendChild(clone);
        const result = { clientHeight: clone.clientHeight, scrollHeight: clone.scrollHeight };
        clone.remove();
        return result;
    }

    // Authoritative definition: uses measureScroller so hidden panes
    // (Bootstrap's display:none tab-pane) and short documents are both
    // handled correctly.
    window.checkScroll = (scrollEl, doc) => {
        if (readState[doc]) return; // already read

        const size = measureScroller(scrollEl);
        // Prevent check if we still cannot measure anything real.
        if (size.clientHeight === 0 || size.scrollHeight === 0) return;

        // Short-doc guard: if the whole document fits in the viewport with
        // room to spare, there is no scroll gesture to perform — acknowledge
        // it immediately.
        const shortDoc = size.scrollHeight <= size.clientHeight + 12;
        const atBottom = shortDoc || scrollEl.scrollTop + scrollEl.clientHeight >= scrollEl.scrollHeight - 8;
        if (atBottom) markRead(doc);
    };

    document.querySelectorAll('.legal-tab').forEach(btn => {
        btn.addEventListener('shown.bs.tab', () => {
            const doc = btn.dataset.doc;
            if (!readState[doc]) {
                const activeScroller = document.querySelector(`#tab-${doc} .tab-pane.active .lang-scroller`)
                    || document.querySelector(`#tab-${doc} .lang-scroller`);
                if (activeScroller) window.checkScroll(activeScroller, doc);
            }
        });
    });

    // ── Handle Sub-Tab (Language Toggle) activation: re-check scroll state ──
    document.querySelectorAll('.nav-pills-sm .nav-link').forEach(btn => {
        btn.addEventListener('shown.bs.tab', (e) => {
            const targetId = e.target.getAttribute('data-bs-target');
            const activeScroller = document.querySelector(`${targetId} .lang-scroller`);
            if (activeScroller) {
                const doc = activeScroller.dataset.parent;
                if (!readState[doc]) {
                    window.checkScroll(activeScroller, doc);
                }
            }
        });
    });

    // ── Agree button ──────────────────────────────────────────────────
    agreeBtn.addEventListener('click', () => {
        if (!DOCS.every(d => readState[d])) return;
        legalModal.hide();
        checkbox.disabled  = false;
        checkbox.checked   = true;
        hiddenInput.value  = '1';
        submitBtn.disabled = false;
        legalError.style.display = 'none';
    });

    // ── Checkbox click intercept: if somehow unchecked, re-open modal ─
    checkbox.addEventListener('change', function () {
        if (!this.checked) {
            if (legalAlreadyAgreed) {
                // Restore previous agreement — reopening the modal to re-read
                // legal documents is explicitly not wanted.
                this.checked       = true;
                hiddenInput.value  = '1';
                submitBtn.disabled = false;
                return;
            }

            hiddenInput.value  = '0';
            submitBtn.disabled = true;
            return;
        }

        if (legalAlreadyAgreed) {
            hiddenInput.value  = '1';
            submitBtn.disabled = false;
            return;
        }

        // Ensure they actually read everything if box was somehow re-enabled
        if (!DOCS.every(d => readState[d])) {
            this.checked        = false;
            hiddenInput.value   = '0';
            submitBtn.disabled  = true;
            legalModal.show();
        } else {
            hiddenInput.value  = '1';
            submitBtn.disabled = false;
        }
    });

    // ── Inline field validation ─────────────────────────────────────────
    // Mirrors the server rules so every invalid field lights up red with its
    // exact problem BEFORE the form is allowed to submit. Server validation
    // is still authoritative — inline errors just save the applicant a
    // wasted round trip and point at the precise field.
    const FIELD_RULES = [
        {
            name: 'first_name', required: true,
            test: v => /^[\p{L}\s\-\.']+$/u.test(v),
            msgEmpty: 'First name is required.',
            msgInvalid: 'First name may only contain letters, spaces, hyphens, and apostrophes.'
        },
        {
            name: 'last_name', required: true,
            test: v => /^[\p{L}\s\-\.']+$/u.test(v),
            msgEmpty: 'Last name is required.',
            msgInvalid: 'Last name may only contain letters, spaces, hyphens, and apostrophes.'
        },
        {
            name: 'middle_name', required: false,
            test: v => v === '' || /^[\p{L}\s\-\.']+$/u.test(v),
            msgInvalid: 'Middle name may only contain letters, spaces, hyphens, and apostrophes.'
        },
        {
            name: 'email', required: true,
            test: v => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v),
            msgEmpty: 'Email address is required.',
            msgInvalid: 'Please enter a valid email address (e.g., name@example.com).'
        },
        {
            name: 'password', required: true,
            test: v => v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /\d/.test(v) && /[!@#$%^&*()_+\-=\[\]{};:'"\\|,.<>\/?`~]/.test(v),
            msgEmpty: 'Password is required.',
            msgInvalid: 'Password must be at least 8 characters with an uppercase letter, a lowercase letter, a number, and a special character.'
        },
        {
            name: 'password_confirmation', required: true,
            custom: el => el.value === '' ? 'Please confirm your password.' : (el.value !== form.querySelector('[name="password"]')?.value ? 'Passwords do not match.' : null),
            msgEmpty: 'Please confirm your password.',
            msgInvalid: 'Passwords do not match.'
        },
        {
            name: 'contact_number', required: true,
            test: v => /^(?:\+[1-9]\d{6,14}|(?:\+?63|0)\d{9,10})$/.test(v.replace(/\s+/g, '')),
            msgEmpty: 'Mobile / contact number is required.',
            msgInvalid: 'Please enter a valid contact number with country code (e.g., +639171234567 or 09171234567).'
        },
        {
            name: 'date_of_birth', required: true,
            custom: el => {
                if (!el.value) return 'Please enter your date of birth.';
                const dob = new Date(el.value + 'T00:00:00');
                if (isNaN(dob.getTime())) return 'Please enter a valid date.';
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
                if (age < 18) return 'You must be at least 18 years old to register.';
                return null;
            },
            msgEmpty: 'Date of birth is required.',
            msgInvalid: 'You must be at least 18 years old to register.'
        },
        {
            name: 'sex', required: true,
            test: v => v === 'Male' || v === 'Female',
            msgEmpty: 'Please select your sex.',
            msgInvalid: 'Please select your sex.'
        },
        {
            name: 'civil_status', required: true,
            test: v => ['Single','Married','Widowed','Separated'].includes(v),
            msgEmpty: 'Please select your civil status.',
            msgInvalid: 'Please select your civil status.'
        },
        {
            name: 'nationality', required: true,
            test: v => v.trim().length > 0 && v.trim().length <= 100,
            msgEmpty: 'Nationality is required.',
            msgInvalid: 'Nationality must be 1\u2013100 characters.'
        },
        {
            name: 'personal_address', required: true,
            test: v => v.trim().length >= 5 && v.trim().length <= 1000,
            msgEmpty: 'Personal address is required.',
            msgInvalid: 'Please enter a complete personal address (at least 5 characters).'
        },
        // Internal-only fields
        {
            name: 'university_email', required: true, skipWhen: () => document.querySelector('[name="client_type"]:checked')?.value !== 'internal',
            test: v => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v),
            msgEmpty: 'University email is required.',
            msgInvalid: 'Please enter a valid university email address.'
        },
        {
            name: 'university_id', required: true, skipWhen: () => document.querySelector('[name="client_type"]:checked')?.value !== 'internal',
            test: v => v.trim().length > 0 && v.trim().length <= 50,
            msgEmpty: 'University ID is required.',
            msgInvalid: 'University ID must be 1\u201350 characters.'
        },
        {
            name: 'affiliation_type', required: true, skipWhen: () => document.querySelector('[name="client_type"]:checked')?.value !== 'internal',
            test: v => ['Student','Faculty','Admin/Staff'].includes(v),
            msgEmpty: 'Please select your affiliation type.',
            msgInvalid: 'Please select your affiliation type.'
        },
        {
            name: 'college_department', required: true, skipWhen: () => document.querySelector('[name="client_type"]:checked')?.value !== 'internal',
            test: v => v.trim().length > 0 && v.trim().length <= 255,
            msgEmpty: 'College or department is required.',
            msgInvalid: 'College/department must be 1\u2013255 characters.'
        },
        // External-only fields
        {
            name: 'organization_name', required: true, skipWhen: () => document.querySelector('[name="client_type"]:checked')?.value !== 'external',
            test: v => v.trim().length === 0 || v.trim().length <= 255,
            msgInvalid: 'Organization name must be 255 characters or fewer.'
        },
        {
            name: 'billing_address', required: true, skipWhen: () => document.querySelector('[name="client_type"]:checked')?.value !== 'external',
            test: v => v.trim().length >= 5 && v.trim().length <= 1000,
            msgEmpty: 'Billing address is required.',
            msgInvalid: 'Please enter a complete billing address (at least 5 characters).'
        },
        {
            name: 'government_id_type', required: true, skipWhen: () => document.querySelector('[name="client_type"]:checked')?.value !== 'external',
            test: v => v.trim().length > 0 && v.trim().length <= 100,
            msgEmpty: 'Please select your government ID type.',
            msgInvalid: 'Please select your government ID type.'
        },
        {
            name: 'government_id_number', required: true, skipWhen: () => document.querySelector('[name="client_type"]:checked')?.value !== 'external',
            test: v => v.trim().length > 0 && v.trim().length <= 100,
            msgEmpty: 'Government ID number is required.',
            msgInvalid: 'Government ID number must be 1\u2013100 characters.'
        },
    ];
    // Fix forward reference: `form` is resolved lazily inside custom checks
    // (they run at validation time, after this const is initialized).
    const form = document.getElementById('registerForm');
    // Declared early: client-type toggle (runs at load) clears leftover red
    // highlights, and needs this Set to exist before validation listeners are
    // attached further down.
    const touchedFields = new Set();

    /**
     * Show or clear an inline error on a single field. Returns true if the
     * field is currently valid, false otherwise.
     */
    function setFieldError(el, message) {
        if (!el) return true;
        // Clear any previous inline message on this field
        clearFieldError(el);
        if (!message) {
            el.classList.remove('is-invalid');
            el.classList.add('is-valid');
            return true;
        }
        el.classList.remove('is-valid');
        el.classList.add('is-invalid');
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback small d-block';
        feedback.setAttribute('data-inline-error', el.id || el.name);
        feedback.textContent = message;
        el.after(feedback);
        return false;
    }

    function clearFieldError(el) {
        if (!el) return;
        // Remove any inline feedback that was appended directly after this
        // field, plus any stray duplicates inside its column wrapper.
        if (el.nextElementSibling?.matches?.('div[data-inline-error]')) {
            el.nextElementSibling.remove();
        }
        el.closest('.col-md-6, .col-md-12, .mb-3, .mb-4')
            ?.querySelectorAll('div[data-inline-error]')
            .forEach(n => n.remove());
    }

    function clearAllInlineErrors() {
        document.querySelectorAll('#registerForm div[data-inline-error]').forEach(n => n.remove());
        document.querySelectorAll('#registerForm .is-invalid, #registerForm .is-valid').forEach(el => {
            el.classList.remove('is-invalid', 'is-valid');
        });
    }

    /**
     * Full client-side validation pass. Highlights every invalid field in
     * red with its inline problem message and returns true only when
     * EVERYTHING is clean — the form is only then allowed to submit.
     */
    function validateRegistrationForm(showInline = true) {
        if (showInline) clearAllInlineErrors();
        let firstInvalid = null;

        for (const rule of FIELD_RULES) {
            const el = document.querySelector(`[name="${rule.name}"]`);
            if (!el) continue;
            if (rule.skipWhen && rule.skipWhen()) continue;
            if (el.type === 'radio') {                    // radios handled below the loop
                continue;
            }

            const value = (el.value || '');
            const isPlaceholderOption = el.tagName === 'SELECT' && !value;

            let message = null;
            if (rule.custom) {
                message = rule.custom(el);
            } else if (rule.required && (!value || (isPlaceholderOption && value === ''))) {
                message = rule.msgEmpty;
            } else if (value && rule.test && !rule.test(value)) {
                message = rule.msgInvalid;
            }

            const ok = setFieldError(el, message);
            if (!ok && !firstInvalid) firstInvalid = el;
        }

        // Client-type radio group
        const clientType = document.querySelector('[name="client_type"]:checked');
        let clientMsg = document.getElementById('clientTypeError');
        if (!clientType) {
            if (!clientMsg) {
                clientMsg = document.createElement('div');
                clientMsg.id = 'clientTypeError';
                clientMsg.className = 'text-danger small mt-2';
                clientMsg.setAttribute('data-inline-error', 'client_type');
                clientMsg.textContent = 'Please select a client classification.';
                document.querySelector('[name="client_type"]').closest('.mb-4')?.appendChild(clientMsg);
            }
            firstInvalid = firstInvalid || clientMsg;
        } else if (clientMsg) {
            clientMsg.remove();
        }

        // Terms agreement box (only flagged when the user tries to submit)
        if (showInline && hiddenInput.value !== '1' && !legalAlreadyAgreed) {
            legalError.style.display = 'block';
            legalError.setAttribute('data-inline-error', 'terms');
            if (!firstInvalid) firstInvalid = legalError;
        } else {
            legalError.style.display = 'none';
        }

        if (firstInvalid) {
            // Scroll to the first problem and give it focus when possible.
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (firstInvalid.focus && !firstInvalid.readOnly) firstInvalid.focus({ preventScroll: true });
        }
        return firstInvalid === null;
    }

    // ── Live validation as the user types/changes ───────────────────────
    // Errors NEVER show on page load. A field only turns red AFTER the user
    // has interacted with it (blur/change = "touched"), or when they try to
    // submit. Once touched and invalid, the red highlight clears live the
    // moment they fix it — never before.
    // (`touchedFields` is declared near the top of this block, before the
    // client-type toggle that can clear leftover highlights at load.)

    function recheckTouchedField(el) {
        if (!touchedFields.has(el.name)) return;          // untouched → stay neutral
        const rule = FIELD_RULES.find(r => r.name === el.name);
        if (!rule || (rule.skipWhen && rule.skipWhen())) return;

        const value = (el.value || '');
        const isPlaceholderOption = el.tagName === 'SELECT' && !value;

        let message = null;
        if (rule.custom) {
            message = rule.custom(el);
        } else if (rule.required && (!value || (isPlaceholderOption && value === ''))) {
            message = rule.msgEmpty;
        } else if (value && rule.test && !rule.test(value)) {
            message = rule.msgInvalid;
        }
        setFieldError(el, message);
    }

    document.querySelectorAll('#registerForm input, #registerForm select, #registerForm textarea').forEach(el => {
        el.addEventListener('blur', () => {
            if (el.type === 'radio') return;
            const rule = FIELD_RULES.find(r => r.name === el.name);
            if (!rule || (rule.skipWhen && rule.skipWhen())) return;
            touchedFields.add(el.name);
            recheckTouchedField(el);
        });
        el.addEventListener('input', () => {
            if (!touchedFields.has(el.name)) return;      // untouched fields never go red
            recheckTouchedField(el);
        });
        el.addEventListener('change', () => {
            if (el.type === 'radio') return;
            const rule = FIELD_RULES.find(r => r.name === el.name);
            if (!rule || (rule.skipWhen && rule.skipWhen())) return;
            touchedFields.add(el.name);
            recheckTouchedField(el);
        });
    });

    // ── Form submit guard ───────────────────────────────────────────────
    // The ONLY gate: a full inline validation pass runs before anything is
    // submitted. If anything is red, the submit is blocked entirely.
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        // First submit attempt: mark every field as touched so ALL problems
        // light up at once. On later attempts, already-touched fields keep
        // their live state (cleared errors stay cleared).
        document.querySelectorAll('#registerForm input, #registerForm select, #registerForm textarea').forEach(el => {
            if (el.type === 'radio') return;
            const rule = FIELD_RULES.find(r => r.name === el.name);
            if (rule && !(rule.skipWhen && rule.skipWhen())) touchedFields.add(el.name);
        });
        const clean = validateRegistrationForm(true);
        if (!clean) {
            e.preventDefault();
            return;
        }
        if (hiddenInput.value !== '1') {
            e.preventDefault();
            legalError.style.display = 'block';
            document.getElementById('legalAgreementBox').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endsection
