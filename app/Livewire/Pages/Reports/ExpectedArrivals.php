<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Reports;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ExpectedArrivals extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['supplier', 'orderLines.product'])
                    ->where('type', OrderType::PURCHASE)
                    ->whereIn('status', [OrderStatus::CONFIRMED, OrderStatus::PROCESSING])
                    ->whereNotNull('delivery_date')
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('Order #'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supplier.name')
                    ->label(__('Supplier'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('orderLines')
                    ->label(__('Items'))
                    ->state(fn (Order $record): string => $record->orderLines->count().' '.__('items'))
                    ->badge()
                    ->color('gray'),

                TextColumn::make('delivery_date')
                    ->label(__('Expected Delivery'))
                    ->date()
                    ->sortable()
                    ->badge()
                    ->color(function (Order $record): string {
                        $daysUntil = (int) now()->diffInDays($record->delivery_date, false);

                        return match (true) {
                            $daysUntil < 0 => 'danger',
                            $daysUntil === 0 => 'warning',
                            $daysUntil <= 3 => 'info',
                            default => 'success',
                        };
                    }),

                TextColumn::make('days_until')
                    ->label(__('Days Until'))
                    ->state(function (Order $record): string {
                        $daysUntil = (int) now()->diffInDays($record->delivery_date, false);

                        return match (true) {
                            $daysUntil < 0 => abs($daysUntil).' '.__('days overdue'),
                            $daysUntil === 0 => __('Today'),
                            default => $daysUntil.' '.__('days'),
                        };
                    })
                    ->badge()
                    ->color(function (Order $record): string {
                        $daysUntil = (int) now()->diffInDays($record->delivery_date, false);

                        return match (true) {
                            $daysUntil < 0 => 'danger',
                            $daysUntil === 0 => 'warning',
                            $daysUntil <= 3 => 'info',
                            default => 'gray',
                        };
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(OrderStatus::class),
            ])
            ->recordUrl(fn (Order $record): string => route('dashboard.orders.view', $record))
            ->defaultSort('delivery_date', 'asc')
            ->striped()
            ->heading(__('Expected Arrivals'))
            ->description(__('Pending purchase orders with expected delivery dates'))
            ->emptyStateHeading(__('No pending arrivals found'))
            ->paginated([10, 25, 50]);
    }

    public function render(): View
    {
        return view('livewire.pages.reports.expected-arrivals');
    }
}
