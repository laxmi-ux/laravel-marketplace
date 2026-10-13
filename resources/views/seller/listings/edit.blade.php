{{-- resources/views/seller/listings/edit.blade.php --}}

<x-seller-layout>

    {{-- =========================================================
         CATEGORY / SUBCATEGORY DATA
    ========================================================== --}}

    @php
        /*
        |--------------------------------------------------------------------------
        | Current Category
        |--------------------------------------------------------------------------
        */

        $categorySlug = $listing->categoryRelation?->slugs;

        $categoryType = match ($categorySlug) {
            'products' => 'product',
            'properties' => 'property',
            'land' => 'land',
            'services' => 'service',
            'projects' => 'project',
            default => old('category', $listing->category ?? ''),
        };


        /*
        |--------------------------------------------------------------------------
        | Category IDs
        |--------------------------------------------------------------------------
        | Your categories table uses "slugs".
        */

        $categoryIds = \App\Models\Category::whereIn('slugs', [
            'products',
            'properties',
            'land',
            'services',
            'projects',
        ])
        ->pluck('id', 'slugs')
        ->toArray();


        /*
        |--------------------------------------------------------------------------
        | All Active Subcategories
        |--------------------------------------------------------------------------
        */

        $allSubcategories = \App\Models\Subcategory::where('status', 1)
            ->orderBy('name')
            ->get([
                'id',
                'category_id',
                'name',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Existing Selected Subcategory
        |--------------------------------------------------------------------------
        */

        $selectedSubcategoryId = old(
            'subcategory_id',
            $listing->subcategory_id
        );


        /*
        |--------------------------------------------------------------------------
        | Category Specific Models
        |--------------------------------------------------------------------------
        */

        $product = $listing->product;

        $property = $listing->property;

        $land = $listing->land;

        $service = $listing->service;

        $project = $listing->project;
    @endphp


    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-[#123B38]">
                Edit Listing
            </h2>

            <p class="text-sm text-[#4F6663]">
                Update your listing information
            </p>

        </div>

    </x-slot>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="bg-[#F5F9F8] px-4 py-6 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-5xl">


            {{-- =====================================================
                 SUCCESS MESSAGE
            ====================================================== --}}

            @if(session('success'))

                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>

            @endif


            {{-- =====================================================
                 ERROR MESSAGE
            ====================================================== --}}

            @if(session('error'))

                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>

            @endif


            {{-- =====================================================
                 VALIDATION ERRORS
            ====================================================== --}}

            @if($errors->any())

                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">

                    <ul class="list-disc pl-5 space-y-1 text-sm text-red-600">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =====================================================
                 BACK LINK
            ====================================================== --}}

            <div class="mb-6">

                <a
                    href="{{ route('seller.listings.index') }}"
                    class="inline-flex items-center text-sm font-medium text-[#00665F] hover:underline"
                >
                    ← Back to My Listings
                </a>

            </div>


            {{-- =====================================================
                 EDIT FORM CARD
            ====================================================== --}}

            <div class="rounded-2xl border border-[#DCE9E6] bg-white shadow-sm">


                {{-- Card Header --}}

                <div class="border-b border-gray-100 px-5 py-5 sm:px-6">

                    <h1 class="text-xl font-bold text-[#163B37]">
                        Edit {{ $listing->title }}
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Update your listing details, image and documents.
                    </p>

                </div>


                {{-- =================================================
                     FORM
                ================================================== --}}

                <form
                    action="{{ route('seller.listings.update', $listing->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf

                    @method('PUT')


                    <div class="p-5 sm:p-6">


                        {{-- =================================================
                             BASIC INFORMATION
                        ================================================== --}}

                        <div class="mb-8">

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Basic Information
                            </h2>


                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                                {{-- Listing Title --}}

                                <div class="md:col-span-2">

                                    <label
                                        for="title"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Listing Title
                                    </label>

                                    <input
                                        type="text"
                                        name="title"
                                        id="title"
                                        value="{{ old('title', $listing->title) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter listing title"
                                        required
                                    >

                                    @error('title')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- Description --}}

                                <div class="md:col-span-2">

                                    <label
                                        for="description"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Description
                                    </label>

                                    <textarea
                                        name="description"
                                        id="description"
                                        rows="5"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter listing description"
                                        required
                                    >{{ old('description', $listing->description) }}</textarea>

                                    @error('description')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- =================================================
                                     CATEGORY
                                ================================================== --}}

                                <div>

                                    <label
                                        for="category"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Category
                                    </label>

                                    <select
                                        name="category"
                                        id="category"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                        <option
                                            value="product"
                                            {{ old('category', $categoryType) === 'product' ? 'selected' : '' }}
                                        >
                                            Product
                                        </option>

                                        <option
                                            value="property"
                                            {{ old('category', $categoryType) === 'property' ? 'selected' : '' }}
                                        >
                                            Property
                                        </option>

                                        <option
                                            value="land"
                                            {{ old('category', $categoryType) === 'land' ? 'selected' : '' }}
                                        >
                                            Land
                                        </option>

                                        <option
                                            value="service"
                                            {{ old('category', $categoryType) === 'service' ? 'selected' : '' }}
                                        >
                                            Service
                                        </option>

                                        <option
                                            value="project"
                                            {{ old('category', $categoryType) === 'project' ? 'selected' : '' }}
                                        >
                                            Project
                                        </option>

                                    </select>

                                    @error('category')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- =================================================
                                     SUBCATEGORY
                                ================================================== --}}

                                <div>

                                    <label
                                        for="subcategory_id"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Subcategory
                                    </label>

                                    <select
                                        name="subcategory_id"
                                        id="subcategory_id"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                        <option value="">
                                            Select Subcategory
                                        </option>

                                        @foreach($allSubcategories as $subcategory)

                                            <option
                                                value="{{ $subcategory->id }}"
                                                data-category-id="{{ $subcategory->category_id }}"
                                                {{ (string) old('subcategory_id', $selectedSubcategoryId) === (string) $subcategory->id ? 'selected' : '' }}
                                            >
                                                {{ $subcategory->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                    @error('subcategory_id')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                    <p class="mt-1 text-xs text-gray-500">
                                        Select a subcategory belonging to the selected category.
                                    </p>

                                </div>


                                {{-- Listing Type --}}

                                <div>

                                    <label
                                        for="listing_type"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Listing Type
                                    </label>

                                    <select
                                        name="listing_type"
                                        id="listing_type"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        required
                                    >

                                        <option
                                            value="sale"
                                            {{ old('listing_type', $listing->listing_type) === 'sale' ? 'selected' : '' }}
                                        >
                                            Sale
                                        </option>

                                        <option
                                            value="rent"
                                            {{ old('listing_type', $listing->listing_type) === 'rent' ? 'selected' : '' }}
                                        >
                                            Rent
                                        </option>

                                        <option
                                            value="lease"
                                            {{ old('listing_type', $listing->listing_type) === 'lease' ? 'selected' : '' }}
                                        >
                                            Lease
                                        </option>

                                        <option
                                            value="buy"
                                            {{ old('listing_type', $listing->listing_type) === 'buy' ? 'selected' : '' }}
                                        >
                                            Buy
                                        </option>

                                    </select>

                                    @error('listing_type')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- Price --}}

                                <div>

                                    <label
                                        for="price"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Price
                                    </label>

                                    <input
                                        type="number"
                                        name="price"
                                        id="price"
                                        step="0.01"
                                        min="0"
                                        value="{{ old('price', $listing->price) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter price"
                                        required
                                    >

                                    @error('price')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- Price Unit --}}

                                <div>

                                    <label
                                        for="price_unit"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Price Unit
                                    </label>

                                    <input
                                        type="text"
                                        name="price_unit"
                                        id="price_unit"
                                        value="{{ old('price_unit', $listing->price_unit) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Example: Per Sq Ft, Per Ton, Per Unit"
                                    >

                                    @error('price_unit')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             LOCATION
                        ================================================== --}}

                        <div class="mb-8">

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Location
                            </h2>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                                {{-- City --}}

                                <div>

                                    <label
                                        for="city"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        City
                                    </label>

                                    <input
                                        type="text"
                                        name="city"
                                        id="city"
                                        value="{{ old('city', $listing->city) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter city"
                                        required
                                    >

                                    @error('city')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- State --}}

                                <div>

                                    <label
                                        for="state"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        State
                                    </label>

                                    <input
                                        type="text"
                                        name="state"
                                        id="state"
                                        value="{{ old('state', $listing->state) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter state"
                                        required
                                    >

                                    @error('state')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- Pincode --}}

                                <div>

                                    <label
                                        for="pincode"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Pincode
                                    </label>

                                    <input
                                        type="text"
                                        name="pincode"
                                        id="pincode"
                                        value="{{ old('pincode', $listing->pincode) }}"
                                        maxlength="6"
                                        inputmode="numeric"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter pincode"
                                        required
                                    >

                                    @error('pincode')

                                        <p class="mt-1 text-sm text-red-600">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             PRODUCT DETAILS
                        ================================================== --}}

                        <div
                            id="productFields"
                            class="{{ $categoryType === 'product' ? '' : 'hidden' }} mb-8"
                        >

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Product Details
                            </h2>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                                {{-- Brand --}}

                                <div>

                                    <label
                                        for="brand"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Brand
                                    </label>

                                    <input
                                        type="text"
                                        name="brand"
                                        id="brand"
                                        value="{{ old('brand', $product?->brand) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Product Code --}}

                                <div>

                                    <label
                                        for="product_code"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Product Code
                                    </label>

                                    <input
                                        type="text"
                                        name="product_code"
                                        id="product_code"
                                        value="{{ old('product_code', $product?->product_code) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Quantity --}}

                                <div>

                                    <label
                                        for="quantity"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Quantity
                                    </label>

                                    <input
                                        type="number"
                                        name="quantity"
                                        id="quantity"
                                        step="0.01"
                                        min="0"
                                        value="{{ old('quantity', $product?->quantity) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Unit --}}

                                <div>

                                    <label
                                        for="unit"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Unit
                                    </label>

                                    <input
                                        type="text"
                                        name="unit"
                                        id="unit"
                                        value="{{ old('unit', $product?->unit) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. kg, piece, ton"
                                    >

                                </div>


                                {{-- Specifications --}}

                                <div class="md:col-span-2">

                                    <label
                                        for="specifications"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Specifications
                                    </label>

                                    <textarea
                                        name="specifications"
                                        id="specifications"
                                        rows="5"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter product specifications"
                                    >{{ old(
                                        'specifications',
                                        $product?->specifications
                                            ? json_encode($product->specifications, JSON_PRETTY_PRINT)
                                            : ''
                                    ) }}</textarea>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             PROPERTY DETAILS
                        ================================================== --}}

                        <div
                            id="propertyFields"
                            class="{{ $categoryType === 'property' ? '' : 'hidden' }} mb-8"
                        >

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Property Details
                            </h2>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                                {{-- Property Type --}}

                                <div>

                                    <label
                                        for="property_type"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Property Type
                                    </label>

                                    <input
                                        type="text"
                                        name="property_type"
                                        id="property_type"
                                        value="{{ old('property_type', $property?->property_type) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. Apartment, Villa, Office"
                                    >

                                </div>


                                {{-- Area --}}

                                <div>

                                    <label
                                        for="property_area"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Area
                                    </label>

                                    <input
                                        type="number"
                                        name="area"
                                        id="property_area"
                                        step="0.01"
                                        min="0"
                                        value="{{ old('area', $property?->area) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Area Unit --}}

                                <div>

                                    <label
                                        for="property_area_unit"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Area Unit
                                    </label>

                                    <input
                                        type="text"
                                        name="area_unit"
                                        id="property_area_unit"
                                        value="{{ old('area_unit', $property?->area_unit) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. sq.ft"
                                    >

                                </div>


                                {{-- Bedrooms --}}

                                <div>

                                    <label
                                        for="bedrooms"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Bedrooms
                                    </label>

                                    <input
                                        type="number"
                                        name="bedrooms"
                                        id="bedrooms"
                                        min="0"
                                        value="{{ old('bedrooms', $property?->bedrooms) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Bathrooms --}}

                                <div>

                                    <label
                                        for="bathrooms"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Bathrooms
                                    </label>

                                    <input
                                        type="number"
                                        name="bathrooms"
                                        id="bathrooms"
                                        min="0"
                                        value="{{ old('bathrooms', $property?->bathrooms) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Property Status --}}

                                <div>

                                    <label
                                        for="property_status"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Property Status
                                    </label>

                                    <input
                                        type="text"
                                        name="property_status"
                                        id="property_status"
                                        value="{{ old('property_status', $property?->property_status) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. Ready to Move"
                                    >

                                </div>


                                {{-- Amenities --}}

                                <div class="md:col-span-2">

                                    <label class="mb-3 block text-sm font-medium text-gray-700">
                                        Amenities
                                    </label>

                                    @php

                                        $amenityOptions = [
                                            'Parking',
                                            'Lift',
                                            'Security',
                                            'Swimming Pool',
                                            'Gym',
                                            'Garden',
                                            'Power Backup',
                                            'Water Supply',
                                        ];

                                        $selectedAmenities =
                                            old(
                                                'amenities',
                                                $property?->amenities ?? []
                                            );

                                        if (!is_array($selectedAmenities)) {
                                            $selectedAmenities = [];
                                        }

                                    @endphp

                                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">

                                        @foreach($amenityOptions as $amenity)

                                            <label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700">

                                                <input
                                                    type="checkbox"
                                                    name="amenities[]"
                                                    value="{{ $amenity }}"
                                                    class="rounded border-gray-300 text-[#056663] focus:ring-[#056663]"
                                                    {{ in_array($amenity, $selectedAmenities) ? 'checked' : '' }}
                                                >

                                                {{ $amenity }}

                                            </label>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             LAND DETAILS
                        ================================================== --}}

                        <div
                            id="landFields"
                            class="{{ $categoryType === 'land' ? '' : 'hidden' }} mb-8"
                        >

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Land Details
                            </h2>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                                {{-- Land Type --}}

                                <div>

                                    <label
                                        for="land_type"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Land Type
                                    </label>

                                    <input
                                        type="text"
                                        name="land_type"
                                        id="land_type"
                                        value="{{ old('land_type', $land?->land_type) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. Residential, Agricultural"
                                    >

                                </div>


                                {{-- Total Area --}}

                                <div>

                                    <label
                                        for="land_total_area"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Total Area
                                    </label>

                                    <input
                                        type="number"
                                        name="total_area"
                                        id="land_total_area"
                                        step="0.01"
                                        min="0"
                                        value="{{ old('total_area', $land?->total_area) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Area Unit --}}

                                <div>

                                    <label
                                        for="land_area_unit"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Area Unit
                                    </label>

                                    <input
                                        type="text"
                                        name="area_unit"
                                        id="land_area_unit"
                                        value="{{ old('area_unit', $land?->area_unit) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. acre, hectare, sq.ft"
                                    >

                                </div>


                                {{-- Survey Number --}}

                                <div>

                                    <label
                                        for="survey_number"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Survey Number
                                    </label>

                                    <input
                                        type="text"
                                        name="survey_number"
                                        id="survey_number"
                                        value="{{ old('survey_number', $land?->survey_number) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Gat Number --}}

                                <div>

                                    <label
                                        for="gat_number"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Gat Number
                                    </label>

                                    <input
                                        type="text"
                                        name="gat_number"
                                        id="gat_number"
                                        value="{{ old('gat_number', $land?->gat_number) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Ownership --}}

                                <div>

                                    <label
                                        for="ownership_type"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Ownership Type
                                    </label>

                                    <input
                                        type="text"
                                        name="ownership_type"
                                        id="ownership_type"
                                        value="{{ old('ownership_type', $land?->ownership_type) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. Freehold"
                                    >

                                </div>


                                {{-- Road Access --}}

                                <div>

                                    <label
                                        for="road_access"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Road Access
                                    </label>

                                    <input
                                        type="text"
                                        name="road_access"
                                        id="road_access"
                                        value="{{ old('road_access', $land?->road_access) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Zoning --}}

                                <div>

                                    <label
                                        for="zoning"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Zoning
                                    </label>

                                    <input
                                        type="text"
                                        name="zoning"
                                        id="zoning"
                                        value="{{ old('zoning', $land?->zoning) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Water --}}

                                <div class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="water_availability"
                                        value="1"
                                        class="rounded border-gray-300 text-[#056663] focus:ring-[#056663]"
                                        {{ old('water_availability', $land?->water_availability) ? 'checked' : '' }}
                                    >

                                    <label class="text-sm text-gray-700">
                                        Water Available
                                    </label>

                                </div>


                                {{-- Electricity --}}

                                <div class="flex items-center gap-3">

                                    <input
                                        type="checkbox"
                                        name="electricity_availability"
                                        value="1"
                                        class="rounded border-gray-300 text-[#056663] focus:ring-[#056663]"
                                        {{ old('electricity_availability', $land?->electricity_availability) ? 'checked' : '' }}
                                    >

                                    <label class="text-sm text-gray-700">
                                        Electricity Available
                                    </label>

                                </div>


                                {{-- Nearby Infrastructure --}}

                                <div class="md:col-span-2">

                                    <label
                                        for="nearby_infrastructure"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Nearby Infrastructure
                                    </label>

                                    <textarea
                                        name="nearby_infrastructure"
                                        id="nearby_infrastructure"
                                        rows="4"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Schools, highways, hospitals, markets, etc."
                                    >{{ old('nearby_infrastructure', $land?->nearby_infrastructure) }}</textarea>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             SERVICE DETAILS
                        ================================================== --}}

                        <div
                            id="serviceFields"
                            class="{{ $categoryType === 'service' ? '' : 'hidden' }} mb-8"
                        >

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Service Details
                            </h2>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                                {{-- Service Type --}}

                                <div>

                                    <label
                                        for="service_type"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Service Type
                                    </label>

                                    <input
                                        type="text"
                                        name="service_type"
                                        id="service_type"
                                        value="{{ old('service_type', $service?->service_type) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter service type"
                                    >

                                </div>


                                {{-- Service Area --}}

                                <div>

                                    <label
                                        for="service_area"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Service Area
                                    </label>

                                    <input
                                        type="text"
                                        name="service_area"
                                        id="service_area"
                                        value="{{ old('service_area', $service?->service_area) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter service area"
                                    >

                                </div>


                                {{-- Experience --}}

                                <div>

                                    <label
                                        for="experience"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Experience
                                    </label>

                                    <input
                                        type="text"
                                        name="experience"
                                        id="experience"
                                        value="{{ old('experience', $service?->experience) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. 5 years"
                                    >

                                </div>


                                {{-- Availability --}}

                                <div>

                                    <label
                                        for="availability"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Availability
                                    </label>

                                    <input
                                        type="text"
                                        name="availability"
                                        id="availability"
                                        value="{{ old('availability', $service?->availability) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. Monday - Saturday"
                                    >

                                </div>


                                {{-- Description --}}

                                <div class="md:col-span-2">

                                    <label
                                        for="service_description"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Service Description
                                    </label>

                                    <textarea
                                        name="service_description"
                                        id="service_description"
                                        rows="5"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Describe the service..."
                                    >{{ old('service_description', $service?->description) }}</textarea>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             PROJECT DETAILS
                        ================================================== --}}

                        <div
                            id="projectFields"
                            class="{{ $categoryType === 'project' ? '' : 'hidden' }} mb-8"
                        >

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Project Details
                            </h2>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                                {{-- Project Type --}}

                                <div>

                                    <label
                                        for="project_type"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Project Type
                                    </label>

                                    <input
                                        type="text"
                                        name="project_type"
                                        id="project_type"
                                        value="{{ old('project_type', $project?->project_type) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Enter project type"
                                    >

                                </div>


                                {{-- Project Status --}}

                                <div>

                                    <label
                                        for="project_status"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Project Status
                                    </label>

                                    <input
                                        type="text"
                                        name="project_status"
                                        id="project_status"
                                        value="{{ old('project_status', $project?->project_status) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. Ongoing, Upcoming"
                                    >

                                </div>


                                {{-- Developer --}}

                                <div>

                                    <label
                                        for="developer_name"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Developer Name
                                    </label>

                                    <input
                                        type="text"
                                        name="developer_name"
                                        id="developer_name"
                                        value="{{ old('developer_name', $project?->developer_name) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Total Area --}}

                                <div>

                                    <label
                                        for="project_total_area"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Total Area
                                    </label>

                                    <input
                                        type="number"
                                        name="total_area"
                                        id="project_total_area"
                                        step="0.01"
                                        min="0"
                                        value="{{ old('total_area', $project?->total_area) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Area Unit --}}

                                <div>

                                    <label
                                        for="project_area_unit"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Area Unit
                                    </label>

                                    <input
                                        type="text"
                                        name="area_unit"
                                        id="project_area_unit"
                                        value="{{ old('area_unit', $project?->area_unit) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="e.g. sq.ft, acre"
                                    >

                                </div>


                                {{-- Start Date --}}

                                <div>

                                    <label
                                        for="start_date"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Start Date
                                    </label>

                                    <input
                                        type="date"
                                        name="start_date"
                                        id="start_date"
                                        value="{{ old('start_date', optional($project?->start_date)->format('Y-m-d')) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Completion Date --}}

                                <div>

                                    <label
                                        for="completion_date"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Completion Date
                                    </label>

                                    <input
                                        type="date"
                                        name="completion_date"
                                        id="completion_date"
                                        value="{{ old('completion_date', optional($project?->completion_date)->format('Y-m-d')) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                    >

                                </div>


                                {{-- Project Description --}}

                                <div class="md:col-span-2">

                                    <label
                                        for="project_description"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Project Description
                                    </label>

                                    <textarea
                                        name="project_description"
                                        id="project_description"
                                        rows="5"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#004C47] focus:ring-2 focus:ring-[#004C47]"
                                        placeholder="Describe the project..."
                                    >{{ old('project_description', $project?->description) }}</textarea>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             CURRENT IMAGE
                        ================================================== --}}

                        <div class="mb-8">

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Listing Image
                            </h2>

                            @if($listing->image)

                                <div class="mb-5">

                                    <p class="mb-2 text-sm font-medium text-gray-700">
                                        Current Image
                                    </p>

                                    <img
                                        src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                                        alt="{{ $listing->title }}"
                                        class="h-40 w-40 rounded-xl border border-gray-200 object-cover"
                                    >

                                </div>

                            @endif

                            <label
                                for="image"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Change Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                id="image"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5"
                            >

                            <p class="mt-2 text-xs text-gray-500">
                                Leave empty to keep the current image. Maximum 5 MB.
                            </p>

                            @error('image')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             DOCUMENTS
                        ================================================== --}}

                        <div class="mb-8">

                            <h2 class="mb-5 text-lg font-semibold text-[#163B37]">
                                Documents
                            </h2>


                            {{-- Existing Documents --}}

                            @if($listing->documents && $listing->documents->count())

                                <div class="mb-5 space-y-3">

                                    @foreach($listing->documents as $document)

                                        <div class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 sm:flex-row sm:items-center sm:justify-between">


                                            {{-- Document Info --}}

                                            <div class="flex min-w-0 items-center gap-3">

                                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#004C47] text-white">

                                                    <span class="material-symbols-outlined">
                                                        description
                                                    </span>

                                                </div>

                                                <div class="min-w-0">

                                                    <p class="truncate text-sm font-medium text-gray-800">
                                                        {{ $document->document_name }}
                                                    </p>

                                                    <p class="text-xs text-gray-500">

                                                        Status:
                                                        {{ ucfirst($document->status) }}

                                                    </p>

                                                </div>

                                            </div>


                                            {{-- Document Actions --}}

                                            <div class="flex flex-wrap items-center gap-2">


                                                {{-- Download --}}

                                                <a
                                                    href="{{ route('seller.listings.documents.download', [
                                                        'listing' => $listing->id,
                                                        'document' => $document->id
                                                    ]) }}"
                                                    class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                                >
                                                    Download
                                                </a>


                                                {{-- Remove --}}

                                                <label
                                                    class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50"
                                                >

                                                    <input
                                                        type="checkbox"
                                                        name="remove_documents[]"
                                                        value="{{ $document->id }}"
                                                        class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                                                    >

                                                    Remove

                                                </label>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <div class="mb-5 rounded-xl border border-dashed border-gray-300 p-4 text-sm text-gray-500">
                                    No documents uploaded yet.
                                </div>

                            @endif


                            {{-- Upload New Documents --}}

                            <label
                                for="documents"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Add / Change Documents
                            </label>

                            <input
                                type="file"
                                name="documents[]"
                                id="documents"
                                multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="w-full rounded-xl border border-gray-300 px-4 py-2.5"
                            >

                            <p class="mt-2 text-xs text-gray-500">
                                You can upload multiple documents. Maximum 5 MB per document.
                            </p>

                            @error('documents')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                            @error('documents.*')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             APPROVAL NOTE
                        ================================================== --}}

                        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4">

                            <div class="flex items-start gap-3">

                                <span class="material-symbols-outlined text-amber-600">
                                    info
                                </span>

                                <div>

                                    <p class="text-sm font-semibold text-amber-800">
                                        Admin Approval
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-amber-700">
                                        After updating this listing, it will be submitted
                                        for admin approval again.
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             BUTTONS
                        ================================================== --}}

                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                            <a
                                href="{{ route('seller.listings.index') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#004C47] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#003F3B]"
                            >

                                <span class="material-symbols-outlined text-[20px]">
                                    save
                                </span>

                                Update Listing

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
         CATEGORY + SUBCATEGORY SWITCHING
    ========================================================== --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const categorySelect =
                document.getElementById('category');

            const subcategorySelect =
                document.getElementById('subcategory_id');


            const sections = {

                product:
                    document.getElementById('productFields'),

                property:
                    document.getElementById('propertyFields'),

                land:
                    document.getElementById('landFields'),

                service:
                    document.getElementById('serviceFields'),

                project:
                    document.getElementById('projectFields'),

            };


            /*
            |--------------------------------------------------------------------------
            | Category -> Database Category Slug
            |--------------------------------------------------------------------------
            */

            const categorySlugMap = {

                product: 'products',

                property: 'properties',

                land: 'land',

                service: 'services',

                project: 'projects'

            };


            /*
            |--------------------------------------------------------------------------
            | Category IDs From Database
            |--------------------------------------------------------------------------
            */

            const categoryIds = @json($categoryIds);


            /*
            |--------------------------------------------------------------------------
            | Update Product / Property / Land / Service / Project Fields
            |--------------------------------------------------------------------------
            */

            function updateCategoryFields() {

                if (!categorySelect) {
                    return;
                }

                const selected =
                    categorySelect.value;


                Object.values(sections).forEach(function (section) {

                    if (section) {

                        section.classList.add('hidden');

                    }

                });


                if (sections[selected]) {

                    sections[selected]
                        .classList.remove('hidden');

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Update Subcategories
            |--------------------------------------------------------------------------
            */

            function updateSubcategories(
                selectedSubcategory = ''
            ) {

                if (!subcategorySelect || !categorySelect) {
                    return;
                }


                const selectedCategory =
                    categorySelect.value;


                const categorySlug =
                    categorySlugMap[selectedCategory];


                const categoryId =
                    categorySlug &&
                    categoryIds[categorySlug]
                        ? String(categoryIds[categorySlug])
                        : '';


                let hasSubcategories = false;


                Array.from(
                    subcategorySelect.options
                ).forEach(function (option, index) {

                    /*
                    |--------------------------------------------------------------------------
                    | Always keep first placeholder visible
                    |--------------------------------------------------------------------------
                    */

                    if (index === 0) {

                        option.hidden = false;
                        option.disabled = false;

                        return;

                    }


                    const optionCategoryId =
                        String(
                            option.dataset.categoryId || ''
                        );


                    if (
                        categoryId !== '' &&
                        optionCategoryId === categoryId
                    ) {

                        option.hidden = false;
                        option.disabled = false;

                        hasSubcategories = true;

                    } else {

                        option.hidden = true;
                        option.disabled = true;

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | Keep Existing Subcategory Selected
                |--------------------------------------------------------------------------
                */

                if (
                    selectedSubcategory !== '' &&
                    hasSubcategories
                ) {

                    const matchingOption =
                        Array.from(
                            subcategorySelect.options
                        ).find(function (option) {

                            return (
                                String(option.value) ===
                                String(selectedSubcategory)
                            );

                        });


                    if (
                        matchingOption &&
                        !matchingOption.disabled
                    ) {

                        subcategorySelect.value =
                            selectedSubcategory;

                    } else {

                        subcategorySelect.value = '';

                    }

                } else {

                    subcategorySelect.value = '';

                }


                /*
                |--------------------------------------------------------------------------
                | Disable Dropdown If Category Has No Subcategories
                |--------------------------------------------------------------------------
                */

                subcategorySelect.disabled =
                    !hasSubcategories;


                if (!hasSubcategories) {

                    subcategorySelect.value = '';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Initial Page Load
            |--------------------------------------------------------------------------
            */

            if (categorySelect) {

                updateCategoryFields();


                updateSubcategories(
                    @json((string) $selectedSubcategoryId)
                );


                /*
                |--------------------------------------------------------------------------
                | Category Change
                |--------------------------------------------------------------------------
                */

                categorySelect.addEventListener(
                    'change',
                    function () {

                        updateCategoryFields();

                        /*
                        | When category changes, remove old
                        | subcategory selection.
                        */

                        updateSubcategories('');

                    }
                );

            }

        });

    </script>

</x-seller-layout>