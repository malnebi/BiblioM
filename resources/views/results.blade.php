<x-layout>
    <x-page-heading>Rezultati za traženu oznaku </x-page-heading>
    <div class="space-y-6">
        @foreach($books as $book)
            <x-book-card-wide :$book />
        @endforeach
    </div>
</x-layout>