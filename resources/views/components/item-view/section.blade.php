@props(['key'])

<div
    class="mt-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8"
    x-show="tab === '{{ $key }}'"
    x-cloak
>
    {{ $slot }}
</div>