<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Stocks;

use App\Models\Stock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListStocks extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'quantity';

    #[Url]
    public string $sortDir = 'desc';

    #[Url]
    public int $perPage = 10;

    #[Url]
    public string $lowStock = '';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedLowStock(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.stocks.list-stocks', [
            'stocks' => $this->getStocks(),
        ]);
    }

    private function getStocks(): LengthAwarePaginator
    {
        return Stock::query()
            ->with(['product', 'warehouse'])
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->whereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', $search)
                            ->orWhere('sku', 'like', $search);
                    })
                        ->orWhereHas('warehouse', function ($warehouseQuery) use ($search) {
                            $warehouseQuery->where('name', 'like', $search);
                        });
                });
            })
            ->when($this->lowStock === 'yes', function ($query) {
                $query->whereColumn('quantity', '<', 'minimum_quantity');
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
