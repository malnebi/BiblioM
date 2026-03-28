@props(['user', 'library'])

<x-panel class="flex flex-col p-6 min-h-[380px] relative">
    
    {{-- КОРИСНИК --}}
    <div class="flex flex-col items-center justify-center flex-grow text-center mt-4">
        {{-- 'active:scale-95' даје ефекат "клилика" на телефону када се додирне --}}
        <a href="/users/{{ $user->id }}" target="_blank" 
           class="group block transition-transform duration-200 active:scale-95" title="Профил">
            
            <div class="relative inline-block">
                <img src="{{ asset('storage/' . $user->user_photo) }}" alt="Avatar" 
                     class="w-52 h-52 rounded-full object-cover mx-auto transition-all duration-300 transform 
                            border-4 border-transparent 
                            group-hover:border-white group-hover:scale-105">
            </div>
            
            <h3 class="text-2xl font-bold mt-5 text-white transition-all duration-300 transform 
                       group-hover:scale-105 group-hover:text-blue-300">
                {{ $user->name }}
            </h3>
        </a>
    </div>

    {{-- БИБЛИОТЕКА --}}
    <div class="mt-auto self-start pt-8">
        <a href="/libraries/{{ $library->id }}" target="_blank" 
           class="group block transition-transform duration-200 active:scale-95" title="Библиотека">
            
            <div class="flex items-center gap-4">
                {{-- Лого са оквиром само на десктопу --}}
                <div class="p-1 rounded-lg transition-all duration-300 transform 
                            border-4 border-transparent 
                            group-hover:border-white group-hover:scale-110 flex-shrink-0">
                    <x-library-logo :library="$user->ownLibrary" :width="48" />
                </div>

                <div class="transition-all duration-300 transform group-hover:scale-105">
                    <span class="text-[10px] text-gray-500 block tracking-widest uppercase font-bold">БИБЛИОТЕКА</span>
                    <span class="text-lg font-extrabold text-white transition-colors duration-300 md:group-hover:text-blue-300">
                        {{ $library->name }}
                    </span>
                </div>
            </div>
        </a>
    </div>
</x-panel>