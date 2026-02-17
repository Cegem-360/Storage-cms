<?php

declare(strict_types=1);

namespace App\Livewire\Pages\ReturnDeliveries;

use App\Filament\Resources\ReturnDeliveries\Schemas\ReturnDeliveryForm;
use App\Livewire\Pages\Concerns\HasEditForm;
use App\Models\ReturnDelivery;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditReturnDelivery extends Component implements HasActions, HasSchemas
{
    use HasEditForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.return-deliveries.edit-return-delivery');
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

    protected static function getResourceLabel(): string
    {
        return 'Return Delivery';
    }

    /** @return Builder<ReturnDelivery> */
    protected function getRecordQuery(): Builder
    {
        return ReturnDelivery::query()->with(['returnDeliveryLines.product']);
    }
}
