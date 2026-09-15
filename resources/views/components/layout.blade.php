<!doctype html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiBLiB</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</head>

<body class="bg-green-900 text-white font-hanken-grotesk">

    <div class="px-4 sm:px-6 lg:px-10">
        {{-- NAVBAR --}}
        <nav x-data="{ open: false, userMenu: false }" class="bg-green-mint border-b border-white/10 py-2">
            <div class="mx-auto max-w-7xl">
                <div class="flex h-16 items-center justify-between">


                    {{-- ЛИЈЕВО: ЛОГО СА ГЛАВНИМ ЛИНКОВИМА --}}
                    <div class="relative ml-3" x-data="{ logoAppMenu: false }" @click.away="logoAppMenu = false">
                        <div>
                            <button @click="logoAppMenu = !logoAppMenu" type="button"
                                :class="logoAppMenu ||
                                    {{ request()->is('home*', 'privacy*', 'about-app*', 'users*') ? 'true' : 'false' }} ?
                                    'ring-2 ring-white ring-offset-2 ring-offset-green-800 scale-105' :
                                    'focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-800'"
                                class="relative flex max-w-xs items-center rounded-full bg-green-800 text-sm transition-all duration-300 ease-in-out focus:outline-none"
                                id="logoapp-menu-button" :aria-expanded="logoAppMenu" aria-haspopup="true">
                                <span class="sr-only">Отвори мени</span>
                                <img src="{{ Vite::asset('resources/images/logo.svg') }}" alt="BiBLiB logo"
                                    class="h-10 w-10">
                            </button>
                        </div>

                        <div x-show="logoAppMenu" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="logoapp-menu-button"
                            tabindex="-1">
                            <!-- Садржај падајућег менија -->
                            @auth
                                <a href="/home" role="menuitem"
                                    class="block rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100">Почетна</a>
                                <a href="/users" role="menuitem"
                                    class="block rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100">Заједница</a>
                            @endauth
                            <a href="/privacy"
                                class="block rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100"
                                role="menuitem">Приватност</a>
                            <a href="/about-app"
                                class="block rounded-md px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100"
                                role="menuitem">О апликацији</a>
                        </div>
                    </div>

                    {{-- ДЕСНО: ДЕСКТОП КОРИСНИЧКИ МЕНИ --}}
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            @auth
                                {{-- ОВАЈ ДИВ МОРА ИМАТИ x-data АКО ГА ВЕЋ НЕМА ИЗНАД --}}
                               
                                <div class="relative ml-3" x-data="{ userMenu: false }" @click.away="userMenu = false">
                                    <div>
                                        <button @click="userMenu = !userMenu" type="button"
                                            :class="userMenu ||
                                                {{ request()->is('loggedUser*', 'settings*') ? 'true' : 'false' }} ?
                                                'ring-2 ring-white ring-offset-2 ring-offset-green-800 scale-105' :
                                                'focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-800'"
                                            class="relative flex max-w-xs items-center rounded-full bg-green-800 text-sm transition-all duration-300 ease-in-out focus:outline-none"
                                            id="user-menu-button" :aria-expanded="userMenu" aria-haspopup="true">
                                            <span class="sr-only">Отвори мени</span>
                                            <img class="h-9 w-9 rounded-full object-cover border border-white/20"
                                                src="{{ Auth::user()->user_photo ? asset('storage/' . Auth::user()->user_photo) : Vite::asset('resources/images/user-placeholder.svg') }}"
                                                alt="{{ Auth::user()->name }}">                                     
                                        </button>
                                    </div>

                                    {{-- Падајући мени --}}
                                    <div x-show="userMenu" x-cloak x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                        tabindex="-1">

                                        <div class="px-4 py-2 border-b border-gray-100">
                                            <p class="text-sm text-gray-900 font-bold truncate">{{ Auth::user()->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                        </div>

                                        <a href="/loggedUser/{{ Auth::user()->id }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem">Профил</a>
                                        <a href="/settings"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem">Подешавања</a>
                                        @if (Auth::user()->role === 'admin')
                                            <a href="/admin/dashboard"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                role="menuitem">Администрација</a>
                                        @endif

                                        <form method="POST" action="/logout" role="none">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 font-semibold"
                                                role="menuitem">
                                                Одјава
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="relative ml-3" x-data="{ libraryMenu: false }" @click.away="libraryMenu = false">
                                    <div>
                                        <button @click="libraryMenu = !libraryMenu" type="button"
                                            :class="libraryMenu ||
                                                {{ request()->is('homeLib*', 'books/create*', 'library/libraryBooks*', 'loans/myLibraryLoans*') ? 'true' : 'false' }} ?
                                                'ring-2 ring-white ring-offset-2 ring-offset-green-800 scale-105' :
                                                'focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-800'"
                                            class="relative flex max-w-xs items-center rounded-full bg-green-800 text-sm transition-all duration-300 ease-in-out focus:outline-none"
                                            id="library-menu-button" :aria-expanded="libraryMenu" aria-haspopup="true">

                                            <span class="sr-only">Отвори мени</span>
                                            <x-library-logo :library="Auth::user()->ownLibrary" :width="40"
                                                class="h-9 w-9 rounded-full object-cover border border-white/20 transition-transform duration-300" />
                                        </button>
                                    </div>
                                    {{-- Садржај падајућег мениja --}}
                                    <div x-show="libraryMenu" x-cloak {{-- Poboljšane tranzicije za sam meni --}}
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        role="menu" aria-orientation="vertical" aria-labelledby="library-menu-button"
                                        tabindex="-1">

                                        <div class="px-4 py-2 border-b border-gray-100">
                                            <p class="text-sm text-gray-700 font-bold truncate">
                                                {{ Auth::user()->ownLibrary->name }}</p>
                                            <p class="text-sm text-gray-700 truncate">Моја библиотека
                                            </p>
                                        </div>
                                        <a href="/library/libraryBooks/{{ Auth::user()->ownLibrary->id ?? '' }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                            role="menuitem">Књиге</a>
                                        <a href="/books/create"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                            role="menuitem"> + Додај нову књигу</a>
                                        <a href="/loans/myLibraryLoans/{{ Auth::user()->ownLibrary->id ?? '' }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                            role="menuitem">ЦИРКУЛАЦИЈА
                                            <p class="text-xs text-gray-500">- Резервације </p>
                                            <p class="text-xs text-gray-500">- Књиге на читању </p>
                                            <p class="text-xs text-gray-500">- Враћене књиге </p>
                                        </a>

                                    </div>
                                </div>

                            @endauth

                            @guest
                                <div class="flex gap-4">
                                    <x-nav-link href="/login">Пријава</x-nav-link>
                                    <x-nav-link href="/register"
                                        class="bg-white/10 px-3 py-1 rounded-lg">Регистрација</x-nav-link>
                                </div>
                            @endguest
                        </div>
                    </div>

                    {{-- МОБИЛНО ДУГМЕ (HAMBURGER) --}}
                    <div class="-mr-2 flex md:hidden">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center justify-center rounded-md p-2 text-white hover:bg-white/10 focus:outline-none transition-colors duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path :class="{ 'hidden': open, 'block': !open }" class="block"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                <path :class="{ 'block': open, 'hidden': !open }" class="hidden"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- МОБИЛНИ МЕНИ --}}
            <div x-show="open" class="md:hidden border-t border-white/10 mt-2" style="display: none;">
                @auth
                    <div class="border-t border-white/10 pb-3 pt-4">
                        <div class="flex items-center px-5">
                            <div class="shrink-0">
                                <x-library-logo :library="Auth::user()->ownLibrary" :width="40"
                                    class="h-10 w-10 rounded-full object-cover border border-white/20" />
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium leading-none text-white">
                                    {{ Auth::user()->ownLibrary->name }}
                                </div>
                                <div class="text-sm font-medium leading-none text-white/60 mt-1">
                                    БИБЛИОТЕКА
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 px-2">
                            <a href="/books/create"
                                class="block rounded-md px-3 py-2 text-base font-medium text-white/70 hover:bg-white/10"
                                role="menuitem">Додај нову књигу</a>
                            <a href="/library/libraryBooks/{{ Auth::user()->ownLibrary->id ?? '' }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-white/70 hover:bg-white/10"
                                role="menuitem">Књиге</a>
                            <a href="/loans/myLibraryLoans/{{ Auth::user()->ownLibrary->id ?? '' }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-white/70 hover:bg-white/10"
                                role="menuitem">ЦИРКУЛАЦИЈА</a>
                        </div>
                    </div>
                    <div class="border-t border-white/10 pb-3 pt-4">
                        <div class="flex items-center px-5">
                            <div class="shrink-0">
                                <img class="h-10 w-10 rounded-full border border-white/20"
                                    src="{{ Auth::user()->user_photo ? asset('storage/' . Auth::user()->user_photo) : Vite::asset('resources/images/user-placeholder.svg') }}"
                                    alt="">
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium leading-none text-white">{{ Auth::user()->name }}
                                </div>
                                <div class="text-sm font-medium leading-none text-white/60 mt-1">
                                    {{ Auth::user()->email }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 px-2">
                            <a href="/loggedUser/{{ Auth::user()->id }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-white/70 hover:bg-white/10">Профил</a>
                            <a href="/settings"
                                class="block rounded-md px-3 py-2 text-base font-medium text-white/70 hover:bg-white/10">Подешавања</a>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-red-300 hover:bg-white/10">Одјава</button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <div class="space-y-1 px-2 pb-3 pt-2">
                        <a href="/login"
                            class="block rounded-md px-3 py-2 text-base font-medium text-white hover:bg-white/10">Пријава</a>
                        <a href="/register"
                            class="block rounded-md px-3 py-2 text-base font-medium text-white hover:bg-white/10">Регистрација</a>
                    </div>
                @endguest
            </div>
        </nav>

        {{-- ГЛАВНИ САДРЖАЈ --}}
        <main class="mt-6 sm:mt-10 max-w-full sm:max-w-[720px] lg:max-w-[986px] mx-auto">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
