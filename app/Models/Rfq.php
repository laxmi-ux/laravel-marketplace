<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rfq extends Model
{
    protected $table = 'rfqs';

    protected $fillable = [
        'requirement_id',
        'seller_id',
        'status',
        'sent_at',
        'viewed_at',
        'responded_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Requirement
    |--------------------------------------------------------------------------
    */

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(
            Requirement::class,
            'requirement_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Seller
    |--------------------------------------------------------------------------
    */

    public function seller(): BelongsTo
    {
        return $this->belongsTo(
            Seller::class,
            'seller_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    public function messages(): HasMany
    {
        return $this->hasMany(
            EnquiryMessage::class,
            'rfq_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Quotation
    |--------------------------------------------------------------------------
    |
    | Quotations are currently linked using:
    | requirement_id + seller_id
    |
    | There is no rfq_id column in quotations.
    |
    */

    public function quotation()
    {
        return $this->hasOne(
            Quotation::class,
            'requirement_id',
            'requirement_id'
        )->where('seller_id', $this->seller_id);
    }
}