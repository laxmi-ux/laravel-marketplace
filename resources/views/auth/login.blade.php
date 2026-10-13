<x-guest-layout>

    <div class="min-h-screen bg-[#F5F9F8] flex flex-col lg:flex-row">

            {{-- =========================================================
            LEFT BRANDING PANEL
        ========================================================== --}}
        <div class="hidden lg:flex lg:w-[42%] bg-[#1F6662] lg:h-screen lg:sticky lg:top-0 relative overflow-hidden">

            {{-- Decorative circles --}}
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-[#185653] opacity-80"></div>
            <div class="absolute bottom-[-120px] left-[-100px] w-80 h-80 rounded-full bg-[#185653] opacity-70"></div>

            <div class="relative z-10 flex flex-col justify-between w-full px-12 xl:px-14 py-12">

                {{-- Logo / Brand --}}
                <div>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">

                        <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-[#1F6662]"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 10.5L12 3l9 7.5M5 9v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V9"/>
                            </svg>
                        </div>

                        <div>
                            <div class="text-white font-bold text-lg leading-none">
                                Marketplace
                            </div>

                            <div class="text-white/80 text-[11px] mt-1">
                                Construction & Business Platform
                            </div>
                        </div>

                    </a>

                    {{-- Tagline --}}
                    <div class="flex items-center gap-2 mt-3">
                        <span class="w-7 h-[3px] bg-[#E4B64C] rounded-full"></span>
                        <span class="text-[#E4B64C] text-xs font-semibold tracking-[0.18em]">
                            BUILD · CONNECT · GROW
                        </span>
                    </div>
                </div>


                {{-- Main Branding Content --}}
                <div class="my-auto max-w-xl py-10">

                    <h1 class="text-4xl xl:text-5xl font-extrabold leading-tight text-white">
                        Everything you need
                        <span class="block text-[#F0C866] mt-1">
                            to build better.
                        </span>
                    </h1>

                    <p class="mt-7 text-white/90 text-sm xl:text-base leading-7 max-w-lg">
                        Discover products, properties, land and construction
                        services. Connect with businesses and grow through one
                        trusted marketplace.
                    </p>


                    {{-- Benefits --}}
                    <div class="mt-9 space-y-6">

                        {{-- Benefit 1 --}}
                        <div class="flex items-start gap-4">

                            <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#F0C866]"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-white font-semibold text-sm">
                                    Discover opportunities
                                </h3>

                                <p class="text-white/75 text-xs mt-1 leading-5">
                                    Explore products, properties, land and services.
                                </p>
                            </div>

                        </div>


                        {{-- Benefit 2 --}}
                        <div class="flex items-start gap-4">

                            <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#F0C866]"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m8-5a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-white font-semibold text-sm">
                                    Connect with businesses
                                </h3>

                                <p class="text-white/75 text-xs mt-1 leading-5">
                                    Connect with buyers, sellers and service providers.
                                </p>
                            </div>

                        </div>


                        {{-- Benefit 3 --}}
                        <div class="flex items-start gap-4">

                            <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#F0C866]"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M9 12l2 2 4-4m5.5-2.5a9 9 0 11-17 0A9 9 0 0112 3.5a9 9 0 019.5 4z"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-white font-semibold text-sm">
                                    Build your business profile
                                </h3>

                                <p class="text-white/75 text-xs mt-1 leading-5">
                                    Create a professional presence and showcase your offerings.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>




                {{-- Footer --}}
                <div class="text-sm text-[#B7D7D3]">

                    © {{ date('Y') }} Marketplace.
                    All rights reserved.

                </div>

            </div>

        </div>


        {{-- ============================================================
            MOBILE HEADER
        ============================================================= --}}
        <div class="lg:hidden bg-[#004C47] text-white px-6 py-7">

            <a href="{{ url('/') }}"
               class="flex items-center gap-3">

                <div class="w-11 h-11 rounded-xl bg-white
                            flex items-center justify-center">

                    <x-application-logo
                        class="w-7 h-7 fill-current text-[#00665F]"
                    />

                </div>

                <div>

                    <h1 class="text-xl font-bold">
                        Marketplace
                    </h1>

                    <p class="text-xs text-[#D3E3E0]">
                        Construction & Business Marketplace
                    </p>

                </div>

            </a>

        </div>


        {{-- ============================================================
            RIGHT LOGIN SECTION
        ============================================================= --}}
        <div class="flex-1 flex items-center justify-center
                    px-5 py-10 sm:px-8 lg:px-12 xl:px-16">

            <div class="w-full max-w-[520px]">


                {{-- Heading --}}
                <div class="mb-8">

                    <p class="text-sm font-semibold uppercase
                              tracking-wider text-[#00665F]">

                        Account Login

                    </p>

                    <h2 class="mt-2 text-3xl sm:text-4xl
                               font-bold tracking-tight
                               text-[#123B38]">

                        Welcome back

                    </h2>

                    <p class="mt-3 text-[#4F6663]">

                        Sign in to continue to your marketplace account.

                    </p>

                </div>


                {{-- Login Card --}}
                <div class="bg-white rounded-2xl
                            border border-[#D3E3E0]
                            shadow-[0_10px_40px_rgba(0,76,71,0.08)]
                            p-6 sm:p-8">


                    {{-- Session Status --}}
                    <x-auth-session-status
                        class="mb-6"
                        :status="session('status')"
                    />


                    <form method="POST"
                          action="{{ route('login') }}">

                        @csrf


                        {{-- EMAIL --}}
                        <div>

                            <x-input-label
                                for="email"
                                :value="__('Email Address')"
                                class="text-[#123B38] font-semibold"
                            />

                            <div class="relative mt-2">

                                <div class="absolute inset-y-0 left-0
                                            flex items-center pl-4
                                            pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-[#718A86]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0
                                               L21 8M5 19h14a2 2 0 002-2V7
                                               a2 2 0 00-2-2H5a2 2 0 00-2 2v10
                                               a2 2 0 002 2z"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"

                                    placeholder="Enter your email"

                                    class="block w-full rounded-xl
                                           border border-[#D3E3E0]
                                           bg-[#F5F9F8]
                                           pl-12 pr-4 py-3.5
                                           text-[#123B38]
                                           placeholder-[#718A86]
                                           focus:border-[#00665F]
                                           focus:ring-2
                                           focus:ring-[#00665F]/20
                                           focus:bg-white
                                           transition"
                                />

                            </div>


                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2"
                            />

                        </div>


                        {{-- PASSWORD --}}
                        <div class="mt-6">

                            <div class="flex items-center
                                        justify-between">

                                <x-input-label
                                    for="password"
                                    :value="__('Password')"
                                    class="text-[#123B38] font-semibold"
                                />


                                @if (Route::has('password.request'))

                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-sm font-medium
                                               text-[#00665F]
                                               hover:text-[#004C47]
                                               transition"
                                    >
                                        Forgot password?
                                    </a>

                                @endif

                            </div>


                            <div class="relative mt-2">

                                <div class="absolute inset-y-0 left-0
                                            flex items-center pl-4
                                            pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-[#718A86]"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6
                                               a2 2 0 00-2-2H6a2 2 0 00-2 2v6
                                               a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z"
                                        />

                                    </svg>

                                </div>


                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"

                                    placeholder="Enter your password"

                                    class="block w-full rounded-xl
                                           border border-[#D3E3E0]
                                           bg-[#F5F9F8]
                                           pl-12 pr-4 py-3.5
                                           text-[#123B38]
                                           focus:border-[#00665F]
                                           focus:ring-2
                                           focus:ring-[#00665F]/20
                                           focus:bg-white
                                           transition"
                                />

                            </div>


                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-2"
                            />

                        </div>


                        {{-- REMEMBER ME --}}
                        <div class="mt-5">

                            <label
                                for="remember_me"
                                class="inline-flex items-center
                                       cursor-pointer"
                            >

                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"

                                    class="rounded-md
                                           border-[#D3E3E0]
                                           text-[#00665F]
                                           shadow-sm
                                           focus:ring-[#00665F]"
                                >

                                <span class="ms-2 text-sm text-[#4F6663]">
                                    {{ __('Remember me') }}
                                </span>

                            </label>

                        </div>


                        {{-- LOGIN BUTTON --}}
                        <button
                            type="submit"

                            class="w-full mt-7
                                   inline-flex items-center
                                   justify-center gap-2
                                   rounded-xl
                                   bg-[#008577]
                                   px-6 py-3.5
                                   text-sm font-semibold
                                   text-white
                                   shadow-lg shadow-[#004C47]/10
                                   hover:bg-[#005B55]
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-[#008577]
                                   focus:ring-offset-2
                                   transition-all duration-200
                                   hover:-translate-y-[1px]"
                        >

                            <span>
                                {{ __('Log in') }}
                            </span>


                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"
                                />

                            </svg>

                        </button>

                    </form>

                </div>


                {{-- Register Link --}}
                <div class="mt-7 text-center">

                    <p class="text-sm text-[#4F6663]">

                        Don't have an account?

                        <a
                            href="{{ route('register') }}"
                            class="font-semibold
                                   text-[#00665F]
                                   hover:text-[#004C47]
                                   transition"
                        >
                            Create an account
                        </a>

                    </p>

                </div>


                {{-- Security Note --}}
                <div class="mt-8 flex items-center justify-center
                            gap-2 text-xs text-[#718A86]">

                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6
                               a2 2 0 00-2-2H6a2 2 0 00-2 2v6
                               a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z"
                        />

                    </svg>

                    Secure login protected by Laravel

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>