@props(['href', 'variant' => 'primary', 'size' => 'default'])

@php
    $variantClasses = [
        'primary' => 'bg-orange-500 text-white hover:bg-orange-600',
        'white' => 'bg-white text-orange-600 hover:bg-gray-100',
        'outline-white' => 'border border-white bg-transparent text-white hover:bg-white/10',
        'dark' => 'bg-gray-900 text-white hover:bg-gray-800',
        'outline' => 'border border-gray-300 bg-white text-gray-900 hover:bg-gray-50',
    ];
    $sizeClasses = [
        'default' => 'h-9 px-4 text-sm',
        'lg' => 'h-12 px-8 text-base',
    ];
@endphp

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md font-semibold shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2',
    $variantClasses[$variant],
    $sizeClasses[$size],
]) }}>{{ $slot }}</a>
