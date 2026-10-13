
<x-buyer-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                My Orders
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Track and manage your product, service, project, property, and land orders.
            </p>
        </div>
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mx-6 mt-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="mx-6 mt-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Info Message --}}
    @if(session('info'))
        <div class="mx-6 mt-6 rounded-xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm text-blue-700">
            {{ session('info') }}
        </div>
    @endif

    <div class="m-6 overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        {{-- Header --}}
        <div class="border-b border-[#DDE9E8] px-6 py-5">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Order History
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        View your orders, payment status, and transaction progress.
                    </p>
                </div>

                <div class="rounded-xl bg-[#E8F5F4] px-4 py-2 text-sm font-medium text-[#056663]">
                    {{ $orders->total() }} Orders
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#DDE9E8]">

                <thead class="bg-[#F7FAFA]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Order
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Seller
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Category
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Amount
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Order Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Payment
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#DDE9E8] bg-white">

                    @forelse($orders as $order)

                        @php
                            /*
                            |--------------------------------------------------------------------------
                            | CATEGORY
                            |--------------------------------------------------------------------------
                            */
                            $category = strtolower(trim((string) ($order->category ?? '')));

                            $categoryKey = match ($category) {
                                'product', 'products' => 'products',
                                'service', 'services' => 'services',
                                'project', 'projects' => 'projects',
                                'property', 'properties' => 'properties',
                                'land' => 'land',
                                default => 'other',
                            };

                            $categoryLabel = match ($categoryKey) {
                                'products' => 'Products',
                                'services' => 'Services',
                                'projects' => 'Projects',
                                'properties' => 'Properties',
                                'land' => 'Land',
                                default => 'Other',
                            };

                            /*
                            |--------------------------------------------------------------------------
                            | DISPLAY ORDER NUMBER
                            |--------------------------------------------------------------------------
                            | Property/Land: stable display-only ORD number.
                            | Other categories: use the saved order_number.
                            */
                            $orderDate = $order->ordered_at
                                ? \Carbon\Carbon::parse($order->ordered_at)->format('Ymd')
                                : ($order->created_at
                                    ? $order->created_at->format('Ymd')
                                    : now()->format('Ymd'));

                            $isPropertyTransaction = in_array(
                                $categoryKey,
                                ['properties', 'land'],
                                true
                            );

                            $displayOrderNumber = $isPropertyTransaction
                                ? 'ORD-' . $orderDate . '-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT)
                                : ($order->order_number ?? 'Order number not assigned');

                            /*
                            |--------------------------------------------------------------------------
                            | ORDER STATUS
                            |--------------------------------------------------------------------------
                            */
                            $status = strtolower(trim((string) ($order->order_status ?? 'pending')));

                            $statusLabel = match ($status) {
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'processing' => 'Processing',
                                'negotiation' => 'Negotiation',
                                'documentation' => 'Documentation',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'rejected' => 'Rejected',
                                default => ucfirst(str_replace('_', ' ', $status)),
                            };

                            $statusClasses = [
                                'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'negotiation' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'documentation' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'shipped' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                'delivered' => 'bg-green-50 text-green-700 border-green-200',
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                            ];

                            $statusClass = $statusClasses[$status]
                                ?? 'bg-gray-50 text-gray-700 border-gray-200';

                            /*
                            |--------------------------------------------------------------------------
                            | PAYMENT STATUS
                            |--------------------------------------------------------------------------
                            */
                            $payment = $order->payments
                                ->sortByDesc('id')
                                ->first();

                            $paymentStatus = strtolower((string) (
                                $order->payment_status
                                ?? $payment?->status
                                ?? 'pending'
                            ));

                            if ($payment?->status === 'paid' || $order->payment_status === 'paid') {
                                $paymentStatus = 'paid';
                            }

                            $paymentLabel = match ($paymentStatus) {
                                'paid' => 'Paid',
                                'pending' => 'Pending',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                                default => ucfirst(str_replace('_', ' ', $paymentStatus)),
                            };

                            $paymentClass = match ($paymentStatus) {
                                'paid' => 'bg-green-50 text-green-700 border-green-200',
                                'failed' => 'bg-red-50 text-red-700 border-red-200',
                                'refunded' => 'bg-purple-50 text-purple-700 border-purple-200',
                                default => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            };

                            /*
                            |--------------------------------------------------------------------------
                            | SELLER
                            |--------------------------------------------------------------------------
                            */
                            $sellerName = $order->seller?->business_name
                                ?? $order->seller?->bussiness_name
                                ?? $order->seller?->name
                                ?? 'Seller';
                        @endphp

                        @if($categoryKey !== 'other')
                            <tr class="transition hover:bg-[#F7FAFA]">

                                {{-- Order Number --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <div class="font-semibold text-gray-800">
                                        {{ $displayOrderNumber }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ optional($order->ordered_at ?? $order->created_at)->format('d M Y, h:i A') }}
                                    </div>
                                </td>

                                {{-- Seller --}}
                                <td class="px-6 py-5">
                                    <div class="text-sm font-medium text-gray-800">
                                        {{ $sellerName }}
                                    </div>
                                </td>

                                {{-- Category --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="inline-flex items-center rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                                        {{ $categoryLabel }}
                                    </span>
                                </td>

                                {{-- Amount --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <div class="text-sm font-semibold text-gray-800">
                                        ₹{{ number_format((float) ($order->total_amount ?? 0), 2) }}
                                    </div>
                                </td>

                                {{-- Order Status --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Payment Status --}}
                                <td class="whitespace-nowrap px-6 py-5">
                                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $paymentClass }}">
                                        {{ $paymentLabel }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td class="whitespace-nowrap px-6 py-5 text-right">
                                    <a
                                        href="{{ route('buyer.orders.show', $order->id) }}"
                                        class="inline-flex items-center gap-2 rounded-lg bg-[#056663] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#04504E]">
                                        <span>View Order</span>
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
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>

                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#E8F5F4]">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-8 w-8 text-[#056663]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m0 0L4 7m8 4v10" />
                                    </svg>
                                </div>

                                <h3 class="mt-5 text-lg font-semibold text-gray-800">
                                    No Orders Found
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    You don't have any orders yet.
                                </p>
                                <a
                                    href="{{ url('/') }}"
                                    class="mt-5 inline-flex items-center rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04504E]">
                                    Continue Shopping
                                </a>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="border-t border-[#DDE9E8] px-6 py-4">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

</x-buyer-layout>
