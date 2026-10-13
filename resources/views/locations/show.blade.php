@extends('layouts.base')

@section('title', $location->name . ' Listings')

@section('content')

<main class="w-full bg-[#F7FAFA] min-h-screen ">

    {{-- Location Header --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-10">

            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
                <a
                    href="{{ route('locations.index') }}"
                    class="hover:text-[#056663]">
                    Locations
                </a>

                <span>/</span>

                <span class="text-gray-700">
                    {{ $location->name }}
                </span>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mt-20">

                <div>

                    <p class="text-sm font-semibold text-[#056663] uppercase tracking-wider mb-2">
                        Explore Location
                    </p>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                        {{ $location->name }}
                    </h1>

                    @if($location->state)
                    <p class="mt-2 text-gray-500">
                        {{ $location->state }}
                    </p>
                    @endif

                    @if($location->description)
                    <p class="mt-4 text-gray-600 max-w-2xl">
                        {{ $location->description }}
                    </p>
                    @endif

                </div>

                {{-- Listing Count --}}
                <div class="bg-[#E8F5F4] rounded-xl px-6 py-4 min-w-[150px]">

                    <p class="text-sm text-gray-600">
                        Available Listings
                    </p>

                    <p class="text-2xl font-bold text-[#056663]">
                        {{ $listings->total() }}
                    </p>

                </div>

            </div>

        </div>
    </section>


    {{-- Listings --}}
    <section class="max-w-7xl mx-auto px-6 py-10">

        <div class="flex items-center justify-between mb-6">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Listings in {{ $location->name }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Properties, land, products, services and projects available in this location.
                </p>
            </div>

        </div>


        @if($listings->count())

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @foreach($listings as $listing)

            <div
                class="bg-white rounded-2xl border border-gray-200 overflow-hidden
                               hover:border-[#056663] hover:shadow-lg transition-all duration-300">

              {{-- Image --}}
<div class="h-48 bg-[#E8F5F4] overflow-hidden">

    @if($listing->image)

        @if(filter_var($listing->image, FILTER_VALIDATE_URL))

            {{-- External Image --}}
            <img
                src="{{ $listing->image }}"
                alt="{{ $listing->title }}"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
            >

        @else

            {{-- Local Uploaded Image --}}
            <img
                src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                alt="{{ $listing->title }}"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
            >

        @endif

    @else

        <div class="w-full h-full flex items-center justify-center">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-14 h-14 text-[#056663]"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.5">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h1m4 0h1M9 11h1m4 0h1M9 15h1m4 0h1" />

            </svg>

        </div>

    @endif

</div>
                {{-- Listing Content --}}
                <div class="p-5">

                    {{-- Category --}}
                    @if($listing->categoryRelation)

                    <span
                        class="inline-flex px-3 py-1 rounded-full
                                           bg-[#E8F5F4] text-[#056663]
                                           text-xs font-semibold">
                        {{ $listing->categoryRelation->name }}
                    </span>

                    @endif


                    {{-- Title --}}
                    <h3
                        class="mt-3 text-lg font-bold text-gray-900 line-clamp-2">
                        {{ $listing->title }}
                    </h3>


                    {{-- Location --}}
                    @if($listing->city)

                    <div class="flex items-center gap-2 mt-3 text-sm text-gray-500">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-[#056663]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 21s8-4.35 8-10a8 8 0 10-16 0c0 5.65 8 10 8 10z" />
                            <circle
                                cx="12"
                                cy="11"
                                r="2.5" />
                        </svg>

                        {{ $listing->city }}

                    </div>

                    @endif


                    {{-- Price --}}
                    @if($listing->price)

                    <div class="mt-4">

                        <p class="text-lg font-bold text-[#056663]">
                            ₹{{ number_format($listing->price) }}
                        </p>

                    </div>

                    @endif


                    {{-- View Button --}}
                    <div class="mt-5">

                        <a
                            href="{{ url('/listings/' . $listing->id) }}"
                            class="inline-flex items-center justify-center
                                           w-full px-4 py-2.5 rounded-lg
                                           bg-[#056663] text-white
                                           text-sm font-semibold
                                           hover:bg-[#04524F]
                                           transition-colors">
                            View Listing
                        </a>

                    </div>

                </div>

            </div>

            @endforeach

        </div>


        {{-- Pagination --}}
        @if($listings->hasPages())

        <div class="mt-10">
            {{ $listings->links() }}
        </div>

        @endif


        @else

        {{-- No Listings --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center">

            <div class="flex justify-center mb-4">

                <div
                    class="w-16 h-16 rounded-full bg-[#E8F5F4]
                               flex items-center justify-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8 text-[#056663]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h1m4 0h1M9 11h1m4 0h1M9 15h1m4 0h1" />
                    </svg>
                </div>

            </div>

            <h2 class="text-xl font-bold text-gray-900">
                No listings found
            </h2>

            <p class="text-gray-500 mt-2">
                There are currently no approved listings available in
                {{ $location->name }}.
            </p>

            <a
                href="{{ route('locations.index') }}"
                class="inline-flex mt-6 px-5 py-2.5 rounded-lg
                           bg-[#056663] text-white
                           font-semibold hover:bg-[#04524F]">
                Browse Other Locations
            </a>

        </div>

        @endif

    </section>

</main>

@endsection