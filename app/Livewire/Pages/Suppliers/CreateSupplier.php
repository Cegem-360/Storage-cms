<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Suppliers;

use App\Filament\Resources\Suppliers\Schemas\SupplierForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Supplier;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateSupplier extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.suppliers.create-supplier');
    }

    protected static function getModel(): string
    {
        return Supplier::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return SupplierForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.suppliers';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.suppliers.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Supplier';
    }
}
