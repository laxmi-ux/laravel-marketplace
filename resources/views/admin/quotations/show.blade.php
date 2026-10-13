@extends('admin.layouts.app')

@section('title', 'Quotation Details')
@section('page-title', 'Quotation Details')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('admin.quotations.index') }}"
                class="flex h-10 w-10 items-center justify-center rounded-lg
                       border border-[#DDE9E8] bg-white text-[#056663]
                       hover:bg-[#E8F5F4]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </a>

            <div>

                <h1 class="text-2xl font-bold text-gray-900">
                    Quotation #{{ $quotation->id }}
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Quotation details submitted by the seller
                </p>

            </div>

        </div>


        {{-- Status --}}
        @php
            $statusClass = match(strtolower((string) $quotation->status)) {
                'sent' => 'bg-blue-50 text-blue-700 border-blue-200',
                'draft' => 'bg-gray-100 text-gray-700 border-gray-200',
                'accepted' => 'bg-green-50 text-green-700 border-green-200',
                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                'expired' => 'bg-orange-50 text-orange-700 border-orange-200',
                default => 'bg-gray-100 text-gray-700 border-gray-200',
            };
        @endphp

        <span class="inline-flex w-fit rounded-full border px-4 py-2 text-sm font-semibold capitalize {{ $statusClass }}">
            {{ $quotation->status
                ? str_replace('_', ' ', $quotation->status)
                : '—' }}
        </span>

    </div>


    {{-- Buyer / Seller --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

        {{-- Buyer --}}
        <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="font-semibold text-gray-900">
                    Buyer Information
                </h2>
            </div>

            <div class="space-y-4 p-6">

                @if($quotation->buyer)

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Name
                        </p>

                        <p class="mt-1 font-semibold text-gray-900">
                            {{ $quotation->buyer->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Email
                        </p>

                        <p class="mt-1 text-sm text-gray-700">
                            {{ $quotation->buyer->email }}
                        </p>
                    </div>

                @else

                    <p class="text-sm text-gray-500">
                        Buyer information unavailable.
                    </p>

                @endif

            </div>

        </div>


        {{-- Seller --}}
        <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="font-semibold text-gray-900">
                    Seller Information
                </h2>
            </div>

            <div class="space-y-4 p-6">

                @if($quotation->seller)

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Business Name
                        </p>

                        <p class="mt-1 font-semibold text-gray-900">
                            {{ $quotation->seller->bussiness_name }}
                        </p>
                    </div>

                    @if($quotation->seller->location)

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Location
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $quotation->seller->location }}
                            </p>
                        </div>

                    @endif

                @else

                    <p class="text-sm text-gray-500">
                        Seller information unavailable.
                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- Requirement --}}
    <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">
                Requirement Information
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 lg:grid-cols-4">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Requirement ID
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    #{{ $quotation->requirement_id }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Category
                </p>

                <p class="mt-1 font-semibold capitalize text-gray-900">
                    {{ $quotation->category ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Requirement Type
                </p>

                <p class="mt-1 font-semibold capitalize text-gray-900">
                    {{ $quotation->requirement?->requirement_type ?? '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Required Date
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $quotation->requirement?->required_date
                        ? \Carbon\Carbon::parse($quotation->requirement->required_date)->format('d M Y')
                        : '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Category Specific Details --}}
    <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-4">

            <h2 class="text-lg font-semibold text-gray-900">
                Quotation Details
            </h2>

        </div>


        <div class="p-6">

            @php
                $category = strtolower((string) $quotation->category);
            @endphp


            {{-- PRODUCT --}}
            @if($category === 'product')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    <div>
                        <p class="field-label">Product Name</p>
                        <p class="field-value">{{ $quotation->product_name ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="field-label">Quantity</p>
                        <p class="field-value">
                            {{ $quotation->quantity ?? '—' }}
                            {{ $quotation->unit ?? '' }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Unit Price</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->unit_price ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Product Subtotal</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->product_subtotal ?? 0), 2) }}
                        </p>
                    </div>

                </div>


            {{-- LAND --}}
            @elseif($category === 'land')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <p class="field-label">Location</p>
                        <p class="field-value">{{ $quotation->land_location ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="field-label">Area</p>
                        <p class="field-value">
                            {{ $quotation->land_area ?? '—' }}
                            {{ $quotation->land_area_unit ?? '' }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Price Per Unit</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->land_price_per_unit ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Land Price</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->land_price ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Development Charges</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->development_charges ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Registration Charges</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->registration_charges ?? 0), 2) }}
                        </p>
                    </div>

                </div>


            {{-- PROPERTY --}}
            @elseif($category === 'property')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <p class="field-label">Property Type</p>
                        <p class="field-value">{{ $quotation->property_type ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="field-label">Location</p>
                        <p class="field-value">{{ $quotation->property_location ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="field-label">Area</p>
                        <p class="field-value">
                            {{ $quotation->property_area ?? '—' }}
                            {{ $quotation->property_area_unit ?? '' }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Property Price</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->property_price ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Maintenance Charges</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->maintenance_charges ?? 0), 2) }}
                        </p>
                    </div>

                </div>


            {{-- SERVICE --}}
            @elseif($category === 'service')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <p class="field-label">Service Name</p>
                        <p class="field-value">{{ $quotation->service_name ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="field-label">Duration</p>
                        <p class="field-value">{{ $quotation->service_duration ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="field-label">Service Charges</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->service_charges ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Labour Charges</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->labour_charges ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Material Charges</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->material_charges ?? 0), 2) }}
                        </p>
                    </div>

                </div>

                @if($quotation->service_description)

                    <div class="mt-6">

                        <p class="field-label">
                            Service Description
                        </p>

                        <div class="mt-2 rounded-lg bg-[#F7FAFA] p-4 text-sm leading-6 text-gray-700">
                            {{ $quotation->service_description }}
                        </div>

                    </div>

                @endif


            {{-- PROJECT --}}
            @elseif($category === 'project')

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    <div>
                        <p class="field-label">Project Name</p>
                        <p class="field-value">{{ $quotation->project_name ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="field-label">Location</p>
                        <p class="field-value">{{ $quotation->project_location ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="field-label">Project Value</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->project_value ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Professional Charges</p>
                        <p class="field-value">
                            ₹{{ number_format((float) ($quotation->professional_charges ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="field-label">Project Timeline</p>
                        <p class="field-value">
                            {{ $quotation->project_timeline ?? '—' }}
                        </p>
                    </div>

                </div>

                @if($quotation->project_scope)

                    <div class="mt-6">

                        <p class="field-label">
                            Project Scope
                        </p>

                        <div class="mt-2 rounded-lg bg-[#F7FAFA] p-4 text-sm leading-6 text-gray-700">
                            {{ $quotation->project_scope }}
                        </div>

                    </div>

                @endif


            @else

                <div class="rounded-lg bg-gray-50 p-5 text-sm text-gray-500">
                    No category-specific quotation details available.
                </div>

            @endif

        </div>

    </div>


    {{-- Common Charges --}}
    <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">
                Charges & Delivery
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 lg:grid-cols-4">

            <div>
                <p class="field-label">Delivery Charges</p>
                <p class="field-value">
                    ₹{{ number_format((float) ($quotation->delivery_charges ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="field-label">Other Charges</p>
                <p class="field-value">
                    ₹{{ number_format((float) ($quotation->other_charges ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="field-label">Tax</p>
                <p class="field-value">
                    ₹{{ number_format((float) ($quotation->tax ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="field-label">Total Amount</p>
                <p class="text-xl font-bold text-[#056663]">
                    ₹{{ number_format((float) ($quotation->total_amount ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="field-label">Delivery Time</p>
                <p class="field-value">
                    {{ $quotation->delivery_time ?? '—' }}
                </p>
            </div>

            <div>
                <p class="field-label">Warranty</p>
                <p class="field-value">
                    {{ $quotation->warranty ?? '—' }}
                </p>
            </div>

            <div>
                <p class="field-label">Valid Until</p>
                <p class="field-value">
                    {{ $quotation->valid_until
                        ? \Carbon\Carbon::parse($quotation->valid_until)->format('d M Y')
                        : '—' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Notes --}}
    @if($quotation->notes)

        <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

            <div class="border-b border-gray-100 px-6 py-4">

                <h2 class="text-lg font-semibold text-gray-900">
                    Seller Notes
                </h2>

            </div>

            <div class="p-6">

                <div class="rounded-lg bg-[#F7FAFA] p-5 text-sm leading-7 text-gray-700">
                    {{ $quotation->notes }}
                </div>

            </div>

        </div>

    @endif

</div>


{{-- Small reusable styles --}}
<style>
    .field-label {
        font-size: 0.75rem;
        line-height: 1rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        color: #6b7280;
    }

    .field-value {
        margin-top: 0.25rem;
        font-weight: 600;
        color: #111827;
    }
</style>

@endsection