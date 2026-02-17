<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Inventories;

use App\Filament\Resources\Inventories\Schemas\InventoryForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Inventory;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateInventory extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.inventories.create-inventory');
    }

    protected static function getModel(): string
    {
        return Inventory::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return InventoryForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.inventories';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.inventories.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Inventory';
    }
}
