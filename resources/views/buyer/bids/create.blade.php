<x-buyer-layout>

    <x-slot name="header">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('listing.show', ['id' => $listing->id]) }}"
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
                    Place Your Bid
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Submit your offer for this listing.
                </p>

            </div>

        </div>

    </x-slot>


    <div class="min-h-screen bg-[#F7FAFA] py-8">

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <div class="grid gap-6 lg:grid-cols-5">


                {{-- Listing --}}
                <div class="lg:col-span-2">

                    <div class="overflow-hidden rounded-2xl
                                border border-[#DDE9E8]
                                bg-white shadow-sm">

                        <div class="h-52 bg-[#F1F6F6]">

                            @if($listing->image)

                                @if(filter_var($listing->image, FILTER_VALIDATE_URL))

                                    <img
                                        src="{{ $listing->image }}"
                                        alt="{{ $listing->title }}"
                                        class="h-full w-full object-cover"
                                    >

                                @else

                                    <img
                                        src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                                        alt="{{ $listing->title }}"
                                        class="h-full w-full object-cover"
                                    >

                                @endif

                            @else

                                <div class="flex h-full items-center justify-center">
                                    <span class="material-symbols-outlined
                                                 text-5xl text-[#B8CECC]">
                                        image
                                    </span>
                                </div>

                            @endif

                        </div>


                        <div class="p-5">

                            @if($listing->categoryRelation)

                                <span class="inline-flex rounded-full
                                             bg-[#E8F5F4]
                                             px-3 py-1 text-xs
                                             font-semibold text-[#056663]">

                                    {{ $listing->categoryRelation->name }}

                                </span>

                            @endif


                            <h1 class="mt-3 text-xl font-bold text-gray-900">
                                {{ $listing->title }}
                            </h1>


                            @if($listing->price)

                                <p class="mt-3 text-lg font-bold text-[#056663]">

                                    ₹{{ number_format($listing->price) }}

                                    @if($listing->price_unit)
                                        <span class="text-xs font-normal text-gray-400">
                                            / {{ $listing->price_unit }}
                                        </span>
                                    @endif

                                </p>

                            @endif


                            @if($listing->seller)

                                <div class="mt-5 flex items-center gap-3
                                            border-t border-[#DDE9E8] pt-5">

                                    <div class="flex h-10 w-10 items-center
                                                justify-center rounded-lg
                                                bg-[#E8F5F4]">

                                        <span class="material-symbols-outlined
                                                     text-[#056663]">
                                            business
                                        </span>

                                    </div>

                                    <div>

                                        <p class="text-xs text-gray-400">
                                            Seller
                                        </p>

                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $listing->seller->bussiness_name }}
                                        </p>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- Bid Form --}}
                <div class="lg:col-span-3">

                    <div class="rounded-2xl border border-[#DDE9E8]
                                bg-white p-6 shadow-sm">

                        <div class="mb-6">

                            <h3 class="text-lg font-bold text-gray-900">
                                Submit Your Bid
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Enter the amount you are willing to offer.
                            </p>

                        </div>


                        @if($errors->any())

                            <div class="mb-5 rounded-xl border border-red-200
                                        bg-red-50 px-4 py-3">

                                <ul class="space-y-1 text-sm text-red-600">

                                    @foreach($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form
                            action="{{ route('buyer.bids.store', $listing->id) }}"
                            method="POST"
                            class="space-y-5"
                        >

                            @csrf


                            {{-- Bid Amount --}}
                            <div>

                                <label
                                    for="bid_amount"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Your Bid Amount
                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">

                                    <span class="absolute left-4 top-1/2
                                                 -translate-y-1/2
                                                 text-gray-400">
                                        ₹
                                    </span>

                                    <input
                                        type="number"
                                        name="bid_amount"
                                        id="bid_amount"
                                        value="{{ old('bid_amount') }}"
                                        min="1"
                                        step="0.01"
                                        required
                                        placeholder="Enter your bid amount"
                                        class="w-full rounded-xl border
                                               border-[#DDE9E8]
                                               py-3 pl-9 pr-4
                                               text-sm text-gray-900
                                               outline-none
                                               focus:border-[#056663]
                                               focus:ring-2
                                               focus:ring-[#056663]/10"
                                    >

                                </div>

                            </div>


                            {{-- Message --}}
                            <div>

                                <label
                                    for="message"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Message
                                    <span class="text-xs font-normal text-gray-400">
                                        (Optional)
                                    </span>
                                </label>

                                <textarea
                                    name="message"
                                    id="message"
                                    rows="5"
                                    maxlength="2000"
                                    placeholder="Add any details or terms related to your bid..."
                                    class="w-full rounded-xl border
                                           border-[#DDE9E8]
                                           px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           focus:border-[#056663]
                                           focus:ring-2
                                           focus:ring-[#056663]/10"
                                >{{ old('message') }}</textarea>

                            </div>


                            {{-- Information --}}
                            <div class="rounded-xl bg-[#F7FAFA]
                                        px-4 py-3">

                                <div class="flex items-start gap-2">

                                    <span class="material-symbols-outlined
                                                 text-[18px] text-[#056663]">
                                        info
                                    </span>

                                    <p class="text-xs leading-5 text-gray-500">
                                        Your bid will be sent to the seller.
                                        The seller can accept or reject your offer.
                                    </p>

                                </div>

                            </div>


                            {{-- Submit --}}
                            <div class="flex flex-col gap-3 sm:flex-row">

                                <a
                                    href="{{ route('listing.show', ['id' => $listing->id]) }}"
                                    class="inline-flex flex-1 items-center
                                           justify-center rounded-xl
                                           border border-[#DDE9E8]
                                           px-5 py-3 text-sm
                                           font-semibold text-gray-600
                                           hover:bg-gray-50"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    class="inline-flex flex-1 items-center
                                           justify-center gap-2 rounded-xl
                                           bg-[#056663] px-5 py-3
                                           text-sm font-semibold text-white
                                           hover:bg-[#045450]"
                                >

                                    <span class="material-symbols-outlined text-[19px]">
                                        gavel
                                    </span>

                                    Submit Bid

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-buyer-layout>