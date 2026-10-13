@extends('admin.layouts.app')

@section('title', 'Transaction Details')
@section('page_title', 'Transaction Details')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | BASIC TRANSACTION DATA
    |--------------------------------------------------------------------------
    */
    $category = strtolower(trim((string) ($transaction->category ?? '')));
    $status = strtolower(trim((string) ($transaction->order_status ?? 'pending')));
    $paymentStatus = strtolower(trim((string) ($transaction->payment_status ?? 'pending')));

    $isProperty = in_array($category, ['property', 'properties'], true);
    $isLand = in_array($category, ['land'], true);

    $categoryLabel = match ($category) {
        'property', 'properties' => 'Property',
        'land' => 'Land',
        'product', 'products' => 'Products',
        'service', 'services' => 'Services',
        'project', 'projects' => 'Projects',
        default => ucfirst($category ?: 'Transaction'),
    };

    /*
    |--------------------------------------------------------------------------
    | SELLER NAME
    |--------------------------------------------------------------------------
    */
    $sellerName =
        $transaction->seller?->company_name
        ?? $transaction->seller?->business_name
        ?? $transaction->seller?->bussiness_name
        ?? $transaction->seller?->name
        ?? 'N/A';

    /*
    |--------------------------------------------------------------------------
    | STATUS CLASSES
    |--------------------------------------------------------------------------
    */
    $statusClass = match ($status) {
        'pending' => 'bg-yellow-100 text-yellow-700',
        'confirmed' => 'bg-blue-100 text-blue-700',
        'negotiation' => 'bg-purple-100 text-purple-700',
        'documentation' => 'bg-orange-100 text-orange-700',
        'processing' => 'bg-indigo-100 text-indigo-700',
        'shipped' => 'bg-blue-100 text-blue-700',
        'delivered' => 'bg-cyan-100 text-cyan-700',
        'completed' => 'bg-green-100 text-green-700',
        'cancelled', 'rejected' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-700',
    };

    /*
    |--------------------------------------------------------------------------
    | PAYMENT CLASSES
    |--------------------------------------------------------------------------
    */
    $paymentClass = match ($paymentStatus) {
        'paid', 'completed', 'success', 'successful' =>
            'bg-green-100 text-green-700',

        'failed', 'cancelled' =>
            'bg-red-100 text-red-700',

        'refunded', 'refund' =>
            'bg-purple-100 text-purple-700',

        'processing' =>
            'bg-blue-100 text-blue-700',

        default =>
            'bg-yellow-100 text-yellow-700',
    };

    /*
    |--------------------------------------------------------------------------
    | FINANCIAL CALCULATION
    |--------------------------------------------------------------------------
    */
    $subtotal = (float) ($transaction->subtotal ?? 0);
    $deliveryCharges = (float) ($transaction->delivery_charges ?? 0);
    $otherCharges = (float) ($transaction->other_charges ?? 0);
    $tax = (float) ($transaction->tax ?? 0);
    $totalAmount = (float) ($transaction->total_amount ?? 0);

    /*
    |--------------------------------------------------------------------------
    | REQUIREMENT
    |--------------------------------------------------------------------------
    */
    $requirement = $transaction->requirement ?? null;

    /*
    |--------------------------------------------------------------------------
    | CLOSED STATUS
    |--------------------------------------------------------------------------
    */
    $isClosed = in_array($status, [
        'completed',
        'cancelled',
        'rejected',
    ], true);
@endphp


