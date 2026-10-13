<x-seller-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-[#123B38]">
                Quotation Details
            </h2>

            <p class="text-sm text-[#4F6663]">
                View quotation and buyer requirement details.
            </p>

        </div>

    </x-slot>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="min-h-screen bg-[#F5F9F8] py-6">

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">


            {{-- =================================================
                 PAGE HEADER
            ================================================== --}}

            <div class="mb-6">

                <div
                    class="flex flex-col gap-4
                           sm:flex-row sm:items-center
                           sm:justify-between">

                    <div>

                        <a
                            href="{{ route('seller.quotations.index') }}"
                            class="mb-3 inline-flex items-center gap-2
                                   text-sm font-medium text-gray-500
                                   transition hover:text-[#00665F]">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19l-7-7 7-7" />

                            </svg>

                            Back to Quotations

                        </a>


                        <div class="flex flex-wrap items-center gap-3">

                            <h1
                                class="text-2xl font-bold text-gray-900
                                       sm:text-3xl">

                                Quotation #{{ $quotation->id }}

                            </h1>


                            @php

                            $statusClasses = match($quotation->status) {

                            'sent' =>
                            'bg-blue-50 text-blue-700 border-blue-200',

                            'accepted' =>
                            'bg-green-50 text-green-700 border-green-200',

                            'rejected' =>
                            'bg-red-50 text-red-700 border-red-200',

                            'countered' =>
                            'bg-yellow-50 text-yellow-700 border-yellow-200',

                            'expired' =>
                            'bg-gray-100 text-gray-600 border-gray-200',

                            default =>
                            'bg-gray-100 text-gray-600 border-gray-200',

                            };

                            @endphp


                            <span
                                class="rounded-full border px-3 py-1
                                       text-xs font-semibold uppercase
                                       tracking-wide {{ $statusClasses }}">

                                {{ ucfirst($quotation->status) }}

                            </span>

                        </div>


                        <p class="mt-1 text-sm text-gray-500">

                            Created
                            {{ $quotation->created_at?->format('d M Y, h:i A') }}

                        </p>

                    </div>


                    {{-- Edit Quotation --}}
                    @if($quotation->status === 'sent' && $quotation->rfq)

                    <a
                        href="{{ route('seller.rfqs.quotation.create', $quotation->rfq->id) }}"
                        class="inline-flex w-full items-center
               justify-center gap-2 rounded-lg
               bg-[#00665F] px-5 py-2.5
               text-sm font-semibold
               text-white shadow-sm
               transition hover:bg-[#00544F]
               sm:w-auto">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1-1-4 9.5-9.5z" />
                        </svg>

                        Edit Quotation

                    </a>

                    @endif
                </div>

            </div>


            {{-- =================================================
                 LISTING SUMMARY
            ================================================== --}}

            <div
                class="mb-6 overflow-hidden rounded-2xl border
                       border-gray-200 bg-white shadow-sm">

                <div class="p-5 sm:p-6 lg:p-7">

                    <div
                        class="flex flex-col gap-5
                               lg:flex-row lg:items-center
                               lg:justify-between">

                        <div class="min-w-0">

                            <p
                                class="text-xs font-semibold uppercase
                                       tracking-wider text-[#00665F]">

                                Listing

                            </p>

                            <h2
                                class="mt-2 truncate text-xl font-bold
                                       text-gray-900 sm:text-2xl">

                                {{ $quotation->requirement?->listing?->title
                                    ?? 'Listing Requirement' }}

                            </h2>

                        </div>


                        {{-- Total --}}
                        <div
                            class="rounded-xl bg-[#EAF5F3] px-5 py-4
                                   lg:min-w-[210px]">

                            <p class="text-xs font-medium text-gray-500">
                                Quotation Total
                            </p>

                            <p
                                class="mt-1 text-2xl font-bold
                                       text-[#00665F]">

                                ₹{{ number_format((float) $quotation->total_amount, 2) }}

                            </p>

                        </div>

                    </div>


                    {{-- Listing Information --}}
                    <div
                        class="mt-6 grid grid-cols-2 gap-5
                               border-t border-gray-100 pt-5
                               sm:grid-cols-4">

                        <div>

                            <p class="text-xs text-gray-400">
                                Quantity
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold
                                       text-gray-900">

                                {{ $quotation->quantity }}
                                {{ $quotation->unit }}

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-400">
                                Unit Price
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold
                                       text-gray-900">

                                ₹{{ number_format((float) $quotation->unit_price, 2) }}

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-400">
                                Delivery Time
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold
                                       text-gray-900">

                                {{ $quotation->delivery_time ?? 'Not specified' }}

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-400">
                                Valid Until
                            </p>

                            <p
                                class="mt-1 text-sm font-semibold
                                       text-gray-900">

                                {{ $quotation->valid_until?->format('d M Y') ?? 'N/A' }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 MAIN CONTENT
            ================================================== --}}

            <div
                class="grid grid-cols-1 gap-6 lg:grid-cols-12">


                {{-- =================================================
                     LEFT COLUMN
                ================================================== --}}

                <div class="space-y-6 lg:col-span-7">


                    {{-- =================================================
                         BUYER INFORMATION
                    ================================================== --}}

                    <div
                        class="overflow-hidden rounded-2xl border
                               border-gray-200 bg-white shadow-sm">

                        <div
                            class="border-b border-gray-100
                                   px-5 py-5 sm:px-6">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-9 w-9 items-center
                                           justify-center rounded-lg
                                           bg-[#EAF5F3] text-[#00665F]">

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                                    </svg>

                                </div>


                                <div>

                                    <h2
                                        class="text-base font-bold
                                               text-gray-900">

                                        Buyer Information

                                    </h2>

                                    <p class="text-xs text-gray-400">
                                        Buyer details
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div
                            class="grid grid-cols-1 gap-x-8 gap-y-6
                                   p-5 sm:grid-cols-2 sm:p-6">

                            <div>

                                <p
                                    class="text-xs font-medium uppercase
                                           tracking-wide text-gray-400">

                                    Buyer Name

                                </p>

                                <p
                                    class="mt-1.5 text-sm font-semibold
                                           text-gray-900">

                                    {{ $quotation->buyer?->name
                                        ?? $quotation->requirement?->name
                                        ?? 'N/A' }}

                                </p>

                            </div>


                            <div>

                                <p
                                    class="text-xs font-medium uppercase
                                           tracking-wide text-gray-400">

                                    Email

                                </p>

                                <p
                                    class="mt-1.5 break-all text-sm
                                           font-medium text-gray-800">

                                    {{ $quotation->buyer?->email
                                        ?? $quotation->requirement?->email
                                        ?? 'N/A' }}

                                </p>

                            </div>


                            <div>

                                <p
                                    class="text-xs font-medium uppercase
                                           tracking-wide text-gray-400">

                                    Phone

                                </p>

                                <p
                                    class="mt-1.5 text-sm font-medium
                                           text-gray-800">

                                    {{ $quotation->requirement?->phone
                                        ?? 'Not provided' }}

                                </p>

                            </div>


                            <div>

                                <p
                                    class="text-xs font-medium uppercase
                                           tracking-wide text-gray-400">

                                    Requirement Date

                                </p>

                                <p
                                    class="mt-1.5 text-sm font-medium
                                           text-gray-800">

                                    {{ $quotation->requirement?->required_date?->format('d M Y')
                                        ?? 'Not specified' }}

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         BUYER REQUIREMENT
                    ================================================== --}}

                    <div
                        class="overflow-hidden rounded-2xl border
                               border-gray-200 bg-white shadow-sm">

                        <div
                            class="border-b border-gray-100
                                   px-5 py-5 sm:px-6">

                            <h2
                                class="text-base font-bold text-gray-900">

                                Buyer Requirement

                            </h2>

                            <p class="mt-1 text-xs text-gray-400">
                                Requirement submitted by the buyer
                            </p>

                        </div>


                        <div class="p-5 sm:p-6">

                            <div
                                class="grid grid-cols-1 gap-4
                                       sm:grid-cols-3">


                                {{-- Quantity --}}
                                <div
                                    class="rounded-xl bg-gray-50 p-4">

                                    <p
                                        class="text-xs font-medium uppercase
                                               tracking-wide text-gray-400">

                                        Quantity

                                    </p>

                                    <p
                                        class="mt-2 text-lg font-bold
                                               text-gray-900">

                                        {{ $quotation->quantity }}

                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $quotation->unit }}
                                    </p>

                                </div>


                                {{-- Delivery Location --}}
                                <div
                                    class="rounded-xl bg-gray-50 p-4">

                                    <p
                                        class="text-xs font-medium uppercase
                                               tracking-wide text-gray-400">

                                        Delivery Location

                                    </p>

                                    <p
                                        class="mt-2 break-words text-sm
                                               font-semibold text-gray-900">

                                        {{ $quotation->requirement?->delivery_location
                                            ?? 'Not specified' }}

                                    </p>

                                </div>


                                {{-- Required Date --}}
                                <div
                                    class="rounded-xl bg-gray-50 p-4">

                                    <p
                                        class="text-xs font-medium uppercase
                                               tracking-wide text-gray-400">

                                        Required Date

                                    </p>

                                    <p
                                        class="mt-2 text-sm font-semibold
                                               text-gray-900">

                                        {{ $quotation->requirement?->required_date?->format('d M Y')
                                            ?? 'Not specified' }}

                                    </p>

                                </div>

                            </div>


                            {{-- Buyer Message --}}
                            @if($quotation->requirement?->message)

                            <div class="mt-6">

                                <p
                                    class="mb-2 text-sm font-semibold
                                               text-gray-800">

                                    Buyer Message

                                </p>

                                <div
                                    class="rounded-xl border
                                               border-gray-200 bg-gray-50
                                               p-4">

                                    <p
                                        class="text-sm leading-6
                                                   text-gray-600">

                                        {{ $quotation->requirement->message }}

                                    </p>

                                </div>

                            </div>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                         SELLER NOTES
                    ================================================== --}}

                    @if($quotation->notes)

                    <div
                        class="overflow-hidden rounded-2xl border
                                   border-gray-200 bg-white shadow-sm">

                        <div
                            class="border-b border-gray-100
                                       px-5 py-5 sm:px-6">

                            <h2
                                class="text-base font-bold
                                           text-gray-900">

                                Seller Notes

                            </h2>

                        </div>

                        <div class="p-5 sm:p-6">

                            <div
                                class="rounded-xl bg-[#F5FAF9] p-4">

                                <p
                                    class="text-sm leading-6
                                               text-gray-600">

                                    {{ $quotation->notes }}

                                </p>

                            </div>

                        </div>

                    </div>

                    @endif

                </div>


                {{-- =================================================
                     RIGHT COLUMN
                ================================================== --}}

                <div class="space-y-6 lg:col-span-5">


                    {{-- =================================================
                         QUOTATION DETAILS
                    ================================================== --}}

                    <div
                        class="overflow-hidden rounded-2xl border
                               border-gray-200 bg-white shadow-sm">

                        <div
                            class="border-b border-gray-100
                                   px-5 py-5 sm:px-6">

                            <h2
                                class="text-base font-bold
                                       text-gray-900">

                                Quotation Details

                            </h2>

                            <p class="mt-1 text-xs text-gray-400">
                                Important quotation information
                            </p>

                        </div>


                        <div class="space-y-5 p-5 sm:p-6">


                            {{-- Valid Until --}}
                            <div class="flex items-center gap-4">

                                <div
                                    class="flex h-10 w-10 shrink-0
                                           items-center justify-center
                                           rounded-lg bg-[#EAF5F3]
                                           text-[#00665F]">

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <rect
                                            x="3"
                                            y="4"
                                            width="18"
                                            height="18"
                                            rx="2"
                                            stroke-width="2" />

                                        <line
                                            x1="16"
                                            y1="2"
                                            x2="16"
                                            y2="6"
                                            stroke-width="2" />

                                        <line
                                            x1="8"
                                            y1="2"
                                            x2="8"
                                            y2="6"
                                            stroke-width="2" />

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-xs text-gray-400">
                                        Valid Until
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-semibold
                                               text-gray-900">

                                        {{ $quotation->valid_until?->format('d M Y')
                                            ?? 'N/A' }}

                                    </p>

                                </div>

                            </div>


                            {{-- Delivery --}}
                            <div class="flex items-center gap-4">

                                <div
                                    class="flex h-10 w-10 shrink-0
                                           items-center justify-center
                                           rounded-lg bg-[#EAF5F3]
                                           text-[#00665F]">

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                            stroke-width="2" />

                                        <path
                                            stroke-linecap="round"
                                            stroke-width="2"
                                            d="M12 7v5l3 2" />

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-xs text-gray-400">
                                        Delivery Time
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-semibold
                                               text-gray-900">

                                        {{ $quotation->delivery_time
                                            ?? 'Not specified' }}

                                    </p>

                                </div>

                            </div>


                            {{-- Unit Price --}}
                            <div class="flex items-center gap-4">

                                <div
                                    class="flex h-10 w-10 shrink-0
                                           items-center justify-center
                                           rounded-lg bg-[#EAF5F3]
                                           text-[#00665F]">

                                    <span
                                        class="text-base font-bold">

                                        ₹

                                    </span>

                                </div>


                                <div>

                                    <p class="text-xs text-gray-400">
                                        Unit Price
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-semibold
                                               text-gray-900">

                                        ₹{{ number_format((float) $quotation->unit_price, 2) }}

                                        <span
                                            class="text-xs font-normal
                                                   text-gray-400">

                                            / {{ $quotation->unit }}

                                        </span>

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         QUOTATION TIMELINE
                    ================================================== --}}

                    <div
                        class="overflow-hidden rounded-2xl border
                               border-gray-200 bg-white shadow-sm">

                        <div
                            class="border-b border-gray-100
                                   px-5 py-5 sm:px-6">

                            <h2
                                class="text-base font-bold
                                       text-gray-900">

                                Quotation Timeline

                            </h2>

                        </div>


                        <div class="p-5 sm:p-6">

                            <div class="relative">


                                {{-- Created --}}
                                <div class="flex gap-4">

                                    <div
                                        class="relative flex flex-col
                                               items-center">

                                        <span
                                            class="z-10 h-3 w-3 rounded-full
                                                   bg-[#00665F]">
                                        </span>

                                        <span
                                            class="absolute top-3 h-16
                                                   w-px bg-gray-200">
                                        </span>

                                    </div>


                                    <div class="pb-7">

                                        <p
                                            class="text-sm font-semibold
                                                   text-gray-900">

                                            Quotation Created

                                        </p>

                                        <p
                                            class="mt-1 text-xs
                                                   text-gray-400">

                                            {{ $quotation->created_at?->format('d M Y, h:i A') }}

                                        </p>

                                    </div>

                                </div>


                                {{-- Response --}}
                                <div class="flex gap-4">

                                    <div
                                        class="flex flex-col items-center">

                                        <span
                                            class="z-10 h-3 w-3
                                                   rounded-full
                                                   {{ in_array($quotation->status, ['accepted', 'rejected'])
                                                        ? 'bg-[#00665F]'
                                                        : 'bg-gray-300' }}">
                                        </span>

                                    </div>


                                    <div>

                                        <p
                                            class="text-sm font-semibold
                                                   text-gray-700">

                                            Buyer Response

                                        </p>

                                        <p
                                            class="mt-1 text-xs
                                                   text-gray-400">

                                            @if($quotation->status === 'accepted')

                                            Quotation accepted by buyer

                                            @elseif($quotation->status === 'rejected')

                                            Quotation rejected by buyer

                                            @elseif($quotation->status === 'expired')

                                            Quotation expired

                                            @elseif($quotation->status === 'countered')

                                            Buyer has submitted a counter offer

                                            @else

                                            Awaiting buyer response

                                            @endif

                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         PRICE SUMMARY
                    ================================================== --}}

                    <div
                        class="overflow-hidden rounded-2xl border
                               border-[#CDE5E1] bg-white shadow-sm">

                        <div
                            class="bg-[#00665F] px-5 py-5 text-white
                                   sm:px-6">

                            <p
                                class="text-xs font-medium uppercase
                                       tracking-wider text-white/70">

                                Quotation Amount

                            </p>

                            <p class="mt-1 text-3xl font-bold">

                                ₹{{ number_format((float) $quotation->total_amount, 2) }}

                            </p>

                        </div>


                        <div class="space-y-4 p-5 sm:p-6">


                            {{-- Subtotal --}}
                            <div
                                class="flex items-center
                                       justify-between gap-4 text-sm">

                                <span class="text-gray-500">
                                    Subtotal
                                </span>

                                <span
                                    class="font-medium text-gray-900">

                                    ₹{{ number_format(
                                        (float) $quotation->quantity *
                                        (float) $quotation->unit_price,
                                        2
                                    ) }}

                                </span>

                            </div>


                            {{-- Delivery --}}
                            <div
                                class="flex items-center
                                       justify-between gap-4 text-sm">

                                <span class="text-gray-500">
                                    Delivery Charges
                                </span>

                                <span
                                    class="font-medium text-gray-900">

                                    ₹{{ number_format(
                                        (float) $quotation->delivery_charges,
                                        2
                                    ) }}

                                </span>

                            </div>


                            {{-- Tax --}}
                            <div
                                class="flex items-center
                                       justify-between gap-4 text-sm">

                                <span class="text-gray-500">
                                    Tax
                                </span>

                                <span
                                    class="font-medium text-gray-900">

                                    ₹{{ number_format(
                                        (float) $quotation->tax,
                                        2
                                    ) }}

                                </span>

                            </div>


                            {{-- Total --}}
                            <div
                                class="border-t border-gray-100 pt-4">

                                <div
                                    class="flex items-center
                                           justify-between gap-4">

                                    <span
                                        class="font-bold text-gray-900">

                                        Total

                                    </span>

                                    <span
                                        class="text-xl font-bold
                                               text-[#00665F]">

                                        ₹{{ number_format(
                                            (float) $quotation->total_amount,
                                            2
                                        ) }}

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div
                class="mt-6 flex flex-col gap-2 text-xs text-gray-400
                       sm:flex-row sm:items-center
                       sm:justify-between">

                <span>
                    Quotation #{{ $quotation->id }}
                </span>

                <span>
                    Total ₹{{ number_format(
                        (float) $quotation->total_amount,
                        2
                    ) }}
                </span>

            </div>

        </div>

    </div>

</x-seller-layout>