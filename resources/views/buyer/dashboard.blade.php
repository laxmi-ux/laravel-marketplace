<x-buyer-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Buyer Dashboard
            </h2>

            <p class="mt-1 text-sm text-[#64748B]">
                Manage your requirements, enquiries, quotations,
                orders and marketplace activities.
            </p>
        </div>

    </x-slot>


    {{-- =========================================================
         DASHBOARD CONTENT
    ========================================================== --}}

    <div class="min-h-screen bg-[#F7FAFA]">

        <div class="py-6 sm:py-8">

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


                {{-- =================================================
                     WELCOME CARD
                ================================================== --}}

                <div class="mb-6 rounded-xl border
                            border-[#DDE9E8] bg-white
                            p-5 shadow-sm sm:p-6">

                    <div class="flex flex-col gap-5
                                md:flex-row md:items-center
                                md:justify-between">

                        <div class="min-w-0">

                            <p class="mb-1 text-sm font-medium text-[#056663]">
                                Welcome back
                            </p>

                            <h1 class="text-2xl font-semibold
                                       text-gray-800 sm:text-3xl">
                                {{ auth()->user()->name }}!
                            </h1>

                            <p class="mt-2 max-w-2xl text-sm
                                      leading-6 text-[#64748B]
                                      sm:text-base">

                                Search listings, submit requirements,
                                contact sellers, request quotations,
                                manage orders and track your marketplace
                                activities.

                            </p>

                        </div>


                        <div class="shrink-0">

                            <a
                                href="{{ route('listing.index') }}"
                                class="inline-flex w-full items-center
                                       justify-center gap-2 rounded-lg
                                       bg-[#056663] px-5 py-2.5
                                       text-sm font-medium text-white
                                       shadow-sm transition
                                       hover:bg-[#045450]
                                       md:w-auto">

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35m2.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />

                                </svg>

                                Browse Listings

                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PRIMARY STATISTICS
                ================================================== --}}

                <div class="grid grid-cols-1 gap-4
                            sm:grid-cols-2 xl:grid-cols-4">


                    {{-- MY REQUIREMENTS --}}

                    <a
                        href="{{ route('buyer.requirement.index') }}"
                        class="block rounded-xl border
                               border-[#DDE9E8] bg-white
                               p-5 shadow-sm transition
                               hover:border-[#056663]
                               hover:shadow-md">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#64748B]">
                                    My Requirements
                                </p>

                                <h3 class="mt-2 text-3xl font-bold text-[#056663]">
                                    {{ $myRequirement ?? 0 }}
                                </h3>

                            </div>

                            <div class="flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]">

                                <span class="material-symbols-outlined text-[#056663]">
                                    description
                                </span>

                            </div>

                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            General requirements submitted
                        </p>

                    </a>


                    {{-- MY ENQUIRIES --}}

                    <a
                        href="{{ route('buyer.enquiries.index') }}"
                        class="block rounded-xl border
                               border-[#DDE9E8] bg-white
                               p-5 shadow-sm transition
                               hover:border-[#056663]
                               hover:shadow-md">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#64748B]">
                                    My Enquiries
                                </p>

                                <h3 class="mt-2 text-3xl font-bold text-[#056663]">
                                    {{ $myEnquiries ?? 0 }}
                                </h3>

                            </div>

                            <div class="flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]">

                                <span class="material-symbols-outlined text-[#056663]">
                                    chat
                                </span>

                            </div>

                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            Enquiries sent to sellers
                        </p>

                    </a>


                    {{-- MY RFQS --}}

                    <a
                        href="{{ route('buyer.rfqs.index') }}"
                        class="block rounded-xl border
                               border-[#DDE9E8] bg-white
                               p-5 shadow-sm transition
                               hover:border-[#056663]
                               hover:shadow-md">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#64748B]">
                                    My RFQs
                                </p>

                                <h3 class="mt-2 text-3xl font-bold text-[#056663]">
                                    {{ $myRfqs ?? 0 }}
                                </h3>

                            </div>

                            <div class="flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]">

                                <span class="material-symbols-outlined text-[#056663]">
                                    request_quote
                                </span>

                            </div>

                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            Requests for quotation
                        </p>

                    </a>


                    {{-- QUOTATIONS --}}

                    <a
                        href="{{ route('buyer.quotations.index') }}"
                        class="block rounded-xl border
                               border-[#DDE9E8] bg-white
                               p-5 shadow-sm transition
                               hover:border-[#056663]
                               hover:shadow-md">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#64748B]">
                                    Quotations
                                </p>

                                <h3 class="mt-2 text-3xl font-bold text-[#056663]">
                                    {{ $quotationCount ?? 0 }}
                                </h3>

                            </div>

                            <div class="flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]">

                                <span class="material-symbols-outlined text-[#056663]">
                                    receipt_long
                                </span>

                            </div>

                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            Quotations received
                        </p>

                    </a>

                </div>


                {{-- =================================================
                     SECONDARY STATISTICS
                ================================================== --}}

                <div class="mt-4 grid grid-cols-1 gap-4
                            sm:grid-cols-2 xl:grid-cols-4">


                    {{-- MY ORDERS --}}

                    <div
                        class="rounded-xl border border-[#DDE9E8]
                               bg-white p-5 shadow-sm transition
                               hover:border-[#056663]
                               hover:shadow-md">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#64748B]">
                                    My Orders
                                </p>

                                <h3 class="mt-2 text-3xl font-bold text-[#056663]">
                                    {{ $orderCount ?? 0 }}
                                </h3>

                            </div>

                            <div class="flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">


                                <span class="material-symbols-outlined text-[#056663]  group-hover:bg-[#056663]">
                                    shopping_cart
                                </span>

                            </div>

                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            Purchases and orders
                        </p>

                    </div>


                    {{-- SAVED LISTINGS --}}

                    <a
                        href="{{ route('buyer.saved-listings.index') }}"
                        class="group block rounded-xl border
                               border-[#DDE9E8] bg-white
                               p-5 shadow-sm transition
                               hover:border-[#056663]
                               hover:shadow-md">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#64748B]">
                                    Saved Listings
                                </p>

                                <h3 class="mt-2 text-3xl font-bold text-[#056663]">
                                    {{ $savedListings ?? 0 }}
                                </h3>

                            </div>

                            <div class="flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">

                                <span class="material-symbols-outlined
                                             text-[#056663]
                                             group-hover:text-white">
                                    bookmark
                                </span>

                            </div>

                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            Favourite listings
                        </p>

                    </a>


                    {{-- COMPARE --}}

                    <a
                        href="{{ route('buyer.compare.index') }}"
                        class="group block rounded-xl border
                               border-[#DDE9E8] bg-white
                               p-5 shadow-sm transition
                               hover:border-[#056663]
                               hover:shadow-md">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#64748B]">
                                    Compare
                                </p>

                                <h3 class="mt-2 text-3xl font-bold text-[#056663]">
                                    {{ $compareCount ?? 0 }}
                                </h3>

                            </div>

                            <div class="flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">

                                <span class="material-symbols-outlined
                                             text-[#056663]
                                             group-hover:text-white">
                                    compare_arrows
                                </span>

                            </div>

                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            Listings to compare
                        </p>

                    </a>


                    {{-- ACCEPTED BIDS --}}

                    <a
                        href="{{ route('buyer.bids.index') }}"
                        class="group block rounded-xl border
           border-[#DDE9E8] bg-white
           p-5 shadow-sm transition
           hover:border-[#056663]
           hover:shadow-md">

                        <div class="flex items-start justify-between">

                            <div>

                                <p class="text-sm font-medium text-[#64748B]">
                                    Accepted Bids
                                </p>

                                <h3 class="mt-2 text-3xl font-bold text-[#056663]">
                                    {{ $acceptedBids ?? 0 }}
                                </h3>

                            </div>


                            <div
                                class="flex h-11 w-11 items-center
                   justify-center rounded-lg
                   bg-[#E8F5F4]
                   transition
                   group-hover:bg-[#056663]">

                                <span
                                    class="material-symbols-outlined
                       text-[#056663]
                       group-hover:text-white">

                                    gavel

                                </span>

                            </div>

                        </div>


                        <p class="mt-2 text-xs text-gray-400">
                            Bids accepted by sellers
                        </p>

                    </a>
                </div>


                {{-- =================================================
                     QUICK ACTIONS
                ================================================== --}}

                <div class="mt-6 rounded-xl border
                            border-[#DDE9E8] bg-white
                            p-5 shadow-sm sm:p-6">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Quick Actions
                        </h2>

                        <p class="mt-1 text-sm text-[#64748B]">
                            Quickly access your most important buyer activities.
                        </p>

                    </div>


                    <div class="mt-5 grid grid-cols-1 gap-4
                                sm:grid-cols-2 lg:grid-cols-3
                                xl:grid-cols-6">


                        {{-- Browse Listings --}}

                        <a
                            href="{{ route('listing.index') }}"
                            class="group block rounded-xl border
                                   border-[#DDE9E8] bg-white
                                   p-5 transition
                                   hover:border-[#056663]
                                   hover:shadow-md">

                            <div class="mb-4 flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">

                                <span class="material-symbols-outlined
                                             text-[#056663]
                                             group-hover:text-white">
                                    search
                                </span>

                            </div>

                            <h3 class="font-semibold text-gray-800">
                                Browse Listings
                            </h3>

                            <p class="mt-1 text-sm leading-5 text-[#64748B]">
                                Search products, properties, land,
                                services and projects.
                            </p>

                        </a>


                        {{-- My Requirements --}}

                        <a
                            href="{{ route('buyer.requirement.index') }}"
                            class="group block rounded-xl border
                                   border-[#DDE9E8] bg-white
                                   p-5 transition
                                   hover:border-[#056663]
                                   hover:shadow-md">

                            <div class="mb-4 flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">

                                <span class="material-symbols-outlined
                                             text-[#056663]
                                             group-hover:text-white">
                                    description
                                </span>

                            </div>

                            <h3 class="font-semibold text-gray-800">
                                My Requirements
                            </h3>

                            <p class="mt-1 text-sm leading-5 text-[#64748B]">
                                Manage your submitted requirements
                                and track their RFQs.
                            </p>

                        </a>


                        {{-- My Enquiries --}}

                        <a
                            href="{{ route('buyer.enquiries.index') }}"
                            class="group block rounded-xl border
                                   border-[#DDE9E8] bg-white
                                   p-5 transition
                                   hover:border-[#056663]
                                   hover:shadow-md">

                            <div class="mb-4 flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">

                                <span class="material-symbols-outlined
                                             text-[#056663]
                                             group-hover:text-white">
                                    chat
                                </span>

                            </div>

                            <h3 class="font-semibold text-gray-800">
                                My Enquiries
                            </h3>

                            <p class="mt-1 text-sm leading-5 text-[#64748B]">
                                View enquiries sent to sellers
                                and seller responses.
                            </p>

                        </a>


                        {{-- My RFQs --}}

                        <a
                            href="{{ route('buyer.rfqs.index') }}"
                            class="group block rounded-xl border
                                   border-[#DDE9E8] bg-white
                                   p-5 transition
                                   hover:border-[#056663]
                                   hover:shadow-md">

                            <div class="mb-4 flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">

                                <span class="material-symbols-outlined
                                             text-[#056663]
                                             group-hover:text-white">
                                    request_quote
                                </span>

                            </div>

                            <h3 class="font-semibold text-gray-800">
                                My RFQs
                            </h3>

                            <p class="mt-1 text-sm leading-5 text-[#64748B]">
                                View quotation requests sent
                                to matching sellers.
                            </p>

                        </a>


                        {{-- Quotations --}}

                        <a
                            href="{{ route('buyer.quotations.index') }}"
                            class="group block rounded-xl border
                                   border-[#DDE9E8] bg-white
                                   p-5 transition
                                   hover:border-[#056663]
                                   hover:shadow-md">

                            <div class="mb-4 flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">

                                <span class="material-symbols-outlined
                                             text-[#056663]
                                             group-hover:text-white">
                                    receipt_long
                                </span>

                            </div>

                            <h3 class="font-semibold text-gray-800">
                                Quotations
                            </h3>

                            <p class="mt-1 text-sm leading-5 text-[#64748B]">
                                Review quotations received
                                from sellers.
                            </p>

                        </a>


                        {{-- Active Bids --}}

                        <a
                            href="{{ route('buyer.bids.index') }}"
                            class="group block rounded-xl border
                                   border-[#DDE9E8] bg-white
                                   p-5 transition
                                   hover:border-[#056663]
                                   hover:shadow-md">

                            <div class="mb-4 flex h-11 w-11 items-center
                                        justify-center rounded-lg bg-[#E8F5F4]
                                        transition group-hover:bg-[#056663]">

                                <span class="material-symbols-outlined
                                             text-[#056663]
                                             group-hover:text-white">
                                    gavel
                                </span>

                            </div>

                            <h3 class="font-semibold text-gray-800">
                                Active Bids
                            </h3>

                            <p class="mt-1 text-sm leading-5 text-[#64748B]">
                                View and manage your active
                                bidding activities.
                            </p>

                        </a>

                    </div>

                </div>


                {{-- =================================================
                     RECENT ACTIVITY
                ================================================== --}}

                <div class="mt-6 rounded-xl border
                            border-[#DDE9E8] bg-white
                            p-5 shadow-sm sm:p-6">

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Recent Activity
                        </h2>

                        <p class="mt-1 text-sm text-[#64748B]">
                            Your recent marketplace activities.
                        </p>

                    </div>


                    <div class="py-10 text-center sm:py-12">

                        <div class="mx-auto flex h-14 w-14
                                    items-center justify-center
                                    rounded-full bg-[#E8F5F4]">

                            <span class="material-symbols-outlined
                                         text-[#056663]">
                                history
                            </span>

                        </div>


                        <h3 class="mt-4 font-medium text-gray-700">
                            No recent activity
                        </h3>


                        <p class="mx-auto mt-1 max-w-md px-4
                                  text-sm text-[#64748B]">

                            Your requirements, enquiries,
                            quotations, orders and other
                            activities will appear here.

                        </p>


                        <a
                            href="{{ route('listing.index') }}"
                            class="mt-5 inline-flex items-center
                                   justify-center rounded-lg
                                   bg-[#056663] px-5 py-2.5
                                   text-sm font-medium text-white
                                   transition hover:bg-[#045450]">

                            Browse Listings

                        </a>

                    </div>

                </div>


            </div>

        </div>

    </div>

</x-buyer-layout>