<x-buyer-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Enquiry
            </h2>

            <p class="text-sm text-[#64748B] mt-0.5">
                Update your requirement details and keep your enquiry information accurate.
            </p>
        </div>

    </x-slot>


    {{-- =========================================================
         PAGE CONTENT
    ========================================================== --}}
    <div class="min-h-screen bg-[#F7FAFA]">

        <div class="px-4 sm:px-6 lg:px-8 py-7 lg:py-9">

            <div class="max-w-4xl mx-auto">


                {{-- =================================================
                     PAGE TITLE CARD
                ================================================== --}}
                <div
                    class="mb-6 bg-white rounded-2xl
                           border border-[#DDE9E8]
                           shadow-sm px-5 py-5 sm:px-7">

                    <div class="flex items-start gap-4">

                        <div
                            class="w-11 h-11 rounded-xl
                                   bg-[#E8F5F4]
                                   flex items-center justify-center
                                   text-[#056663]
                                   flex-shrink-0">

                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 14l-4 1 1-4 8.5-8.5z" />

                            </svg>

                        </div>

                        <div>

                            <h1
                                class="text-xl sm:text-2xl
                                       font-bold text-[#123B38]">

                                Edit Requirement

                            </h1>

                            <p
                                class="mt-1 text-sm text-[#64748B]">

                                Update your requirement details and keep your enquiry information accurate.

                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     EDIT FORM CARD
                ================================================== --}}
                <div
                    class="bg-white rounded-2xl
                           border border-[#DDE9E8]
                           shadow-sm overflow-hidden">


                    {{-- Card Header --}}
                    <div
                        class="px-5 py-5 sm:px-7
                               border-b border-[#E8EFEE]
                               bg-[#FBFDFD]">

                        <h2
                            class="text-lg font-bold text-[#123B38]">

                            Requirement Information

                        </h2>

                        <p
                            class="mt-1 text-sm text-[#64748B]">

                            Modify the details below and save your changes.

                        </p>

                    </div>


                    {{-- Form --}}
                    <form
                        action="{{ route('buyer.requirement.update', $requirement->id) }}"
                        method="POST"
                        class="p-5 sm:p-7">

                        @csrf
                        @method('PUT')


                        {{-- =========================================
                             CONTACT INFORMATION
                        ========================================== --}}
                        <div class="mb-7">

                            <div class="mb-4">

                                <h3
                                    class="text-sm font-semibold text-[#123B38]">

                                    Contact Information

                                </h3>

                                <div
                                    class="mt-2 h-px bg-[#E8EFEE]">
                                </div>

                            </div>


                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- Full Name --}}
                                <div>

                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-gray-700 mb-1.5">

                                        Full Name

                                    </label>

                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name', $requirement->name) }}"
                                        required
                                        class="w-full rounded-xl
                                               border border-[#D5E2E0]
                                               bg-white px-4 py-3
                                               text-sm text-gray-800
                                               outline-none transition
                                               focus:border-[#056663]
                                               focus:ring-2
                                               focus:ring-[#056663]/10">

                                    @error('name')
                                        <p class="mt-1.5 text-xs text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                {{-- Email --}}
                                <div>

                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-gray-700 mb-1.5">

                                        Email

                                    </label>

                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email', $requirement->email) }}"
                                        required
                                        class="w-full rounded-xl
                                               border border-[#D5E2E0]
                                               bg-white px-4 py-3
                                               text-sm text-gray-800
                                               outline-none transition
                                               focus:border-[#056663]
                                               focus:ring-2
                                               focus:ring-[#056663]/10">

                                    @error('email')
                                        <p class="mt-1.5 text-xs text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                {{-- Phone --}}
                                <div class="md:col-span-2">

                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-gray-700 mb-1.5">

                                        Phone

                                    </label>

                                    <input
                                        id="phone"
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone', $requirement->phone) }}"
                                        class="w-full rounded-xl
                                               border border-[#D5E2E0]
                                               bg-white px-4 py-3
                                               text-sm text-gray-800
                                               outline-none transition
                                               focus:border-[#056663]
                                               focus:ring-2
                                               focus:ring-[#056663]/10">

                                    @error('phone')
                                        <p class="mt-1.5 text-xs text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =========================================
                             REQUIREMENT DETAILS
                        ========================================== --}}
                        <div class="mb-7">

                            <div class="mb-4">

                                <h3
                                    class="text-sm font-semibold text-[#123B38]">

                                    Requirement Details

                                </h3>

                                <div
                                    class="mt-2 h-px bg-[#E8EFEE]">
                                </div>

                            </div>


                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                                {{-- Quantity --}}
                                <div>

                                    <label
                                        for="quantity"
                                        class="block text-sm font-medium text-gray-700 mb-1.5">

                                        Quantity

                                    </label>

                                    <input
                                        id="quantity"
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        value="{{ old('quantity', $requirement->quantity) }}"
                                        class="w-full rounded-xl
                                               border border-[#D5E2E0]
                                               bg-white px-4 py-3
                                               text-sm text-gray-800
                                               outline-none transition
                                               focus:border-[#056663]
                                               focus:ring-2
                                               focus:ring-[#056663]/10">

                                    @error('quantity')
                                        <p class="mt-1.5 text-xs text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                {{-- Unit --}}
                                <div>

                                    <label
                                        for="unit"
                                        class="block text-sm font-medium text-gray-700 mb-1.5">

                                        Unit

                                    </label>

                                    <input
                                        id="unit"
                                        type="text"
                                        name="unit"
                                        value="{{ old('unit', $requirement->unit) }}"
                                        class="w-full rounded-xl
                                               border border-[#D5E2E0]
                                               bg-white px-4 py-3
                                               text-sm text-gray-800
                                               outline-none transition
                                               focus:border-[#056663]
                                               focus:ring-2
                                               focus:ring-[#056663]/10">

                                    @error('unit')
                                        <p class="mt-1.5 text-xs text-red-600">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =========================================
                             MESSAGE
                        ========================================== --}}
                        <div class="mb-7">

                            <label
                                for="message"
                                class="block text-sm font-medium text-gray-700 mb-1.5">

                                Requirement Details

                            </label>

                            <textarea
                                id="message"
                                name="message"
                                rows="6"
                                placeholder="Describe your requirement..."
                                class="w-full rounded-xl
                                       border border-[#D5E2E0]
                                       bg-white px-4 py-3
                                       text-sm text-gray-800
                                       placeholder:text-[#94A3B8]
                                       outline-none resize-none
                                       transition
                                       focus:border-[#056663]
                                       focus:ring-2
                                       focus:ring-[#056663]/10">{{ old('message', $requirement->message) }}</textarea>

                            @error('message')
                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- =========================================
                             ACTIONS
                        ========================================== --}}
                        <div
                            class="flex flex-col-reverse
                                   sm:flex-row sm:items-center
                                   sm:justify-end gap-3
                                   pt-5 border-t border-[#E8EFEE]">

                            <a
                                href="{{ route('buyer.requirement.index') }}"
                                class="inline-flex items-center justify-center
                                       rounded-xl
                                       border border-[#D3E3E0]
                                       bg-white
                                       px-5 py-2.5
                                       text-sm font-semibold
                                       text-[#123B38]
                                       hover:bg-[#F5F9F8]
                                       transition">

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="inline-flex items-center justify-center
                                       gap-2 rounded-xl
                                       bg-[#056663]
                                       px-6 py-2.5
                                       text-sm font-semibold
                                       text-white
                                       shadow-sm
                                       hover:bg-[#045450]
                                       hover:shadow
                                       transition">

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7" />

                                </svg>

                                Update Requirement

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-buyer-layout>