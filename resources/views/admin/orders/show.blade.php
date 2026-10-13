
@extends('admin.layouts.app')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */
    $categoryKey = strtolower(trim((string) ($order->category ?? '')));

    $category = match ($categoryKey) {
        'product', 'products'       => 'Products',
        'service', 'services'       => 'Services',
        'project', 'projects'       => 'Projects',
        'property', 'properties'    => 'Properties',
        'land', 'lands'             => 'Land',
        default                     => ucfirst($categoryKey ?: 'Order'),
    };

    $isProduct  = $category === 'Products';
    $isService  = $category === 'Services';
    $isProject  = $category === 'Projects';
    $isProperty = $category === 'Properties';
    $isLand     = $category === 'Land';

    /*
    |--------------------------------------------------------------------------
    | ORDER STATUS
    |--------------------------------------------------------------------------
    */
    $currentStatus = strtolower(
        trim((string) ($order->order_status ?? 'pending'))
    );

    $statusClasses = match ($currentStatus) {
        'completed'     => 'bg-green-100 text-green-700',
        'delivered'     => 'bg-emerald-100 text-emerald-700',
        'shipped'       => 'bg-blue-100 text-blue-700',
        'processing'    => 'bg-indigo-100 text-indigo-700',
        'confirmed'     => 'bg-cyan-100 text-cyan-700',
        'negotiation'   => 'bg-orange-100 text-orange-700',
        'documentation' => 'bg-purple-100 text-purple-700',
        'cancelled', 'rejected' => 'bg-red-100 text-red-700',
        default         => 'bg-yellow-100 text-yellow-700',
    };

    /*
    |--------------------------------------------------------------------------
    | PAYMENT RECORD AND STATUS
    |--------------------------------------------------------------------------
    | Prefer the latest Payment record over the order's cached payment_status.
    */
    $paymentRecord = \App\Models\Payment::where('order_id', $order->id)
        ->latest('id')
        ->first();

    $paymentStatus = strtolower(trim((string) (
        $paymentRecord?->status
        ?? $order->payment_status
        ?? 'pending'
    )));

    $isPaymentPaid = in_array(
        $paymentStatus,
        ['paid', 'successful', 'success'],
        true
    );

    $paymentClasses = match ($paymentStatus) {
        'paid', 'successful', 'success'
            => 'bg-green-100 text-green-700',
        'processing', 'initiated'
            => 'bg-blue-100 text-blue-700',
        'failed'
            => 'bg-red-100 text-red-700',
        'refunded'
            => 'bg-purple-100 text-purple-700',
        'cancelled'
            => 'bg-gray-100 text-gray-700',
        default
            => 'bg-yellow-100 text-yellow-700',
    };

    /*
    |--------------------------------------------------------------------------
    | CATEGORY-SPECIFIC ORDER FLOW
    |--------------------------------------------------------------------------
    */
    $nextStatuses = match ($category) {
        'Products' => [
            'pending'    => ['confirmed'],
            'confirmed'  => ['processing'],
            'processing' => ['shipped'],
            'shipped'    => ['delivered'],
            'delivered'  => ['completed'],
            'completed'  => [],
        ],

        'Services', 'Projects' => [
            'pending'    => ['confirmed'],
            'confirmed'  => ['processing'],
            'processing' => ['completed'],
            'completed'  => [],
        ],

        'Properties', 'Land' => [
            'pending'       => ['confirmed'],
            'confirmed'     => ['negotiation'],
            'negotiation'   => ['documentation'],
            'documentation' => ['completed'],
            'completed'     => [],
        ],

        default => [],
    };

    $availableStatuses = $nextStatuses[$currentStatus] ?? [];

    $isClosed = in_array(
        $currentStatus,
        ['completed', 'cancelled', 'rejected'],
        true
    );

    /*
    |--------------------------------------------------------------------------
    | PAYMENT GATE
    |--------------------------------------------------------------------------
    | Do not offer order status updates until payment is verified.
    | Closed orders are handled separately.
    */
    if (!$isPaymentPaid && !$isClosed) {
        $availableStatuses = [];
    }

    $requirement = $order->requirement ?? null;

    $review = \App\Models\Review::where('order_id', $order->id)->first();

    $paymentReference = $paymentRecord?->transaction_reference
        ?? $order->payment_transaction_id
        ?? $order->transaction_id
        ?? null;

    $paymentMethod = $paymentRecord?->payment_method
        ?? $order->payment_method
        ?? null;

    $paymentDate = $paymentRecord?->paid_at
        ?? $order->paid_at
        ?? null;
