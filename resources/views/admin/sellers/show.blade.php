@extends('admin.layouts.app')

@section('title', 'Seller Details')

@section('page-title', 'Seller Details')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- ========================================================= --}}
    {{-- FLASH MESSAGES --}}
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
                href="{{ route('admin.sellers.index') }}"
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
                    Seller Details
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    View and manage seller account and marketplace activity.
                </p>
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SELLER PROFILE --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        {{-- Profile Header --}}
        <div class="border-b border-gray-100 bg-[#F7FAFA] px-6 py-6">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                {{-- Logo --}}
                <div
                    class="flex h-16 w-16 shrink-0 items-center justify-center
                           overflow-hidden rounded-2xl bg-[#E8F5F4]
                           text-2xl font-bold text-[#056663]"
                >

                    @if($seller->logo)

                        <img
                            src="{{ route('uploads.file', ['path' => $seller->logo]) }}"
                            alt="{{ $seller->bussiness_name }}"
                            class="h-full w-full object-cover"
                        >

                    @else

                        {{ strtoupper(substr($seller->bussiness_name ?? 'S', 0, 1)) }}

                    @endif

                </div>


                {{-- Seller Name --}}
                <div class="flex-1">

                    <div class="flex flex-wrap items-center gap-3">

                        <h2 class="text-xl font-bold text-gray-900">
                            {{ $seller->bussiness_name ?? 'N/A' }}
                        </h2>


                        {{-- Verification Badge --}}
                        @if($seller->is_verified)

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full
                                       bg-green-50 px-3 py-1 text-xs font-semibold
                                       text-green-700"
                            >

                                <span class="material-symbols-outlined text-[15px]">
                                    verified
                                </span>

                                Verified

                            </span>

                        @else

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full
                                       bg-yellow-50 px-3 py-1 text-xs font-semibold
                                       text-yellow-700"
                            >

                                <span class="material-symbols-outlined text-[15px]">
                                    pending
                                </span>

                                Not Verified

                            </span>

                        @endif


                        {{-- Featured Badge --}}
                        @if($seller->is_featured)

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full
                                       bg-purple-50 px-3 py-1 text-xs font-semibold
                                       text-purple-700"
                            >

                                <span class="material-symbols-outlined text-[15px]">
                                    star
                                </span>

                                Featured

                            </span>

                        @endif

                    </div>


                    <p class="mt-1 text-sm text-gray-500">
                        Seller ID #{{ $seller->id }}
                    </p>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- SELLER INFORMATION --}}
        {{-- ===================================================== --}}

        <div class="p-6">

            <h3 class="text-lg font-semibold text-gray-900">
                Seller Information
            </h3>


            <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                {{-- Business Name --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Business Name
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->bussiness_name ?? 'N/A' }}
                    </p>
                </div>


                {{-- Owner --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Owner
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->user?->name ?? 'N/A' }}
                    </p>
                </div>


                {{-- Email --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Email
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->user?->email ?? 'N/A' }}
                    </p>
                </div>


                {{-- Phone --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Phone
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->user?->phone ?? 'N/A' }}
                    </p>
                </div>


                {{-- Category --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Category
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->category ?? 'N/A' }}
                    </p>
                </div>


                {{-- Location --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Location
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->location ?? 'N/A' }}
                    </p>
                </div>


                {{-- Seller Slug --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Seller Slug
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->slug ?? 'N/A' }}
                    </p>
                </div>


                {{-- Registered --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Registered On
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                    </p>
                </div>


                {{-- Updated --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Last Updated
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $seller->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ACTIVITY COUNTS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">

        {{-- Listings --}}
        <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

            <p class="text-sm font-medium text-gray-500">
                Listings
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $listingCount }}
            </p>

            <p class="mt-3 text-xs text-gray-500">
                Seller marketplace listings
            </p>

        </div>


    {{-- BUYER ENQUIRIES --}}
<div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between">

        <div>
            <p class="text-sm font-medium text-gray-500">
                Buyer Enquiries
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
               {{ $buyerEnquiries }}
            </p>

            <p class="mt-3 text-xs text-gray-500">
                Enquiries received from buyers
            </p>
        </div>

      
    </div>

</div>


        {{-- RFQs --}}
        <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

            <p class="text-sm font-medium text-gray-500">
                RFQs
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $rfqCount }}
            </p>

            <p class="mt-3 text-xs text-gray-500">
                RFQs received from buyers
            </p>

        </div>


        {{-- Quotations --}}
        <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

            <p class="text-sm font-medium text-gray-500">
                Quotations
            </p>

            <p class="mt-2 text-3xl font-bold text-gray-900">
                {{ $quotationCount }}
            </p>

            <p class="mt-3 text-xs text-gray-500">
                Quotations submitted
            </p>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- LISTINGS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <h3 class="text-lg font-semibold text-gray-900">
                Seller Listings
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Listings created by this seller.
            </p>

        </div>


        @if($listings->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-[#F7FAFA]">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Listing
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Category
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Created
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($listings as $listing)

                            <tr class="transition hover:bg-[#F7FAFA]">

                                <td class="px-6 py-4">

                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $listing->title ?? 'Untitled Listing' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Listing #{{ $listing->id }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    <span class="text-sm text-gray-700">
                                        Category #{{ $listing->category_id ?? 'N/A' }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    @php

                                        $listingStatusClasses = match($listing->status) {

                                            'approved' =>
                                                'bg-green-50 text-green-700',

                                            'pending' =>
                                                'bg-yellow-50 text-yellow-700',

                                            'rejected' =>
                                                'bg-red-50 text-red-700',

                                            default =>
                                                'bg-gray-100 text-gray-700',

                                        };

                                    @endphp

                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-semibold
                                               {{ $listingStatusClasses }}"
                                    >
                                        {{ ucfirst($listing->status ?? 'N/A') }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <span class="text-sm text-gray-700">
                                        {{ $listing->created_at?->format('d M Y') ?? 'N/A' }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <span class="material-symbols-outlined text-4xl text-gray-300">
                    inventory_2
                </span>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No listings found
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    This seller has not created any listings yet.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- RFQs --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <h3 class="text-lg font-semibold text-gray-900">
                Seller RFQs
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                RFQs received by this seller.
            </p>

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
                                Buyer
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Requirement
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Sent
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($rfqs as $rfq)

                            <tr class="transition hover:bg-[#F7FAFA]">

                                <td class="px-6 py-4">

                                    <span class="text-sm font-semibold text-gray-900">
                                        #{{ $rfq->id }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $rfq->requirement?->user?->name ?? 'N/A' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $rfq->requirement?->user?->email ?? '' }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="text-sm font-medium text-gray-900">
                                        Requirement #{{ $rfq->requirement_id }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ ucfirst($rfq->requirement?->requirement_type ?? 'General') }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    @php

                                        $rfqStatusClasses = match($rfq->status) {

                                            'new' =>
                                                'bg-blue-50 text-blue-700',

                                            'viewed' =>
                                                'bg-yellow-50 text-yellow-700',

                                            'quoted' =>
                                                'bg-green-50 text-green-700',

                                            'rejected' =>
                                                'bg-red-50 text-red-700',

                                            'closed' =>
                                                'bg-gray-100 text-gray-700',

                                            default =>
                                                'bg-gray-100 text-gray-700',

                                        };

                                    @endphp

                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-semibold
                                               {{ $rfqStatusClasses }}"
                                    >
                                        {{ ucfirst($rfq->status ?? 'N/A') }}
                                    </span>

                                </td>


                                <td class="px-6 py-4 whitespace-nowrap">

                                    <span class="text-sm text-gray-700">
                                        {{ $rfq->sent_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                    </span>

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
                    This seller has not received any RFQs yet.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- QUOTATIONS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <h3 class="text-lg font-semibold text-gray-900">
                Seller Quotations
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Quotations submitted by this seller.
            </p>

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
                                Buyer
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Requirement
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Total
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($quotations as $quotation)

                            <tr class="transition hover:bg-[#F7FAFA]">

                                <td class="px-6 py-4">

                                    <span class="text-sm font-semibold text-gray-900">
                                        #{{ $quotation->id }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $quotation->buyer?->name ?? $quotation->requirement?->user?->name ?? 'N/A' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $quotation->buyer?->email ?? $quotation->requirement?->user?->email ?? '' }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="text-sm font-medium text-gray-900">
                                        Requirement #{{ $quotation->requirement_id }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ ucfirst($quotation->requirement?->requirement_type ?? 'General') }}
                                    </p>

                                </td>


                                <td class="px-6 py-4 whitespace-nowrap">

                                    <span class="text-sm font-bold text-gray-900">
                                        ₹ {{ number_format((float) ($quotation->total_amount ?? 0), 2) }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    @php

                                        $quotationStatusClasses = match($quotation->status) {

                                            'sent' =>
                                                'bg-blue-50 text-blue-700',

                                            'accepted' =>
                                                'bg-green-50 text-green-700',

                                            'rejected' =>
                                                'bg-red-50 text-red-700',

                                            'expired' =>
                                                'bg-gray-100 text-gray-700',

                                            default =>
                                                'bg-gray-100 text-gray-700',

                                        };

                                    @endphp

                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-semibold
                                               {{ $quotationStatusClasses }}"
                                    >
                                        {{ ucfirst($quotation->status ?? 'N/A') }}
                                    </span>

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
                    This seller has not submitted any quotations yet.
                </p>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- SELLER MANAGEMENT --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

            <div>

                <h3 class="text-lg font-semibold text-gray-900">
                    Seller Management
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Manage seller verification and featured status.
                </p>

            </div>


            {{-- Current Status --}}
            <div>

                @if($seller->is_verified)

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full
                               bg-green-50 px-3 py-1.5
                               text-xs font-semibold text-green-700"
                    >

                        <span class="material-symbols-outlined text-[16px]">
                            verified
                        </span>

                        Verified Seller

                    </span>

                @else

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full
                               bg-yellow-50 px-3 py-1.5
                               text-xs font-semibold text-yellow-700"
                    >

                        <span class="material-symbols-outlined text-[16px]">
                            pending
                        </span>

                        Verification Pending

                    </span>

                @endif

            </div>

        </div>


        {{-- Actions --}}
        <div class="mt-6 flex flex-wrap gap-3">

            {{-- ================================================= --}}
            {{-- VERIFY SELLER --}}
            {{-- ================================================= --}}

            @if($seller->is_verified)

                <form
                    method="POST"
                    action="{{ route('admin.sellers.unverify', $seller->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Are you sure you want to remove verification from this seller?')"
                        class="inline-flex items-center gap-2 rounded-xl
                               border border-yellow-200 bg-yellow-50
                               px-5 py-2.5 text-sm font-semibold
                               text-yellow-700 transition
                               hover:bg-yellow-100"
                    >

                        <span class="material-symbols-outlined text-[18px]">
                            verified_off
                        </span>

                        Remove Verification

                    </button>

                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('admin.sellers.verify', $seller->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Verify this seller?')"
                        class="inline-flex items-center gap-2 rounded-xl
                               bg-[#056663] px-5 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-[#045451]"
                    >

                        <span class="material-symbols-outlined text-[18px]">
                            verified
                        </span>

                        Verify Seller

                    </button>

                </form>

            @endif


            {{-- ================================================= --}}
            {{-- FEATURED SELLER --}}
            {{-- ================================================= --}}

            @if($seller->is_featured)

                <form
                    method="POST"
                    action="{{ route('admin.sellers.unfeature', $seller->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Remove this seller from featured sellers?')"
                        class="inline-flex items-center gap-2 rounded-xl
                               border border-purple-200 bg-purple-50
                               px-5 py-2.5 text-sm font-semibold
                               text-purple-700 transition
                               hover:bg-purple-100"
                    >

                        <span class="material-symbols-outlined text-[18px]">
                            star
                        </span>

                        Remove Featured

                    </button>

                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('admin.sellers.feature', $seller->id) }}"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        onclick="return confirm('Mark this seller as featured?')"
                        class="inline-flex items-center gap-2 rounded-xl
                               border border-purple-200 bg-white
                               px-5 py-2.5 text-sm font-semibold
                               text-purple-700 transition
                               hover:bg-purple-50"
                    >

                        <span class="material-symbols-outlined text-[18px]">
                            star
                        </span>

                        Mark as Featured

                    </button>

                </form>

            @endif


            {{-- ================================================= --}}
            {{-- BACK TO SELLERS --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('admin.sellers.index') }}"
                class="inline-flex items-center gap-2 rounded-xl
                       border border-gray-300 bg-white
                       px-5 py-2.5 text-sm font-semibold
                       text-gray-700 transition hover:bg-gray-50"
            >

                <span class="material-symbols-outlined text-[18px]">
                    arrow_back
                </span>

                Back to Sellers

            </a>

        </div>

    </div>

</div>

@endsection