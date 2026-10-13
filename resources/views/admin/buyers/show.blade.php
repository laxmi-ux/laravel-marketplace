@extends('admin.layouts.app')

@section('title', 'Buyer Details')

@section('page-title', 'Buyer Details')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- ========================================================= --}}
    {{-- SUCCESS / ERROR MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('admin.buyers.index') }}"
                class="flex h-10 w-10 items-center justify-center rounded-xl
                       text-[#056663] transition hover:bg-[#E8F5F4]"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
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
                    Buyer Details
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    View buyer account and marketplace activity.
                </p>

            </div>

        </div>


        {{-- Top Status Action --}}
        <div>

            @if(($buyer->status ?? 'active') === 'active')

                <form
                    method="POST"
                    action="{{ route('admin.buyers.deactivate', $buyer->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to deactivate this buyer?')"
                        class="rounded-xl border border-red-200 bg-red-50 px-5 py-2.5
                               text-sm font-semibold text-red-700 transition hover:bg-red-100"
                    >
                        Deactivate Buyer
                    </button>

                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('admin.buyers.activate', $buyer->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="rounded-xl bg-[#056663] px-5 py-2.5
                               text-sm font-semibold text-white transition hover:bg-[#045451]"
                    >
                        Activate Buyer
                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BUYER PROFILE --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        {{-- Profile Header --}}
        <div class="border-b border-gray-100 bg-[#F7FAFA] px-6 py-6">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl
                            bg-[#E8F5F4] text-2xl font-bold text-[#056663]">

                    {{ strtoupper(substr($buyer->name ?? 'B', 0, 1)) }}

                </div>


                <div>

                    <div class="flex flex-wrap items-center gap-3">

                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $buyer->name ?? 'N/A' }}
                        </h2>

                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            Buyer
                        </span>


                        @if(($buyer->status ?? 'active') === 'active')

                            <span class="inline-flex items-center gap-1.5 rounded-full
                                         bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">

                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                Active

                            </span>

                        @else

                            <span class="inline-flex items-center gap-1.5 rounded-full
                                         bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">

                                <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                Inactive

                            </span>

                        @endif

                    </div>

                    <p class="mt-1 text-sm text-gray-500">
                        Buyer ID #{{ $buyer->id }}
                    </p>

                </div>

            </div>

        </div>


        {{-- Account Information --}}
        <div class="p-6">

            <h3 class="text-lg font-semibold text-gray-900">
                Account Information
            </h3>

            <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Full Name
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $buyer->name ?? 'N/A' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Email Address
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $buyer->email ?? 'N/A' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Phone Number
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $buyer->phone ?? 'N/A' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Account Role
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        Buyer
                    </p>
                </div>


                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Account Status
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ ucfirst($buyer->status ?? 'Active') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Registered On
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $buyer->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Last Updated
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $buyer->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BUYER ACTIVITY COUNTS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

        {{-- Requirements --}}
        <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Requirements
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $requirementCount }}
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        description
                    </span>

                </div>

            </div>

            <p class="mt-3 text-xs text-gray-500">
                Buyer requirement submissions
            </p>

        </div>

        {{-- Enquiry --}}
<div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between">

        <div>

            <p class="text-sm font-medium text-gray-500">
                Enquiry
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
               {{ $enquiryCount }}
            </p>

        </div>

        <div class="flex h-11 w-11 items-center justify-center
                    rounded-xl bg-[#E8F5F4] text-[#056663]">

            <span class="material-symbols-outlined">
                description
            </span>

        </div>

    </div>

    <p class="mt-3 text-xs text-gray-500">
        My Enquiries
    </p>

</div>


        {{-- RFQs --}}
        <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        RFQs
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $rfqCount }}
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-blue-50 text-blue-600">

                    <span class="material-symbols-outlined">
                        request_quote
                    </span>

                </div>

            </div>

            <p class="mt-3 text-xs text-gray-500">
                RFQs generated for this buyer
            </p>

        </div>


        {{-- Quotations --}}
        <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Quotations
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $quotationCount }}
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-purple-50 text-purple-600">

                    <span class="material-symbols-outlined">
                        receipt_long
                    </span>

                </div>

            </div>

            <p class="mt-3 text-xs text-gray-500">
                Quotations received from sellers
            </p>

        </div>

    </div>

