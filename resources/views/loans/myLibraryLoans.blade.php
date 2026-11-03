<x-layout>
    <div class="mx-auto max-w-7xl space-y-8 px-3 py-5 text-slate-200 sm:space-y-12 sm:px-4 sm:py-8">

        {{-- ===== 1. DASHBOARD СТАТИСТИКА ===== --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
            {{-- Biblioteka --}}
            <div class="flex min-w-0 items-center gap-3 rounded-2xl border border-slate-700 bg-[#1e293b] p-4 shadow-xl sm:gap-4 sm:p-5 lg:col-span-1">
                <div class="shrink-0 rounded-xl border border-slate-700 bg-slate-900/50 p-2.5 sm:p-3">
                    <x-library-logo :library="$library" width="40" class="h-10 w-10 object-contain sm:h-[45px] sm:w-[45px]" />
                </div>
                <div class="min-w-0">
                    <h1 class="truncate text-base font-bold text-white sm:text-lg">{{ $library->name }}</h1>
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-400">Циркулација библиотеке</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 md:grid-cols-4 sm:gap-3 lg:col-span-3">
                <button type="button" aria-pressed="false" onclick="toggleSection('sec-preuzimanje', this)" class="stat-btn min-h-[76px] rounded-xl border border-slate-800 bg-[#1e293b]/40 p-2.5 text-center text-slate-400 transition-all hover:border-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-400 sm:p-3">
                    <span class="stat-label mb-1 block text-[9px] font-bold uppercase text-slate-500 transition-colors sm:text-[10px]">Преузимање</span>
                    <span class="block text-2xl font-black text-blue-400 sm:text-3xl">{{ $groupedProgressLoans->count() }}</span>
                </button>
                <button type="button" aria-pressed="false" onclick="toggleSection('sec-rezervacije', this)" class="stat-btn min-h-[76px] rounded-xl border border-slate-800 bg-[#1e293b]/40 p-2.5 text-center text-slate-400 transition-all hover:border-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-400 sm:p-3">
                    <span class="stat-label mb-1 block text-[9px] font-bold uppercase text-slate-500 transition-colors sm:text-[10px]">Резервације</span>
                    <span class="block text-2xl font-black text-amber-500 sm:text-3xl">{{ $groupedReservedLoans->count() }}</span>
                </button>
                <button type="button" aria-pressed="false" onclick="toggleSection('sec-citanje', this)" class="stat-btn min-h-[76px] rounded-xl border border-slate-800 bg-[#1e293b]/40 p-2.5 text-center text-slate-400 transition-all hover:border-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-400 sm:p-3">
                    <span class="stat-label mb-1 block text-[9px] font-bold uppercase text-slate-500 transition-colors sm:text-[10px]">На читању</span>
                    <span class="block text-2xl font-black text-emerald-500 sm:text-3xl">{{ $groupedActiveLoans->count() }}</span>
                </button>
                <button type="button" aria-pressed="false" onclick="toggleSection('sec-procitano', this)" class="stat-btn min-h-[76px] rounded-xl border border-slate-800 bg-[#1e293b]/40 p-2.5 text-center text-slate-400 transition-all hover:border-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400 sm:p-3">
                    <span class="stat-label mb-1 block text-[9px] font-bold uppercase text-slate-500 transition-colors sm:text-[10px]">Враћено</span>
                    <span class="block text-2xl font-black text-indigo-400 sm:text-3xl">{{ $groupedOverLoans->count() }}</span>
                </button>
            </div>
        </div>

        

        <x-forms.divider />

        {{-- ===== 2. КОНТЕЈНЕРИ СА ДЕТАЉИМА ===== --}}
        <div id="sections-wrapper">

            {{-- --- СЕКЦИЈА: ВРАЋЕНО --- --}}
            <div id="sec-procitano" class="content-sec hidden animate-fade-in">
                <h2 class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-indigo-400 sm:mb-8 sm:text-xs">
                    <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div> Историја враћених књига
                </h2>
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4 sm:gap-x-6 sm:gap-y-12 lg:gap-y-16">
                    @foreach ($groupedOverLoans as $bookId => $loans)
                        <div class="flex flex-col relative group">
                            <x-book-card-wide :book="$loans->first()->book" />

                            <div class="mt-4 bg-white border border-slate-200 border-t-4 border-t-indigo-400 rounded-b-xl shadow-md z-20 transition-all duration-700 ease-in-out" id="panel-{{ $bookId }}">
                                {{-- Хедер --}}
                                <div class="grid grid-cols-3 text-[9px] font-black uppercase text-slate-500 p-3 border-b border-slate-100 bg-slate-50/50">
                                    <div>Од</div><div class="text-center">Члан</div><div class="text-right">До</div>
                                </div>

                                {{-- Листа периода (Приказ само једног реда) --}}
                                <div class="divide-y divide-slate-100 bg-white">
                                    @php $firstLoan = $loans->first(); @endphp
                                    <div class="grid grid-cols-3 text-[10px] text-slate-800 p-3 items-center">
                                        <div class="font-mono text-slate-400">{{ $firstLoan->created_at->format('d.m.y') }}</div>
                                        <div class="truncate text-center px-1 text-slate-700">
                                            <a href="/users/{{ $firstLoan->user->id }}" class="font-bold hover:text-blue-600 hover:underline">{{ $firstLoan->user->name }} {{ $firstLoan->user->last_name }}</a>
                                            <div class="truncate text-[9px] text-slate-500">{{ $firstLoan->user->role_type ?? 'Улога није наведена' }}@if ($firstLoan->user->role_details) · {{ $firstLoan->user->role_details }}@endif</div>
                                        </div>
                                        <div class="font-mono text-right text-slate-400">{{ $firstLoan->updated_at->format('d.m.y') }}</div>
                                    </div>
                                    
                                    {{-- Скривени остатак који се отвара преко картица испод --}}
                                    <div id="extra-{{ $bookId }}" class="max-h-0 overflow-hidden transition-all duration-700 ease-in-out divide-y divide-slate-100 bg-white">
                                        @foreach ($loans->skip(1) as $loan)
                                            <div class="grid grid-cols-3 text-[10px] text-slate-800 p-3 items-center hover:bg-slate-50 transition-colors">
                                                <div class="font-mono text-slate-400">{{ $loan->created_at->format('d.m.y') }}</div>
                                                <div class="truncate text-center px-1 text-slate-700">
                                                    <a href="/users/{{ $loan->user->id }}" class="font-bold hover:text-blue-600 hover:underline">{{ $loan->user->name }} {{ $loan->user->last_name }}</a>
                                                    <div class="truncate text-[9px] text-slate-500">{{ $loan->user->role_type ?? 'Улога није наведена' }}@if ($loan->user->role_details) · {{ $loan->user->role_details }}@endif</div>
                                                </div>
                                                <div class="font-mono text-right text-slate-400">{{ $loan->updated_at->format('d.m.y') }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Дугме --}}
                                <div class="p-1 bg-slate-50/50 rounded-b-xl border-t border-slate-100">
                                    @if($loans->count() > 1)
                                        <button type="button" onclick="softExpand('{{ $bookId }}', this)" class="min-h-9 w-full py-1.5 text-[10px] font-black uppercase tracking-widest text-indigo-500 transition-colors hover:text-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-500">
                                            види још ({{ $loans->count() - 1 }})
                                        </button>
                                    @else
                                        <div class="py-1.5 text-[9px] text-transparent select-none">-</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- --- СЕКЦИЈА: ПРЕУЗИМАЊЕ --- --}}
            <div id="sec-preuzimanje" class="content-sec {{ $hasPendingPickupConfirmations ? '' : 'hidden' }} animate-fade-in">
                <h2 class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-blue-400 sm:mb-8 sm:text-xs">
                    <div class="w-1.5 h-6 bg-blue-500 rounded-full"></div> Преузимање у току
                </h2>
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4 sm:gap-x-6 sm:gap-y-12 lg:gap-y-16">
                    @foreach ($groupedProgressLoans as $bookId => $loans)
                        <div class="flex flex-col">
                            <x-book-card-wide :book="$loans->first()->book" />
                            <div class="mt-4 bg-white border border-slate-200 border-t-4 border-t-blue-400 rounded-b-xl shadow-md p-3">
                                @foreach ($loans->take(1) as $loan)
                                    <div class="flex justify-between items-center bg-slate-50 p-2 rounded border border-slate-100">
                                        <div class="mr-2 min-w-0 truncate">
                                            <a href="/users/{{ $loan->user->id }}" class="block truncate text-[10px] font-bold text-slate-700 hover:text-blue-600 hover:underline">{{ $loan->user->name }} {{ $loan->user->last_name }}</a>
                                            <span class="block truncate text-[9px] text-slate-500">{{ $loan->user->role_type ?? 'Улога није наведена' }}@if ($loan->user->role_details) · {{ $loan->user->role_details }}@endif</span>
                                        </div>
                                        @if ($loan->active != 1)
                                            <form method="POST" action="/loans/loanLibraryConfirm/{{ $loan->id }}">
                                                @csrf @method('PUT')
                                                <button class="inline-flex min-h-9 items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-[10px] font-black uppercase text-white transition-colors hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Потврди</button>
                                            </form>
                                        @else
                                            <span class="text-[9px] font-black text-slate-400 uppercase italic">Чека се потпис</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- --- СЕКЦИЈА: РЕЗЕРВАЦИЈЕ --- --}}
            <div id="sec-rezervacije" class="content-sec hidden animate-fade-in">
                <h2 class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-500 sm:mb-8 sm:text-xs">
                    <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div> Активне резервације
                </h2>
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4 sm:gap-x-6 sm:gap-y-12 lg:gap-y-16">
                    @foreach ($groupedReservedLoans as $bookId => $loans)
                        <div class="flex flex-col">
                            <x-book-card-wide :book="$loans->first()->book" />
                            <div class="mt-4 bg-white border border-slate-200 border-t-4 border-t-amber-400 rounded-b-xl shadow-md p-3">
                                @foreach ($loans->take(1) as $loan)
                                    <div class="flex justify-between items-center">
                                        <div class="min-w-0 truncate">
                                            <a href="/users/{{ $loan->user->id }}" class="block truncate text-[10px] font-bold text-slate-700 hover:text-blue-600 hover:underline">{{ $loan->user->name }} {{ $loan->user->last_name }}</a>
                                            <span class="block truncate text-[9px] text-slate-500">{{ $loan->user->role_type ?? 'Улога није наведена' }}@if ($loan->user->role_details) · {{ $loan->user->role_details }}@endif</span>
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-400">{{ $loan->created_at->format('d.m.y') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- --- СЕКЦИЈА: НА ЧИТАЊУ --- --}}
            <div id="sec-citanje" class="content-sec hidden animate-fade-in">
                <h2 class="mb-6 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-500 sm:mb-8 sm:text-xs">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div> Књиге код чланова
                </h2>
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 md:grid-cols-2 lg:grid-cols-4 sm:gap-x-6 sm:gap-y-12 lg:gap-y-16">
                    @foreach ($groupedActiveLoans as $bookId => $loans)
                        <div class="flex flex-col">
                            <x-book-card-wide :book="$loans->first()->book" />
                            <div class="mt-4 bg-white border border-slate-200 border-t-4 border-t-emerald-400 rounded-b-xl shadow-md p-3">
                                @foreach ($loans->take(1) as $loan)
                                    <div class="flex justify-between items-center">
                                        <div class="mr-2 min-w-0 truncate">
                                            <a href="/users/{{ $loan->user->id }}" class="block truncate text-[10px] font-bold text-slate-700 hover:text-blue-600 hover:underline">{{ $loan->user->name }} {{ $loan->user->last_name }}</a>
                                            <span class="block truncate text-[9px] text-slate-500">{{ $loan->user->role_type ?? 'Улога није наведена' }}@if ($loan->user->role_details) · {{ $loan->user->role_details }}@endif</span>
                                        </div>
                                        <div class="flex shrink-0 flex-col items-end gap-2">
                                            <span class="rounded bg-emerald-100 px-2 py-1 text-[8px] font-black uppercase text-emerald-700">Преузимање потврђено</span>
                                            <form method="POST" action="/loans/returnBookLibraryConfirm/{{ $loan->id }}">
                                                @csrf @method('PUT')
                                                <button class="inline-flex min-h-9 items-center justify-center rounded-lg bg-emerald-600 px-3 py-2 text-[10px] font-black uppercase text-white transition-colors hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Врати</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        function softExpand(id, btn) {
            const extra = document.getElementById('extra-' + id);
            const panel = document.getElementById('panel-' + id);

            if (extra.style.maxHeight && extra.style.maxHeight !== '0px') {
                // ЗАТВАРАЊЕ
                extra.style.maxHeight = '0px';
                setTimeout(() => {
                    panel.style.position = 'relative';
                    panel.style.zIndex = '20';
                }, 700);
                btn.innerText = 'види још (' + (extra.children.length) + ')';
            } else {
                // ОТВАРАЊЕ (преко других картица)
                panel.style.position = 'absolute';
                panel.style.width = '100%';
                panel.style.zIndex = '50';
                extra.style.maxHeight = '400px'; 
                btn.innerText = 'затвори';
            }
        }

        function toggleSection(id, btn) {
            document.querySelectorAll('.content-sec').forEach(s => s.classList.add('hidden'));
            document.querySelectorAll('.stat-btn').forEach(b => {
                b.classList.remove('active-btn', 'border-blue-500', 'border-amber-500', 'border-emerald-500', 'border-indigo-500', 'bg-[#1e293b]', 'text-white');
                b.classList.add('bg-[#1e293b]/40', 'border-slate-800', 'text-slate-400');
                b.setAttribute('aria-pressed', 'false');
            });

            const target = document.getElementById(id);
            if (target) target.classList.remove('hidden');

            btn.classList.remove('bg-[#1e293b]/40', 'border-slate-800', 'text-slate-400');
            btn.classList.add('active-btn', 'bg-[#1e293b]', 'text-white');
            btn.setAttribute('aria-pressed', 'true');
            if (id === 'sec-preuzimanje') btn.classList.add('border-blue-500');
            if (id === 'sec-rezervacije') btn.classList.add('border-amber-500');
            if (id === 'sec-citanje') btn.classList.add('border-emerald-500');
            if (id === 'sec-procitano') btn.classList.add('border-indigo-500');
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleSection('sec-procitano', document.querySelectorAll('.stat-btn')[3]);
        });
    </script>

    <style>
        .stat-btn:hover .stat-label,
        .stat-btn[aria-pressed="true"] .stat-label {
            color: #fff;
        }

        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-layout>