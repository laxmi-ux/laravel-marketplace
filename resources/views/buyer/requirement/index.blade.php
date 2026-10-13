<x-buyer-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Requirement
            </h2>

            <p class="text-sm text-[#64748B] mt-0.5">
                View and manage the requirements you have submitted.
            </p>
        </div>

    </x-slot>


    {{-- =========================================================
         PAGE CONTENT
    ========================================================== --}}
    <div class="min-h-screen bg-[#F7FAFA]">

        <div class="px-4 sm:px-6 lg:px-8 py-7 lg:py-9">

            <div class="max-w-7xl mx-auto">


                {{-- =================================================
                     PAGE HEADER
                ================================================== --}}
                <div
                    class="mb-6 bg-white
                           border border-[#DDE9E8]
                           rounded-2xl
                           shadow-sm
                           px-5 py-5 sm:px-7">

                    <div
                        class="flex flex-col
                               sm:flex-row
                               sm:items-center
                               sm:justify-between
                               gap-4">

                        <div class="flex items-center gap-4">

                            <div
                                class="w-11 h-11 rounded-xl
                                       bg-[#E8F5F4]
                                       flex items-center
                                       justify-center
                                       text-[#056663]
                                       flex-shrink-0">

                                <svg
                                    class="w-5 h-5"
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

                            <div>

                                <h1
                                    class="text-xl sm:text-2xl
                                           font-bold
                                           text-[#123B38]">

                                    My Requirement

                                </h1>

                                <p
                                    class="mt-1 text-sm
                                           text-[#64748B]">

                                    View and manage the requirements you have submitted.

                                </p>

                            </div>

                        </div>


                        {{-- Total Requirement --}}
                        <div
                            class="flex items-center
                                   gap-3 rounded-xl
                                   bg-[#F5F9F8]
                                   border border-[#DDE9E8]
                                   px-4 py-3">

                            <div>

                                <p
                                    class="text-xs font-medium
                                           text-[#718A86]">

                                    Total Requirement

                                </p>

                                <p
                                    class="mt-0.5 text-2xl
                                           font-bold
                                           text-[#056663]">

                                    {{ $requirements->total() }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ALERTS
                ================================================== --}}

                @if(session('success'))

                    <div
                        class="mb-5 flex items-start gap-3
                               rounded-xl
                               border border-green-200
                               bg-green-50
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
                               border border-red-200
                               bg-red-50
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


                @if(session('info'))

                    <div
                        class="mb-5 flex items-start gap-3
                               rounded-xl
                               border border-blue-200
                               bg-blue-50
                               px-4 py-3.5
                               text-sm text-blue-700">

                        <svg
                            class="w-5 h-5 mt-0.5 flex-shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />

                        </svg>

                        <span>
                            {{ session('info') }}
                        </span>

                    </div>

                @endif


                @if($errors->any())

                    <div
                        class="mb-5 rounded-xl
                               border border-red-200
                               bg-red-50
                               px-4 py-3.5
                               text-sm text-red-700">

                        <p class="font-semibold mb-2">
                            Please fix the following:
                        </p>

                        <ul class="list-disc list-inside space-y-1">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- =================================================
                     REQUIREMENTS
                ================================================== --}}
                <div class="space-y-5">

                    @forelse($requirements as $requirement)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | Requirement Source
                            |--------------------------------------------------------------------------
                            */

                            $isGeneral =
                                $requirement->requirement_source === 'general'
                                || (
                                    !$requirement->requirement_source
                                    && !$requirement->listing_id
                                );

                            $isListing =
                                $requirement->requirement_source === 'listing'
                                || (
                                    !$requirement->requirement_source
                                    && $requirement->listing_id
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | Status Styling
                            |--------------------------------------------------------------------------
                            */

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


                        <div
                            class="group bg-white
                                   rounded-2xl
                                   border border-[#DDE9E8]
                                   shadow-sm
                                   overflow-hidden
                                   transition
                                   hover:shadow-md
                                   hover:border-[#C7DDDA]">


                            {{-- =================================================
                                 CARD TOP
                            ================================================== --}}
                            <div
                                class="px-5 py-5
                                       sm:px-6 sm:py-6
                                       border-b border-[#E8EFEE]">

                                <div
                                    class="flex flex-col
                                           lg:flex-row
                                           lg:items-start
                                           lg:justify-between
                                           gap-5">


                                    {{-- Requirement Information --}}
                                    <div class="min-w-0">

                                        <div
                                            class="flex flex-wrap
                                                   items-center
                                                   gap-2 mb-2">

                                            @if($isListing)

                                                <span
                                                    class="inline-flex
                                                           items-center
                                                           rounded-full
                                                           bg-[#E8F5F4]
                                                           px-2.5 py-1
                                                           text-[11px]
                                                           font-semibold
                                                           uppercase
                                                           tracking-wide
                                                           text-[#056663]">

                                                    Listing Requirement

                                                </span>

                                            @else

                                                <span
                                                    class="inline-flex
                                                           items-center
                                                           rounded-full
                                                           bg-[#F3F4F6]
                                                           px-2.5 py-1
                                                           text-[11px]
                                                           font-semibold
                                                           uppercase
                                                           tracking-wide
                                                           text-gray-600">

                                                    General Requirement

                                                </span>

                                            @endif

                                        </div>


                                        <h3
                                            class="text-lg sm:text-xl
                                                   font-bold
                                                   text-[#123B38]
                                                   break-words">

                                            {{ $requirement->listing?->title
                                                ?? 'General Requirement' }}

                                        </h3>


                                        {{-- =================================================
                                             GENERAL REQUIREMENT / RFQ INFORMATION
                                        ================================================== --}}

                                        @if($isGeneral)

                                            @if(
                                                $requirement->rfqs &&
                                                $requirement->rfqs->count()
                                            )

                                                <div
                                                    class="mt-2 flex
                                                           items-center
                                                           gap-2
                                                           text-sm
                                                           text-[#64748B]">

                                                    <svg
                                                        class="w-4 h-4 text-[#056663]"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />

                                                    </svg>

                                                    <span>

                                                        {{ $requirement->rfqs->count() }}

                                                        seller{{ $requirement->rfqs->count() > 1 ? 's' : '' }}

                                                        contacted through RFQ

                                                    </span>

                                                </div>

                                            @else

                                                <div
                                                    class="mt-2 flex
                                                           items-center
                                                           gap-2
                                                           text-sm
                                                           text-[#64748B]">

                                                    <svg
                                                        class="w-4 h-4 text-[#056663]"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />

                                                    </svg>

                                                    <span>
                                                        Waiting for seller assignment / response
                                                    </span>

                                                </div>

                                            @endif


                                        @elseif($requirement->seller)

                                            <div
                                                class="mt-2 flex
                                                       items-center
                                                       gap-2
                                                       text-sm
                                                       text-[#64748B]">

                                                <svg
                                                    class="w-4 h-4 text-[#056663]"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0" />

                                                </svg>

                                                <span>
                                                    Seller:
                                                </span>

                                                <span
                                                    class="font-semibold text-[#123B38]">

                                                    {{ $requirement->seller->bussiness_name
                                                        ?? $requirement->seller->business_name
                                                        ?? 'Seller' }}

                                                </span>

                                            </div>

                                        @endif

                                    </div>


                                    {{-- Status --}}
                                    <div class="flex-shrink-0">

                                        <span
                                            class="inline-flex
                                                   items-center
                                                   rounded-full
                                                   border
                                                   px-3 py-1.5
                                                   text-xs
                                                   font-semibold
                                                   {{ $statusClass }}">

                                            <span
                                                class="mr-1.5
                                                       w-1.5 h-1.5
                                                       rounded-full
                                                       bg-current">
                                            </span>

                                            {{ ucfirst(str_replace(
                                                '_',
                                                ' ',
                                                $requirement->status
                                            )) }}

                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 DETAILS
                            ================================================== --}}
                            <div
                                class="px-5 py-5
                                       sm:px-6 sm:py-6">

                                <div
                                    class="grid grid-cols-1
                                           sm:grid-cols-2
                                           lg:grid-cols-3
                                           gap-4">


                                    {{-- Name --}}
                                    <div
                                        class="rounded-xl
                                               bg-[#F8FBFB]
                                               border border-[#E8EFEE]
                                               p-4">

                                        <p
                                            class="text-xs font-medium
                                                   text-[#718A86]">

                                            Name

                                        </p>

                                        <p
                                            class="mt-1.5 text-sm
                                                   font-semibold
                                                   text-[#123B38]
                                                   break-words">

                                            {{ $requirement->name }}

                                        </p>

                                    </div>


                                    {{-- Email --}}
                                    <div
                                        class="rounded-xl
                                               bg-[#F8FBFB]
                                               border border-[#E8EFEE]
                                               p-4">

                                        <p
                                            class="text-xs font-medium
                                                   text-[#718A86]">

                                            Email

                                        </p>

                                        <p
                                            class="mt-1.5 text-sm
                                                   font-semibold
                                                   text-[#123B38]
                                                   break-all">

                                            {{ $requirement->email }}

                                        </p>

                                    </div>


                                    {{-- Phone --}}
                                    <div
                                        class="rounded-xl
                                               bg-[#F8FBFB]
                                               border border-[#E8EFEE]
                                               p-4">

                                        <p
                                            class="text-xs font-medium
                                                   text-[#718A86]">

                                            Phone

                                        </p>

                                        <p
                                            class="mt-1.5 text-sm
                                                   font-semibold
                                                   text-[#123B38]">

                                            {{ $requirement->phone ?? '-' }}

                                        </p>

                                    </div>


                                    {{-- Quantity --}}
                                    @if($requirement->quantity)

                                        <div
                                            class="rounded-xl
                                                   bg-[#F8FBFB]
                                                   border border-[#E8EFEE]
                                                   p-4">

                                            <p
                                                class="text-xs font-medium
                                                       text-[#718A86]">

                                                Quantity

                                            </p>

                                            <p
                                                class="mt-1.5 text-sm
                                                       font-semibold
                                                       text-[#123B38]">

                                                {{ $requirement->quantity }}

                                                {{ $requirement->unit }}

                                            </p>

                                        </div>

                                    @endif


                                    {{-- Requirement Type --}}
                                    @if($requirement->requirement_type)

                                        <div
                                            class="rounded-xl
                                                   bg-[#F8FBFB]
                                                   border border-[#E8EFEE]
                                                   p-4">

                                            <p
                                                class="text-xs font-medium
                                                       text-[#718A86]">

                                                Requirement Type

                                            </p>

                                            <p
                                                class="mt-1.5 text-sm
                                                       font-semibold
                                                       text-[#123B38]">

                                                {{ ucfirst($requirement->requirement_type) }}

                                            </p>

                                        </div>

                                    @endif


                                    {{-- Budget --}}
                                    @if($requirement->budget)

                                        <div
                                            class="rounded-xl
                                                   bg-[#F8FBFB]
                                                   border border-[#E8EFEE]
                                                   p-4">

                                            <p
                                                class="text-xs font-medium
                                                       text-[#718A86]">

                                                Budget

                                            </p>

                                            <p
                                                class="mt-1.5 text-sm
                                                       font-semibold
                                                       text-[#123B38]">

                                                ₹{{ number_format(
                                                    $requirement->budget,
                                                    2
                                                ) }}

                                            </p>

                                        </div>

                                    @endif


                                    {{-- Delivery Location --}}
                                    @if($requirement->delivery_location)

                                        <div
                                            class="rounded-xl
                                                   bg-[#F8FBFB]
                                                   border border-[#E8EFEE]
                                                   p-4">

                                            <p
                                                class="text-xs font-medium
                                                       text-[#718A86]">

                                                Delivery Location

                                            </p>

                                            <p
                                                class="mt-1.5 text-sm
                                                       font-semibold
                                                       text-[#123B38]
                                                       break-words">

                                                {{ $requirement->delivery_location }}

                                            </p>

                                        </div>

                                    @endif


                                    {{-- Required Date --}}
                                    @if($requirement->required_date)

                                        <div
                                            class="rounded-xl
                                                   bg-[#F8FBFB]
                                                   border border-[#E8EFEE]
                                                   p-4">

                                            <p
                                                class="text-xs font-medium
                                                       text-[#718A86]">

                                                Required Date

                                            </p>

                                            <p
                                                class="mt-1.5 text-sm
                                                       font-semibold
                                                       text-[#123B38]">

                                                {{ \Illuminate\Support\Carbon::parse(
                                                    $requirement->required_date
                                                )->format('d M Y') }}

                                            </p>

                                        </div>

                                    @endif

                                </div>


                                {{-- =================================================
                                     REQUIREMENT DETAILS
                                ================================================== --}}
                                <div
                                    class="mt-5 rounded-xl
                                           border border-[#DDE9E8]
                                           bg-[#F5F9F8]
                                           p-4 sm:p-5">

                                    <div
                                        class="flex items-center gap-2">

                                        <svg
                                            class="w-4 h-4 text-[#056663]"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 10h8M8 14h5m7-2a8 8 0 11-16 0 8 8 0 0116 0z" />

                                        </svg>

                                        <p
                                            class="text-xs font-semibold
                                                   uppercase
                                                   tracking-wide
                                                   text-[#64748B]">

                                            Requirement Details

                                        </p>

                                    </div>

                                    <p
                                        class="mt-2 text-sm
                                               leading-6
                                               text-[#123B38]
                                               whitespace-pre-line">

                                        {{ $requirement->message
                                            ?? 'No details provided.' }}

                                    </p>

                                </div>


                                {{-- =================================================
                                     LATEST MESSAGE
                                ================================================== --}}
                                @if(
                                    $requirement->messages &&
                                    $requirement->messages->count()
                                )

                                    @php

                                        $lastMessage = $requirement->messages
                                            ->sortByDesc('created_at')
                                            ->first();

                                    @endphp

                                    <div
                                        class="mt-4 rounded-xl
                                               border border-[#CDE3DF]
                                               bg-[#EAF5F3]
                                               p-4 sm:p-5">

                                        <div
                                            class="flex flex-col
                                                   sm:flex-row
                                                   sm:items-center
                                                   sm:justify-between
                                                   gap-2">

                                            <div>

                                                <p
                                                    class="text-[11px]
                                                           font-semibold
                                                           uppercase
                                                           tracking-wide
                                                           text-[#00665F]">

                                                    Latest Message

                                                </p>

                                                <p
                                                    class="mt-1 text-sm
                                                           font-semibold
                                                           text-[#123B38]">

                                                    {{ $lastMessage->sender_id === Auth::id()
                                                        ? 'You'
                                                        : 'Seller' }}

                                                </p>

                                            </div>

                                            <span
                                                class="text-xs
                                                       text-[#64748B]">

                                                {{ $lastMessage->created_at->format(
                                                    'd M Y, h:i A'
                                                ) }}

                                            </span>

                                        </div>

                                        <p
                                            class="mt-3 text-sm
                                                   leading-6
                                                   text-[#123B38]">

                                            {{ \Illuminate\Support\Str::limit(
                                                $lastMessage->message,
                                                180
                                            ) }}

                                        </p>

                                    </div>

                                @endif

                            </div>


                            {{-- =================================================
                                 ACTION FOOTER
                            ================================================== --}}
                            <div
                                class="px-5 py-4
                                       sm:px-6
                                       border-t border-[#E8EFEE]
                                       bg-[#FCFEFE]">

                                <div
                                    class="flex flex-col
                                           lg:flex-row
                                           lg:items-center
                                           lg:justify-between
                                           gap-4">


                                    {{-- =================================================
                                         CONVERSATION / RFQ ACTIONS
                                    ================================================== --}}
                                    <div class="flex-1 min-w-0">

                                        {{-- GENERAL REQUIREMENT --}}
                                        @if($isGeneral)

                                            @if(
                                                $requirement->rfqs &&
                                                $requirement->rfqs->count()
                                            )

                                                <div
                                                    class="flex flex-wrap
                                                           gap-2">

                                                    @foreach(
                                                        $requirement->rfqs
                                                        as $rfq
                                                    )

                                                        <a
                                                            href="{{ route(
                                                                'buyer.rfqs.show',
                                                                $rfq->id
                                                            ) }}"
                                                            class="inline-flex
                                                                   items-center
                                                                   gap-2
                                                                   rounded-xl
                                                                   bg-[#00665F]
                                                                   px-4 py-2.5
                                                                   text-sm
                                                                   font-semibold
                                                                   text-white
                                                                   hover:bg-[#00534E]
                                                                   transition
                                                                   whitespace-nowrap">

                                                            <svg
                                                                class="w-4 h-4 flex-shrink-0"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24">

                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 10h8M8 14h5m7-2a8 8 0 11-16 0 8 8 0 0116 0z" />

                                                            </svg>

                                                            <span>

                                                                {{ $rfq->seller?->bussiness_name
                                                                    ?? $rfq->seller?->business_name
                                                                    ?? 'Seller' }}

                                                            </span>

                                                            <span
                                                                class="text-xs
                                                                       font-medium
                                                                       opacity-90">

                                                                View Conversation

                                                            </span>

                                                        </a>

                                                    @endforeach

                                                </div>

                                            @else

                                                <span
                                                    class="inline-flex
                                                           items-center
                                                           gap-2
                                                           rounded-xl
                                                           border
                                                           border-[#DDE9E8]
                                                           bg-[#F8FBFB]
                                                           px-4 py-2.5
                                                           text-sm
                                                           font-semibold
                                                           text-[#718A86]">

                                                    <svg
                                                        class="w-4 h-4"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />

                                                    </svg>

                                                    Waiting for seller assignment / response

                                                </span>

                                            @endif


                                        {{-- LISTING-SPECIFIC REQUIREMENT --}}
                                        @else

                                            <a
                                                href="{{ route(
                                                    'buyer.enquiries.index'
                                                ) }}"
                                                class="inline-flex
                                                       items-center
                                                       justify-center
                                                       gap-2
                                                       rounded-xl
                                                       bg-[#00665F]
                                                       px-5 py-2.5
                                                       text-sm
                                                       font-semibold
                                                       text-white
                                                       hover:bg-[#00534E]
                                                       transition">

                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 10h8M8 14h5m7-2a8 8 0 11-16 0 8 8 0 0116 0z" />

                                                </svg>

                                                View My Enquiries

                                            </a>

                                        @endif

                                    </div>


                                    {{-- =================================================
                                         EDIT + DELETE
                                    ================================================== --}}
                                    <div
                                        class="flex flex-wrap
                                               items-center
                                               gap-3
                                               flex-shrink-0">

                                        {{-- Edit --}}
                                        <a
                                            href="{{ route(
                                                'buyer.requirement.edit',
                                                $requirement->id
                                            ) }}"
                                            class="inline-flex
                                                   items-center
                                                   justify-center
                                                   rounded-xl
                                                   border border-[#00665F]
                                                   bg-white
                                                   px-5 py-2.5
                                                   text-sm
                                                   font-semibold
                                                   text-[#00665F]
                                                   hover:bg-[#EAF5F3]
                                                   transition">

                                            Edit

                                        </a>


                                        {{-- Delete --}}
                                        <form
                                            action="{{ route(
                                                'buyer.requirement.destroy',
                                                $requirement->id
                                            ) }}"
                                            method="POST"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this requirement?'
                                            );">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex
                                                       items-center
                                                       justify-center
                                                       rounded-xl
                                                       border border-red-200
                                                       bg-red-50
                                                       px-5 py-2.5
                                                       text-sm
                                                       font-semibold
                                                       text-red-600
                                                       hover:bg-red-100
                                                       transition">

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>


                    @empty


                        {{-- =================================================
                             EMPTY STATE
                        ================================================== --}}
                        <div
                            class="bg-white
                                   rounded-2xl
                                   border border-[#DDE9E8]
                                   shadow-sm
                                   px-6 py-14
                                   text-center">

                            <div
                                class="mx-auto flex
                                       h-16 w-16
                                       items-center
                                       justify-center
                                       rounded-2xl
                                       bg-[#E8F5F4]
                                       text-[#00665F]">

                                <svg
                                    class="h-8 w-8"
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


                            <h3
                                class="mt-5 text-xl
                                       font-bold
                                       text-[#123B38]">

                                No requirements yet

                            </h3>


                            <p
                                class="mt-2 max-w-md
                                       mx-auto text-sm
                                       leading-6
                                       text-[#64748B]">

                                Submit a general requirement and the marketplace
                                team can process it through the RFQ workflow.

                            </p>


                            <a
                                href="{{ route('requirement.show') }}"
                                class="mt-6 inline-flex
                                       items-center
                                       justify-center
                                       rounded-xl
                                       bg-[#056663]
                                       px-6 py-3
                                       text-sm font-semibold
                                       text-white
                                       shadow-sm
                                       hover:bg-[#045450]
                                       transition">

                                Submit Requirement

                            </a>

                        </div>


                    @endforelse

                </div>


                {{-- =================================================
                     PAGINATION
                ================================================== --}}
                @if($requirements->hasPages())

                    <div class="mt-7">

                        {{ $requirements->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-buyer-layout>
