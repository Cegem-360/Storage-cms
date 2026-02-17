<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Stocks;

use App\Filament\Resources\Stocks\Schemas\StockForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Stock;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateStock extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.stocks.create-stock');
    }

    protected static function getModel(): string
    {
        return Stock::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return StockForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.stocks';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.stocks.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Stock';
    }
}
