<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>
        @yield('title', \App\Helpers\SettingHelper::get('site_name', 'Horizon Elite Properties'))
    </title>
    <style>
        @layer base {

            html,
            body {
                margin: 0;
                padding: 0;
            }

            body {
                overscroll-behavior: none;
            }

            main> :first-child {
                margin-top: 0 !important;
            }

            main> :last-child {
                margin-bottom: 0 !important;
            }
        }

        ::-webkit-scrollbar {
            display: none;
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",

            theme: {
                extend: {

                    colors: {
                        "inverse-surface": "#2e3132",
                        "tertiary-fixed": "#ffdbd2",
                        "error-container": "#ffdad6",
                        "on-tertiary-fixed-variant": "#683b2f",
                        "surface-container": "#edeef0",
                        "on-error-container": "#93000a",
                        "surface-container-lowest": "#ffffff",
                        "text-charcoal": "#091E42",
                        "on-tertiary-container": "#ae7668",
                        "secondary-fixed-dim": "#e9c176",
                        "surface-variant": "#e1e2e4",
                        "secondary": "#775a19",
                        "on-primary-fixed": "#0a1b39",
                        "on-tertiary-fixed": "#341108",
                        "primary-container": "#0a1b39",
                        "border-light": "#E2E8F0",
                        "outline-variant": "#c5c6ce",
                        "secondary-container": "#fed488",
                        "on-tertiary": "#ffffff",
                        "error": "#ba1a1a",
                        "background": "#f8f9fb",
                        "secondary-fixed": "#ffdea5",
                        "on-primary": "#ffffff",
                        "outline": "#75777e",
                        "surface-container-highest": "#e1e2e4",
                        "tertiary-container": "#341108",
                        "tertiary-fixed-dim": "#f8b7a7",
                        "primary": "#000000",
                        "on-secondary-container": "#785a1a",
                        "on-secondary-fixed": "#261900",
                        "primary-fixed-dim": "#b7c6ed",
                        "surface-bright": "#f8f9fb",
                        "surface-container-low": "#f3f4f6",
                        "on-surface-variant": "#45474e",
                        "on-surface": "#191c1e",
                        "on-primary-fixed-variant": "#384667",
                        "tertiary": "#000000",
                        "on-background": "#191c1e",
                        "surface-tint": "#4f5e80",
                        "inverse-primary": "#b7c6ed",
                        "on-error": "#ffffff",
                        "surface-dim": "#d9dadc",
                        "surface": "#f8f9fb",
                        "inverse-on-surface": "#f0f1f3",
                        "surface-container-high": "#e7e8ea",
                        "accent-teal": "#0078DB"
                    },

                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },

                    spacing: {
                        "container-max": "1280px",
                        "margin-desktop": "40px",
                        "margin-mobile": "16px",
                        "base": "8px",
                        "gutter": "24px"
                    },

                    fontFamily: {
                        "body-lg": ["Inter"],
                        "display-lg": ["Hanken Grotesk"],
                        "label-md": ["Inter"],
                        "headline-lg-mobile": ["Hanken Grotesk"],
                        "label-sm": ["Inter"],
                        "headline-md": ["Hanken Grotesk"],
                        "body-md": ["Inter"],
                        "headline-lg": ["Hanken Grotesk"]
                    },

                    fontSize: {
                        "body-lg": ["18px", {
                            lineHeight: "28px",
                            fontWeight: "400"
                        }],

                        "display-lg": ["48px", {
                            lineHeight: "56px",
                            letterSpacing: "-0.02em",
                            fontWeight: "700"
                        }],

                        "label-md": ["14px", {
                            lineHeight: "20px",
                            letterSpacing: "0.01em",
                            fontWeight: "600"
                        }],

                        "headline-lg-mobile": ["24px", {
                            lineHeight: "32px",
                            fontWeight: "600"
                        }],

                        "label-sm": ["12px", {
                            lineHeight: "16px",
                            fontWeight: "500"
                        }],

                        "headline-md": ["24px", {
                            lineHeight: "32px",
                            fontWeight: "500"
                        }],

                        "body-md": ["16px", {
                            lineHeight: "24px",
                            fontWeight: "400"
                        }],

                        "headline-lg": ["32px", {
                            lineHeight: "40px",
                            letterSpacing: "-0.01em",
                            fontWeight: "600"
                        }]
                    }
                }
            }
        }
    </script>

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <link
        href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100..900&family=Inter:wght@100..900&display=swap"
        rel="stylesheet" />

