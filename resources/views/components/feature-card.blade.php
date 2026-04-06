@props(['title', 'description', 'color' => 'blue'])

@php
    $colors = [
        'blue'    => ['bg' => 'bg-blue-500/20',    'icon' => 'text-blue-400',    'border' => 'hover:border-blue-500'],
        'emerald' => ['bg' => 'bg-emerald-500/20', 'icon' => 'text-emerald-400', 'border' => 'hover:border-emerald-500'],
        'amber'   => ['bg' => 'bg-amber-500/20',   'icon' => 'text-amber-400',   'border' => 'hover:border-amber-500'],
    ];
    $style = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-[#1e293b] dark:bg-gradient-to-bl from-gray-700/50 via-transparent border border-slate-700 p-8 rounded-3xl transition-all duration-300 group {{ $style['border'] }} shadow-lg hover:shadow-2xl hover:-translate-y-1">
    <div class="w-14 h-14 {{ $style['bg'] }} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
        {{ $slot }}
    </div>
    <h3 class="text-white text-xl font-bold mb-3 tracking-tight">{{ $title }}</h3>
    <p class="text-slate-400 text-sm leading-relaxed">{{ $description }}</p>
</div>