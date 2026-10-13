@extends('admin.layouts.app')

@section('title', 'Listing Management')

@section('page_title', 'Listing Management')

@section('content')

{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-center">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Listing Management
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Review, approve and manage marketplace listings.
        </p>
    </div>

    <div class="flex items-center gap-2">

        <span class="rounded-lg bg-[#E8F5F4] px-3 py-2 text-sm font-semibold text-[#056663]">
            Total: {{ $listings->total() }}
        </span>

    </div>

</div>


{{-- ========================================================= --}}
{{-- STATISTICS --}}
{{-- ========================================================= --}}

<div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

    {{-- Total --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Total Listings
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ \App\Models\Listing::count() }}
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                <span class="material-symbols-outlined">
                    inventory_2
                </span>
            </div>

        </div>

        <p class="mt-4 text-xs text-slate-400">
            All marketplace listings
        </p>

    </div>


    {{-- Pending --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Pending
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ \App\Models\Listing::where('status', 'pending')->count() }}
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <span class="material-symbols-outlined">
                    pending_actions
                </span>
            </div>

        </div>

        <p class="mt-4 text-xs text-slate-400">
            Waiting for admin approval
        </p>

    </div>


    {{-- Approved --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Approved
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ \App\Models\Listing::where('status', 'approved')->count() }}
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">
                <span class="material-symbols-outlined">
                    check_circle
                </span>
            </div>

        </div>

        <p class="mt-4 text-xs text-slate-400">
            Published marketplace listings
        </p>

    </div>


    {{-- Rejected --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm font-medium text-slate-500">
                    Rejected
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ \App\Models\Listing::where('status', 'rejected')->count() }}
                </p>
            </div>

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <span class="material-symbols-outlined">
                    cancel
                </span>
            </div>

        </div>

        <p class="mt-4 text-xs text-slate-400">
            Listings not approved
        </p>

    </div>

</div>


{{-- ========================================================= --}}
{{-- SEARCH & FILTERS --}}
{{-- ========================================================= --}}

<div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

    <form
        method="GET"
        action="{{ url('/admin/listings') }}"
        class="grid grid-cols-1 gap-4 md:grid-cols-4">

        {{-- Search --}}
        <div class="md:col-span-2">

            <label class="mb-2 block text-sm font-medium text-slate-700">
                Search Listings
            </label>

            <div class="relative">

                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    search
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by listing title..."
                    class="w-full rounded-xl border border-slate-200 py-2.5 pl-11 pr-4 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]">

            </div>

        </div>


        {{-- Category --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-slate-700">
                Category
            </label>

            <select
                name="category"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-[#056663] focus:ring-1 focus:ring-[#056663]">
                <option value="">
                    All Categories
                </option>

                <option value="1" {{ request('category') == '1' ? 'selected' : '' }}>
                    Properties
                </option>

                <option value="2" {{ request('category') == '2' ? 'selected' : '' }}>
                    Land
                </option>

                <option value="3" {{ request('category') == '3' ? 'selected' : '' }}>
                    Products
                </option>

                <option value="4" {{ request('category') == '4' ? 'selected' : '' }}>
                    Services
                </option>

                <option value="5" {{ request('category') == '5' ? 'selected' : '' }}>
                    Projects
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
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-[#056663] focus:ring-1 focus:ring-[#056663]">

                <option value="">
                    All Statuses
                </option>

                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                    Rejected
                </option>


            </select>

        </div>


        {{-- Buttons --}}
        <div class="flex items-end gap-2 md:col-span-4">

            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#045450]">
                <span class="material-symbols-outlined text-[18px]">
                    filter_alt
                </span>

                Apply Filters
            </button>

            <a
                href="{{ url('/admin/listings') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <span class="material-symbols-outlined text-[18px]">
                    refresh
                </span>

                Reset
            </a>

        </div>

    </form>

</div>


{{-- ========================================================= --}}
{{-- LISTINGS TABLE --}}
{{-- ========================================================= --}}

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    {{-- Table Header --}}
    <div class="flex flex-col justify-between gap-3 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center">

        <div>

            <h2 class="text-lg font-bold text-slate-800">
                Marketplace Listings
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Review and manage seller listings.
            </p>

        </div>

        <div class="text-sm text-slate-500">
            Showing
            <span class="font-semibold text-slate-700">
                {{ $listings->firstItem() ?? 0 }}
            </span>
            -
            <span class="font-semibold text-slate-700">
                {{ $listings->lastItem() ?? 0 }}
            </span>
            of
            <span class="font-semibold text-slate-700">
                {{ $listings->total() }}
            </span>
        </div>

    </div>


    {{-- Responsive Table --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[1000px]">

            <thead class="bg-slate-50">

                <tr class="border-b border-slate-200">

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Listing
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Seller
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Category
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Price
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Status
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($listings as $listing)

                <tr class="transition hover:bg-slate-50">

                    {{-- Listing --}}
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-4">

                            {{-- Image --}}
                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-slate-100">

                                @if(!empty($listing->image))

                                <img
                                    src="{{ url('/uploads/' . $listing->image) }}"
                                    alt="{{ $listing->title }}"
                                    class="h-full w-full object-cover">

                                @else

                                <div class="flex h-full w-full items-center justify-center text-slate-400">

                                    <span class="material-symbols-outlined">
                                        image
                                    </span>

                                </div>

                                @endif

                            </div>


                            <div class="min-w-0">

                                <p class="truncate font-semibold text-slate-800">
                                    {{ $listing->title ?? 'Untitled Listing' }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    ID: #{{ $listing->id }}
                                </p>

                            </div>

                        </div>

                    </td>


                    {{-- Seller --}}
                    <td class="px-6 py-5">

                        @if($listing->seller)

                        <p class="font-medium text-slate-700">
                            {{ $listing->seller->bussiness_name
                                            ?? $listing->seller->business_name
                                            ?? $listing->seller->name
                                            ?? 'Seller' }}
                        </p>

                        @if(isset($listing->seller->city))

                        <p class="mt-1 text-xs text-slate-400">
                            {{ $listing->seller->city }}
                        </p>

                        @endif

                        @else

                        <span class="text-sm text-slate-400">
                            Seller not available
                        </span>

                        @endif

                    </td>


                    {{-- Category --}}
                    <td class="px-6 py-5">

                        @php
                        $category = $listing->categoryRelation;

                        $categoryLabel = $category?->name ?? '—';
                        @endphp

                        <span class="inline-flex rounded-lg bg-[#E8F5F4] px-3 py-1.5 text-xs font-semibold text-[#056663]">
                            {{ $categoryLabel }}
                        </span>

                    </td>


                    {{-- Price --}}
                    <td class="px-6 py-5">

                        @if(isset($listing->price) && $listing->price !== null)

                        <span class="font-semibold text-slate-700">
                            ₹{{ number_format((float) $listing->price) }}
                        </span>

                        @else

                        <span class="text-sm text-slate-400">
                            Contact Seller
                        </span>

                        @endif

                    </td>


                    {{-- Status --}}
                    <td class="px-6 py-5">

                        @php
                        $status = strtolower($listing->status ?? 'pending');

                        $statusClasses = [
                        'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                        'approved' => 'bg-green-50 text-green-700 ring-green-600/20',
                        'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
                        'draft' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                        'inactive' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                        ];

                        $statusClass = $statusClasses[$status] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20';
                        @endphp

                        <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold capitalize ring-1 ring-inset {{ $statusClass }}">
                            {{ str_replace('_', ' ', $status) }}
                        </span>

                    </td>


                    {{-- Actions --}}
                    <td class="px-6 py-5">

                        <div class="flex items-center justify-end gap-2">

                            {{-- View --}}
                            <a
                                href="{{ url('/admin/listings/' . $listing->id) }}"
                                title="View Listing"
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-[#056663] hover:bg-[#E8F5F4] hover:text-[#056663]">
                                <span class="material-symbols-outlined text-[18px]">
                                    visibility
                                </span>
                            </a>


                            {{-- Edit --}}
                            <a
                                href="{{ url('/admin/listings/' . $listing->id . '/edit') }}"
                                title="Edit Listing"
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600">
                                <span class="material-symbols-outlined text-[18px]">
                                    edit
                                </span>
                            </a>


                            {{-- Approve --}}
                            @if(in_array($status, ['pending', 'draft']))

                            <form
                                method="POST"
                                action="{{ url('/admin/listings/' . $listing->id . '/approve') }}"
                                onsubmit="return confirm('Are you sure you want to approve this listing?');">

                                @csrf

                                <button
                                    type="submit"
                                    title="Approve Listing"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-green-200 text-green-600 transition hover:bg-green-50">
                                    <span class="material-symbols-outlined text-[18px]">
                                        check
                                    </span>
                                </button>

                            </form>

                            @endif


                            {{-- Reject --}}
                            @if($status === 'pending')

                            <button
                                type="button"
                                title="Reject Listing"
                                onclick="openRejectModal({{ $listing->id }} , '{{ addslashes($listing->title) }}')"
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 text-red-600 transition hover:bg-red-50">
                                <span class="material-symbols-outlined text-[18px]">
                                    close
                                </span>
                            </button>

                            @endif


                            {{-- Delete --}}
                            <form
                                method="POST"
                                action="{{ url('/admin/listings/' . $listing->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this listing? This action cannot be undone.');">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    title="Delete Listing"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                    <span class="material-symbols-outlined text-[18px]">
                                        delete
                                    </span>
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="px-6 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#E8F5F4] text-[#056663]">

                            <span class="material-symbols-outlined text-3xl">
                                inventory_2
                            </span>

                        </div>

                        <h3 class="mt-4 text-lg font-semibold text-slate-800">
                            No Listings Found
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            No marketplace listings match your current filters.
                        </p>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($listings->hasPages())

    <div class="border-t border-slate-200 px-6 py-5">

        {{ $listings->links() }}

    </div>

    @endif

</div>


{{-- ========================================================= --}}
{{-- REJECT MODAL --}}
{{-- ========================================================= --}}

<div
    id="rejectModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4">

    <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">

            <div>

                <h2 class="text-lg font-bold text-slate-800">
                    Reject Listing
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Provide a reason for rejecting this listing.
                </p>

            </div>

            <button
                type="button"
                onclick="closeRejectModal()"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <span class="material-symbols-outlined">
                    close
                </span>
            </button>

        </div>


        {{-- Modal Body --}}
        <form
            id="rejectForm"
            method="POST"
            class="p-6">

            @csrf

            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Listing
                </label>

                <div
                    id="rejectListingTitle"
                    class="rounded-xl bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700">
                    —
                </div>

            </div>


            <div class="mt-5">

                <label
                    for="rejection_reason"
                    class="mb-2 block text-sm font-medium text-slate-700">
                    Rejection Reason
                </label>

                <textarea
                    name="rejection_reason"
                    id="rejection_reason"
                    rows="4"
                    required
                    placeholder="Enter the reason for rejecting this listing..."
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"></textarea>

            </div>


            <div class="mt-6 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeRejectModal()"
                    class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                    Reject Listing
                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>
    function openRejectModal(id, title) {

        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        const listingTitle = document.getElementById('rejectListingTitle');

        form.action = '/admin/listings/' + id + '/reject';

        listingTitle.textContent = title;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    }


    function closeRejectModal() {

        const modal = document.getElementById('rejectModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');

        document.getElementById('rejection_reason').value = '';

    }


    document.getElementById('rejectModal').addEventListener('click', function(event) {

        if (event.target === this) {
            closeRejectModal();
        }

    });
</script>

@endsection