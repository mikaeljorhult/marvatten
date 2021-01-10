@props(['key', 'active'])

@php
    $linkClasses = ($active ?? false)
        ? 'py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap border-pink-500 text-gray-900'
        : 'py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
@endphp

<a
    href="#{{ $key }}"
    x-on:click.prevent="tab = '{{ $key }}'"
    x-bind:class="{ 'border-pink-500 text-gray-900': tab == '{{ $key }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab != '{{ $key }}' }"
    @if($active) aria-current="page" @endif
    {{ $attributes->merge(['class' => $linkClasses]) }}
>
    {{ $slot }}
</a>