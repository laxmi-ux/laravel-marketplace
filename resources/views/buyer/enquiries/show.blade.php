<x-buyer-layout>

    <x-slot name="header">

        <div class="flex items-center gap-3 m-6">

            <a
                href="{{ route('buyer.enquiries.index') }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg
                       text-[#056663] hover:bg-[#E8F5F4]"
            >

                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />

                </svg>

            </a>


            <div>

                <h2 class="text-xl font-semibold text-gray-800">
                    Enquiry Details
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Enquiry #{{ $enquiry->id }}
                </p>

            </div>

        </div>

    </x-slot>


    @php

        $status = strtolower($enquiry->status ?? 'new');


        $statusClasses = match($status) {

            'new' =>
                'border-blue-200 bg-blue-50 text-blue-700',

            'contacted' =>
                'border-yellow-200 bg-yellow-50 text-yellow-700',

            'offer_sent' =>
                'border-purple-200 bg-purple-50 text-purple-700',

            'offer_accepted' =>
                'border-green-200 bg-green-50 text-green-700',

            'offer_rejected' =>
                'border-red-200 bg-red-50 text-red-700',

            'offer_expired' =>
                'border-orange-200 bg-orange-50 text-orange-700',

            default =>
                'border-gray-200 bg-gray-50 text-gray-700',
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


        $isExpired =
            $enquiry->status === 'offer_expired'
            ||
            (
                $enquiry->status === 'offer_sent'
                && $enquiry->offer_valid_until
                && now()->startOfDay()->gt(
                    $enquiry->offer_valid_until->startOfDay()
                )
            );

    @endphp


    <div class="mx-auto max-w-5xl space-y-6 ">


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


        {{-- Main Card --}}
        <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm m-6 ">


            {{-- Header --}}
            <div class="border-b border-[#DDE9E8] bg-[#F7FAFA] px-6 py-5">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Enquiry #{{ $enquiry->id }}
                        </p>

                        <h1 class="mt-1 text-2xl font-bold text-gray-900">
                            {{ $listingTitle }}
                        </h1>

                    </div>


                    <span class="inline-flex w-fit rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses }}">

                        {{ ucwords(str_replace('_', ' ', $status)) }}

                    </span>

                </div>

            </div>


            {{-- Information --}}
            <div class="grid gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">


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


                {{-- Submitted --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Submitted
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ $enquiry->created_at?->format('d M Y, h:i A') }}
                    </p>

                </div>

            </div>


            {{-- Buyer Message --}}
            <div class="border-t border-[#DDE9E8] px-6 py-6">

                <h3 class="text-sm font-semibold text-gray-900">
                    Your Enquiry
                </h3>


                <div class="mt-3 rounded-xl bg-[#F7FAFA] p-4">

                    <p class="whitespace-pre-line text-sm leading-6 text-gray-700">
                        {{ $enquiry->message ?: 'No message provided.' }}
                    </p>

                </div>

            </div>


            {{-- Seller Response --}}
            <div class="border-t border-[#DDE9E8] px-6 py-6">

                <h3 class="text-sm font-semibold text-gray-900">
                    Seller Response
                </h3>


                @if($enquiry->seller_response)

                    <div class="mt-3 rounded-xl border border-[#BFD6D4] bg-[#E8F5F4] p-4">

                        <p class="whitespace-pre-line text-sm leading-6 text-gray-700">
                            {{ $enquiry->seller_response }}
                        </p>


                        @if($enquiry->responded_at)

                            <p class="mt-3 text-xs text-gray-500">

                                Responded on
                                {{ $enquiry->responded_at->format('d M Y, h:i A') }}

                            </p>

                        @endif

                    </div>

                @else

                    <div class="mt-3 rounded-xl border border-dashed border-[#DDE9E8] p-5">

                        <p class="text-sm text-gray-500">
                            The seller has not responded to this enquiry yet.
                        </p>

                    </div>

                @endif

            </div>


            {{-- Commercial Offer --}}
            @if($hasOffer)

                <div class="border-t border-[#DDE9E8] bg-[#F7FAFA] px-6 py-6">

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wide text-[#056663]">
                                Commercial Offer Available
                            </p>

                            @if($enquiry->total_amount !== null)

                                <p class="mt-1 text-xl font-bold text-gray-900">

                                    ₹{{ number_format((float) $enquiry->total_amount, 2) }}

                                </p>

                            @endif


                            @if($enquiry->offer_valid_until)

                                <p class="mt-1 text-sm text-gray-500">

                                    Valid until
                                    {{ $enquiry->offer_valid_until->format('d M Y') }}

                                </p>

                            @endif

                        </div>


                        <a
                            href="{{ route('buyer.enquiries.offer', $enquiry->id) }}"
                            class="inline-flex items-center justify-center rounded-lg
                                   bg-[#056663] px-6 py-3 text-sm font-semibold
                                   text-white transition hover:bg-[#04514F]"
                        >
                            View Commercial Offer
                        </a>

                    </div>

                </div>

            @endif


            {{-- Offer Accepted --}}
            @if($status === 'offer_accepted')

                <div class="border-t border-green-200 bg-green-50 px-6 py-5">

                    <p class="font-semibold text-green-800">
                        Commercial Offer Accepted
                    </p>

                    <p class="mt-1 text-sm text-green-700">
                        You accepted the seller's offer and an order was created.
                    </p>

                    <a
                        href="{{ route('buyer.orders.index') }}"
                        class="mt-4 inline-flex rounded-lg bg-[#056663]
                               px-5 py-2.5 text-sm font-semibold text-white
                               hover:bg-[#04514F]"
                    >
                        View My Orders
                    </a>

                </div>

            @endif


            {{-- Offer Rejected --}}
            @if($status === 'offer_rejected')

                <div class="border-t border-red-200 bg-red-50 px-6 py-5">

                    <p class="font-semibold text-red-800">
                        Commercial Offer Rejected
                    </p>

                    <p class="mt-1 text-sm text-red-700">
                        This commercial offer was rejected.
                    </p>

                </div>

            @endif


            {{-- Offer Expired --}}
            @if($isExpired && $status !== 'offer_accepted')

                <div class="border-t border-orange-200 bg-orange-50 px-6 py-5">

                    <p class="font-semibold text-orange-800">
                        Commercial Offer Expired
                    </p>

                    <p class="mt-1 text-sm text-orange-700">
                        The seller's offer is no longer available for acceptance.
                        Contact the seller if you need an updated offer.
                    </p>

                </div>

            @endif


            {{-- Footer --}}
            <div class="flex flex-col gap-3 border-t border-[#DDE9E8] px-6 py-5 sm:flex-row sm:justify-between">

                <a
                    href="{{ route('buyer.enquiries.index') }}"
                    class="rounded-lg border border-[#DDE9E8]
                           px-5 py-2.5 text-center text-sm font-medium
                           text-gray-700 hover:bg-[#F7FAFA]"
                >
                    Back to Enquiries
                </a>


                <div class="flex flex-col gap-3 sm:flex-row">

                    @if($enquiry->listing)

                        <a
                            href="{{ route('listing.show', $enquiry->listing->id) }}"
                            class="rounded-lg border border-[#DDE9E8]
                                   px-5 py-2.5 text-center text-sm
                                   font-medium text-gray-700
                                   hover:bg-[#F7FAFA]"
                        >
                            View Listing
                        </a>

                    @endif


                    @if($hasOffer)

                        <a
                            href="{{ route('buyer.enquiries.offer', $enquiry->id) }}"
                            class="rounded-lg bg-[#056663]
                                   px-5 py-2.5 text-center text-sm
                                   font-semibold text-white hover:bg-[#04514F]"
                        >
                            View Offer
                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-buyer-layout>