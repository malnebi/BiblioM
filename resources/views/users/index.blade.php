<x-layout>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h1 class="font-bold text-4xl">Чланови БибЛиб заједнице</h1>

                <x-forms.divider />

                <table class="table">
                    <thead>

                        <tr>
                            <th scope="col">Бр. </th>
                            <th scope="col">Име</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <h2 class="hover:text-blue-600  font-bold ">
                                        <a href="/users/{{ $user->id }}" class="btn btn-success" target="_blank">{{ $user->name }}</a>
                                    </h2>
                                </td>
                            </tr>
                        @endforeach



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
