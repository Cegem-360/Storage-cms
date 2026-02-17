<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Orders;

use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Livewire\Pages\Concerns\HasCreateForm;
use App\Models\Order;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class CreateOrder extends Component implements HasActions, HasSchemas
{
    use HasCreateForm;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function render(): View
    {
        return view('livewire.pages.orders.create-order');
    }

    protected static function getModel(): string
    {
        return Order::class;
    }

    protected static function getFormSchemaClass(): string
    {
        return OrderForm::class;
    }

    protected static function getListRouteName(): string
    {
        return 'dashboard.orders';
    }

    protected static function getEditRouteName(): string
    {
        return 'dashboard.orders.edit';
    }

    protected static function getResourceLabel(): string
    {
        return 'Order';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['order_number']) && Order::query()->where('order_number', $data['order_number'])->exists()) {
            $data['order_number'] = $this->generateOrderNumber();
        }

        return $data;
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.now()->format('Ymd').'-'.mb_strtoupper(mb_substr(bin2hex(random_bytes(3)), 0, 6));
        } while (Order::query()->where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
