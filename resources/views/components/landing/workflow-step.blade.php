@props(['title', 'description', 'side' => 'left', 'color' => 'amber'])

@php
$bgColor = match ($color) {
    'emerald' => 'bg-emerald-100',
    default => 'bg-amber-100',
};
@endphp

<div class="relative flex flex-col lg:flex-row items-center gap-6">
    @if($side === 'left')
        <div class="flex-1 lg:text-right order-2 lg:order-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $title }}</h3>
            <p class="text-gray-600 text-sm">{{ $description }}</p>
        </div>
    @else
        <div class="flex-1 lg:opacity-0 order-2 lg:order-1"><!-- Spacer --></div>
    @endif

    <div class="relative z-10 order-1 lg:order-2">
        <div class="w-14 h-14 {{ $bgColor }} rounded-full flex items-center justify-center border-4 border-white shadow-lg">
            {{ $icon }}
        </div>
    </div>

    @if($side === 'left')
        <div class="flex-1 order-3 lg:opacity-0"><!-- Spacer --></div>
    @else
        <div class="flex-1 order-3 lg:text-left">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $title }}</h3>
            <p class="text-gray-600 text-sm">{{ $description }}</p>
        </div>
    @endif
</div>
