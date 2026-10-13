@extends('admin.layouts.app')

@section('title', 'Sellers')

@section('page-title', 'Sellers')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Sellers
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Manage registered sellers and their marketplace accounts.
            </p>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SEARCH / FILTER --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-[#DDE9E8] bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.sellers.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-4"
        >

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Search Seller
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Business name, owner, email, category..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5
                           text-sm outline-none transition
                           focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                >

            </div>


            {{-- Verification --}}
            <div>

                <label class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Verification
                </label>

                <select
                    name="verified"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5
                           text-sm outline-none
                           focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                >

                    <option value="">
                        All Sellers
                    </option>

                    <option
                        value="1"
                        {{ request('verified') === '1' ? 'selected' : '' }}
                    >
                        Verified
                    </option>

                    <option
                        value="0"
                        {{ request('verified') === '0' ? 'selected' : '' }}
                    >
                        Not Verified
                    </option>

                </select>

            </div>


            {{-- Featured --}}
            <div>

                <label class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Featured
                </label>

                <select
                    name="featured"
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5
                           text-sm outline-none
                           focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/10"
                >

                    <option value="">
                        All
                    </option>

                    <option
                        value="1"
                        {{ request('featured') === '1' ? 'selected' : '' }}
                    >
                        Featured
                    </option>

                    <option
                        value="0"
                        {{ request('featured') === '0' ? 'selected' : '' }}
                    >
                        Not Featured
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-2 md:col-span-4">

                <button
                    type="submit"
                    class="rounded-xl bg-[#056663] px-5 py-2.5
                           text-sm font-semibold text-white
                           transition hover:bg-[#045451]"
                >
                    Search
                </button>

                <a
                    href="{{ route('admin.sellers.index') }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-2.5
                           text-sm font-semibold text-gray-700
                           transition hover:bg-gray-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- SELLERS TABLE --}}
    {{-- ========================================================= --}}

    <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

            <div>

                <h2 class="text-lg font-semibold text-gray-900">
                    Seller Management
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $sellers->total() }} seller{{ $sellers->total() == 1 ? '' : 's' }} found.
                </p>

            </div>

        </div>


        @if($sellers->count())

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-100">

                    <thead class="bg-[#F7FAFA]">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Seller
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Owner
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Category
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Location
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Verification
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($sellers as $seller)

                            <tr class="transition hover:bg-[#F7FAFA]">

                                {{-- Seller --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center
                                                    overflow-hidden rounded-xl bg-[#E8F5F4]
                                                    font-bold text-[#056663]">

                                            @if($seller->logo)

                                                <img
                                                    src="{{ route('uploads.file', ['path' => $seller->logo]) }}"
                                                    alt="{{ $seller->bussiness_name }}"
                                                    class="h-full w-full object-cover"
                                                >

                                            @else

                                                {{ strtoupper(substr($seller->bussiness_name ?? 'S', 0, 1)) }}

                                            @endif

                                        </div>


                                        <div>

                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $seller->bussiness_name ?? 'N/A' }}
                                            </p>

                                            <p class="mt-1 text-xs text-gray-500">
                                                Seller #{{ $seller->id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Owner --}}
                                <td class="px-6 py-4">

                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $seller->user?->name ?? 'N/A' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $seller->user?->email ?? '' }}
                                    </p>

                                </td>


                                {{-- Category --}}
                                <td class="px-6 py-4">

                                    <span class="text-sm text-gray-700">
                                        {{ $seller->category ?? 'N/A' }}
                                    </span>

                                </td>


                                {{-- Location --}}
                                <td class="px-6 py-4">

                                    <span class="text-sm text-gray-700">
                                        {{ $seller->location ?? 'N/A' }}
                                    </span>

                                </td>


                                {{-- Verification --}}
                                <td class="px-6 py-4">

                                    <div class="flex flex-wrap gap-2">

                                        @if($seller->is_verified)

                                            <span class="inline-flex items-center gap-1 rounded-full
                                                         bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                                Verified

                                            </span>

                                        @else

                                            <span class="rounded-full bg-yellow-50 px-3 py-1
                                                         text-xs font-semibold text-yellow-700">
                                                Not Verified
                                            </span>

                                        @endif


                                        @if($seller->is_featured)

                                            <span class="rounded-full bg-purple-50 px-3 py-1
                                                         text-xs font-semibold text-purple-700">
                                                Featured
                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- Action --}}
                                <td class="px-6 py-4 text-right">

                                    <a
                                        href="{{ route('admin.sellers.show', $seller->id) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg
                                               border border-[#DDE9E8] bg-white px-3 py-2
                                               text-xs font-semibold text-[#056663]
                                               transition hover:bg-[#E8F5F4]"
                                    >

                                        <span class="material-symbols-outlined text-[17px]">
                                            visibility
                                        </span>

                                        View

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="border-t border-gray-100 px-6 py-4">

                {{ $sellers->links() }}

            </div>

        @else

            <div class="px-6 py-16 text-center">

                <span class="material-symbols-outlined text-5xl text-gray-300">
                    store
                </span>

                <p class="mt-4 text-sm font-semibold text-gray-700">
                    No sellers found
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    No seller accounts match your current filters.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection