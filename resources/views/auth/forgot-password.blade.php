<x-layout>
    <x-page-heading>Промјена заборављене лозинке</x-page-heading>

    @if (session('status'))
        <div role="status" class="mb-6 rounded-lg bg-green-800 p-4 text-center">
            {{ session('status') }}
        </div>
    @else
        <x-forms.form method="POST" action="{{ route('password.email') }}">
            <x-forms.input label="Мејл адреса" name="email" type="email" required />
            <x-forms.button>Пошаљи линк</x-forms.button>
        </x-forms.form>
    @endif

    <p class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-blue-300 underline hover:text-blue-200">Назад на пријаву</a>
    </p>
</x-layout>
