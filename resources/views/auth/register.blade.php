<x-guest-layout>

    <div class="min-h-screen lg:h-screen flex flex-col lg:flex-row overflow-hidden">

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
                <div class="border-t border-white/10 pt-4">
                    <p class="text-white/70 text-xs">
                        © {{ date('Y') }} Marketplace. All rights reserved.
                    </p>
                </div>

            </div>
        </div>


        {{-- =========================================================
            RIGHT FORM PANEL
        ========================================================== --}}
        <div class="w-full lg:w-[58%] lg:h-screen lg:overflow-y-auto bg-[#F7F6F2]">

            {{-- Mobile Header --}}
            <div class="lg:hidden bg-[#1F6662] px-6 py-6">

                <a href="{{ url('/') }}" class="flex items-center gap-3">

                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#1F6662]"
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
                        <div class="text-white font-bold">
                            Marketplace
                        </div>

                        <div class="text-white/70 text-[10px]">
                            Construction & Business Platform
                        </div>
                    </div>

                </a>

            </div>


            {{-- Form Container --}}
            <div class="w-full max-w-[760px] mx-auto px-5 sm:px-8 lg:px-10 py-8 lg:py-10">

                {{-- Top Header --}}
                <div class="flex items-start justify-between gap-5 mb-8">

                    <div>

                        <p class="text-[#C18D24] font-bold text-[11px] tracking-[0.2em] uppercase">
                            Get started
                        </p>

                        <h2 class="text-3xl font-extrabold text-[#18302F] mt-2">
                            Create your account
                        </h2>

                        <p class="text-sm text-[#607876] mt-2">
                            Join the marketplace and start connecting with businesses and opportunities.
                        </p>

                    </div>

                    <div class="hidden sm:block text-right text-xs text-[#607876] whitespace-nowrap pt-1">
                        Already registered?
                        <a href="{{ route('login') }}"
                           class="font-semibold text-[#1F6662] hover:underline">
                            Sign in
                        </a>
                    </div>

                </div>


                {{-- Mobile Login --}}
                <div class="sm:hidden mb-6 text-xs text-[#607876]">
                    Already registered?
                    <a href="{{ route('login') }}"
                       class="font-semibold text-[#1F6662] hover:underline">
                        Sign in
                    </a>
                </div>


                <form method="POST" action="{{ route('register') }}" class="space-y-5">

                    @csrf


                    {{-- =================================================
                        PERSONAL INFORMATION
                    ================================================== --}}
                    <div class="bg-white border border-[#DDE5E3] rounded-2xl shadow-sm p-6 sm:p-7">

                        <div class="mb-6">

                            <h3 class="text-base font-semibold text-[#18302F]">
                                Personal information
                            </h3>

                            <p class="text-xs text-[#6D8582] mt-1">
                                Enter your basic contact information.
                            </p>

                        </div>


                        {{-- Full Name --}}
                        <div>
                            <x-input-label for="name"
                                           value="Full Name"
                                           class="text-[#18302F] font-medium text-s"/>

                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                :value="old('name')"
                                placeholder="Enter your full name"
                                required
                                autofocus
                                autocomplete="name"
                            />

                            <x-input-error :messages="$errors->get('name')" class="mt-1"/>
                        </div>


                        {{-- Email + Phone --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">

                            {{-- Email --}}
                            <div>

                                <x-input-label for="email"
                                               value="Email Address"
                                               class="text-[#18302F] font-medium text-s"/>

                                <x-text-input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                    :value="old('email')"
                                    placeholder="you@example.com"
                                    required
                                    autocomplete="username"
                                />

                                <x-input-error :messages="$errors->get('email')" class="mt-1"/>

                            </div>


                            {{-- Phone --}}
                            <div>

                                <x-input-label for="phone"
                                               value="Phone Number"
                                               class="text-[#18302F] font-medium text-s"/>

                                <div class="flex mt-2">

                                    <div class="flex items-center px-3 bg-gray-50 border border-r-0 border-[#DDE5E3] rounded-l-lg text-sm text-[#607876]">
                                        +91
                                    </div>

                                    <input
                                        id="phone"
                                        name="phone"
                                        type="tel"
                                        value="{{ old('phone') }}"
                                        placeholder="10-digit phone number"
                                        maxlength="10"
                                        required
                                        autocomplete="tel"
                                        class="block w-full border-[#DDE5E3] rounded-r-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                    />

                                </div>

                                <x-input-error :messages="$errors->get('phone')" class="mt-1"/>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        ACCOUNT TYPE
                    ================================================== --}}
                    <div class="bg-white border border-[#DDE5E3] rounded-2xl shadow-sm p-6 sm:p-7">

                        <div class="mb-6">

                            <h3 class="text-base font-semibold text-[#18302F]">
                                Choose your account type
                            </h3>

                            <p class="text-xs text-[#6D8582] mt-1">
                                Select how you want to use the marketplace.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Buyer --}}
                            <label
                                id="buyerCard"
                                class="relative cursor-pointer rounded-xl border border-[#DDE5E3] p-5 transition-all duration-200 hover:border-[#1F6662] bg-white">

                                <input
                                    type="radio"
                                    name="role"
                                    value="buyer"
                                    class="sr-only"
                                    {{ old('role', 'buyer') === 'buyer' ? 'checked' : '' }}
                                    onchange="selectRole('buyer')"
                                />

                                <div class="flex items-start gap-4">

                                    <div class="w-11 h-11 rounded-xl bg-[#EEF6F5] flex items-center justify-center flex-shrink-0">

                                        <svg class="w-5 h-5 text-[#1F6662]"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h13m-6 0a2 2 0 11-4 0m10 0a2 2 0 11-4 0"/>
                                        </svg>

                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-sm text-[#18302F]">
                                            Buyer
                                        </h4>

                                        <p class="text-xs text-[#6D8582] mt-1 leading-5">
                                            Find products, properties, land and services.
                                            Contact sellers and request quotations.
                                        </p>
                                    </div>

                                </div>

                            </label>


                            {{-- Seller --}}
                            <label
                                id="sellerCard"
                                class="relative cursor-pointer rounded-xl border border-[#DDE5E3] p-5 transition-all duration-200 hover:border-[#1F6662] bg-white">

                                <input
                                    type="radio"
                                    name="role"
                                    value="seller"
                                    class="sr-only"
                                    {{ old('role') === 'seller' ? 'checked' : '' }}
                                    onchange="selectRole('seller')"
                                />

                                <div class="flex items-start gap-4">

                                    <div class="w-11 h-11 rounded-xl bg-[#EEF6F5] flex items-center justify-center flex-shrink-0">

                                        <svg class="w-5 h-5 text-[#1F6662]"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M4 21h16M6 21V5a2 2 0 012-2h8a2 2 0 012 2v16M9 7h2m2 0h2M9 11h2m2 0h2M9 15h2m2 0h2"/>
                                        </svg>

                                    </div>

                                    <div>

                                        <h4 class="font-semibold text-sm text-[#18302F]">
                                            Seller
                                        </h4>

                                        <p class="text-xs text-[#6D8582] mt-1 leading-5">
                                            Create your business profile and list
                                            products, properties and land.
                                        </p>

                                    </div>

                                </div>

                                {{-- Selected indicator --}}
                                <div id="sellerCheck"
                                     class="absolute top-4 right-4 w-5 h-5 rounded-full bg-[#C18D24] text-white items-center justify-center hidden">

                                    <svg class="w-3 h-3"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="3"
                                              d="M5 12l4 4L19 6"/>
                                    </svg>

                                </div>

                            </label>

                        </div>

                        <x-input-error :messages="$errors->get('role')" class="mt-2"/>

                    </div>


                    {{-- =================================================
                        SELLER BUSINESS INFORMATION
                    ================================================== --}}
                    <div id="sellerFields"
                         class="bg-[#F1F8F7] border border-[#CFE1DF] rounded-2xl shadow-sm p-6 sm:p-7">

                        <div class="flex items-start gap-3 mb-6">

                            <div class="w-10 h-10 rounded-xl bg-[#DCEFED] flex items-center justify-center flex-shrink-0">

                                <svg class="w-5 h-5 text-[#1F6662]"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M4 21h16M6 21V5a2 2 0 012-2h8a2 2 0 012 2v16M9 7h2m2 0h2M9 11h2m2 0h2M9 15h2m2 0h2"/>
                                </svg>

                            </div>

                            <div>
                                <h3 class="text-base font-semibold text-[#18302F]">
                                    Business information
                                </h3>

                                <p class="text-xs text-[#6D8582] mt-1">
                                    Add your basic business details.
                                </p>
                            </div>

                        </div>


                        {{-- Seller Type + Business Name --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Seller Type --}}
                            <div>

                                <x-input-label
                                    for="seller_type"
                                    value="Seller Type"
                                    class="text-[#18302F] font-medium text-s"
                                />

                                <select
                                    id="seller_type"
                                    name="seller_type"
                                    class="block mt-2 w-full  rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662] text-sm"
                                >
                                    <option value="">Select seller type</option>

                                    <option value="individual"
                                        {{ old('seller_type') === 'individual' ? 'selected' : '' }}>
                                        Individual
                                    </option>

                                    <option value="company"
                                        {{ old('seller_type') === 'company' ? 'selected' : '' }}>
                                        Company
                                    </option>
                                </select>

                                <x-input-error :messages="$errors->get('seller_type')" class="mt-1"/>

                            </div>


                            {{-- Business Name --}}
                            <div>

                                <x-input-label
                                    for="business_name"
                                    value="Business / Company Name"
                                    class="text-[#18302F] font-medium text-s"
                                />

                                <x-text-input
                                    id="business_name"
                                    name="business_name"
                                    type="text"
                                    class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                    :value="old('business_name')"
                                    placeholder="Enter business name"
                                />

                                <x-input-error :messages="$errors->get('business_name')" class="mt-1"/>

                            </div>

                        </div>


                        {{-- Business Category --}}
                        <div class="mt-5">

                            <x-input-label
                                for="business_category"
                                value="Business Category"
                                class="text-[#18302F] font-medium text-s"
                            />

                            <select
                                id="business_category"
                                name="business_category"
                                class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662] text-sm"
                            >

                                <option value="">
                                    Select your business category
                                </option>

                                <option value="building_materials"
                                    {{ old('business_category') === 'properties' ? 'selected' : '' }}>
                                    Properties
                                </option>

                                <option value="real_estate"
                                    {{ old('business_category') === 'real_estate' ? 'selected' : '' }}>
                                    Real Estate
                                </option>

                                <option value="land_developer"
                                    {{ old('business_category') === 'land' ? 'selected' : '' }}>
                                    Land 
                                </option>

                                <option value="construction"
                                    {{ old('business_category') === 'construction' ? 'selected' : '' }}>
                                    Construction
                                </option>

                                <option value="equipment_leasing"
                                    {{ old('business_category') === 'product' ? 'selected' : '' }}>
                                   Product Based on business
                                </option>

                                <option value="services"
                                    {{ old('business_category') === 'services' ? 'selected' : '' }}>
                                    Services
                                </option>

                                <option value="other"
                                    {{ old('business_category') === 'other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>

                            <x-input-error :messages="$errors->get('business_category')" class="mt-1"/>

                        </div>


                        {{-- GST + Pincode --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">

                            {{-- GST --}}
                            <div>

                                <x-input-label
                                    for="gst_number"
                                    value="GST Number"
                                    class="text-[#18302F] font-medium text-s"
                                />

                                <x-text-input
                                    id="gst_number"
                                    name="gst_number"
                                    type="text"
                                    class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662] uppercase"
                                    :value="old('gst_number')"
                                    placeholder="Enter GST Number"
                                />

                                <p class="text-[11px] text-[#78908D] mt-1">
                                    Optional at registration
                                </p>

                                <x-input-error :messages="$errors->get('gst_number')" class="mt-1"/>

                            </div>


                            {{-- Pincode --}}
                            <div>

                                <x-input-label
                                    for="pincode"
                                    value="Pincode"
                                    class="text-[#18302F] font-medium text-s"
                                />

                                <x-text-input
                                    id="pincode"
                                    name="pincode"
                                    type="text"
                                    maxlength="6"
                                    class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                    :value="old('pincode')"
                                    placeholder="6-digit pincode"
                                />

                                <x-input-error :messages="$errors->get('pincode')" class="mt-1"/>

                            </div>

                        </div>


                        {{-- Business Address --}}
                        <div class="mt-5">

                            <x-input-label
                                for="business_address"
                                value="Business Address"
                                class="text-[#18302F] font-medium text-s"
                            />

                            <textarea
                                id="business_address"
                                name="business_address"
                                rows="3"
                                placeholder="Enter your business address"
                                class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662] text-sm"
                            >{{ old('business_address') }}</textarea>

                            <x-input-error :messages="$errors->get('business_address')" class="mt-1"/>

                        </div>


                        {{-- City + State --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">

                            {{-- City --}}
                            <div>

                                <x-input-label
                                    for="city"
                                    value="City"
                                    class="text-[#18302F] font-medium text-s"
                                />

                                <x-text-input
                                    id="city"
                                    name="city"
                                    type="text"
                                    class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                    :value="old('city')"
                                    placeholder="Enter city"
                                />

                                <x-input-error :messages="$errors->get('city')" class="mt-1"/>

                            </div>


                            {{-- State --}}
                            <div>

                                <x-input-label
                                    for="state"
                                    value="State"
                                    class="text-[#18302F] font-medium text-s"
                                />

                                <x-text-input
                                    id="state"
                                    name="state"
                                    type="text"
                                    class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                    :value="old('state')"
                                    placeholder="Enter state"
                                />

                                <x-input-error :messages="$errors->get('state')" class="mt-1"/>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        ACCOUNT SECURITY
                    ================================================== --}}
                    <div class="bg-white border border-[#DDE5E3] rounded-2xl shadow-sm p-6 sm:p-7">

                        <div class="mb-6">

                            <h3 class="text-base font-semibold text-[#18302F]">
                                Account security
                            </h3>

                            <p class="text-xs text-[#6D8582] mt-1">
                                Create a secure password for your account.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Password --}}
                            <div>

                                <x-input-label
                                    for="password"
                                    value="Password"
                                    class="text-[#18302F] font-medium text-s"
                                />

                                <x-text-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                    placeholder="Create password"
                                    required
                                    autocomplete="new-password"
                                />

                                <x-input-error :messages="$errors->get('password')" class="mt-1"/>

                            </div>


                            {{-- Confirm Password --}}
                            <div>

                                <x-input-label
                                    for="password_confirmation"
                                    value="Confirm Password"
                                    class="text-[#18302F] font-medium text-s"
                                />

                                <x-text-input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="block mt-2 w-full border-[#DDE5E3] rounded-lg focus:border-[#1F6662] focus:ring-[#1F6662]"
                                    placeholder="Confirm password"
                                    required
                                    autocomplete="new-password"
                                />

                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1"/>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        TERMS + SUBMIT
                    ================================================== --}}
                    <div class="pb-8">

                        <label class="flex items-start gap-3 cursor-pointer">

                            <input
                                type="checkbox"
                                name="terms"
                                value="1"
                                required
                                class="mt-1 rounded border-[#C8D6D4] text-[#1F6662] focus:ring-[#1F6662]"
                                {{ old('terms') ? 'checked' : '' }}
                            />

                            <span class="text-xs text-[#607876] leading-5">
                                I agree to the
                                <a href="#" class="text-[#1F6662] font-semibold hover:underline">
                                    Terms & Conditions
                                </a>
                                and
                                <a href="#" class="text-[#1F6662] font-semibold hover:underline">
                                    Privacy Policy
                                </a>.
                            </span>

                        </label>

                        <x-input-error :messages="$errors->get('terms')" class="mt-1"/>


                        <button
                            type="submit"
                            class="mt-5 w-full h-12 rounded-xl bg-[#1F6662] hover:bg-[#185653] text-white font-semibold text-sm transition duration-200 flex items-center justify-center gap-2 shadow-sm"
                        >
                            Create account

                            <svg class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 12h14m-6-6l6 6-6 6"/>
                            </svg>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
        ROLE SELECTION SCRIPT
    ============================================================= --}}
    <script>

        function selectRole(role) {

            const buyerCard = document.getElementById('buyerCard');
            const sellerCard = document.getElementById('sellerCard');

            const sellerFields = document.getElementById('sellerFields');
            const sellerCheck = document.getElementById('sellerCheck');

            if (role === 'seller') {

                // Seller selected
                sellerCard.classList.add(
                    'border-[#1F6662]',
                    'bg-[#F1F8F7]'
                );

                sellerCard.classList.remove(
                    'border-[#DDE5E3]',
                    'bg-white'
                );

                // Buyer normal
                buyerCard.classList.remove(
                    'border-[#1F6662]',
                    'bg-[#F1F8F7]'
                );

                buyerCard.classList.add(
                    'border-[#DDE5E3]',
                    'bg-white'
                );

                // Show seller fields
                sellerFields.classList.remove('hidden');

                sellerCheck.classList.remove('hidden');
                sellerCheck.classList.add('flex');

            } else {

                // Buyer selected
                buyerCard.classList.add(
                    'border-[#1F6662]',
                    'bg-[#F1F8F7]'
                );

                buyerCard.classList.remove(
                    'border-[#DDE5E3]',
                    'bg-white'
                );

                // Seller normal
                sellerCard.classList.remove(
                    'border-[#1F6662]',
                    'bg-[#F1F8F7]'
                );

                sellerCard.classList.add(
                    'border-[#DDE5E3]',
                    'bg-white'
                );

                // Hide seller fields
                sellerFields.classList.add('hidden');

                sellerCheck.classList.add('hidden');
                sellerCheck.classList.remove('flex');

            }
        }


        // Set correct state when page loads
        document.addEventListener('DOMContentLoaded', function () {

            const selectedRole =
                document.querySelector('input[name="role"]:checked')?.value || 'buyer';

            selectRole(selectedRole);

        });

    </script>

</x-guest-layout>