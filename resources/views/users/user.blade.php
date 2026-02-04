<x-layout>
    <div class="space-y-10">
        <section class="text-left">
            <h1 class="font-bold text-4xl"> {{ $user->name }}</h1>

            {{-- ================== ФОРМУЛАР ЗА ДОДЈЕЛУ КЊИГЕ ЧЛАНУ ================== --}}
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

            {{-- ================== ПРЕУЗИМАЊЕ У ТОКУ  ================== --}}
            @if ($inProgressLoans->isNotEmpty())
                <x-collapsible-section id="sekcija-preuzimanje" title="МОЈЕ ПРЕУЗИМАЊЕ КЊИГЕ" :count="count($inProgressLoans)">
                    <x-slot name="visible">
                        @foreach ($inProgressLoans->take(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                                @if ($loansBook->active == 1)
                                    ПРЕПОРУКА: Са потписом корисника - потврдом о преузимању изврши и предају књиге
                                @endif
                            </div>
                        @endforeach
                    </x-slot>
                        @foreach ($inProgressLoans->skip(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                                @if ($loansBook->active == 1)
                                    ПРЕПОРУКА: Са потписом корисника - потврдом о преузимању изврши и предају књиге
                                @endif
                            </div>
                        @endforeach             
                </x-collapsible-section>
            @endif

            {{-- ================== РЕЗЕРВАЦИЈЕ ================== --}}
            @if ($reservedLoans->isNotEmpty())
                <x-collapsible-section id="sekcija-rezervacije" title="РЕЗЕРВАЦИЈЕ" :count="count($reservedLoans)">
                    <x-slot name="visible">
                        @foreach ($reservedLoans->take(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                                <form method="POST" action="/loans/loanLibraryConfirm/{{ $loansBook->id }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="col-md-8">
                                        <h3 class="hover:text-blue-600  font-bold ">
                                            <button type="submit" class="btn btn-primary">Позајми
                                                {{ $user->name }}</button>
                                        </h3>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </x-slot>
                        @foreach ($reservedLoans->skip(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                                <form method="POST" action="/loans/loanLibraryConfirm/{{ $loansBook->id }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="col-md-8">
                                        <h3 class="hover:text-blue-600  font-bold ">
                                            <button type="submit" class="btn btn-primary">Позајми
                                                {{ $user->name }}</button>
                                        </h3>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                </x-collapsible-section>
            @endif


            {{-- ================== KЊИГЕ НА ЧИТАЊУ ================== --}}
            @if ($activeLoans->isNotEmpty())
                <x-collapsible-section id="sekcija-aktivne" title="KЊИГЕ НА ЧИТАЊУ" :count="$activeLoansCount">
                    <x-slot name="visible">
                        @foreach ($activeLoans->take(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div
                                class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black mt-2">
                                Рок за враћање је
                                {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.') }}
                                <form method="POST" action="/loans/returnBookLibraryConfirm/{{ $loansBook->id }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="col-md-8">
                                        <h3 class="hover:text-green-600  font-bold ">
                                            <button type="submit" class="btn btn-primary">Врати књигу на
                                                полицу!</button>
                                        </h3>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </x-slot>
                        @foreach ($activeLoans->skip(2) as $loansBook)
                            <x-book-card-wide :book="$loansBook->book" />
                            <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                                Рок за враћање је
                                {{ \Carbon\Carbon::parse($loansBook->return_deadline)->format('d. m. Y.') }}

                                <form method="POST" action="/loans/returnBookLibraryConfirm/{{ $loansBook->id }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="col-md-8">
                                        <h3 class="hover:text-green-600  font-bold ">
                                            <button type="submit" class="btn btn-primary">Врати књигу на
                                                полицу!</button>
                                        </h3>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                </x-collapsible-section>
            @endif
            <x-forms.divider />

            {{-- ================== ПРОЧИТАНЕ КЊИГЕ ================== --}}
            @if ($overLoansCount > 0)
                <x-collapsible-section id="sekcija-procitane" title="ПРОЧИТАНЕ КЊИГЕ" :count="$overLoansCount">

                    {{-- ОВО ЈЕ КЉУЧНИ ДИО: Шаљемо податке у 'visible' слот --}}
                    <x-slot name="visible">
                        @foreach ($overLoans->take(2) as $loansBooks)
                            <div class="mb-4">
                                <x-book-card-wide :book="$loansBooks->book" />
                                <div
                                    class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black mt-2">
                                    {{ $loansBooks->created_at->format('d. m. Y. ') }} -
                                    {{ $loansBooks->updated_at->format('d. m. Y. ') }}
                                </div>
                            </div>
                        @endforeach
                    </x-slot>

                    {{-- Све испод овога аутоматски иде у главни $slot --}}
                    @foreach ($overLoans->skip(2) as $loansBooks)
                        <div class="mb-6">
                            <x-book-card-wide :book="$loansBooks->book" />
                            <div
                                class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black mt-2">
                                {{ $loansBooks->created_at->format('d. m. Y. ') }} -
                                {{ $loansBooks->updated_at->format('d. m. Y. ') }}
                            </div>
                        </div>
                    @endforeach
                </x-collapsible-section>
            @else
                <x-section-heading> {{ $user->name }} НЕМА ПРОЧИТАНИХ КЊИГA </x-section-heading>
            @endif
            <x-forms.divider />
        </section>
    </div>
</x-layout>
