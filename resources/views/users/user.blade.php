<x-layout>
    <div class="space-y-10">
        <section class="text-center">
            <h1 class="font-bold text-4xl">КОРИСНИК {{ $user->name }}</h1>

            <div class="grid lg:grid-cols-2 gap-8 mt-6">
                <h1 class="font-bold text-xl text-left"> Укупан број позајмљених књига је {{ $allLoansNumber }}. </h1>
            </div>
            <x-forms.divider />

            @if ($loansNumber > 0)
                <div class="grid lg:grid-cols-1 gap-8 mt-6">
                    <h1 class="font-bold text-xl text-left text-blue-500">Књиге на читању ({{ $loansNumber }}):</h1>
                </div>
                <x-forms.divider />

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Бр. књиге</th>
                            <th scope="col">Наслов / Аутор</th>
                            <th scope="col">Рок за враћање</th>
                            <th scope="col">Додатне опције</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($loansBooks as $loansBook)
                            <tr class="text-center">
                                <td class="font-bold text-green-500"> {{ $loansBook->book->id }} </td>

                                <td> {{ $loansBook->book->title }} / {{ $loansBook->book->author_fname }}
                                    {{ $loansBook->book->author_lname }}</td>

                                <td class="font-bold text-red-500"> {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.')}}-</td>

                                <td>
                                    <form method="POST" action="/loans/{{ $loansBook->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">
                                            <h3 class="hover:text-blue-600  font-bold ">
                                                <button type="submit" class="btn btn-primary">Врати књигу!</button>
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
            <x-section-heading>
                @if ($loansNumber <= 1)
                    <h3 class="hover:text-blue-800  font-bold ">
                        <a href="/loans/create/{{ $user->id }}" class="btn btn-success">Позајми књигу</a>
                    </h3>
                @endif
            </x-section-heading>
        </section>
    </div>
</x-layout>
