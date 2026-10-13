<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    protected $fillable = [
        'requirement_id',
        'seller_id',
        'buyer_id',
        'category',

        // Product
        'product_name',
        'quantity',
        'unit',
        'unit_price',
        'product_subtotal',

        // Land
        'land_location',
        'land_area',
        'land_area_unit',
        'land_price_per_unit',
        'land_price',
        'development_charges',
        'registration_charges',

        // Property
        'property_type',
        'property_location',
        'property_area',
        'property_area_unit',
        'property_price',
        'maintenance_charges',

        // Service
        'service_name',
        'service_description',
        'service_duration',
        'service_charges',
        'labour_charges',
        'material_charges',

        // Project
        'project_name',
        'project_location',
        'project_scope',
        'project_value',
        'professional_charges',
        'project_timeline',

        // Common
        'delivery_charges',
        'other_charges',
        'tax',
        'total_amount',
        'delivery_time',
        'warranty',
        'valid_until',
        'notes',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'product_subtotal' => 'decimal:2',

        'land_area' => 'decimal:2',
        'land_price_per_unit' => 'decimal:2',
        'land_price' => 'decimal:2',
        'development_charges' => 'decimal:2',
        'registration_charges' => 'decimal:2',

        'property_area' => 'decimal:2',
        'property_price' => 'decimal:2',
        'maintenance_charges' => 'decimal:2',

        'service_charges' => 'decimal:2',
        'labour_charges' => 'decimal:2',
        'material_charges' => 'decimal:2',

        'project_value' => 'decimal:2',
        'professional_charges' => 'decimal:2',

        'delivery_charges' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',

        'valid_until' => 'date',
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
    | Buyer
    |--------------------------------------------------------------------------
    */

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'buyer_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RFQ
    |--------------------------------------------------------------------------
    |
    | Quotations do not have rfq_id.
    |
    | RFQ is identified using:
    | requirement_id + seller_id
    |
    */

    public function rfq()
    {
        return $this->hasOne(
            Rfq::class,
            'requirement_id',
            'requirement_id'
        )->where(
            'seller_id',
            $this->seller_id
        );
    }
}