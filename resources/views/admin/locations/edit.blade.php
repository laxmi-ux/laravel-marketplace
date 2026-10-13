@extends('admin.layouts.app')

@section('title', 'Edit Location')

@section('content')

<div class="p-6">

    <div class="mb-6">

        <a href="{{ route('admin.locations.index') }}"
           class="text-sm text-[#056663] hover:underline">
            ← Back to Locations
        </a>

        <h1 class="text-2xl font-bold text-[#056663] mt-3">
            Edit Location
        </h1>

        <p class="text-gray-500 mt-1">
            Update location information.
        </p>

    </div>


    @if($errors->any())

        <div class="mb-6 rounded-lg bg-red-50 border border-red-200
                    px-4 py-3 text-red-700">

            <ul class="list-disc pl-5 space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <form
            action="{{ route('admin.locations.update', $location) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-6"
        >

            @csrf
            @method('PUT')


            {{-- LOCATION NAME --}}

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Location Name
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $location->name) }}"
                    required
                    class="w-full rounded-lg border border-gray-300
                           px-4 py-3 focus:border-[#056663]
                           focus:ring-[#056663]"
                >

            </div>


            {{-- STATE --}}

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    State
                </label>

                <input
                    type="text"
                    name="state"
                    value="{{ old('state', $location->state) }}"
                    placeholder="e.g. Maharashtra"
                    class="w-full rounded-lg border border-gray-300
                           px-4 py-3 focus:border-[#056663]
                           focus:ring-[#056663]"
                >

            </div>


            {{-- DESCRIPTION --}}

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="5"
                    class="w-full rounded-lg border border-gray-300
                           px-4 py-3 focus:border-[#056663]
                           focus:ring-[#056663]"
                >{{ old('description', $location->description) }}</textarea>

            </div>


            {{-- CURRENT IMAGE --}}

            @if($location->image)

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Current Image
                    </label>

                    <img
                        src="{{ route('uploads.file', ['path' => 'locations/' . $location->image]) }}"
                        alt="{{ $location->name }}"
                        class="w-40 h-28 object-cover rounded-lg border"
                    >

                </div>

            @endif


            {{-- NEW IMAGE --}}

            <div>

                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Change Image
                </label>

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="w-full rounded-lg border border-gray-300
                           px-4 py-3 bg-white"
                >

                <p class="text-xs text-gray-500 mt-2">
                    Leave empty to keep the current image.
                </p>

            </div>


            {{-- STATUS --}}

            <div class="flex items-center gap-3">

                <input
                    type="checkbox"
                    name="status"
                    value="1"
                    id="status"
                    {{ old('status', $location->status) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300
                           text-[#056663] focus:ring-[#056663]"
                >

                <label for="status"
                       class="text-sm font-semibold text-gray-700">
                    Active Location
                </label>

            </div>


            {{-- BUTTONS --}}

            <div class="flex items-center gap-3 pt-4 border-t">

                <button
                    type="submit"
                    class="px-6 py-3 rounded-lg bg-[#056663]
                           text-white font-semibold
                           hover:bg-[#045451]"
                >
                    Update Location
                </button>

                <a
                    href="{{ route('admin.locations.index') }}"
                    class="px-6 py-3 rounded-lg bg-gray-100
                           text-gray-700 font-semibold
                           hover:bg-gray-200"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection