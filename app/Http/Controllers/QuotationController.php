<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Rfq;
use App\Models\Seller;
use App\Helpers\SellerNotificationHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Helpers\BuyerNotificationHelper;

class QuotationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Logged-in Seller
    |--------------------------------------------------------------------------
    */

    private function getSeller(): Seller
    {
        return Seller::where('user_id', Auth::id())
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | Get Seller Quotation
    |--------------------------------------------------------------------------
    */

    private function getSellerQuotation($id): Quotation
    {
        $seller = $this->getSeller();

        return Quotation::with([
            'requirement.listing',
            'requirement.user',
            'seller',
            'buyer',
        ])
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | SELLER - QUOTATIONS
    |--------------------------------------------------------------------------
    */

    public function sellerIndex()
    {
        $seller = $this->getSeller();

        $quotations = Quotation::with([
            'requirement.listing',
            'requirement.user',
            'buyer',
            'rfq',
        ])
            ->where('seller_id', $seller->id)
            ->latest()
            ->paginate(10);

        return view(
            'seller.quotations.index',
            compact('quotations', 'seller')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SELLER - VIEW QUOTATION
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $quotation = $this->getSellerQuotation($id);

        return view(
            'seller.quotations.show',
            compact('quotation')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUYER - MY QUOTATIONS
    |--------------------------------------------------------------------------
    */

    public function buyerIndex()
    {
        $userId = Auth::id();

        $quotations = Quotation::with([
            'requirement.listing',
            'requirement.user',
            'seller',
            'buyer',
        ])
            ->where('buyer_id', $userId)
            ->latest()
            ->paginate(10);

        return view(
            'buyer.quotations.index',
            compact('quotations')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUYER - VIEW QUOTATION
    |--------------------------------------------------------------------------
    */

    public function buyerShow($id)
    {
        $userId = Auth::id();

        $quotation = Quotation::with([
            'seller',
            'buyer',
            'requirement',
            'requirement.listing',
            'requirement.user',
        ])
            ->where('id', $id)
            ->where('buyer_id', $userId)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Find RFQ
        |--------------------------------------------------------------------------
        |
        | Quotation does not have rfq_id.
        | RFQ is connected using:
        | requirement_id + seller_id
        |
        */

        $rfq = Rfq::where(
            'requirement_id',
            $quotation->requirement_id
        )
            ->where(
                'seller_id',
                $quotation->seller_id
            )
            ->latest()
            ->first();

        return view(
            'buyer.quotations.show',
            compact('quotation', 'rfq')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUYER - ACCEPT QUOTATION
    |--------------------------------------------------------------------------
    */

    public function accept($id)
    {
        $userId = Auth::id();

        $quotation = Quotation::with([
            'requirement',
            'requirement.listing',
            'seller',
        ])
            ->where('id', $id)
            ->where('buyer_id', $userId)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Check quotation status
        |--------------------------------------------------------------------------
        */

        if ($quotation->status !== 'sent') {

            return back()->with(
                'error',
                'This quotation can no longer be accepted.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check quotation expiry
        |--------------------------------------------------------------------------
        */

        if (
            $quotation->valid_until &&
            $quotation->valid_until->isPast()
        ) {

            $quotation->update([
                'status' => 'expired',
            ]);

            return back()->with(
                'error',
                'This quotation has expired.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Existing Order / Transaction
        |--------------------------------------------------------------------------
        */

        $existingOrder = Order::where(
            'quotation_id',
            $quotation->id
        )->first();

        if ($existingOrder) {

            $existingCategory = strtolower(
                trim((string) $existingOrder->category)
            );

            /*
            |--------------------------------------------------------------------------
            | Existing Property / Land Transaction
            |--------------------------------------------------------------------------
            */

            if (in_array($existingCategory, [
                'property',
                'properties',
                'land',
            ], true)) {

                return redirect()
                    ->route(
                        'buyer.transactions.show',
                        $existingOrder->id
                    )
                    ->with(
                        'success',
                        'This transaction has already been created.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Existing Product / Service / Project Order
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'buyer.orders.show',
                    $existingOrder->id
                )
                ->with(
                    'success',
                    'An order has already been created for this quotation.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Order + Accept Quotation
        |--------------------------------------------------------------------------
        */

        $order = DB::transaction(function () use ($quotation) {

            /*
            |--------------------------------------------------------------------------
            | Calculate Subtotal
            |--------------------------------------------------------------------------
            */

            $subtotal =
                (float) $quotation->total_amount
                - (float) ($quotation->delivery_charges ?? 0)
                - (float) ($quotation->other_charges ?? 0)
                - (float) ($quotation->tax ?? 0);

            $subtotal = max($subtotal, 0);


            /*
            |--------------------------------------------------------------------------
            | Generate Order / Transaction Number
            |--------------------------------------------------------------------------
            */

            $category = strtolower(
                trim((string) $quotation->category)
            );

            $isTransaction = in_array(
                $category,
                [
                    'property',
                    'properties',
                    'land',
                ],
                true
            );


            /*
            |--------------------------------------------------------------------------
            | Property / Land = TRN-001, TRN-002...
            |--------------------------------------------------------------------------
            */

            if ($isTransaction) {

                $lastTransaction = Order::whereIn(
                    'category',
                    [
                        'property',
                        'properties',
                        'land',
                    ]
                )
                    ->where(
                        'order_number',
                        'like',
                        'TRN-%'
                    )
                    ->orderByDesc('id')
                    ->first();


                if ($lastTransaction) {

                    $lastNumber = (int) str_replace(
                        'TRN-',
                        '',
                        $lastTransaction->order_number
                    );

                    $nextNumber = $lastNumber + 1;

                } else {

                    $nextNumber = 1;
                }


                $orderNumber = 'TRN-' .
                    str_pad(
                        $nextNumber,
                        3,
                        '0',
                        STR_PAD_LEFT
                    );

            } else {

                /*
                |--------------------------------------------------------------------------
                | Product / Service / Project = ORD-...
                |--------------------------------------------------------------------------
                */

                do {

                    $orderNumber =
                        'ORD-' .
                        now()->format('Ymd') .
                        '-' .
                        strtoupper(
                            substr(
                                uniqid(),
                                -6
                            )
                        );

                } while (
                    Order::where(
                        'order_number',
                        $orderNumber
                    )->exists()
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Create Order
            |--------------------------------------------------------------------------
            */

            $order = Order::create([

                'order_number' => $orderNumber,

                'quotation_id' => $quotation->id,

                'requirement_id' => $quotation->requirement_id,

                'buyer_id' => $quotation->buyer_id,

                'seller_id' => $quotation->seller_id,

                'listing_id' =>
                    $quotation->requirement?->listing_id,

                'category' => $quotation->category,


                /*
                |--------------------------------------------------------------------------
                | Product
                |--------------------------------------------------------------------------
                */

                'product_name' => $quotation->product_name,

                'quantity' => $quotation->quantity,

                'unit' => $quotation->unit,

                'unit_price' => $quotation->unit_price,


                /*
                |--------------------------------------------------------------------------
                | Land
                |--------------------------------------------------------------------------
                */

                'land_location' => $quotation->land_location,

                'land_area' => $quotation->land_area,

                'land_area_unit' =>
                    $quotation->land_area_unit,


                /*
                |--------------------------------------------------------------------------
                | Property
                |--------------------------------------------------------------------------
                */

                'property_type' =>
                    $quotation->property_type,

                'property_location' =>
                    $quotation->property_location,

                'property_area' =>
                    $quotation->property_area,

                'property_area_unit' =>
                    $quotation->property_area_unit,


                /*
                |--------------------------------------------------------------------------
                | Service
                |--------------------------------------------------------------------------
                */

                'service_name' =>
                    $quotation->service_name,

                'service_duration' =>
                    $quotation->service_duration,


                /*
                |--------------------------------------------------------------------------
                | Project
                |--------------------------------------------------------------------------
                */

                'project_name' =>
                    $quotation->project_name,

                'project_location' =>
                    $quotation->project_location,

                'project_timeline' =>
                    $quotation->project_timeline,


                /*
                |--------------------------------------------------------------------------
                | Pricing
                |--------------------------------------------------------------------------
                */

                'subtotal' => $subtotal,

                'delivery_charges' =>
                    $quotation->delivery_charges ?? 0,

                'other_charges' =>
                    $quotation->other_charges ?? 0,

                'tax' =>
                    $quotation->tax ?? 0,

                'total_amount' =>
                    $quotation->total_amount ?? 0,


                /*
                |--------------------------------------------------------------------------
                | Delivery
                |--------------------------------------------------------------------------
                */

                'delivery_location' =>
                    $quotation->requirement?->delivery_location,

                'delivery_time' =>
                    $quotation->delivery_time,


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                'payment_status' => 'pending',

                'order_status' => 'pending',

                'ordered_at' => now(),


                /*
                |--------------------------------------------------------------------------
                | Notes
                |--------------------------------------------------------------------------
                */

                'notes' => $quotation->notes,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update Quotation
            |--------------------------------------------------------------------------
            */

            $quotation->update([
                'status' => 'accepted',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Close Requirement
            |--------------------------------------------------------------------------
            */

            if ($quotation->requirement) {

                $quotation->requirement->update([
                    'status' => 'closed',
                ]);
            }


            return $order;
        });


        /*
        |--------------------------------------------------------------------------
        | Find RFQ
        |--------------------------------------------------------------------------
        */

        $rfq = Rfq::where(
            'requirement_id',
            $quotation->requirement_id
        )
            ->where(
                'seller_id',
                $quotation->seller_id
            )
            ->latest()
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Determine Order or Transaction
        |--------------------------------------------------------------------------
        */

        $category = strtolower(
            trim((string) $order->category)
        );

        $isTransaction = in_array(
            $category,
            [
                'property',
                'properties',
                'land',
            ],
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Seller Notification
        |--------------------------------------------------------------------------
        */

        $sellerRoute = $isTransaction
            ? route(
                'seller.transactions.show',
                $order->id
            )
            : route(
                'seller.orders.show',
                $order->id
            );


        SellerNotificationHelper::send(

            $quotation->seller_id,

            $isTransaction
                ? 'New Transaction Received'
                : 'New Order Received',

            $isTransaction
                ? 'Your quotation has been accepted and a new transaction '
                    . $order->order_number
                    . ' has been created.'
                : 'Your quotation has been accepted and a new order '
                    . $order->order_number
                    . ' has been created.',

            $isTransaction
                ? 'transaction'
                : 'order',

            $sellerRoute
        );


        /*
        |--------------------------------------------------------------------------
        | Buyer Notification
        |--------------------------------------------------------------------------
        */

        $buyerRoute = $isTransaction
            ? route(
                'buyer.transactions.show',
                $order->id
            )
            : route(
                'buyer.orders.show',
                $order->id
            );


        BuyerNotificationHelper::send(

            $quotation->buyer_id,

            $isTransaction
                ? 'Transaction Created'
                : 'Order Created',

            $isTransaction
                ? 'Your transaction '
                    . $order->order_number
                    . ' has been created successfully.'
                : 'Your order '
                    . $order->order_number
                    . ' has been created successfully.',

            $isTransaction
                ? 'transaction'
                : 'order',

            $buyerRoute
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect Buyer
        |--------------------------------------------------------------------------
        */

        if ($isTransaction) {

            return redirect()
                ->route(
                    'buyer.transactions.show',
                    $order->id
                )
                ->with(
                    'success',
                    'Quotation accepted and transaction created successfully.'
                );
        }


        return redirect()
            ->route(
                'buyer.orders.show',
                $order->id
            )
            ->with(
                'success',
                'Quotation accepted and order created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | BUYER - REJECT QUOTATION
    |--------------------------------------------------------------------------
    */

    public function reject($id)
    {
        $userId = Auth::id();

        $quotation = Quotation::with([
            'requirement',
            'seller',
        ])
            ->where('id', $id)
            ->where('buyer_id', $userId)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Check quotation status
        |--------------------------------------------------------------------------
        */

        if ($quotation->status !== 'sent') {

            return back()->with(
                'error',
                'This quotation can no longer be rejected.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Reject quotation
        |--------------------------------------------------------------------------
        */

        $quotation->update([
            'status' => 'rejected',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find RFQ
        |--------------------------------------------------------------------------
        */

        $rfq = Rfq::where(
            'requirement_id',
            $quotation->requirement_id
        )
            ->where(
                'seller_id',
                $quotation->seller_id
            )
            ->latest()
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Notify Seller
        |--------------------------------------------------------------------------
        */

        SellerNotificationHelper::send(

            $quotation->seller_id,

            'Quotation Rejected',

            'Your quotation for '
                . (
                    $quotation->requirement->name
                    ?? 'the buyer requirement'
                )
                . ' has been rejected by the buyer.',

            'quotation',

            $rfq
                ? route(
                    'seller.rfqs.show',
                    $rfq->id
                )
                : route(
                    'seller.quotations.show',
                    $quotation->id
                )
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'buyer.quotations.show',
                $quotation->id
            )
            ->with(
                'success',
                'Quotation rejected successfully.'
            );
    }
}