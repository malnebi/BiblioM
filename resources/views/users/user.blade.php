<x-layout>
    <div class="space-y-10">
        <section class="text-left">
            <h1 class="font-bold text-4xl"> {{ $user->name }}</h1>

            <x-forms.divider />


            @if ($inProgressLoans->isNotEmpty())
                <div class="grid lg:grid-cols-1 gap-8 mt-6">
                    <h1 class="font-bold text-xl text-left text-blue-500"> ПРЕУЗИМАЊЕ У ТОКУ </h1>
                </div>
                <table class="table">
                    <thead>
                        <tr class="text-left">
                            <th scope="col">Библиотека</th>
                            <th scope="col">--</th>
                            <th scope="col">Брoj Наслов / Аутор</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inProgressLoans as $loansBook)
                            <tr class="text-left">
                                <td>
                                    <h3 class=" font-bold ">
                                        {{ $loansBook->library->name }}
                                    </h3>
                                </td>
                                <td class="font-bold text-green-500"> {{ $loansBook->book->id }} </td>
                                <td> {{ $loansBook->book->title }} / {{ $loansBook->book->author_fname }}
                                    {{ $loansBook->book->author_lname }}</td>
                                <td>
                                    @if ($loansBook->active == 1)
                                        Чека се потврда корисника о преузимању (потврду корисника и предају књиге вршите
                                        истовремено).
                                    @endif
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
                    <thead>
                        <tr>
                            <th scope="col">--- </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservedLoans as $loansBook)
                            <tr class="text-left">

                                <td>
                                    <h3 class="hover:text-blue-600  font-bold ">
                                        <a href="/users/{{ $loansBook->library->id }}" target="_blank">
                                            {{ $loansBook->library->name }}</a>
                                    </h3>
                                </td>
                                <td> Књига broj {{ $loansBook->book->id }} {{ $loansBook->book->title }} /
                                    {{ $loansBook->book->author_fname }}
                                    {{ $loansBook->book->author_lname }}</td>
                                <td>
                                    <form method="POST" action="/loans/loanLibraryConfirm/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">
                                            <h3 class="hover:text-blue-600  font-bold ">
                                                <button type="submit" class="btn btn-primary">Позајми</button>
                                            </h3>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($activeLoans->isNotEmpty())
                <div class="grid lg:grid-cols-1 gap-8 mt-6">
                    <h1 class="font-bold text-xl text-left text-blue-500">КЊИГЕ НА ЧИТАЊУ ({{ $activeLoansCount }}):
                    </h1>
                </div>
                <x-forms.divider />
                <table class="table">
                    <thead>
                        <tr class="text-left">
                            <th scope="col">Брoj</th>
                            <th scope="col">Наслов / Аутор</th>
                            <th scope="col">Рок за враћање</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activeLoans as $loansBook)
                            <tr class="text-left">
                                <td class="font-bold text-green-500"> {{ $loansBook->book->id }} </td>

                                <td> {{ $loansBook->book->title }} / {{ $loansBook->book->author_fname }}
                                    {{ $loansBook->book->author_lname }}</td>

                                <td class="font-bold text-red-500">
                                    {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.') }}-
                                </td>
                                <td>
                                    <form method="POST" action="/loans/returnBookLibraryConfirm/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">
                                            <h3 class="hover:text-green-600  font-bold ">
                                                <button type="submit" class="btn btn-primary">Потврди враћање
                                                    књиге!</button>
                                            </h3>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="/loans/extend/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">
                                            <h3 class="hover:text-blue-400  font-bold ">
                                                <button type="submit" class="btn btn-primary">Продужи рок за
                                                    враћање!</button>
                                            </h3>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>

        <section>
            @if ($activeLoansCount <= 1)
                <x-forms.form method="POST" action="/loans" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <x-forms.select label="Позајми књигу" name="book_id">
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}" selected> {{ $book->id }} {{ $book->title }}
                                / {{ $book->author_fname }} {{ $book->author_lname }} </option>
                        @endforeach
                    </x-forms.select>
                    <x-forms.button>Позајми на 30 дана</x-forms.button>

                </x-forms.form>
            @endif
        </section>

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
<x-forms.divider />


</x-layout>
