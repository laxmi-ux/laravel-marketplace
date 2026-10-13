<x-buyer-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Saved Listings
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    View and manage the listings you saved for later.
                </p>
            </div>

            <a
                href="{{ route('listings.index') }}"
                class="inline-flex w-full sm:w-auto
                       items-center justify-center gap-2
                       rounded-lg
                       bg-[#056663]
                       px-5 py-2.5
                       text-sm font-medium
                       text-white
                       shadow-sm
                       transition
                       hover:bg-[#045450]"
            >

                <span class="material-symbols-outlined text-[19px]">
                    search
                </span>

                Browse Listings

            </a>

        </div>

    </x-slot>


    {{-- =========================================================
         PAGE
    ========================================================== --}}

    <div class="min-h-screen bg-[#F7FAFA] py-6 sm:py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- =================================================
                 SUCCESS MESSAGE
            ================================================== --}}

            @if(session('success'))

                <div
                    class="mb-6 flex items-start gap-3
                           rounded-xl
                           border border-green-200
                           bg-green-50
                           px-4 py-4
                           text-green-700
                           shadow-sm"
                >

                    <span class="material-symbols-outlined text-[21px]">
                        check_circle
                    </span>

                    <div>
                        <p class="text-sm font-semibold">
                            Success
                        </p>

                        <p class="mt-0.5 text-sm">
                            {{ session('success') }}
                        </p>
                    </div>

                </div>

            @endif


            {{-- =================================================
                 ERROR MESSAGE
            ================================================== --}}

            @if(session('error'))

                <div
                    class="mb-6 flex items-start gap-3
                           rounded-xl
                           border border-red-200
                           bg-red-50
                           px-4 py-4
                           text-red-700
                           shadow-sm"
                >

                    <span class="material-symbols-outlined text-[21px]">
                        error
                    </span>

                    <div>
                        <p class="text-sm font-semibold">
                            Error
                        </p>

                        <p class="mt-0.5 text-sm">
                            {{ session('error') }}
                        </p>
                    </div>

                </div>

            @endif


            {{-- =================================================
                 PAGE SUMMARY
            ================================================== --}}

            @if($savedListings->count())

                <div
                    class="mb-6 rounded-xl
                           border border-[#DDE9E8]
                           bg-white
                           px-5 py-4
                           shadow-sm"
                >

                    <div class="flex items-center justify-between gap-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0
                                       items-center justify-center
                                       rounded-lg
                                       bg-[#E8F5F4]"
                            >

                                <span
                                    class="material-symbols-outlined
                                           text-[#056663]"
                                >
                                    bookmark
                                </span>

                            </div>

                            <div>

                                <p class="text-sm font-semibold text-gray-800">
                                    Your Saved Listings
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $savedListings->total() }}
                                    {{ $savedListings->total() == 1 ? 'listing' : 'listings' }}
                                    saved
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     LISTING GRID
                ================================================== --}}

                <div
                    class="grid grid-cols-1 gap-5
                           sm:grid-cols-2
                           xl:grid-cols-3"
                >

                    @foreach($savedListings as $saved)

                        @php
                            $listing = $saved->listing;
                        @endphp


                        @if($listing)

                            {{-- =====================================
                                 LISTING CARD
                            ====================================== --}}

                            <article
                                class="group flex h-full flex-col
                                       overflow-hidden
                                       rounded-2xl
                                       border border-[#DDE9E8]
                                       bg-white
                                       shadow-sm
                                       transition duration-200
                                       hover:-translate-y-1
                                       hover:border-[#056663]
                                       hover:shadow-lg"
                            >


                                {{-- =================================
                                     IMAGE
                                ================================== --}}

                                <div
                                    class="relative h-52
                                           overflow-hidden
                                           bg-gray-100"
                                >

                                    @if($listing->image)

                                        @if(filter_var($listing->image, FILTER_VALIDATE_URL))

                                            <img
                                                src="{{ $listing->image }}"
                                                alt="{{ $listing->title }}"
                                                class="h-full w-full
                                                       object-cover
                                                       transition duration-300
                                                       group-hover:scale-105"
                                            >

                                        @else

                                            <img
                                                src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                                                alt="{{ $listing->title }}"
                                                class="h-full w-full
                                                       object-cover
                                                       transition duration-300
                                                       group-hover:scale-105"
                                            >

                                        @endif

                                    @else

                                        <div
                                            class="flex h-full w-full
                                                   items-center justify-center
                                                   bg-[#F1F6F6]"
                                        >

                                            <span
                                                class="material-symbols-outlined
                                                       text-6xl
                                                       text-[#B8CECC]"
                                            >
                                                image
                                            </span>

                                        </div>

                                    @endif


                                    {{-- Saved Badge --}}

                                    <div
                                        class="absolute right-4 top-4
                                               flex h-9 w-9
                                               items-center justify-center
                                               rounded-full
                                               bg-white/95
                                               shadow-sm"
                                    >

                                        <span
                                            class="material-symbols-outlined
                                                   text-[20px]
                                                   text-[#056663]"
                                        >
                                            bookmark
                                        </span>

                                    </div>


                                    {{-- Category Badge --}}

                                    <div
                                        class="absolute bottom-4 left-4
                                               rounded-full
                                               bg-white/95
                                               px-3 py-1.5
                                               text-xs font-semibold
                                               text-[#056663]
                                               shadow-sm"
                                    >

                                        {{ $listing->categoryRelation?->name ?? 'Listing' }}

                                    </div>

                                </div>


                                {{-- =================================
                                     CARD CONTENT
                                ================================== --}}

                                <div class="flex flex-1 flex-col p-5">


                                    {{-- Title --}}

                                    <h3
                                        class="line-clamp-2
                                               text-lg font-semibold
                                               leading-6
                                               text-gray-900
                                               transition
                                               group-hover:text-[#056663]"
                                    >

                                        {{ $listing->title ?? 'Untitled Listing' }}

                                    </h3>


                                    {{-- Description --}}

                                    @if(!empty($listing->description))

                                        <p
                                            class="mt-2 line-clamp-2
                                                   text-sm leading-5
                                                   text-gray-500"
                                        >

                                            {{ \Illuminate\Support\Str::limit($listing->description, 120) }}

                                        </p>

                                    @else

                                        <p class="mt-2 text-sm text-gray-400">
                                            No description available.
                                        </p>

                                    @endif


                                    {{-- =================================
                                         PRICE
                                    ================================== --}}

                                    <div class="mt-4">

                                        @if($listing->price)

                                            <p class="text-xl font-bold text-[#056663]">

                                                ₹{{ number_format($listing->price) }}

                                                @if($listing->price_unit)

                                                    <span
                                                        class="text-xs
                                                               font-normal
                                                               text-gray-500"
                                                    >
                                                        /
                                                        {{ str_replace('per ', '', $listing->price_unit) }}
                                                    </span>

                                                @endif

                                            </p>

                                        @else

                                            <p class="text-lg font-semibold text-[#056663]">
                                                Get Quote
                                            </p>

                                        @endif

                                    </div>


                                    {{-- =================================
                                         LOCATION
                                    ================================== --}}

                                    @if($listing->city || $listing->state)

                                        <div
                                            class="mt-3 flex items-center gap-2
                                                   text-sm text-gray-500"
                                        >

                                            <span
                                                class="material-symbols-outlined
                                                       text-[18px]
                                                       text-[#056663]"
                                            >
                                                location_on
                                            </span>

                                            <span class="truncate">

                                                @if($listing->city)
                                                    {{ $listing->city }}
                                                @endif

                                                @if($listing->state)
                                                    @if($listing->city), @endif
                                                    {{ $listing->state }}
                                                @endif

                                            </span>

                                        </div>

                                    @endif


                                    {{-- =================================
                                         SAVED DATE
                                    ================================== --}}

                                    <div
                                        class="mt-3 flex items-center gap-2
                                               text-xs text-gray-400"
                                    >

                                        <span class="material-symbols-outlined text-[16px]">
                                            schedule
                                        </span>

                                        Saved
                                        {{ optional($saved->created_at)->format('d M Y') }}

                                    </div>


                                    {{-- =================================
                                         ACTIONS
                                    ================================== --}}

                                    <div
                                        class="mt-auto flex flex-col
                                               gap-3 pt-5
                                               sm:flex-row"
                                    >

                                        {{-- View Listing --}}

                                        <a
                                            href="{{ route('listing.show', $listing->id) }}"
                                            class="inline-flex flex-1
                                                   items-center
                                                   justify-center
                                                   gap-2
                                                   rounded-lg
                                                   bg-[#056663]
                                                   px-4 py-2.5
                                                   text-sm font-semibold
                                                   text-white
                                                   transition
                                                   hover:bg-[#045450]"
                                        >

                                            <span
                                                class="material-symbols-outlined
                                                       text-[18px]"
                                            >
                                                visibility
                                            </span>

                                            View Listing

                                        </a>


                                        {{-- Remove --}}

                                        <form
                                            action="{{ route('buyer.saved-listings.destroy', $listing->id) }}"
                                            method="POST"
                                            class="sm:w-auto"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex w-full
                                                       items-center
                                                       justify-center
                                                       gap-2
                                                       rounded-lg
                                                       border
                                                       border-red-200
                                                       bg-white
                                                       px-4 py-2.5
                                                       text-sm font-semibold
                                                       text-red-600
                                                       transition
                                                       hover:bg-red-50
                                                       sm:w-auto"
                                            >

                                                <span
                                                    class="material-symbols-outlined
                                                           text-[18px]"
                                                >
                                                    delete
                                                </span>

                                                Remove

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </article>

                        @endif

                    @endforeach

                </div>


                {{-- =================================================
                     PAGINATION
                ================================================== --}}

                @if($savedListings->hasPages())

                    <div class="mt-8">

                        {{ $savedListings->links() }}

                    </div>

                @endif


            @else

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}

                <div
                    class="rounded-2xl
                           border border-[#DDE9E8]
                           bg-white
                           px-6 py-16
                           text-center
                           shadow-sm
                           sm:px-10"
                >

                    <div
                        class="mx-auto flex h-20 w-20
                               items-center justify-center
                               rounded-full
                               bg-[#E8F5F4]"
                    >

                        <span
                            class="material-symbols-outlined
                                   text-4xl
                                   text-[#056663]"
                        >
                            bookmark_border
                        </span>

                    </div>


                    <h3
                        class="mt-6 text-xl
                               font-semibold
                               text-gray-900"
                    >
                        No Saved Listings Yet
                    </h3>


                    <p
                        class="mx-auto mt-2 max-w-md
                               text-sm leading-6
                               text-gray-500"
                    >
                        Save interesting products, properties,
                        land, services or projects and easily
                        find them here later.
                    </p>


                    <a
                        href="{{ route('listings.index') }}"
                        class="mt-6 inline-flex
                               items-center
                               justify-center
                               gap-2
                               rounded-lg
                               bg-[#056663]
                               px-6 py-3
                               text-sm font-semibold
                               text-white
                               shadow-sm
                               transition
                               hover:bg-[#045450]"
                    >

                        <span class="material-symbols-outlined text-[19px]">
                            search
                        </span>

                        Browse Listings

                    </a>

                </div>

            @endif

        </div>

    </div>

</x-buyer-layout>