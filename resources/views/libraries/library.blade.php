<x-layout>
    <div class="space-y-12 max-w-7xl mx-auto py-8 px-4">
        <section data-library-header="{{ $isOwnLibrary ? 'own' : 'public' }}" class="relative overflow-hidden rounded-3xl border border-slate-700 bg-[#1e293b] p-4 shadow-2xl sm:p-8">
            <div class="relative z-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between lg:gap-6">
                <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                    <div class="shrink-0 rounded-xl border border-slate-700 bg-slate-900/50 p-2 shadow-inner sm:rounded-2xl sm:p-4">
                        <x-library-logo :library="$library" width="64" class="h-10 w-10 object-contain sm:h-16 sm:w-16" />
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-sm font-black uppercase text-white sm:text-lg md:text-2xl">
                            <span class="block text-[9px] tracking-[0.16em] text-blue-300 sm:text-xs">
                                {{ $isOwnLibrary ? 'Моја библиотека' : 'Библиотека' }}
                            </span>
                            <span class="block break-words bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">{{ $library->name }}</span>
                        </h1>
                    </div>
                </div>

            </div>
        </section>

        <section aria-label="Претрага књига и приказ библиотеке">
            <div class="grid grid-cols-4 gap-2 sm:gap-3 md:grid-cols-2">
                <div data-home-card="search-books" data-panel-color="blue"
                    aria-pressed="{{ $activePanel === 'search-books' ? 'true' : 'false' }}"
                    class="col-span-3 flex min-w-0 flex-col justify-center gap-1 rounded-xl border border-slate-800 bg-[#1e293b]/40 p-1.5 text-center transition-all hover:border-blue-500 sm:gap-2 sm:p-3 md:col-span-1 md:min-h-[76px]">
                    <h2 class="text-[8px] font-bold uppercase text-slate-300 sm:text-xs">Претрага књига</h2>
                    <form method="GET" action="{{ url('/libraries/' . $library->id) }}" role="search"
                        class="flex min-w-0 flex-col items-center gap-1 md:flex-row">
                        <label for="book-search" class="sr-only">Претражите књиге библиотеке</label>
                        <input id="book-search" type="search" name="q" value="{{ $bookSearch }}"
                            placeholder="Наслов или аутор" aria-label="Наслов или аутор"
                            class="h-3.5 min-h-0 w-full min-w-0 rounded-lg border border-slate-600 px-2 py-0 text-[9px] leading-[10px] sm:h-7 md:flex-1 md:text-xs">
                        <input type="hidden" name="panel" value="search-books">
                        <button type="submit"
                            class="self-center rounded-lg border border-blue-400/50 px-2 py-0.5 text-[8px] font-bold text-blue-200/90 transition-colors hover:bg-blue-400/70 hover:text-slate-950 sm:py-1 sm:text-[10px] md:shrink-0 md:self-auto">
                            Претражи
                        </button>
                    </form>
                </div>

                <button type="button" data-home-panel-toggle="all-books" data-panel-color="blue"
                    data-home-card="all-books" data-panel-reset-on-click data-panel-reset-url="{{ url('/libraries/' . $library->id) }}"
                    aria-controls="all-books" aria-pressed="{{ $activePanel === 'all-books' ? 'true' : 'false' }}"
                    class="group col-span-1 flex min-w-0 min-h-[56px] flex-col items-center justify-center rounded-xl border border-slate-800 bg-[#1e293b]/40 p-1.5 text-center text-slate-400 transition-all duration-300 hover:border-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400 sm:min-h-[64px] sm:p-2 md:min-h-[76px] md:p-3">
                    <span class="mb-0.5 text-[7px] font-bold uppercase leading-tight text-slate-500 transition-colors group-hover:text-white sm:mb-1 sm:text-[9px] md:text-xs">
                        <span class="block">Све</span>
                        <span class="block">књиге</span>
                        <span class="block">библиотеке</span>
                    </span>
                    <span class="text-sm font-black text-blue-400 transition-colors group-hover:text-white sm:text-lg md:text-xl">
                        {{ $allBooksCount }}
                    </span>
                </button>
            </div>
        </section>

        <section id="search-books" data-home-panel data-panel-color="blue" aria-labelledby="library-search-results-heading"
            @if ($activePanel !== 'search-books') hidden @endif>
            <h2 id="library-search-results-heading"
                class="mb-6 flex items-center gap-2 text-[9px] font-bold uppercase tracking-[0.1em] text-blue-400 sm:mb-8 sm:text-xs sm:tracking-[0.2em]">
                <span class="h-6 w-1.5 rounded-full bg-blue-500"></span>
                Резултати претраге књига ({{ $searchBooks->count() }})
            </h2>
            @if ($bookSearchTooShort)
                <p class="mb-4 text-sm text-gray-300">Унесите најмање 3 карактера за претрагу књига.</p>
            @elseif ($bookSearch === '')
                <p class="mb-4 text-sm text-gray-300">Унесите појам за претрагу књига.</p>
            @elseif ($searchBooks->isEmpty())
                <p class="mb-4 text-sm text-gray-300">Нема књига за унесени појам.</p>
            @else
                <div data-mobile-book-grid class="grid grid-cols-2 gap-x-2 gap-y-4 sm:gap-x-6 sm:gap-y-12 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($searchBooks as $book)
                        <x-book-card-wide :$book />
                    @endforeach
                </div>
            @endif
        </section>

        <section id="all-books" data-home-panel data-panel-color="blue" aria-labelledby="library-all-books-heading"
            @if ($activePanel !== 'all-books') hidden @endif>
            <h2 id="library-all-books-heading"
                class="mb-6 flex items-center gap-2 text-[9px] font-bold uppercase tracking-[0.1em] text-blue-400 sm:mb-8 sm:text-xs sm:tracking-[0.2em]">
                <span class="h-6 w-1.5 rounded-full bg-blue-500"></span>
                Све књиге библиотеке ({{ $allBooksCount }})
            </h2>
            @if ($books->isNotEmpty())
                <div data-mobile-book-grid class="grid grid-cols-2 gap-x-2 gap-y-4 sm:gap-x-6 sm:gap-y-12 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($books->take(6) as $book)
                        <x-book-card-wide :$book />
                    @endforeach
                </div>
                @if ($books->count() > 6)
                    <details class="mt-4">
                        <summary class="list-none cursor-pointer font-bold text-orange-500 hover:underline">Види више...</summary>
                        <div data-mobile-book-grid class="mt-4 grid grid-cols-2 gap-x-2 gap-y-4 sm:gap-x-6 sm:gap-y-12 md:grid-cols-2 lg:grid-cols-4">
                            @foreach ($books->skip(6) as $book)
                                <x-book-card-wide :$book />
                            @endforeach
                        </div>
                    </details>
                @endif
            @else
                <p class="text-sm text-slate-400">Библиотека тренутно нема књига.</p>
            @endif
        </section>

        <x-forms.divider />
    </div>
</x-layout>
