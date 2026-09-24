<x-layout>
    <div class="space-y-10">
        <section class="text-center">
            <x-library-logo :library="$library ?? 'No library'" width="100" />
            <h1 class="font-bold text-4xl">
                @unless (str_starts_with($library->name, 'Библиотека '))
                    БИБЛИОТЕКА
                @endunless
                {{ $library->name }}
            </h1>
        </section>
        <section>

            @if ($books->count() > 0)
                {{-- SCENARIO A: Biblioteka ima knjiga --}}

            <div class="space-y-10">

                <section class="text-center">
                    <x-forms.form action="/search/oneLibraryBooks/{{ $library->id }}" class="mt-6">
                        <x-forms.input :label="false" name="q" placeholder="Наслов, аутор, година ..." />
                        <x-forms.button>ПРЕТРАЖИ</x-forms.button>
                    </x-forms.form>
                </section>

                
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
                        Bиблиотека је тренутно празна.</p>
                    
                </div>
            </section>
        @endif
    </div>

    <x-forms.divider />



</x-layout>
