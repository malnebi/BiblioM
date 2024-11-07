<x-layout>
    <div class="space-y-10">

        <section class="text-center">
            <h1 class="font-bold text-4xl"> Find A Book </h1>
          
            <x-forms.form action="/search" class="mt-6">
                <x-forms.input :label="false" name="q" placeholder="Title, authors name, year..." />
                {{-- <x-forms.button>Search</x-forms.button> --}}
            </x-forms.form>

        </section>

        <section class="pt-6">
            <x-section-heading>Featured Books</x-section-heading>

            </div>
        </section>

        <section>
            <x-section-heading>Tags</x-section-heading>

        </section>

        <section>
            <x-section-heading>Recent Books</x-section-heading>
            </div>
        </section>
    </div>
</x-layout>
