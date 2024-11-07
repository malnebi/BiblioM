@props(['library', 'width' => 90])

<img src="{{ asset('storage/' . $library->logo) }}" alt="" class="rounded-xl" width="{{ $width }}">