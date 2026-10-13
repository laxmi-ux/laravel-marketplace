<x-seller-layout>

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-[#123B38]">
                Marketplace Enquiries
            </h2>

            <p class="text-sm text-[#4F6663]">
                Manage enquiries received from buyers
            </p>

        </div>

    </x-slot>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="bg-[#F7FAF9] px-4 py-6 sm:px-5 sm:py-8 lg:px-6">

        <div class="mx-auto w-full max-w-7xl">


            {{-- =====================================================
                 PAGE HEADER
            ====================================================== --}}

            <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                <div>

                    <h1 class="text-2xl font-bold text-[#163B37]">
                        Marketplace Enquiries
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Manage enquiries received from buyers for your listings.
                    </p>

                </div>


                <a
                    href="{{ route('seller.dashboard') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-[#D3E3E0] bg-white px-4 py-2.5 text-sm font-medium text-[#00665F] transition hover:bg-[#EAF5F3]"
                >
                    ← Back to Dashboard
                </a>

            </div>


            {{-- =====================================================
                 SUCCESS MESSAGE
            ====================================================== --}}

            @if(session('success'))

                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>

            @endif


            {{-- =====================================================
                 ERROR MESSAGE
            ====================================================== --}}

            @if(session('error'))

                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>

            @endif


            {{-- =====================================================
                 ENQUIRIES
            ====================================================== --}}

            @forelse($enquiries as $enquiry)

                @php

                    $status = $enquiry->status;

                    $statusClasses = match($status) {

                        'new' =>
                            'bg-blue-50 text-blue-700 border-blue-200',

                        'contacted' =>
                            'bg-yellow-50 text-yellow-700 border-yellow-200',

                        'quotation_sent' =>
                            'bg-purple-50 text-purple-700 border-purple-200',

                        'closed' =>
                            'bg-green-50 text-green-700 border-green-200',

                        default =>
                            'bg-gray-50 text-gray-700 border-gray-200',

                    };


                    $statusLabel = match($status) {

                        'new' =>
                            'New',

                        'contacted' =>
                            'Contacted',

                        'quotation_sent' =>
                            'Quotation Sent',

                        'closed' =>
                            'Closed',

                        default =>
                            ucfirst(str_replace('_', ' ', $status)),

                    };

                @endphp


                {{-- =================================================
                     ENQUIRY CARD
                ================================================== --}}

                <div class="mb-6 overflow-hidden rounded-2xl border border-[#DCE9E6] bg-white shadow-sm">


                    {{-- =================================================
                         ENQUIRY HEADER
                    ================================================== --}}

                    <div class="border-b border-[#E5EEEC] bg-[#F9FCFB] px-5 py-4 sm:px-6">

                        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">

                            <div>

                                <div class="flex flex-wrap items-center gap-2">

                                    <h2 class="text-lg font-semibold text-[#163B37]">

                                        {{ optional($enquiry->listing)->title ?? 'Listing Enquiry' }}

                                    </h2>


                                    <span
                                        class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses }}"
                                    >
                                        {{ $statusLabel }}
                                    </span>

                                </div>


                                <p class="mt-1 text-xs text-gray-500">

                                    Enquiry #{{ $enquiry->id }}

                                    <span class="mx-1">·</span>

                                    {{ $enquiry->created_at?->format('d M Y, h:i A') }}

                                </p>

                            </div>


                            {{-- View Button --}}

                            <a
                                href="{{ route('seller.enquiries.show', $enquiry->id) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-[#CFE1DE] bg-white px-4 py-2 text-sm font-semibold text-[#00665F] transition hover:bg-[#EAF5F3]"
                            >
                                View Details
                            </a>

                        </div>

                    </div>


                    {{-- =================================================
                         ENQUIRY BODY
                    ================================================== --}}

                    <div class="px-5 py-6 sm:px-6">


                        {{-- =================================================
                             INFORMATION GRID
                        ================================================== --}}

                        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">


                            {{-- =================================================
                                 BUYER INFORMATION
                            ================================================== --}}

                            <div class="rounded-xl border border-[#E1ECE9] p-5">

                                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-[#00665F]">
                                    Buyer Information
                                </h3>


                                <div class="space-y-3 text-sm">


                                    {{-- Name --}}

                                    <div class="flex justify-between gap-4">

                                        <span class="text-gray-500">
                                            Name
                                        </span>

                                        <span class="text-right font-medium text-gray-800">

                                            {{ optional($enquiry->user)->name ?? $enquiry->name ?? 'N/A' }}

                                        </span>

                                    </div>


                                    {{-- Email --}}

                                    <div class="flex justify-between gap-4">

                                        <span class="text-gray-500">
                                            Email
                                        </span>

                                        <span class="break-all text-right font-medium text-gray-800">

                                            {{ optional($enquiry->user)->email ?? $enquiry->email ?? 'N/A' }}

                                        </span>

                                    </div>


                                    {{-- Phone --}}

                                    <div class="flex justify-between gap-4">

                                        <span class="text-gray-500">
                                            Phone
                                        </span>

                                        <span class="text-right font-medium text-gray-800">

                                            {{ $enquiry->phone ?? 'N/A' }}

                                        </span>

                                    </div>


                                    {{-- Company --}}

                                    @if(optional($enquiry->user)->business_name)

                                        <div class="flex justify-between gap-4">

                                            <span class="text-gray-500">
                                                Company
                                            </span>

                                            <span class="text-right font-medium text-gray-800">

                                                {{ $enquiry->user->business_name }}

                                            </span>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- =================================================
                                 LISTING / ENQUIRY DETAILS
                            ================================================== --}}

                            <div class="rounded-xl border border-[#E1ECE9] p-5">

                                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-[#00665F]">
                                    Enquiry Details
                                </h3>


                                <div class="space-y-3 text-sm">


                                    {{-- Listing ID --}}

                                    <div class="flex justify-between gap-4">

                                        <span class="text-gray-500">
                                            Listing ID
                                        </span>

                                        <span class="text-right font-medium text-gray-800">

                                            #{{ $enquiry->listing_id }}

                                        </span>

                                    </div>


                                    {{-- Listing Title --}}

                                    <div class="flex justify-between gap-4">

                                        <span class="text-gray-500">
                                            Listing
                                        </span>

                                        <span class="text-right font-medium text-gray-800">

                                            {{ optional($enquiry->listing)->title ?? 'N/A' }}

                                        </span>

                                    </div>


                                    {{-- Quantity --}}

                                    @if($enquiry->quantity !== null)

                                        <div class="flex justify-between gap-4">

                                            <span class="text-gray-500">
                                                Quantity
                                            </span>

                                            <span class="text-right font-medium text-gray-800">

                                                {{ $enquiry->quantity }}

                                                {{ $enquiry->unit ?? '' }}

                                            </span>

                                        </div>

                                    @endif


                                    {{-- Unit --}}

                                    @if($enquiry->unit && $enquiry->quantity === null)

                                        <div class="flex justify-between gap-4">

                                            <span class="text-gray-500">
                                                Unit
                                            </span>

                                            <span class="text-right font-medium text-gray-800">

                                                {{ $enquiry->unit }}

                                            </span>

                                        </div>

                                    @endif


                                    {{-- Enquiry Status --}}

                                    <div class="flex justify-between gap-4">

                                        <span class="text-gray-500">
                                            Status
                                        </span>

                                        <span class="text-right font-medium text-gray-800">

                                            {{ $statusLabel }}

                                        </span>

                                    </div>


                                    {{-- Response Date --}}

                                    @if($enquiry->responded_at)

                                        <div class="flex justify-between gap-4">

                                            <span class="text-gray-500">
                                                Responded
                                            </span>

                                            <span class="text-right font-medium text-gray-800">

                                                {{ $enquiry->responded_at->format('d M Y, h:i A') }}

                                            </span>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             BUYER MESSAGE
                        ================================================== --}}

                        @if($enquiry->message)

                            <div class="mt-5 rounded-xl border border-[#E1ECE9] bg-[#FAFCFB] p-5">

                                <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-[#00665F]">
                                    Buyer Message
                                </h3>

                                <p class="whitespace-pre-line text-sm leading-6 text-gray-700">

                                    {{ $enquiry->message }}

                                </p>

                            </div>

                        @endif


                        {{-- =================================================
                             SELLER RESPONSE
                        ================================================== --}}

                        @if($enquiry->seller_response)

                            <div class="mt-5 rounded-xl border border-green-200 bg-green-50 p-5">

                                <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-green-700">
                                    Your Response
                                </h3>

                                <p class="whitespace-pre-line text-sm leading-6 text-gray-700">

                                    {{ $enquiry->seller_response }}

                                </p>

                                @if($enquiry->responded_at)

                                    <p class="mt-3 text-xs text-green-600">

                                        Responded on
                                        {{ $enquiry->responded_at->format('d M Y, h:i A') }}

                                    </p>

                                @endif

                            </div>

                        @endif


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}

                        <div class="mt-5 flex flex-wrap gap-3">


                            {{-- View Details --}}

                            <a
                                href="{{ route('seller.enquiries.show', $enquiry->id) }}"
                                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                            >
                                View Details
                            </a>


                            {{-- Respond --}}

                            @if($status !== 'closed')

                                <a
                                    href="{{ route('seller.enquiries.respond', $enquiry->id) }}"
                                    class="inline-flex items-center rounded-lg bg-[#00665F] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00544F]"
                                >
                                    {{ $enquiry->seller_response ? 'Update Response' : 'Respond' }}
                                </a>

                            @endif

                        </div>

                    </div>

                </div>


            @empty


                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}

                <div class="rounded-2xl border border-[#DCE9E6] bg-white px-6 py-16 text-center shadow-sm">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#EAF5F3] text-2xl">
                        📩
                    </div>


                    <h2 class="mt-5 text-lg font-semibold text-[#163B37]">
                        No enquiries yet
                    </h2>


                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">

                        You don't have any buyer enquiries for your listings yet.
                        New enquiries will appear here when buyers contact you.

                    </p>


                    <a
                        href="{{ route('seller.listings.index') }}"
                        class="mt-6 inline-flex items-center rounded-lg bg-[#00665F] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00544F]"
                    >
                        View My Listings
                    </a>

                </div>

            @endforelse


            {{-- =====================================================
                 PAGINATION
            ====================================================== --}}

            @if($enquiries->hasPages())

                <div class="mt-6">

                    {{ $enquiries->links() }}

                </div>

            @endif

        </div>

    </div>

</x-seller-layout>
