<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Seller;
use Illuminate\Http\Request;

class AdminSellerController extends Controller
{
    /**
     * Seller List
     */
    public function index(Request $request)
    {
        $query = Seller::with('user');

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('bussiness_name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {

                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('verified')) {

            $query->where(
                'is_verified',
                $request->verified
            );
        }

        if ($request->filled('featured')) {

            $query->where(
                'is_featured',
                $request->featured
            );
        }

        $sellers = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.sellers.index',
            compact('sellers')
        );
    }


    /**
     * Seller Details
     */
  public function show(Seller $seller)
{
    $seller->load([
        'user',

        'listings',

        'requirements.user',
        'requirements.listing',

        'rfqs.requirement',
        'rfqs.quotation',

        'quotations.requirement',
        'quotations.buyer',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Seller Activity
    |--------------------------------------------------------------------------
    */

    $listings = $seller->listings
        ->sortByDesc('id')
        ->values();

    $requirements = $seller->requirements
        ->sortByDesc('id')
        ->values();

    $rfqs = $seller->rfqs
        ->sortByDesc('id')
        ->values();

    $quotations = $seller->quotations
        ->sortByDesc('id')
        ->values();

        

    /*
    |--------------------------------------------------------------------------
    | Counts
    |--------------------------------------------------------------------------
    */

    $listingCount = $listings->count();

    $requirementCount = $requirements->count();

    $rfqCount = $rfqs->count();

    $quotationCount = $quotations->count();

    $buyerEnquiries = Enquiry::where('seller_id', $seller->id)->count();


    return view(
        'admin.sellers.show',
        compact(
            'seller',
            'listings',
            'requirements',
            'rfqs',
            'quotations',
            'listingCount',
            'requirementCount',
           'buyerEnquiries',
            'rfqCount',
            'quotationCount'
        )
    );
}

    /**
     * Verify Seller
     */
    public function verify(Seller $seller)
    {
        $seller->update([
            'is_verified' => 1,
        ]);

        return back()->with(
            'success',
            'Seller verified successfully.'
        );
    }

    public function unverify(Seller $seller)
    {
        $seller->update([
            'is_verified' => 0,
        ]);

        return back()->with(
            'success',
            'Seller verification removed.'
        );
    }


    /**
     * Mark Seller Featured
     */
    public function feature(Seller $seller)
    {
        $seller->update([
            'is_featured' => 1,
        ]);

        return back()->with(
            'success',
            'Seller marked as featured.'
        );
    }


    /**
     * Remove Featured Status
     */
    public function unfeature(Seller $seller)
    {
        $seller->update([
            'is_featured' => 0,
        ]);

        return back()->with(
            'success',
            'Seller removed from featured sellers.'
        );
    }
}
