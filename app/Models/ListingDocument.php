<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingDocument extends Model
{
    protected $fillable = [
        'listing_id',
        'document_name',
        'document_path',
        'document_type',
        'status',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}