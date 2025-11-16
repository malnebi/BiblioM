
<x-layout>
    <div class="space-y-10">
   
        {{-- 
        <section class="text-center">
            <h1 class="font-bold text-4xl"> ПРОНАЂИ</h1>
            <x-forms.form action="/search" class="mt-6">
                <x-forms.input :label="false" name="q" placeholder="Title, authors name, year..." />
                {{-- <x-forms.button>Search</x-forms.button> --}}

        
        {{-- 
        <section class="pt-6">
            <div class="grid lg:grid-cols-3 gap-8 mt-6">
                @foreach ($featuredBooks as $book)
                <x-book-card :$book />
                @endforeach
            </div>
        </section>
        
        <section>
            <x-section-heading>Tags</x-section-heading>
            
            <div class="mt-6 space-x-1">
                @foreach ($tags as $tag)
                <x-tag :$tag />
                @endforeach
            </div>
        </section>
        
        <section>
            <x-section-heading>Recent Books</x-section-heading>
            <div class="mt-6 space-y-6">
                @foreach ($books as $book)
                <x-book-card-wide :$book />
                @endforeach
            </div>
        </section>
    </div>    
    --}} 
    <x-section-heading>СВА ЗАДУЖЕЊА КЊИГА БИБЛИОТЕКЕ {{ Auth::user()->ownLibrary->name }} </x-section-heading>
<x-forms.divider/>
                    <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Број задужења</th>                        
                        <th scope="col">КОРИСНИК</th>
                        <th scope="col">Наслов књиге / Аутор </th>
                        <th scope="col">Рок за враћање</th>
                        <th scope="col">Библиотека</th>  
                        <th scope="col">Књига се налази </th> 
                        <th scope="col">Датум враћања</th>                       
                        
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($loans as $loan)
                        <tr class="text-center">
                            <td>{{ $loan->id }}</td>
                            <td>
                             <h3 class="hover:text-blue-600  font-bold ">   
                                <a href="/users/{{ $loan->user->id }}" target="_blank">{{ $loan->user->name }}</a> 
                             </h3>
                            </td> 
                            
                            <td class="font-bold  text-red-200" > 
                                {{ $loan->book->id }} {{ $loan->book->title }} / {{ $loan->book->author_fname }} 
                            </td> 
                            
                                <td>{{  ($loan->updated_at)->format('d. m. Y. ') }}</td>
                            <td>{{ $loan->library->name }}</td>
                            
                                @if ($loan->active == 1  ) 
                            <td> на читању   </td> 
                                @endif
                            
                                @if ($loan->active == 0) 
                            <td> на полици  </td> <td> {{$loan->updated_at}}</td>  
                                @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div> 
                    {{$loans->links()}}</div> 
            </div>
</x-layout>
