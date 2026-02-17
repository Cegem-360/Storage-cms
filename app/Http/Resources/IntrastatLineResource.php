<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class IntrastatLineResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cn_code' => $this->cn_code,
            'country_of_origin' => $this->country_of_origin,
            'country_of_consignment' => $this->country_of_consignment,
            'region_code' => $this->region_code,
            'transaction_type' => $this->transaction_type,
            'transport_mode' => $this->transport_mode,
            'delivery_terms' => $this->delivery_terms,
            'quantity' => $this->quantity,
            'net_mass' => $this->net_mass,
            'supplementary_quantity' => $this->supplementary_quantity,
            'invoice_value' => $this->invoice_value,
            'statistical_value' => $this->statistical_value,
            'product' => new ProductResource($this->whenLoaded('product')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'created_at' => $this->created_at,
        ];
    }
}
