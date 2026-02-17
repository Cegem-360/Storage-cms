<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Receipts;

use App\Filament\Resources\Receipts\Schemas\ReceiptForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Receipt;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateReceipt extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.receipts.create-receipt');
    }

    protected static function getModel(): string
    {
        return Receipt::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return ReceiptForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.receipts';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.receipts.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Receipt';
    }
}
