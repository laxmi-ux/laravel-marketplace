@extends('layouts.base')

@section('title', 'All Listings')

@section('content')

<main class="pt-24 pb-20">

    <section class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-10">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="mb-8">

            <span class="text-sm uppercase tracking-[0.2em] text-primary">
                Marketplace
            </span>

            <h1 class="text-3xl md:text-4xl font-bold text-primary mt-2">
                All Listings
            </h1>

            <p class="text-gray-500 mt-2">
                Explore properties, land, products, services and projects.
            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTERS --}}
        {{-- ========================================================= --}}

        <form
            action="{{ route('listing.index') }}"
            method="GET"
            class="bg-white border border-gray-200 rounded-2xl p-5 mb-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Search --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search listings..."
                        class="w-full rounded-lg border border-gray-200 px-4 py-3
                               focus:ring-2 focus:ring-primary focus:outline-none">

                </div>


                {{-- City --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ request('city') }}"
                        placeholder="City"
                        class="w-full rounded-lg border border-gray-200 px-4 py-3
                               focus:ring-2 focus:ring-primary focus:outline-none">

                </div>


                {{-- Category --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>

                    <select
                        name="category"
                        class="w-full rounded-lg border border-gray-200 px-4 py-3
                               focus:ring-2 focus:ring-primary focus:outline-none">

                        <option value="">
                            All Categories
                        </option>

                        <option
                            value="products"
                            {{ request('category') == 'products' ? 'selected' : '' }}>
                            Products
                        </option>

                        <option
                            value="properties"
                            {{ request('category') == 'properties' ? 'selected' : '' }}>
                            Properties
                        </option>

                        <option
                            value="land"
                            {{ request('category') == 'land' ? 'selected' : '' }}>
                            Land
                        </option>

                        <option
                            value="services"
                            {{ request('category') == 'services' ? 'selected' : '' }}>
                            Services
                        </option>

                        <option
                            value="projects"
                            {{ request('category') == 'projects' ? 'selected' : '' }}>
                            Projects
                        </option>

                    </select>

                </div>


                {{-- Listing Type --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Listing Type
                    </label>

                    <select
                        name="type"
                        class="w-full rounded-lg border border-gray-200 px-4 py-3
                               focus:ring-2 focus:ring-primary focus:outline-none">

                        <option value="">
                            All Types
                        </option>

                        <option
                            value="sale"
                            {{ request('type') == 'sale' ? 'selected' : '' }}>
                            Sale
                        </option>

                        <option
                            value="rent"
                            {{ request('type') == 'rent' ? 'selected' : '' }}>
                            Rent
                        </option>

                        <option
                            value="lease"
                            {{ request('type') == 'lease' ? 'selected' : '' }}>
                            Lease
                        </option>

                    </select>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="flex justify-end gap-3 mt-4">

                <a
                    href="{{ route('listing.index') }}"
                    class="px-5 py-3 rounded-lg border border-gray-200
                           text-gray-600 hover:bg-gray-50 transition">
                    Clear
                </a>

                <button
                    type="submit"
                    class="px-6 py-3 rounded-lg bg-primary text-white
                           hover:bg-primary/90 transition">
                    Apply Filters
                </button>

            </div>

        </form>


        {{-- ========================================================= --}}
        {{-- RESULT COUNT --}}
        {{-- ========================================================= --}}

        <div class="flex items-center justify-between mb-6">

            <p class="text-gray-500 text-sm">

                Showing

                <span class="font-semibold text-gray-900">
                    {{ $listings->firstItem() ?? 0 }}
                </span>

                -

                <span class="font-semibold text-gray-900">
                    {{ $listings->lastItem() ?? 0 }}
                </span>

                of

                <span class="font-semibold text-gray-900">
                    {{ $listings->total() }}
                </span>

                listings

            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- LISTINGS --}}
        {{-- ========================================================= --}}

        @if($listings->count())

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @foreach($listings as $listing)

            <article
                class="bg-white rounded-2xl border border-gray-200 overflow-hidden
                               hover:shadow-xl hover:-translate-y-1
                               transition-all duration-300">

                {{-- Image --}}
                <div class="relative h-52 bg-gray-100">

                    @if($listing->image)

                    @if(filter_var($listing->image, FILTER_VALIDATE_URL))

                    <img
                        src="{{ $listing->image }}"
                        alt="{{ $listing->title }}"
                        class="w-full h-full object-cover"
                        loading="lazy">

                    @else

                    <img
                        src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                        alt="{{ $listing->title }}"
                        class="w-full h-full object-cover"
                        loading="lazy">

                    @endif

                    @else

                    <div class="w-full h-full flex items-center justify-center">

                        <span class="material-symbols-outlined text-5xl text-gray-300">
                            image
                        </span>

                    </div>

                    @endif


                    {{-- Category --}}
                    <span
                        class="absolute top-3 left-3
                                       px-3 py-1.5 rounded-full
                                       bg-white/95 text-primary
                                       text-xs font-semibold">
                        {{ $listing->categoryRelation?->name ?? 'Listing' }}
                    </span>


                    {{-- Verified --}}
                    @if($listing->is_verified)

                    <span
                        class="absolute top-3 right-3
                                           w-9 h-9 rounded-full
                                           bg-white/95
                                           flex items-center justify-center
                                           text-primary"
                        title="Verified">

                        <span class="material-symbols-outlined text-[18px]">
                            verified
                        </span>

                    </span>

                    @endif

                </div>


                {{-- Content --}}
                <div class="p-5">

                    <h2 class="text-lg font-semibold text-primary line-clamp-2">
                        {{ $listing->title }}
                    </h2>


                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                        {{ $listing->description }}
                    </p>


                    {{-- Location --}}
                    @if($listing->city)

                    <div class="flex items-center gap-2 mt-4 text-sm text-gray-500">

                        <span class="material-symbols-outlined text-[18px]">
                            location_on
                        </span>

                        <span>

                            {{ $listing->city }}

                            @if($listing->state)
                            , {{ $listing->state }}
                            @endif

                        </span>

                    </div>

                    @endif


                    {{-- Seller --}}
                    @if($listing->seller_name)

                    <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">

                        <span class="material-symbols-outlined text-[18px]">
                            store
                        </span>

                        <span class="truncate">
                            {{ $listing->seller_name }}
                        </span>

                    </div>

                    @endif


                    {{-- Bottom --}}
                    <div
                        class="border-t border-gray-100 mt-5 pt-4
                                       flex items-end justify-between gap-3">

                        <div>

                            @if($listing->price)

                            <span class="text-xs text-gray-400 block">

                                {{ $listing->listing_type === 'lease'
                                                ? 'Lease from'
                                                : 'Starting from'
                                            }}

                            </span>

                            <span class="text-lg font-bold text-primary">
                                ₹{{ number_format($listing->price) }}
                            </span>

                            @if($listing->price_unit)

                            <span class="text-xs text-gray-400">
                                /
                                {{ str_replace('per ', '', $listing->price_unit) }}
                            </span>

                            @endif

                            @else

                            <span class="text-xs text-gray-400 block">
                                Pricing
                            </span>

                            <span class="text-lg font-bold text-primary">
                                Get Quote
                            </span>

                            @endif

                        </div>


                        {{-- Actions --}}
                        <div class="flex items-center gap-2">

                            {{-- Compare --}}
                            @auth
                            <form
                                action="{{ route('buyer.compare.store', $listing->id) }}"
                                method="POST">
                                @csrf

                                <button
                                    type="submit"
                                    title="Add to Compare"
                                    class="inline-flex items-center justify-center
                       w-10 h-10
                       rounded-lg
                       border border-gray-200
                       bg-white
                       text-primary
                       hover:bg-[#E8F5F4]
                       hover:border-primary
                       transition">
                                    <span class="material-symbols-outlined text-[19px]">
                                        compare_arrows
                                    </span>
                                </button>
                            </form>
                            @endauth


                            {{-- View --}}
                            <a
                                href="{{ route('listing.show', ['id' => $listing->id]) }}"
                                class="inline-flex items-center gap-1
               px-4 py-2.5
               rounded-lg
               bg-primary
               text-white
               text-sm font-semibold
               hover:bg-primary/90
               transition">
                                View

                                <span class="material-symbols-outlined text-[16px]">
                                    arrow_forward
                                </span>
                            </a>

                        </div>

                    </div>

                </div>

            </article>

            @endforeach

        </div>


        {{-- Pagination --}}
        <div class="mt-10">
            {{ $listings->withQueryString()->links() }}
        </div>

        @else

        {{-- Empty State --}}
        <div class="text-center py-20">

            <span class="material-symbols-outlined text-6xl text-gray-300">
                inventory_2
            </span>

            <h2 class="text-2xl font-semibold text-primary mt-4">
                No listings found
            </h2>

            <p class="text-gray-500 mt-2">
                Try changing your search or filters.
            </p>

            <a
                href="{{ route('listing.index') }}"
                class="inline-block mt-6 px-6 py-3
                           rounded-lg bg-primary text-white">
                View All Listings
            </a>

        </div>

        @endif

    </section>

</main>

@endsection