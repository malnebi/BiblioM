<x-layout>
    <div class="space-y-12 max-w-7xl mx-auto py-8 px-4 text-slate-200">
        
        {{-- ================== DASHBOARD ================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            {{-- Корисник --}}
            <div class="lg:col-span-1 bg-[#1e293b] border border-slate-700 p-5 rounded-2xl flex items-center gap-4 shadow-xl">
                <img src="{{ $user->user_photo ? asset('storage/' . $user->user_photo) : Vite::asset('resources/images/user-placeholder.svg') }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-xl object-cover border border-slate-600">
                <div class="min-w-0">
                    <h1 class="font-bold text-white truncate text-base">{{ $user->name }}</h1>
                    <p class="text-slate-500 text-[10px] uppercase tracking-tighter italic">Мој Профил</p>
                </div>
                <x-library-logo :library="$user->ownLibrary" :width="56" />
            </div>

            {{-- Статистика --}}            
            <div class="lg:col-span-3 grid grid-cols-2 md:grid-cols-4 gap-3">
                <button onclick="toggleDetail('det-preuzimanje', this)" class="stat-btn bg-[#1e293b]/40 border border-slate-800 p-3 rounded-xl hover:border-blue-500 transition-all group">
                    <span class="block text-slate-500 text-[9px] font-bold uppercase mb-1 group-hover:text-blue-400 text-center">За преузимање</span>
                    <span class="text-2xl font-black text-blue-400 block text-center">{{ count($inProgressLoans) }}</span>
                </button>

                <button onclick="toggleDetail('det-citanje', this)" class="stat-btn bg-[#1e293b]/40 border border-slate-800 p-3 rounded-xl hover:border-emerald-500 transition-all group">
                    <span class="block text-slate-500 text-[9px] font-bold uppercase mb-1 group-hover:text-emerald-400 text-center">Читање</span>
                    <span class="text-2xl font-black text-emerald-400 block text-center">{{ $loansNumber }}</span>
                </button>

                <button onclick="toggleDetail('det-rezervacije', this)" class="stat-btn bg-[#1e293b]/40 border border-slate-800 p-3 rounded-xl hover:border-amber-500 transition-all group">
                    <span class="block text-slate-500 text-[9px] font-bold uppercase mb-1 group-hover:text-amber-400 text-center">Резервације</span>
                    <span class="text-2xl font-black text-amber-400 block text-center">{{ count($reservedLoans) }}</span>
                </button>

                <button onclick="toggleDetail('det-procitano', this)" class="stat-btn bg-[#1e293b]/40 border border-slate-800 p-3 rounded-xl hover:border-indigo-500 transition-all group">
                    <span class="block text-slate-500 text-[9px] font-bold uppercase mb-1 group-hover:text-indigo-400 text-center">Прочитано</span>
                    <span class="text-2xl font-black text-indigo-400 block text-center">{{ $overLoansCount }}</span>
                </button>
            </div>
        </div>

        <x-forms.divider />

        {{-- ================== ДЕТАЉИ СЕКЦИЈА ================== --}}
        <div id="details-wrapper">

            {{-- 1. ПРЕУЗИМАЊЕ --}}
            <div id="det-preuzimanje" class="detail-sec hidden animate-fade-in">
                <h2 class="text-blue-400 text-[11px] font-bold uppercase tracking-[0.2em] mb-8">Књиге за преузимање</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                    @foreach ($inProgressLoans as $loansBook)
                        <div class="flex flex-col">
                            <x-book-card-wide :book="$loansBook->book" />
                            <form method="POST" action="/loans/loanBookMemberConfirm/{{ $loansBook->id }}" class="mt-4">
                                @csrf @method('PUT')
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black py-2 rounded-lg uppercase tracking-widest transition-colors shadow-lg">
                                    Потврди преузимање
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. ЧИТАЊЕ --}}
            <div id="det-citanje" class="detail-sec hidden animate-fade-in">
                <h2 class="text-emerald-400 text-[11px] font-bold uppercase tracking-[0.2em] mb-8">Тренутно на читању</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                    @foreach ($activeLoans as $loansBook)
                        <x-book-card-wide :book="$loansBook->book" />
                    @endforeach
                </div>
            </div>

            {{-- 3. РЕЗЕРВАЦИЈЕ --}}
            <div id="det-rezervacije" class="detail-sec hidden animate-fade-in">
                <h2 class="text-amber-400 text-[11px] font-bold uppercase tracking-[0.2em] mb-8">Ваше резервације</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-16">
                    @foreach ($reservedLoans as $loansBook)
                        <div class="flex flex-col">
                            <x-book-card-wide :book="$loansBook->book" />
                            <div class="mt-4 bg-amber-100 border-l-4 border-amber-500 p-3 rounded-r-xl shadow-md flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-tight">Резервисано:</span>
                                <span class="text-xs font-black">{{ $loansBook->created_at->format('d.m.Y.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 4. ПРОЧИТАНО --}}
            <div id="det-procitano" class="detail-sec hidden animate-fade-in">
                <h2 class="text-indigo-400 text-[11px] font-bold uppercase tracking-[0.2em] mb-8 text-center md:text-left">Историја прочитаних књига</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-16">
                    @foreach ($overLoans as $loan)
                        <div class="flex flex-col">
                            <x-book-card-wide :book="$loan->book" />
                            {{-- СВЕТЛА ПОДЛОГА СА ТАМНИМ СЛОВИМА И ВЕЋИМ РАЗМАКОМ (MT-4) --}}
                            <div class="mt-4 bg-slate-100 border-b-2 border-indigo-400 p-3 rounded-xl shadow-inner flex flex-col gap-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-[11px] text-slate-900 font-black italic">{{ $loan->created_at->format('d.m.y') }}</span>
                                    <span class="text-slate-900 text-xs">—</span>
                                    <span class="text-[11px] text-slate-900 font-black italic">{{ $loan->updated_at->format('d.m.y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        function toggleDetail(id, btn) {
            const sections = document.querySelectorAll('.detail-sec');
            const btns = document.querySelectorAll('.stat-btn');
            const target = document.getElementById(id);
            const isVisible = !target.classList.contains('hidden');

            sections.forEach(s => s.classList.add('hidden'));
            btns.forEach(b => {
                b.classList.remove('bg-slate-800/80', 'border-blue-500', 'border-emerald-500', 'border-amber-500', 'border-indigo-500');
                b.classList.add('bg-[#1e293b]/40', 'border-slate-800');
            });

            if (!isVisible) {
                target.classList.remove('hidden');
                btn.classList.add('bg-slate-800/80', 'border-current');
                target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-layout>