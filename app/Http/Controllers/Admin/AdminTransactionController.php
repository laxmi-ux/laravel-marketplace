<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Helpers\AdminNotificationHelper;
use App\Helpers\BuyerNotificationHelper;
use App\Helpers\SellerNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransactionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CATEGORY NORMALIZER
    |--------------------------------------------------------------------------
    */

    private function normalizeCategory(?string $category): string
    {
        return match (strtolower(trim((string) $category))) {
            'property',
            'properties' => 'properties',

            'land' => 'land',

            default => strtolower(trim((string) $category)),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION QUERY
    |--------------------------------------------------------------------------
    */

    private function transactionQuery()
    {
        return Order::with([
            'buyer',
            'seller',
            'listing',
            'quotation',
            'requirement',
        ])->whereIn('category', [
            'property',
            'properties',
            'land',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = $this->transactionQuery();

        /*
        | Search
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('order_number', 'like', "%{$search}%")

                    ->orWhereHas('buyer', function ($buyer) use ($search) {

                        $buyer->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");

                    })

                    ->orWhereHas('seller', function ($seller) use ($search) {

                        $seller->where('company_name', 'like', "%{$search}%")
                            ->orWhere('business_name', 'like', "%{$search}%")
                            ->orWhere('bussiness_name', 'like', "%{$search}%");

                    });

            });
        }


        /*
        | Status filter
        */

        if ($request->filled('order_status')) {

            $query->where(
                'order_status',
                $request->order_status
            );
        }


        /*
        | Category filter
        */

        if ($request->filled('category')) {

            $category = $this->normalizeCategory(
                $request->category
            );

            $query->whereIn('category', match ($category) {

                'properties' => [
                    'property',
                    'properties',
                ],

                'land' => [
                    'land',
                ],

                default => [
                    $request->category,
                ],
            });
        }


        $transactions = $query
            ->latest('ordered_at')
            ->paginate(15)
            ->withQueryString();


        return view(
            'admin.transactions.index',
            compact('transactions')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION DETAILS
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $transaction = $this->transactionQuery()
            ->where('id', $id)
            ->firstOrFail();


        return view(
            'admin.transactions.show',
            compact('transaction')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TRANSACTION STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        $id
    ) {

        $transaction = $this->transactionQuery()
            ->where('id', $id)
            ->firstOrFail();


        $category = $this->normalizeCategory(
            $transaction->category
        );


        /*
        | Allowed statuses
        */

        $allowedStatuses = [
            'pending',
            'confirmed',
            'negotiation',
            'documentation',
            'completed',
        ];


        $request->validate([
            'order_status' => [
                'required',
                'in:' . implode(',', $allowedStatuses),
            ],

            'admin_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $newStatus = $request->order_status;

        $currentStatus = $transaction->order_status;


        /*
        | Valid transaction flow
        */

        $validTransitions = [

            'pending' => [
                'confirmed',
            ],

            'confirmed' => [
                'negotiation',
            ],

            'negotiation' => [
                'documentation',
            ],

            'documentation' => [
                'completed',
            ],

            'completed' => [],
        ];


        if (
            $currentStatus !== $newStatus &&
            !in_array(
                $newStatus,
                $validTransitions[$currentStatus] ?? [],
                true
            )
        ) {

            return back()->with(
                'error',
                "Invalid transaction status transition from {$currentStatus} to {$newStatus}."
            );
        }


        /*
        | Update timestamps
        */

        $updateData = [
            'order_status' => $newStatus,
        ];


        switch ($newStatus) {

            case 'confirmed':

                $updateData['confirmed_at'] = now();

                break;


            case 'negotiation':

                $updateData['negotiation_at'] = now();

                break;


            case 'documentation':

                $updateData['documentation_at'] = now();

                break;


            case 'completed':

                $updateData['completed_at'] = now();

                break;
        }


        /*
        | Admin note
        */

        if ($request->filled('admin_note')) {

            $existingNotes = trim(
                (string) $transaction->notes
            );

            $newNote =
                '[' . now()->format('d M Y H:i') . '] Admin: ' .
                $request->admin_note;


            $updateData['notes'] = $existingNotes
                ? $existingNotes . PHP_EOL . $newNote
                : $newNote;
        }


        DB::transaction(function () use (
            $transaction,
            $updateData,
            $newStatus
        ) {

            $transaction->update(
                $updateData
            );


            /*
            | Buyer notification
            */

            if ($transaction->buyer_id) {

                BuyerNotificationHelper::send(

                    $transaction->buyer_id,

                    'Transaction Status Updated',

                    'Transaction ' .
                    $transaction->order_number .
                    ' has been moved to ' .
                    ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $newStatus
                        )
                    ) .
                    ' by the administrator.',

                    'transaction',

                    route(
                        'buyer.transactions.show',
                        $transaction->id
                    )
                );
            }


            /*
            | Seller notification
            */

            if ($transaction->seller_id) {

                SellerNotificationHelper::send(

                    $transaction->seller_id,

                    'Transaction Status Updated',

                    'Transaction ' .
                    $transaction->order_number .
                    ' has been moved to ' .
                    ucfirst(
                        str_replace(
                            '_',
                            ' ',
                            $newStatus
                        )
                    ) .
                    ' by the administrator.',

                    'transaction',

                    route(
                        'seller.transactions.show',
                        $transaction->id
                    )
                );
            }


            /*
            | Admin notification
            */

            AdminNotificationHelper::send(

                'Transaction Status Updated',

                'Transaction ' .
                $transaction->order_number .
                ' status has been updated to ' .
                ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $newStatus
                    )
                ) .
                '.',

                'transaction',

                route(
                    'admin.transactions.show',
                    $transaction->id
                )
            );
        });


        return back()->with(
            'success',
            'Transaction status updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Request $request,
        $id
    ) {

        $transaction = $this->transactionQuery()
            ->where('id', $id)
            ->firstOrFail();


        if (
            in_array(
                $transaction->order_status,
                [
                    'completed',
                    'cancelled',
                    'rejected',
                ],
                true
            )
        ) {

            return back()->with(
                'error',
                'This transaction can no longer be cancelled.'
            );
        }


        $request->validate([
            'reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $reason = trim(
            (string) $request->reason
        );


        $notes = trim(
            (string) $transaction->notes
        );


        $cancelNote =
            '[' . now()->format('d M Y H:i') . '] ' .
            'Transaction cancelled by Admin';

        if ($reason) {
            $cancelNote .= ': ' . $reason;
        }


        $transaction->update([
            'order_status' => 'cancelled',
            'cancelled_at' => now(),
            'notes' => $notes
                ? $notes . PHP_EOL . $cancelNote
                : $cancelNote,
        ]);


        /*
        | Buyer notification
        */

        if ($transaction->buyer_id) {

            BuyerNotificationHelper::send(

                $transaction->buyer_id,

                'Transaction Cancelled',

                'Transaction ' .
                $transaction->order_number .
                ' has been cancelled by the administrator.',

                'transaction',

                route(
                    'buyer.transactions.show',
                    $transaction->id
                )
            );
        }


        /*
        | Seller notification
        */

        if ($transaction->seller_id) {

            SellerNotificationHelper::send(

                $transaction->seller_id,

                'Transaction Cancelled',

                'Transaction ' .
                $transaction->order_number .
                ' has been cancelled by the administrator.',

                'transaction',

                route(
                    'seller.transactions.show',
                    $transaction->id
                )
            );
        }


        return back()->with(
            'success',
            'Transaction cancelled successfully.'
        );
    }
}