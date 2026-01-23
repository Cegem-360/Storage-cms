<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Customers;

use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
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
final class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return CustomersTable::configureDashboard(
            $table->query(Customer::query())
        );
    }

    public function render(): View
    {
        return view('livewire.pages.customers.list-customers');
    }
}
