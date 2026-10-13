<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    protected $fillable = [

        'title',
        'slug',
        'description',

        'seller_id',

        'category',
        'subcategory',

        'category_id',
        'subcategory_id',

        'listing_type',

        'price',
        'price_unit',

        'city',
        'state',
        'pincode',

        'seller_name',

        'is_verified',
        'is_featured',

        'image',
        'status',
    ];


    protected $casts = [

        'price' => 'decimal:2',

        'is_verified' => 'boolean',

        'is_featured' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | Model Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($listing) {

            if (empty($listing->slug)) {

                $listing->slug = Str::slug(
                    $listing->title
                );
            }
        });
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
    | Category
    |--------------------------------------------------------------------------
    */

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Subcategory
    |--------------------------------------------------------------------------
    */

    public function subcategoryRelation(): BelongsTo
    {
        return $this->belongsTo(
            Subcategory::class,
            'subcategory_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Product
    |--------------------------------------------------------------------------
    */

    public function product(): HasOne
    {
        return $this->hasOne(
            Product::class,
            'listing_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Property
    |--------------------------------------------------------------------------
    */

    public function property(): HasOne
    {
        return $this->hasOne(
            Property::class,
            'listing_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Land
    |--------------------------------------------------------------------------
    */

    public function land(): HasOne
    {
        return $this->hasOne(
            Land::class,
            'listing_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Service
    |--------------------------------------------------------------------------
    */

    public function service(): HasOne
    {
        return $this->hasOne(
            Service::class,
            'listing_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): HasOne
    {
        return $this->hasOne(
            Project::class,
            'listing_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    */

    public function requirements(): HasMany
    {
        return $this->hasMany(
            Requirement::class,
            'listing_id'
        );
    }



    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'listing_id');
    }
    /*
    |--------------------------------------------------------------------------
    | Listing Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            ListingDocument::class,
            'listing_id'
        );
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'listing_id');
    }
}
