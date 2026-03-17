<x-layout>
    <x-page-heading>Пријављивање</x-page-heading>


    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <x-forms.form method="POST" action="/login">
        <x-forms.input label="Мејл адреса" name="email" type="email" />
        <x-forms.input label="Лозинка" name="password" type="password" />

        <x-forms.button>Пријава</x-forms.button>
    </x-forms.form>
</x-layout>
