@props(['user', 'library'])

<x-panel class="flex flex-col !p-2 sm:!p-3 min-h-[150px] sm:min-h-[180px] relative">
    
    {{-- КОРИСНИК --}}
    <div class="flex flex-col items-center justify-center flex-grow text-center mt-1 sm:mt-2">
        {{-- 'active:scale-95' даје ефекат "клилика" на телефону када се додирне --}}
        <a href="/users/{{ $user->id }}" target="_blank" 
           class="group block transition-transform duration-200 active:scale-95" title="Профил">
            
            <div class="relative inline-block">
                <img src="{{ $user->user_photo ? asset('storage/' . $user->user_photo) : Vite::asset('resources/images/user-placeholder.svg') }}" alt="{{ $user->name }}" 
                     class="w-[80px] h-[80px] sm:w-[96px] sm:h-[96px] rounded-full object-cover mx-auto transition-all duration-300 transform
                            border-4 border-transparent 
                            group-hover:border-white group-hover:scale-105">
            </div>
            
            <h3 class="text-sm sm:text-base font-bold mt-1 sm:mt-2 text-white transition-all duration-300 transform truncate max-w-full
                       group-hover:scale-105 group-hover:text-blue-300">
                {{ trim($user->name . ' ' . ($user->last_name ?? '')) }}
            </h3>
        </a>
    </div>

    {{-- БИБЛИОТЕКА --}}
    <div class="mt-auto self-start pt-2 sm:pt-4 w-full">
        <a href="/libraries/{{ $library->id }}" target="_blank" 
           class="group block transition-transform duration-200 active:scale-95" title="Библиотека">
            
            <div class="flex items-center justify-start gap-1">
                {{-- Лого са оквиром само на десктопу --}}
                <div class="p-0 rounded-lg transition-all duration-300 transform
                            border-2 border-transparent
                            group-hover:border-white group-hover:scale-110 flex-shrink-0">
                    <x-library-logo :library="$library" :width="24" />
                </div>

                <div class="min-w-0 flex-1 transition-all duration-300 transform group-hover:scale-105">
                    <span class="text-[7px] xs:text-[6px] text-gray-500 block tracking-[0.1em] sm:tracking-widest uppercase font-bold leading-tight">БИБЛИОТЕКА</span>
                    <span class="text-xs sm:text-sm font-extrabold text-white transition-colors duration-300 md:group-hover:text-blue-300 line-clamp-2">
                        {{ $library->name }}
                    </span>
                </div>
            </div>
        </a>
    </div>
</x-panel>