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
        </div>
    @endif

    <div
        class="flex gap-4"
        style="height: calc(100vh - 240px); min-height: 450px;"
        x-data="{
            streaming: false,
            streamedText: '',
            init() {
                if (typeof window.Echo !== 'undefined') {
                    window.Echo.private('ai-chat.{{ \Illuminate\Support\Facades\Auth::id() }}')
                        .listen('.ai.stream.delta', (e) => {
                            if (!this.streaming) {
                                this.streaming = true;
                                this.streamedText = '';
                            }
                            this.streamedText += e.delta || '';
                            this.$nextTick(() => {
                                this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                            });
                        })
                        .listen('.ai.stream.complete', (e) => {
                            this.streaming = false;
                            this.streamedText = '';
                            $wire.$refresh();
                        });
                }
            }
        }"
    >

        {{-- Sidebar: conversations --}}
        <div class="hidden w-64 shrink-0 flex-col rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 lg:flex">
            <div class="border-b border-gray-200 p-3 dark:border-gray-700">
                <button
                    wire:click="newConversation"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-500 px-3 py-2 text-sm font-medium text-white transition hover:bg-primary-600"
                >
                    <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedPlus" class="h-4 w-4" />
                    {{ __('New conversation') }}
                </button>
            </div>
            <div class="flex-1 space-y-1 overflow-y-auto p-2">
                @php $conversations = $this->getConversations(); @endphp
                @forelse ($conversations as $conv)
                    <button
                        wire:click="loadConversation('{{ $conv->id }}')"
                        @class([
                            'w-full rounded-lg px-3 py-2 text-left text-sm transition',
                            'bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-400' => $conversationId === $conv->id,
                            'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' => $conversationId !== $conv->id,
                        ])
                    >
                        <div class="truncate font-medium">{{ $conv->title ?: __('Conversation') }}</div>
                        <div class="mt-0.5 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ \Carbon\Carbon::parse($conv->created_at)->diffForHumans() }}</span>
                            <span>&middot;</span>
                            <span>{{ $conv->message_count }} {{ __('messages') }}</span>
                        </div>
                    </button>
                @empty
                    <p class="px-3 py-4 text-center text-xs text-gray-400 dark:text-gray-500">
                        {{ __('No previous conversations') }}
                    </p>
                @endforelse
            </div>
        </div>

        {{-- Chat area --}}
        <div class="flex flex-1 flex-col rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Mobile: new conversation --}}
            <div class="flex items-center justify-between border-b border-gray-200 p-3 dark:border-gray-700 lg:hidden">
                <button
                    wire:click="newConversation"
                    class="flex items-center gap-1.5 rounded-lg bg-primary-500 px-3 py-1.5 text-xs font-medium text-white"
                >
                    <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedPlus" class="h-3.5 w-3.5" />
                    {{ __('New') }}
                </button>
                @if (count($messages) > 0)
                    <button wire:click="clearChat" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        {{ __('Clear chat') }}
                    </button>
                @endif
            </div>

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

                {{-- Streaming response --}}
                <template x-if="streaming">
                    <div class="flex justify-start">
                        <div class="mr-3 mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                            <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedSparkles" class="h-4 w-4 animate-pulse text-amber-600 dark:text-amber-400" />
                        </div>
                        <div class="max-w-[75%] rounded-xl bg-gray-100 px-4 py-3 text-sm leading-relaxed text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            <div class="whitespace-pre-wrap" x-text="streamedText"></div>
                            <span class="inline-block h-4 w-1 animate-pulse bg-gray-400"></span>
                        </div>
                    </div>
                </template>

                {{-- Loading indicator (wire:loading fallback) --}}
                <div wire:loading wire:target="sendMessage" x-show="!streaming" class="flex justify-start">
                    <div class="mr-3 mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                        <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedSparkles" class="h-4 w-4 animate-spin text-amber-600 dark:text-amber-400" />
                    </div>
                    <div class="rounded-xl bg-gray-100 px-4 py-3 dark:bg-gray-700">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ __('Thinking...') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input --}}
            <div class="shrink-0 border-t border-gray-200 p-4 dark:border-gray-700">
                <form wire:submit="sendMessage" class="flex gap-3">
                    <input
                        wire:model="message"
                        type="text"
                        placeholder="{{ __('Type your message...') }}"
                        class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                        autocomplete="off"
                        wire:loading.attr="disabled"
                        wire:target="sendMessage"
                        x-bind:disabled="streaming"
                    />
                    <button
                        type="submit"
                        class="flex items-center gap-2 rounded-xl bg-primary-500 px-5 py-3 font-medium text-white transition hover:bg-primary-600 disabled:cursor-not-allowed disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="sendMessage"
                        x-bind:disabled="streaming"
                    >
                        <span wire:loading.remove wire:target="sendMessage" x-show="!streaming">
                            <x-filament::icon :icon="Filament\Support\Icons\Heroicon::OutlinedPaperAirplane" class="h-4 w-4" />
                        </span>
                        <svg wire:loading wire:target="sendMessage" x-show="streaming" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Send') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-filament-panels::page>
