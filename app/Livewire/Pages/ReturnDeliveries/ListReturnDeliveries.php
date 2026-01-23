<?php

declare(strict_types=1);

namespace App\Livewire\Pages\ReturnDeliveries;

use App\Enums\ReturnStatus;
use App\Models\ReturnDelivery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListReturnDeliveries extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'return_date';

    #[Url]
    public string $sortDir = 'desc';

    #[Url]
    public int $perPage = 10;

    #[Url]
    public string $status = '';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.return-deliveries.list-return-deliveries', [
            'returnDeliveries' => $this->getReturnDeliveries(),
            'statuses' => ReturnStatus::cases(),
        ]);
    }

    private function getReturnDeliveries(): LengthAwarePaginator
    {
        return ReturnDelivery::query()
            ->with(['order', 'warehouse', 'processedBy'])
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('return_number', 'like', $search)
                        ->orWhereHas('order', fn ($orderQuery) => $orderQuery->where('order_number', 'like', $search))
                        ->orWhereHas('warehouse', fn ($warehouseQuery) => $warehouseQuery->where('name', 'like', $search));
                });
            })
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
