<x-seller-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-2xl font-bold text-gray-900">
                Bids
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Review and manage bids received from buyers.
            </p>

        </div>

    </x-slot>


    <div class="min-h-screen bg-[#F7FAFA] py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if(session('success'))

            <div class="mb-6 rounded-xl border border-green-200
                            bg-green-50 px-4 py-4 text-green-700">

                {{ session('success') }}

            </div>

            @endif


            @if($bids->count())

            <div class="overflow-hidden rounded-2xl
                            border border-[#DDE9E8]
                            bg-white shadow-sm">

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px]">

                        <thead class="bg-[#F7FAFA]">

                            <tr class="border-b border-[#DDE9E8]">

                                <th class="px-5 py-4 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wide text-gray-500">
                                    Listing
                                </th>

                                <th class="px-5 py-4 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wide text-gray-500">
                                    Buyer
                                </th>

                                <th class="px-5 py-4 text-left text-xs
                                               font-semibold uppercase
                                               tracking-wide text-gray-500">
                                    Bid Amount
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

                            <tr class="hover:bg-[#FAFCFC]">

                                <td class="px-5 py-5">

                                    <p class="max-w-[250px] truncate
                                                      text-sm font-semibold
                                                      text-gray-900">

                                        {{ $bid->listing?->title ?? 'Listing unavailable' }}

                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">

                                        {{ $bid->created_at->format('d M Y') }}

                                    </p>

                                </td>


                                <td class="px-5 py-5">

                                    <p class="text-sm font-semibold text-gray-800">

                                        {{ $bid->user?->name ?? 'Buyer' }}

                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">

                                        {{ $bid->user?->email ?? '' }}

                                    </p>

                                </td>


                                <td class="px-5 py-5">

                                    <span class="text-sm font-bold text-[#056663]">

                                        ₹{{ number_format($bid->bid_amount, 2) }}

                                    </span>

                                </td>


                                <td class="px-5 py-5">

                                    @if($bid->status === 'pending')

                                    <span class="rounded-full bg-amber-50
                                                             px-3 py-1.5 text-xs
                                                             font-semibold text-amber-700">
                                        Pending
                                    </span>

                                    @elseif($bid->status === 'accepted')

                                    <span class="rounded-full bg-green-50
                                                             px-3 py-1.5 text-xs
                                                             font-semibold text-green-700">
                                        Accepted
                                    </span>

                                    @elseif($bid->status === 'rejected')

                                    <span class="rounded-full bg-red-50
                                                             px-3 py-1.5 text-xs
                                                             font-semibold text-red-600">
                                        Rejected
                                    </span>

                                    @else

                                    <span class="rounded-full bg-gray-100
                                                             px-3 py-1.5 text-xs
                                                             font-semibold text-gray-600">
                                        {{ ucfirst($bid->status) }}
                                    </span>

                                    @endif

                                </td>


                                <td class="px-5 py-5 text-right">

                                    <a
                                        href="{{ route('seller.bids.show', $bid->id) }}"
                                        class="inline-flex items-center gap-2
           rounded-lg border border-[#D7E5E3]
           bg-white px-4 py-2
           text-sm font-medium text-[#056663]
           transition
           hover:bg-[#EAF5F3]">

                                        <span>View</span>

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />

                                        </svg>

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

                    <span class="material-symbols-outlined text-4xl text-[#056663]">
                        gavel
                    </span>

                </div>

                <h3 class="mt-6 text-xl font-bold text-gray-900">
                    No Bids Received
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    Buyer bids for your listings will appear here.
                </p>

            </div>

            @endif

        </div>

    </div>

</x-seller-layout>