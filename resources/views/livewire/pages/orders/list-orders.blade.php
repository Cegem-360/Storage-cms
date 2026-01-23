<div>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-heading">{{ __('Orders') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage purchase and sales orders') }}</p>
        </div>
        <a href="{{ route('filament.admin.resources.orders.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('New Order') }}
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4 flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search orders...') }}"
                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm">
            </div>
            <div class="sm:w-48">
                <select wire:model.live="status" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm">
                    <option value="">{{ __('All statuses') }}</option>
                    @foreach($statuses as $orderStatus)
                        <option value="{{ $orderStatus->value }}">{{ $orderStatus->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('Order #') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('Supplier/Customer') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('Total') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                        <tr wire:key="order-{{ $order->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 text-sm font-mono text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $order->supplier?->name ?? $order->customer?->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status->getColor() }}">
                                    {{ $order->status->getLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($order->total_amount ?? 0, 0, ',', ' ') }} Ft</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('filament.admin.resources.orders.edit', $order) }}" class="text-amber-600 hover:text-amber-700">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">{{ __('No orders found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
