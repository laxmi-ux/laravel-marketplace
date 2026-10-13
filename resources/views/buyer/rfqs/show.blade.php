<x-buyer-layout>

<x-slot name="header">
    <div class="flex items-center gap-3">

        <a href="{{ route('buyer.rfqs.index') }}"
           class="flex h-9 w-9 items-center justify-center rounded-lg text-[#056663] hover:bg-[#E8F5F4]">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                RFQ Details
            </h2>

            <p class="text-sm text-gray-500">
                View requirement, seller quotation and conversation
            </p>
        </div>

    </div>
</x-slot>


<div class="mx-auto max-w-6xl px-4 py-8">

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- ERROR --}}
    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- RFQ HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6 overflow-hidden rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

        <div class="bg-[#056663] px-6 py-6 text-white">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm text-white/75">
                        Request for Quotation
                    </p>

                    <h1 class="mt-1 text-2xl font-bold">
                        RFQ #{{ $rfq->id }}
                    </h1>

                    @if($rfq->sent_at)
                        <p class="mt-2 text-sm text-white/75">
                            Sent on {{ $rfq->sent_at->format('d M Y, h:i A') }}
                        </p>
                    @endif

                </div>


                @php
                    $statusClasses = [
                        'new'      => 'bg-blue-100 text-blue-700',
                        'viewed'   => 'bg-yellow-100 text-yellow-700',
                        'quoted'   => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        'expired'  => 'bg-gray-100 text-gray-700',
                        'closed'   => 'bg-gray-100 text-gray-700',
                    ];

                    $statusClass = $statusClasses[$rfq->status]
                        ?? 'bg-gray-100 text-gray-700';
                @endphp

                <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                </span>

            </div>

        </div>


        {{-- SUMMARY --}}
        <div class="grid gap-5 p-6 md:grid-cols-3">

            {{-- Seller --}}
            <div class="rounded-xl border border-[#D8E7E4] bg-[#F8FCFB] p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Seller
                </p>

                @if($rfq->seller)

                    <p class="mt-2 font-semibold text-gray-900">
                        {{ $rfq->seller->bussiness_name ?? 'Seller' }}
                    </p>

                    @if($rfq->seller->location)
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $rfq->seller->location }}
                        </p>
                    @endif

                @else

                    <p class="mt-2 font-semibold text-red-600">
                        Seller information unavailable
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Seller ID: {{ $rfq->seller_id }}
                    </p>

                @endif

            </div>


            {{-- Requirement --}}
            <div class="rounded-xl border border-[#D8E7E4] bg-[#F8FCFB] p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Requirement
                </p>

                <p class="mt-2 font-semibold capitalize text-gray-900">
                    {{ str_replace('_', ' ', $requirement->requirement_type ?? 'General') }}
                </p>

            </div>


            {{-- Source --}}
            <div class="rounded-xl border border-[#D8E7E4] bg-[#F8FCFB] p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Source
                </p>

                <p class="mt-2 font-semibold capitalize text-gray-900">
                    {{ str_replace('_', ' ', $requirement->requirement_source ?? 'General') }}
                </p>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- REQUIREMENT DETAILS --}}
    {{-- ========================================================= --}}

    <div class="mb-6 rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

        <div class="border-b border-[#E4EFED] px-6 py-5">

            <h2 class="text-lg font-bold text-gray-900">
                Requirement Details
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Details submitted by you
            </p>

        </div>


        <div class="grid gap-6 p-6 md:grid-cols-2">

            {{-- Related Listing --}}
            @if($requirement->listing)

                <div class="md:col-span-2">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Related Listing
                    </p>

                    <div class="mt-2 rounded-xl border border-[#D8E7E4] bg-[#F8FCFB] p-4">

                        <p class="font-semibold text-[#056663]">
                            {{ $requirement->listing->title }}
                        </p>

                    </div>

                </div>

            @endif


            {{-- Name --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Name
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $requirement->name ?? '—' }}
                </p>
            </div>


            {{-- Email --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Email
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $requirement->email ?? '—' }}
                </p>
            </div>


            {{-- Phone --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Phone
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $requirement->phone ?? '—' }}
                </p>
            </div>


            {{-- Quantity --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Quantity
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $requirement->quantity ?? '—' }}
                    {{ $requirement->unit ?? '' }}
                </p>
            </div>


            {{-- Budget --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Budget
                </p>

                <p class="mt-1 font-medium text-gray-900">

                    @if($requirement->budget !== null)
                        ₹{{ number_format((float) $requirement->budget, 2) }}
                    @else
                        —
                    @endif

                </p>
            </div>


            {{-- Delivery Location --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Delivery Location
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $requirement->delivery_location ?? '—' }}
                </p>
            </div>


            {{-- Required Date --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Required Date
                </p>

                <p class="mt-1 font-medium text-gray-900">

                    @if($requirement->required_date)
                        {{ \Carbon\Carbon::parse($requirement->required_date)->format('d M Y') }}
                    @else
                        —
                    @endif

                </p>

            </div>


            {{-- Message --}}
            @if($requirement->message)

                <div class="md:col-span-2">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Requirement Message
                    </p>

                    <div class="mt-2 rounded-xl bg-[#F8FCFB] p-4 text-sm leading-6 text-gray-700">
                        {{ $requirement->message }}
                    </div>

                </div>

            @endif

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- QUOTATION --}}
    {{-- ========================================================= --}}

    <div class="mb-6 rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

        <div class="border-b border-[#E4EFED] px-6 py-5">

            <h2 class="text-lg font-bold text-gray-900">
                Seller Quotation
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Quotation received from the seller
            </p>

        </div>


        @if($quotation)

            <div class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">

                <div class="rounded-xl bg-[#F8FCFB] p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Quantity
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $quotation->quantity ?? '—' }}
                        {{ $quotation->unit ?? '' }}
                    </p>

                </div>


                <div class="rounded-xl bg-[#F8FCFB] p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Unit Price
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        ₹{{ number_format((float) ($quotation->unit_price ?? 0), 2) }}
                    </p>

                </div>


                <div class="rounded-xl bg-[#F8FCFB] p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Delivery Charges
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        ₹{{ number_format((float) ($quotation->delivery_charges ?? 0), 2) }}
                    </p>

                </div>


                <div class="rounded-xl bg-[#F8FCFB] p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Tax
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        ₹{{ number_format((float) ($quotation->tax ?? 0), 2) }}
                    </p>

                </div>


                <div class="rounded-xl bg-[#EAF5F3] p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-[#056663]">
                        Total Amount
                    </p>

                    <p class="mt-1 text-xl font-bold text-[#056663]">
                        ₹{{ number_format((float) ($quotation->total_amount ?? 0), 2) }}
                    </p>

                </div>


                <div class="rounded-xl bg-[#F8FCFB] p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Valid Until
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">

                        @if($quotation->valid_until)
                            {{ \Carbon\Carbon::parse($quotation->valid_until)->format('d M Y') }}
                        @else
                            —
                        @endif

                    </p>

                </div>


                <div class="rounded-xl bg-[#F8FCFB] p-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Delivery Time
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $quotation->delivery_time ?? '—' }}
                    </p>

                </div>


                @if($quotation->notes)

                    <div class="sm:col-span-2 lg:col-span-3">

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Seller Notes
                        </p>

                        <div class="mt-2 rounded-xl bg-[#F8FCFB] p-4 text-sm leading-6 text-gray-700">
                            {{ $quotation->notes }}
                        </div>

                    </div>

                @endif

            </div>

        @else

            <div class="px-6 py-10 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#EAF5F3] text-[#056663]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                </div>

                <h3 class="mt-3 font-semibold text-gray-800">
                    No quotation yet
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    The seller has not submitted a quotation for this RFQ.
                </p>

            </div>

        @endif

    </div>



    {{-- ========================================================= --}}
    {{-- CONVERSATION --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#D8E7E4] bg-white shadow-sm">

        <div class="border-b border-[#E4EFED] px-6 py-5">

            <h2 class="text-lg font-bold text-gray-900">
                Conversation
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Communicate with the seller about this RFQ.
            </p>

        </div>


        {{-- Messages --}}
        <div class="max-h-[450px] space-y-4 overflow-y-auto p-6">

            @forelse($rfq->messages as $message)

                @php
                    $isMine = $message->sender_id == Auth::id();
                @endphp

                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">

                    <div class="max-w-[80%]">

                        <div class="mb-1 text-xs text-gray-500
                            {{ $isMine ? 'text-right' : 'text-left' }}">

                            {{ $isMine ? 'You' : 'Seller' }}

                            @if($message->created_at)
                                · {{ $message->created_at->format('d M Y, h:i A') }}
                            @endif

                        </div>


                        <div class="rounded-2xl px-4 py-3 text-sm leading-6
                            {{ $isMine
                                ? 'rounded-br-md bg-[#056663] text-white'
                                : 'rounded-bl-md bg-[#F0F6F5] text-gray-800' }}">

                            {{ $message->message }}

                        </div>

                    </div>

                </div>

            @empty

                <div class="py-10 text-center">

                    <p class="font-medium text-gray-700">
                        No messages yet
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Start a conversation with the seller.
                    </p>

                </div>

            @endforelse

        </div>


        {{-- ===================================================== --}}
        {{-- SEND MESSAGE --}}
        {{-- ===================================================== --}}

        @if(
            $rfq->seller &&
            $rfq->seller->user_id &&
            !in_array($rfq->status, ['rejected', 'expired', 'closed'])
        )

            <div class="border-t border-[#E4EFED] p-6">

                <form method="POST"
                      action="{{ route('buyer.rfqs.message.store', $rfq->id) }}">

                    @csrf

                    <label for="message"
                           class="mb-2 block text-sm font-semibold text-gray-700">

                        Message Seller

                    </label>


                    <textarea
                        id="message"
                        name="message"
                        rows="4"
                        maxlength="5000"
                        required
                        placeholder="Write your message to the seller..."
                        class="w-full rounded-xl border border-[#C9DDDA] px-4 py-3 text-sm
                               text-gray-800 outline-none transition
                               focus:border-[#056663]
                               focus:ring-2 focus:ring-[#056663]/10">{{ old('message') }}</textarea>


                    @error('message')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror


                    <div class="mt-4 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">

                        <a href="{{ route('buyer.rfqs.index') }}"
                           class="inline-flex items-center justify-center rounded-xl
                                  border border-[#C9DDDA] px-5 py-2.5
                                  text-sm font-semibold text-gray-700
                                  hover:bg-gray-50">

                            ← Back to RFQs

                        </a>


                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2
                                       rounded-xl bg-[#056663] px-6 py-2.5
                                       text-sm font-semibold text-white
                                       hover:bg-[#045550]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 19l9 2-9-18-9 18 9-2z"/>
                            </svg>

                            Send Message

                        </button>

                    </div>

                </form>

            </div>

        @elseif($rfq->seller && !$rfq->seller->user_id)

            <div class="border-t border-yellow-200 bg-yellow-50 px-6 py-5">

                <p class="text-sm text-yellow-700">
                    Seller account information is incomplete, so messaging is currently unavailable.
                </p>

            </div>

        @elseif(!$rfq->seller)

            <div class="border-t border-red-200 bg-red-50 px-6 py-5">

                <p class="text-sm text-red-700">
                    Seller information is not available for this RFQ.
                </p>

            </div>

        @elseif(in_array($rfq->status, ['rejected', 'expired', 'closed']))

            <div class="border-t border-[#E4EFED] bg-gray-50 px-6 py-5 text-center">

                <p class="text-sm text-gray-500">
                    This RFQ is closed for messaging.
                </p>

            </div>

        @endif

    </div>


    {{-- Bottom Back --}}
    <div class="mt-6">

        <a href="{{ route('buyer.rfqs.index') }}"
           class="text-sm font-semibold text-[#056663] hover:underline">

            ← Back to My RFQs

        </a>

    </div>

</div>

</x-buyer-layout>