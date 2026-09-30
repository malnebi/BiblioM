@props(['book'])

<x-panel class="group flex h-full flex-col text-center">
    <div class="self-start text-sm">{{ $book->library->name }}</div>
   @if ($book->loan == 1)
            {{-- Ako je knjiga pozajmljena nekome --}}
            <div class="text-red-500 text-xs font-bold"> На читању. </div>
        @elseif ($book->loans->where('description', 'rezervisano')->where('user_id', Auth::id())->first())
            <div class="text-orange-500 text-xs font-bold"> Резервисана! </div>
        @else
            <div class="text-green-500 text-xs font-bold"> На полици. </div>
        @endif
    <div class="py-8 ">
        <h3 class="group-hover:text-blue-800 text-xl font-bold ">
            <a href= "/books/{{ $book->id }}/book" target="_blank" class="btn btn-success">
                {{ $book->title }}
            </a>
        </h3>
        <p class="text-sm mt-4">{{ $book->author_lname }}, {{ $book->author_fname }}</p>
    </div>

    <div class="flex justify-between items-center mt-auto">
        <div>
            @foreach ($book->tags as $tag)
               <x-tag :tag="$tag" size="small" />
            @endforeach
        </div>
        <x-library-logo :library="$book->library" :width="42" />
    </div>
</x-panel>
