
@extends('admin.layouts.app')

@section('title', 'Add Subcategory')

@section('content')
<div class="min-h-screen bg-[#F7FAFA] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">

        {{-- Header --}}
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('admin.subcategories.index') }}"
               class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#DDE9E8] bg-white text-[#056663] hover:bg-[#EAF5F4]">
                &larr;
            </a>

            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Subcategory</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Create a subcategory under a marketplace category.
                </p>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <div class="overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">
            <div class="border-b border-[#DDE9E8] bg-[#EAF5F4] px-6 py-5">
                <h2 class="font-semibold text-[#056663]">Subcategory Information</h2>
                <p class="mt-1 text-xs text-gray-500">Fields marked with * are required.</p>
            </div>

            <form method="POST"
                  action="{{ route('admin.subcategories.store') }}"
                  class="space-y-6 p-6">
                @csrf

                {{-- Parent Category --}}
                <div>
                    <label for="category_id" class="mb-2 block text-sm font-semibold text-gray-700">
                        Parent Category <span class="text-red-500">*</span>
                    </label>

                    <select id="category_id"
                            name="category_id"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">
                        <option value="">Select a category</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (string) old('category_id', request('category_id')) === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">
                        Subcategory Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           maxlength="255"
                           placeholder="e.g. Cement"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="mb-2 block text-sm font-semibold text-gray-700">
                        Slug
                    </label>

                    <input type="text"
                           id="slug"
                           name="slug"
                           value="{{ old('slug') }}"
                           maxlength="255"
                           placeholder="e.g. cement"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">

                    <p class="mt-1 text-xs text-gray-500">
                        Optional. Leave blank if your controller generates it automatically.
                    </p>

                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image URL --}}
                <div>
                    <label for="image" class="mb-2 block text-sm font-semibold text-gray-700">
                        Image URL
                    </label>

                    <input type="url"
                           id="image"
                           name="image"
                           value="{{ old('image') }}"
                           maxlength="2048"
                           placeholder="https://example.com/image.jpg"
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
                              rows="4"
                              placeholder="Describe this subcategory..."
                              class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select id="status"
                            name="status"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-[#056663] focus:ring-2 focus:ring-[#056663]/20">
                        <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.subcategories.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-[#056663] px-6 py-3 text-sm font-semibold text-white hover:bg-[#045451]">
                        Create Subcategory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection