@props(['icon' => 'home', 'active'])

@php
    $component = "heroicon-o-{$icon}";

    $linkClasses = ($active ?? false)
        ? 'group flex items-center bg-gray-100 text-gray-900 px-2 py-2 text-base font-medium rounded-md'
        : 'group flex items-center text-gray-600 hover:bg-gray-50 hover:text-gray-900 px-2 py-2 text-base font-medium rounded-md';

    $iconClasses = ($active ?? false)
        ? 'h-6 w-6 mr-4 text-gray-500'
        : 'w-6 h-6 mr-4 text-gray-400 group-hover:text-gray-500';
@endphp

<a {{ $attributes->merge(['class' => $linkClasses]) }}>
    @svg($component, $iconClasses)
    {{ $slot }}
</a>