</head>

<body class="bg-background font-body-md text-on-background">

    <header class="fixed top-0 w-full z-50 bg-surface-container-lowest/80 backdrop-blur-xl shadow-[0_1px_8px_rgba(4,21,51,0.04)]">

        <div class="h-20 max-w-[1480px] mx-auto px-margin-mobile lg:px-margin-desktop flex items-center justify-between gap-gutter">

            <!-- =====================================================
             LOGO
        ====================================================== -->

<div class="flex items-center gap-base shrink-0">
    <a href="{{ route('home') }}">
        <img
            src="{{ route('marketplace.logo') }}"
            alt="Marketplace Logo"
            class="h-20 w-auto object-contain"
        >
    </a>
</div>



            <!-- =====================================================
             MAIN NAVIGATION
        ====================================================== -->

            <nav class="hidden md:flex items-center justify-center gap-8 flex-1">

                <!-- HOME -->

                <a
                    href="{{ route('home') }}"
                    class="transition-colors text-primary font-bold">
                    Home
                </a>


                <!-- =================================================
     PROPERTIES
================================================== -->

                <div class="relative dropdown-container">

                    <button
                        type="button"
                        onclick="toggleDropdown('propertiesDropdown')"
                        class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors flex items-center gap-1">
                        Properties

                        <span
                            id="propertiesArrow"
                            class="material-symbols-outlined text-[17px] transition-transform">
                            expand_more
                        </span>
                    </button>

                    <div
                        id="propertiesDropdown"
                        class="absolute left-0 top-full mt-2 hidden
        w-56 bg-surface-container-lowest rounded-lg
        shadow-lg border border-outline-variant py-2 z-50">

                        <!-- All Properties -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            All Properties
                        </a>

                        <!-- Residential -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'residential']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Residential
                        </a>

                        <!-- Commercial -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'commercial']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Commercial
                        </a>

                        <!-- Industrial -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'industrial']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Industrial
                        </a>

                        <!-- Agricultural -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'agricultural']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Agricultural
                        </a>

                        <!-- Rental -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'rental']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Rental
                        </a>

                        <!-- For Sale -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'for-sale']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            For Sale
                        </a>

                        <!-- For Lease -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'for-lease']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            For Lease
                        </a>

                        <!-- New Projects -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'new-projects']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            New Projects
                        </a>

                        <!-- Under-Construction Projects -->
                        <a
                            href="{{ route('listings.index', ['category' => 'properties', 'subcategory' => 'under-construction-projects']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Under-Construction Projects
                        </a>

                    </div>

                </div>

                <!-- =================================================
     LAND
