@props(['authorFname' => '', 'authorLname' => '', 'title' => ''])

@php
    $initials = collect([$authorFname, $authorLname])
        ->map(fn ($name) => trim($name))
        ->filter()
        ->map(fn ($name) => mb_strtoupper(mb_substr($name, 0, 1)))
        ->implode('.') . '.';
@endphp

<div {{ $attributes->merge(['class' => 'relative flex flex-col items-center justify-center overflow-hidden rounded bg-gradient-to-br from-emerald-900 via-slate-800 to-blue-950 p-2 text-center']) }}
    data-book-cover-placeholder>
    <div class="pointer-events-none absolute inset-1 rounded border border-white/20 sm:inset-2"></div>
    <span data-cover-initials class="relative text-base font-black tracking-wide text-emerald-100 sm:text-xl">
        {{ $initials ?: 'К.' }}
    </span>
    <span data-cover-title class="relative mt-1 line-clamp-4 break-words text-[8px] font-semibold leading-tight text-white sm:mt-2 sm:text-[10px]">
        {{ $title ?: 'Наслов књиге' }}
    </span>
</div>
