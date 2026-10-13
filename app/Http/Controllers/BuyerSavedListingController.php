<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\SavedListing;
use Illuminate\Support\Facades\Auth;

class BuyerSavedListingController extends Controller
{
    /**
     * Display buyer's saved listings.
     */
    public function index()
    {
        $savedListings = SavedListing::with('listing')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view(
            'buyer.saved-listings.index',
            compact('savedListings')
        );
    }

    /**
     * Save a listing.
     */
    public function store($listingId)
    {
        $listing = Listing::findOrFail($listingId);

        SavedListing::firstOrCreate([
            'user_id' => Auth::id(),
            'listing_id' => $listing->id,
        ]);

        return back()->with(
            'success',
            'Listing saved successfully.'
        );
    }

    /**
     * Remove a saved listing.
     */
    public function destroy($listingId)
    {
        SavedListing::where('user_id', Auth::id())
            ->where('listing_id', $listingId)
            ->delete();

        return back()->with(
            'success',
            'Listing removed from saved listings.'
        );
    }
}