{{-- ================= BUYER ENQUIRIES ================= --}}
<div class="mt-8">

    <div class="mb-4 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                Buyer Enquiries
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Enquiries sent by this buyer to sellers.
            </p>
        </div>

        <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-sm font-semibold text-[#056663]">
            {{ $enquiryCount }} Enquiries
        </span>
    </div>

    @if($enquiries->count())

        <div class="space-y-4">

            @foreach($enquiries as $enquiry)

                @php
                    $seller = $enquiry->seller;

                    // For Auto-RFQ enquiry, get seller from RFQ
                    if (!$seller && $enquiry->rfqs->count()) {
                        $seller = $enquiry->rfqs->first()->seller;
                    }

                    $enquiryType = $enquiry->seller_id
                        ? 'Direct Enquiry'
                        : ($enquiry->rfqs->count()
                            ? 'Auto-RFQ Enquiry'
                            : 'General Enquiry');

                    $statusClasses = match($enquiry->status) {
                        'new' => 'bg-blue-50 text-blue-700',
                        'pending_review' => 'bg-yellow-50 text-yellow-700',
                        'accepted' => 'bg-green-50 text-green-700',
                        'rejected' => 'bg-red-50 text-red-700',
                        'rfq_generated' => 'bg-purple-50 text-purple-700',
                        'contacted' => 'bg-indigo-50 text-indigo-700',
                        'quotation_sent' => 'bg-teal-50 text-teal-700',
                        'closed' => 'bg-gray-100 text-gray-600',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">

                    {{-- Header --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                        <div>
                            <div class="flex items-center gap-2">

                                <h3 class="font-semibold text-gray-900">
                                    Enquiry #{{ $enquiry->id }}
                                </h3>

                                <span class="rounded-full bg-[#E8F5F4] px-2.5 py-1 text-xs font-medium text-[#056663]">
                                    {{ $enquiryType }}
                                </span>

                            </div>

                            <p class="mt-1 text-xs text-gray-400">
                                {{ $enquiry->created_at?->format('d M Y, h:i A') }}
                            </p>
                        </div>

                        <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                            {{ ucwords(str_replace('_', ' ', $enquiry->status)) }}
                        </span>

                    </div>

                    {{-- Enquiry Information --}}
                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Seller
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $seller?->bussiness_name ?? 'Not assigned' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Listing
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ $enquiry->listing?->title ?? 'General Requirement' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Requirement Type
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">
                                {{ ucfirst($enquiry->requirement_type ?? 'General') }}
                            </p>
                        </div>

                    </div>

                    {{-- Buyer Requirement --}}
                    @if($enquiry->message)
                        <div class="mt-5 rounded-lg bg-[#F7FAFA] p-4">

                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                Buyer Requirement
                            </p>

                            <p class="mt-2 text-sm leading-6 text-gray-700">
                                {{ $enquiry->message }}
                            </p>

                        </div>
                    @endif

                    {{-- Contact Information --}}
                    <div class="mt-5 grid grid-cols-1 gap-4 border-t border-gray-100 pt-4 sm:grid-cols-3">

                        <div>
                            <p class="text-xs text-gray-400">Buyer Name</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $enquiry->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $enquiry->email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Phone</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $enquiry->phone ?? '—' }}
                            </p>
                        </div>

                    </div>

                    {{-- RFQ Information --}}
                    @if($enquiry->rfqs->count())

                        <div class="mt-5 border-t border-gray-100 pt-4">

                            <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                                RFQ Information
                            </p>

                            <div class="space-y-2">

                                @foreach($enquiry->rfqs as $rfq)

                                    <div class="flex flex-col gap-2 rounded-lg bg-gray-50 p-3 sm:flex-row sm:items-center sm:justify-between">

                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                RFQ #{{ $rfq->id }}
                                            </p>

                                            <p class="text-xs text-gray-500">
                                                Seller:
                                                {{ $rfq->seller?->bussiness_name ?? '—' }}
                                            </p>
                                        </div>

                                        <div class="text-left sm:text-right">

                                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-gray-600">
                                                {{ ucfirst($rfq->status) }}
                                            </span>

                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $rfq->sent_at?->format('d M Y, h:i A') ?? '—' }}
                                            </p>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif

                </div>

            @endforeach

        </div>

    @else

        <div class="rounded-xl border border-dashed border-[#DDE9E8] bg-white p-10 text-center">

            <span class="material-symbols-outlined text-4xl text-gray-300">
                chat
            </span>

            <p class="mt-3 font-medium text-gray-700">
                No enquiries found
            </p>

            <p class="mt-1 text-sm text-gray-400">
                This buyer has not sent any enquiries to sellers yet.
            </p>

        </div>

    @endif

