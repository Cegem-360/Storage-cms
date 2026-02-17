<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('AI Assistant') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Ask about inventory, orders, or procurement') }}</p>
        </div>
        @if (count($messages) > 0)
            <button
                wire:click="clearChat"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                {{ __('Clear chat') }}
            </button>
        @endif
    </div>

    {{-- Chat area --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col" style="height: calc(100vh - 220px); min-height: 400px;">

        {{-- Messages --}}
        <div
            class="flex-1 overflow-y-auto p-6 space-y-4"
            x-ref="chatMessages"
            @ai-message-received.window="$nextTick(() => $refs.chatMessages.scrollTop = $refs.chatMessages.scrollHeight)"
        >
            @if (count($messages) === 0)
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('How can I help you today?') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md">{{ __('I can help you with stock levels, orders, products, and suppliers. Ask me anything about your data!') }}</p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-lg w-full">
                        <button wire:click="$set('message', '{{ __('Which products are low on stock?') }}')" class="text-left p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm text-gray-700 dark:text-gray-300">
                            {{ __('Which products are low on stock?') }}
                        </button>
                        <button wire:click="$set('message', '{{ __('Show me recent orders') }}')" class="text-left p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm text-gray-700 dark:text-gray-300">
                            {{ __('Show me recent orders') }}
                        </button>
                        <button wire:click="$set('message', '{{ __('List active suppliers') }}')" class="text-left p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm text-gray-700 dark:text-gray-300">
                            {{ __('List active suppliers') }}
                        </button>
                        <button wire:click="$set('message', '{{ __('Stock overview by warehouse') }}')" class="text-left p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm text-gray-700 dark:text-gray-300">
                            {{ __('Stock overview by warehouse') }}
                        </button>
                    </div>
                </div>
            @endif

            @foreach ($messages as $msg)
                <div @class([
                    'flex',
                    'justify-end' => $msg['role'] === 'user',
                    'justify-start' => $msg['role'] === 'assistant',
                ])>
                    @if ($msg['role'] === 'assistant')
                        <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mr-3 mt-1 shrink-0">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                    @endif

                    <div @class([
                        'max-w-[75%] rounded-xl px-4 py-3 text-sm leading-relaxed',
                        'bg-amber-500 text-white' => $msg['role'] === 'user',
                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $msg['role'] === 'assistant',
                    ])>
                        <div class="whitespace-pre-wrap">{!! nl2br(e($msg['content'])) !!}</div>
                    </div>
                </div>
            @endforeach

            @if ($isLoading)
                <div class="flex justify-start">
                    <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mr-3 mt-1 shrink-0">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-xl px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="h-2 w-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="h-2 w-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-200 dark:border-gray-700 p-4 shrink-0">
            <form wire:submit="sendMessage" class="flex gap-3">
                <input
                    wire:model="message"
                    type="text"
                    placeholder="{{ __('Type your message...') }}"
                    class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                    @disabled($isLoading)
                    autocomplete="off"
                />
                <button
                    type="submit"
                    class="rounded-xl bg-amber-500 px-5 py-3 text-white font-medium hover:bg-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center gap-2"
                    @disabled($isLoading)
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    {{ __('Send') }}
                </button>
            </form>
        </div>
    </div>
</div>
