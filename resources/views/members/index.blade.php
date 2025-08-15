
<x-layout>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                
                <table class="table">
                    <thead>

                        <tr> <h1 scope="col">App Members</h1></tr>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Members's name </th>
                            <th scope="col">ID</th>
                            <th scope="col">Members's name </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                        <tr><a href="/members/{{ $member->id }}">
                            <td>{{ $member->id }}</td>
                            <td><a href="/members/{{ $member->id }}/member" class="btn btn-success">{{ $member->name }}</a></td>
                            
                            
                        </tr>
                        @endforeach

                    
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Members's name </th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                        <tr><a href="/members/{{ $member->id }}">
                        <td>{{ $member->id }}</td>
                        <td><a href="/members/{{ $member->id }}/member" class="btn btn-success">{{ $member->name }}</a></td>
            
                        
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</x-layout>
