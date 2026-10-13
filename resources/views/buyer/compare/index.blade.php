<x-buyer-layout>

    <x-slot name="header">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#E8F5F4]">
                    <span class="material-symbols-outlined text-[23px] text-[#056663]">
                        compare_arrows
                    </span>
                </div>

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                        Compare Listings
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Compare selected listings side by side.
                    </p>
                </div>

            </div>

            <a
                href="{{ route('listing.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       bg-[#056663] px-5 py-2.5 text-sm font-semibold
                       text-white shadow-sm transition hover:bg-[#045450]"
            >
                <span class="material-symbols-outlined text-[19px]">
                    add
                </span>

                Add Listings
            </a>

        </div>

    </x-slot>


    {{-- Main --}}
    <div class="min-h-screen bg-[#F7FAFA] py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Success --}}
            @if(session('success'))

                <div class="mb-6 flex items-start gap-3 rounded-xl
                            border border-green-200 bg-green-50
                            px-4 py-4 text-green-700">

                    <span class="material-symbols-outlined">
                        check_circle
                    </span>

                    <div>
                        <p class="text-sm font-semibold">
                            Success
                        </p>

                        <p class="mt-1 text-sm">
                            {{ session('success') }}
                        </p>
                    </div>

                </div>

            @endif


            {{-- Error --}}
            @if(session('error'))

                <div class="mb-6 flex items-start gap-3 rounded-xl
                            border border-red-200 bg-red-50
                            px-4 py-4 text-red-700">

                    <span class="material-symbols-outlined">
                        error
                    </span>

                    <div>
                        <p class="text-sm font-semibold">
                            Unable to complete action
                        </p>

                        <p class="mt-1 text-sm">
                            {{ session('error') }}
                        </p>
                    </div>

                </div>

            @endif


            @if($compareListings->count())


                {{-- Top Summary --}}
                <div class="mb-6 grid gap-4 sm:grid-cols-3">

                    {{-- Selected --}}
                    <div class="rounded-2xl border border-[#DDE9E8]
                                bg-white p-5 shadow-sm">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center
                                        rounded-xl bg-[#E8F5F4]">

                                <span class="material-symbols-outlined text-[#056663]">
                                    checklist
                                </span>

                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Selected
                                </p>

                                <p class="mt-1 text-xl font-bold text-gray-900">
                                    {{ $compareListings->count() }}
                                    <span class="text-sm font-medium text-gray-500">
                                        {{ $compareListings->count() == 1 ? 'Listing' : 'Listings' }}
                                    </span>
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Compare --}}
                    <div class="rounded-2xl border border-[#DDE9E8]
                                bg-white p-5 shadow-sm">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center
                                        rounded-xl bg-[#E8F5F4]">

                                <span class="material-symbols-outlined text-[#056663]">
                                    compare_arrows
                                </span>

                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    Comparison
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    Side-by-side details
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Add --}}
                    <div class="flex items-center justify-between rounded-2xl
                                border border-[#DDE9E8] bg-white p-5 shadow-sm">

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Need another option?
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                Add more listings
                            </p>
                        </div>

                        <a
                            href="{{ route('listing.index') }}"
                            class="flex h-10 w-10 items-center justify-center
                                   rounded-xl bg-[#056663] text-white
                                   transition hover:bg-[#045450]"
                        >
                            <span class="material-symbols-outlined">
                                add
                            </span>
                        </a>

                    </div>

                </div>


                {{-- Comparison Wrapper --}}
                <div class="overflow-hidden rounded-2xl border
                            border-[#DDE9E8] bg-white shadow-sm">

                    <div class="overflow-x-auto">

                        <div class="min-w-[900px]">


                            {{-- ================================================= --}}
                            {{-- LISTING HEADER --}}
                            {{-- ================================================= --}}

                            <div class="grid grid-cols-[180px_repeat(auto-fit,minmax(240px,1fr))]
                                        border-b border-[#DDE9E8]">

                                {{-- Details column --}}
                                <div class="bg-[#F7FAFA] p-5">

                                    <p class="text-xs font-semibold uppercase
                                              tracking-wider text-gray-400">
                                        Compare
                                    </p>

                                    <p class="mt-1 text-sm font-bold text-gray-800">
                                        Listing Details
                                    </p>

                                </div>


                                @foreach($compareListings as $compare)

                                    @php
                                        $listing = $compare->listing;
                                    @endphp

                                    <div class="border-l border-[#DDE9E8] p-4">

                                        @if($listing)

                                            {{-- Image --}}
                                            <div class="relative h-44 overflow-hidden rounded-xl bg-[#F1F6F6]">

                                                @if($listing->image)

                                                    @if(filter_var($listing->image, FILTER_VALIDATE_URL))

                                                        <img
                                                            src="{{ $listing->image }}"
                                                            alt="{{ $listing->title }}"
                                                            class="h-full w-full object-cover"
                                                        >

                                                    @else

                                                        <img
                                                            src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                                                            alt="{{ $listing->title }}"
                                                            class="h-full w-full object-cover"
                                                        >

                                                    @endif

                                                @else

                                                    <div class="flex h-full items-center justify-center">

                                                        <span class="material-symbols-outlined
                                                                     text-5xl text-[#B8CECC]">
                                                            image
                                                        </span>

                                                    </div>

                                                @endif


                                                {{-- Category --}}
                                                @if($listing->categoryRelation)

                                                    <span class="absolute left-3 top-3 rounded-full
                                                                 bg-white/95 px-3 py-1
                                                                 text-[11px] font-semibold
                                                                 text-[#056663] shadow-sm">

                                                        {{ $listing->categoryRelation->name }}

                                                    </span>

                                                @endif

                                            </div>


                                            {{-- Title --}}
                                            <h3 class="mt-4 line-clamp-2 text-base
                                                       font-bold text-gray-900">

                                                {{ $listing->title }}

                                            </h3>


                                            {{-- Price --}}
                                            <div class="mt-2">

                                                @if($listing->price)

                                                    <span class="text-lg font-bold text-[#056663]">
                                                        ₹{{ number_format($listing->price) }}
                                                    </span>

                                                    @if($listing->price_unit)

                                                        <span class="text-xs text-gray-400">
                                                            / {{ $listing->price_unit }}
                                                        </span>

                                                    @endif

                                                @else

                                                    <span class="text-sm font-semibold text-[#056663]">
                                                        Get Quote
                                                    </span>

                                                @endif

                                            </div>


                                            {{-- Actions --}}
                                            <div class="mt-4 flex gap-2">

                                                <a
                                                    href="{{ route('listing.show', ['id' => $listing->id]) }}"
                                                    class="flex-1 inline-flex items-center
                                                           justify-center gap-1.5
                                                           rounded-lg bg-[#056663]
                                                           px-3 py-2.5 text-xs
                                                           font-semibold text-white
                                                           hover:bg-[#045450]"
                                                >

                                                    <span class="material-symbols-outlined text-[16px]">
                                                        visibility
                                                    </span>

                                                    View

                                                </a>


                                                <form
                                                    action="{{ route('buyer.compare.destroy', $listing->id) }}"
                                                    method="POST"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        title="Remove from comparison"
                                                        class="flex h-10 w-10 items-center
                                                               justify-center rounded-lg
                                                               border border-red-200
                                                               text-red-500
                                                               hover:bg-red-50"
                                                    >

                                                        <span class="material-symbols-outlined text-[18px]">
                                                            delete
                                                        </span>

                                                    </button>

                                                </form>

                                            </div>

                                        @else

                                            <div class="flex h-full min-h-[250px]
                                                        items-center justify-center">

                                                <p class="text-sm text-gray-400">
                                                    Listing unavailable
                                                </p>

                                            </div>

                                        @endif

                                    </div>

                                @endforeach

                            </div>


                            {{-- ================================================= --}}
                            {{-- COMPARISON INFORMATION --}}
                            {{-- ================================================= --}}

                            <div class="divide-y divide-[#DDE9E8]">


                                {{-- Category --}}
                                <div class="grid grid-cols-[180px_repeat(auto-fit,minmax(240px,1fr))]">

                                    <div class="flex items-center gap-2 bg-[#F7FAFA] p-5">

                                        <span class="material-symbols-outlined text-[#056663]">
                                            category
                                        </span>

                                        <span class="text-sm font-semibold text-gray-700">
                                            Category
                                        </span>

                                    </div>

                                    @foreach($compareListings as $compare)

                                        <div class="border-l border-[#DDE9E8] p-5">

                                            <span class="inline-flex rounded-lg
                                                         bg-[#E8F5F4] px-3 py-1.5
                                                         text-xs font-semibold text-[#056663]">

                                                {{ $compare->listing?->categoryRelation?->name ?? '—' }}

                                            </span>

                                        </div>

                                    @endforeach

                                </div>


                                {{-- Listing Type --}}
                                <div class="grid grid-cols-[180px_repeat(auto-fit,minmax(240px,1fr))]">

                                    <div class="flex items-center gap-2 bg-[#F7FAFA] p-5">

                                        <span class="material-symbols-outlined text-[#056663]">
                                            sell
                                        </span>

                                        <span class="text-sm font-semibold text-gray-700">
                                            Listing Type
                                        </span>

                                    </div>

                                    @foreach($compareListings as $compare)

                                        <div class="border-l border-[#DDE9E8] p-5 text-sm text-gray-700">

                                            {{ $compare->listing?->listing_type ?? '—' }}

                                        </div>

                                    @endforeach

                                </div>


                                {{-- Location --}}
                                <div class="grid grid-cols-[180px_repeat(auto-fit,minmax(240px,1fr))]">

                                    <div class="flex items-center gap-2 bg-[#F7FAFA] p-5">

                                        <span class="material-symbols-outlined text-[#056663]">
                                            location_on
                                        </span>

                                        <span class="text-sm font-semibold text-gray-700">
                                            Location
                                        </span>

                                    </div>

                                    @foreach($compareListings as $compare)

                                        @php
                                            $listing = $compare->listing;
                                        @endphp

                                        <div class="border-l border-[#DDE9E8] p-5 text-sm text-gray-700">

                                            @if($listing?->city || $listing?->state)

                                                <div class="flex items-start gap-2">

                                                    <span class="material-symbols-outlined
                                                                 text-[18px] text-[#056663]">
                                                        location_on
                                                    </span>

                                                    <span>

                                                        {{ $listing->city }}

                                                        @if($listing->city && $listing->state)
                                                            ,
                                                        @endif

                                                        {{ $listing->state }}

                                                    </span>

                                                </div>

                                            @else

                                                —

                                            @endif

                                        </div>

                                    @endforeach

                                </div>


                                {{-- ================================================= --}}
                                {{-- SELLER --}}
                                {{-- ================================================= --}}

                                <div class="grid grid-cols-[180px_repeat(auto-fit,minmax(240px,1fr))]">

                                    <div class="flex items-center gap-2 bg-[#F7FAFA] p-5">

                                        <span class="material-symbols-outlined text-[#056663]">
                                            business
                                        </span>

                                        <span class="text-sm font-semibold text-gray-700">
                                            Seller
                                        </span>

                                    </div>


                                    @foreach($compareListings as $compare)

                                        @php
                                            $listing = $compare->listing;
                                            $seller = $listing?->seller;
                                        @endphp

                                        <div class="border-l border-[#DDE9E8] p-5">

                                            @if($seller)

                                                <div class="flex items-center gap-3">

                                                    <div class="flex h-10 w-10 shrink-0
                                                                items-center justify-center
                                                                rounded-xl bg-[#E8F5F4]">

                                                        <span class="material-symbols-outlined
                                                                     text-[#056663]">
                                                            business
                                                        </span>

                                                    </div>

                                                    <div class="min-w-0">

                                                        <p class="truncate text-sm font-bold text-gray-900">
                                                            {{ $seller->bussiness_name }}
                                                        </p>

                                                        @if($seller->is_verified)

                                                            <div class="mt-1 flex items-center gap-1">

                                                                <span class="material-symbols-outlined
                                                                             text-[15px] text-[#056663]">
                                                                    verified
                                                                </span>

                                                                <span class="text-xs font-medium text-[#056663]">
                                                                    Verified Seller
                                                                </span>

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>

                                            @elseif($listing?->seller_name)

                                                <div class="flex items-center gap-3">

                                                    <div class="flex h-10 w-10 shrink-0
                                                                items-center justify-center
                                                                rounded-xl bg-[#E8F5F4]">

                                                        <span class="material-symbols-outlined
                                                                     text-[#056663]">
                                                            business
                                                        </span>

                                                    </div>

                                                    <div>

                                                        <p class="text-sm font-bold text-gray-900">
                                                            {{ $listing->seller_name }}
                                                        </p>

                                                        <p class="mt-1 text-xs text-gray-400">
                                                            Seller
                                                        </p>

                                                    </div>

                                                </div>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    Seller not assigned
                                                </span>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>


                                {{-- Price --}}
                                <div class="grid grid-cols-[180px_repeat(auto-fit,minmax(240px,1fr))]">

                                    <div class="flex items-center gap-2 bg-[#F7FAFA] p-5">

                                        <span class="material-symbols-outlined text-[#056663]">
                                            payments
                                        </span>

                                        <span class="text-sm font-semibold text-gray-700">
                                            Price
                                        </span>

                                    </div>

                                    @foreach($compareListings as $compare)

                                        @php
                                            $listing = $compare->listing;
                                        @endphp

                                        <div class="border-l border-[#DDE9E8] p-5">

                                            @if($listing?->price)

                                                <p class="text-base font-bold text-[#056663]">
                                                    ₹{{ number_format($listing->price) }}
                                                </p>

                                                @if($listing->price_unit)

                                                    <p class="mt-1 text-xs text-gray-400">
                                                        Per {{ $listing->price_unit }}
                                                    </p>

                                                @endif

                                            @else

                                                <span class="text-sm font-semibold text-[#056663]">
                                                    Get Quote
                                                </span>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>


                                {{-- Description --}}
                                <div class="grid grid-cols-[180px_repeat(auto-fit,minmax(240px,1fr))]">

                                    <div class="flex items-start gap-2 bg-[#F7FAFA] p-5">

                                        <span class="material-symbols-outlined text-[#056663]">
                                            description
                                        </span>

                                        <span class="text-sm font-semibold text-gray-700">
                                            Description
                                        </span>

                                    </div>

                                    @foreach($compareListings as $compare)

                                        <div class="border-l border-[#DDE9E8] p-5">

                                            @if($compare->listing?->description)

                                                <p class="text-sm leading-6 text-gray-600">
                                                    {{ \Illuminate\Support\Str::limit(
                                                        $compare->listing->description,
                                                        180
                                                    ) }}
                                                </p>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>


                            </div>

                        </div>

                    </div>

                </div>


                {{-- Bottom Information --}}
                <div class="mt-5 rounded-xl border border-[#DDE9E8]
                            bg-white px-4 py-3">

                    <div class="flex items-start gap-2">

                        <span class="material-symbols-outlined text-[18px] text-[#056663]">
                            info
                        </span>

                        <p class="text-xs leading-5 text-gray-500">
                            Compare price, category, location, seller and listing
                            details side by side to review your selected options.
                        </p>

                    </div>

                </div>


            @else


                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white px-6 py-20 text-center shadow-sm">

                    <div class="mx-auto flex h-20 w-20 items-center
                                justify-center rounded-2xl bg-[#E8F5F4]">

                        <span class="material-symbols-outlined
                                     text-4xl text-[#056663]">
                            compare_arrows
                        </span>

                    </div>

                    <h3 class="mt-6 text-xl font-bold text-gray-900">
                        Nothing to Compare Yet
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        Browse marketplace listings and add the options you
                        want to compare here.
                    </p>

                    <a
                        href="{{ route('listing.index') }}"
                        class="mt-7 inline-flex items-center justify-center
                               gap-2 rounded-xl bg-[#056663]
                               px-6 py-3 text-sm font-semibold
                               text-white shadow-sm transition
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