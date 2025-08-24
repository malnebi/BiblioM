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

<body class="bg-black text-white font-hanken-grotesk pb-30">
    <div class="px-10">
        <nav class="flex justify-between items-center py-4 border-b border-white/10">
            <div>
                <a href="/">
                    <img src="{{ Vite::asset('resources/images/logo.svg') }}" alt="">
                </a>
            </div>

            <!-- Center Side Of Navbar -->
            <div class="space-x-6 font-bold">
                @auth
                    <x-nav-link href="/home" :active="request()->is('home')"> Početna</x-nav-link>
                    
                    <x-nav-link href="/books" :active="request()->is('books')"> Knjige</x-nav-link>
                    
                    <x-nav-link href="/homeLib/{lib}" :active="request()->is('homeLib')">  {{ Auth::user()->ownLibrary->name }} knjige</x-nav-link>
                  
                    <x-nav-link href="/users" :active="request()->is('users')"> Korisnici</x-nav-link>
                    
                    
                    <a href="#">Biblioteke</a>
                    
                    <x-nav-link href="/loans" :active="request()->is('loans')"> Zaduženja</x-nav-link>
                
                    @endauth

                <x-nav-link href="/privacy" :active="request()->is('privacy')"> Privatnost </x-nav-link>
                <x-nav-link href="/about-app" :active="request()->is('about-app')"> O Aplikaciji </x-nav-link>

            </div>
            <!-- Right Side Of Navbar -->
            @auth
             <div class="flex space-x-6 font-bold" >
                    <x-nav-link href="/books/create" :active="request()->is('books/create')">Dodaj knjigu</x-nav-link> 
                  
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit">Odjava</button>
                    </form>
                </div>
            @endauth
            @guest
                <div>
                    <x-nav-link href="/register"> Registracija</x-nav-link>
                    <x-nav-link href="/login"> Login</x-nav-link>
                </div>
            @endguest
        </nav>
        
        
        <main class="mt-10 max-w-[986px] mx-auto">
            {{ $slot }}
        </main>
        
    </div>
</body>

</html>
