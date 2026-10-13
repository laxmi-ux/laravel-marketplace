<x-buyer-layout>

    @php
        $categoryKey = strtolower(trim((string) ($order->category ?? '')));

        $isTransaction = in_array(
            $categoryKey,
            ['property', 'properties', 'land'],
            true
        );

        $pageType = $isTransaction ? 'Transaction' : 'Order';

        $backRoute = $isTransaction
            ? route('buyer.transactions.show', $order->id)
            : route('buyer.orders.show', $order->id);

        $listingName = $order->listing?->title
            ?? $order->product_name
            ?? ($isTransaction
                ? ($categoryKey === 'land' ? 'Land Transaction' : 'Property Transaction')
                : 'Marketplace Order');

        $categoryLabel = match ($categoryKey) {
            'property',
            'properties' => 'Property',

            'land' => 'Land',

            default => ucfirst($categoryKey ?: 'Marketplace'),
        };
    @endphp


    <x-slot name="header">

        <div class="flex items-center gap-3">

            <a
                href="{{ $backRoute }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg
                       text-[#056663] hover:bg-[#E8F5F4]"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 19l-7-7 7-7"
                    />

                </svg>
            </a>


            <div>

                <h2 class="text-xl font-semibold text-gray-800">
                    Write a Review
                </h2>

                <p class="text-sm text-gray-500">
                    Share your experience with this {{ strtolower($pageType) }}
                </p>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">


            {{-- Success Message --}}
            @if(session('success'))

                <div class="mb-6 rounded-lg border border-green-200
                            bg-green-50 px-4 py-3 text-sm text-green-700">

                    {{ session('success') }}

                </div>

            @endif


            {{-- Error Message --}}
            @if(session('error'))

                <div class="mb-6 rounded-lg border border-red-200
                            bg-red-50 px-4 py-3 text-sm text-red-700">

                    {{ session('error') }}

                </div>

            @endif


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="mb-6 rounded-lg border border-red-200
                            bg-red-50 px-4 py-3">

                    <ul class="list-disc space-y-1 pl-5 text-sm text-red-600">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Order / Transaction Information --}}
            <div class="mb-6 overflow-hidden rounded-xl border
                        border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] bg-[#F7FAFA] px-6 py-4">

                    <h3 class="text-base font-semibold text-gray-800">

                        {{ $pageType }} Details

                    </h3>

                </div>


                <div class="grid gap-5 px-6 py-5 sm:grid-cols-2">


                    {{-- Order / Transaction Number --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            {{ $pageType }} Number
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $order->order_number }}
                        </p>

                    </div>


                    {{-- Status --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Status
                        </p>

                        <span class="mt-1 inline-flex rounded-full
                                     bg-green-100 px-3 py-1 text-xs
                                     font-semibold text-green-700">

                            {{ ucfirst($order->order_status) }}

                        </span>

                    </div>


                    {{-- Seller --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Seller
                        </p>

                        <p class="mt-1 font-medium text-gray-800">

                            {{ $order->seller?->company_name
                                ?? $order->seller?->business_name
                                ?? $order->seller?->name
                                ?? 'Seller' }}

                        </p>

                    </div>


                    {{-- Listing --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            {{ $isTransaction ? 'Property / Land' : 'Listing' }}
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $listingName }}
                        </p>

                    </div>


                    {{-- Category --}}
                    @if($isTransaction)

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                Category
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                {{ $categoryLabel }}
                            </p>

                        </div>

                    @endif


                </div>

            </div>


            {{-- Review Form --}}
            <div class="overflow-hidden rounded-xl border
                        border-[#DDE9E8] bg-white shadow-sm">


                <div class="border-b border-[#DDE9E8] px-6 py-5">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Rate Your Experience
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">

                        Your feedback helps improve the marketplace experience.

                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('buyer.reviews.store', $order->id) }}"
                    class="space-y-6 px-6 py-6"
                >

                    @csrf


                    {{-- Rating --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700">

                            Rating

                            <span class="text-red-500">*</span>

                        </label>


                        <div
                            class="mt-3 flex items-center gap-2"
                            id="rating-container"
                        >

                            @for($i = 1; $i <= 5; $i++)

                                <button
                                    type="button"
                                    data-rating="{{ $i }}"
                                    class="rating-star text-gray-300
                                           transition hover:text-yellow-400"
                                    aria-label="Rate {{ $i }} out of 5"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-9 w-9"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >

                                        <path d="M12 2.5l2.94 5.96 6.58.96-4.76 4.64 1.12 6.55L12 17.52l-5.88 3.09 1.12-6.55L2.48 9.42l6.58-.96L12 2.5z"/>

                                    </svg>

                                </button>

                            @endfor

                        </div>


                        <input
                            type="hidden"
                            name="rating"
                            id="rating"
                            value="{{ old('rating') }}"
                        >


                        <p
                            id="rating-text"
                            class="mt-2 text-sm font-medium text-[#056663]"
                        >
                            Select a rating
                        </p>


                        @error('rating')

                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Review --}}
                    <div>

                        <label
                            for="review"
                            class="block text-sm font-semibold text-gray-700"
                        >

                            Your Review

                            <span class="text-red-500">*</span>

                        </label>


                        <textarea
                            id="review"
                            name="review"
                            rows="6"
                            maxlength="1000"
                            placeholder="Share your experience with the seller, product, property, land, service, or overall {{ strtolower($pageType) }}..."
                            class="mt-2 block w-full rounded-lg border
                                   border-[#DDE9E8] px-4 py-3 text-sm
                                   text-gray-700 shadow-sm
                                   focus:border-[#056663]
                                   focus:ring-[#056663]"
                        >{{ old('review') }}</textarea>


                        <div class="mt-1 flex justify-between">

                            <p class="text-xs text-gray-500">
                                Maximum 1000 characters.
                            </p>

                            <p
                                id="character-count"
                                class="text-xs text-gray-400"
                            >
                                0 / 1000
                            </p>

                        </div>


                        @error('review')

                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Buttons --}}
                    <div class="flex flex-col-reverse gap-3 border-t
                                border-[#DDE9E8] pt-6 sm:flex-row
                                sm:justify-end">


                        <a
                            href="{{ $backRoute }}"
                            class="inline-flex items-center justify-center
                                   rounded-lg border border-[#DDE9E8]
                                   px-5 py-2.5 text-sm font-medium
                                   text-gray-700 transition
                                   hover:bg-gray-50"
                        >

                            Cancel

                        </a>


                        <button
                            type="submit"
                            class="inline-flex items-center justify-center
                                   rounded-lg bg-[#056663] px-5 py-2.5
                                   text-sm font-semibold text-white
                                   transition hover:bg-[#045550]"
                        >

                            Submit Review

                        </button>

                    </div>

                </form>

            </div>


            {{-- Notice --}}
            <div class="mt-6 rounded-lg border border-[#DDE9E8]
                        bg-[#F7FAFA] px-4 py-3">

                <div class="flex gap-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mt-0.5 h-5 w-5 flex-shrink-0 text-[#056663]"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"
                        />

                    </svg>


                    <p class="text-sm text-gray-600">

                        Your review will be submitted for approval before
                        being displayed publicly.

                    </p>

                </div>

            </div>


        </div>

    </div>


    {{-- Rating + Character Count Script --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const stars =
                document.querySelectorAll('.rating-star');

            const ratingInput =
                document.getElementById('rating');

            const ratingText =
                document.getElementById('rating-text');


            const ratingLabels = {

                1: '1 Star - Poor',

                2: '2 Stars - Fair',

                3: '3 Stars - Good',

                4: '4 Stars - Very Good',

                5: '5 Stars - Excellent'

            };


            function updateStars(rating) {

                stars.forEach(function (star) {

                    const starRating =
                        parseInt(star.dataset.rating);

                    if (starRating <= rating) {

                        star.classList.remove(
                            'text-gray-300'
                        );

                        star.classList.add(
                            'text-yellow-400'
                        );

                    } else {

                        star.classList.remove(
                            'text-yellow-400'
                        );

                        star.classList.add(
                            'text-gray-300'
                        );

                    }

                });


                ratingText.textContent =
                    ratingLabels[rating] ||
                    'Select a rating';

            }


            stars.forEach(function (star) {

                star.addEventListener(
                    'click',
                    function () {

                        const rating =
                            parseInt(
                                this.dataset.rating
                            );

                        ratingInput.value =
                            rating;

                        updateStars(rating);

                    }
                );


                star.addEventListener(
                    'mouseenter',
                    function () {

                        const rating =
                            parseInt(
                                this.dataset.rating
                            );

                        updateStars(rating);

                    }
                );

            });


            document
                .getElementById('rating-container')
                .addEventListener(
                    'mouseleave',
                    function () {

                        const currentRating =
                            parseInt(
                                ratingInput.value || 0
                            );

                        updateStars(
                            currentRating
                        );

                    }
                );


            // Restore old rating after validation error

            const oldRating =
                parseInt(
                    ratingInput.value || 0
                );

            if (oldRating) {

                updateStars(oldRating);

            }


            // Character counter

            const review =
                document.getElementById('review');

            const characterCount =
                document.getElementById(
                    'character-count'
                );


            function updateCharacterCount() {

                characterCount.textContent =
                    review.value.length +
                    ' / 1000';

            }


            review.addEventListener(
                'input',
                updateCharacterCount
            );


            updateCharacterCount();

        });

    </script>

</x-buyer-layout>
