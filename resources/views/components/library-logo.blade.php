@props(['library', 'width' => 90])

@if($library instanceof \App\Models\Library)
    <img src="{{ asset('storage/' . $library->logo) }}" alt="Logo" class="rounded-xl" width="{{ $width }}">
@else
    {{-- Ako nije objekat biblioteke, prikaži placeholder ili ništa --}}
    <div class="bg-slate-800 rounded-xl flex items-center justify-center border border-slate-700" style="width: {{ $width }}px; height: {{ $width }}px;">
        <span class="text-[10px] text-slate-500">Nema logotipa</span>
    </div>
@endif
