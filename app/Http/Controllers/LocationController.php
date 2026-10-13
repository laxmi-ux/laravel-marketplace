<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Listing;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('locations.index', compact('locations'));
    }

    public function show(Location $location)
    {
        abort_unless($location->status, 404);

        $listings = Listing::where('status', 'approved')
            ->where('city', 'like', '%' . $location->name . '%')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('locations.show', compact(
            'location',
            'listings'
        ));
    }
}