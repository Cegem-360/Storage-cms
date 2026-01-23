<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Intrastat Dispatches') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Exports to EU member states') }}</p>
        </div>
        <a href="{{ route('filament.admin.resources.intrastat-declarations.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('New Dispatch Declaration') }}
        </a>
    </div>

    {{-- Filament Table --}}
    {{ $this->table }}
</div>
