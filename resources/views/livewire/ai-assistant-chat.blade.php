<div
    x-data="{ open: false }"
    class="fixed bottom-6 right-6 z-50"
    @keydown.escape.window="open = false"
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
        class="mb-4 w-96 rounded-xl bg-white shadow-2xl ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700 flex flex-col"
        style="max-height: 500px;"
        x-cloak
    >
        {{-- Header --}}
        <div class="flex items-center justify-between rounded-t-xl bg-amber-500 px-4 py-3 shrink-0">
            <div class="flex items-center gap-2">
                <x-heroicon-o-sparkles class="h-5 w-5 text-white" />
                <span class="font-semibold text-white">AI {{ __('Assistant') }}</span>
            </div>
            <div class="flex items-center gap-1">
                <button
                    wire:click="clearChat"
                    class="rounded p-1 text-white/80 hover:bg-white/20 hover:text-white"
                    title="{{ __('Clear chat') }}"
                >
                    <x-heroicon-o-trash class="h-4 w-4" />
                </button>
                <button
                    @click="open = false"
                    class="rounded p-1 text-white/80 hover:bg-white/20 hover:text-white"
                >
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div
            class="flex-1 overflow-y-auto p-4 space-y-3"
            style="max-height: 350px;"
            x-ref="chatMessages"
            @ai-message-received.window="$nextTick(() => $refs.chatMessages.scrollTop = $refs.chatMessages.scrollHeight)"
        >
            @if (count($messages) === 0)
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <x-heroicon-o-chat-bubble-left-right class="h-10 w-10 text-gray-300 dark:text-gray-600 mb-2" />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('How can I help you today?') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        {{ __('Ask about inventory, orders, or procurement') }}
                    </p>
                </div>
            @endif

            @foreach ($messages as $msg)
                <div @class([
                    'flex',
                    'justify-end' => $msg['role'] === 'user',
                    'justify-start' => $msg['role'] === 'assistant',
                ])>
                    <div @class([
                        'max-w-[80%] rounded-lg px-3 py-2 text-sm',
                        'bg-amber-500 text-white' => $msg['role'] === 'user',
                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $msg['role'] === 'assistant',
                    ])>
                        {!! nl2br(e($msg['content'])) !!}
                    </div>
                </div>
            @endforeach

            @if ($isLoading)
                <div class="flex justify-start">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-2">
                        <div class="flex items-center gap-1">
                            <div class="h-2 w-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="h-2 w-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="h-2 w-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-200 dark:border-gray-700 p-3 shrink-0">
            <form wire:submit="sendMessage" class="flex gap-2">
                <input
                    wire:model="message"
                    type="text"
                    placeholder="{{ __('Type your message...') }}"
                    class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                    @disabled($isLoading)
                    autocomplete="off"
                />
                <button
                    type="submit"
                    class="rounded-lg bg-amber-500 px-3 py-2 text-white hover:bg-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition"
                    @disabled($isLoading)
                >
                    <x-heroicon-o-paper-airplane class="h-4 w-4" />
                </button>
            </form>
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
