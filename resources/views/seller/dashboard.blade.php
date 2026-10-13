<x-seller-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-[#123B38]">
                Seller Dashboard
            </h2>

            <p class="text-sm text-[#4F6663]">
                Manage your marketplace business
            </p>

        </div>

    </x-slot>


    <div class="bg-[#F5F9F8] px-4 py-6 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-7xl">


            {{-- Welcome --}}
            <div class="rounded-2xl bg-[#004C47] p-6 shadow-sm">

                <p class="text-sm font-medium text-[#8FD3CC]">
                    Seller Account
                </p>

                <h1 class="mt-2 text-2xl font-bold text-white">
                    Welcome, {{ auth()->user()->name }}!
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-[#B7D7D3]">
                    Manage your listings, enquiries, quotations and
                    marketplace activities from your dashboard.
                </p>

            </div>


            {{-- =================================================
                 STATISTICS
            ================================================== --}}

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">


                {{-- Total Listings --}}
                <div class="rounded-xl border border-[#D3E3E0] bg-white p-5 shadow-sm">

                    <p class="text-sm font-medium text-[#4F6663]">
                        Total Listings
                    </p>

                    <h3 class="mt-2 text-3xl font-bold text-[#123B38]">
                        {{ $totalListings }}
                    </h3>

                    <p class="mt-2 text-xs text-[#718A86]">
                        All your marketplace listings
                    </p>

                </div>


                {{-- Approved --}}
                <div class="rounded-xl border border-[#D3E3E0] bg-white p-5 shadow-sm">

                    <p class="text-sm font-medium text-[#4F6663]">
                        Approved Listings
                    </p>

                    <h3 class="mt-2 text-3xl font-bold text-[#123B38]">
                        {{ $approvedListings }}
                    </h3>

                    <p class="mt-2 text-xs text-[#718A86]">
                        Currently available listings
                    </p>

                </div>


                {{-- Pending --}}
                <div class="rounded-xl border border-[#D3E3E0] bg-white p-5 shadow-sm">

                    <p class="text-sm font-medium text-[#4F6663]">
                        Pending Listings
                    </p>

                    <h3 class="mt-2 text-3xl font-bold text-[#123B38]">
                        {{ $pendingListings }}
                    </h3>

                    <p class="mt-2 text-xs text-[#718A86]">
                        Awaiting admin approval
                    </p>

                </div>


                {{-- Buyer Enquiries --}}
                <div class="rounded-xl border border-[#D3E3E0] bg-white p-5 shadow-sm">

                    <p class="text-sm font-medium text-[#4F6663]">
                        Buyer Enquiries
                    </p>

                    <h3 class="mt-2 text-3xl font-bold text-[#123B38]">
                        {{ $buyerEnquiries }}
                    </h3>

                    <p class="mt-2 text-xs text-[#718A86]">
                        Enquiries received from buyers
                    </p>

                </div>

                <!-- RFQS  -->

                <div class="rounded-2xl border border-[#D3E3E0] bg-white p-5">

                    <p class="text-sm font-medium text-gray-500">
                        New RFQs
                    </p>

                    <p class="mt-2 text-3xl font-bold text-[#056663]">
                        {{ $rfqCount }}
                    </p>

                    

                </div>

                {{-- Quotations --}}
                <div class="rounded-xl border border-[#D3E3E0] bg-white p-5 shadow-sm">

                    <p class="text-sm font-medium text-[#4F6663]">
                        Quotations Sent
                    </p>

                    <h3 class="mt-2 text-3xl font-bold text-[#123B38]">
                        {{ $quotationsSent }}
                    </h3>

                    <p class="mt-2 text-xs text-[#718A86]">
                        Quotations submitted to buyers
                    </p>

                </div>


                {{-- Accepted Quotations --}}
                <div class="rounded-xl border border-[#D3E3E0] bg-white p-5 shadow-sm">

                    <p class="text-sm font-medium text-[#4F6663]">
                        Accepted Quotations
                    </p>

                    <h3 class="mt-2 text-3xl font-bold text-[#123B38]">
                        {{ $acceptedQuotations }}
                    </h3>

                    <p class="mt-2 text-xs text-[#718A86]">
                        Quotations accepted by buyers
                    </p>

                </div>

            </div>


            {{-- =================================================
                 RECENT LISTINGS
            ================================================== --}}

            <div class="mt-6 rounded-2xl border border-[#D3E3E0] bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-xl font-bold text-[#123B38]">
                            Recent Listings
                        </h2>

                        <p class="mt-1 text-sm text-[#4F6663]">
                            Your recently created listings.
                        </p>

                    </div>


                    <a
                        href="{{ route('seller.listings.index') }}"
                        class="text-sm font-semibold text-[#00665F]">
                        View All →
                    </a>

                </div>


                @if(isset($listings) && $listings->count() > 0)

                <div class="mt-5 divide-y divide-[#D3E3E0]">

                    @foreach($listings->take(5) as $listing)

                    <div class="flex items-center justify-between gap-4 py-4">

                        <div class="min-w-0">

                            <h3 class="truncate font-semibold text-[#123B38]">
                                {{ $listing->title }}
                            </h3>

                            <p class="mt-1 text-sm text-[#4F6663]">
                                {{ ucfirst(str_replace('_', ' ', $listing->category)) }}
                            </p>

                        </div>


                        <div class="flex shrink-0 items-center gap-3">

                            <span class="rounded-full bg-[#EAF5F3] px-3 py-1 text-xs font-semibold text-[#00665F]">
                                {{ ucfirst($listing->status) }}
                            </span>

                            <a
                                href="{{ route('seller.listings.edit', $listing->id) }}"
                                class="text-sm font-semibold text-[#00665F]">
                                Edit
                            </a>

                        </div>

                    </div>

                    @endforeach

                </div>

                @else

                <div class="py-12 text-center">

                    <h3 class="font-semibold text-[#123B38]">
                        No listings yet
                    </h3>

                    <p class="mt-1 text-sm text-[#4F6663]">
                        Start by adding your first listing.
                    </p>

                </div>

                @endif

            </div>

        </div>

    </div>

</x-seller-layout>