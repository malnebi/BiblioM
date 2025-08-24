
<x-layout>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                
                <table class="table">
                    <thead>

                        <tr> <h1 scope="col">Korisnici aplikacije</h1></tr>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">IME</th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr><a href="/user/{{ $user->id }}">
                            <td>{{ $user->id }}</td>
                            <td><a href="/users/{{ $user->id }}" class="btn btn-success" target="_blank">{{ $user->name }}</a></td>                            
                            
                        </tr>
                        @endforeach

                    
                 
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</x-layout>
