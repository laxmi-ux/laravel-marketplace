<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $table = 'enquiries';

    protected $fillable = [
        'user_id',
        'listing_id',
        'seller_id',

        // Buyer enquiry details
        'name',
        'email',
        'phone',
        'quantity',
        'unit',
        'message',

        // Seller response
        'seller_response',
        'responded_at',

        // Commercial Offer
        'unit_price',
        'delivery_charges',
        'other_charges',
        'tax',
        'total_amount',
        'delivery_time',
        'offer_valid_until',
        'offer_notes',

        // Commercial Offer timestamps
        'offer_sent_at',
        'offer_accepted_at',
        'offer_rejected_at',

        // Enquiry status
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',

        'unit_price' => 'decimal:2',
        'delivery_charges' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',

        'responded_at' => 'datetime',
        'offer_sent_at' => 'datetime',
        'offer_accepted_at' => 'datetime',
        'offer_rejected_at' => 'datetime',

        'offer_valid_until' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function hasOffer(): bool
    {
        return !is_null($this->offer_sent_at);
    }

    public function isOfferPending(): bool
    {
        return $this->status === 'offer_sent';
    }

    public function isOfferAccepted(): bool
    {
        return $this->status === 'offer_accepted';
    }

    public function isOfferRejected(): bool
    {
        return $this->status === 'offer_rejected';
    }
}