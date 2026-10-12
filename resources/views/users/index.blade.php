<x-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <section class="pt-6" aria-labelledby="community-heading">
                    <x-section-heading>
                        <span id="community-heading">ЗАЈЕДНИЦА ({{ $communityLibraries->total() }})</span>
                    </x-section-heading>
                    <x-forms.divider />

                    <div
                        data-community-list
                        data-next-page="{{ $communityLibraries->nextPageUrl() }}"
                        class="mt-3"
                    >
                        <div data-community-cards class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @include('users.partials.community-cards', ['libraries' => $communityLibraries])
                        </div>

                        @if ($communityLibraries->hasMorePages())
                            <div data-community-sentinel class="h-1" aria-hidden="true"></div>
                            <p data-community-status class="py-3 text-center text-sm text-gray-300" aria-live="polite"></p>
                        @endif
                    </div>

                    @if ($communityLibraries->isEmpty())
                        <p class="mt-4 text-center text-sm text-gray-300">Тренутно нема чланова за приказ.</p>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-layout>
