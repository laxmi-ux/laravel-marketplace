
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login | Marketplace</title>

    {{-- Google Material Symbols --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0..200&display=swap"
        rel="stylesheet"
    >

    {{-- Material Symbols Styling --}}
    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;

            font-variation-settings:
                'FILL' 0,
                'wght' 500,
                'GRAD' 0,
                'opsz' 24;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="min-h-screen bg-slate-50">

    <div class="flex min-h-screen items-center justify-center px-4 py-10">

        <div class="w-full max-w-md">

            {{-- Logo / Brand --}}
            <div class="mb-8 text-center">

                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#E8F5F4]"
                >

                    <span class="material-symbols-outlined text-3xl text-[#056663]">
                        admin_panel_settings
                    </span>

                </div>

                <h1 class="mt-5 text-2xl font-bold text-gray-800">
                    Admin Panel
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Sign in to manage the marketplace
                </p>

            </div>


            {{-- Login Card --}}
            <div
                class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100 sm:p-8"
            >

                @if($errors->any())

                    <div
                        class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4"
                    >

                        <div class="flex gap-3">

                            <span class="material-symbols-outlined text-red-500">
                                error
                            </span>

                            <div>

                                @foreach($errors->all() as $error)

                                    <p class="text-sm text-red-700">
                                        {{ $error }}
                                    </p>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @endif


                <form
                    method="POST"
                    action="{{ route('admin.login.submit') }}"
                    class="space-y-5"
                >

                    @csrf


                    {{-- Email --}}
                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Email Address
                        </label>

                        <div class="relative">

                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                            >
                                mail
                            </span>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="admin@example.com"
                                class="w-full rounded-xl border-gray-200 py-3 pl-11 pr-4 text-sm focus:border-[#056663] focus:ring-[#056663]"
                            >

                        </div>

                    </div>


                    {{-- Password --}}
                    <div>

                        <label
                            for="password"
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Password
                        </label>

                        <div class="relative">

                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                            >
                                lock
                            </span>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="w-full rounded-xl border-gray-200 py-3 pl-11 pr-4 text-sm focus:border-[#056663] focus:ring-[#056663]"
                            >

                        </div>

                    </div>


                    {{-- Remember --}}
                    <div class="flex items-center">

                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300 text-[#056663] focus:ring-[#056663]"
                            >

                            <span class="text-sm text-gray-600">
                                Remember me
                            </span>

                        </label>

                    </div>


                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#056663] px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-[#045451]"
                    >

                        <span class="material-symbols-outlined text-[20px]">
                            login
                        </span>

                        Sign In to Admin Panel

                    </button>

                </form>

            </div>


            <p class="mt-6 text-center text-xs text-gray-400">
                Marketplace Administration
            </p>

        </div>

    </div>

</body>

</html>
