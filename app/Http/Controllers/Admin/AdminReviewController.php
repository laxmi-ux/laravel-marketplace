<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Helpers\SellerNotificationHelper;
use App\Notifications\MarketplaceNotification;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Review Management
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Review::with([
            'order',
            'buyer',
            'seller',
            'listing',
        ])->latest();

        /*
        |--------------------------------------------------------------------------
        | Filter By Review Status
        |--------------------------------------------------------------------------
        */

        if (in_array($request->status, [
            'pending',
            'approved',
            'rejected',
        ])) {
            $query->where('status', $request->status);
        }

        $reviews = $query
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Review Counts
        |--------------------------------------------------------------------------
        */

        $counts = [
            'all'      => Review::count(),
            'pending'  => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

        return view(
            'admin.reviews.index',
            compact('reviews', 'counts')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve / Reject Review
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, Review $review)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Status
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'status' => [
                'required',
                'in:approved,rejected',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Store Previous Status
        |--------------------------------------------------------------------------
        */

        $oldStatus = $review->status;

        /*
        |--------------------------------------------------------------------------
        | Update Review Status
        |--------------------------------------------------------------------------
        */

        $review->update([
            'status' => $validated['status'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Listing Name
        |--------------------------------------------------------------------------
        */

        $listingName = $review->listing->title
            ?? $review->listing->name
            ?? 'your listing';


        /*
        |--------------------------------------------------------------------------
        | APPROVED
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'approved'
            && $oldStatus !== 'approved'
        ) {

            /*
            |--------------------------------------------------------------------------
            | Notify Buyer
            |--------------------------------------------------------------------------
            */

            if ($review->buyer) {

                $review->buyer->notify(
                    new MarketplaceNotification(
                        'Review Approved',
                        'Your review for "' .
                            $listingName .
                            '" has been approved and published.',
                        'review',
                        route('buyer.reviews.index')
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Notify Seller
            |--------------------------------------------------------------------------
            |
            | seller.reviews.index route does not currently exist,
            | therefore URL is set to null.
            |
            */

            if ($review->seller_id) {

                SellerNotificationHelper::send(
                    $review->seller_id,
                    'Review Approved',
                    'The review received for "' .
                        $listingName .
                        '" has been approved and published.',
                    'review',
                    null
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | REJECTED
        |--------------------------------------------------------------------------
        */

        if (
            $validated['status'] === 'rejected'
            && $oldStatus !== 'rejected'
        ) {

            /*
            |--------------------------------------------------------------------------
            | Notify Buyer
            |--------------------------------------------------------------------------
            */

            if ($review->buyer) {

                $review->buyer->notify(
                    new MarketplaceNotification(
                        'Review Rejected',
                        'Your review for "' .
                            $listingName .
                            '" has been rejected by the administrator.',
                        'review',
                        route('buyer.reviews.index')
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Notify Seller
            |--------------------------------------------------------------------------
            |
            | seller.reviews.index route does not currently exist,
            | therefore URL is set to null.
            |
            */

            if ($review->seller_id) {

                SellerNotificationHelper::send(
                    $review->seller_id,
                    'Review Rejected',
                    'The review received for "' .
                        $listingName .
                        '" has been rejected by the administrator.',
                    'review',
                    null
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Admin Success Message
        |--------------------------------------------------------------------------
        */

        $message = $validated['status'] === 'approved'
            ? 'Review approved successfully.'
            : 'Review rejected successfully.';


        return redirect()
            ->route('admin.reviews.index', [
                'status' => $request->query('status'),
                'page' => $request->query('page'),
            ])
            ->with('success', $message);
    }
}