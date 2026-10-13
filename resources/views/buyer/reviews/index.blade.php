<x-buyer-layout>

    <div class="min-h-screen bg-[#F7FAFA]">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col mt-10 px-10 gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Reviews & Ratings
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        View and manage the reviews you have submitted for completed orders.
                    </p>
                </div>

            </div>
        </div>


        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 mt-20  bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif


        {{-- Reviews --}}
        @if($reviews->count())

            <div class="space-y-4 m-6">

                @foreach($reviews as $review)

                    <div class="rounded-2xl border border-[#DDE9E8] bg-white px-6 py-5 shadow-sm">

                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                            {{-- Left --}}
                            <div class="flex-1">

                                {{-- Listing --}}
                                <div class="flex items-start gap-4">

                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">

                                        <svg
                                            class="h-6 w-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0l-8 4m0 0L4 7m8 4v10" />

                                        </svg>

                                    </div>


                                    <div class="min-w-0">

                                        <h2 class="font-semibold text-slate-800">
                                            {{ $review->listing?->title ?? 'Listing' }}
                                        </h2>

                                        @if($review->order)
                                            <p class="mt-1 text-sm text-slate-500">
                                                Order:
                                                <span class="font-medium text-slate-700">
                                                    {{ $review->order->order_number }}
                                                </span>
                                            </p>
                                        @endif

                                        @if($review->seller)
                                            <p class="mt-1 text-sm text-slate-500">
                                                Seller:
                                                <span class="font-medium text-slate-700">
                                                    {{ $review->seller->business_name ?? 'Seller' }}
                                                </span>
                                            </p>
                                        @endif

                                    </div>

                                </div>


                                {{-- Rating --}}
                                <div class="mt-5 flex items-center gap-1">

                                    @for($i = 1; $i <= 5; $i++)

                                        @if($i <= $review->rating)

                                            <svg
                                                class="h-5 w-5 text-yellow-400"
                                                fill="currentColor"
                                                viewBox="0 0 20 20">

                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.036a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.802-2.036a1 1 0 00-1.176 0l-2.802 2.036c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L3.98 8.719c-.783-.57-.38-1.81.588-1.81H8.03a1 1 0 00.95-.69l1.07-3.292z" />

                                            </svg>

                                        @else

                                            <svg
                                                class="h-5 w-5 text-gray-300"
                                                fill="currentColor"
                                                viewBox="0 0 20 20">

                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.036a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.802-2.036a1 1 0 00-1.176 0l-2.802 2.036c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L3.98 8.719c-.783-.57-.38-1.81.588-1.81H8.03a1 1 0 00.95-.69l1.07-3.292z" />

                                            </svg>

                                        @endif

                                    @endfor

                                    <span class="ml-2 text-sm font-medium text-slate-600">
                                        {{ $review->rating }}/5
                                    </span>

                                </div>


                                {{-- Review --}}
                                <div class="mt-4">

                                    <p class="text-sm leading-6 text-slate-600">
                                        {{ $review->review }}
                                    </p>

                                </div>

                            </div>


                            {{-- Right --}}
                            <div class="flex flex-row items-center gap-3 lg:flex-col lg:items-end">

                                {{-- Status --}}
                                @php
                                    $status = strtolower($review->status ?? 'pending');

                                    $statusClass = match($status) {
                                        'approved' => 'bg-green-50 text-green-700 border-green-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    };
                                @endphp

                                <span class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>

                                <span class="text-xs text-slate-400">
                                    {{ $review->created_at?->format('d M Y') }}
                                </span>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}
            @if($reviews->hasPages())
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif


        @else

            {{-- Empty State --}}
            <div class="rounded-2xl border border-[#DDE9E8] bg-white px-10 py-14 text-center shadow-sm">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#E8F5F4] text-[#056663]">

                    <svg
                        class="h-8 w-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.914c.969 0 1.371 1.24.588 1.81l-3.977 2.89a1 1 0 00-.363 1.118l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.977-2.89a1 1 0 00-1.176 0l-3.977 2.89c-.784.57-1.838-.197-1.539-1.118l1.519-4.674a1 1 0 00-.363-1.118l-3.977-2.89c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69l1.519-4.674z" />

                    </svg>

                </div>


                <h2 class="mt-5 text-lg font-semibold text-slate-800">
                    No Reviews Yet
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                    You have not submitted any reviews yet.
                    Once you complete an order, you can share your experience with the seller.
                </p>


                <a
                    href="{{ route('buyer.orders.index') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#04514F]">

                    View My Orders

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7" />

                    </svg>

                </a>

            </div>

        @endif

    </div>

</x-buyer-layout>