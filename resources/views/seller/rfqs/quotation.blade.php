<x-seller-layout>

    <x-slot name="header">

        <div class="flex items-center gap-3">

            {{-- Back --}}
            <a
                href="{{ route('seller.rfqs.show', $rfq->id) }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg
                       text-[#056663] hover:bg-[#E8F5F4]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </a>

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    {{ $quotation ? 'Edit Quotation' : 'Send Quotation' }}
                </h2>

                <p class="text-sm text-gray-500">
                    RFQ #{{ $rfq->id }}
                </p>
            </div>

        </div>

    </x-slot>


    <div class="mx-auto max-w-6xl space-y-6 py-6">

        {{-- Success --}}
        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error --}}
        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif


        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-5">

                <div class="mb-2 font-semibold text-red-700">
                    Please correct the following errors:
                </div>

                <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif


        {{-- =========================================================
             RFQ INFORMATION
        ========================================================== --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="mb-5 flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Requirement / RFQ Details
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Review the buyer requirement before preparing your quotation.
                    </p>
                </div>

                <span
                    class="rounded-full bg-[#E8F5F4] px-4 py-2 text-xs font-semibold uppercase text-[#056663]"
                >
                    {{ ucfirst($category) }}
                </span>

            </div>


            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                {{-- Requirement --}}
                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">
                        Requirement
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        #{{ $requirement->id }}
                    </p>
                </div>


                {{-- Buyer --}}
                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">
                        Buyer
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $requirement->user?->name ?? 'Buyer' }}
                    </p>
                </div>


                {{-- Category --}}
                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">
                        Category
                    </p>

                    <p class="mt-1 font-semibold capitalize text-gray-800">
                        {{ $category }}
                    </p>
                </div>


                {{-- Listing --}}
                @if($requirement->listing)
                    <div class="md:col-span-2 lg:col-span-3">

                        <p class="text-xs font-medium uppercase text-gray-400">
                            Related Listing
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->listing->title }}
                        </p>

                    </div>
                @endif


                {{-- Requirement Message --}}
                @if($requirement->message)
                    <div class="md:col-span-2 lg:col-span-3">

                        <p class="text-xs font-medium uppercase text-gray-400">
                            Buyer Requirement
                        </p>

                        <div class="mt-2 rounded-xl bg-gray-50 p-4 text-sm leading-6 text-gray-700">
                            {{ $requirement->message }}
                        </div>

                    </div>
                @endif

            </div>

        </div>


        {{-- =========================================================
             QUOTATION FORM
        ========================================================== --}}

        <form
            method="POST"
            action="{{ route('seller.rfqs.quotation.store', $rfq->id) }}"
            class="space-y-6"
        >

            @csrf


            {{-- =====================================================
                 PRODUCT
            ====================================================== --}}

            @if($category === 'product')

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">
                            Product Quotation
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Enter the product details and pricing for this RFQ.
                        </p>
                    </div>


                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Product Name --}}
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Product Name
                            </label>

                            <input
                                type="text"
                                name="product_name"
                                value="{{ old('product_name', $quotation?->product_name ?? $requirement->product_name ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Quantity --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Quantity
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                id="quantity"
                                name="quantity"
                                value="{{ old('quantity', $quotation?->quantity ?? $requirement->quantity ?? '') }}"
                                required
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Unit --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Unit
                            </label>

                            <input
                                type="text"
                                name="unit"
                                value="{{ old('unit', $quotation?->unit ?? $requirement->unit ?? '') }}"
                                placeholder="Bag, Kg, Piece, Ton..."
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Unit Price --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Unit Price
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="unit_price"
                                name="unit_price"
                                value="{{ old('unit_price', $quotation?->unit_price ?? '') }}"
                                required
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Product Subtotal --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Product Subtotal
                            </label>

                            <input
                                type="text"
                                id="product_subtotal_display"
                                value="{{ number_format((float)($quotation?->product_subtotal ?? 0), 2) }}"
                                readonly
                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-700"
                            >
                        </div>

                    </div>

                </div>


            {{-- =====================================================
                 LAND
            ====================================================== --}}

            @elseif($category === 'land')

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">
                            Land Quotation
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Provide the land details, pricing and applicable charges.
                        </p>
                    </div>


                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Location --}}
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Land Location
                            </label>

                            <input
                                type="text"
                                name="land_location"
                                value="{{ old('land_location', $quotation?->land_location ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Area --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Land Area
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                name="land_area"
                                value="{{ old('land_area', $quotation?->land_area ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Area Unit --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Area Unit
                            </label>

                            <select
                                name="land_area_unit"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                                <option value="">Select Unit</option>

                                @foreach(['sq.ft', 'sq.m', 'acre', 'hectare', 'guntha'] as $unit)

                                    <option
                                        value="{{ $unit }}"
                                        @selected(old('land_area_unit', $quotation?->land_area_unit ?? '') === $unit)
                                    >
                                        {{ $unit }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        {{-- Price Per Unit --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Price Per Unit
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="land_price_per_unit"
                                value="{{ old('land_price_per_unit', $quotation?->land_price_per_unit ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Total Land Price --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Land Price
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="land_price"
                                name="land_price"
                                value="{{ old('land_price', $quotation?->land_price ?? '') }}"
                                required
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Development Charges --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Development Charges
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="development_charges"
                                name="development_charges"
                                value="{{ old('development_charges', $quotation?->development_charges ?? 0) }}"
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Registration Charges --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Registration Charges
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="registration_charges"
                                name="registration_charges"
                                value="{{ old('registration_charges', $quotation?->registration_charges ?? 0) }}"
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>

                    </div>

                </div>


            {{-- =====================================================
                 PROPERTY
            ====================================================== --}}

            @elseif($category === 'property')

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">
                            Property Quotation
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Provide property details and pricing.
                        </p>
                    </div>


                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Property Type --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Property Type
                            </label>

                            <input
                                type="text"
                                name="property_type"
                                value="{{ old('property_type', $quotation?->property_type ?? '') }}"
                                placeholder="Office, Shop, Warehouse, Apartment..."
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Location --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Property Location
                            </label>

                            <input
                                type="text"
                                name="property_location"
                                value="{{ old('property_location', $quotation?->property_location ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Area --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Property Area
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                name="property_area"
                                value="{{ old('property_area', $quotation?->property_area ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Area Unit --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Area Unit
                            </label>

                            <select
                                name="property_area_unit"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                                <option value="">Select Unit</option>

                                @foreach(['sq.ft', 'sq.m', 'acre', 'hectare'] as $unit)

                                    <option
                                        value="{{ $unit }}"
                                        @selected(old('property_area_unit', $quotation?->property_area_unit ?? '') === $unit)
                                    >
                                        {{ $unit }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        {{-- Property Price --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Property Price
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="property_price"
                                value="{{ old('property_price', $quotation?->property_price ?? '') }}"
                                required
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Maintenance --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Maintenance Charges
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="maintenance_charges"
                                value="{{ old('maintenance_charges', $quotation?->maintenance_charges ?? 0) }}"
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>

                    </div>

                </div>


            {{-- =====================================================
                 SERVICE
            ====================================================== --}}

            @elseif($category === 'service')

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">
                            Service Quotation
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Provide service scope, duration and charges.
                        </p>
                    </div>


                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Service Name --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Service Name
                            </label>

                            <input
                                type="text"
                                name="service_name"
                                value="{{ old('service_name', $quotation?->service_name ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Duration --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Service Duration
                            </label>

                            <input
                                type="text"
                                name="service_duration"
                                value="{{ old('service_duration', $quotation?->service_duration ?? '') }}"
                                placeholder="10 Days / 3 Months"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Description --}}
                        <div class="md:col-span-2">

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Service Description
                            </label>

                            <textarea
                                name="service_description"
                                rows="4"
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >{{ old('service_description', $quotation?->service_description ?? '') }}</textarea>

                        </div>


                        {{-- Service Charges --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Service Charges
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="service_charges"
                                value="{{ old('service_charges', $quotation?->service_charges ?? 0) }}"
                                required
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Labour --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Labour Charges
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="labour_charges"
                                value="{{ old('labour_charges', $quotation?->labour_charges ?? 0) }}"
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Material --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Material Charges
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="material_charges"
                                value="{{ old('material_charges', $quotation?->material_charges ?? 0) }}"
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>

                    </div>

                </div>


            {{-- =====================================================
                 PROJECT
            ====================================================== --}}

            @elseif($category === 'project')

                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">
                            Project Quotation
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Provide project scope, value and timeline.
                        </p>
                    </div>


                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Project Name --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Project Name
                            </label>

                            <input
                                type="text"
                                name="project_name"
                                value="{{ old('project_name', $quotation?->project_name ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Location --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Project Location
                            </label>

                            <input
                                type="text"
                                name="project_location"
                                value="{{ old('project_location', $quotation?->project_location ?? '') }}"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Scope --}}
                        <div class="md:col-span-2">

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Project Scope
                            </label>

                            <textarea
                                name="project_scope"
                                rows="5"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >{{ old('project_scope', $quotation?->project_scope ?? '') }}</textarea>

                        </div>


                        {{-- Project Value --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Project Value
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="project_value"
                                value="{{ old('project_value', $quotation?->project_value ?? 0) }}"
                                required
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Professional Charges --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Professional Charges
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="professional_charges"
                                value="{{ old('professional_charges', $quotation?->professional_charges ?? 0) }}"
                                class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >
                        </div>


                        {{-- Timeline --}}
                        <div class="md:col-span-2">

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Project Timeline
                            </label>

                            <input
                                type="text"
                                name="project_timeline"
                                value="{{ old('project_timeline', $quotation?->project_timeline ?? '') }}"
                                placeholder="3 Months / 6 Months"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                            >

                        </div>

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 COMMON CHARGES
            ====================================================== --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="mb-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Additional Charges & Tax
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        These fields apply to all quotation categories.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Other Charges --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Other Charges
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="other_charges"
                            name="other_charges"
                            value="{{ old('other_charges', $quotation?->other_charges ?? 0) }}"
                            class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                        >

                    </div>


                    {{-- Tax --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Tax
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            id="tax"
                            name="tax"
                            value="{{ old('tax', $quotation?->tax ?? 0) }}"
                            class="quotation-input w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                        >

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 TOTAL / VALIDITY / NOTES
            ====================================================== --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Total --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Total Quotation Amount
                        </label>

                        <input
                            type="text"
                            id="total_amount_display"
                            value="{{ number_format((float)($quotation?->total_amount ?? 0), 2) }}"
                            readonly
                            class="w-full rounded-xl border-gray-200 bg-[#E8F5F4] text-lg font-bold text-[#056663]"
                        >

                    </div>


                    {{-- Valid Until --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Quotation Valid Until
                        </label>

                        <input
                            type="date"
                            name="valid_until"
                            min="{{ now()->format('Y-m-d') }}"
                            value="{{ old(
                                'valid_until',
                                $quotation?->valid_until?->format('Y-m-d')
                            ) }}"
                            required
                            class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                        >

                    </div>


                    {{-- Notes --}}
                    <div class="md:col-span-2">

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            rows="4"
                            placeholder="Additional quotation terms, conditions or notes..."
                            class="w-full rounded-xl border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
                        >{{ old('notes', $quotation?->notes ?? '') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 ACTIONS
            ====================================================== --}}

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('seller.rfqs.show', $rfq->id) }}"
                    class="inline-flex items-center justify-center rounded-xl
                           border border-gray-300 px-6 py-3 text-sm font-semibold
                           text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl
                           bg-[#056663] px-6 py-3 text-sm font-semibold
                           text-white transition hover:bg-[#045450]"
                >

                    {{ $quotation ? 'Update & Send Quotation' : 'Send Quotation' }}

                </button>

            </div>

        </form>

    </div>


    {{-- =============================================================
         TOTAL CALCULATION
    ============================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const category = @json($category);

            const getNumber = (id) => {

                const element = document.getElementById(id);

                if (!element) {
                    return 0;
                }

                const value = parseFloat(element.value);

                return isNaN(value) ? 0 : value;
            };


            const totalDisplay =
                document.getElementById('total_amount_display');


            const subtotalDisplay =
                document.getElementById('product_subtotal_display');


            function calculateTotal() {

                let total = 0;

                /*
                |--------------------------------------------------------------------------
                | PRODUCT
                |--------------------------------------------------------------------------
                */
                if (category === 'product') {

                    const quantity =
                        getNumber('quantity');

                    const unitPrice =
                        getNumber('unit_price');

                    const subtotal =
                        quantity * unitPrice;

                    const other =
                        getNumber('other_charges');

                    const tax =
                        getNumber('tax');

                    total =
                        subtotal
                        + other
                        + tax;


                    if (subtotalDisplay) {

                        subtotalDisplay.value =
                            subtotal.toFixed(2);

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | LAND
                |--------------------------------------------------------------------------
                */
                else if (category === 'land') {

                    const landPrice =
                        getNumber('land_price');

                    const development =
                        getNumber('development_charges');

                    const registration =
                        getNumber('registration_charges');

                    const other =
                        getNumber('other_charges');

                    const tax =
                        getNumber('tax');

                    total =
                        landPrice
                        + development
                        + registration
                        + other
                        + tax;

                }


                /*
                |--------------------------------------------------------------------------
                | PROPERTY
                |--------------------------------------------------------------------------
                */
                else if (category === 'property') {

                    const propertyPrice =
                        getNumber('property_price');

                    const maintenance =
                        getNumber('maintenance_charges');

                    const other =
                        getNumber('other_charges');

                    const tax =
                        getNumber('tax');

                    total =
                        propertyPrice
                        + maintenance
                        + other
                        + tax;

                }


                /*
                |--------------------------------------------------------------------------
                | SERVICE
                |--------------------------------------------------------------------------
                */
                else if (category === 'service') {

                    const service =
                        getNumber('service_charges');

                    const labour =
                        getNumber('labour_charges');

                    const material =
                        getNumber('material_charges');

                    const other =
                        getNumber('other_charges');

                    const tax =
                        getNumber('tax');

                    total =
                        service
                        + labour
                        + material
                        + other
                        + tax;

                }


                /*
                |--------------------------------------------------------------------------
                | PROJECT
                |--------------------------------------------------------------------------
                */
                else if (category === 'project') {

                    const projectValue =
                        getNumber('project_value');

                    const professional =
                        getNumber('professional_charges');

                    const other =
                        getNumber('other_charges');

                    const tax =
                        getNumber('tax');

                    total =
                        projectValue
                        + professional
                        + other
                        + tax;

                }


                if (totalDisplay) {

                    totalDisplay.value =
                        total.toFixed(2);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Listen to all quotation inputs
            |--------------------------------------------------------------------------
            */
            document
                .querySelectorAll('.quotation-input')
                .forEach(function (input) {

                    input.addEventListener(
                        'input',
                        calculateTotal
                    );

                });


            /*
            |--------------------------------------------------------------------------
            | Also listen to category-specific inputs
            |--------------------------------------------------------------------------
            */
            document
                .querySelectorAll(
                    'input[type="number"]'
                )
                .forEach(function (input) {

                    input.addEventListener(
                        'input',
                        calculateTotal
                    );

                });


            /*
            |--------------------------------------------------------------------------
            | Initial calculation
            |--------------------------------------------------------------------------
            */
            calculateTotal();

        });

    </script>

</x-seller-layout>