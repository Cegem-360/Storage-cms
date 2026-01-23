<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Warehouses;

use App\Filament\Resources\Warehouses\Tables\WarehousesTable;
use App\Models\Warehouse;
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
final class ListWarehouses extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return WarehousesTable::configureDashboard(
            $table->query(Warehouse::query())
        );
    }

    public function render(): View
    {
        return view('livewire.pages.warehouses.list-warehouses');
    }
}
