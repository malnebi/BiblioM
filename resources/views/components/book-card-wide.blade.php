{{-- resources/views/components/book-card-wide.blade.php --}}
@props(['book'])

<x-panel class="group p-3 h-full flex flex-col relative overflow-hidden">
    @if (isset($book) && $book)
        <div class="flex gap-x-3 items-start">
            {{-- ЛИЈЕВО: СЛИКА --}}
            <div class="shrink-0">
                <a href="/books/{{ $book->id }}/book" target="_blank">
                    <img src="{{ asset('storage/' . $book->book_cover) }}" alt="{{ $book->title }}"
                        class="w-20 h-28 object-cover rounded shadow-sm group-hover:scale-105 transition-transform duration-300">
                </a>
            </div>

            {{-- СРЕДИНА: ИНФОРМАЦИЈЕ --}}
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start gap-2">
                    <div class="min-w-0">
                        <p class="text-[12px] text-gray-400 uppercase tracking-tight leading-none mb-1">
                            {{ $book->author_lname }}, {{ $book->author_fname }}
                        </p>
                        <h3
                            class="font-bold text-md leading-tight group-hover:text-blue-400 transition-colors truncate">
                            <a href="/books/{{ $book->id }}/book" target="_blank">
                                {{ $book->title }}
                            </a>
                        </h3>
                    </div>

                    {{-- СМАЊЕНИ ЛОГО БИБЛИОТЕКЕ --}}
                    <div class="shrink-0 opacity-40 group-hover:opacity-100 transition-opacity">
                        <x-library-logo :library="$book->library" :width="30" class="w-2 h-2 object-contain" />
                        </p>
                    </div>

                </div>

                {{-- БИБЛИОГРАФИЈА --}}
                <details class="mt-1">
                    <summary
                        class="flex items-center gap-1 cursor-pointer text-[12px] text-gray-500 list-none hover:text-blue-300">
                        <span class="truncate italic">{{ $book->publisher_place }}: {{ $book->publisher_name }},
                            {{ $book->year }}</span>
                        <span class="text-xs">⋯</span>
                    </summary>
                    <div class="mt-1 text-[9px] text-gray-400 bg-black/20 p-1.5 rounded leading-tight">
                        <strong>Инвентарни број:</strong> #{{ $book->lib_book_id }}
                        </br> <strong>Број у апликацији:</strong> #{{ $book->id }}
                        </br> <strong>БИБЛИОТЕКА:</strong> {{ $book->library->name }}
                    </div>
                </details>
            </div>
        </div>

        {{-- ДОЊИ РЕД: СТАТУС (ЛЕВО) + ТАГОВИ (ДЕСНО) --}}
        <div class="mt-auto pt-2 flex items-end justify-between border-t border-slate-700/30">
            {{-- СТАТУС --}}
            <div class="shrink-0">
                @if ($book->loan == 1)
                    <span class="text-red-500 text-[9px] font-black uppercase tracking-tighter">На читању</span>
                @elseif ($book->loans->where('description', 'rezervisano')->where('user_id', Auth::id())->first())
                    <span class="text-orange-500 text-[9px] font-black uppercase tracking-tighter">Резервисана</span>
                @else
                    <span class="text-green-500 text-[9px] font-black uppercase tracking-tighter">На полици</span>
                @endif
            </div>

            {{-- ТАГОВИ - ПОРЕЂАНИ УДЕСНО У ИСТОЈ ВИСИНИ --}}
            <div class="flex flex-wrap justify-end gap-1 ml-4">
                @foreach ($book->tags->take(3) as $tag)
                    <x-tag :$tag size="small"
                        class="text-[8px] px-1 py-0 h-4 flex items-center bg-slate-700/40 border-slate-600" />
                @endforeach
                @if ($book->tags->count() > 2)
                    <span class="text-[8px] text-gray-500">+{{ $book->tags->count() - 2 }}</span>
                @endif
            </div>
        </div>
    @else
        <div class="p-2 text-red-500 text-[10px]">Подаци нису доступни.</div>
    @endif
</x-panel>
