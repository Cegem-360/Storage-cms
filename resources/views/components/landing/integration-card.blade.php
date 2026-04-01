@props(['name', 'color' => 'gray'])

@php
$colorMap = [
    'purple' => ['from-purple-50', 'border-purple-200', 'bg-purple-100'],
    'green' => ['from-green-50', 'border-green-200', 'bg-green-100'],
    'indigo' => ['from-indigo-50', 'border-indigo-200', 'bg-indigo-100'],
    'red' => ['from-red-50', 'border-red-200', 'bg-red-100'],
    'emerald' => ['from-emerald-50', 'border-emerald-200', 'bg-emerald-100'],
    'gray' => ['from-gray-50', 'border-gray-200', 'bg-gray-100'],
];
[$gradient, $border, $iconBg] = $colorMap[$color] ?? $colorMap['gray'];
@endphp

<div class="bg-linear-to-br {{ $gradient }} to-white rounded-xl p-4 border {{ $border }} w-36 text-center">
    <div class="w-10 h-10 {{ $iconBg }} rounded-lg flex items-center justify-center mx-auto mb-2">
        {{ $icon }}
    </div>
    <span class="text-sm font-medium text-gray-700">{{ $name }}</span>
</div>
