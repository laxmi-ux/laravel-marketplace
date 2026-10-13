<x-seller-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-2xl font-bold text-gray-900">
                    RFQ Management
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Manage buyer quotation requests
                </p>

            </div>

        </div>

    </x-slot>


    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">

                {{ session('success') }}

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">

                {{ session('error') }}

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- HEADER CARD --}}
        {{-- ========================================================= --}}

        <div class="mb-6 rounded-2xl border border-[#D3E3E0] bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>

                    <h1 class="text-xl font-bold text-gray-900">
                        Buyer RFQs
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        View requirements matched with your listings and respond with quotations.
                    </p>

                </div>


                <div class="rounded-xl bg-[#EAF5F3] px-5 py-3 text-center">

                    <div class="text-2xl font-bold text-[#056663]">
                        {{ $rfqs->total() }}
                    </div>

                    <div class="text-xs font-medium text-gray-600">
                        Total RFQs
                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- RFQ LIST --}}
        {{-- ========================================================= --}}

        @if($rfqs->count())

            <div class="space-y-4">

                @foreach($rfqs as $rfq)

                    @php

                        $requirement = $rfq->requirement;

                        $listing = $requirement?->listing;

                        $buyer = $requirement?->user;

                    @endphp


                    <div
                        class="rounded-2xl border border-[#D3E3E0] bg-white p-6 shadow-sm transition hover:shadow-md"
                    >

                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">


                            {{-- ================================================= --}}
                            {{-- MAIN RFQ INFORMATION --}}
                            {{-- ================================================= --}}

                            <div class="flex-1">

                                {{-- Title + Status --}}

                                <div class="flex flex-wrap items-center gap-3">

                                    <h3 class="text-lg font-bold text-gray-900">

                                        {{ $listing?->title ?? 'General Requirement' }}

                                    </h3>


                                    {{-- ================================================= --}}
                                    {{-- STATUS --}}
                                    {{-- ================================================= --}}

                                    @if($rfq->status === 'new')

                                        <span
                                            class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700"
                                        >
                                            New
                                        </span>


                                    @elseif($rfq->status === 'viewed')

                                        <span
                                            class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700"
                                        >
                                            Viewed
                                        </span>


                                    @elseif($rfq->status === 'quotation_sent')

                                        <span
                                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                                        >
                                            Quotation Sent
                                        </span>


                                    @elseif($rfq->status === 'quoted')

                                        <span
                                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                                        >
                                            Quoted
                                        </span>


                                    @elseif($rfq->status === 'rejected')

                                        <span
                                            class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700"
                                        >
                                            Rejected
                                        </span>


                                    @else

                                        <span
                                            class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700"
                                        >
                                            {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                                        </span>

                                    @endif

                                </div>


                                {{-- ================================================= --}}
                                {{-- RFQ DETAILS --}}
                                {{-- ================================================= --}}

                                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">


                                    {{-- Buyer --}}

                                    <div>

                                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                            Buyer
                                        </p>

                                        <p class="mt-1 font-medium text-gray-800">

                                            {{ $requirement?->name ?? $buyer?->name ?? 'N/A' }}

                                        </p>

                                    </div>


                                    {{-- Quantity --}}

                                    <div>

                                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                            Quantity
                                        </p>

                                        <p class="mt-1 font-medium text-gray-800">

                                            {{ $requirement?->quantity ?? 'N/A' }}

                                            {{ $requirement?->unit ?? '' }}

                                        </p>

                                    </div>


                                    {{-- Location --}}

                                    <div>

                                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                            Location
                                        </p>

                                        <p class="mt-1 font-medium text-gray-800">

                                            {{ $requirement?->delivery_location ?? 'N/A' }}

                                        </p>

                                    </div>


                                    {{-- Received --}}

                                    <div>

                                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                            Received
                                        </p>

                                        <p class="mt-1 font-medium text-gray-800">

                                            {{ $rfq->created_at?->format('d M Y') }}

                                        </p>

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- BUYER REQUIREMENT MESSAGE --}}
                                {{-- ================================================= --}}

                                @if($requirement?->message)

                                    <div class="mt-4 rounded-xl bg-gray-50 p-4">

                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                            Buyer Requirement
                                        </p>

                                        <p class="mt-1 line-clamp-2 text-sm leading-6 text-gray-700">

                                            {{ $requirement->message }}

                                        </p>

                                    </div>

                                @endif

                            </div>


                            {{-- ================================================= --}}
                            {{-- ACTIONS --}}
                            {{-- ================================================= --}}

                            <div class="flex flex-wrap gap-2 lg:w-48 lg:flex-col">


                                {{-- View RFQ --}}

                                <a
                                    href="{{ route('seller.rfqs.show', $rfq->id) }}"
                                    class="rounded-xl bg-[#056663] px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-[#04534F]"
                                >
                                    View RFQ
                                </a>


                                {{-- ================================================= --}}
                                {{-- SEND / EDIT QUOTATION --}}
                                {{-- ================================================= --}}

                                @if(in_array($rfq->status, ['new', 'viewed', 'quotation_sent']))

                                    <a
                                        href="{{ route('seller.rfqs.quotation.create', $rfq->id) }}"
                                        class="rounded-xl border border-[#056663] px-4 py-2.5 text-center text-sm font-semibold text-[#056663] transition hover:bg-[#EAF5F3]"
                                    >

                                        @if($rfq->status === 'quotation_sent')

                                            Edit Quotation

                                        @else

                                            Send Quotation

                                        @endif

                                    </a>

                                @endif


                                {{-- ================================================= --}}
                                {{-- REJECT RFQ --}}
                                {{-- ================================================= --}}

                                @if(in_array($rfq->status, ['new', 'viewed']))

                                    <form
                                        method="POST"
                                        action="{{ route('seller.rfqs.reject', $rfq->id) }}"
                                        onsubmit="return confirm('Are you sure you want to reject this RFQ?');"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="w-full rounded-xl border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                @endif


                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- ========================================================= --}}
            {{-- PAGINATION --}}
            {{-- ========================================================= --}}

            <div class="mt-6">

                {{ $rfqs->links() }}

            </div>


        @else


            {{-- ========================================================= --}}
            {{-- EMPTY STATE --}}
            {{-- ========================================================= --}}

            <div class="rounded-2xl border border-[#D3E3E0] bg-white px-6 py-16 text-center shadow-sm">

                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#EAF5F3]"
                >

                    <svg
                        class="h-8 w-8 text-[#056663]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0118 8v11a2 2 0 01-2 2z"
                        />

                    </svg>

                </div>


                <h3 class="mt-5 text-lg font-bold text-gray-900">
                    No RFQs Yet
                </h3>


                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">

                    Buyer requirements that match your listings will appear here automatically.

                </p>

            </div>

        @endif

    </div>

</x-seller-layout>