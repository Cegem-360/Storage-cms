@props(['question'])

<div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
        <span class="font-medium text-gray-900">{{ $question }}</span>
        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div x-show="open" x-collapse>
        <div class="px-6 pb-4 text-gray-600">
            {{ $slot }}
        </div>
    </div>
</div>
