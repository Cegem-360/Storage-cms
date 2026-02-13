<div
    x-data="{ open: false }"
    class="fixed bottom-6 right-6 z-50"
>
    {{-- Chat Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="mb-4 w-80 rounded-xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700"
        x-cloak
    >
        <div class="flex items-center justify-between rounded-t-xl bg-amber-500 px-4 py-3">
            <div class="flex items-center gap-2">
                <x-heroicon-o-sparkles class="h-5 w-5 text-white" />
                <span class="font-semibold text-white">AI {{ __('Help') }}</span>
            </div>
            <button @click="open = false" class="text-white hover:text-amber-100">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>
        </div>
        <div class="p-4">
            <p class="mb-3 text-sm text-gray-600 dark:text-gray-300">
                {{ __('How can I help you today?') }}
            </p>
            <div class="space-y-2">
                <a href="https://docs.google.com" target="_blank"
                   class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                    <x-heroicon-o-book-open class="h-4 w-4" />
                    {{ __('Documentation') }}
                </a>
                <a href="mailto:support@example.com"
                   class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                    <x-heroicon-o-envelope class="h-4 w-4" />
                    {{ __('Support Center') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Floating Button --}}
    <button
        @click="open = !open"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-500 text-white shadow-lg transition hover:bg-amber-600 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-amber-300"
    >
        <x-heroicon-o-sparkles x-show="!open" class="h-6 w-6" />
        <x-heroicon-o-x-mark x-show="open" class="h-6 w-6" x-cloak />
    </button>
</div>
