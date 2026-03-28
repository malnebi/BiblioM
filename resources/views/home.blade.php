<x-layout>
    <div class="space-y-10">

        <section class="text-center">
            <h1 class="font-bold text-4xl"> Пронађи књигу </h1>
          
            <x-forms.form action="/search" class="mt-6">
                <x-forms.input :label="false" name="q" placeholder="Наслов, име аутора, година издавања..." />
                {{-- <x-forms.button>Search</x-forms.button> --}}
            </x-forms.form>

        </section>

        <section>
            <x-section-heading>Опсине ознаке</x-section-heading>

            <div class="mt-6 space-x-1">
                @foreach ($tags as $tag)
                    <x-tag :$tag />
                @endforeach
            </div>
        </section>

        <section>
            <x-section-heading>Најновије књиге у апликацији</x-section-heading>
            <div class="mt-6 space-y-6">
                @foreach ($books as $book)
                    <x-book-card-wide :$book />
                @endforeach
            </div>
        </section>
    </div>
</x-layout>
