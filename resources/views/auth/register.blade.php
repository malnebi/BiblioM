<x-layout>
    <div class="container mx-auto px-4 py-10 max-w-5xl">
        <h1 class="text-3xl font-bold text-white text-center mb-12 font-cyrillic">Регистрација</h1>

        <form action="/register" method="POST" enctype="multipart/form-data" class="space-y-12">
            @csrf

            {{-- --- ГОРЊИ ОДЈЕЉАК: ПОДАЦИ О КОРИСНИКУ --- --}}
            <div class="bg-slate-900/40 p-8 rounded-2xl border border-slate-700 shadow-xl">
                <h2 class="text-xl font-semibold text-blue-400 mb-8 border-b border-slate-700 pb-2 font-cyrillic">Подаци
                    о кориснику</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                    {{-- Лева страна корисника --}}
                    <div class="space-y-6">
                        <div class="flex items-start gap-6">
                            {{-- Квадрат за слику корисника --}}
                            <div class="flex-none">
                                <label for="user_photo_input" class="cursor-pointer group block">
                                    <div class="w-32 h-32 bg-slate-800 rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-slate-600 group-hover:border-blue-500 transition-all overflow-hidden"
                                        id="user_photo_preview">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-8 w-8 text-slate-500 group-hover:text-blue-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span
                                            class="text-slate-500 text-xs mt-2 group-hover:text-blue-500 font-cyrillic text-center px-2">Додај
                                            слику</span>
                                    </div>
                                    <input type="file" name="user_photo" id="user_photo_input" class="hidden"
                                        accept="image/*">
                                </label>
                            </div>

                            {{-- Име и Презиме поред слике --}}
                            <div class="flex-grow space-y-4">
                                <x-forms.input label="Име" name="name" required class="max-w-sm" />
                                <x-forms.input label="Презиме" name="last_name" required class="max-w-sm" />
                            </div>
                        </div>

                        {{-- Имејл испод слике --}}
                        <div class="max-w-md">
                            <x-forms.input label="Имејл адреса" name="email" type="email" required />
                        </div>
                    </div>

                    {{-- Десна страна корисника --}}
                    <div class="space-y-4 border-l border-slate-700/50 md:pl-12">
                        <x-forms.input label="Лозинка" name="password" type="password" required />
                        <x-forms.input label="Потврди лозинку" name="password_confirmation" type="password" required />

                        {{-- Падајући мени за Улогу --}}
                        <div class="pt-2">
                            <label for="role_type"
                                class="block text-sm font-medium text-slate-300 mb-1 font-cyrillic">Улога у
                                школи</label>
                            <select name="role_type" id="role_type" class="custom-select w-full">
                                <option value="" disabled selected>Изаберите улогу</option>
                                @foreach (array_keys(\App\Models\User::$schoolRoles) as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Динамички део за подопције (ученик/професор) --}}
                        <div id="details_container" class="mt-4 p-4 bg-slate-800/30 rounded-lg hidden">
                            <div id="select_wrapper" class="hidden">
                                <label for="role_details_select"
                                    class="block text-sm font-medium text-slate-300 mb-1 font-cyrillic">Разред и
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
                </div>
            </div>

            {{-- --- ДОЊИ ОДЈЕЉАК: ПОДАЦИ О БИБЛИОТЕЦИ --- --}}
            <div class="bg-slate-900/40 p-8 rounded-2xl border border-slate-700 shadow-xl">
                <h2 class="text-xl font-semibold text-blue-400 mb-8 border-b border-slate-700 pb-2 font-cyrillic">Подаци
                    о библиотеци</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div class="flex flex-col md:flex-row items-center gap-12">
                            {{-- Лого лево --}}
                            <div class="flex-none">
                                <label for="logo_input" class="cursor-pointer group block">
                                    <div class="w-32 h-32 bg-slate-800 rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-slate-600 group-hover:border-blue-500 transition-all overflow-hidden"
                                        id="logo_preview">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-8 w-8 text-slate-500 group-hover:text-blue-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span
                                            class="text-slate-500 text-xs mt-2 group-hover:text-blue-500 font-cyrillic text-center px-2">Додај
                                            лого</span>
                                    </div>
                                    <input type="file" name="logo" id="logo_input" class="hidden"
                                        accept="image/*">
                                </label>
                            </div>

                            {{-- Назив лијево поред слике --}}
                            <div class="flex-grow w-full max-w-md">
                                <x-forms.input label="Назив библиотеке" name="library" required />
                            </div>
                        </div>
                    </div>
                    {{-- Дугме и Линк за пријаву --}}
                    <div class="text-center space-y-6 pt-6">
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-4 px-16 rounded-full text-lg hover:scale-105 transition-transform shadow-lg font-cyrillic">
                            Региструј се
                        </button>

                        <p class="text-slate-400 font-cyrillic">
                            Имате профил? <a href="/login" class="text-blue-400 hover:underline">Пријава</a>
                        </p>
                    </div>
                </div>
            </div>

        </form>
    </div>

    

    <script>
        // Складиште података за улоге
        const rolesData = @json(\App\Models\User::$schoolRoles);

        // Слике преглед логика
        function setupPreview(inputId, previewId) {
            document.getElementById(inputId).addEventListener('change', function(e) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById(previewId).innerHTML =
                        `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(e.target.files[0]);
            });
        }
        setupPreview('user_photo_input', 'user_photo_preview');
        setupPreview('logo_input', 'logo_preview');

        // Динамичке улоге логика
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
