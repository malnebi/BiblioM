<x-layout>
<div>
<x-book-card-wide :$book/>  
</div>

<x-.forms.divider/>

@auth
<x-panel class="flex gap-x-6">

    <div class="container">
        <a href="/books/{{$book->id}}/edit" class="btn btn-info">EDIT BOOK DATA</a> 
 
        <div class="row justify-content-center">
            <div class="col-md-8">
                BOOK STATUS: {{ $book->loan == 1 ? 'On loan to ' . $book->client->first_name . '  ' . $book->client->last_name . '' : 'Free for loan' }}                   
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

</x-panel>
@endauth

</x-layout>