<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Warehouses;

use App\Filament\Resources\Warehouses\Schemas\WarehouseInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\Warehouse;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewWarehouse extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.warehouses.view-warehouse');
    }

    protected static function getModel(): string
    {
        return Warehouse::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.warehouses';
    }

    protected static function getResourceLabel(): string
    {
        return 'Warehouse';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return WarehouseInfolist::class;
    }
}
