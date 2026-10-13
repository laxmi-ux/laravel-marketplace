
@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

    @php
        $status = strtolower((string) ($payment->status ?? 'unknown'));

        $statusClass = match ($status) {
            'paid' => 'bg-green-100 text-green-700',
            'pending' => 'bg-yellow-100 text-yellow-700',
            'failed', 'rejected' => 'bg-red-100 text-red-700',
            'refunded' => 'bg-purple-100 text-purple-700',
            default => 'bg-gray-100 text-gray-700',
        };
    @endphp

    {{-- Header --}}
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-[#056663]">Payment Details</h1>
            <p class="mt-1 text-sm text-gray-500">
                Review payment information and manage payment verification.
            </p>
        </div>

        <a href="{{ route('admin.payments.index') }}"
           class="inline-flex items-center justify-center rounded-lg border border-[#DDE9E8] px-4 py-2 text-sm font-semibold text-[#056663] hover:bg-[#F7FAFA]">
            ← Back to Payments
        </a>
    </div>

    {{-- Messages --}}
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

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Please correct the following:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Payment information --}}
    <div class="overflow-hidden rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="border-b border-[#DDE9E8] bg-[#F7FAFA] px-6 py-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="font-semibold text-gray-900">
                        Payment #{{ $payment->id }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Order: {{ $payment->order?->order_number ?? 'N/A' }}
                    </p>
                </div>

                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                    {{ ucfirst($payment->status ?? 'Unknown') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            <div>
                <p class="text-sm text-gray-500">Order Number</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $payment->order?->order_number ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Buyer</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $payment->buyer?->name ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Seller</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $payment->seller?->business_name
                        ?? $payment->seller?->company_name
                        ?? $payment->seller?->name
                        ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Payment Amount</p>
                <p class="mt-1 text-xl font-bold text-[#056663]">
                    {{ $payment->currency ?? 'INR' }}
                    {{ number_format((float) $payment->amount, 2) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Payment Method</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $payment->payment_method ?? 'Not recorded' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Transaction Reference / UTR</p>
                <p class="mt-1 break-all font-medium text-gray-900">
                    {{ $payment->transaction_reference ?: 'Not provided' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Payment Gateway</p>
                <p class="mt-1 text-gray-900">
                    {{ $payment->gateway ?? 'Manual Payment' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Gateway Order ID</p>
                <p class="mt-1 break-all text-gray-900">
                    {{ $payment->gateway_order_id ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Gateway Payment ID</p>
                <p class="mt-1 break-all text-gray-900">
                    {{ $payment->gateway_payment_id ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Paid At</p>
                <p class="mt-1 text-gray-900">
                    {{ $payment->paid_at?->format('d M Y, h:i A') ?? 'Not paid' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Created At</p>
                <p class="mt-1 text-gray-900">
                    {{ $payment->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Last Updated</p>
                <p class="mt-1 text-gray-900">
                    {{ $payment->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm text-gray-500">Notes</p>
                <p class="mt-1 whitespace-pre-line text-gray-900">
                    {{ $payment->notes ?: 'No notes available.' }}
                </p>
            </div>

            @if($payment->failure_reason)
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 sm:col-span-2">
                    <p class="text-sm font-semibold text-red-700">
                        Rejection / Failure Reason
                    </p>
                    <p class="mt-1 whitespace-pre-line text-sm text-red-700">
                        {{ $payment->failure_reason }}
                    </p>
                </div>
            @endif

        </div>

        {{-- Admin actions --}}
        @if($status === 'pending')
            <div class="border-t border-[#DDE9E8] bg-[#F7FAFA] p-6">

                <h3 class="text-lg font-semibold text-gray-900">
                    Manage Payment
                </h3>

                <p class="mt-1 text-sm text-gray-600">
                    Confirm payment receipt using reliable payment evidence before approving.
                </p>

                <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Approve payment --}}
                    <form
                        method="POST"
                        action="{{ route('admin.payments.approve', $payment->id) }}"
                        onsubmit="return confirm('Have you verified the payment and confirmed that the funds were received?');"
                        class="rounded-lg border border-green-200 bg-white p-4"
                    >
                        @csrf
                        @method('PATCH')

                        <h4 class="font-semibold text-gray-900">Approve Payment</h4>
                        <p class="mt-1 mb-4 text-sm text-gray-500">
                            Mark this payment as paid after verifying the receipt of funds.
                        </p>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-[#056663] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#045450]"
                        >
                            Approve Payment
                        </button>
                    </form>

                    {{-- Reject payment --}}
                    <form
                        method="POST"
                        action="{{ route('admin.payments.reject', $payment->id) }}"
                        onsubmit="return confirm('Are you sure you want to reject this payment?');"
                        class="space-y-3 rounded-lg border border-red-200 bg-white p-4"
                    >
                        @csrf
                        @method('PATCH')

                        <h4 class="font-semibold text-gray-900">Reject Payment</h4>

                        <label for="failure_reason"
                               class="block text-sm font-medium text-gray-700">
                            Rejection Reason
                        </label>

                        <textarea
                            name="failure_reason"
                            id="failure_reason"
                            rows="3"
                            maxlength="1000"
                            required
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-[#056663] focus:ring-[#056663]"
                            placeholder="Explain why this payment cannot be approved."
                        >{{ old('failure_reason') }}</textarea>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700"
                        >
                            Reject Payment
                        </button>
                    </form>

                </div>
            </div>
        @else
            <div class="border-t border-[#DDE9E8] px-6 py-4 text-sm text-gray-500">
                This payment is currently
                <strong>{{ $status }}</strong>.
                Approval and rejection actions are unavailable.
            </div>
        @endif

    </div>
</div>
@endsection