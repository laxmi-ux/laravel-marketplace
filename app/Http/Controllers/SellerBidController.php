<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerBidController extends Controller
{
    private function getSeller()
    {
        return Seller::where(
            'user_id',
            Auth::id()
        )->firstOrFail();
    }

    /**
     * Seller bids
     */
    public function index()
    {
        $seller = $this->getSeller();

        $bids = Bid::with([
            'listing',
            'user',
        ])
        ->where('seller_id', $seller->id)
        ->latest()
        ->paginate(10);

        return view(
            'seller.bids.index',
            compact('bids')
        );
    }

    /**
     * View bid
     */
    public function show($id)
    {
        $seller = $this->getSeller();

        $bid = Bid::with([
            'listing',
            'user',
            'seller',
        ])
        ->where('seller_id', $seller->id)
        ->findOrFail($id);

        return view(
            'seller.bids.show',
            compact('bid')
        );
    }

    /**
     * Accept bid
     */
    public function accept($id)
    {
        $seller = $this->getSeller();

        $bid = Bid::where('seller_id', $seller->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::transaction(function () use ($bid) {

            $bid->update([
                'status' => 'accepted',
                'responded_at' => now(),
                'seller_response' => 'Bid accepted.',
            ]);

            /*
             * Other pending bids for the same listing
             * are rejected automatically.
             */
            Bid::where('listing_id', $bid->listing_id)
                ->where('id', '!=', $bid->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'responded_at' => now(),
                    'seller_response' =>
                        'Another bid was accepted for this listing.',
                ]);
        });

        return redirect()
            ->route('seller.bids.show', $bid->id)
            ->with(
                'success',
                'Bid accepted successfully.'
            );
    }

    /**
     * Reject bid
     */
    public function reject(Request $request, $id)
    {
        $seller = $this->getSeller();

        $bid = Bid::where('seller_id', $seller->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $validated = $request->validate([
            'seller_response' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $bid->update([
            'status' => 'rejected',
            'seller_response' =>
                $validated['seller_response']
                ?? 'Bid rejected.',
            'responded_at' => now(),
        ]);

        return redirect()
            ->route('seller.bids.show', $bid->id)
            ->with(
                'success',
                'Bid rejected successfully.'
            );
    }
}