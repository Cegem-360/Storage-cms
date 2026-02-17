<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Employees;

use App\Filament\Resources\Employees\Schemas\EmployeeInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\Employee;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewEmployee extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.employees.view-employee');
    }

    protected static function getModel(): string
    {
        return Employee::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.employees';
    }

    protected static function getResourceLabel(): string
    {
        return 'Employee';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return EmployeeInfolist::class;
    }
}
