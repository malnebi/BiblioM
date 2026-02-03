<x-layout>
    <div>
        <x-book-card-wide :$book />
    </div>

    @auth
        @if ($book->library->owner_id == Auth::id())
            {{-- $book->library->owner_id   //direktno pristupa koloni owner_id u tabeli libraries  
     $book->library->owner->id  //dobija vrijednost atributa id iz instance modela User koji je primarni ključ modela  --}}
            <x-panel class="flex gap-x-6">

                <div class="container">


                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            СТАТУС КЊИГЕ: {{ $book->loan == 1 ? 'Књига је код корисника' : 'На полици' }}
                            @if ($book->loan == 1)
                                <a href="/users/{{ $book->lib_user_id }}" class="btn btn-info"
                                    target="_blank">{{ $book->userBorrows->name }}</a>
                            @endif
                        </div>
                    </div>
                    <x-forms.divider />
                    <a href="/books/{{ $book->id }}/edit" class="btn btn-info">ПРОМЈЕНА ПОДАТАКА</a>
                    <x-forms.divider />
                    <button form="delete-form" class="text-red-500 text-sm font-bold leading-6"> БРИСАЊЕ КЊИГЕ </button>

                </div>

                <form method="POST" action="/books/{{ $book->id }}" id="delete-form" class="hidden" id="delete-form">
                    @csrf
                    @method('DELETE')

                    <script>
                        document.getElementById('delete-form').addEventListener('submit', function(event) {
                            event.preventDefault();
                            if (confirm('Are you sure you want to delete this book?')) {
                                this.submit();
                            }
                        });
                    </script>
                </form>

                </div>
            </x-panel>
        @endif
    @endauth


    <div class="space-y-2">
        <div class="border border-blue-300 rounded-lg bg-[#fdfaf5] px-6 py-2 text-sm text-black">
            <div class="grid grid-cols-3 font-semibold pb-1 mb-1 text-xs">
                @if ($book->library->owner_id != Auth::id())

                    @if ($book->loans->where('description', 'rezervisano')->where('user_id', Auth::id())->first())
                        <div class="text-orange-500 font-bold"> Ово је ваша резервација. </div>
                    @else
                        <form method="POST" action="/bookReservation/{{ $book->id }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary text-green-950 hover:text-blue-600">РЕЗЕРВИШИ</button>
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-layout>
