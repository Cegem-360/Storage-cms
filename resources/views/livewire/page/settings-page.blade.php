<div>
    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('System Settings') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Configure system-wide settings and preferences') }}</p>
    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="mb-6 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Inventory Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Inventory Settings') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Configure inventory management preferences') }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Low Stock Threshold --}}
                <div>
                    <label for="lowStockThreshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Low Stock Threshold') }}
                    </label>
                    <input
                        type="number"
                        id="lowStockThreshold"
                        wire:model="lowStockThreshold"
                        min="1"
                        class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                    >
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Default threshold for low stock alerts') }}</p>
                    @error('lowStockThreshold')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Auto Reorder Toggle --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Enable Auto Reorder') }}
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="autoReorderEnabled"
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-amber-500"></div>
                        <span class="ml-3 text-sm text-gray-600 dark:text-gray-400">
                            {{ $autoReorderEnabled ? __('Enabled') : __('Disabled') }}
                        </span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Automatically create purchase orders when stock falls below reorder point') }}</p>
                </div>
            </div>
        </div>

        {{-- Notification Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Notification Settings') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Configure system notification preferences') }}</p>

            <div class="max-w-md">
                <label for="notificationEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Notification Email') }}
                </label>
                <input
                    type="email"
                    id="notificationEmail"
                    wire:model="notificationEmail"
                    placeholder="admin@example.com"
                    class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                >
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Email address for system alerts') }}</p>
                @error('notificationEmail')
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- AI Assistant Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('AI Assistant Settings') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Configure AI assistant provider and credentials') }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- AI Provider --}}
                <div>
                    <label for="aiProvider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('AI Provider') }}
                    </label>
                    <select
                        id="aiProvider"
                        wire:model="aiProvider"
                        class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                    >
                        <option value="openai">OpenAI</option>
                        <option value="anthropic">Anthropic</option>
                        <option value="gemini">Google Gemini</option>
                        <option value="groq">Groq</option>
                        <option value="deepseek">DeepSeek</option>
                        <option value="mistral">Mistral</option>
                        <option value="xai">xAI (Grok)</option>
                        <option value="openrouter">OpenRouter</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Select the AI service provider') }}</p>
                    @error('aiProvider')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- AI Model --}}
                <div>
                    <label for="aiModel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('AI Model') }}
                    </label>
                    <input
                        type="text"
                        id="aiModel"
                        wire:model="aiModel"
                        placeholder="gpt-4o-mini"
                        class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                    >
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Model identifier (e.g. gpt-4o-mini, claude-sonnet-4-5-20250929)') }}</p>
                    @error('aiModel')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- AI API Key --}}
                <div class="md:col-span-2">
                    <label for="aiApiKey" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('API Key') }}
                    </label>
                    <input
                        type="password"
                        id="aiApiKey"
                        wire:model="aiApiKey"
                        placeholder="sk-..."
                        class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                    >
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Your API key for the selected provider. This is stored securely per team.') }}</p>
                    @error('aiApiKey')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Save button --}}
        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save Settings') }}
            </button>
        </div>
    </form>
</div>
