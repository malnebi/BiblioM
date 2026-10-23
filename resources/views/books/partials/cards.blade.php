@foreach ($books as $book)
    <div class="flex flex-col h-full">
        <x-book-card-wide :$book />
    </div>
@endforeach
