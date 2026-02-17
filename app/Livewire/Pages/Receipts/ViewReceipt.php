<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Receipts;

use App\Filament\Resources\Receipts\Schemas\ReceiptInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\Receipt;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewReceipt extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.receipts.view-receipt');
    }

    protected static function getModel(): string
    {
        return Receipt::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.receipts';
    }

    protected static function getResourceLabel(): string
    {
        return 'Receipt';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return ReceiptInfolist::class;
    }

    /** @return Builder<Receipt> */
    protected function getRecordQuery(): Builder
    {
        return Receipt::query()->with(['receiptLines.product', 'order']);
    }
}
