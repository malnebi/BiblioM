<x-layout>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
                    <h1 class="font-bold text-4xl col-span-3 text-center mb-3">
                        ЗАЈЕДНИЦА
                    </h1>
                </div> <x-forms.divider />

                <section class="pt-6">
                    <div class="grid lg:grid-cols-3 gap-8 mt-3">

                        @foreach ($users as $user)
                            @if ($user->ownLibrary)
                            @endif

                            @foreach ($libraries as $library)
                                @if ($library->owner_id === $user->id)
                                    <x-userlib-card :user="$user" :library="$library" />
                                @endif
                            @endforeach
                        @endforeach

                    </div>
            </div>
        </div>
    </div>
</x-layout>
