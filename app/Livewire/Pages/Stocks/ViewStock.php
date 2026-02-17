<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Stocks;

use App\Filament\Resources\Stocks\Schemas\StockInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\Stock;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewStock extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.stocks.view-stock');
    }

    protected static function getModel(): string
    {
        return Stock::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.stocks';
    }

    protected static function getResourceLabel(): string
    {
        return 'Stock';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return StockInfolist::class;
    }
}
