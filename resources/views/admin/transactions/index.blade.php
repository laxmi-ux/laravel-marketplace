@extends('admin.layouts.app')

@section('title', 'Transaction Management')

@section('page_title', 'Transaction Management')

@section('content')

<div class="min-h-screen bg-[#F7FAFA] p-6">

    {{-- =========================================================
         Header
    ========================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-[#056663]">
                Transaction Management
            </h1>

            <p class="text-gray-500 mt-1">
                Manage Property and Land transactions
            </p>
        </div>

    </div>


    {{-- =========================================================
         Flash Messages
    ========================================================== --}}

    @if(session('success'))
        <div class="mb-5 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- =========================================================
         Filters
    ========================================================== --}}

    <div class="bg-white rounded-xl border border-[#DDE9E8] shadow-sm mb-6">

        <form
            method="GET"
            action="{{ route('admin.transactions.index') }}"
            class="p-5"
        >

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Search --}}
                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Transaction number, buyer or seller..."
                        class="w-full border border-[#DDE9E8] rounded-lg px-3 py-2 focus:ring-[#056663] focus:border-[#056663]"
                    >

                </div>


                {{-- Category --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Category
                    </label>

                    <select
                        name="category"
                        class="w-full border border-[#DDE9E8] rounded-lg px-3 py-2 focus:ring-[#056663] focus:border-[#056663]"
                    >

                        <option value="">
                            All Categories
                        </option>

                        <option
                            value="properties"
                            {{ request('category') === 'properties' ? 'selected' : '' }}
                        >
                            Properties
                        </option>

                        <option
                            value="land"
                            {{ request('category') === 'land' ? 'selected' : '' }}
                        >
                            Land
                        </option>

                    </select>

                </div>


                {{-- Status --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>

                    <select
                        name="order_status"
                        class="w-full border border-[#DDE9E8] rounded-lg px-3 py-2 focus:ring-[#056663] focus:border-[#056663]"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="pending"
                            {{ request('order_status') === 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="confirmed"
                            {{ request('order_status') === 'confirmed' ? 'selected' : '' }}
                        >
                            Confirmed
                        </option>

                        <option
                            value="negotiation"
                            {{ request('order_status') === 'negotiation' ? 'selected' : '' }}
                        >
                            Negotiation
                        </option>

                        <option
                            value="documentation"
                            {{ request('order_status') === 'documentation' ? 'selected' : '' }}
                        >
                            Documentation
                        </option>

                        <option
                            value="completed"
                            {{ request('order_status') === 'completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                        <option
                            value="cancelled"
                            {{ request('order_status') === 'cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                        <option
                            value="rejected"
                            {{ request('order_status') === 'rejected' ? 'selected' : '' }}
                        >
                            Rejected
                        </option>

                    </select>

                </div>

            </div>


            {{-- Filter Buttons --}}
            <div class="flex flex-wrap gap-3 mt-4">

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-[#056663] text-white rounded-lg font-medium hover:bg-[#04514F]"
                >
                    Apply Filters
                </button>

                <a
                    href="{{ route('admin.transactions.index') }}"
                    class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- =========================================================
         Transactions Table
    ========================================================== --}}

    <div class="bg-white rounded-xl border border-[#DDE9E8] shadow-sm overflow-hidden">

        {{-- Table Header --}}
        <div class="px-6 py-4 border-b border-[#DDE9E8]">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Transactions
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Property and Land transactions
                    </p>

                </div>

                <div class="text-sm text-gray-500">
                    {{ $transactions->total() }} total
                </div>

            </div>

        </div>


        @if($transactions->count())

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-[#F7FAFA] border-b border-[#DDE9E8]">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                Transaction
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">
                                Buyer
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


                    <tbody class="divide-y divide-[#DDE9E8]">

                        @foreach($transactions as $transaction)

                            @php

                                $category = strtolower(
                                    trim((string) $transaction->category)
                                );

                                $sellerName =
                                    $transaction->seller->company_name
                                    ?? $transaction->seller->business_name
                                    ?? $transaction->seller->bussiness_name
                                    ?? $transaction->seller->name
                                    ?? 'N/A';

                                $status = strtolower(
                                    trim((string) $transaction->order_status)
                                );

                            @endphp


                            <tr class="hover:bg-[#F7FAFA]">

                                {{-- =====================================================
                                     Transaction
                                ====================================================== --}}
                                <td class="px-6 py-4">

                                    <div class="font-semibold text-[#056663]">
                                        {{ $transaction->order_number }}
                                    </div>

                                    @if($transaction->ordered_at)

                                        <div class="text-xs text-gray-500 mt-1">

                                            {{ $transaction->ordered_at->format('d M Y, h:i A') }}

                                        </div>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     Buyer
                                ====================================================== --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-800">

                                        {{ $transaction->buyer->name ?? 'N/A' }}

                                    </div>

                                    @if($transaction->buyer)

                                        <div class="text-xs text-gray-500 mt-1">

                                            {{ $transaction->buyer->email }}

                                        </div>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     Seller
                                ====================================================== --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-800">

                                        {{ $sellerName }}

                                    </div>

                                    @if($transaction->seller && $transaction->seller->user)

                                        <div class="text-xs text-gray-500 mt-1">

                                            {{ $transaction->seller->user->email }}

                                        </div>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     Category
                                ====================================================== --}}
                                <td class="px-6 py-4">

                                    @if(in_array($category, ['property', 'properties'], true))

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            Property
                                        </span>

                                    @elseif($category === 'land')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Land
                                        </span>

                                    @else

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ ucfirst($category) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     Amount
                                ====================================================== --}}
                                <td class="px-6 py-4">

                                    <span class="font-semibold text-gray-800">

                                        ₹{{ number_format((float) $transaction->total_amount, 2) }}

                                    </span>

                                </td>


                                {{-- =====================================================
                                     Status
                                ====================================================== --}}
                                <td class="px-6 py-4">

                                    @if($status === 'pending')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            Pending
                                        </span>

                                    @elseif($status === 'confirmed')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            Confirmed
                                        </span>

                                    @elseif($status === 'negotiation')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                            Negotiation
                                        </span>

                                    @elseif($status === 'documentation')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                            Documentation
                                        </span>

                                    @elseif($status === 'completed')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Completed
                                        </span>

                                    @elseif($status === 'cancelled')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            Cancelled
                                        </span>

                                    @elseif($status === 'rejected')

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            Rejected
                                        </span>

                                    @else

                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ ucfirst($status) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     Action
                                ====================================================== --}}
                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route('admin.transactions.show', $transaction->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-[#056663] text-white rounded-lg text-sm font-medium hover:bg-[#04514F]"
                                    >
                                        View 
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
                 Pagination
            ====================================================== --}}

            <div class="px-6 py-4 border-t border-[#DDE9E8]">

                {{ $transactions->links() }}

            </div>


        @else

            {{-- =====================================================
                 Empty State
            ====================================================== --}}

            <div class="py-16 text-center">

                <div class="text-5xl mb-4">
                    📋
                </div>

                <h3 class="text-lg font-semibold text-gray-800">
                    No Transactions Found
                </h3>

                <p class="text-gray-500 mt-2">
                    There are no Property or Land transactions matching your filters.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection