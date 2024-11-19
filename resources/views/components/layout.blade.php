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
                    <x-nav-link href="/home" :active="request()->is('home')"> Home</x-nav-link>
                    <x-nav-link href="/homeLib/{lib}" :active="request()->is('homeLib')"> My Library</x-nav-link>
                    
                    <x-nav-link href="/books" :active="request()->is('books')"> Books</x-nav-link>
                    <x-nav-link href="/clients" :active="request()->is('clients')"> Clients</x-nav-link>
                    <x-nav-link href="/loans" :active="request()->is('loans')"> Loans</x-nav-link>
                    <a href="#">Libraries</a>
                @endauth

                <x-nav-link href="/privacy" :active="request()->is('privacy')"> Privacy </x-nav-link>
                <x-nav-link href="/about-app" :active="request()->is('about-app')"> About app </x-nav-link>

            </div>
            <!-- Right Side Of Navbar -->
            @auth
                <div class="flex space-x-6 font-bold">
                    <a href="/books/create">Add a Book</a>
                    <x-forms.form method="POST" action="/logout" enctype="multipart/form-data">
                        <button type="submit">Log Out</button>
                    </x-forms.form>
                </div>
            @endauth
            @guest
                <div>
                    <x-nav-link href="/register"> Register</x-nav-link>
                    <x-nav-link href="/login"> Log In</x-nav-link>
                </div>
            @endguest
        </nav>
        
        
        <main class="mt-10 max-w-[986px] mx-auto">
            {{ $slot }}
        </main>
        
    </div>
</body>

</html>
