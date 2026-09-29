<x-layout>
    <div class="max-w-6xl mx-auto py-16 px-6 space-y-24">

        {{-- 1. HERO СЕКЦИЈА --}}
        <section class="text-center space-y-6">
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter">
                Повезујемо читаоце,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">чувамо
                    књиге.</span>
            </h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto leading-relaxed font-medium">
                Дигитални мост између твоје кућне библиотеке и заједнице читалаца.
            </p>
        </section>

        <section class="bg-[#1e293b]/30 border border-slate-800 p-8 rounded-3xl shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 blur-3xl rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10 text-slate-300 leading-loose text-lg italic">
                <strong>БиблиоМ</strong> је платформа за евидентирање, позајмљивање и размјену књига из кућних библиотека.
                Повезујући дигиталну евиденцију са стварним кретањем књига међу читаоцима, БиблиоМ подстиче читање,
                сарадњу и одговорно коришћење књига.
            </div>
        </section>


        <section class="space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-feature-card title="Дигитална библиотека"
                    description="Претвори своју кућну библиотеку у уређену и претраживу дигиталну збирку. Евидентирај књиге и учини их доступним другим читаоцима."
                    color="blue">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </x-feature-card>

                <x-feature-card title="Позајмљивање и размјена"
                    description="Прати доступност и позајмице књига. Једноставна евиденција омогућава да књиге безбједно круже и проналазе пут до нових читалаца."
                    color="emerald">
                    <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </x-feature-card>

                <x-feature-card title="Заједница читалаца"
                    description="Постани дио читалачке мреже засноване на сарадњи, повјерењу и одговорном односу према књигама."
                    color="amber">
                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </x-feature-card>
            </div>
        </section>

        <section class="text-center pt-10 border-t border-slate-800"></section>
    </div>
</x-layout>
