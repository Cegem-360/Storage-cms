<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
final class ListUsers extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'name';

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
        return view('livewire.pages.users.list-users', [
            'users' => $this->getUsers(),
        ]);
    }

    private function getUsers(): LengthAwarePaginator
    {
        return User::query()
            ->with('employee')
            ->when($this->search !== '', function ($query) {
                $search = '%'.$this->search.'%';
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->when($this->activeFilter !== '', fn ($query) => $query->where('is_active', $this->activeFilter === 'active'))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }
}
