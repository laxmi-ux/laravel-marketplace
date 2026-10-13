@extends('admin.layouts.app')

@section('title', 'Property Listings')

@section('page-title', 'Property Listings')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Property Listings
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Manage all property listings submitted by sellers.
            </p>
        </div>

        <a href="{{ route('admin.properties.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg
                  bg-[#056663] px-5 py-3 text-sm font-semibold text-white
                  transition hover:bg-[#04524F]">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 4v16m8-8H4" />

            </svg>

            Add Property Listing
        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

    <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>

    @endif


    {{-- =========================================================
        STAT CARDS
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Total Properties
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $totalProperties }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-gray-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4" />

                    </svg>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="rounded-xl border border-yellow-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Pending
                    </p>

                    <p class="mt-2 text-3xl font-bold text-yellow-600">
                        {{ $pendingProperties }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-yellow-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="rounded-xl border border-green-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Approved
                    </p>

                    <p class="mt-2 text-3xl font-bold text-green-600">
                        {{ $approvedProperties }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-green-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7" />

                    </svg>

                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="rounded-xl border border-red-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Rejected
                    </p>

                    <p class="mt-2 text-3xl font-bold text-red-600">
                        {{ $rejectedProperties }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-red-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <form method="GET"
            action="{{ route('admin.properties.index') }}">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                {{-- Search --}}
                <div class="md:col-span-2">

                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search property, city, state..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663]
                               focus:ring-[#056663]">

                </div>


                {{-- Approval Status --}}
                <div>

                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Approval Status
                    </label>

                    <select
                        name="approval_status"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                               text-sm focus:border-[#056663]
                               focus:ring-[#056663]">

                        <option value="">
                            All Approval Status
                        </option>

                        <option value="pending"
                            {{ request('approval_status') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="approved"
                            {{ request('approval_status') === 'approved' ? 'selected' : '' }}>
                            Approved
                        </option>

                        <option value="rejected"
                            {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>

                    </select>

                </div>


                {{-- Property Status --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Property Status
                    </label>

                    <select
                        name="property_status"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5
               text-sm focus:border-[#056663]
               focus:ring-[#056663]">

                        <option value="">
                            All Property Status
                        </option>

                        <option value="available"
                            {{ request('property_status') === 'available' ? 'selected' : '' }}>
                            Available
                        </option>

                        <option value="ready to move"
                            {{ request('property_status') === 'ready to move' ? 'selected' : '' }}>
                            Ready to Move
                        </option>

                        <option value="under construction"
                            {{ request('property_status') === 'under construction' ? 'selected' : '' }}>
                            Under Construction
                        </option>

                        <option value="sold"
                            {{ request('property_status') === 'sold' ? 'selected' : '' }}>
                            Sold
                        </option>

                        <option value="on hold"
                            {{ request('property_status') === 'on hold' ? 'selected' : '' }}>
                            On Hold
                        </option>

                    </select>
                </div>
            </div>


            {{-- Buttons --}}
            <div class="mt-4 flex gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-[#056663] px-5 py-2.5
                           text-sm font-semibold text-white
                           hover:bg-[#04524F]">

                    Apply Filters

                </button>

                <a
                    href="{{ route('admin.properties.index') }}"
                    class="rounded-lg border border-gray-300
                           px-5 py-2.5 text-sm font-semibold
                           text-gray-700 hover:bg-gray-50">

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- =========================================================
        PROPERTY TABLE
    ========================================================== --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Property
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Type
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Area
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Location
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Price
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Approval
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Property Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-200 bg-white">

                    @forelse($properties as $listing)

                    <tr class="hover:bg-gray-50">

                        {{-- Property --}}
                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                {{-- Image --}}
                                <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">

                                    @if($listing->image)

                                    <img
                                        src="{{ url('/uploads/' . $listing->image) }}"
                                        alt="{{ $listing->title }}"
                                        class="h-full w-full object-cover">

                                    @else

                                    <div class="flex h-full w-full items-center justify-center">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-gray-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 015.828 0L20 17M14 14l1.586-1.586a2 2 0 012.828 0L20 16M14 8h.01M4 20h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />

                                        </svg>

                                    </div>

                                    @endif

                                </div>


                                <div>

                                    <p class="font-semibold text-gray-900">
                                        {{ $listing->title }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        ID: #{{ $listing->id }}
                                    </p>

                                </div>

                            </div>

                        </td>


                        {{-- Property Type --}}
                        <td class="px-6 py-4 text-sm text-gray-700">

                            {{ $listing->property?->property_type
                                    ? ucwords(str_replace('_', ' ', $listing->property->property_type))
                                    : '—'
                                }}

                        </td>


                        {{-- Area --}}
                        <td class="px-6 py-4 text-sm text-gray-700">

                            @if($listing->property?->area)

                            {{ $listing->property->area }}
                            {{ $listing->property->area_unit }}

                            @else

                            —

                            @endif

                        </td>


                        {{-- Location --}}
                        <td class="px-6 py-4 text-sm text-gray-700">

                            {{ $listing->city ?? '—' }}

                            @if($listing->state)
                            <span class="text-gray-400">
                                , {{ $listing->state }}
                            </span>
                            @endif

                        </td>


                        {{-- Price --}}
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">

                            @if($listing->price)

                            ₹{{ number_format($listing->price) }}

                            @if($listing->price_unit)
                            <span class="text-xs font-normal text-gray-500">
                                / {{ $listing->price_unit }}
                            </span>
                            @endif

                            @else

                            —

                            @endif

                        </td>


                        {{-- Approval --}}
                        <td class="px-6 py-4">

                            @if($listing->status === 'approved')

                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                Approved
                            </span>

                            @elseif($listing->status === 'pending')

                            <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                Pending
                            </span>

                            @elseif($listing->status === 'rejected')

                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                Rejected
                            </span>

                            @else

                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                {{ ucfirst($listing->status ?? 'Unknown') }}
                            </span>

                            @endif

                        </td>


                        {{-- Property Status --}}
                        <td class="px-6 py-4">

                            @if($listing->property?->property_status)

                            @php
                            $propertyStatus = strtolower($listing->property->property_status);
                            @endphp

                            @if($propertyStatus === 'ready to move')

                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                Ready to Move
                            </span>

                            @elseif($propertyStatus === 'under construction')

                            <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                Under Construction
                            </span>

                            @elseif($propertyStatus === 'sold')

                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                Sold
                            </span>

                            @else

                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ ucwords($listing->property->property_status) }}
                            </span>

                            @endif

                            @else

                            <span class="text-sm text-gray-400">
                                —
                            </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7"
                            class="px-6 py-12 text-center">

                            <div class="flex flex-col items-center">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 text-gray-300"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4" />

                                </svg>

                                <p class="mt-3 text-sm font-medium text-gray-600">
                                    No property listings found.
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Try changing your search or filters.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($properties->hasPages())

        <div class="border-t border-gray-200 px-6 py-4">

            {{ $properties->links() }}

        </div>

        @endif

    </div>

</div>

@endsection