@props(['active', 'href'])

@php
$classes = ($active ?? false)
            ? 'flex items-center p-2.5 rounded-lg transition-all duration-200 group bg-indigo-50 text-indigo-600'
            : 'flex items-center p-2.5 rounded-lg transition-all duration-200 group text-gray-600 hover:bg-gray-50 hover:text-gray-900';

$iconClasses = ($active ?? false)
            ? 'w-5 h-5 transition duration-75 text-indigo-600'
            : 'w-5 h-5 transition duration-75 text-gray-400 group-hover:text-gray-900';
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <div class="{{ $iconClasses }}">
            {{ $icon }}
        </div>
        <span class="ms-3">{{ $slot }}</span>
    </a>
</li>