@props([
    'value' => 0,
    'color' => 'bg-gray-400',
    'emoji' => '🙂',
    'label' => '',
])

@php
    $height = min(100, max(0, $value));
@endphp

<div class="group flex flex-col items-center justify-end h-full">
    
    <span>{{ $emoji }}</span>

    <div 
        class="w-10 sm:w-16 md:w-24 {{ $color }} rounded-sm relative transition-all duration-500"
        style="height: {{ $height }}%"
    >
        <span class="absolute -top-10 left-1/2 -translate-x-1/2 text-xs opacity-0 group-hover:opacity-100 transition">
            {{ $height }}%
        </span>
    </div>

    <span class="mt-2 text-xs">{{ $label }}</span>
</div>