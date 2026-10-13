<x-seller-layout>

    <x-slot name="header">

        <div class="flex items-center gap-4">

            <a
                href="{{ route('seller.orders.index') }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-[#056663] hover:bg-[#E8F5F4]"
            >
                <span class="material-symbols-outlined">
                    arrow_back
                </span>
            </a>

            <div>

                <h2 class="text-2xl font-bold text-gray-900">
                    Order {{ $order->order_number }}
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Manage buyer order
                </p>

            </div>

        </div>

    </x-slot>


    @php

        /*
        |--------------------------------------------------------------------------
        | CATEGORY NORMALIZATION
        |--------------------------------------------------------------------------
        */

        $categoryKey = strtolower(
            trim((string) ($order->category ?? ''))
        );

        $category = match ($categoryKey) {

            'product',
            'products' => 'Products',

            'service',
            'services' => 'Services',

            'project',
            'projects' => 'Projects',

            default => ucfirst($categoryKey),

        };


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $statusKey = strtolower(
            trim((string) ($order->order_status ?? ''))
        );


        $statusLabel = match ($statusKey) {

            'pending' => 'Pending',

            'confirmed' => 'Confirmed',

            'processing' => 'Processing',

            'shipped' => 'Shipped',

            'delivered' => 'Delivered',

            'completed' => 'Completed',

            'rejected' => 'Rejected',

            'cancelled',
            'canceled' => 'Cancelled',

            default => ucfirst(
                str_replace('_', ' ', $statusKey)
            ),

        };


        $statusClass = match ($statusKey) {

            'pending'
                => 'bg-yellow-100 text-yellow-700',

            'confirmed'
                => 'bg-blue-100 text-blue-700',

            'processing'
                => 'bg-indigo-100 text-indigo-700',

            'shipped'
                => 'bg-purple-100 text-purple-700',

            'delivered'
                => 'bg-green-100 text-green-700',

            'completed'
                => 'bg-emerald-100 text-emerald-700',

            'rejected',
            'cancelled',
            'canceled'
                => 'bg-red-100 text-red-700',

            default
                => 'bg-gray-100 text-gray-700',

        };


        $isProduct = $category === 'Products';

        $isService = $category === 'Services';

        $isProject = $category === 'Projects';


        /*
        |--------------------------------------------------------------------------
        | DELIVERY INFORMATION
        |--------------------------------------------------------------------------
        |
        | Priority:
        |
        | 1. Order delivery information
        | 2. Requirement delivery information
        | 3. Listing location as fallback
        |
        */

        $deliveryLocation =
            $order->delivery_location
            ?? $order->requirement?->delivery_location
            ?? $order->listing?->city;


        /*
        |--------------------------------------------------------------------------
        | DELIVERY ADDRESS
        |--------------------------------------------------------------------------
        */

        $deliveryAddress =
            $order->delivery_address
            ?? $order->requirement?->delivery_address;


        /*
        | If actual delivery address is not available,
        | build address from listing location.
        */

        if (!$deliveryAddress && $order->listing) {

            $deliveryAddress = collect([
                $order->listing->city,
                $order->listing->state,
                $order->listing->pincode,
            ])
                ->filter()
                ->implode(', ');

        }


        /*
        |--------------------------------------------------------------------------
        | DELIVERY TIME
        |--------------------------------------------------------------------------
        */

        $deliveryTime =
            $order->delivery_time
            ?? $order->requirement?->delivery_time;


        /*
        |--------------------------------------------------------------------------
        | REQUIRED DATE
        |--------------------------------------------------------------------------
        */

        $requiredDate =
            $order->required_date
            ?? $order->requirement?->required_date;


        /*
        |--------------------------------------------------------------------------
        | BUYER REQUIREMENT / MESSAGE
        |--------------------------------------------------------------------------
        */

        $deliveryMessage =
            $order->message
            ?? $order->requirement?->message;


    @endphp


    <div class="mx-auto max-w-6xl m-5 space-y-6">


        {{-- ========================================================= --}}
        {{-- FLASH MESSAGES --}}
        {{-- ========================================================= --}}

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


        {{-- ========================================================= --}}
        {{-- ORDER STATUS + ACTION --}}
        {{-- ========================================================= --}}

        <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Current Order Status
                    </p>

                    <div class="mt-2 flex flex-wrap items-center gap-3">

                        <span class="text-2xl font-bold text-[#056663]">
                            {{ $statusLabel }}
                        </span>

                        <span class="rounded-full bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                            {{ $category }}
                        </span>

                    </div>

                </div>


                <div class="flex flex-wrap gap-3">

                    {{-- ================================================= --}}
                    {{-- PENDING --}}
                    {{-- ================================================= --}}

                    @if($statusKey === 'pending')

                        <form
                            method="POST"
                            action="{{ route('seller.orders.accept', $order->id) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
                            >
                                Accept Order
                            </button>

                        </form>


                        <form
                            method="POST"
                            action="{{ route('seller.orders.reject', $order->id) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="rounded-lg border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-100"
                            >
                                Reject
                            </button>

                        </form>


                    {{-- ================================================= --}}
                    {{-- CONFIRMED --}}
                    {{-- ================================================= --}}

                    @elseif($statusKey === 'confirmed')

                        @if($isProduct)

                            <form
                                method="POST"
                                action="{{ route('seller.orders.process', $order->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
                                >
                                    Start Processing
                                </button>

                            </form>


                        @elseif($isService)

                            <form
                                method="POST"
                                action="{{ route('seller.orders.start-work', $order->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
                                >
                                    Start Service
                                </button>

                            </form>


                        @elseif($isProject)

                            <form
                                method="POST"
                                action="{{ route('seller.orders.start-work', $order->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
                                >
                                    Start Project
                                </button>

                            </form>

                        @endif


                    {{-- ================================================= --}}
                    {{-- PROCESSING --}}
                    {{-- ================================================= --}}

                    @elseif($statusKey === 'processing')

                        @if($isProduct)

                            <form
                                method="POST"
                                action="{{ route('seller.orders.ship', $order->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
                                >
                                    Mark as Shipped
                                </button>

                            </form>


                        @elseif($isService)

                            <form
                                method="POST"
                                action="{{ route('seller.orders.complete-work', $order->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
                                >
                                    Complete Service
                                </button>

                            </form>


                        @elseif($isProject)

                            <form
                                method="POST"
                                action="{{ route('seller.orders.complete-work', $order->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
                                >
                                    Complete Project
                                </button>

                            </form>

                        @endif


                    {{-- ================================================= --}}
                    {{-- SHIPPED --}}
                    {{-- ================================================= --}}

                    @elseif($statusKey === 'shipped')

                        @if($isProduct)

                            <form
                                method="POST"
                                action="{{ route('seller.orders.deliver', $order->id) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
                                >
                                    Mark as Delivered
                                </button>

                            </form>

                        @endif


                    {{-- ================================================= --}}
                    {{-- DELIVERED --}}
                    {{-- ================================================= --}}

                    @elseif($statusKey === 'delivered')

                        @if($isProduct)

                            <div class="rounded-lg bg-green-50 px-4 py-2.5 text-sm font-semibold text-green-700">
                                Waiting for buyer confirmation
                            </div>

                        @endif


                    {{-- ================================================= --}}
                    {{-- COMPLETED --}}
                    {{-- ================================================= --}}

                    @elseif($statusKey === 'completed')

                        <div class="rounded-lg bg-green-50 px-4 py-2.5 text-sm font-semibold text-green-700">
                            Order Completed
                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BUYER + DELIVERY / CATEGORY INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="grid gap-6 lg:grid-cols-2">


           
{{-- ===================================================== --}}
{{-- BUYER INFORMATION --}}
{{-- ===================================================== --}}

