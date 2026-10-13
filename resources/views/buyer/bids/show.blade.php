<x-buyer-layout>

    <x-slot name="header">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('buyer.bids.index') }}"
                class="flex h-9 w-9 items-center justify-center
                       rounded-lg text-[#056663]
                       hover:bg-[#E8F5F4]"
            >
                <span class="material-symbols-outlined">
                    arrow_back
                </span>
            </a>

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Bid Details
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    View your bid and seller response.
                </p>
            </div>

        </div>

    </x-slot>


    <div class="min-h-screen bg-[#F7FAFA] py-8">

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            @if(session('success'))

                <div class="mb-6 rounded-xl border border-green-200
                            bg-green-50 px-4 py-4 text-green-700">

                    {{ session('success') }}

                </div>

            @endif


            <div class="grid gap-6 md:grid-cols-3">

                {{-- Listing --}}
                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white p-5 shadow-sm">

                    <p class="text-xs font-semibold uppercase
                              tracking-wide text-gray-400">
                        Listing
                    </p>

                    <h3 class="mt-3 text-lg font-bold text-gray-900">
                        {{ $bid->listing?->title ?? 'Listing unavailable' }}
                    </h3>

                    @if($bid->listing)

                        <a
                            href="{{ route('listing.show', ['id' => $bid->listing->id]) }}"
                            class="mt-4 inline-flex items-center gap-1
                                   text-sm font-semibold text-[#056663]"
                        >
                            View Listing

                            <span class="material-symbols-outlined text-[16px]">
                                arrow_forward
                            </span>
                        </a>

                    @endif

                </div>


                {{-- Bid --}}
                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white p-5 shadow-sm">

                    <p class="text-xs font-semibold uppercase
                              tracking-wide text-gray-400">
                        Your Bid
                    </p>

                    <p class="mt-3 text-2xl font-bold text-[#056663]">
                        ₹{{ number_format($bid->bid_amount, 2) }}
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        Submitted {{ $bid->created_at->format('d M Y, h:i A') }}
                    </p>

                </div>


                {{-- Status --}}
                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white p-5 shadow-sm">

                    <p class="text-xs font-semibold uppercase
                              tracking-wide text-gray-400">
                        Status
                    </p>

                    <div class="mt-3">

                        @if($bid->status === 'pending')

                            <span class="inline-flex rounded-full
                                         bg-amber-50 px-3 py-1.5
                                         text-xs font-semibold text-amber-700">
                                Pending
                            </span>

                        @elseif($bid->status === 'accepted')

                            <span class="inline-flex rounded-full
                                         bg-green-50 px-3 py-1.5
                                         text-xs font-semibold text-green-700">
                                Accepted
                            </span>

                        @elseif($bid->status === 'rejected')

                            <span class="inline-flex rounded-full
                                         bg-red-50 px-3 py-1.5
                                         text-xs font-semibold text-red-600">
                                Rejected
                            </span>

                        @else

                            <span class="inline-flex rounded-full
                                         bg-gray-100 px-3 py-1.5
                                         text-xs font-semibold text-gray-600">
                                {{ ucfirst($bid->status) }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            {{-- Main details --}}
            <div class="mt-6 rounded-2xl border border-[#DDE9E8]
                        bg-white p-6 shadow-sm">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Seller
                    </h3>

                    <p class="mt-2 text-sm text-gray-600">
                        {{ $bid->seller?->bussiness_name
                            ?? $bid->listing?->seller_name
                            ?? 'Seller' }}
                    </p>

                </div>


                @if($bid->message)

                    <div class="mt-6 border-t border-[#DDE9E8] pt-6">

                        <h3 class="text-sm font-semibold text-gray-800">
                            Your Message
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            {{ $bid->message }}
                        </p>

                    </div>

                @endif


                @if($bid->seller_response)

                    <div class="mt-6 rounded-xl bg-[#F7FAFA] p-4">

                        <h3 class="text-sm font-semibold text-gray-800">
                            Seller Response
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            {{ $bid->seller_response }}
                        </p>

                        @if($bid->responded_at)

                            <p class="mt-2 text-xs text-gray-400">
                                Responded
                                {{ $bid->responded_at->format('d M Y, h:i A') }}
                            </p>

                        @endif

                    </div>

                @endif


                {{-- Withdraw --}}
                @if($bid->status === 'pending')

                    <div class="mt-6 border-t border-[#DDE9E8] pt-6">

                        <form
                            action="{{ route('buyer.bids.withdraw', $bid->id) }}"
                            method="POST"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2
                                       rounded-xl border border-red-200
                                       px-4 py-2.5 text-sm font-semibold
                                       text-red-600 hover:bg-red-50"
                            >

                                <span class="material-symbols-outlined text-[18px]">
                                    close
                                </span>

                                Withdraw Bid

                            </button>

                        </form>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-buyer-layout>