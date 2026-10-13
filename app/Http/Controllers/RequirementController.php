<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\EnquiryMessage;
use App\Models\Listing;
use App\Models\Requirement;
use App\Models\Seller;
use App\Notifications\MarketplaceNotification;
use App\Helpers\BuyerNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RequirementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GENERAL REQUIREMENT FORM
    | Buyer → Marketplace → Admin Review → RFQ → Matching Sellers
    |--------------------------------------------------------------------------
    */

    public function show()
    {
        return view('requirement.show');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE GENERAL REQUIREMENT
    |--------------------------------------------------------------------------
    |
    | General requirement flow:
    |
    | Buyer
    |   ↓
    | Requirement
    |   ↓
    | Admin Review
    |   ↓
    | Match Sellers
    |   ↓
    | RFQ
    |
    | IMPORTANT:
    | General requirements are NOT directly assigned to a seller here.
    |
    */

    public function storeGeneral(Request $request)
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
                'nullable',
                'string',
                'max:30',
            ],

            'quantity' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'requirement_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'budget' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'delivery_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'required_date' => [
                'nullable',
                'date',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pincode' => [
                'nullable',
                'string',
                'max:20',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'subcategory' => [
                'nullable',
                'string',
                'max:100',
            ],

            'message' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Requirement Defaults
        |--------------------------------------------------------------------------
        */

        $validated['user_id'] = Auth::id();

        // General requirement does not belong to one listing.
        $validated['listing_id'] = null;

        // Seller will be assigned during RFQ generation.
        $validated['seller_id'] = null;

        // Mark as general requirement.
        $validated['requirement_source'] = 'general';

        // Admin must review before RFQ generation.
        $validated['status'] = 'pending_review';


        /*
        |--------------------------------------------------------------------------
        | Create Requirement
        |--------------------------------------------------------------------------
        */

        $requirement = DB::transaction(function () use ($validated) {

            $requirement = Requirement::create($validated);


            /*
            |--------------------------------------------------------------------------
            | ADMIN NOTIFICATION
            |--------------------------------------------------------------------------
            |
            | Notify all admins about the new requirement.
            |
            */

            $admins = Admin::all();

            foreach ($admins as $admin) {

                $admin->notify(
                    new MarketplaceNotification(
                        'New Requirement Received',
                        'A buyer has submitted a new requirement that requires admin review.',
                        'requirement',
                        route(
                            'admin.requirements.show',
                            $requirement->id
                        )
                    )
                );
            }


            return $requirement;
        });



        BuyerNotificationHelper::send(
            Auth::id(),
            'Requirement Submitted',
            'Your requirement has been submitted successfully. Our team will review it and match you with suitable sellers.',
            'rfq',
            route('buyer.notifications.index')
        );
        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('requirement.show')
            ->with(
                'success',
                'Your requirement has been submitted successfully and is pending admin review.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FIND MATCHING LISTINGS
    |--------------------------------------------------------------------------
    */

    private function findMatchingListings(Requirement $requirement)
    {
        /*
        |--------------------------------------------------------------------------
        | Determine Category
        |--------------------------------------------------------------------------
        */

        $categorySlug = null;

        if ($requirement->requirement_type) {

            $type = strtolower(
                trim($requirement->requirement_type)
            );

            $categoryMap = [

                'product' => 'building-material',

                'products' => 'building-material',

                'building material' => 'building-material',

                'building materials' => 'building-material',

                'property' => 'property',

                'properties' => 'property',

                'land' => 'land',

                'lands' => 'land',

                'service' => 'service',

                'services' => 'service',

                'project' => 'project',

                'projects' => 'project',

            ];

            $categorySlug = $categoryMap[$type] ?? null;
        }


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = Listing::query()
            ->with([
                'seller',
                'category',
                'subcategory',
            ])
            ->where('status', 'active')
            ->whereNotNull('seller_id')
            ->whereHas('seller');


        /*
        |--------------------------------------------------------------------------
        | Category Matching
        |--------------------------------------------------------------------------
        */

        if ($categorySlug) {

            $category = Category::where(
                'slugs',
                $categorySlug
            )->first();

            if ($category) {

                $query->where(
                    'category_id',
                    $category->id
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Fetch Listings
        |--------------------------------------------------------------------------
        */

        $listings = $query
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Score Matching Listings
        |--------------------------------------------------------------------------
        */

        $searchText = strtolower(
            implode(' ', array_filter([

                $requirement->message,

                $requirement->subcategory,

                $requirement->category,

                $requirement->requirement_type,

            ]))
        );


        $deliveryLocation = strtolower(
            trim($requirement->delivery_location ?? '')
        );


        $buyerCity = strtolower(
            trim($requirement->city ?? '')
        );


        $scoredListings = $listings->map(
            function ($listing) use (
                $searchText,
                $deliveryLocation,
                $buyerCity
            ) {

                $score = 0;


                /*
                |--------------------------------------------------------------------------
                | Location Matching
                |--------------------------------------------------------------------------
                */

                $listingCity = strtolower(
                    trim($listing->city ?? '')
                );


                if (
                    $buyerCity &&
                    $listingCity &&
                    $buyerCity === $listingCity
                ) {

                    $score += 30;
                }


                if (
                    $deliveryLocation &&
                    $listingCity &&
                    str_contains(
                        $deliveryLocation,
                        $listingCity
                    )
                ) {

                    $score += 20;
                }


                /*
                |--------------------------------------------------------------------------
                | Keyword Matching
                |--------------------------------------------------------------------------
                */

                $listingText = strtolower(
                    implode(' ', array_filter([

                        $listing->title,

                        $listing->description,

                        $listing->category?->name,

                        $listing->subcategory?->name,

                    ]))
                );


                if ($searchText && $listingText) {

                    /*
                    |--------------------------------------------------------------------------
                    | FIXED REGEX
                    |--------------------------------------------------------------------------
                    */

                    $keywords = preg_split(
                        '/\s+/',
                        $searchText
                    );


                    foreach ($keywords as $keyword) {

                        $keyword = trim($keyword);


                        if (
                            strlen($keyword) >= 3 &&
                            str_contains(
                                $listingText,
                                $keyword
                            )
                        ) {

                            $score += 5;
                        }
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Verified Seller
                |--------------------------------------------------------------------------
                */

                if (
                    $listing->seller &&
                    $listing->seller->is_verified
                ) {

                    $score += 10;
                }


                $listing->match_score = $score;

                return $listing;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Sort By Match Score
        |--------------------------------------------------------------------------
        */

        $sortedListings = $scoredListings
            ->sortByDesc('match_score')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | One Listing Per Seller
        |--------------------------------------------------------------------------
        |
        | We only need one matching listing to identify a seller.
        | RFQ is created once per seller.
        |
        */

        return $sortedListings
            ->unique('seller_id')
            ->values();
    }


    /*
    |--------------------------------------------------------------------------
    | LISTING-SPECIFIC REQUIREMENT
    |--------------------------------------------------------------------------
    |
    | Buyer → Specific Listing → Specific Seller
    |
    | This flow does NOT automatically create an RFQ.
    |
    */

    public function store(
        Request $request,
        $listingId
    ) {

        $listing = Listing::with('seller')
            ->where('id', $listingId)
            ->where('status', 'active')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

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
                'nullable',
                'string',
                'max:30',
            ],

            'quantity' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'requirement_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'budget' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'delivery_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'required_date' => [
                'nullable',
                'date',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pincode' => [
                'nullable',
                'string',
                'max:20',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'subcategory' => [
                'nullable',
                'string',
                'max:100',
            ],

            'message' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Requirement Defaults
        |--------------------------------------------------------------------------
        */

        $validated['user_id'] = Auth::id();

        // Exact listing.
        $validated['listing_id'] = $listing->id;

        // Exact seller.
        $validated['seller_id'] = $listing->seller_id;

        // Listing-specific requirement.
        $validated['requirement_source'] = 'listing';

        // Initial status.
        $validated['status'] = 'new';


        /*
        |--------------------------------------------------------------------------
        | Create Requirement + Admin Notification
        |--------------------------------------------------------------------------
        */

        $requirement = DB::transaction(function () use (
            $validated,
            $listing
        ) {

            $requirement = Requirement::create(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | ADMIN NOTIFICATION
            |--------------------------------------------------------------------------
            */

            $admins = Admin::all();

            foreach ($admins as $admin) {

                $admin->notify(
                    new MarketplaceNotification(
                        'New Listing Enquiry',
                        'A buyer has submitted a new enquiry for listing: '
                            . ($listing->title ?? 'Listing')
                            . '.',
                        'enquiry',
                        route(
                            'admin.requirements.show',
                            $requirement->id
                        )
                    )
                );
            }


            return $requirement;
        });


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->back()
            ->with(
                'success',
                'Your requirement has been sent successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | BUYER REQUIREMENTS
    |--------------------------------------------------------------------------
    */

    public function buyerIndex()
    {
        $requirements = Requirement::with([

            /*
            |--------------------------------------------------------------------------
            | Listing
            |--------------------------------------------------------------------------
            */

            'listing',

            /*
            |--------------------------------------------------------------------------
            | Seller
            |--------------------------------------------------------------------------
            */

            'seller',

            /*
            |--------------------------------------------------------------------------
            | General Requirement → RFQs
            |--------------------------------------------------------------------------
            */

            'rfqs.seller',

            /*
            |--------------------------------------------------------------------------
            | Messages
            |--------------------------------------------------------------------------
            */

            'messages.sender',

            'messages.receiver',

            /*
            |--------------------------------------------------------------------------
            | Quotations
            |--------------------------------------------------------------------------
            */

            'quotations',

        ])
            ->where(
                'user_id',
                Auth::id()
            )
            ->latest()
            ->paginate(10);


        return view(
            'buyer.requirement.index',
            compact('requirements')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW / REPLY TO REQUIREMENT
    |--------------------------------------------------------------------------
    |
    | General requirement:
    |     Must communicate through a specific RFQ.
    |
    | Listing requirement:
    |     Direct seller conversation.
    |
    */

    public function reply($id)
    {
        $requirement = Requirement::with([

            'listing',

            'seller',

            'rfqs.seller',

            'messages.sender',

            'messages.receiver',

            'quotations',

        ])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | GENERAL REQUIREMENT
        |--------------------------------------------------------------------------
        */

        if (
            $requirement->requirement_source === 'general'
        ) {

            /*
            |--------------------------------------------------------------------------
            | RFQs Available
            |--------------------------------------------------------------------------
            */

            if ($requirement->rfqs->count()) {

                return redirect()
                    ->route('buyer.rfqs.index')
                    ->with(
                        'info',
                        'Select a seller RFQ to continue the conversation.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | No RFQ Yet
            |--------------------------------------------------------------------------
            */

            return back()
                ->with(
                    'info',
                    'No seller RFQ is available for this requirement yet.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LISTING-SPECIFIC REQUIREMENT
        |--------------------------------------------------------------------------
        */

        return view(
            'buyer.requirement.reply',
            compact('requirement')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE BUYER REPLY
    |--------------------------------------------------------------------------
    |
    | This method is ONLY for listing-specific requirements.
    |
    | General requirement messages must use the RFQ conversation.
    |
    */

    public function storeReply(
        Request $request,
        $id
    ) {

        $request->validate([

            'message' => [
                'required',
                'string',
                'max:2000',
            ],

        ]);


        $requirement = Requirement::with([
            'seller',
            'rfqs',
        ])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | GENERAL REQUIREMENT
        |--------------------------------------------------------------------------
        */

        if (
            $requirement->requirement_source === 'general'
        ) {

            return redirect()
                ->route('buyer.rfqs.index')
                ->with(
                    'info',
                    'Please select the specific seller RFQ to send your message.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Seller Check
        |--------------------------------------------------------------------------
        */

        if (!$requirement->seller_id) {

            return back()
                ->with(
                    'error',
                    'Seller information is not available for this requirement.'
                );
        }


        if (!$requirement->seller) {

            return back()
                ->with(
                    'error',
                    'Seller information is not available for this requirement.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Direct Seller Message
        |--------------------------------------------------------------------------
        |
        | rfq_id MUST remain NULL for listing enquiries.
        |
        */

        EnquiryMessage::create([

            'requirement_id' => $requirement->id,

            'rfq_id' => null,

            'sender_id' => Auth::id(),

            'receiver_id' => $requirement->seller->user_id,

            'message' => $request->message,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Requirement Status
        |--------------------------------------------------------------------------
        */

        $requirement->update([
            'status' => 'contacted',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'buyer.requirement.reply',
                $requirement->id
            )
            ->with(
                'success',
                'Your message has been sent to the seller.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT REQUIREMENT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $requirement = Requirement::where(
            'id',
            $id
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->firstOrFail();


        return view(
            'buyer.requirement.edit',
            compact('requirement')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE REQUIREMENT
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $requirement = Requirement::where(
            'id',
            $id
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->firstOrFail();


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
                'nullable',
                'string',
                'max:30',
            ],

            'quantity' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'unit' => [
                'nullable',
                'string',
                'max:100',
            ],

            'requirement_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'budget' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'delivery_location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'required_date' => [
                'nullable',
                'date',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pincode' => [
                'nullable',
                'string',
                'max:20',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'subcategory' => [
                'nullable',
                'string',
                'max:100',
            ],

            'message' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);


        
        /*
        |--------------------------------------------------------------------------
        | Do Not Change Ownership / Source Fields
        |--------------------------------------------------------------------------
        */

        $requirement->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('buyer.requirement.index')
            ->with(
                'success',
                'Requirement updated successfully.'
            );


            
    }

    

    /*
    |--------------------------------------------------------------------------
    | DELETE REQUIREMENT
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $requirement = Requirement::where(
            'id',
            $id
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->firstOrFail();


        $requirement->delete();


        return redirect()
            ->route('buyer.requirement.index')
            ->with(
                'success',
                'Requirement deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SELLER ENQUIRIES
    |--------------------------------------------------------------------------
    |
    | Only listing-specific enquiries appear here.
    |
    | General requirements are handled through RFQs.
    |
    */

    public function sellerIndex()
    {
        $seller = Seller::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        $requirements = Requirement::with([

            'listing',

            'user',

            'messages.sender',

            'messages.receiver',

        ])
            ->where(
                'seller_id',
                $seller->id
            )
            ->where(
                'requirement_source',
                'listing'
            )
            ->latest()
            ->paginate(10);


        return view(
            'seller.enquiries.index',
            compact(
                'requirements',
                'seller'
            )
        );
    }
}
