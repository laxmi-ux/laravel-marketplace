<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requirement extends Model
{
    protected $fillable = [

        // Relationships
        'user_id',
        'listing_id',
        'seller_id',
        'requirement_source',

        // Buyer information
        'name',
        'email',
        'phone',

        // Common requirement information
        'requirement_type',
        'quantity',
        'unit',
        'budget',
        'delivery_location',
        'required_date',

        // Product fields
        'product_name',
        'product_category',
        'product_quality',
        'preferred_brand',
        'product_specification',

        // Property fields
        'property_type',
        'property_purpose',
        'property_location',
        'property_area',
        'property_area_unit',
        'bedrooms',
        'bathrooms',
        'property_requirements',

        // Land fields
        'land_type',
        'land_purpose',
        'land_location',
        'land_area',
        'land_area_unit',
        'road_access',
        'land_requirements',

        // Service fields
        'service_name',
        'service_type',
        'service_location',
        'service_timeline',
        'provider_experience',
        'service_scope',

        // Project fields
        'project_type',
        'project_name',
        'project_location',
        'project_area',
        'project_timeline',
        'project_scope',

        // Additional information
        'message',
        'status',
    ];

    protected $casts = [
        'required_date' => 'date',
        'budget' => 'decimal:2',

        'quantity' => 'decimal:2',
        'property_area' => 'decimal:2',
        'land_area' => 'decimal:2',
        'project_area' => 'decimal:2',

        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | LISTING
    |--------------------------------------------------------------------------
    */

    /**
     * Requirement belongs to a listing.
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(
            Listing::class,
            'listing_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SELLER
    |--------------------------------------------------------------------------
    */

    /**
     * Requirement belongs to a seller.
     *
     * Used for direct listing enquiries.
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
    | BUYER / USER
    |--------------------------------------------------------------------------
    */

    /**
     * Requirement belongs to the buyer/user
     * who created the requirement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERAL ENQUIRY MESSAGES
    |--------------------------------------------------------------------------
    */

    /**
     * Requirement has many direct enquiry messages.
     *
     * This is primarily used for normal
     * listing-based communication.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(
            EnquiryMessage::class,
            'requirement_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RFQs
    |--------------------------------------------------------------------------
    */

    /**
     * One requirement can generate
     * multiple RFQs.
     *
     * Example:
     *
     * Requirement #11
     *      ├── RFQ → Seller A
     *      ├── RFQ → Seller B
     *      └── RFQ → Seller C
     */
    public function rfqs(): HasMany
    {
        return $this->hasMany(
            Rfq::class,
            'requirement_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | QUOTATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * One requirement can receive
     * multiple seller quotations.
     *
     * Example:
     *
     * Requirement #11
     *      ├── Quotation → Seller A
     *      ├── Quotation → Seller B
     *      └── Quotation → Seller C
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(
            Quotation::class,
            'requirement_id'
        );
    }
}
