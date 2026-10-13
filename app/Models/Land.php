<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Land extends Model
{
    protected $fillable = [
        'listing_id',
        'land_type',
        'total_area',
        'area_unit',
        'survey_number',
        'gat_number',
        'ownership_type',
        'road_access',
        'zoning',
        'water_availability',
        'electricity_availability',
        'nearby_infrastructure',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}