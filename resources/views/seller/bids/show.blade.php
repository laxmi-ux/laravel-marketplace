<x-seller-layout>

    <x-slot name="header">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('seller.bids.index') }}"
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
                    Review the buyer's offer.
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


                {{-- Buyer --}}
                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white p-5 shadow-sm">

                    <p class="text-xs font-semibold uppercase
                              tracking-wide text-gray-400">
                        Buyer
                    </p>

                    <p class="mt-3 text-sm font-bold text-gray-900">
                        {{ $bid->user?->name ?? 'Buyer' }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        {{ $bid->user?->email ?? '' }}
                    </p>

                </div>


                {{-- Bid --}}
                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white p-5 shadow-sm">

                    <p class="text-xs font-semibold uppercase
                              tracking-wide text-gray-400">
                        Bid Amount
                    </p>

                    <p class="mt-3 text-2xl font-bold text-[#056663]">
                        ₹{{ number_format($bid->bid_amount, 2) }}
                    </p>

                </div>

            </div>


            {{-- Message --}}
            @if($bid->message)

                <div class="mt-6 rounded-2xl border border-[#DDE9E8]
                            bg-white p-6 shadow-sm">

                    <h3 class="text-sm font-bold text-gray-900">
                        Buyer's Message
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-gray-600">
                        {{ $bid->message }}
                    </p>

                </div>

            @endif


            {{-- Actions --}}
            @if($bid->status === 'pending')

                <div class="mt-6 rounded-2xl border border-[#DDE9E8]
                            bg-white p-6 shadow-sm">

                    <h3 class="text-lg font-bold text-gray-900">
                        Respond to Bid
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Accept the offer or reject it with a response.
                    </p>


                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">

                        {{-- Accept --}}
                        <form
                            action="{{ route('seller.bids.accept', $bid->id) }}"
                            method="POST"
                            class="flex-1"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="w-full inline-flex items-center
                                       justify-center gap-2 rounded-xl
                                       bg-[#056663] px-5 py-3
                                       text-sm font-semibold text-white
                                       hover:bg-[#045450]"
                            >

                                <span class="material-symbols-outlined">
                                    check_circle
                                </span>

                                Accept Bid

                            </button>

                        </form>


                        {{-- Reject --}}
                        <form
                            action="{{ route('seller.bids.reject', $bid->id) }}"
                            method="POST"
                            class="flex-1"
                        >

                            @csrf

                            <textarea
                                name="seller_response"
                                rows="3"
                                placeholder="Reason for rejection (optional)"
                                class="mb-3 w-full rounded-xl border
                                       border-[#DDE9E8] px-4 py-3
                                       text-sm outline-none
                                       focus:border-[#056663]"
                            ></textarea>

                            <button
                                type="submit"
                                class="w-full inline-flex items-center
                                       justify-center gap-2 rounded-xl
                                       border border-red-200
                                       px-5 py-3 text-sm font-semibold
                                       text-red-600 hover:bg-red-50"
                            >

                                <span class="material-symbols-outlined">
                                    cancel
                                </span>

                                Reject Bid

                            </button>

                        </form>

                    </div>

                </div>

            @else

                <div class="mt-6 rounded-2xl border border-[#DDE9E8]
                            bg-white p-6 shadow-sm">

                    <p class="text-sm text-gray-500">
                        This bid has already been
                        <strong>{{ $bid->status }}</strong>.
                    </p>

                    @if($bid->seller_response)

                        <p class="mt-3 text-sm text-gray-600">
                            {{ $bid->seller_response }}
                        </p>

                    @endif

                </div>

            @endif

        </div>

    </div>

</x-seller-layout>