@extends('admin.layouts.app')

@section('title', 'Edit Listing')

@section('page-title', 'Edit Listing')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Listing
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Update listing information and status.
            </p>
        </div>

        <a
            href="{{ route('admin.listings.show', $listing) }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-[#056663] hover:text-[#056663]"
        >
            <span class="material-symbols-outlined text-[19px]">
                arrow_back
            </span>
            Back to Listing
        </a>
    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <div class="flex gap-3">
                <span class="material-symbols-outlined text-red-500">
                    error
                </span>

                <div>
                    <h3 class="font-semibold text-red-700">
                        Please fix the following errors:
                    </h3>

                    <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    {{-- Edit Form --}}
    <form
        action="{{ route('admin.listings.update', $listing) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- Basic Information --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-bold text-slate-800">
                    Basic Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Update the main information of this listing.
                </p>
            </div>


            <div class="grid gap-6 p-6 md:grid-cols-2">

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Listing Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $listing->title) }}"
                        required
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                        placeholder="Enter listing title"
                    >
                </div>


                {{-- Category --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Category
                    </label>

                    <select
                        name="category_id"
                        disabled
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none"
                    >
                        <option value="">
                            Select Category
                        </option>

                        <option value="1" {{ $listing->category_id == 1 ? 'selected' : '' }}>
                            Properties
                        </option>

                        <option value="2" {{ $listing->category_id == 2 ? 'selected' : '' }}>
                            Land
                        </option>

                        <option value="3" {{ $listing->category_id == 3 ? 'selected' : '' }}>
                            Products
                        </option>

                        <option value="4" {{ $listing->category_id == 4 ? 'selected' : '' }}>
                            Services
                        </option>

                        <option value="5" {{ $listing->category_id == 5 ? 'selected' : '' }}>
                            Projects
                        </option>
                    </select>

                    <p class="mt-1 text-xs text-slate-400">
                        Category is managed from the listing type/details.
                    </p>
                </div>


                {{-- Subcategory --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Subcategory
                    </label>

                    <input
                        type="text"
                        value="{{ $listing->subcategoryRelation?->name ?? '—' }}"
                        disabled
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none"
                    >
                </div>


                {{-- Listing Type --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Listing Type
                    </label>

                    <input
                        type="text"
                        value="{{ $listing->listing_type ?? '—' }}"
                        disabled
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none"
                    >
                </div>


                {{-- Status --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Status
                    </label>

                    <select
                        name="status"
                        required
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                    >
                        <option value="draft"
                            {{ old('status', $listing->status) === 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="pending"
                            {{ old('status', $listing->status) === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="approved"
                            {{ old('status', $listing->status) === 'approved' ? 'selected' : '' }}>
                            Approved
                        </option>

                        <option value="rejected"
                            {{ old('status', $listing->status) === 'rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>
                    </select>
                </div>


                {{-- Price --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="price"
                        value="{{ old('price', $listing->price) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                        placeholder="Enter price"
                    >
                </div>


                {{-- Price Unit --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Price Unit
                    </label>

                    <input
                        type="text"
                        name="price_unit"
                        value="{{ old('price_unit', $listing->price_unit) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                        placeholder="e.g. per sq.ft, per piece"
                    >
                </div>


                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="6"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                        placeholder="Enter listing description"
                    >{{ old('description', $listing->description) }}</textarea>
                </div>

            </div>
        </div>


        {{-- Location --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-bold text-slate-800">
                    Location
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Update the listing location.
                </p>
            </div>


            <div class="grid gap-6 p-6 md:grid-cols-3">

                {{-- City --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city', $listing->city) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                        placeholder="City"
                    >
                </div>


                {{-- State --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        State
                    </label>

                    <input
                        type="text"
                        name="state"
                        value="{{ old('state', $listing->state) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                        placeholder="State"
                    >
                </div>


                {{-- Pincode --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Pincode
                    </label>

                    <input
                        type="text"
                        name="pincode"
                        value="{{ old('pincode', $listing->pincode) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#056663] focus:ring-1 focus:ring-[#056663]"
                        placeholder="Pincode"
                    >
                </div>

            </div>
        </div>


        {{-- Current Image --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-bold text-slate-800">
                    Listing Image
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Upload a new image if you want to replace the current image.
                </p>
            </div>


            <div class="p-6">

                @if($listing->image)

                    <div class="mb-5">
                        <p class="mb-2 text-sm font-semibold text-slate-700">
                            Current Image
                        </p>

                        <img
                            src="{{ url('/uploads/' . $listing->image) }}"
                            alt="{{ $listing->title }}"
                            class="h-40 w-56 rounded-xl border border-slate-200 object-cover"
                        >
                    </div>

                @endif


                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Replace Image
                </label>

                <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm"
                >

                <p class="mt-2 text-xs text-slate-400">
                    Leave empty to keep the current image.
                </p>

            </div>
        </div>


        {{-- Action Buttons --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('admin.listings.show', $listing) }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#056663] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#045450]"
            >
                <span class="material-symbols-outlined text-[19px]">
                    save
                </span>

                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection