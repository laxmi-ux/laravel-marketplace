@extends('layouts.base')

@section('title', $seller->bussiness_name . ' - Seller Profile')

@section('content')

<main class="w-full bg-[#F7FAFA]">

    {{-- =========================================================
        SELLER HEADER
    ========================================================== --}}

    <section class="bg-white border-b border-gray-200">

        <div class="max-w-[1280px] mx-auto px-5 sm:px-8 lg:px-10 py-10 mt-20">

            <div class="flex flex-col md:flex-row gap-7 md:items-center">

                {{-- Seller Logo --}}
                <div class="flex-shrink-0">

                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center overflow-hidden">

                        @if($seller->logo)

                            @if(filter_var($seller->logo, FILTER_VALIDATE_URL))

                                <img
                                    src="{{ $seller->logo }}"
                                    alt="{{ $seller->bussiness_name }}"
                                    class="w-full h-full object-contain p-4">

                            @else

                                <img
                                    src="{{ asset($seller->logo) }}"
                                    alt="{{ $seller->bussiness_name }}"
                                    class="w-full h-full object-contain p-4">

                            @endif

                        @else

                            <span class="text-4xl font-bold text-[#056663]">

                                {{ collect(explode(' ', trim($seller->bussiness_name)))
                                    ->filter()
                                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                    ->take(2)
                                    ->implode('')
                                }}

                            </span>

                        @endif

                    </div>

                </div>


                {{-- Seller Information --}}
                <div class="flex-1">

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">
                            {{ $seller->bussiness_name }}
                        </h1>

                        @if($seller->is_verified)

                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#056663]/10 text-[#056663] text-xs font-semibold">

                                <span class="material-symbols-outlined text-[16px]">
                                    verified
                                </span>

                                Verified Seller

                            </span>

                        @endif

                    </div>


                    @if($seller->category)

                        <p class="mt-2 text-base text-gray-500">
                            {{ $seller->category }}
                        </p>

                    @endif


                    @if($seller->location)

                        <div class="flex items-center gap-2 mt-4 text-gray-500">

                            <span class="material-symbols-outlined text-[#056663]">
                                location_on
                            </span>

                            <span class="text-sm">
                                {{ $seller->location }}
                            </span>

                        </div>

                    @endif

                </div>


                {{-- Contact Button --}}
                <div class="flex-shrink-0">

                    <a
                        href="#contact-seller"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-[#056663] text-white text-sm font-semibold hover:bg-[#045451] transition-all">

                        <span class="material-symbols-outlined text-[19px]">
                            mail
                        </span>

                        Contact Seller

                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        SELLER CONTENT
    ========================================================== --}}

    <section class="max-w-[1280px] mx-auto px-5 sm:px-8 lg:px-10 py-12">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">


            {{-- =================================================
                LEFT CONTENT
            ================================================== --}}

            <div class="lg:col-span-8">

                {{-- About Seller --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8">

                    <div class="flex items-center gap-3 mb-5">

                        <div class="w-10 h-10 rounded-lg bg-[#056663]/10 flex items-center justify-center">

                            <span class="material-symbols-outlined text-[#056663]">
                                store
                            </span>

                        </div>

                        <h2 class="text-2xl font-bold text-gray-900">
                            About Seller
                        </h2>

                    </div>


                    @if($seller->description)

                        <p class="text-gray-600 leading-7 whitespace-pre-line">
                            {{ $seller->description }}
                        </p>

                    @else

                        <p class="text-gray-500">
                            {{ $seller->bussiness_name }} is a verified seller on our marketplace.
                        </p>

                    @endif

                </div>


                {{-- =================================================
                    SELLER LISTINGS
                ================================================== --}}

                <div class="mt-8">

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h2 class="text-2xl font-bold text-gray-900">
                                Seller Listings
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Products and listings available from this seller.
                            </p>

                        </div>

                    </div>


                    @if($listings->count())

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            @foreach($listings as $listing)

                                @php

                                    $listingImage = null;

                                    if ($listing->image) {

                                        if (str_starts_with($listing->image, 'listings/')) {

                                            $listingImage = route('uploads.file', [
                                                'path' => $listing->image
                                            ]);

                                        } else {

                                            $listingImage = asset($listing->image);

                                        }

                                    }

                                @endphp


                                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all">

                                    {{-- Image --}}
                                    <div class="relative h-52 bg-gray-100 overflow-hidden">

                                        @if($listingImage)

                                            <img
                                                src="{{ $listingImage }}"
                                                alt="{{ $listing->title }}"
                                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">

                                        @else

                                            <div class="w-full h-full flex items-center justify-center">

                                                <span class="material-symbols-outlined text-5xl text-gray-300">
                                                    image
                                                </span>

                                            </div>

                                        @endif


                                        @if($listing->is_verified)

                                            <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full bg-white/95 text-[#056663] text-xs font-semibold shadow-sm">

                                                <span class="material-symbols-outlined text-[14px]">
                                                    verified
                                                </span>

                                                Verified

                                            </span>

                                        @endif

                                    </div>


                                    {{-- Listing Details --}}
                                    <div class="p-5">

                                        <h3 class="text-lg font-bold text-gray-900 line-clamp-2">
                                            {{ $listing->title }}
                                        </h3>


                                        @if($listing->description)

                                            <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                                                {{ $listing->description }}
                                            </p>

                                        @endif


                                        @if($listing->city || $listing->state)

                                            <div class="flex items-center gap-2 mt-4 text-gray-500">

                                                <span class="material-symbols-outlined text-[18px] text-[#056663]">
                                                    location_on
                                                </span>

                                                <span class="text-sm truncate">

                                                    {{ $listing->city }}

                                                    @if($listing->state)
                                                        , {{ $listing->state }}
                                                    @endif

                                                </span>

                                            </div>

                                        @endif


                                        <div class="border-t border-gray-100 mt-4 pt-4 flex items-center justify-between gap-3">

                                            @if($listing->price)

                                                <div>

                                                    <span class="text-xs text-gray-400">
                                                        Starting from
                                                    </span>

                                                    <p class="text-xl font-bold text-[#056663]">
                                                        ₹{{ number_format($listing->price) }}
                                                    </p>

                                                </div>

                                            @else

                                                <div>

                                                    <span class="text-xs text-gray-400">
                                                        Pricing
                                                    </span>

                                                    <p class="text-lg font-bold text-[#056663]">
                                                        Get Quote
                                                    </p>

                                                </div>

                                            @endif


                                            <a
                                                href="{{ route('listing.show', $listing->id) }}"
                                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-[#056663] text-white text-sm font-semibold hover:bg-[#045451] transition-all">

                                                View

                                                <span class="material-symbols-outlined text-[16px]">
                                                    arrow_forward
                                                </span>

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>


                        {{-- Pagination --}}
                        @if($listings->hasPages())

                            <div class="mt-8">
                                {{ $listings->links() }}
                            </div>

                        @endif

                    @else

                        <div class="bg-white rounded-2xl border border-gray-200 text-center py-14 px-6">

                            <span class="material-symbols-outlined text-6xl text-gray-300">
                                inventory_2
                            </span>

                            <h3 class="mt-4 text-xl font-bold text-gray-900">
                                No Listings Available
                            </h3>

                            <p class="mt-2 text-sm text-gray-500">
                                This seller has not added any approved listings yet.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                RIGHT SIDEBAR
            ================================================== --}}

            <div class="lg:col-span-4">

                <div class="lg:sticky lg:top-6 space-y-6">


                    {{-- Seller Information --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-5">
                            Seller Information
                        </h3>


                        @if($seller->bussiness_name)

                            <div class="flex gap-3 mb-4">

                                <span class="material-symbols-outlined text-[#056663]">
                                    business
                                </span>

                                <div>

                                    <p class="text-xs text-gray-400">
                                        Business Name
                                    </p>

                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $seller->bussiness_name }}
                                    </p>

                                </div>

                            </div>

                        @endif


                        @if($seller->category)

                            <div class="flex gap-3 mb-4">

                                <span class="material-symbols-outlined text-[#056663]">
                                    category
                                </span>

                                <div>

                                    <p class="text-xs text-gray-400">
                                        Category
                                    </p>

                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $seller->category }}
                                    </p>

                                </div>

                            </div>

                        @endif


                        @if($seller->location)

                            <div class="flex gap-3">

                                <span class="material-symbols-outlined text-[#056663]">
                                    location_on
                                </span>

                                <div>

                                    <p class="text-xs text-gray-400">
                                        Location
                                    </p>

                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $seller->location }}
                                    </p>

                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- Contact Seller --}}
                    <div
                        id="contact-seller"
                        class="bg-white rounded-2xl border border-gray-200 p-6">

                        <h3 class="text-lg font-bold text-gray-900">
                            Interested in this Seller?
                        </h3>

                        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                            Send an enquiry to this seller for products,
                            properties, services or other requirements.
                        </p>


                        <a
                            href="{{ route('listing.search', ['seller_id' => $seller->id]) }}"
                            class="mt-5 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-[#056663] text-white text-sm font-semibold hover:bg-[#045451] transition-all">

                            <span class="material-symbols-outlined text-[18px]">
                                search
                            </span>

                            View Seller Listings

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

@endsection