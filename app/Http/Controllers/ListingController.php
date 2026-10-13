<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use App\Models\Review;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::query()
            ->where('status', 'approved');

        // SEARCH KEYWORD
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('seller_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('pincode', 'like', "%{$search}%")

                    ->orWhereHas('categoryRelation', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    })

                    ->orWhereHas('subcategoryRelation', function ($subcategoryQuery) use ($search) {
                        $subcategoryQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%");
                    });
            });
        }

        // LOCATION
        if ($request->filled('city')) {

            $city = trim($request->city);

            $query->where(function ($q) use ($city) {

                $q->where('city', 'like', "%{$city}%")
                    ->orWhere('state', 'like', "%{$city}%")
                    ->orWhere('pincode', 'like', "%{$city}%");
            });
        }

        // CATEGORY
        if ($request->filled('category')) {

            $category = trim($request->category);

            $query->whereHas('categoryRelation', function ($q) use ($category) {
                $q->where('slugs', $category);
            });
        }

        // TYPE
        if ($request->filled('type') && $request->type !== 'all') {

            $type = trim($request->type);

            // Home page "buy" = database "sale"
            if ($type === 'buy') {
                $type = 'sale';
            }

            $query->where('listing_type', $type);
        }


        // SUBCATEGORY
if ($request->filled('subcategory')) {

    $subcategory = trim($request->subcategory);

    if (is_numeric($subcategory)) {

        $query->where('subcategory_id', (int) $subcategory);

    } else {

        $query->whereHas('subcategoryRelation', function ($q) use ($subcategory) {
            $q->where('slug', $subcategory)
              ->where('status', 1);
        });
    }
}

        // RESULTS
        $listings = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('listing.index', compact('listings'));
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        return $this->index($request);
    }

    /*
    |--------------------------------------------------------------------------
    | Single Listing
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $listing = Listing::where('id', $id)
            ->where('status', 'approved')
            ->firstOrFail();

        // Get only admin-approved reviews
        $reviews = Review::where('listing_id', $listing->id)
            ->where('status', 'approved')
            ->with('buyer')
            ->latest()
            ->get();

        // Total approved reviews
        $totalReviews = $reviews->count();

        // Average rating
        $averageRating = $totalReviews > 0
            ? round($reviews->avg('rating'), 1)
            : 0;

        // Rating breakdown
        $ratingCounts = [];

        for ($rating = 5; $rating >= 1; $rating--) {
            $ratingCounts[$rating] = $reviews
                ->where('rating', $rating)
                ->count();
        }

        return view('listing.show', compact(
            'listing',
            'reviews',
            'totalReviews',
            'averageRating',
            'ratingCounts'
        ));
    }

    

   /*
|--------------------------------------------------------------------------
| Products Listings
|--------------------------------------------------------------------------
*/

public function products(Request $request)
{
    $query = Listing::query()
        ->where('status', 'approved')
        ->whereHas('categoryRelation', function ($q) {
            $q->where('slugs', 'products');
        });

    // =========================================================
    // SEARCH
    // =========================================================

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('seller_name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('state', 'like', "%{$search}%")
                ->orWhere('pincode', 'like', "%{$search}%")

                ->orWhereHas('subcategoryRelation', function ($subcategoryQuery) use ($search) {
                    $subcategoryQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });

        });
    }


    // =========================================================
    // LOCATION
    // =========================================================

    if ($request->filled('city')) {

        $city = trim($request->city);

        $query->where(function ($q) use ($city) {

            $q->where('city', 'like', "%{$city}%")
                ->orWhere('state', 'like', "%{$city}%")
                ->orWhere('pincode', 'like', "%{$city}%");

        });
    }


    // =========================================================
    // PRODUCT SUBCATEGORY
    // =========================================================

   
if ($request->filled('subcategory')) {

    $subcategory = trim($request->subcategory);

    if (is_numeric($subcategory)) {

        // Filter using subcategory ID
        $query->where('subcategory_id', (int) $subcategory);

    } else {

        // Filter using subcategory slug
        $query->whereHas('subcategoryRelation', function ($q) use ($subcategory) {
            $q->where('slug', $subcategory)
              ->where('status', 1);
        });
    }
}


    // =========================================================
    // RESULTS
    // =========================================================

    $listings = $query
        ->latest()
        ->paginate(12)
        ->withQueryString();


    return view('listing.index', compact('listings'));
}
}