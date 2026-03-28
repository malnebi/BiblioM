<x-layout>
    <div class="container mx-auto px-4 py-10 max-w-5xl">
        @if ($errors->any())
            <div class="bg-red-500/20 border border-red-500 text-red-200 p-4 rounded-xl mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1 class="text-3xl font-bold text-white text-center mb-12 font-cyrillic uppercase tracking-tight">Регистрација
        </h1>

        <x-forms.form action="/register" method="POST" enctype="multipart/form-data">

            {{-- --- ГОРЊИ ОДЈЕЉАК: ПОДАЦИ О КОРИСНИКУ --- --}}
            <x-forms.section title="Подаци о кориснику">
                <x-slot:left>
                    <div class="flex items-start gap-6">
                        {{-- Слика корисника --}}
                        <div class="flex-none">
                            <label for="user_photo_input" class="cursor-pointer group block">
                                <div id="user_photo_preview"
                                    class="w-32 h-32 bg-slate-800 rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-slate-600 group-hover:border-blue-500 transition-all overflow-hidden">
                                    <span
                                        class="text-slate-500 text-[10px] font-bold text-center px-2 uppercase font-cyrillic">Додај
                                        слику</span>
                                </div>
                                <input type="file" name="user_photo" id="user_photo_input" class="hidden"
                                    accept="image/*">
                            </label>
                        </div>

                        {{-- Име и Презиме --}}
                        <div class="flex-grow space-y-4">
                            <x-forms.input label="Име" name="name" required />
                            <x-forms.input label="Презиме" name="last_name" required />
                        </div>
                    </div>

                    <x-forms.input label="Имејл адреса" name="email" type="email" required />
                </x-slot:left>

                <x-slot:right>
                    <div class="space-y-4">
                        <x-forms.input label="Лозинка" name="password" type="password" required />
                        <x-forms.input label="Потврди лозинку" name="password_confirmation" type="password" required />

                        {{-- Улога у школи --}}
                        <div class="pt-2">
                            <label for="role_type"
                                class="block text-sm font-medium text-slate-300 mb-2 font-cyrillic uppercase tracking-wide">Улога
                                у школи</label>
                            <select name="role_type" id="role_type" class="custom-select w-full">
                                <option value="" disabled selected>Изаберите улогу</option>
                                @foreach (array_keys(\App\Models\User::$schoolRoles) as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Динамички део --}}
                        <div id="details_container"
                            class="mt-4 p-4 bg-slate-800/30 rounded-lg hidden border border-slate-700/50">
                            <div id="select_wrapper" class="hidden">
                                <label for="role_details_select"
                                    class="block text-sm font-medium text-slate-300 mb-2 font-cyrillic">Разред и
                                    одјељење</label>
                                <select name="role_details_select" id="role_details_select"
                                    class="custom-select w-full"></select>
                            </div>
                            <div id="input_wrapper" class="hidden">
                                <x-forms.input label="Наведите предмет/позицију" name="role_details_input"
                                    id="role_details_input" />
                            </div>
                        </div>
                    </div>
                </x-slot:right>
            </x-forms.section>

            <div class="h-10"></div> {{-- Размак између секција --}}

            {{-- --- ДОЊИ ОДЈЕЉАК: ПОДАЦИ О БИБЛИОТЕЦИ --- --}}
            <x-forms.section title="Подаци о библиотеци">
                <x-slot:left>
                    <div class="flex items-center gap-6">
                        {{-- Лого библиотеке --}}
                        <div class="flex-none">
                            <label for="logo_input" class="cursor-pointer group block">
                                <div id="logo_preview"
                                    class="w-32 h-32 bg-slate-800 rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-slate-600 group-hover:border-blue-500 transition-all overflow-hidden">
                                    <span
                                        class="text-slate-500 text-[10px] font-bold text-center px-2 uppercase font-cyrillic">Додај
                                        лого</span>
                                </div>
                                <input type="file" name="logo" id="logo_input" class="hidden" accept="image/*">
                            </label>
                        </div>

                        <div class="flex-grow">
                            <x-forms.input label="Назив библиотеке" name="library" required />
                        </div>
                    </div>
                </x-slot:left>

                <x-slot:right>
                    <div class="flex flex-col items-center justify-center h-full space-y-6 pt-6 lg:pt-0">
                        <x-forms.button type="submit">
                            Региструј се
                        </x-forms.button>

                        <p class="text-slate-400 font-cyrillic text-sm">
                            Имате профил? <a href="/login"
                                class="text-blue-400 hover:text-blue-300 hover:underline transition-colors">Пријава</a>
                        </p>
                    </div>
                </x-slot:right>
            </x-forms.section>

        </x-forms.form>
    </div>

    <script>
        const rolesData = @json(\App\Models\User::$schoolRoles);

        // Preview логика (унапређена да уклања текст)
        function setupPreview(inputId, previewId) {
            document.getElementById(inputId).addEventListener('change', function(e) {
                const reader = new FileReader();
                const preview = document.getElementById(previewId);
                reader.onload = (event) => {
                    preview.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(e.target.files[0]);
            });
        }
        setupPreview('user_photo_input', 'user_photo_preview');
        setupPreview('logo_input', 'logo_preview');

        // Динамичке улоге
        const roleType = document.getElementById('role_type');
        const detailsCont = document.getElementById('details_container');
        const selWrap = document.getElementById('select_wrapper');
        const inpWrap = document.getElementById('input_wrapper');
        const subSel = document.getElementById('role_details_select');

        roleType.addEventListener('change', function() {
            const val = this.value;
            const subs = rolesData[val];
            detailsCont.classList.remove('hidden');

            if (val === 'Ученик') {
                selWrap.classList.remove('hidden');
                inpWrap.classList.add('hidden');
                subSel.innerHTML = '';
                subs.forEach(s => subSel.add(new Option(s, s)));
            } else if (val === 'Професор' || val === 'Стручни сарадник') {
                selWrap.classList.add('hidden');
                inpWrap.classList.remove('hidden');
            } else {
                detailsCont.classList.add('hidden');
            }
        });
    </script>
</x-layout>
