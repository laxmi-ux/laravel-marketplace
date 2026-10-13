@extends('admin.layouts.app')

@section('title', 'Category Management')

@section('content')
<div class="min-h-screen bg-[#F7FAFA] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- Header --}}
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Category Management</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage marketplace categories and their status.
                </p>
            </div>

            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-[#056663] px-5 py-3 text-sm font-semibold text-white hover:bg-[#045451]">
                + Add Category
            </a>
        </div>

        {{-- Messages --}}
        @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Summary --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Total Categories</p>
                <p class="mt-2 text-3xl font-bold text-[#056663]">
                    {{ $categories->total() }}
                </p>
            </div>

            <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Active on This Page</p>
                <p class="mt-2 text-3xl font-bold text-green-600">
                    {{ $categories->getCollection()->where('status', true)->count() }}
                </p>
            </div>

            <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
                <p class="text-sm text-gray-500">Subcategories on This Page</p>
                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ $categories->getCollection()->sum('subcategories_count') }}
                </p>
            </div>
        </div>

        {{-- Search and filter --}}
        <form method="GET" action="{{ route('admin.categories.index') }}"
            class="mb-6 flex flex-col gap-3 rounded-xl border border-[#DDE9E8] bg-white p-4 sm:flex-row">

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search categories..."
                class="w-full rounded-lg border-gray-300 text-sm focus:border-[#056663] focus:ring-[#056663]">

            <select name="status"
                class="rounded-lg border-gray-300 text-sm focus:border-[#056663] focus:ring-[#056663]">
                <option value="">All statuses</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit"
                class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045451]">
                Search
            </button>

            <a href="{{ route('admin.categories.index') }}"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Reset
            </a>
        </form>

        {{-- Category table --}}
        <div class="overflow-hidden rounded-xl border border-[#DDE9E8] bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-[#EAF3F2] text-gray-700">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Category</th>
                            <th class="px-5 py-4 font-semibold">Slug</th>
                            <th class="px-5 py-4 font-semibold">Subcategories</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-900">
                                    {{ $category->name }}
                                </div>
                                @if($category->description)
                                <div class="mt-1 max-w-xs truncate text-xs text-gray-500">
                                    {{ $category->description }}
                                </div>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $category->slugs ?? $category->slug ?? '—' }}
                            </td>

                            <td class="px-5 py-4 text-gray-700">
                                {{ $category->subcategories_count ?? 0 }}
                            </td>

                            <td class="px-5 py-4">
                                @if($category->status)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Active
                                </span>
                                @else
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                    Inactive
                                </span>
                                @endif
                            </td>


                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Edit Button --}}
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-[#056663]/20
                  bg-[#EAF5F4] px-3 py-2 text-xs font-semibold text-[#056663]
                  transition duration-200 hover:border-[#056663]
                  hover:bg-[#056663] hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.687a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 7.125 16.875 4.5M19.5 12.75V19.5A1.5 1.5 0 0 1 18 21H4.5A1.5 1.5 0 0 1 3 19.5V6A1.5 1.5 0 0 1 4.5 4.5h7.75" />
                                        </svg>
                                        Edit
                                    </a>

                                    {{-- Activate / Deactivate Button --}}
                                    <form method="POST"
                                        action="{{ route('admin.categories.toggle-status', $category->id) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg border px-3 py-2
                           text-xs font-semibold transition duration-200
                           {{ $category->status
                               ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white'
                               : 'border-green-200 bg-green-50 text-green-700 hover:bg-green-600 hover:text-white' }}">
                                            @if($category->status)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 9v3.75m9-3.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Deactivate
                                            @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9 12.75 2.25 2.25L15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Activate
                                            @endif
                                        </button>
                                    </form>

                                    {{-- Delete Button --}}
                                    <form method="POST"
                                        action="{{ route('admin.categories.destroy', $category->id) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg border
                           border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold
                           text-red-600 transition duration-200
                           hover:border-red-600 hover:bg-red-600 hover:text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 7h12m-10 0 .75 12h6.5L16 7M9 7V4.5h6V7m-5 4v4m4-4v4" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <p class="font-semibold text-gray-700">No categories found</p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Add a category or change your search filters.
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
            <div class="border-t border-gray-100 px-5 py-4">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection