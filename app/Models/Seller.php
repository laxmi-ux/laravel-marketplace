<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Seller extends Model
{

    use  Notifiable;



    protected $fillable = [
        'user_id',
        'bussiness_name',
        'category',
        'location',
        'links',
        'logo',
        'is_verified',
        'is_featured',
        'slug',
    ];

    /*
    |--------------------------------------------------------------------------
    | USER / OWNER
    |--------------------------------------------------------------------------
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
    | LISTINGS
    |--------------------------------------------------------------------------
    */

    public function listings(): HasMany
    {
        return $this->hasMany(
            Listing::class,
            'seller_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REQUIREMENTS / ENQUIRIES
    |--------------------------------------------------------------------------
    */

    public function requirements(): HasMany
    {
        return $this->hasMany(
            Requirement::class,
            'seller_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RFQs
    |--------------------------------------------------------------------------
    */

    public function rfqs(): HasMany
    {
        return $this->hasMany(
            Rfq::class,
            'seller_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | QUOTATIONS
    |--------------------------------------------------------------------------
    */

    public function quotations(): HasMany
    {
        return $this->hasMany(
            Quotation::class,
            'seller_id'
        );
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'seller_id');
    }
}
