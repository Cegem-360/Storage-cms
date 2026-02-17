<div>
    @include('livewire.pages._partials._page-header', [
        'title' => $title,
        'subtitle' => $subtitle ?? null,
        'backUrl' => $backUrl,
    ])

    <form wire:submit="create">
        {{ $this->form }}

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ $backUrl }}"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                {{ __('Cancel') }}
            </a>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition">
                {{ __('Create') }}
            </button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
