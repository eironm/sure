<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'facility_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'reason',
        'number_of_people',
        'status',
        'admin_remarks',
        'total_price',
        'amount_paid',
        'booking_reference',
        'payment_status',
        'payment_id',
        'payment_method',
        'payment_deadline',
        'cashier_signed_by',
        'cashier_signed_at',
        'cash_receipt_number',
        'cash_payment_notes',
        // ── New: Pencil Book Fields ─────────────────────────
        'event_title',
        'end_user_type',
        'end_user_name',
        'reserved_by_name',
        'reserved_by_email',
        'reserved_by_phone',
        // ── New: Endorsement Chain ──────────────────────────
        'endorsed_by_name',
        'endorsed_by_position',
        'endorsement_status',
        // ── New: User Type & Pricing ────────────────────────
        'client_type_snapshot',
        'is_fee_waived',
        // ── New: Security Deposit ───────────────────────────
        'security_deposit',
        'security_deposit_paid',
        'security_deposit_status',
        // ── New: Logistical & Quotation ─────────────────────
        'arrangements',
        'quotation_breakdown',
        // ── New: Agreement & Cancellation ────────────────────
        'agreement_accepted',
        'agreement_accepted_at',
        'cancellation_reason',
        'cancelled_at',
        // ── New: Official Receipt ───────────────────────────
        'official_receipt_number',
    ];

    protected $casts = [
        'start_date'            => 'date',
        'end_date'              => 'date',
        'start_time'            => 'datetime:H:i',
        'end_time'              => 'datetime:H:i',
        'total_price'           => 'decimal:2',
        'amount_paid'           => 'decimal:2',
        'payment_deadline'      => 'datetime',
        'cashier_signed_at'     => 'datetime',
        // New casts
        'is_fee_waived'         => 'boolean',
        'security_deposit'      => 'decimal:2',
        'security_deposit_paid' => 'decimal:2',
        'arrangements'          => 'array',
        'quotation_breakdown'   => 'array',
        'agreement_accepted'    => 'boolean',
        'agreement_accepted_at' => 'datetime',
        'cancelled_at'          => 'datetime',
    ];

    /* ─── Relationships ─────────────────────────────────────────── */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function slotReservations()
    {
        return $this->hasMany(SlotReservation::class);
    }

    public function cashierSignedBy()
    {
        return $this->belongsTo(User::class, 'cashier_signed_by');
    }

    public function documents()
    {
        return $this->hasMany(ReservationDocument::class);
    }

    /* ─── Scopes ────────────────────────────────────────────────── */

    /** Reservations that are still active (pending admin review or approved). */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    /**
     * Reservations that are awaiting payment and past their deadline.
     * Used by the expiry middleware and cron route.
     */
    public function scopeExpiredUnpaid($query)
    {
        return $query
            ->whereIn('payment_status', ['pending', 'cash_pending', 'partially_paid'])
            // Exclude ALL terminal statuses — expired_unpaid_cancelled is the key one
            // that was missing, causing endless re-notification on every page load.
            ->whereNotIn('status', ['cancelled', 'expired_unpaid_cancelled', 'denied'])
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now());
    }

    /* ─── Attribute Accessors ──────────────────────────────────── */

    /**
     * Backward-compatible alias: maps 'purpose' to the 'reason' database column.
     */
    public function getPurposeAttribute(): ?string
    {
        return $this->attributes['reason'] ?? null;
    }

    /* ─── Payment Helpers ──────────────────────────────────────── */

    /**
     * Calculates the remaining unpaid balance.
     */
    public function getRemainingBalanceAttribute()
    {
        return max(0.00, (float) $this->total_price - (float) ($this->amount_paid ?? 0.00));
    }

    /**
     * True if the reservation is still awaiting a payment action from the guest.
     */
    public function isPendingPayment(): bool
    {
        return in_array($this->payment_status, ['pending', 'cash_pending', 'partially_paid'])
            && $this->status !== 'cancelled'
            && $this->payment_deadline !== null
            && $this->payment_deadline->isFuture();
    }

    /**
     * True if the payment window has lapsed (not yet cancelled by job/middleware).
     */
    public function isExpired(): bool
    {
        return in_array($this->payment_status, ['pending', 'cash_pending', 'partially_paid'])
            && $this->status !== 'cancelled'
            && $this->payment_deadline !== null
            && $this->payment_deadline->isPast();
    }

    /**
     * Human-readable absolute deadline, e.g. "Friday, April 18 at 3:48 PM".
     */
    public function deadlineForHumans(): string
    {
        if (!$this->payment_deadline) {
            return 'N/A';
        }

        return $this->payment_deadline->format('l, F j \a\t g:i A');
    }

    /* ─── New: Client Type & Fee Helpers ───────────────────────── */

    /**
     * Whether this reservation was made by an internal user.
     */
    public function isInternal(): bool
    {
        return $this->client_type_snapshot === 'internal';
    }

    /**
     * Whether this reservation was made by an external user.
     */
    public function isExternal(): bool
    {
        return $this->client_type_snapshot === 'external';
    }

    /**
     * Whether a security deposit is required for this reservation.
     */
    public function requiresDeposit(): bool
    {
        return !$this->is_fee_waived
            && (float) $this->security_deposit > 0
            && $this->security_deposit_status !== 'not_required';
    }

    /**
     * Remaining security deposit balance.
     */
    public function getDepositBalanceAttribute(): float
    {
        return max(0.00, (float) $this->security_deposit - (float) $this->security_deposit_paid);
    }

    /* ─── New: Quotation Helpers ───────────────────────────────── */

    /**
     * Calculate the grand total from the quotation breakdown.
     */
    public function getQuotationTotalAttribute(): float
    {
        if (!$this->quotation_breakdown || !is_array($this->quotation_breakdown)) {
            return (float) $this->total_price;
        }

        return array_sum(array_column($this->quotation_breakdown, 'amount'));
    }

    /**
     * Get arrangements formatted for display.
     */
    public function getArrangementsListAttribute(): array
    {
        if (!$this->arrangements || !is_array($this->arrangements)) {
            return [];
        }

        return $this->arrangements;
    }

    /**
     * Get total cost of all arrangements.
     */
    public function getArrangementsTotalAttribute(): float
    {
        if (!$this->arrangements || !is_array($this->arrangements)) {
            return 0.00;
        }

        return array_sum(array_column($this->arrangements, 'subtotal'));
    }

    /* ─── New: Endorsement Helpers ─────────────────────────────── */

    /**
     * Whether endorsement is pending.
     */
    public function needsEndorsement(): bool
    {
        return $this->endorsement_status === 'pending';
    }

    /**
     * Whether endorsement has been received.
     */
    public function isEndorsed(): bool
    {
        return in_array($this->endorsement_status, ['endorsed', 'not_required', 'waived']);
    }

    /**
     * Human-readable endorsement status label.
     */

    /**
     * Human-readable reservation status label (normalized from internal values).
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'                     => 'Pending',
            'approved'                    => 'Approved',
            'denied'                      => 'Denied',
            'cancelled'                   => 'Cancelled',
            'expired_unpaid_cancelled'    => 'Cancelled',
            'completed'                   => 'Completed',
            default                       => ucfirst($this->status ?? 'Unknown'),
        };
    }
    public function getEndorsementLabelAttribute(): string
    {
        return match ($this->endorsement_status) {
            'not_required' => 'Not Required',
            'pending'      => 'Pending Endorsement',
            'endorsed'     => 'Endorsed',
            'waived'       => 'Waived',
            default        => ucfirst($this->endorsement_status ?? 'Unknown'),
        };
    }

    /**
     * Human-readable client type label.
     */
    public function getClientTypeLabelAttribute(): string
    {
        return match ($this->client_type_snapshot) {
            'internal' => 'Internal (University)',
            'external' => 'External',
            default    => 'Not Specified',
        };
    }
}
