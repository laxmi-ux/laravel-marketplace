@extends('admin.layouts.app')

@section('title', 'Requirements')

@section('page-title', 'Requirements')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Buyer Requirements
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Review buyer requirements and manage the Auto-RFQ approval process.
            </p>
        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="flex items-center gap-3 rounded-xl border border-green-200
                    bg-green-50 px-5 py-4 text-sm text-green-700">

            <span class="material-symbols-outlined text-[20px]">
                check_circle
            </span>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="flex items-center gap-3 rounded-xl border border-red-200
                    bg-red-50 px-5 py-4 text-sm text-red-700">

            <span class="material-symbols-outlined text-[20px]">
                error
            </span>

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4">

            <div class="flex items-start gap-3">

                <span class="material-symbols-outlined text-red-500">
                    error
                </span>

                <div>
                    <p class="text-sm font-semibold text-red-700">
                        Please fix the following errors:
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.requirements.index') }}"
            class="grid gap-4 md:grid-cols-4"
        >

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Search
                </label>

                <div class="relative">

                    <span class="material-symbols-outlined absolute left-3 top-1/2
                                 -translate-y-1/2 text-[20px] text-slate-400">
                        search
                    </span>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search buyer, email, phone..."
                        class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4
                               text-sm outline-none transition
                               focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                    >

                </div>

            </div>


            {{-- Requirement Source --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Requirement Source
                </label>

                <select
                    name="requirement_source"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                           text-sm outline-none transition
                           focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                >

                    <option value="">
                        All Sources
                    </option>

                    <option
                        value="general"
                        {{ request('requirement_source') === 'general' ? 'selected' : '' }}
                    >
                        General Requirement
                    </option>

                    <option
                        value="listing"
                        {{ request('requirement_source') === 'listing' ? 'selected' : '' }}
                    >
                        Listing Requirement
                    </option>

                </select>

            </div>


            {{-- Status --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5
                           text-sm outline-none transition
                           focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="pending_review"
                        {{ request('status') === 'pending_review' ? 'selected' : '' }}
                    >
                        Pending Review
                    </option>

                    <option
                        value="accepted"
                        {{ request('status') === 'accepted' ? 'selected' : '' }}
                    >
                        Accepted
                    </option>

                    <option
                        value="rejected"
                        {{ request('status') === 'rejected' ? 'selected' : '' }}
                    >
                        Rejected
                    </option>

                    <option
                        value="rfq_generated"
                        {{ request('status') === 'rfq_generated' ? 'selected' : '' }}
                    >
                        RFQ Generated
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-2 md:col-span-4">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl
                           bg-[#056663] px-5 py-2.5 text-sm font-semibold
                           text-white transition hover:bg-[#045450]"
                >

                    <span class="material-symbols-outlined text-[19px]">
                        filter_alt
                    </span>

                    Filter

                </button>


                <a
                    href="{{ route('admin.requirements.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border
                           border-slate-200 bg-white px-5 py-2.5 text-sm
                           font-semibold text-slate-600 transition hover:bg-slate-50"
                >

                    <span class="material-symbols-outlined text-[19px]">
                        refresh
                    </span>

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- =========================================================
        REQUIREMENTS TABLE
    ========================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[1100px]">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wide text-slate-500">
                            Buyer
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wide text-slate-500">
                            Requirement
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wide text-slate-500">
                            Listing
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wide text-slate-500">
                            Type
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wide text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold
                                   uppercase tracking-wide text-slate-500">
                            Submitted
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold
                                   uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($requirements as $requirement)

                        @php

                            $status = strtolower($requirement->status ?? 'pending_review');

                            $statusClasses = match($status) {

                                'pending_review',
                                'new',
                                'pending_admin'
                                    => 'bg-amber-50 text-amber-700 border-amber-200',

                                'accepted'
                                    => 'bg-blue-50 text-blue-700 border-blue-200',

                                'rejected'
                                    => 'bg-red-50 text-red-700 border-red-200',

                                'rfq_generated'
                                    => 'bg-green-50 text-green-700 border-green-200',

                                default
                                    => 'bg-slate-50 text-slate-600 border-slate-200',

                            };


                            $statusLabel = match($status) {

                                'pending_review' => 'Pending Review',

                                'pending_admin' => 'Pending Review',

                                'rfq_generated' => 'RFQ Generated',

                                default => ucfirst(str_replace('_', ' ', $status)),

                            };

                        @endphp


                        <tr class="transition hover:bg-slate-50">


                            {{-- Buyer --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center
                                               justify-center rounded-full bg-[#E8F5F4]
                                               font-semibold text-[#056663]"
                                    >

                                        {{ strtoupper(substr($requirement->name ?? 'B', 0, 1)) }}

                                    </div>


                                    <div>

                                        <p class="font-semibold text-slate-800">
                                            {{ $requirement->name ?? '—' }}
                                        </p>

                                        @if($requirement->email)

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $requirement->email }}
                                            </p>

                                        @endif

                                        @if($requirement->phone)

                                            <p class="mt-1 text-xs text-slate-400">
                                                {{ $requirement->phone }}
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Requirement --}}
                            <td class="px-6 py-5">

                                <div class="max-w-[250px]">

                                    <p class="font-medium text-slate-700">

                                        {{ $requirement->message
                                            ? \Illuminate\Support\Str::limit(
                                                $requirement->message,
                                                70
                                            )
                                            : 'Requirement submitted'
                                        }}

                                    </p>


                                    @if($requirement->quantity)

                                        <p class="mt-1 text-xs text-slate-500">

                                            Qty:
                                            {{ $requirement->quantity }}
                                            {{ $requirement->unit ?? '' }}

                                        </p>

                                    @endif

                                </div>

                            </td>


                            {{-- Listing --}}
                            <td class="px-6 py-5">

                                @if($requirement->listing)

                                    <p class="max-w-[190px] truncate font-medium text-slate-700">

                                        {{ $requirement->listing->title }}

                                    </p>

                                @else

                                    <span class="text-sm text-slate-400">
                                        General Requirement
                                    </span>

                                @endif

                            </td>


                            {{-- Type --}}
                            <td class="px-6 py-5">

                                @if($requirement->requirement_source === 'listing')

                                    <span
                                        class="inline-flex rounded-lg border border-[#BFE2DF]
                                               bg-[#E8F5F4] px-3 py-1.5 text-xs
                                               font-semibold text-[#056663]"
                                    >
                                        Listing
                                    </span>

                                @else

                                    <span
                                        class="inline-flex rounded-lg border border-slate-200
                                               bg-slate-50 px-3 py-1.5 text-xs
                                               font-semibold text-slate-600"
                                    >
                                        General
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-5">

                                <span
                                    class="inline-flex rounded-lg border px-3 py-1.5
                                           text-xs font-semibold {{ $statusClasses }}"
                                >
                                    {{ $statusLabel }}
                                </span>

                            </td>


                            {{-- Submitted --}}
                            <td class="px-6 py-5">

                                <p class="text-sm font-medium text-slate-700">

                                    {{ $requirement->created_at?->format('d M Y') ?? '—' }}

                                </p>

                                @if($requirement->created_at)

                                    <p class="mt-1 text-xs text-slate-400">

                                        {{ $requirement->created_at->format('h:i A') }}

                                    </p>

                                @endif

                            </td>


                            {{-- Action --}}
                            <td class="px-6 py-5">

                                <div class="flex justify-end">

                                    <a
                                        href="{{ route('admin.requirements.show', $requirement) }}"
                                        title="View Requirement"
                                        class="flex h-9 w-9 items-center justify-center
                                               rounded-lg border border-slate-200
                                               text-slate-500 transition
                                               hover:border-[#056663]
                                               hover:bg-[#E8F5F4]
                                               hover:text-[#056663]"
                                    >

                                        <span class="material-symbols-outlined text-[18px]">
                                            visibility
                                        </span>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div
                                        class="flex h-14 w-14 items-center
                                               justify-center rounded-full bg-slate-100"
                                    >

                                        <span
                                            class="material-symbols-outlined
                                                   text-2xl text-slate-400"
                                        >
                                            inbox
                                        </span>

                                    </div>


                                    <h3 class="mt-4 font-semibold text-slate-700">
                                        No requirements found
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-400">
                                        Buyer requirements will appear here.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($requirements->hasPages())

            <div class="border-t border-slate-100 px-6 py-4">

                {{ $requirements->links() }}

            </div>

        @endif

    </div>

</div>

@endsection