{{-- resources/views/seller/listings/create.blade.php --}}

<x-seller-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <x-slot name="header">

        <div>
            <h2 class="text-2xl font-bold text-[#056663]">
                Add {{ ucfirst($type) }} Listing
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Add your {{ ucfirst($type) }} details to the marketplace.
            </p>
        </div>

    </x-slot>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="bg-[#F5F9F8] px-4 py-6 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-6xl">


            {{-- =====================================================
                 VALIDATION ERRORS
            ====================================================== --}}

            @if($errors->any())

                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

                    <div class="flex items-start gap-3">

                        <span class="material-symbols-outlined text-red-500">
                            error
                        </span>

                        <div>

                            <h3 class="font-semibold text-red-700">
                                Please fix the following errors:
                            </h3>

                            <ul class="mt-2 space-y-1 text-sm text-red-600">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 MAIN FORM
            ====================================================== --}}

            <form
                action="{{ route('seller.listings.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6"
            >

                @csrf


                {{-- =================================================
                     COMMON LISTING INFORMATION
                ================================================== --}}

                <div class="rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">


                    {{-- Section Header --}}
                    <div class="border-b border-gray-100 px-6 py-5">

                        <h3 class="text-lg font-bold text-gray-900">
                            Basic Listing Information
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Enter the common information for your listing.
                        </p>

                    </div>


                    {{-- Fields --}}
                    <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">


                        {{-- =================================================
                             CATEGORY
                        ================================================== --}}

                        <div>

                            <label
                                for="category_display"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Category
                            </label>

                            <input
                                type="text"
                                id="category_display"
                                value="{{ ucfirst($type) }}"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 text-gray-700"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="category"
                                value="{{ $type }}"
                            >

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
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >

                                <option value="">
                                    Select Subcategory
                                </option>

                                @foreach($subcategories as $subcategory)

                                    <option
                                        value="{{ $subcategory->id }}"
                                        {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}
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

                        </div>


                        {{-- =================================================
                             TITLE
                        ================================================== --}}

                        <div class="md:col-span-2">

                            <label
                                for="title"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Listing Title
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="{{ old('title') }}"
                                placeholder="Enter listing title"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                required
                            >

                            @error('title')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             DESCRIPTION
                        ================================================== --}}

                        <div class="md:col-span-2">

                            <label
                                for="description"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Description
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                placeholder="Describe your listing..."
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                required
                            >{{ old('description') }}</textarea>

                            @error('description')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             LISTING TYPE
                        ================================================== --}}

                        <div>

                            <label
                                for="listing_type"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Listing Type
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                name="listing_type"
                                id="listing_type"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                required
                            >

                                <option value="">
                                    Select Type
                                </option>

                                <option
                                    value="sale"
                                    {{ old('listing_type') === 'sale' ? 'selected' : '' }}
                                >
                                    Sale
                                </option>

                                <option
                                    value="rent"
                                    {{ old('listing_type') === 'rent' ? 'selected' : '' }}
                                >
                                    Rent
                                </option>

                                <option
                                    value="lease"
                                    {{ old('listing_type') === 'lease' ? 'selected' : '' }}
                                >
                                    Lease
                                </option>

                                <option
                                    value="buy"
                                    {{ old('listing_type') === 'buy' ? 'selected' : '' }}
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


                        {{-- =================================================
                             PRICE
                        ================================================== --}}

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
                                value="{{ old('price') }}"
                                placeholder="Enter price"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >

                            @error('price')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             PRICE UNIT
                        ================================================== --}}

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
                                value="{{ old('price_unit') }}"
                                placeholder="e.g. sq.ft, kg, piece"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >

                            @error('price_unit')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             CITY
                        ================================================== --}}

                        <div>

                            <label
                                for="city"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                City
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="city"
                                id="city"
                                value="{{ old('city') }}"
                                placeholder="Enter city"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                required
                            >

                            @error('city')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             STATE
                        ================================================== --}}

                        <div>

                            <label
                                for="state"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                State
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="state"
                                id="state"
                                value="{{ old('state') }}"
                                placeholder="Enter state"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                required
                            >

                            @error('state')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =================================================
                             PINCODE
                        ================================================== --}}

                        <div>

                            <label
                                for="pincode"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Pincode
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                name="pincode"
                                id="pincode"
                                value="{{ old('pincode') }}"
                                placeholder="Enter pincode"
                                maxlength="6"
                                inputmode="numeric"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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


                {{-- =========================================================
                     PRODUCT
                ========================================================== --}}

                @if($type === 'product')

                    <div class="rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

                        <div class="border-b border-gray-100 px-6 py-5">

                            <h3 class="text-lg font-bold text-gray-900">
                                Product Details
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Add product-specific information.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">


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
                                    value="{{ old('brand') }}"
                                    placeholder="Enter brand name"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('product_code') }}"
                                    placeholder="Enter product code"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('quantity') }}"
                                    placeholder="Enter quantity"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('unit') }}"
                                    placeholder="e.g. kg, piece, ton"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    rows="4"
                                    placeholder="Enter product specifications"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                >{{ old('specifications') }}</textarea>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- =========================================================
                     PROPERTY
                ========================================================== --}}

                @if($type === 'property')

                    <div class="rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

                        <div class="border-b border-gray-100 px-6 py-5">

                            <h3 class="text-lg font-bold text-gray-900">
                                Property Details
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Add property-specific information.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">


                            {{-- Property Type --}}
                            <div>

                                <label
                                    for="property_type"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Property Type
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="property_type"
                                    id="property_type"
                                    value="{{ old('property_type') }}"
                                    placeholder="e.g. Apartment, Villa, Office"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                    required
                                >

                            </div>


                            {{-- Area --}}
                            <div>

                                <label
                                    for="area"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Area
                                </label>

                                <input
                                    type="number"
                                    name="area"
                                    id="area"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('area') }}"
                                    placeholder="Enter area"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                >

                            </div>


                            {{-- Area Unit --}}
                            <div>

                                <label
                                    for="area_unit"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Area Unit
                                </label>

                                <input
                                    type="text"
                                    name="area_unit"
                                    id="area_unit"
                                    value="{{ old('area_unit') }}"
                                    placeholder="e.g. sq.ft"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('bedrooms') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('bathrooms') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('property_status') }}"
                                    placeholder="e.g. Ready to Move"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                @endphp

                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">

                                    @foreach($amenityOptions as $amenity)

                                        <label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700">

                                            <input
                                                type="checkbox"
                                                name="amenities[]"
                                                value="{{ $amenity }}"
                                                class="rounded border-gray-300 text-[#056663] focus:ring-[#056663]"
                                                {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}
                                            >

                                            {{ $amenity }}

                                        </label>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- =========================================================
                     LAND
                ========================================================== --}}

                @if($type === 'land')

                    <div class="rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

                        <div class="border-b border-gray-100 px-6 py-5">

                            <h3 class="text-lg font-bold text-gray-900">
                                Land Details
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Add land-specific information.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">


                            {{-- Land Type --}}
                            <div>

                                <label
                                    for="land_type"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Land Type
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="land_type"
                                    id="land_type"
                                    value="{{ old('land_type') }}"
                                    placeholder="e.g. Residential, Agricultural"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                    required
                                >

                            </div>


                            {{-- Total Area --}}
                            <div>

                                <label
                                    for="total_area"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Total Area
                                </label>

                                <input
                                    type="number"
                                    name="total_area"
                                    id="total_area"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('total_area') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                >

                            </div>


                            {{-- Area Unit --}}
                            <div>

                                <label
                                    for="area_unit"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Area Unit
                                </label>

                                <input
                                    type="text"
                                    name="area_unit"
                                    id="area_unit"
                                    value="{{ old('area_unit') }}"
                                    placeholder="e.g. acre, hectare, sq.ft"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('survey_number') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('gat_number') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                >

                            </div>


                            {{-- Ownership Type --}}
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
                                    value="{{ old('ownership_type') }}"
                                    placeholder="e.g. Freehold"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('road_access') }}"
                                    placeholder="e.g. 20 ft road"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('zoning') }}"
                                    placeholder="e.g. Residential"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                >

                            </div>


                            {{-- Water --}}
                            <div class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="water_availability"
                                    value="1"
                                    class="rounded border-gray-300 text-[#056663] focus:ring-[#056663]"
                                    {{ old('water_availability') ? 'checked' : '' }}
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
                                    {{ old('electricity_availability') ? 'checked' : '' }}
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
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                    placeholder="Schools, highways, hospitals, markets, etc."
                                >{{ old('nearby_infrastructure') }}</textarea>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- =========================================================
                     SERVICE
                ========================================================== --}}

                @if($type === 'service')

                    <div class="rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

                        <div class="border-b border-gray-100 px-6 py-5">

                            <h3 class="text-lg font-bold text-gray-900">
                                Service Details
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Add service-specific information.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">


                            {{-- Service Type --}}
                            <div>

                                <label
                                    for="service_type"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Service Type
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="service_type"
                                    id="service_type"
                                    value="{{ old('service_type') }}"
                                    placeholder="Enter service type"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                    required
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
                                    value="{{ old('service_area') }}"
                                    placeholder="Enter service area"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('experience') }}"
                                    placeholder="e.g. 5 years"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('availability') }}"
                                    placeholder="e.g. Monday - Saturday"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                    placeholder="Describe the service..."
                                >{{ old('service_description') }}</textarea>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- =========================================================
                     PROJECT
                ========================================================== --}}

                @if($type === 'project')

                    <div class="rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

                        <div class="border-b border-gray-100 px-6 py-5">

                            <h3 class="text-lg font-bold text-gray-900">
                                Project Details
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Add project-specific information.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">


                            {{-- Project Type --}}
                            <div>

                                <label
                                    for="project_type"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Project Type
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="project_type"
                                    id="project_type"
                                    value="{{ old('project_type') }}"
                                    placeholder="Enter project type"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                    required
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
                                    value="{{ old('project_status') }}"
                                    placeholder="e.g. Ongoing, Upcoming"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('developer_name') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                >

                            </div>


                            {{-- Total Area --}}
                            <div>

                                <label
                                    for="total_area"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Total Area
                                </label>

                                <input
                                    type="number"
                                    name="total_area"
                                    id="total_area"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('total_area') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                >

                            </div>


                            {{-- Area Unit --}}
                            <div>

                                <label
                                    for="area_unit"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Area Unit
                                </label>

                                <input
                                    type="text"
                                    name="area_unit"
                                    id="area_unit"
                                    value="{{ old('area_unit') }}"
                                    placeholder="e.g. sq.ft, acre"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('start_date') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    value="{{ old('completion_date') }}"
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
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
                                    class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                                >{{ old('project_description') }}</textarea>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- =========================================================
                     IMAGE & DOCUMENTS
                ========================================================== --}}

                <div class="rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

                    <div class="border-b border-gray-100 px-6 py-5">

                        <h3 class="text-lg font-bold text-gray-900">
                            Images & Documents
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Upload a main listing image and supporting documents.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">


                        {{-- Image --}}
                        <div>

                            <label
                                for="image"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Listing Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                id="image"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="block w-full rounded-xl border border-gray-300 text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-[#E8F5F4] file:px-4 file:py-2 file:text-sm file:font-medium file:text-[#056663] hover:file:bg-[#D8EFEC]"
                            >

                            <p class="mt-2 text-xs text-gray-400">
                                JPG, JPEG, PNG or WEBP. Maximum 5 MB.
                            </p>

                            @error('image')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- Documents --}}
                        <div>

                            <label
                                for="documents"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Supporting Documents
                            </label>

                            <input
                                type="file"
                                name="documents[]"
                                id="documents"
                                multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full rounded-xl border border-gray-300 text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-[#E8F5F4] file:px-4 file:py-2 file:text-sm file:font-medium file:text-[#056663] hover:file:bg-[#D8EFEC]"
                            >

                            <p class="mt-2 text-xs text-gray-400">
                                PDF, DOC, DOCX, JPG, JPEG or PNG. Maximum 5 MB per file.
                            </p>

                            @error('documents.*')

                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                     ACTIONS
                ========================================================== --}}

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('seller.listings.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#056663] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#045450]"
                    >

                        <span class="material-symbols-outlined text-[20px]">
                            add_circle
                        </span>

                        Submit Listing

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-seller-layout>