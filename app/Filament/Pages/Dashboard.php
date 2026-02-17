<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\LowStockWidget;
use App\Filament\Widgets\OrderSuggestionWidget;
use App\Filament\Widgets\RecentOrdersWidget;
use App\Filament\Widgets\StockMovementChartWidget;
use App\Filament\Widgets\StockStatsWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use Override;

final class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $title = 'Dashboard';

    #[Override]
    public function getWidgets(): array
    {
        return [
            StockStatsWidget::class,
            StockMovementChartWidget::class,
            LowStockWidget::class,
            RecentOrdersWidget::class,
            OrderSuggestionWidget::class,
        ];
    }

    #[Override]
    public function getColumns(): array
    {
        return [
            'default' => 1,
            'md' => 2,
            'xl' => 3,
        ];
    }
}
