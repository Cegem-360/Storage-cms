<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Override;

final class StockStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    #[Override]
    protected function getStats(): array
    {
        $totalProducts = Product::query()->count();
        $totalStock = (int) Stock::query()->sum('quantity');
        $lowStockCount = Stock::query()->whereColumn('quantity', '<=', 'minimum_stock')->count();
        $pendingOrders = Order::query()->where('type', OrderType::PURCHASE)
            ->whereIn('status', [OrderStatus::CONFIRMED, OrderStatus::PROCESSING, OrderStatus::SHIPPED])
            ->count();

        $monthlyPurchaseCost = Order::query()->where('type', OrderType::PURCHASE)
            ->whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->sum('total_amount');

        $inventoryValue = Stock::query()
            ->selectRaw('COALESCE(SUM(quantity * unit_cost), 0) as total')
            ->value('total');

        $needReorder = Product::query()
            ->whereHas('stocks')
            ->get()
            ->filter(fn (Product $p): bool => $p->needsReorder())
            ->count();

        return [
            Stat::make(__('Total Products'), number_format($totalProducts))
                ->description(__('Active SKUs in system'))
                ->descriptionIcon(Heroicon::OutlinedCube)
                ->color('primary'),

            Stat::make(__('Total Stock'), number_format($totalStock))
                ->description(__('Units across all warehouses'))
                ->descriptionIcon(Heroicon::OutlinedArchiveBox)
                ->color('success'),

            Stat::make(__('Low Stock Alerts'), number_format($lowStockCount))
                ->description(__('Products below minimum'))
                ->descriptionIcon(Heroicon::OutlinedExclamationTriangle)
                ->color($lowStockCount > 0 ? 'danger' : 'success'),

            Stat::make(__('Pending Orders'), number_format($pendingOrders))
                ->description(__('Awaiting delivery'))
                ->descriptionIcon(Heroicon::OutlinedTruck)
                ->color('info'),

            Stat::make(__('Monthly Purchase Cost'), Number::currency((float) $monthlyPurchaseCost, in: 'HUF', locale: 'hu', precision: 0))
                ->description(__("This month's purchases"))
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('warning'),

            Stat::make(__('Inventory Value'), Number::currency((float) $inventoryValue, in: 'HUF', locale: 'hu', precision: 0))
                ->description(__('Total stock value'))
                ->descriptionIcon(Heroicon::OutlinedCalculator)
                ->color('success'),

            Stat::make(__('Need Reorder'), number_format($needReorder))
                ->description(__('Below reorder point'))
                ->descriptionIcon(Heroicon::OutlinedArrowPath)
                ->color($needReorder > 0 ? 'warning' : 'success'),
        ];
    }
}
