<x-layout>
    <div class="space-y-12 max-w-7xl mx-auto py-8 px-4">
        
        {{-- 1. ЗАГЛАВЉЕ БИБЛИОТЕКЕ --}}
        <section class="bg-[#1e293b] border border-slate-700 p-8 rounded-3xl shadow-2xl relative overflow-hidden">
            {{-- Суптилна декорација у позадини --}}
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-blue-600/10 rounded-full blur-3xl"></div>
            
            <div class="flex flex-col items-center text-center relative z-10">
                <div class="mb-4 p-4 bg-slate-900/50 rounded-2xl border border-slate-700 shadow-inner">
                    <x-library-logo :library="$library ?? 'No library'" width="80" />
                </div>
                <h1 class="font-black text-xl md:text-4xl text-white tracking-tight uppercase">
                    @unless (str_starts_with($library->name, 'Библиотека '))
                        БИБЛИОТЕКА
                    @endunless
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">{{ $library->name }}</span>
                </h1>
                <div class="mt-2 flex items-center gap-2 text-slate-400 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Укупан број наслова: {{ $books->count() }}</span>
                </div>
            </div>

            {{-- ПРЕТРАГА (Унутар заглавља ради бољег фокуса) --}}
            @if ($books->count() > 0)
            <div class="mt-8 max-w-2xl mx-auto">
                <x-forms.form action="/search/oneLibraryBooks/{{ $library->id }}" class="relative">
                    <x-forms.input :label="false" name="q" placeholder="Претражи наслов, аутора или годину издања..." class="pl-4 pr-32 py-4 bg-slate-900 border-slate-700 focus:border-blue-500 text-white rounded-xl shadow-2xl" />
                    <div class="absolute right-2 top-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] px-6 py-2.5 rounded-lg transition-all uppercase tracking-widest shadow-lg">
                            Претражи
                        </button>
                    </div>
                </x-forms.form>
            </div>
            @endif
        </section>

        {{-- 2. САДРЖАЈ БИБЛИОТЕКЕ --}}
        @if ($books->count() > 0)
            <div class="space-y-16">
                
                {{-- ОПИСНЕ ОЗНАКЕ (У ИЗРАДИ) --}}
                <section>
                    <x-section-heading>Ознаке књига моје библиотеке</x-section-heading>
                    <div class="flex flex-wrap gap-2 mt-6">
                        {{-- Овде ће ићи тагови кад буду готови --}}
                        <span class="px-4 py-2 bg-slate-800/40 border border-slate-700 rounded-lg text-slate-500 text-xs italic">
                            Функционалност филтрирања по ознакама је у изради...
                        </span>
                    </div>
                </section>

                {{-- КАРТИЦЕ КЊИГА - ГРИД СА ТРИ КОЛОНЕ --}}
                <section>
                    <x-section-heading>Најновије књиге библиотеке</x-section-heading>
                    
                    {{-- ГРИД СА GAP-Y-12 РАЗМАКОМ --}}
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                        @foreach ($books as $book)
                            <div class="flex flex-col h-full">
                                <x-book-card-wide :$book />
                            </div>
                        @endforeach
                    </div>
                </section>

            </div>
        @else
            {{-- ПРАЗНА БИБЛИОТЕКА СЦЕНАРИО --}}
            <section class="text-center py-24 bg-[#1e293b]/30 rounded-3xl border-2 border-dashed border-slate-800">
                <div class="max-w-md mx-auto px-6">
                    <a href="/books/create"
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-black text-xs px-10 py-4 rounded-xl shadow-xl transform hover:-translate-y-1 transition-all uppercase tracking-widest">
                        + Додај прву књигу
                    </a>
                </div>
            </section>
        @endif

        <x-forms.divider />
    </div>
</x-layout>