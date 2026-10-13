@extends('layouts.base')

@section('title', 'General Requirement')

@section('content')

<div class="min-h-screen bg-[#F7FBFA] py-10 mt-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 mt-20">

        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                        <a href="{{ url('/') }}" class="hover:text-[#056663]">
                            Home
                        </a>

                        <span>/</span>

                        <span class="text-[#056663]">
                            General Requirement
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900">
                        Tell Us What You Need
                    </h1>

                    <p class="mt-2 max-w-2xl text-gray-600">
                        Submit your requirement and our team will review it and connect
                        you with suitable sellers.
                    </p>
                </div>

                <div class="hidden rounded-2xl bg-[#E8F5F4] px-5 py-4 sm:block">
                    <span class="material-symbols-outlined text-3xl text-[#056663]">
                        support_agent
                    </span>
                </div>

            </div>
        </div>


        {{-- =========================================================
            PROCESS STEPS
        ========================================================== --}}
        <div class="mb-8 rounded-2xl border border-[#D3E3E0] bg-white p-5 shadow-sm">

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                {{-- Step 1 --}}
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#056663] text-white">
                        <span class="material-symbols-outlined">
                            edit_note
                        </span>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">
                            1. Submit Requirement
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Tell us what product, property, land, service or project you need.
                        </p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-[#056663]">
                        <span class="material-symbols-outlined">
                            manage_search
                        </span>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">
                            2. Admin Review
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Our team reviews your requirement and identifies suitable sellers.
                        </p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-[#056663]">
                        <span class="material-symbols-outlined">
                            handshake
                        </span>
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">
                            3. Seller Connection
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Suitable sellers can receive RFQs and submit quotations.
                        </p>
                    </div>
                </div>

            </div>
        </div>


        {{-- =========================================================
            SUCCESS MESSAGE
        ========================================================== --}}
        @if(session('success'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                <span class="material-symbols-outlined">
                    check_circle
                </span>

                <div>
                    <p class="font-semibold">
                        Success
                    </p>

                    <p class="text-sm">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif


        {{-- =========================================================
            ERROR MESSAGE
        ========================================================== --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">

                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined">
                        error
                    </span>

                    <div>
                        <p class="font-semibold">
                            Please correct the following errors:
                        </p>

                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        @endif


        {{-- =========================================================
            FORM
        ========================================================== --}}
        <form
            method="POST"
            action="{{ route('requirements.general.store') }}"
            class="space-y-6"
        >
            @csrf

            <input
                type="hidden"
                name="requirement_source"
                value="general"
            >


            {{-- =====================================================
                CONTACT INFORMATION
            ====================================================== --}}
            <div class="overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm">

                <div class="border-b border-[#D3E3E0] bg-[#F8FCFB] px-6 py-5">
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <span class="material-symbols-outlined">
                                person
                            </span>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Contact Information
                            </h2>

                            <p class="text-sm text-gray-500">
                                We will use these details to contact you regarding your requirement.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-3">

                    {{-- Name --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Full Name <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', auth()->user()->name ?? '') }}"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter your name"
                        >

                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Email --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email ?? '') }}"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="you@example.com"
                        >

                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Phone --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Phone <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone', auth()->user()->phone ?? '') }}"
                            required
                            maxlength="10"
                            minlength="10"
                            pattern="[0-9]{10}"
                            inputmode="numeric"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="10 digit mobile number"
                        >

                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- =====================================================
                REQUIREMENT TYPE
            ====================================================== --}}
            <div class="overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm">

                <div class="border-b border-[#D3E3E0] bg-[#F8FCFB] px-6 py-5">
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <span class="material-symbols-outlined">
                                category
                            </span>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Requirement Type
                            </h2>

                            <p class="text-sm text-gray-500">
                                Select the type of requirement you want to submit.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="p-6">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        What are you looking for?
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="requirement_type"
                        id="requirement_type"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                    >
                        <option value="">
                            Select requirement type
                        </option>

                        <option
                            value="product"
                            {{ old('requirement_type') === 'product' ? 'selected' : '' }}
                        >
                            Product
                        </option>

                        <option
                            value="property"
                            {{ old('requirement_type') === 'property' ? 'selected' : '' }}
                        >
                            Property
                        </option>

                        <option
                            value="land"
                            {{ old('requirement_type') === 'land' ? 'selected' : '' }}
                        >
                            Land
                        </option>

                        <option
                            value="service"
                            {{ old('requirement_type') === 'service' ? 'selected' : '' }}
                        >
                            Service
                        </option>

                        <option
                            value="project"
                            {{ old('requirement_type') === 'project' ? 'selected' : '' }}
                        >
                            Project
                        </option>
                    </select>

                    @error('requirement_type')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>
            </div>


            {{-- =====================================================
                PRODUCT REQUIREMENT
            ====================================================== --}}
            <div
                id="product-fields"
                class="requirement-fields hidden overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm"
            >

                <div class="border-b border-[#D3E3E0] bg-[#F8FCFB] px-6 py-5">
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <span class="material-symbols-outlined">
                                inventory_2
                            </span>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Product Requirement
                            </h2>

                            <p class="text-sm text-gray-500">
                                Provide details about the product you need.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    {{-- Product Name --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Product Name <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="product_name"
                            value="{{ old('product_name') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="e.g. Cement, Steel, Tiles"
                        >

                        @error('product_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Product Category --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Product Category
                        </label>

                        <input
                            type="text"
                            name="product_category"
                            value="{{ old('product_category') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="e.g. Building Materials"
                        >
                    </div>


                    {{-- Product Quality --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Product Quality
                        </label>

                        <input
                            type="text"
                            name="product_quality"
                            value="{{ old('product_quality') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="e.g. Premium, Standard"
                        >
                    </div>


                    {{-- Preferred Brand --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Preferred Brand
                        </label>

                        <input
                            type="text"
                            name="preferred_brand"
                            value="{{ old('preferred_brand') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter preferred brand"
                        >
                    </div>


                    {{-- Quantity --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Quantity <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            value="{{ old('quantity') }}"
                            min="1"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter quantity"
                        >

                        @error('quantity')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Unit --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Unit <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="unit"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select unit</option>
                            <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>Kg</option>
                            <option value="ton" {{ old('unit') === 'ton' ? 'selected' : '' }}>Ton</option>
                            <option value="piece" {{ old('unit') === 'piece' ? 'selected' : '' }}>Piece</option>
                            <option value="box" {{ old('unit') === 'box' ? 'selected' : '' }}>Box</option>
                            <option value="meter" {{ old('unit') === 'meter' ? 'selected' : '' }}>Meter</option>
                            <option value="sqft" {{ old('unit') === 'sqft' ? 'selected' : '' }}>Sq. Ft.</option>
                            <option value="other" {{ old('unit') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>

                        @error('unit')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Budget --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Budget
                        </label>

                        <input
                            type="number"
                            name="budget"
                            value="{{ old('budget') }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter budget"
                        >
                    </div>


                    {{-- Delivery Location --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Delivery Location
                        </label>

                        <input
                            type="text"
                            name="delivery_location"
                            value="{{ old('delivery_location') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter delivery location"
                        >
                    </div>


                    {{-- Product Specification --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Product Specification
                        </label>

                        <textarea
                            name="product_specification"
                            rows="4"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Mention size, grade, specification, technical requirements, etc."
                        >{{ old('product_specification') }}</textarea>
                    </div>

                </div>
            </div>


            {{-- =====================================================
                PROPERTY REQUIREMENT
            ====================================================== --}}
            <div
                id="property-fields"
                class="requirement-fields hidden overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm"
            >

                <div class="border-b border-[#D3E3E0] bg-[#F8FCFB] px-6 py-5">
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <span class="material-symbols-outlined">
                                apartment
                            </span>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Property Requirement
                            </h2>

                            <p class="text-sm text-gray-500">
                                Tell us about the property you are looking for.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    {{-- Property Type --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Property Type <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="property_type"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select property type</option>
                            <option value="residential" {{ old('property_type') === 'residential' ? 'selected' : '' }}>
                                Residential
                            </option>
                            <option value="commercial" {{ old('property_type') === 'commercial' ? 'selected' : '' }}>
                                Commercial
                            </option>
                            <option value="industrial" {{ old('property_type') === 'industrial' ? 'selected' : '' }}>
                                Industrial
                            </option>
                            <option value="office" {{ old('property_type') === 'office' ? 'selected' : '' }}>
                                Office
                            </option>
                            <option value="shop" {{ old('property_type') === 'shop' ? 'selected' : '' }}>
                                Shop
                            </option>
                            <option value="warehouse" {{ old('property_type') === 'warehouse' ? 'selected' : '' }}>
                                Warehouse
                            </option>
                            <option value="other" {{ old('property_type') === 'other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>

                        @error('property_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Property Purpose --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Purpose
                        </label>

                        <select
                            name="property_purpose"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select purpose</option>
                            <option value="buy" {{ old('property_purpose') === 'buy' ? 'selected' : '' }}>
                                Buy
                            </option>
                            <option value="rent" {{ old('property_purpose') === 'rent' ? 'selected' : '' }}>
                                Rent
                            </option>
                            <option value="lease" {{ old('property_purpose') === 'lease' ? 'selected' : '' }}>
                                Lease
                            </option>
                        </select>
                    </div>


                    {{-- Property Location --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Property Location <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="property_location"
                            value="{{ old('property_location') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Preferred property location"
                        >

                        @error('property_location')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Property Area --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Property Area
                        </label>

                        <input
                            type="number"
                            name="property_area"
                            value="{{ old('property_area') }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter area"
                        >

                        @error('property_area')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Property Area Unit --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Area Unit
                        </label>

                        <select
                            name="property_area_unit"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select unit</option>
                            <option value="sqft" {{ old('property_area_unit') === 'sqft' ? 'selected' : '' }}>
                                Sq. Ft.
                            </option>
                            <option value="sqm" {{ old('property_area_unit') === 'sqm' ? 'selected' : '' }}>
                                Sq. M.
                            </option>
                            <option value="acre" {{ old('property_area_unit') === 'acre' ? 'selected' : '' }}>
                                Acre
                            </option>
                            <option value="hectare" {{ old('property_area_unit') === 'hectare' ? 'selected' : '' }}>
                                Hectare
                            </option>
                        </select>
                    </div>


                    {{-- Bedrooms --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Bedrooms
                        </label>

                        <input
                            type="number"
                            name="bedrooms"
                            value="{{ old('bedrooms') }}"
                            min="0"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Number of bedrooms"
                        >
                    </div>


                    {{-- Bathrooms --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Bathrooms
                        </label>

                        <input
                            type="number"
                            name="bathrooms"
                            value="{{ old('bathrooms') }}"
                            min="0"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Number of bathrooms"
                        >
                    </div>


                    {{-- Budget --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Budget
                        </label>

                        <input
                            type="number"
                            name="budget"
                            value="{{ old('budget') }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter budget"
                        >
                    </div>


                    {{-- Property Requirements --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Additional Property Requirements
                        </label>

                        <textarea
                            name="property_requirements"
                            rows="4"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Mention furnishing, parking, floor preference, amenities, etc."
                        >{{ old('property_requirements') }}</textarea>
                    </div>

                </div>
            </div>


            {{-- =====================================================
                LAND REQUIREMENT
            ====================================================== --}}
            <div
                id="land-fields"
                class="requirement-fields hidden overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm"
            >

                <div class="border-b border-[#D3E3E0] bg-[#F8FCFB] px-6 py-5">
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <span class="material-symbols-outlined">
                                landscape
                            </span>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Land Requirement
                            </h2>

                            <p class="text-sm text-gray-500">
                                Provide details about the land you need.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    {{-- Land Type --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Land Type <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="land_type"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select land type</option>

                            <option value="residential" {{ old('land_type') === 'residential' ? 'selected' : '' }}>
                                Residential Land
                            </option>

                            <option value="commercial" {{ old('land_type') === 'commercial' ? 'selected' : '' }}>
                                Commercial Land
                            </option>

                            <option value="industrial" {{ old('land_type') === 'industrial' ? 'selected' : '' }}>
                                Industrial Land
                            </option>

                            <option value="agricultural" {{ old('land_type') === 'agricultural' ? 'selected' : '' }}>
                                Agricultural Land
                            </option>

                            <option value="other" {{ old('land_type') === 'other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>

                        @error('land_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Land Purpose --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Purpose
                        </label>

                        <select
                            name="land_purpose"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select purpose</option>

                            <option value="residential" {{ old('land_purpose') === 'residential' ? 'selected' : '' }}>
                                Residential
                            </option>

                            <option value="commercial" {{ old('land_purpose') === 'commercial' ? 'selected' : '' }}>
                                Commercial
                            </option>

                            <option value="industrial" {{ old('land_purpose') === 'industrial' ? 'selected' : '' }}>
                                Industrial
                            </option>

                            <option value="agricultural" {{ old('land_purpose') === 'agricultural' ? 'selected' : '' }}>
                                Agricultural
                            </option>

                            <option value="investment" {{ old('land_purpose') === 'investment' ? 'selected' : '' }}>
                                Investment
                            </option>
                        </select>
                    </div>


                    {{-- Land Location --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Land Location <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="land_location"
                            value="{{ old('land_location') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Preferred land location"
                        >

                        @error('land_location')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Land Area --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Land Area <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="number"
                            name="land_area"
                            value="{{ old('land_area') }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter land area"
                        >

                        @error('land_area')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Land Area Unit --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Land Area Unit <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="land_area_unit"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select unit</option>

                            <option value="sqft" {{ old('land_area_unit') === 'sqft' ? 'selected' : '' }}>
                                Sq. Ft.
                            </option>

                            <option value="sqm" {{ old('land_area_unit') === 'sqm' ? 'selected' : '' }}>
                                Sq. M.
                            </option>

                            <option value="acre" {{ old('land_area_unit') === 'acre' ? 'selected' : '' }}>
                                Acre
                            </option>

                            <option value="hectare" {{ old('land_area_unit') === 'hectare' ? 'selected' : '' }}>
                                Hectare
                            </option>
                        </select>

                        @error('land_area_unit')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Road Access --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Road Access
                        </label>

                        <select
                            name="road_access"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select road access</option>

                            <option value="yes" {{ old('road_access') === 'yes' ? 'selected' : '' }}>
                                Yes
                            </option>

                            <option value="no" {{ old('road_access') === 'no' ? 'selected' : '' }}>
                                No
                            </option>

                            <option value="highway" {{ old('road_access') === 'highway' ? 'selected' : '' }}>
                                Highway Access
                            </option>
                        </select>
                    </div>


                    {{-- Budget --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Budget
                        </label>

                        <input
                            type="number"
                            name="budget"
                            value="{{ old('budget') }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter budget"
                        >
                    </div>


                    {{-- Land Requirements --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Additional Land Requirements
                        </label>

                        <textarea
                            name="land_requirements"
                            rows="4"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Mention zoning, frontage, nearby facilities, road requirements, etc."
                        >{{ old('land_requirements') }}</textarea>
                    </div>

                </div>
            </div>


            {{-- =====================================================
                SERVICE REQUIREMENT
            ====================================================== --}}
            <div
                id="service-fields"
                class="requirement-fields hidden overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm"
            >

                <div class="border-b border-[#D3E3E0] bg-[#F8FCFB] px-6 py-5">
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <span class="material-symbols-outlined">
                                engineering
                            </span>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Service Requirement
                            </h2>

                            <p class="text-sm text-gray-500">
                                Tell us about the service you require.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    {{-- Service Name --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Service Name <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="service_name"
                            value="{{ old('service_name') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="e.g. Construction, Interior Design"
                        >

                        @error('service_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Service Type --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Service Type
                        </label>

                        <input
                            type="text"
                            name="service_type"
                            value="{{ old('service_type') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter service type"
                        >
                    </div>


                    {{-- Service Location --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Service Location <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="service_location"
                            value="{{ old('service_location') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Where is the service required?"
                        >

                        @error('service_location')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Service Timeline --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Service Timeline
                        </label>

                        <input
                            type="text"
                            name="service_timeline"
                            value="{{ old('service_timeline') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="e.g. Within 30 days"
                        >
                    </div>


                    {{-- Budget --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Budget
                        </label>

                        <input
                            type="number"
                            name="budget"
                            value="{{ old('budget') }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter budget"
                        >
                    </div>


                    {{-- Provider Experience --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Preferred Provider Experience
                        </label>

                        <input
                            type="text"
                            name="provider_experience"
                            value="{{ old('provider_experience') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="e.g. 5+ years"
                        >
                    </div>


                    {{-- Service Scope --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Service Scope <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            name="service_scope"
                            rows="5"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Describe the work or service you require"
                        >{{ old('service_scope') }}</textarea>

                        @error('service_scope')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- =====================================================
                PROJECT REQUIREMENT
            ====================================================== --}}
            <div
                id="project-fields"
                class="requirement-fields hidden overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm"
            >

                <div class="border-b border-[#D3E3E0] bg-[#F8FCFB] px-6 py-5">
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <span class="material-symbols-outlined">
                                construction
                            </span>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Project Requirement
                            </h2>

                            <p class="text-sm text-gray-500">
                                Provide information about your project.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    {{-- Project Type --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Project Type <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="project_type"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >
                            <option value="">Select project type</option>

                            <option value="residential" {{ old('project_type') === 'residential' ? 'selected' : '' }}>
                                Residential Project
                            </option>

                            <option value="commercial" {{ old('project_type') === 'commercial' ? 'selected' : '' }}>
                                Commercial Project
                            </option>

                            <option value="industrial" {{ old('project_type') === 'industrial' ? 'selected' : '' }}>
                                Industrial Project
                            </option>

                            <option value="infrastructure" {{ old('project_type') === 'infrastructure' ? 'selected' : '' }}>
                                Infrastructure
                            </option>

                            <option value="other" {{ old('project_type') === 'other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>

                        @error('project_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Project Name --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Project Name
                        </label>

                        <input
                            type="text"
                            name="project_name"
                            value="{{ old('project_name') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter project name"
                        >
                    </div>


                    {{-- Project Location --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Project Location <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="project_location"
                            value="{{ old('project_location') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter project location"
                        >

                        @error('project_location')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Project Area --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Project Area
                        </label>

                        <input
                            type="number"
                            name="project_area"
                            value="{{ old('project_area') }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter area in sq. ft."
                        >

                        @error('project_area')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Budget --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Budget
                        </label>

                        <input
                            type="number"
                            name="budget"
                            value="{{ old('budget') }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter project budget"
                        >
                    </div>


                    {{-- Project Timeline --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Project Timeline
                        </label>

                        <input
                            type="text"
                            name="project_timeline"
                            value="{{ old('project_timeline') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="e.g. 6 months"
                        >
                    </div>


                    {{-- Project Scope --}}
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Project Scope <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            name="project_scope"
                            rows="5"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Describe the complete scope of the project"
                        >{{ old('project_scope') }}</textarea>

                        @error('project_scope')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- =====================================================
                COMMON DELIVERY / DATE / MESSAGE
            ====================================================== --}}
            <div class="overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm">

                <div class="border-b border-[#D3E3E0] bg-[#F8FCFB] px-6 py-5">
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <span class="material-symbols-outlined">
                                info
                            </span>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Additional Information
                            </h2>

                            <p class="text-sm text-gray-500">
                                Add any additional details that can help sellers understand your requirement.
                            </p>
                        </div>

                    </div>
                </div>


                <div class="space-y-5 p-6">

                    {{-- Required Date --}}
                    <div class="max-w-md">
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Required Date
                        </label>

                        <input
                            type="date"
                            name="required_date"
                            value="{{ old('required_date') }}"
                            min="{{ now()->format('Y-m-d') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                        >

                        @error('required_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Delivery Location --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Delivery / Service Location
                        </label>

                        <input
                            type="text"
                            name="delivery_location"
                            value="{{ old('delivery_location') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Enter location if applicable"
                        >

                        @error('delivery_location')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Message --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">
                                Additional Message
                            </label>

                            <span class="text-xs text-gray-400">
                                Maximum 5000 characters
                            </span>
                        </div>

                        <textarea
                            name="message"
                            id="message"
                            rows="6"
                            maxlength="5000"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                            placeholder="Describe any additional requirements, preferences, specifications or questions..."
                        >{{ old('message') }}</textarea>

                        @error('message')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-1 text-right text-xs text-gray-400">
                            <span id="message-count">0</span>/5000
                        </div>
                    </div>

                </div>
            </div>


            {{-- =====================================================
                WHAT HAPPENS NEXT
            ====================================================== --}}
            <div class="rounded-2xl border border-[#BFDCD8] bg-[#E8F5F4] p-6">

                <div class="flex items-start gap-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-[#056663]">
                        <span class="material-symbols-outlined">
                            info
                        </span>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900">
                            What happens after you submit?
                        </h3>

                        <div class="mt-3 space-y-2 text-sm text-gray-600">

                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined mt-0.5 text-base text-[#056663]">
                                    check_circle
                                </span>

                                <span>
                                    Your requirement is submitted to the marketplace admin team.
                                </span>
                            </div>

                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined mt-0.5 text-base text-[#056663]">
                                    check_circle
                                </span>

                                <span>
                                    Admin reviews the requirement and identifies suitable sellers.
                                </span>
                            </div>

                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined mt-0.5 text-base text-[#056663]">
                                    check_circle
                                </span>

                                <span>
                                    An RFQ can be sent to selected sellers.
                                </span>
                            </div>

                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined mt-0.5 text-base text-[#056663]">
                                    check_circle
                                </span>

                                <span>
                                    Sellers can submit quotations for your requirement.
                                </span>
                            </div>

                            <div class="flex items-start gap-2">
                                <span class="material-symbols-outlined mt-0.5 text-base text-[#056663]">
                                    check_circle
                                </span>

                                <span>
                                    You can review quotations and continue with the suitable seller.
                                </span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


            {{-- =====================================================
                SUBMIT BUTTON
            ====================================================== --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">

                <a
                    href="{{ url()->previous() }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#056663] px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#04514F] focus:outline-none focus:ring-2 focus:ring-[#056663] focus:ring-offset-2"
                >
                    <span class="material-symbols-outlined text-[20px]">
                        send
                    </span>

                    Submit Requirement
                </button>

            </div>

        </form>

    </div>
</div>


{{-- =============================================================
    JAVASCRIPT
============================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const requirementType = document.getElementById('requirement_type');

    const sections = {
        product: document.getElementById('product-fields'),
        property: document.getElementById('property-fields'),
        land: document.getElementById('land-fields'),
        service: document.getElementById('service-fields'),
        project: document.getElementById('project-fields')
    };


    /*
    |--------------------------------------------------------------------------
    | Fields that must be required depending on requirement type
    |--------------------------------------------------------------------------
    */

    const conditionalRequiredFields = {

        product: [
            'product_name',
            'quantity',
            'unit'
        ],

        property: [
            'property_type',
            'property_location'
        ],

        land: [
            'land_type',
            'land_location',
            'land_area',
            'land_area_unit'
        ],

        service: [
            'service_name',
            'service_location',
            'service_scope'
        ],

        project: [
            'project_type',
            'project_location',
            'project_scope'
        ]
    };


    /*
    |--------------------------------------------------------------------------
    | Remove required attributes from every category field
    |--------------------------------------------------------------------------
    */

    function clearRequiredFields() {

        Object.values(sections).forEach(section => {

            if (!section) {
                return;
            }

            section.querySelectorAll('[name]').forEach(field => {
                field.removeAttribute('required');
            });
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Show selected section
    |--------------------------------------------------------------------------
    */

    function updateRequirementFields() {

        const selectedType = requirementType.value;

        Object.values(sections).forEach(section => {

            if (!section) {
                return;
            }

            section.classList.add('hidden');
        });


        clearRequiredFields();


        if (!selectedType || !sections[selectedType]) {
            return;
        }


        sections[selectedType].classList.remove('hidden');


        /*
        | Add required attributes only to fields belonging
        | to the selected requirement type.
        */

        if (conditionalRequiredFields[selectedType]) {

            conditionalRequiredFields[selectedType].forEach(fieldName => {

                const field = sections[selectedType].querySelector(
                    `[name="${fieldName}"]`
                );

                if (field) {
                    field.setAttribute('required', 'required');
                }
            });
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Requirement type change
    |--------------------------------------------------------------------------
    */

    requirementType.addEventListener('change', updateRequirementFields);


    /*
    |--------------------------------------------------------------------------
    | Restore selected section after validation error
    |--------------------------------------------------------------------------
    */

    updateRequirementFields();


    /*
    |--------------------------------------------------------------------------
    | Phone number - allow numbers only
    |--------------------------------------------------------------------------
    */

    const phoneInput = document.querySelector('input[name="phone"]');

    if (phoneInput) {

        phoneInput.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 10);

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Message character counter
    |--------------------------------------------------------------------------
    */

    const message = document.getElementById('message');
    const messageCount = document.getElementById('message-count');

    function updateMessageCount() {

        if (!message || !messageCount) {
            return;
        }

        messageCount.textContent = message.value.length;
    }

    if (message) {

        message.addEventListener('input', updateMessageCount);

        updateMessageCount();
    }

});
</script>

@endsection