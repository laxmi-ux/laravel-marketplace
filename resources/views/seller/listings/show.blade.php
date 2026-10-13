<x-seller-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <x-slot name="header">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('seller.listings.index') }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg
                       text-[#056663] transition hover:bg-[#E8F5F4]"
            >
                ←
            </a>

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    Listing Details
                </h2>

                <p class="text-sm text-gray-500">
                    View your listing information
                </p>
            </div>

        </div>

    </x-slot>


    <div class="mx-auto max-w-7xl space-y-6">

        {{-- =====================================================
            SUCCESS / ERROR
        ====================================================== --}}

        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif


        {{-- =====================================================
            LISTING OVERVIEW
        ====================================================== --}}

        <div class="overflow-hidden rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

            <div class="grid min-h-[900px] grid-cols-1 lg:grid-cols-5">

                {{-- =================================================
                    IMAGE
                ================================================== --}}

                <div class="lg:col-span-3">

                    @if($listing->image)

                        <div class="h-[900px] w-full overflow-hidden bg-gray-100">

                            <img
                                src="{{ route('uploads.file', ['path' => $listing->image]) }}"
                                alt="{{ $listing->title }}"
                                class="block h-full w-full object-cover transition duration-300 hover:scale-[1.01]"
                            >

                        </div>

                    @else

                        <div class="flex h-[900px] w-full items-center justify-center bg-gray-100">

                            <div class="text-center text-gray-400">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mx-auto mb-3 h-12 w-12"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 015.828 0L20 17M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>

                                <p class="text-sm">
                                    No Image Available
                                </p>

                            </div>

                        </div>

                    @endif

                </div>


                {{-- =================================================
                    LISTING INFORMATION
                ================================================== --}}

                <div
                    class="flex min-h-[900px] flex-col justify-between
                           border-t border-[#DDE9E8]
                           p-6 lg:col-span-2 lg:border-l lg:border-t-0"
                >

                    <div>

                        {{-- =================================================
                            STATUS
                        ================================================== --}}

                        <div class="mb-4 flex flex-wrap items-center gap-2">

                            @if($listing->status === 'approved')

                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Approved
                                </span>

                            @elseif($listing->status === 'pending')

                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                    Pending
                                </span>

                            @elseif($listing->status === 'rejected')

                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Rejected
                                </span>

                            @elseif($listing->status === 'draft')

                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                    Draft
                                </span>

                            @else

                                <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                                    {{ ucfirst($listing->status ?? 'Unknown') }}
                                </span>

                            @endif


                            @if($listing->is_featured)

                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                    Featured
                                </span>

                            @endif

                        </div>


                        {{-- =================================================
                            TITLE
                        ================================================== --}}

                        <h1 class="text-2xl font-bold leading-tight text-gray-900">
                            {{ $listing->title }}
                        </h1>


                        {{-- =================================================
                            DESCRIPTION
                        ================================================== --}}

                        @if($listing->description)

                            <p class="mt-4 text-sm leading-6 text-gray-600">
                                {{ $listing->description }}
                            </p>

                        @else

                            <p class="mt-4 text-sm italic text-gray-400">
                                No description available.
                            </p>

                        @endif


                        {{-- =================================================
                            PRICE
                        ================================================== --}}

                        @if($listing->price !== null)

                            <div class="mt-6">

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                    Price
                                </p>

                                <p class="mt-1 text-2xl font-bold text-[#056663]">

                                    ₹{{ number_format($listing->price, 2) }}

                                    @if($listing->price_unit)

                                        <span class="text-sm font-normal text-gray-500">
                                            / {{ $listing->price_unit }}
                                        </span>

                                    @endif

                                </p>

                            </div>

                        @endif


                        {{-- =================================================
                            BASIC INFORMATION
                        ================================================== --}}

                        <div class="mt-6 space-y-4">

                            {{-- CATEGORY --}}
                            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">

                                <span class="text-sm text-gray-500">
                                    Category
                                </span>

                                <span class="text-right text-sm font-semibold text-gray-800">

                                    @if($listing->categoryRelation)
                                        {{ $listing->categoryRelation->name }}
                                    @elseif($listing->category_id)
                                        Category #{{ $listing->category_id }}
                                    @else
                                        —
                                    @endif

                                </span>

                            </div>


                            {{-- SUBCATEGORY --}}
                            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">

                                <span class="text-sm text-gray-500">
                                    Subcategory
                                </span>

                                <span class="text-right text-sm font-semibold text-gray-800">

                                    @if($listing->subcategoryRelation)
                                        {{ $listing->subcategoryRelation->name }}
                                    @elseif($listing->subcategory_id)
                                        Subcategory #{{ $listing->subcategory_id }}
                                    @else
                                        —
                                    @endif

                                </span>

                            </div>


                            {{-- SUBCATEGORY ID --}}
                            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">

                                <span class="text-sm text-gray-500">
                                    Subcategory ID
                                </span>

                                <span class="text-right text-sm font-semibold text-gray-800">
                                    {{ $listing->subcategory_id ?: '—' }}
                                </span>

                            </div>


                            {{-- LISTING TYPE --}}
                            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">

                                <span class="text-sm text-gray-500">
                                    Listing Type
                                </span>

                                <span class="text-right text-sm font-semibold text-gray-800">

                                    @if($listing->listing_type)
                                        {{ ucfirst($listing->listing_type) }}
                                    @else
                                        —
                                    @endif

                                </span>

                            </div>


                            {{-- LOCATION --}}
                            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-3">

                                <span class="text-sm text-gray-500">
                                    Location
                                </span>

                                <span class="text-right text-sm font-semibold text-gray-800">

                                    @if($listing->city)
                                        {{ $listing->city }}
                                    @endif

                                    @if($listing->state)
                                        , {{ $listing->state }}
                                    @endif

                                    @if(!$listing->city && !$listing->state)
                                        —
                                    @endif

                                </span>

                            </div>


                            {{-- PINCODE --}}
                            <div class="flex items-start justify-between gap-4">

                                <span class="text-sm text-gray-500">
                                    Pincode
                                </span>

                                <span class="text-right text-sm font-semibold text-gray-800">
                                    {{ $listing->pincode ?: '—' }}
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        ACTIONS
                    ================================================== --}}

                    <div class="mt-6 flex flex-wrap gap-3">

                        <a
                            href="{{ route('seller.listings.edit', $listing->id) }}"
                            class="inline-flex items-center justify-center rounded-lg
                                   bg-[#056663] px-4 py-2.5 text-sm font-semibold
                                   text-white transition hover:bg-[#045552]"
                        >
                            Edit Listing
                        </a>

                        <a
                            href="{{ route('seller.listings.index') }}"
                            class="inline-flex items-center justify-center rounded-lg
                                   border border-[#DDE9E8] bg-white px-4 py-2.5
                                   text-sm font-semibold text-gray-700 transition
                                   hover:bg-gray-50"
                        >
                            Back
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            SELLER INFORMATION
        ====================================================== --}}

        <div class="rounded-xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

            <div class="mb-5">

                <h3 class="text-lg font-semibold text-gray-900">
                    Seller Information
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Information about the seller associated with this listing.
                </p>

            </div>


            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                <div class="rounded-lg bg-[#F7FAFA] p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Business Name
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ $seller->bussiness_name ?? '—' }}
                    </p>

                </div>


                <div class="rounded-lg bg-[#F7FAFA] p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Listing Status
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        {{ ucfirst($listing->status ?? '—') }}
                    </p>

                </div>


                <div class="rounded-lg bg-[#F7FAFA] p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Listing ID
                    </p>

                    <p class="mt-1 font-semibold text-gray-800">
                        #{{ $listing->id }}
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
            PRODUCT DETAILS
        ====================================================== --}}

        @if($listing->product)

            <div class="rounded-xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

                <div class="mb-5 flex items-center justify-between gap-3">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Product Details
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Product information associated with this listing.
                        </p>
                    </div>

                    @if($listing->subcategoryRelation)

                        <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                            {{ $listing->subcategoryRelation->name }}
                        </span>

                    @endif

                </div>


                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- BRAND --}}
                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Brand
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->product->brand ?? '—' }}
                        </p>

                    </div>


                    {{-- PRODUCT CODE --}}
                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Product Code
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->product->product_code ?? '—' }}
                        </p>

                    </div>


                    {{-- QUANTITY --}}
                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Quantity
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">

                            {{ $listing->product->quantity ?? '—' }}

                            @if($listing->product->unit)
                                {{ $listing->product->unit }}
                            @endif

                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =====================================================
            PROPERTY DETAILS
        ====================================================== --}}

        @if($listing->property)

            <div class="rounded-xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

                <h3 class="mb-5 text-lg font-semibold text-gray-900">
                    Property Details
                </h3>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Property Type
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->property->property_type ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Area
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">

                            {{ $listing->property->area ?? '—' }}

                            @if($listing->property->area_unit)
                                {{ $listing->property->area_unit }}
                            @endif

                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Bedrooms
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->property->bedrooms ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Bathrooms
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->property->bathrooms ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =====================================================
            LAND DETAILS
        ====================================================== --}}

        @if($listing->land)

            <div class="rounded-xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

                <h3 class="mb-5 text-lg font-semibold text-gray-900">
                    Land Details
                </h3>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Land Type
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->land->land_type ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Total Area
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">

                            {{ $listing->land->total_area ?? '—' }}

                            @if($listing->land->area_unit)
                                {{ $listing->land->area_unit }}
                            @endif

                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Survey Number
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->land->survey_number ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Gat Number
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->land->gat_number ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =====================================================
            SERVICE DETAILS
        ====================================================== --}}

        @if($listing->service)

            <div class="rounded-xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

                <h3 class="mb-5 text-lg font-semibold text-gray-900">
                    Service Details
                </h3>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Service Type
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->service->service_type ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Service Area
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->service->service_area ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Experience
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->service->experience ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Availability
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->service->availability ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =====================================================
            PROJECT DETAILS
        ====================================================== --}}

        @if($listing->project)

            <div class="rounded-xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

                <h3 class="mb-5 text-lg font-semibold text-gray-900">
                    Project Details
                </h3>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Project Type
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->project->project_type ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Project Status
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->project->project_status ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Developer
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $listing->project->developer_name ?? '—' }}
                        </p>

                    </div>


                    <div class="rounded-lg bg-[#F7FAFA] p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Total Area
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">

                            {{ $listing->project->total_area ?? '—' }}

                            @if($listing->project->area_unit)
                                {{ $listing->project->area_unit }}
                            @endif

                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- =====================================================
            DOCUMENTS
        ====================================================== --}}

        @if($listing->documents && $listing->documents->count())

            <div class="rounded-xl border border-[#DDE9E8] bg-white p-6 shadow-sm">

                <div class="mb-5">

                    <h3 class="text-lg font-semibold text-gray-900">
                        Documents
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Documents associated with this listing.
                    </p>

                </div>


                <div class="space-y-3">

                    @foreach($listing->documents as $document)

                        <div
                            class="flex flex-col gap-4 rounded-lg border border-[#DDE9E8]
                                   bg-[#F7FAFA] p-4 sm:flex-row sm:items-center
                                   sm:justify-between"
                        >

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center
                                           justify-center rounded-lg bg-[#E8F5F4]
                                           text-[#056663]"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M14 3v6h6"
                                        />
                                    </svg>

                                </div>


                                <div>

                                    <p class="font-medium text-gray-800">
                                        {{ $document->document_name }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $document->document_type }}
                                    </p>

                                </div>

                            </div>


                            <a
                                href="{{ route('seller.listings.documents.download', [$listing->id, $document->id]) }}"
                                class="inline-flex items-center justify-center rounded-lg
                                       bg-[#056663] px-4 py-2 text-sm font-semibold
                                       text-white transition hover:bg-[#045552]"
                            >
                                Download
                            </a>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif


        {{-- =====================================================
            BOTTOM ACTIONS
        ====================================================== --}}

        <div class="flex flex-wrap gap-3 pb-6">

            <a
                href="{{ route('seller.listings.edit', $listing->id) }}"
                class="inline-flex items-center justify-center rounded-lg
                       bg-[#056663] px-5 py-2.5 text-sm font-semibold
                       text-white transition hover:bg-[#045552]"
            >
                Edit Listing
            </a>


            <a
                href="{{ route('seller.listings.index') }}"
                class="inline-flex items-center justify-center rounded-lg
                       border border-[#DDE9E8] bg-white px-5 py-2.5
                       text-sm font-semibold text-gray-700 transition
                       hover:bg-gray-50"
            >
                Back to Listings
            </a>

        </div>

    </div>

</x-seller-layout>