@props(['title', 'description', 'color' => 'red'])

@php
$colorMap = [
    'red' => ['from-red-50', 'border-red-100'],
    'amber' => ['from-amber-50', 'border-amber-100'],
    'orange' => ['from-orange-50', 'border-orange-100'],
    'purple' => ['from-purple-50', 'border-purple-100'],
];
[$gradient, $border] = $colorMap[$color] ?? $colorMap['red'];
@endphp

<div class="bg-linear-to-br {{ $gradient }} to-white rounded-2xl p-6 border {{ $border }}">
    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
        {{ $icon }}
    </div>
    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-gray-600 text-sm">{{ $description }}</p>
</div>
