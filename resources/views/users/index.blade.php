<x-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <section class="pt-6" aria-labelledby="community-heading">
                    <div class="group mb-4 flex flex-col gap-3 rounded-xl border border-transparent bg-[#1e293b]/40 p-3 transition-colors duration-200 hover:border-emerald-400/50 sm:flex-row sm:items-center sm:gap-4 sm:p-4">
                        <div class="shrink-0 text-center sm:min-w-36">
                            <h1 id="community-heading" class="text-base font-black uppercase tracking-wide text-white sm:text-lg">
                                Заједница
                            </h1>
                            <p class="mt-0.5 text-xs font-semibold text-emerald-200/80">
                                Чланови: {{ $communityLibraries->total() }}
                            </p>
                        </div>

                        <form method="GET" action="{{ route('community.index') }}" role="search"
                            class="flex min-w-0 flex-1 gap-2">
                            <label for="community-search" class="sr-only">Претражите чланове и библиотеке</label>
                            <input id="community-search" type="search" name="q" value="{{ $search }}"
                                placeholder="Име члана или назив библиотеке" maxlength="100"
                                class="h-10 min-w-0 flex-1 rounded-lg border border-slate-600 px-3 text-sm">
                            <button type="submit"
                                class="shrink-0 rounded-lg border border-emerald-400/50 px-4 py-2 text-sm font-bold text-emerald-200/90 transition-colors hover:bg-emerald-400/70 hover:text-slate-950">
                                Претражи
                            </button>
                            @if ($search !== '')
                                <a href="{{ route('community.index') }}"
                                    class="self-center text-xs font-semibold text-slate-300 hover:text-white">
                                    Очисти
                                </a>
                            @endif
                        </form>
                    </div>

                    <div
                        data-scroll-list
                        data-next-page="{{ $communityLibraries->nextPageUrl() }}"
                        class="mt-3"
                    >
                        <div data-scroll-cards class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @include('users.partials.community-cards', ['libraries' => $communityLibraries])
                        </div>

                        @if ($communityLibraries->hasMorePages())
                            <div data-scroll-sentinel class="h-1" aria-hidden="true"></div>
                            <p data-scroll-status class="py-3 text-center text-sm text-gray-300" aria-live="polite"></p>
                        @endif
                    </div>

                    @if ($communityLibraries->isEmpty())
                        <p class="mt-4 text-center text-sm text-gray-300">
                            {{ $search !== '' ? 'Нема резултата за ову претрагу.' : 'Тренутно нема чланова за приказ.' }}
                        </p>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-layout>
