<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BiBLiB</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-900 text-white font-hanken-grotesk pb-30">
    <div class="px-10">
        <nav class="flex justify-between items-center py-4 border-b bg-green-mint border-white/10">
            <div>
                <a href="/">
                    <img src="{{ Vite::asset('resources/images/logo.svg') }}" alt="">
                </a>
            </div>

            <!-- Center Side Of Navbar -->
            <div class="space-x-6 font-bold">
                @auth
                    <x-nav-link href="/home" :active="request()->is('home')"> Почетна</x-nav-link>
                    
                    <x-nav-link href="/books" :active="request()->is('books')"> Књиге</x-nav-link>
                    
                    <x-nav-link href="/homeLib/{lib}" :active="request()->is('homeLib')">  {{ Auth::user()->ownLibrary->name }} књиге</x-nav-link>
                  
                    <x-nav-link href="/users" :active="request()->is('users')"> Чланови</x-nav-link>
                    
                    
                    <x-nav-link href="/libraries" :active="request()->is('libraries')">Библиотеке</x-nav-link>
                    
                    <x-nav-link href="/loans" :active="request()->is('loans')"> Задужења</x-nav-link>
                
                    @endauth

                <x-nav-link href="/privacy" :active="request()->is('privacy')"> Услови коришћења </x-nav-link>
                <x-nav-link href="/about-app" :active="request()->is('about-app')"> О Апликацији </x-nav-link>

            </div>
            <!-- Right Side Of Navbar -->
            @auth
             <div class="flex space-x-6 font-bold" >
                    <x-nav-link href="/books/create" :active="request()->is('books/create')">Додај књигу</x-nav-link> 
                  
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="rounded-md px-3 py-2 text-md font-semibold hover:text-blue-800 border border-transparent hover:border-blue-800 group transition-colors duration-300 mr-3" 
                        type="submit">Одјава</button>
                    </form>

                    <x-nav-link href="/users/{{ Auth::user()->id }}" :active="request()->is('users/'.Auth::user()->id)">{{ Auth::user()->name }}</x-nav-link>
                </div>
            @endauth
            @guest
                <div>
                    <x-nav-link href="/register"> Регистрација</x-nav-link>
                    <x-nav-link href="/login"> Пријава</x-nav-link>
                </div>
            @endguest
        </nav>
        
        
        <main class="mt-10 max-w-[986px] mx-auto">
            {{ $slot }}
        </main>
        
    </div>
</body>

</html>
