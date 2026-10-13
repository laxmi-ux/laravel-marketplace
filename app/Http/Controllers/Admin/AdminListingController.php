<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Category;
use App\Helpers\SellerNotificationHelper;
use Illuminate\Http\Request;

class AdminListingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listing Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Listing::with([
            'seller',
            'categoryRelation',
            'subcategoryRelation'
        ]);

        // Search by title
        if ($request->filled('search')) {
            $query->where(
                'title',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where(
                'category_id',
                $request->category
            );
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $listings = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where(
            'status',
            1
        )
            ->orderBy('id')
            ->get();

        return view(
            'admin.listings.index',
            compact(
                'listings',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Listing
    |--------------------------------------------------------------------------
    */

    public function show(Listing $listing)
    {
        $listing->load([
            'seller',
            'categoryRelation',
            'subcategoryRelation',
            'product',
            'property',
            'land',
            'service',
            'project',
            'documents',
        ]);

        return view(
            'admin.listings.show',
            compact('listing')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Listing
    |--------------------------------------------------------------------------
    */

    public function edit(Listing $listing)
    {
        $listing->load([
            'categoryRelation',
            'subcategoryRelation',
            'product',
            'property',
            'land',
            'service',
            'project',
        ]);

        return view(
            'admin.listings.edit',
            compact('listing')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Listing
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Listing $listing
    ) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'description' => 'nullable|string',

            'price' => 'nullable|numeric|min:0',

            'price_unit' => 'nullable|string|max:100',

            'city' => 'nullable|string|max:100',

            'state' => 'nullable|string|max:100',

            'pincode' => 'nullable|string|max:20',

            'status' => 'required|in:pending,approved,rejected,draft',
        ]);

        $listing->update($validated);

        return redirect()
            ->route('admin.listings.index')
            ->with(
                'success',
                'Listing updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve Listing
    |--------------------------------------------------------------------------
    */

    public function approve(Listing $listing)
    {
        $listing->update([
            'status' => 'approved',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Notify Seller
        |--------------------------------------------------------------------------
        */

        if ($listing->seller_id) {

            SellerNotificationHelper::send(
                $listing->seller_id,

                'Listing Approved',

                'Your listing "'
                    . ($listing->title ?? 'listing')
                    . '" has been approved by admin and is now active.',

                'listing',

                route(
                    'seller.listings.show',
                    $listing->id
                )
            );
        }


        return redirect()
            ->back()
            ->with(
                'success',
                'Listing approved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Listing
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Listing $listing
    ) {
        $request->validate([
            'rejection_reason' =>
                'nullable|string|max:1000',
        ]);


        $listing->update([
            'status' => 'rejected',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Notify Seller
        |--------------------------------------------------------------------------
        */

        if ($listing->seller_id) {

            $message =
                'Your listing "'
                . ($listing->title ?? 'listing')
                . '" has been rejected by admin.';


            /*
            |----------------------------------------------------------------------
            | Add Rejection Reason
            |----------------------------------------------------------------------
            */

            if ($request->filled('rejection_reason')) {

                $message .=
                    ' Reason: '
                    . $request->rejection_reason;
            }


            SellerNotificationHelper::send(
                $listing->seller_id,

                'Listing Rejected',

                $message,

                'listing',

                route(
                    'seller.listings.show',
                    $listing->id
                )
            );
        }


        return redirect()
            ->back()
            ->with(
                'success',
                'Listing rejected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Listing
    |--------------------------------------------------------------------------
    */

    public function destroy(Listing $listing)
    {
        $listing->delete();

        return redirect()
            ->route('admin.listings.index')
            ->with(
                'success',
                'Listing deleted successfully.'
            );
    }
}