@props(['count', 'type', 'label', 'message', 'href', 'linkLabel'])

@if ($count > 0)
    <div x-data="{ open: false }" @click.away="open = false" class="absolute -right-1 -top-1 z-20">
        <button type="button" @click.stop="open = !open" :aria-expanded="open"
            aria-label="{{ $label }}: {{ $count }}. Прикажи детаље"
            class="flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white ring-2 ring-green-mint hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-white"
            data-notification-count="{{ $type }}">
            {{ $count }}
        </button>
        <div x-show="open" x-cloak
            class="absolute left-0 right-auto top-full z-30 mt-2 w-64 max-w-[calc(100vw-2rem)] rounded-lg bg-white p-4 text-left text-gray-800 shadow-xl ring-1 ring-black/10 sm:left-auto sm:right-0">
            <p class="text-sm font-bold">{{ $label }}</p>
            <p class="mt-1 text-xs leading-relaxed text-gray-600">{{ $message }}</p>
            <a href="{{ $href }}"
                class="mt-3 inline-flex text-xs font-bold text-blue-700 hover:text-blue-900">
                {{ $linkLabel }}
            </a>
        </div>
    </div>
@endif
