<x-layout>
    {{-- 🔵 ИЗМИЈЕЊЕНО: мањи глобални размак --}}
    <div class="space-y-6">

        {{-- ===== HEADER ===== --}}
        <section class="text-center">
            <div class="flex flex-col items-left">
                <x-library-logo :library="$library ?? 'No library'" width="100" />
                <h1 class="font-bold text-4xl">БИБЛИОТЕКА {{ $library->name }}</h1>
            </div>
        </section>

        {{-- ================== ПРЕУЗИМАЊЕ У ТОКУ ================== --}}
        @if ($groupedProgressLoans->isNotEmpty())
            <h1 class="font-bold text-xl text-left text-blue-500">ПРЕУЗИМАЊЕ У ТОКУ</h1>

            {{-- 🔵 ИЗМИЈЕЊЕНО --}}
            <div class="space-y-2">
                @foreach ($groupedProgressLoans as $bookId => $loans)
                    <x-book-card-wide :book="$loans->first()->book" />

                    {{-- 🔵 ЈЕДИНСТВЕН КОНТЕЈНЕР --}}
                    <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                        @foreach ($loans as $loan)
                            <form method="POST" action="/loans/loanLibraryConfirm/{{ $loan->id }}">
                                @csrf
                                @method('PUT')

                                {{-- ❌ УКЛОЊЕНО: непотребни wrapper-и --}}
                                {{-- 🔵 ИЗМИЈЕЊЕНО --}}
                                <button
                                    class="font-bold hover:text-blue-700">
                                    Потврди позајмицу члану {{ $loan->user->name }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ================== РЕЗЕРВАЦИЈЕ ================== --}}
        @if ($reservedLoans->isNotEmpty())
            <h1 class="font-bold text-xl text-left text-blue-500">РЕЗЕРВАЦИЈЕ</h1>

            <div class="space-y-2">
                @foreach ($groupedReservedLoans as $bookId => $loans)
                    <x-book-card-wide :book="$loans->first()->book" />

                    {{-- 🔵 ИСТИ СТИЛ КАО ОСТАЛО --}}
                    <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">

                        {{-- 🔵 ИЗМИЈЕЊЕНО --}}
                        <div class="grid grid-cols-2 font-semibold border-b pb-1 mb-1 text-xs">
                            <div>Члан</div>
                            <div>Наслов</div>
                        </div>

                        @foreach ($loans as $loan)
                            <div class="grid grid-cols-2 py-1">
                                <a href="/users/{{ $loan->user->id }}" class="hover:underline">
                                    {{ $loan->user->name }}
                                </a>
                                <div>{{ $loan->book->title }}</div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ================== НА ЧИТАЊУ ================== --}}
        @if ($loansNumber > 0)
            <h1 class="font-bold text-xl text-left text-blue-500">НА ЧИТАЊУ</h1>

            <div class="space-y-2">
                @foreach ($groupedActiveLoans as $bookId => $loans)
                    <x-book-card-wide :book="$loans->first()->book" />

                    <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">
                        @foreach ($loans as $loan)
                            <div class="leading-tight">
                                Књига је код корисника <strong>{{ $loan->user->name }}</strong>
                                од {{ $loan->created_at->format('d. m. Y.') }}.
                            </div>

                            <form method="POST" action="/loans/returnBookConfirm/{{ $loan->id }}">
                                @csrf
                                @method('PUT')

                                {{-- 🔵 ИЗМИЈЕЊЕНО --}}
                                <button class="font-bold hover:text-green-600 mt-1">
                                    Врати књигу
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ================== ПРОЧИТАНЕ КЊИГЕ ================== --}}
        @if ($overLoans->isNotEmpty())
            <h1 class="font-bold text-xl text-left text-blue-500">ПРОЧИТАНЕ КЊИГЕ</h1>

            <div class="space-y-2">
                @foreach ($groupedOverLoans as $bookId => $loans)
                    <x-book-card-wide :book="$loans->first()->book" />

                    <div class="border border-gray-300 rounded-lg bg-[#fdfaf5] px-4 py-2 text-sm text-black">

                        {{-- 🔵 СТАНДАРДНО ЗАГЛАВЉЕ --}}
                        <div class="grid grid-cols-3 font-semibold border-b pb-1 mb-1 text-xs">
                            <div>Датум позајмице</div>
                            <div>Члан</div>
                            <div>Датум враћања</div>
                        </div>

                        @foreach ($loans as $loan)
                            <div class="grid grid-cols-3 py-1">
                                <div>{{ $loan->created_at->format('d. m. Y.') }}</div>
                                <div>{{ $loan->user->name }}</div>
                                <div>{{ $loan->updated_at->format('d. m. Y.') }}</div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
