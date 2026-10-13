<x-buyer-layout>

    {{-- Header --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4]">
                <svg
                    class="h-6 w-6 text-[#056663]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    My RFQs
                </h2>

                <p class="text-sm text-gray-500">
                    Manage your requirements and seller responses
                </p>
            </div>
        </div>
    </x-slot>


    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">

        {{-- Success Message --}}
        @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif


        {{-- Error Message --}}
        @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
        @endif


        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        {{-- Page Intro --}}
        <div class="rounded-2xl border border-[#D3E3E0] bg-white p-6 shadow-sm">

            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Request for Quotations
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Your requirements have been shared with matching sellers.
                        View seller responses and communicate with them.
                    </p>
                </div>

                <a
                    href="{{ route('buyer.dashboard') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#04534F]">
                    Back to Dashboard
                </a>

            </div>

        </div>


        {{-- RFQ List --}}
        @if($rfqs->count())

        <div class="space-y-4">

            @foreach($rfqs as $rfq)

            @php
            $requirement = $rfq->requirement;
            $seller = $rfq->seller;

            $statusClasses = match($rfq->status) {
            'new' => 'bg-blue-50 text-blue-700 border-blue-200',
            'viewed' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
            'quoted' => 'bg-green-50 text-green-700 border-green-200',
            'rejected' => 'bg-red-50 text-red-700 border-red-200',
            'expired' => 'bg-gray-100 text-gray-600 border-gray-200',
            'closed' => 'bg-purple-50 text-purple-700 border-purple-200',
            default => 'bg-gray-100 text-gray-600 border-gray-200',
            };

            $requirementTitle =
            $requirement?->product_name
            ?? $requirement?->property_type
            ?? $requirement?->land_type
            ?? $requirement?->service_name
            ?? $requirement?->project_name
            ?? $requirement?->requirement_type
            ?? 'General Requirement';

            $sellerName =
            $seller?->bussiness_name
            ?? $seller?->business_name
            ?? 'Seller';
            @endphp


            <div class="overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm transition hover:shadow-md">

                {{-- Top Section --}}
                <div class="border-b border-gray-100 p-5 sm:p-6">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">

                        {{-- Requirement --}}
                        <div class="min-w-0 flex-1">

                            <div class="mb-2 flex flex-wrap items-center gap-2">

                                <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                                    RFQ #{{ $rfq->id }}
                                </span>

                                <span class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                    {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                                </span>

                            </div>

                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $requirementTitle }}
                            </h3>

                            @if($requirement?->message)
                            <p class="mt-2 line-clamp-2 text-sm text-gray-500">
                                {{ $requirement->message }}
                            </p>
                            @endif

                        </div>


                        {{-- Seller --}}
                        <div class="rounded-xl bg-[#F7FBFA] p-4 lg:min-w-[260px]">

                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                Seller
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $sellerName }}
                            </p>

                            @if($seller?->location)
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $seller->location }}
                            </p>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- Details --}}
                <div class="grid grid-cols-2 gap-4 border-b border-gray-100 p-5 sm:grid-cols-4 sm:p-6">

                    <div>
                        <p class="text-xs font-medium text-gray-400">
                            Quantity
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $requirement?->quantity ?? '—' }}
                            {{ $requirement?->unit ?? '' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium text-gray-400">
                            Budget
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            @if($requirement?->budget !== null)
                            ₹{{ number_format((float) $requirement->budget, 2) }}
                            @else
                            —
                            @endif
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium text-gray-400">
                            Delivery Location
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $requirement?->delivery_location ?? '—' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-medium text-gray-400">
                            Required Date
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $requirement?->required_date?->format('d M Y') ?? '—' }}
                        </p>
                    </div>

                </div>


                {{-- Footer Actions --}}
                <div class="flex flex-col gap-3 bg-gray-50 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">

                    <div class="text-xs text-gray-500">
                        Sent
                        {{ $rfq->sent_at?->format('d M Y, h:i A') ?? $rfq->created_at?->format('d M Y, h:i A') }}
                    </div>


                    <div class="flex flex-col gap-2 sm:flex-row">

                        <a
                            href="{{ route('buyer.rfqs.show', $rfq->id) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#056663] bg-white px-4 py-2.5 text-sm font-semibold text-[#056663] transition hover:bg-[#E8F5F4]">
                            View RFQ
                        </a>



                        <div class="flex flex-col gap-2 sm:flex-row">

                            {{-- View RFQ --}}
                            <a
                                href="{{ route('buyer.rfqs.show', $rfq->id) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#056663] bg-white px-4 py-2.5 text-sm font-semibold text-[#056663] transition hover:bg-[#E8F5F4]">
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                View RFQ
                            </a>

                        </div>


                    </div>

                </div>

                @endforeach

            </div>


            {{-- Pagination --}}
            <div class="pt-2">
                {{ $rfqs->links() }}
            </div>

            @else

            {{-- Empty State --}}
            <div class="rounded-2xl border border-dashed border-[#C8DAD7] bg-white px-6 py-16 text-center shadow-sm">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#E8F5F4]">

                    <svg
                        class="h-8 w-8 text-[#056663]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>

                </div>

                <h3 class="mt-5 text-lg font-bold text-gray-900">
                    No RFQs Found
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                    Once you submit a requirement and matching sellers are found,
                    your RFQs will appear here.
                </p>

                <a
                    href="{{ route('requirement.show') }}"
                    class="mt-6 inline-flex items-center justify-center rounded-xl bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#04534F]">
                    Submit Requirement
                </a>

            </div>

            @endif

        </div>

</x-buyer-layout>