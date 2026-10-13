<?php

namespace App\Http\Controllers;

use App\Models\Listing;

class EditLisitingController extends Controller
{
    public function edit($id)
    {
        $listing = Listing::findOrFail($id);

        return view('seller.listings.edit', compact('listing'));
    }
}