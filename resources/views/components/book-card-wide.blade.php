@props(['book'])

<x-panel class="flex gap-x-6">
   <div>
        <x-library-logo :library="$book->library ?? 'No library'"/>
    </div>

   <div class="flex-1 flex flex-col">
       <p class="text-sm text-gray-400 mt-auto transition-colors duration:300"> {{$book->author_lname}}, {{ $book->author_fname }} </p>
       
       <h3 class="font-bold text-xl mt-3 group-hover:text-blue-800 " >
           <a href= "/books/{{$book->id}}/book" target="_blank" class="btn btn-success">
            {{ $book->title }} / {{ $book->author_fname }} {{ $book->author_lname }}. - {{ $book->publisher_place }}: {{ $book->publisher_name }}, {{ $book->year }} 
           </a>
      </h3>
        
        <a href="#" class="self-start text-sm text-gray-400 transition-colors duration:300">Библиотека {{ $book->library->name}}</a">
    </div>

    <div> 
            {{--
            !!<x-book-img :$book> </x-book-img> 
            --}}
    </div>
        <div >
            @foreach($book->tags as $tag)
            <x-tag :$tag/>
            @endforeach       
        </div>
        
</x-panel>