================================================== -->

                <div class="relative dropdown-container">

                    <button
                        type="button"
                        onclick="toggleDropdown('landDropdown')"
                        class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors flex items-center gap-1">
                        Land

                        <span
                            id="landArrow"
                            class="material-symbols-outlined text-[17px] transition-transform">
                            expand_more
                        </span>
                    </button>

                    <div
                        id="landDropdown"
                        class="absolute left-0 top-full mt-2 hidden
        w-60 bg-surface-container-lowest rounded-lg
        shadow-lg border border-outline-variant py-2 z-50">

                        <!-- All Land -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            All Land
                        </a>

                        <!-- Residential Development -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land', 'subcategory' => 'residential-development']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Residential Development
                        </a>

                        <!-- Commercial Development -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land', 'subcategory' => 'commercial-development']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Commercial Development
                        </a>

                        <!-- Industrial Development -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land', 'subcategory' => 'industrial-development']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Industrial Development
                        </a>

                        <!-- Agriculture -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land', 'subcategory' => 'agriculture']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Agriculture
                        </a>

                        <!-- Warehousing -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land', 'subcategory' => 'warehousing']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Warehousing
                        </a>

                        <!-- Solar Projects -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land', 'subcategory' => 'solar-projects']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Solar Projects
                        </a>

                        <!-- Infrastructure Projects -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land', 'subcategory' => 'infrastructure-projects']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Infrastructure Projects
                        </a>

                        <!-- Investment -->
                        <a
                            href="{{ route('listings.index', ['category' => 'land', 'subcategory' => 'investment']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Investment
                        </a>

                    </div>

                </div>

                <!-- =================================================
     Products
================================================== -->

                <div class="relative dropdown-container">

                    <button
                        type="button"
                        onclick="toggleDropdown('materialsDropdown')"
                        class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors flex items-center gap-1">
                        Products

                        <span
                            id="materialsArrow"
                            class="material-symbols-outlined text-[17px] transition-transform">
                            expand_more
                        </span>
                    </button>

                    <div
                        id="materialsDropdown"
                        class="absolute left-0 top-full mt-2 hidden
        w-52 bg-surface-container-lowest rounded-lg
        shadow-lg border border-outline-variant py-2 z-50">

                        <!-- All Products -->
                        <a
                            href="{{ route('products.index') }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            All Products
                        </a>

                        <!-- Cement -->
                        <a
                            href="{{ route('products.index', ['subcategory' => 'cement']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Cement
                        </a>

                        <!-- Steel -->
                        <a
                            href="{{ route('products.index', ['subcategory' => 'steel']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Steel
                        </a>

                        <!-- Bricks & Blocks -->
                        <a
                            href="{{ route('products.index', ['subcategory' => 'bricks-blocks']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Bricks & Blocks
                        </a>

                        <!-- Tiles -->
                        <a
                            href="{{ route('products.index', ['subcategory' => 'tiles']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Tiles
                        </a>

                    </div>

                </div>
                <!-- =================================================
     SERVICES
================================================== -->

                <div class="relative dropdown-container">

                    <button
                        type="button"
                        onclick="toggleDropdown('servicesDropdown')"
                        class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors flex items-center gap-1">
                        Services

                        <span
                            id="servicesArrow"
                            class="material-symbols-outlined text-[17px] transition-transform">
                            expand_more
                        </span>
                    </button>

                    <div
                        id="servicesDropdown"
                        class="absolute left-0 top-full mt-2 hidden
        w-60 bg-surface-container-lowest rounded-lg
        shadow-lg border border-outline-variant py-2 z-50">

                        <!-- All Services -->
                        <a
                            href="{{ route('listings.index', ['category' => 'services']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            All Services
                        </a>

                        <!-- Civil Contractors -->
                        <a
                            href="{{ route('listings.index', ['category' => 'services', 'subcategory' => 'civil-contractors']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Civil Contractors
                        </a>

                        <!-- Electrical Contractors -->
                        <a
                            href="{{ route('listings.index', ['category' => 'services', 'subcategory' => 'electrical-contractors']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Electrical Contractors
                        </a>

                        <!-- Plumbing Contractors -->
                        <a
                            href="{{ route('listings.index', ['category' => 'services', 'subcategory' => 'plumbing-contractors']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Plumbing Contractors
                        </a>

                        <!-- HVAC Contractors -->
                        <a
                            href="{{ route('listings.index', ['category' => 'services', 'subcategory' => 'hvac-contractors']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            HVAC Contractors
                        </a>

                        <!-- Structural Contractors -->
                        <a
                            href="{{ route('listings.index', ['category' => 'services', 'subcategory' => 'structural-contractors']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Structural Contractors
                        </a>

                        <!-- Architects -->
                        <a
                            href="{{ route('listings.index', ['category' => 'services', 'subcategory' => 'architects']) }}"
                            class="block px-4 py-2.5 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">
                            Architects
                        </a>

                    </div>

                </div>

                <!-- =================================================
     PROJECTS
