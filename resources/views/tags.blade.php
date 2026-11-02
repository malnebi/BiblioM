<x-layout>
    <section aria-labelledby="tags-heading">
        <x-section-heading>
            <span id="tags-heading">Описне ознаке ({{ $tags->count() }})</span>
        </x-section-heading>

        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($tags as $tag)
                <x-tag :$tag />
            @endforeach
        </div>

        @if ($tags->isEmpty())
            <p class="mt-6 text-center text-sm text-gray-300">Тренутно нема описних ознака.</p>
        @endif
    </section>
</x-layout>