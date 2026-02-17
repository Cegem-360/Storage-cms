<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class SupplierPriceResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'currency' => $this->currency,
            'minimum_order_quantity' => $this->minimum_order_quantity,
            'lead_time_days' => $this->lead_time_days,
            'valid_from' => $this->valid_from,
            'valid_until' => $this->valid_until,
            'is_active' => $this->is_active,
            'notes' => $this->notes,
            'product' => new ProductResource($this->whenLoaded('product')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'tiers' => SupplierPriceTierResource::collection($this->whenLoaded('tiers')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