<div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

    <div class="flex items-center gap-3">

        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#E8F5F4]">

            <span class="material-symbols-outlined text-[#056663]">
                person
            </span>

        </div>

        <div>

            <h3 class="text-lg font-bold text-gray-900">
                Buyer Information
            </h3>

            <p class="text-xs text-gray-500">
                Buyer details for this order
            </p>

        </div>

    </div>


    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">


        {{-- BUYER NAME --}}

        <div class="rounded-lg bg-[#F7FAFA] p-4">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Buyer Name
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ $order->buyer?->name ?? 'Not provided' }}
            </p>

        </div>


        {{-- EMAIL --}}

        <div class="rounded-lg bg-[#F7FAFA] p-4">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Email Address
            </p>

            <p class="mt-1 break-all text-sm font-semibold text-gray-900">
                {{ $order->buyer?->email ?? 'Not provided' }}
            </p>

        </div>


        {{-- PHONE --}}

        <div class="rounded-lg bg-[#F7FAFA] p-4">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Phone Number
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                {{ $order->buyer?->phone ?? 'Not provided' }}
            </p>

        </div>


        {{-- BUYER ID --}}

        <div class="rounded-lg bg-[#F7FAFA] p-4">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Buyer ID
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                #{{ $order->buyer_id }}
            </p>

        </div>


        {{-- ORDER DATE --}}

        <div class="rounded-lg bg-[#F7FAFA] p-4">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Order Date
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">

                @if($order->ordered_at)

                    {{ $order->ordered_at->format('d M Y') }}

                @else

                    {{ $order->created_at?->format('d M Y') ?? 'Not available' }}

                @endif

            </p>

        </div>


        {{-- ORDER TIME --}}

        <div class="rounded-lg bg-[#F7FAFA] p-4">

            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Order Time
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">

                @if($order->ordered_at)

                    {{ $order->ordered_at->format('h:i A') }}

                @else

                    {{ $order->created_at?->format('h:i A') ?? 'Not available' }}

                @endif

            </p>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- BUYER CONTACT SUMMARY --}}
    {{-- ================================================= --}}

    <div class="mt-5 rounded-lg border border-[#DDE9E8] bg-[#F7FAFA] p-4">

        <div class="flex items-start gap-3">

            <span class="material-symbols-outlined mt-0.5 text-[#056663]">
                contact_mail
            </span>

            <div>

                <p class="text-sm font-semibold text-gray-900">
                    Buyer Contact
                </p>

                <p class="mt-1 text-xs leading-5 text-gray-500">

                    @if($order->buyer?->email && $order->buyer?->phone)

                        The buyer can be contacted using the registered
                        email address and phone number.

                    @elseif($order->buyer?->email)

                        The buyer's registered email address is available.

                    @elseif($order->buyer?->phone)

                        The buyer's registered phone number is available.

                    @else

                        No contact information is available.

                    @endif

                </p>

            </div>

        </div>

    </div>

