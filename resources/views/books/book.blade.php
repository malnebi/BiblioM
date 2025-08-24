<x-layout>
<div>
<x-book-card-wide :$book/>  
</div>

<x-.forms.divider/>

@auth
@if ($book->library->owner_id == Auth::id())
{{-- $book->library->owner_id   //direktno pristupa koloni owner_id u tabeli libraries  
     $book->library->owner->id  //dobija vrijednost atributa id iz instance modela User koji je primarni ključ modela  --}}
<x-panel class="flex gap-x-6">

    <div class="container">
        
        
        <div class="row justify-content-center">
            <div class="col-md-8">         
                STATUS KNJIGE: {{ $book->loan == 1 ? 'On loan to ' . $book->userBorrows->name . '  ' : 'Free for loan' }}                   
            </div>                 
        <x-forms.divider />
            <a href="/books/{{$book->id}}/edit" class="btn btn-info">PROMJENA PODATAKA</a> 
        <x-forms.divider />
            <button  form="delete-form" class="text-red-500 text-sm font-bold leading-6">  BRISANJE KNJIGE    </button>  
      {{--
        <div class="col-md-8"> Number of loans: {{ $numberOfLoans }}</div>      
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table">
                    <thead>LOANS DATA FOR THIS BOOK</thead>
                    <tbody>
                        @foreach($clientName as $clientNames)
                        <tr> 

                            <td>Client ID: {{ $clientNames->client->id }},   {{ $clientNames->client->first_name }} {{ $clientNames->client->last_name}} </td> 
                            <td> {{ $clientNames->created_at}}</td>
                            <td> {{ $clientNames->updated_at}}</td>  
                        </tr>
                        @endforeach                                  
                    </tbody>
                </table>
            </div>
        </div>
        --}}
        </div>  

        <form method="POST" action="/books/{{ $book->id }}" id="delete-form" class="hidden" id="delete-form" >
            @csrf
            @method('DELETE')
            
            <script>
                document.getElementById('delete-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    if (confirm('Are you sure you want to delete this book?')) {
                        this.submit();
                    }
                });
            </script>
        </form>

    </div>
</x-panel>
@endif
@endauth
        
</x-layout>