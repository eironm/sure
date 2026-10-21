@extends('layouts.admin')

@section('title', 'Edit Account')

@section('content')
<style>
    /* ── Account Ledger: flat register language (matches cashier redesign) ── */
    .acct-page { max-width: 860px; margin: 0 auto; padding-bottom: 6rem; }

    /* Ledger header tape */
    .acct-tape {
        background: #0f172a; border-radius: 12px; padding: 1.1rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
        margin-bottom: 1.25rem; color: #fff;
    }
    .acct-tape-brand { line-height: 1.3; min-width: 0; }
    .acct-tape-brand .greet { font-size: 1rem; font-weight: 800; color: #fff; margin: 0; }
    .acct-tape-brand .sub { font-size: 0.78rem; color: #94a3b8; margin: 0.1rem 0 0; font-weight: 500; display: flex; align-items: center; gap: 0.55rem; flex-wrap: wrap; }
    .acct-tape-brand .sub .userref { font-family: 'JetBrains Mono', monospace; color: #cbd5e1; }
    .acct-tape-brand .sub i.dot { color: #10b981; font-size: .6rem; }
    .acct-tape-actions { display: flex; gap: 0.6rem; flex-shrink: 0; }

    /* Ledger card */
    .acct-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem; }
    .acct-card-head {
        padding: 0.9rem 1.4rem; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .acct-card-head h3 { margin: 0; font-size: 0.9rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 0.55rem; }
    .acct-card-head h3 i { color: #0d9488; }
    .acct-card-head .chip { font-size: 0.66rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; }
    .acct-card-body { padding: 1.3rem 1.4rem 1.5rem; }

    /* Fields */
    .acct-field { margin-bottom: 1.15rem; }
    .acct-field:last-child { margin-bottom: 0; }
    .acct-label { display: block; font-size: 0.72rem; font-weight: 700; color: #475569; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.06em; }
    .acct-label .req { color: #dc2626; }
    .acct-label .opt { font-weight: 500; text-transform: none; letter-spacing: 0; color: #94a3b8; }
    .acct-input {
        display: block; width: 100%; padding: 0.62rem 0.85rem; font-size: 0.88rem; font-weight: 500;
        color: #0f172a; background: #fff; border: 1.5px solid #e2e8f0; border-radius: 8px;
        font-family: inherit; line-height: 1.5; appearance: none; transition: border-color .15s, box-shadow .15s;
    }
    .acct-input:focus { outline: none; border-color: #0d9488; box-shadow: 0 0 0 3px rgba(13,148,136,0.10); }
    .acct-input::placeholder { color: #cbd5e1; font-weight: 400; }
    .acct-input.is-invalid { border-color: #fca5a5; }
    .acct-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.10); }
    select.acct-input {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat; background-position: right 0.8rem center; background-size: 13px 11px; padding-right: 2.4rem;
    }
    .acct-error { display: flex; align-items: center; gap: 0.3rem; margin-top: 0.35rem; color: #dc2626; font-size: 0.76rem; font-weight: 600; }
    .acct-helper { margin-top: 0.35rem; font-size: 0.73rem; color: #94a3b8; display: flex; align-items: center; gap: 0.3rem; }
    .acct-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .acct-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .acct-row-2, .acct-row-3 { grid-template-columns: 1fr; } }

    /* Role chips — flat ledger style */
    .acct-roles { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.65rem; }
    @media (max-width: 640px) { .acct-roles { grid-template-columns: 1fr; } }
    .acct-role-option { position: relative; }
    .acct-role-option input { position: absolute; opacity: 0; width: 0; height: 0; }
    .acct-role-chip {
        display: flex; align-items: center; gap: 0.5rem; padding: 0.65rem 0.75rem;
        border: 1.5px solid #e2e8f0; border-radius: 8px; background: #fff;
        font-size: 0.8rem; font-weight: 700; color: #475569; cursor: pointer; transition: all .15s;
    }
    .acct-role-chip i { font-size: 0.95rem; color: #94a3b8; }
    .acct-role-option input:checked + .acct-role-chip { border-color: #0d9488; background: #f0fdf9; color: #0f766e; }
    .acct-role-option input:checked + .acct-role-chip i { color: #0d9488; }

    /* Switch toggle */
    .acct-switch { display: inline-flex; align-items: center; gap: 0.65rem; cursor: pointer; }
    .acct-switch input { display: none; }
    .acct-switch .track { width: 38px; height: 22px; border-radius: 99px; background: #cbd5e1; position: relative; transition: background .2s; flex-shrink: 0; }
    .acct-switch .track::after { content: ''; position: absolute; top: 3px; left: 3px; width: 16px; height: 16px; border-radius: 50%; background: #fff; transition: transform .2s; }
    .acct-switch input:checked + .track { background: #0d9488; }
    .acct-switch input:checked + .track::after { transform: translateX(16px); }
    .acct-switch .lbl { font-size: 0.82rem; font-weight: 700; color: #0f172a; }
    .acct-switch .lbl small { display: block; font-weight: 500; font-size: 0.72rem; color: #94a3b8; }

    /* Avatar drop zone */
    .acct-avatar-zone { display: flex; align-items: center; gap: 1.1rem; }
    .acct-avatar-preview { width: 72px; height: 72px; border-radius: 50%; flex-shrink: 0; overflow: hidden; background: #f1f5f9; }
    .acct-avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .acct-avatar-actions { display: flex; flex-direction: column; gap: 0.4rem; }
    .acct-avatar-note { font-size: 0.73rem; color: #94a3b8; margin-top: 0.15rem; }

    /* Danger zone */
    .acct-danger { border: 1.5px dashed #f8b4b4; }
    .acct-danger .acct-card-head h3 i { color: #dc2626; }
    .acct-danger-note { font-size: 0.8rem; color: #475569; margin-bottom: 1rem; line-height: 1.55; }

    /* Buttons */
    .csh-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1.1rem; border-radius: 8px; font-size: 0.82rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: opacity .15s, background .15s; height: 36px; }
    .csh-btn:hover { opacity: 0.88; color: #fff; }
    .csh-btn.emerald { background: #059669; color: #fff; }
    .csh-btn.red-outline { background: transparent; color: #b91c1c; border: 1.5px solid #f8b4b4; }
    .csh-btn.red-outline:hover { background: #fef2f2; color: #991b1b; }
    .csh-btn.sm { padding: 0.32rem 0.8rem; font-size: 0.76rem; height: 30px; }
    .csh-btn.ghost { background: transparent; color: #475569; border: 1.5px solid #cbd5e1; }
    .csh-btn.ghost:hover { background: #f8fafc; color: #0f172a; }

    /* Sticky bottom bar */
    .acct-sticky {
        position: fixed; bottom: 0; left: 0; right: 0;
        background: #fff; border-top: 1px solid #e2e8f0;
        padding: 0.75rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 0.65rem; flex-wrap: wrap;
        z-index: 900; box-shadow: 0 -4px 14px rgba(15,23,42,0.05);
    }
    .acct-sticky .left { display: flex; gap: 0.6rem; }
    .acct-sticky .right { display: flex; gap: 0.6rem; }
</style>

<div class="acct-page">

    {{-- Ledger header tape --}}
    <div class="acct-tape">
        <div class="acct-tape-brand">
            <p class="greet"><i class="bi bi-pencil-square me-1" style="color:#34d399;"></i>Edit Account</p>
            <p class="sub"><span class="userref">#{{ $user->id }}</span> &middot; {{ $user->first_name ?? $user->name }} {{ $user->last_name ?? '' }} <i class="bi bi-circle-fill dot"></i> {{ ucfirst($user->role) }} <i class="bi bi-circle-fill dot"></i> {{ $user->is_active ? 'Active' : 'Inactive' }}</p>
        </div>
        <div class="acct-tape-actions">
            <a href="{{ route('admin.users.index') }}" class="csh-btn ghost"><i class="bi bi-arrow-left"></i>Back</a>
            <button type="submit" form="acctForm" class="csh-btn emerald"><i class="bi bi-check2"></i>Save Changes</button>
        </div>
    </div>

    <form id="acctForm" method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        {{-- WHO THEY ARE --}}
        <div class="acct-card">
            <div class="acct-card-head">
                <h3><i class="bi bi-person-lines-fill"></i>Who They Are</h3>
                <span class="chip">Identity &amp; login</span>
            </div>
            <div class="acct-card-body">

                <div class="acct-avatar-zone" style="margin-bottom:1.35rem;">
                    <div class="acct-avatar-preview" id="acctAvatarPreview" style="{{ $user->avatar && $user->avatarUrl() ? '' : 'display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0f172a,#334155);color:rgba(255,255,255,.55);' }}">
                        @if ($user->avatar && $user->avatarUrl())
                            <img src="{{ $user->avatarUrl() }}" alt="Current profile photo">
                        @else
                            <i class="bi bi-person-fill" style="font-size:2.6rem;"></i>
                        @endif
                    </div>
                    <div class="acct-avatar-actions">
                        <label for="acctAvatarInput" class="csh-btn emerald sm" style="cursor:pointer;">{{ $user->avatar ? 'Replace Photo' : 'Upload Photo' }}</label>
                        <button type="button" class="csh-btn ghost sm" id="acctAvatarRemove"><i class="bi bi-x-lg"></i>Remove</button>
                        <div class="acct-avatar-note">JPEG / PNG / WebP, up to 2 MB. Optional.</div>
                        <input type="file" id="acctAvatarInput" name="avatar" accept="image/jpeg,image/png,image/webp" style="display:none;">
                        <input type="hidden" id="acctAvatarRemoved" name="avatar_removed" value="">
                        @error('avatar')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="acct-row-3">
                    <div class="acct-field">
                        <label for="first_name" class="acct-label">First Name <span class="req">*</span></label>
                        <input type="text" class="acct-input @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="e.g. Juan" required>
                        @error('first_name')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="acct-field">
                        <label for="middle_name" class="acct-label">Middle Name <span class="opt">(optional)</span></label>
                        <input type="text" class="acct-input @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" placeholder="e.g. Santos">
                        @error('middle_name')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="acct-field">
                        <label for="last_name" class="acct-label">Last Name <span class="req">*</span></label>
                        <input type="text" class="acct-input @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="e.g. Dela Cruz" required>
                        @error('last_name')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="acct-field">
                    <label for="email" class="acct-label">Email Address <span class="req">*</span></label>
                    <input type="email" class="acct-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="e.g. jdelacruz@university.edu.ph" required>
                    @error('email')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    <div class="acct-helper"><i class="bi bi-info-circle"></i>Used as the login credential. Changing it affects their next login.</div>
                </div>

                <div class="acct-row-2">
                    <div class="acct-field">
                        <label class="acct-label">Contact Number <span class="opt">(optional)</span></label>
                        <x-international-phone-input
                            name="contact_number"
                            id="contact_number"
                            :value="old('contact_number', $user->contact_number)"
                            theme="admin"
                        />
                    </div>
                    <div class="acct-field">
                        <label for="date_of_birth" class="acct-label">Date of Birth <span class="opt">(optional)</span></label>
                        <input type="date" class="acct-input @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                        @error('date_of_birth')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ACCESS LEVEL --}}
        <div class="acct-card">
            <div class="acct-card-head">
                <h3><i class="bi bi-key-fill"></i>Access Level</h3>
                <span class="chip">Role, status &amp; password</span>
            </div>
            <div class="acct-card-body">
                <div class="acct-field">
                    <div class="acct-label">Role <span class="req">*</span></div>
                    <div class="acct-roles">
                        @foreach (['guest' => ['bi bi-person', 'Guest'], 'cashier' => ['bi bi-cash-coin', 'Cashier'], 'staff' => ['bi bi-briefcase', 'Staff']] as $slug => [$icon, $label])
                            <label class="acct-role-option">
                                <input type="radio" name="role" value="{{ $slug }}" {{ old('role', $user->role) === $slug ? 'checked' : '' }}>
                                <span class="acct-role-chip"><i class="{{ $icon }}" aria-hidden="true"></i>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('role')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>

                <div class="acct-field">
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

                <div class="acct-row-2">
                    <div class="acct-field">
                        <label for="password" class="acct-label">New Password <span class="opt">(leave blank to keep)</span></label>
                        <input type="password" class="acct-input @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter only to reset">
                        @error('password')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    @include('components.password-strength-meter', [
                        'passwordId' => 'password',
                        'confirmId'  => 'password_confirmation',
                    ])
                    </div>
                    <div class="acct-field">
                        <label for="password_confirmation" class="acct-label">Confirm New Password</label>
                        <input type="password" class="acct-input @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Re-type the new password">
                        @error('password_confirmation')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="acct-helper"><i class="bi bi-info-circle"></i>Leaving both fields empty keeps the current password.</div>
            </div>
        </div>

        {{-- CLIENT PROFILE --}}
        <div class="acct-card">
            <div class="acct-card-head">
                <h3><i class="bi bi-tag-fill"></i>Client Profile</h3>
                <span class="chip">Optional classification</span>
            </div>
            <div class="acct-card-body" x-data="{ clientType: '{{ old('client_type', $user->client_type) }}' }">
                <div class="acct-field">
                    <label for="client_type" class="acct-label">Client Type <span class="opt">(optional)</span></label>
                    <select id="client_type" name="client_type" class="acct-input @error('client_type') is-invalid @enderror" x-model="clientType">
                        <option value="" :selected="clientType === ''">Not Specified</option>
                        <option value="internal" :selected="clientType === 'internal'">Internal (University)</option>
                        <option value="external" :selected="clientType === 'external'">External (General Public)</option>
                    </select>
                    @error('client_type')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>

                <div x-show="clientType === 'internal'" x-cloak>
                    <div style="margin-top:1rem;padding:1.15rem;border-radius:10px;border:1.5px dashed #a7f3d0;background:#f0fdf9;">
                        <div style="display:flex;align-items:center;gap:.5rem;font-size:.78rem;font-weight:800;color:#047857;margin-bottom:1rem;padding-bottom:.65rem;border-bottom:1px solid #d1fae5;"><i class="bi bi-building"></i>Internal Client Details</div>
                        <div class="acct-row-2" style="margin-bottom:1rem;">
                            <div class="acct-field">
                                <label for="university_email" class="acct-label">University Email</label>
                                <input type="email" class="acct-input @error('university_email') is-invalid @enderror" id="university_email" name="university_email" value="{{ old('university_email', $user->university_email) }}">
                                @error('university_email')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                            <div class="acct-field">
                                <label for="university_id" class="acct-label">University ID</label>
                                <input type="text" class="acct-input @error('university_id') is-invalid @enderror" id="university_id" name="university_id" value="{{ old('university_id', $user->university_id) }}">
                                @error('university_id')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="acct-row-2">
                            <div class="acct-field">
                                <label for="affiliation_type" class="acct-label">Affiliation</label>
                                <select id="affiliation_type" name="affiliation_type" class="acct-input @error('affiliation_type') is-invalid @enderror">
                                    <option value="" {{ old('affiliation_type', $user->affiliation_type) == '' ? 'selected' : '' }}>Select Affiliation</option>
                                    <option value="Student" {{ old('affiliation_type', $user->affiliation_type) == 'Student' ? 'selected' : '' }}>Student</option>
                                    <option value="Faculty" {{ old('affiliation_type', $user->affiliation_type) == 'Faculty' ? 'selected' : '' }}>Faculty</option>
                                    <option value="Admin/Staff" {{ old('affiliation_type', $user->affiliation_type) == 'Admin/Staff' ? 'selected' : '' }}>Admin/Staff</option>
                                </select>
                                @error('affiliation_type')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                            <div class="acct-field">
                                <label for="college_department" class="acct-label">College / Department</label>
                                <select id="college_department" name="college_department" class="acct-input @error('college_department') is-invalid @enderror">
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
                                @error('college_department')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="clientType === 'external'" x-cloak>
                    <div style="margin-top:1rem;padding:1.15rem;border-radius:10px;border:1.5px dashed #93c5fd;background:#eff6ff;">
                        <div style="display:flex;align-items:center;gap:.5rem;font-size:.78rem;font-weight:800;color:#1d4ed8;margin-bottom:1rem;padding-bottom:.65rem;border-bottom:1px solid #dbeafe;"><i class="bi bi-globe2"></i>External Client Details</div>
                        <div class="acct-row-2" style="margin-bottom:1rem;">
                            <div class="acct-field">
                                <label for="organization_name" class="acct-label">Organization / Company</label>
                                <input type="text" class="acct-input @error('organization_name') is-invalid @enderror" id="organization_name" name="organization_name" value="{{ old('organization_name', $user->organization_name) }}">
                                @error('organization_name')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                            <div class="acct-field">
                                <label for="billing_address" class="acct-label">Billing Address</label>
                                <input type="text" class="acct-input @error('billing_address') is-invalid @enderror" id="billing_address" name="billing_address" value="{{ old('billing_address', $user->billing_address) }}">
                                @error('billing_address')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="acct-row-2">
                            <div class="acct-field">
                                <label for="government_id_type" class="acct-label">Government ID Type</label>
                                <input type="text" class="acct-input @error('government_id_type') is-invalid @enderror" id="government_id_type" name="government_id_type" value="{{ old('government_id_type', $user->government_id_type) }}" placeholder="e.g. Passport, UMID">
                                @error('government_id_type')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                            <div class="acct-field">
                                <label for="government_id_number" class="acct-label">Government ID Number</label>
                                <input type="text" class="acct-input @error('government_id_number') is-invalid @enderror" id="government_id_number" name="government_id_number" value="{{ old('government_id_number', $user->government_id_number) }}">
                                @error('government_id_number')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- EXTRA DETAILS (collapsed by default) --}}
        <div class="acct-card">
            <div class="acct-card-head">
                <h3><i class="bi bi-person-badge-fill"></i>Extra Details</h3>
                <label class="acct-switch" for="extraToggle">
                    <input type="checkbox" id="extraToggle">
                    <span class="track"></span>
                    <span class="lbl">Expand<small>sex, civil status, nationality, address</small></span>
                </label>
            </div>
            <div class="acct-card-body" id="extraBody" style="display:none;">
                <div class="acct-row-2" style="margin-bottom:1rem;">
                    <div class="acct-field">
                        <label for="sex" class="acct-label">Sex <span class="opt">(optional)</span></label>
                        <select id="sex" name="sex" class="acct-input @error('sex') is-invalid @enderror">
                            <option value="" {{ old('sex', $user->sex) == '' ? 'selected' : '' }}>Not Specified</option>
                            <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('sex')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="acct-field">
                        <label for="civil_status" class="acct-label">Civil Status <span class="opt">(optional)</span></label>
                        <select id="civil_status" name="civil_status" class="acct-input @error('civil_status') is-invalid @enderror">
                            <option value="" {{ old('civil_status', $user->civil_status) == '' ? 'selected' : '' }}>Not Specified</option>
                            <option value="Single" {{ old('civil_status', $user->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('civil_status', $user->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ old('civil_status', $user->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ old('civil_status', $user->civil_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
                        </select>
                        @error('civil_status')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="acct-row-2">
                    <div class="acct-field">
                        <label for="nationality" class="acct-label">Nationality <span class="opt">(optional)</span></label>
                        <input type="text" class="acct-input @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', $user->nationality) }}" placeholder="e.g. Filipino">
                        @error('nationality')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="acct-field">
                        <label for="personal_address" class="acct-label">Personal Address <span class="opt">(optional)</span></label>
                        <input type="text" class="acct-input @error('personal_address') is-invalid @enderror" id="personal_address" name="personal_address" value="{{ old('personal_address', $user->personal_address) }}">
                        @error('personal_address')<div class="acct-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- DANGER ZONE --}}
    <div class="acct-card acct-danger">
        <div class="acct-card-head">
            <h3><i class="bi bi-exclamation-octagon-fill"></i>Danger Zone</h3>
            <span class="chip">Destructive actions</span>
        </div>
        <div class="acct-card-body">
            <div class="acct-danger-note">Permanently delete this account and all of their data. This action cannot be undone. Use the account switch above to deactivate instead when a temporary block is enough.</div>
            <form id="deleteUserForm" method="POST" action="{{ route('admin.users.destroy', $user) }}" style="margin:0;" onsubmit="return confirm('Delete this account permanently? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="csh-btn red-outline"><i class="bi bi-trash3-fill"></i>Delete Account</button>
            </form>
        </div>
    </div>

    <div class="acct-sticky">
        <div class="left">
            <a href="{{ route('admin.users.index') }}" class="csh-btn ghost"><i class="bi bi-x-lg"></i>Cancel</a>
        </div>
        <div class="right">
            <button type="submit" form="acctForm" class="csh-btn emerald"><i class="bi bi-check2"></i>Save Changes</button>
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
