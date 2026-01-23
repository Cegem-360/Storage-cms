<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Stocks') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Monitor stock levels across warehouses') }}</p>
        </div>
    </div>

    {{-- Filament Table --}}
    {{ $this->table }}
</div>
