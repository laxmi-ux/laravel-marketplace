
<x-seller-layout>
    <div class="mx-auto max-w-4xl p-6">

        <a href="{{ route('seller.payments.index') }}"
           class="text-sm font-medium text-[#056663] hover:underline">
            ← Back to Payments
        </a>

        @php
            $status = strtolower($payment->status ?? 'pending');

            $statusClasses = match ($status) {
                'paid' => 'bg-green-100 text-green-800',
                'failed', 'rejected' => 'bg-red-100 text-red-800',
                default => 'bg-yellow-100 text-yellow-800',
            };
        @endphp

        <div class="my-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-[#056663]">
                    Payment Details
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Review the payment and verify the transaction.
                </p>
            </div>

            <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $statusClasses }}">
                {{ ucfirst(str_replace('_', ' ', $status)) }}
            </span>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Payment Information --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h2 class="font-semibold text-gray-800">
                    Payment Information
                </h2>
            </div>

            <div class="space-y-0 p-6">

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Order Number</span>
                    <span class="font-semibold text-gray-900">
                        {{ $payment->order?->order_number ?? 'N/A' }}
                    </span>
                </div>

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Buyer</span>
                    <span class="font-medium text-gray-900">
                        {{ $payment->buyer?->name ?? 'N/A' }}
                    </span>
                </div>

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Amount</span>
                    <span class="text-xl font-bold text-[#056663]">
                        ₹{{ number_format((float) $payment->amount, 2) }}
                    </span>
                </div>

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Currency</span>
                    <span class="font-medium text-gray-900">
                        {{ $payment->currency ?? 'INR' }}
                    </span>
                </div>

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Payment Method</span>
                    <span class="font-medium text-gray-900">
                        {{ $payment->payment_method ?? 'Not recorded' }}
                    </span>
                </div>

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Transaction Reference / UTR</span>
                    <span class="break-all font-mono font-medium text-gray-900">
                        {{ $payment->transaction_reference ?: 'Not provided' }}
                    </span>
                </div>

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Payment Gateway</span>
                    <span class="font-medium text-gray-900">
                        {{ $payment->gateway ?? 'Manual Payment' }}
                    </span>
                </div>

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Paid At</span>
                    <span class="font-medium text-gray-900">
                        {{ $payment->paid_at?->format('d M Y, h:i A') ?? 'Not paid' }}
                    </span>
                </div>

                <div class="flex flex-wrap justify-between gap-2 border-b py-4">
                    <span class="text-gray-500">Order Status</span>
                    <span class="font-medium text-gray-900">
                        {{ ucfirst(str_replace('_', ' ', $payment->order?->order_status ?? 'N/A')) }}
                    </span>
                </div>

                @if($payment->failure_reason)
                    <div class="border-b py-4">
                        <p class="text-sm font-medium text-red-700">
                            Payment Rejection Reason
                        </p>
                        <p class="mt-1 text-sm text-gray-700">
                            {{ $payment->failure_reason }}
                        </p>
                    </div>
                @endif

                @if($payment->notes)
                    <div class="py-4">
                        <p class="text-sm font-medium text-gray-500">
                            Payment Notes
                        </p>
                        <p class="mt-1 whitespace-pre-line text-sm text-gray-700">
                            {{ $payment->notes }}
                        </p>
                    </div>
                @endif

            </div>
        </div>

        {{-- Verification Actions --}}
        @if($status === 'pending')
            <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-bold text-gray-900">
                    Payment Verification
                </h2>

                <p class="mt-2 text-sm leading-6 text-gray-600">
                    Check your actual bank statement or payment account before
                    verifying. A transaction reference alone is not proof of payment.
                </p>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Verify Payment --}}
                    <div class="rounded-lg border border-green-200 bg-green-50 p-5">
                        <h3 class="font-semibold text-green-900">
                            Verify Payment
                        </h3>

                        <p class="mt-2 text-sm text-green-800">
                            Confirm only after checking that the money was actually received.
                        </p>

                        <form method="POST"
                              action="{{ route('seller.payments.verify', $payment->id) }}"
                              class="mt-5 space-y-4"
                              onsubmit="return confirm('Have you verified the actual bank credit? Continue only if the money has been received.');">

                            @csrf
                            @method('PATCH')

                            <label class="flex items-start gap-3 rounded-lg border border-green-200 bg-white p-3">
                                <input
                                    type="checkbox"
                                    name="payment_received"
                                    value="1"
                                    required
                                    class="mt-1 rounded border-gray-300 text-[#056663] focus:ring-[#056663]"
                                >

                                <span class="text-sm text-gray-700">
                                    I confirm that I checked the payment and the money
                                    has actually been received.
                                </span>
                            </label>

                            <button type="submit"
                                    class="w-full rounded-lg bg-green-700 px-4 py-3 font-semibold text-white hover:bg-green-800">
                                Verify Payment
                            </button>
                        </form>
                    </div>

                    {{-- Reject Payment --}}
                    <div class="rounded-lg border border-red-200 bg-red-50 p-5">
                        <h3 class="font-semibold text-red-900">
                            Reject Payment
                        </h3>

                        <p class="mt-2 text-sm text-red-800">
                            Reject only after checking the payment and determining that it is invalid or was not received.
                        </p>

                        <form method="POST"
                              action="{{ route('seller.payments.reject', $payment->id) }}"
                              class="mt-5 space-y-3">

                            @csrf
                            @method('PATCH')

                            <label for="failure_reason"
                                   class="block text-sm font-medium text-gray-700">
                                Reason for rejection
                            </label>

                            <textarea
                                id="failure_reason"
                                name="failure_reason"
                                rows="3"
                                maxlength="1000"
                                required
                                placeholder="Explain why the payment could not be verified..."
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200"
                            >{{ old('failure_reason') }}</textarea>

                            <button type="submit"
                                    class="w-full rounded-lg bg-red-700 px-4 py-3 font-semibold text-white hover:bg-red-800"
                                    onclick="return confirm('Are you sure you want to reject this payment?');">
                                Reject Payment
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        @elseif($status === 'paid')
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-5">
                <h2 class="font-semibold text-green-900">
                    Payment Verified
                </h2>
                <p class="mt-1 text-sm text-green-800">
                    This payment has been marked as paid.
                </p>
            </div>

        @elseif(in_array($status, ['failed', 'rejected'], true))
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-5">
                <h2 class="font-semibold text-red-900">
                    Payment Rejected
                </h2>
                <p class="mt-1 text-sm text-red-800">
                    This payment was rejected. Review the reason above before deciding how to proceed.
                </p>
            </div>
        @endif

    </div>
</x-seller-layout>
