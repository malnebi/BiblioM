<x-layout>
    <div class="mx-auto max-w-7xl space-y-8 px-3 py-5 text-slate-200 sm:px-4 sm:py-8">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-4">
            <section class="flex min-w-0 items-center gap-2 rounded-2xl border border-slate-700 bg-[#1e293b] px-3 py-2 shadow-xl sm:gap-3 sm:px-4 sm:py-2.5 @if ($activeLoansCount <= 2 && $books->isNotEmpty()) lg:col-span-1 @else lg:col-span-4 @endif">
                <img src="{{ $user->user_photo ? asset('storage/' . $user->user_photo) : Vite::asset('resources/images/user-placeholder.svg') }}"
                    alt="{{ $user->name }}" class="h-14 w-14 shrink-0 rounded-xl border border-slate-600 object-cover sm:h-16 sm:w-16">
                <div class="min-w-0">
                    <h1 class="truncate text-base font-bold text-white sm:text-lg">{{ $user->name }} {{ $user->last_name }}</h1>
                    <p class="mt-1 truncate text-[10px] font-semibold uppercase tracking-wide text-blue-300 sm:text-xs">
                        @if ($user->role_type === 'Друго')
                            {{ $user->role_details ?: 'Није наведена' }}
                        @else
                            {{ $user->role_type ?? 'Није наведена' }}@if ($user->role_details) · {{ $user->role_details }}@endif
                        @endif
                    </p>
                </div>
            </section>

            @if ($activeLoansCount <= 2 && $books->isNotEmpty())
                <section class="min-w-0 rounded-2xl border border-slate-700 bg-[#1e293b] p-3 shadow-xl sm:p-4 lg:col-span-3">
                    <x-forms.form method="POST" action="/loans/store" class="!mx-0 !max-w-none !space-y-1.5">
                        <input type="hidden" name="user_id" value="{{ $user->id }}" />
                        <label for="book_search" class="block text-xs font-bold text-blue-300 sm:text-sm">
                            Додијелите књигу овом кориснику
                        </label>
                        <div class="relative" data-book-autocomplete>
                            <input type="search" id="book_search" autocomplete="off" required
                                role="combobox" aria-autocomplete="list" aria-controls="book_suggestions"
                                aria-expanded="false" placeholder="Претражите књигу по наслову или аутору"
                                class="h-9 w-full rounded-lg border border-slate-600 px-3 py-1.5 text-xs">
                            <input type="hidden" name="book_id" id="book_id">

                            <div id="book_suggestions" role="listbox"
                                class="absolute z-20 mt-1 max-h-64 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg"
                                hidden>
                                @foreach ($books as $book)
                                    <button type="button" role="option" aria-selected="false"
                                        id="book_option_{{ $book->id }}" data-book-option="{{ $book->id }}"
                                        data-book-inventory="{{ $book->lib_book_id }}"
                                        class="block w-full border-b border-gray-100 bg-white px-3 py-2 text-left text-sm text-slate-900 opacity-100 hover:bg-gray-100">
                                        {{ $book->lib_book_id }} {{ $book->title }} / {{ $book->author_fname }} {{ $book->author_lname }}. - {{ $book->publisher_place }}: {{ $book->publisher_name }}, {{ $book->year }}
                                    </button>
                                @endforeach
                            </div>
                            <div id="book_no_results" role="status"
                                class="absolute z-20 mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600 shadow-lg"
                                hidden>Нема резултата</div>
                        </div>
                        <x-forms.button class="!transform-none !px-5 !py-1.5 !text-xs">Потврди додјелу</x-forms.button>
                    </x-forms.form>
                </section>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-2 sm:gap-3 md:grid-cols-4">
            <button type="button" data-section="sec-preuzimanje" data-count="{{ $inProgressLoans->count() }}" aria-pressed="false"
                class="loan-stat-btn group min-h-[76px] rounded-xl border border-slate-800 bg-[#1e293b]/40 p-2.5 text-center text-slate-400 transition-all hover:border-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400 sm:p-3">
                <span class="loan-stat-label mb-1 block text-[9px] font-bold uppercase text-slate-500 transition-colors sm:text-[10px]">За преузимање</span>
                <span class="loan-stat-number block text-2xl font-black text-blue-400 transition-colors group-hover:text-white group-aria-pressed:text-white sm:text-3xl">{{ $inProgressLoans->count() }}</span>
            </button>
            <button type="button" data-section="sec-rezervacije" data-count="{{ $reservedLoans->count() }}" aria-pressed="false"
                class="loan-stat-btn group min-h-[76px] rounded-xl border border-slate-800 bg-[#1e293b]/40 p-2.5 text-center text-slate-400 transition-all hover:border-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-400 sm:p-3">
                <span class="loan-stat-label mb-1 block text-[9px] font-bold uppercase text-slate-500 transition-colors sm:text-[10px]">Резервације</span>
                <span class="loan-stat-number block text-2xl font-black text-amber-400 transition-colors group-hover:text-white group-aria-pressed:text-white sm:text-3xl">{{ $reservedLoans->count() }}</span>
            </button>
            <button type="button" data-section="sec-citanje" data-count="{{ $activeLoans->count() }}" aria-pressed="false"
                class="loan-stat-btn group min-h-[76px] rounded-xl border border-slate-800 bg-[#1e293b]/40 p-2.5 text-center text-slate-400 transition-all hover:border-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400 sm:p-3">
                <span class="loan-stat-label mb-1 block text-[9px] font-bold uppercase text-slate-500 transition-colors sm:text-[10px]">На читању</span>
                <span class="loan-stat-number block text-2xl font-black text-emerald-400 transition-colors group-hover:text-white group-aria-pressed:text-white sm:text-3xl">{{ $activeLoansCount }}</span>
            </button>
            <button type="button" data-section="sec-procitano" data-count="{{ $overLoansCount }}" aria-pressed="false"
                class="loan-stat-btn group min-h-[76px] rounded-xl border border-slate-800 bg-[#1e293b]/40 p-2.5 text-center text-slate-400 transition-all hover:border-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400 sm:p-3">
                <span class="loan-stat-label mb-1 block text-[9px] font-bold uppercase text-slate-500 transition-colors sm:text-[10px]">Прочитано</span>
                <span class="loan-stat-number block text-2xl font-black text-indigo-400 transition-colors group-hover:text-white group-aria-pressed:text-white sm:text-3xl">{{ $overLoansCount }}</span>
            </button>
        </div>

        <x-forms.divider />

        <div id="loan-sections">
            <section id="sec-preuzimanje" class="loan-section hidden animate-fade-in">
                <h2 class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-blue-400 sm:mb-8 sm:text-xs">
                    <span class="h-6 w-1.5 rounded-full bg-blue-500"></span> Књиге за преузимање
                </h2>
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4 sm:gap-x-6 sm:gap-y-12">
                    @forelse ($inProgressLoans as $loan)
                        <article class="flex flex-col">
                            <x-book-card-wide :book="$loan->book" />
                            <div class="mt-3 rounded-xl border border-slate-700 bg-[#1e293b] p-3 text-xs text-slate-300">
                                @if ($loan->active == 1)
                                    Чека се потврда корисника о преузимању књиге.
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-slate-400">Тренутно нема књига за преузимање.</p>
                    @endforelse
                </div>
            </section>

            <section id="sec-rezervacije" class="loan-section hidden animate-fade-in">
                <h2 class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-400 sm:mb-8 sm:text-xs">
                    <span class="h-6 w-1.5 rounded-full bg-amber-500"></span> Активне резервације
                </h2>
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4 sm:gap-x-6 sm:gap-y-12">
                    @forelse ($reservedLoans as $loan)
                        <article class="flex flex-col">
                            <x-book-card-wide :book="$loan->book" />
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-700 bg-[#1e293b] p-3">
                                <span class="text-xs text-slate-300">Резервисано {{ $loan->created_at->format('d.m.Y.') }}</span>
                                <form method="POST" action="/loans/loanLibraryConfirm/{{ $loan->id }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="inline-flex min-h-9 items-center justify-center rounded-lg bg-amber-600 px-3 py-2 text-[10px] font-black uppercase text-white transition-colors hover:bg-amber-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                                        Додијели књигу
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-slate-400">Тренутно нема активних резервација.</p>
                    @endforelse
                </div>
            </section>

            <section id="sec-citanje" class="loan-section hidden animate-fade-in">
                <h2 class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-400 sm:mb-8 sm:text-xs">
                    <span class="h-6 w-1.5 rounded-full bg-emerald-500"></span> Књиге код корисника
                </h2>
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4 sm:gap-x-6 sm:gap-y-12">
                    @forelse ($activeLoans as $loan)
                        <article class="flex flex-col">
                            <x-book-card-wide :book="$loan->book" />
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-700 bg-[#1e293b] p-3">
                                <span class="text-xs text-slate-300">
                                    Рок за враћање: {{ \Carbon\Carbon::parse($loan->return_deadline)->format('d.m.Y.') }}
                                </span>
                                <form method="POST" action="/loans/returnBookLibraryConfirm/{{ $loan->id }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="inline-flex min-h-9 items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-[10px] font-black uppercase text-white transition-colors hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
                                        Потврди враћање
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-slate-400">Тренутно нема књига код корисника.</p>
                    @endforelse
                </div>
            </section>

            <section id="sec-procitano" class="loan-section hidden animate-fade-in">
                <h2 class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-indigo-400 sm:mb-8 sm:text-xs">
                    <span class="h-6 w-1.5 rounded-full bg-indigo-500"></span> Историја прочитаних књига
                </h2>
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4 sm:gap-x-6 sm:gap-y-12">
                    @forelse ($overLoans as $loan)
                        <article class="flex flex-col">
                            <x-book-card-wide :book="$loan->book" />
                            <div class="mt-3 flex items-center justify-between rounded-xl border-b-2 border-indigo-400 bg-slate-100 p-3 text-[11px] font-black italic text-slate-900 shadow-inner">
                                <span>{{ $loan->created_at->format('d.m.y') }}</span>
                                <span>—</span>
                                <span>{{ $loan->updated_at->format('d.m.y') }}</span>
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-slate-400">Корисник још нема прочитаних књига из ваше библиотеке.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    <script>
        function showLoanSection(button) {
            const id = button.dataset.section;
            document.querySelectorAll('.loan-section').forEach((section) => section.classList.add('hidden'));
            document.querySelectorAll('.loan-stat-btn').forEach((statButton) => {
                statButton.classList.remove('bg-slate-800/80', 'border-blue-500', 'border-amber-500', 'border-emerald-500', 'border-indigo-500');
                statButton.classList.add('bg-[#1e293b]/40', 'border-slate-800');
                statButton.setAttribute('aria-pressed', 'false');
            });

            document.getElementById(id)?.classList.remove('hidden');
            button.classList.remove('bg-[#1e293b]/40', 'border-slate-800');
            button.classList.add('bg-slate-800/80');
            const borderColor = {
                'sec-preuzimanje': 'border-blue-500',
                'sec-rezervacije': 'border-amber-500',
                'sec-citanje': 'border-emerald-500',
                'sec-procitano': 'border-indigo-500',
            }[id];
            if (borderColor) button.classList.add(borderColor);
            button.setAttribute('aria-pressed', 'true');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const buttons = Array.from(document.querySelectorAll('.loan-stat-btn'));
            const initialButton = buttons.find((button) => Number(button.dataset.count) > 0) ?? buttons[0];

            buttons.forEach((button) => button.addEventListener('click', () => showLoanSection(button)));
            if (initialButton) {
                showLoanSection(initialButton);
            }
        });
    </script>

    <style>
        .loan-stat-btn:hover .loan-stat-label,
        .loan-stat-btn[aria-pressed="true"] .loan-stat-label {
            color: #fff;
        }

        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-layout>