================================================== -->

                <a
                    href="{{ route('listings.index', ['category' => 'projects']) }}"
                    class="hidden xl:block font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors">
                    Projects
                </a>
                <!-- LOCATIONS -->

                <a
                    href="{{ route('locations.index') }}"
                    class="hidden xl:block font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors">
                    Locations
                </a>

            </nav>


            <!-- =====================================================
             RIGHT ACTIONS
        ====================================================== -->

            <div class="flex items-center gap-4 shrink-0">


                <!-- =================================================
                 GUEST USER
            ================================================== -->

                @guest

                <!-- REGISTER -->

                <div class="relative dropdown-container hidden lg:block">

                    <button
                        type="button"
                        onclick="toggleDropdown('registerDropdown')"
                        class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface flex items-center gap-1">

                        Register

                        <span
                            id="registerArrow"
                            class="material-symbols-outlined text-[17px] transition-transform">
                            expand_more
                        </span>

                    </button>


                    <div
                        id="registerDropdown"
                        class="absolute right-0 top-full mt-2 hidden
                        w-56 bg-surface-container-lowest rounded-lg
                        shadow-lg border border-outline-variant py-2 z-50">

                        <a
                            href="{{ route('register') }}"
                            class="block px-4 py-3 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">

                            <div class="font-semibold">
                                Register as Buyer
                            </div>

                            <div class="text-xs opacity-70 mt-1">
                                Find products & properties
                            </div>

                        </a>


                        <a
                            href="{{ route('register') }}"
                            class="block px-4 py-3 text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface">

                            <div class="font-semibold">
                                Register as Seller
                            </div>

                            <div class="text-xs opacity-70 mt-1">
                                List products & properties
                            </div>

                        </a>

                    </div>

                </div>


                <!-- LOGIN -->

                <a
                    href="{{ route('login') }}"
                    class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface">
                    Login
                </a>

                @else


                <!-- =================================================
                     LOGGED-IN USER PROFILE
                ================================================== -->

                <div class="relative dropdown-container hidden lg:block">

                    <button
                        type="button"
                        onclick="toggleDropdown('profileDropdown')"
                        class="flex items-center justify-center
                               w-10 h-10
                               rounded-full
                               text-on-surface-variant
                               hover:text-on-surface
                               hover:bg-surface-container-low
                               transition-colors"
                        aria-label="Account menu">

                        <span class="material-symbols-outlined text-[28px]">
                            account_circle
                        </span>

                    </button>


                    <div
                        id="profileDropdown"
                        class="absolute right-0 top-full mt-3 hidden
                               w-64
                               bg-surface-container-lowest
                               rounded-xl
                               shadow-lg
                               border border-outline-variant
                               overflow-hidden
                               z-50">


                        <!-- USER INFORMATION -->

                        <div class="px-4 py-4 border-b border-outline-variant">

                            <div class="flex items-center gap-3">

                                <span
                                    class="material-symbols-outlined
                                           text-[38px]
                                           text-on-surface-variant">
                                    account_circle
                                </span>

                                <div class="min-w-0">

                                    <p
                                        class="font-semibold
                                               text-on-surface
                                               truncate">
                                        {{ Auth::user()->name }}
                                    </p>

                                    <p
                                        class="text-xs
                                               text-on-surface-variant
                                               truncate">
                                        {{ Auth::user()->email }}
                                    </p>

                                </div>

                            </div>


                            <div class="mt-3">

                                <span
                                    class="inline-flex
                                           items-center
                                           px-2.5
                                           py-1
                                           rounded-full
                                           bg-surface-container-low
                                           text-xs
                                           font-semibold
                                           text-on-surface-variant
                                           capitalize">
                                    {{ Auth::user()->role }}
                                </span>

                            </div>

                        </div>


                        <!-- MY PROFILE -->

                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3
                                   px-4 py-3
                                   text-sm
                                   text-on-surface-variant
                                   hover:bg-surface-container-low
                                   hover:text-on-surface
                                   transition-colors">

                            <span class="material-symbols-outlined text-[20px]">
                                person
                            </span>

                            <span>
                                My Profile
                            </span>

                        </a>


                        <!-- DASHBOARD -->

                        <a
                            href="{{ Auth::user()->role === 'seller'
                                ? route('seller.dashboard')
                                : route('buyer.dashboard') }}"
                            class="flex items-center gap-3
                                   px-4 py-3
                                   text-sm
                                   text-on-surface-variant
                                   hover:bg-surface-container-low
                                   hover:text-on-surface
                                   transition-colors">

                            <span class="material-symbols-outlined text-[20px]">
                                dashboard
                            </span>

                            <span>
                                Dashboard
                            </span>

                        </a>


                        @if(Auth::user()->role === 'seller')

                        <!-- SELLER LISTINGS -->

                        <a
                            href="{{ route('seller.listings.index') }}"
                            class="flex items-center gap-3
                                       px-4 py-3
                                       text-sm
                                       text-on-surface-variant
                                       hover:bg-surface-container-low
                                       hover:text-on-surface
                                       transition-colors">

                            <span class="material-symbols-outlined text-[20px]">
                                inventory_2
                            </span>

                            <span>
                                My Listings
                            </span>

                        </a>


                        <!-- ADD LISTING -->

                        <a
                            href="{{ route('seller.listings.create') }}"
                            class="flex items-center gap-3
                                       px-4 py-3
                                       text-sm
                                       text-on-surface-variant
                                       hover:bg-surface-container-low
                                       hover:text-on-surface
                                       transition-colors">

                            <span class="material-symbols-outlined text-[20px]">
                                add_business
                            </span>

                            <span>
                                List Your Listing
                            </span>

                        </a>


                        <!-- ENQUIRIES -->

                        <a
                            href="{{ route('seller.enquiries.index') }}"
                            class="flex items-center gap-3
                                       px-4 py-3
                                       text-sm
                                       text-on-surface-variant
                                       hover:bg-surface-container-low
                                       hover:text-on-surface
                                       transition-colors">

                            <span class="material-symbols-outlined text-[20px]">
                                mail
                            </span>

                            <span>
                                Enquiries
                            </span>

                        </a>

                        @else

                        <!-- BUYER REQUIREMENTS -->

                        <a
                            href="{{ route('buyer.requirement.index') }}"
                            class="flex items-center gap-3
                                       px-4 py-3
                                       text-sm
                                       text-on-surface-variant
                                       hover:bg-surface-container-low
                                       hover:text-on-surface
                                       transition-colors">

                            <span class="material-symbols-outlined text-[20px]">
                                description
                            </span>

                            <span>
                                My Requirements
                            </span>

                        </a>


                        <!-- SAVED LISTINGS -->

                        <a
                            href="{{ route('buyer.saved-listings.index') }}"
                            class="flex items-center gap-3
                                       px-4 py-3
                                       text-sm
                                       text-on-surface-variant
                                       hover:bg-surface-container-low
                                       hover:text-on-surface
                                       transition-colors">

                            <span class="material-symbols-outlined text-[20px]">
                                favorite
                            </span>

                            <span>
                                Saved Listings
                            </span>

                        </a>

                        @endif


                        <!-- DIVIDER -->

                        <div class="border-t border-outline-variant"></div>


                        <!-- LOGOUT -->

                        <form
                            method="POST"
                            action="{{ route('logout') }}">

                            @csrf

                            <button
                                type="submit"
                                class="w-full
                                       flex items-center gap-3
                                       px-4 py-3
                                       text-sm
                                       text-red-600
                                       hover:bg-red-50
                                       text-left
                                       transition-colors">

                                <span class="material-symbols-outlined text-[20px]">
                                    logout
                                </span>

                                <span>
                                    Log Out
                                </span>

                            </button>

                        </form>

                    </div>

                </div>

                @endguest


                <!-- =================================================
                 SEND REQUIREMENT
            ================================================== -->

                <a
                    href="{{ route('requirement.show') }}"
                    class="hidden lg:flex
                       items-center
                       justify-center
                       bg-primary
                       text-on-primary
                       font-label-md
                       text-label-md
                       px-5
                       py-2.5
                       rounded-lg
                       hover:opacity-90
                       transition-opacity">
                    Send Requirement
                </a>


                <!-- MOBILE MENU -->

                <button
                    class="md:hidden flex items-center"
                    type="button">

                    <span class="material-symbols-outlined text-on-surface">
                        menu
                    </span>

                </button>

            </div>

        </div>

    </header>


    <!-- =============================================================
     DROPDOWN SCRIPT
