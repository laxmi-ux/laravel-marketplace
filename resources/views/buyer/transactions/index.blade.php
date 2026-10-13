<x-buyer-layout>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    My Transactions
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Track and manage your property and land transactions.
                </p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
        @endif

        {{-- Transactions --}}
        <div class="bg-white border border-[#DDE9E8] rounded-xl overflow-hidden shadow-sm">

            @if($transactions->count())

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-[#F7FAFA]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                Transaction
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                Seller
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

                    <tbody class="divide-y divide-gray-100">

                        @foreach($transactions as $transaction)

                        @php
                        $category = strtolower(trim((string) $transaction->category));

                        $categoryKey = match ($category) {
                        'property', 'properties' => 'properties',
                        'land' => 'land',
                        default => $category,
                        };

                        $categoryLabel = match ($categoryKey) {
                        'properties' => 'Property',
                        'land' => 'Land',
                        default => ucfirst($categoryKey),
                        };

                        $statusLabel = match ($transaction->order_status) {
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'negotiation' => 'Negotiation',
                        'documentation' => 'Documentation',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'rejected' => 'Rejected',
                        default => ucfirst(str_replace('_', ' ', (string) $transaction->order_status)),
                        };

                        $statusClass = match ($transaction->order_status) {
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'negotiation' => 'bg-purple-100 text-purple-700',
                        'documentation' => 'bg-indigo-100 text-indigo-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled', 'rejected' => 'bg-red-100 text-red-700',
                        default => 'bg-gray-100 text-gray-700',
                        };

                        $sellerName =
                        $transaction->seller?->company_name
                        ?? $transaction->seller?->business_name
                        ?? $transaction->seller?->bussiness_name
                        ?? $transaction->seller?->name
                        ?? 'Seller';

                        $itemName = $categoryKey === 'properties'
                        ? ($transaction->property_type
                        ?? $transaction->listing?->title
                        ?? 'Property')
                        : ($transaction->listing?->title
                        ?? $transaction->listing?->name
                        ?? 'Land');

                        /*
                        * Transaction number:
                        * Use the existing database field.
                        * Do not query a transaction_number column.
                        */
                        $transactionNumber = $transaction->order_number
                        ?? ('TRN-' . str_pad((string) $transaction->id, 6, '0', STR_PAD_LEFT));
                        @endphp

                        <tr class="hover:bg-[#F7FAFA]">


                            {{-- Transaction Number --}}
                            <td class="px-6 py-5 whitespace-nowrap">

                                @php
                                $storedNumber = trim((string) $transaction->order_number);

                                $transactionNumber = str_starts_with(
                                strtoupper($storedNumber),
                                'TRN-'
                                )
                                ? $storedNumber
                                : 'TRN-' . str_pad(
                                (string) $transaction->id,
                                6,
                                '0',
                                STR_PAD_LEFT
                                );
                                @endphp

                                <div class="font-semibold text-gray-900">
                                    {{ $transactionNumber }}
                                </div>

                                <div class="text-sm text-gray-500 mt-1">
                                    {{ $itemName }}
                                </div>

                                <div class="text-xs text-gray-400 mt-1">
                                    {{ optional($transaction->ordered_at ?? $transaction->created_at)->format('d M Y') }}
                                </div>

                            </td>


                            {{-- Seller --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $sellerName }}
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                            {{ $categoryKey === 'properties'
                                                ? 'bg-teal-100 text-teal-700'
                                                : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $categoryLabel }}
                                </span>
                            </td>

                            {{-- Amount --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">
                                    ₹{{ number_format((float) $transaction->total_amount, 2) }}
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                <a
                                    href="{{ route('buyer.transactions.show', $transaction->id) }}"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-[#056663] text-white text-sm font-semibold hover:bg-[#045450] transition">
                                    View
                                </a>
                            </td>

                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
            @endif

            @else

            {{-- Empty State --}}
            <div class="px-6 py-16 text-center">

                <div class="mx-auto w-14 h-14 rounded-full bg-[#EAF5F4] flex items-center justify-center">
                    <svg
                        class="w-7 h-7 text-[#056663]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M3 10h18M5 10v8a2 2 0 002 2h10a2 2 0 002-2v-8M7 10V6a5 5 0 0110 0v4" />
                    </svg>
                </div>

                <h3 class="mt-4 text-lg font-semibold text-gray-900">
                    No Transactions Found
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    You don't have any property or land transactions yet.
                </p>

            </div>

            @endif

        </div>

    </div>

</x-buyer-layout>