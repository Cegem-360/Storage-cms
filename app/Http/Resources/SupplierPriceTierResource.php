<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class SupplierPriceTierResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'min_quantity' => $this->min_quantity,
            'max_quantity' => $this->max_quantity,
            'price' => $this->price,
        ];
    }
}
