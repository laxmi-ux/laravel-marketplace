<x-seller-layout>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a
                href="{{ route('seller.rfqs.index') }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-[#056663] hover:bg-[#E8F5F4]">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    RFQ Details
                </h2>

                <p class="text-sm text-gray-500">
                    Review buyer requirement and respond with a quotation.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#F6FAF9] py-8">

        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Header Card --}}
            <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm">

                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                    <div>
                        <div class="mb-2 flex items-center gap-3">

                            <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold uppercase text-[#056663]">
                                RFQ #{{ $rfq->id }}
                            </span>

                            @php
                            $statusClasses = [
                            'new' => 'bg-blue-50 text-blue-700',
                            'viewed' => 'bg-yellow-50 text-yellow-700',
                            'quotation_sent' => 'bg-green-50 text-green-700',
                            'rejected' => 'bg-red-50 text-red-700',
                            'expired' => 'bg-gray-100 text-gray-600',
                            'closed' => 'bg-purple-50 text-purple-700',
                            ];

                            $statusLabels = [
                            'new' => 'New',
                            'viewed' => 'Viewed',
                            'quotation_sent' => 'Quotation Sent',
                            'rejected' => 'Rejected',
                            'expired' => 'Expired',
                            'closed' => 'Closed',
                            ];

                            $statusClass = $statusClasses[$rfq->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp

                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ $statusLabels[$rfq->status] ?? ucfirst(str_replace('_', ' ', $rfq->status)) }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ $rfq->requirement?->requirement_type
                                ? ucfirst($rfq->requirement->requirement_type)
                                : 'Buyer Requirement' }}
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Received {{ optional($rfq->created_at)->format('d M Y, h:i A') }}
                        </p>
                    </div>

                    {{-- Action --}}
                    @if(!in_array($rfq->status, ['rejected', 'closed', 'expired']))
                    <a
                        href="{{ route('seller.rfqs.quotation.create', $rfq->id) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#056663] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#045550]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414A1 1 0 0118 8.414V19a2 2 0 01-2 2z" />
                        </svg>

                        {{ $rfq->status === 'quotation_sent' ? 'View / Edit Quotation' : 'Send Quotation' }}
                    </a>
                    @endif

                </div>
            </div>


            {{-- Buyer + Requirement --}}
            <div class="grid gap-6 lg:grid-cols-3">

                {{-- Buyer Information --}}
                <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm">

                    <div class="mb-5 flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-gray-900">
                                Buyer Information
                            </h2>

                            <p class="text-xs text-gray-500">
                                Contact details
                            </p>
                        </div>

                    </div>

                    <div class="space-y-4">

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Name
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $rfq->requirement?->user?->name
                                    ?? $rfq->requirement?->name
                                    ?? 'Not provided' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Email
                            </p>

                            <p class="mt-1 break-all text-sm text-gray-700">
                                {{ $rfq->requirement?->email
                                    ?? $rfq->requirement?->user?->email
                                    ?? 'Not provided' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Phone
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $rfq->requirement?->phone ?? 'Not provided' }}
                            </p>
                        </div>

                    </div>
                </div>


                {{-- Requirement Summary --}}
                <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm lg:col-span-2">

                    <div class="mb-5 flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5h6" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-gray-900">
                                Requirement Details
                            </h2>

                            <p class="text-xs text-gray-500">
                                Buyer requested information
                            </p>
                        </div>

                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Requirement Type
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ ucfirst($rfq->requirement?->requirement_type ?? 'General') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Quantity
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $rfq->requirement?->quantity ?? 'Not specified' }}
                                {{ $rfq->requirement?->unit ?? '' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Budget
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                @if($rfq->requirement?->budget)
                                ₹{{ number_format((float) $rfq->requirement->budget, 2) }}
                                @else
                                Not specified
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Delivery Location
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ $rfq->requirement?->delivery_location ?? 'Not specified' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Required Date
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                @if($rfq->requirement?->required_date)
                                {{ \Carbon\Carbon::parse($rfq->requirement->required_date)->format('d M Y') }}
                                @else
                                Not specified
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Source
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                {{ ucfirst($rfq->requirement?->requirement_source ?? 'General') }}
                            </p>
                        </div>

                    </div>

                    @if($rfq->requirement?->message)

                    <div class="mt-6 border-t border-gray-100 pt-5">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Buyer Message
                        </p>

                        <div class="mt-2 rounded-xl bg-[#F6FAF9] p-4 text-sm leading-6 text-gray-700">
                            {{ $rfq->requirement->message }}
                        </div>

                    </div>

                    @endif

                </div>

            </div>


            {{-- Listing Information --}}
            @if($rfq->requirement?->listing)

            @php
            $listing = $rfq->requirement->listing;
            @endphp

            <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm">

                <div class="mb-5 flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-gray-900">
                                Related Listing
                            </h2>

                            <p class="text-xs text-gray-500">
                                Listing associated with this requirement
                            </p>
                        </div>

                    </div>

                </div>

                <div class="grid gap-6 md:grid-cols-4">

                    <div class="md:col-span-2">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Listing
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">
                            {{ $listing->title }}
                        </p>

                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            City
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->city ?? 'Not specified' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Price
                        </p>

                        <p class="mt-1 font-semibold text-[#056663]">
                            @if($listing->price !== null)
                            ₹{{ number_format((float) $listing->price, 2) }}
                            @else
                            Contact Seller
                            @endif
                        </p>
                    </div>

                </div>

            </div>

            @endif


            {{-- Product / Property / Land / Service Details --}}
            @if($rfq->requirement)

            @php
            $requirement = $rfq->requirement;
            @endphp

            @if(
            $requirement->product_name ||
            $requirement->product_category ||
            $requirement->product_quality ||
            $requirement->preferred_brand ||
            $requirement->product_specification
            )

            <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm">

                <h2 class="mb-5 text-lg font-bold text-gray-900">
                    Product Details
                </h2>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    @if($requirement->product_name)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Product Name
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->product_name }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->product_category)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Category
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->product_category }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->product_quality)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Quality
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->product_quality }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->preferred_brand)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Preferred Brand
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->preferred_brand }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->product_specification)
                    <div class="sm:col-span-2">
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Specification
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->product_specification }}
                        </p>
                    </div>
                    @endif

                </div>

            </div>

            @endif


            @if(
            $requirement->property_type ||
            $requirement->property_purpose ||
            $requirement->property_location ||
            $requirement->property_area ||
            $requirement->bedrooms ||
            $requirement->bathrooms ||
            $requirement->property_requirements
            )

            <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm">

                <h2 class="mb-5 text-lg font-bold text-gray-900">
                    Property Details
                </h2>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    @if($requirement->property_type)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Property Type
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->property_type }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->property_purpose)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Purpose
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->property_purpose }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->property_location)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Location
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->property_location }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->property_area)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Area
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->property_area }}
                            {{ $requirement->property_area_unit ?? '' }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->bedrooms)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Bedrooms
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->bedrooms }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->bathrooms)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Bathrooms
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->bathrooms }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->property_requirements)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Additional Requirements
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->property_requirements }}
                        </p>
                    </div>
                    @endif

                </div>

            </div>

            @endif


            @if(
            $requirement->land_type ||
            $requirement->land_purpose ||
            $requirement->land_location ||
            $requirement->land_area ||
            $requirement->road_access ||
            $requirement->land_requirements
            )

            <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm">

                <h2 class="mb-5 text-lg font-bold text-gray-900">
                    Land Details
                </h2>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    @if($requirement->land_type)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Land Type
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->land_type }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->land_purpose)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Purpose
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->land_purpose }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->land_location)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Location
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->land_location }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->land_area)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Area
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->land_area }}
                            {{ $requirement->land_area_unit ?? '' }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->road_access)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Road Access
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->road_access }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->land_requirements)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Additional Requirements
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->land_requirements }}
                        </p>
                    </div>
                    @endif

                </div>

            </div>

            @endif


            @if(
            $requirement->service_name ||
            $requirement->service_type ||
            $requirement->service_location ||
            $requirement->service_timeline ||
            $requirement->provider_experience ||
            $requirement->service_scope
            )

            <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm">

                <h2 class="mb-5 text-lg font-bold text-gray-900">
                    Service Details
                </h2>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    @if($requirement->service_name)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Service Name
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->service_name }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->service_type)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Service Type
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->service_type }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->service_location)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Location
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->service_location }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->service_timeline)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Timeline
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->service_timeline }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->provider_experience)
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Provider Experience
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->provider_experience }}
                        </p>
                    </div>
                    @endif

                    @if($requirement->service_scope)
                    <div class="sm:col-span-2 lg:col-span-3">
                        <p class="text-xs uppercase tracking-wide text-gray-400">
                            Scope
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->service_scope }}
                        </p>
                    </div>
                    @endif

                </div>

            </div>

            @endif

            @endif


            {{-- =========================================================
     RFQ CONVERSATION
========================================================= --}}
            <div class="rounded-2xl border border-[#D7E7E4] bg-white p-6 shadow-sm">

                {{-- Header --}}
                <div class="mb-6 flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center
                       rounded-xl bg-[#E8F5F4] text-[#056663]">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 10h8M8 14h5m-1 7a9 9 0 100-18 9 9 0 000 18z" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-gray-900">
                                Buyer Messages
                            </h2>

                            <p class="text-xs text-gray-500">
                                Communicate with the buyer regarding this RFQ
                            </p>
                        </div>

                    </div>

                    @if($rfq->messages && $rfq->messages->count())
                    <span
                        class="rounded-full bg-[#E8F5F4] px-3 py-1
                       text-xs font-semibold text-[#056663]">
                        {{ $rfq->messages->count() }}
                        {{ $rfq->messages->count() === 1 ? 'Message' : 'Messages' }}
                    </span>
                    @endif

                </div>


                {{-- Messages --}}
                <div class="space-y-4">

                    @forelse($rfq->messages as $message)

                    @php
                    $isSellerMessage =
                    (int) $message->sender_id === (int) auth()->id();
                    @endphp

                    <div
                        class="flex {{ $isSellerMessage ? 'justify-end' : 'justify-start' }}">

                        <div
                            class="max-w-2xl rounded-2xl px-4 py-3
                    {{ $isSellerMessage
                        ? 'bg-[#056663] text-white'
                        : 'bg-[#F6FAF9] border border-[#D7E7E4] text-gray-800'
                    }}">

                            {{-- Sender --}}
                            <div class="mb-1 flex items-center gap-2">

                                <span
                                    class="text-xs font-semibold
                            {{ $isSellerMessage
                                ? 'text-white'
                                : 'text-[#056663]'
                            }}">
                                    {{ $isSellerMessage
                                ? 'You'
                                : ($message->sender?->name ?? 'Buyer')
                            }}
                                </span>

                                @if($message->created_at)
                                <span
                                    class="text-[11px]
                                {{ $isSellerMessage
                                    ? 'text-white/70'
                                    : 'text-gray-400'
                                }}">
                                    {{ $message->created_at->format('d M Y, h:i A') }}
                                </span>
                                @endif

                            </div>

                            {{-- Message --}}
                            <p class="whitespace-pre-line text-sm leading-6">
                                {{ $message->message }}
                            </p>

                        </div>

                    </div>

                    @empty

                    <div
                        class="rounded-xl border border-dashed
                       border-[#CFE0DD] bg-[#F8FCFB]
                       px-5 py-8 text-center">
                        <div
                            class="mx-auto mb-3 flex h-11 w-11
                           items-center justify-center
                           rounded-full bg-[#E8F5F4]
                           text-[#056663]">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 10h8M8 14h5m-1 7a9 9 0 100-18 9 9 0 000 18z" />
                            </svg>
                        </div>

                        <p class="font-semibold text-gray-700">
                            No messages yet
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            The buyer has not sent any message for this RFQ.
                        </p>
                    </div>

                    @endforelse

                </div>


                {{-- Reply Form --}}
                @if(!in_array($rfq->status, ['rejected', 'closed', 'expired']))

                <div class="mt-6 border-t border-gray-100 pt-6">

                    <form
                        method="POST"
                        action="{{ route('seller.rfqs.message', $rfq->id) }}">
                        @csrf

                        <label
                            for="message"
                            class="mb-2 block text-sm font-semibold text-gray-800">
                            Reply to Buyer
                        </label>

                        <textarea
                            id="message"
                            name="message"
                            rows="4"
                            required
                            maxlength="5000"
                            placeholder="Write your message to the buyer..."
                            class="w-full rounded-xl border border-[#CFE0DD]
                           bg-white px-4 py-3 text-sm text-gray-800
                           outline-none transition
                           focus:border-[#056663]
                           focus:ring-2 focus:ring-[#056663]/10">{{ old('message') }}</textarea>

                        @error('message')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                        @enderror

                        <div class="mt-3 flex justify-end">

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center
                               gap-2 rounded-xl bg-[#056663]
                               px-5 py-3 text-sm font-semibold
                               text-white transition
                               hover:bg-[#045550]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>

                                Send Message

                            </button>

                        </div>

                    </form>

                </div>

                @endif

            </div>


            {{-- Bottom Actions --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">

                <a
                    href="{{ route('seller.rfqs.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-[#CFE0DD] bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Back to RFQs
                </a>

                <div class="flex flex-col gap-3 sm:flex-row">

                    @if(!in_array($rfq->status, ['rejected', 'closed', 'expired']))

                    <form
                        method="POST"
                        action="{{ route('seller.rfqs.reject', $rfq->id) }}"
                        onsubmit="return confirm('Are you sure you want to reject this RFQ?');">
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-xl border border-red-200 bg-white px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-50 sm:w-auto">
                            Reject RFQ
                        </button>
                    </form>

                    <a
                        href="{{ route('seller.rfqs.quotation.create', $rfq->id) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-[#056663] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#045550]">
                        Send Quotation
                    </a>

                    @endif

                </div>

            </div>

        </div>
    </div>

</x-seller-layout>