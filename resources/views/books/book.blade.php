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
                СТАТУС КЊИГЕ: {{ $book->loan == 1 ? 'Књига је код корисника' : 'На полици' }}                   
                @if ($book->loan == 1)
                <a href="/users/{{$book->lib_user_id}}" class="btn btn-info" target="_blank">{{ $book->userBorrows->name }}</a>    
                @endif
            </div>                 
        </div>
        <x-forms.divider />
            <a href="/books/{{$book->id}}/edit" class="btn btn-info">ПРОМЈЕНА ПОДАТАКА</a> 
        <x-forms.divider />
            <button  form="delete-form" class="text-red-500 text-sm font-bold leading-6">  БРИСАЊЕ КЊИГЕ    </button>  
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

@if ($book->library->owner_id != Auth::id())
    
    

          СТАТУС КЊИГЕ: {{ $book->loan == 1 ? 'Књига је код корисника' : 'На полици' }}                   
          
                @if ($book->loan == 0  || $book->lib_user_id  == null)
                {{-- Ako je knjiga na polici ili nije dodijeljena nikom  --}}
               <form method="POST" action="/bookReservation/{{ $book->id }}"  enctype="multipart/form-data">
                @csrf
                <div class="col-md-8">
                   <button type="submit" class="btn btn-primary">РЕЗЕРВИШИ!</button>
                @endif


@endif
        
</x-layout>