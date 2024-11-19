<x-layout>
    <x-page-heading>Results on reguested tag</x-page-heading>
    <div class="space-y-6">
        @foreach($books as $book)
            <x-book-card-wide :$book />
        @endforeach
    </div>
</x-layout>