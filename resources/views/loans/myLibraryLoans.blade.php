<x-layout>
    <div class="space-y-12 max-w-7xl mx-auto py-8 px-4">

        {{-- ===== 1. DASHBOARD СТАТИСТИКА ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            {{-- Biblioteka --}}
            <div class="lg:col-span-1 bg-[#1e293b] border border-slate-700 p-5 rounded-2xl flex items-center gap-4 shadow-xl">
                <div class="p-3 bg-slate-900/50 rounded-xl border border-slate-700">
                    <x-library-logo :library="$library" width="45" />
                </div>
                <div class="min-w-0">
                    <h1 class="font-bold text-white truncate text-base">{{ $library->name }}</h1>
                    <p class="text-blue-400 text-[10px] uppercase font-black tracking-widest">Админ Панел</p>
                </div>
            </div>

            <div class="lg:col-span-3 grid grid-cols-2 md:grid-cols-4 gap-3">
                <button onclick="toggleSection('sec-preuzimanje', this)" class="stat-btn bg-[#1e293b]/40 border border-slate-800 p-3 rounded-xl transition-all text-center text-slate-400">
                    <span class="block text-slate-500 text-[9px] font-bold uppercase mb-1">Преузимање</span>
                    <span class="text-2xl font-black text-blue-400 block">{{ $groupedProgressLoans->count() }}</span>
                </button>
                <button onclick="toggleSection('sec-rezervacije', this)" class="stat-btn bg-[#1e293b]/40 border border-slate-800 p-3 rounded-xl transition-all text-center text-slate-400">
                    <span class="block text-slate-500 text-[9px] font-bold uppercase mb-1">Резервације</span>
                    <span class="text-2xl font-black text-amber-500 block">{{ $groupedReservedLoans->count() }}</span>
                </button>
                <button onclick="toggleSection('sec-citanje', this)" class="stat-btn bg-[#1e293b]/40 border border-slate-800 p-3 rounded-xl transition-all text-center text-slate-400">
                    <span class="block text-slate-500 text-[9px] font-bold uppercase mb-1">На читању</span>
                    <span class="text-2xl font-black text-emerald-500 block">{{ $groupedActiveLoans->count() }}</span>
                </button>
                <button onclick="toggleSection('sec-procitano', this)" class="stat-btn bg-[#1e293b]/40 border border-slate-800 p-3 rounded-xl transition-all text-center text-slate-400">
                    <span class="block text-slate-500 text-[9px] font-bold uppercase mb-1">Враћено</span>
                    <span class="text-2xl font-black text-indigo-400 block">{{ $groupedOverLoans->count() }}</span>
                </button>
            </div>
        </div>

        

        <x-forms.divider />

        {{-- ===== 2. КОНТЕЈНЕРИ СА ДЕТАЉИМА ===== --}}
        <div id="sections-wrapper">

            {{-- --- СЕКЦИЈА: ВРАЋЕНО --- --}}
            <div id="sec-procitano" class="content-sec hidden animate-fade-in">
                <h2 class="text-indigo-400 text-[11px] font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div> Историја враћених књига
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-16">
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
                                        <button onclick="softExpand('{{ $bookId }}', this)" class="w-full py-1.5 text-[9px] font-black text-indigo-500 hover:text-indigo-700 transition-colors uppercase tracking-widest">
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
            <div id="sec-preuzimanje" class="content-sec hidden animate-fade-in">
                <h2 class="text-blue-400 text-[11px] font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-blue-500 rounded-full"></div> Преузимање у току
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-16">
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
                                                <button class="text-[9px] font-black text-blue-600 hover:text-blue-800 uppercase">Потврди</button>
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
                <h2 class="text-amber-500 text-[11px] font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div> Активне резервације
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-16">
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
                <h2 class="text-emerald-500 text-[11px] font-bold uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div> Књиге код чланова
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-16">
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
                                        <form method="POST" action="/loans/returnBookLibraryConfirm/{{ $loan->id }}">
                                            @csrf @method('PUT')
                                            <button class="px-3 py-1 bg-emerald-600 text-white text-[9px] font-black rounded uppercase hover:bg-emerald-700 transition-colors">Врати</button>
                                        </form>
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
            });

            const target = document.getElementById(id);
            if (target) target.classList.remove('hidden');

            btn.classList.remove('bg-[#1e293b]/40', 'border-slate-800', 'text-slate-400');
            btn.classList.add('active-btn', 'bg-[#1e293b]', 'text-white');
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
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Фиксна висина грид контејнера за симетрију */
        .grid-cols-1 > div { min-height: 240px; }
    </style>
</x-layout>