<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class WarehouseResource extends JsonResource
{
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'address' => $this->address,
            'type' => $this->type,
            'capacity' => $this->capacity,
            'is_active' => $this->is_active,
            'is_consignment' => $this->is_consignment,
            'valuation_method' => $this->valuation_method,
            'manager' => new EmployeeResource($this->whenLoaded('manager')),
            'ownerSupplier' => new SupplierResource($this->whenLoaded('ownerSupplier')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
