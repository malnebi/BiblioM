<x-layout>
    <div class="mx-auto max-w-4xl px-4 py-10">
        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-400">Кориснички налог</p>
            <h1 class="mt-2 text-3xl font-black text-white">Подешавања профила</h1>
        </div>

        @if (session('status'))
            <div role="status" class="mb-6 rounded-lg border border-emerald-500/40 bg-emerald-500/10 p-4 text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="mb-6 rounded-lg border border-red-500/40 bg-red-500/10 p-4 text-red-200">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($pendingChange)
            <section class="mb-6 rounded-lg border border-amber-400/40 bg-amber-400/10 p-4 text-amber-100">
                <h2 class="font-semibold">Захтјев чека администраторско одобрење</h2>
                <p class="mt-1 text-sm text-amber-100/80">Измјене профила и библиотеке остају на чекању док их администратор не прегледа.</p>
                @if ($pendingChange->requested_photo)
                    <img src="{{ asset('storage/' . $pendingChange->requested_photo) }}" alt="Предложена фотографија"
                        class="mt-3 h-20 w-20 rounded-full border border-amber-200/50 object-cover">
                @endif
            </section>
        @endif

        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data"
            class="space-y-6 rounded-xl border border-slate-700 bg-[#1e293b] p-6 shadow-xl">
            @csrf
            @method('PUT')

            <section class="space-y-5">
                <h2 class="text-lg font-bold text-white">Име и фотографија</h2>

                <div class="flex flex-wrap items-center gap-6">
                    <div>
                        <p class="mb-2 text-sm text-slate-300">Тренутна фотографија</p>
                        <img src="{{ $user->user_photo ? asset('storage/' . $user->user_photo) : Vite::asset('resources/images/user-placeholder.svg') }}"
                            alt="Тренутна фотографија корисника" class="h-24 w-24 rounded-full border border-slate-600 object-cover">
                    </div>
                    <div class="min-w-64 flex-1">
                        <label for="user_photo" class="mb-2 block text-sm font-medium text-slate-300">Предложи нову фотографију</label>
                        <input id="user_photo" name="user_photo" type="file" accept="image/jpeg,image/png,image/webp"
                            class="block w-full text-sm text-slate-300 file:mr-3 file:rounded-md file:border-0 file:bg-blue-600 file:px-3 file:py-2 file:font-semibold file:text-white hover:file:bg-blue-500">
                        <p class="mt-2 text-xs text-slate-400">JPG, PNG или WebP, до 2 MB. Фотографија чека одобрење.</p>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-forms.input label="Име" name="name" :value="old('name', $pendingChange?->requested_name ?? $user->name)" required maxlength="100" />
                    <x-forms.input label="Презиме" name="last_name" :value="old('last_name', $pendingChange?->requested_last_name ?? $user->last_name)" maxlength="100" />
                </div>
                <p class="text-sm text-slate-400">Промјене имена и фотографије постају активне тек након прегледа администратора.</p>
            </section>

            <section class="space-y-5 border-t border-slate-700 pt-6">
                <h2 class="text-lg font-bold text-white">(У) ШКОЛИ САМ</h2>
                <div>
                    <select id="role_type" name="role_type" class="custom-select w-full">
                        <option value="">{{ $isFormerStudent ? 'Бивши ученик (задржи тренутни статус)' : 'Задржи тренутну улогу' }}</option>
                        @foreach ($roles as $role => $details)
                            <option value="{{ $role }}" @selected(old('role_type', $pendingChange?->requested_role_type ?? $user->role_type) === $role)>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="role_details_container" class="hidden rounded-lg border border-slate-700/50 bg-slate-800/30 p-4">
                    <div id="role_details_select_wrapper" class="hidden">
                        <label for="role_details_select" class="mb-2 block text-sm font-medium text-slate-300">Разред и одјељење</label>
                        <select id="role_details_select" name="role_details" class="custom-select w-full" disabled></select>
                    </div>
                    <div id="role_details_input_wrapper" class="hidden">
                        <label id="role_details_label" for="role_details_input" class="mb-2 block text-sm font-medium text-slate-300">Детаљи улоге</label>
                        <input id="role_details_input" name="role_details" type="text" maxlength="255"
                            value="{{ old('role_details', $pendingChange?->requested_role_details ?? $user->role_details) }}"
                            class="bg-[#1e293b] text-white border border-slate-600 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-slate-500"
                            disabled>
                    </div>
                </div>
                <p class="text-sm text-slate-400">За особе које више нису у школи изаберите „Друго“ и наведите то у детаљима. Промјена се активира након одобрења администратора.</p>
            </section>

            @if ($library)
                <section class="space-y-5 border-t border-slate-700 pt-6">
                    <h2 class="text-lg font-bold text-white">Моја библиотека</h2>

                    <div class="flex flex-wrap items-center gap-6">
                        <div>
                            <p class="mb-2 text-sm text-slate-300">Тренутни логотип</p>
                            <img src="{{ $library->logo ? asset('storage/' . $library->logo) : Vite::asset('resources/images/library-placeholder.svg') }}"
                                alt="Логотип библиотеке" class="h-24 w-24 rounded-lg border border-slate-600 object-cover">
                        </div>
                        <div class="min-w-64 flex-1">
                            <label for="library_logo" class="mb-2 block text-sm font-medium text-slate-300">Предложи нови логотип</label>
                            <input id="library_logo" name="library_logo" type="file" accept="image/jpeg,image/png,image/webp"
                                class="block w-full text-sm text-slate-300 file:mr-3 file:rounded-md file:border-0 file:bg-blue-600 file:px-3 file:py-2 file:font-semibold file:text-white hover:file:bg-blue-500">
                            <p class="mt-2 text-xs text-slate-400">JPG, PNG или WebP, до 2 MB. Логотип чека администраторско одобрење.</p>
                        </div>
                        @if ($pendingChange?->requested_library_logo)
                            <div>
                                <p class="mb-2 text-sm text-amber-200">Предложени логотип</p>
                                <img src="{{ asset('storage/' . $pendingChange->requested_library_logo) }}" alt="Предложени логотип библиотеке"
                                    class="h-24 w-24 rounded-lg border border-amber-300/50 object-cover">
                            </div>
                        @endif
                    </div>

                    <x-forms.input label="Назив библиотеке" name="library_name"
                        :value="old('library_name', $pendingChange?->requested_library_name ?? $library->name)" required maxlength="255" />
                    <p class="text-sm text-slate-400">Назив библиотеке и логотип мијењају се тек након одобрења администратора.</p>
                </section>
            @endif

            <div class="flex justify-end border-t border-slate-700 pt-5">
                <x-forms.button type="submit">Пошаљи измјене на одобрење</x-forms.button>
            </div>
        </form>
    </div>

    <script>
        const schoolRoles = @json($roles);
        const roleTypeSelect = document.getElementById('role_type');
        const roleDetailsContainer = document.getElementById('role_details_container');
        const roleDetailsSelectWrapper = document.getElementById('role_details_select_wrapper');
        const roleDetailsInputWrapper = document.getElementById('role_details_input_wrapper');
        const roleDetailsSelect = document.getElementById('role_details_select');
        const roleDetailsInput = document.getElementById('role_details_input');
        const roleDetailsLabel = document.getElementById('role_details_label');
        const initialRoleDetails = @json(old('role_details', $pendingChange?->requested_role_details ?? $user->role_details));
        const initialRole = roleTypeSelect.value;

        function updateRoleDetails(resetValue = false) {
            const role = roleTypeSelect.value;
            const currentValue = resetValue ? '' : (initialRoleDetails || '');

            roleDetailsContainer.classList.add('hidden');
            roleDetailsSelectWrapper.classList.add('hidden');
            roleDetailsInputWrapper.classList.add('hidden');
            roleDetailsSelect.disabled = true;
            roleDetailsInput.disabled = true;
            roleDetailsSelect.required = false;
            roleDetailsInput.required = false;

            if (role === 'Ученик') {
                roleDetailsContainer.classList.remove('hidden');
                roleDetailsSelectWrapper.classList.remove('hidden');
                roleDetailsSelect.disabled = false;
                roleDetailsSelect.required = true;
                roleDetailsSelect.innerHTML = '';
                roleDetailsSelect.add(new Option('Изаберите разред и одјељење', ''));
                (schoolRoles[role] || []).forEach((detail) => {
                    roleDetailsSelect.add(new Option(detail, detail));
                });
                roleDetailsSelect.value = currentValue;
                return;
            }

            if (['Радник школе', 'Друго'].includes(role)) {
                roleDetailsContainer.classList.remove('hidden');
                roleDetailsInputWrapper.classList.remove('hidden');
                roleDetailsInput.disabled = false;
                roleDetailsInput.value = currentValue;
                roleDetailsInput.required = true;
                roleDetailsInput.placeholder = role === 'Друго'
                    ? 'нпр. пријатељ школе, спољни сарадник'
                    : 'нпр. директор, професор (наставни предмет), педагог, психолог, библиотекар';
                roleDetailsLabel.textContent = role === 'Друго'
                    ? 'Опишите свој однос према школи или библиотеци'
                    : 'Радно мјесто';
            }
        }

        roleTypeSelect.addEventListener('change', () => updateRoleDetails(roleTypeSelect.value !== initialRole));
        updateRoleDetails();
    </script>
</x-layout>