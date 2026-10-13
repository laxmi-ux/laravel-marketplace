@extends('admin.layouts.app')

@section('title', 'View Listing')

@section('page_title', 'View Listing')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Listing Details
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                View complete information about this listing.
            </p>
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('admin.listings.edit', $listing->id) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-[#056663] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#04534f]"
            >
                <span class="material-symbols-outlined text-[18px]">
                    edit
                </span>

                Edit Listing
            </a>

            <a
                href="{{ route('admin.listings.index') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
            >
                <span class="material-symbols-outlined text-[18px]">
                    arrow_back
                </span>

                Back
            </a>

        </div>

    </div>


    {{-- Main Listing --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Left --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Basic Information --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <div class="mb-6 flex items-center justify-between">

                    <h2 class="text-lg font-bold text-slate-900">
                        Basic Information
                    </h2>

                    @php
                        $status = strtolower($listing->status ?? 'pending');
                    @endphp

                    @if($status === 'approved')
                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                            Approved
                        </span>
                    @elseif($status === 'rejected')
                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                            Rejected
                        </span>
                    @elseif($status === 'draft')
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            Draft
                        </span>
                    @else
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                            Pending
                        </span>
                    @endif

                </div>


                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Listing Title
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $listing->title ?? '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Category
                        </p>

                        <p class="mt-1 font-semibold text-[#056663]">
                            {{ $listing->categoryRelation?->name ?? '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Subcategory
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $listing->subcategoryRelation?->name ?? '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Listing Type
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $listing->listing_type ?? '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Price
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            @if($listing->price !== null)
                                ₹{{ number_format((float) $listing->price, 2) }}
                                @if($listing->price_unit)
                                    / {{ $listing->price_unit }}
                                @endif
                            @else
                                —
                            @endif
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Seller
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $listing->seller?->bussiness_name ?? $listing->seller?->business_name ?? '—' }}
                        </p>
                    </div>

                </div>


                {{-- Description --}}
                <div class="mt-6 border-t border-slate-100 pt-6">

                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Description
                    </p>

                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">
                        {{ $listing->description ?? 'No description available.' }}
                    </p>

                </div>

            </div>


            {{-- Location --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <h2 class="mb-6 text-lg font-bold text-slate-900">
                    Location
                </h2>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            City
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $listing->city ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            State
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $listing->state ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Pincode
                        </p>

                        <p class="mt-1 font-semibold text-slate-900">
                            {{ $listing->pincode ?? '—' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Category Specific Details --}}
            @if($listing->product || $listing->property || $listing->land || $listing->service || $listing->project)

                <div class="rounded-2xl border border-slate-200 bg-white p-6">

                    <h2 class="mb-6 text-lg font-bold text-slate-900">
                        {{ $listing->categoryRelation?->name ?? 'Additional' }} Details
                    </h2>


                    {{-- Product --}}
                    @if($listing->product)

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            @foreach($listing->product->getAttributes() as $key => $value)

                                @if(!in_array($key, ['id', 'listing_id', 'created_at', 'updated_at']) && $value !== null && $value !== '')

                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $value }}
                                        </p>
                                    </div>

                                @endif

                            @endforeach

                        </div>

                    {{-- Property --}}
                    @elseif($listing->property)

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            @foreach($listing->property->getAttributes() as $key => $value)

                                @if(!in_array($key, ['id', 'listing_id', 'created_at', 'updated_at']) && $value !== null && $value !== '')

                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $value }}
                                        </p>
                                    </div>

                                @endif

                            @endforeach

                        </div>

                    {{-- Land --}}
                    @elseif($listing->land)

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            @foreach($listing->land->getAttributes() as $key => $value)

                                @if(!in_array($key, ['id', 'listing_id', 'created_at', 'updated_at']) && $value !== null && $value !== '')

                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $value }}
                                        </p>
                                    </div>

                                @endif

                            @endforeach

                        </div>

                    {{-- Service --}}
                    @elseif($listing->service)

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            @foreach($listing->service->getAttributes() as $key => $value)

                                @if(!in_array($key, ['id', 'listing_id', 'created_at', 'updated_at']) && $value !== null && $value !== '')

                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $value }}
                                        </p>
                                    </div>

                                @endif

                            @endforeach

                        </div>

                    {{-- Project --}}
                    @elseif($listing->project)

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            @foreach($listing->project->getAttributes() as $key => $value)

                                @if(!in_array($key, ['id', 'listing_id', 'created_at', 'updated_at']) && $value !== null && $value !== '')

                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $value }}
                                        </p>
                                    </div>

                                @endif

                            @endforeach

                        </div>

                    @endif

                </div>

            @endif

        </div>


        {{-- Right --}}
        <div class="space-y-6">

            {{-- Image --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

                @if($listing->image)

                    <img
                        src="{{ url('/uploads/' . $listing->image) }}"
                        alt="{{ $listing->title }}"
                        class="h-64 w-full object-cover"
                    >

                @else

                    <div class="flex h-64 items-center justify-center bg-slate-50">

                        <span class="material-symbols-outlined text-5xl text-slate-300">
                            image
                        </span>

                    </div>

                @endif

            </div>


            {{-- Seller --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <h2 class="mb-5 text-lg font-bold text-slate-900">
                    Seller Information
                </h2>

                @if($listing->seller)

                    <div class="space-y-4">

                        <div>
                            <p class="text-xs text-slate-400">
                                Business Name
                            </p>

                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $listing->seller->bussiness_name ?? $listing->seller->business_name ?? '—' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400">
                                Location
                            </p>

                            <p class="mt-1 font-semibold text-slate-800">
                                {{ $listing->seller->city ?? '—' }}
                            </p>
                        </div>

                    </div>

                @else

                    <p class="text-sm text-slate-500">
                        Seller information not available.
                    </p>

                @endif

            </div>


            {{-- Listing Meta --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                <h2 class="mb-5 text-lg font-bold text-slate-900">
                    Listing Information
                </h2>

                <div class="space-y-4 text-sm">

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">
                            Listing ID
                        </span>

                        <span class="font-semibold text-slate-800">
                            #{{ $listing->id }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">
                            Created
                        </span>

                        <span class="font-semibold text-slate-800">
                            {{ $listing->created_at?->format('d M Y') ?? '—' }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">
                            Updated
                        </span>

                        <span class="font-semibold text-slate-800">
                            {{ $listing->updated_at?->format('d M Y') ?? '—' }}
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection