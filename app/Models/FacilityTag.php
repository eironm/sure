<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityTag extends Model
{
    protected $table = 'facility_tags';

    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_tag', 'tag_id', 'facility_id');
    }
}
