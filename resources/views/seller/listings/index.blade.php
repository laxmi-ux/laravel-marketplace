<x-seller-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div class="flex items-center gap-3">

            <div>
                <h2 class="text-xl font-bold text-[#123B38]">
                    My Listings
                </h2>

                <p class="text-sm text-[#4F6663]">
                    Manage all the listings you have created.
                </p>
            </div>

        </div>

    </x-slot>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <div class="min-h-screen bg-[#F5F9F8]">

        <div class="w-full px-4 py-6 sm:px-5 sm:py-8 lg:px-6">


            {{-- =====================================================
                SELLER INFORMATION
            ====================================================== --}}

            <div
                class="mb-6 rounded-xl border border-gray-200
                       bg-white p-5 sm:p-6">

                <h1 class="text-xl font-semibold text-gray-800 sm:text-2xl">
                    {{ $seller->bussiness_name }}
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Your marketplace listings
                </p>

            </div>


            {{-- =====================================================
                LISTINGS CONTAINER
            ====================================================== --}}

            <div
                class="overflow-hidden rounded-xl border
                       border-gray-200 bg-white">


                {{-- =================================================
                    LISTINGS HEADER
                ================================================== --}}

                <div class="border-b border-gray-200 p-5 sm:p-6">

                    <div class="w-full">

                        <div class="mb-5">

                            <h2 class="text-lg font-semibold text-gray-800">
                                Your Listings
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $listings->total() }} listing(s) found
                            </p>

                        </div>


                        {{-- =================================================
                            CATEGORY FILTERS
                        ================================================== --}}

                        <div class="mb-5 flex flex-wrap gap-2">

                            {{-- All Listings --}}
                            <a
                                href="{{ route('seller.listings.index') }}"
                                class="rounded-lg border px-4 py-2 text-sm
                                       font-medium transition
                                       {{ !request('category')
                                            ? 'border-[#004C47] bg-[#004C47] text-white'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                       }}">

                                All Listings

                            </a>


                            {{-- Products --}}
                            <a
                                href="{{ route('seller.listings.index', ['category' => 'product']) }}"
                                class="rounded-lg border px-4 py-2 text-sm
                                       font-medium transition
                                       {{ request('category') === 'product'
                                            ? 'border-[#004C47] bg-[#004C47] text-white'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                       }}">

                                Products

                            </a>


                            {{-- Properties --}}
                            <a
                                href="{{ route('seller.listings.index', ['category' => 'property']) }}"
                                class="rounded-lg border px-4 py-2 text-sm
                                       font-medium transition
                                       {{ request('category') === 'property'
                                            ? 'border-[#004C47] bg-[#004C47] text-white'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                       }}">

                                Properties

                            </a>


                            {{-- Land --}}
                            <a
                                href="{{ route('seller.listings.index', ['category' => 'land']) }}"
                                class="rounded-lg border px-4 py-2 text-sm
                                       font-medium transition
                                       {{ request('category') === 'land'
                                            ? 'border-[#004C47] bg-[#004C47] text-white'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                       }}">

                                Land

                            </a>


                            {{-- Services --}}
                            <a
                                href="{{ route('seller.listings.index', ['category' => 'service']) }}"
                                class="rounded-lg border px-4 py-2 text-sm
                                       font-medium transition
                                       {{ request('category') === 'service'
                                            ? 'border-[#004C47] bg-[#004C47] text-white'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                       }}">

                                Services

                            </a>


                            {{-- Projects --}}
                            <a
                                href="{{ route('seller.listings.index', ['category' => 'project']) }}"
                                class="rounded-lg border px-4 py-2 text-sm
                                       font-medium transition
                                       {{ request('category') === 'project'
                                            ? 'border-[#004C47] bg-[#004C47] text-white'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                       }}">

                                Projects

                            </a>

                        </div>


                        {{-- =================================================
                            ADD LISTING BUTTONS
                        ================================================== --}}

                        <div class="flex flex-wrap gap-2">

                            <a
                                href="{{ route('seller.listings.create', ['type' => 'product']) }}"
                                class="inline-flex items-center rounded-lg
                                       bg-[#004C47] px-4 py-2 text-sm
                                       font-medium text-white
                                       hover:bg-[#003F3B] transition">

                                + Product

                            </a>


                            <a
                                href="{{ route('seller.listings.create', ['type' => 'property']) }}"
                                class="inline-flex items-center rounded-lg
                                       bg-[#004C47] px-4 py-2 text-sm
                                       font-medium text-white
                                       hover:bg-[#003F3B] transition">

                                + Property

                            </a>


                            <a
                                href="{{ route('seller.listings.create', ['type' => 'land']) }}"
                                class="inline-flex items-center rounded-lg
                                       bg-[#004C47] px-4 py-2 text-sm
                                       font-medium text-white
                                       hover:bg-[#003F3B] transition">

                                + Land

                            </a>


                            <a
                                href="{{ route('seller.listings.create', ['type' => 'service']) }}"
                                class="inline-flex items-center rounded-lg
                                       bg-[#004C47] px-4 py-2 text-sm
                                       font-medium text-white
                                       hover:bg-[#003F3B] transition">

                                + Service

                            </a>


                            <a
                                href="{{ route('seller.listings.create', ['type' => 'project']) }}"
                                class="inline-flex items-center rounded-lg
                                       bg-[#004C47] px-4 py-2 text-sm
                                       font-medium text-white
                                       hover:bg-[#003F3B] transition">

                                + Project

                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    LISTINGS
                ================================================== --}}

                @if($listings->count())

                    <div class="divide-y divide-gray-200">

                        @foreach($listings as $listing)

                            <div class="p-5 sm:p-6">

                                <div class="flex flex-col gap-6 xl:flex-row">


                                    {{-- =================================================
                                        LISTING INFORMATION
                                    ================================================== --}}

                                    <div
                                        class="flex min-w-0 flex-1
                                               flex-col gap-5 sm:flex-row">


                                        {{-- =================================================
                                            IMAGE
                                        ================================================== --}}

                                        <div
                                            class="relative flex h-40 w-40
                                                   shrink-0 items-center
                                                   justify-center overflow-hidden
                                                   rounded-lg border
                                                   border-gray-200 bg-gray-100
                                                   sm:h-44 sm:w-44">

                                            @if($listing->image)

                                                <img
                                                    src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                                                    alt="{{ $listing->title }}"
                                                    class="h-full w-full object-cover">

                                            @else

                                                <div
                                                    class="flex flex-col items-center
                                                           justify-center px-2
                                                           text-center text-gray-400">

                                                    <svg
                                                        class="mb-1 h-8 w-8"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24">

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M4 16l4-4a3 3 0 014 0l4 4m-1-1l1-1a3 3 0 014 0l1 1M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zm3-10h.01" />

                                                    </svg>

                                                    <span class="text-xs">
                                                        No Image
                                                    </span>

                                                </div>

                                            @endif

                                        </div>


                                        {{-- =================================================
                                            DETAILS
                                        ================================================== --}}

                                        <div class="min-w-0 flex-1">

                                            <h3
                                                class="text-lg font-semibold
                                                       text-gray-800">

                                                {{ $listing->title }}

                                            </h3>


                                            @if($listing->description)

                                                <p
                                                    class="mt-1 line-clamp-2
                                                           text-sm text-gray-500">

                                                    {{ $listing->description }}

                                                </p>

                                            @endif


                                            {{-- =================================================
                                                TAGS
                                            ================================================== --}}

                                            <div
                                                class="mt-3 flex flex-wrap gap-2">


                                                {{-- Category --}}
                                                <span
                                                    class="rounded-full bg-gray-100
                                                           px-2.5 py-1 text-xs
                                                           font-medium text-gray-700">

                                                    @if($listing->categoryRelation)

                                                        {{ $listing->categoryRelation->name }}

                                                    @else

                                                        {{ ucfirst($listing->category ?? 'Category') }}

                                                    @endif

                                                </span>


                                                {{-- Listing Type --}}
                                                @if($listing->listing_type)

                                                    <span
                                                        class="rounded-full bg-gray-100
                                                               px-2.5 py-1 text-xs
                                                               font-medium text-gray-700">

                                                        {{ ucfirst($listing->listing_type) }}

                                                    </span>

                                                @endif


                                                {{-- Price --}}
                                                @if($listing->price !== null)

                                                    <span
                                                        class="rounded-full bg-gray-100
                                                               px-2.5 py-1 text-xs
                                                               font-medium text-gray-700">

                                                        ₹{{ number_format((float) $listing->price, 2) }}

                                                        @if($listing->price_unit)
                                                            / {{ $listing->price_unit }}
                                                        @endif

                                                    </span>

                                                @endif


                                                {{-- Subcategory --}}
                                                @if($listing->subcategoryRelation)

                                                    <span
                                                        class="rounded-full bg-gray-100
                                                               px-2.5 py-1 text-xs
                                                               font-medium text-gray-700">

                                                        {{ $listing->subcategoryRelation->name }}

                                                    </span>

                                                @elseif($listing->subcategory)

                                                    <span
                                                        class="rounded-full bg-gray-100
                                                               px-2.5 py-1 text-xs
                                                               font-medium text-gray-700">

                                                        {{ $listing->subcategory }}

                                                    </span>

                                                @endif

                                            </div>


                                            {{-- =================================================
                                                LOCATION
                                            ================================================== --}}

                                            @if($listing->city || $listing->state || $listing->pincode)

                                                <p
                                                    class="mt-3 text-xs
                                                           text-gray-400">

                                                    @if($listing->city)
                                                        {{ $listing->city }}
                                                    @endif

                                                    @if($listing->city && $listing->state)
                                                        ,
                                                    @endif

                                                    @if($listing->state)
                                                        {{ $listing->state }}
                                                    @endif

                                                    @if($listing->pincode)
                                                        - {{ $listing->pincode }}
                                                    @endif

                                                </p>

                                            @endif


                                            {{-- =================================================
                                                CATEGORY-SPECIFIC INFORMATION
                                            ================================================== --}}

                                            @if($listing->product)

                                                <div class="mt-4 rounded-lg bg-gray-50 p-4">

                                                    <p class="mb-2 text-sm font-semibold text-gray-700">
                                                        Product Details
                                                    </p>

                                                    <div class="grid gap-2 sm:grid-cols-2">

                                                        @if($listing->product->brand)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Brand:
                                                                </span>

                                                                {{ $listing->product->brand }}
                                                            </p>

                                                        @endif

                                                        @if($listing->product->product_code)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Product Code:
                                                                </span>

                                                                {{ $listing->product->product_code }}
                                                            </p>

                                                        @endif

                                                        @if($listing->product->quantity !== null)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Quantity:
                                                                </span>

                                                                {{ $listing->product->quantity }}

                                                                @if($listing->product->unit)
                                                                    {{ $listing->product->unit }}
                                                                @endif

                                                            </p>

                                                        @endif

                                                    </div>

                                                </div>

                                            @elseif($listing->property)

                                                <div class="mt-4 rounded-lg bg-gray-50 p-4">

                                                    <p class="mb-2 text-sm font-semibold text-gray-700">
                                                        Property Details
                                                    </p>

                                                    <div class="grid gap-2 sm:grid-cols-2">

                                                        @if($listing->property->property_type)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Property Type:
                                                                </span>

                                                                {{ $listing->property->property_type }}
                                                            </p>

                                                        @endif

                                                        @if($listing->property->area !== null)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Area:
                                                                </span>

                                                                {{ $listing->property->area }}

                                                                @if($listing->property->area_unit)
                                                                    {{ $listing->property->area_unit }}
                                                                @endif

                                                            </p>

                                                        @endif

                                                        @if($listing->property->bedrooms !== null)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Bedrooms:
                                                                </span>

                                                                {{ $listing->property->bedrooms }}
                                                            </p>

                                                        @endif

                                                        @if($listing->property->bathrooms !== null)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Bathrooms:
                                                                </span>

                                                                {{ $listing->property->bathrooms }}
                                                            </p>

                                                        @endif

                                                        @if($listing->property->property_status)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Status:
                                                                </span>

                                                                {{ $listing->property->property_status }}
                                                            </p>

                                                        @endif

                                                    </div>

                                                </div>

                                            @elseif($listing->land)

                                                <div class="mt-4 rounded-lg bg-gray-50 p-4">

                                                    <p class="mb-2 text-sm font-semibold text-gray-700">
                                                        Land Details
                                                    </p>

                                                    <div class="grid gap-2 sm:grid-cols-2">

                                                        @if($listing->land->land_type)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Land Type:
                                                                </span>

                                                                {{ $listing->land->land_type }}
                                                            </p>

                                                        @endif

                                                        @if($listing->land->total_area !== null)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Total Area:
                                                                </span>

                                                                {{ $listing->land->total_area }}

                                                                @if($listing->land->area_unit)
                                                                    {{ $listing->land->area_unit }}
                                                                @endif

                                                            </p>

                                                        @endif

                                                        @if($listing->land->ownership_type)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Ownership:
                                                                </span>

                                                                {{ $listing->land->ownership_type }}
                                                            </p>

                                                        @endif

                                                        @if($listing->land->road_access)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Road Access:
                                                                </span>

                                                                {{ $listing->land->road_access }}
                                                            </p>

                                                        @endif

                                                        @if($listing->land->zoning)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Zoning:
                                                                </span>

                                                                {{ $listing->land->zoning }}
                                                            </p>

                                                        @endif

                                                    </div>

                                                </div>

                                            @elseif($listing->service)

                                                <div class="mt-4 rounded-lg bg-gray-50 p-4">

                                                    <p class="mb-2 text-sm font-semibold text-gray-700">
                                                        Service Details
                                                    </p>

                                                    <div class="grid gap-2 sm:grid-cols-2">

                                                        @if($listing->service->service_type)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Service Type:
                                                                </span>

                                                                {{ $listing->service->service_type }}
                                                            </p>

                                                        @endif

                                                        @if($listing->service->service_area)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Service Area:
                                                                </span>

                                                                {{ $listing->service->service_area }}
                                                            </p>

                                                        @endif

                                                        @if($listing->service->experience)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Experience:
                                                                </span>

                                                                {{ $listing->service->experience }}
                                                            </p>

                                                        @endif

                                                        @if($listing->service->availability)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Availability:
                                                                </span>

                                                                {{ $listing->service->availability }}
                                                            </p>

                                                        @endif

                                                    </div>

                                                </div>

                                            @elseif($listing->project)

                                                <div class="mt-4 rounded-lg bg-gray-50 p-4">

                                                    <p class="mb-2 text-sm font-semibold text-gray-700">
                                                        Project Details
                                                    </p>

                                                    <div class="grid gap-2 sm:grid-cols-2">

                                                        @if($listing->project->project_type)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Project Type:
                                                                </span>

                                                                {{ $listing->project->project_type }}
                                                            </p>

                                                        @endif

                                                        @if($listing->project->project_status)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Status:
                                                                </span>

                                                                {{ $listing->project->project_status }}
                                                            </p>

                                                        @endif

                                                        @if($listing->project->developer_name)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Developer:
                                                                </span>

                                                                {{ $listing->project->developer_name }}
                                                            </p>

                                                        @endif

                                                        @if($listing->project->total_area !== null)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Total Area:
                                                                </span>

                                                                {{ $listing->project->total_area }}

                                                                @if($listing->project->area_unit)
                                                                    {{ $listing->project->area_unit }}
                                                                @endif

                                                            </p>

                                                        @endif

                                                        @if($listing->project->start_date)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Start Date:
                                                                </span>

                                                                {{ $listing->project->start_date->format('d M Y') }}
                                                            </p>

                                                        @endif

                                                        @if($listing->project->completion_date)

                                                            <p class="text-xs text-gray-500">
                                                                <span class="font-medium text-gray-700">
                                                                    Completion:
                                                                </span>

                                                                {{ $listing->project->completion_date->format('d M Y') }}
                                                            </p>

                                                        @endif

                                                    </div>

                                                </div>

                                            @endif


                                            {{-- =================================================
                                                DOCUMENTS
                                            ================================================== --}}

                                            <div class="mt-4">

                                                <p
                                                    class="mb-2 text-sm
                                                           font-medium text-gray-700">

                                                    Documents

                                                </p>


                                                @if($listing->documents->count())

                                                    <div class="flex flex-wrap gap-2">

                                                        @foreach($listing->documents as $document)

                                                            <a
                                                                href="{{ route('seller.listings.documents.download', [
                                                                    'listing' => $listing->id,
                                                                    'document' => $document->id
                                                                ]) }}"
                                                                class="inline-flex max-w-full
                                                                       items-center gap-2
                                                                       rounded-lg border
                                                                       border-gray-300
                                                                       bg-white px-3 py-2
                                                                       text-xs font-medium
                                                                       text-gray-700
                                                                       hover:border-[#004C47]
                                                                       hover:text-[#004C47]">

                                                                <svg
                                                                    class="h-4 w-4 shrink-0"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24">

                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="1.8"
                                                                        d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z" />

                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="1.8"
                                                                        d="M14 3v6h5M9 13h6M9 17h6" />

                                                                </svg>

                                                                <span class="max-w-[180px] truncate">
                                                                    {{ $document->document_name }}
                                                                </span>

                                                            </a>

                                                        @endforeach

                                                    </div>

                                                @else

                                                    <p class="text-xs text-gray-400">
                                                        No documents uploaded.
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                        STATUS & ACTIONS
                                    ================================================== --}}

                                    <div
                                        class="flex shrink-0 flex-row
                                               items-start gap-3
                                               xl:flex-col xl:items-end">


                                        {{-- =================================================
                                            STATUS
                                        ================================================== --}}

                                        <div>

                                            @if($listing->status === 'approved')

                                                <span
                                                    class="inline-flex rounded-full
                                                           bg-green-100 px-3 py-1
                                                           text-xs font-medium
                                                           text-green-700">

                                                    Approved

                                                </span>

                                            @elseif($listing->status === 'pending')

                                                <span
                                                    class="inline-flex rounded-full
                                                           bg-yellow-100 px-3 py-1
                                                           text-xs font-medium
                                                           text-yellow-700">

                                                    Pending Approval

                                                </span>

                                            @elseif($listing->status === 'rejected')

                                                <span
                                                    class="inline-flex rounded-full
                                                           bg-red-100 px-3 py-1
                                                           text-xs font-medium
                                                           text-red-700">

                                                    Rejected

                                                </span>

                                            @else

                                                <span
                                                    class="inline-flex rounded-full
                                                           bg-gray-100 px-3 py-1
                                                           text-xs font-medium
                                                           text-gray-700">

                                                    {{ ucfirst($listing->status) }}

                                                </span>

                                            @endif

                                        </div>


                                        {{-- =================================================
                                            ACTIONS
                                        ================================================== --}}

                                        <div class="flex flex-wrap gap-2">

                                            {{-- View --}}
                                            @if(Route::has('seller.listings.show'))

                                                <a
                                                    href="{{ route('seller.listings.show', $listing->id) }}"
                                                    class="rounded-lg border
                                                           border-gray-300 px-3 py-2
                                                           text-xs font-medium
                                                           text-gray-700
                                                           hover:bg-gray-50">

                                                    View

                                                </a>

                                            @endif


                                            {{-- Edit --}}
                                            <a
                                                href="{{ route('seller.listings.edit', $listing->id) }}"
                                                class="rounded-lg border
                                                       border-[#004C47] px-3 py-2
                                                       text-xs font-medium
                                                       text-[#004C47]
                                                       hover:bg-[#004C47]
                                                       hover:text-white">

                                                Edit

                                            </a>


                                            {{-- Delete --}}
                                            <form
                                                action="{{ route('seller.listings.destroy', $listing->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this listing?')">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="rounded-lg border
                                                           border-red-300 px-3 py-2
                                                           text-xs font-medium
                                                           text-red-600
                                                           hover:bg-red-600
                                                           hover:text-white">

                                                    Delete

                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    {{-- =================================================
                        PAGINATION
                    ================================================== --}}

                    <div
                        class="border-t border-gray-200
                               px-5 py-5 sm:px-6">

                        {{ $listings->links() }}

                    </div>


                @else

                    {{-- =================================================
                        EMPTY STATE
                    ================================================== --}}

                    <div class="px-5 py-14 text-center">

                        <div
                            class="mx-auto flex h-14 w-14 items-center
                                   justify-center rounded-full bg-gray-100">

                            <svg
                                class="h-7 w-7 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12z" />

                            </svg>

                        </div>


                        <h3
                            class="mt-4 text-lg font-semibold text-gray-700">

                            No listings found

                        </h3>


                        @if(request('category'))

                            <p class="mt-1 text-sm text-gray-500">
                                You don't have any listings in this category yet.
                            </p>

                        @else

                            <p class="mt-1 text-sm text-gray-500">
                                You haven't created any listings yet.
                            </p>

                        @endif


                        <div class="mt-5 flex flex-wrap justify-center gap-2">

                            <a
                                href="{{ route('seller.listings.create', ['type' => 'product']) }}"
                                class="inline-flex rounded-lg
                                       bg-[#004C47] px-5 py-2.5 text-sm
                                       font-medium text-white
                                       hover:bg-[#003F3B] transition">

                                + Add Product

                            </a>

                            <a
                                href="{{ route('seller.listings.create', ['type' => 'property']) }}"
                                class="inline-flex rounded-lg
                                       border border-[#004C47] px-5 py-2.5
                                       text-sm font-medium text-[#004C47]
                                       hover:bg-[#EAF5F3] transition">

                                + Add Property

                            </a>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-seller-layout>