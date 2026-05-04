@props(['title', 'value', 'icon', 'color' => 'blue', 'pulse' => false])

@php
$colors = [
    'blue' => 'text-blue-600 bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800',
    'emerald' => 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800',
    'rose' => 'text-rose-600 bg-rose-50 dark:bg-rose-900/20 border-rose-100 dark:border-rose-800',
    'amber' => 'text-amber-600 bg-amber-50 dark:bg-amber-900/20 border-amber-100 dark:border-amber-800',
    'indigo' => 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 border-indigo-100 dark:border-indigo-800',
];
$currentColor = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 flex items-center gap-4 group hover:shadow-md transition-shadow relative overflow-hidden">
    <div class="p-4 rounded-2xl {{ $currentColor }} flex-shrink-0 relative z-10">
        @if($icon === 'o-users') <x-heroicon-o-users class="w-8 h-8" />
        @elseif($icon === 'o-document-text') <x-heroicon-o-document-text class="w-8 h-8" />
        @elseif($icon === 'o-exclamation-triangle') <x-heroicon-o-exclamation-triangle class="w-8 h-8" />
        @elseif($icon === 'o-star') <x-heroicon-o-star class="w-8 h-8" />
        @endif
        
        @if($pulse)
            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ str_replace('text-', 'bg-', explode(' ', $currentColor)[0]) }}"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 {{ str_replace('text-', 'bg-', explode(' ', $currentColor)[0]) }}"></span>
            </span>
        @endif
    </div>
    <div class="relative z-10">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ $title }}</p>
        <p class="text-3xl font-black text-slate-800 dark:text-white leading-none">{{ $value }}</p>
    </div>

    <!-- Decorative background element -->
    <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full opacity-[0.03] dark:opacity-[0.05] {{ str_replace('text-', 'bg-', explode(' ', $currentColor)[0]) }} group-hover:scale-150 transition-transform duration-500"></div>
</div>