</div>

            {{-- ===================================================== --}}
            {{-- PRODUCT DELIVERY INFORMATION --}}
            {{-- ===================================================== --}}

            @if($isProduct)

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#E8F5F4]">

                            <span class="material-symbols-outlined text-[#056663]">
                                local_shipping
                            </span>

                        </div>

                        <div>

                            <h3 class="text-lg font-bold text-gray-900">
                                Delivery Information
                            </h3>

                            <p class="text-xs text-gray-500">
                                Product delivery details
                            </p>

                        </div>

                    </div>


                    <div class="mt-5 space-y-5">


                        {{-- ================================================= --}}
                        {{-- DELIVERY LOCATION --}}
                        {{-- ================================================= --}}

                        <div>

                            <p class="text-xs font-medium text-gray-500">
                                Delivery Location
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                {{ $deliveryLocation ?: 'Not provided' }}
                            </p>

                        </div>


                        {{-- ================================================= --}}
                        {{-- DELIVERY ADDRESS --}}
                        {{-- ================================================= --}}

                        <div>

                            <p class="text-xs font-medium text-gray-500">
                                Delivery Address
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $deliveryAddress ?: 'Not provided' }}
                            </p>

                        </div>


                        {{-- ================================================= --}}
                        {{-- REQUIRED DATE --}}
                        {{-- ================================================= --}}

                        <div>

                            <p class="text-xs font-medium text-gray-500">
                                Required Date
                            </p>

                            <p class="mt-1 text-sm text-gray-700">

                                @if($requiredDate)

                                    {{ \Carbon\Carbon::parse($requiredDate)->format('d M Y') }}

                                @else

                                    Not provided

                                @endif

                            </p>

                        </div>


                        {{-- ================================================= --}}
                        {{-- DELIVERY TIME --}}
                        {{-- ================================================= --}}

                        <div>

                            <p class="text-xs font-medium text-gray-500">
                                Delivery Time
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $deliveryTime ?: 'Not provided' }}
                            </p>

                        </div>


                        {{-- ================================================= --}}
                        {{-- BUYER REQUIREMENT --}}
                        {{-- ================================================= --}}

                        @if($deliveryMessage)

                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Buyer Requirement
                                </p>

                                <p class="mt-1 text-sm leading-6 text-gray-700">
                                    {{ $deliveryMessage }}
                                </p>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- FALLBACK NOTICE --}}
                        {{-- ================================================= --}}

                        @if(
                            !$order->delivery_location &&
                            !$order->requirement?->delivery_location &&
                            $order->listing?->city
                        )

                            <div class="rounded-lg border border-[#DDE9E8] bg-[#F7FAFA] px-4 py-3">

                                <div class="flex gap-2">

                                    <span class="material-symbols-outlined text-base text-[#056663]">
                                        info
                                    </span>

                                    <p class="text-xs leading-5 text-gray-500">
                                        The delivery location is currently displayed
                                        from the product listing because no specific
                                        delivery location was saved with this order.
                                    </p>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>


            {{-- ===================================================== --}}
            {{-- SERVICE INFORMATION --}}
            {{-- ===================================================== --}}

            @elseif($isService)

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Service Information
                    </h3>

                    <div class="mt-5 space-y-4">

                        @if($order->service_name)

                            <div>

                                <p class="text-xs text-gray-500">
                                    Service
                                </p>

                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $order->service_name }}
                                </p>

                            </div>

                        @endif


                        @if($order->service_duration)

                            <div>

                                <p class="text-xs text-gray-500">
                                    Duration
                                </p>

                                <p class="mt-1 text-sm text-gray-700">
                                    {{ $order->service_duration }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>


            {{-- ===================================================== --}}
            {{-- PROJECT INFORMATION --}}
            {{-- ===================================================== --}}

            @elseif($isProject)

                <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

                    <h3 class="text-lg font-bold text-gray-900">
                        Project Information
                    </h3>

                    <div class="mt-5 space-y-4">

                        @if($order->project_name)

                            <div>

                                <p class="text-xs text-gray-500">
                                    Project
                                </p>

                                <p class="mt-1 font-medium text-gray-900">
                                    {{ $order->project_name }}
                                </p>

                            </div>

                        @endif


                        @if($order->project_location)

                            <div>

                                <p class="text-xs text-gray-500">
                                    Location
                                </p>

                                <p class="mt-1 text-sm text-gray-700">
                                    {{ $order->project_location }}
                                </p>

                            </div>

                        @endif


                        @if($order->project_timeline)

                            <div>

                                <p class="text-xs text-gray-500">
                                    Timeline
                                </p>

                                <p class="mt-1 text-sm text-gray-700">
                                    {{ $order->project_timeline }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            @endif

        </div>


       {{-- =========================
     ORDER DETAILS
========================= --}}
<div class="bg-white rounded-2xl border border-[#DDE9E8] shadow-sm overflow-hidden">

    <div class="px-6 py-4 border-b border-[#DDE9E8] bg-[#F7FAFA]">
        <h3 class="text-lg font-semibold text-[#056663]">
            Order Details
        </h3>
    </div>

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            {{-- Order Number --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Order Number
                </p>

                <p class="mt-1 text-sm font-bold text-[#056663]">
                    {{ $order->order_number }}
                </p>
            </div>

            {{-- Category --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Category
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ ucfirst($order->category ?? 'Not provided') }}
                </p>
            </div>

            {{-- Product / Listing --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Product / Listing
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $order->product_name
                        ?? $order->listing?->title
                        ?? 'Not provided'
                    }}
                </p>
            </div>

            {{-- Quantity --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Quantity
                </p>

                <p class="mt-1 text-sm text-gray-900">
                    {{ $order->quantity ?? 'Not provided' }}
                </p>
            </div>

            {{-- Unit --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Unit
                </p>

                <p class="mt-1 text-sm text-gray-900">
                    {{ $order->unit ?? 'Not provided' }}
                </p>
            </div>

            {{-- Unit Price --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Unit Price
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    ₹{{ number_format((float) $order->unit_price, 2) }}
                </p>
            </div>

            {{-- Subtotal --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Subtotal
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900">
                    ₹{{ number_format((float) $order->subtotal, 2) }}
                </p>
            </div>

            {{-- Delivery Charges --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Delivery Charges
                </p>

                <p class="mt-1 text-sm text-gray-900">
                    ₹{{ number_format((float) ($order->delivery_charges ?? 0), 2) }}
                </p>
            </div>

            {{-- Other Charges --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Other Charges
                </p>

                <p class="mt-1 text-sm text-gray-900">
                    ₹{{ number_format((float) ($order->other_charges ?? 0), 2) }}
                </p>
            </div>

            {{-- Tax --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Tax
                </p>

                <p class="mt-1 text-sm text-gray-900">
                    ₹{{ number_format((float) ($order->tax ?? 0), 2) }}
                </p>
            </div>

            {{-- Total --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Total Amount
                </p>

                <p class="mt-1 text-lg font-bold text-[#056663]">
                    ₹{{ number_format((float) $order->total_amount, 2) }}
                </p>
            </div>

            {{-- Payment Status --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Payment Status
                </p>

                @php
                    $paymentStatus = strtolower($order->payment_status ?? 'pending');
                @endphp

                <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-semibold
                    {{ $paymentStatus === 'paid'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-yellow-100 text-yellow-700'
                    }}">
                    {{ ucfirst($paymentStatus) }}
                </span>
            </div>

            {{-- Order Status --}}
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                    Order Status
                </p>

                @php
                    $orderStatus = strtolower($order->order_status ?? 'pending');
                @endphp

                <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-semibold
                    bg-[#E8F5F4] text-[#056663]">
                    {{ ucfirst($orderStatus) }}
                </span>
            </div>

        </div>

        {{-- Listing Information --}}
        @if($order->listing)
            <div class="mt-6 pt-5 border-t border-[#DDE9E8]">

                <h4 class="text-sm font-semibold text-gray-900 mb-4">
                    Listing Information
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Listing ID
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            #{{ $order->listing->id }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Listing Type
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ ucfirst($order->listing->listing_type ?? 'Not provided') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Listed Price
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            ₹{{ number_format((float) ($order->listing->price ?? 0), 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Price Unit
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ $order->listing->price_unit ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            City
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ $order->listing->city ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            State
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ $order->listing->state ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Pincode
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ $order->listing->pincode ?? 'Not provided' }}
                        </p>
                    </div>

                </div>
            </div>
        @endif

        {{-- Seller Notes --}}
        @if($order->notes)
            <div class="mt-6 pt-5 border-t border-[#DDE9E8]">

                <h4 class="text-sm font-semibold text-gray-900 mb-2">
                    Order Notes
                </h4>

                <div class="p-4 rounded-xl bg-[#F7FAFA] text-sm text-gray-700">
                    {{ $order->notes }}
                </div>

            </div>
        @endif

    </div>
</div>
        {{-- ========================================================= --}}
        {{-- ORDER TIMELINE --}}
        {{-- ========================================================= --}}

        <div class="rounded-xl border border-[#DDE9E8] bg-white p-6">

            <h3 class="text-lg font-bold text-gray-900">
                Order Timeline
            </h3>

            <div class="mt-6 space-y-6">


                {{-- ORDER PLACED --}}

                @if($order->ordered_at)

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-sm font-bold text-[#056663]">
                            ✓
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900">
                                Order Placed
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $order->ordered_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                @endif


                {{-- CONFIRMED --}}

                @if($order->confirmed_at)

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-sm font-bold text-[#056663]">
                            ✓
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900">
                                Order Confirmed
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $order->confirmed_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                @endif


                {{-- PROCESSING --}}

                @if($order->processing_at)

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-sm font-bold text-[#056663]">
                            ✓
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900">

                                @if($isService)
                                    Service Started
                                @elseif($isProject)
                                    Project Started
                                @else
                                    Processing Started
                                @endif

                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $order->processing_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                @endif


                {{-- SHIPPED --}}

                @if($order->shipped_at)

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-sm font-bold text-[#056663]">
                            ✓
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900">
                                Order Shipped
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $order->shipped_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                @endif


                {{-- DELIVERED --}}

                @if($order->delivered_at)

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-sm font-bold text-[#056663]">
                            ✓
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900">
                                Order Delivered
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $order->delivered_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                @endif


                {{-- COMPLETED --}}

                @if($order->completed_at)

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-sm font-bold text-[#056663]">
                            ✓
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900">
                                Order Completed
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $order->completed_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                @endif


                {{-- REJECTED / CANCELLED --}}

                @if($order->cancelled_at)

                    <div class="flex gap-4">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-50 text-sm font-bold text-red-600">
                            !
                        </div>

                        <div>

                            <p class="font-semibold text-red-600">
                                Order Cancelled / Rejected
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $order->cancelled_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </div>

{{-- =========================
     ORDER SUMMARY
========================= --}}
<div class="bg-white rounded-2xl border border-[#DDE9E8] shadow-sm overflow-hidden">

    <div class="px-6 py-4 border-b border-[#DDE9E8] bg-[#F7FAFA]">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#056663]">
                    Order Summary
                </h3>

                <p class="text-xs text-gray-500 mt-1">
                    Complete pricing and transaction summary
                </p>
            </div>

            <span class="text-xs font-medium text-gray-500">
                {{ $order->order_number }}
            </span>
        </div>
    </div>

    <div class="p-6">

        @php
            $quantity = (float) ($order->quantity ?? 0);
            $unitPrice = (float) ($order->unit_price ?? 0);

            $subtotal = (float) ($order->subtotal ?? 0);
            $deliveryCharges = (float) ($order->delivery_charges ?? 0);
            $otherCharges = (float) ($order->other_charges ?? 0);
            $tax = (float) ($order->tax ?? 0);
            $totalAmount = (float) ($order->total_amount ?? 0);

            $calculatedSubtotal = $quantity * $unitPrice;
        @endphp

        {{-- Product / Item --}}
        <div class="pb-5 border-b border-[#DDE9E8]">

            <div class="flex items-start justify-between gap-4">

                <div class="flex-1">

                    <p class="text-sm font-semibold text-gray-900">
                        {{ $order->product_name
                            ?? $order->listing?->title
                            ?? 'Order Item'
                        }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ ucfirst($order->category ?? 'Marketplace Order') }}
                    </p>

                    @if($order->unit)
                        <p class="text-xs text-gray-500 mt-1">
                            Quantity:
                            <span class="font-medium text-gray-700">
                                {{ rtrim(rtrim(number_format($quantity, 2), '0'), '.') }}
                                {{ $order->unit }}
                            </span>
                        </p>
                    @endif

                </div>

                <div class="text-right">

                    <p class="text-sm font-semibold text-gray-900">
                        ₹{{ number_format($subtotal, 2) }}
                    </p>

                    <p class="text-xs text-gray-500">
                        Item Subtotal
                    </p>

                </div>

            </div>

        </div>


        {{-- Price Calculation --}}
        <div class="py-5 border-b border-[#DDE9E8] space-y-4">

            {{-- Unit Price --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">
                    Unit Price
                </span>

                <span class="text-sm font-medium text-gray-900">
                    ₹{{ number_format($unitPrice, 2) }}
                </span>
            </div>

            {{-- Quantity --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">
                    Quantity
                </span>

                <span class="text-sm font-medium text-gray-900">
                    {{ rtrim(rtrim(number_format($quantity, 2), '0'), '.') }}
                    {{ $order->unit ?? '' }}
                </span>
            </div>

            {{-- Subtotal --}}
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">
                    Subtotal
                </span>

                <span class="text-sm font-semibold text-gray-900">
                    ₹{{ number_format($subtotal, 2) }}
                </span>
            </div>

            {{-- Delivery Charges --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">
                    Delivery Charges
                </span>

                <span class="text-sm font-medium text-gray-900">
                    @if($deliveryCharges > 0)
                        ₹{{ number_format($deliveryCharges, 2) }}
                    @else
                        <span class="text-gray-400">₹0.00</span>
                    @endif
                </span>
            </div>

            {{-- Other Charges --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">
                    Other Charges
                </span>

                <span class="text-sm font-medium text-gray-900">
                    @if($otherCharges > 0)
                        ₹{{ number_format($otherCharges, 2) }}
                    @else
                        <span class="text-gray-400">₹0.00</span>
                    @endif
                </span>
            </div>

            {{-- Tax --}}
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">
                    Tax
                </span>

                <span class="text-sm font-medium text-gray-900">
                    @if($tax > 0)
                        ₹{{ number_format($tax, 2) }}
                    @else
                        <span class="text-gray-400">₹0.00</span>
                    @endif
                </span>
            </div>

        </div>


        {{-- Total --}}
        <div class="pt-5">

            <div class="rounded-xl bg-[#E8F5F4] border border-[#D1EBE9] p-5">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-semibold text-[#056663]">
                            Total Order Amount
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Including charges and tax
                        </p>
                    </div>

                    <p class="text-2xl font-bold text-[#056663]">
                        ₹{{ number_format($totalAmount, 2) }}
                    </p>

                </div>

            </div>

        </div>


        
{{-- Payment Information --}}
<div class="mt-5 pt-5 border-t border-[#DDE9E8]">

    @php
        $payment = $order->payments()->latest()->first();

        $paymentStatus = strtolower(
            $payment?->status ?? $order->payment_status ?? 'pending'
        );

        $paymentMethod = $payment?->payment_method;
    @endphp

    <h4 class="text-sm font-semibold text-gray-900 mb-4">
        Payment Information
    </h4>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Payment Status --}}
        <div class="flex items-center justify-between p-4 rounded-xl bg-[#F7FAFA]">
            <span class="text-sm text-gray-600">Payment Status</span>

            <span class="px-3 py-1 rounded-full text-xs font-semibold
                {{ $paymentStatus === 'paid'
                    ? 'bg-green-100 text-green-700'
                    : ($paymentStatus === 'failed'
                        ? 'bg-red-100 text-red-700'
                        : 'bg-yellow-100 text-yellow-700') }}">
                {{ ucfirst($paymentStatus) }}
            </span>
        </div>

        {{-- Payment Method --}}
        <div class="flex items-center justify-between p-4 rounded-xl bg-[#F7FAFA]">
            <span class="text-sm text-gray-600">Payment Method</span>

            <span class="text-sm font-medium text-gray-900">
                {{ $paymentMethod ?: 'Not selected' }}
            </span>
        </div>

        {{-- Payment Date --}}
        <div class="flex items-center justify-between p-4 rounded-xl bg-[#F7FAFA]">
            <span class="text-sm text-gray-600">Payment Date</span>

            <span class="text-sm text-gray-900">
                {{ $payment?->paid_at?->format('d M Y, h:i A') ?? 'Not available yet' }}
            </span>
        </div>

        {{-- Transaction Reference --}}
        <div class="flex items-center justify-between gap-3 p-4 rounded-xl bg-[#F7FAFA]">
            <span class="text-sm text-gray-600">Transaction Reference</span>

            <span class="break-all text-right text-sm text-gray-900">
                {{ $payment?->transaction_reference ?: 'Not provided' }}
            </span>
        </div>

    </div>

    {{-- Manual Payment Update Form --}}
    @if(!in_array($paymentStatus, ['paid', 'failed']))
        <form
            method="POST"
            action="{{ route('seller.orders.payment.update', $order->id) }}"
            class="mt-5 rounded-xl border border-[#DDE9E8] bg-white p-5"
        >
            @csrf
            @method('PATCH')

            <h5 class="text-sm font-semibold text-gray-900">
                Update Manual Payment
            </h5>

            <p class="mt-1 text-xs leading-5 text-gray-500">
                Record the payment method and reference. Mark as Paid only
                after the payment has actually been received.
            </p>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- Payment Method --}}
                <div>
                    <label for="payment_method"
                           class="mb-1 block text-sm font-medium text-gray-700">
                        Payment Method
                    </label>

                    <select
                        id="payment_method"
                        name="payment_method"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#056663] focus:ring-[#056663]"
                    >
                        <option value="">Select payment method</option>

                        <option value="Cash"
                            @selected(old('payment_method', $paymentMethod) === 'Cash')>
                            Cash
                        </option>

                        <option value="UPI"
                            @selected(old('payment_method', $paymentMethod) === 'UPI')>
                            UPI
                        </option>

                        <option value="Bank Transfer"
                            @selected(old('payment_method', $paymentMethod) === 'Bank Transfer')>
                            Bank Transfer
                        </option>
                    </select>

                    @error('payment_method')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Status --}}
                <div>
                    <label for="status"
                           class="mb-1 block text-sm font-medium text-gray-700">
                        Payment Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#056663] focus:ring-[#056663]"
                    >
                        <option value="pending"
                            @selected(old('status', $paymentStatus) === 'pending')>
                            Pending
                        </option>

                        <option value="paid"
                            @selected(old('status') === 'paid')>
                            Paid — payment received
                        </option>
                    </select>

                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Transaction Reference --}}
                <div>
                    <label for="transaction_reference"
                           class="mb-1 block text-sm font-medium text-gray-700">
                        Transaction Reference
                    </label>

                    <input
                        type="text"
                        id="transaction_reference"
                        name="transaction_reference"
                        value="{{ old('transaction_reference', $payment?->transaction_reference) }}"
                        maxlength="255"
                        placeholder="Optional for cash payments"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#056663] focus:ring-[#056663]"
                    >

                    @error('transaction_reference')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Date --}}
                <div>
                    <label for="payment_date"
                           class="mb-1 block text-sm font-medium text-gray-700">
                        Payment Date
                    </label>

                    <input
                        type="datetime-local"
                        id="payment_date"
                        name="payment_date"
                        value="{{ old('payment_date', $payment?->paid_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-[#056663] focus:ring-[#056663]"
                    >

                    @error('payment_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <button
                type="submit"
                class="mt-5 rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#045653]"
            >
                Save Payment Details
            </button>
        </form>
    @else
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            Payment is marked as {{ $paymentStatus }}. The payment details are read-only.
        </div>
    @endif

    <p class="mt-3 text-xs text-gray-500">
        Payment Gateway: Not Required
    </p>

</div>


        {{-- Order Date --}}
        <div class="mt-5 pt-5 border-t border-[#DDE9E8]">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-xs text-gray-500">
                        Order Placed
                    </p>

                    <p class="text-sm font-medium text-gray-900 mt-1">
                        @if($order->ordered_at)
                            {{ \Carbon\Carbon::parse($order->ordered_at)->format('d M Y, h:i A') }}
                        @elseif($order->created_at)
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        @else
                            Not available
                        @endif
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-xs text-gray-500">
                        Order ID
                    </p>

                    <p class="text-sm font-semibold text-[#056663] mt-1">
                        #{{ $order->order_number }}
                    </p>
                </div>

            </div>

        </div>

    </div>
</div>

</x-seller-layout>