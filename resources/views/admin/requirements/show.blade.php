@extends('admin.layouts.app')

@section('title', 'Requirement Details')

@section('page-title', 'Requirement Details')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex items-center gap-3">

                <a
                    href="{{ route('admin.requirements.index') }}"
                    class="flex h-10 w-10 items-center justify-center
                           rounded-xl border border-[#D8E7E4] bg-white
                           text-[#056663] transition
                           hover:bg-[#E8F5F4]"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />

                    </svg>

                </a>


                <div>

                    <h1 class="text-xl font-bold text-gray-800">
                        Requirement Details
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Review the buyer requirement before generating an RFQ.
                    </p>

                </div>

            </div>

        </div>


        {{-- Requirement ID --}}
        <div class="rounded-xl bg-[#E8F5F4] px-4 py-2">

            <span class="text-xs font-medium text-gray-500">
                Requirement ID
            </span>

            <span class="ml-1 text-sm font-bold text-[#056663]">
                #{{ $requirement->id }}
            </span>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div
            class="rounded-xl border border-green-200 bg-green-50
                   px-5 py-4 text-sm font-medium text-green-700"
        >

            <div class="flex items-center gap-2">

                <span class="material-symbols-outlined text-[20px]">
                    check_circle
                </span>

                {{ session('success') }}

            </div>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div
            class="rounded-xl border border-red-200 bg-red-50
                   px-5 py-4 text-sm font-medium text-red-700"
        >

            <div class="flex items-center gap-2">

                <span class="material-symbols-outlined text-[20px]">
                    error
                </span>

                {{ session('error') }}

            </div>

        </div>

    @endif


    {{-- =========================================================
        TOP SUMMARY
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">


        {{-- Requirement Source --}}
        <div
            class="rounded-2xl border border-[#D8E7E4]
                   bg-white p-6 shadow-sm"
        >

            <div class="mb-4 flex items-center justify-between">

                <h2 class="text-base font-bold text-gray-800">
                    Requirement Source
                </h2>


                @if($requirement->requirement_source === 'listing')

                    <span
                        class="rounded-full bg-blue-100 px-3 py-1
                               text-xs font-semibold text-blue-700"
                    >
                        Listing
                    </span>

                @elseif($requirement->requirement_source === 'general')

                    <span
                        class="rounded-full bg-purple-100 px-3 py-1
                               text-xs font-semibold text-purple-700"
                    >
                        General
                    </span>

                @else

                    <span
                        class="rounded-full bg-gray-100 px-3 py-1
                               text-xs font-semibold text-gray-600"
                    >
                        —
                    </span>

                @endif

            </div>


            <p class="text-sm text-gray-500">
                Source of this requirement
            </p>

            <p class="mt-1 font-semibold text-gray-800">

                @if($requirement->requirement_source)

                    {{ ucfirst($requirement->requirement_source) }} Requirement

                @else

                    Not specified

                @endif

            </p>

        </div>


        {{-- Current Status --}}
        <div
            class="rounded-2xl border border-[#D8E7E4]
                   bg-white p-6 shadow-sm"
        >

            <h2 class="mb-4 text-base font-bold text-gray-800">
                Current Status
            </h2>


            @php

                $status = strtolower(
                    $requirement->status ?? 'pending_review'
                );

            @endphp


            @if(in_array($status, ['pending_review', 'new', 'pending_admin']))

                <span
                    class="inline-flex rounded-full bg-amber-100
                           px-4 py-2 text-xs font-semibold text-amber-700"
                >
                    Pending Review
                </span>


            @elseif($status === 'accepted')

                <span
                    class="inline-flex rounded-full bg-blue-100
                           px-4 py-2 text-xs font-semibold text-blue-700"
                >
                    Accepted
                </span>


            @elseif($status === 'rejected')

                <span
                    class="inline-flex rounded-full bg-red-100
                           px-4 py-2 text-xs font-semibold text-red-700"
                >
                    Rejected
                </span>


            @elseif($status === 'rfq_generated')

                <span
                    class="inline-flex rounded-full bg-green-100
                           px-4 py-2 text-xs font-semibold text-green-700"
                >
                    RFQ Generated
                </span>


            @else

                <span
                    class="inline-flex rounded-full bg-gray-100
                           px-4 py-2 text-xs font-semibold text-gray-600"
                >
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </span>

            @endif

        </div>


        {{-- Submitted Date --}}
        <div
            class="rounded-2xl border border-[#D8E7E4]
                   bg-white p-6 shadow-sm"
        >

            <h2 class="mb-4 text-base font-bold text-gray-800">
                Submitted On
            </h2>

            <p class="text-lg font-semibold text-gray-800">

                {{ $requirement->created_at?->format('d M Y') ?? '—' }}

            </p>

            @if($requirement->created_at)

                <p class="mt-1 text-sm text-gray-500">

                    {{ $requirement->created_at->format('h:i A') }}

                </p>

            @endif

        </div>

    </div>


    {{-- =========================================================
        BUYER INFORMATION
    ========================================================== --}}
    <div
        class="rounded-2xl border border-[#D8E7E4]
               bg-white shadow-sm"
    >

        <div class="border-b border-[#E5EFED] px-6 py-5">

            <h2 class="text-lg font-bold text-gray-800">
                Buyer Information
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Contact details of the buyer who submitted this requirement.
            </p>

        </div>


        <div
            class="grid grid-cols-1 gap-6 p-6
                   md:grid-cols-2 lg:grid-cols-4"
        >

            {{-- Name --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Name
                </p>

                <p class="mt-1 font-semibold text-gray-800">
                    {{ $requirement->name ?: '—' }}
                </p>

            </div>


            {{-- Email --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Email
                </p>

                <p class="mt-1 break-all font-semibold text-gray-800">
                    {{ $requirement->email ?: '—' }}
                </p>

            </div>


            {{-- Phone --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Phone
                </p>

                <p class="mt-1 font-semibold text-gray-800">
                    {{ $requirement->phone ?: '—' }}
                </p>

            </div>


            {{-- User ID --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    User ID
                </p>

                <p class="mt-1 font-semibold text-gray-800">
                    {{ $requirement->user_id ? '#' . $requirement->user_id : '—' }}
                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
        REQUIREMENT INFORMATION
    ========================================================== --}}
    <div
        class="rounded-2xl border border-[#D8E7E4]
               bg-white shadow-sm"
    >

        <div class="border-b border-[#E5EFED] px-6 py-5">

            <h2 class="text-lg font-bold text-gray-800">
                Requirement Information
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Details submitted by the buyer.
            </p>

        </div>


        <div
            class="grid grid-cols-1 gap-6 p-6
                   md:grid-cols-2 lg:grid-cols-3"
        >

            {{-- Quantity --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Quantity
                </p>

                <p class="mt-1 font-semibold text-gray-800">
                    {{ $requirement->quantity ?: '—' }}
                </p>

            </div>


            {{-- Unit --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Unit
                </p>

                <p class="mt-1 font-semibold text-gray-800">
                    {{ $requirement->unit ?: '—' }}
                </p>

            </div>


            {{-- Requirement Type --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Requirement Type
                </p>

                <p class="mt-1 font-semibold capitalize text-gray-800">
                    {{ $requirement->requirement_type ?: '—' }}
                </p>

            </div>


            {{-- Budget --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Budget
                </p>

                <p class="mt-1 font-semibold text-gray-800">
                    {{ $requirement->budget ?: '—' }}
                </p>

            </div>


            {{-- Delivery Location --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Delivery Location
                </p>

                <p class="mt-1 font-semibold text-gray-800">
                    {{ $requirement->delivery_location ?: '—' }}
                </p>

            </div>


            {{-- Required Date --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Required Date
                </p>

                <p class="mt-1 font-semibold text-gray-800">

                    @if($requirement->required_date)

                        {{ \Carbon\Carbon::parse(
                            $requirement->required_date
                        )->format('d M Y') }}

                    @else

                        —

                    @endif

                </p>

            </div>

        </div>


        {{-- Buyer Message --}}
        <div class="border-t border-[#E5EFED] px-6 py-5">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Buyer Message
            </p>

            <div
                class="mt-3 rounded-xl bg-[#F7FAF9]
                       p-5 text-sm leading-6 text-gray-700"
            >

                {{ $requirement->message ?: 'No message provided.' }}

            </div>

        </div>

    </div>


    {{-- =========================================================
        LISTING INFORMATION
    ========================================================== --}}
    @if($requirement->listing)

        <div
            class="rounded-2xl border border-[#D8E7E4]
                   bg-white shadow-sm"
        >

            <div class="border-b border-[#E5EFED] px-6 py-5">

                <h2 class="text-lg font-bold text-gray-800">
                    Listing Information
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Listing associated with this requirement.
                </p>

            </div>


            <div class="flex flex-col gap-6 p-6 md:flex-row">

                {{-- Listing Image --}}
                @if($requirement->listing->image)

                    <div class="shrink-0">

                        <img
                            src="{{ url('/uploads/' . $requirement->listing->image) }}"
                            alt="{{ $requirement->listing->title }}"
                            class="h-40 w-40 rounded-xl border
                                   border-gray-200 object-cover"
                            onerror="this.style.display='none';"
                        >

                    </div>

                @endif


                {{-- Listing Details --}}
                <div class="flex-1">

                    <h3 class="text-xl font-bold text-gray-800">
                        {{ $requirement->listing->title }}
                    </h3>


                    <div class="mt-3 flex flex-wrap gap-2">

                        @if($requirement->listing->categoryRelation)

                            <span
                                class="rounded-lg bg-[#E8F5F4]
                                       px-3 py-1.5 text-xs font-semibold
                                       text-[#056663]"
                            >
                                {{ $requirement->listing->categoryRelation->name }}
                            </span>

                        @endif


                        @if($requirement->listing->listing_type)

                            <span
                                class="rounded-lg bg-gray-100
                                       px-3 py-1.5 text-xs font-semibold
                                       text-gray-600"
                            >
                                {{ ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $requirement->listing->listing_type
                                    )
                                ) }}
                            </span>

                        @endif

                    </div>


                    @if($requirement->listing->description)

                        <p class="mt-4 text-sm leading-6 text-gray-600">
                            {{ $requirement->listing->description }}
                        </p>

                    @endif


                    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

                        {{-- Price --}}
                        <div>

                            <p class="text-xs text-gray-400">
                                Price
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                @if($requirement->listing->price)

                                    ₹ {{ number_format(
                                        $requirement->listing->price,
                                        2
                                    ) }}

                                    @if($requirement->listing->price_unit)

                                        / {{ $requirement->listing->price_unit }}

                                    @endif

                                @else

                                    —

                                @endif

                            </p>

                        </div>


                        {{-- Location --}}
                        <div>

                            <p class="text-xs text-gray-400">
                                Location
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">

                                {{ $requirement->listing->city ?: '—' }}

                                @if($requirement->listing->state)

                                    , {{ $requirement->listing->state }}

                                @endif

                            </p>

                        </div>


                        {{-- Listing ID --}}
                        <div>

                            <p class="text-xs text-gray-400">
                                Listing ID
                            </p>

                            <p class="mt-1 font-semibold text-gray-800">
                                #{{ $requirement->listing->id }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        SELLER INFORMATION
        Only show if a seller is already associated.
    ========================================================== --}}
    @if($requirement->seller)

        <div
            class="rounded-2xl border border-[#D8E7E4]
                   bg-white shadow-sm"
        >

            <div class="border-b border-[#E5EFED] px-6 py-5">

                <h2 class="text-lg font-bold text-gray-800">
                    Seller Information
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Seller currently associated with this requirement.
                </p>

            </div>


            <div
                class="grid grid-cols-1 gap-6 p-6
                       md:grid-cols-2 lg:grid-cols-3"
            >

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Business Name
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">

                        {{ $requirement->seller->bussiness_name
                            ?? $requirement->seller->business_name
                            ?? '—'
                        }}

                    </p>

                </div>


                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Seller ID
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        #{{ $requirement->seller->id }}
                    </p>

                </div>


                @if($requirement->seller->city)

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                            City
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $requirement->seller->city }}
                        </p>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- =========================================================
        AUTO-RFQ INFORMATION
    ========================================================== --}}
    @if($status === 'rfq_generated')

        <div
            class="rounded-2xl border border-green-200
                   bg-green-50 shadow-sm"
        >

            <div class="flex items-start gap-4 p-6">

                <div
                    class="flex h-11 w-11 shrink-0 items-center
                           justify-center rounded-xl bg-green-100"
                >

                    <span class="material-symbols-outlined text-green-600">
                        description
                    </span>

                </div>


                <div>

                    <h2 class="font-bold text-green-800">
                        Auto-RFQ Generated
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-green-700">
                        This requirement has been accepted and the system has
                        generated an RFQ automatically for suitable sellers.
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        ADMIN REVIEW ACTION
    ========================================================== --}}
    @if(in_array($status, ['pending_review', 'new', 'pending_admin']))

        <div
            class="rounded-2xl border border-[#D8E7E4]
                   bg-white shadow-sm"
        >

            <div class="border-b border-[#E5EFED] px-6 py-5">

                <h2 class="text-lg font-bold text-gray-800">
                    Admin Review
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Review the buyer's requirement and decide whether it
                    should proceed to the automatic RFQ process.
                </p>

            </div>


            <div class="p-6">

                <div
                    class="mb-6 rounded-xl border border-[#BFE2DF]
                           bg-[#E8F5F4] p-4"
                >

                    <div class="flex items-start gap-3">

                        <span class="material-symbols-outlined text-[#056663]">
                            info
                        </span>

                        <div>

                            <p class="text-sm font-semibold text-[#056663]">
                                Auto-RFQ Process
                            </p>

                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                If you accept this requirement, the system
                                will automatically generate the RFQ and
                                continue the seller-matching process.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="flex flex-col gap-3 sm:flex-row">

                    {{-- ACCEPT --}}
                    <form
                        action="{{ route(
                            'admin.requirements.accept',
                            $requirement
                        ) }}"
                        method="POST"
                        class="w-full sm:w-auto"
                    >

                        @csrf

                        @method('PUT')

                        <button
                            type="submit"
                            onclick="return confirm(
                                'Are you sure you want to accept this requirement? An RFQ will be generated automatically.'
                            )"
                            class="inline-flex w-full items-center
                                   justify-center gap-2 rounded-xl
                                   bg-[#056663] px-6 py-3
                                   text-sm font-semibold text-white
                                   transition hover:bg-[#04524F]
                                   sm:w-auto"
                        >

                            <span class="material-symbols-outlined text-[19px]">
                                check_circle
                            </span>

                            Accept Requirement

                        </button>

                    </form>


                    {{-- REJECT --}}
                    <form
                        action="{{ route(
                            'admin.requirements.reject',
                            $requirement
                        ) }}"
                        method="POST"
                        class="w-full sm:w-auto"
                    >

                        @csrf

                        @method('PUT')

                        <button
                            type="submit"
                            onclick="return confirm(
                                'Are you sure you want to reject this requirement?'
                            )"
                            class="inline-flex w-full items-center
                                   justify-center gap-2 rounded-xl
                                   border border-red-200
                                   bg-white px-6 py-3
                                   text-sm font-semibold text-red-600
                                   transition hover:bg-red-50
                                   sm:w-auto"
                        >

                            <span class="material-symbols-outlined text-[19px]">
                                cancel
                            </span>

                            Reject Requirement

                        </button>

                    </form>

                </div>

            </div>

        </div>

    @elseif($status === 'accepted')

        {{-- Accepted but RFQ generation still processing --}}
        <div
            class="rounded-2xl border border-blue-200
                   bg-blue-50 shadow-sm"
        >

            <div class="flex items-start gap-4 p-6">

                <div
                    class="flex h-11 w-11 shrink-0 items-center
                           justify-center rounded-xl bg-blue-100"
                >

                    <span class="material-symbols-outlined text-blue-600">
                        sync
                    </span>

                </div>


                <div>

                    <h2 class="font-bold text-blue-800">
                        Requirement Accepted
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-blue-700">
                        The requirement has been accepted and is ready for
                        automatic RFQ generation.
                    </p>

                </div>

            </div>

        </div>


    @elseif($status === 'rejected')

        {{-- Rejected --}}
        <div
            class="rounded-2xl border border-red-200
                   bg-red-50 shadow-sm"
        >

            <div class="flex items-start gap-4 p-6">

                <div
                    class="flex h-11 w-11 shrink-0 items-center
                           justify-center rounded-xl bg-red-100"
                >

                    <span class="material-symbols-outlined text-red-600">
                        cancel
                    </span>

                </div>


                <div>

                    <h2 class="font-bold text-red-800">
                        Requirement Rejected
                    </h2>

                    <p class="mt-1 text-sm leading-6 text-red-700">
                        This requirement has been rejected by the Admin.
                        No Auto-RFQ will be generated.
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        BOTTOM ACTION
    ========================================================== --}}
    <div class="flex items-center">

        <a
            href="{{ route('admin.requirements.index') }}"
            class="inline-flex items-center gap-2 rounded-xl
                   border border-gray-300 bg-white px-5 py-3
                   text-sm font-semibold text-gray-700
                   transition hover:bg-gray-50"
        >

            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />

            </svg>

            Back to Requirements

        </a>

    </div>

</div>

@endsection