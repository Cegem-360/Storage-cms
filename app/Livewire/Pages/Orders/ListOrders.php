<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Orders;

use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListOrders extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return OrdersTable::configureDashboard(
            $table->query(Order::query()->with(['supplier', 'customer']))
        );
    }

    public function render(): View
    {
        return view('livewire.pages.orders.list-orders');
    }
}
