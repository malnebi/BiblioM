<x-layout>
    <x-page-heading>Постављање нове лозинке</x-page-heading>

    <x-forms.form method="POST" action="{{ route('password.update') }}">
        <input type="hidden" name="token" value="{{ $token }}">
        <x-forms.input label="Мејл адреса" name="email" type="email" :value="$email" required />
        <x-forms.input label="Нова лозинка" name="password" type="password" required />
        <x-forms.input label="Поновите нову лозинку" name="password_confirmation" type="password" required />
        <x-forms.button>Промијени лозинку</x-forms.button>
    </x-forms.form>
</x-layout>
