
<x-layout>
    <div class="space-y-10">

        <section class="text-center">
            <h1 class="font-bold text-4xl"> PRONAĐI</h1>
            <x-forms.form action="/search" class="mt-6">
                <x-forms.input :label="false" name="q" placeholder="Title, authors name, year..." />
                {{-- <x-forms.button>Search</x-forms.button> --}}
            </x-forms.form>
        </section>

        <x-section-heading>ALL LOANS</x-section-heading>
        
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
<x-forms.divider/>
                    <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Korisnik</th>
                        <th scope="col"></th>
                        <th scope="col">Knjiga</th>
                        <th scope="col">Rok za vraćanje</th>
                        <th scope="col">Biblioteka</th>  
                        <th scope="col">Mjesto knjige - na polici <=> na čitanju</th>                        
                        
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($loans as $loan)
                        <tr>
                            <td>{{ $loan->id }}</td>
                            <td><a href="/users/{{ $loan->user->id }}" target="_blank">{{ $loan->user->name }}</a> </td>
                            <td></td>
                            <td class="font-bold bg-red-700" > $loan->book->id NE RADI {{-- $loan->book->id}} {{ $loan->book->title }} / {{ $loan->book->author_fname }} {{ $loan->book->author_lname --}} </td>
                            <td></td>
                            <td>{{ $loan->return_deadline }}</td>
                            <td>{{ $loan->active }}</td>
                            <td>{{ $loan->library->name }}</td>
                            
                            @if ($loan->active == 1  ) 
                                <td> Knjiga je na čitanju!  </td>  <td> ON  LOAN! </td>
                            @endif
                            
                            @if ($loan->active == 0) 
                                <td> Knjiga je vraćena </td> <td> {{$loan->updated_at}}</td>  
  
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div> 
                    {{$loans->links()}}</div> 
            </div>
</x-layout>
