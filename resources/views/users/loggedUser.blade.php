<x-layout>
    <div class="space-y-10">
        <section class="text-center">
            <h1 class="font-bold text-4xl"> Мој профил</h1>
        </section>

        {{-- ================== ПРЕУЗИМАЊЕ У ТОКУ ================== --}}
        @if ($inProgressLoans->isNotEmpty())
            <x-collapsible-section title="МОЈЕ ПРЕУЗИМАЊЕ КЊИГЕ" :count="count($inProgressLoans)">
                @foreach ($inProgressLoans as $loansBook)
                    <x-book-card-wide :book="$loansBook->book" />
                    <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                        <form method="POST" action="/loans/loanBookMemberConfirm/{{ $loansBook->id }}">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="col-md-8">
                                <h3 class="hover:text-blue-600  font-bold ">
                                    <button type="submit" class="btn btn-primary">Потврди позајмицу!</button>
                                </h3>
                            </div>
                        </form>
                    </div>
                @endforeach
            </x-collapsible-section>
        @endif

        {{-- ================== РЕЗЕРВАЦИЈЕ ================== --}}
        @if ($reservedLoans->isNotEmpty())
            <x-collapsible-section title="МОЈЕ РЕЗЕРВАЦИЈЕ" :count="count($reservedLoans)">
               
                <x-slot name="visible">
                    @foreach ($reservedLoans->take(2) as $loansBook)
                        <x-book-card-wide :book="$loansBook->book" />
                        <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                            @if ($loansBook->book->loans->where('description', 'rezervisano')->where('user_id', Auth::id())->first())
                                Резервисали сте књигу {{ $loansBook->created_at->format('d. m. Y. ') }}
                            @endif
                            @if ($loansBook->book->loans->where('description', 'potpisano')->where('user_id', Auth::id())->first())
                                Сачекајте да се књига врати у библиотеку да бисте могли да је позајмите.
                            @endif
                        </div>
                    @endforeach
                </x-slot>
                {{-- Све испод овога аутоматски иде у главни $slot --}}
                @foreach ($reservedLoans as $loansBook)
                    <x-book-card-wide :book="$loansBook->book" />
                    <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                        @if ($loansBook->book->loans->where('description', 'rezervisano')->where('user_id', Auth::id())->first())
                            Резервисали сте књигу {{ $loansBook->created_at->format('d. m. Y. ') }}
                        @endif
                        @if ($loansBook->book->loans->where('description', 'potpisano')->where('user_id', Auth::id())->first())
                            Сачекајте да се књига врати у библиотеку да бисте могли да је позајмите.
                        @endif
                    </div>
                @endforeach
            </x-collapsible-section>
        @else
            <x-section-heading> НЕМАТЕ РЕЗЕРВАЦИЈА </x-section-heading>
        @endif

        {{-- ================== КЊИГЕ НА ЧИТАЊУ ================== --}}
        @if ($loansNumber > 0)
            <x-collapsible-section title="МОЈЕ КЊИГЕ НА ЧИТАЊУ" :count="$loansNumber">

                {{-- ОВО ЈЕ КЉУЧНИ ДИО: Шаљемо податке у 'visible' слот --}}
                <x-slot name="visible">
                    @foreach ($activeLoans->take(2) as $loansBook)
                        <x-book-card-wide :book="$loansBook->book" />
                    @endforeach
                </x-slot>
                {{-- Све испод овога аутоматски иде у главни $slot --}}

                @foreach ($activeLoans->skip(2) as $loansBook)
                    <x-book-card-wide :book="$loansBook->book" />
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
                        <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black mt-2">
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
        <x-forms.divider />
    </div>
</x-layout>
