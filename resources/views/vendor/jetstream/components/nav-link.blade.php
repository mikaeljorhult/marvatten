@props(['icon' => 'home', 'active'])

@php
    $component = "heroicon-o-{$icon}";

    $linkClasses = ($active ?? false)
        ? 'bg-gray-200 text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md';

    $iconClasses = ($active ?? false)
        ? 'w-6 h-6 mr-3 text-gray-500'
        : 'w-6 h-6 mr-3 text-gray-400 group-hover:text-gray-500';
@endphp

<a {{ $attributes->merge(['class' => $linkClasses]) }}>
    @svg($component, $iconClasses)
    {{ $slot }}
</a>
