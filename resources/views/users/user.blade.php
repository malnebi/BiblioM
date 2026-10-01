<x-layout>
    <div class="mx-auto max-w-7xl space-y-10 px-4 py-8">
        <section class="text-left">
            <section class="relative overflow-hidden rounded-3xl border border-slate-700 bg-[#1e293b] p-6 shadow-2xl md:p-8">
                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex min-w-0 items-center gap-4">
                        <div class="shrink-0 rounded-2xl border border-slate-700 bg-slate-900/50 p-2 shadow-inner">
                            <img src="{{ $user->user_photo ? asset('storage/' . $user->user_photo) : Vite::asset('resources/images/user-placeholder.svg') }}"
                                alt="{{ $user->name }}" class="h-20 w-20 rounded-xl object-cover">
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-lg font-black uppercase text-white md:text-2xl">{{ $user->name }} {{ $user->last_name }}</h1>
                            <p class="mt-2 text-base font-semibold text-blue-300">
                                {{ $user->role_type ?? 'Није наведена' }}@if ($user->role_details) · {{ $user->role_details }}@endif
                            </p>
                        </div>
                    </div>

                    {{-- ================== ФОРМУЛАР ЗА ДОДЈЕЛУ КЊИГЕ ЧЛАНУ ================== --}}
                @if ($activeLoansCount <= 2 && $books->isNotEmpty())
                    <div class="w-full lg:w-1/2">
                        <x-forms.form method="POST" action="/loans/store" class="relative !mx-0 !max-w-none !space-y-3">
                            <input type="hidden" name="user_id" value="{{ $user->id }}" />

                            <label for="book_search" class="mb-2 block text-lg font-bold text-blue-300">
                                Додијелите књигу овом кориснику
                            </label>
                            <div class="relative" data-book-autocomplete>
                                <input type="search" id="book_search" autocomplete="off" required
                                    role="combobox" aria-autocomplete="list" aria-controls="book_suggestions"
                                    aria-expanded="false" placeholder="Изаберите књигу по наслову, аутору или другим подацима"
                                    class="w-full rounded border border-gray-300 px-3 py-2">
                                <input type="hidden" name="book_id" id="book_id">

                                <div id="book_suggestions" role="listbox"
                                    class="absolute z-20 mt-1 max-h-64 w-full overflow-y-auto rounded border border-gray-300 bg-white shadow-lg"
                                    hidden>
                                    @foreach ($books as $book)
                                        <button type="button" role="option" aria-selected="false"
                                            id="book_option_{{ $book->id }}" data-book-option="{{ $book->id }}"
                                            class="block w-full border-b border-gray-100 bg-white px-3 py-2 text-left text-sm text-slate-900 opacity-100 hover:bg-gray-100">
                                            {{ $book->id }} {{ $book->title }} / {{ $book->author_fname }} {{ $book->author_lname }}. - {{ $book->publisher_place }}: {{ $book->publisher_name }}, {{ $book->year }}
                                        </button>
                                    @endforeach
                                </div>
                                <div id="book_no_results" role="status"
                                    class="absolute z-20 mt-1 w-full rounded border border-gray-300 bg-white px-3 py-2 text-sm text-gray-600 shadow-lg"
                                    hidden>Нема резултата</div>
                            </div>
                            <x-forms.button>Потврди</x-forms.button>
                        </x-forms.form>
                    </div>
                @endif
                </div>
            </section>

            {{-- ================== ПРЕУЗИМАЊЕ У ТОКУ  ================== --}}
            @if ($inProgressLoans->isNotEmpty())
                <x-collapsible-section id="sekcija-preuzimanje" title="ПРЕУЗИМАЊЕ КЊИГЕ" :count="count($inProgressLoans)">
                    <x-slot name="visible">
                        @foreach ($inProgressLoans->take(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                                @if ($loansBook->active == 1)
                                    ПРЕПОРУКА: Предај књигу кориснику кад потврди преузимање
                                @endif
                            </div>
                        @endforeach
                    </x-slot>
                    @foreach ($inProgressLoans->skip(2) as $loansBook)
                        <x-book-card-wide :book="$loansBook->book" />
                        <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                            @if ($loansBook->active == 1)
                                ПРЕПОРУКА: Са потписом корисника - потврдом о преузимању изврши и предају књиге
                            @endif
                        </div>
                    @endforeach
                </x-collapsible-section>
            @endif

            {{-- ================== РЕЗЕРВАЦИЈЕ ================== --}}
            @if ($reservedLoans->isNotEmpty())
                <x-collapsible-section id="sekcija-rezervacije" title="РЕЗЕРВАЦИЈЕ" :count="count($reservedLoans)">
                    <x-slot name="visible">
                        @foreach ($reservedLoans->take(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                                <form method="POST" action="/loans/loanLibraryConfirm/{{ $loansBook->id }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="col-md-8">
                                        <h3 class="hover:text-blue-600  font-bold ">
                                            <button type="submit" class="btn btn-primary">Позајми
                                                {{ $user->name }}</button>
                                        </h3>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </x-slot>
                    @foreach ($reservedLoans->skip(2) as $loansBook)
                        <x-book-card-wide :book="$loansBook->book" />
                        <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                            <form method="POST" action="/loans/loanLibraryConfirm/{{ $loansBook->id }}">
                                @csrf
                                {{ method_field('PUT') }}
                                <div class="col-md-8">
                                    <h3 class="hover:text-blue-600  font-bold ">
                                        <button type="submit" class="btn btn-primary">Позајми
                                            {{ $user->name }}</button>
                                    </h3>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </x-collapsible-section>
            @endif


            {{-- ================== KЊИГЕ НА ЧИТАЊУ ================== --}}
            @if ($activeLoans->isNotEmpty())
                <x-collapsible-section id="sekcija-aktivne" title="KЊИГЕ НА ЧИТАЊУ" :count="$activeLoansCount">
                    <x-slot name="visible">
                        @foreach ($activeLoans->take(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div
                                class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black mt-2">
                                Рок за враћање је
                                {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.') }}
                                <form method="POST" action="/loans/returnBookLibraryConfirm/{{ $loansBook->id }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="col-md-8">
                                        <h3 class="hover:text-green-600  font-bold ">
                                            <button type="submit" class="btn btn-primary">Врати књигу на
                                                полицу!</button>
                                        </h3>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </x-slot>
                    @foreach ($activeLoans->skip(2) as $loansBook)
                        <x-book-card-wide :book="$loansBook->book" />
                        <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                            Рок за враћање је
                            {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.') }}

                            <form method="POST" action="/loans/returnBookLibraryConfirm/{{ $loansBook->id }}">
                                @csrf
                                {{ method_field('PUT') }}
                                <div class="col-md-8">
                                    <h3 class="hover:text-green-600  font-bold ">
                                        <button type="submit" class="btn btn-primary">Врати књигу на
                                            полицу!</button>
                                    </h3>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </x-collapsible-section>
            @endif
            <x-forms.divider />

            {{-- ================== ПРОЧИТАНЕ КЊИГЕ ================== --}}
            @if ($overLoansCount > 0)
                <x-collapsible-section id="sekcija-procitane" title="ПРОЧИТАНЕ КЊИГЕ" :count="$overLoansCount">

                    {{-- ОВО ЈЕ КЉУЧНИ ДИО: Шаљемо податке у 'visible' слот --}}
                    <x-slot name="visible">
                        @foreach ($overLoans->take(2) as $loansBooks)
                            <div class="mb-4">
                                <x-book-card-wide :book="$loansBooks->book" />
                                <div
                                    class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black mt-2">
                                    {{ $loansBooks->created_at->format('d. m. Y. ') }} -
                                    {{ $loansBooks->updated_at->format('d. m. Y. ') }}
                                </div>
                            </div>
                        @endforeach
                    </x-slot>

                    {{-- Све испод овога аутоматски иде у главни $slot --}}
                    @foreach ($overLoans->skip(2) as $loansBooks)
                        <div class="mb-6">
                            <x-book-card-wide :book="$loansBooks->book" />
                            <div
                                class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black mt-2">
                                {{ $loansBooks->created_at->format('d. m. Y. ') }} -
                                {{ $loansBooks->updated_at->format('d. m. Y. ') }}
                            </div>
                        </div>
                    @endforeach
                </x-collapsible-section>
            @else
                <x-section-heading> {{ $user->name }} НЕМА ПРОЧИТАНИХ КЊИГA ИЗ ВАШЕ БИБЛИОТЕКЕ </x-section-heading>
            @endif
            <x-forms.divider />
        </section>
    </div>
</x-layout>
