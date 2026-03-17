<x-layout>

    <x-page-heading>Регистрација</x-page-heading>

    <x-forms.form method="POST" action="/register" enctype="multipart/form-data">
        <x-forms.input label="Име" name="name" />
        <x-forms.input label="Презиме" name="last_name" />

        <x-forms.select label="Улога" name="role_type" id="role_type">
            <option value="" disabled selected>Изаберите улогу</option>
            @foreach (array_keys(\App\Models\User::$schoolRoles) as $role)
                <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
        </x-forms.select>

        <div id="details_container" style="display: none;" class="mt-4">

            <div id="select_wrapper" style="display: none;">
                <x-forms.select label="Разред и одјељење" name="role_details_select" id="role_details_select">
                </x-forms.select>
            </div>

            <div id="input_wrapper" style="display: none;">
                <x-forms.input label="Прецизирајте занимање/предмет" name="role_details_input" id="role_details_input"
                    placeholder="нпр. Професор математике" />
            </div>

        </div>

        <script>
            const rolesData = @json(\App\Models\User::$schoolRoles);
            const roleTypeSelect = document.getElementById('role_type');
            const detailsContainer = document.getElementById('details_container');
            const selectWrapper = document.getElementById('select_wrapper');
            const inputWrapper = document.getElementById('input_wrapper');
            const subSelect = document.getElementById('role_details_select');

            roleTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                const subRoles = rolesData[selectedType];

                detailsContainer.style.display = 'block';

                if (selectedType === 'Ученик') {
                    // Прикажи селект, сакриј инпут
                    selectWrapper.style.display = 'block';
                    inputWrapper.style.display = 'none';

                    // Попуни селект опцијама из низа
                    subSelect.innerHTML = '';
                    subRoles.forEach(role => {
                        subSelect.add(new Option(role, role));
                    });
                } else if (selectedType === 'Професор' || selectedType === 'Стручни сарадник') {
                    // Сакриј селект, прикажи инпут за куцање
                    selectWrapper.style.display = 'none';
                    inputWrapper.style.display = 'block';
                } else {
                    // За Родитеље или остале ако не требају детаљи
                    detailsContainer.style.display = 'none';
                }
            });
        </script>
        
        <x-forms.input label="мејл" name="email" type="email" />

        <x-forms.input label="Лозинка" name="password" type="password" />
        <x-forms.input label="Потврда лозинке" name="password_confirmation" type="password" />

        <x-forms.divider />

        <x-forms.input label="Име библиотеке" name="library" />
        <x-forms.input label="Лого библиотеке" name="logo" type="file" />

        <x-forms.button> Региструј се</x-forms.button>
    </x-forms.form>

</x-layout>
