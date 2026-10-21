<section>
    <style>
        /* ═══════════════════════════════════════════════════════════
           Profile Information Form — Redesigned
           ═══════════════════════════════════════════════════════════ */

        /* Section Dividers */
        .pf-section-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2.5rem 0 1.75rem;
            position: relative;
        }

        .pf-section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0, transparent);
        }

        .pf-section-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(var(--brand-primary-rgb, 37,99,235), 0.1), rgba(var(--brand-primary-rgb, 37,99,235), 0.05));
            color: var(--brand-primary, #2563eb);
            border: 1px solid rgba(var(--brand-primary-rgb, 37,99,235), 0.12);
        }

        .pf-section-text {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .pf-section-label {
            font-weight: 800;
            font-size: 0.95rem;
            color: #0f172a;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .pf-section-desc {
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 500;
            line-height: 1.3;
        }

        /* Security Notice */
        .pf-security-banner {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.15rem 1.5rem;
            margin-bottom: 2.25rem;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(var(--brand-primary-rgb, 37,99,235), 0.06) 0%, rgba(var(--brand-primary-rgb, 37,99,235), 0.02) 100%);
            border: 1px solid rgba(var(--brand-primary-rgb, 37,99,235), 0.12);
            position: relative;
            overflow: hidden;
        }

        .pf-security-banner::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            border-radius: 4px 0 0 4px;
        }

        .pf-security-banner-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            font-size: 1rem;
        }

        .pf-security-banner-title {
            font-weight: 700;
            font-size: 0.88rem;
            color: #1e293b;
            margin-bottom: 0.2rem;
        }

        .pf-security-banner-text {
            font-size: 0.82rem;
            color: #64748b;
            line-height: 1.5;
        }

        /* Form Groups */
        .pf-field {
            margin-bottom: 1.35rem;
        }

        .pf-label {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 700;
            color: #334155;
            font-size: 0.78rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .pf-label .optional-tag {
            font-weight: 500;
            text-transform: none;
            letter-spacing: 0;
            color: #94a3b8;
            font-size: 0.76rem;
        }

        .pf-input {
            width: 100%;
            padding: 0.75rem 1.1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #0f172a;
            background: #fff;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            line-height: 1.5;
        }

        .pf-input:focus {
            outline: none;
            border-color: var(--brand-primary, #2563eb);
            box-shadow: 0 0 0 4px rgba(var(--brand-primary-rgb, 37,99,235), 0.1);
            background: #fff;
        }

        .pf-input:disabled,
        .pf-input[readonly] {
            background: #f8fafc;
            color: #64748b;
            cursor: not-allowed;
            border-color: #f1f5f9;
        }

        .pf-input[readonly]:focus {
            border-color: #f1f5f9;
            box-shadow: none;
        }

        .pf-error {
            color: #ef4444;
            font-size: 0.78rem;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-weight: 600;
        }

        .pf-error::before {
            content: '\F623';
            font-family: 'bootstrap-icons';
            font-size: 0.85rem;
        }

        /* Info Value Display (non-editable) */
        .pf-display-value {
            padding: 0.75rem 1.1rem;
            background: #f8fafc;
            border: 1.5px solid #f1f5f9;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #334155;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-height: 48px;
        }

        .pf-display-value i {
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .pf-display-value .empty-val {
            color: #cbd5e1;
        }

        /* Client Type Visual Badges */
        .pf-client-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }

        .pf-client-badge.internal {
            background: linear-gradient(135deg, rgba(var(--brand-primary-rgb, 37,99,235), 0.08), rgba(var(--brand-primary-rgb, 37,99,235), 0.04));
            color: var(--brand-primary, #2563eb);
            border: 1.5px solid rgba(var(--brand-primary-rgb, 37,99,235), 0.18);
        }

        .pf-client-badge.external {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(16, 185, 129, 0.04));
            color: #059669;
            border: 1.5px solid rgba(16, 185, 129, 0.18);
        }

        .pf-client-badge i {
            font-size: 1rem;
        }

        /* Detail Card (glassmorphism) */
        .pf-detail-panel {
            background: linear-gradient(135deg, rgba(var(--brand-primary-rgb, 37,99,235), 0.03) 0%, rgba(248, 250, 252, 0.8) 100%);
            border: 1px solid rgba(var(--brand-primary-rgb, 37,99,235), 0.08);
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 0.75rem;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .pf-detail-panel.external-panel {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.03) 0%, rgba(248, 250, 252, 0.8) 100%);
            border-color: rgba(16, 185, 129, 0.08);
        }

        .pf-detail-panel-header {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .pf-detail-panel-header i {
            font-size: 1.05rem;
        }

        .pf-detail-panel-header.internal-header {
            color: #1e40af;
        }

        .pf-detail-panel-header.external-header {
            color: #047857;
        }

        /* Row helpers */
        .pf-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.1rem;
        }

        .pf-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.1rem;
        }

        @media (max-width: 768px) {
            .pf-grid-2, .pf-grid-3 {
                grid-template-columns: 1fr;
            }
        }

        /* Verification styles */
        .pf-verify-notice {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            background: linear-gradient(135deg, #fffbeb, #fef9c3);
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-top: 0.75rem;
            color: #92400e;
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .pf-verify-notice i {
            color: #d97706;
            font-size: 1rem;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .pf-verify-notice a,
        .pf-verify-notice button {
            color: #b45309;
            font-weight: 700;
            text-decoration: underline;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            font-size: inherit;
        }

        /* Save Button */
        .pf-btn-save {
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            color: #fff;
            border: none;
            padding: 0.8rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            position: relative;
            overflow: hidden;
        }

        .pf-btn-save::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .pf-btn-save:hover:not(:disabled)::before {
            opacity: 1;
        }

        .pf-btn-save:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(var(--brand-primary-rgb, 37,99,235), 0.35);
        }

        .pf-btn-save:active:not(:disabled) {
            transform: translateY(0);
        }

        .pf-btn-save:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Success toast inline */
        .pf-status-success {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #6ee7b7;
            border-radius: 10px;
            color: #065f46;
            font-weight: 600;
            font-size: 0.82rem;
            animation: pf-fadeSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .pf-status-error {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: #ef4444;
            font-weight: 600;
            font-size: 0.82rem;
            animation: pf-fadeSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes pf-fadeSlideIn {
            from { opacity: 0; transform: translateX(-8px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>

    @php
        $isAdmin = Auth::user()->isAdminOrAbove();
        $user = Auth::user();
        $isInternal = $user->client_type === 'internal';
        $isExternal = $user->client_type === 'external';
    @endphp

    <!-- Security Notice for Guests -->
    @if(!$isAdmin)
        <div class="pf-security-banner">
            <div class="pf-security-banner-icon">
                <i class="bi bi-lock-fill"></i>
            </div>
            <div>
                <div class="pf-security-banner-title">Read-Only Profile</div>
                <div class="pf-security-banner-text">
                    Your personal information is locked for security. To request changes, please contact an administrator.
                </div>
            </div>
        </div>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-1">
        @csrf
        @method('patch')

        {{-- ═══ Identity Section ═══ --}}
        <div class="pf-section-divider">
            <div class="pf-section-icon">
                <i class="bi bi-person-vcard"></i>
            </div>
            <div class="pf-section-text">
                <div class="pf-section-label">Identity</div>
                <div class="pf-section-desc">Your legal name and core identity details</div>
            </div>
        </div>

        @if($isAdmin)
        <div class="pf-grid-2" style="margin-bottom: 1.1rem;">
            <div class="pf-field">
                <label for="first_name" class="pf-label">First Name</label>
                <input
                    id="first_name"
                    name="first_name"
                    type="text"
                    class="pf-input"
                    value="{{ old('first_name', $user->first_name) }}"
                    required
                    autocomplete="given-name"
                />
                @error('first_name')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="pf-field">
                <label for="middle_name" class="pf-label">Middle Name <span class="optional-tag">(Optional)</span></label>
                <input
                    id="middle_name"
                    name="middle_name"
                    type="text"
                    class="pf-input"
                    value="{{ old('middle_name', $user->middle_name) }}"
                    autocomplete="off"
                />
                @error('middle_name')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="pf-field">
            <label for="last_name" class="pf-label">Last Name</label>
            <input
                id="last_name"
                name="last_name"
                type="text"
                class="pf-input"
                value="{{ old('last_name', $user->last_name) }}"
                required
                autocomplete="family-name"
            />
            @error('last_name')
                <span class="pf-error">{{ $message }}</span>
            @enderror
        </div>
        @else
        {{-- Guests: name is read-only. Show as clean display values (not misleading inputs). --}}
        <div class="pf-grid-2" style="margin-bottom: 1.1rem;">
            <div class="pf-field">
                <label class="pf-label">First Name</label>
                <div class="pf-display-value">
                    <i class="bi bi-person"></i>
                    {{ $user->first_name ?? '' }}
                    @if(!$user->first_name)<span class="empty-val">—</span>@endif
                </div>
            </div>
            <div class="pf-field">
                <label class="pf-label">Middle Name</label>
                <div class="pf-display-value">
                    {{ $user->middle_name ?? '' }}
                    @if(!$user->middle_name)<span class="empty-val">—</span>@endif
                </div>
            </div>
        </div>
        <div class="pf-field">
            <label class="pf-label">Last Name</label>
            <div class="pf-display-value">
                <i class="bi bi-person"></i>
                {{ $user->last_name ?? '' }}
                @if(!$user->last_name)<span class="empty-val">—</span>@endif
            </div>
        </div>
        @endif

        {{-- ═══ Personal Details Section ═══ --}}
        <div class="pf-section-divider">
            <div class="pf-section-icon">
                <i class="bi bi-calendar-heart"></i>
            </div>
            <div class="pf-section-text">
                <div class="pf-section-label">Personal Details</div>
                <div class="pf-section-desc">Date of birth, sex, civil status, and nationality</div>
            </div>
        </div>

        <div class="pf-grid-2" style="margin-bottom: 1.1rem;">
            <div class="pf-field">
                <label class="pf-label">Date of Birth</label>
                <div class="pf-display-value">
                    <i class="bi bi-calendar3"></i>
                    @if($user->date_of_birth)
                        {{ \Carbon\Carbon::parse($user->date_of_birth)->format('F d, Y') }}
                    @else
                        <span class="empty-val">—</span>
                    @endif
                </div>
            </div>
            <div class="pf-field">
                <label class="pf-label">Sex</label>
                <div class="pf-display-value">
                    {{ $user->sex ?? '' }}
                    @if(!$user->sex)<span class="empty-val">—</span>@endif
                </div>
            </div>
        </div>

        <div class="pf-grid-2">
            <div class="pf-field">
                <label class="pf-label">Civil Status</label>
                <div class="pf-display-value">
                    {{ $user->civil_status ?? '' }}
                    @if(!$user->civil_status)<span class="empty-val">—</span>@endif
                </div>
            </div>
            <div class="pf-field">
                <label class="pf-label">Nationality</label>
                <div class="pf-display-value">
                    <i class="bi bi-flag"></i>
                    {{ $user->nationality ?? '' }}
                    @if(!$user->nationality)<span class="empty-val">—</span>@endif
                </div>
            </div>
        </div>

        {{-- ═══ Contact & Address Section ═══ --}}
        <div class="pf-section-divider">
            <div class="pf-section-icon">
                <i class="bi bi-geo-alt"></i>
            </div>
            <div class="pf-section-text">
                <div class="pf-section-label">Contact & Address</div>
                <div class="pf-section-desc">How to reach you and where you're located</div>
            </div>
        </div>

        <div class="pf-field">
            <label for="email" class="pf-label">Email Address</label>
            @if($isAdmin)
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="pf-input"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                />
                @error('email')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            @else
                <div class="pf-display-value">
                    <i class="bi bi-envelope"></i>
                    {{ $user->email }}
                </div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="pf-verify-notice">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" type="submit">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <div style="margin-top: 0.4rem; color: #065f46; font-weight: 600;">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="pf-grid-2">
            <div class="pf-field">
                <label class="pf-label">Contact Number</label>
                <div class="pf-display-value">
                    <i class="bi bi-phone"></i>
                    {{ $user->contact_number ?? '' }}
                    @if(!$user->contact_number)<span class="empty-val">—</span>@endif
                </div>
            </div>
            <div class="pf-field">
                <label class="pf-label">Personal / Home Address</label>
                <div class="pf-display-value">
                    <i class="bi bi-house"></i>
                    {{ $user->personal_address ?? '' }}
                    @if(!$user->personal_address)<span class="empty-val">—</span>@endif
                </div>
            </div>
        </div>

        {{-- ═══ Client Classification Section ═══ --}}
        <div class="pf-section-divider">
            <div class="pf-section-icon">
                <i class="bi bi-patch-check"></i>
            </div>
            <div class="pf-section-text">
                <div class="pf-section-label">Client Classification</div>
                <div class="pf-section-desc">Your registration type and associated details</div>
            </div>
        </div>

        <div class="pf-field">
            <label class="pf-label">Client Type</label>
            @if($isInternal)
                <span class="pf-client-badge internal">
                    <i class="bi bi-building"></i> Internal Client
                </span>
            @elseif($isExternal)
                <span class="pf-client-badge external">
                    <i class="bi bi-globe"></i> External Client
                </span>
            @else
                <div class="pf-display-value"><span class="empty-val">—</span></div>
            @endif
        </div>

        {{-- Internal-Specific Fields --}}
        @if($isInternal)
            <div class="pf-detail-panel">
                <div class="pf-detail-panel-header internal-header">
                    <i class="bi bi-mortarboard"></i> Institutional Details
                </div>
                <div class="pf-grid-2" style="margin-bottom: 1.1rem;">
                    <div class="pf-field" style="margin-bottom:0;">
                        <label class="pf-label">University Email</label>
                        <div class="pf-display-value">
                            <i class="bi bi-envelope"></i>
                            {{ $user->university_email ?? '' }}
                            @if(!$user->university_email)<span class="empty-val">—</span>@endif
                        </div>
                    </div>
                    <div class="pf-field" style="margin-bottom:0;">
                        <label class="pf-label">University ID</label>
                        <div class="pf-display-value" style="font-family: 'JetBrains Mono', monospace;">
                            {{ $user->university_id ?? '' }}
                            @if(!$user->university_id)<span class="empty-val">—</span>@endif
                        </div>
                    </div>
                </div>
                <div class="pf-grid-2">
                    <div class="pf-field" style="margin-bottom:0;">
                        <label class="pf-label">Affiliation Type</label>
                        <div class="pf-display-value">
                            {{ $user->affiliation_type ?? '' }}
                            @if(!$user->affiliation_type)<span class="empty-val">—</span>@endif
                        </div>
                    </div>
                    <div class="pf-field" style="margin-bottom:0;">
                        <label class="pf-label">College / Department</label>
                        <div class="pf-display-value">
                            {{ $user->college_department ?? '' }}
                            @if(!$user->college_department)<span class="empty-val">—</span>@endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- External-Specific Fields --}}
        @if($isExternal)
            <div class="pf-detail-panel external-panel">
                <div class="pf-detail-panel-header external-header">
                    <i class="bi bi-briefcase"></i> External Client Details
                </div>
                <div class="pf-grid-2" style="margin-bottom: 1.1rem;">
                    <div class="pf-field" style="margin-bottom:0;">
                        <label class="pf-label">Organization / Company</label>
                        <div class="pf-display-value">
                            <i class="bi bi-buildings"></i>
                            {{ $user->organization_name ?? 'Individual' }}
                        </div>
                    </div>
                    <div class="pf-field" style="margin-bottom:0;">
                        <label class="pf-label">Billing Address</label>
                        <div class="pf-display-value">
                            {{ $user->billing_address ?? '' }}
                            @if(!$user->billing_address)<span class="empty-val">—</span>@endif
                        </div>
                    </div>
                </div>
                <div class="pf-grid-2">
                    <div class="pf-field" style="margin-bottom:0;">
                        <label class="pf-label">Government ID Type</label>
                        <div class="pf-display-value">
                            {{ $user->government_id_type ?? '' }}
                            @if(!$user->government_id_type)<span class="empty-val">—</span>@endif
                        </div>
                    </div>
                    <div class="pf-field" style="margin-bottom:0;">
                        <label class="pf-label">Government ID Number</label>
                        <div class="pf-display-value" style="font-family: 'JetBrains Mono', monospace;">
                            {{ $user->government_id_number ?? '' }}
                            @if(!$user->government_id_number)<span class="empty-val">—</span>@endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ═══ Save Button ═══ --}}
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-top: 2.75rem;">
            @if($isAdmin)
            <button type="submit" class="pf-btn-save">
                <i class="bi bi-check2-circle"></i>
                {{ __('Save Changes') }}
            </button>
            @endif

            @if (session('status') === 'profile-updated')
                <div class="pf-status-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ __('Profile updated successfully.') }}
                </div>
            @endif

            @if (session('error'))
                <div class="pf-status-error">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
        </div>
    </form>
</section>
