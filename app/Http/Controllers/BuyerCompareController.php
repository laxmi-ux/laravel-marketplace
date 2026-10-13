<?php

namespace App\Http\Controllers;

use App\Models\CompareListing;
use Illuminate\Support\Facades\Auth;

class BuyerCompareController extends Controller
{
    public function index()
    {
        $compareListings = CompareListing::with([
            'listing.seller',
            'listing.categoryRelation',
        ])
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

        return view('buyer.compare.index', compact('compareListings'));
    }

    public function store($listingId)
    {
        CompareListing::firstOrCreate([
            'user_id' => Auth::id(),
            'listing_id' => $listingId,
        ]);

        return back()->with('success', 'Listing added to compare.');
    }

    public function destroy($listingId)
    {
        CompareListing::where('user_id', Auth::id())
            ->where('listing_id', $listingId)
            ->delete();

        return back()->with('success', 'Listing removed from compare.');
    }
}