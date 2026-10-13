@extends('admin.layouts.app')

@section('title', 'RFQ Details')

@section('page-title', 'RFQ Details')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                RFQ #{{ $rfq->id }}
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Auto-generated RFQ monitoring
            </p>

        </div>


        <a
            href="{{ route('admin.rfqs.index') }}"
            class="rounded-lg border border-gray-300 px-4 py-2
                   text-sm font-semibold text-gray-700
                   hover:bg-gray-50"
        >
            ← Back
        </a>

    </div>


    {{-- Status --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    RFQ Status
                </p>

                @php
                    $statusClasses = match($rfq->status) {
                        'new' => 'bg-blue-50 text-blue-700',
                        'viewed' => 'bg-yellow-50 text-yellow-700',
                        'quoted' => 'bg-green-50 text-green-700',
                        'rejected' => 'bg-red-50 text-red-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp

                <span class="mt-2 inline-flex rounded-full px-4 py-1.5
                             text-sm font-semibold {{ $statusClasses }}">

                    {{ ucfirst($rfq->status) }}

                </span>

            </div>


            <div class="text-sm text-gray-500">

                <div>
                    Sent:
                    <span class="font-medium text-gray-700">
                        {{ $rfq->sent_at
                            ? $rfq->sent_at->format('d M Y, h:i A')
                            : '—' }}
                    </span>
                </div>

                <div class="mt-1">
                    Viewed:
                    <span class="font-medium text-gray-700">
                        {{ $rfq->viewed_at
                            ? $rfq->viewed_at->format('d M Y, h:i A')
                            : '—' }}
                    </span>
                </div>

                <div class="mt-1">
                    Responded:
                    <span class="font-medium text-gray-700">
                        {{ $rfq->responded_at
                            ? $rfq->responded_at->format('d M Y, h:i A')
                            : '—' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Requirement + Seller --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">


        {{-- Requirement --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Requirement Information
            </h2>

            <div class="mt-5 space-y-4">

                <div>
                    <p class="text-xs text-gray-500">
                        Requirement ID
                    </p>

                    <p class="font-semibold text-gray-900">
                        #{{ $rfq->requirement_id }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Buyer
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ $rfq->requirement->name ?? 'N/A' }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ $rfq->requirement->email ?? '' }}
                    </p>

                    @if($rfq->requirement->phone)
                        <p class="text-sm text-gray-500">
                            {{ $rfq->requirement->phone }}
                        </p>
                    @endif
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Requirement Type
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ ucfirst($rfq->requirement->requirement_type ?? 'General') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Location
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ $rfq->requirement->delivery_location ?? 'N/A' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Required Date
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ $rfq->requirement->required_date
                            ? \Carbon\Carbon::parse($rfq->requirement->required_date)->format('d M Y')
                            : 'N/A' }}
                    </p>
                </div>


                @if($rfq->requirement->message)

                    <div>

                        <p class="text-xs text-gray-500">
                            Buyer Message
                        </p>

                        <p class="mt-1 text-sm leading-6 text-gray-700">
                            {{ $rfq->requirement->message }}
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Seller --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Matched Seller
            </h2>

            <div class="mt-5 space-y-4">

                <div>
                    <p class="text-xs text-gray-500">
                        Seller ID
                    </p>

                    <p class="font-semibold text-gray-900">
                        #{{ $rfq->seller_id }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Business Name
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ $rfq->seller->bussiness_name ?? 'N/A' }}
                    </p>
                </div>


                @if($rfq->seller)

                    <div>
                        <p class="text-xs text-gray-500">
                            Location
                        </p>

                        <p class="font-semibold text-gray-900">
                            {{ $rfq->seller->location ?? 'N/A' }}
                        </p>
                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- RFQ Timeline --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-gray-900">
            RFQ Activity
        </h2>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-lg bg-gray-50 p-4">

                <p class="text-xs text-gray-500">
                    RFQ Sent
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $rfq->sent_at
                        ? $rfq->sent_at->format('d M Y, h:i A')
                        : 'Not sent' }}
                </p>

            </div>


            <div class="rounded-lg bg-gray-50 p-4">

                <p class="text-xs text-gray-500">
                    Seller Viewed
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $rfq->viewed_at
                        ? $rfq->viewed_at->format('d M Y, h:i A')
                        : 'Not viewed yet' }}
                </p>

            </div>


            <div class="rounded-lg bg-gray-50 p-4">

                <p class="text-xs text-gray-500">
                    Seller Responded
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $rfq->responded_at
                        ? $rfq->responded_at->format('d M Y, h:i A')
                        : 'Not responded yet' }}
                </p>

            </div>

        </div>

    </div>


    {{-- Quotation --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-gray-900">
            Quotation
        </h2>

        @if($rfq->quotation)

            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <div>
                    <p class="text-xs text-gray-500">
                        Quantity
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ $rfq->quotation->quantity }}
                        {{ $rfq->quotation->unit }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Unit Price
                    </p>

                    <p class="font-semibold text-gray-900">
                        ₹{{ number_format($rfq->quotation->unit_price, 2) }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Delivery Charges
                    </p>

                    <p class="font-semibold text-gray-900">
                        ₹{{ number_format($rfq->quotation->delivery_charges ?? 0, 2) }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Tax
                    </p>

                    <p class="font-semibold text-gray-900">
                        ₹{{ number_format($rfq->quotation->tax ?? 0, 2) }}
                    </p>
                </div>


                <div class="sm:col-span-2">

                    <p class="text-xs text-gray-500">
                        Total
                    </p>

                    <p class="text-2xl font-bold text-[#056663]">
                        ₹{{ number_format($rfq->quotation->total, 2) }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-500">
                        Delivery Time
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ $rfq->quotation->delivery_time ?? 'N/A' }}
                    </p>

                </div>


                <div>

                    <p class="text-xs text-gray-500">
                        Valid Until
                    </p>

                    <p class="font-semibold text-gray-900">
                        {{ $rfq->quotation->valid_until
                            ? \Carbon\Carbon::parse($rfq->quotation->valid_until)->format('d M Y')
                            : 'N/A' }}
                    </p>

                </div>

            </div>


            @if($rfq->quotation->notes)

                <div class="mt-5 border-t border-gray-100 pt-5">

                    <p class="text-xs text-gray-500">
                        Seller Notes
                    </p>

                    <p class="mt-1 text-sm text-gray-700">
                        {{ $rfq->quotation->notes }}
                    </p>

                </div>

            @endif

        @else

            <div class="mt-5 rounded-lg bg-gray-50 p-5 text-sm text-gray-500">
                Seller has not submitted a quotation yet.
            </div>

        @endif

    </div>


    {{-- Messages --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-gray-900">
            Communication
        </h2>

        @forelse($rfq->messages as $message)

            <div class="mt-4 rounded-lg bg-gray-50 p-4">

                <div class="flex items-center justify-between">

                    <div class="font-semibold text-gray-900">
                        {{ $message->sender->name ?? 'User' }}
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ $message->created_at->format('d M Y, h:i A') }}
                    </div>

                </div>

                <p class="mt-2 text-sm text-gray-700">
                    {{ $message->message }}
                </p>

            </div>

        @empty

            <div class="mt-4 rounded-lg bg-gray-50 p-5 text-sm text-gray-500">
                No messages yet.
            </div>

        @endforelse

    </div>

</div>

@endsection