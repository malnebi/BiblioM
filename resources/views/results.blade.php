<x-layout>
    @if ($books->count() > 0)
    <x-page-heading>Резултати претраге {{--$query --}}  </x-page-heading>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
        @foreach($books as $book)
            <x-book-card-wide :$book />
        @endforeach
    </div>

    @else
    <x-page-heading>Нема резултата за тражени појам</x-page-heading>
    @endif
</x-layout>