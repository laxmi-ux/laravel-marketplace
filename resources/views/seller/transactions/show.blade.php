<x-seller-layout>

    @php

        /*
        |--------------------------------------------------------------------------
        | LISTING
        |--------------------------------------------------------------------------
        */

        $listing = $transaction->listing;


        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        $category = strtolower(
            trim((string) $transaction->category)
        );

        $isProperty = in_array(
            $category,
            ['property', 'properties'],
            true
        );

        $isLand = in_array(
            $category,
            ['land'],
            true
        );


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $status = strtolower(
            trim((string) $transaction->order_status)
        );

        $statusClasses = match ($status) {

            'pending' =>
                'bg-yellow-50 text-yellow-700 border-yellow-200',

            'confirmed' =>
                'bg-blue-50 text-blue-700 border-blue-200',

            'negotiation' =>
                'bg-purple-50 text-purple-700 border-purple-200',

            'documentation' =>
                'bg-orange-50 text-orange-700 border-orange-200',

            'completed' =>
                'bg-green-50 text-green-700 border-green-200',

            'rejected', 'cancelled' =>
                'bg-red-50 text-red-700 border-red-200',

            default =>
                'bg-gray-50 text-gray-700 border-gray-200',
        };


        $statusLabel = match ($status) {

            'pending' =>
                'Pending',

            'confirmed' =>
                'Confirmed',

            'negotiation' =>
                'Negotiation',

            'documentation' =>
                'Documentation',

            'completed' =>
                'Completed',

            'rejected' =>
                'Rejected',

            'cancelled' =>
                'Cancelled',

            default =>
                ucfirst($status ?: 'Unknown'),
        };


        /*
        |--------------------------------------------------------------------------
        | FINANCIAL VALUES
        |--------------------------------------------------------------------------
        */

        $quantity = (float) (
            $transaction->quantity ?? 0
        );

        $unitPrice = (float) (
            $transaction->unit_price ?? 0
        );

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
        | PAYMENT
        |--------------------------------------------------------------------------
        */

        $paymentStatus = strtolower(
            trim((string) (
                $transaction->payment_status ?? 'pending'
            ))
        );


        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        $listingLocation = collect([
            $listing?->city,
            $listing?->state,
            $listing?->pincode,
        ])
            ->filter()
            ->implode(', ');

    @endphp


    <div class="p-6">


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <div>

                <a
                    href="{{ route('seller.transactions.index') }}"
                    class="inline-flex items-center text-sm text-[#056663] hover:underline mb-3"
                >
                    ← Back to Transactions
                </a>

                <h1 class="text-2xl font-bold text-[#056663]">
                    Transaction Details
                </h1>

                <p class="text-gray-600 mt-1">
                    {{ $transaction->order_number }}
                </p>

            </div>


            <div>

                <span
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border {{ $statusClasses }}"
                >
                    {{ $statusLabel }}
                </span>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                {{ session('error') }}
            </div>

        @endif


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


            {{-- ===================================================== --}}
            {{-- MAIN INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="lg:col-span-2 space-y-6">


                {{-- ================================================= --}}
                {{-- BUYER INFORMATION --}}
                {{-- ================================================= --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-[#DDE9E8] bg-[#F7FAFA]">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                            <div>

                                <h2 class="text-lg font-semibold text-[#056663]">
                                    Buyer Information
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    Customer details associated with this transaction
                                </p>

                            </div>


                            @if($transaction->buyer)

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full
                                           text-xs font-semibold bg-[#E8F5F4]
                                           text-[#056663]"
                                >
                                    Buyer #{{ $transaction->buyer->id }}
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="p-6">

                        @if($transaction->buyer)

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">


                                {{-- Buyer Name --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Buyer Name
                                    </p>

                                    <p class="font-semibold text-gray-900 mt-1">
                                        {{ $transaction->buyer->name ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- Email --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Email Address
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1 break-all">
                                        {{ $transaction->buyer->email ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- Phone --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Phone Number
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">
                                        {{ $transaction->buyer->phone ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- Buyer ID --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Buyer ID
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">
                                        #{{ $transaction->buyer->id }}
                                    </p>

                                </div>


                                {{-- Transaction Date --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Transaction Date
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">

                                        @if($transaction->ordered_at)

                                            {{ $transaction->ordered_at->format('d M Y') }}

                                        @elseif($transaction->created_at)

                                            {{ $transaction->created_at->format('d M Y') }}

                                        @else

                                            N/A

                                        @endif

                                    </p>

                                </div>


                                {{-- Transaction Time --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Transaction Time
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">

                                        @if($transaction->ordered_at)

                                            {{ $transaction->ordered_at->format('h:i A') }}

                                        @else

                                            N/A

                                        @endif

                                    </p>

                                </div>

                            </div>


                            {{-- Contact Buyer --}}
                            <div class="mt-6 pt-5 border-t border-[#DDE9E8]">

                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">
                                    Contact Buyer
                                </p>


                                <div class="flex flex-wrap gap-3">


                                    @if($transaction->buyer->email)

                                        <a
                                            href="mailto:{{ $transaction->buyer->email }}"
                                            class="inline-flex items-center gap-2 px-4 py-2
                                                   rounded-lg bg-[#E8F5F4] text-[#056663]
                                                   text-sm font-medium hover:bg-[#D9EFED] transition"
                                        >
                                            ✉ Email Buyer
                                        </a>

                                    @endif


                                    @if($transaction->buyer->phone)

                                        <a
                                            href="tel:{{ $transaction->buyer->phone }}"
                                            class="inline-flex items-center gap-2 px-4 py-2
                                                   rounded-lg bg-[#E8F5F4] text-[#056663]
                                                   text-sm font-medium hover:bg-[#D9EFED] transition"
                                        >
                                            ☎ Call Buyer
                                        </a>

                                    @endif

                                </div>

                            </div>


                        @else

                            <div class="rounded-lg bg-yellow-50 border border-yellow-200
                                        text-yellow-700 px-4 py-3 text-sm">

                                Buyer information is not available for this transaction.

                            </div>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PROPERTY / LAND DETAILS --}}
                {{-- ================================================= --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm overflow-hidden">

                    <div class="px-6 py-5 border-b border-[#DDE9E8] bg-[#F7FAFA]">

                        <h2 class="text-lg font-semibold text-[#056663]">

                            {{ $isProperty
                                ? 'Property Details'
                                : ($isLand ? 'Land Details' : 'Listing Details')
                            }}

                        </h2>

                    </div>


                    <div class="p-6">


                        @if($listing)

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                                {{-- Listing Title --}}
                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        {{ $isProperty ? 'Property' : ($isLand ? 'Land' : 'Listing') }}
                                    </p>

                                    <p class="font-semibold text-gray-900 mt-1">
                                        {{ $listing->title ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- Listing Type --}}
                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Listing Type
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">
                                        {{ ucfirst($listing->listing_type ?? 'N/A') }}
                                    </p>

                                </div>


                                {{-- Listing Price --}}
                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Listed Price
                                    </p>

                                    <p class="font-semibold text-gray-900 mt-1">
                                        ₹{{ number_format((float) ($listing->price ?? 0), 2) }}
                                    </p>

                                </div>


                                {{-- Price Unit --}}
                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Price Unit
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">
                                        {{ $listing->price_unit ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- City --}}
                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        City
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">
                                        {{ $listing->city ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- State --}}
                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        State
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">
                                        {{ $listing->state ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- Pincode --}}
                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Pincode
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">
                                        {{ $listing->pincode ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- Transaction Number --}}
                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Transaction Number
                                    </p>

                                    <p class="font-semibold text-[#056663] mt-1">
                                        {{ $transaction->order_number }}
                                    </p>

                                </div>


                                {{-- Location --}}
                                <div class="md:col-span-2">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        {{ $isProperty ? 'Property Location' : ($isLand ? 'Land Location' : 'Listing Location') }}
                                    </p>

                                    <p class="font-medium text-gray-900 mt-1">
                                        {{ $listingLocation ?: 'N/A' }}
                                    </p>

                                </div>


                                {{-- Description --}}
                                <div class="md:col-span-2">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Description
                                    </p>

                                    <p class="font-medium text-gray-700 mt-1 leading-6">
                                        {{ $listing->description ?? 'N/A' }}
                                    </p>

                                </div>

                            </div>


                        @else

                            <div class="rounded-lg bg-yellow-50 border border-yellow-200
                                        text-yellow-700 px-4 py-3 text-sm">

                                Listing details are not available for this transaction.

                            </div>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TRANSACTION SUMMARY --}}
                {{-- ================================================= --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm overflow-hidden">


                    {{-- Summary Header --}}
                    <div class="px-6 py-5 border-b border-[#DDE9E8] bg-[#F7FAFA]">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                            <div>

                                <h2 class="text-lg font-semibold text-[#056663]">
                                    Transaction Summary
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    Complete financial breakdown of this transaction
                                </p>

                            </div>


                            <span class="text-xs font-medium text-gray-500">
                                {{ $transaction->order_number }}
                            </span>

                        </div>

                    </div>


                    <div class="p-6">


                        {{-- ================================================= --}}
                        {{-- ITEM INFORMATION --}}
                        {{-- ================================================= --}}

                        <div class="pb-5 border-b border-[#DDE9E8]">

                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">


                                <div>

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $transaction->product_name
                                            ?? $listing?->title
                                            ?? 'Transaction Item'
                                        }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ ucfirst($category ?: 'Marketplace Transaction') }}
                                    </p>

                                </div>


                                <div class="text-left sm:text-right">

                                    <p class="text-xs text-gray-500">
                                        Transaction ID
                                    </p>

                                    <p class="text-sm font-semibold text-[#056663] mt-1">
                                        {{ $transaction->order_number }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- QUANTITY / UNIT PRICE / SUBTOTAL --}}
                        {{-- ================================================= --}}

                        <div class="py-5 border-b border-[#DDE9E8]">

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">


                                {{-- Quantity --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Quantity
                                    </p>

                                    <p class="text-lg font-semibold text-gray-900 mt-1">

                                        @if($quantity > 0)

                                            {{ rtrim(
                                                rtrim(number_format($quantity, 2), '0'),
                                                '.'
                                            ) }}

                                            {{ $transaction->unit ?? '' }}

                                        @else

                                            N/A

                                        @endif

                                    </p>

                                </div>


                                {{-- Unit Price --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Unit Price
                                    </p>

                                    <p class="text-lg font-semibold text-gray-900 mt-1">
                                        ₹{{ number_format($unitPrice, 2) }}
                                    </p>

                                </div>


                                {{-- Subtotal --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Subtotal
                                    </p>

                                    <p class="text-lg font-semibold text-[#056663] mt-1">
                                        ₹{{ number_format($subtotal, 2) }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PRICE BREAKDOWN --}}
                        {{-- ================================================= --}}

                        <div class="py-5 border-b border-[#DDE9E8]">

                            <h3 class="text-sm font-semibold text-gray-900 mb-4">
                                Price Breakdown
                            </h3>


                            <div class="space-y-4">


                                {{-- Subtotal --}}
                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-sm text-gray-600">
                                            Item Subtotal
                                        </p>

                                        @if($quantity > 0 && $unitPrice > 0)

                                            <p class="text-xs text-gray-400 mt-0.5">

                                                {{ rtrim(
                                                    rtrim(number_format($quantity, 2), '0'),
                                                    '.'
                                                ) }}

                                                {{ $transaction->unit ?? '' }}

                                                ×

                                                ₹{{ number_format($unitPrice, 2) }}

                                            </p>

                                        @endif

                                    </div>


                                    <p class="text-sm font-semibold text-gray-900">
                                        ₹{{ number_format($subtotal, 2) }}
                                    </p>

                                </div>


                                {{-- Delivery --}}
                                <div class="flex items-center justify-between">

                                    <span class="text-sm text-gray-600">
                                        Delivery Charges
                                    </span>

                                    <span class="text-sm font-medium text-gray-900">

                                        @if($deliveryCharges > 0)

                                            ₹{{ number_format($deliveryCharges, 2) }}

                                        @else

                                            <span class="text-gray-400">
                                                ₹0.00
                                            </span>

                                        @endif

                                    </span>

                                </div>


                                {{-- Other Charges --}}
                                <div class="flex items-center justify-between">

                                    <span class="text-sm text-gray-600">
                                        Other Charges
                                    </span>

                                    <span class="text-sm font-medium text-gray-900">

                                        @if($otherCharges > 0)

                                            ₹{{ number_format($otherCharges, 2) }}

                                        @else

                                            <span class="text-gray-400">
                                                ₹0.00
                                            </span>

                                        @endif

                                    </span>

                                </div>


                                {{-- Tax --}}
                                <div class="flex items-center justify-between">

                                    <div>

                                        <span class="text-sm text-gray-600">
                                            Tax
                                        </span>

                                        @if($tax > 0)

                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Applicable tax
                                            </p>

                                        @endif

                                    </div>


                                    <span class="text-sm font-medium text-gray-900">

                                        @if($tax > 0)

                                            ₹{{ number_format($tax, 2) }}

                                        @else

                                            <span class="text-gray-400">
                                                ₹0.00
                                            </span>

                                        @endif

                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- TOTAL --}}
                        {{-- ================================================= --}}

                        <div class="py-5">

                            <div class="rounded-xl bg-[#E8F5F4] border border-[#CFE8E6] p-5">

                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                                    <div>

                                        <p class="text-sm font-semibold text-[#056663]">
                                            Total Transaction Amount
                                        </p>

                                        <p class="text-xs text-gray-500 mt-1">
                                            Includes subtotal, charges and applicable tax
                                        </p>

                                    </div>


                                    <p class="text-2xl font-bold text-[#056663]">
                                        ₹{{ number_format($totalAmount, 2) }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAYMENT / STATUS --}}
                        {{-- ================================================= --}}

                        <div class="pt-5 border-t border-[#DDE9E8]">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                                {{-- Payment Status --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Payment Status
                                    </p>

                                    <div class="mt-2">

                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full
                                                   text-xs font-semibold border
                                                   {{
                                                       $paymentStatus === 'paid'
                                                           ? 'bg-green-50 text-green-700 border-green-200'
                                                           : (
                                                               $paymentStatus === 'failed'
                                                                   ? 'bg-red-50 text-red-700 border-red-200'
                                                                   : 'bg-yellow-50 text-yellow-700 border-yellow-200'
                                                           )
                                                   }}"
                                        >
                                            {{ ucfirst($paymentStatus) }}
                                        </span>

                                    </div>

                                </div>


                                {{-- Transaction Status --}}
                                <div class="rounded-lg bg-[#F7FAFA] p-4">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Transaction Status
                                    </p>

                                    <div class="mt-2">

                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full
                                                   text-xs font-semibold border {{ $statusClasses }}"
                                        >
                                            {{ $statusLabel }}
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- DATE / BUYER --}}
                        {{-- ================================================= --}}

                        <div class="mt-5 pt-5 border-t border-[#DDE9E8]">

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">


                                <div>

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Transaction Created
                                    </p>

                                    <p class="text-sm font-medium text-gray-900 mt-1">

                                        @if($transaction->ordered_at)

                                            {{ $transaction->ordered_at->format('d M Y, h:i A') }}

                                        @elseif($transaction->created_at)

                                            {{ $transaction->created_at->format('d M Y, h:i A') }}

                                        @else

                                            N/A

                                        @endif

                                    </p>

                                </div>


                                <div class="sm:text-right">

                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        Buyer
                                    </p>

                                    <p class="text-sm font-medium text-gray-900 mt-1">
                                        {{ $transaction->buyer->name ?? 'N/A' }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TRANSACTION TIMELINE --}}
                {{-- ================================================= --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm p-6">

                    <h2 class="text-lg font-semibold text-[#056663] mb-6">
                        Transaction Timeline
                    </h2>


                    <div class="space-y-7">


                        {{-- Pending --}}
                        <div class="flex gap-4">

                            <div
                                class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0
                                {{ $transaction->ordered_at
                                    ? 'bg-[#056663]'
                                    : 'bg-gray-300' }}"
                            ></div>


                            <div>

                                <p class="font-semibold text-gray-900">
                                    Pending
                                </p>

                                <p class="text-sm text-gray-500">
                                    Transaction created
                                </p>

                                @if($transaction->ordered_at)

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $transaction->ordered_at->format('d M Y, h:i A') }}
                                    </p>

                                @endif

                            </div>

                        </div>


                        {{-- Confirmed --}}
                        <div class="flex gap-4">

                            <div
                                class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0
                                {{ $transaction->confirmed_at
                                    ? 'bg-[#056663]'
                                    : 'bg-gray-300' }}"
                            ></div>


                            <div>

                                <p class="font-semibold text-gray-900">
                                    Confirmed
                                </p>

                                @if($transaction->confirmed_at)

                                    <p class="text-sm text-gray-500">
                                        Transaction confirmed by seller
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $transaction->confirmed_at->format('d M Y, h:i A') }}
                                    </p>

                                @else

                                    <p class="text-sm text-gray-400">
                                        Waiting for confirmation
                                    </p>

                                @endif

                            </div>

                        </div>


                        {{-- Negotiation --}}
                        <div class="flex gap-4">

                            <div
                                class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0
                                {{ $transaction->negotiation_at
                                    ? 'bg-[#056663]'
                                    : 'bg-gray-300' }}"
                            ></div>


                            <div>

                                <p class="font-semibold text-gray-900">
                                    Negotiation
                                </p>

                                @if($transaction->negotiation_at)

                                    <p class="text-sm text-gray-500">
                                        Negotiation started
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $transaction->negotiation_at->format('d M Y, h:i A') }}
                                    </p>

                                @else

                                    <p class="text-sm text-gray-400">
                                        Not started
                                    </p>

                                @endif

                            </div>

                        </div>


                        {{-- Documentation --}}
                        <div class="flex gap-4">

                            <div
                                class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0
                                {{ $transaction->documentation_at
                                    ? 'bg-[#056663]'
                                    : 'bg-gray-300' }}"
                            ></div>


                            <div>

                                <p class="font-semibold text-gray-900">
                                    Documentation
                                </p>

                                @if($transaction->documentation_at)

                                    <p class="text-sm text-gray-500">
                                        Documentation started
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $transaction->documentation_at->format('d M Y, h:i A') }}
                                    </p>

                                @else

                                    <p class="text-sm text-gray-400">
                                        Not started
                                    </p>

                                @endif

                            </div>

                        </div>


                        {{-- Completed --}}
                        <div class="flex gap-4">

                            <div
                                class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0
                                {{ $transaction->completed_at
                                    ? 'bg-[#056663]'
                                    : 'bg-gray-300' }}"
                            ></div>


                            <div>

                                <p class="font-semibold text-gray-900">
                                    Completed
                                </p>

                                @if($transaction->completed_at)

                                    <p class="text-sm text-green-600">
                                        Transaction completed
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $transaction->completed_at->format('d M Y, h:i A') }}
                                    </p>

                                @else

                                    <p class="text-sm text-gray-400">
                                        Not completed
                                    </p>

                                @endif

                            </div>

                        </div>


                        {{-- Rejected --}}
                        @if($transaction->rejected_at)

                            <div class="flex gap-4">

                                <div class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0 bg-red-500"></div>

                                <div>

                                    <p class="font-semibold text-red-700">
                                        Rejected
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $transaction->rejected_at->format('d M Y, h:i A') }}
                                    </p>

                                </div>

                            </div>

                        @endif


                        {{-- Cancelled --}}
                        @if($transaction->cancelled_at)

                            <div class="flex gap-4">

                                <div class="w-3 h-3 rounded-full mt-1.5 flex-shrink-0 bg-red-500"></div>

                                <div>

                                    <p class="font-semibold text-red-700">
                                        Cancelled
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $transaction->cancelled_at->format('d M Y, h:i A') }}
                                    </p>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT SIDEBAR --}}
            {{-- ===================================================== --}}

            <div class="space-y-6">


                {{-- ================================================= --}}
                {{-- TRANSACTION STATUS --}}
                {{-- ================================================= --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm p-6">

                    <h2 class="text-lg font-semibold text-[#056663] mb-5">
                        Transaction Status
                    </h2>

                    <div class="text-center">

                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full
                                   text-sm font-semibold border {{ $statusClasses }}"
                        >
                            {{ $statusLabel }}
                        </span>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ACTIONS --}}
                {{-- ================================================= --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm p-6">

                    <h2 class="text-lg font-semibold text-[#056663] mb-5">
                        Actions
                    </h2>


                    <div class="space-y-3">


                        {{-- Pending --}}
                        @if($status === 'pending')


                            <form
                                method="POST"
                                action="{{ route('seller.transactions.accept', $transaction->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="w-full px-4 py-3 bg-[#056663] text-white
                                           rounded-lg font-medium hover:bg-[#045450] transition"
                                >
                                    Accept Transaction
                                </button>

                            </form>


                            <form
                                method="POST"
                                action="{{ route('seller.transactions.reject', $transaction->id) }}"
                                onsubmit="return confirm('Are you sure you want to reject this transaction?');"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="w-full px-4 py-3 bg-red-600 text-white
                                           rounded-lg font-medium hover:bg-red-700 transition"
                                >
                                    Reject Transaction
                                </button>

                            </form>


                        {{-- Confirmed --}}
                        @elseif($status === 'confirmed')


                            <form
                                method="POST"
                                action="{{ route('seller.transactions.negotiation', $transaction->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="w-full px-4 py-3 bg-[#056663] text-white
                                           rounded-lg font-medium hover:bg-[#045450] transition"
                                >
                                    Start Negotiation
                                </button>

                            </form>


                        {{-- Negotiation --}}
                        @elseif($status === 'negotiation')


                            <form
                                method="POST"
                                action="{{ route('seller.transactions.documentation', $transaction->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="w-full px-4 py-3 bg-[#056663] text-white
                                           rounded-lg font-medium hover:bg-[#045450] transition"
                                >
                                    Move to Documentation
                                </button>

                            </form>


                        {{-- Documentation --}}
                        @elseif($status === 'documentation')


                            <form
                                method="POST"
                                action="{{ route('seller.transactions.complete', $transaction->id) }}"
                                onsubmit="return confirm('Are you sure you want to complete this transaction?');"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="w-full px-4 py-3 bg-green-600 text-white
                                           rounded-lg font-medium hover:bg-green-700 transition"
                                >
                                    Complete Transaction
                                </button>

                            </form>


                        {{-- Completed --}}
                        @elseif($status === 'completed')


                            <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-center">

                                <p class="font-semibold text-green-700">
                                    Transaction Completed
                                </p>

                                <p class="text-sm text-green-600 mt-1">
                                    This transaction has been successfully completed.
                                </p>

                            </div>


                        {{-- Rejected --}}
                        @elseif($status === 'rejected')


                            <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-center">

                                <p class="font-semibold text-red-700">
                                    Transaction Rejected
                                </p>

                                <p class="text-sm text-red-600 mt-1">
                                    This transaction was rejected.
                                </p>

                            </div>


                        {{-- Cancelled --}}
                        @elseif($status === 'cancelled')


                            <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-center">

                                <p class="font-semibold text-red-700">
                                    Transaction Cancelled
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PAYMENT INFORMATION --}}
                {{-- ================================================= --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm p-6">

                    <h2 class="text-lg font-semibold text-[#056663] mb-5">
                        Payment Information
                    </h2>


                    <div class="space-y-5">


                        {{-- Payment Status --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Payment Status
                            </p>

                            <span
                                class="inline-flex mt-2 px-3 py-1 rounded-full
                                       text-xs font-semibold border
                                       {{
                                           $paymentStatus === 'paid'
                                               ? 'bg-green-50 text-green-700 border-green-200'
                                               : (
                                                   $paymentStatus === 'failed'
                                                       ? 'bg-red-50 text-red-700 border-red-200'
                                                       : 'bg-yellow-50 text-yellow-700 border-yellow-200'
                                               )
                                       }}"
                            >
                                {{ ucfirst($paymentStatus) }}
                            </span>

                        </div>


                        {{-- Amount --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Transaction Amount
                            </p>

                            <p class="text-xl font-bold text-[#056663] mt-1">
                                ₹{{ number_format($totalAmount, 2) }}
                            </p>

                        </div>


                        {{-- Payment Note --}}
                        <div class="pt-4 border-t border-[#DDE9E8]">

                            @if($paymentStatus === 'paid')

                                <p class="text-sm text-green-600">
                                    Payment has been completed.
                                </p>

                            @elseif($paymentStatus === 'failed')

                                <p class="text-sm text-red-600">
                                    Payment failed for this transaction.
                                </p>

                            @else

                                <p class="text-sm text-yellow-600">
                                    Payment is currently pending.
                                </p>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TRANSACTION INFORMATION --}}
                {{-- ================================================= --}}

                <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm p-6">

                    <h2 class="text-lg font-semibold text-[#056663] mb-5">
                        Transaction Information
                    </h2>


                    <div class="space-y-5">


                        {{-- Transaction Number --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Transaction Number
                            </p>

                            <p class="font-semibold text-[#056663] mt-1 break-all">
                                {{ $transaction->order_number }}
                            </p>

                        </div>


                        {{-- Buyer --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Buyer
                            </p>

                            <p class="font-medium text-gray-900 mt-1">
                                {{ $transaction->buyer->name ?? 'N/A' }}
                            </p>

                        </div>


                        {{-- Transaction Type --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Transaction Type
                            </p>

                            <p class="font-medium text-gray-900 mt-1">
                                {{ $isProperty
                                    ? 'Property'
                                    : ($isLand ? 'Land' : ucfirst($category ?: 'Transaction'))
                                }}
                            </p>

                        </div>


                        {{-- Listing --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Listing
                            </p>

                            <p class="font-medium text-gray-900 mt-1">
                                {{ $listing?->title ?? 'N/A' }}
                            </p>

                        </div>


                        {{-- Listing ID --}}
                        @if($listing)

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wide">
                                    Listing ID
                                </p>

                                <p class="font-medium text-gray-900 mt-1">
                                    #{{ $listing->id }}
                                </p>

                            </div>

                        @endif


                        {{-- Transaction Status --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Status
                            </p>

                            <span
                                class="inline-flex mt-2 px-3 py-1 rounded-full
                                       text-xs font-semibold border {{ $statusClasses }}"
                            >
                                {{ $statusLabel }}
                            </span>

                        </div>


                        {{-- Payment Status --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Payment Status
                            </p>

                            <p class="font-medium text-gray-900 mt-1 capitalize">
                                {{ $paymentStatus }}
                            </p>

                        </div>


                        {{-- Created --}}
                        <div>

                            <p class="text-xs text-gray-500 uppercase tracking-wide">
                                Created
                            </p>

                            <p class="font-medium text-gray-900 mt-1">

                                @if($transaction->ordered_at)

                                    {{ $transaction->ordered_at->format('d M Y, h:i A') }}

                                @elseif($transaction->created_at)

                                    {{ $transaction->created_at->format('d M Y, h:i A') }}

                                @else

                                    N/A

                                @endif

                            </p>

                        </div>


                        {{-- Completed --}}
                        @if($transaction->completed_at)

                            <div>

                                <p class="text-xs text-gray-500 uppercase tracking-wide">
                                    Completed
                                </p>

                                <p class="font-medium text-green-600 mt-1">
                                    {{ $transaction->completed_at->format('d M Y, h:i A') }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


            </div>

        </div>

    </div>

</x-seller-layout>