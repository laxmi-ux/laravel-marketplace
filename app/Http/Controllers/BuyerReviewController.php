<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Helpers\SellerNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerReviewController extends Controller
{
    /**
     * Show review form for a completed order.
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

        // Review is allowed only after order is completed
        if ($order->order_status !== 'completed') {
            return redirect()
                ->back()
                ->with('error', 'You can review an order only after it is completed.');
        }

        // Prevent duplicate review
        if ($order->review) {
            return redirect()
                ->back()
                ->with('error', 'You have already reviewed this order.');
        }

        return view('buyer.reviews.create', compact('order'));
    }

    /**
     * Store buyer review.
     */
    public function store(Request $request, $orderId)
    {
        $order = Order::with([
            'seller',
            'listing',
        ])
        ->where('id', $orderId)
        ->where('buyer_id', Auth::id())
        ->firstOrFail();

        // Only completed orders can be reviewed
        if ($order->order_status !== 'completed') {
            return redirect()
                ->back()
                ->with('error', 'You can review an order only after it is completed.');
        }

        // Prevent duplicate review
        if (Review::where('order_id', $order->id)
            ->where('buyer_id', Auth::id())
            ->exists()) {

            return redirect()
                ->route('buyer.orders.show', $order->id)
                ->with('error', 'You have already reviewed this order.');
        }

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'review' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $review = Review::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $order->seller_id,
            'listing_id' => $order->listing_id,
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Seller Notification
        |--------------------------------------------------------------------------
        */
        try {
            SellerNotificationHelper::send(
                $order->seller_id,
                'New Review Received',
                'A buyer has submitted a new review for your order.',
                'review'
            );
        } catch (\Throwable $e) {
            // Do not fail review submission if notification fails.
        }

        return redirect()
            ->route('buyer.orders.show', $order->id)
            ->with(
                'success',
                'Your review has been submitted successfully and is awaiting approval.'
            );
    }
}