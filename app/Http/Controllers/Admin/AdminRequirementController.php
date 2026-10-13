<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use App\Models\Rfq;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\AdminNotificationHelper;
use App\Helpers\SellerNotificationHelper;

class AdminRequirementController extends Controller
{
    /**
     * Display all buyer requirements.
     */
    public function index(Request $request)
    {
        $query = Requirement::with([
            'listing',
            'seller',
            'user'
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%')
                    ->orWhere('requirement_type', 'like', '%' . $search . '%');
            });
        }

        // Requirement source filter
        if ($request->filled('requirement_source')) {
            $query->where(
                'requirement_source',
                $request->requirement_source
            );
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $requirements = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.requirements.index',
            compact('requirements')
        );
    }

    /**
     * Show requirement details.
     */
    public function show(Requirement $requirement)
    {
        $requirement->load([
            'listing',
            'seller',
            'user'
        ]);

        return view(
            'admin.requirements.show',
            compact('requirement')
        );
    }

    /**
     * Update requirement status.
     */
    public function updateStatus(
        Request $request,
        Requirement $requirement
    ) {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $requirement->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Requirement status updated successfully.'
            );
    }

    /**
     * Accept requirement and generate RFQs
     * for matching sellers.
     *
     * Flow:
     *
     * Buyer
     *   ↓
     * General Requirement
     *   ↓
     * Admin Accepts
     *   ↓
     * Find Matching Sellers
     *   ↓
     * Create RFQ for each seller
     *   ↓
     * Notify each seller
     */
    public function accept(Requirement $requirement)
    {
        /*
        |--------------------------------------------------------------------------
        | Already rejected
        |--------------------------------------------------------------------------
        */

        if ($requirement->status === 'rejected') {
            return back()->with(
                'error',
                'This requirement has already been rejected.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Already processed
        |--------------------------------------------------------------------------
        */

        if ($requirement->status === 'rfq_generated') {
            return back()->with(
                'error',
                'RFQs have already been generated for this requirement.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Mark requirement as accepted
        |--------------------------------------------------------------------------
        */

        $requirement->update([
            'status' => 'accepted',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find matching listings
        |--------------------------------------------------------------------------
        */

        $matchingListings = $this->findMatchingListings(
            $requirement
        );

        $createdCount = 0;

        /*
        |--------------------------------------------------------------------------
        | Create RFQs
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $requirement,
            $matchingListings,
            &$createdCount
        ) {

            $sellerIds = $matchingListings
                ->pluck('seller_id')
                ->filter()
                ->unique();

            foreach ($sellerIds as $sellerId) {

                /*
                |--------------------------------------------------------------------------
                | Prevent duplicate RFQ
                |--------------------------------------------------------------------------
                */

                $alreadyExists = Rfq::where(
                    'requirement_id',
                    $requirement->id
                )
                    ->where(
                        'seller_id',
                        $sellerId
                    )
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Create RFQ
                |--------------------------------------------------------------------------
                */

                $rfq = Rfq::create([
                    'requirement_id' => $requirement->id,
                    'seller_id' => $sellerId,
                    'status' => 'new',
                    'sent_at' => now(),
                ]);

                $createdCount++;

                /*
                |--------------------------------------------------------------------------
                | Notify Seller
                |--------------------------------------------------------------------------
                |
                | Each seller receives their own notification.
                | Clicking the notification opens that seller's RFQ.
                |
                */

                SellerNotificationHelper::send(
                    $sellerId,
                    'New RFQ Received',
                    'You have received a new RFQ for '
                        . ($requirement->name ?? 'a buyer requirement')
                        . '.',
                    'rfq',
                    route(
                        'seller.rfqs.show',
                        $rfq->id
                    )
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Update requirement status
            |--------------------------------------------------------------------------
            */

            if ($createdCount > 0) {
                $requirement->update([
                    'status' => 'rfq_generated',
                ]);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | No matching sellers
        |--------------------------------------------------------------------------
        */

        if ($createdCount === 0) {

            return redirect()
                ->route(
                    'admin.requirements.show',
                    $requirement
                )
                ->with(
                    'warning',
                    'Requirement accepted, but no matching sellers were found.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Admin notification
        |--------------------------------------------------------------------------
        */

        AdminNotificationHelper::send(
            'New RFQ Created',
            $createdCount
                . ' RFQ(s) have been generated for requirement "'
                . ($requirement->name ?? 'General Requirement')
                . '".',
            'rfq',
            route('admin.rfqs.index')
        );

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.requirements.show',
                $requirement
            )
            ->with(
                'success',
                $createdCount
                    . ' RFQ(s) generated successfully and sent to matching sellers.'
            );
    }

    /**
     * Find matching approved listings.
     */
    private function findMatchingListings(
        Requirement $requirement
    ) {
        /*
        |--------------------------------------------------------------------------
        | Determine category
        |--------------------------------------------------------------------------
        */

        $categoryId = null;

        switch (
            strtolower(
                trim(
                    (string) $requirement->requirement_type
                )
            )
        ) {

            case 'property':
            case 'properties':
                $categoryId = 1;
                break;

            case 'land':
            case 'lands':
                $categoryId = 2;
                break;

            case 'product':
            case 'products':
            case 'building material':
            case 'building materials':
                $categoryId = 3;
                break;

            case 'service':
            case 'services':
                $categoryId = 4;
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Get approved listings
        |--------------------------------------------------------------------------
        */

        $query = Listing::query()
            ->where(
                'status',
                'approved'
            )
            ->whereNotNull(
                'seller_id'
            )
            ->whereHas(
                'seller'
            );

        if ($categoryId) {
            $query->where(
                'category_id',
                $categoryId
            );
        }

        $listings = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Buyer location
        |--------------------------------------------------------------------------
        */

        $buyerLocation = strtolower(
            trim(
                (string) $requirement->delivery_location
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Build requirement search text
        |--------------------------------------------------------------------------
        */

        $searchText = strtolower(
            implode(
                ' ',
                array_filter([
                    $requirement->message,

                    // Product
                    $requirement->product_name,
                    $requirement->product_category,
                    $requirement->product_quality,
                    $requirement->preferred_brand,
                    $requirement->product_specification,

                    // Property
                    $requirement->property_type,
                    $requirement->property_purpose,
                    $requirement->property_location,
                    $requirement->property_requirements,

                    // Land
                    $requirement->land_type,
                    $requirement->land_purpose,
                    $requirement->land_location,
                    $requirement->land_requirements,

                    // Service
                    $requirement->service_name,
                    $requirement->service_type,
                    $requirement->service_location,
                    $requirement->service_scope,

                    // Project
                    $requirement->project_type,
                    $requirement->project_name,
                    $requirement->project_location,
                    $requirement->project_scope,

                    // Common
                    $requirement->delivery_location,
                    $requirement->requirement_type,
                ])
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate matching score
        |--------------------------------------------------------------------------
        */

        $scoredListings = $listings->map(
            function ($listing) use (
                $buyerLocation,
                $searchText
            ) {

                $score = 0;

                /*
                |------------------------------------------------------------------
                | Listing searchable text
                |------------------------------------------------------------------
                */

                $listingText = strtolower(
                    implode(
                        ' ',
                        array_filter([
                            $listing->title ?? null,
                            $listing->description ?? null,
                            $listing->location ?? null,
                            $listing->city ?? null,
                            $listing->state ?? null,
                        ])
                    )
                );

                /*
                |------------------------------------------------------------------
                | Location match
                |------------------------------------------------------------------
                */

                if (
                    $buyerLocation &&
                    str_contains(
                        $listingText,
                        $buyerLocation
                    )
                ) {
                    $score += 30;
                }

                /*
                |------------------------------------------------------------------
                | Keyword matching
                |------------------------------------------------------------------
                */

                if ($searchText) {

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
                |------------------------------------------------------------------
                | Verified seller bonus
                |------------------------------------------------------------------
                */

                if (
                    $listing->seller &&
                    $listing->seller->is_verified == 1
                ) {
                    $score += 10;
                }

                $listing->match_score = $score;

                return $listing;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Sort by highest match score
        |--------------------------------------------------------------------------
        */

        return $scoredListings
            ->sortByDesc('match_score')
            ->values();
    }
}