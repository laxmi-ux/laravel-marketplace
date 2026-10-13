<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Listing;
use App\Models\Seller;
use App\Helpers\SellerNotificationHelper;
use App\Helpers\BuyerNotificationHelper;
use App\Helpers\AdminNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerEnquiryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Logged-in Seller
    |--------------------------------------------------------------------------
    */

    private function getSeller(): Seller
    {
        /*
        |--------------------------------------------------------------------------
        | Make sure a seller user is authenticated
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            abort(403, 'You must be logged in as a seller.');
        }

        /*
        |--------------------------------------------------------------------------
        | Find seller using logged-in user
        |--------------------------------------------------------------------------
        */

        $seller = Seller::where('user_id', Auth::id())->first();

        if (!$seller) {
            abort(403, 'No seller profile is connected to this user account.');
        }

        return $seller;
    }


    /*
    |--------------------------------------------------------------------------
    | Store Listing Enquiry
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $listingId)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'digits:10',
            ],

            'quantity' => [
                'nullable',
                'numeric',
                'min:0.01',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'message' => [
                'required',
                'string',
                'max:3000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Listing
        |--------------------------------------------------------------------------
        */

        $listing = Listing::findOrFail($listingId);

        if (!$listing->seller_id) {
            return back()->with(
                'error',
                'This listing is not currently connected to a seller.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Enquiry
        |--------------------------------------------------------------------------
        */

        $enquiry = Enquiry::create([
            'user_id' => Auth::id(),
            'listing_id' => $listing->id,
            'seller_id' => $listing->seller_id,

            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],

            'quantity' => $validated['quantity'] ?? null,
            'unit' => $validated['unit'] ?? null,

            'message' => $validated['message'],

            'status' => 'new',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Listing Name
        |--------------------------------------------------------------------------
        */

        $listingName =
            $listing->title
            ?? $listing->name
            ?? $listing->product_name
            ?? 'your listing';

        /*
        |--------------------------------------------------------------------------
        | Seller Notification
        |--------------------------------------------------------------------------
        */

        SellerNotificationHelper::send(
            $listing->seller_id,
            'New Listing Enquiry',
            'You have received a new enquiry from '
                . $validated['name']
                . ' regarding '
                . $listingName
                . '.',
            'enquiry',
            route('seller.enquiries.show', $enquiry->id)
        );

        /*
        |--------------------------------------------------------------------------
        | Admin Notification
        |--------------------------------------------------------------------------
        */

        AdminNotificationHelper::send(
            'New Listing Enquiry',
            'A new enquiry has been submitted by '
                . $validated['name']
                . ' for '
                . $listingName
                . '.',
            'enquiry',
            route('admin.orders.index')
        );

        return back()->with(
            'success',
            'Your enquiry has been submitted successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Seller Enquiries
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $seller = $this->getSeller();

        $enquiries = Enquiry::with([
            'user',
            'listing',
            'seller',
        ])
            ->where('seller_id', $seller->id)
            ->latest()
            ->paginate(10);

        return view(
            'seller.enquiries.index',
            compact('enquiries', 'seller')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Enquiry
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $seller = $this->getSeller();

        /*
        |--------------------------------------------------------------------------
        | Only allow the enquiry's actual seller to view it
        |--------------------------------------------------------------------------
        */

        $enquiry = Enquiry::with([
            'user',
            'listing',
            'seller',
        ])
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        return view(
            'seller.enquiries.show',
            compact('enquiry', 'seller')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Respond Page
    |--------------------------------------------------------------------------
    */

    public function respond($id)
    {
        $seller = $this->getSeller();

        $enquiry = Enquiry::with([
            'user',
            'listing',
            'seller',
        ])
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        return view(
            'seller.enquiries.respond',
            compact('enquiry', 'seller')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Seller Response
    |--------------------------------------------------------------------------
    */

    public function storeResponse(Request $request, $id)
    {
        $seller = $this->getSeller();

        $enquiry = Enquiry::with([
            'user',
            'listing',
        ])
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        $validated = $request->validate([
            'response' => [
                'required',
                'string',
                'max:3000',
            ],
        ]);

        $enquiry->update([
            'seller_response' => $validated['response'],
            'responded_at' => now(),
            'status' => 'contacted',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Buyer Notification
        |--------------------------------------------------------------------------
        */

        if ($enquiry->user_id) {
            BuyerNotificationHelper::send(
                $enquiry->user_id,

                'Seller Responded',

                'The seller has responded to your enquiry regarding '
                    . ($enquiry->listing?->title ?? 'your listing enquiry')
                    . '.',

                'enquiry',

                route(
                    'buyer.enquiries.show',
                    $enquiry->id
                )
            );
        }

        return redirect()
            ->route(
                'seller.enquiries.show',
                $enquiry->id
            )
            ->with(
                'success',
                'Your response has been sent successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Commercial Offer Page
    |--------------------------------------------------------------------------
    */

    public function offer($id)
    {
        $seller = $this->getSeller();

        $enquiry = Enquiry::with([
            'user',
            'listing',
            'seller',
        ])
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        return view(
            'seller.enquiries.offer',
            compact('enquiry', 'seller')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Commercial Offer
    |--------------------------------------------------------------------------
    */

    public function storeOffer(Request $request, $id)
    {
        $seller = $this->getSeller();

        /*
        |--------------------------------------------------------------------------
        | Get Enquiry
        |--------------------------------------------------------------------------
        */

        $enquiry = Enquiry::with([
            'user',
            'listing',
        ])
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Category Detection
        |--------------------------------------------------------------------------
        |
        | 1 = Properties
        | 2 = Land
        | 3 = Products
        | 4 = Services
        | 5 = Projects
        |
        */

        $categoryId = (int) (
            $enquiry->listing?->category_id ?? 0
        );

        $categoryName = match ($categoryId) {
            1 => 'Properties',
            2 => 'Land',
            3 => 'Products',
            4 => 'Services',
            5 => 'Projects',
            default => 'Other',
        };

        $category = strtolower(trim($categoryName));

        $isProduct = str_contains($category, 'product');
        $isProperty = str_contains($category, 'propert');
        $isLand = str_contains($category, 'land');
        $isService = str_contains($category, 'service');
        $isProject = str_contains($category, 'project');

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'delivery_charges' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'other_charges' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'delivery_time' => [
                'nullable',
                'string',
                'max:255',
            ],

            'offer_valid_until' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'offer_notes' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalize Values
        |--------------------------------------------------------------------------
        */

        $quantity = (float) (
            $validated['quantity'] ?? 1
        );

        $unitPrice = (float) (
            $validated['unit_price'] ?? 0
        );

        $deliveryCharges = (float) (
            $validated['delivery_charges'] ?? 0
        );

        $otherCharges = (float) (
            $validated['other_charges'] ?? 0
        );

        $tax = (float) (
            $validated['tax'] ?? 0
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

        if ($isProduct || $isService) {
            $subtotal = $quantity * $unitPrice;
        } else {
            $quantity = 1;
            $subtotal = $unitPrice;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $totalAmount =
            $subtotal
            + $deliveryCharges
            + $otherCharges
            + $tax;

        /*
        |--------------------------------------------------------------------------
        | Save Commercial Offer
        |--------------------------------------------------------------------------
        */

        $enquiry->update([
            'quantity' => $quantity,

            'unit' => $validated['unit'] ?? null,

            'unit_price' => $unitPrice,

            'delivery_charges' => $deliveryCharges,

            'other_charges' => $otherCharges,

            'tax' => $tax,

            'total_amount' => $totalAmount,

            'delivery_time' =>
                $validated['delivery_time'] ?? null,

            'offer_valid_until' =>
                $validated['offer_valid_until'],

            'offer_notes' =>
                $validated['offer_notes'] ?? null,

            'offer_sent_at' => now(),

            'offer_accepted_at' => null,

            'offer_rejected_at' => null,

            'status' => 'offer_sent',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Buyer Notification
        |--------------------------------------------------------------------------
        */

        if ($enquiry->user_id) {

            $listingTitle =
                $enquiry->listing?->title
                ?? $enquiry->listing?->name
                ?? 'your enquiry';

            BuyerNotificationHelper::send(
                $enquiry->user_id,

                'New Commercial Offer',

                'You have received a commercial offer from the seller for '
                    . $listingTitle
                    . '. Total offer amount: ₹'
                    . number_format(
                        $totalAmount,
                        2
                    )
                    . '.',

                'offer',

                route(
                    'buyer.enquiries.show',
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
            'Commercial Offer Submitted',

            'Seller submitted a commercial offer for enquiry #'
                . $enquiry->id
                . ' with a total value of ₹'
                . number_format(
                    $totalAmount,
                    2
                )
                . '.',

            'offer',

            route('admin.orders.index')
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'seller.enquiries.show',
                $enquiry->id
            )
            ->with(
                'success',
                'Commercial offer has been sent successfully.'
            );
    }
}
