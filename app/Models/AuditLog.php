<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'details',
        'ip_address',
    ];

    /**
     * Get the administrator who performed the action.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /* ── Presentation helpers (single source of truth) ─────────── */

    /**
     * Canonical action categories mapped to label, icon, and CSS class.
     */
    public const ACTION_MAP = [
        'created'     => ['label' => 'Created',     'icon' => 'bi-plus-circle-fill',  'class' => 'created'],
        'updated'     => ['label' => 'Updated',     'icon' => 'bi-pencil-fill',       'class' => 'updated'],
        'deleted'     => ['label' => 'Deleted',     'icon' => 'bi-trash-fill',        'class' => 'deleted'],
        'activated'   => ['label' => 'Activated',   'icon' => 'bi-check-circle-fill', 'class' => 'created'],
        'deactivated' => ['label' => 'Deactivated', 'icon' => 'bi-x-circle-fill',     'class' => 'deleted'],
        'approved'    => ['label' => 'Approved',    'icon' => 'bi-check2-circle',     'class' => 'created'],
        'denied'      => ['label' => 'Denied',      'icon' => 'bi-slash-circle-fill', 'class' => 'deleted'],
        'cancelled'   => ['label' => 'Cancelled',   'icon' => 'bi-x-octagon-fill',    'class' => 'deleted'],
        'confirmed'   => ['label' => 'Payment Confirmed', 'icon' => 'bi-cash-coin',   'class' => 'created'],
    ];

    /** Normalized category key for this log's action. */
    public function actionCategory(): string
    {
        $action = strtolower($this->action ?? '');
        foreach (self::ACTION_MAP as $key => $meta) {
            if (str_contains($action, $key)) {
                return $key;
            }
        }
        return 'other';
    }

    /** Human-readable action label. */
    public function actionLabel(): string
    {
        return self::ACTION_MAP[$this->actionCategory()]['label'] ?? ucfirst($this->action);
    }

    /** Bootstrap icon class for the action. */
    public function actionIcon(): string
    {
        return self::ACTION_MAP[$this->actionCategory()]['icon'] ?? 'bi-record-circle';
    }

    /** Badge CSS suffix (created / updated / deleted / other). */
    public function actionClass(): string
    {
        return self::ACTION_MAP[$this->actionCategory()]['class'] ?? 'other';
    }

    /** Display name of the actor, safe against deleted accounts. */
    public function actorName(): string
    {
        return $this->admin->name ?? 'Deleted account';
    }

    /** Filter scope used by the audit log screens. */
    public function scopeFiltered($query, ?string $search, ?string $action, ?string $from, ?string $to)
    {
        return $query
            ->when($search, fn ($q) => $q->where(function ($q2) use ($search) {
                $q2->where('details', 'like', "%{$search}%")
                   ->orWhere('target_type', 'like', "%{$search}%")
                   ->orWhere('ip_address', 'like', "%{$search}%")
                   ->orWhereHas('admin', fn ($q3) => $q3->where('name', 'like', "%{$search}%")
                                                        ->orWhere('email', 'like', "%{$search}%"));
            }))
            ->when($action, fn ($q) => $q->where('action', 'like', "%{$action}%"))
            ->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to));
    }
}
