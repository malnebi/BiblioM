<x-layout>
    <div class="space-y-10">

        <section class="text-center">
            <h1 class="font-bold text-4xl"> {{ $library->name }} Library collection.</h1>
            @if ($numOfBooks == 0)
                <h4> Add some books to your library! </h4>
            @else
                <x-forms.form action="/search" class="mt-6">
                    <x-forms.input :label="false" name="q" placeholder="Title, authors name, year..." />
                    {{-- <x-forms.button>Search</x-forms.button> --}}
                </x-forms.form>
                <section class="pt-6">
                    <x-section-heading>Featured Books</x-section-heading>

                    <div class="grid lg:grid-cols-3 gap-8 mt-6">
                        @foreach ($featuredBooks as $book)
                            <x-book-card :$book />
                        @endforeach
                    </div>
                </section>


        </section>
        <section>
            <x-section-heading>Books</x-section-heading>
            <div class="mt-6 space-y-6">
                @foreach ($books as $book)
                    <x-book-card-wide :$book />
                @endforeach
            </div>
        </section>
        @endif
    </div>
</x-layout>
