<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Warehouses;

use App\Filament\Resources\Warehouses\Schemas\WarehouseForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Warehouse;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateWarehouse extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.warehouses.create-warehouse');
    }

    protected static function getModel(): string
    {
        return Warehouse::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return WarehouseForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.warehouses';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.warehouses.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Warehouse';
    }
}
