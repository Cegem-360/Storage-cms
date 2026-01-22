<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="flex items-center gap-1.5 px-2 py-1.5 text-sm text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
        </svg>
        <span class="uppercase">{{ app()->getLocale() }}</span>
        <svg class="w-3 h-3" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-transition
         class="absolute right-0 mt-1 w-24 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
        <a href="{{ route('language.switch', 'hu') }}"
           class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ app()->getLocale() === 'hu' ? 'bg-gray-100 font-medium' : '' }}">
            Magyar
        </a>
        <a href="{{ route('language.switch', 'en') }}"
           class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors {{ app()->getLocale() === 'en' ? 'bg-gray-100 font-medium' : '' }}">
            English
        </a>
    </div>
</div>
