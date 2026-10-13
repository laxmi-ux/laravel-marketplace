<x-buyer-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <x-slot name="header">

        <div class="flex flex-col gap-3 px-10 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Order Details
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    View complete transaction information and track your order.
                </p>
            </div>

            <a href="{{ route('buyer.orders.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-[#DDE9E8] bg-white px-4 py-2 text-sm font-semibold text-[#056663] hover:bg-[#F7FAFA]">
                ← Back to Orders
            </a>

        </div>

    </x-slot>


    @php

        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        $categoryKey = strtolower(trim((string) ($order->category ?? '')));

        $category = match ($categoryKey) {

            'product',
            'products' => 'Products',

            'service',
            'services' => 'Services',

            'project',
            'projects' => 'Projects',

            'property',
            'properties' => 'Properties',

            'land' => 'Land',

            default => ucfirst(
                str_replace('_', ' ', $categoryKey)
            ),

        };


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $statusKey = strtolower(
            trim((string) ($order->order_status ?? ''))
        );

        $statusLabel = match ($statusKey) {

            'pending' => 'Pending',

            'confirmed' => 'Confirmed',

            'processing' => 'Processing',

            'negotiation' => 'Negotiation',

            'documentation' => 'Documentation',

            'shipped' => 'Shipped',

            'delivered' => 'Delivered',

            'completed' => 'Completed',

            'rejected' => 'Rejected',

            'cancelled',
            'canceled' => 'Cancelled',

            default => ucfirst(
                str_replace('_', ' ', $statusKey)
            ),

        };


        $statusClasses = match ($statusKey) {

            'pending' =>
                'bg-yellow-100 text-yellow-700',

            'confirmed' =>
                'bg-blue-100 text-blue-700',

            'processing' =>
                'bg-indigo-100 text-indigo-700',

            'negotiation' =>
                'bg-orange-100 text-orange-700',

            'documentation' =>
                'bg-purple-100 text-purple-700',

            'shipped' =>
                'bg-purple-100 text-purple-700',

            'delivered' =>
                'bg-green-100 text-green-700',

            'completed' =>
                'bg-emerald-100 text-emerald-700',

            'rejected',
            'cancelled',
            'canceled' =>
                'bg-red-100 text-red-700',

            default =>
                'bg-gray-100 text-gray-700',

        };


        /*
        |--------------------------------------------------------------------------
        | CATEGORY FLAGS
        |--------------------------------------------------------------------------
        */

        $isProduct = $category === 'Products';

        $isService = $category === 'Services';

        $isProject = $category === 'Projects';

        $isProperty = $category === 'Properties';

        $isLand = $category === 'Land';


        /*
        |--------------------------------------------------------------------------
        | AMOUNTS
        |--------------------------------------------------------------------------
        */

        $quantity = (float) ($order->quantity ?? 0);

        $unitPrice = (float) ($order->unit_price ?? 0);

        $subtotal = (float) ($order->subtotal ?? 0);

        $deliveryCharges = (float) (
            $order->delivery_charges ?? 0
        );

        $otherCharges = (float) (
            $order->other_charges ?? 0
        );

        $tax = (float) ($order->tax ?? 0);

        $total = (float) ($order->total_amount ?? 0);


        /*
        |--------------------------------------------------------------------------
        | PAYMENT INFORMATION
        |--------------------------------------------------------------------------
        */

        $payment = $order->payments()->latest()->first();

        $paymentStatus = strtolower((string) (
            $payment?->status ?? $order->payment_status ?? 'pending'
        ));

        $paymentStatusLabel = ucfirst(str_replace('_', ' ', $paymentStatus));

        $paymentStatusClasses = match ($paymentStatus) {
            'paid', 'verified', 'completed' => 'bg-green-100 text-green-700',
            'pending', 'processing' => 'bg-yellow-100 text-yellow-700',
            'failed', 'rejected', 'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };


        /*
        |--------------------------------------------------------------------------
        | SELLER
        |--------------------------------------------------------------------------
        */

        $seller = $order->seller;

        $sellerName =
            $seller?->company_name
            ?? $seller?->business_name
            ?? $seller?->name
            ?? 'Seller';


        /*
        |--------------------------------------------------------------------------
        | BUYER
        |--------------------------------------------------------------------------
        */

        $buyer = $order->buyer;

        $buyerName =
            $buyer?->name
            ?? $buyer?->full_name
            ?? 'Buyer';


        /*
        |--------------------------------------------------------------------------
        | LISTING
        |--------------------------------------------------------------------------
        */

        $listing = $order->listing;

        $listingTitle =
            $listing?->title
            ?? $listing?->name
            ?? $order->product_name
            ?? $order->service_name
            ?? $order->project_name
            ?? 'N/A';


        /*
        |--------------------------------------------------------------------------
        | REVIEW
        |--------------------------------------------------------------------------
        */

        $existingReview = null;

        if ($statusKey === 'completed') {

            $existingReview = \App\Models\Review::where(
                'order_id',
                $order->id
            )
            ->where(
                'buyer_id',
                auth()->id()
            )
            ->first();

        }

    @endphp


    <div class="space-y-6 px-10">


        {{-- =========================================================
             FLASH MESSAGES
        ========================================================== --}}

        @if(session('success'))

            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                {{ session('success') }}
            </div>

        @endif


        @if(session('error'))

            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                {{ session('error') }}
            </div>

        @endif


        {{-- =========================================================
             ORDER HEADER
        ========================================================== --}}

        <div class="mt-6 rounded-xl border border-[#DDE9E8] bg-white p-6">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Order Number
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $order->order_number }}
                    </h1>

                    @if($order->ordered_at)

                        <p class="mt-1 text-sm text-gray-500">
                            Ordered on
                            {{ optional($order->ordered_at)->format('d M Y, h:i A') }}
                        </p>

                    @endif

                </div>


                <div class="flex flex-wrap items-center gap-2">

                    <span class="rounded-full bg-[#E8F5F4] px-3 py-1.5 text-xs font-semibold text-[#056663]">
                        {{ $category }}
                    </span>

                    <span class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusClasses }}">
                        {{ $statusLabel }}
                    </span>

                </div>

            </div>

        </div>


        {{-- =========================================================
             CANCELLED / REJECTED
        ========================================================== --}}

        @if(in_array($statusKey, [
            'cancelled',
            'canceled',
            'rejected'
        ], true))

            <div class="rounded-xl border border-red-200 bg-red-50 p-5">

                <div class="flex gap-3">

                    <div class="text-lg text-red-600">
                        ⚠
                    </div>

                    <div>

                        <h3 class="font-semibold text-red-800">
                            Order {{ $statusLabel }}
                        </h3>

                        <p class="mt-1 text-sm text-red-700">
                            This order is currently marked as
                            <strong>{{ strtolower($statusLabel) }}</strong>.
                        </p>

                    </div>

                </div>

            </div>

        @endif


        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


            {{-- =====================================================
                 LEFT SIDE
            ====================================================== --}}

            <div class="space-y-6 lg:col-span-2">


                {{-- =================================================
                     BUYER INFORMATION
                ================================================== --}}

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Buyer Information
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Customer information associated with this order.
                    </p>


                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Buyer Name
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $buyerName }}
                            </p>

                        </div>


                        @if($buyer?->email)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Email Address
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $buyer->email }}
                                </p>

                            </div>

                        @endif


                        @if($buyer?->phone)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Phone Number
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $buyer->phone }}
                                </p>

                            </div>

                        @elseif($buyer?->mobile)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Phone Number
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $buyer->mobile }}
                                </p>

                            </div>

                        @endif


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Buyer ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $order->buyer_id }}
                            </p>

                        </div>


                        @if($order->ordered_at)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Transaction Date
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ optional($order->ordered_at)->format('d M Y') }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Transaction Time
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ optional($order->ordered_at)->format('h:i A') }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                     SELLER INFORMATION
                ================================================== --}}

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Seller Information
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Seller or business associated with this transaction.
                    </p>


                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Seller / Business
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $sellerName }}
                            </p>

                        </div>


                        @if($seller?->email)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Email Address
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $seller->email }}
                                </p>

                            </div>

                        @elseif($seller?->user?->email)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Email Address
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $seller->user->email }}
                                </p>

                            </div>

                        @endif


                        @if($seller?->phone)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Phone Number
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $seller->phone }}
                                </p>

                            </div>

                        @elseif($seller?->mobile)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Phone Number
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $seller->mobile }}
                                </p>

                            </div>

                        @endif


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Seller ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $order->seller_id }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ORDER / TRANSACTION INFORMATION
                ================================================== --}}

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Transaction Information
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Complete reference information for this order.
                    </p>


                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Order Number
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $order->order_number }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Order ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $order->id }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Category
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $category }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Current Status
                            </p>

                            <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                {{ $statusLabel }}
                            </span>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Buyer ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $order->buyer_id }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Seller ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $order->seller_id }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Listing ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $order->listing_id }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Requirement ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $order->requirement_id ? '#' . $order->requirement_id : 'N/A' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Enquiry ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $order->enquiry_id ? '#' . $order->enquiry_id : 'N/A' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Quotation ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $order->quotation_id ? '#' . $order->quotation_id : 'N/A' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Payment Status
                            </p>

                            <p class="mt-1 font-semibold capitalize text-gray-900">
                                {{ str_replace(
                                    '_',
                                    ' ',
                                    $order->payment_status ?? 'pending'
                                ) }}
                            </p>

                        </div>


                        @if($order->ordered_at)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Created At
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ optional($order->ordered_at)->format('d M Y, h:i A') }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                     LISTING INFORMATION
                ================================================== --}}

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Listing Information
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Information related to the marketplace listing.
                    </p>


                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                        <div class="sm:col-span-2">

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Listing Title
                            </p>

                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $listingTitle }}
                            </p>

                        </div>


                        @if($listing?->description)

                            <div class="sm:col-span-2">

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Description
                                </p>

                                <p class="mt-1 text-sm leading-6 text-gray-700">
                                    {{ $listing->description }}
                                </p>

                            </div>

                        @endif


                        @if($listing?->listing_type)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Listing Type
                                </p>

                                <p class="mt-1 font-semibold capitalize text-gray-900">
                                    {{ str_replace(
                                        '_',
                                        ' ',
                                        $listing->listing_type
                                    ) }}
                                </p>

                            </div>

                        @endif


                        @if($listing?->price !== null)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Listing Price
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    ₹{{ number_format(
                                        (float) $listing->price,
                                        2
                                    ) }}
                                </p>

                            </div>

                        @endif


                        @if($listing?->price_unit)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Price Unit
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing->price_unit }}
                                </p>

                            </div>

                        @endif


                        @if($listing?->category_id)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Category ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    #{{ $listing->category_id }}
                                </p>

                            </div>

                        @endif


                        @if($listing?->subcategory_id)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Subcategory ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    #{{ $listing->subcategory_id }}
                                </p>

                            </div>

                        @endif


                        @if($listing?->city)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    City
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing->city }}
                                </p>

                            </div>

                        @endif


                        @if($listing?->state)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    State
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing->state }}
                                </p>

                            </div>

                        @endif


                        @if($listing?->pincode)

                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Pincode
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing->pincode }}
                                </p>

                            </div>

                        @endif


                        <div>

                            <p class="text-xs font-medium uppercase text-gray-500">
                                Listing ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $order->listing_id }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     CATEGORY SPECIFIC DETAILS
                ================================================== --}}

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $category }} Details
                    </h3>


                    {{-- PRODUCT --}}
                    @if($isProduct)

                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Product Name
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $order->product_name ?? $listingTitle }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Quantity
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">

                                    {{ rtrim(
                                        rtrim(
                                            number_format(
                                                $quantity,
                                                2
                                            ),
                                            '0'
                                        ),
                                        '.'
                                    ) }}

                                    {{ $order->unit ?? '' }}

                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Unit Price
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    ₹{{ number_format(
                                        $unitPrice,
                                        2
                                    ) }}
                                </p>

                            </div>


                        </div>


                    {{-- SERVICE --}}
                    @elseif($isService)

                        <div class="mt-6 space-y-5">


                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Service Name
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $order->service_name ?? $listingTitle }}
                                </p>

                            </div>


                            @if($order->service_duration)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Service Duration
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->service_duration }}
                                    </p>

                                </div>

                            @endif


                            @if($order->requirement?->message)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Requirement / Service Details
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-gray-700">
                                        {{ $order->requirement->message }}
                                    </p>

                                </div>

                            @endif

                        </div>


                    {{-- PROJECT --}}
                    @elseif($isProject)

                        <div class="mt-6 space-y-5">


                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Project Name
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $order->project_name ?? $listingTitle }}
                                </p>

                            </div>


                            @if($order->project_location)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Project Location
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->project_location }}
                                    </p>

                                </div>

                            @endif


                            @if($order->project_timeline)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Project Timeline
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->project_timeline }}
                                    </p>

                                </div>

                            @endif


                            @if($order->requirement?->message)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Project Requirements
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-gray-700">
                                        {{ $order->requirement->message }}
                                    </p>

                                </div>

                            @endif

                        </div>


                    {{-- PROPERTY --}}
                    @elseif($isProperty)

                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Property
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listingTitle }}
                                </p>

                            </div>


                            @if($order->property_type)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Property Type
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->property_type }}
                                    </p>

                                </div>

                            @endif


                            @if($order->property_location)

                                <div class="sm:col-span-2">

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Property Location
                                    </p>

                                    <p class="mt-1 text-sm text-gray-700">
                                        {{ $order->property_location }}
                                    </p>

                                </div>

                            @endif


                            @if($order->property_area)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Property Area
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">

                                        {{ $order->property_area }}

                                        {{ $order->property_area_unit ?? '' }}

                                    </p>

                                </div>

                            @endif

                        </div>


                    {{-- LAND --}}
                    @elseif($isLand)

                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            <div>

                                <p class="text-xs font-medium uppercase text-gray-500">
                                    Land
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listingTitle }}
                                </p>

                            </div>


                            @if($order->land_location)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Land Location
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->land_location }}
                                    </p>

                                </div>

                            @endif


                            @if($order->land_area)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Land Area
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">

                                        {{ $order->land_area }}

                                        {{ $order->land_area_unit ?? '' }}

                                    </p>

                                </div>

                            @endif

                        </div>

                    @endif

                </div>


                {{-- =================================================
                     REQUIREMENT INFORMATION
                ================================================== --}}

                @if($order->requirement)

                    <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            Requirement Information
                        </h3>


                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            @if($order->requirement->id)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Requirement ID
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        #{{ $order->requirement->id }}
                                    </p>

                                </div>

                            @endif


                            @if($order->requirement->requirement_type)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Requirement Type
                                    </p>

                                    <p class="mt-1 font-semibold capitalize text-gray-900">
                                        {{ str_replace(
                                            '_',
                                            ' ',
                                            $order->requirement->requirement_type
                                        ) }}
                                    </p>

                                </div>

                            @endif


                            @if($order->requirement->quantity)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Required Quantity
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->requirement->quantity }}
                                        {{ $order->requirement->unit ?? '' }}
                                    </p>

                                </div>

                            @endif


                            @if($order->requirement->budget)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Budget
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        ₹{{ number_format(
                                            (float) $order->requirement->budget,
                                            2
                                        ) }}
                                    </p>

                                </div>

                            @endif


                            @if($order->requirement->delivery_location)

                                <div class="sm:col-span-2">

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Required Delivery Location
                                    </p>

                                    <p class="mt-1 text-sm text-gray-700">
                                        {{ $order->requirement->delivery_location }}
                                    </p>

                                </div>

                            @endif


                            @if($order->requirement->required_date)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Required Date
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->requirement->required_date }}
                                    </p>

                                </div>

                            @endif


                            @if($order->requirement->message)

                                <div class="sm:col-span-2">

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Requirement Message
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-gray-700">
                                        {{ $order->requirement->message }}
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     QUOTATION INFORMATION
                ================================================== --}}

                @if($order->quotation)

                    <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            Quotation Information
                        </h3>


                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            @if($order->quotation->id)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Quotation ID
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        #{{ $order->quotation->id }}
                                    </p>

                                </div>

                            @endif


                            @if($order->quotation->status)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Quotation Status
                                    </p>

                                    <p class="mt-1 font-semibold capitalize text-gray-900">
                                        {{ str_replace(
                                            '_',
                                            ' ',
                                            $order->quotation->status
                                        ) }}
                                    </p>

                                </div>

                            @endif


                            @if($order->quotation->amount)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Quotation Amount
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        ₹{{ number_format(
                                            (float) $order->quotation->amount,
                                            2
                                        ) }}
                                    </p>

                                </div>

                            @endif


                            @if($order->quotation->message)

                                <div class="sm:col-span-2">

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Quotation Message
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-gray-700">
                                        {{ $order->quotation->message }}
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     DELIVERY INFORMATION
                ================================================== --}}

                @if($isProduct)

                    <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            Delivery Information
                        </h3>


                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


                            @if($order->delivery_address)

                                <div class="sm:col-span-2">

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Delivery Address
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-gray-700">
                                        {{ $order->delivery_address }}
                                    </p>

                                </div>

                            @endif


                            @if($order->delivery_location)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Delivery Location
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->delivery_location }}
                                    </p>

                                </div>

                            @endif


                            @if($order->delivery_time)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Delivery Time
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $order->delivery_time }}
                                    </p>

                                </div>

                            @endif


                            @if($order->shipped_at)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Shipped On
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ optional($order->shipped_at)->format('d M Y, h:i A') }}
                                    </p>

                                </div>

                            @endif


                            @if($order->delivered_at)

                                <div>

                                    <p class="text-xs font-medium uppercase text-gray-500">
                                        Delivered On
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ optional($order->delivered_at)->format('d M Y, h:i A') }}
                                    </p>

                                </div>

                            @endif


                            @if(
                                !$order->delivery_address &&
                                !$order->delivery_location &&
                                !$order->delivery_time &&
                                !$order->shipped_at &&
                                !$order->delivered_at
                            )

                                <div class="sm:col-span-2">

                                    <p class="text-sm text-gray-500">
                                        Delivery information has not been provided yet.
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     ORDER NOTES
                ================================================== --}}

                @if($order->notes)

                    <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            Order Notes
                        </h3>

                        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-700">
                            {{ $order->notes }}
                        </p>

                    </div>

                @endif


                {{-- =================================================
                     STATUS TIMELINE
                ================================================== --}}

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Transaction Timeline
                    </h3>


                    @php

                        if ($isProduct) {

                            $timeline = [

                                [
                                    'key' => 'pending',
                                    'title' => 'Order Placed',
                                    'date' => $order->ordered_at,
                                ],

                                [
                                    'key' => 'confirmed',
                                    'title' => 'Confirmed',
                                    'date' => $order->confirmed_at,
                                ],

                                [
                                    'key' => 'processing',
                                    'title' => 'Processing',
                                    'date' => $order->processing_at,
                                ],

                                [
                                    'key' => 'shipped',
                                    'title' => 'Shipped',
                                    'date' => $order->shipped_at,
                                ],

                                [
                                    'key' => 'delivered',
                                    'title' => 'Delivered',
                                    'date' => $order->delivered_at,
                                ],

                                [
                                    'key' => 'completed',
                                    'title' => 'Completed',
                                    'date' => $order->completed_at,
                                ],

                            ];

                            $timelineOrder = [
                                'pending',
                                'confirmed',
                                'processing',
                                'shipped',
                                'delivered',
                                'completed',
                            ];

                        } elseif ($isService || $isProject) {

                            $timeline = [

                                [
                                    'key' => 'pending',
                                    'title' => 'Order Placed',
                                    'date' => $order->ordered_at,
                                ],

                                [
                                    'key' => 'confirmed',
                                    'title' => 'Confirmed',
                                    'date' => $order->confirmed_at,
                                ],

                                [
                                    'key' => 'processing',
                                    'title' => 'Processing',
                                    'date' => $order->processing_at,
                                ],

                                [
                                    'key' => 'completed',
                                    'title' => 'Completed',
                                    'date' => $order->completed_at,
                                ],

                            ];

                            $timelineOrder = [
                                'pending',
                                'confirmed',
                                'processing',
                                'completed',
                            ];

                        } else {

                            $timeline = [

                                [
                                    'key' => 'pending',
                                    'title' => 'Order Placed',
                                    'date' => $order->ordered_at,
                                ],

                                [
                                    'key' => 'confirmed',
                                    'title' => 'Confirmed',
                                    'date' => $order->confirmed_at,
                                ],

                                [
                                    'key' => 'negotiation',
                                    'title' => 'Negotiation',
                                    'date' => $order->negotiation_at,
                                ],

                                [
                                    'key' => 'documentation',
                                    'title' => 'Documentation',
                                    'date' => $order->documentation_at,
                                ],

                                [
                                    'key' => 'completed',
                                    'title' => 'Completed',
                                    'date' => $order->completed_at,
                                ],

                            ];

                            $timelineOrder = [
                                'pending',
                                'confirmed',
                                'negotiation',
                                'documentation',
                                'completed',
                            ];

                        }

                        $timelineCurrentIndex = array_search(
                            $statusKey,
                            $timelineOrder,
                            true
                        );

                        $timelineCurrentIndex =
                            $timelineCurrentIndex === false
                            ? -1
                            : $timelineCurrentIndex;

                    @endphp


                    <div class="mt-6 space-y-5">

                        @foreach($timeline as $step)

                            @php

                                $stepIndex = array_search(
                                    $step['key'],
                                    $timelineOrder,
                                    true
                                );

                                $stepCompleted =
                                    $timelineCurrentIndex >= $stepIndex;

                                $stepCurrent =
                                    $statusKey === $step['key'];

                            @endphp


                            <div class="flex items-start gap-4">

                                <div class="relative">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full
                                        {{ $stepCompleted
                                            ? 'bg-[#056663] text-white'
                                            : 'bg-gray-100 text-gray-400' }}">

                                        @if($stepCompleted)
                                            ✓
                                        @else
                                            {{ $stepIndex + 1 }}
                                        @endif

                                    </div>


                                    @if(!$loop->last)

                                        <div class="absolute left-1/2 top-10 h-7 w-px -translate-x-1/2
                                            {{ $timelineCurrentIndex > $stepIndex
                                                ? 'bg-[#056663]'
                                                : 'bg-gray-200' }}">
                                        </div>

                                    @endif

                                </div>


                                <div class="pt-1">

                                    <p class="font-semibold
                                        {{ $stepCurrent
                                            ? 'text-[#056663]'
                                            : 'text-gray-900' }}">

                                        {{ $step['title'] }}

                                    </p>


                                    @if($step['date'])

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ optional($step['date'])->format('d M Y, h:i A') }}
                                        </p>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 RIGHT SIDE
            ====================================================== --}}

            <div class="space-y-6">


                {{-- =================================================
                     ORDER AMOUNT
                ================================================== --}}

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Transaction Amount
                    </h3>


                    <div class="mt-5 space-y-4">


                        <div class="flex items-center justify-between">

                            <span class="text-sm text-gray-500">
                                Subtotal
                            </span>

                            <span class="font-medium text-gray-900">
                                ₹{{ number_format($subtotal, 2) }}
                            </span>

                        </div>


                        @if($deliveryCharges > 0)

                            <div class="flex items-center justify-between">

                                <span class="text-sm text-gray-500">
                                    Delivery Charges
                                </span>

                                <span class="font-medium text-gray-900">
                                    ₹{{ number_format(
                                        $deliveryCharges,
                                        2
                                    ) }}
                                </span>

                            </div>

                        @endif


                        @if($otherCharges > 0)

                            <div class="flex items-center justify-between">

                                <span class="text-sm text-gray-500">
                                    Other Charges
                                </span>

                                <span class="font-medium text-gray-900">
                                    ₹{{ number_format(
                                        $otherCharges,
                                        2
                                    ) }}
                                </span>

                            </div>

                        @endif


                        @if($tax > 0)

                            <div class="flex items-center justify-between">

                                <span class="text-sm text-gray-500">
                                    Tax
                                </span>

                                <span class="font-medium text-gray-900">
                                    ₹{{ number_format(
                                        $tax,
                                        2
                                    ) }}
                                </span>

                            </div>

                        @endif


                        <div class="border-t border-gray-200 pt-4">

                            <div class="flex items-center justify-between">

                                <span class="font-bold text-gray-900">
                                    Total Amount
                                </span>

                                <span class="text-xl font-bold text-[#056663]">
                                    ₹{{ number_format(
                                        $total,
                                        2
                                    ) }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PAYMENT INFORMATION
                ================================================== --}}

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Payment Information</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Payment details and verification status for this order.
                            </p>
                        </div>

                        <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $paymentStatusClasses }}">
                            {{ $paymentStatusLabel }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-500">Order Payment Status</p>
                            <p class="mt-1 font-semibold capitalize text-gray-900">
                                {{ str_replace('_', ' ', $order->payment_status ?? 'pending') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase text-gray-500">Order Amount</p>
                            <p class="mt-1 text-lg font-bold text-[#056663]">₹{{ number_format($total, 2) }}</p>
                        </div>

                        @if($payment)
                            <div>
                                <p class="text-xs font-medium uppercase text-gray-500">Payment ID</p>
                                <p class="mt-1 font-semibold text-gray-900">#{{ $payment->id }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase text-gray-500">Payment Method</p>
                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $payment->payment_method ?? $payment->method ?? 'Not specified' }}
                                </p>
                            </div>

                            <div class="sm:col-span-2">
                                <p class="text-xs font-medium uppercase text-gray-500">Transaction Reference</p>
                                <p class="mt-1 break-all font-semibold text-gray-900">
                                    {{ $payment->transaction_reference ?? $payment->transaction_id ?? 'Not provided' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase text-gray-500">Submitted Payment Amount</p>
                                <p class="mt-1 font-semibold text-gray-900">
                                    ₹{{ number_format((float) ($payment->amount ?? 0), 2) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase text-gray-500">Verification Status</p>
                                <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentStatusClasses }}">
                                    {{ $paymentStatusLabel }}
                                </span>
                            </div>

                            @if(!empty($payment->notes))
                                <div class="sm:col-span-2">
                                    <p class="text-xs font-medium uppercase text-gray-500">Payment Notes</p>
                                    <p class="mt-1 text-sm leading-6 text-gray-700">{{ $payment->notes }}</p>
                                </div>
                            @endif

                            @if($payment->created_at)
                                <div>
                                    <p class="text-xs font-medium uppercase text-gray-500">Payment Submitted On</p>
                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $payment->created_at->format('d M Y, h:i A') }}
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="sm:col-span-2 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                                <p class="font-semibold text-yellow-800">No payment record found</p>
                                <p class="mt-1 text-sm text-yellow-700">
                                    Payment transaction details will appear here after a payment record is submitted.
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($payment && $paymentStatus === 'pending')
                        <div class="mt-5 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                            <p class="font-semibold text-yellow-800">Payment verification pending</p>
                            <p class="mt-1 text-sm text-yellow-700">
                                Your payment details have been submitted and are awaiting verification. The payment will not be considered confirmed until it has been verified.
                            </p>
                        </div>
                    @elseif($payment && in_array($paymentStatus, ['paid', 'verified', 'completed'], true))
                        <div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4">
                            <p class="font-semibold text-green-800">Payment verified</p>
                            <p class="mt-1 text-sm text-green-700">
                                The payment record is marked as verified or paid.
                            </p>
                        </div>
                    @elseif($payment && in_array($paymentStatus, ['failed', 'rejected', 'cancelled'], true))
                        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">
                            <p class="font-semibold text-red-800">Payment not approved</p>
                            <p class="mt-1 text-sm text-red-700">
                                Please contact the seller or administrator for assistance.
                            </p>
                        </div>
                    @endif

                </div>


                {{-- =================================================
                     COMPLETED / REVIEW
                ================================================== --}}

                @if($statusKey === 'completed')

                    <div class="rounded-2xl border border-green-200 bg-green-50 p-6">

                        <div class="flex items-start gap-4">


                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M5 13l4 4L19 7"/>

                                </svg>

                            </div>


                            <div class="flex-1">

                                <h3 class="text-lg font-bold text-green-800">
                                    Order Completed
                                </h3>


                                <p class="mt-2 text-sm leading-6 text-green-700">
                                    This {{ strtolower($category) }}
                                    order has been successfully completed.
                                </p>


                                @if($existingReview)

                                    <div class="mt-5 rounded-xl border border-green-200 bg-white p-4">

                                        <div class="flex items-center gap-2">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5 text-green-600"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="2">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M5 13l4 4L19 7"/>

                                            </svg>


                                            <p class="text-sm font-semibold text-gray-800">
                                                Review Submitted
                                            </p>

                                        </div>


                                        <p class="mt-1 text-xs text-gray-500">

                                            Your review has been submitted and is currently
                                            {{ strtolower(
                                                $existingReview->status ?? 'pending'
                                            ) }}.

                                        </p>

                                    </div>


                                @else

                                    <a href="{{ route(
                                        'buyer.reviews.create',
                                        ['orderId' => $order->id]
                                    ) }}"
                                       class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#056663] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#045552] sm:w-auto">


                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.184 3.57a.563.563 0 00-.182.557l1.279 5.321c.115.48-.422.86-.849.605l-4.725-2.847a.563.563 0 00-.58 0l-4.725 2.847c-.427.255-.964-.125-.849-.605l1.279-5.321a.563.563 0 00-.182-.557l-4.184-3.57c-.38-.325-.178-.948.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>

                                        </svg>


                                        Write a Review

                                    </a>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     PRODUCT RECEIPT
                ================================================== --}}

                @if($isProduct && $statusKey === 'delivered')

                    <div class="rounded-xl border border-green-200 bg-green-50 p-6">

                        <h3 class="text-lg font-bold text-green-800">
                            Confirm Product Receipt
                        </h3>


                        <p class="mt-2 text-sm leading-6 text-green-700">
                            Your product has been delivered. Confirm that you have received the product to complete the order.
                        </p>


                        <form action="{{ route(
                            'buyer.orders.complete',
                            $order->id
                        ) }}"
                              method="POST"
                              class="mt-5">

                            @csrf

                            @method('PATCH')


                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to confirm that you received this product?')"
                                    class="w-full rounded-lg bg-[#056663] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#045552]">

                                Confirm Product Received

                            </button>

                        </form>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-buyer-layout>   