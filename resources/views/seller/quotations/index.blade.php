<x-seller-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-[#123B38]">
                Seller Quotations
            </h2>

            <p class="text-sm text-[#4F6663]">
                Manage quotations sent to your buyers.
            </p>

        </div>

    </x-slot>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="min-h-screen bg-[#F5F7F7]">

        <div
            class="mx-auto w-full max-w-7xl
                   px-4 py-6 sm:px-6 lg:px-8">


            {{-- =================================================
                 PAGE HEADER
            ================================================== --}}

            <div class="mb-7">

                <div
                    class="flex flex-col gap-4
                           sm:flex-row sm:items-end
                           sm:justify-between">


                    <div>

                        <h1
                            class="text-2xl font-bold
                                   leading-8 tracking-tight
                                   text-gray-900 sm:text-3xl">

                            My Quotations

                        </h1>

                        <p
                            class="mt-1.5 text-sm leading-5
                                   text-gray-500">

                            Manage quotations sent to your buyers.

                        </p>

                    </div>


                    {{-- Total Quotations --}}
                    <div
                        class="w-fit rounded-xl border
                               border-gray-200 bg-white
                               px-5 py-3 shadow-sm">

                        <p
                            class="text-xs leading-4
                                   text-gray-400">

                            Total Quotations

                        </p>

                        <p
                            class="mt-1 text-lg font-bold
                                   leading-6 text-[#00665F]">

                            {{ $quotations->total() }}

                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SUCCESS MESSAGE
            ================================================== --}}

            @if(session('success'))

            <div
                class="mb-5 flex items-start gap-3
                           rounded-xl border border-green-200
                           bg-green-50 px-4 py-3
                           text-sm leading-5 text-green-700">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7" />

                </svg>

                <span>
                    {{ session('success') }}
                </span>

            </div>

            @endif


            {{-- =================================================
                 ERROR MESSAGE
            ================================================== --}}

            @if(session('error'))

            <div
                class="mb-5 flex items-start gap-3
                           rounded-xl border border-red-200
                           bg-red-50 px-4 py-3
                           text-sm leading-5 text-red-700">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />

                </svg>

                <span>
                    {{ session('error') }}
                </span>

            </div>

            @endif


            {{-- =================================================
                 VALIDATION ERRORS
            ================================================== --}}

            @if($errors->any())

            <div
                class="mb-5 rounded-xl border
                           border-red-200 bg-red-50 px-4 py-3">

                <ul
                    class="list-disc space-y-1 pl-5
                               text-sm leading-5 text-red-600">

                    @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                    @endforeach

                </ul>

            </div>

            @endif


            {{-- =================================================
                 QUOTATIONS LIST
            ================================================== --}}

            <div class="space-y-5">

                @forelse($quotations as $quotation)

                @php

                $requirement = $quotation->requirement;

                $statusClasses = match($quotation->status) {

                'sent' =>
                'border-blue-200 bg-blue-50 text-blue-700',

                'accepted' =>
                'border-green-200 bg-green-50 text-green-700',

                'rejected' =>
                'border-red-200 bg-red-50 text-red-700',

                'countered' =>
                'border-yellow-200 bg-yellow-50 text-yellow-700',

                'expired' =>
                'border-gray-200 bg-gray-100 text-gray-600',

                default =>
                'border-gray-200 bg-gray-100 text-gray-600',

                };

                $extraCharges =
                (float) $quotation->delivery_charges +
                (float) $quotation->tax;

                @endphp


                {{-- =================================================
                         QUOTATION CARD
                    ================================================== --}}

                <div
                    class="overflow-hidden rounded-2xl
                               border border-gray-200 bg-white
                               shadow-sm transition duration-200
                               hover:shadow-md">


                    {{-- =================================================
                             CARD HEADER
                        ================================================== --}}

                    <div
                        class="border-b border-gray-100
                                   px-5 py-5 sm:px-6">

                        <div
                            class="grid grid-cols-1 gap-4
                                       sm:grid-cols-[minmax(0,1fr)_auto]
                                       sm:items-center">


                            {{-- LEFT --}}
                            <div class="min-w-0">

                                <div
                                    class="flex flex-wrap
                                               items-center gap-2">

                                    <span
                                        class="text-xs font-medium
                                                   leading-4 text-gray-400">

                                        Quotation #{{ $quotation->id }}

                                    </span>


                                    <span
                                        class="rounded-full border
                                                   px-2.5 py-1 text-[11px]
                                                   font-semibold uppercase
                                                   leading-4 tracking-wide
                                                   {{ $statusClasses }}">

                                        {{ str_replace('_', ' ', $quotation->status) }}

                                    </span>

                                </div>


                                <h2
                                    class="mt-2 truncate text-lg
                                               font-bold leading-6
                                               text-gray-900">

                                    {{ $requirement?->listing?->title
                                            ?? 'Listing Enquiry' }}

                                </h2>


                                <p
                                    class="mt-1.5 text-xs
                                               leading-4 text-gray-400">

                                    Created
                                    {{ $quotation->created_at?->format('d M Y, h:i A') }}

                                </p>

                            </div>


                            {{-- RIGHT --}}
                            <div
                                class="border-t border-gray-100
                                           pt-4 sm:border-0
                                           sm:pt-0 sm:text-right">

                                <p
                                    class="text-xs leading-4
                                               text-gray-400">

                                    Total Amount

                                </p>

                                <p
                                    class="mt-1 text-2xl font-bold
                                               leading-7 text-[#00665F]">

                                    ₹{{ number_format((float) $quotation->total_amount, 2) }}

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                             CARD BODY
                        ================================================== --}}

                    <div class="px-5 py-6 sm:px-6">


                        {{-- =================================================
                                 PRIMARY INFORMATION
                            ================================================== --}}

                        <div
                            class="grid grid-cols-1 gap-x-6
                                       gap-y-6 sm:grid-cols-2
                                       lg:grid-cols-4">


                            {{-- Buyer --}}
                            <div class="min-w-0">

                                <p
                                    class="text-[11px] font-semibold
                                               uppercase leading-4
                                               tracking-wider
                                               text-gray-400">

                                    Buyer

                                </p>

                                <p
                                    class="mt-2 truncate text-sm
                                               font-semibold leading-5
                                               text-gray-900">

                                    {{ $quotation->buyer?->name
                                            ?? $requirement?->name
                                            ?? 'N/A' }}

                                </p>

                            </div>


                            {{-- Requirement --}}
                            <div class="min-w-0">

                                <p
                                    class="text-[11px] font-semibold
                                               uppercase leading-4
                                               tracking-wider
                                               text-gray-400">

                                    Requirement

                                </p>

                                <p
                                    class="mt-2 truncate text-sm
                                               font-semibold capitalize
                                               leading-5 text-gray-900">

                                    {{ $requirement?->requirement_type
                                            ? str_replace('_', ' ', $requirement->requirement_type)
                                            : 'N/A' }}

                                </p>

                            </div>


                            {{-- Quantity --}}
                            <div class="min-w-0">

                                <p
                                    class="text-[11px] font-semibold
                                               uppercase leading-4
                                               tracking-wider
                                               text-gray-400">

                                    Quantity

                                </p>

                                <p
                                    class="mt-2 text-sm font-semibold
                                               leading-5 text-gray-900">

                                    {{ number_format((float) $quotation->quantity, 2) }}

                                    <span
                                        class="ml-1 font-normal
                                                   text-gray-500">

                                        {{ $quotation->unit }}

                                    </span>

                                </p>

                            </div>


                            {{-- Unit Price --}}
                            <div class="min-w-0">

                                <p
                                    class="text-[11px] font-semibold
                                               uppercase leading-4
                                               tracking-wider
                                               text-gray-400">

                                    Unit Price

                                </p>

                                <p
                                    class="mt-2 text-sm font-semibold
                                               leading-5 text-gray-900">

                                    ₹{{ number_format((float) $quotation->unit_price, 2) }}

                                </p>

                            </div>

                        </div>


                        {{-- =================================================
                                 SECONDARY INFORMATION
                            ================================================== --}}

                        <div
                            class="mt-6 grid grid-cols-1 gap-5
                                       border-t border-gray-100
                                       pt-6 sm:grid-cols-3">


                            {{-- Delivery Time --}}
                            <div
                                class="flex min-h-[52px]
                                           items-center gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-lg bg-[#EAF5F3]
                                               text-[#00665F]">

                                    <svg
                                        class="h-4 w-4"
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


                                <div class="min-w-0">

                                    <p
                                        class="text-[11px]
                                                   font-semibold uppercase
                                                   leading-4 tracking-wide
                                                   text-gray-400">

                                        Delivery Time

                                    </p>

                                    <p
                                        class="mt-1 truncate text-sm
                                                   font-semibold
                                                   leading-5 text-gray-800">

                                        {{ $quotation->delivery_time
                                                ?? 'Not specified' }}

                                    </p>

                                </div>

                            </div>


                            {{-- Valid Until --}}
                            <div
                                class="flex min-h-[52px]
                                           items-center gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-lg bg-[#EAF5F3]
                                               text-[#00665F]">

                                    <svg
                                        class="h-4 w-4"
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


                                <div class="min-w-0">

                                    <p
                                        class="text-[11px]
                                                   font-semibold uppercase
                                                   leading-4 tracking-wide
                                                   text-gray-400">

                                        Valid Until

                                    </p>

                                    <p
                                        class="mt-1 truncate text-sm
                                                   font-semibold
                                                   leading-5 text-gray-800">

                                        {{ $quotation->valid_until
                                                ? $quotation->valid_until->format('d M Y')
                                                : 'N/A' }}

                                    </p>

                                </div>

                            </div>


                            {{-- Extra Charges --}}
                            <div
                                class="flex min-h-[52px]
                                           items-center gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-lg bg-[#EAF5F3]
                                               text-[#00665F]">

                                    <span
                                        class="text-sm font-bold">

                                        ₹

                                    </span>

                                </div>


                                <div class="min-w-0">

                                    <p
                                        class="text-[11px]
                                                   font-semibold uppercase
                                                   leading-4 tracking-wide
                                                   text-gray-400">

                                        Extra Charges

                                    </p>

                                    <p
                                        class="mt-1 truncate text-sm
                                                   font-semibold
                                                   leading-5 text-gray-800">

                                        ₹{{ number_format($extraCharges, 2) }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                                 BUYER MESSAGE
                            ================================================== --}}

                        @if($requirement?->message)

                        <div
                            class="mt-6 rounded-xl border
                                           border-gray-100
                                           bg-gray-50 p-4">

                            <p
                                class="text-[11px] font-semibold
                                               uppercase leading-4
                                               tracking-wider
                                               text-gray-400">

                                Buyer Message

                            </p>

                            <p
                                class="mt-2 line-clamp-2 text-sm
                                               leading-6 text-gray-600">

                                {{ $requirement->message }}

                            </p>

                        </div>

                        @endif


                        {{-- =================================================
                                 ACTIONS
                            ================================================== --}}

                        <div
                            class="mt-6 flex flex-col gap-3
                                       border-t border-gray-100
                                       pt-5 sm:flex-row
                                       sm:items-center
                                       sm:justify-end">


                            {{-- View Details --}}
                            <a
                                href="{{ route('seller.quotations.show', $quotation->id) }}"
                                class="inline-flex h-10 w-full
                                           items-center justify-center
                                           gap-2 rounded-lg border
                                           border-gray-200 bg-white
                                           px-5 text-sm font-semibold
                                           leading-5 text-gray-700
                                           transition
                                           hover:border-[#00665F]
                                           hover:text-[#00665F]
                                           sm:w-auto">

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                </svg>

                                View Details

                            </a>


                            {{-- Edit Quotation --}}
                            @if($quotation->status === 'sent' && $quotation->rfq)

                            <a
                                href="{{ route('seller.rfqs.quotation.create', $quotation->rfq->id) }}"
                                class="inline-flex h-10 w-full
               items-center justify-center
               gap-2 rounded-lg
               bg-[#00665F] px-5
               text-sm font-semibold
               leading-5 text-white
               transition
               hover:bg-[#00534E]
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

                </div>


                @empty

                {{-- =================================================
                         EMPTY STATE
                    ================================================== --}}

                <div
                    class="rounded-2xl border border-gray-200
                               bg-white px-5 py-16 text-center
                               shadow-sm">

                    <div
                        class="mx-auto flex h-14 w-14
                                   items-center justify-center
                                   rounded-full bg-[#EAF5F3]">

                        <svg
                            class="h-7 w-7 text-[#00665F]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />

                        </svg>

                    </div>


                    <h3
                        class="mt-5 text-lg font-bold
                                   leading-6 text-gray-900">

                        No Quotations Found

                    </h3>


                    <p
                        class="mx-auto mt-2 max-w-md
                                   text-sm leading-6 text-gray-500">

                        You have not sent any quotations to
                        buyers yet. Quotations created from
                        buyer enquiries will appear here.

                    </p>


                    <a
                        href="{{ route('seller.enquiries.index') }}"
                        class="mt-5 inline-flex items-center
                                   rounded-lg bg-[#00665F]
                                   px-5 py-2.5 text-sm font-semibold
                                   text-white transition
                                   hover:bg-[#00534E]">

                        View Buyer Enquiries

                    </a>

                </div>

                @endforelse

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}

            @if($quotations->hasPages())

            <div class="mt-7">

                {{ $quotations->links() }}

            </div>

            @endif

        </div>

    </div>

</x-seller-layout>