<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    protected $fillable = [
        'listing_id',
        'property_type',
        'area',
        'area_unit',
        'bedrooms',
        'bathrooms',
        'amenities',
        'property_status',
    ];

    protected $casts = [
        'amenities' => 'array',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}