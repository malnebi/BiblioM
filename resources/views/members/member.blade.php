
<x-layout>

<div> {{'Member'}} {{ $member->id }} {{ $member->name }} </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">NAME </th>
                            <th scope="col">{{ $member->name }} </th>
                            
                            
                            <th scope="col"> Memberr ID </th> <td> {{ $member->id }}</td>
                            <th scope="col"></th>
                            <th>                              
                            </th>
                            <th>   
                                <form method="POST" action="/#" style="display: inline">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <input type="submit" class="btn btn-danger" value="Remove member -> to do next">
                                </form>
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        fQE1YbJfdA
                    <tr>
                        <th scope="col">Number of active loans -> to do next <td></td></th>
                    </tr>
                    <tr>
                        <th scope="col">Number of all borroved books -> to do next <td></td></th>
                    </tr>
                   
                    <tr>
                    <th scope="col">Books on loan -> to do next</th>  
                    </tr>                           
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</x-layout>
