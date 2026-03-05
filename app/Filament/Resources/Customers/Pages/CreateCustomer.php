<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

final class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    public static function syncShippingAddress(array $data): array
    {
        if (! empty($data['same_as_billing'])) {
            $data['shipping_address'] = $data['billing_address'] ?? [];
        }

        unset($data['same_as_billing']);

        return $data;
    }

    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return self::syncShippingAddress($data);
    }
}
