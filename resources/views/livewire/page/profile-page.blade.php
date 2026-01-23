<div>
    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('My Profile') }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage your account and security settings') }}</p>
    </div>

    <div class="space-y-6">
        {{-- Profile Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Profile Information') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Update your account profile information.') }}</p>

            {{-- Success message --}}
            @if (session('profile_success'))
                <div class="mb-6 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('profile_success') }}</p>
                    </div>
                </div>
            @endif

            <form wire:submit="updateProfile" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Full name') }}
                        </label>
                        <input
                            type="text"
                            id="name"
                            wire:model="name"
                            class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Work email') }}
                        </label>
                        <input
                            type="email"
                            id="email"
                            wire:model="email"
                            class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                        >
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition"
                    >
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('Update Password') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>

            {{-- Success message --}}
            @if (session('password_success'))
                <div class="mb-6 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('password_success') }}</p>
                    </div>
                </div>
            @endif

            <form wire:submit="updatePassword" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Current Password --}}
                    <div>
                        <label for="currentPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Current Password') }}
                        </label>
                        <input
                            type="password"
                            id="currentPassword"
                            wire:model="currentPassword"
                            class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                        >
                        @error('currentPassword')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('New Password') }}
                        </label>
                        <input
                            type="password"
                            id="password"
                            wire:model="password"
                            class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                        >
                        @error('password')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Confirm Password') }}
                        </label>
                        <input
                            type="password"
                            id="passwordConfirmation"
                            wire:model="passwordConfirmation"
                            class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                        >
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition"
                    >
                        {{ __('Update Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
