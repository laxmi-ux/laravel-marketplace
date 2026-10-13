@extends('admin.layouts.app')

@section('content')

<div class="min-h-screen bg-[#F7FAFA] p-4 md:p-6">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Report Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage marketplace reports and complaints.</p>
        </div>
    </div>


@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">
        {{ session('success') }}
    </div>
@endif

<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
    @foreach([
        ['Total Reports', $counts['total']],
        ['Pending', $counts['pending']],
        ['Under Review', $counts['under_review']],
        ['Resolved', $counts['resolved']],
        ['Dismissed', $counts['dismissed']],
    ] as [$label, $value])
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-bold text-[#056663]">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm md:p-5">
    <form method="GET" action="{{ route('admin.reports.index') }}"
          class="mb-5 flex flex-col gap-3 md:flex-row">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search subject, reason or description..."
            class="w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
        >

        <select name="status"
                class="rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]">
            <option value="">All statuses</option>
            @foreach([
                'pending' => 'Pending',
                'under_review' => 'Under Review',
                'resolved' => 'Resolved',
                'dismissed' => 'Dismissed',
            ] as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <button class="rounded-lg bg-[#056663] px-5 py-2 font-semibold text-white hover:bg-[#044f4d]">
            Filter
        </button>

        <a href="{{ route('admin.reports.index') }}"
           class="rounded-lg border border-gray-300 px-5 py-2 text-center text-gray-700 hover:bg-gray-50">
            Reset
        </a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] text-left text-sm">
            <thead class="bg-[#F0F7F6] text-gray-600">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Subject</th>
                    <th class="px-4 py-3">Reporter</th>
                    <th class="px-4 py-3">Reported User</th>
                    <th class="px-4 py-3">Reason</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reports as $report)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">{{ $report->id }}</td>
                        <td class="px-4 py-4 font-semibold text-gray-800">
                            {{ $report->subject }}
                        </td>
                        <td class="px-4 py-4">
                            {{ $report->reporter?->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4">
                            {{ $report->reportedUser?->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4">{{ $report->reason }}</td>
                        <td class="px-4 py-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'under_review' => 'bg-blue-100 text-blue-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'dismissed' => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp
                            <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$report->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ str($report->status)->replace('_', ' ')->title() }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-4 py-4">
                            {{ $report->created_at?->format('d M Y') }}
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('admin.reports.show', $report) }}"
                               class="font-semibold text-[#056663] hover:underline">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                            No reports found. Reports submitted by users will appear here.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $reports->links() }}
    </div>
</div>

</div>
@endsection
