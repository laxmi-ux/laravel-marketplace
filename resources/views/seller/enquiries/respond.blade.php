<x-seller-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-[#123B38]">
                Respond to Buyer
            </h2>

            <p class="text-sm text-[#4F6663]">
                Respond to the buyer regarding their enquiry
            </p>

        </div>

    </x-slot>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="bg-[#F7FAF9] px-4 py-6 sm:px-5 sm:py-8 lg:px-6">

        <div class="mx-auto w-full max-w-5xl">


            {{-- Back --}}
            <div class="mb-6">

                <a
                    href="{{ route('seller.enquiries.index') }}"
                    class="inline-flex items-center text-sm font-medium text-[#00665F] hover:underline"
                >
                    ← Back to Buyer Enquiries
                </a>

            </div>


            {{-- Page Header --}}
            <div class="mb-8">

                <h1 class="text-2xl font-bold text-[#163B37]">
                    {{ $enquiry->seller_response ? 'Update Response' : 'Respond to Buyer' }}
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $enquiry->seller_response
                        ? 'Update your response to the buyer.'
                        : 'Send a response regarding this enquiry.' }}
                </p>

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
                 VALIDATION ERRORS
            ====================================================== --}}

            @if($errors->any())

                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">

                    <p class="mb-2 text-sm font-semibold text-red-700">
                        Please correct the following:
                    </p>

                    <ul class="list-inside list-disc space-y-1 text-sm text-red-600">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =====================================================
                 ENQUIRY DETAILS
            ====================================================== --}}

            <div class="mb-6 rounded-2xl border border-[#DCE9E6] bg-white p-5 shadow-sm sm:p-6">

                <div class="mb-5">

                    <div class="flex flex-wrap items-center gap-3">

                        <h2 class="text-lg font-semibold text-[#163B37]">
                            Enquiry Details
                        </h2>

                        @php

                            $status = $enquiry->status;

                            $statusClasses = match ($status) {

                                'new'
                                    => 'border-blue-200 bg-blue-50 text-blue-700',

                                'contacted'
                                    => 'border-yellow-200 bg-yellow-50 text-yellow-700',

                                'quotation_sent'
                                    => 'border-purple-200 bg-purple-50 text-purple-700',

                                'closed'
                                    => 'border-green-200 bg-green-50 text-green-700',

                                default
                                    => 'border-gray-200 bg-gray-50 text-gray-700',

                            };

                            $statusLabel = match ($status) {

                                'new'
                                    => 'New',

                                'contacted'
                                    => 'Contacted',

                                'quotation_sent'
                                    => 'Quotation Sent',

                                'closed'
                                    => 'Closed',

                                default
                                    => ucfirst(str_replace('_', ' ', $status)),

                            };

                        @endphp

                        <span
                            class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses }}"
                        >
                            {{ $statusLabel }}
                        </span>

                    </div>

                    <p class="mt-1 text-xs text-gray-500">
                        Enquiry #{{ $enquiry->id }}
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                    {{-- Buyer --}}
                    <div>

                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Buyer
                        </p>

                        <p class="font-medium text-gray-900">

                            {{ optional($enquiry->user)->name
                                ?? $enquiry->name
                                ?? 'N/A' }}

                        </p>

                    </div>


                    {{-- Email --}}
                    <div>

                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Email
                        </p>

                        <p class="break-all font-medium text-gray-900">

                            {{ optional($enquiry->user)->email
                                ?? $enquiry->email
                                ?? 'N/A' }}

                        </p>

                    </div>


                    {{-- Phone --}}
                    <div>

                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Phone
                        </p>

                        <p class="font-medium text-gray-900">

                            {{ $enquiry->phone ?? 'Not provided' }}

                        </p>

                    </div>


                    {{-- Listing --}}
                    <div>

                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Listing
                        </p>

                        <p class="font-medium text-gray-900">

                            {{ optional($enquiry->listing)->title ?? 'Listing' }}

                        </p>

                    </div>


                    {{-- Listing ID --}}
                    <div>

                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Listing ID
                        </p>

                        <p class="font-medium text-gray-900">

                            #{{ $enquiry->listing_id }}

                        </p>

                    </div>


                    {{-- Quantity --}}
                    <div>

                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Quantity
                        </p>

                        <p class="font-medium text-gray-900">

                            @if($enquiry->quantity !== null)

                                {{ $enquiry->quantity }}

                                @if($enquiry->unit)
                                    {{ $enquiry->unit }}
                                @endif

                            @else

                                Not specified

                            @endif

                        </p>

                    </div>


                    {{-- Status --}}
                    <div>

                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Status
                        </p>

                        <p class="font-medium text-gray-900">
                            {{ $statusLabel }}
                        </p>

                    </div>


                    {{-- Received --}}
                    <div>

                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Received
                        </p>

                        <p class="font-medium text-gray-900">

                            {{ $enquiry->created_at?->format('d M Y, h:i A') }}

                        </p>

                    </div>

                </div>


                {{-- Buyer Message --}}
                @if($enquiry->message)

                    <div class="mt-6">

                        <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500">
                            Buyer's Message
                        </p>

                        <div class="rounded-xl border border-[#E1ECE9] bg-[#F9FCFB] p-4 text-sm leading-6 text-gray-700">

                            {{ $enquiry->message }}

                        </div>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 PREVIOUS SELLER RESPONSE
            ====================================================== --}}

            @if($enquiry->seller_response)

                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-5 shadow-sm sm:p-6">

                    <div class="mb-3">

                        <h2 class="text-lg font-semibold text-green-800">
                            Previous Response
                        </h2>

                        <p class="mt-1 text-sm text-green-700">
                            Your previous response to this buyer.
                        </p>

                    </div>

                    <div class="rounded-xl border border-green-200 bg-white p-4">

                        <p class="whitespace-pre-line text-sm leading-6 text-gray-700">

                            {{ $enquiry->seller_response }}

                        </p>

                    </div>

                    @if($enquiry->responded_at)

                        <p class="mt-3 text-xs text-green-700">

                            Responded on
                            {{ $enquiry->responded_at->format('d M Y, h:i A') }}

                        </p>

                    @endif

                </div>

            @endif


            {{-- =====================================================
                 RESPONSE FORM
            ====================================================== --}}

            <div class="rounded-2xl border border-[#DCE9E6] bg-white p-5 shadow-sm sm:p-6">

                <div class="mb-5">

                    <h2 class="text-lg font-semibold text-[#163B37]">

                        {{ $enquiry->seller_response
                            ? 'Update Your Response'
                            : 'Write Your Response' }}

                    </h2>

                    <p class="mt-1 text-sm text-gray-500">

                        Your response will be saved directly to this buyer enquiry.

                    </p>

                </div>


                <form
                    action="{{ route(
                        'seller.enquiries.respond.store',
                        $enquiry->id
                    ) }}"
                    method="POST"
                >

                    @csrf


                    {{-- Response --}}
                    <div>

                        <label
                            for="response"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Response <span class="text-red-500">*</span>
                        </label>


                        <textarea
                            id="response"
                            name="response"
                            rows="7"
                            required
                            maxlength="3000"
                            placeholder="Write your response to the buyer..."
                            class="w-full rounded-xl border border-gray-300 px-4 py-3
                                   text-sm text-gray-800 shadow-sm
                                   focus:border-[#00665F]
                                   focus:ring-[#00665F]"
                        >{{ old('response', $enquiry->seller_response) }}</textarea>


                        <div class="mt-2 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">

                            <p class="text-xs text-gray-500">
                                Maximum 3000 characters.
                            </p>

                            <p class="text-xs text-gray-400">
                                Your response will be saved with this enquiry.
                            </p>

                        </div>

                    </div>


                    {{-- Buttons --}}
                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                        <a
                            href="{{ route('seller.enquiries.show', $enquiry->id) }}"
                            class="inline-flex items-center justify-center rounded-lg
                                   border border-gray-300 bg-white px-5 py-2.5
                                   text-sm font-medium text-gray-700
                                   transition hover:bg-gray-50"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg
                                   bg-[#00665F] px-5 py-2.5
                                   text-sm font-semibold text-white
                                   transition hover:bg-[#00534E]"
                        >

                            {{ $enquiry->seller_response
                                ? 'Update Response'
                                : 'Send Response' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-seller-layout>
