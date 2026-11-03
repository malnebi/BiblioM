<x-layout>
    <div class="space-y-10">

        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-green-900" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section aria-label="Претрага књига и описних ознака">
            <div class="grid grid-cols-4 gap-2 sm:gap-3">
                <div data-home-card="search-books" data-panel-color="blue"
                    aria-pressed="{{ $activeCard === 'search-books' ? 'true' : 'false' }}"
                    class="col-span-3 flex min-w-0 flex-col justify-center gap-1 rounded-xl border border-slate-800 bg-[#1e293b]/40 p-1.5 text-center transition-all hover:border-blue-500 sm:gap-2 sm:p-3 md:col-span-1 md:min-h-[76px]">
                    <h1 id="book-search-heading" class="text-[8px] font-bold uppercase text-slate-300 sm:text-xs">
                        Претрага књига
                    </h1>
                    <form method="GET" action="{{ route('home') }}" role="search" class="flex min-w-0 flex-col items-center gap-1 md:flex-row">
                        <label for="book-search" class="sr-only">Претражите књиге</label>
                        <input id="book-search" type="search" name="q"
                            value="{{ $bookSearch }}" placeholder="Наслов или аутор" aria-label="Наслов или аутор"
                            class="h-1 min-h-0 w-full min-w-0 rounded-lg border border-slate-600 px-2 py-0 text-[9px] leading-[10px] sm:h-7 md:flex-1 md:text-xs">
                        <button type="submit"
                            class="self-center rounded-lg border border-blue-400/50 px-2 py-0.5 text-[8px] font-bold text-blue-200/90 transition-colors hover:bg-blue-400/70 hover:text-slate-950 sm:py-1 sm:text-[10px] md:shrink-0 md:self-auto">
                            Претражи
                        </button>
                    </form>
                </div>

                <button type="button" data-home-panel-toggle="latest-books" data-panel-color="blue"
                    data-home-card="latest-books"
                    data-panel-reset-url="{{ route('home') }}" aria-controls="latest-books"
                    aria-pressed="{{ $activeCard === 'latest-books' ? 'true' : 'false' }}"
                    class="group col-span-1 flex min-w-0 min-h-[56px] flex-col items-center justify-center rounded-xl border border-slate-800 bg-[#1e293b]/40 p-1.5 text-center text-slate-400 transition-all duration-300 hover:border-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400 sm:min-h-[64px] sm:p-2 md:col-span-1 md:min-h-[76px] md:p-3">
                    <span class="mb-1 text-[8px] font-bold uppercase text-slate-500 transition-colors group-hover:text-white sm:text-[9px] md:text-xs">
                        Све књиге у апликацији
                    </span>
                    <span class="text-lg font-black text-blue-400 transition-colors group-hover:text-white sm:text-xl md:text-2xl">
                        {{ $allBooksCount }}
                    </span>
                </button>

                <div data-home-card="search-tags" data-panel-color="emerald"
                    aria-pressed="{{ $activeCard === 'search-tags' ? 'true' : 'false' }}"
                    class="col-span-3 flex min-w-0 flex-col justify-center gap-1 rounded-xl border border-slate-800 bg-[#1e293b]/40 p-1.5 text-center transition-all hover:border-emerald-500 sm:gap-2 sm:p-3 md:col-span-1 md:min-h-[76px]">
                    <h2 id="tag-search-heading" class="text-[8px] font-bold uppercase text-slate-300 sm:text-xs">
                        Претрага описних ознака
                    </h2>
                    <form method="GET" action="{{ route('home') }}" role="search" class="flex min-w-0 flex-col items-center gap-1 md:flex-row">
                        <label for="tag-search" class="sr-only">Претражите описне ознаке</label>
                        <input id="tag-search" type="search" name="tag_q"
                            value="{{ $tagSearch }}" placeholder="Описна ознака" aria-label="Описна ознака"
                            class="h-3.5 min-h-0 w-full min-w-0 rounded-lg border border-slate-600 px-2 py-0 text-[9px] leading-[10px] sm:h-7 md:flex-1 md:text-xs">
                        <button type="submit"
                            class="self-center rounded-lg border border-emerald-400/50 px-2 py-0.5 text-[8px] font-bold text-emerald-200/90 transition-colors hover:bg-emerald-400/70 hover:text-slate-950 sm:py-1 sm:text-[10px] md:shrink-0 md:self-auto">
                            Претражи
                        </button>
                    </form>
                </div>

                <button type="button" data-home-panel-toggle="all-tags" data-panel-color="emerald"
                    data-home-card="all-tags"
                    data-panel-reset-url="{{ route('home') }}" aria-controls="all-tags"
                    aria-pressed="{{ $activeCard === 'all-tags' ? 'true' : 'false' }}" aria-labelledby="tag-list-heading"
                    class="group col-span-1 flex min-w-0 min-h-[56px] flex-col items-center justify-center rounded-xl border border-slate-800 bg-[#1e293b]/40 p-1.5 text-center text-slate-400 transition-all duration-300 hover:border-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400 sm:min-h-[64px] sm:p-2 md:col-span-1 md:min-h-[76px] md:p-3">
                    <span id="tag-list-heading" class="mb-1 text-[8px] font-bold uppercase text-slate-500 transition-colors group-hover:text-white sm:text-[9px] md:text-xs">
                        Описне ознаке
                    </span>
                    <span class="text-lg font-black text-emerald-400 transition-colors group-hover:text-white sm:text-xl md:text-2xl">
                        {{ $allTagsCount }}
                    </span>
                </button>
            </div>
        </section>

        <section id="latest-books" data-home-panel data-panel-color="blue" @if ($activePanel !== 'latest-books') hidden @endif>
            <h2 class="mb-6 flex items-center gap-2 whitespace-nowrap text-[9px] font-bold uppercase tracking-[0.1em] text-blue-400 sm:mb-8 sm:text-xs sm:tracking-[0.2em]">
                <span class="h-6 w-1.5 rounded-full bg-blue-500"></span>
                @if ($bookSearch !== '')
                    Резултати претраге књига ({{ $books->total() }})
                @else
                    Најновије књиге у апликацији
                @endif
            </h2>
            @if ($bookSearchTooShort)
                <p class="mb-4 text-sm text-gray-300">Унесите најмање 3 карактера за претрагу књига.</p>
            @elseif ($bookSearch !== '' && $books->isEmpty())
                <p class="mb-4 text-sm text-gray-300">Нема књига за унесени појам.</p>
            @endif
            <div
                data-scroll-list
                data-next-page="{{ $books->nextPageUrl() }}"
                class="mt-8"
            >
                <div data-scroll-cards data-mobile-book-grid class="grid grid-cols-2 gap-x-2 gap-y-4 sm:gap-x-6 sm:gap-y-12 md:grid-cols-2 lg:grid-cols-4">
                    @include('books.partials.cards', ['books' => $books])
                </div>

                @if ($books->hasMorePages())
                    <div data-scroll-sentinel class="h-1" aria-hidden="true"></div>
                    <p data-scroll-status class="py-3 text-center text-sm text-gray-300" aria-live="polite"></p>
                @endif
            </div>
        </section>

        <section id="all-tags" data-home-panel data-panel-color="emerald" aria-labelledby="all-tags-heading" @if ($activePanel !== 'all-tags') hidden @endif>
            <h2 id="all-tags-heading" class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-400 sm:mb-8 sm:text-xs">
                <span class="h-6 w-1.5 rounded-full bg-emerald-500"></span>
                @if ($tagSearch !== '')
                    Резултати претраге описних ознака ({{ $tags->count() }})
                @else
                    Описне ознаке ({{ $tags->count() }})
                @endif
            </h2>
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($tags as $tag)
                    <x-tag :$tag />
                @endforeach
            </div>
            @if ($tags->isEmpty())
                <p class="mt-6 text-center text-sm text-gray-300">
                    {{ $tagSearch !== '' ? 'Нема описних ознака за ову претрагу.' : 'Тренутно нема описних ознака.' }}
                </p>
            @endif
        </section>
    </div>
</x-layout>
