<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Reports;

use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ValuationReport extends Component
{
    #[Url]
    public ?int $warehouseId = null;

    #[Url]
    public string $groupBy = 'warehouse';

    public function render(): View
    {
        return view('livewire.pages.reports.valuation-report', [
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'valuationData' => $this->getValuationData(),
            'totals' => $this->getTotals(),
        ]);
    }

    private function getValuationData(): Collection
    {
        $query = Stock::query()
            ->with(['product', 'warehouse'])
            ->when($this->warehouseId, fn ($query) => $query->where('warehouse_id', $this->warehouseId));

        $stocks = $query->get();

        if ($this->groupBy === 'warehouse') {
            return $stocks->groupBy('warehouse_id')->map(function ($items, $warehouseId): array {
                $warehouse = $items->first()->warehouse;

                return [
                    'name' => $warehouse?->name ?? 'Unknown',
                    'total_quantity' => $items->sum('quantity'),
                    'total_value' => $items->sum(fn (Stock $stock): float => $stock->quantity * $this->getUnitCost($stock)),
                    'items' => $items,
                ];
            });
        }

        return $stocks->groupBy(fn ($stock) => $stock->product?->category_id)->map(function ($items, $categoryId): array {
            $category = $items->first()->product?->category;

            return [
                'name' => $category?->name ?? 'Uncategorized',
                'total_quantity' => $items->sum('quantity'),
                'total_value' => $items->sum(fn (Stock $stock): float => $stock->quantity * $this->getUnitCost($stock)),
                'items' => $items,
            ];
        });
    }

    private function getTotals(): array
    {
        $query = Stock::query()
            ->with('product')
            ->when($this->warehouseId, fn ($query) => $query->where('warehouse_id', $this->warehouseId));

        $stocks = $query->get();

        return [
            'total_quantity' => $stocks->sum('quantity'),
            'total_value' => $stocks->sum(fn (Stock $stock): float => $stock->quantity * $this->getUnitCost($stock)),
        ];
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
