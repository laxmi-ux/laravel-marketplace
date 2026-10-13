<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Helpers\AdminNotificationHelper;
use App\Helpers\SellerNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Check Whether Order Is Property/Land Transaction
    |--------------------------------------------------------------------------
    */

    private function isTransaction(Order $order): bool
    {
        return in_array(
            strtolower(trim((string) $order->category)),
            ['property', 'properties', 'land'],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Redirect Buyer Back To Correct Details Page
    |--------------------------------------------------------------------------
    */

    private function buyerShowRoute(Order $order): string
    {
        if ($this->isTransaction($order)) {
            return route('buyer.transactions.show', $order->id);
        }

        return route('buyer.orders.show', $order->id);
    }


    /*
    |--------------------------------------------------------------------------
    | Show Review Form
    |--------------------------------------------------------------------------
    */

    public function create($orderId)
    {
        $order = Order::with([
            'seller',
            'listing',
            'review',
        ])
            ->where('id', $orderId)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Only Completed Orders / Transactions Can Be Reviewed
        |--------------------------------------------------------------------------
        */

        if (strtolower($order->order_status) !== 'completed') {
            return redirect($this->buyerShowRoute($order))
                ->with(
                    'error',
                    'You can review only completed orders or transactions.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Review
        |--------------------------------------------------------------------------
        */

        if ($order->review) {
            return redirect($this->buyerShowRoute($order))
                ->with(
                    'error',
                    'You have already submitted a review for this order or transaction.'
                );
        }


        return view(
            'buyer.reviews.create',
            compact('order')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Review
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $orderId)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Review
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'review' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Buyer's Order / Transaction
        |--------------------------------------------------------------------------
        */

        $order = Order::with([
            'seller',
            'listing',
            'review',
        ])
            ->where('id', $orderId)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Only Completed Orders / Transactions Can Be Reviewed
        |--------------------------------------------------------------------------
        */

        if (strtolower($order->order_status) !== 'completed') {
            return redirect($this->buyerShowRoute($order))
                ->with(
                    'error',
                    'You can review only completed orders or transactions.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Review
        |--------------------------------------------------------------------------
        */

        if ($order->review) {
            return redirect($this->buyerShowRoute($order))
                ->with(
                    'error',
                    'You have already submitted a review for this order or transaction.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Review
        |--------------------------------------------------------------------------
        */

        $review = Review::create([
            'buyer_id'   => Auth::id(),
            'seller_id'  => $order->seller_id,
            'listing_id' => $order->listing_id,
            'order_id'   => $order->id,
            'rating'     => $validated['rating'],
            'review'     => $validated['review'],
            'status'     => 'pending',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Listing Name
        |--------------------------------------------------------------------------
        */

        $listingName = $order->listing->title
            ?? $order->listing->name
            ?? 'your listing';


        /*
        |--------------------------------------------------------------------------
        | Notify All Admins
        |--------------------------------------------------------------------------
        */

        AdminNotificationHelper::send(
            'New Review Submitted',
            'A new review has been submitted for "' .
                $listingName .
                '" and is waiting for admin approval.',
            'review',
            route('admin.reviews.index')
        );


        /*
        |--------------------------------------------------------------------------
        | Notify Seller
        |--------------------------------------------------------------------------
        |
        | Seller review route is currently not available,
        | so URL is set to null.
        |
        */

        if ($order->seller_id) {
            SellerNotificationHelper::send(
                $order->seller_id,
                'New Review Received',
                'You have received a new review for "' .
                    $listingName .
                    '". The review is waiting for admin approval.',
                'review',
                null
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect After Submission
        |--------------------------------------------------------------------------
        */

        return redirect($this->buyerShowRoute($order))
            ->with(
                'success',
                'Your review has been submitted successfully and is waiting for approval.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Buyer Reviews
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $reviews = Review::with([
            'seller',
            'listing',
            'order',
        ])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view(
            'buyer.reviews.index',
            compact('reviews')
        );
    }
}