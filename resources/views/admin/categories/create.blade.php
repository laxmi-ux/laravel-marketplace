@extends('admin.layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="min-h-screen bg-[#F7FAFA] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">

        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.categories.index') }}"
               class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#DDE9E8] bg-white text-[#056663] hover:bg-[#EAF5F4]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Category</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Create a category and add its subcategories.
                </p>
            </div>
        </div>

        {{-- Success / Error Messages --}}
        @if(session('success'))
            <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="mb-2 font-semibold">Please correct these errors:</p>
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Category Form --}}
        <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

            <div class="border-b border-[#DDE9E8] bg-[#EAF5F4] px-6 py-5">
                <h2 class="font-semibold text-[#056663]">Category Information</h2>
                <p class="mt-1 text-xs text-gray-500">
                    Fields marked with * are required.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('admin.categories.store') }}"
                  class="space-y-6 p-6">

                @csrf

                {{-- Category Name --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                        Category Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           maxlength="255"
                           placeholder="e.g. Building Materials"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category Slug --}}
                <div>
                    <label for="slugs" class="mb-2 block text-sm font-semibold text-gray-700">
                        Category Slug
                    </label>

                    <input type="text"
                           id="slugs"
                           name="slugs"
                           value="{{ old('slugs') }}"
                           maxlength="255"
                           placeholder="e.g. building-materials"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                    <p class="mt-1 text-xs text-gray-500">
                        Leave blank to generate the slug from the category name.
                    </p>

                    @error('slugs')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category Image --}}
                <div>
                    <label for="image" class="mb-2 block text-sm font-semibold text-gray-700">
                        Category Image URL
                    </label>

                    <input type="url"
                           id="image"
                           name="image"
                           value="{{ old('image') }}"
                           maxlength="2048"
                           placeholder="https://example.com/category-image.jpg"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="3"
                              placeholder="Describe this category..."
                              class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">
                        Category Status <span class="text-red-500">*</span>
                    </label>

                    <select id="status"
                            name="status"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                        <option value="1" {{ (string) old('status', '1') === '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ (string) old('status', '1') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Subcategories --}}
                <div class="rounded-2xl border border-[#DDE9E8] p-5">

                    <div class="mb-4 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                        <div>
                            <h3 class="font-semibold text-gray-900">Subcategories</h3>
                            <p class="mt-1 text-xs text-gray-500">
                                Add one or more subcategories for this category.
                            </p>
                        </div>

                        <button type="button"
                                id="add-subcategory"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#EAF5F4] px-4 py-2 text-sm font-semibold text-[#056663] hover:bg-[#DDE9E8]">
                            <span class="text-lg">+</span>
                            Add Subcategory
                        </button>
                    </div>

                    <div id="subcategory-list" class="space-y-3">

                        @php
                            $oldSubcategories = old('subcategories', ['']);

                            if (!is_array($oldSubcategories) || count($oldSubcategories) === 0) {
                                $oldSubcategories = [''];
                            }
                        @endphp

                        @foreach($oldSubcategories as $subcategory)
                            <div class="subcategory-row flex items-center gap-2">
                                <input type="text"
                                       name="subcategories[]"
                                       value="{{ is_string($subcategory) ? $subcategory : '' }}"
                                       maxlength="255"
                                       placeholder="e.g. Cement"
                                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                                <button type="button"
                                        class="remove-subcategory shrink-0 rounded-lg border border-red-200 px-3 py-3 text-sm font-medium text-red-600 hover:bg-red-50"
                                        aria-label="Remove subcategory">
                                    Remove
                                </button>
                            </div>
                        @endforeach

                    </div>

                    <p class="mt-3 text-xs text-gray-500">
                        Subcategories are optional. Leave a field blank to skip it.
                    </p>

                    @error('subcategories')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @foreach($errors->get('subcategories.*') as $messages)
                        @foreach($messages as $message)
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @endforeach
                    @endforeach
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">

                    <a href="{{ route('admin.categories.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#056663] px-6 py-3 text-sm font-semibold text-white hover:bg-[#045451]">
                        Create Category
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add / Remove Subcategory Fields --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('subcategory-list');
    const addButton = document.getElementById('add-subcategory');

    if (!list || !addButton) {
        return;
    }

    addButton.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'subcategory-row flex items-center gap-2';

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'subcategories[]';
        input.maxLength = 255;
        input.placeholder = 'Enter subcategory name';
        input.className =
            'w-full rounded-xl border border-gray-300 px-4 py-3 text-sm';

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className =
            'remove-subcategory rounded-lg border border-red-200 px-3 py-3 text-sm text-red-600';
        removeButton.textContent = 'Remove';

        row.appendChild(input);
        row.appendChild(removeButton);
        list.appendChild(row);

        input.focus();
    });

    list.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-subcategory');

        if (!button) return;

        const row = button.closest('.subcategory-row');
        const rows = list.querySelectorAll('.subcategory-row');

        if (rows.length > 1) {
            row.remove();
        } else {
            row.querySelector('input').value = '';
        }
    });
});
</script>
@endsection
