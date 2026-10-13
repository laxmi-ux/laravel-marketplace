<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;

class AdminQuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with([
            'requirement.user',
            'requirement.listing',
            'seller',
            'buyer',
            'rfq',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('requirement_id', 'like', "%{$search}%")

                    ->orWhereHas('requirement', function ($requirementQuery) use ($search) {

                        $requirementQuery
                            ->where('id', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })

                    ->orWhereHas('seller', function ($sellerQuery) use ($search) {

                        $sellerQuery->where(
                            'bussiness_name',
                            'like',
                            "%{$search}%"
                        );
                    })

                    ->orWhereHas('buyer', function ($buyerQuery) use ($search) {

                        $buyerQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $quotations = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.quotations.index',
            compact('quotations')
        );
    }


    public function show(Quotation $quotation)
    {
        $quotation->load([
            'requirement.user',
            'requirement.listing',
            'seller',
            'buyer',
            'rfq',
        ]);

        return view(
            'admin.quotations.show',
            compact('quotation')
        );
    }
}