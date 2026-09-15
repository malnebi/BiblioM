@props(['library', 'width' => 90])

@if($library instanceof \App\Models\Library)
    <img src="{{ $library->logo ? asset('storage/' . $library->logo) : Vite::asset('resources/images/library-placeholder.svg') }}"
        alt="Лого библиотеке" class="rounded-xl" width="{{ $width }}">
@else
    <img src="{{ Vite::asset('resources/images/library-placeholder.svg') }}" alt="Подразумијевани лого библиотеке"
        class="rounded-xl" width="{{ $width }}">
@endif
