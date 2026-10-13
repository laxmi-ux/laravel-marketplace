@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('page_title', 'Admin Dashboard')

@section('content')

{{-- =========================================================
     WELCOME BANNER
========================================================= --}}

<div class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-[#056663] to-[#237A76] p-6 text-white shadow-sm">

    <div class="flex flex-col justify-between gap-5 md:flex-row md:items-center">

        <div>

            <p class="text-sm font-medium text-white/80">
                Welcome back,
            </p>

            <h1 class="mt-1 text-2xl font-bold">
                {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-white/80">
                Monitor listings, users, sellers, requirements,
                enquiries, RFQs, quotations and marketplace activities
                from your admin panel.
            </p>

        </div>

        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/10">

            <span class="material-symbols-outlined text-4xl">
                dashboard
            </span>

        </div>

    </div>

</div>


{{-- =========================================================
     MARKETPLACE STATISTICS
========================================================= --}}

<div class="mb-8">

    <div class="mb-5">

        <h2 class="text-xl font-bold text-slate-800">
            Marketplace Statistics
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Overview of users, sellers and marketplace listings.
        </p>

    </div>


    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">


        {{-- Total Users --}}
        <a
            href="{{ url('/admin/buyers') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Users
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalUsers ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        group
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Registered marketplace users
            </p>

        </a>


        {{-- Total Buyers --}}
        <a
            href="{{ url('/admin/buyers') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Buyers
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalBuyers ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <span class="material-symbols-outlined">
                        person_search
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Registered marketplace buyers
            </p>

        </a>


        {{-- Total Sellers --}}
        <a
            href="{{ url('/admin/sellers') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Sellers
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalSellers ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600">

                    <span class="material-symbols-outlined">
                        store
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Registered marketplace sellers
            </p>

        </a>


        {{-- Total Listings --}}
        <a
            href="{{ url('/admin/listings') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Total Listings
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalListings ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">

                    <span class="material-symbols-outlined">
                        inventory_2
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Marketplace listings
            </p>

        </a>

    </div>

</div>


{{-- =========================================================
     MARKETPLACE ACTIVITY
========================================================= --}}

<div class="mb-8">

    <div class="mb-5">

        <h2 class="text-xl font-bold text-slate-800">
            Marketplace Activity
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Monitor enquiries, requirements, RFQs, quotations and bids.
        </p>

    </div>


    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">


        {{-- Pending Listings --}}
        <a
            href="{{ url('/admin/listings') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-500 hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Pending Listings
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $pendingListings ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                    <span class="material-symbols-outlined">
                        pending_actions
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Listings waiting for admin approval
            </p>

        </a>


        {{-- Buyer Enquiries --}}
        <a
            href="{{ url('/admin/enquiries') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-cyan-500 hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Buyer Enquiries
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalEnquiries ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">

                    <span class="material-symbols-outlined">
                        forum
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Enquiries received from buyers
            </p>

        </a>


        {{-- Requirements --}}
        <a
            href="{{ url('/admin/requirements') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-500 hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Requirements
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalRequirements ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <span class="material-symbols-outlined">
                        description
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Buyer requirements received
            </p>

        </a>


        {{-- RFQs --}}
        <a
            href="{{ url('/admin/rfqs') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-purple-500 hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        RFQs
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalRfqs ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600">

                    <span class="material-symbols-outlined">
                        request_quote
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Requests for quotation
            </p>

        </a>


        {{-- Quotations --}}
        <a
            href="{{ url('/admin/quotations') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-green-500 hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Quotations
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalQuotations ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">

                    <span class="material-symbols-outlined">
                        receipt_long
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Seller quotations submitted
            </p>

        </a>


        {{-- Bids --}}
        <a
            href="{{ url('/admin/bids') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-orange-500 hover:shadow-md"
        >

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Bids
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $totalBids ?? 0 }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">

                    <span class="material-symbols-outlined">
                        gavel
                    </span>

                </div>

            </div>

            <p class="mt-4 text-xs text-slate-400">
                Buyer and seller bid activity
            </p>

        </a>

    </div>

</div>


{{-- =========================================================
     QUICK ACCESS
========================================================= --}}

<div>

    <div class="mb-5">

        <h2 class="text-xl font-bold text-slate-800">
            Quick Access
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Access frequently used admin modules.
        </p>

    </div>


    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">


        {{-- Listing Management --}}
        <a
            href="{{ url('/admin/listings') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        inventory_2
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Listing Management
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Review, approve and manage marketplace listings.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        Manage Listings

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- Buyer Management --}}
        <a
            href="{{ url('/admin/buyers') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        person
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Buyer Management
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        View and manage registered marketplace buyers.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        Manage Buyers

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- Seller Management --}}
        <a
            href="{{ url('/admin/sellers') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        store
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Seller Management
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Manage sellers, verification and featured sellers.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        Manage Sellers

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- Requirements --}}
        <a
            href="{{ url('/admin/requirements') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        assignment
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Requirements
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Review buyer requirements and manage the RFQ process.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        View Requirements

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- RFQs --}}
        <a
            href="{{ url('/admin/rfqs') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        request_quote
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        RFQ Management
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Monitor automatic RFQs generated for sellers.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        Manage RFQs

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- Quotations --}}
        <a
            href="{{ url('/admin/quotations') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        receipt_long
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Quotation Management
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Review seller quotations submitted against RFQs.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        Manage Quotations

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- Bids --}}
        <a
            href="{{ url('/admin/bids') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        gavel
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Bid Management
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Monitor buyer and seller bidding activity.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        Manage Bids

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- Categories --}}
        <a
            href="{{ url('/admin/categories') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        category
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Categories
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Manage marketplace categories and subcategories.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        Manage Categories

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- Locations --}}
        <a
            href="{{ url('/admin/locations') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        location_on
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Locations
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Manage cities, states and marketplace locations.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        Manage Locations

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>


        {{-- Reports --}}
        <a
            href="{{ url('/admin/reports') }}"
            class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-[#056663] hover:shadow-md"
        >

            <div class="flex items-start gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                    <span class="material-symbols-outlined">
                        analytics
                    </span>

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-slate-800">
                        Reports
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        View marketplace activity and performance reports.
                    </p>

                    <div class="mt-3 flex items-center gap-1 text-xs font-semibold text-[#056663]">

                        View Reports

                        <span class="material-symbols-outlined text-[16px]">
                            arrow_forward
                        </span>

                    </div>

                </div>

            </div>

        </a>

    </div>

</div>

@endsection