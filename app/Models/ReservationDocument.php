<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationDocument extends Model
{
    protected $table = 'reservation_documents';

    protected $fillable = [
        'reservation_id',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'document_type',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    protected $appends = [
        'url',
        'formatted_size',
        'file_category',
        'icon_class',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    protected static function booted()
    {
        static::deleting(function ($doc) {
            $path = public_path($doc->file_path);
            if (file_exists($path)) {
                @unlink($path);
            }
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
            }
        });
    }

    /**
     * Get direct public asset URL.
     */
    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'uploads/')) {
            return asset($this->file_path);
        }

        return asset('storage/' . $this->file_path);
    }

    /**
     * Determine general file category: pdf, image, word, or other.
     */
    public function getFileCategoryAttribute(): string
    {
        $mime = strtolower($this->file_type ?? '');
        $ext = strtolower(pathinfo($this->original_name ?? $this->file_path, PATHINFO_EXTENSION));

        if (str_contains($mime, 'pdf') || $ext === 'pdf') {
            return 'pdf';
        }

        if (str_starts_with($mime, 'image/') || in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
            return 'image';
        }

        if (
            str_contains($mime, 'word') ||
            str_contains($mime, 'officedocument') ||
            in_array($ext, ['doc', 'docx'])
        ) {
            return 'word';
        }

        return 'other';
    }

    /**
     * Get human-readable file size (e.g. 1.2 MB, 350 KB).
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes, 1024));
        $val = round($bytes / pow(1024, $i), 1);

        return $val . ' ' . ($units[$i] ?? 'B');
    }

    /**
     * Bootstrap icon class suitable for the file category.
     */
    public function getIconClassAttribute(): string
    {
        return match ($this->file_category) {
            'pdf'   => 'bi bi-file-earmark-pdf-fill text-danger',
            'image' => 'bi bi-file-earmark-image-fill text-primary',
            'word'  => 'bi bi-file-earmark-word-fill text-info',
            default => 'bi bi-file-earmark-text-fill text-secondary',
        };
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->file_category === 'pdf';
    }

    public function getIsImageAttribute(): bool
    {
        return $this->file_category === 'image';
    }

    public function getIsWordAttribute(): bool
    {
        return $this->file_category === 'word';
    }
}
