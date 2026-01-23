<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Return Deliveries') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage customer and supplier returns') }}</p>
        </div>
        <a href="{{ route('filament.admin.resources.return-deliveries.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('New Return') }}
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search returns...') }}"
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                </div>
            </div>
            <div class="sm:w-48">
                <select wire:model.live="status" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white">
                    <option value="">{{ __('All statuses') }}</option>
                    @foreach($statuses as $statusOption)
                        <option value="{{ $statusOption->value }}">{{ $statusOption->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:w-32">
                <select wire:model.live="perPage" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sort('return_number')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                {{ __('Return #') }}
                                @if($sortBy === 'return_number')
                                    <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Order') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Warehouse') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sort('return_date')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200">
                                {{ __('Date') }}
                                @if($sortBy === 'return_date')
                                    <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right">
                            <button wire:click="sort('total_amount')" class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-200 ml-auto">
                                {{ __('Amount') }}
                                @if($sortBy === 'total_amount')
                                    <svg class="w-4 h-4 {{ $sortDir === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($returnDeliveries as $return)
                        <tr wire:key="return-{{ $return->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 text-sm font-mono text-gray-900 dark:text-white">{{ $return->return_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $return->order?->order_number ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $return->warehouse?->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($return->status->getColor())
                                        @case('success') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 @break
                                        @case('danger') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 @break
                                        @case('warning') bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400 @break
                                        @case('info') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 @break
                                        @case('yellow') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 @break
                                        @case('blue') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 @break
                                        @default bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                    @endswitch">
                                    {{ $return->status->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $return->return_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-right font-medium text-gray-900 dark:text-white">{{ number_format($return->total_amount ?? 0, 0, ',', ' ') }} Ft</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('filament.admin.resources.return-deliveries.edit', $return) }}" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">{{ __('No return deliveries found') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($returnDeliveries->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $returnDeliveries->links() }}</div>
        @endif
    </div>

    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
        {{ __('Showing') }} {{ $returnDeliveries->firstItem() ?? 0 }} {{ __('to') }} {{ $returnDeliveries->lastItem() ?? 0 }} {{ __('of') }} {{ $returnDeliveries->total() }} {{ __('results') }}
    </div>
</div>
