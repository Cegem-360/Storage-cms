<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Customers;

use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Livewire\Pages\Concerns\HasEditForm;
use App\Models\Customer;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditCustomer extends Component implements HasActions, HasSchemas
{
    use HasEditForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.customers.edit-customer');
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

    protected static function getResourceLabel(): string
    {
        return 'Customer';
    }
}
