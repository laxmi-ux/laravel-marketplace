
<x-seller-layout>

    {{-- PAGE HEADER --}}
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-[#123B38]">
                Customer Management
            </h2>
            <p class="text-sm text-[#4F6663]">
                Manage customers who have contacted you or placed orders.
            </p>
        </div>
    </x-slot>

    <div class="bg-[#F7FAF9] px-4 py-6 sm:px-5 sm:py-8 lg:px-6">
        <div class="mx-auto w-full max-w-7xl">

            {{-- PAGE TITLE --}}
            <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-[#163B37]">
                        Customers
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        View customer contact details, orders, and enquiries.
                    </p>
                </div>

                <a href="{{ route('seller.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-[#D3E3E0] bg-white px-4 py-2.5 text-sm font-medium text-[#00665F] transition hover:bg-[#EAF5F3]">
                    ← Back to Dashboard
                </a>
            </div>

            {{-- FLASH MESSAGES --}}
            @if(session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- NORMALIZE CUSTOMER DATA --}}
            @php
                $customers = collect($customers ?? [])->map(function ($customer) {
                    $get = function ($key, $default = null) use ($customer) {
                        return data_get($customer, $key, $default);
                    };

                    return [
                        'name' => $get('name', 'Customer'),
                        'email' => $get('email', 'N/A'),
                        'phone' => $get('phone', 'N/A'),
                        'last_activity' => $get('last_activity'),
                        'orders' => collect($get('orders', [])),
                        'enquiries' => collect($get('enquiries', [])),
                    ];
                });
            @endphp

            {{-- SUMMARY CARDS --}}
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                <div class="rounded-2xl border border-[#DCE9E6] bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Total Customers
                            </p>
                            <p class="mt-2 text-3xl font-bold text-[#163B37]">
                                {{ $customers->count() }}
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#EAF5F3] text-2xl">
                            👥
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-[#DCE9E6] bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Customers with Orders
                            </p>
                            <p class="mt-2 text-3xl font-bold text-[#163B37]">
                                {{ $customers->filter(fn($customer) => $customer['orders']->isNotEmpty())->count() }}
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl">
                            🛍️
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-[#DCE9E6] bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Customers with Enquiries
                            </p>
                            <p class="mt-2 text-3xl font-bold text-[#163B37]">
                                {{ $customers->filter(fn($customer) => $customer['enquiries']->isNotEmpty())->count() }}
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-2xl">
                            📩
                        </div>
                    </div>
                </div>

            </div>

            {{-- SEARCH --}}
            <div class="mb-6 rounded-2xl border border-[#DCE9E6] bg-white p-5 shadow-sm">
                <form method="GET"
                      action="{{ route('seller.customers.index') }}"
                      class="flex flex-col gap-3 sm:flex-row">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? request('search') }}"
                        placeholder="Search by customer name, email, or phone..."
                        class="w-full flex-1 rounded-lg border border-[#D3E3E0] bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/10"
                    >

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#00665F] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#00544F]">
                        Search Customers
                    </button>

                    @if(request('search'))
                        <a href="{{ route('seller.customers.index') }}"
                           class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- CUSTOMER LIST --}}
            @forelse($customers as $customer)

                @php
                    $orders = $customer['orders'];
                    $enquiries = $customer['enquiries'];

                    $name = $customer['name'] ?: 'Customer';
                    $email = $customer['email'] ?: 'N/A';
                    $phone = $customer['phone'] ?: 'N/A';
                    $lastActivity = $customer['last_activity'];
                @endphp

                <div class="mb-5 overflow-hidden rounded-2xl border border-[#DCE9E6] bg-white shadow-sm">

                    {{-- CUSTOMER HEADER --}}
                    <div class="border-b border-[#E5EEEC] bg-[#F9FCFB] px-5 py-4 sm:px-6">
                        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#EAF5F3] text-lg font-bold text-[#00665F]">
                                    {{ strtoupper(substr($name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-lg font-semibold text-[#163B37]">
                                        {{ $name }}
                                    </h2>
                                    <p class="mt-1 break-all text-sm text-gray-500">
                                        {{ $email }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                    {{ $orders->count() }} {{ \Illuminate\Support\Str::plural('Order', $orders->count()) }}
                                </span>

                                <span class="rounded-full border border-purple-200 bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">
                                    {{ $enquiries->count() }} {{ \Illuminate\Support\Str::plural('Enquiry', $enquiries->count()) }}
                                </span>
                            </div>

                        </div>
                    </div>

                    {{-- CUSTOMER DETAILS --}}
                    <div class="px-5 py-6 sm:px-6">

                        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                            {{-- CUSTOMER INFORMATION --}}
                            <div class="rounded-xl border border-[#E1ECE9] p-5">
                                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-[#00665F]">
                                    Customer Information
                                </h3>

                                <div class="space-y-4 text-sm">

                                    <div class="flex justify-between gap-4">
                                        <span class="text-gray-500">Name</span>
                                        <span class="text-right font-medium text-gray-800">
                                            {{ $name }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between gap-4">
                                        <span class="text-gray-500">Email</span>
                                        <span class="break-all text-right font-medium text-gray-800">
                                            {{ $email }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between gap-4">
                                        <span class="text-gray-500">Phone</span>
                                        <span class="text-right font-medium text-gray-800">
                                            {{ $phone }}
                                        </span>
                                    </div>

                                    <div class="flex justify-between gap-4">
                                        <span class="text-gray-500">Last Activity</span>
                                        <span class="text-right font-medium text-gray-800">
                                            @if($lastActivity)
                                                {{ \Illuminate\Support\Carbon::parse($lastActivity)->format('d M Y, h:i A') }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </div>

                                </div>
                            </div>

                            {{-- ACTIVITY SUMMARY --}}
                            <div class="rounded-xl border border-[#E1ECE9] p-5">
                                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wide text-[#00665F]">
                                    Customer Activity
                                </h3>

                                <div class="space-y-4">

                                    <div class="flex items-center justify-between rounded-lg bg-[#F7FAF9] p-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                Orders
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Orders placed by this customer
                                            </p>
                                        </div>
                                        <span class="text-xl font-bold text-[#00665F]">
                                            {{ $orders->count() }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between rounded-lg bg-[#F7FAF9] p-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                Enquiries
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                Enquiries received from this customer
                                            </p>
                                        </div>
                                        <span class="text-xl font-bold text-[#00665F]">
                                            {{ $enquiries->count() }}
                                        </span>
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- RECENT ORDERS --}}
                        @if($orders->isNotEmpty())
                            <div class="mt-5 overflow-hidden rounded-xl border border-[#E1ECE9]">
                                <div class="border-b border-[#E1ECE9] bg-[#F9FCFB] px-5 py-3">
                                    <h3 class="text-sm font-semibold text-[#00665F]">
                                        Recent Orders
                                    </h3>
                                </div>

                                <div class="divide-y divide-[#E5EEEC]">
                                    @foreach($orders->take(3) as $order)
                                        @php
                                            $orderListing = data_get($order, 'listing');
                                            $orderDate = data_get($order, 'created_at');
                                            $orderAmount = data_get($order, 'total_amount')
                                                ?? data_get($order, 'amount')
                                                ?? data_get($order, 'price');
                                        @endphp

                                        <div class="flex flex-col justify-between gap-2 px-5 py-4 sm:flex-row sm:items-center">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">
                                                    Order #{{ data_get($order, 'order_number') ?? data_get($order, 'id', 'N/A') }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ data_get($orderListing, 'title', 'Listing unavailable') }}
                                                </p>
                                            </div>

                                            <div class="text-left sm:text-right">
                                                <p class="text-sm font-medium text-gray-800">
                                                    {{ $orderAmount !== null ? number_format((float) $orderAmount, 2) : 'Amount not available' }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    {{ $orderDate ? \Illuminate\Support\Carbon::parse($orderDate)->format('d M Y') : 'Date unavailable' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- RECENT ENQUIRIES --}}
                        @if($enquiries->isNotEmpty())
                            <div class="mt-5 overflow-hidden rounded-xl border border-[#E1ECE9]">
                                <div class="border-b border-[#E1ECE9] bg-[#F9FCFB] px-5 py-3">
                                    <h3 class="text-sm font-semibold text-[#00665F]">
                                        Recent Enquiries
                                    </h3>
                                </div>

                                <div class="divide-y divide-[#E5EEEC]">
                                    @foreach($enquiries->take(3) as $enquiry)
                                        @php
                                            $enquiryListing = data_get($enquiry, 'listing');
                                            $enquiryDate = data_get($enquiry, 'created_at');
                                            $enquiryStatus = data_get($enquiry, 'status', 'new');
                                        @endphp

                                        <div class="flex flex-col justify-between gap-2 px-5 py-4 sm:flex-row sm:items-center">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">
                                                    {{ data_get($enquiryListing, 'title', 'Listing Enquiry') }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500">
                                                    Enquiry #{{ data_get($enquiry, 'id', 'N/A') }}
                                                </p>
                                            </div>

                                            <div class="text-left sm:text-right">
                                                <span class="inline-flex rounded-full border border-[#D3E3E0] bg-[#EAF5F3] px-3 py-1 text-xs font-semibold text-[#00665F]">
                                                    {{ ucfirst(str_replace('_', ' ', $enquiryStatus)) }}
                                                </span>

                                                <p class="mt-2 text-xs text-gray-500">
                                                    {{ $enquiryDate ? \Illuminate\Support\Carbon::parse($enquiryDate)->format('d M Y') : 'Date unavailable' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            @empty

                {{-- EMPTY STATE --}}
                <div class="rounded-2xl border border-[#DCE9E6] bg-white px-6 py-16 text-center shadow-sm">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#EAF5F3] text-2xl">
                        👥
                    </div>

                    <h2 class="mt-5 text-lg font-semibold text-[#163B37]">
                        No customers found
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                        Customers will appear here when they send enquiries or place orders for your listings.
                    </p>

                    @if(request('search'))
                        <a href="{{ route('seller.customers.index') }}"
                           class="mt-6 inline-flex items-center rounded-lg bg-[#00665F] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00544F]">
                            View All Customers
                        </a>
                    @else
                        <a href="{{ route('seller.listings.index') }}"
                           class="mt-6 inline-flex items-center rounded-lg bg-[#00665F] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#00544F]">
                            View My Listings
                        </a>
                    @endif
                </div>

            @endforelse

        </div>
    </div>

</x-seller-layout>
