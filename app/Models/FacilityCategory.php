<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityCategory extends Model
{
    protected $table = 'facility_categories';

    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
    ];

    public function facilities()
    {
        return $this->hasMany(Facility::class, 'category_id');
    }
}
