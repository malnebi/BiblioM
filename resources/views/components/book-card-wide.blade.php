@props(['book'])

<x-panel class="group flex gap-x-6">

    {{-- ЛИЈЕВО: ЛОГО + ИНВЕНТАР + СТАТУС --}}
    <div class="flex flex-col items-center shrink-0">

        <x-library-logo :library="$book->library ?? 'No library'" />

       

        @if ($book->loan == 1)
            <div class="text-red-500 text-xs font-bold mt-1">На читању</div>
        @elseif ($book->loans->where('description', 'rezervisano')->where('user_id', Auth::id())->first())
            <div class="text-orange-500 text-xs font-bold mt-1">Резервисана</div>
        @else
            <div class="text-green-500 text-xs font-bold mt-1">На полици</div>
        @endif
    </div>

    {{-- СРЕДИНА --}}
    <div class="flex-1 flex flex-col">

        {{-- АУТОР --}}
        <p class="text-sm text-gray-200">
            {{ $book->author_lname }}, {{ $book->author_fname }}
        </p>

        {{-- НАСЛОВ --}}
        <h3
            class="font-bold text-md mt-2
                   group-hover:text-blue-800
                   active:text-blue-800
                   focus-visible:text-blue-800"
        >
            <a href="/books/{{ $book->id }}/book" target="_blank">
                {{ $book->title }}
            </a>
        </h3>

        {{-- БИБЛИОГРАФИЈА + ⋯ --}}
        <details class="mt-1">

            {{-- ЈЕДАН РЕД (СКРАЋЕН) --}}
            <summary
                class="flex items-center gap-2 cursor-pointer
                       text-sm text-gray-200 list-none"
            >
                <span class="truncate">
                    {{ $book->publisher_place }}:
                    {{ $book->publisher_name }},
                    {{ $book->year }}
                </span>

                <span
                    class="text-lg text-gray-300
                           hover:text-blue-700 active:text-blue-700"
                >
                    ⋯
                </span>
            </summary>

            {{-- ПРОШИРЕНИ ОПИС – ИСПОД --}}
            <div class="mt-2 text-sm text-gray-400 space-y-1">
             

                <div>
                    <strong>Библиотека:</strong>
                    {{ $book->library->name }}
                </div>

                <div>
                    <strong>Инвентарни број:</strong>
                    #{{ $book->id }}
                </div>
            </div>

        </details>

        {{-- 📱 МОБИЛНИ ТАГОВИ --}}
        <div class="flex flex-wrap gap-2 mt-3 sm:hidden">
            @foreach ($book->tags as $tag)
                <x-tag :$tag size="small" />
            @endforeach
        </div>

    </div>

    {{-- 🖥️ ДЕСКТОП ТАГОВИ --}}
    <div class="hidden sm:flex flex-wrap gap-2 items-start">
        @foreach ($book->tags as $tag)
            <x-tag :$tag />
        @endforeach
    </div>

</x-panel>
