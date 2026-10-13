<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'quotation_id',
        'requirement_id',
        'enquiry_id',
        'buyer_id',
        'seller_id',
        'listing_id',

        'category',
        'product_name',
        'quantity',
        'unit',
        'unit_price',

        // Property / Land
        'land_location',
        'land_area',
        'land_area_unit',
        'property_type',
        'property_location',
        'property_area',
        'property_area_unit',

        // Services
        'service_name',
        'service_duration',

        // Projects
        'project_name',
        'project_location',
        'project_timeline',

        // Pricing
        'subtotal',
        'delivery_charges',
        'other_charges',
        'tax',
        'total_amount',

        // Delivery
        'delivery_address',
        'delivery_location',
        'delivery_time',

        // Status
        'payment_status',
        'order_status',

        // Timestamps
        'ordered_at',
        'confirmed_at',
        'processing_at',

        // Property / Land transaction timestamps
        'negotiation_at',
        'documentation_at',

        // Product order timestamps
        'shipped_at',
        'delivered_at',

        'completed_at',
        'cancelled_at',

        'notes',
    ];


    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',

        'land_area' => 'decimal:2',
        'property_area' => 'decimal:2',

        'subtotal' => 'decimal:2',
        'delivery_charges' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',

        'ordered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'processing_at' => 'datetime',

        'negotiation_at' => 'datetime',
        'documentation_at' => 'datetime',

        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',

        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(
            Quotation::class,
            'quotation_id'
        );
    }


    public function requirement(): BelongsTo
    {
        return $this->belongsTo(
            Requirement::class,
            'requirement_id'
        );
    }


    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(
            Enquiry::class,
            'enquiry_id'
        );
    }


    public function buyer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'buyer_id'
        );
    }


    public function seller(): BelongsTo
    {
        return $this->belongsTo(
            Seller::class,
            'seller_id'
        );
    }


    public function listing(): BelongsTo
    {
        return $this->belongsTo(
            Listing::class,
            'listing_id'
        );
    }


    public function review(): HasOne
    {
        return $this->hasOne(
            Review::class,
            'order_id'
        );
    }

public function payments()
{
    return $this->hasMany(\App\Models\Payment::class);
}
}