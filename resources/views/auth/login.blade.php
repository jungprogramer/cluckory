{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cluckory</title>

    {{-- Tailwind CSS --}}
    @vite('resources/css/app.css')

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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

        .squishy:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 selection:bg-red-200">

    {{-- Header --}}
    <header class="fixed top-0 w-full z-50 py-4 bg-white/95 backdrop-blur-md px-6 lg:px-10">
        <div class="max-w-7xl mx-auto flex justify-center md:justify-start">
            <span class="text-2xl font-black text-red-600 italic tracking-tight heading-font">
                Cluckory
            </span>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="min-h-screen flex items-center justify-center p-6 pt-24">
        <div class="grid grid-cols-1 md:grid-cols-2 max-w-5xl px-12 w-full bg-white rounded-xl overflow-hidden shadow-lg">

            {{-- Left Side Image --}}
            <div class="hidden md:block relative min-h-[600px] bg-red-600 overflow-hidden">

                <img
                    src="{{ asset('image/main photo.jpg') }}"
                    alt="Cluckory Login Image"
                    class="absolute inset-0 w-full h-full object-cover opacity-80"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-red-700 to-transparent opacity-60"></div>

                <div class="absolute bottom-10 left-8 right-8 text-white">
                    <h2 class="heading-font text-5xl font-bold mb-2">
                        Taste the crunch.
                    </h2>
                    <p class="text-lg text-white/90">
                        Join thousands of foodies ordering from Cluckory every single day.
                    </p>
                </div>
            </div>

            {{-- Right Side Form --}}
            <div class="p-8 md:p-14 flex flex-col justify-center">

                <div class="mb-10">
                    <h1 class="heading-font text-4xl font-bold mb-2">
                        Welcome Back!
                    </h1>
                    <p class="text-gray-500">
                        Log in to your account to start ordering your favorites.
                    </p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block font-semibold mb-2">
                            Email or Phone Number
                        </label>

                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                alternate_email
                            </span>

                            <input
                                type="text"
                                id="email"
                                name="email"
                                placeholder="naufal@gmail.com"
                                required
                                class="w-full pl-12 pr-4 py-4 bg-gray-100 rounded-lg border border-transparent focus:border-red-500 focus:outline-none"
                            >
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <label for="password" class="block font-semibold">
                                Password
                            </label>
 
                        </div>

                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                lock
                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                class="w-full pl-12 pr-4 py-4 bg-gray-100 rounded-lg border border-transparent focus:border-red-500 focus:outline-none"
                            >
                        </div>
                    </div>

                    {{-- Login Button --}}
                    <button
                        type="submit"
                        class="w-full py-4 bg-red-600 text-white text-lg font-semibold rounded-lg shadow-md hover:bg-red-700 transition squishy"
                    >
                        Login
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="mt-10 text-center">
                    <p class="text-gray-500">
                        Don't have an account?

                        <a
                            href="{{ route('register') }}"
                            class="text-orange-600 font-semibold hover:underline"
                        >
                            Create an Account
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full border-t bg-white py-6">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <span class="text-sm text-gray-400">
                © 2024 Cluckory Food Delivery. Stay Hungry.
            </span>
        </div>
    </footer>

</body>
</html>