@extends('layouts.base')

@section('title', 'Search Results')

@section('content')

<main class="pt-24 pb-20">

    <section class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-10">

        {{-- Header --}}
        <div class="mb-8">

            <span class="text-sm uppercase tracking-[0.2em] text-primary">
                Marketplace
            </span>

            <h1 class="text-3xl md:text-4xl font-bold text-primary mt-2">
                Search Results
            </h1>

            @if(request('search'))

                <p class="text-gray-500 mt-2">
                    Results for
                    <span class="font-semibold text-gray-700">
                        "{{ request('search') }}"
                    </span>
                </p>

            @else

                <p class="text-gray-500 mt-2">
                    Explore available marketplace listings.
                </p>

            @endif

        </div>


        {{-- Back --}}
        <div class="mb-6">

            <a
                href="{{ route('listing.index') }}"
                class="inline-flex items-center gap-2 text-primary
                       hover:underline"
            >

                <span class="material-symbols-outlined text-[18px]">
                    arrow_back
                </span>

                Back to All Listings

            </a>

        </div>


        {{-- Results --}}
        @if($listings->count())

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach($listings as $listing)

                    <article
                        class="bg-white rounded-2xl border border-gray-200
                               overflow-hidden hover:shadow-xl
                               transition"
                    >

                        {{-- Image --}}
                        <div class="relative h-52 bg-gray-100">

                            @if($listing->image)

                                @if(filter_var($listing->image, FILTER_VALIDATE_URL))

                                    <img
                                        src="{{ $listing->image }}"
                                        alt="{{ $listing->title }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >

                                @else

                                    <img
                                        src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                                        alt="{{ $listing->title }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >

                                @endif

                            @else

                                <div class="w-full h-full flex items-center justify-center">

                                    <span class="material-symbols-outlined text-5xl text-gray-300">
                                        image
                                    </span>

                                </div>

                            @endif


                            <span
                                class="absolute top-3 left-3
                                       bg-white/95 text-primary
                                       px-3 py-1.5 rounded-full
                                       text-xs font-semibold"
                            >
                                {{ $listing->categoryRelation?->name ?? 'Listing' }}
                            </span>

                        </div>


                        {{-- Content --}}
                        <div class="p-5">

                            <h2 class="text-lg font-semibold text-primary line-clamp-2">
                                {{ $listing->title }}
                            </h2>

                            <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                {{ $listing->description }}
                            </p>


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


                            <div class="border-t border-gray-100 mt-5 pt-4
                                        flex items-center justify-between gap-3">

                                <div>

                                    @if($listing->price)

                                        <span class="text-lg font-bold text-primary">
                                            ₹{{ number_format($listing->price) }}
                                        </span>

                                    @else

                                        <span class="text-lg font-bold text-primary">
                                            Get Quote
                                        </span>

                                    @endif

                                </div>


                                <a
                                    href="{{ route('listing.show', ['id' => $listing->id]) }}"
                                    class="px-4 py-2.5 rounded-lg
                                           bg-primary text-white
                                           text-sm font-semibold
                                           hover:bg-primary/90"
                                >
                                    View
                                </a>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>


            <div class="mt-10">
                {{ $listings->withQueryString()->links() }}
            </div>

        @else

            <div class="text-center py-20">

                <span class="material-symbols-outlined text-6xl text-gray-300">
                    search_off
                </span>

                <h2 class="text-2xl font-semibold text-primary mt-4">
                    No listings found
                </h2>

                <p class="text-gray-500 mt-2">
                    Try another search term or browse all listings.
                </p>

                <a
                    href="{{ route('listing.index') }}"
                    class="inline-block mt-6 px-6 py-3
                           rounded-lg bg-primary text-white"
                >
                    View All Listings
                </a>

            </div>

        @endif

    </section>

</main>

@endsection