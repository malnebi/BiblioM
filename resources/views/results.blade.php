<x-layout>
    <x-page-heading>Резултати за одредницу {{-- $tag->name --}} </x-page-heading>
    <div class="space-y-6">
        @foreach($books as $book)
            <x-book-card-wide :$book />
        @endforeach
    </div>
</x-layout>