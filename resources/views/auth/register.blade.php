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
            <div class="p-8 bg-[#0f172a]/50 border border-slate-700 rounded-2xl">
                <h2 class="font-bold text-blue-400 mb-8 text-lg uppercase tracking-wider"></h2>

                <div class="grid lg:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div class="flex items-start gap-6">
                            <div class="flex-none">
                                <label for="user_photo_input" class="relative cursor-pointer group block">
                                    <div id="user_photo_preview"
                                        class="w-32 h-32 bg-slate-800 rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-slate-600 group-hover:border-blue-500 transition-all overflow-hidden">
                                        <img src="{{ Vite::asset('resources/images/user-placeholder.svg') }}"
                                            alt="Подразумијевана слика корисника" class="w-full h-full object-cover">
                                    </div>
                                    <span
                                        class="absolute inset-x-2 bottom-2 rounded-md bg-black/65 px-2 py-1 text-center text-xs font-semibold text-white">Додај
                                        слику</span>
                                    <input type="file" name="user_photo" id="user_photo_input" class="hidden"
                                        accept="image/*">
                                </label>
                            </div>

                            <div class="flex-grow space-y-4">
                                <x-forms.input label="Име" name="name" required />
                                <x-forms.input label="Презиме" name="last_name" required />
                            </div>
                        </div>

                        <x-forms.input label="Имејл адреса" name="email" type="email" required />
                    </div>

                    <div class="space-y-4">
                        <x-forms.input label="Лозинка" name="password" type="password" required />
                        <x-forms.input label="Потврди лозинку" name="password_confirmation" type="password" required />

                        <div class="pt-2">
                            <label for="role_type"
                                class="block text-sm font-medium text-slate-300 mb-2 font-cyrillic uppercase tracking-wide">(У) ШКОЛИ САМ</label>
                            <select name="role_type" id="role_type" class="custom-select w-full">
                                <option value="" disabled @selected(old('role_type') === null)></option>
                                @foreach (array_keys(\App\Models\User::$schoolRoles) as $role)
                                    <option value="{{ $role }}" @selected(old('role_type') === $role)>{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="details_container"
                            class="mt-4 p-4 bg-slate-800/30 rounded-lg hidden border border-slate-700/50">
                            <div id="select_wrapper" class="hidden">
                                <label for="role_details_select"
                                    class="block text-sm font-medium text-slate-300 mb-2 font-cyrillic">Разред и
                                    одјељење</label>
                                <select name="role_details_select" id="role_details_select"
                                    class="custom-select w-full" disabled></select>
                            </div>
                            <div id="input_wrapper" class="hidden">
                                <x-forms.input label="Детаљи улоге" name="role_details_input"
                                    id="role_details_input" disabled />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 border-t border-slate-700 pt-8">
                    <h2 class="font-bold text-blue-400 mb-6 text-lg uppercase tracking-wider">Моја библиотека</h2>
                    <div class="grid lg:grid-cols-2 gap-8 items-start">
                        <div class="flex items-center gap-6">
                            <div class="flex-none">
                                <label for="logo_input" class="relative cursor-pointer group block">
                                    <div id="logo_preview"
                                        class="w-32 h-32 bg-slate-800 rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-slate-600 group-hover:border-blue-500 transition-all overflow-hidden">
                                        <img src="{{ Vite::asset('resources/images/library-placeholder.svg') }}"
                                            alt="Подразумијевани лого библиотеке" class="w-full h-full object-cover">
                                    </div>
                                    <span
                                        class="absolute inset-x-2 bottom-2 rounded-md bg-black/65 px-2 py-1 text-center text-xs font-semibold text-white">Додај
                                        лого</span>
                                    <input type="file" name="logo" id="logo_input" class="hidden" accept="image/*">
                                </label>
                            </div>

                            <div class="flex-grow">
                                <x-forms.input label="Назив" name="library"
                                    placeholder="Библиотека {{ old('name', 'Име корисника') }}" />
                                <p class="text-xs text-slate-400 mt-2">
                                    Ако оставите поље празно, користиће се предложени назив.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="numbering_mode"
                                class="block text-sm font-medium text-slate-300 mb-2 font-cyrillic uppercase tracking-wide">
                                Нумерација књига
                            </label>
                            <select name="numbering_mode" id="numbering_mode" class="custom-select w-full" required>
                                <option value="automatic" @selected(old('numbering_mode', 'automatic') === 'automatic')>
                                    Аутоматски редни број
                                </option>
                                <option value="manual" @selected(old('numbering_mode') === 'manual')>
                                    Ручни инвентарни број
                                </option>
                            </select>
                            <p class="text-xs text-slate-400 mt-2">
                                Изаберите аутоматско додјељивање броја или унос постојећих инвентарних бројева.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col items-center gap-4">
                    <x-forms.button type="submit">Региструј се</x-forms.button>
                    <p class="text-slate-400 font-cyrillic text-sm">
                        Имате профил? <a href="/login"
                            class="text-blue-400 hover:text-blue-300 hover:underline transition-colors">Пријава</a>
                    </p>
                </div>
            </div>

        </x-forms.form>
    </div>

    <script>
        const rolesData = @json(\App\Models\User::$schoolRoles);
        const userNameInput = document.getElementById('name');
        const libraryNameInput = document.getElementById('library');

        function updateLibraryPlaceholder() {
            const userName = userNameInput.value.trim() || 'Име корисника';
            libraryNameInput.placeholder = `Библиотека ${userName}`;
        }

        userNameInput.addEventListener('input', updateLibraryPlaceholder);

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
        const roleDetailsInput = document.getElementById('role_details_input');
        const roleDetailsLabel = inpWrap.querySelector('label');
        const oldStudentDetails = @json(old('role_details_select'));

        roleType.addEventListener('change', function() {
            const val = this.value;
            const subs = rolesData[val];

            detailsCont.classList.add('hidden');
            selWrap.classList.add('hidden');
            inpWrap.classList.add('hidden');
            subSel.disabled = true;
            subSel.required = false;
            roleDetailsInput.disabled = true;
            roleDetailsInput.required = false;

            if (val === 'Ученик') {
                detailsCont.classList.remove('hidden');
                selWrap.classList.remove('hidden');
                subSel.disabled = false;
                subSel.required = true;
                subSel.innerHTML = '';
                subSel.add(new Option('Изаберите разред и одјељење', ''));
                subs.forEach(s => subSel.add(new Option(s, s)));
                subSel.value = oldStudentDetails || '';
            } else if (val === 'Радник школе' || val === 'Друго') {
                detailsCont.classList.remove('hidden');
                inpWrap.classList.remove('hidden');
                roleDetailsInput.disabled = false;
                roleDetailsInput.required = true;
                roleDetailsLabel.textContent = val === 'Друго'
                    ? 'Опишите свој однос према школи или библиотеци'
                    : 'Радно мјесто';
                roleDetailsInput.placeholder = val === 'Друго'
                    ? 'нпр. пријатељ школе, спољни сарадник'
                    : 'нпр. директор, професор (наставни предмет), педагог, психолог, библиотекар';
            }
        });
        roleType.dispatchEvent(new Event('change'));
    </script>
</x-layout>
