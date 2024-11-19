@props(['tag'])

<x-panel class="flex flex-col text-center ">
    <div class="self-start text-sm">*Slika taga</div>

    <div class="py-8 ">
        <h3 class="group-hover:text-blue-800 text-xl font-bold ">
            <x-tag :$tag  />
        </h3>
        <p class="text-sm mt-4">*Opis taga ....</p>
    </div>

</x-panel>
