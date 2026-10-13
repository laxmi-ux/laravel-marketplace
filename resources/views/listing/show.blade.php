@extends('layouts.base')

@section('title', $listing->title)

@section('content')

<section class="max-w-[1280px] w-full mx-auto px-4 sm:px-6 lg:px-10 py-24">

    {{-- ========================================================= --}}
    {{-- FLASH MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))

    <div
        class="mb-6 flex items-start gap-3
                   rounded-lg bg-green-50
                   border border-green-200
                   text-green-700 px-4 py-3">

        <span class="material-symbols-outlined">
            check_circle
        </span>

        <div>
            <p class="font-medium">
                {{ session('success') }}
            </p>
        </div>

    </div>

    @endif


    @if(session('error'))

    <div
        class="mb-6 rounded-lg
                   bg-red-50 border border-red-200
                   text-red-700 px-4 py-3">

        {{ session('error') }}

    </div>

    @endif


    {{-- ========================================================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================================================= --}}

    @if($errors->any())

    <div
        class="mb-6 rounded-lg
                   bg-red-50 border border-red-200
                   text-red-700 px-4 py-3">

        <ul class="list-disc pl-5 text-sm space-y-1">

            @foreach($errors->all() as $error)

            <li>
                {{ $error }}
            </li>

            @endforeach

        </ul>

    </div>

    @endif


    {{-- ========================================================= --}}
    {{-- BACK --}}
    {{-- ========================================================= --}}

    <div class="mb-8">

        <a
            href="{{ route('listing.index') }}"
            class="inline-flex items-center gap-2
                   text-gray-500 hover:text-primary transition">

            <span class="material-symbols-outlined text-[18px]">
                arrow_back
            </span>

            Back to Listings

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- LISTING DETAILS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">


        {{-- ===================================================== --}}
        {{-- IMAGE --}}
        {{-- ===================================================== --}}

        <div>

            <div
                class="relative h-[450px] rounded-2xl overflow-hidden
                       bg-gray-100 shadow-lg">

                @if($listing->image)

                @if(filter_var($listing->image, FILTER_VALIDATE_URL))

                <img
                    src="{{ $listing->image }}"
                    alt="{{ $listing->title }}"
                    class="w-full h-full object-cover">

                @else

                <img
                    src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                    alt="{{ $listing->title }}"
                    class="w-full h-full object-cover">

                @endif

                @else

                <div class="w-full h-full flex items-center justify-center">

                    <span class="material-symbols-outlined text-6xl text-gray-300">
                        image
                    </span>

                </div>

                @endif


                {{-- Verified --}}
                @if($listing->is_verified)

                <div
                    class="absolute top-5 left-5
                               bg-white/95 text-primary
                               px-4 py-2 rounded-full
                               flex items-center gap-2 shadow">

                    <span class="material-symbols-outlined text-[18px]">
                        verified
                    </span>

                    <span class="font-medium">
                        Verified Seller
                    </span>

                </div>

                @endif

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DETAILS --}}
        {{-- ===================================================== --}}

        <div>

            {{-- Category --}}
            <span
                class="inline-block
                       bg-primary/10 text-primary
                       px-4 py-2 rounded-full
                       text-sm font-medium mb-5">

                {{ $listing->categoryRelation?->name ?? 'Listing' }}

            </span>


            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold text-primary mb-5">
                {{ $listing->title }}
            </h1>


            {{-- Price --}}
            <div class="mb-8">

                <p class="text-sm text-gray-500 mb-1">
                    Price
                </p>

                @if($listing->price)

                <p class="text-3xl font-bold text-primary">

                    ₹{{ number_format($listing->price) }}

                    @if($listing->price_unit)

                    <span class="text-sm font-normal text-gray-500">
                        /
                        {{ str_replace('per ', '', $listing->price_unit) }}
                    </span>

                    @endif

                </p>

                @else

                <p class="text-3xl font-bold text-primary">
                    Get Quote
                </p>

                @endif

            </div>


            {{-- Location --}}
            @if($listing->city)

            <div class="flex items-center gap-3 mb-5">

                <span class="material-symbols-outlined text-primary">
                    location_on
                </span>

                <div>

                    <p class="text-sm text-gray-500">
                        Location
                    </p>

                    <p class="text-base text-gray-800">

                        {{ $listing->city }}

                        @if($listing->state)
                        , {{ $listing->state }}
                        @endif

                    </p>

                </div>

            </div>

            @endif


            {{-- Seller --}}
            @if($listing->seller_name)

            <div class="flex items-center gap-3 mb-8">

                <span class="material-symbols-outlined text-primary">
                    store
                </span>

                <div>

                    <p class="text-sm text-gray-500">
                        Seller
                    </p>

                    <p class="text-base text-gray-800">
                        {{ $listing->seller_name }}
                    </p>

                </div>

            </div>

            @endif


            {{-- Description --}}
            <div class="border-t border-gray-200 pt-6 mb-8">

                <h2 class="text-xl font-semibold text-primary mb-3">
                    Description
                </h2>

                <p class="text-gray-600 leading-relaxed">
                    {{ $listing->description }}
                </p>

            </div>


            {{-- ========================================================= --}}
            {{-- ACTIONS --}}
            {{-- ========================================================= --}}

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">


                {{-- ===================================================== --}}
                {{-- SEND ENQUIRY --}}
                {{-- ===================================================== --}}

                @auth

                <a
                    href="#send-enquiry"
                    class="px-6 py-3
                               bg-[#056663] text-white
                               rounded-lg
                               hover:bg-[#045450]
                               transition
                               text-center
                               flex items-center justify-center gap-2">

                    <span class="material-symbols-outlined text-[20px]">
                        send
                    </span>

                    Send Enquiry

                </a>

                @else

                <a
                    href="{{ route('login') }}"
                    class="px-6 py-3
                               bg-[#056663] text-white
                               rounded-lg
                               hover:bg-[#045450]
                               transition
                               text-center
                               flex items-center justify-center gap-2">

                    <span class="material-symbols-outlined text-[20px]">
                        login
                    </span>

                    Login to Send Enquiry

                </a>

                @endauth



                @auth

                @php
                $canBid =
                $listing->seller_id &&
                (!$listing->seller ||
                $listing->seller->user_id !== Auth::id());
                @endphp

                @if($canBid)

                <a
                    href="{{ route('buyer.bids.create', $listing->id) }}"
                    class="inline-flex items-center justify-center gap-2
                   rounded-xl border border-[#056663]
                   bg-[#E8F5F4] px-5 py-3
                   text-sm font-semibold text-[#056663]
                   transition hover:bg-[#DDF1EF]">

                    <span class="material-symbols-outlined text-[19px]">
                        gavel
                    </span>

                    Place Bid

                </a>

                @endif

                @endauth


                {{-- ===================================================== --}}
                {{-- SAVE LISTING --}}
                {{-- ===================================================== --}}

                @auth

                <form
                    action="{{ route('buyer.saved-listings.store', $listing->id) }}"
                    method="POST"
                    class="w-full">

                    @csrf

                    <button
                        type="submit"
                        class="w-full px-6 py-3
                                   border border-[#056663]
                                   text-[#056663]
                                   rounded-lg
                                   hover:bg-[#E8F5F4]
                                   transition
                                   flex items-center justify-center gap-2">

                        <span class="material-symbols-outlined text-[20px]">
                            bookmark_border
                        </span>

                        Save Listing

                    </button>

                </form>

                @else

                <a
                    href="{{ route('login') }}"
                    class="px-6 py-3
                               border border-[#056663]
                               text-[#056663]
                               rounded-lg
                               hover:bg-[#E8F5F4]
                               transition
                               flex items-center justify-center gap-2">

                    <span class="material-symbols-outlined text-[20px]">
                        bookmark_border
                    </span>

                    Login to Save

                </a>

                @endauth


                {{-- ===================================================== --}}
                {{-- CONTACT SELLER --}}
                {{-- ===================================================== --}}

                @if($listing->seller_id)

                <a
                    href="{{ route('sellers.show', $listing->seller?->slug ?? '#') }}"
                    class="border border-primary
                               text-primary
                               py-3
                               rounded-lg
                               flex items-center justify-center gap-2
                               hover:bg-primary
                               hover:text-white
                               transition">

                    <span class="material-symbols-outlined">
                        store
                    </span>

                    Contact Seller

                </a>

                @else

                <button
                    type="button"
                    class="border border-primary
                               text-primary
                               py-3
                               rounded-lg
                               flex items-center justify-center gap-2">

                    <span class="material-symbols-outlined">
                        phone
                    </span>

                    Contact Seller

                </button>

                @endif


                {{-- ===================================================== --}}
                {{-- REPORT LISTING --}}
                {{-- ===================================================== --}}

                @auth

                @if(
                $listing->seller_id &&
                (int) ($listing->seller?->user_id ?? 0) !== (int) Auth::id()
                )

                <a
                    href="{{ route('reports.create', [
                'type' => 'listing',
                'id' => $listing->id
            ]) }}"
                    class="w-full rounded-lg border border-red-200
                   bg-white px-5 py-3 text-red-600
                   flex items-center justify-center gap-2
                   hover:bg-red-50 transition text-center">
                    <span class="material-symbols-outlined text-[20px]">
                        flag
                    </span>

                    Report Listing
                </a>

                {{-- ============================================= --}}
                {{-- REPORT SELLER --}}
                {{-- ============================================= --}}

                @if($listing->seller)

                <a
                    href="{{ route('reports.create', [
                    'type' => 'seller',
                    'id' => $listing->seller->id
                ]) }}"
                    class="w-full rounded-lg border border-red-200
                       bg-white px-5 py-3 text-red-600
                       flex items-center justify-center gap-2
                       hover:bg-red-50 transition text-center">
                    <span class="material-symbols-outlined text-[20px]">
                        person_alert
                    </span>

                    Report Seller
                </a>

                @endif

                @endif

                @else

                {{-- Login required to report --}}

                <a
                    href="{{ route('login') }}"
                    class="w-full rounded-lg border border-red-200
               bg-white px-5 py-3 text-red-600
               flex items-center justify-center gap-2
               hover:bg-red-50 transition text-center">
                    <span class="material-symbols-outlined text-[20px]">
                        flag
                    </span>

                    Login to Report
                </a>

                @endauth

            </div>

        </div>

    </div>





    {{-- ========================================================= --}}
    {{-- SEND ENQUIRY --}}
    {{-- ========================================================= --}}

    <div
        id="send-enquiry"
        class="mt-16 bg-gray-50 rounded-2xl p-6 md:p-8">


        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}

        <div class="mb-8">

            <span
                class="text-sm uppercase tracking-[0.2em]
                       text-primary">

                Buyer Enquiry

            </span>

            <h2 class="text-2xl md:text-3xl font-bold text-primary mt-2">
                Send Enquiry
            </h2>

            <p class="text-gray-500 mt-2">
                Send your enquiry directly to the seller of this listing.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- LISTING SUMMARY --}}
        {{-- ===================================================== --}}

        <div
            class="mb-7 rounded-xl
                   border border-primary/10
                   bg-white p-5">

            <div class="flex items-start gap-4">


                {{-- Image --}}
                <div
                    class="w-20 h-20 rounded-lg
                           overflow-hidden bg-gray-100
                           shrink-0">

                    @if($listing->image)

                    @if(filter_var($listing->image, FILTER_VALIDATE_URL))

                    <img
                        src="{{ $listing->image }}"
                        alt="{{ $listing->title }}"
                        class="w-full h-full object-cover">

                    @else

                    <img
                        src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                        alt="{{ $listing->title }}"
                        class="w-full h-full object-cover">

                    @endif

                    @else

                    <div class="w-full h-full flex items-center justify-center">

                        <span class="material-symbols-outlined text-gray-400">
                            image
                        </span>

                    </div>

                    @endif

                </div>


                {{-- Listing Info --}}
                <div class="min-w-0 flex-1">

                    <p class="text-xs text-gray-500 mb-1">
                        Enquiring About
                    </p>

                    <h3 class="text-lg font-semibold text-primary">
                        {{ $listing->title }}
                    </h3>

                    <div
                        class="flex flex-wrap gap-x-4 gap-y-1
                               mt-2 text-sm text-gray-500">

                        <span>
                            Listing #{{ $listing->id }}
                        </span>

                        <span>
                            {{ $listing->categoryRelation?->name ?? 'Listing' }}
                        </span>

                        @if($listing->city)

                        <span>

                            {{ $listing->city }}

                            @if($listing->state)
                            , {{ $listing->state }}
                            @endif

                        </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- ENQUIRY FORM --}}
        {{-- ===================================================== --}}

        <form
            action="{{ route('enquiry.store', ['listingId' => $listing->id]) }}"
            method="POST"
            class="space-y-6">

            @csrf


            {{-- ================================================= --}}
            {{-- BUYER INFORMATION --}}
            {{-- ================================================= --}}

            <div>

                <h3 class="text-lg font-semibold text-primary mb-4">
                    Your Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- Name --}}
                    <div>

                        <label
                            for="name"
                            class="block text-sm font-medium text-primary mb-2">

                            Your Name *

                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', auth()->user()->name ?? '') }}"
                            required
                            autocomplete="name"
                            class="w-full rounded-lg
                                   border border-gray-200
                                   bg-white px-4 py-3
                                   outline-none
                                   focus:border-primary
                                   focus:ring-1 focus:ring-primary"
                            placeholder="Enter your name">

                    </div>


                    {{-- Email --}}
                    <div>

                        <label
                            for="email"
                            class="block text-sm font-medium text-primary mb-2">

                            Email *

                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email ?? '') }}"
                            required
                            autocomplete="email"
                            class="w-full rounded-lg
                                   border border-gray-200
                                   bg-white px-4 py-3
                                   outline-none
                                   focus:border-primary
                                   focus:ring-1 focus:ring-primary"
                            placeholder="Enter your email">

                    </div>


                    {{-- Phone --}}
                    <div class="md:col-span-2">

                        <label
                            for="phone"
                            class="block text-sm font-medium text-primary mb-2">

                            Phone Number *

                        </label>

                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            maxlength="10"
                            minlength="10"
                            pattern="[0-9]{10}"
                            value="{{ old('phone') }}"
                            required
                            autocomplete="tel"
                            inputmode="numeric"
                            class="w-full rounded-lg
                                   border border-gray-200
                                   bg-white px-4 py-3
                                   outline-none
                                   focus:border-primary
                                   focus:ring-1 focus:ring-primary"
                            placeholder="Enter 10-digit phone number">

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ENQUIRY DETAILS --}}
            {{-- ================================================= --}}

            <div class="border-t border-gray-200 pt-6">

                <h3 class="text-lg font-semibold text-primary mb-1">
                    Enquiry Details
                </h3>

                <p class="text-sm text-gray-500 mb-5">
                    Provide the quantity and any specific details you want
                    the seller to know.
                </p>


                {{-- Quantity + Unit --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                    {{-- Quantity --}}
                    <div>

                        <label
                            for="quantity"
                            class="block text-sm font-medium text-primary mb-2">

                            Quantity

                        </label>

                        <input
                            id="quantity"
                            type="number"
                            name="quantity"
                            value="{{ old('quantity') }}"
                            min="1"
                            step="1"
                            class="w-full rounded-lg
                                   border border-gray-200
                                   bg-white px-4 py-3
                                   outline-none
                                   focus:border-primary
                                   focus:ring-1 focus:ring-primary"
                            placeholder="e.g. 100">

                    </div>


                    {{-- Unit --}}
                    <div>

                        <label
                            for="unit"
                            class="block text-sm font-medium text-primary mb-2">

                            Unit

                        </label>

                        <input
                            id="unit"
                            type="text"
                            name="unit"
                            value="{{ old('unit') }}"
                            class="w-full rounded-lg
                                   border border-gray-200
                                   bg-white px-4 py-3
                                   outline-none
                                   focus:border-primary
                                   focus:ring-1 focus:ring-primary"
                            placeholder="e.g. bags, tons, sq.ft">

                    </div>

                </div>


                {{-- Message --}}
                <div class="mt-5">

                    <label
                        for="message"
                        class="block text-sm font-medium text-primary mb-2">

                        Enquiry Message *

                    </label>

                    <textarea
                        id="message"
                        name="message"
                        rows="5"
                        maxlength="2000"
                        required
                        class="w-full rounded-lg
                               border border-gray-200
                               bg-white px-4 py-3
                               outline-none resize-none
                               focus:border-primary
                               focus:ring-1 focus:ring-primary"
                        placeholder="Describe your requirement, specifications, delivery needs, or any other details...">{{ old('message') }}</textarea>

                    <p class="text-xs text-gray-500 mt-2">
                        Maximum 2000 characters.
                    </p>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- INFORMATION --}}
            {{-- ===================================================== --}}

            <div
                class="rounded-xl
                       bg-primary/5
                       border border-primary/10
                       px-4 py-4">

                <div class="flex items-start gap-3">

                    <span class="material-symbols-outlined text-primary">
                        info
                    </span>

                    <p class="text-sm text-gray-600 leading-6">

                        Your enquiry will be sent directly to the seller of

                        <strong class="text-primary">
                            {{ $listing->title }}
                        </strong>.

                        The seller can review your enquiry and respond to you.

                    </p>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- SUBMIT --}}
            {{-- ===================================================== --}}

            <button
                type="submit"
                class="w-full
                       bg-primary
                       text-white
                       py-3.5
                       rounded-lg
                       font-semibold
                       flex items-center
                       justify-center gap-2
                       hover:bg-primary/90
                       transition">

                <span class="material-symbols-outlined">
                    send
                </span>

                Send Enquiry to Seller

            </button>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- REVIEWS & RATINGS --}}
    {{-- ========================================================= --}}

    <div
        class="mt-16 overflow-hidden rounded-2xl
               border border-[#DDE9E8]
               bg-white shadow-sm">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}

        <div class="border-b border-[#DDE9E8] px-6 py-6 md:px-8">

            <div class="flex flex-col gap-3 sm:flex-row
                        sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-2xl font-bold text-[#056663]">
                        Reviews & Ratings
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        See what verified buyers have to say about this listing.
                    </p>

                </div>

                @if($totalReviews > 0)

                <div
                    class="inline-flex w-fit items-center rounded-full
                               bg-[#E8F5F4] px-4 py-2
                               text-sm font-semibold text-[#056663]">
                    {{ $totalReviews }}
                    {{ $totalReviews == 1 ? 'Review' : 'Reviews' }}
                </div>

                @endif

            </div>

        </div>


        @if($totalReviews > 0)

        {{-- ================================================= --}}
        {{-- RATING SUMMARY --}}
        {{-- ================================================= --}}

        <div
            class="grid grid-cols-1 gap-6
                       border-b border-[#DDE9E8]
                       bg-[#F7FAFA]
                       p-6 md:grid-cols-3 md:p-8">

            {{-- Overall Rating --}}
            <div
                class="flex flex-col items-center justify-center
                           border-b border-[#DDE9E8]
                           pb-6
                           md:border-b-0 md:border-r md:pb-0">

                <div class="text-4xl font-bold text-gray-900">
                    {{ number_format($averageRating, 1) }}
                </div>

                <div class="mt-2 flex items-center gap-1">

                    @for($i = 1; $i <= 5; $i++)

                        @if($i <=floor($averageRating))

                        <svg
                        class="h-5 w-5 fill-current text-[#F59E0B]"
                        viewBox="0 0 20 20">
                        <path d="M10 1.5l2.63 5.33 5.88.86-4.25 4.14
                                             1 5.85L10 14.92l-5.26 2.76
                                             1-5.85L1.49 7.69l5.88-.86L10 1.5z" />
                        </svg>

                        @else

                        <svg
                            class="h-5 w-5 fill-current text-gray-300"
                            viewBox="0 0 20 20">
                            <path d="M10 1.5l2.63 5.33 5.88.86-4.25 4.14
                                             1 5.85L10 14.92l-5.26 2.76
                                             1-5.85L1.49 7.69l5.88-.86L10 1.5z" />
                        </svg>

                        @endif

                        @endfor

                </div>

                <p class="mt-2 text-sm text-gray-500">
                    Based on {{ $totalReviews }}
                    {{ $totalReviews == 1 ? 'review' : 'reviews' }}
                </p>

            </div>


            {{-- Rating Breakdown --}}
            <div class="md:col-span-2">

                <div class="space-y-3">

                    @for($rating = 5; $rating >= 1; $rating--)

                    @php
                    $count = $ratingCounts[$rating] ?? 0;

                    $percentage = $totalReviews > 0
                    ? round(($count / $totalReviews) * 100)
                    : 0;
                    @endphp

                    <div class="flex items-center gap-3">

                        <div
                            class="flex w-12 shrink-0
                                           items-center gap-1
                                           text-sm font-medium
                                           text-gray-700">
                            <span>{{ $rating }}</span>

                            <svg
                                class="h-4 w-4 fill-current text-[#F59E0B]"
                                viewBox="0 0 20 20">
                                <path d="M10 1.5l2.63 5.33 5.88.86-4.25 4.14
                                                 1 5.85L10 14.92l-5.26 2.76
                                                 1-5.85L1.49 7.69l5.88-.86L10 1.5z" />
                            </svg>

                        </div>

                        <div
                            class="h-2 flex-1 overflow-hidden
                                           rounded-full bg-gray-200">

                            <div
                                class="h-full rounded-full bg-[#056663]"
                                style="width: {{ $percentage }}%"></div>

                        </div>

                        <span
                            class="w-10 text-right text-xs text-gray-500">
                            {{ $count }}
                        </span>

                    </div>

                    @endfor

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- APPROVED REVIEW LIST --}}
        {{-- ================================================= --}}

        <div class="divide-y divide-[#DDE9E8]">

            @foreach($reviews as $review)

            <article class="px-6 py-6 md:px-8">

                <div
                    class="flex flex-col gap-4
                                   sm:flex-row sm:items-start
                                   sm:justify-between">

                    {{-- Buyer --}}
                    <div class="flex items-start gap-4">

                        {{-- Avatar --}}
                        <div
                            class="flex h-11 w-11 shrink-0
                                           items-center justify-center
                                           rounded-full
                                           bg-[#E8F5F4]
                                           text-sm font-bold
                                           text-[#056663]">
                            {{ strtoupper(
                                        substr(
                                            $review->buyer?->name ?? 'B',
                                            0,
                                            1
                                        )
                                    ) }}
                        </div>


                        <div>

                            <div
                                class="flex flex-wrap
                                               items-center gap-2">

                                <h3
                                    class="font-semibold text-gray-900">
                                    {{ $review->buyer?->name ?? 'Verified Buyer' }}
                                </h3>


                                {{-- Verified Buyer --}}
                                <span
                                    class="inline-flex items-center
                                                   rounded-full
                                                   bg-[#E8F5F4]
                                                   px-2 py-0.5
                                                   text-[11px]
                                                   font-medium
                                                   text-[#056663]">

                                    <svg
                                        class="mr-1 h-3 w-3"
                                        fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16
                                                       8 8 0 000 16zm3.707-9.293
                                                       a1 1 0 00-1.414-1.414L9
                                                       9.586 7.707 7.999a1 1
                                                       0 00-1.414 1.414l2.293
                                                       2.293a1 1 0 001.414 0l3.414-3.414z"
                                            clip-rule="evenodd" />
                                    </svg>

                                    Verified Buyer

                                </span>

                            </div>


                            {{-- Stars --}}
                            <div class="mt-1 flex items-center gap-1">

                                @for($i = 1; $i <= 5; $i++)

                                    @if($i <=$review->rating)

                                    <svg
                                        class="h-4 w-4 fill-current
                                                           text-[#F59E0B]"
                                        viewBox="0 0 20 20">
                                        <path d="M10 1.5l2.63 5.33
                                                             5.88.86-4.25 4.14
                                                             1 5.85L10 14.92
                                                             l-5.26 2.76 1-5.85
                                                             -4.25-4.14 5.88-.86
                                                             L10 1.5z" />
                                    </svg>

                                    @else

                                    <svg
                                        class="h-4 w-4 fill-current
                                                           text-gray-300"
                                        viewBox="0 0 20 20">
                                        <path d="M10 1.5l2.63 5.33
                                                             5.88.86-4.25 4.14
                                                             1 5.85L10 14.92
                                                             l-5.26 2.76 1-5.85
                                                             -4.25-4.14 5.88-.86
                                                             L10 1.5z" />
                                    </svg>

                                    @endif

                                    @endfor

                                    <span
                                        class="ml-1 text-xs
                                                   font-medium text-gray-500">
                                        {{ $review->rating }}/5
                                    </span>

                            </div>

                        </div>

                    </div>


                    {{-- Date --}}
                    <time
                        class="text-xs text-gray-400 sm:pt-1">
                        {{ $review->created_at?->format('d M Y') }}
                    </time>

                </div>


                {{-- Review Text --}}
                <div class="mt-4 sm:pl-[60px]">

                    <p
                        class="whitespace-pre-line
                                       text-sm leading-6
                                       text-gray-700">
                        {{ $review->review }}
                    </p>

                </div>

            </article>

            @endforeach

        </div>


        @else

        {{-- ================================================= --}}
        {{-- NO APPROVED REVIEWS --}}
        {{-- ================================================= --}}

        <div class="px-6 py-14 text-center md:px-8">

            <div
                class="mx-auto flex h-14 w-14
                           items-center justify-center
                           rounded-full
                           bg-[#E8F5F4]
                           text-[#056663]">

                <span
                    class="material-symbols-outlined text-3xl">
                    rate_review
                </span>

            </div>


            <h3
                class="mt-4 text-lg font-semibold text-gray-900">
                No reviews yet
            </h3>


            <p
                class="mx-auto mt-2 max-w-md
                           text-sm leading-6 text-gray-500">
                There are no approved reviews for this listing yet.
                Reviews from verified buyers will appear here after
                they are approved by our team.
            </p>

        </div>

        @endif

    </div>

</section>

@endsection