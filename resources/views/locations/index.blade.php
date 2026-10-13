@extends('layouts.base')

@section('title', 'Locations')

@section('content')

<main class="w-full bg-[#F7FAFA] min-h-screen">

    {{-- Header --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-10">

            <div class="max-w-3xl">
                <p class="text-sm font-semibold text-[#056663] uppercase tracking-wider mb-2">
                    Explore Locations
                </p>

                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">
                    Find Listings by Location
                </h1>

                <p class="mt-3 text-gray-600">
                    Explore properties, land, products, services and projects
                    available in different locations.
                </p>
            </div>

        </div>
    </section>


    {{-- Locations --}}
    <section class="max-w-7xl mx-auto px-6 py-10">

        @if($locations->count())

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach($locations as $location)

                    <a
                        href="{{ route('locations.show', $location->slug) }}"
                        class="group bg-white rounded-2xl border border-gray-200 overflow-hidden
                               hover:border-[#056663] hover:shadow-lg transition-all duration-300"
                    >

                        {{-- Image --}}
                        <div class="h-48 bg-[#E8F5F4] overflow-hidden">

                            @if($location->image)

                                <img
                                    src="{{ route('uploads.file', ['path' => 'locations/' . $location->image]) }}"
                                    alt="{{ $location->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >

                            @else

                                <div class="w-full h-full flex items-center justify-center">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-14 h-14 text-[#056663]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 21s8-4.35 8-10a8 8 0 10-16 0c0 5.65 8 10 8 10z"
                                        />
                                        <circle
                                            cx="12"
                                            cy="11"
                                            r="2.5"
                                        />
                                    </svg>

                                </div>

                            @endif

                        </div>


                        {{-- Content --}}
                        <div class="p-5">

                            <div class="flex items-start justify-between gap-3">

                                <div>

                                    <h2 class="text-lg font-bold text-gray-900
                                               group-hover:text-[#056663] transition-colors">
                                        {{ $location->name }}
                                    </h2>

                                    @if($location->state)
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $location->state }}
                                        </p>
                                    @endif

                                </div>

                                <span
                                    class="w-9 h-9 rounded-full bg-[#E8F5F4]
                                           flex items-center justify-center
                                           text-[#056663] group-hover:bg-[#056663]
                                           group-hover:text-white transition-colors"
                                >
                                    →
                                </span>

                            </div>

                            @if($location->description)

                                <p class="text-sm text-gray-600 mt-4 line-clamp-2">
                                    {{ $location->description }}
                                </p>

                            @endif

                            <div class="mt-5 text-sm font-semibold text-[#056663]">
                                View Listings →
                            </div>

                        </div>

                    </a>

                @endforeach

            </div>

        @else

            <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">

                <div class="text-gray-400 mb-3">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-12 h-12 mx-auto"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 21s8-4.35 8-10a8 8 0 10-16 0c0 5.65 8 10 8 10z"
                        />
                        <circle cx="12" cy="11" r="2.5" />
                    </svg>
                </div>

                <h2 class="text-xl font-semibold text-gray-900">
                    No locations available
                </h2>

                <p class="text-gray-500 mt-2">
                    Locations will appear here once they are added by the administrator.
                </p>

            </div>

        @endif

    </section>

</main>

@endsection