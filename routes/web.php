<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerListingController;
use App\Http\Controllers\SellerEnquiryController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RfqQuotationController;
use App\Http\Controllers\RFQController;
use App\Http\Controllers\BuyerEnquiryController;
use App\Http\Controllers\BuyerSavedListingController;
use App\Http\Controllers\BuyerCompareController;
use App\Http\Controllers\BuyerBidController;
use App\Http\Controllers\SellerBidController;
use App\Http\Controllers\BuyerReviewController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\SellerPublicController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SellerCustomerController;
use App\Http\Controllers\ReportSubmissionController;
use App\Http\Controllers\BuyerPaymentController;
use App\Http\Controllers\SellerPaymentController;

use App\Http\Controllers\BuyerNotificationController;
use App\Http\Controllers\SellerNotificationController;

use App\Http\Controllers\Admin\AdminRfqController;
use App\Http\Controllers\Admin\AdminLocationController;
use App\Http\Controllers\Admin\AdminQuotationController;
use App\Http\Controllers\Admin\AdminBuyerController;
use App\Http\Controllers\Admin\AdminSellerController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminListingController;
use App\Http\Controllers\Admin\AdminRequirementController;
use App\Http\Controllers\Admin\AdminPropertyController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminLandController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminProjectController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use App\Models\Listing;
use App\Models\Seller;
use App\Models\Category;
use App\Models\Quotation;
use App\Models\Rfq;
use App\Models\Requirement;
use App\Models\Enquiry;
use App\Models\SavedListing;
use App\Models\CompareListing;
use App\Models\Bid;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/



Route::get('/marketplace-logo', function () {
    $path = \App\Models\Setting::where('key', 'logo_path')->value('value');

    if (!$path) {
        abort(404, 'Logo not configured.');
    }

    $fullPath = storage_path('app/public/' . $path);

    if (!File::exists($fullPath)) {
        abort(404, 'Logo file not found.');
    }

    return response()->file($fullPath);
})->name('marketplace.logo');

/*
|--------------------------------------------------------------------------
| PUBLIC LOCATIONS
|--------------------------------------------------------------------------
*/

Route::get('/locations', [LocationController::class, 'index'])
    ->name('locations.index');

Route::get('/locations/{location:slug}', [LocationController::class, 'show'])
    ->name('locations.show');

//Products 

Route::get('/products', [ListingController::class, 'products'])
    ->name('products.index');
/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'home'])
    ->name('home');

Route::get('/home', [HomeController::class, 'home']);


/*
|--------------------------------------------------------------------------
| PUBLIC UPLOADS
|--------------------------------------------------------------------------
*/

Route::get('/uploads/{path}', function ($path) {

    $file = base_path('uploads/' . $path);

    if (!File::exists($file)) {
        abort(404);
    }

    return Response::file($file);
})->where('path', '.*')
    ->name('uploads.file');


/*
|--------------------------------------------------------------------------
| PUBLIC LISTINGS
|--------------------------------------------------------------------------
*/

Route::get('/listing', [ListingController::class, 'index'])
    ->name('listing.index');

Route::get('/listing/{id}', [ListingController::class, 'show'])
    ->name('listing.show');

Route::get('/listings/search', [ListingController::class, 'search'])
    ->name('listing.search');

Route::get('/listings', [ListingController::class, 'index'])
    ->name('listings.index');


/*
|--------------------------------------------------------------------------
| PUBLIC SELLERS
|--------------------------------------------------------------------------
*/

Route::get('/sellers', [SellerController::class, 'index'])
    ->name('sellers.index');

Route::get('/sellers/{slug}', [SellerController::class, 'show'])
    ->name('sellers.show');



