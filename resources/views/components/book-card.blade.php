@props(['book'])

<x-panel class="flex flex-col text-center ">
    <div class="self-start text-sm">{{ $book->library->name }}</div>

    <div class="py-8 ">
        <h3 class="group-hover:text-blue-800 text-xl font-bold ">
            <a href= "/books/{{ $book->id }}/book" target="_blank" class="btn btn-success">
                {{ $book->title }}
            </a>
        </h3>
        <p class="text-sm mt-4">{{ $book->author_fname }}</p>
    </div>

    <div class="flex justify-between items-center mt-auto">
        <div>
            @foreach ($book->tags as $tag)
                <x-tag :$tag size="small" />
            @endforeach
        </div>
        <x-library-logo :library="$book->library" :width="42" />
    </div>
</x-panel>
