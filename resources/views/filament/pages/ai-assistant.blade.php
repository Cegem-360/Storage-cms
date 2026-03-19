<x-filament-panels::page>
    {{-- Token usage indicator --}}
    @php $usageInfo = $this->getTokenUsageInfo(); @endphp
    @if ($usageInfo['hasLimit'])
        <div class="mb-4 rounded-xl border border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-1.5 flex items-center justify-between">
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ __('Monthly AI Usage') }}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ number_format($usageInfo['used']) }} / {{ number_format($usageInfo['limit']) }} {{ __('tokens') }}
                </span>
            </div>
            <div class="h-1.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                <div
                    class="h-1.5 rounded-full transition-all {{ $usageInfo['percentage'] >= 90 ? 'bg-red-500' : ($usageInfo['percentage'] >= 70 ? 'bg-amber-500' : 'bg-green-500') }}"
                    style="width: {{ min(100, $usageInfo['percentage']) }}%"
                ></div>
            </div>
            @if ($usageInfo['exceeded'])
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">
                    {{ __('Monthly AI token limit has been reached. Please contact your administrator.') }}
                </p>
            @endif
        </div>
    @endif

    {{-- Chat area --}}
    <div class="flex flex-col rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800" style="height: calc(100vh - 260px); min-height: 400px;">

        {{-- Messages --}}
        <div
            class="flex-1 space-y-4 overflow-y-auto p-6"
            x-ref="chatMessages"
            @ai-message-received.window="$nextTick(() => $refs.chatMessages.scrollTop = $refs.chatMessages.scrollHeight)"
        >
            @if (count($messages) === 0)
                <div class="flex h-full flex-col items-center justify-center text-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100 dark:bg-amber-900/30">
                        <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedSparkles" class="h-8 w-8 text-amber-600 dark:text-amber-400" />
                    </div>
                    <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">{{ __('How can I help you today?') }}</h3>
                    <p class="max-w-md text-sm text-gray-500 dark:text-gray-400">{{ __('I can help you with stock levels, orders, products, and suppliers. Ask me anything about your data!') }}</p>

                    <div class="mt-6 grid w-full max-w-lg grid-cols-1 gap-3 sm:grid-cols-2">
                        <button wire:click="$set('message', '{{ __('Which products are low on stock?') }}')" class="rounded-lg border border-gray-200 p-3 text-left text-sm text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            {{ __('Which products are low on stock?') }}
                        </button>
                        <button wire:click="$set('message', '{{ __('Show me recent orders') }}')" class="rounded-lg border border-gray-200 p-3 text-left text-sm text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            {{ __('Show me recent orders') }}
                        </button>
                        <button wire:click="$set('message', '{{ __('List active suppliers') }}')" class="rounded-lg border border-gray-200 p-3 text-left text-sm text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            {{ __('List active suppliers') }}
                        </button>
                        <button wire:click="$set('message', '{{ __('Stock overview by warehouse') }}')" class="rounded-lg border border-gray-200 p-3 text-left text-sm text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
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
                        <div class="mr-3 mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                            <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedSparkles" class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                        </div>
                    @endif

                    <div @class([
                        'max-w-[75%] rounded-xl px-4 py-3 text-sm leading-relaxed',
                        'bg-primary-500 text-white' => $msg['role'] === 'user',
                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $msg['role'] === 'assistant',
                    ])>
                        <div class="whitespace-pre-wrap">{!! nl2br(e($msg['content'])) !!}</div>
                    </div>
                </div>
            @endforeach

            @if ($isLoading)
                <div class="flex justify-start">
                    <div class="mr-3 mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                        <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedSparkles" class="h-4 w-4 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div class="rounded-xl bg-gray-100 px-4 py-3 dark:bg-gray-700">
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay: 0ms"></div>
                            <div class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay: 150ms"></div>
                            <div class="h-2 w-2 animate-bounce rounded-full bg-gray-400" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Input --}}
        <div class="shrink-0 border-t border-gray-200 p-4 dark:border-gray-700">
            <form wire:submit="sendMessage" class="flex gap-3">
                <input
                    wire:model="message"
                    type="text"
                    placeholder="{{ __('Type your message...') }}"
                    class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                    @disabled($isLoading)
                    autocomplete="off"
                />
                <button
                    type="submit"
                    class="flex items-center gap-2 rounded-xl bg-primary-500 px-5 py-3 font-medium text-white transition hover:bg-primary-600 disabled:cursor-not-allowed disabled:opacity-50"
                    @disabled($isLoading)
                >
                    <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedPaperAirplane" class="h-4 w-4" />
                    {{ __('Send') }}
                </button>
            </form>
        </div>
    </div>

    @if (count($messages) > 0)
        <div class="mt-3 flex justify-end">
            <x-filament::button color="gray" wire:click="clearChat" size="sm">
                {{ __('Clear chat') }}
            </x-filament::button>
        </div>
    @endif
</x-filament-panels::page>