============================================================== -->

    <script>
        function toggleDropdown(id) {

            const dropdown = document.getElementById(id);

            if (!dropdown) {
                return;
            }

            const dropdowns = [
                'propertiesDropdown',
                'landDropdown',
                'materialsDropdown',
                'servicesDropdown',
                'registerDropdown',
                'profileDropdown'
            ];

            dropdowns.forEach(function(dropdownId) {

                if (dropdownId !== id) {

                    const menu = document.getElementById(dropdownId);

                    if (menu) {
                        menu.classList.add('hidden');
                    }
                }
            });

            dropdown.classList.toggle('hidden');
        }


        document.addEventListener('click', function(event) {

            /*
             * Only keep dropdown open when clicking
             * inside an actual dropdown container.
             */

            if (event.target.closest('.dropdown-container')) {
                return;
            }

            const dropdowns = [
                'propertiesDropdown',
                'landDropdown',
                'materialsDropdown',
                'servicesDropdown',
                'registerDropdown',
                'profileDropdown'
            ];

            dropdowns.forEach(function(dropdownId) {

                const menu = document.getElementById(dropdownId);

                if (menu) {
                    menu.classList.add('hidden');
                }
            });

        });
    </script>


    <!-- =============================================================
     MAIN CONTENT
============================================================== -->

    <main>

        @yield('content')

    </main>


