<x-seller-layout>

    <div class="p-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

            <div>
                <h1 class="text-2xl font-bold text-[#056663]">
                    Orders Management
                </h1>

                <p class="text-gray-600 mt-1">
                    Manage your Orders.
                </p>
            </div>

            <div class="bg-[#E8F5F4] text-[#056663] px-4 py-2 rounded-lg text-sm font-medium">
                {{ $orders->total() }} Orders
            </div>

        </div>




        {{-- Success --}}
        @if(session('success'))

        <div class="mx-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3  text-sm text-green-700">
            {{ session('success') }}
        </div>

        @endif


        {{-- Error --}}
        @if(session('error'))

        <div class="mx-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>

        @endif


        <div class="bg-white border border-[#DDE9E8] rounded-xl shadow-sm overflow-hidden">


            @if($orders->count())
            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-[#F7FAFA]">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Order
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Buyer
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Category
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Amount
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-gray-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($orders as $order)

                        @php

                        $categoryKey = strtolower(
                        trim((string) ($order->category ?? ''))
                        );

                        $category = match ($categoryKey) {

                        'product',
                        'products' => 'Products',

                        'service',
                        'services' => 'Services',

                        'project',
                        'projects' => 'Projects',

                        default => ucfirst($categoryKey),

                        };

                        $statusKey = strtolower(
                        trim((string) ($order->order_status ?? ''))
                        );

                        $statusLabel = match ($statusKey) {

                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                        'cancelled',
                        'canceled' => 'Cancelled',

                        default => ucfirst(
                        str_replace('_', ' ', $statusKey)
                        ),

                        };

                        $statusClass = match ($statusKey) {

                        'pending'
                        => 'bg-yellow-100 text-yellow-700',

                        'confirmed'
                        => 'bg-blue-100 text-blue-700',

                        'processing'
                        => 'bg-indigo-100 text-indigo-700',

                        'shipped'
                        => 'bg-purple-100 text-purple-700',

                        'delivered'
                        => 'bg-green-100 text-green-700',

                        'completed'
                        => 'bg-emerald-100 text-emerald-700',

                        'rejected',
                        'cancelled',
                        'canceled'
                        => 'bg-red-100 text-red-700',

                        default
                        => 'bg-gray-100 text-gray-700',

                        };

                        @endphp


                        <tr class="hover:bg-gray-50">

                            {{-- Order --}}
                            <td class="px-6 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $order->order_number }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ optional($order->ordered_at)->format('d M Y') }}
                                </p>

                            </td>


                            {{-- Buyer --}}
                            <td class="px-6 py-4">

                                <p class="text-sm font-medium text-gray-900">
                                    {{ $order->buyer?->name ?? 'Buyer' }}
                                </p>

                                @if($order->buyer?->email)

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $order->buyer->email }}
                                </p>

                                @endif

                            </td>


                            {{-- Category --}}
                            <td class="px-6 py-4">

                                <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                                    {{ $category }}
                                </span>

                            </td>


                            {{-- Amount --}}
                            <td class="px-6 py-4">

                                <span class="font-semibold text-gray-900">
                                    ₹{{ number_format($order->total_amount ?? 0, 2) }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>

                            </td>


                            {{-- Action --}}
                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('seller.orders.show', $order->id) }}"
                                    class="font-semibold text-[#056663] hover:underline">
                                    View Order
                                </a>

                            </td>

                        </tr>


                        @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-12 text-center text-sm text-gray-500">
                                No orders received yet.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>




            {{-- Pagination --}}
            <div class="mx-5">
                {{ $orders->links() }}
            </div>

            @else

            {{-- Empty State --}}
            <div class="text-center py-16 px-6">

                <div class="mx-auto w-16 h-16 rounded-full bg-[#E8F5F4] flex items-center justify-center mb-4">

                    <svg
                        class="w-8 h-8 text-[#056663]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V5m0 9v5m-7-6h3m8 0h3" />
                    </svg>

                </div>

                <h3 class="text-lg font-semibold text-gray-900">
                    No Order Found
                </h3>

                <p class="text-gray-500 mt-2">
                    Produts and Services orders will appear here.
                </p>

            </div>

            @endif
        </div>
    </div>

</x-seller-layout>