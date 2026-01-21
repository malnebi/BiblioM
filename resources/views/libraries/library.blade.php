<x-layout>
    <div class="space-y-10">
        <section class="text-center">
            <h1 class="font-bold text-4xl">БИБЛИОТЕКА {{ $library->name }}</h1>
        </section>
        <section>
            <div class="space-y-10">

                <section class="text-center">
                    <h1 class="font-bold text-4xl"> Колекција књига члана {{ $user->name }}.</h1>

                    <x-forms.form action="/search" class="mt-6">
                        <x-forms.input :label="false" name="q" placeholder="Наслов, аутор, година ..." />
                        {{-- <x-forms.button>Search</x-forms.button> --}}
                    </x-forms.form>
                </section>

                <section class="pt-6">
                    <x-section-heading>Истакнуто</x-section-heading>

                    <div class="grid lg:grid-cols-3 gap-8 mt-6">
                        @foreach ($featuredBooks as $book)
                            <x-book-card :$book />
                        @endforeach
                    </div>
                </section>
                <section>
                    <x-section-heading>Најновије књиге библиотеке</x-section-heading>
                    <div class="mt-6 space-y-6">
                        @foreach ($books as $book)
                            <x-book-card-wide :$book />
                        @endforeach
                    </div>
                </section>
            </div>

        </section>
    </div>

    <x-forms.divider/>

    <x-section-heading>ПОЗАЈМИЦЕ БИБЛИОТЕКЕ {{ Auth::user()->ownLibrary->name }} члану {{ $user->name }}</x-section-heading>
                    <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Број књиге</th>                        
                        <th scope="col">Наслов књиге / Аутор </th>
                        <th scope="col">Статус </th> 
                        <th scope="col">Позајмљена</th>
                        <th scope="col">Враћена</th>                       
                        
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($loans as $loan)
                        @if ($loan->user->id == $user->id)
                    <tr class="text-center">
                            <td> {{ $loan->book->id }} </td>
                            
                            <td class="font-bold  text-red-200" > 
                                 {{ $loan->book->title }} / {{ $loan->book->author_fname }} {{ $loan->book->author_lname}} 
                            </td>                             
                            
                            @if (($loan->active == 1 && $loan->description == null) || ($loan->active == 0 && $loan->description == 'potpisano'))  
                            <td> резервисана   </td> 
                            @endif
                            
                            @if ($loan->active == 1 && $loan->description == 'potpisano') 
                            <td> на читању   </td> 
                            @endif
                            
                            @if ($loan->active == 0 && $loan->description == null) 
                            <td> на полици  </td> 
                            @endif
                            
                            @if ($loan->active == 0 && $loan->description == null) 
                            <td>{{  ($loan->created_at)->format('d. m. Y. ') }}</td>
                            <td>{{  ($loan->updated_at)->format('d. m. Y. ') }}</td>
                            @endif

                            @if (($loan->active == 0 && $loan->description == 'potpisano') || ($loan->active == 1 && $loan->description == 'potpisano' ))                           
                            <td>{{  ($loan->created_at)->format('d. m. Y. ') }}</td>
                            @endif
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                <div> 
                    {{$loans->links()}}</div> 
            </div>


</x-layout>
