<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Seller;
use App\Models\Banner;

class HomeController extends Controller
{
    public function home()
{
    $categories = Category::orderBy('name')->get();

    $featuredListings = Listing::with('seller')
        ->where('status', 'approved')
        ->where('is_featured', true)
        ->latest()
        ->take(8)
        ->get();

    $featuredSellers = Seller::where('is_featured', true)
        ->where('is_verified', true)
        ->latest()
        ->take(4)
        ->get();

    $banners = Banner::where('status', true)
        ->orderBy('sort_order')
        ->orderByDesc('created_at')
        ->get();

    return view('home.home', compact(
        'categories',
        'featuredListings',
        'featuredSellers',
        'banners'
    ));
}
}