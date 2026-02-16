<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'type' => $this->type,
            'status' => $this->status,
            'order_date' => $this->order_date,
            'delivery_date' => $this->delivery_date,
            'total_amount' => $this->total_amount,
            'notes' => $this->notes,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'orderLines' => OrderLineResource::collection($this->whenLoaded('orderLines')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
