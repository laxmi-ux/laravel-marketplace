<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use App\Helpers\AdminNotificationHelper;
use App\Helpers\BuyerNotificationHelper;
use App\Helpers\SellerNotificationHelper;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | BUYER - ORDERS
        |--------------------------------------------------------------------------
        */

    public function buyerIndex()
    {
        $orders = Order::with([
            'seller',
            'quotation',
            'requirement',
            'listing',
            'payments',
        ])
            ->where('buyer_id', Auth::id())
            ->whereIn('category', [
                'product',
                'products',
                'service',
                'services',
                'project',
                'projects',
                'property',
                'properties',
                'land',
            ])
            ->latest('ordered_at')
            ->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    /*
        |--------------------------------------------------------------------------
        | BUYER - ORDER DETAILS
        |--------------------------------------------------------------------------
        */

    public function buyerShow($id)
{
    $order = Order::with([
        'seller',
        'quotation',
        'requirement',
        'listing',
        'payments',
    ])
        ->where('buyer_id', Auth::id())
        ->where('id', $id)
        ->whereIn('category', [
            'product',
            'products',
            'service',
            'services',
            'project',
            'projects',
            'property',
            'properties',
            'land',
        ])
        ->firstOrFail();

    return view('buyer.orders.show', compact('order'));
}

    /*
        |--------------------------------------------------------------------------
        | BUYER - COMPLETE ORDER
        |--------------------------------------------------------------------------
        */


 public function buyerComplete($id)
{
    $order = Order::where('buyer_id', Auth::id())
        ->where('id', $id)
        ->whereIn('category', ['product', 'products'])
        ->firstOrFail();

    if ($order->order_status !== 'delivered') {
        return back()->with(
            'error',
            'Only delivered product orders can be completed.'
        );
    }

    if (!$this->hasVerifiedPayment($order)) {
        return back()->with(
            'error',
            'This order cannot be completed until payment is verified.'
        );
    }

    $order->update([
        'order_status' => 'completed',
        'completed_at' => now(),
    ]);

    SellerNotificationHelper::send(
        $order->seller_id,
        'Order Completed',
        'Order ' . $order->order_number .
            ' has been marked as completed by the buyer.',
        'order',
        route('seller.orders.show', $order->id)
    );

    AdminNotificationHelper::send(
        'Order Completed',
        'Order ' . $order->order_number .
            ' has been marked as completed by the buyer.',
        'order',
        route('admin.orders.show', $order->id)
    );

    return back()->with(
        'success',
        'Product order completed successfully.'
    );
}
    /*
        |--------------------------------------------------------------------------
        | SELLER - GET SELLER
        |--------------------------------------------------------------------------
        */

    private function getSeller(): Seller
    {
        return Seller::where(
            'user_id',
            Auth::id()
        )->firstOrFail();
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - ORDERS
        |--------------------------------------------------------------------------
        */

    /*
    |--------------------------------------------------------------------------
    | SELLER - ORDERS
    |--------------------------------------------------------------------------
    */

    public function sellerIndex()
    {
        $seller = $this->getSeller();

        $orders = Order::with([
            'buyer',
            'quotation',
            'requirement',
            'listing',
        ])
            ->where('seller_id', $seller->id)
            ->whereIn('category', [
                'product',
                'products',
                'service',
                'services',
                'project',
                'projects',
            ])
            ->latest('ordered_at')
            ->paginate(10);

        return view('seller.orders.index', compact('orders', 'seller'));
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - ORDER DETAILS
        |--------------------------------------------------------------------------
        */

    public function sellerShow($id)
    {
        $seller = $this->getSeller();

        $order = Order::with([
            'buyer',
            'quotation',
            'requirement',
            'listing',
        ])
            ->where('seller_id', $seller->id)
            ->where('id', $id)
            ->whereIn('category', [
                'product',
                'products',
                'service',
                'services',
                'project',
                'projects',
            ])
            ->firstOrFail();

        return view('seller.orders.show', compact('order', 'seller'));
    }
    /*
        |--------------------------------------------------------------------------
        | SELLER - ACCEPT ORDER
        |--------------------------------------------------------------------------
        */

    public function sellerAccept($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($order->order_status !== 'pending') {
            return back()->with(
                'error',
                'This order can no longer be accepted.'
            );
        }

        $order->update([
            'order_status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        BuyerNotificationHelper::send(
            $order->buyer_id,
            'Order Confirmed',
            'Your order ' . $order->order_number .
                ' has been confirmed by the seller.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            'Order Confirmed',
            'Order ' . $order->order_number .
                ' has been confirmed by the seller.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            'Order confirmed successfully.'
        );
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - REJECT ORDER
        |--------------------------------------------------------------------------
        */

    public function sellerReject($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($order->order_status !== 'pending') {
            return back()->with(
                'error',
                'This order can no longer be rejected.'
            );
        }

        $order->update([
            'order_status' => 'rejected',
            'cancelled_at' => now(),
        ]);

        BuyerNotificationHelper::send(
            $order->buyer_id,
            'Order Rejected',
            'Your order ' . $order->order_number .
                ' has been rejected by the seller.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            'Order Rejected',
            'Order ' . $order->order_number .
                ' has been rejected by the seller.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            'Order rejected successfully.'
        );
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - PROCESS PRODUCT ORDER
        |--------------------------------------------------------------------------
        */

    public function sellerProcess($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category = $this->normalizeCategory($order->category);

        if ($category !== 'products') {
            return back()->with(
                'error',
                'Processing is only available for Product orders.'
            );
        }

        if ($order->order_status !== 'confirmed') {
            return back()->with(
                'error',
                'Only confirmed product orders can be moved to processing.'
            );
        }


        if (!$this->hasVerifiedPayment($order)) {
            return back()->with(
                'error',
                'Payment must be verified before processing this order.'
            );
        }


        $order->update([
            'order_status'  => 'processing',
            'processing_at' => now(),
        ]);

        BuyerNotificationHelper::send(
            $order->buyer_id,
            'Order Processing',
            'Your product order ' . $order->order_number .
                ' is now being processed.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            'Order Processing',
            'Product order ' . $order->order_number .
                ' is now being processed by the seller.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            'Product order moved to processing successfully.'
        );
    }

    private function normalizeCategory(?string $category): string
    {
        return match (strtolower(trim((string) $category))) {

            'product',
            'products' => 'products',

            'service',
            'services' => 'services',

            'project',
            'projects' => 'projects',

            'property',
            'properties' => 'properties',

            'land' => 'land',

            default => strtolower(trim((string) $category)),
        };
    }



    private function hasVerifiedPayment(Order $order): bool
    {
        return $order->payment_status === 'paid'
            || $order->payments()->where('status', 'paid')->exists();
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - START SERVICE / PROJECT
        |--------------------------------------------------------------------------
        */

    public function sellerStartWork($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category = strtolower(trim((string) $order->category));

        if (!in_array($category, ['services', 'projects'], true)) {
            return back()->with(
                'error',
                'This action is only available for Services and Projects.'
            );
        }

        if ($order->order_status !== 'confirmed') {
            return back()->with(
                'error',
                'Only confirmed orders can be started.'
            );
        }


        if (!$this->hasVerifiedPayment($order)) {
            return back()->with(
                'error',
                'Payment must be verified before starting this work.'
            );
        }


        $order->update([
            'order_status' => 'processing',
            'processing_at' => now(),
        ]);

        $isService = $category === 'services';

        BuyerNotificationHelper::send(
            $order->buyer_id,
            $isService ? 'Service Started' : 'Project Started',
            $isService
                ? 'Your service order ' . $order->order_number .
                ' has been started by the seller.'
                : 'Your project order ' . $order->order_number .
                ' has been started by the seller.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            $isService ? 'Service Started' : 'Project Started',
            $isService
                ? 'Service order ' . $order->order_number .
                ' has been started by the seller.'
                : 'Project order ' . $order->order_number .
                ' has been started by the seller.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            $isService
                ? 'Service has been started successfully.'
                : 'Project has been started successfully.'
        );
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - COMPLETE SERVICE / PROJECT
        |--------------------------------------------------------------------------
        */

    public function sellerCompleteWork($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category = strtolower(trim((string) $order->category));

        if (!in_array($category, ['services', 'projects'], true)) {
            return back()->with(
                'error',
                'This action is only available for Services and Projects.'
            );
        }

        if ($order->order_status !== 'processing') {
            return back()->with(
                'error',
                'Only active Services or Projects can be completed.'
            );
        }

        $order->update([
            'order_status' => 'completed',
            'completed_at' => now(),
        ]);

        $isService = $category === 'services';

        BuyerNotificationHelper::send(
            $order->buyer_id,
            $isService ? 'Service Completed' : 'Project Completed',
            $isService
                ? 'Your service order ' . $order->order_number .
                ' has been completed by the seller.'
                : 'Your project order ' . $order->order_number .
                ' has been completed by the seller.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            $isService ? 'Service Completed' : 'Project Completed',
            $isService
                ? 'Service order ' . $order->order_number .
                ' has been completed.'
                : 'Project order ' . $order->order_number .
                ' has been completed.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            $isService
                ? 'Service completed successfully.'
                : 'Project completed successfully.'
        );
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - START NEGOTIATION
        |--------------------------------------------------------------------------
        */

    public function sellerStartNegotiation($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category = strtolower(trim((string) $order->category));

        if (!in_array($category, ['properties', 'property', 'land'], true)) {
            return back()->with(
                'error',
                'Negotiation is only available for Property and Land transactions.'
            );
        }

        if ($order->order_status !== 'confirmed') {
            return back()->with(
                'error',
                'Only confirmed transactions can enter negotiation.'
            );
        }

        $order->update([
            'order_status' => 'negotiation',
            'negotiation_at' => now(),
        ]);

        BuyerNotificationHelper::send(
            $order->buyer_id,
            'Transaction Negotiation Started',
            'Your ' . $order->category .
                ' transaction ' . $order->order_number .
                ' has entered negotiation.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            'Transaction Negotiation Started',
            $order->category .
                ' transaction ' . $order->order_number .
                ' has entered negotiation.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            'Transaction moved to negotiation.'
        );
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - MOVE TO DOCUMENTATION
        |--------------------------------------------------------------------------
        */

    public function sellerDocumentation($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category = strtolower(trim((string) $order->category));

        if (!in_array($category, ['properties', 'property', 'land'], true)) {
            return back()->with(
                'error',
                'Documentation is only available for Property and Land transactions.'
            );
        }

        if ($order->order_status !== 'negotiation') {
            return back()->with(
                'error',
                'Only transactions in negotiation can move to documentation.'
            );
        }

        $order->update([
            'order_status' => 'documentation',
            'documentation_at' => now(),
        ]);

        BuyerNotificationHelper::send(
            $order->buyer_id,
            'Documentation Started',
            'Documentation for your ' . $order->category .
                ' transaction ' . $order->order_number .
                ' has started.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            'Documentation Started',
            'Documentation has started for ' . $order->category .
                ' transaction ' . $order->order_number . '.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            'Transaction moved to documentation.'
        );
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - COMPLETE PROPERTY / LAND TRANSACTION
        |--------------------------------------------------------------------------
        */

    public function sellerCompleteTransaction($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category = strtolower(trim((string) $order->category));

        if (!in_array($category, ['properties', 'property', 'land'], true)) {
            return back()->with(
                'error',
                'This action is only available for Property and Land transactions.'
            );
        }

        if ($order->order_status !== 'documentation') {
            return back()->with(
                'error',
                'Only transactions in documentation can be completed.'
            );
        }

        $order->update([
            'order_status' => 'completed',
            'completed_at' => now(),
        ]);

        BuyerNotificationHelper::send(
            $order->buyer_id,
            'Transaction Completed',
            'Your ' . $order->category .
                ' transaction ' . $order->order_number .
                ' has been completed.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            'Transaction Completed',
            $order->category .
                ' transaction ' . $order->order_number .
                ' has been completed.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            'Transaction completed successfully.'
        );
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - SHIP PRODUCT ORDER
        |--------------------------------------------------------------------------
        */

    public function sellerShip($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category = strtolower(trim((string) $order->category));

        if (!in_array($category, ['product', 'products'], true)) {
            return back()->with(
                'error',
                'Shipping is only available for Product orders.'
            );
        }

        if ($order->order_status !== 'processing') {
            return back()->with(
                'error',
                'Only processing product orders can be shipped.'
            );
        }

        $order->update([
            'order_status' => 'shipped',
            'shipped_at'   => now(),
        ]);

        BuyerNotificationHelper::send(
            $order->buyer_id,
            'Order Shipped',
            'Your product order ' . $order->order_number .
                ' has been shipped.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            'Order Shipped',
            'Product order ' . $order->order_number .
                ' has been shipped by the seller.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            'Product order marked as shipped.'
        );
    }

    /*
        |--------------------------------------------------------------------------
        | SELLER - DELIVER PRODUCT ORDER
        |--------------------------------------------------------------------------
        */

    public function sellerDeliver($id)
    {
        $seller = $this->getSeller();

        $order = Order::where('seller_id', $seller->id)
            ->where('id', $id)
            ->firstOrFail();

        $category = strtolower(trim((string) $order->category));

        if (!in_array($category, ['product', 'products'], true)) {
            return back()->with(
                'error',
                'Delivery is only available for Product orders.'
            );
        }

        if ($order->order_status !== 'shipped') {
            return back()->with(
                'error',
                'Only shipped product orders can be marked as delivered.'
            );
        }

        $order->update([
            'order_status' => 'delivered',
            'delivered_at' => now(),
        ]);

        BuyerNotificationHelper::send(
            $order->buyer_id,
            'Order Delivered',
            'Your product order ' . $order->order_number .
                ' has been marked as delivered.',
            'order',
            route('buyer.orders.show', $order->id)
        );

        AdminNotificationHelper::send(
            'Order Delivered',
            'Product order ' . $order->order_number .
                ' has been marked as delivered by the seller.',
            'order',
            route('admin.orders.show', $order->id)
        );

        return back()->with(
            'success',
            'Product order marked as delivered.'
        );
    }



    public function updatePayment(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_method' => [
                'required',
                Rule::in(['Cash', 'UPI', 'Bank Transfer']),
            ],
            'status' => [
                'required',
                Rule::in(['pending', 'paid']),
            ],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'payment_date' => [
                'nullable',
                'date',
                'before_or_equal:now',
                Rule::requiredIf($request->input('status') === 'paid'),
            ],
        ]);

        // A seller account may have more than one seller record.
        $sellerIds = Seller::where('user_id', Auth::id())->pluck('id');

        $order = Order::whereIn('seller_id', $sellerIds)
            ->findOrFail($id);

        $payment = Payment::firstOrNew([
            'order_id' => $order->id,
        ]);

        $payment->buyer_id = $order->buyer_id;
        $payment->seller_id = $order->seller_id;
        $payment->amount = $order->total_amount;
        $payment->currency = 'INR';
        $payment->payment_method = $validated['payment_method'];
        $payment->transaction_reference =
            $validated['transaction_reference'] ?? null;

        $payment->status = $validated['status'];

        if ($validated['status'] === 'paid') {
            $payment->paid_at = $validated['payment_date'];
            $payment->notes = 'Manual payment received and recorded by seller.';
        } else {
            $payment->paid_at = null;
            $payment->notes = 'Payment is pending.';
        }

        $payment->save();

        // Keep the order payment status synchronized.
        $order->payment_status = $validated['status'];
        $order->save();

        return redirect()
            ->route('seller.orders.show', $order->id)
            ->with('success', 'Payment details updated successfully.');
    }
}
