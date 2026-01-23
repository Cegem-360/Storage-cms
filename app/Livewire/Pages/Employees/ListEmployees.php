<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Employees;

use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListEmployees extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'last_name';

    #[Url]
    public string $sortDir = 'asc';

    #[Url]
    public int $perPage = 10;

    #[Url]
    public string $activeFilter = '';

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

    public function updatedActiveFilter(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.pages.employees.list-employees', [
            'employees' => $this->getEmployees(),
        ]);
    }

    private function getEmployees(): LengthAwarePaginator
    {
        return Employee::query()
            ->with(['user', 'warehouse'])
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('employee_code', 'like', $search)
                        ->orWhere('position', 'like', $search)
                        ->orWhere('department', 'like', $search);
                });
            })
            ->when($this->activeFilter !== '', fn ($query) => $query->where('is_active', $this->activeFilter === 'active'))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
