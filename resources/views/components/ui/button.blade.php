@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])

@php
    $variants = [
        'primary' => 'bg-emerald-700 text-white hover:bg-emerald-800 focus-visible:ring-emerald-600',
        'secondary' => 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 focus-visible:ring-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700',
        'warning' => 'bg-amber-600 text-white hover:bg-amber-700 focus-visible:ring-amber-500',
        'purple' => 'bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 focus-visible:ring-purple-400 dark:bg-purple-950/40 dark:text-purple-300 dark:border-purple-800 dark:hover:bg-purple-900/50',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:ring-red-500',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';
    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
