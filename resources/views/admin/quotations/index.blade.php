@extends('admin.layouts.app')

@section('title', 'Quotation Management')
@section('page-title', 'Quotation Management')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Quotation Management
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Manage and monitor quotations submitted by sellers.
        </p>
    </div>


    {{-- Filters --}}
    <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">

        <form method="GET" action="{{ route('admin.quotations.index') }}"
              class="grid grid-cols-1 gap-4 md:grid-cols-4">

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Quotation ID, buyer, seller..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                           focus:border-[#056663] focus:ring-[#056663]"
                >

            </div>


            {{-- Category --}}
            <div>

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Category
                </label>

                <select
                    name="category"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                           focus:border-[#056663] focus:ring-[#056663]"
                >

                    <option value="">All Categories</option>

                    <option value="product"
                        {{ request('category') === 'product' ? 'selected' : '' }}>
                        Product
                    </option>

                    <option value="land"
                        {{ request('category') === 'land' ? 'selected' : '' }}>
                        Land
                    </option>

                    <option value="property"
                        {{ request('category') === 'property' ? 'selected' : '' }}>
                        Property
                    </option>

                    <option value="service"
                        {{ request('category') === 'service' ? 'selected' : '' }}>
                        Service
                    </option>

                    <option value="project"
                        {{ request('category') === 'project' ? 'selected' : '' }}>
                        Project
                    </option>

                </select>

            </div>


            {{-- Status --}}
            <div>

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm
                           focus:border-[#056663] focus:ring-[#056663]"
                >

                    <option value="">All Statuses</option>

                    <option value="sent"
                        {{ request('status') === 'sent' ? 'selected' : '' }}>
                        Sent
                    </option>

                    <option value="draft"
                        {{ request('status') === 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="accepted"
                        {{ request('status') === 'accepted' ? 'selected' : '' }}>
                        Accepted
                    </option>

                    <option value="rejected"
                        {{ request('status') === 'rejected' ? 'selected' : '' }}>
                        Rejected
                    </option>

                    <option value="expired"
                        {{ request('status') === 'expired' ? 'selected' : '' }}>
                        Expired
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-2 md:col-span-4">

                <button
                    type="submit"
                    class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold
                           text-white hover:bg-[#045552]"
                >
                    Filter
                </button>

                <a
                    href="{{ route('admin.quotations.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5
                           text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Quotations Table --}}
    <div class="overflow-hidden rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-[#F7FAFA]">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Quotation
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Category
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Buyer
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Seller
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Total Amount
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 bg-white">

                    @forelse($quotations as $quotation)

                        <tr class="transition hover:bg-[#F7FAFA]">

                            {{-- Quotation --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="font-semibold text-gray-900">
                                    Quotation #{{ $quotation->id }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    Requirement #{{ $quotation->requirement_id }}
                                </div>

                            </td>


                            {{-- Category --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                @php
                                    $category = strtolower((string) $quotation->category);

                                    $categoryClass = match($category) {
                                        'product' => 'bg-blue-50 text-blue-700',
                                        'property' => 'bg-purple-50 text-purple-700',
                                        'land' => 'bg-green-50 text-green-700',
                                        'service' => 'bg-yellow-50 text-yellow-700',
                                        'project' => 'bg-orange-50 text-orange-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold capitalize {{ $categoryClass }}">
                                    {{ $quotation->category
                                        ? str_replace('_', ' ', $quotation->category)
                                        : '—' }}
                                </span>

                            </td>


                            {{-- Buyer --}}
                            <td class="px-6 py-4">

                                @if($quotation->buyer)

                                    <div class="font-medium text-gray-900">
                                        {{ $quotation->buyer->name }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $quotation->buyer->email }}
                                    </div>

                                @else

                                    <span class="text-sm text-gray-400">
                                        Buyer not found
                                    </span>

                                @endif

                            </td>


                            {{-- Seller --}}
                            <td class="px-6 py-4">

                                @if($quotation->seller)

                                    <div class="font-medium text-gray-900">
                                        {{ $quotation->seller->bussiness_name }}
                                    </div>

                                    @if($quotation->seller->location)

                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $quotation->seller->location }}
                                        </div>

                                    @endif

                                @else

                                    <span class="text-sm text-gray-400">
                                        Seller not found
                                    </span>

                                @endif

                            </td>


                            {{-- Total --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <span class="font-semibold text-[#056663]">
                                    ₹{{ number_format((float) ($quotation->total_amount ?? 0), 2) }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                @php
                                    $statusClass = match(strtolower((string) $quotation->status)) {
                                        'sent' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'draft' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        'accepted' => 'bg-green-50 text-green-700 border-green-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        'expired' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp

                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold capitalize {{ $statusClass }}">
                                    {{ $quotation->status
                                        ? str_replace('_', ' ', $quotation->status)
                                        : '—' }}
                                </span>

                            </td>


                            {{-- Action --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.quotations.show', $quotation->id) }}"
                                    class="inline-flex items-center rounded-lg border border-[#056663]
                                           px-3 py-2 text-sm font-medium text-[#056663]
                                           hover:bg-[#E8F5F4]"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-6 py-14 text-center">

                                <div class="text-sm text-gray-500">
                                    No quotations found.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($quotations->hasPages())

            <div class="border-t border-gray-100 px-6 py-4">
                {{ $quotations->links() }}
            </div>

        @endif

    </div>

</div>

@endsection