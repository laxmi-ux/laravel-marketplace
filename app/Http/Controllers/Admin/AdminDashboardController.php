<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Listing;
use App\Models\Requirement;
use App\Models\Rfq;
use App\Models\Quotation;
use App\Models\Enquiry;
use App\Models\Bid;

class AdminDashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Marketplace Statistics
        |--------------------------------------------------------------------------
        */

        // Total registered users
        $totalUsers = User::count();

        // Total buyers
        $totalBuyers = User::where('role', 'buyer')->count();

        // Total sellers
        $totalSellers = Seller::count();

        // Total listings
        $totalListings = Listing::count();


        /*
        |--------------------------------------------------------------------------
        | Marketplace Activity
        |--------------------------------------------------------------------------
        */

        // Listings waiting for admin approval
        $pendingListings = Listing::where('status', 'pending')->count();

        // Total buyer enquiries
        $totalEnquiries = Enquiry::count();

        // Total requirements
        $totalRequirements = Requirement::count();

        // Total RFQs
        $totalRfqs = Rfq::count();

        // Total quotations
        $totalQuotations = Quotation::count();

        // Total bids
        $totalBids = Bid::count();


        /*
        |--------------------------------------------------------------------------
        | Recent Data
        |--------------------------------------------------------------------------
        */

        $recentListings = Listing::with('seller')
            ->latest('id')
            ->take(5)
            ->get();

        $recentRequirements = Requirement::with([
            'user',
            'listing',
            'seller',
        ])
            ->latest('id')
            ->take(5)
            ->get();

        $recentRfqs = Rfq::with([
            'requirement',
            'seller',
        ])
            ->latest('id')
            ->take(5)
            ->get();

        $recentQuotations = Quotation::with([
            'requirement',
            'seller',
            'buyer',
        ])
            ->latest('id')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBuyers',
            'totalSellers',
            'totalListings',

            'pendingListings',
            'totalEnquiries',
            'totalRequirements',
            'totalRfqs',
            'totalQuotations',
            'totalBids',

            'recentListings',
            'recentRequirements',
            'recentRfqs',
            'recentQuotations'
        ));
    }
}