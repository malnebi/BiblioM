<x-layout>
    <div class="space-y-10">

        @if (session('success'))
            <div class="rounded-md bg-green-100 px-4 py-3 text-green-900" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="text-center">
            <h1 class="font-bold text-4xl"> Пронађи књигу </h1>

            <x-forms.form action="/search" class="mt-6">
                <x-forms.input :label="false" name="q" placeholder="Наслов, име аутора, описна ознака..." />
                 <x-forms.button>ПРЕТРАЖИ</x-forms.button>

            </x-forms.form>

        </section>

        <section>
            <x-section-heading>Описне ознаке</x-section-heading>
            <div x-data="{ expanded: false, limit: {{ $limit }} }" class="mt-6">
                <div class="flex flex-wrap gap-2">
                    @foreach ($tags as $index => $tag)
                        <div x-show="expanded || {{ $index }} < limit"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            {{-- Ово спречава 'треперење' пре него што се Alpine учита --}} style="display: {{ $index < $limit ? 'block' : 'none' }}">
                            <x-tag :$tag />
                        </div>
                    @endforeach

                    @if ($tags->count() > $limit)
                        <button @click="expanded = !expanded" type="button"
                            class="text-xs font-bold px-2 py-1 rounded bg-gray-700 hover:bg-gray-500 text-white transition-colors self-center">
                            <span x-show="!expanded">+ Прикажи још ({{ $tags->count() - $limit }})</span>
                            <span x-show="expanded">- Сакриј</span>
                        </button>
                    @endif
                </div>
            </div>
        </section>
        
        <section>
            <x-collapsible-section
                title="Најновије књиге у апликацији"
                :count="$books->count()"
                :show-more="$books->count() > 6"
            >
                <x-slot name="visible">
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                        @foreach ($books->take(6) as $book)
                            <x-book-card-wide :$book />
                        @endforeach
                    </div>
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-12">
                    @foreach ($books->skip(6) as $book)
                        <x-book-card-wide :$book />
                    @endforeach
                </div>
            </x-collapsible-section>
        </section>
    </div>
</x-layout>
