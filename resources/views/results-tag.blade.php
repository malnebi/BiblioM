
<x-layout>
    <x-page-heading>Requested tag </x-page-heading>
    <div class="space-y-6">
        @foreach($tags as $tag)
            <x-tag-card :$tag />
        @endforeach
    </div>
</x-layout>