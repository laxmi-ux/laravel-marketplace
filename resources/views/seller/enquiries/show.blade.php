<x-seller-layout>

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-[#123B38]">
                Enquiry Details
            </h2>

            <p class="text-sm text-[#4F6663]">
                View buyer enquiry and manage your response or commercial offer
            </p>

        </div>

    </x-slot>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="bg-[#F7FAF9] px-4 py-6 sm:px-5 sm:py-8 lg:px-6">

        <div class="mx-auto w-full max-w-6xl">


            {{-- =====================================================
                 TOP HEADER
            ====================================================== --}}

            <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                <div>

                    <div class="flex flex-wrap items-center gap-3">

                        <h1 class="text-2xl font-bold text-[#163B37]">
                            Enquiry #{{ $enquiry->id }}
                        </h1>


                        @php

                            $status = $enquiry->status;

                            $statusClasses = match($status) {

                                'new' =>
                                'bg-blue-50 text-blue-700 border-blue-200',

                                'contacted' =>
                                'bg-yellow-50 text-yellow-700 border-yellow-200',

                                'offer_sent' =>
                                'bg-indigo-50 text-indigo-700 border-indigo-200',

                                'offer_accepted' =>
                                'bg-green-50 text-green-700 border-green-200',

                                'offer_rejected' =>
                                'bg-red-50 text-red-700 border-red-200',

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

                                'offer_sent' =>
                                'Commercial Offer Sent',

                                'offer_accepted' =>
                                'Commercial Offer Accepted',

                                'offer_rejected' =>
                                'Commercial Offer Rejected',

                                'quotation_sent' =>
                                'Quotation Sent',

                                'closed' =>
                                'Closed',

                                default =>
                                ucfirst(str_replace('_', ' ', $status)),

                            };

                        @endphp


                        <span
                            class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses }}"
                        >
                            {{ $statusLabel }}
                        </span>

                    </div>


                    <p class="mt-1 text-sm text-gray-500">

                        Received
                        {{ $enquiry->created_at?->format('d M Y, h:i A') }}

                    </p>

                </div>


                <a
                    href="{{ route('seller.enquiries.index') }}"
                    class="inline-flex items-center justify-center rounded-lg
                           border border-[#D3E3E0] bg-white px-4 py-2.5
                           text-sm font-medium text-[#00665F]
                           transition hover:bg-[#EAF5F3]"
                >
                    ← Back to Enquiries
                </a>

            </div>


            {{-- =====================================================
                 SUCCESS MESSAGE
            ====================================================== --}}

            @if(session('success'))

                <div
                    class="mb-6 rounded-lg border border-green-200
                           bg-green-50 px-4 py-3 text-sm text-green-700"
                >
                    {{ session('success') }}
                </div>

            @endif


            {{-- =====================================================
                 ERROR MESSAGE
            ====================================================== --}}

            @if(session('error'))

                <div
                    class="mb-6 rounded-lg border border-red-200
                           bg-red-50 px-4 py-3 text-sm text-red-700"
                >
                    {{ session('error') }}
                </div>

            @endif


            {{-- =====================================================
                 VALIDATION ERRORS
            ====================================================== --}}

            @if($errors->any())

                <div
                    class="mb-6 rounded-lg border border-red-200
                           bg-red-50 px-4 py-3 text-sm text-red-700"
                >

                    <ul class="list-disc space-y-1 pl-5">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =====================================================
                 LISTING INFORMATION
            ====================================================== --}}

            <div
                class="mb-6 overflow-hidden rounded-2xl
                       border border-[#DCE9E6] bg-white shadow-sm"
            >

                <div
                    class="border-b border-[#E5EEEC]
                           bg-[#F9FCFB] px-5 py-4 sm:px-6"
                >

                    <h2 class="text-base font-semibold text-[#163B37]">
                        Listing Information
                    </h2>

                </div>


                <div class="px-5 py-6 sm:px-6">

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                        {{-- Listing Title --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Listing
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                {{ optional($enquiry->listing)->title ?? 'N/A' }}

                            </p>

                        </div>


                        {{-- Listing ID --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Listing ID
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                {{ $enquiry->listing_id ? '#' . $enquiry->listing_id : 'N/A' }}

                            </p>

                        </div>


                        {{-- Seller --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Seller
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                {{ optional($enquiry->seller)->company_name
                                    ?? optional($enquiry->seller)->business_name
                                    ?? optional($enquiry->seller)->bussiness_name
                                    ?? 'Your Company' }}

                            </p>

                        </div>


                        {{-- Enquiry Date --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Enquiry Date
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                {{ $enquiry->created_at?->format('d M Y, h:i A') }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 BUYER INFORMATION
            ====================================================== --}}

            <div
                class="mb-6 overflow-hidden rounded-2xl
                       border border-[#DCE9E6] bg-white shadow-sm"
            >

                <div
                    class="border-b border-[#E5EEEC]
                           bg-[#F9FCFB] px-5 py-4 sm:px-6"
                >

                    <h2 class="text-base font-semibold text-[#163B37]">
                        Buyer Information
                    </h2>

                </div>


                <div class="px-5 py-6 sm:px-6">

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                        {{-- Name --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Name
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                {{ optional($enquiry->user)->name
                                    ?? $enquiry->name
                                    ?? 'N/A' }}

                            </p>

                        </div>


                        {{-- Email --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Email
                            </p>

                            <p class="mt-1 break-all text-sm font-semibold text-gray-800">

                                {{ optional($enquiry->user)->email
                                    ?? $enquiry->email
                                    ?? 'N/A' }}

                            </p>

                        </div>


                        {{-- Phone --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Phone
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                {{ $enquiry->phone ?? 'N/A' }}

                            </p>

                        </div>


                        {{-- Company --}}

                        @if(optional($enquiry->user)->business_name)

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Company
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    {{ $enquiry->user->business_name }}

                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 ENQUIRY DETAILS
            ====================================================== --}}

            <div
                class="mb-6 overflow-hidden rounded-2xl
                       border border-[#DCE9E6] bg-white shadow-sm"
            >

                <div
                    class="border-b border-[#E5EEEC]
                           bg-[#F9FCFB] px-5 py-4 sm:px-6"
                >

                    <h2 class="text-base font-semibold text-[#163B37]">
                        Enquiry Details
                    </h2>

                </div>


                <div class="px-5 py-6 sm:px-6">

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">


                        {{-- Quantity --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Quantity
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                @if($enquiry->quantity !== null)

                                    {{ $enquiry->quantity }}
                                    {{ $enquiry->unit ?? '' }}

                                @else

                                    N/A

                                @endif

                            </p>

                        </div>


                        {{-- Unit --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Unit
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                {{ $enquiry->unit ?? 'N/A' }}

                            </p>

                        </div>


                        {{-- Status --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Status
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                {{ $statusLabel }}

                            </p>

                        </div>


                        {{-- Responded At --}}

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Responded At
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-800">

                                @if($enquiry->responded_at)

                                    {{ $enquiry->responded_at->format('d M Y, h:i A') }}

                                @else

                                    Not responded yet

                                @endif

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 COMMERCIAL OFFER
            ====================================================== --}}

            @if($enquiry->hasOffer())

                <div
                    class="mb-6 overflow-hidden rounded-2xl
                           border border-[#DCE9E6] bg-white shadow-sm"
                >

                    {{-- Offer Header --}}

                    <div
                        class="border-b border-[#E5EEEC]
                               bg-[#F9FCFB] px-5 py-4 sm:px-6"
                    >

                        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">

                            <div>

                                <h2 class="text-base font-semibold text-[#163B37]">
                                    Commercial Offer
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    Commercial pricing and delivery terms submitted to the buyer.
                                </p>

                            </div>


                            {{-- Offer Status --}}

                            @if($enquiry->status === 'offer_sent')

                                <span
                                    class="inline-flex w-fit rounded-full
                                           border border-indigo-200 bg-indigo-50
                                           px-3 py-1 text-xs font-semibold
                                           text-indigo-700"
                                >
                                    Offer Sent
                                </span>

                            @elseif($enquiry->status === 'offer_accepted')

                                <span
                                    class="inline-flex w-fit rounded-full
                                           border border-green-200 bg-green-50
                                           px-3 py-1 text-xs font-semibold
                                           text-green-700"
                                >
                                    Accepted
                                </span>

                            @elseif($enquiry->status === 'offer_rejected')

                                <span
                                    class="inline-flex w-fit rounded-full
                                           border border-red-200 bg-red-50
                                           px-3 py-1 text-xs font-semibold
                                           text-red-700"
                                >
                                    Rejected
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Offer Details --}}

                    <div class="px-5 py-6 sm:px-6">

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                            {{-- Unit Price --}}

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Unit Price
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    ₹{{ number_format((float) $enquiry->unit_price, 2) }}

                                </p>

                            </div>


                            {{-- Delivery Charges --}}

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Delivery Charges
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    ₹{{ number_format((float) ($enquiry->delivery_charges ?? 0), 2) }}

                                </p>

                            </div>


                            {{-- Other Charges --}}

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Other Charges
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    ₹{{ number_format((float) ($enquiry->other_charges ?? 0), 2) }}

                                </p>

                            </div>


                            {{-- Tax --}}

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Tax
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    ₹{{ number_format((float) ($enquiry->tax ?? 0), 2) }}

                                </p>

                            </div>


                            {{-- Delivery Time --}}

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Delivery Time
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    {{ $enquiry->delivery_time ?: 'Not specified' }}

                                </p>

                            </div>


                            {{-- Offer Valid Until --}}

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Offer Valid Until
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    {{ $enquiry->offer_valid_until?->format('d M Y') ?? 'Not specified' }}

                                </p>

                            </div>


                            {{-- Offer Sent At --}}

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Offer Sent At
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    {{ $enquiry->offer_sent_at?->format('d M Y, h:i A') ?? 'N/A' }}

                                </p>

                            </div>


                            {{-- Quantity Used --}}

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Offer Quantity
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-800">

                                    {{ $enquiry->quantity ?? 1 }}
                                    {{ $enquiry->unit ?? '' }}

                                </p>

                            </div>

                        </div>


                        {{-- Total --}}

                        <div
                            class="mt-6 rounded-xl border border-[#DDE9E8]
                                   bg-[#F7FAFA] p-5"
                        >

                            <div class="flex flex-col gap-2 sm:flex-row
                                        sm:items-center sm:justify-between">

                                <div>

                                    <p class="text-sm font-medium text-gray-600">
                                        Total Commercial Offer
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Including delivery, other charges and tax
                                    </p>

                                </div>


                                <p class="text-2xl font-bold text-[#056663]">

                                    ₹{{ number_format((float) ($enquiry->total_amount ?? 0), 2) }}

                                </p>

                            </div>

                        </div>


                        {{-- Offer Notes --}}

                        @if($enquiry->offer_notes)

                            <div class="mt-6">

                                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Offer Notes
                                </p>

                                <div
                                    class="rounded-xl border border-gray-200
                                           bg-gray-50 px-4 py-4"
                                >

                                    <p class="whitespace-pre-line text-sm leading-7 text-gray-700">
                                        {{ $enquiry->offer_notes }}
                                    </p>

                                </div>

                            </div>

                        @endif


                        {{-- Offer Actions --}}

                        @if($enquiry->status === 'offer_sent')

                            <div class="mt-6 flex flex-wrap gap-3">

                                <a
                                    href="{{ route('seller.enquiries.offer', $enquiry->id) }}"
                                    class="inline-flex items-center rounded-lg
                                           bg-[#00665F] px-5 py-2.5
                                           text-sm font-semibold text-white
                                           transition hover:bg-[#00544F]"
                                >
                                    Edit Commercial Offer
                                </a>

                            </div>

                        @endif

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 BUYER MESSAGE
            ====================================================== --}}

            @if($enquiry->message)

                <div
                    class="mb-6 overflow-hidden rounded-2xl
                           border border-[#DCE9E6] bg-white shadow-sm"
                >

                    <div
                        class="border-b border-[#E5EEEC]
                               bg-[#F9FCFB] px-5 py-4 sm:px-6"
                    >

                        <h2 class="text-base font-semibold text-[#163B37]">
                            Buyer Message
                        </h2>

                    </div>


                    <div class="px-5 py-6 sm:px-6">

                        <p class="whitespace-pre-line text-sm leading-7 text-gray-700">
                            {{ $enquiry->message }}
                        </p>

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 SELLER RESPONSE
            ====================================================== --}}

            @if($enquiry->seller_response)

                <div
                    class="mb-6 overflow-hidden rounded-2xl
                           border border-green-200 bg-green-50"
                >

                    <div
                        class="border-b border-green-200
                               px-5 py-4 sm:px-6"
                    >

                        <h2 class="text-base font-semibold text-green-800">
                            Your Response
                        </h2>

                    </div>


                    <div class="px-5 py-6 sm:px-6">

                        <p class="whitespace-pre-line text-sm leading-7 text-gray-700">
                            {{ $enquiry->seller_response }}
                        </p>


                        @if($enquiry->responded_at)

                            <p class="mt-4 text-xs text-green-700">

                                Responded on
                                {{ $enquiry->responded_at->format('d M Y, h:i A') }}

                            </p>

                        @endif

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 ACTIONS
            ====================================================== --}}

            <div class="flex flex-wrap gap-3">


                {{-- Back --}}

                <a
                    href="{{ route('seller.enquiries.index') }}"
                    class="inline-flex items-center rounded-lg
                           border border-gray-300 bg-white px-5 py-2.5
                           text-sm font-semibold text-gray-700
                           transition hover:bg-gray-50"
                >
                    ← Back
                </a>


                {{-- Send Commercial Offer --}}

                @if(
                    in_array($status, ['new', 'contacted']) &&
                    !$enquiry->hasOffer()
                )

                    <a
                        href="{{ route('seller.enquiries.offer', $enquiry->id) }}"
                        class="inline-flex items-center rounded-lg
                               bg-[#00665F] px-5 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-[#00544F]"
                    >
                        Send Commercial Offer
                    </a>

                @endif


                {{-- Respond / Update Response --}}

                @if(
                    !in_array($status, [
                        'offer_accepted',
                        'offer_rejected',
                        'closed'
                    ])
                )

                    <a
                        href="{{ route('seller.enquiries.respond', $enquiry->id) }}"
                        class="inline-flex items-center rounded-lg
                               border border-[#00665F] bg-white px-5 py-2.5
                               text-sm font-semibold text-[#00665F]
                               transition hover:bg-[#EAF5F3]"
                    >
                        {{ $enquiry->seller_response
                            ? 'Update Response'
                            : 'Respond to Buyer' }}
                    </a>

                @endif


                {{-- Edit Offer --}}

                @if($enquiry->status === 'offer_sent')

                    <a
                        href="{{ route('seller.enquiries.offer', $enquiry->id) }}"
                        class="inline-flex items-center rounded-lg
                               border border-[#00665F] bg-white px-5 py-2.5
                               text-sm font-semibold text-[#00665F]
                               transition hover:bg-[#EAF5F3]"
                    >
                        Edit Offer
                    </a>

                @endif

            </div>


        </div>

    </div>

</x-seller-layout>