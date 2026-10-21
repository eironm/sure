<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'location',
        'capacity',
        'price_per_hour',
        'is_available',
        'amenities',
        'operating_hours',
        'contact_person',
        'contact_phone',
        'terms_conditions',
        'category_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the images for the facility.
     */
    public function images()
    {
        return $this->hasMany(FacilityImage::class)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * Get the featured image for the facility.
     */
    public function featuredImage()
    {
        return $this->hasOne(FacilityImage::class)->where('is_featured', true);
    }

    /**
     * Get the first image or featured image for the facility.
     */
    public function getMainImage()
    {
        $featured = $this->featuredImage()->first();
        if ($featured) {
            return $featured->url;
        }
        
        $first = $this->images()->first();
        return $first ? $first->url : null;
    }

    /**
     * Get the category for the facility.
     */
    public function category()
    {
        return $this->belongsTo(FacilityCategory::class, 'category_id');
    }

    /**
     * Get the tags for the facility.
     */
    public function tags()
    {
        return $this->belongsToMany(FacilityTag::class, 'facility_tag', 'facility_id', 'tag_id');
    }

    /**
     * Get the operating hours for the facility.
     */
    public function operatingHours()
    {
        return $this->hasMany(FacilityOperatingHour::class);
    }

    /**
     * Get the blackout dates for the facility.
     */
    public function blackoutDates()
    {
        return $this->hasMany(FacilityBlackoutDate::class);
    }

    /**
     * Get the time slots for the facility.
     */
    public function timeSlots()
    {
        return $this->hasMany(FacilityTimeSlot::class);
    }

    /**
     * Get the reservations for the facility.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
