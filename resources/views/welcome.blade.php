<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Cluckory - Welcome</title>

    {{-- Tailwind CSS --}}
    @vite('resources/css/app.css')

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
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

<body class="bg-white text-gray-900 antialiased">

    {{-- Header --}}
    <header class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">

            {{-- Logo --}}
            <div>
                <span class="text-2xl font-black tracking-tight italic text-red-600 heading-font">
                    Cluckory
                </span>
            </div>

            {{-- Auth Buttons --}}
            <div class="flex items-center gap-4">

                {{-- Login --}}
                <a
                    href="{{ route('login') }}"
                    class="px-6 py-2 font-semibold text-gray-700 hover:text-red-600 transition"
                >
                    Login
                </a>

                {{-- Register --}}
                <a
                    href="{{ route('register') }}"
                    class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-sm hover:bg-red-700 transition"
                >
                    Register
                </a>

            </div>
        </div>
    </header>

    {{-- Main --}}
    <main class="pt-24">

        {{-- Hero Section --}}
        <section class="min-h-screen flex items-center justify-center px-6 py-16">

            <div class="max-w-4xl w-full text-center">

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-200 rounded-full text-sm font-semibold mb-6">
                    <span class="material-symbols-outlined text-[18px]">
                        local_fire_department
                    </span>
                    Hottest fried chicken in town
                </div>

                {{-- Main Title --}}
                <h1 class="text-5xl md:text-7xl font-black leading-tight heading-font mb-6">
                    Crunchy. Juicy.
                    <br>
                    <span class="text-red-600">
                        Irresistible.
                    </span>
                </h1>

                {{-- Description --}}
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed max-w-2xl mx-auto">
                    Experience the ultimate gold-standard crunch.
                    Crafted with passion, delivered with speed.
                    Your next craving starts here at Cluckory.
                </p>

            </div>

        </section>

    </main>

    {{-- Footer --}}
    <footer class="border-t bg-gray-50 py-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">

            <div>
                <span class="text-lg font-bold text-red-600 heading-font">
                    Cluckory
                </span>
                <p class="text-sm text-gray-500 mt-2">
                    © 2024 Cluckory Food Delivery. Crafted for hunger.
                </p>
            </div>

            <div class="flex gap-6 text-sm text-gray-500">
                <a href="#" class="hover:text-red-600 transition">
                    Contact
                </a>

                <a href="#" class="hover:text-red-600 transition">
                    Delivery Area
                </a>

                <a href="#" class="hover:text-red-600 transition">
                    Privacy Policy
                </a>
            </div>

        </div>
    </footer>

</body>
</html>