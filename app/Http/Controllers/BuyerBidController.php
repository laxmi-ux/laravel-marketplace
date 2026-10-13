<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\SellerNotificationHelper;

class BuyerBidController extends Controller
{
    /**
     * Buyer active/all bids
     */
    public function index()
    {
        $buyerId = Auth::id();

        $bids = Bid::with([
            'listing',
            'seller',
        ])
            ->where('user_id', $buyerId)
            ->latest()
            ->paginate(10);

        $activeCount = Bid::where('user_id', $buyerId)
            ->whereIn('status', ['pending', 'active'])
            ->count();

        $acceptedCount = Bid::where('user_id', $buyerId)
            ->where('status', 'accepted')
            ->count();

        $rejectedCount = Bid::where('user_id', $buyerId)
            ->where('status', 'rejected')
            ->count();

        $withdrawnCount = Bid::where('user_id', $buyerId)
            ->where('status', 'withdrawn')
            ->count();

        return view('buyer.bids.index', compact(
            'bids',
            'activeCount',
            'acceptedCount',
            'rejectedCount',
            'withdrawnCount'
        ));
    }

    /**
     * Show bid form
     */
    public function create($listingId)
    {
        $listing = Listing::with('seller')
            ->findOrFail($listingId);

        // Listing must have seller
        if (!$listing->seller_id) {
            return back()->with(
                'error',
                'This listing is not currently available for bidding.'
            );
        }

        // Seller cannot bid on own listing
        if (
            $listing->seller &&
            $listing->seller->user_id === Auth::id()
        ) {
            return back()->with(
                'error',
                'You cannot place a bid on your own listing.'
            );
        }

        return view('buyer.bids.create', compact('listing'));
    }

    /**
     * Store buyer bid
     */
    public function store(Request $request, $listingId)
    {
        $listing = Listing::with('seller')
            ->findOrFail($listingId);

        if (!$listing->seller_id || !$listing->seller) {
            return back()
                ->withInput()
                ->withErrors([
                    'bid_amount' => 'This listing does not have a valid seller.',
                ]);
        }

        // Seller cannot bid on own listing
        if ($listing->seller->user_id === Auth::id()) {
            return back()
                ->withInput()
                ->withErrors([
                    'bid_amount' => 'You cannot place a bid on your own listing.',
                ]);
        }

        $validated = $request->validate([
            'bid_amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'message' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        // Prevent multiple pending bids on same listing
        $existingBid = Bid::where('user_id', Auth::id())
            ->where('listing_id', $listing->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingBid) {
            return back()
                ->withInput()
                ->withErrors([
                    'bid_amount' =>
                    'You already have an active bid on this listing.',
                ]);
        }

        $bid = Bid::create([
            'user_id' => Auth::id(),
            'listing_id' => $listing->id,
            'seller_id' => $listing->seller_id,
            'bid_amount' => $validated['bid_amount'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        // Notify Seller
        SellerNotificationHelper::send(
            $listing->seller_id,
            'New Bid Received',
            'You have received a new bid of ₹'
                . number_format($bid->bid_amount, 2)
                . ' for your listing: '
                . ($listing->title ?? 'your listing'),
            'bid',
            route('seller.bids.show', $bid->id)
        );


        return redirect()
            ->route('buyer.bids.index')
            ->with(
                'success',
                'Your bid has been submitted successfully.'
            );
    }

    /**
     * Buyer bid details
     */
    public function show($id)
    {
        $bid = Bid::with([
            'listing',
            'seller',
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('buyer.bids.show', compact('bid'));
    }

    /**
     * Withdraw pending bid
     */
    public function withdraw($id)
    {
        $bid = Bid::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $bid->update([
            'status' => 'withdrawn',
        ]);

        return redirect()
            ->route('buyer.bids.index')
            ->with(
                'success',
                'Your bid has been withdrawn.'
            );
    }
}
