<section>

    <form method="post"
          action="{{ route('profile.update') }}"
          class="space-y-8">

        @csrf
        @method('patch')


        {{-- ========================================= --}}
        {{-- BUYER PROFILE --}}
        {{-- ========================================= --}}

        @if($user->role === 'buyer')

            <div>

                <h3 class="mb-5 text-lg font-bold text-[#123B38]">
                    Personal Information
                </h3>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                    {{-- Name --}}
                    <div>

                        <label for="name"
                               class="block text-sm font-medium text-[#4F6663]">
                            Full Name
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Phone --}}
                    <div>

                        <label for="phone"
                               class="block text-sm font-medium text-[#4F6663]">
                            Phone Number
                        </label>

                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone', $user->phone) }}"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="md:col-span-2">

                        <label for="email"
                               class="block text-sm font-medium text-[#4F6663]">
                            Email Address
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>


        {{-- ========================================= --}}
        {{-- SELLER PROFILE --}}
        {{-- ========================================= --}}

        @elseif($user->role === 'seller')

            <div>

                <h3 class="mb-5 text-lg font-bold text-[#123B38]">
                    Personal Information
                </h3>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                    {{-- Name --}}
                    <div>

                        <label for="name"
                               class="block text-sm font-medium text-[#4F6663]">
                            Full Name
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Phone --}}
                    <div>

                        <label for="phone"
                               class="block text-sm font-medium text-[#4F6663]">
                            Phone Number
                        </label>

                        <input
                            id="phone"
                            name="phone"
                            type="text"
                            value="{{ old('phone', $user->phone) }}"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="md:col-span-2">

                        <label for="email"
                               class="block text-sm font-medium text-[#4F6663]">
                            Email Address
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                        @error('email')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- SELLER BUSINESS --}}
            <div class="border-t border-[#D3E3E0] pt-8">

                <h3 class="mb-5 text-lg font-bold text-[#123B38]">
                    Business Information
                </h3>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                    {{-- Seller Type --}}
                    <div>

                        <label for="seller_type"
                               class="block text-sm font-medium text-[#4F6663]">
                            Profile Type
                        </label>

                        <select
                            id="seller_type"
                            name="seller_type"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                            <option value="individual"
                                {{ old('seller_type', $user->seller_type) === 'individual' ? 'selected' : '' }}>
                                Individual Seller
                            </option>

                            <option value="company"
                                {{ old('seller_type', $user->seller_type) === 'company' ? 'selected' : '' }}>
                                Company
                            </option>

                        </select>

                        @error('seller_type')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Business Name --}}
                    <div>

                        <label for="business_name"
                               class="block text-sm font-medium text-[#4F6663]">
                            Business / Company Name
                        </label>

                        <input
                            id="business_name"
                            name="business_name"
                            type="text"
                            value="{{ old('business_name', $user->business_name) }}"
                            required
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                        @error('business_name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Business Category --}}
                    <div>

                        <label for="business_category"
                               class="block text-sm font-medium text-[#4F6663]">
                            Business Category
                        </label>

                        <select
                            id="business_category"
                            name="business_category"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                            <option value="">Select Category</option>

                            <option value="properties"
                                {{ old('business_category', $user->business_category) === 'properties' ? 'selected' : '' }}>
                                Properties
                            </option>

                            <option value="real_estate"
                                {{ old('business_category', $user->business_category) === 'real_estate' ? 'selected' : '' }}>
                                Real Estate
                            </option>

                            <option value="land"
                                {{ old('business_category', $user->business_category) === 'land' ? 'selected' : '' }}>
                                Land 
                            </option>

                            <option value="construction"
                                {{ old('business_category', $user->business_category) === 'construction' ? 'selected' : '' }}>
                                Construction
                            </option>

                            <option value="product"
                                {{ old('business_category', $user->business_category) === 'product' ? 'selected' : '' }}>
                               Product Based on Business
                            </option>

                            <option value="services"
                                {{ old('business_category', $user->business_category) === 'services' ? 'selected' : '' }}>
                                Services
                            </option>

                            <option value="other"
                                {{ old('business_category', $user->business_category) === 'other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                        @error('business_category')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- GST --}}
                    <div>

                        <label for="gst_number"
                               class="block text-sm font-medium text-[#4F6663]">
                            GST Number
                        </label>

                        <input
                            id="gst_number"
                            name="gst_number"
                            type="text"
                            value="{{ old('gst_number', $user->gst_number) }}"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                    </div>


                    {{-- City --}}
                    <div>

                        <label for="city"
                               class="block text-sm font-medium text-[#4F6663]">
                            City
                        </label>

                        <input
                            id="city"
                            name="city"
                            type="text"
                            value="{{ old('city', $user->city) }}"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                    </div>


                    {{-- State --}}
                    <div>

                        <label for="state"
                               class="block text-sm font-medium text-[#4F6663]">
                            State
                        </label>

                        <input
                            id="state"
                            name="state"
                            type="text"
                            value="{{ old('state', $user->state) }}"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                    </div>


                    {{-- Pincode --}}
                    <div>

                        <label for="pincode"
                               class="block text-sm font-medium text-[#4F6663]">
                            Pincode
                        </label>

                        <input
                            id="pincode"
                            name="pincode"
                            type="text"
                            value="{{ old('pincode', $user->pincode) }}"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >

                    </div>


                    {{-- Business Address --}}
                    <div class="md:col-span-2">

                        <label for="business_address"
                               class="block text-sm font-medium text-[#4F6663]">
                            Business Address
                        </label>

                        <textarea
                            id="business_address"
                            name="business_address"
                            rows="4"
                            class="mt-2 block w-full rounded-lg border border-[#D3E3E0] bg-[#F5F9F8] px-4 py-3 text-sm text-[#123B38] focus:border-[#00665F] focus:ring-2 focus:ring-[#00665F]/20"
                        >{{ old('business_address', $user->business_address) }}</textarea>

                    </div>

                </div>

            </div>

        @endif


        {{-- SAVE --}}
        <div class="flex items-center gap-4 border-t border-[#D3E3E0] pt-6">

            <button
                type="submit"
                class="rounded-lg bg-[#008577] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#005B55]"
            >
                Save Changes
            </button>


            @if (session('status') === 'profile-updated')

                <p class="text-sm font-medium text-green-600">
                    Profile updated successfully.
                </p>

            @endif

        </div>

    </form>

</section>