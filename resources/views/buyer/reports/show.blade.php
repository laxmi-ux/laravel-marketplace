
<x-buyer-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('buyer.notifications.index') }}"
               class="flex h-9 w-9 items-center justify-center rounded-lg text-[#056663] hover:bg-[#E8F5F4]">
                &larr;
            </a>

            <h2 class="text-xl font-semibold text-[#056663]">
                Report Details
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6">
        @if(session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">

            <div>
                <p class="text-sm text-gray-500">Report ID</p>
                <p class="font-semibold">#{{ $report->id }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Subject</p>
                <p class="font-semibold">{{ $report->subject }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Reason</p>
                <p>{{ ucwords(str_replace('_', ' ', $report->reason)) }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Your Description</p>
                <p class="whitespace-pre-line">{{ $report->description }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Current Status</p>

                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-50 text-yellow-700',
                        'under_review' => 'bg-blue-50 text-blue-700',
                        'resolved' => 'bg-green-50 text-green-700',
                        'dismissed' => 'bg-red-50 text-red-700',
                    ];

                    $statusClass = $statusClasses[$report->status]
                        ?? 'bg-gray-100 text-gray-700';
                @endphp

                <span class="inline-block rounded-full px-3 py-1 text-sm font-medium {{ $statusClass }}">
                    {{ ucwords(str_replace('_', ' ', $report->status)) }}
                </span>
            </div>

            @if($report->admin_notes)
                <div class="rounded-lg border border-teal-100 bg-teal-50 p-4">
                    <p class="text-sm font-semibold text-[#056663]">
                        Admin Response
                    </p>

                    <p class="mt-2 whitespace-pre-line text-gray-700">{{ $report->admin_notes }}</p>
                </div>
            @endif

            <div class="border-t pt-4">
                <a href="{{ route('buyer.notifications.index') }}"
                   class="inline-flex items-center rounded-lg bg-[#056663] px-5 py-2 text-white hover:bg-[#045450]">
                    Back to Notifications
                </a>
            </div>

        </div>
    </div>
</x-buyer-layout>
