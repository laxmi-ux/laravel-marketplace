<?php

namespace App\Http\Controllers;

use App\Models\Rfq;
use App\Models\Seller;
use App\Models\Quotation;
use App\Models\EnquiryMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Helpers\BuyerNotificationHelper;

use App\Helpers\SellerNotificationHelper;


class RFQController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Seller RFQ List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $seller = Seller::where('user_id', Auth::id())
            ->firstOrFail();

        $rfqs = Rfq::with([
            'requirement.user',
            'requirement.listing',
        ])
            ->where('seller_id', $seller->id)
            ->latest()
            ->paginate(10);

        return view('seller.rfqs.index', compact('rfqs'));
    }


    /*
    |--------------------------------------------------------------------------
    | Show RFQ
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $seller = Seller::where('user_id', Auth::id())
            ->firstOrFail();

        $rfq = Rfq::with([
            'requirement.user',
            'requirement.listing',
            'requirement.seller',
            'messages.sender',
            'messages.receiver',
        ])
            ->where('seller_id', $seller->id)
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Mark RFQ as viewed
        |--------------------------------------------------------------------------
        */

        if ($rfq->status === 'new') {
            $rfq->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get quotation for this RFQ
        |--------------------------------------------------------------------------
        |
        | Quotations are linked using:
        | requirement_id + seller_id
        |
        */

        $quotation = Quotation::where(
            'requirement_id',
            $rfq->requirement_id
        )
            ->where('seller_id', $seller->id)
            ->latest()
            ->first();

        return view(
            'seller.rfqs.show',
            compact('rfq', 'quotation')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Send Message - Seller → Buyer
    |--------------------------------------------------------------------------
    */

    public function sendMessage(Request $request, $id)
    {
        $seller = Seller::where('user_id', Auth::id())
            ->firstOrFail();

        $rfq = Rfq::with('requirement')
            ->where('seller_id', $seller->id)
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Validate message
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Buyer
        |--------------------------------------------------------------------------
        */

        $buyerId = $rfq->requirement->user_id;

        if (!$buyerId) {
            return back()->with(
                'error',
                'Buyer could not be found for this RFQ.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create RFQ message
        |--------------------------------------------------------------------------
        */

        EnquiryMessage::create([
            'requirement_id' => $rfq->requirement_id,
            'rfq_id' => $rfq->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $buyerId,
            'message' => $validated['message'],
        ]);

      

        /*
        |--------------------------------------------------------------------------
        | Update RFQ
        |--------------------------------------------------------------------------
        */

        if (!in_array($rfq->status, ['rejected', 'closed'])) {
            $rfq->update([
                'status' => 'viewed',
                'responded_at' => now(),
            ]);
        }


        BuyerNotificationHelper::send(
            $buyerId,
            'RFQ Updated',
            'The seller has responded to your RFQ. Please check your RFQ conversation for the latest update.',
            'rfq',
            route('buyer.rfqs.show', $rfq->id)
        );


      

        return redirect()
            ->route('seller.rfqs.show', $rfq->id)
            ->with(
                'success',
                'Message sent successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject RFQ
    |--------------------------------------------------------------------------
    */

    public function reject($id)
    {
        $seller = Seller::where('user_id', Auth::id())
            ->firstOrFail();

        $rfq = Rfq::where('seller_id', $seller->id)
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Do not allow rejection after quotation
        |--------------------------------------------------------------------------
        */

        if ($rfq->status === 'quoted') {
            return back()->with(
                'error',
                'This RFQ already has a quotation and cannot be rejected.'
            );
        }

        $rfq->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return redirect()
            ->route('seller.rfqs.index')
            ->with(
                'success',
                'RFQ rejected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Quotation Form
    |--------------------------------------------------------------------------
    */

    public function quotationCreate($id)
    {
        $seller = Seller::where('user_id', Auth::id())
            ->firstOrFail();

        $rfq = Rfq::with([
            'requirement.user',
            'requirement.listing',
        ])
            ->where('seller_id', $seller->id)
            ->findOrFail($id);

        if (in_array($rfq->status, ['rejected', 'closed'])) {
            return back()->with(
                'error',
                'Quotation cannot be created for this RFQ.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Existing quotation
        |--------------------------------------------------------------------------
        */

        $quotation = Quotation::where(
            'requirement_id',
            $rfq->requirement_id
        )
            ->where('seller_id', $seller->id)
            ->latest()
            ->first();

        return view(
            'seller.rfqs.quotation',
            compact('rfq', 'quotation')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Send Quotation
    |--------------------------------------------------------------------------
    */

    public function quotationStore(Request $request, $id)
    {
        $seller = Seller::where('user_id', Auth::id())
            ->firstOrFail();

        $rfq = Rfq::with('requirement')
            ->where('seller_id', $seller->id)
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'unit' => [
                'required',
                'string',
                'max:100',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'delivery_charges' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'valid_until' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'delivery_time' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $quantity = (float) $validated['quantity'];

        $unitPrice = (float) $validated['unit_price'];

        $deliveryCharges = (float) (
            $validated['delivery_charges'] ?? 0
        );

        $tax = (float) (
            $validated['tax'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate total
        |--------------------------------------------------------------------------
        */

        $subtotal = $quantity * $unitPrice;

        $totalAmount =
            $subtotal +
            $deliveryCharges +
            $tax;

        DB::transaction(function () use (
            $validated,
            $rfq,
            $seller,
            $quantity,
            $unitPrice,
            $deliveryCharges,
            $tax,
            $totalAmount
        ) {

            /*
            |--------------------------------------------------------------------------
            | Create quotation
            |--------------------------------------------------------------------------
            */

            Quotation::create([
                'requirement_id' => $rfq->requirement_id,

                'seller_id' => $seller->id,

                /*
                | Requirement user is the buyer
                */

                'buyer_id' => $rfq->requirement->user_id,

                'quantity' => $quantity,

                'unit' => $validated['unit'],

                'unit_price' => $unitPrice,

                'delivery_charges' => $deliveryCharges,

                'tax' => $tax,

                'total_amount' => $totalAmount,

                'valid_until' => $validated['valid_until'],

                'delivery_time' =>
                $validated['delivery_time'] ?? null,

                'notes' =>
                $validated['notes'] ?? null,

                'status' => 'sent',
            ]);




            /*
            |--------------------------------------------------------------------------
            | Update RFQ
            |--------------------------------------------------------------------------
            */

            $rfq->update([
                'status' => 'quoted',
                'responded_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Requirement
            |--------------------------------------------------------------------------
            */

            if ($rfq->requirement) {
                $rfq->requirement->update([
                    'status' => 'quotation_sent',
                ]);
            }
        });


        BuyerNotificationHelper::send(
            $rfq->requirement->user_id,
            'New Quotation Received',
            'A seller has submitted a quotation for your RFQ. Review the quotation and accept or reject it.',
            'quotation',
            route('buyer.rfqs.show', $rfq->id)
        );

        return redirect()
            ->route('seller.rfqs.show', $rfq->id)
            ->with(
                'success',
                'Quotation sent successfully to the buyer.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Buyer RFQ List
    |--------------------------------------------------------------------------
    */

    public function buyerIndex()
    {
        $rfqs = Rfq::with([
            'requirement.listing',
            'seller',
        ])
            ->whereHas('requirement', function ($query) {
                $query->where(
                    'user_id',
                    Auth::id()
                );
            })
            ->latest()
            ->paginate(10);

        return view(
            'buyer.rfqs.index',
            compact('rfqs')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Buyer Show RFQ
    |--------------------------------------------------------------------------
    */

    public function buyerShow($id)
    {
        $rfq = Rfq::with([
            'requirement.listing',
            'requirement.user',
            'seller',
            'messages.sender',
        ])->findOrFail($id);

        // Security: only the buyer who created the requirement can view this RFQ
        if (!$rfq->requirement || $rfq->requirement->user_id !== Auth::id()) {
            abort(403);
        }

        $quotation = Quotation::where('requirement_id', $rfq->requirement_id)
            ->where('seller_id', $rfq->seller_id)
            ->latest()
            ->first();

        $requirement = $rfq->requirement;

        return view('buyer.rfqs.show', compact(
            'rfq',
            'requirement',
            'quotation'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Buyer Send Message → Seller
    |--------------------------------------------------------------------------
    */

    public function buyerSendMessage(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $rfq = Rfq::with([
            'requirement',
            'seller',
        ])
            ->whereHas('requirement', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        // Seller must exist
        if (!$rfq->seller) {
            return back()->with(
                'error',
                'No seller is assigned to this RFQ.'
            );
        }

        // Seller must have a linked user account
        if (empty($rfq->seller->user_id)) {
            return back()->with(
                'error',
                'This seller is not linked to a user account. Please update the seller account.'
            );
        }

        // Buyer must exist
        if (!$rfq->requirement || empty($rfq->requirement->user_id)) {
            return back()->with(
                'error',
                'Buyer account could not be found.'
            );
        }

        // Create message
        EnquiryMessage::create([
            'requirement_id' => $rfq->requirement_id,
            'rfq_id'         => $rfq->id,
            'sender_id'      => Auth::id(),
            'receiver_id'    => $rfq->seller->user_id,
            'message'        => $validated['message'],
        ]);


          SellerNotificationHelper::send(
            $rfq->seller_id,
            'New RFQ Message',
            'The buyer has sent you a new message regarding your RFQ.',
            'rfq',
            route('seller.rfqs.show', $rfq->id)
        );


        // Update RFQ
        if (!in_array($rfq->status, ['rejected', 'closed', 'expired'])) {
            $rfq->update([
                'status'       => 'viewed',
                'responded_at' => now(),
            ]);
        }

        return redirect()
            ->route('buyer.rfqs.show', $rfq->id)
            ->with(
                'success',
                'Message sent successfully to the seller.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RFQ Conversation
    |--------------------------------------------------------------------------
    */

    public function conversation($id)
    {
        $rfq = Rfq::with([
            'requirement',
            'seller',
            'messages.sender',
            'messages.receiver',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Buyer Access
        |--------------------------------------------------------------------------
        */

        $isBuyer = $rfq->requirement
            && $rfq->requirement->user_id === Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Seller Access
        |--------------------------------------------------------------------------
        */

        $seller = Seller::where(
            'user_id',
            Auth::id()
        )->first();

        $isSeller = $seller
            && $rfq->seller_id === $seller->id;

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $isBuyer || $isSeller,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Get quotation
        |--------------------------------------------------------------------------
        */

        $quotation = Quotation::where(
            'requirement_id',
            $rfq->requirement_id
        )
            ->where(
                'seller_id',
                $rfq->seller_id
            )
            ->latest()
            ->first();

        return view(
            'rfq.conversation',
            compact(
                'rfq',
                'quotation'
            )
        );
    }
}
