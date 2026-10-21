@extends('layouts.superadmin')
@section('title', 'Edit Account — ' . ($user->first_name ?? $user->name) . ' ' . ($user->last_name ?? ''))

@section('content')
<style>
    /* Compact profile avatar and accessible account switch for the edit form. */
    .acct-avatar-preview { width: 72px; height: 72px; border-radius: 50%; flex: 0 0 72px; overflow: hidden; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
    .acct-avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .acct-switch { display: inline-flex; align-items: center; gap: .65rem; cursor: pointer; position: relative; }
    .acct-switch input[type="checkbox"] { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
    .acct-switch .track { width: 38px; height: 22px; border-radius: 99px; background: #cbd5e1; position: relative; transition: background .2s; flex: 0 0 auto; }
    .acct-switch .track::after { content: ''; position: absolute; top: 3px; left: 3px; width: 16px; height: 16px; border-radius: 50%; background: #fff; transition: transform .2s; box-shadow: 0 1px 2px rgba(15,23,42,.18); }
    .acct-switch input[type="checkbox"]:checked + .track { background: var(--brand-primary, #f97316); }
    .acct-switch input[type="checkbox"]:checked + .track::after { transform: translateX(16px); }
    .acct-switch .lbl { font-size: .82rem; font-weight: 700; color: var(--ink-1, #0f172a); }
    .acct-switch .lbl small { display: block; font-weight: 500; font-size: .72rem; color: var(--ink-3, #64748b); }
</style>
<div class="sa-page-center">

    <div class="page-header" style="margin-bottom: 1.25rem;">
        <div>
            <h1 class="ph-title">Edit Account</h1>
            <p class="sa-page-subnote"><span style="font-family:'JetBrains Mono',monospace;color:var(--ink-2);">#{{ $user->id }}</span> &middot; {{ $user->first_name ?? $user->name }} {{ $user->last_name ?? '' }} <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--good);"></i> {{ ucfirst($user->role) }} <i class="bi bi-circle-fill" style="font-size:.4rem;color:{{ $user->is_active ? 'var(--good)' : 'var(--bad)' }};"></i> {{ $user->is_active ? 'Active' : 'Inactive' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Back</a>
        </div>
    </div>

    <form id="acctForm" method="POST" action="{{ route('superadmin.users.update', $user) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        {{-- WHO THEY ARE --}}
        <div class="fieldset">
            <div class="fieldset-title">
                <i class="bi bi-person-lines-fill"></i> Who They Are
                <small>Identity &amp; login</small>
            </div>

            <div class="field">
                <label class="form-label">Profile Photo <span class="opt">(optional)</span></label>
                <div style="display: flex; align-items: center; gap: 1rem; margin-top: .35rem;">
                    <div class="acct-avatar-preview" id="acctAvatarPreview" style="{{ $user->avatar && $user->avatarUrl() ? '' : 'display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0f172a,#334155);color:rgba(255,255,255,.55);' }}">
                        @if ($user->avatar && $user->avatarUrl())
                            <img src="{{ $user->avatarUrl() }}" alt="Current profile photo">
                        @else
                            <i class="bi bi-person-fill" style="font-size:2.2rem;"></i>
                        @endif
                    </div>
                    <div>
                        <label for="acctAvatarInput" class="file-choose" style="cursor:pointer;"><i class="bi bi-camera-fill"></i>{{ $user->avatar ? 'Replace Photo' : 'Upload Photo' }}</label>
                        <button type="button" class="file-choose ms-1" id="acctAvatarRemove"><i class="bi bi-x-lg"></i>Remove</button>
                        <div class="form-hint">JPEG / PNG / WebP, up to 2 MB. Optional.</div>
                        <input type="file" id="acctAvatarInput" name="avatar" accept="image/jpeg,image/png,image/webp" style="display:none;">
                        <input type="hidden" id="acctAvatarRemoved" name="avatar_removed" value="">
                        @error('avatar')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="field-row cols-3">
                <div class="field">
                    <label for="first_name" class="form-label">First Name <span class="req">*</span></label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="e.g. Juan" required>
                    @error('first_name')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="middle_name" class="form-label">Middle Name <span class="opt">(optional)</span></label>
                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" placeholder="e.g. Santos">
                    @error('middle_name')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="last_name" class="form-label">Last Name <span class="req">*</span></label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="e.g. Dela Cruz" required>
                    @error('last_name')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="email" class="form-label">Email Address <span class="req">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="e.g. jdelacruz@university.edu.ph" required>
                @error('email')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="form-hint"><i class="bi bi-info-circle"></i> Used as the login credential.</div>
            </div>

            <div class="field-row cols-2">
                <div class="field">
                    <label class="form-label">Contact Number <span class="opt">(optional)</span></label>
                    <x-international-phone-input
                        name="contact_number"
                        id="contact_number"
                        :value="old('contact_number', $user->contact_number)"
                        theme="bootstrap"
                    />
                </div>
                <div class="field">
                    <label for="date_of_birth" class="form-label">Date of Birth <span class="opt">(optional)</span></label>
                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                    @error('date_of_birth')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- ACCESS LEVEL --}}
        <div class="fieldset">
            <div class="fieldset-title">
                <i class="bi bi-key-fill"></i> Access Level
                <small>Role, status &amp; password</small>
            </div>

            <div class="field">
                <label class="form-label">Role <span class="req">*</span></label>
                <div class="role-grid">
                    @foreach (['guest' => ['bi bi-person', 'Guest'], 'cashier' => ['bi bi-cash-coin', 'Cashier'], 'staff' => ['bi bi-briefcase', 'Staff'], 'admin' => ['bi bi-shield-lock', 'Admin']] as $slug => [$icon, $label])
                        <label class="role-option">
                            <input type="radio" name="role" value="{{ $slug }}" {{ old('role', $user->role) === $slug ? 'checked' : '' }}>
                            <span class="role-option-chip"><i class="{{ $icon }}" aria-hidden="true"></i>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('role')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>

            <div class="field">
                @php
                    $acctActive = old('is_active') !== null ? (int) old('is_active') === 1 : (bool) $user->is_active;
                @endphp
                <label class="acct-switch" id="activeSwitch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="activeToggle" name="is_active" value="1" {{ $acctActive ? 'checked' : '' }}>
                    <span class="track"></span>
                    <span class="lbl">Account Active<small id="activeSwitchNote">{{ $acctActive ? 'On — user can log in' : 'Off — login blocked' }}</small></span>
                </label>
            </div>
            <script>document.addEventListener('DOMContentLoaded', function () { var t = document.getElementById('activeToggle'); var n = document.getElementById('activeSwitchNote'); if (t && n) { t.addEventListener('change', function () { n.textContent = t.checked ? 'On — user can log in' : 'Off — login blocked'; }); } });</script>

            <div class="field-row cols-2">
                <div class="field">
                    <label for="password" class="form-label">New Password <span class="opt">(leave blank to keep)</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter only to reset">
                    @error('password')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    @include('components.password-strength-meter', [
                        'passwordId' => 'password',
                        'confirmId'  => 'password_confirmation',
                    ])
                </div>
                <div class="field">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Re-type the new password">
                    @error('password_confirmation')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-hint" style="margin-top:.45rem;"><i class="bi bi-info-circle"></i> Leaving both fields empty keeps the current password.</div>
        </div>

        {{-- CLIENT PROFILE --}}
        <div class="fieldset">
            <div class="fieldset-title">
                <i class="bi bi-tag-fill"></i> Client Profile
                <small>Optional classification</small>
            </div>

            <div class="field-row" x-data="{ clientType: '{{ old('client_type', $user->client_type ?? '') }}' }">
                <div class="field">
                    <label for="client_type" class="form-label">Client Type <span class="opt">(optional)</span></label>
                    <select id="client_type" name="client_type" class="form-control @error('client_type') is-invalid @enderror" x-model="clientType">
                        <option value="" :selected="clientType === ''">Not Specified</option>
                        <option value="internal" :selected="clientType === 'internal'">Internal (University)</option>
                        <option value="external" :selected="clientType === 'external'">External (General Public)</option>
                    </select>
                    @error('client_type')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>

                <div x-show="clientType === 'internal'" x-cloak>
                    <div class="callout ok">
                        <div class="callout-call"><i class="bi bi-building"></i>Internal Client Details</div>
                        <div class="field-row cols-2" style="margin-bottom: 1rem;">
                            <div class="field">
                                <label for="university_email" class="form-label">University Email</label>
                                <input type="email" class="form-control @error('university_email') is-invalid @enderror" id="university_email" name="university_email" value="{{ old('university_email', $user->university_email) }}">
                                @error('university_email')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="affiliation_type" class="form-label">Affiliation Type</label>
                                <select id="affiliation_type" name="affiliation_type" class="form-control @error('affiliation_type') is-invalid @enderror">
                                    <option value="" {{ old('affiliation_type', $user->affiliation_type) == '' ? 'selected' : '' }}>Select Type</option>
                                    <option value="Student" {{ old('affiliation_type', $user->affiliation_type) == 'Student' ? 'selected' : '' }}>Student</option>
                                    <option value="Faculty" {{ old('affiliation_type', $user->affiliation_type) == 'Faculty' ? 'selected' : '' }}>Faculty</option>
                                    <option value="Staff" {{ old('affiliation_type', $user->affiliation_type) == 'Staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                                @error('affiliation_type')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="field">
                            <label for="college_department" class="form-label">College / Department</label>
                            <select id="college_department" name="college_department" class="form-control @error('college_department') is-invalid @enderror">
                                <option value="" {{ old('college_department', $user->college_department) == '' ? 'selected' : '' }}>Select Department</option>
                                <option value="College of Engineering" {{ old('college_department', $user->college_department) == 'College of Engineering' ? 'selected' : '' }}>College of Engineering</option>
                                <option value="College of Education" {{ old('college_department', $user->college_department) == 'College of Education' ? 'selected' : '' }}>College of Education</option>
                                <option value="College of Arts and Sciences" {{ old('college_department', $user->college_department) == 'College of Arts and Sciences' ? 'selected' : '' }}>College of Arts and Sciences</option>
                                <option value="College of Business and Accountancy" {{ old('college_department', $user->college_department) == 'College of Business and Accountancy' ? 'selected' : '' }}>College of Business and Accountancy</option>
                                <option value="College of Information and Communications Technology" {{ old('college_department', $user->college_department) == 'College of Information and Communications Technology' ? 'selected' : '' }}>College of ICT</option>
                                <option value="College of Architecture and Fine Arts" {{ old('college_department', $user->college_department) == 'College of Architecture and Fine Arts' ? 'selected' : '' }}>College of Architecture &amp; Fine Arts</option>
                                <option value="College of Nursing" {{ old('college_department', $user->college_department) == 'College of Nursing' ? 'selected' : '' }}>College of Nursing</option>
                                <option value="School of Graduate Studies" {{ old('college_department', $user->college_department) == 'School of Graduate Studies' ? 'selected' : '' }}>School of Graduate Studies</option>
                                <option value="Administration / General Staff" {{ old('college_department', $user->college_department) == 'Administration / General Staff' ? 'selected' : '' }}>Administration / General Staff</option>
                            </select>
                            @error('college_department')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div x-show="clientType === 'external'" x-cloak>
                    <div class="callout info">
                        <div class="callout-call"><i class="bi bi-globe2"></i>External Client Details</div>
                        <div class="field-row cols-2" style="margin-bottom: 1rem;">
                            <div class="field">
                                <label for="organization_name" class="form-label">Organization / Company</label>
                                <input type="text" class="form-control @error('organization_name') is-invalid @enderror" id="organization_name" name="organization_name" value="{{ old('organization_name', $user->organization_name) }}">
                                @error('organization_name')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="billing_address" class="form-label">Billing Address</label>
                                <input type="text" class="form-control @error('billing_address') is-invalid @enderror" id="billing_address" name="billing_address" value="{{ old('billing_address', $user->billing_address) }}">
                                @error('billing_address')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="field-row cols-2">
                            <div class="field">
                                <label for="government_id_type" class="form-label">Government ID Type</label>
                                <input type="text" class="form-control @error('government_id_type') is-invalid @enderror" id="government_id_type" name="government_id_type" value="{{ old('government_id_type', $user->government_id_type) }}" placeholder="e.g. Passport, UMID">
                                @error('government_id_type')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label for="government_id_number" class="form-label">Government ID Number</label>
                                <input type="text" class="form-control @error('government_id_number') is-invalid @enderror" id="government_id_number" name="government_id_number" value="{{ old('government_id_number', $user->government_id_number) }}">
                                @error('government_id_number')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- EXTRA DETAILS (collapsed by default) --}}
        <div class="fieldset">
            <div class="fieldset-title">
                <i class="bi bi-person-badge-fill"></i> Extra Details
                <label class="toggle-row" style="margin: 0; padding: .5rem .75rem;">
                    <input type="checkbox" id="extraToggle" style="margin-right:.65rem;">
                    <span class="tl">Expand <small>sex, civil status, nationality, address</small></span>
                </label>
            </div>
            <div id="extraBody" style="display:none; padding: 1rem 0 0;">
                <div class="field-row cols-2" style="margin-bottom: 1rem;">
                    <div class="field">
                        <label for="sex" class="form-label">Sex <span class="opt">(optional)</span></label>
                        <select id="sex" name="sex" class="form-control @error('sex') is-invalid @enderror">
                            <option value="" {{ old('sex', $user->sex) == '' ? 'selected' : '' }}>Not Specified</option>
                            <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('sex')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="civil_status" class="form-label">Civil Status <span class="opt">(optional)</span></label>
                        <select id="civil_status" name="civil_status" class="form-control @error('civil_status') is-invalid @enderror">
                            <option value="" {{ old('civil_status', $user->civil_status) == '' ? 'selected' : '' }}>Not Specified</option>
                            <option value="Single" {{ old('civil_status', $user->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('civil_status', $user->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ old('civil_status', $user->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ old('civil_status', $user->civil_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
                        </select>
                        @error('civil_status')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="field-row cols-2">
                    <div class="field">
                        <label for="nationality" class="form-label">Nationality <span class="opt">(optional)</span></label>
                        <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', $user->nationality) }}" placeholder="e.g. Filipino">
                        @error('nationality')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="personal_address" class="form-label">Personal Address <span class="opt">(optional)</span></label>
                        <input type="text" class="form-control @error('personal_address') is-invalid @enderror" id="personal_address" name="personal_address" value="{{ old('personal_address', $user->personal_address) }}">
                        @error('personal_address')<div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

    </form>

    <div class="save-bar">
        <div class="sb-note"><i class="bi bi-info-circle-fill"></i> Changes take effect immediately.</div>
        <div class="sb-actions">
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-ghost"><i class="bi bi-x-lg"></i>Cancel</a>
            <button type="submit" form="acctForm" class="btn btn-primary"><i class="bi bi-check2"></i>Save Changes</button>
        </div>
    </div>
</div>

<script>
(function acctAvatar() {
    var input = document.getElementById('acctAvatarInput');
    var preview = document.getElementById('acctAvatarPreview');
    var remove = document.getElementById('acctAvatarRemove');
    if (!input || !preview || !remove) return;
    var fallback = preview.innerHTML;
    function showFile(file) {
        if (!file) { preview.innerHTML = fallback; return; }
        var reader = new FileReader();
        reader.onload = function () {
            preview.innerHTML = '<img src="' + reader.result + '" alt="Profile photo preview">';
        };
        reader.readAsDataURL(file);
    }
    input.addEventListener('change', function () {
        showFile(this.files && this.files[0] ? this.files[0] : null);
    });
    remove.addEventListener('click', function () {
        input.value = '';
        showFile(null);
        var hidden = document.getElementById('acctAvatarRemoved');
        if (hidden) hidden.value = '1';
    });
})();
(function acctExtra() {
    var toggle = document.getElementById('extraToggle');
    var body = document.getElementById('extraBody');
    if (!toggle || !body) return;
    toggle.addEventListener('change', function () {
        body.style.display = toggle.checked ? '' : 'none';
    });
})();
</script>
@endsection
