<x-buyer-layout>
<div class="min-h-screen bg-[#F7FAFA] py-10">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('buyer.orders.index') }}"
               class="text-sm font-medium text-[#056663] hover:underline">
                ← Back to My Orders
            </a>

            <h1 class="mt-4 text-3xl font-bold text-gray-900">
                Checkout & Payment
            </h1>

            <p class="mt-2 text-sm text-gray-600">
                Review your order and submit your payment details for verification.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Order Details --}}
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Order Details
                    </h2>

                    <div class="mt-5 space-y-4">
                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Order Number</span>
                            <span class="text-right font-medium text-gray-900">
                                {{ $order->order_number ?? ('#'.$order->id) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Product / Listing</span>
                            <span class="text-right font-medium text-gray-900">
                                {{ $order->listing->title ?? 'Marketplace Order' }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Quantity</span>
                            <span class="font-medium text-gray-900">
                                {{ $order->quantity ?? 1 }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Order Status</span>
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                {{ ucfirst(str_replace('_', ' ', $order->order_status ?? 'pending')) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-gray-500">Payment Status</span>
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                {{ ucfirst(str_replace('_', ' ', $payment->status ?? 'pending')) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Payment Form --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Submit Payment Details
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Pay using the payment instructions provided by the seller or marketplace.
                        Then enter your UPI transaction reference or bank transfer reference below.
                        Your payment will remain pending until it is verified.
                    </p>

                    @if(($payment->status ?? 'pending') === 'paid')
                        <div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                            This payment has already been marked as paid.
                        </div>
                    @else
                        <form method="POST"
                              action="{{ route('buyer.orders.manual-payment', $order->id) }}"
                              class="mt-6 space-y-5">
                            @csrf

                            <div>
                                <label for="payment_method"
                                       class="mb-2 block text-sm font-medium text-gray-700">
                                    Payment Method
                                </label>

                                <select id="payment_method"
                                        name="payment_method"
                                        required
                                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-[#056663] focus:outline-none focus:ring-2 focus:ring-[#056663]/20">
                                    <option value="">Select payment method</option>
                                    <option value="UPI" @selected(old('payment_method') === 'UPI')>
                                        UPI
                                    </option>
                                    <option value="Bank Transfer" @selected(old('payment_method') === 'Bank Transfer')>
                                        Bank Transfer
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="transaction_reference"
                                       class="mb-2 block text-sm font-medium text-gray-700">
                                    Transaction Reference / UTR
                                </label>

                                <input id="transaction_reference"
                                       type="text"
                                       name="transaction_reference"
                                       value="{{ old('transaction_reference') }}"
                                       required
                                       maxlength="255"
                                       placeholder="Enter your UPI transaction ID or bank UTR"
                                       class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-[#056663] focus:outline-none focus:ring-2 focus:ring-[#056663]/20">
                            </div>

                            <button type="submit"
                                    class="w-full rounded-lg bg-[#056663] px-5 py-3 font-semibold text-white transition hover:bg-[#044F4D]">
                                Submit Payment for Verification
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Amount Summary --}}
            <div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Amount Summary
                    </h2>

                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-gray-900">
                                ₹{{ number_format((float)($order->subtotal ?? $order->total_amount), 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Delivery Charges</span>
                            <span class="font-medium text-gray-900">
                                ₹{{ number_format((float)($order->delivery_charges ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Other Charges</span>
                            <span class="font-medium text-gray-900">
                                ₹{{ number_format((float)($order->other_charges ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">Tax</span>
                            <span class="font-medium text-gray-900">
                                ₹{{ number_format((float)($order->tax_amount ?? 0), 2) }}
                            </span>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between gap-3">
                                <span class="font-semibold text-gray-900">Total Amount</span>
                                <span class="text-xl font-bold text-[#056663]">
                                    ₹{{ number_format((float)($order->total_amount ?? $payment->amount ?? 0), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <p class="mt-5 text-xs leading-5 text-gray-500">
                        Submitting a transaction reference does not confirm payment.
                        Verification is required before a prepaid order can proceed.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
</x-buyer-layout>