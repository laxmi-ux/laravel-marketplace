@extends('layouts.base')

@section('title', 'Home')

@section('content')

<main class="w-full">

    {{-- =========================================================
        HERO SECTION
    ========================================================== --}}

    <section class="relative w-full min-h-[620px] lg:min-h-[680px] flex items-center justify-center overflow-hidden">

        {{-- Background --}}
        @forelse($banners as $index => $banner)

            <div
                class="hero-banner-bg absolute inset-0 bg-cover bg-center {{ $index === 0 ? '' : 'hidden' }}"
                data-banner-index="{{ $index }}"
                style="background-image: url('{{ route('uploads.file', ['path' => $banner->image]) }}');">
            </div>

        @empty

            {{-- Original Background --}}
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB12o8sL_txahucn7x37WS3yh0Vm-9Bzs9dpFXIfofxbnUAvRUX04o3DR7csjy_qvmzA6NauZRpBEb0GOnLgW60EisYrYysEilFeiSD2nznegiRaF12zbEO46O8vubz8EROawEleClyE0JTtL5oSl2VYBekfXKACkYBSmrdp86-Qepl85idespOIqUbE8BzjSAeZ9yl1buJBkONnonKqg7yrVgAaL7jA_ar_n3yeNGs_Z0cmmVgU24mLA');">
            </div>

        @endforelse


        {{-- Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-primary/50 to-primary/90"></div>


        {{-- Hero Content --}}
        <div class="relative z-10 w-full max-w-[1280px] mx-auto px-5 sm:px-8 lg:px-10 py-24">

            <div class="max-w-4xl mx-auto text-center">

                @forelse($banners as $index => $banner)

                    <div
                        class="hero-banner-content {{ $index === 0 ? '' : 'hidden' }}"
                        data-banner-content="{{ $index }}">

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-tight">
                            {{ $banner->title }}
                        </h1>

                        @if($banner->subtitle)

                            <p class="mt-6 text-base sm:text-lg lg:text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                                {{ $banner->subtitle }}
                            </p>

                        @endif

                    </div>

                @empty

                    {{-- Original Hero Content --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-5xl xl:text-6xl mt-20 font-bold text-white leading-tight">
                        Build. Buy. Grow.
                    </h1>

                    <p class="mt-6 text-base sm:text-lg lg:text-xl text-white/90 max-w-2xl mx-auto leading-relaxed">
                        Your trusted marketplace for properties, land, building materials,
                        products and leasing solutions. Discover verified opportunities
                        and connect with the right sellers.
                    </p>

                @endforelse

            </div>


            {{-- =================================================
                SEARCH BOX
            ================================================== --}}

            <div class="mt-10 max-w-6xl mx-auto">

                <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-4 sm:p-6 lg:p-7">

                    <form
                        action="{{ route('listing.search') }}"
                        method="GET">

                        <input
                            type="hidden"
                            name="type"
                            id="listingType"
                            value="{{ request('type', 'buy') }}">

                        <input
                            type="hidden"
                            name="category"
                            id="listingCategory"
                            value="{{ request('category') }}">


                        {{-- Search Tabs --}}
                        <div class="flex gap-2 sm:gap-5 overflow-x-auto border-b border-gray-200 mb-6 pb-1">

                            <button
                                type="button"
                                data-type="buy"
                                data-category=""
                                class="search-tab active-tab whitespace-nowrap px-3 sm:px-4 py-2.5 text-sm font-semibold">
                                Buy
                            </button>

                            <button
                                type="button"
                                data-type="rent"
                                data-category=""
                                class="search-tab whitespace-nowrap px-3 sm:px-4 py-2.5 text-sm font-medium text-gray-500">
                                Rent
                            </button>

                            <button
                                type="button"
                                data-type="lease"
                                data-category=""
                                class="search-tab whitespace-nowrap px-3 sm:px-4 py-2.5 text-sm font-medium text-gray-500">
                                Lease
                            </button>

                            <button
                                type="button"
                                data-type="buy"
                                data-category="land"
                                class="search-tab whitespace-nowrap px-3 sm:px-4 py-2.5 text-sm font-medium text-gray-500">
                                Land
                            </button>

                        </div>


                        {{-- Search Fields --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                            {{-- Location --}}
                            <div>

                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Location
                                </label>

                                <div class="relative">

                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">
                                        location_on
                                    </span>

                                    <input
                                        type="text"
                                        name="city"
                                        value="{{ request('city') }}"
                                        placeholder="City, Area or PIN"
                                        class="w-full h-12 rounded-lg bg-gray-100 border-0 pl-10 pr-3 text-sm text-gray-800 focus:ring-2 focus:ring-primary/30">

                                </div>

                            </div>


                            {{-- Category --}}
                            <div>

                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Category
                                </label>

                                <div class="relative">

                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">
                                        category
                                    </span>

                                    <select
                                        name="category"
                                        id="categorySelect"
                                        class="w-full h-12 rounded-lg bg-gray-100 border-0 pl-10 pr-8 text-sm text-gray-800 focus:ring-2 focus:ring-primary/30 appearance-none">

                                        <option value="">
                                            All Categories
                                        </option>

                                        @foreach ($categories as $category)

                                            <option
                                                value="{{ $category->slugs }}"
                                                {{ request('category') == $category->slugs ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                        expand_more
                                    </span>

                                </div>

                            </div>


                            {{-- Requirement --}}
                            <div>

                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Requirement
                                </label>

                                <div class="relative">

                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">
                                        tune
                                    </span>

                                    <select
                                        name="requirement"
                                        class="w-full h-12 rounded-lg bg-gray-100 border-0 pl-10 pr-8 text-sm text-gray-800 focus:ring-2 focus:ring-primary/30 appearance-none">

                                        <option value="">
                                            What are you looking for?
                                        </option>

                                        <option value="residential">
                                            Residential Property
                                        </option>

                                        <option value="commercial">
                                            Commercial Property
                                        </option>

                                        <option value="industrial">
                                            Industrial Property
                                        </option>

                                        <option value="land">
                                            Plot / Land
                                        </option>

                                        <option value="building-materials">
                                            Building Materials
                                        </option>

                                        <option value="rental">
                                            Rental / Lease
                                        </option>

                                    </select>

                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                        expand_more
                                    </span>

                                </div>

                            </div>


                            {{-- Keyword --}}
                            <div>

                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Keyword
                                </label>

                                <div class="relative">

                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">
                                        search
                                    </span>

                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Search listings..."
                                        class="w-full h-12 rounded-lg bg-gray-100 border-0 pl-10 pr-3 text-sm text-gray-800 focus:ring-2 focus:ring-primary/30">

                                </div>

                            </div>


                            {{-- Search Button --}}
                            <div class="flex items-end">

                                <button
                                    type="submit"
                                    class="w-full h-12 rounded-lg bg-primary text-white font-semibold flex items-center justify-center gap-2 hover:bg-primary/90 transition-all shadow-md">

                                    <span class="material-symbols-outlined text-[20px]">
                                        search
                                    </span>

                                    Search

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        HERO BANNER AUTO SLIDER
        Does not change existing design/layout
    ========================================================== --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const heroBannerBackgrounds =
                document.querySelectorAll('.hero-banner-bg');

            const heroBannerContents =
                document.querySelectorAll('.hero-banner-content');

            if (heroBannerBackgrounds.length > 1) {

                let currentBanner = 0;

                setInterval(function () {

                    heroBannerBackgrounds[currentBanner]
                        .classList.add('hidden');

                    if (heroBannerContents[currentBanner]) {
                        heroBannerContents[currentBanner]
                            .classList.add('hidden');
                    }

                    currentBanner =
                        (currentBanner + 1) %
                        heroBannerBackgrounds.length;

                    heroBannerBackgrounds[currentBanner]
                        .classList.remove('hidden');

                    if (heroBannerContents[currentBanner]) {
                        heroBannerContents[currentBanner]
                            .classList.remove('hidden');
                    }

                }, 5000);

            }

        });
    </script>


    {{-- =========================================================
        SHOP BY CATEGORY
    ========================================================== --}}

    <section
        id="categories"
        class="w-full max-w-[1480px] mx-auto px-5 sm:px-8 lg:px-10 py-20 lg:py-24">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-10">

            <div>

                <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-secondary-fixed-dim mb-2">
                    Explore
                </span>

                <h2 class="text-3xl sm:text-4xl font-bold text-primary">
                    Shop by Category
                </h2>

            </div>

            <a
                href="#featured-listings"
                class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:gap-3 transition-all">

                Explore Listings

                <span class="material-symbols-outlined text-[18px]">
                    arrow_forward
                </span>

            </a>

        </div>


        {{-- CATEGORY ROW --}}
        <div
            class="flex flex-nowrap overflow-x-auto lg:overflow-visible gap-5 sm:gap-6 lg:gap-8 pb-4 lg:pb-0 scrollbar-hide">

            @forelse ($categories as $category)

                <a
                    href="{{ route('listing.search', ['category' => $category->slugs]) }}"
                    class="group flex-shrink-0 w-[180px] sm:w-[200px] lg:flex-1 lg:w-auto text-center">

                    {{-- Image --}}
                    <div
                        class="relative w-full aspect-square rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm group-hover:shadow-xl transition-all duration-300">

                        @if($category->image)

                            @php

                                $categoryImage = $category->image;

                                if (is_string($categoryImage)) {

                                    $decodedCategoryImage =
                                        json_decode($categoryImage, true);

                                    if (
                                        json_last_error() === JSON_ERROR_NONE &&
                                        is_array($decodedCategoryImage)
                                    ) {

                                        $categoryImage =
                                            $decodedCategoryImage['url']
                                            ?? $decodedCategoryImage['src']
                                            ?? $decodedCategoryImage['image']
                                            ?? null;

                                    }

                                }

                            @endphp


                            @if($categoryImage)

                                <img
                                    src="{{ $categoryImage }}"
                                    alt="{{ $category->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    loading="lazy">

                            @else

                                <div class="w-full h-full flex items-center justify-center bg-gray-100">

                                    <span class="material-symbols-outlined text-5xl text-gray-300">
                                        image
                                    </span>

                                </div>

                            @endif

                        @else

                            <div class="w-full h-full flex items-center justify-center bg-gray-100">

                                <span class="material-symbols-outlined text-5xl text-gray-300">
                                    category
                                </span>

                            </div>

                        @endif


                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    </div>


                    {{-- Category Name --}}
                    <h3 class="mt-4 text-base sm:text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors">
                        {{ $category->name }}
                    </h3>

                    @if($category->description)

                        <p class="mt-1 text-xs sm:text-sm text-gray-500 line-clamp-2">
                            {{ $category->description }}
                        </p>

                    @endif

                </a>

            @empty

                <div class="w-full text-center py-10">

                    <span class="material-symbols-outlined text-5xl text-gray-300">
                        category
                    </span>

                    <p class="mt-3 text-gray-500">
                        No categories available.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================================================
        FEATURED LISTINGS
    ========================================================== --}}

    <section
        id="featured-listings"
        class="w-full max-w-[1480px] mx-auto px-5 sm:px-8 lg:px-10 pb-20 lg:pb-28">

        {{-- HEADER --}}
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-8">

            <div>

                <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-secondary-fixed-dim mb-2">
                    Marketplace
                </span>

                <h2 class="text-3xl sm:text-4xl font-bold text-primary">
                    Featured Listings
                </h2>

                <p class="mt-3 text-sm sm:text-base text-gray-500 max-w-2xl">
                    Discover verified products, properties, land and leasing
                    opportunities from trusted sellers.
                </p>

            </div>

            <a
                href="{{ route('listing.search') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:gap-3 transition-all">

                View All Listings

                <span class="material-symbols-outlined text-[18px]">
                    arrow_forward
                </span>

            </a>

        </div>


        {{-- CATEGORY FILTERS --}}
        <div class="flex flex-nowrap overflow-x-auto gap-2.5 pb-2 mb-8 scrollbar-hide">

            <button
                type="button"
                class="listing-filter active-filter whitespace-nowrap px-5 py-2.5 rounded-full bg-black text-white text-sm font-semibold"
                data-filter="all">
                All Listings
            </button>

            <button
                type="button"
                class="listing-filter whitespace-nowrap px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 text-sm font-medium"
                data-filter="land">
                Land
            </button>

            <button
                type="button"
                class="listing-filter whitespace-nowrap px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 text-sm font-medium"
                data-filter="properties">
                Properties
            </button>

            <button
                type="button"
                class="listing-filter whitespace-nowrap px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 text-sm font-medium"
                data-filter="products">
                Products
            </button>

            <button
                type="button"
                class="listing-filter whitespace-nowrap px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 text-sm font-medium"
                data-filter="services">
                Services
            </button>

            <button
                type="button"
                class="listing-filter whitespace-nowrap px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 text-sm font-medium"
                data-filter="projects">
                Projects
            </button>

        </div>


        {{-- LISTINGS GRID --}}
        <div
            id="listings-grid"
            class="flex flex-wrap justify-center gap-6">

            @forelse($featuredListings as $listing)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | CATEGORY
                    |--------------------------------------------------------------------------
                    */

                    $categoryId = (int) $listing->category_id;

                    $categoryMap = [
                        1 => 'properties',
                        2 => 'land',
                        3 => 'products',
                        4 => 'services',
                        5 => 'projects',
                    ];

                    $categoryNameMap = [
                        1 => 'Properties',
                        2 => 'Land',
                        3 => 'Products',
                        4 => 'Services',
                        5 => 'Projects',
                    ];

                    $categoryFilter =
                        $categoryMap[$categoryId] ?? '';

                    $categoryName =
                        $categoryNameMap[$categoryId] ?? 'Listing';


                    /*
                    |--------------------------------------------------------------------------
                    | SELLER
                    |--------------------------------------------------------------------------
                    */

                    $sellerName =
                        $listing->seller_name
                        ?? optional($listing->seller)->bussiness_name
                        ?? 'Verified Seller';

                @endphp


                {{-- LISTING CARD --}}
                <article
                    class="listing-card group w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] xl:w-[calc(25%-18px)] max-w-[340px] bg-white rounded-2xl overflow-hidden border border-gray-200 hover:border-primary/30 hover:shadow-xl transition-all duration-300"
                    data-category="{{ $categoryFilter }}">


                    {{-- IMAGE --}}
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


                    <div class="relative h-[210px] sm:h-[220px] overflow-hidden bg-gray-100">

                        @if($listingImage)

                            <img
                                src="{{ $listingImage }}"
                                alt="{{ $listing->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy">

                        @else

                            <div class="w-full h-full flex items-center justify-center">

                                <span class="material-symbols-outlined text-6xl text-gray-300">
                                    image
                                </span>

                            </div>

                        @endif


                        {{-- Image Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>


                        {{-- Category --}}
                        <div class="absolute top-3 left-3">

                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full bg-white/95 backdrop-blur-sm text-xs font-semibold text-primary shadow-sm">

                                {{ $categoryName }}

                            </span>

                        </div>


                        {{-- Verified --}}
                        @if($listing->is_verified)

                            <div class="absolute top-3 right-3">

                                <span
                                    class="w-9 h-9 rounded-full bg-white/95 backdrop-blur-sm flex items-center justify-center text-primary shadow-sm"
                                    title="Verified Seller">

                                    <span class="material-symbols-outlined text-[18px]">
                                        verified
                                    </span>

                                </span>

                            </div>

                        @endif

                    </div>


                    {{-- CARD CONTENT --}}
                    <div class="p-5">

                        {{-- Title --}}
                        <h3
                            class="text-lg font-bold text-gray-900 leading-snug line-clamp-2 min-h-[50px]">

                            {{ $listing->title }}

                        </h3>


                        {{-- Description --}}
                        <p
                            class="mt-2 text-sm text-gray-500 leading-relaxed line-clamp-2 min-h-[42px]">

                            {{ Str::limit($listing->description, 100) }}

                        </p>


                        {{-- Location --}}
                        <div class="flex items-center gap-2 mt-4 text-gray-500">

                            <span class="material-symbols-outlined text-[18px] text-primary">
                                location_on
                            </span>

                            <span class="text-sm truncate">

                                {{ $listing->city }}

                                @if($listing->state)
                                    , {{ $listing->state }}
                                @endif

                            </span>

                        </div>


                        {{-- Seller --}}
                        <div class="flex items-center gap-2 mt-2 text-gray-500">

                            <span class="material-symbols-outlined text-[18px] text-primary">
                                store
                            </span>

                            <span class="text-sm truncate">
                                {{ $sellerName }}
                            </span>

                        </div>


                        {{-- Divider --}}
                        <div class="border-t border-gray-100 mt-5 pt-4"></div>


                        {{-- Bottom --}}
                        <div class="flex items-end justify-between gap-3">

                            {{-- Price --}}
                            <div class="min-w-0">

                                @if($listing->price)

                                    <span class="block text-[11px] text-gray-400 mb-1">

                                        {{ $listing->listing_type === 'lease'
                                            ? 'Lease from'
                                            : 'Starting from'
                                        }}

                                    </span>

                                    <p class="text-xl font-bold text-primary truncate">

                                        ₹{{ number_format($listing->price) }}

                                    </p>

                                    @if($listing->price_unit)

                                        <span class="text-[11px] text-gray-400">

                                            /
                                            {{ str_replace('per ', '', $listing->price_unit) }}

                                        </span>

                                    @endif

                                @else

                                    <span class="block text-[11px] text-gray-400 mb-1">
                                        Pricing
                                    </span>

                                    <p class="text-lg font-bold text-primary">
                                        Get Quote
                                    </p>

                                @endif

                            </div>


                            {{-- View --}}
                            <a
                                href="{{ route('listing.show', $listing->id) }}"
                                class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-black text-white text-sm font-semibold hover:bg-primary transition-all">

                                View

                                <span class="material-symbols-outlined text-[16px]">
                                    arrow_forward
                                </span>

                            </a>

                        </div>

                    </div>

                </article>

            @empty

                <div class="w-full text-center py-16">

                    <span class="material-symbols-outlined text-6xl text-gray-300">
                        inventory_2
                    </span>

                    <h3 class="mt-4 text-xl font-bold text-primary">
                        No Featured Listings
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Featured listings will appear here once sellers add their listings.
                    </p>

                </div>

            @endforelse

        </div>


        {{-- NO FILTER RESULTS --}}
        <div
            id="no-filter-results"
            class="hidden w-full text-center py-16">

            <span class="material-symbols-outlined text-6xl text-gray-300">
                inventory_2
            </span>

            <h3 class="mt-4 text-xl font-bold text-primary">
                No Listings Found
            </h3>

            <p class="mt-2 text-sm text-gray-500">
                There are no listings available in this category.
            </p>

            <a
                href="{{ route('listing.search') }}"
                class="inline-flex items-center gap-2 mt-5 px-6 py-3 rounded-lg bg-black text-white text-sm font-semibold hover:bg-primary transition-all">

                Explore All Listings

                <span class="material-symbols-outlined text-[18px]">
                    arrow_forward
                </span>

            </a>

        </div>


        {{-- EXPLORE ALL --}}
        <div class="flex justify-center mt-12">

            <a
                href="{{ route('listing.search') }}"
                class="inline-flex items-center gap-3 px-7 py-3.5 rounded-lg border border-primary text-primary bg-white text-sm font-semibold hover:bg-primary hover:text-white transition-all">

                Explore All Listings

                <span class="material-symbols-outlined text-[18px]">
                    arrow_forward
                </span>

            </a>

        </div>

    </section>

{{-- =========================================================
 VERIFIED SELLERS
========================================================= --}}

<section class="w-full bg-gray-50 border-y border-gray-100">

    <div class="max-w-[1480px] mx-auto px-5 sm:px-8 lg:px-10 py-20 lg:py-24">

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">

            <div>

                <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-secondary-fixed-dim mb-2">
                    Trusted Network
                </span>

                <h2 class="text-3xl sm:text-4xl font-bold text-primary">
                    Verified Sellers
                </h2>

                <p class="mt-3 text-sm sm:text-base text-gray-500 max-w-2xl">
                    Connect with verified manufacturers, suppliers, property
                    owners and businesses offering quality products and services.
                </p>

            </div>

        </div>


        {{-- Sellers --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            @forelse($featuredSellers as $seller)

                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300">

                    {{-- Seller Logo --}}
                    <div class="h-36 bg-gray-50 flex items-center justify-center relative">

                        <div class="w-20 h-20 rounded-xl bg-white shadow-sm flex items-center justify-center overflow-hidden">

                            @if($seller->logo)

                                @if(filter_var($seller->logo, FILTER_VALIDATE_URL))

                                    <img
                                        src="{{ $seller->logo }}"
                                        alt="{{ $seller->bussiness_name }}"
                                        class="w-full h-full object-contain p-3"
                                        loading="lazy">

                                @else

                                    <img
                                        src="{{ asset($seller->logo) }}"
                                        alt="{{ $seller->bussiness_name }}"
                                        class="w-full h-full object-contain p-3"
                                        loading="lazy">

                                @endif

                            @else

                                <span class="text-2xl font-bold text-primary">

                                    {{ collect(explode(' ', trim($seller->bussiness_name)))
                                        ->filter()
                                        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                        ->take(2)
                                        ->implode('')
                                    }}

                                </span>

                            @endif

                        </div>


                        {{-- Verified Badge --}}
                        @if($seller->is_verified)

                            <div class="absolute top-4 right-4">

                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">

                                    <span class="material-symbols-outlined text-[14px]">
                                        verified
                                    </span>

                                    Verified

                                </span>

                            </div>

                        @endif

                    </div>


                    {{-- Seller Details --}}
                    <div class="p-5">

                        <h3 class="text-lg font-bold text-gray-900 truncate">
                            {{ $seller->bussiness_name }}
                        </h3>


                        @if($seller->category)

                            <p class="mt-1 text-sm text-gray-500 truncate">
                                {{ $seller->category }}
                            </p>

                        @endif


                        @if($seller->location)

                            <div class="flex items-center gap-2 mt-4 text-gray-500">

                                <span class="material-symbols-outlined text-[18px] text-primary">
                                    location_on
                                </span>

                                <span class="text-sm truncate">
                                    {{ $seller->location }}
                                </span>

                            </div>

                        @endif


                      <a
    href="{{ route('seller.public.show', $seller->id) }}"
    class="mt-5 w-full flex items-center justify-center gap-2 py-2.5 rounded-lg border border-primary text-primary text-sm font-semibold hover:bg-primary hover:text-white transition-all">

    View Seller

    <span class="material-symbols-outlined text-[17px]">
        arrow_forward
    </span>

</a>
                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-12">

                    <span class="material-symbols-outlined text-5xl text-gray-300">
                        store
                    </span>

                    <h3 class="mt-4 text-xl font-bold text-primary">
                        No Featured Sellers
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Featured sellers will appear here once they are added.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>


    {{-- =========================================================
        HOW MARKETPLACE WORKS
    ========================================================== --}}

    <section class="bg-primary text-white">

        <div class="max-w-[1480px] mx-auto px-5 sm:px-8 lg:px-10 py-20 lg:py-28">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-14 lg:gap-20">

                {{-- Left --}}
                <div class="lg:col-span-5">

                    <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-secondary-fixed mb-4">
                        How It Works
                    </span>

                    <h2 class="text-4xl sm:text-5xl font-bold leading-tight">
                        A Smarter Way to Source.
                    </h2>

                    <p class="mt-6 text-base sm:text-lg text-white/70 leading-relaxed">
                        Connect with verified sellers, discover the right products
                        and properties, and send your requirements directly through
                        one marketplace.
                    </p>

                </div>


                {{-- Features --}}
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-10">

                    {{-- Feature 1 --}}
                    <div>

                        <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center mb-5">

                            <span class="material-symbols-outlined text-[28px] text-secondary-fixed">
                                verified_user
                            </span>

                        </div>

                        <h3 class="text-xl font-bold">
                            Verified Sellers
                        </h3>

                        <p class="mt-3 text-sm sm:text-base text-white/70 leading-relaxed">
                            Connect with verified manufacturers, suppliers,
                            property owners and service providers.
                        </p>

                    </div>


                    {{-- Feature 2 --}}
                    <div>

                        <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center mb-5">

                            <span class="material-symbols-outlined text-[28px] text-secondary-fixed">
                                request_quote
                            </span>

                        </div>

                        <h3 class="text-xl font-bold">
                            Send Your Requirement
                        </h3>

                        <p class="mt-3 text-sm sm:text-base text-white/70 leading-relaxed">
                            Can't find what you need? Submit your requirement
                            and let relevant sellers respond.
                        </p>

                    </div>


                    {{-- Feature 3 --}}
                    <div>

                        <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center mb-5">

                            <span class="material-symbols-outlined text-[28px] text-secondary-fixed">
                                compare_arrows
                            </span>

                        </div>

                        <h3 class="text-xl font-bold">
                            Compare & Connect
                        </h3>

                        <p class="mt-3 text-sm sm:text-base text-white/70 leading-relaxed">
                            Compare products, prices, specifications and
                            sellers before making the right decision.
                        </p>

                    </div>


                    {{-- Feature 4 --}}
                    <div>

                        <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center mb-5">

                            <span class="material-symbols-outlined text-[28px] text-secondary-fixed">
                                handshake
                            </span>

                        </div>

                        <h3 class="text-xl font-bold">
                            Build Business Connections
                        </h3>

                        <p class="mt-3 text-sm sm:text-base text-white/70 leading-relaxed">
                            Connect buyers and sellers directly and create
                            long-term business relationships.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


  {{-- =========================================================
CTA
========================================================== --}}

<section class="max-w-[1280px] mx-auto px-5 sm:px-8 lg:px-10 py-20 lg:py-28">


<div class="relative overflow-hidden rounded-3xl
            bg-gray-50 border border-gray-200
            px-6 sm:px-10 lg:px-20
            py-16 lg:py-20 text-center">

    <div class="absolute -top-20 -right-20
                w-64 h-64 rounded-full
                bg-primary/5 blur-3xl">
    </div>

    <div class="absolute -bottom-20 -left-20
                w-72 h-72 rounded-full
                bg-secondary-fixed/10 blur-3xl">
    </div>


    <div class="relative z-10 max-w-2xl mx-auto">

        <h2 class="text-3xl sm:text-4xl lg:text-5xl
                   font-bold text-primary">
            List Your Property or Product
        </h2>


        <p class="mt-5 text-base sm:text-lg
                  text-gray-500 leading-relaxed">
            Reach verified buyers and grow your business
            through our trusted marketplace.
        </p>


        @guest

            {{-- Guest → Login --}}

            <a
                href="{{ route('login') }}"
                class="inline-flex items-center justify-center
                       gap-2 mt-8 px-8 py-3.5
                       rounded-lg bg-primary
                       text-white font-semibold
                       hover:bg-primary/90
                       transition-all shadow-lg"
            >

                Login as Seller to List

                <span class="material-symbols-outlined text-[18px]">
                    arrow_forward
                </span>

            </a>

        @else

            @if(Auth::user()->role === 'seller')

                {{-- Seller → Directly create listing --}}

                <a
                    href="{{ route('seller.listings.create') }}"
                    class="inline-flex items-center justify-center
                           gap-2 mt-8 px-8 py-3.5
                           rounded-lg bg-primary
                           text-white font-semibold
                           hover:bg-primary/90
                           transition-all shadow-lg"
                >

                    List Your Listing

                    <span class="material-symbols-outlined text-[18px]">
                        arrow_forward
                    </span>

                </a>

            @else

                {{-- Buyer → Login/Seller access --}}

                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center justify-center
                           gap-2 mt-8 px-8 py-3.5
                           rounded-lg bg-primary
                           text-white font-semibold
                           hover:bg-primary/90
                           transition-all shadow-lg"
                >

                    Login as Seller to List

                    <span class="material-symbols-outlined text-[18px]">
                        arrow_forward
                    </span>

                </a>

            @endif

        @endguest

    </div>

</div>


</section>

</main>


{{-- =============================================================
    SMALL CUSTOM CSS
============================================================= --}}

<style>

    .active-tab {
        color: #000;
        border-bottom: 2px solid currentColor;
        font-weight: 700;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    html {
        scroll-behavior: smooth;
    }

</style>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const filterButtons = document.querySelectorAll('.listing-filter');
    const listingCards = document.querySelectorAll('.listing-card');
    const noFilterResults = document.getElementById('no-filter-results');

    filterButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const selectedFilter = this.dataset.filter;

            let visibleCount = 0;

            // Update active button
            filterButtons.forEach(function (btn) {

                btn.classList.remove(
                    'bg-black',
                    'text-white',
                    'font-semibold'
                );

                btn.classList.add(
                    'border',
                    'border-gray-200',
                    'bg-white',
                    'text-gray-600',
                    'font-medium'
                );
            });

            this.classList.remove(
                'border',
                'border-gray-200',
                'bg-white',
                'text-gray-600',
                'font-medium'
            );

            this.classList.add(
                'bg-black',
                'text-white',
                'font-semibold'
            );

            // Filter listings
            listingCards.forEach(function (card) {

                const cardCategory = card.dataset.category;

                if (
                    selectedFilter === 'all' ||
                    cardCategory === selectedFilter
                ) {

                    card.classList.remove('hidden');

                    visibleCount++;

                } else {

                    card.classList.add('hidden');

                }

            });

            // Show / hide no-results message
            if (noFilterResults) {

                if (visibleCount === 0) {
                    noFilterResults.classList.remove('hidden');
                } else {
                    noFilterResults.classList.add('hidden');
                }

            }

        });

    });

});
</script>

@endsection