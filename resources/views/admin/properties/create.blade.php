@extends('admin.layouts.app')

@section('title', 'Add Property Listing')

@section('page-title', 'Add Property Listing')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Add Property Listing
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Create a new property listing from the admin panel.
            </p>
        </div>

        <a
            href="{{ route('admin.properties.index') }}"
            class="rounded-lg border border-gray-300 px-5 py-2.5
                   text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Back to Properties
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
        <p class="font-semibold text-red-700">
            Please fix the following errors:
        </p>

        <ul class="mt-2 list-inside list-disc text-sm text-red-600">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
    <div class="rounded-lg border border-green-200 bg-green-50 p-4">
        <p class="text-sm font-medium text-green-700">
            {{ session('success') }}
        </p>
    </div>
    @endif

    {{-- Form --}}
    <form
        action="{{ route('admin.properties.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        {{-- ========================================================= --}}
        {{-- BASIC LISTING INFORMATION --}}
        {{-- ========================================================= --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="mb-5 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-900">
                Basic Listing Information
            </h2>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Property Title --}}
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Property Title
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="Enter property title"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Description
                        <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        required
                        placeholder="Enter property description"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">{{ old('description') }}</textarea>
                </div>

                {{-- Listing Type --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Listing Type
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="listing_type"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                        <option value="">Select Listing Type</option>

                        <option
                            value="sale"
                            {{ old('listing_type') === 'sale' ? 'selected' : '' }}>
                            For Sale
                        </option>

                        <option
                            value="rent"
                            {{ old('listing_type') === 'rent' ? 'selected' : '' }}>
                            For Rent
                        </option>

                        <option
                            value="lease"
                            {{ old('listing_type') === 'lease' ? 'selected' : '' }}>
                            For Lease
                        </option>
                    </select>
                </div>

                {{-- Category --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Category
                    </label>

                    <input
                        type="text"
                        value="Properties"
                        readonly
                        class="w-full rounded-lg border border-gray-300 bg-gray-100
                               px-4 py-2.5 text-sm text-gray-600">

                    <input
                        type="hidden"
                        name="category_id"
                        value="1">
                </div>

            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- PROPERTY DETAILS --}}
        {{-- ========================================================= --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="mb-5 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-900">
                Property Details
            </h2>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Property Type --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Property Type
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="property_type"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                        <option value="">Select Property Type</option>

                        <option
                            value="apartment"
                            {{ old('property_type') === 'apartment' ? 'selected' : '' }}>
                            Apartment
                        </option>

                        <option
                            value="flat"
                            {{ old('property_type') === 'flat' ? 'selected' : '' }}>
                            Flat
                        </option>

                        <option
                            value="villa"
                            {{ old('property_type') === 'villa' ? 'selected' : '' }}>
                            Villa
                        </option>

                        <option
                            value="house"
                            {{ old('property_type') === 'house' ? 'selected' : '' }}>
                            House
                        </option>

                        <option
                            value="commercial"
                            {{ old('property_type') === 'commercial' ? 'selected' : '' }}>
                            Commercial
                        </option>

                        <option
                            value="office"
                            {{ old('property_type') === 'office' ? 'selected' : '' }}>
                            Office
                        </option>

                        <option
                            value="shop"
                            {{ old('property_type') === 'shop' ? 'selected' : '' }}>
                            Shop
                        </option>

                        <option
                            value="warehouse"
                            {{ old('property_type') === 'warehouse' ? 'selected' : '' }}>
                            Warehouse
                        </option>

                        <option
                            value="other"
                            {{ old('property_type') === 'other' ? 'selected' : '' }}>
                            Other
                        </option>
                    </select>
                </div>


                {{-- Area --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Area
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        name="area"
                        value="{{ old('area') }}"
                        step="0.01"
                        min="0"
                        required
                        placeholder="Enter area"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                </div>


                {{-- Area Unit --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Area Unit
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="area_unit"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                        <option value="">Select Area Unit</option>

                        <option
                            value="sq ft"
                            {{ old('area_unit') === 'sq ft' ? 'selected' : '' }}>
                            Sq. Ft.
                        </option>

                        <option
                            value="sq m"
                            {{ old('area_unit') === 'sq m' ? 'selected' : '' }}>
                            Sq. M.
                        </option>

                        <option
                            value="sq yard"
                            {{ old('area_unit') === 'sq yard' ? 'selected' : '' }}>
                            Sq. Yard
                        </option>

                        <option
                            value="acre"
                            {{ old('area_unit') === 'acre' ? 'selected' : '' }}>
                            Acre
                        </option>

                        <option
                            value="hectare"
                            {{ old('area_unit') === 'hectare' ? 'selected' : '' }}>
                            Hectare
                        </option>
                    </select>
                </div>


                {{-- Bedrooms --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Bedrooms
                    </label>

                    <input
                        type="number"
                        name="bedrooms"
                        value="{{ old('bedrooms', 0) }}"
                        min="0"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                </div>


                {{-- Bathrooms --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Bathrooms
                    </label>

                    <input
                        type="number"
                        name="bathrooms"
                        value="{{ old('bathrooms', 0) }}"
                        min="0"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                </div>


                {{-- Property Status --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Property Status
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="property_status"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                        <option value="">Select Property Status</option>

                        <option
                            value="available"
                            {{ old('property_status') === 'available' ? 'selected' : '' }}>
                            Available
                        </option>

                        <option
                            value="ready to move"
                            {{ old('property_status') === 'ready to move' ? 'selected' : '' }}>
                            Ready to Move
                        </option>

                        <option
                            value="under construction"
                            {{ old('property_status') === 'under construction' ? 'selected' : '' }}>
                            Under Construction
                        </option>

                        <option
                            value="sold"
                            {{ old('property_status') === 'sold' ? 'selected' : '' }}>
                            Sold
                        </option>

                        <option
                            value="on hold"
                            {{ old('property_status') === 'on hold' ? 'selected' : '' }}>
                            On Hold
                        </option>
                    </select>
                </div>


                {{-- Amenities --}}
                <div class="md:col-span-2">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Amenities
                    </label>

                    @php
                    $amenities = [
                    'Parking',
                    'Lift',
                    'Security',
                    'Garden',
                    'Swimming Pool',
                    'Gym',
                    'Power Backup',
                    'CCTV',
                    ];
                    @endphp

                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">

                        @foreach ($amenities as $amenity)
                        <label class="flex items-center gap-2 text-sm text-gray-700">

                            <input
                                type="checkbox"
                                name="amenities[]"
                                value="{{ $amenity }}"
                                class="rounded border-gray-300 text-[#056663]
                                           focus:ring-[#056663]"
                                {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}>

                            {{ $amenity }}

                        </label>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- PRICE AND LOCATION --}}
        {{-- ========================================================= --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="mb-5 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-900">
                Price and Location
            </h2>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Price --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Price
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        name="price"
                        value="{{ old('price') }}"
                        min="0"
                        step="0.01"
                        required
                        placeholder="Enter price"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                </div>


                {{-- Price Unit --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Price Unit
                    </label>

                    <select
                        name="price_unit"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                        <option value="">Select Price Unit</option>

                        <option
                            value="total"
                            {{ old('price_unit') === 'total' ? 'selected' : '' }}>
                            Total Price
                        </option>

                        <option
                            value="per sq ft"
                            {{ old('price_unit') === 'per sq ft' ? 'selected' : '' }}>
                            Per Sq. Ft.
                        </option>

                        <option
                            value="per sq m"
                            {{ old('price_unit') === 'per sq m' ? 'selected' : '' }}>
                            Per Sq. M.
                        </option>

                        <option
                            value="per month"
                            {{ old('price_unit') === 'per month' ? 'selected' : '' }}>
                            Per Month
                        </option>
                    </select>
                </div>


                {{-- City --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        City
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city') }}"
                        required
                        placeholder="Enter city"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                </div>


                {{-- State --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        State
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="state"
                        value="{{ old('state') }}"
                        required
                        placeholder="Enter state"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                </div>


                {{-- Pincode --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Pincode
                    </label>

                    <input
                        type="text"
                        name="pincode"
                        value="{{ old('pincode') }}"
                        maxlength="10"
                        placeholder="Enter pincode"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663] focus:ring-[#056663]">
                </div>


                
                {{-- Property Image --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Property Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        class="block w-full rounded-lg border border-gray-300
               px-3 py-2 text-sm">

                    <p class="mt-1 text-xs text-gray-500">
                        JPG, JPEG, PNG or WEBP. Maximum size: 5 MB.
                    </p>

                    @error('image')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>


                {{-- Property Document --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Property Document
                    </label>

                    <input
                        type="file"
                        name="document"
                        accept=".pdf,.doc,.docx"
                        class="block w-full rounded-lg border border-gray-300
               px-3 py-2 text-sm">

                    <p class="mt-1 text-xs text-gray-500">
                        PDF, DOC or DOCX. Maximum size: 10 MB.
                    </p>

                    @error('document')
                    <p class="mt-1 text-xs text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>


            </div>
        </div>


        {{-- ========================================================= --}}
        {{-- BUTTONS --}}
        {{-- ========================================================= --}}
        <div class="flex justify-end gap-3">

            <a
                href="{{ route('admin.properties.index') }}"
                class="rounded-lg border border-gray-300 px-6 py-3
                       text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-[#056663] px-6 py-3
                       text-sm font-semibold text-white hover:bg-[#04524F]">
                Save Property Listing
            </button>

        </div>

    </form>

</div>

@endsection