@endphp

<div class="mx-auto max-w-7xl space-y-6">

    {{-- BACK --}}
    <div>
        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-[#056663] hover:underline">
            ← Back to Orders
        </a>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
            <p class="font-semibold">Please correct the following:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ORDER HEADER --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-gray-500">Order Number</p>

                <h1 class="mt-1 text-2xl font-bold text-gray-900">
                    {{ $order->order_number ?? 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                </h1>

                <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                    <span>
                        Placed on
                        {{ $order->ordered_at
                            ? \Illuminate\Support\Carbon::parse($order->ordered_at)->format('d M Y, h:i A')
                            : '—' }}
                    </span>

                    <span class="text-gray-300">|</span>

                    <span class="font-medium text-[#056663]">{{ $category }}</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $statusClasses }}">
                    {{ ucwords(str_replace('_', ' ', $currentStatus)) }}
                </span>

                <span class="text-2xl font-bold text-gray-900">
                    ₹{{ number_format((float) ($order->total_amount ?? 0), 2) }}
                </span>
            </div>
        </div>
    </div>

    {{-- BUYER + SELLER --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- BUYER --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Buyer Information</h2>
                <span class="rounded-lg bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                    Buyer
                </span>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">Name</p>
                    <p class="mt-1 font-medium text-gray-900">
                        {{ $order->buyer?->name ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">Buyer ID</p>
                    <p class="mt-1 font-medium text-gray-900">#{{ $order->buyer_id ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">Email</p>
                    <p class="mt-1 break-all text-sm text-gray-700">
                        {{ $order->buyer?->email ?? '—' }}
                    </p>
                </div>

                @if($order->buyer?->phone)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Phone</p>
                        <p class="mt-1 text-sm text-gray-700">{{ $order->buyer->phone }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- SELLER --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Seller Information</h2>
                <span class="rounded-lg bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                    Seller
                </span>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">Company</p>
                    <p class="mt-1 font-medium text-gray-900">
                        {{ $order->seller?->company_name
                            ?? $order->seller?->business_name
                            ?? $order->seller?->bussiness_name
                            ?? $order->seller?->name
                            ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">Seller ID</p>
                    <p class="mt-1 font-medium text-gray-900">#{{ $order->seller_id ?? '—' }}</p>
                </div>

                @if($order->seller?->user)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Email</p>
                        <p class="mt-1 break-all text-sm text-gray-700">
                            {{ $order->seller->user->email }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ADMIN STATUS MANAGEMENT --}}
    <div class="rounded-2xl border border-[#B9DDDA] bg-[#F7FAFA] p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Admin Order Status Management</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Update the order status when administrative intervention is required.
                </p>
            </div>

            <span class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-[#056663] shadow-sm">
                Current: {{ ucwords(str_replace('_', ' ', $currentStatus)) }}
            </span>
        </div>

        @if(!$isClosed && !$isPaymentPaid)
            <div class="mt-6 rounded-xl border border-yellow-200 bg-yellow-50 p-5">
                <p class="font-semibold text-yellow-800">Payment Verification Required</p>
                <p class="mt-1 text-sm text-yellow-700">
                    The order status cannot be advanced until the payment has been verified and marked as paid.
                </p>

                @if($paymentRecord)
                    <a href="{{ route('admin.payments.show', $paymentRecord->id) }}"
                       class="mt-4 inline-flex items-center rounded-lg bg-[#056663] px-4 py-2 text-sm font-semibold text-white hover:bg-[#045653]">
                        Review Payment
                    </a>
                @else
                    <a href="{{ route('admin.payments.index') }}"
                       class="mt-4 inline-flex items-center rounded-lg bg-[#056663] px-4 py-2 text-sm font-semibold text-white hover:bg-[#045653]">
                        Open Payment Management
                    </a>
                @endif
            </div>

        @elseif(!$isClosed && count($availableStatuses) > 0)
            <form method="POST"
                  action="{{ route('admin.orders.status', $order->id) }}"
                  class="mt-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Update Status
                        </label>

                        <select name="order_status"
                                required
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-[#056663] focus:ring-[#056663]">
                            <option value="">Select next status</option>
                            @foreach($availableStatuses as $status)
                                <option value="{{ $status }}" @selected(old('order_status') === $status)>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Admin Note
                            <span class="font-normal text-gray-400">(Optional)</span>
                        </label>

                        <input type="text"
                               name="admin_note"
                               maxlength="1000"
                               value="{{ old('admin_note') }}"
                               placeholder="Add a note about this status update..."
                               class="w-full rounded-lg border-gray-300 text-sm focus:border-[#056663] focus:ring-[#056663]">
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-xs text-gray-500">
                        Status options are based on the selected order category.
                    </p>

                    <button type="submit"
                            class="rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#045653]">
                        Update Order Status
                    </button>
                </div>
            </form>

        @elseif(!$isClosed)
            <p class="mt-5 rounded-lg bg-white p-4 text-sm text-gray-600">
                No further status changes are available for this order.
            </p>
        @else
            <p class="mt-5 rounded-lg bg-white p-4 text-sm font-medium text-gray-600">
                This order is <strong>{{ ucwords(str_replace('_', ' ', $currentStatus)) }}</strong>.
                Status updates are no longer available.
            </p>
        @endif
    </div>

    {{-- ORDER INFORMATION --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900">Order Information</h2>
        <p class="mt-1 text-sm text-gray-500">Complete identification and transaction details.</p>

        <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Order ID</p>
                <p class="mt-1 font-semibold text-gray-900">#{{ $order->id }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Order Number</p>
                <p class="mt-1 font-semibold text-[#056663]">{{ $order->order_number ?? '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Category</p>
                <p class="mt-1 font-medium text-gray-900">{{ $category }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Buyer ID</p>
                <p class="mt-1 text-gray-900">#{{ $order->buyer_id ?? '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Seller ID</p>
                <p class="mt-1 text-gray-900">#{{ $order->seller_id ?? '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Listing ID</p>
                <p class="mt-1 text-gray-900">{{ $order->listing_id ? '#'.$order->listing_id : '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Enquiry ID</p>
                <p class="mt-1 text-gray-900">{{ $order->enquiry_id ? '#'.$order->enquiry_id : '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Requirement ID</p>
                <p class="mt-1 text-gray-900">{{ $order->requirement_id ? '#'.$order->requirement_id : '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Quotation ID</p>
                <p class="mt-1 text-gray-900">{{ $order->quotation_id ? '#'.$order->quotation_id : '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Order Date</p>
                <p class="mt-1 text-gray-900">
                    {{ $order->ordered_at
                        ? \Illuminate\Support\Carbon::parse($order->ordered_at)->format('d M Y, h:i A')
                        : '—' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Current Status</p>
                <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                    {{ ucwords(str_replace('_', ' ', $currentStatus)) }}
                </span>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Payment Status</p>
                <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentClasses }}">
                    {{ ucwords(str_replace('_', ' ', $paymentStatus)) }}
                </span>
            </div>
        </div>
    </div>

    {{-- CATEGORY DETAILS --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900">
            {{ $isProduct ? 'Product Details' : ($isService ? 'Service Details' : ($isProject ? 'Project Details' : ($isProperty ? 'Property Details' : 'Land Details'))) }}
        </h2>

        @if($order->listing)
            <div class="mt-5 rounded-xl bg-[#F7FAFA] p-4">
                <p class="text-xs font-medium uppercase text-gray-400">Marketplace Listing</p>
                <p class="mt-1 font-semibold text-[#056663]">{{ $order->listing->title ?? 'Listing' }}</p>

                @if($order->listing->city || $order->listing->state)
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $order->listing->city ?? '' }}@if($order->listing->city && $order->listing->state), @endif{{ $order->listing->state ?? '' }}
                    </p>
                @endif
            </div>
        @endif

        <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">

            @if($isProduct)
                @if($order->product_name)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Product</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->product_name }}</p>
                    </div>
                @endif

                @if($order->product_brand)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Brand</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->product_brand }}</p>
                    </div>
                @endif
            @endif

            @if($isService)
                @if($order->service_name)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Service</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->service_name }}</p>
                    </div>
                @endif

                @if($order->service_duration)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Duration</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->service_duration }}</p>
                    </div>
                @endif
            @endif

            @if($isProject)
                @if($order->project_name)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Project</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->project_name }}</p>
                    </div>
                @endif

                @if($order->project_location)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Project Location</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->project_location }}</p>
                    </div>
                @endif

                @if($order->project_timeline)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Project Timeline</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->project_timeline }}</p>
                    </div>
                @endif
            @endif

            @if($isProperty)
                @if($order->property_type)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Property Type</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->property_type }}</p>
                    </div>
                @endif

                @if($order->property_area)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Property Area</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $order->property_area }} {{ $order->property_area_unit ?? '' }}
                        </p>
                    </div>
                @endif

                @if($order->property_location)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Property Location</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->property_location }}</p>
                    </div>
                @endif
            @endif

            @if($isLand)
                @if($order->land_area)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Land Area</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $order->land_area }} {{ $order->land_area_unit ?? '' }}
                        </p>
                    </div>
                @endif

                @if($order->land_location)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Land Location</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $order->land_location }}</p>
                    </div>
                @endif
            @endif

            @if($order->quantity)
                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">Quantity</p>
                    <p class="mt-1 font-medium text-gray-900">{{ $order->quantity }} {{ $order->unit ?? '' }}</p>
                </div>
            @endif

            @if($order->unit_price !== null)
                <div>
                    <p class="text-xs font-medium uppercase text-gray-400">Unit Price</p>
                    <p class="mt-1 font-medium text-gray-900">₹{{ number_format((float) $order->unit_price, 2) }}</p>
                </div>
            @endif

            @if($requirement?->message)
                <div class="md:col-span-2 lg:col-span-3">
                    <p class="text-xs font-medium uppercase text-gray-400">Requirement / Description</p>
                    <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $requirement->message }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- REQUIREMENT INFORMATION --}}
    @if($requirement)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">Requirement Information</h2>
            <p class="mt-1 text-sm text-gray-500">Buyer requirement associated with this transaction.</p>

            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @if($requirement->name ?? null)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Requirement Name</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $requirement->name }}</p>
                    </div>
                @endif

                @if($requirement->requirement_type ?? null)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Requirement Type</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ ucfirst($requirement->requirement_type) }}</p>
                    </div>
                @endif

                @if($requirement->quantity ?? null)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Required Quantity</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">{{ $requirement->quantity }} {{ $requirement->unit ?? '' }}</p>
                    </div>
                @endif

                @if($requirement->budget ?? null)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Budget</p>
                        <p class="mt-1 font-semibold text-gray-900">₹{{ number_format((float) $requirement->budget, 2) }}</p>
                    </div>
                @endif

                @if($requirement->delivery_location ?? null)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Required Location</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $requirement->delivery_location }}</p>
                    </div>
                @endif

                @if($requirement->required_date ?? null)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Required Date</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ \Illuminate\Support\Carbon::parse($requirement->required_date)->format('d M Y') }}
                        </p>
                    </div>
                @endif

                @if($requirement->status ?? null)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Requirement Status</p>
                        <p class="mt-1 font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $requirement->status)) }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- FINANCIAL SUMMARY --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900">Financial Summary</h2>
        <p class="mt-1 text-sm text-gray-500">Complete transaction amount calculation.</p>

        <div class="mt-6 max-w-2xl space-y-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-medium text-gray-900">₹{{ number_format((float) ($order->subtotal ?? 0), 2) }}</span>
            </div>

            @if($isProduct)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Delivery Charges</span>
                    <span class="font-medium text-gray-900">₹{{ number_format((float) ($order->delivery_charges ?? 0), 2) }}</span>
                </div>
            @endif

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Other Charges</span>
                <span class="font-medium text-gray-900">₹{{ number_format((float) ($order->other_charges ?? 0), 2) }}</span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Tax</span>
                <span class="font-medium text-gray-900">₹{{ number_format((float) ($order->tax ?? 0), 2) }}</span>
            </div>

            <div class="flex justify-between border-t border-gray-200 pt-4">
                <span class="font-bold text-gray-900">Total Amount</span>
                <span class="text-2xl font-bold text-[#056663]">₹{{ number_format((float) ($order->total_amount ?? 0), 2) }}</span>
            </div>
        </div>
    </div>

    {{-- PAYMENT INFORMATION --}}
    <div class="rounded-2xl border border-[#B9DDDA] bg-[#F7FAFA] p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Payment Information</h2>
                <p class="mt-1 text-sm text-gray-500">Payment status and transaction information for this order.</p>
            </div>

            <span class="rounded-full px-4 py-2 text-sm font-semibold {{ $paymentClasses }}">
                {{ ucwords(str_replace('_', ' ', $paymentStatus)) }}
            </span>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Payment Status</p>
                <p class="mt-1 font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $paymentStatus)) }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Payment Amount</p>
                <p class="mt-1 font-semibold text-gray-900">
                    ₹{{ number_format((float) ($paymentRecord?->amount ?? $order->total_amount ?? 0), 2) }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Payment Reference</p>
                <p class="mt-1 break-all font-medium text-gray-900">
                    {{ $paymentReference ?: 'Not available' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Payment Method</p>
                <p class="mt-1 font-medium text-gray-900">{{ $paymentMethod ?: 'Not specified' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Payment Date</p>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $paymentDate
                        ? \Illuminate\Support\Carbon::parse($paymentDate)->format('d M Y, h:i A')
                        : 'Not paid yet' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase text-gray-400">Payment Record ID</p>
                <p class="mt-1 font-medium text-gray-900">
                    {{ $paymentRecord ? '#'.$paymentRecord->id : 'No payment record' }}
                </p>
            </div>
        </div>

        @if(!$paymentRecord)
            <div class="mt-6 rounded-xl border border-yellow-200 bg-yellow-50 p-4">
                <p class="font-semibold text-yellow-800">Payment Record Not Found</p>
                <p class="mt-1 text-sm text-yellow-700">
                    No payment record is linked to this order. Create or submit a payment through your payment workflow before advancing the order.
                </p>
            </div>
        @elseif(!$isPaymentPaid && !$isClosed)
            <div class="mt-6 rounded-xl border border-yellow-200 bg-yellow-50 p-4">
                <p class="font-semibold text-yellow-800">Payment Awaiting Verification</p>
                <p class="mt-1 text-sm text-yellow-700">
                    Check the submitted payment method and reference, and verify receipt before approving the payment.
                </p>

                <a href="{{ route('admin.payments.show', $paymentRecord->id) }}"
                   class="mt-4 inline-flex items-center rounded-lg bg-[#056663] px-4 py-2 text-sm font-semibold text-white hover:bg-[#045653]">
                    Review Payment
                </a>
            </div>
        @elseif($isPaymentPaid)
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4">
                <p class="font-semibold text-green-800">Payment Verified</p>
                <p class="mt-1 text-sm text-green-700">
                    The payment record is marked as paid.
                </p>
            </div>
        @elseif($paymentStatus === 'failed')
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4">
                <p class="font-semibold text-red-800">Payment Failed</p>
                <p class="mt-1 text-sm text-red-700">
                    The payment attempt was unsuccessful. Verify any resubmitted payment before continuing.
                </p>
            </div>
        @elseif($paymentStatus === 'refunded')
            <div class="mt-6 rounded-xl border border-purple-200 bg-purple-50 p-4">
                <p class="font-semibold text-purple-800">Payment Refunded</p>
                <p class="mt-1 text-sm text-purple-700">
                    The payment record is marked as refunded.
                </p>
            </div>
        @endif
    </div>

    {{-- DELIVERY / LOCATION --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">
                {{ $isProduct ? 'Delivery Information' : 'Transaction Location / Notes' }}
            </h2>

            <div class="mt-5 space-y-5">
                @if($order->delivery_address)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Delivery Address</p>
                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $order->delivery_address }}</p>
                    </div>
                @endif

                @if($order->delivery_location)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Delivery Location</p>
                        <p class="mt-1 text-sm text-gray-700">{{ $order->delivery_location }}</p>
                    </div>
                @endif

                @if($order->delivery_time)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Delivery / Service Time</p>
                        <p class="mt-1 text-sm text-gray-700">{{ $order->delivery_time }}</p>
                    </div>
                @endif

                @if($order->shipped_at)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Shipped At</p>
                        <p class="mt-1 text-sm text-gray-700">
                            {{ \Illuminate\Support\Carbon::parse($order->shipped_at)->format('d M Y, h:i A') }}
                        </p>
                    </div>
                @endif

                @if($order->delivered_at)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Delivered At</p>
                        <p class="mt-1 text-sm text-gray-700">
                            {{ \Illuminate\Support\Carbon::parse($order->delivered_at)->format('d M Y, h:i A') }}
                        </p>
                    </div>
                @endif

                @if($order->notes)
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Order Notes</p>
                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif

                @if(!$order->delivery_address && !$order->delivery_location && !$order->delivery_time && !$order->notes)
                    <p class="text-sm text-gray-500">
                        No additional delivery or transaction information provided.
                    </p>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">Listing Information</h2>

            @if($order->listing)
                <div class="mt-5 space-y-5">
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Listing Title</p>
                        <p class="mt-1 font-semibold text-[#056663]">{{ $order->listing->title ?? '—' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Listing ID</p>
                        <p class="mt-1 text-sm text-gray-700">#{{ $order->listing->id }}</p>
                    </div>

                    @if($order->listing->city || $order->listing->state)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Location</p>
                            <p class="mt-1 text-sm text-gray-700">
                                {{ $order->listing->city ?? '' }}
                                @if($order->listing->city && $order->listing->state), @endif
                                {{ $order->listing->state ?? '' }}
                                @if($order->listing->pincode)- {{ $order->listing->pincode }}@endif
                            </p>
                        </div>
                    @endif

                    @if($order->listing->price)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">Listing Price</p>
                            <p class="mt-1 font-semibold text-gray-900">
                                ₹{{ number_format((float) $order->listing->price, 2) }}
                            </p>
                        </div>
                    @endif
                </div>
            @else
                <p class="mt-5 text-sm text-gray-500">No listing information available.</p>
            @endif
        </div>
    </div>

    {{-- ORDER TIMELINE --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900">Order Timeline</h2>
        <p class="mt-1 text-sm text-gray-500">
            Complete progress history of this {{ strtolower($category) }} transaction.
        </p>

        <div class="mt-7 space-y-6">
            @if($order->ordered_at)
                <div class="flex gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-[#056663]">✓</div>
                    <div>
                        <p class="font-semibold text-gray-900">Order Placed</p>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ \Illuminate\Support\Carbon::parse($order->ordered_at)->format('d M Y, h:i A') }}
                        </p>
                    </div>
                </div>
            @endif

            @if($isPaymentPaid)
                <div class="flex gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-700">₹</div>
                    <div>
                        <p class="font-semibold text-gray-900">Payment Verified</p>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $paymentDate
                                ? \Illuminate\Support\Carbon::parse($paymentDate)->format('d M Y, h:i A')
                                : 'Payment marked as paid' }}
                        </p>
                    </div>
                </div>
            @endif

            @foreach([
                'confirmed_at' => 'Order Confirmed',
                'processing_at' => ($isService ? 'Service Started' : ($isProject ? 'Project Started' : 'Processing Started')),
                'negotiation_at' => 'Negotiation Started',
                'documentation_at' => 'Documentation Started',
                'shipped_at' => 'Order Shipped',
                'delivered_at' => 'Order Delivered',
                'completed_at' => ($isService ? 'Service Completed' : ($isProject ? 'Project Completed' : ($isProperty ? 'Property Transaction Completed' : ($isLand ? 'Land Transaction Completed' : 'Order Completed')))),
                'cancelled_at' => 'Order Cancelled',
            ] as $dateField => $timelineLabel)
                @if($order->{$dateField})
                    @if($dateField !== 'shipped_at' && $dateField !== 'delivered_at' || $isProduct)
                        <div class="flex gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-[#056663]">
                                ✓
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $timelineLabel }}</p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ \Illuminate\Support\Carbon::parse($order->{$dateField})->format('d M Y, h:i A') }}
                                </p>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach

            @if($currentStatus === 'rejected')
                <div class="flex gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700">!</div>
                    <div>
                        <p class="font-semibold text-red-700">Order Rejected</p>
                        <p class="mt-1 text-sm text-gray-500">This order was rejected.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- REVIEW & RATING --}}
    @if($currentStatus === 'completed')
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Review & Rating</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Buyer feedback associated with this completed transaction.
                    </p>
                </div>

                @if($review)
                    @php
                        $reviewStatus = strtolower(trim((string) ($review->status ?? 'pending')));
                    @endphp

                    <span class="rounded-full px-4 py-2 text-xs font-semibold
                        {{ $reviewStatus === 'approved'
                            ? 'bg-green-100 text-green-700'
                            : ($reviewStatus === 'rejected'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($reviewStatus) }}
                    </span>
                @endif
            </div>

            @if($review)
                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Rating</p>
                        <div class="mt-2 flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= (int) $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-xl">★</span>
                            @endfor
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Reviewed By</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $review->buyer?->name ?? $order->buyer?->name ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">Review Date</p>
                        <p class="mt-1 text-sm text-gray-700">
                            {{ $review->created_at
                                ? \Illuminate\Support\Carbon::parse($review->created_at)->format('d M Y, h:i A')
                                : '—' }}
                        </p>
                    </div>
                </div>

                @if($review->review ?? $review->comment ?? null)
                    <div class="mt-6 rounded-xl bg-[#F7FAFA] p-5">
                        <p class="text-xs font-medium uppercase text-gray-400">Review</p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                            {{ $review->review ?? $review->comment }}
                        </p>
                    </div>
                @endif
            @else
                <div class="mt-6 rounded-xl border border-yellow-200 bg-yellow-50 p-5">
                    <p class="font-semibold text-yellow-800">No Review Submitted</p>
                    <p class="mt-1 text-sm text-yellow-700">
                        The buyer has not submitted a review for this transaction yet.
                    </p>
                </div>
            @endif
        </div>
    @endif

    {{-- ADMIN CANCELLATION --}}
    @if(!$isClosed)
        <div class="rounded-2xl border border-red-200 bg-red-50 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-bold text-red-800">Admin Intervention</h2>
                    <p class="mt-1 text-sm text-red-700">Cancel this order only when necessary.</p>
                </div>

                <button type="button"
                        onclick="document.getElementById('cancelOrderModal').classList.remove('hidden')"
                        class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                    Cancel Order
                </button>
            </div>
        </div>
    @endif
</div>

{{-- CANCELLATION MODAL --}}
<div id="cancelOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/40 p-4">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Cancel Order</h2>

                <button type="button"
                        onclick="document.getElementById('cancelOrderModal').classList.add('hidden')"
                        class="text-2xl text-gray-400 hover:text-gray-600">
                    &times;
                </button>
            </div>

            <p class="mt-2 text-sm text-gray-500">
                Please provide a reason for cancelling this order.
            </p>

            <form method="POST"
                  action="{{ route('admin.orders.cancel', $order->id) }}"
                  class="mt-5">
                @csrf
                @method('PATCH')

                <textarea name="reason"
                          rows="4"
                          required
                          maxlength="1000"
                          placeholder="Enter cancellation reason..."
                          class="w-full rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">{{ old('reason') }}</textarea>

                <div class="mt-5 flex justify-end gap-3">
                    <button type="button"
                            onclick="document.getElementById('cancelOrderModal').classList.add('hidden')"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Close
                    </button>

                    <button type="submit"
                            class="rounded-lg bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                        Confirm Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
