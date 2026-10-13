<x-buyer-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-[#E8F5F4]">

                    <span class="material-symbols-outlined
                                 text-[23px] text-[#056663]">
                        gavel
                    </span>

                </div>

                <div>

                    <h2 class="text-2xl font-bold text-gray-900">
                        Active Bids
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Track your bids and seller responses.
                    </p>

                </div>

            </div>


            <a
                href="{{ route('listing.index') }}"
                class="inline-flex items-center justify-center gap-2
                       rounded-xl bg-[#056663] px-5 py-2.5
                       text-sm font-semibold text-white
                       hover:bg-[#045450]"
            >

                <span class="material-symbols-outlined text-[19px]">
                    search
                </span>

                Browse Listings

            </a>

        </div>

    </x-slot>


    <div class="min-h-screen bg-[#F7FAFA] py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            @if(session('success'))

                <div class="mb-6 rounded-xl border border-green-200
                            bg-green-50 px-4 py-4 text-green-700">

                    <div class="flex items-center gap-2">

                        <span class="material-symbols-outlined">
                            check_circle
                        </span>

                        <p class="text-sm font-semibold">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            @endif


            @if($bids->count())

                <div class="overflow-hidden rounded-2xl
                            border border-[#DDE9E8]
                            bg-white shadow-sm">

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[900px]">

                            <thead>

                                <tr class="border-b border-[#DDE9E8]
                                           bg-[#F7FAFA]">

                                    <th class="px-5 py-4 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wide text-gray-500">
                                        Listing
                                    </th>

                                    <th class="px-5 py-4 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wide text-gray-500">
                                        Seller
                                    </th>

                                    <th class="px-5 py-4 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wide text-gray-500">
                                        Your Bid
                                    </th>

                                    <th class="px-5 py-4 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wide text-gray-500">
                                        Status
                                    </th>

                                    <th class="px-5 py-4 text-right text-xs
                                               font-semibold uppercase
                                               tracking-wide text-gray-500">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-[#DDE9E8]">

                                @foreach($bids as $bid)

                                    <tr class="transition hover:bg-[#FAFCFC]">

                                        {{-- Listing --}}
                                        <td class="px-5 py-5">

                                            <div class="flex items-center gap-3">

                                                <div class="h-14 w-14 shrink-0
                                                            overflow-hidden rounded-lg
                                                            bg-[#F1F6F6]">

                                                    @if($bid->listing?->image)

                                                        @if(filter_var($bid->listing->image, FILTER_VALIDATE_URL))

                                                            <img
                                                                src="{{ $bid->listing->image }}"
                                                                class="h-full w-full object-cover"
                                                                alt="{{ $bid->listing->title }}"
                                                            >

                                                        @else

                                                            <img
                                                                src="{{ route('uploads.file', ['path' => $bid->listing->image]) }}"
                                                                class="h-full w-full object-cover"
                                                                alt="{{ $bid->listing->title }}"
                                                            >

                                                        @endif

                                                    @else

                                                        <div class="flex h-full items-center justify-center">

                                                            <span class="material-symbols-outlined
                                                                         text-gray-300">
                                                                image
                                                            </span>

                                                        </div>

                                                    @endif

                                                </div>


                                                <div class="min-w-0">

                                                    <p class="max-w-[250px]
                                                              truncate text-sm
                                                              font-semibold text-gray-900">

                                                        {{ $bid->listing?->title ?? 'Listing unavailable' }}

                                                    </p>

                                                    <p class="mt-1 text-xs text-gray-400">

                                                        {{ $bid->created_at->format('d M Y') }}

                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- Seller --}}
                                        <td class="px-5 py-5">

                                            <p class="text-sm font-semibold text-gray-800">

                                                {{ $bid->seller?->bussiness_name
                                                    ?? $bid->listing?->seller_name
                                                    ?? 'Seller' }}

                                            </p>

                                        </td>


                                        {{-- Amount --}}
                                        <td class="px-5 py-5">

                                            <p class="text-sm font-bold text-[#056663]">

                                                ₹{{ number_format($bid->bid_amount, 2) }}

                                            </p>

                                        </td>


                                        {{-- Status --}}
                                        <td class="px-5 py-5">

                                            @if($bid->status === 'pending')

                                                <span class="inline-flex items-center gap-1.5
                                                             rounded-full bg-amber-50
                                                             px-3 py-1.5 text-xs
                                                             font-semibold text-amber-700">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                                                    Pending

                                                </span>

                                            @elseif($bid->status === 'accepted')

                                                <span class="inline-flex items-center gap-1.5
                                                             rounded-full bg-green-50
                                                             px-3 py-1.5 text-xs
                                                             font-semibold text-green-700">

                                                    <span class="material-symbols-outlined text-[15px]">
                                                        check_circle
                                                    </span>

                                                    Accepted

                                                </span>

                                            @elseif($bid->status === 'rejected')

                                                <span class="inline-flex items-center gap-1.5
                                                             rounded-full bg-red-50
                                                             px-3 py-1.5 text-xs
                                                             font-semibold text-red-600">

                                                    <span class="material-symbols-outlined text-[15px]">
                                                        cancel
                                                    </span>

                                                    Rejected

                                                </span>

                                            @else

                                                <span class="inline-flex rounded-full
                                                             bg-gray-100 px-3 py-1.5
                                                             text-xs font-semibold
                                                             text-gray-600">

                                                    {{ ucfirst($bid->status) }}

                                                </span>

                                            @endif

                                        </td>


                                        {{-- Action --}}
                                        <td class="px-5 py-5 text-right">

                                            <a
                                                href="{{ route('buyer.bids.show', $bid->id) }}"
                                                class="inline-flex items-center gap-1.5
                                                       rounded-lg border
                                                       border-[#DDE9E8]
                                                       px-3 py-2 text-xs
                                                       font-semibold text-[#056663]
                                                       hover:bg-[#E8F5F4]"
                                            >

                                                View

                                                <span class="material-symbols-outlined text-[16px]">
                                                    arrow_forward
                                                </span>

                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                <div class="mt-5">
                    {{ $bids->links() }}
                </div>

            @else

                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white px-6 py-20 text-center shadow-sm">

                    <div class="mx-auto flex h-20 w-20 items-center
                                justify-center rounded-2xl bg-[#E8F5F4]">

                        <span class="material-symbols-outlined
                                     text-4xl text-[#056663]">
                            gavel
                        </span>

                    </div>

                    <h3 class="mt-6 text-xl font-bold text-gray-900">
                        No Bids Yet
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        Browse marketplace listings and place a bid on
                        listings that allow bidding.
                    </p>

                    <a
                        href="{{ route('listing.index') }}"
                        class="mt-7 inline-flex items-center gap-2
                               rounded-xl bg-[#056663]
                               px-6 py-3 text-sm font-semibold
                               text-white hover:bg-[#045450]"
                    >

                        <span class="material-symbols-outlined">
                            search
                        </span>

                        Browse Listings

                    </a>

                </div>

            @endif

        </div>

    </div>

</x-buyer-layout>