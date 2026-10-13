{{-- resources/views/admin/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Admin Dashboard') - Marketplace
    </title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">

    {{-- Tailwind / Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f7fafa;
        }

        ::-webkit-scrollbar-thumb {
            background: #c8dcda;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #056663;
        }

        /* Sidebar transition */
        #adminSidebar {
            transition:
                width 0.25s ease,
                transform 0.25s ease;
        }

        #adminMain {
            transition:
                margin-left 0.25s ease;
        }

        /* Desktop collapsed sidebar */
        body.sidebar-collapsed #adminSidebar {
            width: 80px;
        }

        body.sidebar-collapsed #adminMain {
            margin-left: 80px;
        }

        body.sidebar-collapsed .sidebar-label,
        body.sidebar-collapsed .sidebar-arrow,
        body.sidebar-collapsed .sidebar-section-title {
            display: none;
        }

        body.sidebar-collapsed .sidebar-link {
            justify-content: center;
        }

        body.sidebar-collapsed .sidebar-dropdown {
            display: none !important;
        }

        body.sidebar-collapsed .sidebar-logo-text {
            display: none;
        }

        body.sidebar-collapsed .sidebar-logo {
            justify-content: center;
        }

        /* Notification scrollbar */
        #notificationDropdown::-webkit-scrollbar {
            width: 5px;
        }

        /* Mobile */
        @media (max-width: 1023px) {

            #adminSidebar {
                width: 280px !important;
                transform: translateX(-100%);
            }

            #adminSidebar.mobile-open {
                transform: translateX(0);
            }

            #adminMain {
                margin-left: 0 !important;
            }

            body.sidebar-collapsed #adminSidebar {
                width: 280px;
            }

            body.sidebar-collapsed #adminMain {
                margin-left: 0;
            }

            body.sidebar-collapsed .sidebar-label,
            body.sidebar-collapsed .sidebar-arrow,
            body.sidebar-collapsed .sidebar-section-title,
            body.sidebar-collapsed .sidebar-logo-text {
                display: block;
            }

            body.sidebar-collapsed .sidebar-link {
                justify-content: flex-start;
            }
        }
    </style>

    @stack('styles')
</head>


