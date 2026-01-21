<x-layout>
    <div class="space-y-10">
        
        <div>
        <x-library-logo :library="$library ?? 'No library'"  width="100"/>
        </div>
   
        <h1 class="font-bold text-4xl"> БИБЛИОТЕКА {{ $library->name }} </h1>

        <section class="pt-6 text-center">
            <x-nav-link href="/books/create">ДОДАЈ КЊИГУ</x-nav-link>

            
            @if ($numOfBooks != 0)
                <x-nav-link href="/books/create">ПРЕТРАЖИ БИБЛИОТЕКУ</x-nav-link>

                <x-nav-link href="/loans">ПОГЛЕДАЈ ПОЗАЈМИЦЕ</x-nav-link>
            @endif
        </section>

        

        <section class="text-center">
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

    </div>

    <x-forms.divider />

</x-layout>
