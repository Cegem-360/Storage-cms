<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Employees;

use App\Filament\Resources\Employees\Schemas\EmployeeForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Employee;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateEmployee extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.employees.create-employee');
    }

    protected static function getModel(): string
    {
        return Employee::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return EmployeeForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.employees';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.employees.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Employee';
    }
}
