<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Listing;

class SellerPublicController extends Controller
{
    public function show($id)
    {
        $seller = Seller::where('id', $id)
            ->where('is_verified', true)
            ->firstOrFail();

        $listings = Listing::where('seller_id', $seller->id)
            ->where('status', 'approved')
            ->latest()
            ->paginate(12);

        return view('seller.public.show', compact(
            'seller',
            'listings'
        ));
    }
}