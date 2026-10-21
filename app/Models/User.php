<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /** Ordered role hierarchy (lowest → highest) */
    const ROLE_GUEST      = 'guest';
    const ROLE_CASHIER    = 'cashier';
    const ROLE_STAFF      = 'staff';
    const ROLE_ADMIN      = 'admin';
    const ROLE_SUPERADMIN = 'superadmin';

    const ALL_ROLES = [
        self::ROLE_GUEST,
        self::ROLE_CASHIER,
        self::ROLE_STAFF,
        self::ROLE_ADMIN,
        self::ROLE_SUPERADMIN,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'is_active',
        'client_type',
        'contact_number',
        // Universal personal identity
        'sex',
        'civil_status',
        'nationality',
        'personal_address',
        // Internal-specific
        'university_email',
        'university_id',
        'affiliation_type',
        'college_department',
        // External-specific
        'date_of_birth',
        'organization_name',
        'billing_address',
        'government_id_type',
        'government_id_number',
        // Profile image
        'avatar',
        // Pre-login verification code (6-digit, hashed)
        'verification_code',
        'verification_code_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Public URL for the user's avatar, or null if none.
     *
     * Handles both storage conventions:
     *  - New uploads live in public/uploads/avatars (asset() directly).
     *  - Legacy uploads live in storage/app/public/avatars (asset('storage/…')).
     */
    public function avatarUrl(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        return str_starts_with($this->avatar, 'uploads/')
            ? asset($this->avatar)
            : asset('storage/' . $this->avatar);
    }

    /* ── Verification Code Helpers ────────────────────────────────── */

    /**
     * Whether the user has successfully entered their pre-login
     * verification code (external and internal both require this).
     */
    public function codeVerified(): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Whether the user still needs to complete any pre-login gate:
     *   - verification code not yet entered, or
     *   - internal user not yet approved by an administrator.
     */
    public function needsActivation(): bool
    {
        if (!$this->codeVerified()) {
            return true;
        }

        // Internal university accounts also require admin approval.
        return ($this->client_type ?? 'external') === 'internal' && !$this->is_active;
    }

    /* ── Role Helpers ─────────────────────────────────────────────── */

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCashier(): bool
    {
        return $this->role === self::ROLE_CASHIER;
    }

    public function isGuest(): bool
    {
        return $this->role === self::ROLE_GUEST;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    /** SuperAdmin or Admin */
    public function isAdminOrAbove(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPERADMIN]);
    }

    /** SuperAdmin, Admin, or Cashier — can access Cashier panel */
    public function canAccessCashier(): bool
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN, self::ROLE_CASHIER]);
    }

    /** Check if user is restricted to ONLY the cashier panel */
    public function isRestrictedCashier(): bool
    {
        return $this->role === self::ROLE_CASHIER;
    }

    /** Get a human-readable role label */
    public function roleLabel(): string
    {
        return match($this->role) {
            self::ROLE_SUPERADMIN => 'Super Admin',
            self::ROLE_ADMIN      => 'Admin',
            self::ROLE_CASHIER    => 'Cashier',
            self::ROLE_STAFF      => 'Staff',
            default               => 'Guest',
        };
    }

    /* ── Relationships ────────────────────────────────────────────── */

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function cashierConfirmations()
    {
        return $this->hasMany(Reservation::class, 'cashier_signed_by');
    }

    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /* ── Email Notification Overrides ────────────────────────────── */

    /**
     * Send the password reset notification using our branded template.
     */
    public function sendPasswordResetNotification($token): void
    {
        // Never crash the forgot-password request if the mail transport
        // fails (e.g., test-mode API key on the live host). The failure is
        // logged; Laravel's broker still reports success to the user so the
        // email address is not enumerated, and the error surfaces on the
        // verify-email page instead of a raw 500.
        safe_notify($this, new CustomResetPassword($token));
    }

    /**
     * Send the email verification notification using our branded template.
     */
    public function sendEmailVerificationNotification(): void
    {
        safe_notify($this, new CustomVerifyEmail);
    }
}
