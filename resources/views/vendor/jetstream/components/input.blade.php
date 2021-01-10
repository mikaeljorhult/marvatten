@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block sm:text-sm border-gray-300 focus:ring-pink-500 focus:border-pink-500 rounded-md shadow-sm']) !!}>
