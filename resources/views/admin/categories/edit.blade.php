@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="min-h-screen bg-[#F7FAFA] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">

        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.categories.index') }}"
               class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#DDE9E8] bg-white text-[#056663] transition hover:bg-[#EAF5F4]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Update the category details and visibility.
                </p>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="mb-2 font-semibold">Please correct the following:</p>
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

            <div class="border-b border-[#DDE9E8] bg-[#EAF5F4] px-6 py-5">
                <h2 class="font-semibold text-[#056663]">Category Information</h2>
                <p class="mt-1 text-xs text-gray-500">
                    Category ID: {{ $category->id }}
                </p>
            </div>

            <form method="POST"
                  action="{{ route('admin.categories.update', $category->id) }}"
                  class="space-y-6 p-6">
                @csrf
                @method('PUT')

                {{-- Category Name --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                        Category Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $category->name) }}"
                           required
                           maxlength="255"
                           placeholder="Enter category name"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slugs" class="mb-2 block text-sm font-semibold text-gray-700">
                        Category Slug
                    </label>

                    <input type="text"
                           id="slugs"
                           name="slugs"
                           value="{{ old('slugs', $category->slugs ?? $category->slug) }}"
                           placeholder="e.g. building-materials"
                           maxlength="255"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                    <p class="mt-1 text-xs text-gray-500">
                        Used in category URLs. Leave blank to generate it from the category name.
                    </p>

                    @error('slugs')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image URL --}}
                <div>
                    <label for="image" class="mb-2 block text-sm font-semibold text-gray-700">
                        Category Image URL
                    </label>

                    <input type="text"
                           id="image"
                           name="image"
                           value="{{ old('image', $category->image) }}"
                           maxlength="2048"
                           placeholder="https://example.com/category-image.jpg"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if($category->image)
                        <div class="mt-3">
                            <p class="mb-2 text-xs font-medium text-gray-500">Current image</p>
                            <img src="{{ $category->image }}"
                                 alt="{{ $category->name }}"
                                 class="h-28 w-40 rounded-xl border border-gray-200 object-cover"
                                 onerror="this.style.display='none'">
                        </div>
                    @endif
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Describe this category..."
                              class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">{{ old('description', $category->description) }}</textarea>

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
                        <option value="1"
                            {{ (string) old('status', (int) $category->status) === '1' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0"
                            {{ (string) old('status', (int) $category->status) === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    <p class="mt-1 text-xs text-gray-500">
                        Deactivating a category can prevent it from appearing in active category selections.
                    </p>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.categories.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#056663] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#045451] focus:outline-none focus:ring-2 focus:ring-[#056663] focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection