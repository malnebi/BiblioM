@props(['label', 'name', 'value' => null])

@php
    $defaults = [
        'type' => 'text',
        'id' => $name,
        'name' => $name,
        'class' => 'bg-[#1e293b] text-white border border-slate-00 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-grey-900',
        'value' => $value ?? old($name)
    ];
@endphp

<x-forms.field :$label :$name>
    <input {{ $attributes($defaults) }}>
</x-forms.field>


