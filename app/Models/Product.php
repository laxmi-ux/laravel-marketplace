<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'listing_id',
        'brand',
        'product_code',
        'quantity',
        'unit',
        'specifications',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'specifications' => 'array',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}