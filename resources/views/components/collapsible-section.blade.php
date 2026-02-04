@props(['id' => 'section-' . Str::random(8), 'title' => null, 'count' => null])

<div {{ $attributes->merge(['class' => 'mt-6']) }}>

    {{-- Наслов секције се приказује само ако је прослеђен --}}
    @if($title)
        <x-section-heading>
            {{ $title }} @if($count !== null) ({{ $count }}) @endif
        </x-section-heading>
        <x-forms.divider />
    @endif

    {{-- Приказ увек видљивог дела --}}
    <div class="space-y-4">
        @if(isset($visible))
            {{ $visible }}
        @endif
    </div>

    {{-- Приказ дела који се отвара на клик --}}
    @if(isset($slot) && trim($slot) !== '')
        <details 
            id="{{ $id }}" 
            class="group mt-4" 
            ontoggle="if(this.open) { setTimeout(() => { this.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 100); }"
        >
            <summary class="list-none cursor-pointer outline-none">
                <div class="flex items-center text-orange-500 font-bold hover:underline">
                    <span class="group-open:hidden">Види више...</span>
                    <span class="hidden group-open:inline text-gray-400 font-normal text-xs uppercase italic tracking-widest">Прикажи мање</span>
                </div>
            </summary>

            <div class="mt-4 space-y-4">
                {{ $slot }}
            </div>
        </details>
    @endif
</div>

<style>
    #{{ $id }} { scroll-margin-top: 2rem; }
    summary::-webkit-details-marker { display: none; }
</style>
