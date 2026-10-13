<?php

namespace App\Http\Controllers;

use App\Helpers\AdminNotificationHelper;
use App\Helpers\BuyerNotificationHelper;
use App\Models\Payment;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerPaymentController extends Controller
{
    /**
     * Display payments belonging to the logged-in seller.
     */
    public function index()
    {
        $sellerIds = Seller::where('user_id', Auth::id())
            ->pluck('id');

        $payments = Payment::with([
            'order.listing.categoryRelation',
            'buyer',
        ])
            ->whereIn('seller_id', $sellerIds)
            ->latest()
            ->paginate(10);

        return view('seller.payments.index', compact('payments'));
    }

    /**
     * Display payment details.
     */
    public function show($id)
    {
        $sellerIds = Seller::where('user_id', Auth::id())
            ->pluck('id');

        $payment = Payment::with([
            'order.listing.categoryRelation',
            'buyer',
            'seller',
        ])
            ->whereIn('seller_id', $sellerIds)
            ->findOrFail($id);

        return view('seller.payments.show', compact('payment'));
    }

    /**
     * Verify a manual payment after confirming actual receipt.
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'payment_received' => ['required', 'accepted'],
        ], [
            'payment_received.required' =>
                'Please confirm that the payment was received.',
            'payment_received.accepted' =>
                'You must confirm actual receipt before verifying payment.',
        ]);

        $sellerIds = Seller::where('user_id', Auth::id())
            ->pluck('id');

        $sellerUserId = Auth::id();

        // Update the payment and order atomically.
        $payment = DB::transaction(function () use (
            $id,
            $sellerIds,
            $sellerUserId
        ) {
            $payment = Payment::whereIn('seller_id', $sellerIds)
                ->lockForUpdate()
                ->findOrFail($id);

            if ($payment->status !== 'pending') {
                abort(422, 'Only pending payments can be verified.');
            }

            $order = $payment->order;

            if (!$order) {
                abort(404, 'The order linked to this payment was not found.');
            }

            $now = now();

            $payment->status = 'paid';
            $payment->paid_at = $now;
            $payment->failure_reason = null;

            $payment->notes = trim(
                ($payment->notes ? $payment->notes . "\n" : '') .
                'Manual payment verified by seller user ID ' .
                $sellerUserId . ' on ' . $now->toDateTimeString()
            );

            $payment->save();

            $order->payment_status = 'paid';

            $category = strtolower(trim(
                $order->listing->categoryRelation->name
                    ?? $order->listing->category
                    ?? ''
            ));

            // Automatically confirm only pending product orders.
            if (
                in_array($category, ['product', 'products'], true)
                && $order->order_status === 'pending'
            ) {
                $order->order_status = 'confirmed';
            }

            $order->save();

            return $payment->fresh(['order']);
        });

        // Send notifications only after the database transaction succeeds.
        $buyerId = $payment->buyer_id
            ?? $payment->order?->buyer_id;

        if ($buyerId) {
            BuyerNotificationHelper::send(
                (int) $buyerId,
                'Payment Verified',
                'Your payment for order #' .
                    ($payment->order?->order_number ?? $payment->order_id) .
                    ' has been verified by the seller.',
                'payment',
                route('buyer.orders.show', $payment->order_id)
            );
        }

        AdminNotificationHelper::send(
            'Payment Verified',
            'Payment #' . $payment->id .
                ' for order #' .
                ($payment->order?->order_number ?? $payment->order_id) .
                ' has been verified by the seller.',
            'payment',
            route('admin.payments.show', $payment->id)
        );

        return redirect()
            ->route('seller.payments.show', $payment->id)
            ->with(
                'success',
                'Payment verified successfully. The buyer and admin have been notified.'
            );
    }

    /**
     * Reject a pending payment.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'failure_reason' => ['required', 'string', 'max:1000'],
        ]);

        $sellerIds = Seller::where('user_id', Auth::id())
            ->pluck('id');

        $sellerUserId = Auth::id();

        $reason = trim($request->input('failure_reason'));

        // Update the payment and order atomically.
        $payment = DB::transaction(function () use (
            $id,
            $sellerIds,
            $sellerUserId,
            $reason
        ) {
            $payment = Payment::whereIn('seller_id', $sellerIds)
                ->lockForUpdate()
                ->findOrFail($id);

            if ($payment->status !== 'pending') {
                abort(422, 'Only pending payments can be rejected.');
            }

            $order = $payment->order;

            $payment->status = 'failed';
            $payment->failure_reason = $reason;

            $payment->notes = trim(
                ($payment->notes ? $payment->notes . "\n" : '') .
                'Payment rejected by seller user ID ' .
                $sellerUserId . ' on ' . now()->toDateTimeString()
            );

            $payment->save();

            // Rejected payments must not confirm the order.
            if ($order) {
                $order->payment_status = 'failed';
                $order->save();
            }

            return $payment->fresh(['order']);
        });

        // Notify the buyer after the rejection has been saved.
        $buyerId = $payment->buyer_id
            ?? $payment->order?->buyer_id;

        if ($buyerId) {
            BuyerNotificationHelper::send(
                (int) $buyerId,
                'Payment Rejected',
                'Your payment for order #' .
                    ($payment->order?->order_number ?? $payment->order_id) .
                    ' was rejected. Reason: ' . $reason,
                'payment',
                route('buyer.orders.show', $payment->order_id)
            );
        }

        // Admin is notified for monitoring; no approval action is provided.
        AdminNotificationHelper::send(
            'Payment Rejected',
            'Payment #' . $payment->id .
                ' for order #' .
                ($payment->order?->order_number ?? $payment->order_id) .
                ' was rejected by the seller. Reason: ' . $reason,
            'payment',
            route('admin.payments.show', $payment->id)
        );

        return redirect()
            ->route('seller.payments.show', $payment->id)
            ->with(
                'success',
                'Payment rejected successfully. The buyer and admin have been notified.'
            );
    }
}
