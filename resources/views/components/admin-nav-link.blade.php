@props(['active', 'href', 'icon', 'label'])

@php
$classes = ($active ?? false)
            ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20'
            : 'text-slate-300 hover:bg-slate-700 hover:text-white';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => "flex items-center gap-3 p-3 rounded-xl transition-all duration-200 group $classes"]) }}>
    <div class="flex-shrink-0">
        @if($icon === 'o-home') <x-heroicon-o-home class="w-6 h-6" />
        @elseif($icon === 'o-users') <x-heroicon-o-users class="w-6 h-6" />
        @elseif($icon === 'o-academic-cap') <x-heroicon-o-academic-cap class="w-6 h-6" />
        @elseif($icon === 'o-document-text') <x-heroicon-o-document-text class="w-6 h-6" />
        @elseif($icon === 'o-exclamation-triangle') <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
        @elseif($icon === 'o-star') <x-heroicon-o-star class="w-6 h-6" />
        @elseif($icon === 'o-clipboard-document-list') <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
        @endif
    </div>
    <span x-show="sidebarOpen" x-transition.opacity class="font-medium whitespace-nowrap overflow-hidden">
        {{ $label }}
    </span>
</a>
