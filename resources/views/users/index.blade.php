<x-layout>
    @php
        $communityLibraries = $libraries->filter(fn ($library) => $users->contains('id', $library->owner_id))->values();
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <section class="pt-6">
                    <x-collapsible-section
                        title="ЗАЈЕДНИЦА"
                        :count="$communityLibraries->count()"
                        :show-more="$communityLibraries->count() > 6"
                    >
                        <x-slot name="visible">
                            <div class="grid lg:grid-cols-3 gap-8 mt-3">
                                @foreach ($communityLibraries->take(6) as $library)
                                    <x-userlib-card :user="$users->firstWhere('id', $library->owner_id)" :library="$library" />
                                @endforeach
                            </div>
                        </x-slot>

                        <div class="grid lg:grid-cols-3 gap-8 mt-3">
                            @foreach ($communityLibraries->skip(6) as $library)
                                <x-userlib-card :user="$users->firstWhere('id', $library->owner_id)" :library="$library" />
                            @endforeach
                        </div>
                    </x-collapsible-section>
            </div>
        </div>
    </div>
</x-layout>
