<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use App\Models\EnquiryMessage;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Http\Request;

class AdminBuyerController extends Controller
{
    /**
     * Buyer List
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', 'buyer');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $buyers = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.buyers.index', compact('buyers'));
    }


    /**
     * Buyer Details
     */
    public function show(User $buyer)
    {
        abort_unless($buyer->role === 'buyer', 404);

        /*
        |--------------------------------------------------------------------------
        | Load buyer's marketplace activity
        |--------------------------------------------------------------------------
        */

        $buyer->load([
            'requirements.listing',
            'requirements.seller',
            'requirements.rfqs.seller',
            'requirements.rfqs.quotation',
            'requirements.quotations.seller',
            'requirements.messages.sender',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Buyer Requirements
        |--------------------------------------------------------------------------
        */

        $requirements = $buyer->requirements
            ->sortByDesc('id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Buyer RFQs
        |--------------------------------------------------------------------------
        |
        | RFQs belong to requirements, so we collect
        | all RFQs generated from this buyer's requirements.
        |
        */

        $rfqs = $requirements
            ->flatMap(function ($requirement) {
                return $requirement->rfqs;
            })
            ->sortByDesc('id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Buyer Quotations
        |--------------------------------------------------------------------------
        */

        $quotations = $requirements
            ->flatMap(function ($requirement) {
                return $requirement->quotations;
            })
            ->sortByDesc('id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Buyer Messages
        |--------------------------------------------------------------------------
        */

        $messages = $requirements
            ->flatMap(function ($requirement) {
                return $requirement->messages;
            })
            ->sortByDesc('id')
            ->values();



        $enquiryCount = Requirement::where('user_id', $buyer->id)
            ->where(function ($query) {
                $query->whereNotNull('seller_id')
                    ->orWhereHas('rfqs');
            })
            ->count();



        // =========================================================
        // BUYER ENQUIRIES
        // =========================================================

        $enquiries = Requirement::with([
            'user',
            'listing',
            'seller',
            'rfqs.seller',
            'messages.sender',
        ])
            ->where('user_id', $buyer->id)
            ->where(function ($query) {
                $query->whereNotNull('seller_id')
                    ->orWhereHas('rfqs');
            })
            ->latest()
            ->get();

        $enquiryCount = $enquiries->count();
        /*
        |--------------------------------------------------------------------------
        | Counts
        |--------------------------------------------------------------------------
        */

        $requirementCount = $requirements->count();
        $rfqCount = $rfqs->count();
        $quotationCount = $quotations->count();
        $messageCount = $messages->count();

        return view('admin.buyers.show', compact(
            'buyer',
            'requirements',
            'rfqs',
            'quotations',
            'messages',
            'requirementCount',
            'rfqCount',
            'quotationCount',
            'enquiryCount',
            'messageCount',
            'enquiries'
        ));
    }


    /**
     * Activate Buyer
     */
    public function activate(User $buyer)
    {
        abort_unless($buyer->role === 'buyer', 404);

        $buyer->update([
            'status' => 'active',
        ]);

        return back()->with(
            'success',
            'Buyer activated successfully.'
        );
    }

    /**
     * Deactivate Buyer
     */
    public function deactivate(User $buyer)
    {
        abort_unless($buyer->role === 'buyer', 404);

        $buyer->update([
            'status' => 'inactive',
        ]);

        return back()->with(
            'success',
            'Buyer deactivated successfully.'
        );
    }
}