</div>
    
    {{-- ========================================================= --}}
    {{-- REQUIREMENTS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        Buyer Requirements
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Requirements submitted by this buyer.
                    </p>

                </div>

                <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                    {{ $requirementCount }}
                </span>

            </div>

        </div>


        @if($requirements->count())

            <div class="divide-y divide-gray-100">

                @foreach($requirements as $requirement)

                    <div class="p-6">

                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                            <div class="min-w-0">

                                <div class="flex flex-wrap items-center gap-2">

                                    <span class="text-sm font-bold text-gray-900">
                                        Requirement #{{ $requirement->id }}
                                    </span>


                                    @if($requirement->requirement_type)

                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                            {{ ucfirst($requirement->requirement_type) }}
                                        </span>

                                    @endif


                                    @if($requirement->status)

                                        @php
                                            $requirementStatusClasses = match($requirement->status) {
                                                'new' => 'bg-gray-100 text-gray-700',
                                                'pending_review' => 'bg-yellow-50 text-yellow-700',
                                                'accepted' => 'bg-blue-50 text-blue-700',
                                                'rejected' => 'bg-red-50 text-red-700',
                                                'rfq_generated' => 'bg-purple-50 text-purple-700',
                                                'contacted' => 'bg-indigo-50 text-indigo-700',
                                                'quotation_sent' => 'bg-green-50 text-green-700',
                                                'closed' => 'bg-gray-100 text-gray-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp

                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $requirementStatusClasses }}">
                                            {{ ucwords(str_replace('_', ' ', $requirement->status)) }}
                                        </span>

                                    @endif

                                </div>


                                {{-- Requirement Information --}}
                                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Requirement Type
                                        </p>

                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ ucfirst($requirement->requirement_type ?? 'N/A') }}
                                        </p>
                                    </div>


                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Quantity
                                        </p>

                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ $requirement->quantity ?? 'N/A' }}
                                            {{ $requirement->unit ?? '' }}
                                        </p>
                                    </div>


                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Budget
                                        </p>

                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ $requirement->budget ? '₹ ' . number_format((float) $requirement->budget, 2) : 'N/A' }}
                                        </p>
                                    </div>


                                    <div>
                                        <p class="text-xs text-gray-500">
                                            Required Date
                                        </p>

                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ $requirement->required_date?->format('d M Y') ?? 'N/A' }}
                                        </p>
                                    </div>

                                </div>


                                {{-- Location --}}
                                <div class="mt-4">

                                    <p class="text-xs text-gray-500">
                                        Location
                                    </p>

                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ $requirement->delivery_location ?? 'N/A' }}
                                    </p>

                                </div>


                                {{-- Product --}}
                                @if($requirement->requirement_type === 'product')

                                    <div class="mt-4 rounded-xl bg-[#F7FAFA] p-4">

                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Product Details
                                        </p>

                                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Product
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->product_name ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Category
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->product_category ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Quality
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->product_quality ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Brand
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->preferred_brand ?? 'N/A' }}
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                @endif


                                {{-- Property --}}
                                @if(in_array($requirement->requirement_type, ['property', 'properties']))

                                    <div class="mt-4 rounded-xl bg-[#F7FAFA] p-4">

                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Property Details
                                        </p>

                                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Property Type
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->property_type ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Purpose
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->property_purpose ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Location
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->property_location ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Area
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->property_area ?? 'N/A' }}
                                                    {{ $requirement->property_area_unit ?? '' }}
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                @endif


                                {{-- Land --}}
                                @if(in_array($requirement->requirement_type, ['land', 'lands']))

                                    <div class="mt-4 rounded-xl bg-[#F7FAFA] p-4">

                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Land Details
                                        </p>

                                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Land Type
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->land_type ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Purpose
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->land_purpose ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Location
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->land_location ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Area
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->land_area ?? 'N/A' }}
                                                    {{ $requirement->land_area_unit ?? '' }}
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                @endif


                                {{-- Service --}}
                                @if(in_array($requirement->requirement_type, ['service', 'services']))

                                    <div class="mt-4 rounded-xl bg-[#F7FAFA] p-4">

                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Service Details
                                        </p>

                                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Service
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->service_name ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Type
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->service_type ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Location
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->service_location ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Timeline
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->service_timeline ?? 'N/A' }}
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                @endif


                                {{-- Project --}}
                                @if(in_array($requirement->requirement_type, ['project', 'projects']))

                                    <div class="mt-4 rounded-xl bg-[#F7FAFA] p-4">

                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            Project Details
                                        </p>

                                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Project Type
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->project_type ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Project Name
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->project_name ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Location
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->project_location ?? 'N/A' }}
                                                </p>
                                            </div>

                                            <div>
                                                <span class="text-xs text-gray-500">
                                                    Timeline
                                                </span>

                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $requirement->project_timeline ?? 'N/A' }}
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                @endif


                                {{-- Message --}}
                                @if($requirement->message)

                                    <div class="mt-4">

                                        <p class="text-xs text-gray-500">
                                            Buyer Message
                                        </p>

                                        <p class="mt-1 rounded-xl bg-gray-50 p-3 text-sm text-gray-700">
                                            {{ $requirement->message }}
                                        </p>

                                    </div>

                                @endif

                            </div>


                            {{-- Date --}}
                            <div class="shrink-0 text-left lg:text-right">

                                <p class="text-xs text-gray-500">
                                    Submitted
                                </p>

                                <p class="mt-1 text-sm font-medium text-gray-900">
                                    {{ $requirement->created_at?->format('d M Y') }}
                                </p>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <span class="material-symbols-outlined text-4xl text-gray-300">
                    description
                </span>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No requirements found
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    This buyer has not submitted any requirements yet.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- RFQs --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        Buyer RFQs
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        RFQs generated from this buyer's requirements.
                    </p>

                </div>

                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ $rfqCount }}
                </span>

            </div>

        </div>


        @if($rfqs->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-[#F7FAFA]">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                RFQ
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Requirement
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Seller
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Sent
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach($rfqs as $rfq)

                            <tr class="transition hover:bg-[#F7FAFA]">

                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="text-sm font-semibold text-gray-900">
                                        #{{ $rfq->id }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <div>

                                        <p class="text-sm font-medium text-gray-900">
                                            Requirement #{{ $rfq->requirement_id }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ ucfirst($rfq->requirement?->requirement_type ?? 'General Requirement') }}
                                        </p>

                                    </div>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $rfq->seller?->bussiness_name ?? 'Seller #' . $rfq->seller_id }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    @php
                                        $rfqStatusClasses = match($rfq->status) {
                                            'new' => 'bg-blue-50 text-blue-700',
                                            'viewed' => 'bg-yellow-50 text-yellow-700',
                                            'quoted' => 'bg-green-50 text-green-700',
                                            'rejected' => 'bg-red-50 text-red-700',
                                            'closed' => 'bg-gray-100 text-gray-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $rfqStatusClasses }}">
                                        {{ ucfirst($rfq->status ?? 'N/A') }}
                                    </span>

                                </td>


                                <td class="whitespace-nowrap px-6 py-4">

                                    <p class="text-sm text-gray-700">
                                        {{ $rfq->sent_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                    </p>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <span class="material-symbols-outlined text-4xl text-gray-300">
                    request_quote
                </span>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No RFQs found
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    No RFQs have been generated for this buyer.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- QUOTATIONS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        Seller Quotations
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Quotations received for this buyer's requirements.
                    </p>

                </div>

                <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">
                    {{ $quotationCount }}
                </span>

            </div>

        </div>


        @if($quotations->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-[#F7FAFA]">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Quotation
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Requirement
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Seller
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Total Amount
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Valid Until
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach($quotations as $quotation)

                            <tr class="transition hover:bg-[#F7FAFA]">

                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="text-sm font-semibold text-gray-900">
                                        #{{ $quotation->id }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="text-sm font-medium text-gray-900">
                                        Requirement #{{ $quotation->requirement_id }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ ucfirst($quotation->requirement?->requirement_type ?? 'General') }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $quotation->seller?->bussiness_name ?? 'Seller' }}
                                    </p>

                                </td>


                                <td class="whitespace-nowrap px-6 py-4">

                                    <p class="text-sm font-bold text-gray-900">
                                        ₹ {{ number_format((float) ($quotation->total_amount ?? 0), 2) }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    @php
                                        $quotationStatusClasses = match($quotation->status) {
                                            'sent' => 'bg-blue-50 text-blue-700',
                                            'accepted' => 'bg-green-50 text-green-700',
                                            'rejected' => 'bg-red-50 text-red-700',
                                            'expired' => 'bg-gray-100 text-gray-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $quotationStatusClasses }}">
                                        {{ ucfirst($quotation->status ?? 'N/A') }}
                                    </span>

                                </td>


                                <td class="whitespace-nowrap px-6 py-4">

                                    <p class="text-sm text-gray-700">
                                        {{ $quotation->valid_until?->format('d M Y') ?? 'N/A' }}
                                    </p>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <span class="material-symbols-outlined text-4xl text-gray-300">
                    receipt_long
                </span>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No quotations found
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    No seller quotations have been received yet.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- ENQUIRY MESSAGES --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        Enquiry Messages
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Communication related to this buyer's requirements.
                    </p>

                </div>

                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                    {{ $messageCount }}
                </span>

            </div>

        </div>


        @if($messages->count())

            <div class="divide-y divide-gray-100">

                @foreach($messages as $message)

                    <div class="p-6">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $message->sender?->name ?? 'User' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    Requirement #{{ $message->requirement_id }}
                                </p>

                            </div>

                            <span class="text-xs text-gray-500">
                                {{ $message->created_at?->format('d M Y, h:i A') }}
                            </span>

                        </div>


                        <div class="mt-3 rounded-xl bg-[#F7FAFA] p-4">

                            <p class="text-sm leading-6 text-gray-700">
                                {{ $message->message ?? $message->content ?? 'No message content.' }}
                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <span class="material-symbols-outlined text-4xl text-gray-300">
                    chat
                </span>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No enquiry messages
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    No communication has been recorded for this buyer's requirements.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- ACCOUNT ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

        <h3 class="text-lg font-semibold text-gray-900">
            Account Actions
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            Manage the buyer's account status.
        </p>

        <div class="mt-5 flex flex-wrap gap-3">

            @if(($buyer->status ?? 'active') === 'active')

                <form
                    method="POST"
                    action="{{ route('admin.buyers.deactivate', $buyer->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to deactivate this buyer?')"
                        class="rounded-xl border border-red-200 bg-red-50 px-5 py-2.5
                               text-sm font-semibold text-red-700 transition hover:bg-red-100"
                    >
                        Deactivate Account
                    </button>

                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('admin.buyers.activate', $buyer->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="rounded-xl bg-[#056663] px-5 py-2.5
                               text-sm font-semibold text-white transition hover:bg-[#045451]"
                    >
                        Activate Account
                    </button>

                </form>

            @endif


            <a
                href="{{ route('admin.buyers.index') }}"
                class="rounded-xl border border-gray-300 bg-white px-5 py-2.5
                       text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                Back to Buyers
            </a>

        </div>

    </div>

</div>

@endsection