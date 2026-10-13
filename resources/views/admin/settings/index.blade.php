
@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
<div class="mx-auto max-w-5xl p-4 sm:p-6">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#056663]">
            General Settings
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Manage your marketplace information and website logo.
        </p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="mb-2 font-semibold">
                Please correct the following errors:
            </p>

            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Settings Form --}}
    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
        enctype="multipart/form-data"
        class="space-y-6 rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm sm:p-7"
    >
        @csrf
        @method('PUT')

        {{-- Marketplace Name --}}
        <div>
            <label for="site_name"
                   class="mb-2 block text-sm font-semibold text-gray-700">
                Marketplace Name *
            </label>

            <input
                type="text"
                id="site_name"
                name="site_name"
                value="{{ old('site_name', $settings['site_name'] ?? 'Marketplace') }}"
                required
                maxlength="150"
                class="w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
            >
        </div>

        {{-- Contact Email --}}
        <div>
            <label for="contact_email"
                   class="mb-2 block text-sm font-semibold text-gray-700">
                Contact Email
            </label>

            <input
                type="email"
                id="contact_email"
                name="contact_email"
                value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                maxlength="150"
                class="w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
            >
        </div>

        {{-- Contact Phone --}}
        <div>
            <label for="contact_phone"
                   class="mb-2 block text-sm font-semibold text-gray-700">
                Contact Phone
            </label>

            <input
                type="text"
                id="contact_phone"
                name="contact_phone"
                value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                maxlength="30"
                class="w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
            >
        </div>

        {{-- Business Address --}}
        <div>
            <label for="address"
                   class="mb-2 block text-sm font-semibold text-gray-700">
                Business Address
            </label>

            <textarea
                id="address"
                name="address"
                rows="4"
                maxlength="1000"
                class="w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]"
            >{{ old('address', $settings['address'] ?? '') }}</textarea>
        </div>

        {{-- Marketplace Logo --}}
        <div class="border-t border-[#DDE9E8] pt-6">
            <h2 class="text-lg font-semibold text-[#056663]">
                Marketplace Logo
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Upload a PNG, JPG, JPEG, or WebP image. Maximum size: 2 MB.
                Leave this field empty to keep your current logo.
            </p>

            @php
                $currentLogo = $settings['logo_path'] ?? null;

                $logoUrl = $currentLogo
                    ? asset('storage/' . $currentLogo)
                    : asset('uploads/logo.png');
            @endphp

            {{-- Current Logo Preview --}}
            <div class="mt-4">
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Current Logo
                </label>

                <div class="flex min-h-28 items-center rounded-lg border border-[#DDE9E8] bg-[#F7FAFA] p-4">
                    <img
                        src="{{ $logoUrl }}"
                        alt="{{ $settings['site_name'] ?? 'Marketplace' }} logo"
                        class="max-h-24 max-w-full object-contain"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                    >

                    <p class="hidden text-sm text-gray-500">
                        Logo preview unavailable. Please check the image path.
                    </p>
                </div>
            </div>

            {{-- Upload New Logo --}}
            <div class="mt-4">
                <label
                    for="logo"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Upload New Logo
                </label>

                <input
                    type="file"
                    name="logo"
                    id="logo"
                    accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp"
                    class="block w-full rounded-lg border border-gray-300 bg-white p-3 text-sm
                           file:mr-4 file:rounded-md file:border-0
                           file:bg-[#E8F5F4] file:px-4 file:py-2
                           file:font-semibold file:text-[#056663]
                           hover:file:bg-[#DDE9E8]"
                >

                @error('logo')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end border-t border-gray-100 pt-5">
            <button
                type="submit"
                class="rounded-lg bg-[#056663] px-6 py-3 text-sm font-semibold text-white
                       transition hover:bg-[#045450]"
            >
                Save Settings
            </button>
        </div>

    </form>
</div>
@endsection
