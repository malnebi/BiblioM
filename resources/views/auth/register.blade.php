<x-layout>

    <x-page-heading>Регистрација</x-page-heading>

    <x-forms.form method="POST" action="/register" enctype="multipart/form-data">
        <x-forms.input label="Име" name="name" />
        <x-forms.input label="мејл" name="email" type="email" />
        <x-forms.input label="Лозинка" name="password" type="password" />
        <x-forms.input label="Потврда лозинке" name="password_confirmation" type="password" />

        <x-forms.divider />

        <x-forms.input label="Име библиотеке" name="library" />
        <x-forms.input label="Лого библиотеке" name="logo" type="file" />

        <x-forms.button> Региструј се</x-forms.button>
    </x-forms.form>

</x-layout>
