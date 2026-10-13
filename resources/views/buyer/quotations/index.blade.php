<x-buyer-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Quotations
            </h2>

            <p class="text-sm text-[#64748B] mt-0.5">
                View and manage quotations received from sellers.
            </p>
        </div>
    </x-slot>


    <div class="min-h-screen bg-[#F7FAFA]">

        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- PAGE HEADER --}}
            <div class="mb-7 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-3">

                    <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#E8F5F4] text-[#056663]">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 14l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                            My Quotations
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            View and manage quotations received from sellers.
                        </p>
                    </div>

                </div>


                {{-- TOTAL --}}
                <div class="w-full sm:w-auto min-w-[180px] rounded-2xl border border-[#D3E3E0] bg-white px-5 py-4 shadow-sm">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Total Quotations
                    </p>

                    <div class="flex items-end gap-2 mt-1">

                        <p class="text-2xl font-bold text-[#00665F]">
                            {{ $quotations->total() }}
                        </p>

                        <span class="text-xs text-gray-400 pb-1">
                            quotations
                        </span>

                    </div>

                </div>

            </div>


            {{-- ALERTS --}}
            @if(session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3.5 text-sm text-green-700">

                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>

                    <span>{{ session('success') }}</span>

                </div>
            @endif


            @if(session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700">

                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>

                    <span>{{ session('error') }}</span>

                </div>
            @endif


            {{-- QUOTATIONS --}}
            <div class="space-y-5">

                @forelse($quotations as $quotation)

                    @php
                        $category = strtolower($quotation->category ?? 'general');

                        $categoryLabel = match($category) {
                            'product' => 'Product',
                            'land' => 'Land',
                            'property' => 'Property',
                            'service' => 'Service',
                            'project' => 'Project',
                            default => 'General',
                        };

                        $categoryClasses = match($category) {
                            'product' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'land' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'property' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'service' => 'bg-orange-50 text-orange-700 border-orange-200',
                            'project' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                        };

                        $statusClasses = match($quotation->status) {
                            'accepted' => 'bg-green-50 text-green-700 border-green-200',
                            'rejected' => 'bg-red-50 text-red-700 border-red-200',
                            'expired' => 'bg-gray-100 text-gray-600 border-gray-200',
                            'countered' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            default => 'bg-blue-50 text-blue-700 border-blue-200',
                        };

                        $itemName = match($category) {
                            'product' => $quotation->product_name,
                            'land' => $quotation->land_location,
                            'property' => $quotation->property_type,
                            'service' => $quotation->service_name,
                            'project' => $quotation->project_name,
                            default => null,
                        };
                    @endphp


                    <article class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm hover:shadow-md transition">

                        {{-- HEADER --}}
                        <div class="px-5 sm:px-6 py-5 border-b border-[#E8EFEE]">

                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                                <div class="min-w-0">

                                    <div class="flex flex-wrap items-center gap-2 mb-3">

                                        {{-- CATEGORY --}}
                                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $categoryClasses }}">
                                            {{ $categoryLabel }}
                                        </span>

                                        {{-- STATUS --}}
                                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold capitalize {{ $statusClasses }}">
                                            {{ str_replace('_', ' ', $quotation->status) }}
                                        </span>

                                        <span class="text-xs text-gray-400">
                                            {{ $quotation->created_at->format('d M Y') }}
                                        </span>

                                    </div>


                                    {{-- ITEM --}}
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 break-words">

                                        {{ $itemName
                                            ?? $quotation->requirement?->listing?->title
                                            ?? 'General Requirement' }}

                                    </h2>


                                    {{-- LISTING --}}
                                    @if($quotation->requirement?->listing)

                                        <p class="mt-1 text-sm text-gray-500">
                                            Listing:
                                            <span class="font-medium text-gray-700">
                                                {{ $quotation->requirement->listing->title }}
                                            </span>
                                        </p>

                                    @else

                                        <p class="mt-1 text-sm text-gray-500">
                                            General Requirement
                                        </p>

                                    @endif


                                    {{-- SELLER --}}
                                    <p class="mt-1.5 text-sm text-gray-500">

                                        Seller:

                                        <span class="font-semibold text-gray-700">
                                            {{ $quotation->seller?->bussiness_name
                                                ?? $quotation->seller?->business_name
                                                ?? $quotation->seller?->name
                                                ?? 'Seller' }}
                                        </span>

                                    </p>

                                </div>


                                {{-- AMOUNT --}}
                                <div class="lg:text-right lg:min-w-[190px]">

                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                        Total Amount
                                    </p>

                                    <p class="mt-1 text-2xl sm:text-3xl font-bold text-[#00665F]">
                                        ₹{{ number_format((float) $quotation->total_amount, 2) }}
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- DETAILS --}}
                        <div class="px-5 sm:px-6 py-5">

                            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                                {{-- CATEGORY-SPECIFIC SUMMARY --}}

                                @if($category === 'product')

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Quantity
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            {{ $quotation->quantity ?? '—' }}
                                            {{ $quotation->unit ?? '' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Unit Price
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            ₹{{ number_format((float) ($quotation->unit_price ?? 0), 2) }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Subtotal
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            ₹{{ number_format((float) ($quotation->product_subtotal ?? 0), 2) }}
                                        </p>
                                    </div>

                                @elseif($category === 'land')

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Land Area
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            {{ $quotation->land_area ?? '—' }}
                                            {{ $quotation->land_area_unit ?? '' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Price / Unit
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            ₹{{ number_format((float) ($quotation->land_price_per_unit ?? 0), 2) }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Land Price
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            ₹{{ number_format((float) ($quotation->land_price ?? 0), 2) }}
                                        </p>
                                    </div>

                                @elseif($category === 'property')

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Property Type
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            {{ $quotation->property_type ?? '—' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Area
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            {{ $quotation->property_area ?? '—' }}
                                            {{ $quotation->property_area_unit ?? '' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Property Price
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            ₹{{ number_format((float) ($quotation->property_price ?? 0), 2) }}
                                        </p>
                                    </div>

                                @elseif($category === 'service')

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Service
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            {{ $quotation->service_name ?? '—' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Duration
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            {{ $quotation->service_duration ?? '—' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Service Charges
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            ₹{{ number_format((float) ($quotation->service_charges ?? 0), 2) }}
                                        </p>
                                    </div>

                                @elseif($category === 'project')

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Project
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            {{ $quotation->project_name ?? '—' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Timeline
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            {{ $quotation->project_timeline ?? '—' }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">
                                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                            Project Value
                                        </p>
                                        <p class="mt-1 text-sm font-semibold text-gray-800">
                                            ₹{{ number_format((float) ($quotation->project_value ?? 0), 2) }}
                                        </p>
                                    </div>

                                @endif


                                {{-- OTHER CHARGES --}}
                                <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">

                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                        Other Charges
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800">
                                        ₹{{ number_format((float) ($quotation->other_charges ?? 0), 2) }}
                                    </p>

                                </div>


                                {{-- TAX --}}
                                <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">

                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                        Tax
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800">
                                        ₹{{ number_format((float) ($quotation->tax ?? 0), 2) }}
                                    </p>

                                </div>


                                {{-- VALIDITY --}}
                                <div class="rounded-xl bg-[#F7FAF9] px-4 py-3.5">

                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                        Valid Until
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800">
                                        {{ $quotation->valid_until?->format('d M Y') ?? '—' }}
                                    </p>

                                </div>

                            </div>


                            {{-- DELIVERY TIME --}}
                            @if($quotation->delivery_time)

                                <div class="mt-4 rounded-xl bg-[#F7FAF9] border border-[#E4ECEB] px-4 py-3.5">

                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                        Delivery / Completion Time
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-800">
                                        {{ $quotation->delivery_time }}
                                    </p>

                                </div>

                            @endif


                            {{-- NOTES --}}
                            @if($quotation->notes)

                                <div class="mt-5 rounded-xl border border-[#E4ECEB] bg-[#F7FAF9] px-4 py-4">

                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                                        Seller Notes
                                    </p>

                                    <p class="mt-2 text-sm leading-6 text-gray-600 whitespace-pre-line">
                                        {{ $quotation->notes }}
                                    </p>

                                </div>

                            @endif


                            {{-- ACTIONS --}}
                            <div class="mt-5 pt-5 border-t border-[#E8EFEE] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                                <a
                                    href="{{ route('buyer.quotations.show', $quotation->id) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#00665F] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#00554F]">

                                    View Quotation

                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>

                                </a>


                                @if($quotation->status === 'sent')

                                    <div class="flex flex-col sm:flex-row gap-3">

                                        <form
                                            action="{{ route('buyer.quotations.accept', $quotation->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to accept this quotation?');">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-green-200 bg-green-50 px-5 py-2.5 text-sm font-semibold text-green-700 transition hover:bg-green-100">

                                                Accept

                                            </button>

                                        </form>


                                        <form
                                            action="{{ route('buyer.quotations.reject', $quotation->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to reject this quotation?');">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100">

                                                Reject

                                            </button>

                                        </form>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </article>

                @empty

                    <div class="rounded-2xl border border-dashed border-[#C9D9D7] bg-white px-6 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EAF5F3] text-[#00665F]">

                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M9 14.25l6-6m-7.5 1.5h.008v.008H7.5V9.75zm9 4.5h.008v.008H16.5v-.008z"
                                />
                            </svg>

                        </div>

                        <h3 class="mt-5 text-lg font-semibold text-gray-900">
                            No quotations yet
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                            Quotations received from sellers will appear here.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- PAGINATION --}}
            @if($quotations->hasPages())

                <div class="mt-7">
                    {{ $quotations->links() }}
                </div>

            @endif

        </div>

    </div>

</x-buyer-layout>