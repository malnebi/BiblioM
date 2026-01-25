<x-layout>
    <div class="space-y-10">
        <section class="text-center">
            <h1 class="font-bold text-4xl"> Мој профил</h1>
        </section>


        @if ($inProgressLoans->isNotEmpty())
            <div class="grid lg:grid-cols-1 gap-8 mt-6">
                <h1 class="font-bold text-xl text-left text-blue-500">ПРЕУЗИМАЊЕ У ТОКУ </h1>
            </div>
            <table class="table">
                <thead>
                    <tr class="text-justify">
                        <th scope="col">Библиотека</th>
                        <th scope="col">--</th>
                        <th scope="col">Брoj Наслов / Аутор</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inProgressLoans as $loansBook)
                        <tr class="text-justify">
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
                                <form method="POST" action="/loans/loanMemberConfirm/{{ $loansBook->id }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="col-md-8">
                                        <h3 class="hover:text-blue-600  font-bold ">
                                            <button type="submit" class="btn btn-primary">Потврди позајмицу!</button>
                                        </h3>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($reservedLoans->isNotEmpty())
            <div class="grid lg:grid-cols-1 gap-8 mt-6">
                <h1 class="font-bold text-xl text-left text-blue-500"> РЕЗЕРВАЦИЈЕ </h1>
            </div>
            <table class="table">
                <thead class="text-justify">
                    <tr>
                        <th scope="col">Библиотека</th>
                        <th scope="col">--</th>
                        <th scope="col">Брoj Наслов / Аутор</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservedLoans as $loansBook)
                        <tr class="text-justify">
                            <td>
                                <h3 class="hover:text-blue-600  font-bold ">
                                    <a href="/users/{{ $loansBook->library->id }}"
                                        target="_blank">{{ $loansBook->library->name }}</a>
                                </h3>
                            </td>
                            <td class="font-bold text-green-500"> {{ $loansBook->book->id }} </td>
                            <td> {{ $loansBook->book->title }} /
                                {{ $loansBook->book->author_fname }}{{ $loansBook->book->author_lname }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h1 class="font-bold text-xl text-left text-blue-500"> НЕМАТЕ РЕЗЕРВАЦИЈА </h1>
        @endif


        @if ($loansNumber > 0)
            <div class="grid lg:grid-cols-1 gap-8 mt-6">
                <h1 class="font-bold text-xl text-left text-blue-500"> КЊИГЕ НА ЧИТАЊУ ({{ $loansNumber }})</h1>
            </div>
            <x-forms.divider />
            <table class="table">
                <thead class="text-justify">
                    <tr>
                        <th scope="col">Наслов / Аутор / Број књиге</th>
                        <th scope="col">Рок за враћање</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeLoans as $loansBook)
                        <tr class="text-justify">
                            <td> {{ $loansBook->book->title }} / {{ $loansBook->book->author_fname }}
                                {{ $loansBook->book->author_lname }} / {{ $loansBook->book->id }}</td>
                            <td class="font-bold text-red-500">
                                {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.') }}-</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h1 class="font-bold text-xl text-left text-blue-500"> Немате књига на читању! </h1>
        @endif
        <x-forms.divider />


        @if ($overLoansCount > 0)
            <div class="grid lg:grid-cols-1 gap-8 mt-6">
                <h1 class="font-bold text-xl text-left text-blue-500"> ПРОЧИТАНЕ КЊИГЕ ( {{ $overLoansCount }} )</h1>
            </div>
            <x-forms.divider />
            <table class="table">
                <thead>
                    <tr class="text-justify">
                        <th scope="col">Наслов / Аутор </th>
                        <th scope="col"> Број књиге</th>
                        <th scope="col">БИБЛИОТЕКА</th>
                        <th scope="col">Вријеме позајмице</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($overLoans as $loansBooks)
                        <tr class="text-justify">
                            <td class="font-bold  text-red-200">
                                {{ $loansBooks->book->title }} / {{ $loansBooks->book->author_fname }}
                                {{ $loansBooks->book->author_lname }}
                            </td>
                            <td class="font-bold text-green-500"> {{ $loansBooks->book->id }} </td>
                            <td>
                                <h3 class="hover:text-blue-600  font-bold ">
                                    <a href="/users/{{ $loansBooks->library->id }}"
                                        target="_blank">{{ $loansBooks->library->name }}</a>
                                </h3>
                            </td>
                            <td>{{ $loansBooks->created_at->format('d. m. Y. ') }} - </td>

                            <td> {{ $loansBooks->updated_at->format('d. m. Y. ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h1 class="font-bold text-xl text-left text-blue-500"> НЕМАТЕ ПРОЧИТАНИХ КЊИГA </h1>
        @endif

        <div>{{ $loans->links() }}</div>
    </div>
</x-layout>