<div class="min-h-screen bg-[#F7FAFA] p-6">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-[#056663]">
                Transaction Details
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Transaction #{{ $transaction->order_number ?? 'N/A' }}
            </p>
        </div>

        <a
            href="{{ route('admin.transactions.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-[#DDE9E8] bg-white px-4 py-2.5 text-sm font-medium text-[#056663] hover:bg-[#EEF7F6]"
        >
            ← Back to Transactions
        </a>

    </div>


    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}
    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Please correct the following:</p>

            <ul class="mt-2 list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- =========================================================
         TOP SUMMARY
    ========================================================== --}}
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        {{-- Transaction --}}
        <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Transaction
            </p>

            <p class="mt-2 font-bold text-[#056663]">
                {{ $transaction->order_number ?? 'N/A' }}
            </p>
        </div>

        {{-- Category --}}
        <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Category
            </p>

            <p class="mt-2 font-bold text-gray-800">
                {{ $categoryLabel }}
            </p>
        </div>

        {{-- Status --}}
        <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Transaction Status
            </p>

            <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                {{ ucfirst(str_replace('_', ' ', $status)) }}
            </span>
        </div>

        {{-- Total --}}
        <div class="rounded-xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Total Amount
            </p>

            <p class="mt-2 text-xl font-bold text-[#056663]">
                ₹{{ number_format($totalAmount, 2) }}
            </p>
        </div>

    </div>


    {{-- =========================================================
         MAIN LAYOUT
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- =====================================================
             LEFT CONTENT
        ====================================================== --}}
        <div class="space-y-6 lg:col-span-2">


            {{-- =================================================
                 TRANSACTION INFORMATION
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Transaction Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Basic transaction and marketplace information.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Transaction Number
                        </p>

                        <p class="mt-1 font-semibold text-[#056663]">
                            {{ $transaction->order_number ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Category
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $categoryLabel }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Transaction Status
                        </p>

                        <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Payment Status
                        </p>

                        <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentClass }}">
                            {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                        </span>
                    </div>

                    @if($transaction->ordered_at)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">
                                Transaction Created
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-800">
                                {{ $transaction->ordered_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    @endif

                    @if($transaction->enquiry_id)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">
                                Enquiry ID
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                #{{ $transaction->enquiry_id }}
                            </p>
                        </div>
                    @endif

                    @if($transaction->requirement_id)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">
                                Requirement ID
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                #{{ $transaction->requirement_id }}
                            </p>
                        </div>
                    @endif

                    @if($transaction->quotation_id)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">
                                Quotation ID
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                #{{ $transaction->quotation_id }}
                            </p>
                        </div>
                    @endif

                    @if($transaction->listing_id)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">
                                Listing ID
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                #{{ $transaction->listing_id }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>


            {{-- =================================================
                 BUYER INFORMATION
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">

                    <div class="flex items-center justify-between">

                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Buyer Information
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Customer associated with this transaction.
                            </p>
                        </div>

                        <span class="rounded-lg bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                            Buyer
                        </span>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Buyer Name
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $transaction->buyer?->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Buyer Email
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $transaction->buyer?->email ?? 'N/A' }}
                        </p>
                    </div>

                    @if($transaction->buyer?->phone)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">
                                Buyer Phone
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                {{ $transaction->buyer->phone }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Buyer ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            #{{ $transaction->buyer_id ?? 'N/A' }}
                        </p>
                    </div>

                </div>
            </div>


            {{-- =================================================
                 SELLER INFORMATION
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">

                    <div class="flex items-center justify-between">

                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Seller Information
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Seller or company associated with this transaction.
                            </p>
                        </div>

                        <span class="rounded-lg bg-[#E8F5F4] px-3 py-1 text-xs font-semibold text-[#056663]">
                            Seller
                        </span>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Company / Seller
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $sellerName }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Seller ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            #{{ $transaction->seller_id ?? 'N/A' }}
                        </p>
                    </div>

                    @if($transaction->seller?->user)
                        <div>
                            <p class="text-xs font-medium uppercase text-gray-400">
                                Seller Email
                            </p>

                            <p class="mt-1 font-medium text-gray-800">
                                {{ $transaction->seller->user->email ?? 'N/A' }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>


            {{-- =================================================
                 PROPERTY / LAND DETAILS
            ================================================== --}}
            @if($isProperty || $isLand)

                <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                    <div class="border-b border-[#DDE9E8] px-6 py-4">

                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $isLand ? 'Land Details' : 'Property Details' }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $isLand
                                ? 'Land information associated with this transaction.'
                                : 'Property information associated with this transaction.' }}
                        </p>

                    </div>

                    <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                        @if($isProperty)

                            <div>
                                <p class="text-xs font-medium uppercase text-gray-400">
                                    Property Type
                                </p>

                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $transaction->property_type ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase text-gray-400">
                                    Property Location
                                </p>

                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $transaction->property_location ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase text-gray-400">
                                    Property Area
                                </p>

                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $transaction->property_area ?? 'N/A' }}

                                    @if($transaction->property_area_unit)
                                        {{ $transaction->property_area_unit }}
                                    @endif
                                </p>
                            </div>

                        @elseif($isLand)

                            <div>
                                <p class="text-xs font-medium uppercase text-gray-400">
                                    Land Location
                                </p>

                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $transaction->land_location ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase text-gray-400">
                                    Land Area
                                </p>

                                <p class="mt-1 font-medium text-gray-800">
                                    {{ $transaction->land_area ?? 'N/A' }}

                                    @if($transaction->land_area_unit)
                                        {{ $transaction->land_area_unit }}
                                    @endif
                                </p>
                            </div>

                        @endif

                        @if($transaction->listing)

                            <div class="md:col-span-2">

                                <p class="text-xs font-medium uppercase text-gray-400">
                                    Marketplace Listing
                                </p>

                                <p class="mt-1 font-semibold text-[#056663]">
                                    {{ $transaction->listing->title ?? 'N/A' }}
                                </p>

                            </div>

                        @endif

                    </div>
                </div>

            @endif


            {{-- =================================================
                 REQUIREMENT INFORMATION
            ================================================== --}}
            @if($requirement)

                <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                    <div class="border-b border-[#DDE9E8] px-6 py-4">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Requirement Information
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Requirement submitted by the buyer.
                        </p>

                    </div>

                    <div class="p-6">

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            @if(!empty($requirement->name))
                                <div>
                                    <p class="text-xs font-medium uppercase text-gray-400">
                                        Requirement Name
                                    </p>

                                    <p class="mt-1 text-sm font-medium text-gray-800">
                                        {{ $requirement->name }}
                                    </p>
                                </div>
                            @endif

                            @if(!empty($requirement->requirement_type))
                                <div>
                                    <p class="text-xs font-medium uppercase text-gray-400">
                                        Requirement Type
                                    </p>

                                    <p class="mt-1 text-sm font-medium text-gray-800">
                                        {{ $requirement->requirement_type }}
                                    </p>
                                </div>
                            @endif

                            @if(!empty($requirement->quantity))
                                <div>
                                    <p class="text-xs font-medium uppercase text-gray-400">
                                        Quantity
                                    </p>

                                    <p class="mt-1 text-sm font-medium text-gray-800">
                                        {{ $requirement->quantity }}
                                        {{ $requirement->unit ?? '' }}
                                    </p>
                                </div>
                            @endif

                            @if(!empty($requirement->budget))
                                <div>
                                    <p class="text-xs font-medium uppercase text-gray-400">
                                        Budget
                                    </p>

                                    <p class="mt-1 text-sm font-medium text-gray-800">
                                        ₹{{ number_format((float) $requirement->budget, 2) }}
                                    </p>
                                </div>
                            @endif

                            @if(!empty($requirement->delivery_location))
                                <div class="md:col-span-2">
                                    <p class="text-xs font-medium uppercase text-gray-400">
                                        Requirement Location
                                    </p>

                                    <p class="mt-1 text-sm text-gray-700">
                                        {{ $requirement->delivery_location }}
                                    </p>
                                </div>
                            @endif

                            @if(!empty($requirement->required_date))
                                <div>
                                    <p class="text-xs font-medium uppercase text-gray-400">
                                        Required Date
                                    </p>

                                    <p class="mt-1 text-sm text-gray-700">
                                        {{ $requirement->required_date }}
                                    </p>
                                </div>
                            @endif

                            @if(!empty($requirement->message))
                                <div class="md:col-span-2">
                                    <p class="text-xs font-medium uppercase text-gray-400">
                                        Requirement Message
                                    </p>

                                    <div class="mt-2 rounded-lg bg-[#F7FAFA] p-4 text-sm leading-6 text-gray-700">
                                        {{ $requirement->message }}
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>

            @endif


            {{-- =================================================
                 FINANCIAL INFORMATION
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Financial Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Complete transaction amount calculation.
                    </p>

                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    <div>
                        <p class="text-sm text-gray-500">
                            Subtotal
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            ₹{{ number_format($subtotal, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Delivery Charges
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            ₹{{ number_format($deliveryCharges, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Other Charges
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            ₹{{ number_format($otherCharges, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Tax
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            ₹{{ number_format($tax, 2) }}
                        </p>
                    </div>

                    <div class="border-t border-gray-200 pt-4 md:col-span-2">

                        <div class="flex items-center justify-between">

                            <span class="text-lg font-bold text-gray-900">
                                Total Transaction Amount
                            </span>

                            <span class="text-2xl font-bold text-[#056663]">
                                ₹{{ number_format($totalAmount, 2) }}
                            </span>

                        </div>

                    </div>

                </div>
            </div>


            {{-- =================================================
                 PAYMENT INFORMATION
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Payment Information
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Payment status for this transaction.
                            </p>
                        </div>

                        <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $paymentClass }}">
                            {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                        </span>

                    </div>

                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Payment Status
                        </p>

                        <p class="mt-1 font-semibold text-gray-800">
                            {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Amount Payable
                        </p>

                        <p class="mt-1 font-semibold text-[#056663]">
                            ₹{{ number_format($totalAmount, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Payment Reference
                        </p>

                        <p class="mt-1 text-sm text-gray-700">
                            {{ $transaction->payment_reference ?? 'Not available' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Payment Method
                        </p>

                        <p class="mt-1 text-sm text-gray-700">
                            {{ $transaction->payment_method ?? 'Not available' }}
                        </p>
                    </div>

                    @if($paymentStatus === 'paid' || $paymentStatus === 'completed' || $paymentStatus === 'successful')

                        <div class="md:col-span-2">
                            <div class="rounded-lg border border-green-200 bg-green-50 p-4">

                                <p class="font-semibold text-green-800">
                                    Payment Completed
                                </p>

                                <p class="mt-1 text-sm text-green-700">
                                    Payment has been successfully recorded for this transaction.
                                </p>

                            </div>
                        </div>

                    @elseif($paymentStatus === 'failed')

                        <div class="md:col-span-2">
                            <div class="rounded-lg border border-red-200 bg-red-50 p-4">

                                <p class="font-semibold text-red-800">
                                    Payment Failed
                                </p>

                                <p class="mt-1 text-sm text-red-700">
                                    The payment associated with this transaction was unsuccessful.
                                </p>

                            </div>
                        </div>

                    @elseif($paymentStatus === 'refunded')

                        <div class="md:col-span-2">
                            <div class="rounded-lg border border-purple-200 bg-purple-50 p-4">

                                <p class="font-semibold text-purple-800">
                                    Payment Refunded
                                </p>

                                <p class="mt-1 text-sm text-purple-700">
                                    The payment amount has been marked as refunded.
                                </p>

                            </div>
                        </div>

                    @else

                        <div class="md:col-span-2">
                            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">

                                <p class="font-semibold text-yellow-800">
                                    Payment Pending
                                </p>

                                <p class="mt-1 text-sm text-yellow-700">
                                    Payment has not yet been marked as completed.
                                </p>

                            </div>
                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 NOTES
            ================================================== --}}
            @if(!empty($transaction->notes))

                <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                    <div class="border-b border-[#DDE9E8] px-6 py-4">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Transaction Notes
                        </h2>

                    </div>

                    <div class="p-6">

                        <div class="rounded-lg bg-[#F7FAFA] p-4 text-sm leading-6 text-gray-700">
                            {{ $transaction->notes }}
                        </div>

                    </div>

                </div>

            @endif


            {{-- =================================================
                 TRANSACTION REFERENCE INFORMATION
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Reference Information
                    </h2>

                </div>

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Order ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            #{{ $transaction->id }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Buyer ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            #{{ $transaction->buyer_id ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Seller ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            #{{ $transaction->seller_id ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Listing ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            #{{ $transaction->listing_id ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Enquiry ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $transaction->enquiry_id ? '#'.$transaction->enquiry_id : 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Requirement ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $transaction->requirement_id ? '#'.$transaction->requirement_id : 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase text-gray-400">
                            Quotation ID
                        </p>

                        <p class="mt-1 font-medium text-gray-800">
                            {{ $transaction->quotation_id ? '#'.$transaction->quotation_id : 'N/A' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT SIDEBAR
        ====================================================== --}}
        <div class="space-y-6">


            {{-- =================================================
                 CURRENT STATUS
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Transaction Status
                    </h2>
                </div>

                <div class="p-6 text-center">

                    <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </span>

                    <p class="mt-3 text-sm text-gray-500">
                        Current transaction status
                    </p>

                </div>

            </div>


            {{-- =================================================
                 PAYMENT STATUS CARD
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Payment Status
                    </h2>

                </div>

                <div class="p-6">

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-gray-500">
                            Current Payment
                        </span>

                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $paymentClass }}">
                            {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                        </span>

                    </div>

                    <div class="mt-5 border-t border-gray-100 pt-5">

                        <p class="text-xs uppercase text-gray-400">
                            Transaction Amount
                        </p>

                        <p class="mt-1 text-xl font-bold text-[#056663]">
                            ₹{{ number_format($totalAmount, 2) }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 TRANSACTION TIMELINE
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Transaction Timeline
                    </h2>

                </div>

                <div class="space-y-5 p-6">

                    {{-- Pending --}}
                    <div class="flex gap-3">

                        <div class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#E8F5F4] text-xs font-bold text-[#056663]">
                            ✓
                        </div>

                        <div>
                            <p class="font-medium text-gray-800">
                                Transaction Created
                            </p>

                            @if($transaction->ordered_at)
                                <p class="text-xs text-gray-500">
                                    {{ $transaction->ordered_at->format('d M Y, h:i A') }}
                                </p>
                            @else
                                <p class="text-xs text-gray-400">
                                    Not available
                                </p>
                            @endif
                        </div>

                    </div>


                    {{-- Confirmed --}}
                    <div class="flex gap-3">

                        <div class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full
                            {{ $transaction->confirmed_at ? 'bg-[#E8F5F4] text-[#056663]' : 'bg-gray-100 text-gray-400' }}
                            text-xs font-bold">
                            {{ $transaction->confirmed_at ? '✓' : '•' }}
                        </div>

                        <div>
                            <p class="font-medium text-gray-800">
                                Confirmed
                            </p>

                            @if($transaction->confirmed_at)
                                <p class="text-xs text-gray-500">
                                    {{ $transaction->confirmed_at->format('d M Y, h:i A') }}
                                </p>
                            @else
                                <p class="text-xs text-gray-400">
                                    Not completed
                                </p>
                            @endif
                        </div>

                    </div>


                    {{-- Negotiation --}}
                    <div class="flex gap-3">

                        <div class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full
                            {{ $transaction->negotiation_at ? 'bg-[#E8F5F4] text-[#056663]' : 'bg-gray-100 text-gray-400' }}
                            text-xs font-bold">
                            {{ $transaction->negotiation_at ? '✓' : '•' }}
                        </div>

                        <div>
                            <p class="font-medium text-gray-800">
                                Negotiation
                            </p>

                            @if($transaction->negotiation_at)
                                <p class="text-xs text-gray-500">
                                    {{ $transaction->negotiation_at->format('d M Y, h:i A') }}
                                </p>
                            @else
                                <p class="text-xs text-gray-400">
                                    Not completed
                                </p>
                            @endif
                        </div>

                    </div>


                    {{-- Documentation --}}
                    <div class="flex gap-3">

                        <div class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full
                            {{ $transaction->documentation_at ? 'bg-[#E8F5F4] text-[#056663]' : 'bg-gray-100 text-gray-400' }}
                            text-xs font-bold">
                            {{ $transaction->documentation_at ? '✓' : '•' }}
                        </div>

                        <div>
                            <p class="font-medium text-gray-800">
                                Documentation
                            </p>

                            @if($transaction->documentation_at)
                                <p class="text-xs text-gray-500">
                                    {{ $transaction->documentation_at->format('d M Y, h:i A') }}
                                </p>
                            @else
                                <p class="text-xs text-gray-400">
                                    Not completed
                                </p>
                            @endif
                        </div>

                    </div>


                    {{-- Completed --}}
                    <div class="flex gap-3">

                        <div class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full
                            {{ $transaction->completed_at ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}
                            text-xs font-bold">
                            {{ $transaction->completed_at ? '✓' : '•' }}
                        </div>

                        <div>
                            <p class="font-medium text-gray-800">
                                Completed
                            </p>

                            @if($transaction->completed_at)
                                <p class="text-xs text-gray-500">
                                    {{ $transaction->completed_at->format('d M Y, h:i A') }}
                                </p>
                            @else
                                <p class="text-xs text-gray-400">
                                    Not completed
                                </p>
                            @endif
                        </div>

                    </div>


                    {{-- Cancelled --}}
                    @if($status === 'cancelled' || $transaction->cancelled_at)

                        <div class="flex gap-3">

                            <div class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 text-xs font-bold">
                                !
                            </div>

                            <div>

                                <p class="font-medium text-red-700">
                                    Cancelled
                                </p>

                                @if($transaction->cancelled_at)
                                    <p class="text-xs text-gray-500">
                                        {{ $transaction->cancelled_at->format('d M Y, h:i A') }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-500">
                                        Transaction cancelled
                                    </p>
                                @endif

                            </div>

                        </div>

                    @endif

                </div>
            </div>


            {{-- =================================================
                 ADMIN ACTIONS
            ================================================== --}}
            <div class="rounded-xl border border-[#DDE9E8] bg-white shadow-sm">

                <div class="border-b border-[#DDE9E8] px-6 py-4">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Admin Actions
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Manage the transaction from here.
                    </p>

                </div>

                <div class="space-y-5 p-6">

                    @if(!$isClosed)

                        {{-- =====================================
                             STATUS UPDATE
                        ====================================== --}}
                        <form
                            method="POST"
                            action="{{ route('admin.transactions.status', $transaction->id) }}"
                        >

                            @csrf
                            @method('PATCH')

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Update Transaction Status
                            </label>

                            <select
                                name="order_status"
                                required
                                class="mb-3 w-full rounded-lg border border-[#DDE9E8] px-3 py-2.5 text-sm focus:border-[#056663] focus:ring-[#056663]"
                            >

                                @if($status === 'pending')

                                    <option value="confirmed">
                                        Confirmed
                                    </option>

                                @elseif($status === 'confirmed')

                                    <option value="negotiation">
                                        Negotiation
                                    </option>

                                @elseif($status === 'negotiation')

                                    <option value="documentation">
                                        Documentation
                                    </option>

                                @elseif($status === 'documentation')

                                    <option value="completed">
                                        Completed
                                    </option>

                                @endif

                            </select>

                            <textarea
                                name="admin_note"
                                rows="3"
                                maxlength="1000"
                                placeholder="Add admin note (optional)"
                                class="mb-3 w-full rounded-lg border border-[#DDE9E8] px-3 py-2.5 text-sm focus:border-[#056663] focus:ring-[#056663]"
                            ></textarea>

                            <button
                                type="submit"
                                class="w-full rounded-lg bg-[#056663] py-2.5 text-sm font-semibold text-white hover:bg-[#04514F]"
                            >
                                Update Transaction
                            </button>

                        </form>


                        {{-- =====================================
                             CANCEL
                        ====================================== --}}
                        <div class="border-t border-gray-100 pt-5">

                            <p class="mb-3 text-sm font-semibold text-gray-700">
                                Cancel Transaction
                            </p>

                            <form
                                method="POST"
                                action="{{ route('admin.transactions.cancel', $transaction->id) }}"
                                onsubmit="return confirm('Are you sure you want to cancel this transaction?');"
                            >

                                @csrf
                                @method('PATCH')

                                <textarea
                                    name="reason"
                                    rows="3"
                                    maxlength="1000"
                                    placeholder="Cancellation reason..."
                                    class="mb-3 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-500 focus:ring-red-500"
                                ></textarea>

                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-red-600 py-2.5 text-sm font-semibold text-white hover:bg-red-700"
                                >
                                    Cancel Transaction
                                </button>

                            </form>

                        </div>

                    @else

                        <div class="rounded-lg bg-gray-50 p-4 text-center">

                            <p class="text-sm font-medium text-gray-700">
                                No further actions are available.
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                This transaction is {{ $status }}.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


        </div>

    </div>

</div>

@endsection