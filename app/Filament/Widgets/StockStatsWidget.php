<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Team;
use App\Models\User;
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
        $teamThreshold = $this->getTeamThreshold();

        $totalProducts = Product::query()->count();
        $totalStock = (int) Stock::query()->sum('quantity');
        $lowStockCount = Stock::query()
            ->where(function ($query) use ($teamThreshold): void {
                $query->where(function ($q): void {
                    $q->where('minimum_stock', '>', 0)
                        ->whereColumn('quantity', '<=', 'minimum_stock');
                });

                if ($teamThreshold > 0) {
                    $query->orWhere(function ($q) use ($teamThreshold): void {
                        $q->where('minimum_stock', 0)
                            ->where('quantity', '<=', $teamThreshold);
                    });
                }
            })
            ->count();
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

    private function getTeamThreshold(): int
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user?->team_id) {
            return 0;
        }

        $team = Team::query()->with('settings')->find($user->team_id);

        return (int) $team?->getSetting('low_stock_threshold', 0);
    }
}
