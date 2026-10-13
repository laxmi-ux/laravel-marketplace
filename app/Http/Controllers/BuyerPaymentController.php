<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerPaymentController extends Controller
{
    /**
     * Display buyer payment history.
     */
    public function index()
    {
        $payments = Payment::with(['order', 'seller'])
            ->where('buyer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('buyer.payments.index', compact('payments'));
    }

    /**
     * Display a payment's details.
     */
    public function show($id)
    {
        $payment = Payment::with(['order', 'seller'])
            ->where('buyer_id', Auth::id())
            ->findOrFail($id);

        return view('buyer.payments.show', compact('payment'));
    }

    /**
     * Display checkout for an existing buyer order.
     */
    public function checkout($id)
    {
        $order = Order::where('buyer_id', Auth::id())
            ->whereNotIn('category', [
                'property',
                'properties',
                'land',
            ])
            ->findOrFail($id);

        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            [
                'buyer_id' => $order->buyer_id,
                'seller_id' => $order->seller_id,
                'amount' => $order->total_amount,
                'currency' => 'INR',
                'status' => 'pending',
                'notes' => 'Awaiting payment confirmation.',
            ]
        );

        // Keep payment amount aligned with the order.
        if ($payment->status !== 'paid') {
            $payment->amount = $order->total_amount;
            $payment->buyer_id = $order->buyer_id;
            $payment->seller_id = $order->seller_id;
            $payment->save();
        }

        return view('buyer.payments.checkout', compact(
            'order',
            'payment'
        ));
    }

    /**
     * Record a buyer's request to pay manually.
     *
     * This does NOT confirm that money was received.
     */
    public function submitManualPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:UPI,Bank Transfer',
            ],
            'transaction_reference' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $order = Order::where('buyer_id', Auth::id())
            ->whereNotIn('category', [
                'property',
                'properties',
                'land',
            ])
            ->findOrFail($id);

        $payment = Payment::where('order_id', $order->id)
            ->where('buyer_id', Auth::id())
            ->firstOrFail();

        if ($payment->status === 'paid') {
            return back()->with(
                'success',
                'This payment has already been confirmed.'
            );
        }

        $payment->payment_method = $validated['payment_method'];
        $payment->gateway = null;
        $payment->transaction_reference =
            $validated['transaction_reference'];
        $payment->status = 'pending';
        $payment->paid_at = null;
        $payment->notes =
            'Buyer submitted manual payment details. Awaiting seller/admin verification.';
        $payment->save();

        return redirect()
            ->route('buyer.payments.show', $payment->id)
            ->with(
                'success',
                'Payment details submitted. Your payment is awaiting verification.'
            );
    }
}
