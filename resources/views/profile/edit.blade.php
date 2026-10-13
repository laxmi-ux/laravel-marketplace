<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-2xl font-bold text-[#123B38]">
                {{ $user->role === 'seller' ? 'Seller Profile' : 'Buyer Profile' }}
            </h2>

            <p class="mt-1 text-sm text-[#4F6663]">
                {{ $user->role === 'seller'
                    ? 'Manage your seller and business profile'
                    : 'Manage your buyer profile and personal information'
                }}
            </p>
        </div>

    </x-slot>


    <div class="min-h-screen bg-[#F5F9F8] py-8">

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">


            {{-- PROFILE HEADER --}}
            <div class="mb-6 overflow-hidden rounded-2xl border border-[#D3E3E0] bg-white shadow-sm">

                <div class="bg-[#004C47] px-6 py-7 sm:px-8">

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-4">

                            {{-- Avatar --}}
                            <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-[#00665F] text-2xl font-bold text-white ring-4 ring-white/10">

                                {{ strtoupper(substr($user->name, 0, 1)) }}

                            </div>


                            <div>

                                <h3 class="text-xl font-bold text-white">
                                    {{ $user->name }}
                                </h3>

                                <p class="mt-1 text-sm text-[#B7D7D3]">
                                    {{ $user->email }}
                                </p>


                                {{-- ROLE --}}
                                <div class="mt-3">

                                    @if($user->role === 'seller')

                                        <span class="inline-flex rounded-full bg-[#8FD3CC] px-3 py-1 text-xs font-semibold text-[#004C47]">
                                            Seller Profile
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-[#8FD3CC] px-3 py-1 text-xs font-semibold text-[#004C47]">
                                            Buyer Profile
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- ACCOUNT STATUS --}}
                        <div class="rounded-xl border border-white/10 bg-white/10 px-5 py-4">

                            <p class="text-xs uppercase tracking-wide text-[#B7D7D3]">
                                Account Status
                            </p>

                            <div class="mt-2 flex items-center gap-2">

                                <span class="h-2.5 w-2.5 rounded-full bg-[#8FD3CC]"></span>

                                <span class="text-sm font-semibold text-white">
                                    Active
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ========================================= --}}
            {{-- SELLER PROFILE --}}
            {{-- ========================================= --}}

            @if($user->role === 'seller')

                <div class="mb-6 rounded-2xl border border-[#D3E3E0] bg-white p-6 shadow-sm sm:p-8">

                    <div class="mb-6">

                        <h3 class="text-xl font-bold text-[#123B38]">
                            Seller Profile
                        </h3>

                        <p class="mt-1 text-sm text-[#4F6663]">
                            Manage your business and seller information.
                        </p>

                    </div>


                    @include('profile.partials.update-profile-information-form')

                </div>

            @endif



            {{-- ========================================= --}}
            {{-- BUYER PROFILE --}}
            {{-- ========================================= --}}

            @if($user->role === 'buyer')

                <div class="mb-6 rounded-2xl border border-[#D3E3E0] bg-white p-6 shadow-sm sm:p-8">

                    <div class="mb-6">

                        <h3 class="text-xl font-bold text-[#123B38]">
                            Buyer Profile
                        </h3>

                        <p class="mt-1 text-sm text-[#4F6663]">
                            Manage your personal information and contact details.
                        </p>

                    </div>


                    @include('profile.partials.update-profile-information-form')

                </div>

            @endif



            {{-- PASSWORD --}}
            <div class="mb-6 rounded-2xl border border-[#D3E3E0] bg-white p-6 shadow-sm sm:p-8">

                @include('profile.partials.update-password-form')

            </div>



            {{-- DELETE ACCOUNT --}}
            <div class="rounded-2xl border border-red-100 bg-white p-6 shadow-sm sm:p-8">

                @include('profile.partials.delete-user-form')

            </div>


        </div>

    </div>

</x-app-layout>