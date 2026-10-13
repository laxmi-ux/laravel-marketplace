@extends('admin.layouts.app')

@section('title', 'Locations')

@section('content')

<div class="p-6">

    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-[#056663]">
                Locations
            </h1>

            <p class="text-gray-500 mt-1">
                Manage marketplace locations.
            </p>
        </div>

        <a href="{{ route('admin.locations.create') }}"
           class="px-5 py-2.5 rounded-lg bg-[#056663] text-white font-semibold hover:bg-[#045451]">
            + Add Location
        </a>

    </div>


    @if(session('success'))

        <div class="mb-5 rounded-lg bg-green-50 border border-green-200
                    px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-[#F7FAFA] border-b">

                    <tr>

                        <th class="px-5 py-4 text-left text-sm font-semibold">
                            Image
                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold">
                            Location
                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold">
                            State
                        </th>

                        <th class="px-5 py-4 text-left text-sm font-semibold">
                            Status
                        </th>

                        <th class="px-5 py-4 text-right text-sm font-semibold">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($locations as $location)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4">

                                @if($location->image)

                                    <img
                                        src="{{ route('uploads.file', ['path' => 'locations/' . $location->image]) }}"
                                        class="w-14 h-14 rounded-lg object-cover border"
                                        alt="{{ $location->name }}"
                                    >

                                @else

                                    <div class="w-14 h-14 rounded-lg bg-[#E8F5F4]
                                                flex items-center justify-center
                                                text-[#056663] font-bold">
                                        {{ strtoupper(substr($location->name, 0, 1)) }}
                                    </div>

                                @endif

                            </td>

                            <td class="px-5 py-4">

                                <div class="font-semibold text-gray-800">
                                    {{ $location->name }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    /{{ $location->slug }}
                                </div>

                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $location->state ?: '—' }}
                            </td>

                            <td class="px-5 py-4">

                                @if($location->status)

                                    <span class="px-3 py-1 rounded-full text-xs
                                                 font-semibold bg-green-100 text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="px-3 py-1 rounded-full text-xs
                                                 font-semibold bg-red-100 text-red-700">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('admin.locations.edit', $location) }}"
                                       class="px-3 py-2 rounded-lg bg-blue-50 text-blue-600
                                              text-sm font-medium hover:bg-blue-100">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.locations.toggle-status', $location) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg bg-yellow-50
                                                       text-yellow-700 text-sm font-medium
                                                       hover:bg-yellow-100">
                                            {{ $location->status ? 'Disable' : 'Enable' }}
                                        </button>

                                    </form>

                                    <form
                                        action="{{ route('admin.locations.destroy', $location) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this location?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg bg-red-50
                                                       text-red-600 text-sm font-medium
                                                       hover:bg-red-100">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="px-5 py-12 text-center text-gray-500">

                                No locations found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($locations->hasPages())

            <div class="px-5 py-4 border-t">
                {{ $locations->links() }}
            </div>

        @endif

    </div>

</div>

@endsection