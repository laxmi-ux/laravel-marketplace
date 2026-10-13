<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Review;
use App\Helpers\AdminNotificationHelper;
use App\Helpers\BuyerNotificationHelper;
use App\Helpers\SellerNotificationHelper;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CATEGORY NORMALIZER
    |--------------------------------------------------------------------------
    */

    private function normalizeCategory(?string $category): string
    {
        return match (strtolower(trim((string) $category))) {
            'property', 'properties' => 'properties',
            'land' => 'land',
            default => strtolower(trim((string) $category)),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK TRANSACTION CATEGORY
    |--------------------------------------------------------------------------
    */

    private function isTransaction(Order $transaction): bool
    {
        return in_array(
            $this->normalizeCategory($transaction->category),
            ['properties', 'land'],
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSACTION NUMBER
    |--------------------------------------------------------------------------
    | Uses the existing order_number field.
    | No transaction_number column is required.
    */

    private function getTransactionNumber(Order $transaction): string
    {
        $number = trim((string) $transaction->order_number);

        if ($number !== '' && str_starts_with(strtoupper($number), 'TRN-')) {
            return $number;
        }

        // Display-only fallback; this value is not stored in the database.
        return 'TRN-' . str_pad(
            (string) $transaction->id,
            6,
            '0',
            STR_PAD_LEFT
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GET SELLER
    |--------------------------------------------------------------------------
    */

    private function getSeller(): Seller
    {
        return Seller::where('user_id', Auth::id())->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | GET SELLER TRANSACTION
    |--------------------------------------------------------------------------
    */

    private function getSellerTransaction($id): Order
    {
        $seller = $this->getSeller();

        return Order::with([
            'buyer',
            'seller',
            'quotation',
            'requirement',
            'listing',
        ])
            ->where('id', $id)
            ->where('seller_id', $seller->id)
            ->whereIn('category', ['property', 'properties', 'land'])
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | BUYER - TRANSACTION LIST
    |--------------------------------------------------------------------------
    */

    public function buyerIndex()
    {
        $transactions = Order::with([
            'seller',
            'quotation',
            'requirement',
            'listing',
        ])
            ->where('buyer_id', Auth::id())
            ->whereIn('category', ['property', 'properties', 'land'])
            ->latest('ordered_at')
            ->paginate(10);

        return view('buyer.transactions.index', compact('transactions'));
    }

    /*
    |--------------------------------------------------------------------------
    | BUYER - TRANSACTION DETAILS
    |--------------------------------------------------------------------------
    */

    public function buyerShow($id)
    {
        $transaction = Order::with([
            'buyer',
            'seller',
            'quotation',
            'requirement',
            'listing',
        ])
            ->where('buyer_id', Auth::id())
            ->where('id', $id)
            ->whereIn('category', ['property', 'properties', 'land'])
            ->firstOrFail();

        $review = Review::where('order_id', $transaction->id)
            ->where('buyer_id', Auth::id())
            ->first();

        return view(
            'buyer.transactions.show',
            compact('transaction', 'review')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUYER - COMPLETE TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function buyerComplete($id)
    {
        $transaction = Order::where('buyer_id', Auth::id())
            ->where('id', $id)
            ->whereIn('category', ['property', 'properties', 'land'])
            ->firstOrFail();

        if ($transaction->order_status !== 'documentation') {
            return back()->with(
                'error',
                'Only transactions in documentation stage can be completed.'
            );
        }

        $transaction->update([
            'order_status' => 'completed',
            'completed_at' => now(),
        ]);

        $number = $this->getTransactionNumber($transaction);

        // Notify Buyer
        BuyerNotificationHelper::send(
            $transaction->buyer_id,
            'Transaction Completed',
            "Transaction {$number} has been completed successfully.",
            'transaction',
            route('buyer.transactions.show', $transaction->id)
        );

        // Notify Seller
        SellerNotificationHelper::send(
            $transaction->seller_id,
            'Transaction Completed',
            "Transaction {$number} has been completed by the buyer.",
            'transaction',
            route('seller.transactions.show', $transaction->id)
        );

        // Notify Admin
        AdminNotificationHelper::send(
            'Transaction Completed',
            "Transaction {$number} has been completed by the buyer.",
            'transaction',
            route('admin.transactions.show', $transaction->id)
        );

        return back()->with(
            'success',
            'Transaction completed successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SELLER - TRANSACTION LIST
    |--------------------------------------------------------------------------
    */

    public function sellerIndex()
    {
        $seller = $this->getSeller();

        $transactions = Order::with([
            'buyer',
            'seller',
            'quotation',
            'requirement',
            'listing',
        ])
            ->where('seller_id', $seller->id)
            ->whereIn('category', ['property', 'properties', 'land'])
            ->latest('ordered_at')
            ->paginate(10);

        return view(
            'seller.transactions.index',
            compact('transactions', 'seller')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SELLER - TRANSACTION DETAILS
    |--------------------------------------------------------------------------
    */

    public function sellerShow($id)
    {
        $seller = $this->getSeller();
        $transaction = $this->getSellerTransaction($id);

        return view(
            'seller.transactions.show',
            compact('transaction', 'seller')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SELLER - ACCEPT TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function sellerAccept($id)
    {
        $transaction = $this->getSellerTransaction($id);

        if ($transaction->order_status !== 'pending') {
            return back()->with(
                'error',
                'Only pending transactions can be accepted.'
            );
        }

        $transaction->update([
            'order_status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $number = $this->getTransactionNumber($transaction);

        BuyerNotificationHelper::send(
            $transaction->buyer_id,
            'Transaction Confirmed',
            "Your transaction {$number} has been confirmed by the seller.",
            'transaction',
            route('buyer.transactions.show', $transaction->id)
        );

        AdminNotificationHelper::send(
            'Transaction Confirmed',
            "Transaction {$number} has been confirmed by the seller.",
            'transaction',
            route('admin.transactions.show', $transaction->id)
        );

        return back()->with(
            'success',
            'Transaction confirmed successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SELLER - REJECT TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function sellerReject($id)
    {
        $transaction = $this->getSellerTransaction($id);

        if ($transaction->order_status !== 'pending') {
            return back()->with(
                'error',
                'Only pending transactions can be rejected.'
            );
        }

        $transaction->update([
            'order_status' => 'rejected',
            'cancelled_at' => now(),
        ]);

        $number = $this->getTransactionNumber($transaction);

        BuyerNotificationHelper::send(
            $transaction->buyer_id,
            'Transaction Rejected',
            "Your transaction {$number} has been rejected by the seller.",
            'transaction',
            route('buyer.transactions.show', $transaction->id)
        );

        AdminNotificationHelper::send(
            'Transaction Rejected',
            "Transaction {$number} has been rejected by the seller.",
            'transaction',
            route('admin.transactions.show', $transaction->id)
        );

        return back()->with(
            'success',
            'Transaction rejected successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SELLER - START NEGOTIATION
    |--------------------------------------------------------------------------
    */

    public function sellerStartNegotiation($id)
    {
        $transaction = $this->getSellerTransaction($id);

        if ($transaction->order_status !== 'confirmed') {
            return back()->with(
                'error',
                'Only confirmed transactions can move to negotiation.'
            );
        }

        $transaction->update([
            'order_status' => 'negotiation',
            'negotiation_at' => now(),
        ]);

        $number = $this->getTransactionNumber($transaction);

        BuyerNotificationHelper::send(
            $transaction->buyer_id,
            'Negotiation Started',
            "Negotiation has started for transaction {$number}.",
            'transaction',
            route('buyer.transactions.show', $transaction->id)
        );

        AdminNotificationHelper::send(
            'Transaction Negotiation',
            "Negotiation has started for transaction {$number}.",
            'transaction',
            route('admin.transactions.show', $transaction->id)
        );

        return back()->with(
            'success',
            'Transaction moved to negotiation.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SELLER - DOCUMENTATION
    |--------------------------------------------------------------------------
    */

    public function sellerDocumentation($id)
    {
        $transaction = $this->getSellerTransaction($id);

        if ($transaction->order_status !== 'negotiation') {
            return back()->with(
                'error',
                'Only transactions in negotiation can move to documentation.'
            );
        }

        $transaction->update([
            'order_status' => 'documentation',
            'documentation_at' => now(),
        ]);

        $number = $this->getTransactionNumber($transaction);

        BuyerNotificationHelper::send(
            $transaction->buyer_id,
            'Documentation Started',
            "Documentation has started for transaction {$number}.",
            'transaction',
            route('buyer.transactions.show', $transaction->id)
        );

        AdminNotificationHelper::send(
            'Transaction Documentation',
            "Documentation has started for transaction {$number}.",
            'transaction',
            route('admin.transactions.show', $transaction->id)
        );

        return back()->with(
            'success',
            'Transaction moved to documentation.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SELLER - COMPLETE TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function sellerComplete($id)
    {
        $transaction = $this->getSellerTransaction($id);

        if ($transaction->order_status !== 'documentation') {
            return back()->with(
                'error',
                'Only transactions in documentation stage can be completed.'
            );
        }

        $transaction->update([
            'order_status' => 'completed',
            'completed_at' => now(),
        ]);

        $number = $this->getTransactionNumber($transaction);

        BuyerNotificationHelper::send(
            $transaction->buyer_id,
            'Transaction Completed',
            "Transaction {$number} has been completed by the seller.",
            'transaction',
            route('buyer.transactions.show', $transaction->id)
        );

        AdminNotificationHelper::send(
            'Transaction Completed',
            "Transaction {$number} has been completed by the seller.",
            'transaction',
            route('admin.transactions.show', $transaction->id)
        );

        return back()->with(
            'success',
            'Transaction completed successfully.'
        );
    }
}
