<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Reports;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ExpectedArrivals extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 10;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.reports.expected-arrivals', [
            'orders' => $this->getOrders(),
        ]);
    }

    private function getOrders(): LengthAwarePaginator
    {
        return Order::query()
            ->with(['supplier', 'items.product'])
            ->where('type', OrderType::PURCHASE)
            ->whereIn('status', [OrderStatus::CONFIRMED, OrderStatus::PROCESSING])
            ->whereNotNull('expected_delivery_date')
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', $search)
                        ->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                            $supplierQuery->where('name', 'like', $search);
                        });
                });
            })
            ->orderBy('expected_delivery_date', 'asc')
            ->paginate($this->perPage);
    }
}
