<x-buyer-layout>

    @php

        /*
        |--------------------------------------------------------------------------
        | Listing
        |--------------------------------------------------------------------------
        */

        $listing = $enquiry->listing;


        /*
        |--------------------------------------------------------------------------
        | Category Detection
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Listing has category_id.
        | Do NOT use $listing->category.
        |
        */

        $categoryId = (int) data_get(
            $listing,
            'category_id'
        );


        $categoryName = match ($categoryId) {

            1 => 'Properties',

            2 => 'Land',

            3 => 'Products',

            4 => 'Services',

            5 => 'Projects',

            default => 'Other',
        };


        $category = strtolower(
            trim($categoryName)
        );


        $isProduct = str_contains(
            $category,
            'product'
        );


        $isProperty = str_contains(
            $category,
            'propert'
        );


        $isLand = str_contains(
            $category,
            'land'
        );


        $isService = str_contains(
            $category,
            'service'
        );


        $isProject = str_contains(
            $category,
            'project'
        );


        /*
        |--------------------------------------------------------------------------
        | Listing Title
        |--------------------------------------------------------------------------
        */

        $listingTitle =
            data_get($listing, 'title')
            ?? data_get($listing, 'name')
            ?? data_get($listing, 'product_name')
            ?? 'Listing';


        /*
        |--------------------------------------------------------------------------
        | Quantity Based Categories
        |--------------------------------------------------------------------------
        */

        $isQuantityBased =
            in_array(
                $categoryId,
                [3, 4],
                true
            );


        /*
        |--------------------------------------------------------------------------
        | Price Labels
        |--------------------------------------------------------------------------
        */

        if ($isProduct) {

            $priceLabel = 'Product Price';

        } elseif ($isService) {

            $priceLabel = 'Service Price';

        } elseif ($isProperty) {

            $priceLabel = 'Property Price';

        } elseif ($isLand) {

            $priceLabel = 'Land Price';

        } elseif ($isProject) {

            $priceLabel = 'Project Price';

        } else {

            $priceLabel = 'Quoted Price';

        }


        /*
        |--------------------------------------------------------------------------
        | Offer Values
        |--------------------------------------------------------------------------
        */

        $quantity = (float) (
            $enquiry->quantity ?? 1
        );


        $unitPrice = (float) (
            $enquiry->unit_price ?? 0
        );


        $deliveryCharges = (float) (
            $enquiry->delivery_charges ?? 0
        );


        $otherCharges = (float) (
            $enquiry->other_charges ?? 0
        );


        $tax = (float) (
            $enquiry->tax ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | Base Price
        |--------------------------------------------------------------------------
        */

        $basePrice = $isQuantityBased
            ? ($quantity * $unitPrice)
            : $unitPrice;


        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $totalAmount = $enquiry->total_amount !== null
            ? (float) $enquiry->total_amount
            : (
                $basePrice
                + $deliveryCharges
                + $otherCharges
                + $tax
            );


        /*
        |--------------------------------------------------------------------------
        | Expiry
        |--------------------------------------------------------------------------
        */

        $isExpired =
            $enquiry->status === 'offer_expired'
            ||
            (
                $enquiry->status === 'offer_sent'
                && $enquiry->offer_valid_until
                && now()->startOfDay()->gt(
                    $enquiry->offer_valid_until->startOfDay()
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Can Take Action
        |--------------------------------------------------------------------------
        */

        $canTakeAction =
            $enquiry->status === 'offer_sent'
            && !$isExpired;


        /*
        |--------------------------------------------------------------------------
        | Seller Name
        |--------------------------------------------------------------------------
        */

        $sellerName =
            $enquiry->seller?->bussiness_name
            ?? $enquiry->seller?->business_name
            ?? $enquiry->seller?->company_name
            ?? $enquiry->seller?->name
            ?? 'Seller';

    @endphp


    <x-slot name="header">

        <div>

            <h2 class="text-xl font-semibold text-gray-800">
                Commercial Offer
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Review the seller's commercial offer before taking action.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">


            {{-- Back --}}
            <div class="mb-6">

                <a
                    href="{{ route('buyer.enquiries.show', $enquiry->id) }}"
                    class="inline-flex items-center text-sm font-medium
                           text-[#056663] hover:underline"
                >
                    ← Back to Enquiry
                </a>

            </div>


            {{-- Success --}}
            @if(session('success'))

                <div class="mb-6 rounded-xl border border-green-200
                            bg-green-50 px-4 py-3 text-sm text-green-700">

                    {{ session('success') }}

                </div>

            @endif


            {{-- Error --}}
            @if(session('error'))

                <div class="mb-6 rounded-xl border border-red-200
                            bg-red-50 px-4 py-3 text-sm text-red-700">

                    {{ session('error') }}

                </div>

            @endif


            {{-- Info --}}
            @if(session('info'))

                <div class="mb-6 rounded-xl border border-blue-200
                            bg-blue-50 px-4 py-3 text-sm text-blue-700">

                    {{ session('info') }}

                </div>

            @endif


            {{-- Offer Header --}}
            <div class="mb-6 rounded-2xl border border-[#DDE9E8]
                        bg-white shadow-sm">

                <div class="flex flex-col gap-4 border-b border-[#DDE9E8]
                            px-6 py-5 sm:flex-row sm:items-center
                            sm:justify-between">

                    <div>

                        <p class="text-xs font-medium uppercase
                                  tracking-wide text-gray-500">
                            Seller Commercial Offer
                        </p>


                        <h1 class="mt-1 text-xl font-semibold text-gray-800">
                            {{ $listingTitle }}
                        </h1>


                        <p class="mt-1 text-sm text-gray-500">
                            Enquiry #{{ $enquiry->id }}
                        </p>

                    </div>


                    <span
                        class="inline-flex w-fit rounded-full
                               bg-[#E8F5F4] px-3 py-1 text-xs
                               font-semibold text-[#056663]"
                    >
                        {{ $categoryName }}
                    </span>

                </div>


                <div class="grid gap-5 p-6 md:grid-cols-2">


                    {{-- Seller --}}
                    <div>

                        <p class="text-xs text-gray-500">
                            Seller
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $sellerName }}
                        </p>

                    </div>


                    {{-- Offer Submitted --}}
                    <div>

                        <p class="text-xs text-gray-500">
                            Offer Submitted
                        </p>

                        <p class="mt-1 font-medium text-gray-800">

                            {{ $enquiry->offer_sent_at
                                ? $enquiry->offer_sent_at->format('d M Y, h:i A')
                                : 'N/A' }}

                        </p>

                    </div>


                    {{-- Valid Until --}}
                    <div>

                        <p class="text-xs text-gray-500">
                            Offer Valid Until
                        </p>

                        <p
                            class="mt-1 font-medium
                            {{ $isExpired ? 'text-red-600' : 'text-gray-800' }}"
                        >

                            {{ $enquiry->offer_valid_until
                                ? $enquiry->offer_valid_until->format('d M Y')
                                : 'N/A' }}

                        </p>

                    </div>


                    {{-- Status --}}
                    <div>

                        <p class="text-xs text-gray-500">
                            Status
                        </p>


                        <div class="mt-1">

                            @if($isExpired)

                                <span
                                    class="inline-flex rounded-full border
                                           border-red-200 bg-red-50 px-3 py-1
                                           text-xs font-semibold text-red-700"
                                >
                                    Expired
                                </span>

                            @elseif($enquiry->status === 'offer_sent')

                                <span
                                    class="inline-flex rounded-full border
                                           border-yellow-200 bg-yellow-50 px-3 py-1
                                           text-xs font-semibold text-yellow-700"
                                >
                                    Awaiting Your Response
                                </span>

                            @elseif($enquiry->status === 'offer_accepted')

                                <span
                                    class="inline-flex rounded-full border
                                           border-green-200 bg-green-50 px-3 py-1
                                           text-xs font-semibold text-green-700"
                                >
                                    Accepted
                                </span>

                            @elseif($enquiry->status === 'offer_rejected')

                                <span
                                    class="inline-flex rounded-full border
                                           border-red-200 bg-red-50 px-3 py-1
                                           text-xs font-semibold text-red-700"
                                >
                                    Rejected
                                </span>

                            @else

                                <span
                                    class="inline-flex rounded-full border
                                           border-gray-200 bg-gray-50 px-3 py-1
                                           text-xs font-semibold text-gray-700"
                                >
                                    {{ ucwords(str_replace('_', ' ', $enquiry->status)) }}
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- Commercial Terms --}}
            <div class="mb-6 rounded-2xl border border-[#DDE9E8]
                        bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-5">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Commercial Terms
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Review the pricing and terms submitted by the seller.
                    </p>

                </div>


                <div class="space-y-5 p-6">


                    {{-- Main Price --}}
                    <div class="flex items-center justify-between gap-4
                                rounded-xl bg-[#F7FAFA] p-4">

                        <div>

                            <p class="text-sm text-gray-500">
                                {{ $priceLabel }}
                            </p>


                            @if($isQuantityBased)

                                <p class="mt-1 text-xs text-gray-500">

                                    {{ rtrim(rtrim(number_format($quantity, 2), '0'), '.') }}

                                    {{ $enquiry->unit ?? 'unit' }}

                                    ×

                                    ₹{{ number_format($unitPrice, 2) }}

                                </p>

                            @else

                                <p class="mt-1 text-xs text-gray-500">
                                    Seller's quoted amount
                                </p>

                            @endif

                        </div>


                        <p class="text-lg font-semibold text-gray-800">

                            ₹{{ number_format($basePrice, 2) }}

                        </p>

                    </div>


                    {{-- Additional Charges --}}
                    <div class="space-y-3">


                        <div class="flex justify-between gap-4 text-sm">

                            <span class="text-gray-500">

                                @if($isProduct)

                                    Delivery Charges

                                @elseif($isProperty || $isLand)

                                    Registration / Documentation Charges

                                @elseif($isService)

                                    Service / Additional Charges

                                @elseif($isProject)

                                    Project / Additional Charges

                                @else

                                    Additional Charges

                                @endif

                            </span>


                            <span class="font-medium text-gray-800">

                                ₹{{ number_format($deliveryCharges, 2) }}

                            </span>

                        </div>


                        <div class="flex justify-between gap-4 text-sm">

                            <span class="text-gray-500">
                                Other Charges
                            </span>

                            <span class="font-medium text-gray-800">
                                ₹{{ number_format($otherCharges, 2) }}
                            </span>

                        </div>


                        <div class="flex justify-between gap-4 text-sm">

                            <span class="text-gray-500">
                                Tax
                            </span>

                            <span class="font-medium text-gray-800">
                                ₹{{ number_format($tax, 2) }}
                            </span>

                        </div>


                        {{-- Total --}}
                        <div class="border-t border-[#DDE9E8] pt-4">

                            <div class="flex items-center justify-between gap-4">

                                <span class="text-base font-semibold text-gray-800">
                                    Total Offer
                                </span>


                                <span class="text-2xl font-bold text-[#056663]">

                                    ₹{{ number_format($totalAmount, 2) }}

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Timeline --}}
            <div class="mb-6 rounded-2xl border border-[#DDE9E8]
                        bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-5">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Timeline & Terms
                    </h2>

                </div>


                <div class="grid gap-5 p-6 md:grid-cols-2">


                    <div>

                        <p class="text-xs text-gray-500">

                            @if($isProperty)

                                Possession / Handover Time

                            @elseif($isLand)

                                Registration / Handover Timeline

                            @elseif($isService)

                                Service Duration

                            @elseif($isProject)

                                Project Timeline

                            @else

                                Delivery Time

                            @endif

                        </p>


                        <p class="mt-1 font-medium text-gray-800">

                            {{ $enquiry->delivery_time ?: 'Not specified' }}

                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-gray-500">
                            Offer Valid Until
                        </p>


                        <p
                            class="mt-1 font-medium
                            {{ $isExpired ? 'text-red-600' : 'text-gray-800' }}"
                        >

                            {{ $enquiry->offer_valid_until
                                ? $enquiry->offer_valid_until->format('d M Y')
                                : 'Not specified' }}

                        </p>

                    </div>

                </div>

            </div>


            {{-- Seller Notes --}}
            @if($enquiry->offer_notes)

                <div class="mb-6 rounded-2xl border border-[#DDE9E8]
                            bg-white shadow-sm">

                    <div class="border-b border-[#DDE9E8] px-6 py-5">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Seller Notes & Terms
                        </h2>

                    </div>


                    <div class="p-6">

                        <div class="rounded-xl bg-[#F7FAFA]
                                    p-4 text-sm leading-6 text-gray-700
                                    whitespace-pre-line">

                            {{ $enquiry->offer_notes }}

                        </div>

                    </div>

                </div>

            @endif


            {{-- Expired --}}
            @if($isExpired && $enquiry->status !== 'offer_accepted')

                <div class="mb-6 rounded-2xl border border-red-200
                            bg-red-50 p-5">

                    <h3 class="font-semibold text-red-800">
                        This commercial offer has expired.
                    </h3>

                    <p class="mt-1 text-sm text-red-700">
                        You can no longer accept or reject this offer.
                        Please contact the seller if you need an updated offer.
                    </p>

                </div>

            @endif


            {{-- Accepted --}}
            @if($enquiry->status === 'offer_accepted')

                <div class="mb-6 rounded-2xl border border-green-200
                            bg-green-50 p-5">

                    <h3 class="font-semibold text-green-800">
                        Commercial Offer Accepted
                    </h3>

                    <p class="mt-1 text-sm text-green-700">
                        You accepted this seller's commercial offer and an order
                        has been created.
                    </p>


                    <a
                        href="{{ route('buyer.orders.index') }}"
                        class="mt-4 inline-flex rounded-lg bg-[#056663]
                               px-5 py-2.5 text-sm font-semibold text-white
                               hover:bg-[#04514F]"
                    >
                        View Order
                    </a>

                </div>

            @endif


            {{-- Rejected --}}
            @if($enquiry->status === 'offer_rejected')

                <div class="mb-6 rounded-2xl border border-red-200
                            bg-red-50 p-5">

                    <h3 class="font-semibold text-red-800">
                        Commercial Offer Rejected
                    </h3>

                    <p class="mt-1 text-sm text-red-700">
                        This commercial offer has been rejected.
                    </p>

                </div>

            @endif


            {{-- Actions --}}
            @if($canTakeAction)

                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white p-6 shadow-sm">

                    <div class="mb-5">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Respond to Offer
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Review the total amount and seller terms before
                            choosing an action.
                        </p>

                    </div>


                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">


                        {{-- Reject --}}
                        <form
                            method="POST"
                            action="{{ route(
                                'buyer.enquiries.offer.reject',
                                $enquiry->id
                            ) }}"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                onclick="return confirm(
                                    'Are you sure you want to reject this commercial offer?'
                                )"
                                class="inline-flex w-full items-center
                                       justify-center rounded-xl border
                                       border-red-300 bg-white px-6 py-3
                                       text-sm font-semibold text-red-600
                                       transition hover:bg-red-50
                                       sm:w-auto"
                            >
                                Reject Offer
                            </button>

                        </form>


                        {{-- Accept --}}
                        <form
                            method="POST"
                            action="{{ route(
                                'buyer.enquiries.offer.accept',
                                $enquiry->id
                            ) }}"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                onclick="return confirm(
                                    'Are you sure you want to accept this commercial offer and create an order?'
                                )"
                                class="inline-flex w-full items-center
                                       justify-center rounded-xl
                                       bg-[#056663] px-6 py-3
                                       text-sm font-semibold text-white
                                       shadow-sm transition
                                       hover:bg-[#045451]
                                       sm:w-auto"
                            >
                                Accept Offer & Create Order
                            </button>

                        </form>

                    </div>

                </div>

            @endif


        </div>

    </div>

</x-buyer-layout>