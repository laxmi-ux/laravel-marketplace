@extends('admin.layouts.app')

@section('title', 'Buyer Management')

@section('page-title', 'Buyer Management')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Success --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Buyers
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Manage all registered buyers on the marketplace.
            </p>
        </div>

        <div class="rounded-xl bg-[#E8F5F4] px-5 py-3">

            <p class="text-xs font-semibold uppercase tracking-wide text-[#056663]">
                Total Buyers
            </p>

            <p class="mt-1 text-xl font-bold text-[#056663]">
                {{ $buyers->total() }}
            </p>

        </div>

    </div>


    {{-- Filters --}}
    <div class="rounded-2xl border border-[#DDE9E8] bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.buyers.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-4"
        >

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Search Buyer
                </label>

                <div class="relative">

                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">

                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                            />
                        </svg>

                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, email or phone..."
                        class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm
                               focus:border-[#056663] focus:ring-[#056663]"
                    >

                </div>

            </div>


            {{-- Status --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                           focus:border-[#056663] focus:ring-[#056663]"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="active"
                        {{ request('status') === 'active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        {{ request('status') === 'inactive' ? 'selected' : '' }}
                    >
                        Inactive
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="rounded-xl bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-[#045451]"
                >
                    Apply
                </button>

                <a
                    href="{{ route('admin.buyers.index') }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-2.5
                           text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Buyers Table --}}
    <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-[#F7FAFA]">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Buyer
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Contact
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Registered
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 bg-white">

                    @forelse($buyers as $buyer)

                        <tr class="transition hover:bg-[#F7FAFA]">

                            {{-- Buyer --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#E8F5F4] text-sm font-bold text-[#056663]">
                                        {{ strtoupper(substr($buyer->name ?? 'B', 0, 1)) }}
                                    </div>

                                    <div>

                                        <p class="font-semibold text-gray-900">
                                            {{ $buyer->name ?? 'N/A' }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            Buyer #{{ $buyer->id }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Contact --}}
                            <td class="px-6 py-4">

                                <p class="text-sm text-gray-800">
                                    {{ $buyer->email ?? 'N/A' }}
                                </p>

                                @if(!empty($buyer->phone))

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $buyer->phone }}
                                    </p>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @if(($buyer->status ?? 'active') === 'active')

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Registered --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">

                                {{ $buyer->created_at?->format('d M Y') ?? 'N/A' }}

                            </td>


                            {{-- Action --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.buyers.show', $buyer->id) }}"
                                    class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold
                                           text-[#056663] transition hover:bg-[#E8F5F4]"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center"
                            >

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                    <svg
                                        class="h-6 w-6 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>

                                </div>

                                <h3 class="mt-4 text-sm font-semibold text-gray-900">
                                    No buyers found
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Try changing your search or filter criteria.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($buyers->hasPages())

            <div class="border-t border-gray-100 px-6 py-4">

                {{ $buyers->links() }}

            </div>

        @endif

    </div>

</div>

@endsection@extends('admin.layouts.app')

@section('title', 'Buyer Management')

@section('page-title', 'Buyer Management')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Success --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Buyers
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Manage all registered buyers on the marketplace.
            </p>
        </div>

        <div class="rounded-xl bg-[#E8F5F4] px-5 py-3">

            <p class="text-xs font-semibold uppercase tracking-wide text-[#056663]">
                Total Buyers
            </p>

            <p class="mt-1 text-xl font-bold text-[#056663]">
                {{ $buyers->total() }}
            </p>

        </div>

    </div>


    {{-- Filters --}}
    <div class="rounded-2xl border border-[#DDE9E8] bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.buyers.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-4"
        >

            {{-- Search --}}
            <div class="md:col-span-2">

                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Search Buyer
                </label>

                <div class="relative">

                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">

                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                            />
                        </svg>

                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, email or phone..."
                        class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-sm
                               focus:border-[#056663] focus:ring-[#056663]"
                    >

                </div>

            </div>


            {{-- Status --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm
                           focus:border-[#056663] focus:ring-[#056663]"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="active"
                        {{ request('status') === 'active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        {{ request('status') === 'inactive' ? 'selected' : '' }}
                    >
                        Inactive
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="rounded-xl bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-[#045451]"
                >
                    Apply
                </button>

                <a
                    href="{{ route('admin.buyers.index') }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-2.5
                           text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- Buyers Table --}}
    <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-[#F7FAFA]">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Buyer
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Contact
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Registered
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100 bg-white">

                    @forelse($buyers as $buyer)

                        <tr class="transition hover:bg-[#F7FAFA]">

                            {{-- Buyer --}}
                            <td class="whitespace-nowrap px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#E8F5F4] text-sm font-bold text-[#056663]">
                                        {{ strtoupper(substr($buyer->name ?? 'B', 0, 1)) }}
                                    </div>

                                    <div>

                                        <p class="font-semibold text-gray-900">
                                            {{ $buyer->name ?? 'N/A' }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            Buyer #{{ $buyer->id }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Contact --}}
                            <td class="px-6 py-4">

                                <p class="text-sm text-gray-800">
                                    {{ $buyer->email ?? 'N/A' }}
                                </p>

                                @if(!empty($buyer->phone))

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $buyer->phone }}
                                    </p>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @if(($buyer->status ?? 'active') === 'active')

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Registered --}}
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">

                                {{ $buyer->created_at?->format('d M Y') ?? 'N/A' }}

                            </td>


                            {{-- Action --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.buyers.show', $buyer->id) }}"
                                    class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold
                                           text-[#056663] transition hover:bg-[#E8F5F4]"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center"
                            >

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                    <svg
                                        class="h-6 w-6 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>

                                </div>

                                <h3 class="mt-4 text-sm font-semibold text-gray-900">
                                    No buyers found
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Try changing your search or filter criteria.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($buyers->hasPages())

            <div class="border-t border-gray-100 px-6 py-4">

                {{ $buyers->links() }}

            </div>

        @endif

    </div>

</div>

@endsection