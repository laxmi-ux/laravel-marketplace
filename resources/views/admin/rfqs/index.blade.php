@extends('admin.layouts.app')

@section('title', 'RFQ Management')

@section('page-title', 'RFQ Management')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Auto-RFQ Management
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Monitor RFQs generated from buyer requirements.
            </p>
        </div>

    </div>


    {{-- Search / Filter --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

        <form method="GET"
              action="{{ route('admin.rfqs.index') }}"
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
                    placeholder="RFQ ID, requirement, buyer or seller..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                           text-sm focus:border-[#056663]
                           focus:ring-[#056663]"
                >

            </div>


            {{-- Status --}}
            <div>

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5
                           text-sm focus:border-[#056663]
                           focus:ring-[#056663]"
                >

                    <option value="">All Statuses</option>

                    <option value="new"
                        {{ request('status') === 'new' ? 'selected' : '' }}>
                        New
                    </option>

                    <option value="viewed"
                        {{ request('status') === 'viewed' ? 'selected' : '' }}>
                        Viewed
                    </option>

                    <option value="quoted"
                        {{ request('status') === 'quoted' ? 'selected' : '' }}>
                        Quoted
                    </option>

                    <option value="rejected"
                        {{ request('status') === 'rejected' ? 'selected' : '' }}>
                        Rejected
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="rounded-lg bg-[#056663] px-5 py-2.5
                           text-sm font-semibold text-white
                           hover:bg-[#04514f]"
                >
                    Filter
                </button>

                <a
                    href="{{ route('admin.rfqs.index') }}"
                    class="rounded-lg border border-gray-300
                           px-5 py-2.5 text-sm font-semibold
                           text-gray-700 hover:bg-gray-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- RFQ Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            RFQ
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Requirement
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Buyer
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Seller
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Sent
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold
                                   uppercase tracking-wider text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($rfqs as $rfq)

                        <tr class="hover:bg-gray-50">

                            {{-- RFQ --}}
                            <td class="px-6 py-4">

                                <div class="font-semibold text-gray-900">
                                    #{{ $rfq->id }}
                                </div>

                            </td>


                            {{-- Requirement --}}
                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    #{{ $rfq->requirement_id }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ ucfirst($rfq->requirement->requirement_type ?? 'General') }}
                                </div>

                            </td>


                            {{-- Buyer --}}
                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $rfq->requirement->name ?? 'N/A' }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $rfq->requirement->email ?? '' }}
                                </div>

                            </td>


                            {{-- Seller --}}
                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">

                                    {{ $rfq->seller->bussiness_name
                                        ?? 'Seller #' . $rfq->seller_id }}

                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @php
                                    $statusClasses = match($rfq->status) {
                                        'new' => 'bg-blue-50 text-blue-700',
                                        'viewed' => 'bg-yellow-50 text-yellow-700',
                                        'quoted' => 'bg-green-50 text-green-700',
                                        'rejected' => 'bg-red-50 text-red-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp

                                <span class="inline-flex rounded-full px-3 py-1
                                             text-xs font-semibold {{ $statusClasses }}">

                                    {{ ucfirst($rfq->status) }}

                                </span>

                            </td>


                            {{-- Sent --}}
                            <td class="px-6 py-4 text-sm text-gray-500">

                                {{ $rfq->sent_at
                                    ? $rfq->sent_at->format('d M Y, h:i A')
                                    : '—' }}

                            </td>


                            {{-- Action --}}
                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.rfqs.show', $rfq) }}"
                                    class="inline-flex items-center rounded-lg
                                           bg-[#056663] px-4 py-2
                                           text-xs font-semibold text-white
                                           hover:bg-[#04514f]"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="px-6 py-12 text-center">

                                <div class="text-sm font-medium text-gray-700">
                                    No RFQs found.
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    RFQs generated from accepted requirements
                                    will appear here.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($rfqs->hasPages())

            <div class="border-t border-gray-200 px-6 py-4">
                {{ $rfqs->links() }}
            </div>

        @endif

    </div>

</div>

@endsection