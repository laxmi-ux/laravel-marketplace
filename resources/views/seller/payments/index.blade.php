
<x-seller-layout>
<div class="mx-auto max-w-6xl p-6">
    <h1 class="mb-6 text-2xl font-bold text-[#056663]">Order Payments</h1>

    <div class="overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Order</th>
                    <th class="p-3">Buyer</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3">Method</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="border-t">
                        <td class="p-3">{{ $payment->order?->order_number ?? 'N/A' }}</td>
                        <td class="p-3">{{ $payment->buyer?->name ?? 'N/A' }}</td>
                        <td class="p-3">₹{{ number_format((float) $payment->amount, 2) }}</td>
                        <td class="p-3">{{ $payment->payment_method ?? 'Not recorded' }}</td>
                        <td class="p-3">{{ ucfirst($payment->status) }}</td>
                        <td class="p-3">{{ $payment->created_at?->format('d M Y') }}</td>
                        <td class="p-3">
                            <a class="font-semibold text-[#056663]"
                               href="{{ route('seller.payments.show', $payment->id) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">
                        No payment records found yet.
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $payments->links() }}</div>
</div>

</x-seller-layout>