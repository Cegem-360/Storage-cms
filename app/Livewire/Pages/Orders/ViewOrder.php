<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Orders;

use App\Filament\Resources\Orders\Schemas\OrderInfolist;
use App\Livewire\Pages\Concerns\HasViewInfolist;
use App\Models\Order;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ViewOrder extends Component implements HasActions, HasSchemas
{
    use HasViewInfolist;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.orders.view-order');
    }

    protected static function getModel(): string
    {
        return Order::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.orders';
    }

    protected static function getResourceLabel(): string
    {
        return 'Order';
    }

    protected static function getInfolistSchemaClass(): string
    {
        return OrderInfolist::class;
    }

    /** @return Builder<Order> */
    protected function getRecordQuery(): Builder
    {
        return Order::query()->with(['orderLines.product', 'receipts']);
    }
}
