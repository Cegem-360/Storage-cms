<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class StockMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'notes' => $this->notes,
            'product' => new ProductResource($this->whenLoaded('product')),
            'sourceWarehouse' => new WarehouseResource($this->whenLoaded('sourceWarehouse')),
            'targetWarehouse' => new WarehouseResource($this->whenLoaded('targetWarehouse')),
            'batch' => new BatchResource($this->whenLoaded('batch')),
            'executor' => new EmployeeResource($this->whenLoaded('executor')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
