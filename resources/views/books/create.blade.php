<x-layout>
    <div class="space-y-10 max-w-5xl mx-auto">
        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-200 p-4 rounded-xl mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1 class="font-bold text-center text-4xl mb-10 text-white">ДОДАЈ КЊИГУ</h1>
        <x-forms.form action="/books" method="POST" enctype="multipart/form-data">

            {{-- ГОРЊИ ДИО: Основни подаци --}}
            <x-forms.section title="Подаци о књизи">
                {{-- Лијева страна --}}
                <x-slot:left>
                    <div class="flex gap-6 items-start">
                        {{-- Фотографија књиге са Preview функцијом --}}
                        <div class="shrink-0">
                            <label for="book_image" class="cursor-pointer group">
                                <div id="image-preview-container"
                                    class="w-32 h-44 bg-[#1e293b] border-2 border-dashed border-slate-600 rounded-lg flex flex-col items-center justify-center group-hover:border-blue-500 transition-all overflow-hidden">
                                    <img id="image-preview" src="#" alt="Preview"
                                        class="hidden w-full h-full object-cover">
                                    <div id="upload-placeholder" class="flex flex-col items-center">
                                        <span class="text-[10px] text-gray-400 mt-2">ДОДАЈ СЛИКУ</span>
                                    </div>
                                </div>
                                <input type="file" id="book_image" name="book_image" class="hidden"
                                    onchange="previewFile()">
                            </label>
                        </div>

                        {{-- Име и презиме аутора --}}
                        <div class="flex-1 space-y-4">
                            <x-forms.input label="Име аутора" name="author_fname" placeholder="нпр. Иво" />
                            <x-forms.input label="Презиме аутора" name="author_lname" placeholder="нпр. Андрић" />
                        </div>
                    </div>
                    <x-forms.input label="Наслов књиге" name="title" placeholder="Унесите пуни наслов..." />
                </x-slot:left>

                {{-- Десна страна --}}
                <x-slot:right>
                    <div class="space-y-4">
                        <x-forms.input label="Издавач" name="publisher_name" />
                        <x-forms.input label="Мјесто издавања" name="publisher_place" />
                        <x-forms.input label="Година" name="year" />

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
                            САЧУВАЈ КЊИГУ
                        </x-forms.button>
                    </div>
                </x-slot:right>

            </x-forms.section>
        </x-forms.form>
    </div>

    {{-- 2. JavaScript за Preview слике --}}
    <script>
        function previewFile() {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const file = document.querySelector('input[type=file]').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function() {
                preview.src = reader.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-layout>
