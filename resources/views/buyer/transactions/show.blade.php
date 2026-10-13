<x-buyer-layout>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @php

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            $category = strtolower(
                trim((string) ($transaction->category ?? ''))
            );

            $categoryKey = match ($category) {

                'property',
                'properties' => 'properties',

                'land' => 'land',

                default => $category,

            };

            $categoryLabel = match ($categoryKey) {

                'properties' => 'Property',

                'land' => 'Land',

                default => ucfirst(
                    str_replace('_', ' ', $categoryKey)
                ),

            };


            $isProperty = $categoryKey === 'properties';

            $isLand = $categoryKey === 'land';


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $statusKey = strtolower(
                trim((string) ($transaction->order_status ?? ''))
            );

            $statusLabel = match ($statusKey) {

                'pending' => 'Pending',

                'confirmed' => 'Confirmed',

                'negotiation' => 'Negotiation',

                'documentation' => 'Documentation',

                'completed' => 'Completed',

                'cancelled',
                'canceled' => 'Cancelled',

                'rejected' => 'Rejected',

                default => ucfirst(
                    str_replace('_', ' ', $statusKey)
                ),

            };


            $statusClass = match ($statusKey) {

                'pending' =>
                    'bg-yellow-100 text-yellow-700',

                'confirmed' =>
                    'bg-blue-100 text-blue-700',

                'negotiation' =>
                    'bg-purple-100 text-purple-700',

                'documentation' =>
                    'bg-indigo-100 text-indigo-700',

                'completed' =>
                    'bg-green-100 text-green-700',

                'cancelled',
                'canceled',
                'rejected' =>
                    'bg-red-100 text-red-700',

                default =>
                    'bg-gray-100 text-gray-700',

            };


            /*
            |--------------------------------------------------------------------------
            | SELLER
            |--------------------------------------------------------------------------
            */

            $seller = $transaction->seller;

            $sellerName =
                $seller?->company_name
                ?? $seller?->business_name
                ?? $seller?->bussiness_name
                ?? $seller?->name
                ?? 'Seller';


            /*
            |--------------------------------------------------------------------------
            | BUYER
            |--------------------------------------------------------------------------
            */

            $buyer = $transaction->buyer;

            $buyerName =
                $buyer?->name
                ?? $buyer?->full_name
                ?? 'Buyer';


            /*
            |--------------------------------------------------------------------------
            | LISTING
            |--------------------------------------------------------------------------
            */

            $listing = $transaction->listing;


            /*
            |--------------------------------------------------------------------------
            | PROPERTY / LAND DETAILS
            |--------------------------------------------------------------------------
            |
            | These fields belong to the ORDER table.
            | They are NOT read from listings because your listings table
            | does not contain these columns.
            |
            */

            $propertyType =
                $transaction->property_type ?? null;

            $propertyArea =
                $transaction->property_area ?? null;

            $propertyAreaUnit =
                $transaction->property_area_unit ?? null;

            $propertyLocation =
                $transaction->property_location ?? null;


            $landArea =
                $transaction->land_area ?? null;

            $landAreaUnit =
                $transaction->land_area_unit ?? null;

            $landLocation =
                $transaction->land_location ?? null;


            /*
            |--------------------------------------------------------------------------
            | AMOUNTS
            |--------------------------------------------------------------------------
            */

            $subtotal = (float) (
                $transaction->subtotal ?? 0
            );

            $deliveryCharges = (float) (
                $transaction->delivery_charges ?? 0
            );

            $otherCharges = (float) (
                $transaction->other_charges ?? 0
            );

            $tax = (float) (
                $transaction->tax ?? 0
            );

            $totalAmount = (float) (
                $transaction->total_amount ?? 0
            );


            /*
            |--------------------------------------------------------------------------
            | REVIEW
            |--------------------------------------------------------------------------
            |
            | If your controller already passes $review, this query can
            | be removed and the controller value can be used.
            |
            */

            $review = \App\Models\Review::where(
                'order_id',
                $transaction->id
            )
            ->where(
                'buyer_id',
                auth()->id()
            )
            ->first();


            /*
            |--------------------------------------------------------------------------
            | CLOSED STATUS
            |--------------------------------------------------------------------------
            */

            $isClosed = in_array(
                $statusKey,
                [
                    'completed',
                    'cancelled',
                    'canceled',
                    'rejected',
                ],
                true
            );

        @endphp


        {{-- =========================================================
             BACK
        ========================================================== --}}

        <div class="mb-6">

            <a
                href="{{ route('buyer.transactions.index') }}"
                class="inline-flex items-center text-sm font-medium text-[#056663] hover:underline"
            >
                ← Back to Transactions
            </a>

        </div>


        {{-- =========================================================
             FLASH MESSAGES
        ========================================================== --}}

        @if(session('success'))

            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>

        @endif


        @if(session('error'))

            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>

        @endif


        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="bg-white border border-[#DDE9E8] rounded-xl p-6 shadow-sm mb-6">

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5">

                <div>

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $transaction->order_number }}
                        </h1>

                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>

                    </div>

                    <p class="mt-2 text-sm text-gray-500">
                        {{ $categoryLabel }} Transaction
                    </p>

                </div>


                <div class="text-left md:text-right">

                    <div class="text-xs text-gray-500">
                        Transaction Amount
                    </div>

                    <div class="text-2xl font-bold text-[#056663] mt-1">
                        ₹{{ number_format($totalAmount, 2) }}
                    </div>

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

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

                <div class="flex gap-3">

                    <div class="text-red-600 text-xl">
                        ⚠
                    </div>

                    <div>

                        <h3 class="font-semibold text-red-800">
                            Transaction {{ $statusLabel }}
                        </h3>

                        <p class="mt-1 text-sm text-red-700">
                            This transaction is currently marked as
                            {{ strtolower($statusLabel) }}.
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================
             MAIN GRID
        ========================================================== --}}

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


            {{-- =====================================================
                 MAIN CONTENT
            ====================================================== --}}

            <div class="lg:col-span-2 space-y-6">


                {{-- =================================================
                     BUYER INFORMATION
                ================================================== --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-900">
                            Buyer Information
                        </h2>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">


                            <div>

                                <p class="text-xs text-gray-500">
                                    Buyer Name
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $buyerName }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Buyer ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    #{{ $transaction->buyer_id }}
                                </p>

                            </div>


                            @if($buyer?->email)

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Email
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $buyer->email }}
                                    </p>

                                </div>

                            @endif


                            @if($buyer?->phone)

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Phone
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $buyer->phone }}
                                    </p>

                                </div>

                            @elseif($buyer?->mobile)

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Phone
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $buyer->mobile }}
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SELLER INFORMATION
                ================================================== --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-900">
                            Seller Information
                        </h2>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">


                            <div>

                                <p class="text-xs text-gray-500">
                                    Seller / Company
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $sellerName }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Seller ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    #{{ $transaction->seller_id }}
                                </p>

                            </div>


                            @if($seller?->email)

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Email
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $seller->email }}
                                    </p>

                                </div>

                            @endif


                            @if($seller?->phone)

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Phone
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $seller->phone }}
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PROPERTY / LAND DETAILS
                ================================================== --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-900">
                            {{ $categoryLabel }} Details
                        </h2>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">


                            {{-- Listing --}}
                            <div>

                                <p class="text-xs text-gray-500">
                                    {{ $isProperty ? 'Property' : 'Land' }}
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing?->title ?? $transaction->product_name ?? '—' }}
                                </p>

                            </div>


                            {{-- Listing Type --}}
                            <div>

                                <p class="text-xs text-gray-500">
                                    Listing Type
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ ucfirst(
                                        $listing?->listing_type ?? '—'
                                    ) }}
                                </p>

                            </div>


                            {{-- Listing Price --}}
                            <div>

                                <p class="text-xs text-gray-500">
                                    Listing Price
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">

                                    @if($listing?->price !== null)

                                        ₹{{ number_format(
                                            (float) $listing->price,
                                            2
                                        ) }}

                                    @else

                                        —

                                    @endif

                                </p>

                            </div>


                            {{-- Price Unit --}}
                            <div>

                                <p class="text-xs text-gray-500">
                                    Price Unit
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing?->price_unit ?? '—' }}
                                </p>

                            </div>


                            {{-- Property Type --}}
                            @if($isProperty)

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Property Type
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $propertyType ?? '—' }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-gray-500">
                                        Property Area
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">

                                        {{ $propertyArea ?? '—' }}

                                        @if($propertyAreaUnit)
                                            {{ $propertyAreaUnit }}
                                        @endif

                                    </p>

                                </div>


                                <div class="sm:col-span-2">

                                    <p class="text-xs text-gray-500">
                                        Property Location
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $propertyLocation ?? '—' }}
                                    </p>

                                </div>

                            @endif


                            {{-- Land --}}
                            @if($isLand)

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Land Area
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">

                                        {{ $landArea ?? '—' }}

                                        @if($landAreaUnit)
                                            {{ $landAreaUnit }}
                                        @endif

                                    </p>

                                </div>


                                <div class="sm:col-span-2">

                                    <p class="text-xs text-gray-500">
                                        Land Location
                                    </p>

                                    <p class="mt-1 font-semibold text-gray-900">
                                        {{ $landLocation ?? '—' }}
                                    </p>

                                </div>

                            @endif


                            {{-- Listing City --}}
                            <div>

                                <p class="text-xs text-gray-500">
                                    City
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing?->city ?? '—' }}
                                </p>

                            </div>


                            {{-- State --}}
                            <div>

                                <p class="text-xs text-gray-500">
                                    State
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing?->state ?? '—' }}
                                </p>

                            </div>


                            {{-- Pincode --}}
                            <div>

                                <p class="text-xs text-gray-500">
                                    Pincode
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $listing?->pincode ?? '—' }}
                                </p>

                            </div>


                            {{-- Description --}}
                            <div class="sm:col-span-2">

                                <p class="text-xs text-gray-500">
                                    Description
                                </p>

                                <p class="mt-1 text-sm text-gray-700 leading-6">
                                    {{ $listing?->description ?? '—' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     TRANSACTION INFORMATION
                ================================================== --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-900">
                            Transaction Information
                        </h2>

                    </div>


                    <div class="p-6">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">


                            <div>

                                <p class="text-xs text-gray-500">
                                    Order Number
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $transaction->order_number }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Order ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    #{{ $transaction->id }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Category
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $categoryLabel }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Current Status
                                </p>

                                <span class="mt-1 inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Listing ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    #{{ $transaction->listing_id }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Enquiry ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $transaction->enquiry_id
                                        ? '#' . $transaction->enquiry_id
                                        : '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Requirement ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $transaction->requirement_id
                                        ? '#' . $transaction->requirement_id
                                        : '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Quotation ID
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $transaction->quotation_id
                                        ? '#' . $transaction->quotation_id
                                        : '—' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Payment Status
                                </p>

                                <p class="mt-1 font-semibold capitalize text-gray-900">
                                    {{ str_replace(
                                        '_',
                                        ' ',
                                        $transaction->payment_status ?? 'pending'
                                    ) }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Order Date
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ optional($transaction->ordered_at)
                                        ->format('d M Y, h:i A') ?? '—' }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     REQUIREMENT
                ================================================== --}}

                @if($transaction->requirement)

                    <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                        <div class="px-6 py-4 border-b border-gray-100">

                            <h2 class="text-lg font-bold text-gray-900">
                                Requirement Information
                            </h2>

                        </div>


                        <div class="p-6">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">


                                @if($transaction->requirement->name)

                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Requirement Name
                                        </p>

                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ $transaction->requirement->name }}
                                        </p>

                                    </div>

                                @endif


                                @if($transaction->requirement->requirement_type)

                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Requirement Type
                                        </p>

                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $transaction->requirement->requirement_type
                                                )
                                            ) }}
                                        </p>

                                    </div>

                                @endif


                                @if($transaction->requirement->quantity)

                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Quantity
                                        </p>

                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ $transaction->requirement->quantity }}
                                            {{ $transaction->requirement->unit ?? '' }}
                                        </p>

                                    </div>

                                @endif


                                @if($transaction->requirement->budget)

                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Budget
                                        </p>

                                        <p class="mt-1 font-semibold text-gray-900">
                                            ₹{{ number_format(
                                                (float) $transaction->requirement->budget,
                                                2
                                            ) }}
                                        </p>

                                    </div>

                                @endif


                                @if($transaction->requirement->delivery_location)

                                    <div class="sm:col-span-2">

                                        <p class="text-xs text-gray-500">
                                            Delivery Location
                                        </p>

                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ $transaction->requirement->delivery_location }}
                                        </p>

                                    </div>

                                @endif


                                @if($transaction->requirement->required_date)

                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Required Date
                                        </p>

                                        <p class="mt-1 font-semibold text-gray-900">
                                            {{ $transaction->requirement->required_date }}
                                        </p>

                                    </div>

                                @endif


                                @if($transaction->requirement->message)

                                    <div class="sm:col-span-2">

                                        <p class="text-xs text-gray-500">
                                            Requirement Message
                                        </p>

                                        <p class="mt-1 text-sm text-gray-700 leading-6">
                                            {{ $transaction->requirement->message }}
                                        </p>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     NOTES
                ================================================== --}}

                @if($transaction->notes)

                    <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                        <div class="px-6 py-4 border-b border-gray-100">

                            <h2 class="text-lg font-bold text-gray-900">
                                Transaction Notes
                            </h2>

                        </div>

                        <div class="p-6">

                            <p class="text-sm text-gray-700 leading-6 whitespace-pre-line">
                                {{ $transaction->notes }}
                            </p>

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     TRANSACTION TIMELINE
                ================================================== --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-900">
                            Transaction Timeline
                        </h2>

                    </div>


                    <div class="p-6">

                        <div class="space-y-6">


                            {{-- ORDERED --}}
                            <div class="flex gap-4">

                                <div class="w-9 h-9 shrink-0 rounded-full bg-[#056663] text-white flex items-center justify-center text-sm font-bold">
                                    1
                                </div>

                                <div>

                                    <h3 class="font-semibold text-gray-900">
                                        Transaction Initiated
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ optional($transaction->ordered_at)
                                            ->format('d M Y, h:i A') ?? '—' }}
                                    </p>

                                </div>

                            </div>


                            {{-- CONFIRMED --}}
                            <div class="flex gap-4">

                                <div class="w-9 h-9 shrink-0 rounded-full
                                    {{ $transaction->confirmed_at
                                        ? 'bg-[#056663] text-white'
                                        : 'bg-gray-100 text-gray-400' }}
                                    flex items-center justify-center text-sm font-bold">
                                    2
                                </div>

                                <div>

                                    <h3 class="font-semibold text-gray-900">
                                        Confirmed
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">

                                        @if($transaction->confirmed_at)

                                            {{ optional($transaction->confirmed_at)
                                                ->format('d M Y, h:i A') }}

                                        @else

                                            Not completed yet

                                        @endif

                                    </p>

                                </div>

                            </div>


                            {{-- NEGOTIATION --}}
                            <div class="flex gap-4">

                                <div class="w-9 h-9 shrink-0 rounded-full
                                    {{ $transaction->negotiation_at
                                        ? 'bg-[#056663] text-white'
                                        : 'bg-gray-100 text-gray-400' }}
                                    flex items-center justify-center text-sm font-bold">
                                    3
                                </div>

                                <div>

                                    <h3 class="font-semibold text-gray-900">
                                        Negotiation
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">

                                        @if($transaction->negotiation_at)

                                            {{ optional($transaction->negotiation_at)
                                                ->format('d M Y, h:i A') }}

                                        @else

                                            Not completed yet

                                        @endif

                                    </p>

                                </div>

                            </div>


                            {{-- DOCUMENTATION --}}
                            <div class="flex gap-4">

                                <div class="w-9 h-9 shrink-0 rounded-full
                                    {{ $transaction->documentation_at
                                        ? 'bg-[#056663] text-white'
                                        : 'bg-gray-100 text-gray-400' }}
                                    flex items-center justify-center text-sm font-bold">
                                    4
                                </div>

                                <div>

                                    <h3 class="font-semibold text-gray-900">
                                        Documentation
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">

                                        @if($transaction->documentation_at)

                                            {{ optional($transaction->documentation_at)
                                                ->format('d M Y, h:i A') }}

                                        @else

                                            Not completed yet

                                        @endif

                                    </p>

                                </div>

                            </div>


                            {{-- COMPLETED --}}
                            <div class="flex gap-4">

                                <div class="w-9 h-9 shrink-0 rounded-full
                                    {{ $transaction->completed_at
                                        ? 'bg-green-600 text-white'
                                        : 'bg-gray-100 text-gray-400' }}
                                    flex items-center justify-center text-sm font-bold">

                                    @if($transaction->completed_at)
                                        ✓
                                    @else
                                        5
                                    @endif

                                </div>

                                <div>

                                    <h3 class="font-semibold text-gray-900">
                                        Completed
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">

                                        @if($transaction->completed_at)

                                            {{ optional($transaction->completed_at)
                                                ->format('d M Y, h:i A') }}

                                        @else

                                            Not completed yet

                                        @endif

                                    </p>

                                </div>

                            </div>


                            {{-- CANCELLED --}}
                            @if($transaction->cancelled_at)

                                <div class="flex gap-4">

                                    <div class="w-9 h-9 shrink-0 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-bold">
                                        !
                                    </div>

                                    <div>

                                        <h3 class="font-semibold text-red-700">
                                            Cancelled
                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ optional($transaction->cancelled_at)
                                                ->format('d M Y, h:i A') }}
                                        </p>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 SIDEBAR
            ====================================================== --}}

            <div class="space-y-6">


                {{-- =================================================
                     TRANSACTION SUMMARY
                ================================================== --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-900">
                            Transaction Summary
                        </h2>

                    </div>


                    <div class="p-6 space-y-4">


                        <div class="flex justify-between text-sm">

                            <span class="text-gray-500">
                                Subtotal
                            </span>

                            <span class="font-medium text-gray-900">
                                ₹{{ number_format($subtotal, 2) }}
                            </span>

                        </div>


                        <div class="flex justify-between text-sm">

                            <span class="text-gray-500">
                                Delivery Charges
                            </span>

                            <span class="font-medium text-gray-900">
                                ₹{{ number_format($deliveryCharges, 2) }}
                            </span>

                        </div>


                        <div class="flex justify-between text-sm">

                            <span class="text-gray-500">
                                Other Charges
                            </span>

                            <span class="font-medium text-gray-900">
                                ₹{{ number_format($otherCharges, 2) }}
                            </span>

                        </div>


                        <div class="flex justify-between text-sm">

                            <span class="text-gray-500">
                                Tax
                            </span>

                            <span class="font-medium text-gray-900">
                                ₹{{ number_format($tax, 2) }}
                            </span>

                        </div>


                        <div class="pt-4 border-t border-gray-100 flex justify-between">

                            <span class="font-bold text-gray-900">
                                Total
                            </span>

                            <span class="font-bold text-[#056663]">
                                ₹{{ number_format($totalAmount, 2) }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PAYMENT
                ================================================== --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-900">
                            Payment Information
                        </h2>

                    </div>


                    <div class="p-6 space-y-4">


                        <div>

                            <p class="text-xs text-gray-500">
                                Payment Status
                            </p>

                            <p class="mt-1 font-semibold text-gray-900 capitalize">
                                {{ str_replace(
                                    '_',
                                    ' ',
                                    $transaction->payment_status ?? 'pending'
                                ) }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Total Amount
                            </p>

                            <p class="mt-1 text-xl font-bold text-[#056663]">
                                ₹{{ number_format($totalAmount, 2) }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     TRANSACTION IDs
                ================================================== --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm">

                    <div class="px-6 py-4 border-b border-gray-100">

                        <h2 class="text-lg font-bold text-gray-900">
                            Reference Information
                        </h2>

                    </div>


                    <div class="p-6 space-y-4 text-sm">


                        <div>

                            <p class="text-xs text-gray-500">
                                Transaction ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $transaction->id }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Listing ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                #{{ $transaction->listing_id }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Enquiry ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $transaction->enquiry_id
                                    ? '#' . $transaction->enquiry_id
                                    : 'Not available' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Requirement ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $transaction->requirement_id
                                    ? '#' . $transaction->requirement_id
                                    : 'Not available' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Quotation ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $transaction->quotation_id
                                    ? '#' . $transaction->quotation_id
                                    : 'Not available' }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     COMPLETE TRANSACTION
                ================================================== --}}

                @if($statusKey === 'documentation')

                    <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            Complete Transaction
                        </h3>

                        <p class="mt-2 text-sm text-gray-500 leading-6">
                            Documentation has been completed. You can now mark this transaction as completed.
                        </p>


                        <form
                            method="POST"
                            action="{{ route(
                                'buyer.transactions.complete',
                                $transaction->id
                            ) }}"
                            class="mt-5"
                        >

                            @csrf

                            @method('PATCH')


                            <button
                                type="submit"
                                onclick="return confirm('Are you sure you want to complete this transaction?')"
                                class="w-full inline-flex items-center justify-center px-5 py-3
                                       bg-[#056663] hover:bg-[#045552]
                                       text-white text-sm font-semibold
                                       rounded-lg transition"
                            >
                                Mark Transaction as Completed
                            </button>

                        </form>

                    </div>

                @endif


                {{-- =================================================
                     REVIEW & RATING
                ================================================== --}}

                @if($statusKey === 'completed')

                    <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            Review & Rating
                        </h3>


                        @if(!$review)

                            <p class="mt-2 text-sm text-gray-500 leading-6">
                                Your transaction is completed. Share your experience with the seller.
                            </p>


                            <a
                                href="{{ route(
                                    'buyer.reviews.create',
                                    ['orderId' => $transaction->id]
                                ) }}"
                                class="mt-5 inline-flex w-full items-center justify-center
                                       px-5 py-3 bg-[#056663]
                                       hover:bg-[#045552]
                                       text-white text-sm font-semibold
                                       rounded-lg transition"
                            >
                                Write a Review
                            </a>


                        @elseif($review->status === 'pending')

                            <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4">

                                <p class="text-sm font-semibold text-yellow-800">
                                    Review Submitted
                                </p>

                                <p class="mt-1 text-sm text-yellow-700">
                                    Your review is waiting for admin approval.
                                </p>

                            </div>


                        @elseif($review->status === 'approved')

                            <div class="mt-4 rounded-lg border border-green-200 bg-green-50 p-4">

                                <p class="text-sm font-semibold text-green-800">
                                    Your Review
                                </p>


                                <div class="mt-2 flex items-center gap-1">

                                    @for($i = 1; $i <= 5; $i++)

                                        <svg
                                            class="w-5 h-5 {{ $i <= (int) $review->rating
                                                ? 'text-yellow-400'
                                                : 'text-gray-300' }}"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >

                                            <path d="M12 2.5l2.94 5.96 6.58.96-4.76 4.64 1.12 6.55L12 17.52l-5.88 3.09 1.12-6.55-4.76-4.64 6.58-.96L12 2.5z"/>

                                        </svg>

                                    @endfor

                                </div>


                                @if($review->review)

                                    <div class="mt-3 rounded-lg bg-white border border-green-100 p-3">

                                        <p class="text-sm text-gray-700 leading-6">
                                            {{ $review->review }}
                                        </p>

                                    </div>

                                @endif

                            </div>


                        @elseif($review->status === 'rejected')

                            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4">

                                <p class="text-sm font-semibold text-red-800">
                                    Review Rejected
                                </p>

                                <p class="mt-1 text-sm text-red-700">
                                    Your previous review was rejected by the administrator.
                                </p>

                            </div>

                        @endif

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-buyer-layout>