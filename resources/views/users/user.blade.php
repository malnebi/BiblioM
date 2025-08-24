    
<x-layout>
<x-section-heading>КОРИСНИК {{$user->name}}</x-section-heading>
         

    <div class="container">
        <div class="row justify-content-left">
            <div class="col-md-8">
                <table class="table">
                    <thead>
                
                        <tr>
                           <th scope="col">Број активних позајмица..... </th>
                        </tr>
                        <tr>
                           <th scope="col">Број свих позајмљених књига....  </th>
                    </tr>
                   
                    <tr>
                        <th scope="col">Књиге на позајмици ....</th>  
                    </tr>                           
                    <th>   
                    </th>
                    </thead>    
                    
                    <tbody>
                        <td> 5{{--$user->books->count()--}}</td>
                        <td> 20{{--$user->books->count()--}}  </td>
                    <td> broj </td>
                    <td> broj </td>    
                    <td> broj </td>    
    
                </tbody>
            </table>
{{-- 
@if($numberOfLoans <= 1)
@endif
--}}
<a href="/loans/create/{{$user->id}}" class="btn btn-success">Позајми књигу</a>   
        </div>
    </div>
</div>

<form method="POST" action="/#" style="display: inline">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    <input type="submit" class="btn btn-danger " value="REMOVE MEMBER -> to do next">
</form>

</x-layout>
