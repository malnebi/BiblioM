<x-layout>
    <div class="space-y-10">
        <section class="text-center">
            <h1 class="font-bold text-4xl">ЧЛАН {{ $user->name }}</h1>

            <div class="grid lg:grid-cols-2 gap-8 mt-6">
                <h1 class="font-bold text-xl text-left"> Укупан број позајмљених књига је {{ $allLoansCount }}. </h1>
            </div>
            <x-forms.divider />

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
                        <tr class="text-center">

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
                                @if ($loansBook->active == 1)
                                    Чека се корисничка потврда о преузимању књиге (потпис)
                                @else
                                    <form method="POST" action="/loans/loanLibraryConfirm/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">
                                            <h3 class="hover:text-blue-600  font-bold ">
                                                <button type="submit" class="btn btn-primary">Позајми</button>
                                            </h3>
                                        </div>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="grid lg:grid-cols-1 gap-8 mt-6">
                <h1 class="font-bold text-xl text-left text-blue-500">КЊИГЕ НА ЧИТАЊУ ({{ $activeLoansCount }}):</h1>
            </div>
            <x-forms.divider />
            <table class="table">
                <thead>
                    <tr>
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
                        @if ($loansBook->active == 1 && $loansBook->description == 'potpisano')
                            <tr class="text-center">
                                <td class="font-bold text-green-500"> {{ $loansBook->book->id }} </td>

                                <td> {{ $loansBook->book->title }} / {{ $loansBook->book->author_fname }}
                                    {{ $loansBook->book->author_lname }}</td>

                                <td class="font-bold text-red-500">
                                    {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.') }}-</td>
                                <td>
                                    @if ($loansBook->description == null)
                                        Додијељено члану - чека се потврда о преузимању
                                    @else
                                        <form method="POST" action="/loans/{{ $loansBook->id }}">
                                            @csrf
                                            {{ method_field('PUT') }}
                                            <div class="col-md-8">
                                                <h3 class="hover:text-blue-600  font-bold ">
                                                    <button type="submit" class="btn btn-primary">Врати књигу!</button>
                                                </h3>
                                            </div>
                                        </form>

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
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
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

    </div>

</x-layout>
