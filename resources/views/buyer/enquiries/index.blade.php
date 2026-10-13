<x-buyer-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                My Enquiries
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                View enquiries submitted to sellers and track commercial offers.
            </p>
        </div>

    </x-slot>


    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Success --}}
        @if(session('success'))

            <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                {{ session('success') }}
            </div>

        @endif


        {{-- Error --}}
        @if(session('error'))

            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                {{ session('error') }}
            </div>

        @endif


        {{-- Info --}}
        @if(session('info'))

            <div class="rounded-xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700">
                {{ session('info') }}
            </div>

        @endif


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

                <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Page Header --}}
        <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm m-6">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h1 class="text-2xl font-bold text-gray-900">
                        My Enquiries
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Track your listing enquiries, seller responses and commercial offers.
                    </p>

                </div>


                <a
                    href="{{ route('listings.index') }}"
                    class="inline-flex items-center justify-center rounded-lg
                           bg-[#056663] px-5 py-2.5 text-sm font-semibold
                           text-white transition hover:bg-[#04514F]"
                >
                    Browse Listings
                </a>

            </div>

        </div>


        {{-- Enquiries --}}
        @forelse($enquiries as $enquiry)

            @php

                $status = strtolower($enquiry->status ?? 'new');

                $statusClasses = match($status) {

                    'new' =>
                        'bg-blue-50 text-blue-700 border-blue-200',

                    'contacted' =>
                        'bg-yellow-50 text-yellow-700 border-yellow-200',

                    'offer_sent' =>
                        'bg-purple-50 text-purple-700 border-purple-200',

                    'offer_accepted' =>
                        'bg-green-50 text-green-700 border-green-200',

                    'offer_rejected' =>
                        'bg-red-50 text-red-700 border-red-200',

                    'offer_expired' =>
                        'bg-orange-50 text-orange-700 border-orange-200',

                    'closed' =>
                        'bg-gray-100 text-gray-700 border-gray-200',

                    default =>
                        'bg-gray-50 text-gray-700 border-gray-200',
                };


                $listingTitle =
                    $enquiry->listing?->title
                    ?? $enquiry->listing?->name
                    ?? 'Listing #' . $enquiry->listing_id;


                $sellerName =
                    $enquiry->seller?->bussiness_name
                    ?? $enquiry->seller?->business_name
                    ?? $enquiry->seller?->company_name
                    ?? $enquiry->seller?->name
                    ?? 'Seller';


                $hasOffer = $enquiry->hasOffer();


                $offerExpired =
                    $enquiry->status === 'offer_expired';


                if (
                    $enquiry->status === 'offer_sent'
                    && $enquiry->offer_valid_until
                    && now()->startOfDay()->gt(
                        $enquiry->offer_valid_until->startOfDay()
                    )
                ) {
                    $offerExpired = true;
                }

            @endphp


            <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm m-6 ">

                {{-- Header --}}
                <div class="border-b border-[#DDE9E8] bg-[#F7FAFA] px-6 py-5">

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                        <div>

                            <div class="flex flex-wrap items-center gap-2">

                                <span class="text-sm font-semibold text-gray-500">
                                    Enquiry #{{ $enquiry->id }}
                                </span>


                                <span class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </span>

                            </div>


                            <h2 class="mt-2 text-lg font-bold text-gray-900">
                                {{ $listingTitle }}
                            </h2>

                        </div>


                        <div class="text-sm text-gray-500">

                            {{ $enquiry->created_at?->format('d M Y, h:i A') }}

                        </div>

                    </div>

                </div>


                {{-- Main Information --}}
                <div class="grid gap-6 p-6 lg:grid-cols-3">

                    {{-- Listing --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                            Listing
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $listingTitle }}
                        </p>

                    </div>


                    {{-- Seller --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                            Seller
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $sellerName }}
                        </p>

                    </div>


                    {{-- Quantity --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                            Quantity
                        </p>

                        <p class="mt-1 font-medium text-gray-900">

                            @if($enquiry->quantity)

                                {{ rtrim(rtrim(number_format((float) $enquiry->quantity, 2), '0'), '.') }}

                                {{ $enquiry->unit ?? '' }}

                            @else

                                Not specified

                            @endif

                        </p>

                    </div>

                </div>


                {{-- Buyer Message --}}
                <div class="border-t border-[#DDE9E8] px-6 py-5">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Your Message
                    </p>

                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                        {{ $enquiry->message ?: 'No message provided.' }}
                    </p>

                </div>


                {{-- Seller Response --}}
                @if($enquiry->seller_response)

                    <div class="border-t border-[#DDE9E8] bg-[#F7FAFA] px-6 py-5">

                        <p class="text-xs font-semibold uppercase tracking-wide text-[#056663]">
                            Seller Response
                        </p>

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                            {{ $enquiry->seller_response }}
                        </p>

                        @if($enquiry->responded_at)

                            <p class="mt-2 text-xs text-gray-400">

                                Responded on
                                {{ $enquiry->responded_at->format('d M Y, h:i A') }}

                            </p>

                        @endif

                    </div>

                @endif


                {{-- Commercial Offer --}}
                @if($hasOffer)

                    <div class="border-t border-[#DDE9E8] bg-[#F7FAFA] px-6 py-5">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-[#056663]">
                                    Commercial Offer
                                </p>

                                <p class="mt-1 text-sm text-gray-600">

                                    @if($enquiry->total_amount !== null)

                                        Total:
                                        <span class="font-bold text-gray-900">
                                            ₹{{ number_format((float) $enquiry->total_amount, 2) }}
                                        </span>

                                    @endif

                                    @if($enquiry->offer_valid_until)

                                        <span class="mx-1 text-gray-300">•</span>

                                        Valid until
                                        {{ $enquiry->offer_valid_until->format('d M Y') }}

                                    @endif

                                </p>

                            </div>


                            <a
                                href="{{ route('buyer.enquiries.offer', $enquiry->id) }}"
                                class="inline-flex items-center justify-center rounded-lg
                                       bg-[#056663] px-5 py-2.5 text-sm font-semibold
                                       text-white transition hover:bg-[#04514F]"
                            >
                                {{ $offerExpired ? 'View Offer' : 'View Commercial Offer' }}
                            </a>

                        </div>

                    </div>

                @endif


                {{-- Footer --}}
                <div class="flex flex-col gap-3 border-t border-[#DDE9E8] px-6 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="text-sm text-gray-500">

                        @if($enquiry->phone)

                            Phone: {{ $enquiry->phone }}

                        @else

                            Email: {{ $enquiry->email }}

                        @endif

                    </div>


                    <div class="flex flex-wrap gap-2">

                        @if($enquiry->listing)

                            <a
                                href="{{ route('listing.show', $enquiry->listing->id) }}"
                                class="rounded-lg border border-[#DDE9E8]
                                       px-4 py-2 text-sm font-medium
                                       text-gray-700 hover:bg-[#F7FAFA]"
                            >
                                View Listing
                            </a>

                        @endif


                        <a
                            href="{{ route('buyer.enquiries.show', $enquiry->id) }}"
                            class="rounded-lg bg-[#056663]
                                   px-4 py-2 text-sm font-semibold
                                   text-white hover:bg-[#04514F]"
                        >
                            View Details
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="rounded-2xl border border-dashed border-[#BFD6D4] bg-white px-6 py-16 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#E8F5F4] text-[#056663]">

                    <svg
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M8 10h8M8 14h5m7-2a8 8 0 11-16 0 8 8 0 0116 0z"
                        />

                    </svg>

                </div>


                <h3 class="mt-4 text-lg font-semibold text-gray-900">
                    No enquiries yet
                </h3>


                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                    When you contact a seller about a listing, your enquiry will appear here.
                </p>


                <a
                    href="{{ route('listings.index') }}"
                    class="mt-5 inline-flex rounded-lg bg-[#056663]
                           px-5 py-2.5 text-sm font-semibold text-white
                           hover:bg-[#04514F]"
                >
                    Browse Listings
                </a>

            </div>

        @endforelse


        {{-- Pagination --}}
        @if($enquiries->hasPages())

            <div class="m-6 ">
                {{ $enquiries->links() }}
            </div>

        @endif

    </div>

</x-buyer-layout>