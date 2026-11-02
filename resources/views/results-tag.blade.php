
<x-layout>
    <x-page-heading>Резултати претраге описних ознака</x-page-heading>
    <div class="space-y-6">
        @forelse($tags as $tag)
            <x-tag-card :$tag />
        @empty
            <p class="text-center text-sm text-gray-300">Нема описних ознака за ову претрагу.</p>
        @endforelse
    </div>
</x-layout>