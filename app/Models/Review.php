<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Seller;
use App\Models\Listing;
use App\Models\Order;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'listing_id',
        'order_id',
        'rating',
        'review',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Buyer
    |--------------------------------------------------------------------------
    */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Seller
    |--------------------------------------------------------------------------
    */
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Listing
    |--------------------------------------------------------------------------
    */
    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Order
    |--------------------------------------------------------------------------
    */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
