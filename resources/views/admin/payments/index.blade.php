
@extends('admin.layouts.app')

@section('title', 'Payment Management')

@section('content')
<div class="mx-auto max-w-7xl p-4 sm:p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#056663]">Payment Management</h1>
        <p class="mt-1 text-sm text-gray-500">
            View and manage buyer payments, transaction references, and payment status.
        </p>
    </div>

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

    <form method="GET" action="{{ route('admin.payments.index') }}"
          class="mb-5 flex flex-col gap-3 rounded-xl border border-[#DDE9E8] bg-white p-4 sm:flex-row">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search order number or transaction reference..."
            class="w-full rounded-lg border-gray-300 p-3 text-sm focus:border-[#056663] focus:ring-[#056663] sm:flex-1"
        >

        <select
            name="status"
            class="rounded-lg border-gray-300 p-3 text-sm focus:border-[#056663] focus:ring-[#056663]"
        >
            <option value="">All statuses</option>
            @foreach(['pending', 'paid', 'failed', 'refunded'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="rounded-lg bg-[#056663] px-5 py-3 text-sm font-semibold text-white hover:bg-[#045450]">
            Filter
        </button>

        <a href="{{ route('admin.payments.index') }}"
           class="rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Reset
        </a>
    </form>

    <div class="overflow-hidden rounded-xl border border-[#DDE9E8] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-[#F7FAFA] text-gray-700">
                    <tr>
                        <th class="p-4 font-semibold">Payment / Order</th>
                        <th class="p-4 font-semibold">Buyer</th>
                        <th class="p-4 font-semibold">Seller</th>
                        <th class="p-4 font-semibold">Amount</th>
                        <th class="p-4 font-semibold">Method</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 text-right font-semibold">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        @php
                            $statusClass = match (strtolower((string) $payment->status)) {
                                'paid' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'failed', 'rejected' => 'bg-red-100 text-red-700',
                                'refunded' => 'bg-purple-100 text-purple-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="p-4">
                                <p class="font-semibold text-gray-900">
                                    Payment #{{ $payment->id }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Order: {{ $payment->order?->order_number ?? 'N/A' }}
                                </p>
                                <p class="mt-1 max-w-[220px] break-all text-xs text-gray-500">
                                    Ref: {{ $payment->transaction_reference ?: 'Not provided' }}
                                </p>
                            </td>

                            <td class="p-4 text-gray-700">
                                {{ $payment->buyer?->name ?? 'N/A' }}
                            </td>

                            <td class="p-4 text-gray-700">
                                {{ $payment->seller?->business_name
                                    ?? $payment->seller?->company_name
                                    ?? $payment->seller?->name
                                    ?? 'N/A' }}
                            </td>

                            <td class="p-4 whitespace-nowrap font-semibold text-gray-900">
                                {{ $payment->currency ?? 'INR' }}
                                {{ number_format((float) $payment->amount, 2) }}
                            </td>

                            <td class="p-4 text-gray-700">
                                {{ $payment->payment_method ?? 'Not recorded' }}
                            </td>

                            <td class="p-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($payment->status ?? 'Unknown') }}
                                </span>
                            </td>

                            <td class="p-4 text-right">
                                <a href="{{ route('admin.payments.show', $payment->id) }}"
                                   class="inline-flex rounded-lg border border-[#056663] px-4 py-2 font-semibold text-[#056663] hover:bg-[#F0F8F7]">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center">
                                <p class="font-semibold text-gray-700">No payment records found.</p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Try changing your search term or status filter.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $payments->appends(request()->query())->links() }}
    </div>

</div>
@endsection
