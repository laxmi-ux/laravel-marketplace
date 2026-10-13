<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rfq;
use Illuminate\Http\Request;

class AdminRfqController extends Controller
{
    /**
     * Display all RFQs.
     */
    public function index(Request $request)
    {
        $query = Rfq::with([
            'requirement.user',
            'requirement.listing',
            'seller',
            'quotation',
        ]);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('id', 'like', "%{$search}%")

                    ->orWhereHas('requirement', function ($requirementQuery) use ($search) {

                        $requirementQuery
                            ->where('id', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })

                    ->orWhereHas('seller', function ($sellerQuery) use ($search) {

                        $sellerQuery
                            ->where('bussiness_name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rfqs = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.rfqs.index', compact('rfqs'));
    }


    /**
     * Display RFQ details.
     */
    public function show(Rfq $rfq)
    {
        $rfq->load([
            'requirement.user',
            'requirement.listing',
            'seller',
            'messages.sender',
            'quotation',
        ]);

        return view('admin.rfqs.show', compact('rfq'));
    }
}