{{-- resources/views/seller/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Marketplace') }} - Seller
    </title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js'
    ])

</head>


<body class="bg-[#F5F9F8] font-sans antialiased">

    <div class="min-h-screen">


        {{-- =====================================================
             FIXED HEADER
        ====================================================== --}}

        <header
            class="fixed inset-x-0 top-0 z-50
                   h-20 border-b border-[#D3E3E0]
                   bg-white/95 backdrop-blur
                   shadow-sm">

            <div
                class="flex h-full items-center
                       px-4 sm:px-6 lg:px-8">

                {{-- Sidebar Toggle --}}
                <button
                    type="button"
                    id="sidebarToggle"
                    aria-label="Toggle sidebar"
                    aria-expanded="true"
                    class="mr-3 rounded-lg p-2
                           text-[#00665F]
                           transition
                           hover:bg-[#EAF5F3]">

                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                    </svg>

                </button>


                {{-- Page Header --}}
                <div class="min-w-0 flex-1">

                    {{ $header ?? '' }}

                </div>


                {{-- =====================================================
     NOTIFICATIONS + USER AREA
====================================================== --}}

                <div class="flex items-center gap-3">

                    {{-- =================================================
         NOTIFICATIONS
    ================================================== --}}

                    @auth

                    @php
                    $unreadNotificationCount = auth()->user()
                    ->unreadNotifications()
                    ->count();
                    @endphp

                    <a
                        href="{{ route('seller.notifications.index') }}"
                        title="Notifications"
                        class="relative flex h-10 w-10
           items-center justify-center
           rounded-xl
           text-[#718A86]
           transition
           hover:bg-[#EAF5F3]
           hover:text-[#00665F]">

                        <span class="material-symbols-outlined text-[23px]">
                            notifications
                        </span>

                        @if($unreadNotificationCount > 0)

                        <span
                            class="absolute -right-1 -top-1
                   flex h-5 min-w-[20px]
                   items-center justify-center
                   rounded-full
                   bg-red-500
                   px-1
                   text-[10px]
                   font-bold
                   text-white
                   ring-2 ring-white">

                            {{ $unreadNotificationCount > 99
                ? '99+'
                : $unreadNotificationCount }}

                        </span>

                        @endif

                    </a>

                    @endauth


                    {{-- =================================================
         SELLER USER AREA
    ================================================== --}}

                    <div class="hidden items-center gap-3 sm:flex">

                        <div class="text-right">

                            <p
                                class="text-sm font-semibold
                       text-[#123B38]">
                                {{ auth()->user()->name }}
                            </p>

                            <p
                                class="text-xs
                       text-[#718A86]">
                                Seller
                            </p>

                        </div>


                        {{-- User Avatar --}}
                        <div
                            class="flex h-10 w-10 shrink-0
                   items-center justify-center
                   rounded-full
                   bg-[#EAF5F3]">

                            <svg
                                class="h-5 w-5 text-[#00665F]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0
                       4 4 0 018 0z
                       M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                            </svg>

                        </div>

                    </div>

                </div>
            </div>

        </header>


        {{-- =====================================================
             PAGE AREA
        ====================================================== --}}

        <div
            class="flex min-h-screen
                   pt-20">


            {{-- =================================================
                 SIDEBAR
            ================================================== --}}

            <aside
                id="sellerSidebar"
                class="fixed inset-y-0 left-0
                       z-40 flex w-64
                       -translate-x-full flex-col
                       border-r border-[#D3E3E0]
                       bg-white
                       transition-all duration-300
                       lg:sticky lg:top-20
                       lg:h-[calc(100vh-80px)]
                       lg:translate-x-0">


                {{-- =================================================
                     SIDEBAR HEADER
                ================================================== --}}

                <div
                    class="flex h-20 shrink-0 items-center
                           justify-between
                           border-b border-[#D3E3E0]
                           px-4">

                    <div class="sidebar-text min-w-0">

                        <h2
                            class="text-lg font-bold
                                   text-[#123B38]">

                            Marketplace

                        </h2>

                        <p
                            class="text-xs text-[#718A86]">

                            Seller Panel

                        </p>

                    </div>


                    {{-- Mobile Close --}}
                    <button
                        type="button"
                        id="sidebarClose"
                        aria-label="Close sidebar"
                        class="rounded-lg p-2
                               text-[#4F6663]
                               transition
                               hover:bg-[#EAF5F3]
                               hover:text-[#00665F]
                               lg:hidden">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>


                {{-- =================================================
                     SELLER INFORMATION
                ================================================== --}}

                <div
                    class="shrink-0 border-b
                           border-[#D3E3E0] p-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0
                                   items-center justify-center
                                   rounded-full
                                   bg-[#EAF5F3]">

                            <svg
                                class="h-5 w-5 text-[#00665F]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7" />

                            </svg>

                        </div>


                        <div class="sidebar-text min-w-0">

                            <p
                                class="truncate text-sm
                                       font-semibold
                                       text-[#123B38]">

                                {{ auth()->user()->name }}

                            </p>

                            <p class="text-xs text-[#718A86]">

                                Seller

                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     NAVIGATION
                ================================================== --}}

                <nav
                    class="flex-1 overflow-y-auto p-3">


                    {{-- Dashboard --}}
                    <a
                        href="{{ route('seller.dashboard') }}"
                        class="sidebar-link
                               {{ request()->routeIs('seller.dashboard')
                                    ? 'active'
                                    : '' }}">

                        <svg
                            class="sidebar-icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l9-9 9 9M5 10v10h14V10" />

                        </svg>

                        <span class="sidebar-text">
                            Dashboard
                        </span>

                    </a>


                    {{-- Profile --}}
                    <a
                        href="{{ route('profile.edit') }}"
                        class="sidebar-link
                               {{ request()->routeIs('profile.*')
                                    ? 'active'
                                    : '' }}">

                        <svg
                            class="sidebar-icon"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7-7" />

                        </svg>

                        <span class="sidebar-text">
                            Profile
                        </span>

                    </a>


                    {{-- =================================================
                         LISTINGS
                    ================================================== --}}

                    <div class="sidebar-section">

                        <p
                            class="sidebar-text
                                   sidebar-heading">

                            Listings

                        </p>


                        {{-- Add Product --}}
                        <a
                            href="{{ route('seller.listings.create', ['type' => 'product']) }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.listings.create')
                                      && request('type') === 'product'
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4" />

                            </svg>

                            <span class="sidebar-text">
                                Add Product
                            </span>

                        </a>


                        {{-- Add Property --}}
                        <a
                            href="{{ route('seller.listings.create', ['type' => 'property']) }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.listings.create')
                                      && request('type') === 'property'
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" />

                            </svg>

                            <span class="sidebar-text">
                                Add Property
                            </span>

                        </a>


                        {{-- Add Land --}}
                        <a
                            href="{{ route('seller.listings.create', ['type' => 'land']) }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.listings.create')
                                      && request('type') === 'land'
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 20l6-6 4 4 8-8M3 20h18" />

                            </svg>

                            <span class="sidebar-text">
                                Add Land
                            </span>

                        </a>


                        {{-- Add Service --}}
                        <a
                            href="{{ route('seller.listings.create', ['type' => 'service']) }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.listings.create')
                                      && request('type') === 'service'
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4" />

                            </svg>

                            <span class="sidebar-text">
                                Add Service
                            </span>

                        </a>


                        {{-- Add Project --}}
                        <a
                            href="{{ route('seller.listings.create', ['type' => 'project']) }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.listings.create')
                                      && request('type') === 'project'
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 21h18M5 21V8a2 2 0 012-2h10a2 2 0 012 2v13M9 21v-5h6v5" />

                            </svg>

                            <span class="sidebar-text">
                                Add Project
                            </span>

                        </a>


                        {{-- My Listings --}}
                        <a
                            href="{{ route('seller.listings.index') }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.listings.index')
                                      || request()->routeIs('seller.listings.show')
                                      || request()->routeIs('seller.listings.edit')
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />

                            </svg>

                            <span class="sidebar-text">
                                My Listings
                            </span>

                        </a>

                    </div>


                    {{-- =================================================
                         BUSINESS
                    ================================================== --}}

                    <div class="sidebar-section">

                        <p
                            class="sidebar-text
                                   sidebar-heading">

                            Business

                        </p>


                        {{-- Buyer Enquiries --}}
                        <a
                            href="{{ route('seller.enquiries.index') }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.enquiries.*')
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14l-4-4z" />

                            </svg>

                            <span class="sidebar-text">
                                Buyer Enquiries
                            </span>

                        </a>


                        {{-- Buyer RFQs --}}
                        <a
                            href="{{ route('seller.rfqs.index') }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.rfqs.*')
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5h6a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2h6M9 9h6M9 13h6M9 17h4" />

                            </svg>

                            <span class="sidebar-text">
                                RFQs
                            </span>

                        </a>


                        {{-- =================================================
                             SELLER BIDS
                        ================================================== --}}

                        <a
                            href="{{ route('seller.bids.index') }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.bids.*')
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 3h6v2h2a2 2 0 012 2v3a2 2 0 01-2 2h-2v2h2a2 2 0 012 2v3a2 2 0 01-2 2H7a2 2 0 01-2-2v-3a2 2 0 012-2h2v-2H7a2 2 0 01-2-2V7a2 2 0 012-2h2V3z" />

                            </svg>

                            <span class="sidebar-text">
                                Bids
                            </span>

                        </a>


                        {{-- Quotations --}}
                        <a
                            href="{{ route('seller.quotations.index') }}"
                            class="sidebar-link
                                   {{ request()->routeIs('seller.quotations.*')
                                        ? 'active'
                                        : '' }}">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />

                            </svg>

                            <span class="sidebar-text">
                                Quotations
                            </span>

                        </a>


                        {{-- Orders --}}
                        <a
                            href="{{ route('seller.orders.index') }}"
                            class="sidebar-link">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h13M9 21h.01M18 21h.01" />

                            </svg>

                            <span class="sidebar-text">
                                Orders
                            </span>

                        </a>

                         {{-- Payments --}}
                        <a
                            href="{{ route('seller.payments.index') }}"
                            class="sidebar-link">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h13M9 21h.01M18 21h.01" />

                            </svg>

                            <span class="sidebar-text">
                              Payments
                            </span>

                        </a>


                        {{-- Transcations --}}
                        <a
                            href="{{ route('seller.transactions.index') }}"
                            class="sidebar-link">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 4h13M9 21h.01M18 21h.01" />

                            </svg>

                            <span class="sidebar-text">
                                Transactions
                            </span>

                        </a>



                        {{-- Customers --}}
                        <a
                            href="{{ route('seller.customers.index') }}"
                            class="sidebar-link">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656-.126-1.283-.356-1.857" />

                            </svg>

                            <span class="sidebar-text">
                                Customers
                            </span>

                        </a>

                    </div>

                </nav>


                {{-- =================================================
                     LOGOUT
                ================================================== --}}

                <div
                    class="shrink-0 border-t
                           border-[#D3E3E0] p-3">

                    <form
                        method="POST"
                        action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
                            class="sidebar-link w-full
                                   text-left
                                   hover:bg-red-50
                                   hover:text-red-600">

                            <svg
                                class="sidebar-icon"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                            </svg>

                            <span class="sidebar-text">
                                Logout
                            </span>

                        </button>

                    </form>

                </div>

            </aside>


            {{-- =================================================
                 MOBILE OVERLAY
            ================================================== --}}

            <div
                id="sidebarOverlay"
                class="fixed inset-0 z-30 hidden
                       bg-black/30 lg:hidden">
            </div>


            {{-- =================================================
                 MAIN CONTENT
            ================================================== --}}

            <main
                class="min-w-0 flex-1">

                {{ $slot }}

            </main>

        </div>

    </div>


    {{-- =========================================================
         SIDEBAR CSS
    ========================================================== --}}

    <style>
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 3px;
            border-radius: 8px;
            color: #4F6663;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: #EAF5F3;
            color: #00665F;
        }

        .sidebar-link.active {
            background: #EAF5F3;
            color: #00665F;
            font-weight: 600;
        }

        .sidebar-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .sidebar-heading {
            padding: 12px 12px 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #718A86;
        }

        .sidebar-section {
            margin-top: 10px;
        }


        /* =====================================================
           DESKTOP COLLAPSED SIDEBAR
        ====================================================== */

        @media (min-width: 1024px) {

            #sellerSidebar.collapsed {
                width: 72px;
            }

            #sellerSidebar.collapsed .sidebar-text {
                display: none;
            }

            #sellerSidebar.collapsed .sidebar-link {
                justify-content: center;
                padding-left: 10px;
                padding-right: 10px;
            }

            #sellerSidebar.collapsed .sidebar-heading {
                display: none;
            }

            #sellerSidebar.collapsed .sidebar-section {
                margin-top: 8px;
            }

            #sellerSidebar.collapsed .sidebar-icon {
                margin: 0;
            }

        }


        /* =====================================================
           MOBILE SIDEBAR
        ====================================================== */

        @media (max-width: 1023px) {

            #sellerSidebar {
                width: 280px;
            }

        }
    </style>


    {{-- =========================================================
         SIDEBAR JAVASCRIPT
    ========================================================== --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const sidebar =
                document.getElementById('sellerSidebar');

            const toggle =
                document.getElementById('sidebarToggle');

            const close =
                document.getElementById('sidebarClose');

            const overlay =
                document.getElementById('sidebarOverlay');


            if (!sidebar || !toggle) {
                return;
            }


            /* =====================================================
               RESTORE DESKTOP STATE
            ====================================================== */

            if (window.innerWidth >= 1024) {

                const savedState =
                    localStorage.getItem(
                        'sellerSidebarCollapsed'
                    );

                if (savedState === 'true') {

                    sidebar.classList.add(
                        'collapsed'
                    );

                    toggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }

            }


            /* =====================================================
               MOBILE OPEN
            ====================================================== */

            function openMobileSidebar() {

                sidebar.classList.remove(
                    '-translate-x-full'
                );

                if (overlay) {

                    overlay.classList.remove(
                        'hidden'
                    );

                }

                document.body.classList.add(
                    'overflow-hidden'
                );

                toggle.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }


            /* =====================================================
               MOBILE CLOSE
            ====================================================== */

            function closeMobileSidebar() {

                sidebar.classList.add(
                    '-translate-x-full'
                );

                if (overlay) {

                    overlay.classList.add(
                        'hidden'
                    );

                }

                document.body.classList.remove(
                    'overflow-hidden'
                );

                toggle.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }


            /* =====================================================
               TOGGLE
            ====================================================== */

            toggle.addEventListener(
                'click',
                function() {

                    if (window.innerWidth >= 1024) {

                        sidebar.classList.toggle(
                            'collapsed'
                        );

                        const collapsed =
                            sidebar.classList.contains(
                                'collapsed'
                            );

                        localStorage.setItem(
                            'sellerSidebarCollapsed',
                            collapsed
                        );

                        toggle.setAttribute(
                            'aria-expanded',
                            collapsed ?
                            'false' :
                            'true'
                        );

                    } else {

                        openMobileSidebar();

                    }

                }
            );


            /* =====================================================
               MOBILE CLOSE BUTTON
            ====================================================== */

            if (close) {

                close.addEventListener(
                    'click',
                    function() {

                        closeMobileSidebar();

                    }
                );

            }


            /* =====================================================
               OVERLAY
            ====================================================== */

            if (overlay) {

                overlay.addEventListener(
                    'click',
                    function() {

                        closeMobileSidebar();

                    }
                );

            }


            /* =====================================================
               ESCAPE KEY
            ====================================================== */

            document.addEventListener(
                'keydown',
                function(event) {

                    if (
                        event.key === 'Escape' &&
                        window.innerWidth < 1024
                    ) {

                        closeMobileSidebar();

                    }

                }
            );


            /* =====================================================
               RESIZE
            ====================================================== */

            window.addEventListener(
                'resize',
                function() {

                    if (window.innerWidth >= 1024) {

                        sidebar.classList.remove(
                            '-translate-x-full'
                        );

                        if (overlay) {

                            overlay.classList.add(
                                'hidden'
                            );

                        }

                        document.body.classList.remove(
                            'overflow-hidden'
                        );


                        const saved =
                            localStorage.getItem(
                                'sellerSidebarCollapsed'
                            );

                        if (saved === 'true') {

                            sidebar.classList.add(
                                'collapsed'
                            );

                        } else {

                            sidebar.classList.remove(
                                'collapsed'
                            );

                        }

                    } else {

                        sidebar.classList.remove(
                            'collapsed'
                        );

                        sidebar.classList.add(
                            '-translate-x-full'
                        );

                    }

                }
            );

        });
    </script>

</body>

</html>