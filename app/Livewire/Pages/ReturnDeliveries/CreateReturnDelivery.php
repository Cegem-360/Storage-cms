<?php

declare(strict_types=1);

namespace App\Livewire\Pages\ReturnDeliveries;

use App\Filament\Resources\ReturnDeliveries\Schemas\ReturnDeliveryForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\ReturnDelivery;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateReturnDelivery extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.return-deliveries.create-return-delivery');
    }

    protected static function getModel(): string
    {
        return ReturnDelivery::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return ReturnDeliveryForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.return-deliveries';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.return-deliveries.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Return Delivery';
    }
}
