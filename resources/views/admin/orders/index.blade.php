@extends('admin.layouts.app')

@section('title', 'Order Management')

@section('page-title', 'Order Management')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | CATEGORY HELPER
    |--------------------------------------------------------------------------
    */

    $normalizeCategory = function ($category) {

        return match (strtolower(trim((string) $category))) {

            'product',
            'products' => 'products',

            'service',
            'services' => 'services',

            'project',
            'projects' => 'projects',

            default => strtolower(trim((string) $category)),
        };
    };


    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    $statusClasses = function ($status) {

        return match (strtolower(trim((string) $status))) {

            'pending' =>
                'bg-yellow-100 text-yellow-700',

            'confirmed' =>
                'bg-cyan-100 text-cyan-700',

            'processing' =>
                'bg-indigo-100 text-indigo-700',

            'shipped' =>
                'bg-blue-100 text-blue-700',

            'delivered' =>
                'bg-emerald-100 text-emerald-700',

            'completed' =>
                'bg-green-100 text-green-700',

            'cancelled',
            'rejected' =>
                'bg-red-100 text-red-700',

            default =>
                'bg-gray-100 text-gray-700',
        };
    };


    /*
    |--------------------------------------------------------------------------
    | CATEGORY BADGE
    |--------------------------------------------------------------------------
    */

    $categoryClasses = function ($category) {

        return match ($category) {

            'products' =>
                'bg-blue-50 text-blue-700',

            'services' =>
                'bg-purple-50 text-purple-700',

            'projects' =>
                'bg-orange-50 text-orange-700',

            default =>
                'bg-gray-100 text-gray-700',
        };
    };
@endphp


