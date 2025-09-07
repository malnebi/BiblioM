<x-layout>
    <div class="space-y-10">
        <section class="text-center">
            <h1 class="font-bold text-4xl">БИБЛИОТЕКА {{ $library->name }}</h1>
        </section>
        <section>
            <div class="space-y-10">

                <section class="text-center">
                    <h1 class="font-bold text-4xl"> {{ $library->name }} библиотечка колекција.</h1>

                    <x-forms.form action="/search" class="mt-6">
                        <x-forms.input :label="false" name="q" placeholder="Наслов, аутор, година ..." />
                        {{-- <x-forms.button>Search</x-forms.button> --}}
                    </x-forms.form>
                </section>

                <section class="pt-6">
                    <x-section-heading>Истакнуто</x-section-heading>

                    <div class="grid lg:grid-cols-3 gap-8 mt-6">
                        @foreach ($featuredBooks as $book)
                            <x-book-card :$book />
                        @endforeach
                    </div>
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
    </div>
</x-layout>
