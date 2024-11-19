<x-layout>
    <section class="text-center">
        <h1 class="font-bold text-4xl"> Шта ти се чита?</h1>
      
        <x-forms.form action="/search-tag" class="mt-6">
            <x-forms.input :label="false" name="q" placeholder="Наука, Умјетност, Спорт, Технологија, Књижевност, Језик, Белетристика ... " />
            {{-- <x-forms.button>Search</x-forms.button> --}}
        </x-forms.form>
    
    </section>


    <section>
        <x-section-heading>Tags</x-section-heading>

        <div class="mt-6 space-x-1">
            @foreach ($tags as $tag)
                <x-tag :$tag />
            @endforeach
        </div>
    </section>

    
    </x-layout>