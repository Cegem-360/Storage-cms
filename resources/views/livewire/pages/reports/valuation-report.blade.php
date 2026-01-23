<div>
    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Valuation Report') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Stock valuation based on standard cost') }}</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Quantity') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totals['total_quantity'], 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Value') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totals['total_value'], 0, ',', ' ') }} Ft</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            <div class="sm:w-48">
                <select wire:model.live="warehouseId" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">{{ __('All warehouses') }}</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:w-48">
                <select wire:model.live="groupBy" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="warehouse">{{ __('Group by Warehouse') }}</option>
                    <option value="category">{{ __('Group by Category') }}</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Valuation Data --}}
    @forelse($valuationData as $groupId => $group)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            {{-- Group Header --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 {{ $groupBy === 'warehouse' ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-purple-100 dark:bg-purple-900/30' }} rounded-lg flex items-center justify-center">
                            @if($groupBy === 'warehouse')
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $group['name'] }}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $group['items']->count() }} {{ __('products') }} &middot;
                                {{ number_format($group['total_quantity'], 0, ',', ' ') }} {{ __('units') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Value') }}</p>
                        <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($group['total_value'], 0, ',', ' ') }} Ft</p>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/30">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('SKU') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Product') }}</th>
                            @if($groupBy === 'category')
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Warehouse') }}</th>
                            @endif
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Quantity') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Unit Cost') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('Total Value') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($group['items'] as $stock)
                            @php
                                $unitCost = $stock->product->standard_cost ?? 0;
                                $totalValue = $stock->quantity * $unitCost;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 text-sm font-mono text-gray-600 dark:text-gray-300">{{ $stock->product?->sku ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $stock->product?->name ?? '-' }}</td>
                                @if($groupBy === 'category')
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $stock->warehouse?->name ?? '-' }}</td>
                                @endif
                                <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">{{ number_format($stock->quantity, 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 text-sm text-right text-gray-600 dark:text-gray-300">{{ number_format($unitCost, 0, ',', ' ') }} Ft</td>
                                <td class="px-6 py-4 text-sm text-right font-medium text-gray-900 dark:text-white">{{ number_format($totalValue, 0, ',', ' ') }} Ft</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <td colspan="{{ $groupBy === 'category' ? '5' : '4' }}" class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 text-right">{{ __('Subtotal') }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-right text-gray-900 dark:text-white">{{ number_format($group['total_value'], 0, ',', ' ') }} Ft</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12">
            <div class="flex flex-col items-center">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">{{ __('No valuation data found') }}</p>
                @if($warehouseId)
                    <button wire:click="$set('warehouseId', null)" class="mt-2 text-sm text-amber-600 dark:text-amber-400 hover:underline">
                        {{ __('Clear filter') }}
                    </button>
                @endif
            </div>
        </div>
    @endforelse
</div>
