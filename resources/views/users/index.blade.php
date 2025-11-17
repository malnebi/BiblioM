<x-layout>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <h1 class="font-bold text-4xl">БибЛиб заједницa</h1>

                <x-forms.divider />

                <table class="table">
                    <thead>

                        <tr>
                            <th scope="col">Бр. </th>
                            <th scope="col">Име</th>
                            <th scope="col">Библиотека</th>
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
                                <td class="col-span-2">
                                    @foreach ($libraries as $library)
                                        @if ($library->owner_id === $user->id)
                                            <h2 class="hover:text-blue-600  font-bold ">
                                                <a href="/libraries/{{ $library->id }}" class="btn btn-success" target="_blank">{{ $library->name }}</a>
                                            </h2>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
