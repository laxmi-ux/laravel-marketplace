{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,0" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Marketplace') }}
    </title>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js'
    ])

</head>


<body class="bg-[#F7FAFA] font-sans antialiased">

    <div class="min-h-screen">


        {{-- =====================================================
             FIXED HEADER
        ====================================================== --}}

        <header
            class="fixed inset-x-0 top-0 z-50
                   h-20 border-b border-[#DDE9E8]
                   bg-white/95 backdrop-blur
                   shadow-sm">

            <div
                class="flex h-full items-center
                       px-4 sm:px-6 lg:px-8">


                {{-- Buyer Header Toggle --}}
                <button
                    type="button"
                    id="buyerHeaderToggle"
                    aria-label="Toggle buyer sidebar"
                    aria-expanded="true"
                    class="mr-3 flex shrink-0
                           items-center justify-center
                           rounded-lg p-2
                           text-[#056663]
                           transition
                           hover:bg-[#E8F5F4]">

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


                {{-- Page Header Slot --}}
                <div class="min-w-0 flex-1">

                    {{ $header ?? '' }}

                </div>


                {{-- =====================================================
     NOTIFICATIONS + USER AREA
====================================================== --}}

                <div class="flex items-center gap-3">

                    {{-- =================================================
     NOTIFICATION BELL
================================================== --}}

                    @auth

                    @php
                    $buyer = auth()->user();

                    $unreadNotificationCount = $buyer
                    ->unreadNotifications()
                    ->count();

                    $latestNotifications = $buyer
                    ->notifications()
                    ->latest()
                    ->take(5)
                    ->get();
                    @endphp

                    <div
                        class="relative"
                        id="buyerNotificationWrapper">

                        {{-- Bell Button --}}
                        <button
                            type="button"
                            id="buyerNotificationButton"
                            title="Notifications"
                            class="relative flex h-10 w-10
                   items-center justify-center
                   rounded-xl
                   text-[#64748B]
                   transition
                   hover:bg-[#E8F5F4]
                   hover:text-[#056663]">

                            <span class="material-symbols-outlined text-[23px]">
                                notifications
                            </span>

                            @if($unreadNotificationCount > 0)

                            <span
                                class="absolute -right-1 -top-1
                           flex min-w-[20px] h-5
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

                        </button>


                        {{-- Notification Dropdown --}}
                        <div
                            id="buyerNotificationDropdown"
                            class="absolute right-0 top-12 z-[60]
                   hidden w-[360px]
                   overflow-hidden rounded-xl
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

                                    <p class="text-xs text-gray-500">
                                        @if($unreadNotificationCount > 0)
                                        {{ $unreadNotificationCount }} unread
                                        @else
                                        No unread notifications
                                        @endif
                                    </p>

                                </div>


                                <a
                                    href="{{ route('buyer.notifications.index') }}"
                                    class="text-xs font-semibold text-[#056663]
                           hover:underline">
                                    View All
                                </a>

                            </div>


                            {{-- Notifications --}}
                            <div class="max-h-[380px] overflow-y-auto">

                                @forelse($latestNotifications as $notification)

                                @php
                                $notificationTitle =
                                $notification->data['title']
                                ?? 'Notification';

                                $notificationMessage =
                                $notification->data['message']
                                ?? '';

                                $notificationType =
                                $notification->data['type']
                                ?? 'system';

                                $notificationUrl =
                                $notification->data['url']
                                ?? route('buyer.notifications.index');
                                @endphp


                                <a
                                    href="{{ route(
                            'buyer.notifications.read',
                            $notification->id
                        ) }}"
                                    class="flex gap-3 border-b
                               border-[#F0F4F4]
                               px-4 py-3
                               transition
                               hover:bg-[#F7FAFA]
                               {{ is_null($notification->read_at)
                                    ? 'bg-[#F7FAFA]'
                                    : '' }}">

                                    {{-- Notification Icon --}}
                                    <div
                                        class="flex h-9 w-9 shrink-0
                                   items-center justify-center
                                   rounded-lg
                                   bg-[#E8F5F4]
                                   text-[#056663]">

                                        @if($notificationType === 'quotation')

                                        <span class="material-symbols-outlined text-[20px]">
                                            request_quote
                                        </span>

                                        @elseif($notificationType === 'bid')

                                        <span class="material-symbols-outlined text-[20px]">
                                            gavel
                                        </span>

                                        @elseif($notificationType === 'rfq')

                                        <span class="material-symbols-outlined text-[20px]">
                                            description
                                        </span>

                                        @elseif($notificationType === 'enquiry')

                                        <span class="material-symbols-outlined text-[20px]">
                                            mail
                                        </span>

                                        @elseif($notificationType === 'order')

                                        <span class="material-symbols-outlined text-[20px]">
                                            shopping_cart
                                        </span>

                                        @else

                                        <span class="material-symbols-outlined text-[20px]">
                                            notifications
                                        </span>

                                        @endif

                                    </div>


                                    {{-- Content --}}
                                    <div class="min-w-0 flex-1">

                                        <div class="flex items-start justify-between gap-2">

                                            <p
                                                class="truncate text-sm
                                           {{ is_null($notification->read_at)
                                                ? 'font-semibold text-gray-900'
                                                : 'font-medium text-gray-700' }}">
                                                {{ $notificationTitle }}
                                            </p>


                                            @if(is_null($notification->read_at))

                                            <span
                                                class="mt-1 h-2 w-2
                                               shrink-0 rounded-full
                                               bg-[#056663]"></span>

                                            @endif

                                        </div>


                                        <p
                                            class="mt-1 line-clamp-2
                                       text-xs leading-5
                                       text-gray-500">
                                            {{ $notificationMessage }}
                                        </p>


                                        <p class="mt-1 text-[10px] text-gray-400">

                                            {{ $notification->created_at->diffForHumans() }}

                                        </p>

                                    </div>

                                </a>

                                @empty

                                <div class="px-5 py-10 text-center">

                                    <span
                                        class="material-symbols-outlined
                                   text-4xl text-gray-300">
                                        notifications_none
                                    </span>

                                    <p class="mt-2 text-sm font-medium text-gray-500">
                                        No notifications yet
                                    </p>

                                </div>

                                @endforelse

                            </div>


                            {{-- Footer --}}
                            <div
                                class="border-t border-[#DDE9E8]
                       bg-[#F7FAFA] px-4 py-3">

                                <a
                                    href="{{ route('buyer.notifications.index') }}"
                                    class="block text-center
                           text-sm font-semibold
                           text-[#056663]
                           hover:underline">
                                    View All Notifications
                                </a>

                            </div>

                        </div>

                    </div>

                    @endauth

                    {{-- =================================================
         USER AREA
    ================================================== --}}

                    <div class="hidden items-center gap-3 sm:flex">

                        <div class="max-w-[180px] text-right">

                            <p
                                class="truncate text-sm font-semibold
                       text-[#123B38]">
                                {{ auth()->user()->name }}
                            </p>

                            <p
                                class="text-xs text-[#64748B]">
                                {{ ucfirst(auth()->user()->role ?? 'Buyer') }}
                            </p>

                        </div>


                        <div
                            class="flex h-10 w-10 shrink-0
                   items-center justify-center
                   rounded-full
                   bg-[#E8F5F4]
                   text-[#056663]
                   font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
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
                 MOBILE OVERLAY
            ================================================== --}}

            <div
                id="buyerSidebarOverlay"
                class="fixed inset-0 z-30 hidden
                       bg-black/40 lg:hidden">
            </div>


            {{-- =================================================
                 BUYER SIDEBAR
            ================================================== --}}

            <aside
                id="buyerSidebar"
                class="fixed inset-y-20 left-0 z-40
                       flex h-[calc(100vh-80px)]
                       w-64 shrink-0
                       -translate-x-full
                       flex-col
                       border-r border-[#DDE9E8]
                       bg-white shadow-sm
                       transition-all duration-300
                       ease-in-out
                       lg:sticky lg:top-20
                       lg:translate-x-0">


                {{-- =================================================
                     SIDEBAR HEADER
                ================================================== --}}

                <div
                    class="flex h-16 shrink-0
                           items-center justify-between
                           border-b border-[#DDE9E8]
                           px-4">

                    <div
                        id="buyerSidebarLogo"
                        class="flex min-w-0
                               items-center gap-3
                               overflow-hidden">

                        <div
                            class="flex h-9 w-9 shrink-0
                                   items-center justify-center
                                   rounded-lg
                                   bg-[#056663]
                                   text-white font-bold">

                            B

                        </div>


                        <div class="sidebar-text min-w-0">

                            <h3
                                class="truncate font-semibold
                                       text-gray-800">

                                Buyer Panel

                            </h3>

                            <p
                                class="text-xs text-[#64748B]">

                                Marketplace

                            </p>

                        </div>

                    </div>


                    {{-- Mobile Close --}}
                    <button
                        type="button"
                        id="buyerSidebarClose"
                        aria-label="Close buyer sidebar"
                        class="rounded-lg p-2
                               text-[#64748B]
                               transition
                               hover:bg-[#E8F5F4]
                               hover:text-[#056663]
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
                     BUYER INFORMATION
                ================================================== --}}

                <div
                    class="shrink-0 border-b
                           border-[#DDE9E8]
                           px-4 py-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0
                                   items-center justify-center
                                   rounded-full
                                   bg-[#E8F5F4]
                                   text-[#056663]
                                   font-semibold">

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        </div>


                        <div class="sidebar-text min-w-0">

                            <p
                                class="truncate text-sm
                                       font-semibold
                                       text-gray-800">

                                {{ auth()->user()->name }}

                            </p>

                            <p
                                class="truncate text-xs
                                       text-[#64748B]">

                                Buyer

                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     NAVIGATION
                ================================================== --}}

                <nav
                    class="flex-1 overflow-y-auto
                           px-3 py-4">


                    <p
                        class="sidebar-text mb-2 px-3
                               text-xs font-semibold
                               uppercase tracking-wider
                               text-[#94A3B8]">

                        Main Menu

                    </p>


                    {{-- Dashboard --}}
                    <a
                        href="{{ route('buyer.dashboard') }}"
                        title="Dashboard"
                        class="buyer-sidebar-link
                               {{ request()->routeIs('buyer.dashboard')
                                    ? 'active'
                                    : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            Dashboard
                        </span>

                    </a>


                    {{-- Profile --}}
                    <a
                        href="{{ route('profile.edit') }}"
                        title="Profile"
                        class="buyer-sidebar-link
                               {{ request()->routeIs('profile.*')
                                    ? 'active'
                                    : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            Profile
                        </span>

                    </a>


                    {{-- Browse Listings --}}
                    <a
                        href="{{ route('listings.index') }}"
                        title="Browse Listings"
                        class="buyer-sidebar-link
                               {{ request()->routeIs('listings.index')
                                    ? 'active'
                                    : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m2.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            Browse Listings
                        </span>

                    </a>



                    {{-- My Enquiries --}}
                    <a
                        href="{{ route('buyer.enquiries.index') }}"
                        title="My Enquiries"
                        class="buyer-sidebar-link
           {{ request()->routeIs('buyer.enquiries.*')
                ? 'active'
                : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 10h8M8 14h5m7-2a8 8 0 11-16 0 8 8 0 0116 0z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            My Enquiries
                        </span>

                    </a>



                    {{-- My Requirement --}}
                    <a
                        href="{{ route('buyer.requirement.index') }}"
                        title="My Requirement"
                        class="buyer-sidebar-link
                               {{ request()->routeIs('buyer.requirement.*')
                                    ? 'active'
                                    : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 10h8M8 14h5m7-2a8 8 0 11-16 0 8 8 0 0116 0z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            My Requirement
                        </span>

                    </a>




                    {{-- RFQs --}}
                    <a
                        href="{{ route('buyer.rfqs.index') }}"
                        title="My RFQs"
                        class="buyer-sidebar-link
           {{ request()->routeIs('buyer.rfqs.*')
                ? 'active'
                : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586L19 9.414V19a2 2 0 01-2 2z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            My RFQs
                        </span>

                    </a>


                    {{-- Quotations --}}
                    <a
                        href="{{ route('buyer.quotations.index') }}"

                        title="Quotations"
                        class="buyer-sidebar-link
                               {{ request()->routeIs('buyer.quotations.*')
                                    ? 'active'
                                    : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 14l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            Quotations
                        </span>

                    </a>


                    {{-- My Orders --}}
                    <a
                        href="{{ route('buyer.orders.index') }}"
                        title="My Orders"
                        class="buyer-sidebar-link
        {{ request()->routeIs('buyer.orders.*') ? 'active' : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 2a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            My Orders
                        </span>
                    </a>



                    {{-- Order Tracking --}}
                    <a
                        href="{{ route('buyer.orders.index') }}"
                        title="Order Tracking"
                        class="buyer-sidebar-link
        {{ request()->routeIs('buyer.orders.show') ? 'active' : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 20l-5-2V6l5 2 6-2 5 2v12l-5-2-6 2zm0-12v12m6-14v12" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            Order Tracking
                        </span>
                    </a>


                      {{--Payment--}}
                    <a
                        href="{{ route('buyer.payments.index') }}"
                        title="Order Tracking"
                        class="buyer-sidebar-link
        {{ request()->routeIs('buyer.payments.show') ? 'active' : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 20l-5-2V6l5 2 6-2 5 2v12l-5-2-6 2zm0-12v12m6-14v12" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            Payments
                        </span>
                    </a>



                    {{-- My Transcation --}}
                    <a
                        href="{{ route('buyer.transactions.index') }}"
                        title="My Orders"
                        class="buyer-sidebar-link
        {{ request()->routeIs('buyer.transactions.*') ? 'active' : '' }}">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 2a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            My Transactions
                        </span>
                    </a>


                    {{-- Saved Listings --}}
                    <a
                        href="{{ route('buyer.saved-listings.index') }}"
                        title="Saved Listings"
                        class="buyer-sidebar-link">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3-7 3V5z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            Saved Listings
                        </span>

                    </a>


                    {{-- Compare --}}
                    <a
                        href="{{ route('buyer.compare.index') }}"
                        title="Compare Listings"
                        class="buyer-sidebar-link">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7v10m0 0l-3-3m3 3l3-3m8-4V7m0 0l-3 3m3-3l3 3M3 4h18M3 20h18" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            Compare Listings
                        </span>

                    </a>


                    {{-- My Bids --}}
                    <a
                        href="{{ route('buyer.bids.index') }}"
                        title="My Bids"
                        class="buyer-sidebar-link">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-2.21 0-4 1.12-4 2.5S9.79 13 12 13s4 1.12 4 2.5S14.21 18 12 18m0-10V6m0 12v-2m0-12a6 6 0 100 12 6 6 0 000-12z" />

                        </svg>

                        <span class="sidebar-text whitespace-nowrap">
                            My Bids
                        </span>

                    </a>

{{-- Reviews --}}
<a
    href="{{ route('buyer.reviews.index') }}"
    title="Reviews & Ratings"
    class="buyer-sidebar-link">

    <svg
        class="h-5 w-5 shrink-0"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24">

        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.914c.969 0 1.371 1.24.588 1.81l-3.977 2.89a1 1 0 00-.363 1.118l1.519 4.674c.3.921-.755 1.688-1.539 1.118l-3.977-2.89c-.784-.57-1.838-.57-1.539-1.118l1.519-4.674a1 1 0 00-.363-1.118l-3.977-2.89c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69l1.519-4.674z" />
    </svg>

    <span>
        Reviews & Ratings
    </span>
</a>

                </nav>


                {{-- =================================================
                     LOGOUT
                ================================================== --}}

                <div
                    class="shrink-0 border-t
                           border-[#DDE9E8] p-3">

                    <form
                        method="POST"
                        action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
                            title="Logout"
                            class="buyer-sidebar-link w-full
                                   text-left">

                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />

                            </svg>

                            <span class="sidebar-text whitespace-nowrap">
                                Logout
                            </span>

                        </button>

                    </form>

                </div>

            </aside>


            {{-- =================================================
                 MAIN CONTENT
            ================================================== --}}

            <main
                id="buyerMainContent"
                class="min-w-0 flex-1">

                {{ $slot }}

            </main>

        </div>

    </div>


    {{-- =========================================================
         BUYER SIDEBAR CSS
    ========================================================== --}}

    <style>
        .buyer-sidebar-link {

            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;

            padding: 0.7rem 0.75rem;
            margin-bottom: 0.25rem;

            border-radius: 0.6rem;

            color: #64748B;

            font-size: 0.875rem;
            font-weight: 500;

            transition:
                background-color 0.2s ease,
                color 0.2s ease;

        }


        .buyer-sidebar-link:hover {

            background: #EAF5F4;
            color: #056663;

        }


        .buyer-sidebar-link.active {

            background: #E8F5F4;
            color: #056663;
            font-weight: 600;

        }


        /* =====================================================
           COLLAPSED DESKTOP
        ====================================================== */

        @media (min-width: 1024px) {

            #buyerSidebar.collapsed {

                width: 72px;

            }


            #buyerSidebar.collapsed .sidebar-text {

                display: none;

            }


            #buyerSidebar.collapsed .buyer-sidebar-link {

                justify-content: center;

                padding-left: 0.75rem;
                padding-right: 0.75rem;

            }


            #buyerSidebar.collapsed #buyerSidebarLogo {

                justify-content: center;

            }

        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 1023px) {

            #buyerSidebar {

                width: 280px;

            }


            #buyerSidebar.mobile-open {

                transform: translateX(0);

            }

        }
    </style>


    {{-- =========================================================
         BUYER SIDEBAR JAVASCRIPT
    ========================================================== --}}

    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                const sidebar =
                    document.getElementById(
                        'buyerSidebar'
                    );

                const headerToggle =
                    document.getElementById(
                        'buyerHeaderToggle'
                    );

                const close =
                    document.getElementById(
                        'buyerSidebarClose'
                    );

                const overlay =
                    document.getElementById(
                        'buyerSidebarOverlay'
                    );


                if (
                    !sidebar ||
                    !headerToggle
                ) {

                    return;

                }


                /* =================================================
                   RESTORE SIDEBAR STATE
                ================================================== */

                if (
                    window.innerWidth >= 1024 &&
                    localStorage.getItem(
                        'buyerSidebarCollapsed'
                    ) === 'true'
                ) {

                    sidebar.classList.add(
                        'collapsed'
                    );

                    headerToggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }


                /* =================================================
                   OPEN MOBILE SIDEBAR
                ================================================== */

                function openMobileSidebar() {

                    sidebar.classList.add(
                        'mobile-open'
                    );

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

                    headerToggle.setAttribute(
                        'aria-expanded',
                        'true'
                    );

                }


                /* =================================================
                   CLOSE MOBILE SIDEBAR
                ================================================== */

                function closeMobileSidebar() {

                    sidebar.classList.remove(
                        'mobile-open'
                    );

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

                    headerToggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }


                /* =================================================
                   HEADER TOGGLE
                ================================================== */

                headerToggle.addEventListener(
                    'click',
                    function() {

                        if (
                            window.innerWidth >= 1024
                        ) {

                            sidebar.classList.toggle(
                                'collapsed'
                            );

                            const collapsed =
                                sidebar.classList.contains(
                                    'collapsed'
                                );

                            localStorage.setItem(
                                'buyerSidebarCollapsed',
                                collapsed
                            );

                            headerToggle.setAttribute(
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


                /* =================================================
                   MOBILE CLOSE
                ================================================== */

                if (close) {

                    close.addEventListener(
                        'click',
                        function() {

                            closeMobileSidebar();

                        }
                    );

                }


                /* =================================================
                   OVERLAY
                ================================================== */

                if (overlay) {

                    overlay.addEventListener(
                        'click',
                        function() {

                            closeMobileSidebar();

                        }
                    );

                }


                /* =================================================
                   MOBILE MENU CLOSE
                ================================================== */

                document
                    .querySelectorAll(
                        '.buyer-sidebar-link'
                    )
                    .forEach(
                        function(link) {

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

                        }
                    );


                /* =================================================
                   ESC KEY
                ================================================== */

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


                /* =================================================
                   RESIZE
                ================================================== */

                window.addEventListener(
                    'resize',
                    function() {

                        if (
                            window.innerWidth >= 1024
                        ) {

                            sidebar.classList.remove(
                                '-translate-x-full'
                            );

                            sidebar.classList.remove(
                                'mobile-open'
                            );

                            if (overlay) {

                                overlay.classList.add(
                                    'hidden'
                                );

                            }

                            document.body.classList.remove(
                                'overflow-hidden'
                            );


                            const savedState =
                                localStorage.getItem(
                                    'buyerSidebarCollapsed'
                                );

                            if (
                                savedState === 'true'
                            ) {

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

            }
        );



        /* =================================================
                   BUYER NOTIFICATION DROPDOWN
                ================================================== */

        const notificationButton =
            document.getElementById(
                'buyerNotificationButton'
            );

        const notificationDropdown =
            document.getElementById(
                'buyerNotificationDropdown'
            );

        const notificationWrapper =
            document.getElementById(
                'buyerNotificationWrapper'
            );


        if (
            notificationButton &&
            notificationDropdown
        ) {

            notificationButton.addEventListener(
                'click',
                function(event) {

                    event.stopPropagation();

                    notificationDropdown.classList.toggle(
                        'hidden'
                    );

                }
            );


            document.addEventListener(
                'click',
                function(event) {

                    if (
                        notificationWrapper &&
                        !notificationWrapper.contains(
                            event.target
                        )
                    ) {

                        notificationDropdown.classList.add(
                            'hidden'
                        );

                    }

                }
            );

        }
    </script>

</body>

</html>