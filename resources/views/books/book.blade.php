<x-layout>
<div>
<x-book-card-wide :$book/>  
</div>

<x-.forms.divider/>

@auth
<x-panel class="flex gap-x-6">

    <div class="container">
        <a href="/books/{{$book->id}}/edit" class="btn btn-info">EDIT BOOK DATA</a> 

       <button  form="delete-form" class="text-red-500 text-sm font-bold leading-6">  Delete book</button>  

        <div class="row justify-content-center">
            <div class="col-md-8">         
                BOOK STATUS: {{ $book->loan == 1 ? 'On loan to ' . $book->lib_user_id . '  ' .$book->lib_user_id  . '' : 'Free for loan' }}                   
            </div>                 
          
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
</x-panel>
@endauth
        
</x-layout>