/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {



    Route::get('/reports/create', [
        ReportSubmissionController::class,
        'create',
    ])->name('reports.create');

    Route::post('/reports', [
        ReportSubmissionController::class,
        'store',
    ])->name('reports.store');


    Route::get('/buyer/reports/{report}', [
        \App\Http\Controllers\BuyerReportController::class,
        'show'
    ])->name('buyer.reports.show');
    /*
    |--------------------------------------------------------------------------
    | GENERAL REQUIREMENT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/requirement',
        [RequirementController::class, 'show']
    )->name('requirement.show');

    Route::post(
        '/requirement',
        [RequirementController::class, 'storeGeneral']
    )->name('requirements.general.store');


    /*
    |--------------------------------------------------------------------------
    | BUYER DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/buyer/dashboard', function () {

        $userId = Auth::id();

        $myRequirement = Requirement::where(
            'user_id',
            $userId
        )
            ->where(
                'requirement_source',
                'general'
            )
            ->count();

        $myEnquiries = Enquiry::where(
            'user_id',
            $userId
        )->count();

        $savedListings = SavedListing::where(
            'user_id',
            $userId
        )->count();

        $compareCount = CompareListing::where(
            'user_id',
            $userId
        )->count();

        $myRfqs = Rfq::whereHas(
            'requirement',
            function ($query) use ($userId) {
                $query->where(
                    'user_id',
                    $userId
                );
            }
        )->count();

        $quotationCount = Quotation::where(
            'buyer_id',
            $userId
        )->count();

        $acceptedBids = Bid::where(
            'user_id',
            $userId
        )
            ->where(
                'status',
                'accepted'
            )
            ->count();

        $requirements = Requirement::with([
            'listing',
            'seller',
            'rfqs.seller',
            'quotations.seller',
        ])
            ->where(
                'user_id',
                $userId
            )
            ->latest()
            ->get();

        return view(
            'buyer.dashboard',
            compact(
                'myRequirement',
                'myEnquiries',
                'myRfqs',
                'quotationCount',
                'savedListings',
                'compareCount',
                'acceptedBids',
                'requirements'
            )
        );
    })->name('buyer.dashboard');


    /*
    |--------------------------------------------------------------------------
    | BUYER NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buyer/notifications',
        [BuyerNotificationController::class, 'index']
    )->name('buyer.notifications.index');

    Route::get(
        '/buyer/notifications/{notification}/read',
        [BuyerNotificationController::class, 'read']
    )->name('buyer.notifications.read');

    Route::patch(
        '/buyer/notifications/read-all',
        [BuyerNotificationController::class, 'readAll']
    )->name('buyer.notifications.readAll');

    Route::delete(
        '/buyer/notifications/{notification}',
        [BuyerNotificationController::class, 'destroy']
    )->name('buyer.notifications.destroy');


    /*
    |--------------------------------------------------------------------------
    | BUYER BIDS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buyer/bids',
        [BuyerBidController::class, 'index']
    )->name('buyer.bids.index');

    Route::get(
        '/buyer/bids/create/{listingId}',
        [BuyerBidController::class, 'create']
    )->name('buyer.bids.create');

    Route::post(
        '/buyer/bids/{listingId}',
        [BuyerBidController::class, 'store']
    )->name('buyer.bids.store');

    Route::get(
        '/buyer/bids/{id}',
        [BuyerBidController::class, 'show']
    )->name('buyer.bids.show');

    Route::post(
        '/buyer/bids/{id}/withdraw',
        [BuyerBidController::class, 'withdraw']
    )->name('buyer.bids.withdraw');


    /*
    |--------------------------------------------------------------------------
    | BUYER SAVED LISTINGS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buyer/saved-listings',
        [BuyerSavedListingController::class, 'index']
    )->name('buyer.saved-listings.index');

    Route::post(
        '/buyer/saved-listings/{listingId}',
        [BuyerSavedListingController::class, 'store']
    )->name('buyer.saved-listings.store');

    Route::delete(
        '/buyer/saved-listings/{listingId}',
        [BuyerSavedListingController::class, 'destroy']
    )->name('buyer.saved-listings.destroy');


    /*
    |--------------------------------------------------------------------------
    | BUYER COMPARE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buyer/compare',
        [BuyerCompareController::class, 'index']
    )->name('buyer.compare.index');

    Route::post(
        '/buyer/compare/{listingId}',
        [BuyerCompareController::class, 'store']
    )->name('buyer.compare.store');

    Route::delete(
        '/buyer/compare/{listingId}',
        [BuyerCompareController::class, 'destroy']
    )->name('buyer.compare.destroy');


    /*
    |--------------------------------------------------------------------------
    | BUYER REQUIREMENTS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buyer/requirement',
        [RequirementController::class, 'buyerIndex']
    )->name('buyer.requirement.index');

    Route::get(
        '/buyer/requirement/{id}/edit',
        [RequirementController::class, 'edit']
    )->name('buyer.requirement.edit');

    Route::put(
        '/buyer/requirement/{id}',
        [RequirementController::class, 'update']
    )->name('buyer.requirement.update');

    Route::delete(
        '/buyer/requirement/{id}',
        [RequirementController::class, 'destroy']
    )->name('buyer.requirement.destroy');


    /*
    |--------------------------------------------------------------------------
    | BUYER ENQUIRIES
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/listing/{listingId}/enquiry',
        [BuyerEnquiryController::class, 'store']
    )->name('enquiry.store');

    Route::get(
        '/buyer/enquiries',
        [BuyerEnquiryController::class, 'index']
    )->name('buyer.enquiries.index');

    Route::get(
        '/buyer/enquiries/{id}',
        [BuyerEnquiryController::class, 'show']
    )->name('buyer.enquiries.show');


    /*
    |--------------------------------------------------------------------------
    | BUYER ENQUIRY OFFERS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buyer/enquiries/{id}/offer',
        [BuyerEnquiryController::class, 'offer']
    )->name('buyer.enquiries.offer');

    Route::patch(
        '/buyer/enquiries/{id}/offer/accept',
        [BuyerEnquiryController::class, 'acceptOffer']
    )->name('buyer.enquiries.offer.accept');

    Route::patch(
        '/buyer/enquiries/{id}/offer/reject',
        [BuyerEnquiryController::class, 'rejectOffer']
    )->name('buyer.enquiries.offer.reject');


    /*
    |--------------------------------------------------------------------------
    | BUYER RFQS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buyer/rfqs',
        [RFQController::class, 'buyerIndex']
    )->name('buyer.rfqs.index');

    Route::get(
        '/buyer/rfqs/{id}',
        [RFQController::class, 'buyerShow']
    )->name('buyer.rfqs.show');

    Route::post(
        '/buyer/rfqs/{id}/message',
        [RFQController::class, 'buyerSendMessage']
    )->name('buyer.rfqs.message.store');


    /*
    |--------------------------------------------------------------------------
    | RFQ CONVERSATION
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/rfq/{id}/conversation',
        [RFQController::class, 'conversation']
    )->name('rfq.conversation');


    /*
    |--------------------------------------------------------------------------
    | BUYER QUOTATIONS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buyer/quotations',
        [QuotationController::class, 'buyerIndex']
    )->name('buyer.quotations.index');

    Route::get(
        '/buyer/quotations/{id}',
        [QuotationController::class, 'buyerShow']
    )->name('buyer.quotations.show');

    Route::post(
        '/buyer/quotations/{id}/accept',
        [QuotationController::class, 'accept']
    )->name('buyer.quotations.accept');

    Route::post(
        '/buyer/quotations/{id}/reject',
        [QuotationController::class, 'reject']
    )->name('buyer.quotations.reject');


    /*
    |--------------------------------------------------------------------------
    | BUYER ORDERS / REVIEWS / TRANSACTIONS
    |--------------------------------------------------------------------------
    */

    Route::prefix('buyer')
        ->name('buyer.')
        ->group(function () {

            Route::get(
                '/orders',
                [OrderController::class, 'buyerIndex']
            )->name('orders.index');

            Route::get(
                '/orders/{id}',
                [OrderController::class, 'buyerShow']
            )->name('orders.show');

            Route::patch(
                '/orders/{id}/complete',
                [OrderController::class, 'buyerComplete']
            )->name('orders.complete');

            Route::get(
                '/orders/{orderId}/review',
                [BuyerReviewController::class, 'create']
            )->name('reviews.create');

            Route::post(
                '/orders/{orderId}/review',
                [BuyerReviewController::class, 'store']
            )->name('reviews.store');

            Route::get(
                '/transactions',
                [TransactionController::class, 'buyerIndex']
            )->name('transactions.index');

            Route::get(
                '/transactions/{id}',
                [TransactionController::class, 'buyerShow']
            )->name('transactions.show');

            Route::patch(
                '/transactions/{id}/complete',
                [TransactionController::class, 'buyerComplete']
            )->name('transactions.complete');

            Route::get(
                '/reviews',
                [ReviewController::class, 'index']
            )->name('reviews.index');

            /*
             * IMPORTANT:
             * These duplicate review routes previously existed.
             * Keeping only one version prevents route-name conflicts.
             */
            Route::get(
                '/reviews/{orderId}/create',
                [ReviewController::class, 'create']
            )->name('reviews.create.form');

            Route::post(
                '/reviews/{orderId}',
                [ReviewController::class, 'store']
            )->name('reviews.store.form');




            /*
|--------------------------------------------------------------------------
| BUYER CHECKOUT & MANUAL PAYMENT
|--------------------------------------------------------------------------
*/

            Route::get(
                '/orders/{id}/checkout',
                [BuyerPaymentController::class, 'checkout']
            )->name('orders.checkout');

            Route::post(
                '/orders/{id}/manual-payment',
                [BuyerPaymentController::class, 'submitManualPayment']
            )->name('orders.manual-payment');

            /*
|--------------------------------------------------------------------------
| BUYER PAYMENT HISTORY
|--------------------------------------------------------------------------
*/

            Route::get(
                '/payments',
                [BuyerPaymentController::class, 'index']
            )->name('payments.index');

            Route::get(
                '/payments/{id}',
                [BuyerPaymentController::class, 'show']
            )->name('payments.show');


            // payment management 
            Route::get('/payments', [BuyerPaymentController::class, 'index'])
                ->name('payments.index');

            Route::get('/payments/{id}', [BuyerPaymentController::class, 'show'])
                ->name('payments.show');
        });


    /*
    |--------------------------------------------------------------------------
    | SELLER ROUTES
    |--------------------------------------------------------------------------
    */

    Route::prefix('seller')
        ->name('seller.')
        ->group(function () {


            /*
            |--------------------------------------------------------------------------
            | SELLER DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                function () {

                    $seller = Seller::where(
                        'user_id',
                        Auth::id()
                    )->firstOrFail();

                    $totalListings = Listing::where(
                        'seller_id',
                        $seller->id
                    )->count();

                    $approvedListings = Listing::where(
                        'seller_id',
                        $seller->id
                    )
                        ->where(
                            'status',
                            'approved'
                        )
                        ->count();

                    $pendingListings = Listing::where(
                        'seller_id',
                        $seller->id
                    )
                        ->where(
                            'status',
                            'pending'
                        )
                        ->count();

                    $rejectedListings = Listing::where(
                        'seller_id',
                        $seller->id
                    )
                        ->where(
                            'status',
                            'rejected'
                        )
                        ->count();

                    $buyerEnquiries = Enquiry::where(
                        'seller_id',
                        $seller->id
                    )->count();

                    $rfqCount = Rfq::where(
                        'seller_id',
                        $seller->id
                    )
                        ->whereIn(
                            'status',
                            [
                                'new',
                                'viewed',
                            ]
                        )
                        ->count();

                    $listings = Listing::where(
                        'seller_id',
                        $seller->id
                    )
                        ->latest()
                        ->take(10)
                        ->get();

                    $quotationsSent = Quotation::where(
                        'seller_id',
                        $seller->id
                    )->count();

                    $acceptedQuotations = Quotation::where(
                        'seller_id',
                        $seller->id
                    )
                        ->where(
                            'status',
                            'accepted'
                        )
                        ->count();

                    $acceptedBids = Bid::where(
                        'seller_id',
                        $seller->id
                    )
                        ->where(
                            'status',
                            'accepted'
                        )
                        ->count();

                    return view(
                        'seller.dashboard',
                        compact(
                            'seller',
                            'totalListings',
                            'approvedListings',
                            'pendingListings',
                            'rejectedListings',
                            'buyerEnquiries',
                            'rfqCount',
                            'listings',
                            'quotationsSent',
                            'acceptedQuotations',
                            'acceptedBids'
                        )
                    );
                }
            )->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | SELLER NOTIFICATIONS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/notifications',
                [SellerNotificationController::class, 'index']
            )->name('notifications.index');

            Route::get(
                '/notifications/{notification}/read',
                [SellerNotificationController::class, 'read']
            )->name('notifications.read');

            Route::patch(
                '/notifications/read-all',
                [SellerNotificationController::class, 'readAll']
            )->name('notifications.readAll');

            Route::delete(
                '/notifications/{notification}',
                [SellerNotificationController::class, 'destroy']
            )->name('notifications.destroy');


            /*
            |--------------------------------------------------------------------------
            | SELLER BIDS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/bids',
                [SellerBidController::class, 'index']
            )->name('bids.index');

            Route::get(
                '/bids/{id}',
                [SellerBidController::class, 'show']
            )->name('bids.show');

            Route::post(
                '/bids/{id}/accept',
                [SellerBidController::class, 'accept']
            )->name('bids.accept');

            Route::post(
                '/bids/{id}/reject',
                [SellerBidController::class, 'reject']
            )->name('bids.reject');


            /*
            |--------------------------------------------------------------------------
            | SELLER ENQUIRIES
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/enquiries',
                [SellerEnquiryController::class, 'index']
            )->name('enquiries.index');

            Route::get(
                '/enquiries/{id}',
                [SellerEnquiryController::class, 'show']
            )->name('enquiries.show');

            Route::get(
                '/enquiries/{id}/respond',
                [SellerEnquiryController::class, 'respond']
            )->name('enquiries.respond');

            Route::post(
                '/enquiries/{id}/respond',
                [SellerEnquiryController::class, 'storeResponse']
            )->name('enquiries.respond.store');

            Route::get(
                '/enquiries/{id}/offer',
                [SellerEnquiryController::class, 'offer']
            )->name('enquiries.offer');

            Route::post(
                '/enquiries/{id}/offer',
                [SellerEnquiryController::class, 'storeOffer']
            )->name('enquiries.offer.store');


            //  Customer Management

            Route::get('/customers', [SellerCustomerController::class, 'index'])->name('customers.index');

            /*
            |--------------------------------------------------------------------------
            | SELLER LISTINGS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/listings',
                [SellerListingController::class, 'index']
            )->name('listings.index');

            Route::get(
                '/listings/create',
                [SellerListingController::class, 'create']
            )->name('listings.create');

            Route::post(
                '/listings',
                [SellerListingController::class, 'store']
            )->name('listings.store');

            Route::get(
                '/listings/{listing}/edit',
                [SellerListingController::class, 'edit']
            )->name('listings.edit');

            Route::put(
                '/listings/{listing}',
                [SellerListingController::class, 'update']
            )->name('listings.update');

            /*
             * IMPORTANT:
             * Specific document route comes before {listing}
             */
            Route::get(
                '/listings/{listing}/documents/{document}',
                [SellerListingController::class, 'downloadDocument']
            )->name('listings.documents.download');

            Route::get(
                '/listings/{listing}',
                [SellerListingController::class, 'show']
            )->name('listings.show');

            Route::delete(
                '/listings/{id}',
                [SellerListingController::class, 'destroy']
            )->name('listings.destroy');


            /*
            |--------------------------------------------------------------------------
            | SUBCATEGORIES API
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/api/subcategories/{categorySlug}',
                function ($categorySlug) {

                    $category = Category::where(
                        'slugs',
                        $categorySlug
                    )
                        ->where(
                            'status',
                            true
                        )
                        ->firstOrFail();

                    $subcategories = \App\Models\Subcategory::where(
                        'category_id',
                        $category->id
                    )
                        ->where(
                            'status',
                            true
                        )
                        ->orderBy('name')
                        ->get([
                            'id',
                            'name',
                            'slug',
                        ]);

                    return response()->json($subcategories);
                }
            )->name('api.subcategories');


            /*
            |--------------------------------------------------------------------------
            | SELLER RFQ MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/rfqs',
                [RFQController::class, 'index']
            )->name('rfqs.index');

            Route::get(
                '/rfqs/{id}',
                [RFQController::class, 'show']
            )->name('rfqs.show');

            Route::post(
                '/rfqs/{id}/message',
                [RFQController::class, 'sendMessage']
            )->name('rfqs.message');

            Route::post(
                '/rfqs/{id}/reject',
                [RFQController::class, 'reject']
            )->name('rfqs.reject');

            Route::get(
                '/rfqs/{rfq}/quotation',
                [RfqQuotationController::class, 'create']
            )->name('rfqs.quotation.create');

            Route::post(
                '/rfqs/{rfq}/quotation',
                [RfqQuotationController::class, 'store']
            )->name('rfqs.quotation.store');


            /*
            |--------------------------------------------------------------------------
            | SELLER QUOTATIONS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/quotations',
                [QuotationController::class, 'sellerIndex']
            )->name('quotations.index');

            Route::get(
                '/quotations/{id}',
                [QuotationController::class, 'show']
            )->name('quotations.show');


            /*
            |--------------------------------------------------------------------------
            | SELLER ORDERS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/orders',
                [OrderController::class, 'sellerIndex']
            )->name('orders.index');

            Route::get(
                '/orders/{id}',
                [OrderController::class, 'sellerShow']
            )->name('orders.show');

            Route::patch(
                '/orders/{id}/accept',
                [OrderController::class, 'sellerAccept']
            )->name('orders.accept');

            Route::patch(
                '/orders/{id}/reject',
                [OrderController::class, 'sellerReject']
            )->name('orders.reject');

            Route::patch(
                '/orders/{id}/process',
                [OrderController::class, 'sellerProcess']
            )->name('orders.process');

            Route::patch(
                '/orders/{id}/ship',
                [OrderController::class, 'sellerShip']
            )->name('orders.ship');

            Route::patch(
                '/orders/{id}/deliver',
                [OrderController::class, 'sellerDeliver']
            )->name('orders.deliver');

            Route::patch(
                '/orders/{id}/start-work',
                [OrderController::class, 'sellerStartWork']
            )->name('orders.start-work');

            Route::patch(
                '/orders/{id}/complete-work',
                [OrderController::class, 'sellerCompleteWork']
            )->name('orders.complete-work');

            Route::patch(
                '/orders/{id}/negotiation',
                [OrderController::class, 'sellerStartNegotiation']
            )->name('orders.negotiation');

            Route::patch(
                '/orders/{id}/documentation',
                [OrderController::class, 'sellerDocumentation']
            )->name('orders.documentation');

            Route::patch(
                '/orders/{id}/complete-transaction',
                [OrderController::class, 'sellerCompleteTransaction']
            )->name('orders.complete-transaction');


            // payment 

            Route::patch(
                '/payments/{id}/verify',
                [SellerPaymentController::class, 'verify']
            )->name('payments.verify');

            Route::patch(
                '/payments/{id}/reject',
                [SellerPaymentController::class, 'reject']
            )->name('payments.reject');

            /*
            |--------------------------------------------------------------------------
            | SELLER TRANSACTIONS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/transactions',
                [TransactionController::class, 'sellerIndex']
            )->name('transactions.index');

            Route::get(
                '/transactions/{id}',
                [TransactionController::class, 'sellerShow']
            )->name('transactions.show');

            Route::patch(
                '/transactions/{id}/accept',
                [TransactionController::class, 'sellerAccept']
            )->name('transactions.accept');

            Route::patch(
                '/transactions/{id}/reject',
                [TransactionController::class, 'sellerReject']
            )->name('transactions.reject');

            Route::patch(
                '/transactions/{id}/negotiation',
                [TransactionController::class, 'sellerStartNegotiation']
            )->name('transactions.negotiation');

            Route::patch(
                '/transactions/{id}/documentation',
                [TransactionController::class, 'sellerDocumentation']
            )->name('transactions.documentation');

            Route::patch(
                '/transactions/{id}/complete',
                [TransactionController::class, 'sellerComplete']
            )->name('transactions.complete');


            // payment Management 

            Route::get('/payments', [SellerPaymentController::class, 'index'])
                ->name('payments.index');

            Route::get('/payments/{id}', [SellerPaymentController::class, 'show'])
                ->name('payments.show');


            Route::patch(
                '/orders/{id}/payment',
                [OrderController::class, 'updatePayment']
            )->name('orders.payment.update');
        });


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| PUBLIC SELLER PROFILE
|--------------------------------------------------------------------------
|
| IMPORTANT:
| This route is intentionally AFTER /seller/dashboard.
| Otherwise /seller/dashboard can be interpreted as {seller}.
|
*/

Route::get(
    '/seller/{seller}',
    [SellerPublicController::class, 'show']
)->name('seller.public.show');


/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION & MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {



        Route::resource('locations', AdminLocationController::class)
            ->except(['show']);

        Route::patch(
            'locations/{location}/toggle-status',
            [AdminLocationController::class, 'toggleStatus']
        )->name('locations.toggle-status');
        /*
        |--------------------------------------------------------------------------
        | ADMIN BANNERS
        |--------------------------------------------------------------------------
        */

        Route::resource('banners', BannerController::class)
            ->except(['show']);

        Route::patch(
            'banners/{banner}/toggle-status',
            [BannerController::class, 'toggleStatus']
        )->name('banners.toggleStatus');


        /*
        |--------------------------------------------------------------------------
        | ADMIN LOGIN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/login',
            [AdminAuthController::class, 'showLogin']
        )->name('login');

        Route::post(
            '/login',
            [AdminAuthController::class, 'login']
        )->name('login.submit');


        /*
        |--------------------------------------------------------------------------
        | ADMIN PROTECTED ROUTES
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth:admin')->group(function () {



            /*
            |--------------------------------------------------------------------------
            | ADMIN NOTIFICATIONS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/notifications',
                [AdminNotificationController::class, 'index']
            )->name('notifications.index');

            Route::get(
                '/notifications/{notification}/read',
                [AdminNotificationController::class, 'read']
            )->name('notifications.read');

            Route::patch(
                '/notifications/read-all',
                [AdminNotificationController::class, 'readAll']
            )->name('notifications.readAll');

            Route::delete(
                '/notifications/{notification}',
                [AdminNotificationController::class, 'destroy']
            )->name('notifications.destroy');





            /*
            |--------------------------------------------------------------------------
            | ADMIN DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/dashboard',
                [AdminDashboardController::class, 'index']
            )->name('dashboard');


            /*
            |--------------------------------------------------------------------------
            | ADMIN LOGOUT
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/logout',
                [AdminAuthController::class, 'logout']
            )->name('logout');


            /*
            |--------------------------------------------------------------------------
            | BUYER MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/buyers',
                [AdminBuyerController::class, 'index']
            )->name('buyers.index');

            Route::get(
                '/buyers/{buyer}',
                [AdminBuyerController::class, 'show']
            )->name('buyers.show');

            Route::patch(
                '/buyers/{buyer}/activate',
                [AdminBuyerController::class, 'activate']
            )->name('buyers.activate');

            Route::patch(
                '/buyers/{buyer}/deactivate',
                [AdminBuyerController::class, 'deactivate']
            )->name('buyers.deactivate');


            /*
            |--------------------------------------------------------------------------
            | SELLER MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/sellers',
                [AdminSellerController::class, 'index']
            )->name('sellers.index');

            Route::get(
                '/sellers/{seller}',
                [AdminSellerController::class, 'show']
            )->name('sellers.show');

            Route::patch(
                '/sellers/{seller}/verify',
                [AdminSellerController::class, 'verify']
            )->name('sellers.verify');

            Route::patch(
                '/sellers/{seller}/unverify',
                [AdminSellerController::class, 'unverify']
            )->name('sellers.unverify');

            Route::patch(
                '/sellers/{seller}/feature',
                [AdminSellerController::class, 'feature']
            )->name('sellers.feature');

            Route::patch(
                '/sellers/{seller}/unfeature',
                [AdminSellerController::class, 'unfeature']
            )->name('sellers.unfeature');





            /*
|--------------------------------------------------------------------------
| ADMIN CATEGORY MANAGEMENT
|--------------------------------------------------------------------------
*/

            Route::resource('categories', AdminCategoryController::class)
                ->except(['show']);

            Route::patch(
                '/categories/{category}/toggle-status',
                [AdminCategoryController::class, 'toggleStatus']
            )->name('categories.toggle-status');



            /*
            |--------------------------------------------------------------------------
            | ADMIN LISTINGS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/listings',
                [AdminListingController::class, 'index']
            )->name('listings.index');

            Route::get(
                '/listings/{listing}',
                [AdminListingController::class, 'show']
            )->name('listings.show');

            Route::get(
                '/listings/{listing}/edit',
                [AdminListingController::class, 'edit']
            )->name('listings.edit');

            Route::put(
                '/listings/{listing}',
                [AdminListingController::class, 'update']
            )->name('listings.update');

            Route::post(
                '/listings/{listing}/approve',
                [AdminListingController::class, 'approve']
            )->name('listings.approve');

            Route::post(
                '/listings/{listing}/reject',
                [AdminListingController::class, 'reject']
            )->name('listings.reject');

            Route::delete(
                '/listings/{listing}',
                [AdminListingController::class, 'destroy']
            )->name('listings.destroy');


            /*
            |--------------------------------------------------------------------------
            | ADMIN PROPERTIES
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/properties',
                [AdminPropertyController::class, 'index']
            )->name('properties.index');

            Route::get(
                '/properties/create',
                [AdminPropertyController::class, 'create']
            )->name('properties.create');

            Route::post(
                '/properties',
                [AdminPropertyController::class, 'store']
            )->name('properties.store');


            /*
            |--------------------------------------------------------------------------
            | ADMIN REQUIREMENTS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/requirements',
                [AdminRequirementController::class, 'index']
            )->name('requirements.index');

            Route::get(
                '/requirements/{requirement}',
                [AdminRequirementController::class, 'show']
            )->name('requirements.show');

            Route::put(
                '/requirements/{requirement}/status',
                [AdminRequirementController::class, 'updateStatus']
            )->name('requirements.status');

            Route::put(
                '/requirements/{requirement}/accept',
                [AdminRequirementController::class, 'accept']
            )->name('requirements.accept');

            Route::put(
                '/requirements/{requirement}/reject',
                [AdminRequirementController::class, 'reject']
            )->name('requirements.reject');


            /*
            |--------------------------------------------------------------------------
            | ADMIN RFQS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/rfqs',
                [AdminRfqController::class, 'index']
            )->name('rfqs.index');

            Route::get(
                '/rfqs/{rfq}',
                [AdminRfqController::class, 'show']
            )->name('rfqs.show');


            /*
            |--------------------------------------------------------------------------
            | ADMIN QUOTATIONS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/quotations',
                [AdminQuotationController::class, 'index']
            )->name('quotations.index');

            Route::get(
                '/quotations/{quotation}',
                [AdminQuotationController::class, 'show']
            )->name('quotations.show');


            /*
            |--------------------------------------------------------------------------
            | ADMIN ORDERS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/orders',
                [AdminOrderController::class, 'index']
            )->name('orders.index');

            Route::get(
                '/orders/{id}',
                [AdminOrderController::class, 'show']
            )->name('orders.show');

            Route::patch(
                '/orders/{id}/status',
                [AdminOrderController::class, 'updateStatus']
            )->name('orders.status');

            Route::patch(
                '/orders/{id}/cancel',
                [AdminOrderController::class, 'cancel']
            )->name('orders.cancel');


            /*
            |--------------------------------------------------------------------------
            | ADMIN TRANSACTIONS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/transactions',
                [AdminTransactionController::class, 'index']
            )->name('transactions.index');

            Route::get(
                '/transactions/{id}',
                [AdminTransactionController::class, 'show']
            )->name('transactions.show');

            Route::patch(
                '/transactions/{id}/status',
                [AdminTransactionController::class, 'updateStatus']
            )->name('transactions.status');

            Route::patch(
                '/transactions/{id}/cancel',
                [AdminTransactionController::class, 'cancel']
            )->name('transactions.cancel');



            // Reports 


            Route::get('/reports', [AdminReportController::class, 'index'])
                ->name('reports.index');

            Route::get('/reports/{report}', [AdminReportController::class, 'show'])
                ->name('reports.show');

            Route::put('/reports/{report}', [AdminReportController::class, 'update'])
                ->name('reports.update');


            // payment 
            Route::get('/payments', [AdminPaymentController::class, 'index'])
                ->name('payments.index');

            Route::get('/payments/{id}', [AdminPaymentController::class, 'show'])
                ->name('payments.show');

            // Route::patch('/payments/{id}/approve', [AdminPaymentController::class, 'approve'])
            //     ->name('payments.approve');

            // Route::patch('/payments/{id}/reject', [AdminPaymentController::class, 'reject'])
            //     ->name('payments.reject');


            // settings 

            Route::get('/settings', [AdminSettingController::class, 'index'])
                ->name('settings.index');

            Route::put('/settings', [AdminSettingController::class, 'update'])
                ->name('settings.update');
            /*
            |--------------------------------------------------------------------------
            | ADMIN REVIEWS
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/reviews',
                [AdminReviewController::class, 'index']
            )->name('reviews.index');

            Route::patch(
                '/reviews/{review}/status',
                [AdminReviewController::class, 'updateStatus']
            )->name('reviews.updateStatus');
        });
    });


/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
