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
final class StockOverview extends Component
{
    #[Url]
    public ?int $warehouseId = null;

    #[Url]
    public string $search = '';

    public function render(): View
    {
        return view('livewire.pages.reports.stock-overview', [
            'warehouses' => Warehouse::orderBy('name')->get(),
            'stockData' => $this->getStockData(),
        ]);
    }

    private function getStockData(): Collection
    {
        return Stock::query()
            ->with(['product', 'warehouse'])
            ->when($this->warehouseId, fn ($query) => $query->where('warehouse_id', $this->warehouseId))
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('sku', 'like', $search);
                });
            })
            ->get()
            ->groupBy('warehouse_id');
    }
}
