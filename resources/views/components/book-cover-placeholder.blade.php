@props(['authorFname' => '', 'authorLname' => '', 'title' => ''])

@php
    $authorInitials = collect([$authorFname, $authorLname])
        ->map(fn ($name) => trim($name))
        ->filter()
        ->map(fn ($name) => mb_strtoupper(mb_substr($name, 0, 1)))
        ->implode('.');
    $initials = $authorInitials !== '' ? $authorInitials . '.' : 'К.';
@endphp

<div {{ $attributes->merge(['class' => 'relative flex flex-col items-center justify-center overflow-hidden rounded bg-gradient-to-br from-emerald-900 via-slate-800 to-blue-950 p-2 text-center']) }}
    data-book-cover-placeholder>
    <div class="pointer-events-none absolute inset-1 rounded border border-white/20 sm:inset-2"></div>
    <span data-cover-initials class="relative text-xs font-black tracking-wide text-emerald-100 sm:text-sm">
        {{ $initials }}
    </span>
    <span data-cover-title title="{{ $title }}" class="relative mt-1 line-clamp-3 break-words text-[6px] font-semibold leading-tight text-white sm:mt-2 sm:text-[7px]">
        {{ $title ?: 'Наслов књиге' }}
    </span>
</div>
