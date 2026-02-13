<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Supplier;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use UnitEnum;

final class SupplierPerformanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.supplier-performance-report';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::REPORTS;

    protected static ?string $title = 'Supplier Performance';

    protected static ?string $navigationLabel = 'Supplier Performance';

    protected static ?int $navigationSort = 25;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Supplier::query()
                    ->where('is_active', true)
                    ->with(['orders' => function ($query): void {
                        $query->where('type', OrderType::PURCHASE);
                    }])
            )
            ->columns([
                TextColumn::make('company_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_orders')
                    ->label('Total Orders')
                    ->state(fn (Supplier $record): int => $record->orders->count())
                    ->numeric()
                    ->alignEnd(),

                TextColumn::make('completed_orders')
                    ->label('Completed Orders')
                    ->state(fn (Supplier $record): int => $this->completedOrders($record)->count())
                    ->numeric()
                    ->alignEnd(),

                TextColumn::make('on_time_rate')
                    ->label('On-Time Rate')
                    ->state(function (Supplier $record): string {
                        $completed = $this->completedOrders($record);

                        if ($completed->isEmpty()) {
                            return '-';
                        }

                        $onTime = $completed->filter(function ($order): bool {
                            return ! $order->delivery_date || $order->updated_at <= $order->delivery_date->endOfDay();
                        })->count();

                        return number_format(($onTime / $completed->count()) * 100, 1).'%';
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        if ($state === '-') {
                            return 'gray';
                        }

                        $value = (float) mb_rtrim($state, '%');

                        return match (true) {
                            $value >= 90 => 'success',
                            $value >= 70 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->alignCenter(),

                TextColumn::make('avg_lead_time')
                    ->label('Avg. Lead Time')
                    ->state(function (Supplier $record): string {
                        $completed = $this->completedOrders($record)
                            ->filter(fn ($order): bool => $order->order_date && $order->updated_at);

                        if ($completed->isEmpty()) {
                            return '-';
                        }

                        $totalDays = $completed->sum(fn ($order) => $order->order_date->diffInDays($order->updated_at));

                        return number_format($totalDays / $completed->count(), 1).' '.__('days');
                    })
                    ->alignEnd(),

                TextColumn::make('total_order_value')
                    ->label('Total Order Value')
                    ->state(fn (Supplier $record): float => (float) $record->orders->sum('total_amount'))
                    ->money('HUF')
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success'),
            ])
            ->defaultSort('company_name')
            ->paginated([10, 25, 50, 100])
            ->heading(__('Supplier delivery and quality statistics'))
            ->emptyStateHeading(__('No supplier data available'))
            ->emptyStateIcon(Heroicon::OutlinedBuildingOffice);
    }

    private function completedOrders(Supplier $supplier): Collection
    {
        return $supplier->orders->whereIn('status', [OrderStatus::COMPLETED, OrderStatus::DELIVERED]);
    }
}
