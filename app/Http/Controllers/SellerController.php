<?php

namespace App\Http\Controllers;

use  App\Models\Seller;

class SellerController extends Controller 
{
    public function index()
    {
        $sellers = Seller::latest()->paginate(12);

        return view('sellers.index',compact('sellers'));

    }

    public function show($slug)
    {
        $seller = Seller::where('slug', $slug)->firstOrFail();

        return view('sellers.show',compact('sellers'));
        
    }
}

?>