<!-- =============================================================
     PROFESSIONAL MARKETPLACE FOOTER
============================================================== -->

@php
    $logoPath = \App\Helpers\SettingHelper::get('logo_path');
    $siteName = \App\Helpers\SettingHelper::get(
        'site_name',
        'Horizon Elite Properties'
    );
    $contactEmail = \App\Helpers\SettingHelper::get('contact_email');
    $contactPhone = \App\Helpers\SettingHelper::get('contact_phone');
    $businessAddress = \App\Helpers\SettingHelper::get('address');
@endphp

<footer class="w-full bg-[#304766] text-white mt-20">

    <!-- MAIN FOOTER -->
    <div class="max-w-[1480px] mx-auto px-margin-mobile lg:px-margin-desktop
                pt-14 pb-12">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5
                    gap-10 lg:gap-8">

            <!-- BRAND -->
            <div class="sm:col-span-2 lg:col-span-2">

                <a href="{{ route('home') }}"
                   class="inline-flex items-center mb-5">

                    <img
                      src="{{ url('/uploads/logo.png') }}"
                        alt="{{ $siteName }} Logo"
                        class="h-20 w-auto max-w-[220px] object-contain"
                    >
                </a>

                <p class="text-sm leading-7 text-white/75 max-w-md">
                    Your trusted destination for properties, land, building
                    materials, professional services and construction projects.
                    Discover opportunities and connect with the right partners.
                </p>

                <div class="mt-6 flex flex-col gap-3 text-sm text-white/80">

                    @if($contactEmail)
                        <a href="mailto:{{ $contactEmail }}"
                           class="flex items-center gap-3 hover:text-white transition-colors break-all">
                            <span class="material-symbols-outlined text-xl">
                                mail
                            </span>
                            {{ $contactEmail }}
                        </a>
                    @endif

                    @if($contactPhone)
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}"
                           class="flex items-center gap-3 hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-xl">
                                call
                            </span>
                            {{ $contactPhone }}
                        </a>
                    @endif

                </div>
            </div>

            <!-- MARKETPLACE -->
            <div>
                <h3 class="text-base font-semibold mb-5">
                    Marketplace
                </h3>

                <ul class="space-y-3 text-sm text-white/70">

                    <li>
                        <a href="{{ route('home') }}"
                           class="hover:text-white transition-colors">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('listings.index', ['category' => 'properties']) }}"
                           class="hover:text-white transition-colors">
                            Properties
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('listings.index', ['category' => 'land']) }}"
                           class="hover:text-white transition-colors">
                            Land &amp; Plots
                        </a>
                    </li>

                  
                    <li>
                        <a href="{{ route('listings.index', ['category' => 'services']) }}"
                           class="hover:text-white transition-colors">
                            Services
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('listings.index', ['category' => 'projects']) }}"
                           class="hover:text-white transition-colors">
                            Projects
                        </a>
                    </li>

                </ul>
            </div>

            <!-- ACCOUNT -->
            <div>
                <h3 class="text-base font-semibold mb-5">
                    Account
                </h3>

                <ul class="space-y-3 text-sm text-white/70">

                    <li>
                        <a href="{{ url('/login') }}"
                           class="hover:text-white transition-colors">
                            Login
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/register') }}"
                           class="hover:text-white transition-colors">
                            Create Account
                        </a>
                    </li>

                 
                    <li>
                        <a href="{{ url('/seller/listings/create') }}"
                           class="hover:text-white transition-colors">
                            Post a Listing
                        </a>
                    </li>

                </ul>
            </div>

            <!-- SUPPORT & COMPANY -->
            <div>
                <h3 class="text-base font-semibold mb-5">
                    Help &amp; Company
                </h3>

                <ul class="space-y-3 text-sm text-white/70">

                    <li>
                        <a href="{{ url('/about') }}"
                           class="hover:text-white transition-colors">
                            About Us
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/contact') }}"
                           class="hover:text-white transition-colors">
                            Contact Us
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/privacy-policy') }}"
                           class="hover:text-white transition-colors">
                            Privacy Policy
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/terms') }}"
                           class="hover:text-white transition-colors">
                            Terms &amp; Conditions
                        </a>
                    </li>

                    @if($businessAddress)
                        <li class="pt-2 flex items-start gap-2 leading-6">
                            <span class="material-symbols-outlined text-xl">
                                location_on
                            </span>
                            <span>{{ $businessAddress }}</span>
                        </li>
                    @endif

                </ul>
            </div>

        </div>
    </div>

    <!-- FOOTER BOTTOM -->
    <div class="border-t border-white/15">

        <div class="max-w-[1480px] mx-auto px-margin-mobile
                    lg:px-margin-desktop py-5">

            <div class="flex flex-col md:flex-row
                        justify-between items-center gap-4">

                <p class="text-xs sm:text-sm text-white/65 text-center md:text-left">
                    &copy; {{ date('Y') }} {{ $siteName }}.
                    All rights reserved.
                </p>

                <p class="text-xs sm:text-sm text-white/65 text-center">
                    Built for better property and construction connections.
                </p>

            </div>
        </div>
    </div>

</footer>


</body>

</html>