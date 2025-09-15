<x-layout>
    <div class="space-y-10">

        <section class="text-center">
            <h1 class="font-bold text-4xl"> {{ $library->name }} библиотечка колекција.</h1>
            @if ($numOfBooks == 0)
                <x-forms.divider />
                <x-nav-link href="/books/create" :active="request()->is('books/create')">Додај књиге у своју библиотеку</x-nav-link> </h>
            @else
                <x-forms.form action="/search" class="mt-6">
                    <x-forms.input :label="false" name="q" placeholder="Наслов, аутор, година ..." />
                    <x-forms.button>Пронађи</x-forms.button> 
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
        @endif
    </div>
</x-layout>
