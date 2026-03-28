<x-layout>
    <div class="space-y-10">
        {{-- Zaglavlje biblioteke se uvek prikazuje --}}
        <section class="text-center">
            <x-library-logo :library="$library ?? 'No library'" width="100" />
            <h1 class="font-bold text-4xl">БИБЛИОТЕКА {{ $library->name }}</h1>
        </section>

        @if ($books->count() > 0)
            {{-- SCENARIO A: Biblioteka ima knjiga --}}
            <section>
                <div class="space-y-10">
                    {{-- Pretraga --}}
                    <section class="text-center">
                        <x-forms.form action="/search" class="mt-6">
                            <x-forms.input :label="false" name="q" placeholder="Наслов, аутор, година ..." />
                            <x-forms.button>ПРЕТРАЖИ</x-forms.button>
                        </x-forms.form>
                    </section>

                    {{-- Opisne oznake --}}
                    // <section>
                    //    <x-section-heading>Опсне ознаке књига моје библиотеке (у изради) </x-section-heading>

                    {{-- Najnovije knjige --}}
                    <section>
                        <x-section-heading>Најновије књиге библиотеке</x-section-heading>
                        <div class="mt-6 space-y-6">
                            @foreach ($books as $book)
                                <x-book-card-wide :$book />
                            @endforeach
                        </div>
                    </section>
                </div>
            </section>
        @else
            {{-- SCENARIO B: Biblioteka je prazna --}}
            <section class="text-center py-20 bg-slate-900/50 rounded-2xl border border-slate-800">
                <div class="max-w-md mx-auto">
                    <p class="text-gray-400 text-lg mb-8">
                        Ваша библиотека је тренутно празна. Почните тако што ћете додати прву књигу у свој инвентар.
                    </p>
                    <a href="/books/create"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold  
    transition-colors duration-200 rounded-full px-12 py-3 shadow-lg transform hover:scale-105">
                        + ДОДАЈ ПРВУ КЊИГУ
                    </a>
                </div>
            </section>
        @endif
    </div>

    <x-forms.divider />
</x-layout>
