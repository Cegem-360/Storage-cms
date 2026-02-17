<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Customers;

use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Customer;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateCustomer extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.customers.create-customer');
    }

    protected static function getModel(): string
    {
        return Customer::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return CustomerForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.customers';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.customers.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Customer';
    }
}
