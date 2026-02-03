<!doctype html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>BiBLiB</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-900 text-white font-hanken-grotesk">

    {{-- 🔵 ИЗМИЈЕЊЕНО: padding responsive --}}
    <div class="px-4 sm:px-6 lg:px-10">

        {{-- ================= NAVBAR ================= --}}
        <nav
            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4 border-b bg-green-mint border-white/10">

            {{-- ЛЕВО: ЛОГО + INFO --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <a href="/about-app">
                    <img src="{{ Vite::asset('resources/images/logo.svg') }}"
                        alt="BiBLiB logo"
                        class="h-10 w-auto">
                </a>

                {{-- 🔵 МОБИЛНИ INFO ЛИНКОВИ --}}
                <ul class="text-sm text-white/80 flex gap-4 sm:hidden">
                    <li><a href="/about-app">О апликацији</a></li>
                    <li><a href="/privacy">Приватност</a></li>
                </ul>
            </div>

            {{-- ЦЕНТАР: ГЛАВНА НАВИГАЦИЈА --}}
            @auth
                <div class="flex justify-center gap-4 font-semibold text-sm sm:text-base">
                    <x-nav-link href="/home" :active="request()->is('home')">Почетна</x-nav-link>
                    <x-nav-link href="/users" :active="request()->is('users')">Заједница</x-nav-link>
                </div>
            @endauth

            {{-- ДЕСНО: КОРИСНИК --}}
            @auth
                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 font-semibold text-sm sm:text-base">

                    <x-nav-link href="/homeLib/{lib}">
                        Моја библиотека
                    </x-nav-link>

                    <x-nav-link href="/loggedUser/{{ Auth::user()->id }}">
                        {{ Auth::user()->name }}
                    </x-nav-link>

                    <form method="POST" action="/logout">
                        @csrf
                        <button
                            class="px-3 py-1 rounded-md border border-white/20 hover:text-red-300 text-sm">
                            Одјава
                        </button>
                    </form>
                </div>
            @endauth

            {{-- GUEST --}}
            @guest
                <div class="flex gap-4 text-sm">
                    <x-nav-link href="/register">Регистрација</x-nav-link>
                    <x-nav-link href="/login">Пријава</x-nav-link>
                </div>
            @endguest
        </nav>

        {{-- ================= MAIN ================= --}}
        <main
            class="mt-6 sm:mt-10 max-w-full sm:max-w-[720px] lg:max-w-[986px] mx-auto">
            {{ $slot }}
        </main>

    </div>
</body>

</html>
