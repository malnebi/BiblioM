<x-layout>
    <div class="space-y-10">

        <div>
            <x-library-logo :library="$library ?? 'No library'" width="100" />
        </div>

        <h1 class="font-bold text-xl"> БИБЛИОТЕКА {{ $library->name }} </h1>
        <section class="pt-6 text-center">

            <div class="grid lg:grid-cols-3 gap-8 mt-6 text-md">
                <div class="flex flex-col gap-8 mt-7">
                    <x-nav-link href="/library/libraryBooks/{{ $library->id }}">КЊИГЕ </x-nav-link>
                </div>
                <div class="flex flex-col gap-8 mt-7">
                    <x-nav-link href="/books/create">ДОДАЈ НОВУ</x-nav-link>
                </div>
                <div class="flex flex-col gap-8 mt-7">
                    <x-nav-link href="/loans/myLibraryLoans/{{ $library->id }}">ПОЗАЈМИЦЕ</x-nav-link>
                </div>
        </section>

    </div>

    <x-forms.divider />

</x-layout>
