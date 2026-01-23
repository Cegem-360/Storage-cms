<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Employees;

use App\Filament\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListEmployees extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return EmployeesTable::configureDashboard(
            $table->query(Employee::query()->with(['user', 'warehouse']))
        );
    }

    public function render(): View
    {
        return view('livewire.pages.employees.list-employees');
    }
}
