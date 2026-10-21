<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityImage extends Model
{
    protected $table = 'facility_images';
    
    protected $fillable = [
        'facility_id',
        'draft_token',
        'image_url',
        'image_path',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
    ];

    protected $appends = [
        'url',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the URL for the image, prioritizing the uploaded file.
     */
    public function getUrlAttribute()
    {
        if ($this->image_path) {
            // If the path already contains 'uploads/', it's using the new direct public method
            if (str_starts_with($this->image_path, 'uploads/')) {
                return asset($this->image_path);
            }
            
            // Fallback for old storage-based paths
            return asset('storage/' . $this->image_path);
        }
        return $this->image_url;
    }
}
