<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Reports;

use App\Models\Stock;
use App\Models\Warehouse;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class StockOverview extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Stock::query()
                    ->with(['product', 'warehouse'])
            )
            ->columns([
                TextColumn::make('product.sku')
                    ->label(__('SKU'))
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),

                TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('warehouse.name')
                    ->label(__('Warehouse'))
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('minimum_stock')
                    ->label(__('Minimum'))
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('stock_status')
                    ->label(__('Status'))
                    ->state(fn (Stock $record): string => $record->isLowStock() ? __('Low Stock') : __('OK'))
                    ->badge()
                    ->color(fn (Stock $record): string => $record->isLowStock() ? 'danger' : 'success'),
            ])
            ->filters([
                SelectFilter::make('warehouse_id')
                    ->label(__('Warehouse'))
                    ->options(Warehouse::query()->orderBy('name')->pluck('name', 'id'))
                    ->placeholder(__('All warehouses')),
            ])
            ->groups([
                Group::make('warehouse.name')
                    ->label(__('Warehouse')),
            ])
            ->defaultGroup('warehouse.name')
            ->defaultSort('product.name')
            ->striped()
            ->heading(__('Stock Overview'))
            ->description(__('Current stock levels grouped by warehouse'))
            ->emptyStateHeading(__('No stock data found'))
            ->paginated([10, 25, 50, 100]);
    }

    public function render(): View
    {
        return view('livewire.pages.reports.stock-overview');
    }
}
