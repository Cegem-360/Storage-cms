<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class StockStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $totalStock = Stock::sum('quantity');
        $lowStockCount = Stock::where('quantity', '<=', Stock::raw('minimum_stock'))->count();
        $pendingOrders = Order::where('type', OrderType::PURCHASE)
            ->whereIn('status', [OrderStatus::CONFIRMED, OrderStatus::PROCESSING, OrderStatus::SHIPPED])
            ->count();

        return [
            Stat::make(__('Total Products'), number_format($totalProducts))
                ->description(__('Active SKUs in system'))
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary'),

            Stat::make(__('Total Stock'), number_format($totalStock))
                ->description(__('Units across all warehouses'))
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('success'),

            Stat::make(__('Low Stock Alerts'), number_format($lowStockCount))
                ->description(__('Products below minimum'))
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),

            Stat::make(__('Pending Orders'), number_format($pendingOrders))
                ->description(__('Awaiting delivery'))
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),
        ];
    }
}
