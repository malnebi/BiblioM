<x-layout>

    <x-page-heading>Измјена података о књизи</x-page-heading>

    <x-forms.form method="POST" action="/books/{{ $book->id }}" enctype="multipart/form-data">
        @csrf
        {{ method_field('PUT') }}
        {{-- ГОРЊИ ДИО: Основни подаци --}}
        <x-forms.section title="Подаци о књизи">
            {{-- Лијева страна --}}
            <x-slot:left>
                <div class="flex gap-6 items-start">
                    {{-- Фотографија књиге са Preview функцијом --}}
                    <div class="shrink-0">
                        <label for="book_cover_input" class="cursor-pointer group block">
                            <div id="book_cover_preview"
                                class="w-32 h-44 bg-[#1e293b] border-2 border-dashed border-slate-600 rounded-lg flex flex-col items-center justify-center group-hover:border-blue-500 transition-all overflow-hidden">

                                {{-- Ако књига већ има слику, приказујемо је одмах --}}
                                @if ($book->book_cover)
                                    <img src="{{ asset('storage/' . $book->book_cover) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span id="placeholder-text"
                                        class="text-slate-500 text-[10px] font-bold text-center px-2 uppercase">
                                        Кликни за слику
                                    </span>
                                @endif
                            </div>

                            {{-- Инпут са ID-јем који ће JS пратити --}}
                            <input type="file" id="book_cover_input" name="book_cover" class="hidden"
                                accept="image/*">
                        </label>
                    </div>
                    {{-- Име и презиме аутора --}}
                    <div class="flex-1 space-y-4">
                        <x-forms.input label="Име аутора" name="author_fname" value="{{ $book->author_fname }}" />
                        <x-forms.input label="Презиме аутора" name="author_lname" value="{{ $book->author_lname }}" />
                    </div>
                </div>
                <x-forms.input label="Инвентарски број" name="lib_book_id" type="number" min="1"
                    value="{{ $book->lib_book_id }}" required
                    @if ($book->library->numbering_mode === 'automatic') readonly @endif />
                <x-forms.input label="Наслов књиге" name="title" value="{{ $book->title }}" />
            </x-slot:left>

            {{-- Десна страна --}}
            <x-slot:right>
                <div class="space-y-4">
                    <x-forms.input label="Издавач" name="publisher_name" value="{{ $book->publisher_name }}" />
                    <x-forms.input label="Мјесто издавања" name="publisher_place"
                        value="{{ $book->publisher_place }}" />
                    <x-forms.input label="Година" name="year" value="{{ $book->year }}" />

                    <div class="pt-2">
                        <button type="button"
                            onclick="document.getElementById('extra-fields').classList.toggle('hidden')"
                            class="text-blue-400 text-sm hover:underline italic">
                            + Поља за проширедн опис... (опција у изради)
                        </button>
                        <div id="extra-fields" class="hidden mt-4 space-y-4 border-t border-slate-800 pt-4">
                            <x-forms.input label="ISBN број" name="isbn" />
                            <x-forms.input label="Број страница" name="pages" type="number" />
                        </div>
                    </div>
                </div>
            </x-slot:right>
        </x-forms.section>

        {{-- ДОЊИ ДИО: Ознаке и слање --}}
        <x-forms.section title="Описне ознаке">
            <x-slot:left>

                {{-- Користимо класу 'custom-select' коју већ имаш у app.css --}}
                <select name="tag1" class="custom-select w-full">
                    <option value="" disabled selected>Изаберите прву ознаку</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>

                <select name="tag2" class="custom-select w-full">
                    <option value="" disabled selected>Изаберите другу ознаку</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>

                <select name="tag3" class="custom-select w-full">
                    <option value="" disabled selected>Изаберите трећу ознаку</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </x-slot:left>

            {{-- Десна страна: Нова ознака и заобљено дугме --}}
            <x-slot:right>
                <div>
                    <x-forms.input label="Предложи нову ознаку (опција у изради)" name="suggested_tag"
                        placeholder="Упишите нову ознаку... (опција у изради)" />
                    <p class="text-[11px] text-gray-500 mt-2 italic">
                        * Ваше предложене ознаке ће постати видљиве након одобрења администратора.
                    </p>
                </div>

                <div class="flex justify-center">
                    <x-forms.button type="submit">
                        Ажурирај податке о књиги
                    </x-forms.button>
                </div>
            </x-slot:right>

        </x-forms.section>

    </x-forms.form>

    {{-- 2. JavaScript за Preview слике --}}
    <script>
        function setupPreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            if (input && preview) {
                input.addEventListener('change', function(e) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        // Ово мења постојећу слику (или текст) новом изабраном сликом
                        preview.innerHTML =
                            `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                        preview.classList.remove('border-dashed');
                        preview.classList.add('border-solid', 'border-blue-500');
                    };
                    if (e.target.files[0]) {
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }
        }

        // Само позовемо функцију са одговарајућим ID-јевима
        setupPreview('book_cover_input', 'book_cover_preview');
    </script>
</x-layout>
