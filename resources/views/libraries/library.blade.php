<x-layout>
    <div class="space-y-12 max-w-7xl mx-auto py-8 px-4">
        <section class="bg-[#1e293b] border border-slate-700 p-8 rounded-3xl shadow-2xl relative overflow-hidden">
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex min-w-0 items-center gap-4">
                    <div class="shrink-0 p-4 bg-slate-900/50 rounded-2xl border border-slate-700 shadow-inner">
                        <x-library-logo :library="$library" width="64" />
                    </div>
                    <div class="min-w-0">
                        <h1 class="font-black text-lg :text-2xl text-white uppercase">
                            @unless (str_starts_with($library->name, 'Библиотека '))
                                БИБЛИОТЕКА
                            @endunless
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">{{ $library->name }}</span>
                        </h1>
                        <div class="mt-2 flex items-center gap-2 text-slate-400 text-sm font-medium">
                            <span>Укупан број наслова: {{ $books->count() }}</span>
                        </div>
                    </div>
                </div>

                @if ($books->isNotEmpty())
                    <div class="w-full lg:w-1/2">
                        <x-forms.form action="/search/oneLibraryBooks/{{ $library->id }}" class="relative !mx-0 !max-w-none">
                            <x-forms.input :label="false" name="q" placeholder="Претражи наслов, аутора или годину издања..." class="pl-4 pr-32 py-4 bg-slate-900 border-slate-700 focus:border-blue-500 text-white rounded-xl shadow-2xl" />
                            <div class="absolute right-2 top-2">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black text-[10px] px-6 py-2.5 rounded-lg transition-all uppercase tracking-widest shadow-lg">
                                    Претражи
                                </button>
                            </div>
                        </x-forms.form>
                    </div>
                @endif
            </div>
        </section>

        @if ($books->isNotEmpty())
            <section>
                <x-collapsible-section
                    title="Најновије књиге библиотеке"
                    :count="$books->count()"
                    :show-more="$books->count() > 6"
                >
                    <x-slot name="visible">
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                            @foreach ($books->take(6) as $book)
                                <x-book-card-wide :$book />
                            @endforeach
                        </div>
                    </x-slot>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                        @foreach ($books->skip(6) as $book)
                            <x-book-card-wide :$book />
                        @endforeach
                    </div>
                </x-collapsible-section>
            </section>
        @else
            <section class="text-center py-24 bg-[#1e293b]/30 rounded-3xl border-2 border-dashed border-slate-800">
                <p class="text-gray-400 text-lg">Библиотека тренутно нема књига.</p>
            </section>
        @endif

        <x-forms.divider />
    </div>
</x-layout>
