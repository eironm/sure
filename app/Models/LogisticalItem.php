<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'unit_label',
        'max_quantity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'max_quantity'  => 'integer',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
    ];

    /* ─── Scopes ────────────────────────────────────────────────── */

    /**
     * Only active items available for selection.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ordered by sort_order for display.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /* ─── Helpers ───────────────────────────────────────────────── */

    /**
     * Get all active items grouped by category for the wizard UI.
     */
    public static function getGroupedForWizard(): array
    {
        return static::active()
            ->ordered()
            ->get()
            ->groupBy('category')
            ->map(fn ($items) => $items->values())
            ->toArray();
    }

    /**
     * Human-readable price label.
     */
    public function getPriceLabelAttribute(): string
    {
        if ((float) $this->price <= 0) {
            return 'Included';
        }

        return '₱' . number_format($this->price, 2) . '/' . $this->unit_label;
    }
}
