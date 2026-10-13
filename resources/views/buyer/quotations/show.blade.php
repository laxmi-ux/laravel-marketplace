<x-buyer-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Quotation Details
            </h2>

            <p class="text-sm text-[#64748B] mt-0.5">
                Review quotation details, seller information and quotation response.
            </p>
        </div>

    </x-slot>


    @php

    $category = strtolower($quotation->category ?? 'general');

    $categoryLabel = match($category) {
    'product' => 'Product',
    'land' => 'Land',
    'property' => 'Property',
    'service' => 'Service',
    'project' => 'Project',
    default => 'General',
    };

    $statusClasses = match($quotation->status) {

    'sent' =>
    'bg-[#E8F1FF] text-[#24558A] border-[#C9DCF5]',

    'accepted' =>
    'bg-[#E8F7EF] text-[#177245] border-[#BCE6CE]',

    'rejected' =>
    'bg-[#FFF0F0] text-[#B42318] border-[#F2C5C2]',

    'countered' =>
    'bg-[#FFF7E6] text-[#9A6700] border-[#F0D69A]',

    'expired' =>
    'bg-[#F3F4F6] text-[#6B7280] border-[#D1D5DB]',

    default =>
    'bg-gray-100 text-gray-700 border-gray-200',
    };

    @endphp


    <div class="min-h-screen bg-[#F5F7F7]">

        <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">


            {{-- BACK --}}
            <a
                href="{{ route('buyer.quotations.index') }}"
                class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-[#00665F] transition hover:text-[#004F4A]">

                <span class="text-lg leading-none">←</span>

                My Quotations

            </a>


            {{-- =================================================
                 PAGE HEADER
            ================================================== --}}
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-[#111827]">
                            {{ $categoryLabel }} Quotation
                        </h1>

                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusClasses }}">
                            {{ ucfirst(str_replace('_', ' ', $quotation->status)) }}
                        </span>

                    </div>

                    <p class="mt-1.5 text-sm text-[#6B7280]">
                        Quotation #{{ $quotation->id }}

                        <span class="mx-1">•</span>

                        Received from seller
                    </p>

                </div>


                @if($quotation->valid_until)

                <div class="rounded-xl border border-[#DDE7E5] bg-white px-5 py-3 shadow-sm">

                    <p class="text-xs font-medium text-[#6B7280]">
                        Valid Until
                    </p>

                    <p class="mt-0.5 text-sm font-bold text-[#111827]">
                        {{ $quotation->valid_until->format('d M Y') }}
                    </p>

                </div>

                @endif

            </div>


            {{-- ALERTS --}}
            @if(session('success'))

            <div class="mb-5 flex items-start gap-3 rounded-xl border border-[#BCE6CE] bg-[#EAF8F0] px-4 py-3 text-sm font-medium text-[#176B43]">

                <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7" />

                </svg>

                <span>
                    {{ session('success') }}
                </span>

            </div>

            @endif


            @if(session('error'))

            <div class="mb-5 flex items-start gap-3 rounded-xl border border-[#F2C5C2] bg-[#FFF1F0] px-4 py-3 text-sm font-medium text-[#B42318]">

                <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />

                </svg>

                <span>
                    {{ session('error') }}
                </span>

            </div>

            @endif


            @if($errors->any())

            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700">

                <p class="mb-2 font-semibold">
                    Please fix the following:
                </p>

                <ul class="list-disc list-inside space-y-1">

                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

            @endif


            {{-- =================================================
                 TOP INFORMATION
            ================================================== --}}
            <div class="grid gap-5 lg:grid-cols-3">


                {{-- LISTING / REQUIREMENT --}}
                <div class="overflow-hidden rounded-2xl border border-[#DDE7E5] bg-white shadow-sm lg:col-span-2">

                    <div class="flex items-center justify-between gap-4 border-b border-[#E8EEEE] px-5 py-4">

                        <div class="min-w-0">

                            <p class="text-xs font-semibold uppercase tracking-wider text-[#00665F]">
                                Requirement
                            </p>

                            <h2 class="mt-1 text-lg font-bold text-[#111827] break-words">

                                {{ $quotation->requirement?->listing?->title
                                    ?? 'General Requirement' }}

                            </h2>

                        </div>

                        <div class="ml-4 hidden h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#EAF5F3] sm:flex">

                            <svg class="h-5 w-5 text-[#00665F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 10.5L12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6" />

                            </svg>

                        </div>

                    </div>


                    <div class="grid gap-x-8 gap-y-5 px-5 py-5 sm:grid-cols-2">


                        {{-- CATEGORY --}}
                        <div>

                            <p class="text-xs font-medium text-[#6B7280]">
                                Category
                            </p>

                            <p class="mt-1 text-sm font-semibold capitalize text-[#1F2937]">
                                {{ $categoryLabel }}
                            </p>

                        </div>


                        {{-- LISTING --}}
                        <div>

                            <p class="text-xs font-medium text-[#6B7280]">
                                Listing
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#1F2937] break-words">
                                {{ $quotation->requirement?->listing?->title ?? 'General Requirement' }}
                            </p>

                        </div>


                        {{-- SELLER --}}
                        <div>

                            <p class="text-xs font-medium text-[#6B7280]">
                                Seller
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#1F2937]">

                                {{ $quotation->seller?->bussiness_name
                                    ?? $quotation->seller?->business_name
                                    ?? $quotation->seller?->name
                                    ?? 'Seller' }}

                            </p>

                        </div>


                        {{-- SELLER LOCATION --}}
                        <div>

                            <p class="text-xs font-medium text-[#6B7280]">
                                Seller Location
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#1F2937]">
                                {{ $quotation->seller?->location ?? '—' }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- TOTAL --}}
                <div class="relative overflow-hidden rounded-2xl bg-[#00665F] p-5 shadow-sm">

                    <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-white/10"></div>

                    <div class="absolute -bottom-12 -left-10 h-32 w-32 rounded-full bg-white/5"></div>

                    <div class="relative">

                        <p class="text-sm font-medium text-white/75">
                            Total Quotation Amount
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-white">
                            ₹{{ number_format((float) $quotation->total_amount, 2) }}
                        </p>

                        <div class="mt-5 border-t border-white/15 pt-4">

                            <div class="flex items-center justify-between text-sm">

                                <span class="text-white/70">
                                    Category
                                </span>

                                <span class="font-semibold text-white">
                                    {{ $categoryLabel }}
                                </span>

                            </div>

                            @if($quotation->delivery_time)

                            <div class="mt-3 flex items-center justify-between gap-4 text-sm">

                                <span class="text-white/70">
                                    Completion
                                </span>

                                <span class="font-semibold text-white text-right">
                                    {{ $quotation->delivery_time }}
                                </span>

                            </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 CATEGORY SPECIFIC DETAILS
            ================================================== --}}
            <div class="mt-5 overflow-hidden rounded-2xl border border-[#DDE7E5] bg-white shadow-sm">

                <div class="border-b border-[#E8EEEE] px-5 py-4">

                    <h2 class="text-base font-bold text-[#111827]">
                        {{ $categoryLabel }} Quotation Details
                    </h2>

                    <p class="mt-0.5 text-xs text-[#6B7280]">
                        Detailed pricing and quotation information provided by the seller.
                    </p>

                </div>


                <div class="px-5 py-5">


                    {{-- =================================================
                         PRODUCT
                    ================================================== --}}
                    @if($category === 'product')

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">Product Name</p>
                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->product_name ?? '—' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">Quantity</p>
                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->quantity ?? '—' }}
                                {{ $quotation->unit ?? '' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">Unit Price</p>
                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->unit_price ?? 0), 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">Product Subtotal</p>
                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->product_subtotal ?? 0), 2) }}
                            </p>
                        </div>

                    </div>


                    {{-- =================================================
                         LAND
                    ================================================== --}}
                    @elseif($category === 'land')

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Land Location
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->land_location ?? '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Land Area
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->land_area ?? '—' }}
                                {{ $quotation->land_area_unit ?? '' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Price Per Unit
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->land_price_per_unit ?? 0), 2) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Land Price
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->land_price ?? 0), 2) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Development Charges
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->development_charges ?? 0), 2) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Registration Charges
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->registration_charges ?? 0), 2) }}
                            </p>
                        </div>

                    </div>


                    {{-- =================================================
                         PROPERTY
                    ================================================== --}}
                    @elseif($category === 'property')

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Property Type
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->property_type ?? '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Property Location
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->property_location ?? '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Property Area
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->property_area ?? '—' }}
                                {{ $quotation->property_area_unit ?? '' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Property Price
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->property_price ?? 0), 2) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Maintenance Charges
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->maintenance_charges ?? 0), 2) }}
                            </p>
                        </div>

                    </div>


                    {{-- =================================================
                         SERVICE
                    ================================================== --}}
                    @elseif($category === 'service')

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Service Name
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->service_name ?? '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Service Duration
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->service_duration ?? '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Service Charges
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->service_charges ?? 0), 2) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Labour Charges
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->labour_charges ?? 0), 2) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Material Charges
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->material_charges ?? 0), 2) }}
                            </p>
                        </div>

                    </div>


                    @if($quotation->service_description)

                    <div class="mt-5 rounded-xl bg-[#F7FAFA] p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-[#6B7280]">
                            Service Description
                        </p>

                        <p class="mt-2 text-sm leading-6 text-[#374151] whitespace-pre-line">
                            {{ $quotation->service_description }}
                        </p>

                    </div>

                    @endif


                    {{-- =================================================
                         PROJECT
                    ================================================== --}}
                    @elseif($category === 'project')

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Project Name
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->project_name ?? '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Project Location
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->project_location ?? '—' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Project Value
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->project_value ?? 0), 2) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Professional Charges
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->professional_charges ?? 0), 2) }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs font-medium text-[#6B7280]">
                                Project Timeline
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->project_timeline ?? '—' }}
                            </p>
                        </div>

                    </div>


                    @if($quotation->project_scope)

                    <div class="mt-5 rounded-xl bg-[#F7FAFA] p-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-[#6B7280]">
                            Project Scope
                        </p>

                        <p class="mt-2 text-sm leading-6 text-[#374151] whitespace-pre-line">
                            {{ $quotation->project_scope }}
                        </p>

                    </div>

                    @endif

                    @else

                    <p class="text-sm text-gray-500">
                        No category-specific quotation details are available.
                    </p>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 CHARGES BREAKDOWN
            ================================================== --}}
            <div class="mt-5 grid gap-5 lg:grid-cols-2">


                {{-- PRICING --}}
                <div class="overflow-hidden rounded-2xl border border-[#DDE7E5] bg-white shadow-sm">

                    <div class="border-b border-[#E8EEEE] px-5 py-4">

                        <h2 class="text-base font-bold text-[#111827]">
                            Pricing Breakdown
                        </h2>

                        <p class="mt-0.5 text-xs text-[#6B7280]">
                            Complete quotation amount calculation.
                        </p>

                    </div>


                    <div class="divide-y divide-[#E8EEEE]">


                        @if($category === 'product')

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Product Subtotal
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->product_subtotal ?? 0), 2) }}
                            </span>
                        </div>

                        @elseif($category === 'land')

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Land Price
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->land_price ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Development Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->development_charges ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Registration Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->registration_charges ?? 0), 2) }}
                            </span>
                        </div>

                        @elseif($category === 'property')

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Property Price
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->property_price ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Maintenance Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->maintenance_charges ?? 0), 2) }}
                            </span>
                        </div>

                        @elseif($category === 'service')

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Service Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->service_charges ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Labour Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->labour_charges ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Material Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->material_charges ?? 0), 2) }}
                            </span>
                        </div>

                        @elseif($category === 'project')

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Project Value
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->project_value ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-[#6B7280]">
                                Professional Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->professional_charges ?? 0), 2) }}
                            </span>
                        </div>

                        @endif


                        {{-- DELIVERY --}}
                        <div class="flex items-center justify-between px-5 py-3.5">

                            <span class="text-sm text-[#6B7280]">
                                Delivery Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->delivery_charges ?? 0), 2) }}
                            </span>

                        </div>


                        {{-- OTHER --}}
                        <div class="flex items-center justify-between px-5 py-3.5">

                            <span class="text-sm text-[#6B7280]">
                                Other Charges
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->other_charges ?? 0), 2) }}
                            </span>

                        </div>


                        {{-- TAX --}}
                        <div class="flex items-center justify-between px-5 py-3.5">

                            <span class="text-sm text-[#6B7280]">
                                Tax
                            </span>

                            <span class="text-sm font-semibold text-[#111827]">
                                ₹{{ number_format((float) ($quotation->tax ?? 0), 2) }}
                            </span>

                        </div>


                        {{-- TOTAL --}}
                        <div class="flex items-center justify-between bg-[#F7FAFA] px-5 py-4">

                            <span class="text-sm font-bold text-[#111827]">
                                Total Amount
                            </span>

                            <span class="text-lg font-bold text-[#00665F]">
                                ₹{{ number_format((float) $quotation->total_amount, 2) }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- BUYER REQUIREMENT --}}
                <div class="overflow-hidden rounded-2xl border border-[#DDE7E5] bg-white shadow-sm">

                    <div class="border-b border-[#E8EEEE] px-5 py-4">

                        <h2 class="text-base font-bold text-[#111827]">
                            Your Requirement
                        </h2>

                        <p class="mt-0.5 text-xs text-[#6B7280]">
                            Information submitted to the seller.
                        </p>

                    </div>


                    <div class="px-5 py-5">

                        <div class="grid gap-5 sm:grid-cols-2">


                            {{-- QUANTITY --}}
                            <div>

                                <p class="text-xs font-medium text-[#6B7280]">
                                    Quantity
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#111827]">

                                    {{ $quotation->requirement?->quantity ?? '—' }}

                                    {{ $quotation->requirement?->unit ?? '' }}

                                </p>

                            </div>


                            {{-- REQUIRED DATE --}}
                            <div>

                                <p class="text-xs font-medium text-[#6B7280]">
                                    Required Date
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#111827]">

                                    @if($quotation->requirement?->required_date)

                                    {{ \Illuminate\Support\Carbon::parse(
                                            $quotation->requirement->required_date
                                        )->format('d M Y') }}

                                    @else
                                    —
                                    @endif

                                </p>

                            </div>


                            {{-- BUDGET --}}
                            <div>

                                <p class="text-xs font-medium text-[#6B7280]">
                                    Budget
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#111827]">

                                    @if($quotation->requirement?->budget)

                                    ₹{{ number_format(
                                            (float) $quotation->requirement->budget,
                                            2
                                        ) }}

                                    @else
                                    —
                                    @endif

                                </p>

                            </div>


                            {{-- DELIVERY LOCATION --}}
                            <div>

                                <p class="text-xs font-medium text-[#6B7280]">
                                    Delivery Location
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#111827] break-words">

                                    {{ $quotation->requirement?->delivery_location ?? '—' }}

                                </p>

                            </div>

                        </div>


                        @if($quotation->requirement?->message)

                        <div class="mt-5 rounded-xl bg-[#F7FAFA] p-4">

                            <p class="text-xs font-semibold uppercase tracking-wide text-[#6B7280]">
                                Message
                            </p>

                            <p class="mt-2 text-sm leading-6 text-[#374151] whitespace-pre-line">
                                {{ $quotation->requirement->message }}
                            </p>

                        </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SELLER INFORMATION
            ================================================== --}}
            <div class="mt-5 grid gap-5 lg:grid-cols-2">


                <div class="overflow-hidden rounded-2xl border border-[#DDE7E5] bg-white shadow-sm">

                    <div class="border-b border-[#E8EEEE] px-5 py-4">

                        <h2 class="text-base font-bold text-[#111827]">
                            Seller Information
                        </h2>

                    </div>


                    <div class="grid gap-x-8 gap-y-5 px-5 py-5 sm:grid-cols-2">


                        <div>

                            <p class="text-xs font-medium text-[#6B7280]">
                                Business Name
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">

                                {{ $quotation->seller?->bussiness_name
                                    ?? $quotation->seller?->business_name
                                    ?? $quotation->seller?->name
                                    ?? 'Seller' }}

                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium text-[#6B7280]">
                                Location
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->seller?->location ?? '—' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium text-[#6B7280]">
                                Delivery / Completion
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->delivery_time ?? '—' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs font-medium text-[#6B7280]">
                                Quotation Date
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#111827]">
                                {{ $quotation->created_at->format('d M Y') }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- WARRANTY --}}
                <div class="overflow-hidden rounded-2xl border border-[#DDE7E5] bg-white shadow-sm">

                    <div class="border-b border-[#E8EEEE] px-5 py-4">

                        <h2 class="text-base font-bold text-[#111827]">
                            Warranty & Notes
                        </h2>

                    </div>


                    <div class="px-5 py-5">

                        @if($quotation->warranty)

                        <div class="rounded-xl bg-[#F7FAFA] p-4">

                            <p class="text-xs font-semibold uppercase tracking-wide text-[#6B7280]">
                                Warranty
                            </p>

                            <p class="mt-2 text-sm leading-6 text-[#374151] whitespace-pre-line">
                                {{ $quotation->warranty }}
                            </p>

                        </div>

                        @endif


                        @if($quotation->notes)

                        <div class="{{ $quotation->warranty ? 'mt-4' : '' }} rounded-xl bg-[#F7FAFA] p-4">

                            <p class="text-xs font-semibold uppercase tracking-wide text-[#6B7280]">
                                Seller Notes
                            </p>

                            <p class="mt-2 text-sm leading-6 text-[#374151] whitespace-pre-line">
                                {{ $quotation->notes }}
                            </p>

                        </div>

                        @endif


                        @if(!$quotation->warranty && !$quotation->notes)

                        <p class="text-sm text-[#9CA3AF]">
                            No additional warranty or notes were provided by the seller.
                        </p>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}
            <div class="mt-5 rounded-2xl border border-[#DDE7E5] bg-white p-5 shadow-sm">

                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    <div>

                        <p class="text-sm font-semibold text-[#111827]">
                            Quotation Actions
                        </p>

                        <p class="mt-1 text-xs text-[#6B7280]">
                            Review the quotation and respond to the seller.
                        </p>

                    </div>


                    <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap">


                        @if($rfq)
                        <a
                            href="{{ route('buyer.rfqs.show', $rfq->id) }}"
                            class="inline-flex items-center justify-center gap-2
               rounded-xl border border-[#056663]
               bg-white px-5 py-3
               text-sm font-semibold text-[#056663]
               transition hover:bg-[#E8F5F4]">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 10h8M8 14h5m-9 6 3.5-3.5A8 8 0 1 1 20 12a8 8 0 0 1-8 8H4z" />
                            </svg>

                            Message Seller
                        </a>
                        @endif


                        {{-- ACTIONS --}}
                        @if($quotation->status === 'sent')

                        {{-- REJECT --}}
                        <form
                            action="{{ route(
                                    'buyer.quotations.reject',
                                    $quotation->id
                                ) }}"
                            method="POST"
                            class="w-full sm:w-auto"
                            onsubmit="return confirm('Are you sure you want to reject this quotation?');">

                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-xl border border-[#E1B5B2] px-5 py-2.5 text-sm font-semibold text-[#B42318] transition hover:bg-[#FFF3F2]">

                                Reject

                            </button>

                        </form>


                        {{-- ACCEPT --}}
                        <form
                            action="{{ route(
                                    'buyer.quotations.accept',
                                    $quotation->id
                                ) }}"
                            method="POST"
                            class="w-full sm:w-auto"
                            onsubmit="return confirm('Are you sure you want to accept this quotation?');">

                            @csrf

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-[#00665F] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#00534E]">

                                Accept Quotation

                            </button>

                        </form>

                        @else

                        <div class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold capitalize text-gray-600">

                            {{ str_replace('_', ' ', $quotation->status) }}

                        </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-buyer-layout>