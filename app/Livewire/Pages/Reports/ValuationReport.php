<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Reports;

use App\Models\Stock;
use App\Models\Team;
use App\Models\Warehouse;
use App\Services\Inventory\InventoryValuationService;
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
final class ValuationReport extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $service = resolve(InventoryValuationService::class);

        return $table
            ->query(
                Stock::query()
                    ->with(['product.category', 'warehouse'])
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

                TextColumn::make('product.category.name')
                    ->label(__('Category'))
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('unit_cost_display')
                    ->label(__('Unit Cost'))
                    ->state(fn (Stock $record): float => $this->getUnitCost($record))
                    ->money(Team::currency())
                    ->alignEnd(),

                TextColumn::make('total_value_display')
                    ->label(__('Total Value'))
                    ->state(fn (Stock $record): float => $record->quantity * $this->getUnitCost($record))
                    ->money(Team::currency())
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success'),
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
                Group::make('product.category.name')
                    ->label(__('Category')),
            ])
            ->defaultGroup('warehouse.name')
            ->defaultSort('product.name')
            ->striped()
            ->heading(__('Valuation Report'))
            ->description(__('Stock valuation based on standard cost'))
            ->emptyStateHeading(__('No valuation data found'))
            ->paginated([10, 25, 50, 100]);
    }

    public function render(): View
    {
        return view('livewire.pages.reports.valuation-report');
    }

    private function getUnitCost(Stock $stock): float
    {
        if ($stock->unit_cost > 0) {
            return (float) $stock->unit_cost;
        }

        if ($stock->product?->standard_cost !== null) {
            return (float) $stock->product->standard_cost;
        }

        return (float) ($stock->product?->price ?? 0);
    }
}
