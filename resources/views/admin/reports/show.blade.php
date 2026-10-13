@extends('admin.layouts.app')

@section('content')

<div class="min-h-screen bg-[#F7FAFA] p-4 md:p-6">
    <div class="mb-6">
        <a href="{{ route('admin.reports.index') }}"
           class="text-sm font-semibold text-[#056663] hover:underline">
            &larr; Back to Reports
        </a>


    <h1 class="mt-3 text-2xl font-bold text-gray-800">
        Report #{{ $report->id }}
    </h1>
    <p class="mt-1 text-sm text-gray-500">Review the complaint and record the resolution.</p>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">
        <ul class="list-inside list-disc">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-gray-800">Report Details</h2>

            <dl class="space-y-4">
                <div>
                    <dt class="text-sm text-gray-500">Subject</dt>
                    <dd class="mt-1 font-semibold text-gray-800">{{ $report->subject }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-gray-500">Reason</dt>
                    <dd class="mt-1 text-gray-800">{{ $report->reason }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-gray-500">Description</dt>
                    <dd class="mt-1 whitespace-pre-line text-gray-800">{{ $report->description }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-gray-500">Related Listing</dt>
                    <dd class="mt-1 text-gray-800">
                        {{ $report->listing?->title ?? 'No listing linked' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-gray-500">Submitted</dt>
                    <dd class="mt-1 text-gray-800">
                        {{ $report->created_at?->format('d M Y, h:i A') }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-gray-800">People Involved</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-sm text-gray-500">Reporter</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $report->reporter?->name ?? 'Not available' }}
                    </p>
                    <p class="break-all text-sm text-gray-500">
                        {{ $report->reporter?->email ?? '' }}
                    </p>
                </div>

                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-sm text-gray-500">Reported User</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $report->reportedUser?->name ?? 'Not specified' }}
                    </p>
                    <p class="break-all text-sm text-gray-500">
                        {{ $report->reportedUser?->email ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <form method="POST"
              action="{{ route('admin.reports.update', $report) }}"
              class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            @csrf
            @method('PUT')

            <h2 class="mb-5 text-lg font-bold text-gray-800">Manage Report</h2>

            <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">
                Report Status
            </label>
            <select id="status" name="status" required
                    class="mb-5 w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]">
                @foreach([
                    'pending' => 'Pending',
                    'under_review' => 'Under Review',
                    'resolved' => 'Resolved',
                    'dismissed' => 'Dismissed',
                ] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $report->status) === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <label for="admin_notes" class="mb-2 block text-sm font-semibold text-gray-700">
                Admin Notes
            </label>
            <textarea id="admin_notes" name="admin_notes" rows="6"
                      maxlength="5000"
                      placeholder="Enter investigation notes or resolution details..."
                      class="mb-5 w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]">{{ old('admin_notes', $report->admin_notes) }}</textarea>

            <button type="submit"
                    class="w-full rounded-lg bg-[#056663] px-4 py-3 font-semibold text-white hover:bg-[#044f4d]">
                Save Changes
            </button>
        </form>
    </div>
</div>


</div>
@endsection
