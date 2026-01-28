<x-layout>

    <h1 class="font-bold text-4xl"> БИБЛИОТЕКА {{ Auth::user()->ownLibrary->name }}</h1>

    <x-forms.divider />



    <div class="space-y-10">

        
        <section class="text-center"> РЕЗЕРВАЦИЈЕ</section>
        <div class="mt-6 space-y-6">
            <table class="table">
                <thead>
                    <tr class="text-left">
                        <th scope="col">Члан---</th>
                        <th scope="col">Књига</th>
                        <th scope="col">Датум резервације</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loans as $loan)
                        @if ($loan->active == null && $loan->description == 'rezervisano')
                            <tr class="text-left">
                                <td>
                                    <h3 class="hover:text-blue-600  font-bold ">
                                        <a href="/users/{{ $loan->user->id }}"
                                            target="_blank">{{ $loan->user->name }}</a>
                                    </h3>
                                </td>
                                <td class="font-bold  text-red-200">
                                    {{ $loan->book->id }} {{ $loan->book->title }} / {{ $loan->book->author_fname }}
                                </td>
                                <td>{{ $loan->created_at->format('d. m. Y. ') }}</td>
                                <td>
                                    <form method="POST" action="/loans/loanLibraryConfirm/{{ $loan->id }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="col-md-8">

                                            <h3 class="hover:text-blue-600  font-bold ">
                                                <button type="submit" class="btn btn-primary">Одобри позајмицу
                                                    koриснику {{ $loan->user->name }}
                                                    (само уколико корисник добио књигу)
                                                </button>
                                            </h3>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="space-y-10">
            <section class="text-center"> КЊИГЕ НА ЧИТАЊУ</section>
            <div class="mt-6 space-y-6">
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">Број позајмице ---</th>
                            <th scope="col">Члан</th>
                            <th scope="col">Књига </th>
                            <th scope="col">Рок за враћање</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            @if ($loan->active == 1 && $loan->description == 'potpisano')
                                <tr class="text-left">
                                    <td>{{ $loan->id }}</td>
                                    <td>
                                        <h3 class="hover:text-blue-600  font-bold ">
                                            <a href="/users/{{ $loan->user->id }}"
                                                target="_blank">{{ $loan->user->name }}</a>
                                        </h3>
                                    </td>
                                    <td class="font-bold  text-red-200">
                                        {{ $loan->book->id }} {{ $loan->book->title }} /
                                        {{ $loan->book->author_fname }}
                                    </td>
                                    <td>{{ $loan->updated_at->format('d. m. Y. ') }}</td>
                                </tr>
                            @endif
                            @if ($loan->active == 1 && $loan->description != 'potpisano')
                                <td> Чека се потврда корисника {{ $loan->user->name }} о преузимању ваше књиге
                                    {{ $loan->book->title }} /
                                    {{ $loan->book->author_fname }}, број {{ $loan->book->id }}</td>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div>{{ $loans->links() }}</div>
            </div>
        </div>

        <div class="space-y-10">
            <section class="text-center"> ЗАВРШЕНЕ ПОЗАЈМИЦЕ</section>
            <div class="mt-6 space-y-6">
                <table class="table">
                    <thead>
                        <tr class="text-left">
                            <th scope="col">Број позајмице ---</th>
                            <th scope="col">Члан --- </th>
                            <th scope="col">Књига </th>
                            <th scope="col">Датум </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            @if ($loan->active == 0)
                                <tr class="text-left">
                                    <td>{{ $loan->id }}</td>
                                    <td>
                                        <h3 class="hover:text-blue-600  font-bold ">
                                            <a href="/users/{{ $loan->user->id }}"
                                                target="_blank">{{ $loan->user->name }}</a>
                                        </h3>
                                    </td>
                                    <td class="font-bold  text-red-200">
                                        {{-- $loan->book->id }} {{ $loan->book->title }} /
                                        {{ $loan->book->author_fname --}}
                                    </td>
                                    <td>{{ $loan->created_at->format('d. m. Y. ') }} -
                                        {{ $loan->updated_at->format('d. m. Y. ') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div>{{ $loans->links() }}</div>
            </div>
        </div>


    </div>


</x-layout>
