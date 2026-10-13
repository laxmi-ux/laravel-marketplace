<x-guest-layout>

    <div class="min-h-[70vh] flex items-center justify-center mt-20 px-4 py-8">

        <div class="w-full max-w-md">

            <!-- Card -->
            <div class="bg-white rounded-2xl border border-[#DDE9E8] shadow-sm overflow-hidden">

                <!-- Header -->
                <div class="px-6 sm:px-8 pt-8 pb-6 text-center">

                    <!-- Icon -->
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center
                                rounded-full bg-[#E8F5F4]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8 text-[#056663]"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5.5 20a6.5 6.5 0 0113 0"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 8v6m0 0l-2-2m2 2l2-2"
                            />
                        </svg>

                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl font-bold text-gray-900">
                        Forgot Password?
                    </h1>

                    <!-- Description -->
                    <p class="mt-3 text-sm leading-6 text-gray-600">
                        Don't worry, it happens to everyone. Enter your
                        registered email address and we'll send you a
                        password reset link.
                    </p>

                </div>

                <!-- Form -->
                <div class="px-6 sm:px-8 pb-8">

                    <!-- Session Status -->
                    <x-auth-session-status
                        class="mb-5"
                        :status="session('status')"
                    />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email -->
                        <div>
                            <x-input-label
                                for="email"
                                :value="__('Email Address')"
                                class="text-sm font-medium text-gray-700"
                            />

                            <div class="relative mt-2">

                                <!-- Email Icon -->
                                <div class="pointer-events-none absolute inset-y-0 left-0
                                            flex items-center pl-3.5">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5v9a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 16.5v-9z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4 7l8 6 8-6"
                                        />
                                    </svg>

                                </div>

                                <x-text-input
                                    id="email"
                                    class="block w-full pl-11 py-3 rounded-lg
                                           border-gray-300
                                           focus:border-[#056663]
                                           focus:ring-[#056663]"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    placeholder="Enter your email address"
                                />

                            </div>

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2"
                            />

                        </div>

                        <!-- Submit -->
                        <div class="mt-6">

                            <button
                                type="submit"
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
                                       focus:ring-offset-2"
                            >

                                <!-- Mail Icon -->
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 7.5A2.5 2.5 0 015.5 5h13A2.5 2.5 0 0121 7.5v9a2.5 2.5 0 01-2.5 2.5h-13A2.5 2.5 0 013 16.5v-9z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 7l8 6 8-6"
                                    />
                                </svg>

                                <span>
                                    Send Password Reset Link
                                </span>

                            </button>

                        </div>

                        <!-- Divider -->
                        <div class="relative my-6">

                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>

                            <div class="relative flex justify-center">
                                <span class="bg-white px-3 text-xs text-gray-500">
                                    Remember your password?
                                </span>
                            </div>

                        </div>

                        <!-- Back to Login -->
                        <div class="text-center">

                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center gap-2
                                       text-sm font-semibold
                                       text-[#056663]
                                       hover:text-[#044f4d]
                                       transition"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h20"
                                    />
                                </svg>

                                Back to Login

                            </a>

                        </div>

                    </form>

                </div>

            </div>

            <!-- Security Note -->
            <div class="mt-5 flex items-center justify-center gap-2 text-xs text-gray-500">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 text-[#056663]"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.5 12l1.7 1.7 3.5-3.5"
                    />
                </svg>

                <span>
                    Your account information is securely protected.
                </span>

            </div>

        </div>

    </div>

</x-guest-layout>
