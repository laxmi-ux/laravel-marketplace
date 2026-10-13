<x-buyer-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             Requirement Conversation
            </h2>

            <p class="text-sm text-[#64748B] mt-0.5">
                Communicate with the seller regarding your enquiry.
            </p>
        </div>

    </x-slot>


    {{-- =========================================================
         PAGE CONTENT
    ========================================================== --}}
    <div class="min-h-screen bg-[#F7FAFA]">

        <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">


            {{-- =================================================
                 PAGE HEADER
            ================================================== --}}
            <div class="mb-7">

                <a
                    href="{{ route('buyer.requirement.index') }}"
                    class="inline-flex items-center gap-2
                           text-sm font-medium
                           text-[#00665F]
                           hover:text-[#004F4A]
                           transition">

                    <span class="text-lg leading-none">←</span>

                    Back to My Requirement

                </a>


                <div class="mt-5">

                    <h1
                        class="text-2xl sm:text-3xl
                               font-bold
                               text-gray-900">

                      Requirement Conversation

                    </h1>

                    <p class="text-sm sm:text-base text-gray-500 mt-2">

                        Communicate with the seller regarding your enquiry.

                    </p>

                </div>

            </div>


            {{-- =================================================
                 ALERTS
            ================================================== --}}

            @if(session('success'))

                <div
                    class="mb-5 flex items-start gap-3
                           rounded-xl
                           bg-green-50
                           border border-green-200
                           px-4 py-3.5
                           text-sm text-green-700">

                    <svg
                        class="w-5 h-5 mt-0.5 flex-shrink-0"
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


            @if(session('error'))

                <div
                    class="mb-5 flex items-start gap-3
                           rounded-xl
                           bg-red-50
                           border border-red-200
                           px-4 py-3.5
                           text-sm text-red-700">

                    <svg
                        class="w-5 h-5 mt-0.5 flex-shrink-0"
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


            @if($errors->any())

                <div
                    class="mb-5 rounded-xl
                           bg-red-50
                           border border-red-200
                           p-4">

                    <p class="font-semibold text-red-700 mb-2">
                        Please fix the following errors:
                    </p>

                    <ul class="list-disc list-inside space-y-1 text-sm text-red-600">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =================================================
              Requirement DETAILS CARD
            ================================================== --}}
            <section
                class="bg-white
                       rounded-2xl
                       border border-[#DDE9E8]
                       shadow-sm
                       overflow-hidden
                       mb-6">

                {{-- Card Header --}}
                <div
                    class="px-5 sm:px-6
                           py-4
                           border-b border-[#E6EFEE]
                           flex items-center
                           justify-between gap-4">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-900">
                           Requirement Details
                        </h2>

                        <p class="text-xs text-gray-500 mt-1">
                            Information related to this enquiry
                        </p>

                    </div>


                    <div
                        class="px-3 py-1.5 rounded-full
                               bg-[#E8F5F4]
                               text-[#056663]
                               text-xs font-semibold
                               whitespace-nowrap">

                        #{{ $requirement->id }}

                    </div>

                </div>


                {{-- Details --}}
                <div class="p-5 sm:p-6">

                    <div
                        class="grid grid-cols-1
                               sm:grid-cols-2
                               gap-x-8 gap-y-6">


                        {{-- Listing --}}
                        <div>

                            <p
                                class="text-xs font-medium
                                       uppercase tracking-wide
                                       text-gray-400 mb-1.5">

                                Listing

                            </p>

                            <p class="text-sm font-semibold text-gray-900">

                                {{ $requirement->listing?->title ?? 'General Requirement' }}

                            </p>

                        </div>


                        {{-- Seller --}}
                        <div>

                            <p
                                class="text-xs font-medium
                                       uppercase tracking-wide
                                       text-gray-400 mb-1.5">

                                Seller

                            </p>

                            <p class="text-sm font-semibold text-gray-900">

                                {{ $requirement->seller?->bussiness_name
                                    ?? $requirement->seller?->business_name
                                    ?? 'Seller' }}

                            </p>

                        </div>


                        {{-- Requirement ID --}}
                        <div>

                            <p
                                class="text-xs font-medium
                                       uppercase tracking-wide
                                       text-gray-400 mb-1.5">

                             Requirement ID

                            </p>

                            <p class="text-sm font-semibold text-gray-900">

                                #{{ $requirement->id }}

                            </p>

                        </div>


                        {{-- Status --}}
                        <div>

                            <p
                                class="text-xs font-medium
                                       uppercase tracking-wide
                                       text-gray-400 mb-1.5">

                                Status

                            </p>

                            @php

                                $statusClass = match($requirement->status) {

                                    'pending_admin' =>
                                        'bg-orange-50 text-orange-700 border-orange-200',

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

                            @endphp

                            <span
                                class="inline-flex items-center
                                       px-2.5 py-1
                                       rounded-full
                                       border
                                       {{ $statusClass }}
                                       text-xs font-semibold">

                                {{ ucfirst(str_replace('_', ' ', $requirement->status)) }}

                            </span>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 ORIGINAL REQUIREMENT
            ================================================== --}}
            <section
                class="bg-white
                       rounded-2xl
                       border border-[#DDE9E8]
                       shadow-sm
                       overflow-hidden
                       mb-6">

                <div
                    class="px-5 sm:px-6
                           py-4
                           border-b border-[#E6EFEE]">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Your Requirement
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        Original requirement submitted by you
                    </p>

                </div>


                <div class="p-5 sm:p-6">

                    <div
                        class="rounded-xl
                               bg-[#F5F9F8]
                               border border-[#DDE9E8]
                               p-4 sm:p-5">

                        <p
                            class="text-sm leading-6
                                   text-[#123B38]
                                   whitespace-pre-line">

                            {{ $requirement->message ?? 'No requirement details provided.' }}

                        </p>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 CONVERSATION CARD
            ================================================== --}}
            <section
                class="bg-white
                       rounded-2xl
                       border border-[#DDE9E8]
                       shadow-sm
                       overflow-hidden
                       mb-6">

                {{-- Header --}}
                <div
                    class="px-5 sm:px-6
                           py-4
                           border-b border-[#E6EFEE]">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Conversation
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        Messages between you and the seller
                    </p>

                </div>


                {{-- Messages --}}
                <div class="p-5 sm:p-6">

                    @if($requirement->messages->count())

                        <div class="space-y-5">

                            @foreach($requirement->messages as $message)

                                @php
                                    $isMine = $message->sender_id === Auth::id();
                                @endphp

                                <div
                                    class="flex
                                           {{ $isMine
                                                ? 'justify-end'
                                                : 'justify-start' }}">

                                    <div
                                        class="w-full sm:max-w-[78%]
                                               rounded-2xl
                                               px-4 py-3.5
                                               shadow-sm
                                               {{ $isMine
                                                    ? 'bg-[#00665F] text-white rounded-br-md'
                                                    : 'bg-[#F3F7F7] text-gray-900 border border-[#E4ECEB] rounded-bl-md' }}">

                                        {{-- Message Header --}}
                                        <div
                                            class="flex flex-wrap
                                                   items-center
                                                   justify-between
                                                   gap-2
                                                   mb-2">

                                            <span class="text-sm font-semibold">

                                                {{ $isMine ? 'You' : 'Seller' }}

                                            </span>

                                            <span
                                                class="text-[11px]
                                                       {{ $isMine
                                                            ? 'text-white/70'
                                                            : 'text-gray-400' }}">

                                                {{ $message->created_at->format('d M Y, h:i A') }}

                                            </span>

                                        </div>


                                        {{-- Message --}}
                                        <p
                                            class="whitespace-pre-line
                                                   text-sm leading-6">

                                            {{ $message->message }}

                                        </p>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        {{-- Empty Conversation --}}
                        <div
                            class="py-12
                                   flex flex-col
                                   items-center
                                   justify-center
                                   text-center">

                            <div
                                class="w-14 h-14
                                       rounded-full
                                       bg-[#E8F5F4]
                                       flex items-center
                                       justify-center
                                       text-[#056663]
                                       mb-4">

                                <svg
                                    class="w-7 h-7"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 10h8M8 14h5m7-2a8 8 0 11-16 0 8 8 0 0116 0z" />

                                </svg>

                            </div>

                            <h3 class="font-semibold text-gray-800">
                                No messages yet
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Start the conversation with the seller below.
                            </p>

                        </div>

                    @endif

                </div>

            </section>


            {{-- =================================================
                 REPLY FORM
            ================================================== --}}
            <section
                class="bg-white
                       rounded-2xl
                       border border-[#DDE9E8]
                       shadow-sm
                       overflow-hidden">

                {{-- Header --}}
                <div
                    class="px-5 sm:px-6
                           py-4
                           border-b border-[#E6EFEE]">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Reply to Seller
                    </h2>

                    <p class="text-xs text-gray-500 mt-1">
                        Send a message regarding your enquiry.
                    </p>

                </div>


                {{-- Form --}}
                <form
                    action="{{ route('buyer.enquiries.reply.store', $requirement->id) }}"
                    method="POST"
                    class="p-5 sm:p-6">

                    @csrf

                    <div>

                        <label
                            for="message"
                            class="block
                                   text-sm font-medium
                                   text-gray-700
                                   mb-2">

                            Your Message

                        </label>


                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            required
                            maxlength="3000"
                            placeholder="Write your message to the seller..."
                            class="w-full
                                   rounded-xl
                                   border border-gray-300
                                   bg-white
                                   px-4 py-3
                                   text-sm text-gray-900
                                   placeholder:text-gray-400
                                   focus:border-[#00665F]
                                   focus:ring-1
                                   focus:ring-[#00665F]
                                   resize-y">{{ old('message') }}</textarea>


                        <p class="text-xs text-gray-400 mt-2">
                            Maximum 3000 characters.
                        </p>

                    </div>


                    {{-- Buttons --}}
                    <div
                        class="flex flex-col-reverse
                               sm:flex-row
                               sm:justify-end
                               gap-3
                               mt-6">

                        <a
                            href="{{ route('buyer.requirement.index') }}"
                            class="inline-flex
                                   items-center
                                   justify-center
                                   px-5 py-2.5
                                   rounded-xl
                                   border border-gray-300
                                   bg-white
                                   text-sm font-medium
                                   text-gray-700
                                   hover:bg-gray-50
                                   transition">

                            Cancel

                        </a>


                        <button
                            type="submit"
                            class="inline-flex
                                   items-center
                                   justify-center
                                   gap-2
                                   px-5 py-2.5
                                   rounded-xl
                                   bg-[#00665F]
                                   text-white
                                   text-sm font-semibold
                                   hover:bg-[#00534E]
                                   transition
                                   shadow-sm">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 12h14M13 6l6 6-6 6" />

                            </svg>

                            Send Message

                        </button>

                    </div>

                </form>

            </section>

        </div>

    </div>

</x-buyer-layout>