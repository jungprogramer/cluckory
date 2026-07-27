<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cluckory - Register</title>

    {{-- Tailwind CSS --}}
    @vite('resources/css/app.css')

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .heading-font {
            font-family: 'Epilogue', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">

    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <span class="text-2xl font-black text-red-600 italic tracking-tight heading-font">
                Cluckory
            </span>
        </div>
    </header>

    {{-- Main --}}
    <main class="min-h-screen flex items-center justify-center px-6 py-12">

        <section class="w-full max-w-md bg-white rounded-3xl border border-gray-100 shadow-sm p-8 md:p-12">

            {{-- Title --}}
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold heading-font mb-2">
                    Create an Account
                </h2>
                <p class="text-gray-500">
                    Sign up to start your spicy journey with Cluckory.
                </p>
            </div>

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                {{-- Error Message --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-300 text-red-600 px-4 py-3 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Full Name --}}
                <div>
                    <label for="name" class="block font-semibold mb-2">
                        Full Name
                    </label>

                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            person
                        </span>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            placeholder="Naufal Tunjung"
                            class="w-full pl-12 pr-4 py-4 bg-gray-100 rounded-lg border border-transparent focus:border-red-500 focus:outline-none"
                        >
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block font-semibold mb-2">
                        Email Address
                    </label>

                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            mail
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="naufal@gmail.com"
                            class="w-full pl-12 pr-4 py-4 bg-gray-100 rounded-lg border border-transparent focus:border-red-500 focus:outline-none"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block font-semibold mb-2">
                        Password
                    </label>

                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            lock
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full pl-12 pr-4 py-4 bg-gray-100 rounded-lg border border-transparent focus:border-red-500 focus:outline-none"
                        >
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block font-semibold mb-2">
                        Confirm Password
                    </label>

                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            verified_user
                        </span>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            placeholder="••••••••"
                            class="w-full pl-12 pr-4 py-4 bg-gray-100 rounded-lg border border-transparent focus:border-red-500 focus:outline-none"
                        >
                    </div>
                </div>

                {{-- Terms --}}
                <div class="flex items-start gap-3">
                    <input
                        type="checkbox"
                        id="terms"
                        required
                        class="mt-1 h-5 w-5 rounded border-gray-300 text-red-600 focus:ring-red-500"
                    >

                    <label for="terms" class="text-sm text-gray-500 leading-relaxed">
                        I agree to the
                        <a href="#" class="text-red-600 font-semibold hover:underline">
                            Terms of Service
                        </a>
                        and
                        <a href="#" class="text-red-600 font-semibold hover:underline">
                            Privacy Policy
                        </a>.
                    </label>
                </div>

                {{-- Submit Button --}}
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-4 rounded-xl text-lg font-semibold transition"
                >
                    Register
                </button>
            </form>

            {{-- Login Link --}}
            <p class="text-center text-gray-500 mt-8">
                Already have an account?

                <a
                    href="{{ route('login') }}"
                    class="text-red-600 font-semibold hover:underline"
                >
                    Login
                </a>
            </p>

        </section>

    </main>

</body>
</html>