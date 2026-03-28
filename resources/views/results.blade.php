<x-layout>
    @if ($books->count() > 0)
    <x-page-heading>Резултати претраге {{--$query --}}  </x-page-heading>

    <div class="space-y-6">
        @foreach($books as $book)
            <x-book-card-wide :$book />
        @endforeach
    </div>

    @else
    <x-page-heading>Нема резултата за тражени појам</x-page-heading>
    @endif
</x-layout>