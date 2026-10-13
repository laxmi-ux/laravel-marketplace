<x-buyer-layout>
<div class="mx-auto max-w-3xl p-6">
    <a href="{{ route('buyer.payments.index') }}" class="text-[#056663]">← Back to My Payments</a>
    <h1 class="my-5 text-2xl font-bold text-[#056663]">Payment Details</h1>

    <div class="space-y-4 rounded-xl border bg-white p-6">
        <p><strong>Order:</strong> {{ $payment->order?->order_number ?? 'N/A' }}</p>
        <p><strong>Amount:</strong> ₹{{ number_format((float) $payment->amount, 2) }}</p>
        <p><strong>Currency:</strong> {{ $payment->currency }}</p>
        <p><strong>Method:</strong> {{ $payment->payment_method ?? 'Not recorded' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
        <p><strong>Transaction Reference:</strong> {{ $payment->transaction_reference ?? 'N/A' }}</p>
        <p><strong>Paid At:</strong> {{ $payment->paid_at?->format('d M Y, h:i A') ?? 'Not paid' }}</p>
    </div>
</div>
</x-buyer-layout>