<body class="bg-[#F7FAFA] text-gray-800 antialiased">

    {{-- ========================================================= --}}
    {{-- MOBILE SIDEBAR OVERLAY --}}
    {{-- ========================================================= --}}

    <div
        id="sidebarOverlay"
        class="fixed inset-0 z-40 hidden bg-black/40 lg:hidden"></div>


    {{-- ========================================================= --}}
    {{-- ADMIN SIDEBAR --}}
    {{-- ========================================================= --}}

    <aside
        id="adminSidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-[280px]
               flex-col border-r border-[#DDE9E8]
               bg-white">

        {{-- Logo --}}
        <div
            class="flex h-[72px] shrink-0 items-center
                   border-b border-[#DDE9E8] px-5">
            <a
                href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}"
                class="sidebar-logo flex w-full items-center gap-3">

                <div
                    class="flex h-10 w-10 shrink-0 items-center
                           justify-center rounded-xl
                           bg-[#056663] text-white">
                    <span class="material-symbols-outlined">
                        store
                    </span>
                </div>

                <div class="sidebar-logo-text min-w-0">
                    <h1 class="truncate text-base font-bold text-gray-900">
                        Marketplace
                    </h1>

                    <p class="text-[11px] text-gray-500">
                        Admin Panel
                    </p>
                </div>

            </a>
        </div>


        {{-- Sidebar Navigation --}}
        <div class="flex-1 overflow-y-auto px-3 py-5">

            {{-- MAIN --}}
            <p class="sidebar-section-title mb-2 px-3 text-[10px]
                      font-bold uppercase tracking-wider text-gray-400">
                Main
            </p>


            {{-- Dashboard --}}
            <a
                href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}"
                class="sidebar-link mb-1 flex items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium
                       transition
                       {{ request()->routeIs('admin.dashboard')
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">
                <span class="material-symbols-outlined text-[21px]">
                    dashboard
                </span>

                <span class="sidebar-label">
                    Dashboard
                </span>
            </a>


            {{-- ================================================= --}}
            {{-- MARKETPLACE --}}
            {{-- ================================================= --}}

            @php
            $marketplaceOpen =
            request()->routeIs('admin.listings.*') ||
            request()->routeIs('admin.properties.*') ||
            request()->routeIs('admin.land.*') ||
            request()->routeIs('admin.products.*') ||
            request()->routeIs('admin.services.*') ||
            request()->routeIs('admin.projects.*');
            @endphp

            <button
                type="button"
                data-sidebar-dropdown="marketplaceDropdown"
                class="sidebar-link mb-1 flex w-full items-center
                       justify-between rounded-xl px-3 py-2.5
                       text-sm font-medium transition
                       {{ $marketplaceOpen
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">

                <span class="flex items-center gap-3">

                    <span class="material-symbols-outlined text-[21px]">
                        storefront
                    </span>

                    <span class="sidebar-label">
                        Marketplace
                    </span>

                </span>

                <span
                    class="sidebar-arrow material-symbols-outlined text-[19px]
                           transition-transform
                           {{ $marketplaceOpen ? 'rotate-180' : '' }}">
                    expand_more
                </span>

            </button>


            <div
                id="marketplaceDropdown"
                class="sidebar-dropdown ml-3 space-y-1
                       {{ $marketplaceOpen ? '' : 'hidden' }}">

                <a
                    href="{{ Route::has('admin.listings.index') ? route('admin.listings.index') : '#' }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                           text-sm transition
                           {{ request()->routeIs('admin.listings.*')
                                ? 'bg-[#F0F7F6] font-semibold text-[#056663]'
                                : 'text-gray-500 hover:bg-[#F5F9F9] hover:text-[#056663]' }}">
                    <span class="material-symbols-outlined text-[18px]">
                        inventory_2
                    </span>

                    <span class="sidebar-label">
                        Listings
                    </span>
                </a>


                <a
                    href="{{ Route::has('admin.properties.index') ? route('admin.properties.index') : '#' }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                           text-sm transition
                           {{ request()->routeIs('admin.properties.*')
                                ? 'bg-[#F0F7F6] font-semibold text-[#056663]'
                                : 'text-gray-500 hover:bg-[#F5F9F9] hover:text-[#056663]' }}">
                    <span class="material-symbols-outlined text-[18px]">
                        apartment
                    </span>

                    <span class="sidebar-label">
                        Properties
                    </span>
                </a>


                <a
                    href="{{ Route::has('admin.land.index') ? route('admin.land.index') : '#' }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                           text-sm transition
                           {{ request()->routeIs('admin.land.*')
                                ? 'bg-[#F0F7F6] font-semibold text-[#056663]'
                                : 'text-gray-500 hover:bg-[#F5F9F9] hover:text-[#056663]' }}">
                    <span class="material-symbols-outlined text-[18px]">
                        landscape
                    </span>

                    <span class="sidebar-label">
                        Land & Plots
                    </span>
                </a>


                <a
                    href="{{ Route::has('admin.products.index') ? route('admin.products.index') : '#' }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                           text-sm transition
                           {{ request()->routeIs('admin.products.*')
                                ? 'bg-[#F0F7F6] font-semibold text-[#056663]'
                                : 'text-gray-500 hover:bg-[#F5F9F9] hover:text-[#056663]' }}">
                    <span class="material-symbols-outlined text-[18px]">
                        category
                    </span>

                    <span class="sidebar-label">
                        Products
                    </span>
                </a>


                <a
                    href="{{ Route::has('admin.services.index') ? route('admin.services.index') : '#' }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                           text-sm transition
                           {{ request()->routeIs('admin.services.*')
                                ? 'bg-[#F0F7F6] font-semibold text-[#056663]'
                                : 'text-gray-500 hover:bg-[#F5F9F9] hover:text-[#056663]' }}">
                    <span class="material-symbols-outlined text-[18px]">
                        handyman
                    </span>

                    <span class="sidebar-label">
                        Services
                    </span>
                </a>


                <a
                    href="{{ Route::has('admin.projects.index') ? route('admin.projects.index') : '#' }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                           text-sm transition
                           {{ request()->routeIs('admin.projects.*')
                                ? 'bg-[#F0F7F6] font-semibold text-[#056663]'
                                : 'text-gray-500 hover:bg-[#F5F9F9] hover:text-[#056663]' }}">
                    <span class="material-symbols-outlined text-[18px]">
                        construction
                    </span>

                    <span class="sidebar-label">
                        Projects
                    </span>
                </a>

            </div>


            {{-- ================================================= --}}
            {{-- USERS --}}
            {{-- ================================================= --}}

            @php
            $usersOpen =
            request()->routeIs('admin.buyers.*') ||
            request()->routeIs('admin.sellers.*');
            @endphp

            <button
                type="button"
                data-sidebar-dropdown="usersDropdown"
                class="sidebar-link mb-1 mt-2 flex w-full
                       items-center justify-between rounded-xl
                       px-3 py-2.5 text-sm font-medium transition
                       {{ $usersOpen
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">

                <span class="flex items-center gap-3">

                    <span class="material-symbols-outlined text-[21px]">
                        group
                    </span>

                    <span class="sidebar-label">
                        Users
                    </span>

                </span>

                <span
                    class="sidebar-arrow material-symbols-outlined text-[19px]
                           transition-transform
                           {{ $usersOpen ? 'rotate-180' : '' }}">
                    expand_more
                </span>

            </button>


            <div
                id="usersDropdown"
                class="sidebar-dropdown ml-3 space-y-1
                       {{ $usersOpen ? '' : 'hidden' }}">

                <a
                    href="{{ Route::has('admin.buyers.index') ? route('admin.buyers.index') : '#' }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                           text-sm transition
                           {{ request()->routeIs('admin.buyers.*')
                                ? 'bg-[#F0F7F6] font-semibold text-[#056663]'
                                : 'text-gray-500 hover:bg-[#F5F9F9] hover:text-[#056663]' }}">
                    <span class="material-symbols-outlined text-[18px]">
                        person
                    </span>

                    <span class="sidebar-label">
                        Buyers
                    </span>
                </a>


                <a
                    href="{{ Route::has('admin.sellers.index') ? route('admin.sellers.index') : '#' }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                           text-sm transition
                           {{ request()->routeIs('admin.sellers.*')
                                ? 'bg-[#F0F7F6] font-semibold text-[#056663]'
                                : 'text-gray-500 hover:bg-[#F5F9F9] hover:text-[#056663]' }}">
                    <span class="material-symbols-outlined text-[18px]">
                        store
                    </span>

                    <span class="sidebar-label">
                        Sellers
                    </span>
                </a>

            </div>


            {{-- ================================================= --}}
            {{-- PROCUREMENT --}}
            {{-- ================================================= --}}

            <p class="sidebar-section-title mb-2 mt-7 px-3 text-[10px]
                      font-bold uppercase tracking-wider text-gray-400">
                Procurement
            </p>


            {{-- Requirements --}}
            <a
                href="{{ Route::has('admin.requirements.index') ? route('admin.requirements.index') : '#' }}"
                class="sidebar-link mb-1 flex items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs('admin.requirements.*')
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">
                <span class="material-symbols-outlined text-[21px]">
                    assignment
                </span>

                <span class="sidebar-label">
                    Requirements
                </span>
            </a>


            {{-- RFQs --}}
            <a
                href="{{ Route::has('admin.rfqs.index') ? route('admin.rfqs.index') : '#' }}"
                class="sidebar-link mb-1 flex items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs('admin.rfqs.*')
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">
                <span class="material-symbols-outlined text-[21px]">
                    request_quote
                </span>

                <span class="sidebar-label">
                    RFQs
                </span>
            </a>


            {{-- Quotations --}}
            <a
                href="{{ Route::has('admin.quotations.index') ? route('admin.quotations.index') : '#' }}"
                class="sidebar-link mb-1 flex items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs('admin.quotations.*')
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">
                <span class="material-symbols-outlined text-[21px]">
                    receipt_long
                </span>

                <span class="sidebar-label">
                    Quotations
                </span>
            </a>


            {{-- Orders --}}
            <a
                href="{{ route('admin.orders.index') }}"
                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
        {{ request()->routeIs('admin.orders.*')
            ? 'bg-[#056663] text-white'
            : 'text-gray-700 hover:bg-gray-100' }}">
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 2a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z" />
                </svg>

                <span>
                    Orders
                </span>
            </a>

              {{-- payments --}}
            <a
                href="{{ route('admin.payments.index') }}"
                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
        {{ request()->routeIs('admin.payments.*')
            ? 'bg-[#056663] text-white'
            : 'text-gray-700 hover:bg-gray-100' }}">
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 2a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z" />
                </svg>

                <span>
                   Payments
                </span>
            </a>



              {{-- Transaction --}}
            <a
                href="{{ route('admin.transactions.index') }}"
                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium
        {{ request()->routeIs('admin.transactions.*')
            ? 'bg-[#056663] text-white'
            : 'text-gray-700 hover:bg-gray-100' }}">
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 2a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z" />
                </svg>

                <span>
                  Transaction
                </span>
            </a>

            {{-- ================================================= --}}
            {{-- MANAGEMENT --}}
            {{-- ================================================= --}}

            <p class="sidebar-section-title mb-2 mt-7 px-3 text-[10px]
                      font-bold uppercase tracking-wider text-gray-400">
                Management
            </p>


            {{-- Categories --}}
            <a
                href="{{ Route::has('admin.categories.index') ? route('admin.categories.index') : '#' }}"
                class="sidebar-link mb-1 flex items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs('admin.categories.*')
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">
                <span class="material-symbols-outlined text-[21px]">
                    category
                </span>

                <span class="sidebar-label">
                    Categories
                </span>
            </a>


            {{-- Locations --}}
            <a
                href="{{ Route::has('admin.locations.index') ? route('admin.locations.index') : '#' }}"
                class="sidebar-link mb-1 flex items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs('admin.locations.*')
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">
                <span class="material-symbols-outlined text-[21px]">
                    location_on
                </span>

                <span class="sidebar-label">
                    Locations
                </span>
            </a>


            {{-- Reviews --}}
           <a href="{{ route('admin.reviews.index') }}"
   class="flex items-center gap-3 rounded-xl px-4 py-3
          text-sm font-medium text-gray-700
          transition hover:bg-[#E8F5F4] hover:text-[#056663]
          {{ request()->routeIs('admin.reviews.*')
              ? 'bg-[#E8F5F4] text-[#056663]'
              : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="h-5 w-5" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
              stroke-width="1.8"
              d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.112
                 5.518.442a.563.563 0 01.321.988l-4.205 3.6
                 1.285 5.378a.562.562 0 01-.84.61L12 16.77
                 7.278 19.63a.562.562 0 01-.84-.61l1.285-5.379
                 -4.205-3.6a.563.563 0 01.321-.988l5.518-.442
                 2.125-5.112z"/>
    </svg>

    <span>Review & Rating Management</span>
</a>


            {{-- Reports --}}
            <a
                href="{{ Route::has('admin.reports.index') ? route('admin.reports.index') : '#' }}"
                class="sidebar-link mb-1 flex items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs('admin.reports.*')
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">
                <span class="material-symbols-outlined text-[21px]">
                    analytics
                </span>

                <span class="sidebar-label">
                    Reports
                </span>
            </a>


            {{-- Settings --}}
            <a
                href="{{ Route::has('admin.settings.index') ? route('admin.settings.index') : '#' }}"
                class="sidebar-link mb-1 flex items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium transition
                       {{ request()->routeIs('admin.settings.*')
                            ? 'bg-[#E8F5F4] text-[#056663]'
                            : 'text-gray-600 hover:bg-[#F3F8F7] hover:text-[#056663]' }}">
                <span class="material-symbols-outlined text-[21px]">
                    settings
                </span>

                <span class="sidebar-label">
                    Settings
                </span>
            </a>

        </div>


        {{-- ========================================================= --}}
        {{-- SIDEBAR FOOTER --}}
        {{-- ========================================================= --}}

        <div class="shrink-0 border-t border-[#DDE9E8] p-3">

            <button
                type="button"
                id="sidebarCollapseButton"
                class="flex w-full items-center gap-3 rounded-xl
                       px-3 py-2.5 text-sm font-medium
                       text-gray-500 transition
                       hover:bg-[#F3F8F7] hover:text-[#056663]">
                <span
                    id="sidebarCollapseIcon"
                    class="material-symbols-outlined text-[21px]">
                    left_panel_close
                </span>

                <span class="sidebar-label">
                    Collapse Sidebar
                </span>
            </button>

        </div>

    </aside>


    {{-- ========================================================= --}}
    {{-- MAIN AREA --}}
    {{-- ========================================================= --}}

    <div
        id="adminMain"
        class="min-h-screen ml-[280px]">

        {{-- ===================================================== --}}
        {{-- TOP HEADER --}}
        {{-- ===================================================== --}}

        <header
            class="sticky top-0 z-30 flex h-[72px]
                   items-center justify-between
                   border-b border-[#DDE9E8]
                   bg-white/95 px-4 backdrop-blur
                   sm:px-6 lg:px-8">

            {{-- Left --}}
            <div class="flex items-center gap-3">

                {{-- Mobile menu --}}
                <button
                    type="button"
                    id="mobileMenuButton"
                    class="flex h-10 w-10 items-center justify-center
                           rounded-xl text-gray-600
                           transition hover:bg-[#E8F5F4]
                           hover:text-[#056663]
                           lg:hidden">
                    <span class="material-symbols-outlined">
                        menu
                    </span>
                </button>


                {{-- Desktop collapse --}}
                <button
                    type="button"
                    id="desktopCollapseButton"
                    class="hidden h-10 w-10 items-center justify-center
                           rounded-xl text-gray-600
                           transition hover:bg-[#E8F5F4]
                           hover:text-[#056663]
                           lg:flex">
                    <span class="material-symbols-outlined">
                        menu_open
                    </span>
                </button>


                <div class="hidden sm:block">
                    <h2 class="text-lg font-bold text-gray-900">
                        @yield('page-title', 'Dashboard')
                    </h2>

                    <p class="text-xs text-gray-500">
                        Manage your marketplace
                    </p>
                </div>

            </div>


            {{-- Right --}}
            <div class="flex items-center gap-2 sm:gap-3">

                {{-- ================================================= --}}
                {{-- NOTIFICATIONS --}}
                {{-- ================================================= --}}

                @php
                $admin = auth('admin')->user();

                $unreadNotificationCount = $admin
                ? $admin->unreadNotifications()->count()
                : 0;

                $latestNotifications = $admin
                ? $admin->notifications()
                ->latest()
                ->take(5)
                ->get()
                : collect();
                @endphp


                <div
                    class="relative"
                    id="notificationWrapper">

                    {{-- Notification Button --}}
                    <button
                        type="button"
                        id="notificationButton"
                        class="relative flex h-10 w-10
                               items-center justify-center
                               rounded-xl text-gray-600
                               transition
                               hover:bg-[#E8F5F4]
                               hover:text-[#056663]"
                        aria-label="Notifications">

                        <span class="material-symbols-outlined text-[23px]">
                            notifications
                        </span>


                        @if($unreadNotificationCount > 0)

                        <span
                            class="absolute -right-0.5 -top-0.5
                                       flex h-5 min-w-[20px]
                                       items-center justify-center
                                       rounded-full bg-red-500
                                       px-1 text-[10px]
                                       font-bold text-white">
                            {{ $unreadNotificationCount > 99
                                    ? '99+'
                                    : $unreadNotificationCount }}
                        </span>

                        @endif

                    </button>


                    {{-- ================================================= --}}
                    {{-- NOTIFICATION DROPDOWN --}}
                    {{-- ================================================= --}}

                    <div
                        id="notificationDropdown"
                        class="absolute right-0 top-12 z-50 hidden
                               w-[380px] max-w-[calc(100vw-2rem)]
                               overflow-hidden rounded-2xl
                               border border-[#DDE9E8]
                               bg-white shadow-xl">

                        {{-- Header --}}
                        <div
                            class="flex items-center justify-between
                                   border-b border-[#DDE9E8]
                                   px-4 py-3">

                            <div>

                                <h3 class="text-sm font-bold text-gray-900">
                                    Notifications
                                </h3>

                                @if($unreadNotificationCount > 0)

                                <p class="mt-0.5 text-xs text-gray-500">
                                    {{ $unreadNotificationCount }}
                                    unread notification{{ $unreadNotificationCount > 1 ? 's' : '' }}
                                </p>

                                @else

                                <p class="mt-0.5 text-xs text-gray-500">
                                    You're all caught up
                                </p>

                                @endif

                            </div>


                            @if($unreadNotificationCount > 0)

                            <form
                                method="POST"
                                action="{{ route('admin.notifications.readAll') }}">
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="text-xs font-semibold
                                               text-[#056663]
                                               hover:text-[#034B49]">
                                    Mark all as read
                                </button>

                            </form>

                            @endif

                        </div>


                        {{-- Notification List --}}
                        <div
                            class="max-h-[420px] overflow-y-auto">

                            @forelse($latestNotifications as $notification)

                            @php

                            $data = $notification->data ?? [];

                            $type = $data['type'] ?? 'system';

                            $icon = match ($type) {

                            'requirement'
                            => 'assignment',

                            'enquiry'
                            => 'chat',

                            'rfq'
                            => 'request_quote',

                            'quotation'
                            => 'receipt_long',

                            'listing'
                            => 'inventory_2',

                            'seller_verification'
                            => 'verified',

                            'seller_featured'
                            => 'star',

                            'seller'
                            => 'store',

                            'buyer'
                            => 'person',

                            'order'
                            => 'shopping_cart',

                            'bid'
                            => 'gavel',

                            'review'
                            => 'reviews',

                            'account'
                            => 'manage_accounts',

                            'system'
                            => 'settings',

                            default
                            => 'notifications',
                            };

                            $isUnread = is_null(
                            $notification->read_at
                            );

                            @endphp


                            <a
                                href="{{ route(
                                        'admin.notifications.read',
                                        $notification->id
                                    ) }}"
                                class="flex gap-3
                                           border-b border-[#EEF4F3]
                                           px-4 py-3 transition
                                           hover:bg-[#F7FAFA]
                                           {{ $isUnread
                                                ? 'bg-[#F0F9F8]'
                                                : 'bg-white' }}">

                                {{-- Icon --}}
                                <div
                                    class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-xl
                                               {{ $isUnread
                                                    ? 'bg-[#DDF3F1] text-[#056663]'
                                                    : 'bg-gray-100 text-gray-500' }}">

                                    <span
                                        class="material-symbols-outlined
                                                   text-[20px]">
                                        {{ $icon }}
                                    </span>

                                </div>


                                {{-- Content --}}
                                <div class="min-w-0 flex-1">

                                    <div
                                        class="flex items-start
                                                   justify-between gap-2">

                                        <h4
                                            class="text-sm
                                                {{ $isUnread
                                                    ? 'font-bold text-gray-900'
                                                    : 'font-medium text-gray-700' }}">
                                            {{ $data['title'] ?? 'Notification' }}
                                        </h4>


                                        @if($isUnread)

                                        <span
                                            class="mt-1.5 h-2 w-2
                                                           shrink-0 rounded-full
                                                           bg-[#056663]"></span>

                                        @endif

                                    </div>


                                    @if(!empty($data['message']))

                                    <p
                                        class="mt-1 line-clamp-2
                                                       text-xs leading-5
                                                       text-gray-500">
                                        {{ $data['message'] }}
                                    </p>

                                    @endif


                                    <p class="mt-1.5 text-[11px] text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>

                                </div>

                            </a>

                            @empty

                            {{-- Empty --}}
                            <div class="px-6 py-10 text-center">

                                <div
                                    class="mx-auto flex h-12 w-12
                                               items-center justify-center
                                               rounded-full
                                               bg-[#E8F5F4]
                                               text-[#056663]">

                                    <span
                                        class="material-symbols-outlined
                                                   text-[24px]">
                                        notifications_none
                                    </span>

                                </div>


                                <h4
                                    class="mt-3 text-sm
                                               font-semibold text-gray-900">
                                    No notifications
                                </h4>


                                <p
                                    class="mt-1 text-xs text-gray-500">
                                    You don't have any notifications yet.
                                </p>

                            </div>

                            @endforelse

                        </div>


                        {{-- Footer --}}
                        <div
                            class="border-t border-[#DDE9E8]
                                   bg-[#FAFCFC]">

                            <a
                                href="{{ route('admin.notifications.index') }}"
                                class="flex items-center justify-center
                                       gap-1 px-4 py-3
                                       text-sm font-semibold
                                       text-[#056663]
                                       transition
                                       hover:bg-[#F0F7F6]">

                                View all notifications

                                <span
                                    class="material-symbols-outlined
                                           text-[18px]">
                                    arrow_forward
                                </span>

                            </a>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ADMIN PROFILE --}}
                {{-- ================================================= --}}

                <div
                    class="hidden h-8 w-px bg-[#DDE9E8] sm:block"></div>


                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-full bg-[#E8F5F4]
                               text-[#056663]">
                        <span class="material-symbols-outlined">
                            person
                        </span>
                    </div>


                    <div class="hidden min-w-0 md:block">

                        <p
                            class="truncate text-sm font-semibold
                                   text-gray-900">
                            {{ $admin?->name ?? 'Admin' }}
                        </p>

                        <p class="text-[11px] text-gray-500">
                            Administrator
                        </p>

                    </div>


                    {{-- Profile Dropdown --}}
                    <div class="relative">

                        <button
                            type="button"
                            id="profileButton"
                            class="flex h-9 w-9 items-center
                                   justify-center rounded-lg
                                   text-gray-500 transition
                                   hover:bg-[#F3F8F7]
                                   hover:text-[#056663]">

                            <span class="material-symbols-outlined">
                                expand_more
                            </span>

                        </button>


                        <div
                            id="profileDropdown"
                            class="absolute right-0 top-11 z-50 hidden
                                   w-48 overflow-hidden rounded-xl
                                   border border-[#DDE9E8]
                                   bg-white shadow-lg">

                            @if(Route::has('admin.profile.edit'))

                            <a
                                href="{{ route('admin.profile.edit') }}"
                                class="flex items-center gap-3
                                           px-4 py-3 text-sm
                                           text-gray-600
                                           hover:bg-[#F3F8F7]
                                           hover:text-[#056663]">

                                <span class="material-symbols-outlined text-[19px]">
                                    person
                                </span>

                                Profile

                            </a>

                            @endif


                            @if(Route::has('admin.settings.index'))

                            <a
                                href="{{ route('admin.settings.index') }}"
                                class="flex items-center gap-3
                                           px-4 py-3 text-sm
                                           text-gray-600
                                           hover:bg-[#F3F8F7]
                                           hover:text-[#056663]">

                                <span class="material-symbols-outlined text-[19px]">
                                    settings
                                </span>

                                Settings

                            </a>

                            @endif


                            <div class="border-t border-[#DDE9E8]"></div>


                            <form
                                method="POST"
                                action="{{ route('admin.logout') }}">

                                @csrf

                                <button
                                    type="submit"
                                    class="flex w-full items-center gap-3
                                           px-4 py-3 text-sm
                                           text-red-600
                                           hover:bg-red-50">

                                    <span class="material-symbols-outlined text-[19px]">
                                        logout
                                    </span>

                                    Logout

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </header>


        {{-- ===================================================== --}}
        {{-- PAGE CONTENT --}}
        {{-- ===================================================== --}}

        <main class="p-4 sm:p-6 lg:p-8">

            {{-- Success Message --}}
            @if(session('success'))

            <div
                class="mb-6 flex items-start gap-3 rounded-xl
                           border border-green-200
                           bg-green-50 px-4 py-3
                           text-green-800">

                <span class="material-symbols-outlined text-[20px]">
                    check_circle
                </span>

                <p class="text-sm font-medium">
                    {{ session('success') }}
                </p>

            </div>

            @endif


            {{-- Error Message --}}
            @if(session('error'))

            <div
                class="mb-6 flex items-start gap-3 rounded-xl
                           border border-red-200
                           bg-red-50 px-4 py-3
                           text-red-800">

                <span class="material-symbols-outlined text-[20px]">
                    error
                </span>

                <p class="text-sm font-medium">
                    {{ session('error') }}
                </p>

            </div>

            @endif


            {{-- Validation Errors --}}
            @if($errors->any())

            <div
                class="mb-6 rounded-xl border border-red-200
                           bg-red-50 px-4 py-3 text-red-800">

                <div class="flex items-start gap-3">

                    <span class="material-symbols-outlined text-[20px]">
                        error
                    </span>

                    <div>

                        <p class="text-sm font-semibold">
                            Please fix the following errors:
                        </p>

                        <ul
                            class="mt-2 list-disc space-y-1
                                       pl-5 text-xs">

                            @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

            @endif


            @yield('content')

        </main>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | Sidebar
            |--------------------------------------------------------------------------
            */

            const sidebar =
                document.getElementById('adminSidebar');

            const sidebarOverlay =
                document.getElementById('sidebarOverlay');

            const mobileMenuButton =
                document.getElementById('mobileMenuButton');

            const desktopCollapseButton =
                document.getElementById('desktopCollapseButton');

            const sidebarCollapseButton =
                document.getElementById('sidebarCollapseButton');

            const sidebarCollapseIcon =
                document.getElementById('sidebarCollapseIcon');


            /*
            |--------------------------------------------------------------------------
            | Desktop Sidebar State
            |--------------------------------------------------------------------------
            */

            const savedSidebarState =
                localStorage.getItem('adminSidebarCollapsed');

            if (
                savedSidebarState === 'true' &&
                window.innerWidth >= 1024
            ) {
                document.body.classList.add('sidebar-collapsed');

                if (sidebarCollapseIcon) {
                    sidebarCollapseIcon.textContent =
                        'left_panel_open';
                }
            }


            function toggleDesktopSidebar() {

                if (window.innerWidth < 1024) {
                    return;
                }

                document.body.classList.toggle(
                    'sidebar-collapsed'
                );

                const collapsed =
                    document.body.classList.contains(
                        'sidebar-collapsed'
                    );

                localStorage.setItem(
                    'adminSidebarCollapsed',
                    collapsed
                );

                if (sidebarCollapseIcon) {

                    sidebarCollapseIcon.textContent =
                        collapsed ?
                        'left_panel_open' :
                        'left_panel_close';

                }
            }


            if (desktopCollapseButton) {

                desktopCollapseButton.addEventListener(
                    'click',
                    toggleDesktopSidebar
                );

            }


            if (sidebarCollapseButton) {

                sidebarCollapseButton.addEventListener(
                    'click',
                    toggleDesktopSidebar
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Mobile Sidebar
            |--------------------------------------------------------------------------
            */

            function openMobileSidebar() {

                if (!sidebar) {
                    return;
                }

                sidebar.classList.add('mobile-open');

                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('hidden');
                }

                document.body.classList.add('overflow-hidden');
            }


            function closeMobileSidebar() {

                if (!sidebar) {
                    return;
                }

                sidebar.classList.remove('mobile-open');

                if (sidebarOverlay) {
                    sidebarOverlay.classList.add('hidden');
                }

                document.body.classList.remove('overflow-hidden');
            }


            if (mobileMenuButton) {

                mobileMenuButton.addEventListener(
                    'click',
                    openMobileSidebar
                );

            }


            if (sidebarOverlay) {

                sidebarOverlay.addEventListener(
                    'click',
                    closeMobileSidebar
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Sidebar Dropdowns
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('[data-sidebar-dropdown]')
                .forEach(function(button) {

                    button.addEventListener(
                        'click',
                        function() {

                            if (
                                window.innerWidth >= 1024 &&
                                document.body.classList.contains(
                                    'sidebar-collapsed'
                                )
                            ) {
                                return;
                            }

                            const targetId =
                                button.getAttribute(
                                    'data-sidebar-dropdown'
                                );

                            const dropdown =
                                document.getElementById(
                                    targetId
                                );

                            if (!dropdown) {
                                return;
                            }

                            const arrow =
                                button.querySelector(
                                    '.sidebar-arrow'
                                );

                            dropdown.classList.toggle(
                                'hidden'
                            );

                            if (arrow) {

                                arrow.classList.toggle(
                                    'rotate-180'
                                );

                            }

                        }
                    );

                });


            /*
            |--------------------------------------------------------------------------
            | Notifications
            |--------------------------------------------------------------------------
            */

            const notificationButton =
                document.getElementById(
                    'notificationButton'
                );

            const notificationDropdown =
                document.getElementById(
                    'notificationDropdown'
                );

            const notificationWrapper =
                document.getElementById(
                    'notificationWrapper'
                );


            if (
                notificationButton &&
                notificationDropdown
            ) {

                notificationButton.addEventListener(
                    'click',
                    function(event) {

                        event.stopPropagation();

                        notificationDropdown
                            .classList
                            .toggle('hidden');

                        if (
                            profileDropdown &&
                            !profileDropdown.classList.contains(
                                'hidden'
                            )
                        ) {
                            profileDropdown.classList.add(
                                'hidden'
                            );
                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Profile Dropdown
            |--------------------------------------------------------------------------
            */

            const profileButton =
                document.getElementById(
                    'profileButton'
                );

            const profileDropdown =
                document.getElementById(
                    'profileDropdown'
                );


            if (
                profileButton &&
                profileDropdown
            ) {

                profileButton.addEventListener(
                    'click',
                    function(event) {

                        event.stopPropagation();

                        profileDropdown
                            .classList
                            .toggle('hidden');

                        if (
                            notificationDropdown &&
                            !notificationDropdown.classList.contains(
                                'hidden'
                            )
                        ) {
                            notificationDropdown.classList.add(
                                'hidden'
                            );
                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Close Dropdowns On Outside Click
            |--------------------------------------------------------------------------
            */

            document.addEventListener(
                'click',
                function(event) {

                    if (
                        notificationWrapper &&
                        !notificationWrapper.contains(
                            event.target
                        )
                    ) {

                        if (notificationDropdown) {

                            notificationDropdown.classList.add(
                                'hidden'
                            );

                        }

                    }


                    const profileWrapper =
                        profileButton ?
                        profileButton.parentElement :
                        null;


                    if (
                        profileWrapper &&
                        !profileWrapper.contains(
                            event.target
                        )
                    ) {

                        if (profileDropdown) {

                            profileDropdown.classList.add(
                                'hidden'
                            );

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Close Mobile Sidebar When Clicking A Link
            |--------------------------------------------------------------------------
            */

            if (sidebar) {

                sidebar
                    .querySelectorAll('a')
                    .forEach(function(link) {

                        link.addEventListener(
                            'click',
                            function() {

                                if (
                                    window.innerWidth < 1024
                                ) {
                                    closeMobileSidebar();
                                }

                            }
                        );

                    });

            }


            /*
            |--------------------------------------------------------------------------
            | Responsive Sidebar Reset
            |--------------------------------------------------------------------------
            */

            window.addEventListener(
                'resize',
                function() {

                    if (window.innerWidth >= 1024) {

                        closeMobileSidebar();

                    }

                }
            );

        });
    </script>


    @stack('scripts')

</body>

</html>