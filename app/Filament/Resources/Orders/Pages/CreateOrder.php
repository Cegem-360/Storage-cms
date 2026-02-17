<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Models\Order;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Wizard\Step;
use Override;

final class CreateOrder extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = OrderResource::class;

    /**
     * @return array<int, Step>
     */
    protected function getSteps(): array
    {
        return [
            Step::make(__('Order Details'))
                ->description(__('Basic order information'))
                ->schema(OrderForm::getOrderInfoFields())
                ->columns(2),

            Step::make(__('Order Items'))
                ->description(__('Add products to the order'))
                ->schema([
                    OrderForm::getOrderLineRepeater(),
                ]),

            Step::make(__('Shipping & Summary'))
                ->description(__('Shipping address and order review'))
                ->schema(OrderForm::getShippingFields())
                ->columns(2),
        ];
    }

    #[Override]
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
