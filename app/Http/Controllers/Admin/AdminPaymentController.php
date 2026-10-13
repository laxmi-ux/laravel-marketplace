<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    /**
     * Display all payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['order', 'buyer', 'seller'])
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Display payment details.
     */
    public function show($id)
    {
        $payment = Payment::with(['order', 'buyer', 'seller'])
            ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Approve a manually submitted payment.
     */
    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            $payment = Payment::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if (strtolower((string) $payment->status) !== 'pending') {
                abort(422, 'Only pending payments can be approved.');
            }

            $order = $payment->order()
                ->lockForUpdate()
                ->first();

            if (!$order) {
                abort(422, 'The associated order could not be found.');
            }

            $payment->status = 'paid';
            $payment->paid_at = now();
            $payment->failure_reason = null;
            $payment->save();

            $order->payment_status = 'paid';
            $order->save();
        });

        return redirect()
            ->route('admin.payments.show', $id)
            ->with('success', 'Payment approved successfully.');
    }

    /**
     * Reject a manually submitted payment.
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'failure_reason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($id, $validated) {
            $payment = Payment::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if (strtolower((string) $payment->status) !== 'pending') {
                abort(422, 'Only pending payments can be rejected.');
            }

            $order = $payment->order()
                ->lockForUpdate()
                ->first();

            $payment->status = 'failed';
            $payment->failure_reason = $validated['failure_reason'];
            $payment->paid_at = null;
            $payment->save();

            if ($order) {
                $order->payment_status = 'failed';
                $order->save();
            }
        });

        return redirect()
            ->route('admin.payments.show', $id)
            ->with('success', 'Payment rejected successfully.');
    }
}
