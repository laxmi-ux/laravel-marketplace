<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Order;
use App\Models\Listing;
use App\Helpers\BuyerNotificationHelper;
use App\Helpers\SellerNotificationHelper;
use App\Helpers\AdminNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Payment;

class BuyerEnquiryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Buyer Enquiries
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $enquiries = Enquiry::with([
            'listing.categoryRelation',
            'seller',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view(
            'buyer.enquiries.index',
            compact('enquiries')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Listing Enquiry
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $listingId)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Enquiry
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'quantity' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Approved Listing
        |--------------------------------------------------------------------------
        */

        $listing = Listing::where('id', $listingId)
            ->where('status', 'approved')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Create Enquiry
        |--------------------------------------------------------------------------
        */

        $enquiry = Enquiry::create([
            'user_id' => Auth::id(),
            'listing_id' => $listing->id,
            'seller_id' => $listing->seller_id,

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,

            'quantity' => $request->quantity,
            'unit' => $request->unit,

            'message' => $request->message,

            'status' => 'new',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Seller Notification
        |--------------------------------------------------------------------------
        */

        if ($listing->seller_id) {

            SellerNotificationHelper::send(
                $listing->seller_id,

                'New Listing Enquiry',

                'You have received a new enquiry for "' .
                    $listing->title .
                    '".',

                'enquiry',

                route(
                    'seller.enquiries.show',
                    $enquiry->id
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Admin Notification
        |--------------------------------------------------------------------------
        */

        AdminNotificationHelper::send(
            'New Listing Enquiry',

            'A new enquiry has been submitted for "' .
                $listing->title .
                '".',

            'enquiry',
            null
        );
        /*
        |--------------------------------------------------------------------------
        | Buyer Notification
        |--------------------------------------------------------------------------
        */

        BuyerNotificationHelper::send(
            Auth::id(),

            'Enquiry Submitted',

            'Your enquiry for "' .
                $listing->title .
                '" has been submitted successfully.',

            'enquiry',

            route(
                'buyer.enquiries.show',
                $enquiry->id
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('listing.show', $listing->id)
            ->with(
                'success',
                'Your enquiry has been submitted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Enquiry
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $enquiry = Enquiry::with([
            'listing.categoryRelation',
            'seller',
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view(
            'buyer.enquiries.show',
            compact('enquiry')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Commercial Offer
    |--------------------------------------------------------------------------
    */

    public function offer($id)
    {
        $enquiry = Enquiry::with([
            'listing.categoryRelation',
            'seller',
            'user',
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view(
            'buyer.enquiries.offer',
            compact('enquiry')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accept Seller Offer
    |--------------------------------------------------------------------------
    */


    public function acceptOffer($id)
    {
        /*
    |--------------------------------------------------------------------------
    | Load Enquiry
    |--------------------------------------------------------------------------
    */

        $enquiry = Enquiry::with([
            'listing.categoryRelation',
            'seller',
            'user',
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);


        /*
    |--------------------------------------------------------------------------
    | Make Sure Commercial Offer Exists
    |--------------------------------------------------------------------------
    |
    | This is a Listing Enquiry Commercial Offer.
    | It is stored directly in the enquiries table.
    |
    */

        if (
            empty($enquiry->offer_sent_at) ||
            $enquiry->status !== 'offer_sent'
        ) {
            return back()
                ->with(
                    'error',
                    'No active commercial offer is available for this enquiry.'
                );
        }


        /*
    |--------------------------------------------------------------------------
    | Prevent Duplicate Order / Transaction
    |--------------------------------------------------------------------------
    */

        $existingOrder = Order::where(
            'enquiry_id',
            $enquiry->id
        )->first();

        if ($existingOrder) {

            $existingCategory = strtolower(
                trim((string) $existingOrder->category)
            );

            $isExistingTransaction = in_array(
                $existingCategory,
                [
                    'property',
                    'properties',
                    'land',
                ],
                true
            );

            if ($isExistingTransaction) {

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

            return redirect()
                ->route(
                    'buyer.orders.show',
                    $existingOrder->id
                )
                ->with(
                    'success',
                    'An order has already been created for this enquiry.'
                );
        }


        /*
    |--------------------------------------------------------------------------
    | Get Category
    |--------------------------------------------------------------------------
    */

        $category = $enquiry->listing?->categoryRelation?->name
            ?? 'Other';

        $categoryKey = strtolower(
            trim((string) $category)
        );


        /*
    |--------------------------------------------------------------------------
    | Identify Property / Land Transaction
    |--------------------------------------------------------------------------
    */

        $isTransaction = in_array(
            $categoryKey,
            [
                'property',
                'properties',
                'land',
            ],
            true
        );


        /*
    |--------------------------------------------------------------------------
    | Normalize Category
    |--------------------------------------------------------------------------
    */

        if (
            $categoryKey === 'property' ||
            $categoryKey === 'properties'
        ) {

            $orderCategory = 'properties';
        } elseif ($categoryKey === 'land') {

            $orderCategory = 'land';
        } elseif (
            $categoryKey === 'product' ||
            $categoryKey === 'products'
        ) {

            $orderCategory = 'products';
        } elseif (
            $categoryKey === 'service' ||
            $categoryKey === 'services'
        ) {

            $orderCategory = 'services';
        } elseif (
            $categoryKey === 'project' ||
            $categoryKey === 'projects'
        ) {

            $orderCategory = 'projects';
        } else {

            $orderCategory = $categoryKey;
        }


        /*
    |--------------------------------------------------------------------------
    | Get Commercial Offer Pricing
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | These values come from the enquiries table.
    |
    | The RFQ Quotation model is NOT used here.
    |
    */

        $quantity = (float) (
            $enquiry->quantity ?? 1
        );

        $unitPrice = (float) (
            $enquiry->unit_price ?? 0
        );

        $deliveryCharges = (float) (
            $enquiry->delivery_charges ?? 0
        );

        $otherCharges = (float) (
            $enquiry->other_charges ?? 0
        );

        $tax = (float) (
            $enquiry->tax ?? 0
        );


        /*
    |--------------------------------------------------------------------------
    | Calculate Subtotal
    |--------------------------------------------------------------------------
    |
    | Products / Services:
    | quantity × unit price
    |
    | Properties / Land / Projects:
    | complete quoted price
    |
    */

        if (
            $categoryKey === 'product' ||
            $categoryKey === 'products' ||
            $categoryKey === 'service' ||
            $categoryKey === 'services'
        ) {

            $subtotal =
                $quantity *
                $unitPrice;
        } else {

            $quantity = 1;

            $subtotal =
                $unitPrice;
        }


        /*
    |--------------------------------------------------------------------------
    | Get Commercial Offer Total
    |--------------------------------------------------------------------------
    |
    | Prefer the total saved by SellerEnquiryController.
    | If it is unavailable, calculate it again.
    |
    */

        $totalAmount = (float) (
            $enquiry->total_amount ?? 0
        );

        if ($totalAmount <= 0) {

            $totalAmount =
                $subtotal
                + $deliveryCharges
                + $otherCharges
                + $tax;
        }


        /*
    |--------------------------------------------------------------------------
    | Generate Transaction / Order Number
    |--------------------------------------------------------------------------
    */

        if ($isTransaction) {

            /*
        |--------------------------------------------------------------------------
        | Transaction Number
        | Example: TRN-001
        |--------------------------------------------------------------------------
        */

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

            $orderNumber =
                'TRN-' .
                str_pad(
                    $nextNumber,
                    3,
                    '0',
                    STR_PAD_LEFT
                );
        } else {

            /*
        |--------------------------------------------------------------------------
        | Normal Order Number
        | Example: ORD-20261006-A12BC
        |--------------------------------------------------------------------------
        */

            do {

                $orderNumber =
                    'ORD-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(
                        Str::random(5)
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
    | Create Order / Transaction
    |--------------------------------------------------------------------------
    */

        $order = Order::create([

            /*
        |--------------------------------------------------------------------------
        | Basic Information
        |--------------------------------------------------------------------------
        */

            'order_number' =>
            $orderNumber,

            /*
        | No quotation_id here.
        | This order came from a Listing Enquiry.
        */
            'quotation_id' =>
            null,

            'enquiry_id' =>
            $enquiry->id,

            'buyer_id' =>
            $enquiry->user_id,

            'seller_id' =>
            $enquiry->seller_id,

            'listing_id' =>
            $enquiry->listing_id,

            'category' =>
            $orderCategory,


            /*
        |--------------------------------------------------------------------------
        | Product / Listing Information
        |--------------------------------------------------------------------------
        */

            'product_name' =>
            $enquiry->listing?->title,

            'quantity' =>
            $quantity,

            'unit' =>
            $enquiry->unit,

            'unit_price' =>
            $unitPrice,


            /*
        |--------------------------------------------------------------------------
        | Pricing
        |--------------------------------------------------------------------------
        */

            'subtotal' =>
            $subtotal,

            'delivery_charges' =>
            $deliveryCharges,

            'other_charges' =>
            $otherCharges,

            'tax' =>
            $tax,

            'total_amount' =>
            $totalAmount,


            /*
        |--------------------------------------------------------------------------
        | Delivery / Location
        |--------------------------------------------------------------------------
        */

            'delivery_location' =>
            $enquiry->delivery_location,


            /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

            'order_status' =>
            'pending',

            'payment_status' =>
            'pending',


            /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

            'ordered_at' =>
            now(),



            
            /*
        |--------------------------------------------------------------------------
        | Notes
        |--------------------------------------------------------------------------
        */

            'notes' =>
            $enquiry->offer_notes
                ?? $enquiry->message,
        ]);



        /*
|--------------------------------------------------------------------------
| Create Pending Payment Record
|--------------------------------------------------------------------------
| This records the amount due. It does NOT mean payment was received.
*/

        Payment::firstOrCreate(
            [
                'order_id' => $order->id,
            ],
            [
                'buyer_id' => $order->buyer_id,
                'seller_id' => $order->seller_id,
                'amount' => $order->total_amount,
                'currency' => 'INR',
                'payment_method' => null,
                'gateway' => null,
                'gateway_order_id' => null,
                'gateway_payment_id' => null,
                'transaction_reference' => null,
                'status' => 'pending',
                'paid_at' => null,
                'failure_reason' => null,
                'notes' => 'Awaiting payment confirmation.',
            ]
        );


        /*
    |--------------------------------------------------------------------------
    | Update Enquiry
    |--------------------------------------------------------------------------
    |
    | The buyer has accepted the commercial offer.
    |
    */

        $enquiry->update([
            'status' =>
            'offer_accepted',

            'offer_accepted_at' =>
            now(),

            'offer_rejected_at' =>
            null,
        ]);


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

            $enquiry->seller_id,

            $isTransaction
                ? 'New Transaction Received'
                : 'New Order Received',

            $isTransaction

                ? 'A new property/land transaction ' .
                $order->order_number .
                ' has been created from a listing enquiry. Amount: ₹' .
                number_format(
                    (float) $order->total_amount,
                    2
                ) .
                '.'

                : 'A new order ' .
                $order->order_number .
                ' has been created from a listing enquiry. Amount: ₹' .
                number_format(
                    (float) $order->total_amount,
                    2
                ) .
                '.',

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

            $enquiry->user_id,

            $isTransaction
                ? 'Transaction Created'
                : 'Order Created',

            $isTransaction

                ? 'Your transaction ' .
                $order->order_number .
                ' has been created successfully for ₹' .
                number_format(
                    (float) $order->total_amount,
                    2
                ) .
                '.'

                : 'Your order ' .
                $order->order_number .
                ' has been created successfully for ₹' .
                number_format(
                    (float) $order->total_amount,
                    2
                ) .
                '.',

            $isTransaction
                ? 'transaction'
                : 'order',

            $buyerRoute
        );


        /*
    |--------------------------------------------------------------------------
    | Admin Notification
    |--------------------------------------------------------------------------
    */

        $adminRoute = $isTransaction

            ? route(
                'admin.transactions.show',
                $order->id
            )

            : route(
                'admin.orders.show',
                $order->id
            );

        AdminNotificationHelper::send(

            $isTransaction
                ? 'New Transaction Created'
                : 'New Order Created',

            $isTransaction

                ? 'Transaction ' .
                $order->order_number .
                ' has been created from a property/land listing enquiry. Transaction amount: ₹' .
                number_format(
                    (float) $order->total_amount,
                    2
                ) .
                '.'

                : 'Order ' .
                $order->order_number .
                ' has been created from a listing enquiry. Order amount: ₹' .
                number_format(
                    (float) $order->total_amount,
                    2
                ) .
                '.',

            $isTransaction
                ? 'transaction'
                : 'order',

            $adminRoute
        );


        /*
    |--------------------------------------------------------------------------
    | Redirect
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
                    'Listing enquiry accepted and transaction created successfully. Transaction amount: ₹' .
                        number_format(
                            (float) $order->total_amount,
                            2
                        ) .
                        '.'
                );
        }

        return redirect()
            ->route(
                'buyer.orders.show',
                $order->id
            )
            ->with(
                'success',
                'Listing enquiry accepted and order created successfully. Order amount: ₹' .
                    number_format(
                        (float) $order->total_amount,
                        2
                    ) .
                    '.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Reject Offer
    |--------------------------------------------------------------------------
    */

    public function rejectOffer($id)
    {
        $enquiry = Enquiry::where(
            'user_id',
            Auth::id()
        )->findOrFail($id);

        $enquiry->update([
            'status' => 'closed',
        ]);

        if ($enquiry->seller_id) {

            SellerNotificationHelper::send(

                $enquiry->seller_id,

                'Enquiry Rejected',

                'The buyer has rejected the enquiry/offer.',

                'enquiry',

                route(
                    'seller.enquiries.show',
                    $enquiry->id
                )
            );
        }

        return redirect()
            ->route('buyer.enquiries.index')
            ->with(
                'success',
                'Enquiry rejected successfully.'
            );
    }
}
