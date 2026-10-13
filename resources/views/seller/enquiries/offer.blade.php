<x-seller-layout>

    @php
        /*
        |--------------------------------------------------------------------------
        | Category Detection
        |--------------------------------------------------------------------------
        */

        $listing = $enquiry->listing;

        $categoryName = data_get($listing, 'category.name');

        if (!$categoryName) {
            $categoryName = match ((int) data_get($listing, 'category_id')) {
                1 => 'Properties',
                2 => 'Land',
                3 => 'Products',
                default => 'Other',
            };
        }

        $category = strtolower(trim($categoryName));

        /*
        |--------------------------------------------------------------------------
        | Category Configuration
        |--------------------------------------------------------------------------
        */

        $isProduct = str_contains($category, 'product');
        $isProperty = str_contains($category, 'propert');
        $isLand = str_contains($category, 'land');
        $isService = str_contains($category, 'service');
        $isProject = str_contains($category, 'project');

        if ($isProduct) {

            $categoryTitle = 'Product Commercial Offer';
            $priceLabel = 'Unit Price';
            $additionalLabel = 'Delivery Charges';
            $timeLabel = 'Delivery Time';
            $quantityLabel = 'Quantity';
            $showQuantity = true;

        } elseif ($isProperty) {

            $categoryTitle = 'Property Commercial Offer';
            $priceLabel = 'Property Price';
            $additionalLabel = 'Registration / Documentation Charges';
            $timeLabel = 'Possession / Handover Time';
            $quantityLabel = 'Property';
            $showQuantity = false;

        } elseif ($isLand) {

            $categoryTitle = 'Land Commercial Offer';
            $priceLabel = 'Land Price';
            $additionalLabel = 'Registration / Documentation Charges';
            $timeLabel = 'Registration / Handover Timeline';
            $quantityLabel = 'Land';
            $showQuantity = false;

        } elseif ($isService) {

            $categoryTitle = 'Service Commercial Offer';
            $priceLabel = 'Service Price';
            $additionalLabel = 'Service / Additional Charges';
            $timeLabel = 'Service Duration';
            $quantityLabel = 'Quantity';
            $showQuantity = true;

        } elseif ($isProject) {

            $categoryTitle = 'Project Commercial Offer';
            $priceLabel = 'Project Price';
            $additionalLabel = 'Project / Additional Charges';
            $timeLabel = 'Project Timeline';
            $quantityLabel = 'Project';
            $showQuantity = false;

        } else {

            $categoryTitle = 'Commercial Offer';
            $priceLabel = 'Quoted Price';
            $additionalLabel = 'Additional Charges';
            $timeLabel = 'Delivery / Completion Time';
            $quantityLabel = 'Quantity';
            $showQuantity = true;
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Values
        |--------------------------------------------------------------------------
        */

        $quantity = old(
            'quantity',
            $enquiry->quantity ?? 1
        );

        $unit = old(
            'unit',
            $enquiry->unit ?? 'unit'
        );

        $unitPrice = old(
            'unit_price',
            $enquiry->unit_price ?? ''
        );

        $deliveryCharges = old(
            'delivery_charges',
            $enquiry->delivery_charges ?? 0
        );

        $otherCharges = old(
            'other_charges',
            $enquiry->other_charges ?? 0
        );

        $tax = old(
            'tax',
            $enquiry->tax ?? 0
        );

        $deliveryTime = old(
            'delivery_time',
            $enquiry->delivery_time ?? ''
        );

        $offerValidUntil = old(
            'offer_valid_until',
            optional($enquiry->offer_valid_until)->format('Y-m-d')
        );

        $offerNotes = old(
            'offer_notes',
            $enquiry->offer_notes ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | Listing Details
        |--------------------------------------------------------------------------
        */

        $listingTitle = data_get($listing, 'title')
            ?? data_get($listing, 'name')
            ?? data_get($listing, 'product_name')
            ?? 'Listing';

        $propertyType = data_get($listing, 'property_type');
        $propertyLocation = data_get($listing, 'property_location')
            ?? data_get($listing, 'location');

        $propertyArea = data_get($listing, 'property_area')
            ?? data_get($listing, 'area');

        $propertyAreaUnit = data_get($listing, 'property_area_unit')
            ?? data_get($listing, 'area_unit');

        $landLocation = data_get($listing, 'land_location')
            ?? data_get($listing, 'location');

        $landArea = data_get($listing, 'land_area')
            ?? data_get($listing, 'area');

        $landAreaUnit = data_get($listing, 'land_area_unit')
            ?? data_get($listing, 'area_unit');

        $serviceName = data_get($listing, 'service_name')
            ?? data_get($listing, 'name');

        $serviceDuration = data_get($listing, 'service_duration');

        $projectName = data_get($listing, 'project_name')
            ?? data_get($listing, 'name');

        $projectLocation = data_get($listing, 'project_location')
            ?? data_get($listing, 'location');

        $projectTimeline = data_get($listing, 'project_timeline');
    @endphp


    <x-slot name="header">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('seller.enquiries.show', $enquiry->id) }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg
                       text-[#056663] hover:bg-[#E8F5F4]"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $categoryTitle }}
                </h2>

                <p class="text-sm text-gray-500">
                    Create a commercial offer for this buyer enquiry
                </p>
            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif


            {{-- Error Message --}}
            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif


            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-4">
                    <p class="mb-2 font-semibold text-red-700">
                        Please correct the following errors:
                    </p>

                    <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- Buyer Enquiry Summary --}}
            <div class="mb-6 rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-5">

                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Buyer Enquiry
                            </p>

                            <h3 class="mt-1 text-lg font-semibold text-gray-800">
                                {{ $enquiry->name ?? 'Buyer' }}
                            </h3>
                        </div>

                        <span class="inline-flex w-fit items-center rounded-full
                                     bg-[#E8F5F4] px-3 py-1 text-xs font-semibold
                                     text-[#056663]">
                            {{ $categoryName }}
                        </span>

                    </div>

                </div>


                <div class="grid gap-5 p-6 md:grid-cols-2">

                    <div>
                        <p class="text-xs text-gray-500">Listing</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $listingTitle }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">Buyer</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $enquiry->name ?? 'N/A' }}
                        </p>
                    </div>

                    @if($enquiry->quantity)
                        <div>
                            <p class="text-xs text-gray-500">Requested Quantity</p>
                            <p class="mt-1 font-medium text-gray-800">
                                {{ $enquiry->quantity }}
                                {{ $enquiry->unit ?? '' }}
                            </p>
                        </div>
                    @endif

                    @if($enquiry->message)
                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500">Buyer Message</p>

                            <div class="mt-2 rounded-xl bg-[#F7FAFA] p-4 text-sm text-gray-700">
                                {{ $enquiry->message }}
                            </div>
                        </div>
                    @endif

                </div>

            </div>


            {{-- Category Specific Listing Information --}}
            <div class="mb-6 rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-5">

                    <h3 class="text-base font-semibold text-gray-800">
                        {{ $categoryName }} Details
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Review the listing details before submitting your commercial offer.
                    </p>

                </div>


                <div class="grid gap-5 p-6 sm:grid-cols-2 lg:grid-cols-3">

                    @if($isProduct)

                        <div>
                            <p class="text-xs text-gray-500">Product</p>
                            <p class="mt-1 font-medium text-gray-800">
                                {{ $listingTitle }}
                            </p>
                        </div>

                    @elseif($isProperty)

                        @if($propertyType)
                            <div>
                                <p class="text-xs text-gray-500">Property Type</p>
                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $propertyType }}
                                </p>
                            </div>
                        @endif

                        @if($propertyLocation)
                            <div>
                                <p class="text-xs text-gray-500">Location</p>
                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $propertyLocation }}
                                </p>
                            </div>
                        @endif

                        @if($propertyArea)
                            <div>
                                <p class="text-xs text-gray-500">Area</p>
                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $propertyArea }}
                                    {{ $propertyAreaUnit ?? '' }}
                                </p>
                            </div>
                        @endif

                    @elseif($isLand)

                        @if($landLocation)
                            <div>
                                <p class="text-xs text-gray-500">Land Location</p>
                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $landLocation }}
                                </p>
                            </div>
                        @endif

                        @if($landArea)
                            <div>
                                <p class="text-xs text-gray-500">Land Area</p>
                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $landArea }}
                                    {{ $landAreaUnit ?? '' }}
                                </p>
                            </div>
                        @endif

                    @elseif($isService)

                        <div>
                            <p class="text-xs text-gray-500">Service</p>
                            <p class="mt-1 font-medium text-gray-800">
                                {{ $serviceName ?? $listingTitle }}
                            </p>
                        </div>

                        @if($serviceDuration)
                            <div>
                                <p class="text-xs text-gray-500">Existing Service Duration</p>
                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $serviceDuration }}
                                </p>
                            </div>
                        @endif

                    @elseif($isProject)

                        <div>
                            <p class="text-xs text-gray-500">Project</p>
                            <p class="mt-1 font-medium text-gray-800">
                                {{ $projectName ?? $listingTitle }}
                            </p>
                        </div>

                        @if($projectLocation)
                            <div>
                                <p class="text-xs text-gray-500">Location</p>
                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $projectLocation }}
                                </p>
                            </div>
                        @endif

                        @if($projectTimeline)
                            <div>
                                <p class="text-xs text-gray-500">Project Timeline</p>
                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $projectTimeline }}
                                </p>
                            </div>
                        @endif

                    @else

                        <div>
                            <p class="text-xs text-gray-500">Listing</p>
                            <p class="mt-1 font-medium text-gray-800">
                                {{ $listingTitle }}
                            </p>
                        </div>

                    @endif

                </div>

            </div>


            {{-- Commercial Offer Form --}}
            <form
                method="POST"
                action="{{ route('seller.enquiries.offer.store', $enquiry->id) }}"
                class="rounded-2xl border border-[#DDE9E8] bg-white shadow-sm"
            >

                @csrf


                <div class="border-b border-[#DDE9E8] px-6 py-5">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Commercial Offer
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Enter the commercial terms you are offering to the buyer.
                    </p>

                </div>


                <div class="space-y-8 p-6">

                    {{-- Main Pricing --}}
                    <div>

                        <h4 class="mb-4 text-sm font-semibold uppercase tracking-wide text-[#056663]">
                            Pricing Details
                        </h4>

                        <div class="grid gap-5 md:grid-cols-2">

                            {{-- Quantity --}}
                            @if($showQuantity)

                                <div>

                                    <label
                                        for="quantity"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        {{ $quantityLabel }}
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex">

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            name="quantity"
                                            id="quantity"
                                            value="{{ $quantity }}"
                                            required
                                            class="w-full rounded-l-xl border border-gray-300 px-4 py-3
                                                   text-sm focus:border-[#056663] focus:ring-[#056663]"
                                        >

                                        <input
                                            type="text"
                                            name="unit"
                                            value="{{ $unit }}"
                                            placeholder="Unit"
                                            class="w-32 rounded-r-xl border-y border-r border-gray-300
                                                   px-4 py-3 text-sm focus:border-[#056663]
                                                   focus:ring-[#056663]"
                                        >

                                    </div>

                                    @error('quantity')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror

                                </div>

                            @else

                                {{-- Hidden values for non quantity based categories --}}
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="unit" value="{{ strtolower($categoryName) }}">

                            @endif


                            {{-- Main Price --}}
                            <div>

                                <label
                                    for="unit_price"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    {{ $priceLabel }}
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">

                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2
                                               text-sm font-medium text-gray-500"
                                    >
                                        ₹
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="unit_price"
                                        id="unit_price"
                                        value="{{ $unitPrice }}"
                                        required
                                        placeholder="0.00"
                                        class="w-full rounded-xl border border-gray-300 py-3 pl-9 pr-4
                                               text-sm focus:border-[#056663] focus:ring-[#056663]"
                                    >

                                </div>

                                @error('unit_price')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                            </div>


                            {{-- Category-specific Additional Charges --}}
                            <div>

                                <label
                                    for="delivery_charges"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    {{ $additionalLabel }}
                                </label>

                                <div class="relative">

                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2
                                               text-sm font-medium text-gray-500"
                                    >
                                        ₹
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="delivery_charges"
                                        id="delivery_charges"
                                        value="{{ $deliveryCharges }}"
                                        placeholder="0.00"
                                        class="w-full rounded-xl border border-gray-300 py-3 pl-9 pr-4
                                               text-sm focus:border-[#056663] focus:ring-[#056663]"
                                    >

                                </div>

                                @error('delivery_charges')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                            </div>


                            {{-- Other Charges --}}
                            <div>

                                <label
                                    for="other_charges"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Other Charges
                                </label>

                                <div class="relative">

                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2
                                               text-sm font-medium text-gray-500"
                                    >
                                        ₹
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="other_charges"
                                        id="other_charges"
                                        value="{{ $otherCharges }}"
                                        placeholder="0.00"
                                        class="w-full rounded-xl border border-gray-300 py-3 pl-9 pr-4
                                               text-sm focus:border-[#056663] focus:ring-[#056663]"
                                    >

                                </div>

                                @error('other_charges')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                            </div>


                            {{-- Tax --}}
                            <div>

                                <label
                                    for="tax"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Tax
                                </label>

                                <div class="relative">

                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2
                                               text-sm font-medium text-gray-500"
                                    >
                                        ₹
                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="tax"
                                        id="tax"
                                        value="{{ $tax }}"
                                        placeholder="0.00"
                                        class="w-full rounded-xl border border-gray-300 py-3 pl-9 pr-4
                                               text-sm focus:border-[#056663] focus:ring-[#056663]"
                                    >

                                </div>

                                @error('tax')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Delivery / Timeline --}}
                    <div>

                        <h4 class="mb-4 text-sm font-semibold uppercase tracking-wide text-[#056663]">
                            Timeline & Validity
                        </h4>

                        <div class="grid gap-5 md:grid-cols-2">

                            <div>

                                <label
                                    for="delivery_time"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    {{ $timeLabel }}
                                </label>

                                <input
                                    type="text"
                                    name="delivery_time"
                                    id="delivery_time"
                                    value="{{ $deliveryTime }}"
                                    placeholder="{{ $timeLabel }}"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3
                                           text-sm focus:border-[#056663] focus:ring-[#056663]"
                                >

                                @error('delivery_time')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                            </div>


                            <div>

                                <label
                                    for="offer_valid_until"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Offer Valid Until
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="offer_valid_until"
                                    id="offer_valid_until"
                                    value="{{ $offerValidUntil }}"
                                    min="{{ now()->format('Y-m-d') }}"
                                    required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3
                                           text-sm focus:border-[#056663] focus:ring-[#056663]"
                                >

                                @error('offer_valid_until')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Notes --}}
                    <div>

                        <h4 class="mb-4 text-sm font-semibold uppercase tracking-wide text-[#056663]">
                            Offer Notes
                        </h4>

                        <textarea
                            name="offer_notes"
                            id="offer_notes"
                            rows="5"
                            maxlength="3000"
                            placeholder="Add terms, conditions, inclusions, exclusions, payment terms or any other important information..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
                                   text-sm focus:border-[#056663] focus:ring-[#056663]"
                        >{{ $offerNotes }}</textarea>

                        @error('offer_notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                    </div>


                    {{-- Offer Calculation --}}
                    <div class="rounded-2xl border border-[#DDE9E8] bg-[#F7FAFA] p-5">

                        <div class="mb-4 flex items-center justify-between">

                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    Offer Summary
                                </h4>

                                <p class="text-xs text-gray-500">
                                    Estimated total commercial value
                                </p>
                            </div>

                            <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                                {{ $categoryName }}
                            </span>

                        </div>


                        <div class="space-y-3 text-sm">

                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">
                                    {{ $priceLabel }}
                                </span>

                                <span id="summaryBasePrice" class="font-medium text-gray-800">
                                    ₹0.00
                                </span>
                            </div>


                            @if($showQuantity)

                                <div class="flex justify-between gap-4">

                                    <span class="text-gray-500">
                                        Quantity
                                    </span>

                                    <span id="summaryQuantity" class="font-medium text-gray-800">
                                        {{ $quantity }}
                                    </span>

                                </div>

                            @endif


                            <div class="flex justify-between gap-4">

                                <span class="text-gray-500">
                                    {{ $additionalLabel }}
                                </span>

                                <span id="summaryDelivery" class="font-medium text-gray-800">
                                    ₹0.00
                                </span>

                            </div>


                            <div class="flex justify-between gap-4">

                                <span class="text-gray-500">
                                    Other Charges
                                </span>

                                <span id="summaryOther" class="font-medium text-gray-800">
                                    ₹0.00
                                </span>

                            </div>


                            <div class="flex justify-between gap-4">

                                <span class="text-gray-500">
                                    Tax
                                </span>

                                <span id="summaryTax" class="font-medium text-gray-800">
                                    ₹0.00
                                </span>

                            </div>


                            <div class="border-t border-[#DDE9E8] pt-3">

                                <div class="flex justify-between gap-4">

                                    <span class="font-semibold text-gray-800">
                                        Total Offer
                                    </span>

                                    <span
                                        id="summaryTotal"
                                        class="text-xl font-bold text-[#056663]"
                                    >
                                        ₹0.00
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-[#DDE9E8]
                            bg-[#F7FAFA] px-6 py-5 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('seller.enquiries.show', $enquiry->id) }}"
                        class="inline-flex items-center justify-center rounded-xl border
                               border-gray-300 bg-white px-5 py-2.5 text-sm font-medium
                               text-gray-700 hover:bg-gray-50"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl
                               bg-[#056663] px-6 py-2.5 text-sm font-semibold
                               text-white shadow-sm transition hover:bg-[#045451]"
                    >
                        {{ $enquiry->hasOffer() ? 'Update Commercial Offer' : 'Send Commercial Offer' }}
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- Calculation Script --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const quantityInput = document.getElementById('quantity');
            const unitPriceInput = document.getElementById('unit_price');
            const deliveryInput = document.getElementById('delivery_charges');
            const otherInput = document.getElementById('other_charges');
            const taxInput = document.getElementById('tax');

            const summaryBasePrice = document.getElementById('summaryBasePrice');
            const summaryQuantity = document.getElementById('summaryQuantity');
            const summaryDelivery = document.getElementById('summaryDelivery');
            const summaryOther = document.getElementById('summaryOther');
            const summaryTax = document.getElementById('summaryTax');
            const summaryTotal = document.getElementById('summaryTotal');

            const isQuantityBased = @json($showQuantity);


            function numberValue(element) {

                if (!element) {
                    return 0;
                }

                const value = parseFloat(element.value);

                return isNaN(value) ? 0 : value;
            }


            function formatCurrency(value) {

                return '₹' + value.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            }


            function calculateOffer() {

                const quantity = isQuantityBased
                    ? numberValue(quantityInput)
                    : 1;

                const unitPrice = numberValue(unitPriceInput);
                const delivery = numberValue(deliveryInput);
                const other = numberValue(otherInput);
                const tax = numberValue(taxInput);

                /*
                |--------------------------------------------------------------------------
                | Product / Service
                |--------------------------------------------------------------------------
                | Quantity × Unit Price
                |
                |--------------------------------------------------------------------------
                | Property / Land / Project / Other
                |--------------------------------------------------------------------------
                | Quoted Price directly
                */

                const basePrice = isQuantityBased
                    ? quantity * unitPrice
                    : unitPrice;

                const total = basePrice + delivery + other + tax;


                if (summaryBasePrice) {
                    summaryBasePrice.textContent = formatCurrency(basePrice);
                }

                if (summaryQuantity) {
                    summaryQuantity.textContent = quantity;
                }

                if (summaryDelivery) {
                    summaryDelivery.textContent = formatCurrency(delivery);
                }

                if (summaryOther) {
                    summaryOther.textContent = formatCurrency(other);
                }

                if (summaryTax) {
                    summaryTax.textContent = formatCurrency(tax);
                }

                if (summaryTotal) {
                    summaryTotal.textContent = formatCurrency(total);
                }

            }


            [
                quantityInput,
                unitPriceInput,
                deliveryInput,
                otherInput,
                taxInput
            ].forEach(function (input) {

                if (input) {
                    input.addEventListener('input', calculateOffer);
                }

            });


            calculateOffer();

        });

    </script>

</x-seller-layout>