<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Intrastat;

use App\Enums\IntrastatDirection;
use App\Enums\IntrastatStatus;
use App\Models\IntrastatDeclaration;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListOutbounds extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'declaration_date';

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

    public function render(): View
    {
        return view('livewire.pages.intrastat.list-outbounds', [
            'declarations' => $this->getDeclarations(),
            'statuses' => IntrastatStatus::cases(),
        ]);
    }

    private function getDeclarations(): LengthAwarePaginator
    {
        return IntrastatDeclaration::query()
            ->where('direction', IntrastatDirection::DISPATCH)
            ->withCount('intrastatLines')
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('declaration_number', 'like', $search);
                });
            })
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
