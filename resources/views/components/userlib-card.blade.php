@props(['user', 'library'])

<x-panel class="flex flex-col text-center ">
   
    <div class="py-8 ">
        <h3 class="group-hover:text-blue-800 text-xl font-bold ">
            <a href= "/users/{{ $user->id }}" target="_blank" class="btn btn-success" title="Погледај профил {{ $user->name }}">
                {{ $user->name }}
            </a>
        </h3>

    </div>

    <div class="flex justify-between items-center mt-auto">
        <h3 class="hover:text-blue-200 text-xl font-bold ">
            <a href= "/libraries/{{ $library->id }}" target="_blank" class="btn btn-success" title="Udji u biblioteku {{ $library->name }}">
                <x-library-logo :library="$user->ownLibrary" :width="42" />
                {{ $library->name }}
            </a>
        </h3>
    </div>
</x-panel>
