<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Customers;

use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\Customer;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewCustomer extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.customers.view-customer');
    }

    protected static function getModel(): string
    {
        return Customer::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.customers';
    }

    protected static function getResourceLabel(): string
    {
        return 'Customer';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return CustomerInfolist::class;
    }
}
