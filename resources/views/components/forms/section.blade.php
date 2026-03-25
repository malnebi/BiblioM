{{-- resources/views/components/forms/section.blade.php --}}
@props(['title'])

<div {{ $attributes->merge(['class' => 'p-8 bg-[#0f172a]/50 border border-slate-700 rounded-2xl']) }}>
    @if($title)
        <h2 class="font-bold text-blue-400 mb-8 text-lg uppercase tracking-wider">
            {{ $title }}
        </h2>
    @endif

    <div class="grid lg:grid-cols-2 gap-12">
        {{-- Лева колона --}}
        <div class="space-y-6">
            {{ $left }}
        </div>

        {{-- Десна колона --}}
        <div class="space-y-6 flex flex-col justify-between">
            {{ $right }}
        </div>
    </div>
</div>