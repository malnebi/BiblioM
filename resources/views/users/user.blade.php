<x-layout>
    <x-section-heading>КОРИСНИК {{ $user->name }}</x-section-heading>


    <div class="container">
        <div class="row justify-content-left">
            <div class="col-md-8">
                <tr>
                    <th scope="col">Број активних позајмица..... {{ $loansNumber }} </th>
                </tr>
                <tr>
                    <th scope="col">Број свих позајмљених књига....{{ $allLoansNumber }} </th>
                </tr>
                <h3>Књиге на позајмици ....</h3>
                <table class="table">
                    <thead>




                    </thead>
                    <tbody>

                        <tr>
                            @foreach ($loansBooks as $loansBook)
                        <tr>
                            <td>Book id: {{ $loansBook->book->id }} </td>
                            <td> {{ $loansBook->book->title }} / {{ $loansBook->book->author_fname }}
                                {{ $loansBook->book->author_lname }}</td>
                            <td>Return deadline </td>
                            <td> {{ $loansBook->return_deadline }} </td>

                            @if ($loansNumber > 0)
                                <td>
                                    <form method="POST" action="/loans/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">
                                            <button type="submit" class="btn btn-primary">Return a book!</button>
                                        </div>
                                    </form>
                                </td>

                                <td>
                                    <form method="POST" action="/loans/extend/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">

                                            <button type="submit" class="btn btn-primary">Extend deadline!</button>

                                        </div>
                                    </form>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                        </tr>

                    </tbody>
                </table>

                @if ($loansNumber <= 1)
                    <a href="/loans/create/{{ $user->id }}" class="btn btn-success">Позајми књигу</a>
            </div>
        </div>
    </div>
    @endif


</x-layout>
