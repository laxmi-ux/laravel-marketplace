<x-seller-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

            <div>
                <h1 class="text-2xl font-bold text-[#056663]">
                    Transaction Management
                </h1>

                <p class="text-gray-600 mt-1">
                    Manage your Property and Land transactions.
                </p>
            </div>

            <div class="bg-[#E8F5F4] text-[#056663] px-4 py-2 rounded-lg text-sm font-medium">
                {{ $transactions->total() }} Transactions
            </div>

        </div>


        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-5 rounded-lg bg-green-50 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error Message --}}
        @if(session('error'))
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif


        {{-- Transactions --}}
        <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm overflow-hidden">

            @if($transactions->count())

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-[#F7FAFA] border-b border-[#DDE9E8]">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Transaction
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Buyer
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Type
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Amount
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-[#DDE9E8]">

                            @foreach($transactions as $transaction)

                                @php

                                    $category = strtolower(
                                        trim((string) $transaction->category)
                                    );

                                    $isProperty = in_array(
                                        $category,
                                        ['property', 'properties'],
                                        true
                                    );

                                    $status = strtolower(
                                        trim((string) $transaction->order_status)
                                    );

                                    $statusClasses = match($status) {

                                        'pending' =>
                                            'bg-yellow-50 text-yellow-700 border-yellow-200',

                                        'confirmed' =>
                                            'bg-blue-50 text-blue-700 border-blue-200',

                                        'negotiation' =>
                                            'bg-purple-50 text-purple-700 border-purple-200',

                                        'documentation' =>
                                            'bg-orange-50 text-orange-700 border-orange-200',

                                        'completed' =>
                                            'bg-green-50 text-green-700 border-green-200',

                                        'rejected', 'cancelled' =>
                                            'bg-red-50 text-red-700 border-red-200',

                                        default =>
                                            'bg-gray-50 text-gray-700 border-gray-200',
                                    };

                                    $statusLabel = match($status) {

                                        'pending' => 'Pending',

                                        'confirmed' => 'Confirmed',

                                        'negotiation' => 'Negotiation',

                                        'documentation' => 'Documentation',

                                        'completed' => 'Completed',

                                        'rejected' => 'Rejected',

                                        'cancelled' => 'Cancelled',

                                        default => ucfirst($status),
                                    };

                                @endphp


                                <tr class="hover:bg-[#F7FAFA] transition">


                                    {{-- Transaction --}}
                                    <td class="px-6 py-5">

                                        <div class="font-semibold text-gray-900">
                                            {{ $transaction->order_number }}
                                        </div>

                                        <div class="text-xs text-gray-500 mt-1">

                                            @if($transaction->ordered_at)
                                                {{ $transaction->ordered_at->format('d M Y, h:i A') }}
                                            @else
                                                -
                                            @endif

                                        </div>

                                    </td>


                                    {{-- Buyer --}}
                                    <td class="px-6 py-5">

                                        <div class="font-medium text-gray-900">

                                            {{ $transaction->buyer->name
                                                ?? 'Buyer' }}

                                        </div>

                                        @if($transaction->buyer?->email)

                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $transaction->buyer->email }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Type --}}
                                    <td class="px-6 py-5">

                                        @if($isProperty)

                                            <span class="text-gray-800">
                                                Property
                                            </span>

                                        @else

                                            <span class="text-gray-800">
                                                Land
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Amount --}}
                                    <td class="px-6 py-5">

                                        <span class="font-semibold text-gray-900">
                                            ₹{{ number_format((float) $transaction->total_amount, 2) }}
                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td class="px-6 py-5">

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                                            {{ $statusLabel }}
                                        </span>

                                    </td>


                                    {{-- Action --}}
                                    <td class="px-6 py-5 text-right">

                                        <a
                                            href="{{ route('seller.transactions.show', $transaction->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-[#056663] text-white text-sm font-medium rounded-lg hover:bg-[#045450] transition"
                                        >
                                            View Details
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-[#DDE9E8]">
                    {{ $transactions->links() }}
                </div>


            @else

                {{-- Empty State --}}
                <div class="text-center py-16 px-6">

                    <div class="mx-auto w-16 h-16 rounded-full bg-[#E8F5F4] flex items-center justify-center mb-4">

                        <svg
                            class="w-8 h-8 text-[#056663]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V5m0 9v5m-7-6h3m8 0h3"
                            />
                        </svg>

                    </div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        No Transactions Found
                    </h3>

                    <p class="text-gray-500 mt-2">
                        Property and Land transactions will appear here.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-seller-layout>