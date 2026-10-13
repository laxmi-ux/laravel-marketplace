<x-guest-layout>

    <div class="w-full max-w-md  mt-20 mx-auto">

        {{-- Icon --}}
        <div class="flex justify-center mb-5">
            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#E8F5F4]">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-7 w-7 text-[#056663]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-1.5 0h12a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H6a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5Z" />
                </svg>
            </div>
        </div>

        {{-- Heading --}}
        <div class="text-center mb-7">
            <h1 class="text-2xl font-bold text-gray-900">
                Reset Password
            </h1>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                Enter your new password below to secure your account.
            </p>
        </div>

        {{-- Card --}}
        <div class="rounded-2xl border border-[#DDE9E8] bg-white p-6 shadow-sm sm:p-8">

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- Password Reset Token --}}
                <input type="hidden"
                    name="token"
                    value="{{ $request->route('token') }}">

                {{-- Email Address --}}
                <div>
                    <x-input-label
                        for="email"
                        :value="__('Email Address')"
                        class="text-sm font-medium text-gray-700" />

                    <x-text-input
                        id="email"
                        class="mt-2 block w-full rounded-lg border-gray-300
                               focus:border-[#056663]
                               focus:ring-[#056663]"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        required
                        autofocus
                        autocomplete="username" />

                    <x-input-error
                        :messages="$errors->get('email')"
                        class="mt-2" />
                </div>

                {{-- New Password --}}
                <div class="mt-5">
                    <x-input-label
                        for="password"
                        :value="__('New Password')"
                        class="text-sm font-medium text-gray-700" />

                    <x-text-input
                        id="password"
                        class="mt-2 block w-full rounded-lg border-gray-300
                               focus:border-[#056663]
                               focus:ring-[#056663]"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password" />

                    <x-input-error
                        :messages="$errors->get('password')"
                        class="mt-2" />
                </div>

                {{-- Confirm Password --}}
                <div class="mt-5">
                    <x-input-label
                        for="password_confirmation"
                        :value="__('Confirm Password')"
                        class="text-sm font-medium text-gray-700" />

                    <x-text-input
                        id="password_confirmation"
                        class="mt-2 block w-full rounded-lg border-gray-300
                               focus:border-[#056663]
                               focus:ring-[#056663]"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password" />

                    <x-input-error
                        :messages="$errors->get('password_confirmation')"
                        class="mt-2" />
                </div>

                {{-- Reset Button --}}
                <div class="mt-7">
                    <x-primary-button
                        class="w-full inline-flex items-center justify-center
                                       gap-2 rounded-lg
                                       bg-[#056663]
                                       px-5 py-3
                                       text-sm font-semibold text-white
                                       shadow-sm
                                       transition
                                       duration-200
                                       hover:bg-[#045452]
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-[#056663]
                                       focus:ring-offset-2">
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>

            </form>
        </div>

        {{-- Back to Login --}}
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="text-sm font-medium text-[#056663] hover:text-[#045550] hover:underline">
                ← Back to Login
            </a>
        </div>

    </div>

</x-guest-layout>