@php
    $method = strtoupper($attributes->get('method', 'GET'));
    $formMethod = in_array($method, ['GET', 'POST'], true) ? $method : 'POST';
@endphp

<form {{ $attributes->except('method')->merge(["class" => "max-w-2xl mx-auto space-y-6", "method" => $formMethod]) }}>
    @if ($formMethod === 'POST')
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif
    @endif

    {{ $slot }}
</form>
