<x-layout>
    <div class="space-y-10">
        <section class="text-center">
            <h1 class="font-bold text-4xl"> {{ $user->name }}</h1>
        </section>
        <div class="grid lg:grid-cols-1 gap-8 mt-6">
            <x-section-heading> РЕЗЕРВАЦИЈЕ </x-section-heading>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Библиотека</th>
                    <th scope="col">--</th>
                    <th scope="col">Брoj Наслов / Аутор</th>
                    <th scope="col"></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($loansBooksReserved as $loansBook)
                    <tr class="text-center">
                        <td>
                            <h3 class="hover:text-blue-600  font-bold ">
                                <a href="/users/{{ $loansBook->library->id }}"
                                    target="_blank">{{ $loansBook->library->name }}</a>
                            </h3>
                        </td>

                        <td class="font-bold text-green-500"> {{ $loansBook->book->id }} </td>

                        <td> {{ $loansBook->book->title }} / {{ $loansBook->book->author_fname }}
                            {{ $loansBook->book->author_lname }}</td>
                        <td>
                            @if ($loansBook->active == 1)
                                <form method="POST" action="/loans/loanMemberConfirm/{{ $loansBook->id }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="col-md-8">
                                        <h3 class="hover:text-blue-600  font-bold ">
                                            <button type="submit" class="btn btn-primary">Потврди позајмицу!</button>
                                        </h3>
                                    </div>
                                </form>
                            @else
                                Захтјев за позајмицу прослијеђен библиотеци {{ $loansBook->library->name }}
                            @endif
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>


        @if ($loansNumber > 0)
            <div class="grid lg:grid-cols-1 gap-8 mt-6">
                <x-section-heading> КЊИГЕ НА ЧИТАЊУ ({{ $loansNumber }})</x-section-heading>
            </div>

            <x-forms.divider />

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Библиотека--</th>
                        <th scope="col">Брoj књиге</th>
                        <th scope="col">Наслов / Аутор</th>
                        <th scope="col">Рок за враћање</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($loansBooksActive as $loansBook)
                        <tr class="text-center">
                            <td>
                                <h3 class="hover:text-blue-600  font-bold ">
                                    <a href="/users/{{ $loansBook->library->id }}"
                                        target="_blank">{{ $loansBook->library->name }}</a>
                                </h3>
                            </td>

                            <td class="font-bold text-green-500"> {{ $loansBook->book->id }} </td>

                            <td> {{ $loansBook->book->title }} / {{ $loansBook->book->author_fname }}
                                {{ $loansBook->book->author_lname }}</td>
                            <td class="font-bold text-red-500">
                                {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.') }}-</td>
                            <td>

                                @if ($loansBook->active == 0)
                                    Враћена књига - чека се потврда
                                @endif

                                @if ($loansBook->active == 1 && $loansBook->description == 'potpisano')
                                    <form method="POST" action="/loans/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">
                                            <h3 class="hover:text-blue-600  font-bold ">
                                                <button type="submit" class="btn btn-primary"> Врати
                                                    књигу!</button>
                                            </h3>
                                        </div>
                                    </form>
                                @endif

                                @if ($loansBook->active == 1 && $loansBook->description == null)
                                    <form method="POST" action="/loanMemberConfirm/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">
                                            <h3 class="hover:text-blue-600  font-bold ">
                                                <button type="submit" class="btn btn-primary">Потврди
                                                    позајмицу!</button>
                                            </h3>
                                        </div>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @endif
        <x-forms.divider />


        <div class="grid lg:grid-cols-1 gap-8 mt-6">
            <x-section-heading> ПРОЧИТАНЕ КЊИГЕ ( {{ $loansNumberOver }} )</x-section-heading>
        </div>
        <x-forms.divider />
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">БИБЛИОТЕКА</th>
                    <th scope="col">Број књиге</th>
                    <th scope="col">Наслов / Аутор </th>
                    <th scope="col">--</th>
                    <th scope="col">Датум</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loansBooksOver as $loansBooks)
                    <tr class="text-center">
                        <td>
                            <h3 class="hover:text-blue-600  font-bold ">
                                <a href="/users/{{ $loansBooks->library->id }}"
                                    target="_blank">{{ $loansBooks->library->name }}</a>
                            </h3>
                        </td>
                        <td>{{ $loansBooks->book->id }}</td>
                        <td class="font-bold  text-red-200">
                            {{ $loansBooks->book->id }} {{ $loansBooks->book->title }} /
                            {{ $loansBooks->author_fname }}
                        </td>
                        <td>{{ $loansBooks->created_at->format('d. m. Y. ') }}</td>
                        <td> - </td>
                        <td> {{ $loansBooks->updated_at->format('d. m. Y. ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>{{ $loans->links() }}</div>
    </div>
</x-layout>
