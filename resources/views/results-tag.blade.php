
<x-layout>
    <x-page-heading>Тражена одредница {{$tags->name}}</x-page-heading>
    <div class="space-y-6">
        @foreach($tags as $tag)
            <x-tag-card :$tag />
        @endforeach
    </div>
</x-layout>