<div class="space-y-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Orders
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Monitor and manage marketplace product, service, and project orders.
            </p>

        </div>


        {{-- Total Orders --}}

        <div class="rounded-xl border border-gray-200 bg-white px-5 py-4 shadow-sm">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Total Orders
            </p>

            <p class="mt-1 text-2xl font-bold text-[#056663]">
                {{ $orders->total() }}
            </p>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FLASH MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-8 w-8 items-center justify-center
                           rounded-full bg-green-100 text-green-700"
                >
                    ✓
                </div>

                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>

            </div>

        </div>

    @endif


    @if(session('error'))

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-8 w-8 items-center justify-center
                           rounded-full bg-red-100 text-red-700"
                >
                    !
                </div>

                <p class="text-sm font-medium text-red-800">
                    {{ session('error') }}
                </p>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FILTERS --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.orders.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5"
        >

            {{-- Search --}}

            <div class="lg:col-span-2">

                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Order number, buyer, seller..."
                    class="w-full rounded-lg border-gray-300 text-sm
                           focus:border-[#056663]
                           focus:ring-[#056663]"
                >

            </div>


            {{-- Order Status --}}

            <div>

                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Order Status
                </label>

                <select
                    name="order_status"
                    class="w-full rounded-lg border-gray-300 text-sm
                           focus:border-[#056663]
                           focus:ring-[#056663]"
                >

                    <option value="">
                        All Statuses
                    </option>

                    @foreach([
                        'pending',
                        'confirmed',
                        'processing',
                        'shipped',
                        'delivered',
                        'completed',
                        'cancelled',
                        'rejected'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(request('order_status') === $status)
                        >
                            {{ ucfirst($status) }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Payment Status --}}

            <div>

                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Payment
                </label>

                <select
                    name="payment_status"
                    class="w-full rounded-lg border-gray-300 text-sm
                           focus:border-[#056663]
                           focus:ring-[#056663]"
                >

                    <option value="">
                        All Payments
                    </option>

                    @foreach([
                        'pending',
                        'paid',
                        'failed',
                        'refunded'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(request('payment_status') === $status)
                        >
                            {{ ucfirst($status) }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Category --}}

            <div>

                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700"
                >
                    Category
                </label>

                <select
                    name="category"
                    class="w-full rounded-lg border-gray-300 text-sm
                           focus:border-[#056663]
                           focus:ring-[#056663]"
                >

                    <option value="">
                        All Categories
                    </option>

                    <option
                        value="products"
                        @selected(request('category') === 'products')
                    >
                        Products
                    </option>

                    <option
                        value="services"
                        @selected(request('category') === 'services')
                    >
                        Services
                    </option>

                    <option
                        value="projects"
                        @selected(request('category') === 'projects')
                    >
                        Projects
                    </option>

                </select>

            </div>


            {{-- Buttons --}}

            <div class="flex items-end gap-2 lg:col-span-5">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center
                           rounded-lg bg-[#056663] px-5 py-2.5
                           text-sm font-semibold text-white
                           transition hover:bg-[#045653]"
                >
                    Apply Filters
                </button>


                <a
                    href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center justify-center
                           rounded-lg border border-gray-300
                           bg-white px-5 py-2.5
                           text-sm font-semibold text-gray-700
                           transition hover:bg-gray-50"
                >
                    Clear
                </a>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- ORDER TABLE --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th
                            class="px-5 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500"
                        >
                            Order
                        </th>


                        <th
                            class="px-5 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500"
                        >
                            Buyer
                        </th>


                        <th
                            class="px-5 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500"
                        >
                            Seller
                        </th>


                        <th
                            class="px-5 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500"
                        >
                            Category
                        </th>


                        <th
                            class="px-5 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500"
                        >
                            Amount
                        </th>


                        <th
                            class="px-5 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500"
                        >
                            Payment
                        </th>


                        <th
                            class="px-5 py-4 text-left text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500"
                        >
                            Status
                        </th>


                        <th
                            class="px-5 py-4 text-right text-xs
                                   font-semibold uppercase tracking-wide
                                   text-gray-500"
                        >
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($orders as $order)

                        @php

                            $categoryKey =
                                $normalizeCategory($order->category);

                            $categoryLabel = match ($categoryKey) {

                                'products' => 'Products',

                                'services' => 'Services',

                                'projects' => 'Projects',

                                default => ucfirst($categoryKey),
                            };


                            $currentStatus =
                                strtolower(
                                    trim(
                                        (string)
                                        ($order->order_status ?? 'pending')
                                    )
                                );


                            $paymentStatus =
                                strtolower(
                                    trim(
                                        (string)
                                        ($order->payment_status ?? 'pending')
                                    )
                                );

                        @endphp


                        <tr class="transition hover:bg-gray-50">


                            {{-- ================================================= --}}
                            {{-- ORDER --}}
                            {{-- ================================================= --}}

                            <td class="whitespace-nowrap px-5 py-4">

                                <div>

                                    <p class="font-semibold text-gray-900">
                                        {{ $order->order_number }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">

                                        @if($order->ordered_at)

                                            {{ optional($order->ordered_at)->format('d M Y') }}

                                        @else

                                            —

                                        @endif

                                    </p>

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- BUYER --}}
                            {{-- ================================================= --}}

                            <td class="px-5 py-4">

                                <div>

                                    <p class="font-medium text-gray-900">
                                        {{ $order->buyer?->name ?? '—' }}
                                    </p>

                                    @if($order->buyer?->email)

                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $order->buyer->email }}
                                        </p>

                                    @endif

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- SELLER --}}
                            {{-- ================================================= --}}

                            <td class="px-5 py-4">

                                <div>

                                    <p class="font-medium text-gray-900">

                                        {{ $order->seller?->company_name
                                            ?? $order->seller?->business_name
                                            ?? $order->seller?->bussiness_name
                                            ?? $order->seller?->name
                                            ?? 'Seller' }}

                                    </p>

                                </div>

                            </td>


                            {{-- ================================================= --}}
                            {{-- CATEGORY --}}
                            {{-- ================================================= --}}

                            <td class="whitespace-nowrap px-5 py-4">

                                <span
                                    class="inline-flex rounded-full
                                           px-3 py-1 text-xs
                                           font-semibold
                                           {{ $categoryClasses($categoryKey) }}"
                                >
                                    {{ $categoryLabel }}
                                </span>

                            </td>


                            {{-- ================================================= --}}
                            {{-- AMOUNT --}}
                            {{-- ================================================= --}}

                            <td class="whitespace-nowrap px-5 py-4">

                                <p class="font-semibold text-gray-900">

                                    ₹{{ number_format(
                                        $order->total_amount ?? 0,
                                        2
                                    ) }}

                                </p>

                            </td>


                            {{-- ================================================= --}}
                            {{-- PAYMENT --}}
                            {{-- ================================================= --}}

                            <td class="whitespace-nowrap px-5 py-4">

                                @php

                                    $paymentClasses = match ($paymentStatus) {

                                        'paid' =>
                                            'bg-green-100 text-green-700',

                                        'failed' =>
                                            'bg-red-100 text-red-700',

                                        'refunded' =>
                                            'bg-purple-100 text-purple-700',

                                        default =>
                                            'bg-yellow-100 text-yellow-700',
                                    };

                                @endphp


                                <span
                                    class="inline-flex rounded-full
                                           px-3 py-1 text-xs
                                           font-semibold
                                           {{ $paymentClasses }}"
                                >
                                    {{ ucfirst($paymentStatus) }}
                                </span>

                            </td>


                            {{-- ================================================= --}}
                            {{-- ORDER STATUS --}}
                            {{-- ================================================= --}}

                            <td class="whitespace-nowrap px-5 py-4">

                                <span
                                    class="inline-flex rounded-full
                                           px-3 py-1 text-xs
                                           font-semibold
                                           {{ $statusClasses($currentStatus) }}"
                                >

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $currentStatus
                                        )
                                    ) }}

                                </span>

                            </td>


                            {{-- ================================================= --}}
                            {{-- ACTION --}}
                            {{-- ================================================= --}}

                            <td class="whitespace-nowrap px-5 py-4 text-right">

                                <a
                                    href="{{ route(
                                        'admin.orders.show',
                                        $order->id
                                    ) }}"
                                    class="inline-flex items-center gap-1.5
                                           rounded-lg bg-[#056663]
                                           px-4 py-2 text-xs
                                           font-semibold text-white
                                           transition hover:bg-[#045653]"
                                >

                                    View Order

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-3.5 w-3.5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 5l7 7-7 7"
                                        />

                                    </svg>

                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-16 text-center"
                            >

                                <div
                                    class="mx-auto flex h-14 w-14
                                           items-center justify-center
                                           rounded-full bg-[#E8F5F4]
                                           text-[#056663]"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-7 w-7"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M20 13V7a2 2 0 00-2-2h-3.5L13 3H11L9.5 5H6a2 2 0 00-2 2v6"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4 13h16v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6z"
                                        />

                                    </svg>

                                </div>


                                <h3
                                    class="mt-4 text-base
                                           font-semibold text-gray-900"
                                >
                                    No Orders Found
                                </h3>


                                <p class="mt-1 text-sm text-gray-500">

                                    No product, service, or project orders
                                    match your current filters.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        @if($orders->hasPages())

            <div
                class="border-t border-gray-200
                       bg-white px-5 py-4"
            >

                {{ $orders->links() }}

            </div>

        @endif

    </div>

</div>

@endsection