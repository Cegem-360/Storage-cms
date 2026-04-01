@props(['title', 'description'])

<div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
        {{ $icon }}
    </div>
    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $title }}</h3>
    <p class="text-gray-600 mb-4">{{ $description }}</p>
    <ul class="space-y-2 text-sm text-gray-600">
        {{ $slot }}
    </ul>
</div>
